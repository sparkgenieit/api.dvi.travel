import { Injectable, InternalServerErrorException, Logger } from '@nestjs/common';
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

interface ResAvenueInventory {
  InvCode: number;
  Inventory: Array<{
    Date: string;
    InvCount: number;
    StopSell: boolean;
    CloseOnArrival: boolean;
    CloseOnDeparture: boolean;
  }>;
}

interface ResAvenueRate {
  RateCode: number;
  Rate: Array<{
    Date: string;
    Single: number;
    Double: number;
    Triple?: number;
    ExtraPax: number;
    ExtraChild: number;
    MinStay: number;
    MaxStay: number;
    StopSell: boolean;
  }>;
}

interface ResAvenueRoomType {
  InvTypeCode: number;
  InvTypeName: string;
  Occupancy: number;
}

interface ResAvenueRatePlan {
  RatePlanCode: number;
  RatePlanName: string;
  MealPlan: string;
}

@Injectable()
export class ResAvenueHotelProvider implements IHotelProvider {
  private readonly BASE_URL = process.env.RESAVENUE_BASE_URL || 'http://203.109.97.241:8080/ChannelController';
  private readonly USERNAME = process.env.RESAVENUE_USERNAME || 'testpmsk4@resavenue.com';
  private readonly PASSWORD = process.env.RESAVENUE_PASSWORD || 'testpms@123';
  private readonly ID_CONTEXT = process.env.RESAVENUE_ID_CONTEXT || 'REV';

  private logger = new Logger(ResAvenueHotelProvider.name);
  private http: AxiosInstance = axios;
  private logFile = path.join(process.cwd(), 'resavenue-hotel-provider.log');

  constructor(private readonly prisma: PrismaService) {
    this.logger.log('🏨 ResAvenue Hotel Provider initialized');
    this.logger.log(`Using endpoint: ${this.BASE_URL}`);
  }

  private fileLog(message: string) {
    const timestamp = new Date().toISOString();
    const logMessage = `[${timestamp}] ${message}\n`;
    try {
      fs.appendFileSync(this.logFile, logMessage);
    } catch (e) {
      // Ignore file write errors
    }
  }

  getName(): string {
    return 'ResAvenue';
  }

  /**
   * Get property details (master data: room types, rate plans)
   */
  private async getPropertyDetails(hotelCode: string): Promise<any> {
    try {
      const response = await this.http.post(
        `${this.BASE_URL}/PropertyDetails`,
        {
          OTA_HotelDetailsRQ: {
            POS: {
              Username: this.USERNAME,
              Password: this.PASSWORD,
              ID_Context: this.ID_CONTEXT,
            },
            TimeStamp: '20261015T15:22:50',
            EchoToken: `details-${Date.now()}`,
            HotelCode: hotelCode,
          },
        },
        {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          timeout: 30000,
        }
      );

      return response.data?.OTA_HotelDetailsRS?.[0] || null;
    } catch (error) {
      this.logger.error(`Failed to fetch property details for hotel ${hotelCode}: ${error.message}`);
      return null;
    }
  }

  /**
   * Get inventory (room availability) for specific dates
   */
  private async getInventory(
    hotelCode: string,
    startDate: string,
    endDate: string,
    invCodes: number[]
  ): Promise<ResAvenueInventory[]> {
    try {
      const response = await this.http.post(
        `${this.BASE_URL}/PropertyDetails`,
        {
          OTA_HotelInventoryRQ: {
            POS: {
              Username: this.USERNAME,
              Password: this.PASSWORD,
              ID_Context: this.ID_CONTEXT,
            },
            TimeStamp: '20261015T15:22:50',
            EchoToken: `inv-${Date.now()}`,
            HotelCode: hotelCode,
            Start: startDate,
            End: endDate,
            InvCodes: invCodes,
          },
        },
        {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          timeout: 30000,
        }
      );

      return response.data?.OTA_HotelInventoryRS?.Inventories || [];
    } catch (error) {
      this.logger.error(`Failed to fetch inventory for hotel ${hotelCode}: ${error.message}`);
      return [];
    }
  }

  /**
   * Get rates (pricing) for specific dates
   */
  private async getRates(
    hotelCode: string,
    startDate: string,
    endDate: string,
    rateCodes: number[]
  ): Promise<ResAvenueRate[]> {
    try {
      const response = await this.http.post(
        `${this.BASE_URL}/PropertyDetails`,
        {
          OTA_HotelRateRQ: {
            POS: {
              Username: this.USERNAME,
              Password: this.PASSWORD,
              ID_Context: this.ID_CONTEXT,
            },
            HotelCode: hotelCode,
            Start: startDate,
            End: endDate,
            RateCodes: rateCodes,
          },
        },
        {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          timeout: 30000,
        }
      );

      return response.data?.OTA_HotelRateRS?.Rates || [];
    } catch (error) {
      this.logger.error(`Failed to fetch rates for hotel ${hotelCode}: ${error.message}`);
      return [];
    }
  }

