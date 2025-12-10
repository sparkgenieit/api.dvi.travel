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

if ($_GET['type'] == 'hotel_details') :

    $response = [];
    $errors = [];

    $hidden_hotel_ID = $_POST['hidden_hotel_ID'];
    $hotel_margin = $_POST['hotel_margin'];
    $hotel_margin_gst_type = $_POST['hotel_margin_gst_type'];
    $hotel_margin_gst_percentage = $_POST['hotel_margin_gst_percentage'];
    //$hotel_breafast_cost = $_POST['hotel_breafast_cost'];
    //$hotel_lunch_cost = $_POST['hotel_lunch_cost'];
    //$hotel_dinner_cost = $_POST['hotel_dinner_cost'];

    if (!empty($errors)) :
        //error call
        $response['success'] = false;
        $response['errors'] = $errors;
    else :
        //success call        
        $response['success'] = true;

        $arrFields = array('`hotel_margin`', '`hotel_margin_gst_type`', '`hotel_margin_gst_percentage`');

        $arrValues = array("$hotel_margin", "$hotel_margin_gst_type", "$hotel_margin_gst_percentage");

        $sqlwhere = " `hotel_id` = '$hidden_hotel_ID'";
        //UPDATE HOTEL DETAILS
        if (sqlACTIONS("UPDATE", "dvi_hotel", $arrFields, $arrValues, $sqlwhere)) :
            $response['result'] = true;
        else :
            $response['result'] = false;
        endif;
    endif;

    echo json_encode($response);

