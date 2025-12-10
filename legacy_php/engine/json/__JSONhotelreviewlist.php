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
    $hotel_id = $_GET["id"];

    echo "{";
    echo '"data":[';

    $select_hotelREVIEW_query = sqlQUERY_LABEL("SELECT `hotel_review_id`, `hotel_rating`, `hotel_description`, `createdon` FROM `dvi_hotel_review_details` WHERE `hotel_id`='$hotel_id' AND `deleted` = '0' ORDER BY `createdon` DESC") or die("#1-UNABLE_TO_COLLECT_GUIDE_REVIEW_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotelREVIEW_query)) :
        $counter++;
        $hotel_review_id = $fetch_list_data['hotel_review_id'];
        $hotel_rating = $fetch_list_data['hotel_rating'];
        $hotel_description = $fetch_list_data['hotel_description'];
        $createdon = date('d/m/Y H:i A', strtotime($fetch_list_data['createdon']));

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"hotel_rating": "' . $hotel_rating . '",';
        $datas .= '"hotel_description": "' . $hotel_description . '",';
        $datas .= '"createdon": "' . $createdon . '",';
        $datas .= '"modify": "' . $hotel_review_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
