// FILE: src/modules/global-settings/dto/update-global-settings.dto.ts

export class UpdateGlobalSettingsDto {
  eligibile_country_code?: string | null;

  extrabed_rate_percentage?: number;
  childwithbed_rate_percentage?: number;
  childnobed_rate_percentage?: number;

  hotel_margin?: number;
  hotel_margin_gst_type?: boolean;
  hotel_margin_gst_percentage?: number;

  itinerary_distance_limit?: number;
  allowed_km_limit_per_day?: number;

  itinerary_common_buffer_time?: string | null;
  itinerary_travel_by_flight_buffer_time?: string | null;
  itinerary_travel_by_train_buffer_time?: string | null;
  itinerary_travel_by_road_buffer_time?: string | null;
  itinerary_break_time?: string | null;

  itinerary_hotel_start?: string | null;
  itinerary_hotel_return?: string | null;
  itinerary_additional_margin_percentage?: number;
  itinerary_additional_margin_day_limit?: number;

  custom_hotspot_or_activity?: string | null;
  accommodation_return?: string | null;
  vehicle_terms_condition?: string | null;

  itinerary_local_speed_limit?: number;
  itinerary_outstation_speed_limit?: number;

  agent_referral_bonus_credit?: number;

  hotel_terms_condition?: string | null;
  hotel_voucher_terms_condition?: string | null;
  vehicle_voucher_terms_condition?: string | null;

  site_title?: string | null;

  company_name?: string | null;
  company_address?: string | null;
  company_pincode?: string | null;
  company_gstin_no?: string | null;
  company_pan_no?: string | null;
  company_contact_no?: string | null;
  company_email_id?: string | null;
  company_logo?: string | null;

  hotel_hsn?: string | null;
  vehicle_hsn?: string | null;
  service_component_hsn?: string | null;

  site_seeing_restriction_km_limit?: number;

  youtube_link?: string | null;
  facebook_link?: string | null;
  instagram_link?: string | null;
  linkedin_link?: string | null;

  cc_email_id?: string | null;
  default_hotel_voucher_email_id?: string | null;
  default_vehicle_voucher_email_id?: string | null;
  default_accounts_email_id?: string | null;

  company_cin?: string | null;
  bank_acc_holder_name?: string | null;
  bank_acc_no?: string | null;
  bank_ifsc_code?: string | null;
  bank_name?: string | null;
  branch_name?: string | null;
}
