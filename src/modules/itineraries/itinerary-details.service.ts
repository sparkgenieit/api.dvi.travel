// FILE: src/modules/itineraries/itinerary-details.service.ts
import { Injectable, NotFoundException } from '@nestjs/common';
import { Request } from 'express';
import { PrismaService } from '../../prisma.service';
import { LatestItineraryQueryDto } from './dto/latest-itinerary-query.dto';

// ---------------------------------------------------------------------------
// DTOs for Itinerary Details response (shared shape with frontend)
// ---------------------------------------------------------------------------

export interface VehicleCostBreakdownItemDto {
  label: string;
  amount: number;
}

export interface ItineraryVehicleRowDto {
  vendorName: string | null;
  branchName: string | null;
  vehicleOrigin: string | null;
  totalQty: string;
  totalAmount: string;

  // Optional detailed charges – can be filled from vendor-eligible table later
  rentalCharges?: number;
  tollCharges?: number;
  parkingCharges?: number;
  driverCharges?: number;
  permitCharges?: number;
  before6amDriver?: number;
  before6amVendor?: number;
  after8pmDriver?: number;
  after8pmVendor?: number;
  breakdown?: VehicleCostBreakdownItemDto[];

  // Optional UI helper fields for the vehicle card
  dayLabel?: string;
  fromLabel?: string;
  toLabel?: string;
  packageLabel?: string;
  col1Distance?: string;
  col1Duration?: string;
  col2Distance?: string;
  col2Duration?: string;
  col3Distance?: string;
  col3Duration?: string;
  imageUrl?: string | null;
}

export interface CostBreakdownDto {
  totalVehicleCost: number;
  totalVehicleAmount: number;
  additionalMargin: number;
  totalAmount: number;
  couponDiscount: number;
  agentMargin: number;
  totalRoundOff: number;
  netPayable: number;
  companyName: string;
}

// NOTE: Hotel fields removed – hotels come from a separate endpoint now.
export interface ItineraryDetailsResponseDto {
  quoteId: string;
  planId: number;
  dateRange: string;
  roomCount: number;
  extraBed: number;
  childWithBed: number;
  childWithoutBed: number;
  adults: number;
  children: number;
  infants: number;
  overallCost: string;

  // DAY / ROUTE TIMELINE
  days: any[]; // already shaped for FE (Start/Travel/Attraction/Return)

  // VEHICLES
  vehicles: ItineraryVehicleRowDto[];

  // PACKAGE NOTES + COSTING
  packageIncludes: {
    description: string;
    houseBoatNote: string;
    rateNote: string;
  };
  costBreakdown: CostBreakdownDto;
}

@Injectable()
export class ItineraryDetailsService {
  constructor(private readonly prisma: PrismaService) {}

  // ---------------------------------------------------------------------------
  // Low-level helpers
  // ---------------------------------------------------------------------------

  private toBigIntOrZero(value?: number | string | bigint | null): bigint {
    if (typeof value === 'bigint') return value;
    if (value === null || value === undefined) return 0n;
    const n = Number(value);
    if (Number.isNaN(n)) return 0n;
    return BigInt(Math.trunc(n));
  }

  private parseDate(value?: string | Date | null): Date | null {
    if (!value) return null;
    if (value instanceof Date) return value;

    const str = String(value).trim();
    if (!str) return null;

    // "DD/MM/YYYY"
    if (str.includes('/') && !str.includes('T')) {
      const [dStr, mStr, yStr] = str.split('/');
      const d = parseInt(dStr ?? '0', 10);
      const m = parseInt(mStr ?? '0', 10);
      const y = parseInt(yStr ?? '0', 10);
      if (!d || !m || !y) return null;
      return new Date(y, m - 1, d, 0, 0, 0, 0);
    }

    const dt = new Date(str);
    return isNaN(dt.getTime()) ? null : dt;
  }

  private startOfDay(d: Date) {
    const x = new Date(d);
    x.setHours(0, 0, 0, 0);
    return x;
  }

  private endOfDay(d: Date) {
    const x = new Date(d);
    x.setHours(23, 59, 59, 999);
    return x;
  }

