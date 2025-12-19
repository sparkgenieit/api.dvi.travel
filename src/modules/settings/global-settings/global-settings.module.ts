// FILE: src/modules/global-settings/global-settings.module.ts

import { Module } from "@nestjs/common";
import { GlobalSettingsController } from "./global-settings.controller";
import { GlobalSettingsService } from "./global-settings.service";
import { PrismaService } from "../../../prisma.service";

@Module({
  controllers: [GlobalSettingsController],
  providers: [GlobalSettingsService, PrismaService],
  exports: [GlobalSettingsService],
})
export class GlobalSettingsModule {}
