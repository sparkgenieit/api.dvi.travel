import { ApiProperty } from '@nestjs/swagger';
import { IsNumber, IsString, IsOptional } from 'class-validator';

export class CreateOrderDto {
  @ApiProperty({ example: 1000, description: 'Amount in INR (not paise)' })
  @IsNumber()
  amount!: number;

  @ApiProperty({ example: 'wallet_topup', enum: ['wallet_topup', 'subscription_renewal'] })
  @IsString()
  type!: 'wallet_topup' | 'subscription_renewal';

  @ApiProperty({ required: false })
  @IsOptional()
  @IsNumber()
  planId?: number;
}
