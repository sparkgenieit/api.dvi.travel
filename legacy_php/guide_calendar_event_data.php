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

include_once('jackus.php');
admin_reguser_protect();
$guide_id = $_GET['guide_id'];

// Check if guide_ID is provided
if ($guide_id != '') {
    $select_price_list_data = sqlQUERY_LABEL("SELECT
        `slot_type`,
        `pax_count`,
        `guide_price_book_ID` AS `price_book_id`,
        `year`,
        `month`,
        `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`,
        `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`,
        `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`,
        `status`,
        `deleted`
    FROM
        `dvi_guide_pricebook`   
    WHERE
        `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());
} else {
    // Handle the case where guide_ID is not provided, modify as needed
    die("Guide ID not provided.");
}

$events = array(); // Initialize an array to store EVENTS
$counter = 0; // Initialize a counter

while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) {
    $type = 'slot_type_' . $row['slot_type'] . '_pax_' . $row['pax_count']; // Create an event type based on slot type and pax count

    // Loop through days and create events
    for ($dayNumber = 1; $dayNumber <= 31; $dayNumber++) {
        $counter++;
        // Format the start_date as "year-month-dayX"
        $startDate = sprintf("%04d-%02d-%02d", $row["year"], date('m', strtotime($row["month"])), $dayNumber);
        $dayFieldName = $row["day_" . $dayNumber];

        $eventName = 'Guide - Slot ' . $row['slot_type'] . ' - Pax ' . $row['pax_count'] . ' - ' . $dayFieldName;

        $events[] = [
            'title' => $eventName,
            'start' => $startDate,
            'end' => $startDate,
            'allDay' => true,
            'extendedProps' => [
                'calendar' => $type, // Use the event type (slot_type_pax_count)
                'guide_id' => $guide_id, // Include guide ID in extended properties
            ],
        ];
    }
}

// Output the events array as JSON
header('Content-Type: application/json');
echo json_encode($events);
