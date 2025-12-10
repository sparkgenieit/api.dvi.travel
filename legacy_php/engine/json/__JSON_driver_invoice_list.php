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

    $select_hotspot_query = sqlQUERY_LABEL("SELECT VEHICLE.`vehicle_details_ID`, VEHICLE.`vehicle_type_id`, VEHICLE.`vehicle_count`, ITINERARY.`arrival_location`, ITINERARY.`departure_location`, ITINERARY.`generated_quote_code`, ITINERARY.`trip_start_date_and_time`, ITINERARY.`trip_end_date_and_time`, ITINERARY.`status` FROM `dvi_itinerary_plan_vehicle_details` AS VEHICLE LEFT JOIN `dvi_itinerary_plan_details` AS ITINERARY ON ITINERARY.`itinerary_plan_ID`=VEHICLE.`itinerary_plan_id` WHERE VEHICLE.`status`='1' and VEHICLE.`deleted`='0' ORDER BY ITINERARY.`itinerary_plan_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotspot_query)) :
        $counter++;
        $vehicle_details_ID = $fetch_list_data['vehicle_details_ID'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
		$location = "<div><b>Source:</b> " . $arrival_location . "</div><div><b>Destination:</b> " . $departure_location . "</div>";
		
        //$generated_quote_code = $fetch_list_data['generated_quote_code'];
        $generated_quote_code = 'DVIA0001';
		
        $trip_start_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_start_date_and_time']));
        $trip_end_date_and_time = date('d-m-Y h:i A', strtotime($fetch_list_data['trip_end_date_and_time']));
		$date_and_time = "<div class='text-center'>" . $trip_start_date_and_time . '<br/> To <br/>' . $trip_end_date_and_time . "</div>";
		
		//$location = '<span class="text-center">' . $arrival_location . '<br/> To <br/>'. $departure_location . '</span>';
		//$date_and_time = '<span class="text-center">' . $trip_start_date_and_time . '<br/> To <br/>'. $trip_end_date_and_time . '</span>';
		
        $status = $fetch_list_data['status'];
		
		$travel_distance = '421';
		$travel_cost = '₹ 15,770';

        $datas .= "{";
        $datas .= '"counter": "' . $counter . '",'; //0
        $datas .= '"generated_quote_code": "' . $generated_quote_code . '",'; //1
        $datas .= '"location": "' . $location . '",'; //2
        $datas .= '"date_and_time": "' . $date_and_time . '",'; //3
        $datas .= '"travel_distance": "' . $travel_distance . '",'; //4
        $datas .= '"travel_cost": "' . $travel_cost . '",'; //5
        $datas .= '"status": "' . $status . '",'; //6
        $datas .= '"modify": "' . $vehicle_details_ID . '"'; //7
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
