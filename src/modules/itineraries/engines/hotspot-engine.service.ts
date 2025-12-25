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
    // 1) Fetch ALL current hotspots (manual and auto) including soft-deleted ones
    // This allows the engine to respect previous deletions during rebuild
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
    // We pass the existing hotspots so the builder can try to keep them even if they conflict
    const { hotspotRows, parkingRows, shiftedItems, droppedItems } =
      await this.timelineBuilder.buildTimelineForPlan(tx, planId, existingHotspots);

    if (!hotspotRows.length) {
      return { shiftedItems: [], droppedItems: [] };
    }

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
    // 1) Fetch all existing hotspots for this plan
    const existingHotspots = await (tx as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: planId,
        item_type: 4,
        deleted: 0,
      },
    });

    // 2) Add the new one to the list for the builder
    // We mark it as manual so the builder prioritizes it and flags conflicts
    existingHotspots.push({
      itinerary_plan_ID: planId,
      itinerary_route_ID: routeId,
      hotspot_ID: hotspotId,
      hotspot_plan_own_way: 1, // MARK AS MANUAL
    });

    // 3) Build the timeline in memory
    const { hotspotRows, shiftedItems, droppedItems } = await this.timelineBuilder.buildTimelineForPlan(tx, planId, existingHotspots);

    // 4) Enrich the timeline with UI fields (text, timeRange, type)
    const enrichedTimeline = await TimelineEnricher.enrich(tx, planId, hotspotRows);

    // 5) Find the newly added hotspot in the results to show its timing/conflicts
    const newHotspotRow = enrichedTimeline.find(
      (r) => Number(r.itinerary_route_ID) === Number(routeId) && 
             Number(r.hotspot_ID) === Number(hotspotId) && 
             r.item_type === 4
    );

    // 6) Also check if it caused conflicts in OTHER hotspots
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
    };
  }
}