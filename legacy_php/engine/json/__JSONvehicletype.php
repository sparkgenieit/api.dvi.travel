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

    $select_hotel_vehicle_type_query = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_type_title`, `occupancy`, `status` FROM `dvi_vehicle_type` WHERE `deleted` = '0' ORDER BY `vehicle_type_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_vehicle_type_query)) :
        $counter++;
        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
        $vehicle_type_title = $fetch_list_data['vehicle_type_title'];
        $occupancy = $fetch_list_data['occupancy'];
        $status = $fetch_list_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vehicle_type_title": "' . $vehicle_type_title . '",';
        $datas .= '"occupancy": "' . $occupancy . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $vehicle_type_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
