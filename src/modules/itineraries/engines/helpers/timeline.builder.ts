// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/helpers/timeline.builder.ts
//
// PURPOSE:
//   Orchestrate building rows for:
//     • dvi_itinerary_route_hotspot_details
//     • dvi_itinerary_route_hotspot_parking_charge
//   using the helper builders.
//
// IMPORTANT:
//   - This is a PHP-parity-oriented skeleton, not final parity.
//   - You MUST plug in the real “selected hotspots per route” table
//     and the real “hotel location per route” query where marked TODO.
//   - It keeps createdByUserId = 1 to avoid changing other services.
//     Later you can pass the real user id from controller → service → engine.

import { Prisma } from "@prisma/client";
import { HotspotDetailRow } from "./types";
import { RefreshmentBuilder } from "./refreshment.builder";
import { TravelSegmentBuilder } from "./travel-segment.builder";
import { HotspotSegmentBuilder } from "./hotspot-segment.builder";
import { HotelTravelBuilder } from "./hotel-travel.builder";
import { ReturnSegmentBuilder } from "./return-segment.builder";
import {
  ParkingChargeBuilder,
  ParkingChargeRow,
} from "./parking-charge.builder";
import { timeToSeconds, addSeconds } from "./time.helper";
import { DistanceHelper } from "./distance.helper";
import { TimeConverter } from "./time-converter";
import * as fs from "fs";
import * as path from "path";

type Tx = Prisma.TransactionClient;

interface PlanHeader {
  itinerary_plan_ID: number;
  trip_start_date: Date;
  trip_end_date: Date;
  pick_up_date_and_time: Date;
  arrival_type: number;
  departure_type: number;
  entry_ticket_required: number;
  nationality: number;
  total_adult: number;
  total_children: number;
  total_infants: number;
  itinerary_preference: number;
  departure_location?: string | null;
}

interface RouteRow {
  itinerary_route_ID: number;
  itinerary_plan_ID: number;
  itinerary_route_date: Date;
  route_start_time: string;
  route_end_time: string;
  location_name: string | null;
  next_visiting_location: string | null;
  location_id: number | null;
}

// Minimal view of a selected hotspot row.
// ⚠️ You MUST adjust table/field names in fetchSelectedHotspotsForRoute().
interface SelectedHotspot {
  hotspot_ID: number;
  display_order?: number;
}

export class TimelineBuilder {
  private readonly refreshmentBuilder = new RefreshmentBuilder();
  private readonly travelBuilder = new TravelSegmentBuilder();
  private readonly hotspotBuilder = new HotspotSegmentBuilder();
  private readonly hotelBuilder = new HotelTravelBuilder();
  private readonly returnBuilder = new ReturnSegmentBuilder();
  private readonly parkingBuilder = new ParkingChargeBuilder();
  private readonly distanceHelper = new DistanceHelper();
  private logFilePath: string;

  constructor() {
    // Create logs directory if it doesn't exist
    const logDir = path.join(process.cwd(), 'tmp');
    if (!fs.existsSync(logDir)) {
      fs.mkdirSync(logDir, { recursive: true });
    }
    this.logFilePath = path.join(logDir, `hotspot_optimization_${Date.now()}.log`);
    this.log(`=== HOTSPOT OPTIMIZATION LOG ===`);
    this.log(`Started at: ${new Date().toISOString()}\n`);
  }

  private log(message: string) {
    const timestamp = new Date().toISOString();
    const logMessage = `[${timestamp}] ${message}\n`;
    fs.appendFileSync(this.logFilePath, logMessage);
    console.log(message); // Also log to console for real-time monitoring
  }

  /**
   * Check if hotspot operating hours allow visit during the specified time window.
   * PHP: checkHOTSPOTOPERATINGHOURS() in sql_functions.php line 10388-10429
   * 
   * PHP Logic (line 10419-10423):
   * if (($start_timestamp >= $operating_start_timestamp) && ($end_timestamp <= $operating_end_timestamp))
   * 
   * Returns true if BOTH start AND end time fall within THE SAME operating hours window.
   * Does NOT allow waiting - wait logic is handled separately in includeHotspotInItinerary (lines 15169-15240)
   */
  private async checkHotspotOperatingHours(
    tx: any,
    hotspotId: number,
    dayOfWeek: number,
    visitStartTime: string,
    visitEndTime: string,
  ): Promise<boolean> {
    // Fetch timing records for this hotspot on this day
    const timingRecords = await tx.dvi_hotspot_timing.findMany({
      where: {
        hotspot_ID: hotspotId,
        hotspot_timing_day: dayOfWeek,
        status: 1,
        deleted: 0,
      },
      select: {
        hotspot_start_time: true,
        hotspot_end_time: true,
        hotspot_open_all_time: true,
      },
    });

    if (!timingRecords || timingRecords.length === 0) {
      // No timing records = not open on this day
      return false;
    }

    // Check if open all time
    const openAllTime = timingRecords.some((t: any) => t.hotspot_open_all_time === 1);
    if (openAllTime) {
      return true;
    }

    // Convert visit times to comparable format (seconds since midnight)
    const visitStartSeconds = timeToSeconds(visitStartTime);
    const visitEndSeconds = timeToSeconds(visitEndTime);

    // PHP Logic: Loop through each set of operating hours
    // Check if BOTH start AND end fall within THE SAME window (no waiting allowed here)
    for (const timing of timingRecords) {
      if (!timing.hotspot_start_time || !timing.hotspot_end_time) continue;

      // IMPORTANT: Convert to UTC like route times (database stores as UTC timestamps)
      const openTime = timing.hotspot_start_time instanceof Date
        ? `${String(timing.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(timing.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}:${String(timing.hotspot_start_time.getUTCSeconds()).padStart(2, '0')}`
        : String(timing.hotspot_start_time);
      const closeTime = timing.hotspot_end_time instanceof Date
        ? `${String(timing.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(timing.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}:${String(timing.hotspot_end_time.getUTCSeconds()).padStart(2, '0')}`
        : String(timing.hotspot_end_time);

      const openSeconds = timeToSeconds(openTime);
      const closeSeconds = timeToSeconds(closeTime);

      this.log(`[Timeline] Hotspot ${hotspotId} check window: ${openTime}-${closeTime}, visit: ${visitStartTime}-${visitEndTime}`);

      // PHP line 10419-10423: Both start >= open AND end <= close
      if (visitStartSeconds >= openSeconds && visitEndSeconds <= closeSeconds) {
        this.log(`[Timeline] Hotspot ${hotspotId} operating hours OK - entire visit ${visitStartTime}-${visitEndTime} fits in ${openTime}-${closeTime}`);
        return true;
      }
    }

    // Visit doesn't fit in any single operating window
    // PHP handles waiting separately (not in this function)
    this.log(`[Timeline] Hotspot ${hotspotId} operating hours check FAILED - visit ${visitStartTime}-${visitEndTime} doesn't fit in any window`);
    return false;
  }

