import {
  BadRequestException,
  Injectable,
  InternalServerErrorException,
  Logger,
} from '@nestjs/common';
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

interface HobseEnvelope<T = any> {
  hobse: {
    version: string;
    datetime: string;
    clientToken?: string;
    accessToken?: string;
    productToken?: string;
    response?: {
      status: {
        success: 'true' | 'false';
        code: string;
        message: string;
      };
      totalRecords?: number;
      data?: T;
      errors?: Array<{
        code?: string;
        message?: string;
        param?: any;
      }>;
    };
    request?: any;
  };
}

@Injectable()
export class HobseHotelProvider implements IHotelProvider {
  private readonly logger = new Logger(HobseHotelProvider.name);
  private http: AxiosInstance = axios;

  // e.g. https://api.hobse.com/v1/qa
  private readonly BASE_URL =
    process.env.HOBSE_BASE_URL || 'https://api.hobse.com/v1/qa';

  private readonly CLIENT_TOKEN = process.env.HOBSE_CLIENT_TOKEN || '';
  private readonly ACCESS_TOKEN = process.env.HOBSE_ACCESS_TOKEN || '';
  private readonly PRODUCT_TOKEN = process.env.HOBSE_PRODUCT_TOKEN || '';

  private readonly PARTNER_TYPE = process.env.HOBSE_PARTNER_TYPE || 'TA';
  private readonly PARTNER_ID = process.env.HOBSE_PARTNER_ID || '';
  private readonly PARTNER_TYPE_ID = process.env.HOBSE_PARTNER_TYPE_ID || '';
  private readonly PRICE_OWNER_TYPE = process.env.HOBSE_PRICE_OWNER_TYPE || '2';
  private readonly TARIFF_MODE = process.env.HOBSE_TARIFF_MODE || 'B2B';
  private readonly CHANNEL_NAME = process.env.HOBSE_CHANNEL_NAME || 'DVI';

  constructor(private readonly prisma: PrismaService) {}

  getName(): string {
    return 'HOBSE';
  }

  private buildParams(method: string, data: any): HobseEnvelope {
    // Ensure method has htl/ prefix for the request envelope
    const methodWithPrefix = method.startsWith('htl/') ? method : `htl/${method}`;
    
    return {
      hobse: {
        version: '1.0',
        datetime: new Date().toISOString().replace('Z', '+05:30'),
        clientToken: this.CLIENT_TOKEN,
        accessToken: this.ACCESS_TOKEN,
        productToken: this.PRODUCT_TOKEN,
        request: {
          method: methodWithPrefix,
          data: {
            ...data,
            resultType: 'json',
          },
        },
      },
    };
  }

  /**
   * Send application/x-www-form-urlencoded with key "params"
   */
  private async postForm<T = any>(method: string, data: any): Promise<HobseEnvelope<T>> {
    try {
      const url = `${this.BASE_URL}/${method}`; // BASE_URL already includes /htl, just append method name
      const paramsEnvelope = this.buildParams(method, data);

      const form = new URLSearchParams();
      form.append('params', JSON.stringify(paramsEnvelope));

      this.logger.debug(`📤 HOBSE POST ${url}`);
      this.logger.debug(`📋 HOBSE Params: ${JSON.stringify(paramsEnvelope).substring(0, 200)}...`);

      const resp = await this.http.post<HobseEnvelope<T>>(url, form.toString(), {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        timeout: 45000,
      });

      const ok = resp?.data?.hobse?.response?.status?.success === 'true';
      if (!ok) {
        const errorMessage = resp?.data?.hobse?.response?.status?.message ||
          resp?.data?.hobse?.response?.errors?.[0]?.message ||
          JSON.stringify(resp?.data?.hobse?.response) ||
          'Unknown HOBSE API error';
        this.logger.warn(`⚠️  HOBSE Response: ${errorMessage}`);
        throw new Error(errorMessage);
      }

      return resp.data;
    } catch (e: any) {
      this.logger.error(`❌ HOBSE API failed: ${e?.message}`);
      throw new InternalServerErrorException(`HOBSE API failed: ${e?.message}`);
    }
  }

