// REPLACE-WHOLE-FILE
// FILE: src/itineraries/engines/vehicles-engine.service.ts

import { Injectable } from "@nestjs/common";
import { Prisma } from "@prisma/client";
import { CreateVehicleDto } from "../dto/create-itinerary.dto";

type Tx = Prisma.TransactionClient;

@Injectable()
export class VehiclesEngineService {
  async rebuildPlanVehicles(
    planId: number,
    vehicles: CreateVehicleDto[],
    tx: Tx,
    userId: number,
  ) {
    await (tx as any).dvi_itinerary_plan_vehicle_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });

    const rows = (vehicles || []).map((v) => ({
      itinerary_plan_id: planId,
      vehicle_type_id: v.vehicle_type_id,
      vehicle_count: v.vehicle_count,
      createdby: userId,
      createdon: new Date(),
      updatedon: null,
      status: 1,
      deleted: 0,
    }));

    if (rows.length) {
      await (tx as any).dvi_itinerary_plan_vehicle_details.createMany({
        data: rows,
      });
    }
  }
}
