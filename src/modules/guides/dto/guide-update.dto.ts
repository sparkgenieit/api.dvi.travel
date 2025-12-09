
// FILE: src/modules/guides/dto/guide-update.dto.ts
import { PartialType } from '@nestjs/mapped-types';
import { GuideCreateDto, GuidePricebookDto, GuideReviewCreateDto } from './guide-create.dto';
import { IsOptional, ValidateNested } from 'class-validator';
import { Type } from 'class-transformer';

export class GuideUpdateDto extends PartialType(GuideCreateDto) {
  @IsOptional() @ValidateNested() @Type(() => GuidePricebookDto)
  pricebook?: GuidePricebookDto;

  @IsOptional() @ValidateNested({ each: true }) @Type(() => GuideReviewCreateDto)
  reviews?: GuideReviewCreateDto[];
}
