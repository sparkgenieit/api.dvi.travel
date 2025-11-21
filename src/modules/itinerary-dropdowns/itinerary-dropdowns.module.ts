import { Module } from '@nestjs/common';
import { ItineraryDropdownsController } from './itinerary-dropdowns.controller';
import { ItineraryDropdownsService } from './itinerary-dropdowns.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [ItineraryDropdownsController],
  providers: [ItineraryDropdownsService, PrismaService],
  exports: [ItineraryDropdownsService],
})
export class ItineraryDropdownsModule {}
