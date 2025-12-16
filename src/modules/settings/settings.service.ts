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

    if (!settings) throw new NotFoundException('Global settings not found');
    return settings;
  }

  async updateGlobalSettings(dto: UpdateGlobalSettingsDto) {
    const existing = await this.prisma.dvi_global_settings.findFirst({
      where: { deleted: 0 },
    });

    if (!existing) throw new NotFoundException('Global settings not found');

    return this.prisma.dvi_global_settings.update({
      where: { global_settings_ID: existing.global_settings_ID },
      data: {
        ...dto,
        updatedon: new Date(),
      },
    });
  }

  // ==================== CITIES ====================

  async getCities() {
    const cities = await this.prisma.dvi_cities.findMany({
      where: { deleted: 0 },
      select: {
        id: true,
        name: true,
        state_id: true,
        status: true,
      },
      orderBy: { name: 'asc' },
    });

    // dvi_cities has state_id but schema has NO Prisma relation, so we batch-fetch states
    const stateIds = Array.from(new Set(cities.map(c => c.state_id).filter(Boolean)));
    const states = await this.prisma.dvi_states.findMany({
      where: { deleted: 0, id: { in: stateIds } },
      select: { id: true, name: true },
    });
    const stateMap = new Map(states.map(s => [s.id, s.name]));

    return cities.map(city => ({
      city_id: city.id,
      city_name: city.name,
      state_id: city.state_id,
      state_name: stateMap.get(city.state_id) || '',
      status: city.status,
    }));
  }

  async getCity(id: number) {
    const city = await this.prisma.dvi_cities.findFirst({
      where: { id, deleted: 0 },
      select: {
        id: true,
        name: true,
        state_id: true,
        status: true,
      },
    });

    if (!city) throw new NotFoundException(`City with ID ${id} not found`);

    const state = await this.prisma.dvi_states.findFirst({
      where: { id: city.state_id, deleted: 0 },
      select: { id: true, name: true },
    });

    return {
      city_id: city.id,
      city_name: city.name,
      state_id: city.state_id,
      state_name: state?.name || '',
      status: city.status,
    };
  }

  async createCity(dto: CreateCityDto) {
    return this.prisma.dvi_cities.create({
      data: {
        name: dto.city_name,
        state_id: dto.state_id,
        status: dto.status ?? 1,
        deleted: 0,
        createdon: new Date(),
      },
    });
  }

  async updateCity(id: number, dto: UpdateCityDto) {
    const existing = await this.prisma.dvi_cities.findFirst({
      where: { id, deleted: 0 },
      select: { id: true },
    });

    if (!existing) throw new NotFoundException(`City with ID ${id} not found`);

    return this.prisma.dvi_cities.update({
      where: { id },
      data: {
        ...(dto.city_name !== undefined ? { name: dto.city_name } : {}),
        ...(dto.state_id !== undefined ? { state_id: dto.state_id } : {}),
        ...(dto.status !== undefined ? { status: dto.status } : {}),
        updatedon: new Date(),
      },
    });
  }

  async deleteCity(id: number) {
    const existing = await this.prisma.dvi_cities.findFirst({
      where: { id, deleted: 0 },
      select: { id: true },
    });

    if (!existing) throw new NotFoundException(`City with ID ${id} not found`);

    await this.prisma.dvi_cities.update({
      where: { id },
      data: { deleted: 1, updatedon: new Date() },
    });

    return { message: 'City deleted successfully' };
  }

  // ==================== HOTEL CATEGORIES ====================

  async getHotelCategories() {
    return this.prisma.dvi_hotel_category.findMany({
      where: { deleted: 0 },
      orderBy: { hotel_category_id: 'asc' },
    });
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
    return this.prisma.dvi_hotel_category.create({
      data: {
        hotel_category_title: dto.category_title,
        hotel_category_code: String(dto.hotel_category),
        status: dto.status ?? 1,
        deleted: 0,
        createdon: new Date(),
      },
    });
  }

  async updateHotelCategory(id: number, dto: UpdateHotelCategoryDto) {
    const existing = await this.prisma.dvi_hotel_category.findFirst({
      where: { hotel_category_id: id, deleted: 0 },
      select: { hotel_category_id: true },
    });

    if (!existing) {
      throw new NotFoundException(`Hotel category with ID ${id} not found`);
    }

    return this.prisma.dvi_hotel_category.update({
      where: { hotel_category_id: id },
      data: {
        ...(dto.category_title !== undefined
          ? { hotel_category_title: dto.category_title }
          : {}),
        ...(dto.hotel_category !== undefined
          ? { hotel_category_code: String(dto.hotel_category) }
          : {}),
        ...(dto.status !== undefined ? { status: dto.status } : {}),
        updatedon: new Date(),
      },
    });
  }

  async deleteHotelCategory(id: number) {
    const existing = await this.prisma.dvi_hotel_category.findFirst({
      where: { hotel_category_id: id, deleted: 0 },
      select: { hotel_category_id: true },
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
    return this.prisma.dvi_states.findMany({
      where: { deleted: 0 },
      orderBy: { name: 'asc' },
      select: {
        id: true,
        name: true,
      },
    });
  }
}
