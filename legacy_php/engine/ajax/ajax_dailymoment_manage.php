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
set_time_limit(0);
ini_set('memory_limit', '256G');
include_once('../../jackus.php');
include_once('../../smtp_functions.php');

$itinerary_session_id = session_id();
/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'hotspotstatus'):

        $response = [];

        $itinerary_plan_ID = $_GET['plan_ID'];
        $itinerarystatus = $_GET['status'];
        $itinerary_route_ID = $_GET['route_ID'];
        $itinerary_route_hotspot_ID = $_GET['routehotspot_ID'];
        $item_type = $_GET['type_ID'];
        $not_visit_description = $_GET['description'];

        $arrFields = array('`driver_hotspot_status`', '`driver_not_visited_description`');
        $arrValues = array("$itinerarystatus", "$not_visit_description");

        $sqlWhere = "`itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$itinerary_route_hotspot_ID' AND `item_type` = '$item_type'";

        if ($itinerary_route_hotspot_ID != ''):
            $response['success'] = true;
            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere);

            if ($insert_status) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_hotspotstatus'):

        $response = [];

        $itinerary_plan_ID = $_GET['plan_ID'];
        $itinerarystatus = $_GET['status'];
        $itinerary_route_ID = $_GET['route_ID'];
        $itinerary_route_hotspot_ID = $_GET['routehotspot_ID'];
        $item_type = $_GET['type_ID'];
        $not_visit_description = $_GET['description'];

        $arrFields = array('`guide_hotspot_status`', '`guide_not_visited_description`');
        $arrValues = array("$itinerarystatus", "$not_visit_description");

        $sqlWhere = "`itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$itinerary_route_hotspot_ID' AND `item_type` = '$item_type'";

        if ($itinerary_route_hotspot_ID != ''):
            $response['success'] = true;
            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere);

            if ($insert_status) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guidestatus'):

        $response = [];

        $itinerary_plan_ID = $_GET['plan_ID'];
        $itinerarystatus = $_GET['status'];
        $itinerary_route_ID = $_GET['route_ID'];
        $itinerary_guide_ID = $_GET['route_guide_ID'];
        $not_visit_description = $_GET['description'];


        $arrFields = array('`driver_guide_status`', '`driver_not_visited_description`');
        $arrValues = array("$itinerarystatus", "$not_visit_description");

        $sqlWhere = "`itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_guide_ID` = '$itinerary_guide_ID'";

        if ($itinerary_guide_ID != ''):
            $response['success'] = true;
            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_guide_details", $arrFields, $arrValues, $sqlWhere);

            if ($insert_status) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'wholeday_guidestatus'):

        $response = [];

        $itinerary_plan_ID = $_GET['plan_ID'];
        $itinerarystatus = $_GET['status'];
        $itinerary_route_ID = $_GET['route_ID'];
        $itinerary_guide_ID = $_GET['route_guide_ID'];
        $not_visit_description = $_GET['description'];


        $arrFields = array('`wholeday_guidehotspot_status`', '`guide_not_visited_description`');
        $arrValues = array("$itinerarystatus", "$not_visit_description");

        $sqlWhere = "`itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID'";

        if ($itinerary_guide_ID != ''):
            $response['success'] = true;
            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_details", $arrFields, $arrValues, $sqlWhere);

            if ($insert_status) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;
        echo json_encode($response);


    elseif ($_GET['type'] == 'activitystatus'):

        $response = [];

        $itinerary_plan_ID = $_GET['plan_ID'];
        $itinerarystatus = $_GET['status'];
        $itinerary_route_ID = $_GET['route_ID'];
        $itinerary_activity_ID = $_GET['route_activity_ID'];
        $itinerary_route_hotspot_ID = $_GET['route_hotspot_ID'];
        $not_visit_description = $_GET['description'];


        $arrFields = array('`driver_activity_status`', '`driver_not_visited_description`');
        $arrValues = array("$itinerarystatus", "$not_visit_description");

        $sqlWhere = "`itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_activity_ID` = '$itinerary_activity_ID' AND `route_hotspot_ID` = '$itinerary_route_hotspot_ID'";


        if ($itinerary_activity_ID != ''):
            $response['success'] = true;
            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_activity_details", $arrFields, $arrValues, $sqlWhere);

            if ($insert_status) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_activitystatus'):

        $response = [];

        $itinerary_plan_ID = $_GET['plan_ID'];
        $itinerarystatus = $_GET['status'];
        $itinerary_route_ID = $_GET['route_ID'];
        $itinerary_activity_ID = $_GET['route_activity_ID'];
        $itinerary_route_hotspot_ID = $_GET['route_hotspot_ID'];
        $not_visit_description = $_GET['description'];


        $arrFields = array('`guide_activity_status`', '`guide_not_visited_description`');
        $arrValues = array("$itinerarystatus", "$not_visit_description");

        $sqlWhere = "`itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_activity_ID` = '$itinerary_activity_ID' AND `route_hotspot_ID` = '$itinerary_route_hotspot_ID'";


        if ($itinerary_activity_ID != ''):
            $response['success'] = true;
            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_activity_details", $arrFields, $arrValues, $sqlWhere);

            if ($insert_status) :
                $response['result_success'] = true;
            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'drivercharge') :

        $errors = [];
        $response = [];

        $itinerary_route_ID = $_GET['Route_id'];
        $itinerary_plan_ID = $_GET['Plan_id'];
        $visited_charge = trim($_POST['visited_charge']);
        $visited_charge_amount = trim($_POST['visited_charge_amount']);
        $hidden_charge = trim($_POST['hidden_charge']);

        if (empty($visited_charge)) :
            $errors['visited_charge_required'] = true;
        endif;
        if (empty($visited_charge_amount)) :
            $errors['visited_charge_amount_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`itinerary_route_ID`', '`itinerary_plan_ID`', '`charge_type`', '`charge_amount`', '`status`', '`deleted`');

            $arrValues = array("$itinerary_route_ID", "$itinerary_plan_ID", "$visited_charge", "$visited_charge_amount", "1", "0");

            if ($hidden_charge != '' && $hidden_charge != 0 && (!empty($hidden_charge))) :
                $sqlwhere = " `driver_charge_ID` = '$hidden_charge' ";

                if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_dailymoment_charge", $arrFields, $arrValues, $sqlwhere)) :
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_dailymoment_charge", $arrFields, $arrValues, '')) :
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;


        endif;

        echo json_encode($response);
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);


        $delete_charge = sqlQUERY_LABEL("UPDATE `dvi_confirmed_itinerary_dailymoment_charge` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `driver_charge_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_GST:" . sqlERROR_LABEL());

        if ($delete_charge) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;
        echo json_encode($response);
    elseif ($_GET['type'] == 'confirmdelete_rating') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);


        $delete_charge = sqlQUERY_LABEL("UPDATE `dvi_confirmed_itinerary_customer_feedback` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `customer_feedback_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_GST:" . sqlERROR_LABEL());

        if ($delete_charge) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'driverImage') :

        $errors = [];
        $response = [];

        $itinerary_route_ID = $_GET['Route_id'];
        $itinerary_plan_ID = $_GET['Plan_id'];

        $uploadDir = '../../uploads/driver_dailymoment_gallery/';

        // Check if files were uploaded
        if (!empty($_FILES['dailymoment_uploadimage']['name'][0])) :

            // Loop through each file
            foreach ($_FILES['dailymoment_uploadimage']['tmp_name'] as $key => $tmpFile) {

                $fileName = rand(0, 99999) . time() . '-' . trim($_FILES['dailymoment_uploadimage']['name'][$key]);
                $filename = $uploadDir . '/' . $fileName;

                if (move_uploaded_file($tmpFile, $filename)) :
                    $_driver_dailymoment_image = $fileName;
                else :
                    $_driver_dailymoment_image = '';
                    $errors[] = "Failed to upload file: " . $_FILES['dailymoment_uploadimage']['name'][$key];
                endif;

                if (!empty($errors)) :
                    // error call
                    $response['success'] = false;
                    $response['errors'] = $errors;
                else :
                    $response['success'] = true;

                    if ($itinerary_route_ID != '' && $itinerary_plan_ID != '') :

                        $arrFields = array('`itinerary_route_ID`', '`itinerary_plan_ID`', '`driver_upload_image`', '`status`', '`deleted`');
                        $arrValues = array("$itinerary_route_ID", "$itinerary_plan_ID", "$_driver_dailymoment_image", "1", "0");

                        if (sqlACTIONS("INSERT", "dvi_confirmed_driver_uploadimage", $arrFields, $arrValues, '')) :
                            $response['i_result'] = true;
                            $response['result_success'] = true;
                        else :
                            $response['i_result'] = false;
                            $response['result_success'] = false;
                        endif;
                    endif;

                endif;
            }

        else :
            $errors[] = "No files were uploaded.";
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'driveruploadkilometer') :

        $errors = [];
        $response = [];


        $starting_kilometer = trim($_POST['starting_kilometer']);
        $itinerary_plan_ID = $_GET['Plan_id'];
        $itinerary_route_ID = $_GET['Route_id'];
        $vendor_id = $_GET['Vendor_id'];
        $vehicle_type_ID = $_GET['Vehicle_type_ID'];
        $vehicle_ID = $_GET['Vehicle_ID'];


        if (empty($starting_kilometer)) :
            $errors['starting_kilometer_required'] = true;
        endif;

        $uploadDir = '../../uploads/driver_speedmeter_gallery/';

        if (!empty($_FILES['driver_speedmeter_image']['name'])) {

            // Generate a unique file name
            $fileName = rand(0, 99999) . time() . '-' . trim($_FILES['driver_speedmeter_image']['name']);
            $filename = $uploadDir . '/' . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['driver_speedmeter_image']['tmp_name'], $filename)) {
                $_opening_speedmeter_image = $fileName;
                $response['success'] = true;

                // Insert into database if itinerary_route_ID and itinerary_plan_ID are available
                if (!empty($itinerary_route_ID) && !empty($itinerary_plan_ID)) {


                    $arrFields = array('`driver_opening_km`', '`opening_speedmeter_image`');
                    $arrValues = array("$starting_kilometer", "$_opening_speedmeter_image");

                    $sqlWhere = "`itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vehicle_type_ID' AND `vehicle_id` = '$vehicle_ID'";


                    if ($starting_kilometer != ''):
                        $response['success'] = true;
                        $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_vendor_vehicle_details", $arrFields, $arrValues, $sqlWhere);

                        if ($insert_status) :
                            $response['i_result'] = true;
                            $response['result_success'] = true;
                            $response['redirect_URL'] = 'dailymoment.php?formtype=show_daylist&&id=' . $itinerary_plan_ID;
                        else :
                            $response['i_result'] = false;
                            $response['result_success'] = false;
                        endif;
                    endif;
                }
            } else {
                // Handle file upload error
                $response['success'] = false;
                $response['errors'] = array("Failed to upload the file: " . $_FILES['driver_speedmeter_image']['name']);
            }
        } else {
            // No file was uploaded
            $response['success'] = false;
            $response['errors'] = array("No file was uploaded.");
        }

        echo json_encode($response);
    elseif ($_GET['type'] == 'driveruploadclosingkilometer') :

        $errors = [];
        $response = [];


        $closing_kilometer = trim($_POST['closing_kilometer']);
        $itinerary_plan_ID = $_GET['Plan_id'];
        $itinerary_route_ID = $_GET['Route_id'];
        $vendor_id = $_GET['Vendor_id'];
        $vehicle_type_ID = $_GET['Vehicle_type_ID'];
        $vehicle_ID = $_GET['Vehicle_ID'];


        if (empty($closing_kilometer)) :
            $errors['closing_kilometer_required'] = true;
        else:
            $driver_opening_km = get_CONFIRMED_ITINEARY_DAILYMOMENT_KILOMETER($itinerary_plan_ID, $itinerary_route_ID, $vendor_id, $vehicle_type_ID, $vehicle_ID, 'driver_opening_km');
            if ($closing_kilometer <= $driver_opening_km) :
                $errors['closing_kilometer_min_error'] = true;
            endif;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $uploadDir = '../../uploads/driver_speedmeter_gallery/';

            if (!empty($_FILES['driver_speedmeter_image']['name'])) {

                // Generate a unique file name
                $fileName = rand(0, 99999) . time() . '-' . trim($_FILES['driver_speedmeter_image']['name']);
                $filename = $uploadDir . '/' . $fileName;

                // Move the uploaded file to the target directory

                $response['success'] = true;
                if (move_uploaded_file($_FILES['driver_speedmeter_image']['tmp_name'], $filename)) {
                    $_opening_speedmeter_image = $fileName;
                    $response['success'] = true;

                    // Insert into database if itinerary_route_ID and itinerary_plan_ID are available
                    if (!empty($itinerary_route_ID) && !empty($itinerary_plan_ID)) {
                        // Define the current day's closing kilometer and opening speedmeter image
                        $arrFields = array('`driver_closing_km`', '`closing_speedmeter_image`');
                        $arrValues = array("$closing_kilometer", "$_opening_speedmeter_image");

                        $sqlWhere = "`itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vehicle_type_ID' AND `vehicle_id` = '$vehicle_ID'";

                        if ($closing_kilometer != ''):
                            $response['success'] = true;

                            // Update the current day's data
                            $insert_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_vendor_vehicle_details", $arrFields, $arrValues, $sqlWhere);

                            if ($insert_status) :
                                // Now get the next day's route ID, either from logic or increment
                                $select_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("SELECT `itinerary_route_id` 
                                FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details`
                                WHERE `itinerary_plan_id` = '$itinerary_plan_ID'
                                AND `itinerary_route_id` > '$itinerary_route_ID'
                                ORDER BY `itinerary_route_id` ASC
                                LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_LIST:" . sqlERROR_LABEL());

                                if (sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_vehicle_details) > 0) :
                                    while ($fetch_itinerary_plan_vendor_vehicle_details = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_vehicle_details)) :
                                        $next_day_route_id = $fetch_itinerary_plan_vendor_vehicle_details['itinerary_route_id'];
                                    endwhile;
                                endif;

                                // Define the next day's opening kilometer and speedmeter image
                                $arrFields = array('`driver_opening_km`', '`opening_speedmeter_image`');
                                $arrValues = array("$closing_kilometer", "$_opening_speedmeter_image");

                                $sqlWhere = "`itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$next_day_route_id' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vehicle_type_ID' AND `vehicle_id` = '$vehicle_ID'";

                                // Update the next day's data
                                $insert_starting_status = sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_plan_vendor_vehicle_details", $arrFields, $arrValues, $sqlWhere);
                                if ($insert_starting_status) :
                                    if (!empty($itinerary_plan_ID)) :
                                        $arrFields_route = array('`driver_trip_completed`');
                                        $arrValues_route = array("1");
                                        $sqlwhere_route = " `itinerary_route_ID` = '$itinerary_route_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID'  ";
                                        sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_details", $arrFields_route, $arrValues_route, $sqlwhere_route);
                                    endif;
                                    $start_date = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time');
                                    $route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '');

                                    // Calculate day count
                                    $start_date_obj = new DateTime($start_date);
                                    $route_date_obj = new DateTime($route_date);
                                    $day_difference = $start_date_obj->diff($route_date_obj)->days + 1;
                                    $get_day_count = "Day " . $day_difference;

                                    // Set global variables
                                    $_SESSION['global_trip_itinerary_plan_ID'] = $itinerary_plan_ID;
                                    $_SESSION['global_trip_itinerary_day_count'] = $get_day_count;

                                    // Include email script
                                    include('ajax_trip_completed_email_notification.php');

                                    // Clean up globals
                                    unset($_SESSION['global_trip_itinerary_plan_ID']);
                                    unset($_SESSION['global_trip_itinerary_day_count']);
                                    $response['i_result'] = true;
                                    $response['result_success'] = true;
                                    $response['redirect_URL'] = 'dailymoment.php?formtype=show_daycomplete&id=' . $itinerary_plan_ID . '&routeid= ' . $itinerary_route_ID;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                            endif;
                        endif;
                    }
                } else {
                    // Handle file upload error
                    $response['success'] = false;
                    $response['errors'] = array("Failed to upload the file: " . $_FILES['driver_speedmeter_image']['name']);
                }
            } else {
                // No file was uploaded
                $response['success'] = false;
                $response['errors'] = array("No file was uploaded.");
            }

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'driverrating') :

        $errors = [];
        $response = [];

        $itinerary_route_ID = $_GET['Route_id'];
        $itinerary_plan_ID = $_GET['Plan_id'];
        $driver_rating = trim($_POST['driver_rating']);
        $review_description = trim($_POST['review_description']);
        $hidden_feedback_ID = trim($_POST['hidden_feedback_ID']);
        $cstmr_id = trim($_POST['cstmr_id']);


        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`itinerary_route_ID`', '`itinerary_plan_ID`', '`customer_id`', '`customer_rating`', '`feedback_description`', '`status`', '`deleted`');

            $arrValues = array("$itinerary_route_ID", "$itinerary_plan_ID", "$cstmr_id", "$driver_rating", "$review_description", "1", "0");

            if ($hidden_feedback_ID != '' && $hidden_feedback_ID != 0 && (!empty($hidden_feedback_ID))) :
                $sqlwhere = " `customer_feedback_ID` = '$hidden_feedback_ID' ";

                if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_customer_feedback", $arrFields, $arrValues, $sqlwhere)) :
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                $cstmr_id = Encryption::Encode($cstmr_id, SECRET_KEY);
                $itinerary_plan_ID = Encryption::Encode($itinerary_plan_ID, SECRET_KEY);
                if (!empty($driver_rating)):
                    if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_customer_feedback", $arrFields, $arrValues, '')) :
                        $response['i_result'] = true;
                        $response['redirect_URL'] = "dailymoment.php?formtype=driver&cstmrid=$cstmr_id&id=$itinerary_plan_ID";
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;
                else:
                    $response['i_result'] = true;
                    $response['redirect_URL'] = "dailymoment.php?formtype=driver&cstmrid=$cstmr_id&id=$itinerary_plan_ID";
                    $response['result_success'] = true;

                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guideconfirmdelete_rating') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);


        $delete_charge = sqlQUERY_LABEL("UPDATE `dvi_confirmed_itinerary_guide_feedback` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `guide_feedback_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_GST:" . sqlERROR_LABEL());

        if ($delete_charge) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;
        echo json_encode($response);


    elseif ($_GET['type'] == 'guiderating') :

        $errors = [];
        $response = [];

        $itinerary_route_ID = $_GET['Route_id'];
        $itinerary_plan_ID = $_GET['Plan_id'];
        $guide_rating = trim($_POST['guide_rating']);
        $review_description = trim($_POST['review_description']);
        $hidden_guide_feedback_ID = trim($_POST['hidden_guide_feedback_ID']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`itinerary_route_ID`', '`itinerary_plan_ID`', '`guide_rating`', '`guide_description`', '`status`', '`deleted`');

            $arrValues = array("$itinerary_route_ID", "$itinerary_plan_ID", "$guide_rating", "$review_description", "1", "0");



            if ($hidden_guide_feedback_ID != '' && $hidden_guide_feedback_ID != 0 && (!empty($hidden_guide_feedback_ID))) :
                $sqlwhere = " `guide_feedback_ID` = '$hidden_guide_feedback_ID' ";

                if (sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_guide_feedback", $arrFields, $arrValues, $sqlwhere)) :
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                if (sqlACTIONS("INSERT", "dvi_confirmed_itinerary_guide_feedback", $arrFields, $arrValues, '')) :
                    if (!empty($itinerary_plan_ID)) :
                        $arrFields_route = array('`guide_trip_completed`');
                        $arrValues_route = array("1");
                        $sqlwhere_route = " `itinerary_route_ID` = '$itinerary_route_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID'";
                        sqlACTIONS("UPDATE", "dvi_confirmed_itinerary_route_details", $arrFields_route, $arrValues_route, $sqlwhere_route);
                    endif;
                    $response['i_result'] = true;
                    $response['redirect_URL'] = "dailymoment.php?formtype=guide&id=$itinerary_plan_ID";
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);
    endif;

else :
    echo "Request Ignored";
endif;
