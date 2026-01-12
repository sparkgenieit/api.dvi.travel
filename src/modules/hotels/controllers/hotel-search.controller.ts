import {
  Controller,
  Post,
  Body,
  Logger,
  HttpCode,
  HttpStatus,
  SetMetadata,
} from '@nestjs/common';
import { HotelSearchService } from '../services/hotel-search.service';
import { HotelSearchDTO } from '../dto/hotel.dto';
import {
  HotelSearchResult,
} from '../interfaces/hotel-provider.interface';

const IS_PUBLIC_KEY = 'isPublic';
const Public = () => SetMetadata(IS_PUBLIC_KEY, true);

@Controller('hotels')
export class HotelSearchController {
  private readonly logger = new Logger(HotelSearchController.name);

  constructor(private hotelSearchService: HotelSearchService) {}

  @Post('search')
  @Public()
  @HttpCode(HttpStatus.OK)
  async searchHotels(@Body() dto: HotelSearchDTO) {
    const startTime = Date.now();
    this.logger.log('\n════════════════════════════════════════════════════════════════════════════════════════');
    this.logger.log('🏨 INCOMING HOTEL SEARCH REQUEST');
    this.logger.log(`📍 Request Timestamp: ${new Date().toISOString()}`);
    this.logger.log(`📋 Request Body:`);
    this.logger.log(`   - City Code: ${dto.cityCode}`);
    this.logger.log(`   - Check-in: ${dto.checkInDate}`);
    this.logger.log(`   - Check-out: ${dto.checkOutDate}`);
    this.logger.log(`   - Rooms: ${dto.roomCount}`);
    this.logger.log(`   - Guests: ${dto.guestCount}`);
    this.logger.log('────────────────────────────────────────────────────────────────────────────────────────');

    try {
      const results = await this.hotelSearchService.searchHotels(dto);
      const duration = Date.now() - startTime;

      const response = {
        success: true,
        message: `Found ${results.length} hotels`,
        data: {
          totalResults: results.length,
          hotels: results,
          filters: {
            ratings: this.extractRatings(results),
            priceRange: this.getPriceRange(results),
            facilities: this.extractFacilities(results),
            providers: this.extractProviders(results),
          },
        },
      };

      this.logger.log('\n✅ HOTEL SEARCH SUCCESS');
      this.logger.log(`📊 Results: ${results.length} hotels found`);
      this.logger.log(`⏱️  Total Duration: ${duration}ms`);
      this.logger.log(`📤 Response Summary: success=true, totalResults=${results.length}`);
      this.logger.log('════════════════════════════════════════════════════════════════════════════════════════\n');

      return response;
    } catch (error) {
      const duration = Date.now() - startTime;
      const errorMessage = error instanceof Error ? error.message : String(error);
      const errorStack = error instanceof Error ? error.stack : 'No stack trace';
      this.logger.error('\n❌ HOTEL SEARCH FAILED');
      this.logger.error(`🚨 Error Message: ${errorMessage}`);
      this.logger.error(`⏱️  Duration: ${duration}ms`);
      this.logger.error(`📋 Error Stack: ${errorStack}`);
      this.logger.log('════════════════════════════════════════════════════════════════════════════════════════\n');
      throw error;
    }
  }

  private extractRatings(hotels: HotelSearchResult[]): number[] {
    const ratings = new Set(hotels.map((h) => h.rating).filter((r) => r > 0));
    return Array.from(ratings).sort((a, b) => a - b);
  }

  private getPriceRange(
    hotels: HotelSearchResult[],
  ): { min: number; max: number } {
    const prices = hotels.map((h) => h.price);
    return {
      min: Math.min(...prices),
      max: Math.max(...prices),
    };
  }

  private extractFacilities(hotels: HotelSearchResult[]): string[] {
    const facilities = new Set<string>();
    hotels.forEach((h) => {
      if (h.facilities && Array.isArray(h.facilities)) {
        h.facilities.forEach((f) => {
          if (f && typeof f === 'string') {
            facilities.add(f.trim());
          }
        });
      }
    });
    return Array.from(facilities).sort();
  }

  private extractProviders(hotels: HotelSearchResult[]): string[] {
    const providers = new Set(hotels.map((h) => h.provider));
    return Array.from(providers).sort();
  }
}
