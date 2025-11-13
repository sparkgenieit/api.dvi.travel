import { ApiProperty, ApiPropertyOptional } from '@nestjs/swagger';

export class CreateItineraryDto {
  @ApiProperty() title: string;
  @ApiPropertyOptional() code?: string;
  @ApiPropertyOptional() travelerName?: string;
  @ApiPropertyOptional() travelerCount?: number;
  @ApiPropertyOptional() agentId?: string;
  @ApiPropertyOptional() vendorId?: string;
  @ApiPropertyOptional() startDate?: Date;
  @ApiPropertyOptional() endDate?: Date;
  @ApiPropertyOptional() notes?: string;
  @ApiPropertyOptional() status?: string;
}
