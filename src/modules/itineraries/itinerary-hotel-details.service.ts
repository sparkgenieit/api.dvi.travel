// FILE: src/itineraries/itinerary-hotel-details.service.ts

import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { dvi_itinerary_plan_details } from '@prisma/client';

export interface ItineraryHotelTabDto {
  groupType: number;
  label: string;
  totalAmount: number;
}

export interface ItineraryHotelRowDto {
  groupType: number;
  day: string;
  destination: string;
  hotelName: string;
  category: number;
  roomType: string;
  mealPlan: string;
  totalHotelCost: number;
  totalHotelTaxAmount: number;
}

export interface ItineraryHotelDetailsResponseDto {
  quoteId: string;
  planId: number;
  hotelRatesVisible: boolean;
  hotelTabs: ItineraryHotelTabDto[];
  hotels: ItineraryHotelRowDto[];
  totalRoomCount: number;
}

/**
 * Room-level DTO, inspired by PHP structured_hotel_room_details[]
 */
export interface ItineraryHotelRoomDto {
  itineraryPlanId: number;
  itineraryRouteId: number;
  itineraryPlanHotelRoomDetailsId: number;
  hotelId: number;
  roomTypeId: number;
  roomId: number;

  // Pricing & tax
  pricePerNight: number;
  gstType: string | null;
  gstPercentage: number;

  // Occupancy / extras – these are totals per (route,hotel,roomType,room)
  totalExtraBed: number;
  totalChildWithBed: number;
  totalChildWithoutBed: number;
  extraBedCharge: number;
  childWithBedCharge: number;
  childWithoutBedCharge: number;
}

export interface ItineraryHotelRoomDetailsResponseDto {
  quoteId: string;
  planId: number;
  rooms: ItineraryHotelRoomDto[];
}

@Injectable()
export class ItineraryHotelDetailsService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Public endpoint-style method: used by /itineraries/hotel_details/:quoteId
   */
  async getHotelDetailsByQuoteId(
    quoteId: string,
  ): Promise<ItineraryHotelDetailsResponseDto> {
    const plan = await this.prisma.dvi_itinerary_plan_details.findFirst({
      where: { itinerary_quote_ID: quoteId, deleted: 0 },
    });

    if (!plan) {
      throw new NotFoundException('Itinerary not found');
    }

    return this.getHotelDetailsForPlan(plan);
  }

 /**
 * NEW: Public endpoint-style method for /itineraries/hotel_room_details/:quoteId
 * Rough equivalent of PHP structured_hotel_room_details[] construction,
 * but ENRICHED with hotel & room-type labels for React cards.
 */
