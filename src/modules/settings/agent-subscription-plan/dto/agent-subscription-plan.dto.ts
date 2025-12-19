// FILE: src/modules/agent-subscription-plan/dto/agent-subscription-plan.dto.ts

import { ApiProperty, ApiPropertyOptional } from '@nestjs/swagger';
import {
  IsBoolean,
  IsEnum,
  IsNotEmpty,
  IsNumber,
  IsOptional,
  IsString,
  Min,
} from 'class-validator';

export enum SubscriptionTypeEnum {
  Free = 'Free',
  Paid = 'Paid',
}

export class AgentSubscriptionPlanPayloadDto {
  @ApiProperty()
  @IsString()
  @IsNotEmpty()
  planTitle!: string;

  @ApiProperty({ enum: SubscriptionTypeEnum })
  @IsEnum(SubscriptionTypeEnum)
  type!: SubscriptionTypeEnum;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  cost!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  itineraryCount!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  itineraryCost!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  joiningBonus!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  validityDays!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  adminCount!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  staffCount!: number;

  @ApiProperty()
  @IsNumber()
  @Min(0)
  additionalChargePerStaff!: number;

  @ApiPropertyOptional({ description: 'HTML / rich text notes' })
  @IsString()
  @IsOptional()
  notes?: string;
}

export class UpdateAgentSubscriptionPlanDto extends AgentSubscriptionPlanPayloadDto {}

export class UpdateStatusDto {
  @ApiProperty()
  @IsBoolean()
  status!: boolean;
}

export class UpdateRecommendedDto {
  @ApiProperty()
  @IsBoolean()
  recommended!: boolean;
}
