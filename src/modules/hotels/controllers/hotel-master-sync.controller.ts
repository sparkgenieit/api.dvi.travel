import { Controller, Post, Get, Param, Logger } from '@nestjs/common';
import { TboSoapSyncService } from '../services/tbo-soap-sync.service';
import { HobseHotelMasterSyncService } from '../services/hobse-hotel-master-sync.service';

/**
 * Hotel Master Sync Controller
 *
 * Endpoints to sync TBO city and hotel master data from SOAP v7 APIs
 * + HOBSE master sync into dvi_hotel
 */
@Controller('hotels/sync')
export class HotelMasterSyncController {
  private logger = new Logger(HotelMasterSyncController.name);

  constructor(
    private readonly tboSoapService: TboSoapSyncService,
    private readonly hobseHotelMasterSyncService: HobseHotelMasterSyncService,
  ) {}

  /**
   * Sync all cities and hotels from TBO
   * POST /api/v1/hotels/sync/all
   */
  @Post('all')
  async syncAllCitiesAndHotels() {
    try {
      this.logger.log('🔄 Starting full sync of cities and hotels from TBO SOAP API...');
      const results = await this.tboSoapService.syncAllCities();

      const summary = Array.from(results.entries()).map(([city, count]) => ({
        city,
        hotelsAdded: count,
      }));

      return {
        success: true,
        message: 'Cities and hotels synced successfully',
        summary,
        totalHotels: Array.from(results.values()).reduce((a, b) => a + b, 0),
      };
    } catch (error) {
      this.logger.error(`❌ Sync failed: ${error.message}`);
      return {
        success: false,
        message: `Sync failed: ${error.message}`,
      };
    }
  }

  /**
   * Sync hotels for a specific city
   * POST /api/v1/hotels/sync/city/:cityCode
   */
  @Post('city/:cityCode')
  async syncCity(@Param('cityCode') cityCode: string) {
    try {
      this.logger.log(`🔄 Starting sync for city: ${cityCode}`);
      const count = await this.tboSoapService.syncHotelsForCity(cityCode);
      return {
        success: true,
        message: `Synced ${count} hotels for city ${cityCode}`,
        hotelCount: count,
      };
    } catch (error) {
      this.logger.error(`❌ Sync failed: ${error.message}`);
      return {
        success: false,
        message: `Sync failed: ${error.message}`,
      };
    }
  }

  /**
   * Get current hotel master data count
   * GET /api/v1/hotels/sync/master-data/count
   */
  @Get('master-data/count')
  async getHotelCount() {
    const count = await this.tboSoapService.getHotelCount();
    return {
      hotelCount: count,
      message: `Total hotels in master database: ${count}`,
    };
  }

  // -------------------- HOBSE --------------------

  /**
   * Sync all hotels from HOBSE into dvi_hotel
   * POST /api/v1/hotels/sync/hobse/all
   */
  @Post('hobse/all')
  async syncHobseAllHotels() {
    try {
      this.logger.log('🔄 Starting HOBSE hotel sync into dvi_hotel...');
      const result = await this.hobseHotelMasterSyncService.syncAllHotelsToDviHotel();
      return {
        success: true,
        message: 'HOBSE hotels synced into dvi_hotel successfully',
        ...result,
      };
    } catch (error) {
      this.logger.error(`❌ HOBSE sync failed: ${error.message}`);
      return {
        success: false,
        message: `HOBSE sync failed: ${error.message}`,
      };
    }
  }
}
