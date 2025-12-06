import {
  BadRequestException,
  Injectable,
  NotFoundException,
} from "@nestjs/common";
import { Prisma, dvi_itinerary_plan_details } from "@prisma/client";
import { PrismaService } from "../../prisma.service";
import { CreateItineraryDto } from "./dto/create-itinerary.dto";
import {
  PREF_BOTH,
  PREF_HOTEL,
  PREF_VEHICLE,
} from "./utils/itinerary.constants";
import { ItineraryHotspotsEngine } from "./engines/itinerary-hotspots.engine";
import { ItineraryVehiclesEngine } from "./engines/itinerary-vehicles.engine";
import {
  countTravellers,
  isValidDate,
  isoTimeToPrismaTime,
  normalizeToDateOnlyUTC,
  parseViaRoute,
  timeStringToPrismaTime,
  toBigInt,
} from "./utils/itinerary.utils";

const DEBUG = true; // flip to false to silence logs

@Injectable()
export class ItinerariesService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly hotspotsEngine: ItineraryHotspotsEngine,
    private readonly vehiclesEngine: ItineraryVehiclesEngine,
  ) {}

  // ---------- helpers ----------
  private monthName(d: Date) {
    return d.toLocaleString("en-US", { month: "long" });
  }
  private monthNumber(d: Date) {
    return String(d.getMonth() + 1);
  }
  private dayCol(
    d: Date,
  ): `day_${1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | 13 | 14 | 15 | 16 | 17 | 18 | 19 | 20 | 21 | 22 | 23 | 24 | 25 | 26 | 27 | 28 | 29 | 30 | 31}` {
    const n = d.getDate() as
      | 1
      | 2
      | 3
      | 4
      | 5
      | 6
      | 7
      | 8
      | 9
      | 10
      | 11
      | 12
      | 13
      | 14
      | 15
      | 16
      | 17
      | 18
      | 19
      | 20
      | 21
      | 22
      | 23
      | 24
      | 25
      | 26
      | 27
      | 28
      | 29
      | 30
      | 31;
    return `day_${n}`;
  }
  private money(n?: number | null) {
    const v = Number(n ?? 0);
    return Number.isFinite(v)
      ? Math.round((v + Number.EPSILON) * 100) / 100
      : 0;
  }
  private pct(p?: number | null) {
    const v = Number(p ?? 0);
    return Number.isFinite(v) ? v : 0;
  }
  private tax(amount: number, gstPct: number) {
    return this.money(amount * (gstPct / 100));
  }
  private log(...args: any[]) {
    if (DEBUG) console.log("[ItinerariesService]", ...args);
  }

  /**
   * Generic city normalizer – works for ANY city.
   * - trim
   * - collapse multiple spaces
   * - Title Case
   */
  private normalizeCity(raw?: string | null) {
    const s = String(raw ?? "").trim();
    if (!s) return "";
    const collapsed = s.replace(/\s+/g, " ");
    return collapsed.replace(/\b\w/g, (m) => m.toUpperCase());
  }

  // Generate DVIYYYYMMDD{number} where {number} = last for today + 1
  private async generateQuoteId(
    tx: Prisma.TransactionClient,
    now: Date,
  ): Promise<string> {
    const yyyy = now.getFullYear();
    const mm = String(now.getMonth() + 1).padStart(2, "0");
    const dd = String(now.getDate()).padStart(2, "0");
    const prefix = `DVI${yyyy}${mm}${dd}`;

    const last = await tx.dvi_itinerary_plan_details.findFirst({
      where: {
        itinerary_quote_ID: { startsWith: prefix },
      },
      orderBy: {
        itinerary_quote_ID: "desc",
      },
      select: {
        itinerary_quote_ID: true,
      },
    });

    const lastNumber =
      last?.itinerary_quote_ID
        ? parseInt(last.itinerary_quote_ID.slice(prefix.length), 10) || 0
        : 0;

    const nextNumber = lastNumber + 1;
    return `${prefix}${nextNumber}`;
  }

  // ---- runtime table/column discovery for safe raw SQL ----
  private async tableHasColumns(
    tx: Prisma.TransactionClient,
    tableName: string,
    wanted: string[],
  ) {
    const [{ dbName }] = await tx.$queryRawUnsafe<any[]>(
      `SELECT DATABASE() AS dbName`,
    );
    const rows = await tx.$queryRawUnsafe<any[]>(
      `SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?`,
      dbName,
      tableName,
    );
    const have = new Set(rows.map((r) => String(r.COLUMN_NAME)));
    return wanted.filter((c) => have.has(c));
  }
  /**
   * Look up dvi_stored_locations.location_ID and distance by source/destination.
   * Returns { locationId: null, distance: null } if not found or source/destination empty.
   */
  private async resolveStoredLocationWithDistance(
    tx: Prisma.TransactionClient,
    source?: string | null,
    destination?: string | null,
    opts: { allowDeleted?: boolean } = {},
  ): Promise<{ locationId: bigint | null; distance: string | null }> {
    const src = String(source ?? "").trim();
    const dst = String(destination ?? "").trim();
    if (!src || !dst) {
      return { locationId: null, distance: null };
    }

    const where: any = {
      source_location: src,
      destination_location: dst,
    };
    if (!opts.allowDeleted) {
      where.deleted = 0;
    }

    const repo: any = (tx as any).dvi_stored_locations;
    if (!repo) {
      console.error(
        "[ItinerariesService] dvi_stored_locations model missing on Prisma client",
      );
      return { locationId: null, distance: null };
    }

    const row = await repo.findFirst({
      where,
      select: {
        location_ID: true,
        distance: true,
      },
    });

    if (!row) {
      return { locationId: null, distance: null };
    }

    // Prisma BigInt maps to bigint in TS, so we just pass it through
    const locationId = (row as any).location_ID ?? null;
    const distance =
      row.distance !== undefined && row.distance !== null
        ? String(row.distance)
        : null;

    return { locationId, distance };
  }


  /**
   * City-aware hotel picker (generic, no hard-coded city names).
   *
   * Tries:
   *   - h.hotel_city = :city (multiple casings)
   *   - h.hotel_place LIKE %:city%
   *   - h.hotel_address LIKE %:city%
   * Falls back to category-only if none match.
   */
  private async pickHotelForCity(
    tx: Prisma.TransactionClient,
    cityName: string | null,
    hotel_category_id: number,
  ): Promise<{
    hotel_id: number;
    hotel_margin: number | null;
    hotel_margin_gst_type: number | null;
    hotel_margin_gst_percentage: number | null;
    hotel_hotspot_status: number | null;
  } | null> {
    const baseWhere: Prisma.dvi_hotelWhereInput = {
      hotel_category: hotel_category_id,
      status: 1,
      OR: [{ deleted: false }, { deleted: null }],
    };

    // Normalize + build variants (NO hard-coded "Chennai/Pondy" etc)
    const norm = (s: string) => s.replace(/\s+/g, " ").trim();
    const title = (s: string) =>
      s.toLowerCase().replace(/\b\w/g, (m) => m.toUpperCase());

    const normalized = this.normalizeCity(cityName);
    const input = norm(normalized);
    const variants = new Set<string>();

    if (input) {
      variants.add(input); // Title Case
      variants.add(input.toLowerCase());
      variants.add(input.toUpperCase());
      variants.add(title(input)); // again Title Case, but safe
    }

    // 1) City-aware try: exact city first, then contains on place/address
    if (variants.size) {
      const equalsCityOrs: Prisma.dvi_hotelWhereInput[] = [];
      const containsPlaceOrs: Prisma.dvi_hotelWhereInput[] = [];
      const containsAddrOrs: Prisma.dvi_hotelWhereInput[] = [];

      for (const v of variants) {
        equalsCityOrs.push({ hotel_city: v });
        containsPlaceOrs.push({ hotel_place: { contains: v } });
        containsAddrOrs.push({ hotel_address: { contains: v } });
      }

      const cityAwareWhere: Prisma.dvi_hotelWhereInput = {
        ...baseWhere,
        OR: [
          { OR: equalsCityOrs },
          { OR: containsPlaceOrs },
          { OR: containsAddrOrs },
        ],
      };

      const pickedCity = await tx.dvi_hotel.findFirst({
        where: cityAwareWhere,
        orderBy: { hotel_id: "asc" },
        select: {
          hotel_id: true,
          hotel_margin: true,
          hotel_margin_gst_type: true,
          hotel_margin_gst_percentage: true,
          hotel_hotspot_status: true,
        },
      });

      if (pickedCity) return pickedCity;
    }

    // 2) Fallback: category-only
    const pickedAny = await tx.dvi_hotel.findFirst({
      where: baseWhere,
      orderBy: { hotel_id: "asc" },
      select: {
        hotel_id: true,
        hotel_margin: true,
        hotel_margin_gst_type: true,
        hotel_margin_gst_percentage: true,
        hotel_hotspot_status: true,
      },
    });

    return pickedAny ?? null;
  }

  // ---------- main entry ----------
  async createPlan(dto: CreateItineraryDto) {
    if (!dto?.plan || !Array.isArray(dto.routes) || dto.routes.length === 0) {
      throw new BadRequestException("plan + routes are required");
    }

    const now = new Date();
    const planPayload = dto.plan;

    const pref = Number(planPayload.itinerary_preference ?? 0);
    const wantsHotel = pref === PREF_HOTEL || pref === PREF_BOTH;
    const wantsVehicle = pref === PREF_VEHICLE || pref === PREF_BOTH;

    const totals = countTravellers(dto.travellers);
    const tripStart = new Date(planPayload.trip_start_date as any);
    const tripEnd = new Date(planPayload.trip_end_date as any);
    const pickup = new Date(planPayload.pick_up_date_and_time as any);

    const isUpdate =
      Number((planPayload as any).itinerary_plan_id ?? 0) > 0;
    const planIdFromClient = Number(
      (planPayload as any).itinerary_plan_id ?? 0,
    );

    const staffCreatedBy = Number(planPayload.staff_id) || 1;

    this.log("createPlan:start", {
      isUpdate,
      wantsHotel,
      wantsVehicle,
      routes: dto.routes.length,
    });

    const created = await this.prisma.$transaction(async (tx) => {
      let planId: number | undefined;

      if (isUpdate) {
        const existing = await tx.dvi_itinerary_plan_details.findUnique({
          where: { itinerary_plan_ID: planIdFromClient },
          select: { itinerary_plan_ID: true },
        });
        if (!existing)
          throw new NotFoundException(
            `Plan ${planIdFromClient} not found`,
          );
        planId = existing.itinerary_plan_ID;
      }

      const quoteId = !isUpdate
        ? await this.generateQuoteId(tx, now)
        : undefined;
      this.log("quoteId", { quoteId });

      // PLAN LOCATION_ID from dvi_stored_locations (arrival → departure)
      const { locationId: planLocationFromStore } =
        await this.resolveStoredLocationWithDistance(
          tx,
          (planPayload as any).arrival_point ?? null,
          (planPayload as any).departure_point ?? null,
          { allowDeleted: true },
        );
      const planLocationId = planLocationFromStore ?? 0;

      const planData: Prisma.dvi_itinerary_plan_detailsUncheckedCreateInput =
        {
          agent_id: planPayload.agent_id ?? 0,
          staff_id: planPayload.staff_id ?? 1,
          location_id: toBigInt(planLocationId),
          arrival_location: (planPayload as any).arrival_point ?? null,
          departure_location: (planPayload as any).departure_point ?? null,

          trip_start_date_and_time: isValidDate(tripStart)
            ? tripStart
            : null,
          trip_end_date_and_time: isValidDate(tripEnd) ? tripEnd : null,
          pick_up_date_and_time: isValidDate(pickup) ? pickup : null,

          arrival_type: planPayload.arrival_type ?? 0,
          departure_type: planPayload.departure_type ?? 0,

          expecting_budget: Number(planPayload.budget ?? 0),
          itinerary_type: planPayload.itinerary_type ?? 0,
          entry_ticket_required: planPayload.entry_ticket_required ?? 0,

          no_of_routes: dto.routes.length,
          no_of_days: planPayload.no_of_days ?? dto.routes.length,
          no_of_nights: planPayload.no_of_nights ?? 0,

          total_adult: totals.adults,
          total_children: totals.children,
          total_infants: totals.infants,

          nationality: planPayload.nationality ?? 0,
          itinerary_preference: Number(
            planPayload.itinerary_preference ?? 0,
          ),
          guide_for_itinerary: planPayload.guide_for_itinerary ?? 0,

          food_type: planPayload.food_type ?? 0,
          special_instructions:
            planPayload.special_instructions ?? null,

          status: 1,
          deleted: 0,
        };

      let planRow: {
        itinerary_plan_ID: number;
      } & Partial<dvi_itinerary_plan_details>;

      if (isUpdate && planId) {
        planRow = await tx.dvi_itinerary_plan_details.update({
          where: { itinerary_plan_ID: planId },
          data: {
            ...(planData as any),
            updatedon: now,
          } as Prisma.dvi_itinerary_plan_detailsUncheckedUpdateInput,
          select: {
            itinerary_plan_ID: true,
            createdby: true,
            itinerary_quote_ID: true,
          },
        });
      } else {
        planRow = await tx.dvi_itinerary_plan_details.create({
          data: {
            ...(planData as any),
            itinerary_quote_ID: quoteId,
            createdby: staffCreatedBy,
            createdon: now,
          } as any,
          select: {
            itinerary_plan_ID: true,
            createdby: true,
            itinerary_quote_ID: true,
          },
        });
      }
      const finalPlanId = planRow.itinerary_plan_ID;
      this.log("planSaved", {
        finalPlanId,
        quoteId: planRow.itinerary_quote_ID,
        planLocationId,
      });

      if (!wantsHotel) await this.deleteHotelPlanData(tx, finalPlanId);
      if (!wantsVehicle) await this.deleteVehiclePlanData(tx, finalPlanId);

      if (isUpdate) {
        await tx.dvi_itinerary_route_hotspot_entry_cost_details.deleteMany({
          where: { itinerary_plan_id: finalPlanId },
        });
        await tx.dvi_itinerary_route_hotspot_parking_charge.deleteMany({
          where: { itinerary_plan_ID: finalPlanId },
        });
        await tx.dvi_itinerary_route_hotspot_details.deleteMany({
          where: { itinerary_plan_ID: finalPlanId },
        });

        await tx.dvi_itinerary_plan_vendor_vehicle_details.deleteMany({
          where: { itinerary_plan_id: finalPlanId },
        });
        await tx.dvi_itinerary_plan_vendor_eligible_list.deleteMany({
          where: { itinerary_plan_id: finalPlanId },
        });
      }

      const existingRouteIds = isUpdate
        ? new Set(
            (
              await tx.dvi_itinerary_route_details.findMany({
                where: {
                  itinerary_plan_ID: finalPlanId,
                  deleted: 0,
                },
                select: { itinerary_route_ID: true },
              })
            ).map((r) => r.itinerary_route_ID),
          )
        : new Set<number>();

      const keepRouteIds: number[] = [];
      const routeMetas: Array<{
        routeId: number;
        dateOnly: Date | null;
        stayCity: string | null;
      }> = [];

      for (let i = 0; i < dto.routes.length; i++) {
        const r = dto.routes[i] as any;

        // date-only is local calendar date, no UTC shift issues
        const dateOnly = normalizeToDateOnlyUTC(
          new Date(r.itinerary_route_date),
        );

        const isFirst = i === 0;
        const isLast = i === dto.routes.length - 1;

        const startTime = isFirst
          ? isoTimeToPrismaTime(tripStart)
          : timeStringToPrismaTime(
              process.env.ITINERARY_DEFAULT_ROUTE_START_TIME ??
                "09:00:00",
            );
        const endTime = isLast
          ? isoTimeToPrismaTime(tripEnd)
          : timeStringToPrismaTime(
              process.env.ITINERARY_DEFAULT_ROUTE_END_TIME ??
                "20:00:00",
            );

        // get location_ID + distance from dvi_stored_locations
        const {
          locationId: storedLocationId,
          distance: storedDistance,
        } = await this.resolveStoredLocationWithDistance(
          tx,
          r.location_name ?? null,
          r.next_visiting_location ?? null,
        );

        const finalLocationId =
          storedLocationId ?? toBigInt(planPayload.location_id);
        // PHP parity: if nothing found, fall back to route value, else "0"
        const finalNoOfKm =
          storedDistance ??
          (r.no_of_km !== undefined && r.no_of_km !== null
            ? r.no_of_km
            : "0");

        const routeIdFromClient = Number(r.itinerary_route_id ?? 0);
        this.log("route:upsert", {
          i,
          routeIdFromClient,
          dateOnly,
          src: r.location_name,
          dst: r.next_visiting_location,
          storedLocationId: String(storedLocationId ?? ""),
          storedDistance,
          finalNoOfKm,
        });

        let routeId: number;
        if (isUpdate && routeIdFromClient > 0) {
          const found =
            await tx.dvi_itinerary_route_details.findFirst({
              where: {
                itinerary_route_ID: routeIdFromClient,
                itinerary_plan_ID: finalPlanId,
                deleted: 0,
              },
              select: { itinerary_route_ID: true },
            });

          if (found) {
            const updated =
              await tx.dvi_itinerary_route_details.update({
                where: {
                  itinerary_route_ID: found.itinerary_route_ID,
                },
                data: {
                  itinerary_plan_ID: finalPlanId,
                  location_id: finalLocationId,
                  location_name: r.location_name ?? null,
                  next_visiting_location:
                    r.next_visiting_location ?? null,
                  itinerary_route_date: dateOnly,
                  no_of_days: r.no_of_days ?? i + 1,
                  no_of_km: finalNoOfKm,
                  direct_to_next_visiting_place:
                    r.direct_to_next_visiting_place ?? 1,
                  route_start_time: startTime,
                  route_end_time: endTime,
                  updatedon: new Date(),
                  status: 1,
                  deleted: 0,
                } as any,
                select: { itinerary_route_ID: true },
              });
            routeId = updated.itinerary_route_ID;
          } else {
            const createdRoute =
              await tx.dvi_itinerary_route_details.create({
                data: {
                  itinerary_plan_ID: finalPlanId,
                  location_id: finalLocationId,
                  location_name: r.location_name ?? null,
                  next_visiting_location:
                    r.next_visiting_location ?? null,
                  itinerary_route_date: dateOnly,
                  no_of_days: r.no_of_days ?? i + 1,
                  no_of_km: finalNoOfKm,
                  direct_to_next_visiting_place:
                    r.direct_to_next_visiting_place ?? 1,
                  route_start_time: startTime,
                  route_end_time: endTime,
                  createdby: staffCreatedBy,
                  createdon: new Date(),
                  status: 1,
                  deleted: 0,
                } as any,
                select: { itinerary_route_ID: true },
              });
            routeId = createdRoute.itinerary_route_ID;
          }
        } else {
          const routeRow =
            await tx.dvi_itinerary_route_details.create({
              data: {
                itinerary_plan_ID: finalPlanId,
                location_id: finalLocationId,
                location_name: r.location_name ?? null,
                next_visiting_location:
                  r.next_visiting_location ?? null,
                itinerary_route_date: dateOnly,
                no_of_days: r.no_of_days ?? i + 1,
                no_of_km: finalNoOfKm,
                direct_to_next_visiting_place:
                  r.direct_to_next_visiting_place ?? 1,
                route_start_time: startTime,
                route_end_time: endTime,
                createdby: staffCreatedBy,
                createdon: new Date(),
                status: 1,
                deleted: 0,
              } as any,
              select: { itinerary_route_ID: true },
            });
          routeId = routeRow.itinerary_route_ID;
        }

        keepRouteIds.push(routeId);
        routeMetas.push({
          routeId,
          dateOnly: dateOnly,
          stayCity: (r.next_visiting_location ?? null) as any,
        });

        // VIA ROUTES
        await tx.dvi_itinerary_via_route_details.deleteMany({
          where: {
            itinerary_plan_ID: finalPlanId,
            itinerary_route_ID: routeId,
          },
        });

        const viaList = parseViaRoute(r.via_route);
        for (const viaName of viaList) {
          await tx.dvi_itinerary_via_route_details.create({
            data: {
              itinerary_route_ID: routeId,
              itinerary_plan_ID: finalPlanId,
              itinerary_route_date: dateOnly,
              source_location: r.location_name ?? null,
              destination_location:
                r.next_visiting_location ?? null,
              itinerary_via_location_ID: 0,
              itinerary_via_location_name: viaName,
              itinerary_session_id: null,
              createdby: staffCreatedBy,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            } as any,
          });
        }
      }

      if (isUpdate) {
        for (const oldId of existingRouteIds) {
          if (keepRouteIds.includes(oldId)) continue;
          await tx.dvi_itinerary_via_route_details.deleteMany({
            where: {
              itinerary_plan_ID: finalPlanId,
              itinerary_route_ID: oldId,
            },
          });
          await tx.dvi_itinerary_plan_hotel_room_amenities.deleteMany({
            where: { itinerary_plan_id: finalPlanId, itinerary_route_id: oldId },
          });
          await tx.dvi_itinerary_plan_hotel_room_details.deleteMany({
            where: { itinerary_plan_id: finalPlanId, itinerary_route_id: oldId },
          });
          await tx.dvi_itinerary_plan_hotel_details.deleteMany({
            where: { itinerary_plan_id: finalPlanId, itinerary_route_id: oldId },
          });
          await tx.dvi_itinerary_route_details.deleteMany({
            where: {
              itinerary_plan_ID: finalPlanId,
              itinerary_route_ID: oldId,
            },
          });
        }
      }

      if (isUpdate) {
        await tx.dvi_itinerary_traveller_details.deleteMany({
          where: { itinerary_plan_ID: finalPlanId },
        });
      }
      for (const t of dto.travellers ?? []) {
        await tx.dvi_itinerary_traveller_details.create({
          data: {
            itinerary_plan_ID: finalPlanId,
            traveller_type: (t as any).traveller_type ?? 0,
            room_id: (t as any).room_id ?? 0,
            traveller_age: null,
            child_bed_type: 0,
            createdby: staffCreatedBy,
            createdon: new Date(),
            status: 1,
            deleted: 0,
          } as any,
        });
      }

      if (wantsHotel) {
        await this.persistHotelsLikePhp(tx, {
          planId: finalPlanId,
          createdby: Number(planRow.createdby ?? staffCreatedBy),
          noOfNights: Number(planPayload.no_of_nights ?? 0),
          routeMetas,
          totalPersons:
            totals.adults + totals.children + totals.infants,
        });
      }

      if (wantsVehicle) {
        await this.deleteVehiclePlanData(tx, finalPlanId);
        for (const v of dto.vehicles ?? []) {
          await tx.dvi_itinerary_plan_vehicle_details.create({
            data: {
              itinerary_plan_id: finalPlanId,
              vehicle_type_id: Number(
                (v as any).vehicle_type_id ?? 0,
              ),
              vehicle_count: Number(
                (v as any).vehicle_count ?? 0,
              ),
              createdby: staffCreatedBy,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            } as any,
          });
        }
      }

      return {
        planId: finalPlanId,
        quoteId: planRow.itinerary_quote_ID,
        routeIds: keepRouteIds,
        createdBy: Number(
          planRow.createdby ?? staffCreatedBy,
        ),
        wantsVehicle,
      };
    });

    const hotspotResult =
      await this.hotspotsEngine.rebuildHotspots({
        planId: created.planId,
      });
    const vehicleEligible = created.wantsVehicle
      ? await this.vehiclesEngine.rebuildEligibleVendorList({
          planId: created.planId,
          createdBy: created.createdBy,
        })
      : { planId: created.planId, inserted: 0 };

    this.log("createPlan:done", {
      planId: created.planId,
      hotspotRoutes: hotspotResult.routes?.length,
      vehicleEligible,
    });

    return {
      planId: created.planId,
      quoteId:created.quoteId,
      routeIds: created.routeIds,
      hotspots: hotspotResult.routes,
      vehicleEligible,
      message:
        "Plan created/updated AND hotspots rebuilt AND vendor eligible vehicles inserted.",
    };
  }



  // ---------- HOTEL writer (uses city-aware picker) ----------
  private CATEGORY_BY_GROUP: Record<number, number> = {
    1: 2,
    2: 5,
    3: 2,
    4: 4,
  };

  private async persistHotelsLikePhp(
    tx: Prisma.TransactionClient,
    args: {
      planId: number;
      createdby: number;
      noOfNights: number;
      routeMetas: Array<{
        routeId: number;
        dateOnly: Date | null;
        stayCity: string | null;
      }>;
      totalPersons: number;
    },
  ) {
    const { planId, createdby, noOfNights, routeMetas, totalPersons } =
      args;

    const maxNightsPossible = Math.max(0, routeMetas.length - 1);
    const nights =
      noOfNights > 0
        ? Math.min(noOfNights, maxNightsPossible)
        : maxNightsPossible;
    const stayRoutes = routeMetas.slice(0, nights);

    this.log("hotels:begin", {
      planId,
      nights: stayRoutes.length,
      mapping: this.CATEGORY_BY_GROUP,
      totalPersons,
    });

    for (const rm of stayRoutes) {
      await tx.dvi_itinerary_plan_hotel_room_amenities.deleteMany({
        where: { itinerary_plan_id: planId, itinerary_route_id: rm.routeId },
      });
      await tx.dvi_itinerary_plan_hotel_room_details.deleteMany({
        where: { itinerary_plan_id: planId, itinerary_route_id: rm.routeId },
      });
      await tx.dvi_itinerary_plan_hotel_details.deleteMany({
        where: { itinerary_plan_id: planId, itinerary_route_id: rm.routeId },
      });
    }

    const groupTypes = [1, 2, 3, 4];

    for (const rm of stayRoutes) {
      const routeId = rm.routeId;
      const routeDate = rm.dateOnly ?? new Date();
      const cityName = (rm.stayCity ?? "").trim() || null;

      const dc = this.dayCol(routeDate);
      const yearStr = String(routeDate.getFullYear());
      const monthStr = this.monthName(routeDate);
      const monthNum = this.monthNumber(routeDate);

      this.log("night", {
        routeId,
        date: routeDate.toLocaleDateString("en-CA"),
        cityName,
        dc,
        yearStr,
        monthStr,
        monthNum,
      });

      for (const groupType of groupTypes) {
        const hotel_category_id =
          this.CATEGORY_BY_GROUP[groupType] ?? 0;
        this.log("groupType", {
          routeId,
          groupType,
          hotel_category_id,
        });

        const picked = await this.pickHotelForCity(
          tx,
          cityName,
          hotel_category_id,
        );
        this.log("hotelPick", { routeId, groupType, picked });

        const hotel_id = Number(picked?.hotel_id ?? 0);
        const marginPct = this.pct(picked?.hotel_margin);
        const marginGstType = Number(
          picked?.hotel_margin_gst_type ?? 0,
        );
        const marginGstPct = this.pct(
          picked?.hotel_margin_gst_percentage,
        );

        let rawRoomRows: any[] = [];
        if (hotel_id) {
          rawRoomRows =
            (await tx.dvi_hotel_room_price_book.findMany({
              where: { hotel_id, year: yearStr, month: monthStr },
              select: { room_id: true, [dc]: true } as any,
            })) as any[];
          if (rawRoomRows.length === 0) {
            this.log(
              "roomPriceBook:empty:nameMonth; retry:numberMonth",
              { hotel_id, yearStr, monthNum },
            );
            rawRoomRows =
              (await tx.dvi_hotel_room_price_book.findMany({
                where: { hotel_id, year: yearStr, month: monthNum },
                select: { room_id: true, [dc]: true } as any,
              })) as any[];
          }
        }
        this.log("roomPriceBook:rows", {
          count: rawRoomRows.length,
        });

        const roomPriceRows = rawRoomRows.map((r) => ({
          room_id: Number(r.room_id),
          rate: this.money(r?.[dc]),
          gstPct: 0,
        }));

        let pickedRoom:
          | { room_id: number; rate: number; gstPct: number }
          | null = null;
        for (const r of roomPriceRows) {
          if (r.rate > 0) {
            pickedRoom = r;
            break;
          }
        }
        this.log("roomPick", { pickedRoom });

        let total_no_of_rooms = 0,
          total_room_cost = 0,
          total_room_gst_amount = 0;
        let roomRowId: number | null = null;

        if (pickedRoom) {
          const qty = 1;
          const roomCost = this.money(qty * pickedRoom.rate);
          const roomGst = this.money(
            roomCost * (pickedRoom.gstPct / 100),
          );

          const roomRow =
            await tx.dvi_itinerary_plan_hotel_room_details.create({
              data: {
                itinerary_plan_id: planId,
                itinerary_route_id: routeId,
                itinerary_route_date: routeDate as any,
                hotel_id,

                group_type: groupType,
                room_type_id: pickedRoom.room_id,
                room_id: pickedRoom.room_id,
                room_qty: qty,
                room_rate: pickedRoom.rate,
                gst_type: 0,
                gst_percentage: pickedRoom.gstPct,

                extra_bed_count: 0,
                extra_bed_rate: 0,
                child_without_bed_count: 0,
                child_without_bed_charges: 0,
                child_with_bed_count: 0,
                child_with_bed_charges: 0,

                breakfast_required: 0,
                lunch_required: 0,
                dinner_required: 0,

                breakfast_cost_per_person: 0,
                lunch_cost_per_person: 0,
                dinner_cost_per_person: 0,

                total_breafast_cost: 0,
                total_lunch_cost: 0,
                total_dinner_cost: 0,

                total_room_cost: roomCost,
                total_room_gst_amount: roomGst,

                createdby: createdby ?? 1,
                createdon: new Date(),
                updatedon: new Date(),
                status: 1,
                deleted: 0,
              } as any,
            });

          roomRowId =
            (roomRow as any)
              ?.itinerary_plan_hotel_room_details_ID ?? null;

          total_no_of_rooms += qty;
          total_room_cost += roomCost;
          total_room_gst_amount += roomGst;
        }

        let bCost = 0,
          bGst = 0,
          lCost = 0,
          lGst = 0,
          dCost = 0,
          dGst = 0;
        if (hotel_id) {
          let mealRow: any =
            await tx.dvi_hotel_meal_price_book.findFirst({
              where: { hotel_id, year: yearStr, month: monthStr },
              select: { [dc]: true } as any,
            });
          if (!mealRow) {
            this.log(
              "mealPriceBook:empty:nameMonth; retry:numberMonth",
              { hotel_id, yearStr, monthNum },
            );
            mealRow =
              await tx.dvi_hotel_meal_price_book.findFirst({
                where: { hotel_id, year: yearStr, month: monthNum },
                select: { [dc]: true } as any,
              });
          }
          const persons = Math.max(
            0,
            Number(totalPersons ?? 0),
          );
          const perMeal = this.money(mealRow?.[dc] ?? 0);
          bCost = this.money(persons * perMeal);
          lCost = this.money(persons * perMeal);
          dCost = this.money(persons * perMeal);
        }

        const total_hotel_meal_plan_cost = this.money(
          bCost + lCost + dCost,
        );
        const total_hotel_meal_plan_cost_gst_amount = this.money(
          bGst + lGst + dGst,
        );

        const hotel_margin_rate = this.money(
          (total_room_cost * marginPct) / 100,
        );
        const hotel_margin_rate_tax_amt = this.money(
          (hotel_margin_rate * marginGstPct) / 100,
        );

        const total_amenities_cost = 0;
        const total_amenities_gst_amount = 0;

        const total_hotel_cost = this.money(
          total_room_cost +
            total_hotel_meal_plan_cost +
            total_amenities_cost +
            hotel_margin_rate,
        );
        const total_hotel_tax_amount = this.money(
          total_room_gst_amount +
            total_hotel_meal_plan_cost_gst_amount +
            total_amenities_gst_amount +
            hotel_margin_rate_tax_amt,
        );

        this.log("hotelTotals", {
          routeId,
          groupType,
          hotel_id,
          total_no_of_rooms,
          total_room_cost,
          total_room_gst_amount,
          total_hotel_meal_plan_cost,
          total_hotel_meal_plan_cost_gst_amount,
          hotel_margin_rate,
          hotel_margin_rate_tax_amt,
          total_hotel_cost,
          total_hotel_tax_amount,
        });

        const details =
          await tx.dvi_itinerary_plan_hotel_details.create({
            data: {
              group_type: groupType,
              itinerary_plan_id: planId,
              itinerary_route_id: routeId,
              itinerary_route_date: routeDate as any,
              itinerary_route_location: cityName,

              hotel_required: hotel_id ? 1 : 0,
              hotel_category_id: hotel_category_id,
              hotel_id,

              hotel_margin_percentage: marginPct,
              hotel_margin_gst_type: marginGstType,
              hotel_margin_gst_percentage: marginGstPct,
              hotel_margin_rate,
              hotel_margin_rate_tax_amt,

              hotel_breakfast_cost: bCost,
              hotel_breakfast_cost_gst_amount: bGst,
              hotel_lunch_cost: lCost,
              hotel_lunch_cost_gst_amount: lGst,
              hotel_dinner_cost: dCost,
              hotel_dinner_cost_gst_amount: dGst,

              total_no_of_persons: totalPersons,

              total_hotel_meal_plan_cost,
              total_hotel_meal_plan_cost_gst_amount,

              total_extra_bed_cost: 0,
              total_extra_bed_cost_gst_amount: 0,
              total_childwith_bed_cost: 0,
              total_childwith_bed_cost_gst_amount: 0,
              total_childwithout_bed_cost: 0,
              total_childwithout_bed_cost_gst_amount: 0,

              total_no_of_rooms,
              total_room_cost,
              total_room_gst_amount,

              total_hotel_cost,
              total_hotel_tax_amount,

              total_amenities_cost,
              total_amenities_gst_amount,

              createdby: createdby ?? 1,
              createdon: new Date(),
              updatedon: new Date(),
              status: 1,
              deleted: 0,
            } as any,
          });

        this.log("hotelDetails:inserted", {
          detailsId:
            (details as any)
              .itinerary_plan_hotel_details_ID,
          routeId,
          groupType,
          hotel_id,
        });

        if (roomRowId) {
          await tx.dvi_itinerary_plan_hotel_room_details.update({
            where: {
              itinerary_plan_hotel_room_details_ID: roomRowId,
            },
            data: {
              itinerary_plan_hotel_details_id:
                (details as any)
                  .itinerary_plan_hotel_details_ID,
            } as any,
          });
          this.log("roomDetails:backlinked", { roomRowId });
        }

        if ((picked?.hotel_hotspot_status ?? 1) === 0) {
          await this.clearDayHotspotsTx(tx, planId, routeId);
          this.log("hotspots:clearedForHotel", { routeId });
        }
      }
    }

    this.log("hotels:end", { planId });
  }

  // ---------- cleanup ----------
  private async clearDayHotspotsTx(
    tx: Prisma.TransactionClient,
    planId: number,
    routeId: number,
  ) {
    await tx.dvi_itinerary_route_hotspot_details
      .deleteMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
        },
      })
      .catch(() => {});
    await tx.dvi_itinerary_route_hotspot_entry_cost_details
      .deleteMany({
        where: {
          itinerary_plan_id: planId,
          itinerary_route_id: routeId,
        },
      })
      .catch(() => {});
    await tx.dvi_itinerary_route_hotspot_parking_charge
      .deleteMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
        },
      })
      .catch(() => {});
  }

  private async deleteHotelPlanData(
    tx: Prisma.TransactionClient,
    planId: number,
  ) {
    await tx.dvi_itinerary_plan_hotel_room_amenities.deleteMany({
      where: { itinerary_plan_id: planId },
    });
    await tx.dvi_itinerary_plan_hotel_room_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });
    await tx.dvi_itinerary_plan_hotel_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });
  }

  private async deleteVehiclePlanData(
    tx: Prisma.TransactionClient,
    planId: number,
  ) {
    await tx.dvi_itinerary_plan_vehicle_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });
    await tx.dvi_itinerary_plan_vendor_vehicle_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });
    await tx.dvi_itinerary_plan_vendor_eligible_list.deleteMany({
      where: { itinerary_plan_id: planId },
    });
  }
}
