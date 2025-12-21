import { Controller, Get, Query, Res, UseGuards } from '@nestjs/common';
import { Response } from 'express';
import { AccountsExportService } from './accounts-export.service';
import { AccountsManagerQueryDto } from '../accounts-manager/dto/accounts-manager-query.dto';
import { AccountsLedgerQueryDto } from '../accounts-ledger/dto/accounts-ledger-query.dto';
import { ApiBearerAuth, ApiOperation, ApiTags } from '@nestjs/swagger';
import { JwtAuthGuard } from '../../auth/jwt-auth.guard';

@ApiTags('Accounts Export')
@ApiBearerAuth()
@Controller('accounts-export')
export class AccountsExportController {
  constructor(private readonly svc: AccountsExportService) {}

  @UseGuards(JwtAuthGuard)
  @Get('manager/excel')
  @ApiOperation({ summary: 'Export Accounts Manager to Excel' })
  async exportManagerExcel(@Query() query: AccountsManagerQueryDto, @Res() res: Response) {
    return this.svc.exportAccountsManagerExcel(query, res);
  }

  @UseGuards(JwtAuthGuard)
  @Get('ledger/excel')
  @ApiOperation({ summary: 'Export Accounts Ledger to Excel' })
  async exportLedgerExcel(@Query() query: AccountsLedgerQueryDto, @Res() res: Response) {
    return this.svc.exportAccountsLedgerExcel(query, res);
  }
}
