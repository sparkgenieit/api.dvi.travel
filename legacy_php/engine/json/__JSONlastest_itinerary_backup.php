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

    $limit = isset($_GET['length']) ? (int)$_GET['length'] : 10; // Number of records per page
    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0; // Starting record
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 0;

    try {
        // Get the total number of records
        $totalRecordsQuery = "SELECT COUNT(*) AS total FROM dvi_itinerary_plan_details WHERE deleted = '0'";
        $totalRecordsResult = sqlQUERY_LABEL($totalRecordsQuery) or die("#1-UNABLE_TO_COUNT_TOTAL_RECORDS:" . sqlERROR_LABEL());
        $totalRecords = sqlFETCHARRAY_LABEL($totalRecordsResult)['total'];

        // Get the paginated records
        $select_ITINERARYLIST_query = sqlQUERY_LABEL("CALL GetLatestItineraryPlans($start, $limit)") or die("#2-UNABLE_TO_COLLECT_ITINERARY_LIST:" . sqlERROR_LABEL());

        $datas = [];
        $counter = $start;

        // Fetch data and build the JSON response
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_ITINERARYLIST_query)) {
            $counter++;
            $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
            $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
            $arrival_location = $fetch_list_data['arrival_location'];
            $departure_location = $fetch_list_data['departure_location'];
            $no_of_days = $fetch_list_data['no_of_days'];
            $no_of_nights = $fetch_list_data['no_of_nights'];
            $no_of_routes = $fetch_list_data['no_of_routes'];
            $expecting_budget = $fetch_list_data['expecting_budget'];
            $trip_start_date_and_time = $fetch_list_data["trip_start_date_and_time"];
            $trip_end_date_and_time = $fetch_list_data["trip_end_date_and_time"];
            $total_adult = $fetch_list_data["total_adult"];
            $total_children = $fetch_list_data["total_children"];
            $total_infants = $fetch_list_data["total_infants"];
            $total_members = "<span>Adult - $total_adult</br>Children - $total_children</br>Infants - $total_infants</span>";

            $datas[] = [
                'counter' => $counter,
                'modify' => $itinerary_plan_ID,
                'itinerary_quote_ID' => $itinerary_quote_ID,
                'arrival_location' => $arrival_location,
                'departure_location' => $departure_location,
                'no_of_days_and_nights' => $no_of_nights . '&' . $no_of_days,
                'no_of_person' => $total_members,
                'trip_start_date_and_time' => date('d/m/Y h:i A', strtotime($trip_start_date_and_time)),
                'trip_end_date_and_time' => date('d/m/Y h:i A', strtotime($trip_end_date_and_time)),
                'total_adult' => $total_adult,
                'total_children' => $total_children,
                'total_infants' => $total_infants
            ];
        }

        // Free the result set to avoid "commands out of sync" error
        sqlFREE_RESULT($select_ITINERARYLIST_query);

        $response = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $datas
        ];

        echo json_encode($response);
    } catch (Exception $e) {
        $response = [
            "draw" => $draw,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => $e->getMessage()
        ];
        echo json_encode($response);
    }

else :
    echo "Request Ignored !!!";
endif;
