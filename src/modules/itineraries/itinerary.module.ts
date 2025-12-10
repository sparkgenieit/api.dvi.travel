// REPLACE-WHOLE-FILE
// FILE: src/itineraries/itineraries.module.ts (adjust path if different)

import { Module } from '@nestjs/common';
import { ItinerariesController } from './itineraries.controller';
import { ItinerariesService } from './itineraries.service';
import { ItineraryDetailsService } from './itinerary-details.service';
import { ItineraryHotelDetailsService } from './itinerary-hotel-details.service';
import { HotelEngineService } from './engines/hotel-engine.service';

import { PrismaService } from '../../prisma.service';
import { ItineraryHotspotsEngine } from './engines/itinerary-hotspots.engine';
import { ItineraryVehiclesEngine } from './engines/itinerary-vehicles.engine';

// ✅ New engines + helpers
import { PlanEngineService } from './engines/plan-engine.service';
import { RouteEngineService } from './engines/route-engine.service';
import { HotspotEngineService } from './engines/hotspot-engine.service';
import { TravellersEngineService } from './engines/travellers-engine.service';
import { VehiclesEngineService } from './engines/vehicles-engine.service';

@Module({
  controllers: [ItinerariesController],
  providers: [
    // core services
    ItinerariesService,
    ItineraryDetailsService,
    ItineraryHotelDetailsService,
    PrismaService,

    // existing engines you already had
    HotelEngineService,
    ItineraryHotspotsEngine,
    ItineraryVehiclesEngine,

    // new quote + engines (PHP-parity + no-hardcoding)
    PlanEngineService,
    RouteEngineService,
    HotspotEngineService,
    TravellersEngineService,
    VehiclesEngineService,
  ],
})
export class ItinerariesModule {}
