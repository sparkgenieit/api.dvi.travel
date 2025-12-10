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
    $agent_ID = trim($_GET['agent_ID']);

    echo "{";
    echo '"data":[';

    $select_subscription_list_query = sqlQUERY_LABEL("SELECT `subscription_plan_title`, `subscription_amount`, `validity_start`, `validity_end`, `subscription_payment_status`, `transaction_id`, `subscription_status` FROM `dvi_agent_subscribed_plans` WHERE `deleted` = '0' AND `agent_ID` = '$agent_ID' ORDER BY `agent_subscribed_plan_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_subscription_list_query)) :
        $counter++;
        $subscription_plan_title = $fetch_list_data['subscription_plan_title'];
        $subscription_amount = number_format($fetch_list_data['subscription_amount'], 2);

        $validity_start = dateformat_datepicker($fetch_list_data['validity_start']);
        $validity_end = dateformat_datepicker($fetch_list_data['validity_end']);

        $subscription_payment_status = $fetch_list_data['subscription_payment_status'];
        $transaction_id = $fetch_list_data['transaction_id'];

        if ($transaction_id != NULL) :
            $transaction_id = $transaction_id;
        else :
            $transaction_id = '--';
        endif;

        if ($subscription_payment_status == 1) :
            $subscription_payment_label = "<span class='badge bg-label-success me-1 cursor-pointer'>Paid</span>";
        else :
            $subscription_payment_label = "<span class='badge bg-label-warning me-1 cursor-pointer'>Free</span>";
        endif;

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"subscription_plan_title": "' . $subscription_plan_title . '",';
        $datas .= '"subscription_amount": "' . $subscription_amount . '",';
        $datas .= '"validity_start": "' . $validity_start . '",';
        $datas .= '"validity_end": "' . $validity_end . '",';
        $datas .= '"subscription_payment_status": "' .  $subscription_payment_label . '",';
        $datas .= '"transaction_id": "' . $transaction_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
