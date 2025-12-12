import { Module } from '@nestjs/common';
import { AgentController } from './agent.controller';
import { AgentService } from './agent.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [AgentController],
  providers: [AgentService, PrismaService],
  exports: [AgentService],
})
export class AgentModule {}
