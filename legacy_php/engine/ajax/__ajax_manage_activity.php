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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'activity_basic_info') :

        $errors = [];
        $response = [];

        $_activity_name = trim($_POST['activity_name']);
        $_hotspot_place_id = $_POST['hotspot_place'];
        $_max_allowed_person_count = $_POST['max_allowed_person_count'];
        $_duration_activity = $_POST['duration_activity'];
        $_activity_description = trim($_POST['activity_description']);
        //DEFAULT TIME SLOTS
        $_default_activity_start_time = $_POST['default_activity_start_time'];
        $_default_activity_end_time = $_POST['default_activity_end_time'];

        //SPECIAL DATE AND TIME SLOTS
        $_special_day_checked = $_POST['special_day'];

        if ($_special_day_checked == 1) :
            $_specialday_date_input = $_POST['specialday_date_input'];
            $_specialday_start_time = $_POST['specialday_start_time'];
            $_specialday_end_time = $_POST['specialday_end_time'];
        endif;

        $hidden_activity_ID = $_POST['hidden_activity_ID'];

        foreach ($_FILES['activity_image_upload']['error'] as $error) {
            if ($error === UPLOAD_ERR_NO_FILE) {
                $image_selected = false;
            } else {
                $image_selected = true;
            }
        }

        if ($hidden_activity_ID == "") :
            if (empty($image_selected)) :
                $errors['activity_image_required'] = true;
            endif;
        endif;

        if (empty($_activity_name)) :
            $errors['activity_name_required'] = true;
        endif;
        if (empty($_hotspot_place_id)) :
            $errors['hotspot_place_required'] = true;
        endif;
        if (empty($_max_allowed_person_count)) :
            $errors['max_allowed_person_count_required'] = true;
        endif;

        if (empty($_duration_activity)) :
            $errors['duration_activity_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields_activity = array('`activity_title`', '`hotspot_id`', '`max_allowed_person_count`', '`activity_duration`', '`activity_description`', '`createdby`', '`status`');

            $arrValues_activity = array("$_activity_name", "$_hotspot_place_id", "$_max_allowed_person_count",  "$_duration_activity", "$_activity_description", "$logged_user_id", "1");

            if ($hidden_activity_ID != '' && $hidden_activity_ID != 0) :
                $sqlWhere_activity = " `activity_id` = '$hidden_activity_ID' ";
                //UPDATE ACTIVITY DETAILS
                if (sqlACTIONS("UPDATE", "dvi_activity", $arrFields_activity, $arrValues_activity, $sqlWhere_activity)) :

                    //INSERT NEWLY UPLOADED ACTIVITY IMAGES
                    if (isset($_FILES['activity_image_upload']) && !empty($_FILES['activity_image_upload']['name'][0])) {

                        for ($j = 0; $j < count($_FILES['activity_image_upload']['name']); $j++) :
                            $uploadDir = '../../uploads/activity_gallery';
                            $tmpFile = $_FILES['activity_image_upload']['tmp_name'][$j];
                            $imageInfo = getimagesize($tmpFile);
                            if ($imageInfo !== false) :
                                $activity_image = 'activity_image_' . $j . "_" . date('ymdhis') . '_.png';
                                $filename = $uploadDir . '/' . $activity_image;


                                // Move the uploaded file to the folder
                                if (isset($tmpFile) && isset($filename)) :
                                    if (move_uploaded_file($tmpFile, $filename)) :

                                        $arrFields_img = array(
                                            '`activity_id`', '`activity_image_gallery_name`',  '`createdby`', '`status`'
                                        );

                                        $arrValues_img = array(
                                            "$hidden_activity_ID", "$activity_image",  "$logged_user_id", "1"
                                        );

                                        //INSERT ACTIVITY IMAGE DETAILS
                                        if (sqlACTIONS(
                                            "INSERT",
                                            "dvi_activity_image_gallery_details",
                                            $arrFields_img,
                                            $arrValues_img,
                                            ''
                                        )) :
                                        endif;
                                    else :
                                    //$flie_uploaded = false;
                                    endif;
                                endif;
                            else :
                            //File is not an Image
                            //$flie_uploaded = false;
                            endif;
                        endfor;
                    }

                    //DELETE EXISTING TIMESLOTS
                    if ($hidden_activity_ID) :
                        $sqlWhere_time_slots = " `activity_id` = '$hidden_activity_ID' ";
                        $delete_previous_time_slot_details = sqlACTIONS("DELETE", "dvi_activity_time_slot_details", '', '', $sqlWhere_time_slots);
                    endif;

                    //INSERT TIME SLOTS
                    for ($j = 0; $j < count($_default_activity_start_time); $j++) :
                        $time_slot_type = 1;

                        $arrFields_default_timeslots = array(
                            '`activity_id`', '`time_slot_type`', '`start_time`', '`end_time`', '`createdby`', '`status`'
                        );

                        $start_time = date('H:i:s', strtotime($_default_activity_start_time[$j]));
                        $end_time = date('H:i:s', strtotime($_default_activity_end_time[$j]));

                        $arrValues_default_timeslots = array(
                            "$hidden_activity_ID", "$time_slot_type", "$start_time", "$end_time", "$logged_user_id", "1"
                        );

                        //INSERT 
                        if (sqlACTIONS("INSERT", "dvi_activity_time_slot_details", $arrFields_default_timeslots, $arrValues_default_timeslots, '')) :
                        endif;

                    endfor;

                    if ($_special_day_checked == 1) :

                        for ($j = 0; $j < count($_specialday_start_time); $j++) :
                            $time_slot_type = 2;

                            if ($_specialday_date_input[$j] != "") :
                                $_specialday_date = date('Y-m-d', strtotime($_specialday_date_input[$j]));
                            else :
                                $_specialday_date = $prev_specialday_date_input;
                            endif;

                            $start_time = date('H:i:s', strtotime($_specialday_start_time[$j]));
                            $end_time = date('H:i:s', strtotime($_specialday_end_time[$j]));

                            $arrFields_special_timeslots = array(
                                '`activity_id`', '`time_slot_type`', '`special_date`', '`start_time`', '`end_time`', '`createdby`', '`status`'
                            );

                            $arrValues_special_timeslots = array(
                                "$hidden_activity_ID", "$time_slot_type", "$_specialday_date", "$start_time", "$end_time", "$logged_user_id", "1"
                            );

                            //INSERT 
                            if (sqlACTIONS("INSERT", "dvi_activity_time_slot_details", $arrFields_special_timeslots, $arrValues_special_timeslots, '')) :
                            endif;
                            $prev_specialday_date_input = $_specialday_date;
                        endfor;

                    endif;

                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'activitydetails.php?route=edit&formtype=activity_price_book&id=' . $hidden_activity_ID;
                    $response['result_success'] = true;

                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT ACTIVITY DETAILS
                if (sqlACTIONS("INSERT", "dvi_activity", $arrFields_activity, $arrValues_activity, '')) :
                    $activity_id = sqlINSERTID_LABEL();

                    //INSERT ACTIVITY IMAGES
                    for ($j = 0; $j < count($_FILES['activity_image_upload']['name']); $j++) :
                        $uploadDir = '../../uploads/activity_gallery';
                        $tmpFile = $_FILES['activity_image_upload']['tmp_name'][$j];
                        $imageInfo = getimagesize($tmpFile);
                        if ($imageInfo !== false) :
                            $activity_image = 'activity_image_' . $j . "_" . date('ymdhis') . '_.png';
                            $filename = $uploadDir . '/' . $activity_image;


                            // Move the uploaded file to the folder
                            if (isset($tmpFile) && isset($filename)) :
                                if (move_uploaded_file($tmpFile, $filename)) :

                                    $arrFields_img = array(
                                        '`activity_id`', '`activity_image_gallery_name`',  '`createdby`', '`status`'
                                    );

                                    $arrValues_img = array(
                                        "$activity_id", "$activity_image",  "$logged_user_id", "1"
                                    );

                                    //INSERT ACTIVITY IMAGE DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_activity_image_gallery_details", $arrFields_img, $arrValues_img, '')) :
                                    endif;
                                else :
                                //$flie_uploaded = false;
                                endif;
                            endif;
                        else :
                        //File is not an Image
                        //$flie_uploaded = false;
                        endif;
                    endfor;

                    //INSERT TIME SLOTS
                    for ($j = 0; $j < count($_default_activity_start_time); $j++) :
                        $time_slot_type = 1;

                        $arrFields_default_timeslots = array(
                            '`activity_id`', '`time_slot_type`', '`start_time`', '`end_time`', '`createdby`', '`status`'
                        );

                        $start_time = date('H:i:s', strtotime($_default_activity_start_time[$j]));
                        $end_time = date('H:i:s', strtotime($_default_activity_end_time[$j]));

                        $arrValues_default_timeslots = array(
                            "$activity_id", "$time_slot_type", "$start_time", "$end_time", "$logged_user_id", "1"
                        );

                        //INSERT 
                        if (sqlACTIONS("INSERT", "dvi_activity_time_slot_details", $arrFields_default_timeslots, $arrValues_default_timeslots, '')) :
                        endif;

                    endfor;

                    if ($_special_day_checked == 1) :

                        for ($j = 0; $j < count($_specialday_start_time); $j++) :
                            $time_slot_type = 2;

                            if ($_specialday_date_input[$j] != "") :
                                $_specialday_date = date('Y-m-d', strtotime($_specialday_date_input[$j]));
                            else :
                                $_specialday_date = $prev_specialday_date_input;
                            endif;

                            $arrFields_special_timeslots = array(
                                '`activity_id`', '`time_slot_type`', '`special_date`', '`start_time`', '`end_time`', '`createdby`', '`status`'
                            );

                            $start_time = date('H:i:s', strtotime($_specialday_start_time[$j]));
                            $end_time = date('H:i:s', strtotime($_specialday_end_time[$j]));

                            $arrValues_special_timeslots = array(
                                "$activity_id", "$time_slot_type", "$_specialday_date", "$start_time", "$end_time", "$logged_user_id", "1"
                            );

                            //INSERT 
                            if (sqlACTIONS("INSERT", "dvi_activity_time_slot_details", $arrFields_special_timeslots, $arrValues_special_timeslots, '')) :
                            endif;
                            $prev_specialday_date_input = $_specialday_date;
                        endfor;

                    endif;

                    $response['i_result'] = true;
                    $response['result_success'] = true;
                    $response['redirect_URL'] = 'activitydetails.php?route=add&formtype=activity_price_book&id=' . $activity_id;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'activity_pricebook') :

        $errors = [];
        $response = [];
        $_hotspot_id = $_POST['hotspot'];
        $hidden_activity_ID = $_POST['hidden_activity_ID'];
        $_indian_nationality = $_POST['indian_nationality'];
        $_nonindian_nationality = $_POST['nonindian_nationality'];
        $_selectstartdate = $_POST['selectstartdate'];
        $_selectenddate = $_POST['selectenddate'];

        if (empty($_hotspot_id)) :
            $errors['hotspot_required'] = true;
        endif;
        if (empty($hidden_activity_ID)) :
            $errors['activity_required'] = true;
        endif;
        if (empty($_selectstartdate)) :
            $errors['selectstartdate_required'] = true;
        endif;
        if (empty($_selectenddate)) :
            $errors['selectenddate_required'] = true;
        endif;

        if ($_indian_nationality == '1') :
            $indian_cost = array();

            $_adult_cost = $_POST['adult_cost'];
            $_child_cost = $_POST['child_cost'];
            $_infant_cost = $_POST['infant_cost'];

            $indian_cost[1] = $_adult_cost;
            $indian_cost[2] = $_child_cost;
            $indian_cost[3] = $_infant_cost;

        endif;

        if ($_nonindian_nationality == '2') :
            $foreigner_cost = array();

            $_foreign_adult_cost = $_POST['foreign_adult_cost'];
            $_foreign_child_cost = $_POST['foreign_child_cost'];
            $_foreign_infant_cost = $_POST['foreign_infant_cost'];
            $foreigner_cost[1] = $_foreign_adult_cost;
            $foreigner_cost[2] = $_foreign_child_cost;
            $foreigner_cost[3] = $_foreign_infant_cost;

        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;

        else :
            //success call		

            $response['success'] = true;

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
                $start_date_of_month = date('Y-m-d', $currentDate);
                if ($endDateMonth != $currentMonth) :
                    $end_day_of_month = (int)date('t', $currentDate);
                    $end_date_of_month = date('Y-m-t', $currentDate);
                else :
                    $end_day_of_month = (int)date('d', $endDate);
                    $end_date_of_month = date('Y-m-d', $endDate);
                endif;

                if ($_indian_nationality == '1') : //INDIAN OR BOTH

                    //PRICE TYPE  1 -ADULT | 2- CHILD | 3- INFANT
                    for ($_price_type = 1; $_price_type <= 3; $_price_type++) :

                        if ($indian_cost[$_price_type] != '') :

                            $activity_price = $indian_cost[$_price_type];
                            //CHECK PRICE DETAILS ALREADY EXISTING
                            $check_price_already_existing = sqlQUERY_LABEL("SELECT `activity_price_book_id` FROM `dvi_activity_pricebook` WHERE `activity_id`='$hidden_activity_ID' AND `deleted`='0' AND  `price_type`='$_price_type' AND `year`='$currentYear' AND `month`='$currentMonthName' AND `nationality`='1' AND `hotspot_id`='$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                            if (sqlNUMOFROW_LABEL($check_price_already_existing) > 0) :
                                while ($fetch_price_row = sqlFETCHARRAY_LABEL($check_price_already_existing)) :
                                    $activity_price_book_id = $fetch_price_row['activity_price_book_id'];
                                endwhile;

                                if ($start_date_of_month != $end_date_of_month) :

                                    $dayValuesArray = array();
                                    $dayfieldsArray = array();

                                    for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
                                        $day_wise_val = 'day_' . $i;

                                        $dayfieldsArray[] = "`" . $day_wise_val . "`";
                                        $dayValuesArray[] =  $activity_price;
                                    }

                                    $arrStaticFields = array();
                                    $arrStaticvalues = array();

                                    $arrFields = array_merge($arrStaticFields, $dayfieldsArray);
                                    $arrValues = array_merge($arrStaticvalues, $dayValuesArray);

                                else :

                                    $day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

                                    $arrFields = array($day_wise_varaible);
                                    $arrValues = array("$activity_price");
                                endif;

                                $sqlWhere = " `activity_price_book_id` = '$activity_price_book_id' ";
                                //UPDATE DETAILS
                                if (sqlACTIONS("UPDATE", "dvi_activity_pricebook", $arrFields, $arrValues, $sqlWhere)) :
                                endif;

                            else :
                                //INSERT DETAILS
                                if ($start_date_of_month != $end_date_of_month) :

                                    $dayValuesArray = array();
                                    $dayfieldsArray = array();

                                    for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
                                        $day_wise_val = 'day_' . $i;

                                        $dayfieldsArray[] = "`" . $day_wise_val . "`";
                                        $dayValuesArray[] =  $activity_price;
                                    }

                                    $arrStaticFields = array('`hotspot_id`', '`activity_id`', '`nationality`', '`price_type`', '`year`', '`month`', '`createdby`', '`status`');
                                    $arrStaticvalues = array("$_hotspot_id", "$hidden_activity_ID", "1", "$_price_type", "$currentYear", "$currentMonthName", "$logged_user_id", "1");

                                    $arrFields = array_merge($arrStaticFields, $dayfieldsArray);
                                    $arrValues = array_merge($arrStaticvalues, $dayValuesArray);

                                else :

                                    $day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

                                    $arrFields = array('`hotspot_id`', '`activity_id`', '`nationality`', '`price_type`', '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');

                                    $arrValues = array("$_hotspot_id", "$hidden_activity_ID", "1", "$_price_type", "$currentYear", "$currentMonthName", "$activity_price", "$logged_user_id", "1");
                                endif;
                                //INSERT DETAILS
                                if (sqlACTIONS(
                                    "INSERT",
                                    "dvi_activity_pricebook",
                                    $arrFields,
                                    $arrValues,
                                    ''
                                )) :
                                endif;

                            endif;
                        endif;
                    endfor;
                endif;

                if ($_nonindian_nationality == '2') : //NON INDIAN OR BOTH

                    //PRICE TYPE  1 -ADULT | 2- CHILD | 3- INFANT
                    for ($_price_type = 1; $_price_type <= 3; $_price_type++) :

                        if ($foreigner_cost[$_price_type] != '') :

                            $activity_price = $foreigner_cost[$_price_type];
                            //CHECK PRICE DETAILS ALREADY EXISTING
                            $check_price_already_existing = sqlQUERY_LABEL("SELECT `activity_price_book_id` FROM `dvi_activity_pricebook` WHERE `activity_id`='$hidden_activity_ID' AND `deleted`='0' AND  `price_type`='$_price_type' AND `year`='$currentYear' AND `month`='$currentMonthName' AND `nationality`='2' AND `hotspot_id`='$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                            if (sqlNUMOFROW_LABEL($check_price_already_existing) > 0) :
                                while ($fetch_price_row = sqlFETCHARRAY_LABEL($check_price_already_existing)) :
                                    $activity_price_book_id = $fetch_price_row['activity_price_book_id'];
                                endwhile;

                                if ($start_date_of_month != $end_date_of_month) :

                                    $dayValuesArray = array();
                                    $dayfieldsArray = array();

                                    for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
                                        $day_wise_val = 'day_' . $i;

                                        $dayfieldsArray[] = "`" . $day_wise_val . "`";
                                        $dayValuesArray[] =  $activity_price;
                                    }

                                    $arrStaticFields = array();
                                    $arrStaticvalues = array();

                                    $arrFields = array_merge($arrStaticFields, $dayfieldsArray);
                                    $arrValues = array_merge($arrStaticvalues, $dayValuesArray);

                                else :

                                    $day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

                                    $arrFields = array($day_wise_varaible);
                                    $arrValues = array("$activity_price");
                                endif;

                                $sqlWhere = " `activity_price_book_id` = '$activity_price_book_id' ";
                                //UPDATE DETAILS
                                if (sqlACTIONS("UPDATE", "dvi_activity_pricebook", $arrFields, $arrValues, $sqlWhere)) :
                                endif;

                            else :
                                //INSERT DETAILS
                                if ($start_date_of_month != $end_date_of_month) :

                                    $dayValuesArray = array();
                                    $dayfieldsArray = array();

                                    for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
                                        $day_wise_val = 'day_' . $i;

                                        $dayfieldsArray[] = "`" . $day_wise_val . "`";
                                        $dayValuesArray[] =  $activity_price;
                                    }

                                    $arrStaticFields = array('`hotspot_id`', '`activity_id`', '`nationality`', '`price_type`', '`year`', '`month`', '`createdby`', '`status`');
                                    $arrStaticvalues = array("$_hotspot_id", "$hidden_activity_ID", "2", "$_price_type", "$currentYear", "$currentMonthName", "$logged_user_id", "1");

                                    $arrFields = array_merge($arrStaticFields, $dayfieldsArray);
                                    $arrValues = array_merge($arrStaticvalues, $dayValuesArray);

                                else :

                                    $day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

                                    $arrFields = array('`hotspot_id`', '`activity_id`', '`nationality`', '`price_type`', '`year`', '`month`', $day_wise_varaible, '`createdby`', '`status`');

                                    $arrValues = array("$_hotspot_id", "$hidden_activity_ID", "2", "$_price_type", "$currentYear", "$currentMonthName", "$activity_price", "$logged_user_id", "1");
                                endif;
                                //INSERT DETAILS
                                if (sqlACTIONS(
                                    "INSERT",
                                    "dvi_activity_pricebook",
                                    $arrFields,
                                    $arrValues,
                                    ''
                                )) :
                                endif;

                            endif;
                        endif;
                    endfor;
                endif;


                // Move to the next month and set the day to 1
                $currentDate = strtotime('+1 month', strtotime(date('01-m-Y', $currentDate)));

            endwhile;

            $response['u_result'] = true;
            $response['result_success'] = true;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'activity_feedback') :

        $errors = [];
        $response = [];

        $activity_rating = $validation_globalclass->sanitize($_POST['activity_rating']);
        $review_description = $validation_globalclass->sanitize($_POST['review_description']);
        $status = '1';
        $hidden_activity_review_id = $_POST['hidden_activity_review_id'];
        $hidden_activity_ID = $_POST['hidden_activity_ID'];

        if (empty($activity_rating)) :
            $errors['activity_rating_required'] = true;
        endif;
        if (empty($review_description)) :
            $errors['review_description_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;


            if ($hidden_activity_review_id != 0 && $hidden_activity_review_id != "") :

                $arrFields = array('`activity_rating`', '`activity_description`');

                $arrValues = array("$activity_rating", "$review_description");

                $sqlWhere_activity_review = " `activity_review_id` = '$hidden_activity_review_id' ";

                //UPDATE REVIEW DETAILS
                if (sqlACTIONS("UPDATE", "dvi_activity_review_details", $arrFields, $arrValues, $sqlWhere_activity_review)) :

                    $response['u_result'] = true;
                    $response['activity_id'] =  $hidden_activity_ID;
                    $response['result_success'] = true;
                    $response['redirect_URL'] = 'activitydetails.php?route=add&formtype=activity_feedback_review&id=' . $hidden_activity_ID;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;

            else :

                $arrFields = array('`activity_id`', '`activity_rating`', '`activity_description`', '`createdby`', '`status`');

                $arrValues = array("$hidden_activity_ID", "$activity_rating", "$review_description", "$logged_user_id", "$status");

                //INSERT FEEDBACK DETAILS
                if (sqlACTIONS("INSERT", "dvi_activity_review_details", $arrFields, $arrValues, '')) :

                    $response['i_result'] = true;
                    $response['result_success'] = true;
                    $response['activity_id'] =  $hidden_activity_ID;
                    $response['redirect_URL'] = 'activitydetails.php?route=add&formtype=activity_feedback_review&id=' . $hidden_activity_ID;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'activity_feedback_delete') :

        $ID = $_GET['ID'];

        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

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
                    <button type="submit" onclick="confirmRATINGDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_activity_feedback_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_LIST = sqlQUERY_LABEL("UPDATE `dvi_activity_review_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_review_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());
        if ($delete_LIST) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'activity_image_delete') :

        $ID = $_GET['ID'];

        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

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
                <p class="text-center">Do you really want to delete this Image? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmACTIVITYIMAGEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_activity_image_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_LIST = sqlQUERY_LABEL("UPDATE `dvi_activity_image_gallery_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_image_gallery_details_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());
        if ($delete_LIST) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_activity_status') :

        $errors = [];
        $response = [];

        $ACTIVITY_ID = $validation_globalclass->sanitize($_POST['ACTIVITY_ID']);
        $STATUS_ID = $validation_globalclass->sanitize($_POST['STATUS_ID']);

        if ($STATUS_ID == '1') :
            $status = '0';
        elseif ($STATUS_ID == '0') :
            $status = '1';
        endif;

        //Update query
        $arrFields = array('`status`');
        $arrValues = array("$status");
        $sqlWhere = " `activity_id` = '$ACTIVITY_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_activity", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'activity_delete') :

        $ID = $_GET['ID'];

        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

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
                <p class="text-center">Do you really want to delete this record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmACTIVITYDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_activity_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_activity = sqlQUERY_LABEL("UPDATE `dvi_activity` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        if ($delete_activity) :

            $delete_activity_images = sqlQUERY_LABEL("UPDATE `dvi_activity_image_gallery_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $delete_activity_timeslots = sqlQUERY_LABEL("UPDATE `dvi_activity_time_slot_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $delete_activity_reviews = sqlQUERY_LABEL("UPDATE `dvi_activity_review_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $delete_activity_pricebook = sqlQUERY_LABEL("UPDATE `dvi_activity_pricebook` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `activity_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());


            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
