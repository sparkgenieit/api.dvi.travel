// REPLACE-WHOLE-FILE
// FILE: src/itineraries/itineraries.service.ts

import { Injectable, BadRequestException } from "@nestjs/common";
import { PrismaService } from "../../prisma.service";
import {
  CreateItineraryDto,
} from "./dto/create-itinerary.dto";
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

    // Increase interactive transaction timeout; hotspot rebuild + hotel lookups can exceed default 5s
    const result = await this.prisma.$transaction(async (tx) => {
      const planId = await this.planEngine.upsertPlanHeader(
        dto.plan,
        dto.travellers,
        tx,
        userId,
      );

      const routes = await this.routeEngine.rebuildRoutes(
        planId,
        dto.plan,
        dto.routes,
        tx,
        userId,
      );

      console.log('[ItinerariesService] Routes rebuilt, now rebuilding permit charges...');
      
      // Rebuild permit charges after routes are created
      await this.routeEngine.rebuildPermitCharges(tx, planId, userId);
      
      console.log('[ItinerariesService] Permit charges rebuilt');

      // Rebuild via routes AFTER routes are created and BEFORE hotspots
      const routeIds = routes.map((r: any) => r.itinerary_route_ID);
      await this.viaRoutesEngine.rebuildViaRoutes(tx, planId, dto.routes, routeIds, userId);

      await this.planEngine.updateNoOfRoutes(planId, tx);

      await this.travellersEngine.rebuildTravellers(
        planId,
        dto.travellers,
        tx,
        userId,
      );

      await this.vehiclesEngine.rebuildPlanVehicles(
        planId,
        dto.vehicles,
        tx,
        userId,
      );

      if (
        dto.plan.itinerary_preference === 1 ||
        dto.plan.itinerary_preference === 3
      ) {
        await this.hotelEngine.rebuildPlanHotels(
          planId,
          tx,
          userId,
          
        );
      }

      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);

      const planRow = await (tx as any).dvi_itinerary_plan_details.findUnique({
        where: { itinerary_plan_ID: planId },
        select: { itinerary_quote_ID: true },
      });

      return {
        planId,
        quoteId: planRow?.itinerary_quote_ID,
        routeIds: routes.map((r: any) => r.itinerary_route_ID),
        message:
          "Plan created/updated with routes, vehicles, travellers, hotspots, and hotels.",
      };
    }, { timeout: 60000, maxWait: 10000 });

    // Rebuild parking charges AFTER routes and hotspots
    await this.hotspotEngine.rebuildParkingCharges(result.planId, userId);

    // Rebuild vendor eligible list and vendor vehicle details AFTER transaction completes
    // (requires committed routes & hotspots data)
    await this.itineraryVehiclesEngine.rebuildEligibleVendorList({
      planId: result.planId,
      createdBy: userId,
    });

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
  }}