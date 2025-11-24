// FILE: src/modules/itinerary/dto/create-itinerary.dto.ts
import { ApiProperty } from '@nestjs/swagger';

export class ItineraryRouteDto {
  @ApiProperty()
  location_name!: string;

  @ApiProperty()
  next_visiting_location!: string;

  @ApiProperty()
  itinerary_route_date!: string;

  @ApiProperty()
  no_of_days!: number;

  @ApiProperty()
  no_of_km!: string;

  @ApiProperty()
  direct_to_next_visiting_place!: number;
}

export class ItineraryHotspotDto {
  @ApiProperty()
  hotspot_ID!: number;

  @ApiProperty()
  hotspot_order!: number;

  @ApiProperty()
  hotspot_adult_entry_cost!: number;

  @ApiProperty()
  hotspot_child_entry_cost!: number;

  @ApiProperty()
  hotspot_infant_entry_cost!: number;

  @ApiProperty()
  hotspot_travelling_distance!: string;

  @ApiProperty()
  hotspot_start_time!: string;

  @ApiProperty()
  hotspot_end_time!: string;
}

export class ItineraryVehicleDto {
  @ApiProperty()
  vehicle_type_id!: number;

  @ApiProperty()
  vehicle_count!: number;
}

/**
 * Shape of "plan" in your frontend payload:
 * {
 *   plan: {
 *     agent_id,
 *     arrival_point,
 *     departure_point,
 *     itinerary_preference,
 *     itinerary_type,
 *     trip_start_date,
 *     trip_end_date,
 *     arrival_type,
 *     departure_type,
 *     no_of_nights,
 *     no_of_days,
 *     budget,
 *     entry_ticket_required,
 *     guide_for_itinerary,
 *     nationality,
 *     food_type,
 *     adult_count,
 *     child_count,
 *     infant_count,
 *     pick_up_date_and_time?,
 *     special_instructions?
 *   },
 *   routes: [...],
 *   vehicles: [...]
 * }
 */
export class ItineraryPlanDto {
  @ApiProperty()
  agent_id!: number;

  @ApiProperty({ required: false })
  staff_id?: number;

  @ApiProperty()
  arrival_point!: string;

  @ApiProperty()
  departure_point!: string;

  @ApiProperty()
  itinerary_preference!: number;

  @ApiProperty()
  itinerary_type!: number;

  @ApiProperty()
  trip_start_date!: string;

  @ApiProperty()
  trip_end_date!: string;

  @ApiProperty()
  arrival_type!: number;

  @ApiProperty()
  departure_type!: number;

  @ApiProperty()
  no_of_nights!: number;

  @ApiProperty()
  no_of_days!: number;

  @ApiProperty()
  budget!: number;

  @ApiProperty()
  entry_ticket_required!: number;

  @ApiProperty()
  guide_for_itinerary!: number;

  @ApiProperty()
  nationality!: number;

  @ApiProperty()
  food_type!: number;

  @ApiProperty()
  adult_count!: number;

  @ApiProperty()
  child_count!: number;

  @ApiProperty()
  infant_count!: number;

  @ApiProperty({ required: false })
  pick_up_date_and_time?: string;

  @ApiProperty({ required: false })
  special_instructions?: string;
}

export class CreateItineraryDto {
  @ApiProperty({ type: ItineraryPlanDto })
  plan!: ItineraryPlanDto;

  @ApiProperty({ type: [ItineraryRouteDto] })
  routes!: ItineraryRouteDto[];

  @ApiProperty({ type: [ItineraryHotspotDto], required: false })
  hotspots?: ItineraryHotspotDto[];

  @ApiProperty({ type: [ItineraryVehicleDto], required: false })
  vehicles?: ItineraryVehicleDto[];
}
