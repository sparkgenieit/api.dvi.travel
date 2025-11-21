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

export class CreateItineraryDto {
  @ApiProperty()
  agent_id!: number;

  @ApiProperty()
  staff_id!: number;

  @ApiProperty()
  arrival_location!: string;

  @ApiProperty()
  departure_location!: string;

  @ApiProperty()
  trip_start_date_and_time!: string;

  @ApiProperty()
  trip_end_date_and_time!: string;

  @ApiProperty()
  expecting_budget!: number;

  @ApiProperty()
  itinerary_type!: number;

  @ApiProperty()
  total_adult!: number;

  @ApiProperty()
  total_children!: number;

  @ApiProperty()
  total_infants!: number;

  @ApiProperty()
  meal_plan_breakfast!: number;

  @ApiProperty()
  meal_plan_lunch!: number;

  @ApiProperty()
  meal_plan_dinner!: number;

  @ApiProperty({ type: [ItineraryRouteDto] })
  routes!: ItineraryRouteDto[];

  @ApiProperty({ type: [ItineraryHotspotDto] })
  hotspots!: ItineraryHotspotDto[];
}
