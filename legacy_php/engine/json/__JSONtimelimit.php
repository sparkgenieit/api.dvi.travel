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

    if ($logged_vendor_id != "" && $logged_vendor_id != "0") :
        $vendor_filter = " AND `vendor_id`='$logged_vendor_id' ";
    elseif (isset($_GET['ID'])) :
        $vendor_id = $_GET['ID'];
        $vendor_filter = " AND `vendor_id`='$vendor_id' ";
    else :
        $vendor_filter = "";
    endif;

    echo "{";
    echo '"data":[';

    $select_timeLIMITLIST_query = sqlQUERY_LABEL("SELECT `time_limit_id`, `vendor_id`, `vendor_vehicle_type_id`, `time_limit_title`, `hours_limit`, `km_limit`,`status` FROM `dvi_time_limit` WHERE `deleted` = '0' {$vendor_filter} ORDER BY `time_limit_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_timeLIMITLIST_query)) :
        $counter++;
        $time_limit_id = $fetch_list_data['time_limit_id'];
        $vendor_id = $fetch_list_data['vendor_id'];
        $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid');
        $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
        $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'label');
        $time_limit_title = $fetch_list_data['time_limit_title'];
        $hours_limit = $fetch_list_data['hours_limit'];
        $km_limit = $fetch_list_data['km_limit'];
        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vendor_id": "' . $vendor_id . '",';
        $datas .= '"vendor_name": "' . $vendor_name . '",';
        $datas .= '"vehicle_type": "' . $vehicle_type . '",';
        $datas .= '"time_limit_title": "' . $time_limit_title . '",';
        $datas .= '"hours_limit": "' . $hours_limit . '",';
        $datas .= '"km_limit": "' . $km_limit . '",';
        $datas .= '"status": "' . $status . '",';
        // $datas .= '"status_label": "' . $status_label . '",';
        $datas .= '"modify": "' . $time_limit_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
