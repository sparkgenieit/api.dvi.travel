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
include_once('../../smtp_functions.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'staff_basic_info') :

        $errors = [];
        $response = [];

        $_staff_name = trim($_POST['staff_name']);
        $_staff_email = trim($_POST['staff_email']);
        $_staff_mobile = trim($_POST['staff_mobile']);
        $_staff_select_role = trim($_POST['staff_select_role']);
        $_staff_password = trim($_POST['staff_password']);
        $hidden_staff_ID = $_POST['hidden_staff_ID'];
        $hidden_agent_ID = $_POST['hidden_agent_ID'];

        if (empty($_staff_name)) :
            $errors['staff_name_required'] = true;
        endif;

        if (empty($_staff_mobile)) :
            $errors['staff_mobile_required'] = true;
        endif;

        if ($logged_user_level == '4') :
            $agent_id = $logged_agent_id;
            $_staff_select_role = 4;
        else :
            $agent_id = $hidden_agent_ID;
            $_staff_select_role = $_staff_select_role;
        endif;

        if ($hidden_staff_ID == '') :
            if (empty($_staff_email)) :
                $errors['staff_email_required'] = true;
            endif;
            if (empty($_staff_select_role) && $logged_user_level != '4' && $hidden_agent_ID == '') :
                $errors['staff_select_role_required'] = true;
            endif;
            if (empty($_staff_password)) :
                $errors['staff_password_required'] = true;
            endif;
        endif;


        if ($hidden_staff_ID == '' && $hidden_staff_ID == 0) :
            $account_email_id_already_exist = CHECK_USERNAME($_staff_email, 'useremail', '');
            $account_mobile_no_already_exist = CHECK_USERNAME($_staff_mobile, 'username', '');

            if ($account_email_id_already_exist > 0) :
                $errors['staff_email_address_already_exist'] = true;
            endif;

            if ($account_mobile_no_already_exist > 0) :
                $errors['staff_mobile_no_already_exist'] = true;
            endif;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($hidden_staff_ID != '' && $hidden_staff_ID != 0) :

                $sqlWhere = " `staff_id` = '$hidden_staff_ID' ";

                $arrFields = array('`staff_name`', '`staff_email`',  '`staff_mobile`', '`roleID`');

                $arrValues = array("$_staff_name", "$_staff_email",  "$_staff_mobile", "$_staff_select_role");

                //UPDATE VENDOR DETAILS
                if (sqlACTIONS("UPDATE", "dvi_staff_details", $arrFields, $arrValues, $sqlWhere)) :

                    if (!empty($_staff_password)) :
                        $pwd_hash = PwdHash($_staff_password);
                        $usertoken = md5($_staff_password);
                        $arrFields_users = array('`roleID`', '`usertoken`', '`password`');
                        $arrValues_users = array("$_staff_select_role", "$usertoken", "$pwd_hash");
                        $sqlwhere_users = " `staff_id` = '$hidden_staff_ID' ";
                        sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users);
                    else :
                        $arrFields_users = array('`roleID`');
                        $arrValues_users = array("$_staff_select_role");
                        $sqlwhere_users = " `staff_id` = '$hidden_staff_ID' ";
                        sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users);
                    endif;

                    $response['u_result'] = true;

                    if ($hidden_agent_ID != '') :
                        $response['redirect_URL'] = 'agent.php?route=edit&formtype=agent_staff&id=' . $hidden_agent_ID . '';
                    else :
                        $response['redirect_URL'] = 'newstaff.php';
                    endif;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :

                $arrFields = array('`agent_id`', '`staff_name`', '`staff_email`', '`staff_mobile`', '`roleID`', '`createdby`', '`status`');

                $arrValues = array("$agent_id", "$_staff_name", "$_staff_email", "$_staff_mobile", "$_staff_select_role", "$logged_user_id", "1");

                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_staff_details", $arrFields, $arrValues, '')) :
                    $staff_id = sqlINSERTID_LABEL();
                    //INSERT USERS TABLE
                    $pwd_hash = PwdHash($_staff_password);
                    $usertoken = md5($_staff_password);
                    $arrFields_users = array('`staff_id`', '`agent_id`', '`usertoken`', '`username`', '`useremail`', '`password`', '`roleID`', '`userapproved`', '`createdby`', '`status`');
                    $arrValues_users = array("$staff_id", "$agent_id", "$usertoken", "$_staff_mobile", "$_staff_email", "$pwd_hash", "$_staff_select_role", "1", "$logged_user_id", "1");

                    if (sqlACTIONS("INSERT", "dvi_users", $arrFields_users, $arrValues_users, '')) :
                        $response['i_result'] = true;
                        if ($hidden_agent_ID != '') :
                            $response['redirect_URL'] = 'agent.php?route=edit&formtype=agent_staff&id=' . $hidden_agent_ID . '';
                        else :
                            $response['redirect_URL'] = 'newstaff.php';
                        endif;
                        $response['result_success'] = true;
                    endif;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete') :
        $ID = $_GET['ID'];
