// FILE: src/modules/settings/dto/hotel-category.dto.ts

import { IsString, IsNumber, IsOptional, IsIn } from 'class-validator';
import { ApiProperty, ApiPropertyOptional } from '@nestjs/swagger';

export class CreateHotelCategoryDto {
  @ApiProperty()
  @IsNumber()
  hotel_category!: number;

  @ApiProperty()
  @IsString()
  category_title!: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsIn([0, 1])
  status?: number;
}

export class UpdateHotelCategoryDto {
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  hotel_category?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  category_title?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsIn([0, 1])
  status?: number;
}
