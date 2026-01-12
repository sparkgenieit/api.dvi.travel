import {
  Injectable,
  BadRequestException,
  InternalServerErrorException,
  Logger,
} from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import {
  IHotelProvider,
  HotelSearchResult,
  HotelSearchCriteria,
  HotelPreferences,
} from '../interfaces/hotel-provider.interface';
import { HotelSearchDTO } from '../dto/hotel.dto';
import { TBOHotelProvider } from '../providers/tbo-hotel.provider';

@Injectable()
export class HotelSearchService {
  private providers: Map<string, IHotelProvider>;
  private readonly logger = new Logger(HotelSearchService.name);

  constructor(
    private prisma: PrismaService,
    private tboProvider: TBOHotelProvider,
  ) {
    this.providers = new Map([
      ['tbo', this.tboProvider],
      // Add HOBSE and Revenue providers here when implemented
      // ['hobse', this.hobseProvider],
      // ['revenue', this.revenueProvider],
    ]);
  }

  async searchHotels(searchCriteria: HotelSearchDTO): Promise<HotelSearchResult[]> {
    const startTime = Date.now();
    try {
      const {
        cityCode,
        checkInDate,
        checkOutDate,
        roomCount,
        guestCount,
        providers = ['tbo'], // Default to TBO
      } = searchCriteria;

      this.logger.log('\n🔍 HOTEL SEARCH SERVICE PROCESSING');
      this.logger.log(`📥 Input Criteria:`);
      this.logger.log(`   - City Code: ${cityCode}`);
      this.logger.log(`   - Check-in: ${checkInDate}`);
      this.logger.log(`   - Check-out: ${checkOutDate}`);
      this.logger.log(`   - Rooms: ${roomCount}`);
      this.logger.log(`   - Guests: ${guestCount}`);
      this.logger.log(`   - Providers: ${providers.join(', ')}`);

      // Validation
      if (!cityCode) {
        throw new BadRequestException('City code is required');
      }

      const checkIn = new Date(checkInDate);
      const checkOut = new Date(checkOutDate);

      if (checkIn >= checkOut) {
        throw new BadRequestException('Check-in must be before check-out');
      }

      // Check if dates are in the past
      if (checkIn < new Date()) {
        throw new BadRequestException('Check-in date cannot be in the past');
      }

      // Get eligible providers
      const activeProviders = providers
        .map((p) => this.providers.get(p))
        .filter((p) => p !== undefined);

      if (activeProviders.length === 0) {
        throw new BadRequestException('No valid hotel providers specified');
      }

      this.logger.log(`🔄 Searching across ${activeProviders.length} provider(s): ${activeProviders.map(p => p.getName()).join(', ')}`);

      // Search in parallel across all providers
      const searchPromises = activeProviders.map((provider) =>
        this.executeProviderSearch(
          provider,
          {
            cityCode,
            checkInDate,
            checkOutDate,
            roomCount,
            guestCount,
          },
          searchCriteria.preferences,
        ),
      );

      const results = await Promise.all(searchPromises);
      const allHotels = results.flat();

      if (allHotels.length === 0) {
        this.logger.warn(`⚠️  No hotels found for the given criteria`);
        this.logger.log(`⏱️  Total Time: ${Date.now() - startTime}ms`);
        return [];
      }

      this.logger.log(`✅ Found ${allHotels.length} hotels across all providers`);
      this.logger.log(`⏱️  Provider Search Time: ${Date.now() - startTime}ms`);

      // Deduplicate and rank hotels
      const uniqueHotels = this.deduplicateHotels(allHotels);
      const rankedHotels = this.rankHotels(uniqueHotels, searchCriteria.preferences);

      this.logger.log(`📋 Returning ${rankedHotels.length} unique, ranked hotels`);
      this.logger.log(`⏱️  Total Service Time: ${Date.now() - startTime}ms\n`);

      return rankedHotels;
    } catch (error) {
      const errorMessage = error instanceof Error ? error.message : String(error);
      const errorStack = error instanceof Error ? error.stack : 'No stack trace';
      this.logger.error(`\n❌ Hotel search error: ${errorMessage}`);
      this.logger.error(`Error Stack: ${errorStack}`);
      this.logger.log(`⏱️  Failed After: ${Date.now() - startTime}ms\n`);
      throw error;
    }
  }

  private async executeProviderSearch(
    provider: IHotelProvider,
    criteria: HotelSearchCriteria,
    preferences?: HotelPreferences,
  ): Promise<HotelSearchResult[]> {
    try {
      this.logger.log(`   🔗 [${provider.getName()}] Starting search...`);
      this.logger.log(`   🔗 [${provider.getName()}] Criteria: ${JSON.stringify(criteria)}`);
      const results = await provider.search(criteria, preferences);
      this.logger.log(`   ✅ [${provider.getName()}] Found ${results.length} hotels`);
      if (results.length === 0) {
        this.logger.warn(`   ⚠️  [${provider.getName()}] Returned empty array!`);
      }
      return results;
    } catch (error) {
      const errorMsg = error instanceof Error ? error.message : String(error);
      const errorStack = error instanceof Error ? error.stack : 'No stack';
      this.logger.error(
        `   ❌ [${provider.getName()}] Search failed: ${errorMsg}`,
      );
      this.logger.error(`   Stack: ${errorStack}`);
      // Return empty array instead of failing entire search
      return [];
    }
  }

  private deduplicateHotels(hotels: HotelSearchResult[]): HotelSearchResult[] {
    // Don't deduplicate - keep ALL room types for price tier generation
    // Each room type should be treated as a separate pricing option
    // for the tier generation algorithm
    this.logger.log(`Keeping all ${hotels.length} room types (no deduplication) for price tier diversity`);
    return hotels;
  }

  private rankHotels(
    hotels: HotelSearchResult[],
    preferences?: HotelPreferences,
  ): HotelSearchResult[] {
    return hotels.sort((a, b) => {
      // Priority 1: Rating (if preference set)
      if (preferences?.minRating) {
        const aRatingMatch = a.rating >= preferences.minRating ? 1 : 0;
        const bRatingMatch = b.rating >= preferences.minRating ? 1 : 0;
        if (aRatingMatch !== bRatingMatch) {
          return bRatingMatch - aRatingMatch;
        }
      }

      // Priority 2: Price (ascending)
      if (a.price !== b.price) {
        return a.price - b.price;
      }

      // Priority 3: Rating (descending)
      return b.rating - a.rating;
    });
  }
}
