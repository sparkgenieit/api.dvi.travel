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

    $vendor_ID = $_GET['vendor_ID'];
    $local_pricebook_start_date = $_GET['local_pricebook_start_date'];
    $local_pricebook_end_date = $_GET['local_pricebook_end_date'];

    // Convert start and end dates to timestamps
    $start_timestamp = strtotime($local_pricebook_start_date);
    $end_timestamp = strtotime($local_pricebook_end_date);

    // Initialize arrays to hold the months and years
    $months = [];
    $years = [];

    // Loop through each month between the start and end dates
    while ($start_timestamp <= $end_timestamp) {
        $months[] = date('F', $start_timestamp);  // Get full month name (e.g., "June")
        $years[] = date('Y', $start_timestamp);   // Get the year (e.g., "2024")

        // Move to the next month
        $start_timestamp = strtotime('+1 month', $start_timestamp);
    }

    // Remove duplicates and prepare strings for SQL query
    $months_list = "'" . implode("', '", array_unique($months)) . "'";
    $years_list = implode(", ", array_unique($years));

    // Ensure joins only include relevant records to avoid duplicates
    $select_vehicle_local_pricebook_query = sqlQUERY_LABEL("
         SELECT 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vlpb.month, vlpb.year, 
        'Local' AS cost_type, tl.time_limit_title AS time_limit, NULL AS km_limit, 
        vlpb.day_1, vlpb.day_2, vlpb.day_3, vlpb.day_4, vlpb.day_5, vlpb.day_6, vlpb.day_7, 
        vlpb.day_8, vlpb.day_9, vlpb.day_10, vlpb.day_11, vlpb.day_12, vlpb.day_13, vlpb.day_14, 
        vlpb.day_15, vlpb.day_16, vlpb.day_17, vlpb.day_18, vlpb.day_19, vlpb.day_20, vlpb.day_21, 
        vlpb.day_22, vlpb.day_23, vlpb.day_24, vlpb.day_25, vlpb.day_26, vlpb.day_27, vlpb.day_28, 
        vlpb.day_29, vlpb.day_30, vlpb.day_31
    FROM dvi_vehicle_local_pricebook vlpb
    LEFT JOIN dvi_vendor_details v ON v.vendor_id = vlpb.vendor_id 
    LEFT JOIN dvi_vendor_branches vb ON vb.vendor_branch_id = vlpb.vendor_branch_id
    LEFT JOIN dvi_vendor_vehicle_types vvt ON vvt.vendor_vehicle_type_ID = vlpb.vehicle_type_id
    LEFT JOIN dvi_vehicle_type vt ON vt.vehicle_type_id = vvt.vehicle_type_id
    LEFT JOIN dvi_time_limit tl ON tl.time_limit_id = vlpb.time_limit_id
    WHERE vlpb.vendor_id = $vendor_ID AND vlpb.vehicle_price_book_id IS NOT NULL AND vlpb.month IN ($months_list) AND vlpb.year IN ($years_list)
    ORDER BY v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title 
    ") or die(json_encode(['error' => "#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL()]));

    $datas = [];
    $counter = 0;

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_local_pricebook_query)) {
        $counter++;
        $price_book_type = 'Local';
        $vehicle_type = $fetch_list_data['vehicle_type_title'];
        $year = $fetch_list_data['year'];
        $month = $fetch_list_data['month'];
        $time_limit = $fetch_list_data['time_limit'];

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
            "price_book_type" => $price_book_type,
            "vehicle_type" => $vehicle_type,
            "month" => $month,
            "year" => $year,
            "time_limit" => $time_limit,
        ], $day_data);
    }

    echo json_encode(['data' => $datas]);
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit(); // Ensure the script stops here
}
