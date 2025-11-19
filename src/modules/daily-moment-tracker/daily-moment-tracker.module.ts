// FILE: src/modules/daily-moment-tracker/daily-moment-tracker.module.ts

import { Module } from '@nestjs/common';
import { DailyMomentTrackerService } from './daily-moment-tracker.service';
import { DailyMomentTrackerController } from './daily-moment-tracker.controller';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [DailyMomentTrackerController],
  providers: [DailyMomentTrackerService, PrismaService],
  exports: [DailyMomentTrackerService],
})
export class DailyMomentTrackerModule {}
