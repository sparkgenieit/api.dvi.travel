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
    console.log('[HOTEL-ENGINE] rebuildPlanHotels started for planId:', planId);

    /* ---------------- PHASE 0: HARD RESET ---------------- */
    let opStart = Date.now();

    await (tx as any).dvi_itinerary_plan_hotel_room_amenities.deleteMany({
      where: { itinerary_plan_id: planId },
    });

    await (tx as any).dvi_itinerary_plan_hotel_room_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });

    await (tx as any).dvi_itinerary_plan_hotel_details.deleteMany({
      where: { itinerary_plan_id: planId },
    });
    console.log('[HOTEL-ENGINE] Delete old data:', Date.now() - opStart, 'ms');

    /* ---------------- PLAN & ROUTES ---------------- */
    opStart = Date.now();

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
        next_visiting_location: true, // PHP uses this for hotel city!
      },
    });
    console.log('[HOTEL-ENGINE] Fetch plan+routes:', Date.now() - opStart, 'ms, routes:', routes.length);

    /* ---------------- PHASE 1: INSERT ROOMS WITH HOTEL SELECTION ---------------- */
    opStart = Date.now();
    let hotelPickCount = 0;
    let roomPriceCount = 0;
    let mealPriceCount = 0;

    const totalRoutes = routes.length;
    
    // Collect all hotel selection tasks for parallel execution
    const hotelTasks: Array<{
      routeIndex: number;
      routeId: number;
      routeDate: Date;
      city: string;
      groupType: number;
    }> = [];
    
    for (let routeIndex = 0; routeIndex < routes.length; routeIndex++) {
      const r = routes[routeIndex];
      const isLastRoute = (routeIndex === totalRoutes - 1);
      
      if (isLastRoute) continue; // No overnight stay needed on departure day
      
      const routeDate = r.itinerary_route_date ? new Date(r.itinerary_route_date) : new Date();
      const city = r.next_visiting_location;

      for (const groupType of [1, 2, 3, 4]) {
        hotelTasks.push({
          routeIndex,
          routeId: r.itinerary_route_ID,
          routeDate,
          city,
          groupType,
        });
      }
    }

    // Execute all hotel picks + pricing in parallel
    const hotelResults = await Promise.all(
      hotelTasks.map(async (task) => {
        hotelPickCount++;
        const hotel = await this.hotelPricing.pickHotelByCategory(
          preferredCategory,
          task.city,
          task.routeDate
        );

        if (!hotel) {
          return {
            ...task,
            hotel: null,
            roomPrices: [],
            mealPrices: { breakfast: { price: 0 }, lunch: { price: 0 }, dinner: { price: 0 } },
            roomTypeId: 0,
          };
        }

        // Fetch room prices, meal prices, and room type in parallel
        const [roomPrices, mealPrices] = await Promise.all([
          this.hotelPricing.getRoomPrices(hotel.hotel_id, task.routeDate),
          this.hotelPricing.getMealPrice(hotel.hotel_id, task.routeDate),
        ]);

        roomPriceCount++;
        mealPriceCount++;

        const roomPrice = roomPrices.find(rp => rp.rate > 0) || roomPrices[0] || { room_id: 0, rate: 0 };
        
        let roomTypeId = 0;
        if (roomPrice.room_id > 0) {
          const roomMaster = await (tx as any).dvi_hotel_rooms.findFirst({
            where: { room_ID: roomPrice.room_id },
            select: { room_type_id: true },
          });
          roomTypeId = roomMaster?.room_type_id || 0;
        }

        return {
          ...task,
          hotel,
          roomPrices,
          mealPrices,
          roomPrice,
          roomTypeId,
        };
      })
    );

    // Insert all room records
    for (const result of hotelResults) {
      const routeForInsert = routes.find((r: any) => r.itinerary_route_ID === result.routeId);
      if (!routeForInsert) continue;

      if (!result.hotel) {
        // No hotel found, create placeholder
        await (tx as any).dvi_itinerary_plan_hotel_room_details.create({
          data: {
            itinerary_plan_id: planId,
            itinerary_route_id: result.routeId,
            itinerary_route_date: routeForInsert.itinerary_route_date,
            group_type: result.groupType,
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
        continue;
      }

      const hotelId = result.hotel.hotel_id;
      const roomRate = result.roomPrice?.rate || 0;
      const roomId = result.roomPrice?.room_id || 0;
      const roomTypeId = result.roomTypeId || 0;
      const breakfastCost = result.mealPrices.breakfast.price || 0;
      const totalBreakfastCost = breakfastCost * totalPersons;

      await (tx as any).dvi_itinerary_plan_hotel_room_details.create({
        data: {
          itinerary_plan_id: planId,
          itinerary_route_id: result.routeId,
          itinerary_route_date: routeForInsert.itinerary_route_date,
          group_type: result.groupType,

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
    console.log('[HOTEL-ENGINE] Phase 1 insert rooms:', Date.now() - opStart, 'ms');
    console.log('[HOTEL-ENGINE] Hotel picks:', hotelPickCount, '| Room prices:', roomPriceCount, '| Meal prices:', mealPriceCount);

    /* ---------------- PHASE 2: CREATE HEADERS FROM ROOMS ---------------- */
    opStart = Date.now();

    for (let routeIndex = 0; routeIndex < routes.length; routeIndex++) {
      const r = routes[routeIndex];
      const isLastRoute = (routeIndex === totalRoutes - 1);
      
      // Skip last route (same as Phase 1)
      if (isLastRoute) {
        continue;
      }
      
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

        // Calculate hotel margin (12% of room + breakfast costs)
        const baseCost = totalRoomCost + totalBreakfastCost;
        const marginRate = baseCost * 0.12; // 12%
        const marginTaxAmt = marginRate * 0.18; // 18% GST on margin

        const header = await (tx as any)
          .dvi_itinerary_plan_hotel_details.create({
            data: {
              itinerary_plan_id: planId,
              itinerary_route_id: r.itinerary_route_ID,
              itinerary_route_date: r.itinerary_route_date,
              itinerary_route_location: r.next_visiting_location, // PHP uses next_visiting_location!
              group_type: groupType,

              hotel_required: 1,
              hotel_category_id: preferredCategory,
              hotel_id: hotelId,

              hotel_margin_percentage: 12,
              hotel_margin_gst_type: 2,
              hotel_margin_gst_percentage: 18,
              hotel_margin_rate: marginRate,
              hotel_margin_rate_tax_amt: marginTaxAmt,

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
    console.log('[HOTEL-ENGINE] Phase 2 create headers:', Date.now() - opStart, 'ms');

    /* ---------------- PHASE 3: ZERO-PRICE CLEANUP ---------------- */
    opStart = Date.now();

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
    console.log('[HOTEL-ENGINE] Phase 3 zero-price cleanup:', Date.now() - opStart, 'ms');
    console.log('[HOTEL-ENGINE] rebuildPlanHotels completed');
  }
}
