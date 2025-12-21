// FILE: src/modules/language/language.module.ts

import { Module } from '@nestjs/common';
import { LanguageController } from './language.controller';
import { LanguageService } from './language.service';
import { PrismaService } from '../../../prisma.service';

@Module({
  controllers: [LanguageController],
  providers: [LanguageService, PrismaService],
  exports: [LanguageService],
})
export class LanguageModule {}
