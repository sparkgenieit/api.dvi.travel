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
.scrollable-columns { width: 100%; overflow-x: auto; overflow-y: auto; }
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
        // ===================== MEAL PLAN (Breakfast / Lunch / Dinner) =====================
        $html .= '<h5 class="mt-5 mb-0">Hotel Meal Plan Details</h5><div class="table-container">';

        // Master meal types (always show 3 rows per month to keep rows aligned)
        $marginTypes = [
            1 => 'Hotel Margin',
            2 => 'Margin GST Type',
            3 => 'Margin GST Percentage',
        ];

        // ---------- LEFT: fixed column (repeat for EACH month) ----------
        $html .= '<div class="fixed-columns">
    <table>
        <thead>
            <tr>
                <th>Margin Details</th>
            </tr>
        </thead>
        <tbody>';

        $monthIndex = 0;
        foreach ($months as $monthYear) {
            // Repeat the header row between months so height aligns with right-side month header
            if ($monthIndex > 0) {
                $html .= '<tr><th style="border-top-left-radius:0">Margin Details</th></tr>';
            }
            foreach ($marginTypes as $mtId => $mtTitle) {
                $html .= '<tr><td class="text-start">' . htmlspecialchars($mtTitle) . '</td></tr>';
            }
            $monthIndex++;
        }
        $html .= '</tbody></table></div>';

        // ---------- RIGHT: scrollable (one table per month; columns = days; rows = magin details in same order) ----------
        $html .= '<div class="scrollable-columns"><table style="width:0%">';
        $previousMonthYear = '';

        foreach ($months as $monthYear) {
            $year         = date('Y', strtotime($monthYear));
            $monthNumeric = date('m', strtotime($monthYear));
            $monthText    = date('F', strtotime($monthYear));

            // Limit days to the overlap of selected range and this month
            $monthStart = max($start_date, date('Y-m-01', strtotime("$year-$monthNumeric-01")));
            $monthEnd   = min($end_date,   date('Y-m-t', strtotime("$year-$monthNumeric-01")));

            // Collect days of this month in the selected range
            $days_in_month = [];
            foreach (getValidDaysForDateRange($start_date, $end_date) as $day) {
                if (date('m', strtotime($day)) == $monthNumeric && date('Y', strtotime($day)) == $year) {
                    $days_in_month[] = $day;
                }
            }
            if (empty($days_in_month)) continue;

            // Close previous month table, open a new one
            if ($previousMonthYear !== '') {
                $html .= '</tbody></table>';
            }
            $html .= '<table style="width:0%"><thead><tr>';
            foreach ($days_in_month as $d) {
                $html .= '<th>' . date('D - d M, Y', strtotime($d)) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // Build SELECT of all needed day_* columns
            $dayColsArr = array_map(fn($d) => 'day_' . date('j', strtotime($d)), $days_in_month);
            $selectCols = implode(',', $dayColsArr);

            // Fetch all meal rows for the month at once; index by type
            $price_sql = "SELECT type, $selectCols FROM `dvi_hotel_margin_price_book` WHERE hotel_id = '$hotelID' AND `year` = '$year' AND `month` = '$monthText' AND `type` IN ('1','2','3')";
            $price_res = sqlQUERY_LABEL($price_sql);
            $price_idx = []; // key = type
            while ($pr = sqlFETCHARRAY_LABEL($price_res)) {
                $price_idx[(int)$pr['type']] = $pr;
            }

            // Render rows in EXACT same order as the left side
            foreach ($marginTypes as $mtId => $mtTitle) {
                $row = $price_idx[$mtId] ?? null;

                $html .= '<tr>';
                foreach ($days_in_month as $d) {
                    $col = 'day_' . date('j', strtotime($d));
                    if ($mtId == 1):
                        $return_data = ((float)$row[$col]) . '%';
                    elseif ($mtId == 2):
                        $return_data = getGSTTYPE((float)$row[$col], 'label');
                    elseif ($mtId == 3):
                        $return_data = ((float)$row[$col]) . '%';
                    endif;
                    if ($row && isset($row[$col]) && $row[$col] !== '' && $row[$col] !== null) {
                        $html .= '<td>' . $return_data . '</td>';
                    } else {
                        $html .= '<td>No data</td>';
                    }
                }
                $html .= '</tr>';
            }

            $previousMonthYear = $monthYear;
        }

        // Close last table and container
        $html .= '</tbody></table></div></div>';
        // ===================== /MEAL PLAN =====================

        echo $html;

    endif;
else:
    echo "Request Ignored";
endif;
