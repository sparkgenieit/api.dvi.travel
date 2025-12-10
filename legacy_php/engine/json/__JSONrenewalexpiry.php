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

    $select_vehicle_details_query = sqlQUERY_LABEL("SELECT `driver_id`,`driver_name`,`driver_license_number`,  `driver_license_expiry_date` FROM `dvi_driver_details` WHERE  `deleted` = '0' ORDER BY `vehicle_type_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
        $counter++;
        $driver_name = $fetch_list_data['driver_name'];
        $driver_license_number = $fetch_list_data['driver_license_number'];
        $driver_license_expiry_date = $fetch_list_data['driver_license_expiry_date'];
        $status = $fetch_list_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"driver_name": "' . $driver_name . '",';
        $datas .= '"driver_license_number": "' . $driver_license_number . '",';
        $datas .= '"driver_license_expiry_date": "' . $driver_license_expiry_date . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $driver_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
