import { Type } from 'class-transformer';
import { IsInt, IsOptional, IsString, Min } from 'class-validator';

export class ListAgentQueryDto {
  @IsOptional() @Type(() => Number) @IsInt() @Min(0)
  page?: number = 0;

  @IsOptional() @Type(() => Number) @IsInt() @Min(1)
  limit?: number = 10;

  @IsOptional() @IsString()
  q?: string;

  @IsOptional() @Type(() => Number) @IsInt() @Min(0)
  start?: number;

  @IsOptional() @Type(() => Number) @IsInt() @Min(1)
  length?: number;

  @IsOptional() @IsString()
  draw?: string;

  @IsOptional() @Type(() => Number) @IsInt() @Min(0)
  travelExpertId?: number;
}
