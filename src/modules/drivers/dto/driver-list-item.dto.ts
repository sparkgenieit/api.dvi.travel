// FILE: src/modules/drivers/dto/driver-list-item.dto.ts
import { ApiProperty } from '@nestjs/swagger';

export class DriverListItemDto {
  @ApiProperty()
  id!: number;

  @ApiProperty()
  name!: string;

  @ApiProperty()
  mobile!: string;

  @ApiProperty()
  licenseNumber!: string;

  @ApiProperty({ required: false, nullable: true })
  licenseExpiryDate!: Date | null;

  @ApiProperty({
    example: 'Active',
    enum: ['Active', 'In-Active', 'Expires Today'],
  })
  licenseStatus!: string;

  @ApiProperty({ description: 'true = Active, false = Inactive' })
  status!: boolean;
}
