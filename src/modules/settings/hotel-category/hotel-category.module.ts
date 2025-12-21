// FILE: src/modules/hotel-category/hotel-category.module.ts

import { Module } from '@nestjs/common';
import { HotelCategoryService } from './hotel-category.service';
import { HotelCategoryController } from './hotel-category.controller';
import { PrismaService } from '../../../prisma.service';

@Module({
  controllers: [HotelCategoryController],
  providers: [HotelCategoryService, PrismaService],
  exports: [HotelCategoryService],
})
export class HotelCategoryModule {}