  /**
   * Pick cheapest room option from GetAvailableRoomTariff response
   */
  private pickCheapestRoomOption(roomOptions: any[] = []) {
    if (!roomOptions || roomOptions.length === 0) {
      this.logger.warn(`⚠️  HOBSE: roomOptions array is empty`);
      return null;
    }

    let best: any = null;
    let bestPrice = Number.POSITIVE_INFINITY;

    for (const opt of roomOptions) {
      // Handle ratesData array
      const rates = Array.isArray(opt?.ratesData) ? opt.ratesData : [];
      
      if (rates.length === 0) {
        this.logger.debug(`⚠️  HOBSE: Room option has no ratesData: ${opt?.roomCode}`);
        // Try to use direct price fields if ratesData is not available
        const directPrice = Number(opt?.totalCost || opt?.roomCost || opt?.price || 0);
        if (directPrice > 0 && directPrice < bestPrice) {
          bestPrice = directPrice;
          best = {
            roomCode: opt.roomCode,
            roomName: opt.roomName,
            occupancyTypeCode: opt.occupancyTypeCode,
            occupancyTypeName: opt.occupancyTypeName,
            ratePlanCode: opt.ratePlanCode,
            ratePlanName: opt.ratePlanName,
            tariff: String(opt?.roomCost || opt?.price || 0),
            tax: String(opt?.tax || 0),
          };
        }
        continue;
      }

      for (const r of rates) {
        const price = Number(r?.totalCostWithTax || r?.roomCost || r?.totalCost || r?.cost || 0);
        if (price > 0 && price < bestPrice) {
          bestPrice = price;
          best = {
            roomCode: opt.roomCode,
            roomName: opt.roomName,
            occupancyTypeCode: opt.occupancyTypeCode,
            occupancyTypeName: opt.occupancyTypeName,
            ratePlanCode: opt.ratePlanCode,
            ratePlanName: opt.ratePlanName,
            tariff: String(r?.roomCost || r?.basePrice || 0),
            tax: String(r?.taxes || r?.tax || 0),
          };
        }
      }
    }

    if (best) {
      this.logger.log(`✅ HOBSE: Selected room ${best.roomCode} at ₹${bestPrice}`);
    } else {
      this.logger.warn(`⚠️  HOBSE: No suitable room found in ${roomOptions.length} options`);
    }

    return best;
  }

  /**
   * SEARCH
   * criteria.cityCode here is your itinerary flow code (usually TBO city code).
   * We map it to dvi_cities.hobse_city_code + city name (for filtering GetHotelList by cityName).
   */
  async search(criteria: HotelSearchCriteria, _preferences?: HotelPreferences): Promise<HotelSearchResult[]> {
    try {
      this.logger.log(`\n   📡 HOBSE SEARCH: cityCode=${criteria.cityCode}, ${criteria.checkInDate}→${criteria.checkOutDate}`);

      // Convert cityCode to string for database lookup (Prisma expects string)
      const cityCodeAsString = String(criteria.cityCode);

      // Build where conditions with proper string values
      const orConditions: any[] = [
        { tbo_city_code: cityCodeAsString },
        { hobse_city_code: cityCodeAsString },
        { name: cityCodeAsString }, // Also support lookup by city name directly
      ];

      const cityRow = await this.prisma.dvi_cities.findFirst({
        where: {
          OR: orConditions,
        },
        select: { name: true, hobse_city_code: true },
      });

      if (!cityRow?.hobse_city_code || !cityRow?.name) {
        this.logger.warn(`   ⚠️  No HOBSE mapping for cityCode: ${criteria.cityCode}`);
        return [];
      }

      // Get full hotel list (empty parameters - API returns all hotels)
      const listResp = await this.postForm('GetHotelList', {});
      const allHotels: any[] = Array.isArray(listResp?.hobse?.response?.data) ? listResp.hobse.response.data : [];

      // Filter by city name
      const cityNameLower = cityRow.name.toLowerCase();
      const cityHotels = allHotels.filter((h) => (h?.cityName || '').toLowerCase() === cityNameLower);

      if (cityHotels.length === 0) {
        this.logger.warn(`   📭 No HOBSE hotels found for city: ${cityRow.name}`);
        return [];
      }

      const results: HotelSearchResult[] = [];

      // limited concurrency
      const concurrency = 5;
      for (let i = 0; i < cityHotels.length; i += concurrency) {
        const slice = cityHotels.slice(i, i + concurrency);

        const chunk = await Promise.all(
          slice.map((h) =>
            this.getHotelTariffAsSearchResult({
              hobseCityId: cityRow.hobse_city_code!,
              hotelId: h.hotelId,
              hotelName: h.hotelName,
              starCategory: h.starCategory,
              address: h.address,
              criteria,
            }).catch((e) => {
              this.logger.error(`   ❌ Tariff failed for hotel ${h?.hotelId}: ${e?.message || e}`);
              return null;
            }),
          ),
        );

        chunk.filter(Boolean).forEach((x) => results.push(x as HotelSearchResult));
      }

      this.logger.log(`   ✅ HOBSE: returning ${results.length}/${cityHotels.length} hotels with tariffs`);
      return results;
    } catch (error: any) {
      this.logger.error(`   ❌ HOBSE search error: ${error?.message || error}`);
      return [];
    }
  }

