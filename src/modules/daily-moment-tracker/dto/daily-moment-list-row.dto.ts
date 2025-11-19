// FILE: src/modules/daily-moment-tracker/dto/daily-moment-list-row.dto.ts

export type TripType = 'Arrival' | 'Departure' | 'Ongoing';

export class DailyMomentListRowDto {
  count!: number;

  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;
  itinerary_quote_ID!: string | number;

  route_date!: string; // formatted "dd-mm-YYYY"
  trip_type!: TripType;

  location_name!: string | null;
  next_visiting_location!: string | null;

  // Optional fields that PHP fills via helper functions
  guest_name?: string | null;
  arrival_flight_details?: string | null;
  departure_flight_details?: string | null;

  hotel_name?: string | null;
  vehicle_type_title?: string | null;
  vendor_name?: string | null;
  meal_plan?: string | null;

  vehicle_no?: string | null;
  driver_name?: string | null;
  driver_mobile?: string | null;

  special_remarks?: string | null;
  travel_expert_name?: string | null;
  agent_name?: string | null;
}
