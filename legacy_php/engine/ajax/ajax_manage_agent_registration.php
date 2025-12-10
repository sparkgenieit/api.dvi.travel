<?php

include_once('../../jackus.php');
include_once('../../smtp_functions.php');
require '../../../vendor/autoload.php'; // Autoload the Composer dependencies
// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
use Razorpay\Api\Api;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

  if ($_GET['type'] == 'register') :

    $errors = [];
    $response = [];

    $agent_first_name = trim($_POST['agent_first_name']);
    $agent_last_name = trim($_POST['agent_last_name']);
    $country_name = 101;
    $state_name = trim($_POST['state_name']);
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

    $agent_email_address = trim($_POST['agent_email_address']);
    $agent_mobile_number = trim($_POST['agent_mobile_number']);
    $agent_alternative_mobile_number = $_POST['agent_alternative_mobile_number'];
    $agent_gst_number = $_POST['agent_gst_number'];
    $agent_subscription_plan = $_POST['agent_subscription_plan'];
    $get_agent_referral_number = $_POST['referral_number'];
    $site_logo = trim($_POST['agent_company_logo']);
    $agent_company_name = trim($_POST['agent_company_name']);

    // Handle file upload
    $upload_dir = '../../uploads/agent_doc/';

    if (isset($_FILES['agent_gst_file_attachement']) && $_FILES['agent_gst_file_attachement']['error'] == UPLOAD_ERR_OK) {
      $file_tmp_name = $_FILES['agent_gst_file_attachement']['tmp_name'];
      $file_name = basename($_FILES['agent_gst_file_attachement']['name']);
      $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
      $file_mime = mime_content_type($file_tmp_name);

      if (strtolower($file_ext) !== 'pdf' || $file_mime !== 'application/pdf') {
        $errors['agent_gst_file_attachement_invalid'] = true;
      } else {
        $unique_file_name = uniqid() . '.' . $file_ext;
        $upload_file_path = $upload_dir . $unique_file_name;

        if (move_uploaded_file($file_tmp_name, $upload_file_path)) {
          $agent_gst_file_attachement = $unique_file_name;
        } else {
          $errors['agent_gst_file_attachement_failed'] = true;
        }
      }
    }


    // File upload paths
    $uploadDir = '../../uploads/agent_gallery/';

    // Handle site logo upload
    if (!empty($_FILES['agent_company_logo']['name'])) {
      $_agent_site_logo = uploadFile('agent_company_logo', $uploadDir, $errors, 'agent_company_logo');
    } else {
      $errors['agent_company_logo_required'] = true;
    }

    if (empty($_POST['agent_first_name'])) :
      $errors['agent_first_name_required'] = true;
    elseif (empty($_POST['agent_last_name'])) :
      $errors['agent_last_name_required'] = true;
    elseif (empty($_POST['agent_email_address'])) :
      $errors['agent_email_address_required'] = true;
    elseif (empty($_POST['agent_mobile_number'])) :
      $errors['agent_mobile_number_required'] = true;
    elseif (empty($_POST['agent_subscription_plan'])) :
      $errors['agent_subscription_plan_required'] = true;
    elseif (empty($_POST['agent_company_name'])) :
      $errors['agent_company_name_required'] = true;
    elseif (empty($country_name)) :
      $errors['country_name_required'] = true;
    elseif (empty($_POST['state_name'])) :
      $errors['state_name_required'] = true;
    elseif (empty($_POST['city_name'])) :
      $errors['city_name_required'] = true;
    endif;

    $account_email_id_already_exist = CHECK_USERNAME($agent_email_address, 'useremail', '');
    $account_mobile_no_already_exist = CHECK_USERNAME($agent_mobile_number, 'username', '');

    if ($account_email_id_already_exist > 0) :
      //NO RECORDS FOUND AGAINST EMAIL AND MOBILE
      $errors['agent_email_address_already_exist'] = true;
    endif;

    if ($account_mobile_no_already_exist > 0) :
      //NO RECORDS FOUND AGAINST EMAIL AND MOBILE
      $errors['agent_mobile_no_already_exist'] = true;
    endif;

    unset($_SESSION['_agent_subscription_plan']);
    unset($_SESSION['_agent_subscription_amount']);
    unset($_SESSION['_agent_details']);
    unset($_SESSION['_agent_referral_number']);

    if (!empty($errors)) :
      //error call
      $response['success'] = false;
      $response['errors'] = $errors;
    else :
      $response['success'] = true;
      $secretKey  = SECRET_KEY_CAPTCHA;
      $token      = $_POST["g-token"];
      $ip         = $_SERVER['REMOTE_ADDR'];

      /* ======================= POST METHOD =====================*/
      $url = "https://www.google.com/recaptcha/api/siteverify";
      $data = array('secret' => SECRET_KEY_CAPTCHA, 'response' => $token, 'remoteip' => $ip);

      // use key 'http' even if you send the request to https://...
      $options = array('http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
      ));
      $context = stream_context_create($options);
      $result = file_get_contents($url, false, $context);
      $recaptcha_response = json_decode($result);

      if ($recaptcha_response->success) :
        //get subscription details
        $subscription_details_query = "SELECT `agent_subscription_plan_ID`, `agent_subscription_plan_title`, `itinerary_allowed`, `subscription_type`, `subscription_amount`, `joining_bonus`, `admin_count`, `staff_count`, `additional_charge_for_per_staff`, `per_itinerary_cost`, `validity_in_days`, `subscription_notes`, `status` FROM `dvi_agent_subscription_plan` WHERE `agent_subscription_plan_ID` = '$agent_subscription_plan' AND `deleted` = '0'";

        $select_agent_subscribed_details = sqlQUERY_LABEL($subscription_details_query) or die("#1-UNABLE_TO_getSUBSCRIPTION_REGISTRATION_DETAILS:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_agent_subscribed_details) > 0) :
          while ($fetch_data = sqlFETCHARRAY_LABEL($select_agent_subscribed_details)) :
            $_get_agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
            $_get_agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
            $_get_itinerary_allowed = $fetch_data['itinerary_allowed'];
            $_get_subscription_type = $fetch_data['subscription_type'];
            $_get_subscription_amount = $fetch_data['subscription_amount'];
            $_get_joining_bonus = $fetch_data['joining_bonus'];
            $_get_admin_count = $fetch_data['admin_count'];
            $_get_staff_count = $fetch_data['staff_count'];
            $_get_additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];
            $_get_per_itinerary_cost = $fetch_data['per_itinerary_cost'];
            $_get_validity_in_days = $fetch_data['validity_in_days'];
            $_get_subscription_notes = $fetch_data['subscription_notes'];
            $_get_validity_start = date('Y-m-d H:i:s'); // Current timestamp
            $_get_validity_end = date('Y-m-d H:i:s', strtotime("+$_get_validity_in_days days")); // End date based on validity
          endwhile;
          if (
            $_get_subscription_amount == 0 && $_get_subscription_type != 1
          ) :
            $agent_ip_address = getUserIpAddr();
            $agent_ref_no = generateReferenceNumber();
            $arrFields = array('`subscription_plan_id`', '`agent_name`', '`agent_lastname`', '`agent_primary_mobile_number`', '`agent_alternative_mobile_number`', '`agent_email_id`', '`agent_country`', '`agent_state`', '`agent_city`', '`agent_gst_number`', '`agent_gst_attachment`', '`agent_ip_address`', '`agent_ref_no`', '`status`');
            $arrValues = array("$agent_subscription_plan", "$agent_first_name", "$agent_last_name", "$agent_mobile_number", "$agent_alternative_mobile_number", "$agent_email_address", "$country_name", "$state_name", "$city_name", "$agent_gst_number", "$agent_gst_file_attachement", "$agent_ip_address", "$agent_ref_no", "1");

            if ($get_agent_referral_number) :
              $_get_agent_referral_number = getAGENT_details($get_agent_referral_number, '', 'check_agent_referral_number');
              $get_agent_id = getAGENT_details($get_agent_referral_number, '', 'get_agent_id_from_referral_number');
              $_get_referral_bonus = getGLOBALSETTING('agent_referral_bonus_credit');
            endif;

            if ($_get_agent_referral_number > 0 && $_get_agent_referral_number == 1) :
              $add_arrField = array('`sponsor_id`');
              $add_arrValue = array("$get_agent_id");
              $arrFields = array_merge($arrFields, $add_arrField);
              $arrValues = array_merge($arrValues, $add_arrValue);
            else :
              $arrFields = $arrFields;
              $arrValues = $arrValues;
            endif;

            if (sqlACTIONS("INSERT", "dvi_agent", $arrFields, $arrValues, '')) {
              $inserted_agent_ID = sqlINSERTID_LABEL();
              $get_agent_name = getAGENT_details($inserted_agent_ID, '', 'label');

              $agentconfig_arrFields = array('agent_ID', 'site_logo', 'company_name');
              $agentconfig_arrValues = array("$inserted_agent_ID", "$_agent_site_logo", "$agent_company_name");

              if (sqlACTIONS("INSERT", "dvi_agent_configuration", $agentconfig_arrFields, $agentconfig_arrValues, '')) :
              endif;

              // Insert subscribed details for the agent
              $subscribed_details_arrFields = array('agent_ID', 'subscription_plan_ID', 'subscription_plan_title', 'itinerary_allowed', 'subscription_type', 'subscription_amount', 'joining_bonus', 'admin_count', 'staff_count', 'additional_charge_for_per_staff', 'per_itinerary_cost', 'validity_start', 'validity_end', 'subscription_notes', 'subscription_payment_status', 'subscription_status', 'status');
              $subscribed_details_arrValues = array("$inserted_agent_ID", "$_get_agent_subscription_plan_ID", "$_get_agent_subscription_plan_title", "$_get_itinerary_allowed", "$_get_subscription_type", "$_get_subscription_amount", "$_get_joining_bonus", "$_get_admin_count", "$_get_staff_count", "$_get_additional_charge_for_per_staff", "$_get_per_itinerary_cost", "$_get_validity_start", "$_get_validity_end", "$_get_subscription_notes", "0", "0", "1");

              if (sqlACTIONS("INSERT", "dvi_agent_subscribed_plans", $subscribed_details_arrFields, $subscribed_details_arrValues, '')) {

                $transaction_date = date('Y-m-d');

                $coupon_wallet_joining_bonus_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
                $coupon_wallet_joining_bonus_arrValues = array("$inserted_agent_ID", "$transaction_date", "$_get_joining_bonus", "1", "Agent Free Subscription Joining Bonus", "1");

                if (sqlACTIONS("INSERT", "dvi_coupon_wallet", $coupon_wallet_joining_bonus_arrFields, $coupon_wallet_joining_bonus_arrValues, '')) :

                  $get_total_coupon_wallet = getAGENT_details($inserted_agent_ID, '', 'get_total_agent_coupon_wallet');
                  $total_coupon_wallet = $_get_joining_bonus + $get_total_coupon_wallet;

                  $agent_arrFields = array('`total_coupon_wallet`');
                  $agent_arrValues = array("$total_coupon_wallet");
                  $sqlWhere = " `agent_ID` = '$inserted_agent_ID' ";
                  sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);

                  if ($_get_agent_referral_number > 0 && $_get_agent_referral_number == 1) :

                    $coupon_wallet_referral_bonus_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');

                    $coupon_wallet_referral_bonus_arrValues = array("$get_agent_id", "$transaction_date", "$_get_referral_bonus", "1", "Agent Referral Bonus Credit from Agent Id: $get_agent_name", "1");

                    if (sqlACTIONS("INSERT", "dvi_coupon_wallet", $coupon_wallet_referral_bonus_arrFields, $coupon_wallet_referral_bonus_arrValues, '')) :
                      $get_total_coupon_wallet = getAGENT_details($get_agent_id, '', 'get_total_agent_coupon_wallet');
                      $total_coupon_wallet = $_get_referral_bonus + $get_total_coupon_wallet;

                      $agent_arrFields = array('`total_coupon_wallet`');
                      $agent_arrValues = array("$total_coupon_wallet");
                      $sqlWhere = " `agent_ID` = '$get_agent_id' ";
                      sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                    endif;

                  endif;
                endif;

                // Create login for the agent
                $current_DATE = date('Y');
                $_agent_username = $agent_mobile_number;
                $_agent_password = $_agent_username . '@' . $current_DATE;

                $pwd_hash = PwdHash($_agent_password);
                $usertoken = md5($_agent_password);

                $arrFields_users = array('agent_id', 'usertoken', 'username', 'useremail', 'password', 'roleID', 'userapproved', 'createdby', 'status');
                $arrValues_users = array("$inserted_agent_ID", "$usertoken", "$_agent_username", "$agent_email_address", "$pwd_hash", "4", "0", "$logged_user_id", "1");

                if (sqlACTIONS("INSERT", "dvi_users", $arrFields_users, $arrValues_users, '')) {
                  $inserted_user_ID = sqlINSERTID_LABEL();
                  $sanitize_update_pwd_email = trim($agent_email_address);

                  $filter_update_pwd_email = filter_var($sanitize_update_pwd_email, FILTER_SANITIZE_EMAIL);
                  $filter_update_pwd_email = filter_var($sanitize_update_pwd_email, FILTER_VALIDATE_EMAIL);
                  $encoded_update_pwd_email = Encryption::Encode($filter_update_pwd_email, SECRET_KEY);

                  $get_mktime_string_format = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
                  $expiry_date = date("Y-m-d H:i:s", $get_mktime_string_format);
                  $string = $forgot_pwd_email;
                  $key = md5($string . microtime(true));
                  $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
                  $keyhash = $key . $addKey;
                  $today_DATE = date('Y-m-d');

                  $today_reset_password_generated_count = sqlQUERY_LABEL("SELECT `pwd_reset_ID` FROM `dvi_pwd_activate_log` where DATE(`createdon`) = '$today_DATE' and `deleted` = '0' and `email_ID` = '$encoded_update_pwd_email'") or die("#1-today_reset_password_generated_count" . sqlERROR_LABEL());
                  $get_today_reset_pwd_num_rows = sqlNUMOFROW_LABEL($today_reset_password_generated_count);

                  $update_existing_generated_reset_key = sqlQUERY_LABEL("UPDATE `dvi_pwd_activate_log` SET `status` = '1' WHERE `status` = '0' and `deleted` = '0' and `email_ID` = '$encoded_update_pwd_email'") or die("#1-UPDATE_UNUSED_GENERATED_KEY" . sqlERROR_LABEL());

                  $arrFields = array('`email_ID`', '`userID`', '`agent_ID`', '`reset_key`', '`expiry_date`', '`createdby`', '`status`');
                  $arrValues = array("$filter_update_pwd_email", "$inserted_user_ID", "$inserted_agent_ID", "$keyhash", "$expiry_date", "$inserted_user_ID", "0");
                  sqlACTIONS("INSERT", "dvi_pwd_activate_log", $arrFields, $arrValues, '');
                  $plan = "free_subscription";
                  // Set global variables
                  global $_get_agent_subscription_plan_ID, $inserted_agent_ID, $plan;

                  // Assign values to global variables
                  $_SESSION['global_sid'] = $_get_agent_subscription_plan_ID;
                  $_SESSION['global_agent_id'] = $inserted_agent_ID;
                  $_SESSION['global_plan'] = $plan;

                  // Include the email notification script
                  include('ajax_agent_subscription_confirmation_email_notification.php');

                  // Unset the global variables
                  unset($_SESSION['global_sid']);
                  unset($_SESSION['global_agent_id']);
                  unset($_SESSION['global_plan']);

                  $activation_link = PUBLICPATH . 'confirmpassword.php?key=' . $keyhash . '&email=' . $encoded_update_pwd_email . '&action=activate';

                  $current_YEAR = date('Y');
                  $company_name = getGLOBALSETTING('company_name');
                  $company_email_id = getGLOBALSETTING('company_email_id');
                  $company_contact_no = getGLOBALSETTING('company_contact_no');
                  $email_activation_title = "Activate Account";
                  $site_title = getGLOBALSETTING('site_title');
                  $subject_title = "Activate Your Account";
                  $site_logo = BASEPATH . 'assets/img/' . getGLOBALSETTING('company_logo');
                  $custome_message = "Thank you for registering. To activate your account, please click the link below. This link is valid for 24 hours. During activation, you will be prompted to update your password.";
                  $footer_content = " Copyright &copy; $current_YEAR | $company_name";

                  $message_template = '<!DOCTYPE html>
<html
  dir="ltr"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
  lang="en"
>
  <head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>' . $email_activation_title . '</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Imprima&display=swap"
      rel="stylesheet"
    />
    <style type="text/css">
      body {
        font-family: "DM Sans", sans-serif;
      }
      #outlook a {
        padding: 0;
      }
      .es-button {
        mso-style-priority: 100 !important;
        text-decoration: none !important;
      }
      a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
      }
      .es-desk-hidden {
        display: none;
        float: left;
        overflow: hidden;
        width: 0;
        max-height: 0;
        line-height: 0;
        mso-hide: all;
      }
      @media only screen and (max-width: 600px) {
        p,
        ul li,
        ol li,
        a {
          line-height: 150% !important;
        }
        h1,
        h2,
        h3,
        h1 a,
        h2 a,
        h3 a {
          line-height: 120%;
        }
        h1 {
          font-size: 30px !important;
          text-align: left;
        }
        h2 {
          font-size: 24px !important;
          text-align: left;
        }
        h3 {
          font-size: 20px !important;
          text-align: left;
        }
        .es-header-body h1 a,
        .es-content-body h1 a,
        .es-footer-body h1 a {
          font-size: 30px !important;
          text-align: left;
        }
        .es-header-body h2 a,
        .es-content-body h2 a,
        .es-footer-body h2 a {
          font-size: 24px !important;
          text-align: left;
        }
        .es-header-body h3 a,
        .es-content-body h3 a,
        .es-footer-body h3 a {
          font-size: 20px !important;
          text-align: left;
        }
        .es-menu td a {
          font-size: 14px !important;
        }
        .es-header-body p,
        .es-header-body ul li,
        .es-header-body ol li,
        .es-header-body a {
          font-size: 14px !important;
        }
        .es-content-body p,
        .es-content-body ul li,
        .es-content-body ol li,
        .es-content-body a {
          font-size: 14px !important;
        }
        .es-footer-body p,
        .es-footer-body ul li,
        .es-footer-body ol li,
        .es-footer-body a {
          font-size: 14px !important;
        }
        .es-infoblock p,
        .es-infoblock ul li,
        .es-infoblock ol li,
        .es-infoblock a {
          font-size: 12px !important;
        }
        *[class="gmail-fix"] {
          display: none !important;
        }
        .es-m-txt-c,
        .es-m-txt-c h1,
        .es-m-txt-c h2,
        .es-m-txt-c h3 {
          text-align: center !important;
        }
        .es-m-txt-r,
        .es-m-txt-r h1,
        .es-m-txt-r h2,
        .es-m-txt-r h3 {
          text-align: right !important;
        }
        .es-m-txt-l,
        .es-m-txt-l h1,
        .es-m-txt-l h2,
        .es-m-txt-l h3 {
          text-align: left !important;
        }
        .es-m-txt-r img,
        .es-m-txt-c img,
        .es-m-txt-l img {
          display: inline !important;
        }
        .es-button-border {
          display: block !important;
        }
        a.es-button,
        button.es-button {
          font-size: 18px !important;
          display: block !important;
          border-right-width: 0px !important;
          border-left-width: 0px !important;
          border-top-width: 15px !important;
          border-bottom-width: 15px !important;
        }
        .es-adaptive table,
        .es-left,
        .es-right {
          width: 100% !important;
        }
        .es-content table,
        .es-header table,
        .es-footer table,
        .es-content,
        .es-footer,
        .es-header {
          width: 100% !important;
          max-width: 600px !important;
        }
        .es-adapt-td {
          display: block !important;
          width: 100% !important;
        }
        .adapt-img {
          width: 100% !important;
          height: auto !important;
        }
        .es-m-p0 {
          padding: 0px !important;
        }
        .es-m-p0r {
          padding-right: 0px !important;
        }
        .es-m-p0l {
          padding-left: 0px !important;
        }
        .es-m-p0t {
          padding-top: 0px !important;
        }
        .es-m-p0b {
          padding-bottom: 0 !important;
        }
        .es-m-p20b {
          padding-bottom: 20px !important;
        }
        .es-mobile-hidden,
        .es-hidden {
          display: none !important;
        }
        tr.es-desk-hidden,
        td.es-desk-hidden,
        table.es-desk-hidden {
          width: auto !important;
          overflow: visible !important;
          float: none !important;
          max-height: inherit !important;
          line-height: inherit !important;
        }
        tr.es-desk-hidden {
          display: table-row !important;
        }
        table.es-desk-hidden {
          display: table !important;
        }
        td.es-desk-menu-hidden {
          display: table-cell !important;
        }
        .es-menu td {
          width: 1% !important;
        }
        table.es-table-not-adapt,
        .esd-block-html table {
          width: auto !important;
        }
        table.es-social {
          display: inline-block !important;
        }
        table.es-social td {
          display: inline-block !important;
        }
        .es-desk-hidden {
          display: table-row !important;
          width: auto !important;
          overflow: visible !important;
          max-height: inherit !important;
        }
      }
      @media screen and (max-width: 384px) {
        .mail-message-content {
          width: 414px !important;
        }
      }
      :root {
        --line-border-fill: #3498db;
        --line-border-empty: #e0e0e0;
      }
      .container {
        text-align: center;
      }

      .progress-container {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
        max-width: 100%;
        width: 380px;
      }

      .progress-container::before {
        content: ""; /* Mandatory with ::before */
        background-color: #e0e0e0;
        position: absolute;
        top: 70%;
        left: 0;
        transform: translateY(-50%);
        height: 2px;
        width: 100%;
        z-index: 1;
      }

      .progress {
        background-color: var(--line-border-fill);
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        height: 4px;
        width: 0%;
        z-index: -1;
        transition: 0.4s ease;
      }

      .label {
        font-size: 12px;
        color: #999;
        margin-bottom: 5px;
      }

      .circle {
        position: relative; /* Ensure proper positioning of the label */
        background-color: #fff;
        color: #999;
        border-radius: 50%;
        height: 45px; /* Adjust size as needed */
        width: 45px; /* Adjust size as needed */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--line-border-empty);
        transition: 0.4s ease;
        z-index: 2;
        margin-top: 30px; /* Adjust margin between circles */
      }

      .circle img {
        max-width: calc(100% - 20px); /* Adjust the space around the image */
        max-height: calc(100% - 20px); /* Adjust the space around the image */
      }

      .circle .label {
        position: absolute;
        top: -28px; /* Adjust label position above the circle */
        white-space: nowrap;
      }

      .circle.active {
        border-color: var(--line-border-fill);
      }
    </style>
  </head>
  <body
    style="
      width: 100%;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    "
  >
    <div
      dir="ltr"
      class="es-wrapper-color"
      lang="en"
      style="background-color: #ffffff"
    >
      <table
        class="es-wrapper"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        role="none"
        style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #ffffff;
        "
      >
        <tr>
          <td valign="top" style="padding: 0; margin: 0">
            <table
              cellpadding="0"
              cellspacing="0"
              class="es-footer"
              align="center"
              role="none"
              style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              "
            >
              <tr>
                <td align="center" style="padding: 0; margin: 0">
                  <table
                    bgcolor="#bcb8b1"
                    class="es-footer-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    role="none"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    "
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f6f8fa"
                        style="
                          padding: 0;
                          margin: 0;
                          padding-top: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          background-color: #f6f8fa;
                        "
                      >
                        <table
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          role="none"
                          style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          "
                        >
                          <tr>
                            <td
                              align="center"
                              valign="top"
                              style="padding: 0; margin: 0; width: 540px"
                            >
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                role="presentation"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                "
                              >
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      font-size: 0px;
                                    "
                                  >
                                    <a
                                      target="_blank"
                                      href="' . BASEPATH . '"
                                      style="
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        text-decoration: underline;
                                        color: #2d3142;
                                        font-size: 14px;
                                      "
                                      ><img
                                       src=' . $site_logo . '
                                        alt="Logo"
                                        style="
                                          display: block;
                                          border: 0;
                                          outline: none;
                                          text-decoration: none;
                                          -ms-interpolation-mode: bicubic;
                                        "
                                        height="70"
                                        title="Logo"
                                    /></a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table
              cellpadding="0"
              cellspacing="0"
              class="es-content"
              align="center"
              role="none"
              style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              "
            >
              <tr>
                <td align="center" style="padding: 0; margin: 0">
                  <table
                    bgcolor="#f6f8fa"
                    class="es-content-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #f6f8fa;
                      border-radius: 20px 20px 0px 0px;
                      width: 600px;
                    "
                    role="none"
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f6f8fa"
                        style="
                          padding: 0;
                          margin: 0;
                          padding-top: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          background-color: #f6f8fa;
                        "
                      >
                        <table
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          role="none"
                          style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          "
                        >
                          <tr>
                            <td
                              align="center"
                              valign="top"
                              style="padding: 0; margin: 0; width: 560px"
                            >
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                bgcolor="#fff"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  background-color: #fff;
                                  border-radius: 10px;
                                  border: 1px solid rgba(135, 70, 180, 0.1);
                                "
                                role="presentation"
                              >
                                <tr>
                                  <td
                                    align="center"
                                    logo
                                    class="es-m-txt-c"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 40px;
                                      font-size: 0px;
                                    "
                                  >
                                    <h3
                                      style="
                                        margin: 0;
                                        mso-line-height-rule: exactly;
                                        font-size: 24px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #2d3142;
                                      "
                                    >
                                    ' . $email_activation_title . '
                                    </h3>
                                  </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    logo
                                    class="es-m-txt-c"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 20px;
                                      font-size: 0px;
                                    "
                                  >
                                    <img
                                      src="' . PUBLICPATH . '/assets/img/email-with-tick.png"
                                      alt="Logo"
                                      style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      "
                                      title="Logo"
                                      height="100"
                                    />
                                  </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 20px;
                                      margin: 0;
                                      padding-top: 0px;
                                    "
                                  >
                                    <p
                                      style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        line-height: 12px;
                                        color: #2d3142;
                                        font-size: 14px;
                                        font-weight: bold;
                                        padding-right: 20px;
                                        padding-bottom: 30px;
                                        padding-left: 20px;
                                      "
                                    >
                                      Hello ' . $get_agent_name . ',
                                    </p>
                                    <p
                                      style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        line-height: 18px;
                                        color: #2d3142;
                                        font-size: 14px;
                                        padding-right: 20px;
                                        padding-left: 20px;
                                      "
                                    >' . $custome_message . '
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 10px;
                                    "
                                  >
                                                                       <!--[if mso]>
