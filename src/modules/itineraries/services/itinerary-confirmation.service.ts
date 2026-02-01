import { Injectable, BadRequestException, NotFoundException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import { ConfirmQuotationDto } from '../dto/confirm-quotation.dto';
import { CancelItineraryDto } from '../dto/cancel-itinerary.dto';
import { ItineraryDetailsService } from '../itinerary-details.service';

/**
 * Itinerary Confirmation Service
 * Handles confirmation, booking, and cancellation operations
 * - Confirm quotations with wallet deductions
 * - Process hotel bookings with TBO/ResAvenue/HOBSE
 * - Cancel itineraries
 */
@Injectable()
export class ItineraryConfirmationService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly itineraryDetails: ItineraryDetailsService,
  ) {}

  /**
   * Confirm a quotation and deduct from wallet
   */
  async confirmQuotation(dto: ConfirmQuotationDto) {
    const userId = 1; // TODO: Get from authenticated user

    // 1. Get plan details and cost breakdown
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: dto.itinerary_plan_ID },
    });

    if (!plan) {
      throw new NotFoundException('Itinerary plan not found');
    }

    const quoteId = plan.itinerary_quote_ID;
    if (!quoteId) {
      throw new BadRequestException('Quote ID not found for this plan');
    }

    const details = await this.itineraryDetails.getItineraryDetails(quoteId);
    const cost = details.costBreakdown;

    // 2. Check wallet balance
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: dto.agent },
      select: { total_cash_wallet: true },
    });

    if (!agent || agent.total_cash_wallet < cost.netPayable) {
      throw new BadRequestException(
        `Insufficient wallet balance. Required: ${cost.netPayable}, Available: ${agent?.total_cash_wallet || 0}`,
      );
    }

    // Parse arrival and departure dates
    const parseDateTime = (dateTimeStr: string) => {
      // Format: "12-12-2025 9:00 AM"
      const [datePart, timePart, meridiem] = dateTimeStr.split(' ');
      const [day, month, year] = datePart.split('-');
      let [hours, minutes] = timePart.split(':').map(Number);

      if (meridiem === 'PM' && hours !== 12) hours += 12;
      if (meridiem === 'AM' && hours === 12) hours = 0;

      return new Date(Number(year), Number(month) - 1, Number(day), hours, Number(minutes));
    };

    const arrivalDateTime = parseDateTime(dto.arrival_date_time);
    const departureDateTime = parseDateTime(dto.departure_date_time);

    // 2.5 Save draft hotel records with group_type BEFORE transaction
    if (dto.hotel_bookings && dto.hotel_bookings.length > 0) {
      const groupType = Number(dto.hotel_group_type) || 1;
      console.log(`[Confirm Quotation] Saving ${dto.hotel_bookings.length} draft hotel records with group_type=${groupType}`);

      for (const booking of dto.hotel_bookings) {
        const isHobse = booking.provider === 'HOBSE';
        const hotelId = isHobse ? 0 : parseInt(booking.hotelCode);

        const findWhere = isHobse
          ? {
              itinerary_plan_id: dto.itinerary_plan_ID,
              itinerary_route_id: booking.routeId,
              hotel_code: booking.hotelCode,
              deleted: 0,
            }
          : {
              itinerary_plan_id: dto.itinerary_plan_ID,
              itinerary_route_id: booking.routeId,
              hotel_id: hotelId,
              deleted: 0,
            };

        const existing = await this.prisma.dvi_itinerary_plan_hotel_details.findFirst({
          where: findWhere as any,
        });

        if (existing) {
          await this.prisma.dvi_itinerary_plan_hotel_details.update({
            where: {
              itinerary_plan_hotel_details_ID: existing.itinerary_plan_hotel_details_ID,
            },
            data: {
              group_type: groupType,
              total_hotel_cost: booking.netAmount || 0,
              updatedon: new Date(),
            },
          });
        } else {
          const createData: any = {
            itinerary_plan_id: dto.itinerary_plan_ID,
            itinerary_route_id: booking.routeId,
            group_type: groupType,
            total_hotel_cost: booking.netAmount || 0,
            hotel_required: 1,
            createdby: userId,
            createdon: new Date(),
            status: 1,
            deleted: 0,
          };

          if (isHobse) {
            createData.hotel_id = 0;
            createData.hotel_code = booking.hotelCode;
          } else {
            createData.hotel_id = hotelId;
          }

          await this.prisma.dvi_itinerary_plan_hotel_details.create({
            data: createData,
          });
        }
      }
    }

    // 3. Start Transaction
    return await this.prisma.$transaction(async (tx) => {
      // A. Deduct from wallet
      await tx.dvi_cash_wallet.create({
        data: {
          agent_id: dto.agent,
          transaction_date: new Date(),
          transaction_amount: cost.netPayable,
          transaction_type: 2, // Debit
          remarks: `Confirmed Itinerary: ${quoteId}`,
          transaction_id: quoteId,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      await tx.dvi_agent.update({
        where: { agent_ID: dto.agent },
        data: {
          total_cash_wallet: {
            decrement: cost.netPayable,
          },
        },
      });

      // B. Insert into dvi_confirmed_itinerary_plan_details
      const confirmedPlan = await tx.dvi_confirmed_itinerary_plan_details.create({
        data: {
          itinerary_plan_ID: plan.itinerary_plan_ID,
          agent_id: dto.agent,
          staff_id: plan.staff_id || 0,
          location_id: plan.location_id || 0n,
          arrival_location: plan.arrival_location,
          departure_location: plan.departure_location,
          itinerary_quote_ID: plan.itinerary_quote_ID,
          trip_start_date_and_time: plan.trip_start_date_and_time,
          trip_end_date_and_time: plan.trip_end_date_and_time,
          total_hotspot_charges: cost.totalHotspotCost || 0,
          total_activity_charges: cost.totalActivityCost || 0,
          total_hotel_charges: cost.totalHotelAmount || 0,
          total_vehicle_charges: cost.totalVehicleAmount || 0,
          total_guide_charges: cost.totalGuideCost || 0,
          itinerary_sub_total: (cost.totalHotelAmount || 0) + (cost.totalVehicleAmount || 0),
          itinerary_agent_margin_charges: cost.agentMargin || 0,
          itinerary_gross_total_amount: cost.totalAmount || 0,
          itinerary_total_margin_cost: cost.additionalMargin || 0,
          itinerary_total_net_payable_amount: cost.netPayable,
          itinerary_total_paid_amount: cost.netPayable,
          itinerary_total_balance_amount: 0,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      const confirmedPlanId = confirmedPlan.confirmed_itinerary_plan_ID;

      // C. Insert Primary Guest
      await tx.dvi_confirmed_itinerary_customer_details.create({
        data: {
          confirmed_itinerary_plan_ID: confirmedPlanId,
          itinerary_plan_ID: dto.itinerary_plan_ID,
          agent_id: dto.agent,
          primary_customer: 1,
          customer_type: 1, // Adult
          customer_salutation: dto.primary_guest_salutation,
          customer_name: dto.primary_guest_name,
          customer_age: parseInt(dto.primary_guest_age) || 0,
          primary_contact_no: dto.primary_guest_contact_no,
          altenative_contact_no: dto.primary_guest_alternative_contact_no || '',
          email_id: dto.primary_guest_email_id || '',
          arrival_date_and_time: arrivalDateTime,
          arrival_place: dto.arrival_place,
          arrival_flight_details: dto.arrival_flight_details || '',
          departure_date_and_time: departureDateTime,
          departure_place: dto.departure_place,
          departure_flight_details: dto.departure_flight_details || '',
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      // D. Insert Additional Adults
      if (dto.adult_name && dto.adult_name.length > 0) {
        for (let i = 0; i < dto.adult_name.length; i++) {
          if (dto.adult_name[i]) {
            await tx.dvi_confirmed_itinerary_customer_details.create({
              data: {
                confirmed_itinerary_plan_ID: confirmedPlanId,
                itinerary_plan_ID: dto.itinerary_plan_ID,
                agent_id: dto.agent,
                primary_customer: 0,
                customer_type: 1, // Adult
                customer_name: dto.adult_name[i],
                customer_age: parseInt(dto.adult_age?.[i] || '0') || 0,
                createdby: userId,
                createdon: new Date(),
                status: 1,
                deleted: 0,
              },
            });
          }
        }
      }

      // E. Insert Children
      if (dto.child_name && dto.child_name.length > 0) {
        for (let i = 0; i < dto.child_name.length; i++) {
          if (dto.child_name[i]) {
            await tx.dvi_confirmed_itinerary_customer_details.create({
              data: {
                confirmed_itinerary_plan_ID: confirmedPlanId,
                itinerary_plan_ID: dto.itinerary_plan_ID,
                agent_id: dto.agent,
                primary_customer: 0,
                customer_type: 2, // Child
                customer_name: dto.child_name[i],
                customer_age: parseInt(dto.child_age?.[i] || '0') || 0,
                createdby: userId,
                createdon: new Date(),
                status: 1,
                deleted: 0,
              },
            });
          }
        }
      }

      // F. Insert Infants
      if (dto.infant_name && dto.infant_name.length > 0) {
        for (let i = 0; i < dto.infant_name.length; i++) {
          if (dto.infant_name[i]) {
            await tx.dvi_confirmed_itinerary_customer_details.create({
              data: {
                confirmed_itinerary_plan_ID: confirmedPlanId,
                itinerary_plan_ID: dto.itinerary_plan_ID,
                agent_id: dto.agent,
                primary_customer: 0,
                customer_type: 3, // Infant
                customer_name: dto.infant_name[i],
                customer_age: parseInt(dto.infant_age?.[i] || '0') || 0,
                createdby: userId,
                createdon: new Date(),
                status: 1,
                deleted: 0,
              },
            });
          }
        }
      }

      // G. Insert into dvi_accounts_itinerary_details
      await tx.dvi_accounts_itinerary_details.create({
        data: {
          itinerary_plan_ID: dto.itinerary_plan_ID,
          agent_id: dto.agent,
          staff_id: plan.staff_id || 0,
          confirmed_itinerary_plan_ID: confirmedPlanId,
          itinerary_quote_ID: plan.itinerary_quote_ID,
          total_billed_amount: cost.netPayable,
          total_received_amount: cost.netPayable,
          total_receivable_amount: 0,
          total_payable_amount: cost.totalAmount,
          total_payout_amount: 0,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      // H. Update draft plan status
      await tx.dvi_itinerary_plan_details.update({
        where: { itinerary_plan_ID: dto.itinerary_plan_ID },
        data: {
          quotation_status: 1,
          updatedon: new Date(),
        },
      });

      return {
        success: true,
        message: 'Quotation confirmed successfully',
        itinerary_plan_ID: dto.itinerary_plan_ID,
        confirmed_itinerary_plan_ID: confirmedPlanId,
        bookingResults: null,
      };
    });
  }

  /**
   * Process hotel bookings after confirmation (called outside transaction)
   */
  async processConfirmationWithTboBookings(
    baseResult: any,
    dto: ConfirmQuotationDto,
    tboHotelBooking?: any,
    resavenueHotelBooking?: any,
    hobseHotelBooking?: any,
    endUserIp: string = '192.168.1.1',
  ) {
    if (!dto.hotel_bookings || dto.hotel_bookings.length === 0) {
      return baseResult;
    }

    const userId = 1;
    const allBookingResults: any[] = [];

    try {
      const tboHotels = dto.hotel_bookings.filter(h => h.provider === 'tbo');
      const resavenueHotels = dto.hotel_bookings.filter(h => h.provider === 'ResAvenue');
      const hobseHotels = dto.hotel_bookings.filter(h => h.provider === 'HOBSE');

      if (tboHotels.length > 0 && tboHotelBooking) {
        const selections = tboHotels.map((hotel) => ({
          routeId: hotel.routeId,
          selection: {
            hotelCode: hotel.hotelCode,
            bookingCode: hotel.bookingCode,
            roomType: hotel.roomType,
            checkInDate: hotel.checkInDate,
            checkOutDate: hotel.checkOutDate,
            numberOfRooms: hotel.numberOfRooms,
            guestNationality: hotel.guestNationality,
            netAmount: hotel.netAmount,
            passengers: hotel.passengers,
          },
        }));

        const tboBookingResults = await tboHotelBooking.confirmItineraryHotels(
          baseResult.confirmed_itinerary_plan_ID,
          baseResult.itinerary_plan_ID,
          selections,
          endUserIp || dto.endUserIp || '192.168.1.1',
          userId,
          Number(dto.hotel_group_type) || 1,
        );
        allBookingResults.push(...tboBookingResults);
      }

      if (resavenueHotels.length > 0 && resavenueHotelBooking) {
        const resavenueSelections = resavenueHotels.map((hotel) => ({
          routeId: hotel.routeId,
          selection: {
            hotelCode: hotel.hotelCode,
            bookingCode: hotel.bookingCode,
            roomType: hotel.roomType,
            checkInDate: hotel.checkInDate,
            checkOutDate: hotel.checkOutDate,
            numberOfRooms: hotel.numberOfRooms,
            guestNationality: hotel.guestNationality,
            netAmount: hotel.netAmount,
            guests: hotel.passengers.map((p) => ({
              firstName: p.firstName,
              lastName: p.lastName,
              email: p.email,
              phone: p.phoneNo,
            })),
          },
          invCode: 1,
          rateCode: 1,
        }));

        const resavenueBookingResults = await resavenueHotelBooking.confirmItineraryHotels(
          baseResult.confirmed_itinerary_plan_ID,
          baseResult.itinerary_plan_ID,
          resavenueSelections,
          userId,
        );
        allBookingResults.push(...resavenueBookingResults);
      }

      if (hobseHotels.length > 0 && hobseHotelBooking) {
        const hobseBookingResults = await hobseHotelBooking.confirmItineraryHotels(
          baseResult.itinerary_plan_ID,
          hobseHotels,
          {
            salutation: (dto as any).title || 'Mr',
            name: (dto as any).contactName || 'Guest',
            email: (dto as any).contactEmail || '',
            phone: (dto as any).contactPhone || '',
          },
        );
        allBookingResults.push(...hobseBookingResults);
      }

      return {
        ...baseResult,
        bookingResults: allBookingResults,
      };
    } catch (error) {
      console.error('Error processing hotel bookings:', error);
      return {
        ...baseResult,
        bookingResults: {
          status: 'error',
          message: error.message,
        },
      };
    }
  }

  /**
   * Cancel an itinerary
   */
  async cancelItinerary(dto: CancelItineraryDto) {
    const userId = 1;

    const confirmedPlan = await this.prisma.dvi_confirmed_itinerary_plan_details.findUnique({
      where: { confirmed_itinerary_plan_ID: dto.confirmed_itinerary_plan_ID },
    });

    if (!confirmedPlan) {
      throw new BadRequestException('Confirmed itinerary plan not found');
    }

    // Calculate refund amount (full amount in this case)
    const refundAmount = Number(confirmedPlan.itinerary_total_paid_amount || 0);

    return await this.prisma.$transaction(async (tx) => {
      // 1. Create refund transaction
      await tx.dvi_cash_wallet.create({
        data: {
          agent_id: confirmedPlan.agent_id,
          transaction_date: new Date(),
          transaction_amount: refundAmount,
          transaction_type: 1, // Credit
          remarks: `Refund for cancelled itinerary: ${confirmedPlan.itinerary_quote_ID}`,
          transaction_id: confirmedPlan.itinerary_quote_ID,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      // 2. Update agent balance
      await tx.dvi_agent.update({
        where: { agent_ID: confirmedPlan.agent_id },
        data: {
          total_cash_wallet: {
            increment: refundAmount,
          },
        },
      });

      // 3. Soft delete confirmed itinerary
      await tx.dvi_confirmed_itinerary_plan_details.update({
        where: { confirmed_itinerary_plan_ID: dto.confirmed_itinerary_plan_ID },
        data: {
          deleted: 1,
          updatedon: new Date(),
        },
      });

      // 4. Delete related records (soft delete)
      await tx.dvi_confirmed_itinerary_customer_details.updateMany({
        where: { confirmed_itinerary_plan_ID: dto.confirmed_itinerary_plan_ID },
        data: { deleted: 1, updatedon: new Date() },
      });

      return {
        success: true,
        message: 'Itinerary cancelled successfully',
        refund_amount: refundAmount,
      };
    });
  }
}