elseif ($_GET['type'] == 'hotel_meal_details') :

    $response = [];
    $errors = [];

    $hotel_id = $_POST['hotel_id'] ?? null;
    $meal_start_date = $_POST['meal_start_date'];
    $meal_end_date = $_POST['meal_end_date'];
    $hotel_breafast_cost = $_POST['hotel_breafast_cost'];
    $hotel_lunch_cost = $_POST['hotel_lunch_cost'];
    $hotel_dinner_cost = $_POST['hotel_dinner_cost'];
    // $response['processed'] = [];

    if ($hotel_id === null) :
        $errors[] = "Invalid hotel.";
    else :

        if ($hotel_breafast_cost !== '') :
            // Get all months between start date and end 
            $meal_type = 1;
            $meal_cost = $hotel_breafast_cost;
            $months = getMonthsBetweenDates($meal_start_date, $meal_end_date);
            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                // Determine start and end days for the current month
                $currentStartDay = ($month == date('F', strtotime($meal_start_date))) ? (int)convertDateFormat($meal_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($meal_end_date))) ? (int)convertDateFormat($meal_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists for this meal type
                $sqlCheck = "SELECT `hotel_meal_price_book_id` FROM `dvi_hotel_meal_price_book` WHERE `hotel_id` = '$hotel_id' AND  `meal_type` = '$meal_type' AND `year` = '$year' AND `month` = '$month'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    if ($meal_cost !== null) :
                        $updateFields = [];
                        for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                            $updateFields[] = "`day_$day` = " . (is_numeric($meal_cost) ? $meal_cost : '0');
                        endfor;
                        $updatedon = date('Y-m-d H:i:s');
                        $updateFields[] = "`updatedon` = '$updatedon'";
                        $sqlUpdate = "UPDATE `dvi_hotel_meal_price_book` SET " . implode(', ', $updateFields) . " WHERE `hotel_id` = '$hotel_id' AND `meal_type` = '$meal_type' AND `year` = '$year' AND `month` = '$month'";

                        if (sqlQUERY_LABEL($sqlUpdate)) :
                            $response['success'] = true;
                        else :
                            $response['error'] = "Failed to update Meal charge in $month-$year.";
                        endif;
                    endif;
                else :
                    // Insert new record
                    if ($meal_type !== null) :
                        $arrFields = array('`hotel_id`',  '`meal_type`', '`year`', '`month`', '`createdby`', '`status`');
                        for ($day = 1; $day <= 31; $day++) :
                            $arrFields[] = "`day_$day`";
                        endfor;

                        $arrValues = array(
                            "$hotel_id",
                            "$meal_type",
                            "$year",
                            "$month",
                            "$logged_user_id",
                            '1'
                        );
                        for ($day = 1; $day <= 31; $day++) :
                            $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? (is_numeric($meal_cost) ? $meal_cost : '0') : '0';
                        endfor;

                        if (sqlACTIONS("INSERT", "dvi_hotel_meal_price_book", $arrFields, $arrValues, '')) :
                            $response['success'] = true;
                        else :
                            $response['error'] = "Failed to insert Meal charge  in $month-$year.";
                        endif;
                    endif;
                endif;

            endforeach;

        endif;

        if ($hotel_lunch_cost !== '') :
            // Get all months between start date and end 
            $meal_type = 2;
            $meal_cost = $hotel_lunch_cost;
            $months = getMonthsBetweenDates($meal_start_date, $meal_end_date);
            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                // Determine start and end days for the current month
                $currentStartDay = ($month == date('F', strtotime($meal_start_date))) ? (int)convertDateFormat($meal_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($meal_end_date))) ? (int)convertDateFormat($meal_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists for this meal type
                $sqlCheck = "SELECT `hotel_meal_price_book_id` FROM `dvi_hotel_meal_price_book` WHERE `hotel_id` = '$hotel_id' AND  `meal_type` = '$meal_type' AND `year` = '$year' AND `month` = '$month'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    if ($meal_cost !== null) :
                        $updateFields = [];
                        for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                            $updateFields[] = "`day_$day` = " . (is_numeric($meal_cost) ? $meal_cost : '0');
                        endfor;
                        $updatedon = date('Y-m-d H:i:s');
                        $updateFields[] = "`updatedon` = '$updatedon'";
                        $sqlUpdate = "UPDATE `dvi_hotel_meal_price_book` SET " . implode(', ', $updateFields) . " WHERE `hotel_id` = '$hotel_id' AND `meal_type` = '$meal_type' AND `year` = '$year' AND `month` = '$month'";

                        if (sqlQUERY_LABEL($sqlUpdate)) :
                            $response['success'] = true;
                        else :
                            $response['error'] = "Failed to update Meal charge in $month-$year.";
                        endif;
                    endif;
                else :
                    // Insert new record
                    if ($meal_type !== null) :
                        $arrFields = array('`hotel_id`',  '`meal_type`', '`year`', '`month`', '`createdby`', '`status`');
                        for ($day = 1; $day <= 31; $day++) :
                            $arrFields[] = "`day_$day`";
                        endfor;

                        $arrValues = array(
                            "$hotel_id",
                            "$meal_type",
                            "$year",
                            "$month",
                            "$logged_user_id",
                            '1'
                        );
                        for ($day = 1; $day <= 31; $day++) :
                            $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? (is_numeric($meal_cost) ? $meal_cost : '0') : '0';
                        endfor;

                        if (sqlACTIONS("INSERT", "dvi_hotel_meal_price_book", $arrFields, $arrValues, '')) :
                            $response['success'] = true;
                        else :
                            $response['error'] = "Failed to insert Meal charge  in $month-$year.";
                        endif;
                    endif;
                endif;

            endforeach;


        endif;

        if ($hotel_dinner_cost !== '') :
            // Get all months between start date and end 
            $meal_type = 3;
            $meal_cost = $hotel_dinner_cost;
            $months = getMonthsBetweenDates($meal_start_date, $meal_end_date);
            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                // Determine start and end days for the current month
                $currentStartDay = ($month == date('F', strtotime($meal_start_date))) ? (int)convertDateFormat($meal_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($meal_end_date))) ? (int)convertDateFormat($meal_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists for this meal type
                $sqlCheck = "SELECT `hotel_meal_price_book_id` FROM `dvi_hotel_meal_price_book` WHERE `hotel_id` = '$hotel_id' AND  `meal_type` = '$meal_type' AND `year` = '$year' AND `month` = '$month'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    if ($meal_cost !== null) :
                        $updateFields = [];
                        for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                            $updateFields[] = "`day_$day` = " . (is_numeric($meal_cost) ? $meal_cost : '0');
                        endfor;
                        $updatedon = date('Y-m-d H:i:s');
                        $updateFields[] = "`updatedon` = '$updatedon'";
                        $sqlUpdate = "UPDATE `dvi_hotel_meal_price_book` SET " . implode(', ', $updateFields) . " WHERE `hotel_id` = '$hotel_id' AND `meal_type` = '$meal_type' AND `year` = '$year' AND `month` = '$month'";

                        if (sqlQUERY_LABEL($sqlUpdate)) :
                            $response['success'] = "Updated Meal charge in $month-$year.";
                        else :
                            $response['error'] = "Failed to update Meal charge in $month-$year.";
                        endif;
                    endif;
                else :
                    // Insert new record
                    if ($meal_type !== null) :
                        $arrFields = array('`hotel_id`',  '`meal_type`', '`year`', '`month`', '`createdby`', '`status`');
                        for ($day = 1; $day <= 31; $day++) :
                            $arrFields[] = "`day_$day`";
                        endfor;

                        $arrValues = array(
                            "$hotel_id",
                            "$meal_type",
                            "$year",
                            "$month",
                            "$logged_user_id",
                            '1'
                        );
                        for ($day = 1; $day <= 31; $day++) :
                            $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? (is_numeric($meal_cost) ? $meal_cost : '0') : '0';
                        endfor;

                        if (sqlACTIONS("INSERT", "dvi_hotel_meal_price_book", $arrFields, $arrValues, '')) :
                            $response['success'] = "Inserted Meal charge in $month-$year.";
                        else :
                            $response['error'] = "Failed to insert Meal charge  in $month-$year.";
                        endif;
                    endif;
                endif;

            endforeach;
        endif;

    endif;

    // Send response
    $response['errors'] = $errors;
    echo json_encode($response);

elseif ($_GET['type'] == 'hotel_amenities_details') :

    $response = [];
    $errors = [];

    $hotel_id = $_POST['hotel_id'] ?? null;
    $amenities_start_date = $_POST['amenities_start_date'] ?? [];
    $amenities_end_date = $_POST['amenities_end_date'] ?? [];
    $hidden_hotel_amenities_ids = $_POST['hotel_amenities_id'] ?? [];
    $hours_charges = $_POST['hours_charge'] ?? [];
    $day_charges = $_POST['day_charge'] ?? [];
    $hidden_amenities_titles = $_POST['hidden_amenities_title'] ?? [];
    $response['processed'] = [];

    if ($hotel_id === null || empty($hidden_hotel_amenities_ids)) :
        $errors[] = "Invalid hotel or no amenities provided.";
    else :
        // Iterate through each amenity to update the database
        foreach ($hidden_hotel_amenities_ids as $key => $hotel_amenities_id) :
            $hours_charge = isset($hours_charges[$key]) && $hours_charges[$key] !== '' ? $hours_charges[$key] : null;
            $day_charge = isset($day_charges[$key]) && $day_charges[$key] !== '' ? $day_charges[$key] : null;
            $hidden_amenities_title = $hidden_amenities_titles[$key];

            if ($hours_charge !== null || $day_charge !== null) :
                // Get all months between start date and end date
                $months = getMonthsBetweenDates($amenities_start_date[$key], $amenities_end_date[$key]);

                foreach ($months as $monthYear) :
                    list($month, $year) = explode('-', $monthYear);

                    // Determine start and end days for the current month
                    $currentStartDay = ($month == date('F', strtotime($amenities_start_date[$key]))) ? (int)convertDateFormat($amenities_start_date[$key]) : 1;
                    $currentEndDay = ($month == date('F', strtotime($amenities_end_date[$key]))) ? (int)convertDateFormat($amenities_end_date[$key]) : (int)date('t', strtotime("$year-$month-01"));

                    // Check if the record exists for hour charge
                    $sqlCheck_for_hour_charge = "SELECT `hotel_amenities_price_book_id` FROM `dvi_hotel_amenities_price_book` WHERE `hotel_id` = '$hotel_id' AND `hotel_amenities_id` = '$hotel_amenities_id' AND `pricetype` = '2' AND `year` = '$year' AND `month` = '$month'";
                    $resultCheck_for_hour_charge = sqlQUERY_LABEL($sqlCheck_for_hour_charge);

                    if (sqlNUMOFROW_LABEL($resultCheck_for_hour_charge) > 0) :
                        // Update existing record
                        if ($hours_charge !== null) :
                            $updateFields = [];
                            for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                                $updateFields[] = "`day_$day` = " . (is_numeric($hours_charge) ? $hours_charge : '0');
                            endfor;
                            $updatedon = date('Y-m-d H:i:s');
                            $updateFields[] = "`updatedon` = '$updatedon'";
                            $sqlUpdate = "UPDATE `dvi_hotel_amenities_price_book` SET " . implode(', ', $updateFields) . " WHERE `hotel_id` = '$hotel_id' AND `hotel_amenities_id` = '$hotel_amenities_id' AND `pricetype` = '2' AND `year` = '$year' AND `month` = '$month'";

                            if (sqlQUERY_LABEL($sqlUpdate)) :
                                $response['success'][] = "Updated hours charge for $hidden_amenities_title in $month-$year.";
                            else :
                                $response['error'][] = "Failed to update hours charge for $hidden_amenities_title in $month-$year.";
                            endif;
                        endif;
                    else :
                        // Insert new record
                        if ($hours_charge !== null) :
                            $arrFields = array('`hotel_id`', '`hotel_amenities_id`', '`pricetype`', '`year`', '`month`', '`createdby`', '`status`');
                            for ($day = 1; $day <= 31; $day++) :
                                $arrFields[] = "`day_$day`";
                            endfor;

                            $arrValues = array(
                                "$hotel_id",
                                "$hotel_amenities_id",
                                '2',
                                "$year",
                                "$month",
                                "$logged_user_id",
                                '1'
                            );
                            for ($day = 1; $day <= 31; $day++) :
                                $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? (is_numeric($hours_charge) ? $hours_charge : '0') : '0';
                            endfor;

                            if (sqlACTIONS("INSERT", "dvi_hotel_amenities_price_book", $arrFields, $arrValues, '')) :
                                $response['success'][] = "Inserted hours charge for $hidden_amenities_title in $month-$year.";
                            else :
                                $response['error'][] = "Failed to insert hours charge for $hidden_amenities_title in $month-$year.";
                            endif;
                        endif;
                    endif;

                    // Check if the record exists for day charge
                    $sqlCheck_for_day_charge = "SELECT `hotel_amenities_price_book_id` FROM `dvi_hotel_amenities_price_book` WHERE `hotel_id` = '$hotel_id' AND `hotel_amenities_id` = '$hotel_amenities_id' AND `pricetype` = '1' AND `year` = '$year' AND `month` = '$month'";
                    $resultCheck_for_day_charge = sqlQUERY_LABEL($sqlCheck_for_day_charge);

                    if (sqlNUMOFROW_LABEL($resultCheck_for_day_charge) > 0) :
                        if ($day_charge !== null) :
                            $updateFields = [];
                            for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                                $updateFields[] = "`day_$day` = " . (is_numeric($day_charge) ? $day_charge : '0');
                            endfor;
                            $updatedon = date('Y-m-d H:i:s');
                            $updateFields[] = "`updatedon` = '$updatedon'";

                            $sqlUpdate = "UPDATE `dvi_hotel_amenities_price_book` SET " . implode(', ', $updateFields) . " WHERE `hotel_id` = '$hotel_id' AND `hotel_amenities_id` = '$hotel_amenities_id' AND `pricetype` = '1' AND `year` = '$year' AND `month` = '$month'";

                            if (sqlQUERY_LABEL($sqlUpdate)) :
                                $response['success'][] = "Updated day charge for $hidden_amenities_title in $month-$year.";
                            else :
                                $response['error'][] = "Failed to update day charge for $hidden_amenities_title in $month-$year.";
                            endif;
                        endif;
                    else :
                        // Insert new record
                        if ($day_charge !== null) :
                            $arrFields = array('`hotel_id`', '`hotel_amenities_id`', '`pricetype`', '`year`', '`month`', '`createdby`', '`status`');
                            for ($day = 1; $day <= 31; $day++) :
                                $arrFields[] = "`day_$day`";
                            endfor;

                            $arrValues = array(
                                "$hotel_id",
                                "$hotel_amenities_id",
                                '1',
                                "$year",
                                "$month",
                                "$logged_user_id",
                                '1'
                            );
                            for ($day = 1; $day <= 31; $day++) :
                                $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? (is_numeric($day_charge) ? $day_charge : '0') : '0';
                            endfor;

                            if (sqlACTIONS("INSERT", "dvi_hotel_amenities_price_book", $arrFields, $arrValues, '')) :
                                $response['success'][] = "Inserted day charge for $hidden_amenities_title in $month-$year.";
                            else :
                                $response['error'][] = "Failed to insert day charge for $hidden_amenities_title in $month-$year.";
                            endif;
                        endif;
                    endif;
                endforeach;
            else :
                $response['error'][] = "No charges provided for $hidden_amenities_title.";
            endif;
        endforeach;
    endif;

    // Send response
    $response['errors'] = $errors;
    echo json_encode($response);

elseif ($_GET['type'] == 'hotel_room_details') :

    $response = [];
    $errors = [];

    $hotel_id = $_POST['hotel_id'] ?? null;
    $room_type_ids = $_POST['room_type_id'] ?? [];
    $room_start_date = $_POST['room_start_date'] ?? null;
    $room_end_date = $_POST['room_end_date'] ?? null;
    $room_ids = $_POST['room_id'] ?? [];
    $room_rental_prices = $_POST['room_rental_price'] ?? [];
    $gst_types = $_POST['gst_type'] ?? [];
    $gst_percentages = $_POST['gst_percentage'] ?? [];
    $extra_bed_charges = $_POST['extra_bed_charge'] ?? [];
    $child_with_bed_charges = $_POST['child_with_bed_charge'] ?? [];
    $child_without_bed_charges = $_POST['child_without_bed_charge'] ?? [];

    // Ensure $room_ids is an array
    if (!is_array($room_ids)) {
        $room_ids = [];
    }

    // Filter entries with valid room_rental_price
    $filtered_entries = [];
    for ($i = 0; $i < count($room_ids); $i++) :
        if (($room_rental_prices[$i] != "") || ($extra_bed_charges[$i] != "") || ($child_with_bed_charges[$i] != "") || ($child_without_bed_charges[$i] != "")) :
            $filtered_entries[] = [
                'room_id' => $room_ids[$i],
                'room_type_id' => $room_type_ids[$i],
                'room_rental_price' => $room_rental_prices[$i],
                'gst_type' => $gst_types[$i],
                'gst_percentage' => $gst_percentages[$i],
                'extra_bed_charge' => $extra_bed_charges[$i],
                'child_with_bed_charge' => $child_with_bed_charges[$i],
                'child_without_bed_charge' => $child_without_bed_charges[$i]
            ];
        endif;
    endfor;

    // Iterate through each filtered entry to update the database
    foreach ($filtered_entries as $entry) :
        $room_id = $entry['room_id'];
        $room_type_id = $entry['room_type_id'];
        $room_rental_price = $entry['room_rental_price'];
        $extra_bed_charge = $entry['extra_bed_charge'];
        $child_with_bed_charge = $entry['child_with_bed_charge'];
        $child_without_bed_charge = $entry['child_without_bed_charge'];

        $gst_type = $entry['gst_type'] ?? null;
        $gst_percentage = $entry['gst_percentage'] ?? null;

        // Update GST type, GST percentage in dvi_hotel_rooms
        if ($gst_type !== null || $gst_percentage !== null) :
            $arrFields = [];
            $arrValues = [];
            if ($gst_type !== null) :
                $arrFields[] = '`gst_type`';
                $arrValues[] = "$gst_type";
            endif;
            if ($gst_percentage !== null) :
                $arrFields[] = '`gst_percentage`';
                $arrValues[] = "$gst_percentage";
            endif;
            /*  if ($extra_bed_charge !== null) :
                $arrFields[] = '`extra_bed_charge`';
                $arrValues[] = "$extra_bed_charge";
            endif;
            if ($child_with_bed_charge !== null) :
                $arrFields[] = '`child_with_bed_charge`';
                $arrValues[] = "$child_with_bed_charge";
            endif;
            if ($child_without_bed_charge !== null) :
                $arrFields[] = '`child_without_bed_charge`';
                $arrValues[] = "$child_without_bed_charge";
            endif;*/
            $sqlwhere = " `room_ID` = '$room_id' AND `hotel_id` = '$hotel_id'";

            if (sqlACTIONS("UPDATE", "dvi_hotel_rooms", $arrFields, $arrValues, $sqlwhere)) :
                $response['success'] = true;
            else :
                $response['success'] = false;
            endif;
        endif;

        //UPDATE ROOM RATE
        if ($room_rental_price != "" && $room_start_date && $room_end_date) :
            $months = getMonthsBetweenDates($room_start_date, $room_end_date);
            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                $currentStartDay = ($month == date('F', strtotime($room_start_date))) ? (int)convertDateFormat($room_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($room_end_date))) ? (int)convertDateFormat($room_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists
                $sqlCheck = "SELECT `hotel_price_book_id` FROM `dvi_hotel_room_price_book` WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='0'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    $updateFields = [];
                    for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                        $updateFields[] = "`day_$day` = \"" . (is_numeric($room_rental_price) ? $room_rental_price : '0') . "\"";
                    endfor;

                    $updatedon = date('Y-m-d H:i:s');
                    $updateFields[] = "`updatedon` = '$updatedon'";
                    $sqlUpdate = "UPDATE `dvi_hotel_room_price_book` SET " . implode(', ', $updateFields) . " WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month'  AND `price_type`='0'";

                    if (sqlQUERY_LABEL($sqlUpdate)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                else :
                    // Insert new record
                    $arrFields = ['`hotel_id`', '`room_type_id`', '`room_id`', '`year`', '`month`', '`createdby`', '`status`'];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrFields[] = "`day_$day`";
                    endfor;

                    $arrValues = ["'$hotel_id'", "'$room_type_id'", "'$room_id'", "'$year'", "'$month'", "'$logged_user_id'", "'1'"];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrValues[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? '"' . (is_numeric($room_rental_price) ? $room_rental_price : '0') . '"' : '"0"';
                    endfor;

                    $sqlInsert = "INSERT INTO `dvi_hotel_room_price_book` (" . implode(', ', $arrFields) . ") VALUES (" . implode(', ', $arrValues) . ")";

                    if (sqlQUERY_LABEL($sqlInsert)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                endif;
            endforeach;
        endif;

        //UPDATE EXTRA BED RATE
        if ($extra_bed_charge != "" && $room_start_date && $room_end_date) :
            $months = getMonthsBetweenDates($room_start_date, $room_end_date);

            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                $currentStartDay = ($month == date('F', strtotime($room_start_date))) ? (int)convertDateFormat($room_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($room_end_date))) ? (int)convertDateFormat($room_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists
                $sqlCheck = "SELECT `hotel_price_book_id` FROM `dvi_hotel_room_price_book` WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='1'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    $updateFields1 = [];
                    for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                        $updateFields1[] = "`day_$day` = \"" . (is_numeric($extra_bed_charge) ? $extra_bed_charge : '0') . "\"";
                    endfor;

                    $updatedon = date('Y-m-d H:i:s');
                    $updateFields1[] = "`updatedon` = '$updatedon'";
                    $sqlUpdate = "UPDATE `dvi_hotel_room_price_book` SET " . implode(', ', $updateFields1) . " WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='1'";

                    if (sqlQUERY_LABEL($sqlUpdate)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                else :
                    // Insert new record
                    $arrFields1 = ['`hotel_id`', '`room_type_id`', '`room_id`', '`price_type`', '`year`', '`month`', '`createdby`', '`status`'];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrFields1[] = "`day_$day`";
                    endfor;

                    $arrValues1 = ["'$hotel_id'", "'$room_type_id'", "'$room_id'", "1", "'$year'", "'$month'", "'$logged_user_id'", "'1'"];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrValues1[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? '"' . (is_numeric($extra_bed_charge) ? $extra_bed_charge : '0') . '"' : '"0"';
                    endfor;

                    $sqlInsert = "INSERT INTO `dvi_hotel_room_price_book` (" . implode(', ', $arrFields1) . ") VALUES (" . implode(', ', $arrValues1) . ")";

                    if (sqlQUERY_LABEL($sqlInsert)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                endif;
            endforeach;
        endif;

        //UPDATE CHILD WITH BED RATE
        if ($child_with_bed_charge != "" && $room_start_date && $room_end_date) :
            $months = getMonthsBetweenDates($room_start_date, $room_end_date);

            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                $currentStartDay = ($month == date('F', strtotime($room_start_date))) ? (int)convertDateFormat($room_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($room_end_date))) ? (int)convertDateFormat($room_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists
                $sqlCheck = "SELECT `hotel_price_book_id` FROM `dvi_hotel_room_price_book` WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='2'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    $updateFields2 = [];
                    for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                        $updateFields2[] = "`day_$day` = \"" . (is_numeric($child_with_bed_charge) ? $child_with_bed_charge : '0') . "\"";
                    endfor;

                    $updatedon = date('Y-m-d H:i:s');
                    $updateFields2[] = "`updatedon` = '$updatedon'";
                    $sqlUpdate = "UPDATE `dvi_hotel_room_price_book` SET " . implode(', ', $updateFields2) . " WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='2'";

                    if (sqlQUERY_LABEL($sqlUpdate)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                else :
                    // Insert new record
                    $arrFields2 = ['`hotel_id`', '`room_type_id`', '`room_id`', '`price_type`', '`year`', '`month`', '`createdby`', '`status`'];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrFields2[] = "`day_$day`";
                    endfor;

                    $arrValues2 = ["'$hotel_id'", "'$room_type_id'", "'$room_id'", "2", "'$year'", "'$month'", "'$logged_user_id'", "'1'"];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrValues2[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? '"' . (is_numeric($child_with_bed_charge) ? $child_with_bed_charge : '0') . '"' : '"0"';
                    endfor;

                    $sqlInsert = "INSERT INTO `dvi_hotel_room_price_book` (" . implode(', ', $arrFields2) . ") VALUES (" . implode(', ', $arrValues2) . ")";

                    if (sqlQUERY_LABEL($sqlInsert)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                endif;
            endforeach;
        endif;

        //UPDATE CHILD WITHOUT BED RATE
        if ($child_without_bed_charge != "" && $room_start_date && $room_end_date) :
            $months = getMonthsBetweenDates($room_start_date, $room_end_date);

            foreach ($months as $monthYear) :
                list($month, $year) = explode('-', $monthYear);

                $currentStartDay = ($month == date('F', strtotime($room_start_date))) ? (int)convertDateFormat($room_start_date) : 1;
                $currentEndDay = ($month == date('F', strtotime($room_end_date))) ? (int)convertDateFormat($room_end_date) : (int)date('t', strtotime("$year-$month-01"));

                // Check if the record exists
                $sqlCheck = "SELECT `hotel_price_book_id` FROM `dvi_hotel_room_price_book` WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='3'";
                $resultCheck = sqlQUERY_LABEL($sqlCheck);

                if (sqlNUMOFROW_LABEL($resultCheck) > 0) :
                    // Update existing record
                    $updateFields3 = [];
                    for ($day = $currentStartDay; $day <= $currentEndDay; $day++) :
                        $updateFields3[] = "`day_$day` = \"" . (is_numeric($child_without_bed_charge) ? $child_without_bed_charge : '0') . "\"";
                    endfor;

                    $updatedon = date('Y-m-d H:i:s');
                    $updateFields3[] = "`updatedon` = '$updatedon'";
                    $sqlUpdate = "UPDATE `dvi_hotel_room_price_book` SET " . implode(', ', $updateFields3) . " WHERE `hotel_id` = '$hotel_id' AND `room_id` = '$room_id' AND `room_type_id` = '$room_type_id' AND `year` = '$year' AND `month` = '$month' AND `price_type`='3'";

                    if (sqlQUERY_LABEL($sqlUpdate)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                else :
                    // Insert new record
                    $arrFields3 = ['`hotel_id`', '`room_type_id`', '`room_id`', '`price_type`', '`year`', '`month`', '`createdby`', '`status`'];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrFields3[] = "`day_$day`";
                    endfor;

                    $arrValues3 = ["'$hotel_id'", "'$room_type_id'", "'$room_id'", "3", "'$year'", "'$month'", "'$logged_user_id'", "'1'"];
                    for ($day = 1; $day <= 31; $day++) :
                        $arrValues3[] = ($day >= $currentStartDay && $day <= $currentEndDay) ? '"' . (is_numeric($child_without_bed_charge) ? $child_without_bed_charge : '0') . '"' : '"0"';
                    endfor;

                    $sqlInsert = "INSERT INTO `dvi_hotel_room_price_book` (" . implode(', ', $arrFields3) . ") VALUES (" . implode(', ', $arrValues3) . ")";

                    if (sqlQUERY_LABEL($sqlInsert)) :
                        $response['success'] = true;
                    else :
                        $response['success'] = false;
                    endif;
                endif;
            endforeach;
        endif;

    endforeach;

    echo json_encode($response);

endif;
