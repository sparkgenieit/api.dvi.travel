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
// if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST
$source_location = isset($_GET['source_location']) ? $_GET['source_location'] : '';
$destination_location = isset($_GET['destination_location']) ? $_GET['destination_location'] : '';
$source_location = htmlentities($source_location);
$destination_location = htmlentities($destination_location);
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$start_date_format = dateformat_database($start_date);
$end_date_format = dateformat_database($end_date);
$limit = isset($_GET['length']) ? (int)$_GET['length'] : 10; // Number of records per page
$start = isset($_GET['start']) ? (int)$_GET['start'] : 0; // Starting record
$draw = isset($_GET['draw']) ? intval($_GET['draw']) : 0;
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : ''; // Search value
$logged_staff_id = isset($logged_staff_id) ? (int)$logged_staff_id : 0;
$logged_agent_id = isset($logged_agent_id) ? (int)$logged_agent_id : 0;
$logged_vendor_id = isset($logged_vendor_id) ? (int)$logged_vendor_id : 0;
$filter_agent_id = isset($_GET['agent_id']) ? (int)$_GET['agent_id'] : 0;

$filter_staff_id = isset($_GET['staff_id']) ? (int)$_GET['staff_id'] : 0;
if ($filter_staff_id != 0 && $filter_staff_id != '') {
    $logged_staff_id = $filter_staff_id;
}
$filter_guide_id = isset($_GET['guide_id']) ? (int)$_GET['guide_id'] : 0;
$filter_vendor_id = isset($_GET['vendor_id']) ? (int)$_GET['vendor_id'] : 0;
$cnfi_list_filter = isset($_GET['cnfi_list_filter']) ? $_GET['cnfi_list_filter'] : 0; // Filter for confirmed itinerary list
try {
    $baseQuery = "SELECT COUNT(cip.itinerary_plan_ID) AS total
    FROM dvi_confirmed_itinerary_plan_details cip
    WHERE cip.deleted = 0";

    // Check for cancelled itinerary filter
    if ($cnfi_list_filter != 0 && $cnfi_list_filter != '') {
        $baseQuery = "SELECT COUNT(cip.itinerary_plan_ID) AS total
                    FROM dvi_confirmed_itinerary_plan_details cip
                    JOIN dvi_cancelled_itineraries dci
                    ON dci.itinerary_plan_id = cip.itinerary_plan_ID
                    WHERE cip.deleted = 0 ";
    }

    if (!empty($start_date_format) && ($start_date_format != '0000-00-00')) {
        $baseQuery .= " AND DATE(cip.trip_start_date_and_time) = '$start_date_format'";
    }

    if (!empty($end_date_format) && ($end_date_format != '0000-00-00')) {
        $baseQuery .= " AND DATE(cip.trip_end_date_and_time) = '$end_date_format'";
    }

    // Apply agent and staff-level access filters
    if ($logged_staff_id != 0 && $logged_user_level == 3) {
        /* $baseQuery .= " AND cip.agent_id IN (SELECT agent_ID FROM dvi_agent WHERE travel_expert_id = " . intval($logged_staff_id) . ")"; */
        $logged_staff_id = 0;
    } elseif ($logged_user_level == 4 && $logged_agent_id != 0 && $logged_staff_id != 0) {
        $baseQuery .= " AND (cip.agent_id = " . intval($logged_agent_id) . "
          AND cip.staff_id = " . intval($logged_staff_id) . ")";
    } elseif ($logged_user_level == 4 && $logged_agent_id != 0) {
        $baseQuery .= " AND cip.agent_id = " . intval($logged_agent_id);
    } elseif ($filter_agent_id > 0) {
        $baseQuery .= " AND cip.agent_id = " . intval($filter_agent_id);
    }

    if (($logged_vendor_id != 0 && $logged_user_level == 2) || ($filter_vendor_id != '' && $filter_vendor_id != '0')) {
        if ($filter_vendor_id != '' && $filter_vendor_id != '0') {
            $logged_vendor_id = $filter_vendor_id;
        }
        $baseQuery = '';
        $baseQuery = "
            SELECT COUNT(dip.itinerary_plan_ID) AS total
            FROM dvi_confirmed_itinerary_plan_details dip
            LEFT JOIN dvi_itinerary_plan_vendor_eligible_list vel
            ON vel.itinerary_plan_id = dip.itinerary_plan_ID
            AND vel.vendor_id = " . intval($logged_vendor_id) . "
            AND vel.itineary_plan_assigned_status = 1
            WHERE dip.deleted = 0
            AND vel.itinerary_plan_id IS NOT NULL
        ";
    }

    if (($filter_guide_id != '' && $filter_guide_id != '0')) {
        $baseQuery = '';
        $baseQuery = " SELECT COUNT(dip.itinerary_plan_ID) AS total
                        FROM dvi_confirmed_itinerary_plan_details dip
                        LEFT JOIN dvi_itinerary_route_guide_details gd
                        ON gd.itinerary_plan_ID = dip.itinerary_plan_ID
                        AND gd.guide_id = " . intval($filter_guide_id) . "
                        AND gd.status = 1
                        WHERE dip.deleted = 0
                        AND gd.itinerary_plan_ID IS NOT NULL ";
    }

    // Count the total records
    $totalRecordsResult = sqlQUERY_LABEL($baseQuery) or die("#1-UNABLE_TO_COUNT_TOTAL_RECORDS:" . sqlERROR_LABEL());
    $totalRecords = sqlFETCHARRAY_LABEL($totalRecordsResult)['total'];
    sqlFREE_RESULT($totalRecordsResult);

    // Get the filtered records
    $select_ITINERARYLIST_query = sqlQUERY_LABEL("CALL GetConfirmedItineraryPlans($start, $limit, $logged_staff_id, $logged_agent_id, $logged_vendor_id, $filter_agent_id, $filter_guide_id, '$searchValue', '$start_date_format', '$end_date_format', '$source_location', '$destination_location', '$logged_user_level', '$cnfi_list_filter')") or die("#2-UNABLE_TO_COLLECT_ITINERARY_LIST:" . sqlERROR_LABEL());

    // Get the total number of records
    /* $totalRecordsQuery = "SELECT COUNT(*) AS total FROM dvi_confirmed_itinerary_plan_details WHERE deleted = '0'";
        if ($logged_staff_id != 0 && $logged_user_level == 3) {
            $totalRecordsQuery .= " AND `agent_id` IN (SELECT `agent_ID` FROM `dvi_agent` WHERE `travel_expert_id` = '$logged_staff_id')";
        } elseif ($logged_agent_id != 0 && $logged_user_level == 4) {
            $totalRecordsQuery .= " AND (`agent_id` = '$logged_agent_id') ";
        } elseif ($logged_agent_id != 0 && $logged_staff_id != 0 && $logged_user_level == 4) {
            $totalRecordsQuery .= " AND (`agent_id` = '$logged_agent_id' AND `staff_id` = '$logged_staff_id')";
        }
        $totalRecordsResult = sqlQUERY_LABEL($totalRecordsQuery) or die("#1-UNABLE_TO_COUNT_TOTAL_RECORDS:" . sqlERROR_LABEL());
        $totalRecords = sqlFETCHARRAY_LABEL($totalRecordsResult)['total'];

        // Free the result set to avoid "commands out of sync" error
        sqlFREE_RESULT($totalRecordsResult);

        // Get the paginated records
        $select_ITINERARYLIST_query = sqlQUERY_LABEL("CALL GetConfirmedItineraryPlans($start, $limit, $logged_staff_id, $logged_agent_id, $filter_agent_id, $filter_staff_id)") or die("#2-UNABLE_TO_COLLECT_ITINERARY_LIST:" . sqlERROR_LABEL());
        */

    $datas = [];
    $counter = $start;
    // Fetch data and build the JSON response
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_ITINERARYLIST_query)) {
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $confirmed_itinerary_plan_ID = $fetch_list_data['confirmed_itinerary_plan_ID'];
        $hotel_voucher_count = $fetch_list_data['hotel_voucher_count'];
        $vehicle_voucher_count = $fetch_list_data['vehicle_voucher_count'];
        $itinerary_quote_ID = $fetch_list_data['itinerary_quote_id'];
        $itinerary_booking_ID = $fetch_list_data['itinerary_booking_id'];
        $arrival_location = $fetch_list_data['arrival_location'];
        $departure_location = $fetch_list_data['departure_location'];
        $no_of_days = $fetch_list_data['no_of_days'];
        $itinerary_preference = $fetch_list_data['itinerary_preference'];
        $no_of_nights = $fetch_list_data['no_of_nights'];
        $no_of_routes = $fetch_list_data['no_of_routes'];
        $expecting_budget = $fetch_list_data['expecting_budget'];
        $trip_start_date_and_time = $fetch_list_data["trip_start_date_and_time"];
        $trip_end_date_and_time = $fetch_list_data["trip_end_date_and_time"];
        $total_adult = $fetch_list_data["total_adult"];
        $total_children = $fetch_list_data["total_children"];
        $total_infants = $fetch_list_data["total_infants"];
        $itinerary_total_net_payable_amount = $fetch_list_data["itinerary_total_net_payable_amount"];
        $itinerary_total_paid_amount = $fetch_list_data["itinerary_total_paid_amount"];
        $itinerary_total_balance_amount = $fetch_list_data["itinerary_total_balance_amount"];
        $CIP_AID = $fetch_list_data["CIP_AID"];
        //$username = $fetch_list_data["username"];
        $createdon = $fetch_list_data["createdon"];
        $primary_customer = $fetch_list_data["primary_customer"];
        $total_members = "<span>Adult - $total_adult</br>Children - $total_children</br>Infants - $total_infants</span>";
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
        $itinerary_cancellation_status = $fetch_list_data["itinerary_cancellation_status"];
        $datas[] = [
            'counter' => $counter,
            'modify' => $itinerary_plan_ID,
            'itinerary_quote_ID' => $itinerary_quote_ID,
            'itinerary_booking_ID' => $itinerary_booking_ID,
            'hotel_voucher_count' => $hotel_voucher_count,
            'vehicle_voucher_count' => $vehicle_voucher_count,
            'confirmed_itinerary_plan_ID' => $confirmed_itinerary_plan_ID,
            'arrival_location' => $arrival_location,
            'itinerary_preference' => $itinerary_preference,
            'departure_location' => $departure_location,
            'no_of_days_and_nights' => $no_of_nights . '&' . $no_of_days,
            'primary_customer' => $primary_customer,
            'no_of_person' => $total_members,
            'trip_start_date_and_time' => date('d/m/Y h:i A', strtotime($trip_start_date_and_time)),
            'trip_end_date_and_time' => date('d/m/Y h:i A', strtotime($trip_end_date_and_time)),
            'total_adult' => $total_adult,
            'total_children' => $total_children,
            'total_infants' => $total_infants,
            'itinerary_total_net_payable_amount' => $itinerary_total_net_payable_amount,
            'itinerary_total_paid_amount' => $itinerary_total_paid_amount,
            'itinerary_total_balance_amount' => $itinerary_total_balance_amount,
            'agentID' => $CIP_AID,
            'username' => $username,
            'itinerary_cancellation_status' => $itinerary_cancellation_status,
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
// else :
//     echo "Request Ignored !!!";
// endif;