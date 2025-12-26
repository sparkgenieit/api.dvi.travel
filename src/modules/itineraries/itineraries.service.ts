// REPLACE-WHOLE-FILE
// FILE: src/itineraries/itineraries.service.ts

import { Injectable, BadRequestException } from "@nestjs/common";
import { PrismaService } from "../../prisma.service";
import {
  CreateItineraryDto,
} from "./dto/create-itinerary.dto";
import { ConfirmQuotationDto } from "./dto/confirm-quotation.dto";
import { LatestItineraryQueryDto } from "./dto/latest-itinerary-query.dto";
import { PlanEngineService } from "./engines/plan-engine.service";
import { RouteEngineService } from "./engines/route-engine.service";
import { HotspotEngineService } from "./engines/hotspot-engine.service";
import { HotelEngineService } from "./engines/hotel-engine.service";
import { TravellersEngineService } from "./engines/travellers-engine.service";
import { VehiclesEngineService } from "./engines/vehicles-engine.service";
import { ViaRoutesEngine } from "./engines/via-routes.engine";
import { ItineraryVehiclesEngine } from "./engines/itinerary-vehicles.engine";
import { RouteValidationService } from "./validation/route-validation.service";

@Injectable()
export class ItinerariesService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly planEngine: PlanEngineService,
    private readonly routeEngine: RouteEngineService,
    private readonly hotspotEngine: HotspotEngineService,
    private readonly hotelEngine: HotelEngineService,
    private readonly travellersEngine: TravellersEngineService,
    private readonly vehiclesEngine: VehiclesEngineService,
    private readonly viaRoutesEngine: ViaRoutesEngine,
    private readonly itineraryVehiclesEngine: ItineraryVehiclesEngine,
    private readonly routeValidation: RouteValidationService,
  ) {}

  async createPlan(dto: CreateItineraryDto) {
    const userId = 1;
    const perfStart = Date.now();

    // Validate hotel availability BEFORE starting the transaction
    // Only validate if hotels are needed (itinerary_preference 1 or 3)
    if (dto.plan.itinerary_preference === 1 || dto.plan.itinerary_preference === 3) {
      const categoryStr = String(dto.plan.preferred_hotel_category || '');
      const categories = categoryStr
        .split(',')
        .map((c) => Number(c.trim()))
        .filter((c) => c > 0);
      const preferredCategory = categories[0] || 2;

      try {
        const validations = await this.routeValidation.validateHotelAvailability(
          dto.routes,
          preferredCategory
        );
        
        // Log successful validation
        console.log('[ItinerariesService] Hotel validation passed:', validations.length, 'routes checked');
      } catch (error) {
        // Re-throw BadRequestException with hotel availability details
        if (error instanceof BadRequestException) {
          throw error;
        }
        // Handle unexpected validation errors
        throw new BadRequestException({
          message: 'Failed to validate hotel availability',
          error: error instanceof Error ? error.message : String(error),
        });
      }
    }

    const txStart = Date.now();
    
    // Increase interactive transaction timeout; hotspot rebuild + hotel lookups can exceed default 5s
    const result = await this.prisma.$transaction(async (tx) => {
      const opStart = Date.now();
      const planId = await this.planEngine.upsertPlanHeader(
        dto.plan,
        dto.travellers,
        tx,
        userId,
      );
      console.log('[PERF] upsertPlanHeader:', Date.now() - opStart, 'ms');

      let opStart2 = Date.now();
      const routes = await this.routeEngine.rebuildRoutes(
        planId,
        dto.plan,
        dto.routes,
        tx,
        userId,
      );
      console.log('[PERF] rebuildRoutes:', Date.now() - opStart2, 'ms');

      // Rebuild permit charges after routes are created
      opStart2 = Date.now();
      await this.routeEngine.rebuildPermitCharges(tx, planId, userId);
      console.log('[PERF] rebuildPermitCharges:', Date.now() - opStart2, 'ms');

      // Rebuild via routes AFTER routes are created and BEFORE hotspots
      opStart2 = Date.now();
      const routeIds = routes.map((r: any) => r.itinerary_route_ID);
      await this.viaRoutesEngine.rebuildViaRoutes(tx, planId, dto.routes, routeIds, userId);
      console.log('[PERF] rebuildViaRoutes:', Date.now() - opStart2, 'ms');

      opStart2 = Date.now();
      await this.planEngine.updateNoOfRoutes(planId, tx);
      console.log('[PERF] updateNoOfRoutes:', Date.now() - opStart2, 'ms');

      opStart2 = Date.now();
      await this.travellersEngine.rebuildTravellers(
        planId,
        dto.travellers,
        tx,
        userId,
      );
      console.log('[PERF] rebuildTravellers:', Date.now() - opStart2, 'ms');

      opStart2 = Date.now();
      await this.vehiclesEngine.rebuildPlanVehicles(
        planId,
        dto.vehicles,
        tx,
        userId,
      );
      console.log('[PERF] rebuildPlanVehicles:', Date.now() - opStart2, 'ms');

      if (
        dto.plan.itinerary_preference === 1 ||
        dto.plan.itinerary_preference === 3
      ) {
        opStart2 = Date.now();
        await this.hotelEngine.rebuildPlanHotels(
          planId,
          tx,
          userId,
          
        );
        console.log('[PERF] rebuildPlanHotels:', Date.now() - opStart2, 'ms');
      }

      opStart2 = Date.now();
      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);
      console.log('[PERF] rebuildRouteHotspots:', Date.now() - opStart2, 'ms');

      opStart2 = Date.now();
      const planRow = await (tx as any).dvi_itinerary_plan_details.findUnique({
        where: { itinerary_plan_ID: planId },
        select: { itinerary_quote_ID: true },
      });
      console.log('[PERF] getPlanRow:', Date.now() - opStart2, 'ms');
      console.log('[PERF] TOTAL TRANSACTION:', Date.now() - txStart, 'ms');

      return {
        planId,
        quoteId: planRow?.itinerary_quote_ID,
        routeIds: routes.map((r: any) => r.itinerary_route_ID),
        message:
          "Plan created/updated with routes, vehicles, travellers, hotspots, and hotels.",
      };
    }, { timeout: 120000, maxWait: 20000 }); // Increased to 120s while we optimize further

    // Rebuild parking charges AFTER routes and hotspots
    let postStart = Date.now();
    await this.hotspotEngine.rebuildParkingCharges(result.planId, userId);
    console.log('[PERF] rebuildParkingCharges:', Date.now() - postStart, 'ms');

    // Rebuild vendor eligible list and vendor vehicle details AFTER transaction completes
    // (requires committed routes & hotspots data)
    postStart = Date.now();
    await this.itineraryVehiclesEngine.rebuildEligibleVendorList({
      planId: result.planId,
      createdBy: userId,
    });
    console.log('[PERF] rebuildEligibleVendorList:', Date.now() - postStart, 'ms');
    console.log('[PERF] TOTAL createPlan:', Date.now() - perfStart, 'ms');

    return result;
  }

  /**
   * Delete a hotspot from an itinerary route
   * Removes records from dvi_itinerary_route_hotspot_details and related tables
   */
  async deleteHotspot(planId: number, routeId: number, hotspotId: number) {
    const userId = 1;

    await this.prisma.$transaction(async (tx) => {
      // Delete activities associated with this hotspot
      await (tx as any).dvi_itinerary_route_activity_details.deleteMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          route_hotspot_ID: hotspotId,
        },
      });

      // Delete the hotspot record
      const deleted = await (tx as any).dvi_itinerary_route_hotspot_details.deleteMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          route_hotspot_ID: hotspotId,
        },
      });

      if (deleted.count === 0) {
        throw new BadRequestException('Hotspot not found');
      }

      // Update route details timestamp
      await (tx as any).dvi_itinerary_route_details.updateMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
        },
        data: {
          updatedon: new Date(),
        },
      });
    });

    // Rebuild parking charges after deletion
    await this.hotspotEngine.rebuildParkingCharges(planId, userId);

    return {
      success: true,
      message: 'Hotspot deleted successfully',
    };
  }

  /**
   * Get available activities for a hotspot location
   */
  async getAvailableActivities(hotspotId: number) {
    const activities = await (this.prisma as any).dvi_activity.findMany({
      where: {
        hotspot_id: hotspotId,
        deleted: 0,
        status: 1,
      },
      select: {
        activity_id: true,
        activity_title: true,
        activity_description: true,
        activity_duration: true,
        max_allowed_person_count: true,
      },
      orderBy: { activity_title: 'asc' },
    });

    return activities.map((a: any) => ({
      id: a.activity_id,
      title: a.activity_title || '',
      description: a.activity_description || '',
      duration: a.activity_duration || null,
      maxPersons: a.max_allowed_person_count || 0,
    }));
  }

  /**
   * Add an activity to a hotspot in the itinerary
   */
  async addActivity(data: {
    planId: number;
    routeId: number;
    routeHotspotId: number;
    hotspotId: number;
    activityId: number;
    amount?: number;
    startTime?: string;
    endTime?: string;
    duration?: string;
  }) {
    const userId = 1;

    // Get the next activity order
    const existingActivities = await (this.prisma as any).dvi_itinerary_route_activity_details.findMany({
      where: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        route_hotspot_ID: data.routeHotspotId,
        deleted: 0,
      },
      select: { activity_order: true },
      orderBy: { activity_order: 'desc' },
      take: 1,
    });

    const nextOrder = existingActivities.length > 0 ? existingActivities[0].activity_order + 1 : 1;

    // Insert the activity
    const result = await (this.prisma as any).dvi_itinerary_route_activity_details.create({
      data: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        route_hotspot_ID: data.routeHotspotId,
        hotspot_ID: data.hotspotId,
        activity_ID: data.activityId,
        activity_order: nextOrder,
        activity_amout: data.amount || 0,
        activity_traveling_time: data.duration || null,
        activity_start_time: data.startTime || null,
        activity_end_time: data.endTime || null,
        createdby: userId,
        createdon: new Date(),
        status: 1,
        deleted: 0,
      },
    });

    return {
      success: true,
      message: 'Activity added successfully',
      activityId: result.route_activity_ID,
    };
  }

  /**
   * Delete an activity from an itinerary route
   */
  async deleteActivity(planId: number, routeId: number, activityId: number) {
    const userId = 1;

    const deleted = await (this.prisma as any).dvi_itinerary_route_activity_details.deleteMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
        route_activity_ID: activityId,
      },
    });

    if (deleted.count === 0) {
      throw new BadRequestException('Activity not found');
    }

    // Update route details timestamp
    await (this.prisma as any).dvi_itinerary_route_details.updateMany({
      where: {
        itinerary_plan_ID: planId,
        itinerary_route_ID: routeId,
      },
      data: {
        modifiedDate: new Date(),
        modifiedBy: userId,
      },
    });

    return {
      success: true,
      message: 'Activity deleted successfully',
    };
  }

  /**
   * Get available hotspots for a location
   */
  async getAvailableHotspots(locationId: number) {
    // Get the location details from dvi_stored_locations
    const location = await (this.prisma as any).dvi_stored_locations.findFirst({
      where: {
        location_ID: locationId,
        deleted: 0,
      },
      select: {
        destination_location: true,
      },
    });

    if (!location || !location.destination_location) {
      return [];
    }

    const locationName = location.destination_location;

    const hotspots = await (this.prisma as any).dvi_hotspot_place.findMany({
      where: {
        status: 1,
        deleted: 0,
        hotspot_location: {
          contains: locationName,
        },
      },
      select: {
        hotspot_ID: true,
        hotspot_name: true,
        hotspot_adult_entry_cost: true,
        hotspot_description: true,
        hotspot_duration: true,
        hotspot_location: true,
      },
      orderBy: {
        hotspot_name: 'asc',
      },
    });

    return hotspots.map((h: any) => ({
      id: h.hotspot_ID,
      name: h.hotspot_name,
      amount: h.hotspot_adult_entry_cost || 0,
      description: h.hotspot_description || '',
      timeSpend: h.hotspot_duration ? new Date(h.hotspot_duration).getUTCHours() : 0,
      locationMap: h.hotspot_location || null,
    }));
  }

  /**
   * Add a hotspot to an itinerary route
   */
  async addHotspot(data: { planId: number; routeId: number; hotspotId: number }) {
    const userId = 1;

    // Get the hotspot details
    const hotspot = await (this.prisma as any).dvi_hotspot_place.findUnique({
      where: { hotspot_ID: data.hotspotId },
    });

    if (!hotspot) {
      throw new BadRequestException('Hotspot not found');
    }

    // Get the max hotspot order for this route
    const existingHotspots = await (this.prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        deleted: 0,
      },
      select: { hotspot_order: true },
      orderBy: { hotspot_order: 'desc' },
      take: 1,
    });

    const nextOrder = existingHotspots.length > 0 ? existingHotspots[0].hotspot_order + 1 : 1;

    // Insert the hotspot
    const result = await (this.prisma as any).dvi_itinerary_route_hotspot_details.create({
      data: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        hotspot_ID: data.hotspotId,
        item_type: 4, // Hotspot/Attraction type
        hotspot_order: nextOrder,
        hotspot_adult_entry_cost: hotspot.hotspot_adult_entry_cost || 0,
        hotspot_child_entry_cost: hotspot.hotspot_child_entry_cost || 0,
        hotspot_infant_entry_cost: hotspot.hotspot_infant_entry_cost || 0,
        hotspot_foreign_adult_entry_cost: hotspot.hotspot_foreign_adult_entry_cost || 0,
        hotspot_foreign_child_entry_cost: hotspot.hotspot_foreign_child_entry_cost || 0,
        hotspot_foreign_infant_entry_cost: hotspot.hotspot_foreign_infant_entry_cost || 0,
        hotspot_amout: hotspot.hotspot_adult_entry_cost || 0,
        hotspot_traveling_time: hotspot.hotspot_duration || null,
        hotspot_start_time: new Date('1970-01-01T00:00:00.000Z'),
        hotspot_end_time: new Date('1970-01-01T00:00:00.000Z'),
        createdby: userId,
        createdon: new Date(),
        status: 1,
        deleted: 0,
      },
    });

    // Update route details timestamp
    await (this.prisma as any).dvi_itinerary_route_details.updateMany({
      where: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
      },
      data: {
        updatedon: new Date(),
      },
    });

    return {
      success: true,
      message: 'Hotspot added successfully',
      hotspotId: result.route_hotspot_ID,
    };
  }

  /**
   * Get available hotels for a route (within 20km radius)
   */
  async getAvailableHotels(routeId: number) {
    // Get route details
    const route = await (this.prisma as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_route_ID: routeId },
    });

    if (!route || !route.location_id) {
      return [];
    }

    // Get location coordinates separately
    const location = await (this.prisma as any).dvi_stored_locations.findFirst({
      where: { location_ID: Number(route.location_id) },
      select: {
        destination_location_lattitude: true,
        destination_location_longitude: true,
      },
    });

    if (!location || !location.destination_location_lattitude || !location.destination_location_longitude) {
      return [];
    }

    const destLat = Number(location.destination_location_lattitude);
    const destLng = Number(location.destination_location_longitude);

    // Fetch hotels with Haversine distance calculation
    const hotels = await this.prisma.$queryRaw`
      SELECT 
        h.hotel_ID,
        h.hotel_name,
        h.hotel_address,
        h.hotel_latitude,
        h.hotel_longitude,
        h.hotel_category,
        h.hotel_check_in,
        h.hotel_check_out,
        (6371 * acos(
          cos(radians(${destLat})) * 
          cos(radians(h.hotel_latitude)) * 
          cos(radians(h.hotel_longitude) - radians(${destLng})) + 
          sin(radians(${destLat})) * 
          sin(radians(h.hotel_latitude))
        )) AS distance_in_km
      FROM dvi_hotel h
      WHERE h.status = 1 
        AND h.deleted = 0
        AND h.hotel_latitude IS NOT NULL
        AND h.hotel_longitude IS NOT NULL
      HAVING distance_in_km <= 20
      ORDER BY distance_in_km ASC
      LIMIT 20
    `;

    return (hotels as any[]).map(h => ({
      id: h.hotel_ID,
      name: h.hotel_name,
      address: h.hotel_address,
      category: h.hotel_category,
      checkIn: h.hotel_check_in,
      checkOut: h.hotel_check_out,
      distance: Number(h.distance_in_km).toFixed(2),
    }));
  }

  /**
   * Select/update hotel for a route
   */
  async selectHotel(data: { 
    planId: number; 
    routeId: number; 
    hotelId: number; 
    roomTypeId: number;
    mealPlan?: { all?: boolean; breakfast?: boolean; lunch?: boolean; dinner?: boolean; };
  }) {
    const userId = 1;

    // Check if hotel assignment already exists in hotel_details
    const existingHotelDetails = await (this.prisma as any).dvi_itinerary_plan_hotel_details.findFirst({
      where: {
        itinerary_plan_id: data.planId,
        itinerary_route_id: data.routeId,
        deleted: 0,
      },
    });

    const mealBreakfast = data.mealPlan?.breakfast || data.mealPlan?.all ? 1 : 0;
    const mealLunch = data.mealPlan?.lunch || data.mealPlan?.all ? 1 : 0;
    const mealDinner = data.mealPlan?.dinner || data.mealPlan?.all ? 1 : 0;

    let hotelDetailsId: number;

    if (existingHotelDetails) {
      // Update existing hotel assignment
      await (this.prisma as any).dvi_itinerary_plan_hotel_details.update({
        where: { itinerary_plan_hotel_details_ID: existingHotelDetails.itinerary_plan_hotel_details_ID },
        data: {
          hotel_id: data.hotelId,
          updatedon: new Date(),
        },
      });
      hotelDetailsId = existingHotelDetails.itinerary_plan_hotel_details_ID;
    } else {
      // Create new hotel assignment
      const created = await (this.prisma as any).dvi_itinerary_plan_hotel_details.create({
        data: {
          itinerary_plan_id: data.planId,
          itinerary_route_id: data.routeId,
          hotel_id: data.hotelId,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
      hotelDetailsId = created.itinerary_plan_hotel_details_ID;
    }

    // Check if room details already exist
    const existingRoomDetails = await (this.prisma as any).dvi_itinerary_plan_hotel_room_details.findFirst({
      where: {
        itinerary_plan_id: data.planId,
        itinerary_route_id: data.routeId,
        hotel_id: data.hotelId,
        deleted: 0,
      },
    });

    if (existingRoomDetails) {
      // Update existing room details
      await (this.prisma as any).dvi_itinerary_plan_hotel_room_details.update({
        where: { itinerary_plan_hotel_room_details_ID: existingRoomDetails.itinerary_plan_hotel_room_details_ID },
        data: {
          room_type_id: data.roomTypeId,
          breakfast_required: mealBreakfast,
          lunch_required: mealLunch,
          dinner_required: mealDinner,
          updatedon: new Date(),
        },
      });
    } else {
      // Create new room details
      await (this.prisma as any).dvi_itinerary_plan_hotel_room_details.create({
        data: {
          itinerary_plan_hotel_details_id: hotelDetailsId,
          itinerary_plan_id: data.planId,
          itinerary_route_id: data.routeId,
          hotel_id: data.hotelId,
          room_type_id: data.roomTypeId,
          breakfast_required: mealBreakfast,
          lunch_required: mealLunch,
          dinner_required: mealDinner,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    return {
      success: true,
      message: 'Hotel selected successfully',
    };
  }

  async selectVehicleVendor(data: {
    planId: number;
    vehicleTypeId: number;
    vendorEligibleId: number;
  }) {
    // First, reset all vendors for this vehicle type to unassigned (0)
    await (this.prisma as any).dvi_itinerary_plan_vendor_eligible_list.updateMany({
      where: {
        itinerary_plan_id: data.planId,
        vehicle_type_id: data.vehicleTypeId,
      },
      data: {
        itineary_plan_assigned_status: 0,
      },
    });

    // Then, set the selected vendor to assigned (1)
    await (this.prisma as any).dvi_itinerary_plan_vendor_eligible_list.update({
      where: {
        itinerary_plan_vendor_eligible_ID: data.vendorEligibleId,
      },
      data: {
        itineary_plan_assigned_status: 1,
      },
    });

    return {
      success: true,
      message: 'Vehicle vendor selected successfully',
    };
  }

  async getPlanForEdit(planId: number) {
    // Fetch the plan
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
    });

    if (!plan) {
      throw new BadRequestException(`Plan ${planId} not found`);
    }

    // Fetch routes
    const routes = await this.prisma.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: planId, deleted: 0 },
      orderBy: { no_of_days: 'asc' },
    });

    // Fetch via routes for each route
    const routesWithVia = await Promise.all(
      routes.map(async (route) => {
        const viaRoutes = await this.prisma.dvi_itinerary_via_route_details.findMany({
          where: {
            itinerary_plan_ID: planId,
            itinerary_route_ID: route.itinerary_route_ID,
            deleted: 0,
          },
          orderBy: { itinerary_via_route_ID: 'asc' },
        });

        return {
          ...route,
          via_routes: viaRoutes.map(v => ({
            itinerary_via_location_ID: v.itinerary_via_location_ID,
            itinerary_via_location_name: v.itinerary_via_location_name,
          })),
        };
      })
    );

    // Fetch vehicles - note: this table uses lowercase itinerary_plan_id
    const vehicles = await this.prisma.dvi_itinerary_plan_vehicle_details.findMany({
      where: { itinerary_plan_id: planId, deleted: 0 },
      orderBy: { vehicle_details_ID: 'asc' },
    });

    return {
      plan,
      routes: routesWithVia,
      vehicles,
    };
  }

  async getCustomerInfoForm(planId: number) {
    // Get plan details
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: planId },
      select: {
        itinerary_quote_ID: true,
        agent_id: true,
      },
    });

    if (!plan) {
      throw new BadRequestException('Itinerary plan not found');
    }

    // Get agent details
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: plan.agent_id },
      select: {
        agent_name: true,
        total_cash_wallet: true,
      },
    });

    if (!agent) {
      throw new BadRequestException('Agent not found');
    }

    const walletBalance = Number(agent.total_cash_wallet || 0);
    const formattedBalance = walletBalance.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    });

    return {
      quotation_no: plan.itinerary_quote_ID || '',
      agent_name: agent.agent_name,
      wallet_balance: formattedBalance,
      balance_sufficient: walletBalance > 0,
    };
  }

  async checkWalletBalance(agentId: number) {
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: agentId },
      select: {
        total_cash_wallet: true,
      },
    });

    if (!agent) {
      throw new BadRequestException('Agent not found');
    }

    const balance = Number(agent.total_cash_wallet || 0);
    const formattedBalance = `₹ ${balance.toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })}`;

    return {
      balance,
      formatted_balance: formattedBalance,
      is_sufficient: balance > 0,
    };
  }

  async confirmQuotation(dto: ConfirmQuotationDto) {
    const userId = 1; // TODO: Get from authenticated user

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

    // Get plan details to check if already confirmed
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: dto.itinerary_plan_ID },
    });

    if (!plan) {
      throw new BadRequestException('Itinerary plan not found');
    }

    // Insert or update primary guest details in dvi_confirmed_itinerary_customer_details
    const existingCustomer = await this.prisma.dvi_confirmed_itinerary_customer_details.findFirst({
      where: {
        itinerary_plan_ID: dto.itinerary_plan_ID,
        primary_customer: 1,
        deleted: 0,
      },
    });

    if (existingCustomer) {
      // Update existing customer
      await this.prisma.dvi_confirmed_itinerary_customer_details.update({
        where: { confirmed_itinerary_customer_ID: existingCustomer.confirmed_itinerary_customer_ID },
        data: {
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
          updatedon: new Date(),
        },
      });
    } else {
      // Create new customer record
      await this.prisma.dvi_confirmed_itinerary_customer_details.create({
        data: {
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
    }

    // Handle additional adults if provided
    if (dto.adult_name && dto.adult_name.length > 0) {
      for (let i = 0; i < dto.adult_name.length; i++) {
        if (dto.adult_name[i]) {
          await this.prisma.dvi_confirmed_itinerary_customer_details.create({
            data: {
              itinerary_plan_ID: dto.itinerary_plan_ID,
              agent_id: dto.agent,
              primary_customer: 0,
              customer_type: 1, // Adult
              customer_salutation: 'Mr',
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

    // Update plan status to confirmed
    await this.prisma.dvi_itinerary_plan_details.update({
      where: { itinerary_plan_ID: dto.itinerary_plan_ID },
      data: {
        quotation_status: 1, // Confirmed
        updatedon: new Date(),
      },
    });

    return {
      success: true,
      message: 'Quotation confirmed successfully',
      itinerary_plan_ID: dto.itinerary_plan_ID,
    };
  }

  async getConfirmedItineraries(query: LatestItineraryQueryDto) {
    const {
      draw = 1,
      start = 0,
      length = 10,
      start_date,
      end_date,
      source_location,
      destination_location,
      agent_id,
      staff_id,
    } = query;

    // Parse date filters if provided
    const parseDateFilter = (dateStr: string | undefined) => {
      if (!dateStr) return undefined;
      const [day, month, year] = dateStr.split('/');
      return new Date(Number(year), Number(month) - 1, Number(day));
    };

    const startDate = parseDateFilter(start_date);
    const endDate = parseDateFilter(end_date);

    // Build where clause
    const where: any = {
      quotation_status: 1, // Only confirmed quotations
      deleted: 0,
    };

    if (agent_id) {
      where.agent_id = agent_id;
    }

    if (staff_id) {
      where.staff_id = staff_id;
    }

    if (startDate) {
      where.trip_start_date_and_time = {
        gte: startDate,
      };
    }

    if (endDate) {
      where.trip_end_date_and_time = {
        lte: new Date(endDate.getTime() + 86400000 - 1), // End of day
      };
    }

    if (source_location) {
      where.arrival_location = {
        contains: source_location,
      };
    }

    if (destination_location) {
      where.departure_location = {
        contains: destination_location,
      };
    }

    // Get total count
    const recordsTotal = await this.prisma.dvi_itinerary_plan_details.count();
    const recordsFiltered = await this.prisma.dvi_itinerary_plan_details.count({
      where,
    });

    // Fetch paginated data
    const plans = await this.prisma.dvi_itinerary_plan_details.findMany({
      where,
      skip: start,
      take: length,
      orderBy: {
        createdon: 'desc',
      },
      select: {
        itinerary_plan_ID: true,
        itinerary_quote_ID: true,
        agent_id: true,
        staff_id: true,
        arrival_location: true,
        departure_location: true,
        trip_start_date_and_time: true,
        trip_end_date_and_time: true,
        no_of_days: true,
        no_of_nights: true,
        createdon: true,
        createdby: true,
      },
    });

    // Get primary customer details for each plan
    const data = await Promise.all(
      plans.map(async (plan) => {
        const customer = await this.prisma.dvi_confirmed_itinerary_customer_details.findFirst({
          where: {
            itinerary_plan_ID: plan.itinerary_plan_ID,
            primary_customer: 1,
            deleted: 0,
          },
          select: {
            customer_name: true,
            primary_contact_no: true,
            arrival_date_and_time: true,
            departure_date_and_time: true,
          },
        });

        // Get agent name
        const agent = plan.agent_id
          ? await this.prisma.dvi_agent.findUnique({
              where: { agent_ID: plan.agent_id },
              select: { agent_name: true },
            })
          : null;

        return {
          itinerary_plan_ID: plan.itinerary_plan_ID,
          booking_quote_id: plan.itinerary_quote_ID,
          agent_name: agent?.agent_name || 'N/A',
          primary_customer_name: customer?.customer_name || 'N/A',
          primary_contact_no: customer?.primary_contact_no || 'N/A',
          arrival_location: plan.arrival_location,
          departure_location: plan.departure_location,
          arrival_date: customer?.arrival_date_and_time || plan.trip_start_date_and_time,
          departure_date: customer?.departure_date_and_time || plan.trip_end_date_and_time,
          nights: plan.no_of_nights,
          days: plan.no_of_days,
          created_on: plan.createdon,
          created_by: plan.createdby,
        };
      }),
    );

    return {
      draw,
      recordsTotal,
      recordsFiltered,
      data,
    };
  }

  async getAgentsForFilter() {
    const agents = await this.prisma.dvi_agent.findMany({
      select: {
        agent_ID: true,
        agent_name: true,
        agent_lastname: true,
      },
      orderBy: {
        agent_name: 'asc',
      },
    });

    return agents.map((a) => ({
      id: a.agent_ID,
      name: a.agent_name || '',
      staff_name: a.agent_lastname || '',
    }));
  }

  async getLocationsForFilter() {
    // Get unique arrival and departure locations from confirmed itineraries
    const plans = await this.prisma.dvi_itinerary_plan_details.findMany({
      where: {
        quotation_status: 1,
        deleted: 0,
      },
      select: {
        arrival_location: true,
        departure_location: true,
      },
    });

    const locationsSet = new Set<string>();
    
    plans.forEach((plan) => {
      if (plan.arrival_location) locationsSet.add(plan.arrival_location);
      if (plan.departure_location) locationsSet.add(plan.departure_location);
    });

    return Array.from(locationsSet)
      .filter(loc => loc && loc.trim().length > 0)
      .sort()
      .map(loc => ({ value: loc, label: loc }));
  }

  /**
   * Get unique locations for latest itineraries filter (from all non-deleted plans)
   */
  async getLocationsForLatestFilter(): Promise<{ value: string; label: string }[]> {
    const plans = await this.prisma.dvi_itinerary_plan_details.findMany({
      where: {
        deleted: 0,
      },
      select: {
        arrival_location: true,
        departure_location: true,
      },
    });

    const locationsSet = new Set<string>();
    
    plans.forEach((plan) => {
      if (plan.arrival_location) locationsSet.add(plan.arrival_location);
      if (plan.departure_location) locationsSet.add(plan.departure_location);
    });

    return Array.from(locationsSet)
      .filter(loc => loc && loc.trim().length > 0)
      .sort()
      .map(loc => ({ value: loc, label: loc }));
  }
}