  private async getHotelTariffAsSearchResult(args: {
    hobseCityId: string;
    hotelId: string;
    hotelName: string;
    starCategory?: string;
    address?: string;
    criteria: HotelSearchCriteria;
  }): Promise<HotelSearchResult | null> {
    const { hobseCityId, hotelId, hotelName, starCategory, address, criteria } = args;

    const sessionId = `DVI-${Date.now()}-${Math.random().toString(16).slice(2)}`;

    const adultCount = String(Math.max(1, criteria.guestCount || 2));
    const roomData = [{ adultCount, childCount: '0', infantCount: '0' }];

    const req = {
      sessionId,
      fromDate: criteria.checkInDate,
      toDate: criteria.checkOutDate,
      cityId: String(hobseCityId),
      priceOwnerType: String(this.PRICE_OWNER_TYPE),
      partnerId: String(this.PARTNER_ID),
      partnerTypeId: String(this.PARTNER_TYPE_ID),
      tariffMode: String(this.TARIFF_MODE),
      roomData,
      hotelFilter: [{ hotelId }],
    };

    const tariffResp = await this.postForm('GetAvailableRoomTariff', req);
    const payload = tariffResp?.hobse?.response?.data;

    const list = Array.isArray(payload) ? payload : [];
    if (!Array.isArray(payload) || list.length === 0) return null;

    const hotelTariff = list[0];
    const roomOptions: any[] = Array.isArray(hotelTariff?.roomOptions) ? hotelTariff.roomOptions : [];
    if (roomOptions.length === 0) return null;

    let minPrice = Number.POSITIVE_INFINITY;
    let bestOption: any = null;

    const roomTypes: RoomType[] = [];

    for (const opt of roomOptions) {
      const ratesData = Array.isArray(opt?.ratesData) ? opt.ratesData : [];
      const firstRate = ratesData[0] || {};

      const priceStr = firstRate?.roomCost ?? firstRate?.totalCostWithTax ?? firstRate?.totalCost ?? '0';
      const price = parseFloat(String(priceStr)) || 0;

      if (price > 0 && price < minPrice) {
        minPrice = price;
        bestOption = opt;
      }

      roomTypes.push({
        roomCode: opt.roomCode || '',
        roomName: opt.roomName || opt.roomDesc || 'Room',
        bedType: opt.occupancyTypeName || 'Standard',
        capacity: Number(firstRate?.totalPax || criteria.guestCount || 2),
        price,
        cancellationPolicy: '',
      });
    }

    if (!bestOption || !isFinite(minPrice) || minPrice <= 0) return null;

    const roomTypeName = bestOption?.roomName || bestOption?.roomDesc || '';
    const mealPlan = bestOption?.ratePlanName || bestOption?.ratePlanDesc || '-';

    return {
      provider: 'HOBSE',
      hotelCode: hotelId,
      hotelName: hotelName || 'HOBSE Hotel',
      cityCode: criteria.cityCode,
      address: address || '',
      rating: parseInt(String(starCategory || hotelTariff?.starCategory || '0'), 10) || 0,
      category: starCategory ? `${starCategory}-Star` : '',
      facilities: [],
      images: [],
      price: minPrice,
      currency: hotelTariff?.currencyCode || 'INR',
      roomTypes,
      roomType: roomTypeName,
      mealPlan,
      searchReference: JSON.stringify({
        provider: 'HOBSE',
        hotelId,
        cityId: hobseCityId,
        checkInDate: criteria.checkInDate,
        checkOutDate: criteria.checkOutDate,
        priceOwnerType: this.PRICE_OWNER_TYPE,
        partnerId: this.PARTNER_ID,
        partnerTypeId: this.PARTNER_TYPE_ID,
        tariffMode: this.TARIFF_MODE,
        roomCode: bestOption?.roomCode,
        occupancyTypeCode: bestOption?.occupancyTypeCode,
        ratePlanCode: bestOption?.ratePlanCode,
      }),
      expiresAt: new Date(Date.now() + 30 * 60 * 1000),
    };
  }

