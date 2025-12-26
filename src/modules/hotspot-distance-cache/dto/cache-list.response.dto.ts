export class CacheListResponseDto {
  total: number;
  page: number;
  size: number;
  pages: number;
  rows: CacheListRow[];
}

export class CacheListRow {
  id: number;
  fromHotspotId: number;
  fromHotspotName: string | null;
  toHotspotId: number;
  toHotspotName: string | null;
  travelLocationType: number;
  haversineKm: number;
  correctionFactor: number;
  distanceKm: number;
  speedKmph: number;
  travelTime: string;
  method: string;
  createdAt: string;
  updatedAt: string;
}