<table role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td style="padding: 0 10px;">
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" 
                         href="' . $activation_link . '" 
                         style="height:40px;v-text-anchor:middle;width:200px;" 
                         arcsize="10%" strokecolor="#001255" fillcolor="#001255">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:sans-serif;font-size:14px;white-space:nowrap;">
                    ' . $subject_title . '
                </center>
            </v:roundrect>
        </td>
    </tr>
</table>
<![endif]-->
<![if !mso]>
<a href="' . $activation_link . '" 
   target="_blank" 
   style="background:#001255;border-radius:7px;color:#ffffff;display:inline-block;font-size:14px;padding:10px 20px;text-decoration:none;text-align:center;white-space:nowrap;">
    ' . $subject_title . '
</a>
<![endif]>
                             </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 5px;
                                      color: #2d3142;
                                    "
                                  >
                                    <p style="margin: 0;">[or]</p>
                                  </td>
                                </tr>
                                 <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 5px;
                                      color: #2d3142;
                                    "
                                  >
                                  <p style="margin: 0;">Click on the link below</p>
                                  </td>
                                </tr>
                                 <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 40px;
                                    "
                                  >
                                <a href="' . $activation_link . '">' . $activation_link . '</a>
                                   </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table
              cellpadding="0"
              cellspacing="0"
              class="es-content"
              align="center"
              role="none"
              style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              "
            >
              <tr>
                <td align="center" style="padding: 0; margin: 0">
                  <table
                    bgcolor="#efefef"
                    class="es-content-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    role="none"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #efefef;
                      width: 600px;
                    "
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f6f8fa"
                        style="
                          margin: 0;
                          padding: 20px;
                          background-color: #f6f8fa;
                        "
                      >
                        <table
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          role="none"
                          style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          "
                        >
                          <tr>
                            <td
                              align="left"
                              style="padding: 0; margin: 0; width: 560px"
                            >
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                role="none"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                "
                              >
                                <tr>
                                  <td
                                    align="left"
                                    style="padding: 0; margin: 0; width: 560px"
                                  >
                                    <table
                                      cellpadding="0"
                                      cellspacing="0"
                                      width="100%"
                                      role="presentation"
                                      style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      "
                                    >
                                      <tr>
                                        <td
                                          align="center"
                                          style="padding: 0; margin: 0"
                                        >
                                          <p
                                            style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #2d3142;
                                              font-size: 12px;
                                            "
                                          >
                                            ' . $company_name . '<br />+91
                                             ' . $company_contact_no . ',   ' . $company_email_id . '<br /> ' . getGLOBALSETTING('company_address') . ' –  ' . getGLOBALSETTING('company_pincode') . '.
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td
                                          align="center"
                                          style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 20px;
                                          "
                                        >
                                          <p
                                            style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #2d3142;
                                              font-size: 12px;
                                            "
                                          >
                                            <a
                                              target="_blank"
                                              href=""
                                              style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "
                                            ></a
                                            >' . $footer_content . '<a
                                              target="_blank"
                                              href=""
                                              style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "
                                            ></a>
                                          </p>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>';

                  $subject = "$site_title - $subject_title";
                  $send_from = "$SMTP_EMAIL_SEND_FROM";
                  $admin_email_id = getGLOBALSETTING('company_email_id');
                  $cc_email_id = getGLOBALSETTING('cc_email_id');
                  $to = [$sanitize_update_pwd_email, $admin_email_id];
                  $cc = [$cc_email_id];
                  $Bcc = [$bcc_emailid];
                  $sender_name = "$SMTP_EMAIL_SEND_NAME";
                  SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);

                  // Insert payment details
                  $response['free_result'] = true;
                  $encoded_agent_ID = Encryption::Encode($inserted_agent_ID, SECRET_KEY);
                  $encoded_subscription_plan_ID = Encryption::Encode($_get_agent_subscription_plan_ID, SECRET_KEY);
                  $response['returnURL'] = PUBLICPATH . 'paymentsuccessful.php?id=' . $encoded_agent_ID . '&subscription_plan=' . $encoded_subscription_plan_ID . '&plan=activate_subscription';
                } else {
                  $response['free_result'] = false;
                  $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Failed to create agent login.!!!</div>';
                }
              } else {
                $response['free_result'] = false;
                $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable Create Subscription!!!</div>';
              }
            } else {
              $response['free_result'] = false;
              $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Failed to register agent.!!!</div>';
            }
          else :
            $_SESSION['_agent_subscription_plan'] = $_get_agent_subscription_plan_ID;
            $_SESSION['_agent_subscription_amount'] = $_get_subscription_amount;
            $_SESSION['_agent_details'] = [
              'agent_first_name' => $agent_first_name,
              'agent_last_name' => $agent_last_name,
              'agent_email_address' => $agent_email_address,
              'agent_mobile_number' => $agent_mobile_number,
              'agent_alternative_mobile_number' => $agent_alternative_mobile_number,
              'agent_gst_number' => $agent_gst_number,
              'agent_gst_file_attachement' => $agent_gst_file_attachement,
              'country_name' => $country_name,
              'state_name' => $state_name,
              'city_name' => $city_name
            ];
            $_SESSION['_agent_referral_number'] = $get_agent_referral_number;
            $api = new Api(API_KEY, API_SECRET);
            $receipt_id = 'order_rcptid_' . uniqid();
            $orderData = [
              'receipt' => $receipt_id,
              'amount' => $_get_subscription_amount * 100,
              'currency' => 'INR',
              'payment_capture' => 1
            ];
            $razorpayOrder = $api->order->create($orderData);
            $response['order_id'] = $razorpayOrder['id'];
            $response['amount'] = $razorpayOrder['amount'];
            $response['paid_result'] = true;
          endif;
        else :
          $response['free_result'] = false;
          $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Invalid subscription plan.!!!</div>';
        endif;
      else :
        $response['free_result'] = false;
        $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Please Refresh, Captcha Validation Timeout [or] Failed !!!</div>';
      endif;
    endif;

    echo json_encode($response);

  elseif ($_GET['type'] == 'confirm_payment') :

    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_signature = $_POST['razorpay_signature'];

    $key_secret = API_SECRET;
    $data = $razorpay_order_id . "|" . $razorpay_payment_id;
    $generated_signature = hash_hmac('sha256', $data, $key_secret);

    if ($generated_signature === $razorpay_signature) {
      // Handle successful payment
      $subscription_plan_id = $_SESSION['_agent_subscription_plan'];
      $subscription_plan_amount = $_SESSION['_agent_subscription_amount'];
      $agent_details = $_SESSION['_agent_details'];

      // Get subscription details
      $subscription_details_query = "SELECT * FROM `dvi_agent_subscription_plan` WHERE `agent_subscription_plan_ID` = '$subscription_plan_id' AND `deleted` = '0'";
      $select_agent_subscribed_details = sqlQUERY_LABEL($subscription_details_query) or die("#1-UNABLE_TO_getSUBSCRIPTION_REGISTRATION_DETAILS:" . sqlERROR_LABEL());
      if (sqlNUMOFROW_LABEL($select_agent_subscribed_details) > 0) {
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_agent_subscribed_details)) {
          $_get_agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
          $_get_agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
          $_get_itinerary_allowed = $fetch_data['itinerary_allowed'];
          $_get_subscription_type = $fetch_data['subscription_type'];
          $_get_subscription_amount = $fetch_data['subscription_amount'];
          $_get_joining_bonus = $fetch_data['joining_bonus'];
          $_get_admin_count = $fetch_data['admin_count'];
          $_get_staff_count = $fetch_data['staff_count'];
          $_get_additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];
          $_get_per_itinerary_cost = $fetch_data['per_itinerary_cost'];
          $_get_validity_in_days = $fetch_data['validity_in_days'];
          $_get_subscription_notes = $fetch_data['subscription_notes'];
          $_get_validity_start = date('Y-m-d H:i:s');
          $_get_validity_end = date('Y-m-d H:i:s', strtotime("+$_get_validity_in_days days"));
        }

        $agent_ip_address = getUserIpAddr();
        $agent_ref_no = generateReferenceNumber();
        // Insert agent details
        $arrFields = array('`subscription_plan_id`', '`agent_name`', '`agent_lastname`', '`agent_primary_mobile_number`', '`agent_alternative_mobile_number`', '`agent_email_id`', '`agent_country`', '`agent_state`', '`agent_city`', '`agent_gst_number`', '`agent_gst_attachment`', '`agent_ip_address`', '`agent_ref_no`', '`status`');
        $arrValues = array("$subscription_plan_id", "{$agent_details['agent_first_name']}", "{$agent_details['agent_last_name']}", "{$agent_details['agent_mobile_number']}", "{$agent_details['agent_alternative_mobile_number']}", "{$agent_details['agent_email_address']}", "{$agent_details['country_name']}", "{$agent_details['state_name']}", "{$agent_details['city_name']}", "{$agent_details['agent_gst_number']}", "{$agent_details['agent_gst_file_attachement']}", "$agent_ip_address", "$agent_ref_no", "1");

        $get_agent_referral_number = $_SESSION['_agent_referral_number'];

        if ($get_agent_referral_number) :
          $_get_agent_referral_number = getAGENT_details($get_agent_referral_number, '', 'check_agent_referral_number');
          $get_agent_id = getAGENT_details($get_agent_referral_number, '', 'get_agent_id_from_referral_number');
          $_get_referral_bonus = getGLOBALSETTING('agent_referral_bonus_credit');
        endif;

        if ($_get_agent_referral_number > 0 && $_get_agent_referral_number == 1) :
          $add_arrField = array('`sponsor_id`');
          $add_arrValue = array("$get_agent_id");
          $arrFields = array_merge($arrFields, $add_arrField);
          $arrValues = array_merge($arrValues, $add_arrValue);
        else :
          $arrFields = $arrFields;
          $arrValues = $arrValues;
        endif;

        if (sqlACTIONS("INSERT", "dvi_agent", $arrFields, $arrValues, '')) {
          $inserted_agent_ID = sqlINSERTID_LABEL();
          $get_agent_name = getAGENT_details($inserted_agent_ID, '', 'label');
          // Insert subscribed details for the agent
          $subscribed_details_arrFields = array('agent_ID', 'subscription_plan_ID', 'subscription_plan_title', 'itinerary_allowed', 'subscription_type', 'subscription_amount', 'joining_bonus', 'admin_count', 'staff_count', 'additional_charge_for_per_staff', 'per_itinerary_cost', 'validity_start', 'validity_end', 'subscription_notes', 'subscription_payment_status', 'transaction_id', 'subscription_status', 'status');
          $subscribed_details_arrValues = array("$inserted_agent_ID", "$_get_agent_subscription_plan_ID", "$_get_agent_subscription_plan_title", "$_get_itinerary_allowed", "$_get_subscription_type", "$_get_subscription_amount", "$_get_joining_bonus", "$_get_admin_count", "$_get_staff_count", "$_get_additional_charge_for_per_staff", "$_get_per_itinerary_cost", "$_get_validity_start", "$_get_validity_end", "$_get_subscription_notes", "1", "$razorpay_payment_id", "1", "1");

          if (sqlACTIONS("INSERT", "dvi_agent_subscribed_plans", $subscribed_details_arrFields, $subscribed_details_arrValues, '')) {

            $transaction_date = date('Y-m-d');

            $coupon_wallet_joining_bonus_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
            $coupon_wallet_joining_bonus_arrValues = array("$inserted_agent_ID", "$transaction_date", "$_get_joining_bonus", "1", "Agent Paid Subscription Joining Bonus", "1");

            if (sqlACTIONS("INSERT", "dvi_coupon_wallet", $coupon_wallet_joining_bonus_arrFields, $coupon_wallet_joining_bonus_arrValues, '')) :

              $get_total_coupon_wallet = getAGENT_details($inserted_agent_ID, '', 'get_total_agent_coupon_wallet');
              $total_coupon_wallet = $_get_joining_bonus + $get_total_coupon_wallet;

              $agent_arrFields = array('`total_coupon_wallet`');
              $agent_arrValues = array("$total_coupon_wallet");
              $sqlWhere = " `agent_ID` = '$inserted_agent_ID' ";
              sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);

              if ($_get_agent_referral_number > 0 && $_get_agent_referral_number == 1) :

                $coupon_wallet_referral_bonus_arrFields = array('agent_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');

                $coupon_wallet_referral_bonus_arrValues = array("$get_agent_id", "$transaction_date", "$_get_referral_bonus", "1", "Agent Referral Bonus Credit from Agent Id: $get_agent_name", "1");

                if (sqlACTIONS("INSERT", "dvi_coupon_wallet", $coupon_wallet_referral_bonus_arrFields, $coupon_wallet_referral_bonus_arrValues, '')) :
                  $get_total_coupon_wallet = getAGENT_details($get_agent_id, '', 'get_total_agent_coupon_wallet');
                  $total_coupon_wallet = $_get_referral_bonus + $get_total_coupon_wallet;

                  $agent_arrFields = array('`total_coupon_wallet`');
                  $agent_arrValues = array("$total_coupon_wallet");
                  $sqlWhere = " `agent_ID` = '$get_agent_id' ";
                  sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                endif;

              endif;
            endif;

            $cash_wallet_arrFields = array('agent_id', 'transaction_id', 'transaction_date', 'transaction_amount', 'transaction_type', 'remarks', 'status');
            $cash_wallet_arrValues = array("$inserted_agent_ID", "$razorpay_payment_id", "$transaction_date", "$_get_subscription_amount", "1", "Agent Paid Subscription Transaction", "1");

            if (sqlACTIONS("INSERT", "dvi_cash_wallet", $cash_wallet_arrFields, $cash_wallet_arrValues, '')) :
              $get_total_cash_wallet = getAGENT_details($inserted_agent_ID, '', 'get_total_agent_cash_wallet');
              $total_cash_wallet = $_get_subscription_amount + $get_total_cash_wallet;

              $agent_arrFields = array('`total_cash_wallet`');
              $agent_arrValues = array("$total_cash_wallet");
              $sqlWhere = " `agent_ID` = '$inserted_agent_ID' ";
              sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
            endif;

            // Create login for the agent
            $current_DATE = date('Y');
            $email_parts = explode('@', $agent_details['agent_email_address']);
            $_agent_username = $agent_details['agent_mobile_number'];
            $_agent_password = $_agent_username . '@' . $current_DATE;

            $pwd_hash = PwdHash($_agent_password);
            $usertoken = md5($_agent_password);

            $arrFields_users = array('agent_id', 'usertoken', 'username', 'useremail', 'password', 'roleID', 'userapproved', 'createdby', 'status');
            $arrValues_users = array("$inserted_agent_ID", "$usertoken", "$_agent_username", $agent_details['agent_email_address'], "$pwd_hash", "4", "0", "$logged_user_id", 1);

            if (sqlACTIONS("INSERT", "dvi_users", $arrFields_users, $arrValues_users, '')) {
              $inserted_user_ID = sqlINSERTID_LABEL();
              $get_agent_email_address = $agent_details['agent_email_address'];
              $sanitize_update_pwd_email = trim($get_agent_email_address);

              $filter_update_pwd_email = filter_var($sanitize_update_pwd_email, FILTER_SANITIZE_EMAIL);
              $filter_update_pwd_email = filter_var($sanitize_update_pwd_email, FILTER_VALIDATE_EMAIL);
              $encoded_update_pwd_email = Encryption::Encode($filter_update_pwd_email, SECRET_KEY);

              $get_mktime_string_format = mktime(date("H"), date("i"), date("s"), date("m"), date("d") + 1, date("Y"));
              $expiry_date = date("Y-m-d H:i:s", $get_mktime_string_format);
              $string = $forgot_pwd_email;
              $key = md5($string . microtime(true));
              $addKey = substr(md5(uniqid(rand(), 1)), 3, 10);
              $keyhash = $key . $addKey;
              $today_DATE = date('Y-m-d');

              $today_reset_password_generated_count = sqlQUERY_LABEL("SELECT `pwd_reset_ID` FROM `dvi_pwd_activate_log` where DATE(`createdon`) = '$today_DATE' and `deleted` = '0' and `email_ID` = '$encoded_update_pwd_email'") or die("#1-today_reset_password_generated_count" . sqlERROR_LABEL());
              $get_today_reset_pwd_num_rows = sqlNUMOFROW_LABEL($today_reset_password_generated_count);

              $update_existing_generated_reset_key = sqlQUERY_LABEL("UPDATE `dvi_pwd_activate_log` SET `status` = '1' WHERE `status` = '0' and `deleted` = '0' and `email_ID` = '$encoded_update_pwd_email'") or die("#1-UPDATE_UNUSED_GENERATED_KEY" . sqlERROR_LABEL());

              $arrFields = array('`email_ID`', '`userID`', '`agent_ID`', '`reset_key`', '`expiry_date`', '`createdby`', '`status`');
              $arrValues = array("$filter_update_pwd_email", "$inserted_user_ID", "$inserted_agent_ID", "$keyhash", "$expiry_date", "$inserted_user_ID", "0");
              sqlACTIONS("INSERT", "dvi_pwd_activate_log", $arrFields, $arrValues, '');

              $plan = "paid_subscription";
              // Set global variables
              global $_get_agent_subscription_plan_ID, $inserted_agent_ID, $plan;

              // Assign values to global variables
              $_SESSION['global_sid'] = $_get_agent_subscription_plan_ID;
              $_SESSION['global_agent_id'] = $inserted_agent_ID;
              $_SESSION['global_plan'] = $plan;

              // Include the email notification script
              include('ajax_agent_subscription_confirmation_email_notification.php');

              // Unset the global variables
              unset($_SESSION['global_sid']);
              unset($_SESSION['global_agent_id']);
              unset($_SESSION['global_plan']);

              $activation_link = PUBLICPATH . 'confirmpassword.php?key=' . $keyhash . '&email=' . $encoded_update_pwd_email . '&action=activate';

              $site_title = getGLOBALSETTING('site_title');
              $current_YEAR = date('Y');
              $company_name = getGLOBALSETTING('company_name');
              $company_email_id = getGLOBALSETTING('company_email_id');
              $company_contact_no = getGLOBALSETTING('company_contact_no');
              $email_activation_title = "Activate Your Account";
              $site_logo = BASEPATH . 'assets/img/' . getGLOBALSETTING('company_logo');
              $subject_title =
                "Activate Your Account";
              $custome_message = "Thank you for registering. To activate your account, please click the link below. This link is valid for 24 hours. During activation, you will be prompted to update your password.";
              $footer_content = " Copyright &copy; $current_YEAR | $company_name";

              $message_template = '<!DOCTYPE html>
<html
  dir="ltr"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
  lang="en"
>
  <head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>' . $email_activation_title . '</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Imprima&display=swap"
      rel="stylesheet"
    />
    <style type="text/css">
      body {
        font-family: "DM Sans", sans-serif;
      }
      #outlook a {
        padding: 0;
      }
      .es-button {
        mso-style-priority: 100 !important;
        text-decoration: none !important;
      }
      a[x-apple-data-detectors] {
        color: inherit !important;
        text-decoration: none !important;
        font-size: inherit !important;
        font-family: inherit !important;
        font-weight: inherit !important;
        line-height: inherit !important;
      }
      .es-desk-hidden {
        display: none;
        float: left;
        overflow: hidden;
        width: 0;
        max-height: 0;
        line-height: 0;
        mso-hide: all;
      }
      @media only screen and (max-width: 600px) {
        p,
        ul li,
        ol li,
        a {
          line-height: 150% !important;
        }
        h1,
        h2,
        h3,
        h1 a,
        h2 a,
        h3 a {
          line-height: 120%;
        }
        h1 {
          font-size: 30px !important;
          text-align: left;
        }
        h2 {
          font-size: 24px !important;
          text-align: left;
        }
        h3 {
          font-size: 20px !important;
          text-align: left;
        }
        .es-header-body h1 a,
        .es-content-body h1 a,
        .es-footer-body h1 a {
          font-size: 30px !important;
          text-align: left;
        }
        .es-header-body h2 a,
        .es-content-body h2 a,
        .es-footer-body h2 a {
          font-size: 24px !important;
          text-align: left;
        }
        .es-header-body h3 a,
        .es-content-body h3 a,
        .es-footer-body h3 a {
          font-size: 20px !important;
          text-align: left;
        }
        .es-menu td a {
          font-size: 14px !important;
        }
        .es-header-body p,
        .es-header-body ul li,
        .es-header-body ol li,
        .es-header-body a {
          font-size: 14px !important;
        }
        .es-content-body p,
        .es-content-body ul li,
        .es-content-body ol li,
        .es-content-body a {
          font-size: 14px !important;
        }
        .es-footer-body p,
        .es-footer-body ul li,
        .es-footer-body ol li,
        .es-footer-body a {
          font-size: 14px !important;
        }
        .es-infoblock p,
        .es-infoblock ul li,
        .es-infoblock ol li,
        .es-infoblock a {
          font-size: 12px !important;
        }
        *[class="gmail-fix"] {
          display: none !important;
        }
        .es-m-txt-c,
        .es-m-txt-c h1,
        .es-m-txt-c h2,
        .es-m-txt-c h3 {
          text-align: center !important;
        }
        .es-m-txt-r,
        .es-m-txt-r h1,
        .es-m-txt-r h2,
        .es-m-txt-r h3 {
          text-align: right !important;
        }
        .es-m-txt-l,
        .es-m-txt-l h1,
        .es-m-txt-l h2,
        .es-m-txt-l h3 {
          text-align: left !important;
        }
        .es-m-txt-r img,
        .es-m-txt-c img,
        .es-m-txt-l img {
          display: inline !important;
        }
        .es-button-border {
          display: block !important;
        }
        a.es-button,
        button.es-button {
          font-size: 18px !important;
          display: block !important;
          border-right-width: 0px !important;
          border-left-width: 0px !important;
          border-top-width: 15px !important;
          border-bottom-width: 15px !important;
        }
        .es-adaptive table,
        .es-left,
        .es-right {
          width: 100% !important;
        }
        .es-content table,
        .es-header table,
        .es-footer table,
        .es-content,
        .es-footer,
        .es-header {
          width: 100% !important;
          max-width: 600px !important;
        }
        .es-adapt-td {
          display: block !important;
          width: 100% !important;
        }
        .adapt-img {
          width: 100% !important;
          height: auto !important;
        }
        .es-m-p0 {
          padding: 0px !important;
        }
        .es-m-p0r {
          padding-right: 0px !important;
        }
        .es-m-p0l {
          padding-left: 0px !important;
        }
        .es-m-p0t {
          padding-top: 0px !important;
        }
        .es-m-p0b {
          padding-bottom: 0 !important;
        }
        .es-m-p20b {
          padding-bottom: 20px !important;
        }
        .es-mobile-hidden,
        .es-hidden {
          display: none !important;
        }
        tr.es-desk-hidden,
        td.es-desk-hidden,
        table.es-desk-hidden {
          width: auto !important;
          overflow: visible !important;
          float: none !important;
          max-height: inherit !important;
          line-height: inherit !important;
        }
        tr.es-desk-hidden {
          display: table-row !important;
        }
        table.es-desk-hidden {
          display: table !important;
        }
        td.es-desk-menu-hidden {
          display: table-cell !important;
        }
        .es-menu td {
          width: 1% !important;
        }
        table.es-table-not-adapt,
        .esd-block-html table {
          width: auto !important;
        }
        table.es-social {
          display: inline-block !important;
        }
        table.es-social td {
          display: inline-block !important;
        }
        .es-desk-hidden {
          display: table-row !important;
          width: auto !important;
          overflow: visible !important;
          max-height: inherit !important;
        }
      }
      @media screen and (max-width: 384px) {
        .mail-message-content {
          width: 414px !important;
        }
      }
      :root {
        --line-border-fill: #3498db;
        --line-border-empty: #e0e0e0;
      }
      .container {
        text-align: center;
      }

      .progress-container {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
        max-width: 100%;
        width: 380px;
      }

      .progress-container::before {
        content: ""; /* Mandatory with ::before */
        background-color: #e0e0e0;
        position: absolute;
        top: 70%;
        left: 0;
        transform: translateY(-50%);
        height: 2px;
        width: 100%;
        z-index: 1;
      }

      .progress {
        background-color: var(--line-border-fill);
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        height: 4px;
        width: 0%;
        z-index: -1;
        transition: 0.4s ease;
      }

      .label {
        font-size: 12px;
        color: #999;
        margin-bottom: 5px;
      }

      .circle {
        position: relative; /* Ensure proper positioning of the label */
        background-color: #fff;
        color: #999;
        border-radius: 50%;
        height: 45px; /* Adjust size as needed */
        width: 45px; /* Adjust size as needed */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 3px solid var(--line-border-empty);
        transition: 0.4s ease;
        z-index: 2;
        margin-top: 30px; /* Adjust margin between circles */
      }

      .circle img {
        max-width: calc(100% - 20px); /* Adjust the space around the image */
        max-height: calc(100% - 20px); /* Adjust the space around the image */
      }

      .circle .label {
        position: absolute;
        top: -28px; /* Adjust label position above the circle */
        white-space: nowrap;
      }

      .circle.active {
        border-color: var(--line-border-fill);
      }
    </style>
  </head>
  <body
    style="
      width: 100%;
      -webkit-text-size-adjust: 100%;
      -ms-text-size-adjust: 100%;
      padding: 0;
      margin: 0;
    "
  >
    <div
      dir="ltr"
      class="es-wrapper-color"
      lang="en"
      style="background-color: #ffffff"
    >
      <table
        class="es-wrapper"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        role="none"
        style="
          mso-table-lspace: 0pt;
          mso-table-rspace: 0pt;
          border-collapse: collapse;
          border-spacing: 0px;
          padding: 0;
          margin: 0;
          width: 100%;
          height: 100%;
          background-repeat: repeat;
          background-position: center top;
          background-color: #ffffff;
        "
      >
        <tr>
          <td valign="top" style="padding: 0; margin: 0">
            <table
              cellpadding="0"
              cellspacing="0"
              class="es-footer"
              align="center"
              role="none"
              style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
                background-color: transparent;
                background-repeat: repeat;
                background-position: center top;
              "
            >
              <tr>
                <td align="center" style="padding: 0; margin: 0">
                  <table
                    bgcolor="#bcb8b1"
                    class="es-footer-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    role="none"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #ffffff;
                      width: 600px;
                    "
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f6f8fa"
                        style="
                          padding: 0;
                          margin: 0;
                          padding-top: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          background-color: #f6f8fa;
                        "
                      >
                        <table
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          role="none"
                          style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          "
                        >
                          <tr>
                            <td
                              align="center"
                              valign="top"
                              style="padding: 0; margin: 0; width: 540px"
                            >
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                role="presentation"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                "
                              >
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      font-size: 0px;
                                    "
                                  >
                                    <a
                                      target="_blank"
                                      href="' . BASEPATH . '"
                                      style="
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        text-decoration: underline;
                                        color: #2d3142;
                                        font-size: 14px;
                                      "
                                      ><img
                                        src=' . $site_logo . '
                                        alt="Logo"
                                        style="
                                          display: block;
                                          border: 0;
                                          outline: none;
                                          text-decoration: none;
                                          -ms-interpolation-mode: bicubic;
                                        "
                                        height="70"
                                        title="Logo"
                                    /></a>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table
              cellpadding="0"
              cellspacing="0"
              class="es-content"
              align="center"
              role="none"
              style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              "
            >
              <tr>
                <td align="center" style="padding: 0; margin: 0">
                  <table
                    bgcolor="#f6f8fa"
                    class="es-content-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #f6f8fa;
                      border-radius: 20px 20px 0px 0px;
                      width: 600px;
                    "
                    role="none"
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f6f8fa"
                        style="
                          padding: 0;
                          margin: 0;
                          padding-top: 20px;
                          padding-left: 20px;
                          padding-right: 20px;
                          background-color: #f6f8fa;
                        "
                      >
                        <table
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          role="none"
                          style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          "
                        >
                          <tr>
                            <td
                              align="center"
                              valign="top"
                              style="padding: 0; margin: 0; width: 560px"
                            >
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                bgcolor="#fff"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: separate;
                                  border-spacing: 0px;
                                  background-color: #fff;
                                  border-radius: 10px;
                                  border: 1px solid rgba(135, 70, 180, 0.1);
                                "
                                role="presentation"
                              >
                                <tr>
                                  <td
                                    align="center"
                                    logo
                                    class="es-m-txt-c"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 40px;
                                      font-size: 0px;
                                    "
                                  >
                                    <h3
                                      style="
                                        margin: 0;
                                        mso-line-height-rule: exactly;
                                        font-size: 24px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #2d3142;
                                      "
                                    >
                                    ' . $email_activation_title . '
                                    </h3>
                                  </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    logo
                                    class="es-m-txt-c"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 10px;
                                      padding-bottom: 20px;
                                      font-size: 0px;
                                    "
                                  >
                                    <img
                                      src="' . PUBLICPATH . '/assets/img/email-with-tick.png"
                                      alt="Logo"
                                      style="
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                        font-size: 12px;
                                      "
                                      title="Logo"
                                      height="100"
                                    />
                                  </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 20px;
                                      margin: 0;
                                      padding-top: 0px;
                                    "
                                  >
                                    <p
                                      style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        line-height: 12px;
                                        color: #2d3142;
                                        font-size: 14px;
                                        font-weight: bold;
                                        padding-right: 20px;
                                        padding-bottom: 30px;
                                        padding-left: 20px;
                                      "
                                    >
                                      Hello ' . $get_agent_name . ',
                                    </p>
                                    <p
                                      style="
                                        margin: 0;
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                        line-height: 18px;
                                        color: #2d3142;
                                        font-size: 14px;
                                        padding-right: 20px;
                                        padding-left: 20px;
                                      "
                                    >' . $custome_message . '
                                    </p>
                                  </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 10px;
                                    "
                                  >
                                                                       <!--[if mso]>
<table role="presentation" cellspacing="0" cellpadding="0">
    <tr>
        <td style="padding: 0 10px;">
            <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" 
                         href="' . $activation_link . '" 
                         style="height:40px;v-text-anchor:middle;width:200px;" 
                         arcsize="10%" strokecolor="#001255" fillcolor="#001255">
                <w:anchorlock/>
                <center style="color:#ffffff;font-family:sans-serif;font-size:14px;white-space:nowrap;">
                    ' . $subject_title . '
                </center>
            </v:roundrect>
        </td>
    </tr>
</table>
<![endif]-->
<![if !mso]>
<a href="' . $activation_link . '" 
   target="_blank" 
   style="background:#001255;border-radius:7px;color:#ffffff;display:inline-block;font-size:14px;padding:10px 20px;text-decoration:none;text-align:center;white-space:nowrap;">
    ' . $subject_title . '
</a>
<![endif]>
                                 </td>
                                </tr>
                                <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 5px;
                                      color: #2d3142;
                                    "
                                  >
                                    <p style="margin: 0;">[or]</p>
                                  </td>
                                </tr>
                                 <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-top: 5px;
                                      padding-bottom: 5px;
                                      color: #2d3142;
                                    "
                                  >
                                  <p style="margin: 0;">Click on the link below</p>
                                  </td>
                                </tr>
                                 <tr>
                                  <td
                                    align="center"
                                    style="
                                      padding: 0;
                                      margin: 0;
                                      padding-bottom: 40px;
                                    "
                                  >
                                <a href="' . $activation_link . '">' . $activation_link . '</a>
                                   </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table
              cellpadding="0"
              cellspacing="0"
              class="es-content"
              align="center"
              role="none"
              style="
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
                border-collapse: collapse;
                border-spacing: 0px;
                table-layout: fixed !important;
                width: 100%;
              "
            >
              <tr>
                <td align="center" style="padding: 0; margin: 0">
                  <table
                    bgcolor="#efefef"
                    class="es-content-body"
                    align="center"
                    cellpadding="0"
                    cellspacing="0"
                    role="none"
                    style="
                      mso-table-lspace: 0pt;
                      mso-table-rspace: 0pt;
                      border-collapse: collapse;
                      border-spacing: 0px;
                      background-color: #efefef;
                      width: 600px;
                    "
                  >
                    <tr>
                      <td
                        align="left"
                        bgcolor="#f6f8fa"
                        style="
                          margin: 0;
                          padding: 20px;
                          background-color: #f6f8fa;
                        "
                      >
                        <table
                          cellpadding="0"
                          cellspacing="0"
                          width="100%"
                          role="none"
                          style="
                            mso-table-lspace: 0pt;
                            mso-table-rspace: 0pt;
                            border-collapse: collapse;
                            border-spacing: 0px;
                          "
                        >
                          <tr>
                            <td
                              align="left"
                              style="padding: 0; margin: 0; width: 560px"
                            >
                              <table
                                cellpadding="0"
                                cellspacing="0"
                                width="100%"
                                role="none"
                                style="
                                  mso-table-lspace: 0pt;
                                  mso-table-rspace: 0pt;
                                  border-collapse: collapse;
                                  border-spacing: 0px;
                                "
                              >
                                <tr>
                                  <td
                                    align="left"
                                    style="padding: 0; margin: 0; width: 560px"
                                  >
                                    <table
                                      cellpadding="0"
                                      cellspacing="0"
                                      width="100%"
                                      role="presentation"
                                      style="
                                        mso-table-lspace: 0pt;
                                        mso-table-rspace: 0pt;
                                        border-collapse: collapse;
                                        border-spacing: 0px;
                                      "
                                    >
                                      <tr>
                                        <td
                                          align="center"
                                          style="padding: 0; margin: 0"
                                        >
                                          <p
                                            style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #2d3142;
                                              font-size: 12px;
                                            "
                                          >
                                             ' . $company_name . '<br />+91
                                             ' . $company_contact_no . ',   ' . $company_email_id . '<br /> ' . getGLOBALSETTING('company_address') . ' –  ' . getGLOBALSETTING('company_pincode') . '.
                                          </p>
                                        </td>
                                      </tr>
                                      <tr>
                                        <td
                                          align="center"
                                          style="
                                            padding: 0;
                                            margin: 0;
                                            padding-top: 20px;
                                          "
                                        >
                                          <p
                                            style="
                                              margin: 0;
                                              -webkit-text-size-adjust: none;
                                              -ms-text-size-adjust: none;
                                              mso-line-height-rule: exactly;
                                              line-height: 18px;
                                              color: #2d3142;
                                              font-size: 12px;
                                            "
                                          >
                                            <a
                                              target="_blank"
                                              href=""
                                              style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "
                                            ></a
                                            >' . $footer_content . '<a
                                              target="_blank"
                                              href=""
                                              style="
                                                -webkit-text-size-adjust: none;
                                                -ms-text-size-adjust: none;
                                                mso-line-height-rule: exactly;
                                                text-decoration: underline;
                                                color: #2d3142;
                                                font-size: 12px;
                                              "
                                            ></a>
                                          </p>
                                        </td>
                                      </tr>
                                    </table>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </body>
</html>';

              $subject = "$site_title - $subject_title";
              $send_from = "$SMTP_EMAIL_SEND_FROM";
              $admin_email_id = getGLOBALSETTING('company_email_id');
              $cc_email_id = getGLOBALSETTING('cc_email_id');
              $to = [$sanitize_update_pwd_email, $admin_email_id];
              $cc = [$cc_email_id];
              $Bcc = [$bcc_emailid];
              $sender_name = "$SMTP_EMAIL_SEND_NAME";
              SMTP_EMAIL_CONFIG($to, $cc, $reply_to, $send_from, $Bcc, $sender_name, $subject, $message_template);

              // Insert payment details
              $response['free_result'] = true;
              $encoded_agent_ID = Encryption::Encode($inserted_agent_ID, SECRET_KEY);
              $encoded_subscription_plan_ID = Encryption::Encode($_get_agent_subscription_plan_ID, SECRET_KEY);
              $response['returnURL'] = PUBLICPATH . 'paymentsuccessful.php?id=' . $encoded_agent_ID . '&subscription_plan=' . $encoded_subscription_plan_ID . '&plan=activate_subscription';
            } else {
              $response['free_result'] = false;
              $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Failed to create agent login.!!!</div>';
            }
          } else {
            $response['free_result'] = false;
            $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable Create Subscription!!!</div>';
          }
        } else {
          $response['free_result'] = false;
          $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Failed to register agent.!!!</div>';
        }
      } else {
        $response['free_result'] = false;
        $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Invalid subscription plan.!!!</div>';
      }
    } else {
      $response['free_result'] = false;
      $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Payment verification failed.!!!</div>';
    }
    echo json_encode($response);
  endif;
else :
  echo "Request Ignored !!!";
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
