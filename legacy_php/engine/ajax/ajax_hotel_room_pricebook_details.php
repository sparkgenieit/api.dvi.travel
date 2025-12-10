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
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $response = [];
        $errors = [];

        $hotelID = $_POST['hotelID'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Get all months between start date and end date
        $months = getMonthsBetweenDates($start_date, $end_date);

        // Prepare HTML table with compact CSS and better UI
        $html = <<<HTML
<style>
body, html { margin: 0; padding: 0; font-family: Arial, sans-serif; }
.table-container { display: flex; width: 100vw; height: auto; }
.scrollable-columns { width: 80%; overflow-x: auto; overflow-y: auto; }
table { width: 0%; border-collapse: collapse; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
th, td { padding: 4px; text-align: center; font-size: 0.9vw; white-space: nowrap; }
.scrollable-columns th, td { border: 1px solid #ddd; }
th { background: linear-gradient(to bottom, rgb(114, 49, 207), rgb(195, 60, 166), rgb(238, 63, 206)); color: white; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
.fixed-columns table th:first-child { border-top-left-radius: 8px; }
.scrollable-columns table th:last-child { border-top-right-radius: 8px; }
.scrollable-columns table td:last-child { border-bottom-right-radius: 8px; }
td { background-color: #f4f4f4; color: #333; }
tbody tr:nth-child(even) td { background-color: #f9f9f9; border: 1px solid #ddd; }
tbody tr:nth-child(odd) td { background-color: #f9f9f9; border: 1px solid #ddd; }
.fixed-columns { position: relative; z-index: 10; }
.fixed-columns table { border-right: 1px solid #ddd; }
.fixed-columns th { background-color: #444; color: white; position: sticky; left: 0; z-index: 10; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
.scrollable-columns::-webkit-scrollbar { width: 10px; height: 10px; }
.scrollable-columns::-webkit-scrollbar-track { background-color: #e0e0e0; border-radius: 8px; }
.scrollable-columns::-webkit-scrollbar-thumb { background-color: #4b0082; border-radius: 10px; border: 2px solid #e0e0e0; }
.scrollable-columns::-webkit-scrollbar-thumb:hover { background-color: #4b0082; }
.scrollable-columns { scrollbar-width: thin; scrollbar-color: #4b0082 #e0e0e0; }
</style>
HTML;

        $html .= ' <h5 class="mb-0">Hotel Room Details</h5><div class="table-container">';

        $rooms_master = []; // key: room_id-room_type_id
        foreach ($months as $monthYear) {
            list($monthName, $year) = explode('-', $monthYear);
            $sql = "
        SELECT DISTINCT
            ROOM.room_id,
            ROOM.room_title,
            RT.room_type_id,
            RT.room_type_title
        FROM dvi_hotel_room_price_book PB
        INNER JOIN dvi_hotel_rooms ROOM
            ON PB.room_id = ROOM.room_id AND PB.hotel_id = ROOM.hotel_id
        INNER JOIN dvi_hotel_roomtype RT
            ON RT.room_type_id = PB.room_type_id
        WHERE PB.hotel_id   = '$hotelID'
          AND PB.year       = '$year'
          AND PB.month      = '$monthName'
          AND PB.price_type = '0'
        ORDER BY ROOM.room_id ASC
    ";
            $res = sqlQUERY_LABEL($sql);
            while ($r = sqlFETCHARRAY_LABEL($res)) {
                $key = $r['room_id'] . '-' . $r['room_type_id'];
                if (!isset($rooms_master[$key])) {
                    $rooms_master[$key] = [
                        'room_id'         => (int)$r['room_id'],
                        'room_type_id'    => (int)$r['room_type_id'],
                        'room_title'      => $r['room_title'],
                        'room_type_title' => $r['room_type_title'],
                    ];
                }
            }
        }

        $html .= '<div class="fixed-columns">
<table>
    <thead>
        <tr>
            <th>Room Name</th>
            <th>Room Type</th>
        </tr>
    </thead>
    <tbody>';

        $monthIndex = 0;
        foreach ($months as $monthYear) {
            // Add a repeated header row to align with the right table’s month header height
            if ($monthIndex > 0) {
                $html .= '<tr>
            <th style="border-top-left-radius:0">Room Name</th>
            <th>Room Type</th>
        </tr>';
            }

            // Print the SAME room list for this month
            foreach ($rooms_master as $rm) {
                $html .= '<tr>
            <td class="text-start">' . htmlspecialchars($rm['room_title']) . '</td>
            <td class="text-start">' . htmlspecialchars($rm['room_type_title']) . '</td>
        </tr>';
            }

            $monthIndex++;
        }

        $html .= '</tbody></table></div>';

        $html .= '<div class="scrollable-columns"><table style="width:0%">';
        $previousMonthYear = '';

        foreach ($months as $monthYear) {
            $year         = date('Y', strtotime($monthYear));
            $monthNumeric = date('m', strtotime($monthYear));
            $monthText    = date('F', strtotime($monthYear));

            // Collect days of this month in the selected range
            $days_in_month = [];
            foreach (getValidDaysForDateRange($start_date, $end_date) as $day) {
                if (date('m', strtotime($day)) == $monthNumeric && date('Y', strtotime($day)) == $year) {
                    $days_in_month[] = $day;
                }
            }
            if (empty($days_in_month)) continue;

            // Close previous month table and open a new one
            if ($previousMonthYear !== '') {
                $html .= '</tbody></table>';
            }
            $html .= '<table style="width:0%"><thead><tr>';
            foreach ($days_in_month as $day) {
                $html .= '<th>' . date('D - d M, Y', strtotime($day)) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // Precompute columns & index prices for this month
            $dayCols    = array_map(fn($d) => 'day_' . date('j', strtotime($d)), $days_in_month);
            $selectCols = implode(',', $dayCols);

            $price_sql = "
        SELECT room_id, room_type_id, $selectCols
        FROM dvi_hotel_room_price_book
        WHERE hotel_id   = '$hotelID'
          AND year       = '$year'
          AND month      = '$monthText'
          AND price_type = '0'
    ";
            $price_res = sqlQUERY_LABEL($price_sql);
            $price_idx = []; // key: roomId-roomTypeId
            while ($pr = sqlFETCHARRAY_LABEL($price_res)) {
                $pkey = $pr['room_id'] . '-' . $pr['room_type_id'];
                $price_idx[$pkey] = $pr;
            }

            // Render rows in the SAME order as LEFT table
            foreach ($rooms_master as $rm) {
                $key = $rm['room_id'] . '-' . $rm['room_type_id'];
                $row = $price_idx[$key] ?? null;

                $html .= '<tr>';
                foreach ($days_in_month as $d) {
                    $col = 'day_' . date('j', strtotime($d));
                    $price = ($row && isset($row[$col]) && $row[$col] !== '' && $row[$col] !== null)
                        ? general_currency_symbol . ' ' . number_format((float)$row[$col], 2)
                        : 'No Price';
                    $html .= '<td>' . $price . '</td>';
                }
                $html .= '</tr>';
            }

            $previousMonthYear = $monthYear;
        }
        $html .= '</tbody></table></div>'; // end scrollable

        echo $html;

    endif;
else:
    echo "Request Ignored";
endif;
