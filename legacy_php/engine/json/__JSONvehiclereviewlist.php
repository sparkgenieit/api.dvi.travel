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
$vehicle_id = $_GET["id"];

    echo "{";
    echo '"data":[';

    $select_hotelREVIEW_query = sqlQUERY_LABEL("SELECT `vehicle_review_id`, `vehicle_id`, `vehicle_rating`, `vehicle_description`,`createdon` FROM `dvi_vehicle_review_details` WHERE `vehicle_id`='$vehicle_id' AND `deleted` = '0' ORDER BY `createdon` DESC") or die("#1-UNABLE_TO_COLLECT_GUIDE_REVIEW_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotelREVIEW_query)) :
        $counter++;
        $vehicle_review_id = $fetch_list_data['vehicle_review_id'];
        $vehicle_id = $fetch_list_data['vehicle_id'];
        $vehicle_rating = $fetch_list_data['vehicle_rating'];
        $vehicle_description = $fetch_list_data['vehicle_description'];
        $createdon = date('d/m/Y H:i A', strtotime($fetch_list_data['createdon']));

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vehicle_rating": "' . $vehicle_rating . '",';
        $datas .= '"vehicle_description": "' . $vehicle_description . '",';
        $datas .= '"createdon": "' . $createdon . '",';
        $datas .= '"modify": "' . $vehicle_review_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
	echo "Request Ignored !!!";
endif;
