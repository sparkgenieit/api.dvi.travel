// FILE: src/modules/itinerary/itinerary.service.ts

import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import {
  CreateItineraryDto,
  ItineraryRouteDto,
  ItineraryHotspotDto,
  ItineraryVehicleDto,
} from './dto/create-itinerary.dto';
import { UpdateItineraryDto } from './dto/update-itinerary.dto';

@Injectable()
export class ItineraryService {
  constructor(private prisma: PrismaService) {}

  /**
   * Create itinerary (NestJS version of ajax_latest_manage_itineary.php save logic)
   * - Saves main plan row (dvi_itinerary_plan_details)
   * - Saves route rows (dvi_itinerary_route_details)
   * - Saves hotspot rows (dvi_itinerary_route_hotspot_details)
   * - Saves vehicle rows (dvi_itinerary_plan_vehicle_details)
   */
  async create(dto: CreateItineraryDto) {
    // Derive some server-side defaults similar to PHP
    const routes: ItineraryRouteDto[] = dto.routes ?? [];
    const hotspots: ItineraryHotspotDto[] = dto.hotspots ?? [];
    const vehicles: ItineraryVehicleDto[] = dto.vehicles ?? [];

    const noOfRoutes = routes.length;
    const noOfDays = dto.no_of_days ?? noOfRoutes;
    const noOfNights =
      dto.no_of_nights ?? (noOfDays > 0 ? noOfDays - 1 : 0);

    const tripStart =
      dto.trip_start_date_and_time
        ? new Date(dto.trip_start_date_and_time as any)
        : undefined;
    const tripEnd =
      dto.trip_end_date_and_time
        ? new Date(dto.trip_end_date_and_time as any)
        : undefined;

    const pickUpDateTime =
      dto.pick_up_date_and_time
        ? new Date(dto.pick_up_date_and_time as any)
        : tripStart ?? null;

    const plan = await this.prisma.dvi_itinerary_plan_details.create({
      data: {
        agent_id: dto.agent_id ?? 0,
        staff_id: dto.staff_id ?? 0,
        arrival_location: dto.arrival_location,
        departure_location: dto.departure_location,
        trip_start_date_and_time: tripStart ?? null,
        trip_end_date_and_time: tripEnd ?? null,
        arrival_type: dto.arrival_type ?? 0,
        departure_type: dto.departure_type ?? 0,
        expecting_budget: (dto.expecting_budget as any) ?? 0,
        itinerary_type: dto.itinerary_type ?? 0,
        entry_ticket_required: dto.entry_ticket_required ?? 0,
        no_of_routes: noOfRoutes,
        no_of_days: noOfDays,
        no_of_nights: noOfNights,
        total_adult: dto.total_adult ?? 0,
        total_children: dto.total_children ?? 0,
        total_infants: dto.total_infants ?? 0,
        nationality: dto.nationality ?? 0,
        itinerary_preference: dto.itinerary_preference ?? 0,
        meal_plan_breakfast: dto.meal_plan_breakfast ?? 0,
        meal_plan_lunch: dto.meal_plan_lunch ?? 0,
        meal_plan_dinner: dto.meal_plan_dinner ?? 0,
        guide_for_itinerary: dto.guide_for_itinerary ?? 0,
        food_type: dto.food_type ?? 0,
        special_instructions: dto.special_instructions ?? null,
        pick_up_date_and_time: pickUpDateTime,
        createdon: new Date(),
      },
    });

    // ROUTES
    for (const route of routes) {
      await this.prisma.dvi_itinerary_route_details.create({
        data: {
          itinerary_plan_ID: plan.itinerary_plan_ID,
          location_name: route.location_name,
          next_visiting_location: route.next_visiting_location,
          itinerary_route_date: route.itinerary_route_date
            ? new Date(route.itinerary_route_date as any)
            : null,
          no_of_days: route.no_of_days ?? 0,
          no_of_km: route.no_of_km ?? '',
          direct_to_next_visiting_place:
            route.direct_to_next_visiting_place ?? 0,
          createdon: new Date(),
        },
      });
    }

// HOTSPOTS (kept simple – same as before, you can wire route-wise later)
for (const hotspot of hotspots) {
  await this.prisma.dvi_itinerary_route_hotspot_details.create({
    data: {
      itinerary_plan_ID: plan.itinerary_plan_ID,
      // If you later link hotspot to a specific route, pass real ID here
      itinerary_route_ID: 0,
      hotspot_ID: hotspot.hotspot_ID,
      hotspot_order: hotspot.hotspot_order ?? 0,
      hotspot_adult_entry_cost: hotspot.hotspot_adult_entry_cost ?? 0,
      hotspot_child_entry_cost: hotspot.hotspot_child_entry_cost ?? 0,
      hotspot_infant_entry_cost: hotspot.hotspot_infant_entry_cost ?? 0,
      hotspot_travelling_distance:
        hotspot.hotspot_travelling_distance ?? 0,

      // ✅ main fixes here:
      hotspot_start_time: hotspot.hotspot_start_time
        ? new Date(hotspot.hotspot_start_time as any)
        : undefined,
      hotspot_end_time: hotspot.hotspot_end_time
        ? new Date(hotspot.hotspot_end_time as any)
        : undefined,

      createdon: new Date(),
    },
  });
}


    // VEHICLES (new) – dvi_itinerary_plan_vehicle_details
    for (const vehicle of vehicles) {
      await this.prisma.dvi_itinerary_plan_vehicle_details.create({
        data: {
          itinerary_plan_id: plan.itinerary_plan_ID,
          vehicle_type_id: vehicle.vehicle_type_id,
          vehicle_count: vehicle.vehicle_count ?? 0,
          createdon: new Date(),
        },
      });
    }

    return { message: 'Created', planId: plan.itinerary_plan_ID };
  }

