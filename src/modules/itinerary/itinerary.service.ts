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
    const routes: ItineraryRouteDto[] = dto.routes ?? [];
    const hotspots: ItineraryHotspotDto[] = dto.hotspots ?? [];
    const vehicles: ItineraryVehicleDto[] = dto.vehicles ?? [];
    const planPayload = dto.plan; // <-- everything is under "plan"

    const noOfRoutes = routes.length;
    const noOfDays = planPayload.no_of_days ?? noOfRoutes;
    const noOfNights =
      planPayload.no_of_nights ?? (noOfDays > 0 ? noOfDays - 1 : 0);

    const tripStart = planPayload.trip_start_date
      ? new Date(planPayload.trip_start_date as any)
      : undefined;

    const tripEnd = planPayload.trip_end_date
      ? new Date(planPayload.trip_end_date as any)
      : undefined;

    const pickUpDateTime = planPayload.pick_up_date_and_time
      ? new Date(planPayload.pick_up_date_and_time as any)
      : tripStart ?? null;

    const plan = await this.prisma.dvi_itinerary_plan_details.create({
      data: {
        agent_id: planPayload.agent_id ?? 0,
        staff_id: planPayload.staff_id ?? 0,
        arrival_location: planPayload.arrival_point,
        departure_location: planPayload.departure_point,
        trip_start_date_and_time: tripStart ?? null,
        trip_end_date_and_time: tripEnd ?? null,
        arrival_type: planPayload.arrival_type ?? 0,
        departure_type: planPayload.departure_type ?? 0,
        // budget -> expecting_budget
        expecting_budget: (planPayload.budget as any) ?? 0,
        itinerary_type: planPayload.itinerary_type ?? 0,
        itinerary_preference: planPayload.itinerary_preference ?? 0,
        entry_ticket_required: planPayload.entry_ticket_required ?? 0,
        no_of_routes: noOfRoutes,
        no_of_days: noOfDays,
        no_of_nights: noOfNights,
        // counts mapping
        total_adult: planPayload.adult_count ?? 0,
        total_children: planPayload.child_count ?? 0,
        total_infants: planPayload.infant_count ?? 0,
        nationality: planPayload.nationality ?? 0,
        guide_for_itinerary: planPayload.guide_for_itinerary ?? 0,
        food_type: planPayload.food_type ?? 0,
        // meal plan not in payload yet -> default 0
        meal_plan_breakfast: 0,
        meal_plan_lunch: 0,
        meal_plan_dinner: 0,
        special_instructions: planPayload.special_instructions ?? null,
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
          no_of_km: (route as any).no_of_km ?? '',
          direct_to_next_visiting_place:
            route.direct_to_next_visiting_place ?? 0,
          createdon: new Date(),
        },
      });
    }

    // HOTSPOTS
    for (const hotspot of hotspots) {
      await this.prisma.dvi_itinerary_route_hotspot_details.create({
        data: {
          itinerary_plan_ID: plan.itinerary_plan_ID,
          // Later you can link to specific route id instead of 0
          itinerary_route_ID: 0,
          hotspot_ID: hotspot.hotspot_ID,
          hotspot_order: hotspot.hotspot_order ?? 0,
          hotspot_adult_entry_cost:
            hotspot.hotspot_adult_entry_cost ?? 0,
          hotspot_child_entry_cost:
            hotspot.hotspot_child_entry_cost ?? 0,
          hotspot_infant_entry_cost:
            hotspot.hotspot_infant_entry_cost ?? 0,
          hotspot_travelling_distance:
            hotspot.hotspot_travelling_distance ?? '',
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

    // VEHICLES
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
    // dto is Partial<CreateItineraryDto>:
    // { plan?: ItineraryPlanDto; routes?: ItineraryRouteDto[]; ... }
    const data: any = {
      updatedon: new Date(),
    };

    const plan = dto.plan;

    if (plan) {
      // ------- basic IDs -------
      if (plan.agent_id !== undefined) data.agent_id = plan.agent_id;
      if (plan.staff_id !== undefined) data.staff_id = plan.staff_id;

      // ------- locations -------
      if (plan.arrival_point !== undefined) {
        data.arrival_location = plan.arrival_point;
      }
      if (plan.departure_point !== undefined) {
        data.departure_location = plan.departure_point;
      }

      // ------- dates -------
      if (plan.trip_start_date !== undefined) {
        data.trip_start_date_and_time = new Date(
          plan.trip_start_date as any,
        );
      }
      if (plan.trip_end_date !== undefined) {
        data.trip_end_date_and_time = new Date(
          plan.trip_end_date as any,
        );
      }

      // pick-up (optional – fall back to start date if needed)
      if (plan.pick_up_date_and_time !== undefined) {
        data.pick_up_date_and_time = new Date(
          plan.pick_up_date_and_time as any,
        );
      }

      // ------- types & preferences -------
      if (plan.itinerary_type !== undefined) {
        data.itinerary_type = plan.itinerary_type;
      }
      if (plan.itinerary_preference !== undefined) {
        data.itinerary_preference = plan.itinerary_preference;
      }
      if (plan.arrival_type !== undefined) {
        data.arrival_type = plan.arrival_type;
      }
      if (plan.departure_type !== undefined) {
        data.departure_type = plan.departure_type;
      }

      // ------- budget / flags -------
      if (plan.budget !== undefined) {
        data.expecting_budget = plan.budget as any;
      }
      if (plan.entry_ticket_required !== undefined) {
        data.entry_ticket_required = plan.entry_ticket_required;
      }
      if (plan.guide_for_itinerary !== undefined) {
        data.guide_for_itinerary = plan.guide_for_itinerary;
      }
      if (plan.nationality !== undefined) {
        data.nationality = plan.nationality;
      }
      if (plan.food_type !== undefined) {
        data.food_type = plan.food_type;
      }

      // ------- counts -------
      if (plan.adult_count !== undefined) {
        data.total_adult = plan.adult_count;
      }
      if (plan.child_count !== undefined) {
        data.total_children = plan.child_count;
      }
      if (plan.infant_count !== undefined) {
        data.total_infants = plan.infant_count;
      }

      // ------- days / nights -------
      if (plan.no_of_days !== undefined) {
        data.no_of_days = plan.no_of_days;
      }
      if (plan.no_of_nights !== undefined) {
        data.no_of_nights = plan.no_of_nights;
      }

      // ------- notes -------
      if (plan.special_instructions !== undefined) {
        data.special_instructions = plan.special_instructions;
      }
    }

    // Optionally update count of routes if caller sends them
    if (dto.routes && dto.routes.length > 0) {
      data.no_of_routes = dto.routes.length;

      // If no_of_days/ no_of_nights not explicitly sent, you can
      // recompute them here (optional):
      if (!data.no_of_days) {
        data.no_of_days = dto.routes.length;
      }
      if (!data.no_of_nights && data.no_of_days > 0) {
        data.no_of_nights = data.no_of_days - 1;
      }
      // NOTE: we are NOT modifying individual route rows here.
    }

    const updated =
      await this.prisma.dvi_itinerary_plan_details.update({
        where: { itinerary_plan_ID: id },
        data,
      });

    return { message: 'Updated', updated };
  }

}
