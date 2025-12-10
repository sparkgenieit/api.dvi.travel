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

        $activity_ID = $_POST['activity_ID'];
        $hotspot_ID = $_POST['hotspot_ID'];
        $start_date = dateformat_database($_POST['start_date']);
        $end_date = dateformat_database($_POST['end_date']);

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

        $html .= '<div class="table-container">';

        $html .= '<div class="fixed-columns">
    <table>
        <thead>
            <tr>
                <th>Hotspot</th>
                <th>Nationality</th>
                <th>Price Type</th>
            </tr>
        </thead>
        <tbody>';

        $months_count = 0;
        foreach ($months as $monthYear) :
            $months_count++;
            list($monthName, $year) = explode('-', $monthYear);

            // Convert month name to month number
            $month = date('n', strtotime($monthName));


            $select_combined_data = "SELECT `hotspot_id`, `activity_id`, `nationality`, `price_type` FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_ID' AND `hotspot_id` = '$hotspot_ID' AND `year` = '$year' AND `month` = '$monthName'";

            // Execute combined query
            $result = sqlQUERY_LABEL($select_combined_data);
            $total_no_of_activity_rows = sqlNUMOFROW_LABEL($result);
            $activity = [];

            // Collect room data
            while ($row = sqlFETCHARRAY_LABEL($result)) :
                $activity[] = $row;
            endwhile;

            // Display rooms
            $activity_count = 0;
            foreach ($activity as $index => $row) :
                $activity_count++;
                $hotspot_id = htmlspecialchars($row['hotspot_id']);
                $nationality = htmlspecialchars($row['nationality']);
                $price_type = htmlspecialchars($row['price_type']);
                $get_hotspot = getHOTSPOTDETAILS($hotspot_id, 'label');
                $get_nationality = getNATIONALITY($nationality, 'label');
                $get_price_type = getPRICETYPE($price_type, 'label');
                $html .= '<tr>
            <td class="text-start">' . $get_hotspot . '</td>
            <td class="text-start">' . $get_nationality . '</td>
            <td class="text-start">' . $get_price_type . '</td>
        </tr>';

                // Add a repeating header if needed
                if ($total_no_of_activity_rows == $activity_count && $months_count < count($months)) :
                    $html .= '<tr>
                <th style="border-top-left-radius: 0px;">Hotspot</th>
                <th style="border-top-left-radius: 0px;">Nationality</th>
                <th style="border-top-left-radius: 0px;">Price Type</th>
            </tr>';
                endif;
            endforeach;

            if (empty($activity)) :
                $html .= '<tr><td colspan="3">No data found for <b>' . htmlspecialchars($monthName) . ', ' . htmlspecialchars($year) . '</b></td></tr>';

                // Add a repeating header if needed
                if ($total_no_of_activity_rows == $activity_count && $months_count < count($months)) :
                    $html .= '<tr>
                <th style="border-top-left-radius: 0px;">Hotspot</th>
                <th style="border-top-left-radius: 0px;">Nationality</th>
                <th style="border-top-left-radius: 0px;">Price Type</th>
            </tr>';
                endif;
            endif;
        endforeach;

        $html .= '</tbody></table></div>';

        // Scrollable columns table for prices
        $html .= '<div class="scrollable-columns">
    <table style="width:0%">';

        // Iterate through each month again to create price tables
        $previousMonthYear = ''; // To track if we're processing a new month

        foreach ($months as $monthYear) :
            $year = date('Y', strtotime($monthYear));
            $monthNumeric = date('m', strtotime($monthYear));
            $monthText = date('F', strtotime($monthYear));
            $validDays = getValidDaysForDateRange($start_date, $end_date);

            if (empty($validDays)) continue;

            $check_month_year[$monthYear] = true; // Track the current month

            // If we're processing a new month, close the previous table and start a new one
            if ($monthYear !== $previousMonthYear) :
                if ($previousMonthYear !== '') :
                    $html .= '</tbody></table>'; // Close previous table
                endif;

                // Start a new table for the current month
                $html .= '<table style="width:0%">';
                $headers = '';
                foreach ($validDays as $day) :
                    // Check if the current day belongs to the current month
                    if (date('m', strtotime($day)) == $monthNumeric) :
                        $headers .= '<th>' . date('D - d M, Y', strtotime("$day")) . '</th>';
                    endif;
                endforeach;

                // Add headers for the current month
                $html .= "<thead><tr>$headers</tr></thead><tbody>";
            endif;

            // Create an array of valid columns for the SQL query
            $dayColumns = array_filter(array_map(function ($day) use ($monthNumeric) {
                if (date('m', strtotime($day)) == $monthNumeric) :
                    return "day_" . date('j', strtotime($day));
                endif;
                return null; // Exclude invalid days
            }, $validDays));

            // Check if there are valid day columns before proceeding
            if (!empty($dayColumns)) :
                $select_price_data = "SELECT " . implode(', ', $dayColumns) . " FROM `dvi_activity_pricebook` WHERE `activity_id` = '$activity_ID' AND `hotspot_id` = '$hotspot_ID' AND `year` = '$year' AND `month` = '$monthText'";

                $result = sqlQUERY_LABEL($select_price_data);

                // Add rows with price data
                while ($row = sqlFETCHARRAY_LABEL($result)) :
                    $html .= '<tr>';
                    foreach ($validDays as $day) :
                        if (date('m', strtotime($day)) == $monthNumeric) : // Ensure the day is in the current month
                            $dayColumn = "day_" . date('j', strtotime($day));
                            // Display price or "No Price" if not available
                            $html .= '<td>' . (isset($row[$dayColumn]) && $row[$dayColumn] != '' ? general_currency_symbol . ' ' . number_format($row[$dayColumn], 2) : 'No Price') . '</td>';
                        endif;
                    endforeach;
                    $html .= '</tr>';
                endwhile;

                // If no data is available, show a message
                if (sqlNUMOFROW_LABEL($result) == 0) :
                    $html .= '<tr><td colspan="' . count($validDays) . '">No data available for <b>' . date('F, Y', strtotime("$year-$monthNumeric-01")) . '</b></td></tr>';
                endif;
            endif;

            $previousMonthYear = $monthYear; // Set the current month as the previous one
        endforeach;

        // Close the final table
        $html .= '</tbody></table>';

        echo $html;

    endif;
else:
    echo "Request Ignored";
endif;
