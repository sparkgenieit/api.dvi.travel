import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';

interface RouteValidation {
  routeIndex: number;
  locationName: string;
  nextVisitingLocation: string;
  hasHotels: boolean;
  hotelCount: number;
}

@Injectable()
export class RouteValidationService {
  constructor(private readonly prisma: PrismaService) {}

  /**
   * Validates that all overnight locations have available hotels
   * Throws BadRequestException with details if validation fails
   */
  async validateHotelAvailability(
    routes: Array<{ location_name: string; next_visiting_location: string }>,
    preferredCategory: number = 2
  ): Promise<RouteValidation[]> {
    const validations: RouteValidation[] = [];
    const totalRoutes = routes.length;
    
    for (let i = 0; i < routes.length; i++) {
      const route = routes[i];
      const isLastRoute = (i === totalRoutes - 1);
      
      // Skip last route - no overnight stay needed on departure day
      if (isLastRoute) {
        validations.push({
          routeIndex: i,
          locationName: route.location_name,
          nextVisitingLocation: route.next_visiting_location,
          hasHotels: true, // Not needed
          hotelCount: -1, // N/A
        });
        continue;
      }
      
      // Hotel is booked at next_visiting_location (destination city)
      const city = route.next_visiting_location;
      
      // STRATEGY 1: Extract city name from complex strings like "ECR Beach, Chennai, Tamil Nadu"
      // Common patterns: "Place, City, State" or "City, State" or just "City"
      let citySearchTerms: string[] = [city];
      
      // If city contains comma, extract individual parts
      if (city.includes(',')) {
        const parts = city.split(',').map(p => p.trim());
        citySearchTerms.push(...parts);
        
        // For patterns like "ECR Beach, Chennai, Tamil Nadu", the actual city is usually the middle part
        if (parts.length >= 2) {
          citySearchTerms.push(parts[1]); // "Chennai" from "ECR Beach, Chennai, Tamil Nadu"
        }
      }
      
      // Also try case variants
      const allSearchTerms = citySearchTerms.flatMap(term => [
        term,
        term.toUpperCase(),
        term.toLowerCase(),
        term.charAt(0).toUpperCase() + term.slice(1).toLowerCase()
      ]);
      
      let hotelCount = 0;
      
      // STRATEGY 2: Try exact matches on hotel_city field (location IDs are stored as strings)
      for (const searchTerm of allSearchTerms) {
        const hotels = await (this.prisma as any).dvi_hotel.findMany({
          where: {
            hotel_category: preferredCategory,
            hotel_city: searchTerm,
            status: 1,
            // deleted is nullable boolean in Prisma; treat NULL as not-deleted
            deleted: { not: true },
          },
          select: { hotel_id: true },
        });
        
        if (hotels.length > 0) {
          hotelCount = hotels.length;
          break;
        }
      }
      
      // STRATEGY 3: If still no hotels, try LIKE search on hotel address/name
      if (hotelCount === 0) {
        // Try each city term in the address/name
        for (const searchTerm of citySearchTerms) {
          const hotelsLike = await this.prisma.$queryRaw<Array<{ count: bigint }>>`
            SELECT COUNT(*) as count
            FROM dvi_hotel
            WHERE hotel_category = ${preferredCategory}
              AND status = 1
              AND (deleted = 0 OR deleted IS NULL)
              AND (hotel_city LIKE ${`%${searchTerm}%`}
                OR hotel_name LIKE ${`%${searchTerm}%`}
                OR hotel_address LIKE ${`%${searchTerm}%`})
          `;
          hotelCount = Number(hotelsLike[0]?.count || 0);
          if (hotelCount > 0) break;
        }
      }
      
      validations.push({
        routeIndex: i,
        locationName: route.location_name,
        nextVisitingLocation: route.next_visiting_location,
        hasHotels: hotelCount > 0,
        hotelCount,
      });
    }
    
    // Check if any overnight location has no hotels
    const missingHotels = validations.filter(v => v.hotelCount === 0);
    
    if (missingHotels.length > 0) {
      const cities = missingHotels.map(v => v.nextVisitingLocation).join(', ');
      const details = missingHotels.map(v => 
        `Day ${v.routeIndex + 1}: ${v.locationName} → ${v.nextVisitingLocation} (0 hotels found)`
      ).join('\n');
      
      throw new BadRequestException({
        message: `No hotels available in the following cities: ${cities}`,
        details: details,
        missingHotels: missingHotels.map(v => ({
          day: v.routeIndex + 1,
          city: v.nextVisitingLocation,
        })),
        suggestion: 'Please choose cities with available hotels or adjust your route.',
      });
    }
    
    return validations;
  }

  /**
   * Checks if hotels exist in a specific city
   */
  async hasHotelsInCity(city: string, preferredCategory: number = 2): Promise<boolean> {
    // Extract city name from complex strings like "ECR Beach, Chennai, Tamil Nadu"
    let citySearchTerms: string[] = [city];
    
    if (city.includes(',')) {
      const parts = city.split(',').map(p => p.trim());
      citySearchTerms.push(...parts);
      if (parts.length >= 2) {
        citySearchTerms.push(parts[1]); // Extract middle part as primary city
      }
    }
    
    const allSearchTerms = citySearchTerms.flatMap(term => [
      term,
      term.toUpperCase(),
      term.toLowerCase(),
      term.charAt(0).toUpperCase() + term.slice(1).toLowerCase()
    ]);
    
    for (const searchTerm of allSearchTerms) {
      const count = await (this.prisma as any).dvi_hotel.count({
        where: {
          hotel_category: preferredCategory,
          hotel_city: searchTerm,
          status: 1,
          // deleted is nullable boolean in Prisma; treat NULL as not-deleted
          deleted: { not: true },
        },
      });
      
      if (count > 0) return true;
    }
    
    // Try LIKE search on address/name as fallback
    for (const searchTerm of citySearchTerms) {
      const hotelsLike = await this.prisma.$queryRaw<Array<{ count: bigint }>>`
        SELECT COUNT(*) as count
        FROM dvi_hotel
        WHERE hotel_category = ${preferredCategory}
          AND status = 1
          AND (deleted = 0 OR deleted IS NULL)
          AND (hotel_city LIKE ${`%${searchTerm}%`}
            OR hotel_name LIKE ${`%${searchTerm}%`}
            OR hotel_address LIKE ${`%${searchTerm}%`})
      `;
      
      if (Number(hotelsLike[0]?.count || 0) > 0) return true;
    }
    
    return false;
  }
}
