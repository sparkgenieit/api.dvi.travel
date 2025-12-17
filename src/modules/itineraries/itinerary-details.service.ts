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

  // IDs needed for vendor selection
  vendorEligibleId?: number;
  vehicleTypeId?: number;
  isAssigned?: boolean;

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
  // Hotel costs
  totalRoomCost?: number;
  roomCostPerPerson?: number;
  hotelPaxCount?: number;
  totalAmenitiesCost?: number;
  extraBedCost?: number;
  childWithBedCost?: number;
  childWithoutBedCost?: number;
  totalHotelAmount?: number;
  
  // Vehicle costs
  totalVehicleCost: number;
  totalVehicleAmount: number;
  totalVehicleQty?: number;
  
  // Activity/Guide costs
  totalGuideCost?: number;
  totalHotspotCost?: number;
  totalActivityCost?: number;
  
  // Final calculations
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

  /** YYYY-MM-DD using server local timezone (DB stores IST wall-clock). */
  private formatISODateLocal(d: Date): string {
    return `${d.getFullYear()}-${this.pad2(d.getMonth() + 1)}-${this.pad2(d.getDate())}`;
  }

  private formatCreatedOn(d?: Date | string | null) {
    const dt = d instanceof Date ? d : d ? new Date(d) : null;
    if (!dt || isNaN(dt.getTime())) return '';
    const weekday = dt.toLocaleString('en-US', { weekday: 'short' });
    const month = dt.toLocaleString('en-US', { month: 'short' });
    return `${weekday}, ${month} ${this.pad2(dt.getDate())}, ${dt.getFullYear()}`;
  }

  /**
   * Extract TIME from DATETIME field and format as "hh:mm AM/PM".
   * 
   * IMPORTANT:
   * - MySQL DATETIME stores wall-clock time without timezone (e.g., "2025-12-24 12:00:00").
   * - Prisma reads this as UTC, so "2025-12-24 12:00:00" becomes a JS Date with UTC time.
   * - We extract the time portion using UTC getters to get the original wall-clock time.
   * - This prevents timezone conversion (12:00 stays 12:00, not shifted to 17:30 IST).
   */
  private formatTripDateTime(d?: Date | string | null) {
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


  /**
   * FORMAT MySQL TIME (stored as IST wall-clock in DB) → "hh:mm AM/PM".
   *
   * IMPORTANT:
   * - MySQL TIME has no timezone.
   * - Prisma maps TIME to a JS Date (1970-01-01T12:00:00.000Z) in UTC.
   * - We must use UTC getters to read the time value without timezone conversion.
   * - Using local getters on an IST server would add +5:30 (12:00 → 17:30).
   */
  private formatTime(d?: Date | string | null): string | null {
    if (!d) return null;
    const dt = d instanceof Date ? d : new Date(d);
    if (isNaN(dt.getTime())) return null;

    let hh = dt.getUTCHours();           // ✅ Read UTC time value
    const mm = this.pad2(dt.getUTCMinutes());

    const ampm = hh >= 12 ? 'PM' : 'AM';
    hh = hh % 12;
    if (hh === 0) hh = 12;

    return `${this.pad2(hh)}:${mm} ${ampm}`;
  }

  /** Convert a TIME duration (stored as Date) to "X Hours" / "Y Min" */
  private formatDuration(d?: Date | string | null): string | null {
    if (!d) return null;
    const dt = d instanceof Date ? d : new Date(d);
    if (isNaN(dt.getTime())) return null;

    const totalMinutes = dt.getUTCHours() * 60 + dt.getUTCMinutes();   // ✅ Read UTC time value
    if (totalMinutes <= 0) return null;

    const h = Math.floor(totalMinutes / 60);
    const m = totalMinutes % 60;

    if (h > 0 && m > 0) return `${h} Hours ${m} Min`;
    if (h > 0) return `${h} Hours`;
    return `${m} Min`;
  }

  // ---------------------------------------------------------------------------
  // Itinerary DETAILS (parity-ish with PHP, WITHOUT hotels)
  // ---------------------------------------------------------------------------
  async getItineraryDetails(
    quoteId: string,
    groupType?: number,
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
        await this.prisma.$queryRawUnsafe(`
          SELECT 
            route_hotspot_ID,
            itinerary_plan_ID,
            itinerary_route_ID,
            item_type,
            hotspot_order,
            hotspot_ID,
            hotspot_adult_entry_cost,
            hotspot_child_entry_cost,
            hotspot_infant_entry_cost,
            hotspot_foreign_adult_entry_cost,
            hotspot_foreign_child_entry_cost,
            hotspot_foreign_infant_entry_cost,
            hotspot_amout,
            CAST(hotspot_traveling_time AS CHAR) as hotspot_traveling_time,
            CAST(itinerary_travel_type_buffer_time AS CHAR) as itinerary_travel_type_buffer_time,
            hotspot_travelling_distance,
            hotspot_start_time,
            hotspot_end_time,
            allow_break_hours,
            allow_via_route,
            via_location_name,
            hotspot_plan_own_way,
            createdby,
            createdon,
            updatedon,
            status,
            deleted
          FROM dvi_itinerary_route_hotspot_details
          WHERE itinerary_plan_ID = ${planId}
            AND itinerary_route_ID = ${route.itinerary_route_ID}
            AND deleted = 0
          ORDER BY hotspot_order ASC
        `) as any[];

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

      // Find item_type 1 (START/BREAK) to get actual start time
      const startHotspot = routeHotspots.find(
        (rh) => Number((rh as any).item_type ?? 0) === 1,
      );

      // Only add START segment if item_type 1 exists (match PHP behavior)
      if (startHotspot) {
        const startTimeRange = `${this.formatTime((startHotspot as any).hotspot_start_time ?? null)} - ${this.formatTime((startHotspot as any).hotspot_end_time ?? null)}`;

        segments.push({
          type: 'start' as const,
          title: index === 0 ? 'Start your Journey' : 'Start Your Day',
          timeRange: startTimeRange,
        });
      }

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

        if (itemType === 2) {
          // TRAVEL row (from source to next location)
          const toName =
            route.next_visiting_location ??
            location?.destination_location ??
            plan.departure_location ??
            '';

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

        if (itemType === 3) {
          // Item type 3 can be: break hours, via route, or lunch break
          const allowBreakHours = (rh as any).allow_break_hours ?? 0;
          const allowViaRoute = (rh as any).allow_via_route ?? 0;
          const viaLocationName = (rh as any).via_location_name?.trim();

          if (allowBreakHours === 1) {
            // BREAK HOURS (Lunch break, waiting time, etc.)
            const toName = master?.hotspot_name ?? viaLocationName ?? previousStopName;
            
            segments.push({
              type: 'break' as const,
              location: toName,
              duration: this.formatDuration(travelDuration),
              timeRange:
                startTimeText && endTimeText
                  ? `${startTimeText} - ${endTimeText}`
                  : null,
            });
          } else if (allowViaRoute === 1 && viaLocationName) {
            // VIA ROUTE (Travel via a location)
            const toName = viaLocationName;

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
          } else {
            // Regular travel to next hotspot or destination
            // When hotspot_ID is 0, use route's next_visiting_location as destination
            const toName = master?.hotspot_name ?? 
                          viaLocationName ?? 
                          (rh.hotspot_ID === 0 ? route.next_visiting_location : null) ??
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
          }
          continue;
        }

        if (itemType === 4) {
          // ATTRACTION / HOTSPOT visit
          if (!master || !master.hotspot_name?.trim()) {
            continue;
          }

          const stayDuration = (master as any).hotspot_duration ?? null;
          const hotspotAmount = (rh as any).hotspot_amout ?? 0;
          const hotspotPlanOwnWay = (rh as any).hotspot_plan_own_way ?? 0;
          const hotspotVideoUrl = master.hotspot_video_url ?? null;

          // Fetch activities for this hotspot
          const activities = await this.prisma.dvi_itinerary_route_activity_details.findMany({
            where: {
              itinerary_plan_ID: planId,
              itinerary_route_ID: route.itinerary_route_ID,
              route_hotspot_ID: rh.route_hotspot_ID,
              hotspot_ID: rh.hotspot_ID as number,
              deleted: 0,
              status: 1,
            },
            orderBy: { activity_order: 'asc' },
          });

          // Fetch activity masters for details
          const activityIds = activities.map(a => a.activity_ID).filter(id => id > 0);
          const activityMasters = activityIds.length
            ? await this.prisma.dvi_activity.findMany({
                where: {
                  activity_id: { in: activityIds },
                  deleted: 0,
                },
              })
            : [];

          const activityMap = new Map(activityMasters.map(a => [a.activity_id, a]));

          const activityList = activities.map(actDetail => {
            const actMaster = activityMap.get(actDetail.activity_ID);
            return {
              id: actDetail.route_activity_ID,
              activityId: actDetail.activity_ID,
              title: actMaster?.activity_title ?? '',
              description: actMaster?.activity_description ?? '',
              amount: Number(actDetail.activity_amout || 0),
              startTime: this.formatTime(actDetail.activity_start_time as any),
              endTime: this.formatTime(actDetail.activity_end_time as any),
              duration: this.formatDuration(actDetail.activity_traveling_time as any),
              image: null, // Can be fetched from activity gallery if needed
            };
          });

          segments.push({
            type: 'attraction' as const,
            name: master.hotspot_name,
            description: master.hotspot_description ?? '',
            visitTime:
              startTimeText && endTimeText
                ? `${startTimeText} - ${endTimeText}`
                : null,
            duration: this.formatDuration(stayDuration),
            amount: hotspotAmount > 0 ? Number(hotspotAmount) : null,
            image: null,
            videoUrl: hotspotVideoUrl,
            planOwnWay: hotspotPlanOwnWay === 1,
            activities: activityList,
            hotspotId: rh.hotspot_ID as number,
            routeHotspotId: rh.route_hotspot_ID,
            locationId: route.location_id ? Number(route.location_id) : null,
          });

          previousStopName = master.hotspot_name;
          continue;
        }

        if (itemType === 6) {
          // HOTEL CHECK-IN / RETURN segment
          // Fetch assigned hotel for this route using the selected group_type
          const hotelWhere: any = {
            itinerary_plan_id: planId,
            itinerary_route_id: route.itinerary_route_ID,
            deleted: 0,
          };
          
          // Use selected group_type (hotel recommendation tab), default to 1
          if (groupType !== undefined) {
            hotelWhere.group_type = groupType;
          } else {
            hotelWhere.group_type = 1;
          }
          
          const hotelAssignment = await this.prisma.dvi_itinerary_plan_hotel_details.findFirst({
            where: hotelWhere,
          });

          let hotelName = 'Hotel';
          let hotelAddress = '';

          if (hotelAssignment && hotelAssignment.hotel_id) {
            const hotel = await this.prisma.dvi_hotel.findFirst({
              where: {
                hotel_id: hotelAssignment.hotel_id,
                deleted: false,
              },
            });

            if (hotel) {
              hotelName = hotel.hotel_name ?? 'Hotel';
              hotelAddress = hotel.hotel_address ?? '';
            }
          }

          segments.push({
            type: 'checkin' as const,
            hotelName,
            hotelAddress,
            time: startTimeText,
          });

          continue;
        }

        if (itemType === 7) {
          // DROP OFF - final travel to airport/departure point
          const toName = route.next_visiting_location ?? plan.departure_location ?? 'Departure Point';

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
      }

      // Add "Click to Add Hotspot" segment after all hotspots
      // This allows users to add more hotspots to the route
      // Try to get location_id from route.location_id, or lookup by location_name if missing
      let hotspotLocationId = route.location_id ? Number(route.location_id) : null;
      
      if (!hotspotLocationId) {
        // Try multiple strategies to find the location
        let foundLocation = null;
        
        // Strategy 1: Exact match on source_location with route.location_name
        if (route.location_name) {
          foundLocation = await this.prisma.dvi_stored_locations.findFirst({
            where: {
              source_location: route.location_name,
              deleted: 0,
            },
          });
        }
        
        // Strategy 2: If not found, try matching with next_visiting_location
        if (!foundLocation && route.next_visiting_location) {
          foundLocation = await this.prisma.dvi_stored_locations.findFirst({
            where: {
              OR: [
                { source_location: route.next_visiting_location },
                { destination_location: route.next_visiting_location },
              ],
              deleted: 0,
            },
          });
        }
        
        // Strategy 3: Fuzzy match on location_name (contains)
        if (!foundLocation && route.location_name) {
          foundLocation = await this.prisma.dvi_stored_locations.findFirst({
            where: {
              OR: [
                { source_location: { contains: route.location_name } },
                { destination_location: { contains: route.location_name } },
              ],
              deleted: 0,
            },
          });
        }
        
        if (foundLocation) {
          hotspotLocationId = Number(foundLocation.location_ID);
        }
      }
      
      segments.push({
        type: 'hotspot' as const,
        text: 'Click to Add Hotspot',
        locationId: hotspotLocationId,
      });

      // RETURN block at the end of the day (only if no item_type 6 or 7 exists)
      const hasReturnOrDropOff = routeHotspots.some((rh) => {
        const itemType = Number((rh as any).item_type ?? 0);
        return itemType === 6 || itemType === 7;
      });

      const dayEndTimeText = this.formatTime(route.route_end_time as any);

      if (!hasReturnOrDropOff) {
        segments.push({
          type: 'return' as const,
          time: dayEndTimeText,
          note: null,
        });
      }

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

      // Fetch via routes for this route
      const viaRoutes = await this.prisma.dvi_itinerary_via_route_details.findMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: route.itinerary_route_ID,
          deleted: 0,
        },
        orderBy: { itinerary_via_route_ID: 'asc' },
      });

      const viaRoutesList = viaRoutes.map(vr => ({
        id: Number(vr.itinerary_via_location_ID),
        name: vr.itinerary_via_location_name,
      }));

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
        viaRoutes: viaRoutesList,
        segments,
      });
    }

    // ------------------------------ VEHICLES ------------------------------
    // PHP displays vehicles directly from dvi_itinerary_plan_vendor_eligible_list
    // Each row in eligible list is already aggregated per vendor/branch/type/origin
    const eligibleRows =
      await this.prisma.dvi_itinerary_plan_vendor_eligible_list.findMany({
        where: { itinerary_plan_id: planId, deleted: 0 },
        orderBy: { itinerary_plan_vendor_eligible_ID: 'asc' },
      });

    // Also fetch day-wise vehicle details to calculate KM totals
    const vehicleDetailsRows =
      await this.prisma.$queryRawUnsafe(`
        SELECT 
          itinerary_plan_vendor_vehicle_details_ID,
          itinerary_plan_vendor_eligible_ID,
          itinerary_plan_id,
          itinerary_route_id,
          itinerary_route_date,
          vehicle_type_id,
          vehicle_qty,
          vendor_id,
          vendor_vehicle_type_id,
          vehicle_id,
          vendor_branch_id,
          time_limit_id,
          kms_limit_id,
          travel_type,
          itinerary_route_location_from,
          itinerary_route_location_to,
          total_running_km,
          CAST(total_running_time AS CHAR) as total_running_time,
          total_siteseeing_km,
          CAST(total_siteseeing_time AS CHAR) as total_siteseeing_time,
          total_pickup_km,
          CAST(total_pickup_duration AS CHAR) as total_pickup_duration,
          total_drop_km,
          CAST(total_drop_duration AS CHAR) as total_drop_duration,
          total_extra_km,
          extra_km_rate,
          total_extra_km_charges,
          total_travelled_km,
          total_travelled_time,
          vehicle_rental_charges,
          vehicle_toll_charges,
          vehicle_parking_charges,
          vehicle_driver_charges,
          vehicle_permit_charges,
          before_6_am_extra_time,
          after_8_pm_extra_time,
          before_6_am_charges_for_driver,
          before_6_am_charges_for_vehicle,
          after_8_pm_charges_for_driver,
          after_8_pm_charges_for_vehicle,
          total_vehicle_amount,
          createdby,
          createdon,
          updatedon,
          status,
          deleted
        FROM dvi_itinerary_plan_vendor_vehicle_details
        WHERE itinerary_plan_id = ${planId} AND deleted = 0
        ORDER BY itinerary_route_date ASC
      `) as any[];

    // Group vehicle details by eligible ID to sum KMs
    const vehicleDetailsByEligible = new Map<number, any[]>();
    for (const vd of vehicleDetailsRows) {
      const eligibleId = (vd as any).itinerary_plan_vendor_eligible_ID;
      if (!vehicleDetailsByEligible.has(eligibleId)) {
        vehicleDetailsByEligible.set(eligibleId, []);
      }
      vehicleDetailsByEligible.get(eligibleId)!.push(vd);
    }

    // 3) Load vendor branches (for names & origin location)
    const branchIds = Array.from(
      new Set(
        eligibleRows
          .map((e) => (e as any).vendor_branch_id)
          .filter((id: number) => typeof id === 'number' && id > 0),
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

    // Build vehicles array directly from eligible list (like PHP does)
    const vehicles: ItineraryVehicleRowDto[] = eligibleRows.map((eligible) => {
      const branchId = (eligible as any).vendor_branch_id ?? 0;
      const branch = branchMap.get(branchId) || null;
      const vehicleTypeId = (eligible as any).vehicle_type_id ?? 0;
      const origin = ((eligible as any).vehicle_orign ?? '').toString().trim();
      
      const qty = (eligible as any).total_vehicle_qty ?? 0;
      const totalAmount = (eligible as any).vehicle_grand_total ?? 0;

      // Get all charge breakdowns
      const rentalCharges = (eligible as any).total_rental_charges ?? 0;
      const tollCharges = (eligible as any).total_toll_charges ?? 0;
      const parkingCharges = (eligible as any).total_parking_charges ?? 0;
      const driverCharges = (eligible as any).total_driver_charges ?? 0;
      const permitCharges = (eligible as any).total_permit_charges ?? 0;
      const before6amDriver = (eligible as any).total_before_6_am_charges_for_driver ?? 0;
      const before6amVendor = (eligible as any).total_before_6_am_charges_for_vehicle ?? 0;
      const after8pmDriver = (eligible as any).total_after_8_pm_charges_for_driver ?? 0;
      const after8pmVendor = (eligible as any).total_after_8_pm_charges_for_vehicle ?? 0;

      // Calculate aggregated KMs from day-wise vehicle details
      const eligibleId = eligible.itinerary_plan_vendor_eligible_ID;
      const dayWiseDetails = vehicleDetailsByEligible.get(eligibleId) || [];
      
      let totalRunningKm = 0;
      let totalSiteseeingKm = 0;
      let totalTravelledKm = 0;
      
      for (const vd of dayWiseDetails) {
        totalRunningKm += parseFloat((vd as any).total_running_km || 0);
        totalSiteseeingKm += parseFloat((vd as any).total_siteseeing_km || 0);
        totalTravelledKm += parseFloat((vd as any).total_travelled_km || 0);
      }

      // Build a breakdown list only for >0 amounts (for UI card)
      const tmp: VehicleCostBreakdownItemDto[] = [];
      const pushItem = (label: string, amount: number) => {
        if (amount > 0) {
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

      const breakdown = tmp.length ? tmp : undefined;

      // Simple PHP-like package label: "Outstation - 250 KM"
      const totalKms = (eligible as any).total_kms ?? '';
      const packageLabel = totalKms ? `Outstation - ${totalKms}KM` : undefined;

      return {
        vendorName: branch?.vendor_branch_name ?? null,
        branchName: branch?.vendor_branch_name ?? null,
        vehicleOrigin: origin || branch?.vendor_branch_location || null,
        totalQty: String(qty),
        totalAmount: totalAmount.toFixed(2),

        // IDs needed for vendor selection
        vendorEligibleId: eligible.itinerary_plan_vendor_eligible_ID,
        vehicleTypeId: vehicleTypeId,
        isAssigned: (eligible as any).itineary_plan_assigned_status === 1,

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
        
        // KM columns for the UI card
        col1Distance: totalRunningKm > 0 ? `${totalRunningKm.toFixed(2)} KM` : '0.00 KM',
        col2Distance: totalSiteseeingKm > 0 ? `${totalSiteseeingKm.toFixed(2)} KM` : '0.00 KM',
        col3Distance: totalTravelledKm > 0 ? `${totalTravelledKm.toFixed(2)} KM` : '0.00 KM',
        col1Duration: '0 Min', // Duration can be calculated if needed
        col2Duration: '0 Min',
        col3Duration: '0 Min',
      };
    });

    // 5) Total vehicle amount for footer: sum only ASSIGNED vehicles (itineary_plan_assigned_status = 1)
    // This matches PHP behavior which filters by assigned status
    const totalVehicleAmountFromEligible = eligibleRows.reduce(
      (sum, e) => {
        const isAssigned = (e as any).itineary_plan_assigned_status === 1;
        return sum + (isAssigned ? ((e as any).vehicle_grand_total ?? 0) : 0);
      },
      0,
    );

    const totalVehicleAmount =
      totalVehicleAmountFromEligible > 0
        ? totalVehicleAmountFromEligible
        : vehicles.reduce(
            (sum: number, v: any) => sum + (v.total_vehicle_amount ?? 0),
            0,
          );

    // ------------------------------ COST BREAKDOWN (calculate from database) ------------------------------
    
    // 1. Calculate Hotel Costs with detailed breakdown
    // Filter by group_type if provided (for hotel recommendation tabs)
    const hotelWhere: any = { itinerary_plan_id: planId, deleted: 0 };
    if (groupType !== undefined) {
      hotelWhere.group_type = groupType;
    }
    const hotelRows = await this.prisma.dvi_itinerary_plan_hotel_details.findMany({
      where: hotelWhere,
    });

    // Total room cost (excluding meals initially)
    let totalRoomCost = 0;
    let totalAmenitiesCost = 0;
    let extraBedCost = 0;
    let childWithBedCost = 0;
    let childWithoutBedCost = 0;
    let totalMealCost = 0;

    hotelRows.forEach(h => {
      totalRoomCost += Number(h.total_room_cost || 0) + Number(h.hotel_margin_rate || 0) + Number(h.total_room_gst_amount || 0);
      totalAmenitiesCost += Number(h.total_amenities_cost || 0);
      extraBedCost += Number(h.total_extra_bed_cost || 0);
      childWithBedCost += Number(h.total_childwith_bed_cost || 0);
      childWithoutBedCost += Number(h.total_childwithout_bed_cost || 0);
      totalMealCost += Number(h.total_hotel_meal_plan_cost || 0);
    });

    // Calculate per-person room cost (PHP logic)
    const totalAdults = plan.total_adult || 0;
    const totalChildren = plan.total_children || 0;
    const totalExtraBed = plan.total_extra_bed || 0;
    const hotelPaxCount = totalAdults - totalExtraBed;
    
    const paxMealCost = (totalAdults + totalChildren) > 0 
      ? totalMealCost / (totalAdults + totalChildren)
      : 0;
    
    const totalRoomCostUpdated = totalRoomCost + (hotelPaxCount * paxMealCost);
    const roomCostPerPerson = hotelPaxCount > 0 
      ? totalRoomCostUpdated / hotelPaxCount
      : 0;

    // Update costs with meal portions
    const updatedExtraBedCost = extraBedCost + (paxMealCost * totalExtraBed);
    const updatedChildWithBedCost = childWithBedCost + (paxMealCost * (plan.total_child_with_bed || 0));
    const updatedChildWithoutBedCost = childWithoutBedCost + (paxMealCost * (plan.total_child_without_bed || 0));

    const totalHotelAmount = totalRoomCostUpdated + totalAmenitiesCost + updatedExtraBedCost + updatedChildWithBedCost + updatedChildWithoutBedCost;

    // 2. Vehicle costs already calculated
    const totalVehicleCost = totalVehicleAmount;
    const totalVehicleQty = eligibleRows.reduce((sum, e) => sum + Number((e as any).total_vehicle_qty || 0), 0);

    // 3. Calculate Guide, Hotspot, and Activity costs
    // For now set to 0, can be calculated from route activities/guides if needed
    const totalGuideCost = 0;
    const totalHotspotCost = 0;
    const totalActivityCost = 0;

    // 4. Calculate additional margin (10% for trips <= configured day limit)
    const itineraryNoDays = plan.no_of_days || 0;
    const additionalMarginPercentage = 10; // Could come from global settings
    const additionalMarginDayLimit = 3; // Could come from global settings
    
    const subtotal = totalHotelAmount + totalVehicleCost;
    const additionalMargin = itineraryNoDays <= additionalMarginDayLimit 
      ? (subtotal * additionalMarginPercentage) / 100
      : 0;

    // 4. Calculate total amount before discounts
    const totalAmount = subtotal + additionalMargin;

    // 5. Get coupon discount and agent margin from plan
    const couponDiscount = 0; // Not currently stored in plan table
    const agentMargin = Number(plan.agent_margin || 0);

    // 6. Calculate round off
    const netBeforeRoundOff = totalAmount - couponDiscount + agentMargin;
    const roundedNet = Math.round(netBeforeRoundOff);
    const totalRoundOff = roundedNet - netBeforeRoundOff;

    // 7. Final net payable
    const netPayable = roundedNet;

    const costBreakdown: CostBreakdownDto = {
      // Hotel costs
      totalRoomCost: totalRoomCostUpdated > 0 ? totalRoomCostUpdated : undefined,
      roomCostPerPerson: roomCostPerPerson > 0 ? roomCostPerPerson : undefined,
      hotelPaxCount: hotelPaxCount > 0 ? hotelPaxCount : undefined,
      totalAmenitiesCost: totalAmenitiesCost > 0 ? totalAmenitiesCost : undefined,
      extraBedCost: updatedExtraBedCost > 0 ? updatedExtraBedCost : undefined,
      childWithBedCost: updatedChildWithBedCost > 0 ? updatedChildWithBedCost : undefined,
      childWithoutBedCost: updatedChildWithoutBedCost > 0 ? updatedChildWithoutBedCost : undefined,
      totalHotelAmount: totalHotelAmount > 0 ? totalHotelAmount : undefined,
      
      // Vehicle costs
      totalVehicleCost: totalVehicleCost,
      totalVehicleAmount: totalVehicleCost,
      totalVehicleQty: totalVehicleQty > 0 ? totalVehicleQty : undefined,
      
      // Activity/Guide costs
      totalGuideCost: totalGuideCost > 0 ? totalGuideCost : undefined,
      totalHotspotCost: totalHotspotCost > 0 ? totalHotspotCost : undefined,
      totalActivityCost: totalActivityCost > 0 ? totalActivityCost : undefined,
      
      // Final calculations
      additionalMargin: additionalMargin,
      totalAmount: totalAmount,
      couponDiscount: couponDiscount,
      agentMargin: agentMargin,
      totalRoundOff: totalRoundOff,
      netPayable: netPayable,
      companyName: 'Doview Holidays India Pvt ltd',
    };

    // ------------------------------ TOP SUMMARY ------------------------------
    const dateRange =
      plan.trip_start_date_and_time && plan.trip_end_date_and_time
        ? `${this.formatISODateLocal(plan.trip_start_date_and_time)} to ${this.formatISODateLocal(
            plan.trip_end_date_and_time,
          )}`
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
      overallCost: netPayable.toFixed(2), // Use calculated net payable

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


  async findOne(id: number, groupType?: number) {
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: id },
    });
    if (!plan) throw new NotFoundException('Itinerary not found');
    
    const quoteId = plan.itinerary_quote_ID;
    if (!quoteId) throw new NotFoundException('Quote ID not found for this plan');
    return this.getItineraryDetails(quoteId, groupType);
  }

  async findOneOld(id: number, groupType?: number) {
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: id },
    });
    if (!plan) throw new NotFoundException('Itinerary not found');

    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: id, deleted: 0 },
    });

    // Fetch via routes for all routes in this plan
    const viaRoutesRaw = await this.prisma.dvi_itinerary_via_route_details.findMany({
      where: { itinerary_plan_ID: id, deleted: 0 },
    });

    // Group via routes by route ID
    const viaRoutesByRouteId = new Map<number, any[]>();
    for (const vr of viaRoutesRaw) {
      const routeId = Number(vr.itinerary_route_ID);
      if (!viaRoutesByRouteId.has(routeId)) {
        viaRoutesByRouteId.set(routeId, []);
      }
      viaRoutesByRouteId.get(routeId)!.push({
        itinerary_via_location_ID: Number(vr.itinerary_via_location_ID),
        itinerary_via_location_name: vr.itinerary_via_location_name,
      });
    }

    // Add via_routes array to each route
    const routesWithVia = routes.map(r => ({
      ...r,
      via_routes: viaRoutesByRouteId.get(Number(r.itinerary_route_ID)) || [],
    }));

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

    return { plan, routes: routesWithVia, hotspots, vehicles, travellers };
  }
}
