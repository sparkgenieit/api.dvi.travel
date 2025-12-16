// FILE: src/itineraries/itineraries.controller.ts
// ✅ Fixes Prisma error by:
// 1) Moving @Get(':id') to the VERY END (so it won’t swallow /customer-info, /confirmed, etc.)
// 2) Enforcing numeric :id with ParseIntPipe (so "confirmed" never becomes NaN)
// 3) Importing Request type correctly (your file used Request without import)

import {
  Body,
  Controller,
  Param,
  Post,
  Get,
  Query,
  Req,
  Delete,
  Res,
  ParseIntPipe,
} from '@nestjs/common';
import {
  ApiBody,
  ApiExtraModels,
  ApiOperation,
  ApiQuery,
  ApiTags,
  ApiBearerAuth,
  ApiParam,
  ApiOkResponse,
} from '@nestjs/swagger';
import {
  CreateItineraryDto,
  CreatePlanDto,
  CreateRouteDto,
  CreateTravellerDto,
  CreateVehicleDto,
} from './dto/create-itinerary.dto';
import { LatestItineraryQueryDto } from './dto/latest-itinerary-query.dto';
import { ConfirmQuotationDto } from './dto/confirm-quotation.dto';
import { ItinerariesService } from './itineraries.service';
import { ItineraryDetailsService } from './itinerary-details.service';
import {
  ItineraryHotelDetailsResponseDto,
  ItineraryHotelRoomDetailsResponseDto,
} from './itinerary-hotel-details.service';
import { ItineraryHotelDetailsService } from './itinerary-hotel-details.service';
import { ItineraryExportService } from './itinerary-export.service';
import { Public } from '../../auth/public.decorator';
import { Response, Request } from 'express';

@ApiTags('Itineraries')
@ApiBearerAuth()
@ApiExtraModels(
  CreateItineraryDto,
  CreatePlanDto,
  CreateRouteDto,
  CreateVehicleDto,
  CreateTravellerDto,
)
@Controller('itineraries')
export class ItinerariesController {
  constructor(
    private readonly svc: ItinerariesService,
    private readonly detailsService: ItineraryDetailsService,
    private readonly hotelDetailsService: ItineraryHotelDetailsService,
    private readonly exportService: ItineraryExportService,
  ) {}