  async findOne(id: number) {
    const plan =
      await this.prisma.dvi_itinerary_plan_details.findUnique({
        where: { itinerary_plan_ID: id },
      });

    if (!plan) throw new NotFoundException('Itinerary not found');

    const routes =
      await this.prisma.dvi_itinerary_route_details.findMany({
        where: { itinerary_plan_ID: id },
      });

    const hotspots =
      await this.prisma.dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: id },
      });

    const vehicles =
      await this.prisma.dvi_itinerary_plan_vehicle_details.findMany({
        where: { itinerary_plan_id: id },
      });

    return { plan, routes, hotspots, vehicles };
  }

  async update(id: number, dto: UpdateItineraryDto) {
    // Only update top-level plan fields for now; routes/hotspots/vehicles
    // can use separate endpoints if needed.
    const data: any = {
      updatedon: new Date(),
    };

    if (dto.agent_id !== undefined) data.agent_id = dto.agent_id;
    if (dto.staff_id !== undefined) data.staff_id = dto.staff_id;
    if (dto.arrival_location !== undefined)
      data.arrival_location = dto.arrival_location;
    if (dto.departure_location !== undefined)
      data.departure_location = dto.departure_location;

    if (dto.trip_start_date_and_time !== undefined) {
      data.trip_start_date_and_time = new Date(
        dto.trip_start_date_and_time as any,
      );
    }
    if (dto.trip_end_date_and_time !== undefined) {
      data.trip_end_date_and_time = new Date(
        dto.trip_end_date_and_time as any,
      );
    }

    if (dto.expecting_budget !== undefined)
      data.expecting_budget = dto.expecting_budget as any;
    if (dto.itinerary_type !== undefined)
      data.itinerary_type = dto.itinerary_type;

    if (dto.total_adult !== undefined) data.total_adult = dto.total_adult;
    if (dto.total_children !== undefined)
      data.total_children = dto.total_children;
    if (dto.total_infants !== undefined)
      data.total_infants = dto.total_infants;

    if (dto.meal_plan_breakfast !== undefined)
      data.meal_plan_breakfast = dto.meal_plan_breakfast;
    if (dto.meal_plan_lunch !== undefined)
      data.meal_plan_lunch = dto.meal_plan_lunch;
    if (dto.meal_plan_dinner !== undefined)
      data.meal_plan_dinner = dto.meal_plan_dinner;

    const updated =
      await this.prisma.dvi_itinerary_plan_details.update({
        where: { itinerary_plan_ID: id },
        data,
      });

    return { message: 'Updated', updated };
  }
}
