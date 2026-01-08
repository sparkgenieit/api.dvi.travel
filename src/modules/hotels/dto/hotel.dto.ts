import {
  IsString,
  IsNotEmpty,
  IsDateString,
  IsNumber,
  Min,
  IsArray,
  IsOptional,
  IsObject,
  ValidateNested,
  IsEmail,
  IsMobilePhone,
} from 'class-validator';
import { Type } from 'class-transformer';

export class HotelSearchDTO {
  @IsString()
  @IsNotEmpty()
  cityCode: string;

  @IsDateString()
  @IsNotEmpty()
  checkInDate: string;

  @IsDateString()
  @IsNotEmpty()
  checkOutDate: string;

  @IsNumber()
  @Min(1)
  @IsNotEmpty()
  roomCount: number;

  @IsNumber()
  @Min(1)
  @IsNotEmpty()
  guestCount: number;

  @IsArray()
  @IsOptional()
  providers?: string[];

  @IsObject()
  @IsOptional()
  preferences?: {
    minRating?: number;
    maxPrice?: number;
    facilities?: string[];
  };
}

export class GuestDetailsDTO {
  @IsString()
  @IsNotEmpty()
  firstName: string;

  @IsString()
  @IsNotEmpty()
  lastName: string;

  @IsEmail()
  @IsNotEmpty()
  email: string;

  @IsMobilePhone()
  @IsNotEmpty()
  phone: string;
}

export class RoomSelectionDTO {
  @IsString()
  @IsNotEmpty()
  roomCode: string;

  @IsNumber()
  @Min(1)
  @IsNotEmpty()
  quantity: number;

  @IsNumber()
  @Min(1)
  @IsNotEmpty()
  guestCount: number;
}

export class HotelConfirmationDTO {
  @IsNumber()
  @IsNotEmpty()
  itineraryPlanId: number;

  @IsString()
  @IsNotEmpty()
  searchReference: string;

  @IsString()
  @IsNotEmpty()
  hotelCode: string;

  @IsDateString()
  @IsNotEmpty()
  checkInDate: string;

  @IsDateString()
  @IsNotEmpty()
  checkOutDate: string;

  @IsNumber()
  @Min(1)
  @IsNotEmpty()
  roomCount: number;

  @IsArray()
  @ValidateNested({ each: true })
  @Type(() => GuestDetailsDTO)
  @IsNotEmpty()
  guests: GuestDetailsDTO[];

  @IsArray()
  @ValidateNested({ each: true })
  @Type(() => RoomSelectionDTO)
  @IsNotEmpty()
  rooms: RoomSelectionDTO[];

  @IsString()
  @IsNotEmpty()
  contactName: string;

  @IsEmail()
  @IsNotEmpty()
  contactEmail: string;

  @IsMobilePhone()
  @IsNotEmpty()
  contactPhone: string;
}

export class HotelPaymentDTO {
  @IsString()
  @IsNotEmpty()
  confirmationReference: string;

  @IsString()
  @IsNotEmpty()
  paymentMethod: string; // 'razorpay', 'netbanking', etc.
}

export class CancellationDTO {
  @IsString()
  @IsNotEmpty()
  reason: string;
}
