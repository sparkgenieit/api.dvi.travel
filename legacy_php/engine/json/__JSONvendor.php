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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')): //CHECK AJAX REQUEST

    $vendor_id = $_POST['vendor_id'];

    if ($logged_user_level == 1):
        $filter_by_vendor = "";
    else:
        $filter_by_vendor = " AND `vendor_id` = '$logged_vendor_id'";
    endif;
    echo "{";
    echo '"data":[';

    $select_vendor_list_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`,`vendor_code`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_email`,`status`  FROM `dvi_vendor_details` WHERE `deleted` = '0' {$filter_by_vendor} ORDER BY `vendor_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vendor_list_query)) :
        $counter++;
        $vendor_id = $fetch_list_data['vendor_id'];
        $vendor_name = $fetch_list_data['vendor_name'];
        $vendor_code = $fetch_list_data['vendor_code'];
        $branch_count = get_branch_count($vendor_id, 'vendor_count');
        $vendor_primary_mobile_number = $fetch_list_data['vendor_primary_mobile_number'];
        $vendor_email = $fetch_list_data['vendor_email'];
        $status = $fetch_list_data['status'];


        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vendor_name": "' . $vendor_name . '",';
        $datas .= '"vendor_code": "' . $vendor_code . '",';
        $datas .= '"vendor_mobile": "' . $vendor_primary_mobile_number . '",';

        $datas .= '"branch_count": "' . $branch_count . '",';
        $datas .= '"status": "' . $status . '",';

        // $datas .= '"hotel_category": "' . $hotel_category . '",';
        $datas .= '"modify": "' . $vendor_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
