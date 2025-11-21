import { Injectable, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { CreateItineraryDto, ItineraryRouteDto, ItineraryHotspotDto } from './dto/create-itinerary.dto';
import { UpdateItineraryDto } from './dto/update-itinerary.dto';

@Injectable()
export class ItineraryService {
  constructor(private prisma: PrismaService) {}

  async create(dto: CreateItineraryDto) {
    const plan = await this.prisma.dvi_itinerary_plan_details.create({
      data: {
        agent_id: dto.agent_id,
        staff_id: dto.staff_id,
        arrival_location: dto.arrival_location,
        departure_location: dto.departure_location,
        trip_start_date_and_time: new Date(dto.trip_start_date_and_time),
        trip_end_date_and_time: new Date(dto.trip_end_date_and_time),
        expecting_budget: dto.expecting_budget,
        itinerary_type: dto.itinerary_type,
        total_adult: dto.total_adult,
        total_children: dto.total_children,
        total_infants: dto.total_infants,
        meal_plan_breakfast: dto.meal_plan_breakfast,
        meal_plan_lunch: dto.meal_plan_lunch,
        meal_plan_dinner: dto.meal_plan_dinner,
        createdon: new Date(),
      },
    });

    for (const route of dto.routes ?? []) {
      await this.prisma.dvi_itinerary_route_details.create({
        data: {
          itinerary_plan_ID: plan.itinerary_plan_ID,
          location_name: route.location_name,
          next_visiting_location: route.next_visiting_location,
          itinerary_route_date: new Date(route.itinerary_route_date),
          no_of_days: route.no_of_days,
          no_of_km: route.no_of_km,
          direct_to_next_visiting_place: route.direct_to_next_visiting_place,
          createdon: new Date(),
        },
      });
    }

    for (const hotspot of dto.hotspots ?? []) {
      await this.prisma.dvi_itinerary_route_hotspot_details.create({
        data: {
          itinerary_plan_ID: plan.itinerary_plan_ID,
          itinerary_route_ID: 0, // TODO: link to specific route if needed
          hotspot_ID: hotspot.hotspot_ID,
          hotspot_order: hotspot.hotspot_order,
          hotspot_adult_entry_cost: hotspot.hotspot_adult_entry_cost,
          hotspot_child_entry_cost: hotspot.hotspot_child_entry_cost,
          hotspot_infant_entry_cost: hotspot.hotspot_infant_entry_cost,
          hotspot_travelling_distance: hotspot.hotspot_travelling_distance,
          hotspot_start_time: new Date(hotspot.hotspot_start_time),
          hotspot_end_time: new Date(hotspot.hotspot_end_time),
          createdon: new Date(),
        },
      });
    }

    return { message: 'Itinerary created', id: plan.itinerary_plan_ID };
  }

  async findOne(id: number) {
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: id },
    });

    if (!plan) throw new NotFoundException('Itinerary not found');

    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: id },
    });

    const hotspots = await this.prisma.dvi_itinerary_route_hotspot_details.findMany({
      where: { itinerary_plan_ID: id },
    });

    return { plan, routes, hotspots };
  }

  async update(id: number, dto: UpdateItineraryDto) {
    // Only update top-level plan fields for now; routes/hotspots can use separate endpoints if needed.
    const data: any = {
      updatedon: new Date(),
    };

    if (dto.agent_id !== undefined) data.agent_id = dto.agent_id;
    if (dto.staff_id !== undefined) data.staff_id = dto.staff_id;
    if (dto.arrival_location !== undefined) data.arrival_location = dto.arrival_location;
    if (dto.departure_location !== undefined) data.departure_location = dto.departure_location;
    if (dto.trip_start_date_and_time !== undefined) {
      data.trip_start_date_and_time = new Date(dto.trip_start_date_and_time as any);
    }
    if (dto.trip_end_date_and_time !== undefined) {
      data.trip_end_date_and_time = new Date(dto.trip_end_date_and_time as any);
    }
    if (dto.expecting_budget !== undefined) data.expecting_budget = dto.expecting_budget as any;
    if (dto.itinerary_type !== undefined) data.itinerary_type = dto.itinerary_type;
    if (dto.total_adult !== undefined) data.total_adult = dto.total_adult;
    if (dto.total_children !== undefined) data.total_children = dto.total_children;
    if (dto.total_infants !== undefined) data.total_infants = dto.total_infants;
    if (dto.meal_plan_breakfast !== undefined) data.meal_plan_breakfast = dto.meal_plan_breakfast;
    if (dto.meal_plan_lunch !== undefined) data.meal_plan_lunch = dto.meal_plan_lunch;
    if (dto.meal_plan_dinner !== undefined) data.meal_plan_dinner = dto.meal_plan_dinner;

    const updated = await this.prisma.dvi_itinerary_plan_details.update({
      where: { itinerary_plan_ID: id },
      data,
    });

    return { message: 'Updated', updated };
  }
}
