// FILE: src/modules/itineraries/itinerary-hotel-details-tbo.service.ts

import { Injectable, NotFoundException, Logger, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../prisma.service';
import { HotelSearchService } from '../hotels/services/hotel-search.service';
import { HotelSearchResult } from '../hotels/interfaces/hotel-provider.interface';
import {
  ItineraryHotelTabDto,
  ItineraryHotelRowDto,
  ItineraryHotelDetailsResponseDto,
} from './itinerary-hotel-details.service';

/**
 * This service generates dynamic hotel packages from TBO API
 * instead of retrieving them from the database
 */
@Injectable()
export class ItineraryHotelDetailsTboService {
  private readonly logger = new Logger(ItineraryHotelDetailsTboService.name);

  constructor(
    private readonly prisma: PrismaService,
    private readonly hotelSearchService: HotelSearchService,
  ) {}

  /**
   * Get hotel details with dynamic packages from TBO API
   * Creates 4 different price tier packages: Budget, Mid-Range, Premium, Luxury
   */
  async getHotelDetailsByQuoteIdFromTbo(
    quoteId: string,
  ): Promise<ItineraryHotelDetailsResponseDto> {
    const startTime = Date.now();
    this.logger.log(`\n📡 TBO HOTEL PACKAGES: Fetching dynamic packages for quote: ${quoteId}`);

    // Step 1: Get itinerary plan
    const plan = await this.prisma.dvi_itinerary_plan_details.findFirst({
      where: { itinerary_quote_ID: quoteId, deleted: 0 },
    });

    if (!plan) {
      this.logger.warn(`⚠️  Quote ID not found: ${quoteId}`);
      throw new NotFoundException('Itinerary not found');
    }

    const planId = plan.itinerary_plan_ID;
    this.logger.log(`✅ Found plan ID: ${planId}`);

    // Step 2: Get itinerary routes (days and destinations)
    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0 },
      orderBy: { itinerary_route_date: 'asc' },
    });

    this.logger.log(`📅 Routes Query Result: ${JSON.stringify({
      total: routes.length,
      routes: routes.map(r => ({ id: (r as any).itinerary_route_ID, location: (r as any).location_name, date: (r as any).itinerary_route_date }))
    })}`);

    if (routes.length === 0) {
      this.logger.warn(`⚠️  No routes found for plan ${planId}`);
      throw new BadRequestException('Itinerary has no routes');
    }

    this.logger.log(`📅 Found ${routes.length} routes to process`);

    // Get number of nights from plan to determine which routes need hotels
    const noOfNights = Number((plan as any).no_of_nights || 0);
    this.logger.log(`🌙 Plan has ${noOfNights} nights`);

    // Step 3: Fetch hotels from TBO for each route (except last route if it's departure day)
    const hotelsByRoute = await this.fetchHotelsForRoutes(routes, noOfNights);
    
    // Debug: Check if any hotels were found
    const hotelEntries = Array.from(hotelsByRoute.entries());
    this.logger.log(`\n📊 HOTEL FETCH RESULTS:`);
    hotelEntries.forEach(([routeId, hotels]) => {
      this.logger.log(`   Route ${routeId}: ${hotels.length} hotels`);
      if (hotels.length > 0) {
        this.logger.log(`      - ${hotels.map(h => h.hotelName).join(', ')}`);
      }
    });
    
    if (hotelEntries.every(([_, hotels]) => hotels.length === 0)) {
      this.logger.warn(`\n❌ WARNING: ALL ROUTES RETURNED ZERO HOTELS!\n`);
    }
    
    this.logger.log(`🏨 Hotels by Route: ${JSON.stringify(Object.fromEntries(hotelsByRoute))}`);

    // Step 4: Generate 4 price tier packages
    const packages = this.generatePricePackages(hotelsByRoute, routes);

    // Step 5: Build response
    const response = this.buildHotelDetailsResponse(
      quoteId,
      planId,
      packages,
      hotelsByRoute,
      routes,
      noOfNights,
    );

    const duration = Date.now() - startTime;
    this.logger.log(`✅ Generated ${packages.length} hotel packages`);
    this.logger.log(`⏱️  Total TBO Service Time: ${duration}ms\n`);

    return response;
  }

  /**
   * Fetch available hotels from TBO for each route
   */
  private async fetchHotelsForRoutes(
    routes: any[],
    noOfNights: number,
  ): Promise<Map<number, HotelSearchResult[]>> {
    const hotelsByRoute = new Map<number, HotelSearchResult[]>();
    const totalRoutes = routes.length;

    for (let routeIndex = 0; routeIndex < routes.length; routeIndex++) {
      const route = routes[routeIndex];
      const routeId = (route as any).itinerary_route_ID;
      
      // Skip hotel generation for the last route (departure day) if routeIndex >= noOfNights
      const isLastRoute = routeIndex === totalRoutes - 1;
      if (isLastRoute && routeIndex >= noOfNights) {
        this.logger.log(`   ⏭️  Skipping route ${routeIndex + 1} (last route - departure day, no hotel needed)`);
        continue;
      }
      
      try {
        // Use next_visiting_location (where you're staying) NOT location_name (where you're departing from)
        const destination = (route as any).next_visiting_location;
        const routeDate = new Date((route as any).itinerary_route_date);

        // Set check-out to next day (standard 1-night stay)
        const checkOutDate = new Date(routeDate);
        checkOutDate.setDate(checkOutDate.getDate() + 1);

        this.logger.log(`   🔍 Route ${routeId} (index ${routeIndex}): Searching hotels for "${destination}" (${routeDate.toISOString().split('T')[0]})`);

        // Map destination to city code (now queries database)
        const cityCode = await this.mapDestinationToCityCode(destination);
        this.logger.log(`   📌 Route ${routeId}: mapDestinationToCityCode returned: "${cityCode}"`);
        
        // Skip searching if city code is empty (couldn't find city in database)
        if (!cityCode || cityCode === '') {
          this.logger.warn(`   ❌ Route ${routeId}: Skipping hotel search for "${destination}" - no city code found`);
          hotelsByRoute.set(routeId, []);
          continue; // Skip to next route
        }
        
        this.logger.log(`      Calling hotelSearchService.searchHotels...`);
        this.logger.log(`         - cityCode: ${cityCode}`);
        this.logger.log(`         - checkInDate: ${routeDate.toISOString().split('T')[0]}`);
        this.logger.log(`         - checkOutDate: ${checkOutDate.toISOString().split('T')[0]}`);

        // Call TBO search API - let TBO return available hotels for this city
        const searchCriteria = {
          cityCode,
          checkInDate: routeDate.toISOString().split('T')[0],
          checkOutDate: checkOutDate.toISOString().split('T')[0],
          roomCount: 1,
          guestCount: 2,
          providers: ['tbo'],
        };
        this.logger.log(`         - About to call searchHotels with: ${JSON.stringify(searchCriteria)}`);
        const hotels = await this.hotelSearchService.searchHotels(searchCriteria);
        this.logger.log(`      ✅ searchHotels returned ${hotels ? hotels.length : 'UNDEFINED'} hotels`);
        if (!hotels || hotels.length === 0) {
          this.logger.warn(`      ⚠️  EMPTY ARRAY RETURNED FROM searchHotels!`);
        }
        hotelsByRoute.set(routeId, hotels || []);
      } catch (error) {
        const errorMsg = error instanceof Error ? error.message : String(error);
        const errorStack = error instanceof Error ? error.stack : 'No stack';
        const fullError = `ERROR: ${errorMsg}\nSTACK: ${errorStack}`;
        this.logger.error(`      ❌ HOTEL SEARCH ERROR: ${fullError}`);
        // Write error to file for debugging
        try {
          const fs = require('fs');
          fs.appendFileSync('d:\\hotel-error.log', `[${new Date().toISOString()}] Route ${routeId}: ${fullError}\n`);
        } catch (e) {}
        hotelsByRoute.set(routeId, []);
      }
    }

    return hotelsByRoute;
  }

  /**
   * Generate 4 price tier packages: Budget, Mid-Range, Premium, Luxury
   * Ensures INCREASING price order: Budget < Mid-Range < Premium < Luxury
   */
  private generatePricePackages(
    hotelsByRoute: Map<number, HotelSearchResult[]>,
    routes: any[],
  ): Array<{ groupType: number; label: string; hotels: Array<HotelSearchResult & { routeId: number }> }> {
    const packages: Array<{
      groupType: number;
      label: string;
      hotels: Array<HotelSearchResult & { routeId: number }>;
    }> = [];

    const labels = [
      'Budget Hotels',
      'Mid-Range Hotels',
      'Premium Hotels',
      'Luxury Hotels',
    ];

    // Debug: Log all available hotels
    this.logger.log(`\n   📊 PRICE TIER GENERATION DEBUG:`);
    this.logger.log(`   Total routes: ${routes.length}`);
    hotelsByRoute.forEach((hotels, routeId) => {
      const prices = hotels.map(h => h.price).join(', ');
      this.logger.log(`   Route ${routeId}: ${hotels.length} hotels (Prices: ${prices})`);
    });

    // Collect ALL unique price points across all routes
    const allPrices = new Set<number>();
    hotelsByRoute.forEach(hotels => {
      hotels.forEach(h => allPrices.add(h.price));
    });
    const uniquePrices = Array.from(allPrices).sort((a, b) => a - b);
    this.logger.log(`   Unique price points available: ${uniquePrices.join(', ')}`);

    // For each price tier
    for (let tier = 0; tier < 4; tier++) {
      const selectedHotels: Array<HotelSearchResult & { routeId: number }> = [];

      // For each route, select hotel that matches this tier's price expectation
      for (const route of routes) {
        const routeId = (route as any).itinerary_route_ID;
        const availableHotels = hotelsByRoute.get(routeId) || [];

        if (availableHotels.length === 0) {
          this.logger.warn(`      ⚠️  No hotels available for route ${routeId}`);
          // CREATE PLACEHOLDER FOR NO HOTELS - price 0
          const placeholderHotel: any = {
            hotelCode: '0',
            hotelName: 'No Hotels Available',
            roomType: '-',
            mealPlan: '-',
            price: 0,
            rating: 0,
            routeId: routeId
          };
          selectedHotels.push(placeholderHotel);
          this.logger.debug(
            `   Tier ${tier + 1}, Route ${routeId}: No hotels - Added placeholder with ₹0`
          );
          continue;
        }

        // Sort by price
        const sortedByPrice = [...availableHotels].sort((a, b) => a.price - b.price);

        // Select hotel based on tier and unique prices available
        let selectedHotel: HotelSearchResult;
        
        if (uniquePrices.length === 1) {
          // Only one price point - use same for all tiers
          selectedHotel = sortedByPrice[0];
        } else if (uniquePrices.length === 2) {
          // Two price points: Budget/Mid-Range use lower, Premium/Luxury use higher
          if (tier < 2) {
            // Budget (0) and Mid-Range (1) - pick from lower price options
            selectedHotel = sortedByPrice[0];
          } else {
            // Premium (2) and Luxury (3) - pick from higher price options
            selectedHotel = sortedByPrice[sortedByPrice.length - 1];
          }
        } else {
          // Multiple price points - distribute evenly
          // Budget = lowest, Mid-Range = lower-mid, Premium = upper-mid, Luxury = highest
          const targetIndex = Math.floor((tier / 3) * (sortedByPrice.length - 1));
          selectedHotel = sortedByPrice[Math.min(targetIndex, sortedByPrice.length - 1)];
        }

        // Attach routeId to track which route this hotel belongs to
        const hotelWithRoute = { ...selectedHotel, routeId } as HotelSearchResult & { routeId: number };
        selectedHotels.push(hotelWithRoute);
        this.logger.debug(
          `   Tier ${tier + 1}, Route ${routeId}: Selected ₹${selectedHotel.price}`
        );
      }

      // Add package even if we only have hotels for SOME routes
      // (routes with no hotels will show as "No Hotels Available")
      if (selectedHotels.length > 0) {
        const totalPrice = selectedHotels.reduce((sum, h) => sum + h.price, 0);
        packages.push({
          groupType: tier + 1,
          label: labels[tier],
          hotels: selectedHotels,
        });
        this.logger.log(`   ✅ ${labels[tier]}: ₹${totalPrice} total (${selectedHotels.length} routes, includes placeholders for routes with no hotels)`);
      } else {
        this.logger.log(`   ❌ ${labels[tier]}: No hotels found for any route (tier SKIPPED)`);
      }
    }

    this.logger.log(`📦 Generated ${packages.length} price tier packages\n`);
    return packages;
  }

  /**
   * Build the response DTO
   */
  private buildHotelDetailsResponse(
    quoteId: string,
    planId: number,
    packages: Array<{ groupType: number; label: string; hotels: Array<HotelSearchResult & { routeId: number }> }>,
    hotelsByRoute: Map<number, HotelSearchResult[]>,
    routes: any[],
    noOfNights: number,
  ): ItineraryHotelDetailsResponseDto {
    // Build hotel tabs (one per package with total cost)
    const hotelTabs: ItineraryHotelTabDto[] = packages.map((pkg) => {
      const totalAmount = pkg.hotels.reduce((sum, h) => sum + h.price, 0);
      return {
        groupType: pkg.groupType,
        label: pkg.label,
        totalAmount,
      };
    });

    // Build hotel rows (detail rows for each package)
    const hotelRows: ItineraryHotelRowDto[] = [];

    for (const pkg of packages) {
      for (const hotel of pkg.hotels) {
        // Find the route using the routeId attached to the hotel
        const route = routes.find((r: any) => r.itinerary_route_ID === hotel.routeId);
        if (!route) {
          this.logger.warn(`⚠️  Route ${hotel.routeId} not found for hotel ${hotel.hotelName}`);
          continue;
        }
        
        // Skip departure day (last route when routeIndex >= noOfNights)
        const routeIndex = routes.indexOf(route);
        const isLastRoute = routeIndex === routes.length - 1;
        if (isLastRoute && routeIndex >= noOfNights) {
          this.logger.log(`   ⏭️  Skipping route ${hotel.routeId} from response (departure day)`);
          continue;
        }
        
        // Use next_visiting_location (where you're staying) for destination display
        const destination = (route as any).next_visiting_location || (route as any).location_name || '';
        
        // Use actual hotel name from TBO API response
        const displayHotelName = hotel.hotelName;

        hotelRows.push({
          groupType: pkg.groupType,
          itineraryRouteId: (route as any).itinerary_route_ID,
          day: `Day ${routeIndex + 1} | ${new Date((route as any).itinerary_route_date).toISOString().split('T')[0]}`,
          destination: destination,
          hotelId: parseInt(hotel.hotelCode) || 0,
          hotelName: displayHotelName,
          category: hotel.rating ? parseInt(String(hotel.rating)) : 0, // Category/star rating
          roomType: hotel.roomType || '', // Room type from TBO response
          mealPlan: hotel.mealPlan || '-', // Meal plan from TBO response if available
          totalHotelCost: Math.round(hotel.price),
          totalHotelTaxAmount: 0, // TBO API doesn't provide tax breakdown
          // TBO booking code for PreBook/Book API calls
          searchReference: hotel.searchReference,
          bookingCode: hotel.searchReference, // Use searchReference as booking code
        });
      }
    }

    return {
      quoteId,
      planId,
      hotelRatesVisible: true,
      hotelTabs,
      hotels: hotelRows,
      totalRoomCount: hotelRows.length,
    };
  }

  /**
   * Map destination name to database city ID by querying dvi_cities table
   * Fetches from database - NO hardcoding!
   * The TBO code will be fetched from dvi_cities.tbo_city_code
   */
  private async mapDestinationToCityCode(destination: string): Promise<string> {
    try {
      // Step 1: Try exact match first
      let city = await this.prisma.dvi_cities.findFirst({
        where: { name: destination },
      });

      if (!city) {
        // Step 2: Try partial match with first part (before comma)
        // E.g., "Hyderabad, Rajiv Gandhi International Airport" → "Hyderabad"
        const firstPart = destination.split(',')[0].trim();
        
        if (firstPart !== destination) {
          this.logger.log(`   Trying partial match: "${firstPart}"`);
          city = await this.prisma.dvi_cities.findFirst({
            where: { name: firstPart },
          });
        }
      }

      if (!city) {
        // Step 3: Try fuzzy match - city name contains destination prefix
        const prefix = destination.split(',')[0].trim().toUpperCase();
        this.logger.log(`   Trying fuzzy match with prefix: "${prefix}"`);
        
        city = await this.prisma.dvi_cities.findFirst({
          where: {
            name: {
              contains: prefix,
            },
          },
        });
      }

      if (city && city.tbo_city_code) {
        this.logger.log(`✅ Found city in database: "${destination}" → Mapped to: "${city.name}" → TBO Code: ${city.tbo_city_code}`);
        return city.tbo_city_code;
      }

      // If still not found or no TBO code, return empty string instead of throwing
      // This allows other routes to continue processing
      if (!city) {
        this.logger.warn(
          `⚠️  SKIPPING: City "${destination}" not found in dvi_cities table. ` +
          `Tried: exact match, first-part match, and fuzzy match. ` +
          `This route will have no hotels.`
        );
      } else {
        this.logger.warn(
          `⚠️  SKIPPING: City "${destination}" found in database but has no TBO city code. ` +
          `This route will have no hotels.`
        );
      }
      // Return empty string instead of throwing error
      // This allows package generation to continue with other routes
      return '';
    } catch (error) {
      const msg = error instanceof Error ? error.message : String(error);
      this.logger.warn(`⚠️  Error mapping destination "${destination}": ${msg}. Skipping this route.`);
      return ''; // Return empty string to allow other routes to continue
    }
  }
}
