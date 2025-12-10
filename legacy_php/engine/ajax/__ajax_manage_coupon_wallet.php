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

        if (empty($_POST['coupen_amount'])) :
            $errors['coupen_amount_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter GST Title !!!</div>';
        elseif (empty($_POST['coupen_remarks'])) :
            $errors['coupen_remarks_required'] = '<div class="alert alert-left alert-warning  mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter GST !!!</div>';
        endif;
        //SANITIZE
        $sanitize_coupen_amount = $validation_globalclass->sanitize($_POST['coupen_amount']);
        $sanitize_coupen_remarks = $validation_globalclass->sanitize($_POST['coupen_remarks']);
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
            $arrValues = array("$AGENT_ID", "$transaction_date", 1, "$sanitize_coupen_amount", "$sanitize_coupen_remarks", 1);

            //INSERT HOTEL CATEGORY INFO
            if (sqlACTIONS("INSERT", "dvi_coupon_wallet", $arrFields, $arrValues, '')) :
                $get_total_coupon_wallet = getAGENT_details($AGENT_ID, '', 'get_total_agent_coupon_wallet');
                $total_coupon_wallet = $sanitize_coupen_amount + $get_total_coupon_wallet;

                $agent_arrFields = array('`total_coupon_wallet`');
                $agent_arrValues = array("$total_coupon_wallet");
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
