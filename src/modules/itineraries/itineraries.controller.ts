import { Body, Controller, Delete, Get, Param, Patch, Post, Query, UseGuards } from '@nestjs/common';
import { ApiOperation, ApiParam, ApiTags } from '@nestjs/swagger';
import { ItinerariesService } from './itineraries.service';
import { PaginationQueryDto } from '../../common/pagination';
import { CreateItineraryDto } from './dto/create-itinerary.dto';
import { UpdateItineraryDto } from './dto/update-itinerary.dto';
import { CreateDayDto } from './dto/create-day.dto';
import { CreateSegmentDto } from './dto/create-segment.dto';
import { RolesGuard } from '../../auth/roles.guard';
import { Roles } from '../../auth/roles.decorator';
import { SwaggerRole } from '../../auth/swagger-role.enum';

@ApiTags('itineraries')
@UseGuards(RolesGuard)
@Controller('itineraries')
export class ItinerariesController {
  constructor(private svc: ItinerariesService) {}

  @ApiOperation({ summary: 'List itineraries (search = title/code)' })
  @Roles(SwaggerRole.admin as any, SwaggerRole.agent as any, SwaggerRole.vendor as any)
  @Get()
  list(@Query() q: PaginationQueryDto) { return this.svc.list(q); }

  @ApiOperation({ summary: 'Create itinerary' })
  @Roles(SwaggerRole.admin as any, SwaggerRole.agent as any)
  @Post()
  create(@Body() dto: CreateItineraryDto) { return this.svc.create(dto); }

  @ApiOperation({ summary: 'Get itinerary with days & segments' })
  @ApiParam({ name: 'id' })
  @Roles(SwaggerRole.admin as any, SwaggerRole.agent as any, SwaggerRole.vendor as any)
  @Get(':id')
  get(@Param('id') id: string) { return this.svc.get(id); }

  @ApiOperation({ summary: 'Update itinerary' })
  @ApiParam({ name: 'id' })
  @Roles(SwaggerRole.admin as any, SwaggerRole.agent as any)
  @Patch(':id')
  update(@Param('id') id: string, @Body() dto: UpdateItineraryDto) { return this.svc.update(id, dto); }

  @ApiOperation({ summary: 'Delete itinerary' })
  @ApiParam({ name: 'id' })
  @Roles(SwaggerRole.admin as any)
  @Delete(':id')
  remove(@Param('id') id: string) { return this.svc.remove(id); }

  // Days
  @ApiOperation({ summary: 'Add day to itinerary' })
  @ApiParam({ name: 'id' })
  @Roles(SwaggerRole.admin as any, SwaggerRole.agent as any)
  @Post(':id/days')
  addDay(@Param('id') id: string, @Body() dto: CreateDayDto) { return this.svc.addDay(id, dto); }

  @ApiOperation({ summary: 'Delete day' })
  @ApiParam({ name: 'id' })
  @ApiParam({ name: 'dayId' })
  @Roles(SwaggerRole.admin as any)
  @Delete(':id/days/:dayId')
  delDay(@Param('id') id: string, @Param('dayId') dayId: string) { return this.svc.deleteDay(id, dayId); }

  // Segments
  @ApiOperation({ summary: 'Add segment to day' })
  @ApiParam({ name: 'dayId' })
  @Roles(SwaggerRole.admin as any, SwaggerRole.agent as any)
  @Post('days/:dayId/segments')
  addSeg(@Param('dayId') dayId: string, @Body() dto: CreateSegmentDto) { return this.svc.addSegment(dayId, dto); }

  @ApiOperation({ summary: 'Delete segment' })
  @ApiParam({ name: 'dayId' })
  @ApiParam({ name: 'segmentId' })
  @Roles(SwaggerRole.admin as any)
  @Delete('days/:dayId/segments/:segmentId')
  delSeg(@Param('dayId') dayId: string, @Param('segmentId') segmentId: string) { return this.svc.deleteSegment(dayId, segmentId); }
}