?>
        <div class="modal-body">
            <div class="row">

                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmSTAFFDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>

    <?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        $delete_staff = sqlQUERY_LABEL("UPDATE `dvi_staff_details` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `staff_id` = '$_ID' ") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());

        if ($delete_staff) :
            $delete_USER = sqlQUERY_LABEL("UPDATE `dvi_users` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `staff_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_USER:" . sqlERROR_LABEL());

            $response['success'] = true;
            $response['result_success'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $staff_ID = $_POST['STAFF_ID'];
        $oldstatus = $_POST['oldstatus'];

        if ($oldstatus == 1) :
            $status = 0;
        elseif ($oldstatus == 0) :
            $status = 1;
        endif;

        //Update query
        $arrFields = array('`status`');

        $arrValues = array("$status");

        $sqlWhere = " `staff_id` = '$staff_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_staff_details", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            //Update query
            $arrFields_user = array('`userbanned`');

            if ($status == 1) :
                $user_banned = 0;
            elseif ($status == 0) :
                $user_banned = 1;
            endif;

            $arrValues = array("$user_banned");

            $sqlWhere = " `staff_id` = '$staff_ID' ";
            $update_user_status = sqlACTIONS("UPDATE", "dvi_users", $arrFields_user, $arrValues, $sqlWhere);

            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_additional_staff_request') :

        $errors = [];
        $response = [];

        $SUBSCRIPTION_PLAN_ID = $_POST['SUBSCRIPTION_PLAN_ID'];
        $AGENT_ID = $_POST['AGENT_ID'];
        $additional_charge_for_per_staff = get_AGENT_SUBSCRIBED_PLAN_DETAILS($SUBSCRIPTION_PLAN_ID, $AGENT_ID, 'additional_charge_for_per_staff');

        $check_previous_request_details = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_additional_info_ID`, COALESCE(SUM(`no_of_additional_staff`), 0) AS TOTAL_STAFF, COALESCE(SUM(`total_additional_staff_charges`), 0) AS TOTAL_STAFF_CHARGES FROM `dvi_agent_subscribed_plans_additional_info` WHERE `agent_ID` = '$AGENT_ID' AND `status` = '0' GROUP BY `agent_subscribed_plan_additional_info_ID`") or die("#1-UNABLE_TO_COLLECT_THE_REQUEST:" . sqlERROR_LABEL());
        $total_num_rows_count = sqlNUMOFROW_LABEL($check_previous_request_details);
        while ($fetch_agent_staff_details = sqlFETCHARRAY_LABEL($check_previous_request_details)) :
            $agent_subscribed_plan_additional_info_ID = $fetch_agent_staff_details['agent_subscribed_plan_additional_info_ID'];
            $TOTAL_STAFF = $fetch_agent_staff_details['TOTAL_STAFF'];
            $TOTAL_STAFF_CHARGES = $fetch_agent_staff_details['TOTAL_STAFF_CHARGES'];
        endwhile;

        if ($total_num_rows_count == 0) :
            $total_new_staff = 1;
            $total_staff_charges = $additional_charge_for_per_staff;
            $arrFields_agent_staff = array('`agent_subscribed_plan_ID`', '`no_of_additional_staff`', '`agent_ID`', '`total_additional_staff_charges`', '`createdby`', '`status`');

            $arrValues_agent_staff = array("$SUBSCRIPTION_PLAN_ID", "$total_new_staff", "$AGENT_ID", "$total_staff_charges", "$logged_user_id", "0");

            if (sqlACTIONS("INSERT", "dvi_agent_subscribed_plans_additional_info", $arrFields_agent_staff, $arrValues_agent_staff, '')) :
                $response['result_success'] = true;

                // Set global variables
                global $SUBSCRIPTION_PLAN_ID, $total_new_staff, $AGENT_ID, $total_staff_charges;

                // Assign values to global variables
                $_SESSION['global_sid'] = $SUBSCRIPTION_PLAN_ID;
                $_SESSION['global_staff_count'] = $total_new_staff;
                $_SESSION['global_agent_id'] = $AGENT_ID;
                $_SESSION['global_total_charges'] = $total_staff_charges;

                // Include the email notification script
                include('ajax_attempt_staff_email_notification.php');

                // Unset the global variables
                unset($_SESSION['global_sid']);
                unset($_SESSION['global_staff_count']);
                unset($_SESSION['global_agent_id']);
                unset($_SESSION['global_total_charges']);

            else :
                $response['result_success'] = false;
            endif;
        else :
            if ($TOTAL_STAFF > 0 && $TOTAL_STAFF_CHARGES > 0) :
                $PER_STAFF_CHARGE = ($TOTAL_STAFF_CHARGES / $TOTAL_STAFF);
            endif;

            $total_new_staff = $TOTAL_STAFF + 1;
            $total_staff_charges = $total_new_staff * $PER_STAFF_CHARGE;

            $arrFields_agent_staff = array('`agent_subscribed_plan_ID`', '`no_of_additional_staff`', '`agent_ID`', '`total_additional_staff_charges`', '`createdby`', '`status`');

            $arrValues_agent_staff = array("$SUBSCRIPTION_PLAN_ID", "$total_new_staff", "$AGENT_ID", "$total_staff_charges", "$logged_user_id", "0");

            $sqlWhere = " `agent_subscribed_plan_additional_info_ID` = '$agent_subscribed_plan_additional_info_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_agent_subscribed_plans_additional_info", $arrFields_agent_staff, $arrValues_agent_staff, $sqlWhere)) :
                $response['result_success'] = true;
                // Set global variables
                global $SUBSCRIPTION_PLAN_ID, $total_new_staff, $AGENT_ID, $total_staff_charges;

                // Assign values to global variables
                $_SESSION['global_sid'] = $SUBSCRIPTION_PLAN_ID;
                $_SESSION['global_staff_count'] = $total_new_staff;
                $_SESSION['global_agent_id'] = $AGENT_ID;
                $_SESSION['global_total_charges'] = $total_staff_charges;

                // Include the email notification script
                include('ajax_attempt_staff_email_notification.php');

                // Unset the global variables
                unset($_SESSION['global_sid']);
                unset($_SESSION['global_staff_count']);
                unset($_SESSION['global_agent_id']);
                unset($_SESSION['global_total_charges']);

            else :
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);
    elseif ($_GET['type'] == 'approve_staff_request') :

        $_id = $_GET['ID'];
        $AGENT_ID = getAGENT_ADD_STAFF_DETAILS($_id, 'get_agent_id');
        $SUBSCRIBED_PLAN_ID = getAGENT_ADD_STAFF_DETAILS($_id, 'agent_subscribed_plan_id');
        $ADDITIONAL_STAFF_COUNT = getAGENT_ADD_STAFF_DETAILS($_id, 'get_staff_count');
        $ADDITIONAL_STAFF_CHARGE = getAGENT_ADD_STAFF_DETAILS($_id, 'get_staff_charge');

        $agent_full_name = getAGENT_details($AGENT_ID, '', 'label');
    ?>
        <div class="modal-body">
            <div class="row">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="70" height="70" x="0" y="0" viewBox="0 0 330 330" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <path d="M165 0C74.019 0 0 74.019 0 165s74.019 165 165 165 165-74.019 165-165S255.981 0 165 0zm0 300c-74.44 0-135-60.561-135-135S90.56 30 165 30s135 60.561 135 135-60.561 135-135 135z" fill="#44b678" opacity="1" data-original="#000000" class="" />
                            <path d="m226.872 106.664-84.854 84.853-38.89-38.891c-5.857-5.857-15.355-5.858-21.213-.001-5.858 5.858-5.858 15.355 0 21.213l49.496 49.498a15 15 0 0 0 10.606 4.394h.001c3.978 0 7.793-1.581 10.606-4.393l95.461-95.459c5.858-5.858 5.858-15.355 0-21.213-5.858-5.858-15.355-5.859-21.213-.001z" fill="#44b678" opacity="1" data-original="#000000" class="" />
                        </g>
                    </svg>
                </div>
                <h6 class="mt-3 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to approve additional staff request? <br /> Agent Name: <b><?= $agent_full_name; ?></b> <br />This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmAPPROVEAGENTSTAFFREQUEST('<?= $_id; ?>');" class="btn btn-danger">Approve</button>
                </div>

            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_approve_staff_request') :

        $errors = [];
        $response = [];

        $_id = $_POST['_ID'];
        $AGENT_ID = getAGENT_ADD_STAFF_DETAILS($_id, 'get_agent_id');
        $SUBSCRIBED_PLAN_ID = getAGENT_ADD_STAFF_DETAILS($_id, 'agent_subscribed_plan_id');
        $ADDITIONAL_STAFF_COUNT = getAGENT_ADD_STAFF_DETAILS($_id, 'get_staff_count');
        $ADDITIONAL_STAFF_CHARGE = getAGENT_ADD_STAFF_DETAILS($_id, 'get_staff_charge');
        $approved_by = $logged_user_id;

        $select_agent_details = sqlQUERY_LABEL("SELECT `agent_ID` FROM `dvi_agent` WHERE `status`= '1' AND `deleted`='0' AND `agent_ID` = '$AGENT_ID'") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());
        $total_active_agent_count = sqlNUMOFROW_LABEL($select_agent_details);
        if ($total_active_agent_count > 0) :
            //success call	
            $response['success'] = true;
            $check_agent_add_additional_staff_details = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_ID` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `agent_subscribed_plan_additional_info_ID` = '$_id' AND `status`= '0' AND `deleted`='0' ORDER BY `agent_subscribed_plan_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());
            $check_agent_add_additional_staff_details_count = sqlNUMOFROW_LABEL($check_agent_add_additional_staff_details);

            if ($check_agent_add_additional_staff_details_count > 0) :
                $current_date = date('Y-m-d');
                $check_agent_subscription_plan_details = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_ID` FROM `dvi_agent_subscribed_plans` WHERE `agent_ID` = '$AGENT_ID' AND `subscription_plan_ID` = '$SUBSCRIBED_PLAN_ID' AND `status`= '1' AND `deleted`='0' AND `validity_end` > '$current_date' ORDER BY `agent_subscribed_plan_ID`  DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());

                $check_agent_subscription_plan_count = sqlNUMOFROW_LABEL($check_agent_subscription_plan_details);
                if ($check_agent_subscription_plan_count > 0) :

                    $agent_subscribed_plan_arrFields = array('`additional_staff_count`', '`additional_staff_charge`', '`additional_staff_approved_by`');
                    $agent_subscribed_plan_arrValues = array("$ADDITIONAL_STAFF_COUNT", "$ADDITIONAL_STAFF_CHARGE", "$approved_by");
                    $agent_subscribed_plan_sqlWhere = "`agent_ID` = '$AGENT_ID' AND `subscription_plan_ID` = '$SUBSCRIBED_PLAN_ID' AND `status`= '1' AND `deleted`='0' ";

                    $get_cash_wallet_balance = getAGENT_details($AGENT_ID, '', 'get_total_agent_cash_wallet');
                    $get_coupon_wallet_balance = getAGENT_details($AGENT_ID, '', 'get_total_agent_coupon_wallet');
                    $transaction_date = date('Y-m-d');
                    if ($get_cash_wallet_balance >= $ADDITIONAL_STAFF_CHARGE || $get_coupon_wallet_balance >= $ADDITIONAL_STAFF_CHARGE) :

                        if (sqlACTIONS("UPDATE", "dvi_agent_subscribed_plans", $agent_subscribed_plan_arrFields, $agent_subscribed_plan_arrValues, $agent_subscribed_plan_sqlWhere)) :
                            $update_additional_staff_info_arrFields = array('`status`');
                            $update_additional_staff_info_arrValues = array("1");
                            $update_additional_staff_info_sqlWhere = " `agent_subscribed_plan_additional_info_ID` = '$_id' AND `status`= '0' AND `deleted`='0' ";

                            sqlACTIONS("UPDATE", "dvi_agent_subscribed_plans_additional_info", $update_additional_staff_info_arrFields, $update_additional_staff_info_arrValues, $update_additional_staff_info_sqlWhere);

                            if ($get_cash_wallet_balance >= $ADDITIONAL_STAFF_CHARGE) :

                                $cash_wallet_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
                                $cash_wallet_arrValues = array("$AGENT_ID", "$transaction_date", "$ADDITIONAL_STAFF_CHARGE", "2", "Agent Request Staff Approvel Detection", "1");

                                sqlACTIONS("INSERT", "dvi_cash_wallet", $cash_wallet_arrFields, $cash_wallet_arrValues, '');

                                $total_cash_wallet = $get_cash_wallet_balance - $ADDITIONAL_STAFF_CHARGE;

                                $agent_arrFields = array('`total_cash_wallet`');
                                $agent_arrValues = array("$total_cash_wallet");
                                $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
                                sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                            else :
                                $coupon_wallet_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
                                $coupon_wallet_arrValues = array("$AGENT_ID", "$transaction_date", "$ADDITIONAL_STAFF_CHARGE", "2", "Agent Request Staff Approvel Detection", "1");

                                sqlACTIONS("INSERT", "dvi_coupon_wallet", $coupon_wallet_arrFields, $coupon_wallet_arrValues, '');

                                $total_coupon_wallet = $get_coupon_wallet_balance - $ADDITIONAL_STAFF_CHARGE;

                                $agent_arrFields = array('`total_coupon_wallet`');
                                $agent_arrValues = array("$total_coupon_wallet");
                                $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
                                sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                            endif;

                            $response['success'] = true;
                            $response['result'] = true;

                            $response['result_success'] = "Additional Staff Approved Successfully";
                        else :
                            $response['result'] = false;
                            $response['result_error'] = "Unable to Approve the Additional Staff";
                        endif;
                    else :
                        $response['result'] = false;
                        $response['result_error'] = "No Balance Available in Agent Coupon Wallet and  Cash Wallet";
                    endif;
                else :
                    $response['result_error'] = "Agent Subscription Plan is Invalid [or] Expired";
                endif;
            else :
                $response['result_error'] = "Invalid Request [or]  Agent Additional Staff Request Already Approved";
            endif;

        else :
            $errors['not_active_agent'] = "Agent was not Active. Please Verify the Agent Information First";
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'decline_staff_request') :

        $ID = $_GET['ID'];

        $agent_full_name = getAGENT_details($ID, '', 'label');
    ?>
        <div class="row">
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="70" height="70" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                    <g>
                        <g fill="#000">
                            <path d="M255.575 476.292a219.93 219.93 0 0 1-156.036-64.53c-86.052-86.051-86.052-226.057 0-312.108a219.255 219.255 0 0 1 156.054-64.653c58.95 0 114.37 22.951 156.036 64.653 41.684 41.684 64.653 97.103 64.653 156.054s-22.952 114.37-64.653 156.054a219.989 219.989 0 0 1-156.054 64.53zm.018-405.98a184.107 184.107 0 0 0-131.09 54.306c-35.01 35.011-54.29 81.567-54.29 131.09s19.28 96.062 54.29 131.09c72.28 72.28 189.899 72.298 262.162 0 35.01-35.01 54.307-81.567 54.307-131.09s-19.28-96.062-54.307-131.09a184.192 184.192 0 0 0-131.072-54.307z" fill="#ea5455" opacity="1" data-original="#000000" class="" />
                            <path d="M180.677 348.25a17.64 17.64 0 0 1-16.334-10.888 17.64 17.64 0 0 1 3.852-19.249l149.804-149.804a17.65 17.65 0 0 1 24.964 0 17.65 17.65 0 0 1 0 24.964L193.159 343.078a17.53 17.53 0 0 1-12.482 5.172z" fill="#ea5455" opacity="1" data-original="#000000" class="" />
                            <path d="M330.491 348.25a17.59 17.59 0 0 1-12.482-5.172L168.204 193.273a17.654 17.654 0 0 1 24.965-24.964l149.804 149.804a17.632 17.632 0 0 1 3.852 19.249 17.645 17.645 0 0 1-6.512 7.927 17.642 17.642 0 0 1-9.822 2.961z" fill="#ea5455" opacity="1" data-original="#000000" class="" />
                        </g>
                    </g>
                </svg>
            </div>
            <h6 class="mt-3 mb-2 text-center">Are you sure?</h6>
            <p class="text-center">Do you really want to decline the staff request of<br /> <b><?= $agent_full_name; ?></b>? <br /> This process cannot be undone.</p>

            <div class="text-center pb-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" onclick="confirmDECLINESTAFF_REQUEST_APPROVEL('<?= $ID; ?>');" class="btn btn-danger">Decline</button>
            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_decline_staff_request') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];


        $agent_arrFields = array('`status`');
        $agent_arrValues = array("2");
        $agent_sqlWhere = " `agent_ID` = '$_ID' AND `status`= '0' AND `deleted`='0' ";

        if (sqlACTIONS("UPDATE", "dvi_agent_subscribed_plans_additional_info", $agent_arrFields, $agent_arrValues, $agent_sqlWhere)) :
            $response['result'] = true; /* "Decline the Student Grade Promotion" */
            $response['result_success'] = "Agent Staff Request was successfully Declined";
        else :
            $response['result'] = false; /* "Unable to Decline the Student Grade Promotion" */
            $response['result_error'] = "Unable to Decline the Agent Staff Request";
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'bulk_approve_staff_request') :

        $agent_staff_request_approval = $_POST['agent_staff_request_approval'];

        $array_agent_staff_request_approval = implode(',', $agent_staff_request_approval);
    ?>
        <div class="modal-body">
            <div class="row">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="70" height="70" x="0" y="0" viewBox="0 0 330 330" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                        <g>
                            <path d="M165 0C74.019 0 0 74.019 0 165s74.019 165 165 165 165-74.019 165-165S255.981 0 165 0zm0 300c-74.44 0-135-60.561-135-135S90.56 30 165 30s135 60.561 135 135-60.561 135-135 135z" fill="#44b678" opacity="1" data-original="#000000" class="" />
                            <path d="m226.872 106.664-84.854 84.853-38.89-38.891c-5.857-5.857-15.355-5.858-21.213-.001-5.858 5.858-5.858 15.355 0 21.213l49.496 49.498a15 15 0 0 0 10.606 4.394h.001c3.978 0 7.793-1.581 10.606-4.393l95.461-95.459c5.858-5.858 5.858-15.355 0-21.213-5.858-5.858-15.355-5.859-21.213-.001z" fill="#44b678" opacity="1" data-original="#000000" class="" />
                        </g>
                    </svg>
                </div>
                <h6 class="mt-3 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to approve all agent additional staff request? <br />This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <input type="hidden" name="hidden_selected_agent_request" id="hidden_selected_agent_request" value="<?= $array_agent_staff_request_approval; ?>" hidden>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmBULKAPPROVESTAFF_REQUEST_APPROVEL();" class="btn btn-danger">Approve</button>
                </div>

            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_bulk_approve_staff_request') :

        $errors = [];
        $response = [];

        $hidden_selected_IDS = $_POST['_hidden_selected_agent_request'];
        $approved_by = $logged_user_id;

        $select_additional_info_details = sqlQUERY_LABEL("SELECT `agent_ID`, `agent_subscribed_plan_additional_info_ID` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `status`= '0' AND `deleted`='0' AND `agent_subscribed_plan_additional_info_ID` IN ($hidden_selected_IDS)") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());
        $total_active_info_count = sqlNUMOFROW_LABEL($select_additional_info_details);
        if ($total_active_info_count > 0) :
            while ($fetch_info_data = sqlFETCHARRAY_LABEL($select_additional_info_details)) :
                $AGENT_ID = $fetch_info_data['agent_ID'];
                $_id = $fetch_info_data['agent_subscribed_plan_additional_info_ID'];

                $SUBSCRIBED_PLAN_ID = getAGENT_ADD_STAFF_DETAILS($_id, 'agent_subscribed_plan_id');
                $ADDITIONAL_STAFF_COUNT = getAGENT_ADD_STAFF_DETAILS($_id, 'get_staff_count');
                $ADDITIONAL_STAFF_CHARGE = getAGENT_ADD_STAFF_DETAILS($_id, 'get_staff_charge');

                $check_agent_add_additional_staff_details = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_ID` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `agent_subscribed_plan_additional_info_ID` = '$_id' AND `status`= '0' AND `deleted`='0' ORDER BY `agent_subscribed_plan_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());
                $check_agent_add_additional_staff_details_count = sqlNUMOFROW_LABEL($check_agent_add_additional_staff_details);

                if ($check_agent_add_additional_staff_details_count > 0) :
                    $current_date = date('Y-m-d');
                    $check_agent_subscription_plan_details = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_ID` FROM `dvi_agent_subscribed_plans` WHERE `agent_ID` = '$AGENT_ID' AND `subscription_plan_ID` = '$SUBSCRIBED_PLAN_ID' AND `status`= '1' AND `deleted`='0' AND `validity_end` > '$current_date' ORDER BY `agent_subscribed_plan_ID`  DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_AGENT_DETAILS:" . sqlERROR_LABEL());

                    $check_agent_subscription_plan_count = sqlNUMOFROW_LABEL($check_agent_subscription_plan_details);
                    if ($check_agent_subscription_plan_count > 0) :

                        $agent_subscribed_plan_arrFields = array('`additional_staff_count`', '`additional_staff_charge`', '`additional_staff_approved_by`');
                        $agent_subscribed_plan_arrValues = array("$ADDITIONAL_STAFF_COUNT", "$ADDITIONAL_STAFF_CHARGE", "$approved_by");
                        $agent_subscribed_plan_sqlWhere = "`agent_ID` = '$AGENT_ID' AND `subscription_plan_ID` = '$SUBSCRIBED_PLAN_ID' AND `status`= '1' AND `deleted`='0' ";

                        $get_cash_wallet_balance = getAGENT_details($AGENT_ID, '', 'get_total_agent_cash_wallet');
                        $get_coupon_wallet_balance = getAGENT_details($AGENT_ID, '', 'get_total_agent_coupon_wallet');
                        $transaction_date = date('Y-m-d');
                        if ($get_cash_wallet_balance >= $ADDITIONAL_STAFF_CHARGE || $get_coupon_wallet_balance >= $ADDITIONAL_STAFF_CHARGE) :

                            if (sqlACTIONS("UPDATE", "dvi_agent_subscribed_plans", $agent_subscribed_plan_arrFields, $agent_subscribed_plan_arrValues, $agent_subscribed_plan_sqlWhere)) :
                                $update_additional_staff_info_arrFields = array('`status`');
                                $update_additional_staff_info_arrValues = array("1");
                                $update_additional_staff_info_sqlWhere = " `agent_subscribed_plan_additional_info_ID` = '$_id' AND `status`= '0' AND `deleted`='0' ";

                                sqlACTIONS("UPDATE", "dvi_agent_subscribed_plans_additional_info", $update_additional_staff_info_arrFields, $update_additional_staff_info_arrValues, $update_additional_staff_info_sqlWhere);

                                if ($get_coupon_wallet_balance >= $ADDITIONAL_STAFF_CHARGE) :
                                    $coupon_wallet_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
                                    $coupon_wallet_arrValues = array("$AGENT_ID", "$transaction_date", "$ADDITIONAL_STAFF_CHARGE", "2", "Agent Request Staff Approvel Detection", "1");

                                    sqlACTIONS("INSERT", "dvi_coupon_wallet", $coupon_wallet_arrFields, $coupon_wallet_arrValues, '');

                                    $total_coupon_wallet = $get_coupon_wallet_balance - $ADDITIONAL_STAFF_CHARGE;

                                    $agent_arrFields = array('`total_coupon_wallet`');
                                    $agent_arrValues = array("$total_coupon_wallet");
                                    $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
                                    sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                                else :
                                    $cash_wallet_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
                                    $cash_wallet_arrValues = array("$AGENT_ID", "$transaction_date", "$ADDITIONAL_STAFF_CHARGE", "2", "Agent Request Staff Approvel Detection", "1");

                                    sqlACTIONS("INSERT", "dvi_cash_wallet", $cash_wallet_arrFields, $cash_wallet_arrValues, '');

                                    $total_cash_wallet = $get_cash_wallet_balance - $ADDITIONAL_STAFF_CHARGE;

                                    $agent_arrFields = array('`total_cash_wallet`');
                                    $agent_arrValues = array("$total_cash_wallet");
                                    $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
                                    sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                                endif;

                                $response['success'] = true;
                                $response['result'] = true;

                                $response['result_success'] = "Additional Staff Approved Successfully for Agent ID: $AGENT_ID";
                            else :
                                $response['result'] = false;
                                $response['result_error'] = "Unable to Approve the Additional Staff for Agent ID: $AGENT_ID";
                            endif;
                        else :
                            $response['result'] = false;
                            $response['result_error'] = "No Balance Available in Agent Coupon Wallet and  Cash Wallet for Agent ID: $AGENT_ID";
                        endif;

                    else :
                        $response['result_error'] = "Agent Subscription Plan is Invalid [or] Expired for Agent ID: $AGENT_ID";
                    endif;
                else :
                    $response['result_error'] = "Invalid Request [or] Agent Additional Staff Request Already Approved for Agent ID: $AGENT_ID";
                endif;

            endwhile;
        else :
            $errors['not_active_agent'] = "No Active Agents Found. Please Verify the Agent Information First";
        endif;

        echo json_encode($response);
    endif;
endif;
