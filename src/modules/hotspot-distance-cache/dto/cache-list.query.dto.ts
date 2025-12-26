export class CacheListQueryDto {
  page?: number = 1;
  size?: number = 50;
  search?: string = '';
  sortBy?: string = 'createdAt';
  sortOrder?: 'asc' | 'desc' = 'desc';
  fromHotspotId?: number;
  toHotspotId?: number;
  travelLocationType?: number;
}