  /**
   * Main search method - searches for hotels by city
   */
  async search(
    criteria: HotelSearchCriteria,
    preferences?: HotelPreferences,
  ): Promise<HotelSearchResult[]> {
    const startTime = Date.now();
    this.logger.log(`\n   🏨 RESAVENUE PROVIDER: Starting hotel search for city: ${criteria.cityCode}`);

    try {
      // Step 1: Resolve city name from city code (supports both TBO codes and city names)
      let cityName = criteria.cityCode;
      
      // Check if cityCode is numeric (TBO format) - if so, look up the city name
      if (/^\d+$/.test(criteria.cityCode)) {
        const city = await this.prisma.dvi_cities.findFirst({
          where: { tbo_city_code: criteria.cityCode },
        });
        
        if (city) {
          cityName = city.name;
          this.logger.log(`   🗺️  Resolved TBO code ${criteria.cityCode} → ${cityName}`);
        } else {
          this.logger.warn(`   ⚠️  Could not resolve TBO city code: ${criteria.cityCode}`);
          return [];
        }
      }

      // Step 2: Query database for ResAvenue hotels in this city
      const hotels = await this.prisma.dvi_hotel.findMany({
        where: {
          hotel_city: cityName,
          resavenue_hotel_code: { not: null },
          deleted: false,
          status: 1,
        },
      });

      if (hotels.length === 0) {
        this.logger.log(`   📭 No ResAvenue hotels found in city: ${cityName}`);
        return [];
      }

      this.logger.log(`   📋 Found ${hotels.length} ResAvenue hotel(s) in ${cityName}`);

      // Step 3: For each hotel, fetch live data from ResAvenue
      const hotelSearchPromises = hotels.map((hotel) =>
        this.searchHotel(hotel, criteria)
      );

      const results = await Promise.all(hotelSearchPromises);
      const validResults = results.filter((r) => r !== null) as HotelSearchResult[];

      const totalTime = Date.now() - startTime;
      this.logger.log(`   ✅ ResAvenue search completed: ${validResults.length}/${hotels.length} hotels available (${totalTime}ms)`);

      return validResults;
    } catch (error) {
      this.logger.error(`   ❌ ResAvenue search error: ${error.message}`);
      throw new InternalServerErrorException(`ResAvenue search failed: ${error.message}`);
    }
  }

  /**
   * Search individual hotel - fetch property details, inventory and rates dynamically
   */
  private async searchHotel(
    hotel: any,
    criteria: HotelSearchCriteria
  ): Promise<HotelSearchResult | null> {
    const hotelCode = hotel.resavenue_hotel_code;
    
    try {
      // Step 1: Fetch PropertyDetails to get room and rate codes dynamically
      const propertyDetails = await this.getPropertyDetails(hotelCode);
      if (!propertyDetails || !propertyDetails.RoomTypes) {
        this.logger.warn(`   ⚠️  Hotel ${hotelCode}: No property details available`);
        return null;
      }

      // Step 2: Extract active room and rate codes from PropertyDetails
      const roomTypes = propertyDetails.RoomTypes.filter((room: any) => room.room_status === 'active');
      
      if (roomTypes.length === 0) {
        this.logger.debug(`   📭 Hotel ${hotel.hotel_name} (${hotelCode}): No active rooms`);
        return null;
      }

      const invCodes: number[] = [];
      const rateCodes: number[] = [];
      const roomTypeMap: Map<number, any> = new Map();
      const ratePlanMap: Map<number, any> = new Map();

      // Build maps for room types and rate plans
      roomTypes.forEach((room: any) => {
        invCodes.push(room.room_id);
        roomTypeMap.set(room.room_id, room);

        if (room.RatePlans) {
          room.RatePlans.filter((rate: any) => rate.rate_status === 'active').forEach((rate: any) => {
            rateCodes.push(rate.rate_id);
            ratePlanMap.set(rate.rate_id, rate);
          });
        }
      });

      if (invCodes.length === 0 || rateCodes.length === 0) {
        this.logger.debug(`   📭 Hotel ${hotel.hotel_name} (${hotelCode}): No active rooms/rates`);
        return null;
      }

      // Step 3: Fetch live inventory and rates for the date range
      const [inventories, rates] = await Promise.all([
        this.getInventory(hotelCode, criteria.checkInDate, criteria.checkOutDate, invCodes),
        this.getRates(hotelCode, criteria.checkInDate, criteria.checkOutDate, rateCodes),
      ]);

      if (inventories.length === 0 || rates.length === 0) {
        this.logger.debug(`   📭 Hotel ${hotel.hotel_name} (${hotelCode}): No inventory/rates returned`);
        return null;
      }

      // Step 4: Check if rooms are available
      const availableRooms = this.findAvailableRooms(
        inventories,
        rates,
        roomTypeMap,
        ratePlanMap,
        criteria
      );

      if (availableRooms.length === 0) {
        this.logger.debug(`   📭 Hotel ${hotel.hotel_name} (${hotelCode}): No availability`);
        return null;
      }

      // Calculate minimum price
      const minPrice = Math.min(...availableRooms.map((r) => r.price));

      // Build search result
      const result: HotelSearchResult = {
        provider: 'ResAvenue',
        hotelCode: hotelCode,
        hotelName: hotel.hotel_name,
        cityCode: hotel.hotel_city,
        address: hotel.hotel_address || '',
        rating: hotel.hotel_category || 0,
        category: `${hotel.hotel_category || 0}-star`,
        facilities: [],
        images: [],
        price: minPrice,
        currency: 'INR',
        roomTypes: availableRooms,
        searchReference: `RESAVENUE-${hotelCode}-${Date.now()}`,
        expiresAt: new Date(Date.now() + 30 * 60 * 1000), // 30 minutes
      };

      this.logger.log(`   ✅ ${hotel.hotel_name}: ${availableRooms.length} room(s), from ₹${minPrice}`);
      return result;
    } catch (error) {
      this.logger.error(`   ❌ Error searching hotel ${hotelCode}: ${error.message}`);
      return null;
    }
  }

