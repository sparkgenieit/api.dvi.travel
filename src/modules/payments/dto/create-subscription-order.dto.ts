import { IsNumber, IsOptional, IsString } from 'class-validator';

export class CreateSubscriptionOrderDto {
  @IsNumber()
  planId!: number;

  @IsOptional()
  @IsNumber()
  agentSubscribedPlanId?: number;
}
