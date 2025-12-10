// REPLACE-WHOLE-FILE
// FILE: src/itineraries/itineraries.service.ts

import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../prisma.service";
import {
  CreateItineraryDto,
} from "./dto/create-itinerary.dto";
import { PlanEngineService } from "./engines/plan-engine.service";
import { RouteEngineService } from "./engines/route-engine.service";
import { HotspotEngineService } from "./engines/hotspot-engine.service";
import { HotelEngineService } from "./engines/hotel-engine.service";
import { TravellersEngineService } from "./engines/travellers-engine.service";
import { VehiclesEngineService } from "./engines/vehicles-engine.service";

@Injectable()
export class ItinerariesService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly planEngine: PlanEngineService,
    private readonly routeEngine: RouteEngineService,
    private readonly hotspotEngine: HotspotEngineService,
    private readonly hotelEngine: HotelEngineService,
    private readonly travellersEngine: TravellersEngineService,
    private readonly vehiclesEngine: VehiclesEngineService,
  ) {}

  async createPlan(dto: CreateItineraryDto) {
    const userId = 1;

    // Increase interactive transaction timeout; hotspot rebuild + hotel lookups can exceed default 5s
    return this.prisma.$transaction(async (tx) => {
      const planId = await this.planEngine.upsertPlanHeader(
        dto.plan,
        dto.travellers,
        tx,
        userId,
      );

      const routes = await this.routeEngine.rebuildRoutes(
        planId,
        dto.plan,
        dto.routes,
        tx,
        userId,
      );

      await this.planEngine.updateNoOfRoutes(planId, tx);

      await this.travellersEngine.rebuildTravellers(
        planId,
        dto.travellers,
        tx,
        userId,
      );

      await this.vehiclesEngine.rebuildPlanVehicles(
        planId,
        dto.vehicles,
        tx,
        userId,
      );

      if (
        dto.plan.itinerary_preference === 1 ||
        dto.plan.itinerary_preference === 3
      ) {
        await this.hotelEngine.rebuildPlanHotels(
          planId,
          tx,
          userId,
          
        );
      }

      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);

      const planRow = await (tx as any).dvi_itinerary_plan_details.findUnique({
        where: { itinerary_plan_ID: planId },
        select: { itinerary_quote_ID: true },
      });

      return {
        planId,
        quoteId: planRow?.itinerary_quote_ID,
        routeIds: routes.map((r: any) => r.itinerary_route_ID),
        message:
          "Plan created/updated with routes, vehicles, travellers, hotspots, and hotels.",
      };
    }, { timeout: 30000, maxWait: 5000 });
  }
}
