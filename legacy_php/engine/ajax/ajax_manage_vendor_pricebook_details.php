<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

$response = [];
$errors = [];

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if ($_GET['type'] == 'vendor_margin_details') :

    // Get POST data
    $vendor_id = $_POST['vendor_id'];
    $vendor_margin = $_POST['vendor_margin'];
    $vendor_margin_gst_type = $_POST['vendor_margin_gst_type'];
    $vendor_margin_gst_percentage = $_POST['vendor_margin_gst_percentage'];

    // Validate input
    if ($vendor_margin === '' || !is_numeric($vendor_margin)) :
        $errors[] = "Invalid Vendor Margin.";
    endif;
    if (empty($vendor_margin_gst_type)) :
        $errors[] = "Vendor Margin GST Type is required.";
    endif;
    if ($vendor_margin_gst_percentage === '' || !is_numeric($vendor_margin_gst_percentage)) :
        $errors[] = "Invalid Vendor Margin GST Percentage.";
    endif;

    if (!empty($errors)) :
        // Error response
        $response['success'] = false;
        $response['errors'] = $errors;
    else :
        // Success call        
        $response['success'] = true;

        // Fields to be updated
        $arrFields = array('`vendor_margin`', '`vendor_margin_gst_type`', '`vendor_margin_gst_percentage`');
        $arrValues = array("$vendor_margin", "$vendor_margin_gst_type", "$vendor_margin_gst_percentage");

        // Define where condition
        $sqlwhere = " `vendor_id` = '$vendor_id'";

        // Update VENDOR DETAILS
        if (sqlACTIONS("UPDATE", "dvi_vendor_details", $arrFields, $arrValues, $sqlwhere)) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;
    endif;

    echo json_encode($response);

elseif ($_GET['type'] == 'vendor_driver_cost') :

    $vendor_vehicle_type_IDs = $_POST['vendor_vehicle_type_ID'];
    $vehicle_type_title = $_POST['vehicle_type_title'];
    $driver_battas = $_POST['driver_batta'];
    $food_costs = $_POST['food_cost'];
    $accomodation_costs = $_POST['accomodation_cost'];
    $extra_costs = $_POST['extra_cost'];
    $morning_costs = $_POST['driver_early_morning_charges'];
    $evening_costs = $_POST['driver_evening_charges'];

    // Validate input
    foreach ($vendor_vehicle_type_IDs as $index => $vendor_vehicle_type_ID) :
        $selected_vehicle_type_title = $vehicle_type_title[$index];
        if ($driver_battas[$index] === '' || !is_numeric($driver_battas[$index])) :
            $errors[] = "Invalid Driver Cost for $selected_vehicle_type_title.";
        endif;
        if ($food_costs[$index] === '' || !is_numeric($food_costs[$index])) :
            $errors[] = "Invalid Food Cost for $selected_vehicle_type_title.";
        endif;
        if ($accomodation_costs[$index] === '' || !is_numeric($accomodation_costs[$index])) :
            $errors[] = "Invalid Accommodation Cost for $selected_vehicle_type_title.";
        endif;
        if ($extra_costs[$index] === '' || !is_numeric($extra_costs[$index])) :
            $errors[] = "Invalid Extra Cost for $selected_vehicle_type_title.";
        endif;
        if ($morning_costs[$index] === '' || !is_numeric($morning_costs[$index])) :
            $errors[] = "Invalid Morning Charge for $selected_vehicle_type_title.";
        endif;
        if ($evening_costs[$index] === '' || !is_numeric($evening_costs[$index])) :
            $errors[] = "Invalid Evening Charge for $selected_vehicle_type_title.";
        endif;
    endforeach;

    if (!empty($errors)) :
        // Error response
        $response['success'] = false;
        $response['errors'] = $errors;
    else :
        // Update driver cost details for each vehicle type
        $response['success'] = true;
        $response['result'] = true;

        foreach ($vendor_vehicle_type_IDs as $index => $vendor_vehicle_type_ID) :
            // Fields to be updated
            $arrFields = array(
                '`driver_batta`',
                '`food_cost`',
                '`accomodation_cost`',
                '`extra_cost`',
                '`driver_early_morning_charges`',
                '`driver_evening_charges`'
            );
            $arrValues = array(
                $driver_battas[$index],
                $food_costs[$index],
                $accomodation_costs[$index],
                $extra_costs[$index],
                $morning_costs[$index],
                $evening_costs[$index]
            );

            // Define where condition
            $sqlwhere = " `vendor_vehicle_type_ID` = '$vendor_vehicle_type_ID'";

            // Update driver cost details
            if (!sqlACTIONS("UPDATE", "dvi_vendor_vehicle_types", $arrFields, $arrValues, $sqlwhere)) :
                $response['result'] = false;
                break;
            endif;
        endforeach;
    endif;

    echo json_encode($response);

