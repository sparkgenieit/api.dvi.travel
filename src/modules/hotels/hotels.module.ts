
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
import { HotelConfirmController } from './controllers/hotel-confirm.controller';
import { HotelMasterSyncController } from './controllers/hotel-master-sync.controller';
import { HotelsService } from './hotels.service';
import { TBOHotelProvider } from './providers/tbo-hotel.provider';
import { ResAvenueHotelProvider } from './providers/resavenue-hotel.provider';
import { HotelSearchService } from './services/hotel-search.service';
import { HotelConfirmService } from './services/hotel-confirm.service';
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
    HotelConfirmController,
    HotelMasterSyncController,
  ],
  providers: [HotelsService, TBOHotelProvider, ResAvenueHotelProvider, HotelSearchService, HotelConfirmService, TboHotelMasterSyncService, TboSoapSyncService, TboApiSyncService],
  exports: [HotelsService, HotelSearchService, HotelConfirmService, TBOHotelProvider, ResAvenueHotelProvider, TboSoapSyncService, TboApiSyncService],
})
export class HotelsModule {}