  /**
   * Find available rooms by matching inventory and rates with PropertyDetails metadata
   */
  private findAvailableRooms(
    inventories: ResAvenueInventory[],
    rates: ResAvenueRate[],
    roomTypeMap: Map<number, any>,
    ratePlanMap: Map<number, any>,
    criteria: HotelSearchCriteria
  ): RoomType[] {
    const availableRooms: RoomType[] = [];

    // For each room type with inventory
    for (const inv of inventories) {
      // Check if all dates have availability
      const allDatesAvailable = inv.Inventory.every(
        (day) => day.InvCount >= criteria.roomCount && !day.StopSell
      );

      if (!allDatesAvailable) continue;

      // Get room metadata from PropertyDetails
      const roomType = roomTypeMap.get(inv.InvCode);
      if (!roomType) continue;

      // Find rates for this room type
      for (const rate of rates) {
        // Check if all dates have valid rates
        const allDatesHaveRates = rate.Rate.every((day) => !day.StopSell);
        if (!allDatesHaveRates) continue;

        // Get rate plan metadata from PropertyDetails
        const ratePlan = ratePlanMap.get(rate.RateCode);
        if (!ratePlan) continue;

        // Calculate total price for the stay
        const totalPrice = rate.Rate.reduce((sum, day) => {
          const pricePerRoom = criteria.guestCount === 1 ? day.Single : day.Double;
          return sum + pricePerRoom * criteria.roomCount;
        }, 0);

        availableRooms.push({
          roomCode: `${inv.InvCode}-${rate.RateCode}`,
          roomName: `${roomType.room_name} - ${ratePlan.rate_name}`,
          bedType: 'Standard',
          capacity: roomType.max_occupancy || 2,
          price: totalPrice,
          cancellationPolicy: 'As per hotel policy',
        });
      }
    }

    return availableRooms;
  }

