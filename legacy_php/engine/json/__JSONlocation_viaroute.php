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

    $location_ID = $_GET['location_ID'];
    $select_LOCATIONLIST_query = sqlQUERY_LABEL("SELECT `via_route_location_ID`, `location_id`, `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude`, `via_route_location_city`, `distance_from_source_to_via_route`, `duration_from_source_to_via_route` FROM `dvi_stored_location_via_routes` WHERE `location_id`='$location_ID' AND `deleted` = '0' ORDER BY `via_route_location_ID` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_LOCATIONLIST_query)) :
        $counter++;
        $via_route_location_ID = $fetch_list_data['via_route_location_ID'];
        $via_route_location = html_entity_decode($fetch_list_data['via_route_location']);
        $via_route_location_lattitude = $fetch_list_data['via_route_location_lattitude'];
        $via_route_location_longitude = $fetch_list_data['via_route_location_longitude'];
        $distance_from_source_to_via_route = $fetch_list_data['distance_from_source_to_via_route'];
        $duration_from_source_to_via_route = $fetch_list_data['duration_from_source_to_via_route'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"via_route_location": "' . $via_route_location . '",';
        $datas .= '"via_route_location_lattitude": "' . $via_route_location_lattitude . '",';
        $datas .= '"via_route_location_longitude": "' . $via_route_location_longitude . '",';
        $datas .= '"modify": "' . $via_route_location_ID . '"'; //6
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
