// FILE: src/modules/hotspots/dto/hotspot-list.query.dto.ts

import { IsInt, IsOptional, IsString, Min } from 'class-validator';
import { Type } from 'class-transformer';

export class HotspotListQueryDto {
  @IsOptional()
  @IsString()
  q?: string;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  page?: number;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  size?: number;
}