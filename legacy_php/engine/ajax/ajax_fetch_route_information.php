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

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_id = $_POST['itinerary_plan_id'];
        $route_details = $_POST['route_details'];

        // Initialize the response array
        $response['route_details'] = [];

        foreach ($route_details as $detail) :
            $day_no = $detail['day_no'];
            $itinerary_route_ID = $detail['itinerary_route_ID'];
            $next_visiting_location = $detail['next_visiting_location'];

            // Query your database to get information based on itinerary_plan_id and day_no
            $select_route_info_query = sqlQUERY_LABEL("SELECT `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_FETCH_ROUTE_INFO:" . sqlERROR_LABEL());

            // Fetch the result
            if ($row = sqlFETCHARRAY_LABEL($select_route_info_query)) :
                $db_next_visiting_location = $row['next_visiting_location'];
            else :
                $db_next_visiting_location = $next_visiting_location; // Fallback to the posted value if no database result
            endif;

            // Add result to the response array
            $response['route_details'][] = [
                'day_no' => $day_no,
                'next_visiting_location' => $db_next_visiting_location
            ];
        endforeach;

        // Output the response as JSON
        echo json_encode($response);

    endif;
else:
    echo "Request Ignored";
endif;
