import { ApiProperty } from '@nestjs/swagger';
export class CreateDayDto {
  @ApiProperty() dayNumber: number;
  @ApiProperty({ required: false }) date?: Date;
  @ApiProperty({ required: false }) notes?: string;
}
