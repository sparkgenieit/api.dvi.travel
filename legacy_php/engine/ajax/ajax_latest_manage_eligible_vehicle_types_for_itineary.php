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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') : // CHECK AJAX REQUEST

    if ($_GET['type'] == 'check_vehicle_types') :

        // Initialize response and errors
        $response = ['vehicle_options' => '', 'selected_vehicle_ids' => []];
        $errors = [];

        // Collect and sanitize input data
        $source_locations = isset($_POST['source_location']) ? $_POST['source_location'] : [];
        $next_visiting_locations = isset($_POST['next_visiting_location']) ? $_POST['next_visiting_location'] : [];
        $itinerary_plan_ID = isset($_POST['itinerary_plan_ID']) ? $_POST['itinerary_plan_ID'] : NULL;

        // Merge and filter locations
        $all_locations = array_merge($source_locations, $next_visiting_locations);
        $unique_locations = array_values(array_filter(array_unique($all_locations)));

        // Retrieve eligible location cities
        $eligible_cities = getSTOREDLOCATIONDETAILS($unique_locations, 'get_location_city_from_location_name');
        $imploded_cities = implode("','", $eligible_cities);

        // Get selected vehicle type IDs if itinerary_plan_ID is provided
        if ($itinerary_plan_ID) :
            $select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `status` = '1' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_details)) :
                $response['selected_vehicle_ids'][] = $fetch_vehicle_data['vehicle_type_id'];
            endwhile;
        endif;

        // SQL query to check vehicle type availability
        $query = "
            SELECT DISTINCT VENDOR_VEHICLE_TYPES.vehicle_type_id AS vehicle_type, 
                            VEHICLE_TYPES.vehicle_type_title 
            FROM dvi_vehicle VEHICLE
            LEFT JOIN dvi_vendor_vehicle_types VENDOR_VEHICLE_TYPES ON VEHICLE.vehicle_type_id = VENDOR_VEHICLE_TYPES.vendor_vehicle_type_ID 
               AND VEHICLE.vendor_id = VENDOR_VEHICLE_TYPES.vendor_id
            LEFT JOIN dvi_vendor_details VENDOR_DETAILS ON VENDOR_DETAILS.vendor_id = VEHICLE.vendor_id
            LEFT JOIN dvi_vendor_branches VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.vendor_branch_id = VEHICLE.vendor_branch_id
            LEFT JOIN dvi_vehicle_type VEHICLE_TYPES ON VEHICLE_TYPES.vehicle_type_id = VENDOR_VEHICLE_TYPES.vehicle_type_id
            WHERE VEHICLE.status = '1' 
              AND VEHICLE.deleted = '0' 
              AND VENDOR_DETAILS.status = '1' 
              AND VENDOR_DETAILS.deleted = '0' 
              AND VENDOR_BRANCH_DETAILS.status = '1' 
              AND VENDOR_BRANCH_DETAILS.deleted = '0'
              AND VEHICLE.owner_city IN ('$imploded_cities')
            GROUP BY VEHICLE.vehicle_type_id
        ";

        // Execute query and process results
        $result = sqlQUERY_LABEL($query);
        if ($result) :
            $total_count = sqlNUMOFROW_LABEL($result);
            if ($total_count > 0) :
                $response['vehicle_options'] = "<option value=''>Choose Vehicle Type</option>";
                while ($row = sqlFETCHARRAY_LABEL($result)) :
                    $vehicle_type = $row['vehicle_type'];
                    $vehicle_type_title = $row['vehicle_type_title'];
                    $response['vehicle_options'] .= "<option value='$vehicle_type'>$vehicle_type_title</option>";
                endwhile;
            else :
                $response['vehicle_options'] = "<option value=''>No Vehicle types found</option>";
            endif;
        else :
            $errors[] = "Unable to collect vehicle details.";
        endif;

        // Return response as JSON
        if (!empty($errors)) :
            $response['errors'] = $errors;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
