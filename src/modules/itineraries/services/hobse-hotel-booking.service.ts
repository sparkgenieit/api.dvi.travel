import { Injectable, Logger } from '@nestjs/common';
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

  async confirmItineraryHotels(
    planId: number,
    hobseHotels: HotelSelectionDto[],
    primaryGuest: {
      salutation: string;
      name: string;
      phone: string;
      email?: string;
    },
  ): Promise<any[]> {
    this.logger.log(`🏨 HOBSE BOOKING: Plan ${planId}, hotels=${hobseHotels.length}`);

    const results: any[] = [];

    for (const sel of hobseHotels) {
      try {
        // 1) Find route city (needed for hobse city id)
        const route = await (this.prisma as any).dvi_itinerary_route_details.findFirst({
          where: { itinerary_route_ID: sel.routeId },
          select: { next_visiting_location: true },
        });

        const cityName = route?.next_visiting_location;
        if (!cityName) throw new Error(`Route city not found for routeId=${sel.routeId}`);

        // 2) Map to hobse_city_code
        const city = await this.prisma.dvi_cities.findFirst({
          where: { name: cityName },
          select: { hobse_city_code: true },
        });

        const hobseCityId = city?.hobse_city_code;
        if (!hobseCityId) throw new Error(`City '${cityName}' not mapped to hobse_city_code`);

        // 3) Count pax
        const adults = sel.passengers?.filter((p) => p.paxType === 1).length || 1;
        const children = sel.passengers?.filter((p) => p.paxType === 2).length || 0;
        const infants = sel.passengers?.filter((p) => p.paxType === 3).length || 0;

        // 4) Guest split: use lead passenger if present, else primary guest
        const lead = sel.passengers?.find((p) => p.leadPassenger) || sel.passengers?.[0];

        const [firstName, ...rest] = (lead?.firstName || primaryGuest.name || 'Guest').split(' ');
        const lastName = (lead?.lastName || rest.join(' ') || '').trim();

        // 5) Unique channelBookingId (prevents duplicate error)
        const channelBookingId = `DVI-${planId}-${sel.routeId}-${Date.now()}`;

        // 6) Call provider booking
        const booking = await this.hobseProvider.createBookingFromItinerary({
          hotelId: sel.hotelCode,
          cityId: String(hobseCityId),
          checkInDate: sel.checkInDate,
          checkOutDate: sel.checkOutDate,
          adultCount: adults,
          childCount: children,
          infantCount: infants,
          channelBookingId,
          bookingDesc: sel.roomType || '',

          guest: {
            title: lead?.title || primaryGuest.salutation || 'Mr',
            firstName,
            lastName: lastName || 'Guest',
            mobileNumber: lead?.phoneNo || primaryGuest.phone,
            email: lead?.email || primaryGuest.email || '',
            address: '',
            state: '',
            city: cityName,
            country: 'India',
          },
        });

        // 7) Save confirmation row
        const saved = await (this.prisma as any).hobse_hotel_booking_confirmation.create({
          data: {
            plan_id: planId,
            route_id: sel.routeId,
            hotel_code: sel.hotelCode,
            booking_id: channelBookingId,
            check_in_date: new Date(sel.checkInDate),
            check_out_date: new Date(sel.checkOutDate),
            room_count: sel.numberOfRooms || 1,
            guest_count: sel.passengers?.length || 1,
            total_amount: Number(booking?.cost?.totalReservationCost || 0),
            currency: 'INR',
            booking_status: 'confirmed',
            api_response: booking as any,
            created_at: new Date(),
            updated_at: new Date(),
          },
        });

        results.push({
          provider: 'HOBSE',
          routeId: sel.routeId,
          hotelId: sel.hotelCode,
          channelBookingId,
          dbId: saved.hobse_hotel_booking_confirmation_ID,
          status: 'success',
        });
      } catch (e: any) {
        this.logger.error(`❌ HOBSE booking failed: ${e?.message}`);
        results.push({
          provider: 'HOBSE',
          routeId: sel.routeId,
          hotelId: sel.hotelCode,
          status: 'failed',
          error: e?.message,
        });
      }
    }

    return results;
  }

  /**
   * Cancel HOBSE bookings for specific routes
   */
  async cancelItineraryHotelsByRoutes(
    planId: number,
    routeIds: number[],
  ): Promise<any> {
    this.logger.log(`🗑️ HOBSE CANCELLATION: Plan ${planId}, routes=${routeIds}`);

    const bookings = await (this.prisma as any).hobse_hotel_booking_confirmation.findMany({
      where: {
        plan_id: planId,
        route_id: { in: routeIds },
      },
    });

    this.logger.log(`Found ${bookings.length} HOBSE bookings to cancel`);

    for (const booking of bookings) {
      try {
        // Call HOBSE cancellation API if available
        // For now, just mark as cancelled in DB
        await (this.prisma as any).hobse_hotel_booking_confirmation.update({
          where: { hobse_hotel_booking_confirmation_ID: booking.hobse_hotel_booking_confirmation_ID },
          data: { booking_status: 'cancelled' },
        });
        this.logger.log(`✅ Cancelled HOBSE booking ${booking.booking_id}`);
      } catch (e: any) {
        this.logger.error(`❌ HOBSE cancellation failed: ${e?.message}`);
      }
    }

    return { success: true };
  }

  /**
   * Cancel all HOBSE bookings for an itinerary
   */
  async cancelItineraryHotels(planId: number): Promise<any> {
    this.logger.log(`🗑️ HOBSE FULL CANCELLATION: Plan ${planId}`);

    const bookings = await (this.prisma as any).hobse_hotel_booking_confirmation.findMany({
      where: { plan_id: planId },
    });

    this.logger.log(`Found ${bookings.length} HOBSE bookings to cancel`);

    for (const booking of bookings) {
      try {
        // Call HOBSE cancellation API if available
        // For now, just mark as cancelled in DB
        await (this.prisma as any).hobse_hotel_booking_confirmation.update({
          where: { hobse_hotel_booking_confirmation_ID: booking.hobse_hotel_booking_confirmation_ID },
          data: { booking_status: 'cancelled' },
        });
        this.logger.log(`✅ Cancelled HOBSE booking ${booking.booking_id}`);
      } catch (e: any) {
        this.logger.error(`❌ HOBSE cancellation failed: ${e?.message}`);
      }
    }

    return { success: true };
  }
}
