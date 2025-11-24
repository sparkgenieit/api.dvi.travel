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

export class CreateItineraryDto {
  // core IDs
  @ApiProperty()
  agent_id!: number;

  @ApiProperty()
  staff_id!: number;

  // locations
  @ApiProperty()
  arrival_location!: string;

  @ApiProperty()
  departure_location!: string;

  // trip window (ISO strings from frontend)
  @ApiProperty()
  trip_start_date_and_time!: string;

  @ApiProperty()
  trip_end_date_and_time!: string;

  // budget / type
  @ApiProperty()
  expecting_budget!: number;

  @ApiProperty()
  itinerary_type!: number;

  // totals
  @ApiProperty()
  total_adult!: number;

  @ApiProperty()
  total_children!: number;

  @ApiProperty()
  total_infants!: number;

  // meal plan
  @ApiProperty()
  meal_plan_breakfast!: number;

  @ApiProperty()
  meal_plan_lunch!: number;

  @ApiProperty()
  meal_plan_dinner!: number;

  // ---------- EXTRA FIELDS TO MATCH PHP LOGIC ----------

  @ApiProperty({ required: false, description: '1=Hotel, 2=Vehicle, 3=Both' })
  itinerary_preference?: number;

  @ApiProperty({ required: false })
  arrival_type?: number;

  @ApiProperty({ required: false })
  departure_type?: number;

  @ApiProperty({ required: false })
  no_of_days?: number;

  @ApiProperty({ required: false })
  no_of_nights?: number;

  @ApiProperty({ required: false })
  entry_ticket_required?: number;

  @ApiProperty({ required: false })
  guide_for_itinerary?: number;

  @ApiProperty({ required: false })
  nationality?: number;

  @ApiProperty({ required: false, description: '1=Veg, 2=Non-veg, 3=Egg' })
  food_type?: number;

  @ApiProperty({ required: false, description: 'ISO string or date-time text' })
  pick_up_date_and_time?: string;

  @ApiProperty({ required: false })
  special_instructions?: string;

  // collections
  @ApiProperty({ type: [ItineraryRouteDto] })
  routes!: ItineraryRouteDto[];

  @ApiProperty({ type: [ItineraryHotspotDto] })
  hotspots!: ItineraryHotspotDto[];

  @ApiProperty({ type: [ItineraryVehicleDto], required: false })
  vehicles?: ItineraryVehicleDto[];
}
