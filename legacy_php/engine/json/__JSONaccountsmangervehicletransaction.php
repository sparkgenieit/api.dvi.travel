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

use function PHPSTORM_META\type;

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    echo "{";
    echo '"data":[';

    $accounts_itinerary_vehicle_details_ID = $_GET['ID'];

    $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT  `transaction_amount`, `transaction_date`, `transaction_done_by`, `mode_of_pay`, `transaction_utr_no`, `transaction_attachment` FROM `dvi_accounts_itinerary_vehicle_transaction_history` WHERE `deleted` = '0' AND `accounts_itinerary_vehicle_details_ID` = '$accounts_itinerary_vehicle_details_ID' ORDER BY `accounts_itinerary_vehicle_details_ID` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
        $counter++;
        $transaction_amount = $fetch_list_data['transaction_amount'];
        $transaction_date = date('d-m-Y h:i A', strtotime($fetch_list_data['transaction_date']));
        $transaction_done_by = $fetch_list_data['transaction_done_by'];
        $mode_of_pay = $fetch_list_data['mode_of_pay'];
        $transaction_utr_no = $fetch_list_data['transaction_utr_no'];
        $transaction_attachment = $fetch_list_data['transaction_attachment'];


        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"transaction_amount": "' . general_currency_symbol . ' ' . number_format(round($transaction_amount),2) . '",';
        $datas .= '"transaction_date": "' . $transaction_date . '",';
        $datas .= '"transaction_done_by": "' . $transaction_done_by . '",';
        $datas .= '"mode_of_pay": "' . $mode_of_pay . '",';
        $datas .= '"transaction_utr_no": "' . $transaction_utr_no . '",';
        $datas .= '"transaction_attachment": "' . $transaction_attachment . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
