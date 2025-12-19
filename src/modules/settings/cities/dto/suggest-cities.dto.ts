import { IsInt, IsNotEmpty, IsString, Min } from "class-validator";
import { Type } from "class-transformer";

export class SuggestCitiesDto {
  @Type(() => Number)
  @IsInt()
  @Min(1)
  state_id!: number;

  @IsNotEmpty()
  @IsString()
  term!: string;
}
