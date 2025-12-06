import { BadRequestException, Injectable, NotFoundException } from "@nestjs/common";
import { Prisma } from "@prisma/client";
import { PrismaService } from "../../../prisma.service";
import {
  ITEM_REFRESH,
  ITEM_TRAVEL_OR_BREAK,
  ITEM_VISIT,
} from "../utils/itinerary.constants";
import {
  buildTravellers,
  combineDateOnlyAndTime,
  computeEntryCost,
  computeTravelParity,
  dateTimeToPrismaTime,
  phpDayOfWeekNumericFromDateOnly,
  secondsToPrismaTime,
  timeToSeconds,
  toFloat,
  uniqueTokens,
  resolveTimingForDay,
} from "../utils/itinerary.utils";

@Injectable()
export class ItineraryHotspotsEngine {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Rebuild hotspot rows for a plan (and optionally a single route)
   * to mirror PHP ajax_latest_manage_itineary_opt.php behaviour:
   *
   * - Clear all hotspot-related tables for that plan+route:
   *   - dvi_itinerary_route_hotspot_details
   *   - dvi_itinerary_route_hotspot_entry_cost_details
   *   - dvi_itinerary_route_hotspot_parking_charge
   *   - dvi_itinerary_route_activity_details
   *   - dvi_itinerary_route_activity_entry_cost_details
   *
   * - Insert:
   *   - 1 “refresh” row (ITEM_REFRESH)
   *   - For each chosen hotspot:
   *     - travel row (ITEM_TRAVEL_OR_BREAK, allow_break_hours = 0)
   *     - optional break row (ITEM_TRAVEL_OR_BREAK, allow_break_hours = 1)
   *     - visit row (ITEM_VISIT)
   *   - Optional entry-cost detail rows per traveller in
   *     dvi_itinerary_route_hotspot_entry_cost_details
   */
  async rebuildHotspots(opts: { planId: number; routeId?: number; limit?: number }) {
    const planId = Number(opts.planId);
    if (!Number.isFinite(planId) || planId <= 0) {
      throw new BadRequestException("Invalid planId");
    }

    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
    });

    if (!plan) {
      throw new NotFoundException(`Plan ${planId} not found`);
    }

    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        deleted: 0,
        ...(opts.routeId ? { itinerary_route_ID: opts.routeId } : {}),
      },
      orderBy: { itinerary_route_ID: "asc" },
    });

    if (routes.length === 0) {
      throw new NotFoundException(
        opts.routeId ? `Route ${opts.routeId} not found` : "No routes found for this plan",
      );
    }

    const results: any[] = [];

    for (const route of routes) {
      const res = await this.prisma.$transaction(async (tx) => {
        // ---------------------------------------------------------------------
        // 1) CLEAR EXISTING HOTSPOT + RELATED DATA FOR THIS PLAN + ROUTE
        //    Mirrors PHP block that does DELETE on:
        //    - hotspot details
        //    - hotspot entry cost details
        //    - hotspot parking charge
        //    - route activity details
        //    - route activity entry cost details
        // ---------------------------------------------------------------------
        await this.deleteRouteHotspotData(tx, planId, route.itinerary_route_ID);

        // ---------------------------------------------------------------------
        // 2) INSERT REFRESH/BUFFER ROW (PHP-like)
        //    This is equivalent to the “refresh” row inserted in PHP just
        //    after clearing hotspot rows for that route.
        // ---------------------------------------------------------------------
        await tx.dvi_itinerary_route_hotspot_details.create({
          data: {
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            item_type: ITEM_REFRESH, // must match PHP numeric constant
            hotspot_order: 1,
            hotspot_ID: 0,
            allow_break_hours: 0,
            allow_via_route: 0,
            hotspot_plan_own_way: 0,
            hotspot_traveling_time: secondsToPrismaTime(0),
            itinerary_travel_type_buffer_time: secondsToPrismaTime(0),
            hotspot_start_time: secondsToPrismaTime(0),
            hotspot_end_time: secondsToPrismaTime(0),
            hotspot_travelling_distance: null,
            createdby: plan.createdby ?? 0,
            createdon: new Date(),
            status: 1,
            deleted: 0,
          } as Prisma.dvi_itinerary_route_hotspot_detailsUncheckedCreateInput,
        });

        const dayDate = route.itinerary_route_date;
        if (!dayDate) {
          return {
            routeId: route.itinerary_route_ID,
            insertedVisits: 0,
            reason: "Route date is null",
          };
        }

        const dayOfWeekNumeric = phpDayOfWeekNumericFromDateOnly(dayDate);

        // ---------------------------------------------------------------------
        // 3) ROUTE DAY WINDOW (start/end) – PHP gives defaults if null
        // ---------------------------------------------------------------------
        const dayStart = combineDateOnlyAndTime(dayDate, route.route_start_time, "09:00:00");
        const dayEnd = combineDateOnlyAndTime(dayDate, route.route_end_time, "20:00:00");

        // ---------------------------------------------------------------------
        // 4) VIA ROUTE NAMES (for hotspot location matching)
        //    PHP uses via routes heavily for token matching, so we include them
        //    in the tokens used to filter hotspot_place.
        // ---------------------------------------------------------------------
        const viaRows = await tx.dvi_itinerary_via_route_details.findMany({
          where: {
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            deleted: 0,
          },
          select: { itinerary_via_location_name: true },
        });

        const tokens = uniqueTokens([
          route.location_name ?? "",
          route.next_visiting_location ?? "",
          ...viaRows.map((v) => v.itinerary_via_location_name ?? ""),
        ]);

        // ---------------------------------------------------------------------
        // 5) HOTSPOT TIMINGS – filter by weekday & open status
        // ---------------------------------------------------------------------
        const timings = await tx.dvi_hotspot_timing.findMany({
          where: {
            deleted: 0,
            status: 1,
            hotspot_closed: 0,
            hotspot_timing_day: dayOfWeekNumeric,
          },
          select: {
            hotspot_ID: true,
            hotspot_open_all_time: true,
            hotspot_start_time: true,
            hotspot_end_time: true,
          },
        });

        const candidateIds = Array.from(
          new Set(timings.map((t) => t.hotspot_ID).filter((x): x is number => !!x)),
        );

        // ---------------------------------------------------------------------
        // 6) HOTSPOT MASTER RECORDS (PHP uses priority ordering)
        // ---------------------------------------------------------------------
        const hotspotsAll = candidateIds.length
          ? await tx.dvi_hotspot_place.findMany({
              where: {
                deleted: 0,
                status: 1,
                hotspot_ID: { in: candidateIds },
                ...(tokens.length
                  ? {
                      OR: tokens.map((token) => ({
                        hotspot_location: { contains: token },
                      })),
                    }
                  : {}),
              },
              orderBy: {
                hotspot_priority: "desc",
              },
            })
          : [];

        const hotspots =
          typeof opts.limit === "number" && opts.limit >= 0
            ? hotspotsAll.slice(0, opts.limit)
            : hotspotsAll;

        // ---------------------------------------------------------------------
        // 7) INITIAL COORDS: use stored_locations (like PHP)
        // ---------------------------------------------------------------------
        const startCoords = await this.findStartCoords(
          tx,
          route.location_name,
          route.next_visiting_location,
        );

        let cursor: {
          time: Date;
          locationText: string;
          lat: number | null;
          lng: number | null;
        } = {
          time: dayStart,
          locationText: String(route.location_name ?? ""),
          lat: startCoords?.lat ?? null,
          lng: startCoords?.lng ?? null,
        };

        // ---------------------------------------------------------------------
        // 8) MAIN LOOP – per hotspot
        //    For each hotspot we generate:
        //      - travel row
        //      - optional break row
        //      - visit row (+ optional entry cost details)
        // ---------------------------------------------------------------------
        let hotspotOrder = 1; // 1 is used by the refresh/buffer row
        let insertedVisits = 0;

        for (const h of hotspots) {
          const hotspotId = h.hotspot_ID;
          const hLat = toFloat(h.hotspot_latitude);
          const hLng = toFloat(h.hotspot_longitude);
          if (hLat == null || hLng == null) continue;

          // Avoid duplicates on same plan + route + hotspot
          const existing = await tx.dvi_itinerary_route_hotspot_details.findFirst({
            where: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              hotspot_ID: hotspotId,
              deleted: 0,
            },
            select: { route_hotspot_ID: true },
          });

          if (existing) continue;

          // -------------------------------------------------------------
          // 8.a TRAVEL COMPUTATION – matches PHP idea of “parity”
          // -------------------------------------------------------------
          const travel = computeTravelParity({
            prevLat: cursor.lat,
            prevLng: cursor.lng,
            prevLocation: cursor.locationText,
            nextLat: hLat,
            nextLng: hLng,
            nextLocation: String(h.hotspot_location ?? ""),
          });

          const travelStart = cursor.time;
          const travelEnd = new Date(travelStart.getTime() + travel.travelSeconds * 1000);

          // default duration if hotspot_duration not supplied
          const durationSeconds = timeToSeconds(h.hotspot_duration) ?? 3600;

          const hotspotTimings = timings.filter((t) => t.hotspot_ID === hotspotId);
          const timingDecision = resolveTimingForDay(
            dayDate,
            hotspotTimings as any,
            travelEnd,
            durationSeconds,
          );

          if (!timingDecision.allowed) {
            continue;
          }

          const visitStart = timingDecision.visitStart;
          const visitEnd = new Date(visitStart.getTime() + durationSeconds * 1000);

          // Do not exceed day end (same as PHP daily window constraint)
          if (visitEnd.getTime() > dayEnd.getTime()) {
            continue;
          }

          // -------------------------------------------------------------
          // 8.b TRAVEL ROW (ITEM_TRAVEL_OR_BREAK, allow_break_hours = 0)
          // -------------------------------------------------------------
          hotspotOrder++;
          await tx.dvi_itinerary_route_hotspot_details.create({
            data: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              item_type: ITEM_TRAVEL_OR_BREAK,
              hotspot_order: hotspotOrder,
              hotspot_ID: hotspotId,
              allow_break_hours: 0,
              allow_via_route: 0,
              via_location_name: null,
              hotspot_plan_own_way: 0,

              hotspot_traveling_time: secondsToPrismaTime(travel.travelSeconds),
              itinerary_travel_type_buffer_time: secondsToPrismaTime(0),
              hotspot_travelling_distance: travel.distanceKm.toFixed(2),
              hotspot_start_time: dateTimeToPrismaTime(travelStart),
              hotspot_end_time: dateTimeToPrismaTime(travelEnd),

              createdby: plan.createdby ?? 0,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            } as Prisma.dvi_itinerary_route_hotspot_detailsUncheckedCreateInput,
          });

          // -------------------------------------------------------------
          // 8.c BREAK ROW (OPTIONAL – ITEM_TRAVEL_OR_BREAK, allow_break_hours = 1)
          // -------------------------------------------------------------
          if (visitStart.getTime() > travelEnd.getTime()) {
            hotspotOrder++;
            await tx.dvi_itinerary_route_hotspot_details.create({
              data: {
                itinerary_plan_ID: planId,
                itinerary_route_ID: route.itinerary_route_ID,
                item_type: ITEM_TRAVEL_OR_BREAK,
                hotspot_order: hotspotOrder,
                hotspot_ID: hotspotId,
                allow_break_hours: 1,
                allow_via_route: 0,
                via_location_name: null,
                hotspot_plan_own_way: 0,

                hotspot_traveling_time: secondsToPrismaTime(0),
                itinerary_travel_type_buffer_time: secondsToPrismaTime(0),
                hotspot_travelling_distance: null,
                hotspot_start_time: dateTimeToPrismaTime(travelEnd),
                hotspot_end_time: dateTimeToPrismaTime(visitStart),

                createdby: plan.createdby ?? 0,
                createdon: new Date(),
                status: 1,
                deleted: 0,
              } as Prisma.dvi_itinerary_route_hotspot_detailsUncheckedCreateInput,
            });
          }

          // -------------------------------------------------------------
          // 8.d VISIT ROW (ITEM_VISIT)
          //     We also mirror PHP by putting aggregated entry values
          //     on the visit row itself (hotspot_*_entry_cost columns).
          // -------------------------------------------------------------
          const entry = computeEntryCost({
            entryTicketRequired: Number(plan.entry_ticket_required ?? 0) === 1,
            nationality: Number(plan.nationality ?? 0),
            adults: Number(plan.total_adult ?? 0),
            children: Number(plan.total_children ?? 0),
            infants: Number(plan.total_infants ?? 0),
            hotspot: h,
          });

          hotspotOrder++;
          const visitRow = await tx.dvi_itinerary_route_hotspot_details.create({
            data: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              item_type: ITEM_VISIT,
              hotspot_order: hotspotOrder,
              hotspot_ID: hotspotId,
              allow_break_hours: 0,
              allow_via_route: 0,
              via_location_name: null,
              hotspot_plan_own_way: 0,

              hotspot_adult_entry_cost: entry.adultUnit,
              hotspot_child_entry_cost: entry.childUnit,
              hotspot_infant_entry_cost: entry.infantUnit,
              hotspot_foreign_adult_entry_cost: entry.foreignAdultUnit,
              hotspot_foreign_child_entry_cost: entry.foreignChildUnit,
              hotspot_foreign_infant_entry_cost: entry.foreignInfantUnit,
              hotspot_amout: entry.totalAmount,

              // keep travel parity same as PHP:
              hotspot_traveling_time: secondsToPrismaTime(travel.travelSeconds),
              itinerary_travel_type_buffer_time: secondsToPrismaTime(0),
              hotspot_travelling_distance: travel.distanceKm.toFixed(2),
              hotspot_start_time: dateTimeToPrismaTime(visitStart),
              hotspot_end_time: dateTimeToPrismaTime(visitEnd),

              createdby: plan.createdby ?? 0,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            } as Prisma.dvi_itinerary_route_hotspot_detailsUncheckedCreateInput,
            select: {
              route_hotspot_ID: true,
            },
          });

          // -------------------------------------------------------------
          // 8.e ENTRY COST TRAVELLERS ROWS
          //     This mirrors the PHP behaviour where detailed per-traveller
          //     rows are stored in dvi_itinerary_route_hotspot_entry_cost_details.
          // -------------------------------------------------------------
          if (entry.totalAmount > 0) {
            const travellers = buildTravellers(
              Number(plan.total_adult ?? 0),
              Number(plan.total_children ?? 0),
              Number(plan.total_infants ?? 0),
            );

            for (const tr of travellers) {
              const cost = entry.costByTravellerType[tr.type] ?? 0;
              if (cost <= 0) continue;

              await tx.dvi_itinerary_route_hotspot_entry_cost_details.create({
                data: {
                  route_hotspot_id: visitRow.route_hotspot_ID,
                  hotspot_ID: hotspotId,
                  itinerary_plan_id: planId,
                  itinerary_route_id: route.itinerary_route_ID,
                  traveller_type: tr.type,
                  traveller_name: tr.name,
                  entry_ticket_cost: cost,
                  createdby: plan.createdby ?? 0,
                  createdon: new Date(),
                  status: 1,
                  deleted: 0,
                },
              });
            }
          }

          insertedVisits++;

          // Advance cursor for next hotspot
          cursor = {
            time: visitEnd,
            locationText: String(h.hotspot_location ?? ""),
            lat: hLat,
            lng: hLng,
          };
        }

        return {
          routeId: route.itinerary_route_ID,
          dayOfWeekNumeric,
          candidates: hotspots.length,
          insertedVisits,
          tokens,
        };
      });

      results.push(res);
    }

    return { planId, routes: results };
  }

  /**
   * Delete all hotspot-related data for a given plan+route.
   * Mirrors the DELETE chain in PHP around:
   *   dvi_itinerary_route_hotspot_details
   *   dvi_itinerary_route_hotspot_entry_cost_details
   *   dvi_itinerary_route_hotspot_parking_charge
   *   dvi_itinerary_route_activity_details
   *   dvi_itinerary_route_activity_entry_cost_details
   */
  private async deleteRouteHotspotData(
    tx: Prisma.TransactionClient,
    planId: number,
    routeId: number,
  ) {
    // Entry ticket / traveller-level details
    await tx.dvi_itinerary_route_hotspot_entry_cost_details.deleteMany({
      where: {
        itinerary_plan_id: planId,
        itinerary_route_id: routeId,
      },
    });

    // Parking charges per hotspot
    await tx.dvi_itinerary_route_hotspot_parking_charge.deleteMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
      },
    });

    // Activity entry costs (if any attached to this plan+route)
    await tx.dvi_itinerary_route_activity_entry_cost_details.deleteMany({
      where: {
        itinerary_plan_id: planId,
        itinerary_route_id: routeId,
      },
    });

    // Activity rows themselves
    await tx.dvi_itinerary_route_activity_details.deleteMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
      },
    });

    // Finally, all hotspot rows for this plan+route
    await tx.dvi_itinerary_route_hotspot_details.deleteMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
      },
    });
  }

  /**
   * Use dvi_stored_locations to get initial source coordinates for
   * (source_location, destination_location) pair – same tables PHP uses.
   */
  private async findStartCoords(
    tx: Prisma.TransactionClient,
    source?: string | null,
    destination?: string | null,
  ) {
    const src = (source ?? "").trim();
    const dst = (destination ?? "").trim();
    if (!src || !dst) return null;

    const row = await tx.dvi_stored_locations.findFirst({
      where: {
        deleted: 0,
        status: 1,
        source_location: { contains: src },
        destination_location: { contains: dst },
      },
      select: {
        source_location_lattitude: true,
        source_location_longitude: true,
      },
    });

    if (!row) return null;

    const lat =
      typeof row.source_location_lattitude === "number"
        ? row.source_location_lattitude
        : null;
    const lng =
      typeof row.source_location_longitude === "number"
        ? row.source_location_longitude
        : null;

    if (lat == null || lng == null) return null;

    return { lat, lng };
  }
}
