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

    if ($_GET['type'] == 'add') :

        $errors = [];
        $response = [];

        if (empty($_POST['cash_amount'])) :
            $errors['cash_amount_required'] = true;
        endif;
        if (empty($_POST['cash_remarks'])) :
            $errors['cash_remarks_required'] = true;
        endif;

        //SANITIZE
        $sanitize_cash_amount = $validation_globalclass->sanitize($_POST['cash_amount']);
        $sanitize_cash_remarks = $validation_globalclass->sanitize($_POST['cash_remarks']);
        $AGENT_ID = $validation_globalclass->sanitize($_POST['AGENT_ID']);

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $transaction_date = date('Y-m-d');

            $arrFields = array('`agent_id`', '`transaction_date`', '`transaction_type`', '`transaction_amount`', '`remarks`', '`status`');
            $arrValues = array("$AGENT_ID", "$transaction_date", 1, "$sanitize_cash_amount", "$sanitize_cash_remarks", 1);

            //INSERT HOTEL CATEGORY INFO
            if (sqlACTIONS("INSERT", "dvi_cash_wallet", $arrFields, $arrValues, '')) :
                $get_total_cash_wallet = getAGENT_details($AGENT_ID, '', 'get_total_agent_cash_wallet');
                $total_cash_wallet = $sanitize_cash_amount + $get_total_cash_wallet;

                $agent_arrFields = array('`total_cash_wallet`');
                $agent_arrValues = array("$total_cash_wallet");
                $sqlWhere = " `agent_ID` = '$AGENT_ID' ";
                sqlACTIONS("UPDATE", "dvi_agent", $agent_arrFields, $agent_arrValues, $sqlWhere);
                //SUCCESS
                $response['result'] = true;

            else :
                $response['result'] = false;
            endif;
        endif;

        echo json_encode($response);
    endif;
else :
    echo "Request Ignored";
endif;
