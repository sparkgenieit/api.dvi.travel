// FILE: src/modules/hotspots/dto/hotspot-list.response.dto.ts

export interface HotspotDto {
  id: number;
  name: string;
  /** Optional textual address if available in DB, otherwise null */
  address: string | null;

  /** Location FKs (nullable because not all rows have them) */
  cityId: number | null;
  stateId: number | null;
  countryId: number | null;

  /** Geo (nullable when not stored) */
  latitude: number | null;
  longitude: number | null;

  /** Row status flag if present on table (nullable) */
  status: number | null;

  /** First image URL resolved from hotspot gallery tables (nullable) */
  photoUrl: string | null;
}

export interface HotspotListResponseDto {
  total: number;
  page: number;
  size: number;
  items: HotspotDto[];
}