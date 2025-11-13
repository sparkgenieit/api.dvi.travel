import { Module } from '@nestjs/common';
import { ItinerariesController } from './itineraries.controller';
import { ItinerariesService } from './itineraries.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [ItinerariesController],
  providers: [ItinerariesService, PrismaService],
  exports: [ItinerariesService],
})
export class ItinerariesModule {}
