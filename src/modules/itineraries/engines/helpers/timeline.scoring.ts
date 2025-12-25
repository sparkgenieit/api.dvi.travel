// REPLACE-WHOLE-FILE
// FILE: src/modules/itineraries/engines/helpers/timeline.scoring.ts

import { SelectedHotspot } from "./timeline.hotspot-selector";
import { HotspotLite } from "./timeline.prefetch";
import { DistanceHelper } from "./distance.helper";
import { TimelineLogger } from "./timeline.logger";

/**
 * Fix #2: Priority > CityOrder
 *
 * IMPORTANT:
 * - SelectedHotspot typically contains only hotspot_ID + metadata needed for selection.
 * - Do NOT assume SelectedHotspot has hotspot_priority/city_order.
 * - Derive Priority and CityOrder from HotspotLite (hotspotMap) or fallback defaults.
 *
 * Score = (Priority * 10000) + (CityOrder * 100) + Distance
 * Lower score is better.
 */
export function computeGreedyScore(
  sh: SelectedHotspot,
  currentCoords: { lat: number; lon: number } | undefined,
  hotspotMap: Map<number, HotspotLite>,
): number {
  const hs = hotspotMap.get(sh.hotspot_ID);

  // Distance (fallback 0 if no coords)
  let distance = 0;
  if (currentCoords && hs?.lat != null && hs?.lon != null) {
    const dh = new DistanceHelper();
    distance = dh.calculateHaversine(
      currentCoords.lat,
      currentCoords.lon,
      Number(hs.lat),
      Number(hs.lon),
    );
  }

  // Priority: take from hotspot record if exists; else default 9999
  // (Different DB schemas may use hotspot_priority or priority — handle both)
  const priorityRaw: any =
    (hs as any)?.hotspot_priority ??
    (hs as any)?.priority ??
    0;
  let priority = Number.isFinite(Number(priorityRaw)) ? Number(priorityRaw) : 0;
  if (priority === 0) priority = 9999; // PHP parity: 0 means no priority -> last

  // City order: many implementations store this on selected items;
  // if not available, derive it from selected hotspot (if selector added it),
  // otherwise default to 1 (Source).
  const cityOrderRaw: any = (sh as any)?.city_order ?? (sh as any)?.cityOrder ?? 1;
  const cityOrder = Number.isFinite(Number(cityOrderRaw)) ? Number(cityOrderRaw) : 1;

  const score = cityOrder * 10000 + priority * 100 + distance;

  TimelineLogger.log(
    `[SCORING] HS ${hs?.name ?? "Unknown"} (ID: ${sh.hotspot_ID}): ` +
      `CityOrder=${cityOrder}, Priority=${priority}, Dist=${distance.toFixed(2)} => Score=${score.toFixed(2)}`,
  );

  return score;
}
