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

  async rebuildHotspots(opts: { planId: number; routeId?: number; limit?: number }) {
    const planId = Number(opts.planId);
    if (!planId) throw new BadRequestException("Invalid planId");

    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
    });
    if (!plan) throw new NotFoundException(`Plan ${planId} not found`);

    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        deleted: 0,
        ...(opts.routeId ? { itinerary_route_ID: opts.routeId } : {}),
      },
      orderBy: { itinerary_route_ID: "asc" },
    });

    const results: any[] = [];

    for (const route of routes) {
      const res = await this.prisma.$transaction(async (tx) => {
        await this.deleteRouteHotspotData(tx, planId, route.itinerary_route_ID);

        // REFRESH ROW
        await tx.dvi_itinerary_route_hotspot_details.create({
          data: {
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            item_type: ITEM_REFRESH,
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
        if (!dayDate) return { routeId: route.itinerary_route_ID, insertedVisits: 0 };

        const dayStart = combineDateOnlyAndTime(dayDate, route.route_start_time, "09:00:00");
        const dayEnd = combineDateOnlyAndTime(dayDate, route.route_end_time, "20:00:00");

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

        const timings = await tx.dvi_hotspot_timing.findMany({
          where: {
            deleted: 0,
            status: 1,
            hotspot_closed: 0,
            hotspot_timing_day: phpDayOfWeekNumericFromDateOnly(dayDate),
          },
        });

        const candidateIds = Array.from(new Set(timings.map((t) => t.hotspot_ID).filter(Boolean)));

        const hotspots = candidateIds.length
          ? await tx.dvi_hotspot_place.findMany({
              where: {
                deleted: 0,
                status: 1,
                hotspot_ID: { in: candidateIds },
                ...(tokens.length
                  ? {
                      OR: tokens.map((t) => ({
                        hotspot_location: { contains: t },
                      })),
                    }
                  : {}),
              },
              orderBy: { hotspot_priority: "desc" },
            })
          : [];

        let cursor = {
          time: dayStart,
          lat: null as number | null,
          lng: null as number | null,
          locationText: String(route.location_name ?? ""),
        };

        let order = 1;
        let insertedVisits = 0;

        for (const h of hotspots) {
          const hLat = toFloat(h.hotspot_latitude);
          const hLng = toFloat(h.hotspot_longitude);
          if (hLat == null || hLng == null) continue;

          const exists = await tx.dvi_itinerary_route_hotspot_details.findFirst({
            where: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              hotspot_ID: h.hotspot_ID,
              deleted: 0,
            },
          });
          if (exists) continue;

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

          const durationSeconds = timeToSeconds(h.hotspot_duration) ?? 3600;
          const timingDecision = resolveTimingForDay(
            dayDate,
            timings.filter((t) => t.hotspot_ID === h.hotspot_ID),
            travelEnd,
            durationSeconds,
          );
          if (!timingDecision.allowed) continue;

          const visitStart = timingDecision.visitStart;
          const visitEnd = new Date(visitStart.getTime() + durationSeconds * 1000);
          if (visitEnd > dayEnd) continue;

          // TRAVEL ROW
          order++;
          await tx.dvi_itinerary_route_hotspot_details.create({
            data: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              item_type: ITEM_TRAVEL_OR_BREAK,
              hotspot_order: order,
              hotspot_ID: h.hotspot_ID,
              allow_break_hours: 0,
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

          // VISIT ROW  ✅ PHP-PARITY FIX (NO travel fields)
          const entry = computeEntryCost({
            entryTicketRequired: Number(plan.entry_ticket_required) === 1,
            nationality: Number(plan.nationality),
            adults: Number(plan.total_adult),
            children: Number(plan.total_children),
            infants: Number(plan.total_infants),
            hotspot: h,
          });

          order++;
          const visit = await tx.dvi_itinerary_route_hotspot_details.create({
            data: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              item_type: ITEM_VISIT,
              hotspot_order: order,
              hotspot_ID: h.hotspot_ID,
              hotspot_amout: entry.totalAmount,
              hotspot_start_time: dateTimeToPrismaTime(visitStart),
              hotspot_end_time: dateTimeToPrismaTime(visitEnd),
              createdby: plan.createdby ?? 0,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            },
            select: { route_hotspot_ID: true },
          });

          if (entry.totalAmount > 0) {
            const travellers = buildTravellers(
              Number(plan.total_adult),
              Number(plan.total_children),
              Number(plan.total_infants),
            );
            for (const tr of travellers) {
              const cost = entry.costByTravellerType[tr.type] ?? 0;
              if (cost > 0) {
                await tx.dvi_itinerary_route_hotspot_entry_cost_details.create({
                  data: {
                    route_hotspot_id: visit.route_hotspot_ID,
                    hotspot_ID: h.hotspot_ID,
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
          }

          insertedVisits++;
          cursor = {
            time: visitEnd,
            locationText: String(h.hotspot_location ?? ""),
            lat: hLat,
            lng: hLng,
          };
        }

        return { routeId: route.itinerary_route_ID, insertedVisits };
      });

      results.push(res);
    }

    return { planId, routes: results };
  }

  private async deleteRouteHotspotData(tx: Prisma.TransactionClient, planId: number, routeId: number) {
    await Promise.all([
      tx.dvi_itinerary_route_hotspot_entry_cost_details.deleteMany({
        where: { itinerary_plan_id: planId, itinerary_route_id: routeId },
      }),
      tx.dvi_itinerary_route_hotspot_parking_charge.deleteMany({
        where: { itinerary_plan_ID: planId, itinerary_route_ID: routeId },
      }),
      tx.dvi_itinerary_route_activity_entry_cost_details.deleteMany({
        where: { itinerary_plan_id: planId, itinerary_route_id: routeId },
      }),
      tx.dvi_itinerary_route_activity_details.deleteMany({
        where: { itinerary_plan_ID: planId, itinerary_route_ID: routeId },
      }),
      tx.dvi_itinerary_route_hotspot_details.deleteMany({
        where: { itinerary_plan_ID: planId, itinerary_route_ID: routeId },
      }),
    ]);
  }
}
