// FILE: src/modules/settings/settings.service.ts

import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { UpdateGlobalSettingsDto } from './dto/global-settings.dto';
import { CreateCityDto, UpdateCityDto } from './dto/city.dto';
import { CreateHotelCategoryDto, UpdateHotelCategoryDto } from './dto/hotel-category.dto';

@Injectable()
export class SettingsService {
  constructor(private readonly prisma: PrismaService) {}

  // ==================== GLOBAL SETTINGS ====================

  async getGlobalSettings() {
    const settings = await this.prisma.dvi_global_settings.findFirst({
      where: { deleted: 0 },
    });

    if (!settings) {
      throw new NotFoundException('Global settings not found');
    }

    return settings;
  }

  async updateGlobalSettings(dto: UpdateGlobalSettingsDto) {
    const existing = await this.prisma.dvi_global_settings.findFirst({
      where: { deleted: 0 },
    });

    if (!existing) {
      throw new NotFoundException('Global settings not found');
    }

    const updated = await this.prisma.dvi_global_settings.update({
      where: { global_settings_ID: existing.global_settings_ID },
      data: {
        ...dto,
        updatedon: new Date(),
      },
    });

    return updated;
  }

  // ==================== CITIES ====================

  async getCities() {
    const cities = await this.prisma.dvi_city.findMany({
      where: { deleted: 0 },
      include: {
        dvi_states: {
          select: {
            state_id: true,
            state_name: true,
          },
        },
      },
      orderBy: { city_name: 'asc' },
    });

    return cities.map(city => ({
      city_id: city.city_id,
      city_name: city.city_name,
      state_id: city.state_id,
      state_name: city.dvi_states?.state_name || '',
      status: city.status,
    }));
  }

  async getCity(id: number) {
    const city = await this.prisma.dvi_city.findFirst({
      where: { city_id: id, deleted: 0 },
      include: {
        dvi_states: {
          select: {
            state_id: true,
            state_name: true,
          },
        },
      },
    });

    if (!city) {
      throw new NotFoundException(`City with ID ${id} not found`);
    }

    return {
      city_id: city.city_id,
      city_name: city.city_name,
      state_id: city.state_id,
      state_name: city.dvi_states?.state_name || '',
      status: city.status,
    };
  }

  async createCity(dto: CreateCityDto) {
    const city = await this.prisma.dvi_city.create({
      data: {
        city_name: dto.city_name,
        state_id: dto.state_id,
        status: dto.status ?? 1,
        deleted: 0,
        createdon: new Date(),
      },
    });

    return city;
  }

  async updateCity(id: number, dto: UpdateCityDto) {
    const existing = await this.prisma.dvi_city.findFirst({
      where: { city_id: id, deleted: 0 },
    });

    if (!existing) {
      throw new NotFoundException(`City with ID ${id} not found`);
    }

    const updated = await this.prisma.dvi_city.update({
      where: { city_id: id },
      data: {
        ...dto,
        updatedon: new Date(),
      },
    });

    return updated;
  }

  async deleteCity(id: number) {
    const existing = await this.prisma.dvi_city.findFirst({
      where: { city_id: id, deleted: 0 },
    });

    if (!existing) {
      throw new NotFoundException(`City with ID ${id} not found`);
    }

    await this.prisma.dvi_city.update({
      where: { city_id: id },
      data: { deleted: 1, updatedon: new Date() },
    });

    return { message: 'City deleted successfully' };
  }

  // ==================== HOTEL CATEGORIES ====================

  async getHotelCategories() {
    const categories = await this.prisma.dvi_hotel_category.findMany({
      where: { deleted: 0 },
      orderBy: { hotel_category_id: 'asc' },
    });

    return categories;
  }

  async getHotelCategory(id: number) {
    const category = await this.prisma.dvi_hotel_category.findFirst({
      where: { hotel_category_id: id, deleted: 0 },
    });

    if (!category) {
      throw new NotFoundException(`Hotel category with ID ${id} not found`);
    }

    return category;
  }

  async createHotelCategory(dto: CreateHotelCategoryDto) {
    const category = await this.prisma.dvi_hotel_category.create({
      data: {
        hotel_category_title: dto.category_title,
        hotel_category_code: String(dto.hotel_category),
        status: dto.status ?? 1,
        deleted: 0,
        createdon: new Date(),
      },
    });

    return category;
  }

  async updateHotelCategory(id: number, dto: UpdateHotelCategoryDto) {
    const existing = await this.prisma.dvi_hotel_category.findFirst({
      where: { hotel_category_id: id, deleted: 0 },
    });

    if (!existing) {
      throw new NotFoundException(`Hotel category with ID ${id} not found`);
    }

    const updated = await this.prisma.dvi_hotel_category.update({
      where: { hotel_category_id: id },
      data: {
        hotel_category_title: dto.category_title,
        hotel_category_code: dto.hotel_category ? String(dto.hotel_category) : undefined,
        updatedon: new Date(),
      },
    });

    return updated;
  }

  async deleteHotelCategory(id: number) {
    const existing = await this.prisma.dvi_hotel_category.findFirst({
      where: { hotel_category_id: id, deleted: 0 },
    });

    if (!existing) {
      throw new NotFoundException(`Hotel category with ID ${id} not found`);
    }

    await this.prisma.dvi_hotel_category.update({
      where: { hotel_category_id: id },
      data: { deleted: 1, updatedon: new Date() },
    });

    return { message: 'Hotel category deleted successfully' };
  }

  // ==================== STATES ====================

  async getStates() {
    const states = await this.prisma.dvi_states.findMany({
      where: { deleted: 0 },
      orderBy: { name: 'asc' },
      select: {
        id: true,
        name: true,
      },
    });

    return states;
  }
}
