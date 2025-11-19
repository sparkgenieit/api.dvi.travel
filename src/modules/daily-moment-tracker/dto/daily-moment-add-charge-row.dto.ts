// FILE: src/modules/daily-moment-tracker/dto/daily-moment-add-charge-row.dto.ts

export class DailyMomentAddChargeRowDto {
  count!: number;

  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;

  route_date!: string; // dd-mm-YYYY
  location_name!: string | null;
  next_visiting_location!: string | null;

  charge_type!: string;
  charge_amount!: string | number;

  // maps to driver_charge_ID in PHP (used for edit/delete)
  modify!: number;
}
