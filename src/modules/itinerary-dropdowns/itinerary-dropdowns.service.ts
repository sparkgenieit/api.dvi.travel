// FILE: src/modules/itinerary-dropdowns/itinerary-dropdowns.service.ts

import { Injectable } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';

export type SimpleOption = {
  id: string;
  label: string;
};

export type LocationOption = {
  id: string; // same as PHP: <option value="LOCATION_NAME">
  name: string;
};

type LocationType = 'source' | 'destination';

@Injectable()
export class ItineraryDropdownsService {
  constructor(private readonly prisma: PrismaService) {}

  // ---------------------------------------------------------------------------
  // LOCATIONS (source / destination) from dvi_stored_locations like old PHP
  // ---------------------------------------------------------------------------
  async getLocations(
    type: LocationType = 'source',
    sourceLocation?: string,
  ): Promise<LocationOption[]> {
    if (type === 'destination') {
      const rows = await this.prisma.dvi_stored_locations.findMany({
        where: {
          deleted: 0,
          status: 1,
          ...(sourceLocation ? { source_location: sourceLocation } : {}),
        } as any,
        select: {
          destination_location: true,
        },
        distinct: ['destination_location'],
      } as any);

      return rows
        .filter((r) => !!r.destination_location)
        .map((r) => ({
          id: r.destination_location as string,
          name: r.destination_location as string,
        }));
    }

    const rows = await this.prisma.dvi_stored_locations.findMany({
      where: {
        deleted: 0,
        status: 1,
      } as any,
      select: {
        source_location: true,
      },
      distinct: ['source_location'],
    } as any);

    return rows
      .filter((r) => !!r.source_location)
      .map((r) => ({
        id: r.source_location as string,
        name: r.source_location as string,
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
      { id: '101', label: 'Indian T' },
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

  // ---------------------------------------------------------------------------
  // VIA ROUTES – match old PHP behaviour (use DB: dvi_stored_locations +
  // dvi_stored_location_via_routes)
  // ---------------------------------------------------------------------------
  async getViaRoutes(
    source?: string,
    destination?: string,
    q?: string, // optional typed text from frontend
  ): Promise<SimpleOption[]> {
    const src = (source || '').trim();
    const dest = (destination || '').trim();

    if (!src || !dest) {
      console.warn(
        '[ViaRoutes] Missing source or destination',
        'source=',
        src,
        'destination=',
        dest,
      );
      return [];
    }

    // 1) Find location_ID from dvi_stored_locations (same as PHP)
    const location = await this.prisma.dvi_stored_locations.findFirst({
      where: {
        deleted: 0,
        status: 1,
        source_location: src,
        destination_location: dest,
      } as any,
      select: {
        location_ID: true,
      },
    } as any);

    if (!location) {
      console.warn(
        '[ViaRoutes] No location_ID found for source/destination',
        'source=',
        src,
        'destination=',
        dest,
      );
      return [];
    }

    // 2) Fetch via routes for that location_id
    const viaRoutes = await this.prisma.dvi_stored_location_via_routes.findMany({
      where: {
        deleted: 0,
        status: 1,
        location_id: location.location_ID,
        ...(q && q.trim()
          ? {
              via_route_location: {
                contains: q.trim(),
                mode: 'insensitive',
              } as any,
            }
          : {}),
      } as any,
      select: {
        via_route_location_ID: true,
        via_route_location: true,
      },
      orderBy: {
        via_route_location: 'asc',
      },
    } as any);

    if (!viaRoutes.length) {
      return [];
    }

    // 3) Map to SimpleOption[] (id = via_route_location_ID, label = name)
    return viaRoutes.map((r) => ({
      id: String(r.via_route_location_ID),
      label: r.via_route_location ?? '',
    }));
  }
}
