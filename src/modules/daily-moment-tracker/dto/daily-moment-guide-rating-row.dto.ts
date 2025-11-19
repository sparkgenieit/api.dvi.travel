// FILE: src/modules/daily-moment-tracker/dto/daily-moment-guide-rating-row.dto.ts

export class DailyMomentGuideRatingRowDto {
  count!: number;

  itinerary_plan_ID!: number;
  itinerary_route_ID!: number;

  route_date!: string; // dd-mm-YYYY
  location_name!: string | null;
  next_visiting_location!: string | null;

  guide_rating!: number;
  guide_description!: string | null;

  // guide_feedback_ID
  modify!: number;
}
