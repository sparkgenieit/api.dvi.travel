import { ApiProperty } from '@nestjs/swagger';
import {
  IsString,
  IsInt,
  IsOptional,
  IsArray,
  Min,
} from 'class-validator';

export class ConfirmQuotationDto {
  @ApiProperty({ example: 12 })
  @IsInt()
  @Min(1)
  itinerary_plan_ID!: number;

  @ApiProperty({ example: 3 })
  @IsInt()
  @Min(1)
  agent!: number;

  @ApiProperty({ example: 'Mr' })
  @IsString()
  primary_guest_salutation!: string;

  @ApiProperty({ example: 'John Doe' })
  @IsString()
  primary_guest_name!: string;

  @ApiProperty({ example: '9876543210' })
  @IsString()
  primary_guest_contact_no!: string;

  @ApiProperty({ example: '34' })
  @IsString()
  primary_guest_age!: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  primary_guest_alternative_contact_no?: string;

  @ApiProperty({ example: 'john@example.com', required: false })
  @IsOptional()
  @IsString()
  primary_guest_email_id?: string;

  @ApiProperty({ type: [String], example: [], required: false })
  @IsOptional()
  @IsArray()
  adult_name?: string[];

  @ApiProperty({ type: [String], example: [], required: false })
  @IsOptional()
  @IsArray()
  adult_age?: string[];

  @ApiProperty({ example: '12-12-2025 9:00 AM' })
  @IsString()
  arrival_date_time!: string;

  @ApiProperty({ example: 'Chennai International Airport' })
  @IsString()
  arrival_place!: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  arrival_flight_details?: string;

  @ApiProperty({ example: '19-12-2025 4:00 PM' })
  @IsString()
  departure_date_time!: string;

  @ApiProperty({ example: 'Trivandrum, Domestic Airport' })
  @IsString()
  departure_place!: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  departure_flight_details?: string;

  @ApiProperty({ example: 'old', description: 'old or new' })
  @IsString()
  price_confirmation_type!: string;

  @ApiProperty({ example: 'undefined', required: false })
  @IsOptional()
  @IsString()
  hotel_group_type?: string;
}

export class WalletBalanceResponseDto {
  @ApiProperty({ example: 12834.0 })
  balance!: number;

  @ApiProperty({ example: '₹ 12,834.00' })
  formatted_balance!: string;

  @ApiProperty({ example: true })
  is_sufficient!: boolean;
}

export class CustomerInfoFormResponseDto {
  @ApiProperty({ example: 'DVI20251210' })
  quotation_no!: string;

  @ApiProperty({ example: 'Aalim Khoja' })
  agent_name!: string;

  @ApiProperty({ example: '12,834.00' })
  wallet_balance!: string;

  @ApiProperty({ example: true })
  balance_sufficient!: boolean;
}
