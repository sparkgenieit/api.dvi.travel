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
import { timeToSeconds, addSeconds, secondsToTime } from "./time.helper";
import { DistanceHelper } from "./distance.helper";
import { TimeConverter } from "./time-converter";
import * as fs from "fs";
import * as path from "path";

type Tx = Prisma.TransactionClient;

interface PlanHeader {
  itinerary_plan_ID: number;
  trip_start_date: Date;
  trip_end_date: Date;
  trip_start_date_and_time?: Date | null;
  trip_end_date_and_time?: Date | null;
  pick_up_date_and_time: Date;
  arrival_type: number;
  departure_type: number;
  entry_ticket_required: number;
  nationality: number;
  total_adult: number;
  total_children: number;
  total_infants: number;
  itinerary_preference: number;
  arrival_location?: string | null;
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
  hotspot_priority?: number;
}

export class TimelineBuilder {
  private readonly refreshmentBuilder = new RefreshmentBuilder();
  private readonly travelBuilder = new TravelSegmentBuilder();
  private readonly hotspotBuilder = new HotspotSegmentBuilder();
  private readonly hotelBuilder = new HotelTravelBuilder();
  private readonly returnBuilder = new ReturnSegmentBuilder();
  // Make parkingBuilder public so HotspotEngineService can use it for rebuilding parking charges
  public readonly parkingBuilder = new ParkingChargeBuilder();
  private readonly distanceHelper = new DistanceHelper();

  constructor() {
    // Logging removed for performance
  }

  /**
   * Normalize city names for comparison (single source of truth)
   * Removes airport, railway, station, etc. and normalizes to lowercase
   */
  private normalizeCityName(name: string): string {
    return String(name || "")
      .toLowerCase()
      .replace(/[.,()]/g, " ")
      .replace(/\b(international|domestic)\b/g, " ")
      .replace(/\b(airport|air\s*port|railway|rail|station|stn|junction|jn|central|egmore|terminus|bus\s*stand|stand)\b/g, " ")
      .replace(/\s+/g, " ")
      .trim();
  }