  @Post()
  @ApiOperation({
    summary:
      'Create OR Update plan + routes + vehicles + travellers (NO hotspots yet). Use plan.itinerary_plan_id for update.',
  })
  @ApiBody({
    type: CreateItineraryDto,
    examples: {
      create: {
        summary: 'Create (no ids)',
        value: {
          plan: {
            agent_id: 126,
            staff_id: 0,
            location_id: 0,
            arrival_point: 'Chennai International Airport',
            departure_point: 'Pondicherry',
            itinerary_preference: 3,
            itinerary_type: 2,
            preferred_hotel_category: [13],
            hotel_facilities: ['24hr-business-center', '24hr-checkin'],
            trip_start_date: '2025-11-29T12:00:00+05:30',
            trip_end_date: '2025-12-01T12:00:00+05:30',
            pick_up_date_and_time: '2025-11-29T12:00:00+05:30',
            arrival_type: 1,
            departure_type: 1,
            no_of_nights: 2,
            no_of_days: 3,
            budget: 15000,
            entry_ticket_required: 0,
            guide_for_itinerary: 0,
            nationality: 101,
            food_type: 1,
            adult_count: 2,
            child_count: 0,
            infant_count: 0,
            special_instructions: '',
          },
          routes: [
            {
              location_name: 'Chennai International Airport',
              next_visiting_location: 'Chennai',
              itinerary_route_date: '2025-11-29T00:00:00+05:30',
              no_of_days: 1,
              no_of_km: '',
              direct_to_next_visiting_place: 1,
              via_route: '',
              via_routes: [
                {
                  itinerary_via_location_ID: 101,
                  itinerary_via_location_name: 'Mahabalipuram',
                },
              ],
            },
            {
              location_name: 'Chennai',
              next_visiting_location: 'Pondicherry',
              itinerary_route_date: '2025-11-30T00:00:00+05:30',
              no_of_days: 2,
              no_of_km: '',
              direct_to_next_visiting_place: 1,
              via_route: '',
            },
            {
              location_name: 'Pondicherry',
              next_visiting_location: 'Pondicherry',
              itinerary_route_date: '2025-12-01T00:00:00+05:30',
              no_of_days: 3,
              no_of_km: '',
              direct_to_next_visiting_place: 1,
              via_route: '',
            },
          ],
          vehicles: [{ vehicle_type_id: 20, vehicle_count: 1 }],
          travellers: [
            { room_id: 1, traveller_type: 1 },
            { room_id: 1, traveller_type: 1 },
          ],
        },
      },

      update: {
        summary:
          'Update (PHP-like hidden IDs): plan.itinerary_plan_id + routes[].itinerary_route_id + vehicles[].vehicle_details_id',
        value: {
          plan: {
            itinerary_plan_id: 28230, // <-- UPDATE EXISTING PLAN
            agent_id: 126,
            staff_id: 0,
            location_id: 0,
            arrival_point: 'Chennai International Airport',
            departure_point: 'Pondicherry',
            itinerary_preference: 3,
            itinerary_type: 2,
            preferred_hotel_category: [13],
            hotel_facilities: ['24hr-business-center', '24hr-checkin'],
            trip_start_date: '2025-11-29T12:00:00+05:30',
            trip_end_date: '2025-12-01T12:00:00+05:30',
            pick_up_date_and_time: '2025-11-29T12:00:00+05:30',
            arrival_type: 1,
            departure_type: 1,
            no_of_nights: 2,
            no_of_days: 3,
            budget: 15000,
            entry_ticket_required: 0,
            guide_for_itinerary: 0,
            nationality: 101,
            food_type: 1,
            adult_count: 2,
            child_count: 0,
            infant_count: 0,
            special_instructions: '',
          },
          routes: [
            {
              itinerary_route_id: 19, // <-- UPDATE EXISTING ROUTE
              location_name: 'Chennai International Airport',
              next_visiting_location: 'Chennai',
              itinerary_route_date: '2025-11-29T00:00:00+05:30',
              no_of_days: 1,
              no_of_km: '',
              direct_to_next_visiting_place: 1,
              via_route: '',
              via_routes: [
                {
                  itinerary_via_location_ID: 101,
                  itinerary_via_location_name: 'Mahabalipuram',
                },
              ],
            },
            {
              itinerary_route_id: 20, // <-- UPDATE EXISTING ROUTE
              location_name: 'Chennai',
              next_visiting_location: 'Pondicherry',
              itinerary_route_date: '2025-11-30T00:00:00+05:30',
              no_of_days: 2,
              no_of_km: '',
              direct_to_next_visiting_place: 1,
              via_route: '',
            },
            {
              itinerary_route_id: 21, // <-- UPDATE EXISTING ROUTE
              location_name: 'Pondicherry',
              next_visiting_location: 'Pondicherry',
              itinerary_route_date: '2025-12-01T00:00:00+05:30',
              no_of_days: 3,
              no_of_km: '',
              direct_to_next_visiting_place: 1,
              via_route: '',
            },
          ],
          vehicles: [
            {
              vehicle_details_id: 19879, // <-- UPDATE EXISTING VEHICLE ROW
              vehicle_type_id: 1,
              vehicle_count: 1,
            },
          ],
          travellers: [
            { room_id: 1, traveller_type: 1 },
            { room_id: 1, traveller_type: 1 },
          ],
        },
      },
    },
  })
  async createPlan(@Body() dto: CreateItineraryDto) {
    return this.svc.createPlan(dto);
  }

