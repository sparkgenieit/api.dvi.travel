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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) { // CHECK AJAX REQUEST

    header('Content-Type: application/json');

    $state = $_GET['state'];
    $city = $_GET['city'];
    $month = $_GET['month'];
    $year = $_GET['year'];

    // Ensure joins only include relevant records to avoid duplicates
    $select_hotel_room_pricebook_query = sqlQUERY_LABEL("
        SELECT
            h.hotel_id,
            h.hotel_name,
            hrpb.year,
            hrpb.month,
            hr.room_title,
            r.room_type_title,
            hrpb.room_type_id,
            hrpb.price_type,
            hrpb.day_1,
            hrpb.day_2,
            hrpb.day_3,
            hrpb.day_4,
            hrpb.day_5,
            hrpb.day_6,
            hrpb.day_7,
            hrpb.day_8,
            hrpb.day_9,
            hrpb.day_10,
            hrpb.day_11,
            hrpb.day_12,
            hrpb.day_13,
            hrpb.day_14,
            hrpb.day_15,
            hrpb.day_16,
            hrpb.day_17,
            hrpb.day_18,
            hrpb.day_19,
            hrpb.day_20,
            hrpb.day_21,
            hrpb.day_22,
            hrpb.day_23,
            hrpb.day_24,
            hrpb.day_25,
            hrpb.day_26,
            hrpb.day_27,
            hrpb.day_28,
            hrpb.day_29,
            hrpb.day_30,
            hrpb.day_31,
            r.room_type_title
        FROM
            dvi_hotel AS h
        JOIN
            dvi_hotel_room_price_book AS hrpb ON h.hotel_id = hrpb.hotel_id
        JOIN
            dvi_hotel_rooms AS hr ON h.hotel_id = hr.hotel_id AND hrpb.room_type_id = hr.room_type_id
        JOIN
            dvi_cities AS c ON h.hotel_city = c.id
        JOIN
            dvi_states AS s ON h.hotel_state = s.id
        JOIN
            dvi_hotel_roomtype AS r ON hrpb.room_type_id = r.room_type_id
        WHERE
            c.id = '$city' AND
            s.id = '$state' AND
            hrpb.month = '$month' AND
            hrpb.year = '$year' AND
            h.deleted = '0' AND
            hrpb.deleted = '0' AND
            hr.deleted = '0' AND
            h.status = '1' AND
            hrpb.status = '1' GROUP BY hrpb.year, hrpb.month, h.hotel_id, hr.room_id, hr.room_type_id, hr.room_title, hrpb.price_type ORDER BY h.hotel_id,hrpb.room_type_id, hrpb.price_type
    ") or die(json_encode(['error' => "#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL()]));

    $datas = [];
    $counter = 0;

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_room_pricebook_query)) {
        $counter++;
        $hotel_name = $fetch_list_data['hotel_name'];
        $room_title = $fetch_list_data['room_title'];
        $room_type_id = $fetch_list_data['room_type_id'];
        $room_type_title = $fetch_list_data['room_type_title'];
        $price_type = $fetch_list_data['price_type'];
        $year = $fetch_list_data['year'];
        $month = $fetch_list_data['month'];

        if ($price_type == 0):
            $price_type_label = 'Room Rate';
        elseif ($price_type == 1):
            $price_type_label = 'Extra Bed Rate';
        elseif ($price_type == 2):
            $price_type_label = 'Child with Bed Rate';
        elseif ($price_type == 3):
            $price_type_label = 'Child without Bed Rate';
        endif;

        $day_data = [];

        for ($day_count = 1; $day_count <= 31; $day_count++) {
            $day_variable = 'day_' . $day_count;
            $day_price = $fetch_list_data[$day_variable];
            $day_data[$day_variable] = $day_price > 0 ? general_currency_symbol . ' ' . number_format(
                $day_price,
                0
            ) : general_currency_symbol . number_format(0, 0);  // Format for better readability
        }

        $datas[] = array_merge([
            'count' => $counter,
            'hotel_name' => $hotel_name,
            'room_title' => $room_title,
            'room_type_title' => $room_type_title,
            'price_type_label' => $price_type_label,
            'year' => $year,
            'month' => $month
        ], $day_data);
    }

    echo json_encode(['data' => $datas]);
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit(); // Ensure the script stops here
}
