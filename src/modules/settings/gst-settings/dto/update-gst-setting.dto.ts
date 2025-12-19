import { IsNumber, IsOptional, IsString, Min } from 'class-validator';

export class UpdateGstSettingDto {
  @IsOptional()
  @IsString()
  gstTitle?: string;

  @IsOptional()
  @IsNumber()
  @Min(0)
  gst?: number;

  @IsOptional()
  @IsNumber()
  @Min(0)
  cgst?: number;

  @IsOptional()
  @IsNumber()
  @Min(0)
  sgst?: number;

  @IsOptional()
  @IsNumber()
  @Min(0)
  igst?: number;

  // frontend may send status, but PHP forces status=1 on update
  status?: boolean;
}
