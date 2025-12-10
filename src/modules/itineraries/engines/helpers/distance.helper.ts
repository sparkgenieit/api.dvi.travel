// FILE: src/modules/itineraries/engines/helpers/distance.helper.ts

import { Prisma } from "@prisma/client";
import { minutesToTime } from "./time.helper";

type Tx = Prisma.TransactionClient;

export interface DistanceResult {
  distanceKm: number;      // e.g. 8.42
  travelTime: string;      // HH:MM:SS
  bufferTime: string;      // HH:MM:SS
}

/**
 * Wraps distance + duration + buffer calculation using:
 *  - Haversine formula with lat/long coordinates (matching PHP)
 *  - dvi_stored_locations.distance / duration (fallback)
 *  - dvi_global_settings.itinerary_* fields
 */
export class DistanceHelper {
  /**
   * Calculate distance and duration using Haversine formula (matches PHP).
   */
  async fromCoordinates(
    tx: Tx,
    startLat: number,
    startLon: number,
    endLat: number,
    endLon: number,
    travelLocationType: 1 | 2,
  ): Promise<DistanceResult> {
    console.log(
      `[Haversine] Calculating distance: (${startLat},${startLon}) -> (${endLat},${endLon})`,
    );
    
    // Haversine formula - matches PHP's calculateDistanceAndDuration
    const earthRadius = 6371; // km
    
    const startLatRad = (startLat * Math.PI) / 180;
    const startLonRad = (startLon * Math.PI) / 180;
    const endLatRad = (endLat * Math.PI) / 180;
    const endLonRad = (endLon * Math.PI) / 180;
    
    const latDiff = endLatRad - startLatRad;
    const lonDiff = endLonRad - startLonRad;
    
    const a = Math.pow(Math.sin(latDiff / 2), 2) +
      Math.cos(startLatRad) * Math.cos(endLatRad) *
      Math.pow(Math.sin(lonDiff / 2), 2);
    
    const distance = 2 * earthRadius * Math.asin(Math.sqrt(a));
    
    // Apply correction factor (matches PHP)
    const correctionFactor = 1.5;
    const correctedDistance = distance * correctionFactor;
    
    console.log(`[Haversine] Raw distance: ${distance.toFixed(2)}km, Corrected: ${correctedDistance.toFixed(2)}km`);
    
    // Get speed limit from global settings
    const gs = await (tx as any).dvi_global_settings.findFirst({
      where: { deleted: 0, status: 1 },
    });
    
    const avgSpeedKmPerHr = travelLocationType === 1
      ? Number(gs?.itinerary_local_speed_limit ?? 40)
      : Number(gs?.itinerary_outstation_speed_limit ?? 60);
    
    console.log(
      `[Haversine] Global settings speed: local=${gs?.itinerary_local_speed_limit}, outstation=${gs?.itinerary_outstation_speed_limit}, using=${avgSpeedKmPerHr}km/h for type=${travelLocationType}`,
    );
    
    // Calculate duration
    const durationHours = correctedDistance / avgSpeedKmPerHr;
    const hours = Math.floor(durationHours);
    const minutes = Math.round((durationHours - hours) * 60);
    
    // Convert to HH:MM:SS
    const travelTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;
    
    const bufferTime = await this.getBufferTime(tx, travelLocationType);
    
    console.log(
      `[Haversine] Speed: ${avgSpeedKmPerHr}km/h, Duration: ${travelTime}, Buffer: ${bufferTime}`,
    );
    
    return {
      distanceKm: correctedDistance,
      travelTime,
      bufferTime,
    };
  }
  /**
   * Use stored_locations row directly (when you already know location_ID).
   */
  async fromLocationId(
    tx: Tx,
    locationId: number,
    travelLocationType: 1 | 2, // 1=local, 2=outstation
  ): Promise<DistanceResult> {
    const loc = await (tx as any).dvi_stored_locations.findFirst({
      where: { location_ID: locationId },
    });

    if (!loc) {
      // Fallback: no row, zero distance/time.
      return {
        distanceKm: 0,
        travelTime: "00:00:00",
        bufferTime: "00:00:00",
      };
    }

    const distance = Number(loc.distance ?? 0);
    // loc.duration might be in minutes or "HH:MM:SS" – adapt if needed.
    const rawDuration = Number(loc.duration ?? 0);
    const travelTime = minutesToTime(rawDuration); // adjust if your duration is already HH:MM:SS

    const bufferTime = await this.getBufferTime(tx, travelLocationType);

    return {
      distanceKm: distance,
      travelTime,
      bufferTime,
    };
  }

  /**
   * When you have source/dest names (cities/locations) instead of location_ID.
   * First tries stored locations, then falls back to coordinate-based calculation.
   */
  async fromSourceAndDestination(
    tx: Tx,
    sourceLocation: string,
    destinationLocation: string,
    travelLocationType: 1 | 2,
    sourceCoords?: { lat: number; lon: number },
    destCoords?: { lat: number; lon: number },
  ): Promise<DistanceResult> {
    const loc = await (tx as any).dvi_stored_locations.findFirst({
      where: {
        source_location: sourceLocation,
        destination_location: destinationLocation,
      },
    });

    if (loc) {
      const distance = Number(loc.distance ?? 0);
      const rawDuration = Number(loc.duration ?? 0);
      const travelTime = minutesToTime(rawDuration);
      const bufferTime = await this.getBufferTime(tx, travelLocationType);

      return {
        distanceKm: distance,
        travelTime,
        bufferTime,
      };
    }
    
    // Fallback to coordinate-based calculation if available
    if (sourceCoords && destCoords) {
      console.log(
        `[DistanceHelper] Using Haversine: source=(${sourceCoords.lat},${sourceCoords.lon}), dest=(${destCoords.lat},${destCoords.lon})`,
      );
      return this.fromCoordinates(
        tx,
        sourceCoords.lat,
        sourceCoords.lon,
        destCoords.lat,
        destCoords.lon,
        travelLocationType,
      );
    }
    
    console.log(
      `[DistanceHelper] No data: sourceCoords=${!!sourceCoords}, destCoords=${!!destCoords}`,
    );

    // No data available - return zeros
    return {
      distanceKm: 0,
      travelTime: "00:00:00",
      bufferTime: "00:00:00",
    };
  }

  private async getBufferTime(
    tx: Tx,
    travelLocationType: 1 | 2,
  ): Promise<string> {
    // TODO: adjust field names to match your dvi_global_settings model.
    const gs = await (tx as any).dvi_global_settings.findFirst({
      where: { deleted: 0, status: 1 },
    });

    if (!gs) return "00:00:00";

    const minutes =
      travelLocationType === 1
        ? Number(gs.itinerary_local_buffer_time ?? 0)
        : Number(gs.itinerary_outstation_buffer_time ?? 0);

    return minutesToTime(minutes);
  }
}
