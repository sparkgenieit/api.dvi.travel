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

        // ===================== AMENITIES (Day / Hour) — aligned block =====================
        $html .= '<h5 class="mt-5 mb-0">Hotel Amenities Price Details</h5><div class="table-container">';

        // 0) Helpers
        $priceTypeText = function ($pt) {
            return ($pt == '1' ? 'Day' : 'Hour');
        };
        $allowedTypes  = ['1', '2']; // 1 = Day, 2 = Hour

        // 1) Build a MASTER list of (amenity, pricetype) combos across all selected months
        $rows_master = []; // key: amenityId-pricetype
        foreach ($months as $monthYear) {
            list($monthName, $year) = explode('-', $monthYear);
            $sql = "
        SELECT DISTINCT
            PB.hotel_amenities_id,
            AM.amenities_title,
            PB.pricetype
        FROM dvi_hotel_amenities_price_book PB
        INNER JOIN dvi_hotel_amenities AM
            ON PB.hotel_amenities_id = AM.hotel_amenities_id
        WHERE PB.hotel_id  = '$hotelID'
          AND PB.year      = '$year'
          AND PB.month     = '$monthName'
          AND PB.pricetype IN ('1','2')
        ORDER BY PB.hotel_amenities_id ASC, PB.pricetype ASC
    ";
            $res = sqlQUERY_LABEL($sql);
            while ($r = sqlFETCHARRAY_LABEL($res)) {
                $key = $r['hotel_amenities_id'] . '-' . $r['pricetype'];
                if (!isset($rows_master[$key])) {
                    $rows_master[$key] = [
                        'amenities_id'    => (int)$r['hotel_amenities_id'],
                        'amenities_title' => $r['amenities_title'],
                        'pricetype'       => (string)$r['pricetype'],
                    ];
                }
            }
        }

        // 2) LEFT: fixed columns — repeat for EVERY month so heights match the right side
        $html .= '<div class="fixed-columns"><table>
    <thead>
        <tr>
            <th>Amenities Name</th>
            <th>Price Type</th>
        </tr>
    </thead>
    <tbody>';

        $monthIndex = 0;
        foreach ($months as $monthYear) {
            if ($monthIndex > 0) {
                // repeat header row between month blocks for neat visual alignment
                $html .= '<tr>
            <th style="border-top-left-radius:0">Amenities Name</th>
            <th>Price Type</th>
        </tr>';
            }
            if (!empty($rows_master)) {
                foreach ($rows_master as $rm) {
                    $html .= '<tr>
                <td class="text-start">' . htmlspecialchars($rm['amenities_title']) . '</td>
                <td class="text-start">' . htmlspecialchars($priceTypeText($rm['pricetype'])) . '</td>
            </tr>';
                }
            } else {
                $html .= '<tr><td colspan="2">No data found</td></tr>';
            }
            $monthIndex++;
        }
        $html .= '</tbody></table></div>';

        // 3) RIGHT: scrollable — one table per month; columns = days; rows follow rows_master order
        $html .= '<div class="scrollable-columns">';

        foreach ($months as $monthYear) {
            $year         = date('Y', strtotime($monthYear));
            $monthNumeric = date('m', strtotime($monthYear));
            $monthText    = date('F', strtotime($monthYear));

            // Days limited to overlap of selected range and this month
            $monthStart = max($start_date, date('Y-m-01', strtotime("$year-$monthNumeric-01")));
            $monthEnd   = min($end_date,   date('Y-m-t', strtotime("$year-$monthNumeric-01")));
            $days_in_month = getValidDaysForDateRange($monthStart, $monthEnd);
            if (empty($days_in_month)) {
                continue;
            }

            // Month table
            $html .= '<table style="width:0%"><thead><tr>';
            foreach ($days_in_month as $d) {
                $html .= '<th>' . date('D - d M, Y', strtotime($d)) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // Fetch all amenity rows for this month; index by amenityId-pricetype
            $dayColsArr = array_map(fn($d) => 'day_' . date('j', strtotime($d)), $days_in_month);
            $selectCols = implode(',', $dayColsArr);

            $price_sql = "
        SELECT hotel_amenities_id, pricetype, $selectCols
        FROM dvi_hotel_amenities_price_book
        WHERE hotel_id  = '$hotelID'
          AND year      = '$year'
          AND month     = '$monthText'
          AND pricetype IN ('1','2')
    ";
            $price_res = sqlQUERY_LABEL($price_sql);
            $price_idx = []; // key: amenityId-pricetype
            while ($pr = sqlFETCHARRAY_LABEL($price_res)) {
                $pkey = $pr['hotel_amenities_id'] . '-' . $pr['pricetype'];
                $price_idx[$pkey] = $pr;
            }

            // Render rows in EXACT same order as left side
            if (!empty($rows_master)) {
                foreach ($rows_master as $rm) {
                    $key = $rm['amenities_id'] . '-' . $rm['pricetype'];
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
            } else {
                $html .= '<tr><td colspan="' . count($days_in_month) . '">No amenities for ' . htmlspecialchars($monthText) . ' ' . $year . '</td></tr>';
            }

            $html .= '</tbody></table>';
        }

        $html .= '</div></div>'; // end scrollable + container
        // ===================== /AMENITIES =====================

        echo $html;

    endif;
else:
    echo "Request Ignored";
endif;
