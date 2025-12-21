// FILE: src/modules/agent-subscription-plan/agent-subscription-plan.controller.ts

import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Patch,
  Post,
  Put,
  Req,
} from '@nestjs/common';
import { ApiTags } from '@nestjs/swagger';
import { AgentSubscriptionPlanService } from './agent-subscription-plan.service';
import {
  AgentSubscriptionPlanPayloadDto,
  UpdateRecommendedDto,
  UpdateStatusDto,
} from './dto/agent-subscription-plan.dto';

@ApiTags('agent-subscription-plans')
@Controller('agent-subscription-plans')
export class AgentSubscriptionPlanController {
  constructor(
    private readonly agentSubscriptionPlanService: AgentSubscriptionPlanService,
  ) {}

  @Get()
  async list() {
    return this.agentSubscriptionPlanService.findAll();
  }

  @Get(':id')
  async getOne(@Param('id', ParseIntPipe) id: number) {
    return this.agentSubscriptionPlanService.findOne(id);
  }

  @Post()
  async create(
    @Body() payload: AgentSubscriptionPlanPayloadDto,
    @Req() req: any,
  ) {
    const userId = req?.user?.id ?? 0;
    return this.agentSubscriptionPlanService.create(payload, userId);
  }

  @Put(':id')
  async update(
    @Param('id', ParseIntPipe) id: number,
    @Body() payload: AgentSubscriptionPlanPayloadDto,
    @Req() req: any,
  ) {
    const userId = req?.user?.id ?? 0;
    return this.agentSubscriptionPlanService.update(id, payload, userId);
  }

  @Delete(':id')
  async remove(@Param('id', ParseIntPipe) id: number, @Req() req: any) {
    const userId = req?.user?.id ?? 0;
    return this.agentSubscriptionPlanService.remove(id, userId);
  }

  @Patch(':id/status')
  async updateStatus(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: UpdateStatusDto,
    @Req() req: any,
  ) {
    const userId = req?.user?.id ?? 0;
    return this.agentSubscriptionPlanService.updateStatus(
      id,
      body.status,
      userId,
    );
  }

  @Patch(':id/recommended')
  async updateRecommended(
    @Param('id', ParseIntPipe) id: number,
    @Body() body: UpdateRecommendedDto,
    @Req() req: any,
  ) {
    const userId = req?.user?.id ?? 0;
    return this.agentSubscriptionPlanService.updateRecommended(
      id,
      body.recommended,
      userId,
    );
  }
}
