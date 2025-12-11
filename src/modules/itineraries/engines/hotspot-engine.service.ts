// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/hotspot-engine.service.ts

import { Injectable, Logger } from "@nestjs/common";
import { Prisma } from "@prisma/client";
import { PrismaService } from "../../../prisma/prisma.service";
import { TimelineBuilder } from "./helpers/timeline.builder";

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
  async rebuildRouteHotspots(tx: Tx, planId: number): Promise<void> {
    this.logger.log(`Rebuilding hotspot timeline for itinerary_plan_ID=${planId}`);

    // 1) Delete old hotspot details and parking charges before rebuilding
    const deletedHotspots = await (tx as any).dvi_itinerary_route_hotspot_details.deleteMany({
      where: { itinerary_plan_ID: planId },
    });

    const deletedParking = await (tx as any).dvi_itinerary_route_hotspot_parking_charge.deleteMany({
      where: { itinerary_plan_ID: planId },
    });

    this.logger.log(
      `Cleared ${deletedHotspots.count} existing hotspot rows and ${deletedParking.count} parking rows for plan ${planId}`,
    );

    // 2) Build new timeline rows in memory
    const { hotspotRows, parkingRows } =
      await this.timelineBuilder.buildTimelineForPlan(tx, planId);

    this.logger.log(
      `Built ${hotspotRows.length} hotspot rows and ${parkingRows.length} parking rows for plan ${planId}`,
    );

    if (!hotspotRows.length) {
      this.logger.warn(
        `No hotspot timeline segments built for itinerary_plan_ID=${planId}`,
      );
      return;
    }

    // Log first few rows for debugging
    this.logger.log(
      `Sample hotspot rows: ${JSON.stringify(hotspotRows.slice(0, 3).map(r => ({ 
        item_type: r.item_type, 
        hotspot_ID: r.hotspot_ID, 
        order: r.hotspot_order 
      })))}`,
    );

    // 3) Insert hotspot details
    await (tx as any).dvi_itinerary_route_hotspot_details.createMany({
      data: hotspotRows,
    });

    // 4) Insert parking charge rows (if any)
    if (parkingRows.length) {
      await (tx as any).dvi_itinerary_route_hotspot_parking_charge.createMany({
        data: parkingRows,
      });
    }

    this.logger.log(
      `Hotspot timeline rebuilt for plan=${planId} (hotspots=${hotspotRows.length}, parking=${parkingRows.length})`,
    );
  }

  /**
   * Rebuild ONLY parking charges for a plan (called after vendor vehicles are created).
   * This is needed because parking charge builder requires vendor vehicle details.
   */
  async rebuildParkingCharges(planId: number, userId: number): Promise<void> {
    this.logger.log(`Rebuilding parking charges for itinerary_plan_ID=${planId}`);

    await this.prisma.$transaction(async (tx) => {
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
        orderBy: { itinerary_route_hotspot_details_ID: 'asc' },
      });

      const parkingRows = [];
      for (const detail of hotspotDetails) {
        const parking = await this.timelineBuilder.parkingBuilder.buildForHotspot(tx, {
          planId,
          routeId: detail.itinerary_route_ID,
          hotspotId: detail.hotspot_ID,
          userId,
        });

        if (parking) {
          parkingRows.push(parking);
        }
      }

      // Insert parking charges
      if (parkingRows.length) {
        await (tx as any).dvi_itinerary_route_hotspot_parking_charge.createMany({
          data: parkingRows,
        });
      }

      this.logger.log(`Rebuilt ${parkingRows.length} parking charges for plan=${planId}`);
    }, { timeout: 30000 });
  }
