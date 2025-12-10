    <?php

    $response = [];
    $eligible_hotspots = [];
    $conflicting_hotspots = []; // Array to store conflicting hotspot IDs
    $errors = [];

    $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
    $itinerary_route_ID = $_POST['itinerary_route_ID'];
    $hotspot_ID = $_POST['hotspot_ID'];
    $requested_hotspot_ID = $_POST['hotspot_ID'];

    if (empty($itinerary_plan_ID) || empty($itinerary_route_ID) || empty($hotspot_ID)) :
        $errors['something_went_wrong'] = "Something went wrong. Please try again Later...";
    endif;

    $selected_hotspot_query = sqlQUERY_LABEL("SELECT `hotspot_ID`, `hotspot_order`, `hotspot_plan_own_way` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '3' ORDER BY `hotspot_order` DESC LIMIT 1") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($selected_hotspot_query) > 0) :
        while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($selected_hotspot_query)) :
            $get_last_hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
            $hotspot_order = $fetch_hotspot_data['hotspot_order'];
            $hotspot_plan_own_way = $fetch_hotspot_data['hotspot_plan_own_way'];
        endwhile;
    endif;

    $selected_hotspot_query_data = sqlQUERY_LABEL("SELECT `hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '4' ORDER BY `hotspot_order` DESC LIMIT 1") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($selected_hotspot_query_data) > 0) :
        while ($fetch_hotspot_time_data = sqlFETCHARRAY_LABEL($selected_hotspot_query_data)) :
            $hotspot_siteseeing_travel_start_time = $fetch_hotspot_time_data['hotspot_end_time'];
        endwhile;
    endif;

    $route_end_time = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'route_end_time');
    $departure_location = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
    $selected_NEXT_VISITING_PLACE = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location');
    $selected_DIRECT_DESTINATION_VISIT_CHECK = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'direct_to_next_visiting_place');
    $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');
    $departure_type = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'departure_type');
    $total_adult = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_adult');
    $total_children = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_children');
    $total_infants = get_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, 'total_infants');

    $get_starting_location_item_type = get_ITINEARY_ROUTE_HOTSPOT_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_item_type');
    $hotspot_siteseeing_travel_start_time = get_ITINEARY_ROUTE_HOTSPOT_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_item_type_endtime');

    $start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');

    if ($get_starting_location_item_type == 1) :
        $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
        $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');
        $staring_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');
        $hotspot_order = 1;
    elseif ($get_starting_location_item_type == 2) :
        $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
        $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
        $staring_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');
        $hotspot_order = 2;
    endif;

    // Initialize the array to store existing hotspot IDs
    $array_of_existing_hotspot_ID = [];
    $select_existing_itineary_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
    while ($fetch_existing_hotspot_data = sqlFETCHARRAY_LABEL($select_existing_itineary_hotspot_details)) :
        $array_of_existing_hotspot_ID[] = $fetch_existing_hotspot_data['hotspot_ID'];
    endwhile;

    // Add the new hotspot ID to the existing array
    $array_of_existing_hotspot_ID[] = $hotspot_ID;

    // Remove zeros from the array
    $array_of_existing_hotspot_ID = array_filter($array_of_existing_hotspot_ID);

    // Remove duplicate values from the array
    $array_of_existing_hotspot_ID = array_unique($array_of_existing_hotspot_ID);

    $array_of_existing_hotspot_ID = array_values($array_of_existing_hotspot_ID);

    $comma_sepearated_hotspot_ID = implode(',', $array_of_existing_hotspot_ID);

    // Convert the date string to a Unix timestamp using strtotime
    $timestamp = strtotime($itinerary_route_date);

    if ($timestamp !== false) :
        // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
        $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
    endif;

    #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
    $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_PLACE.`hotspot_ID` IN ($comma_sepearated_hotspot_ID) AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' GROUP BY HOTSPOT_PLACE.`hotspot_ID`") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
    $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

    // Initialize variables for the starting location
    $previous_hotspot_location = $staring_location_name;

    if ($select_hotspot_details_num_rows_count > 0) :
        $suitable_hotspots = array();
        //INITIALIZE AN ARRAY TO STORE UNIQUE HOTSPOT IDs
        $uniqueHotspotIDs = [];
        while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)) :

            // CREATE A UNIQUE IDENTIFIER FOR THE CURRENT HOTSPOT DATA
            $hotspotDataIdentifier = serialize($fetch_hotspot_data);

            // CHECK IF THE HOTSPOT DATA HAS ALREADY BEEN ADDED
            if (!isset($uniqueHotspots[$hotspotDataIdentifier])) :
                // IF NOT, ADD IT TO THE ARRAY AND PROCEED WITH ADDING THE HOTSPOT TO THE SUITABLE HOTSPOTS ARRAY
                $uniqueHotspots[$hotspotDataIdentifier] = true;
                //EXTRACT HOTSPOT DATA
                $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];

                // Determine the travel location type
                $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                // CALCULATE THE DISTANCE AND DURATION FROM THE STARTING LOCATION
                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                // STORE THE HOTSPOT DATA IN THE SUITABLE HOTSPOTS ARRAY
                $suitable_hotspots[] = array(
                    'hotspot_ID' => $hotspot_ID,
                    'hotspot_name' => $hotspot_name,
                    'hotspot_duration' => $hotspot_duration,
                    'hotspot_latitude' => $hotspot_latitude,
                    'hotspot_longitude' => $hotspot_longitude,
                    'hotspot_distance' => $get_hotspot_travelling_distance,
                    'hotspot_location' => $hotspot_location,
                );
            endif;
        endwhile;

        //SORT SUITABLE HOTSPOTS BY DISTANCE
        usort($suitable_hotspots, function ($a, $b) {
            return $a['hotspot_distance'] - $b['hotspot_distance'];
        });

        $requested_hotspot_added = false;

        //EXTRACT THE SUITABLE HOTSPOTS ARRAY
        foreach ($suitable_hotspots as $hotspot) :

            $suitable_hotspot_ID = $hotspot['hotspot_ID'];
            $hotspot_travel_time = $hotspot['hotspot_travel_time'];
            $hotspot_distance = $hotspot['hotspot_distance'];
            $hotspot_latitude = $hotspot['hotspot_latitude'];
            $hotspot_longitude = $hotspot['hotspot_longitude'];
            $hotspot_duration = $hotspot['hotspot_duration'];
            $hotspot_name = $hotspot['hotspot_name'];
            $hotspot_location = $hotspot['hotspot_location'];

            // Split the time string into its components
            list($hours, $minutes, $seconds) = explode(":", $hotspot_duration);

            // Convert minutes to a fraction of an hour
            $decimalMinutes = $minutes / 60;

            // Calculate the total hours
            $totalHours = $hours + $decimalMinutes;

            $total_spend_duration = $totalHours . " Hrs";

            // Determine the travel location type
            $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
            $hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');
            $hotspot_traveling_time = $result['duration'];

            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
            preg_match('/(\d+) hour/', $hotspot_traveling_time, $hoursMatch);
            preg_match('/(\d+) mins/', $hotspot_traveling_time, $minutesMatch);

            // INITIALIZE HOURS AND MINUTES TO ZERO
            $hours = 0;
            $minutes = 0;

            $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
            $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

            // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59
            $extraHours = floor($minutes / 60);
            $hours += $extraHours;
            $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

            // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
            $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
            $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

            // FORMAT THE TIME AS H:i:s
            $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

            //CALCAULATE THE DURATIONS IN SECONDS
            $totalSeconds = ($hours * 3600) + ($minutes * 60);

            //CONVERT {hotspot_siteseeing_travel_start_time} TO SECONDS
            $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

            //ADD THE DURATION TO THE START TIME
            $newTimeInSeconds = $startTimeInSeconds + $totalSeconds;

            //CONVERT THE NEW TIME TO {hotspot_siteseeing_travel_end_time} H:i:s FORMAT
            $hotspot_siteseeing_travel_end_time = date('H:i:s', $newTimeInSeconds);

            $get_hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
            $get_hotspot_ending_time_seconds = strtotime($hotspot_siteseeing_travel_end_time) + $get_hotspot_duration_seconds;

            //CONVERT THE NEW TIME TO {hotspot_siteseeing_end_time} H:i:s FORMAT
            $get_hotspot_ending_time = date('H:i:s', $get_hotspot_ending_time_seconds);

            $exceeds_route_end_time = checkRouteEndTime($get_hotspot_ending_time, $route_end_time);

            $operating_hours_available = checkHOTSPOTOPERATINGHOURS($suitable_hotspot_ID, $dayOfWeekNumeric, $hotspot_siteseeing_travel_end_time, $get_hotspot_ending_time, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id);

            $get_hotspot_operating_hours = getHOTSPOT_OPERATING_HOURS($suitable_hotspot_ID, $dayOfWeekNumeric, 'get_hotspot_operating_hours');

            /* echo "suitable_hotspot_ID => $suitable_hotspot_ID | hotspot_siteseeing_travel_start_time => $hotspot_siteseeing_travel_start_time | get_hotspot_ending_time => $get_hotspot_ending_time";
    echo "<br>"; */

            if ($requested_hotspot_added == false):
                if (!$operating_hours_available) :
                    $get_hotspot_operating_hours = getHOTSPOT_OPERATING_HOURS($suitable_hotspot_ID, $dayOfWeekNumeric, 'get_hotspot_operating_hours');
                    // If the current hotspot is not the requested one, consider it as a conflicting hotspot
                    if ($suitable_hotspot_ID != $requested_hotspot_ID) :
                        $conflicting_hotspots[] = $suitable_hotspot_ID;
                    endif;
                    $errors['hotspot_operating_hours_not_available'] = "Sorry, $hotspot_name operates during the following hours: <br>$get_hotspot_operating_hours<br><br>Total Spending Duration: $totalHours Hrs";
                    $errors['hotspot_operating_hours_not_available_dayOfWeekNumeric'] = $dayOfWeekNumeric;
                    $errors['hotspot_operating_hours_not_available_hotspot_ID'] = $conflicting_hotspots;
                    $errors['hotspot_operating_hours_not_available_itinerary_plan_ID'] = $itinerary_plan_ID;
                    $errors['hotspot_operating_hours_not_available_itinerary_route_ID'] = $itinerary_route_ID;
                    $errors['try_to_add_new_hotspot_ID'] = $requested_hotspot_ID;
                else :
                    $eligible_hotspots[] = $hotspot;
                    if ($suitable_hotspot_ID != $requested_hotspot_ID) :
                        $conflicting_hotspots[] = $suitable_hotspot_ID;
                        $errors['hotspot_operating_hours_not_available'] = "Sorry, $hotspot_name operates during the following hours: <br>$get_hotspot_operating_hours<br><br>Total Spending Duration: $totalHours Hrs";
                        $errors['hotspot_operating_hours_not_available_dayOfWeekNumeric'] = $dayOfWeekNumeric;
                        $errors['hotspot_operating_hours_not_available_hotspot_ID'] = $conflicting_hotspots;
                        $errors['hotspot_operating_hours_not_available_itinerary_plan_ID'] = $itinerary_plan_ID;
                        $errors['hotspot_operating_hours_not_available_itinerary_route_ID'] = $itinerary_route_ID;
                        $errors['try_to_add_new_hotspot_ID'] = $requested_hotspot_ID;
                    else:
                        $requested_hotspot_added = true;
                    endif;
                endif;
            endif;
            $hotspot_siteseeing_travel_start_time = $get_hotspot_ending_time;
        endforeach;

        if (!empty($conflicting_hotspots)):
            $response['success'] = false;
            $response['errors'] = $errors;
        endif;
        print_r($conflicting_hotspots);
        exit;

        // Step 2: Allocate Hotspots
        if (!empty($errors)) :
            $response['success'] = false;
            $response['errors'] = $errors;
        else:
            $response['success'] = true;
            foreach ($eligible_hotspots as $hotspot) :
                $hotspot_ID = $hotspot['hotspot_ID'];
                $hotspot_duration = $hotspot['hotspot_duration'];
                $hotspot_location = $hotspot['hotspot_location'];
                $hotspot_latitude = $hotspot['hotspot_latitude'];
                $hotspot_longitude = $hotspot['hotspot_longitude'];
                $hotspot_siteseeing_start_time = $hotspot['hotspot_siteseeing_travel_start_time'];

                $hotspot_amout = 0;
                $hotspot_adult_entry_cost = 0;
                $hotspot_child_entry_cost = 0;
                $hotspot_infant_entry_cost = 0;
                $hotspot_foreign_adult_entry_cost = 0;
                $hotspot_foreign_child_entry_cost = 0;
                $hotspot_foreign_infant_entry_cost = 0;

                // Check if entry ticket is required
                if ($entry_ticket_required == 1) :
                    $total_adult = $total_adult;
                    $total_children = $total_children;
                    $total_infants = $total_infants;

                    $hotspot_adult_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_adult_entry_cost');
                    $hotspot_child_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_child_entry_cost');
                    $hotspot_infant_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_infant_entry_cost');
                    $hotspot_foreign_adult_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_foreign_adult_entry_cost');
                    $hotspot_foreign_child_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_foreign_child_entry_cost');
                    $hotspot_foreign_infant_entry_cost = getHOTSPOT_CHARGES_DETAILS($hotspot_ID, 'hotspot_foreign_infant_entry_cost');

                    if ($nationality != 101) :
                        $total_adult_cost = $total_adult * $hotspot_foreign_adult_entry_cost;
                        $total_child_cost = $total_children * $hotspot_foreign_child_entry_cost;
                        $total_infant_cost = $total_infants * $hotspot_foreign_infant_entry_cost;

                        $hotspot_amout = $total_adult_cost + $total_child_cost + $total_infant_cost;
                    else :
                        $total_adult_cost = $total_adult * $hotspot_adult_entry_cost;
                        $total_child_cost = $total_children * $hotspot_child_entry_cost;
                        $total_infant_cost = $total_infants * $hotspot_infant_entry_cost;

                        $hotspot_amout = $total_adult_cost + $total_child_cost + $total_infant_cost;
                    endif;
                endif;

                // Check if hotspot is already in the itinerary
                $check_hotspot_travel_already_added = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `hotspot_ID` = '$hotspot_ID' AND `item_type`='3'");
                $check_hotspot_travel_already_added_num_rows = sqlNUMOFROW_LABEL($check_hotspot_travel_already_added);

                $check_hotspot_siteseeing_already_added = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `hotspot_ID` = '$hotspot_ID' AND `item_type`='4'");
                $check_hotspot_siteseeing_already_added_num_rows = sqlNUMOFROW_LABEL($check_hotspot_siteseeing_already_added);

                if ($check_hotspot_travel_already_added_num_rows == 0 && $check_hotspot_siteseeing_already_added_num_rows == 0) :
                    // Insert or update logic
                    $hotspot_order++;
                    $route_hotspot_traveling_arrFields = array(
                        '`itinerary_plan_ID`',
                        '`itinerary_route_ID`',
                        '`item_type`',
                        '`hotspot_order`',
                        '`hotspot_ID`',
                        '`hotspot_traveling_time`',
                        '`hotspot_travelling_distance`',
                        '`hotspot_start_time`',
                        '`hotspot_end_time`',
                        '`createdby`',
                        '`status`'
                    );
                    $route_hotspot_traveling_arrValues = array(
                        "$itinerary_plan_ID",
                        "$itinerary_route_ID",
                        "3",
                        "$hotspot_order",
                        "$hotspot_ID",
                        "$duration_formatted",
                        "$hotspot_travelling_distance",
                        "$hotspot_siteseeing_start_time",
                        "$hotspot_siteseeing_end_time",
                        "$logged_user_id",
                        "1"
                    );

                    // Insert the itinerary hotspot traveling data
                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_traveling_arrFields, $route_hotspot_traveling_arrValues, '')) :
                        // Update the start time for the next hotspot
                        $hotspot_siteseeing_start_time = $hotspot_siteseeing_end_time;

                        // Convert the duration to seconds and add it to the start time
                        $hotspot_duration_seconds = strtotime("1970-01-01 $hotspot_duration UTC");
                        $hotspot_siteseeing_new_end_time = strtotime($hotspot_siteseeing_start_time) + $hotspot_duration_seconds;

                        // Convert the new time to H:i:s format
                        $hotspot_siteseeing_end_time = date('H:i:s', $hotspot_siteseeing_new_end_time);

                        $route_hotspot_arrFields = array(
                            '`itinerary_plan_ID`',
                            '`itinerary_route_ID`',
                            '`item_type`',
                            '`hotspot_order`',
                            '`hotspot_ID`',
                            '`hotspot_adult_entry_cost`',
                            '`hotspot_child_entry_cost`',
                            '`hotspot_infant_entry_cost`',
                            '`hotspot_foreign_adult_entry_cost`',
                            '`hotspot_foreign_child_entry_cost`',
                            '`hotspot_foreign_infant_entry_cost`',
                            '`hotspot_amout`',
                            '`hotspot_traveling_time`',
                            '`hotspot_start_time`',
                            '`hotspot_end_time`',
                            '`createdby`',
                            '`status`'
                        );
                        $route_hotspot_arrValues = array(
                            "$itinerary_plan_ID",
                            "$itinerary_route_ID",
                            "4",
                            "$hotspot_order",
                            "$hotspot_ID",
                            "$hotspot_adult_entry_cost",
                            "$hotspot_child_entry_cost",
                            "$hotspot_infant_entry_cost",
                            "$hotspot_foreign_adult_entry_cost",
                            "$hotspot_foreign_child_entry_cost",
                            "$hotspot_foreign_infant_entry_cost",
                            "$hotspot_amout",
                            "$hotspot_duration",
                            "$hotspot_siteseeing_start_time",
                            "$hotspot_siteseeing_end_time",
                            "$logged_user_id",
                            "1"
                        );

                        // Insert the itinerary hotspot sightseeing place data
                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_arrFields, $route_hotspot_arrValues, '')) :
                            $hotspot_siteseeing_travel_start_time = $hotspot_siteseeing_end_time;
                            $staring_location_latitude = $hotspot_latitude;
                            $staring_location_longtitude = $hotspot_longitude;
                            $previous_hotspot_location = $hotspot_location;
                        endif;
                    endif;
                else :
                    // Update existing records
                    if ($check_hotspot_travel_already_added_num_rows > 0) :
                        while ($fetch_hotspot_travel_data = sqlFETCHARRAY_LABEL($check_hotspot_travel_already_added)) :
                            $route_hotspot_ID = $fetch_hotspot_travel_data['route_hotspot_ID'];
                        endwhile;

                        $hotspot_order++;
                        $route_hotspot_traveling_arrFields = array(
                            '`itinerary_plan_ID`',
                            '`itinerary_route_ID`',
                            '`item_type`',
                            '`hotspot_order`',
                            '`hotspot_ID`',
                            '`hotspot_traveling_time`',
                            '`hotspot_travelling_distance`',
                            '`hotspot_start_time`',
                            '`hotspot_end_time`',
                            '`createdby`',
                            '`status`'
                        );
                        $route_hotspot_traveling_arrValues = array(
                            "$itinerary_plan_ID",
                            "$itinerary_route_ID",
                            "3",
                            "$hotspot_order",
                            "$hotspot_ID",
                            "$duration_formatted",
                            "$hotspot_travelling_distance",
                            "$hotspot_siteseeing_start_time",
                            "$hotspot_siteseeing_end_time",
                            "$logged_user_id",
                            "1"
                        );
                        $travel_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `hotspot_ID` = '$hotspot_ID' AND `item_type`= '3' ";
                        sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_traveling_arrFields, $route_hotspot_traveling_arrValues, $travel_sqlwhere);
                    endif;

                    // Update sightseeing data
                    if ($check_hotspot_siteseeing_already_added_num_rows > 0) :
                        $route_hotspot_arrFields = array(
                            '`itinerary_plan_ID`',
                            '`itinerary_route_ID`',
                            '`item_type`',
                            '`hotspot_order`',
                            '`hotspot_ID`',
                            '`hotspot_adult_entry_cost`',
                            '`hotspot_child_entry_cost`',
                            '`hotspot_infant_entry_cost`',
                            '`hotspot_foreign_adult_entry_cost`',
                            '`hotspot_foreign_child_entry_cost`',
                            '`hotspot_foreign_infant_entry_cost`',
                            '`hotspot_amout`',
                            '`hotspot_traveling_time`',
                            '`hotspot_start_time`',
                            '`hotspot_end_time`',
                            '`createdby`',
                            '`status`'
                        );
                        $route_hotspot_arrValues = array(
                            "$itinerary_plan_ID",
                            "$itinerary_route_ID",
                            "4",
                            "$hotspot_order",
                            "$hotspot_ID",
                            "$hotspot_adult_entry_cost",
                            "$hotspot_child_entry_cost",
                            "$hotspot_infant_entry_cost",
                            "$hotspot_foreign_adult_entry_cost",
                            "$hotspot_foreign_child_entry_cost",
                            "$hotspot_foreign_infant_entry_cost",
                            "$hotspot_amout",
                            "$hotspot_duration",
                            "$hotspot_siteseeing_start_time",
                            "$hotspot_siteseeing_end_time",
                            "$logged_user_id",
                            "1"
                        );
                        $sightseeing_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `hotspot_ID` = '$hotspot_ID' AND `item_type`='4' ";
                        sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_arrFields, $route_hotspot_arrValues, $sightseeing_sqlwhere);
                    endif;
                endif;
            endforeach;

            if ($response['success'] == true) :

                $get_last_route_id_from_this_itinerary_plan = sqlQUERY_LABEL("SELECT MAX(`itinerary_route_ID`) AS max_route_id FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                $fetch_last_route_id_from_this_itineary_plan = sqlFETCHARRAY_LABEL($get_last_route_id_from_this_itinerary_plan);
                $last_itinerary_route_ID = $fetch_last_route_id_from_this_itineary_plan['max_route_id'];

                //INSERT THE END OF THE TRIP DEPARTURE START TIME
                if ($departure_location == $selected_NEXT_VISITING_PLACE && $last_itinerary_route_ID == $itinerary_route_ID) :
                    $hotspot_order = $hotspot_order;

                    //Determine the buffer time based on the departure_type [1 - By Flight | 2 - By Train | 3 - By Road]
                    switch ($departure_type):
                        case 1: // By Flight
                            $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                            break;
                        case 2: // By Train
                            $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                            break;
                        case 3: // By Road
                            $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                            break;
                        default:
                            $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                    endswitch;

                    $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                    $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                    $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                    // Determine the travel location type
                    $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                    // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                    $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                    $destination_traveling_time = $result['duration'];

                    // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                    preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                    preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                    // INITIALIZE HOURS AND MINUTES TO ZERO
                    $hours = 0;
                    $minutes = 0;

                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                    // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59
                    $extraHours = floor($minutes / 60);
                    $hours += $extraHours;
                    $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                    // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                    $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                    $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                    // FORMAT THE TIME AS H:i:s
                    $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                    //CALCAULATE THE DURATIONS IN SECONDS
                    $totalSeconds = ($hours * 3600) + ($minutes * 60);

                    //ADD THE DURATION TO THE START TIME
                    $newTimeInSeconds = $totalSeconds + strtotime($itinerary_travel_type_buffer_time);

                    //CONVERT THE NEW TIME TO {destination_total_duration} H:i:s FORMAT
                    $destination_total_duration = date('H:i:s', $newTimeInSeconds);

                    // Convert hotspot_siteseeing_travel_start_time to seconds
                    $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                    // Convert destination_total_duration to seconds
                    list($hours, $minutes, $seconds) = sscanf($destination_total_duration, "%d:%d:%d");
                    $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                    // Add the duration and buffer time to the start time
                    $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds + $bufferInSeconds;

                    // Convert the total time back to H:i:s format
                    $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                    $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                    $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                    $hotspot_order++;
                    $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                    $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$destination_total_duration", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                    if ($select_itineary_hotspot_return_departure_location_count > 0) :
                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                        $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                        endif;
                    else :
                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                        endif;
                    endif;

                    if ($destination_travel_end_time <= $route_end_time) :
                        $itinerary_route_details_arrFields = array('`route_end_time`');
                        $itinerary_route_details_arrValues = array("$destination_travel_end_time");
                        $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";

                        //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :

                            $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'itinerary_route_date');

                            $new_trip_end_date_n_time = $itinerary_route_date . ' ' . $destination_travel_end_time;

                            $update_itineary_plan_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_details` SET `trip_end_date_and_time`='$new_trip_end_date_n_time' WHERE `deleted`='0' and `itinerary_plan_ID`='$itinerary_plan_ID'") or die(" #3-UNABLE_TO_UPDATE_ITINEARY_PLAN_DETAILS_DETAILS:" . sqlERROR_LABEL());
                        endif;
                    endif;

                else :

                    $itinerary_travel_type_buffer_time = "00:00:00";

                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                        $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                        $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                        $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                        // Determine the travel location type
                        $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                        // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                        $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                        $destination_traveling_time = $result['duration'];

                        // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                        preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                        preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                        // INITIALIZE HOURS AND MINUTES TO ZERO
                        $hours = 0;
                        $minutes = 0;

                        $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                        $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                        // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59
                        $extraHours = floor($minutes / 60);
                        $hours += $extraHours;
                        $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                        // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                        $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                        $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                        // FORMAT THE TIME AS H:i:s
                        $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                        // Convert hotspot_start_time to seconds
                        $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                        // Convert destination_total_duration to seconds
                        list($hours, $minutes, $seconds) = sscanf($duration_formatted, "%d:%d:%d");
                        $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                        // Add the duration and buffer time to the start time
                        $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds;

                        // Convert the total time back to H:i:s format
                        $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                        $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted`='0' and `itinerary_plan_ID`='$itinerary_plan_ID' AND `itinerary_route_ID`='$itinerary_route_ID' AND `item_type`='5'") or die(" #3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                        $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                        $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                        $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "5", "$hotspot_order", "$duration_formatted", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                        if ($select_itineary_hotspot_return_departure_location_count > 0) :
                            $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                            $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                            $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '5' ";
                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                            endif;
                        else :
                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                            endif;
                        endif;
                        $hotspot_siteseeing_travel_start_time = $destination_travel_end_time;
                    endif;

                    $hotspot_order = $hotspot_order;

                    $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                    $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                    $hotspot_order++;
                    $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                    $route_hotspot_return_to_hotel_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "6", "$hotspot_order", "$hotspot_siteseeing_travel_start_time", "$hotspot_siteseeing_travel_start_time", "$logged_user_id", "1");

                    if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                        $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                        $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                        $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                        endif;
                    else :
                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                        endif;
                    endif;

                    if ($hotspot_siteseeing_travel_start_time >= $route_end_time) :
                        $itinerary_route_details_arrFields = array('`route_end_time`');
                        $itinerary_route_details_arrValues = array("$hotspot_siteseeing_travel_start_time");
                        $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";

                        //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :
                        endif;
                    endif;

                endif;
            endif;
        endif;
    endif;

    echo json_encode($response);
