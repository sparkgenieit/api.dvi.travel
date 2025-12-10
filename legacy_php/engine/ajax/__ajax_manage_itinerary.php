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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    if ($_GET['type'] == 'itinerary_basic_info') :

        $errors = [];
        $response = [];

        $_arrival_location = trim($_POST['arrival_location']);
        $_departure_location = trim($_POST['departure_location']);
        $_trip_start_date_and_time = trim($_POST['trip_start_date_and_time']);
        $_trip_end_date_and_time = $_POST['trip_end_date_and_time'];
        $_no_of_days = $_POST['no_of_days'];
        $_no_of_nights = trim($_POST['no_of_nights']);
        $_number_of_routes = trim($_POST['number_of_routes']);
        $_total_adult = trim($_POST['total_adult']);
        $_total_children = trim($_POST['total_children']);
        $_total_infants = trim($_POST['total_infants']);
        $_expecting_budget = $_POST['expecting_budget'];
        $_itinerary_prefrence = $_POST['itinerary_prefrence'];
        $_number_of_rooms = $_POST['number_of_rooms'];
        $_number_of_child_no_bed = $_POST['number_of_child_no_bed'];
        $_number_of_extra_beds = $_POST['number_of_extra_beds'];
        $_vehicle_type = $_POST['vehicle_type'];
        $_vehicle_count = $_POST['vehicle_count'];
        $hidden_itinerary_plan_ID = $_POST['hidden_itinerary_plan_ID'];

        if (empty($_arrival_location)) :
            $errors['arrival_location_required'] = true;
        endif;
        if (empty($_departure_location)) :
            $errors['departure_location_required'] = true;
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
        if (empty($_vehicle_type)) :
            $errors['vehicle_type_required'] = true;
        endif;
        if (empty($_vehicle_count)) :
            $errors['vehicle_count_required'] = true;
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

            $_vehicle_type = implode(',', $_vehicle_type);
            $_vehicle_count = implode(',', $_vehicle_count);

            $arrFields = array('`arrival_location`', '`departure_location`', '`trip_start_date_and_time`', '`trip_end_date_and_time`', '`expecting_budget`', '`no_of_routes`', '`no_of_days`', '`no_of_nights`', '`total_adult`', '`total_children`', '`total_infants`', '`itinerary_preference`', '`preferred_room_count`', '`total_extra_bed`', '`total_child_no_bed`', '`preferred_vehicle_type_id`', '`preferred_vehicle_count`', '`createdby`', '`status`');

            $arrValues = array("$_arrival_location", "$_departure_location", "$_trip_start_date_and_time", "$_trip_end_date_and_time", "$_expecting_budget", "$_number_of_routes", "$_no_of_days", "$_no_of_nights", "$_total_adult", "$_total_children", "$_total_infants", "$_itinerary_prefrence", "$_number_of_rooms", "$_number_of_extra_beds", "$_number_of_child_no_bed", "$_vehicle_type", "$_vehicle_count", "$logged_user_id", "1");

            if ($hidden_itinerary_plan_ID != '' && $hidden_itinerary_plan_ID != 0) :
                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
                //UPDATE HOTEL DETAILS
                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_details", $arrFields, $arrValues, $sqlWhere)) :
                    $response['u_result'] = true;
                    $response['redirect_URL'] = 'itinerary.php?route=add&formtype=itinerary_list&id=' . $hidden_itinerary_plan_ID;
                    $response['result_success'] = true;
                else :
                    $response['u_result'] = false;
                    $response['result_success'] = false;
                endif;
            else :
                //INSERT HOTEL DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_details", $arrFields, $arrValues, '')) :
                    $itinerary_id = sqlINSERTID_LABEL();
                    $response['i_result'] = true;
                    $response['redirect_URL'] = 'itinerary.php?route=add&formtype=itinerary_list&id=' . $itinerary_id;
                    $response['result_success'] = true;
                else :
                    $response['i_result'] = false;
                    $response['result_success'] = false;
                endif;
            endif;

        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'itinerary_route_final') :
        $errors = [];
        $response = [];

        $_itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $_itinerary_route_ID = $_POST['itinerary_route_ID'];
        $_routeDay = $_POST['routeDay'];
        $_routeNight = $_POST['routeNight'];

        if (empty($_itinerary_route_ID)) :
            $errors['itinerary_route_ID_required'] = true;
        endif;
        if (empty($_routeDay)) :
            $errors['routeDay_required'] = true;
        endif;
        if (empty($_routeNight)) :
            $errors['routeNight_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            foreach ($_itinerary_route_ID as $key_pricebook => $val_pricebook) :
                $itinerary_route_ID = trim($_POST['itinerary_route_ID'][$key_pricebook]);
                $routeDay = trim($_POST['routeDay'][$key_pricebook]);
                $routeNight = trim($_POST['routeNight'][$key_pricebook]);

                $arrFields = array('`itinerary_route_days`', '`itinerary_route_nights`', '`createdby`', '`status`');
                $arrValues = array("$routeDay", "$routeNight", "$logged_user_id", "1");

                if ($itinerary_route_ID != '' && $itinerary_route_ID != 0) :
                    $sqlWhere = " `itinerary_route_ID` = '$itinerary_route_ID' AND `itinerary_plan_ID` = '$_itinerary_plan_ID' ";
                    //UPDATE HOTEL DETAILS
                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $arrFields, $arrValues, $sqlWhere)) :
                        $response['u_result'] = true;
                        $response['redirect_URL'] = 'itinerary.php?route=add&formtype=itinerary_daywise&id=' . $_itinerary_plan_ID;
                        $response['result_success'] = true;
                    else :
                        $response['u_result'] = false;
                        $response['result_success'] = false;
                    endif;
                endif;
            endforeach;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'itinerary_route_hotspot_details') :
        $errors = [];
        $response = [];

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
        if (empty($_dayOfWeekNumeric)) :
            $errors['dayOfWeekNumeric_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`hotspot_ID`', '`hotspot_entry_time_label`', '`hotspot_amout`', '`createdby`', '`status`');
            $arrValues = array("$_itinerary_plan_ID", "$_itinerary_route_ID", "$_hotspot_id", "$_dayOfWeekNumeric", "", "$logged_user_id", "1");

            //INSERT
            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $arrFields, $arrValues, '')) :
                $response['i_result'] = true;
                $response['result_success'] = true;
            else :
                $response['i_result'] = false;
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    elseif ($_GET['type'] == 'remove_itinerary_route_hotspot_details') :
        $errors = [];
        $response = [];

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
        if (empty($_dayOfWeekNumeric)) :
            $errors['dayOfWeekNumeric_required'] = true;
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call		
            $response['success'] = true;

            $sqlWhere = " `itinerary_plan_ID`='$_itinerary_plan_ID' AND `itinerary_route_ID`='$_itinerary_route_ID' AND `hotspot_ID`='$_hotspot_id' AND `hotspot_entry_time_label`='$_dayOfWeekNumeric' ";

            //INSERT
            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $sqlWhere)) :
                $response['i_result'] = true;
                $response['result_success'] = true;
            else :
                $response['i_result'] = false;
                $response['result_success'] = false;
            endif;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
