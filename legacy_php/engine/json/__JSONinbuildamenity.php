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
    
    $select_inbuild_amenity_type_query = sqlQUERY_LABEL("SELECT `inbuilt_amenity_type_id`, `inbuilt_amenity_title`, `status` FROM `dvi_inbuilt_amenities` WHERE `deleted` = '0' ORDER BY `inbuilt_amenity_type_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_inbuild_amenity_type_query)) :
        $counter++;
        $inbuilt_amenity_type_id = $fetch_list_data['inbuilt_amenity_type_id'];
        $inbuilt_amenity_title = $fetch_list_data['inbuilt_amenity_title'];
        // $inbuilt_amenity_availability = $fetch_list_data['inbuilt_amenity_availability'];
        $status = $fetch_list_data['status'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"inbuilt_amenity_title": "' . $inbuilt_amenity_title . '",';
        // $datas .= '"inbuilt_amenity_availability": "' . $inbuilt_amenity_availability . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $inbuilt_amenity_type_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
