// REPLACE-WHOLE-FILE: src/modules/vendors/vendors.module.ts

import { Module } from '@nestjs/common';
import { VendorsController } from './vendors.controller';
import { VendorsDropdownsController } from './vendors.controller';
import { VendorsService } from './vendors.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [VendorsController, VendorsDropdownsController ],
  providers: [VendorsService, PrismaService],
  exports: [VendorsService],
})
export class VendorsModule {}
