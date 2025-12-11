// FILE: src/modules/staff/staff.controller.ts
import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Put,
  Query,
} from '@nestjs/common';
import { StaffService } from './staff.service';

@Controller('staff')
export class StaffController {
  constructor(private readonly staffService: StaffService) {}

  // LIST (kept first)
  @Get()
  async list(
    @Query('agentId') agentId?: string,
    @Query('search') search?: string,
    @Query('status') status?: string,
    @Query('page') page = '1',
    @Query('pageSize') pageSize = '50',
  ) {
    return this.staffService.list({
      agentId: agentId ? Number(agentId) : undefined,
      search: search || undefined,
      status: status !== undefined ? Number(status) : undefined,
      page: Number(page) || 1,
      pageSize: Number(pageSize) || 50,
    });
  }

  // ✅ STATIC ROUTE MUST COME BEFORE :id
  @Get('roles')
  async listRoleOptions() {
    return this.staffService.listRoleOptions();
  }

  // GET ONE
  @Get(':id')
  async getOne(@Param('id', new ParseIntPipe()) id: number) {
    return this.staffService.getOne(id);
  }

  // PREVIEW (if you have it; still after :id static “roles”)
  @Get(':id/preview')
  async preview(@Param('id', new ParseIntPipe()) id: number) {
    return this.staffService.preview(id);
  }

  // CREATE
  @Post()
  async create(@Body() body: any) {
    return this.staffService.create({
      agentId: Number(body.agentId ?? 0),
      staffName: body.staffName,
      staffMobile: body.staffMobile,
      staffEmail: body.staffEmail,
      roleId: Number(body.roleId ?? 0),
      status: Number(body.status ?? 1),
      loginEmail: body.loginEmail,
      password: body.password,
      createdBy: Number(body.createdBy ?? 1),
    });
  }

  // UPDATE
  @Put(':id')
  async update(@Param('id', new ParseIntPipe()) id: number, @Body() body: any) {
    return this.staffService.update(id, {
      agentId: body.agentId !== undefined ? Number(body.agentId) : undefined,
      staffName: body.staffName,
      staffMobile: body.staffMobile,
      staffEmail: body.staffEmail,
      roleId: body.roleId !== undefined ? Number(body.roleId) : undefined,
      status: body.status !== undefined ? Number(body.status) : undefined,
      loginEmail: body.loginEmail,
      password: body.password,
      updatedBy: body.updatedBy !== undefined ? Number(body.updatedBy) : undefined,
    });
  }

  // DELETE (soft)
  @Delete(':id')
  async softDelete(@Param('id', new ParseIntPipe()) id: number) {
    return this.staffService.softDelete(id);
  }
}