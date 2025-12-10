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

        $vendor_ID = $_POST['vendor_ID'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Get all months between start date and end date
        $months = getMonthsBetweenDates($start_date, $end_date);
        $validDays = getValidDaysForDateRange($start_date, $end_date);

        // 1. Build unique vehicle types and km limits for all selected months (with IDs for perfect row match)
        $vehicle_types = [];
        foreach ($months as $monthYear) {
            list($monthName, $year) = explode('-', $monthYear);
            $query = "
                SELECT 
                    VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID, 
                    VEHICLE_TYPE.vehicle_type_title, 
                    KMS_LIMIT.kms_limit_title, 
                    PRICE_BOOK.kms_limit_id
                FROM dvi_vehicle_outstation_price_book PRICE_BOOK
                INNER JOIN dvi_vendor_vehicle_types VENDOR_VEHICLE_TYPE 
                    ON VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID = PRICE_BOOK.vehicle_type_id
                    AND PRICE_BOOK.vendor_id = VENDOR_VEHICLE_TYPE.vendor_id
                INNER JOIN dvi_vehicle_type VEHICLE_TYPE 
                    ON VEHICLE_TYPE.vehicle_type_id = VENDOR_VEHICLE_TYPE.vehicle_type_id
                LEFT JOIN dvi_kms_limit KMS_LIMIT 
                    ON KMS_LIMIT.kms_limit_id = PRICE_BOOK.kms_limit_id
                WHERE PRICE_BOOK.vendor_id = '$vendor_ID'
                AND PRICE_BOOK.year = '$year'
                AND PRICE_BOOK.month = '$monthName'
                GROUP BY VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID, PRICE_BOOK.kms_limit_id
                ORDER BY VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID ASC
            ";
            $result = sqlQUERY_LABEL($query);
            while ($vrow = sqlFETCHARRAY_LABEL($result)) {
                $key = $vrow['vendor_vehicle_type_ID'] . '-' . $vrow['kms_limit_id'];
                if (!isset($vehicle_types[$key])) {
                    $vehicle_types[$key] = [
                        'vehicle_type_title' => $vrow['vehicle_type_title'],
                        'kms_limit_title'    => $vrow['kms_limit_title'],
                        'vendor_vehicle_type_ID' => $vrow['vendor_vehicle_type_ID'],
                        'kms_limit_id'       => $vrow['kms_limit_id']
                    ];
                }
            }
        }

        // 2. Prepare HTML & CSS (no UI changes)
        $html = <<<HTML
<style>
body, html { margin: 0; padding: 0; font-family: Arial, sans-serif; }
.table-container { display: flex; width: 100vw; height: auto; }
.scrollable-columns { width: 80%; overflow-x: auto; overflow-y: auto; }
table { width: 0%; border-collapse: collapse; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
th, td { padding: 4px; text-align: center; font-size: 0.9vw; white-space: nowrap; }
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

        // 3. LEFT: Vehicle Type + KM Limit Table
        $html .= '<div class="fixed-columns"><table><thead><tr>
                <th>Vehicle Type</th>
                <th>KM Limit</th>
            </tr></thead><tbody>';
        foreach ($vehicle_types as $vrow) {
            $html .= '<tr>
                <td class="text-start">' . ucfirst(htmlspecialchars($vrow['vehicle_type_title'])) . '</td>
                <td class="text-start">' . htmlspecialchars($vrow['kms_limit_title']) . '</td>
            </tr>';
        }
        if (empty($vehicle_types)) {
            $html .= '<tr><td colspan="2">No data found</td></tr>';
        }
        $html .= '</tbody></table></div>';

        // 4. RIGHT: Scrollable Price Table (rows = vehicle_types, columns = days in months)
        $html .= '<div class="scrollable-columns"><table style="width:0%">';
        foreach ($months as $monthYear) {
            $year = date('Y', strtotime($monthYear));
            $monthNumeric = date('m', strtotime($monthYear));
            $monthText = date('F', strtotime($monthYear));
            $days_in_month = [];
            foreach ($validDays as $day) {
                if (date('m', strtotime($day)) == $monthNumeric) {
                    $days_in_month[] = $day;
                }
            }
            if (empty($days_in_month)) continue;

            // Header for the month
            $html .= "<thead><tr>";
            foreach ($days_in_month as $day) {
                $html .= '<th>' . date('D - d M, Y', strtotime($day)) . '</th>';
            }
            $html .= "</tr></thead><tbody>";

            // For each vehicle type row, output prices for each day
            foreach ($vehicle_types as $vrow) {
                $vendor_vehicle_type_ID = $vrow['vendor_vehicle_type_ID'];
                $kms_limit_id = $vrow['kms_limit_id'];
                $html .= '<tr>';
                foreach ($days_in_month as $day) {
                    $day_col = "day_" . date('j', strtotime($day));
                    // Query for this cell's price
                    $price_query = "
                        SELECT `$day_col`
                        FROM dvi_vehicle_outstation_price_book
                        WHERE vendor_id = '$vendor_ID'
                          AND year = '$year'
                          AND month = '$monthText'
                          AND vehicle_type_id = '$vendor_vehicle_type_ID'
                          AND kms_limit_id = '$kms_limit_id'
                        LIMIT 1
                    ";
                    $result = sqlQUERY_LABEL($price_query);
                    $row = sqlFETCHARRAY_LABEL($result);
                    $price = (isset($row[$day_col]) && $row[$day_col] !== '' && $row[$day_col] !== null)
                        ? general_currency_symbol . ' ' . number_format($row[$day_col], 2)
                        : 'No Price';
                    $html .= '<td>' . $price . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        }
        $html .= '</table></div>';

        // 5. END TABLE
        $html .= '</div>';

        echo $html;

    endif;
else:
    echo "Request Ignored";
endif;
