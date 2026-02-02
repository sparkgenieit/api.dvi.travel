// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/hotspot-engine.service.ts

import { Injectable, Logger } from "@nestjs/common";
import { Prisma } from "@prisma/client";
import { PrismaService } from "../../../prisma.service";
import { TimelineBuilder } from "./helpers/timeline.builder";
import { TimelineEnricher } from "./helpers/timeline.enricher";

type Tx = Prisma.TransactionClient;

@Injectable()
export class HotspotEngineService {
  private readonly logger = new Logger(HotspotEngineService.name);

  // We don't use Nest DI for helpers so you don't have to touch the module.
  private readonly timelineBuilder = new TimelineBuilder();

  constructor(private readonly prisma: PrismaService) {}

  /**
   * Main entry called from ItinerariesService inside a prisma.$transaction.
   * Mirrors PHP: wipes old hotspot timeline & parking charges and rebuilds them.
   */
  async rebuildRouteHotspots(tx: Tx, planId: number, existingHotspotsFromService?: any[]): Promise<any> {
    // 1) Fetch ALL current hotspots (manual and auto) INCLUDING soft-deleted ones for reference
    // Note: We include deleted:1 records, but the timeline builder/selector will exclude them
    // This ensures deleted hotspots are NOT re-added during rebuild
    let existingHotspots = existingHotspotsFromService;
    
    if (!existingHotspots) {
      existingHotspots = await (tx as any).dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: planId,
          item_type: 4, // Only actual hotspot visits
        },
      });
    }

    // 2) Delete ONLY active hotspot details before rebuilding. 
    // We keep deleted: 1 records as "tombstones" to prevent auto-selection.
    await (tx as any).dvi_itinerary_route_hotspot_details.deleteMany({
      where: { 
        itinerary_plan_ID: planId,
        deleted: 0,
      },
    });

    await (tx as any).dvi_itinerary_route_hotspot_parking_charge.deleteMany({
      where: { itinerary_plan_ID: planId },
    });

    // 3) Build new timeline rows in memory
    // Pass existing hotspots (including deleted ones) to the builder
    // The builder/selector will filter out deleted:1 hotspots so they are NOT re-added
    const { hotspotRows, parkingRows } =
      await this.timelineBuilder.buildTimelineForPlan(tx, planId, existingHotspots);

    // Initialize tracking arrays
    const shiftedItems: any[] = [];
    const droppedItems: any[] = [];

    // 4) Insert hotspot details
    const dbHotspotRows = hotspotRows.map(row => {
      // Strip out UI-only fields before saving to DB
      const { 
        isConflict, 
        conflictReason, 
        isManual, 
        type, 
        text, 
        timeRange, 
        locationId,
        ...dbRow 
      } = row as any;
      
      return {
        ...dbRow,
        is_conflict: isConflict ? 1 : 0,
        conflict_reason: conflictReason || null,
      };
    });

    await (tx as any).dvi_itinerary_route_hotspot_details.createMany({
      data: dbHotspotRows,
    });

    // 5) Insert parking charge rows (if any)
    if (parkingRows.length) {
      await (tx as any).dvi_itinerary_route_hotspot_parking_charge.createMany({
        data: parkingRows,
      });
    }

    return { shiftedItems, droppedItems };
  }

  /**
   * Rebuild ONLY parking charges for a plan (called after vendor vehicles are created).
   * This is needed because parking charge builder requires vendor vehicle details.
   */
  async rebuildParkingCharges(planId: number, userId: number): Promise<void> {
    await this.prisma.$transaction(async (tx: Tx) => {
      // Delete existing parking charges
      await (tx as any).dvi_itinerary_route_hotspot_parking_charge.deleteMany({
        where: { itinerary_plan_ID: planId },
      });

      // Get all route hotspot details for this plan
      const hotspotDetails = await (tx as any).dvi_itinerary_route_hotspot_details.findMany({
        where: {
          itinerary_plan_ID: planId,
          item_type: 4, // Only actual hotspot visits (not travel segments)
          deleted: 0,
          status: 1,
        },
        orderBy: { route_hotspot_ID: 'asc' },
      });

      const parkingRows = [];
      for (const detail of hotspotDetails) {
        const parkingRowsForHotspot = await this.timelineBuilder.parkingBuilder.buildForHotspot(tx, {
          planId,
          routeId: detail.itinerary_route_ID,
          hotspotId: detail.hotspot_ID,
          userId,
        });
        if (parkingRowsForHotspot && parkingRowsForHotspot.length > 0) {
          parkingRows.push(...parkingRowsForHotspot);
        }
      }

      // Insert parking charges
      if (parkingRows.length) {
        await (tx as any).dvi_itinerary_route_hotspot_parking_charge.createMany({
          data: parkingRows,
        });
      }
    }, { timeout: 60000 });
  }

  /**
   * Preview adding a manual hotspot without saving to DB.
   */
  async previewManualHotspotAdd(
    tx: Tx,
    planId: number,
    routeId: number,
    hotspotId: number,
  ): Promise<any> {
    console.log(`\n🔍 PREVIEW-ADD: planId=${planId}, routeId=${routeId}, hotspotId=${hotspotId}`);
    
    // 1) Fetch the current route details
    const currentRoute = await (tx as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_route_ID: routeId },
      select: {
        itinerary_route_ID: true,
        location_name: true,
        next_visiting_location: true,
        direct_to_next_visiting_place: true,
        itinerary_route_date: true,
        route_start_time: true,
        route_end_time: true,
      },
    });

    if (!currentRoute) {
      throw new Error(`Route ${routeId} not found`);
    }
    
    console.log(`📍 Route ${routeId}: ${currentRoute.location_name} → ${currentRoute.next_visiting_location}`);
    console.log(`⏰ Route timing: ${currentRoute.route_start_time} to ${currentRoute.route_end_time}`);

    // 2) Check if there's a next route that connects to this one
    let nextRoute = null;
    let shouldIncludeNextDay = false;

    const currentDestination = (currentRoute.next_visiting_location || "").split("|")[0].trim();
    
    // Find next route by checking date order
    const allRoutes = await (tx as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId },
      orderBy: { itinerary_route_date: 'asc' },
      select: {
        itinerary_route_ID: true,
        location_name: true,
        next_visiting_location: true,
        direct_to_next_visiting_place: true,
        itinerary_route_date: true,
      },
    });

    const currentRouteIndex = allRoutes.findIndex((r: any) => r.itinerary_route_ID === routeId);
    
    if (currentRouteIndex !== -1 && currentRouteIndex + 1 < allRoutes.length) {
      const potentialNextRoute = allRoutes[currentRouteIndex + 1];
      const nextSource = (potentialNextRoute.location_name || "").split("|")[0].trim();
      const isDirectToNext = Number(potentialNextRoute.direct_to_next_visiting_place || 0) === 1;

      // Check if next route's source matches current route's destination AND it's not direct
      if (nextSource === currentDestination && !isDirectToNext) {
        nextRoute = potentialNextRoute;
        shouldIncludeNextDay = true;
      }
    }

    // 3) Fetch existing hotspots ONLY for current and optionally next route
    const routeIdsToInclude = [routeId];
    if (shouldIncludeNextDay && nextRoute) {
      routeIdsToInclude.push(nextRoute.itinerary_route_ID);
    }

    const existingHotspots = await (tx as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: { in: routeIdsToInclude },
        deleted: 0,
      },
    });

    // 4) Check if this hotspot is already assigned to this route to avoid duplicates
    // For manual hotspots (item_type=4), we should not create duplicates
    const existingManualHotspot = await (tx as any).dvi_itinerary_route_hotspot_details.findFirst({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        hotspot_ID: hotspotId,
        item_type: 4,
        deleted: 0,
      },
    });

    // If this hotspot is already assigned, reuse it instead of creating duplicate
    let newHotspotRecord = existingManualHotspot;
    if (!newHotspotRecord) {
      // 5) Temporarily insert the new hotspot into the database so the timeline builder can find it
      // IMPORTANT: Set placeholder times and order so timeline builder can process correctly
      // Timeline builder will recalculate these based on scheduling logic
      // hotspot_start_time and hotspot_end_time MUST NOT be null for DB constraints
      // hotspot_order MUST NOT be 0 (reserved for other item types)
      const placeholderTime = new Date('1970-01-01T00:00:00Z'); // Midnight UTC as default
      const placeholderOrder = 999; // High number to place at end (will be recalculated)
      
      newHotspotRecord = await (tx as any).dvi_itinerary_route_hotspot_details.create({
        data: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          hotspot_ID: hotspotId,
          hotspot_plan_own_way: 1, // MARK AS MANUAL
          item_type: 4,
          hotspot_order: placeholderOrder, // Placeholder, will be recalculated
          hotspot_start_time: placeholderTime, // Placeholder, will be recalculated
          hotspot_end_time: placeholderTime, // Placeholder, will be recalculated
          createdby: 1,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // 6) Build the timeline with the newly inserted hotspot available for processing
    const { hotspotRows } = await this.timelineBuilder.buildTimelineForPlan(tx, planId);

    // Initialize tracking arrays (not returned by buildTimelineForPlan)
    const shiftedItems: any[] = [];
    const droppedItems: any[] = [];

    // 7) Filter hotspotRows to only include current and next route
    const filteredRows = hotspotRows.filter(row => 
      routeIdsToInclude.includes(Number(row.itinerary_route_ID))
    );

    // 7) Enrich the filtered timeline with UI fields
    const enrichedTimeline = await TimelineEnricher.enrich(tx, planId, filteredRows);

    // 8) Find the newly added hotspot in the results
    const newHotspotRow = enrichedTimeline.find(
      (r) => Number(r.itinerary_route_ID) === Number(routeId) && 
             Number(r.hotspot_ID) === Number(hotspotId) && 
             r.item_type === 4
    );
    
    console.log(`📊 Preview result for hotspot ${hotspotId}:`, newHotspotRow ? 'FOUND' : 'NOT FOUND');
    if (newHotspotRow) {
      console.log(`   - Time: ${(newHotspotRow as any).timeRange}`);
      console.log(`   - Conflict: ${(newHotspotRow as any).isConflict || false}`);
      if ((newHotspotRow as any).isConflict) {
        console.log(`   - Reason: ${(newHotspotRow as any).conflictReason}`);
      }
    }

    // 9) Check for conflicts in OTHER hotspots (only in included routes)
    const otherConflicts = enrichedTimeline.filter(
      (r) => r.item_type === 4 && 
             (r as any).isConflict && 
             !(Number(r.itinerary_route_ID) === Number(routeId) && Number(r.hotspot_ID) === Number(hotspotId))
    );

    return {
      newHotspot: newHotspotRow,
      otherConflicts: otherConflicts.map(c => ({
        hotspotId: c.hotspot_ID,
        reason: (c as any).conflictReason
      })),
      shiftedItems,
      droppedItems,
      fullTimeline: enrichedTimeline,
      includedRouteIds: routeIdsToInclude, // Debug info
      nextRouteIncluded: shouldIncludeNextDay,
    };
  }
}