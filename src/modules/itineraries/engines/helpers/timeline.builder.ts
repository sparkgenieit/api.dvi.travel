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
  hotspot_name?: string;
  display_order?: number;
  hotspot_priority?: number;
  city_order?: number; // 1: Source, 2: Via, 3: Destination
  isBoundaryMatch?: boolean;
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

  // ⚡ PERFORMANCE: Pre-fetched data maps
  private hotelDetailsMap = new Map<number, any>();
  private hotelDataMap = new Map<number, any>();
  private hotelCityMap = new Map<number, string>();
  private storedLocationMap = new Map<number, any>();
  private viaRouteMap = new Map<number, any[]>(); // ⚡ PERFORMANCE: Pre-fetched via routes map
  private cityIdMap = new Map<string, number>(); // ⚡ PERFORMANCE: Pre-fetched city ID map (normalized name -> city ID)

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
   * 
   * @param timingMap - Pre-fetched timing data map for all hotspots (performance optimization)
   */
  private checkHotspotOperatingHoursFromMap(
    timingMap: Map<number, Map<number, any[]>>,
    hotspotId: number,
    dayOfWeek: number,
    visitStartTime: string,
    visitEndTime: string,
  ): { canVisitNow: boolean; nextWindowStart: string | null; operatingHours?: string } {
    // Get timing records from pre-fetched map (NO DB QUERY)
    const timingRecords = timingMap.get(hotspotId)?.get(dayOfWeek) || [];

    if (!timingRecords || timingRecords.length === 0) {
      // No timing records = not open on this day
      return { canVisitNow: false, nextWindowStart: null, operatingHours: "Closed today" };
    }

    let nextWindowStart: string | null = null;
    let allWindows: string[] = [];
    const currentSeconds = timeToSeconds(visitStartTime);

    // Check if any timing window allows the full visit (start AND end within same window)
    for (const timing of timingRecords) {
      // Skip if hotspot is closed
      if (timing.hotspot_closed === 1) {
        continue;
      }
      
      // Open all time = always available
      if (timing.hotspot_open_all_time === 1) {
        return { canVisitNow: true, nextWindowStart: null, operatingHours: "Open 24 hours" };
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
      
      allWindows.push(`${operatingStart.substring(0, 5)} - ${operatingEnd.substring(0, 5)}`);

      const visitStartSeconds = timeToSeconds(visitStartTime);
      const visitEndSeconds = timeToSeconds(visitEndTime);
      const opStartSeconds = timeToSeconds(operatingStart);
      const opEndSeconds = timeToSeconds(operatingEnd);
      
      const BUFFER_SECONDS = 15 * 60; // 15 minutes buffer for early arrival

      // PHP Logic: BOTH start and end must fall within the SAME operating window
      // MODIFIED: Allow arrival up to 15 minutes before opening time (user can wait)
      if (visitStartSeconds >= (opStartSeconds - BUFFER_SECONDS) && visitEndSeconds <= opEndSeconds) {
        return { canVisitNow: true, nextWindowStart: null, operatingHours: allWindows.join(", ") };
      }
      
      // Track next available window that's after current time
      if (opStartSeconds > currentSeconds) {
        if (nextWindowStart === null || opStartSeconds < timeToSeconds(nextWindowStart)) {
          nextWindowStart = operatingStart;
        }
      }
    }
    
    // No timing window accommodates the current visit, but return next window if available
    return { canVisitNow: false, nextWindowStart, operatingHours: allWindows.join(", ") };
  }

  /**
   * Main orchestrator for one plan.
   * Returns in-memory arrays that hotspot-engine.service.ts will insert.
   */
  async buildTimelineForPlan(
    tx: Tx,
    planId: number,
    existingHotspots: any[] = [],
  ): Promise<{ 
    hotspotRows: HotspotDetailRow[]; 
    parkingRows: ParkingChargeRow[];
    shiftedItems?: any[];
    droppedItems?: any[];
  }> {
    const buildStart = Date.now();
    console.log('[TIMELINE] buildTimelineForPlan started for planId:', planId);

    // Track existing hotspots for change detection (shifted/dropped)
    const beforeHotspots = new Map<number, { routeId: number; startTime: string }>();
    for (const eh of existingHotspots) {
      if (eh.hotspot_ID && eh.hotspot_start_time) {
        beforeHotspots.set(Number(eh.hotspot_ID), {
          routeId: Number(eh.itinerary_route_ID),
          startTime: TimeConverter.toTimeString(eh.hotspot_start_time),
        });
      }
    }
    
    // ⚡ PERFORMANCE: Clear helper caches
    this.parkingBuilder.clearCache();
    
    let opStart = Date.now();
    const plan = (await (tx as any).dvi_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: planId, deleted: 0 },
    })) as PlanHeader | null;
    console.log('[TIMELINE] Fetch plan:', Date.now() - opStart, 'ms');

    if (!plan) {
      return { hotspotRows: [], parkingRows: [] };
    }

    // ⚡ PERFORMANCE: Pre-fetch global settings for DistanceHelper
    const gs = await (tx as any).dvi_global_settings.findFirst({
      where: { deleted: 0, status: 1 },
    });
    this.distanceHelper.setGlobalSettings(gs);

    opStart = Date.now();
    const routes = (await (tx as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0, status: 1 },
      orderBy: [
        { itinerary_route_date: "asc" },
        { itinerary_route_ID: "asc" },
      ],
    })) as RouteRow[];
    console.log('[TIMELINE] Fetch routes:', Date.now() - opStart, 'ms, count:', routes.length);

    if (!routes.length) {
      return { hotspotRows: [], parkingRows: [] };
    }

    // ⚡ PERFORMANCE: Identify last route ID once
    const lastRouteId = routes[routes.length - 1].itinerary_route_ID;

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

    // ⚡ PERFORMANCE OPTIMIZATION: Fetch all hotspots ONCE instead of once per route
    opStart = Date.now();
    const allHotspots = (await (tx as any).dvi_hotspot_place?.findMany({
      where: {
        deleted: 0,
        status: 1,
      },
    })) || [];
    console.log('[TIMELINE] Fetch ALL hotspots ONCE:', Date.now() - opStart, 'ms, count:', allHotspots.length);

    // ⚡ Create hotspot lookup map for O(1) access (avoid repeated DB queries)
    const hotspotMap = new Map();
    for (const h of allHotspots) {
      hotspotMap.set(h.hotspot_ID, {
        hotspot_name: h.hotspot_name,
        hotspot_location: h.hotspot_location,
        hotspot_latitude: h.hotspot_latitude,
        hotspot_longitude: h.hotspot_longitude,
        hotspot_duration: h.hotspot_duration,
      });
    }
    console.log('[TIMELINE] Created hotspot lookup map');

    // ⚡ PERFORMANCE: Pre-fetch all stored locations for these routes
    const locationIds = routes.map(r => r.location_id).filter((id): id is number => id !== null && id !== undefined);
    const storedLocations = await (tx as any).dvi_stored_locations.findMany({
      where: {
        location_ID: { in: locationIds.map(id => BigInt(id)) },
        deleted: 0,
        status: 1,
      }
    });
    this.storedLocationMap.clear();
    for (const sl of storedLocations) {
      this.storedLocationMap.set(Number(sl.location_ID), sl);
    }

    // ⚡ PERFORMANCE: Pre-fetch all city-to-city distances for the itinerary
    const cities = new Set<string>();
    routes.forEach(r => {
      if (r.location_name) cities.add(r.location_name.split('|')[0].trim());
      if (r.next_visiting_location) cities.add(r.next_visiting_location.split('|')[0].trim());
    });
    if (plan.arrival_location) cities.add(plan.arrival_location.split('|')[0].trim());
    if (plan.departure_location) cities.add(plan.departure_location.split('|')[0].trim());

    const cityList = Array.from(cities).filter(c => !!c);
    if (cityList.length > 0) {
      const cityStoredLocations = await (tx as any).dvi_stored_locations.findMany({
        where: {
          OR: [
            { source_location: { in: cityList } },
            { destination_location: { in: cityList } }
          ],
          deleted: 0,
          status: 1,
        }
      });
      this.distanceHelper.prePopulateCache(cityStoredLocations);
    }

    // ⚡ PERFORMANCE: Pre-fetch all hotel details for the plan
    const hotelDetails = await (tx as any).dvi_itinerary_plan_hotel_details.findMany({
      where: { itinerary_plan_id: planId, deleted: 0, status: 1 },
    });
    this.hotelDetailsMap.clear();
    for (const hd of hotelDetails) {
      this.hotelDetailsMap.set(Number(hd.itinerary_route_id), hd);
    }

    // ⚡ PERFORMANCE: Pre-fetch all hotels in the plan
    const hotelIds = hotelDetails.map((hd: any) => Number(hd.hotel_id)).filter((id: number) => !!id);
    const hotels = await (tx as any).dvi_hotel.findMany({
      where: { hotel_id: { in: hotelIds } }
    });
    this.hotelDataMap.clear();
    for (const h of hotels) {
      this.hotelDataMap.set(Number(h.hotel_id), h);
    }

    // ⚡ PERFORMANCE: Pre-fetch all location names for hotel cities if they are IDs
    const hotelCityIds = hotels.map((h: any) => Number(h.hotel_city)).filter((id: number) => !!id && !isNaN(id));
    const hotelCityLocations = await (tx as any).dvi_stored_locations.findMany({
      where: { location_ID: { in: hotelCityIds.map((id: number) => BigInt(id)) } }
    });
    this.hotelCityMap.clear();
    for (const l of hotelCityLocations) {
      this.hotelCityMap.set(Number(l.location_ID), l.location_name);
    }

    // ⚡ PERFORMANCE: Pre-fetch all via routes for the plan
    const viaRoutesAll = await (tx as any).dvi_itinerary_via_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0, status: 1 },
    });
    this.viaRouteMap.clear();
    for (const vr of viaRoutesAll) {
      const routeId = Number(vr.itinerary_route_ID);
      if (!this.viaRouteMap.has(routeId)) {
        this.viaRouteMap.set(routeId, []);
      }
      this.viaRouteMap.get(routeId)!.push(vr);
    }

    // ⚡ PERFORMANCE: Pre-fetch all city IDs for boundary matching
    const allCities = await (tx as any).dvi_cities.findMany({
      where: { deleted: 0 },
      select: { id: true, name: true }
    });
    this.cityIdMap.clear();
    for (const city of allCities) {
      this.cityIdMap.set(this.normalizeCityName(city.name), city.id);
    }

    // ⚡ Batch-fetch ALL timing data for ALL days at once (avoid 42+ individual queries)
    opStart = Date.now();
    const allTimings = await (tx as any).dvi_hotspot_timing.findMany({
      where: {
        deleted: 0,
        status: 1,
      },
    });
    
    // Group timings by hotspot_ID and day for O(1) lookup
    const timingMap = new Map<number, Map<number, any[]>>();
    for (const timing of allTimings) {
      const hotspotId = Number(timing.hotspot_ID);
      const day = Number(timing.hotspot_timing_day);
      
      if (!timingMap.has(hotspotId)) {
        timingMap.set(hotspotId, new Map());
      }
      const dayMap = timingMap.get(hotspotId)!;
      if (!dayMap.has(day)) {
        dayMap.set(day, []);
      }
      dayMap.get(day)!.push(timing);
    }
    console.log('[TIMELINE] Batch-fetched ALL timing data:', Date.now() - opStart, 'ms, records:', allTimings.length);

    const hotspotRows: HotspotDetailRow[] = [];
    const parkingRows: ParkingChargeRow[] = [];

    // Track hotspots already added to THIS plan during rebuild to avoid duplicates
    const addedHotspotIds = new Set<number>();

    // TODO (later): pass real user id from controller/service.
    const createdByUserId = 1;

    // Track first route for special Day 1 handling
    let routeIndex = 0;

    for (const route of routes) {
      const routeProcessStart = Date.now();
      console.log('[TIMELINE] Processing route', routeIndex + 1, '/', routes.length, '- routeId:', route.itinerary_route_ID);
      
      const isFirstRoute = routeIndex === 0;
      routeIndex++;

      // ⚡ EXISTING HOTSPOTS: Filter hotspots that were already in this route
      const routeExistingHotspots = existingHotspots.filter(h => Number(h.itinerary_route_ID) === Number(route.itinerary_route_ID));
      const routeExistingHotspotIds = new Set(routeExistingHotspots.map(h => Number(h.hotspot_ID)));
      const routeManualHotspotIds = new Set(routeExistingHotspots.filter(h => h.hotspot_plan_own_way === 1).map(h => Number(h.hotspot_ID)));
      
      // ⚡ PERFORMANCE: Use pre-calculated last route ID
      const isLastRoute = Number(route.itinerary_route_ID) === Number(lastRouteId);
      
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
        : '20:00:00';
      
      let routeEndSeconds = timeToSeconds(routeEndTime);
      
      // Handle overnight routes: if end time < start time, add 24 hours to end
      const routeStartSeconds = timeToSeconds(routeStartTime);
      if (routeEndSeconds < routeStartSeconds) {
        routeEndSeconds += 86400; // Add 24 hours in seconds
      }
      
      let currentTime = routeStartTime;

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
      let destCityCoords: { lat: number; lon: number } | undefined = undefined;
      let sourceCity = "";
      let destinationCity = "";
      
      // ✅ RULE 1: ENFORCE CUTOFF (destination arrival deadline)
      // Calculate: latestAllowedHotspotEnd = hotelCutoffSeconds - (travel to destination + buffer)
      // This ensures user reaches destination city by the cutoff for hotel check-in
      let latestAllowedHotspotEndSeconds = 0; // Default: no cutoff (will be calculated for non-last routes)
      
      if (route.location_id) {
        const storedLoc = this.storedLocationMap.get(Number(route.location_id));
        
        if (storedLoc) {
          sourceCity = storedLoc.source_location || "";
          destinationCity = storedLoc.destination_location || "";
          currentCoords = {
            lat: Number(storedLoc.source_location_lattitude ?? 0),
            lon: Number(storedLoc.source_location_longitude ?? 0),
          };
          destCityCoords = {
            lat: Number(storedLoc.destination_location_lattitude ?? 0),
            lon: Number(storedLoc.destination_location_longitude ?? 0),
          };
        }
      }

      // Fallback to route fields if not found
      if (!sourceCity) sourceCity = ((route.location_name as string) || "").split('|')[0].trim();
      if (!destinationCity) destinationCity = ((route.next_visiting_location as string) || "").split('|')[0].trim();

      // 1) ADD REFRESHMENT BREAK (PHP line 969-993)
      // PHP adds 1-hour refreshment at route start EXCEPT for last route
      // Last route starts directly with hotspots (order 2) and skips refreshment ROW
      // BUT PHP still advances currentTime by buffer amount for last route (without creating row)
      if (!isLastRoute) {
        const globalSettings = gs;
        
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
        const globalSettings = gs;
        
        const bufferTime = globalSettings?.itinerary_common_buffer_time
          ? (globalSettings.itinerary_common_buffer_time instanceof Date
            ? `${String(globalSettings.itinerary_common_buffer_time.getUTCHours()).padStart(2, '0')}:${String(globalSettings.itinerary_common_buffer_time.getUTCMinutes()).padStart(2, '0')}:${String(globalSettings.itinerary_common_buffer_time.getUTCSeconds()).padStart(2, '0')}`
            : String(globalSettings.itinerary_common_buffer_time))
          : '01:00:00';
        
        const bufferSeconds = timeToSeconds(bufferTime);
        currentTime = addSeconds(currentTime, bufferSeconds);
      }

      // 2) SELECTED HOTSPOTS FOR THIS ROUTE
      // DAY-1 DIFFERENT CITIES: If Day 1 and source city != destination city, enforce max 3 priority hotspots
      let selectedHotspots: SelectedHotspot[] = [];
      
      const normalizedSourceCity = this.normalizeCityName(sourceCity);
      const normalizedDestinationCity = this.normalizeCityName(destinationCity);
      const isDay1DifferentCities = isFirstRoute && normalizedSourceCity && normalizedDestinationCity && normalizedSourceCity !== normalizedDestinationCity && (route as any).direct_to_next_visiting_place !== 1;
      
      if (isDay1DifferentCities) {
        // Day-1 with different cities: fetch max 3 priority hotspots from source city,
        // but ALSO include destination hotspots if there is time (user requirement).
        selectedHotspots = await this.fetchSelectedHotspotsForRoute(
          tx,
          planId,
          route.itinerary_route_ID,
          allHotspots,
          routeExistingHotspots,
          3, // maxSourceHotspots: Limit to top 3 from source city
          false, // skipDestinationHotspots: Include destination hotspots
          route,
          this.storedLocationMap.get(Number(route.location_id)),
          timingMap,
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
          
          selectedHotspots = []; // Empty - no hotspots for Day 1 in arrival city
        } else {
          // Traveling away from arrival city on Day 1 - apply same direct/non-direct logic
          const directToNext = (route as any).direct_to_next_visiting_place || 0;
          
          if (directToNext === 1) {
            // Direct travel: Skip arrival city hotspots
            
            selectedHotspots = await this.fetchSelectedHotspotsForRoute(
              tx,
              planId,
              route.itinerary_route_ID,
              allHotspots,
              routeExistingHotspots,
              undefined, // No source limit for direct travel
              false,
              route, // ⚡ PERFORMANCE: Pass route object
              this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
              timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
            );
          } else {
            // Non-direct travel: Visit all available arrival city hotspots
            
            selectedHotspots = await this.fetchSelectedHotspotsForRoute(
              tx,
              planId,
              route.itinerary_route_ID,
              allHotspots,
              routeExistingHotspots,
              undefined, // No source limit for direct travel
              false,
              route, // ⚡ PERFORMANCE: Pass route object
              this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
              timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
            );
          }
        }
      } else if (isFirstRoute && !shouldDeferDay1Sightseeing) {
        // Day 1 traveling to different city - check direct flag
        const directToNext = (route as any).direct_to_next_visiting_place || 0;
        
        if (directToNext === 1) {
          // Direct travel: Skip arrival city hotspots, go straight to destination
          // Fetch destination city hotspots only (fetchSelectedHotspotsForRoute handles direct flag internally)
          
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
            allHotspots,
            routeExistingHotspots,
            undefined, // No source limit for direct travel (will skip source anyway)
            false,
            route, // ⚡ PERFORMANCE: Pass route object
            this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
            timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
          );
        } else {
          // Non-direct travel: Visit all available arrival city hotspots
          
          // Fetch all available hotspots, skip destination (will be on Day 2)
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
            allHotspots,
            routeExistingHotspots,
            undefined, // No limit - schedule all top priority hotspots
            true, // Skip destination hotspots - they'll be added on Day 2
            route, // ⚡ PERFORMANCE: Pass route object
            this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
            timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
          );
        }
      } else if (isLastRoute && shouldDeferDay1Sightseeing) {
        // Last day in departure city - fetch hotspots for departure city sightseeing
        const currentCity = this.normalizeCityName(currentLocationName);
        const departureCity = this.normalizeCityName(departurePoint);
        
        if (currentCity === departureCity) {
          // Do local sightseeing on last day
          
          // Fetch hotspots for this city (will get popular spots)
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
            allHotspots,
            routeExistingHotspots,
            undefined,
            false,
            route, // ⚡ PERFORMANCE: Pass route object
            this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
            timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
          );
        } else {
          // Normal last route
          selectedHotspots = await this.fetchSelectedHotspotsForRoute(
            tx,
            planId,
            route.itinerary_route_ID,
            allHotspots,
            routeExistingHotspots,
            undefined,
            false,
            route, // ⚡ PERFORMANCE: Pass route object
            this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
            timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
          );
        }
      } else {
        // Normal route - fetch hotspots
        selectedHotspots = await this.fetchSelectedHotspotsForRoute(
          tx,
          planId,
          route.itinerary_route_ID,
          allHotspots,
          routeExistingHotspots,
          undefined,
          false,
          route, // ⚡ PERFORMANCE: Pass route object
          this.storedLocationMap.get(Number(route.location_id)), // ⚡ PERFORMANCE: Pass pre-fetched location
          timingMap, // ⚡ PERFORMANCE: Pass pre-fetched timing map
        );
      }

      // NO LUNCH BREAKS OR TIME CUTOFFS - User can schedule all hotspots and delete unwanted ones from UI
      // Day 1: Schedule ALL top priority hotspots without time constraints
      // User can reach hotel at any time

      // STRATEGY: For Day-1 different cities, process hotspots with strict priority walk
      // For other days, use multi-pass scheduling to fill gaps with deferred hotspots
      
      console.log('[TIMELINE] Selected hotspots for route:', selectedHotspots.length);
      const routeLoopStart = Date.now();
      let hotspotQueryCount = 0;
      let distanceCalcCount = 0;
      let operatingHoursCount = 0;
      
      // UNIFIED SCHEDULING: Multi-pass scheduling with deferred hotspots for ALL days
      const maxPasses = 5; // Prevent infinite loops
      let pass = 1;
      let addedInLastPass = true;
      const deferredHotspots: Array<typeof selectedHotspots[0] & { deferredAtTime: string; opensAt: string }> = [];
      
      // BUSINESS RULE: Default cutoff is 20:00. 
      // If user adds a hotspot manually OR it's a boundary-matched hotspot, the cutoff can extend to 22:00.
      const isDefaultCutoff = (routeEndTime === '20:00:00' || routeEndTime === '18:00:00');
      const hasManualHotspots = routeManualHotspotIds.size > 0 || selectedHotspots.some(sh => sh.isBoundaryMatch);
      
      let hotelCutoffSeconds = routeEndSeconds;
      if (isDefaultCutoff && hasManualHotspots) {
        hotelCutoffSeconds = timeToSeconds('22:00:00');
        // Update routeEndSeconds so the greedy loop respects the extension
        routeEndSeconds = hotelCutoffSeconds;
      }

      // ✅ CALCULATE INITIAL CUTOFF from starting location → destination
      // This ensures we don't add hotspots that would make the cutoff deadline impossible
      if (!isLastRoute) {
        const rawDestination = (route.next_visiting_location as string) || currentLocationName;
        const destinationCity = rawDestination.split('|')[0].trim();
        
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
        let hotspotsToTry = pass === 1 ? [...selectedHotspots] : deferredHotspots.filter(h => !addedHotspotIds.has(h.hotspot_ID));
        
        if (pass > 1) {
          deferredHotspots.length = 0; // Clear for next pass
          if (hotspotsToTry.length === 0) break; // No more to try
        }

        // GREEDY SELECTION: Pick the best next hotspot from hotspotsToTry
        while (hotspotsToTry.length > 0) {
          let bestIndex = -1;
          let bestScore = Infinity;
          
          for (let i = 0; i < hotspotsToTry.length; i++) {
            const sh = hotspotsToTry[i];
            if (addedHotspotIds.has(sh.hotspot_ID)) continue;

            const hotspotData = hotspotMap.get(sh.hotspot_ID);
            if (!hotspotData) continue;

            const hsLat = Number(hotspotData.hotspot_latitude ?? 0);
            const hsLon = Number(hotspotData.hotspot_longitude ?? 0);
            
            let distance = 0;
            if (currentCoords && hsLat && hsLon) {
              distance = this.distanceHelper.calculateHaversine(
                currentCoords.lat, currentCoords.lon,
                hsLat, hsLon
              );
            }

            const isManual = routeManualHotspotIds.has(sh.hotspot_ID);
            const effectivePriority = isManual ? 1 : Number(sh.hotspot_priority || 10);
            const cityOrder = sh.city_order || 1;
            
            // Score: lower is better. 
            // City Order (10000) + Priority (100) + Distance (1)
            // This ensures we finish source city before moving to via/destination
            const score = (cityOrder * 10000) + (effectivePriority * 100) + distance;
            
            if (score < bestScore) {
              bestScore = score;
              bestIndex = i;
            }
          }

          if (bestIndex === -1) break;

          // Pick the best one and remove from try list
          const sh = hotspotsToTry.splice(bestIndex, 1)[0];
          
          // Stop if we have run out of route time (unless it's a manual hotspot)
          let currentSeconds = timeToSeconds(currentTime);
          if (currentSeconds < routeStartSeconds) {
            currentSeconds += 86400;
          }
          
          const isManualHotspot = routeManualHotspotIds.has(sh.hotspot_ID) || sh.isBoundaryMatch;
          if (currentSeconds >= routeEndSeconds && !isManualHotspot) {
            continue; // Skip auto-suggested spots if time is up
          }

          if (addedHotspotIds.has(sh.hotspot_ID)) {
            continue;
          }

          const hotspotData = hotspotMap.get(sh.hotspot_ID);
          if (!hotspotData) continue;

          const rawLocation = hotspotData.hotspot_location as string || currentLocationName;
          const hotspotLocationName = rawLocation.split('|')[0].trim();
          const hotspotDuration = hotspotData.hotspot_duration || '01:00:00';
          const destCoords = {
            lat: Number(hotspotData.hotspot_latitude ?? 0),
            lon: Number(hotspotData.hotspot_longitude ?? 0),
          };
          
          if (!currentCoords) currentCoords = destCoords;

          distanceCalcCount++;
          const travelTimeToHotspot = await this.calculateTravelTimeWithCoords(
            tx,
            currentLocationName,
            hotspotLocationName,
            currentCoords,
            destCoords,
          );

          const travelDurationSeconds = timeToSeconds(travelTimeToHotspot);
          const timeAfterTravel = addSeconds(currentTime, travelDurationSeconds);
          
          const hotspotDurationSeconds = timeToSeconds(hotspotDuration);
          const timeAfterSightseeing = addSeconds(timeAfterTravel, hotspotDurationSeconds);
          
          let sightseeingEndSeconds = timeToSeconds(timeAfterSightseeing);
          if (sightseeingEndSeconds < routeStartSeconds) {
            sightseeingEndSeconds += 86400;
          }
          
          let isConflict = false;
          let conflictReason = "";

          if (latestAllowedHotspotEndSeconds > 0 && sightseeingEndSeconds > latestAllowedHotspotEndSeconds) {
            if (isManualHotspot) {
              isConflict = true;
              conflictReason = `Arrival at destination would be after ${secondsToTime(hotelCutoffSeconds)}`;
            } else {
              continue;
            }
          }
          
          if (sightseeingEndSeconds > routeEndSeconds && !isConflict) {
            if (isManualHotspot) {
              isConflict = true;
              const travelEndSeconds = timeToSeconds(timeAfterTravel);
              const dayEndStr = routeEndTime.substring(0, 5);
              
              if (travelEndSeconds > routeEndSeconds) {
                conflictReason = `Travel ends at ${timeAfterTravel.substring(0, 5)}, exceeding day end time of ${dayEndStr}`;
              } else {
                conflictReason = `Sightseeing would end at ${timeAfterSightseeing.substring(0, 5)}, exceeding day end time of ${dayEndStr}`;
              }
            } else {
              continue;
            }
          }
          
          const jsDay = route.itinerary_route_date ? new Date(route.itinerary_route_date).getDay() : 0;
          const dayOfWeek = (jsDay + 6) % 7;
          
          operatingHoursCount++;
          const operatingHoursCheck = this.checkHotspotOperatingHoursFromMap(
            timingMap,
            sh.hotspot_ID,
            dayOfWeek,
            timeAfterTravel,
            timeAfterSightseeing,
          );
          
          if (!operatingHoursCheck.canVisitNow && !isConflict) {
            if (isManualHotspot) {
              isConflict = true;
              const arrivalTime = timeAfterTravel.substring(0, 5);
              const closingInfo = operatingHoursCheck.operatingHours || "Closed today";
              
              if (operatingHoursCheck.operatingHours && operatingHoursCheck.operatingHours !== "Closed today") {
                const firstWindow = operatingHoursCheck.operatingHours.split(',')[0].trim();
                const [openStr, closeStr] = firstWindow.split(' - ').map(s => s.trim());
                
                if (timeAfterTravel < openStr) {
                  conflictReason = `Hotspot opens at ${openStr}. Arrival at ${arrivalTime}.`;
                } else if (timeAfterTravel >= closeStr) {
                  conflictReason = `Hotspot closed at ${arrivalTime}. Operating hours: ${closingInfo}`;
                } else {
                  conflictReason = `Hotspot closes at ${closeStr}. Visit would end at ${timeAfterSightseeing.substring(0, 5)}.`;
                }
              } else {
                conflictReason = `Hotspot is closed today. Operating hours: ${closingInfo}`;
              }
            } else if (operatingHoursCheck.nextWindowStart) {
              if (pass === 1) {
                deferredHotspots.push({
                  ...sh,
                  deferredAtTime: currentTime,
                  opensAt: operatingHoursCheck.nextWindowStart,
                });
                continue;
              }
            } else {
              continue;
            }
          }
          
          addedInLastPass = true;
          const currentOrder = order;
          const travelLocationType = this.getTravelLocationType(currentLocationName, hotspotLocationName);
          
          const { row: travelRow, nextTime: tToHotspot } = await this.travelBuilder.buildTravelSegment(tx, {
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
            isConflict,
            conflictReason,
          });

          hotspotRows.push(travelRow);
          currentTime = tToHotspot;
          currentLocationName = hotspotLocationName;
          currentCoords = destCoords;

          const { row: hotspotRow, nextTime: tAfterHotspot } = await this.hotspotBuilder.build(tx, {
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
            hotspotPlanOwnWay: routeManualHotspotIds.has(sh.hotspot_ID) ? 1 : 0,
            isConflict,
            conflictReason,
            isManual: routeManualHotspotIds.has(sh.hotspot_ID),
          });

          hotspotRows.push(hotspotRow);
          addedHotspotIds.add(sh.hotspot_ID);
          order++;
          currentTime = tAfterHotspot;

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
        pass++;
      }
      
      // End of unified scheduling
      console.log('[TIMELINE] Route loop stats - Queries:', hotspotQueryCount, '| Distance calcs:', distanceCalcCount, '| Operating hours:', operatingHoursCount, '| Time:', Date.now() - routeLoopStart, 'ms');

      // ✅ RULE 2 + 3: TRAVEL TO HOTEL & FIX TIME BUG (item_type = 5)
      // BUSINESS RULE: Hotel check-in must be before the cutoff time
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
            // ✅ PHP PARITY: Only use coordinates (Haversine) if we actually visited hotspots.
            // If no hotspots were visited, PHP uses the direct city-to-city distance from DB.
            sourceCoords: addedHotspotIds.size > 0 ? currentCoords : undefined,
            destCoords: addedHotspotIds.size > 0 ? destCityCoords : undefined,
          });

        // ✅ RULE 3: Fix "06:58 AM" time bug using proper UTC date conversion
        // Never mix local timezone Date objects with UTC TIME fields
        let adjustedHotelRow = { ...toHotelRow };
        const hotelEndSeconds = timeToSeconds(tAfterHotel);
        
        // Enforce cutoff hard cap
        if (hotelEndSeconds > hotelCutoffSeconds) {
          // Use TimeConverter.toDate() for consistent UTC handling
          adjustedHotelRow.hotspot_end_time = TimeConverter.toDate(secondsToTime(hotelCutoffSeconds));
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
            // ✅ PHP PARITY: Only use coordinates (Haversine) if we actually visited hotspots.
            destCoords: addedHotspotIds.size > 0 ? destCityCoords : undefined,
          });

        hotspotRows.push(returnRow);
        currentTime = tAfterReturn;
        currentLocationName = plan.departure_location as string;
      }
    }

    // ⚡ ENRICH ROWS FOR PREVIEW (Add names, text, and timeRange)
    for (const row of hotspotRows) {
      const itemType = Number(row.item_type);
      const startTime = TimeConverter.toTimeString(row.hotspot_start_time).substring(0, 5);
      const endTime = TimeConverter.toTimeString(row.hotspot_end_time).substring(0, 5);
      (row as any).timeRange = `${startTime} - ${endTime}`;

      if (itemType === 1) {
        (row as any).type = 'start';
        (row as any).text = 'Start Day';
      } else if (itemType === 2 || itemType === 3 || itemType === 5 || itemType === 7) {
        (row as any).type = 'travel';
        const hs = hotspotMap.get(row.hotspot_ID);
        const destName = hs ? (hs.hotspot_name || hs.hotspot_location || 'Hotspot').split('|')[0] : (row as any).via_location_name || 'Destination';
        (row as any).text = `Travel to ${destName}`;
      } else if (itemType === 4) {
        (row as any).type = 'hotspot';
        (row as any).locationId = row.hotspot_ID;
        const hs = hotspotMap.get(row.hotspot_ID);
        (row as any).text = hs ? (hs.hotspot_name || hs.hotspot_location || 'Hotspot').split('|')[0] : 'Hotspot';
      } else if (itemType === 6) {
        (row as any).type = 'hotel';
        (row as any).text = 'Hotel Stay';
      }
    }

    // Detect shifted and dropped items
    const shiftedItems: any[] = [];
    const droppedItems: any[] = [];
    const addedHotspotIdsInResult = new Set<number>();

    for (const row of hotspotRows) {
      if (row.item_type === 4 && row.hotspot_ID) {
        const hotspotId = Number(row.hotspot_ID);
        addedHotspotIdsInResult.add(hotspotId);
        const before = beforeHotspots.get(hotspotId);
        if (before) {
          const afterStartTime = TimeConverter.toTimeString(row.hotspot_start_time);
          if (before.startTime !== afterStartTime || before.routeId !== Number(row.itinerary_route_ID)) {
            shiftedItems.push({
              hotspotId,
              oldTime: before.startTime,
              newTime: afterStartTime,
              oldRouteId: before.routeId,
              newRouteId: Number(row.itinerary_route_ID),
            });
          }
        }
      }
    }

    for (const [hotspotId, before] of beforeHotspots.entries()) {
      if (!addedHotspotIdsInResult.has(hotspotId)) {
        droppedItems.push({
          hotspotId,
          oldTime: before.startTime,
          oldRouteId: before.routeId,
        });
      }
    }

    return { 
      hotspotRows, 
      parkingRows,
      shiftedItems,
      droppedItems
    };
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
   * 
   * @param allHotspots - Pre-fetched array of all active hotspots (performance optimization)
   * @param maxSourceHotspots - Optional limit for source location hotspots (for Day 1 arrival city)
   */
  private async fetchSelectedHotspotsForRoute(
    tx: Tx,
    planId: number,
    routeId: number,
    allHotspots: any[],
    existingHotspotsFromEngine: any[] = [],
    maxSourceHotspots?: number,
    skipDestinationHotspots?: boolean,
    routeObj?: RouteRow, // ⚡ PERFORMANCE: Optional pre-fetched route
    storedLocObj?: any, // ⚡ PERFORMANCE: Optional pre-fetched location
    timingMap?: Map<number, Map<number, any[]>>, // ⚡ PERFORMANCE: Optional pre-fetched timing map
  ): Promise<SelectedHotspot[]> {
    const fetchStart = Date.now();
    try {
      // 1) Load route context (dates + locations)
      let opStart = Date.now();
      const route = routeObj || (await (tx as any).dvi_itinerary_route_details?.findFirst({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          deleted: 0,
          status: 1,
        },
      })) as RouteRow | null;
      console.log('[TIMELINE] fetchSelectedHotspotsForRoute - fetch route:', Date.now() - opStart, 'ms');

      if (!route) {
        return [];
      }

      // 0) Use existing hotspots passed from the engine
      // These are hotspots that were already in the route.
      // We must include them regardless of city matching.
      const existingHotspotIds = new Set(existingHotspotsFromEngine.map((h: any) => Number(h.hotspot_ID)));
      const manualHotspotIds = new Set(
        existingHotspotsFromEngine
          .filter(h => h.hotspot_plan_own_way === 1)
          .map(h => Number(h.hotspot_ID))
      );

      // PHP LINE 1023-1033: Get location names from stored_locations table, NOT from route fields
      // $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');
      // $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');
      opStart = Date.now();
      
      // Use route fields as primary source of truth for names
      let targetLocation = route.location_name || "";
      let nextLocation = route.next_visiting_location || "";
      
      // If location_id is present, use it to refine names (PHP parity)
      if (route.location_id && Number(route.location_id) > 0) {
        const storedLoc = storedLocObj || this.storedLocationMap.get(Number(route.location_id));
        if (storedLoc) {
          targetLocation = storedLoc.source_location || targetLocation;
          nextLocation = storedLoc.destination_location || nextLocation;
        }
      }
      
      if (!targetLocation && !nextLocation) {
        return [];
      }
      console.log('[TIMELINE] fetchSelectedHotspotsForRoute - location lookup:', Date.now() - opStart, 'ms');

      // PHP uses day-of-week filtering via dvi_hotspot_timing (date('N')-1 => Monday=0)
      const routeDate = route.itinerary_route_date
        ? new Date(route.itinerary_route_date)
        : null;
      const phpDow = routeDate
        ? ((routeDate.getDay() + 6) % 7) // JS: Sunday=0; PHP: Monday=0, Sunday=6
        : undefined;

      // 2) Preload hotspot timings for this day (if available)
      // PHP uses LEFT JOIN without filtering hotspot_closed - includes all hotspots with timing records
      opStart = Date.now();
      let allowedHotspotIds: Set<number> | null = null;
      
      if (phpDow !== undefined) {
        if (timingMap) {
          // ⚡ PERFORMANCE: Use pre-fetched timing map
          allowedHotspotIds = new Set();
          for (const [hotspotId, dayMap] of timingMap.entries()) {
            if (dayMap.has(phpDow)) {
              allowedHotspotIds.add(hotspotId);
            }
          }
        } else {
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
      }
      console.log('[TIMELINE] fetchSelectedHotspotsForRoute - fetch timings:', Date.now() - opStart, 'ms');

      // 3) Use pre-fetched hotspots array (passed as parameter for performance)
      // Note: allHotspots is now passed from buildTimelineForPlan to avoid redundant queries

      // 3b) Fetch operating hours for all hotspots to enable time-aware sorting
      // PHP behavior: sortHotspots() re-orders to prioritize time-critical hotspots
      // Include all timing records (even closed) - checkHotspotOperatingHours will filter later
      let hotspotTimings: any[] = [];
      if (phpDow !== undefined) {
        if (timingMap) {
          // ⚡ PERFORMANCE: Use pre-fetched timing map
          for (const dayMap of timingMap.values()) {
            const timings = dayMap.get(phpDow);
            if (timings) {
              hotspotTimings.push(...timings);
            }
          }
        } else {
          hotspotTimings = await (tx as any).dvi_hotspot_timing?.findMany({
            where: {
              hotspot_timing_day: phpDow,
              deleted: 0,
              status: 1,
            },
          }) || [];
        }
      }

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

      const normalizedTarget = this.normalizeCityName(targetLocation);
      const normalizedNext = this.normalizeCityName(nextLocation);

      const sourceCityId = this.cityIdMap.get(normalizedTarget);
      const destCityId = this.cityIdMap.get(normalizedNext);
      
      const sIdStr = sourceCityId ? String(sourceCityId) : null;
      const dIdStr = destCityId ? String(destCityId) : null;

      // Get starting location coordinates from stored_locations (already fetched above)
      // PHP line 1108-1109: Uses source coordinates for starting point
      let startLat = 0;
      let startLon = 0;
      
      if (route.location_id) {
        const storedLoc = this.storedLocationMap.get(Number(route.location_id));
        
        if (storedLoc) {
          // Use source coordinates (PHP uses source for starting point)
          startLat = Number(storedLoc.source_location_lattitude ?? 0);
          startLon = Number(storedLoc.source_location_longitude ?? 0);
        }
      }
      
      // Fallback: If location_id is missing/0 and no coordinates, try by location_name
      if (!startLat && !startLon && targetLocation) {
        // Try exact match first
        let foundLoc = Array.from(this.storedLocationMap.values()).find(
          sl => sl.source_location === targetLocation
        );
        
        // Fuzzy match if exact didn't work
        if (!foundLoc && route.location_name) {
          const searchName = route.location_name.toLowerCase();
          foundLoc = Array.from(this.storedLocationMap.values()).find(
            sl => sl.source_location?.toLowerCase().includes(searchName)
          );
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
      const existingHotspots: any[] = [];

      // Helper function to match location with normalization
      // Normalizes both sides to handle "Chennai International Airport" == "Chennai"
      const containsLocation = (hotspotLocation: string | null, targetLocation: string | null): boolean => {
        if (!hotspotLocation || !targetLocation) return false;
        
        const normalizedTarget = this.normalizeCityName(targetLocation);
        const hotspotParts = hotspotLocation.split('|').map(p => this.normalizeCityName(p));
        
        return hotspotParts.some(p => {
          if (p.length < 3 || normalizedTarget.length < 3) return p === normalizedTarget;
          // Match if one contains the other (e.g. "Chennai" in "Chennai International Airport")
          return normalizedTarget.includes(p) || p.includes(normalizedTarget);
        });
      };

      for (const h of allHotspots) {
        const hId = Number(h.hotspot_ID ?? 0);
        const isManual = manualHotspotIds.has(hId);

        // Check if timing allows this hotspot on this day
        // Manual hotspots are ALWAYS included in the pool, even if closed (they will be flagged as conflicts)
        if (!isManual && allowedHotspotIds && !allowedHotspotIds.has(hId)) {
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

        // NEW: Check city_boundaries for "on-the-way" hotspots
        let matchesBoundary = false;
        if (h.city_boundaries && sIdStr && dIdStr) {
          try {
            const boundaries = JSON.parse(h.city_boundaries);
            if (Array.isArray(boundaries)) {
              matchesBoundary = boundaries.includes(sIdStr) && boundaries.includes(dIdStr);
            }
          } catch (e) {
            // Ignore parse errors
          }
        }

        const hotspotWithDistance = { 
          ...h, 
          hotspot_distance: distance,
          isBoundaryMatch: matchesBoundary 
        };

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
        
        if (matchesBoundary) {
          // Boundary matches are treated as "on-the-way" (Via) hotspots
          viaRouteHotspots.push(hotspotWithDistance);
        } else if (matchesDestination) {
          destinationHotspots.push(hotspotWithDistance);
        }

        // If it's an existing hotspot, add to existing bucket
        if (existingHotspotIds.has(Number(h.hotspot_ID))) {
          existingHotspots.push(hotspotWithDistance);
        }
      }
      
      // Fetch via routes for this route and match hotspots
      const viaRoutes = this.viaRouteMap.get(Number(routeId)) || [];
      
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
          // Manual hotspots and boundary-matched hotspots are treated as Priority 1 (highest)
          const aIsManual = manualHotspotIds.has(Number(a.hotspot_ID)) || a.isBoundaryMatch;
          const bIsManual = manualHotspotIds.has(Number(b.hotspot_ID)) || b.isBoundaryMatch;
          
          const aPriority = aIsManual ? 1 : Number(a.hotspot_priority ?? 0);
          const bPriority = bIsManual ? 1 : Number(b.hotspot_priority ?? 0);
          
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
      }
      
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
        } else {
          matchingHotspots = [...sourceLocationHotspots, ...viaRouteHotspots, ...destinationHotspots];
        }
      }

      // Add existing hotspots at the end (ensuring no duplicates)
      // CRITICAL: Only add MANUAL hotspots here. Automatic hotspots should be re-evaluated
      // based on the current city to prevent "stickiness" of incorrect hotspots.
      for (const eh of existingHotspots) {
        const isManual = manualHotspotIds.has(Number(eh.hotspot_ID));
        if (isManual && !matchingHotspots.some(h => Number(h.hotspot_ID) === Number(eh.hotspot_ID))) {
          matchingHotspots.push(eh);
        }
      }

      // De-duplicate by hotspot_ID and keep first occurrence
      const seen = new Set<number>();
      const uniqueHotspots: any[] = [];
      
      // Helper to determine city order based on which bucket it was in
      const getCityOrder = (h: any) => {
        if (sourceLocationHotspots.some(sh => sh.hotspot_ID === h.hotspot_ID)) return 1;
        if (viaRouteHotspots.some(vh => vh.hotspot_ID === h.hotspot_ID)) return 2;
        if (destinationHotspots.some(dh => dh.hotspot_ID === h.hotspot_ID)) return 3;
        return 1; // Default to source for manual/existing
      };

      for (const h of matchingHotspots) {
        const id = Number(h.hotspot_ID ?? 0) || 0;
        if (!id || seen.has(id)) continue;
        seen.add(id);
        uniqueHotspots.push(h);
      }
      
      // PHP PARITY: Do NOT re-sort after concatenation
      // The order from concatenation (source + destination + via) is the final order

      return uniqueHotspots.map((h: any, index: number) => {
        const isManual = manualHotspotIds.has(Number(h.hotspot_ID));
        return {
          hotspot_ID: Number(h.hotspot_ID ?? 0) || 0,
          hotspot_name: h.hotspot_name,
          display_order: Number(h.hotspot_priority ?? index + 1) || index + 1,
          hotspot_priority: isManual ? 1 : (Number(h.hotspot_priority ?? 0) || 10),
          city_order: getCityOrder(h),
          isBoundaryMatch: h.isBoundaryMatch,
        };
      });
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
    // ⚡ PERFORMANCE: Use pre-fetched map
    const hotel = this.hotelDetailsMap.get(routeId);

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
    // ⚡ PERFORMANCE: Use pre-fetched maps
    const details = this.hotelDetailsMap.get(routeId);

    const hotelId = Number(details?.hotel_id ?? 0) || 0;
    if (!hotelId) return null;

    const hotel = this.hotelDataMap.get(hotelId);

    if (!hotel) {
      return { hotelId, hotelName: null, hotelCity: null };
    }

    // Try to get the location name if hotel_city is a location_id
    let hotelCity: string | null = null;
    const citySafe = Number(hotel.hotel_city) || 0;
    if (citySafe > 0) {
      // hotel_city is a location_id reference, look it up in pre-fetched map
      hotelCity = this.hotelCityMap.get(citySafe) ?? null;
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

  /**
   * Preview manual hotspot addition.
   * Returns the proposed timeline with conflict warnings.
   */
  async previewManualHotspotAdd(
    tx: Tx,
    planId: number,
    routeId: number,
    hotspotId: number,
  ): Promise<any> {
    // 1. Fetch current manual hotspots for this plan
    const manualHotspots = await (tx as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        hotspot_plan_own_way: 1,
        deleted: 0,
      },
    });

    // 2. Add the new hotspot to the list
    const newHotspot = {
      itinerary_plan_ID: planId,
      itinerary_route_ID: routeId,
      hotspot_ID: hotspotId,
      hotspot_plan_own_way: 1,
    };
    
    const updatedManualHotspots = [...manualHotspots, newHotspot];

    // 3. Rebuild the timeline in memory
    const { hotspotRows, parkingRows } = await this.buildTimelineForPlan(tx, planId, updatedManualHotspots);

    return {
      hotspotRows,
      parkingRows,
      hasConflicts: hotspotRows.some(r => (r as any).isConflict),
    };
  }
}

// --- RECENT EDITS BELOW --- //