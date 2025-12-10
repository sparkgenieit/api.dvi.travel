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

    $select_hotspotlist_query = sqlQUERY_LABEL("SELECT `hotspot_place_id`, `hotspot_place_title`, `hotspot_place_city`, `hotspot_place_latitude`, `hotspot_place_longitude`, `createdon`, `status` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `status` = '1' ORDER BY `hotspot_place_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspotlist_query)) :
        $counter++;
        $hotspot_place_id = $fetch_list_data['hotspot_place_id'];
        $hotspot_place_title = $fetch_list_data['hotspot_place_title'];     
        $hotspot_place_city = $fetch_list_data['hotspot_place_city'];
        $hotspot_place_latitude = $fetch_list_data['hotspot_place_latitude'];
        $hotspot_place_longitude = $fetch_list_data['hotspot_place_longitude'];
        $hotspot_place_latitudeandlongittude = $hotspot_place_latitude;
        $createdon = $fetch_list_data['createdon'];

        $status = $fetch_list_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"hotspot_place_title": "' . $hotspot_place_title . '",';
        $datas .= '"hotspot_place_city": "' . $hotspot_place_city . '",';
        $datas .= '"hotspot_place_latitudeandlongittude": "' . $hotspot_place_latitude . " & ". $hotspot_place_longitude . '",';
        $datas .= '"createdon": "' . $createdon . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $hotspot_place_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
