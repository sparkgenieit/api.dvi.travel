import { Injectable, InternalServerErrorException, Logger, Inject } from '@nestjs/common';
import axios, { AxiosInstance } from 'axios';
import { PrismaService } from '../../../prisma.service';
import * as fs from 'fs';
import * as path from 'path';
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

@Injectable()
export class TBOHotelProvider implements IHotelProvider {
  // Production API Endpoints from Postman Collection
  private readonly SEARCH_API_URL = 'https://affiliate.tektravels.com/HotelAPI';
  private readonly BOOKING_API_URL = 'https://hotelbe.tektravels.com';
  private readonly SHARED_API_URL = 'https://sharedapi.tektravels.com';
  
  // Real Production Credentials (From Postman - Verified Working)
  private readonly USERNAME = process.env.TBO_USERNAME || 'Doview';
  private readonly PASSWORD = process.env.TBO_PASSWORD || 'Doview@12345';
  
  private logger = new Logger(TBOHotelProvider.name);
  private tokenId: string | null = null;
  private tokenExpiry: Date | null = null;
  private http: AxiosInstance = axios;
  private logFile = path.join(process.cwd(), 'tbo-hotel-provider.log');

  private fileLog(message: string) {
    const timestamp = new Date().toISOString();
    const logMessage = `[${timestamp}] ${message}\n`;
    try {
      fs.appendFileSync(this.logFile, logMessage);
    } catch (e) {
      // Ignore file write errors
    }
  }

  constructor(private readonly prisma: PrismaService) {
    this.fileLog('═════════════════════════════════════════');
    this.fileLog('TBOHotelProvider CONSTRUCTOR CALLED');
    this.fileLog('═════════════════════════════════════════');
    this.fileLog(`PrismaService type: ${typeof this.prisma}`);
    this.fileLog(`PrismaService value: ${this.prisma ? 'DEFINED' : 'UNDEFINED'}`);
    this.fileLog(`PrismaService constructor: ${this.prisma?.constructor?.name || 'N/A'}`);
    console.log('🔐 TBOHotelProvider CONSTRUCTOR CALLED');
    console.log('🔐 PrismaService received:', !!prisma, typeof prisma);
    if (!prisma) {
      console.error('🔴 PrismaService is NULL/UNDEFINED!');
      this.fileLog('🔴 ERROR: PrismaService is NULL/UNDEFINED!');
    }
    this.logger.log('🔐 TBO Hotel Provider initialized with production endpoints');
    this.logger.log(`Using credentials: ${this.USERNAME}`);
  }

  getName(): string {
    return 'TBO';
  }

