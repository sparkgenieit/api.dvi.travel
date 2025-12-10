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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'driver_basic_info') :

        $errors = [];
        $response = [];

        $_driver_name = trim($_POST['driver_name']);
        $_vendor_id = trim($_POST['vendor_id']);
        $_vendor_vehicle_id = trim($_POST['vendor_vehicle_id']);
        $_driver_code = trim($_POST['driver_code']);
        $_driver_primary_mobile_number = trim($_POST['driver_primary_mobile_number']);
        $_driver_alternate_mobile_number = $_POST['driver_alternate_mobile_number'];
        $_driver_whatsapp_mobile_number = trim($_POST['driver_whatsapp_mobile_number']);
        $_driver_email = $_POST['driver_email'];
        $_driver_aadharcard_num = trim($_POST['driver_aadharcard_num']);
        $_driver_voter_id_num = $_POST['driver_voter_id_num'];
        $_driver_pan_card = trim($_POST['driver_pan_card']);
        $_driver_license_issue_date = trim(
            dateformat_database($_POST['driver_license_issue_date'])
        );
        $_driver_license_expiry_date = trim(
            dateformat_database($_POST['driver_license_expiry_date'])
        );
        $_driver_license_number = trim($_POST['driver_license_number']);
        $_driver_blood_group = trim($_POST['driver_blood_group']);
        $_driver_gender = trim($_POST['driver_gender']);
        $_driver_date_of_birth = trim(dateformat_database($_POST['driver_date_of_birth']));
        // $_driver_profile_image = trim($_POST['driver_profile_image']);
        $old_file_name = $_POST['old_file_name']; // Retrieve the existing file name
        $new_file_name = trim($_FILES['file']['name']); // Retrieve the new file name
        $_driver_address = trim($_POST['driver_address']);
        $hidden_DRIVER_ID = $_POST['hidden_driver_ID'];

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call
            $response['success'] = true;
            $uploadDir = '../../uploads/driver_gallery/';
            if (!empty($new_file_name)) {
                $tmpFile = $_FILES['file']['tmp_name'];
                $fileExtension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                $fileName = rand(0, 99999) . '-' . time() . '.' . $fileExtension;

                $filename = $uploadDir . '/' . $fileName;
                if (move_uploaded_file($tmpFile, $filename)) {
                    $_driver_profile_image = $fileName;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                $_driver_profile_image = $old_file_name;
            }

            $_driver_name = ucwords($_driver_name);

            if (($_driver_license_expiry_date < date("Y-m-d"))) :
                $status = '0';
            else :
                $status = '1';
            endif;

            if ($hidden_DRIVER_ID != '' && $hidden_DRIVER_ID != 0) :
                $arrFields = array('`driver_name`', '`vendor_id`', '`vehicle_type_id`', '`driver_primary_mobile_number`', '`driver_alternate_mobile_number`', '`driver_whatsapp_mobile_number`', '`driver_email`', '`driver_aadharcard_num`', '`driver_voter_id_num`', '`driver_pan_card`', '`driver_license_issue_date`', '`driver_license_expiry_date`', '`driver_license_number`', '`driver_blood_group`', '`driver_gender`', '`driver_date_of_birth`', '`driver_profile_image`', '`driver_address`', '`createdby`', '`status`');

                $arrValues = array("$_driver_name", "$_vendor_id", "$_vendor_vehicle_id", "$_driver_primary_mobile_number", "$_driver_alternate_mobile_number", "$_driver_whatsapp_mobile_number", "$_driver_email", "$_driver_aadharcard_num", "$_driver_voter_id_num", "$_driver_pan_card", "$_driver_license_issue_date", "$_driver_license_expiry_date", "$_driver_license_number", "$_driver_blood_group", "$_driver_gender", "$_driver_date_of_birth", "$_driver_profile_image", "$_driver_address", "$logged_user_id", "$status");

                $sqlWhere = " `driver_id` = '$hidden_DRIVER_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_driver_details", $arrFields, $arrValues, $sqlWhere)) :

                    $license_renewal_log_list_datas = sqlQUERY_LABEL("SELECT `license_number`,`start_date`,`end_date` FROM `dvi_driver_license_renewal_log_details` WHERE `license_number` = '$_driver_license_number' and `start_date` = '$_driver_license_issue_date' and `end_date` = '$_driver_license_expiry_date' and `status` = '1' and `deleted` = '0'") or die("UNABLE_TO_CHECKING_DRIVER_DETAILS:" . sqlERROR_LABEL());

                    $license_renewal_log_total_row = sqlNUMOFROW_LABEL($license_renewal_log_list_datas);

                    if ($license_renewal_log_total_row == 0) :

                        $driver_license_renewal_log_arrFields = array('`vendor_id`', '`driver_id`', '`license_number`', '`start_date`', '`end_date`', '`createdby`', '`status`');

                        $driver_license_renewal_log_arrValues = array("$_vendor_id", "$hidden_DRIVER_ID", "$_driver_license_number", "$_driver_license_issue_date", "$_driver_license_expiry_date", "$logged_user_id", "1");

                        sqlACTIONS("INSERT", "dvi_driver_license_renewal_log_details", $driver_license_renewal_log_arrFields, $driver_license_renewal_log_arrValues, '');

                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'driver.php?route=edit&formtype=driver_cost&id=' . $hidden_DRIVER_ID;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'driver.php?route=edit&formtype=driver_cost&id=' . $hidden_DRIVER_ID;
                        $response['result_success'] = true;
                    endif;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :

                $_driver_code = get_DRIVER_CODE('driver_code', $hidden_DRIVER_ID);

                $arrFields = array('`driver_name`', '`driver_code`', '`vendor_id`', '`vehicle_type_id`', '`driver_primary_mobile_number`', '`driver_alternate_mobile_number`', '`driver_whatsapp_mobile_number`', '`driver_email`', '`driver_aadharcard_num`', '`driver_voter_id_num`', '`driver_pan_card`', '`driver_license_issue_date`', '`driver_license_expiry_date`', '`driver_license_number`', '`driver_blood_group`', '`driver_gender`', '`driver_date_of_birth`', '`driver_profile_image`', '`driver_address`', '`createdby`', '`status`');

                $arrValues = array("$_driver_name", "$_driver_code", "$_vendor_id", "$_vendor_vehicle_id", "$_driver_primary_mobile_number", "$_driver_alternate_mobile_number", "$_driver_whatsapp_mobile_number", "$_driver_email", "$_driver_aadharcard_num", "$_driver_voter_id_num", "$_driver_pan_card", "$_driver_license_issue_date", "$_driver_license_expiry_date", "$_driver_license_number", "$_driver_blood_group", "$_driver_gender", "$_driver_date_of_birth", "$_driver_profile_image", "$_driver_address", "$logged_user_id", "$status");

                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_driver_details", $arrFields, $arrValues, '')) :
                    $driver_id = sqlINSERTID_LABEL();

                    $driver_license_renewal_log_arrFields = array('`vendor_id`', '`driver_id`', '`license_number`', '`start_date`', '`end_date`', '`createdby`', '`status`');

                    $driver_license_renewal_log_arrValues = array("$_vendor_id", "$driver_id", "$_driver_license_number", "$_driver_license_issue_date", "$_driver_license_expiry_date", "$logged_user_id", "1");

                    sqlACTIONS("INSERT", "dvi_driver_license_renewal_log_details", $driver_license_renewal_log_arrFields, $driver_license_renewal_log_arrValues, '');
                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'driver.php?route=add&formtype=driver_cost&id=' . $driver_id;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'driver_cost') :

        $errors = [];
        $response = [];

        $hidden_DRIVER_ID = trim($_POST['hidden_driver_ID']);
        $_driver_salary = trim($_POST['driver_salary']);
        $_driver_early_morning_charges = trim($_POST['driver_early_morning_charges']);
        $_driver_evening_charges = trim($_POST['driver_evening_charges']);

        $_driver_food_cost = trim($_POST['driver_food_cost']);
        $_driver_accomdation_cost = trim($_POST['driver_accomdation_cost']);
        $_driver_bhatta_cost = trim($_POST['driver_bhatta_cost']);
        $_driver_gst_type = trim($_POST['driver_gst_type']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $select_driver_cost_list = sqlQUERY_LABEL("SELECT `driver_id` FROM `dvi_driver_costdetails` WHERE `deleted` = '0' AND `driver_id` = '$hidden_DRIVER_ID'") or die("#1-UNABLE_TO_COLLECT_DRIVER_DETAILS:" . sqlERROR_LABEL());
            $num_row_driver_cost = sqlNUMOFROW_LABEL($select_driver_cost_list);

            if (($hidden_DRIVER_ID != '' && $hidden_DRIVER_ID != 0) && ($num_row_driver_cost != 0)) :

                $arrFields = array('`driver_salary`', '`driver_food_cost`', '`driver_accomdation_cost`', '`driver_bhatta_cost`', '`driver_gst_type`', '`driver_early_morning_charges`', '`driver_evening_charges`', '`createdby`');

                $arrValues = array("$_driver_salary", "$_driver_food_cost", "$_driver_accomdation_cost", "$_driver_bhatta_cost", "$_driver_gst_type", "$_driver_early_morning_charges", "$_driver_evening_charges", "$logged_user_id");

                $sqlWhere = " `driver_id` = '$hidden_DRIVER_ID' ";

                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_driver_costdetails", $arrFields, $arrValues, $sqlWhere)) :
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'driver.php?route=edit&formtype=driver_upload_documents&id=' . $hidden_DRIVER_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            elseif ($num_row_driver_cost == 0) :

                $arrFields = array('`driver_id`', '`driver_salary`', '`driver_food_cost`', '`driver_accomdation_cost`', '`driver_bhatta_cost`', '`driver_gst_type`', '`driver_early_morning_charges`', '`driver_evening_charges`', '`createdby`', '`status`');

                $arrValues = array("$hidden_DRIVER_ID", "$_driver_salary", "$_driver_food_cost", "$_driver_accomdation_cost", "$_driver_bhatta_cost", "$_driver_gst_type", "$_driver_early_morning_charges", "$_driver_evening_charges", "$logged_user_id", "1");

                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_driver_costdetails", $arrFields, $arrValues, '')) :
                    $driver_id = sqlINSERTID_LABEL();
                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'driver.php?route=add&formtype=driver_upload_documents&id=' . $driver_id;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'driver_upload_document') :

        $errors = [];
        $response = [];
        $hidden_DRIVER_ID = $_POST['hidden_driver_ID'];
        $_document_type = trim($_POST['document_type']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($hidden_DRIVER_ID != '') :
                $select_list = sqlQUERY_LABEL("SELECT `driver_code`, `driver_name` FROM `dvi_driver_details` WHERE `deleted` = '0' AND `driver_id` = '$hidden_DRIVER_ID'") or die("#1-UNABLE_TO_COLLECT_DRIVER_DETAILS:" . sqlERROR_LABEL());

                while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                    $counter++;
                    $driver_code = $fetch_data['driver_code'];
                    $driver_name = $fetch_data['driver_name'];
                endwhile;
            endif;

            // if (!file_exists('../../uploads/driver_gallery/' . $driver_name . $driver_code)) {
            //     mkdir('../../uploads/driver_gallery/' . $driver_name . $driver_code, 0777, true);
            // }

            // $uploadDir = '../../uploads/driver_gallery/' . $driver_name . $driver_code;
            // if (!empty($_FILES)) :
            //     $tmpFile = $_FILES['file']['tmp_name'];
            //     // $File_Ext = substr($_FILES['file']['name'], strrpos($_FILES['file']['name'],'.'));
            //     $fileName = rand(0, 99999) . time() . '-' . trim($_FILES['file']['name']);
            //     $filename = $uploadDir . '/' . $fileName;
            //     if (move_uploaded_file($tmpFile, $filename)) :
            //         $_driver_document_image = $fileName;
            //     else :
            //         $_driver_document_image = '';
            //     endif;
            // endif;
            $uploadDir = '../../uploads/driver_gallery/';
            if (!empty($_FILES)) :
                $tmpFile = $_FILES['file']['tmp_name'];
                // $File_Ext = substr($_FILES['file']['name'], strrpos($_FILES['file']['name'],'.'));
                $fileName = rand(0, 99999) . time() . '-' . trim($_FILES['file']['name']);
                $filename = $uploadDir . '/' . $fileName;
                if (move_uploaded_file($tmpFile, $filename)) :
                    $_driver_document_image = $fileName;
                else :
                    $_driver_document_image = '';
                endif;
            endif;
            $select_driver_document_list = sqlQUERY_LABEL("SELECT `driver_id` FROM `dvi_driver_document_details` WHERE `deleted` = '0' AND `driver_id` = '$hidden_DRIVER_ID' AND  `document_type` = '$_document_type'") or die("#1-UNABLE_TO_COLLECT_DRIVER_DETAILS:" . sqlERROR_LABEL());
            $num_row_driver_document = sqlNUMOFROW_LABEL($select_driver_document_list);

            if (($hidden_DRIVER_ID != '' && $hidden_DRIVER_ID != 0) && ($num_row_driver_document != 0)) :

                $arrFields = array('`driver_document_name`');
                $arrValues = array("$_driver_document_image");
                $sqlWhere = " `driver_id` = '$hidden_DRIVER_ID' AND  `document_type` = '$_document_type' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_driver_document_details", $arrFields, $arrValues, $sqlWhere)) :
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'driver.php?route=add&formtype=driver_cost&id=' . $driver_id;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            elseif ($num_row_driver_document == 0) :
                $arrFields = array('`driver_id`', '`document_type`', '`driver_document_name`', '`createdby`', '`status`');

                $arrValues = array("$hidden_DRIVER_ID", "$_document_type", "$_driver_document_image", "$logged_user_id", "1");
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_driver_document_details", $arrFields, $arrValues, '')) :
                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'driver.php?route=edit&formtype=driver_cost&id=' . $driver_id;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $DRIVER_ID = $_POST['DRIVER_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $DRIVER_ID = $validation_globalclass->sanitize($DRIVER_ID);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $select_DRIVERLIST_query = sqlQUERY_LABEL("SELECT `driver_license_expiry_date` FROM `dvi_driver_details` WHERE `deleted` = '0' and `driver_id`='$DRIVER_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_DRIVERLIST_query)) :
                $driver_license_expiry_date = date('Y-m-d', strtotime($fetch_list_data['driver_license_expiry_date']));
            endwhile;

            $response['result_driver_license_expiry_date'] = false;
            if (($driver_license_expiry_date < date("Y-m-d"))) :
                $new_status = '0';
                $response['result_driver_license_expiry_date'] = true;
            else :
                $new_status = '1';
            endif;
        else :
            $new_status = '0';
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = " `driver_id` = '$DRIVER_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_driver_details", $arrFields, $arrValues, $sqlwhere)) :
            //CATEGORY STATUS CHANGED TO ACTIVE / INACTIVE MODE
            $response['result'] = true;
        else :
            $response['success'] = false;
            $response['response_error'] = true;
        endif;

        echo json_encode($response);

    //DELETE OPERATION - CONDITION BASED

    elseif ($_GET['type'] == 'driver_review') :

        $errors = [];
        $response = [];

        $_driver_rating = $_POST['driver_rating'];
        $_review_description = $_POST['review_description'];
        $hidden_driver_ID = $_POST['hiddenDRIVER_ID'];
        $hidden_driver_review_ID = $_POST['hiddenDRIVER_REVIEW_ID'];

        // if (empty($_driver_rating)) :
        //     $errors['driver_rating_required'] = true;
        // endif;
        // if (empty($_review_description)) :
        //     $errors['review_description_required'] = true;
        // endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`driver_id`', '`driver_rating`', '`driver_description`', '`createdby`', '`status`');
            $arrValues = array("$hidden_driver_ID", "$_driver_rating", "$_review_description", "$logged_user_id", "1");

            if ($hidden_driver_review_ID != '' && $hidden_driver_review_ID != 0) :

                $sqlWhere = " `driver_review_id` = '$hidden_driver_review_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_driver_review_details", $arrFields, $arrValues, $sqlWhere)) :
                    $response['driver_id'] = $hidden_driver_ID;
                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_driver_review_details", $arrFields, $arrValues, '')) :
                    $response['driver_id'] = $hidden_driver_ID;
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'deleted_review') :

        $errors = [];
        $response = [];

        $DRIVER_ID = $_POST['ID'];
        $REVIEW_ID = $_POST['REVIEW_ID'];

        //SANITIZE
        $DRIVER_ID = $validation_globalclass->sanitize($DRIVER_ID);
        $REVIEW_ID = $validation_globalclass->sanitize($REVIEW_ID);

        $arrFields = array('`deleted`');
        $arrValues = array("1");
        $sqlwhere = " `driver_id` = '$DRIVER_ID' AND  `driver_review_id` = '$REVIEW_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_driver_review_details", $arrFields, $arrValues, $sqlwhere)) :
            //DELETED CHANGED to 1
            $response['result'] = true;
        else :
            $response['success'] = false;
            $response['response_error'] = true;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'driver_review_delete') :

        $ID = $_GET['ID'];
        $REVIEW = $_GET['REVIEW'];

        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);
        $REVIEW = $validation_globalclass->sanitize($REVIEW);
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
                    <button type="submit" onclick="confirmDRIVERREVIEWDELETE('<?= $ID; ?>', '<?= $REVIEW; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'delete') :
        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);

        // Query to check if the driver is used
        $select_driver_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`driver_id`) AS TOTAL_USED_COUNT FROM `dvi_driver_details`
WHERE `driver_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        while ($fetch_driver_used_data = sqlFETCHARRAY_LABEL($select_driver_id_already_used)) :
            $TOTAL_USED_COUNT = $fetch_driver_used_data['TOTAL_USED_COUNT'];
        endwhile;
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
                    <button type="submit" onclick="confirmDRIVERDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_DRIVER = sqlQUERY_LABEL("UPDATE `dvi_driver_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `driver_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_DRIVER:" . sqlERROR_LABEL());
        if ($delete_DRIVER) :
            $response['success'] = true;
            $response['response_result'] = 'Driver deleted successfully.';
        else :
            $response['success'] = false;
            $response['result_error'] = 'Failed to delete the driver.';
        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
?>