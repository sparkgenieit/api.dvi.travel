// FILE: src/itineraries/itineraries.controller.ts

import { Body, Controller, Param, Post, Get, Query, Req } from '@nestjs/common';
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
import { ItinerariesService } from './itineraries.service';
import { ItineraryDetailsService } from './itinerary-details.service';
import {
  ItineraryHotelDetailsResponseDto,
  ItineraryHotelRoomDetailsResponseDto,
} from './itinerary-hotel-details.service';
import { ItineraryHotelDetailsService } from './itinerary-hotel-details.service';

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
    schema: {
      type: 'string',
      default: 'DVI202512032',
    },
  })
  @ApiOkResponse({
    description: 'Full itinerary details for the given quoteId',
  })
  async getItineraryDetails(@Param('quoteId') quoteId: string) {
    return this.detailsService.getItineraryDetails(quoteId);
  }

  // ⭐ EXISTING ENDPOINT: hotel_details/:quoteId
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
  @ApiOkResponse({
    description: 'Hotel details for the given quoteId',
  })
  async getItineraryHotelDetails(
    @Param('quoteId') quoteId: string,
  ): Promise<ItineraryHotelDetailsResponseDto> {
    return this.hotelDetailsService.getHotelDetailsByQuoteId(quoteId);
  }

  // ⭐ NEW ENDPOINT: hotel_room_details/:quoteId
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
  @ApiOkResponse({
    description: 'Hotel room details for the given quoteId',
  })
  async getItineraryHotelRoomDetails(
    @Param('quoteId') quoteId: string,
  ): Promise<ItineraryHotelRoomDetailsResponseDto> {
    return this.hotelDetailsService.getHotelRoomDetailsByQuoteId(quoteId);
  }

  // ✅ SP-FREE datatable endpoint (replaces CALL GetLatestItineraryPlans)
  @Get('latest')
  @ApiOperation({ summary: 'Latest itineraries datatable' })
  async latest(@Query() q: LatestItineraryQueryDto, @Req() req: Request) {
    return this.detailsService.getLatestItinerariesDataTable(q, req);
  }

  @Get(':id')
  @ApiOperation({ summary: 'Get itinerary by plan id' })
  @ApiParam({ name: 'id', example: 17940 })
  async findOne(@Param('id') id: string) {
    return this.detailsService.findOne(Number(id));
  }
}
