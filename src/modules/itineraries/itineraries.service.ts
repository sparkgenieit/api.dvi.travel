// REPLACE-WHOLE-FILE
// FILE: src/itineraries/itineraries.service.ts

import { Injectable, BadRequestException, NotFoundException } from "@nestjs/common";
import { PrismaService } from "../../prisma.service";
import {
  CreateItineraryDto,
} from "./dto/create-itinerary.dto";
import { ConfirmQuotationDto } from "./dto/confirm-quotation.dto";
import { CancelItineraryDto } from "./dto/cancel-itinerary.dto";
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
import { ItineraryDetailsService } from "./itinerary-details.service";
import { TimeConverter } from "./engines/helpers/time-converter";
import { HotspotDetailRow } from "./engines/helpers/types";

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
    private readonly itineraryDetails: ItineraryDetailsService,
  ) {}

  async createPlan(dto: CreateItineraryDto, req: any) {
    const u: any = (req as any).user ?? {};
    const userId = Number(u.userId ?? 1);
    const agentId = Number(u.agentId ?? 0);
    const staffId = Number(u.staffId ?? 0);

    // If user is an agent, force their agentId
    if (agentId > 0) {
      dto.plan.agent_id = agentId;
    }
    // If user is a staff/travel expert, force their staffId
    if (staffId > 0) {
      dto.plan.staff_id = staffId;
    }

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

      // ⚡ PRESERVE HOTSPOT CONTEXT: Fetch existing hotspots and their route dates BEFORE routes are deleted
      // This ensures that when we rebuild hotspots later, we know which day each "tombstone" (deleted hotspot) belonged to.
      const oldRoutes = await (tx as any).dvi_itinerary_route_details.findMany({
        where: { itinerary_plan_ID: planId },
        select: { itinerary_route_ID: true, itinerary_route_date: true }
      });
      const oldRouteDateMap = new Map(oldRoutes.map((r: any) => [r.itinerary_route_ID, r.itinerary_route_date]));
      
      // ✅ FIX: Only fetch non-deleted hotspots when preparing for rebuild
      // This prevents deleted hotspots from being re-added during rebuild
      const oldHotspots = await (tx as any).dvi_itinerary_route_hotspot_details.findMany({
        where: { itinerary_plan_ID: planId, item_type: 4, deleted: 0 }
      });
      
      const existingHotspotsWithDates = oldHotspots.map((h: any) => ({
        ...h,
        route_date: oldRouteDateMap.get(h.itinerary_route_ID)
      }));

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
      await this.hotspotEngine.rebuildRouteHotspots(tx, planId, existingHotspotsWithDates);
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
   * Hard deletes the hotspot from timeline and adds to excluded_hotspot_ids
   */
  async deleteHotspot(planId: number, routeId: number, hotspotId: number) {
    const userId = 1;

    await this.prisma.$transaction(async (tx) => {
      // First, fetch the hotspot record to get the actual hotspot_ID
      const hotspotRecord = await (tx as any).dvi_itinerary_route_hotspot_details.findUnique({
        where: {
          route_hotspot_ID: hotspotId,
        },
      });

      if (!hotspotRecord) {
        throw new BadRequestException('Hotspot not found');
      }

      const actualHotspotId = hotspotRecord.hotspot_ID; // This is the master hotspot ID

      // Hard delete activities associated with this hotspot
      await (tx as any).dvi_itinerary_route_activity_details.deleteMany({
        where: {
          itinerary_plan_ID: planId,
          itinerary_route_ID: routeId,
          route_hotspot_ID: hotspotId,
        },
      });

      // Hard delete the hotspot record completely
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

      // Get current route to update excluded_hotspot_ids
      const route = await (tx as any).dvi_itinerary_route_details.findUnique({
        where: { itinerary_route_ID: routeId },
      });

      // ✅ FIX: Add the actual hotspot_ID (not route_hotspot_ID) to excluded list
      // This allows the selector to properly filter hotspots by their master hotspot_ID
      const excluded = (route?.excluded_hotspot_ids as number[]) || [];
      if (!excluded.includes(actualHotspotId)) {
        excluded.push(actualHotspotId);
      }

      // Update route with excluded list and timestamp
      await (tx as any).dvi_itinerary_route_details.update({
        where: { itinerary_route_ID: routeId },
        data: {
          excluded_hotspot_ids: excluded,
          updatedon: new Date(),
        },
      });

      // Trigger a full rebuild of the hotspots for this plan
      // This ensures travel times and hotel arrival are recalculated after deletion
      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);
    }, { timeout: 60000 });

    // Rebuild parking charges after deletion
    await this.hotspotEngine.rebuildParkingCharges(planId, userId);

    return {
      success: true,
      message: 'Hotspot deleted and timeline recalculated successfully',
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
        updatedon: new Date(),
        createdby: userId,
      },
    });

    return {
      success: true,
      message: 'Activity deleted successfully',
    };
  }

  /**
   * Get available hotspots for a route
   */
  /**
   * Get available hotspots for a route
   *
   * NEW RULES:
   * - direct_to_next_visiting_place === 1  => destination pool only, priority DESC
   * - direct_to_next_visiting_place === 0  => interleave source/destination in chunks of 3
   * - already added hotspots => mark visitAgain=true (and include them even if not in pool)
   */
  async getAvailableHotspots(routeId: number) {
    // 1) Route
    const route = await (this.prisma as any).dvi_itinerary_route_details.findFirst({
      where: { itinerary_route_ID: routeId, deleted: 0 },
    });

    if (!route || !route.location_id) return [];

    // 2) Location master
    const location = await (this.prisma as any).dvi_stored_locations.findFirst({
      where: { location_ID: Number(route.location_id), deleted: 0 },
    });

    if (!location) return [];

    const sourceName: string | null = (location as any).source_location ?? null;
    const destName: string | null = (location as any).destination_location ?? null;

    const directDestination = Number(route.direct_to_next_visiting_place || 0) === 1;

    // 3) Already-added hotspots for this route => visitAgain
    const alreadyAddedRows = await (this.prisma as any).dvi_itinerary_route_hotspot_details.findMany({
      where: {
        itinerary_route_ID: routeId,
        deleted: 0,
        status: 1,
        item_type: 4,
      },
      select: { hotspot_ID: true },
    });

    const alreadyAddedIds = new Set<number>(
      (alreadyAddedRows || [])
        .map((r: any) => Number(r.hotspot_ID))
        .filter((n: number) => Number.isFinite(n) && n > 0),
    );

    // 3.5) Get excluded hotspot IDs (deleted by user)
    const excludedIds = new Set<number>(
      (route.excluded_hotspot_ids as number[]) || []
    );

    // 4) Pool fetcher (priority DESC + stable tie-break)
    const fetchPool = async (cityName: string | null) => {
      if (!cityName) return [];
      return await (this.prisma as any).dvi_hotspot_place.findMany({
        where: {
          status: 1,
          deleted: 0,
          hotspot_location: { contains: cityName },
        },
        select: {
          hotspot_ID: true,
          hotspot_name: true,
          hotspot_adult_entry_cost: true,
          hotspot_description: true,
          hotspot_duration: true,
          hotspot_location: true,
          hotspot_priority: true,
        },
        orderBy: [{ hotspot_priority: "desc" }, { hotspot_ID: "asc" }],
      });
    };

    const sourcePool = await fetchPool(sourceName);
    const destPool = await fetchPool(destName);

    // 5) Build final ordered list
    const seen = new Set<number>();
    const ordered: any[] = [];

    const pushUnique = (h: any) => {
      const id = Number(h?.hotspot_ID);
      if (!id || seen.has(id)) return;
      if (excludedIds.has(id)) return; // ✅ Skip excluded hotspots
      seen.add(id);
      ordered.push(h);
    };

    if (directDestination) {
      // direct = true => destination only
      for (const h of destPool) pushUnique(h);
    } else {
      // direct = false => interleave 3-by-3 source/dest
      const CHUNK = 3;
      let i = 0;
      let j = 0;

      while (i < sourcePool.length || j < destPool.length) {
        for (let k = 0; k < CHUNK && i < sourcePool.length; k++, i++) pushUnique(sourcePool[i]);
        for (let k = 0; k < CHUNK && j < destPool.length; k++, j++) pushUnique(destPool[j]);
      }
    }

    // 6) Add missing already-added hotspots (if not present in pools)
    const missingAddedIds = [...alreadyAddedIds].filter((id) => !seen.has(id));
    if (missingAddedIds.length > 0) {
      const missing = await (this.prisma as any).dvi_hotspot_place.findMany({
        where: { hotspot_ID: { in: missingAddedIds } },
        select: {
          hotspot_ID: true,
          hotspot_name: true,
          hotspot_adult_entry_cost: true,
          hotspot_description: true,
          hotspot_duration: true,
          hotspot_location: true,
          hotspot_priority: true,
        },
        orderBy: [{ hotspot_priority: "desc" }, { hotspot_ID: "asc" }],
      });
      for (const h of missing) pushUnique(h);
    }

    if (ordered.length === 0) return [];

    // 7) Timings
    const hotspotIds = ordered.map((h: any) => Number(h.hotspot_ID));
    const timings = await (this.prisma as any).dvi_hotspot_timing.findMany({
      where: { hotspot_ID: { in: hotspotIds }, deleted: 0, status: 1 },
      orderBy: { hotspot_start_time: "asc" },
    });

    const timingMap = new Map<number, string>();
    const formatTime = (date: Date | null) => {
      if (!date) return "";
      const h = date.getUTCHours();
      const m = date.getUTCMinutes();
      const ampm = h >= 12 ? "PM" : "AM";
      const h12 = h % 12 || 12;
      return `${String(h12).padStart(2, "0")}:${String(m).padStart(2, "0")} ${ampm}`;
    };

    for (const t of timings) {
      if (t.hotspot_closed === 1) continue;

      let timeStr = "";
      if (t.hotspot_open_all_time === 1) {
        timeStr = "Open 24 Hours";
      } else if (t.hotspot_start_time && t.hotspot_end_time) {
        const start = formatTime(t.hotspot_start_time);
        const end = formatTime(t.hotspot_end_time);
        timeStr = `${start} - ${end}`;
      }

      if (timeStr && !timingMap.has(t.hotspot_ID)) {
        timingMap.set(t.hotspot_ID, timeStr);
      }
    }

    // 8) Response (+ visitAgain)
    return ordered.map((h: any) => ({
      id: h.hotspot_ID,
      name: h.hotspot_name,
      amount: h.hotspot_adult_entry_cost || 0,
      description: h.hotspot_description || "",
      timeSpend: h.hotspot_duration ? new Date(h.hotspot_duration).getUTCHours() : 0,
      locationMap: h.hotspot_location || null,
      timings: timingMap.get(h.hotspot_ID) || "No timings available",
      visitAgain: alreadyAddedIds.has(Number(h.hotspot_ID)),
    }));
  }


  /**
   * Add a hotspot to an itinerary route
   */
  async addHotspot(data: { planId: number; routeId: number; hotspotId: number }) {
    const userId = 1;

    // 1) Insert the manual hotspot record first
    // We mark it with hotspot_plan_own_way = 1 so the engine preserves it
    await (this.prisma as any).dvi_itinerary_route_hotspot_details.create({
      data: {
        itinerary_plan_ID: data.planId,
        itinerary_route_ID: data.routeId,
        hotspot_ID: data.hotspotId,
        item_type: 4, // Hotspot/Attraction type
        hotspot_plan_own_way: 1, // MARK AS MANUAL
        createdby: userId,
        createdon: new Date(),
        status: 1,
        deleted: 0,
      },
    });

    // 1.5) Remove from excluded list if it was previously deleted
    const route = await (this.prisma as any).dvi_itinerary_route_details.findUnique({
      where: { itinerary_route_ID: data.routeId },
    });

    const excluded = (route?.excluded_hotspot_ids as number[]) || [];
    const filteredExcluded = excluded.filter((id: number) => id !== data.hotspotId);

    await (this.prisma as any).dvi_itinerary_route_details.update({
      where: { itinerary_route_ID: data.routeId },
      data: { excluded_hotspot_ids: filteredExcluded },
    });

    // 2) Trigger a full rebuild of the hotspots for this plan
    // The engine will now see the manual hotspot, keep it, and calculate all travel times/hotel shifts
    const result = await this.prisma.$transaction(async (tx) => {
      return await this.hotspotEngine.rebuildRouteHotspots(tx, data.planId);
    }, { timeout: 60000 });

    return {
      success: true,
      message: 'Hotspot added and timeline recalculated successfully',
      shiftedItems: result.shiftedItems,
      droppedItems: result.droppedItems,
    };
  }

  /**
   * Preview adding a hotspot to an itinerary route
   */
  async previewAddHotspot(data: { planId: number; routeId: number; hotspotId: number }) {
    const result = await this.prisma.$transaction(async (tx) => {
      return await this.hotspotEngine.previewManualHotspotAdd(
        tx,
        data.planId,
        data.routeId,
        data.hotspotId,
      );
    }, { timeout: 60000 });

    return result;
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
        h.hotel_id,
        h.hotel_name,
        h.hotel_address,
        h.hotel_latitude,
        h.hotel_longitude,
        h.hotel_category,
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
      id: h.hotel_id,
      name: h.hotel_name,
      address: h.hotel_address,
      category: h.hotel_category,
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

  async getAgentWalletBalance(agentId: number) {
    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: agentId },
      select: { total_cash_wallet: true },
    });

    return { balance: Number(agent?.total_cash_wallet || 0) };
  }

  async confirmQuotation(dto: ConfirmQuotationDto) {
    const userId = 1; // TODO: Get from authenticated user

    // 1. Get plan details and cost breakdown
    const plan = await this.prisma.dvi_itinerary_plan_details.findUnique({
      where: { itinerary_plan_ID: dto.itinerary_plan_ID },
    });

    if (!plan) {
      throw new NotFoundException('Itinerary plan not found');
    }

    if (plan.quotation_status === 1) {
      throw new BadRequestException('Quotation is already confirmed');
    }

    const quoteId = plan.itinerary_quote_ID;
    if (!quoteId) {
      throw new BadRequestException('Quote ID not found for this plan');
    }

    const details = await this.itineraryDetails.getItineraryDetails(quoteId);
    const cost = details.costBreakdown;

    // 2. Check wallet balance
    const walletInfo = await this.getAgentWalletBalance(dto.agent);
    if (walletInfo.balance < cost.netPayable) {
      throw new BadRequestException(`Insufficient wallet balance. Required: ${cost.netPayable}, Available: ${walletInfo.balance}`);
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

      // Update agent balance
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
          arrival_type: plan.arrival_type || 0,
          departure_type: plan.departure_type || 0,
          expecting_budget: plan.expecting_budget || 0,
          itinerary_type: plan.itinerary_type || 0,
          entry_ticket_required: plan.entry_ticket_required || 0,
          no_of_routes: plan.no_of_routes || 0,
          no_of_days: plan.no_of_days || 0,
          no_of_nights: plan.no_of_nights || 0,
          total_adult: plan.total_adult || 0,
          total_children: plan.total_children || 0,
          total_infants: plan.total_infants || 0,
          nationality: plan.nationality || 0,
          itinerary_preference: plan.itinerary_preference || 0,
          meal_plan_breakfast: plan.meal_plan_breakfast || 0,
          meal_plan_lunch: plan.meal_plan_lunch || 0,
          meal_plan_dinner: plan.meal_plan_dinner || 0,
          preferred_room_count: plan.preferred_room_count || 0,
          total_extra_bed: plan.total_extra_bed || 0,
          total_child_with_bed: plan.total_child_with_bed || 0,
          total_child_without_bed: plan.total_child_without_bed || 0,
          guide_for_itinerary: plan.guide_for_itinerary || 0,
          food_type: plan.food_type || 0,
          special_instructions: plan.special_instructions,
          pick_up_date_and_time: plan.pick_up_date_and_time,
          hotel_terms_condition: (plan as any).hotel_terms_condition,
          vehicle_terms_condition: (plan as any).vehicle_terms_condition,
          hotel_rates_visibility: plan.hotel_rates_visibility || 0,
          
          // Costs from breakdown
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

      // G. Copy related tables (Travellers, Vehicles, Routes, Via Routes, Hotels, Hotspots, Activities)
      await this.copyDraftToConfirmed(tx, dto.itinerary_plan_ID, confirmedPlanId, userId);

      // H. Insert into dvi_accounts_itinerary_details
      await tx.dvi_accounts_itinerary_details.create({
        data: {
          itinerary_plan_ID: dto.itinerary_plan_ID,
          agent_id: dto.agent,
          staff_id: plan.staff_id || 0,
          confirmed_itinerary_plan_ID: confirmedPlanId,
          itinerary_quote_ID: plan.itinerary_quote_ID,
          trip_start_date_and_time: plan.trip_start_date_and_time,
          trip_end_date_and_time: plan.trip_end_date_and_time,
          total_billed_amount: cost.netPayable,
          total_received_amount: cost.netPayable,
          total_receivable_amount: 0,
          total_payable_amount: cost.totalAmount, // Total cost before agent margin
          total_payout_amount: 0,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      // I. Update draft plan status
      await tx.dvi_itinerary_plan_details.update({
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
          confirmed_itinerary_plan_ID: confirmedPlanId,
        };
      });
  }

  private async copyDraftToConfirmed(tx: any, draftPlanId: number, confirmedPlanId: number, userId: number) {
    // 2. Vehicles
    const vehicles = await tx.dvi_itinerary_plan_vehicle_details.findMany({
      where: { itinerary_plan_id: draftPlanId, deleted: 0 },
    });
    for (const v of vehicles) {
      await tx.dvi_confirmed_itinerary_plan_vehicle_details.create({
        data: {
          vehicle_details_ID: v.vehicle_details_ID,
          itinerary_plan_id: draftPlanId,
          vehicle_type_id: v.vehicle_type_id,
          vehicle_count: v.vehicle_count,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // 3. Routes
    const routes = await tx.dvi_itinerary_route_details.findMany({
      where: { itinerary_plan_ID: draftPlanId, deleted: 0 },
    });
    for (const r of routes) {
      await tx.dvi_confirmed_itinerary_route_details.create({
        data: {
          itinerary_route_ID: r.itinerary_route_ID,
          itinerary_plan_ID: draftPlanId,
          location_id: r.location_id,
          location_name: r.location_name,
          itinerary_route_date: r.itinerary_route_date,
          no_of_days: r.no_of_days,
          no_of_km: r.no_of_km,
          direct_to_next_visiting_place: r.direct_to_next_visiting_place,
          next_visiting_location: r.next_visiting_location,
          route_start_time: r.route_start_time,
          route_end_time: r.route_end_time,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // 4. Via Routes
    const viaRoutes = await tx.dvi_itinerary_via_route_details.findMany({
      where: { itinerary_plan_ID: draftPlanId, deleted: 0 },
    });
    for (const vr of viaRoutes) {
      await tx.dvi_confirmed_itinerary_via_route_details.create({
        data: {
          itinerary_via_route_ID: vr.itinerary_via_route_ID,
          itinerary_route_ID: vr.itinerary_route_ID,
          itinerary_route_date: vr.itinerary_route_date,
          itinerary_plan_ID: draftPlanId,
          source_location: vr.source_location,
          destination_location: vr.destination_location,
          itinerary_via_location_ID: vr.itinerary_via_location_ID,
          itinerary_via_location_name: vr.itinerary_via_location_name,
          itinerary_session_id: vr.itinerary_session_id,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // 5. Hotels
    const hotels = await tx.dvi_itinerary_plan_hotel_details.findMany({
      where: { itinerary_plan_id: draftPlanId, deleted: 0 },
    });
    for (const h of hotels) {
      await tx.dvi_confirmed_itinerary_plan_hotel_details.create({
        data: {
          itinerary_plan_hotel_details_ID: h.itinerary_plan_hotel_details_ID,
          group_type: h.group_type,
          itinerary_plan_id: draftPlanId,
          itinerary_route_id: h.itinerary_route_id,
          itinerary_route_date: h.itinerary_route_date,
          itinerary_route_location: h.itinerary_route_location,
          hotel_required: h.hotel_required,
          hotel_category_id: h.hotel_category_id,
          hotel_id: h.hotel_id,
          hotel_margin_percentage: h.hotel_margin_percentage,
          hotel_margin_gst_type: h.hotel_margin_gst_type,
          hotel_margin_gst_percentage: h.hotel_margin_gst_percentage,
          hotel_margin_rate: h.hotel_margin_rate,
          hotel_margin_rate_tax_amt: h.hotel_margin_rate_tax_amt,
          hotel_breakfast_cost: h.hotel_breakfast_cost,
          hotel_breakfast_cost_gst_amount: h.hotel_breakfast_cost_gst_amount,
          hotel_lunch_cost: h.hotel_lunch_cost,
          hotel_lunch_cost_gst_amount: h.hotel_lunch_cost_gst_amount,
          hotel_dinner_cost: h.hotel_dinner_cost,
          hotel_dinner_cost_gst_amount: h.hotel_dinner_cost_gst_amount,
          total_no_of_persons: h.total_no_of_persons,
          total_hotel_meal_plan_cost: h.total_hotel_meal_plan_cost,
          total_hotel_meal_plan_cost_gst_amount: h.total_hotel_meal_plan_cost_gst_amount,
          total_extra_bed_cost: h.total_extra_bed_cost,
          total_extra_bed_cost_gst_amount: h.total_extra_bed_cost_gst_amount,
          total_childwith_bed_cost: h.total_childwith_bed_cost,
          total_childwith_bed_cost_gst_amount: h.total_childwith_bed_cost_gst_amount,
          total_childwithout_bed_cost: h.total_childwithout_bed_cost,
          total_childwithout_bed_cost_gst_amount: h.total_childwithout_bed_cost_gst_amount,
          total_no_of_rooms: h.total_no_of_rooms,
          total_room_cost: h.total_room_cost,
          total_room_gst_amount: h.total_room_gst_amount,
          total_hotel_cost: h.total_hotel_cost,
          total_amenities_cost: h.total_amenities_cost,
          total_amenities_gst_amount: h.total_amenities_gst_amount,
          total_hotel_tax_amount: h.total_hotel_tax_amount,
        },
      });
    }

    // 5a. Hotel Room Details
    const hotelRooms = await tx.dvi_itinerary_plan_hotel_room_details.findMany({
      where: { itinerary_plan_id: draftPlanId, deleted: 0 },
    });
    for (const hr of hotelRooms) {
      await tx.dvi_confirmed_itinerary_plan_hotel_room_details.create({
        data: {
          itinerary_plan_hotel_room_details_ID: hr.itinerary_plan_hotel_room_details_ID,
          itinerary_plan_hotel_details_id: hr.itinerary_plan_hotel_details_id,
          group_type: hr.group_type,
          itinerary_plan_id: draftPlanId,
          itinerary_route_id: hr.itinerary_route_id,
          itinerary_route_date: hr.itinerary_route_date,
          hotel_id: hr.hotel_id,
          room_type_id: hr.room_type_id,
          room_id: hr.room_id,
          room_qty: hr.room_qty,
          room_rate: hr.room_rate,
          gst_type: hr.gst_type,
          gst_percentage: hr.gst_percentage,
          extra_bed_count: hr.extra_bed_count,
          extra_bed_rate: hr.extra_bed_rate,
          child_without_bed_count: hr.child_without_bed_count,
          child_without_bed_charges: hr.child_without_bed_charges,
          child_with_bed_count: hr.child_with_bed_count,
          child_with_bed_charges: hr.child_with_bed_charges,
          breakfast_required: hr.breakfast_required,
          lunch_required: hr.lunch_required,
          dinner_required: hr.dinner_required,
          breakfast_cost_per_person: hr.breakfast_cost_per_person,
          lunch_cost_per_person: hr.lunch_cost_per_person,
          dinner_cost_per_person: hr.dinner_cost_per_person,
          total_breafast_cost: hr.total_breafast_cost,
          total_lunch_cost: hr.total_lunch_cost,
          total_dinner_cost: hr.total_dinner_cost,
          total_room_cost: hr.total_room_cost,
          total_room_gst_amount: hr.total_room_gst_amount,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // 5b. Hotel Room Amenities
    const hotelAmenities = await tx.dvi_itinerary_plan_hotel_room_amenities.findMany({
      where: { itinerary_plan_id: draftPlanId, deleted: 0 },
    });
    for (const ha of hotelAmenities) {
      await tx.dvi_confirmed_itinerary_plan_hotel_room_amenities.create({
        data: {
          itinerary_plan_hotel_room_amenities_details_ID: ha.itinerary_plan_hotel_room_amenities_details_ID,
          itinerary_plan_hotel_details_id: ha.itinerary_plan_hotel_details_id,
          group_type: ha.group_type,
          itinerary_plan_id: draftPlanId,
          itinerary_route_id: ha.itinerary_route_id,
          itinerary_route_date: ha.itinerary_route_date,
          hotel_id: ha.hotel_id,
          hotel_amenities_id: ha.hotel_amenities_id,
          total_qty: ha.total_qty,
          amenitie_rate: ha.amenitie_rate,
          total_amenitie_cost: ha.total_amenitie_cost,
          total_amenitie_gst_amount: ha.total_amenitie_gst_amount,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });
    }

    // 6. Hotspots
    const hotspots = await tx.dvi_itinerary_route_hotspot_details.findMany({
      where: { itinerary_plan_ID: draftPlanId, deleted: 0 },
    });
    for (const hs of hotspots) {
      await tx.dvi_confirmed_itinerary_route_hotspot_details.create({
        data: {
          route_hotspot_ID: hs.route_hotspot_ID,
          itinerary_plan_ID: draftPlanId,
          itinerary_route_ID: hs.itinerary_route_ID,
          item_type: hs.item_type,
          hotspot_order: hs.hotspot_order,
          hotspot_ID: hs.hotspot_ID,
          hotspot_adult_entry_cost: hs.hotspot_adult_entry_cost,
          hotspot_child_entry_cost: hs.hotspot_child_entry_cost,
          hotspot_infant_entry_cost: hs.hotspot_infant_entry_cost,
          hotspot_foreign_adult_entry_cost: hs.hotspot_foreign_adult_entry_cost,
          hotspot_foreign_child_entry_cost: hs.hotspot_foreign_child_entry_cost,
          hotspot_foreign_infant_entry_cost: hs.hotspot_foreign_infant_entry_cost,
          hotspot_amout: hs.hotspot_amout,
          hotspot_traveling_time: hs.hotspot_traveling_time,
          itinerary_travel_type_buffer_time: hs.itinerary_travel_type_buffer_time,
          hotspot_travelling_distance: hs.hotspot_travelling_distance,
          hotspot_start_time: hs.hotspot_start_time,
          hotspot_end_time: hs.hotspot_end_time,
          allow_break_hours: hs.allow_break_hours,
          allow_via_route: hs.allow_via_route,
          via_location_name: hs.via_location_name,
          hotspot_plan_own_way: hs.hotspot_plan_own_way,
          createdby: userId,
          status: 1,
        },
      });
    }

    // 7. Activities
    const activities = await tx.dvi_itinerary_route_activity_details.findMany({
      where: { itinerary_plan_ID: draftPlanId, deleted: 0 },
    });
    for (const a of activities) {
      await tx.dvi_confirmed_itinerary_route_activity_details.create({
        data: {
          route_activity_ID: a.route_activity_ID,
          itinerary_plan_ID: draftPlanId,
          itinerary_route_ID: a.itinerary_route_ID,
          route_hotspot_ID: a.route_hotspot_ID,
          hotspot_ID: a.hotspot_ID,
          activity_ID: a.activity_ID,
          activity_order: a.activity_order,
          activity_charges_for_foreign_adult: a.activity_charges_for_foreign_adult,
          activity_charges_for_foreign_children: a.activity_charges_for_foreign_children,
          activity_charges_for_foreign_infant: a.activity_charges_for_foreign_infant,
          activity_charges_for_adult: a.activity_charges_for_adult,
          activity_charges_for_children: a.activity_charges_for_children,
          activity_charges_for_infant: a.activity_charges_for_infant,
          activity_amout: a.activity_amout,
          activity_traveling_time: a.activity_traveling_time,
          activity_start_time: a.activity_start_time,
          activity_end_time: a.activity_end_time,
          createdby: userId,
          status: 1,
        },
      });
    }

    // 8. Guides
    const guides = await tx.dvi_itinerary_route_guide_details.findMany({
      where: { itinerary_plan_ID: draftPlanId, deleted: 0 },
    });
    for (const g of guides) {
      await tx.dvi_confirmed_itinerary_route_guide_details.create({
        data: {
          route_guide_ID: g.route_guide_ID,
          itinerary_plan_ID: draftPlanId,
          itinerary_route_ID: g.itinerary_route_ID,
          guide_id: g.guide_id,
          guide_type: g.guide_type,
          guide_language: g.guide_language,
          guide_slot: g.guide_slot,
          guide_cost: g.guide_cost,
          createdby: userId,
          status: 1,
        },
      });
    }

    // 9. Vendor Eligible List
    const vendorEligible = await tx.dvi_itinerary_plan_vendor_eligible_list.findMany({
      where: { itinerary_plan_id: draftPlanId, deleted: 0 },
    });
    for (const ve of vendorEligible) {
      await tx.dvi_confirmed_itinerary_plan_vendor_eligible_list.create({
        data: {
          itinerary_plan_vendor_eligible_ID: ve.itinerary_plan_vendor_eligible_ID,
          itineary_plan_assigned_status: ve.itineary_plan_assigned_status,
          itinerary_plan_id: draftPlanId,
          vehicle_type_id: ve.vehicle_type_id,
          total_vehicle_qty: ve.total_vehicle_qty,
          vendor_id: ve.vendor_id,
          outstation_allowed_km_per_day: ve.outstation_allowed_km_per_day,
          vendor_vehicle_type_id: ve.vendor_vehicle_type_id,
          vehicle_id: ve.vehicle_id,
          vendor_branch_id: ve.vendor_branch_id,
          vehicle_orign: ve.vehicle_orign,
          vehicle_count: ve.vehicle_count,
          total_kms: ve.total_kms,
          total_outstation_km: ve.total_outstation_km,
          total_time: ve.total_time,
          total_rental_charges: ve.total_rental_charges,
          total_toll_charges: ve.total_toll_charges,
          total_parking_charges: ve.total_parking_charges,
          total_driver_charges: ve.total_driver_charges,
          total_permit_charges: ve.total_permit_charges,
          total_before_6_am_extra_time: ve.total_before_6_am_extra_time,
          total_after_8_pm_extra_time: ve.total_after_8_pm_extra_time,
          total_before_6_am_charges_for_driver: ve.total_before_6_am_charges_for_driver,
          total_before_6_am_charges_for_vehicle: ve.total_before_6_am_charges_for_vehicle,
          total_after_8_pm_charges_for_driver: ve.total_after_8_pm_charges_for_driver,
          total_after_8_pm_charges_for_vehicle: ve.total_after_8_pm_charges_for_vehicle,
          extra_km_rate: ve.extra_km_rate,
          total_allowed_kms: ve.total_allowed_kms,
          total_extra_kms: ve.total_extra_kms,
          total_extra_kms_charge: ve.total_extra_kms_charge,
          total_allowed_local_kms: ve.total_allowed_local_kms,
          total_extra_local_kms: ve.total_extra_local_kms,
          total_extra_local_kms_charge: ve.total_extra_local_kms_charge,
          vehicle_gst_type: ve.vehicle_gst_type,
          vehicle_gst_percentage: ve.vehicle_gst_percentage,
          vehicle_gst_amount: ve.vehicle_gst_amount,
          vehicle_total_amount: ve.vehicle_total_amount,
          vendor_margin_percentage: ve.vendor_margin_percentage,
          vendor_margin_gst_type: ve.vendor_margin_gst_type,
          vendor_margin_gst_percentage: ve.vendor_margin_gst_percentage,
          vendor_margin_amount: ve.vendor_margin_amount,
          vendor_margin_gst_amount: ve.vendor_margin_gst_amount,
          vehicle_grand_total: ve.vehicle_grand_total,
          createdby: userId,
          status: 1,
        },
      });
    }

    // 10. Vendor Vehicle Details
    const vendorVehicleDetails = await tx.dvi_itinerary_plan_vendor_vehicle_details.findMany({
      where: { itinerary_plan_id: draftPlanId, deleted: 0 },
    });
    for (const vvd of vendorVehicleDetails) {
      await tx.dvi_confirmed_itinerary_plan_vendor_vehicle_details.create({
        data: {
          itinerary_plan_vendor_vehicle_details_ID: vvd.itinerary_plan_vendor_vehicle_details_ID,
          itinerary_plan_vendor_eligible_ID: vvd.itinerary_plan_vendor_eligible_ID,
          itinerary_plan_id: draftPlanId,
          itinerary_route_id: vvd.itinerary_route_id,
          itinerary_route_date: vvd.itinerary_route_date,
          vehicle_type_id: vvd.vehicle_type_id,
          vehicle_qty: vvd.vehicle_qty,
          vendor_id: vvd.vendor_id,
          vendor_vehicle_type_id: vvd.vendor_vehicle_type_id,
          vehicle_id: vvd.vehicle_id,
          vendor_branch_id: vvd.vendor_branch_id,
          time_limit_id: vvd.time_limit_id,
          travel_type: vvd.travel_type,
          itinerary_route_location_from: vvd.itinerary_route_location_from,
          itinerary_route_location_to: vvd.itinerary_route_location_to,
          total_running_km: vvd.total_running_km,
          total_running_time: vvd.total_running_time,
          total_siteseeing_km: vvd.total_siteseeing_km,
          total_siteseeing_time: vvd.total_siteseeing_time,
          total_pickup_km: vvd.total_pickup_km,
          total_pickup_duration: vvd.total_pickup_duration,
          total_drop_km: vvd.total_drop_km,
          total_drop_duration: vvd.total_drop_duration,
          total_extra_km: vvd.total_extra_km,
          extra_km_rate: vvd.extra_km_rate,
          total_extra_km_charges: vvd.total_extra_km_charges,
          total_travelled_km: vvd.total_travelled_km,
          total_travelled_time: vvd.total_travelled_time,
          vehicle_rental_charges: vvd.vehicle_rental_charges,
          vehicle_toll_charges: vvd.vehicle_toll_charges,
          vehicle_parking_charges: vvd.vehicle_parking_charges,
          vehicle_driver_charges: vvd.vehicle_driver_charges,
          vehicle_permit_charges: vvd.vehicle_permit_charges,
          before_6_am_extra_time: vvd.before_6_am_extra_time,
          after_8_pm_extra_time: vvd.after_8_pm_extra_time,
          before_6_am_charges_for_driver: vvd.before_6_am_charges_for_driver,
          before_6_am_charges_for_vehicle: vvd.before_6_am_charges_for_vehicle,
          after_8_pm_charges_for_driver: vvd.after_8_pm_charges_for_driver,
          after_8_pm_charges_for_vehicle: vvd.after_8_pm_charges_for_vehicle,
          total_vehicle_amount: vvd.total_vehicle_amount,
          createdby: userId,
          status: 1,
        },
      });
    }

    // 11. Route Permit Charges
    const permitCharges = await tx.dvi_itinerary_plan_route_permit_charge.findMany({
      where: { itinerary_plan_ID: draftPlanId, deleted: 0 },
    });
    for (const pc of permitCharges) {
      await tx.dvi_confirmed_itinerary_plan_route_permit_charge.create({
        data: {
          cnf_itinerary_route_permit_charge_ID: pc.route_permit_charge_ID,
          route_permit_charge_ID: pc.route_permit_charge_ID,
          itinerary_plan_ID: draftPlanId,
          itinerary_route_ID: pc.itinerary_route_ID,
          itinerary_route_date: pc.itinerary_route_date,
          vendor_id: pc.vendor_id,
          vendor_branch_id: pc.vendor_branch_id,
          vendor_vehicle_type_id: pc.vendor_vehicle_type_id,
          source_state_id: pc.source_state_id,
          destination_state_id: pc.destination_state_id,
          permit_cost: pc.permit_cost,
          createdby: userId,
          status: 1,
        },
      });
    }
  }

  async cancelItinerary(dto: CancelItineraryDto) {
    const userId = 1; // TODO: Get from authenticated user

    if (!dto.itinerary_plan_ID) {
      throw new BadRequestException('Itinerary Plan ID is required');
    }

    const confirmedPlan = await this.prisma.dvi_confirmed_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: dto.itinerary_plan_ID, deleted: 0 },
    });

    if (!confirmedPlan) {
      throw new NotFoundException(`Confirmed itinerary not found for Plan ID: ${dto.itinerary_plan_ID}`);
    }

    const totalAmount = confirmedPlan.itinerary_total_net_payable_amount || 0;
    const percentage = Number(dto.cancellation_percentage) || 0;
    const cancellationCharge = Math.round((totalAmount * percentage) / 100);
    const refundAmount = Math.max(0, totalAmount - cancellationCharge);

    return await this.prisma.$transaction(async (tx) => {
      // 1. Create cancellation record
      const cancellation = await tx.dvi_cancelled_itineraries.create({
        data: {
          itinerary_plan_id: Number(dto.itinerary_plan_ID),
          total_cancelled_service_amount: totalAmount,
          total_cancellation_charge: cancellationCharge,
          total_refund_amount: Math.round(refundAmount),
          itinerary_cancellation_status: 1,
          createdby: userId,
          createdon: new Date(),
          status: 1,
          deleted: 0,
        },
      });

      // 2. Refund to wallet
      if (refundAmount > 0) {
        await tx.dvi_cash_wallet.create({
          data: {
            agent_id: confirmedPlan.agent_id,
            transaction_date: new Date(),
            transaction_amount: Math.round(refundAmount),
            transaction_type: 1, // Credit
            remarks: `Refund for Cancelled Itinerary: ${confirmedPlan.itinerary_quote_ID}`,
            transaction_id: confirmedPlan.itinerary_quote_ID,
            createdby: userId,
            createdon: new Date(),
            status: 1,
            deleted: 0,
          },
        });
      }

      // 3. Update draft plan status
      await tx.dvi_itinerary_plan_details.update({
        where: { itinerary_plan_ID: dto.itinerary_plan_ID },
        data: {
          quotation_status: 2, // Cancelled
          updatedon: new Date(),
        },
      });

      // 4. Update confirmed plan status
      await tx.dvi_confirmed_itinerary_plan_details.update({
        where: { confirmed_itinerary_plan_ID: confirmedPlan.confirmed_itinerary_plan_ID },
        data: {
          itinerary_cancellation_status: 1,
          updatedon: new Date(),
        },
      });

      return {
        success: true,
        message: 'Itinerary cancelled successfully',
        refund_amount: Math.round(refundAmount),
      };
    });
  }

  async getAgentsForFilter(req: any) {
    const u: any = (req as any).user ?? {};
    const staffId = Number(u.staffId ?? 0);
    const agentId = Number(u.agentId ?? 0);

    const where: any = { deleted: 0 };

    if (agentId > 0) {
      where.agent_ID = agentId;
    } else if (staffId > 0) {
      where.travel_expert_id = staffId;
    }

    const agents = await this.prisma.dvi_agent.findMany({
      where,
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

  async getConfirmedItineraries(query: LatestItineraryQueryDto, req: any) {
    const {
      start = 0,
      length = 10,
      start_date,
      end_date,
      source_location,
      destination_location,
      agent_id,
      staff_id,
    } = query;

    const u: any = (req as any).user ?? {};
    const logged_user_level = Number(u.roleID ?? u.roleId ?? u.role ?? 0) || 0;
    const input_staff_id = Number(u.staff_id ?? u.staffId ?? 0) || 0;
    const input_agent_id = Number(u.agent_id ?? u.agentId ?? 0) || 0;
    const input_guide_id = Number(u.guide_id ?? u.guideId ?? 0) || 0;

    const where: any = {
      quotation_status: 1,
      deleted: 0,
    };

    if (input_agent_id > 0) {
      where.agent_id = input_agent_id;
    } else if (input_guide_id > 0) {
      // Guide logic: find itineraries where this guide is assigned
      const guideAssignments = await this.prisma.dvi_confirmed_itinerary_route_guide_details.findMany({
        where: { guide_id: input_guide_id, deleted: 0 },
        select: { itinerary_plan_ID: true },
      });
      const assignedPlanIds = [...new Set(guideAssignments.map(a => a.itinerary_plan_ID))];
      where.itinerary_plan_ID = { in: assignedPlanIds };
    } else if (input_staff_id > 0 && logged_user_level !== 6) {
      // Travel Expert logic
      const teAgents = await this.prisma.dvi_agent.findMany({
        where: { travel_expert_id: input_staff_id } as any,
        select: { agent_ID: true },
      });
      const teAgentIds = teAgents.map((a) => Number(a.agent_ID)).filter((n) => n > 0);
      where.OR = [
        { staff_id: input_staff_id },
        ...(teAgentIds.length ? [{ agent_id: { in: teAgentIds } }] : []),
      ];
    } else {
      if (agent_id) where.agent_id = agent_id;
      if (staff_id) where.staff_id = staff_id;
    }

    if (start_date && end_date) {
      where.trip_start_date_and_time = {
        gte: new Date(start_date),
        lte: new Date(end_date),
      };
    }

    if (source_location) {
      where.arrival_location = { contains: source_location };
    }

    if (destination_location) {
      where.departure_location = { contains: destination_location };
    }

    const [total, filtered, data] = await Promise.all([
      this.prisma.dvi_itinerary_plan_details.count({
        where: { quotation_status: 1, deleted: 0 },
      }),
      this.prisma.dvi_itinerary_plan_details.count({ where }),
      this.prisma.dvi_itinerary_plan_details.findMany({
        where,
        skip: Number(start),
        take: Number(length),
        orderBy: { createdon: 'desc' },
      }),
    ]);

    // Fetch agents manually since no relations in Prisma schema
    const agentIds = [...new Set(data.map((p) => p.agent_id))];
    const agents = await this.prisma.dvi_agent.findMany({
      where: { agent_ID: { in: agentIds } },
      select: { agent_ID: true, agent_name: true },
    });
    const agentMap = new Map(agents.map((a) => [a.agent_ID, a.agent_name]));

    return {
      draw: query.draw || 1,
      recordsTotal: total,
      recordsFiltered: filtered,
      data: data.map((p) => ({
        itinerary_plan_ID: p.itinerary_plan_ID,
        booking_quote_id: p.itinerary_quote_ID,
        agent_name: agentMap.get(p.agent_id) || 'N/A',
        primary_customer_name: 'N/A', // Customer info not in this table
        primary_contact_no: 'N/A',
        arrival_location: p.arrival_location,
        departure_location: p.departure_location,
        arrival_date: p.trip_start_date_and_time,
        departure_date: p.trip_end_date_and_time,
        nights: p.no_of_nights,
        days: p.no_of_days,
        created_on: p.createdon,
        created_by: p.createdby,
      })),
    };
  }

  async getCancelledItineraries(query: LatestItineraryQueryDto, req: any) {
    const {
      start = 0,
      length = 10,
      start_date,
      end_date,
      agent_id,
    } = query;

    const u: any = (req as any).user ?? {};
    const logged_user_level = Number(u.roleID ?? u.roleId ?? u.role ?? 0) || 0;
    const input_staff_id = Number(u.staff_id ?? u.staffId ?? 0) || 0;
    const input_agent_id = Number(u.agent_id ?? u.agentId ?? 0) || 0;

    const where: any = {
      deleted: 0,
    };

    if (start_date && end_date) {
      where.createdon = {
        gte: new Date(start_date),
        lte: new Date(end_date),
      };
    }

    if (input_agent_id > 0) {
      const agentPlans = await this.prisma.dvi_itinerary_plan_details.findMany({
        where: { agent_id: input_agent_id },
        select: { itinerary_plan_ID: true },
      });
      where.itinerary_plan_id = { in: agentPlans.map((p) => p.itinerary_plan_ID) };
    } else if (input_staff_id > 0 && logged_user_level !== 6) {
      const teAgents = await this.prisma.dvi_agent.findMany({
        where: { travel_expert_id: input_staff_id } as any,
        select: { agent_ID: true },
      });
      const teAgentIds = teAgents.map((a) => Number(a.agent_ID)).filter((n) => n > 0);
      
      const tePlans = await this.prisma.dvi_itinerary_plan_details.findMany({
        where: {
          OR: [
            { staff_id: input_staff_id },
            ...(teAgentIds.length ? [{ agent_id: { in: teAgentIds } }] : []),
          ],
        },
        select: { itinerary_plan_ID: true },
      });
      where.itinerary_plan_id = { in: tePlans.map((p) => p.itinerary_plan_ID) };
    } else if (agent_id) {
      const agentPlans = await this.prisma.dvi_itinerary_plan_details.findMany({
        where: { agent_id: agent_id },
        select: { itinerary_plan_ID: true },
      });
      where.itinerary_plan_id = { in: agentPlans.map((p) => p.itinerary_plan_ID) };
    }

    const [total, filtered, data] = await Promise.all([
      this.prisma.dvi_cancelled_itineraries.count({
        where: { deleted: 0 },
      }),
      this.prisma.dvi_cancelled_itineraries.count({ where }),
      this.prisma.dvi_cancelled_itineraries.findMany({
        where,
        skip: Number(start),
        take: Number(length),
        orderBy: { createdon: 'desc' },
      }),
    ]);

    // Fetch plan details and agents manually
    const planIds = data.map((p) => p.itinerary_plan_id);
    const plans = await this.prisma.dvi_itinerary_plan_details.findMany({
      where: { itinerary_plan_ID: { in: planIds } },
      select: { itinerary_plan_ID: true, itinerary_quote_ID: true, agent_id: true },
    });
    const planMap = new Map(plans.map((p) => [p.itinerary_plan_ID, p]));

    const agentIds = [...new Set(plans.map((p) => p.agent_id))];
    const agents = await this.prisma.dvi_agent.findMany({
      where: { agent_ID: { in: agentIds } },
      select: { agent_ID: true, agent_name: true },
    });
    const agentMap = new Map(agents.map((a) => [a.agent_ID, a.agent_name]));

    return {
      draw: query.draw || 1,
      recordsTotal: total,
      recordsFiltered: filtered,
      data: data.map((p) => {
        const plan = planMap.get(p.itinerary_plan_id);
        return {
          cancelled_itinerary_ID: p.cancelled_itinerary_ID,
          itinerary_plan_ID: p.itinerary_plan_id,
          booking_quote_id: plan?.itinerary_quote_ID || 'N/A',
          agent_name: agentMap.get(plan?.agent_id || 0) || 'N/A',
          cancelled_date: p.createdon,
          cancelled_reason: 'N/A', // Reason not in this table
          refund_amount: p.total_refund_amount,
          refund_status: p.itinerary_cancellation_status,
        };
      }),
    };
  }

  async getAccountsItineraries(query: LatestItineraryQueryDto, req: any) {
    const {
      start = 0,
      length = 10,
      agent_id,
    } = query;

    const u: any = (req as any).user ?? {};
    const logged_user_level = Number(u.roleID ?? u.roleId ?? u.role ?? 0) || 0;
    const input_staff_id = Number(u.staff_id ?? u.staffId ?? 0) || 0;
    const input_agent_id = Number(u.agent_id ?? u.agentId ?? 0) || 0;

    const where: any = {
      deleted: 0,
    };

    if (input_agent_id > 0) {
      where.agent_id = input_agent_id;
    } else if (input_staff_id > 0 && logged_user_level !== 6) {
      const teAgents = await this.prisma.dvi_agent.findMany({
        where: { travel_expert_id: input_staff_id } as any,
        select: { agent_ID: true },
      });
      const teAgentIds = teAgents.map((a) => Number(a.agent_ID)).filter((n) => n > 0);
      where.OR = [
        { staff_id: input_staff_id },
        ...(teAgentIds.length ? [{ agent_id: { in: teAgentIds } }] : []),
      ];
    } else if (agent_id) {
      where.agent_id = agent_id;
    }

    const [total, filtered, data] = await Promise.all([
      this.prisma.dvi_accounts_itinerary_details.count({
        where: { deleted: 0 },
      }),
      this.prisma.dvi_accounts_itinerary_details.count({ where }),
      this.prisma.dvi_accounts_itinerary_details.findMany({
        where,
        skip: Number(start),
        take: Number(length),
        orderBy: { createdon: 'desc' },
      }),
    ]);

    // Fetch agents manually
    const agentIds = [...new Set(data.map((p) => p.agent_id))];
    const agents = await this.prisma.dvi_agent.findMany({
      where: { agent_ID: { in: agentIds } },
      select: { agent_ID: true, agent_name: true },
    });
    const agentMap = new Map(agents.map((a) => [a.agent_ID, a.agent_name]));

    return {
      draw: query.draw || 1,
      recordsTotal: total,
      recordsFiltered: filtered,
      data: data.map((p) => ({
        accounts_itinerary_details_ID: p.accounts_itinerary_details_ID,
        itinerary_plan_ID: p.itinerary_plan_ID,
        booking_quote_id: p.itinerary_quote_ID,
        agent_name: agentMap.get(p.agent_id) || 'N/A',
        trip_start_date: p.trip_start_date_and_time,
        trip_end_date: p.trip_end_date_and_time,
        total_billed_amount: p.total_billed_amount,
        total_received_amount: p.total_received_amount,
        total_receivable_amount: p.total_receivable_amount,
        total_payable_amount: p.total_payable_amount,
        total_payout_amount: p.total_payout_amount,
        created_on: p.createdon,
      })),
    };
  }

  async getVoucherDetails(itineraryPlanId: number) {
    const plan = await this.prisma.dvi_confirmed_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0 },
    });

    if (!plan) {
      throw new NotFoundException('Confirmed itinerary plan not found');
    }

    const customer = await this.prisma.dvi_confirmed_itinerary_customer_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, primary_customer: 1, deleted: 0 },
    });

    const vehicles = await this.prisma.dvi_confirmed_itinerary_plan_vendor_eligible_list.findMany({
      where: { itinerary_plan_id: itineraryPlanId, deleted: 0, status: 1, itineary_plan_assigned_status: 1 },
    });

    const hotels = await this.prisma.dvi_confirmed_itinerary_plan_hotel_details.findMany({
      where: { itinerary_plan_id: itineraryPlanId, deleted: 0, status: 1 },
      orderBy: { itinerary_route_date: 'asc' },
    });

    // Fetch additional details for vehicles
    const vehicleDetails = await Promise.all(vehicles.map(async (v) => {
      const vendor = await this.prisma.dvi_vendor_details.findUnique({
        where: { vendor_id: v.vendor_id },
        select: { vendor_name: true },
      });
      const vehicleType = await this.prisma.dvi_vehicle_type.findUnique({
        where: { vehicle_type_id: v.vehicle_type_id },
        select: { vehicle_type_title: true },
      });
      const branch = await this.prisma.dvi_vendor_branches.findUnique({
        where: { vendor_branch_id: v.vendor_branch_id },
        select: { vendor_branch_name: true },
      });

      return {
        ...v,
        vendor_name: vendor?.vendor_name || 'N/A',
        vehicle_type_title: vehicleType?.vehicle_type_title || 'N/A',
        branch_label: branch?.vendor_branch_name || 'N/A',
      };
    }));

    // Fetch additional details for hotels
    const hotelDetails = await Promise.all(hotels.map(async (h) => {
      const hotel = await this.prisma.dvi_hotel.findUnique({
        where: { hotel_id: h.hotel_id },
        select: { hotel_name: true },
      });
      
      const rooms = await this.prisma.dvi_confirmed_itinerary_plan_hotel_room_details.findMany({
        where: { confirmed_itinerary_plan_hotel_details_id: h.confirmed_itinerary_plan_hotel_details_ID, deleted: 0 },
      });

      const roomDetails = await Promise.all(rooms.map(async (r) => {
        const roomType = await this.prisma.dvi_hotel_roomtype.findUnique({
          where: { room_type_id: r.room_type_id },
          select: { room_type_title: true },
        });
        return {
          ...r,
          room_type_title: roomType?.room_type_title || 'N/A',
        };
      }));

      return {
        ...h,
        hotel_name: hotel?.hotel_name || 'N/A',
        rooms: roomDetails,
      };
    }));

    return {
      plan,
      customer,
      vehicles: vehicleDetails,
      hotels: hotelDetails,
    };
  }

  async getPluckCardData(itineraryPlanId: number) {
    const plan = await this.prisma.dvi_confirmed_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0 },
    });

    if (!plan) {
      throw new NotFoundException('Confirmed itinerary plan not found');
    }

    const customer = await this.prisma.dvi_confirmed_itinerary_customer_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, primary_customer: 1, deleted: 0 },
    });

    return {
      guestName: customer ? `${customer.customer_salutation || ''} ${customer.customer_name}`.trim() : 'N/A',
      contactNo: customer?.primary_contact_no || 'N/A',
      arrivalLocation: plan.arrival_location,
      arrivalDateTime: plan.trip_start_date_and_time,
      departureLocation: plan.departure_location,
      departureDateTime: plan.trip_end_date_and_time,
      flightDetails: plan.special_instructions,
    };
  }

  async getPluckCardDataByConfirmedId(confirmedPlanId: number) {
    const plan = await this.prisma.dvi_confirmed_itinerary_plan_details.findUnique({
      where: { confirmed_itinerary_plan_ID: confirmedPlanId },
    });

    if (!plan) {
      throw new NotFoundException('Confirmed itinerary plan not found');
    }

    const customer = await this.prisma.dvi_confirmed_itinerary_customer_details.findFirst({
      where: { confirmed_itinerary_plan_ID: confirmedPlanId, primary_customer: 1, deleted: 0 },
    });

    return {
      guestName: customer ? `${customer.customer_salutation || ''} ${customer.customer_name}`.trim() : 'N/A',
      contactNo: customer?.primary_contact_no || 'N/A',
      arrivalLocation: plan.arrival_location,
      arrivalDateTime: plan.trip_start_date_and_time,
      departureLocation: plan.departure_location,
      departureDateTime: plan.trip_end_date_and_time,
      flightDetails: plan.special_instructions,
    };
  }

  async getInvoiceData(itineraryPlanId: number) {
    const plan = await this.prisma.dvi_confirmed_itinerary_plan_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0 },
    });

    if (!plan) {
      throw new NotFoundException('Confirmed itinerary plan not found');
    }

    const agent = await this.prisma.dvi_agent.findUnique({
      where: { agent_ID: plan.agent_id },
    });

    const customer = await this.prisma.dvi_confirmed_itinerary_customer_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, primary_customer: 1, deleted: 0 },
    });

    const settings = await this.prisma.dvi_global_settings.findFirst({
      where: { status: 1, deleted: 0 },
    });

    const accounts = await this.prisma.dvi_accounts_itinerary_details.findFirst({
      where: { itinerary_plan_ID: itineraryPlanId, deleted: 0 },
    });

    return {
      company: settings,
      agent,
      guest: customer,
      itinerary: plan,
      totalAmount: accounts?.total_billed_amount || 0,
    };
  }

  /**
   * Preview manual hotspot addition.
   */
  async previewManualHotspot(planId: number, routeId: number, hotspotId: number) {
    return this.prisma.$transaction(async (tx) => {
      // Use the timeline builder directly for preview
      const { TimelineBuilder } = await import("./engines/helpers/timeline.builder");
      const builder = new TimelineBuilder();
      const result = await builder.previewManualHotspotAdd(tx, planId, routeId, hotspotId);

      // Filter for the specific route we are previewing
      const rows = (result.hotspotRows as HotspotDetailRow[]).filter(r => Number(r.itinerary_route_ID) === Number(routeId));
      
      // Fetch hotspot names for display
      const hotspotIds = rows.map(r => r.hotspot_ID).filter((id): id is number => !!id && id > 0);
      const hotspotMasters = await tx.dvi_hotspot_place.findMany({
        where: { hotspot_ID: { in: hotspotIds } }
      });
      const hotspotMap = new Map(hotspotMasters.map(h => [Number(h.hotspot_ID), h.hotspot_name]));

      // Map to frontend segment format
      return rows.map(r => {
        const startTime = this.itineraryDetails.formatTime(r.hotspot_start_time);
        const endTime = this.itineraryDetails.formatTime(r.hotspot_end_time);
        
        let text = '';
        switch(Number(r.item_type)) {
          case 1: text = 'Start / Refreshment'; break;
          case 2: text = 'Travel'; break;
          case 3: text = 'Travel'; break;
          case 4: text = hotspotMap.get(Number(r.hotspot_ID)) || 'Sightseeing'; break;
          case 5: text = 'Travel to Hotel'; break;
          case 6: text = 'Hotel Check-in'; break;
          case 7: text = 'Travel to Departure'; break;
          default: text = 'Activity';
        }

        return {
          type: r.item_type === 4 ? 'hotspot' : 'other',
          timeRange: startTime && endTime ? `${startTime} - ${endTime}` : (startTime || endTime || ''),
          text: text,
          isConflict: r.isConflict === true,
          conflictReason: r.conflictReason || null,
          locationId: r.hotspot_ID
        };
      });
    }, { timeout: 30000 });
  }

  /**
   * Add a manual hotspot to a route and rebuild the timeline.
   */
  async addManualHotspot(planId: number, routeId: number, hotspotId: number, userId: number) {
    return this.prisma.$transaction(async (tx) => {
      // 1. Insert the manual hotspot record
      // We only need a minimal record; the engine will fill in the rest during rebuild
      await (tx as any).dvi_itinerary_route_hotspot_details.create({
        data: {
          itinerary_plan_ID: Number(planId),
          itinerary_route_ID: Number(routeId),
          hotspot_ID: Number(hotspotId),
          hotspot_plan_own_way: 1,
          item_type: 4, // Hotspot visit
          createdby: Number(userId),
          status: 1,
          deleted: 0,
        },
      });

      // 2. Rebuild the timeline
      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);

      return { success: true };
    }, { timeout: 30000 });
  }

  /**
   * Remove a manual hotspot and rebuild the timeline.
   */
  async removeManualHotspot(planId: number, hotspotId: number) {
    return this.prisma.$transaction(async (tx) => {
      await (tx as any).dvi_itinerary_route_hotspot_details.updateMany({
        where: {
          itinerary_plan_ID: Number(planId),
          hotspot_ID: Number(hotspotId),
          hotspot_plan_own_way: 1,
        },
        data: { deleted: 1 }
      });

      await this.hotspotEngine.rebuildRouteHotspots(tx, Number(planId));

      return { success: true };
    }, { timeout: 30000 });
  }

  /**
   * Rebuild a route: Clear excluded hotspots and rebuild fresh
   * This lets user get new auto-selected hotspots to replace deleted ones
   */
  async rebuildRoute(planId: number, routeId: number) {
    const userId = 1;

    return this.prisma.$transaction(async (tx) => {
      // Clear the excluded_hotspot_ids so all hotspots can be auto-selected again
      await (tx as any).dvi_itinerary_route_details.update({
        where: { itinerary_route_ID: routeId },
        data: {
          excluded_hotspot_ids: [],
          updatedon: new Date(),
        },
      });

      // Rebuild the timeline with fresh selection
      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);

      return { 
        success: true,
        message: 'Route rebuilt with fresh hotspot selection',
      };
    }, { timeout: 60000 });
  }

  /**
   * Update route start and end times and rebuild the timeline.
   */
  async updateRouteTimes(planId: number, routeId: number, startTime: string, endTime: string) {
    console.log(`[updateRouteTimes] planId=${planId}, routeId=${routeId}, startTime=${startTime}, endTime=${endTime}`);
    
    return this.prisma.$transaction(async (tx) => {
      // 1. Verify the record exists and belongs to the plan
      const record = await (tx as any).dvi_itinerary_route_details.findFirst({
        where: { 
          itinerary_route_ID: routeId,
          itinerary_plan_ID: planId
        }
      });

      if (!record) {
        console.error(`[updateRouteTimes] Record NOT found for routeId=${routeId} and planId=${planId}`);
        // Check if it exists with a different planId for debugging
        const otherRecord = await (tx as any).dvi_itinerary_route_details.findUnique({
          where: { itinerary_route_ID: routeId }
        });
        if (otherRecord) {
          console.error(`[updateRouteTimes] Record exists but belongs to planId=${otherRecord.itinerary_plan_ID}`);
          throw new BadRequestException(`Route ${routeId} belongs to plan ${otherRecord.itinerary_plan_ID}, not ${planId}`);
        }
        throw new BadRequestException(`Route ${routeId} not found`);
      }

      console.log(`[updateRouteTimes] Found record. Updating...`);

      // 2. Update the route details
      await (tx as any).dvi_itinerary_route_details.update({
        where: { itinerary_route_ID: routeId },
        data: {
          route_start_time: TimeConverter.toDate(startTime),
          route_end_time: TimeConverter.toDate(endTime),
          updatedon: new Date(),
        },
      });

      // 3. Rebuild the timeline for the entire plan
      console.log(`[updateRouteTimes] Rebuilding timeline for planId=${planId}...`);
      await this.hotspotEngine.rebuildRouteHotspots(tx, planId);

      return { success: true };
    }, { timeout: 60000 });
  }
}
