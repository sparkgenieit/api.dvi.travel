// FILE: src/modules/drivers/dto/update-driver-status.dto.ts
import { ApiProperty } from '@nestjs/swagger';
import { IsBoolean } from 'class-validator';

export class UpdateDriverStatusDto {
  @ApiProperty()
  @IsBoolean()
  status!: boolean;
}
