// FILE: src/modules/daily-moment-tracker/daily-moment-tracker.controller.ts

import {
  Controller,
  Get,
  Post,
  Body,
  Query,
  ParseIntPipe,
  DefaultValuePipe,
} from '@nestjs/common';
import { ApiBearerAuth, ApiOperation, ApiQuery, ApiTags } from '@nestjs/swagger';
import { DailyMomentTrackerService } from './daily-moment-tracker.service';
import {
  ListDailyMomentQueryDto,
  UpsertDailyMomentChargeDto,
  DailyMomentHotspotRowDto,
} from './dto/daily-moment-tracker.dto';

@ApiTags('daily-moment-tracker')
@ApiBearerAuth() // uses default bearer auth from main.ts
@Controller('daily-moment-tracker')
export class DailyMomentTrackerController {
  constructor(private readonly service: DailyMomentTrackerService) {}

  // List of Daily Moment (main grid)
  @Get()
  @ApiOperation({
    summary: 'List daily moments (main grid)',
    description:
      'Returns per-day rows for ongoing trips, filtered by date, location, and trip type.',
  })
  async getDailyMoments(@Query() query: ListDailyMomentQueryDto) {
    return this.service.listDailyMoments(query);
  }

  // Extra charges list for one day (car icon)
  @Get('charges')
  @ApiOperation({
    summary: 'List extra charges for a day',
    description:
      'Returns all extra charges linked to a specific itinerary plan and route (car icon popup).',
  })
  @ApiQuery({ name: 'itineraryPlanId', required: true, type: Number })
  @ApiQuery({ name: 'itineraryRouteId', required: false, type: Number })
  async getCharges(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe)
    itineraryPlanId: number,
    @Query('itineraryRouteId', new DefaultValuePipe(0), ParseIntPipe)
    itineraryRouteId: number,
  ) {
    return this.service.listCharges(itineraryPlanId, itineraryRouteId);
  }

  // Create / update extra charge
  @Post('charges')
  @ApiOperation({
    summary: 'Create or update an extra charge',
    description:
      'Upserts an extra charge row for a given itinerary plan, route, and charge type.',
  })
  async upsertCharge(@Body() dto: UpsertDailyMomentChargeDto) {
    return this.service.upsertCharge(dto);
  }

  // Driver rating list
  @Get('driver-ratings')
  @ApiOperation({
    summary: 'List driver ratings for itinerary',
  })
  @ApiQuery({ name: 'itineraryPlanId', required: true, type: Number })
  async getDriverRatings(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe)
    itineraryPlanId: number,
  ) {
    return this.service.listDriverRatings(itineraryPlanId);
  }

  // Guide rating list
  @Get('guide-ratings')
  @ApiOperation({
    summary: 'List guide ratings for itinerary',
  })
  @ApiQuery({ name: 'itineraryPlanId', required: true, type: Number })
  async getGuideRatings(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe)
    itineraryPlanId: number,
  ) {
    return this.service.listGuideRatings(itineraryPlanId);
  }

  // Day-wise hotspot cards (Visited / Not Visited)
  @Get('route-hotspots')
  @ApiOperation({
    summary: 'List hotspots for a route (Visited / Not Visited)',
    description:
      'Returns hotspot cards for a given itinerary plan + route, used in the Daily Moment car screen.',
  })
  @ApiQuery({ name: 'itineraryPlanId', required: true, type: Number })
  @ApiQuery({ name: 'itineraryRouteId', required: true, type: Number })
  async getRouteHotspots(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe)
    itineraryPlanId: number,
    @Query('itineraryRouteId', new DefaultValuePipe(0), ParseIntPipe)
    itineraryRouteId: number,
  ): Promise<DailyMomentHotspotRowDto[]> {
    return this.service.listRouteHotspots(itineraryPlanId, itineraryRouteId);
  }
}
