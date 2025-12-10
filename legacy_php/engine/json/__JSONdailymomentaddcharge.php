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


    $itinerary_plan_ID = $_GET['plan_ID'];

    echo "{";
    echo '"data":[';

    $select_hotel_list_query = sqlQUERY_LABEL("SELECT `driver_charge_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `charge_type`, `charge_amount` FROM `dvi_confirmed_itinerary_dailymoment_charge` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `driver_charge_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $counter++;
        $driver_charge_ID = $fetch_list_data['driver_charge_ID'];
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $itinerary_route_ID = $fetch_list_data['itinerary_route_ID'];
        $get_route_date =  getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '');
        $location_name =  getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_name_from_routedate', '');
        $next_visiting_location =  getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location', '');
        $formatted_route_date = date('D, M d,Y', strtotime($get_route_date));
        $charge_type = $fetch_list_data['charge_type'];
        $charge_amount = $fetch_list_data['charge_amount'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"get_route_date": "' . $formatted_route_date . '",';
        $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
        $datas .= '"itinerary_route_ID": "' . $itinerary_route_ID . '",';
        $datas .= '"location_name": "' . $location_name . '",';
        $datas .= '"next_visiting_location": "' . $next_visiting_location . '",';
        $datas .= '"charge_type": "' . $charge_type . '",';
        $datas .= '"charge_amount": "' . $charge_amount . '",';
        $datas .= '"modify": "' . $driver_charge_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