async getHotelRoomDetailsByQuoteId(
  quoteId: string,
): Promise<ItineraryHotelRoomDetailsResponseDto> {
  const plan = await this.prisma.dvi_itinerary_plan_details.findFirst({
    where: { itinerary_quote_ID: quoteId, deleted: 0 },
  });

  if (!plan) {
    throw new NotFoundException('Itinerary not found');
  }

  const planId = plan.itinerary_plan_ID;

  // 1) Raw room rows
  const roomRowsRaw =
    await this.prisma.dvi_itinerary_plan_hotel_room_details.findMany({
      where: { itinerary_plan_id: planId, deleted: 0 },
    });

  if (!roomRowsRaw.length) {
    return {
      quoteId: plan.itinerary_quote_ID ?? '',
      planId,
      rooms: [],
    };
  }

  // 2) Collect hotel_ids and room_type_ids to enrich labels
  const hotelIds = Array.from(
    new Set(
      roomRowsRaw
        .map((r: any) => Number(r.hotel_id ?? 0))
        .filter((id) => id > 0),
    ),
  );

  const roomTypeIds = Array.from(
    new Set(
      roomRowsRaw
        .map((r: any) => Number(r.room_type_id ?? 0))
        .filter((id) => id > 0),
    ),
  );

  const hotels = hotelIds.length
    ? await this.prisma.dvi_hotel.findMany({
        where: { hotel_id: { in: hotelIds }, deleted: false },
      })
    : [];

  const roomTypes = roomTypeIds.length
    ? await this.prisma.dvi_hotel_roomtype.findMany({
        where: { room_type_id: { in: roomTypeIds } },
      })
    : [];

  const hotelMap = new Map<number, any>(
    hotels.map((h: any) => [Number(h.hotel_id), h]),
  );

  const roomTypeMap = new Map<number, any>(
    roomTypes.map((rt: any) => [Number(rt.room_type_id), rt]),
  );

  // 3) Map to DTO
  const rooms: ItineraryHotelRoomDto[] = roomRowsRaw.map((row: any) => {
    const itineraryPlanId = Number(row.itinerary_plan_id ?? planId ?? 0);
    const itineraryRouteId = Number(row.itinerary_route_id ?? 0);
    const itineraryPlanHotelRoomDetailsId = Number(
      row.itinerary_plan_hotel_room_details_ID ?? row.id ?? 0,
    );

    const hotelId = Number(row.hotel_id ?? 0);
    const roomTypeId = Number(row.room_type_id ?? 0);
    const roomId = Number(row.room_id ?? 0);

    const hotel = hotelMap.get(hotelId) || null;
    const roomType = roomTypeMap.get(roomTypeId) || null;

    const hotelName = hotel ? (hotel.hotel_name as string) ?? '' : '';
    const hotelCategory = hotel
      ? Number(hotel.hotel_category ?? hotel.hotel_star ?? 0) || null
      : null;

    const roomTypeName = roomType
      ? (roomType.room_type_title as string) ?? ''
      : '';

    const pricePerNight = Number(
      row.total_price ?? row.price_per_night ?? row.room_price ?? 0,
    );

    const gstType: string | null =
      (row.gst_type as string | undefined) ?? null;
    const gstPercentage = Number(row.gst_percentage ?? 0);

    const totalExtraBed = Number(row.total_extra_bed ?? 0);
    const totalChildWithBed = Number(row.total_child_with_bed ?? 0);
    const totalChildWithoutBed = Number(row.total_child_without_bed ?? 0);

    const extraBedCharge = Number(row.extra_bed_charge ?? 0);
    const childWithBedCharge = Number(row.child_with_bed_charge ?? 0);
    const childWithoutBedCharge = Number(row.child_without_bed_charge ?? 0);

    return {
      itineraryPlanId,
      itineraryRouteId,
      itineraryPlanHotelRoomDetailsId,
      hotelId,
      hotelName,
      hotelCategory,
      roomTypeId,
      roomTypeName,
      roomId,
      pricePerNight,
      gstType,
      gstPercentage,
      totalExtraBed,
      totalChildWithBed,
      totalChildWithoutBed,
      extraBedCharge,
      childWithBedCharge,
      childWithoutBedCharge,
    };
  });

  return {
    quoteId: plan.itinerary_quote_ID ?? '',
    planId,
    rooms,
  };
}


  /**
   * Internal reusable method: used by ItineraryDetailsService when building the full details payload.
   */
  async getHotelDetailsForPlan(
    plan: dvi_itinerary_plan_details,
  ): Promise<ItineraryHotelDetailsResponseDto> {
    const planId = plan.itinerary_plan_ID;

    // 1) Visibility flag from plan (hotel_rates_visibility)
    const hotelRatesVisible: boolean =
      (plan as any).hotel_rates_visibility === 1 ||
      (plan as any).hotel_rates_visibility === true;

    // 2) Raw hotel rows from dvi_itinerary_plan_hotel_details
    const hotelRowsRaw =
      await this.prisma.dvi_itinerary_plan_hotel_details.findMany({
        where: { itinerary_plan_id: planId, deleted: 0 },
        orderBy: [
          { group_type: 'asc' as const },
          { itinerary_route_date: 'asc' as const },
        ],
      });

    // 3) Distinct hotels for name/category
    const hotelIds = Array.from(
      new Set(
        hotelRowsRaw
          .map((h) => (h as any).hotel_id as number | null)
          .filter((id): id is number => typeof id === 'number' && id > 0),
      ),
    );

    const hotelMasters = hotelIds.length
      ? await this.prisma.dvi_hotel.findMany({
          where: { hotel_id: { in: hotelIds }, deleted: false },
        })
      : [];

    const hotelMap = new Map(
      hotelMasters.map((h) => [Number((h as any).hotel_id), h]),
    );

    // 4) Per-group GRAND_TOTAL_OF_THE_HOTEL_CHARGES
    //    SUM(total_hotel_cost) + SUM(total_hotel_tax_amount)
    const hotelGroupsRaw =
      await this.prisma.dvi_itinerary_plan_hotel_details.groupBy({
        by: ['group_type'],
        where: { itinerary_plan_id: planId, deleted: 0 },
        _sum: {
          total_hotel_cost: true,
          total_hotel_tax_amount: true,
        },
      });

    const hotelTabs: ItineraryHotelTabDto[] = hotelGroupsRaw
      .map((g) => {
        const groupType = Number((g as any).group_type ?? 0) || 0;
        const totalHotelCost = Number(g._sum.total_hotel_cost ?? 0);
        const totalHotelTaxAmount = Number(g._sum.total_hotel_tax_amount ?? 0);
        const groupTotal = totalHotelCost + totalHotelTaxAmount;

        // NOTE: margins/markups are NOT applied here – this is pure SUM(cost + tax)
        return {
          groupType,
          label: `Recommended #${groupType}`,
          totalAmount: Number(groupTotal),
        };
      })
      .sort((a, b) => a.groupType - b.groupType);

    // 5) Per-row hotel list (with group_type & per-row cost)
    const hotels: ItineraryHotelRowDto[] = hotelRowsRaw.map((h, idx) => {
      const master = hotelMap.get(Number((h as any).hotel_id)) || null;
      const dateLabel = h.itinerary_route_date
        ? h.itinerary_route_date.toISOString().slice(0, 10)
        : '';

      return {
        groupType: Number((h as any).group_type ?? 0) || 0,
        day: `Day ${idx + 1} | ${dateLabel}`,
        destination: (h as any).itinerary_route_location ?? '',
        hotelName: master ? ((master as any).hotel_name ?? '') : '',
        category: master ? ((master as any).hotel_category ?? 0) : 0,
        roomType: '', // room/meal details can be wired later
        mealPlan: '',
        totalHotelCost: Number((h as any).total_hotel_cost ?? 0),
        totalHotelTaxAmount: Number((h as any).total_hotel_tax_amount ?? 0),
      };
    });

    // 6) Total room count (fallback)
    const totalRoomCount = hotelRowsRaw.reduce(
      (sum, h) => sum + ((h as any).total_no_of_rooms ?? 0),
      0,
    );

    return {
      quoteId: plan.itinerary_quote_ID ?? '',
      planId,
      hotelRatesVisible,
      hotelTabs,
      hotels,
      totalRoomCount,
    };
  }
}
