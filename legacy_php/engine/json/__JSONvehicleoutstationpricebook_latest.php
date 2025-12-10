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
    $outstation_pricebook_start_date = $_GET['outstation_pricebook_start_date'];
    $outstation_pricebook_end_date = $_GET['outstation_pricebook_end_date'];

    // Convert start and end dates to timestamps
    $start_timestamp = strtotime($outstation_pricebook_start_date);
    $end_timestamp = strtotime($outstation_pricebook_end_date);

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
    $select_vehicle_outstation_pricebook_query = sqlQUERY_LABEL("SELECT 
        v.vendor_name, vb.vendor_branch_name, vt.vehicle_type_title, vopb.month, vopb.year, 
        'Outstation' AS cost_type, NULL AS time_limit, kl.kms_limit_title AS km_limit, 
        vopb.day_1, vopb.day_2, vopb.day_3, vopb.day_4, vopb.day_5, vopb.day_6, vopb.day_7, 
        vopb.day_8, vopb.day_9, vopb.day_10, vopb.day_11, vopb.day_12, vopb.day_13, vopb.day_14, 
        vopb.day_15, vopb.day_16, vopb.day_17, vopb.day_18, vopb.day_19, vopb.day_20, vopb.day_21, 
        vopb.day_22, vopb.day_23, vopb.day_24, vopb.day_25, vopb.day_26, vopb.day_27, vopb.day_28, 
        vopb.day_29, vopb.day_30, vopb.day_31, vopb.createdon
    FROM dvi_vehicle_outstation_price_book vopb
    LEFT JOIN dvi_vendor_details v ON v.vendor_id = vopb.vendor_id 
    LEFT JOIN dvi_vendor_branches vb ON vb.vendor_branch_id = vopb.vendor_branch_id
    LEFT JOIN dvi_vendor_vehicle_types vvt ON vvt.vendor_vehicle_type_ID = vopb.vehicle_type_id
    LEFT JOIN dvi_vehicle_type vt ON vt.vehicle_type_id = vvt.vehicle_type_id
    LEFT JOIN dvi_kms_limit kl ON kl.kms_limit_id = vopb.kms_limit_id
    WHERE vopb.vendor_id = $vendor_ID AND 
          vopb.vehicle_outstation_price_book_id IS NOT NULL AND 
          vopb.month IN ($months_list) AND 
          vopb.year IN ($years_list)
    ORDER BY vopb.createdon DESC") or die(json_encode(['error' => "#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL()]));

    $datas = [];
    $counter = 0;

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_outstation_pricebook_query)) {
        $counter++;
        $price_book_type = 'Outstation';
        $vehicle_type = $fetch_list_data['vehicle_type_title'];
        $year = $fetch_list_data['year'];
        $month = $fetch_list_data['month'];
        $km_limit = $fetch_list_data['km_limit'];

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
            "km_limit" => $km_limit,
        ], $day_data);
    }

    echo json_encode(['data' => $datas]);
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit(); // Ensure the script stops here
}
