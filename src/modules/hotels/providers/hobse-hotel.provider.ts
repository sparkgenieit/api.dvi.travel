import { Injectable, InternalServerErrorException, Logger } from '@nestjs/common';
import axios, { AxiosInstance } from 'axios';
import { PrismaService } from '../../../prisma.service';
import {
  IHotelProvider,
  HotelSearchResult,
  RoomType,
  HotelSearchCriteria,
  HotelPreferences,
  HotelConfirmationDTO,
  HotelConfirmationResult,
  HotelConfirmationDetails,
  CancellationResult,
} from '../interfaces/hotel-provider.interface';

interface HobseRequest {
  hobse: {
    version: string;
    datetime: string;
    clientToken: string;
    accessToken: string;
    productToken: string;
    request: {
      method: string;
      data: any;
    };
  };
}

interface HobseResponse {
  hobse: {
    version: string;
    datetime: string;
    response: {
      status: {
        success: string;
        code: string;
        message: string;
      };
      totalRecords: number;
      data: any[];
    };
    request: any;
  };
}

@Injectable()
export class HobseHotelProvider implements IHotelProvider {
  private readonly BASE_URL = process.env.HOBSE_BASE_URL || 'https://api.hobse.com';
  private readonly CLIENT_TOKEN = process.env.HOBSE_CLIENT_TOKEN || '';
  private readonly ACCESS_TOKEN = process.env.HOBSE_ACCESS_TOKEN || '';
  private readonly PRODUCT_TOKEN = process.env.HOBSE_PRODUCT_TOKEN || '';

  private logger = new Logger(HobseHotelProvider.name);
  private http: AxiosInstance = axios;

  constructor(private readonly prisma: PrismaService) {
    this.logger.log('🏨 HOBSE Hotel Provider initialized');
    this.logger.log(`Using endpoint: ${this.BASE_URL}`);
  }

  getName(): string {
    return 'HOBSE';
  }

  /**
   * Build HOBSE request wrapper
   */
  private buildRequest(method: string, data: any): HobseRequest {
    return {
      hobse: {
        version: '1.0',
        datetime: new Date().toISOString().replace('Z', '+05:30'),
        clientToken: this.CLIENT_TOKEN,
        accessToken: this.ACCESS_TOKEN,
        productToken: this.PRODUCT_TOKEN,
        request: {
          method,
          data: {
            ...data,
            resultType: 'json',
          },
        },
      },
    };
  }

  /**
   * Make HOBSE API call
   */
  private async makeRequest(method: string, data: any): Promise<HobseResponse> {
    try {
      const request = this.buildRequest(method, data);
      
      this.logger.debug(`📤 HOBSE Request: ${method}`);
      this.logger.debug(`   Data: ${JSON.stringify(data)}`);

      const response = await this.http.post<HobseResponse>(
        this.BASE_URL,
        request,
        {
          headers: {
            'Content-Type': 'application/json',
          },
          timeout: 30000,
        }
      );

      // Check response status
      if (response.data?.hobse?.response?.status?.success !== 'true') {
        const message = response.data?.hobse?.response?.status?.message || 'Unknown error';
        throw new Error(`HOBSE API Error: ${message}`);
      }

      this.logger.debug(`📥 HOBSE Response: Success`);
      return response.data;
    } catch (error) {
      this.logger.error(`❌ HOBSE API Error: ${error.message}`);
      throw new InternalServerErrorException(`HOBSE API failed: ${error.message}`);
    }
  }

  /**
   * Search hotels
   */
  async search(
    criteria: HotelSearchCriteria,
    preferences?: HotelPreferences,
  ): Promise<HotelSearchResult[]> {
    try {
      this.logger.log(`\n   📡 HOBSE PROVIDER: Starting hotel search for city: ${criteria.cityCode}`);

      // Step 1: Get HOBSE city mapping
      const hobseCity = await this.prisma.dvi_cities.findFirst({
        where: { hobse_city_code: criteria.cityCode },
      });

      if (!hobseCity?.hobse_city_code) {
        this.logger.warn(`   ⚠️  City ${criteria.cityCode} not mapped to HOBSE`);
        return [];
      }

      this.logger.log(`   🗺️  City Mapping: HOBSE ${hobseCity.hobse_city_code} → ${hobseCity.name}`);

      // Step 2: Get hotel list for city
      const hotelListResponse = await this.makeRequest('htl/GetHotelList', {});
      const hotels = hotelListResponse.hobse.response.data || [];

      // Filter hotels by city
      const cityHotels = hotels.filter(
        (h: any) => h.cityName?.toLowerCase() === hobseCity.name.toLowerCase()
      );

      if (cityHotels.length === 0) {
        this.logger.warn(`   📭 No HOBSE hotels found for city: ${hobseCity.name}`);
        return [];
      }

      this.logger.log(`   ✅ Found ${cityHotels.length} HOBSE hotels in ${hobseCity.name}`);

      // Step 3: Get available room tariffs for each hotel
      const searchResults: HotelSearchResult[] = [];

      for (const hotel of cityHotels) {
        try {
          const roomResult = await this.searchHotelRooms(
            hotel.hotelId,
            hotel.hotelName,
            criteria
          );
          if (roomResult) {
            searchResults.push(roomResult);
          }
        } catch (error) {
          this.logger.error(`   ❌ Error searching hotel ${hotel.hotelId}: ${error.message}`);
        }
      }

      this.logger.log(`   ✅ Returning ${searchResults.length} HOBSE hotels with availability`);
      return searchResults;
    } catch (error) {
      this.logger.error(`   ❌ HOBSE search error: ${error.message}`);
      return [];
    }
  }