  /**
   * Confirm hotel booking using OTA_HotelResNotifRQ (Booking Push)
   */
  async confirmBooking(bookingDetails: HotelConfirmationDTO): Promise<HotelConfirmationResult> {
    try {
      this.logger.log(`\n   📝 RESAVENUE: Confirming booking for hotel ${bookingDetails.hotelCode}`);

      // Extract room and rate codes from roomCode (format: "InvCode-RateCode")
      const [invCode, rateCode] = bookingDetails.rooms[0].roomCode.split('-');
      const uniqueBookingRef = `DVI-${Date.now()}`;
      const now = new Date();
      const timestamp = now.toISOString().replace(/\.\d{3}Z$/, '');

      // Build OTA_HotelResNotifRQ according to ResAvenue API documentation
      const bookingRequest = {
        OTA_HotelResNotifRQ: {
          Target: 'Production',
          Version: '1.0',
          EchoToken: `booking-${Date.now()}`,
          TimeStamp: timestamp,
          HotelReservations: {
            HotelReservation: [
              {
                UniqueID: {
                  ID: uniqueBookingRef,
                  OTA: 'DVI',
                  BookingSource: 'DVI Journey Manager',
                },
                ResStatus: 'Confirm',
                RoomStays: {
                  RoomStay: bookingDetails.rooms.map((room) => ({
                    TimeSpan: {
                      Start: bookingDetails.checkInDate,
                      End: bookingDetails.checkOutDate,
                    },
                    BasicPropertyInfo: {
                      HotelCode: bookingDetails.hotelCode,
                      HotelName: '',
                    },
                    GuestCounts: {
                      GuestCount: [
                        {
                          Count: room.guestCount,
                          AgeQualifyingCode: '10', // 10 = Adult
                        },
                      ],
                    },
                    RoomTypes: {
                      RoomType: {
                        NumberOfUnits: room.quantity,
                        RoomDescription: {
                          Name: '',
                        },
                        RoomTypeCode: invCode,
                      },
                    },
                    Total: {
                      CurrencyCode: 'INR',
                      Amount: 0, // Will be calculated from rates
                    },
                    RatePlans: {
                      RatePlan: {
                        RatePlanName: '',
                        RatePlanCode: rateCode,
                      },
                    },
                    RoomRates: {
                      RoomRate: {
                        Rates: [],
                      },
                    },
                    ResGuestRPHs: {
                      ResGuestRPH: {
                        RPH: 0,
                      },
                    },
                  })),
                },
                ResGlobalInfo: {
                  SpecialRequest: '',
                  Total: {
                    CurrencyCode: 'INR',
                    TotalTax: 0,
                    TaxType: 'Inclusive',
                    TotalBookingAmount: 0,
                    Commission: 0,
                    CommissionType: 'Inclusive',
                  },
                },
                CreateDateTime: timestamp,
                PayAtHotel: 'Y',
                ResGuests: {
                  ResGuest: bookingDetails.guests.map((guest, index) => ({
                    Profiles: {
                      ProfileInfo: {
                        UniqueID: {
                          Type: '1',
                          ID_Context: `Guest-${index + 1}`,
                        },
                        Profile: {
                          ProfileType: '1', // 1 = Guest
                          Customer: {
                            PersonName: {
                              GivenName: guest.firstName,
                              Surname: guest.lastName,
                            },
                            Email: guest.email,
                            Telephone: guest.phone,
                          },
                        },
                      },
                    },
                    ResGuestRPH: index,
                  })),
                },
              },
            ],
          },
        },
      };

      this.logger.debug(`📤 Booking request: ${JSON.stringify(bookingRequest, null, 2)}`);

      // Send booking push to ResAvenue
      const response = await this.http.post(
        `${this.BASE_URL}/PropertyDetails`,
        bookingRequest,
        {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          timeout: 30000,
        }
      );

      this.logger.debug(`📥 Booking response: ${JSON.stringify(response.data, null, 2)}`);

      // Check response status
      const status = response.data?.OTA_HotelResNotifRS?.Status;
      if (status === 'Failure') {
        const remark = response.data?.OTA_HotelResNotifRS?.Remark || 'Unknown error';
        throw new Error(`Booking failed: ${remark}`);
      }

      this.logger.log(`   ✅ Booking confirmed: ${uniqueBookingRef}`);

      return {
        provider: 'ResAvenue',
        confirmationReference: uniqueBookingRef,
        hotelCode: bookingDetails.hotelCode,
        hotelName: '',
        checkIn: bookingDetails.checkInDate,
        checkOut: bookingDetails.checkOutDate,
        roomCount: bookingDetails.roomCount,
        totalPrice: 0,
        priceBreadown: {
          roomCharges: 0,
          taxes: 0,
          discounts: 0,
        },
        cancellationPolicy: 'As per hotel policy',
        status: 'confirmed',
        bookingDeadline: new Date(Date.now() + 24 * 60 * 60 * 1000).toISOString(), // 24 hours
      };
    } catch (error) {
      this.logger.error(`   ❌ Booking confirmation failed: ${error.message}`);
      throw new InternalServerErrorException(`ResAvenue booking failed: ${error.message}`);
    }
  }