  @Get('details/:quoteId')
  @ApiOperation({
    summary: 'Get full itinerary details by Quote ID',
    description:
      'Returns PHP-like consolidated itinerary details (plan, routes, vehicles, hotspots, hotels, costs, etc.) for a given Quote ID.',
  })
  @ApiParam({
    name: 'quoteId',
    required: true,
    description: 'Quote ID generated for the itinerary',
    example: 'DVI202512032',
    schema: { type: 'string', default: 'DVI202512032' },
  })
  @ApiQuery({
    name: 'groupType',
    required: false,
    description: 'Optional filter for hotel recommendation category (1-4)',
    example: 4,
    type: Number,
  })
  @ApiOkResponse({ description: 'Full itinerary details for the given quoteId' })
  async getItineraryDetails(
    @Param('quoteId') quoteId: string,
    @Query('groupType') groupType?: string,
  ) {
    const groupTypeNum = groupType !== undefined ? Number(groupType) : undefined;
    return this.detailsService.getItineraryDetails(quoteId, groupTypeNum);
  }

  @Get('hotel_details/:quoteId')
  @ApiOperation({
    summary: 'Get hotel details for an itinerary by Quote ID',
    description:
      'Returns hotel tabs + per-day hotel rows (group_type, totals, visibility) mirroring PHP GetHOTEL_ITINEARY_PLAN_DETAILS logic.',
  })
  @ApiParam({
    name: 'quoteId',
    required: true,
    description: 'Quote ID generated for the itinerary',
    example: 'DVI202512032',
  })
  @ApiOkResponse({ description: 'Hotel details for the given quoteId' })
  async getItineraryHotelDetails(
    @Param('quoteId') quoteId: string,
  ): Promise<ItineraryHotelDetailsResponseDto> {
    return this.hotelDetailsService.getHotelDetailsByQuoteId(quoteId);
  }

  @Get('hotel_room_details/:quoteId')
  @ApiOperation({
    summary: 'Get hotel ROOM details for an itinerary by Quote ID',
    description:
      'Returns structured hotel room details (per route / hotel / room / roomType) roughly mirroring the PHP structured_hotel_room_details block.',
  })
  @ApiParam({
    name: 'quoteId',
    required: true,
    description: 'Quote ID generated for the itinerary',
    example: 'DVI202512032',
  })
  @ApiOkResponse({ description: 'Hotel room details for the given quoteId' })
  async getItineraryHotelRoomDetails(
    @Param('quoteId') quoteId: string,
  ): Promise<ItineraryHotelRoomDetailsResponseDto> {
    return this.hotelDetailsService.getHotelRoomDetailsByQuoteId(quoteId);
  }

  @Get('latest')
  @ApiOperation({ summary: 'Latest itineraries datatable' })
  async latest(@Query() q: LatestItineraryQueryDto, @Req() req: Request) {
    return this.detailsService.getLatestItinerariesDataTable(q, req);
  }

  @Delete('hotspot/:planId/:routeId/:hotspotId')
  @ApiOperation({ summary: 'Delete a hotspot from an itinerary route' })
  @ApiParam({ name: 'planId', example: 17940, description: 'Itinerary Plan ID' })
  @ApiParam({ name: 'routeId', example: 1, description: 'Route ID' })
  @ApiParam({ name: 'hotspotId', example: 123, description: 'Route Hotspot ID' })
  @ApiOkResponse({ description: 'Hotspot deleted successfully' })
  async deleteHotspot(
    @Param('planId') planId: string,
    @Param('routeId') routeId: string,
    @Param('hotspotId') hotspotId: string,
  ) {
    return this.svc.deleteHotspot(
      Number(planId),
      Number(routeId),
      Number(hotspotId),
    );
  }

  @Get('activities/available/:hotspotId')
  @ApiOperation({ summary: 'Get available activities for a hotspot location' })
  @ApiParam({
    name: 'hotspotId',
    example: 123,
    description: 'Hotspot Location ID',
  })
  @ApiOkResponse({ description: 'List of available activities' })
  async getAvailableActivities(@Param('hotspotId') hotspotId: string) {
    return this.svc.getAvailableActivities(Number(hotspotId));
  }

  @Post('activities/add')
  @ApiOperation({ summary: 'Add an activity to a hotspot in the itinerary' })
  @ApiBody({
    schema: {
      type: 'object',
      properties: {
        planId: { type: 'number', example: 17940 },
        routeId: { type: 'number', example: 1 },
        routeHotspotId: { type: 'number', example: 123 },
        hotspotId: { type: 'number', example: 456 },
        activityId: { type: 'number', example: 789 },
        amount: { type: 'number', example: 500 },
        startTime: { type: 'string', example: '10:00:00', nullable: true },
        endTime: { type: 'string', example: '11:00:00', nullable: true },
        duration: { type: 'string', example: '01:00:00', nullable: true },
      },
      required: ['planId', 'routeId', 'routeHotspotId', 'hotspotId', 'activityId'],
    },
  })
  @ApiOkResponse({ description: 'Activity added successfully' })
  async addActivity(@Body() body: any) {
    return this.svc.addActivity(body);
  }

