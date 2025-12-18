import { IsInt, IsOptional, Min } from "class-validator";
import { Type } from "class-transformer";

export class ListCitiesQueryDto {
  @IsOptional()
  @Type(() => Number)
  @IsInt()
  @Min(1)
  countryId?: number; // default 101 like PHP
}
