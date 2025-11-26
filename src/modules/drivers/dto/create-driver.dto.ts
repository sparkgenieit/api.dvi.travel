// FILE: src/modules/drivers/dto/create-driver.dto.ts

import { ApiProperty } from '@nestjs/swagger';
import {
  IsDateString,
  IsEmail,
  IsNotEmpty,
  IsNumber,
  IsOptional,
  IsString,
} from 'class-validator';

export class CreateDriverBasicDto {
  @ApiProperty()
  @IsNumber()
  vendorId: number;

  @ApiProperty()
  @IsNumber()
  vehicleTypeId: number;

  @ApiProperty()
  @IsString()
  @IsNotEmpty()
  driverName: string;

  @ApiProperty()
  @IsString()
  @IsNotEmpty()
  primaryMobileNumber: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  alternateMobileNumber?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  whatsappMobileNumber?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsEmail()
  email?: string;

  @ApiProperty()
  @IsString()
  @IsNotEmpty()
  licenseNumber: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsDateString()
  licenseIssueDate?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsDateString()
  licenseExpiryDate?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  bloodGroup?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  gender?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  aadharNumber?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  panNumber?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  voterIdNumber?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  profileImageUrl?: string;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsString()
  address?: string;
}

export class DriverCostDto {
  @ApiProperty()
  @IsNumber()
  driverSalary: number;

  @ApiProperty()
  @IsNumber()
  foodCost: number;

  @ApiProperty()
  @IsNumber()
  accommodationCost: number;

  @ApiProperty()
  @IsNumber()
  bhattaCost: number;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsNumber()
  earlyMorningCharges?: number;

  @ApiProperty({ required: false })
  @IsOptional()
  @IsNumber()
  eveningCharges?: number;
}

export class CreateDriverDto {
  @ApiProperty({ type: CreateDriverBasicDto })
  basic: CreateDriverBasicDto;

  @ApiProperty({ type: DriverCostDto, required: false })
  @IsOptional()
  cost?: DriverCostDto;
}
