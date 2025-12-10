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
require '../../../vendor/autoload.php'; // Autoload the Composer dependencies
use Razorpay\Api\Api;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

  if ($_GET['type'] == 'add') :
    $errors = [];
    $response = [];

    if (empty($_POST['agent_subscription_plan_ID'])) :
      $errors['id_error'] = true;
    endif;
    if (empty($_POST['agent_ID'])) :
      $errors['id_error'] = true;
    endif;

    //SANITIZE
    $agent_subscription_plan_id = $validation_globalclass->sanitize($_POST['agent_subscription_plan_ID']);
    $agent_subscribed_plan_id = $validation_globalclass->sanitize($_POST['agent_subscribed_plan_ID']);
    $AGENT_ID = $validation_globalclass->sanitize($_POST['agent_ID']);


    unset($_SESSION['_agent_id']);
    unset($_SESSION['_agent_subscription_plan_id']);
    unset($_SESSION['_agent_subscribed_plan_id']);
    unset($_SESSION['_total_amount']);

    if (!empty($errors)) :
      //error call
      $response['success'] = false;
      $response['errors'] = $errors;
    else :
      //success call	

      $response['success'] = true;
      $_SESSION['_agent_subscription_plan_id'] = $agent_subscription_plan_id;
      $_SESSION['_agent_id'] = $AGENT_ID;

      $get_subscription_amount = getSUBSCRIPTION_REGISTRATION($agent_subscription_plan_id, 'subscription_amount');

      if (!empty($agent_subscribed_plan_id)) :

        $_SESSION['_agent_subscribed_plan_id'] = $agent_subscribed_plan_id;
        $additional_staff_charge = getSUBSCRIPTION_REGISTRATION($agent_subscription_plan_id, 'additional_charge_for_per_staff');
        $subscription_amount = $get_subscription_amount;
        $TOTAL_AMOUNT = $subscription_amount + $additional_staff_charge;
      else :
        $TOTAL_AMOUNT = $get_subscription_amount;
      endif;
      $_SESSION['_total_amount'] = $TOTAL_AMOUNT;
      $api = new Api(API_KEY, API_SECRET);
      $receipt_id = 'order_rcptid_' . uniqid();
      $orderData = [
        'receipt' => $receipt_id,
        'amount' => $TOTAL_AMOUNT * 100,
        'currency' => 'INR',
        'payment_capture' => 1
      ];
      $razorpayOrder = $api->order->create($orderData);
      $response['order_id'] = $razorpayOrder['id'];
      $response['amount'] = $razorpayOrder['amount'];
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
      $response['success'] = true;
      $AGENT_ID = $_SESSION['_agent_id'];
      $agent_subscription_plan_id = $_SESSION['_agent_subscription_plan_id'];
      $agent_subscribed_plan_id = $_SESSION['_agent_subscribed_plan_id'];
      $TOTAL_AMOUNT = $_SESSION['_total_amount'];
      if (!empty($agent_subscribed_plan_id)) :
        // $filter_
        $select_subscribed_plan_details = sqlQUERY_LABEL("SELECT asp.`agent_subscription_plan_ID`, asp.`agent_subscription_plan_title`, asp.`itinerary_allowed`,  asp.`subscription_type`, asp.`admin_count`, asp.`staff_count`, asp.`additional_charge_for_per_staff`, asp.`per_itinerary_cost`, asp.`validity_in_days`, asp.`subscription_notes`, asb.`agent_subscribed_plan_ID`, asb.`additional_staff_count`, asb.`additional_staff_charge` FROM `dvi_agent_subscribed_plans` asb LEFT JOIN `dvi_agent_subscription_plan` asp ON asb.`subscription_plan_ID` = asp.`agent_subscription_plan_ID` WHERE asb.`status` = 1 AND asb.`deleted` = 0 AND asp.`status` = 1 AND asp.`deleted` = 0 AND asb.`agent_ID`= $AGENT_ID AND asp.`agent_subscription_plan_ID`= $agent_subscription_plan_id AND asb.`agent_subscribed_plan_ID`= $agent_subscribed_plan_id") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
        $count_subscribed_plan_details = sqlNUMOFROW_LABEL($select_subscribed_plan_details);
        if ($count_subscribed_plan_details > 0) :
          while ($fetch_data = sqlFETCHARRAY_LABEL($select_subscribed_plan_details)) :
            $_get_agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
            $_get_agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
            $_get_itinerary_allowed = $fetch_data['itinerary_allowed'];
            $_get_subscription_type = $fetch_data['subscription_type'];
            $_get_admin_count = $fetch_data['admin_count'];
            //add staff cal
            $staff_count = $fetch_data['staff_count'];
            $additional_staff_count = $fetch_data['additional_staff_count'];
            $_get_staff_count = $staff_count + $additional_staff_count;
            $_get_additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];
            $additional_staff_charge = $fetch_data['additional_staff_charge'];
            $_get_per_itinerary_cost = $fetch_data['per_itinerary_cost'];
            $_get_validity_in_days = $fetch_data['validity_in_days'];
            $_get_subscription_notes = $fetch_data['subscription_notes'];
            $_get_validity_start = date('Y-m-d H:i:s');
            $_get_validity_end = date('Y-m-d H:i:s', strtotime("+$_get_validity_in_days days"));

          endwhile;
        else :
          $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable to get Subscribed Details!!!</div>';
        endif;
      else :
        // Get subscription details
        $subscription_details_query = "SELECT * FROM `dvi_agent_subscription_plan` WHERE `agent_subscription_plan_ID` = '$agent_subscription_plan_id' AND `deleted` = '0'AND `status` = '1'";
        $select_agent_subscribed_details = sqlQUERY_LABEL($subscription_details_query) or die("#1-UNABLE_TO_getSUBSCRIPTION_REGISTRATION_DETAILS:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_agent_subscribed_details) > 0) :
          while ($fetch_data = sqlFETCHARRAY_LABEL($select_agent_subscribed_details)) {
            $_get_agent_subscription_plan_ID = $fetch_data['agent_subscription_plan_ID'];
            $_get_agent_subscription_plan_title = $fetch_data['agent_subscription_plan_title'];
            $_get_itinerary_allowed = $fetch_data['itinerary_allowed'];
            $_get_subscription_type = $fetch_data['subscription_type'];
            $_get_admin_count = $fetch_data['admin_count'];
            $_get_staff_count = $fetch_data['staff_count'];
            $_get_additional_charge_for_per_staff = $fetch_data['additional_charge_for_per_staff'];
            $_get_per_itinerary_cost = $fetch_data['per_itinerary_cost'];
            $_get_validity_in_days = $fetch_data['validity_in_days'];
            $_get_subscription_notes = $fetch_data['subscription_notes'];
            $_get_validity_start = date('Y-m-d H:i:s');
            $_get_validity_end = date('Y-m-d H:i:s', strtotime("+$_get_validity_in_days days"));
          }
        else :
          $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable to get Subscription Details!!!</div>';
        endif;
      endif;
      if (!empty($TOTAL_AMOUNT)) :
        $TOTAL_AMOUNT = $_SESSION['_total_amount'];
      else :
        $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable to get Subscription Amount!!!</div>';
      endif;

      $subscribed_details_arrFields = array('agent_ID', 'subscription_plan_ID', 'subscription_plan_title', 'itinerary_allowed', 'subscription_type', 'subscription_amount', 'admin_count', 'staff_count', 'additional_charge_for_per_staff', 'per_itinerary_cost', 'validity_start', 'validity_end', 'subscription_notes', 'subscription_payment_status', 'transaction_id', 'subscription_status', 'status');
      $subscribed_details_arrValues = array("$AGENT_ID", "$_get_agent_subscription_plan_ID", "$_get_agent_subscription_plan_title", "$_get_itinerary_allowed", "$_get_subscription_type", "$TOTAL_AMOUNT", "$_get_admin_count", "$_get_staff_count", "$_get_additional_charge_for_per_staff", "$_get_per_itinerary_cost", "$_get_validity_start", "$_get_validity_end", "$_get_subscription_notes", "1", "$razorpay_payment_id", "1", "1");


      if (sqlACTIONS("INSERT", "dvi_agent_subscribed_plans", $subscribed_details_arrFields, $subscribed_details_arrValues, '')) {
        $transaction_date = date('Y-m-d');
        $remarks = "Agent Subscription Renewel";
        $arrFields = array('`agent_id`', '`transaction_date`', '`transaction_type`', '`transaction_amount`', '`remarks`', '`transaction_id`', '`status`');
        $arrValues = array("$AGENT_ID", "$transaction_date", 1, "$TOTAL_AMOUNT", "$remarks", "$razorpay_payment_id",  1);

        //INSERT HOTEL CATEGORY INFO
        if (sqlACTIONS("INSERT", "dvi_cash_wallet", $arrFields, $arrValues, '')) :
          $get_total_cash_wallet = getAGENT_details($AGENT_ID, '', 'get_total_agent_cash_wallet');
          $total_cash_wallet = $TOTAL_AMOUNT + $get_total_cash_wallet;

          $agent_arrFields = array('`total_cash_wallet`');
          $agent_arrValues = array("$total_cash_wallet");
          $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
          sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
          //SUCCESS
          $response['free_result'] = true;

          $plan = "renewal_subscription";
          // Set global variables
          global $_get_agent_subscription_plan_ID, $AGENT_ID, $plan;

          // Assign values to global variables
          $_SESSION['global_sid'] = $_get_agent_subscription_plan_ID;
          $_SESSION['global_agent_id'] = $AGENT_ID;
          $_SESSION['global_plan'] = $plan;

          // Include the email notification script
          include('ajax_agent_subscription_confirmation_email_notification.php');

          // Unset the global variables
          unset($_SESSION['global_sid']);
          unset($_SESSION['global_agent_id']);
          unset($_SESSION['global_plan']);

          $encoded_agent_ID = Encryption::Encode($AGENT_ID, SECRET_KEY);
          $encoded_subscription_plan_ID = Encryption::Encode($_get_agent_subscription_plan_ID, SECRET_KEY);
          $response['returnURL'] = PUBLICPATH . 'paymentsuccessful.php?id=' . $encoded_agent_ID . '&subscription_plan=' . $encoded_subscription_plan_ID . '&plan=renewel_subscription';
        else :
          $response['free_result'] = false;
          $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable to Add Cash Wallet.!!!</div>';
        endif;
      } else {
        $response['free_result'] = false;
        $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable Create Subscription!!!</div>';
      }
    } else {
      $response['success'] = false;
      $response['free_result'] = false;
      $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Payment verification failed.!!!</div>';
    }

    echo json_encode($response);
  endif;
else :
  echo "Request Ignored";
endif;
