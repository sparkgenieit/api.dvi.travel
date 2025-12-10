// REPLACE-WHOLE-FILE
// FILE: src/itineraries/utils/distance-utils.ts

type TravelType = "road" | "city" | "walk";

const DEFAULT_SPEED_KMPH: Record<TravelType, number> = {
  road: 50,
  city: 25,
  walk: 5,
};

// Haversine (km)
export function haversineKm(lat1: number, lon1: number, lat2: number, lon2: number) {
  const R = 6371;
  const dLat = ((lat2 - lat1) * Math.PI) / 180;
  const dLon = ((lon2 - lon1) * Math.PI) / 180;
  const a =
    Math.sin(dLat / 2) ** 2 +
    Math.cos((lat1 * Math.PI) / 180) *
      Math.cos((lat2 * Math.PI) / 180) *
      Math.sin(dLon / 2) ** 2;
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return +(R * c).toFixed(2);
}

export function typeByDistanceKm(km: number): TravelType {
  if (km <= 1.0) return "walk";
  if (km <= 20.0) return "city";
  return "road";
}

export function durationHMSForKm(km: number, speedKmPh: number) {
  const hours = km / Math.max(1e-6, speedKmPh);
  const sec = Math.round(hours * 3600);
  const h = Math.floor(sec / 3600);
  const m = Math.floor((sec % 3600) / 60);
  const s = sec % 60;
  const z = (n: number) => String(n).padStart(2, "0");
  return `${z(h)}:${z(m)}:${z(s)}`;
}

export function speedForType(tt: TravelType, dbSpeed?: number) {
  return dbSpeed && dbSpeed > 0 ? dbSpeed : DEFAULT_SPEED_KMPH[tt];
}
