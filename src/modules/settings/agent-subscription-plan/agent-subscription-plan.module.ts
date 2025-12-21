// FILE: src/modules/agent-subscription-plan/agent-subscription-plan.module.ts

import { Module } from '@nestjs/common';
import { PrismaService } from '../../../prisma.service';
import { AgentSubscriptionPlanController } from './agent-subscription-plan.controller';
import { AgentSubscriptionPlanService } from './agent-subscription-plan.service';

@Module({
  controllers: [AgentSubscriptionPlanController],
  providers: [PrismaService, AgentSubscriptionPlanService],
  exports: [AgentSubscriptionPlanService],
})
export class AgentSubscriptionPlanModule {}
