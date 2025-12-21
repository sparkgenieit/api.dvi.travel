import { Controller, Get, Post, Body, Query, Delete, Param } from '@nestjs/common';
import { IncidentalExpensesService } from './incidental-expenses.service';
import { ApiTags, ApiOperation } from '@nestjs/swagger';

@ApiTags('Incidental Expenses')
@Controller('incidental-expenses')
export class IncidentalExpensesController {
  constructor(private readonly incidentalExpensesService: IncidentalExpensesService) {}

  @Get('available-components')
  @ApiOperation({ summary: 'Get available components for incidental expenses' })
  getAvailableComponents(@Query('itineraryPlanId') itineraryPlanId: string) {
    return this.incidentalExpensesService.getAvailableComponents(Number(itineraryPlanId));
  }

  @Get('available-margin')
  @ApiOperation({ summary: 'Get available margin for a component' })
  getAvailableMargin(
    @Query('itineraryPlanId') itineraryPlanId: string,
    @Query('componentType') componentType: string,
    @Query('componentId') componentId?: string,
  ) {
    return this.incidentalExpensesService.getAvailableMargin(
      Number(itineraryPlanId),
      Number(componentType),
      componentId ? Number(componentId) : undefined,
    );
  }

  @Post()
  @ApiOperation({ summary: 'Add incidental expense' })
  addIncidentalExpense(@Body() data: {
    itineraryPlanId: number;
    componentType: number;
    componentId: number;
    amount: number;
    reason: string;
    createdBy: number;
  }) {
    return this.incidentalExpensesService.addIncidentalExpense(data);
  }

  @Get('history')
  @ApiOperation({ summary: 'Get incidental expenses history' })
  getHistory(@Query('itineraryPlanId') itineraryPlanId: string) {
    return this.incidentalExpensesService.getIncidentalHistory(Number(itineraryPlanId));
  }

  @Delete('history/:id')
  @ApiOperation({ summary: 'Delete incidental expense history' })
  deleteHistory(@Param('id') id: string) {
    return this.incidentalExpensesService.deleteIncidentalHistory(Number(id));
  }
}
