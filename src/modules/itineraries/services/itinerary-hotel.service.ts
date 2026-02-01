import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import { ItineraryHotelDetailsTboService } from '../itinerary-hotel-details-tbo.service';

/**
 * Itinerary Hotel Service
 * Handles all hotel-related operations for itineraries
 * - Get available hotels
 * - Select/update hotel
 * - Bulk save hotel selections
 */
@Injectable()
export class ItineraryHotelService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly hotelDetailsTboService: ItineraryHotelDetailsTboService,
  ) {}

  /**
   * Get available hotels for a route
   */
  async getAvailableHotels(routeId: number) {
    // Get route details
    const route = await (this.prisma as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_route_ID: routeId },
    });

    if (!route || !route.location_id) {
      return [];
    }

    // Get location coordinates separately
    const location = await (this.prisma as any).dvi_stored_locations.findFirst({
      where: { location_ID: Number(route.location_id) },
      select: {
        destination_location_lattitude: true,
        destination_location_longitude: true,
      },
    });

    if (!location || !location.destination_location_lattitude || !location.destination_location_longitude) {
      return [];
    }

    const destLat = Number(location.destination_location_lattitude);
    const destLng = Number(location.destination_location_longitude);

    // Fetch hotels with Haversine distance calculation
    const hotels = await this.prisma.$queryRaw`
      SELECT 
        h.hotel_id,
        h.hotel_name,
        h.hotel_address,
        h.hotel_latitude,
        h.hotel_longitude,
        h.hotel_category,
        (6371 * acos(
          cos(radians(${destLat})) * 
          cos(radians(h.hotel_latitude)) * 
          cos(radians(h.hotel_longitude) - radians(${destLng})) + 
          sin(radians(${destLat})) * 
          sin(radians(h.hotel_latitude))
        )) AS distance_in_km
      FROM dvi_hotel h
      WHERE h.status = 1 
        AND h.deleted = 0
        AND h.hotel_latitude IS NOT NULL
        AND h.hotel_longitude IS NOT NULL
      HAVING distance_in_km <= 20
      ORDER BY distance_in_km ASC
      LIMIT 20
    `;

    return (hotels as any[]).map(h => ({
      id: h.hotel_id,
      name: h.hotel_name,
      address: h.hotel_address,
      category: h.hotel_category,
      distance: Number(h.distance_in_km).toFixed(2),
    }));
  }

  /**
   * Select/update hotel for a route
   */
  async selectHotel(data: {
    planId: number;
    routeId: number;
    hotelId: number;
    roomTypeId: number;
    groupType?: number;
    mealPlan?: { all?: boolean; breakfast?: boolean; lunch?: boolean; dinner?: boolean };
  }) {
    const userId = 1;

    // Get the quote ID to clear the cache
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: data.planId },
    });
    const quoteId = (plan as any)?.itinerary_quote_ID || '';

    // Check if hotel assignment already exists in hotel_details
    const existingHotelDetails = await (this.prisma as any).dvi_itinerary_plan_hotel_details.findFirst({
      where: {
        itinerary_plan_id: data.planId,
        itinerary_route_id: data.routeId,
        deleted: 0,
      },
    });

    const mealBreakfast = data.mealPlan?.breakfast || data.mealPlan?.all ? 1 : 0;
    const mealLunch = data.mealPlan?.lunch || data.mealPlan?.all ? 1 : 0;
    const mealDinner = data.mealPlan?.dinner || data.mealPlan?.all ? 1 : 0;

    let hotelDetailsId: number;

    if (existingHotelDetails) {
      // Update existing hotel assignment
      console.log(
        `📝 Updating existing hotel - Old ID: ${existingHotelDetails.hotel_id}, New ID: ${data.hotelId}, GroupType: ${data.groupType}`
      );
      await (this.prisma as any).dvi_itinerary_plan_hotel_details.update({
        where: { itinerary_plan_hotel_details_ID: existingHotelDetails.itinerary_plan_hotel_details_ID },
        data: {
          hotel_id: data.hotelId,
          group_type: data.groupType || 1,
          updatedon: new Date(),
        },
      });
      const updated = await (this.prisma as any).dvi_itinerary_plan_hotel_details.findUnique({
        where: { itinerary_plan_hotel_details_ID: existingHotelDetails.itinerary_plan_hotel_details_ID },
      });
      console.log(`✅ Updated. New values - hotel_id: ${(updated as any).hotel_id}, group_type: ${(updated as any).group_type}`);
      hotelDetailsId = existingHotelDetails.itinerary_plan_hotel_details_ID;
    } else {
      // Create new hotel assignment
      console.log(`✨ Creating new hotel - ID: ${data.hotelId}, GroupType: ${data.groupType}`);
      const created = await (this.prisma as any).dvi_itinerary_plan_hotel_details.create({
        data: {
          itinerary_plan_id: data.planId,
          itinerary_route_id: data.routeId,
          hotel_id: data.hotelId,
          group_type: data.groupType || 1,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
      console.log(`✅ Created. Values - hotel_id: ${(created as any).hotel_id}, group_type: ${(created as any).group_type}`);
      hotelDetailsId = created.itinerary_plan_hotel_details_ID;
    }

    // Check if room details already exist
    const existingRoomDetails = await (this.prisma as any).dvi_itinerary_plan_hotel_room_details.findFirst({
      where: {
        itinerary_plan_id: data.planId,
        itinerary_route_id: data.routeId,
        hotel_id: data.hotelId,
        deleted: 0,
      },
    });

    if (existingRoomDetails) {
      // Update existing room details
      await (this.prisma as any).dvi_itinerary_plan_hotel_room_details.update({
        where: { itinerary_plan_hotel_room_details_ID: existingRoomDetails.itinerary_plan_hotel_room_details_ID },
        data: {
          room_type_id: data.roomTypeId,
          breakfast_required: mealBreakfast,
          lunch_required: mealLunch,
          dinner_required: mealDinner,
          updatedon: new Date(),
        },
      });
    } else {
      // Create new room details
      await (this.prisma as any).dvi_itinerary_plan_hotel_room_details.create({
        data: {
          itinerary_plan_hotel_details_id: hotelDetailsId,
          itinerary_plan_id: data.planId,
          itinerary_route_id: data.routeId,
          hotel_id: data.hotelId,
          room_type_id: data.roomTypeId,
          breakfast_required: mealBreakfast,
          lunch_required: mealLunch,
          dinner_required: mealDinner,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // Clear cache for this quote so next request gets fresh data
    if (quoteId) {
      this.hotelDetailsTboService.clearCacheForQuote(quoteId);
    }

    return {
      success: true,
      message: 'Hotel selected successfully',
    };
  }

  /**
   * Bulk save hotel selections - used before confirming itinerary
   */
  async bulkSaveHotels(planId: number, hotels: any[]) {
    const userId = 1;

    // Get the quote ID to clear the cache
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
    });
    const quoteId = (plan as any)?.itinerary_quote_ID || '';

    console.log(`📦 Bulk saving ${hotels.length} hotel(s) for plan ${planId}`);

    for (const hotel of hotels) {
      await this.selectHotel({
        planId,
        routeId: hotel.routeId,
        hotelId: hotel.hotelId,
        roomTypeId: hotel.roomTypeId || 1,
        groupType: hotel.groupType,
        mealPlan: hotel.mealPlan,
      });
    }

    // Clear cache once at the end
    if (quoteId) {
      this.hotelDetailsTboService.clearCacheForQuote(quoteId);
    }

    return {
      success: true,
      message: `Successfully saved ${hotels.length} hotel selections`,
    };
  }
}
