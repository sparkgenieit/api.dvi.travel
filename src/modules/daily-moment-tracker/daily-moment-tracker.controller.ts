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
import { DailyMomentTrackerService } from './daily-moment-tracker.service';
import {
  ListDailyMomentQueryDto,
  UpsertDailyMomentChargeDto,
} from './dto/daily-moment-tracker.dto';

@Controller('daily-moment-tracker')
export class DailyMomentTrackerController {
  constructor(private readonly service: DailyMomentTrackerService) {}

  // List of Daily Moment (main grid)
  @Get()
  async getDailyMoments(@Query() query: ListDailyMomentQueryDto) {
    return this.service.listDailyMoments(query);
  }

  // Extra charges list for one day (car icon)
  @Get('charges')
  async getCharges(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe) itineraryPlanId: number,
    @Query('itineraryRouteId', new DefaultValuePipe(0), ParseIntPipe) itineraryRouteId: number,
  ) {
    return this.service.listCharges(itineraryPlanId, itineraryRouteId);
  }

  // Create / update extra charge
  @Post('charges')
  async upsertCharge(@Body() dto: UpsertDailyMomentChargeDto) {
    return this.service.upsertCharge(dto);
  }

  // Driver rating list
  @Get('driver-ratings')
  async getDriverRatings(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe) itineraryPlanId: number,
  ) {
    return this.service.listDriverRatings(itineraryPlanId);
  }

  // Guide rating list
  @Get('guide-ratings')
  async getGuideRatings(
    @Query('itineraryPlanId', new DefaultValuePipe(0), ParseIntPipe) itineraryPlanId: number,
  ) {
    return this.service.listGuideRatings(itineraryPlanId);
  }
}
