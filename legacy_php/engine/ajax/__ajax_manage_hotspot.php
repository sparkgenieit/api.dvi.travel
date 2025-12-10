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

    if ($_GET['type'] == 'hotspot_info') :

        $errors = [];
        $response = [];

        /* print_r($_POST);
        exit; */

        $_hotspot_name = trim($_POST['hotspot_name']);
        $_hotspot_type = trim($_POST['hotspot_type']);
        $_hotspot_location = $_POST['hotspot_location'];
        $_hotspot_latitude = trim($_POST['hotspot_latitude']);
        $_hotspot_longitude = trim($_POST['hotspot_longitude']);

        $_hotspot_place_id = trim($_POST['hotspot_place_id']);
        $_hotspot_description = trim($_POST['hotspot_description']);
        $_hotspot_address = trim($_POST['hotspot_address']);
        $_hotspot_landmark = $_POST['hotspot_landmark'];
        $_hotspot_priority = $_POST['hotspot_priority'];
        $_hotspot_adult_entry_cost = trim($_POST['hotspot_adult_entry_cost']);
        $_hotspot_child_entry_cost = trim($_POST['hotspot_child_entry_cost']);
        $_hotspot_infant_entry_cost = trim($_POST['hotspot_infant_entry_cost']);
        $_hotspot_foreign_adult_entry_cost = trim($_POST['foreign_adult_entry_cost']);
        $_hotspot_foreign_child_entry_cost = trim($_POST['foreign_child_entry_cost']);
        $_hotspot_foreign_infant_entry_cost = trim($_POST['foreign_infant_entry_cost']);
        $_hotspot_duration = trim($_POST['hotspot_duration']);
        $_hotspot_rating = trim($_POST['hotspot_rating']);
        $_hotspot_video_url = trim($_POST['hotspot_video_url']);

        $_vehicle_parking_charge = $_POST['vehicle_parking_charge'];
        $_vehicle_type_id = $_POST['vehicle_type_id'];
        $_vehicle_parking_charge_ID = $_POST['vehicle_parking_charge_ID'];

        $hidden_hotspot_ID = $_POST['hidden_hotspot_ID'];

        if (empty($_hotspot_name)) :
            $errors['hotspot_name_required'] = true;
        endif;
        if (empty($_hotspot_type)) :
            $errors['hotspot_type_required'] = true;
        endif;
        if (empty($_hotspot_location)) :
            $errors['hotspot_location_required'] = true;
        endif;
        if (empty($_hotspot_latitude)) :
            $errors['hotspot_latitude_required'] = true;
        endif;
        if (empty($_hotspot_longitude)) :
            $errors['hotspot_longitude_required'] = true;
        endif;

        if (empty($_hotspot_description)) :
            $errors['hotspot_description_required'] = true;
        endif;
        if (empty($_hotspot_address)) :
            $errors['hotspot_address_required'] = true;
        endif;
        if (empty($_hotspot_landmark)) :
            $errors['hotspot_landmark_required'] = true;
        endif;
        if ($_hotspot_adult_entry_cost == '') :
            $errors['hotspot_adult_entry_cost_required'] = true;
        endif;
        if ($_hotspot_child_entry_cost == '') :
            $errors['hotspot_child_entry_cost_required'] = true;
        endif;
        if ($_hotspot_infant_entry_cost == '') :
            $errors['hotspot_infant_entry_cost_required'] = true;
        endif;

        if ($_hotspot_duration == '') :
            $errors['hotspot_duration_required'] = true;
        endif;
        if ($_hotspot_video_url == '') :
            $errors['hotspot_video_url_required'] = true;
        endif;


        if ($hidden_hotspot_ID == '') :
            if ($_FILES['hotspot_gallery']['name'] == '') :
                $errors['hotspot_photo_url_required'] = true;
            endif;
        endif;

        $operatingHours = isset($_POST['operating_hours']) ? $_POST['operating_hours'] : [];

        $_hotspot_location = implode('|', $_hotspot_location);

        // Array to store unique timings for each day
        $uniqueTimings = [];

        /* foreach ($operatingHours as $day => $timings) {
            $uniqueTimings[$day] = [];

            if (is_array($timings)) { // Check if $timings is an array
                foreach ($timings as $timing) {
                    // Check if $timing has the required keys
                    if (isset($timing['start'], $timing['end'])) {
                        // Check if the timing is unique for the day
                        $start = $timing['start'];
                        $end = $timing['end'];

                        $key = $start . '-' . $end;

                        // Check for duplicate timing
                        if (!in_array($key, $uniqueTimings[$day])) {
                            // Check for overlapping time
                            $overlap = false;
                            foreach ($uniqueTimings[$day] as $existingTiming) {
                                list($existingStart, $existingEnd) = explode('-', $existingTiming);

                                if (($start >= $existingStart && $start < $existingEnd) || ($end > $existingStart && $end <= $existingEnd)) {
                                    // Overlapping time found
                                    $overlap = true;
                                    $errors['hotspot_overlapping_duration_found'] = "Overlapping timing found for $day: $start - $end";
                                    break;
                                }
                            }

                            if (!$overlap) {
                                // Add the unique timing to the array
                                $uniqueTimings[$day][] = $key;
                            }
                        } else {
                            // Handle the case where a duplicate timing is found
                            $errors['hotspot_duplicate_duration_found'] = "Duplicate timing found for $day: $start - $end";
                            break;
                        }
                    } else {
                        // Handle case where required keys are missing
                        $errors['missing_timing_keys'] = "Missing timing keys for $day";
                        break;
                    }
                }
            } else {
                // Handle case where $timings is not an array
                $errors['invalid_timings_array'] = "Invalid timings array for $day";
            }
        } */

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($_hotspot_duration != '') :
                $_hotspot_duration = date("H:i:s", strtotime($_hotspot_duration));
            else :
                $_hotspot_duration = "01:00:00";
            endif;

            $arrFields = array('`hotspot_type`', '`hotspot_name`', '`hotspot_description`', '`hotspot_address`', '`hotspot_landmark`', '`hotspot_location`', '`hotspot_priority`', '`hotspot_adult_entry_cost`', '`hotspot_child_entry_cost`', '`hotspot_infant_entry_cost`', '`hotspot_foreign_adult_entry_cost`', '`hotspot_foreign_child_entry_cost`', '`hotspot_foreign_infant_entry_cost`', '`hotspot_duration`',  '`hotspot_rating`', '`hotspot_latitude`', '`hotspot_longitude`', '`hotspot_video_url`', '`createdby`', '`status`');

            $arrValues = array("$_hotspot_type", "$_hotspot_name", "$_hotspot_description", "$_hotspot_address", "$_hotspot_landmark", "$_hotspot_location", "$_hotspot_priority", "$_hotspot_adult_entry_cost", "$_hotspot_child_entry_cost", "$_hotspot_infant_entry_cost", "$_hotspot_foreign_adult_entry_cost", "$_hotspot_foreign_child_entry_cost", "$_hotspot_foreign_infant_entry_cost", "$_hotspot_duration",  "$_hotspot_rating", "$_hotspot_latitude", "$_hotspot_longitude", "$_hotspot_video_url", "$logged_user_id", "1");

            if ($hidden_hotspot_ID != '' && $hidden_hotspot_ID != 0) :

                $sqlWhere = " `hotspot_ID` = '$hidden_hotspot_ID' ";

                //HOTSPOT GALLERY
                $hotspot_gallery_array = $_FILES['hotspot_gallery']['name'];

                if (isset($hotspot_gallery_array)) :
                    foreach ($hotspot_gallery_array as $key => $val) :
                        $upload_dir = '../../uploads/hotspot_gallery/';
                        $filetype = end(explode('.', $_FILES['hotspot_gallery']['name'][$key]));
                        $file_name = $_FILES['hotspot_gallery']['name'][$key];
                        $file_name = trim($file_name);
                        $file_type = $_FILES['hotspot_gallery']['type'][$key];
                        $file_temp_loc  = $_FILES['hotspot_gallery']['tmp_name'][$key];
                        $file_error_msg = $_FILES['hotspot_gallery']['error'][$key];
                        $file_size = $_FILES['hotspot_gallery']['size'][$key];
                        $move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);

                        if ($move_file) :
                            $arrFields_gallery = array('`hotspot_ID`', '`hotspot_gallery_name`', '`createdby`', '`status`');
                            $arrValues_gallery = array("$hidden_hotspot_ID", "$file_name", "$logged_user_id", "1");
                            if (sqlACTIONS("INSERT", "dvi_hotspot_gallery_details", $arrFields_gallery, $arrValues_gallery, '')) :
                            //SUCCESS
                            endif;
                        endif;
                    endforeach;
                endif;

                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_hotspot_place", $arrFields, $arrValues, $sqlWhere)) :

                    $operating_hours = $_POST['operating_hours'];

                    foreach ($operating_hours as $day_text => $day_data) {
                        // Determine the day index
                        $hotspot_timing_day = array_search($day_text, array_keys($operating_hours));

                        // Check if open 24hrs or closed 24hrs
                        $is24hrs = isset($day_data['open24hrs']) ? $day_data['open24hrs'] : 0;
                        $isClosed24hrs = isset($day_data['closed24hrs']) ? $day_data['closed24hrs'] : 0;

                        // Check if any timing data is provided
                        $timingDataProvided = false;

                        // Check if there are any non-24hr time slots
                        foreach ($day_data as $timing_data) {
                            if (isset($timing_data['start']) && isset($timing_data['end'])) {
                                $timingDataProvided = true;
                                break;
                            }
                        }

                        if ($is24hrs == '1') {
                            // Delete existing records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_ID` = '$hidden_hotspot_ID'");

                            // Insert record for open 24hrs
                            $arrFieldTiming = array('`hotspot_ID`', '`hotspot_timing_day`', '`hotspot_open_all_time`', '`createdby`', '`status`');
                            $arrValuesTiming = array("$hidden_hotspot_ID", "$hotspot_timing_day", "1", "$logged_user_id", "1");
                            sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFieldTiming, $arrValuesTiming, '');
                        } elseif ($isClosed24hrs == '1') {
                            // Delete existing records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_ID` = '$hidden_hotspot_ID'");

                            // Insert record for closed 24hrs
                            $arrFieldTiming = array('`hotspot_ID`', '`hotspot_timing_day`', '`hotspot_closed`', '`createdby`', '`status`');
                            $arrValuesTiming = array("$hidden_hotspot_ID", "$hotspot_timing_day", "1", "$logged_user_id", "1");
                            sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFieldTiming, $arrValuesTiming, '');
                        } elseif ($timingDataProvided) {
                            // Delete existing non-24hr records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_open_all_time` = '0' AND `hotspot_closed` = '0' AND `hotspot_ID` = '$hidden_hotspot_ID'");

                            // Delete existing records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_ID` = '$hidden_hotspot_ID'");

                            // Initialize arrays to store time slots for insertion
                            $insertTimeSlots = [];

                            foreach ($day_data as $timing_data) {
                                if (isset($timing_data['start']) && isset($timing_data['end'])) {
                                    $hotspot_start_time = date("H:i:s", strtotime($timing_data['start']));
                                    $hotspot_end_time = date("H:i:s", strtotime($timing_data['end']));

                                    // Check if the time slot is unique
                                    $timeSlot = $hotspot_start_time . '-' . $hotspot_end_time;
                                    if (!in_array($timeSlot, $insertTimeSlots)) {
                                        $insertTimeSlots[] = $timeSlot;

                                        // Insert new record for non-24hr scenario
                                        $arrFieldTiming = array('`hotspot_ID`', '`hotspot_timing_day`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_open_all_time`', '`hotspot_closed`', '`createdby`', '`status`');
                                        $arrValuesTiming = array("$hidden_hotspot_ID", "$hotspot_timing_day", "$hotspot_start_time", "$hotspot_end_time", "$is24hrs", "$isClosed24hrs", "$logged_user_id", "1");

                                        // Insert new record
                                        sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFieldTiming, $arrValuesTiming, '');
                                    }
                                }
                            }
                        }
                    }

                    //VEHICLE PARKING CHARGE
                    for ($j = 0; $j < count($_vehicle_parking_charge); $j++) :

                        $parking_charge_id = $_vehicle_parking_charge_ID[$j];
                        $vehicle_type_id = $_vehicle_type_id[$j];
                        $vehicle_parking_charge = $_vehicle_parking_charge[$j];

                        if ($parking_charge_id != '' && $parking_charge_id != 0) :
                            //UPDATE
                            $arrFields_parking = array('`parking_charge`');
                            $arrValues_parking = array("$vehicle_parking_charge");
                            $sqlWhere_parking = " `hotspot_id` = '$hidden_hotspot_ID' AND `vehicle_type_id`= '$vehicle_type_id' ";

                            //UPDATE PARKING DETAILS
                            if (sqlACTIONS("UPDATE", "dvi_hotspot_vehicle_parking_charges", $arrFields_parking, $arrValues_parking, $sqlWhere_parking)) :
                            endif;

                        else :
                            //INSERT 
                            $arrFields_parking = array('`hotspot_id`', '`vehicle_type_id`',  '`parking_charge`', '`createdby`', '`status`');

                            $arrValues_parking = array("$hidden_hotspot_ID", "$vehicle_type_id",  "$vehicle_parking_charge",  "$logged_user_id", "1");

                            //INSERT PARKING DETAILS
                            if (sqlACTIONS("INSERT", "dvi_hotspot_vehicle_parking_charges", $arrFields_parking, $arrValues_parking, '')) :
                            endif;

                        endif;

                    endfor;

                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'newhotspot.php';
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;

            else :

                //INSERT HOTSPOT DETAILS
                if (sqlACTIONS("INSERT", "dvi_hotspot_place", $arrFields, $arrValues, '')) :
                    $hotspot_ID = sqlINSERTID_LABEL();

                    //HOTSPOT GALLERY
                    $hotspot_gallery_array = $_FILES['hotspot_gallery']['name'];

                    foreach ($hotspot_gallery_array as $key => $val) :
                        $upload_dir = '../../uploads/hotspot_gallery/';
                        $filetype = end(explode('.', $_FILES['hotspot_gallery']['name'][$key]));
                        $file_name = $_FILES['hotspot_gallery']['name'][$key];
                        $file_name = trim($file_name);
                        $file_type = $_FILES['hotspot_gallery']['type'][$key];
                        $file_temp_loc  = $_FILES['hotspot_gallery']['tmp_name'][$key];
                        $file_error_msg = $_FILES['hotspot_gallery']['error'][$key];
                        $file_size = $_FILES['hotspot_gallery']['size'][$key];
                        $move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);

                        if ($move_file) :
                            $arrFields_gallery = array('`hotspot_ID`', '`hotspot_gallery_name`', '`createdby`', '`status`');
                            $arrValues_gallery = array("$hotspot_ID", "$file_name", "$logged_user_id", "1");
                            if (sqlACTIONS("INSERT", "dvi_hotspot_gallery_details", $arrFields_gallery, $arrValues_gallery, '')) :
                            //SUCCESS
                            endif;
                        endif;

                    endforeach;

                    $operating_hours = $_POST['operating_hours'];

                    foreach ($operating_hours as $day_text => $day_data) {
                        // Determine the day index
                        $hotspot_timing_day = array_search($day_text, array_keys($operating_hours));

                        // Check if open 24hrs or closed 24hrs
                        $is24hrs = isset($day_data['open24hrs']) ? $day_data['open24hrs'] : null;
                        $isClosed24hrs = isset($day_data['closed24hrs']) ? $day_data['closed24hrs'] : null;

                        // Check if any timing data is provided
                        $timingDataProvided = false;

                        // Check if there are any non-24hr time slots
                        foreach ($day_data as $timing_data) {
                            if (isset($timing_data['start']) && isset($timing_data['end'])) {
                                $timingDataProvided = true;
                                break;
                            }
                        }

                        if ($is24hrs == '1') {
                            // Delete existing records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_ID` = '$hotspot_ID'");

                            // Insert record for open 24hrs
                            $arrFieldTiming = array('`hotspot_ID`', '`hotspot_timing_day`', '`hotspot_open_all_time`', '`createdby`', '`status`');
                            $arrValuesTiming = array("$hotspot_ID", "$hotspot_timing_day", "1", "$logged_user_id", "1");
                            sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFieldTiming, $arrValuesTiming, '');
                        } elseif ($isClosed24hrs == '1') {
                            // Delete existing records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_ID` = '$hotspot_ID'");

                            // Insert record for closed 24hrs
                            $arrFieldTiming = array('`hotspot_ID`', '`hotspot_timing_day`', '`hotspot_closed`', '`createdby`', '`status`');
                            $arrValuesTiming = array("$hotspot_ID", "$hotspot_timing_day", "1", "$logged_user_id", "1");
                            sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFieldTiming, $arrValuesTiming, '');
                        } elseif ($timingDataProvided) {
                            // Delete existing non-24hr records for the day
                            sqlACTIONS("DELETE", "dvi_hotspot_timing", '', '', "`hotspot_timing_day` = '$hotspot_timing_day' AND `hotspot_open_all_time` = '0' AND `hotspot_closed` = '0' AND `hotspot_ID` = '$hotspot_ID'");

                            // Initialize arrays to store time slots for insertion
                            $insertTimeSlots = [];

                            foreach ($day_data as $timing_data) {
                                if (isset($timing_data['start']) && isset($timing_data['end'])) {
                                    $hotspot_start_time = date("H:i:s", strtotime($timing_data['start']));
                                    $hotspot_end_time = date("H:i:s", strtotime($timing_data['end']));

                                    // Check if the time slot is unique
                                    $timeSlot = $hotspot_start_time . '-' . $hotspot_end_time;
                                    if (!in_array($timeSlot, $insertTimeSlots)) {
                                        $insertTimeSlots[] = $timeSlot;

                                        // Insert new record for non-24hr scenario
                                        $arrFieldTiming = array('`hotspot_ID`', '`hotspot_timing_day`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_open_all_time`', '`hotspot_closed`', '`createdby`', '`status`');
                                        $arrValuesTiming = array("$hotspot_ID", "$hotspot_timing_day", "$hotspot_start_time", "$hotspot_end_time", "0", "0", "$logged_user_id", "1");

                                        // Insert new record
                                        sqlACTIONS("INSERT", "dvi_hotspot_timing", $arrFieldTiming, $arrValuesTiming, '');
                                    }
                                }
                            }
                        }
                    }

                    //VEHICLE PARKING CHARGE
                    for ($j = 0; $j < count($_vehicle_parking_charge); $j++) :

                        $parking_charge_id = $_vehicle_parking_charge_ID[$j];
                        $vehicle_type_id = $_vehicle_type_id[$j];
                        $vehicle_parking_charge = $_vehicle_parking_charge[$j];

                        if ($parking_charge_id != '' && $parking_charge_id != 0) :
                            //UPDATE
                            $arrFields_parking = array('`parking_charge`');
                            $arrValues_parking = array("$vehicle_parking_charge");
                            $sqlWhere_parking = " `hotspot_id` = '$hotspot_ID' AND `vehicle_type_id`= '$vehicle_type_id'";

                            //UPDATE PARKING DETAILS
                            if (sqlACTIONS("UPDATE", "dvi_hotspot_vehicle_parking_charges", $arrFields_parking, $arrValues_parking, $sqlWhere_parking)) :
                            endif;

                        else :
                            //INSERT 
                            $arrFields_parking = array('`hotspot_id`', '`vehicle_type_id`',  '`parking_charge`', '`createdby`', '`status`');

                            $arrValues_parking = array("$hotspot_ID", "$vehicle_type_id",  "$vehicle_parking_charge",  "$logged_user_id", "1");

                            //INSERT PARKING DETAILS
                            if (sqlACTIONS("INSERT", "dvi_hotspot_vehicle_parking_charges", $arrFields_parking, $arrValues_parking, '')) :
                            endif;

                        endif;
                    endfor;

                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'newhotspot.php';
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotspot_priority_update') :

        $errors = [];
        $response = [];

        // Sanitize input data
        $hotspot_ID = $validation_globalclass->sanitize($_POST['id']);
        $hotspot_priority = $validation_globalclass->sanitize($_POST['priority']);
        if ($hotspot_ID != '' && $hotspot_priority != ''):
            // Prepare the update query
            $arrFields = array('`hotspot_priority`');
            $arrValues = array("$hotspot_priority");
            $sqlWhere = " `hotspot_ID` = '$hotspot_ID' ";

            // Execute the update
            $update_status = sqlACTIONS("UPDATE", "dvi_hotspot_place", $arrFields, $arrValues, $sqlWhere);

            // Set response based on update status
            if ($update_status) :
                $response['success'] = true;
            else :
                $response['success'] = false;
            endif;
        else :
            $response['success'] = false;
        endif;

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);

    elseif ($_GET['type'] == 'hotspot_delete') :

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
                    <button type="submit" onclick="confirmHOTSPOTDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    <?php

    elseif ($_GET['type'] == 'confirm_hotspot_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_activity = sqlQUERY_LABEL("UPDATE `dvi_hotspot_place` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotspot_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        if ($delete_activity) :

            $delete_activity_images = sqlQUERY_LABEL("UPDATE `dvi_hotspot_timing` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotspot_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_hotspot_gallery') :

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
                    <button type="submit" onclick="confirmGALLERYDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_hotspot_gallery_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_AGENT = sqlQUERY_LABEL("UPDATE `dvi_hotspot_gallery_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `hotspot_gallery_details_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_IMAGE:" . sqlERROR_LABEL());

        if ($delete_AGENT == '1') :
            $response['result'] = true;
        else :

            $response['result'] = false;
        // $response['response_error'] = true;
        endif;
        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
