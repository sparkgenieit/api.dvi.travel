import { Module } from '@nestjs/common';
import { ExportPricebookController } from './export-pricebook.controller';
import { ExportPricebookService } from './export-pricebook.service';
import { PrismaService } from '../../prisma.service';

@Module({
  controllers: [ExportPricebookController],
  providers: [ExportPricebookService, PrismaService],
  exports: [ExportPricebookService],
})
export class ExportPricebookModule {}
