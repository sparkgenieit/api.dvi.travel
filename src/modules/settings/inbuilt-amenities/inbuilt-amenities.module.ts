// FILE: src/modules/inbuilt-amenities/inbuilt-amenities.module.ts

import { Module } from "@nestjs/common";
import { InbuiltAmenitiesController } from "./inbuilt-amenities.controller";
import { InbuiltAmenitiesService } from "./inbuilt-amenities.service";
import { PrismaService } from "../../../prisma.service";

@Module({
  controllers: [InbuiltAmenitiesController],
  providers: [InbuiltAmenitiesService, PrismaService],
  exports: [InbuiltAmenitiesService],
})
export class InbuiltAmenitiesModule {}
