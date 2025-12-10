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

// ini_set('display_errors', 1);
// ini_set('log_errors', 1);

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $PR_HOTSPOT_ID = $_GET['PR_HOTSPOT_ID'];
        $NXT_HOTSPOT_ID = $_GET['NXT_HOTSPOT_ID'];
        $PLAN_ID = $_GET['PLAN_ID'];
        $ROUTE_ID = $_GET['ROUTE_ID'];

        $select_previous_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_name`, `hotspot_description`, `hotspot_latitude`, `hotspot_longitude` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_ID` = '$PR_HOTSPOT_ID'") or die("#1-UNABLE_TO_COLLECT_PR_HOTSPOT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_previous_hotspot_data = sqlFETCHARRAY_LABEL($select_previous_hotspot_details)) :
            $previous_hotspot_name = $fetch_previous_hotspot_data['hotspot_name'];
            $previous_hotspot_description = $fetch_previous_hotspot_data['hotspot_description'];
            $previous_hotspot_latitude = $fetch_previous_hotspot_data['hotspot_latitude'];
            $previous_hotspot_longitude = $fetch_previous_hotspot_data['hotspot_longitude'];
        endwhile;

        $select_next_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_name`, `hotspot_description`, `hotspot_latitude`, `hotspot_longitude` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_ID` = '$NXT_HOTSPOT_ID'") or die("#1-UNABLE_TO_COLLECT_NXT_HOTSPOT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_next_hotspot_data = sqlFETCHARRAY_LABEL($select_next_hotspot_details)) :
            $next_hotspot_name = $fetch_next_hotspot_data['hotspot_name'];
            $next_hotspot_description = $fetch_next_hotspot_data['hotspot_description'];
            $next_hotspot_latitude = $fetch_next_hotspot_data['hotspot_latitude'];
            $next_hotspot_longitude = $fetch_next_hotspot_data['hotspot_longitude'];
        endwhile;

        $get_start_location_name = getITINEARYROUTE_DETAILS($PLAN_ID, $ROUTE_ID, 'location_name');
        $get_start_location_id = getITINEARYROUTE_DETAILS($PLAN_ID, $ROUTE_ID, 'get_starting_location_id');
        $get_start_longitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $get_start_location_id);
        $get_end_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $get_start_location_id);
        $get_itinerary_route_date = getITINEARYROUTE_DETAILS($PLAN_ID, $ROUTE_ID, 'itinerary_route_date');

        // Convert the date string to a Unix timestamp using strtotime
        $timestamp = strtotime($get_itinerary_route_date);

        if ($timestamp !== false) :
            // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
            $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;

            // If you want to get the day name (Sunday, Monday, etc.), you can use:
            $dayOfWeekName = date('l', $timestamp);
        //echo "Day of the week (name): $dayOfWeekName";
        endif;

        $check_previous_travelling_distance = calculateDistanceAndDuration($get_start_longitude, $get_end_longitude, $previous_hotspot_latitude, $previous_hotspot_longitude);

        $check_next_travelling_distance = calculateDistanceAndDuration($get_start_longitude, $get_end_longitude, $next_hotspot_latitude, $next_hotspot_longitude);

?>
        <div class="row">
            <div class="text-center">
                <svg class="icon-44 text-warning" width="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </div>
            <h4 class="text-center mt-3">Hotspot Distance Alert !!!</h4>
            <p class="text-start mt-3">Before proceeding, please note that <b><?= $next_hotspot_name; ?></b> is nearest to <b><?= $get_start_location_name; ?></b>. <br /><br />Since you have previously added <b><?= $previous_hotspot_name; ?></b> as a hotspot, we recommend visiting <b><?= $next_hotspot_name; ?></b> first and then proceeding to <b><?= $previous_hotspot_name; ?></b>.<br /><br /> If you agree with this approach, click 'Proceed'. Otherwise, feel free to decline and choose your own itinerary.</p>
            <div class="text-center mt-3 pb-0">
                <button type="button" class="btn btn-secondary" onclick="declineHOTSPOTDISTANCEALERT('<?= $PLAN_ID; ?>','<?= $ROUTE_ID; ?>','<?= $PR_HOTSPOT_ID; ?>','<?= $NXT_HOTSPOT_ID; ?>','<?= $dayOfWeekNumeric; ?>')">Decline</button>
                <button type="button" class="btn btn-primary" onclick="confirmHOTSPOTDISTANCEALERT('<?= $PLAN_ID; ?>','<?= $ROUTE_ID; ?>','<?= $PR_HOTSPOT_ID; ?>','<?= $NXT_HOTSPOT_ID; ?>','<?= $dayOfWeekNumeric; ?>')">Proceed</button>
            </div>
        </div>
