import { IsInt, IsOptional, IsString, MaxLength } from 'class-validator';

export class UpdateActivityDto {
  @IsString() @IsOptional()
  activity_title?: string;

  @IsInt() @IsOptional()
  hotspot_id?: number;

  @IsInt() @IsOptional()
  max_allowed_person_count?: number;

  @IsString() @IsOptional()
  activity_duration?: string;

  @IsString() @IsOptional()
  @MaxLength(65535)
  activity_description?: string;

  @IsInt() @IsOptional()
  updatedby?: number;
}
