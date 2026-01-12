import { Injectable, Logger, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import { HotelSearchService } from '../../hotels/services/hotel-search.service';
import axios, { AxiosInstance } from 'axios';

interface TboHotelSelection {
  hotelCode: string;
  bookingCode: string;
  roomType: string;
  checkInDate: string;
  checkOutDate: string;
  numberOfRooms: number;
  guestNationality: string;
  netAmount: number;
  passengers: TboHotelPassenger[];
}

interface TboHotelPassenger {
  title: string;
  firstName: string;
  middleName?: string;
  lastName: string;
  email?: string;
  paxType: number; // 1 = Adult, 2 = Child
  leadPassenger: boolean;
  age: number;
  passportNo?: string;
  passportIssueDate?: string;
  passportExpDate?: string;
  phoneNo?: string;
  gstNumber?: string;
  gstCompanyName?: string;
  pan?: string;
}

interface PreBookResponse {
  Status: number;
  Message: string;
  TraceId?: string;
  Token?: string;
  BookingCode?: string;
  HotelCode?: string;
}

interface BookResponse {
  BookResult: {
    TBOReferenceNo: string | null;
    VoucherStatus: boolean;
    ResponseStatus: number;
    Error: {
      ErrorCode: number;
      ErrorMessage: string;
    };
    TraceId: string;
    Status: number;
    HotelBookingStatus: string | null;
    ConfirmationNo: string | null;
    BookingRefNo: string | null;
    BookingId: number;
    IsPriceChanged: boolean;
    IsCancellationPolicyChanged: boolean;
  };
}

@Injectable()
export class TboHotelBookingService {
  private readonly logger = new Logger(TboHotelBookingService.name);
  private readonly client: AxiosInstance;

  private readonly TBO_USERNAME = process.env.TBO_API_USERNAME || 'Doview';
  private readonly TBO_PASSWORD = process.env.TBO_API_PASSWORD || 'Doview@12345';
  private readonly PREBOOK_URL = 'https://affiliate.tektravels.com/HotelAPI/PreBook';
  private readonly BOOK_URL = 'https://hotelbe.tektravels.com/hotelservice.svc/rest/book';
  private readonly USE_MOCK_TBO = process.env.TBO_USE_MOCK === 'true' || false;

  constructor(
    private readonly prisma: PrismaService,
    private readonly hotelSearchService: HotelSearchService,
  ) {
    // Create axios client with explicit Authorization header (not auth object)
    const credentials = Buffer.from(`${this.TBO_USERNAME}:${this.TBO_PASSWORD}`).toString('base64');
    
    this.client = axios.create({
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Basic ${credentials}`,
      },
    });

    if (this.USE_MOCK_TBO) {
      this.logger.warn('⚠️  TBO_USE_MOCK is enabled - Using mock TBO responses');
    }
  }

  /**
   * Execute PreBook API call for TBO hotel
   * This confirms the room availability and locks the price
   */
  async preBookHotel(
    selection: TboHotelSelection,
  ): Promise<PreBookResponse> {
    try {
      this.logger.log(
        `🏨 PreBook: Hotel ${selection.hotelCode}, Booking Code: ${selection.bookingCode}`,
      );
      this.logger.log(
        `📤 PreBook Payload: BookingCode=${selection.bookingCode}, PaymentMode=Limit`,
      );

      // Check if using mock mode for development
      if (this.USE_MOCK_TBO) {
        return this.generateMockPreBookResponse(selection);
      }

      // TBO PreBook expects JSON with only BookingCode and PaymentMode
      const payload = {
        BookingCode: selection.bookingCode,
        PaymentMode: 'Limit',
      };

      this.logger.log(`📤 Full PreBook Payload (JSON): ${JSON.stringify(payload)}`);

      const response = await this.client.post<PreBookResponse>(
        this.PREBOOK_URL,
        payload,
        {
          headers: {
            'Content-Type': 'application/json',
          },
        },
      );

      if (!response.data) {
        this.logger.error('❌ PreBook response.data is undefined');
        throw new BadRequestException('PreBook response is empty or undefined');
      }

      // Handle TBO status response - it can be a number or object with Code/Description
      const statusCode = typeof response.data.Status === 'object' && response.data.Status
        ? (response.data.Status as any).Code 
        : response.data.Status;
      
      const statusMessage = typeof response.data.Status === 'object' && response.data.Status
        ? (response.data.Status as any).Description || response.data.Message
        : response.data.Message;

      // TBO PreBook uses Status.Code = 200 for success, other endpoints use Status.Code = 1
      if (statusCode !== 1 && statusCode !== 200) {
        const message = statusMessage || 'Unknown TBO error';
        this.logger.error(`❌ PreBook Status Code=${statusCode}: ${JSON.stringify(response.data)}`);
        throw new BadRequestException(
          `PreBook failed: ${message}`,
        );
      }

      this.logger.log(`✅ PreBook successful: ${JSON.stringify(response.data)}`);
      return response.data;
    } catch (error: any) {
      // Extract meaningful error message
      let errorMsg = 'Unknown error';
      
      if (error instanceof BadRequestException) {
        throw error; // Re-throw our own exceptions
      }
      
      if (error?.response?.data?.Message) {
        errorMsg = error.response.data.Message;
      } else if (error?.response?.data) {
        errorMsg = `TBO API Error: ${JSON.stringify(error.response.data)}`;
      } else if (error?.message) {
        errorMsg = error.message;
      } else if (typeof error === 'string') {
        errorMsg = error;
      }
      
      const statusCode = error?.response?.status;
      
      this.logger.error(`❌ PreBook error: ${errorMsg}`);
      if (statusCode) {
        this.logger.error(`   HTTP Status: ${statusCode}`);
      }
      
      throw new BadRequestException(
        `PreBook failed for hotel ${selection.hotelCode}: ${errorMsg}`,
      );
    }
  }

  /**
   * Execute Book API call to confirm hotel booking with guest details
   */
  async bookHotel(
    preBookResponse: PreBookResponse,
    selection: TboHotelSelection,
    endUserIp: string = '192.168.1.1',
  ): Promise<BookResponse> {
    try {
      // Check if using mock mode for development
      if (this.USE_MOCK_TBO) {
        return this.generateMockBookResponse(preBookResponse, selection);
      }

      // Map passengers to TBO format
      const hotelRoomsDetails = this.mapPassengersToRooms(
        selection.passengers,
        selection.numberOfRooms,
      );

      const bookingPayload = {
        BookingCode: preBookResponse.BookingCode || selection.bookingCode,
        IsVoucherBooking: false,
        GuestNationality: selection.guestNationality,
        EndUserIp: endUserIp,
        RequestedBookingMode: 1,
        NetAmount: selection.netAmount,
        HotelRoomsDetails: hotelRoomsDetails,
      };

      this.logger.log(
        `📝 Booking: Hotel ${selection.hotelCode}, Payload: ${JSON.stringify(bookingPayload)}`,
      );

      const response = await this.client.post<BookResponse>(
        this.BOOK_URL,
        bookingPayload,
      );

      // Log full response for debugging
      this.logger.log(`📥 Book API Response: ${JSON.stringify(response.data)}`);

      // Handle TBO status response - Book API returns BookResult.Status (1 for success)
      const bookResult = response.data.BookResult;
      const statusCode = bookResult.Status;
      const responseStatus = bookResult.ResponseStatus;

      // Check ResponseStatus (1 = success, 2 = error) or Status field
      if ((responseStatus && responseStatus !== 1) || (statusCode !== 1 && statusCode !== 200)) {
        const errorMessage = bookResult.Error?.ErrorMessage || 'Unknown error';
        this.logger.error(`❌ Book Status Code=${statusCode}, ResponseStatus=${responseStatus}: ${JSON.stringify(response.data)}`);
        throw new BadRequestException(
          `Booking failed: ${errorMessage}`,
        );
      }

      this.logger.log(`✅ Booking successful: ${JSON.stringify(response.data)}`);
      return response.data;
    } catch (error) {
      this.logger.error(`❌ Booking error: ${error.message}`);
      if (error.response) {
        this.logger.error(`❌ Book API Error Response: ${JSON.stringify(error.response.data)}`);
      }
      throw new BadRequestException(
        `Booking failed for hotel ${selection.hotelCode}: ${error.message}`,
      );
    }
  }

  /**
   * Map passengers to TBO hotel rooms format
   * Each room contains the passengers assigned to it
   * 
   * IMPORTANT: TBO requires passenger count to match what was searched
   * If user searched for 2 adults but only provides 1 passenger, duplicate it
   */
  private mapPassengersToRooms(
    passengers: TboHotelPassenger[],
    numberOfRooms: number,
  ) {
    // TBO hotel searches are typically for 2 adults per room
    // If only 1 passenger provided, duplicate it to meet TBO's expectations
    const EXPECTED_PASSENGERS_PER_ROOM = 2;
    
    let workingPassengers = [...passengers];
    
    // If we have fewer passengers than expected, duplicate them
    const expectedTotalPassengers = numberOfRooms * EXPECTED_PASSENGERS_PER_ROOM;
    while (workingPassengers.length < expectedTotalPassengers) {
      // Duplicate existing passengers
      const duplicatePassenger = { ...workingPassengers[0], leadPassenger: false };
      workingPassengers.push(duplicatePassenger);
    }

    const roomsPerSize = Math.ceil(workingPassengers.length / numberOfRooms);
    const rooms = [];

    for (let i = 0; i < numberOfRooms; i++) {
      const startIdx = i * roomsPerSize;
      const endIdx = Math.min(startIdx + roomsPerSize, workingPassengers.length);
      const roomPassengers = workingPassengers.slice(startIdx, endIdx);

      // Mark first passenger in room as lead
      const mappedPassengers = roomPassengers.map((p, idx) => ({
        Title: p.title,
        FirstName: p.firstName,
        MiddleName: p.middleName || '',
        LastName: p.lastName,
        Email: p.email || null,
        PaxType: p.paxType,
        LeadPassenger: idx === 0 ? true : false,
        Age: p.age,
        PassportNo: p.passportNo || null,
        PassportIssueDate: p.passportIssueDate || null,
        PassportExpDate: p.passportExpDate || null,
        Phoneno: p.phoneNo || null,
        PaxId: 0,
        GSTCompanyAddress: null,
        GSTCompanyContactNumber: null,
        GSTCompanyName: p.gstCompanyName || null,
        GSTNumber: p.gstNumber || null,
        GSTCompanyEmail: null,
        PAN: p.pan || null,
      }));

      rooms.push({
        HotelPassenger: mappedPassengers,
      });
    }

    return rooms;
  }

  /**
   * Save TBO booking confirmation to database
   */
  async saveTboBookingConfirmation(
    confirmedPlanId: number,
    itineraryPlanId: number,
    routeId: number,
    hotelCode: string,
    bookingResponse: BookResponse,
    selection: TboHotelSelection,
    userId: number,
  ) {
    try {
      const saved = await this.prisma.tbo_hotel_booking_confirmation.create({
        data: {
          confirmed_itinerary_plan_ID: confirmedPlanId,
          itinerary_plan_ID: itineraryPlanId,
          itinerary_route_ID: routeId,
          tbo_hotel_code: hotelCode,
          tbo_booking_id: String(bookingResponse.BookResult.BookingId || ''),
          tbo_booking_reference_number:
            bookingResponse.BookResult.BookingRefNo || '',
          tbo_trace_id: bookingResponse.BookResult.TraceId || '',
          booking_code: selection.bookingCode,
          check_in_date: new Date(selection.checkInDate),
          check_out_date: new Date(selection.checkOutDate),
          number_of_rooms: selection.numberOfRooms,
          net_amount: selection.netAmount,
          guest_nationality: selection.guestNationality,
          total_guests: selection.passengers.length,
          api_response: JSON.stringify(bookingResponse),
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      this.logger.log(
        `💾 Saved TBO booking confirmation: ID ${saved.tbo_hotel_booking_confirmation_ID}`,
      );
      return saved;
    } catch (error) {
      this.logger.error(`❌ Error saving booking confirmation: ${error.message}`);
      throw new BadRequestException(
        `Failed to save booking confirmation: ${error.message}`,
      );
    }
  }

  /**
   * Confirm multiple hotel bookings for an itinerary
   */
  async confirmItineraryHotels(
    confirmedPlanId: number,
    itineraryPlanId: number,
    selections: Array<{
      routeId: number;
      selection: TboHotelSelection;
    }>,
    endUserIp: string,
    userId: number,
  ) {
    const results = [];

    for (const { routeId, selection } of selections) {
      try {
        // Step 1: PreBook the hotel
        const preBookResponse = await this.preBookHotel(selection);

        // Step 2: Book the hotel with guest details
        const bookResponse = await this.bookHotel(
          preBookResponse,
          selection,
          endUserIp,
        );

        // Step 3: Save confirmation to database
        const savedConfirmation = await this.saveTboBookingConfirmation(
          confirmedPlanId,
          itineraryPlanId,
          routeId,
          selection.hotelCode,
          bookResponse,
          selection,
          userId,
        );

        results.push({
          routeId,
          hotelCode: selection.hotelCode,
          bookingId: String(bookResponse.BookResult.BookingId),
          status: 'confirmed',
          confirmation: savedConfirmation,
        });

        this.logger.log(
          `✅ Hotel booking completed for route ${routeId}: ${bookResponse.BookResult.BookingId}`,
        );
      } catch (error) {
        this.logger.error(
          `❌ Failed to book hotel for route ${routeId}: ${error.message}`,
        );
        results.push({
          routeId,
          hotelCode: selection.hotelCode,
          status: 'failed',
          error: error.message,
        });
      }
    }

    return results;
  }

  /**
   * Generate mock PreBook response for development/testing
   */
  private generateMockPreBookResponse(
    selection: TboHotelSelection,
  ): PreBookResponse {
    const mockResponse: PreBookResponse = {
      Status: 1,
      Message: 'PreBook Mock Successful',
      TraceId: `MOCK_TRACE_${Date.now()}`,
      Token: `MOCK_TOKEN_${selection.bookingCode}_${Date.now()}`,
      BookingCode: selection.bookingCode,
      HotelCode: selection.hotelCode,
    };

    this.logger.log(
      `✅ [MOCK] PreBook successful: ${JSON.stringify(mockResponse)}`,
    );
    return mockResponse;
  }

  /**
   * Generate mock Book response for development/testing
   */
  private generateMockBookResponse(
    preBookResponse: PreBookResponse,
    selection: TboHotelSelection,
  ): BookResponse {
    const mockResponse: BookResponse = {
      BookResult: {
        TBOReferenceNo: null,
        VoucherStatus: false,
        ResponseStatus: 1,
        Error: {
          ErrorCode: 0,
          ErrorMessage: '',
        },
        TraceId: preBookResponse.TraceId,
        Status: 1,
        HotelBookingStatus: 'Confirmed',
        ConfirmationNo: `MOCK_CONF_${Date.now()}`,
        BookingRefNo: `MOCK_REF_${selection.hotelCode}_${Date.now()}`,
        BookingId: Date.now(),
        IsPriceChanged: false,
        IsCancellationPolicyChanged: false,
      },
    };

    this.logger.log(
      `✅ [MOCK] Book successful: ${JSON.stringify(mockResponse)}`,
    );
    return mockResponse;
  }
}
