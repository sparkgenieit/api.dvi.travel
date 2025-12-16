// FILE: src/modules/settings/settings.controller.ts

import {
  Controller,
  Get,
  Post,
  Put,
  Delete,
  Body,
  Param,
  ParseIntPipe,
} from '@nestjs/common';
import { ApiTags, ApiOperation } from '@nestjs/swagger';
import { SettingsService } from './settings.service';
import { UpdateGlobalSettingsDto } from './dto/global-settings.dto';
import { CreateCityDto, UpdateCityDto } from './dto/city.dto';
import { CreateHotelCategoryDto, UpdateHotelCategoryDto } from './dto/hotel-category.dto';

@ApiTags('Settings')
@Controller('settings')
export class SettingsController {
  constructor(private readonly settingsService: SettingsService) {}

  // ==================== GLOBAL SETTINGS ====================
  
  @Get('global')
  @ApiOperation({ summary: 'Get global settings' })
  getGlobalSettings() {
    return this.settingsService.getGlobalSettings();
  }

  @Put('global')
  @ApiOperation({ summary: 'Update global settings' })
  updateGlobalSettings(@Body() dto: UpdateGlobalSettingsDto) {
    return this.settingsService.updateGlobalSettings(dto);
  }

  // ==================== CITIES ====================
  
  @Get('cities')
  @ApiOperation({ summary: 'Get all cities' })
  getCities() {
    return this.settingsService.getCities();
  }

  @Get('cities/:id')
  @ApiOperation({ summary: 'Get city by ID' })
  getCity(@Param('id', ParseIntPipe) id: number) {
    return this.settingsService.getCity(id);
  }

  @Post('cities')
  @ApiOperation({ summary: 'Create city' })
  createCity(@Body() dto: CreateCityDto) {
    return this.settingsService.createCity(dto);
  }

  @Put('cities/:id')
  @ApiOperation({ summary: 'Update city' })
  updateCity(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: UpdateCityDto,
  ) {
    return this.settingsService.updateCity(id, dto);
  }

  @Delete('cities/:id')
  @ApiOperation({ summary: 'Delete city' })
  deleteCity(@Param('id', ParseIntPipe) id: number) {
    return this.settingsService.deleteCity(id);
  }

  // ==================== HOTEL CATEGORIES ====================
  
  @Get('hotel-categories')
  @ApiOperation({ summary: 'Get all hotel categories' })
  getHotelCategories() {
    return this.settingsService.getHotelCategories();
  }

  @Get('hotel-categories/:id')
  @ApiOperation({ summary: 'Get hotel category by ID' })
  getHotelCategory(@Param('id', ParseIntPipe) id: number) {
    return this.settingsService.getHotelCategory(id);
  }

  @Post('hotel-categories')
  @ApiOperation({ summary: 'Create hotel category' })
  createHotelCategory(@Body() dto: CreateHotelCategoryDto) {
    return this.settingsService.createHotelCategory(dto);
  }

  @Put('hotel-categories/:id')
  @ApiOperation({ summary: 'Update hotel category' })
  updateHotelCategory(
    @Param('id', ParseIntPipe) id: number,
    @Body() dto: UpdateHotelCategoryDto,
  ) {
    return this.settingsService.updateHotelCategory(id, dto);
  }

  @Delete('hotel-categories/:id')
  @ApiOperation({ summary: 'Delete hotel category' })
  deleteHotelCategory(@Param('id', ParseIntPipe) id: number) {
    return this.settingsService.deleteHotelCategory(id);
  }

  // ==================== STATES (for dropdown) ====================
  
  @Get('states')
  @ApiOperation({ summary: 'Get all states' })
  getStates() {
    return this.settingsService.getStates();
  }
}
