import { Module } from '@nestjs/common';
import { IncidentalExpensesService } from './incidental-expenses.service';
import { IncidentalExpensesController } from './incidental-expenses.controller';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [IncidentalExpensesController],
  providers: [IncidentalExpensesService, PrismaService],
})
export class IncidentalExpensesModule {}
