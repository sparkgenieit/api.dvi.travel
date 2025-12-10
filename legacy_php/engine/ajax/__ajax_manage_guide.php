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

    if ($_GET['type'] == 'guide_basic_info') :

        $errors = [];
        $response = [];

        $_guide_name = trim($_POST['guide_name']);
        $_guide_dob = trim(dateformat_database($_POST['guide_dob']));
        $_blood_group = trim($_POST['blood_group']);
        $_guide_gender = trim($_POST['guide_gender']);
        $_guide_primary_mobile_number = trim($_POST['guide_primary_mobile_number']);
        $_guide_alternative_mobile_number = trim($_POST['guide_alternative_mobile_no']);
        $_guide_email = trim($_POST['guide_email_id']);
        $_guide_emergency_mobile_number = trim($_POST['guide_emergency_mobile_number']);
        $_guide_language_proficiency = trim($_POST['language_proficiency']);
        $_guide_aadhar_number = trim($_POST['guide_aadhar_no']);
        $_guide_experience = trim($_POST['guide_experience']);
        $_guide_country = trim($_POST['guide_country']);
        $_guide_state = trim($_POST['guide_state']);
        $_guide_city = trim($_POST['guide_city']);
        $_guide_gst = trim($_POST['guide_gst_percentage']);
        $gst_status = trim($_POST['gst_status']);

        $_guide_bank_name = trim($_POST['guide_bank_name']);
        $_guide_bank_branch_name = trim($_POST['guide_branch_name']);
        $_guide_ifsc_code = trim($_POST['guide_IFSC_code']);
        $_guide_account_number = trim($_POST['guide_account_no']);
        $_guide_confirm_account_no = trim($_POST['guide_confirm_account_no']);
        $_guide_select_role = trim($_POST['guide_select_role']);
        $_guide_password = trim($_POST['guide_password']);
        $hidden_guide_ID = trim($_POST['hidden_guide_ID']);

        //  $hidden_guide_ID = $_POST['hidden_guide_ID'];

        $guide_slot = $_POST['guide_slot'];
        $_guide_slot = implode(',', $guide_slot);
        //guide_confirm_account_no

        $_hotspotCheckbox = trim($_POST['hotspotCheckbox']);
        $_activityCheckbox = trim($_POST['activityCheckbox']);
        $_itineraryCheckbox = trim($_POST['itineraryCheckbox']);

        if ($_hotspotCheckbox == 1) :
            $_guide_preffered_for = 1;
            $hotspotSelect = $_POST['hotspotSelect'];
            $_applicable_hotspot_places = implode(',', $hotspotSelect);
        elseif ($_activityCheckbox == 1) :
            $_guide_preffered_for = 2;
            $activitySelect  = $_POST['activitySelect'];
            $_applicable_activity_places = implode(',', $activitySelect);
        elseif ($_itineraryCheckbox == 1) :
            $_guide_preffered_for = 3;
        endif;

        // $_guide_language_proficiency = implode(',', $_guide_language_proficiency);

        if (empty($_guide_name)) :
            $errors['guide_name_required'] = true;
        endif;
        if (empty($_guide_gender)) :
            $errors['guide_gender_required'] = true;
        endif;
        if (empty($_guide_primary_mobile_number)) :
            $errors['guide_primary_mobile_no_required'] = true;
        endif;
        if (empty($_guide_email)) :
            $errors['guide_email_id_required'] = true;
        endif;
        if (empty($_guide_select_role)) :
            $errors['guide_select_role_required'] = true;
        endif;
        if (empty($_guide_language_proficiency)) :
            $errors['guide_language_proficiency_required'] = true;
        endif;

        if (empty($_guide_gst)) :
            $errors['guide_gst_required'] = true;
        endif;
        if (empty($_guide_slot)) :
            $errors['guide_slot_required'] = true;
        endif;

        if ($hidden_guide_ID == '') :
            if (empty($_guide_select_role)) :
                $errors['guide_select_role_required'] = true;
            endif;
            if (empty($_guide_password)) :
                $errors['guide_password_required'] = true;
            endif;
        endif;

        if ($_guide_emergency_mobile_number == $_guide_primary_mobile_number) :
            $errors['guide_emergency_mobile_number_same'] = "Emeregency mobile number and primary mobile number should not be same";
        endif;

        //check guide_confirm_account_no and guide_account_no are same if it is not same then store error on guide_account_no_not_same
        if ($_guide_confirm_account_no != $_guide_account_number) :
            $errors['guide_account_no_not_same'] = "Account number and confirm account number should be same";
        endif;


        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`guide_name`', '`guide_dob`', '`guide_bloodgroup`', '`guide_gender`', '`guide_primary_mobile_number`', '`guide_alternative_mobile_number`', '`guide_email`', '`guide_emergency_mobile_number`', '`guide_language_proficiency`', '`guide_aadhar_number`', '`guide_experience`', '`guide_country`', '`guide_state`', '`guide_city`', '`guide_gst`', '`gst_type`', '`guide_available_slot`', '`guide_bank_name`', '`guide_bank_branch_name`', '`guide_ifsc_code`', '`guide_account_number`', '`guide_preffered_for`', '`applicable_hotspot_places`', '`applicable_activity_places`', '`createdby`', '`status`');

            $arrValues = array("$_guide_name", "$_guide_dob", "$_blood_group", "$_guide_gender", "$_guide_primary_mobile_number", "$_guide_alternative_mobile_number", "$_guide_email", "$_guide_emergency_mobile_number", "$_guide_language_proficiency", "$_guide_aadhar_number", "$_guide_experience", "$_guide_country", "$_guide_state", "$_guide_city", "$_guide_gst", "$gst_status", "$_guide_slot", "$_guide_bank_name", "$_guide_bank_branch_name", "$_guide_ifsc_code", "$_guide_account_number", "$_guide_preffered_for", "$_applicable_hotspot_places", "$_applicable_activity_places", "$logged_user_id", 1);

            if ($hidden_guide_ID != '' && $hidden_guide_ID != 0) :
                $sqlWhere = " `guide_id` = '$hidden_guide_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_guide_details", $arrFields, $arrValues, $sqlWhere)) :
                    if (!empty($_guide_password)) :
                        $pwd_hash = PwdHash($_guide_password);
                        $usertoken = md5($_guide_password);
                        $arrFields_users = array('`roleID`', '`usertoken`', '`password`');
                        $arrValues_users = array("$_guide_select_role", "$usertoken", "$pwd_hash");
                        $sqlwhere_users = " `guide_id` = '$hidden_guide_ID' ";
                        sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users);
                    endif;
                    if (!empty($_guide_select_role)) :
                        $arrFields_users = array('`roleID`');
                        $arrValues_users = array("$_guide_select_role");
                        $sqlwhere_users = " `guide_id` = '$hidden_guide_ID' ";
                        (sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users));
                    endif;
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'guide.php?route=add&formtype=guide_pricebook&id=' . $hidden_guide_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_guide_details", $arrFields, $arrValues, '')) :
                    $guide_id = sqlINSERTID_LABEL();
                    //INSERT USERS TABLE
                    $pwd_hash = PwdHash($_guide_password);
                    $usertoken = md5($_guide_password);
                    $arrFields_users = array('`guide_id`', '`usertoken`', '`username`', '`useremail`', '`password`', '`roleID`', '`userapproved`', '`createdby`', '`status`');
                    $arrValues_users = array("$guide_id", "$usertoken", "$_guide_primary_mobile_number", "$_guide_email", "$pwd_hash", "$_guide_select_role", "1", "$logged_user_id", "1");

                    if (sqlACTIONS("INSERT", "dvi_users", $arrFields_users, $arrValues_users, '')) :
                        $response['i_result'] = true;
                        $response['redirect_URL'] = 'guide.php?route=add&formtype=guide_pricebook&id=' . $guide_id;
                        $response['result_success'] = true;
                    endif;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_guide_status') :

        $errors = [];
        $response = [];

        $GUIDE_ID = $validation_globalclass->sanitize($_POST['GUIDE_ID']);
        $STATUS_ID = $validation_globalclass->sanitize($_POST['STATUS_ID']);

        if ($STATUS_ID == '1') :
            $status = '0';
        elseif ($STATUS_ID == '0') :
            $status = '1';
        endif;

        //Update query
        $arrFields = array('`status`');
        $arrValues = array("$status");
        $sqlWhere = " `guide_id` = '$GUIDE_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_guide_details", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_delete') :

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
                    <button type="submit" onclick="confirmGUIDEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_guide_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_guide = sqlQUERY_LABEL("UPDATE `dvi_guide_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `guide_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_GUIDE:" . sqlERROR_LABEL());

        if ($delete_guide) :

            $delete_guide_reviews = sqlQUERY_LABEL("UPDATE `dvi_guide_review_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `guide_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_review:" . sqlERROR_LABEL());

            $delete_guide_pricebook = sqlQUERY_LABEL("UPDATE `dvi_guide_pricebook` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `guide_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_PRICEBOOK:" . sqlERROR_LABEL());

            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_pricebook') :

        $errors = [];
        $response = [];

        $_guide_ID = $_POST['hidden_guide_ID'];
        $_selectstartdate = $_POST['selectstartdate'];
        $_selectenddate = $_POST['selectenddate'];

        $_pax_type = $_POST['pax_type'];
        $_pax_slot_type = $_POST['pax_slot_type'];

        $_pax1_slot_price = $_POST['pax1_slot_price'];
        $_pax2_slot_price = $_POST['pax2_slot_price'];
        $_pax3_slot_price = $_POST['pax3_slot_price'];


        if (empty($_guide_ID)) :
            $errors['guide_required'] = true;
        endif;
        if (empty($_selectstartdate)) :
            $errors['selectstartdate_required'] = true;
        endif;
        if (empty($_selectenddate)) :
            $errors['selectenddate_required'] = true;
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

                for ($k = 0; $k < count($_pax_type); $k++) :

                    $pax_type = $_pax_type[$k];

                    for ($l = 0; $l < count($_pax_slot_type); $l++) :

                        $pax_slot_type = $_pax_slot_type[$l];
                        $guide_price = ${'_pax' . $pax_type . '_slot_price'}[$l];

                        if ($guide_price != '') :

                            //CHECK PRICE DETAILS ALREADY EXISTING
                            $check_price_already_existing = sqlQUERY_LABEL("SELECT `guide_price_book_ID` FROM `dvi_guide_pricebook` WHERE `guide_id`='$_guide_ID' AND `deleted`='0' AND  `pax_count`='$pax_type' AND  `slot_type`='$pax_slot_type'  AND `year`='$currentYear' AND `month`='$currentMonthName' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                            if (sqlNUMOFROW_LABEL($check_price_already_existing) > 0) :
                                while ($fetch_price_row = sqlFETCHARRAY_LABEL($check_price_already_existing)) :
                                    $guide_price_book_ID = $fetch_price_row['guide_price_book_ID'];
                                endwhile;

                                if ($start_date_of_month != $end_date_of_month) :

                                    $dayValuesArray = array();
                                    $dayfieldsArray = array();

                                    for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
                                        $day_wise_val = 'day_' . $i;

                                        $dayfieldsArray[] = "`" . $day_wise_val . "`";
                                        $dayValuesArray[] =  $guide_price;
                                    }

                                    $arrStaticFields = array();
                                    $arrStaticvalues = array();

                                    $arrFields = array_merge($arrStaticFields, $dayfieldsArray);
                                    $arrValues = array_merge($arrStaticvalues, $dayValuesArray);

                                else :

                                    $day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

                                    $arrFields = array($day_wise_varaible);
                                    $arrValues = array("$guide_price");
                                endif;

                                $sqlWhere = " `guide_price_book_ID` = '$guide_price_book_ID' ";
                                //UPDATE DETAILS
                                if (sqlACTIONS("UPDATE", "dvi_guide_pricebook", $arrFields, $arrValues, $sqlWhere)) :
                                endif;

                            else :
                                //INSERT DETAILS
                                if ($start_date_of_month != $end_date_of_month) :

                                    $dayValuesArray = array();
                                    $dayfieldsArray = array();

                                    for ($i = ltrim($start_day_of_month, '0'); $i <= $end_day_of_month; $i++) {
                                        $day_wise_val = 'day_' . $i;

                                        $dayfieldsArray[] = "`" . $day_wise_val . "`";
                                        $dayValuesArray[] =  $guide_price;
                                    }

                                    $arrStaticFields = array('`guide_id`', '`year`', '`month`', '`pax_count`', '`slot_type`', '`createdby`', '`status`');
                                    $arrStaticvalues = array("$_guide_ID", "$currentYear", "$currentMonthName", "$pax_type", "$pax_slot_type", "$logged_user_id", "1");

                                    $arrFields = array_merge($arrStaticFields, $dayfieldsArray);
                                    $arrValues = array_merge($arrStaticvalues, $dayValuesArray);

                                else :

                                    $day_wise_varaible = "`" . 'day_' . ltrim($start_day_of_month, '0') . "`";

                                    $arrFields = array('`guide_id`', '`year`', '`month`', '`pax_count`', '`slot_type`', $day_wise_varaible, '`createdby`', '`status`');

                                    $arrValues = array("$_guide_ID", "$currentYear", "$currentMonthName", "$pax_type", "$pax_slot_type", "$guide_price", "$logged_user_id", "1");

                                endif;
                                //INSERT DETAILS
                                if (sqlACTIONS(
                                    "INSERT",
                                    "dvi_guide_pricebook",
                                    $arrFields,
                                    $arrValues,
                                    ''
                                )) :
                                endif;

                            endif;

                        endif;

                    endfor;

                endfor;

                // Move to the next month and set the day to 1
                $currentDate = strtotime('+1 month', strtotime(date('01-m-Y', $currentDate)));

            endwhile;

            $response['u_result'] = true;
            $response['result_success'] = true;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_feedback') :

        $errors = [];
        $response = [];

        $guide_rating = $validation_globalclass->sanitize($_POST['guide_rating']);
        $review_description = $validation_globalclass->sanitize($_POST['review_description']);
        $status = '1';
        $hidden_guide_review_id = $_POST['hidden_guide_review_id'];
        $hidden_guide_ID = $_POST['hidden_guide_ID'];

        if (empty($guide_rating)) :
            $errors['guide_rating_required'] = true;
        endif;
        if (empty($review_description)) :
            $errors['guide_description_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;


            if ($hidden_guide_review_id != 0 && $hidden_guide_review_id != "") :

                $arrFields = array('`guide_rating`', '`guide_description`');

                $arrValues = array("$guide_rating", "$review_description");

                $sqlWhere_guide_review = " `guide_review_id` = '$hidden_guide_review_id' ";

                //UPDATE REVIEW DETAILS
                if (sqlACTIONS("UPDATE", "dvi_guide_review_details", $arrFields, $arrValues, $sqlWhere_guide_review)) :

                    $response['u_result'] = true;
                    $response['guide_id'] =  $hidden_guide_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;

            else :

                $arrFields = array('`guide_id`', '`guide_rating`', '`guide_description`', '`createdby`', '`status`');

                $arrValues = array("$hidden_guide_ID", "$guide_rating", "$review_description", "$logged_user_id", "$status");

                //INSERT FEEDBACK DETAILS
                if (sqlACTIONS("INSERT", "dvi_guide_review_details", $arrFields, $arrValues, '')) :

                    $response['i_result'] = true;
                    $response['result_success'] = true;
                    $response['guide_id'] =  $hidden_guide_ID;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_feedback_delete') :

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
    elseif ($_GET['type'] == 'confirm_guide_feedback_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_LIST = sqlQUERY_LABEL("UPDATE `dvi_guide_review_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `guide_review_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());
        if ($delete_LIST) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