  @Delete('activities/:planId/:routeId/:activityId')
  @ApiOperation({ summary: 'Delete an activity from an itinerary route' })
  @ApiParam({ name: 'planId', example: 17940, description: 'Itinerary Plan ID' })
  @ApiParam({ name: 'routeId', example: 1, description: 'Route ID' })
  @ApiParam({
    name: 'activityId',
    example: 123,
    description: 'Route Activity ID',
  })
  @ApiOkResponse({ description: 'Activity deleted successfully' })
  async deleteActivity(
    @Param('planId') planId: string,
    @Param('routeId') routeId: string,
    @Param('activityId') activityId: string,
  ) {
    return this.svc.deleteActivity(
      Number(planId),
      Number(routeId),
      Number(activityId),
    );
  }

  @Get('hotspots/available/:locationId')
  @ApiOperation({ summary: 'Get available hotspots for a location' })
  @ApiParam({ name: 'locationId', example: 123, description: 'Location ID' })
  @ApiOkResponse({ description: 'List of available hotspots' })
  async getAvailableHotspots(@Param('locationId') locationId: string) {
    return this.svc.getAvailableHotspots(Number(locationId));
  }

  @Post('hotspots/add')
  @ApiOperation({ summary: 'Add a hotspot to an itinerary route' })
  @ApiBody({
    schema: {
      type: 'object',
      properties: {
        planId: { type: 'number', example: 17940 },
        routeId: { type: 'number', example: 1 },
        hotspotId: { type: 'number', example: 456 },
      },
      required: ['planId', 'routeId', 'hotspotId'],
    },
  })
  @ApiOkResponse({ description: 'Hotspot added successfully' })
  async addHotspot(@Body() body: any) {
    return this.svc.addHotspot(body);
  }

  @Get('hotels/available/:routeId')
  @ApiOperation({ summary: 'Get available hotels for a route' })
  @ApiParam({ name: 'routeId', example: 1, description: 'Route ID' })
  @ApiOkResponse({ description: 'List of available hotels' })
  async getAvailableHotels(@Param('routeId') routeId: string) {
    return this.svc.getAvailableHotels(Number(routeId));
  }

  @Post('hotels/select')
  @ApiOperation({ summary: 'Select/update hotel for a route' })
  @ApiBody({
    schema: {
      type: 'object',
      properties: {
        planId: { type: 'number', example: 17940 },
        routeId: { type: 'number', example: 1 },
        hotelId: { type: 'number', example: 123 },
        roomTypeId: { type: 'number', example: 456 },
        mealPlan: {
          type: 'object',
          properties: {
            all: { type: 'boolean' },
            breakfast: { type: 'boolean' },
            lunch: { type: 'boolean' },
            dinner: { type: 'boolean' },
          },
        },
      },
      required: ['planId', 'routeId', 'hotelId', 'roomTypeId'],
    },
  })
  @ApiOkResponse({ description: 'Hotel selected successfully' })
  async selectHotel(@Body() body: any) {
    return this.svc.selectHotel(body);
  }

  @Post('vehicles/select-vendor')
  @ApiOperation({ summary: 'Select/update vehicle vendor for a vehicle type' })
  @ApiBody({
    schema: {
      type: 'object',
      properties: {
        planId: { type: 'number', example: 17940 },
        vehicleTypeId: { type: 'number', example: 1 },
        vendorEligibleId: { type: 'number', example: 123 },
      },
      required: ['planId', 'vehicleTypeId', 'vendorEligibleId'],
    },
  })
  @ApiOkResponse({ description: 'Vehicle vendor selected successfully' })
  async selectVehicleVendor(@Body() body: any) {
    return this.svc.selectVehicleVendor(body);
  }

