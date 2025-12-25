// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/helpers/timeline.builder.ts

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
import { timeToSeconds, secondsToTime, addSeconds } from "./time.helper";
import { DistanceHelper } from "./distance.helper";
import { TimeConverter } from "./time-converter";

// New Modular Helpers
import { TimelinePrefetcher } from "./timeline.prefetch";
import { HotspotSelector, SelectedHotspot } from "./timeline.hotspot-selector";
import { computeGreedyScore } from "./timeline.scoring";
import { computeCutoffPolicy } from "./timeline.cutoff-policy";
import { OperatingHoursChecker } from "./timeline.operating-hours";
import { TimelineLogger } from "./timeline.logger";

type Tx = Prisma.TransactionClient;

export class TimelineBuilder {
  private readonly refreshmentBuilder = new RefreshmentBuilder();
  private readonly travelBuilder = new TravelSegmentBuilder();
  private readonly hotspotBuilder = new HotspotSegmentBuilder();
  private readonly hotelBuilder = new HotelTravelBuilder();
  private readonly returnBuilder = new ReturnSegmentBuilder();
  public readonly parkingBuilder = new ParkingChargeBuilder();
  private readonly distanceHelper = new DistanceHelper();

  private readonly prefetcher = new TimelinePrefetcher();
  private readonly selector = new HotspotSelector();
  private readonly hoursChecker = new OperatingHoursChecker();

  constructor() {}

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
    TimelineLogger.clear();
    TimelineLogger.log("[TIMELINE] buildTimelineForPlan started for planId:", planId);

    // 1. PRE-FETCH EVERYTHING
    const context = await this.prefetcher.prefetchAll(tx, planId);
    if (!context.plan || !context.routes.length) {
      return { hotspotRows: [], parkingRows: [] };
    }
    const { plan, routes, gs, hotspotMap, timingMap, lastRouteId } = context;
    this.distanceHelper.setGlobalSettings(gs);
    this.parkingBuilder.clearCache();

    const hotspotRows: HotspotDetailRow[] = [];
    const parkingRows: ParkingChargeRow[] = [];
    const addedHotspotIds = new Set<number>();
    const createdByUserId = 1;

    const DAY_SECONDS = 24 * 60 * 60;

    const willCrossMidnight = (startTime: string, durationSec: number): boolean => {
      const s = timeToSeconds(startTime);
      if (s < 0) return true;
      return s + Math.max(0, durationSec) >= DAY_SECONDS;
    };

