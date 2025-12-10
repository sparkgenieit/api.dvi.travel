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
require '../../../vendor/autoload.php'; // Autoload the Composer dependencies
use Razorpay\Api\Api;

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'add') :
        $errors = [];
        $response = [];

        if (empty($_POST['cash_amount'])) :
            $errors['cash_amount_required'] = true;
        endif;
        unset($_SESSION['_agent_id']);
        unset($_SESSION['_cash_amount']);
        //SANITIZE
        $sanitize_cash_amount = $validation_globalclass->sanitize($_POST['cash_amount']);
        $AGENT_ID = $validation_globalclass->sanitize($_POST['AGENT_ID']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call	

            $response['success'] = true;
            $_SESSION['_agent_id'] = $AGENT_ID;
            $_SESSION['_cash_amount'] = $sanitize_cash_amount;
            $api = new Api(API_KEY, API_SECRET);
            $receipt_id = 'order_rcptid_' . uniqid();
            $orderData = [
                'receipt' => $receipt_id,
                'amount' => $sanitize_cash_amount * 100,
                'currency' => 'INR',
                'payment_capture' => 1
            ];
            $razorpayOrder = $api->order->create($orderData);
            $response['order_id'] = $razorpayOrder['id'];
            $response['amount'] = $razorpayOrder['amount'];
            echo json_encode($response);
        endif;
    elseif ($_GET['type'] == 'confirm_payment') :
        $razorpay_payment_id = $_POST['razorpay_payment_id'];
        $razorpay_order_id = $_POST['razorpay_order_id'];
        $razorpay_signature = $_POST['razorpay_signature'];
        $key_secret = API_SECRET;
        $data = $razorpay_order_id . "|" . $razorpay_payment_id;
        $generated_signature = hash_hmac('sha256', $data, $key_secret);

        if ($generated_signature === $razorpay_signature) {
            $AGENT_ID = $_SESSION['_agent_id'];
            $cash_amount = $_SESSION['_cash_amount'];
            $transaction_date = date('Y-m-d');
            $remarks = "Self Top Up";
            $arrFields = array('`agent_id`', '`transaction_date`', '`transaction_type`', '`transaction_amount`', '`remarks`', '`transaction_id`', '`status`');
            $arrValues = array("$AGENT_ID", "$transaction_date", 1, "$cash_amount", "$remarks", "$razorpay_payment_id",  1);

            //INSERT HOTEL CATEGORY INFO
            if (sqlACTIONS("INSERT", "dvi_cash_wallet", $arrFields, $arrValues, '')) :
                $get_total_cash_wallet = getAGENT_details($AGENT_ID, '', 'get_total_agent_cash_wallet');
                $total_cash_wallet = $cash_amount + $get_total_cash_wallet;

                $agent_arrFields = array('`total_cash_wallet`');
                $agent_arrValues = array("$total_cash_wallet");
                $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
                sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                //SUCCESS
                $response['free_result'] = true;
            else :
                $response['free_result'] = false;
                $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Unable to Add Cash Wallet.!!!</div>';
            endif;
        } else {
            $response['free_result'] = false;
            $response['result_error'] = '<div class="alert alert-danger text-center" role="alert">Payment verification failed.!!!</div>';
        }

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
