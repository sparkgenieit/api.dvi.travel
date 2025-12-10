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

    $select_hotspot_query = sqlQUERY_LABEL("SELECT CONFIRMED_ITINEARY_GUIDE.`itinerary_plan_ID` AS GUIDE_REQUIRED, CONFIRED_ITINEARY.`itinerary_plan_ID`, CONFIRED_ITINEARY.`arrival_location`, CONFIRED_ITINEARY.`departure_location`, CONFIRED_ITINEARY.`itinerary_quote_ID`, CONFIRED_ITINEARY.`trip_start_date_and_time`, CONFIRED_ITINEARY.`trip_end_date_and_time`, CONFIRED_ITINEARY.`no_of_days` FROM `dvi_confirmed_itinerary_plan_details` CONFIRED_ITINEARY LEFT JOIN `dvi_confirmed_itinerary_route_guide_details` CONFIRMED_ITINEARY_GUIDE ON CONFIRMED_ITINEARY_GUIDE.`itinerary_plan_ID` = CONFIRED_ITINEARY.`itinerary_plan_ID` WHERE CONFIRED_ITINEARY.`deleted` = '0' AND CONFIRED_ITINEARY.`status` = '1' GROUP BY CONFIRED_ITINEARY.`itinerary_plan_ID` ORDER BY CONFIRED_ITINEARY.`confirmed_itinerary_plan_ID` DESC; ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
        $counter++;
        $GUIDE_REQUIRED = $fetch_list_data['GUIDE_REQUIRED'];
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
        $no_of_days = $fetch_list_data['no_of_days'];
        $guest_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",'; //0
        $datas .= '"GUIDE_REQUIRED": "' . $GUIDE_REQUIRED . '",'; //1
        $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",'; //1
        $datas .= '"quote_id": "' . $itinerary_quote_ID . '",'; //1
        $datas .= '"arrival_location": "' . $arrival_location . '",'; //2
        $datas .= '"departure_location": "' . $departure_location . '",'; //3
        $datas .= '"guest_name": "' . $guest_name . '",'; //4
        $datas .= '"days": "Day - ' . $no_of_days . '",'; //5
        $datas .= '"modify": "' . $itinerary_plan_ID . '"'; //5
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
