// REPLACE-WHOLE-FILE: src/modules/vehicle-availability/vehicle-availability.controller.ts

import { Body, Controller, Get, Post, Query } from '@nestjs/common';
import { ApiBearerAuth, ApiTags } from '@nestjs/swagger';
import { VehicleAvailabilityService } from './vehicle-availability.service';
import { VehicleAvailabilityQueryDto } from './dto/vehicle-availability-query.dto';
import { VehicleAvailabilityResponseDto } from './dto/vehicle-availability-response.dto';

@ApiTags('vehicle-availability')
@ApiBearerAuth()
@Controller('vehicle-availability')
export class VehicleAvailabilityController {
  constructor(private readonly service: VehicleAvailabilityService) {}

  @Get()
  async getChart(
    @Query() query: VehicleAvailabilityQueryDto,
  ): Promise<VehicleAvailabilityResponseDto> {
    return this.service.getVehicleAvailabilityChart(query);
  }

  // ✅ for filters / dropdowns
  @Get('vendors')
  async vendors() {
    return this.service.listVendors();
  }

  // ✅ for filters / dropdowns
  @Get('vehicle-types')
  async vehicleTypes() {
    return this.service.listVehicleTypes();
  }

  // optional if you want agents dropdown too
  @Get('agents')
  async agents() {
    return this.service.listAgents();
  }

  // ✅ dynamic locations dropdown (derived from confirmed itinerary route data)
  @Get('locations')
  async locations(@Query('q') q?: string) {
    return this.service.listLocations(q);
  }

  // ✅ Add New Vehicle modal → inserts into dvi_vehicle (and returns created id)
  @Post('vehicles')
  async createVehicle(@Body() dto: any) {
    return this.service.createVehicle(dto);
  }

  // ✅ Add New Driver modal → inserts into driver table (model differs across DBs)
  @Post('drivers')
  async createDriver(@Body() dto: any) {
    return this.service.createDriver(dto);
  }
}
