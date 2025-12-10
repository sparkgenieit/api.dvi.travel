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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    echo "{";
    echo '"data":[';

    $hotel_id = $_GET['hotel_id'];
    $amenities_start_date = $_GET['amenities_start_date'];
    $amenities_end_date = $_GET['amenities_end_date'];

    // Convert start and end dates to timestamps
    $start_timestamp = strtotime($amenities_start_date);
    $end_timestamp = strtotime($amenities_end_date);

    // Initialize arrays to hold the months and years
    $months = [];
    $years = [];

    // Loop through each month between the start and end dates
    while (
        $start_timestamp <= $end_timestamp
    ) {
        $months[] = date('F', $start_timestamp);  // Get full month name (e.g., "June")
        $years[] = date(
            'Y',
            $start_timestamp
        );   // Get the year (e.g., "2024")

        // Move to the next month
        $start_timestamp = strtotime('+1 month', $start_timestamp);
    }

    // Remove duplicates and prepare strings for SQL query
    $months_list = "'" . implode("', '", array_unique($months)) . "'";
    $years_list = implode(", ", array_unique($years));


    $select_hotel_room_pricebook_query = sqlQUERY_LABEL("SELECT 
    h.hotel_id,
    h.hotel_name,
    COALESCE(ha.hotel_amenities_id, 0) AS amenity_id,
    COALESCE(ha.amenities_title, '-') AS amenity_name,  -- Changed from 'No Amenities' to '-'
    COALESCE(hapb.hotel_amenities_price_book_id, 0) AS price_book_id,
    COALESCE(hapb.pricetype, '-') AS pricetype,         -- Changed from 'No Price Type' to '-'
    COALESCE(hapb.year, '$year') AS year,               -- Showing selected year instead of 'No Year'
    COALESCE(hapb.month, '$month') AS month,            -- Showing selected month instead of 'No Month'
    COALESCE(hapb.day_1, 0) AS day_1,
    COALESCE(hapb.day_2, 0) AS day_2,
    COALESCE(hapb.day_3, 0) AS day_3,
    COALESCE(hapb.day_4, 0) AS day_4,
    COALESCE(hapb.day_5, 0) AS day_5,
    COALESCE(hapb.day_6, 0) AS day_6,
    COALESCE(hapb.day_7, 0) AS day_7,
    COALESCE(hapb.day_8, 0) AS day_8,
    COALESCE(hapb.day_9, 0) AS day_9,
    COALESCE(hapb.day_10, 0) AS day_10,
    COALESCE(hapb.day_11, 0) AS day_11,
    COALESCE(hapb.day_12, 0) AS day_12,
    COALESCE(hapb.day_13, 0) AS day_13,
    COALESCE(hapb.day_14, 0) AS day_14,
    COALESCE(hapb.day_15, 0) AS day_15,
    COALESCE(hapb.day_16, 0) AS day_16,
    COALESCE(hapb.day_17, 0) AS day_17,
    COALESCE(hapb.day_18, 0) AS day_18,
    COALESCE(hapb.day_19, 0) AS day_19,
    COALESCE(hapb.day_20, 0) AS day_20,
    COALESCE(hapb.day_21, 0) AS day_21,
    COALESCE(hapb.day_22, 0) AS day_22,
    COALESCE(hapb.day_23, 0) AS day_23,
    COALESCE(hapb.day_24, 0) AS day_24,
    COALESCE(hapb.day_25, 0) AS day_25,
    COALESCE(hapb.day_26, 0) AS day_26,
    COALESCE(hapb.day_27, 0) AS day_27,
    COALESCE(hapb.day_28, 0) AS day_28,
    COALESCE(hapb.day_29, 0) AS day_29,
    COALESCE(hapb.day_30, 0) AS day_30,
    COALESCE(hapb.day_31, 0) AS day_31
FROM 
    dvi_hotel h
LEFT JOIN 
    dvi_hotel_amenities ha ON ha.hotel_id = h.hotel_id
    AND ha.deleted = '0'
    AND ha.status = '1'
LEFT JOIN 
    dvi_hotel_amenities_price_book hapb ON ha.hotel_amenities_id = hapb.hotel_amenities_id
    AND hapb.deleted = '0' 
    AND hapb.status = '1'
WHERE 
    h.deleted = '0' 
    AND h.status = '1'  
    AND hapb.hotel_id = '$hotel_id'  
    AND hapb.month IN ($months_list) AND hapb.year IN ($years_list)") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());




    $datas = '';
    $counter = 0;

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_room_pricebook_query)) :
        $counter++;
        // $hotel_name = $fetch_list_data['hotel_name'];
        $amenity_name = $fetch_list_data['amenity_name'] ? $fetch_list_data['amenity_name'] : 'N/A';
        $pricetype = $fetch_list_data['pricetype'];
        $year = $fetch_list_data['year'] ? $fetch_list_data['year'] : $year;
        $month = $fetch_list_data['month'] ? $fetch_list_data['month'] : $month;

        // Translate pricetype
        $pricetype_label = ($pricetype == 1) ? 'Day' : (($pricetype == 2) ? 'Hour' : 'N/A');

        $day_data = [];
        // for ($day_count = 1; $day_count <= 31; $day_count++) :
        //     $day_variable = 'day_' . $day_count;
        //     $day_data[$day_variable] = $fetch_list_data[$day_variable] ? $fetch_list_data[$day_variable] : 0;
        // endfor;

        for ($day_count = 1; $day_count <= 31; $day_count++) {
            $day_variable = 'day_' . $day_count;
            $day_price = $fetch_list_data[$day_variable];
            $day_data[$day_variable] = $day_price > 0 ? general_currency_symbol . ' ' . number_format(
                $day_price,
                0
            ) : general_currency_symbol  . number_format(0, 0);  // Format for better readability
        }


        $datas .= json_encode(array_merge([
            'count' => $counter,
            // 'hotel_name' => $hotel_name,
            'amenities_title' => $amenity_name,
            'pricetype' => $pricetype_label,
            'year' => $year,
            'month' => $month
        ], $day_data)) . ',';

    endwhile; //end of while loop

    $data_formatted = rtrim($datas, ',');
    echo $data_formatted;
    echo "]}";

endif;
