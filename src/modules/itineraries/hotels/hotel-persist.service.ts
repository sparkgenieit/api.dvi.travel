import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../prisma.service";
import { HotelPricingService } from "./hotel-pricing.service";

export interface PersistHotelInput {
  itinerary_plan_id: number;
  itinerary_route_id: number;
  itinerary_route_date: Date;
  itinerary_route_location: string | null;
  group_type: number;
  hotel_category_id: number;
  total_no_of_persons: number;
  createdby: number;
}

const money = (v: any) => {
  const n = Number(v ?? 0);
  return Number.isFinite(n) ? Math.round((n + Number.EPSILON) * 100) / 100 : 0;
};

@Injectable()
export class HotelPersistService {
  private readonly log = new Logger("HotelPersistService");

  constructor(
    private readonly prisma: PrismaService,
    private readonly pricing: HotelPricingService,
  ) {}

  /** Create real hotel rows for a single route/night (details + 1 room line + computed totals). */
  async persistForPlanDay(input: PersistHotelInput) {
    const {
      itinerary_plan_id, itinerary_route_id, itinerary_route_date,
      itinerary_route_location, group_type, hotel_category_id,
      total_no_of_persons, createdby,
    } = input;

    this.log.debug(`persistForPlanDay:start route=${itinerary_route_id} date=${itinerary_route_date.toISOString().slice(0,10)} city='${itinerary_route_location}' cat=${hotel_category_id} group=${group_type}`);

    // 1) Pick hotel (supports optional city in pricing service)
    const hotel = await this.pricing.pickHotelByCategory(hotel_category_id, itinerary_route_location);
    const hotel_id = Number(hotel?.hotel_id ?? 0);
    const marginPct = Number(hotel?.hotel_margin ?? 0);
    const marginGstType = Number(hotel?.hotel_margin_gst_type ?? 0);
    const marginGstPct = Number(hotel?.hotel_margin_gst_percentage ?? 0);
    this.log.debug(`hotelPick -> id=${hotel_id} city='${(hotel as any)?.hotel_city ?? ""}' marginPct=${marginPct}`);

    // 2) Price books for the date
    const roomPrices = hotel_id ? await this.pricing.getRoomPrices(hotel_id, itinerary_route_date) : [];
    const meals      = hotel_id ? await this.pricing.getMealPrice(hotel_id, itinerary_route_date)
                                : { breakfast: { price: 0, gstPct: 0 }, lunch: { price: 0, gstPct: 0 }, dinner: { price: 0, gstPct: 0 } };

    // 3) Choose a room (first non-zero; else first)
    const room = roomPrices.find(r => r.rate > 0) ?? roomPrices[0];
    this.log.debug(`roomPick -> ${room ? `room_id=${room.room_id} rate=${room.rate}` : "none"}`);

    let total_no_of_rooms = 0, total_room_cost = 0, total_room_gst_amount = 0;
    let roomDetailsId: number | null = null;

    if (room) {
      const qty = 1;
      const cost = money(qty * room.rate);
      const gst  = money(cost * (room.gstPct / 100));

      const roomRow = await this.prisma.dvi_itinerary_plan_hotel_room_details.create({
        data: {
          itinerary_plan_hotel_details_id: 0, // backfilled after details insert
          group_type,
          itinerary_plan_id,
          itinerary_route_id,
          itinerary_route_date: itinerary_route_date as any,
          hotel_id,
          room_type_id: room.room_id,
          room_id: room.room_id,
          room_qty: qty,
          room_rate: room.rate,
          gst_type: 0,
          gst_percentage: room.gstPct,

          extra_bed_count: 0,
          extra_bed_rate: 0,
          child_without_bed_count: 0,
          child_without_bed_charges: 0,
          child_with_bed_count: 0,
          child_with_bed_charges: 0,

          breakfast_required: 0,
          lunch_required: 0,
          dinner_required: 0,

          breakfast_cost_per_person: 0,
          lunch_cost_per_person: 0,
          dinner_cost_per_person: 0,

          total_breafast_cost: 0,
          total_lunch_cost: 0,
          total_dinner_cost: 0,

          total_room_cost: cost,
          total_room_gst_amount: gst,

          createdby,
          status: 1,
          deleted: 0,
        } as any,
      });

      roomDetailsId = (roomRow as any).itinerary_plan_hotel_room_details_ID ?? null;
      total_no_of_rooms += qty;
      total_room_cost += cost;
      total_room_gst_amount += gst;

      this.log.debug(`roomDetails:inserted id=${roomDetailsId} qty=${qty} cost=${cost} gst=${gst}`);
    }

    // 4) Meals for persons (GST=0 in schema → computed as 0)
    const persons = Math.max(0, Number(total_no_of_persons ?? 0));
    const bCost = money(persons * meals.breakfast.price), bGst = money(bCost * (meals.breakfast.gstPct / 100));
    const lCost = money(persons * meals.lunch.price),     lGst = money(lCost * (meals.lunch.gstPct / 100));
    const dCost = money(persons * meals.dinner.price),    dGst = money(dCost * (meals.dinner.gstPct / 100));

    const mealCost = money(bCost + lCost + dCost);
    const mealGst  = money(bGst + lGst + dGst);

    // 5) Margin on rooms
    const marginRate    = money((total_room_cost * marginPct) / 100);
    const marginRateGst = money((marginRate * marginGstPct) / 100);

    // 6) Details
    const details = await this.prisma.dvi_itinerary_plan_hotel_details.create({
      data: {
        group_type,
        itinerary_plan_id,
        itinerary_route_id,
        itinerary_route_date: itinerary_route_date as any,
        itinerary_route_location,

        hotel_required: hotel_id ? 1 : 0,
        hotel_category_id,
        hotel_id,

        hotel_margin_percentage: marginPct,
        hotel_margin_gst_type: marginGstType,
        hotel_margin_gst_percentage: marginGstPct,
        hotel_margin_rate: marginRate,
        hotel_margin_rate_tax_amt: marginRateGst,

        hotel_breakfast_cost: bCost,
        hotel_breakfast_cost_gst_amount: bGst,
        hotel_lunch_cost: lCost,
        hotel_lunch_cost_gst_amount: lGst,
        hotel_dinner_cost: dCost,
        hotel_dinner_cost_gst_amount: dGst,

        total_no_of_persons: persons,

        total_hotel_meal_plan_cost: mealCost,
        total_hotel_meal_plan_cost_gst_amount: mealGst,

        total_extra_bed_cost: 0,
        total_extra_bed_cost_gst_amount: 0,
        total_childwith_bed_cost: 0,
        total_childwith_bed_cost_gst_amount: 0,
        total_childwithout_bed_cost: 0,
        total_childwithout_bed_cost_gst_amount: 0,

        total_no_of_rooms,
        total_room_cost,
        total_room_gst_amount,

        total_hotel_cost: money(total_room_cost + mealCost + marginRate),
        total_hotel_tax_amount: money(total_room_gst_amount + mealGst + marginRateGst),

        total_amenities_cost: 0,
        total_amenities_gst_amount: 0,

        createdby,
        status: 1,
        deleted: 0,
      } as any,
    });

    const detailsId = (details as any).itinerary_plan_hotel_details_ID as number;
    this.log.debug(`hotelDetails:inserted id=${detailsId} hotel_id=${hotel_id} rooms=${total_no_of_rooms} roomCost=${total_room_cost} meal=${mealCost}`);

    // 7) Backfill child FK to details
    if (roomDetailsId) {
      await this.prisma.dvi_itinerary_plan_hotel_room_details.update({
        where: { itinerary_plan_hotel_room_details_ID: roomDetailsId },
        data: { itinerary_plan_hotel_details_id: detailsId } as any,
      });
      this.log.debug(`roomDetails:backfilled details_id=${detailsId} for room_id=${roomDetailsId}`);
    }

    return detailsId;
  }
}
