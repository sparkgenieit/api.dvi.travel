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

// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

echo "{";
echo '"data":[';

$select_hotelCATEGORYLIST_query = sqlQUERY_LABEL("SELECT `permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' ORDER BY `permit_cost_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

$groupedData = array(); // Initialize an array to hold grouped data
$counter = 1; // Initialize the counter variable

while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotelCATEGORYLIST_query)) {
    $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
    $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
    $source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
    $destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
    $permit_cost = $fetch_list_data['permit_cost'];

    // Group data by source state
    if (!isset($groupedData[$source_state_name])) {
        $groupedData[$source_state_name] = array(
            "counter" => $counter,
            "vehicle_type_name" => $vehicle_type_name,
            "source_state" => $source_state_name,
            "destination_states_and_costs" => array()
            // Include the source state in each row
        );
        $counter++; // Increment the counter for each new source state
    }

    // Add destination state and permit cost to the array for the current source state
    $groupedData[$source_state_name]["destination_states_and_costs"][] = "$destination_state_name: $permit_cost";
}

// Convert the grouped data to JSON
$jsonData = json_encode(array_values($groupedData)); // Use array_values to remove source state keys

echo $jsonData;




echo "]}";
// else :
//     echo "Request Ignored !!!";
// endif;
