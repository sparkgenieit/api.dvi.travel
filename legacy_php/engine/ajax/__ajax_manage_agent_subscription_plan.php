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

    if ($_GET['type'] == 'basic_info') :

        $errors = [];
        $response = [];

        $agent_subscription_plan_title = trim($_POST['agent_subscription_plan_title']);
        $itinerary_allowed = trim($_POST['itinerary_allowed']);
        $subscription_type = trim($_POST['subscription_type']);
        $subscription_amount = $_POST['subscription_amount'];
        $joining_bonus = $_POST['joining_bonus'];
        $admin_count = trim($_POST['admin_count']);
        $staff_count = trim($_POST['staff_count']);
        $per_itinerary_cost = $_POST['per_itinerary_cost'];
        $validity_in_days = trim($_POST['validity_in_days']);
        $subscription_notes = ($_POST['subscription_notes']);
        $additional_charge_for_per_staff = trim($_POST['additional_charge_for_per_staff']);

    
        $hidden_agent_subscription_ID = trim($_POST['hidden_agent_subscription_ID']);

        if (empty($agent_subscription_plan_title)) :
            $errors['agent_subscription_plan_title'] = true;
        endif;
        if (empty($itinerary_allowed)) :
            $errors['itinerary_allowed'] = true;
        endif;
        if (empty($subscription_type)) :
            $errors['subscription_type'] = true;
        endif;
        if (empty($joining_bonus)) :
            $errors['joining_bonus'] = true;
        endif;
        if (empty($staff_count)) :
            $errors['staff_count'] = true;
        endif;
        if (empty($per_itinerary_cost)) :
            $errors['per_itinerary_cost'] = true;
        endif;
        if (empty($validity_in_days)) :
            $errors['validity_in_days'] = true;
        endif;
        if (empty($subscription_notes)) :
            $errors['subscription_notes'] = true;
        endif;
        if (empty($additional_charge_for_per_staff)) :
            $errors['additional_charge_for_per_staff'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`agent_subscription_plan_title`', '`itinerary_allowed`', '`subscription_type`', '`subscription_amount`', '`joining_bonus`', '`admin_count`', '`staff_count`', '`per_itinerary_cost`', '`validity_in_days`', '`subscription_notes`', '`additional_charge_for_per_staff`', '`createdby`', '`status`');

            $arrValues = array("$agent_subscription_plan_title", "$itinerary_allowed", "$subscription_type", "$subscription_amount", "$joining_bonus", "$admin_count", "$staff_count", "$per_itinerary_cost", "$validity_in_days", "$subscription_notes", "$additional_charge_for_per_staff", "$logged_user_id", "1");

            if ($hidden_agent_subscription_ID != '' && $hidden_agent_subscription_ID != 0) :
                $sqlWhere = " `agent_subscription_plan_ID` = '$hidden_agent_subscription_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_agent_subscription_plan", $arrFields, $arrValues, $sqlWhere)) :
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'agent_subscription_plan.php';
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_agent_subscription_plan", $arrFields, $arrValues, '')) :
                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'agent_subscription_plan.php';
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_status') :

        $errors = [];
        $response = [];

        $AGENT_SUBSCRIPTION_ID = $validation_globalclass->sanitize($_POST['AGENT_SUBSCRIPTION_ID']);
        $STATUS_ID = $validation_globalclass->sanitize($_POST['STATUS_ID']);

        if ($STATUS_ID == '1') :
            $status = '0';
        elseif ($STATUS_ID == '0') :
            $status = '1';
        endif;

        //Update query
        $arrFields = array('`status`');
        $arrValues = array("$status");
        $sqlWhere = " `agent_subscription_plan_ID` = '$AGENT_SUBSCRIPTION_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_agent_subscription_plan", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'recommended_status') :

        $errors = [];
        $response = [];

        $AGENT_SUBSCRIPTION_ID = $validation_globalclass->sanitize($_POST['AGENT_SUBSCRIPTION_ID']);
        $RECOMMENDED_STATUS_ID = $validation_globalclass->sanitize($_POST['RECOMMENDED_STATUS_ID']);


        if ($RECOMMENDED_STATUS_ID == '1') :
            $status = '0';
        elseif ($RECOMMENDED_STATUS_ID == '0') :
            $status = '1';
        endif;

        //Update query
        $arrFields = array('`recommended_status`');
        $arrValues = array("$status");
        $sqlWhere = " `agent_subscription_plan_ID` = '$AGENT_SUBSCRIPTION_ID' ";

        $update_status = sqlACTIONS("UPDATE", "dvi_agent_subscription_plan", $arrFields, $arrValues, $sqlWhere);

        if ($update_status) :
            // If the new status is '1', turn off all other recommended statuses
            if ($status == '1') {
                $sqlWhereOff = " `agent_subscription_plan_ID` != '$AGENT_SUBSCRIPTION_ID' ";
                $arrFieldsOff = array('`recommended_status`');
                $arrValuesOff = array('0');
                sqlACTIONS("UPDATE", "dvi_agent_subscription_plan", $arrFieldsOff, $arrValuesOff, $sqlWhereOff);
            }

            $response['result_success'] = true;
        else :
            $response['result_success'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_delete') :

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
                    <button type="submit" onclick="confirmAGENT_SUBSCRIBE_PLAN('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>

            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_update_delete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_LIST = sqlQUERY_LABEL("UPDATE `dvi_agent_subscription_plan` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `agent_subscription_plan_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_SUBSCRIPTION_PLAN:" . sqlERROR_LABEL());
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