  // Not needed for hotel listing endpoint right now
  async getConfirmation(_confirmationRef: string): Promise<HotelConfirmationDetails> {
    throw new InternalServerErrorException('HOBSE confirmation not implemented');
  }

  async confirmBooking(_bookingDetails: HotelConfirmationDTO): Promise<HotelConfirmationResult> {
    throw new InternalServerErrorException(
      'HOBSE booking must be called via HobseHotelBookingService.confirmItineraryHotels()',
    );
  }

  async cancelBooking(_confirmationRef: string, _reason: string): Promise<CancellationResult> {
    throw new InternalServerErrorException('HOBSE cancel not implemented');
  }

  /**
   * MAIN BOOKING METHOD - Used by HobseHotelBookingService
   * Flow:
   * 1) GetAvailableRoomTariff
   * 2) CalculateReservationCost
   * 3) CreateBooking
   */
  async createBookingFromItinerary(input: {
    hotelId: string;
    cityId: string; // hobse city id
    checkInDate: string; // YYYY-MM-DD
    checkOutDate: string; // YYYY-MM-DD
    adultCount: number;
    childCount: number;
    infantCount: number;

    guest: {
      title: string;
      firstName: string;
      lastName: string;
      mobileNumber: string;
      email: string;
      address?: string;
      state?: string;
      city?: string;
      country?: string;
      billToTaxNumber?: string;
      billToCompanyName?: string;
      billToAddress?: string;
    };

    channelBookingId: string;
    bookingDesc?: string;
  }) {
    // 1) GetAvailableRoomTariff
    const sessionId = `DVI-${Date.now()}`;

    const tariffResp = await this.postForm<any>('GetAvailableRoomTariff', {
      sessionId,
      fromDate: input.checkInDate,
      toDate: input.checkOutDate,
      cityId: input.cityId,
      priceOwnerType: this.PRICE_OWNER_TYPE,
      partnerId: this.PARTNER_ID,
      partnerTypeId: this.PARTNER_TYPE_ID,
      tariffMode: this.TARIFF_MODE,
      roomData: [
        {
          adultCount: String(input.adultCount),
          childCount: String(input.childCount),
          infantCount: String(input.infantCount),
        },
      ],
      hotelFilter: [{ hotelId: input.hotelId }],
    });

    const data = tariffResp?.hobse?.response?.data;
    const hotelRow = Array.isArray(data) ? data[0] : null;
    
    this.logger.debug(`📦 HOBSE GetAvailableRoomTariff response data type: ${Array.isArray(data) ? 'array' : typeof data}`);
    if (Array.isArray(data)) {
      this.logger.debug(`   Available hotels in response: ${data.length}`);
    }
    
    if (!hotelRow) {
      this.logger.error(`❌ No hotel row in HOBSE response. Full data: ${JSON.stringify(data).substring(0, 500)}`);
      throw new Error('No hotel data returned from HOBSE GetAvailableRoomTariff');
    }
    
    if (!hotelRow?.roomOptions?.length) {
      this.logger.error(`❌ No roomOptions in hotel row. Available keys: ${Object.keys(hotelRow).join(', ')}`);
      this.logger.error(`   hotelRow data: ${JSON.stringify(hotelRow).substring(0, 500)}`);
      throw new Error('No roomOptions returned from HOBSE GetAvailableRoomTariff');
    }

    this.logger.debug(`📋 Found ${hotelRow.roomOptions.length} room options, selecting cheapest...`);
    const best = this.pickCheapestRoomOption(hotelRow.roomOptions);
    if (!best) {
      this.logger.error(`❌ Unable to pick best room. Room options: ${JSON.stringify(hotelRow.roomOptions).substring(0, 500)}`);
      throw new Error('Unable to select cheapest room option from HOBSE response');
    }

    // 2) CalculateReservationCost
    const costResp = await this.postForm<any>('CalculateReservationCost', {
      hotelId: input.hotelId,
      priceOwnerType: this.PRICE_OWNER_TYPE,
      partnerType: this.PARTNER_TYPE,
      partnerId: this.PARTNER_ID,
      partnerTypeId: this.PARTNER_TYPE_ID,
      checkInDate: input.checkInDate,
      checkOutDate: input.checkOutDate,
      tariffMode: this.TARIFF_MODE,
      roomData: [
        {
          roomCode: best.roomCode,
          occupancyTypeCode: best.occupancyTypeCode,
          ratePlanCode: best.ratePlanCode,
          adultCount: String(input.adultCount),
          childCount: String(input.childCount),
          infantCount: String(input.infantCount),
        },
      ],
    });

    const costData = Array.isArray(costResp?.hobse?.response?.data)
      ? costResp.hobse.response.data[0]
      : null;

    if (!costData) {
      throw new Error('No cost data returned from HOBSE CalculateReservationCost');
    }

    const totalTariff = String(costData.totalTariff || '0.00');
    const totalTax = String(costData.totalTax || '0.00');
    const totalReservationCost = String(costData.totalReservationCost || '0.00');

    // 3) CreateBooking
    const createResp = await this.postForm<any>('CreateBooking', {
      bookingData: {
        hotelId: input.hotelId,
        channelBookingId: input.channelBookingId,
        pmsBookingId: '',
        priceOwnerType: this.PRICE_OWNER_TYPE,
        bookingStatus: '0',
        tariffMode: this.TARIFF_MODE,
        checkInDate: input.checkInDate,
        checkOutDate: input.checkOutDate,
        bookingAmount: totalReservationCost,
        netRate: totalTariff,
        taxAmount: totalTax,
        paidAmount: totalTariff,
        isPayAtHotel: 'Yes',
        amountToBeCollected: totalTariff,
        paymentInstruction: 'Pay at Checkout',
        bookingDesc: input.bookingDesc || '',
      },
      roomData: [
        {
          roomCode: best.roomCode,
          occupancyTypeCode: best.occupancyTypeCode,
          ratePlanCode: best.ratePlanCode,
          adultCount: String(input.adultCount),
          childCount: String(input.childCount),
          infantCount: String(input.infantCount),
          tariff: best.tariff,
          tax: best.tax,
        },
      ],
      guestData: {
        title: input.guest.title,
        firstName: input.guest.firstName,
        lastName: input.guest.lastName,
        mobileNumber: input.guest.mobileNumber,
        email: input.guest.email,
        address: input.guest.address || '',
        state: input.guest.state || '',
        city: input.guest.city || '',
        country: input.guest.country || 'India',
        billToTaxNumber: input.guest.billToTaxNumber || '',
        billToCompanyName: input.guest.billToCompanyName || '',
        billToAddress: input.guest.billToAddress || '',
      },
      bookingSourceData: {
        partnerType: this.PARTNER_TYPE,
        partnerId: this.PARTNER_ID,
        partnerTypeId: this.PARTNER_TYPE_ID,
        partnerCode: '',
        partnerName: '',
        partnerEmail: '',
        partnerPhoneNumber: '',
        partnerAddress: '',
        partnerState: '',
        partnerCity: '',
        partnerCountry: '',
        channelName: this.CHANNEL_NAME,
      },
    });

    return {
      provider: 'HOBSE',
      hotelId: input.hotelId,
      checkInDate: input.checkInDate,
      checkOutDate: input.checkOutDate,
      selectedRoom: best,
      cost: {
        totalTariff,
        totalTax,
        totalReservationCost,
        cancelTerm: costData.cancelTerm || [],
        bookingPolicy: costData.bookingPolicy || '',
      },
      apiResponse: createResp,
    };
  }
}
