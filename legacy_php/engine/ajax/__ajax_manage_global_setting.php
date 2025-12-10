<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*
*/

include_once('../../jackus.php');
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'global_setting') :

        $errors = [];
        $response = [];
       
        $_itinerary_additional_margin_day_limit = trim($_POST['itinerary_additional_margin_day_limit']);
        $_itinerary_additional_margin_percentage = trim($_POST['itinerary_additional_margin_percentage']);
        $_itinerary_distance_limit = trim($_POST['itinerary_distance_limit']);
        $_allowed_km_limit_per_day = trim($_POST['allowed_km_limit_per_day']);
        $_itinerary_common_buffer_time = trim($_POST['itinerary_common_buffer_time']);
        $_site_seeing_restriction_km_limit = trim($_POST['site_seeing_restriction_km_limit']);
        $_itinerary_travel_by_flight_buffer_time = trim($_POST['itinerary_travel_by_flight_buffer_time']);
        $_itinerary_travel_by_train_buffer_time = trim($_POST['itinerary_travel_by_train_buffer_time']);
        $_itinerary_travel_by_road_buffer_time = trim($_POST['itinerary_travel_by_road_buffer_time']);
        $_itinerary_break_time = $_POST['itinerary_break_time'];
        $_itinerary_hotel_return = $_POST['itinerary_hotel_return'];
        $_itinerary_hotel_start = $_POST['itinerary_hotel_start'];
        $_custom_hotspot_or_activity  = trim($_POST['custom_hotspot_or_activity']);
        $_accommodation_return = trim($_POST['accommodation_return']);
        $_hotel_terms_condition = html_entity_decode(trim($_POST['hotel_terms_condition']));
        $_hotel_voucher_terms_condition = html_entity_decode(trim($_POST['hotel_voucher_terms_condition']));
        $_vehicle_terms_condition = html_entity_decode(trim($_POST['vehicle_terms_condition']));
        $_vehicle_voucher_terms_condition = html_entity_decode(trim($_POST['vehicle_voucher_terms_condition']));
        $_itinerary_local_speed_limit = trim($_POST['itinerary_local_speed_limit']);
        $_itinerary_outstation_speed_limit = trim($_POST['itinerary_outstation_speed_limit']);
        $_agent_referral_bonus_credit = trim($_POST['agent_referral_bonus_credit']);
        $_site_title = trim($_POST['site_title']);
        $_company_name = trim($_POST['company_name']);
        $_company_address = trim($_POST['company_address']);
        $_company_pincode = trim($_POST['company_pincode']);
        $_company_gstin_no = trim($_POST['company_gstin_no']);
        $_company_contact_no = trim($_POST['company_contact_no']);
        $_company_email_id = trim($_POST['company_email_id']);
        $_cc_email_id = trim($_POST['cc_email_id']);
        $_default_hotel_voucher_email_id = trim($_POST['default_hotel_voucher_email_id']);
        $_default_vehicle_voucher_email_id = trim($_POST['default_vehicle_voucher_email_id']);
        $_hotel_hsn = trim($_POST['hotel_hsn']);
        $_vehicle_hsn = trim($_POST['vehicle_hsn']);
        $_service_component_hsn = trim($_POST['service_component_hsn']);
        $_company_pan_no = trim($_POST['company_pan_no']);
        $_youtube_link = trim($_POST['youtube_link']);
        $_facebook_link = trim($_POST['facebook_link']);
        $_instagram_link = trim($_POST['instagram_link']);
        $_linkedin_link = trim($_POST['linkedin_link']);
        $_company_cin = trim($_POST['company_cin']);
        $_account_holder_name = trim($_POST['account_holder_name']);
        $_account_number = trim($_POST['account_number']);
        $_branch_name = trim($_POST['branch_name']);
        $_bank_name = trim($_POST['bank_name']);
        $_bank_ifsc_code = trim($_POST['bank_ifsc_no']);
        $_default_accounts_email_id = trim($_POST['default_accounts_email_id']);
        $_country_id = $_POST['country_id'];
        $_extrabed_rate_percentage = $_POST['extrabed_rate_percentage'];
        $_childwithbed_rate_percentage = $_POST['childwithbed_rate_percentage'];
        $_childnobed_rate_percentage = $_POST['childnobed_rate_percentage'];
        $_hotel_margin = $_POST['hotel_margin'];
        $_hotel_margin_gst_type = $_POST['hotel_margin_gst_type'];
        $_hotel_margin_gst_percentage = $_POST['hotel_margin_gst_percentage'];
        
        if (isset($_country_id)):
            $country_code = implode(',', $_country_id);
        else:
            $country_code = '';
        endif;

        if (isset($_FILES['company_logo']['name'])) :
            $upload_dir = '../../uploads/logo/';
            $fileName = $_FILES["company_logo"]["name"];
            $fileInfo = pathinfo($fileName);
            $fileExtension = $fileInfo["extension"];
            $file_type = $_FILES['company_logo']['type'];
            $file_name = $fileName;
            $file_temp_loc  = $_FILES['company_logo']['tmp_name'];
            $file_error_msg = $_FILES['company_logo']['error'];
            $file_size = $_FILES['company_logo']['size'];
            $company_logo_move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);
        endif;

        if ($company_logo_move_file) :
            $add_logo_arrField = array('`company_logo`');
            $add_logo_arrValue = array("$file_name");
        endif;

        $hidden_global_settings_ID = $_POST['hidden_global_settings_ID'];

        if (empty($_itinerary_distance_limit)) :
            $errors['itinerary_distance_limit_required'] = true;
        endif;
        if (empty($_allowed_km_limit_per_day)) :
            $errors['allowed_km_required'] = true;
        endif;
        if (empty($_itinerary_outstation_speed_limit)) :
            $errors['itinerary_outstation_speed_limit_required'] = true;
        endif;
        if (empty($_itinerary_local_speed_limit)) :
            $errors['itinerary_local_speed_limit_required'] = true;
        endif;
        if (empty($_itinerary_common_buffer_time)) :
            $errors['itinerary_common_buffer_time_required'] = true;
        endif;
        if (empty($_itinerary_travel_by_flight_buffer_time)) :
            $errors['itinerary_travel_by_flight_buffer_time_required'] = true;
        endif;
        if (empty($_itinerary_travel_by_train_buffer_time)) :
            $errors['itinerary_travel_by_train_buffer_time_required'] = true;
        endif;
        if ($_itinerary_travel_by_road_buffer_time == '') :
            $errors['itinerary_travel_by_road_buffer_time_required'] = true;
        endif;
        if ($_itinerary_break_time == '') :
            $errors['itinerary_break_time_required'] = true;
        endif;
        if ($_itinerary_hotel_return == '') :
            $errors['itinerary_hotel_return_required'] = true;
        endif;
        if ($_itinerary_hotel_start == '') :
            $errors['itinerary_hotel_start_required'] = true;
        endif;
        // if ($_custom_hotspot_or_activity == '') :
        //     $errors['custom_hotspot_or_activity_required'] = true;
        // endif;
        // if ($_accommodation_return == '') :
        //     $errors['accommodation_return_required'] = true;
        // endif;
        if ($_hotel_terms_condition == '' || $_vehicle_terms_condition == '' || $_hotel_voucher_terms_condition == '' || $_vehicle_voucher_terms_condition == '') :
            $errors['terms_condition_required'] = true;
        endif;
        if ($_site_title == '') :
            $errors['site_title_return_required'] = true;
        endif;
        if ($_company_name == '') :
            $errors['company_name_return_required'] = true;
        endif;
        if ($_company_address == '') :
            $errors['company_address_return_required'] = true;
        endif;
        if ($_company_pincode == '') :
            $errors['company_pincode_return_required'] = true;
        endif;
        if ($_company_gstin_no == '') :
            $errors['company_gstin_no_return_required'] = true;
        endif;
        if ($_company_contact_no == '') :
            $errors['company_contact_no_return_required'] = true;
        endif;
        if ($_company_email_id == '') :
            $errors['company_email_id_return_required'] = true;
        endif;
        if ($_cc_email_id == '') :
            $errors['cc_email_id_return_required'] = true;
        endif;
        if ($_default_hotel_voucher_email_id == '') :
            $errors['default_hotel_voucher_email_id_required'] = true;
        endif;
        if ($_default_vehicle_voucher_email_id == '') :
            $errors['default_vehicle_voucher_email_id_required'] = true;
        endif;
        if ($_default_accounts_email_id == '') :
            $errors['default_accounts_email_id_required'] = true;
        endif;
        if ($_hotel_hsn == '') :
            $errors['hotel_hsn_return_required'] = true;
        endif;
        if ($_vehicle_hsn == '') :
            $errors['vehicle_hsn_return_required'] = true;
        endif;
        if ($_service_component_hsn == '') :
            $errors['service_component_hsn_return_required'] = true;
        endif;
        if ($_company_pan_no == '') :
            $errors['company_pan_no_return_required'] = true;
        endif;
        if ($_youtube_link == '') :
            $errors['youtube_link_required'] = true;
        endif;
        if ($_facebook_link == '') :
            $errors['facebook_link_required'] = true;
        endif;
        if ($_instagram_link == '') :
            $errors['instagram_link_required'] = true;
        endif;
        if ($_linkedin_link == '') :
            $errors['linkedin_link_required'] = true;
        endif;
        if ($_company_cin == '') :
            $errors['company_cin_required'] = true;
        endif;
        if ($_account_holder_name == '') :
            $errors['account_holder_name_required'] = true;
        endif;
        if ($_account_number == '') :
            $errors['account_number_required'] = true;
        endif;
        if ($_bank_name == '') :
            $errors['bank_name_required'] = true;
        endif;
        if ($_branch_name == '') :
            $errors['branch_name_required'] = true;
        endif;
        if ($_bank_ifsc_code == '') :
            $errors['bank_ifsc_code_required'] = true;
        endif;
         if ($_itinerary_additional_margin_day_limit == '') :
            $errors['itinerary_additional_margin_day_limit_required'] = true;
        endif;
         if ($_itinerary_additional_margin_percentage == '') :
            $errors['itinerary_additional_margin_percentage_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($_itinerary_common_buffer_time != '') :
                $_itinerary_common_buffer_time = date("H:i:s", strtotime($_itinerary_common_buffer_time));
            endif;
            if ($_itinerary_travel_by_flight_buffer_time != '') :
                $_itinerary_travel_by_flight_buffer_time = date("H:i:s", strtotime($_itinerary_travel_by_flight_buffer_time));
            endif;
            if ($_itinerary_travel_by_train_buffer_time != '') :
                $_itinerary_travel_by_train_buffer_time = date("H:i:s", strtotime($_itinerary_travel_by_train_buffer_time));
            endif;
            if ($_itinerary_travel_by_road_buffer_time != '') :
                $_itinerary_travel_by_road_buffer_time = date("H:i:s", strtotime($_itinerary_travel_by_road_buffer_time));
            endif;

            // '`custom_hotspot_or_activity`', '`accommodation_return`',
            $arrFields = array('`extrabed_rate_percentage`', '`childwithbed_rate_percentage`', '`childnobed_rate_percentage`', '`hotel_margin`', '`hotel_margin_gst_type`', '`hotel_margin_gst_percentage`', '`eligibile_country_code`', '`itinerary_distance_limit`', '`allowed_km_limit_per_day`', '`site_seeing_restriction_km_limit`', '`itinerary_common_buffer_time`', '`itinerary_travel_by_flight_buffer_time`', '`itinerary_travel_by_train_buffer_time`', '`itinerary_travel_by_road_buffer_time`', '`itinerary_break_time`', '`itinerary_hotel_return`', '`itinerary_hotel_start`', '`hotel_terms_condition`', '`hotel_voucher_terms_condition`', '`vehicle_terms_condition`', '`vehicle_voucher_terms_condition`', '`itinerary_local_speed_limit`', '`itinerary_outstation_speed_limit`', '`agent_referral_bonus_credit`', '`site_title`', '`company_name`', '`company_address`', '`company_pincode`', '`company_gstin_no`', '`company_contact_no`', '`company_email_id`', '`cc_email_id`', '`default_hotel_voucher_email_id`', '`default_vehicle_voucher_email_id`', '`default_accounts_email_id`', '`company_pan_no`', '`hotel_hsn`', '`vehicle_hsn`', '`service_component_hsn`', '`youtube_link`', '`facebook_link`', '`instagram_link`', '`linkedin_link`', '`company_cin`',  '`bank_acc_holder_name`',  '`bank_acc_no`',   '`branch_name`', '`bank_name`', '`bank_ifsc_code`', '`createdby`', '`status`','`itinerary_additional_margin_day_limit`','`itinerary_additional_margin_percentage`');

            // "$_custom_hotspot_or_activity", "$_accommodation_return",
            $arrValues = array("$_extrabed_rate_percentage", "$_childwithbed_rate_percentage", "$_childnobed_rate_percentage", "$_hotel_margin", "$_hotel_margin_gst_type", "$_hotel_margin_gst_percentage", "$country_code", "$_itinerary_distance_limit", "$_allowed_km_limit_per_day", "$_site_seeing_restriction_km_limit", "$_itinerary_common_buffer_time", "$_itinerary_travel_by_flight_buffer_time", " $_itinerary_travel_by_train_buffer_time", "$_itinerary_travel_by_road_buffer_time", "$_itinerary_break_time", "$_itinerary_hotel_return", "$_itinerary_hotel_start", "$_hotel_terms_condition", "$_hotel_voucher_terms_condition", "$_vehicle_terms_condition", "$_vehicle_voucher_terms_condition", "$_itinerary_local_speed_limit", "$_itinerary_outstation_speed_limit", "$_agent_referral_bonus_credit", "$_site_title", "$_company_name", "$_company_address", "$_company_pincode", "$_company_gstin_no", "$_company_contact_no", "$_company_email_id", "$_cc_email_id", "$_default_hotel_voucher_email_id", "$_default_vehicle_voucher_email_id", "$_default_accounts_email_id", "$_company_pan_no", "$_hotel_hsn", "$_vehicle_hsn", "$_service_component_hsn", "$_youtube_link", "$_facebook_link", "$_instagram_link", "$_linkedin_link", "$_company_cin", "$_account_holder_name", "$_account_number", "$_branch_name", "$_bank_name", "$_bank_ifsc_code",  "$logged_user_id", 1,"$_itinerary_additional_margin_day_limit","$_itinerary_additional_margin_percentage");

            // Add the additional fields and values conditionally
            if ($company_logo_move_file) :
                $arrFields = array_merge($arrFields, $add_logo_arrField);
                $arrValues = array_merge($arrValues, $add_logo_arrValue);
            else :
                $arrFields = $arrFields;
                $arrValues = $arrValues;
            endif;

            if ($hidden_global_settings_ID != '' && $hidden_global_settings_ID != 0) :
                $sqlWhere = " `global_settings_ID` = '$hidden_global_settings_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_global_settings", $arrFields, $arrValues, $sqlWhere)) :
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'global_settings.php';
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'state_config_update') :

        $errors = [];
        $response = [];

        $_vehicle_onground_support_number = trim($_POST['vehicle_onground_support_number']);
        $_vehicle_escalation_call_number = trim($_POST['vehicle_escalation_call_number']);
        $state_id = $_POST['state_name'];

        if (empty($state_id)) :
            $errors['state_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
            $arrFields = array('`vehicle_onground_support_number`', '`vehicle_escalation_call_number`', '`createdby`');
            $arrValues = array(
                "$_vehicle_onground_support_number",
                "$_vehicle_escalation_call_number",
                "$logged_user_id"
            );
            if ($state_id != '' && $state_id != 0) :
                $sqlWhere = " `id` = '$state_id' ";

                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_states", $arrFields, $arrValues, $sqlWhere)) :

                    $response['u_result'] = true;
                    // $response['redirect_URL'] = 'global_settings.php';
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;
        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
