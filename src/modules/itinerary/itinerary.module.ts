import { Module } from '@nestjs/common';
import { ItineraryController } from './itinerary.controller';
import { ItineraryService } from './itinerary.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [ItineraryController],
  providers: [ItineraryService, PrismaService],
})
export class ItineraryModule {}
