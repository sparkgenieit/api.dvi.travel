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

    // Get hotel category IDs as array
    $hotel_category_ids = isset($_POST['hotel_category_ids']) ? $_POST['hotel_category_ids'] : [];
    if (!is_array($hotel_category_ids)) {
        $hotel_category_ids = [$hotel_category_ids];
    }

    // Validate and sanitize IDs
    $hotel_category_ids = array_filter(array_map('intval', $hotel_category_ids));
    sort($hotel_category_ids, SORT_NUMERIC);

    if (empty($hotel_category_ids)) {
        echo json_encode([]);
        exit;
    }

    // CACHE SECTION
    $cache_key = 'facilities_' . md5(implode('_', $hotel_category_ids)) . '.json';
    $cache_path = sys_get_temp_dir() . '/' . $cache_key;
    $cache_ttl = 300; // cache 5 minutes

    if (file_exists($cache_path) && (time() - filemtime($cache_path)) < $cache_ttl) {
        header('Content-Type: application/json');
        echo file_get_contents($cache_path);
        exit;
    }

    // Fetch facilities from dvi_hotel
    $ids_in = implode(',', $hotel_category_ids);
    $query = "SELECT hotel_id, hotel_facilities FROM dvi_hotel WHERE hotel_category IN ($ids_in) AND hotel_facilities IS NOT NULL AND hotel_facilities != ''";
    $result = sqlQUERY_LABEL($query);

    $all_facilities = [];

    while ($row = sqlFETCHARRAY_LABEL($result)) {
        $hotel_id = $row['hotel_id'];
        $facilities = json_decode($row['hotel_facilities'], true);

        if (is_array($facilities)) {
            foreach ($facilities as $facility) {
                $facility = trim($facility);
                if ($facility !== '') {
                    $all_facilities[$facility] = true; // deduplicate
                }
            }
        }
    }

    // Unique facilities list
    $unique_facilities = array_keys($all_facilities);
    sort($unique_facilities, SORT_NATURAL | SORT_FLAG_CASE);

    // Prepare for Selectize: [{value: ..., text: ...}, ...]
    $options = [];
    foreach ($unique_facilities as $facility) {
        $options[] = [
            "value" => $facility,
            "text"  => $facility
        ];
    }

    // Save to cache
    file_put_contents($cache_path, json_encode($options));

    header('Content-Type: application/json');
    echo json_encode($options);
    exit;

else :
    echo "Request Ignored !!!";
endif;
