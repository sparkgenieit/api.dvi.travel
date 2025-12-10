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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        // Capture input data from POST request
        $state = $_POST['state'];
        $city = $_POST['city'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $dates = generateDateRange($start_date, $end_date); // Generates date range array from start to end dates

        // Prepare HTML table with compact CSS and better UI
        $html = <<<HTML
<style>
body, html { margin: 0; padding: 0; font-family: Arial, sans-serif; overflow-x: hidden; /* Restrict horizontal scroll */ }
.table-container { display: flex; width: 100vw; height: auto; }
.fixed-columns { position: relative; z-index: 10; }
.scrollable-columns { width: 80%; overflow-x: auto; overflow-y: hidden; }
table { border-collapse: collapse; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
th, td { padding: 4px; text-align: center; font-size: 0.9vw; white-space: nowrap; }
.scrollable-columns th, td { border: 1px solid #ddd; }
th { background: linear-gradient(to bottom, rgb(114, 49, 207), rgb(195, 60, 166), rgb(238, 63, 206)); color: white; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.fixed-columns table th:first-child { border-top-left-radius: 8px; }
.scrollable-columns table th:last-child { border-top-right-radius: 8px; }
.scrollable-columns table td:last-child { border-bottom-right-radius: 8px; }
td { background-color: #f4f4f4; color: #333; }
tbody tr:nth-child(even) td { background-color: #f9f9f9; }
tbody tr:nth-child(odd) td { background-color: #f9f9f9; }
.fixed-columns table { border-right: 1px solid #ddd; }
.fixed-columns th { background-color: #444; color: white; position: sticky; left: 0; z-index: 10; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
.scrollable-columns::-webkit-scrollbar { width: 10px; height: 10px; }
.scrollable-columns::-webkit-scrollbar-track { background-color: #e0e0e0; border-radius: 8px; }
.scrollable-columns::-webkit-scrollbar-thumb { background-color: #4b0082; border-radius: 10px; border: 2px solid #e0e0e0; }
.scrollable-columns::-webkit-scrollbar-thumb:hover { background-color: #4b0082; }
.scrollable-columns { scrollbar-width: thin; scrollbar-color: #4b0082 #e0e0e0; }
</style>

<div class="col-md-8">
</div>
<div class="col-md-4">
    <input type="text" id="search" class="form-control" autocomplete="off" placeholder="Search..." onkeyup="searchTable()">
</div>

<div class="table-container">
    <div class="fixed-columns">
        <table>
            <thead>
                <tr>
                    <th class="text-start">Hotel Name</th>
                    <th class="text-start">City</th>
                    <th class="text-start">Room Name</th>
                    <th class="text-start">Room Type</th>
                    <th class="text-start">Rate Type</th>
                </tr>
            </thead>
            <tbody>
HTML;

        // Query to fetch room data for given state and city
        $select_combined_data = "SELECT DISTINCT PRICE_BOOK.`room_id`, HOTEL.`hotel_id`, HOTEL.`hotel_name`, ROOM.`room_title`, PRICE_BOOK.`room_type_id`, ROOM_TYPE.`room_type_title`, STATES.`name` AS STATE_NAME, CITY.`name` AS CITY_NAME, PRICE_BOOK.`price_type` FROM `dvi_hotel_room_price_book` PRICE_BOOK LEFT JOIN `dvi_hotel_rooms` ROOM ON PRICE_BOOK.`room_id` = ROOM.`room_id` AND PRICE_BOOK.`hotel_id` = ROOM.`hotel_id` LEFT JOIN `dvi_hotel_roomtype` ROOM_TYPE ON ROOM_TYPE.`room_type_id` = PRICE_BOOK.`room_type_id` LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = PRICE_BOOK.`hotel_id` LEFT JOIN `dvi_cities` AS CITY ON HOTEL.`hotel_city` = CITY.`id` LEFT JOIN `dvi_states` AS STATES ON HOTEL.`hotel_state` = STATES.`id` WHERE STATES.`id` = '$state' AND CITY.`id` = '$city' ORDER BY ROOM.`room_title`, PRICE_BOOK.`room_type_id`, PRICE_BOOK.`price_type` ASC";

        $result = sqlQUERY_LABEL($select_combined_data);
        $rooms = [];
        while ($row = sqlFETCHARRAY_LABEL($result)) {
            $rooms[] = $row;
        }

        // Loop through each room to display details under fixed columns
        foreach ($rooms as $room) {
            $price_type = $room['price_type'];

            if ($price_type == 0) {
                $price_type_label = 'Room Rate';
            } elseif (
                $price_type == 1
            ) {
                $price_type_label = 'Extra Bed Rate';
            } elseif (
                $price_type == 2
            ) {
                $price_type_label = 'Child with Bed Rate';
            } elseif (
                $price_type == 3
            ) {
                $price_type_label = 'Child without Bed Rate';
            }

            $html .= '<tr>';
            $html .= '<td class="text-start">' . htmlspecialchars($room['hotel_name']) . '</td>';
            $html .= '<td class="text-start">' . htmlspecialchars($room['CITY_NAME']) . '</td>';
            $html .= '<td class="text-start">' . htmlspecialchars($room['room_title']) . '</td>';
            $html .= '<td class="text-start">' . htmlspecialchars($room['room_type_title']) . '</td>';
            $html .= '<td class="text-start">' . htmlspecialchars($price_type_label) . '</td>';
            $html .= '</tr>';
        }

        $select_hotel_mealplan_data = "SELECT DISTINCT HOTEL.`hotel_id`, HOTEL.`hotel_name`, CITY.`name` AS CITY_NAME, PRICE_BOOK.`meal_type` FROM `dvi_hotel_meal_price_book` PRICE_BOOK LEFT JOIN `dvi_hotel` HOTEL ON HOTEL.`hotel_id` = PRICE_BOOK.`hotel_id` LEFT JOIN `dvi_cities` AS CITY ON HOTEL.`hotel_city` = CITY.`id` LEFT JOIN `dvi_states` AS STATES ON HOTEL.`hotel_state` = STATES.`id` WHERE STATES.`id` = '$state' AND CITY.`id` = '$city' ORDER BY PRICE_BOOK.`meal_type` ASC";

        $hotel_mealplan_result = sqlQUERY_LABEL($select_hotel_mealplan_data);
        $hotel_meal_plan = [];
        while ($meal_plan_row = sqlFETCHARRAY_LABEL($hotel_mealplan_result)) {
            $hotel_meal_plan[] = $meal_plan_row;
        }

        // Loop through each room to display details under fixed columns
        foreach ($hotel_meal_plan as $hotel_meal_plan_data) {
            $meal_type = $hotel_meal_plan_data['meal_type'];

            if (
                $meal_type == 1
            ) {
                $meal_type_label = 'Breakfast';
            } elseif (
                $meal_type == 2
            ) {
                $meal_type_label = 'Lunch';
            } elseif (
                $meal_type == 3
            ) {
                $meal_type_label = 'Dinner';
            }

            $html .= '<tr>';
            $html .= '<td class="text-start">' . htmlspecialchars($hotel_meal_plan_data['hotel_name']) . '</td>';
            $html .= '<td class="text-start">' . htmlspecialchars($hotel_meal_plan_data['CITY_NAME']) . '</td>';
            $html .= '<td class="text-start">--</td>';
            $html .= '<td class="text-start">--</td>';
            $html .= '<td class="text-start">' . htmlspecialchars($meal_type_label) . '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>'; // Close fixed columns table

        $html .= '<div class="scrollable-columns"><table><thead><tr>';

        // Add dynamic headers for each date in the range
        foreach ($dates as $date) {
            $html .= '<th>' . date('D - d M, Y', strtotime($date)) . '</th>';
        }

        $html .= '</tr></thead><tbody>';

        // Loop through each room to display prices under scrollable columns
        foreach ($rooms as $room) {
            $html .= '<tr>';

            // Fetch price data for each date in the range
            foreach ($dates as $date) {
                $dayColumn = "day_" . date('j', strtotime($date));
                $year = date('Y', strtotime($date));
                $month = date('F', strtotime($date));
                $price_data_query = " SELECT `$dayColumn` FROM `dvi_hotel_room_price_book` WHERE `room_id` = '" . $room['room_id'] . "' AND `room_type_id` = '" . $room['room_type_id'] . "' AND `price_type` = '" . $room['price_type'] . "' AND `hotel_id` = '" . $room['hotel_id'] . "' AND `year` = '" . $year . "' AND `month` = '" . $month . "' ";

                /* echo $price_data_query;
                echo "<br>"; */

                $price_result = sqlQUERY_LABEL($price_data_query);
                $price_row = sqlFETCHARRAY_LABEL($price_result);

                // Display price if available, else show 'No Price'
                $html .= '<td>' . (!empty($price_row[$dayColumn]) ? general_currency_symbol . ' ' . number_format($price_row[$dayColumn], 2) : 'No Price') . '</td>';
            }

            $html .= '</tr>';
        }

        // Loop through each room to display prices under scrollable columns
        foreach ($hotel_meal_plan as $hotel_meal_plan_data) {
            $html .= '<tr>';

            // Fetch price data for each date in the range
            foreach ($dates as $date) {
                $dayColumn = "day_" . date('j', strtotime($date));
                $year = date('Y', strtotime($date));
                $month = date('F', strtotime($date));
                $price_data_query = " SELECT `$dayColumn` FROM `dvi_hotel_meal_price_book` WHERE `meal_type` = '" . $hotel_meal_plan_data['meal_type'] . "' AND `hotel_id` = '" . $hotel_meal_plan_data['hotel_id'] . "' AND `year` = '" . $year . "' AND `month` = '" . $month . "' ";

                /* echo $price_data_query;
                echo "<br>"; */

                $price_result = sqlQUERY_LABEL($price_data_query);
                $price_row = sqlFETCHARRAY_LABEL($price_result);

                // Display price if available, else show 'No Price'
                $html .= '<td>' . (!empty($price_row[$dayColumn]) ? general_currency_symbol . ' ' . number_format($price_row[$dayColumn], 2) : 'No Price') . '</td>';
            }

            $html .= '</tr>';
        }

        // Close scrollable columns table and container
        $html .= '</tbody></table></div></div>';

        // Output the final HTML
        echo $html;

    endif;
endif;
