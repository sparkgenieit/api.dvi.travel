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

    $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `generated_quote_code`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `itinerary_preference`, `preferred_room_count`, `total_extra_bed`, `vehicle_type`,  `status`, `deleted` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' ORDER BY `itinerary_plan_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $generated_quote_code = $fetch_list_data['generated_quote_code'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
        $no_of_days = $fetch_list_data['no_of_days'];
        $no_of_nights = $fetch_list_data['no_of_nights'];
        $no_of_routes = $fetch_list_data['no_of_routes'];
        $expecting_budget = $fetch_list_data['expecting_budget'];
        $trip_start_date_and_time = $fetch_list_data["trip_start_date_and_time"];
        $trip_end_date_and_time = $fetch_list_data["trip_end_date_and_time"];
        $date_effective_from = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_start_date_and_time']));
        $date_effective_to = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_end_date_and_time']));
        $total_adult = $fetch_list_data["total_adult"];
        $total_children = $fetch_list_data["total_children"];
        $total_infants = $fetch_list_data["total_infants"];
        $total_members = 'Adult-' . $total_adult . "</br>" . "Children-" . $total_children . "</br>" . "Infants-"  . $total_infants;

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",';
        $datas .= '"generated_quote_code": "' . $generated_quote_code . '",';
        $datas .= '"arrival_location": "' . $arrival_location . '",';
        $datas .= '"departure_location": "' . $departure_location . '",';
        $datas .= '"no_of_days_and_nights": "' . $no_of_days . "&" . $no_of_nights . '",';
        $datas .= '"no_of_routes": "' . $no_of_routes . '",';
        $datas .= '"expecting_budget": "' . $expecting_budget . '",';
        $datas .= '"trip_start_date_and_time": "' . $date_effective_from . '",';
        $datas .= '"trip_end_date_and_time": "' . $date_effective_to . '",';
        $datas .= '"no_of_person": "' . $total_members . '",';
        $datas .= '"modify": "' . $itinerary_plan_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
