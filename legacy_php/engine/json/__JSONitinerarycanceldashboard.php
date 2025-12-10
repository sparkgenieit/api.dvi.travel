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

    if ($logged_agent_id != '' && $logged_agent_id != '0') {
        $filter_agent = "AND cp.`agent_id` = $logged_agent_id";
    } else {
        $filter_agent = "";
    }
    
    // Fetch 20 cancelled itineraries with matching details
    $select_cancel_list_query = sqlQUERY_LABEL("
        SELECT 
            cp.`itinerary_plan_ID`,
            cp.`arrival_location`,
            cp.`departure_location`,
            cp.`itinerary_quote_ID`,
            cp.`trip_start_date_and_time`,
            cp.`trip_end_date_and_time`
        FROM `dvi_cancelled_itineraries` ci
        INNER JOIN `dvi_confirmed_itinerary_plan_details` cp
            ON ci.`itinerary_plan_id` = cp.`itinerary_plan_ID`
        WHERE ci.`deleted` = '0'
          AND cp.`deleted` = '0'
          {$filter_agent}
        ORDER BY cp.`itinerary_plan_ID` DESC
        LIMIT 20
    ") or die("#1-UNABLE_TO_COLLECT_CANCELLED_ITINERARIES_LIST:" . sqlERROR_LABEL());
    
    $counter = 0;
    $datas = '';
    
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_cancel_list_query)) {
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
        $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
        $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_start_date_and_time']));
        $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_end_date_and_time']));
    
        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
        $datas .= '"arrival_location": "' . $arrival_location . '",';
        $datas .= '"departure_location": "' . $departure_location . '",';
        $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '",';
        $datas .= '"customer_name": "' . $customer_name . '",';
        $datas .= '"trip_start_date_and_time": "' . $trip_start_date_and_time . '",';
        $datas .= '"trip_end_date_and_time": "' . $trip_end_date_and_time . '"';
        $datas .= " },";
    }
    

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
