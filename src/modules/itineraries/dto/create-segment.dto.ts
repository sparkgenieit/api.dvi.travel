import { ApiProperty } from '@nestjs/swagger';
export class CreateSegmentDto {
  @ApiProperty() order: number;
  @ApiProperty() type: string;
  @ApiProperty() title: string;
  @ApiProperty({ required: false }) location?: string;
  @ApiProperty({ required: false }) startTime?: string;
  @ApiProperty({ required: false }) endTime?: string;
  @ApiProperty({ required: false }) details?: string;
}
