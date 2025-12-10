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

// include_once('../../jackus.php');

// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

//     echo "{";
//     echo '"data":[';


//     $month = $_GET['month'];
//     $year = $_GET['year'];




//     $select_hotel_room_pricebook_query = sqlQUERY_LABEL("SELECT `guide_price_book_ID`, `guide_id`, `year`, `month`, `pax_count`, `slot_type`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status` FROM `dvi_guide_pricebook` WHERE `deleted` = '0' And `status`='1' AND `month`='$month' AND `year`= '$year' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

//     $datas = '';
//     $counter = 0;

//     while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_room_pricebook_query)) :
//         $counter++;
//         $guide_price_book_ID = $fetch_list_data['guide_price_book_ID'];
//         $guide_id = $fetch_list_data['guide_id'];
//         $guide_name = getGUIDEDETAILS($guide_id, 'label');
//         $slot = getSLOTTYPE($guide_id, 'label');
//         $pax_count = $fetch_list_data['pax_count'];
//         $slot_type = getSLOTTYPE($guide_id, 'label');
//         $year = $fetch_list_data['year'];
//         $month = $fetch_list_data['month'];

//         $day_data = [];
//         for ($day_count = 1; $day_count <= 31; $day_count++) : $day_variable = 'day_' . $day_count;
//             $day_data[$day_variable] = $fetch_list_data[$day_variable];
//         endfor;
//         $datas .= json_encode(array_merge([
//             'count' =>
//             $counter,
//             'guide_price_book_ID' => $guide_price_book_ID,
//             'guide_id' => $guide_id,
//             'guide_name' => $guide_name,
//             'pax_count' => $pax_count,
//             'slot' => $slot,
//             'year' => $year,
//             'month' => $month
//         ], $day_data)) . ',';

//     endwhile; //end of while loop

//     $data_formatted = rtrim($datas, ',');
//     echo $data_formatted;
//     echo "]}";

// endif;



include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    header('Content-Type: application/json'); // Set the header for JSON output

    $month = $_GET['month'] ?? ''; // Safely get the month, default to empty string if not set
    $year = $_GET['year'] ?? ''; // Safely get the year, default to empty string if not set

    // Map slot types to their descriptions
    $slot_descriptions = [
        1 => 'Slot 1: 8 AM to 1 PM',
        2 => 'Slot 2: 1 PM to 6 PM',
        3 => 'Slot 3: 6 PM to 9 PM'
    ];

    // Map pax count ranges to slot types
    $pax_count_descriptions = [
        1 => '1-5 pax',
        2 => '6-14 pax',
        3 => '15-40 pax'
    ];


    // echo "SELECT `guide_price_book_ID`, `guide_id`, `year`, `month`, `pax_count`, `slot_type`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status` FROM `dvi_guide_pricebook` WHERE `deleted` = '0' AND `status`='1' AND `month`='$month' AND `year`= '$year'";
    // exit;


    $select_hotel_room_pricebook_query = sqlQUERY_LABEL("SELECT `guide_price_book_ID`, `guide_id`, `year`, `month`, `pax_count`, `slot_type`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `status` FROM `dvi_guide_pricebook` WHERE `deleted` = '0' AND `status`='1' AND `month`='$month' AND `year`= '$year'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

    $results = [];
    $counter = 0;
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_room_pricebook_query)) {
        $counter++;
        $day_data = [];
        // for ($day_count = 1; $day_count <= 31; $day_count++) {
        //     $day_variable = 'day_' . $day_count;
        //     $day_data[$day_variable] = $fetch_list_data[$day_variable] ?? 0; // Default to 0 if undefined
        // }


        $day_data = [];
        for ($day = 1; $day <= 31; $day++) {
            $dayKey = "day_$day";
            // Format each day's price with the currency symbol, defaulting to 0 if not set
            $day_data[$dayKey] = $fetch_list_data[$dayKey] ? general_currency_symbol . ' ' . number_format($fetch_list_data[$dayKey], 0) : general_currency_symbol . ' ' . number_format(0, 0);
        }

        $results[] = array_merge([
            'count' => $counter,
            'guide_price_book_ID' => $fetch_list_data['guide_price_book_ID'],
            'guide_id' => $fetch_list_data['guide_id'],
            'guide_name' => getGUIDEDETAILS($fetch_list_data['guide_id'], 'label'),
            'pax_count' => $pax_count_descriptions[$fetch_list_data['pax_count']] ?? 'Unknown Pax Range', // Include pax count description based on slot type
            'slot_type' => $slot_descriptions[$fetch_list_data['slot_type']] ?? 'Unknown Slot', // Include slot description
            'year' => $fetch_list_data['year'],
            'month' => $fetch_list_data['month']
        ], $day_data);
    }

    echo json_encode(['data' => $results]); // Print the results as JSON
}
