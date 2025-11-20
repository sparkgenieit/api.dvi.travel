// FILE: src/modules/daily-moment-tracker/dto/daily-moment-tracker.dto.ts

import { Type } from 'class-transformer';
import { IsDateString, IsInt, IsNotEmpty, IsNumber, IsOptional, IsString, Min } from 'class-validator';

export class ListDailyMomentQueryDto {
  @IsNotEmpty()
  @IsString()
  fromDate!: string; // supports "YYYY-MM-DD" or "DD-MM-YYYY" (we parse in service)

  @IsNotEmpty()
  @IsString()
  toDate!: string;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(0)
  itineraryPlanId?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(0)
  agentId?: number;
}

export class DailyMomentRowDto {
  count!: number;
  guest_name!: string;
  quote_id!: string | null;
  itinerary_plan_ID!: number;
  route_date!: string; // dd-mm-YYYY (formatted like PHP)
  trip_type!: 'Arrival' | 'Departure' | 'Ongoing';
  location_name!: string | null;
  next_visiting_location!: string | null;
  arrival_flight_details!: string;
  departure_flight_details!: string;
  hotel_name!: string;
  vehicle_type_title!: string;
  vendor_name!: string;
  meal_plan!: string; // e.g. "B L D"
  vehicle_no!: string;
  driver_name!: string;
  driver_mobile!: string;
  special_remarks!: string;
  travel_expert_name!: string;
  agent_name!: string;
}

/**
 * Add / Update extra charges (car icon popup)
 */
export class UpsertDailyMomentChargeDto {
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  driverChargeId?: number; // maps to driver_charge_ID

  @Type(() => Number)
  @IsInt()
  itineraryPlanId!: number; // itinerary_plan_ID

  @Type(() => Number)
  @IsInt()
  itineraryRouteId!: number; // itinerary_route_ID

  @IsNotEmpty()
  @IsString()
  chargeType!: string; // charge_type

  @Type(() => Number)
  @IsNumber()
  chargeAmount!: number; // charge_amount
}

export class DailyMomentChargeRowDto {
  driver_charge_ID!: number;
  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;
  charge_type!: string | null;
  charge_amount!: number;
}

/**
 * Driver Rating listing DTO (per day)
 */
export class DriverRatingRowDto {
  driver_feedback_ID!: number;
  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;
  route_date!: string; // dd-mm-YYYY
  location_name!: string | null;
  next_visiting_location!: string | null;
  driver_rating!: string | null;
  driver_description!: string | null;
}

/**
 * Guide Rating listing DTO (per day, using guide reviews + route_guide mapping)
 */
export class GuideRatingRowDto {
  guide_review_id!: number;
  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;
  route_date!: string; // dd-mm-YYYY
  location_name!: string | null;
  next_visiting_location!: string | null;
  guide_id!: number;
  guide_name!: string | null;
  guide_rating!: string | null;
  guide_description!: string | null;
}

/**
 * Route Hotspot DTO (for the pink cards with Visited / Not-Visited buttons)
 */
export class DailyMomentHotspotRowDto {
  // ordering inside the day (1,2,3,...)
  serial_no!: number;

  // identifiers
  confirmed_route_hotspot_ID!: number;
  route_hotspot_ID!: number;
  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;
  hotspot_ID!: number;

  // display info
  hotspot_name!: string;
  hotspot_location!: string;

  // timing & duration
  start_time!: string;        // e.g. "01:56 PM"
  end_time!: string;          // e.g. "03:26 PM"
  duration_minutes!: number;  // total minutes
  duration_label!: string;    // e.g. "1 Hour 30 Min"

  // visit status (for buttons)
  driver_hotspot_status!: number;               // 0 = Not-Visited, 1 = Visited
  driver_not_visited_description!: string | null;
  guide_hotspot_status!: number;
  guide_not_visited_description!: string | null;
}
