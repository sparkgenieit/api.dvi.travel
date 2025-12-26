import { Module } from '@nestjs/common';
import { HotspotDistanceCacheController } from './hotspot-distance-cache.controller';
import { HotspotDistanceCacheService } from './hotspot-distance-cache.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [HotspotDistanceCacheController],
  providers: [HotspotDistanceCacheService, PrismaService],
  exports: [HotspotDistanceCacheService],
})
export class HotspotDistanceCacheModule {}
