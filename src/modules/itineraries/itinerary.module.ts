import { Module } from '@nestjs/common';
import { ItinerariesController } from './itineraries.controller';
import { ItinerariesService } from './itineraries.service';
import { ItineraryDetailsService } from './itinerary-details.service';
import { ItineraryHotelDetailsService } from './itinerary-hotel-details.service';

import { PrismaService } from '../../prisma.service';
import { ItineraryHotspotsEngine } from './engines/itinerary-hotspots.engine';
import { ItineraryVehiclesEngine } from './engines/itinerary-vehicles.engine';

@Module({
  controllers: [ItinerariesController],
  providers: [
    ItinerariesService,
    ItineraryDetailsService,
    ItineraryHotelDetailsService,
    PrismaService,
    ItineraryHotspotsEngine,
    ItineraryVehiclesEngine,
  ],
})
export class ItinerariesModule {}
