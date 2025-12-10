<?php
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    $source_location = isset($_GET['source_location']) ? $_GET['source_location'] : '';
    $destination_location = isset($_GET['destination_location']) ? $_GET['destination_location'] : '';
    $source_location = htmlentities($source_location);
    $destination_location = htmlentities($destination_location);

    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    $start_date_format = !empty($start_date) ? dateformat_database($start_date) : NULL;
    $end_date_format = !empty($end_date) ? dateformat_database($end_date) : NULL;

    $limit = isset($_GET['length']) ? (int)$_GET['length'] : 10; // Number of records per page
    $start = isset($_GET['start']) ? (int)$_GET['start'] : 0; // Starting record
    $draw = isset($_GET['draw']) ? intval($_GET['draw']) : 0;

    $searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : ''; // Search value

    $logged_staff_id = isset($logged_staff_id) ? (int)$logged_staff_id : 0;
    $logged_agent_id = isset($logged_agent_id) ? (int)$logged_agent_id : 0;

    $filter_agent_id = isset($_GET['agent_id']) ? (int)$_GET['agent_id'] : 0;
    $filter_staff_id = isset($_GET['staff_id']) ? (int)$_GET['staff_id'] : 0;

    try {
        // Get the total number of records with dynamic filter based on logged-in user role
        /* $totalRecordsQuery = "SELECT COUNT(`itinerary_plan_ID`) AS total FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0'";

        if ($logged_staff_id != 0 && $logged_user_level == 3) {
            $totalRecordsQuery .= " AND `agent_id` IN (SELECT `agent_ID` FROM `dvi_agent` WHERE `travel_expert_id` = '$logged_staff_id')";
        } elseif ($logged_agent_id != 0 && $logged_user_level == 4) {
            $totalRecordsQuery .= " AND (`agent_id` = '$logged_agent_id') ";
        } elseif ($logged_agent_id != 0 && $logged_staff_id != 0 && $logged_user_level == 4) {
            $totalRecordsQuery .= " AND (`agent_id` = '$logged_agent_id' AND `staff_id` = '$logged_staff_id')";
        }

        $totalRecordsResult = sqlQUERY_LABEL($totalRecordsQuery) or die("#1-UNABLE_TO_COUNT_TOTAL_RECORDS:" . sqlERROR_LABEL());
        $totalRecords = sqlFETCHARRAY_LABEL($totalRecordsResult)['total'];

        // Get the paginated records with dynamic filter
        $select_ITINERARYLIST_query = sqlQUERY_LABEL("CALL GetLatestItineraryPlans($start, $limit, $logged_staff_id, $logged_agent_id, $filter_agent_id, $filter_staff_id)") or die("#2-UNABLE_TO_COLLECT_ITINERARY_LIST:" . sqlERROR_LABEL());*/

        // Build the base query
        $baseQuery = "SELECT COUNT(`itinerary_plan_ID`) AS total FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0'";

        // Apply search filter if there's a search value
        if (!empty($searchValue)) {
            $baseQuery .= " AND (`arrival_location` LIKE '%$searchValue%' OR `departure_location` LIKE '%$searchValue%' OR `itinerary_quote_ID` LIKE '%$searchValue%')";
        }

        // Additional filters based on the user's role
        if ($logged_staff_id != 0 && $logged_user_level == 3) { // Travel Expert
            /* $baseQuery .= " AND `agent_id` IN (SELECT `agent_ID` FROM `dvi_agent` WHERE `travel_expert_id` = '$logged_staff_id')"; */
            $logged_staff_id = 0;
        } elseif ($logged_agent_id != 0 && $logged_user_level == 4) { // Agent
            $baseQuery .= " AND (`agent_id` = '$logged_agent_id') ";
        } elseif ($logged_agent_id != 0 && $logged_staff_id != 0 && $logged_user_level == 4) { // Agent Staff
            $baseQuery .= " AND (`agent_id` = '$logged_agent_id' AND `staff_id` = '$logged_staff_id')";
        }

        // Count the total records
        $totalRecordsResult = sqlQUERY_LABEL($baseQuery) or die("#1-UNABLE_TO_COUNT_TOTAL_RECORDS:" . sqlERROR_LABEL());
        $totalRecords = sqlFETCHARRAY_LABEL($totalRecordsResult)['total'];

        sqlFREE_RESULT($totalRecordsResult);

        // Get the filtered records
        $select_ITINERARYLIST_query = sqlQUERY_LABEL("CALL GetLatestItineraryPlans($start, $limit, $logged_staff_id, $logged_agent_id, $filter_agent_id, $filter_staff_id, " . ($searchValue ? "'$searchValue'" : 'NULL') . ", " . ($start_date_format ? "'$start_date_format'" : 'NULL') . ", " . ($end_date_format ? "'$end_date_format'" : 'NULL') . ", " . ($source_location ? "'$source_location'" : 'NULL') . ", " . ($destination_location ? "'$destination_location'" : 'NULL') . ",$logged_user_level)") or die("#2-UNABLE_TO_COLLECT_ITINERARY_LIST:" . sqlERROR_LABEL());

        $datas = [];
        $counter = $start;
        // Fetch data and build the JSON response
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_ITINERARYLIST_query)) {
            $counter++;
            $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
            $itinerary_quote_ID = $fetch_list_data['itinerary_quote_id'];
            $itinerary_booking_ID = $fetch_list_data['itinerary_booking_id'];
            $arrival_location = $fetch_list_data['arrival_location'];
            $departure_location = $fetch_list_data['departure_location'];
            $itinerary_preference = $fetch_list_data['itinerary_preference'];
            $no_of_days = $fetch_list_data['no_of_days'];
            $no_of_nights = $fetch_list_data['no_of_nights'];
            $no_of_routes = $fetch_list_data['no_of_routes'];
            $expecting_budget = $fetch_list_data['expecting_budget'];
            $trip_start_date_and_time = $fetch_list_data["trip_start_date_and_time"];
            $trip_end_date_and_time = $fetch_list_data["trip_end_date_and_time"];
            $total_adult = $fetch_list_data["total_adult"];
            $total_children = $fetch_list_data["total_children"];
            $total_infants = $fetch_list_data["total_infants"];
            $roleID = $fetch_list_data["roleID"];
            $staff_id = $fetch_list_data["staff_id"];
            $agent_id = $fetch_list_data["agent_id"];

            if ($roleID == 1): //ADMIN
                $username = $fetch_list_data["username"];
            elseif ($roleID == 3 && $staff_id != 0 && $agent_id == 0): //TE
                $username = "Travel Expert - <br>" . $fetch_list_data["staff_name"];
            elseif ($roleID == 4 && $staff_id == 0 && $agent_id != 0): //AGENT
                $username = "Agent - <br>" . $fetch_list_data["agent_name"];
            elseif ($roleID == 4 && $staff_id != 0 && $agent_id != 0): //AGENT STAFF
                $username = "Agent - <br>" . $fetch_list_data["staff_name"];
            elseif ($roleID == 5 && $staff_id != 0 && $agent_id == 0): //GUIDE
                $username = "Guide - <br>" . $fetch_list_data["staff_name"];
            endif;

            $createdon = $fetch_list_data["createdon"]; // Added createdon field
            $total_members = "<span>Adult - $total_adult</br>Children - $total_children</br>Infants - $total_infants</span>";

            $datas[] = [
                'counter' => $counter,
                'modify' => $itinerary_plan_ID,
                'itinerary_quote_ID' => $itinerary_quote_ID,
                'itinerary_booking_ID' => $itinerary_booking_ID,
                'arrival_location' => $arrival_location,
                'departure_location' => $departure_location,
                'itinerary_preference' => $itinerary_preference,
                'no_of_days_and_nights' => $no_of_nights . '&' . $no_of_days,
                'no_of_person' => $total_members,
                'trip_start_date_and_time' => date('d/m/Y h:i A', strtotime($trip_start_date_and_time)),
                'trip_end_date_and_time' => date('d/m/Y h:i A', strtotime($trip_end_date_and_time)),
                'total_adult' => $total_adult,
                'total_children' => $total_children,
                'total_infants' => $total_infants,
                'username' => $username,
                'createdon' => date('D, M d, Y', strtotime($createdon)) // Formatted createdon date
            ];
        }

        // Free the result set to avoid "commands out of sync" error
        sqlFREE_RESULT($select_ITINERARYLIST_query);

        if ($searchValue):
            $totalRecords = $counter;
        endif;

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
