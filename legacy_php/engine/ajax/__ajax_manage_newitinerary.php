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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'itinerary_basic_info') :

        $errors = [];
        $response = [];

        $_arrival_location = trim($_POST['arrival_location']);
        $_departure_location = trim($_POST['departure_location']);
        $_trip_start_date_and_time = $_POST['trip_start_date_and_time'];
        $_trip_end_date_and_time = $_POST['trip_end_date_and_time'];
        $_no_of_days = $_POST['no_of_days'];
        $_no_of_nights = trim($_POST['no_of_nights']);
        $_number_of_routes = trim($_POST['number_of_routes']);
        $_itinerary_type = trim($_POST['itinerary_type']);
        $_total_adult = trim($_POST['total_adult']);
        $_total_children = trim($_POST['total_children']);
        $_total_infants = trim($_POST['total_infants']);
        $_total_travellers = ($_total_adult + $_total_children + $_total_infants);
        $_nationality = $_POST['nationality'];

        $_expecting_budget = $_POST['expecting_budget'];
        $_itinerary_prefrence = $_POST['itinerary_prefrence'];
        $_special_instructions = $_POST['special_instructions'];
        $_food_type = $_POST['food_type'];
        $_guide_for_itinerary =  $_POST['guide_for_itinerary'];

        $_traveller_age = $_POST['traveller_age'];
        $_hidden_traveller_type = $_POST['hidden_traveller_type'];
        $_hidden_traveller_name = $_POST['hidden_traveller_name'];

        $_meal_plan_breakfast = $_POST['meal_plan_breakfast'];
        $_meal_plan_dinner = $_POST['meal_plan_dinner'];
        $_meal_plan_lunch = $_POST['meal_plan_lunch'];

        $_meal_plan_breakfast = ($_meal_plan_breakfast == 'on') ? 1 : 0;
        $_meal_plan_dinner = ($_meal_plan_dinner == 'on') ? 1 : 0;
        $_meal_plan_lunch = ($_meal_plan_lunch == 'on') ? 1 : 0;

        if ($_itinerary_prefrence == 1) : //hotel
            $_number_of_rooms = $_POST['number_of_rooms'];
            $_number_of_child_no_bed = $_POST['number_of_child_no_bed'];
            $_number_of_extra_beds = $_POST['number_of_extra_beds'];
            $_vehicle_category = 0;
            $_pick_up_date_and_time = "";
        elseif ($_itinerary_prefrence == 2) : //vehicle
            $_number_of_rooms = 0;
            $_number_of_child_no_bed = 0;
            $_number_of_extra_beds = 0;
            $_vehicle_category = $_POST['vehicle_category'];
            $_pick_up_date_and_time = $_POST['pick_up_date_and_time'];
        elseif ($_itinerary_prefrence == 3) : //both
            $_number_of_rooms = $_POST['number_of_rooms'];
            $_number_of_child_no_bed = $_POST['number_of_child_no_bed'];
            $_number_of_extra_beds = $_POST['number_of_extra_beds'];
            $_vehicle_category = $_POST['vehicle_category'];
            $_pick_up_date_and_time = $_POST['pick_up_date_and_time'];
        endif;

        $get_location_details = sqlQUERY_LABEL("SELECT `location_ID` FROM `dvi_stored_locations` WHERE `source_location`='$_arrival_location' AND  `destination_location` ='$_departure_location' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
            while ($location_row = sqlFETCHARRAY_LABEL($get_location_details)) :
                $location_id = $location_row['location_ID'];
            endwhile;
        endif;


        $_arrival_type = $_POST['arrival_type'];
        $_departure_type = $_POST['departure_type'];

        $hidden_itinerary_plan_ID = $_POST['hidden_itinerary_plan_ID'];

        if (empty($_arrival_location)) :
            $errors['arrival_location_required'] = true;
        endif;

        /* if (empty($_special_instructions)) :
            $errors['special_instructions_required'] = true;
        endif; */

        if (empty($_food_type)) :
            $errors['food_type_required'] = true;
        endif;

        if (empty($_nationality)) :
            $errors['nationality_required'] = true;
        endif;

        if ($_guide_for_itinerary == "") :
            $errors['guide_for_itinerary_required'] = true;
        endif;

        if (empty($_arrival_type)) :
            $errors['arrival_type_required'] = true;
        endif;

        if (empty($_departure_location)) :
            $errors['departure_location_required'] = true;
        endif;

        if (empty($_departure_type)) :
            $errors['departure_type_required'] = true;
        endif;

        if (empty($_trip_start_date_and_time)) :
            $errors['trip_start_date_and_time_required'] = true;
        endif;
        if (empty($_trip_end_date_and_time)) :
            $errors['trip_end_date_and_time_required'] = true;
        endif;
        if (empty($_number_of_routes)) :
            $errors['number_of_routes_required'] = true;
        endif;
        if (empty($_no_of_days)) :
            $errors['no_of_days_required'] = true;
        endif;
        if ($_no_of_nights == '') :
            if (empty($_no_of_nights)) :
                $errors['no_of_nights_required'] = true;
            endif;
        endif;
        if ($_total_children == '') :
            if (empty($_total_children)) :
                $errors['total_children_required'] = true;
            endif;
        endif;
        if (empty($_expecting_budget)) :
            $errors['expecting_budget_required'] = true;
        endif;
        if (empty($_itinerary_prefrence)) :
            $errors['itinerary_prefrence_required'] = true;
        endif;

        if ($_itinerary_prefrence == 1) : //hotel

            if (empty($_number_of_rooms)) :
                $errors['number_of_rooms_required'] = true;
            endif;
            if ($_number_of_child_no_bed == '') :
                if (empty($_number_of_child_no_bed)) :
                    $errors['number_of_child_no_bed_required'] = true;
                endif;
            endif;
            if ($_number_of_extra_beds == '') :
                if (empty($_number_of_extra_beds)) :
                    $errors['number_of_extra_beds_required'] = true;
                endif;
            endif;

        elseif ($_itinerary_prefrence == 2) : //vehicle
            /* if (empty($_vehicle_category)) :
                $errors['vehicle_type_required'] = true;
            endif; */
            if (empty($_pick_up_date_and_time)) :
                $errors['pick_up_date_and_time_required'] = true;
            endif;

        elseif ($_itinerary_prefrence == 3) : //both

            if (empty($_number_of_rooms)) :
                $errors['number_of_rooms_required'] = true;
            endif;
            if ($_number_of_child_no_bed == '') :
                if (empty($_number_of_child_no_bed)) :
                    $errors['number_of_child_no_bed_required'] = true;
                endif;
            endif;
            if ($_number_of_extra_beds == '') :
                if (empty($_number_of_extra_beds)) :
                    $errors['number_of_extra_beds_required'] = true;
                endif;
            endif;
            /* if (empty($_vehicle_category)) :
                $errors['vehicle_type_required'] = true;
            endif; */
            if (empty($_pick_up_date_and_time)) :
                $errors['pick_up_date_and_time_required'] = true;
            endif;

        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
            $_trip_start_date_and_time = date('Y-m-d H:i:s', strtotime($_trip_start_date_and_time));
            $_trip_end_date_and_time = date('Y-m-d H:i:s', strtotime($_trip_end_date_and_time));

            if ($_pick_up_date_and_time != "") :
                $_pick_up_date_and_time = date('Y-m-d H:i:s', strtotime($_pick_up_date_and_time));
            endif;

            $arrFields = array('`arrival_location`', '`departure_location`', '`location_id`', '`trip_start_date_and_time`', '`trip_end_date_and_time`', '`arrival_type`', '`departure_type`', '`expecting_budget`', '`no_of_routes`', '`itinerary_type`', '`no_of_days`', '`no_of_nights`', '`total_adult`', '`total_children`', '`total_infants`', '`nationality`', '`itinerary_preference`', '`preferred_room_count`', '`total_extra_bed`', '`total_child_no_bed`', '`vehicle_type`',  '`guide_for_itinerary`', '`food_type`', '`special_instructions`', '`pick_up_date_and_time`', '`meal_plan_breakfast`', '`meal_plan_lunch`', '`meal_plan_dinner`', '`createdby`', '`status`');

            $arrValues = array("$_arrival_location", "$_departure_location", "$location_id", "$_trip_start_date_and_time", "$_trip_end_date_and_time", "$_arrival_type", "$_departure_type", "$_expecting_budget", "$_number_of_routes", "$_itinerary_type",  "$_no_of_days", "$_no_of_nights", "$_total_adult", "$_total_children", "$_total_infants", "$_nationality", "$_itinerary_prefrence", "$_number_of_rooms", "$_number_of_extra_beds", "$_number_of_child_no_bed", "$_vehicle_category",   "$_guide_for_itinerary", "$_food_type", "$_special_instructions", "$_pick_up_date_and_time",  "$_meal_plan_breakfast",  "$_meal_plan_lunch", "$_meal_plan_dinner", "$logged_user_id", "1");

            if ($hidden_itinerary_plan_ID != '' && $hidden_itinerary_plan_ID != 0) :

                $select_existing_itinerary_details = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_plan_ID'") or die("#1-UNABLE_TO_LIST:" . sqlERROR_LABEL());
                $itinerary_num_rows_count = sqlNUMOFROW_LABEL($select_existing_itinerary_details);
                if ($itinerary_num_rows_count > 0) :
                    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_existing_itinerary_details)) :
                        $old_arrival_location = $fetch_list_data['arrival_location'];
                        $old_departure_location = $fetch_list_data['departure_location'];
                        $old_trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
                        $old_trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
                    endwhile;

                    if ($old_arrival_location != $_arrival_location || $old_departure_location != $_departure_location || $old_trip_start_date_and_time != $_trip_start_date_and_time || $old_trip_end_date_and_time != $_trip_end_date_and_time) :

                        //DELETE EXISTING ROUTE
                        $sqlWhere_route = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
                        $delete_previous_plan_details = sqlACTIONS("DELETE", "dvi_itinerary_route_details", '', '', $sqlWhere_route);

                        //INSERT ROUTE DETAILS AGAIN
                        for ($i = 1; $i <= $_no_of_days; $i++) :

                            if ($i == 1) :
                                $selected_LOCATION_NAME = $_arrival_location;

                                $selected_DISTANCE = 0;
                            elseif ($i == $_no_of_days) :
                                $selected_LOCATION_NAME = $_departure_location;

                            else :
                                $selected_LOCATION_NAME = "";

                            endif;

                            $itinerary_route_date = date('Y-m-d', strtotime($_trip_start_date_and_time . ' + ' . ($i - 1) . ' days'));

                            $arrFields_route = array(
                                '`itinerary_plan_ID`', '`location_name`',  '`itinerary_route_date`',   '`no_of_days`',  '`createdby`', '`status`'
                            );

                            $arrValues_route = array("$hidden_itinerary_plan_ID", "$selected_LOCATION_NAME", "$itinerary_route_date", "1",  "$logged_user_id", "1");

                            //INSERT ROUTE DETAILS
                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $arrFields_route, $arrValues_route, '')) :
                            endif;
                        endfor;

                    endif;
                endif;

                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";

                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_details", $arrFields, $arrValues, $sqlWhere)) :

                    $select_traveller_age_list_query = sqlQUERY_LABEL("SELECT `traveller_details_ID`, `traveller_name`,`traveller_age` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $total_traveller_list_count_data = sqlNUMOFROW_LABEL($select_traveller_age_list_query);
                    if ($total_traveller_list_count_data != $_total_travellers) :
                        $itinerary_traveller_sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
                        $delete_removed_age_details = sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $itinerary_traveller_sqlWhere);
                        $traveller_count_mismatch = true;
                    endif;

                    foreach ($_traveller_age as $key => $val) :
                        $traveler++;
                        $selected_TRAVELLER_AGE = $_traveller_age[$key];
                        $selected_traveller_details_ID = $_POST['hidden_traveller_details_ID'][$key];
                        $selected_traveller_type = $_POST['hidden_traveller_type'][$key];
                        $selected_traveller_name = $_POST['hidden_traveller_name'][$key];

                        $arrFields_traveller = array('`itinerary_plan_ID`', '`traveller_type`', '`traveller_name`', '`traveller_age`', '`createdby`', '`status`');
                        $arrValues_traveller = array("$hidden_itinerary_plan_ID", "$selected_traveller_type", "$selected_traveller_name", "$selected_TRAVELLER_AGE",  "$logged_user_id", "1");

                        if ($traveller_count_mismatch) :
                            //INSERT TRAVELER DETAILS
                            if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $arrFields_traveller, $arrValues_traveller, '')) :
                            endif;
                        else :
                            $sqlwhere = " `traveller_details_ID` = '$selected_traveller_details_ID' ";
                            //UPDATE TRAVELER DETAILS
                            if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $arrFields_traveller, $arrValues_traveller, $sqlwhere)) :
                            endif;
                        endif;
                    endforeach;

                    if ($_itinerary_prefrence == 2 || $_itinerary_prefrence == 3) :

                        $vehicle_type = $_POST['vehicle_type'];
                        $vehicle_count = $_POST['vehicle_count'];
                        $vehicle_details_ID = $_POST['hidden_vehicle_ID'];

                        for ($i = 0; $i < count($vehicle_type); $i++) :

                            if ($vehicle_details_ID[$i] != "") :

                                $arrFields_vehicle_details = array('`vehicle_type_id`', '`vehicle_count`');

                                $arrValues_vehicle_details = array("$vehicle_type[$i]", "$vehicle_count[$i]");

                                $sqlWhere_vehicle_details = " `vehicle_details_ID` = '$vehicle_details_ID[$i]' ";

                                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_vehicle_details", $arrFields_vehicle_details, $arrValues_vehicle_details, $sqlWhere_vehicle_details)) :
                                //SUCCESS
                                endif;

                            else :

                                $arrFields_vehicle_details = array('`itinerary_plan_id`', '`vehicle_type_id`', '`vehicle_count`', '`createdby`', '`status`');

                                $arrValues_vehicle_details = array("$hidden_itinerary_plan_ID", "$vehicle_type[$i]", "$vehicle_count[$i]", "$logged_user_id", "1");

                                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vehicle_details", $arrFields_vehicle_details, $arrValues_vehicle_details, '')) :
                                //SUCCESS
                                endif;

                            endif;

                        endfor;

                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'newitinerary.php?route=add&formtype=itinerary_routes&id=' . $hidden_itinerary_plan_ID;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'newitinerary.php?route=add&formtype=itinerary_routes&id=' . $hidden_itinerary_plan_ID;
                        $response['result_success'] = true;
                    endif;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_details", $arrFields, $arrValues, '')) :
                    $itinerary_id = sqlINSERTID_LABEL();

                    foreach ($_traveller_age as $key => $val) :
                        $traveler++;
                        $selected_TRAVELER_AGE = $_traveller_age[$key];
                        $selected_traveller_type = $_POST['hidden_traveller_type'][$key];
                        $selected_traveller_name = $_POST['hidden_traveller_name'][$key];

                        $arrFields_traveller = array('`itinerary_plan_ID`', '`traveller_type`', '`traveller_name`', '`traveller_age`', '`createdby`', '`status`');
                        $arrValues_traveller = array("$itinerary_id", "$selected_traveller_type", "$selected_traveller_name", "$selected_TRAVELER_AGE",  "$logged_user_id", "1");

                        //INSERT TRAVELER DETAILS
                        if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $arrFields_traveller, $arrValues_traveller, '')) :

                        endif;

                    endforeach;

                    //INSERT ROUTE DETAILS
                    for ($i = 1; $i <= $_no_of_days; $i++) :

                        if ($i == 1) :
                            $selected_LOCATION_NAME = $_arrival_location;
                            $selected_DISTANCE = 0;
                        elseif ($i == $_no_of_days) :
                            $selected_LOCATION_NAME = $_departure_location;
                        else :
                            $selected_LOCATION_NAME = "";
                        endif;

                        $itinerary_route_date = date('Y-m-d', strtotime($_trip_start_date_and_time . ' + ' . ($i - 1) . ' days'));

                        $arrFields_route = array('`itinerary_plan_ID`', '`location_name`',  '`itinerary_route_date`',  '`no_of_days`',  '`createdby`', '`status`');

                        $arrValues_route = array("$itinerary_id", "$selected_LOCATION_NAME", "$itinerary_route_date", "1",  "$logged_user_id", "1");

                        //INSERT ROUTE DETAILS
                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $arrFields_route, $arrValues_route, '')) :

                        endif;
                    endfor;

                    if ($_itinerary_prefrence == 2 || $_itinerary_prefrence == 3) :

                        $vehicle_type = $_POST['vehicle_type'];
                        $vehicle_count = $_POST['vehicle_count'];
                        for ($i = 0; $i < count($vehicle_type); $i++) :

                            $arrFields_vehicle_details = array('`itinerary_plan_id`', '`vehicle_type_id`', '`vehicle_count`', '`createdby`', '`status`');

                            $arrValues_vehicle_details = array("$itinerary_id", "$vehicle_type[$i]", "$vehicle_count[$i]", "$logged_user_id", "1");

                            if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vehicle_details", $arrFields_vehicle_details, $arrValues_vehicle_details, '')) :
                            //SUCCESS
                            endif;

                        endfor;
                    endif;

                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'newitinerary.php?route=add&formtype=itinerary_routes&id=' . $itinerary_id;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'delete_vehicle_type') :

        $VEHICLE_DETAILS_ID = $_POST['__ID'];

        $arrFields = array('`deleted`');
        $arrValues = array("1");
        $sqlWhere = " `vehicle_details_ID` = '$VEHICLE_DETAILS_ID' ";

        if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_vehicle_details", $arrFields, $arrValues, $sqlWhere)) :
            $response['i_result'] = true;
            $response['result_success'] = true;
        else :
            $response['i_result'] = false;
            $response['result_success'] = false;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'itinerary_route_info') :

        /* error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1); */

        $errors = [];
        $response = [];

        $_location_name = $_POST['location_name'];
        $_direct_to_next_visiting_place = $_POST['direct_to_next_visiting_place'];
        $_next_visiting_place = $_POST['next_visiting_place'];
        $_via_route = $_POST['via_route'];
        $hidden_itinerary_plan_ID = $_POST['hidden_itinerary_plan_ID'];

        $trip_start_date_and_time = dateformat_database(get_ITINERARY_PLAN_DETAILS($hidden_itinerary_plan_ID, 'trip_start_date_and_time'));
        $no_of_itinerarydays = get_ITINERARY_PLAN_DETAILS($hidden_itinerary_plan_ID, 'no_of_days');

        if ($hidden_itinerary_plan_ID) :
            $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
            $delete_previous_plan_details = sqlACTIONS("DELETE", "dvi_itinerary_route_details", '', '', $sqlWhere);
        endif;

        $response['success'] = true;

        // Initialize the Google Maps Distance Matrix API URL
        //$apiUrl = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&key={$GOOGLEMAP_API_KEY}";
        $no_of_days = 0;

        foreach ($_location_name as $key => $value) :

            $selected_LOCATION_NAME = $_POST['location_name'][$key];
            $selected_NO_OF_DAYS = 1;
            $selected_VIA_ROUTE = $_POST['via_route'][$key];
            if ($selected_VIA_ROUTE) :
                $selected_VIA_ROUTE = $selected_VIA_ROUTE;
            else :
                $selected_VIA_ROUTE = NULL;
            endif;

            $selected_direct_to_next_visiting_place = $_POST['direct_to_next_visiting_place'][$key];
            if ($selected_direct_to_next_visiting_place != "") :
                $selected_direct_to_next_visiting_place = 1;
            else :
                $selected_direct_to_next_visiting_place = 0;
            endif;
            $selected_next_visiting_place = $_POST['next_visiting_place'][$key];

            $itinerary_route_date = date('Y-m-d', strtotime($trip_start_date_and_time . ' + ' . $no_of_days . ' days'));
            $itinerary_route_date_time = date('Y-m-d H:i:s', strtotime($trip_start_date_and_time . ' + ' . $no_of_days . ' days'));

            $no_of_days = $no_of_days + $selected_NO_OF_DAYS;

            //if ($key != 0) :

            $fetch_distance_from_master_table = sqlQUERY_LABEL("SELECT `location_ID`,`distance` FROM  `dvi_stored_locations` WHERE `destination_location` = '$selected_next_visiting_place' AND `source_location` = '$selected_LOCATION_NAME' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

            if (sqlNUMOFROW_LABEL($fetch_distance_from_master_table) > 0) :
                while ($row = sqlFETCHARRAY_LABEL($fetch_distance_from_master_table)) :
                    $location_ID = $row['location_ID'];
                    $distanceKM = $row['distance'];
                endwhile;
            else :
                $distanceKM = 0;
            endif;

            //$currentLat = $selected_LOCATION_LATTITUDE;
            // $currentLon = $selected_LOCATION_LONGTITUDE;

            // Build the request URL for the Distance Matrix API
            //$url = "{$apiUrl}&origins={$prevLat},{$prevLon}&destinations={$currentLat},{$currentLon}";
            // Make the API request
            //$distance_response = file_get_contents($url);
            //$data = json_decode($distance_response, true);
            //$distanceKM = $data['rows'][0]['elements'][0]['distance']['text'];
            //endif;

            $arrFields = array('`itinerary_plan_ID`', '`location_id`', '`location_name`', '`itinerary_route_date`', '`no_of_days`', '`no_of_km`', '`direct_to_next_visiting_place`', '`location_via_route`',  '`next_visiting_location`',  '`createdby`', '`status`');

            $arrValues = array("$hidden_itinerary_plan_ID", "$location_ID", "$selected_LOCATION_NAME", "$itinerary_route_date",  "$selected_NO_OF_DAYS", "$distanceKM", "$selected_direct_to_next_visiting_place", "$selected_VIA_ROUTE",  "$selected_next_visiting_place",  "$logged_user_id", "1");

            //INSERT ROUTE DETAILS
            if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $arrFields, $arrValues, '')) :
                $response['i_result'] = true;
                $response['redirect_URL'] = 'newitinerary.php?route=add&formtype=itinerary_routes&id=' . $hidden_itinerary_plan_ID;
                $response['result_success'] = true;
            else :
                $response['i_result'] = false;
                $response['result_success'] = false;
            endif;

        endforeach;

        if ($no_of_itinerarydays != count($_location_name)) :

            $updated_days_count = count($_location_name);
            $updated_nights_count = $updated_days_count - 1;

            $arrFields_plan = array('`trip_end_date_and_time`',  '`no_of_days`', '`no_of_nights`');
            $arrValues_plan = array("$itinerary_route_date_time", "$updated_days_count", "$updated_nights_count");
            $sqlWhere_plan = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
            //UPDATE PLAN DETAILS
            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_details", $arrFields_plan, $arrValues_plan, $sqlWhere_plan)) :
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'guide_for_itinerary') :

        $errors = [];
        $response = [];

        $_guide_language = $_POST['guide_language'];
        $_guide_slot = $_POST['guide_slot'];
        $_itinerary_plan_ID = trim($_POST['itinerary_plan_ID']);
        $_itinerary_route_ID = trim($_POST['itinerary_route_ID']);
        $_guide_type = trim($_POST['guide_type']);
        $hidden_route_guide_ID = trim($_POST['hidden_route_guide_ID']);

        if (empty($hidden_route_guide_ID)) :
            if (empty($_guide_language)) :
                $errors['guide_language_required'] = true;
            endif;
            if (empty($_guide_type)) :
                $errors['guide_type_required'] = true;
            endif;
            if (empty($_itinerary_plan_ID)) :
                $errors['itinerary_plan_ID_required'] = true;
            endif;
            if ($_guide_type != '1') :
                if (empty($_itinerary_route_ID)) :
                    $errors['itinerary_route_ID_required'] = true;
                endif;
                if (empty($_guide_slot)) :
                    $errors['guide_slot_required'] = true;
                endif;
            endif;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            if ($_guide_language != '') :
                $_guide_language = implode(',', $_guide_language);
            endif;

            if ($_guide_slot != '') :
                $_guide_slot = implode(',', $_guide_slot);
            endif;

            $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`guide_type`', '`guide_language`', '`guide_slot`', '`createdby`', '`status`');

            $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_guide_type", "$_guide_language", "$_guide_slot", "$logged_user_id", "1");

            if ($_guide_type == 0) :
                $filter_by_route = " AND `itinerary_route_ID` = '$_itinerary_route_ID' ";
            endif;

            if ($hidden_route_guide_ID != '' && $hidden_route_guide_ID != 0) :
                if (empty($_guide_language) && empty($_guide_slot)) :
                    $delete_sqlwhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' {$filter_by_route} ";
                    $delete_itinerary_route_guide_details = sqlACTIONS("DELETE", "dvi_itinerary_route_guide_details", '', '', $delete_sqlwhere);
                    $response['u_result'] = true;
                    $response['result_success'] = true;
                else :
                    $sqlWhere = " `route_guide_ID` = '$hidden_route_guide_ID' ";
                    //UPDATE DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_guide_details", $arrFields, $arrValues, $sqlWhere)) :
                        $response['u_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;
                endif;
            else :
                //INSERT DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_route_guide_details", $arrFields, $arrValues, $sqlWhere)) :
                    $itinerary_route_guide_id = sqlINSERTID_LABEL();
                    $response['i_result'] = true;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'update_itinerary_route_timing') :

        $errors = [];
        $response = [];

        $_itinerary_route_ID  = $_POST['itinerary_route_ID'];
        $_itinerary_plan_ID  = $_POST['itinerary_plan_ID'];
        $_itinerary_route_counter = $_POST['itinerary_route_counter'];
        $_hotspot_start_time = $_POST['hotspot_start_time'];
        $_hotspot_end_time = $_POST['hotspot_end_time'];
        $_total_itinerary_route_count = $_POST['total_itinerary_route_count'];
        $direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'direct_to_next_visiting_place');
        $start_latitude = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'location_latitude');
        $start_longitude = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'location_longtitude');
        $next_visiting_location = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'next_visiting_location');
        $end_latitude = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'next_visiting_location_latitude');
        $end_longitude = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'next_visiting_location_longitude');
        $itinerary_common_buffer_time = getGLOBALSETTING('itinerary_common_buffer_time');
        $start_location_id = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'get_starting_location_id');

        $minimum_travel_distance = calculateTravelDistanceAndTime($start_location_id);
        $travel_time = $minimum_travel_distance['duration'];

        // Extract hours and minutes from the duration string
        preg_match('/(\d+) hour/', $travel_time, $travel_hours_match);
        preg_match('/(\d+) min/', $travel_time, $travel_minutes_match);

        $travel_hours = isset($travel_hours_match[1]) ? $travel_hours_match[1] : 0;
        $travel_minutes = isset($travel_minutes_match[1]) ? $travel_minutes_match[1] : 0;

        // Format the time as H:i:s
        $travel_formatted_time = sprintf('%02d:%02d:00', $travel_hours, $travel_minutes);

        $formatted_hotspot_start_time = date('H:i:s', strtotime($_hotspot_start_time));
        // Convert times to seconds
        $trip_started_on = strtotime("1970-01-01 $formatted_hotspot_start_time UTC");
        $next_travel_end_on = strtotime("1970-01-01 $travel_formatted_time UTC");
        $total_itinerary_common_buffer_time = strtotime("1970-01-01 $itinerary_common_buffer_time UTC");

        $travelling_end_time = gmdate('H:i:s', ($trip_started_on + $next_travel_end_on + $total_itinerary_common_buffer_time));

        if (empty($_itinerary_route_ID)) :
            $errors['itinerary_route_ID_required'] = true;
        endif;
        if (empty($_itinerary_plan_ID)) :
            $errors['itinerary_plan_ID_required'] = true;
        endif;
        if (empty($_itinerary_route_counter)) :
            $errors['itinerary_route_counter_required'] = true;
        endif;

        $select_hotel_list_query = sqlQUERY_LABEL("SELECT `trip_start_date_and_time`, `trip_end_date_and_time`, `departure_type` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
            $trip_start_date_and_time = date('H:i:s', strtotime($fetch_list_data['trip_start_date_and_time']));
            $trip_end_date_and_time = date('H:i:s', strtotime($fetch_list_data['trip_end_date_and_time']));
            $departure_type = $fetch_list_data["departure_type"];

            if ($departure_type == '1') :
                $global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
            elseif ($departure_type == '2') :
                $global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
            elseif ($departure_type == '3') :
                $global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
            endif;
        endwhile;

        $_hotspot_start_time = date('H:i:s', strtotime($_hotspot_start_time));
        $_hotspot_end_time = date('H:i:s', strtotime($_hotspot_end_time));

        if ($travelling_end_time >= $_hotspot_end_time) :
            $errors['minimum_trip_end_time_should_be_required'] = 'End time should be ' . date('g:i A', strtotime($travelling_end_time)) . ' or greater than';
        endif;

        if ($_hotspot_start_time != '') :
            if ($_itinerary_route_counter == $_total_itinerary_route_count && ($_hotspot_start_time >= $trip_end_date_and_time)) :
                $errors['hotspot_start_time_exceed'] = true;
            endif;

            $global_setting_route_end_time = date('H:i:s', strtotime($trip_end_date_and_time . ' -' . date('g', strtotime($global_setting_end_buffer_time)) . ' hour' . date('i', strtotime($global_setting_end_buffer_time)) . 'min'));

            if ($_itinerary_route_counter == $_total_itinerary_route_count && ($_hotspot_start_time >= $global_setting_route_end_time)) :
                $errors['hotspot_buffer_exceed'] = 'Start time should be lesser than ' . date('g:i A', strtotime($global_setting_route_end_time));
            endif;

            if ($_hotspot_start_time >= $_hotspot_end_time) :
                $errors['hotspot_start_and_end_time_exceed'] = true;
            endif;
        else :
            if ($_hotspot_start_time == '' && $_hotspot_end_time == '') :
                $errors['hotspot_end_time_required'] = true;
                $errors['hotspot_start_time_required'] = true;
            elseif ($_hotspot_start_time == '') :
                $errors['hotspot_start_time_required'] = true;
            endif;
        endif;

        if ($_hotspot_end_time != '') :
            if ($_itinerary_route_counter == '1' && ($_hotspot_end_time <= $trip_start_date_and_time)) :
                $errors['hotspot_end_time_exceed'] = true;
            endif;

            if ($_hotspot_start_time >= $_hotspot_end_time) :
                $errors['hotspot_start_and_end_time_exceed'] = true;
            endif;
        else :
            if ($_hotspot_start_time == '' && $_hotspot_end_time == '') :
                $errors['hotspot_start_time_required'] = true;
                $errors['hotspot_end_time_required'] = true;
            elseif ($_hotspot_end_time == '') :
                $errors['hotspot_end_time_required'] = true;
            endif;
        endif;

        $minimum_hotspot_ending_hour = date('H:i:s', strtotime($_hotspot_start_time . ' +' . date('g', strtotime($itinerary_common_buffer_time)) . ' hour' . date('i', strtotime($itinerary_common_buffer_time)) . 'min'));

        if ($_hotspot_end_time < $minimum_hotspot_ending_hour) :
            $errors['hotspot_minimum_hotspot_ending_hour_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call
            $response['success'] = true;

            $hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
            $hotspot_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "1", "1", "$itinerary_common_buffer_time", "$_hotspot_start_time", "$minimum_hotspot_ending_hour", "$logged_user_id", "1");

            $select_itineary_refresh_buffer_time = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '0' AND `item_type` = '1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $select_itineary_refresh_buffer_time_count = sqlNUMOFROW_LABEL($select_itineary_refresh_buffer_time);

            if ($select_itineary_refresh_buffer_time_count == 0) :
                //INSERT ITINEARY ROUTE HOTSPOT DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, '')) :

                    $arrFields = array('`route_start_time`', '`route_end_time`', '`createdby`', '`status`');
                    $arrValues = array("$_hotspot_start_time", "$_hotspot_end_time", "$logged_user_id", "1");

                    if ($_itinerary_route_ID != '' && $_itinerary_route_ID != 0 && $_itinerary_plan_ID != '' && $_itinerary_plan_ID != 0) :
                        $sqlWhere = " `itinerary_route_ID` = '$_itinerary_route_ID' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' ";
                        //UPDATE ITINEARY ROUTE DETAILS
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $arrFields, $arrValues, $sqlWhere)) :
                            $response['u_result'] = true;
                            $response['result_success'] = true;
                        else :
                            $response['u_result'] = false;
                            $response['result_success'] = false;
                        endif;
                    endif;
                endif;
            else :
                //UPDATE ITINEARY ROUTE HOTSPOT DETAILS
                $route_hotspot_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '0' AND `item_type` = '1' ";

                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, $route_hotspot_sqlWhere)) :

                    $arrFields = array('`route_start_time`', '`route_end_time`', '`createdby`', '`status`');
                    $arrValues = array("$_hotspot_start_time", "$_hotspot_end_time", "$logged_user_id", "1");

                    if ($_itinerary_route_ID != '' && $_itinerary_route_ID != 0 && $_itinerary_plan_ID != '' && $_itinerary_plan_ID != 0) :
                        $sqlWhere = " `itinerary_route_ID` = '$_itinerary_route_ID' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' ";
                        //UPDATE ITINEARY ROUTE DETAILS
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $arrFields, $arrValues, $sqlWhere)) :
                            $response['u_result'] = true;
                            $response['result_success'] = true;
                        else :
                            $response['u_result'] = false;
                            $response['result_success'] = false;
                        endif;
                    endif;
                endif;
            endif;
        endif;

        if ($direct_to_next_visiting_place == 1) :

            $travel_distance = calculateTravelDistanceAndTime($start_location_id);
            $_hotspot_order = 2;
            $_distance = $travel_distance['distance'];
            $_time = $travel_distance['duration'];
            $_end_time = getITINEARY_ROUTE_HOTSPOT_DETAILS('2', $_itinerary_plan_ID, $_itinerary_route_ID, 'hotspot_end_time');
            $_hotspot_id = $hotspot_id;
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

            $trip_start_time = $_end_time;
            $trip_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

            if ($route_end_time <= $trip_end_time) :
                $hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`custom_description`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                $hotspot_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "2", "$next_visiting_location", "2", "$formatted_time", "$_distance", "$trip_start_time", "$trip_end_time", "$logged_user_id", "1");

                $select_itineary_refresh_buffer_time = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '0' AND `item_type` = '2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $select_itineary_refresh_buffer_time_count = sqlNUMOFROW_LABEL($select_itineary_refresh_buffer_time);

                if ($select_itineary_refresh_buffer_time_count == 0) :
                    //INSERT ITINEARY ROUTE HOTSPOT DETAILS
                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, '')) :
                        $response['i_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['i_result'] = false;
                        $response['result_success'] = false;
                    endif;
                else :
                    //UPDATE ITINEARY ROUTE HOTSPOT DETAILS
                    $hotspot_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '0' AND `item_type` = '2' ";

                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, $hotspot_sqlWhere)) :
                        $response['u_result'] = true;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;
                endif;
            endif;
        else :
        // return to hotel
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'add_new_hotspots') :

        $errors = [];
        $response = [];

        $end_latitude = $_POST['hotspot_latitude'];
        $end_longitude = $_POST['hotspot_longitude'];
        $_hotspot_id = $_POST['hotspot_id'];
        $_itinerary_route_ID = $_POST['itinerary_route_ID'];
        $_itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $_dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];

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
        /* print_r($travel_distance);
            exit; */

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

            $select_hotspot_distance_calculation_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`hotspot_travelling_distance` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' AND HOTSPOT_DETAILS.`item_type` = '2' ORDER BY HOTSPOT_DETAILS.`hotspot_order` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
            $total_hotspot_distance_calculate_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_distance_calculation_list_query);
            if ($total_hotspot_distance_calculate_list_num_rows_count > 0) :
                while ($fetch_hotspot_distance_list_data = sqlFETCHARRAY_LABEL($select_hotspot_distance_calculation_list_query)) :
                    $hotspot_ID = $fetch_hotspot_distance_list_data['hotspot_ID'];
                    $hotspot_travelling_distance = $fetch_hotspot_distance_list_data['hotspot_travelling_distance'];
                endwhile;

                $get_start_location_id = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'get_starting_location_id');
                $get_start_latitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $get_start_location_id);
                $get_end_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $get_start_location_id);

                $check_travelling_distance = calculateDistanceAndDuration($get_start_latitude, $get_end_longitude, $end_latitude, $end_longitude);

                /* echo $hotspot_travelling_distance;
                    echo "<br>"; */
                $next_hotspot_travelling_distance = round($check_travelling_distance['distance'], 1);

                if ($hotspot_travelling_distance >= $next_hotspot_travelling_distance) :
                    $errors['hotspot_distance_calculate_checker'] = true;
                    $errors['previous_hotspot_place'] = $hotspot_ID;
                    $errors['next_hotspot_place'] = $_hotspot_id;
                    $errors['itinerary_plan_ID'] = $_itinerary_plan_ID;
                    $errors['itinerary_route_ID'] = $_itinerary_route_ID;
                else :
                //
                endif;
            endif;

            /* echo $start_latitude;
            echo "<br>";
            echo $start_longitude;
            echo "<br>";
            echo $end_latitude;
            echo "<br>";
            echo $end_longitude;
            echo "<br>"; */

            $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);

            /* print_r($travel_distance);
            print_r($check_travelling_distance);
            exit; */

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

        echo json_encode($response);

    elseif ($_GET['type'] == 'add_hotel_day_wise') :

        $errors = [];
        $response = [];

        $end_latitude = $_POST['latitude'];
        $end_longitude = $_POST['longitude'];
        $_itinerary_plan_hotel_details_ID = $_POST['itinerary_plan_hotel_details_ID'];
        $_itinerary_route_ID = $_POST['itinerary_route_ID'];
        $_itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $_dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];

        if (empty($_itinerary_plan_hotel_details_ID)) :
            $errors['itinerary_plan_hotel_details_ID_required'] = true;
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

        $select_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`,HOTSPOT_PLACES.`hotspot_duration`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' ORDER BY HOTSPOT_DETAILS.`hotspot_order` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
        $total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
        if ($total_hotspot_list_num_rows_count > 0) :
            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                $hotspot_order = $fetch_hotspot_list_data['hotspot_order'] + 1;
                $hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
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

        if ($hotspot_ID == 0) :
            $start_location_id = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'get_starting_location_id');
            $start_longitude = getITINEARYROUTE_DETAILS('', '', 'location_latitude', $start_location_id);
            $end_longitude = getITINEARYROUTE_DETAILS('', '', 'location_longtitude', $start_location_id);
            $travel_distance = calculateTravelDistanceAndTime($start_location_id);
            $_distance = $travel_distance['distance'];
            $_time = $travel_distance['duration'];
        else :
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

        $_itinerary_plan_hotel_details_ID = $_itinerary_plan_hotel_details_ID;
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

            $hotspot_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`itinerary_plan_hotel_details_ID`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
            $hotspot_arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "2", "$_itinerary_plan_hotel_details_ID", "$_hotspot_order", "$formatted_time", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

            $select_itineary_hotspot_details = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `itinerary_plan_hotel_details_ID` = '$_itinerary_plan_hotel_details_ID' AND `item_type` = '4'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $select_tineary_hotspot_details_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_details);

            if ($select_tineary_hotspot_details_count == 0) :
                //INSERT ITINEARY ROUTE HOTSPOT DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, '')) :

                    $route_day_end_time = $route_end_time;

                    $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                        $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                        $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                        $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                    endwhile;

                    $hotspot_duration = getGLOBALSETTING('itinerary_common_buffer_time');

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

                        $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_order`', '`itinerary_plan_hotel_details_ID`', '`item_type`', '`hotspot_entry_time_label`', '`hotspot_amout`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                        $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_order", "$_itinerary_plan_hotel_details_ID", "4", "$_dayOfWeekNumeric", "$_hotspot_amout", "$hotspot_duration", "$new_hotspots_start_time", "$new_hotspots_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");

                        //INSERT
                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, '')) :
                            $route_hotspot_ID = sqlINSERTID_LABEL();
                            $response['i_result'] = true;
                            $response['result_success'] = true;
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
                endif;
            else :
                //UPDATE ITINEARY ROUTE HOTSPOT DETAILS
                $hotspot_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID' AND `hotspot_travelling_distance` IS NOT NULL AND `hotspot_entry_time_label` IS NULL AND `hotspot_ID` = '$_hotspot_id' AND `item_type` = '2' ";

                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $hotspot_arrFields, $hotspot_arrValues, $hotspot_sqlWhere)) :

                endif;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'remove_itinerary_route_hotspot_details') :

        $errors = [];
        $response = [];

        $__hotspot_id = $_POST['hotspot_id'];
        $_itinerary_route_ID = $_POST['itinerary_route_ID'];
        $_itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $_dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];

        if (empty($__hotspot_id)) :
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

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_ID`, HOTSPOT_DETAILS.`itinerary_route_ID`, HOTSPOT_DETAILS.`item_type`, HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' AND HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' AND HOTSPOT_DETAILS.`status`='1' AND HOTSPOT_DETAILS.`deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
            $total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);

            if ($total_route_hotspot_list_num_rows_count > 0) :
                while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
                    $counter++;
                    $route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
                    $itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
                    $itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
                    $item_type = $fetch_route_hotspot_list_data['item_type'];
                    $hotspot_order = $fetch_route_hotspot_list_data['hotspot_order'];
                    $hotspot_id = $fetch_route_hotspot_list_data['hotspot_ID'];
                    $hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
                    $hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
                    $hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
                    $hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];
                    $end_latitude = $fetch_route_hotspot_list_data['hotspot_latitude'];
                    $end_longitude = $fetch_route_hotspot_list_data['hotspot_longitude'];

                    if ($hotspot_id == $__hotspot_id) :
                        $re_order = '1';
                        $update_hotpot_order = $hotspot_order - 2;
                        $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$__hotspot_id'";

                        //INSERT
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $sqlWhere)) :

                            $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$__hotspot_id' AND `activity_entry_time_label`='$_dayOfWeekNumeric' ";
                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_details", '', '', $sqlWhere)) :
                            endif;

                            $parking_charges_sqlWhere = " `itinerary_plan_ID` = '$_itinerary_plan_ID' and `itinerary_route_ID` = '$_itinerary_route_ID' and `hotspot_ID`='$__hotspot_id' ";
                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $parking_charges_sqlWhere)) :
                            endif;

                            if ($total_route_hotspot_list_num_rows_count == $counter) :
                                $response['i_result'] = true;
                                $response['result_success'] = true;
                                $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                            endif;

                        endif;

                    elseif ($re_order == '1') :
                        $previous_hotpot_order = $update_hotpot_order;

                        $select_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' and HOTSPOT_DETAILS.`hotspot_order`='$previous_hotpot_order' ORDER BY HOTSPOT_DETAILS.`hotspot_order`") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());

                        $total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
                        if ($total_hotspot_list_num_rows_count > 0 && $previous_hotpot_order != '0') :
                            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                                $update_hotpot_order = $fetch_hotspot_list_data['hotspot_order'] + 1;
                                //$hotspot_ID = $fetch_hotspot_list_data['hotspot_ID'];
                                $start_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
                                $start_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
                                $hotspot_traveling_time = $fetch_hotspot_list_data['hotspot_traveling_time'];
                                $hotspot_travelling_distance = $fetch_hotspot_list_data['hotspot_travelling_distance'];
                                $start_time = $fetch_hotspot_list_data['hotspot_start_time'];
                                $end_time = $fetch_hotspot_list_data['hotspot_end_time'];
                            endwhile;
                        else :
                            $update_hotpot_order = '1';

                            $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `location_id`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID`='$_itinerary_route_ID' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                            $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                            if ($total_itinerary_plan_rows_count > 0) :
                                while ($fetch_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_query)) :
                                    $location_id = $fetch_list_plan_data['location_id'];
                                    $start_latitude = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'location_latitude', $location_id);
                                    $start_longitude = getITINEARYROUTE_DETAILS($_itinerary_plan_ID, $_itinerary_route_ID, 'location_longtitude', $location_id);
                                    $start_time = $fetch_list_plan_data['route_start_time'];
                                    $start_time = date('H:i:s', strtotime($start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                                    $end_time = $fetch_list_plan_data['route_end_time'];
                                endwhile;
                            endif;
                        endif;

                        $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);
                        $_hotspot_order = $update_hotpot_order;
                        $_distance = round($travel_distance['distance'], 1);
                        $_time = $travel_distance['duration'];
                        $_start_time = $start_time;
                        $_end_time = $end_time;
                        $_hotspot_id = $hotspot_id;
                        $_itinerary_route_ID = $_itinerary_route_ID;
                        $_itinerary_plan_ID = $_itinerary_plan_ID;
                        $_dayOfWeekNumeric = $_dayOfWeekNumeric;

                        // Use regular expressions to extract hours and minutes
                        preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);

                        $time_seconds = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                        $timeMinutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                        // Convert hours and minutes to seconds
                        $totalSeconds = round(($time_seconds * 3600) + ($timeMinutes * 60));

                        // Format the seconds into 'H:i:s'
                        $formattedTime = gmdate('H:i:s', $totalSeconds);

                        // Extract hours and minutes from the duration string
                        preg_match('/(\d+) hour/', $_time, $hours_match);
                        preg_match('/(\d+) min/', $_time, $minutes_match);

                        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                        // Format the time as H:i:s
                        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                        if ($_hotspot_order == '1') :
                            if ($item_type == '2') :
                                $hotspot_start_time = date('H:i:s', strtotime($_start_time));
                            elseif ($item_type == '3') :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_start_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_start_time = date('H:i:s', $total_seconds);
                            endif;
                        else :
                            if ($item_type == '2') :
                                $hotspot_start_time = date('H:i:s', strtotime($_end_time));
                            elseif ($item_type == '3') :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_end_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_start_time = date('H:i:s', $total_seconds);
                            endif;
                        endif;

                        if ($item_type == '2') :

                            if ($_hotspot_order == '1') :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_start_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_end_time = date('H:i:s', $total_seconds);
                            else :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_end_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_end_time = date('H:i:s', $total_seconds);
                            endif;
                        elseif ($item_type == '3') :
                            $hotspot_end_time = date('H:i:s', strtotime($hotspot_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                        endif;
                        $hotspot_activity_skipping = '0';

                        $select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID`='$_itinerary_route_ID' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_route_details_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query);
                        $route_day_end_time = $fetch_route_details_list_plan_data['route_end_time'];

                        if ($item_type != '4') :
                            $select_hotspot_places_query = sqlQUERY_LABEL("SELECT `hotspot_timing_status` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                            $fetch_hotspot_places_list_data = sqlFETCHARRAY_LABEL($select_hotspot_places_query);
                            $hotspot_timing_status = $fetch_hotspot_places_list_data['hotspot_timing_status'];

                            $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((`hotspot_start_time` <= '$hotspot_start_time' AND `hotspot_end_time` >= '$hotspot_end_time') OR `hotspot_open_all_time` = '1' )") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                            $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                        elseif ($item_type == '4') :
                            $selected_itinerary_hotel_query = sqlQUERY_LABEL("SELECT `hotel_id`, `room_id` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `status`='1' AND `deleted`='0' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' AND `itinerary_route_ID` = '$_itinerary_route_ID'") or die("#1-getITINEARY_ROUTE_HOTSPOT_DETAILS: " . sqlERROR_LABEL());
                            $fetch_itinerary_hotel_data = sqlFETCHARRAY_LABEL($selected_itinerary_hotel_query);
                            $hotel_id = $fetch_itinerary_hotel_data['hotel_id'];
                            $room_id = $fetch_itinerary_hotel_data['room_id'];
                            $check_in_time = getROOM_DETAILS($room_id, 'check_in_time');
                            $check_in_time = getROOM_DETAILS($room_id, 'check_in_time');

                            if ($check_in_time <= $hotspot_start_time) :
                                $total_itinerary_plan_rows_count = 1;
                            endif;
                        endif;

                        if ($total_itinerary_plan_rows_count > 0 || $hotspot_timing_status == '0') :
                            if (date('H:i:s', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) > $hotspot_end_time) :

                                $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                                    $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                                    $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                                    $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                                endwhile;

                                $select_hotspot_place_list_query = sqlQUERY_LABEL("SELECT `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_hotspot_place_list_data = sqlFETCHARRAY_LABEL($select_hotspot_place_list_query)) :
                                    $hotspot_total_adult_entry_cost = $fetch_hotspot_place_list_data['hotspot_adult_entry_cost'] * $total_adult;
                                    $hotspot_total_child_entry_cost = $fetch_hotspot_place_list_data['hotspot_child_entry_cost'] * $total_children;
                                    $hotspot_total_infant_entry_cost = $fetch_hotspot_place_list_data['hotspot_infant_entry_cost'] * $total_infants;
                                endwhile;

                                $_hotspot_amout = $hotspot_total_adult_entry_cost + $hotspot_total_child_entry_cost + $hotspot_total_infant_entry_cost;

                                $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_order`', '`hotspot_ID`', '`hotspot_entry_time_label`', '`hotspot_amout`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                                $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_order", "$_hotspot_id", "$_dayOfWeekNumeric", "$_hotspot_amout", "$formattedTime", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");
                                $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `route_hotspot_ID`='$route_hotspot_ID' ";

                                //INSERT
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere)) :

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

                                    $response['i_result'] = true;
                                    $response['result_success'] = true;
                                    $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                                $response['hotspot_day_time_over_status'] = true;
                                $response['hotspot_day_time_over'] = 'Tour ends at ' . date('g:i A', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' for that day; please update day end time for hotspot additions.';
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
                        endif;

                    endif;
                endwhile;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'remove_itinerary_route_hotel_details') :

        $errors = [];
        $response = [];

        $_itinerary_plan_hotel_details_ID = $_POST['itinerary_plan_hotel_details_ID'];
        $_itinerary_route_ID = $_POST['itinerary_route_ID'];
        $_itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $_dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];

        if (empty($_itinerary_plan_hotel_details_ID)) :
            $errors['itinerary_plan_hotel_details_ID_required'] = true;
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

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_ID`, HOTSPOT_DETAILS.`itinerary_route_ID`, HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`item_type`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_hotel_details_ID`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' AND HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' AND HOTSPOT_DETAILS.`status`='1' AND HOTSPOT_DETAILS.`deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
            $total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);

            if ($total_route_hotspot_list_num_rows_count > 0) :
                while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
                    $counter++;
                    $route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
                    $itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
                    $itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
                    $hotspot_order = $fetch_route_hotspot_list_data['hotspot_order'];
                    $item_type = $fetch_route_hotspot_list_data['item_type'];
                    $hotspot_ID = $fetch_route_hotspot_list_data['hotspot_ID'];
                    $itinerary_plan_hotel_details_ID = $fetch_route_hotspot_list_data['itinerary_plan_hotel_details_ID'];
                    $hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
                    $hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
                    $hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
                    $hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];

                    $select_itinerary_hotel_details = sqlQUERY_LABEL("SELECT `hotel_id` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `status` = '1' and  `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_details)) :
                        $count++;
                        $hotel_id = $fetch_hotel_data['hotel_id'];
                        $end_latitude = getHOTELDETAILS($hotel_id, 'hotel_latitude');
                        $end_longitude = getHOTELDETAILS($hotel_id, 'hotel_longitude');
                    endwhile;

                    if ($itinerary_plan_hotel_details_ID == $_itinerary_plan_hotel_details_ID) :
                        $re_order = '1';
                        $update_hotpot_order = $hotspot_order - 2;
                        $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `itinerary_plan_hotel_details_ID`='$_itinerary_plan_hotel_details_ID'";

                        //INSERT
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $sqlWhere)) :
                            if ($total_route_hotspot_list_num_rows_count == $counter) :
                                $response['i_result'] = true;
                                $response['result_success'] = true;
                                $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                            endif;
                        endif;

                    elseif ($re_order == '1') :

                        $previous_hotpot_order = $update_hotpot_order;

                        $select_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' and HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' and HOTSPOT_DETAILS.`hotspot_order`='$previous_hotpot_order' ORDER BY HOTSPOT_DETAILS.`hotspot_order`") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());

                        $total_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_list_query);
                        if ($total_hotspot_list_num_rows_count > 0 && $previous_hotpot_order != '0') :
                            while ($fetch_hotspot_list_data = sqlFETCHARRAY_LABEL($select_hotspot_list_query)) :
                                $update_hotpot_order = $fetch_hotspot_list_data['hotspot_order'] + 1;
                                $hotspot_id = $fetch_hotspot_list_data['hotspot_ID'];
                                $start_latitude = $fetch_hotspot_list_data['hotspot_latitude'];
                                $start_longitude = $fetch_hotspot_list_data['hotspot_longitude'];
                                $hotspot_traveling_time = $fetch_hotspot_list_data['hotspot_traveling_time'];
                                $hotspot_travelling_distance = $fetch_hotspot_list_data['hotspot_travelling_distance'];
                                $_start_time = $fetch_hotspot_list_data['hotspot_start_time'];
                                $_end_time = $fetch_hotspot_list_data['hotspot_end_time'];
                            endwhile;
                        else :
                            $update_hotpot_order = '1';

                            $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID`='$_itinerary_route_ID' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                            $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                            if ($total_itinerary_plan_rows_count > 0) :
                                while ($fetch_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_query)) :
                                    $location_name = $fetch_list_plan_data['location_name'];
                                    $start_latitude = $fetch_list_plan_data['location_latitude'];
                                    $start_longitude = $fetch_list_plan_data['location_longtitude'];
                                    $start_time = $fetch_list_plan_data['route_start_time'];
                                    $_start_time = date('H:i:s', strtotime($start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                                    $_end_time = $fetch_list_plan_data['route_end_time'];
                                endwhile;
                            endif;
                        endif;

                        $travel_distance = calculateDistanceAndDuration($start_latitude, $start_longitude, $end_latitude, $end_longitude);
                        $_hotspot_order = $update_hotpot_order;
                        $_distance = round($travel_distance['distance'], 1);
                        $_time = $travel_distance['duration'];
                        $_start_time = $_start_time;
                        $_end_time = $_end_time;
                        $_hotspot_id = $hotspot_id;
                        $_itinerary_route_ID = $_itinerary_route_ID;
                        $_itinerary_plan_ID = $_itinerary_plan_ID;
                        $_dayOfWeekNumeric = $_dayOfWeekNumeric;

                        // Use regular expressions to extract hours and minutes
                        preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);

                        $time_seconds = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                        $timeMinutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                        // Convert hours and minutes to seconds
                        $totalSeconds = round(($time_seconds * 3600) + ($timeMinutes * 60));

                        // Format the seconds into 'H:i:s'
                        $formattedTime = gmdate('H:i:s', $totalSeconds);

                        // Extract hours and minutes from the duration string
                        preg_match('/(\d+) hour/', $_time, $hours_match);
                        preg_match('/(\d+) min/', $_time, $minutes_match);

                        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                        // Format the time as H:i:s
                        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                        if ($_hotspot_order == '1') :
                            if ($item_type == '2') :
                                $hotspot_start_time = date('H:i:s', strtotime($_start_time));
                            elseif ($item_type == '3') :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_start_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_start_time = date('H:i:s', $total_seconds);
                            endif;
                        else :
                            if ($item_type == '2') :
                                $hotspot_start_time = date('H:i:s', strtotime($_end_time));
                            elseif ($item_type == '3') :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_end_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_start_time = date('H:i:s', $total_seconds);
                            endif;
                        endif;

                        if ($item_type == '2') :

                            if ($_hotspot_order == '1') :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_start_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_end_time = date('H:i:s', $total_seconds);
                            else :
                                // Convert time string 1 to a Unix timestamp
                                $time1_unix = strtotime($_end_time);

                                // Extract hours and minutes from time string 2
                                preg_match('/(\d+\.\d+) hour (\d+\.\d+) mins/', $_time, $matches);
                                $time2_hours = isset($matches[1]) ? (float)$matches[1] : 0; // hours
                                $time2_minutes = isset($matches[2]) ? (float)$matches[2] : 0; // minutes

                                // Convert hours and minutes to seconds
                                $time2_seconds = $time2_hours * 3600 + $time2_minutes * 60;

                                // Add the seconds from time string 2 to the Unix timestamp of time string 1
                                $total_seconds = $time1_unix + $time2_seconds;

                                // Convert the total seconds back to a formatted time string
                                $hotspot_end_time = date('H:i:s', $total_seconds);
                            endif;
                        elseif ($item_type == '3') :
                            $hotspot_end_time = date('H:i:s', strtotime($hotspot_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                        endif;
                        $hotspot_activity_skipping = '0';

                        $select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID`='$_itinerary_route_ID' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_route_details_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query);
                        $route_day_end_time = $fetch_route_details_list_plan_data['route_end_time'];

                        $select_hotspot_places_query = sqlQUERY_LABEL("SELECT `hotspot_timing_status` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_hotspot_places_list_data = sqlFETCHARRAY_LABEL($select_hotspot_places_query);
                        $hotspot_timing_status = $fetch_hotspot_places_list_data['hotspot_timing_status'];

                        $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((`hotspot_start_time` <= '$hotspot_start_time' AND `hotspot_end_time` >= '$hotspot_end_time') OR `hotspot_open_all_time` = '1' )") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);

                        if ($total_itinerary_plan_rows_count > 0 || $hotspot_timing_status == '0') :
                            if (date('H:i:s', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) > $hotspot_end_time) :

                                $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                                    $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                                    $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                                    $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                                endwhile;

                                $select_hotspot_place_list_query = sqlQUERY_LABEL("SELECT `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_hotspot_place_list_data = sqlFETCHARRAY_LABEL($select_hotspot_place_list_query)) :
                                    $hotspot_total_adult_entry_cost = $fetch_hotspot_place_list_data['hotspot_adult_entry_cost'] * $total_adult;
                                    $hotspot_total_child_entry_cost = $fetch_hotspot_place_list_data['hotspot_child_entry_cost'] * $total_children;
                                    $hotspot_total_infant_entry_cost = $fetch_hotspot_place_list_data['hotspot_infant_entry_cost'] * $total_infants;
                                endwhile;

                                $_hotspot_amout = $hotspot_total_adult_entry_cost + $hotspot_total_child_entry_cost + $hotspot_total_infant_entry_cost;

                                $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_order`', '`hotspot_ID`', '`hotspot_entry_time_label`', '`hotspot_amout`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                                $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_order", "$_hotspot_id", "$_dayOfWeekNumeric", "$_hotspot_amout", "$formattedTime", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");
                                $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `route_hotspot_ID`='$route_hotspot_ID' ";

                                //INSERT
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere)) :

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

                                    $response['i_result'] = true;
                                    $response['result_success'] = true;
                                    $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                                $response['hotspot_day_time_over_status'] = true;
                                $response['hotspot_day_time_over'] = 'Tour ends at ' . date('g:i A', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' for that day; please update day end time for hotspot additions.';
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
                        endif;

                    endif;
                endwhile;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'itinerary_route_activity_details') :

    elseif ($_GET['type'] == 'itinerary_route_activity_details') :

        $errors = [];
        $response = [];

        $_route_hotspot_ID  = $_POST['route_hotspot_ID'];
        $_activity_id  = $_POST['activity_id'];
        $_dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];
        $_hotspot_id = $_POST['hotspot_id'];

        if (empty($_route_hotspot_ID)) :
            $errors['route_hotspot_ID_required'] = true;
        endif;
        if (empty($_activity_id)) :
            $errors['activity_id_required'] = true;
        endif;
        if ($_dayOfWeekNumeric == '') :
            $errors['dayOfWeekNumeric_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $select_route_activity_list_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `hotspot_ID`, `hotspot_start_time`, `hotspot_end_time`, `hotspot_activity_skipping` FROM `dvi_itinerary_route_hotspot_details` WHERE `route_hotspot_ID`='$_route_hotspot_ID' AND `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
            $total_route_activity_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_activity_list_query);
            if ($total_route_activity_list_num_rows_count > 0) :
                while ($fetch_route_activity_list_data = sqlFETCHARRAY_LABEL($select_route_activity_list_query)) :
                    $counter++;
                    $_itinerary_plan_ID = $fetch_route_activity_list_data['itinerary_plan_ID'];
                    $_itinerary_route_ID = $fetch_route_activity_list_data['itinerary_route_ID'];
                    //$_hotspot_id = $fetch_route_activity_list_data['hotspot_ID'];
                    $_start_time = $fetch_route_activity_list_data['hotspot_start_time'];
                    $_hotspot_end_time = $fetch_route_activity_list_data['hotspot_end_time'];
                    $_hotspot_activity_skipping = $fetch_route_activity_list_data['hotspot_activity_skipping'];
                endwhile;
            endif;

            $select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_ID`, HOTSPOT_DETAILS.`itinerary_route_ID`, HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' AND HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' AND HOTSPOT_DETAILS.`status`='1' AND HOTSPOT_DETAILS.`deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
            $total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);

            if ($total_route_hotspot_list_num_rows_count > 0) :
                while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
                    $route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
                    $itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
                    $itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
                    $hotspot_order = $fetch_route_hotspot_list_data['hotspot_order'];
                    $hotspot_id = $fetch_route_hotspot_list_data['hotspot_ID'];
                    $hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
                    $hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
                    $hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
                    $hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];
                    $end_latitude = $fetch_route_hotspot_list_data['hotspot_latitude'];
                    $end_longitude = $fetch_route_hotspot_list_data['hotspot_longitude'];

                    if ($hotspot_id == $_hotspot_id) :
                        $reorder_hotspot = '1';
                        $start_latitude = $fetch_route_hotspot_list_data['hotspot_latitude'];
                        $start_longitude = $fetch_route_hotspot_list_data['hotspot_longitude'];

                        $select_activity_list_query = sqlQUERY_LABEL("SELECT `route_activity_ID`, `activity_order`, `activity_ID`, `activity_entry_time_label`, `activity_start_time`, `activity_end_time` FROM dvi_itinerary_route_activity_details WHERE `hotspot_ID`='$_hotspot_id' AND `itinerary_plan_ID`= '$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `route_hotspot_ID`='$_route_hotspot_ID' AND `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                        $total_activity_count = sqlNUMOFROW_LABEL($select_activity_list_query);

                        if ($total_activity_count > 0) :
                            while ($fetch_activity_data = sqlFETCHARRAY_LABEL($select_activity_list_query)) :
                                $_route_activity_ID = $fetch_activity_data['route_activity_ID'];
                                $_activity_order = $fetch_activity_data['activity_order'];
                                $_activity_ID = $fetch_activity_data['activity_ID'];
                                $_activity_entry_time_label = $fetch_activity_data['activity_entry_time_label'];
                                $_activity_start_time = $fetch_activity_data['activity_start_time'];
                                $_activity_end_time = $fetch_activity_data['activity_end_time'];

                                /*if ($_activity_id == $_activity_ID) :
                                    $_reorder_activity = '1';
                                    $update_activity_order = $_activity_order;
                                    $previous_activity_start_time = $fetch_activity_data['activity_start_time'];

                                    $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `activity_entry_time_label`='$_dayOfWeekNumeric' AND `activity_ID`='$_activity_id' ";

                                    //DELETE
                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_details", '', '', $sqlWhere)) :
                                        if ($total_activity_count == '1') :
                                            $activity_end_time = $previous_activity_start_time;
                                        endif;

                                        $response['i_result'] = true;
                                        $response['result_success'] = true;
                                        $response['itinerary_plan_ID'] = $_itinerary_plan_ID;
                                        $response['itinerary_route_ID'] = $_itinerary_route_ID;
										$response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                    else :
                                        $response['i_result'] = false;
                                        $response['result_success'] = false;
                                    endif;
                                elseif ($_reorder_activity == '1') :*/
                                $previous_activity_order = $update_activity_order - 1;

                                $activity_start_time = $previous_activity_start_time;

                                $activity_duration_DB = getACTIVITYDETAILS($_activity_ID, 'activity_duration');
                                $activity_duration = getACTIVITYDETAILS($_activity_ID, 'activity_duration');
                                $activity_duration = date('H', strtotime($activity_duration)) . 'hours ' . date('i', strtotime($activity_duration)) . 'mins';

                                $activity_end_time = date('H:i:s', strtotime($previous_activity_start_time . ' +' . $activity_duration));

                                $activity_start_time = date('H:i:s', strtotime($hotspot_start_time));
                                // $activity_start_time = date('H:i:s', strtotime($_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));

                                $activity_duration_DB = getACTIVITYDETAILS($_activity_id, 'activity_duration');
                                $activity_duration = getACTIVITYDETAILS($_activity_id, 'activity_duration');

                                $activity_duration = date('H', strtotime($activity_duration)) . 'hours ' . date('i', strtotime($activity_duration)) . 'mins';

                                //$activity_end_time = date('H:i:s', strtotime($activity_start_time . ' +' . $activity_duration));
                                $activity_end_time = date('H:i:s', strtotime($activity_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                                $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                                    $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                                    $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                                    $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                                endwhile;

                                $select_itinerary_route_details_list_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                $fetch_itinerary_route_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_list_query);
                                $itinerary_route_date = $fetch_itinerary_route_details_list_data['itinerary_route_date'];

                                $month = date('F', strtotime($itinerary_route_date));
                                $year = date('Y', strtotime($itinerary_route_date));
                                $date = 'day_' . date('n', strtotime($itinerary_route_date));
                                $itinerary_date = date('Y-m-d', strtotime($itinerary_route_date));

                                $selected_pricebook_query = sqlQUERY_LABEL("SELECT `year`, `month`, `price_type`, `$date` FROM `dvi_activity_pricebook` WHERE `activity_id`='$_activity_ID' AND `status`='1' and `deleted` = '0' AND `year`='$year' AND `month`='$month'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                $activity_pricebook_query = sqlNUMOFROW_LABEL($selected_pricebook_query);

                                if ($activity_pricebook_query > 0) :
                                    while ($fetch_pricebook_list_data = sqlFETCHARRAY_LABEL($selected_pricebook_query)) :
                                        $price_type = $fetch_pricebook_list_data['price_type'];
                                        $price = $fetch_pricebook_list_data[$date];
                                        if ($price_type == '1') :
                                            $activity_total_adult_entry_cost = $price * $total_adult;
                                        elseif ($price_type == '2') :
                                            $activity_total_child_entry_cost = $price * $total_children;
                                        elseif ($price_type == '3') :
                                            $activity_total_infant_entry_cost = $price * $total_infants;
                                        endif;
                                    endwhile;
                                    $_activity_amout = $activity_total_adult_entry_cost + $activity_total_child_entry_cost + $activity_total_infant_entry_cost;
                                else :
                                    $_activity_amout = 0;
                                endif;

                                $arrFields_activity = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`route_hotspot_ID`', '`activity_traveling_time`', '`activity_order`', '`activity_ID`', '`activity_entry_time_label`', '`activity_amout`', '`activity_start_time`', '`activity_end_time`', '`createdby`', '`status`');
                                $arrValues_activity = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_id", "$_route_hotspot_ID", "$activity_duration_DB", "$previous_activity_order", "$_activity_ID", "$_dayOfWeekNumeric", "$_activity_amout", "$activity_start_time", "$activity_end_time", "$logged_user_id", "1");

                                $sqlWhere_activity = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `activity_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$_route_hotspot_ID' AND `activity_ID`='$_activity_ID' ";

                                //UPDATE
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_activity_details", $arrFields_activity, $arrValues_activity, $sqlWhere_activity)) :
                                    $response['i_result'] = true;
                                    $response['result_success'] = true;
                                    $response['itinerary_plan_ID'] = $_itinerary_plan_ID;
                                    $response['itinerary_route_ID'] = $_itinerary_route_ID;
                                    $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            //endif;
                            //$update_activity_order++;
                            endwhile;
                            $end_time = $activity_end_time;
                            $arrFields = array('`hotspot_end_time`');
                            $arrValues = array("$activity_end_time");
                            $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `hotspot_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$_route_hotspot_ID' ";

                            //UPDATE
                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere)) :
                            endif;
                        else :
                            $_activity_order = 1;
                            $activity_start_time = date('H:i:s', strtotime($hotspot_start_time));
                            // $activity_start_time = date('H:i:s', strtotime($_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));

                            $activity_duration_DB = getACTIVITYDETAILS($_activity_id, 'activity_duration');
                            $activity_duration = getACTIVITYDETAILS($_activity_id, 'activity_duration');

                            $activity_duration = date('H', strtotime($activity_duration)) . 'hours ' . date('i', strtotime($activity_duration)) . 'mins';

                            //$activity_end_time = date('H:i:s', strtotime($activity_start_time . ' +' . $activity_duration));
                            $activity_end_time = date('H:i:s', strtotime($activity_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                            $end_time = $activity_end_time;

                            $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                                $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                                $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                                $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                            endwhile;

                            $select_itinerary_route_details_list_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $fetch_itinerary_route_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_list_query);
                            $itinerary_route_date = $fetch_itinerary_route_details_list_data['itinerary_route_date'];

                            $month = date('F', strtotime($itinerary_route_date));
                            $year = date('Y', strtotime($itinerary_route_date));
                            $date = 'day_' . date('n', strtotime($itinerary_route_date));
                            $itinerary_date = date('Y-m-d', strtotime($itinerary_route_date));

                            $selected_pricebook_query = sqlQUERY_LABEL("SELECT `year`, `month`, `price_type`, `$date` FROM `dvi_activity_pricebook` WHERE `activity_id`='$_activity_id' AND `status`='1' and `deleted` = '0' AND `year`='$year' AND `month`='$month'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                            $activity_pricebook_query = sqlNUMOFROW_LABEL($selected_pricebook_query);

                            if ($activity_pricebook_query > 0) :
                                while ($fetch_pricebook_list_data = sqlFETCHARRAY_LABEL($selected_pricebook_query)) :
                                    $price_type = $fetch_pricebook_list_data['price_type'];
                                    $price = $fetch_pricebook_list_data[$date];
                                    if ($price_type == '1') :
                                        $activity_total_adult_entry_cost = $price * $total_adult;
                                    elseif ($price_type == '2') :
                                        $activity_total_child_entry_cost = $price * $total_children;
                                    elseif ($price_type == '3') :
                                        $activity_total_infant_entry_cost = $price * $total_infants;
                                    endif;
                                endwhile;
                                $_activity_amout = $activity_total_adult_entry_cost + $activity_total_child_entry_cost + $activity_total_infant_entry_cost;
                            else :
                                $_activity_amout = 0;
                            endif;

                            $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`route_hotspot_ID`', '`activity_traveling_time`', '`activity_order`', '`activity_ID`', '`activity_entry_time_label`', '`activity_amout`', '`activity_start_time`', '`activity_end_time`', '`createdby`', '`status`');
                            $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_id", "$_route_hotspot_ID", "$activity_duration_DB", "$_activity_order", "$_activity_id", "$_dayOfWeekNumeric", "$_activity_amout", "$activity_start_time", "$activity_end_time", "$logged_user_id", "1");

                            //INSERT
                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_activity_details", $arrFields, $arrValues, '')) :
                                $arrFields_hotspot = array('`hotspot_end_time`');
                                $arrValues_hotspot = array("$activity_end_time");
                                $sqlWhere_hotspot = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `hotspot_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$_route_hotspot_ID' ";

                                //INSERT
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields_hotspot, $arrValues_hotspot, $sqlWhere_hotspot)) :
                                endif;
                                $response['i_result'] = true;
                                $response['result_success'] = true;
                                $response['itinerary_plan_ID'] = $_itinerary_plan_ID;
                                $response['itinerary_route_ID'] = $_itinerary_route_ID;
                                $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                            endif;

                        endif;
                    elseif ($reorder_hotspot == '1') :
                        $travel_distance = calculateTravelDistanceAndTime($start_latitude, $start_longitude, $end_latitude, $end_longitude, $GOOGLEMAP_API_KEY);
                        $_distance = $travel_distance['distance'];
                        $_time = $travel_distance['duration'];
                        $_end_time = $end_time;
                        $_hotspot_id = $hotspot_id;
                        $_itinerary_route_ID = $_itinerary_route_ID;
                        $_itinerary_plan_ID = $_itinerary_plan_ID;
                        $_dayOfWeekNumeric = $_dayOfWeekNumeric;

                        // Extract hours and minutes from the duration string
                        preg_match('/(\d+) hour/', $_time, $hours_match);
                        preg_match('/(\d+) min/', $_time, $minutes_match);

                        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                        // Format the time as H:i:s
                        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                        $hotspot_start_time = date('H:i:s', strtotime($_end_time . ' +' . $_time));
                        $hotspot_end_time = date('H:i:s', strtotime($hotspot_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                        $hotspot_activity_skipping = '0';

                        $select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `location_name`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID`='$_itinerary_route_ID' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_route_details_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query);
                        $route_day_end_time = $fetch_route_details_list_plan_data['route_end_time'];

                        $select_hotspot_places_query = sqlQUERY_LABEL("SELECT `hotspot_timing_status` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_hotspot_places_list_data = sqlFETCHARRAY_LABEL($select_hotspot_places_query);
                        $hotspot_timing_status = $fetch_hotspot_places_list_data['hotspot_timing_status'];

                        $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((hotspot_start_time <= '$hotspot_start_time' AND hotspot_end_time >= '$hotspot_end_time') OR `hotspot_open_all_time` = '1' )") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                        if ($total_itinerary_plan_rows_count > 0 || $hotspot_timing_status == '0') :
                            if (date('H:i:s', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) > $hotspot_end_time) :
                                $arrFields = array('`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                                $arrValues = array("$formatted_time", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");
                                $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `hotspot_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$route_hotspot_ID' ";

                                //INSERT
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere)) :
                                    $response['i_result'] = true;
                                    $response['result_success'] = true;
                                    $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                                $response['hotspot_day_time_over_status'] = true;
                                $response['hotspot_day_time_over'] = 'Tour ends at ' . date('g:i A', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' for that day; please update day end time for hotspot additions.';
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

                            $response['hotspot_not_available'] = 'Next available time: ' . $time . '.';
                        endif;
                    endif;
                endwhile;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'remove_itineary_route_activity_details') :

        $errors = [];
        $response = [];

        $_hotspot_id = $_POST['hotspot_id'];
        $_route_hotspot_ID  = $_POST['route_hotspot_ID'];
        $_activity_id  = $_POST['activity_id'];
        $_dayOfWeekNumeric = $_POST['dayOfWeekNumeric'];
        $_hotspot_id = $_POST['hotspot_id'];

        if (empty($_route_hotspot_ID)) :
            $errors['route_hotspot_ID_required'] = true;
        endif;
        if (empty($_activity_id)) :
            $errors['activity_id_required'] = true;
        endif;
        if ($_dayOfWeekNumeric == '') :
            $errors['dayOfWeekNumeric_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $select_route_activity_list_query = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `route_hotspot_ID`='$_route_hotspot_ID' AND `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
            $total_route_activity_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_activity_list_query);
            if ($total_route_activity_list_num_rows_count > 0) :
                while ($fetch_route_activity_list_data = sqlFETCHARRAY_LABEL($select_route_activity_list_query)) :
                    $counter++;
                    $_itinerary_plan_ID = $fetch_route_activity_list_data['itinerary_plan_ID'];
                    $_itinerary_route_ID = $fetch_route_activity_list_data['itinerary_route_ID'];
                endwhile;
            endif;


            $select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.`itinerary_plan_ID`, HOTSPOT_DETAILS.`itinerary_route_ID`, HOTSPOT_DETAILS.`hotspot_order`, HOTSPOT_DETAILS.`hotspot_ID`, HOTSPOT_DETAILS.`hotspot_traveling_time`, HOTSPOT_DETAILS.`hotspot_travelling_distance`, HOTSPOT_DETAILS.`hotspot_start_time`, HOTSPOT_DETAILS.`hotspot_end_time`, HOTSPOT_PLACES.`hotspot_latitude`, HOTSPOT_PLACES.`hotspot_longitude` FROM `dvi_itinerary_route_hotspot_details` AS HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` AS HOTSPOT_PLACES ON HOTSPOT_PLACES.`hotspot_ID` = HOTSPOT_DETAILS.`hotspot_ID` WHERE HOTSPOT_DETAILS.`itinerary_plan_ID`='$_itinerary_plan_ID' AND HOTSPOT_DETAILS.`itinerary_route_ID`='$_itinerary_route_ID' AND HOTSPOT_DETAILS.`status`='1' AND HOTSPOT_DETAILS.`deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
            $total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);

            if ($total_route_hotspot_list_num_rows_count > 0) :
                while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
                    $route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
                    $itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
                    $itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
                    $hotspot_order = $fetch_route_hotspot_list_data['hotspot_order'];
                    $hotspot_id = $fetch_route_hotspot_list_data['hotspot_ID'];
                    $hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
                    $hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
                    $hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
                    $hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];
                    $end_latitude = $fetch_route_hotspot_list_data['hotspot_latitude'];
                    $end_longitude = $fetch_route_hotspot_list_data['hotspot_longitude'];

                    if ($hotspot_id == $_hotspot_id) :
                        $reorder_hotspot = '1';
                        $start_latitude = $fetch_route_hotspot_list_data['hotspot_latitude'];
                        $start_longitude = $fetch_route_hotspot_list_data['hotspot_longitude'];

                        $select_activity_list_query = sqlQUERY_LABEL("SELECT `route_activity_ID`, `activity_order`, `activity_ID`, `activity_entry_time_label`, `activity_start_time`, `activity_end_time` FROM dvi_itinerary_route_activity_details WHERE `hotspot_ID`='$_hotspot_id' AND `itinerary_plan_ID`= '$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `route_hotspot_ID`='$_route_hotspot_ID' AND `status`='1' AND `deleted`='0'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                        $total_activity_count = sqlNUMOFROW_LABEL($select_activity_list_query);
                        if ($total_activity_count > 0) :
                            while ($fetch_activity_data = sqlFETCHARRAY_LABEL($select_activity_list_query)) :
                                $_route_activity_ID = $fetch_activity_data['route_activity_ID'];
                                $_activity_order = $fetch_activity_data['activity_order'];
                                $_activity_ID = $fetch_activity_data['activity_ID'];
                                $_activity_entry_time_label = $fetch_activity_data['activity_entry_time_label'];
                                $_activity_start_time = $fetch_activity_data['activity_start_time'];
                                $_activity_end_time = $fetch_activity_data['activity_end_time'];

                                if ($_activity_id == $_activity_ID) :
                                    $_reorder_activity = '1';
                                    $update_activity_order = $_activity_order;
                                    $previous_activity_start_time = $fetch_activity_data['activity_start_time'];

                                    $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `activity_entry_time_label`='$_dayOfWeekNumeric' AND `activity_ID`='$_activity_id' ";

                                    //DELETE
                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_details", '', '', $sqlWhere)) :
                                        if ($total_activity_count == '1') :
                                            $activity_end_time = $previous_activity_start_time;
                                        endif;

                                        $response['i_result'] = true;
                                        $response['result_success'] = true;
                                        $response['itinerary_plan_ID'] = $_itinerary_plan_ID;
                                        $response['itinerary_route_ID'] = $_itinerary_route_ID;
                                        $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                    else :
                                        $response['i_result'] = false;
                                        $response['result_success'] = false;
                                    endif;
                                elseif ($_reorder_activity == '1') :
                                    $previous_activity_order = $update_activity_order - 1;

                                    $activity_start_time = $previous_activity_start_time;

                                    $activity_duration_DB = getACTIVITYDETAILS($_activity_ID, 'activity_duration');
                                    $activity_duration = getACTIVITYDETAILS($_activity_ID, 'activity_duration');
                                    $activity_duration = date('H', strtotime($activity_duration)) . 'hours ' . date('i', strtotime($activity_duration)) . 'mins';

                                    $activity_end_time = date('H:i:s', strtotime($previous_activity_start_time . ' +' . $activity_duration));

                                    $select_itinerary_plan_details_list_query = sqlQUERY_LABEL("SELECT `total_adult`, `total_children`, `total_infants` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    while ($fetch_itinerary_plan_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_details_list_query)) :
                                        $total_adult = $fetch_itinerary_plan_details_list_data['total_adult'];
                                        $total_children = $fetch_itinerary_plan_details_list_data['total_children'];
                                        $total_infants = $fetch_itinerary_plan_details_list_data['total_infants'];
                                    endwhile;

                                    $select_itinerary_route_details_list_query = sqlQUERY_LABEL("SELECT `itinerary_route_date` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID` = '$_itinerary_route_ID' and `itinerary_plan_ID`='$_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $fetch_itinerary_route_details_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_list_query);
                                    $itinerary_route_date = $fetch_itinerary_route_details_list_data['itinerary_route_date'];

                                    $month = date('F', strtotime($itinerary_route_date));
                                    $year = date('Y', strtotime($itinerary_route_date));
                                    $date = 'day_' . date('d', strtotime($itinerary_route_date));
                                    $itinerary_date = date('Y-m-d', strtotime($itinerary_route_date));

                                    $selected_pricebook_query = sqlQUERY_LABEL("SELECT `year`, `month`, `price_type`, `$date` FROM `dvi_activity_pricebook` WHERE `activity_id`='$_activity_ID' AND `status`='1' and `deleted` = '0' AND `year`='$year' AND `month`='$month'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                    $activity_pricebook_query = sqlNUMOFROW_LABEL($selected_pricebook_query);

                                    if ($activity_pricebook_query > 0) :
                                        while ($fetch_pricebook_list_data = sqlFETCHARRAY_LABEL($selected_pricebook_query)) :
                                            $price_type = $fetch_pricebook_list_data['price_type'];
                                            $price = $fetch_pricebook_list_data[$date];
                                            if ($price_type == '1') :
                                                $activity_total_adult_entry_cost = $price * $total_adult;
                                            elseif ($price_type == '2') :
                                                $activity_total_child_entry_cost = $price * $total_children;
                                            elseif ($price_type == '3') :
                                                $activity_total_infant_entry_cost = $price * $total_infants;
                                            endif;
                                        endwhile;
                                        $_activity_amout = $activity_total_adult_entry_cost + $activity_total_child_entry_cost + $activity_total_infant_entry_cost;
                                    else :
                                        $_activity_amout = 0;
                                    endif;

                                    $arrFields_activity = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`route_hotspot_ID`', '`activity_traveling_time`', '`activity_order`', '`activity_ID`', '`activity_entry_time_label`', '`activity_amout`', '`activity_start_time`', '`activity_end_time`', '`createdby`', '`status`');
                                    $arrValues_activity = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_id", "$_route_hotspot_ID", "$activity_duration_DB", "$previous_activity_order", "$_activity_ID", "$_dayOfWeekNumeric", "$_activity_amout", "$activity_start_time", "$activity_end_time", "$logged_user_id", "1");

                                    $sqlWhere_activity = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `activity_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$_route_hotspot_ID' AND `activity_ID`='$_activity_ID' ";

                                    //UPDATE
                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_activity_details", $arrFields_activity, $arrValues_activity, $sqlWhere_activity)) :
                                        $response['i_result'] = true;
                                        $response['result_success'] = true;
                                        $response['itinerary_plan_ID'] = $_itinerary_plan_ID;
                                        $response['itinerary_route_ID'] = $_itinerary_route_ID;
                                        $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                    else :
                                        $response['i_result'] = false;
                                        $response['result_success'] = false;
                                    endif;
                                endif;
                                $update_activity_order++;
                            endwhile;
                            $end_time = $activity_end_time;
                            $arrFields = array('`hotspot_end_time`');
                            $arrValues = array("$activity_end_time");
                            $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `hotspot_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$_route_hotspot_ID' ";

                            //UPDATE
                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere)) :
                            endif;
                        endif;
                    elseif ($reorder_hotspot == '1') :
                        $travel_distance = calculateTravelDistanceAndTime($start_latitude, $start_longitude, $end_latitude, $end_longitude, $GOOGLEMAP_API_KEY);
                        $_distance = $travel_distance['distance'];
                        $_time = $travel_distance['duration'];
                        $_end_time = $end_time;
                        $_hotspot_id = $hotspot_id;
                        $_itinerary_route_ID = $_itinerary_route_ID;
                        $_itinerary_plan_ID = $_itinerary_plan_ID;
                        $_dayOfWeekNumeric = $_dayOfWeekNumeric;

                        // Extract hours and minutes from the duration string
                        preg_match('/(\d+) hour/', $_time, $hours_match);
                        preg_match('/(\d+) min/', $_time, $minutes_match);

                        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                        // Format the time as H:i:s
                        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                        //INSERT
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_details", '', '', $sqlWhere)) :

                            $response['i_result'] = true;
                            $response['result_success'] = true;
                            $response['itinerary_plan_ID'] = $_itinerary_plan_ID;
                            $response['itinerary_route_ID'] = $_itinerary_route_ID;
                            $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                        else :
                            $response['i_result'] = false;
                            $response['result_success'] = false;
                        endif;

                        $hotspot_start_time = date('H:i:s', strtotime($_end_time . ' +' . $_time));
                        $hotspot_end_time = date('H:i:s', strtotime($hotspot_start_time . ' +' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                        $hotspot_activity_skipping = '0';

                        $select_itinerary_route_details_query = sqlQUERY_LABEL("SELECT `location_name`, `location_latitude`, `location_longtitude`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_route_ID`='$_itinerary_route_ID' and `itinerary_plan_ID` = '$_itinerary_plan_ID'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_route_details_list_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details_query);
                        $route_day_end_time = $fetch_route_details_list_plan_data['route_end_time'];

                        $select_hotspot_places_query = sqlQUERY_LABEL("SELECT `hotspot_timing_status` FROM `dvi_hotspot_place` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $fetch_hotspot_places_list_data = sqlFETCHARRAY_LABEL($select_hotspot_places_query);
                        $hotspot_timing_status = $fetch_hotspot_places_list_data['hotspot_timing_status'];

                        $select_itinerary_plan_query = sqlQUERY_LABEL("SELECT `hotspot_ID` FROM `dvi_hotspot_timing` WHERE `deleted` = '0' and `hotspot_ID`='$_hotspot_id' and `hotspot_timing_day` = '$_dayOfWeekNumeric' and ((hotspot_start_time <= '$hotspot_start_time' AND hotspot_end_time >= '$hotspot_end_time') OR `hotspot_open_all_time` = '1' )") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                        $total_itinerary_plan_rows_count = sqlNUMOFROW_LABEL($select_itinerary_plan_query);
                        if ($total_itinerary_plan_rows_count > 0 || $hotspot_timing_status == '0') :
                            if (date('H:i:s', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) > $hotspot_end_time) :
                                $arrFields = array('`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`hotspot_activity_skipping`', '`createdby`', '`status`');
                                $arrValues = array("$formatted_time", "$_distance", "$hotspot_start_time", "$hotspot_end_time", "$hotspot_activity_skipping", "$logged_user_id", "1");
                                $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `hotspot_entry_time_label`='$_dayOfWeekNumeric' AND `route_hotspot_ID`='$route_hotspot_ID' ";

                                //INSERT
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlWhere)) :
                                    $response['i_result'] = true;
                                    $response['result_success'] = true;
                                    $response['overall_trip_cost'] = getOVERLALLTRIPCOST($_itinerary_plan_ID);
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                                $response['hotspot_day_time_over_status'] = true;
                                $response['hotspot_day_time_over'] = 'Tour ends at ' . date('g:i A', strtotime($route_day_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' for that day; please update day end time for hotspot additions.';
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

                            $response['hotspot_not_available'] = 'Next available time: ' . $time . '.';
                        endif;
                    endif;

                endwhile;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'skip_activty') :

        $errors = [];
        $response = [];

        $_itinerary_plan_ID  = $_POST['itinerary_plan_ID'];
        $_itinerary_route_ID = $_POST['itinerary_route_ID'];
        $_route_hotspot_ID  = $_POST['route_hotspot_ID'];
        $_hotspot_id  = $_POST['hotspot_id'];

        if (empty($_itinerary_plan_ID)) :
            $errors['itinerary_plan_ID_required'] = true;
        endif;
        if (empty($_itinerary_route_ID)) :
            $errors['itinerary_route_ID_required'] = true;
        endif;
        if (empty($_route_hotspot_ID)) :
            $errors['route_hotspot_ID_required'] = true;
        endif;
        if (empty($_hotspot_id)) :
            $errors['hotspot_id_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;
            $arrFields = array('`hotspot_activity_skipping`');
            $arrValues = array("1");
            $sqlwhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `route_hotspot_ID`='$_route_hotspot_ID' ";

            //INSERT
            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, $sqlwhere)) :
                $response['u_result'] = true;
                $response['result_success'] = true;
                $response['success_message'] = 'Activity is skipped for hotspot - <b>' . getHOTSPOTDETAILS($_hotspot_id, 'label') . '</b>';
            else :
                $response['u_result'] = false;
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
