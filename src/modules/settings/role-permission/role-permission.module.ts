// FILE: src/modules/role-permission/role-permission.module.ts

import { Module } from '@nestjs/common';
import { RolePermissionController } from './role-permission.controller';
import { RolePermissionService } from './role-permission.service';
import { PrismaService } from '../../../prisma.service';

@Module({
  controllers: [RolePermissionController],
  providers: [RolePermissionService, PrismaService],
  exports: [RolePermissionService],
})
export class RolePermissionModule {}