  /**
   * Cancel hotel booking using OTA_HotelResNotifRQ with ResStatus='Cancel'
   */
  async cancelBooking(confirmationRef: string, reason: string): Promise<CancellationResult> {
    try {
      this.logger.log(`\n   ❌ RESAVENUE: Cancelling booking ${confirmationRef}`);

      const now = new Date();
      const timestamp = now.toISOString().replace(/\.\d{3}Z$/, '');

      // Build cancellation request using OTA_HotelResNotifRQ format
      const cancellationRequest = {
        OTA_HotelResNotifRQ: {
          Target: 'Production',
          Version: '1.0',
          EchoToken: `cancel-${Date.now()}`,
          TimeStamp: timestamp,
          HotelReservations: {
            HotelReservation: [
              {
                UniqueID: {
                  ID: confirmationRef,
                  OTA: 'DVI',
                  BookingSource: 'DVI Journey Manager',
                },
                ResStatus: 'Cancel',
                ResGlobalInfo: {
                  SpecialRequest: reason,
                },
              },
            ],
          },
        },
      };

      this.logger.debug(`📤 Cancellation request: ${JSON.stringify(cancellationRequest, null, 2)}`);

      const response = await this.http.post(
        `${this.BASE_URL}/PropertyDetails`,
        cancellationRequest,
        {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          timeout: 30000,
        }
      );

      this.logger.debug(`📥 Cancellation response: ${JSON.stringify(response.data, null, 2)}`);

      // Check response status
      const status = response.data?.OTA_HotelResNotifRS?.Status;
      if (status === 'Failure') {
        const remark = response.data?.OTA_HotelResNotifRS?.Remark || 'Unknown error';
        throw new Error(`Cancellation failed: ${remark}`);
      }

      this.logger.log(`   ✅ Booking cancelled: ${confirmationRef}`);

      return {
        cancellationRef: confirmationRef,
        refundAmount: 0, // Would come from API response
        charges: 0, // Would come from API response
        refundDays: 5, // Default refund period
      };
    } catch (error) {
      this.logger.error(`   ❌ Cancellation failed: ${error.message}`);
      throw new InternalServerErrorException(`ResAvenue cancellation failed: ${error.message}`);
    }
  }

  /**
   * Get booking confirmation details using OTA_HotelResNotifRQ (Booking Pull)
   */
  async getConfirmation(confirmationRef: string): Promise<HotelConfirmationDetails> {
    try {
      this.logger.log(`\n   📋 RESAVENUE: Getting confirmation for ${confirmationRef}`);

      const now = new Date();
      const timestamp = now.toISOString().replace(/\.\d{3}Z$/, '');

      // Build booking pull request
      const pullRequest = {
        OTA_HotelResNotifRQ: {
          EchoToken: `pull-${Date.now()}`,
          TimeStamp: timestamp,
          Target: 'Production',
          Version: '1.0',
          PropertyId: confirmationRef, // Booking reference
          FromDate: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0], // Last 30 days
          ToDate: new Date().toISOString().split('T')[0], // Today
        },
      };

      this.logger.debug(`📤 Pull request: ${JSON.stringify(pullRequest, null, 2)}`);

      const response = await this.http.post(
        `${this.BASE_URL}/PropertyDetails`,
        pullRequest,
        {
          headers: {
            'Content-Type': 'application/json',
            Accept: 'application/json',
          },
          timeout: 30000,
        }
      );

      this.logger.debug(`📥 Pull response: ${JSON.stringify(response.data, null, 2)}`);

      const reservations = response.data?.OTA_HotelResNotifRQ?.HotelReservations;
      if (!reservations || reservations.length === 0) {
        throw new Error('Booking not found');
      }

      // Find the booking with matching ID
      const booking = reservations.find(
        (res: any) => res.HotelReservation?.[0]?.UniqueID?.ID === confirmationRef
      );

      if (!booking) {
        throw new Error('Booking not found');
      }

      const hotelRes = booking.HotelReservation[0];
      const roomStay = hotelRes.RoomStays?.RoomStay?.[0];
      const resGlobalInfo = hotelRes.ResGlobalInfo;

      this.logger.log(`   ✅ Booking details retrieved`);

      return {
        confirmationRef: confirmationRef,
        hotelName: roomStay?.BasicPropertyInfo?.HotelName || '',
        checkIn: roomStay?.TimeSpan?.Start || '',
        checkOut: roomStay?.TimeSpan?.End || '',
        roomCount: roomStay?.RoomTypes?.RoomType?.NumberOfUnits || 1,
        totalPrice: parseFloat(resGlobalInfo?.Total?.TotalBookingAmount) || 0,
        status: hotelRes.ResStatus || 'Confirmed',
        cancellationPolicy: 'As per hotel policy',
      };
    } catch (error) {
      this.logger.error(`   ❌ Get confirmation failed: ${error.message}`);
      throw new InternalServerErrorException(
        `Failed to get confirmation details: ${error.message}`
      );
    }
  }
}
