import { ApiProperty } from '@nestjs/swagger';
import { Transform } from 'class-transformer';
import { ArrayNotEmpty, IsArray, IsInt, Min } from 'class-validator';

export class AssignVehicleDto {
  @ApiProperty({ example: 12 })
  @Transform(({ value }) => Number(value))
  @IsInt()
  @Min(1)
  vendorId!: number;

  @ApiProperty({
    example: 3,
    description: 'Vendor vehicle type id (vehicle_type_id in legacy)',
  })
  @Transform(({ value }) => Number(value))
  @IsInt()
  @Min(1)
  vehicleTypeId!: number;

  @ApiProperty({ example: 55 })
  @Transform(({ value }) => Number(value))
  @IsInt()
  @Min(1)
  vehicleId!: number;

  @ApiProperty({ example: [17940, 17941] })
  @IsArray()
  @ArrayNotEmpty()
  @Transform(({ value }) =>
    (Array.isArray(value) ? value : String(value).split(',')).map((v) =>
      Number(v),
    ),
  )
  itineraryPlanIds!: number[];
}
