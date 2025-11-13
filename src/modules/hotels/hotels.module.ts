
// src/modules/hotels/hotels.module.ts
import { Module } from '@nestjs/common';
import {
  HotelsController,
  LocationsController,
  RootStatesController,
  RootCitiesController,
  DviGeoController,
  PreviewAliasesController
} from './hotels.controller';
import { MetaController } from './meta.controller';
import { HotelsService } from './hotels.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [
    HotelsController,
    LocationsController,
    RootStatesController,
    RootCitiesController,
    DviGeoController,
    MetaController,
    PreviewAliasesController,
  ],
  providers: [HotelsService, PrismaService],
  exports: [HotelsService],
})
export class HotelsModule {}
