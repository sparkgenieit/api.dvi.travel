<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
include_once('../../smtp_functions.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'assign_vehicle') :

        $response = [];
        $errors = [];

        $itinerary_plan_id = $_POST['itineraryPlanId'];
        $vendor_id = $_POST['vendor_id'];
        $vendor_vehicle_type_id = $_POST['vehicle_type_id'];
        $vehicle_id = $_POST['vehicle_id'];
        $driver_id = $_POST['driver_id'];
        $trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_start_date_and_time');
        $trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_end_date_and_time');
        $assigned_vehicle_status = "1";
        $assigned_on = date('Y-m-d H:i:s');

        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;
            $civvd_arrFields = [
                '`vehicle_id`',
                '`createdby`'
            ];
            $civvd_arrValues = [
                "$vehicle_id",
                "$logged_user_id"
            ];

            $civvd_sqlwhere = " `vendor_id` = '$vendor_id' and `vendor_vehicle_type_id` = '$vendor_vehicle_type_id' and `itinerary_plan_id` = '$itinerary_plan_id'";

            // Insert Vehicle Assigned 
            if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_vendor_vehicle_details", $civvd_arrFields, $civvd_arrValues, $civvd_sqlwhere)) :
                $arrFields = [
                    '`itinerary_plan_id`',
                    '`vendor_id`',
                    '`vendor_vehicle_type_id`',
                    '`vehicle_id`',
                    '`trip_start_date_and_time`',
                    '`trip_end_date_and_time`',
                    '`assigned_vehicle_status`',
                    '`assigned_on`',
                    '`createdby`',
                    '`status`'
                ];

                $arrValues = [
                    "$itinerary_plan_id",
                    "$vendor_id",
                    "$vendor_vehicle_type_id",
                    "$vehicle_id",
                    "$trip_start_date_and_time",
                    "$trip_end_date_and_time",
                    "$assigned_vehicle_status",
                    "$assigned_on",
                    "$logged_user_id",
                    '1'
                ];

                // Assign values to global variables
                $_SESSION['global_itinerary_plan_ID'] = $itinerary_plan_id;
                $_SESSION['global_vendor_id'] = $vendor_id;
                $_SESSION['global_vendor_vehicle_type_id'] = $vendor_vehicle_type_id;
                $_SESSION['global_vehicle_id'] = $vehicle_id;
                $_SESSION['global_driver_id'] = $driver_id;
                $_SESSION['global_trip_start_date_and_time'] = $trip_start_date_and_time;
                $_SESSION['global_trip_end_date_and_time'] = $trip_end_date_and_time;
                $_SESSION['global_assigned_vehicle_status'] = $assigned_vehicle_status;
                $_SESSION['global_assigned_on'] = $assigned_on;

                // Include the email notification script
                include('ajax_vehicle_assign_email_notification.php');

                // Unset the global variables
                unset($_SESSION['global_itinerary_plan_ID']);
                unset($_SESSION['global_vendor_id']);
                unset($_SESSION['global_vendor_vehicle_type_id']);
                unset($_SESSION['global_vehicle_id']);
                unset($_SESSION['global_driver_id']);
                unset($_SESSION['global_trip_start_date_and_time']);
                unset($_SESSION['global_trip_end_date_and_time']);
                unset($_SESSION['global_assigned_vehicle_status']);
                unset($_SESSION['global_assigned_on']);

                if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_vendor_vehicle_assigned", $arrFields, $arrValues, '')) :
                    //Assign Driver 
                    $assigned_driver_status = "1";

                    $arrFields_driver = [
                        '`itinerary_plan_id`',
                        '`vendor_id`',
                        '`vendor_vehicle_type_id`',
                        '`vehicle_id`',
                        '`driver_id`',
                        '`trip_start_date_and_time`',
                        '`trip_end_date_and_time`',
                        '`assigned_driver_status`',
                        '`driver_assigned_on`',
                        '`createdby`',
                        '`status`'
                    ];

                    $arrValues_driver = [
                        "$itinerary_plan_id",
                        "$vendor_id",
                        "$vendor_vehicle_type_id",
                        "$vehicle_id",
                        "$driver_id",
                        "$trip_start_date_and_time",
                        "$trip_end_date_and_time",
                        "$assigned_driver_status",
                        "$assigned_on",
                        "$logged_user_id",
                        '1'
                    ];

                    if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_vendor_driver_assigned", $arrFields_driver, $arrValues_driver, '')) :
                        $response['result_success'] = true;
                    else:
                        $response['result_success'] = false;
                    endif;
                else:
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'reassign_driver') :

        $response = [];
        $errors = [];

        $itinerary_plan_id = $_POST['itineraryPlanId'];
        $vendor_id = $_POST['vendor_id'];
        $driver_id = $_POST['driver_id'];

        $trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_start_date_and_time');
        $trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_id, 'trip_end_date_and_time');
        $assigned_driver_status = "1";
        $assigned_on = date('Y-m-d H:i:s');

        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = [
                '`driver_id`',
                '`driver_assigned_on`',
                '`createdby`'
            ];

            $arrValues = [
                "$driver_id",
                "$assigned_on",
                "$logged_user_id",
            ];

            $sqlWhere = " `itinerary_plan_id` = '$itinerary_plan_id' AND `vendor_id`='$vendor_id' ";

            if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_vendor_driver_assigned", $arrFields, $arrValues, $sqlWhere)) :
                $response['result_success'] = true;
            else:
                $response['result_success'] = false;
            endif;

        endif;

        echo json_encode($response);
        exit;

    /* elseif ($_GET['type'] == 'add_driver') :

        $response = [];
        $errors = [];

        $_vendor_id = $_POST['vendor_name'];
        $_vendor_vehicle_type_id = $_POST['vehicle_type'];
        $_driver_name = $_POST['driver_name'];
        $_driver_primary_mobile_number = $_POST['driver_primary_mobile_number'];

        if (empty($_POST['vendor_name'])) :
            $errors['vendor_name_required'] = true;
        endif;

        if (empty($_POST['vehicle_type'])) :
            $errors['vehicle_type_required'] = true;
        endif;

        if (empty($_POST['driver_name'])) :
            $errors['driver_name_required'] = true;
        endif;

        if (empty($_POST['driver_primary_mobile_number'])) :
            $errors['driver_primary_mobile_number_required'] = true;
        endif;

        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;
            $arrFields = array('`driver_name`',  '`vendor_id`', '`vehicle_type_id`', '`driver_primary_mobile_number`',  '`createdby`', '`status`');

            $arrValues = array("$_driver_name", "$_vendor_id", "$_vendor_vehicle_type_id", "$_driver_primary_mobile_number", "$logged_user_id", "1");

            //INSERT DRIVER DETAILS
            if (sqlACTIONS("INSERT", "dvi_driver_details", $arrFields, $arrValues, '')) :
                $response['result_success'] = true;
            else:
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);
        exit; */

    elseif ($_GET['type'] == 'add_driver') :

        $response = [];
        $errors = [];

        $_vendor_id = $_POST['vendor_name'];
        $_vendor_vehicle_type_id = $_POST['vehicle_type'];
        $_driver_name = $_POST['driver_name'];
        $_driver_primary_mobile_number = $_POST['driver_primary_mobile_number'];

        if (empty($_POST['vendor_name'])) :
            $errors['vendor_name_required'] = true;
        endif;

        if (empty($_POST['vehicle_type'])) :
            $errors['vehicle_type_required'] = true;
        endif;

        if (empty($_POST['driver_name'])) :
            $errors['driver_name_required'] = true;
        endif;

        if (empty($_POST['driver_primary_mobile_number'])) :
            $errors['driver_primary_mobile_number_required'] = true;
        endif;

        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;
            $arrFields = array('`driver_name`',  '`vendor_id`', '`vehicle_type_id`', '`driver_primary_mobile_number`',  '`createdby`', '`status`');

            $arrValues = array("$_driver_name", "$_vendor_id", "$_vendor_vehicle_type_id", "$_driver_primary_mobile_number", "$logged_user_id", "1");

            //INSERT DRIVER DETAILS
            if (sqlACTIONS("INSERT", "dvi_driver_details", $arrFields, $arrValues, '')) :
                $response['result_success'] = true;
            else:
                $response['result_success'] = false;
            endif;

        endif;

        echo json_encode($response);
        exit;

    elseif ($_GET['type'] == 'add_vehicle') :

        $response = [];
        $errors = [];

        $_vendor_id = $_POST['vendor_name'];
        $_vendor_vehicle_type_id = $_POST['vehicle_type'];
        $_branch_id = $_POST['vendor_branch'];
        $_registration_number = trim($_POST['registration_number']);
        $_registration_number = str_replace(' ', '', $_registration_number);
        $_insurance_start_date = dateformat_database($_POST['insurance_start_date']);
        $_insurance_end_date = dateformat_database($_POST['insurance_end_date']);
        $_vehicle_location = $_POST['vehicle_orign'];
        $_vehicle_location_id = getSTOREDLOCATIONDETAILS($_vehicle_location, 'LOCATION_ID');
        $_vehicle_fc_expiry_date = dateformat_database($_POST['vehicle_fc_expiry_date']);

        if (empty($_POST['vendor_name'])) :
            $errors['vendor_name_required'] = true;
        endif;

        if (empty($_POST['vehicle_type'])) :
            $errors['vehicle_type_required'] = true;
        endif;

        if (empty($_registration_number)) :
            $errors['registration_number_required'] = true;
        endif;

        if (empty($_vehicle_fc_expiry_date)) :
            $errors['vehicle_fc_expiry_date_required'] = true;
        endif;

        if ($_vehicle_location == "") :
            $errors['vehicle_location_required'] = true;
        endif;

        if (empty($_insurance_start_date)) :
            $errors['insurance_start_date_required'] = true;
        endif;
        if (empty($_insurance_end_date)) :
            $errors['insurance_end_date_required'] = true;
        endif;


        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $_vehicle_fc_expiry_date = date('Y-m-d', strtotime($_vehicle_fc_expiry_date));
            $_insurance_end_date = date('Y-m-d', strtotime($_insurance_end_date));
            if (($_vehicle_fc_expiry_date < date("Y-m-d")) || ($_insurance_end_date < date("Y-m-d"))) :
                $status = '0';
            else :
                $status = '1';
            endif;

            $vechicle_arrFields = array('`vendor_id`', '`vendor_branch_id`',  '`vehicle_type_id`', '`registration_number`', '`vehicle_fc_expiry_date`',  '`vehicle_location_id`', '`insurance_start_date`', '`insurance_end_date`',  '`createdby`', '`status`');

            $vechicle_arrValues = array("$_vendor_id", "$_branch_id", "$_vendor_vehicle_type_id", "$_registration_number",  "$_vehicle_fc_expiry_date", "$_vehicle_location_id", "$_insurance_start_date", "$_insurance_end_date", "$logged_user_id", "$status");

            //INSERT DRIVER DETAILS
            if (sqlACTIONS("INSERT", "dvi_vehicle", $vechicle_arrFields, $vechicle_arrValues, '')) :
                $response['result_success'] = true;
            else:
                $response['result_success'] = false;
            endif;

        endif;

        echo json_encode($response);
        exit;

    endif;


else :
    echo json_encode(['success' => false, 'message' => 'Request Ignored']);
    exit;
endif;
