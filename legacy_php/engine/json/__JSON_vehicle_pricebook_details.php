<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2020 Touchmark De`Science
*
*/

include_once('../../jackus.php');

// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    // echo "{";
    // echo '"data":[';
$vid = $_GET['vid'];
$vid = '1';

// $roomtype_ID = $_GET['roomTypeFilter'];

// $room_ID = getROOM_DETAILS($roomtype_ID, 'ROOM_TYPE_ID');

// $filter_local = " and `room_id` = '$room_ID' ";

// if ($room_ID == '0' || $room_ID == '') :

    // Execute the SQL query
    $select_price_list_data = sqlQUERY_LABEL("SELECT 'local' AS `type`, `vehicle_price_book_id`, `vehicle_type_id`, `hours_limit`, `cost_type`, `year`, `month`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status`, `deleted`
    FROM `dvi_vehicle_local_pricebook`
    WHERE `deleted` = '0' AND `status` = '1' AND `vehicle_type_id` = '$vid'
    
    UNION ALL
    
    SELECT 'outstation' AS `type`, `vehicle_outstation_price_book_id`, `vehicle_type_id`, `kms_limit_id`, `time_limit_id`, `year`, `month`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status`, `deleted`
    FROM `dvi_vehicle_outstation_price_book`
    WHERE `deleted` = '0' AND `status` = '1' AND `vehicle_type_id` = '$vid';") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());
 
$events = array(); // Initialize an array to store EVENTS
$counter = 0; // Initialize a counter

while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
    $type = $row['type']; // Get the event type (room or amenity)
    $month = $row["month"];
    $year = $row["year"];
    $price_type = $row["price_type"];

    if ($type == 'local') :
        $title_name = 'Local';
    elseif ($type == 'amenities') :
        if ($price_type == 1) :
            $price_type_label = 'Day';
        elseif ($price_type == 2) :
            $price_type_label = 'Hour';
        else :
            $price_type_label = 'N/A';
        endif;
        $title_name = 'Outstation';
    endif;

    if (strtolower($month) == "february") :
        if ($year % 400 == 0) :
            $leapyear = 1;
        elseif ($year % 100 == 0) :
            $leapyear = 1;
        elseif ($year % 4 == 0) :
            $leapyear = 1;
        else :
            $leapyear = 0;
        endif;
    endif;

    $total_days_in_month = 0;
    if (strtolower($month) == "february") :
        if ($leapyear == 1) :
            $total_days_in_month = 29;
        else :
            $total_days_in_month = 28;
        endif;
    else :
        if ((strtolower($month) != "april") && (strtolower($month) != "june") && (strtolower($month) != "september") && (strtolower($month) != "november")) :
            $total_days_in_month = 31;
        else :
            $total_days_in_month = 30;
        endif;
    endif;

    // Loop through days and create events
    for ($dayNumber = 1; $dayNumber <= $total_days_in_month; $dayNumber++) {
        $counter++;
        if ($row["day_" . $dayNumber]) :
            if ($price_type != 0) :
                $price_rate = number_format($row["day_" . $dayNumber], 2) . '/' . $price_type_label;
            else :
                $price_rate = number_format($row["day_" . $dayNumber], 2);
            endif;
            $startDate = sprintf("%04d-%02d-%02d", $year, date('m', strtotime($month)), $dayNumber);

            $events[] = [
                'title' => $title_name . " - " . $global_currency_format . ' ' . $price_rate,
                'start' => $startDate,
                'end' => $startDate,
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => $type, // Use the event type (room or amenities)
                ],
            ];
        endif;
    }
endwhile;

// Output the events array as JSON
header('Content-Type: application/json');
echo json_encode($events);
