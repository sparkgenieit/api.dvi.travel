// FILE: src/modules/itineraries/engines/helpers/distance.helper.ts

import { Prisma } from "@prisma/client";
import { minutesToDurationTime, minutesToTime, secondsToDurationTime, timeToSeconds } from "./time.helper";
import { TimeConverter } from "./time-converter";

type Tx = Prisma.TransactionClient;

// In-memory cache for distance lookups
const distanceCache = new Map<string, any>();

export interface DistanceResult {
  distanceKm: number; // e.g. 8.42
  travelTime: string; // HH:MM:SS
  bufferTime: string; // HH:MM:SS
}

/**
 * Parse dvi_stored_locations.duration into TOTAL MINUTES.
 * Supports:
 * - "49 mins"
 * - "1 hour 56 mins"
 * - "3 hours 5 mins"
 * - "1 day 1 hour"
 * - "1 day 0 hours"
 * - "1 day 2 hours 15 mins"
 * Also supports numeric durations (treated as minutes).
 */
function parseDurationToMinutes(duration: any): number | null {
  if (duration == null) return null;

  if (typeof duration === "number" && Number.isFinite(duration)) {
    return Math.max(0, Math.floor(duration));
  }

  const s = String(duration).trim().toLowerCase();
  if (!s) return null;

  let days = 0;
  let hours = 0;
  let mins = 0;

  const dMatch = s.match(/(\d+)\s*day/);
  const hMatch = s.match(/(\d+)\s*hour/);
  const mMatch = s.match(/(\d+)\s*min/);

  if (dMatch) days = Number(dMatch[1] || 0);
  if (hMatch) hours = Number(hMatch[1] || 0);
  if (mMatch) mins = Number(mMatch[1] || 0);

  if (!dMatch && !hMatch && !mMatch) {
    const n = Number(s);
    if (Number.isFinite(n)) return Math.max(0, Math.floor(n));
    return null;
  }

  return days * 1440 + hours * 60 + mins;
}

/**
 * CACHE-FIRST: Get or compute hotspot-to-hotspot distance + travel time.
 * 
 * Checks HotspotDistanceCache first:
 * - If found AND speedKmph matches AND correctionFactor matches AND updatedAt < 30 days old → return cached
 * - Otherwise compute haversine, apply correction, upsert BOTH directions (A→B and B→A), return result
 * 
 * Inputs:
 * - tx: Prisma TransactionClient
 * - fromHotspotId?: ID of origin hotspot (required for cache lookup)
 * - toHotspotId?: ID of destination hotspot (required for cache lookup)
 * - fromLat, fromLng, toLat, toLng: Coordinates for haversine computation
 * - travelLocationType: 1 (local) or 2 (outstation)
 * - speedKmph?: Override speed; if missing, fetches from global settings
 * - correctionFactor?: Override correction; defaults to 1.5
 * - bufferMinutes?: Override buffer; if missing, fetches from global settings
 * 
 * Returns:
 * - { distanceKm, travelTime (HH:MM:SS), bufferTime (HH:MM:SS) }
 */
