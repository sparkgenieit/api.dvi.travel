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

    if ($_GET['type'] == 'vendor_basic_info') :

        $errors = [];
        $response = [];

        $_vendor_name = trim($_POST['vendor_name']);
        $_vendor_email = trim($_POST['vendor_email']);
        $_vendor_primary_mobile_number = trim($_POST['vendor_primary_mobile_number']);
        $_vendor_alternative_mobile_number = $_POST['vendor_alternative_mobile_number'];
        $_vendor_country = $_POST['vendor_country'];
        $_vendor_state = trim($_POST['vendor_state']);
        $_vendor_city = $_POST['vendor_city'];
        $_vendor_pincode = trim($_POST['vendor_pincode']);
        $_vendor_othernumber = trim($_POST['vendor_othernumber']);
        $_vendor_margin = trim($_POST['vendor_margin']);
        $_vendor_address = trim($_POST['vendor_address']);
        $_vendor_company_name = trim($_POST['vendor_company_name']);
        $_invoice_gstin_number = trim($_POST['invoice_gstin_number']);
        $_invoice_pan_number = trim($_POST['invoice_pan_number']);
        $_invoice_pincode = trim($_POST['invoice_pincode']);
        $_invoice_mobile_number = trim($_POST['invoice_mobile_number']);
        $_invoice_email = trim($_POST['invoice_email']);
        $_invoice_address = trim($_POST['invoice_address']);
        if (isset($_FILES['invoice_logo']['name'])) :
            $upload_dir = '../../uploads/logo/';
            $fileName = $_FILES["invoice_logo"]["name"];
            $fileInfo = pathinfo($fileName);
            $fileExtension = $fileInfo["extension"];
            $file_type = $_FILES['invoice_logo']['type'];
            $file_name = $fileName;
            $file_temp_loc  = $_FILES['invoice_logo']['tmp_name'];
            $file_error_msg = $_FILES['invoice_logo']['error'];
            $file_size = $_FILES['invoice_logo']['size'];
            $invoice_logo_move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);
        else:
            $errors['invoice_logo_required'] = true;
        endif;

        if ($invoice_logo_move_file) :
            $add_logo_arrField = array('`invoice_logo`');
            $add_logo_arrValue = array("$file_name");
        endif;
        $_vendor_margin_gst_type = trim($_POST['vendor_margin_gst_type']);
        $_vendor_margin_gst_percentage = trim($_POST['vendor_margin_gst_percentage']);

        $_vendor_select_role = trim($_POST['vendor_select_role']);
        $_vendor_username = trim($_POST['vendor_username']);
        $_vendor_password = trim($_POST['vendor_password']);
        $hidden_vendor_ID = $_POST['hidden_vendor_ID'];

        $firstThreeLetters = substr($_vendor_city, 0, 3);
        $vendor_referance = "DVIV-$firstThreeLetters"; // Your prefix value
        $randomNumber = mt_rand(1, 1000000); // Generate a random number between 1 and 100
        $_vendor_code = $vendor_referance . $randomNumber;

        if (empty($_vendor_name)) :
            $errors['vendor_name_required'] = true;
        endif;

        if (empty($_vendor_primary_mobile_number)) :
            $errors['vendor_primary_mobile_number_required'] = true;
        endif;
        if (empty($_vendor_alternative_mobile_number)) :
            $errors['vendor_alternative_mobile_number_required'] = true;
        endif;
        if (empty($_vendor_country)) :
            $errors['vendor_country_required'] = true;
        endif;
        if (empty($_vendor_state)) :
            $errors['vendor_state_required'] = true;
        endif;
        if (empty($_vendor_city)) :
            $errors['vendor_city_required'] = true;
        endif;
        if (empty($_vendor_pincode)) :
            $errors['vendor_pincode_required'] = true;
        endif;
        if (empty($_vendor_address)) :
            $errors['vendor_address_required'] = true;
        endif;
        if ($_vendor_margin == "") :
            $errors['vendor_margin_required'] = true;
        endif;
        if (empty($_vendor_margin_gst_type)) :
            $errors['vendor_margin_gst_type_required'] = true;
        endif;
        if (empty($_vendor_margin_gst_percentage)) :
            $errors['vendor_margin_gst_percentage_required'] = true;
        endif;
        if (empty($_vendor_company_name)) :
            $errors['vendor_company_name_required'] = true;
        endif;
        if (empty($_invoice_gstin_number)) :
            $errors['invoice_gstin_number_required'] = true;
        endif;
        if (empty($_invoice_pan_number)) :
            $errors['invoice_pan_number_required'] = true;
        endif;
        if (empty($_invoice_pincode)) :
            $errors['invoice_pincode_required'] = true;
        endif;
        if (empty($_invoice_mobile_number)) :
            $errors['invoice_mobile_number_required'] = true;
        endif;
        if (empty($_invoice_email)) :
            $errors['invoice_email_required'] = true;
        endif;
        if (empty($_invoice_address)) :
            $errors['invoice_address_required'] = true;
        endif;

        if ($hidden_vendor_ID == '') :

            if (empty($_vendor_email)) :
                $errors['vendor_email_required'] = true;
            endif;
            if (empty($_vendor_select_role)) :
                $errors['vendor_select_role_required'] = true;
            endif;
            if (empty($_vendor_username)) :
                $errors['vendor_username_required'] = true;
            endif;
            if (empty($_vendor_password)) :
                $errors['vendor_password_required'] = true;
            endif;
        endif;

        // $check_email_already_exist = CHECK_USERNAME($_vendor_email, 'useremail', '');

        // if ($check_email_already_exist > 0) :
        //     $errors['vendor_email_already_exist'] = true;
        // endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($hidden_vendor_ID != '' && $hidden_vendor_ID != 0) :
                $sqlWhere = " `vendor_id` = '$hidden_vendor_ID' ";

                $arrFields = array('`vendor_name`', '`vendor_code`', '`vendor_email`', '`vendor_primary_mobile_number`', '`vendor_alternative_mobile_number`', '`vendor_country`', '`vendor_state`', '`vendor_city`', '`vendor_pincode`', '`vendor_othernumber`', '`vendor_margin`', '`vendor_margin_gst_type`', '`vendor_margin_gst_percentage`', '`vendor_address`', '`vendor_company_name`', '`invoice_gstin_number`', '`invoice_pan_number`', '`invoice_pincode`', '`invoice_mobile_number`', '`invoice_email`', '`invoice_address`');

                $arrValues = array("$_vendor_name", "$_vendor_code", "$_vendor_email",  "$_vendor_primary_mobile_number", "$_vendor_alternative_mobile_number", "$_vendor_country", "$_vendor_state", "$_vendor_city", "$_vendor_pincode", "$_vendor_othernumber", "$_vendor_margin", "$_vendor_margin_gst_type", "$_vendor_margin_gst_percentage",  "$_vendor_address", "$_vendor_company_name", "$_invoice_gstin_number", "$_invoice_pan_number", "$_invoice_pincode", "$_invoice_mobile_number", "$_invoice_email", "$_invoice_address");

                if ($invoice_logo_move_file) :
                    $arrFields = array_merge($arrFields, $add_logo_arrField);
                    $arrValues = array_merge($arrValues, $add_logo_arrValue);
                else :
                    $arrFields = $arrFields;
                    $arrValues = $arrValues;
                endif;

                //UPDATE VENDOR DETAILS
                if (sqlACTIONS("UPDATE", "dvi_vendor_details", $arrFields, $arrValues, $sqlWhere)) :
                    if (!empty($_vendor_password)) :
                        $pwd_hash = PwdHash($_vendor_password);
                        $usertoken = md5($_vendor_password);
                        $arrFields_users = array('`useremail`', '`roleID`', '`usertoken`', '`password`');
                        $arrValues_users = array("$_vendor_email", "$_vendor_select_role", "$usertoken", "$pwd_hash");
                        $sqlwhere_users = " `vendor_id` = '$hidden_vendor_ID' ";
                        sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users);
                    else:
                        $arrFields_users = array('`useremail`', '`roleID`');
                        $arrValues_users = array("$_vendor_email", "$_vendor_select_role");
                        $sqlwhere_users = " `vendor_id` = '$hidden_vendor_ID' ";
                        sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users);
                    endif;
                    /*  if (!empty($_vendor_username)) :
                        $arrFields_users = array('`username`');
                        $arrValues_users = array("$_vendor_username");
                        $sqlwhere_users = " `vendor_id` = '$hidden_vendor_ID' ";
                        (sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users));
                    endif;*/
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'newvendor.php?route=edit&formtype=branch_info&id=' . $hidden_vendor_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :

                $arrFields = array('`vendor_name`', '`vendor_code`', '`vendor_email`', '`vendor_primary_mobile_number`', '`vendor_alternative_mobile_number`', '`vendor_country`', '`vendor_state`', '`vendor_city`', '`vendor_pincode`', '`vendor_othernumber`', '`vendor_margin`', '`vendor_margin_gst_type`', '`vendor_margin_gst_percentage`', '`vendor_address`', '`vendor_company_name`', '`invoice_gstin_number`', '`invoice_pan_number`', '`invoice_pincode`', '`invoice_mobile_number`', '`invoice_email`', '`invoice_address`', '`createdby`', '`status`');

                $arrValues = array("$_vendor_name", "$_vendor_code", "$_vendor_email",  "$_vendor_primary_mobile_number", "$_vendor_alternative_mobile_number", "$_vendor_country", "$_vendor_state", "$_vendor_city", "$_vendor_pincode", "$_vendor_othernumber", "$_vendor_margin", "$_vendor_margin_gst_type", "$_vendor_margin_gst_percentage",  "$_vendor_address", "$_vendor_company_name", "$_invoice_gstin_number", "$_invoice_pan_number", "$_invoice_pincode", "$_invoice_mobile_number", "$_invoice_email", "$_invoice_address", "$logged_user_id", "1");

                if ($invoice_logo_move_file) :
                    $arrFields = array_merge($arrFields, $add_logo_arrField);
                    $arrValues = array_merge($arrValues, $add_logo_arrValue);
                else :
                    $arrFields = $arrFields;
                    $arrValues = $arrValues;
                endif;

                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_vendor_details", $arrFields, $arrValues, '')) :
                    $vendor_id = sqlINSERTID_LABEL();
                    //INSERT USERS TABLE
                    $pwd_hash = PwdHash($_vendor_password);
                    $usertoken = md5($_vendor_password);
                    $arrFields_users = array('`vendor_id`', '`usertoken`', '`username`', '`useremail`', '`password`', '`roleID`', '`userapproved`', '`createdby`', '`status`');
                    $arrValues_users = array("$vendor_id", "$usertoken", "$_vendor_username", "$_vendor_email", "$pwd_hash", "$_vendor_select_role", "1", "$logged_user_id", "1");
                    if (sqlACTIONS("INSERT", "dvi_users", $arrFields_users, $arrValues_users, '')) :
                        $response['i_result'] = true;
                        $response['redirect_URL'] = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_id;
                        $response['result_success'] = true;

                    endif;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'vendor_branch') :

        $errors = [];
        $response = [];

        $_vendor_branch_name = $_POST['vendor_branch_name'];
        $_vendor_branch_emailid = $_POST['vendor_branch_emailid'];
        $_vendor_branch_primary_mobile_number = $_POST['vendor_branch_primary_mobile_number'];
        $_vendor_branch_alternative_mobile_number = $_POST['vendor_branch_alternative_mobile_number'];
        $_vendor_branch_country = $_POST['vendor_branch_country'];
        $_vendor_branch_state = $_POST['vendor_branch_state'];
        $_vendor_branch_city = $_POST['vendor_branch_city'];
        $_vendor_branch_pincode = $_POST['vendor_branch_pincode'];
        $_vendor_branch_location = $_POST['vendor_branch_location'];
        $_vendor_branch_gst = $_POST['vendor_branch_gst'];
        $_vendor_branch_gst_type = $_POST['vendor_branch_gst_type'];
        $_vendor_branch_address = $_POST['vendor_branch_address'];

        if (empty($_vendor_branch_name)) :
            $errors['vendor_branch_name_required'] = true;
        endif;
        if (empty($_vendor_branch_emailid)) :
            $errors['vendor_branch_email_required'] = true;
        endif;
        if (empty($_vendor_branch_primary_mobile_number)) :
            $errors['branch_primary_mobile_required'] = true;
        endif;

        if (empty($_vendor_branch_alternative_mobile_number)) :
            $errors['alternative_primary_mobile_required'] = true;
        endif;
        if (empty($_vendor_branch_country)) :
            $errors['vendor_country'] = true;
        endif;

        if (empty($_vendor_branch_state)) :
            $errors['vendor_state_required'] = true;
        endif;
        if (empty($_vendor_branch_city)) :
            $errors['vendor_city_required'] = true;
        endif;
        if (empty($_vendor_branch_location)) :
            $errors['vendor_branch_place_required'] = true;
        endif;
        if (empty($_vendor_branch_pincode)) :
            $errors['vendor_pincode_required'] = true;
        endif;
        if (empty($_vendor_branch_address)) :
            $errors['vendor_address_required'] = true;
        endif;

        if (empty($_vendor_branch_gst)) :
            $errors['vendor_gst_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            foreach ($_vendor_branch_name as $key => $val) :

                $count = count($_vendor_branch_name);
                $selected_vendor_branch_name = trim($_POST['vendor_branch_name'][$key]);
                $selected_vendor_branch_emailid = trim($_POST['vendor_branch_emailid'][$key]);
                $selected_vendor_branch_primary_mobile_number = $_POST['vendor_branch_primary_mobile_number'][$key];
                $selected_vendor_branch_alternative_mobile_number = $_POST['vendor_branch_alternative_mobile_number'][$key];
                $selected_vendor_branch_country = $_POST['vendor_branch_country'][$key];
                $selected_vendor_branch_state = trim($_POST['vendor_branch_state'][$key]);
                $selected_vendor_branch_city = $_POST['vendor_branch_city'][$key];
                $selected_vendor_branch_pincode = $_POST['vendor_branch_pincode'][$key];
                $selected_vendor_branch_location = $_POST['vendor_branch_location'][$key];
                $selected_vendor_branch_gst_type = $_POST['vendor_branch_gst_type'][$key];
                $selected_vendor_branch_gst = $_POST['vendor_branch_gst'][$key];
                $selected_vendor_branch_address = $_POST['vendor_branch_address'][$key];
                $selected_vendor_id = $_POST['hidden_vendor_id'][0];
                $selected_vendor_branch_id = $_POST['hidden_vendor_branch_id'][$key];

                if ($selected_vendor_branch_id != '' && $selected_vendor_branch_id != 0) :

                    $branch_arrFields = array('`vendor_id`', '`vendor_branch_name`',  '`vendor_branch_emailid`', '`vendor_branch_primary_mobile_number`', '`vendor_branch_alternative_mobile_number`', '`vendor_branch_country`', '`vendor_branch_state`', ' `vendor_branch_city`', '`vendor_branch_pincode`', '`vendor_branch_location`', '`vendor_branch_gst_type`', '`vendor_branch_gst`', '`vendor_branch_address`', '`createdby`', '`status`');

                    $branch_arrValues = array("$selected_vendor_id", "$selected_vendor_branch_name",  "$selected_vendor_branch_emailid", "$selected_vendor_branch_primary_mobile_number", "$selected_vendor_branch_alternative_mobile_number", "$selected_vendor_branch_country", "$selected_vendor_branch_state", "$selected_vendor_branch_city", "$selected_vendor_branch_pincode", "$selected_vendor_branch_location", "$selected_vendor_branch_gst_type", "$selected_vendor_branch_gst", "$selected_vendor_branch_address", "$logged_user_id", "1");

                    $branch_sqlwhere = " `vendor_branch_id` = '$selected_vendor_branch_id' and `vendor_id`='$selected_vendor_id' ";
                    //UPDATE VENDOR BRANCH
                    if (sqlACTIONS("UPDATE", "dvi_vendor_branches", $branch_arrFields, $branch_arrValues, $branch_sqlwhere)) :
                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $selected_vendor_id;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;
                else :

                    $branch_arrFields = array('`vendor_id`', '`vendor_branch_name`', '`vendor_branch_emailid`', '`vendor_branch_primary_mobile_number`', '`vendor_branch_alternative_mobile_number`', '`vendor_branch_country`', '`vendor_branch_state`', ' `vendor_branch_city`', '`vendor_branch_pincode`', '`vendor_branch_location`', '`vendor_branch_gst_type`', '`vendor_branch_gst`', '`vendor_branch_address`', '`createdby`', '`status`');
                    $branch_arrValues = array("$selected_vendor_id", "$selected_vendor_branch_name", "$selected_vendor_branch_emailid", "$selected_vendor_branch_primary_mobile_number", "$selected_vendor_branch_alternative_mobile_number", "$selected_vendor_branch_country", "$selected_vendor_branch_state", "$selected_vendor_branch_city", "$selected_vendor_branch_pincode", "$selected_vendor_branch_location", "$selected_vendor_branch_gst_type", "$selected_vendor_branch_gst", "$selected_vendor_branch_address", "$logged_user_id", "1");

                    //INSERT VENDOR BRANCH
                    if (sqlACTIONS("INSERT", "dvi_vendor_branches", $branch_arrFields, $branch_arrValues, '')) :
                        $branch_ID = sqlINSERTID_LABEL();
                        $response['i_result'] = true;
                        $response['redirect_URL'] = 'newvendor.php?route=add&formtype=driver_cost&id=' . $selected_vendor_id;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;
                endif;
            endforeach;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'permit_cost') :
        $errors = [];
        $response = [];

        $_vehicle_type = $_POST['vehicle_type'];
        $_selectedState = $_POST['selected_state'];

        $stateInput = $_POST['state_cost'];
        $numStateCosts = count($stateInput);

        $stateInputhidden = $_POST['statehidden_cost'];
        $numDestinations = count($stateInputhidden);

        $hidden_permit_vendor_ID = $_POST['hidden_vendor_ID'];

        if (empty($_vehicle_type)) :
            $errors['vehicle_type_id_required'] = true;
        endif;
        if (empty($_selectedState)) :
            $errors['selectedState_required'] = true;
        endif;
        if (empty($hidden_permit_vendor_ID)) :
            $errors['hidden_permit_vendor_ID_required'] = true;
        endif;

        if (!empty($errors)) {
            $response['success'] = false;
            $response['errors'] = $errors;
        } else {
            $response['success'] = true;

            for ($i = 0; $i < $numDestinations; $i++) {
                $vendor_ID = $hidden_permit_vendor_ID;

                $source_state = $_selectedState;
                $destination_state = $stateInputhidden[$i];

                $state_cost = $stateInput[$i];
                if ($state_cost == '') :
                    $state_cost = 0;
                endif;

                if ($vendor_ID != '' && $vendor_ID != 0 && $_vehicle_type != '' && $_vehicle_type != 0 && $source_state != '' && $source_state != 0 && $destination_state != '' && $destination_state != 0) :
                    $select_permit_cost_list_query = sqlQUERY_LABEL("SELECT `permit_cost_id` FROM `dvi_permit_cost` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID' and `vehicle_type_id`='$_vehicle_type' and `source_state_id`='$source_state' and `destination_state_id` = '$destination_state'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                    while ($fetch_permit_cost_list_data = sqlFETCHARRAY_LABEL($select_permit_cost_list_query)) :
                        $selected_permitcost_ID = $fetch_permit_cost_list_data['permit_cost_id'];
                    endwhile;
                endif;

                $permit_cost_arrFields = array('`vendor_id`', '`vehicle_type_id`', '`source_state_id`', '`destination_state_id`', '`permit_cost`', '`createdby`', '`status`');
                $permit_cost_arrValues = array("$vendor_ID",  "$_vehicle_type", "$source_state", "$destination_state", "$state_cost",  "$logged_user_id", "1");
                //print_r($permit_cost_arrValues);

                if ($selected_permitcost_ID != '' && $selected_permitcost_ID != 0) {
                    $permit_cost_sqlwhere = " `permit_cost_id` = '$selected_permitcost_ID' AND `vehicle_type_id` = '$_vehicle_type' AND `source_state_id` = '$source_state' AND `destination_state_id` = '$destination_state'";
                    // Update permit cost details
                    if (sqlACTIONS("UPDATE", "dvi_permit_cost", $permit_cost_arrFields, $permit_cost_arrValues, $permit_cost_sqlwhere)) {
                        $response['u_result'] = true;
                        $response['result_success'] = true;
                    } else {
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    }
                } else {
                    // Insert permit cost details
                    if (sqlACTIONS("INSERT", "dvi_permit_cost", $permit_cost_arrFields, $permit_cost_arrValues, '')) {
                        $permit_cost_id = sqlINSERTID_LABEL();
                        $response['i_result'] = true;
                        $response['result_success'] = true;
                    } else {
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    }
                }
            }
        }
        //exit;

        echo json_encode($response);

    elseif ($_GET['type'] == 'vendor_vehicle_type') :

        $errors = [];
        $response = [];

        $_vehicle_type = trim($_POST['vehicle_type']);
        $_driver_bhatta = trim($_POST['driver_bhatta']);
        $_food_cost = trim($_POST['food_cost']);
        $_accomdation_cost = trim($_POST['accomdation_cost']);
        $_extra_cost = trim($_POST['extra_cost']);
        $_driver_early_morning_charges = trim($_POST['driver_early_morning_charges']);
        $_driver_evening_charges = trim($_POST['driver_evening_charges']);
        $hidden_vendor_vehicle_type_ID = $_POST['hidden_vendor_vehicle_type_ID'];
        $hidden_vendor_ID = $_POST['hidden_vendor_ID'];


        if (empty($_vehicle_type)) :
            $errors['vehicle_type_required'] = true;
        endif;

        if ($_driver_bhatta == "") :
            $errors['driver_bhatta_required'] = true;
        endif;
        if ($_food_cost == "") :
            $errors['food_cost_required'] = true;
        endif;
        if ($_accomdation_cost == "") :
            $errors['accomdation_cost_required'] = true;
        endif;
        if ($_extra_cost == "") :
            $errors['extra_cost_required'] = true;
        endif;
        if ($_driver_early_morning_charges == "") :
            $errors['driver_early_morning_charges_required'] = true;
        endif;
        if ($_driver_evening_charges == "") :
            $errors['driver_evening_charges_required'] = true;
        endif;

        //VEHICLE TYPE DUPLICATE CHECK
        if (empty($hidden_vendor_vehicle_type_ID)) :

            $select_vehicle_type_details = sqlQUERY_LABEL("SELECT `vendor_vehicle_type_ID` FROM `dvi_vendor_vehicle_types` WHERE `deleted` = '0'  AND `vehicle_type_id` = '$_vehicle_type' AND `vendor_id` = '$hidden_vendor_ID'  ") or die("#1-UNABLE_TO_COLLECT_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
            if (sqlNUMOFROW_LABEL($select_vehicle_type_details) > 0) :
                $errors['vehicle_type_duplicated'] = true;
            endif;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($hidden_vendor_vehicle_type_ID != '' && $hidden_vendor_vehicle_type_ID != 0) :

                $sqlWhere = " `vendor_id` = '$hidden_vendor_ID' AND `vendor_vehicle_type_ID`= '$hidden_vendor_vehicle_type_ID' ";

                $arrFields = array('`vendor_id`', '`vehicle_type_id`',  '`driver_batta`', '`food_cost`', '`accomodation_cost`', '`extra_cost`', '`driver_early_morning_charges`', '`driver_evening_charges`');

                $arrValues = array("$hidden_vendor_ID", "$_vehicle_type",  "$_driver_bhatta", "$_food_cost", "$_accomdation_cost", "$_extra_cost", "$_driver_early_morning_charges", "$_driver_evening_charges");

                //UPDATE VENDOR DETAILS
                if (sqlACTIONS("UPDATE", "dvi_vendor_vehicle_types", $arrFields, $arrValues, $sqlWhere)) :

                    $response['u_result'] = true;
                    //$response['redirect_URL'] = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $hidden_vendor_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;

            else :

                $arrFields = array('`vendor_id`', '`vehicle_type_id`',  '`driver_batta`', '`food_cost`', '`accomodation_cost`', '`extra_cost`', '`driver_early_morning_charges`', '`driver_evening_charges`', '`createdby`', '`status`');

                $arrValues = array("$hidden_vendor_ID", "$_vehicle_type",  "$_driver_bhatta", "$_food_cost", "$_accomdation_cost", "$_extra_cost", "$_driver_early_morning_charges", "$_driver_evening_charges", "$logged_user_id", "1");

                //INSERT VEHICLE TYPE DETAILS
                if (sqlACTIONS("INSERT", "dvi_vendor_vehicle_types", $arrFields, $arrValues, '')) :
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'vendor_vehicle') :

        $errors = [];
        $response = [];

        $_vehicle_type_id = $_POST['vehicle_type_id'];
        $_registration_number = trim($_POST['registration_number']);
        $_registration_number = str_replace(' ', '', $_registration_number);
        $_registration_date = dateformat_database($_POST['registration_date']);
        $_engine_number = $_POST['engine_number'];
        $_owner_name = $_POST['owner_name'];
        $_owner_contact_no = $_POST['owner_contact_no'];
        $_owner_email_id = $_POST['owner_email_id'];
        $_owner_country = $_POST['owner_country'];
        $_owner_state = $_POST['owner_state'];
        $_owner_city = $_POST['owner_city'];
        $_owner_pincode = $_POST['owner_pincode'];
        $_owner_address = $_POST['owner_address'];
        $_chassis_number = $_POST['chassis_number'];
        $_vehicle_fc_expiry_date = dateformat_database($_POST['vehicle_fc_expiry_date']);
        $_fuel_type = $_POST['fuel_type'];
        $_extra_km_charge = $_POST['extra_km_charge'];
        $_vehicle_location = $_POST['vehicle_orign'];
        $_evening_charges = $_POST['evening_charges'];
        $_vehicle_video_url = $_POST['vehicle_video_url'];
        $_early_morning_charges = $_POST['early_morning_charges'];
        $_vehicle_location_id = getSTOREDLOCATIONDETAILS($_vehicle_location, 'LOCATION_ID');
        $_insurance_policy_number = $_POST['insurance_policy_number'];
        $_insurance_start_date = dateformat_database($_POST['insurance_start_date']);
        $_insurance_end_date = dateformat_database($_POST['insurance_end_date']);
        $_insurance_contact_no = $_POST['insurance_contact_no'];
        $_rto_code = $_POST['rto_code'];
        $_vendor_id = $_POST['vendor_id'];
        $_branch_id = $_POST['branch_id'];
        $_vehicle_id = $_POST['vehicle_id'];

        $firstThreeLetters = substr($_owner_Name, 0, 3);
        $vendor_vehicle = "DVIV-$firstThreeLetters"; // Your prefix value
        $randomNumber = mt_rand(1, 1000000); // Generate a random number between 1 and 100

        if (empty($_vehicle_type_id)) :
            $errors['vehicle_type_id_required'] = true;
        endif;
        if (empty($_registration_number)) :
            $errors['registration_number_required'] = true;
        endif;
        if (empty($_registration_date)) :
            $errors['registration_date_required'] = true;
        endif;
        if (empty($_engine_number)) :
            $errors['engine_number_required'] = true;
        endif;
        if (empty($_owner_name)) :
            $errors['owner_name_required'] = true;
        endif;
        if (empty($_owner_contact_no)) :
            $errors['owner_contact_no_required'] = true;
        endif;
        if (empty($_owner_country)) :
            $errors['owner_country_required'] = true;
        endif;
        if (empty($_owner_state)) :
            $errors['owner_state_required'] = true;
        endif;
        if (empty($_owner_city)) :
            $errors['owner_city_required'] = true;
        endif;
        if (empty($_owner_pincode)) :
            $errors['owner_pincode_required'] = true;
        endif;
        if (empty($_owner_address)) :
            $errors['owner_address_required'] = true;
        endif;
        if (empty($_chassis_number)) :
            $errors['chassis_number_required'] = true;
        endif;
        if (empty($_vehicle_fc_expiry_date)) :
            $errors['vehicle_fc_expiry_date_required'] = true;
        endif;
        if (empty($_fuel_type)) :
            $errors['fuel_type_required'] = true;
        endif;
        if ($_extra_km_charge == "") :
            $errors['extra_km_charge_required'] = true;
        endif;
        if ($_vehicle_location == "") :
            $errors['vehicle_location_required'] = true;
        endif;
        if (empty($_insurance_policy_number)) :
            $errors['insurance_policy_number_required'] = true;
        endif;
        if (empty($_insurance_start_date)) :
            $errors['insurance_start_date_required'] = true;
        endif;
        if (empty($_insurance_end_date)) :
            $errors['insurance_end_date_required'] = true;
        endif;
        if (empty($_insurance_contact_no)) :
            $errors['insurance_contact_no_required'] = true;
        endif;
        if (empty($_rto_code)) :
            $errors['rto_code_required'] = true;
        endif;
        if (empty($_vendor_id)) :
            $errors['vendor_id_required'] = true;
        endif;
        if (empty($_branch_id)) :
            $errors['branch_id_required'] = true;
        endif;
        if ($_early_morning_charges == "") :
            $errors['early_morning_charges_required'] = true;
        endif;
        if ($_evening_charges == "") :
            $errors['evening_charges_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $_vehicle_fc_expiry_date = date('Y-m-d', strtotime($_vehicle_fc_expiry_date));
            $_insurance_end_date = date('Y-m-d', strtotime($_insurance_end_date));
            if (($_vehicle_fc_expiry_date < date("Y-m-d")) || ($_insurance_end_date < date("Y-m-d"))) :
                $status = '0';
            else :
                $status = '1';
            endif;

            $vechicle_arrFields = array('`vendor_id`', '`vendor_branch_id`',  '`vehicle_type_id`', '`registration_number`', '`registration_date`', '`engine_number`', '`owner_name`', '`owner_contact_no`', '`owner_email_id`', '`owner_country`', '`owner_state`', '`owner_city`', '`owner_pincode`', '`owner_address`', '`chassis_number`', '`vehicle_fc_expiry_date`', '`fuel_type`', '`early_morning_charges`', '`evening_charges`', '`vehicle_video_url`', '`extra_km_charge`', '`vehicle_location_id`', '`insurance_policy_number`', '`insurance_start_date`', '`insurance_end_date`', '`insurance_contact_no`', '`RTO_code`',  '`createdby`', '`status`');

            $vechicle_arrValues = array("$_vendor_id", "$_branch_id", "$_vehicle_type_id", "$_registration_number", "$_registration_date", "$_engine_number", "$_owner_name", "$_owner_contact_no", "$_owner_email_id", "$_owner_country", "$_owner_state", "$_owner_city", "$_owner_pincode", "$_owner_address", "$_chassis_number", "$_vehicle_fc_expiry_date", "$_fuel_type", "$_early_morning_charges", "$_evening_charges", "$_vehicle_video_url", "$_extra_km_charge", "$_vehicle_location_id", "$_insurance_policy_number", "$_insurance_start_date", "$_insurance_end_date", "$_insurance_contact_no", "$_rto_code", "$logged_user_id", "$status");

            if ($_vehicle_id != '' && $_vehicle_id != 0) :
                $vehicle_sqlwhere = " `vehicle_id` = '$_vehicle_id' ";
                //UPDATE VEHICLE
                if (sqlACTIONS("UPDATE", "dvi_vehicle", $vechicle_arrFields, $vechicle_arrValues, $vehicle_sqlwhere)) :

                    if (count($_FILES) !== 0) :
                        //VEHICLE GALLERY
                        $vehicle_gallery_count = count($_FILES['vehicle_gallery']['name']);

                        if ($vehicle_gallery_count > 0) :
                            for ($i = 1; $i <= $vehicle_gallery_count; $i++) :
                                if (isset($_FILES['vehicle_gallery']['name'][$i]) && $_FILES['vehicle_gallery']['name'][$i] != '') :
                                    $upload_dir = '../../uploads/vehicle_gallery/';
                                    $file_extension = strtolower(end(explode('.', $_FILES['vehicle_gallery']['name'][$i])));
                                    $file_name = $vendor_vehicle . '-' . rand(0, 99999) . time() . '.' . $file_extension;
                                    $file_type = $_FILES['vehicle_gallery']['type'][$i];
                                    $file_temp_loc  = $_FILES['vehicle_gallery']['tmp_name'][$i];
                                    $file_error_msg = $_FILES['vehicle_gallery']['error'][$i];
                                    $file_size = $_FILES['vehicle_gallery']['size'][$i];
                                    $move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);

                                    $_document_type = $_POST['document_type_id'][$i];

                                    if ($move_file) :
                                        $arrFields_gallery = array('`vehicle_id`', '`image_type`', '`vehicle_gallery_name`', '`createdby`', '`status`');
                                        $arrValues_gallery = array("$_vehicle_id", "$_document_type", "$file_name", "$logged_user_id", "1");
                                        if (sqlACTIONS("INSERT", "dvi_vehicle_gallery_details", $arrFields_gallery, $arrValues_gallery, '')) :
                                        //SUCCESS
                                        endif;
                                    endif;
                                endif;
                            endfor;
                        endif;
                    endif;

                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT VEHICLE DETAILS
                if (sqlACTIONS("INSERT", "dvi_vehicle", $vechicle_arrFields, $vechicle_arrValues, '')) :
                    $vehicle_ID = sqlINSERTID_LABEL();

                    if (count($_FILES) !== 0) :
                        //VEHICLE GALLERY
                        $vehicle_gallery_count = count($_FILES['vehicle_gallery']['name']);

                        if ($vehicle_gallery_count > 0) :
                            for ($i = 1; $i <= $vehicle_gallery_count; $i++) :
                                if (isset($_FILES['vehicle_gallery']['name'][$i]) && $_FILES['vehicle_gallery']['name'][$i] != '') :
                                    $upload_dir = '../../uploads/vehicle_gallery/';
                                    $file_extension = strtolower(end(explode('.', $_FILES['vehicle_gallery']['name'][$i])));
                                    $file_name = $vendor_vehicle . '-' . rand(0, 99999) . time() . '.' . $file_extension;
                                    $file_type = $_FILES['vehicle_gallery']['type'][$i];
                                    $file_temp_loc  = $_FILES['vehicle_gallery']['tmp_name'][$i];
                                    $file_error_msg = $_FILES['vehicle_gallery']['error'][$i];
                                    $file_size = $_FILES['vehicle_gallery']['size'][$i];
                                    $move_file = move_uploaded_file($file_temp_loc, $upload_dir . $file_name);

                                    $_document_type = $_POST['document_type_id'][$i];

                                    if ($move_file) :
                                        $arrFields_gallery = array('`vehicle_id`', '`image_type`', '`vehicle_gallery_name`', '`createdby`', '`status`');
                                        $arrValues_gallery = array("$vehicle_ID", "$_document_type", "$file_name", "$logged_user_id", "1");
                                        if (sqlACTIONS("INSERT", "dvi_vehicle_gallery_details", $arrFields_gallery, $arrValues_gallery, '')) :
                                        //SUCCESS
                                        endif;
                                    endif;
                                endif;
                            endfor;
                        endif;
                    endif;

                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete') :
        $ID = $_GET['ID'];

        // $select_vendor_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`kms_limit_id`) AS TOTAL_USED_COUNT FROM `dvi_kms_limit` WHERE `status` = '1' and `vendor_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // while ($fetch_vendor_used_data = sqlFETCHARRAY_LABEL($select_vendor_id_already_used)) :
        //     $TOTAL_USED_COUNT_KM_LIMIT = $fetch_vendor_used_data['TOTAL_USED_COUNT'];
        // endwhile;

        // $select_vendor_id_already_used_time = sqlQUERY_LABEL("SELECT COUNT(`time_limit_id`) AS TOTAL_USED_COUNT FROM `dvi_time_limit` WHERE `status` = '1' and `vendor_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // while ($fetch_vendor_used_data1 = sqlFETCHARRAY_LABEL($select_vendor_id_already_used_time)) :
        //     $TOTAL_USED_COUNT_TIME_LIMIT = $fetch_vendor_used_data1['TOTAL_USED_COUNT'];
        // endwhile;

        // $select_vendor_id_already_used_branch = sqlQUERY_LABEL("SELECT COUNT(`vendor_branch_id`) AS TOTAL_USED_COUNT FROM `dvi_vendor_branches` WHERE `status` = '1' and `vendor_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_id_already_used_branch)) :
        //     $TOTAL_USED_COUNT_BRANCH = $fetch_vendor_branch_data['TOTAL_USED_COUNT'];
        // endwhile;

        // $select_vendor_id_already_used_vehicle = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_USED_COUNT FROM `dvi_vehicle` WHERE `status` = '1' and `vendor_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // while ($fetch_vendor_vehicle_data = sqlFETCHARRAY_LABEL($select_vendor_id_already_used_vehicle)) :
        //     $TOTAL_USED_COUNT_VEHICLE = $fetch_vendor_vehicle_data['TOTAL_USED_COUNT'];
        // endwhile;
?>
        <div class="modal-body">
            <div class="row">
                <?php if ($TOTAL_USED_COUNT_KM_LIMIT == 0 && $TOTAL_USED_COUNT_TIME_LIMIT == 0 && $TOTAL_USED_COUNT_BRANCH == 0 && $TOTAL_USED_COUNT_VEHICLE == 0) :
                ?>
                    <div class="text-center">
                        <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                            <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                    <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.<br>This
                        process includes deletion of Branch,Permits<br>drivers,Users and price against the Vendor.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" onclick="confirmVENDORDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                    </div>
                <?php else :
                ?>
                    <!-- <div class="text-center">
            <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round"></path>
                <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round"></path>
            </svg>
        </div>
        <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this Vendor.</h6>
        <p class="text-center"> Since this vendor is assigned with either Branches or Vehicle or Local and Outstation KM
            and Time limit.</p>
        <div class="text-center pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div> -->
                <?php endif;
                ?>
            </div>
        </div>

<?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];
        //SANITIZE
        // $_ID = $validation_globalclass->sanitize($_ID);

        $delete_vendor = sqlQUERY_LABEL("UPDATE `dvi_vendor_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        if ($delete_vendor) :

            $delete_vendor_branch = sqlQUERY_LABEL("UPDATE `dvi_vendor_branches` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_BRANCHES:" . sqlERROR_LABEL());

            $select_vehicle_gallery = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_vehicle` WHERE `vendor_id` = '$_ID'  AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery)) :
                $vehicle_id = $fetch_vehicle_gallery_data['vehicle_id'];

                $delete_vendor_branch_vehicle_gallery = sqlQUERY_LABEL("UPDATE `dvi_vehicle_gallery_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

            endwhile;

            $delete_vendor_vehicle = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle` WHERE `vendor_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_vendor_vehicle_types = sqlQUERY_LABEL("DELETE FROM `dvi_vendor_vehicle_types` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_vehicle_outstation_price_book = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_outstation_price_book` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_vehicle_local_pricebook = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_local_pricebook` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_time_limit = sqlQUERY_LABEL("DELETE FROM `dvi_time_limit` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_kms_limit = sqlQUERY_LABEL("DELETE FROM `dvi_kms_limit` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_permit_cost = sqlQUERY_LABEL("DELETE FROM `dvi_permit_cost` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_USER = sqlQUERY_LABEL("DELETE FROM `dvi_users` WHERE `vendor_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_USER:" . sqlERROR_LABEL());

            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result'] = false;
        endif;


        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $vendor_ID = $_POST['VENDOR_ID'];
        $oldstatus = $_POST['oldstatus'];

        if ($oldstatus == '1') :
            $status = '0';
        elseif ($oldstatus == '0') :
            $status = '1';
        endif;

        //Update query
        $arrFields = array('`status`');

        $arrValues = array("$status");

        $sqlWhere = " `vendor_id` = '$vendor_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_vendor_details", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :

            $arrFields_user = array('`userbanned`');

            if ($status == 1) :
                $user_banned = 0;
            elseif ($status == 0) :
                $user_banned = 1;
            endif;

            $arrValues = array("$user_banned");

            $sqlWhere = " `vendor_id` = '$vendor_ID' ";
            $update_user_status = sqlACTIONS("UPDATE", "dvi_users", $arrFields_user, $arrValues, $sqlWhere);

            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'vehicleupdatestatus') :

        $errors = [];
        $response = [];

        $vehicle_ID = $_POST['VEHICLE_ID'];
        $oldstatus = $_POST['STATUS_ID'];

        if ($oldstatus == '1') :
            $status = '0';
        elseif ($oldstatus == '0') :
            $select_VEHICLELIST_query = sqlQUERY_LABEL("SELECT `vehicle_fc_expiry_date`, `insurance_end_date` FROM `dvi_vehicle` WHERE `deleted` = '0' and `vehicle_id`='$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_VEHICLELIST_query)) :
                $vehicle_fc_expiry_date = date('Y-m-d', strtotime($fetch_list_data['vehicle_fc_expiry_date']));
                $insurance_end_date = date('Y-m-d', strtotime($fetch_list_data['insurance_end_date']));
            endwhile;

            $response['result_vehicle_fc_expiry'] = false;
            $response['result_insurance_end_date'] = false;
            if (($vehicle_fc_expiry_date < date("Y-m-d"))) :
                $status = '0';
                $response['result_vehicle_fc_expiry'] = true;
            elseif (($insurance_end_date < date("Y-m-d"))) :
                $status = '0';
                $response['result_insurance_end_date'] = true;
            else :
                $status = '1';
            endif;
        endif;

        //Update query
        $arrFields = array('`status`');

        $arrValues = array("$status");

        $sqlWhere = " `vehicle_id` = '$vehicle_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_vehicle", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);



    elseif ($_GET['type'] == 'confirm_branch_delete') :

        $errors = [];
        $response = [];

        $vendor_branch_ID = $_POST['vendor_branch_ID'];
        $vendor_ID = $_POST['vendor_ID'];
        $ROUTE = $_POST['ROUTE'];


        //SANITIZE
        // $_ID = $validation_globalclass->sanitize($_ID);

        $delete_vendor_branch = sqlQUERY_LABEL("UPDATE `dvi_vendor_branches` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vendor_id` = '$vendor_ID' and `vendor_branch_id`='$vendor_branch_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_id` FROM `dvi_vehicle` WHERE `vendor_id` = '$vendor_ID' and `vendor_branch_id`='$vendor_branch_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
        while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
            $vehicle_id = $fetch_vehicle_gallery_data['vehicle_id'];

            $delete_vendor_branch_vehicle_gallery = sqlQUERY_LABEL("UPDATE `dvi_vehicle_gallery_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        endwhile;

        $delete_vendor_branch_vehicle = sqlQUERY_LABEL("UPDATE `dvi_vehicle` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `vendor_id` = '$vendor_ID' and `vendor_branch_id`='$vendor_branch_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        $delete_vehicle_outstation_price_book = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_outstation_price_book` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

        $delete_vehicle_local_pricebook = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_local_pricebook` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());


        if ($delete_vendor_branch) :
            $response['success'] = true;
            $response['result_success'] = true;
            $response['redirect_URL'] = 'newvendor.php?route=' . $ROUTE . '&formtype=branch_info&id=' . $vendor_ID;
        else :
            $response['result_success'] = false;
        endif;



        echo json_encode($response);

    elseif ($_GET['type'] == 'confirm_vehicle_gallery_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        $vehicle_gallery_sqlwhere = " `vehicle_gallery_details_id` = '$_ID'";
        $upload_dir = '../../uploads/vehicle_gallery/';

        $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `vehicle_gallery_details_id`= '$_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
        $fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch);
        $vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];

        if (sqlACTIONS("DELETE", "dvi_vehicle_gallery_details", '', '', $vehicle_gallery_sqlwhere)) :
            unlink($upload_dir . $vehicle_gallery_name);
            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'confirm_vehicle_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        $arrFields = array('`deleted`');
        $arrValues = array("1");
        $sqlwhere = " `vehicle_id` = '$_ID'";

        if (sqlACTIONS("UPDATE", "dvi_vehicle", $arrFields, $arrValues, $sqlwhere)) :

            $select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `vehicle_gallery_details_id`= '$_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());


            $delete_vehicle_outstation_price_book = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_outstation_price_book` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());

            $delete_vehicle_local_pricebook = sqlQUERY_LABEL("DELETE FROM `dvi_vehicle_local_pricebook` WHERE `vendor_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_VEHICLE:" . sqlERROR_LABEL());


            $total_gallery_count = sqlNUMOFROW_LABEL($select_vehicle_gallery_branch);
            if ($total_gallery_count > 0) :
                while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
                    $upload_dir = '../../uploads/vehicle_gallery/';
                    $vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];
                    unlink($upload_dir . $vehicle_gallery_name);
                endwhile;

                $vehicle_gallery_sqlwhere = " `vehicle_id` = '$_ID'";
                if (sqlACTIONS("DELETE", "dvi_vehicle_gallery_details", '', '', $vehicle_gallery_sqlwhere)) :
                    $response['success'] = true;
                    $response['result_success'] = true;
                else :
                    $response['result_success'] = false;
                endif;

            else :
                $response['success'] = true;
                $response['result_success'] = true;
            endif;

        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_permit_cost') :

        $errors = [];
        $response = [];

        $hidden_source_state_id = trim($_POST['selected_state']);
        $hidden_vehicle_type_id = trim($_POST['vehicle_type']);
        $hidden_vendor_id = trim($_POST['hidden_vendor_ID']);

        $permit_cost = $_POST['permit_cost'];
        $hidden_destination_state_id = $_POST['hidden_destination_state_id'];
        $hidden_permit_cost_ID = $_POST['hidden_permit_cost_ID'];

        if (empty($hidden_vehicle_type_id)) :
            $errors['vehicle_type_id_required'] = true;
        endif;
        if (empty($hidden_source_state_id)) :
            $errors['selectedState_required'] = true;
        endif;
        if (empty($hidden_vendor_id)) :
            $errors['hidden_permit_vendor_ID_required'] = true;
        endif;

        $check_permit_cost_availability_query = sqlQUERY_LABEL("SELECT `permit_cost_id` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$hidden_vendor_id' AND `vehicle_type_id` = '$hidden_vehicle_type_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        $check_permit_cost_availability_num_rows_count = sqlNUMOFROW_LABEL($check_permit_cost_availability_query);

        if ($check_permit_cost_availability_num_rows_count > 0 && empty($hidden_permit_cost_ID)):
            $errors['vehicle_type_permit_charges_already_exist'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            for ($j = 0; $j < count($permit_cost); $j++) :

                $permit_cost_ID = $hidden_permit_cost_ID[$j];
                $destination_state_id = $hidden_destination_state_id[$j];
                $vehicle_permit_cost = $permit_cost[$j];

                if ($permit_cost_ID != '' && $permit_cost_ID != 0) :

                    $arrFields_src_des = array('`permit_cost`');
                    $arrValues_src_des = array("$vehicle_permit_cost");
                    $sqlWhere_src_des = " `permit_cost_id` = '$permit_cost_ID' ";

                    //UPDATE PERMIT COST DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_permit_cost", $arrFields_src_des, $arrValues_src_des, $sqlWhere_src_des)) :

                        $response['u_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;

                else :

                    //INSERT PERMIT COST DETAILS
                    $arrFields_src_des = array('`vehicle_type_id`', '`vendor_id`',  '`source_state_id`', '`destination_state_id`', '`permit_cost`', '`createdby`', '`status`');

                    $arrValues_src_des = array("$hidden_vehicle_type_id", "$hidden_vendor_id",  "$hidden_source_state_id", "$destination_state_id", "$vehicle_permit_cost",  "$logged_user_id", "1");

                    if (sqlACTIONS("INSERT", "dvi_permit_cost", $arrFields_src_des, $arrValues_src_des, '')) :
                        $response['i_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;

                endif;

            endfor;
        endif;

        echo json_encode($response);
    endif;
endif;
