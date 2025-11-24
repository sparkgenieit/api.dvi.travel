import { Module } from '@nestjs/common';
import { ItineraryViaRoutesController } from './itinerary-via-routes.controller';
import { ItineraryViaRoutesService } from './itinerary-via-routes.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [ItineraryViaRoutesController],
  providers: [ItineraryViaRoutesService, PrismaService],
  exports: [ItineraryViaRoutesService],
})
export class ItineraryViaRoutesModule {}