async function getOrComputeDistanceCached(
  tx: Tx,
  opts: {
    fromHotspotId?: number | null;
    toHotspotId?: number | null;
    fromLat: number;
    fromLng: number;
    toLat: number;
    toLng: number;
    travelLocationType: 1 | 2;
    speedKmph?: number;
    correctionFactor?: number;
    bufferMinutes?: number;
  },
): Promise<DistanceResult> {
  const {
    fromHotspotId,
    toHotspotId,
    fromLat,
    fromLng,
    toLat,
    toLng,
    travelLocationType,
    speedKmph: overrideSpeed,
    correctionFactor: overrideCorrectionFactor,
    bufferMinutes: overrideBuffer,
  } = opts;

  // Fetch global settings if not provided
  let speedKmph = overrideSpeed;
  let correctionFactor = overrideCorrectionFactor ?? 1.5;
  let bufferMinutes = overrideBuffer;

  if (!speedKmph || !Number.isFinite(bufferMinutes)) {
    const gs = await (tx as any).dvi_global_settings.findFirst({
      where: { deleted: 0, status: 1 },
    });

    if (!speedKmph) {
      speedKmph =
        travelLocationType === 1
          ? Number(gs?.itinerary_local_speed_limit ?? 40)
          : Number(gs?.itinerary_outstation_speed_limit ?? 60);
    }

    if (!Number.isFinite(bufferMinutes)) {
      bufferMinutes =
        travelLocationType === 1
          ? Number(gs?.itinerary_local_buffer_time ?? 0)
          : Number(gs?.itinerary_outstation_buffer_time ?? 0);
    }
  }

  // ===== CACHE-FIRST LOOKUP =====
  // Only try cache if both hotspot IDs are present
  if (fromHotspotId && toHotspotId) {
    const cached = await (tx as any).hotspotDistanceCache.findUnique({
      where: {
        fromHotspotId_toHotspotId_travelLocationType: {
          fromHotspotId,
          toHotspotId,
          travelLocationType,
        },
      },
    });

    if (cached) {
      const cachedSpeed = Number(cached.speedKmph);
      const cachedCorrection = Number(cached.correctionFactor);
      const isSpeedMatch = Math.abs(cachedSpeed - speedKmph) < 0.01;
      const isCorrectionMatch = Math.abs(cachedCorrection - correctionFactor) < 0.001;

      if (isSpeedMatch && isCorrectionMatch) {
        // Check age: valid if updated in last 30 days
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);

        if (cached.updatedAt >= thirtyDaysAgo) {
          // CACHE HIT → return persisted values
          const travelTime = formatTimeFromDate(cached.travelTime);
          const bufferTime = minutesToTime(bufferMinutes ?? 0);

          return {
            distanceKm: Number(cached.distanceKm),
            travelTime,
            bufferTime,
          };
        }
      }
    }
  }

  // ===== CACHE MISS OR STALE → COMPUTE =====
  const earthRadius = 6371;

  const startLatRad = (fromLat * Math.PI) / 180;
  const startLonRad = (fromLng * Math.PI) / 180;
  const endLatRad = (toLat * Math.PI) / 180;
  const endLonRad = (toLng * Math.PI) / 180;

  const latDiff = endLatRad - startLatRad;
  const lonDiff = endLonRad - startLonRad;

  const a =
    Math.pow(Math.sin(latDiff / 2), 2) +
    Math.cos(startLatRad) * Math.cos(endLatRad) * Math.pow(Math.sin(lonDiff / 2), 2);

  const haversineKm = 2 * earthRadius * Math.asin(Math.sqrt(a));
  const correctedDistance = haversineKm * correctionFactor;

  // Compute travel time: distance / speed * 60 = minutes, then convert to HH:MM:SS
  const travelMinutes = (correctedDistance / speedKmph) * 60;
  const travelTime = secondsToDurationTime(travelMinutes * 60);

  const bufferTime = minutesToTime(bufferMinutes ?? 0);

  // ===== UPSERT BOTH DIRECTIONS =====
  if (fromHotspotId && toHotspotId) {
    // Convert travel time to TIME(0)-safe Date for DB
    const travelTimeDate = TimeConverter.stringToDate(travelTime);

    // Fetch hotspot names for cache storage
    let fromName: string | null = null;
    let toName: string | null = null;

    try {
      const [fromHotspot, toHotspot] = await Promise.all([
        (tx as any).dvi_hotspot_place?.findUnique?.({
          where: { hotspot_ID: fromHotspotId },
          select: { hotspot_name: true },
        }),
        (tx as any).dvi_hotspot_place?.findUnique?.({
          where: { hotspot_ID: toHotspotId },
          select: { hotspot_name: true },
        }),
      ]);

      fromName = fromHotspot?.hotspot_name || null;
      toName = toHotspot?.hotspot_name || null;
    } catch (err) {
      // If fetching names fails, continue without them (graceful degradation)
      console.warn("Warning: Could not fetch hotspot names for cache:", err);
    }

    // Upsert A→B
    await (tx as any).hotspotDistanceCache.upsert({
      where: {
        fromHotspotId_toHotspotId_travelLocationType: {
          fromHotspotId,
          toHotspotId,
          travelLocationType,
        },
      },
      create: {
        fromHotspotId,
        toHotspotId,
        travelLocationType,
        fromHotspotName: fromName,
        toHotspotName: toName,
        haversineKm,
        correctionFactor,
        distanceKm: correctedDistance,
        speedKmph,
        travelTime: travelTimeDate,
        method: "HAVERSINE",
      },
      update: {
        fromHotspotName: fromName,
        toHotspotName: toName,
        haversineKm,
        correctionFactor,
        distanceKm: correctedDistance,
        speedKmph,
        travelTime: travelTimeDate,
        updatedAt: new Date(),
      },
    });

    // Upsert B→A (haversine is symmetric)
    await (tx as any).hotspotDistanceCache.upsert({
      where: {
        fromHotspotId_toHotspotId_travelLocationType: {
          fromHotspotId: toHotspotId,
          toHotspotId: fromHotspotId,
          travelLocationType,
        },
      },
      create: {
        fromHotspotId: toHotspotId,
        toHotspotId: fromHotspotId,
        travelLocationType,
        fromHotspotName: toName,
        toHotspotName: fromName,
        haversineKm,
        correctionFactor,
        distanceKm: correctedDistance,
        speedKmph,
        travelTime: travelTimeDate,
        method: "HAVERSINE",
      },
      update: {
        fromHotspotName: toName,
        toHotspotName: fromName,
        haversineKm,
        correctionFactor,
        distanceKm: correctedDistance,
        speedKmph,
        travelTime: travelTimeDate,
        updatedAt: new Date(),
      },
    });
  }

  return {
    distanceKm: correctedDistance,
    travelTime,
    bufferTime,
  };
}

