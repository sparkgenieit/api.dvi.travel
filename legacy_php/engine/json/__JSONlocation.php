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

    $source_location = isset($_GET['source_location']) ? $_GET['source_location'] : '';
    $destination_location = isset($_GET['destination_location']) ? $_GET['destination_location'] : '';
    $limit = isset($_GET['length']) ? (int)$_GET['length'] : 10; // Number of records per page
    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0; // Starting record
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 0;

    $source_location = htmlentities($source_location);
    $destination_location = htmlentities($destination_location);

    // Prepare the SQL query
    if ($source_location && $destination_location) :
        $select_LOCATIONLIST_query = sqlQUERY_LABEL("CALL GetLocations('$source_location', '$destination_location', $start, $limit)") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    else :
        $select_LOCATIONLIST_query = sqlQUERY_LABEL("CALL GetLocations('$source_location', '', $start, $limit)") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    endif;

    $datas = [];
    $counter = $start;

    // Fetch data and build the JSON response
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_LOCATIONLIST_query)) :
        $counter++;
        $location_ID = $fetch_list_data['location_ID'];
        $source_location = $fetch_list_data['source_location'];
        $destination_location = $fetch_list_data['destination_location'];
        $distance = $fetch_list_data['distance'];
        $duration = $fetch_list_data['duration'];
        $status = $fetch_list_data['status'];

        $datas[] = [
            'count' => $counter,
            'source_location' => $source_location,
            'destination_location' => $destination_location,
            'distance' => $distance,
            'duration' => $duration,
            'status' => $status,
            'modify' => $location_ID
        ];
    endwhile;

    // Free the result set from the stored procedure to avoid "Commands out of sync" error
    sqlFREE_RESULT($select_LOCATIONLIST_query);

    // Consume any remaining result sets from the stored procedure
    while (sqlMORE_RESULT($GLOBALS['conn']) && sqlNEXT_RESULT($GLOBALS['conn'])) {
        // Nothing to do here, just move to the next result
    }

    // Get the total records count for the query
    $stmtTotal = sqlQUERY_LABEL("SELECT COUNT(`source_location`) as TOTAL_LOCATIONS FROM `dvi_stored_locations` WHERE `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_TOTAL_COUNT:" . sqlERROR_LABEL());
    $totalResult = sqlFETCHARRAY_LABEL($stmtTotal);
    $totalRecords = $totalResult['TOTAL_LOCATIONS'];

    // Prepare the JSON response
    $response = [
        "draw" => $draw,
        "recordsTotal" => $totalRecords,
        "recordsFiltered" => $totalRecords,
        "data" => $datas
    ];

    echo json_encode($response);
else :
    echo "Request Ignored !!!";
endif;
