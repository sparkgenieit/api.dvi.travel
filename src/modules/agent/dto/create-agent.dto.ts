import { IsEmail, IsInt, IsNumber, IsOptional, IsString, MaxLength } from 'class-validator';

export class CreateAgentDto {
  @IsOptional() @IsInt() travel_expert_id?: number;
  @IsOptional() @IsInt() subscription_plan_id?: number;

  @IsOptional() @IsString() @MaxLength(250) agent_name?: string;
  @IsOptional() @IsString() @MaxLength(250) agent_lastname?: string;

  @IsOptional() @IsEmail() agent_email_id?: string;

  @IsOptional() @IsString() agent_primary_mobile_number?: string;
  @IsOptional() @IsString() agent_alternative_mobile_number?: string;

  @IsOptional() @IsInt() agent_country?: number;
  @IsOptional() @IsInt() agent_state?: number;
  @IsOptional() @IsInt() agent_city?: number;

  @IsOptional() @IsString() agent_company_name?: string;
  @IsOptional() @IsString() agent_gst_number?: string;
  @IsOptional() @IsString() agent_gst_attachment?: string;

  @IsOptional() @IsNumber() agent_margin?: number;
  @IsOptional() @IsInt() agent_margin_gst_type?: number;
  @IsOptional() @IsNumber() agent_margin_gst_percentage?: number;
  @IsOptional() @IsNumber() itinerary_margin_discount_percentage?: number;
}
