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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'check_distance_limit') :

        $response = [];
        $errors = [];

        $source = $_POST['source'];
        $via_routes = $_POST['via_routes'];
        $destination = $_POST['destination'];

        // Assuming you retrieve latitude and longitude from the database using getSTOREDLOCATIONDETAILS
        // Retrieve locations data
        $source_location_latitude = getSTOREDLOCATIONDETAILS($source, 'location_latitude_from_location_name');
        $source_location_longitude = getSTOREDLOCATIONDETAILS($source, 'location_longtitude_from_location_name');
        $destination_location_latitude = getSTOREDLOCATIONDETAILS($destination, 'location_latitude_from_location_name');
        $destination_location_longitude = getSTOREDLOCATIONDETAILS($destination, 'location_longtitude_from_location_name');

        // Get details for all via routes
        $via_locations = getSTOREDLOCATION_VIAROUTE_DETAILS('', implode(',', $via_routes), 'MULTIPLE_VIAROUTE_LOCATION');

        // Variables to hold cumulative and individual distances
        $cumulative_distance = 0;
        $individual_distances = [];

        // 1. Calculate distance from Source to Via[0]
        if (!empty($via_locations[0])) :
            $via1_latitude = getSTOREDLOCATIONDETAILS($via_locations[0], 'location_latitude_from_location_name');
            $via1_longitude = getSTOREDLOCATIONDETAILS($via_locations[0], 'location_longtitude_from_location_name');

            $distance_source_to_via1 = haversineDistance(
                $source_location_latitude,
                $source_location_longitude,
                $via1_latitude,
                $via1_longitude
            );

            $individual_distances['source_to_via1'] = $distance_source_to_via1;
            $cumulative_distance += $distance_source_to_via1;
        endif;

        // 2. Calculate distances between Via points (if more than one via route)
        for ($i = 0; $i < count($via_locations) - 1; $i++) :
            $current_via_latitude = getSTOREDLOCATIONDETAILS($via_locations[$i], 'location_latitude_from_location_name');
            $current_via_longitude = getSTOREDLOCATIONDETAILS($via_locations[$i], 'location_longtitude_from_location_name');
            $next_via_latitude = getSTOREDLOCATIONDETAILS($via_locations[$i + 1], 'location_latitude_from_location_name');
            $next_via_longitude = getSTOREDLOCATIONDETAILS($via_locations[$i + 1], 'location_longtitude_from_location_name');

            $distance_via_to_via = haversineDistance(
                $current_via_latitude,
                $current_via_longitude,
                $next_via_latitude,
                $next_via_longitude
            );

            $individual_distances['via' . ($i + 1) . '_to_via' . ($i + 2)] = $distance_via_to_via;
            $cumulative_distance += $distance_via_to_via;
        endfor;

        // 3. Calculate distance from last Via to Destination
        if (!empty($via_locations[count($via_locations) - 1])) :
            $last_via_latitude = getSTOREDLOCATIONDETAILS($via_locations[count($via_locations) - 1], 'location_latitude_from_location_name');
            $last_via_longitude = getSTOREDLOCATIONDETAILS($via_locations[count($via_locations) - 1], 'location_longtitude_from_location_name');

            $distance_last_via_to_destination = haversineDistance(
                $last_via_latitude,
                $last_via_longitude,
                $destination_location_latitude,
                $destination_location_longitude
            );

            $individual_distances['last_via_to_destination'] = $distance_last_via_to_destination;
            $cumulative_distance += $distance_last_via_to_destination;
        endif;

        /* // Output individual distances and cumulative distance
        echo "Individual Distances: ";
        print_r($individual_distances);
        echo "<br>";
        echo "Cumulative Distance: " . $cumulative_distance . " KM";

        exit; */

        $cumulative_distance = round($cumulative_distance);
        $itinerary_distance_limit = getGLOBALSETTING('itinerary_distance_limit');

        if ($cumulative_distance >= 0):
            if ($cumulative_distance > $itinerary_distance_limit):
                $errors['result_error'] = "Distance KM Limit Exceeded !!!";
            endif;
        else:
            $errors['result_error'] = "Unable to Add Via Route !!!";
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;
        endif;

        echo json_encode($response);

    endif;
else:
    echo "Request Ignored";
endif;
