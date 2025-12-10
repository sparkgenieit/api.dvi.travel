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


        // ===================== EXTRA BED / CHILD RATES =====================
        $html .= '<h5 class="mt-5 mb-0">Hotel Extra Bed/Child with Bed/Child without Bed Details</h5>
<div class="table-container">';

        // --- helpers ---
        $priceTypeLabel = function ($pt) {
            if ((int)$pt === 1) return 'Extra Bed Rate';
            if ((int)$pt === 2) return 'Child with Bed Rate';
            if ((int)$pt === 3) return 'Child without Bed Rate';
            return 'Unknown';
        };
        $priceTypes = [1, 2, 3];

        // inclusive day list helper
        if (!function_exists('daysBetweenInclusive')) {
            function daysBetweenInclusive(string $start, string $end): array
            {
                $out = [];
                $s = new DateTime($start);
                $e = new DateTime($end);
                if ($s > $e) return $out;
                while ($s <= $e) {
                    $out[] = $s->format('Y-m-d');
                    $s->modify('+1 day');
                }
                return $out;
            }
        }

        // 1) master rooms list (union across selected months)
        $rooms_master = []; // key: roomId-roomTypeId
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
          AND PB.price_type IN ('1','2','3')
        ORDER BY ROOM.room_id ASC, RT.room_type_id ASC
    ";
            $res = sqlQUERY_LABEL($sql);
            while ($r = sqlFETCHARRAY_LABEL($res)) {
                $key = $r['room_id'] . '-' . $r['room_type_id'];
                if (!isset($rooms_master[$key])) {
                    $rooms_master[$key] = [
                        'room_id'         => (int)$r['room_id'],
                        'room_title'      => $r['room_title'],
                        'room_type_id'    => (int)$r['room_type_id'],
                        'room_type_title' => $r['room_type_title'],
                    ];
                }
            }
        }

        // 2) LEFT: fixed columns — repeat for every month and include Bed Type
        $html .= '<div class="fixed-columns">
<table>
    <thead>
        <tr>
            <th>Room Name</th>
            <th>Room Type</th>
            <th>Bed Type</th>
        </tr>
    </thead>
    <tbody>';

        $monthIndex = 0;
        foreach ($months as $monthYear) {
            if ($monthIndex > 0) {
                $html .= '<tr>
            <th style="border-top-left-radius:0">Room Name</th>
            <th>Room Type</th>
            <th>Bed Type</th>
        </tr>';
            }
            if (!empty($rooms_master)) {
                foreach ($rooms_master as $rm) {
                    foreach ($priceTypes as $pt) {
                        $html .= '<tr>
                    <td class="text-start">' . htmlspecialchars($rm['room_title']) . '</td>
                    <td class="text-start">' . htmlspecialchars($rm['room_type_title']) . '</td>
                    <td class="text-start">' . htmlspecialchars($priceTypeLabel($pt)) . '</td>
                </tr>';
                    }
                }
            } else {
                $html .= '<tr><td colspan="3">No data found</td></tr>';
            }
            $monthIndex++;
        }
        $html .= '</tbody></table></div>';

        // 3) RIGHT: scrollable — per month; rows = rooms_master × priceTypes in same order
        $html .= '<div class="scrollable-columns"><table style="width:0%">';
        $prevMonth = '';

        foreach ($months as $monthYear) {
            $year         = date('Y', strtotime($monthYear));
            $monthNumeric = date('m', strtotime($monthYear));
            $monthText    = date('F', strtotime($monthYear));

            // exact month bounds
            $monthFirst = date('Y-m-01', strtotime("$year-$monthNumeric-01"));
            $monthLast  = date('Y-m-t',  strtotime("$year-$monthNumeric-01"));

            // inclusive overlap with requested range
            $startEdge = max(strtotime($start_date), strtotime($monthFirst));
            $endEdge   = min(strtotime($end_date),   strtotime($monthLast));
            if ($startEdge > $endEdge) {
                continue;
            }

            $days_in_month = daysBetweenInclusive(date('Y-m-d', $startEdge), date('Y-m-d', $endEdge));
            if (empty($days_in_month)) {
                continue;
            }

            if ($prevMonth !== '') {
                $html .= '</tbody></table>';
            }

            // month header
            $html .= '<table style="width:0%"><thead><tr>';
            foreach ($days_in_month as $d) {
                $html .= '<th>' . date('D - d M, Y', strtotime($d)) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // fetch all needed day_* columns in one shot
            $dayCols    = array_map(fn($d) => 'day_' . date('j', strtotime($d)), $days_in_month);
            $selectCols = implode(',', $dayCols);

            $price_sql = "
        SELECT room_id, room_type_id, price_type, $selectCols
        FROM dvi_hotel_room_price_book
        WHERE hotel_id    = '$hotelID'
          AND year        = '$year'
          AND month       = '$monthText'
          AND price_type IN ('1','2','3')
    ";
            $price_res = sqlQUERY_LABEL($price_sql);
            $price_idx = []; // key: roomId-roomTypeId-priceType
            while ($pr = sqlFETCHARRAY_LABEL($price_res)) {
                $pkey = $pr['room_id'] . '-' . $pr['room_type_id'] . '-' . $pr['price_type'];
                $price_idx[$pkey] = $pr;
            }

            // draw rows in the same order as left
            if (!empty($rooms_master)) {
                foreach ($rooms_master as $rm) {
                    foreach ($priceTypes as $pt) {
                        $key = $rm['room_id'] . '-' . $rm['room_type_id'] . '-' . $pt;
                        $row = $price_idx[$key] ?? null;

                        $html .= '<tr>';
                        foreach ($days_in_month as $d) {
                            $col = 'day_' . date('j', strtotime($d));
                            if ($row && isset($row[$col]) && $row[$col] !== '' && $row[$col] !== null) {
                                $html .= '<td>' . general_currency_symbol . ' ' . number_format((float)$row[$col], 2) . '</td>';
                            } else {
                                $html .= '<td>No Price</td>';
                            }
                        }
                        $html .= '</tr>';
                    }
                }
            } else {
                $html .= '<tr><td colspan="' . count($days_in_month) . '">No room data for ' . htmlspecialchars($monthText) . ' ' . $year . '</td></tr>';
            }

            $prevMonth = $monthYear;
        }

        $html .= '</tbody></table></div></div>';
        // ===================== /EXTRA BED / CHILD RATES =====================

        echo $html;

    endif;
else:
    echo "Request Ignored";
endif;
