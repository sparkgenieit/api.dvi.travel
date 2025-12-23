import { IsNumber, IsOptional, IsBoolean, Min, Max } from 'class-validator';
import { ApiProperty } from '@nestjs/swagger';

export class CancelItineraryDto {
  @ApiProperty({ example: 33960 })
  @IsNumber()
  itinerary_plan_ID!: number;

  @ApiProperty({ example: 10 })
  @IsNumber()
  @Min(0)
  @Max(100)
  cancellation_percentage!: number;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsBoolean()
  cancel_guide?: boolean;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsBoolean()
  cancel_hotspot?: boolean;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsBoolean()
  cancel_activity?: boolean;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsBoolean()
  cancel_hotel?: boolean;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsBoolean()
  cancel_vehicle?: boolean;
}
