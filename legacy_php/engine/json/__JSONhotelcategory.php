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

    $select_hotelCATEGORYLIST_query = sqlQUERY_LABEL("SELECT `hotel_category_id`, `hotel_category_title`, `hotel_category_code`, `status` FROM `dvi_hotel_category` WHERE `deleted` = '0' ORDER BY `hotel_category_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotelCATEGORYLIST_query)) :
        $counter++;
        $hotel_category_id = $fetch_list_data['hotel_category_id'];
        $hotel_category_title = $fetch_list_data['hotel_category_title'];
        $hotel_category_code = $fetch_list_data['hotel_category_code'];
        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"hotel_category_title": "' . $hotel_category_title . '",';
        $datas .= '"hotel_category_code": "' . $hotel_category_code . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"status_label": "' . $status_label . '",';
        $datas .= '"modify": "' . $hotel_category_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
