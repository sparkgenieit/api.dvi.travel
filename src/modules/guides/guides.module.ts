// FILE: src/modules/guides/guides.module.ts
import { Module } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { GuidesService } from './guideservice';
import { GuidesController } from './guidecontroller';

@Module({
  controllers: [GuidesController],
  providers: [GuidesService, PrismaService],
  exports: [GuidesService],
})
export class GuidesModule {}