elseif ($_GET['type'] == 'vehicle_extra_cost') :

    $vehicle_type_ids = $_POST['vehicle_type_id'];
    $vehicle_type_titles = $_POST['vehicle_type_title'];
    $extra_km_charges = $_POST['extra_km_charge'];
    $extra_hour_charges = $_POST['extra_hour_charge'];
    $early_morning_charges = $_POST['early_morning_charges'];
    $evening_charges = $_POST['evening_charges'];
    $vendor_id = $_POST['vendor_id'];
    $vendor_branch_id = $_POST['vendor_branch_id'];

    foreach ($vehicle_type_ids as $index => $vehicle_type_id) :
        $selected_vehicle_type_title = $vehicle_type_titles[$index];
        if ($extra_km_charges[$index] === '' || !is_numeric($extra_km_charges[$index])) :
            $errors[] = "Invalid Extra KM Charge for $selected_vehicle_type_title.";
        endif;
        if ($extra_hour_charges[$index] === '' || !is_numeric($extra_hour_charges[$index])) :
            $errors[] = "Invalid Extra Hour Charge for $selected_vehicle_type_title.";
        endif;
        if ($early_morning_charges[$index] === '' || !is_numeric($early_morning_charges[$index])) :
            $errors[] = "Invalid Early Morning Charges for $selected_vehicle_type_title.";
        endif;
        if ($evening_charges[$index] === '' || !is_numeric($evening_charges[$index])) :
            $errors[] = "Invalid Evening Charges for $selected_vehicle_type_title.";
        endif;
    endforeach;

    if (!empty($errors)) :
        // Error response
        $response['success'] = false;
        $response['errors'] = $errors;
    else :
        // Update vehicle cost details for each vehicle type
        $response['success'] = true;
        $response['result'] = true;

        foreach ($vehicle_type_ids as $index => $vehicle_type_id) :
            // Fields to be updated
            $arrFields = array(
                '`extra_km_charge`',
                '`early_morning_charges`',
                '`evening_charges`',
                '`extra_hour_charge`'
            );
            $arrValues = array(
                $extra_km_charges[$index],
                $early_morning_charges[$index],
                $evening_charges[$index],
                $extra_hour_charges[$index],
            );

            // Define where condition
            $sqlwhere = " `vehicle_type_id` = '$vehicle_type_id' AND `vendor_id` = '$vendor_id' AND `vendor_branch_id` = '$vendor_branch_id'";

            // Update vehicle cost details
            if (!sqlACTIONS("UPDATE", "dvi_vehicle", $arrFields, $arrValues, $sqlwhere)) :
                $response['result'] = false;
                break;
            endif;
        endforeach;
    endif;

    echo json_encode($response);

