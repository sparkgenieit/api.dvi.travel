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
$hotel_ID = $_GET['choose_hotel'];
// $choosen_state = $_GET['choosen_state'];
// $choosen_city = $_GET['choosen_city'];
// $choosen_place = $_GET['choosen_place'];
// Execute the SQL query
if ($hotel_ID != '') :
    $select_price_list_data = sqlQUERY_LABEL("SELECT
    'rooms' AS `type`,
    `hotel_price_book_id` AS `price_book_id`,
    `hotel_id`, `room_id` as `room_id_R_amenities_id`, '0' as `price_type`,
    `year`,
    `month`,
    `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`,
    `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`,
    `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`,
    `status`,
    `deleted`
FROM
    `dvi_hotel_room_price_book`   
WHERE
    `deleted` = '0' and `hotel_ID` = $hotel_ID
UNION ALL

SELECT
    'amenities' AS type,
    `hotel_amenities_price_book_id` AS `price_book_id`,
    `hotel_id`, `hotel_amenities_id` as `room_id_R_amenities_id`, `pricetype` as `price_type`,
    `year`,
    `month`,
    `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`,
    `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`,
    `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`,
    `status`,
    `deleted`
FROM
    `dvi_hotel_amenities_price_book`
WHERE
    `deleted` = '0' and `hotel_ID` = $hotel_ID") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());
else :

    $select_price_list_data = sqlQUERY_LABEL("SELECT
'rooms' AS `type`,
`hotel_price_book_id` AS `price_book_id`,
`hotel_id`, `room_id` as `room_id_R_amenities_id`, '0' as `price_type`,
`year`,
`month`,
`day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`,
`day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`,
`day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`,
`status`,
`deleted`
FROM
`dvi_hotel_room_price_book`   
WHERE
`deleted` = '0'
UNION ALL

SELECT
'amenities' AS type,
`hotel_amenities_price_book_id` AS `price_book_id`,
`hotel_id`, `hotel_amenities_id` as `room_id_R_amenities_id`, `pricetype` as `price_type`,
`year`,
`month`,
`day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`,
`day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`,
`day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`,
`status`,
`deleted`
FROM
`dvi_hotel_amenities_price_book`
WHERE
`deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_PRICE_LIST:" . sqlERROR_LABEL());
endif;

$events = array(); // Initialize an array to store EVENTS
$counter = 0; // Initialize a counter

while ($row = sqlFETCHARRAY_LABEL($select_price_list_data)) :
    $type = $row['type']; // Get the event type (room or amenity)

    if ($type === 'rooms') :
        $room_id = $row['room_id_R_amenities_id'];
        $hotel_ID = $row['hotel_id'];
        $eventTitle = getROOM_DETAILS($room_id, 'room_title');
        $hotel_name = getHOTEL_DETAIL($hotel_ID, '', 'label');

    elseif ($type === 'amenities') :
        $hotel_amenities_id = $row['room_id_R_amenities_id'];
        $eventTitle = getAMENITYDETAILS($hotel_amenities_id, 'amenities_title');
        $hotel_ID = $row['hotel_id'];
        $hotel_name = getHOTEL_DETAIL($hotel_ID, '', 'label');

    endif;

    // Loop through days and create events
    for ($dayNumber = 1; $dayNumber <= 31; $dayNumber++) {
        $counter++;
        // Format the start_date as "year-month-dayX"
        $startDate = sprintf("%04d-%02d-%02d", $row["year"], date('m', strtotime($row["month"])), $dayNumber);
        $dayFieldName = $row["day_" . $dayNumber];

        $eventName = ($type === 'rooms') ? 'Room - ' . $dayFieldName : 'Amenity - ' . $dayFieldName;

        $events[] = [
            // 'id' => $counter,
            'title' => $hotel_name . " - " . $eventTitle . " - " . $dayFieldName,
            'start' => $startDate,
            'end' => $startDate,
            'allDay' => true,
            'extendedProps' => [
                'calendar' => $type, // Use the event type (room or amenities)
            ],
        ];
    }

endwhile;

// Output the events array as JSON
header('Content-Type: application/json');
echo json_encode($events);
