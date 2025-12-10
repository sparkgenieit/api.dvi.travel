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

    echo "{";
    echo '"data":[';

    $select_hotspot_query = sqlQUERY_LABEL("SELECT `activity_id`, `activity_title`, `hotspot_id`, `status` FROM `dvi_activity` WHERE `deleted` = '0' ORDER BY `hotspot_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
        $counter++;
        $activity_id = $fetch_list_data['activity_id'];
        $activity_title = $fetch_list_data['activity_title'];
        $hotspot_id = $fetch_list_data['hotspot_id'];
        $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
        $hotspot_location = getHOTSPOTDETAILS($hotspot_id, 'hotspot_location');
        $status = $fetch_list_data['status'];

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",'; //0
        $datas .= '"activity_title": "' . $activity_title . '",'; //1
        $datas .= '"hotspot_name": "' . $hotspot_name . '",'; //2
        $datas .= '"hotspot_location": "' . $hotspot_location . '",'; //3
        $datas .= '"status": "' . $status . '",'; //4
        $datas .= '"modify": "' . $activity_id . '"'; //5
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
