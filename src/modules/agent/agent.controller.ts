// FILE: src/modules/agent/agent.controller.ts
import {
  Body,
  Controller,
  Delete,
  Get,
  Param,
  ParseIntPipe,
  Post,
  Put,
  Query,
} from '@nestjs/common';
import { AgentService } from './agent.service';
import { ListAgentQueryDto } from './dto/list-agent.dto';
import { CreateAgentDto } from './dto/create-agent.dto';
import { UpdateAgentDto } from './dto/update-agent.dto';

@Controller('agents')
export class AgentController {
  constructor(private readonly service: AgentService) {}

  /**
   * Lightweight list: [{ id, name }]
   * Useful for dropdowns or quick client-side enrichment.
   * Path order matters—keep this ABOVE ':id'.
   */
  @Get('names')
  listNames() {
    return this.service.listNames();
  }
  /** Full list with all fields (existing) */
  @Get('full')
  fullList(@Query() query: ListAgentQueryDto) {
    return this.service.listFull(query);
  }

  /** Paginated/DT list with rich fields (existing) */
  @Get()
  list(@Query() query: ListAgentQueryDto) {
    return this.service.list(query);
  }

  /** Preview / read one */
  @Get(':id')
  preview(@Param('id', ParseIntPipe) id: number) {
    return this.service.getById(id);
  }

  /** Edit prefill (same as preview for now) */
  @Get(':id/edit')
  prefill(@Param('id', ParseIntPipe) id: number) {
    return this.service.getEditPrefill(id);
  }

  /**
   * Subscription history for an agent.
   * Reads rows from dvi_agent_subscribed_plans (or synthesizes “Free / 365 Days”).
   */
  @Get(':id/subscriptions')
  subscriptions(@Param('id', ParseIntPipe) id: number) {
    return this.service.getSubscriptions(id);
  }

  /** Create */
  @Post()
  create(@Body() body: CreateAgentDto) {
    return this.service.create(body);
  }

  /** Update */
  @Put(':id')
  update(@Param('id', ParseIntPipe) id: number, @Body() body: UpdateAgentDto) {
    return this.service.update(id, body);
  }

  /** Soft delete */
  @Delete(':id')
  remove(@Param('id', ParseIntPipe) id: number) {
    return this.service.softDelete(id);
  }
}
