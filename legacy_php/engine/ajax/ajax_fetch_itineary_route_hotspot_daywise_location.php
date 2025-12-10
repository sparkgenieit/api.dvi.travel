<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

if ($_GET['type'] == 'show_form') :

    $itinerary_route_ID = $_GET['itinerary_route_ID'];

    $select_itinerary_route_list_query = sqlQUERY_LABEL("SELECT `location_name`, `location_latitude`, `location_longtitude`,`next_visiting_location`, `next_visiting_location_latitude`, `next_visiting_location_longitude`, `location_via_route`, `via_route_latitude`, `via_route_longtitude` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ROUTE_LOCATION_LIST:" . sqlERROR_LABEL());

    $customLocations = array(); // Initialize an array to store locations

    while ($fetch_itinerary_route_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_list_query)) :
        $location_name = $fetch_itinerary_route_list_data['location_name'];
        $location_latitude = $fetch_itinerary_route_list_data['location_latitude'];
        $location_longitude = $fetch_itinerary_route_list_data['location_longtitude'];
        $next_visiting_location = $fetch_itinerary_route_list_data['next_visiting_location'];
        $next_visiting_location_latitude = $fetch_itinerary_route_list_data['next_visiting_location_latitude'];
        $next_visiting_location_longitude = $fetch_itinerary_route_list_data['next_visiting_location_longitude'];
        $location_via_route = $fetch_itinerary_route_list_data['location_via_route'];
        $via_route_latitude = $fetch_itinerary_route_list_data['via_route_latitude'];
        $via_route_longitude = $fetch_itinerary_route_list_data['via_route_longtitude'];

        // Create an array for each location
        $location = array(
            'name' => $location_name,
            'location' => array('lat' => $location_latitude, 'lng' => $location_longitude)
        );

        // Add the location to the customLocations array
        $customLocations[] = $location;

        // Check if next visiting location is not null
        if (!empty($next_visiting_location)) :
            $nextLocation = array(
                'name' => $next_visiting_location,
                'location' => array('lat' => $next_visiting_location_latitude, 'lng' => $next_visiting_location_longitude)
            );
            $customLocations[] = $nextLocation;
        endif;

        // Check if via route location is not null
        if (!empty($location_via_route)) :
            $viaLocation = array(
                'name' => $location_via_route,
                'location' => array('lat' => $via_route_latitude, 'lng' => $via_route_longitude)
            );
            $customLocations[] = $viaLocation;
        endif;
    endwhile;

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($customLocations);
endif;
