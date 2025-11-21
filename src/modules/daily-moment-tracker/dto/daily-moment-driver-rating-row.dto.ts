// FILE: src/modules/daily-moment-tracker/dto/daily-moment-driver-rating-row.dto.ts

export class DailyMomentDriverRatingRowDto {
  count!: number;

  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;

  route_date!: string; // dd-mm-YYYY
  location_name!: string | null;
  next_visiting_location!: string | null;

  // "N Star" in PHP; here we keep rating as number and format in frontend if needed
  customer_rating!: number;
  feedback_description!: string | null;

  // customer_feedback_ID
  modify!: number;
}
