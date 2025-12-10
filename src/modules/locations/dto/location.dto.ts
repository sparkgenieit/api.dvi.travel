import { ApiProperty, PartialType } from '@nestjs/swagger';

export class CreateLocationDto {
  @ApiProperty() source_location!: string;
  @ApiProperty() source_city!: string;
  @ApiProperty() source_state!: string;
  @ApiProperty() source_latitude!: string;
  @ApiProperty() source_longitude!: string;

  @ApiProperty() destination_location!: string;
  @ApiProperty() destination_city!: string;
  @ApiProperty() destination_state!: string;
  @ApiProperty() destination_latitude!: string;
  @ApiProperty() destination_longitude!: string;

  @ApiProperty({ description: 'Distance in KM' }) distance_km!: number;
  @ApiProperty({ description: 'Eg: "5 hours 22 mins"' }) duration_text!: string;

  @ApiProperty({ required: false }) location_description?: string;
}

export class UpdateLocationDto extends PartialType(CreateLocationDto) {}

export class ModifyLocationNameDto {
  @ApiProperty({ enum: ['source', 'destination'] })
  scope!: 'source' | 'destination';

  @ApiProperty()
  new_name!: string;
}

export class TollChargeUpsertDto {
  @ApiProperty() vehicle_type_id!: number;
  @ApiProperty() toll_charge!: number;
}

export class BulkTollPayloadDto {
  @ApiProperty({ type: [TollChargeUpsertDto] })
  items!: TollChargeUpsertDto[];
}
