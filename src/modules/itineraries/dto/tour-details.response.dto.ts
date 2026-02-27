// FILE: src/modules/itineraries/dto/tour-details.response.dto.ts

export class TourDetailsResponseDto {
  quoteId: string;

  startDate: string; // "05 May 2026"
  endDate: string;   // "08 May 2026"

  tripNights: number; // 3
  tripDays: number;   // 4

  entryTicketRequired: boolean; // true/false

  nationality: {
    id: number | null;     // from plan.nationality (if present)
    label: string | null;  // resolved from stored table if you have it, else null
  };

  pax: {
    adults: number;
    children: number;
    infants: number;
    total: number;
  };

  roomCount: number;
}