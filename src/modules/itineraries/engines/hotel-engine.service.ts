// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/hotel-engine.service.ts
// PHP PARITY – FINAL (ROOM-FIRST, HEADER-AFTER, ZERO-PRICE CLEANUP)

import { Injectable } from "@nestjs/common";
import { Prisma } from "@prisma/client";

type Tx = Prisma.TransactionClient;

@Injectable()
export class HotelEngineService {

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

    const routes = await (tx as any).dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId },
      orderBy: { itinerary_route_ID: "asc" },
      select: {
        itinerary_route_ID: true,
        itinerary_route_date: true,
        location_name: true,
      },
    });

    /* ---------------- PHASE 1: INSERT ROOMS FIRST ---------------- */

    for (const r of routes) {
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
            _sum: { room_qty: true },
          });

        const totalRooms = Number(agg._sum.room_qty || 0);

        const header = await (tx as any)
          .dvi_itinerary_plan_hotel_details.create({
            data: {
              itinerary_plan_id: planId,
              itinerary_route_id: r.itinerary_route_ID,
              itinerary_route_date: r.itinerary_route_date,
              itinerary_route_location: r.location_name,
              group_type: groupType,

              hotel_required: 1,
              hotel_category_id: 0,
              hotel_id: 0,

              hotel_margin_percentage: 12,
              hotel_margin_gst_type: 2,
              hotel_margin_gst_percentage: 18,
              hotel_margin_rate: 0,
              hotel_margin_rate_tax_amt: 0,

              hotel_breakfast_cost: 0,
              hotel_breakfast_cost_gst_amount: 0,
              hotel_lunch_cost: 0,
              hotel_lunch_cost_gst_amount: 0,
              hotel_dinner_cost: 0,
              hotel_dinner_cost_gst_amount: 0,

              total_no_of_persons: totalPersons,
              total_no_of_rooms: totalRooms,

              total_room_cost: 0,
              total_room_gst_amount: 0,
              total_hotel_cost: 0,

              total_hotel_meal_plan_cost: 0,
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

    /* ---------------- PHASE 3: ZERO-PRICE NULLIFICATION ---------------- */

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
          hotel_id: 0,
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
