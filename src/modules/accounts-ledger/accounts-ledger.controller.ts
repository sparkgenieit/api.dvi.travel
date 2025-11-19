// FILE: src/modules/accounts-ledger/accounts-ledger.controller.ts

import { Controller, Get, Query } from '@nestjs/common';
import { ApiQuery, ApiTags } from '@nestjs/swagger';
import { AccountsLedgerService } from './accounts-ledger.service';
import {
  AccountsLedgerComponentType,
  AccountsLedgerQueryDto,
} from './dto/accounts-ledger-query.dto';
import { AccountsLedgerOptionsDto } from './dto/accounts-ledger-options.dto';

@ApiTags('Accounts Ledger')
@Controller('accounts-ledger')
export class AccountsLedgerController {
  constructor(private readonly service: AccountsLedgerService) {}

  @Get()
  @ApiQuery({ name: 'quoteId', required: false })
  @ApiQuery({
    name: 'componentType',
    enum: AccountsLedgerComponentType,
    required: true,
  })
  @ApiQuery({ name: 'fromDate', required: false, description: 'DD/MM/YYYY' })
  @ApiQuery({ name: 'toDate', required: false, description: 'DD/MM/YYYY' })
  @ApiQuery({ name: 'guideId', required: false, type: Number })
  @ApiQuery({ name: 'hotelId', required: false, type: Number })
  @ApiQuery({ name: 'activityId', required: false, type: Number })
  @ApiQuery({ name: 'hotspotId', required: false, type: Number })
  @ApiQuery({ name: 'vendorId', required: false, type: Number })
  @ApiQuery({ name: 'agentId', required: false, type: Number })
  async getLedger(@Query() query: AccountsLedgerQueryDto): Promise<any[]> {
    return this.service.getLedger(query);
  }

  @Get('options')
  @ApiQuery({
    name: 'componentType',
    enum: AccountsLedgerComponentType,
    required: false,
  })
  @ApiQuery({ name: 'fromDate', required: false, description: 'DD/MM/YYYY' })
  @ApiQuery({ name: 'toDate', required: false, description: 'DD/MM/YYYY' })
  async getFilterOptions(
    @Query() query: AccountsLedgerQueryDto,
  ): Promise<AccountsLedgerOptionsDto> {
    return this.service.getFilterOptions(query);
  }
}
