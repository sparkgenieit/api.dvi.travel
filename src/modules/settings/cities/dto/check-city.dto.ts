import { IsInt, IsOptional, IsString, Min } from "class-validator";
import { Type } from "class-transformer";

export class CheckCityDuplicateDto {
  @IsString()
  city_name!: string;

  @Type(() => Number)
  @IsInt()
  @Min(1)
  state_id!: number;

  @IsOptional()
  @IsString()
  old_city_name?: string;
}