  /**
   * Search available rooms for a specific hotel
   */
  private async searchHotelRooms(
    hotelId: string,
    hotelName: string,
    criteria: HotelSearchCriteria
  ): Promise<HotelSearchResult | null> {
    try {
      // Get available room tariff
      const tariffResponse = await this.makeRequest('htl/GetAvailableRoomTariff', {
        hotelId,
        checkInDate: criteria.checkInDate,
        checkOutDate: criteria.checkOutDate,
        noOfRooms: criteria.roomCount,
        noOfGuests: criteria.guestCount,
      });

      const roomData = tariffResponse.hobse.response.data;
      if (!roomData || roomData.length === 0) {
        return null;
      }

      // Get hotel info for details
      const hotelInfo = await this.makeRequest('htl/GetHotelInfo', { hotelId });
      const hotelDetails = hotelInfo.hobse.response.data[0]?.hotelInfo?.[0];

      // Parse available rooms
      const roomTypes: RoomType[] = [];
      let minPrice = Infinity;

      for (const room of roomData) {
        const price = parseFloat(room.totalCost || room.tariff || '0');
        if (price > 0 && price < minPrice) {
          minPrice = price;
        }

        roomTypes.push({
          roomCode: room.roomCode || room.occupancyCode,
          roomName: room.roomName || 'Standard Room',
          bedType: room.occupancyName || 'Standard',
          capacity: room.maxPaxCount || 2,
          price,
          cancellationPolicy: room.cancellationPolicy || 'As per hotel policy',
        });
      }

      if (roomTypes.length === 0) {
        return null;
      }

      return {
        provider: 'HOBSE',
        hotelCode: hotelId,
        hotelName: hotelName,
        cityCode: criteria.cityCode,
        address: hotelDetails?.address || '',
        rating: parseInt(hotelDetails?.starCategory || '0'),
        category: `${hotelDetails?.starCategory || '0'}-Star`,
        facilities: this.extractFacilities(hotelDetails),
        images: this.extractImages(hotelInfo.hobse.response.data[0]?.images),
        price: minPrice === Infinity ? 0 : minPrice,
        currency: 'INR',
        roomTypes,
        searchReference: `HOBSE-${hotelId}-${Date.now()}`,
        expiresAt: new Date(Date.now() + 30 * 60 * 1000), // 30 minutes
      };
    } catch (error) {
      this.logger.error(`   ❌ Error getting room tariff for ${hotelId}: ${error.message}`);
      return null;
    }
  }

  /**
   * Extract facilities from hotel details
   */
  private extractFacilities(hotelDetails: any): string[] {
    const facilities: string[] = [];
    
    try {
      const attributes = hotelDetails?.hotelAttributes || {};
      
      // Extract from different attribute categories
      ['hotelFeatures', 'roomAmenities', 'businessServices', 'recreationServices'].forEach(category => {
        if (attributes[category]) {
          const items = Array.isArray(attributes[category]) ? attributes[category] : [];
          items.forEach((item: any) => {
            if (item.name) facilities.push(item.name);
          });
        }
      });
    } catch (error) {
      this.logger.debug(`Could not extract facilities: ${error.message}`);
    }

    return facilities.slice(0, 10); // Return top 10
  }

  /**
   * Extract images from hotel data
   */
  private extractImages(images: any): string[] {
    const imageUrls: string[] = [];
    
    try {
      if (images?.hotelImages) {
        images.hotelImages.forEach((img: any) => {
          if (img.imagePath) imageUrls.push(img.imagePath);
          if (img.hotelLogoPath) imageUrls.push(img.hotelLogoPath);
        });
      }

      if (images?.roomImages) {
        images.roomImages.forEach((img: any) => {
          if (img.imagePath) imageUrls.push(img.imagePath);
        });
      }
    } catch (error) {
      this.logger.debug(`Could not extract images: ${error.message}`);
    }

    return imageUrls.slice(0, 5); // Return top 5
  }

