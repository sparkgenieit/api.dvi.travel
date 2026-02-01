import { Injectable, BadRequestException } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import { HotspotEngineService } from '../engines/hotspot-engine.service';

/**
 * Itinerary Hotspot Service
 * Handles all hotspot-related operations for itineraries
 * - Add/delete hotspots
 * - Get available hotspots
 * - Preview hotspot additions
 */
@Injectable()
export class ItineraryHotspotService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly hotspotEngine: HotspotEngineService,
  ) {}

  /**
   * Delete a hotspot from an itinerary route
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
   * Get available hotspots for a route
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
}
