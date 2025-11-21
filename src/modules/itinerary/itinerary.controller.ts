// FILE: src/modules/itinerary/itinerary.controller.ts

import {
  Body,
  Controller,
  Get,
  Param,
  Post,
  Put,
} from '@nestjs/common';
import { ApiBearerAuth, ApiTags } from '@nestjs/swagger';
import { ItineraryService } from './itinerary.service';
import { CreateItineraryDto } from './dto/create-itinerary.dto';
import { UpdateItineraryDto } from './dto/update-itinerary.dto';

@ApiTags('itineraries')
@ApiBearerAuth() // uses the default bearer auth defined in main.ts
@Controller('itineraries')
export class ItineraryController {
  constructor(private readonly service: ItineraryService) {}

  @Post()
  async create(@Body() dto: CreateItineraryDto) {
    return this.service.create(dto);
  }

  @Get(':id')
  async findOne(@Param('id') id: string) {
    return this.service.findOne(Number(id));
  }

  @Put(':id')
  async update(
    @Param('id') id: string,
    @Body() dto: UpdateItineraryDto,
  ) {
    return this.service.update(Number(id), dto);
  }
}