/**
 * Format a DATE/TIME from database to HH:MM:SS string.
 * Prisma TIME(0) fields come back as Date objects in UTC.
 */
function formatTimeFromDate(dateOrNull: any): string {
  if (!dateOrNull) return "00:00:00";

  if (dateOrNull instanceof Date) {
    const h = String(dateOrNull.getUTCHours()).padStart(2, "0");
    const m = String(dateOrNull.getUTCMinutes()).padStart(2, "0");
    const s = String(dateOrNull.getUTCSeconds()).padStart(2, "0");
    return `${h}:${m}:${s}`;
  }

  return String(dateOrNull);
}



export class DistanceHelper {
  async fromCoordinates(
    tx: Tx,
    startLat: number,
    startLon: number,
    endLat: number,
    endLon: number,
    travelLocationType: 1 | 2,
  ): Promise<DistanceResult> {
    const earthRadius = 6371;

    const startLatRad = (startLat * Math.PI) / 180;
    const startLonRad = (startLon * Math.PI) / 180;
    const endLatRad = (endLat * Math.PI) / 180;
    const endLonRad = (endLon * Math.PI) / 180;

    const latDiff = endLatRad - startLatRad;
    const lonDiff = endLonRad - startLonRad;

    const a =
      Math.pow(Math.sin(latDiff / 2), 2) +
      Math.cos(startLatRad) * Math.cos(endLatRad) * Math.pow(Math.sin(lonDiff / 2), 2);

    const distance = 2 * earthRadius * Math.asin(Math.sqrt(a));

    const correctionFactor = 1.5;
    const correctedDistance = distance * correctionFactor;

    const gs = await (tx as any).dvi_global_settings.findFirst({
      where: { deleted: 0, status: 1 },
    });

    const avgSpeedKmPerHr =
      travelLocationType === 1
        ? Number(gs?.itinerary_local_speed_limit ?? 40)
        : Number(gs?.itinerary_outstation_speed_limit ?? 60);

    const durationHours = correctedDistance / avgSpeedKmPerHr;
    const wholeHours = Math.floor(durationHours);
    const minutes = Math.round((durationHours - wholeHours) * 60);

    // This is a computed travel time (not from DB). It should never exceed 24h normally.
    const travelTime = `${String(wholeHours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:00`;

    const bufferTime = await this.getBufferTime(tx, travelLocationType);

    return { distanceKm: correctedDistance, travelTime, bufferTime };
  }

