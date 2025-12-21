// FILE: src/modules/hotel-category/dto/check-hotel-category-title.dto.ts

import { IsNotEmpty, IsOptional, IsString, MaxLength, IsInt } from 'class-validator';
import { Type } from 'class-transformer';

export class CheckHotelCategoryTitleDto {
  @IsString()
  @IsNotEmpty()
  @MaxLength(65535)
  title: string;

  @IsOptional()
  @Type(() => Number)
  @IsInt()
  excludeId?: number;
}
