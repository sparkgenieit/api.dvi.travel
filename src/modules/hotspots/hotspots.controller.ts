// FILE: src/modules/hotspots/hotspots.controller.ts

import { Controller, Get, NotFoundException, Param, ParseIntPipe, Query } from '@nestjs/common';
import { HotspotListQueryDto } from './dto/hotspot-list.query.dto';
import { HotspotsService } from './hotspots.service';
import { HotspotListResponseDto, HotspotDto } from './dto/hotspot-list.response.dto';

@Controller('hotspots')
export class HotspotsController {
  constructor(private readonly svc: HotspotsService) {}

  /**
   * GET /hotspots
   * Mirrors the PHP “List of Hotspot” page:
   * - free-text search (q)
   * - optional filters (city/state/country/status)
   * - optional includeImages
   * - pagination & sort
   */
  @Get()
  async list(@Query() q: HotspotListQueryDto): Promise<HotspotListResponseDto> {
    return this.svc.list(q);
  }

  /**
   * GET /hotspots/:id
   * Basic details (with first image if includeImages=true is passed as query).
   */
  @Get(':id')
  async getOne(
    @Param('id', ParseIntPipe) id: number,
    @Query('includeImages') includeImages?: string,
  ): Promise<HotspotDto> {
    const row = await this.svc.getOne(id, includeImages === 'true');
    if (!row) throw new NotFoundException('Hotspot not found');
    return row;
  }
}