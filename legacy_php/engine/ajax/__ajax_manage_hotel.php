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

    if ($_GET['type'] == 'hotel_basic_info') :

        $errors = [];
        $response = [];

        $_hotel_name = trim($_POST['hotel_name']);
        $_hotel_code = trim($_POST['hotel_code']);
        $_hotel_place = trim($_POST['hotel_place']);
        $_hotel_mobile_no = $_POST['hotel_mobile_no'];
        $_hotel_email_id = $_POST['hotel_email_id'];
        $_hotel_address = trim($_POST['hotel_address']);
        $_hotel_category = $_POST['hotel_category'];
        $_hotel_status = trim($_POST['hotel_status']);
        $_hotel_powerbackup = trim($_POST['hotel_powerbackup']);
        $_hotel_country = trim($_POST['hotel_country']);
        $_hotel_state = trim($_POST['hotel_state']);
        $_hotel_city = trim($_POST['hotel_city']);
        $_hotel_postal_code = trim($_POST['hotel_postal_code']);
        $_hotel_latitude = trim($_POST['hotel_latitude']);
        $_hotel_longitude = trim($_POST['hotel_longitude']);
        $_hotel_hotspot_status = trim($_POST['hotel_hotspot_status']);

        $_hotel_margin = trim($_POST['hotel_margin']);
        $_hotel_margin_gst_type = trim($_POST['hotel_margin_gst_type']);
        $_hotel_margin_gst_percentage = trim($_POST['hotel_margin_gst_percentage']);
        //$_hotel_breafast_cost = trim($_POST['hotel_breafast_cost']);
        //$_hotel_lunch_cost = trim($_POST['hotel_lunch_cost']);
        //$_hotel_dinner_cost = trim($_POST['hotel_dinner_cost']);

        $hidden_hotel_ID = $_POST['hidden_hotel_ID'];

        // Decode the JSON array
        $jsondecoded_mobile_data = json_decode($_hotel_mobile_no, true);

        // Extract "value" field from each object
        $mobile_data = array();
        foreach ($jsondecoded_mobile_data as $item_mobile) :
            $mobile_data[] = $item_mobile['value'];
        endforeach;

        // Decode the JSON array
        $jsondecoded_email_data = json_decode($_hotel_email_id, true);

        // Extract "value" field from each object
        $email_data = array();
        foreach ($jsondecoded_email_data as $item_email) :
            $email_data[] = $item_email['value'];
        endforeach;

        $formatted_hotel_mobile_no = implode(',', $mobile_data);
        $formatted_hotel_email_id = implode(',', $email_data);

        if (empty($_hotel_name)) :
            $errors['hotel_name_required'] = true;
        endif;
        if (empty($_hotel_code)) :
            $errors['hotel_code_required'] = true;
        endif;
        if (empty($_hotel_place)) :
            $errors['hotel_place_required'] = true;
        endif;
        if (empty($_hotel_mobile_no)) :
            $errors['hotel_mobile_no_required'] = true;
        endif;
        if (empty($_hotel_email_id)) :
            $errors['hotel_email_id_required'] = true;
        endif;
        if (empty($_hotel_address)) :
            $errors['hotel_address_required'] = true;
        endif;
        if (empty($_hotel_category)) :
            $errors['hotel_category_required'] = true;
        endif;
        if ($_hotel_status == '') :
            if (empty($_hotel_status)) :
                $errors['hotel_status_required'] = true;
            endif;
        endif;
        if ($_hotel_powerbackup == '') :
            if (empty($_hotel_powerbackup)) :
                $errors['hotel_powerbackup_required'] = true;
            endif;
        endif;
        if (empty($_hotel_country)) :
            $errors['hotel_country_required'] = true;
        endif;
        if (empty($_hotel_state)) :
            $errors['hotel_state_required'] = true;
        endif;
        if (empty($_hotel_city)) :
            $errors['hotel_city_required'] = true;
        endif;
        if (empty($_hotel_postal_code)) :
            $errors['hotel_postal_code_required'] = true;
        endif;
        if ($_hotel_margin == "") :
            $errors['hotel_margin_required'] = true;
        endif;
        if (empty($_hotel_margin_gst_type)) :
            $errors['hotel_margin_gst_type_required'] = true;
        endif;
        if (empty($_hotel_margin_gst_percentage)) :
            $errors['hotel_margin_gst_percentage_required'] = true;
        endif;
        /* if ($_hotel_breafast_cost == "") :
            $errors['hotel_breafast_cost_required'] = true;
        endif;
        if ($_hotel_lunch_cost == "") :
            $errors['hotel_lunch_cost_required'] = true;
        endif;
        if ($_hotel_dinner_cost == "") :
            $errors['hotel_dinner_cost_required'] = true;
        endif;*/

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`hotel_name`', '`hotel_code`', '`hotel_place`', '`hotel_mobile`', '`hotel_email`', '`hotel_country`', '`hotel_state`', '`hotel_city`', '`hotel_address`', '`hotel_pincode`', '`hotel_margin`', '`hotel_margin_gst_type`', '`hotel_margin_gst_percentage`', '`hotel_latitude`', '`hotel_longitude`', '`hotel_category`', '`hotel_power_backup`', '`hotel_hotspot_status`', '`createdby`', '`status`');

            $arrValues = array("$_hotel_name", "$_hotel_code", "$_hotel_place", "$formatted_hotel_mobile_no", "$formatted_hotel_email_id", "$_hotel_country", "$_hotel_state", "$_hotel_city", "$_hotel_address", "$_hotel_postal_code", "$_hotel_margin", "$_hotel_margin_gst_type", "$_hotel_margin_gst_percentage", "$_hotel_latitude", "$_hotel_longitude", "$_hotel_category", "$_hotel_powerbackup", "$_hotel_hotspot_status", "$logged_user_id", "$_hotel_status");

            if ($hidden_hotel_ID != '' && $hidden_hotel_ID != 0) :
                $sqlWhere = " `hotel_id` = '$hidden_hotel_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_hotel", $arrFields, $arrValues, $sqlWhere)) :
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'hotel.php?route=edit&formtype=room_details&id=' . $hidden_hotel_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_hotel", $arrFields, $arrValues, '')) :
                    $hotel_id = sqlINSERTID_LABEL();
                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'hotel.php?route=add&formtype=room_details&id=' . $hotel_id;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotel_pricebook') :

        $errors = [];
        $response = [];

        $_hotel_category = $_POST['hotel_category']; // hotel category
        $hidden_hotel_ID = $_POST['hotel_name']; // hotel name
        $_room_type_id = $_POST['room_type']; // room type
        $_price = $_POST['price'];
        $_selectstartdate = $_POST['selectstartdate'];
        $_selectenddate = $_POST['selectenddate'];

        if (empty($_hotel_category)) :
            $errors['hotel_category_required'] = true;
        endif;
        if (empty($hidden_hotel_ID)) :
            $errors['hotel_name_required'] = true;
        endif;
        if (empty($_room_type_id)) :
            $errors['room_type_required'] = true;
        endif;

        if (empty($_selectstartdate)) :
            $errors['selectstartdate_required'] = true;
        endif;
        if (empty($_selectenddate)) :
            $errors['selectenddate_required'] = true;
        endif;
        if (empty($_price)) :
            $errors['price_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;

        else :
            //success call		

            $response['success'] = true;

            //FETCH ALL ROOMS OF SELECTED ROOM TYPE AND UPDATE WITH NEW PRICE
            $selected_rooms_query = sqlQUERY_LABEL("SELECT `room_ID` FROM `dvi_hotel_rooms` where `deleted` = '0' AND `status`='1' and `hotel_id` = '$hidden_hotel_ID' AND `room_type_id`='$_room_type_id' ") or die("#PARENT-LABEL: getHOTEL_ROOM_TYPE_DETAIL: " . sqlERROR_LABEL());
            if (sqlNUMOFROW_LABEL($selected_rooms_query) > 0) :
                while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_rooms_query)) :
                    $room_ID = $fetch_room_data['room_ID'];

                    $startDate = strtotime($_selectstartdate);
                    $endDate = strtotime($_selectenddate);
                    $endDateMonth = date('m', $endDate);
                    // Loop through each month and year
                    $currentDate = $startDate;
                    while ($currentDate <= $endDate) :
                        $currentYear = date('Y', $currentDate);
                        $currentMonth = date('m', $currentDate);
                        $currentMonthName = date('F', $currentDate);

                        // Determine start and end days of the month
                        $start_day_of_month = (int)date('d', $currentDate);
                        if ($endDateMonth != $currentMonth) :
                            $end_day_of_month = (int)date('t', $currentDate);
                        else :
                            $end_day_of_month = (int)date('d', $endDate);
                        endif;

                        //Check Room Price is already exixting 
                        $check_price_already_existing = sqlQUERY_LABEL("SELECT `hotel_price_book_id`,`month`,`year` FROM `dvi_hotel_room_price_book` WHERE `hotel_id`='$hidden_hotel_ID' AND `room_id`='$room_ID' AND `deleted`='0' AND  `year`='$currentYear' AND `month` ='$currentMonthName' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($check_price_already_existing) > 0) :
                            //UPDATE ROOM PRICE
                            while ($fetch_price_row = sqlFETCHARRAY_LABEL($check_price_already_existing)) :
                                $hotel_price_book_id = $fetch_price_row['hotel_price_book_id'];
                                $price_year = $fetch_price_row['year'];
                                $price_month = $fetch_price_row['month'];

                                for ($i = 1; $i <= 31; $i++) :
                                    if (
                                        $i >= $start_day_of_month && $i <= $end_day_of_month
                                    ) :
                                        ${"day_" . $i} = $_price;
                                        $update_price = sqlQUERY_LABEL("UPDATE `dvi_hotel_room_price_book` SET `day_$i` = '$_price', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotel_price_book_id` = '$hotel_price_book_id'") or die("#1-UNABLE_TO_UPDATE:" . sqlERROR_LABEL());
                                    endif;

                                endfor;
                                $response['u_result'] = true;
                                $response['hotel_id'] =  $hidden_hotel_ID;
                                $response['result_success'] = true;
                            endwhile;

                        else :
                            //INSERT PRICE DETAILS

                            for ($i = 1; $i <= 31; $i++) :
                                if ($i >= $start_day_of_month && $i <= $end_day_of_month) :
                                    ${"day_" . $i} = $_price;
                                else :
                                    ${"day_" . $i} = 0;
                                endif;
                            endfor;

                            $arrFields_price_details = array('`hotel_id`', '`room_type_id`', '`room_id`', '`year`', '`month`', '`day_1`', '`day_2`', '`day_3`', '`day_4`', '`day_5`', '`day_6`', '`day_7`', '`day_8`', '`day_9`', '`day_10`', '`day_11`', '`day_12`', '`day_13`', '`day_14`', '`day_15`', '`day_16`', '`day_17`', '`day_18`', '`day_19`', '`day_20`', '`day_21`', '`day_22`', '`day_23`', '`day_24`', '`day_25`', '`day_26`', '`day_27`', '`day_28`', '`day_29`', '`day_30`', '`day_31`', '`createdby`', '`status`');

                            $arrValues_price_details = array("$hidden_hotel_ID", "$_room_type_id", "$room_ID", "$currentYear", "$currentMonthName", "$day_1", "$day_2", "$day_3", "$day_4", "$day_5", "$day_6", "$day_7", "$day_8", "$day_9", "$day_10", "$day_11", "$day_12", "$day_13", "$day_14", "$day_15", "$day_16", "$day_17", "$day_18", "$day_19", "$day_20", "$day_21", "$day_22", "$day_23", "$day_24", "$day_25", "$day_26", "$day_27", "$day_28", "$day_29", "$day_30", "$day_31", "$logged_user_id", "1");

                            //INSERT PRICE DETAILS
                            if (sqlACTIONS("INSERT", "dvi_hotel_room_price_book", $arrFields_price_details, $arrValues_price_details, '')) :

                                $response['i_result'] = true;
                                $response['result_success'] = true;
                                $response['hotel_id'] =  $hidden_hotel_ID;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                            endif;

                        endif;

                        // Move to the next month and set the day to 1
                        $currentDate = strtotime('+1 month', strtotime(date('01-m-Y', $currentDate)));
                    endwhile;

                endwhile;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotel_room_details') :

        $errors = [];
        $response = [];

        $_hotel_room_type_title = $_POST['hotel_room_type_title'];
        $_preferred_for = $_POST['preferred_for'];
        $_hotel_room_title = $_POST['hotel_room_title'];
        $_no_of_rooms_available = $_POST['no_of_rooms_available'];
        $_air_conditioner_avilability = $_POST['air_conditioner_avilability'];
        $_room_status = $_POST['room_status'];
        $_total_max_adult = $_POST['total_max_adult'];
        $_total_max_children = $_POST['total_max_children'];
        $_check_in_time = $_POST['check_in_time'];
        $_check_out_time = $_POST['check_out_time'];
        $_breakfast_included = $_POST['breakfast_included'];
        $_lunch_included = $_POST['lunch_included'];
        $_dinner_included = $_POST['dinner_included'];
        $hidden_room_ID = $_POST['hidden_room_ID'];
        $_hotel_gst_status = $_POST['gst_status'];
        $_hotel_gst_value = $_POST['gst_status_value'];
        $_inbuild_amenities = $_POST['inbuild_amenities'];
        /* $_child_with_bed_charge = $_POST['child_with_bed_charge'];
        $_child_without_bed_charge = $_POST['child_without_bed_charge'];
        $_extra_bed_charge = $_POST['extra_bed_charge']; */
        $hidden_hotel_ID = $_POST['hidden_hotel_ID'];

        if (empty($_hotel_room_type_title)) :
            $errors['hotel_room_type_title_required'] = true;
        endif;
        if (empty($_hotel_room_title)) :
            $errors['hotel_room_title_required'] = true;
        endif;
        /* if (empty($_preferred_for)) :
            $errors['preferred_for_required'] = true;
        endif; */
        if (empty($_inbuild_amenities)) :
            $errors['inbuild_amenities_required'] = true;
        endif;
        if ($_air_conditioner_avilability == '') :
            if (empty($_air_conditioner_avilability)) :
                $errors['air_conditioner_avilability_required'] = true;
            endif;
        endif;
        /* if (empty($_extra_bed_charge)) :
            $errors['extra_bed_charge_required'] = true;
        endif;

        if (empty($_child_with_bed_charge)) :
            $errors['child_with_bed_charge_required'] = true;
        endif;

        if (empty($_child_without_bed_charge)) :
            $errors['child_without_bed_charge_required'] = true;
        endif;*/

        if ($_room_status == '') :
            if (empty($_room_status)) :
                $errors['room_status_required'] = true;
            endif;
        endif;

        if (empty($_total_max_adult)) :
            $errors['total_max_adult_required'] = true;
        endif;
        if ($_total_max_children == '') :
            if (empty($_total_max_children)) :
                $errors['total_max_children_required'] = true;
            endif;
        endif;
        if (empty($_check_in_time)) :
            $errors['check_in_time_required'] = true;
        endif;
        if (empty($_check_out_time)) :
            $errors['check_out_time_required'] = true;
        endif;
        if (empty($_hotel_gst_status)) :
            $errors['gst_status_required'] = true;
        endif;
        if (empty($_hotel_gst_value)) :
            $errors['gst_status_value_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            foreach ($_hotel_room_type_title as $key => $val) :

                $count = count($_hotel_room_type_title);
                $selected_ROOM_TYPE_TITLE = trim($_POST['hotel_room_type_title'][$key]);
                $selected_ROOM_TITLE = trim($_POST['hotel_room_title'][$key]);
                $selected_ROOM_AVILABLE_COUNT = trim($_POST['no_of_rooms_available'][$key]);
                $selected_PREFERRED_FOR = $_POST['preferred_for'][$key];
                $selected_AC_AVILABILITY = $_POST['air_conditioner_avilability'][$key];
                $selected_ROOM_STATUS = $_POST['room_status'][$key];
                $selected_MAX_ADULTS = $_POST['total_max_adult'][$key];
                $selected_MAX_CHILDREN = $_POST['total_max_children'][$key];
                $selected_CHECK_IN_TIME = $_POST['check_in_time'][$key];
                $selected_CHECK_OUT_TIME = $_POST['check_out_time'][$key];
                $selected_BREAKFAST_INCLUDED = $_POST['breakfast_included'][$key];
                $selecetd_LUNCH_INCLUDED = $_POST['lunch_included'][$key];
                $selecetd_DINNER_INCLUDED = $_POST['dinner_included'][$key];
                $selected_GST_STATUS = $_POST['gst_status'][$key];
                $selected_GST_STATUS_VALUE = $_POST['gst_status_value'][$key];
                $selected_INBUILT_AMENITIES = $_POST['inbuild_amenities'][$key];
                $selected_ROOM_ID = $_POST['hidden_room_ID'][$key];

                $selected_CHECK_IN_TIME_24_HRS_FORMAT = date("H:i:00", strtotime($selected_CHECK_IN_TIME));
                $selected_CHECK_OUT_TIME_24_HRS_FORMAT = date("H:i:00", strtotime($selected_CHECK_OUT_TIME));

                if ($selected_PREFERRED_FOR) :
                    $preferred_for_data = implode(',', $selected_PREFERRED_FOR);
                else :
                    $preferred_for_data = '';
                endif;

                if ($selected_INBUILT_AMENITIES) :
                    $inbuild_amenities_data = implode(',', $selected_INBUILT_AMENITIES);
                else :
                    $inbuild_amenities_data = '';
                endif;

                if ($selected_BREAKFAST_INCLUDED[0] == 'on') :
                    $breakfast_included_status = 1;
                else :
                    $breakfast_included_status = 0;
                endif;

                if ($selecetd_LUNCH_INCLUDED[0] == 'on') :
                    $lunch_included_status = 1;
                else :
                    $lunch_included_status = 0;
                endif;

                if ($selecetd_DINNER_INCLUDED[0] == 'on') :
                    $dinner_included_status = 1;
                else :
                    $dinner_included_status = 0;
                endif;

                if ($selected_ROOM_TYPE_TITLE != '') :
                    $room_type_datas = sqlQUERY_LABEL("SELECT `room_type_id` FROM `dvi_hotel_roomtype` where `room_type_title` = '$selected_ROOM_TYPE_TITLE' and `deleted` = '0'") or die("#UNABLE_TO_CHECK_ROOM_TYPE_DETAILS:" . sqlERROR_LABEL());
                    $borrower_data_count = sqlNUMOFROW_LABEL($room_type_datas);
                    if ($borrower_data_count > 0) :
                        $fetch_data = sqlFETCHARRAY_LABEL($room_type_datas);
                        $selected_room_type_id = $fetch_data['room_type_id'];
                    else :
                        $room_type_arrFields = array('`room_type_title`', '`createdby`', '`status`');
                        $room_type_arrValues = array("$selected_ROOM_TYPE_TITLE", "$logged_user_id", "1");
                        if (sqlACTIONS('INSERT', "dvi_hotel_roomtype", $room_type_arrFields, $room_type_arrValues, '')) :
                            $selected_room_type_id = sqlINSERTID_LABEL();
                        endif;
                    endif;
                else :
                    $selected_room_type_id = 1; //Default Room Type ID Others
                endif;

                if ($selected_ROOM_ID != '' && $selected_ROOM_ID != 0) :
                    $room_ref_code = getROOM_DETAILS($selected_ROOM_ID, 'room_ref_code');
                else :
                    $room_ref_code = get_ROOM_REFERENCE_CODE($hidden_hotel_ID, $selected_room_type_id, $selected_ROOM_TYPE_TITLE);
                endif;
                $room_arrFields = array('`hotel_id`', '`room_type_id`', '`preferred_for`', '`no_of_rooms_available`', '`room_title`', '`room_ref_code`', '`air_conditioner_availability`', '`total_max_adults`', '`total_max_childrens`', '`check_in_time`', '`check_out_time`', '`gst_type`', '`gst_percentage`', '`breakfast_included`', '`lunch_included`', '`dinner_included`', '`inbuilt_amenities`',   '`createdby`', '`status`');

                $room_arrValues = array("$hidden_hotel_ID", "$selected_room_type_id", "$preferred_for_data", "$selected_ROOM_AVILABLE_COUNT", "$selected_ROOM_TITLE", "$room_ref_code", "$selected_AC_AVILABILITY", "$selected_MAX_ADULTS", "$selected_MAX_CHILDREN", "$selected_CHECK_IN_TIME_24_HRS_FORMAT", "$selected_CHECK_OUT_TIME_24_HRS_FORMAT", "$selected_GST_STATUS", "$selected_GST_STATUS_VALUE", "$breakfast_included_status", "$lunch_included_status", "$dinner_included_status", "$inbuild_amenities_data",  "$logged_user_id", "$selected_ROOM_STATUS");

                if ($selected_ROOM_ID != '' && $selected_ROOM_ID != 0) :
                    $room_sqlwhere = " `room_ID` = '$selected_ROOM_ID' ";

                    //ROOM GALLERY
                    if (isset($_FILES['room_gallery']['name'][$key])) :
                        $room_gallery_count = count($_FILES['room_gallery']['name'][$key]);
                    else :
                        $room_gallery_count = 0;
                    endif;

                    if ($room_gallery_count > 0) :
                        for ($i = 0; $i < $room_gallery_count; $i++) :
                            if (isset($_FILES['room_gallery']['name'][$key][$i]) && $_FILES['room_gallery']['name'][$key][$i] != '') :
                                $upload_dir = '../../uploads/room_gallery/';
                                $filetype = end(explode('.', $_FILES['room_gallery']['name'][$key][$i]));
                                $file_name = $room_ref_code . "_" . ($i + 1) . "." . $filetype;
                                $file_type = $_FILES['room_gallery']['type'][$key][$i];
                                $file_temp_loc  = $_FILES['room_gallery']['tmp_name'][$key][$i];
                                $file_error_msg = $_FILES['room_gallery']['error'][$key][$i];
                                $file_size = $_FILES['room_gallery']['size'][$key][$i];
                                $move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);

                                if ($move_file) :
                                    $arrFields_gallery = array('`hotel_id`', '`room_id`', '`room_gallery_name`', '`createdby`', '`status`');
                                    $arrValues_gallery = array("$hidden_hotel_ID", "$selected_ROOM_ID", "$file_name", "$logged_user_id", "1");
                                    if (sqlACTIONS("INSERT", "dvi_hotel_room_gallery_details", $arrFields_gallery, $arrValues_gallery, '')) :
                                    //SUCCESS
                                    endif;
                                endif;
                            endif;
                        endfor;
                    endif;

                    $existing_ROOM_TYPE_ID = getROOM_PRICEBOOK_DETAILS($hidden_hotel_ID, $selected_ROOM_ID, '', '', '', 'price_book_room_type');

                    //UPDATE ROOM DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_hotel_rooms", $room_arrFields, $room_arrValues, $room_sqlwhere)) :
                        if ($existing_ROOM_TYPE_ID != $selected_room_type_id):
                            //UPDATE ROOM TYPE ID IN PRICEBOOK TABLE 
                            $room_price_arrFields = array('`room_type_id`');
                            $room_price_arrValues = array("$selected_room_type_id");
                            $room_price_sqlwhere = " `room_id` = '$selected_ROOM_ID' ";
                            if (sqlACTIONS("UPDATE", "dvi_hotel_room_price_book", $room_price_arrFields, $room_price_arrValues, $room_price_sqlwhere)) :
                            //SUCCESS UPDATE PRICEBOOK
                            endif;
                        endif;
                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hidden_hotel_ID;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;
                else :
                    //INSERT ROOM DETAILS
                    if (sqlACTIONS("INSERT", "dvi_hotel_rooms", $room_arrFields, $room_arrValues, '')) :
                        $room_ID = sqlINSERTID_LABEL();

                        //ROOM GALLERY
                        $room_gallery_count = count($_FILES['room_gallery']['name'][$key]);
                        if ($room_gallery_count > 0) :
                            for ($i = 0; $i < $room_gallery_count; $i++) :
                                if (isset($_FILES['room_gallery']['name'][$key][$i]) && $_FILES['room_gallery']['name'][$key][$i] != '') :
                                    $upload_dir = '../../uploads/room_gallery/';
                                    $filetype = end(explode('.', $_FILES['room_gallery']['name'][$key][$i]));
                                    $file_name = $room_ref_code . "_" . ($i + 1) . "." . $filetype;
                                    $file_type = $_FILES['room_gallery']['type'][$key][$i];
                                    $file_temp_loc  = $_FILES['room_gallery']['tmp_name'][$key][$i];
                                    $file_error_msg = $_FILES['room_gallery']['error'][$key][$i];
                                    $file_size = $_FILES['room_gallery']['size'][$key][$i];
                                    $move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);

                                    if ($move_file) :
                                        $arrFields_gallery = array('`hotel_id`', '`room_id`', '`room_gallery_name`', '`createdby`', '`status`');
                                        $arrValues_gallery = array("$hidden_hotel_ID", "$room_ID", "$file_name", "$logged_user_id", "1");
                                        if (sqlACTIONS("INSERT", "dvi_hotel_room_gallery_details", $arrFields_gallery, $arrValues_gallery, '')) :
                                        //SUCCESS
                                        endif;
                                    endif;
                                endif;
                            endfor;
                        endif;

                        $response['i_result'] = true;
                        $response['redirect_URL'] = 'hotel.php?route=add&formtype=room_amenities&id=' . $hidden_hotel_ID;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;
                endif;
            endforeach;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'confirm_room_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];
        $_HOT_ID = $_POST['_HOT_ID'];

        $room_arrFields = array('`deleted`');
        $room_arrValues = array("1");
        $room_sqlwhere = " `room_ID` = '$_ID'";

        if (sqlACTIONS("UPDATE", "dvi_hotel_rooms", $room_arrFields, $room_arrValues, $room_sqlwhere)) :

            $delete_LIST_HOTELROOMPRICEBOOK = sqlQUERY_LABEL("DELETE FROM `dvi_hotel_room_price_book` WHERE `hotel_id` = '$_HOT_ID' AND `room_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $delete_LIST_HOTELROOMGALLERY = sqlQUERY_LABEL("UPDATE `dvi_hotel_room_gallery_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotel_id` = '$_HOT_ID' AND `room_ID` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());


            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'confirm_room_gallery_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];
        $_HOT_ID = $_POST['_HOT_ID'];

        $room_gallery_sqlwhere = " `hotel_room_gallery_details_id` = '$_ID'";
        $upload_dir = '../../uploads/room_gallery/';
        $room_gallery_name = getROOM_GALLERY_DETAILS('', '', $_ID, 'room_gallery_name');

        if (sqlACTIONS("DELETE", "dvi_hotel_room_gallery_details", '', '', $room_gallery_sqlwhere)) :
            unlink($upload_dir . $room_gallery_name);
            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotel_amenities_details') :

        $errors = [];
        $response = [];

        $_amenities_title = $_POST['amenities_title'];
        //$_amenities_code = $_POST['amenities_code'];
        $_amenities_qty = $_POST['amenities_qty'];
        $_availability_type = $_POST['availability_type'];
        $_available_start_time = $_POST['available_start_time'];
        $_available_end_time = $_POST['available_end_time'];
        $_amenities_status = $_POST['amenities_status'];
        $hidden_amenities_ID = $_POST['hidden_amenities_ID'];
        $hidden_hotel_ID = $_POST['hidden_hotel_ID'];

        foreach ($_amenities_title as $key => $val) :
            $count = count($_amenities_title);
            $selected_AMENITIES_TITLE = trim($_POST['amenities_title'][$key]);
            $selected_AMENITIES_TITLE = htmlspecialchars($selected_AMENITIES_TITLE, ENT_QUOTES, 'UTF-8');
            //$selected_AMENITIES_CODE = trim($_POST['amenities_code'][$key]);


            $selected_QTY = $_POST['amenities_qty'][$key];
            $selected_AVILABILITY_TYPE = $_POST['availability_type'][$key];
            $selected_START_TIME = $_POST['available_start_time'][$key];
            $selected_END_TIME = trim($_POST['available_end_time'][$key]);
            $selected_STATUS = $_POST['amenities_status'][$key];
            $selected_AMENITIES_ID = $_POST['hidden_amenities_ID'][$key];

            $selected_START_TIME_24_HRS_FORMAT = date("H:i:00", strtotime($selected_START_TIME));
            $selected_END_TIME_24_HRS_FORMAT = date("H:i:00", strtotime($selected_END_TIME));

            if (empty($selected_AMENITIES_TITLE)) :
                $errors['amenities_title_required'] = true;
            endif;
            /* if (empty($selected_AMENITIES_CODE)) :
                $errors['amenities_code_required'] = true;
            endif;*/
            if (empty($selected_QTY)) :
                $errors['amenities_qty_required'] = true;
            endif;
            if (empty($selected_AVILABILITY_TYPE)) :
                $errors['amenities_availability_type_required'] = true;
            endif;
            if ($selected_AVILABILITY_TYPE == 2) :
                if (empty($selected_START_TIME)) :
                    $errors['amenities_start_time_required'] = true;
                endif;
                if (empty($selected_END_TIME)) :
                    $errors['amenities_end_time_required'] = true;
                endif;
            endif;
            if ($selected_STATUS == '') :
                if (empty($selected_STATUS)) :
                    $errors['amenities_status_required'] = true;
                endif;
            endif;

            if (!empty($errors)) :
                //error call
                $response['success'] = false;
                $response['errors'] = $errors;
            else :
                //success call		
                $response['success'] = true;

                if ($selected_AVILABILITY_TYPE == 1) :
                    $selected_START_TIME_24_HRS_FORMAT = '00:00:00';
                    $selected_END_TIME_24_HRS_FORMAT = '00:00:00';
                else :
                    $selected_START_TIME_24_HRS_FORMAT = $selected_START_TIME_24_HRS_FORMAT;
                    $selected_END_TIME_24_HRS_FORMAT = $selected_END_TIME_24_HRS_FORMAT;
                endif;

                /*if ($selected_AMENITIES_ID != '' && $selected_AMENITIES_ID != 0) :
                    $selected_AMENITIES_CODE = getAMENITYDETAILS($selected_AMENITIES_ID, 'amenity_code');
                else :
                    // $selected_AMENITIES_CODE = get_AMENTITES_CODE($hidden_hotel_ID, $selected_room_type_id, $selected_AMENITIES_TITLE); 
                    $selected_AMENITIES_CODE = $selected_AMENITIES_CODE;
                endif;*/


                if ($selected_AMENITIES_ID != '' && $selected_AMENITIES_ID != 0) :

                    $amenities_arrFields = array('`hotel_id`', '`amenities_title`',  '`quantity`', '`availability_type`', '`start_time`', '`end_time`', '`createdby`', '`status`');

                    $amenities_arrValues = array("$hidden_hotel_ID", "$selected_AMENITIES_TITLE",  "$selected_QTY", "$selected_AVILABILITY_TYPE", "$selected_START_TIME_24_HRS_FORMAT", "$selected_END_TIME_24_HRS_FORMAT", "$logged_user_id", "$selected_STATUS");

                    $amenities_sqlwhere = " `hotel_amenities_id` = '$selected_AMENITIES_ID' ";
                    //UPDATE AMENITIES DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_hotel_amenities", $amenities_arrFields, $amenities_arrValues, $amenities_sqlwhere)) :
                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'hotel.php?route=add&formtype=hotel_pricebook&id=' . $hidden_hotel_ID;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;

                else :

                    //Amenity code Generation        
                    $collect_hotel_amenity_count = sqlQUERY_LABEL("SELECT `amenities_code`FROM `dvi_hotel_amenities` WHERE `deleted` = '0'   ORDER BY `hotel_amenities_id` DESC LIMIT 1") or die("#1-collect_hotel_amenities_count: " . sqlERROR_LABEL());

                    if (sqlNUMOFROW_LABEL($collect_hotel_amenity_count) > 0) :
                        while ($collect_data = sqlFETCHARRAY_LABEL($collect_hotel_amenity_count)) :
                            $amenities_code = $collect_data['amenities_code'];
                        endwhile;
                        $amenities_code++;
                    else :
                        $amenities_title_prefix = substr($amenities_title, 0, 3);
                        $randomNumber = mt_rand(1, 1000000);
                        $amenities_code = 'DVIAMEN' . $amenities_title_prefix . $randomNumber;
                    endif;

                    $amenities_code = strtoupper($amenities_code);

                    $amenities_arrFields = array('`hotel_id`', '`amenities_title`', '`amenities_code`', '`quantity`', '`availability_type`', '`start_time`', '`end_time`', '`createdby`', '`status`');

                    $amenities_arrValues = array("$hidden_hotel_ID", "$selected_AMENITIES_TITLE", "$amenities_code",  "$selected_QTY", "$selected_AVILABILITY_TYPE", "$selected_START_TIME_24_HRS_FORMAT", "$selected_END_TIME_24_HRS_FORMAT", "$logged_user_id", "$selected_STATUS");


                    //INSERT AMENITIES DETAILS
                    if (sqlACTIONS("INSERT", "dvi_hotel_amenities", $amenities_arrFields, $amenities_arrValues, '')) :
                        $response['i_result'] = true;
                        $response['redirect_URL'] = 'hotel.php?route=add&formtype=hotel_pricebook&id=' . $hidden_hotel_ID;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;
                endif;
            endif;
        endforeach;

        echo json_encode($response);

    elseif ($_GET['type'] == 'confirm_amenities_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];
        $_HOT_ID = $_POST['_HOT_ID'];

        $amenities_arrFields = array('`deleted`');
        $amenities_arrValues = array("1");
        $amenities_sqlwhere = " `hotel_amenities_id` = '$_ID'";

        if (sqlACTIONS("UPDATE", "dvi_hotel_amenities", $amenities_arrFields, $amenities_arrValues, $amenities_sqlwhere)) :

            $delete_LIST_HOTELAMENITITYPRICEBOOK = sqlQUERY_LABEL("DELETE FROM `dvi_hotel_amenities_price_book` WHERE `hotel_id` = '$_HOT_ID' and `hotel_amenities_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    //DELETE OPERATION - CONDITION BASED

    elseif ($_GET['type'] == 'hotel_delete') :

        $ID = $_GET['ID'];

        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

        // $select_hotel_listroom_id_already_used = sqlQUERY_LABEL("SELECT `room_ID` FROM `dvi_hotel_rooms` WHERE `status` = '1' and `hotel_id` = '$ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // $get_hotel_listroom_id_already_used_count = sqlNUMOFROW_LABEL($select_hotel_listroom_id_already_used);

        // $select_hotel_listamenities_id_already_used = sqlQUERY_LABEL("SELECT `hotel_amenities_id` FROM `dvi_hotel_amenities` WHERE `status` = '1' and `hotel_id` = '$ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // $get_hotel_listamenities_id_already_used_count = sqlNUMOFROW_LABEL($select_hotel_listamenities_id_already_used);

        // $select_hotel_listhotelpricebook_id_already_used = sqlQUERY_LABEL("SELECT `hotel_price_book_id` FROM `dvi_hotel_room_price_book` WHERE `status` = '1' and `hotel_id` = '$ID' AND `deleted` = '0';") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // $get_hotel_listhotelpricebook_id_already_used_count = sqlNUMOFROW_LABEL($select_hotel_listhotelpricebook_id_already_used);

        // $select_hotel_listhotelamenitypricebook_id_already_used = sqlQUERY_LABEL("SELECT `hotel_amenities_price_book_id` FROM `dvi_hotel_amenities_price_book` WHERE `status` = '1' and `hotel_id` = '$ID' AND `deleted` = '0';") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // $get_hotel_listhotelamenitypricebook_id_already_used_count = sqlNUMOFROW_LABEL($select_hotel_listhotelamenitypricebook_id_already_used);

?>
        <div class="modal-body">
            <div class="row">
                <?php if ($get_hotel_listroom_id_already_used_count == 0 && $get_hotel_listamenities_id_already_used_count == 0 && $select_hotel_listhotelpricebook_id_already_used == 0  && $select_hotel_listhotelamenitypricebook_id_already_used == 0) : ?>
                    <div class="text-center">
                        <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                    <p class="text-center">Do you really want to delete these record? <br />This process cannot be undone.<br>This
                        process includes deletion of rooms,amenities and price against the hotel.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" onclick="confirmHOTELDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                    </div>
                <?php else :  ?>
                    <!-- <div class="text-center">
                        <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this hotel.</h6>
                    <p class="text-center"> Since its assigned to specific Room Or Amenities.<br>This
                        process includes deletion of rooms,amenities and price against the hotel</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div> -->
                <?php endif;  ?>
            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_hotel_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_LIST_HOTEL = sqlQUERY_LABEL("UPDATE `dvi_hotel` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotel_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_LIST_HOTELROOM = sqlQUERY_LABEL("UPDATE `dvi_hotel_rooms` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotel_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_LIST_HOTELROOMPRICEBOOK = sqlQUERY_LABEL("DELETE FROM `dvi_hotel_room_price_book` WHERE `hotel_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_LIST_HOTELMEALPRICEBOOK = sqlQUERY_LABEL("DELETE FROM `dvi_hotel_meal_price_book` WHERE `hotel_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_LIST_HOTELAMENITIES = sqlQUERY_LABEL("UPDATE `dvi_hotel_amenities` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotel_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_LIST_HOTELAMENITIESPRICEBOOK = sqlQUERY_LABEL("DELETE FROM `dvi_hotel_amenities_price_book` WHERE `hotel_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_LIST_HOTELROOMGALLERY = sqlQUERY_LABEL("UPDATE `dvi_hotel_room_gallery_details` SET `deleted`='1' WHERE `hotel_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        if ($delete_LIST_HOTEL) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_room_price') :

        $errors = [];
        $response = [];

        $_HOT_ID = $_POST['_HOT_ID'];
        $_ROOM_ID = $_POST['_ROOM_ID'];
        $_RATE_Y = $_POST['_RATE_Y'];
        $_RATE_M = $_POST['_RATE_M'];
        $_RATE_D = $_POST['_RATE_D'];
        $_ROOM_RATE = $_POST['_ROOM_RATE'];

        //SANITIZE
        $sanitize_hotel_id = $validation_globalclass->sanitize($_HOT_ID);
        $sanitize_room_id = $validation_globalclass->sanitize($_ROOM_ID);
        $sanitize_rate_year = $validation_globalclass->sanitize($_RATE_Y);
        $sanitize_rate_month =  $validation_globalclass->sanitize($_RATE_M);
        $sanitize_rate_day = $validation_globalclass->sanitize($_RATE_D);
        $sanitize_room_rate = $validation_globalclass->sanitize($_ROOM_RATE);

        $room_rate_for_the_day_num_rows = getROOM_PRICEBOOK_DETAILS($sanitize_hotel_id, $sanitize_room_id, $sanitize_rate_year, $sanitize_rate_month, $sanitize_rate_day, 'room_rate_for_the_day_num_rows');

        if ($room_rate_for_the_day_num_rows == 0) :
            $arrFields = array('`hotel_id`', '`room_id`', '`year`', '`month`', "`$sanitize_rate_day`", '`createdby`', '`status`');
            $arrValues = array("$sanitize_hotel_id", "$sanitize_room_id", "$sanitize_rate_year", "$sanitize_rate_month", "$sanitize_room_rate", "$logged_user_id", '1');

            if (sqlACTIONS("INSERT", "dvi_hotel_room_price_book", $arrFields, $arrValues, '')) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        else :
            $arrFields = array("`$sanitize_rate_day`");
            $arrValues = array("$sanitize_room_rate");
            $sqlwhere = " `hotel_id` = '$sanitize_hotel_id' and `room_id` = '$sanitize_room_id' and `year` = '$sanitize_rate_year' and `month` = '$sanitize_rate_month' and `status` = '1' and `deleted` = '0'";

            if (sqlACTIONS("UPDATE", "dvi_hotel_room_price_book", $arrFields, $arrValues, $sqlwhere)) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_amenities_price') :

        $errors = [];
        $response = [];

        $_HOT_ID = $_POST['_HOT_ID'];
        $_AMENITIES_ID = $_POST['_AMENITIES_ID'];
        $_RATE_Y = $_POST['_RATE_Y'];
        $_RATE_M = $_POST['_RATE_M'];
        $_RATE_D = $_POST['_RATE_D'];
        $_AMENITIE_DAY_RATE = $_POST['_AMENITIE_DAY_RATE'];
        $_AMENITIE_HOUR_RATE = $_POST['_AMENITIE_HOUR_RATE'];

        //SANITIZE
        $sanitize_hotel_id = $validation_globalclass->sanitize($_HOT_ID);
        $sanitize_amenities_id = $validation_globalclass->sanitize($_AMENITIES_ID);
        $sanitize_rate_year = $validation_globalclass->sanitize($_RATE_Y);
        $sanitize_rate_month =  $validation_globalclass->sanitize($_RATE_M);
        $sanitize_rate_day = $validation_globalclass->sanitize($_RATE_D);
        $sanitize_amenities_day_rate = $validation_globalclass->sanitize($_AMENITIE_DAY_RATE);
        $sanitize_amenities_hour_rate = $validation_globalclass->sanitize($_AMENITIE_HOUR_RATE);

        $amenities_rate_for_the_day_num_rows = getAMENITIES_PRICEBOOK_DETAILS($sanitize_hotel_id, $sanitize_amenities_id, $sanitize_rate_year, $sanitize_rate_month, $sanitize_rate_day, 'amenities_rate_for_the_day_num_rows');

        $amenities_rate_for_the_hour_num_rows = getAMENITIES_PRICEBOOK_DETAILS($sanitize_hotel_id, $sanitize_amenities_id, $sanitize_rate_year, $sanitize_rate_month, $sanitize_rate_day, 'amenities_rate_for_the_hour_num_rows');

        //AMENITIES PER DAY COST
        if ($amenities_rate_for_the_day_num_rows == 0) :
            $arrFields = array('`hotel_id`', '`hotel_amenities_id`', '`year`', '`month`', "`$sanitize_rate_day`", '`pricetype`', '`createdby`', '`status`');
            $arrValues = array("$sanitize_hotel_id", "$sanitize_amenities_id", "$sanitize_rate_year", "$sanitize_rate_month", "$sanitize_amenities_day_rate", "1", "$logged_user_id", '1');

            if (sqlACTIONS("INSERT", "dvi_hotel_amenities_price_book", $arrFields, $arrValues, '')) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        else :
            $arrFields = array("`$sanitize_rate_day`");
            $arrValues = array("$sanitize_amenities_day_rate");
            $sqlwhere = " `pricetype` = '1' and `hotel_id` = '$sanitize_hotel_id' and `hotel_amenities_id` = '$sanitize_amenities_id' and `year` = '$sanitize_rate_year' and `month` = '$sanitize_rate_month' and `status` = '1' and `deleted` = '0'";

            if (sqlACTIONS("UPDATE", "dvi_hotel_amenities_price_book", $arrFields, $arrValues, $sqlwhere)) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        //AMENITIES PER HOUR COST
        if ($amenities_rate_for_the_hour_num_rows == 0) :
            $arrFields = array('`hotel_id`', '`hotel_amenities_id`', '`year`', '`month`', "`$sanitize_rate_day`", '`pricetype`', '`createdby`', '`status`');
            $arrValues = array("$sanitize_hotel_id", "$sanitize_amenities_id", "$sanitize_rate_year", "$sanitize_rate_month", "$sanitize_amenities_hour_rate", "2", "$logged_user_id", '1');

            if (sqlACTIONS("INSERT", "dvi_hotel_amenities_price_book", $arrFields, $arrValues, '')) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        else :
            $arrFields = array("`$sanitize_rate_day`");
            $arrValues = array("$sanitize_amenities_hour_rate");
            $sqlwhere = " `pricetype` = '2' and `hotel_id` = '$sanitize_hotel_id' and `hotel_amenities_id` = '$sanitize_amenities_id' and `year` = '$sanitize_rate_year' and `month` = '$sanitize_rate_month' and `status` = '1' and `deleted` = '0'";

            if (sqlACTIONS("UPDATE", "dvi_hotel_amenities_price_book", $arrFields, $arrValues, $sqlwhere)) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotel_review') :

        $errors = [];
        $response = [];

        $_hotel_rating = $_POST['hotel_rating'];
        $_hotel_description = $_POST['review_description'];
        $hidden_hotel_ID = $_POST['hiddenHOTEL_ID'];
        $hidden_hotel_review_ID = $_POST['hidden_hotel_review_id'];

        // if (empty($_hotel_rating)) :
        //     $errors['hotel_rating_required'] = true;
        // endif;
        // if (empty($_hotel_description)) :
        //     $errors['review_description_required'] = true;
        // endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`hotel_id`', '`hotel_rating`', '`hotel_description`', '`createdby`', '`status`');
            $arrValues = array("$hidden_hotel_ID", "$_hotel_rating", "$_hotel_description", "$logged_user_id", "1");

            if ($hidden_hotel_review_ID != '' && $hidden_hotel_review_ID != 0) :
                $sqlWhere = " `hotel_review_id` = '$hidden_hotel_review_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_hotel_review_details", $arrFields, $arrValues, $sqlWhere)) :
                    $response['hotel_id'] = $hidden_hotel_ID;
                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_hotel_review_details", $arrFields, $arrValues, '')) :
                    $response['hotel_id'] = $hidden_hotel_ID;
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'changestatus') :

        $errors = [];
        $response = [];

        $hotel_ID = $_GET['hotel_ID'];
        $oldstatus = $_GET['oldstatus'];

        if ($oldstatus == '1') :
            $status = '0';
        elseif ($oldstatus == '0') :
            $status = '1';
        endif;

        //Update query
        $arrFields = array('`status`');

        $arrValues = array("$status");

        $sqlWhere = " `hotel_id` = '$hotel_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_hotel", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);


    elseif ($_GET['type'] == 'deleted_review') :

        $errors = [];
        $response = [];

        $HOTEL_REVIEW_ID = $_POST['ID'];



        //SANITIZE
        // $HOTEL_ID = $validation_globalclass->sanitize($HOTEL_ID);
        // $REVIEW_ID = $validation_globalclass->sanitize($REVIEW_ID);

        $arrFields = array('`deleted`');
        $arrValues = array("1");
        $sqlwhere = " `hotel_review_id` = '$HOTEL_REVIEW_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_hotel_review_details", $arrFields, $arrValues, $sqlwhere)) :
            //CATEGORY STATUS CHANGED TO ACTIVE / INACTIVE MODE
            $response['result'] = true;
        else :
            $response['success'] = false;
            $response['response_error'] = true;
        endif;

        echo json_encode($response);


    elseif ($_GET['type'] == 'hotel_review_delete') :
        $ID = $_GET['ID'];

        // $REVIEW = $_GET['REVIEW'];

        //SANITIZE
        // $ID = $validation_globalclass->sanitize($ID);
        // $REVIEW = $validation_globalclass->sanitize($REVIEW);
    ?>
        <div class="modal-body">
            <div class="row">

                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmHOTELREVIEWDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>

<?php
    endif;
else :
    echo "Request Ignored";
endif;
