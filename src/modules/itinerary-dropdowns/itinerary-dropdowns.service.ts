import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';

export type SimpleOption = {
  id: string;
  label: string;
};

export type LocationOption = {
  id: number;
  name: string;
};

@Injectable()
export class ItineraryDropdownsService {
  constructor(private readonly prisma: PrismaService) {}

  async getLocations(): Promise<LocationOption[]> {
    const rows = await this.prisma.dvi_cities.findMany({
      where: {
        deleted: 0,
        status: 1,
      } as any,
      select: {
        id: true,
        name: true,
      },
      orderBy: [{ name: 'asc' }],
    } as any);

    return rows.map((r) => ({
      id: r.id,
      name: r.name,
    }));
  }

  async getItineraryTypes(): Promise<SimpleOption[]> {
    return [
      { id: '1', label: 'Default' },
      { id: '2', label: 'Customize' },
    ];
  }

  async getTravelTypes(): Promise<SimpleOption[]> {
    return [
      { id: '1', label: 'By Flight' },
      { id: '2', label: 'By Train' },
      { id: '3', label: 'By Road' },
    ];
  }

  async getEntryTicketOptions(): Promise<SimpleOption[]> {
    return [
      { id: '1', label: 'Yes' },
      { id: '0', label: 'No' },
    ];
  }

  async getGuideOptions(): Promise<SimpleOption[]> {
    return [
      { id: '1', label: 'Yes' },
      { id: '0', label: 'No' },
    ];
  }

  async getNationalities(): Promise<SimpleOption[]> {
    return [
      { id: '1', label: 'Indian' },
      { id: '2', label: 'Non Indian' },
    ];
  }

  async getFoodPreferences(): Promise<SimpleOption[]> {
    return [
      { id: 'veg', label: 'Vegetarian' },
      { id: 'non-veg', label: 'Non-Vegetarian' },
      { id: 'egg', label: 'Eggetarian' },
    ];
  }

  async getVehicleTypes(): Promise<SimpleOption[]> {
    const rows = await this.prisma.dvi_vehicle_type.findMany({
      where: {
        status: 1,
        deleted: 0,
      } as any,
      select: {
        vehicle_type_id: true,
        vehicle_type_title: true,
      },
      orderBy: [{ vehicle_type_title: 'asc' }],
    } as any);

    return rows.map((r) => ({
      id: String(r.vehicle_type_id),
      label: r.vehicle_type_title ?? '',
    }));
  }

  async getHotelCategories(): Promise<SimpleOption[]> {
    const rows = await this.prisma.dvi_hotel_category.findMany({
      where: {
        status: 1,
        deleted: 0,
      } as any,
      select: {
        hotel_category_id: true,
        hotel_category_title: true,
        hotel_category_code: true,
      },
      orderBy: [{ hotel_category_title: 'asc' }],
    } as any);

    return rows.map((r) => ({
      id: String(r.hotel_category_id),
      label: r.hotel_category_title ?? r.hotel_category_code ?? '',
    }));
  }

  async getHotelFacilities(): Promise<SimpleOption[]> {
    return [
      { id: '24hr-business-center', label: '24 Hour business center' },
      { id: '24hr-checkin', label: '24 Hour Check-In' },
      { id: '24hr-frontdesk', label: '24 Hour Front Desk' },
      { id: '24hr-room-service', label: '24 Hour Room Service' },
      { id: 'fitness-centre', label: '24-hour fitness facilities' },
      { id: 'wifi', label: 'Free Wi-Fi' },
      { id: 'parking', label: 'Free Parking' },
      { id: 'pool', label: 'Swimming Pool' },
      { id: 'spa', label: 'Spa' },
      { id: 'restaurant', label: 'In-house Restaurant' },
    ];
  }
}
