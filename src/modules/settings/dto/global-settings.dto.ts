// FILE: src/modules/settings/dto/global-settings.dto.ts

import { IsString, IsNumber, IsOptional, IsEmail, IsIn, IsBoolean } from 'class-validator';
import { ApiPropertyOptional } from '@nestjs/swagger';

export class UpdateGlobalSettingsDto {
  // State Configuration
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  state_name?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  onground_support_number?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  escalation_call_number?: string;

  // Hotel API
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  tbo_eligible_country?: string;

  // Extra Occupancy
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  extrabed_rate_percentage?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  childwithbed_rate_percentage?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  child_nobed_rate_percentage?: number;

  // Hotel Default Margin
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  hotel_margin_in_percentage?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsBoolean()
  hotel_margin_gst_type?: boolean;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  hotel_margin_gst_percentage?: number;

  // Itinerary Distance
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  itinerary_distance_limit?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  allowed_km_per_day?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  common_buffer_time?: string;

  // Site Seeing KM Limit
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  site_seeing_km_limit?: number;

  // Travel Buffer Time
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  flight_buffer_time?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  train_buffer_time?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  road_buffer_time?: string;

  // Customize Text
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  journey_start_text?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  between_day_start_text?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  between_day_end_text?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  hotel_terms_condition?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  vehicle_terms_condition?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  hotel_voucher_terms?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  vehicle_voucher_terms?: string;

  // Travel Speed
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  local_travel_speed_limit?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  outstation_travel_speed_limit?: number;

  // Additional Margin
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  additional_margin_percentage?: number;

  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  additional_margin_day_limit?: number;

  // Agent Settings
  @ApiPropertyOptional()
  @IsOptional()
  @IsNumber()
  referral_bonus_credit?: number;

  // Site Settings
  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  site_title?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  company_name?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  address?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  pincode?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  gstin_no?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  pan_no?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  contact_no?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsEmail()
  email_id?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsEmail()
  cc_email_id?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsEmail()
  hotel_voucher_email?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsEmail()
  vehicle_voucher_email?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsEmail()
  accounts_email?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  hotel_hsn?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  vehicle_hsn?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  guide_hotspot_activity_hsn?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  logo_path?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  cin_number?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  youtube_link?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  facebook_link?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  instagram_link?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  linkedin_link?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  account_holder_name?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  account_number?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  ifsc_code?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  bank_name?: string;

  @ApiPropertyOptional()
  @IsOptional()
  @IsString()
  branch_name?: string;
}
