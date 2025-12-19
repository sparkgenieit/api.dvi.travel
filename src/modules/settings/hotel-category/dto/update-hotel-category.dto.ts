// FILE: src/modules/hotel-category/dto/update-hotel-category.dto.ts

import { PartialType } from '@nestjs/mapped-types';
import { CreateHotelCategoryDto } from './create-hotel-category.dto';

export class UpdateHotelCategoryDto extends PartialType(CreateHotelCategoryDto) {}
