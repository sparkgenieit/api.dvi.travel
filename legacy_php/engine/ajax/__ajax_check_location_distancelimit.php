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

    $sanitize_source_location = $validation_globalclass->sanitize($_POST['source_location']);
    $sanitize_destination_location = $validation_globalclass->sanitize($_POST['destination_location']);

    $fetch_distance_from_master_table = sqlQUERY_LABEL("SELECT `location_ID`,`distance` FROM  `dvi_stored_locations` WHERE `destination_location` = '$sanitize_destination_location' AND `source_location` = '$sanitize_source_location' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

    if (sqlNUMOFROW_LABEL($fetch_distance_from_master_table) > 0) :
        while ($row = sqlFETCHARRAY_LABEL($fetch_distance_from_master_table)) :
            $location_ID = $row['location_ID'];
            $distanceKM = $row['distance'];
            $calculated_distance = str_ireplace(array("km", "KM"), "", $distanceKM);
        endwhile;
    else :
        $calculated_distance = 0;
    endif;

    $select_global_settings = sqlQUERY_LABEL("SELECT `global_settings_ID`, `itinerary_distance_limit`, `itinerary_travel_by_flight_buffer_time`, `itinerary_travel_by_train_buffer_time`, `itinerary_travel_by_road_buffer_time` FROM `dvi_global_settings` WHERE `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($select_global_settings) > 0) :
        while ($fetch_settings_data = sqlFETCHARRAY_LABEL($select_global_settings)) :
            $itinerary_distance_limit = $fetch_settings_data['itinerary_distance_limit'];
        endwhile;
    endif;

    if ($calculated_distance <= $itinerary_distance_limit) :
        echo "true";
    else :
        echo "false";
    endif;
else :
    echo "Request Ignored";
endif;
