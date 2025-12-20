// FILE: src/modules/itineraries/engines/helpers/return-segment.builder.ts

import { Prisma } from "@prisma/client";
import { HotspotDetailRow } from "./types";
import { TravelSegmentBuilder } from "./travel-segment.builder";

type Tx = Prisma.TransactionClient;

export class ReturnSegmentBuilder {
  private readonly travelBuilder = new TravelSegmentBuilder();

  /**
   * item_type = 7 → return to departure location.
   * Uses plan.departure_location as destination.
   */
  async buildReturnToDeparture(
    tx: Tx,
    opts: {
      planId: number;
      routeId: number;
      order: number;
      startTime: string;
      travelLocationType: 1 | 2;
      userId: number;
      currentLocationName: string;
      transportMode?: 'road' | 'train' | 'flight';
      destCoords?: { lat: number; lon: number };
    },
  ): Promise<{ row: HotspotDetailRow; nextTime: string }> {
    // TODO: adjust field names if departure_location differs.
    const plan = await (tx as any).dvi_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: opts.planId, deleted: 0 },
    });

    const destinationLocationName = plan?.departure_location as string;

    return this.travelBuilder.buildTravelSegment(tx, {
      planId: opts.planId,
      routeId: opts.routeId,
      order: opts.order,
      item_type: 7,
      startTime: opts.startTime,
      travelLocationType: opts.travelLocationType,
      userId: opts.userId,
      sourceLocationName: opts.currentLocationName,
      destinationLocationName,
      destCoords: opts.destCoords,
    });
  }
}
