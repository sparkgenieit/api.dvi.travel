import { IsInt, IsOptional, IsString } from 'class-validator';

export class CreateAmenityDto {
  @IsInt()
  hotel_id!: number;

  @IsString()
  amenities_title!: string;

  @IsOptional()
  @IsInt()
  createdby?: number;

  @IsOptional()
  @IsInt()
  status?: number;
}
