// FILE: src/modules/vehicle-types/vehicle-types.module.ts

import { Module } from "@nestjs/common";
import { VehicleTypesController } from "./vehicle-types.controller";
import { VehicleTypesService } from "./vehicle-types.service";
import { PrismaService } from "../../../prisma.service";

@Module({
  controllers: [VehicleTypesController],
  providers: [VehicleTypesService, PrismaService],
  exports: [VehicleTypesService],
})
export class VehicleTypesModule {}
