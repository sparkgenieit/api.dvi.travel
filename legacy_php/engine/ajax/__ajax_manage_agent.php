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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'edit') :

        $errors = [];
        $response = [];

        $agent_first_name = trim($_POST['agent_first_name']);
        $agent_last_name = trim($_POST['agent_last_name']);
        $agent_email_address = trim($_POST['agent_email_address']);
        $agent_mobile_number = trim($_POST['agent_mobile_number']);
        $agent_alternative_mobile_number = $_POST['agent_alternative_mobile_number'];
        $agent_gst_number = $_POST['agent_gst_number'];
        $agent_subscription_plan = $_POST['agent_subscription_plan'];
        $travel_expert = $_POST['travel_expert'];
        $hidden_agent_ID = $_POST['hidden_agent_ID']; // Assuming agent_id is passed in POST data
        $hidden_travel_expert_ID = $_POST['hidden_travel_expert_ID'];
        $country_name = $_POST['country_name'];
        $country_name = 101;
        $state_name = $_POST['state_name'];
        $sanitize_city_name_lower = strtolower(trim($_POST['city_name']));
        $list_cities_datas = sqlQUERY_LABEL("SELECT `id` FROM `dvi_cities` WHERE `state_id`= '$state_name' AND LOWER(`name`) = '" . $sanitize_city_name_lower . "'") or die("UNABLE_TO_CHECKING_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
        $total_cities_row = sqlNUMOFROW_LABEL($list_cities_datas);

        if (($total_cities_row == 1)) :
            $city_name = trim($_POST['city_name']);
            while ($fetch_data = sqlFETCHARRAY_LABEL($list_cities_datas)) :
                $city_name = $fetch_data['id'];
            endwhile;
        elseif (($total_cities_row == 0)):
            $sanitize_city_name = $validation_globalclass->sanitize($_POST['city_name']);
            $sanitize_city_name = ucfirst($sanitize_city_name);
            $sanitize_state_id = $validation_globalclass->sanitize($_POST['state_name']);
            $arrFields = array('`state_id`', '`name`');
            $arrValues = array("$sanitize_state_id", "$sanitize_city_name");
            if (sqlACTIONS("INSERT", "dvi_cities", $arrFields, $arrValues, '')):
                $city_name = sqlINSERTID_LABEL();
            endif;
        else:
            $city_name = 0;
        endif;

        if (empty($_POST['agent_first_name'])) :
            $errors['agent_first_name_required'] = true;
        elseif (empty($_POST['agent_last_name'])) :
            $errors['agent_last_name_required'] = true;
        elseif (empty($_POST['agent_email_address'])) :
            $errors['agent_email_address_required'] = true;
        elseif (empty($_POST['agent_mobile_number'])) :
            $errors['agent_mobile_number_required'] = true;
        elseif (empty($_POST['agent_gst_number'])) :
            $errors['agent_gst_number_required'] = true;
        elseif (empty($country_name)) :
            $errors['country_name_required'] = true;
        elseif (empty($_POST['state_name'])) :
            $errors['state_name_required'] = true;
        elseif (empty($_POST['city_name'])) :
            $errors['city_name_required'] = true;
        endif;

        if (!empty($errors)) :
            // error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`agent_country`', '`agent_state`', '`agent_city`', '`travel_expert_id`', '`agent_name`', '`agent_lastname`', '`agent_primary_mobile_number`', '`agent_alternative_mobile_number`', '`agent_email_id`', '`agent_gst_number`');
            $arrValues = array("$country_name", "$state_name", "$city_name", "$travel_expert", "$agent_first_name", " $agent_last_name", "$agent_mobile_number", "$agent_alternative_mobile_number", "$agent_email_address", "$agent_gst_number");
            $sqlWhere = " `agent_id` = '$hidden_agent_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_agent", $arrFields, $arrValues, $sqlWhere)) {
                // UPDATE SUCCESSFUL

                if (($hidden_travel_expert_ID = 0 || $hidden_travel_expert_ID = '') || ($hidden_travel_expert_ID != $travel_expert)) :

                    global $travel_expert, $hidden_agent_ID;

                    // Assign values to global variables
                    $_SESSION['global_texp_id'] = $travel_expert;
                    $_SESSION['global_aid'] = $hidden_agent_ID;

                    // Include the email notification script
                    include('ajax_assign_travel_expert_email_notification.php');

                    // Unset the global variables
                    unset($_SESSION['global_texp_id']);
                    unset($_SESSION['global_aid']);

                endif;
                $response['result'] = true;
                $response['redirect_URL'] = 'agent.php?route=edit&formtype=agent_staff&id=' . $hidden_agent_ID . '';
                $response['result_success'] = true;
                $response['result_success_email'] = true;
            } else {
                $response['result'] = false;
                $response['result_success'] = false;
            }

        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'config') :

        $errors = [];
        $response = [];

        $agent_id = $_GET['id'];
        $itinerary_margin_discount_percentage = trim($_POST['itinerary_margin_discount_percentage']);
        $agent_margin = trim($_POST['agent_margin']);
        $agent_margin_gst_type = trim($_POST['agent_margin_gst_type']);
        $agent_margin_gst_percentage = trim($_POST['agent_margin_gst_percentage']);
        $agent_password = trim($_POST['agent_password']);


        $_site_address = $_POST['site_address'];
        $_terms_condition = $_POST['terms_condition'];
        $_gst_in_number = trim($_POST['gst_in_number']);
        $_invoice_pan_no = trim($_POST['invoice_pan_no']);
        $_invoice_address = $_POST['invoice_address'];

        $new_file_name = trim($_FILES['site_logo_upload']['name']);
        $invoice_file_name = trim($_FILES['invoice_logo_upload']['name']);
        $site_logo = trim($_POST['site_logo']);
        $invoice_logo = trim($_POST['invoice_logo']);
        $agent_company_name = trim($_POST['agent_company_name']);

        if ($itinerary_margin_discount_percentage == '') :
            $errors['agent_itinerary_discount_margin_required'] = true;
        endif;
        if ($agent_margin == '') :
            $errors['agent_itinerary_margin_required'] = true;
        endif;
        if (empty($agent_margin_gst_type)) :
            $errors['agent_margin_gst_type_required'] = true;
        endif;
        if ($agent_margin_gst_percentage == '') :
            $errors['agent_margin_gst_percentage_required'] = true;
        endif;

        /* if (empty($site_logo)) :
            $errors['site_logo_upload_required'] = true;
        endif;
        if (empty($invoice_logo)) :
            $errors['invoice_logo_upload_required'] = true;
        endif;
        if (empty($_site_address)) :
            $errors['site_address_required'] = true;
        endif;
        if (empty($_terms_condition)) :
            $errors['terms_condition_required'] = true;
        endif;
        if (empty($_gst_in_number)) :
            $errors['gst_in_number_required'] = true;
        endif;
        if (empty($_invoice_pan_no)) :
            $errors['invoice_pan_no_required'] = true;
        endif;
        if (empty($_invoice_address)) :
            $errors['invoice_address_required'] = true;
        endif;
        if (empty($agent_company_name)) :
            $errors['agent_company_name_required'] = true;
        endif; */

        if (!empty($errors)) :
            // error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;

            $arrFields = array('`itinerary_margin_discount_percentage`', '`agent_margin`', '`agent_margin_gst_type`', '`agent_margin_gst_percentage`');
            $arrValues = array("$itinerary_margin_discount_percentage", "$agent_margin", "$agent_margin_gst_type", "$agent_margin_gst_percentage");
            $sqlWhere = " `agent_id` = '$agent_id'";

            if (sqlACTIONS("UPDATE", "dvi_agent", $arrFields, $arrValues, $sqlWhere)) {

                if (!empty($agent_password)) :
                    $pwd_hash = PwdHash($agent_password);
                    $usertoken = md5($agent_password);
                    $arrFields_users = array('`usertoken`', '`password`');
                    $arrValues_users = array("$usertoken", "$pwd_hash");
                    $sqlwhere_users = " `agent_id` = '$agent_id' ";
                    sqlACTIONS("UPDATE", "dvi_users", $arrFields_users, $arrValues_users, $sqlwhere_users);
                endif;

                // File upload paths
                $uploadDir = '../../uploads/agent_gallery/';

                // Handle site logo upload
                if (!empty($_FILES['site_logo_upload']['name'])) {
                    $_agent_site_logo = uploadFile('site_logo_upload', $uploadDir, $errors, 'site_logo_upload');
                } else {
                    $_agent_site_logo = $site_logo; // Use existing logo if no new file is uploaded
                }


                // Handle invoice logo upload
                if (!empty($_FILES['invoice_logo_upload']['name'])) {
                    $_agent_invoice_logo = uploadFile('invoice_logo_upload', $uploadDir, $errors, 'invoice_logo_upload');
                } else {
                    $_agent_invoice_logo = $invoice_logo; // Use existing logo if no new file is uploaded
                }


                $arrFields = ['`site_logo`', '`site_address`', '`company_name`', '`terms_condition`', '`invoice_logo`', '`invoice_gstin_no`', '`invoice_pan_no`', '`invoice_address`', '`status`'];
                $arrValues = ["$_agent_site_logo", "$_site_address", "$agent_company_name", "$_terms_condition", "$_agent_invoice_logo", "$_gst_in_number", "$_invoice_pan_no", "$_invoice_address", "1"];

                $AGENT_COUNT = get_AGENT_CONFIG_DETAILS($agent_id, 'get_agent_count');

                if ($AGENT_COUNT == 1):

                    $sqlWhere = " `agent_id` = '$agent_id'";

                    sqlACTIONS("UPDATE", "dvi_agent_configuration", $arrFields, $arrValues, $sqlWhere);

                else:

                    $arrFields = array_merge(['`agent_id`'], $arrFields);
                    $arrValues = array_merge(["$agent_id"], $arrValues);

                    sqlACTIONS("INSERT", "dvi_agent_configuration", $arrFields, $arrValues, '');
                endif;

                // UPDATE SUCCESSFUL
                $response['result'] = true;
                $response['redirect_URL'] = 'agent.php';
                $response['result_success'] = true;
            } else {
                $response['result'] = false;
                $response['result_success'] = false;
            }
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'updatestatus') :

        $errors = [];
        $response = [];

        $AGENT_ID = $_POST['AGENT_ID'];
        $STATUS_ID = $_POST['STATUS_ID'];

        //SANITIZE
        $AGENT_ID = $validation_globalclass->sanitize($AGENT_ID);
        $STATUS_ID = $validation_globalclass->sanitize($STATUS_ID);

        if ($STATUS_ID == 0) :
            $new_status = 1;
        else :
            $new_status = 0;
        endif;

        $arrFields = array('`status`');
        $arrValues = array("$new_status");
        $sqlwhere = "`agent_ID` = '$AGENT_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_agent", $arrFields, $arrValues, $sqlwhere)) :

            $arrFields_user = array('`userbanned`', '`userapproved`');

            if ($new_status == 1) :
                $user_banned = 0;
                $user_approved = 1;
            elseif ($new_status == 0) :
                $user_banned = 1;
                $user_approved = 0;
            endif;

            $arrValues = array("$user_banned", "$user_approved");

            $sqlWhere = " `agent_id` = '$AGENT_ID' ";
            $update_user_status = sqlACTIONS("UPDATE", "dvi_users", $arrFields_user, $arrValues, $sqlWhere);

            $response['result'] = true;
        else :
            $response['success'] = false;
            $response['response_error'] = true;
        endif;

        echo json_encode($response);

    //DELETE OPERATION - CONDITION BASED

    elseif ($_GET['type'] == 'delete') :

        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);



        // $select_staff_id_already_used = sqlQUERY_LABEL("SELECT COUNT(`staff_id`) AS TOTAL_USED_COUNT FROM `dvi_staff` WHERE `status` = '1' and `staff_id` = '$ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        // while ($fetch_staff_used_data = sqlFETCHARRAY_LABEL($select_staff_id_already_used)) :
        //     $TOTAL_USED_COUNT = $fetch_staff_used_data['TOTAL_USED_COUNT'];
        // endwhile;
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
                    <button type="submit" onclick="confirmAGENTDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirmdelete') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);


        $delete_AGENT = sqlQUERY_LABEL("UPDATE `dvi_agent` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `agent_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_AGENT:" . sqlERROR_LABEL());

        if ($delete_AGENT == '1') :

            $delete_USER = sqlQUERY_LABEL("UPDATE `dvi_users` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `agent_id` = '$_ID'") or die("#1-UNABLE_TO_DELETE_USER:" . sqlERROR_LABEL());

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
?>