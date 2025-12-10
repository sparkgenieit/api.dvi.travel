export interface TimeSlot {
  startTime: string;
  endTime: string;
}

export interface SpecialDay {
  date: string;
  timeSlots: TimeSlot[];
}

export interface ActivityPricing {
  startDate: string;
  endDate: string;
  adult: number;
  children: number;
  infant: number;
  foreignAdult: number;
  foreignChildren: number;
  foreignInfant: number;
}

export interface ActivityReview {
  id: string;
  rating: number;
  description: string;
  createdOn: string;
}

export interface Activity {
  id: string;
  title: string;
  hotspot: string;
  hotspotPlace: string;
  maxAllowedPersonCount: number;
  duration: string;
  description: string;
  images: string[];
  defaultAvailableTimes: TimeSlot[];
  isSpecialDay: boolean;
  specialDays: SpecialDay[];
  pricing: ActivityPricing;
  reviews: ActivityReview[];
  status: boolean;
}
