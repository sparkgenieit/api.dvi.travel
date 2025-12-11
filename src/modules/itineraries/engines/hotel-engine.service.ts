import { Injectable } from "@nestjs/common";
import { Prisma } from "@prisma/client";
import { HotelPricingService } from "../hotels/hotel-pricing.service";

type Tx = Prisma.TransactionClient;

@Injectable()
export class HotelEngineService {
  constructor(private readonly hotelPricing: HotelPricingService) {}

  async rebuildPlanHotels(
    planId: number,
    tx: Tx,
    userId: number,
  ) {

    /* ---------------- PHASE 0: HARD RESET ---------------- */

    await (tx as any).dvi_itinerary_plan_hotel_room_amenities.deleteMany({
      where: { itinerary_plan_id: planId },
    });

    await (tx as any).dvi_itinerary_plan_hotel_room_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });

    await (tx as any).dvi_itinerary_plan_hotel_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });

    /* ---------------- PLAN & ROUTES ---------------- */

    const plan = await (tx as any).dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
      select: {
        total_adult: true,
        total_children: true,
        total_infants: true,
        preferred_hotel_category: true,
      },
    });

    const totalPersons =
      Number(plan?.total_adult || 0) +
      Number(plan?.total_children || 0) +
      Number(plan?.total_infants || 0);

    // Parse preferred hotel categories (can be comma-separated string)
    const categoryStr = String(plan?.preferred_hotel_category || '');
    const categories = categoryStr
      .split(',')
      .map((c) => Number(c.trim()))
      .filter((c) => c > 0);
    const preferredCategory = categories[0] || 2; // Default to category 2

    const routes = await (tx as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId },
      orderBy: { itinerary_route_ID: "asc" },
      select: {
        itinerary_route_ID: true,
        itinerary_route_date: true,
        location_name: true,
      },
    });

    /* ---------------- PHASE 1: INSERT ROOMS WITH HOTEL SELECTION ---------------- */

    for (const r of routes) {
      const routeDate = r.itinerary_route_date ? new Date(r.itinerary_route_date) : new Date();
      const city = r.location_name;

      // Pick hotel for this location
      const hotel = await this.hotelPricing.pickHotelByCategory(preferredCategory, city);
      
      if (!hotel) {
        // No hotel found, create placeholder
        for (const groupType of [1, 2, 3, 4]) {
          await (tx as any).dvi_itinerary_plan_hotel_room_details.create({
            data: {
              itinerary_plan_id: planId,
              itinerary_route_id: r.itinerary_route_ID,
              itinerary_route_date: r.itinerary_route_date,
              group_type: groupType,
              hotel_id: 0,
              room_type_id: 0,
              room_id: 0,
              room_qty: 1,
              room_rate: 0,
              gst_type: 1,
              gst_percentage: 0,
              extra_bed_count: 0,
              extra_bed_rate: 0,
              child_without_bed_count: 0,
              child_without_bed_charges: 0,
              child_with_bed_count: 0,
              child_with_bed_charges: 0,
              breakfast_required: 1,
              lunch_required: 0,
              dinner_required: 0,
              breakfast_cost_per_person: 0,
              lunch_cost_per_person: 0,
              dinner_cost_per_person: 0,
              total_breafast_cost: 0,
              total_lunch_cost: 0,
              total_dinner_cost: 0,
              total_room_cost: 0,
              total_room_gst_amount: 0,
              createdby: userId,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            },
          });
        }
        continue;
      }

      const hotelId = hotel.hotel_id;

      // Get room prices for this hotel and date
      const roomPrices = await this.hotelPricing.getRoomPrices(hotelId, routeDate);
      
      // Get meal prices
      const mealPrices = await this.hotelPricing.getMealPrice(hotelId, routeDate);

      // For each group type, create room details
      for (const groupType of [1, 2, 3, 4]) {
        // Pick first available room with a rate (PHP behavior)
        const roomPrice = roomPrices.find(rp => rp.rate > 0) || roomPrices[0] || { room_id: 0, rate: 0 };
        
        const roomRate = roomPrice.rate || 0;
        const roomId = roomPrice.room_id || 0;

        // Get room type from room master
        let roomTypeId = 0;
        if (roomId > 0) {
          const roomMaster = await (tx as any).dvi_hotel_rooms.findFirst({
            where: { room_ID: roomId },
            select: { room_type_id: true },
          });
          roomTypeId = roomMaster?.room_type_id || 0;
        }

        const breakfastCost = mealPrices.breakfast.price || 0;
        const totalBreakfastCost = breakfastCost * totalPersons;

        await (tx as any).dvi_itinerary_plan_hotel_room_details.create({
          data: {
            itinerary_plan_id: planId,
            itinerary_route_id: r.itinerary_route_ID,
            itinerary_route_date: r.itinerary_route_date,
            group_type: groupType,

            hotel_id: hotelId,
            room_type_id: roomTypeId,
            room_id: roomId,
            room_qty: 1,
            room_rate: roomRate,

            gst_type: 1,
            gst_percentage: 0,

            extra_bed_count: 0,
            extra_bed_rate: 0,
            child_without_bed_count: 0,
            child_without_bed_charges: 0,
            child_with_bed_count: 0,
            child_with_bed_charges: 0,

            breakfast_required: 1,
            lunch_required: 0,
            dinner_required: 0,

            breakfast_cost_per_person: breakfastCost,
            lunch_cost_per_person: 0,
            dinner_cost_per_person: 0,

            total_breafast_cost: totalBreakfastCost,
            total_lunch_cost: 0,
            total_dinner_cost: 0,
            total_room_cost: roomRate,
            total_room_gst_amount: 0,

            createdby: userId,
            createdon: new Date(),
            status: 1,
            deleted: 0,
          },
        });
      }
    }

    /* ---------------- PHASE 2: CREATE HEADERS FROM ROOMS ---------------- */

    for (const r of routes) {
      for (const groupType of [1, 2, 3, 4]) {

        const agg = await (tx as any)
          .dvi_itinerary_plan_hotel_room_details.aggregate({
            where: {
              itinerary_plan_id: planId,
              itinerary_route_id: r.itinerary_route_ID,
              group_type: groupType,
              deleted: 0,
              status: 1,
            },
            _sum: { 
              room_qty: true,
              total_room_cost: true,
              total_breafast_cost: true,
            },
          });

        const totalRooms = Number(agg._sum.room_qty || 0);
        const totalRoomCost = Number(agg._sum.total_room_cost || 0);
        const totalBreakfastCost = Number(agg._sum.total_breafast_cost || 0);

        // Get hotel_id from first room detail
        const firstRoom = await (tx as any).dvi_itinerary_plan_hotel_room_details.findFirst({
          where: {
            itinerary_plan_id: planId,
            itinerary_route_id: r.itinerary_route_ID,
            group_type: groupType,
          },
          select: { hotel_id: true },
        });

        const hotelId = firstRoom?.hotel_id || 0;

        const header = await (tx as any)
          .dvi_itinerary_plan_hotel_details.create({
            data: {
              itinerary_plan_id: planId,
              itinerary_route_id: r.itinerary_route_ID,
              itinerary_route_date: r.itinerary_route_date,
              itinerary_route_location: r.location_name,
              group_type: groupType,

              hotel_required: 1,
              hotel_category_id: preferredCategory,
              hotel_id: hotelId,

              hotel_margin_percentage: 12,
              hotel_margin_gst_type: 2,
              hotel_margin_gst_percentage: 18,
              hotel_margin_rate: 0,
              hotel_margin_rate_tax_amt: 0,

              hotel_breakfast_cost: totalBreakfastCost,
              hotel_breakfast_cost_gst_amount: 0,
              hotel_lunch_cost: 0,
              hotel_lunch_cost_gst_amount: 0,
              hotel_dinner_cost: 0,
              hotel_dinner_cost_gst_amount: 0,

              total_no_of_persons: totalPersons,
              total_no_of_rooms: totalRooms,

              total_room_cost: totalRoomCost,
              total_room_gst_amount: 0,
              total_hotel_cost: totalRoomCost + totalBreakfastCost,

              total_hotel_meal_plan_cost: totalBreakfastCost,
              total_hotel_meal_plan_cost_gst_amount: 0,

              total_extra_bed_cost: 0,
              total_childwith_bed_cost: 0,
              total_childwithout_bed_cost: 0,

              total_amenities_cost: 0,
              total_amenities_gst_amount: 0,
              total_hotel_tax_amount: 0,

              createdby: userId,
              createdon: new Date(),
              status: 1,
              deleted: 0,
            },
          });

        await (tx as any).dvi_itinerary_plan_hotel_room_details.updateMany({
          where: {
            itinerary_plan_id: planId,
            itinerary_route_id: r.itinerary_route_ID,
            group_type: groupType,
          },
          data: {
            itinerary_plan_hotel_details_id:
              header.itinerary_plan_hotel_details_ID,
          },
        });
      }
    }

    /* ---------------- PHASE 3: ZERO-PRICE CLEANUP ---------------- */

    // NOTE: We keep the hotel_id even if room_rate is 0
    // Only zero out the room-specific fields
    const zeroRows = await (tx as any)
      .dvi_itinerary_plan_hotel_room_details.findMany({
        where: {
          itinerary_plan_id: planId,
          room_rate: 0,
          deleted: 0,
          status: 1,
        },
        select: {
          itinerary_route_id: true,
          group_type: true,
        },
      });

    for (const row of zeroRows) {
      await (tx as any).dvi_itinerary_plan_hotel_room_details.updateMany({
        where: {
          itinerary_plan_id: planId,
          itinerary_route_id: row.itinerary_route_id,
          group_type: row.group_type,
        },
        data: {
          // Keep hotel_id! Don't reset to 0
          room_type_id: 0,
          room_id: 0,
          room_rate: 0,
          total_breafast_cost: 0,
          total_lunch_cost: 0,
          total_dinner_cost: 0,
          total_room_cost: 0,
          total_room_gst_amount: 0,
        },
      });
    }
  }
}
