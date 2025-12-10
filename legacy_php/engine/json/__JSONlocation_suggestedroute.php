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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) :

    header('Content-Type: application/json; charset=utf-8');

    // sanitize
    $location_ID = isset($_GET['location_ID']) ? (int)$_GET['location_ID'] : 0;

    // NOTE: filter on l.route_location_id (detail table), not r.location_id
    $sql = "SELECT r.stored_route_ID, r.route_name, r.no_of_nights, l.route_location_name 
            FROM dvi_stored_routes r
            LEFT JOIN dvi_stored_route_location_details l 
                ON r.stored_route_ID = l.stored_route_id
            WHERE r.deleted = 0 
            AND l.deleted = 0 
            AND r.location_id = '$location_ID'
            ORDER BY r.stored_route_ID, l.stored_route_location_ID";

    $rs = sqlQUERY_LABEL($sql) or die('#1-UNABLE_TO_COLLECT_ROUTE_LIST:' . sqlERROR_LABEL());
    $rows = [];
    $counter = 0;
    $currentRoute = null;
    $routeDetails = [];

    while ($r = sqlFETCHARRAY_LABEL($rs)) {
        // If new route starts, save the old one
        if ($currentRoute !== null && $currentRoute != $r['stored_route_ID']) {
            $counter++;
            $rows[] = [
                'count'         => (string)$counter,
                'routes'        => $routeName,
                'no_of_nights'        => $no_of_nights,
                'route_details' => formatLocations($routeDetails),
                'modify'        => (string)$currentRoute
            ];
            $routeDetails = [];
        }

        $currentRoute = $r['stored_route_ID'];
        $routeName = $r['route_name'];
        $no_of_nights = $r['no_of_nights'];
        $routeDetails[] = $r['route_location_name'];  // now safe, because no GROUP_CONCAT
    }

    // save the last route
    if ($currentRoute !== null) {
        $counter++;
        $rows[] = [
            'count'         => (string)$counter,
            'routes'        => $routeName,
            'no_of_nights'        => $no_of_nights,
            'route_details' => formatLocations($routeDetails),
            'modify'        => (string)$currentRoute
        ];
    }

    echo json_encode(['data' => $rows], JSON_UNESCAPED_UNICODE);

/* else :
    echo 'Request Ignored !!!';
endif; */