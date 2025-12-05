// FILE: src/modules/hotspots/hotspots.module.ts

import { Module } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { HotspotsController } from './hotspots.controller';
import { HotspotsService } from './hotspots.service';

@Module({
  controllers: [HotspotsController],
  providers: [PrismaService, HotspotsService],
  exports: [HotspotsService],
})
export class HotspotsModule {}
