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
    if ($agent_ID) :
        $filter_by_agent_id = "AND `agent_id` = '$agent_ID'";
    else :
        $filter_by_agent_id = "";
    endif;
    echo "{";
    echo '"data":[';

    $select_list_query = sqlQUERY_LABEL("SELECT `transaction_date`, `transaction_amount`, `transaction_type`, `remarks` FROM `dvi_coupon_wallet` WHERE `deleted` = '0' {$filter_by_agent_id} ORDER BY `coupon_wallet_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_list_query)) :
        $counter++;
        $transaction_date = dateformat_datepicker($fetch_list_data['transaction_date']);
        $transaction_amount = number_format($fetch_list_data['transaction_amount'], 2);
        $transaction_type = $fetch_list_data['transaction_type'];
        $remarks = $fetch_list_data['remarks'];

        if ($transaction_type == 1) :
            $transaction_type_label = "<span class='badge bg-label-success me-1 cursor-pointer'>Credit</span>";
        else :
            $transaction_type_label = "<span class='badge bg-label-danger me-1 cursor-pointer'>Debit</span>";
        endif;

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"transaction_date": "' . $transaction_date . '",';
        $datas .= '"transaction_amount": "' . $transaction_amount . '",';
        $datas .= '"transaction_type": "' . $transaction_type_label . '",';
        $datas .= '"remarks": "' . $remarks . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
