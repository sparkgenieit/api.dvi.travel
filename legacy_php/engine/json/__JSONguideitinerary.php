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


    $select_hotel_list_query = sqlQUERY_LABEL("SELECT 
    cird.`itinerary_plan_ID`,
    cipd.`confirmed_itinerary_plan_ID`, 
    cipd.`arrival_location`, 
    cipd.`departure_location`, 
    cipd.`itinerary_quote_ID`, 
    cipd.`trip_start_date_and_time`, 
    cipd.`trip_end_date_and_time`, 
    cipd.`total_adult`, 
    cipd.`total_children`, 
    cipd.`total_infants`
FROM 
    `dvi_confirmed_itinerary_route_guide_details` cird
LEFT JOIN 
    `dvi_confirmed_itinerary_plan_details` cipd 
ON 
    cird.`itinerary_plan_ID` = cipd.`itinerary_plan_ID`
WHERE 
    cird.`deleted` = '0'
    AND cipd.`deleted` = '0'
ORDER BY 
    cipd.`confirmed_itinerary_plan_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
        $trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
        $format_trip_start_date_and_time = date('d/m/Y h:i A', strtotime($trip_start_date_and_time));
        $trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
        $format_trip_end_date_and_time = date('d/m/Y h:i A', strtotime($trip_end_date_and_time));
        $total_adult = $fetch_list_data['total_adult'];
        $total_children = $fetch_list_data['total_children'];
        $total_infants = $fetch_list_data['total_infants'];
        $total_members = "<span>Adult - $total_adult</br>Children - $total_children</br>Infants - $total_infants</span>";


        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '",';
        $datas .= '"arrival_location": "' . $arrival_location . '",';
        $datas .= '"departure_location": "' . $departure_location . '",';
        $datas .= '"start_date_and_time": "' . $format_trip_start_date_and_time . '",';
        $datas .= '"end_date_and_time": "' . $format_trip_end_date_and_time . '",';
        $datas .= '"total_members": "' . $total_members . '",';
        $datas .= '"modify": "' . $itinerary_plan_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
