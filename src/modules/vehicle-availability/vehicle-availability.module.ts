//src/modules/vehicle-availability/vehicle-availability.module.ts

import { Module } from "@nestjs/common";
import { VehicleAvailabilityController } from "./vehicle-availability.controller";
import { VehicleAvailabilityService } from "./vehicle-availability.service";
import { PrismaService } from "../../prisma.service";

@Module({
  controllers: [VehicleAvailabilityController],
  providers: [VehicleAvailabilityService, PrismaService],
  exports: [VehicleAvailabilityService],
})
export class VehicleAvailabilityModule {}
