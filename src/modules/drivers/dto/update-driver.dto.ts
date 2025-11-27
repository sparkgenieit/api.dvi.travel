// FILE: src/modules/drivers/dto/update-driver.dto.ts

import { PartialType } from '@nestjs/swagger';
import {
  CreateDriverBasicDto,
  DriverCostDto,
} from './create-driver.dto';
import { ApiProperty } from '@nestjs/swagger';
import { IsOptional } from 'class-validator';

export class UpdateDriverBasicDto extends PartialType(
  CreateDriverBasicDto,
) {}

export class UpdateDriverCostDto extends PartialType(DriverCostDto) {}

export class UpdateDriverDto {
  @ApiProperty({ type: UpdateDriverBasicDto, required: false })
  @IsOptional()
  basic?: UpdateDriverBasicDto;

  @ApiProperty({ type: UpdateDriverCostDto, required: false })
  @IsOptional()
  cost?: UpdateDriverCostDto;
}
