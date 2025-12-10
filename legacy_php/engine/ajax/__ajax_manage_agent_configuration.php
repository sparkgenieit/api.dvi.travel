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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

    if ($_GET['type'] === 'agent_config') {

        $errors = [];
        $response = [];

        $_site_address = $_POST['site_address'];
        $_terms_condition = $_POST['terms_condition'];
        $_gst_in_number = trim($_POST['gst_in_number']);
        $_invoice_pan_no = trim($_POST['invoice_pan_no']);
        $agent_company_name = trim($_POST['agent_company_name']);
        $_invoice_address = $_POST['invoice_address'];

        $new_file_name = trim($_FILES['site_logo_upload']['name']);
        $invoice_file_name = trim($_FILES['invoice_logo_upload']['name']);

        $_hidden_agent_ID = trim($_POST['hidden_agent_ID']);
        $site_logo = trim($_POST['site_logo']);
        $invoice_logo = trim($_POST['invoice_logo']);

        $agent_id = $logged_agent_id;


        // if (empty($site_logo)) :
        //     $errors['site_logo_upload_required'] = true;
        // endif;
        // if (empty($_site_address)) :
        //     $errors['site_address_required'] = true;
        // endif;
        // if (empty($_terms_condition)) :
        //     $errors['terms_condition_required'] = true;
        // endif;
        // if (empty($invoice_logo)) :
        //     $errors['invoice_logo_upload_required'] = true;
        // endif;
        // if (empty($_gst_in_number)) :
        //     $errors['gst_in_number_required'] = true;
        // endif;
        // if (empty($_invoice_pan_no)) :
        //     $errors['invoice_pan_no_required'] = true;
        // endif;
        // if (empty($_invoice_address)) :
        //     $errors['invoice_address_required'] = true;
        // endif;
        // if (empty($agent_company_name)) :
        //     $errors['agent_company_name_required'] = true;
        // endif;

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

        if (!empty($errors)) {
            $response['success'] = false;
            $response['errors'] = $errors;
        } else {
            $response['success'] = true;

            $arrFields = ['`site_logo`', '`company_name`', '`site_address`', '`terms_condition`', '`invoice_logo`', '`invoice_gstin_no`', '`invoice_pan_no`', '`invoice_address`', '`status`'];
            $arrValues = ["$_agent_site_logo", "$agent_company_name",  "$_site_address", "$_terms_condition", "$_agent_invoice_logo", "$_gst_in_number", "$_invoice_pan_no", "$_invoice_address", "1"];

            $AGENT_COUNT = get_AGENT_CONFIG_DETAILS($_hidden_agent_ID, 'get_agent_count');

            if ($AGENT_COUNT == 1):

                $sqlWhere = " `agent_id` = '$_hidden_agent_ID'";

                if (sqlACTIONS("UPDATE", "dvi_agent_configuration", $arrFields, $arrValues, $sqlWhere)) {
                    $response['result'] = true;
                    $response['redirect_URL'] = 'profile.php';
                    $response['result_success'] = true;
                } else {
                    $response['result'] = false;
                    $response['result_success'] = false;
                }

            else:

                $arrFields = array_merge(['`agent_id`'], $arrFields);
                $arrValues = array_merge(["$agent_id"], $arrValues);

                if (sqlACTIONS("INSERT", "dvi_agent_configuration", $arrFields, $arrValues, '')) {
                    $response['result'] = true;
                    $response['redirect_URL'] = 'profile.php';
                    $response['result_success'] = true;
                } else {
                    $response['result'] = false;
                    $response['result_success'] = false;
                }
            endif;
        }

        echo json_encode($response);
    }
} else {
    echo "Request Ignored";
}

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