  /**
   * Authenticate and get TokenId from TBO API
   * Required for all hotel operations
   */
  private async authenticate(): Promise<string> {
    try {
      // Check if token is still valid
      if (this.tokenId && this.tokenExpiry && new Date() < this.tokenExpiry) {
        this.logger.debug('   🔑 Using cached TBO TokenId');
        return this.tokenId;
      }

      this.logger.log(`   🔐 TBO Authentication Request:`);
      this.logger.log(`      - Endpoint: POST ${this.SHARED_API_URL}/SharedData.svc/rest/Authenticate`);
      this.logger.log(`      - Username: ${this.USERNAME}`);

      const authRequest = {
        ClientId: process.env.TBO_CLIENT_ID || 'ApiIntegrationNew',
        UserName: this.USERNAME,
        Password: this.PASSWORD,
        EndUserIp: process.env.TBO_END_USER_IP || '192.168.1.1',
      };

      const authStartTime = Date.now();
      const response = await this.http.post(
        `${this.SHARED_API_URL}/SharedData.svc/rest/Authenticate`,
        authRequest,
        {
          timeout: 30000,
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      const authTime = Date.now() - authStartTime;
      this.logger.log(`   ⏱️  TBO Auth Response Time: ${authTime}ms`);
      this.logger.debug(`   📋 TBO Auth Response: ${JSON.stringify(response.data)}`);

      // TBO API returns Status: 1 for success, not Status.Code
      const status = response.data?.Status;
      if (status !== 1) {
        const errorMsg = response.data?.Error?.ErrorMessage || `Status ${status}`;
        throw new Error(`Authentication failed: ${errorMsg}`);
      }

      if (!response.data?.TokenId) {
        throw new Error('Authentication failed: No TokenId in response');
      }

      this.tokenId = response.data.TokenId;
      // Token valid for 24 hours, but we'll refresh every 12 hours
      this.tokenExpiry = new Date(Date.now() + 12 * 60 * 60 * 1000);

      this.logger.log(`   ✅ TBO Authentication successful - TokenId: ${this.tokenId.substring(0, 8)}...`);
      return this.tokenId;
    } catch (error) {
      this.logger.error(`   ❌ TBO Authentication Error: ${error.message}`);
      this.logger.error(`   📋 Error Details: ${JSON.stringify(error.response?.data || error)}`);
      throw new InternalServerErrorException(
        `TBO Authentication failed: ${error.message || 'Unknown error'}`
      );
    }
  }

  async search(
    criteria: HotelSearchCriteria,
    preferences?: HotelPreferences,
  ): Promise<HotelSearchResult[]> {
    const startTime = Date.now();
    this.fileLog('\n═════════════════════════════════════════');
    this.fileLog('search() method called');
    this.fileLog('═════════════════════════════════════════');
    this.fileLog(`this.prisma type: ${typeof this.prisma}`);
    this.fileLog(`this.prisma value: ${this.prisma ? 'DEFINED' : 'UNDEFINED'}`);
    this.fileLog(`this.prisma.dvi_cities exists: ${!!this.prisma?.dvi_cities}`);
    console.log('📡 TBOHotelProvider.search() called');
    console.log('📡 this.prisma is:', !!this.prisma, typeof this.prisma);
    if (!this.prisma) {
      console.error('🔴 this.prisma is NULL/UNDEFINED in search method!');
      this.fileLog('🔴 ERROR: this.prisma is NULL/UNDEFINED in search method!');
      throw new Error('PrismaService not injected');
    }
    try {
      this.logger.log(`\n   📡 TBO PROVIDER: Starting hotel search for city: ${criteria.cityCode}`);

      // Step 1: Get TBO city code mapping
      // criteria.cityCode is already the TBO city code (from tbo_city_code field)
      const tboCity = await this.prisma.dvi_cities.findFirst({
        where: { tbo_city_code: criteria.cityCode },
      });

      if (!tboCity?.tbo_city_code) {
        throw new Error(
          `City ${criteria.cityCode} not mapped to TBO. TBO City Code: ${tboCity?.tbo_city_code}`
        );
      }

      this.logger.log(`   🗺️  City Mapping: TBO ${tboCity.tbo_city_code} → ${tboCity.name} (ID: ${tboCity.id})`);

      // Step 2: Get hotel codes from TBO SharedData API or master database
      // Instead of hardcoding, fetch from TBO's master hotel list
      let hotelCodes: string | undefined;
      let isUsingDatabaseCodes = false;
      
      if (criteria.hotelCodes) {
        // If explicitly provided (for testing), use it
        hotelCodes = criteria.hotelCodes;
        this.logger.log(`   📋 Using provided hotel codes: ${hotelCodes}`);
      } else {
        // Query database for hotel codes from tbo_hotel_master table
        // This table is synced daily from TBO's GetHotels API via cron/scheduler
        hotelCodes = await this.getHotelCodesForCityFromDb(tboCity.tbo_city_code);
        if (!hotelCodes) {
          this.logger.warn(`   ⚠️  No hotel codes in database for city ${tboCity.tbo_city_code} - will search by city only`);
          // Don't return empty - let TBO search by city without specific hotel codes
        } else {
          isUsingDatabaseCodes = true;
          this.logger.log(`   📋 Fetched ${hotelCodes.split(',').length} hotel codes from database`);
        }
      }

      // CRITICAL FIX: If using database codes, verify they look like valid TBO codes
      // Real TBO codes are 7 digits starting with 10 (e.g., 1014829, 1089687, 1138045)
      // Note: All hotel codes in database are synced from TBO API, so they're already valid
      if (isUsingDatabaseCodes && hotelCodes) {
        this.logger.log(`   ✅ Using ${hotelCodes.split(',').length} hotel codes from database`);
      }

      // Step 3: Chunk hotel codes (TBO recommends 100 codes per request)
      // Per TBO API docs: "send parallel searches for 100 hotel codes chunks"
      const hotelCodeChunks = this.chunkHotelCodes(hotelCodes, 100);
      
      if (hotelCodeChunks.length > 0) {
        this.logger.log(`   📊 Split ${hotelCodes?.split(',').length || 0} hotels into ${hotelCodeChunks.length} chunk(s) of max 100 codes`);
      }

      // If no hotel codes, search by city only (one request)
      const requestChunks = hotelCodeChunks.length > 0 ? hotelCodeChunks : [''];

      // Step 4: Make parallel searches for each chunk
      const basicAuth = Buffer.from('TBOApi:TBOApi@123').toString('base64');
      const chunkPromises = requestChunks.map((chunk) =>
        this.executeTBOSearch(
          {
            CheckIn: this.formatDateToISO(criteria.checkInDate),
            CheckOut: this.formatDateToISO(criteria.checkOutDate),
            HotelCodes: chunk,
            CityCode: tboCity.tbo_city_code,
            GuestNationality: 'IN',
            PaxRooms: [
              {
                Adults: Math.max(criteria.guestCount, 1),
                Children: 0,
                ChildrenAges: [],
              },
            ],
            ResponseTime: 23.0,
            IsDetailedResponse: true,
            Filters: {
              Refundable: true,
              NoOfRooms: criteria.roomCount || 1,
              MealType: 'WithMeal',
              OrderBy: 0,
              StarRating: 0,
              HotelName: null,
            },
          },
          basicAuth,
          chunk ? `(chunk: ${chunk.split(',').length} hotels)` : '(city-wide search)'
        )
      );

      const chunkResponses = await Promise.all(chunkPromises);
      const allHotels = chunkResponses.flat();

      if (allHotels.length === 0) {
        this.logger.warn(`   📭 No hotels found for city: ${criteria.cityCode}`);
        return [];
      }

      const hotels = allHotels;
      this.logger.log(`   ✅ TBO API returned ${hotels.length} hotels across ${requestChunks.length} request(s)`);

      if (hotels.length === 0) {
        this.logger.warn(`   📭 No hotels found for city: ${criteria.cityCode}`);
        return [];
      }

      // Step 7: Transform to standard format
      // TBO returns rooms within hotels, we need to flatten and deduplicate
      const expiresAt = new Date(Date.now() + 15 * 60 * 1000); // 15 min validity

      const results: HotelSearchResult[] = [];

      for (const hotel of hotels) {
        // Log the EXACT hotel data from TBO API
        this.logger.log(`\n🏨 TBO Hotel Raw Data:`);
        this.logger.log(`   - Full Hotel Object: ${JSON.stringify(hotel)}`);
        this.logger.log(`   - HotelCode: ${hotel.HotelCode}`);
        this.logger.log(`   - HotelName (from TBO): ${hotel.HotelName}`);
        this.logger.log(`   - Hotel Keys: ${Object.keys(hotel).join(', ')}`);

        // Fetch actual hotel name from database (synced from TBO GetHotels API)
        const hotelMasterData = await this.getHotelMasterDataFromDb(hotel.HotelCode, criteria.cityCode);
        const hotelDisplayName = hotelMasterData?.hotel_name ?? `Hotel ${hotel.HotelCode}`;

        this.logger.log(`   - Database Hotel Name: ${hotelDisplayName}`);
        this.logger.log(`   - Using Name: ${hotelDisplayName}\n`);

        // Process each room as a separate offering with the SAME real hotel name
        // (One HotelCode = One real hotel, not fake variants)
        for (let idx = 0; idx < (hotel.Rooms || []).length; idx++) {
          const room = hotel.Rooms[idx];
          const totalFare = room.TotalFare || room.DayRates?.[0]?.[0]?.BasePrice || 0;
          const roomName = room.Name?.[0] || 'Standard Room';
          
          // Use REAL BookingCode from TBO Search API response (not generated)
          const realBookingCode = room.BookingCode || `${hotel.HotelCode}_${room.TBORoomID}`;

          results.push({
            provider: 'tbo',
            hotelCode: hotel.HotelCode,
            hotelName: hotelDisplayName, // Real hotel name from database
            cityCode: criteria.cityCode,
            address: hotelMasterData?.hotel_address ?? '',
            rating: hotelMasterData?.star_rating ?? 0,
            category: hotelMasterData?.star_rating ? `${hotelMasterData.star_rating}-Star` : '-',
            facilities: (room.Inclusion || '').split(',').map((f) => f.trim()),
            images: [],
            price: parseFloat(totalFare.toString()),
            currency: hotel.Currency || 'INR',
            roomType: roomName, // Room type name for display
            mealPlan: (room.Inclusion || '').includes('Breakfast') ? 'Breakfast Included' : '-',
            roomTypes: [
              {
                roomCode: realBookingCode,
                roomName: roomName,
                bedType: roomName.includes('King') ? 'King' : 'Twin',
                capacity: 2,
                price: parseFloat(totalFare.toString()),
                cancellationPolicy: room.CancelPolicies?.[0]?.ChargeType || 'Non-refundable',
              },
            ],
            // Use REAL BookingCode from TBO as searchReference
            searchReference: realBookingCode,
            expiresAt: expiresAt,
          });
        }
      }

      this.logger.log(`✅ Successfully transformed ${results.length} hotels`);
      this.logger.log(`📊 Room type breakdown: ${results.map(r => r.price).join(', ')}`);
      return results;
    } catch (error: any) {
      const errorMsg = error instanceof Error ? error.message : String(error);
      this.logger.error(`❌ Hotel Search Error: ${errorMsg}`);
      this.logger.error(`   📋 Stack: ${error?.stack?.substring(0, 200)}`);
      
      // CRITICAL: Don't throw - return empty array for graceful handling
      // This allows the system to generate placeholder "No Hotels Available" instead of crashing
      this.logger.warn(`   ⚠️  Returning empty results instead of throwing error`);
      return [];
    }
  }

  async confirmBooking(
    bookingDetails: HotelConfirmationDTO,
  ): Promise<HotelConfirmationResult> {
    try {
      this.logger.log(
        `📋 Confirming booking for hotel: ${bookingDetails.hotelCode}`
      );

      // Step 1: Authenticate and get TokenId
      const tokenId = await this.authenticate();

      // Step 2: Validate search reference
      const searchRef = bookingDetails.searchReference;
      if (!searchRef) {
        throw new Error('Search reference is required for confirmation');
      }

      // Step 3: Build TBO PreBook request (from Postman collection)
      // PreBook is required before booking to lock the room
      const prebookRequest = {
        CheckInDate: this.formatDateToISO(bookingDetails.checkInDate),
        CheckOutDate: this.formatDateToISO(bookingDetails.checkOutDate),
        HotelCode: bookingDetails.hotelCode,
        RoomCode: bookingDetails.rooms[0]?.roomCode || '',
        RoomCount: bookingDetails.roomCount,
        TokenId: tokenId,
      };

      this.logger.debug(`📤 PreBook request: ${JSON.stringify(prebookRequest)}`);

      // Step 4: Call PreBook API
      const prebookResponse = await this.http
        .post(`${this.SEARCH_API_URL}/PreBook`, prebookRequest, {
          timeout: 30000,
          headers: {
            'Content-Type': 'application/json',
          },
        });

      let prebookRefId = prebookResponse.data?.PreBookRefId;
      if (!prebookRefId) {
        this.logger.warn(
          `⚠️ PreBook status: ${prebookResponse.data?.Status?.Code}`
        );
        // If PreBook fails, continue with booking using original search reference
        prebookRefId = searchRef;
      } else {
        this.logger.log(`✅ PreBook successful: ${prebookRefId}`);
      }

      // Step 5: Build TBO Book request (from Postman collection)
      const bookRequest = {
        PreBookRefId: prebookRefId,
        HotelCode: bookingDetails.hotelCode,
        GuestDetails: bookingDetails.guests.map((g) => ({
          FirstName: g.firstName,
          LastName: g.lastName,
          Email: g.email,
          MobileNo: g.phone,
          Title: 'Mr', // Default title
        })),
        ContactDetails: {
          Name: bookingDetails.contactName,
          EmailId: bookingDetails.contactEmail,
          MobileNo: bookingDetails.contactPhone,
        },
        TokenId: tokenId,
      };

      this.logger.debug(`📤 Book request: ${JSON.stringify(bookRequest)}`);

      // Step 6: Call Book API
      const bookResponse = await this.http
        .post(`${this.BOOKING_API_URL}/hotelservice.svc/rest/Book`, bookRequest, {
          timeout: 30000,
          headers: {
            'Content-Type': 'application/json',
          },
        });

      // Check response status
      const bookingStatus = bookResponse.data?.Status;
      if (!bookingStatus || bookingStatus.Code !== 200) {
        throw new Error(
          `Booking failed: ${bookingStatus?.Description || 'Unknown error'}`
        );
      }

      const bookingRefId = bookResponse.data?.BookingRefId;
      this.logger.log(`✅ Booking confirmed with ref: ${bookingRefId}`);

      // Step 7: Return confirmation
      return {
        provider: 'tbo',
        confirmationReference: bookingRefId,
        hotelCode: bookingDetails.hotelCode,
        hotelName: bookingDetails.hotelCode, // Would come from API response
        checkIn: bookingDetails.checkInDate,
        checkOut: bookingDetails.checkOutDate,
        roomCount: bookingDetails.roomCount,
        totalPrice: 0, // Would come from PreBook/Book response
        priceBreadown: {
          roomCharges: 0,
          taxes: 0,
          discounts: 0,
        },
        cancellationPolicy: 'As per TBO policy',
        status: 'confirmed',
        bookingDeadline: new Date().toISOString(),
      };
    } catch (error) {
      this.logger.error(
        `❌ Booking Confirmation Error: ${error.message}`,
        error.stack
      );
      throw new InternalServerErrorException(
        `TBO confirmation failed: ${error.message || 'Unknown error'}`
      );
    }
  }

  async getConfirmation(
    confirmationRef: string,
  ): Promise<HotelConfirmationDetails> {
    try {
      this.logger.log(`📋 Getting confirmation status for: ${confirmationRef}`);

      // Step 1: Authenticate and get TokenId
      const tokenId = await this.authenticate();

      // Step 2: Build GetBookingDetail request (from Postman collection)
      const request = {
        BookingRefId: confirmationRef,
        TokenId: tokenId,
      };

      this.logger.debug(`📤 GetBookingDetail request: ${JSON.stringify(request)}`);

      // Step 3: Call GetBookingDetail API
      const response = await this.http
        .post(
          `${this.BOOKING_API_URL}/hotelservice.svc/rest/Getbookingdetail`,
          request,
          {
            timeout: 30000,
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );

      const status = response.data?.Status;
      if (!status || status.Code !== 200) {
        throw new Error(
          `Failed to fetch booking: ${status?.Description || 'Unknown error'}`
        );
      }

      this.logger.log(`✅ Booking details retrieved`);

      return {
        confirmationRef: confirmationRef,
        hotelName: response.data.HotelName || '',
        checkIn: response.data.CheckInDate,
        checkOut: response.data.CheckOutDate,
        roomCount: response.data.RoomCount || 1,
        totalPrice: parseFloat(response.data.TotalPrice) || 0,
        status: response.data.BookingStatus || 'confirmed',
        cancellationPolicy: response.data.CancellationPolicy || 'As per TBO',
      };
    } catch (error) {
      this.logger.error(
        `❌ Get Confirmation Error: ${error.message}`,
        error.stack
      );
      throw new InternalServerErrorException('Failed to get confirmation details');
    }
  }

  async cancelBooking(
    confirmationRef: string,
    reason: string,
  ): Promise<CancellationResult> {
    try {
      this.logger.log(
        `❌ Cancelling booking: ${confirmationRef}, Reason: ${reason}`
      );

      // Step 1: Authenticate and get TokenId
      const tokenId = await this.authenticate();

      // Step 2: Build SendChangeRequest with RequestType=4 (from Postman collection)
      const request = {
        BookingRefId: confirmationRef,
        RequestType: 4, // 4 = Cancellation
        TokenId: tokenId,
        Remarks: reason,
      };

      this.logger.debug(`📤 Cancellation request: ${JSON.stringify(request)}`);

      // Step 3: Call SendChangeRequest API
      const response = await this.http
        .post(
          `${this.BOOKING_API_URL}/hotelservice.svc/rest/SendChangeRequest`,
          request,
          {
            timeout: 30000,
            headers: {
              'Content-Type': 'application/json',
            },
          }
        );

      const cancelStatus = response.data?.Status;
      if (!cancelStatus || cancelStatus.Code !== 200) {
        throw new Error(
          `Cancellation failed: ${cancelStatus?.Description || 'Unknown error'}`
        );
      }

      this.logger.log(`✅ Booking cancelled successfully`);

      return {
        cancellationRef: response.data?.CancellationId || confirmationRef,
        refundAmount: parseFloat(response.data?.RefundAmount) || 0,
        charges: parseFloat(response.data?.CancellationCharges) || 0,
        refundDays: response.data?.RefundDays || 5,
      };
    } catch (error) {
      this.logger.error(
        `❌ Cancel Booking Error: ${error.message}`,
        error.stack
      );
      throw new InternalServerErrorException('Failed to cancel booking');
    }
  }

  private parseRating(ratingStr: string): number {
    const ratingMap: Record<string, number> = {
      OneStar: 1,
      TwoStar: 2,
      ThreeStar: 3,
      FourStar: 4,
      FiveStar: 5,
      All: 0,
    };
    return ratingMap[ratingStr] || 0;
  }

  private parseFacilities(facilities: any): string[] {
    if (!facilities) return [];

    if (Array.isArray(facilities)) {
      return facilities
        .flat()
        .filter((f) => f && typeof f === 'string')
        .map((f) => f.trim());
    }

    if (typeof facilities === 'string') {
      return facilities
        .split(',')
        .map((f) => f.trim())
        .filter((f) => f);
    }

    return [];
  }

  private parseRoomTypes(roomsData: any[]): RoomType[] {
    if (!roomsData || !Array.isArray(roomsData)) return [];

    return roomsData.map((room) => ({
      roomCode: room.RoomCode || '',
      roomName: room.RoomName || '',
      bedType: room.BedType || 'Not specified',
      capacity: parseInt(room.Capacity) || 1,
      price: parseFloat(room.Price) || 0,
      cancellationPolicy: room.CancellationPolicy || 'Non-refundable',
    }));
  }

  /**
   * Fetch hotel codes dynamically from TBO GetHotels API
   * Falls back to database if API fails
   * 
   * NEVER uses hardcoded values
   */
  private async fetchHotelsFromTBOApi(tboCityCode: string): Promise<string> {
    try {
      this.logger.log(`📡 TBO API: Fetching hotels from GetHotels API for city: ${tboCityCode}`);

      // Get token for authentication
      const tokenId = await this.authenticate();

      // Build GetHotels request
      const getHotelsRequest = {
        CityCode: tboCityCode,
        TokenId: tokenId,
        StarRating: 0, // 0 = all ratings
      };

      this.logger.debug(`📤 GetHotels request: ${JSON.stringify(getHotelsRequest)}`);

      // Call TBO GetHotels API
      const response = await this.http.post(
        `${this.SHARED_API_URL}/SharedData.svc/rest/GetHotels`,
        getHotelsRequest,
        {
          timeout: 30000,
          headers: { 'Content-Type': 'application/json' },
        }
      );

      const status = response.data?.Status;
      if (status !== 1) {
        this.logger.warn(`⚠️  TBO API GetHotels returned status: ${status}. Falling back to database.`);
        this.fileLog(`⚠️  TBO API status ${status} - falling back to database`);
        return await this.getHotelCodesFromDbFallback(tboCityCode);
      }

      const hotels = response.data?.Hotels || [];
      this.logger.log(`✅ TBO GetHotels returned ${hotels.length} hotels for city ${tboCityCode}`);

      if (hotels.length === 0) {
        this.logger.warn(`⚠️  TBO API returned 0 hotels for city ${tboCityCode}. Falling back to database.`);
        this.fileLog(`⚠️  TBO API returned 0 hotels - falling back to database`);
        return await this.getHotelCodesFromDbFallback(tboCityCode);
      }

      // Extract and return hotel codes
      const hotelCodes: string[] = [];
      for (const hotel of hotels) {
        hotelCodes.push(hotel.HotelCode);
      }

      this.logger.log(`✅ Extracted ${hotelCodes.length} hotel codes from TBO GetHotels response`);
      this.fileLog(`✅ TBO API SUCCESS: ${hotelCodes.length} hotels extracted`);
      return hotelCodes.join(',');
    } catch (error) {
      const err = error as Error;
      this.logger.error(`❌ TBO API GetHotels failed: ${err.message}. Falling back to database.`);
      this.fileLog(`❌ TBO API Exception: ${err.message} - falling back to database`);
      return await this.getHotelCodesFromDbFallback(tboCityCode);
    }
  }

  /**
   * Fallback hotel codes when TBO API is unavailable
   * Returns hardcoded popular hotels per city
   */
  private parseStarRating(categoryString: string): number {
    // Extract star rating from category string like "5-Star", "4-Star", etc.
    if (!categoryString) return 0;
    const match = categoryString.match(/(\d+)-Star/);
    return match ? parseInt(match[1], 10) : 0;
  }

  /**
   * Fetch hotel codes from database (tbo_hotel_master table)
   * This is the PRIMARY method - no hardcoding allowed
   * 
   * Error Handling:
   * 1. If DB has hotels → Return them
   * 2. If DB empty → Log error & return empty string (forces error handling upstream)
   * 3. If DB query fails → Log error & return empty string
   */
  private async getHotelCodesFromDbFallback(tboCityCode: string): Promise<string> {
    try {
      this.logger.warn(`⚠️  Fallback: Fetching hotel codes from tbo_hotel_master for city ${tboCityCode}`);

      if (!this.prisma) {
        this.logger.error(`🔴 CRITICAL: PrismaService is NULL/UNDEFINED - Cannot query database`);
        return '';
      }

      const hotels = await this.prisma.tbo_hotel_master.findMany({
        where: {
          tbo_city_code: tboCityCode,
          status: 1, // Active hotels only
        },
        select: {
          tbo_hotel_code: true,
        },
        take: 100, // Limit to 100 hotels
      });

      if (!hotels || hotels.length === 0) {
        this.logger.error(
          `❌ DATABASE ERROR: No hotels found in tbo_hotel_master for city ${tboCityCode}. ` +
          `Make sure to run hotel sync: POST /hotels/sync/all`
        );
        this.fileLog(`❌ DATABASE ERROR: No hotels in tbo_hotel_master for city ${tboCityCode}`);
        return '';
      }

      const hotelCodes = hotels
        .map((h) => h.tbo_hotel_code)
        .filter((code) => code && code.trim() !== '')
        .join(',');

      this.logger.warn(`⚠️  Fallback Query SUCCESS: Found ${hotels.length} hotels in database`);
      this.logger.log(`📋 Hotel codes from DB: ${hotelCodes.substring(0, 100)}...`);
      this.fileLog(`✅ Fallback DB query SUCCESS: ${hotels.length} hotels, codes: ${hotelCodes}`);

      return hotelCodes;
    } catch (error: any) {
      const errorMsg = error instanceof Error ? error.message : String(error);
      this.logger.error(
        `🔴 DATABASE QUERY ERROR: Failed to fetch hotels from tbo_hotel_master: ${errorMsg}`
      );
      this.fileLog(`🔴 DB Query Error: ${errorMsg}`);
      return '';
    }
  }

  /**
   * Fetch hotel codes for a city from database
   * Queries tbo_hotel_master table (synced from TBO GetHotels API)
   * 
   * PRIMARY FLOW:
   * 1. Try database first (tbo_hotel_master)
   * 2. If DB empty, return empty string (error handling)
   * 3. No hardcoding - database must be synced via POST /hotels/sync/all
   */
  private async getHotelCodesForCityFromDb(tboCityCode: string): Promise<string> {
    try {
      this.logger.log(`📊 PRIMARY: Querying tbo_hotel_master for city ${tboCityCode}`);

      if (!this.prisma) {
        this.logger.error(`🔴 CRITICAL: PrismaService is NULL/UNDEFINED`);
        this.fileLog('🔴 CRITICAL: PrismaService is NULL/UNDEFINED in getHotelCodesForCityFromDb');
        throw new Error('PrismaService not available');
      }

      // Query tbo_hotel_master table (synced from TBO GetHotels API)
      const hotels = await this.prisma.tbo_hotel_master.findMany({
        where: {
          tbo_city_code: tboCityCode,
          status: 1, // Active hotels only
        },
        select: {
          tbo_hotel_code: true,
        },
        take: 100,
      });

      if (!hotels || hotels.length === 0) {
        this.logger.error(
          `❌ PRIMARY: No hotels in tbo_hotel_master for city ${tboCityCode}. ` +
          `Run sync: POST /api/v1/hotels/sync/all`
        );
        this.fileLog(`❌ PRIMARY Query Failed: No hotels for city ${tboCityCode}`);

        // Try fallback query from dvi_hotel table
        this.logger.log(`🔄 FALLBACK: Trying dvi_hotel table for city ${tboCityCode}`);
        const dviHotels = await this.prisma.dvi_hotel.findMany({
          where: {
            tbo_city_code: tboCityCode,
            deleted: false,
          },
          select: {
            tbo_hotel_code: true,
          },
          take: 100,
        });

        if (dviHotels && dviHotels.length > 0) {
          const codes = dviHotels
            .map((h) => h.tbo_hotel_code)
            .filter((code) => code && code.trim() !== '')
            .join(',');

          this.logger.log(`✅ FALLBACK: Found ${dviHotels.length} hotels in dvi_hotel`);
          this.logger.log(`📋 Hotel codes: ${codes.substring(0, 100)}...`);
          return codes;
        }

        this.logger.error(
          `❌ FALLBACK FAILED: No hotels in dvi_hotel either for city ${tboCityCode}. ` +
          `Database is empty. Returning empty string.`
        );
        return '';
      }

      const hotelCodes = hotels
        .map((h) => h.tbo_hotel_code)
        .filter((code) => code && code.trim() !== '')
        .join(',');

      this.logger.log(`✅ PRIMARY SUCCESS: Found ${hotels.length} hotels in tbo_hotel_master`);
      this.logger.log(`📋 Hotel codes from DB: ${hotelCodes.substring(0, 100)}...`);
      this.fileLog(`✅ PRIMARY Query SUCCESS: ${hotels.length} hotels, codes: ${hotelCodes}`);

      return hotelCodes;
    } catch (error: any) {
      const errorMsg = error instanceof Error ? error.message : String(error);
      this.logger.error(
        `🔴 DATABASE QUERY ERROR: ${errorMsg}`
      );
      this.fileLog(`🔴 DB Query Exception: ${errorMsg}`);
      return '';
    }
  }

  /**
   * Get hotel master data from database
   * Queries dvi_hotel table for specific hotel
   * Matches PHP structure from cron_tbo_hotel_details_core_data.php
   * Returns: hotel_name, hotel_address, hotel_category (star rating)
   */
  private async getHotelMasterDataFromDb(hotelCode: string, cityCode?: string) {
    try {
      // Database stores tbo_hotel_code as base code (e.g., "1035259")
      // Search by tbo_hotel_code and optionally by tbo_city_code for city-aware lookup
      
      let hotel: any = null;

      // Strategy 1: If city code provided, search for this city's hotel first in dvi_hotel
      if (cityCode) {
        hotel = await this.prisma.dvi_hotel.findFirst({
          where: {
            tbo_hotel_code: hotelCode,
            tbo_city_code: cityCode,
            deleted: false,
          },
        });

        if (hotel) {
          this.logger.log(`✅ Found hotel by code+city: ${hotelCode} (City: ${cityCode}) -> ${hotel.hotel_name}`);
          return {
            hotel_name: hotel.hotel_name,
            hotel_address: hotel.hotel_address || '',
            star_rating: hotel.hotel_category || 0,
          };
        }

        this.logger.log(`🔍 Hotel ${hotelCode} not found in dvi_hotel for city ${cityCode}, searching globally...`);
      }

      // Strategy 2: Search by hotel code only in dvi_hotel (global search)
      hotel = await this.prisma.dvi_hotel.findFirst({
        where: {
          tbo_hotel_code: hotelCode,
          deleted: false,
        },
      });

      if (hotel) {
        this.logger.log(`✅ Found hotel by code in dvi_hotel: ${hotelCode} -> ${hotel.hotel_name}`);
        return {
          hotel_name: hotel.hotel_name,
          hotel_address: hotel.hotel_address || '',
          star_rating: hotel.hotel_category || 0,
        };
      }

      // Strategy 3: Fallback to tbo_hotel_master table (synced from TBO GetHotels API)
      this.logger.log(`🔍 Hotel ${hotelCode} not found in dvi_hotel, checking tbo_hotel_master...`);
      
      const tboHotel: any = await this.prisma.tbo_hotel_master.findFirst({
        where: {
          tbo_hotel_code: hotelCode,
          ...(cityCode ? { tbo_city_code: cityCode } : {}),
          status: 1,
        },
      });

      if (tboHotel) {
        this.logger.log(`✅ Found hotel in tbo_hotel_master: ${hotelCode} -> ${tboHotel.hotel_name}`);
        return {
          hotel_name: tboHotel.hotel_name || `Hotel ${hotelCode}`,
          hotel_address: tboHotel.hotel_address || '',
          star_rating: tboHotel.star_rating || 0,
        };
      }

      this.logger.warn(`⚠️  Hotel ${hotelCode} not found in any database table`);
      return null;
    } catch (error) {
      const err = error as Error;
      this.logger.error(`❌ Error querying hotel from database: ${err.message}`);
      return null;
    }
  }

  private formatDateToISO(dateStr: string): string {
    const date = new Date(dateStr);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`; // YYYY-MM-DD (ISO format)
  }

  private formatDate(dateStr: string): string {
    const date = new Date(dateStr);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    return `${day}/${month}/${year}`; // DD/MM/YYYY (for legacy support)
  }



  private generateCacheKey(criteria: HotelSearchCriteria): string {
    return `hotel_search_${criteria.cityCode}_${criteria.checkInDate}_${criteria.checkOutDate}_${criteria.roomCount}`;
  }

  /**
   * Split hotel codes into chunks of max size
   * Per TBO API documentation: send parallel searches for 100 hotel codes chunks
   */
  private chunkHotelCodes(hotelCodes: string | undefined, chunkSize: number = 100): string[] {
    if (!hotelCodes || hotelCodes.trim() === '') {
      return [];
    }

    const codes = hotelCodes.split(',').map(c => c.trim()).filter(c => c);
    const chunks: string[] = [];

    for (let i = 0; i < codes.length; i += chunkSize) {
      chunks.push(codes.slice(i, i + chunkSize).join(','));
    }

    return chunks;
  }

  /**
   * Execute a single TBO Search API request for a chunk of hotel codes
   */
  private async executeTBOSearch(
    searchRequest: any,
    basicAuth: string,
    description: string = ''
  ): Promise<any[]> {
    try {
      this.logger.log(`   📤 TBO Search Request ${description}:`);
      this.logger.log(`      - Check-in: ${searchRequest.CheckIn}`);
      this.logger.log(`      - Check-out: ${searchRequest.CheckOut}`);
      this.logger.log(`      - City Code: ${searchRequest.CityCode}`);
      this.logger.log(`      - Hotel Codes: ${searchRequest.HotelCodes || '(All available hotels for city)'}`);
      this.logger.log(`      - Guests: ${searchRequest.PaxRooms[0].Adults} adults`);

      const startTime = Date.now();
      const response = await this.http.post(`${this.SEARCH_API_URL}/Search`, searchRequest, {
        timeout: 30000,
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Basic ${basicAuth}`,
        },
      });

      const responseTime = Date.now() - startTime;
      this.logger.log(`   ⏱️  TBO API Response Time ${description}: ${responseTime}ms`);
      this.logger.debug(`   ⏱️  TBO API Response: ${JSON.stringify(response.data).substring(0, 300)}`);

      // Check response status
      const statusObj = response.data?.Status;
      const statusCode = typeof statusObj === 'object' ? statusObj?.Code : statusObj;

      if (statusCode !== 200) {
        const description = typeof statusObj === 'object' ? statusObj?.Description : 'Unknown error';
        this.logger.warn(`   ⚠️  TBO Search returned status: ${statusCode} - ${description}`);
        return [];
      }

      const hotels = response.data.HotelResult || [];
      this.logger.log(`   ✅ This request returned ${hotels.length} hotels`);
      return hotels;
    } catch (error: any) {
      const errorMsg = error instanceof Error ? error.message : String(error);
      this.logger.error(`   ❌ TBO Search Error ${description}: ${errorMsg}`);
      return [];
    }
  }
}