  /**
   * Main orchestrator for one plan.
   * Returns in-memory arrays that hotspot-engine.service.ts will insert.
   */
  async buildTimelineForPlan(
    tx: Tx,
    planId: number,
  ): Promise<{ hotspotRows: HotspotDetailRow[]; parkingRows: ParkingChargeRow[] }> {
    const plan = (await (tx as any).dvi_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: planId, deleted: 0 },
    })) as PlanHeader | null;

    if (!plan) {
      return { hotspotRows: [], parkingRows: [] };
    }

    const routes = (await (tx as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0, status: 1 },
      orderBy: [
        { itinerary_route_date: "asc" },
        { itinerary_route_ID: "asc" },
      ],
    })) as RouteRow[];

    if (!routes.length) {
      return { hotspotRows: [], parkingRows: [] };
    }

    const hotspotRows: HotspotDetailRow[] = [];
    const parkingRows: ParkingChargeRow[] = [];

    // Track hotspots already added to THIS plan during rebuild to avoid duplicates
    const addedHotspotIds = new Set<number>();

    // TODO (later): pass real user id from controller/service.
    const createdByUserId = 1;

    for (const route of routes) {
      // Determine if this is the last route BEFORE processing
      const isLastRoute = await this.isLastRouteOfPlan(
        tx,
        planId,
        route.itinerary_route_ID,
      );
      
      // PHP BEHAVIOR: Last route starts at order 2 (no refreshment, order starts at 2)
      let order = isLastRoute ? 2 : 1;
      
      // Convert Date objects to HH:MM:SS time strings
      // IMPORTANT: Use UTC methods because database stores times as UTC timestamps
      const routeStartTime: string = typeof route.route_start_time === 'string' 
        ? route.route_start_time 
        : route.route_start_time && typeof route.route_start_time === 'object'
        ? `${String((route.route_start_time as any).getUTCHours()).padStart(2, '0')}:${String((route.route_start_time as any).getUTCMinutes()).padStart(2, '0')}:${String((route.route_start_time as any).getUTCSeconds()).padStart(2, '0')}`
        : '09:00:00';
        
      const routeEndTime: string = typeof route.route_end_time === 'string'
        ? route.route_end_time
        : route.route_end_time && typeof route.route_end_time === 'object'
        ? `${String((route.route_end_time as any).getUTCHours()).padStart(2, '0')}:${String((route.route_end_time as any).getUTCMinutes()).padStart(2, '0')}:${String((route.route_end_time as any).getUTCSeconds()).padStart(2, '0')}`
        : '18:00:00';
      
      this.log(`[Timeline] Route ${route.itinerary_route_ID} (${route.location_name} → ${route.next_visiting_location}): routeStartTime=${routeStartTime}, routeEndTime=${routeEndTime}, direct_to_next=${(route as any).direct_to_next_visiting_place || 0}`);
      
      let currentTime = routeStartTime;
      let routeEndSeconds = timeToSeconds(routeEndTime);
      
      // Handle overnight routes: if end time < start time, add 24 hours to end
      const routeStartSeconds = timeToSeconds(routeStartTime);
      if (routeEndSeconds < routeStartSeconds) {
        routeEndSeconds += 86400; // Add 24 hours in seconds
      }

      // Maintain current logical location name for distance calculations.
      // Start with the route's location_name (same as PHP "route start city").
      let currentLocationName: string =
        (route.location_name as string) ||
        (route.next_visiting_location as string) ||
        (plan.departure_location as string) ||
        "";
      
      // Get starting coordinates from stored_locations using location_id (PHP: getITINEARYROUTE_DETAILS + getSTOREDLOCATIONDETAILS)
      // PHP line 1108-1109: $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
      let currentCoords: { lat: number; lon: number } | undefined = undefined;
      
      if (route.location_id) {
        const storedLoc = await (tx as any).dvi_stored_locations?.findFirst({
          where: {
            location_ID: Number(route.location_id),
            deleted: 0,
            status: 1,
          },
        });
        
        if (storedLoc) {
          currentCoords = {
            lat: Number(storedLoc.source_location_lattitude ?? 0),
            lon: Number(storedLoc.source_location_longitude ?? 0),
          };
          this.log(
            `[Timeline] Route ${route.itinerary_route_ID} starting coords from location_id ${route.location_id}: (${currentCoords.lat}, ${currentCoords.lon})`,
          );
        } else {
          this.log(
            `[Timeline] Route ${route.itinerary_route_ID} location_id ${route.location_id} not found in stored_locations`,
          );
        }
      }

      // 1) ADD REFRESHMENT BREAK (PHP line 969-993)
      // PHP adds 1-hour refreshment at route start EXCEPT for last route
      // Last route starts directly with hotspots (order 2) and skips refreshment ROW
      // BUT PHP still advances currentTime by buffer amount for last route (without creating row)
      if (!isLastRoute) {
        const globalSettings = await (tx as any).dvi_global_settings?.findFirst({
          where: { status: 1, deleted: 0 },
          select: { itinerary_common_buffer_time: true },
        });
        
        const bufferTime = globalSettings?.itinerary_common_buffer_time
          ? (globalSettings.itinerary_common_buffer_time instanceof Date
            ? `${String(globalSettings.itinerary_common_buffer_time.getUTCHours()).padStart(2, '0')}:${String(globalSettings.itinerary_common_buffer_time.getUTCMinutes()).padStart(2, '0')}:${String(globalSettings.itinerary_common_buffer_time.getUTCSeconds()).padStart(2, '0')}`
            : String(globalSettings.itinerary_common_buffer_time))
          : '01:00:00';
        
        const bufferSeconds = timeToSeconds(bufferTime);
        const refreshmentEndTime = addSeconds(currentTime, bufferSeconds);
        const refreshmentEndSeconds = timeToSeconds(refreshmentEndTime);
        
        // Only add refreshment if it fits within route time
        if (refreshmentEndSeconds <= routeEndSeconds) {
          // PHP line 978: refreshment fields - use TimeConverter to match other builders
          hotspotRows.push({
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            item_type: 1,
            hotspot_order: order++,
            hotspot_traveling_time: TimeConverter.toDate(bufferTime),
            hotspot_start_time: TimeConverter.toDate(currentTime),
            hotspot_end_time: TimeConverter.toDate(refreshmentEndTime),
            createdby: createdByUserId,
            status: 1,
            deleted: 0,
          });
          
          this.log(
            `[Timeline] Route ${route.itinerary_route_ID} - Added refreshment break: ${currentTime} - ${refreshmentEndTime}`,
          );
          
          // Update current time after refreshment
          currentTime = refreshmentEndTime;
        } else {
          this.log(
            `[Timeline] Route ${route.itinerary_route_ID} - Skipping refreshment (not enough time): ${refreshmentEndTime} > ${routeEndTime}`,
          );
        }
      } else {
        // PHP BEHAVIOR: Last route doesn't create refreshment ROW but still advances time
        const globalSettings = await (tx as any).dvi_global_settings?.findFirst({
          where: { status: 1, deleted: 0 },
          select: { itinerary_common_buffer_time: true },
        });
        
        const bufferTime = globalSettings?.itinerary_common_buffer_time
          ? (globalSettings.itinerary_common_buffer_time instanceof Date
            ? `${String(globalSettings.itinerary_common_buffer_time.getUTCHours()).padStart(2, '0')}:${String(globalSettings.itinerary_common_buffer_time.getUTCMinutes()).padStart(2, '0')}:${String(globalSettings.itinerary_common_buffer_time.getUTCSeconds()).padStart(2, '0')}`
            : String(globalSettings.itinerary_common_buffer_time))
          : '01:00:00';
        
        const bufferSeconds = timeToSeconds(bufferTime);
        currentTime = addSeconds(currentTime, bufferSeconds);
        
        this.log(
          `[Timeline] Route ${route.itinerary_route_ID} - Skipping refreshment row (last route) but advancing time by ${bufferTime} to ${currentTime}`,
        );
      }

      // 2) SELECTED HOTSPOTS FOR THIS ROUTE
      const selectedHotspots = await this.fetchSelectedHotspotsForRoute(
        tx,
        planId,
        route.itinerary_route_ID,
      );

      this.log(
        `[Timeline] Route ${route.itinerary_route_ID}: Selected ${selectedHotspots.length} hotspots`,
      );

      this.log(
        `[Timeline] Route ${route.itinerary_route_ID} - currentTime: ${currentTime}, routeEndTime: ${routeEndTime}, routeEndSeconds: ${routeEndSeconds}`,
      );

      // Build travel + hotspot segments in order.
      for (const sh of selectedHotspots) {
        // stop if we have run out of route time
        let currentSeconds = timeToSeconds(currentTime);
        // Handle overnight: if current time < start time, add 24 hours
        if (currentSeconds < routeStartSeconds) {
          currentSeconds += 86400;
        }
        
        this.log(
          `[Timeline] Hotspot ${sh.hotspot_ID} - currentSeconds: ${currentSeconds}, routeEndSeconds: ${routeEndSeconds}`,
        );
        
        if (currentSeconds >= routeEndSeconds) {
          this.log(
            `[Timeline] Stopping hotspots for route ${route.itinerary_route_ID} because currentTime ${currentTime} (${currentSeconds}s) >= route_end_time ${routeEndTime} (${routeEndSeconds}s)`,
          );
          break;
        }

        this.log(
          `[Timeline] Processing hotspot ${sh.hotspot_ID} for route ${route.itinerary_route_ID}`,
        );

        // PHP CHECK: Skip if hotspot already added to THIS PLAN (any previous route in this rebuild)
        // Line 15159 in sql_functions.php: check_hotspot_already_added_the_itineary_plan
        if (addedHotspotIds.has(sh.hotspot_ID)) {
          this.log(
            `[Timeline] Skipping hotspot ${sh.hotspot_ID} - already added to plan ${planId}`,
          );
          continue;
        }

        // 2.a) Get hotspot details including coordinates
        const hotspotData = await tx.dvi_hotspot_place.findUnique({
          where: { hotspot_ID: sh.hotspot_ID },
          select: {
            hotspot_location: true,
            hotspot_latitude: true,
            hotspot_longitude: true,
            hotspot_duration: true,
          },
        });

        if (!hotspotData) {
          this.log(`[Timeline] Hotspot ${sh.hotspot_ID} not found, skipping`);
          continue;
        }

        const hotspotLocationName = hotspotData.hotspot_location as string || currentLocationName;
        const hotspotDuration = hotspotData.hotspot_duration || '01:00:00';
        const destCoords = {
          lat: Number(hotspotData.hotspot_latitude ?? 0),
          lon: Number(hotspotData.hotspot_longitude ?? 0),
        };

        this.log(
          `[Timeline] Hotspot ${sh.hotspot_ID} coords: lat=${destCoords.lat}, lon=${destCoords.lon}`,
        );
        
        // If this is the first hotspot and we don't have starting coords,
        // assume minimal travel time (starting near the first hotspot)
        if (!currentCoords) {
          this.log(
            `[Timeline] First hotspot ${sh.hotspot_ID} - assuming starting near this location`,
          );
          // Set currentCoords to first hotspot location for subsequent calculations
          currentCoords = destCoords;
        }

        // 2.b) Calculate travel time using coordinates (matches PHP)
        const travelTimeToHotspot = await this.calculateTravelTimeWithCoords(
          tx,
          currentLocationName,
          hotspotLocationName,
          currentCoords, // Use tracked current coordinates
          destCoords,
        );
        const travelDurationSeconds = timeToSeconds(travelTimeToHotspot);
        const timeAfterTravel = addSeconds(currentTime, travelDurationSeconds);

        // Get hotspot duration
        const hotspotDurationSeconds = timeToSeconds(hotspotDuration);
        const timeAfterHotspot = addSeconds(
          timeAfterTravel,
          hotspotDurationSeconds,
        );

        // PHP CHECK: Validate operating hours (PHP sql_functions.php line 15121)
        // Must check BEFORE checking route end time
        const phpDow = route.itinerary_route_date
          ? ((new Date(route.itinerary_route_date).getDay() + 6) % 7)
          : 0;
        
        this.log(
          `[Timeline] Checking operating hours for hotspot ${sh.hotspot_ID}: visit window ${timeAfterTravel}-${timeAfterHotspot} on day ${phpDow}`,
        );
        
        const operatingHoursOk = await this.checkHotspotOperatingHours(
          tx,
          sh.hotspot_ID,
          phpDow,
          timeAfterTravel, // Visit starts after travel
          timeAfterHotspot, // Visit ends after duration
        );
        
        if (!operatingHoursOk) {
          this.log(
            `[Timeline] Hotspot ${sh.hotspot_ID} not open during visit window ${timeAfterTravel}-${timeAfterHotspot} on day ${phpDow}, skipping`,
          );
          continue; // Skip this hotspot, try next one
        }
        
        this.log(
          `[Timeline] Hotspot ${sh.hotspot_ID} operating hours OK`,
        );

        // PHP CHECK: If hotspot END time exceeds route_end_time significantly, skip
        // PHP allows some buffer (observed: hotspots can extend 2-3 hours past route end)
        let hotspotEndSeconds = timeToSeconds(timeAfterHotspot);
        // Handle overnight: if hotspot end time < start time, add 24 hours
        if (hotspotEndSeconds < routeStartSeconds) {
          hotspotEndSeconds += 86400;
        }
        
        const bufferSeconds = 4 * 3600; // Allow 4 hours buffer past route_end_time (PHP behavior observed)
        if (hotspotEndSeconds > routeEndSeconds + bufferSeconds) {
          this.log(
            `[Timeline] Hotspot ${sh.hotspot_ID} would end at ${timeAfterHotspot} (${hotspotEndSeconds}s) (exceeds route_end_time ${routeEndTime} (${routeEndSeconds}s) + buffer), skipping`,
          );
          continue; // Try next hotspot instead of breaking
        }

        // 2.c) Build TRAVEL SEGMENT (item_type = 3)
        // PHP BEHAVIOR: Travel and Visit segments share the SAME hotspot_order
        const currentOrder = order;
        
        const travelLocationType = this.getTravelLocationType(
          currentLocationName,
          hotspotLocationName,
        );
        this.log(
          `[Timeline] Travel type for "${currentLocationName}" -> "${hotspotLocationName}": ${travelLocationType} (1=local, 2=outstation)`,
        );
        const { row: travelRow, nextTime: tToHotspot } =
          await this.travelBuilder.buildTravelSegment(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: currentOrder, // Use current order without incrementing
            item_type: 3, // Site Seeing Traveling
            travelLocationType,
            startTime: currentTime,
            userId: createdByUserId,
            sourceLocationName: currentLocationName,
            destinationLocationName: hotspotLocationName,
            hotspotId: sh.hotspot_ID, // PHP sets hotspot_ID for item_type=3
            sourceCoords: currentCoords, // Use current location coordinates
            destCoords: destCoords,
          });

        hotspotRows.push(travelRow);
        currentTime = tToHotspot;
        currentLocationName = hotspotLocationName;
        currentCoords = destCoords; // Update to hotspot coordinates

        // 2.d) Build HOTSPOT STAY SEGMENT (item_type = 4)
        this.log(
          `[Timeline] Building hotspot row for hotspot_ID=${sh.hotspot_ID}`,
        );
        const { row: hotspotRow, nextTime: tAfterHotspot } =
          await this.hotspotBuilder.build(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: currentOrder, // Use same order as travel segment
            hotspotId: sh.hotspot_ID,
            startTime: currentTime,
            userId: createdByUserId,
            totalAdult: plan.total_adult,
            totalChildren: plan.total_children,
            totalInfants: plan.total_infants,
            nationality: plan.nationality,
            itineraryPreference: plan.itinerary_preference,
          });

        this.log(
          `[Timeline] Built hotspot row: item_type=${hotspotRow.item_type}, hotspot_ID=${hotspotRow.hotspot_ID}, order=${hotspotRow.hotspot_order}`,
        );

        hotspotRows.push(hotspotRow);
        
        // Mark this hotspot as added to prevent duplicates in subsequent routes
        addedHotspotIds.add(sh.hotspot_ID);
        
        // NOW increment order after both travel and visit are added
        order++;
        
        currentTime = tAfterHotspot;
        // currentLocationName remains at the hotspot.

        // 2.d) PARKING CHARGE ROW for this hotspot (if a master row exists)
        const parking = await this.parkingBuilder.buildForHotspot(tx, {
          planId,
          routeId: route.itinerary_route_ID,
          hotspotId: sh.hotspot_ID,
          userId: createdByUserId,
        });

        if (parking) {
          parkingRows.push(parking);
        }
      }

      // 3) TRAVEL TO HOTEL (item_type = 5)
      // In PHP this uses the chosen hotel for that route/date.
      // PHP BEHAVIOR: Hotel travel (type 5) and hotel closing (type 6) share the same order
      // PHP BEHAVIOR: Last route SKIPS hotel rows (no ToHotel, no AtHotel)
      if (!isLastRoute) {
        const hotelOrder = order;
        
        const hotelLocationName =
          (await this.getHotelLocationNameForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
          )) ||
          (route.next_visiting_location as string) ||
          currentLocationName;

        const { row: toHotelRow, nextTime: tAfterHotel } =
          await this.hotelBuilder.buildToHotel(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: hotelOrder,
            startTime: currentTime,
            travelLocationType: 1, // TODO: local vs outstation if needed
            userId: createdByUserId,
            sourceLocationName: currentLocationName,
            destinationLocationName: hotelLocationName,
          });

        hotspotRows.push(toHotelRow);
        currentTime = tAfterHotel;
        currentLocationName = hotelLocationName;

        // 4) RETURN / CLOSING ROW FOR HOTEL (item_type = 6)
        // PHP usually creates a closing row with 0 distance/time.
        const { row: closeHotelRow, nextTime: tClose } =
          await this.hotelBuilder.buildReturnToHotel(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: hotelOrder, // Use same order as hotel travel
            startTime: currentTime,
            userId: createdByUserId,
          });

        hotspotRows.push(closeHotelRow);
        order++; // Increment order after both hotel rows added
        currentTime = tClose;
        // currentLocationName stays at hotel.
      } else {
        this.log(
          `[Timeline] Route ${route.itinerary_route_ID} - Skipping hotel rows (last route - PHP behavior)`,
        );
      }

      // 5) LAST ROUTE ONLY → RETURN TO DEPARTURE LOCATION (item_type = 7)

      if (isLastRoute) {
        const { row: returnRow, nextTime: tAfterReturn } =
          await this.returnBuilder.buildReturnToDeparture(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: order++,
            startTime: currentTime,
            travelLocationType: 2, // outstation by default
            userId: createdByUserId,
            currentLocationName,
          });

        hotspotRows.push(returnRow);
        currentTime = tAfterReturn;
        currentLocationName = plan.departure_location as string;
      }
    }

    this.log(
      `[Timeline] Returning ${hotspotRows.length} hotspot rows and ${parkingRows.length} parking rows`,
    );
    this.log(
      `[Timeline] Hotspot rows item_types: ${hotspotRows.map((r) => r.item_type).join(", ")}`,
    );
    this.log(
      `[Timeline] Hotspot rows hotspot_IDs: ${hotspotRows.map((r) => r.hotspot_ID).join(", ")}`,
    );

    this.log(`\n=== LOG FILE SAVED TO: ${this.logFilePath} ===\n`);

    return { hotspotRows, parkingRows };
  }

  /**
   * Decide if this is the last route of the plan (used for item_type = 7).
   */
  private async isLastRouteOfPlan(
    tx: Tx,
    planId: number,
    routeId: number,
  ): Promise<boolean> {
    const last = await (tx as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_plan_ID: planId, deleted: 0 },
      orderBy: [
        { itinerary_route_date: "desc" },
        { itinerary_route_ID: "desc" },
      ],
    });

    if (!last) return false;
    return last.itinerary_route_ID === routeId;
  }

  /**
   * Fetch available hotspots for a given route location.
   *
   * In PHP: the `includeHotspotInItinerary()` function is called for each hotspot
   * that is available at the route's location, in priority order.
   *
   * We replicate this by:
   * 1. Get the route's location_name or next_visiting_location
   * 2. Query dvi_hotspot_place for hotspots matching that location (by name)
   * 3. Return them sorted by priority
   *
   * NOTE: In the schema, dvi_hotspot_place doesn't have a location_id field.
   * Instead, it has hotspot_location (text) which must be matched against
   * the route's location_name or next_visiting_location.
   */
  private async fetchSelectedHotspotsForRoute(
    tx: Tx,
    planId: number,
    routeId: number,
  ): Promise<SelectedHotspot[]> {
    try {
      // 1) Load route context (dates + locations)
      const route = (await (tx as any).dvi_itinerary_route_details?.findFirst({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          deleted: 0,
          status: 1,
        },
      })) as RouteRow | null;

      if (!route) {
        this.log(
          `[fetchSelectedHotspots] Route not found for plan ${planId}, route ${routeId}`,
        );
        return [];
      }

      const targetLocation = (route.location_name as string) || "";
      const nextLocation = (route.next_visiting_location as string) || "";

      if (!targetLocation && !nextLocation) {
        this.log(
          `[fetchSelectedHotspots] No location name found for route ${routeId}`,
        );
        return [];
      }

      // PHP uses day-of-week filtering via dvi_hotspot_timing (date('N')-1 => Monday=0)
      const routeDate = route.itinerary_route_date
        ? new Date(route.itinerary_route_date)
        : null;
      const phpDow = routeDate
        ? ((routeDate.getDay() + 6) % 7) // JS: Sunday=0; PHP: Monday=0, Sunday=6
        : undefined;

      this.log(
        `[fetchSelectedHotspots] route ${routeId} target="${targetLocation}" next="${nextLocation}" dow=${phpDow}`,
      );

      // 2) Preload hotspot timings for this day (if available)
      let allowedHotspotIds: Set<number> | null = null;
      if (phpDow !== undefined) {
        const timingRows = await (tx as any).dvi_hotspot_timing?.findMany({
          where: {
            hotspot_timing_day: phpDow,
            deleted: 0,
            status: 1,
          },
        });
        allowedHotspotIds = new Set(
          (timingRows || []).map((t: any) => Number(t.hotspot_ID ?? 0)).filter(Boolean),
        );
      }

      // 3) Fetch all active hotspots (don't sort yet - will sort after calculating distances)
      const allHotspots =
        (await (tx as any).dvi_hotspot_place?.findMany({
          where: {
            deleted: 0,
            status: 1,
          },
        })) || [];

      this.log(
        `[fetchSelectedHotspots] Found ${allHotspots.length} total active hotspots`,
      );

      const targetLower = targetLocation.toLowerCase();
      const nextLower = nextLocation.toLowerCase();
      const directToNextVisitingPlace = (route as any).direct_to_next_visiting_place || 0;

      this.log(
        `[fetchSelectedHotspots] direct_to_next_visiting_place=${directToNextVisitingPlace}`,
      );
      this.log(
        `[fetchSelectedHotspots] source="${targetLocation}", destination="${nextLocation}"`,
      );

      // Get starting location coordinates from dvi_stored_locations (like PHP)
      let startLat = 0;
      let startLon = 0;
      
      // Try to fetch from stored_locations using location_name
      const storedLocation = await (tx as any).dvi_stored_locations?.findFirst({
        where: {
          OR: [
            { source_location: { contains: targetLocation } },
            { destination_location: { contains: targetLocation } },
          ],
          deleted: 0,
          status: 1,
        },
      });
      
      if (storedLocation) {
        // Use source coordinates (PHP uses source for starting point)
        startLat = Number(storedLocation.source_location_lattitude ?? 0);
        startLon = Number(storedLocation.source_location_longitude ?? 0);
        this.log(
          `[fetchSelectedHotspots] Starting coords from stored_locations: (${startLat}, ${startLon})`,
        );
      } else {
        this.log(
          `[fetchSelectedHotspots] No stored_locations found for "${targetLocation}", distances will be 0`,
        );
      }

      // PHP LINE 1003-1011: Filter includes source location when direct_to_next_visiting_place != 1
      // Categorize hotspots like PHP does (lines 1197-1210)
      let sourceLocationHotspots: any[] = [];
      const destinationHotspots: any[] = [];
      const viaRouteHotspots: any[] = [];

      // Helper function to match location like PHP's containsLocation()
      // PHP splits hotspot_location by '|' and checks if target is in the array (exact match after normalization)
      const containsLocation = (hotspotLocation: string | null, targetLocation: string | null): boolean => {
        if (!hotspotLocation || !targetLocation) return false;
        
        // Split by pipe and normalize (trim, lowercase)
        const hotspotParts = hotspotLocation.split('|').map(p => p.trim().toLowerCase());
        const normalizedTarget = targetLocation.trim().toLowerCase();
        
        // Check if normalized target exists in the array (exact match, not substring)
        return hotspotParts.includes(normalizedTarget);
      };

      for (const h of allHotspots) {
        // Check if timing allows this hotspot on this day
        if (allowedHotspotIds && !allowedHotspotIds.has(Number(h.hotspot_ID ?? 0))) {
          this.log(
            `[fetchSelectedHotspots] Skipping hotspot ${h.hotspot_ID} (${h.hotspot_name}) - not open on day ${phpDow}`,
          );
          continue;
        }

        // Calculate distance from starting location to this hotspot
        const hsLat = Number(h.hotspot_latitude ?? 0);
        const hsLon = Number(h.hotspot_longitude ?? 0);
        let distance = 0;
        
        if (startLat && startLon && hsLat && hsLon) {
          const earthRadius = 6371;
          const dLat = ((hsLat - startLat) * Math.PI) / 180;
          const dLon = ((hsLon - startLon) * Math.PI) / 180;
          const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos((startLat * Math.PI) / 180) *
              Math.cos((hsLat * Math.PI) / 180) *
              Math.sin(dLon / 2) *
              Math.sin(dLon / 2);
          const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
          distance = earthRadius * c * 1.5;
        }

        const hotspotWithDistance = { ...h, hotspot_distance: distance };

        // PHP containsLocation() - exact match in pipe-delimited list, not substring
        const matchesSource = containsLocation(h.hotspot_location as string, targetLocation);
        const matchesDestination = containsLocation(h.hotspot_location as string, nextLocation);
        
        // Check if location is PRIMARY (first in pipe-delimited list) and if ONLY location
        const locationParts = (h.hotspot_location || '').split('|').map((p: string) => p.trim().toLowerCase());
        const primaryLocation = locationParts[0] || '';
        const isOnlyLocation = locationParts.length === 1;
        const isPrimarySource = primaryLocation === (targetLocation || '').trim().toLowerCase();
        const isPrimaryDestination = primaryLocation === (nextLocation || '').trim().toLowerCase();

        // PHP BEHAVIOR: Add to source and/or destination based on location match
        // CRITICAL PHP LOGIC: Hotspots can be in BOTH lists (will be deduplicated later)
        // However, if a hotspot matches destination as PRIMARY, prefer adding it to DESTINATION only
        // to maintain proper concatenation order (SOURCE + DESTINATION)
        const shouldAddToSource = matchesSource && !(matchesDestination && isPrimaryDestination);
        const shouldAddToDestination = matchesDestination;

        if (shouldAddToSource) {
          sourceLocationHotspots.push({ ...hotspotWithDistance, isPrimarySource, isOnlyLocation });
          this.log(
            `[fetchSelectedHotspots] Hotspot ${h.hotspot_ID} "${h.hotspot_name}" → SOURCE (matches "${targetLocation}"${isPrimarySource ? ' - PRIMARY' : ''}${isOnlyLocation ? ' - ONLY' : ''})`,
          );
        }
        
        if (shouldAddToDestination) {
          destinationHotspots.push({ ...hotspotWithDistance, isPrimaryDestination, isOnlyLocation });
          this.log(
            `[fetchSelectedHotspots] Hotspot ${h.hotspot_ID} "${h.hotspot_name}" → DESTINATION (matches "${nextLocation}"${isPrimaryDestination ? ' - PRIMARY' : ''}${isOnlyLocation ? ' - ONLY' : ''})`,
          );
        }

        // TODO: via_route_hotspots matching via locations (for future implementation)
      }

      // PHP sortHotspots() for each category  
      // PHP BEHAVIOR: Priority DESC (higher number = higher priority), then multi-location, then matches-both, then distance
      const sortHotspots = (hotspots: any[], sourceLocation: string = '', destLocation: string = '') => {
        hotspots.sort((a: any, b: any) => {
          const aPriority = Number(a.hotspot_priority ?? 0);
          const bPriority = Number(b.hotspot_priority ?? 0);
          
          // Priority-0 hotspots go last
          if (aPriority === 0 && bPriority !== 0) return 1;
          if (aPriority !== 0 && bPriority === 0) return -1;
          
          // Then by priority DESC (higher number = higher priority, comes first)
          if (aPriority !== bPriority) return bPriority - aPriority;
          
          // For same priority, prefer hotspots with multiple locations
          const aLocCount = (a.hotspot_location || '').split('|').length;
          const bLocCount = (b.hotspot_location || '').split('|').length;
          if (aLocCount !== bLocCount) return bLocCount - aLocCount; // More locations first
          
          // For same location count, prefer hotspots matching BOTH source and destination
          if (sourceLocation && destLocation) {
            const aMatchesBoth = containsLocation(a.hotspot_location, sourceLocation) && containsLocation(a.hotspot_location, destLocation);
            const bMatchesBoth = containsLocation(b.hotspot_location, sourceLocation) && containsLocation(b.hotspot_location, destLocation);
            if (aMatchesBoth && !bMatchesBoth) return -1;
            if (!aMatchesBoth && bMatchesBoth) return 1;
          }
          
          // Finally by distance (ASC - closer first)
          return a.hotspot_distance - b.hotspot_distance;
        });
      };

      sortHotspots(sourceLocationHotspots, targetLocation, nextLocation);
      sortHotspots(destinationHotspots, targetLocation, nextLocation);
      sortHotspots(viaRouteHotspots, targetLocation, nextLocation);

      // PHP BEHAVIOR: For direct=0 routes, filter out priority-0 SOURCE hotspots
      // PHP ajax_latest_manage_itineary_opt.php: Only non-zero priority source hotspots are used
      // This prevents low-value attractions from being added when traveling between cities
      if (directToNextVisitingPlace === 0) {
        const beforeFilter = sourceLocationHotspots.length;
        sourceLocationHotspots = sourceLocationHotspots.filter((h: any) => {
          const priority = Number(h.hotspot_priority ?? 0);
          return priority > 0;
        });
        const afterFilter = sourceLocationHotspots.length;
        if (beforeFilter > afterFilter) {
          this.log(
            `[fetchSelectedHotspots] Filtered ${beforeFilter - afterFilter} priority-0 SOURCE hotspots (${beforeFilter} → ${afterFilter})`,
          );
        }
      }

      this.log(
        `[fetchSelectedHotspots] Categorized: source=${sourceLocationHotspots.length}, destination=${destinationHotspots.length}, via=${viaRouteHotspots.length}`,
      );

      // Detect airport arrival routes: Airport → City
      const isAirportArrival = targetLocation.toLowerCase().includes('airport') && 
                               !nextLocation.toLowerCase().includes('airport');
      
      if (isAirportArrival) {
        this.log(`[fetchSelectedHotspots] AIRPORT ARRIVAL DETECTED: "${targetLocation}" → "${nextLocation}"`);
      }

      // PHP LINE 1261 or 1338: Process hotspots based on direct_to_next_visiting_place
      let matchingHotspots: any[] = [];
      
      if (directToNextVisitingPlace === 1) {
        // PHP LINE 1262-1336: Process via_route_hotspots, then destination_hotspots
        this.log(`[fetchSelectedHotspots] direct_to_next_visiting_place=1: Processing VIA → DESTINATION`);
        matchingHotspots = [...viaRouteHotspots, ...destinationHotspots];
      } else if (isAirportArrival) {
        // SPECIAL CASE: Airport arrival routes should use DESTINATION hotspots (city), not SOURCE (airport)
        this.log(`[fetchSelectedHotspots] direct_to_next_visiting_place=0 + AIRPORT ARRIVAL: Processing DESTINATION → VIA`);
        matchingHotspots = [...destinationHotspots, ...viaRouteHotspots];
      } else {
        // PHP BEHAVIOR: For city-to-city routes with direct=0, use SOURCE + DESTINATION hotspots
        // Route 179 (Chennai → Pondicherry) selects hotspots from BOTH locations
        this.log(`[fetchSelectedHotspots] direct_to_next_visiting_place=0: Processing SOURCE → DESTINATION → VIA`);
        matchingHotspots = [...sourceLocationHotspots, ...destinationHotspots, ...viaRouteHotspots];
      }

      this.log(
        `[fetchSelectedHotspots] Matched ${matchingHotspots.length} hotspots after PHP ordering`,
      );

      // Log first few matched hotspots with their priorities and distances
      if (matchingHotspots.length > 0) {
        this.log(
          `[fetchSelectedHotspots] First 5 hotspots (sorted): ${matchingHotspots.slice(0, 5).map((h: any) => 
            `ID=${h.hotspot_ID} "${h.hotspot_name}" priority=${h.hotspot_priority || 0} distance=${h.hotspot_distance.toFixed(2)}km`
          ).join(', ')}`,
        );
      }

      // De-duplicate by hotspot_ID and keep first occurrence
      const seen = new Set<number>();
      const uniqueHotspots: any[] = [];
      for (const h of matchingHotspots) {
        const id = Number(h.hotspot_ID ?? 0) || 0;
        if (!id || seen.has(id)) continue;
        seen.add(id);
        uniqueHotspots.push(h);
      }

      this.log(
        `[fetchSelectedHotspots] Matched ${uniqueHotspots.length} hotspots for route ${routeId}`,
      );

      return uniqueHotspots.map((h: any, index: number) => ({
        hotspot_ID: Number(h.hotspot_ID ?? 0) || 0,
        display_order: Number(h.hotspot_priority ?? index + 1) || index + 1,
      }));
    } catch (err) {
      console.error("[fetchSelectedHotspots] Error:", err);
      return [];
    }
  }

  /**
   * Get the "location name" (city) of a hotspot.
   *
   * In PHP, this is whatever you used in getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION
   * when travelling to a hotspot.
   *
   * TODO: Adjust the field you return:
   *   - hotspot_location
   *   - hotspot_city
   *   - city
   *   - etc. depending on your dvi_hotspot_place schema.
   */
  private async getHotspotLocationName(
    tx: Tx,
    hotspotId: number,
  ): Promise<string | null> {
    if (!hotspotId) return null;

    const hs = await (tx as any).dvi_hotspot_place?.findFirst({
      where: { hotspot_ID: hotspotId, deleted: 0, status: 1 },
    });

    if (!hs) return null;

    return (
      hs.hotspot_location ??
      hs.hotspot_city ??
      hs.city ??
      hs.location_name ??
      null
    );
  }

  /**
   * Get the hotel city/location used for travel-to-hotel segment for a route.
   *
   * In PHP, this comes from your big hotel-selection query joining:
   *   dvi_itinerary_route_details + dvi_stored_locations + dvi_hotel + dvi_hotel_rooms
   *
   * TODO: Replace this placeholder with the real query and returned city field.
   */
  private async getHotelLocationNameForRoute(
    tx: Tx,
    planId: number,
    routeId: number,
  ): Promise<string | null> {
    // Example placeholder:
    //  - It tries to find a hotel row for this plan/route/date and returns
    //    hotel_city_name (adjust field names).
    const hotel = await (tx as any).dvi_itinerary_plan_hotel_details?.findFirst(
      {
        where: {
          itinerary_plan_id: planId,
          itinerary_route_id: routeId,
          deleted: 0,
          status: 1,
        },
      },
    );

    if (!hotel) return null;

    const h = hotel.hotel || hotel;

    return (
      h.hotel_city ??
      h.city ??
      h.hotel_location ??
      h.hotel_name ??
      null
    );
  }

  /**
   * Calculate travel time between two locations (matching PHP logic).
   * Returns HH:MM:SS format string.
   */
  private async calculateTravelTime(
    tx: Tx,
    sourceLocationName: string,
    destinationLocationName: string,
  ): Promise<string> {
    const distanceResult = await this.distanceHelper.fromSourceAndDestination(
      tx,
      sourceLocationName,
      destinationLocationName,
      1, // travelLocationType: 1 = local
    );
    
    // Return travel time + buffer time
    const totalSeconds = timeToSeconds(distanceResult.travelTime) + timeToSeconds(distanceResult.bufferTime);
    return addSeconds('00:00:00', totalSeconds);
  }

  private async calculateTravelTimeWithCoords(
    tx: Tx,
    sourceLocationName: string,
    destinationLocationName: string,
    sourceCoords?: { lat: number; lon: number },
    destCoords?: { lat: number; lon: number },
  ): Promise<string> {
    const travelLocationType = this.getTravelLocationType(
      sourceLocationName,
      destinationLocationName,
    );
    const distanceResult = await this.distanceHelper.fromSourceAndDestination(
      tx,
      sourceLocationName,
      destinationLocationName,
      travelLocationType,
      sourceCoords,
      destCoords,
    );
    
    // Return travel time + buffer time
    const totalSeconds = timeToSeconds(distanceResult.travelTime) + timeToSeconds(distanceResult.bufferTime);
    return addSeconds('00:00:00', totalSeconds);
  }

  /**
   * Determine travel location type (matches PHP getTravelLocationType)
   * @param startLocation - Starting location name (can contain pipe-separated values)
   * @param endLocation - Ending location name (can contain pipe-separated values)
   * @returns 1 if same location (local), 2 if different location (outstation)
   */
  private getTravelLocationType(
    startLocation: string,
    endLocation: string,
  ): 1 | 2 {
    const startLocations = startLocation.split('|').map((s) => s.trim());
    const endLocations = endLocation.split('|').map((e) => e.trim());

    // Check if any start location matches any end location
    for (const start of startLocations) {
      for (const end of endLocations) {
        if (start === end) {
          return 1; // Same location (local)
        }
      }
    }
    return 2; // Different location (outstation)
  }
}

// --- RECENT EDITS BELOW --- //