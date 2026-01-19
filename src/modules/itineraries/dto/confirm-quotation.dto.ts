import { ApiProperty } from '@nestjs/swagger';
import {
  IsString,
  IsInt,
  IsOptional,
  IsArray,
  Min,
  IsNumber,
  IsBoolean,
} from 'class-validator';

export class HotelPassengerDto {
  @ApiProperty({ example: 'Mr' })
  @IsString()
  title!: string;

  @ApiProperty({ example: 'John' })
  @IsString()
  firstName!: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  middleName?: string;

  @ApiProperty({ example: 'Doe' })
  @IsString()
  lastName!: string;

  @ApiProperty({ example: 'john@example.com', required: false })
  @IsOptional()
  @IsString()
  email?: string;

  @ApiProperty({ example: 1, description: '1=Adult, 2=Child' })
  @IsInt()
  paxType!: number;

  @ApiProperty({ example: true })
  @IsBoolean()
  leadPassenger!: boolean;

  @ApiProperty({ example: 30 })
  @IsInt()
  age!: number;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  passportNo?: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  passportIssueDate?: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  passportExpDate?: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  phoneNo?: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  gstNumber?: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  gstCompanyName?: string;

  @ApiProperty({ example: '', required: false })
  @IsOptional()
  @IsString()
  pan?: string;
}

export class HotelSelectionDto {
  @ApiProperty({ example: 'tbo', description: 'Hotel provider: tbo, ResAvenue, etc.' })
  @IsString()
  provider!: string;

  @ApiProperty({ example: 1 })
  @IsInt()
  routeId!: number;

  @ApiProperty({ example: '1035259' })
  @IsString()
  hotelCode!: string;

  @ApiProperty({ example: '1035259!TB!2!TB!27fe40ea-75db-11f0-8023-825b5693933e!TB!AFF!' })
  @IsString()
  bookingCode!: string;

  @ApiProperty({ example: 'Double Bed' })
  @IsString()
  roomType!: string;

  @ApiProperty({ example: '2025-12-12' })
  @IsString()
  checkInDate!: string;

  @ApiProperty({ example: '2025-12-13' })
  @IsString()
  checkOutDate!: string;

  @ApiProperty({ example: 1 })
  @IsInt()
  numberOfRooms!: number;

  @ApiProperty({ example: 'IN' })
  @IsString()
  guestNationality!: string;

  @ApiProperty({ example: 5000 })
  @IsNumber()
  netAmount!: number;

  @ApiProperty({ type: [HotelPassengerDto] })
  @IsArray()
  passengers!: HotelPassengerDto[];
}

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

  @ApiProperty({ type: [String], example: [], required: false })
  @IsOptional()
  @IsArray()
  child_name?: string[];

  @ApiProperty({ type: [String], example: [], required: false })
  @IsOptional()
  @IsArray()
  child_age?: string[];

  @ApiProperty({ type: [String], example: [], required: false })
  @IsOptional()
  @IsArray()
  infant_name?: string[];

  @ApiProperty({ type: [String], example: [], required: false })
  @IsOptional()
  @IsArray()
  infant_age?: string[];

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

  @ApiProperty({
    type: [HotelSelectionDto],
    description: 'Selected hotels to be booked during confirmation (multi-provider)',
    required: false,
  })
  @IsOptional()
  @IsArray()
  hotel_bookings?: HotelSelectionDto[];

  @ApiProperty({ example: '192.168.1.1', required: false })
  @IsOptional()
  @IsString()
  endUserIp?: string;
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
