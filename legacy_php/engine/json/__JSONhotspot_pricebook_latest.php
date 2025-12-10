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

define('GENERAL_CURRENCY_SYMBOL', '&#8377;'); // HTML Entity for the Rupee symbol

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json'); // Set the header for JSON output

    $hotspot_location = $_GET['hotspot_location'] ?? ''; // Safely get the hotspot location

    $query = "SELECT `hotspot_name`, `hotspot_location`, `hotspot_adult_entry_cost`, `hotspot_child_entry_cost`, `hotspot_infant_entry_cost`, `hotspot_foreign_adult_entry_cost`, `hotspot_foreign_child_entry_cost`, `hotspot_foreign_infant_entry_cost` FROM `dvi_hotspot_place` WHERE `deleted` = '0' AND `status` = '1' AND `hotspot_location` = '$hotspot_location'";

    $select_hotspot_pricebook_query = sqlQUERY_LABEL($query) or die(json_encode(["error" => "#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL()]));

    $results = [];
    $counter = 0;
    while ($row = sqlFETCHARRAY_LABEL($select_hotspot_pricebook_query)) {
        $counter++;
        $results[] = [
            'count' => $counter,
            'hotspot_name' => $row['hotspot_name'],
            'hotspot_location' => $row['hotspot_location'],
            'hotspot_adult_entry_cost' => formatCurrency($row['hotspot_adult_entry_cost']),
            'hotspot_child_entry_cost' => formatCurrency($row['hotspot_child_entry_cost']),
            'hotspot_infant_entry_cost' => formatCurrency($row['hotspot_infant_entry_cost']),
            'hotspot_foreign_adult_entry_cost' => formatCurrency($row['hotspot_foreign_adult_entry_cost']),
            'hotspot_foreign_child_entry_cost' => formatCurrency($row['hotspot_foreign_child_entry_cost']),
            'hotspot_foreign_infant_entry_cost' => formatCurrency($row['hotspot_foreign_infant_entry_cost'])
        ];
    }

    echo json_encode(['data' => $results]);
}

function formatCurrency($amount)
{
    // Return the currency symbol and the amount rounded to the nearest whole number
    return GENERAL_CURRENCY_SYMBOL . number_format((float)($amount ?: 0), 0);
}