<?php
    elseif ($_GET['type'] == 'proceed_hotspot_distance_alert') :

        $errors = [];
        $response = [];

        $_hotspot_id = $_POST['NXT_HOTSPOT_ID'];
        $_itinerary_route_ID = $_POST['ROUTE_ID'];
        $_itinerary_plan_ID = $_POST['PLAN_ID'];
        $dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];

        $select_next_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_latitude`, `hotspot_longitude` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_ID` = '$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_NXT_HOTSPOT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_next_hotspot_data = sqlFETCHARRAY_LABEL($select_next_hotspot_details)) :
            $next_hotspot_latitude = $fetch_next_hotspot_data['hotspot_latitude'];
            $next_hotspot_longitude = $fetch_next_hotspot_data['hotspot_longitude'];
        endwhile;

        $end_latitude = $next_hotspot_latitude;
        $end_longitude = $next_hotspot_longitude;
        $_dayOfWeekNumeric = $dayOfWeekNumeric;

        if (empty($_hotspot_id)) :
            $errors['hotspot_id_required'] = true;
        endif;
        if (empty($_itinerary_route_ID)) :
            $errors['itinerary_route_ID_required'] = true;
        endif;
        if (empty($_itinerary_plan_ID)) :
            $errors['itinerary_plan_ID_required'] = true;
        endif;
        if ($_dayOfWeekNumeric == '') :
            $errors['dayOfWeekNumeric_required'] = true;
        endif;

        $select_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_hotel_details_ID`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`,HOTSPOT_PLACES.`hotspot_duration`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' ORDER BY HOTSPOT_DETAILS.`hotspot_order` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        $total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
        if ($total_hotspot_list_num_rows_count > 0) :
            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                $hotspot_order = $fetch_hotspot_list_data['hotspot_order'] + 1;
                $hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
                $itinerary_plan_hotel_details_ID = $fetch_hotspot_list_data['itinerary_plan_hotel_details_ID'];
                $start_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
                $start_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
                $hotspot_traveling_time = $fetch_hotspot_list_data['hotspot_traveling_time'];
                $hotspot_travelling_distance = $fetch_hotspot_list_data['hotspot_travelling_distance'];
                $start_time = $fetch_hotspot_list_data['hotspot_start_time'];
                $end_time = $fetch_hotspot_list_data['hotspot_end_time'];
                $hotspot_duration = $fetch_hotspot_list_data['hotspot_duration'];
            endwhile;
        else :
            $hotspot_order = 1;
        endif;

        if ($hotspot_ID == 0 && $itinerary_plan_hotel_details_ID == 0) :
            $start_location_id = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'get_starting_location_id');
            $start_latitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $start_location_id);
            $start_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $start_location_id);
            $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);

            // Extract duration in hours and minutes from the result
            $duration_string = $travel_distance['duration'];

            // Extract hours and minutes from duration string
            list($hours, $minutes) = explode(' ', $duration_string);

            // Convert to float
            $hours = (float) $hours;
            $minutes = (float) $minutes;

            // Calculate total minutes
            $total_minutes = ($hours * 60) + $minutes;

            // Convert total minutes to hours and minutes
            $hours = floor($total_minutes / 60);
            $minutes = $total_minutes % 60;

            // Format the time
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);

            // Extract distance
            $_distance = round($travel_distance['distance'], 1);
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);
        else :
            if ($itinerary_plan_hotel_details_ID != 0) :
                $selected_itinerary_hotel_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_itinerary_plan_hotel_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID'") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
                $fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_itinerary_hotel_query);
                $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];

                $selected_query = sqlQUERY_LABEL("SELECT `hotel_longitude`, `hotel_latitude` FROM `dvi_hotel` where `hotel_id` = '$hotel_id'") or die("#getHOTEL_DETAILS: UNABLE_TO_GET_HOTEL_PLACE: " . sqlERROR_LABEL());
                while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                    $start_latitude = $fetch_data['hotel_latitude'];
                    $start_longitude = $fetch_data['hotel_longitude'];
                endwhile;

            endif;

            $select_added_hotspot_data = sqlQUERY_LABEL("SELECT `hotspot_order`, `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID`='$_itinerary_plan_ID' and `itinerary_route_ID`='$_itinerary_route_ID' AND `item_type` = '3'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
            $total_added_hotspot_num_rows_count = sqlNUMOFROW_LABEL($select_added_hotspot_data);

            $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);

            // Extract duration in hours and minutes from the result
            $duration_string = $travel_distance['duration'];

            // Extract hours and minutes from duration string
            list($hours, $minutes) = explode(' ', $duration_string);

            // Convert to float
            $hours = (float) $hours;
            $minutes = (float) $minutes;

            // Calculate total minutes
            $total_minutes = ($hours * 60) + $minutes;

            // Convert total minutes to hours and minutes
            $hours = floor($total_minutes / 60);
            $minutes = $total_minutes % 60;

            // Format the time
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);

            // Extract distance
            $_distance = round($travel_distance['distance'], 1);
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);
        endif;

        /* print_r($travel_distance);
        exit; */

        $direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'direct_to_next_visiting_place');
        $route_end_time = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_end_time');

        if ($direct_to_next_visiting_place == 1) :
            $item_type = 2;
        endif;

        $_hotspot_order = $hotspot_order;
        $_start_time = getITINEARY_ROUTE_HOTSPOT_DETAILS($item_type, $_itinerary_plan_ID, $_itinerary_route_ID, 'hotspot_end_time');
        $_end_time = getITINEARY_ROUTE_HOTSPOT_DETAILS($item_type, $_itinerary_plan_ID, $_itinerary_route_ID, 'hotspot_end_time');
        if ($_end_time == '') :
            $_end_time = date('H:i:s', strtotime(getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_start_time', "") . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
        endif;

        $_hotspot_id = $_hotspot_id;
        $_itinerary_route_ID = $_itinerary_route_ID;
        $_itinerary_plan_ID = $_itinerary_plan_ID;

        // Extract hours and minutes from the duration string
        preg_match('/(\d+) hour/', $_time, $hours_match);
        preg_match('/(\d+) min/', $_time, $minutes_match);

        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

        // Format the time as H:i:s
        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

        // Convert times to seconds
        $seconds1 = strtotime("1970-01-01 $_end_time UTC");
        $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

        $hotspot_start_time = $_end_time;
        $hotspot_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

        // Convert time strings to timestamps
        $route_end_timestamp = strtotime($route_end_time);
        $hotspot_end_timestamp = strtotime($hotspot_end_time);

        if ($route_end_timestamp <= $hotspot_end_timestamp) :
            $errors['hotspot_end_time_exceed'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call
            $response['success'] = true;

            $hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_ID`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
            $hotspot_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "2", "$_hotspot_id", "$_hotspot_order", "$formatted_time", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

            $select_itineary_hotspot_details = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '$_hotspot_id' AND `item_type` = '2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $select_tineary_hotspot_details_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_details);

            if ($select_tineary_hotspot_details_count == 0) :
                //INSERT ITINEARY ROUTE HOTSPOT DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, '')) :

                    $route_day_end_time = $route_end_time;

                    $select_hotspot_places_query = sqlQUERY_LABEL("SELECT `hotspot_timing_status` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' and HOTSPOT_PLACE.`hotspot_ID` = '$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((`hotspot_start_time` <= '$hotspot_end_time' AND `hotspot_end_time` >= '$hotspot_end_time') OR `hotspot_open_all_time` = '1')") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    $fetch_hotspot_time_avail_count_data = sqlNUMOFROW_LABEL($select_hotspot_places_query);
                    /* $fetch_hotspot_places_list_data = sqlFETCHARRAY_LABEL($select_hotspot_places_query);
					$hotspot_timing_status = $fetch_hotspot_places_list_data['hotspot_timing_status']; */

                    $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((`hotspot_start_time` <= '$hotspot_start_time' AND `hotspot_end_time` >= '$hotspot_start_time') OR `hotspot_open_all_time` = '1' )") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);

                    if ($total_itinerary_plan_rows_count > 0 && ($fetch_hotspot_time_avail_count_data > 0)) :

                        $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                            $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                            $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                            $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                        endwhile;

                        $select_hotspot_place_list_query = sqlQUERY_LABEL("SELECT `hotspot_duration`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        while ($fetch_hotspot_place_list_data = sqlFETCHARRAY_LABEL($select_hotspot_place_list_query)) :
                            $hotspot_total_adult_entry_cost = $fetch_hotspot_place_list_data['hotspot_adult_entry_cost'] * $total_adult;
                            $hotspot_total_child_entry_cost = $fetch_hotspot_place_list_data['hotspot_child_entry_cost'] * $total_children;
                            $hotspot_total_infant_entry_cost = $fetch_hotspot_place_list_data['hotspot_infant_entry_cost'] * $total_infants;
                            $hotspot_duration = $fetch_hotspot_place_list_data['hotspot_duration'];
                        endwhile;

                        $new_hotspots_start_time = $hotspot_end_time;

                        // Parse time strings into hours, minutes, and seconds
                        list($h1, $m1, $s1) = explode(':', $hotspot_end_time);
                        list($h2, $m2, $s2) = explode(':', $hotspot_duration);

                        // Add hours, minutes, and seconds separately
                        $total_hours = $h1 + $h2;
                        $total_minutes = $m1 + $m2;
                        $total_seconds = $s1 + $s2;

                        // Adjust minutes and seconds if they exceed their respective limits
                        if ($total_seconds >= 60) :
                            $total_seconds -= 60;
                            $total_minutes++;
                        endif;

                        if ($total_minutes >= 60) :
                            $total_minutes -= 60;
                            $total_hours++;
                        endif;

                        // Format the result
                        $total_time = sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds);
                        $new_hotspots_end_time = $total_time;

                        $_hotspot_amout = $hotspot_total_adult_entry_cost + $hotspot_total_child_entry_cost + $hotspot_total_infant_entry_cost;
                        $_hotspot_order++;

                        $new_route_end_time = date('H:i:s', strtotime($route_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));

                        if ($new_route_end_time >= $new_hotspots_end_time) :

                            $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_order`', '`hotspot_ID`', '`item_type`', '`hotspot_entry_time_label`', '`hotspot_amout`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                            $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_order", "$_hotspot_id", "3", "$_dayOfWeekNumeric", "$_hotspot_amout", "$hotspot_duration", "$new_hotspots_start_time", "$new_hotspots_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");

                            //INSERT
                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, '')) :

                                $get_vehicle_details = getITINEARY_PLAN_VEHICLE_DETAILS($_itinerary_plan_ID, '', 'get_vehicle_details');

                                foreach ($get_vehicle_details as $index => $vehicle) :
                                    $vehicle_type_id = $vehicle['vehicle_type_id'];
                                    $vehicle_count = $vehicle['vehicle_count'];

                                    $total_amount = getVEHICLE_PARKING_CHARGES_DETAILS($_hotspot_id, $vehicle_type_id, 'total_amount');

                                    $get_total_amount = $total_amount * $vehicle_count;

                                    $parking_charges_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`vehicle_type`', '`vehicle_qty`', '`parking_charges_amt`', '`createdby`', '`status`');

                                    $parking_charges_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_id", "$vehicle_type_id", "$vehicle_count", "$get_total_amount", "$logged_user_id", "1");

                                    $select_hotspot_place_parking_charges = sqlQUERY_LABEL("SELECT `itinerary_hotspot_parking_charge_ID` FROM `dvi_itinerary_route_hotspot_parking_charge` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `hotspot_ID`='$_hotspot_id' and `vehicle_type` = '$vehicle_type_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_hotspot_place_parcking_charges_count = sqlNUMOFROW_LABEL($select_hotspot_place_parking_charges);

                                    if ($total_hotspot_place_parcking_charges_count == 0) :
                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_parking_charge", $parking_charges_arrFields, $parking_charges_arrValues, '')) :
                                        endif;
                                    else :
                                        $parking_charges_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `hotspot_ID`='$_hotspot_id' and `vehicle_type` = '$vehicle_type_id' ";
                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_parking_charge", $parking_charges_arrFields, $parking_charges_arrValues, $parking_charges_sqlWhere)) :
                                        endif;
                                    endif;

                                endforeach;

                                $route_hotspot_ID = sqlINSERTID_LABEL();
                                $response['i_result'] = true;
                                $response['result_success'] = true;

                                $selected_itinerary_hotel_query = sqlQUERY_LABEL("SELECT `hotel_id`, `room_id` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID'") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
                                $selected_itinerary_hotel_num_count = sqlNUMOFROW_LABEL($selected_itinerary_hotel_query);
                                if ($selected_itinerary_hotel_num_count > 0) :
                                    while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_itinerary_hotel_query)) :
                                        $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];
                                        $room_id = $fetch_itinerary_hotel_data['room_id'];

                                        $check_in_time = getROOM_DETAILS($room_id, 'check_in_time');
                                    endwhile;
                                endif;

                                $select_route_itinerary_hotspot_hotel_query = sqlQUERY_LABEL("SELECT `item_type` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `item_type`='4'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                                $total_itinerary_hotspot_hotel_num_rows_count = sqlNUMOFROW_LABEL($select_route_itinerary_hotspot_hotel_query);

                                if ($new_hotspots_end_time > $check_in_time && $total_itinerary_hotspot_hotel_num_rows_count == 0 && $selected_itinerary_hotel_num_count > 0) :
                                    $response['result_checkin_available'] = true;
                                else :
                                    $response['result_checkin_available'] = false;
                                endif;

                                $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);

                                $select_itinerary_route_details_list_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                $fetch_itinerary_route_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_list_query);
                                $itinerary_route_date = $fetch_itinerary_route_details_list_data['itinerary_route_date'];
                                $itinerary_route_date = date('Y-m-d', strtotime($itinerary_route_date));

                                $select_activity_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_id`='$_hotspot_id' and ACTIVITY_TIME_SLOT.`status`='1' and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date`='$itinerary_route_date'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                                $total_activity_rows_count = sqlNUMOFROW_LABEL($select_activity_query);
                                if ($total_activity_rows_count > 0) :
                                    $response['activity_available'] = true;
                                    $response['route_hotspot_ID'] = $route_hotspot_ID;
                                    $response['hotspot_start_time'] = $hotspot_start_time;
                                    $response['hotspot_end_time'] = $hotspot_end_time;
                                else :
                                    $select_activity_without_date_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_id`='$_hotspot_id' and ACTIVITY_TIME_SLOT.`status`='1' and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date` IS NULL") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                                    $total_activity_without_date_rows_count = sqlNUMOFROW_LABEL($select_activity_without_date_query);
                                    if ($total_activity_without_date_rows_count > 0) :
                                        $response['activity_available'] = true;
                                        $response['route_hotspot_ID'] = $route_hotspot_ID;
                                        $response['hotspot_start_time'] = $hotspot_start_time;
                                        $response['hotspot_end_time'] = $hotspot_end_time;
                                    else :
                                        $response['activity_available'] = false;
                                    endif;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                                $response['activity_available'] = false;
                            endif;
                        else :
                            $response['i_result'] = false;
                            $response['result_success'] = false;
                            $response['hotspot_day_time_over_status'] = true;
                            $response['hotspot_day_time_over'] = 'Tour ends at ' . date('g:i A', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' for that day; please update day end time for hotspot additions.';
                            $delete_hotspot_travelling_data = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        endif;
                    else :

                        $response['i_result'] = false;
                        $response['result_success'] = false;
                        $response['hotspot_not_available_status'] = true;

                        $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_start_time`, `hotspot_end_time` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                        while ($fetch_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_query)) :
                            $counter++;
                            $table_hotspot_start_time = $fetch_list_plan_data['hotspot_start_time'];
                            $table_hotspot_end_time = $fetch_list_plan_data['hotspot_end_time'];

                            $time .= date('g:i A', strtotime($table_hotspot_start_time)) . ' to ' . date('g:i A', strtotime($table_hotspot_end_time));

                            if ($total_itinerary_plan_rows_count != $counter) :
                                $time .= ' ,';
                            endif;
                        endwhile;
                        if ($total_itinerary_plan_rows_count > 0) :
                            $response['hotspot_not_available'] = 'Next available time: ' . $time . '.';
                        else :
                            $response['hotspot_not_available'] = "Don't have any next available time.";
                        endif;
                        $delete_hotspot_travelling_data = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    endif;
                endif;
            else :
                //UPDATE ITINEARY ROUTE HOTSPOT DETAILS
                $hotspot_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '$_hotspot_id' AND `item_type` = '2' ";

                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, $hotspot_sqlWhere)) :

                endif;
            endif;
        endif;

        $select_hotspot_added_list = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.`item_type`, HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_start_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID'") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        $total_added_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_added_list);
        // Initialize hotspot_order before entering the loop
        //$hotspot_order = 2;

        if ($total_added_hotspot_list_num_rows_count > 0) :
            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_added_list)) :
                $route_hotspot_ID = $fetch_hotspot_list_data['route_hotspot_ID'];
                $item_type = $fetch_hotspot_list_data['item_type'];
                $hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
                $hotspot_order = $fetch_hotspot_list_data['hotspot_order'];
                $hotspot_traveling_time = $fetch_hotspot_list_data['hotspot_traveling_time'];
                $hotspot_start_time = $fetch_hotspot_list_data['hotspot_start_time'];

                $previous_hotspot_ID = $hotspot_ID - 1;
                $previous_hotspot_order = $hotspot_order - 2;


                /* echo "<br><br>";
                echo 'route_hotspot_ID ' . $route_hotspot_ID . '<br>';
                echo 'item_type ' . $item_type . '<br>';
                echo 'hotspot_ID ' . $hotspot_ID . '<br>';
                echo 'hotspot_traveling_time ' . $hotspot_traveling_time . '<br>';
                echo 'hotspot_start_time ' . $hotspot_start_time . '<br>';
                echo "-------------------------<br>"; */

                if ($_hotspot_id == $hotspot_ID) :
                    //if ($item_type == 2) :
                    //$hotspot_order = 1;
                    /* echo " UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$hotspot_order' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID' ";
                        echo "<br>"; */
                    $updating_previous_hotspot_orders = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$hotspot_order' WHERE `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `item_type` = '$item_type' AND `hotspot_order` = '$previous_hotspot_order'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    $updating_hotspot_orders = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$previous_hotspot_order' WHERE `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());

                // elseif ($item_type == 3) :
                //     $hotspot_order = 2;
                //     /* echo " UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$hotspot_order' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID' ";
                //     echo "<br>"; */
                //     $updating_hotspot_orders = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$hotspot_order' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                // endif;
                // else :
                //     $hotspot_order++;
                //     /* echo " UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$hotspot_order' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID' ";
                //     echo "<br>"; */
                //     $updating_hotspot_orders = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_order` = '$hotspot_order' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                endif;
            endwhile;
        endif;

        $_start_time = date('H:i:s', strtotime(getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_start_time', "") . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
        $hotspot_start_time = $_start_time;

        $start_location_id = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'get_starting_location_id');
        $start_latitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $start_location_id);
        $start_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $start_location_id);
        $_end_time = getITINEARY_ROUTE_HOTSPOT_DETAILS($item_type, $_itinerary_plan_ID, $_itinerary_route_ID, 'hotspot_end_time');

        $select_hotspot_reorder_details = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.`item_type`, HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`, HOTSPOT_PLACES.`hotspot_duration` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' ORDER BY HOTSPOT_DETAILS.`hotspot_order` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_reorder_details)) :
            $route_hotspot_ID = $fetch_hotspot_list_data['route_hotspot_ID'];
            $hotspot_order = $fetch_hotspot_list_data['hotspot_order'];
            $item_type = $fetch_hotspot_list_data['item_type'];
            $hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
            $hotspot_duration = $fetch_hotspot_list_data['hotspot_duration'];

            $hotspot_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
            $hotspot_longitude = $fetch_hotspot_list_data['hotspot_longitude'];

            if ($item_type == 2) :
                $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $hotspot_latitude, $hotspot_longitude);

                // Extract duration in hours and minutes from the result
                $duration_string = $travel_distance['duration'];

                // Extract hours and minutes from duration string
                list($hours, $minutes) = explode(' ', $duration_string);

                // Convert to float
                $hours = (float) $hours;
                $minutes = (float) $minutes;

                // Calculate total minutes
                $total_minutes = ($hours * 60) + $minutes;

                // Convert total minutes to hours and minutes
                $hours = floor($total_minutes / 60);
                $minutes = $total_minutes % 60;

                // Format the time
                $_time = sprintf("%02d hour %02d min", $hours, $minutes);

                // Extract distance
                $_distance = round($travel_distance['distance'], 1);
                $_time = sprintf("%02d hour %02d min", $hours, $minutes);

                // Extract hours and minutes from the duration string
                preg_match('/(\d+) hour/', $_time, $hours_match);
                preg_match('/(\d+) min/', $_time, $minutes_match);

                $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                // Format the time as H:i:s
                $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                // Convert times to seconds
                $seconds1 = strtotime("1970-01-01 $_end_time UTC");
                $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

                $hotspot_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

            elseif ($item_type == 3) :

                // Parse time strings into hours, minutes, and seconds
                list($h1, $m1, $s1) = explode(':', $hotspot_end_time);
                list($h2, $m2, $s2) = explode(':', $hotspot_duration);

                // Add hours, minutes, and seconds separately
                $total_hours = $h1 + $h2;
                $total_minutes = $m1 + $m2;
                $total_seconds = $s1 + $s2;

                // Adjust minutes and seconds if they exceed their respective limits
                if ($total_seconds >= 60) :
                    $total_seconds -= 60;
                    $total_minutes++;
                endif;

                if ($total_minutes >= 60) :
                    $total_minutes -= 60;
                    $total_hours++;
                endif;

                // Format the result
                $total_time = sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds);
                $hotspot_end_time = $total_time;
                $_distance = NULL;
            endif;

            /* echo 'hotspot_end_time ' . $hotspot_end_time . '<br>';
            echo "-------------------------<br>";
            echo $_distance;
            echo "<br>"; */

            /* echo "UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_traveling_time` = '$formatted_time', `hotspot_travelling_distance` = '$_distance', `hotspot_start_time` = '$hotspot_start_time', `hotspot_end_time` = '$hotspot_end_time' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID'";
            echo "<br>"; */

            $updating_hotspot_timing = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_traveling_time` = '$formatted_time', `hotspot_travelling_distance` = '$_distance', `hotspot_start_time` = '$hotspot_start_time', `hotspot_end_time` = '$hotspot_end_time' WHERE `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '$item_type' AND `hotspot_ID` = '$hotspot_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());

            $select_hotspot_activity_reorder_details = sqlQUERY_LABEL("SELECT `route_activity_ID`, `activity_traveling_time` FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `itinerary_plan_ID`='$_itinerary_plan_ID' and `itinerary_route_ID`='$_itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' ORDER BY `route_activity_ID` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
            $total_hotspot_activity_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_activity_reorder_details);
            if ($total_hotspot_activity_num_rows_count > 0) :
                while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_reorder_details)) :
                    $route_activity_ID = $fetch_hotspot_list_data['route_activity_ID'];
                    $activity_traveling_time = $fetch_hotspot_list_data['activity_traveling_time'];
                    $activity_start_time = $hotspot_end_time;
                    $activity_end_time = $activity_end_time;

                endwhile;
            endif;

            $hotspot_start_time = $hotspot_end_time;
            $start_latitude = $hotspot_latitude;
            $start_longitude = $hotspot_longitude;

        endwhile;

        echo json_encode($response);

    elseif ($_GET['type'] == 'decline_hotspot_distance_alert') :

        $errors = [];
        $response = [];

        $_hotspot_id = $_POST['NXT_HOTSPOT_ID'];
        $_itinerary_route_ID = $_POST['ROUTE_ID'];
        $_itinerary_plan_ID = $_POST['PLAN_ID'];
        $dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];

        $select_next_hotspot_details = sqlQUERY_LABEL("SELECT `hotspot_latitude`, `hotspot_longitude` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_ID` = '$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_NXT_HOTSPOT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        while ($fetch_next_hotspot_data = sqlFETCHARRAY_LABEL($select_next_hotspot_details)) :
            $next_hotspot_latitude = $fetch_next_hotspot_data['hotspot_latitude'];
            $next_hotspot_longitude = $fetch_next_hotspot_data['hotspot_longitude'];
        endwhile;

        $end_latitude = $next_hotspot_latitude;
        $end_longitude = $next_hotspot_longitude;
        $_dayOfWeekNumeric = $dayOfWeekNumeric;

        if (empty($_hotspot_id)) :
            $errors['hotspot_id_required'] = true;
        endif;
        if (empty($_itinerary_route_ID)) :
            $errors['itinerary_route_ID_required'] = true;
        endif;
        if (empty($_itinerary_plan_ID)) :
            $errors['itinerary_plan_ID_required'] = true;
        endif;
        if ($_dayOfWeekNumeric == '') :
            $errors['dayOfWeekNumeric_required'] = true;
        endif;

        $select_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_hotel_details_ID`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`,HOTSPOT_PLACES.`hotspot_duration`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' ORDER BY HOTSPOT_DETAILS.`hotspot_order` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        $total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
        if ($total_hotspot_list_num_rows_count > 0) :
            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                $hotspot_order = $fetch_hotspot_list_data['hotspot_order'] + 1;
                $hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
                $itinerary_plan_hotel_details_ID = $fetch_hotspot_list_data['itinerary_plan_hotel_details_ID'];
                $start_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
                $start_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
                $hotspot_traveling_time = $fetch_hotspot_list_data['hotspot_traveling_time'];
                $hotspot_travelling_distance = $fetch_hotspot_list_data['hotspot_travelling_distance'];
                $start_time = $fetch_hotspot_list_data['hotspot_start_time'];
                $end_time = $fetch_hotspot_list_data['hotspot_end_time'];
                $hotspot_duration = $fetch_hotspot_list_data['hotspot_duration'];
            endwhile;
        else :
            $hotspot_order = 1;
        endif;

        if ($hotspot_ID == 0 && $itinerary_plan_hotel_details_ID == 0) :
            $start_location_id = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'get_starting_location_id');
            $start_latitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $start_location_id);
            $start_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $start_location_id);
            $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);

            // Extract duration in hours and minutes from the result
            $duration_string = $travel_distance['duration'];

            // Extract hours and minutes from duration string
            list($hours, $minutes) = explode(' ', $duration_string);

            // Convert to float
            $hours = (float) $hours;
            $minutes = (float) $minutes;

            // Calculate total minutes
            $total_minutes = ($hours * 60) + $minutes;

            // Convert total minutes to hours and minutes
            $hours = floor($total_minutes / 60);
            $minutes = $total_minutes % 60;

            // Format the time
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);

            // Extract distance
            $_distance = round($travel_distance['distance'], 1);
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);
        else :
            if ($itinerary_plan_hotel_details_ID != 0) :
                $selected_itinerary_hotel_query = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_itinerary_plan_hotel_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID'") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
                $fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_itinerary_hotel_query);
                $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];

                $selected_query = sqlQUERY_LABEL("SELECT `hotel_longitude`, `hotel_latitude` FROM `dvi_hotel` where `hotel_id` = '$hotel_id'") or die("#getHOTEL_DETAILS: UNABLE_TO_GET_HOTEL_PLACE: " . sqlERROR_LABEL());
                while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                    $start_latitude = $fetch_data['hotel_latitude'];
                    $start_longitude = $fetch_data['hotel_longitude'];
                endwhile;

            endif;

            $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);

            // Extract duration in hours and minutes from the result
            $duration_string = $travel_distance['duration'];

            // Extract hours and minutes from duration string
            list($hours, $minutes) = explode(' ', $duration_string);

            // Convert to float
            $hours = (float) $hours;
            $minutes = (float) $minutes;

            // Calculate total minutes
            $total_minutes = ($hours * 60) + $minutes;

            // Convert total minutes to hours and minutes
            $hours = floor($total_minutes / 60);
            $minutes = $total_minutes % 60;

            // Format the time
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);

            // Extract distance
            $_distance = round($travel_distance['distance'], 1);
            $_time = sprintf("%02d hour %02d min", $hours, $minutes);
        endif;

        $direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'direct_to_next_visiting_place');
        $route_end_time = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_end_time');

        if ($direct_to_next_visiting_place == 1) :
            $item_type = 2;
        endif;

        $_hotspot_order = $hotspot_order;
        $_start_time = getITINEARY_ROUTE_HOTSPOT_DETAILS($item_type, $_itinerary_plan_ID, $_itinerary_route_ID, 'hotspot_end_time');
        $_end_time = getITINEARY_ROUTE_HOTSPOT_DETAILS($item_type, $_itinerary_plan_ID, $_itinerary_route_ID, 'hotspot_end_time');
        if ($_end_time == '') :
            $_end_time = date('H:i:s', strtotime(getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'route_start_time', "") . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
        endif;

        $_hotspot_id = $_hotspot_id;
        $_itinerary_route_ID = $_itinerary_route_ID;
        $_itinerary_plan_ID = $_itinerary_plan_ID;

        // Extract hours and minutes from the duration string
        preg_match('/(\d+) hour/', $_time, $hours_match);
        preg_match('/(\d+) min/', $_time, $minutes_match);

        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

        // Format the time as H:i:s
        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

        // Convert times to seconds
        $seconds1 = strtotime("1970-01-01 $_end_time UTC");
        $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

        $hotspot_start_time = $_end_time;
        $hotspot_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

        // Convert time strings to timestamps
        $route_end_timestamp = strtotime($route_end_time);
        $hotspot_end_timestamp = strtotime($hotspot_end_time);

        if ($route_end_timestamp <= $hotspot_end_timestamp) :
            $errors['hotspot_end_time_exceed'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call
            $response['success'] = true;

            $hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_ID`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
            $hotspot_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "2", "$_hotspot_id", "$_hotspot_order", "$formatted_time", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

            $select_itineary_hotspot_details = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '$_hotspot_id' AND `item_type` = '2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $select_tineary_hotspot_details_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_details);

            if ($select_tineary_hotspot_details_count == 0) :
                //INSERT ITINEARY ROUTE HOTSPOT DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, '')) :

                    $updating_hotspot_orders = sqlQUERY_LABEL("UPDATE `dvi_itinerary_route_hotspot_details` SET `hotspot_plan_own_way` = '1' WHERE `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `item_type` = '2' AND `hotspot_ID` = '$_hotspot_id'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());

                    $route_day_end_time = $route_end_time;

                    $select_hotspot_places_query = sqlQUERY_LABEL("SELECT `hotspot_timing_status` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' and HOTSPOT_PLACE.`hotspot_ID` = '$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((`hotspot_start_time` <= '$hotspot_end_time' AND `hotspot_end_time` >= '$hotspot_end_time') OR `hotspot_open_all_time` = '1')") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    $fetch_hotspot_time_avail_count_data = sqlNUMOFROW_LABEL($select_hotspot_places_query);
                    /* $fetch_hotspot_places_list_data = sqlFETCHARRAY_LABEL($select_hotspot_places_query);
					$hotspot_timing_status = $fetch_hotspot_places_list_data['hotspot_timing_status']; */

                    $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((`hotspot_start_time` <= '$hotspot_start_time' AND `hotspot_end_time` >= '$hotspot_start_time') OR `hotspot_open_all_time` = '1' )") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);

                    if ($total_itinerary_plan_rows_count > 0 && ($fetch_hotspot_time_avail_count_data > 0)) :

                        $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                            $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                            $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                            $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                        endwhile;

                        $select_hotspot_place_list_query = sqlQUERY_LABEL("SELECT `hotspot_duration`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        while ($fetch_hotspot_place_list_data = sqlFETCHARRAY_LABEL($select_hotspot_place_list_query)) :
                            $hotspot_total_adult_entry_cost = $fetch_hotspot_place_list_data['hotspot_adult_entry_cost'] * $total_adult;
                            $hotspot_total_child_entry_cost = $fetch_hotspot_place_list_data['hotspot_child_entry_cost'] * $total_children;
                            $hotspot_total_infant_entry_cost = $fetch_hotspot_place_list_data['hotspot_infant_entry_cost'] * $total_infants;
                            $hotspot_duration = $fetch_hotspot_place_list_data['hotspot_duration'];
                        endwhile;

                        $new_hotspots_start_time = $hotspot_end_time;

                        // Parse time strings into hours, minutes, and seconds
                        list($h1, $m1, $s1) = explode(':', $hotspot_end_time);
                        list($h2, $m2, $s2) = explode(':', $hotspot_duration);

                        // Add hours, minutes, and seconds separately
                        $total_hours = $h1 + $h2;
                        $total_minutes = $m1 + $m2;
                        $total_seconds = $s1 + $s2;

                        // Adjust minutes and seconds if they exceed their respective limits
                        if ($total_seconds >= 60) :
                            $total_seconds -= 60;
                            $total_minutes++;
                        endif;

                        if ($total_minutes >= 60) :
                            $total_minutes -= 60;
                            $total_hours++;
                        endif;

                        // Format the result
                        $total_time = sprintf("%02d:%02d:%02d", $total_hours, $total_minutes, $total_seconds);
                        $new_hotspots_end_time = $total_time;

                        $_hotspot_amout = $hotspot_total_adult_entry_cost + $hotspot_total_child_entry_cost + $hotspot_total_infant_entry_cost;
                        $_hotspot_order++;

                        $new_route_end_time = date('H:i:s', strtotime($route_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));

                        if ($new_route_end_time >= $new_hotspots_end_time) :

                            $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_order`', '`hotspot_ID`', '`item_type`', '`hotspot_entry_time_label`', '`hotspot_amout`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                            $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_order", "$_hotspot_id", "3", "$_dayOfWeekNumeric", "$_hotspot_amout", "$hotspot_duration", "$new_hotspots_start_time", "$new_hotspots_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");

                            //INSERT
                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, '')) :

                                $get_vehicle_details = getITINEARY_PLAN_VEHICLE_DETAILS($_itinerary_plan_ID, '', 'get_vehicle_details');

                                foreach ($get_vehicle_details as $index => $vehicle) :
                                    $vehicle_type_id = $vehicle['vehicle_type_id'];
                                    $vehicle_count = $vehicle['vehicle_count'];

                                    $total_amount = getVEHICLE_PARKING_CHARGES_DETAILS($_hotspot_id, $vehicle_type_id, 'total_amount');

                                    $get_total_amount = $total_amount * $vehicle_count;

                                    $parking_charges_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`vehicle_type`', '`vehicle_qty`', '`parking_charges_amt`', '`createdby`', '`status`');

                                    $parking_charges_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_id", "$vehicle_type_id", "$vehicle_count", "$get_total_amount", "$logged_user_id", "1");

                                    $select_hotspot_place_parking_charges = sqlQUERY_LABEL("SELECT `itinerary_hotspot_parking_charge_ID` FROM `dvi_itinerary_route_hotspot_parking_charge` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `hotspot_ID`='$_hotspot_id' and `vehicle_type` = '$vehicle_type_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_hotspot_place_parcking_charges_count = sqlNUMOFROW_LABEL($select_hotspot_place_parking_charges);

                                    if ($total_hotspot_place_parcking_charges_count == 0) :
                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_parking_charge", $parking_charges_arrFields, $parking_charges_arrValues, '')) :
                                        endif;
                                    else :
                                        $parking_charges_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `hotspot_ID`='$_hotspot_id' and `vehicle_type` = '$vehicle_type_id' ";
                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_parking_charge", $parking_charges_arrFields, $parking_charges_arrValues, $parking_charges_sqlWhere)) :
                                        endif;
                                    endif;

                                endforeach;

                                $route_hotspot_ID = sqlINSERTID_LABEL();
                                $response['i_result'] = true;
                                $response['result_success'] = true;

                                $selected_itinerary_hotel_query = sqlQUERY_LABEL("SELECT `hotel_id`, `room_id` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID'") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
                                $selected_itinerary_hotel_num_count = sqlNUMOFROW_LABEL($selected_itinerary_hotel_query);
                                if ($selected_itinerary_hotel_num_count > 0) :
                                    while ($fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_itinerary_hotel_query)) :
                                        $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];
                                        $room_id = $fetch_itinerary_hotel_data['room_id'];

                                        $check_in_time = getROOM_DETAILS($room_id, 'check_in_time');
                                    endwhile;
                                endif;

                                $select_route_itinerary_hotspot_hotel_query = sqlQUERY_LABEL("SELECT `item_type` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `item_type`='4'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                                $total_itinerary_hotspot_hotel_num_rows_count = sqlNUMOFROW_LABEL($select_route_itinerary_hotspot_hotel_query);

                                if ($new_hotspots_end_time > $check_in_time && $total_itinerary_hotspot_hotel_num_rows_count == 0 && $selected_itinerary_hotel_num_count > 0) :
                                    $response['result_checkin_available'] = true;
                                else :
                                    $response['result_checkin_available'] = false;
                                endif;

                                $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);

                                $select_itinerary_route_details_list_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                $fetch_itinerary_route_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_list_query);
                                $itinerary_route_date = $fetch_itinerary_route_details_list_data['itinerary_route_date'];
                                $itinerary_route_date = date('Y-m-d', strtotime($itinerary_route_date));

                                $select_activity_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_id`='$_hotspot_id' and ACTIVITY_TIME_SLOT.`status`='1' and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date`='$itinerary_route_date'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                                $total_activity_rows_count = sqlNUMOFROW_LABEL($select_activity_query);
                                if ($total_activity_rows_count > 0) :
                                    $response['activity_available'] = true;
                                    $response['route_hotspot_ID'] = $route_hotspot_ID;
                                    $response['hotspot_start_time'] = $hotspot_start_time;
                                    $response['hotspot_end_time'] = $hotspot_end_time;
                                else :
                                    $select_activity_without_date_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_id`='$_hotspot_id' and ACTIVITY_TIME_SLOT.`status`='1' and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date` IS NULL") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                                    $total_activity_without_date_rows_count = sqlNUMOFROW_LABEL($select_activity_without_date_query);
                                    if ($total_activity_without_date_rows_count > 0) :
                                        $response['activity_available'] = true;
                                        $response['route_hotspot_ID'] = $route_hotspot_ID;
                                        $response['hotspot_start_time'] = $hotspot_start_time;
                                        $response['hotspot_end_time'] = $hotspot_end_time;
                                    else :
                                        $response['activity_available'] = false;
                                    endif;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                                $response['activity_available'] = false;
                            endif;
                        else :
                            $response['i_result'] = false;
                            $response['result_success'] = false;
                            $response['hotspot_day_time_over_status'] = true;
                            $response['hotspot_day_time_over'] = 'Tour ends at ' . date('g:i A', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' for that day; please update day end time for hotspot additions.';
                            $delete_hotspot_travelling_data = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        endif;
                    else :

                        $response['i_result'] = false;
                        $response['result_success'] = false;
                        $response['hotspot_not_available_status'] = true;

                        $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_start_time`, `hotspot_end_time` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                        while ($fetch_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_query)) :
                            $counter++;
                            $table_hotspot_start_time = $fetch_list_plan_data['hotspot_start_time'];
                            $table_hotspot_end_time = $fetch_list_plan_data['hotspot_end_time'];

                            $time .= date('g:i A', strtotime($table_hotspot_start_time)) . ' to ' . date('g:i A', strtotime($table_hotspot_end_time));

                            if ($total_itinerary_plan_rows_count != $counter) :
                                $time .= ' ,';
                            endif;
                        endwhile;
                        if ($total_itinerary_plan_rows_count > 0) :
                            $response['hotspot_not_available'] = 'Next available time: ' . $time . '.';
                        else :
                            $response['hotspot_not_available'] = "Don't have any next available time.";
                        endif;
                        $delete_hotspot_travelling_data = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                    endif;
                endif;
            else :
                //UPDATE ITINEARY ROUTE HOTSPOT DETAILS
                $hotspot_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '$_hotspot_id' AND `item_type` = '2' ";

                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, $hotspot_sqlWhere)) :
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
?>