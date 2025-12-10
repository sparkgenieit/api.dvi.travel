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

        $processed_by = trim($_POST['processed_by']);
        $mode_of_payment = trim($_POST['mode_of_payment']);
        $utr_number = trim($_POST['utr_number']);
        $payment_amount = trim($_POST['payment_amount']);
        $hidden_itinerary_ID = trim($_POST['hidden_itinerary_hotel_ID']);
        $itinerary_route_date = trim($_POST['hidden_hotel_route_date']);
        $hotel_ID = trim($_POST['hidden_hotel_id']);
        $acc_hotel_detail_id = $_POST['hidden_acc_hotel_detail_id'];
        $new_file_name = trim($_FILES['accounts_uploadimage']['name']);


        if (empty($processed_by)) :
            $errors['hotel_processed_by_required'] = true;
        endif;
        if (empty($mode_of_payment)) :
            $errors['hotel_mode_of_payment_required'] = true;
        endif;
        if (empty($utr_number)) :
            $errors['hotel_utr_number_required'] = true;
        endif;
        if (empty($payment_amount)) :
            $errors['hotel_payment_amount_required'] = true;
        endif;

        // File upload paths
        $uploadDir = '../../uploads/accounts_payment/';

        // Handle site logo upload
        if (!empty($_FILES['accounts_uploadimage']['name'])) {
            $_payment_screenshot = uploadFile('accounts_uploadimage', $uploadDir, $errors, 'accounts_uploadimage');
        } else {
            $_payment_screenshot = $new_file_name; // Use existing logo if no new file is uploaded
        }

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($payment_amount != '' && $payment_amount != 0) :

                $payout_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $hotel_ID, 'total_payout_amount');
                $total_payout_amount = $payout_amount + $payment_amount;

                $arrFields = array('`total_payout_amount`');
                $arrValues = array("$total_payout_amount");
                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_ID'";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_details", $arrFields, $arrValues, $sqlWhere)) :
                    $total_balance = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $hotel_ID, 'total_balance');
                    $total_balance_amount = $total_balance - $payment_amount;

                    $paid_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $hotel_ID, 'total_paid_hotel_amount');
                    $total_paid_amount = $paid_amount + $payment_amount;

                    $arrhotelFields = array('`total_paid`', '`total_balance`');
                    $arrhotelValues = array("$total_paid_amount", "$total_balance_amount");
                    $sqlhotelWhere = " `itinerary_plan_ID` = '$hidden_itinerary_ID' AND `hotel_id` = '$hotel_ID' AND `accounts_itinerary_hotel_details_ID` = '$acc_hotel_detail_id' ";
                    if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_hotel_details", $arrhotelFields, $arrhotelValues, $sqlhotelWhere)) :
                    endif;

                    $select_itinerary_hotel_daywise_details = sqlQUERY_LABEL("SELECT `accounts_itinerary_hotel_details_ID`, `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_ID' and `hotel_id` = '$hotel_ID' and `accounts_itinerary_hotel_details_ID` = '$acc_hotel_detail_id'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                    if (sqlNUMOFROW_LABEL($select_itinerary_hotel_daywise_details) > 0):
                        while ($fetch_hotel_details = sqlFETCHARRAY_LABEL($select_itinerary_hotel_daywise_details)):
                            $accounts_itinerary_hotel_details_ID = $fetch_hotel_details['accounts_itinerary_hotel_details_ID'];
                            $accounts_itinerary_details_ID = $fetch_hotel_details['accounts_itinerary_details_ID'];
                        endwhile;
                    endif;

                    $current_date = date("Y-m-d H:i:s");
                    $arrhoteltransactionFields = array('`accounts_itinerary_hotel_details_ID`', '`accounts_itinerary_details_ID`', '`transaction_amount`', '`transaction_date`', '`transaction_done_by`', '`mode_of_pay`', '`transaction_utr_no`', '`transaction_attachment`', '`createdby`', '`status`');
                    $arrhoteltransactionValues = array("$accounts_itinerary_hotel_details_ID", "$accounts_itinerary_details_ID", "$payment_amount", "$current_date", "$processed_by", "$mode_of_payment", "$utr_number", "$_payment_screenshot", "$logged_user_id", "1");

                    if (sqlACTIONS("INSERT", "dvi_accounts_itinerary_hotel_transaction_history", $arrhoteltransactionFields, $arrhoteltransactionValues, '')) :
                    endif;

                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;

                //CONFIRMATION EMAIL

                if ($response['result_success'] == true):

                    $confirmed_itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'itinerary_quote_ID');
                    $primary_customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_ID, 'primary_customer_name');
                    $total_adult = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'total_adult');
                    $total_children = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'total_children');
                    $total_infants = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'total_infants');
                    $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'preferred_room_count');
                    $food_type_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'food_type');
                    $food_type = getFOODTYPE($food_type_id, 'label');
                    $hotel_name = getHOTEL_DETAIL($hotel_ID, '', 'label');
                    $hotel_email = getHOTEL_DETAIL($hotel_ID, '', 'hotel_email');
                    $hotel_address = getHOTEL_DETAIL($hotel_ID, '', 'hotel_address');
                    $agent_id = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_ID, 'agent_id');
                    $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
                    $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
                    $agent_email = getAGENT_details($agent_id, '', 'get_agent_email_address');


                    //HOTEL ASSIGNED TO ONE DAY 
                    $get_room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($hidden_itinerary_ID, $itinerary_route_date, 'get_room_type_id');
                    $check_in_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($hotel_ID, $get_room_type_id, 'check_in_time')));
                    $check_out_time = date('h:i A', strtotime(getHOTEL_ROOM_DETAILS($hotel_ID, $get_room_type_id, 'check_out_time')));
                    $check_in_date = date('M d, Y', strtotime($itinerary_route_date)) . ' ' . $check_in_time;
                    $check_out_date = date('M d, Y', strtotime($itinerary_route_date . ' +1 day')) . ' ' . $check_out_time;
                    $room_type_title = getROOMTYPE_DETAILS($get_room_type_id, 'room_type_title');

                    $mealplandetails = getMEALPLAN_DETAILS_FOR_CONFIRMED_ITINEARY_PLAN($hidden_itinerary_ID, $itinerary_route_date);
                    /* if ($i == 0) :
                         $meal_plan_details = str_replace("Breakfast, ", "", $mealplandetails);
                     elseif ($i == (count($hotel_id) - 1)) :
                         $meal_plan_details = str_replace(", Dinner", "", $mealplandetails);
                     else :
                         $meal_plan_details = $mealplandetails;
                     endif; */

                    $meal_plan_details = $mealplandetails;

                    $roomDetails = getRoomDetails($hidden_itinerary_ID, $itinerary_route_date);
                    $formatRoomDetails = formatRoomDetails(roomDetails: $roomDetails);
                    /* $formatMealPlanDetails = getCONFIRMED_ITINENARY_DETAILS_FOR_HOTEL_VOUCHER($hidden_itinerary_ID, $hotel_ID, $itinerary_route_date, '', 'meal_plan_with_cost'); */
                    $occupancyDetails = getOccupancyDetails($hidden_itinerary_ID, $itinerary_route_date);
                    $formattedoccupancyDetails = formatOccupancyDetails($occupancyDetails);


                    // Set global variables      
                    global $confirmed_itinerary_quote_ID, $primary_customer_name, $hotel_ID, $hotel_name, $hotel_address, $check_in_date, $check_out_date, $room_type_title, $total_adult, $total_children, $total_infants, $preferred_room_count, $meal_plan_details, $formatRoomDetails, $formatMealPlanDetails, $food_type,  $accounts_itinerary_hotel_details_ID, $payment_amount, $hidden_itinerary_ID, $hotel_email, $agent_email, $travel_expert_staff_email;

                    // Assign values to global variables
                    $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_ID;
                    $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                    $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                    $_SESSION['global_hotel_ID'] = $hotel_ID;
                    $_SESSION['global_hotel_name'] = $hotel_name;
                    $_SESSION['global_hotel_email'] = $hotel_email;
                    $_SESSION['global_hotel_address'] = $hotel_address;
                    $_SESSION['global_check_in_date'] = $check_in_date;
                    $_SESSION['global_check_out_date'] = $check_out_date;
                    $_SESSION['global_room_type_title'] = $room_type_title;
                    $_SESSION['global_total_adult'] = $total_adult;
                    $_SESSION['global_total_children'] = $total_children;
                    $_SESSION['global_total_infants'] = $total_infants;
                    $_SESSION['global_preferred_room_count'] = $preferred_room_count;
                    $_SESSION['global_meal_plan_details'] = $meal_plan_details;
                    $_SESSION['global_formatRoomDetails'] = $formatRoomDetails;
                    $_SESSION['global_formattedoccupancyDetails'] = $formattedoccupancyDetails;
                    $_SESSION['global_food_type'] = $food_type;
                    $_SESSION['global_accounts_itinerary_hotel_details_ID'] = $accounts_itinerary_hotel_details_ID;
                    $_SESSION['global_payment_amount'] = $payment_amount;
                    $_SESSION['global_agent_email'] = $agent_email;
                    $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;

                    // Include the email notification script
                    include('ajax_accounts_hotel_payment_email_notification.php');

                    // Assign values to global variables
                    unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                    unset($_SESSION['global_primary_customer_name']);
                    unset($_SESSION['global_hotel_ID']);
                    unset($_SESSION['global_hotel_name']);
                    unset($_SESSION['global_hotel_email']);
                    unset($_SESSION['global_hotel_address']);
                    unset($_SESSION['global_check_in_date']);
                    unset($_SESSION['global_check_out_date']);
                    unset($_SESSION['global_room_type_title']);
                    unset($_SESSION['global_total_adult']);
                    unset($_SESSION['global_total_children']);
                    unset($_SESSION['global_total_infants']);
                    unset($_SESSION['global_preferred_room_count']);
                    unset($_SESSION['global_meal_plan_details']);
                    unset($_SESSION['global_formatRoomDetails']);
                    unset($_SESSION['global_formattedoccupancyDetails']);
                    unset($_SESSION['global_food_type']);
                    unset($_SESSION['global_accounts_itinerary_hotel_details_ID']);
                    unset($_SESSION['global_payment_amount']);
                    unset($_SESSION['global_hidden_itinerary_plan_id']);
                    unset($_SESSION['global_agent_email']);
                    unset($_SESSION['global_travel_expert_staff_email']);
                endif;


            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'vehicle_basic_info') :

        $errors = [];
        $response = [];

        $processed_by = trim($_POST['processed_by']);
        $mode_of_payment = trim($_POST['mode_of_payment']);
        $utr_number = trim($_POST['utr_number']);
        $payment_amount = trim($_POST['payment_amount_vehicle']);
        $hidden_itinerary_ID = trim($_POST['hidden_itinerary_vehicle_ID']);
        $vehicle_ID = trim($_POST['hidden_vehicle_id']);

        $new_file_name = trim($_FILES['accounts_vehicle_uploadimage']['name']);


        if (empty($processed_by)) :
            $errors['vehicle_processed_by_required'] = true;
        endif;
        if (empty($mode_of_payment)) :
            $errors['vehicle_mode_of_payment_required'] = true;
        endif;
        if (empty($utr_number)) :
            $errors['vehicle_utr_number_required'] = true;
        endif;
        if (empty($payment_amount)) :
            $errors['vehicle_payment_amount_required'] = true;
        endif;


        // File upload paths
        $uploadDir = '../../uploads/accounts_payment/';

        // Handle site logo upload
        if (!empty($_FILES['accounts_vehicle_uploadimage']['name'])) {
            $_payment_screenshot = uploadFile('accounts_vehicle_uploadimage', $uploadDir, $errors, 'accounts_vehicle_uploadimage');
        } else {
            $_payment_screenshot = $new_file_name; // Use existing logo if no new file is uploaded
        }

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($payment_amount != '' && $payment_amount != 0) :

                $payout_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, '', 'total_payout_amount');
                $total_payout_amount = $payout_amount + $payment_amount;

                $arrFields = array('`total_payout_amount`');
                $arrValues = array("$total_payout_amount");
                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_ID'";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_details", $arrFields, $arrValues, $sqlWhere)) :

                    $total_balance = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $vehicle_ID, 'total_balance_vehicle');
                    $total_balance_amount = $total_balance - $payment_amount;

                    $paid_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $vehicle_ID, 'total_paid_vehicle_amount');
                    $total_paid_amount = $paid_amount + $payment_amount;

                    $arrvehicleFields = array('`total_paid`', '`total_balance`');
                    $arrvehicleValues = array("$total_paid_amount", "$total_balance_amount");
                    $sqlvehicleWhere = "`itinerary_plan_ID` = '$hidden_itinerary_ID' AND `vehicle_id` = '$vehicle_ID'";
                    if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_vehicle_details", $arrvehicleFields, $arrvehicleValues, $sqlvehicleWhere)) :

                        $select_itinerary_vehicle_details = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_details_ID`, `accounts_itinerary_details_ID`, `vehicle_type_id`, `vendor_id`, `vendor_branch_id`, `total_vehicle_qty`, `total_purchase` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_ID' and `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_itinerary_vehicle_details) > 0):
                            while ($fetch_vehicle_details = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_details)):
                                $accounts_itinerary_vehicle_details_ID = $fetch_vehicle_details['accounts_itinerary_vehicle_details_ID'];
                                $accounts_itinerary_details_ID = $fetch_vehicle_details['accounts_itinerary_details_ID'];
                                $vehicle_type_id = $fetch_vehicle_details['vehicle_type_id'];
                                $vendor_id = $fetch_vehicle_details['vendor_id'];
                                $vendor_branch_id = $fetch_vehicle_details['vendor_branch_id'];
                                $total_vehicle_qty = $fetch_vehicle_details['total_vehicle_qty'];
                                $total_purchase = $fetch_vehicle_details['total_purchase'];
                            endwhile;
                        endif;

                        $current_date = date("Y-m-d H:i:s");
                        $arrvehicletransactionFields = array('`accounts_itinerary_details_ID`', '`accounts_itinerary_vehicle_details_ID`', '`transaction_amount`', '`transaction_date`', '`transaction_done_by`', '`mode_of_pay`', '`transaction_utr_no`', '`transaction_attachment`', '`createdby`', '`status`');
                        $arrvehicletransactionValues = array("$accounts_itinerary_details_ID", "$accounts_itinerary_vehicle_details_ID", "$payment_amount", "$current_date", "$processed_by", "$mode_of_payment", "$utr_number", "$_payment_screenshot", "$logged_user_id", "1");

                        if (sqlACTIONS("INSERT", "dvi_accounts_itinerary_vehicle_transaction_history", $arrvehicletransactionFields, $arrvehicletransactionValues, '')) :
                        endif;
                    endif;

                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;


                //CONFIRMATION EMAIL

                if ($response['result_success'] == true):

                    $confirmed_itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'itinerary_quote_ID');
                    $primary_customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_ID, 'primary_customer_name');
                    $total_adult = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'total_adult');
                    $total_children = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'total_children');
                    $total_infants = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'total_infants');
                    $preferred_room_count = get_ITINEARY_CONFIRMED_PLAN_DETAILS($hidden_itinerary_ID, 'preferred_room_count');
                    $vehicle_type_title = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
                    $vendor_name = getVENDOR_DETAILS($vendor_id, 'label');
                    $vendor_branch = getBranchLIST($vendor_branch_id, 'branch_label');
                    $vendor_email = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_email');
                    $agent_id = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($hidden_itinerary_ID, 'agent_id');
                    $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
                    $travel_expert_staff_email = getTRAVEL_EXPERT($travel_expert_id, 'staff_email');
                    $agent_email = getAGENT_details($agent_id, '', 'get_agent_email_address');


                    // Set global variables      
                    global $confirmed_itinerary_quote_ID, $primary_customer_name, $vehicle_ID, $vehicle_type_title, $vendor_name, $vendor_branch, $vendor_email, $total_adult, $total_children, $total_infants, $total_vehicle_qty, $total_purchase, $accounts_itinerary_vehicle_details_ID, $payment_amount, $hidden_itinerary_ID, $hotel_email, $agent_email, $travel_expert_staff_email;

                    // Assign values to global variables
                    $_SESSION['global_hidden_itinerary_plan_id'] = $hidden_itinerary_ID;
                    $_SESSION['global_confirmed_itinerary_quote_ID'] = $confirmed_itinerary_quote_ID;
                    $_SESSION['global_primary_customer_name'] = $primary_customer_name;
                    $_SESSION['global_vehicle_ID'] = $vehicle_ID;
                    $_SESSION['global_vehicle_type_title'] = $vehicle_type_title;
                    $_SESSION['global_vendor_name'] = $vendor_name;
                    $_SESSION['global_vendor_branch'] = $vendor_branch;
                    $_SESSION['global_vendor_email'] = $vendor_email;
                    $_SESSION['global_total_adult'] = $total_adult;
                    $_SESSION['global_total_children'] = $total_children;
                    $_SESSION['global_total_infants'] = $total_infants;
                    $_SESSION['global_total_vehicle_qty'] = $total_vehicle_qty;
                    $_SESSION['global_total_purchase'] = $total_purchase;
                    $_SESSION['global_accounts_itinerary_vehicle_details_ID'] = $accounts_itinerary_vehicle_details_ID;
                    $_SESSION['global_payment_amount'] = $payment_amount;
                    $_SESSION['global_travel_expert_staff_email'] = $travel_expert_staff_email;

                    // Include the email notification script
                    include('ajax_accounts_vehicle_payment_email_notification.php');

                    // Assign values to global variables
                    unset($_SESSION['global_confirmed_itinerary_quote_ID']);
                    unset($_SESSION['global_primary_customer_name']);
                    unset($_SESSION['global_vehicle_ID']);
                    unset($_SESSION['global_vehicle_type_title']);
                    unset($_SESSION['global_vendor_name']);
                    unset($_SESSION['global_vendor_branch']);
                    unset($_SESSION['global_vendor_email']);
                    unset($_SESSION['global_total_adult']);
                    unset($_SESSION['global_total_children']);
                    unset($_SESSION['global_total_infants']);
                    unset($_SESSION['global_total_vehicle_qty']);
                    unset($_SESSION['global_total_purchase']);
                    unset($_SESSION['global_accounts_itinerary_vehicle_details_ID']);
                    unset($_SESSION['global_payment_amount']);
                    unset($_SESSION['global_hidden_itinerary_plan_id']);
                    unset($_SESSION['global_travel_expert_staff_email']);
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_basic_info') :

        $errors = [];
        $response = [];

        $processed_by = trim($_POST['processed_by']);
        $mode_of_payment = trim($_POST['mode_of_payment']);
        $utr_number = trim($_POST['utr_number']);
        $payment_amount = trim($_POST['payment_amount_guide']);
        $hidden_itinerary_ID = trim($_POST['hidden_itinerary_ID']);
        $guide_ID = trim($_POST['hidden_guide_id']);
        $acc_guide_detail_id = trim($_POST['hidden_acc_guide_detail_id']);

        $new_file_name = trim($_FILES['accounts_uploadimage_guide']['name']);

        if (empty($processed_by)) :
            $errors['guide_processed_by_required'] = true;
        endif;
        if (empty($mode_of_payment)) :
            $errors['guide_mode_of_payment_required'] = true;
        endif;
        if (empty($utr_number)) :
            $errors['guide_utr_number_required'] = true;
        endif;
        if (empty($payment_amount)) :
            $errors['guide_payment_amount_required'] = true;
        endif;


        // File upload paths
        $uploadDir = '../../uploads/accounts_payment/';

        // Handle site logo upload
        if (!empty($_FILES['accounts_uploadimage_guide']['name'])) {
            $_payment_screenshot = uploadFile('accounts_uploadimage_guide', $uploadDir, $errors, 'accounts_uploadimage_guide');
        } else {
            $_payment_screenshot = $new_file_name; // Use existing logo if no new file is uploaded
        }

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($payment_amount != '' && $payment_amount != 0) :

                $payout_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, '', 'total_payout_amount');
                $total_payout_amount = $payout_amount + $payment_amount;

                $arrFields = array('`total_payout_amount`');
                $arrValues = array("$total_payout_amount");
                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_ID'";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_details", $arrFields, $arrValues, $sqlWhere)) :

                    $total_balance = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $acc_guide_detail_id, 'total_balance_guide');
                    $total_balance_amount = $total_balance - $payment_amount;

                    $paid_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $acc_guide_detail_id, 'total_paid_guide_amount');
                    $total_paid_amount = $paid_amount + $payment_amount;

                    $arrguideFields = array('`total_paid`', '`total_balance`');
                    $arrguideValues = array("$total_paid_amount", "$total_balance_amount");
                    $sqlguideWhere = "`itinerary_plan_ID` = '$hidden_itinerary_ID' AND `guide_id` = '$guide_ID' AND `accounts_itinerary_guide_details_ID` = '$acc_guide_detail_id'";
                    if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_guide_details", $arrguideFields, $arrguideValues, $sqlguideWhere)) :
                        $select_itinerary_guide_details = sqlQUERY_LABEL("SELECT `accounts_itinerary_guide_details_ID`, `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_ID' and `accounts_itinerary_guide_details_ID` = '$acc_guide_detail_id'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_itinerary_guide_details) > 0):
                            while ($fetch_guide_details = sqlFETCHARRAY_LABEL($select_itinerary_guide_details)):
                                $accounts_itinerary_guide_details_ID = $fetch_guide_details['accounts_itinerary_guide_details_ID'];
                                $accounts_itinerary_details_ID = $fetch_guide_details['accounts_itinerary_details_ID'];
                            endwhile;
                        endif;
                        $current_date = date("Y-m-d H:i:s");
                        $arrguidetransactionFields = array('`accounts_itinerary_details_ID`', '`accounts_itinerary_guide_details_ID`', '`transaction_amount`', '`transaction_date`', '`transaction_done_by`', '`mode_of_pay`', '`transaction_utr_no`', '`transaction_attachment`', '`createdby`', '`status`');
                        $arrguidetransactionValues = array("$accounts_itinerary_details_ID", "$accounts_itinerary_guide_details_ID", "$payment_amount", "$current_date", "$processed_by", "$mode_of_payment", "$utr_number", "$_payment_screenshot", "$logged_user_id", "1");

                        if (sqlACTIONS("INSERT", "dvi_accounts_itinerary_guide_transaction_history", $arrguidetransactionFields, $arrguidetransactionValues, '')) :
                        endif;

                    endif;
                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'hotspot_basic_info') :

        $errors = [];
        $response = [];

        $processed_by = trim($_POST['processed_by']);
        $mode_of_payment = trim($_POST['mode_of_payment']);
        $utr_number = trim($_POST['utr_number']);
        $payment_amount = trim($_POST['payment_amount_hotspot']);
        $hidden_itinerary_ID = trim($_POST['hidden_itinerary_hotspot_ID']);
        $hidden_hotspot_id = trim($_POST['hidden_hotspot_id']);
        $acc_hotspot_detail_id = trim($_POST['hidden_acc_hotspot_detail_id']);

        $new_file_name = trim($_FILES['accounts_uploadimage_hotspot']['name']);

        if (empty($processed_by)) :
            $errors['hotspot_processed_by_required'] = true;
        endif;
        if (empty($mode_of_payment)) :
            $errors['hotspot_mode_of_payment_required'] = true;
        endif;
        if (empty($utr_number)) :
            $errors['hotspot_utr_number_required'] = true;
        endif;
        if (empty($payment_amount)) :
            $errors['hotspot_payment_amount_required'] = true;
        endif;


        // File upload paths
        $uploadDir = '../../uploads/accounts_payment/';

        // Handle site logo upload
        if (!empty($_FILES['accounts_uploadimage_hotspot']['name'])) {
            $_payment_screenshot = uploadFile('accounts_uploadimage_hotspot', $uploadDir, $errors, 'accounts_uploadimage_hotspot');
        } else {
            $_payment_screenshot = $new_file_name; // Use existing logo if no new file is uploaded
        }

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($payment_amount != '' && $payment_amount != 0) :

                $payout_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, '', 'total_payout_amount');
                $total_payout_amount = $payout_amount + $payment_amount;

                $arrFields = array('`total_payout_amount`');
                $arrValues = array("$total_payout_amount");
                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_ID'";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_details", $arrFields, $arrValues, $sqlWhere)) :

                    $total_balance = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $acc_hotspot_detail_id, 'total_balance_hotspot');
                    $total_balance_amount = $total_balance - $payment_amount;

                    $paid_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $acc_hotspot_detail_id, 'total_paid_hotspot_amount');
                    $total_paid_amount = $paid_amount + $payment_amount;

                    $arrhotspotFields = array('`total_paid`', '`total_balance`');
                    $arrhotspotValues = array("$total_paid_amount", "$total_balance_amount");
                    $sqlhotspotWhere = "`itinerary_plan_ID` = '$hidden_itinerary_ID' AND `hotspot_ID` = '$hidden_hotspot_id' AND `accounts_itinerary_hotspot_details_ID` = '$acc_hotspot_detail_id'";

                    if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_hotspot_details", $arrhotspotFields, $arrhotspotValues, $sqlhotspotWhere)) :
                        $select_itinerary_hotspot_details = sqlQUERY_LABEL("SELECT `accounts_itinerary_hotspot_details_ID`, `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_ID' and `accounts_itinerary_hotspot_details_ID` = '$acc_hotspot_detail_id'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_itinerary_hotspot_details) > 0):
                            while ($fetch_hotspot_details = sqlFETCHARRAY_LABEL($select_itinerary_hotspot_details)):
                                $accounts_itinerary_hotspot_details_ID = $fetch_hotspot_details['accounts_itinerary_hotspot_details_ID'];
                                $accounts_itinerary_details_ID = $fetch_hotspot_details['accounts_itinerary_details_ID'];
                            endwhile;
                        endif;
                        $current_date = date("Y-m-d H:i:s");
                        $arrhotspottransactionFields = array('`accounts_itinerary_details_ID`', '`accounts_itinerary_hotspot_details_ID`', '`transaction_amount`', '`transaction_date`', '`transaction_done_by`', '`mode_of_pay`', '`transaction_utr_no`', '`transaction_attachment`', '`createdby`', '`status`');
                        $arrhotspottransactionValues = array("$accounts_itinerary_details_ID", "$accounts_itinerary_hotspot_details_ID", "$payment_amount", "$current_date", "$processed_by", "$mode_of_payment", "$utr_number", "$_payment_screenshot", "$logged_user_id", "1");

                        if (sqlACTIONS("INSERT", "dvi_accounts_itinerary_hotspot_transaction_history", $arrhotspottransactionFields, $arrhotspottransactionValues, '')) :
                        endif;
                    endif;
                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);
    elseif ($_GET['type'] == 'activity_basic_info') :

        $errors = [];
        $response = [];

        $processed_by = trim($_POST['processed_by']);
        $mode_of_payment = trim($_POST['mode_of_payment']);
        $utr_number = trim($_POST['utr_number']);
        $payment_amount = trim($_POST['payment_amount_activity']);
        $hidden_itinerary_ID = trim($_POST['hidden_itinerary_activity_ID']);
        $hidden_activity_id = trim($_POST['hidden_activity_id']);
        $acc_activity_detail_id = trim($_POST['hidden_acc_activity_detail_id']);

        $new_file_name = trim($_FILES['accounts_uploadimage_activity']['name']);

        if (empty($processed_by)) :
            $errors['activity_processed_by_required'] = true;
        endif;
        if (empty($mode_of_payment)) :
            $errors['activity_mode_of_payment_required'] = true;
        endif;
        if (empty($utr_number)) :
            $errors['activity_utr_number_required'] = true;
        endif;
        if (empty($payment_amount)) :
            $errors['activity_payment_amount_required'] = true;
        endif;


        // File upload paths
        $uploadDir = '../../uploads/accounts_payment/';

        // Handle site logo upload
        if (!empty($_FILES['accounts_uploadimage_activity']['name'])) {
            $_payment_screenshot = uploadFile('accounts_uploadimage_activity', $uploadDir, $errors, 'accounts_uploadimage_activity');
        } else {
            $_payment_screenshot = $new_file_name; // Use existing logo if no new file is uploaded
        }

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($payment_amount != '' && $payment_amount != 0) :

                $payout_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, '', 'total_payout_amount');
                $total_payout_amount = $payout_amount + $payment_amount;

                $arrFields = array('`total_payout_amount`');
                $arrValues = array("$total_payout_amount");
                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_ID'";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_details", $arrFields, $arrValues, $sqlWhere)) :

                    $total_balance = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $acc_activity_detail_id, 'total_balance_activity');
                    $total_balance_amount = $total_balance - $payment_amount;

                    $paid_amount = getACCOUNTS_MANAGER_DETAILS($hidden_itinerary_ID, $acc_activity_detail_id, 'total_paid_activity_amount');
                    $total_paid_amount = $paid_amount + $payment_amount;

                    $arractivityFields = array('`total_paid`', '`total_balance`');
                    $arractivityValues = array("$total_paid_amount", "$total_balance_amount");
                    $sqlactivityWhere = "`itinerary_plan_ID` = '$hidden_itinerary_ID' AND `activity_ID` = '$hidden_activity_id' AND `accounts_itinerary_activity_details_ID` = '$acc_activity_detail_id'";

                    if (sqlACTIONS("UPDATE", "dvi_accounts_itinerary_activity_details", $arractivityFields, $arractivityValues, $sqlactivityWhere)) :
                        $select_itinerary_activity_details = sqlQUERY_LABEL("SELECT `accounts_itinerary_activity_details_ID`, `accounts_itinerary_details_ID` FROM `dvi_accounts_itinerary_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_ID' and `accounts_itinerary_activity_details_ID` = '$acc_activity_detail_id'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                        if (sqlNUMOFROW_LABEL($select_itinerary_activity_details) > 0):
                            while ($fetch_activity_details = sqlFETCHARRAY_LABEL($select_itinerary_activity_details)):
                                $accounts_itinerary_activity_details_ID = $fetch_activity_details['accounts_itinerary_activity_details_ID'];
                                $accounts_itinerary_details_ID = $fetch_activity_details['accounts_itinerary_details_ID'];
                            endwhile;
                        endif;
                        $current_date = date("Y-m-d H:i:s");
                        $arractivitytransactionFields = array('`accounts_itinerary_details_ID`', '`accounts_itinerary_activity_details_ID`', '`transaction_amount`', '`transaction_date`', '`transaction_done_by`', '`mode_of_pay`', '`transaction_utr_no`', '`transaction_attachment`', '`createdby`', '`status`');
                        $arractivitytransactionValues = array("$accounts_itinerary_details_ID", "$accounts_itinerary_activity_details_ID", "$payment_amount", "$current_date", "$processed_by", "$mode_of_payment", "$utr_number", "$_payment_screenshot", "$logged_user_id", "1");

                        if (sqlACTIONS("INSERT", "dvi_accounts_itinerary_activity_transaction_history", $arractivitytransactionFields, $arractivitytransactionValues, '')) :
                        endif;
                    endif;
                    $response['u_result'] = true;
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
function uploadFile($inputName, $uploadDir, $errors, $errorKey)
{
    $file = $_FILES[$inputName];
    $tmpFile = $file['tmp_name'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $validExtensions = ['jpg', 'jpeg', 'png'];

    if (!in_array($fileExtension, $validExtensions)) {
        $errors[$errorKey . '_invalid_type'] = true;
        return '';
    }

    $fileName = uniqid() . '.' . $fileExtension;
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($tmpFile, $filePath)) {
        return $fileName;
    } else {
        $errors[$errorKey . '_upload_error'] = true;
        return '';
    }
}