elseif ($_GET['type'] == 'vehicle_local_pricebook_cost') :

    $vendor_ids = $_POST['vendor_id'] ?? [];
    $vendor_branch_ids = $_POST['vendor_branch_id'] ?? [];
    $vehicle_ids = $_POST['vehicle_id'] ?? [];
    $time_limit_ids = $_POST['time_limit_id'] ?? [];
    $vehicle_type_ids = $_POST['vehicle_type_id'] ?? [];
    $vehicle_type_titles = $_POST['vehicle_type_title'] ?? [];
    $vehicle_rental_charges = $_POST['vehicle_rental_charge'] ?? [];
    $start_date = $_POST['local_pricebook_start_date'] ?? [];
    $end_date = $_POST['local_pricebook_end_date'] ?? [];

    // Ensure $vehicle_ids is an array
    if (!is_array($vehicle_ids)) {
        $vehicle_ids = [];
    }

    $filtered_entries = [];

    // Filter entries with a valid vehicle_rental_charge
    for ($i = 0; $i < count($vehicle_ids); $i++) :
        if ($vehicle_rental_charges[$i] !== null && $vehicle_rental_charges[$i] !== '') :
            $filtered_entries[] = [
                'vendor_id' => $vendor_ids[$i],
                'vendor_branch_id' => $vendor_branch_ids[$i],
                'vehicle_id' => $vehicle_ids[$i],
                'time_limit_id' => $time_limit_ids[$i],
                'vehicle_type_id' => $vehicle_type_ids[$i],
                'vehicle_type_title' => $vehicle_type_titles[$i],
                'vehicle_rental_charge' => $vehicle_rental_charges[$i],
            ];
        endif;
    endfor;

    // Process each filtered entry
    foreach ($filtered_entries as $entry) :
        $vendor_id = $entry['vendor_id'];
        $vendor_branch_id = $entry['vendor_branch_id'];
        $vehicle_id = $entry['vehicle_id'];
        $time_limit_id = $entry['time_limit_id'];
        $vehicle_type_id = $entry['vehicle_type_id'];
        $vehicle_type_title = $entry['vehicle_type_title'];
        $vehicle_rental_charge = $entry['vehicle_rental_charge'];

        if ($start_date && $end_date) :
            $months = getMonthsBetweenDates($start_date, $end_date);

            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                $currentStartDay = ($month == date('F', strtotime($start_date))) ? (int)convertDateFormat($start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($end_date))) ? (int)convertDateFormat($end_date) : (int)date('t', strtotime("$year-$month-01"));

                $currentdate = date('Y-m-d H:i:s');

                // Check if the record exists
                $sqlCheck = "SELECT `vehicle_price_book_id` FROM `dvi_vehicle_local_pricebook` WHERE `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vehicle_type_id' AND `time_limit_id` = '$time_limit_id' AND `year` = '$year' AND `month` = '$month' ";

                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    $updateFields = [];
                    for ($day = $currentStartDay; $day <= $currentEndDay; $day++) {
                        $updateFields[] = "`day_$day` = \"" . (is_numeric($vehicle_rental_charge) ? $vehicle_rental_charge : '0') . "\"";
                    }
                    $sqlUpdate = "UPDATE `dvi_vehicle_local_pricebook` SET " . implode(', ', $updateFields) . " WHERE `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vehicle_type_id' AND `time_limit_id` = '$time_limit_id' AND `year` = '$year' AND `month` = '$month'";

                    if (sqlQUERY_LABEL($sqlUpdate)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                        $errors[] = "Failed to update record for $vehicle_type_title";
                        error_log("Failed to update record for $vehicle_type_title: " . sqlERROR_LABEL());
                    endif;
                else :
                    // Insert new record
                    $arrFields = [
                        '`vendor_id`',
                        '`vendor_branch_id`',
                        '`vehicle_type_id`',
                        '`time_limit_id`',
                        '`cost_type`',
                        '`year`',
                        '`month`',
                        '`createdby`',
                        '`status`',
                        '`createdon`'
                    ];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrFields[] = "`day_$day`";
                    endfor;

                    $arrValues = [
                        "'$vendor_id'",
                        "'$vendor_branch_id'",
                        "'$vehicle_type_id'",
                        "'$time_limit_id'",
                        "'1'",
                        "'$year'",
                        "'$month'",
                        "'$logged_user_id'",
                        "'1'",
                        "'" . date('Y-m-d H:i:s') . "'"
                    ];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? '"' . (is_numeric($vehicle_rental_charge) ? $vehicle_rental_charge : '0') . '"' : '"0"';
                    endfor;

                    $sqlInsert = "INSERT INTO `dvi_vehicle_local_pricebook` (" . implode(', ', $arrFields) . ") VALUES (" . implode(', ', $arrValues) . ")";
                    if (sqlQUERY_LABEL($sqlInsert)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                        $errors[] = "Failed to insert record for vehicle $vehicle_type_title.";
                        error_log("Failed to insert record for vehicle $vehicle_type_title: " . sqlERROR_LABEL());
                    endif;
                endif;
            endforeach;
        endif;
    endforeach;

    if (!empty($errors)) :
        $response['errors'] = $errors;
    endif;

    echo json_encode($response);

elseif ($_GET['type'] == 'vehicle_outstation_pricebook_cost') :

    try {
        $vendor_ids = $_POST['vendor_id'] ?? [];
        $vendor_branch_ids = $_POST['vendor_branch_id'] ?? [];
        $vehicle_ids = $_POST['vehicle_id'] ?? [];
        $kms_limit_ids = $_POST['kms_limit_id'] ?? [];
        $vehicle_type_ids = $_POST['vehicle_type_id'] ?? [];
        $vehicle_type_titles = $_POST['vehicle_type_title'] ?? [];
        $vehicle_rental_charges = $_POST['outstation_vehicle_rental_charge'] ?? [];
        $start_date = $_POST['outstation_pricebook_start_date'] ?? null;
        $end_date = $_POST['outstation_pricebook_end_date'] ?? null;

        // Ensure $vehicle_ids is an array
        if (!is_array($vehicle_ids)) :
            $vehicle_ids = [];
        endif;

        $filtered_entries = [];

        // Filter entries with a valid vehicle_rental_charge
        for ($i = 0; $i < count($vehicle_ids); $i++) :
            if ($vehicle_rental_charges[$i] !== null && $vehicle_rental_charges[$i] !== '') :
                $filtered_entries[] = [
                    'vendor_id' => $vendor_ids[$i],
                    'vendor_branch_id' => $vendor_branch_ids[$i],
                    'vehicle_id' => $vehicle_ids[$i],
                    'kms_limit_id' => $kms_limit_ids[$i],
                    'vehicle_type_id' => $vehicle_type_ids[$i],
                    'vehicle_type_title' => $vehicle_type_titles[$i],
                    'vehicle_rental_charge' => $vehicle_rental_charges[$i],
                ];
            endif;
        endfor;

        // Process each filtered entry
        foreach ($filtered_entries as $entry) :
            $vendor_id = $entry['vendor_id'];
            $vendor_branch_id = $entry['vendor_branch_id'];
            $vehicle_id = $entry['vehicle_id'];
            $kms_limit_id = $entry['kms_limit_id'];
            $vehicle_type_id = $entry['vehicle_type_id'];
            $vehicle_type_title = $entry['vehicle_type_title'];
            $vehicle_rental_charge = $entry['vehicle_rental_charge'];

            if ($start_date && $end_date) :
                $months = getMonthsBetweenDates($start_date, $end_date);

                foreach ($months as $monthYear) :
                    list($month, $year) = explode('-', $monthYear);

                    $currentStartDay = ($month == date('F', strtotime($start_date))) ? (int)convertDateFormat($start_date) : 1;
                    $currentEndDay = ($month == date('F', strtotime($end_date))) ? (int)convertDateFormat($end_date) : (int)date('t', strtotime("$year-$month-01"));

                    $currentdate = date('Y-m-d H:i:s');

                    // Check if the record exists
                    $sqlCheck = "SELECT `vehicle_outstation_price_book_id` FROM `dvi_vehicle_outstation_price_book` WHERE `vendor_id` = '$vendor_id' AND `vendor_branch_id` = '$vendor_branch_id' AND `vehicle_type_id` = '$vehicle_type_id' AND `kms_limit_id` = '$kms_limit_id' AND `year` = '$year' AND `month` = '$month'";

                    $resultCheck = sqlQUERY_LABEL($sqlCheck);

                    if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                        // Update existing record
                        $updateFields = [];
                        for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                            $updateFields[] = "`day_$day` = \"" . (is_numeric($vehicle_rental_charge) ? $vehicle_rental_charge : '0') . "\"";
                        endfor;
                        $sqlUpdate = "UPDATE `dvi_vehicle_outstation_price_book` SET " . implode(', ', $updateFields) . " WHERE `vendor_id` = '$vendor_id' AND `vendor_branch_id` = '$vendor_branch_id' AND `vehicle_type_id` = '$vehicle_type_id' AND `kms_limit_id` = '$kms_limit_id' AND `year` = '$year' AND `month` = '$month'";

                        if (sqlQUERY_LABEL($sqlUpdate)) :
                            $response['success'] = true;
                        else :
                            $response['success'] = false;
                            $errors[] = "Failed to update record for vehicle $vehicle_type_title.";
                            error_log("Failed to update record for vehicle $vehicle_type_title: " . sqlERROR_LABEL());
                        endif;
                    else :
                        // Insert new record
                        $arrFields = [
                            '`vendor_id`',
                            '`vendor_branch_id`',
                            '`vehicle_type_id`',
                            '`kms_limit_id`',
                            '`year`',
                            '`month`',
                            '`createdby`',
                            '`status`',
                            '`createdon`'
                        ];
                        for ($day = 1; $day <= 31; $day++) :
                            $arrFields[] = "`day_$day`";
                        endfor;

                        $arrValues = [
                            "'$vendor_id'",
                            "'$vendor_branch_id'",
                            "'$vehicle_type_id'",
                            "'$kms_limit_id'",
                            "'$year'",
                            "'$month'",
                            "'$logged_user_id'",
                            "'1'",
                            "'" . date('Y-m-d H:i:s') . "'"
                        ];
                        for ($day = 1; $day <= 31; $day++) :
                            $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? '"' . (is_numeric($vehicle_rental_charge) ? $vehicle_rental_charge : '0') . '"' : '"0"';
                        endfor;

                        $sqlInsert = "INSERT INTO `dvi_vehicle_outstation_price_book` (" . implode(', ', $arrFields) . ") VALUES (" . implode(', ', $arrValues) . ")";

                        if (sqlQUERY_LABEL($sqlInsert)) :
                            $response['success'] = true;
                        else :
                            $response['success'] = false;
                            $errors[] = "Failed to insert record for vehicle $vehicle_type_title.";
                            error_log("Failed to insert record for vehicle $vehicle_type_title: " . sqlERROR_LABEL());
                        endif;
                    endif;
                endforeach;
            endif;
        endforeach;

        if (!empty($errors)) :
            $response['errors'] = $errors;
        endif;

        echo json_encode($response);
    } catch (Exception $e) {
        $response['success'] = false;
        $response['errors'] = [$e->getMessage()];
        echo json_encode($response);
    }

endif;