  /**
   * Confirm hotel booking
   */
  async confirmBooking(
    bookingDetails: HotelConfirmationDTO,
  ): Promise<HotelConfirmationResult> {
    try {
      this.logger.log(`\n   📋 HOBSE: Confirming booking for hotel ${bookingDetails.hotelCode}`);

      // Step 1: Calculate reservation cost
      const costResponse = await this.makeRequest('htl/CalculateReservationCost', {
        hotelId: bookingDetails.hotelCode,
        roomCode: bookingDetails.rooms[0]?.roomCode,
        checkInDate: bookingDetails.checkInDate,
        checkOutDate: bookingDetails.checkOutDate,
        noOfRooms: bookingDetails.roomCount,
      });

      // Step 2: Create booking
      const bookingData = {
        hotelId: bookingDetails.hotelCode,
        roomCode: bookingDetails.rooms[0]?.roomCode,
        checkInDate: bookingDetails.checkInDate,
        checkOutDate: bookingDetails.checkOutDate,
        noOfRooms: bookingDetails.roomCount,
        guestDetails: bookingDetails.guests.map(g => ({
          firstName: g.firstName,
          lastName: g.lastName,
          email: g.email,
          phone: g.phone,
        })),
        contactPerson: {
          name: bookingDetails.contactName,
          email: bookingDetails.contactEmail,
          phone: bookingDetails.contactPhone,
        },
      };

      const bookingResponse = await this.makeRequest('htl/CreateBooking', bookingData);
      const bookingResult = bookingResponse.hobse.response.data[0];

      this.logger.log(`   ✅ HOBSE booking confirmed: ${bookingResult.bookingId}`);

      return {
        provider: 'HOBSE',
        confirmationReference: bookingResult.bookingId,
        hotelCode: bookingDetails.hotelCode,
        hotelName: bookingResult.hotelName || '',
        checkIn: bookingDetails.checkInDate,
        checkOut: bookingDetails.checkOutDate,
        roomCount: bookingDetails.roomCount,
        totalPrice: parseFloat(bookingResult.totalAmount || '0'),
        priceBreadown: {
          roomCharges: parseFloat(bookingResult.roomCharges || '0'),
          taxes: parseFloat(bookingResult.taxes || '0'),
          discounts: 0,
        },
        cancellationPolicy: 'As per hotel policy',
        status: 'confirmed',
        bookingDeadline: bookingDetails.checkInDate,
      };
    } catch (error) {
      this.logger.error(`   ❌ HOBSE booking failed: ${error.message}`);
      throw new InternalServerErrorException(`HOBSE booking failed: ${error.message}`);
    }
  }

  /**
   * Cancel booking
   */
  async cancelBooking(confirmationRef: string, reason: string): Promise<CancellationResult> {
    try {
      this.logger.log(`\n   ❌ HOBSE: Cancelling booking ${confirmationRef}`);

      const response = await this.makeRequest('htl/SetBookingStatus', {
        bookingId: confirmationRef,
        status: 'cancelled',
        remarks: reason,
      });

      const result = response.hobse.response.data[0];

      this.logger.log(`   ✅ HOBSE booking cancelled: ${confirmationRef}`);

      return {
        cancellationRef: confirmationRef,
        refundAmount: parseFloat(result.refundAmount || '0'),
        charges: parseFloat(result.cancellationCharges || '0'),
        refundDays: result.refundDays || 7,
      };
    } catch (error) {
      this.logger.error(`   ❌ HOBSE cancellation failed: ${error.message}`);
      throw new InternalServerErrorException(`HOBSE cancellation failed: ${error.message}`);
    }
  }

  /**
   * Get confirmation details
   */
  async getConfirmation(confirmationRef: string): Promise<HotelConfirmationDetails> {
    try {
      this.logger.log(`\n   📋 HOBSE: Getting booking details ${confirmationRef}`);

      const response = await this.makeRequest('htl/GetBooking', {
        bookingId: confirmationRef,
      });

      const booking = response.hobse.response.data[0];

      return {
        confirmationRef: confirmationRef,
        hotelName: booking.hotelName,
        checkIn: booking.checkInDate,
        checkOut: booking.checkOutDate,
        roomCount: booking.noOfRooms,
        totalPrice: parseFloat(booking.totalAmount || '0'),
        status: booking.status || 'confirmed',
        cancellationPolicy: 'As per hotel policy',
      };
    } catch (error) {
      this.logger.error(`   ❌ HOBSE get confirmation failed: ${error.message}`);
      throw new InternalServerErrorException(`HOBSE get confirmation failed: ${error.message}`);
    }
  }
}
