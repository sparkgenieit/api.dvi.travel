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

    $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `agent_id`, `arrival_location`, `departure_location`, `itinerary_quote_ID`, `trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_confirmed_itinerary_plan_details` WHERE `agent_id` != 0 ORDER BY `itinerary_plan_ID` DESC") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $agent_id = $fetch_list_data['agent_id'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
        $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
        $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_start_date_and_time']));
        $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_end_date_and_time']));
        $agent_name = getAGENT_details($agent_id, '', 'label');
    
        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
        $datas .= '"agent_name": "' . $agent_name . '",';
        $datas .= '"arrival_location": "' . $arrival_location . '",';
        $datas .= '"departure_location": "' . $departure_location . '",';
        $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '",';
        $datas .= '"trip_start_date_and_time": "' . $trip_start_date_and_time . '",';
        $datas .= '"trip_end_date_and_time": "' . $trip_end_date_and_time . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
