import { Module } from '@nestjs/common';
import { AccountsExportService } from './accounts-export.service';
import { AccountsExportController } from './accounts-export.controller';
import { PrismaService } from '../../prisma.service';
import { AccountsManagerModule } from '../accounts-manager/accounts-manager.module';
import { AccountsLedgerModule } from '../accounts-ledger/accounts-ledger.module';

@Module({
  imports: [AccountsManagerModule, AccountsLedgerModule],
  controllers: [AccountsExportController],
  providers: [AccountsExportService, PrismaService],
})
export class AccountsExportModule {}
