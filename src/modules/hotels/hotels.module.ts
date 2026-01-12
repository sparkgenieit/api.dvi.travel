
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
import { HotelSearchController } from './controllers/hotel-search.controller';
import { HotelMasterSyncController } from './controllers/hotel-master-sync.controller';
import { HotelsService } from './hotels.service';
import { TBOHotelProvider } from './providers/tbo-hotel.provider';
import { HotelSearchService } from './services/hotel-search.service';
import { TboHotelMasterSyncService } from './services/tbo-hotel-master-sync.service';
import { TboSoapSyncService } from './services/tbo-soap-sync.service';
import { TboApiSyncService } from './services/tbo-api-sync.service';

@Module({
  imports: [],
  controllers: [
    HotelsController,
    LocationsController,
    RootStatesController,
    RootCitiesController,
    DviGeoController,
    MetaController,
    PreviewAliasesController,
    HotelSearchController,
    HotelMasterSyncController,
  ],
  providers: [HotelsService, TBOHotelProvider, HotelSearchService, TboHotelMasterSyncService, TboSoapSyncService, TboApiSyncService],
  exports: [HotelsService, HotelSearchService, TBOHotelProvider, TboSoapSyncService, TboApiSyncService],
})
export class HotelsModule {}