    // 2. PROCESS ROUTES
    for (let i = 0; i < routes.length; i++) {
      const route = routes[i];
      const isLastRoute = route.itinerary_route_ID === lastRouteId;

      // Setup Route Time (fallbacks preserved)
      const routeStartTime = context.toTimeString(route.route_start_time) || "09:00:00";
      const routeEndTime = context.toTimeString(route.route_end_time) || "20:00:00";
      let currentTime = routeStartTime;

      // 3. REFRESHMENT (PHP Parity)
      const bufferTime = context.toTimeString(gs?.itinerary_common_buffer_time) || "01:00:00";
      const bufferSec = timeToSeconds(bufferTime);

      if (!isLastRoute) {
        // do not allow refreshment to wrap day (safety)
        if (!willCrossMidnight(currentTime, bufferSec)) {
          const refreshmentEndTime = addSeconds(currentTime, bufferSec);

          hotspotRows.push({
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            item_type: 1,
            hotspot_order: 1,
            hotspot_traveling_time: TimeConverter.toDate(bufferTime),
            hotspot_start_time: TimeConverter.toDate(currentTime),
            hotspot_end_time: TimeConverter.toDate(refreshmentEndTime),
            createdby: createdByUserId,
            status: 1,
            deleted: 0,
          });

          currentTime = refreshmentEndTime;
        }
      } else {
        // Last route advances time but no row
        if (!willCrossMidnight(currentTime, bufferSec)) {
          currentTime = addSeconds(currentTime, bufferSec);
        }
      }

      // 4. SELECT HOTSPOTS
      const selectedHotspots = await this.selector.selectForRoute(
        tx,
        plan,
        route,
        context,
        existingHotspots,
      );
      TimelineLogger.log(
        `[TIMELINE] Route ${route.itinerary_route_ID}: Selected ${selectedHotspots.length} hotspots from pool`,
      );

      // Manual hotspot IDs for this route (real DB field)
      const routeManualIds = new Set<number>(
        (existingHotspots || [])
          .filter(
            (h: any) =>
              Number(h.itinerary_route_ID) === Number(route.itinerary_route_ID) &&
              Number(h.hotspot_plan_own_way) === 1,
          )
          .map((h: any) => Number(h.hotspot_ID)),
      );

      // ✅ FIX: hasManualOrBoundary must be based on:
      // - manual hotspots on this route
      // - OR selector’s boundary/manual marker (boundary match)
      const hasManualOrBoundary = selectedHotspots.some((sh) => {
        if (routeManualIds.has(sh.hotspot_ID)) return true;
        return sh.isBoundaryMatch === true; // boundary match (NOT source/dest)
      });

      // 5. CUTOFF POLICY (Dynamic deadlines)
      const policy = await computeCutoffPolicy(
        tx,
        route,
        plan,
        currentTime,
        this.distanceHelper,
        isLastRoute,
        hasManualOrBoundary,
      );

      TimelineLogger.log(
        `[TIMELINE] Route ${route.itinerary_route_ID}: Policy - latestAllowedEnd=${policy.latestAllowedEnd}, hotelCutoff=${secondsToTime(
          policy.hotelCutoff,
        )}, hasManualOrBoundary=${hasManualOrBoundary}`,
      );

      const destCityName = (route.next_visiting_location || "").split("|")[0].trim();
      const destCityNorm = destCityName.toLowerCase();

      const computeDynamicLatestAllowedEnd = async (
        targetLocation: string,
        targetCoords?: { lat: number; lon: number },
      ): Promise<{
        latestAllowedEndSec: number;
        isAtDestinationNow: boolean;
      }> => {
        const curNorm = (targetLocation || "").split("|")[0].trim().toLowerCase();
        const isAtDestinationNow = curNorm === destCityNorm;

        // ✅ PHP Parity: Even if we are in the destination city, we still calculate 
        // travel time to the specific destination (e.g. Hotel or Airport) 
        // if coordinates are available.
        const remaining = await this.distanceHelper.fromSourceAndDestination(
          tx,
          targetLocation,
          destCityName,
          2,
          targetCoords,
          policy.destCityCoords,
        );

        const remainingSec =
          timeToSeconds(remaining.travelTime || "00:00:00") +
          timeToSeconds(remaining.bufferTime || "00:00:00");

        const latestAllowedEndSec = Math.max(0, policy.hotelCutoff - remainingSec);
        return { latestAllowedEndSec, isAtDestinationNow };
      };

      // 6. GREEDY SCHEDULING
      let order = 2; // Refreshment was 1

      let pass = 1;
      let addedInPass = true;
      const deferred: (SelectedHotspot & { opensAt: string })[] = [];

      while (pass <= 2 && addedInPass) {
        addedInPass = false;
        let pool = pass === 1 ? [...selectedHotspots] : [...deferred];

        if (pass === 2) {
          TimelineLogger.log(
            `[TIMELINE] Route ${route.itinerary_route_ID}: Starting Pass 2 for ${deferred.length} deferred spots`,
          );
          deferred.length = 0;
        }

        while (pool.length > 0) {
          pool.sort((a, b) => {
            const sa = computeGreedyScore(a, policy.currentCoords, hotspotMap);
            const sb = computeGreedyScore(b, policy.currentCoords, hotspotMap);
            return sa - sb;
          });

          const sh = pool.shift()!;
          if (addedHotspotIds.has(sh.hotspot_ID)) continue;

          const hs = hotspotMap.get(sh.hotspot_ID);
          if (!hs) continue;

          // Travel seconds (local) to hotspot
          const travel = await this.distanceHelper.fromSourceAndDestination(
            tx,
            policy.currentLocation,
            hs.location,
            1,
            policy.currentCoords,
            { lat: hs.lat, lon: hs.lon },
          );

          const travelSec =
            timeToSeconds(travel.travelTime || "00:00:00") +
            timeToSeconds(travel.bufferTime || "00:00:00");

          // ✅ FIX: never allow travel to wrap midnight (this caused 22:56 -> 00:56)
          if (willCrossMidnight(currentTime, travelSec)) {
            if (routeManualIds.has(sh.hotspot_ID)) {
              // manual: keep as conflict visit (but we cannot safely build wrapped travel row)
              TimelineLogger.log(
                `[TIMELINE] Manual HS ${hs.name} would cross midnight during travel. Marking as conflict + skipping auto scheduling.`,
              );
              // We cannot add wrapped travel/hotspot without corrupting timeline,
              // so we skip it here (manual conflict handling is preserved for closed/deadline cases).
            }
            continue;
          }

          const travelTime = addSeconds("00:00:00", travelSec);
          const arrivalTime = addSeconds(currentTime, travelSec);

          const durationSec = timeToSeconds(hs.duration || "01:00:00");

          // baseline visit end (may be shifted by opening hours)
          let visitStartTime = arrivalTime;
          let visitEndTime = addSeconds(arrivalTime, durationSec);

          // Operating hours checks
          let isConflict = false;
          let conflictReason = "";

          const hours = this.hoursChecker.check(
            timingMap,
            sh.hotspot_ID,
            route.itinerary_route_date,
            arrivalTime,
            visitEndTime,
          );

          if (!hours.canVisitNow) {
            if (pass === 1 && hours.nextWindowStart) {
              TimelineLogger.log(
                `[TIMELINE] Deferring ${hs.name} (ID: ${sh.hotspot_ID}) to Pass 2. Opens at ${hours.nextWindowStart}`,
              );
              deferred.push({ ...sh, opensAt: hours.nextWindowStart });
              continue;
            }

            if (routeManualIds.has(sh.hotspot_ID)) {
              isConflict = true;
              conflictReason = "Manual Hotspot Conflict (Closed)";
              TimelineLogger.log(
                `[TIMELINE] Manual HS ${hs.name} (ID: ${sh.hotspot_ID}) is CLOSED. Adding as CONFLICT.`,
              );
            } else {
              TimelineLogger.log(
                `[TIMELINE] Skipping ${hs.name} (ID: ${sh.hotspot_ID}) - Closed. Arrival: ${arrivalTime}, End: ${visitEndTime}`,
              );
              continue;
            }
          }

          // If arrived before opening, shift start to opening time
          if (hours.adjustedStartTime) {
            visitStartTime = hours.adjustedStartTime;
            visitEndTime = addSeconds(visitStartTime, durationSec);
          }

          // ✅ FIX: never allow visit itself to wrap midnight
          if (willCrossMidnight(visitStartTime, durationSec)) {
            if (routeManualIds.has(sh.hotspot_ID)) {
              isConflict = true;
              conflictReason = conflictReason
                ? conflictReason + " & Crosses Midnight"
                : "Manual Hotspot Conflict (Crosses Midnight)";
              TimelineLogger.log(
                `[TIMELINE] Manual HS ${hs.name} crosses midnight. Adding as CONFLICT.`,
              );
            } else {
              TimelineLogger.log(
                `[TIMELINE] Skipping ${hs.name} (ID: ${sh.hotspot_ID}) - Visit crosses midnight. Start: ${visitStartTime}`,
              );
              continue;
            }
          }

          // Deadline check (dynamic)
          const { latestAllowedEndSec, isAtDestinationNow } = await computeDynamicLatestAllowedEnd(
            hs.location,
            { lat: hs.lat, lon: hs.lon },
          );
          const endSec = timeToSeconds(visitEndTime);

          // Always enforce hotelCutoff as absolute day boundary
          if (endSec > policy.hotelCutoff) {
            if (routeManualIds.has(sh.hotspot_ID)) {
              isConflict = true;
              conflictReason = conflictReason
                ? conflictReason + " & Past Cutoff"
                : "Manual Hotspot Conflict (Past Cutoff)";
            } else {
              TimelineLogger.log(
                `[TIMELINE] Skipping ${hs.name} (ID: ${sh.hotspot_ID}) - Past cutoff ${secondsToTime(
                  policy.hotelCutoff,
                )}. End: ${visitEndTime}`,
              );
              continue;
            }
          }

          if (endSec > latestAllowedEndSec && !isAtDestinationNow) {
            if (routeManualIds.has(sh.hotspot_ID)) {
              isConflict = true;
              conflictReason = conflictReason
                ? conflictReason + " & Past Deadline"
                : "Manual Hotspot Conflict (Past Deadline)";
              TimelineLogger.log(
                `[TIMELINE] Manual HS ${hs.name} (ID: ${sh.hotspot_ID}) past deadline ${secondsToTime(
                  latestAllowedEndSec,
                )}. Adding as CONFLICT.`,
              );
            } else {
              TimelineLogger.log(
                `[TIMELINE] Skipping ${hs.name} (ID: ${sh.hotspot_ID}) - Past deadline ${secondsToTime(
                  latestAllowedEndSec,
                )}. End: ${visitEndTime}`,
              );
              continue;
            }
          }

          TimelineLogger.log(
            `[TIMELINE] Adding ${hs.name} (ID: ${sh.hotspot_ID}). Arrival: ${arrivalTime}, Visit: ${visitStartTime} - ${visitEndTime}`,
          );

          // ADD TRAVEL ROW
          const { row: tRow } = await this.travelBuilder.buildTravelSegment(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order,
            item_type: 3,
            startTime: currentTime,
            userId: createdByUserId,
            sourceLocationName: policy.currentLocation,
            destinationLocationName: hs.location,
            hotspotId: sh.hotspot_ID,
            sourceCoords: policy.currentCoords,
            destCoords: { lat: hs.lat, lon: hs.lon },
            travelLocationType: 1,
          });

          hotspotRows.push(tRow);
          currentTime = TimeConverter.toTimeString(tRow.hotspot_end_time);

          // If we must wait until opening, jump time forward (no new item types)
          if (timeToSeconds(visitStartTime) > timeToSeconds(currentTime)) {
            currentTime = visitStartTime;
          }

          // ADD HOTSPOT ROW
          const { row: hRow } = await this.hotspotBuilder.build(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order,
            hotspotId: sh.hotspot_ID,
            startTime: currentTime,
            userId: createdByUserId,
            totalAdult: plan.total_adult,
            totalChildren: plan.total_children,
            totalInfants: plan.total_infants,
            nationality: plan.nationality,
            itineraryPreference: plan.itinerary_preference,
            hotspotPlanOwnWay: routeManualIds.has(sh.hotspot_ID) ? 1 : 0,
            isConflict,
            conflictReason,
          });

          hotspotRows.push(hRow);
          currentTime = TimeConverter.toTimeString(hRow.hotspot_end_time);

          addedHotspotIds.add(sh.hotspot_ID);
          policy.currentLocation = hs.location;
          policy.currentCoords = { lat: hs.lat, lon: hs.lon };
          order++;
          addedInPass = true;

          // Parking rows
          const pRows = await this.parkingBuilder.buildForHotspot(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            hotspotId: sh.hotspot_ID,
            userId: createdByUserId,
          });
          parkingRows.push(...pRows);
        }

        pass++;
      }

      // 6.5 DIRECT TRAVEL (PHP Parity)
      if (route.direct_to_next_visiting_place === 1) {
        // Guard: never wrap midnight
        const directTravel = await this.distanceHelper.fromSourceAndDestination(
          tx,
          policy.currentLocation,
          destCityName,
          2,
          policy.currentCoords,
          policy.destCityCoords,
        );
        const directSec =
          timeToSeconds(directTravel.travelTime || "00:00:00") +
          timeToSeconds(directTravel.bufferTime || "00:00:00");

        if (!willCrossMidnight(currentTime, directSec)) {
          const { row: directRow } = await this.travelBuilder.buildTravelSegment(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order,
            item_type: 2,
            startTime: currentTime,
            userId: createdByUserId,
            sourceLocationName: policy.currentLocation,
            destinationLocationName: destCityName,
            sourceCoords: policy.currentCoords,
            destCoords: policy.destCityCoords,
            travelLocationType: 2,
          });

          hotspotRows.push(directRow);
          currentTime = TimeConverter.toTimeString(directRow.hotspot_end_time);
          TimelineLogger.log(`[TIMELINE] Adding direct travel to ${destCityName}. Distance: ${directRow.hotspot_travelling_distance} KM`);
          policy.currentLocation = destCityName;
          policy.currentCoords = policy.destCityCoords;
          order++;
        }
      }

      // 7. HOTEL / RETURN
      if (!isLastRoute) {
        const destCity = (route.next_visiting_location || "").split("|")[0].trim();

        const toHotelTravel = await this.distanceHelper.fromSourceAndDestination(
          tx,
          policy.currentLocation,
          destCity,
          2,
          policy.currentCoords,
          policy.destCityCoords,
        );
        const toHotelSec =
          timeToSeconds(toHotelTravel.travelTime || "00:00:00") +
          timeToSeconds(toHotelTravel.bufferTime || "00:00:00");

        if (!willCrossMidnight(currentTime, toHotelSec)) {
          const { row: toHotel } = await this.hotelBuilder.buildToHotel(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order,
            startTime: currentTime,
            travelLocationType: 2,
            userId: createdByUserId,
            sourceLocationName: policy.currentLocation,
            destinationLocationName: destCity,
            sourceCoords: policy.currentCoords,
            destCoords: policy.destCityCoords,
          });

          hotspotRows.push(toHotel);
          currentTime = TimeConverter.toTimeString(toHotel.hotspot_end_time);
          TimelineLogger.log(`[TIMELINE] Adding hotel segment to ${destCity}. Distance: ${toHotel.hotspot_travelling_distance} KM`);

          const { row: stay } = await this.hotelBuilder.buildReturnToHotel(tx, {
            planId,
            routeId: route.itinerary_route_ID,
            order,
            startTime: currentTime,
            userId: createdByUserId,
          });
          hotspotRows.push(stay);
        }
      } else {
        const { row: ret } = await this.returnBuilder.buildReturnToDeparture(tx, {
          planId,
          routeId: route.itinerary_route_ID,
          order,
          startTime: currentTime,
          travelLocationType: 2,
          userId: createdByUserId,
          currentLocationName: policy.currentLocation,
          destCoords: policy.destCityCoords,
          sourceCoords: policy.currentCoords,
          destCityName: route.next_visiting_location,
        } as any);
        TimelineLogger.log(`[TIMELINE] Adding return segment to ${route.next_visiting_location}. Distance: ${ret.hotspot_travelling_distance} KM`);
        hotspotRows.push(ret);
      }
    }

    return { hotspotRows, parkingRows };
  }

  /**
   * Preview manual hotspot addition.
   * (Used by itineraries.service.ts)
   */
  async previewManualHotspotAdd(tx: Tx, planId: number, routeId: number, hotspotId: number): Promise<any> {
    // 1. Fetch existing hotspots for the plan
    const existingHotspots = await (tx as any).dvi_itinerary_route_hotspots.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0 },
    });

    // 2. Add the new hotspot as a "manual" one to the list
    // This simulates what happens if the user adds it.
    existingHotspots.push({
      itinerary_plan_ID: planId,
      itinerary_route_ID: routeId,
      hotspot_ID: hotspotId,
      hotspot_plan_own_way: 1, // Mark as manual
      status: 1,
      deleted: 0,
    });

    // 3. Run the full timeline builder with this augmented list
    return this.buildTimelineForPlan(tx, planId, existingHotspots);
  }
}
