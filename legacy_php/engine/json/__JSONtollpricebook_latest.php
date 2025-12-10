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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    $vehicle_type_id = $_GET['vehicle_type']; // Ensure this variable is received securely and sanitized


    $query = sqlQUERY_LABEL("SELECT
        vtc.vehicle_toll_charge_ID,
        vtc.location_id,
        vtc.vehicle_type_id,
        vtc.toll_charge,
        sl.source_location,
        sl.destination_location
    FROM
        dvi_vehicle_toll_charges AS vtc
    JOIN
        dvi_stored_locations AS sl ON vtc.location_id = sl.location_ID
    WHERE
        vtc.vehicle_type_id = '$vehicle_type_id' AND
        vtc.deleted = 0 AND
        vtc.status = 1 AND
        sl.deleted = 0 AND
        sl.status = 1") or die(json_encode(['error' => "SQL Error: " . sqlERROR_LABEL()]));

    $results = "";
    $counter = 0;

    echo "{";
    echo '"data":[';

    while ($row = sqlFETCHARRAY_LABEL($query)) {
        $counter++;
        $vehicle_type_name = totalkms($row['vehicle_type_id'], 'vehicle_type'); // Assuming the function is used to fetch vehicle type name
        // Format the toll charge with currency symbol and no decimals
        $formatted_toll_charge = general_currency_symbol . ' ' . number_format($row['toll_charge'], 0, '.', '');

        $results .= "{";
        $results .= '"count": "' . $counter . '",';
        $results .= '"toll_charge_id": "' . $row['vehicle_toll_charge_ID'] . '",';
        $results .= '"location_id": "' . $row['location_id'] . '",';
        $results .= '"vehicle_type_id": "' . $row['vehicle_type_id'] . '",';
        $results .= '"toll_charge": "' . $formatted_toll_charge . '",';
        $results .= '"source_location": "' . $row['source_location'] . '",';
        $results .= '"destination_location": "' . $row['destination_location'] . '",';
        $results .= '"vehicle_type_name": "' . $vehicle_type_name . '"';
        $results .= "},";
    }

    // Remove the last comma and close the JSON array and object
    $results = rtrim($results, ',');
    echo $results;
    echo "]}";
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit();
}