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

    $select_hotel_room_pricebook_query = sqlQUERY_LABEL("SELECT `activity_price_book_id`, `hotspot_id`, `activity_id`, `nationality`, `price_type`, `year`, `month`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status` FROM `dvi_activity_pricebook` WHERE `deleted` = '0' And `status`='1' ") or
        die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

    $datas = '';
    $counter = 0;

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_room_pricebook_query)) :
        $counter++;
        $activity_price_book_id = $fetch_list_data['activity_price_book_id'];
        $hotspot_id = $fetch_list_data['hotspot_id'];
        $activity_id = $fetch_list_data['activity_id'];
        $nationality = $fetch_list_data['nationality'];
        $price_type = $fetch_list_data['price_type'];
        $year = $fetch_list_data['year'];
        $month = $fetch_list_data['month'];

        $day_data = [];
        for ($day_count = 1; $day_count <= 31; $day_count++) : $day_variable = 'day_' . $day_count;
            $day_data[$day_variable] = $fetch_list_data[$day_variable];
        endfor;
        $datas .= json_encode(array_merge([
            'count' =>
            $counter,
            'activity_price_book_id' => $activity_price_book_id,
            'hotspot_id' => $hotspot_id,
            'activity_id' => $activity_id,
            'nationality' => $nationality,
            'price_type' => $price_type,
            'year' => $year,
            'month' => $month
        ], $day_data)) . ',';

    endwhile; //end of while loop

    $data_formatted = rtrim($datas, ',');
    echo $data_formatted;
    echo "]}";

endif;
