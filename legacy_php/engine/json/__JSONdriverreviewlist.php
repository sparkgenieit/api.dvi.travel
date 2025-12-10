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
$driver_id = $_GET["id"];

    echo "{";
    echo '"data":[';

    $select_hotelREVIEW_query = sqlQUERY_LABEL("SELECT `driver_review_id`, `driver_rating`, `driver_description`, `createdon` FROM `dvi_driver_review_details` WHERE `driver_id`='$driver_id' AND `deleted` = '0' ORDER BY `createdon` DESC") or die("#1-UNABLE_TO_COLLECT_GUIDE_REVIEW_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotelREVIEW_query)) :
        $counter++;
        $driver_review_id = $fetch_list_data['driver_review_id'];
        $driver_rating = $fetch_list_data['driver_rating'];
        $driver_description = $fetch_list_data['driver_description'];
        $createdon = date('d/m/Y H:i A', strtotime($fetch_list_data['createdon']));

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"driver_rating": "' . $driver_rating . '",';
        $datas .= '"driver_description": "' . $driver_description . '",';
        $datas .= '"createdon": "' . $createdon . '",';
        $datas .= '"modify": "' . $driver_review_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
	echo "Request Ignored !!!";
endif;
