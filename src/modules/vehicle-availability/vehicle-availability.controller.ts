// REPLACE-WHOLE-FILE: src/modules/vehicle-availability/vehicle-availability.controller.ts

import { Controller, Get, Query } from '@nestjs/common';
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
}
