import { Injectable, Logger, InternalServerErrorException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import { HobseHotelProvider } from '../../hotels/providers/hobse-hotel.provider';
import { HotelSelectionDto } from '../dto/confirm-quotation.dto';

@Injectable()
export class HobseHotelBookingService {
  private readonly logger = new Logger(HobseHotelBookingService.name);

  constructor(
    private readonly prisma: PrismaService,
    private readonly hobseProvider: HobseHotelProvider,
  ) {}

  /**
   * Confirm and book HOBSE hotels for an itinerary
   */
  async confirmItineraryHotels(
    planId: number,
    hobseHotels: HotelSelectionDto[],
    contactDetails: { name: string; email: string; phone: string },
  ): Promise<any[]> {
    this.logger.log(`\n🏨 HOBSE HOTEL BOOKING SERVICE:`);
    this.logger.log(`   Plan ID: ${planId}`);
    this.logger.log(`   Hotels to book: ${hobseHotels.length}`);

    const bookingResults = [];

    for (const hotel of hobseHotels) {
      try {
        this.logger.log(`\n   📋 Processing HOBSE hotel: ${hotel.hotelCode}`);

        // Get route details
        const route = await (this.prisma as any).dvi_itinerary_plan_details.findUnique({
          where: { itinerary_plan_detail_id: hotel.routeId },
          include: {
            dvi_itinerary_plan_route_dates: true,
          },
        });

        if (!route) {
          throw new Error(`Route ${hotel.routeId} not found`);
        }

        const checkIn = route.dvi_itinerary_plan_route_dates[0]?.date;
        const checkOut = route.dvi_itinerary_plan_route_dates[route.dvi_itinerary_plan_route_dates.length - 1]?.date;

        if (!checkIn || !checkOut) {
          throw new Error(`Invalid route dates for route ${hotel.routeId}`);
        }

        // Book hotel via HOBSE API
        const bookingResult = await this.bookHotel({
          hotelCode: hotel.hotelCode,
          roomCode: (hotel as any).roomCode || '',
          occupancyCode: (hotel as any).occupancyCode || '',
          ratePlanCode: (hotel as any).ratePlanCode || '',
          checkInDate: checkIn.toISOString().split('T')[0],
          checkOutDate: checkOut.toISOString().split('T')[0],
          roomCount: (hotel as any).roomCount || 1,
          guests: hotel.passengers,
          contactName: contactDetails.name,
          contactEmail: contactDetails.email,
          contactPhone: contactDetails.phone,
        });

        // Save to database
        const savedBooking = await this.saveHobseBookingConfirmation(
          planId,
          hotel.routeId,
          bookingResult,
          hotel,
        );

        this.logger.log(`   ✅ HOBSE hotel booked successfully: ${bookingResult.confirmationReference}`);

        bookingResults.push({
          routeId: hotel.routeId,
          hotelCode: hotel.hotelCode,
          bookingId: savedBooking.hobse_hotel_booking_confirmation_ID,
          confirmationReference: bookingResult.confirmationReference,
          status: 'success',
          provider: 'HOBSE',
        });
      } catch (error) {
        this.logger.error(`   ❌ Error booking HOBSE hotel: ${error.message}`);
        bookingResults.push({
          routeId: hotel.routeId,
          hotelCode: hotel.hotelCode,
          status: 'failed',
          error: error.message,
          provider: 'HOBSE',
        });
      }
    }

    return bookingResults;
  }

  /**
   * Book hotel via HOBSE provider
   */
  private async bookHotel(bookingData: any): Promise<any> {
    try {
      this.logger.log(`   📞 Calling HOBSE API to book hotel ${bookingData.hotelCode}`);

      const result = await this.hobseProvider.confirmBooking(bookingData);

      this.logger.log(`   ✅ HOBSE API booking successful`);
      return result;
    } catch (error) {
      this.logger.error(`   ❌ HOBSE booking failed: ${error.message}`);
      throw error;
    }
  }

  /**
   * Save HOBSE booking confirmation to database
   */
  private async saveHobseBookingConfirmation(
    planId: number,
    routeId: number,
    bookingResult: any,
    hotelSelection: HotelSelectionDto,
  ): Promise<any> {
    try {
      this.logger.log(`   💾 Saving HOBSE booking to database...`);

      const booking = await this.prisma.hobse_hotel_booking_confirmation.create({
        data: {
          plan_id: planId,
          route_id: routeId,
          hotel_code: hotelSelection.hotelCode,
          booking_id: bookingResult.confirmationReference,
          check_in_date: new Date(bookingResult.checkIn),
          check_out_date: new Date(bookingResult.checkOut),
          room_count: (hotelSelection as any).roomCount || 1,
          guest_count: hotelSelection.passengers?.length || 1,
          total_amount: bookingResult.totalPrice,
          currency: 'INR',
          booking_status: 'confirmed',
          api_response: bookingResult,
          created_at: new Date(),
          updated_at: new Date(),
        },
      });

      this.logger.log(`   ✅ HOBSE booking saved: ID ${booking.hobse_hotel_booking_confirmation_ID}`);
      return booking;
    } catch (error) {
      this.logger.error(`   ❌ Error saving HOBSE booking: ${error.message}`);
      throw error;
    }
  }

  /**
   * Cancel HOBSE hotels for an itinerary
   */
  async cancelItineraryHotels(planId: number): Promise<void> {
    this.logger.log(`\n❌ HOBSE CANCELLATION SERVICE:`);
    this.logger.log(`   Plan ID: ${planId}`);

    try {
      // Get all confirmed HOBSE bookings for this plan
      const bookings = await this.prisma.hobse_hotel_booking_confirmation.findMany({
        where: {
          plan_id: planId,
          booking_status: 'confirmed',
        },
      });

      if (bookings.length === 0) {
        this.logger.log(`   ℹ️  No confirmed HOBSE bookings found for plan ${planId}`);
        return;
      }

      this.logger.log(`   📋 Found ${bookings.length} HOBSE bookings to cancel`);

      // Cancel each booking via HOBSE API
      for (const booking of bookings) {
        try {
          this.logger.log(`   📞 Cancelling HOBSE booking: ${booking.booking_id}`);

          const cancellationResult = await this.hobseProvider.cancelBooking(
            booking.booking_id,
            'Itinerary cancelled by user',
          );

          // Update booking status
          await this.prisma.hobse_hotel_booking_confirmation.update({
            where: {
              hobse_hotel_booking_confirmation_ID: booking.hobse_hotel_booking_confirmation_ID,
            },
            data: {
              booking_status: 'cancelled',
              cancellation_response: cancellationResult as Record<string, any>,
              updated_at: new Date(),
            },
          });

          this.logger.log(`   ✅ HOBSE booking cancelled: ${booking.booking_id}`);
        } catch (error) {
          this.logger.error(`   ❌ Error cancelling HOBSE booking ${booking.booking_id}: ${error.message}`);
          // Continue with other cancellations
        }
      }

      this.logger.log(`   ✅ HOBSE cancellation process complete`);
    } catch (error) {
      this.logger.error(`   ❌ HOBSE cancellation service error: ${error.message}`);
      throw error;
    }
  }

  /**
   * Cancel HOBSE hotel bookings for specific routes only
   */
  async cancelItineraryHotelsByRoutes(planId: number, routeIds: number[]): Promise<void> {
    this.logger.log(`\n❌ HOBSE ROUTE-BASED CANCELLATION SERVICE:`);
    this.logger.log(`   Plan ID: ${planId}`);
    this.logger.log(`   Route IDs: ${routeIds.join(',')}`);

    try {
      if (!routeIds || routeIds.length === 0) {
        this.logger.log(`   ℹ️  No route IDs provided for cancellation`);
        return;
      }

      // Get all confirmed HOBSE bookings for this plan and specific routes
      const bookings = await this.prisma.hobse_hotel_booking_confirmation.findMany({
        where: {
          plan_id: planId,
          route_id: { in: routeIds },
          booking_status: 'confirmed',
        },
      });

      if (bookings.length === 0) {
        this.logger.log(
          `   ℹ️  No confirmed HOBSE bookings found for plan ${planId} and routes [${routeIds.join(',')}]`,
        );
        return;
      }

      this.logger.log(`   📋 Found ${bookings.length} HOBSE bookings to cancel for selected routes`);

      // Cancel each booking via HOBSE API
      for (const booking of bookings) {
        try {
          this.logger.log(`   📞 Cancelling HOBSE booking: ${booking.booking_id} (Route ${booking.route_id})`);

          const cancellationResult = await this.hobseProvider.cancelBooking(
            booking.booking_id,
            'Hotel cancelled via voucher',
          );

          // Update booking status
          await this.prisma.hobse_hotel_booking_confirmation.update({
            where: {
              hobse_hotel_booking_confirmation_ID: booking.hobse_hotel_booking_confirmation_ID,
            },
            data: {
              booking_status: 'cancelled',
              cancellation_response: cancellationResult as Record<string, any>,
              updated_at: new Date(),
            },
          });

          this.logger.log(`   ✅ HOBSE booking cancelled: ${booking.booking_id} (Route ${booking.route_id})`);
        } catch (error) {
          this.logger.error(
            `   ❌ Error cancelling HOBSE booking ${booking.booking_id} (Route ${booking.route_id}): ${error.message}`,
          );
          // Continue with other cancellations
        }
      }

      this.logger.log(`   ✅ HOBSE route-based cancellation process complete`);
    } catch (error) {
      this.logger.error(`   ❌ HOBSE route-based cancellation service error: ${error.message}`);
      throw error;
    }
  }
}