  /**
   * Check if hotspot operating hours allow visit during the specified time window.
   * PHP: checkHOTSPOTOPERATINGHOURS() in sql_functions.php line 10388-10429
   * 
   * PHP Logic (line 10419-10423):
   * if (($start_timestamp >= $operating_start_timestamp) && ($end_timestamp <= $operating_end_timestamp))
   * 
   * Returns object with:
   * - canVisitNow: true if BOTH start AND end time fall within THE SAME operating hours window
   * - nextWindowStart: start time of next available window (if canVisitNow is false)
   */
  private async checkHotspotOperatingHours(
    tx: any,
    hotspotId: number,
    dayOfWeek: number,
    visitStartTime: string,
    visitEndTime: string,
  ): Promise<{ canVisitNow: boolean; nextWindowStart: string | null }> {
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
        hotspot_closed: true,
      },
    });

    if (!timingRecords || timingRecords.length === 0) {
      // No timing records = not open on this day
      return { canVisitNow: false, nextWindowStart: null };
    }

    let nextWindowStart: string | null = null;
    const currentSeconds = timeToSeconds(visitStartTime);

    // Check if any timing window allows the full visit (start AND end within same window)
    for (const timing of timingRecords) {
      // Skip if hotspot is closed
      if (timing.hotspot_closed === 1) {
        continue;
      }
      
      // Open all time = always available
      if (timing.hotspot_open_all_time === 1) {
        return { canVisitNow: true, nextWindowStart: null };
      }
      
      // Get operating window times
      const operatingStart = timing.hotspot_start_time
        ? (timing.hotspot_start_time instanceof Date
          ? `${String(timing.hotspot_start_time.getUTCHours()).padStart(2, '0')}:${String(timing.hotspot_start_time.getUTCMinutes()).padStart(2, '0')}:${String(timing.hotspot_start_time.getUTCSeconds()).padStart(2, '0')}`
          : String(timing.hotspot_start_time))
        : '00:00:00';
      
      const operatingEnd = timing.hotspot_end_time
        ? (timing.hotspot_end_time instanceof Date
          ? `${String(timing.hotspot_end_time.getUTCHours()).padStart(2, '0')}:${String(timing.hotspot_end_time.getUTCMinutes()).padStart(2, '0')}:${String(timing.hotspot_end_time.getUTCSeconds()).padStart(2, '0')}`
          : String(timing.hotspot_end_time))
        : '23:59:59';
      
      const visitStartSeconds = timeToSeconds(visitStartTime);
      const visitEndSeconds = timeToSeconds(visitEndTime);
      const opStartSeconds = timeToSeconds(operatingStart);
      const opEndSeconds = timeToSeconds(operatingEnd);
      
      // PHP Logic: BOTH start and end must fall within the SAME operating window
      if (visitStartSeconds >= opStartSeconds && visitEndSeconds <= opEndSeconds) {
        return { canVisitNow: true, nextWindowStart: null };
      }
      
      // Track next available window that's after current time
      if (opStartSeconds > currentSeconds) {
        if (nextWindowStart === null || opStartSeconds < timeToSeconds(nextWindowStart)) {
          nextWindowStart = operatingStart;
        }
      }
    }
    
    // No timing window accommodates the current visit, but return next window if available
    return { canVisitNow: false, nextWindowStart };
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

    // SCENARIO 2: Check if arrival city == departure city
    // If yes AND departure time > 4 PM, skip Day 1 local sightseeing and do it on last day
    const arrivalPoint = String(plan.arrival_location ?? '').trim();
    const departurePoint = String(plan.departure_location ?? '').trim();
    
    const isSameArrivalDepartureCity = 
      this.normalizeCityName(arrivalPoint) === this.normalizeCityName(departurePoint);
    
    // Check departure time (extract hour from trip_end_date_and_time)
    let departureTimeAfter4PM = false;
    if (plan.trip_end_date_and_time && plan.trip_end_date_and_time instanceof Date) {
      const departureHour = plan.trip_end_date_and_time.getUTCHours();
      departureTimeAfter4PM = departureHour >= 16; // 4 PM or later
    }
    
    const shouldDeferDay1Sightseeing = isSameArrivalDepartureCity && departureTimeAfter4PM;

    const hotspotRows: HotspotDetailRow[] = [];
    const parkingRows: ParkingChargeRow[] = [];

    // Track hotspots already added to THIS plan during rebuild to avoid duplicates
    const addedHotspotIds = new Set<number>();

    // TODO (later): pass real user id from controller/service.
    const createdByUserId = 1;

    // Track first route for special Day 1 handling
    let routeIndex = 0;

    for (const route of routes) {
      const isFirstRoute = routeIndex === 0;
      routeIndex++;
      
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
        
      let routeEndTime: string = typeof route.route_end_time === 'string'
        ? route.route_end_time
        : route.route_end_time && typeof route.route_end_time === 'object'
        ? `${String((route.route_end_time as any).getUTCHours()).padStart(2, '0')}:${String((route.route_end_time as any).getUTCMinutes()).padStart(2, '0')}:${String((route.route_end_time as any).getUTCSeconds()).padStart(2, '0')}`
        : '18:00:00';
      
      // CRITICAL FIX: Day 1 must allow hotel check-in by 10 PM (22:00)
      // To guarantee this with ~30 min hotel travel + buffer, cap activities to 21:00 (9 PM) on Day 1
      // This ensures: Last activity ends ~21:00 → Hotel travel 21:00-21:30 → Check-in by ~21:30 (9:30 PM) ✅ BEFORE 10 PM
      if (isFirstRoute) {
        const routeEndSeconds = timeToSeconds(routeEndTime);
        const cutoffSeconds = timeToSeconds('21:00:00'); // 9 PM
        if (routeEndSeconds > cutoffSeconds) {
          routeEndTime = '21:00:00'; // Cap to 9 PM for Day 1
        }
      }
      
      let currentTime = routeStartTime;
      let routeEndSeconds = timeToSeconds(routeEndTime);
      
      // Handle overnight routes: if end time < start time, add 24 hours to end
      const routeStartSeconds = timeToSeconds(routeStartTime);
      if (routeEndSeconds < routeStartSeconds) {
        routeEndSeconds += 86400; // Add 24 hours in seconds
      }
      
      // DAY 1 SPECIAL: Override end time to 8 PM (20:00) for proper structure
      if (isFirstRoute && !isLastRoute) {
        routeEndSeconds = timeToSeconds('20:00:00');
        if (routeEndSeconds < routeStartSeconds) {
          routeEndSeconds += 86400;
        }
      }

      // Maintain current logical location name for distance calculations.
      // Start with the route's location_name (same as PHP "route start city").
      // Parse pipe-separated location to get first/main location only
      const rawStartLocation = (route.location_name as string) ||
        (route.next_visiting_location as string) ||
        (plan.departure_location as string) ||
        "";
      let currentLocationName: string = rawStartLocation.split('|')[0].trim();
      
      // Get starting coordinates from stored_locations using location_id (PHP: getITINEARYROUTE_DETAILS + getSTOREDLOCATIONDETAILS)
      // PHP line 1108-1109: $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
      let currentCoords: { lat: number; lon: number } | undefined = undefined;
      
      // ✅ RULE 1: ENFORCE 22:00 CUTOFF (destination arrival deadline)
      // Calculate: latestAllowedHotspotEnd = 22:00 - (travel to destination + buffer)
      // This ensures user reaches destination city by 22:00 for hotel check-in
      let latestAllowedHotspotEndSeconds = 0; // Default: no cutoff (will be calculated for non-last routes)
      
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
          
          // Update current time after refreshment
          currentTime = refreshmentEndTime;
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
      }

      // 2) CALCULATE LATESTNONHOTELEND CUTOFF FOR NON-LAST ROUTES
      // BUSINESS RULE: User must reach destination city by 22:00
      // latestNonHotelEnd = 22:00 - (travel_time + buffer_time to hotel)
      let latestNonHotelEndSeconds = routeEndSeconds; // Default: no cutoff
      let latestNonHotelEndTime = routeEndTime; // Default string representation
      
      if (!isLastRoute) {
        const hotelCutoffSeconds = timeToSeconds("22:00:00"); // 10 PM
        
        // Compute source/destination cities using stored_locations
        let sourceCity = "";
        let destinationCity = "";
        
        if (route.location_id) {
          const storedLoc = await (tx as any).dvi_stored_locations?.findFirst({
            where: {
              location_ID: BigInt(route.location_id),
              deleted: 0,
              status: 1,
            },
          });
          
          if (storedLoc) {
            sourceCity = storedLoc.source_location || "";
            destinationCity = storedLoc.destination_location || "";
          }
        }
        
        // Fallback to route fields if not found
        if (!sourceCity) sourceCity = ((route.location_name as string) || "").split('|')[0].trim();
        if (!destinationCity) destinationCity = ((route.next_visiting_location as string) || "").split('|')[0].trim();
        
        // Get travel time and buffer for hotel travel (outstation type=2)
        const hotelTravelResult = await this.distanceHelper.fromSourceAndDestination(
          tx,
          destinationCity,
          destinationCity, // Hotel is in destination city
          2, // type=2 (outstation travel to hotel)
        );
        
        const travelPlusBufferSeconds = 
          timeToSeconds(hotelTravelResult.travelTime) + 
          timeToSeconds(hotelTravelResult.bufferTime);
        
        latestNonHotelEndSeconds = hotelCutoffSeconds - travelPlusBufferSeconds;
        
        // Convert to time string for logging
        if (latestNonHotelEndSeconds > 0) {
          const hours = Math.floor(latestNonHotelEndSeconds / 3600);
          const minutes = Math.floor((latestNonHotelEndSeconds % 3600) / 60);
          const seconds = latestNonHotelEndSeconds % 60;
          latestNonHotelEndTime = `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
        } else {
          latestNonHotelEndSeconds = 0;
          latestNonHotelEndTime = "00:00:00";
        }
      }

      // 2) SELECTED HOTSPOTS FOR THIS ROUTE
      // DAY-1 DIFFERENT CITIES: If Day 1 and source city != destination city, enforce max 3 priority hotspots
      let selectedHotspots: SelectedHotspot[] = [];
      
      // Compute source/destination cities using stored_locations
      let sourceCity = "";
      let destinationCity = "";
      
      if (route.location_id) {
        const storedLoc = await (tx as any).dvi_stored_locations?.findFirst({
          where: {
            location_ID: BigInt(route.location_id),
            deleted: 0,
            status: 1,
          },
        });
        
        if (storedLoc) {
          sourceCity = storedLoc.source_location || "";
          destinationCity = storedLoc.destination_location || "";
        }
      }
      
      // Fallback to route fields if not found
      if (!sourceCity) sourceCity = ((route.location_name as string) || "").split('|')[0].trim();
      if (!destinationCity) destinationCity = ((route.next_visiting_location as string) || "").split('|')[0].trim();
      
      const normalizedSourceCity = this.normalizeCityName(sourceCity);
      const normalizedDestinationCity = this.normalizeCityName(destinationCity);
      const isDay1DifferentCities = isFirstRoute && normalizedSourceCity && normalizedDestinationCity && normalizedSourceCity !== normalizedDestinationCity;
      
      if (isDay1DifferentCities) {
        // Day-1 with different cities: fetch max 3 priority hotspots from source city only
        selectedHotspots = await this.fetchDay1TopPrioritySourceHotspots(
          tx,
          planId,
          route.itinerary_route_ID,
          sourceCity,
          destinationCity,
        );
      } else if (isFirstRoute && shouldDeferDay1Sightseeing) {
        // Check if current route is STAYING in arrival city (not just passing through)
        // Only skip if both location_name AND next_visiting_location are in arrival city
        const currentCity = this.normalizeCityName(currentLocationName);
        const nextCity = this.normalizeCityName(route.next_visiting_location || '');
        const arrivalCity = this.normalizeCityName(arrivalPoint);
        
        // CRITICAL FIX: Only skip if STAYING in arrival city, not just starting from there
        // Example: "Madurai Airport → Alleppey" should NOT skip (traveling away)
        // Example: "Madurai Airport → Madurai" SHOULD skip (staying in same city)
        const isStayingInArrivalCity = (currentCity === arrivalCity) && (nextCity === arrivalCity);
        
        if (isStayingInArrivalCity) {
          // Skip hotspot selection on Day 1 in arrival city
          // Travel directly to next destination
          try {
            fs.appendFileSync(
              'd:/wamp64\www\dvi_fullstack\dvi_backend\tmp\hotspot_selection.log',
              `SCENARIO 2: Day 1 staying in arrival city ${currentLocationName} → ${route.next_visiting_location} - skipping local sightseeing\n`
            );
          } catch (e) {}
          
          selectedHotspots = []; // Empty - no hotspots for Day 1 in arrival city
        } else {
          // Traveling away from arrival city on Day 1 - apply same direct/non-direct logic
          const directToNext = (route as any).direct_to_next_visiting_place || 0;
          
          if (directToNext === 1) {
            // Direct travel: Skip arrival city hotspots
            try {
              fs.appendFileSync(
                'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
                `SCENARIO 2 DAY 1 DIRECT: ${currentLocationName} → ${route.next_visiting_location} - going directly to destination\n`
              );
            } catch (e) {}
            
            selectedHotspots = await this.fetchSelectedHotspotsForRoute(
              tx,
              planId,
              route.itinerary_route_ID,
              undefined, // No source limit for direct travel
            );
          } else {
            // Non-direct travel: Visit all available arrival city hotspots
            try {
              fs.appendFileSync(
                'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
                `SCENARIO 2 DAY 1 NON-DIRECT: ${currentLocationName} → ${route.next_visiting_location} - scheduling all top priority hotspots\n`
              );
            } catch (e) {}
            
            selectedHotspots = await this.fetchSelectedHotspotsForRoute(
              tx,
              planId,
              route.itinerary_route_ID,
              undefined, // No limit - schedule all top priority hotspots
              true, // Skip destination hotspots - they'll be added on Day 2
            );
          }
        }
      } else if (isFirstRoute && !shouldDeferDay1Sightseeing) {
        // Day 1 traveling to different city - check direct flag
        const directToNext = (route as any).direct_to_next_visiting_place || 0;
        
        if (directToNext === 1) {
          // Direct travel: Skip arrival city hotspots, go straight to destination
          // Fetch destination city hotspots only (fetchSelectedHotspotsForRoute handles direct flag internally)
          try {
            fs.appendFileSync(
              'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
              `DAY 1 DIRECT: ${currentLocationName} → ${route.next_visiting_location} - going directly to destination\n`
            );
          } catch (e) {}
          
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
            undefined, // No source limit for direct travel (will skip source anyway)
          );
        } else {
          // Non-direct travel: Visit all available arrival city hotspots
          try {
            fs.appendFileSync(
              'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
              `DAY 1 NON-DIRECT: ${currentLocationName} → ${route.next_visiting_location} - scheduling all top priority hotspots\n`
            );
          } catch (e) {}
          
          // Fetch all available hotspots, skip destination (will be on Day 2)
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
            undefined, // No limit - schedule all top priority hotspots
            true, // Skip destination hotspots - they'll be added on Day 2
          );
          
          try {
            fs.appendFileSync(
              'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
              `DAY 1 NON-DIRECT: Selected ${selectedHotspots.length} total hotspots (priorities: ${selectedHotspots.map(h => h.hotspot_priority).join(', ')})\n`
            );
          } catch (e) {}
        }
      } else if (isLastRoute && shouldDeferDay1Sightseeing) {
        // Last day in departure city - fetch hotspots for departure city sightseeing
        const currentCity = this.normalizeCityName(currentLocationName);
        const departureCity = this.normalizeCityName(departurePoint);
        
        if (currentCity === departureCity) {
          // Do local sightseeing on last day
          try {
            fs.appendFileSync(
              'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
              `SCENARIO 2: Last day in departure city ${currentLocationName} - doing local sightseeing\n`
            );
          } catch (e) {}
          
          // Fetch hotspots for this city (will get popular spots)
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
          );
        } else {
          // Normal last route
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
          );
        }
      } else {
        // Normal route - fetch hotspots
        selectedHotspots = await this.fetchSelectedHotspotsForRoute(
          tx,
          planId,
          route.itinerary_route_ID,
        );
      }

      // NO LUNCH BREAKS OR TIME CUTOFFS - User can schedule all hotspots and delete unwanted ones from UI
      // Day 1: Schedule ALL top priority hotspots without time constraints
      // User can reach hotel at any time

      // STRATEGY: For Day-1 different cities, process hotspots with strict priority walk
      // For other days, use multi-pass scheduling to fill gaps with deferred hotspots
      
      if (isDay1DifferentCities) {
        // DAY-1 DIFFERENT CITIES: Strict priority walk with operating hour waiting
        // Process each hotspot in priority order, wait for next operating window if needed
        
        for (const sh of selectedHotspots) {
          // Skip if already added
          if (addedHotspotIds.has(sh.hotspot_ID)) {
            continue;
          }

          // Get hotspot details
          const hotspotData = await tx.dvi_hotspot_place.findUnique({
            where: { hotspot_ID: sh.hotspot_ID },
            select: {
              hotspot_location: true,
              hotspot_latitude: true,
              hotspot_longitude: true,
              hotspot_duration: true,
            },
          });

          if (!hotspotData) continue;

          const hotspotLocationName = hotspotData.hotspot_location as string || currentLocationName;
          const hotspotDuration = hotspotData.hotspot_duration || '01:00:00';
          const destCoords = {
            lat: Number(hotspotData.hotspot_latitude ?? 0),
            lon: Number(hotspotData.hotspot_longitude ?? 0),
          };
          
          if (!currentCoords) currentCoords = destCoords;

          // Calculate travel time
          const travelTimeToHotspot = await this.calculateTravelTimeWithCoords(
            tx,
            currentLocationName,
            hotspotLocationName,
            currentCoords,
            destCoords,
          );

          const travelDurationSeconds = timeToSeconds(travelTimeToHotspot);
          let timeAfterTravel = addSeconds(currentTime, travelDurationSeconds);
          
          const hotspotDurationSeconds = timeToSeconds(hotspotDuration);
          let timeAfterSightseeing = addSeconds(timeAfterTravel, hotspotDurationSeconds);

          // ✅ CHECK: Would this hotspot cause arrival after 10 PM?
          // Calculate travel time from THIS hotspot → destination
          const rawDestination = (route.next_visiting_location as string) || currentLocationName;
          const destinationCity = rawDestination.split('|')[0].trim();
          const parsedHotspotLocation = hotspotLocationName.split('|')[0].trim();
          
          const travelToDestResult = await this.distanceHelper.fromSourceAndDestination(
            tx,
            parsedHotspotLocation,
            destinationCity,
            2, // outstation
          );
          
          const travelToDestSeconds = 
            timeToSeconds(travelToDestResult.travelTime) +
            timeToSeconds(travelToDestResult.bufferTime);
          
          const hotelCutoffSeconds = timeToSeconds("22:00:00");
          const sightseeingEndSeconds = timeToSeconds(timeAfterSightseeing);
          const projectedArrivalSeconds = sightseeingEndSeconds + travelToDestSeconds;
          
          if (projectedArrivalSeconds > hotelCutoffSeconds) {
            try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] DAY1 SKIPPED - would cause arrival after 10 PM (projected: ${secondsToTime(projectedArrivalSeconds)})\n`); } catch(e) {}
            continue; // Skip this hotspot
          }

          // Get day of week for operating hours check
          const jsDay = route.itinerary_route_date ? new Date(route.itinerary_route_date).getDay() : 0;
          const dayOfWeek = (jsDay + 6) % 7;

          // Check operating hours
          let operatingCheck = await this.checkHotspotOperatingHours(
            tx,
            sh.hotspot_ID,
            dayOfWeek,
            timeAfterTravel,
            timeAfterSightseeing,
          );

          if (!operatingCheck.canVisitNow && operatingCheck.nextWindowStart) {
            // Hotspot opens later - wait until next window
            
            // Advance time to next window start
            timeAfterTravel = operatingCheck.nextWindowStart;
            timeAfterSightseeing = addSeconds(timeAfterTravel, hotspotDurationSeconds);
            
            // Re-check if it fits in the next window
            operatingCheck = await this.checkHotspotOperatingHours(
              tx,
              sh.hotspot_ID,
              dayOfWeek,
              timeAfterTravel,
              timeAfterSightseeing,
            );
            
            if (!operatingCheck.canVisitNow) {
              continue; // Skip this hotspot
            }
          } else if (!operatingCheck.canVisitNow) {
            // No operating hours available - skip
            continue;
          }

          // Add travel segment
          const currentOrder = order;
          const travelLocationType = this.getTravelLocationType(currentLocationName, hotspotLocationName);
          
          const { row: travelRow } = await this.travelBuilder.buildTravelSegment(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: currentOrder,
            item_type: 3,
            travelLocationType,
            startTime: currentTime,
            userId: createdByUserId,
            sourceLocationName: currentLocationName,
            destinationLocationName: hotspotLocationName,
            hotspotId: sh.hotspot_ID,
            sourceCoords: currentCoords,
            destCoords: destCoords,
          });

          hotspotRows.push(travelRow);
          currentTime = timeAfterTravel;
          currentLocationName = hotspotLocationName;
          currentCoords = destCoords;

          // Add hotspot segment
          const { row: hotspotRow } = await this.hotspotBuilder.build(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: currentOrder,
            hotspotId: sh.hotspot_ID,
            startTime: currentTime,
            userId: createdByUserId,
            totalAdult: plan.total_adult,
            totalChildren: plan.total_children,
            totalInfants: plan.total_infants,
            nationality: plan.nationality,
            itineraryPreference: plan.itinerary_preference,
          });

          hotspotRows.push(hotspotRow);
          addedHotspotIds.add(sh.hotspot_ID);
          order++;
          currentTime = timeAfterSightseeing;

          // Parking charges
          const parkingRowsForHotspot = await this.parkingBuilder.buildForHotspot(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            hotspotId: sh.hotspot_ID,
            userId: createdByUserId,
          });

          if (parkingRowsForHotspot && parkingRowsForHotspot.length > 0) {
            parkingRows.push(...parkingRowsForHotspot);
          }
        }

        // ✅ GAP-FILLING: Try to insert skipped hotspots into time gaps before first hotspot
        
        const skippedHotspots = selectedHotspots.filter(sh => !addedHotspotIds.has(sh.hotspot_ID));
        
        if (skippedHotspots.length > 0) {
          // Find first added hotspot
          const firstHotspotRow = hotspotRows.find(r => r.item_type === 4);
          
          if (firstHotspotRow) {
            const firstHotspotStartTime = TimeConverter.toTimeString(firstHotspotRow.hotspot_start_time);
            const firstHotspotStartSeconds = timeToSeconds(firstHotspotStartTime);
            
            // Find when route actually starts (after arrival)
            const arrivalRow = hotspotRows.find(r => r.item_type === 1);
            const routeStartTime = arrivalRow ? TimeConverter.toTimeString(arrivalRow.hotspot_end_time) : currentTime;
            const routeStartSeconds = timeToSeconds(routeStartTime);
            
            const gapBeforeFirst = firstHotspotStartSeconds - routeStartSeconds;
            
            try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  Gap before first hotspot: ${Math.floor(gapBeforeFirst/60)} minutes (${routeStartTime} to ${firstHotspotStartTime})\n`); } catch(e) {}
            
            // Try to fit skipped hotspots in this gap
            for (const sh of skippedHotspots) {
              const hotspotData = await tx.dvi_hotspot_place.findUnique({
                where: { hotspot_ID: sh.hotspot_ID },
                select: {
                  hotspot_location: true,
                  hotspot_latitude: true,
                  hotspot_longitude: true,
                  hotspot_duration: true,
                },
              });
              
              if (!hotspotData) continue;
              
              const hotspotLocationName = hotspotData.hotspot_location as string;
              const hotspotDuration = hotspotData.hotspot_duration || '01:00:00';
              const hotspotDurationSeconds = timeToSeconds(hotspotDuration);
              const destCoords = {
                lat: Number(hotspotData.hotspot_latitude ?? 0),
                lon: Number(hotspotData.hotspot_longitude ?? 0),
              };
              
              // Calculate travel from current location to this hotspot
              const travelToHotspot = await this.calculateTravelTimeWithCoords(
                tx,
                currentLocationName,
                hotspotLocationName,
                currentCoords,
                destCoords,
              );
              
              const travelSeconds = timeToSeconds(travelToHotspot);
              const totalNeeded = travelSeconds + hotspotDurationSeconds;
              
              // Check if it fits in the gap (leave buffer for travel to next hotspot)
              if (totalNeeded <= gapBeforeFirst - 1800) {
                // Check if adding it still allows reaching destination by 10 PM
                const visitEndTime = addSeconds(routeStartTime, totalNeeded);
                
                const parsedHotspotLocation = hotspotLocationName.split('|')[0].trim();
                const rawDestination = (route.next_visiting_location as string) || currentLocationName;
                const destinationCity = rawDestination.split('|')[0].trim();
                
                const travelToDestResult = await this.distanceHelper.fromSourceAndDestination(
                  tx,
                  parsedHotspotLocation,
                  destinationCity,
                  2,
                );
                
                const travelToDestSeconds = 
                  timeToSeconds(travelToDestResult.travelTime) +
                  timeToSeconds(travelToDestResult.bufferTime);
                
                const hotelCutoffSeconds = timeToSeconds("22:00:00");
                const projectedArrivalSeconds = timeToSeconds(visitEndTime) + travelToDestSeconds;
                
                if (projectedArrivalSeconds <= hotelCutoffSeconds) {
                  // It fits! Insert it before first hotspot
                  const insertOrder = firstHotspotRow.hotspot_order - 0.5;
                  
                  // Add travel segment
                  const travelLocationType = this.getTravelLocationType(currentLocationName, hotspotLocationName);
                  const { row: travelRow } = await this.travelBuilder.buildTravelSegment(tx, {
                    planId,
                    routeId: route.itinerary_route_ID,
                    order: insertOrder,
                    item_type: 3,
                    travelLocationType,
                    startTime: routeStartTime,
                    userId: createdByUserId,
                    sourceLocationName: currentLocationName,
                    destinationLocationName: hotspotLocationName,
                    hotspotId: sh.hotspot_ID,
                    sourceCoords: currentCoords,
                    destCoords: destCoords,
                  });
                  
                  hotspotRows.push(travelRow);
                  
                  // Add hotspot segment
                  const visitStartTime = addSeconds(routeStartTime, travelSeconds);
                  const { row: hotspotRow } = await this.hotspotBuilder.build(tx, {
                    planId,
                    routeId: route.itinerary_route_ID,
                    order: insertOrder,
                    hotspotId: sh.hotspot_ID,
                    startTime: visitStartTime,
                    userId: createdByUserId,
                    totalAdult: plan.total_adult,
                    totalChildren: plan.total_children,
                    totalInfants: plan.total_infants,
                    nationality: plan.nationality,
                    itineraryPreference: plan.itinerary_preference,
                  });
                  
                  hotspotRows.push(hotspotRow);
                  addedHotspotIds.add(sh.hotspot_ID);
                  
                  // Add parking
                  const parkingRowsForHotspot = await this.parkingBuilder.buildForHotspot(tx, {
                    planId,
                    routeId: route.itinerary_route_ID,
                    hotspotId: sh.hotspot_ID,
                    userId: createdByUserId,
                  });
                  
                  if (parkingRowsForHotspot && parkingRowsForHotspot.length > 0) {
                    parkingRows.push(...parkingRowsForHotspot);
                  }
                  
                  // ✅ Update the first hotspot's segments to start AFTER the gap-filled hotspot
                  const visitEndTime = addSeconds(visitStartTime, hotspotDurationSeconds);
                  
                  // Find travel and visit segments for first hotspot (same order)
                  const firstHotspotTravelRow = hotspotRows.find(r => 
                    r.item_type === 3 && r.hotspot_order === firstHotspotRow.hotspot_order
                  );
                  
                  if (firstHotspotTravelRow && firstHotspotRow) {
                    // Get first hotspot data for recalculating travel
                    const firstHotspotData = await tx.dvi_hotspot_place.findUnique({
                      where: { hotspot_ID: firstHotspotRow.hotspot_ID },
                      select: { hotspot_location: true, hotspot_latitude: true, hotspot_longitude: true },
                    });
                    
                    if (firstHotspotData) {
                      const firstHotspotLocation = firstHotspotData.hotspot_location as string;
                      const firstHotspotCoords = {
                        lat: Number(firstHotspotData.hotspot_latitude ?? 0),
                        lon: Number(firstHotspotData.hotspot_longitude ?? 0),
                      };
                      
                      // Calculate travel from gap-filled hotspot to first hotspot
                      const travelToFirst = await this.calculateTravelTimeWithCoords(
                        tx,
                        hotspotLocationName,
                        firstHotspotLocation,
                        destCoords,
                        firstHotspotCoords,
                      );
                      
                      const travelToFirstSeconds = timeToSeconds(travelToFirst);
                      const travelEndTime = addSeconds(visitEndTime, travelToFirstSeconds);
                      
                      // Update travel segment times
                      firstHotspotTravelRow.hotspot_start_time = TimeConverter.toDate(visitEndTime);
                      firstHotspotTravelRow.hotspot_end_time = TimeConverter.toDate(travelEndTime);
                      
                      // Update first hotspot visit start time
                      firstHotspotRow.hotspot_start_time = TimeConverter.toDate(travelEndTime);
                      // End time stays as it is (will be waiting time until opening)
                    }
                  }
                  
                  break; // Only insert one hotspot to avoid complexity
                } else {
                  try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] Cannot gap-fill: would cause arrival at ${secondsToTime(projectedArrivalSeconds)}\n`); } catch(e) {}
                }
              } else {
                try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] Cannot gap-fill: needs ${Math.floor(totalNeeded/60)} mins but gap is ${Math.floor(gapBeforeFirst/60)} mins\n`); } catch(e) {}
              }
            }
          }
        }
        
      } else {
        // OTHER DAYS: Multi-pass scheduling with deferred hotspots
        const maxPasses = 5; // Prevent infinite loops
        let pass = 1;
        let addedInLastPass = true;
        const deferredHotspots: Array<typeof selectedHotspots[0] & { deferredAtTime: string; opensAt: string }> = [];
        
        // ✅ CALCULATE INITIAL CUTOFF from starting location → destination
        // This ensures we don't add hotspots that would make 10 PM deadline impossible
        if (!isLastRoute) {
          const rawDestination = (route.next_visiting_location as string) || currentLocationName;
          const destinationCity = rawDestination.split('|')[0].trim();
          const hotelCutoffSeconds = timeToSeconds("22:00:00"); // 10 PM deadline
          
          const initialTravelResult = await this.distanceHelper.fromSourceAndDestination(
            tx,
            currentLocationName,
            destinationCity,
            2, // type=2: outstation
          );
          
          const initialTravelTimeSeconds =
            timeToSeconds(initialTravelResult.travelTime) +
            timeToSeconds(initialTravelResult.bufferTime);
          
          latestAllowedHotspotEndSeconds = Math.max(0, hotelCutoffSeconds - initialTravelTimeSeconds);
        }
        
        while (pass <= maxPasses && addedInLastPass) {
          addedInLastPass = false;
          const hotspotsToTry = pass === 1 ? selectedHotspots : deferredHotspots.filter(h => !addedHotspotIds.has(h.hotspot_ID));
          
          if (pass > 1) {
            deferredHotspots.length = 0; // Clear for next pass
            if (hotspotsToTry.length === 0) break; // No more to try
            try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `\n  === PASS ${pass}: Retrying ${hotspotsToTry.length} deferred hotspots (current time: ${currentTime}) ===\n`); } catch(e) {}
          }

        // Build travel + hotspot segments in order (NO LUNCH BREAKS OR CUTOFF CHECKS)
        for (const sh of hotspotsToTry) {
        
        // USER REQUIREMENT: Day 1 schedules ALL hotspots - no route time limit
        // Other days: stop if we have run out of route time
        if (!isFirstRoute) {
          let currentSeconds = timeToSeconds(currentTime);
          // Handle overnight: if current time < start time, add 24 hours
          if (currentSeconds < routeStartSeconds) {
            currentSeconds += 86400;
          }
          
          if (currentSeconds >= routeEndSeconds) {
            break;
          }
        }

        // DAY 1 TRAVEL CUTOFF: We'll check if this hotspot would finish after cutoff
        // AFTER calculating timeAfterSightseeing (lines ~835-851)
        // Don't break here - we need to try scheduling and see if it fits

        // PHP CHECK: Skip if hotspot already added to THIS PLAN (any previous route in this rebuild)
        // Line 15159 in sql_functions.php: check_hotspot_already_added_the_itineary_plan
        if (addedHotspotIds.has(sh.hotspot_ID)) {
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
          continue;
        }

        // Parse pipe-separated location to get first/main location only
        // hotspot_location format: "Madurai|Railway Station|Airport|..." 
        // We only want "Madurai" for distance calculation
        const rawLocation = hotspotData.hotspot_location as string || currentLocationName;
        const hotspotLocationName = rawLocation.split('|')[0].trim();
        const hotspotDuration = hotspotData.hotspot_duration || '01:00:00';
        const destCoords = {
          lat: Number(hotspotData.hotspot_latitude ?? 0),
          lon: Number(hotspotData.hotspot_longitude ?? 0),
        };
        
        // If this is the first hotspot and we don't have starting coords,
        // assume minimal travel time (starting near the first hotspot)
        if (!currentCoords) {
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

        // PHP PARITY: Check if SIGHTSEEING end time (item_type=3) exceeds route_end_time
        // PHP allows the BREAK (item_type=4) to exceed route_end_time, but the sightseeing must fit
        // Calculate end time AFTER travel only (before sightseeing duration)
        const travelDurationSeconds = timeToSeconds(travelTimeToHotspot);
        const timeAfterTravel = addSeconds(currentTime, travelDurationSeconds);
        
        // PHP checks if SIGHTSEEING END TIME fits within route_end_time
        // Sightseeing duration is just the hotspot visit time (not including break)
        const hotspotDurationSeconds = timeToSeconds(hotspotDuration);
        const timeAfterSightseeing = addSeconds(timeAfterTravel, hotspotDurationSeconds);
        
        let sightseeingEndSeconds = timeToSeconds(timeAfterSightseeing);
        if (sightseeingEndSeconds < routeStartSeconds) {
          sightseeingEndSeconds += 86400;
        }
        
        // ✅ RULE 1: Enforce 22:00 destination arrival cutoff
        // Skip hotspots that would prevent reaching destination by 22:00
        if (latestAllowedHotspotEndSeconds > 0 && sightseeingEndSeconds > latestAllowedHotspotEndSeconds) {
          continue; // Skip and try next hotspot
        }
        
        // PHP CHECK: If SIGHTSEEING would exceed route_end_time, STOP processing
        // NOTE: The break (item_type=4) is allowed to exceed route_end_time
        // SKIP THIS CHECK FOR DAY 1 - Day 1 has its own time management
        // USER REQUIREMENT: Keep user busy with all hotspots - no time constraints on Day 1
        if (!isFirstRoute && sightseeingEndSeconds > routeEndSeconds) {
          try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] SKIPPED - sightseeing exceeds route end time (${timeAfterSightseeing} > ${routeEndTime})\n`); } catch(e) {}
          continue; // PHP uses CONTINUE - skip this hotspot and try next ones
        }
        
        // PHP CHECK: If SIGHTSEEING would exceed route_end_time, STOP processing
        // NOTE: The break (item_type=4) is allowed to exceed route_end_time
        // SKIP THIS CHECK FOR DAY 1 - Day 1 has its own time management
        // USER REQUIREMENT: Keep user busy with all hotspots - no time constraints on Day 1
        if (!isFirstRoute && sightseeingEndSeconds > routeEndSeconds) {
          try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] SKIPPED - sightseeing exceeds route end time (${timeAfterSightseeing} > ${routeEndTime})\n`); } catch(e) {}
          continue; // PHP uses CONTINUE - skip this hotspot and try next ones
        }

        // PHP CHECK: Validate operating hours
        // PHP uses date('N')-1 where date('N') gives 1=Monday, 2=Tuesday, ..., 7=Sunday
        // So date('N')-1 gives: 0=Monday, 1=Tuesday, ..., 6=Sunday
        // JavaScript getDay() gives: 0=Sunday, 1=Monday, ..., 6=Saturday
        // Convert JS to PHP: (jsDay + 6) % 7
        const jsDay = route.itinerary_route_date
          ? new Date(route.itinerary_route_date).getDay()
          : 0;
        const dayOfWeek = (jsDay + 6) % 7; // Convert to PHP convention (0=Monday)
        
        const operatingHoursCheck = await this.checkHotspotOperatingHours(
          tx,
          sh.hotspot_ID,
          dayOfWeek,
          timeAfterTravel, // Visit starts after travel
          timeAfterSightseeing, // Visit ends after sightseeing duration
        );
        
        // USER REQUIREMENT: Schedule all hotspots to keep user busy
        // BALANCED APPROACH: Respect operating hours when available, but be flexible
        // - If hotspot can be visited now → schedule it
        // - If hotspot opens later today → defer and try next hotspot (fill gaps)
        // - If hotspot is closed OR has no operating hours → skip it (unrealistic to schedule)
        
        if (!operatingHoursCheck.canVisitNow) {
          if (operatingHoursCheck.nextWindowStart) {
            // Hotspot opens later today - defer for now, try other hotspots first
            if (pass === 1) {
              // First pass: defer and track for later retry
              deferredHotspots.push({
                ...sh,
                deferredAtTime: currentTime,
                opensAt: operatingHoursCheck.nextWindowStart
              });
            }
            if (isFirstRoute) {
              try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] DEFERRED DAY 1 (opens at ${operatingHoursCheck.nextWindowStart}) - trying other hotspots first\n`); } catch(e) {}
            } else {
              try { fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `  [${sh.hotspot_ID}] DEFERRED (opens at ${operatingHoursCheck.nextWindowStart}) - trying next hotspot first\n`); } catch(e) {}
            }
            continue; // Try next hotspot to fill the time
          } else {
            // No operating hours available - skip this hotspot (closed or no data)
            continue;
          }
        }
        addedInLastPass = true; // Mark that we added something in this pass
        // 2.c) Build TRAVEL SEGMENT (item_type = 3)
        // PHP BEHAVIOR: Travel and Visit segments share the SAME hotspot_order
        const currentOrder = order;
        
        const travelLocationType = this.getTravelLocationType(
          currentLocationName,
          hotspotLocationName,
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

        hotspotRows.push(hotspotRow);
        
        // Mark this hotspot as added to prevent duplicates in subsequent routes
        addedHotspotIds.add(sh.hotspot_ID);
        
        // NOW increment order after both travel and visit are added
        order++;
        
        currentTime = tAfterHotspot;
        // currentLocationName remains at the hotspot.

        // ✅ RECALCULATE CUTOFF: User is now at the hotspot, not starting location
        // Travel time from HERE (last hotspot) → destination is what matters for 22:00 deadline
        if (!isLastRoute) {
          const rawDestination = (route.next_visiting_location as string) || currentLocationName;
          const destinationCity = rawDestination.split('|')[0].trim();
          const hotelCutoffSeconds = timeToSeconds("22:00:00"); // 10 PM deadline
          
          // Get NEW travel time from CURRENT LOCATION (last hotspot) → destination
          const finalTravelResult = await this.distanceHelper.fromSourceAndDestination(
            tx,
            currentLocationName, // NOW at hotspot location
            destinationCity,
            2, // type=2: outstation
          );
          
          const finalTravelTimeSeconds =
            timeToSeconds(finalTravelResult.travelTime) +
            timeToSeconds(finalTravelResult.bufferTime);
          
          const newCutoff = Math.max(0, hotelCutoffSeconds - finalTravelTimeSeconds);
          
          // ⚠️ If travel time from here is too long, we need to SKIP remaining hotspots
          if (newCutoff <= timeToSeconds(currentTime)) {
            // Stop adding more hotspots - travel time is already too long
            break;
          } else {
            latestAllowedHotspotEndSeconds = newCutoff;
          }
        }

        // NO LUNCH BREAK LOGIC - removed per user request

        // 2.d) PARKING CHARGE ROWS for this hotspot (one per vendor vehicle)
        const parkingRowsForHotspot = await this.parkingBuilder.buildForHotspot(tx, {
          planId,
          routeId: route.itinerary_route_ID,
          hotspotId: sh.hotspot_ID,
          userId: createdByUserId,
        });

        if (parkingRowsForHotspot && parkingRowsForHotspot.length > 0) {
          parkingRows.push(...parkingRowsForHotspot);
        }
      }
      
      // End of hotspot scheduling loop
      pass++;
      } // End of while loop for multi-pass scheduling
      } // End of else (OTHER DAYS)

      // ✅ RULE 2 + 3: TRAVEL TO HOTEL & FIX TIME BUG (item_type = 5)
      // BUSINESS RULE: Hotel check-in must be before 10 PM (22:00)
      // Last route SKIPS hotel rows
      if (!isLastRoute) {
        const hotelOrder = order;

        const hotelInfo = await this.getHotelDetailsForRoute(
          tx,
          planId,
          route.itinerary_route_ID,
        );

        // ✅ ALWAYS use DESTINATION CITY for distance calculation (not hotel coordinates)
        // This ensures consistent distance regardless of hotel selection
        // Parse pipe-separated location to get first/main location only
        const rawDestinationCity = (route.next_visiting_location as string) || currentLocationName;
        const destinationCity = rawDestinationCity.split('|')[0].trim();
        
        // ✅ RULE 2: Always show final travel segment to destination (outstation type=2)
        // Start time is right after last hotspot ends
        const hotelStartTime = currentTime;

        // Distance calculation MUST use destination city, NOT hotel coordinates
        // This ensures fixed distance calculations across plan rebuilds
        // Parse source location to remove pipe-separated alternatives
        const sourceCity = currentLocationName.split('|')[0].trim();
        
        const { row: toHotelRow, nextTime: tAfterHotel } =
          await this.hotelBuilder.buildToHotel(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: hotelOrder,
            startTime: hotelStartTime,
            travelLocationType: 2, // Outstation to destination city
            userId: createdByUserId,
            sourceLocationName: sourceCity,
            destinationLocationName: destinationCity,
            sourceCoords: currentCoords,
            // ❌ DO NOT pass destCoords - forces DB lookup by city name for consistent distance
          });

        // ✅ RULE 3: Fix "06:58 AM" time bug using proper UTC date conversion
        // Never mix local timezone Date objects with UTC TIME fields
        let adjustedHotelRow = { ...toHotelRow };
        const hotelCutoffSeconds = timeToSeconds("22:00:00");
        const hotelEndSeconds = timeToSeconds(tAfterHotel);
        
        // Enforce 22:00 hard cap
        if (hotelEndSeconds > hotelCutoffSeconds) {
          // Use TimeConverter.toDate() for consistent UTC handling
          adjustedHotelRow.hotspot_end_time = TimeConverter.toDate("22:00:00");
        } else {
          adjustedHotelRow.hotspot_end_time = TimeConverter.toDate(tAfterHotel);
        }

        // Ensure start time uses proper UTC conversion
        adjustedHotelRow.hotspot_start_time = TimeConverter.toDate(hotelStartTime);

        hotspotRows.push(adjustedHotelRow);
        currentTime = Math.min(hotelEndSeconds, hotelCutoffSeconds) > 0
          ? secondsToTime(Math.min(hotelEndSeconds, hotelCutoffSeconds))
          : tAfterHotel;
        currentLocationName = "Hotel";
        if (hotelInfo?.coords) {
          currentCoords = hotelInfo.coords;
        }

        // 4) RETURN / CLOSING ROW FOR HOTEL (item_type = 6)
        const { row: closeHotelRow, nextTime: tClose } =
          await this.hotelBuilder.buildReturnToHotel(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order: hotelOrder,
            startTime: currentTime,
            userId: createdByUserId,
          });

        hotspotRows.push(closeHotelRow);
        order++;
        currentTime = tClose;
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
   * Day-1 special: Fetch top 3 priority hotspots from source city only.
   * Enforces:
   * - hotspot_priority > 0 (priority hotspots only)
   * - normalized location matches source city
   * - sorted by priority asc, then distance asc
   * - limited to 3 hotspots
   */
  private async fetchDay1TopPrioritySourceHotspots(
    tx: Tx,
    planId: number,
    routeId: number,
    sourceCity: string,
    destinationCity: string,
  ): Promise<SelectedHotspot[]> {
    try {
      const route = (await (tx as any).dvi_itinerary_route_details?.findFirst({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          deleted: 0,
          status: 1,
        },
      })) as RouteRow | null;

      if (!route) return [];

      // Get route date for operating hours check
      const routeDate = route.itinerary_route_date ? new Date(route.itinerary_route_date) : null;
      const phpDow = routeDate ? ((routeDate.getDay() + 6) % 7) : undefined;

      // Get starting location coordinates
      let startLat = 0;
      let startLon = 0;
      
      if (route.location_id) {
        const storedLoc = await (tx as any).dvi_stored_locations?.findFirst({
          where: { location_ID: BigInt(route.location_id), deleted: 0, status: 1 },
        });
        
        if (storedLoc) {
          startLat = Number(storedLoc.source_location_lattitude ?? 0);
          startLon = Number(storedLoc.source_location_longitude ?? 0);
        }
      }

      // Fetch all active hotspots
      const allHotspots = (await (tx as any).dvi_hotspot_place?.findMany({
        where: { deleted: 0, status: 1, hotspot_priority: { gt: 0 } }, // Priority > 0 only
      })) || [];

      // Filter to source city only and calculate distances
      const normalizedSourceCity = this.normalizeCityName(sourceCity);
      const sourceHotspots: any[] = [];

      for (const h of allHotspots) {
        // Normalize hotspot location and check if it matches source city
        const hotspotParts = String(h.hotspot_location || "")
          .split("|")
          .map(p => this.normalizeCityName(p));
        
        if (!hotspotParts.includes(normalizedSourceCity)) {
          continue; // Skip if not in source city
        }

        // Calculate distance from starting location
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

        sourceHotspots.push({ ...h, hotspot_distance: distance });
      }

      // Sort by priority asc, then distance asc
      sourceHotspots.sort((a: any, b: any) => {
        const aPriority = Number(a.hotspot_priority ?? 0);
        const bPriority = Number(b.hotspot_priority ?? 0);
        
        if (aPriority !== bPriority) {
          return aPriority - bPriority; // Lower priority first
        }
        return a.hotspot_distance - b.hotspot_distance; // Closer first
      });

      // Take top 3
      const topThree = sourceHotspots.slice(0, 3);

      try {
        fs.appendFileSync(
          'd:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
          `[fetchDay1TopPrioritySourceHotspots] Route ${routeId}: source="${sourceCity}" → Selected ${topThree.length} hotspots: ${topThree.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`).join(', ')}\\n`
        );
      } catch (e) {}

      return topThree.map((h: any) => ({
        hotspot_ID: Number(h.hotspot_ID ?? 0),
        display_order: Number(h.hotspot_priority ?? 0),
        hotspot_priority: Number(h.hotspot_priority ?? 0),
      }));
    } catch (err) {
      console.error("[fetchDay1TopPrioritySourceHotspots] Error:", err);
      return [];
    }
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
   * 
   * @param maxSourceHotspots - Optional limit for source location hotspots (for Day 1 arrival city)
   */
  private async fetchSelectedHotspotsForRoute(
    tx: Tx,
    planId: number,
    routeId: number,
    maxSourceHotspots?: number,
    skipDestinationHotspots?: boolean,
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
        return [];
      }

      // PHP LINE 1023-1033: Get location names from stored_locations table, NOT from route fields
      // $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');
      // $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');
      let targetLocation = "";
      let nextLocation = "";
      
      if (route.location_id) {
        const storedLoc = await (tx as any).dvi_stored_locations?.findFirst({
          where: {
            location_ID: BigInt(route.location_id),
            deleted: 0,
            status: 1,
          },
        });
        
        if (storedLoc) {
          targetLocation = storedLoc.source_location || "";
          nextLocation = storedLoc.destination_location || "";
        }
      }
      
      // Fallback: If location_id is missing/0, try to find by route location names
      if (!targetLocation && !nextLocation) {
        // Try exact match first
        if (route.location_name) {
          const foundBySource = await (tx as any).dvi_stored_locations?.findFirst({
            where: {
              source_location: route.location_name,
              deleted: 0,
              status: 1,
            },
          });
          
          if (foundBySource) {
            targetLocation = foundBySource.source_location || "";
            nextLocation = foundBySource.destination_location || "";
          }
        }
        
        // Fuzzy match if exact didn't work
        if (!targetLocation && !nextLocation && route.location_name) {
          const foundFuzzy = await (tx as any).dvi_stored_locations?.findFirst({
            where: {
              OR: [
                { source_location: { contains: route.location_name } },
                { destination_location: { contains: route.location_name } },
              ],
              deleted: 0,
              status: 1,
            },
          });
          
          if (foundFuzzy) {
            targetLocation = foundFuzzy.source_location || "";
            nextLocation = foundFuzzy.destination_location || "";
          }
        }
        
        // If still not found, try next_visiting_location
        if (!targetLocation && !nextLocation && route.next_visiting_location) {
          // Try exact match
          const foundByNext = await (tx as any).dvi_stored_locations?.findFirst({
            where: {
              OR: [
                { source_location: route.next_visiting_location },
                { destination_location: route.next_visiting_location },
              ],
              deleted: 0,
              status: 1,
            },
          });
          
          if (foundByNext) {
            targetLocation = foundByNext.source_location || "";
            nextLocation = foundByNext.destination_location || "";
          }
          
          // Fuzzy match as last resort
          if (!targetLocation && !nextLocation) {
            const foundFuzzyNext = await (tx as any).dvi_stored_locations?.findFirst({
              where: {
                OR: [
                  { source_location: { contains: route.next_visiting_location } },
                  { destination_location: { contains: route.next_visiting_location } },
                ],
                deleted: 0,
                status: 1,
              },
            });
            
            if (foundFuzzyNext) {
              targetLocation = foundFuzzyNext.source_location || "";
              nextLocation = foundFuzzyNext.destination_location || "";
            }
          }
        }
      }

      if (!targetLocation && !nextLocation) {
        return [];
      }

      // PHP uses day-of-week filtering via dvi_hotspot_timing (date('N')-1 => Monday=0)
      const routeDate = route.itinerary_route_date
        ? new Date(route.itinerary_route_date)
        : null;
      const phpDow = routeDate
        ? ((routeDate.getDay() + 6) % 7) // JS: Sunday=0; PHP: Monday=0, Sunday=6
        : undefined;

      // 2) Preload hotspot timings for this day (if available)
      // PHP uses LEFT JOIN without filtering hotspot_closed - includes all hotspots with timing records
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

      // 3b) Fetch operating hours for all hotspots to enable time-aware sorting
      // PHP behavior: sortHotspots() re-orders to prioritize time-critical hotspots
      // Include all timing records (even closed) - checkHotspotOperatingHours will filter later
      const hotspotTimings = phpDow !== undefined
        ? await (tx as any).dvi_hotspot_timing?.findMany({
            where: {
              hotspot_timing_day: phpDow,
              deleted: 0,
              status: 1,
            },
          }) || []
        : [];

      // Map hotspot_ID -> earliest closing time for quick lookup
      const closingTimeMap = new Map<number, string>();
      for (const timing of hotspotTimings) {
        const hotspotId = Number(timing.hotspot_ID ?? 0);
        const endTime = timing.hotspot_end_time || '23:59:59';
        
        // Keep earliest closing time if multiple slots exist
        if (!closingTimeMap.has(hotspotId) || endTime < closingTimeMap.get(hotspotId)!) {
          closingTimeMap.set(hotspotId, endTime);
        }
      }

      const targetLower = targetLocation.toLowerCase();
      const nextLower = nextLocation.toLowerCase();
      const directToNextVisitingPlace = (route as any).direct_to_next_visiting_place || 0;

      // Get starting location coordinates from stored_locations (already fetched above)
      // PHP line 1108-1109: Uses source coordinates for starting point
      let startLat = 0;
      let startLon = 0;
      
      if (route.location_id) {
        const storedLoc = await (tx as any).dvi_stored_locations?.findFirst({
          where: {
            location_ID: BigInt(route.location_id),
            deleted: 0,
            status: 1,
          },
        });
        
        if (storedLoc) {
          // Use source coordinates (PHP uses source for starting point)
          startLat = Number(storedLoc.source_location_lattitude ?? 0);
          startLon = Number(storedLoc.source_location_longitude ?? 0);
        }
      }
      
      // Fallback: If location_id is missing/0 and no coordinates, try by location_name
      if (!startLat && !startLon && targetLocation) {
        // Try exact match first
        let foundLoc = await (tx as any).dvi_stored_locations?.findFirst({
          where: {
            source_location: targetLocation,
            deleted: 0,
            status: 1,
          },
        });
        
        // Fuzzy match if exact didn't work
        if (!foundLoc && route.location_name) {
          foundLoc = await (tx as any).dvi_stored_locations?.findFirst({
            where: {
              source_location: { contains: route.location_name },
              deleted: 0,
              status: 1,
            },
          });
        }
        
        if (foundLoc) {
          startLat = Number(foundLoc.source_location_lattitude ?? 0);
          startLon = Number(foundLoc.source_location_longitude ?? 0);
        }
      }

      // PHP LINE 1003-1011: Filter includes source location when direct_to_next_visiting_place != 1
      // Categorize hotspots like PHP does (lines 1197-1210)
      let sourceLocationHotspots: any[] = [];
      const destinationHotspots: any[] = [];
      const viaRouteHotspots: any[] = [];

      // Helper function to match location with normalization
      // Normalizes both sides to handle "Chennai International Airport" == "Chennai"
      const containsLocation = (hotspotLocation: string | null, targetLocation: string | null): boolean => {
        if (!hotspotLocation || !targetLocation) return false;
        
        // Split by pipe and normalize each part
        const hotspotParts = hotspotLocation.split('|').map(p => this.normalizeCityName(p));
        const normalizedTarget = this.normalizeCityName(targetLocation);
        
        const matches = hotspotParts.includes(normalizedTarget);
        
        // Debug logging
        if (routeId === 980 && matches) {
          try {
            fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
              `  [containsLocation] Hotspot parts: ${JSON.stringify(hotspotParts)}\n` +
              `  [containsLocation] Target: "${normalizedTarget}" → MATCH\n`
            );
          } catch (e) {}
        }
        
        return matches;
      };

      for (const h of allHotspots) {
        // Check if timing allows this hotspot on this day
        if (allowedHotspotIds && !allowedHotspotIds.has(Number(h.hotspot_ID ?? 0))) {
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
        
        // PHP PARITY: Lines showing categorization:
        // if ($source_match) :
        //     $source_location_hotspots[] = $hotspot_details;
        // endif;
        // if ($destination_match) :
        //     $destination_hotspots[] = $hotspot_details;
        // endif;
        
        // CRITICAL: Hotspot can be in BOTH buckets (e.g., hotspot_location = "Chennai|Pondicherry")
        // Deduplication happens AFTER bucket selection based on direct flag
        if (matchesSource) {
          sourceLocationHotspots.push(hotspotWithDistance);
        }
        
        if (matchesDestination) {
          destinationHotspots.push(hotspotWithDistance);
        }
      }
      
      // Fetch via routes for this route and match hotspots
      const viaRoutes = await (tx as any).dvi_itinerary_via_route_details?.findMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          deleted: 0,
          status: 1,
        },
      }) || [];
      
      // For each via location, find matching hotspots
      for (const viaRoute of viaRoutes) {
        const viaLocationName = viaRoute.itinerary_via_location_name;
        if (!viaLocationName) continue;
        
        for (const h of allHotspots) {
          // Check if timing allows this hotspot on this day
          if (allowedHotspotIds && !allowedHotspotIds.has(Number(h.hotspot_ID ?? 0))) {
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
          
          // Check if hotspot matches via location
          const matchesVia = containsLocation(h.hotspot_location as string, viaLocationName);
          
          if (matchesVia) {
            viaRouteHotspots.push(hotspotWithDistance);
          }
        }
      }

      // PHP PARITY: Exact copy of sortHotspots function from ajax_latest_manage_itineary.php
      const sortHotspots = (hotspots: any[]) => {
        hotspots.sort((a: any, b: any) => {
          const aPriority = Number(a.hotspot_priority ?? 0);
          const bPriority = Number(b.hotspot_priority ?? 0);
          
          // Priority 0 goes to END
          if (aPriority === 0 && bPriority !== 0) {
            return 1;
          } else if (aPriority !== 0 && bPriority === 0) {
            return -1;
          } else if (aPriority === bPriority) {
            // Same priority: sort by distance ASC (closer first)
            return a.hotspot_distance - b.hotspot_distance;
          }
          // Different priorities: sort by priority ASC (lower number first)
          return aPriority - bPriority;
        });
      };

      // PHP BEHAVIOR: Sort individual location buckets, NOT the final combined list
      sortHotspots(sourceLocationHotspots);
      sortHotspots(destinationHotspots);
      sortHotspots(viaRouteHotspots);
      
      // Apply max source hotspots limit if specified (for Day 1 arrival city)
      if (maxSourceHotspots && maxSourceHotspots > 0 && sourceLocationHotspots.length > maxSourceHotspots) {
        // Limit to top priority hotspots only
        sourceLocationHotspots = sourceLocationHotspots.slice(0, maxSourceHotspots);
        try {
          fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
            `[Route ${routeId}] LIMITED source hotspots to ${maxSourceHotspots} (was ${sourceLocationHotspots.length + maxSourceHotspots})\n`
          );
        } catch (e) {}
      }
      
      // Debug logging to trace sorting behavior
      try {
        const fs = require('fs');
        fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
          `[Route ${routeId}] After sorting each bucket:\n` +
          `  Source (${targetLocation}): ${sourceLocationHotspots.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`).join(', ')}\n` +
          `  Destination (${nextLocation}): ${destinationHotspots.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`).join(', ')}\n` +
          `  Via: ${viaRouteHotspots.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`).join(', ')}\n` +
          `  Direct: ${directToNextVisitingPlace}\n`
        );
      } catch (e) {}
      
      // PHP does NOT filter priority=0, it just sorts them to the END
      // Time constraints and route_end_time will naturally prevent low-priority hotspots
      // from being added if there's not enough time

      // PHP PARITY: Process hotspots based on direct_to_next_visiting_place
      // Concatenate buckets in the order PHP processes them
      let matchingHotspots: any[] = [];
      
      if (directToNextVisitingPlace === 1) {
        // PHP ELSE BRANCH (direct == 1): Process via_route_hotspots, then destination_hotspots
        matchingHotspots = [...viaRouteHotspots, ...destinationHotspots];
      } else {
        // PHP ELSE BRANCH (direct == 0): Process source, via, then destination
        // Order: source_location_hotspots → via_route_hotspots → destination_hotspots
        
        // DAY 1 NON-DIRECT: Skip destination hotspots entirely
        // User requirement: "Day 1 should have max 3 Madurai hotspots, Day 2 will have Alleppey hotspots"
        if (skipDestinationHotspots) {
          matchingHotspots = [...sourceLocationHotspots, ...viaRouteHotspots];
          try {
            fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log',
              `[Route ${routeId}] DAY 1 NON-DIRECT: Skipping destination hotspots (will be added on Day 2)\n`
            );
          } catch (e) {}
        } else {
          matchingHotspots = [...sourceLocationHotspots, ...viaRouteHotspots, ...destinationHotspots];
        }
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
      
      // PHP PARITY: Do NOT re-sort after concatenation
      // The order from concatenation (source + destination + via) is the final order

      try {
        fs.appendFileSync('d:/wamp64/www/dvi_fullstack/dvi_backend/tmp/hotspot_selection.log', `[Route ${routeId}] Selected: ${uniqueHotspots.map(h => `${h.hotspot_ID}(p${h.hotspot_priority})`).join(', ')}\n`);
      } catch (e) {
        // Ignore file write errors
      }

      return uniqueHotspots.map((h: any, index: number) => ({
        hotspot_ID: Number(h.hotspot_ID ?? 0) || 0,
        display_order: Number(h.hotspot_priority ?? index + 1) || index + 1,
        hotspot_priority: Number(h.hotspot_priority ?? 0) || 0,
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

  private async getHotelDetailsForRoute(
    tx: Tx,
    planId: number,
    routeId: number,
  ): Promise<{ hotelId: number; hotelName: string | null; hotelCity: string | null; coords?: { lat: number; lon: number } } | null> {
    const details = await (tx as any).dvi_itinerary_plan_hotel_details?.findFirst({
      where: {
        itinerary_plan_id: planId,
        itinerary_route_id: routeId,
        group_type: 1,
        deleted: 0,
        status: 1,
      },
      select: {
        hotel_id: true,
      },
    });

    const hotelId = Number(details?.hotel_id ?? 0) || 0;
    if (!hotelId) return null;

    const hotel = await (tx as any).dvi_hotel?.findFirst({
      where: {
        hotel_id: hotelId,
      },
      select: {
        hotel_name: true,
        hotel_city: true,
        hotel_latitude: true,
        hotel_longitude: true,
      },
    });

    if (!hotel) {
      return { hotelId, hotelName: null, hotelCity: null };
    }

    // Try to get the location name if hotel_city is a location_id
    let hotelCity: string | null = null;
    const citySafe = Number(hotel.hotel_city) || 0;
    if (citySafe > 0) {
      // hotel_city is a location_id reference, look it up
      try {
        const location = await (tx as any).dvi_stored_locations?.findFirst({
          where: { location_id: citySafe },
          select: { location_name: true },
        });
        hotelCity = (location?.location_name as string) ?? null;
      } catch {
        hotelCity = null;
      }
    } else {
      // hotel_city is a direct string
      hotelCity = (hotel.hotel_city as string) ?? null;
    }

    const lat = Number(hotel.hotel_latitude);
    const lon = Number(hotel.hotel_longitude);
    const hasCoords = Number.isFinite(lat) && Number.isFinite(lon);

    return {
      hotelId,
      hotelName: (hotel.hotel_name as string) ?? null,
      hotelCity,
      coords: hasCoords ? { lat, lon } : undefined,
    };
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