  async fromLocationId(tx: Tx, locationId: number, travelLocationType: 1 | 2): Promise<DistanceResult> {
    const loc = await (tx as any).dvi_stored_locations.findFirst({
      where: { location_ID: locationId },
    });

    if (!loc) {
      return { distanceKm: 0, travelTime: "00:00:00", bufferTime: "00:00:00" };
    }

    const distance = Number(loc.distance ?? 0);

    // ✅ duration in DB is string like "3 hours 5 mins" / "1 day 1 hour"
    const totalMinutes = parseDurationToMinutes(loc.duration);

    // ✅ IMPORTANT: use *duration* formatter (NO wrap)
    const travelTime = minutesToDurationTime(totalMinutes ?? 0);

    const bufferTime = await this.getBufferTime(tx, travelLocationType);

    return { distanceKm: distance, travelTime, bufferTime };
  }

  async fromSourceAndDestination(
    tx: Tx,
    sourceLocation: string,
    destinationLocation: string,
    travelLocationType: 1 | 2,
    sourceCoords?: { lat: number; lon: number },
    destCoords?: { lat: number; lon: number },
  ): Promise<DistanceResult> {
    const trimmedSource = String(sourceLocation ?? "").trim();
    const trimmedDest = String(destinationLocation ?? "").trim();

    // ✅ PHP PARITY: For hotspots (which provide coordinates), PHP ALWAYS uses 
    // Haversine formula instead of looking up in dvi_stored_locations.
    // This prevents using city-to-city distances for specific hotspots.
    if (sourceCoords && destCoords && 
        (sourceCoords.lat !== 0 || sourceCoords.lon !== 0) && 
        (destCoords.lat !== 0 || destCoords.lon !== 0)) {
      return this.fromCoordinates(
        tx,
        sourceCoords.lat,
        sourceCoords.lon,
        destCoords.lat,
        destCoords.lon,
        travelLocationType,
      );
    }

    const logMsg = `[DistanceHelper] Looking up: "${trimmedSource}" → "${trimmedDest}"\n`;
    
    // Check cache first
    const cacheKey = `${trimmedSource}|${trimmedDest}`;
    let loc = distanceCache.get(cacheKey);

    if (!loc) {
      // Query DB and cache result
      loc = await (tx as any).dvi_stored_locations.findFirst({
        where: {
          deleted: 0,
          source_location: trimmedSource,
          destination_location: trimmedDest,
        },
        orderBy: { location_ID: "desc" },
      });
      
      if (loc) {
        distanceCache.set(cacheKey, loc);
      }
    }

    if (loc) {
      const foundMsg = `[DistanceHelper] Found in DB: ${loc.distance} km\n`;
      const distance = Number(loc.distance ?? 0);

      const totalMinutes = parseDurationToMinutes(loc.duration);

      // ✅ IMPORTANT: use *duration* formatter (NO wrap)
      const travelTime = minutesToDurationTime(totalMinutes ?? 0);

      const bufferTime = await this.getBufferTime(tx, travelLocationType);
      return { distanceKm: distance, travelTime, bufferTime };
    }

    const notFoundMsg = `[DistanceHelper] NOT found in DB, using coordinates fallback\n`;
    
    return { distanceKm: 0, travelTime: "00:00:00", bufferTime: "00:00:00" };
  }

  private async getBufferTime(tx: Tx, travelLocationType: 1 | 2): Promise<string> {
    const gs = await (tx as any).dvi_global_settings.findFirst({
      where: { deleted: 0, status: 1 },
    });

    if (!gs) return "00:00:00";

    const minutes =
      travelLocationType === 1
        ? Number(gs.itinerary_local_buffer_time ?? 0)
        : Number(gs.itinerary_outstation_buffer_time ?? 0);

    // Buffer is a TIME amount; wrapping is fine here.
    return minutesToTime(minutes);
  }
}

export { getOrComputeDistanceCached };

