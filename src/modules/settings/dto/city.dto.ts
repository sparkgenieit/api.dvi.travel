// FILE: src/modules/settings/dto/city.dto.ts

import { IsString, IsNumber, IsOptional, IsIn } from 'class-validator';
import { ApiProperty, ApiPropertyOptional } from '@nestjs/swagger';

export class CreateCityDto {
  @ApiProperty()
  @IsString()
  city_name!: string;

  @ApiProperty()
  @IsNumber()
  state_id!: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsIn([0, 1])
  status?: number;
}

export class UpdateCityDto {
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  city_name?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  state_id?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsIn([0, 1])
  status?: number;
}
