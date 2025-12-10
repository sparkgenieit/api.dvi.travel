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

    $select_hotel_vehicle_type_query = sqlQUERY_LABEL("SELECT `language_id`, `language`, `status` FROM `dvi_language` WHERE `deleted` = '0' ORDER BY `language_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_vehicle_type_query)) :
        $counter++;
        $language_id = $fetch_list_data['language_id'];
        $language = $fetch_list_data['language'];
        $status = $fetch_list_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"language": "' . $language . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $language_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
