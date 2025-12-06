import { Body, Controller, Delete, Get, Param, Patch, Post, Query, Req } from '@nestjs/common';
import { ApiBearerAuth, ApiOperation, ApiQuery, ApiTags } from '@nestjs/swagger';
import { LocationsService } from './locations.service';
import { BulkTollPayloadDto, CreateLocationDto, ModifyLocationNameDto, UpdateLocationDto } from './dto/location.dto';

@ApiTags('Locations')
@ApiBearerAuth()
@Controller('locations')
export class LocationsController {
  constructor(private readonly svc: LocationsService) {}

  @Get()
  @ApiOperation({ summary: 'List locations with filters & pagination' })
  @ApiQuery({ name: 'source', required: false })
  @ApiQuery({ name: 'destination', required: false })
  @ApiQuery({ name: 'search', required: false })
  @ApiQuery({ name: 'page', required: false })
  @ApiQuery({ name: 'pageSize', required: false })
  list(@Query() q: any) {
    return this.svc.list(q);
  }

  @Get('dropdowns')
  @ApiOperation({ summary: 'Fetch dropdown options for Source/Destination' })
  dropdowns() {
    return this.svc.dropdowns();
  }

  @Post()
  @ApiOperation({ summary: 'Add Location (source→destination row)' })
  create(@Body() dto: CreateLocationDto) {
    return this.svc.create(dto);
  }

  @Get(':id')
  @ApiOperation({ summary: 'Get a single Location row' })
  get(@Param('id') id: string) {
    return this.svc.get(Number(id));
  }

  @Patch(':id')
  @ApiOperation({ summary: 'Edit Location row' })
  update(@Param('id') id: string, @Body() dto: UpdateLocationDto) {
    return this.svc.update(Number(id), dto);
  }

  @Patch(':id/modify-name')
  @ApiOperation({ summary: 'Quick rename Source/Destination location string' })
  modifyName(@Param('id') id: string, @Body() dto: ModifyLocationNameDto) {
    return this.svc.modifyName(Number(id), dto.scope, dto.new_name);
  }

  @Delete(':id')
  @ApiOperation({ summary: 'Soft delete location row' })
  softDelete(@Param('id') id: string) {
    return this.svc.softDelete(Number(id));
  }

  @Get(':id/tolls')
  @ApiOperation({ summary: 'Get toll charges grid for this location' })
  getTolls(@Param('id') id: string) {
    return this.svc.getTolls(Number(id));
  }

  @Post(':id/tolls')
  @ApiOperation({ summary: 'Save toll charges (replace all for this location)' })
  upsertTolls(@Param('id') id: string, @Body() body: BulkTollPayloadDto, @Req() req: any) {
    const userId = Number(req?.user?.id) || 0;
    return this.svc.upsertTolls(Number(id), body.items || [], userId);
  }
}
