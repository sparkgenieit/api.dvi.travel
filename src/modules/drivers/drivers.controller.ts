// NEW FILE: src/modules/drivers/drivers.controller.ts
import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  Patch,
  Post,
  Put,
  Query,
  Req,
} from '@nestjs/common';
import { DriversService } from './drivers.service';
import { UpdateDriverStatusDto } from './dto/update-driver-status.dto';
import { CreateDriverDto } from './dto/create-driver.dto';
import { UpdateDriverDto } from './dto/update-driver.dto';

@Controller('drivers')
export class DriversController {
  constructor(private readonly driversService: DriversService) {}

  @Post()
  async create(@Body() body: CreateDriverDto) {
    return this.driversService.create(body);
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    return this.driversService.findOne(Number(id));
  }

  @Put(':id')
  async update(@Param('id') id: string, @Body() body: UpdateDriverDto) {
    return this.driversService.update(Number(id), body);
  }

  /**
   * GET /drivers
   * - Returns list of drivers for listing page
   * - If req.user.vendor_id exists, it will filter by that vendor
   * - Otherwise optional ?vendorId= can be used
   */
  @Get()
  async findAll(@Req() req: any, @Query('vendorId') vendorId?: string) {
    const userVendorId =
      req && req.user && (req.user.vendor_id || req.user.vendorId);

    let resolvedVendorId: number | undefined;
    if (typeof userVendorId === 'number') {
      resolvedVendorId = userVendorId;
    } else if (typeof userVendorId === 'string') {
      const n = Number(userVendorId);
      resolvedVendorId = Number.isNaN(n) ? undefined : n;
    } else if (vendorId) {
      const n = Number(vendorId);
      resolvedVendorId = Number.isNaN(n) ? undefined : n;
    }

    return this.driversService.findAll(resolvedVendorId);
  }

  /**
   * PATCH /drivers/:id/status
   * - Toggle active/inactive from the UI switch
   */
  @Patch(':id/status')
  async updateStatus(
    @Param('id') id: string,
    @Body() body: UpdateDriverStatusDto,
  ) {
    await this.driversService.updateStatus(Number(id), body.status);
    return { success: true };
  }

  /**
   * DELETE /drivers/:id
   * - Delete driver (used by trash icon)
   */
  @Delete(':id')
  async remove(@Param('id') id: string) {
    await this.driversService.remove(Number(id));
    return { success: true };
  }
}