  private toCsv(value: any): string {
    if (value === null || value === undefined) return '';
    if (Array.isArray(value)) {
      return value
        .map((v) => String(v).trim())
        .filter(Boolean)
        .join(',');
    }

    const str = String(value).trim();
    if (!str) return '';

    if (str.startsWith('[') && str.endsWith(']')) {
      try {
        const parsed = JSON.parse(str);
        if (Array.isArray(parsed)) {
          return parsed
            .map((v) => String(v).trim())
            .filter(Boolean)
            .join(',');
        }
      } catch {
        // ignore
      }
    }

    return str;
  }

  private pad2(n: number) {
    return String(n).padStart(2, '0');
  }

  private formatCreatedOn(d?: Date | string | null) {
    const dt = d instanceof Date ? d : d ? new Date(d) : null;
    if (!dt || isNaN(dt.getTime())) return '';
    const weekday = dt.toLocaleString('en-US', { weekday: 'short' });
    const month = dt.toLocaleString('en-US', { month: 'short' });
    return `${weekday}, ${month} ${this.pad2(dt.getDate())}, ${dt.getFullYear()}`;
  }

  /** FORMAT for DataTable timestamps (uses local time, same as old PHP) */
  private formatTripDateTime(d?: Date | string | null) {
    const dt = d instanceof Date ? d : d ? new Date(d) : null;
    if (!dt || isNaN(dt.getTime())) return '';
    const dd = this.pad2(dt.getDate());
    const mm = this.pad2(dt.getMonth() + 1);
    const yyyy = dt.getFullYear();
    let hh = dt.getHours();
    const min = this.pad2(dt.getMinutes());
    const ampm = hh >= 12 ? 'PM' : 'AM';
    hh = hh % 12;
    if (hh === 0) hh = 12;
    return `${dd}/${mm}/${yyyy} ${this.pad2(hh)}:${min} ${ampm}`;
  }

  /** FORMAT MySQL TIME (stored as UTC date 1970-01-01) → "hh:mm AM/PM" WITHOUT offset */
  private formatTime(d?: Date | string | null): string | null {
    if (!d) return null;
    const dt = d instanceof Date ? d : new Date(d);
    if (isNaN(dt.getTime())) return null;
    let hh = dt.getUTCHours();
    const mm = this.pad2(dt.getUTCMinutes());
    const ampm = hh >= 12 ? 'PM' : 'AM';
    hh = hh % 12;
    if (hh === 0) hh = 12;
    return `${this.pad2(hh)}:${mm} ${ampm}`;
  }

  /** FORMAT duration TIME → "15 Min" / "3 Hours 26 Min" */
  private formatDuration(d?: Date | string | null): string | null {
    if (!d) return null;
    const dt = d instanceof Date ? d : new Date(d);
    if (isNaN(dt.getTime())) return null;
    const totalMinutes = dt.getUTCHours() * 60 + dt.getUTCMinutes();
    if (totalMinutes <= 0) return null;
    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;
    if (h && m) return `${h} Hours ${m} Min`;
    if (h) return `${h} Hours`;
    return `${m} Min`;
  }

