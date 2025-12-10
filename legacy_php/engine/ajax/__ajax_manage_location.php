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

set_time_limit(0);
ini_set('max_execution_time', 5000); // Increase max execution time
include_once('../../jackus.php');

// ini_set('display_errors', 1);
// ini_set('log_errors', 1);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'add') :

        /* // Log function
        function log_debug($message)
        {
            file_put_contents('debug_log.txt', $message . "\n", FILE_APPEND);
        } */

        $errors = [];
        $response = [];
        $hid_location_ID = $_POST['hid_location_ID'];

        // Log initial POST data
        /* log_debug("Initial POST data: " . print_r($_POST, true)); */

        // Validate required fields
        $required_fields = [
            'source_location' => 'Source Location',
            'source_location_city' => 'Source Location City',
            'source_location_state' => 'Source Location State',
            'source_location_lattitude' => 'Source Location Latitude',
            'source_location_longitude' => 'Source Location Longitude'
        ];

        foreach ($required_fields as $field => $name) {
            if (empty($_POST[$field])) {
                $errors[$field . '_required'] = "<div class='alert alert-left alert-warning mt-3' role='alert'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 128 128' class='mx-2'>
                    <g>
                        <path fill='#f16a1b' d='M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z'></path>
                        <g fill='#fcf4d9'>
                            <rect width='9.638' height='29.377' x='59.181' y='46.444' rx='4.333'></rect>
                            <circle cx='64' cy='87.428' r='4.819'></circle>
                        </g>
                    </g>
                </svg>
                Please Enter $name !!!
            </div>";
            }
        }

        if (!empty($hid_location_ID)) {
            $destination_required_fields = [
                'destination_location' => 'Destination Location',
                'destination_location_city' => 'Destination Location City',
                'destination_location_state' => 'Destination Location State',
                'destination_location_lattitude' => 'Destination Location Latitude',
                'destination_location_longitude' => 'Destination Location Longitude'
            ];

            foreach ($destination_required_fields as $field => $name) {
                if (empty($_POST[$field])) {
                    $errors[$field . '_required'] = "<div class='alert alert-left alert-warning mt-3' role='alert'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 128 128' class='mx-2'>
                        <g>
                            <path fill='#f16a1b' d='M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z'></path>
                            <g fill='#fcf4d9'>
                                <rect width='9.638' height='29.377' x='59.181' y='46.444' rx='4.333'></rect>
                                <circle cx='64' cy='87.428' r='4.819'></circle>
                            </g>
                        </g>
                    </svg>
                    Please Enter $name !!!
                </div>";
                }
            }
        }

        // If there are errors, return them as the response
        if (!empty($errors)) {
            $response['success'] = false;
            $response['errors'] = $errors;
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Sanitize and assign input values
        $source_location = trim(addslashes($_POST['source_location']));
        $source_location_city = trim(addslashes($_POST['source_location_city']));
        $source_location_state = trim(addslashes($_POST['source_location_state']));
        $source_location_lattitude = trim(addslashes($_POST['source_location_lattitude']));
        $source_location_longitude = trim(addslashes($_POST['source_location_longitude']));
        $location_description = nl2br($_POST['location_description']);

        // Assign destination values if they exist in POST
        $destination_location = isset($_POST['destination_location']) ? trim(addslashes($_POST['destination_location'])) : '';
        $destination_location_city = isset($_POST['destination_location_city']) ? trim(addslashes($_POST['destination_location_city'])) : '';
        $destination_location_state = isset($_POST['destination_location_state']) ? trim(addslashes($_POST['destination_location_state'])) : '';
        $destination_location_lattitude = isset($_POST['destination_location_lattitude']) ? trim(addslashes($_POST['destination_location_lattitude'])) : '';
        $destination_location_longitude = isset($_POST['destination_location_longitude']) ? trim(addslashes($_POST['destination_location_longitude'])) : '';
        $loaction_distance = isset($_POST['loaction_distance']) ? addslashes($_POST['loaction_distance']) : '';
        $loaction_duration = isset($_POST['loaction_duration']) ? addslashes($_POST['loaction_duration']) : '';

        /* // Log sanitized input values
        log_debug("Sanitized input values: " . print_r([
            'source_location' => $source_location,
            'source_location_city' => $source_location_city,
            'source_location_state' => $source_location_state,
            'source_location_lattitude' => $source_location_lattitude,
            'source_location_longitude' => $source_location_longitude
        ], true)); */

        /* // Function to calculate distance using the Haversine formula
        function haversineDistance($lat1, $lon1, $lat2, $lon2)
        {
            $earth_radius = 6371; // Earth radius in kilometers
            $dLat = deg2rad($lat2 - $lat1);
            $dLon = deg2rad($lon2 - $lon1);
            $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            return $earth_radius * $c;
        } */

        // Function to estimate duration based on distance and return it in "hh:mm" format
        function estimateDuration($distance)
        {
            $average_speed = 25; // Assume an average speed of 25 km/h
            $hours = $distance / $average_speed;
            $minutes = ($hours - floor($hours)) * 60;
            $duration = floor($hours) . " hours " . round($minutes) . " mins";
            return $duration;
        }

        $success = true;

        if (!empty($hid_location_ID)) {
            // Update existing location
            $sqlWhere = " `location_ID` = '$hid_location_ID' ";
            $arrFields = [
                '`source_location`',
                '`source_location_lattitude`',
                '`source_location_longitude`',
                '`source_location_city`',
                '`source_location_state`',
                '`destination_location`',
                '`destination_location_lattitude`',
                '`destination_location_longitude`',
                '`destination_location_city`',
                '`destination_location_state`',
                '`location_description`',
                '`distance`',
                '`duration`'
            ];
            $arrValues_src_to_des = [
                "$source_location",
                "$source_location_lattitude",
                "$source_location_longitude",
                "$source_location_city",
                "$source_location_state",
                "$destination_location",
                "$destination_location_lattitude",
                "$destination_location_longitude",
                "$destination_location_city",
                "$destination_location_state",
                "$location_description",
                "$loaction_distance",
                "$loaction_duration"
            ];
            /* log_debug("Updating existing location with ID $hid_location_ID"); */
            $success = sqlACTIONS("UPDATE", "dvi_stored_locations", $arrFields, $arrValues_src_to_des, $sqlWhere);
        } else {
            // Insert new location
            $select_all_locations = sqlQUERY_LABEL("SELECT DISTINCT `source_location`, `source_location_lattitude`, `source_location_longitude`, `source_location_city`, `source_location_state` FROM `dvi_stored_locations` WHERE `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

            $locations = [];
            if (sqlNUMOFROW_LABEL($select_all_locations) > 0) {
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_all_locations)) {
                    $locations[] = [
                        'source_location' => html_entity_decode(html_entity_decode($fetch_list_data['source_location'])),
                        'source_latitude' => trim($fetch_list_data['source_location_lattitude']),
                        'source_longitude' => trim($fetch_list_data['source_location_longitude']),
                        'source_location_city' => trim($fetch_list_data['source_location_city']),
                        'source_location_state' => trim($fetch_list_data['source_location_state'])
                    ];
                }
            }

            $arrFields = [
                '`source_location`',
                '`source_location_lattitude`',
                '`source_location_longitude`',
                '`source_location_city`',
                '`source_location_state`',
                '`destination_location`',
                '`destination_location_lattitude`',
                '`destination_location_longitude`',
                '`destination_location_city`',
                '`destination_location_state`',
                '`distance`',
                '`duration`',
                '`created_from`',
                '`createdby`',
                '`status`'
            ];

            $route_perday_km_limit = getGLOBALSETTING('itinerary_distance_limit');
            /* log_debug("Route per day km limit: $route_perday_km_limit"); */

            // Loop through all locations and filter eligible ones
            $eligible_locations = [];
            foreach ($locations as $location) {
                $distance = haversineDistance($source_location_lattitude, $source_location_longitude, $location['source_latitude'], $location['source_longitude']);
                if (
                    $distance <= $route_perday_km_limit
                ) {
                    $eligible_locations[] = $location;
                }
            }

            /* log_debug("Eligible locations: " . print_r($eligible_locations, true)); */

            // Loop through eligible locations and create combinations
            foreach ($eligible_locations as $location) {
                $destination_location = trim(addslashes($location['source_location']));
                $destination_latitude = trim($location['source_latitude']);
                $destination_longitude = trim($location['source_longitude']);
                $destination_location_city = trim($location['source_location_city']);
                $destination_location_state = trim($location['source_location_state']);

                // Check if the destination location already exists for this source location
                $check_destination_for_source_exists = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE `source_location`='$source_location' AND `destination_location`='$destination_location'") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                if (sqlNUMOFROW_LABEL($check_destination_for_source_exists) == 0) {
                    $distance = haversineDistance($source_location_lattitude, $source_location_longitude, $destination_latitude, $destination_longitude);
                    /* log_debug("Distance from $source_location to $destination_location: $distance"); */

                    if ($distance <= $route_perday_km_limit) {
                        $duration = estimateDuration($distance);
                        /* log_debug("Duration from $source_location to $destination_location: $duration"); */

                        $arrValues_src_to_des = [
                            "$source_location",
                            "$source_location_lattitude",
                            "$source_location_longitude",
                            "$source_location_city",
                            "$source_location_state",
                            "$destination_location",
                            "$destination_latitude",
                            "$destination_longitude",
                            "$destination_location_city",
                            "$destination_location_state",
                            "$distance",
                            "$duration",
                            "1",
                            "$logged_user_id",
                            "1"
                        ];
                        /* log_debug("Inserting source to destination: " . print_r($arrValues_src_to_des, true)); */

                        if (sqlACTIONS("INSERT", "dvi_stored_locations", $arrFields, $arrValues_src_to_des, '')) {
                            if ($source_location != $destination_location) {
                                $check_des_to_source_already_exists = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE (`source_location`='$destination_location' AND `destination_location`='$source_location') OR (`source_location_lattitude`='$destination_latitude' AND `source_location_longitude`='$destination_longitude' AND `destination_location_lattitude`='$source_location_lattitude' AND `destination_location_longitude`='$source_location_longitude')") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                                if (sqlNUMOFROW_LABEL($check_des_to_source_already_exists) == 0) {
                                    $arrValues_des_to_src = [
                                        "$destination_location",
                                        "$destination_latitude",
                                        "$destination_longitude",
                                        "$destination_location_city",
                                        "$destination_location_state",
                                        "$source_location",
                                        "$source_location_lattitude",
                                        "$source_location_longitude",
                                        "$source_location_city",
                                        "$source_location_state",
                                        "$distance",
                                        "$duration",
                                        "1",
                                        "$logged_user_id",
                                        "1"
                                    ];
                                    /* log_debug("Inserting destination to source: " . print_r($arrValues_des_to_src, true)); */
                                    sqlACTIONS("INSERT", "dvi_stored_locations", $arrFields, $arrValues_des_to_src, '');
                                }
                            }
                        }
                    }
                }
            }
        }

        $response['result'] = $success ? true : false;
        $response['success'] = $success;

        header('Content-Type: application/json');
        echo json_encode($response);

        if (
            json_last_error() !== JSON_ERROR_NONE
        ) :
            error_log('JSON encode error: ' . json_last_error_msg());
        endif;

    elseif ($_GET['type'] == 'add_via_route') :

        $errors = [];
        $response = [];

        if (empty($_POST['via_route_location'])) :
            $errors['via_route_required'] = '<div class="alert alert-left alert-warning mt-3" role="alert"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="24" height="24" x="0" y="0" viewBox="0 0 128 128" style="enable-background:new 0 0 512 512" xml:space="preserve" class="mx-2"><g><path fill="#f16a1b" d="M57.362 26.54 20.1 91.075a7.666 7.666 0 0 0 6.639 11.5h74.518a7.666 7.666 0 0 0 6.639-11.5L70.638 26.54a7.665 7.665 0 0 0-13.276 0z" data-original="#ffb400" opacity="1"></path><g fill="#fcf4d9"><rect width="9.638" height="29.377" x="59.181" y="46.444" rx="4.333" fill="#fcf4d9" data-original="#fcf4d9"></rect><circle cx="64" cy="87.428" r="4.819" fill="#fcf4d9" data-original="#fcf4d9"></circle></g></g></svg>Please Enter Via Route !!!</div>';
        endif;

        // SANITIZE
        $via_route = trim(htmlentities($_POST['via_route_location']));
        $via_route_location_longitude = $_POST['via_route_location_longitude'];
        $via_route_location_lattitude = $_POST['via_route_location_lattitude'];
        $via_route_location_state = trim($_POST['via_route_location_state']);
        $via_route_location_city = trim($_POST['via_route_location_city']);

        $hid_location_id = $_POST['hid_location_id'];
        $hid_via_route_id = $_POST['hid_via_route_id'];

        if ($hid_via_route_id != "") :
            // UPDATE
            $distance_from_source_to_via_route = $_POST['distance_from_source_location'];
            $duration_from_source_to_via_route = $_POST['duration_from_source_location'];

            $distance_from_via_route_to_destination = $_POST['distance_from_via_route_to_destination'];
            $duration_from_via_route_to_destination = $_POST['duration_from_via_route_to_destination'];
        else :
            // INSERT
            $source_location_lattitude = getSTOREDLOCATIONDETAILS($hid_location_id, 'source_location_lattitude');
            $source_location_longitude = getSTOREDLOCATIONDETAILS($hid_location_id, 'source_location_longitude');

            $destination_location_lattitude = getSTOREDLOCATIONDETAILS($hid_location_id, 'destination_location_lattitude');
            $destination_location_longitude = getSTOREDLOCATIONDETAILS($hid_location_id, 'destination_location_longitude');

            /* // Function to calculate distance using the Haversine formula
            function haversineDistance($lat1, $lon1, $lat2, $lon2)
            {
                $earth_radius = 6371; // Earth radius in kilometers
                $dLat = deg2rad($lat2 - $lat1);
                $dLon = deg2rad($lon2 - $lon1);
                $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                return $earth_radius * $c;
            } */

            // Function to estimate duration based on distance and return it in "hh:mm" format
            function estimateDuration($distance)
            {
                $average_speed = 25; // Assume an average speed of 25 km/h
                $hours = $distance / $average_speed;
                $minutes = ($hours - floor($hours)) * 60;
                $duration = floor($hours) . " hours " . round($minutes) . " mins";
                return $duration;
            }

            $distance_from_source_to_via_route = haversineDistance($source_location_lattitude, $source_location_longitude, $via_route_location_lattitude, $via_route_location_longitude);
            $duration_from_source_to_via_route = estimateDuration($distance_from_source_to_via_route);

            $distance_from_via_route_to_destination = haversineDistance($via_route_location_lattitude, $via_route_location_longitude, $destination_location_lattitude, $destination_location_longitude);
            $duration_from_via_route_to_destination = estimateDuration($distance_from_via_route_to_destination);
        endif;

        if (!empty($errors)) :
            // Error response
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            // Success call
            $response['success'] = true;
            $response['result'] = true;

            if (!empty($hid_via_route_id)) :
                // UPDATE VIAROUTE
                $sqlWhere = " `via_route_location_ID` = '$hid_via_route_id' ";
                $arrFields = array('`location_id`', '`via_route_location`', '`via_route_location_lattitude`', '`via_route_location_longitude`', '`via_route_location_state`', '`via_route_location_city`',  '`distance_from_source_to_via_route`', '`duration_from_source_to_via_route`', '`distance_from_via_route_to_destination`', '`duration_from_via_route_to_destination`');

                $arrValues = array("$hid_location_id", "$via_route", "$via_route_location_lattitude", "$via_route_location_longitude", "$via_route_location_state", "$via_route_location_city", "$distance_from_source_to_via_route", "$duration_from_source_to_via_route", "$distance_from_via_route_to_destination", "$duration_from_via_route_to_destination");

                // Update VIAROUTE DETAILS
                $success = sqlACTIONS("UPDATE", "dvi_stored_location_via_routes", $arrFields, $arrValues, $sqlWhere);
            else :
                // INSERT VIAROUTE
                $check_via_route_already_exists = sqlQUERY_LABEL("SELECT `via_route_location_ID` FROM `dvi_stored_location_via_routes` WHERE `location_id` ='$hid_location_id' AND `deleted`='0' AND((`via_route_location`='$via_route') AND (`via_route_location_lattitude`='$via_route_location_lattitude' AND `via_route_location_longitude` ='$via_route_location_longitude')) ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                if (sqlNUMOFROW_LABEL($check_via_route_already_exists) == 0) :

                    $arrFields = array('`location_id`', '`via_route_location`', '`via_route_location_lattitude`', '`via_route_location_longitude`', '`via_route_location_state`', '`via_route_location_city`',  '`distance_from_source_to_via_route`', '`duration_from_source_to_via_route`', '`distance_from_via_route_to_destination`', '`duration_from_via_route_to_destination`', '`created_from`', '`createdby`', '`status`');
                    $arrValues = array("$hid_location_id", "$via_route", "$via_route_location_lattitude", "$via_route_location_longitude", "$via_route_location_state", "$via_route_location_city", "$distance_from_source_to_via_route", "$duration_from_source_to_via_route", "$distance_from_via_route_to_destination", "$duration_from_via_route_to_destination", "1", "$logged_user_id", "1");

                    $success = sqlACTIONS("INSERT", "dvi_stored_location_via_routes", $arrFields, $arrValues, '');
                else :
                    $success = false;
                    $errors['location_not_available'] = 'Entered via route location is already existing. Please check latitude, longitude or via route location name entered.';
                endif;
            endif;

            // Check for success state
            if ($success == true) :
                $response['result'] = true;
            else :
                $response['result'] = false;
                $response['success'] = false;
                $response['errors'] = $errors;
            endif;
        endif;

        // Output the final response once
        echo json_encode($response);

    elseif ($_GET['type'] == 'add_toll_charge') :

        $errors = [];
        $response = [];

        $_hid_location_id = trim($_POST['hid_location_id']);
        $_hid_source_location = trim($_POST['hid_source_location']);
        $_hid_source_location = htmlspecialchars_decode($_hid_source_location, ENT_QUOTES);
        $_hid_source_location = trim($_hid_source_location);

        $_hid_destination_location = trim($_POST['hid_destination_location']);
        $_hid_destination_location = htmlspecialchars_decode($_hid_destination_location, ENT_QUOTES);
        $_hid_destination_location = trim($_hid_destination_location);

        $location_id_dest_src = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($_hid_destination_location, $_hid_source_location);

        $_vehicle_toll_charge = $_POST['vehicle_toll_charge'];
        $_vehicle_type_id = $_POST['vehicle_type_id'];
        $_vehicle_toll_charge_ID = $_POST['vehicle_toll_charge_ID'];

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            for ($j = 0; $j < count($_vehicle_toll_charge); $j++) :

                $toll_charge_id = $_vehicle_toll_charge_ID[$j];
                $vehicle_type_id = $_vehicle_type_id[$j];
                $vehicle_toll_charge = $_vehicle_toll_charge[$j];

                if ($toll_charge_id != '' && $toll_charge_id != 0) :

                    //UPDATE(SOURCE TO DESTINATION)
                    $arrFields_src_des = array('`toll_charge`');
                    $arrValues_src_des = array("$vehicle_toll_charge");
                    $sqlWhere_src_des = " `location_id` = '$_hid_location_id' AND `vehicle_type_id`= '$vehicle_type_id' ";

                    //UPDATE TOLL DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_vehicle_toll_charges", $arrFields_src_des, $arrValues_src_des, $sqlWhere_src_des)) :

                        //UPDATE (DESTINATION TO SOURCE )
                        $arrFields_des_src = array('`toll_charge`');
                        $arrValues_des_src = array("$vehicle_toll_charge");
                        $sqlWhere_des_src = " `location_id` = '$location_id_dest_src' AND `vehicle_type_id`= '$vehicle_type_id' ";

                        if (sqlACTIONS("UPDATE", "dvi_vehicle_toll_charges", $arrFields_des_src, $arrValues_des_src, $sqlWhere_des_src)) :
                        endif;

                        $response['u_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;


                else :
                    //INSERT (SOURCE TO DESTINATION)
                    $arrFields_src_des = array('`location_id`', '`vehicle_type_id`',  '`toll_charge`', '`createdby`', '`status`');

                    $arrValues_src_des = array("$_hid_location_id", "$vehicle_type_id",  "$vehicle_toll_charge",  "$logged_user_id", "1");

                    //INSERT TOLL DETAILS
                    if (sqlACTIONS("INSERT", "dvi_vehicle_toll_charges", $arrFields_src_des, $arrValues_src_des, '')) :

                        if ($location_id_dest_src) :

                            //INSERT (DESTINATION TO SOURCE )
                            $arrFields_des_src = array('`location_id`', '`vehicle_type_id`',  '`toll_charge`', '`createdby`', '`status`');

                            $arrValues_des_src = array("$location_id_dest_src", "$vehicle_type_id",  "$vehicle_toll_charge",  "$logged_user_id", "1");

                            //INSERT TOLL DETAILS
                            if (sqlACTIONS("INSERT", "dvi_vehicle_toll_charges", $arrFields_des_src, $arrValues_des_src, '')) :
                            endif;
                        endif;

                        $response['i_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;

                endif;

            endfor;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_via_route') :
        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);
?>
        <div class="modal-body">
            <div class="row">
                <?php //if ($TOTAL_USED_COUNT == 0) : 
                ?>
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmVIAROUTEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
                <?php /* else : ?>
                    <div class="text-center">
                        <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this record.</h6>
                    <p class="text-center"> Since its assigned to specific hotel with permission.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                <?php endif; */ ?>
            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_delete_via_route') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_HOTEL = sqlQUERY_LABEL("UPDATE `dvi_stored_location_via_routes` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `via_route_location_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());
        if ($delete_HOTEL) :

            $response['result'] = true;

        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_location') :
        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);
    ?>
        <div class="modal-body">
            <div class="row">
                <?php //if ($TOTAL_USED_COUNT == 0) : 
                ?>
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete this location? <br /> This process cannot be undo.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmLOCATIONDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
                <?php /* else : ?>
                    <div class="text-center">
                        <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this record.</h6>
                    <p class="text-center"> Since its assigned to specific hotel with permission.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                <?php endif; */ ?>
            </div>
        </div>
    <?php
    elseif ($_GET['type'] == 'confirm_delete_location') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $delete_HOTEL = sqlQUERY_LABEL("UPDATE `dvi_stored_locations` SET `deleted` = '1', `updatedon` = '" . date('Y-m-d H:i:s') . "' WHERE `location_ID` = '$_ID'") or die("#1-UNABLE_TO_DELETE_HOTEL:" . sqlERROR_LABEL());
        if ($delete_HOTEL) :

            $response['result'] = true;

        else :
            $response['result'] = false;
        // $response['response_error'] = true;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'add_route_suggestions') :

        $errors = [];
        $response = [];

        $nights_count = $_POST['nights_count'];
        $route_location = $_POST['route_location'];
        //print_r($route_location);
        //print_r($nights_count);die;
        if ($nights_count == '' || $nights_count == 0) :
            $errors['nights_required'] = 'true';    
        endif;

        if (count($route_location) == 0) :
            $errors['route_required'] = 'true';
        endif;

        $hid_location_id = $_POST['hid_location_id'];
        $hid_suggested_route_id = $_POST['hid_suggested_route_id'];

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
            $success = true;

            if ($hid_suggested_route_id != '' && $hid_suggested_route_id != 0) :
                
                $arrFields_routes = array('`no_of_nights`');
                $arrValues_routes = array("$nights_count");
                $sqlWhere_routes = " `stored_route_ID` = '$hid_suggested_route_id' ";

                if (sqlACTIONS("UPDATE", "dvi_stored_routes", $arrFields_routes, $arrValues_routes, $sqlWhere_routes)) :
                    //UPDATE  ROUTE TABLE
                    $hid_stored_route_location_id = $_POST['hid_stored_route_location_id'];
                    for ($i = 0; $i < count($route_location); $i++) :
                        $hid_stored_route_locationID =  $hid_stored_route_location_id[$i];
                        $location_name = trim($route_location[$i]);
                        $route_location_id = getSTOREDLOCATIONDETAILS($location_name, 'LOCATION_ID');
                        //$route_location_name = getSTOREDLOCATIONDETAILS($location_name, 'SOURCE_LOCATION');

                        if ($hid_stored_route_locationID != "") :

                            $arrFields_route_location = array('`route_location_id`', '`route_location_name`');
                            $arrValues_route_location = array("$route_location_id", "$location_name");
                           // $arrValues_route_location = array("$location_name","$route_location_id");
                            $sqlWhere_route_location = " `stored_route_location_ID` = '$hid_stored_route_locationID' ";

                            if (sqlACTIONS("UPDATE", "dvi_stored_route_location_details", $arrFields_route_location, $arrValues_route_location, $sqlWhere_route_location)) :
                            else :
                                $success = false;
                            endif;
                        else :

                            //$check_route_location_already_exists = sqlQUERY_LABEL("SELECT `stored_route_location_ID` FROM `dvi_stored_route_location_details` WHERE `route_location_id` ='$route_location_id' AND `stored_route_id` ='$hid_suggested_route_id' AND `deleted`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                           // if (sqlNUMOFROW_LABEL($check_route_location_already_exists) == 0) :

                                $arrFields_route_location = array('`stored_route_id`', '`route_location_id`', '`route_location_name`',  '`createdby`', '`status`');
                                $arrValues_route_location = array("$hid_suggested_route_id", "$route_location_id", "$location_name",  "1", "$logged_user_id", "1");

                                if (sqlACTIONS("INSERT", "dvi_stored_route_location_details", $arrFields_route_location, $arrValues_route_location, '')) :
                                else :
                                    $success = false;
                                endif;

                            //endif;
                        endif;
                    endfor;
                endif;
            else :
                //INSERT ROUTE TABLE

                $check_route_count = sqlQUERY_LABEL("SELECT `stored_route_ID` FROM `dvi_stored_routes` WHERE `location_id` ='$hid_location_id' AND `deleted`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
                $route_count = sqlNUMOFROW_LABEL($check_route_count) + 1;
                $route_name = "Route " . $route_count;

                $arrFields_routes = array('`location_id`', '`route_name`', '`no_of_nights`', '`createdby`', '`status`');
                $arrValues_routes = array("$hid_location_id", "$route_name",  "$nights_count", "$logged_user_id", "1");

                if (sqlACTIONS("INSERT", "dvi_stored_routes", $arrFields_routes, $arrValues_routes, '')) :
                    //SUCCESS
                    $stored_route_ID = sqlINSERTID_LABEL();
                    for ($i = 0; $i < count($route_location); $i++) :
                        $location_name = $route_location[$i];
                        $route_location_id = getSTOREDLOCATIONDETAILS($location_name, 'LOCATION_ID');

                        //$check_route_location_already_exists = sqlQUERY_LABEL("SELECT `stored_route_location_ID` FROM `dvi_stored_route_location_details` WHERE `route_location_id` ='$route_location_id' AND `stored_route_id` ='$stored_route_ID' AND `deleted`='0' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                       // if (sqlNUMOFROW_LABEL($check_route_location_already_exists) == 0) :

                            $arrFields_route_location = array('`stored_route_id`', '`route_location_id`', '`route_location_name`',  '`createdby`', '`status`');
                            $arrValues_route_location = array("$stored_route_ID", "$route_location_id", "$location_name",  "1", "$logged_user_id", "1");

                            if (sqlACTIONS("INSERT", "dvi_stored_route_location_details", $arrFields_route_location, $arrValues_route_location, '')) :
                            else :
                                $success = false;
                            endif;

                       // endif;
                    endfor;

                else :
                    $success = false;
                endif;

            endif;

            if ($success == true) :
                //SUCCESS
                $response['result'] = true;
            else :
                $response['result'] = false;
                $response['success'] = false;
                $errors['route_not_available'] = true;
                $response['errors'] = $errors;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_route') :

        $ID = $_GET['ID'];
        //SANITIZE
        $ID = $validation_globalclass->sanitize($ID);
    ?>
        <div class="modal-body">
            <div class="row">
                <?php //if ($TOTAL_USED_COUNT == 0) : 
                ?>
                <div class="text-center">
                    <svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
                        <path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
                <p class="text-center">Do you really want to delete these record? <br /> This process cannot be undone.</p>
                <div class="text-center pb-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" onclick="confirmROUTEDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
                </div>
                <?php /* else : ?>
                    <div class="text-center">
                        <svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this record.</h6>
                    <p class="text-center"> Since its assigned to specific hotel with permission.</p>
                    <div class="text-center pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                <?php endif; */ ?>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'confirm_delete_route') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $sqlwhere = " `stored_route_ID` = '$_ID'";
        if (sqlACTIONS("DELETE", "dvi_stored_routes", '', '', $sqlwhere)) :

            $sqlwhere_loc = " `stored_route_id` = '$_ID'";
            if (sqlACTIONS("DELETE", "dvi_stored_route_location_details", '', '', $sqlwhere_loc)) :
                $response['result'] = true;
            endif;

        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'confirm_delete_route_location') :

        $errors = [];
        $response = [];

        $_ID = $_POST['_ID'];

        //SANITIZE
        $_ID = $validation_globalclass->sanitize($_ID);

        $selected_query = sqlQUERY_LABEL("SELECT `stored_route_id`  FROM `dvi_stored_route_location_details` WHERE `stored_route_location_ID` = '$_ID' AND `deleted` = '0' AND `status` = '1' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_location_data = sqlFETCHARRAY_LABEL($selected_query)) :
                $stored_route_id = $fetch_location_data['stored_route_id'];
            endwhile;
        endif;


        $sqlwhere_loc = " `stored_route_location_ID` = '$_ID'";
        if (sqlACTIONS("DELETE", "dvi_stored_route_location_details", '', '', $sqlwhere_loc)) :
            $update_nightsquery = sqlQUERY_LABEL("UPDATE `dvi_stored_routes` 
                                SET `no_of_nights` = `no_of_nights` - 1 
                                WHERE `stored_route_id` = '$stored_route_id' 
                                AND `deleted` = '0' 
                                AND `status` = '1'") 
                                or die("#1-UNABLE_TO_UPDATE_DATA:" . sqlERROR_LABEL());

            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_location_name') :

        $response = [];
        $errors = [];

        $old_location_name = htmlentities(trim($_POST['old_location_name']));
        $new_location_name = trim($_POST['new_location_name']);

        $arrFields_source_location = array('`source_location`');
        $arrValues_source_location = array("$new_location_name");
        $sqlWhere_source_location = " `source_location` = '$old_location_name'";
        if (sqlACTIONS("UPDATE", "dvi_stored_locations", $arrFields_source_location, $arrValues_source_location, $sqlWhere_source_location)) :
            $arrFields_destination_location = array('`destination_location`');
            $arrValues_destination_location = array("$new_location_name");
            $sqlWhere_destination_location = " `destination_location` = '$old_location_name' ";
            if (sqlACTIONS("UPDATE", "dvi_stored_locations", $arrFields_destination_location, $arrValues_destination_location, $sqlWhere_destination_location)) :
                $arrFields_viaroute_location = array('`via_route_location`');
                $arrValues_viaroute_location = array("$new_location_name");
                $sqlWhere_viaroute_location = " `via_route_location` = '$old_location_name'";
                if (sqlACTIONS("UPDATE", "dvi_stored_location_via_routes", $arrFields_viaroute_location, $arrValues_viaroute_location, $sqlWhere_viaroute_location)) :
                    //UPDATE LOCATION NAME IN HOTSPOT TABLE
                    // Fetch hotspots containing old location (anywhere)
                    $normalized_old = strtolower(str_replace(' ', '', $old_location_name)); // remove spaces, normalize

                    $hotspot_query = sqlQUERY_LABEL("
                        SELECT hotspot_ID, hotspot_location  
                        FROM dvi_hotspot_place  
                        WHERE REPLACE(LOWER(hotspot_location), ' ', '') LIKE '%$normalized_old%'
                        AND deleted = '0'
                    ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                    if (sqlNUMOFROW_LABEL($hotspot_query) > 0) {

                        while ($row = sqlFETCHARRAY_LABEL($hotspot_query)) {

                            $hotspot_id = $row['hotspot_ID'];
                            $locations  = explode('|', $row['hotspot_location']);
                            $changed    = false;

                            foreach ($locations as &$segment) {

                                // Normalize the segment for comparison
                                $normalized_segment = strtolower(str_replace(' ', '', trim($segment)));

                                // Match only exact location ignoring spaces & casing
                                if ($normalized_segment === $normalized_old) {
                                    $segment = $new_location_name;
                                    $changed = true;
                                }
                            }

                            // Only update if a change was detected
                            if ($changed) {
                                $updated_locations = implode('|', $locations);

                                sqlQUERY_LABEL("
                                    UPDATE dvi_hotspot_place 
                                    SET hotspot_location = '$updated_locations',
                                        updatedon = NOW()
                                    WHERE hotspot_ID = '$hotspot_id'
                                ") or die("#2-UNABLE_TO_UPDATE_DATA:" . sqlERROR_LABEL());
                            }
                        }
                    }

                    //UPDATE LOCATION NAME IN dvi_stored_route_location_details TABLE
                    $update_route_query = sqlQUERY_LABEL(" UPDATE dvi_stored_route_location_details  SET route_location_name = '$new_location_name', updatedon = NOW()  WHERE REPLACE(route_location_name, ' ', '') LIKE '%" . str_replace(' ', '', $old_location_name) . "%' AND deleted = '0'") or die("#2-UNABLE_TO_UPDATE_DATA:" . sqlERROR_LABEL());
                    $response['success'] = true;
                    $response['result'] = true;
                    $response['message'] = "Locations updated successfully.";
                else :
                    $response['success'] = false;
                    $response['result'] = false;
                    $response['message'] = "Failed to update via route locations.";
                endif;
            else :
                $response['success'] = false;
                $response['result'] = false;
                $response['message'] = "Failed to update via route locations.";
            endif;
        else :
            $response['success'] = false;
            $response['result'] = false;
            $response['message'] = "Failed to update source and destination locations.";
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_location_name') :

        $response = [];
        $errors = [];

        $location_name = htmlentities(trim($_POST['location_name']));

        if ($location_name) :

            $delete_source_location = sqlQUERY_LABEL("DELETE FROM `dvi_stored_locations` WHERE `source_location` = '$location_name'") or die("#1-UNABLE_TO_DELETE_SOURCE_LOCATION:" . sqlERROR_LABEL());
            $delete_destination_location = sqlQUERY_LABEL("DELETE FROM `dvi_stored_locations` WHERE `destination_location` = '$location_name'") or die("#1-UNABLE_TO_DELETE_DESTINATION_LOCATION:" . sqlERROR_LABEL());
            $delete_via_route_location = sqlQUERY_LABEL("DELETE FROM `dvi_stored_location_via_routes` WHERE `via_route_location` = '$location_name'") or die("#1-UNABLE_TO_DELETE_VIA_ROUTE_LOCATION:" . sqlERROR_LABEL());

            $response['success'] = true;
            $response['result'] = true;
            $response['message'] = "Locations deleted successfully.";

        else :
            $response['success'] = false;
            $response['result'] = false;
            $response['message'] = "Failed to delete locations.";
        endif;

        echo json_encode($response);
    endif;

else :
    echo "Request Ignored";
endif;
