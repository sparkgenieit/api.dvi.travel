// FILE: src/modules/hotspots/dto/hotspot-list.query.dto.ts

import { Type } from 'class-transformer';
import { IsBooleanString, IsInt, IsOptional, IsString, Max, Min } from 'class-validator';

export class HotspotListQueryDto {
  /** Free-text search on hotspot name/address */
  @IsOptional()
  @IsString()
  q?: string;

  /** Filter by city/state/country if present in your schema */
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  cityId?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  stateId?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  countryId?: number;

  /** 0/1/undefined — if your schema has a status column */
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  status?: number;

  /** Include first image from gallery table */
  @IsOptional()
  @IsBooleanString()
  includeImages?: string;

  /** Pagination */
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  page?: number = 1;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  @Max(200)
  size?: number = 50;

  /** Sort by name (asc/desc) if present */
  @IsOptional()
  @IsString()
  sort?: 'name:asc' | 'name:desc';
}