  @Get('edit/:id')
  @ApiOperation({ summary: 'Get itinerary raw plan data for editing' })
  @ApiParam({ name: 'id', example: 17940, description: 'Itinerary Plan ID' })
  @ApiOkResponse({
    description: 'Returns plan, routes, and vehicles for editing in the form',
  })
  async getPlanForEdit(@Param('id', ParseIntPipe) id: number) {
    return this.svc.getPlanForEdit(id);
  }

  @Get('export/:id')
  @Public()
  @ApiOperation({ summary: 'Export itinerary to Excel' })
  @ApiParam({ name: 'id', example: 14, description: 'Itinerary Plan ID' })
  async exportToExcel(
    @Param('id', ParseIntPipe) id: number,
    @Res() res: Response,
  ) {
    const workbook = await this.exportService.exportItineraryToExcel(id);

    res.setHeader(
      'Content-Type',
      'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    );
    res.setHeader(
      'Content-Disposition',
      `attachment; filename="ITINERARY-DVI${id}.xlsx"`,
    );

    await workbook.xlsx.write(res);
    res.end();
  }

  @Get('customer-info/:planId')
  @ApiOperation({ summary: 'Get customer info form data for confirm quotation' })
  @ApiParam({ name: 'planId', example: 12, description: 'Itinerary Plan ID' })
  @ApiOkResponse({
    description: 'Returns quotation number, agent name, and wallet balance',
  })
  async getCustomerInfoForm(@Param('planId', ParseIntPipe) planId: number) {
    return this.svc.getCustomerInfoForm(planId);
  }

  @Get('wallet-balance/:agentId')
  @ApiOperation({ summary: 'Check agent wallet balance' })
  @ApiParam({ name: 'agentId', example: 3, description: 'Agent ID' })
  @ApiOkResponse({ description: 'Returns agent wallet balance and sufficiency status' })
  async checkWalletBalance(@Param('agentId', ParseIntPipe) agentId: number) {
    return this.svc.checkWalletBalance(agentId);
  }

  @Post('confirm-quotation')
  @ApiOperation({ summary: 'Confirm quotation with guest details' })
  @ApiBody({ type: ConfirmQuotationDto })
  @ApiOkResponse({ description: 'Quotation confirmed successfully' })
  async confirmQuotation(@Body() dto: ConfirmQuotationDto) {
    return this.svc.confirmQuotation(dto);
  }

  @Get('confirmed')
  @ApiOperation({
    summary: 'Get confirmed itineraries list with pagination and filters',
  })
  @ApiQuery({ name: 'draw', required: false, type: Number })
  @ApiQuery({ name: 'start', required: false, type: Number })
  @ApiQuery({ name: 'length', required: false, type: Number })
  @ApiQuery({
    name: 'start_date',
    required: false,
    type: String,
    description: 'Format: DD/MM/YYYY',
  })
  @ApiQuery({
    name: 'end_date',
    required: false,
    type: String,
    description: 'Format: DD/MM/YYYY',
  })
  @ApiQuery({ name: 'source_location', required: false, type: String })
  @ApiQuery({ name: 'destination_location', required: false, type: String })
  @ApiQuery({ name: 'agent_id', required: false, type: Number })
  @ApiQuery({ name: 'staff_id', required: false, type: Number })
  async getConfirmedItineraries(@Query() query: LatestItineraryQueryDto) {
    return this.svc.getConfirmedItineraries(query);
  }

  /**
   * ✅ MUST BE LAST.
   * Otherwise it will swallow routes like /customer-info/:planId, /confirmed, /latest, etc.
   */
  @Get(':id')
  @ApiOperation({ summary: 'Get itinerary by plan id' })
  @ApiParam({ name: 'id', example: 17940 })
  @ApiQuery({
    name: 'groupType',
    required: false,
    description: 'Hotel recommendation group type (1-4)',
  })
  async findOne(
    @Param('id', ParseIntPipe) id: number,
    @Query('groupType') groupType?: string,
  ) {
    const groupTypeNum = groupType ? Number(groupType) : undefined;
    return this.detailsService.findOne(id, groupTypeNum);
  }
}