  // ---------------------------------------------------------------------------
  // Itinerary DETAILS (parity-ish with PHP, WITHOUT hotels)
  // ---------------------------------------------------------------------------
  async getItineraryDetails(
    quoteId: string,
  ): Promise<ItineraryDetailsResponseDto> {
    // ------------------------------ PLAN ------------------------------
    const plan = await this.prisma.dvi_itinerary_plan_details.findFirst({
      where: { itinerary_quote_ID: quoteId, deleted: 0 },
    });

    if (!plan) {
      throw new NotFoundException('Itinerary not found');
    }
    const planId = plan.itinerary_plan_ID;

    // ------------------------- ROUTES + HOTSPOTS ----------------------
    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0, status: 1 },
      orderBy: { itinerary_route_ID: 'asc' },
    });

    const days: any[] = [];

    for (let index = 0; index < routes.length; index++) {
      const route = routes[index];

      // from/to locations (dvi_stored_locations)
      const location =
        route.location_id && route.location_id !== BigInt(0)
          ? await this.prisma.dvi_stored_locations.findFirst({
              where: {
                location_ID: route.location_id,
                deleted: 0,
              },
            })
          : null;

      // route-hotspot rows
      const routeHotspots =
        await this.prisma.dvi_itinerary_route_hotspot_details.findMany({
          where: {
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            deleted: 0,
          },
          orderBy: { hotspot_order: 'asc' },
        });

      const hotspotIds = Array.from(
        new Set(
          routeHotspots
            .map((h) => h.hotspot_ID)
            .filter((id) => typeof id === 'number' && id > 0),
        ),
      );

      const hotspotMasters = hotspotIds.length
        ? await this.prisma.dvi_hotspot_place.findMany({
            where: {
              hotspot_ID: { in: hotspotIds },
              deleted: 0,
            },
          })
        : [];

      const hotspotMap = new Map(hotspotMasters.map((h) => [h.hotspot_ID, h]));

      const segments: any[] = [];

      let previousStopName =
        location?.source_location ??
        route.location_name ??
        plan.arrival_location ??
        '';

      let totalDistanceKm = 0;

      // PHP header always shows a generic "Start ..." row with 12 AM – 12 AM.
      segments.push({
        type: 'start' as const,
        title: index === 0 ? 'Start your Journey' : 'Start Your Day',
        timeRange: '12:00 AM - 12:00 AM',
      });

      for (const rh of routeHotspots) {
        const master = rh.hotspot_ID
          ? hotspotMap.get(rh.hotspot_ID as number) || null
          : null;

        const itemType = Number((rh as any).item_type ?? 0);

        const distanceStr = (rh as any)
          .hotspot_travelling_distance as string | null | undefined;
        const distanceNum =
          distanceStr && distanceStr.trim().length
            ? parseFloat(distanceStr)
            : 0;
        const travelDistance = `${(distanceNum || 0).toFixed(2)} KM`;

        const travelDuration = (rh as any).hotspot_traveling_time ?? null;
        const startTimeText = this.formatTime(
          (rh as any).hotspot_start_time ?? null,
        );
        const endTimeText = this.formatTime(
          (rh as any).hotspot_end_time ?? null,
        );

        // ---------------- ITEM TYPE HANDLING (match PHP) ----------------
        if (itemType === 1) {
          // PHP doesn't actually show a separate row here; we already pushed
          // the generic "Start your Journey" above, so just update previousStop.
          previousStopName =
            location?.source_location ??
            route.location_name ??
            plan.arrival_location ??
            '';
          continue;
        }

        if (itemType === 2 || itemType === 3) {
          // TRAVEL row
          const toName =
            (rh as any).via_location_name?.trim() ||
            master?.hotspot_name ||
            previousStopName;

          if (!Number.isNaN(distanceNum)) {
            totalDistanceKm += distanceNum;
          }

          segments.push({
            type: 'travel' as const,
            from: previousStopName,
            to: toName,
            timeRange:
              startTimeText && endTimeText
                ? `${startTimeText} - ${endTimeText}`
                : null,
            distance: travelDistance,
            duration: this.formatDuration(travelDuration),
            note: 'This may vary due to traffic conditions',
          });

          previousStopName = toName;
          continue;
        }

        // ATTRACTION / HOTSPOT rows (all other item types)
        if (!master || !master.hotspot_name?.trim()) {
          continue;
        }

        const stayDuration = (master as any).hotspot_duration ?? null;

        segments.push({
          type: 'attraction' as const,
          name: master.hotspot_name,
          description: master.hotspot_description ?? '',
          visitTime:
            startTimeText && endTimeText
              ? `${startTimeText} - ${endTimeText}`
              : null,
          duration: this.formatDuration(stayDuration),
          image: null,
        });

        previousStopName = master.hotspot_name;
      }

      // RETURN block at the end of the day
      const dayEndTimeText = this.formatTime(route.route_end_time as any);

      segments.push({
        type: 'return' as const,
        time: dayEndTimeText,
        note: null,
      });

      // Day distance: prefer total_running_kms from route (PHP header),
      // otherwise fall back to summed travel distances.
      let dayDistance: string | undefined = undefined;
      const totalRunningRaw: any = (route as any).total_running_kms;
      if (totalRunningRaw !== undefined && totalRunningRaw !== null) {
        const n = parseFloat(String(totalRunningRaw));
        if (!Number.isNaN(n)) {
          dayDistance = `${n.toFixed(2)} KM`;
        }
      }
      if (!dayDistance && totalDistanceKm > 0) {
        dayDistance = `${totalDistanceKm.toFixed(2)} KM`;
      }

      const dayStartTimeText = this.formatTime(route.route_start_time as any);

      days.push({
        id: route.itinerary_route_ID,
        dayNumber: index + 1,
        date: route.itinerary_route_date,
        departure:
          location?.source_location ??
          route.location_name ??
          plan.arrival_location ??
          '',
        arrival:
          location?.destination_location ??
          route.next_visiting_location ??
          plan.departure_location ??
          '',
        distance: dayDistance,
        startTime: dayStartTimeText,
        endTime: dayEndTimeText,
        segments,
      });
    }

    // ------------------------------ VEHICLES ------------------------------
    // 1) Raw plan-level vendor vehicle rows (per day / route)
    const vehicleRows =
      await this.prisma.dvi_itinerary_plan_vendor_vehicle_details.findMany({
        where: { itinerary_plan_id: planId, deleted: 0 },
        orderBy: { itinerary_route_date: 'asc' },
      });

    // 2) Plan-level aggregated eligible rows (per vendor branch + vehicle type)
    const eligibleRows =
      await this.prisma.dvi_itinerary_plan_vendor_eligible_list.findMany({
        where: { itinerary_plan_id: planId, deleted: 0 },
      });

    // Map eligible rows by (branch + vehicleType) like PHP
    type EligibleRow = (typeof eligibleRows)[number];

    const eligibleMap = new Map<string, EligibleRow>();
    for (const e of eligibleRows) {
      const branchId = (e as any).vendor_branch_id ?? 0;
      const vehicleTypeId = (e as any).vehicle_type_id ?? 0;
      const key = `${branchId}|${vehicleTypeId}`;
      if (!eligibleMap.has(key)) {
        eligibleMap.set(key, e);
      }
    }

    // 3) Load vendor branches (for names & origin location)
    const branchIds = Array.from(
      new Set(
        vehicleRows
          .map((v) => v.vendor_branch_id)
          .filter((id) => typeof id === 'number' && id > 0),
      ),
    );

    const branches = branchIds.length
      ? await this.prisma.dvi_vendor_branches.findMany({
          where: { vendor_branch_id: { in: branchIds }, deleted: 0 },
        })
      : [];

    const branchMap = new Map(
      branches.map((b) => [b.vendor_branch_id, b]),
    );

    // 4) PHP shows ONE ROW PER ORIGIN CITY (per branch / vehicle type).
    //    We dedupe by: branch + origin city + vehicle type.
    const seenVehicleKeys = new Set<string>();

    const vehicles: ItineraryVehicleRowDto[] =
      vehicleRows.reduce<ItineraryVehicleRowDto[]>((acc, v) => {
        const branch = branchMap.get(v.vendor_branch_id) || null;

        const originFrom = (
          (v as any).itinerary_route_location_from ?? ''
        )
          .toString()
          .trim();

        const vehicleTypeId = (v as any).vehicle_type_id ?? 0;
        const key = `${v.vendor_branch_id || 0}|${originFrom}|${vehicleTypeId}`;

        if (seenVehicleKeys.has(key)) {
          // Already represented – skip duplicates, like PHP
          return acc;
        }
        seenVehicleKeys.add(key);

        // Try to find an aggregated eligible row for this branch + vehicle type
        const eligible = eligibleMap.get(
          `${v.vendor_branch_id || 0}|${vehicleTypeId}`,
        );

        // Base values from plan-vehicle row
        let totalAmount = v.total_vehicle_amount ?? 0;
        let qty = v.vehicle_qty ?? 0;

        // Optional PHP-like cost breakdown
        let rentalCharges: number | undefined;
        let tollCharges: number | undefined;
        let parkingCharges: number | undefined;
        let driverCharges: number | undefined;
        let permitCharges: number | undefined;
        let before6amDriver: number | undefined;
        let before6amVendor: number | undefined;
        let after8pmDriver: number | undefined;
        let after8pmVendor: number | undefined;
        let breakdown: VehicleCostBreakdownItemDto[] | undefined;
        let packageLabel: string | undefined;

        if (eligible) {
          // If aggregated vehicle_grand_total exists, use that like PHP
          if (typeof (eligible as any).vehicle_grand_total === 'number') {
            totalAmount = (eligible as any).vehicle_grand_total ?? totalAmount;
          }

          // Prefer aggregated total_vehicle_qty if present
          if (typeof (eligible as any).total_vehicle_qty === 'number') {
            qty = (eligible as any).total_vehicle_qty ?? qty;
          }

          rentalCharges =
            (eligible as any).total_rental_charges ?? undefined;
          tollCharges =
            (eligible as any).total_toll_charges ?? undefined;
          parkingCharges =
            (eligible as any).total_parking_charges ?? undefined;
          driverCharges =
            (eligible as any).total_driver_charges ?? undefined;
          permitCharges =
            (eligible as any).total_permit_charges ?? undefined;

          before6amDriver =
            (eligible as any).total_before_6_am_charges_for_driver ??
            undefined;
          before6amVendor =
            (eligible as any).total_before_6_am_charges_for_vehicle ??
            undefined;
          after8pmDriver =
            (eligible as any).total_after_8_pm_charges_for_driver ??
            undefined;
          after8pmVendor =
            (eligible as any).total_after_8_pm_charges_for_vehicle ??
            undefined;

          // Build a breakdown list only for >0 amounts (for UI card)
          const tmp: VehicleCostBreakdownItemDto[] = [];
          const pushItem = (label: string, amount?: number) => {
            if (typeof amount === 'number' && amount > 0) {
              tmp.push({ label, amount });
            }
          };

          pushItem('Rental Charges', rentalCharges);
          pushItem('Toll Charges', tollCharges);
          pushItem('Parking Charges', parkingCharges);
          pushItem('Driver Charges', driverCharges);
          pushItem('Permit Charges', permitCharges);
          pushItem('Before 6 AM (Driver)', before6amDriver);
          pushItem('Before 6 AM (Vehicle)', before6amVendor);
          pushItem('After 8 PM (Driver)', after8pmDriver);
          pushItem('After 8 PM (Vehicle)', after8pmVendor);

          breakdown = tmp.length ? tmp : undefined;

          // Simple PHP-like package label: "Local - 120 KM" / "Outstation - 600 KM"
          const travelType =
            ((eligible as any).travel_type as string | null) ?? null;
          const allowedKms =
            (eligible as any).total_allowed_kms ??
            (eligible as any).total_allowed_local_kms ??
            null;

          if (travelType) {
            if (typeof allowedKms === 'number' && allowedKms > 0) {
              packageLabel = `${travelType} - ${allowedKms} KM`;
            } else {
              packageLabel = travelType;
            }
          }
        }

        acc.push({
          vendorName: branch?.vendor_branch_name ?? null,
          branchName: branch?.vendor_branch_name ?? null,
          vehicleOrigin:
            originFrom || branch?.vendor_branch_location || null,
          // FE can format these; keep them as raw string values
          totalQty: String(qty),
          totalAmount: totalAmount.toFixed(2),

          rentalCharges,
          tollCharges,
          parkingCharges,
          driverCharges,
          permitCharges,
          before6amDriver,
          before6amVendor,
          after8pmDriver,
          after8pmVendor,
          breakdown,
          packageLabel,
        });

        return acc;
      }, []);

    // 5) Total vehicle amount for footer: prefer aggregated eligible totals if present
    const totalVehicleAmountFromEligible = eligibleRows.reduce(
      (sum, e) => sum + ((e as any).vehicle_grand_total ?? 0),
      0,
    );

    const totalVehicleAmount =
      totalVehicleAmountFromEligible > 0
        ? totalVehicleAmountFromEligible
        : vehicleRows.reduce(
            (sum, v) => sum + (v.total_vehicle_amount ?? 0),
            0,
          );

    // ------------------------------ COST BREAKDOWN (still minimal) ------------------------------
    const costBreakdown: CostBreakdownDto = {
      totalVehicleCost: totalVehicleAmount,
      totalVehicleAmount: totalVehicleAmount,
      additionalMargin: 0,
      totalAmount: totalVehicleAmount,
      couponDiscount: 0,
      agentMargin: plan.agent_margin ?? 0,
      totalRoundOff: 0,
      netPayable: totalVehicleAmount,
      companyName: '',
    };

    // ------------------------------ TOP SUMMARY ------------------------------
    const dateRange =
      plan.trip_start_date_and_time && plan.trip_end_date_and_time
        ? `${plan.trip_start_date_and_time
            .toISOString()
            .slice(0, 10)} to ${plan.trip_end_date_and_time
            .toISOString()
            .slice(0, 10)}`
        : '';

    // roomCount: ONLY from plan now. Hotels are handled by hotel endpoint.
    const roomCount = plan.preferred_room_count ?? 0;

    const response: ItineraryDetailsResponseDto = {
      quoteId: plan.itinerary_quote_ID ?? '',
      planId: plan.itinerary_plan_ID,
      dateRange,
      roomCount,
      extraBed: plan.total_extra_bed ?? 0,
      childWithBed: plan.total_child_with_bed ?? 0,
      childWithoutBed: plan.total_child_without_bed ?? 0,
      adults: plan.total_adult ?? 0,
      children: plan.total_children ?? 0,
      infants: plan.total_infants ?? 0,
      overallCost: (plan.expecting_budget ?? 0).toFixed(2), // can refine later

      days,

      vehicles,
      packageIncludes: {
        description: '',
        houseBoatNote: '',
        rateNote: '',
      },
      costBreakdown,
    };

    return response;
  }

  
    // ---------------------------------------------------------------------------
  // Latest Itineraries DataTable (unchanged logic, just using helpers)
  // ---------------------------------------------------------------------------
  async getLatestItinerariesDataTable(
    q: LatestItineraryQueryDto,
    req: any,
  ) {
    const rawQuery: any = (req as any)?.query ?? {};

    const searchValue =
      (rawQuery.search &&
        (rawQuery.search.value ?? rawQuery.search['value'])) ||
      rawQuery['search[value]'] ||
      '';

    const draw = Number(q.draw ?? rawQuery.draw ?? 0) || 0;
    const start = Number(q.start ?? rawQuery.start ?? 0) || 0;
    const limit = Number(q.length ?? rawQuery.length ?? 10) || 10;

    const startDateRaw = this.parseDate(
      q.start_date ?? rawQuery.start_date ?? null,
    );
    const endDateRaw = this.parseDate(
      q.end_date ?? rawQuery.end_date ?? null,
    );

    const startDate = startDateRaw ? this.startOfDay(startDateRaw) : null;
    const endDate = endDateRaw ? this.endOfDay(endDateRaw) : null;

    const source_location = String(
      q.source_location ?? rawQuery.source_location ?? '',
    ).trim();
    const destination_location = String(
      q.destination_location ?? rawQuery.destination_location ?? '',
    ).trim();

    const filter_agent_id =
      Number(q.agent_id ?? rawQuery.agent_id ?? 0) || 0;
    const filter_staff_id =
      Number(q.staff_id ?? rawQuery.staff_id ?? 0) || 0;

    const u: any = (req as any).user ?? {};
    const logged_user_level =
      Number(u.roleID ?? u.roleId ?? u.role ?? 0) || 0;
    const input_staff_id = Number(u.staff_id ?? u.staffId ?? 0) || 0;
    const input_agent_id = Number(u.agent_id ?? u.agentId ?? 0) || 0;

    const s = String(searchValue ?? '').trim();

    let roleOr: any | null = null;

    if (input_staff_id > 0 && logged_user_level !== 6) {
      const teAgents = await this.prisma.dvi_agent.findMany({
        where: {
          travel_expert_id: input_staff_id,
        } as any,
        select: { agent_ID: true },
      });
      const teAgentIds = teAgents
        .map((a) => Number(a.agent_ID))
        .filter((n) => n > 0);

      roleOr = {
        OR: [
          { staff_id: input_staff_id },
          ...(teAgentIds.length ? [{ agent_id: { in: teAgentIds } }] : []),
        ],
      };
    } else if (input_agent_id > 0) {
      const agentStaff = await this.prisma.dvi_staff_details.findMany({
        where: {
          agent_id: input_agent_id,
        } as any,
        select: { staff_id: true },
      });
      const agentStaffIds = agentStaff
        .map((x) => Number(x.staff_id))
        .filter((n) => n > 0);

      roleOr = {
        OR: [
          { agent_id: input_agent_id },
          ...(agentStaffIds.length ? [{ staff_id: { in: agentStaffIds } }] : []),
        ],
      };
    }

    let searchOr: any[] = [];
    if (s) {
      const staffMatches = await this.prisma.dvi_staff_details.findMany({
        where: {
          staff_name: { contains: s },
        } as any,
        select: { staff_id: true },
        take: 500,
      });
      const staffIdsByName = staffMatches
        .map((x) => Number(x.staff_id))
        .filter((n) => n > 0);

      const agentMatches = await this.prisma.dvi_agent.findMany({
        where: {
          agent_name: { contains: s },
        } as any,
        select: { agent_ID: true },
        take: 500,
      });
      const agentIdsByName = agentMatches
        .map((x) => Number(x.agent_ID))
        .filter((n) => n > 0);

      const userMatches = await this.prisma.dvi_users.findMany({
        where: {
          OR: [
            { username: { contains: s } },
            ...(staffIdsByName.length
              ? [{ staff_id: { in: staffIdsByName } }]
              : []),
            ...(agentIdsByName.length
              ? [{ agent_id: { in: agentIdsByName } }]
              : []),
          ],
        } as any,
        select: { userID: true },
        take: 1000,
      });
      const userIdsBySearch = userMatches
        .map((x) => Number(x.userID))
        .filter((n) => n > 0);

      const confirmedMatches =
        await this.prisma.dvi_confirmed_itinerary_plan_details.findMany(
          {
            where: {
              deleted: 0,
              itinerary_quote_ID: { contains: s },
            } as any,
            select: { itinerary_plan_ID: true },
            take: 1000,
          },
        );
      const planIdsByConfirmed = confirmedMatches
        .map((x) => Number(x.itinerary_plan_ID))
        .filter((n) => n > 0);

      searchOr = [
        { arrival_location: { contains: s } },
        { departure_location: { contains: s } },
        { itinerary_quote_ID: { contains: s } },
        ...(userIdsBySearch.length
          ? [{ createdby: { in: userIdsBySearch } }]
          : []),
        ...(planIdsByConfirmed.length
          ? [{ itinerary_plan_ID: { in: planIdsByConfirmed } }]
          : []),
      ];
    }

    const where: any = {
      deleted: 0,
      ...(roleOr ? roleOr : {}),
      ...(s ? { OR: searchOr } : {}),
    };

    if (startDate) {
      where.trip_start_date_and_time = {
        ...(where.trip_start_date_and_time ?? {}),
        gte: startDate,
      };
    }
    if (endDate) {
      where.trip_end_date_and_time = {
        ...(where.trip_end_date_and_time ?? {}),
        lte: endDate,
      };
    }

    if (source_location) where.arrival_location = source_location;
    if (destination_location) where.departure_location = destination_location;

    if (filter_agent_id > 0) where.agent_id = filter_agent_id;
    if (filter_staff_id > 0) where.staff_id = filter_staff_id;

    const totalRecords =
      await this.prisma.dvi_itinerary_plan_details.count({ where });

    const plans = await this.prisma.dvi_itinerary_plan_details.findMany({
      where,
      orderBy: { itinerary_plan_ID: 'desc' },
      skip: start,
      take: limit,
      select: {
        itinerary_plan_ID: true,
        arrival_location: true,
        departure_location: true,
        trip_start_date_and_time: true,
        trip_end_date_and_time: true,
        expecting_budget: true,
        itinerary_quote_ID: true,
        no_of_routes: true,
        no_of_days: true,
        no_of_nights: true,
        total_adult: true,
        total_children: true,
        total_infants: true,
        itinerary_preference: true,
        preferred_room_count: true,
        total_extra_bed: true,
        status: true,
        deleted: true,
        createdon: true,
        createdby: true,
        staff_id: true,
        agent_id: true,
      } as any,
    });

    const planIds = plans
      .map((p: any) => Number(p.itinerary_plan_ID))
      .filter((n) => n > 0);
    const createdByUserIds = plans
      .map((p: any) => Number(p.createdby))
      .filter((n) => n > 0);

    const confirmed = planIds.length
      ? await this.prisma.dvi_confirmed_itinerary_plan_details.findMany({
          where: { itinerary_plan_ID: { in: planIds }, deleted: 0 } as any,
          select: { itinerary_plan_ID: true, itinerary_quote_ID: true },
        })
      : [];
    const confirmedMap = new Map<number, string>();
    for (const c of confirmed as any[]) {
      const pid = Number(c.itinerary_plan_ID);
      if (pid) confirmedMap.set(pid, String(c.itinerary_quote_ID ?? ''));
    }

    const users = createdByUserIds.length
      ? await this.prisma.dvi_users.findMany({
          where: { userID: { in: createdByUserIds } } as any,
          select: {
            userID: true,
            roleID: true,
            staff_id: true,
            agent_id: true,
            username: true,
          },
        })
      : [];
    const userMap = new Map<number, any>();
    for (const uu of users as any[]) userMap.set(Number(uu.userID), uu);

    const staffIds = Array.from(
      new Set(
        (users as any[])
          .map((x) => Number(x.staff_id))
          .filter((n) => n > 0),
      ),
    );
    const agentIds = Array.from(
      new Set(
        (users as any[])
          .map((x) => Number(x.agent_id))
          .filter((n) => n > 0),
      ),
    );

    const staffRows = staffIds.length
      ? await this.prisma.dvi_staff_details.findMany({
          where: { staff_id: { in: staffIds } } as any,
          select: { staff_id: true, staff_name: true },
        })
      : [];
    const staffMap = new Map<number, string>();
    for (const st of staffRows as any[])
      staffMap.set(Number(st.staff_id), String(st.staff_name ?? ''));

    const agentRows = agentIds.length
      ? await this.prisma.dvi_agent.findMany({
          where: { agent_ID: { in: agentIds } } as any,
          select: { agent_ID: true, agent_name: true },
        })
      : [];
    const agentMap = new Map<number, string>();
    for (const ag of agentRows as any[])
      agentMap.set(Number(ag.agent_ID), String(ag.agent_name ?? ''));

    let counter = start;

    const data = (plans ?? []).map((p: any) => {
      counter++;

      const pid = Number(p.itinerary_plan_ID ?? 0) || 0;
      const uRec = userMap.get(Number(p.createdby ?? 0)) ?? null;

      const roleID = Number(uRec?.roleID ?? 0) || 0;
      const staff_id = Number(uRec?.staff_id ?? 0) || 0;
      const agent_id = Number(uRec?.agent_id ?? 0) || 0;

      const staff_name = staff_id ? staffMap.get(staff_id) ?? '' : '';
      const agent_name = agent_id ? agentMap.get(agent_id) ?? '' : '';

      let username = '';
      if (roleID === 1) {
        username = String(uRec?.username ?? '');
      } else if (roleID === 3 && staff_id !== 0 && agent_id === 0) {
        username = `Travel Expert - <br>${staff_name}`;
      } else if (roleID === 4 && staff_id === 0 && agent_id !== 0) {
        username = `Agent - <br>${agent_name}`;
      } else if (roleID === 4 && staff_id !== 0 && agent_id !== 0) {
        username = `Agent - <br>${staff_name}`;
      } else if (roleID === 5 && staff_id !== 0 && agent_id === 0) {
        username = `Guide - <br>${staff_name}`;
      }

      const total_adult = Number(p.total_adult ?? 0) || 0;
      const total_children = Number(p.total_children ?? 0) || 0;
      const total_infants = Number(p.total_infants ?? 0) || 0;

      const total_members = `<span>Adult - ${total_adult}</br>Children - ${total_children}</br>Infants - ${total_infants}</span>`;

      return {
        counter,
        modify: pid,
        itinerary_quote_ID: String(p.itinerary_quote_ID ?? '') || null,
        itinerary_booking_ID: confirmedMap.get(pid) ?? null,
        arrival_location: p.arrival_location ?? '',
        departure_location: p.departure_location ?? '',
        itinerary_preference:
          Number(p.itinerary_preference ?? 0) || 0,
        no_of_days_and_nights: `${
          Number(p.no_of_nights ?? 0) || 0
        }&${Number(p.no_of_days ?? 0) || 0}`,
        no_of_person: total_members,
        trip_start_date_and_time: this.formatTripDateTime(
          p.trip_start_date_and_time,
        ),
        trip_end_date_and_time: this.formatTripDateTime(
          p.trip_end_date_and_time,
        ),
        total_adult,
        total_children,
        total_infants,
        username,
        createdon: this.formatCreatedOn(p.createdon),
      };
    });

    return {
      draw,
      recordsTotal: totalRecords,
      recordsFiltered: totalRecords,
      data,
    };
  }


  async findOne(id: number) {
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: id },
    });
    if (!plan) throw new NotFoundException('Itinerary not found');

    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: id, deleted: 0 },
    });

    const hotspots =
      await this.prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: id, deleted: 0 },
      });

    const vehicles =
      await this.prisma.dvi_itinerary_plan_vehicle_details.findMany({
        where: { itinerary_plan_id: id, deleted: 0 },
      });

    const travellers =
      await this.prisma.dvi_itinerary_traveller_details.findMany({
        where: { itinerary_plan_ID: id, deleted: 0 },
      });

    return { plan, routes, hotspots, vehicles, travellers };
  }
}
