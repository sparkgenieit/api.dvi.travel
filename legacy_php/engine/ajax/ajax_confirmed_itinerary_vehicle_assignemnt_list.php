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

/*ini_set('display_errors', 1);
ini_set('log_errors', 1);*/

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $date_from = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_from'])));
        $date_to = date('Y-m-d', strtotime(str_replace('/', '-', $_POST['date_to'])));

        $vendor_id = $_POST['vendor_id'];
        if ($vendor_id):
            $vendor_ids_array = array_map('trim', $vendor_id);
            $formatted_vendor_id_list = implode(", ", array_map(function ($id) {
                return "'" . addslashes($id) . "'"; // Sanitize for SQL injection
            }, $vendor_ids_array));
            $filter_by_vendor_id = " AND (CNF_ITINEARY_VEHICLE_VOUCER.`vendor_id` IN ($formatted_vendor_id_list)) ";
        else:
            $filter_by_vendor_id = "";
        endif;

        $vehicle_type_id = $_POST['vehicle_type_id'];
        if ($vehicle_type_id):
            $vehicle_type_ids_array = array_map('trim', $vehicle_type_id);
            $formattedvehicle_type_id_list = implode(", ", array_map(function ($id) {
                return "'" . addslashes($id) . "'"; // Sanitize for SQL injection
            }, $vehicle_type_ids_array));
            $filter_by_vendor_vehicle_type_id = " AND  (VEHICLE.`vehicle_type_id` IN ($formattedvehicle_type_id_list) ) ";
        else:
            $filter_by_vendor_vehicle_type_id = "";
        endif;

        $agent_id = $_POST['agent_id'];
        if ($agent_id):
            $agent_ids_array = array_map('trim', $agent_id);
            $formattedagent_id_list = implode(", ", array_map(function ($id) {
                return "'" . addslashes($id) . "'"; // Sanitize for SQL injection
            }, $agent_ids_array));
            $filter_by_agent_id = " AND  (CNF_ITINEARY.`agent_id` IN ($formattedagent_id_list)) ";
        else:
            $filter_by_agent_id = "";
        endif;

        $location = $_POST['location_id'];
        if ($location):
            $locations_array = array_map('trim', $location);
            $formattedlocations_list = implode(", ", array_map(function ($id) {
                return "'" . addslashes($id) . "'"; // Sanitize for SQL injection
            }, $locations_array));
            //$filter_by_location = " AND  ((CNF_ROUTE.`location_name` IN ($formattedlocations_list)) OR (CNF_ROUTE.`next_visiting_location` IN ($formattedlocations_list))) ";
            $filter_by_location = " AND EXISTS (SELECT 1 FROM `dvi_confirmed_itinerary_route_details` CNF_ROUTE WHERE CNF_ROUTE. `itinerary_plan_ID` = CNF_ITINEARY.`itinerary_plan_ID` AND (CNF_ROUTE.`location_name` IN ($formattedlocations_list) OR CNF_ROUTE.`next_visiting_location` IN ($formattedlocations_list)) )";
        else:
            $filter_by_location = "";
        endif;

        // Get the first and last day of the current month using strtotime
        $startOfMonth = strtotime($date_from);  // First day of the current month
        $endOfMonth = strtotime($date_to);     // Last day of the current month

        // Create an array to hold all the dates of the month
        $dates = [];
        $currentDate = $startOfMonth;

        while ($currentDate <= $endOfMonth) {
            // Format each date as 'Y-m-d' for easier comparison
            $dates[] = date('Y-m-d', $currentDate);
            // Move to the next day
            $currentDate = strtotime('+1 day', $currentDate);
        }
?>
        <div id="vehicle_availability_list">
            <div class="text-nowrap table-responsive table-bordered">
                <table id="vehicle-availability-table">
                    <thead>
                        <tr>
                            <th scope="col">Vendor</th>
                            <th scope="col">Vehicle Type</th>
                            <!-- Loop through PHP array and display date headers -->
                            <?php foreach ($dates as $date): ?>
                                <th scope="col"><?php echo date('d-M Y', strtotime($date)); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $selected_query = sqlQUERY_LABEL("SELECT
                                        VEHICLE.`vendor_id`,
                                        VEHICLE.`vehicle_type_id`,
                                        VEHICLE.`vehicle_id`,
                                        VEHICLE.`registration_number`,
                                        GROUP_CONCAT(DISTINCT CNF_ITINEARY.`itinerary_plan_ID` ORDER BY CNF_ITINEARY.`trip_start_date_and_time`) AS itinerary_plan_IDs,
                                        GROUP_CONCAT(DATE_FORMAT(CNF_ITINEARY.`trip_start_date_and_time`, '%Y-%m-%d') ORDER BY CNF_ITINEARY.`trip_start_date_and_time`) AS trip_start_dates,
                                        GROUP_CONCAT(DATE_FORMAT(CNF_ITINEARY.`trip_end_date_and_time`, '%Y-%m-%d') ORDER BY CNF_ITINEARY.`trip_start_date_and_time`) AS trip_end_dates,
                                        GROUP_CONCAT(CNF_ITINEARY.`arrival_location` ORDER BY CNF_ITINEARY.`trip_start_date_and_time`) AS arrival_locations,
                                        GROUP_CONCAT(CNF_ITINEARY.`departure_location` ORDER BY CNF_ITINEARY.`trip_start_date_and_time`) AS departure_locations,
                                        GROUP_CONCAT(DISTINCT CNF_ITINEARY.`itinerary_quote_ID` ORDER BY CNF_ITINEARY.`trip_start_date_and_time`) AS itinerary_quote_IDs,
                                        route_data.route_arrival_locations,
                                        route_data.route_departure_locations,
                                        COUNT(DISTINCT CNF_ITINEARY.`itinerary_plan_ID`) AS confirmed_itinerary_count
                                    FROM
                                        `dvi_vehicle` VEHICLE
                                    LEFT JOIN
                                        `dvi_confirmed_itinerary_plan_vehicle_voucher_details` CNF_ITINEARY_VEHICLE_VOUCER
                                        ON CNF_ITINEARY_VEHICLE_VOUCER.`vendor_id` = VEHICLE.`vendor_id`
                                        AND CNF_ITINEARY_VEHICLE_VOUCER.`vehicle_type_id` = VEHICLE.`vehicle_type_id`
                                    LEFT JOIN
                                        `dvi_confirmed_itinerary_plan_details` CNF_ITINEARY
                                        ON CNF_ITINEARY_VEHICLE_VOUCER.`itinerary_plan_id` = CNF_ITINEARY.`itinerary_plan_ID`
                                    LEFT JOIN (
                                        SELECT
                                            CNF_ROUTE.`itinerary_plan_ID`,
                                            GROUP_CONCAT(DISTINCT CNF_ROUTE.`location_name` ORDER BY CNF_ROUTE.`location_name`) AS route_arrival_locations,
                                            GROUP_CONCAT(DISTINCT CNF_ROUTE.`next_visiting_location` ORDER BY CNF_ROUTE.`next_visiting_location`) AS route_departure_locations
                                        FROM
                                            `dvi_confirmed_itinerary_route_details` CNF_ROUTE
                                        GROUP BY
                                            CNF_ROUTE.`itinerary_plan_ID`
                                    ) AS route_data ON route_data.itinerary_plan_ID = CNF_ITINEARY.`itinerary_plan_ID`
                                    WHERE
                                        CNF_ITINEARY_VEHICLE_VOUCER.`itinerary_plan_id` IS NOT NULL
                                        AND CNF_ITINEARY.`status` = '1'
                                        AND CNF_ITINEARY.`deleted` = '0'
                                        AND (CNF_ITINEARY.`itinerary_preference` = '2' OR CNF_ITINEARY.`itinerary_preference` = '3')
                                        AND CNF_ITINEARY_VEHICLE_VOUCER.`vehicle_booking_status` = '4'
                                        AND CNF_ITINEARY_VEHICLE_VOUCER.`status` = '1'
                                        AND CNF_ITINEARY_VEHICLE_VOUCER.`deleted` = '0'
                                        AND VEHICLE.`status` = '1'
                                        AND VEHICLE.`deleted` = '0'
                                        AND ((DATE(CNF_ITINEARY.`trip_start_date_and_time`) BETWEEN '$date_from' AND '$date_to') OR (DATE(CNF_ITINEARY.`trip_end_date_and_time`) BETWEEN '$date_from' AND '$date_to')) 
                                        $filter_by_vendor_id  $filter_by_vendor_vehicle_type_id $filter_by_agent_id $filter_by_location 
                                    GROUP BY
                                        VEHICLE.`vendor_id`,
                                        VEHICLE.`vehicle_type_id`,
                                        VEHICLE.`vehicle_id`,
                                        VEHICLE.`registration_number`
                                    HAVING
                                        confirmed_itinerary_count > 0;") or die("#1-getCOURSE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());


                        if (sqlNUMOFROW_LABEL($selected_query) > 0):
                            while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) {
                                $vehicle_id = $fetch_data['vehicle_id'];
                                $vendor_id = $fetch_data['vendor_id'];
                                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                $vendor = getVENDOR_DETAILS($fetch_data['vendor_id'], 'label');
                                $vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                $registration_number = $fetch_data['registration_number'];
                                $itinerary_plan_IDs = explode(',', $fetch_data['itinerary_plan_IDs']);
                                $trip_start_dates = explode(',', $fetch_data['trip_start_dates']);
                                $trip_end_dates = explode(',', $fetch_data['trip_end_dates']);
                                $arrival_locations = explode(',', $fetch_data['arrival_locations']);
                                $departure_locations = explode(',', $fetch_data['departure_locations']);
                                $itinerary_quote_IDs = explode(',', $fetch_data['itinerary_quote_IDs']);
                                $today = strtotime(date('Y-m-d'));
                                $confirmed_itineraries = $fetch_data['confirmed_itineraries'];
                                $processed_ranges = [];

                                echo "<tr>";
                                echo "<td>$vendor</td>";
                                echo "<td>$vehicle_type </br><span class='text-blue-color'> $registration_number</span></td>";

                                // Loop through each date in the month

                                foreach ($dates as $date) {
                                    $date_timestamp = strtotime($date);

                                    $itinerary_details = '';
                                    $itinerary_details_not_assigned = "";
                                    $combined_itinerary_details = "";
                                    $combined_assigned_itinerary_details = "";
                                    $assigned_class = '';
                                    $driver_edit = '';
                                    $string_itinerary_plan_IDs = "";
                                    $string_itinerary_quote_IDs = "";
                                    $show_assign_button = false;
                                    $show_driver_button = false;
                                    $show_driver_name = false;
                                    $is_vehicle_assigned = false;
                                    $is_driver_assigned = false;
                                    $is_itinerary_running = false;
                                    $ASSIGNED_ITINERARY = [];
                                    // Loop through all start and end dates for this vehicle and check if current date falls within any trip
                                    for ($i = 0; $i < count($trip_start_dates); $i++) {

                                        $itinerary_plan_ID = $itinerary_plan_IDs[$i];
                                        $trip_start = strtotime($trip_start_dates[$i]);
                                        $trip_end = strtotime($trip_end_dates[$i]);

                                        $vehicletype_ID = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'get_vehicle_type_id');
                                        $no_of_vehicle_to_be_assigned = getCONFIRMED_ITINERARY_VEHICLE_COUNT($itinerary_plan_ID, $vehicletype_ID);
                                        // echo $vehicle_count . "<br>";


                                        // Convert the date range into a unique key for tracking
                                        $range_key = $trip_start .  '-vt_' . $vehicle_type_id;

                                        if ($date_timestamp >= $trip_start && $date_timestamp <= $trip_end) {

                                            // Mark itinerary as running if the current date is within the start and end dates
                                            if ($today >= $trip_start && $today <= $trip_end) {
                                                $is_itinerary_running = true;
                                            }


                                            $trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time');
                                            $trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time');
                                            $trip_start_time = date('H:i A', strtotime($trip_start_date_and_time));
                                            $trip_end_time = date('H:i A', strtotime($trip_end_date_and_time));
                                            $available_vehicles = getAVAILABLE_VEHICLE_COUNT($vendor_id, $vehicle_type_id, $trip_start_date_and_time, $trip_end_date_and_time);
                                            //echo $vendor . "-" . $vehicle_type . "-" . $available_vehicles . "<br>";

                                            // Check if this vehicle is already assigned to this itinerary 

                                            $assigned_vehicle_query = sqlQUERY_LABEL("SELECT vendor_vehicle_assigned_ID, vehicle_id FROM dvi_confirmed_itinerary_vendor_vehicle_assigned WHERE `status` = '1' AND `deleted` = '0' 
                                                    AND `vendor_id` = '{$fetch_data['vendor_id']}'
                                                    AND `vendor_vehicle_type_id` = '{$fetch_data['vehicle_type_id']}' AND `itinerary_plan_id` = '$itinerary_plan_ID'
                                                    ") or die("#3-assignCHECK: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                            // If the vehicle is assigned
                                            if (sqlNUMOFROW_LABEL($assigned_vehicle_query) > 0) {
                                                // Mark the vehicle as assigned
                                                $is_vehicle_assigned = true;
                                                //All vehicles are assigned
                                                if ($no_of_vehicle_to_be_assigned == sqlNUMOFROW_LABEL($assigned_vehicle_query)):
                                                    while ($vehicle_data = sqlFETCHARRAY_LABEL($assigned_vehicle_query)) {
                                                        $assigned_vehicle_id = $vehicle_data['vehicle_id'];
                                                        if ($assigned_vehicle_id == $vehicle_id) {
                                                            $route_query = sqlQUERY_LABEL("SELECT `itinerary_route_ID`,`location_name`, `next_visiting_location`
                                                                    FROM `dvi_confirmed_itinerary_route_details`
                                                                    WHERE `status` = '1'
                                                                    AND `deleted` = '0'
                                                                    AND `itinerary_plan_ID` = '$itinerary_plan_ID'
                                                                    AND `itinerary_route_date` = '$date' ") or die("#2-getROUTE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                                            $route_details = [];
                                                            while ($route_data = sqlFETCHARRAY_LABEL($route_query)) {
                                                                $itinerary_route_ID = $route_data['itinerary_route_ID'];
                                                                $route_details[] = $route_data['location_name'] . " => " . $route_data['next_visiting_location'];
                                                            }

                                                            $hotel_id = get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'HOTEL_DETAILS');
                                                            if ($hotel_id) {
                                                                $hotel_name = "Hotel :" . getHOTEL_DETAIL($hotel_id, '', 'label') . " " . getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city');
                                                            }

                                                            //TWO TRIPS ON SAME DAY
                                                            //$assigned_itinerary_key = $trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id;

                                                            if ($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id]) {

                                                                $assigned_trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'trip_end_date_and_time');

                                                                $assigned_trip_end_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'departure_location');

                                                                if (($assigned_trip_end_date_and_time < $trip_start_date_and_time) && ($date == $trip_start_dates[$i])):

                                                                    //$assigned_class =        'class="arrival-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                                    $combined_assigned_itinerary_details .= '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                            <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                                ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                            </a>
                                                                            <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                                                <i class="ti ti-calendar-event text-body ti-sm"></i> ' . htmlspecialchars($trip_start_time) . '
                                                                            </span>
                                                                            </h6> <h6 class="text-dark mb-2">Guest : ' .  get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'customer_salutation') . "." . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') . "  " . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no') . '</h6>
                                                                            <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details)) . '</h6>
                                                                            <h6 class="text-dark mb-2">' . $hotel_name . '</h6>';
                                                                    // Check if a driver is already assigned
                                                                    $driver_assigned_query = sqlQUERY_LABEL("SELECT `driver_assigned_ID`,`driver_id`
                                                                            FROM `dvi_confirmed_itinerary_vendor_driver_assigned`
                                                                            WHERE `status` = '1'
                                                                            AND `deleted` = '0'
                                                                            AND `itinerary_plan_id` = '$itinerary_plan_ID'
                                                                            AND `vendor_id` = '{$fetch_data['vendor_id']}'
                                                                            AND `vendor_vehicle_type_id` = '{$fetch_data['vehicle_type_id']}'  AND `vehicle_id`='$vehicle_id' ") or die("#4-checkDRIVER: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                                                    if (sqlNUMOFROW_LABEL($driver_assigned_query) > 0):
                                                                        $is_driver_assigned = true;  // Mark the driver as assigned
                                                                        $show_driver_name = true;
                                                                        // If a driver is assigned, get the driver's name
                                                                        $driver_data1 = sqlFETCHARRAY_LABEL($driver_assigned_query);
                                                                        // $driver_id = $driver_data1['driver_id'];
                                                                        $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                        $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                        $get_registration_number = $fetch_data['registration_number'];
                                                                        $customer_whatsapp_no = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                        $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                        $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
                                                                        $driver_details = "<br>'.' Driver Name:'. $get_drivername.' <br>'.' Mobile No:'. $get_mobile_no.' <br>'. ' Vehicle Name:'. $get_vehicle_type.' <br>'.' Vehicle Number:'. $get_registration_number.'";
                                                                        $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                        $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);
                                                                        if ($date == $trip_start_dates[$i]) {
                                                                            $driver_edit = ' <span class="cursor-pointer" onclick="editDRIVERMODAL(' . $itinerary_plan_ID . ',' . $vendor_id . ',' . $driver_id . ')"> <i class="ti-sm ti ti-edit mb-1 ms-2"></i> </span>';
                                                                        }

                                                                        $combined_assigned_itinerary_details .= ' <div class="d-flex">
                                                                                    <div>
                                                                                        <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - ' . getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name') . '-' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . ' </h6>
                                                                                        <div class="d-flex align-items-center">
                                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                                           <a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                                        </div>
                                                                                    </div>
                                                                                    <span class="cursor-pointer" onclick="editDRIVERMODAL(' . $itinerary_plan_ID . ',' . $vendor_id . ',' . $driver_id . ')"> <i class="ti-sm ti ti-edit mb-1 ms-2"></i> </span>
                                                                                </div>';

                                                                    endif;
                                                                else:
                                                                    $combined_assigned_itinerary_details = "";
                                                                endif;
                                                            } else {
                                                                // Prepare itinerary details
                                                                if ($date == $trip_start_dates[$i]) {
                                                                    $assigned_class =        'class="arrival-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                                    $itinerary_details = '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                            <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                                ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                            </a>
                                                                            <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                                                <i class="ti ti-calendar-event text-body ti-sm"></i> ' . htmlspecialchars($trip_start_time) . '
                                                                            </span>
                                                                            </h6>
                                                                            <h6 class="text-dark mb-2">Guest : ' .  get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'customer_salutation') . "." . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') . "  " . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no') . '</h6>
                                                                            <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details)) . '</h6><h6 class="text-dark mb-2"> ' . $hotel_name . '</h6>';
                                                                } elseif ($date == $trip_end_dates[$i]) {

                                                                    //$assigned_itinerary_key = $trip_end_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id;
                                                                    $ASSIGNED_ITINERARY[$trip_end_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id] = $itinerary_plan_ID;

                                                                    $assigned_class =        'class="departure-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";

                                                                    // Create the HTML string with the link included
                                                                    $itinerary_details = '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                            <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                                ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                            </a>
                                                                            <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                                                <i class="ti ti-calendar-event text-body ti-sm"></i>' . htmlspecialchars($trip_end_time) . '
                                                                            </span>
                                                                            </h6>
                                                                            <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details)) . '</h6>';
                                                                } else {
                                                                    $assigned_class =        'class="inbetween-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";

                                                                    // Create the HTML string with the link included
                                                                    $itinerary_details = '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                            <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                                ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                            </a>
                                                                            </h6>
                                                                            <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details))
                                                                        . '</h6>
                                                                            <h6 class="text-dark mb-2">' . $hotel_name . '</h6>';
                                                                }

                                                                // Check if a driver is already assigned
                                                                $driver_assigned_query = sqlQUERY_LABEL("SELECT `driver_assigned_ID`,`driver_id`
                                                                            FROM `dvi_confirmed_itinerary_vendor_driver_assigned`
                                                                            WHERE `status` = '1'
                                                                            AND `deleted` = '0'
                                                                            AND `itinerary_plan_id` = '$itinerary_plan_ID'
                                                                            AND `vendor_id` = '{$fetch_data['vendor_id']}'
                                                                            AND `vendor_vehicle_type_id` = '{$fetch_data['vehicle_type_id']}'  AND `vehicle_id`='$vehicle_id' ") or die("#4-checkDRIVER: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                                                if (sqlNUMOFROW_LABEL($driver_assigned_query) == 0 && $date == $trip_start_dates[$i]) {
                                                                    // No driver assigned, show button to assign driver
                                                                    $show_driver_button = true;
                                                                } elseif (sqlNUMOFROW_LABEL($driver_assigned_query) > 0) {
                                                                    $is_driver_assigned = true;  // Mark the driver as assigned
                                                                    $show_driver_name = true;
                                                                    // If a driver is assigned, get the driver's name
                                                                    $driver_data = sqlFETCHARRAY_LABEL($driver_assigned_query);
                                                                    $driver_id = $driver_data['driver_id'];
                                                                    $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                    $whatsapp_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'whatsapp_no');
                                                                    $mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
                                                                    if ($date == $trip_start_dates[$i]) {
                                                                        $driver_edit = ' <span class="cursor-pointer" onclick="editDRIVERMODAL(' . $itinerary_plan_ID . ',' . $vendor_id . ',' . $driver_id . ')">
                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i> </span>';
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                elseif ($no_of_vehicle_to_be_assigned > sqlNUMOFROW_LABEL($assigned_vehicle_query)):
                                                    //some of the vehicles are assigned

                                                    while ($vehicle_data = sqlFETCHARRAY_LABEL($assigned_vehicle_query)) {
                                                        $assigned_vehicle_id = $vehicle_data['vehicle_id'];
                                                        if ($assigned_vehicle_id == $vehicle_id) {
                                                            $route_query = sqlQUERY_LABEL("SELECT `location_name`, `next_visiting_location`
                                                                    FROM `dvi_confirmed_itinerary_route_details`
                                                                    WHERE `status` = '1'
                                                                    AND `deleted` = '0'
                                                                    AND `itinerary_plan_ID` = '$itinerary_plan_ID'
                                                                    AND `itinerary_route_date` = '$date' ") or die("#2-getROUTE: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                                            $route_details = [];
                                                            while ($route_data = sqlFETCHARRAY_LABEL($route_query)) {
                                                                $itinerary_route_ID = $route_data['itinerary_route_ID'];
                                                                $route_details[] = $route_data['location_name'] . " => " . $route_data['next_visiting_location'];
                                                            }

                                                            $hotel_id = get_ASSIGNED_HOTEL_FOR_ITINEARY_CONFIRMED_PLAN_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, '', '', '', 'HOTEL_DETAILS');

                                                            if ($hotel_id) {
                                                                $hotel_name = "Hotel :" . getHOTEL_DETAIL($hotel_id, '', 'label') . " " . getHOTEL_DETAIL($hotel_id, '', 'hotel_state_city');
                                                            }
                                                            //TWO TRIPS ON SAME DAY
                                                            //$assigned_itinerary_key = $trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id;

                                                            if ($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id]) {

                                                                $assigned_trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'trip_end_date_and_time');

                                                                $assigned_trip_end_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'departure_location');

                                                                if (($assigned_trip_end_date_and_time < $trip_start_date_and_time) && ($date == $trip_start_dates[$i])):

                                                                    //$assigned_class =        'class="arrival-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                                    $combined_assigned_itinerary_details .= '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                            <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                                ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                            </a>
                                                                            <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                                                <i class="ti ti-calendar-event text-body ti-sm"></i> ' . htmlspecialchars($trip_start_time) . '
                                                                            </span>
                                                                            </h6> <h6 class="text-dark mb-2">Guest : ' .  get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'customer_salutation') . "." . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') . "  " . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no') . '</h6>
                                                                            <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details))  . '</h6>
                                                                            <h6 class="text-dark mb-2">' . $hotel_name . '</h6>';

                                                                    // Check if a driver is already assigned
                                                                    $driver_assigned_query = sqlQUERY_LABEL("SELECT `driver_assigned_ID`,`driver_id`
                                                                            FROM `dvi_confirmed_itinerary_vendor_driver_assigned`
                                                                            WHERE `status` = '1'
                                                                            AND `deleted` = '0'
                                                                            AND `itinerary_plan_id` = '$itinerary_plan_ID'
                                                                            AND `vendor_id` = '{$fetch_data['vendor_id']}'
                                                                            AND `vendor_vehicle_type_id` = '{$fetch_data['vehicle_type_id']}'  AND `vehicle_id`='$vehicle_id' ") or die("#4-checkDRIVER: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                                                    if (sqlNUMOFROW_LABEL($driver_assigned_query) > 0):
                                                                        $is_driver_assigned = true;  // Mark the driver as assigned
                                                                        $show_driver_name = true;
                                                                        // If a driver is assigned, get the driver's name
                                                                        $driver_data1 = sqlFETCHARRAY_LABEL($driver_assigned_query);
                                                                        $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                        $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                        $get_registration_number = $fetch_data['registration_number'];
                                                                        $customer_whatsapp_no = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                        $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                        $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
                                                                        $driver_details = "<br>'.' Driver Name:'. $get_drivername.' <br>'.' Mobile No:'. $get_mobile_no.' <br>'. ' Vehicle Name:'. $get_vehicle_type.' <br>'.' Vehicle Number:'. $get_registration_number.'";
                                                                        $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                        $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);
                                                                        //if ($date == $trip_start_dates[$i]) {
                                                                        //    $driver_edit = ' <span class="cursor-pointer" onclick="editDRIVERMODAL(' . $itinerary_plan_ID . ',' . $vendor_id . ',' . $driver_id . ')"> <i class="ti-sm ti ti-edit mb-1 ms-2"></i> </span>';
                                                                        //}

                                                                        $combined_assigned_itinerary_details .= ' <div class="d-flex">
                                                                                    <div>
                                                                                        <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - ' . getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name') . '-' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . ' </h6>
                                                                                        <div class="d-flex align-items-center">
                                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                                          <a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                                        </div>
                                                                                    </div>
                                                                                    <span class="cursor-pointer" onclick="editDRIVERMODAL(' . $itinerary_plan_ID . ',' . $vendor_id . ',' . $driver_id . ')"> <i class="ti-sm ti ti-edit mb-1 ms-2"></i> </span>
                                                                                </div>';

                                                                    endif;
                                                                else:
                                                                    $combined_assigned_itinerary_details = "";
                                                                endif;
                                                            } else {
                                                                // Prepare itinerary details
                                                                if ($date == $trip_start_dates[$i]) {
                                                                    $assigned_class =        'class="arrival-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                                    $itinerary_details = '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                        <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                            ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                        </a>
                                                                        <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                                            <i class="ti ti-calendar-event text-body ti-sm"></i> ' . htmlspecialchars($trip_start_time) . '
                                                                        </span>
                                                                        </h6>
                                                                        <h6 class="text-dark mb-2">Guest : ' .  get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'customer_salutation') . "." . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name') . "  " . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no') . '</h6>
                                                                        <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details))
                                                                        . '</h6>
                                                                            <h6 class="text-dark mb-2">' . $hotel_name . '</h6>';
                                                                } elseif ($date == $trip_end_dates[$i]) {

                                                                    //$assigned_itinerary_key = $trip_end_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id;
                                                                    $ASSIGNED_ITINERARY[$trip_end_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id] = $itinerary_plan_ID;

                                                                    $assigned_class =        'class="departure-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";

                                                                    // Create the HTML string with the link included
                                                                    $itinerary_details = '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                        <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                            ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                        </a>
                                                                        <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;">
                                                                            <i class="ti ti-calendar-event text-body ti-sm"></i>' . htmlspecialchars($trip_end_time) . '
                                                                        </span>
                                                                        </h6>
                                                                        <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details)) . '</h6>';
                                                                } else {
                                                                    $assigned_class =        'class="inbetween-vehicle"';
                                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";

                                                                    // Create the HTML string with the link included
                                                                    $itinerary_details = '<h6 class="text-blue-color mb-1 d-flex gap-2">
                                                                        <a href="' . $_url . urlencode($itinerary_plan_ID) . '" target="_blank">
                                                                            ' . htmlspecialchars($itinerary_quote_IDs[$i]) . '
                                                                        </a>
                                                                        </h6>
                                                                        <h6 class="text-dark mb-2">' . implode('<br>', array_map('htmlspecialchars', $route_details))
                                                                        . '</h6>
                                                                            <h6 class="text-dark mb-2">' . $hotel_name . '</h6>';
                                                                }

                                                                // Check if a driver is already assigned
                                                                $driver_assigned_query = sqlQUERY_LABEL("SELECT `driver_assigned_ID`,`driver_id`
                                                                        FROM `dvi_confirmed_itinerary_vendor_driver_assigned`
                                                                        WHERE `status` = '1'
                                                                        AND `deleted` = '0'
                                                                        AND `itinerary_plan_id` = '$itinerary_plan_ID'
                                                                        AND `vendor_id` = '{$fetch_data['vendor_id']}'
                                                                        AND `vendor_vehicle_type_id` = '{$fetch_data['vehicle_type_id']}' AND `vehicle_id`='$vehicle_id'
                                                                        
                                                                    ") or die("#4-checkDRIVER: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

                                                                if (sqlNUMOFROW_LABEL($driver_assigned_query) == 0 && $date == $trip_start_dates[$i]) {
                                                                    // No driver assigned, show button to assign driver
                                                                    $show_driver_button = true;
                                                                } elseif (sqlNUMOFROW_LABEL($driver_assigned_query) > 0) {
                                                                    $is_driver_assigned = true;  // Mark the driver as assigned
                                                                    $show_driver_name = true;
                                                                    // If a driver is assigned, get the driver's name
                                                                    $driver_data = sqlFETCHARRAY_LABEL($driver_assigned_query);
                                                                    $driver_id = $driver_data['driver_id'];
                                                                    $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                    $whatsapp_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'whatsapp_no');
                                                                    $mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                    $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                    $get_registration_number = $fetch_data['registration_number'];
                                                                    $customer_whatsapp_no = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                    $driver_details = "<br>'.' Driver Name:'. $drivername.' <br>'.' Mobile No:'. $mobile_no.' <br>'. ' Vehicle Name:'. $get_vehicle_type.' <br>'.' Vehicle Number:'. $get_registration_number.'";

                                                                    if ($date == $trip_start_dates[$i]) {
                                                                        $driver_edit = ' <span class="cursor-pointer" onclick="editDRIVERMODAL(' . $itinerary_plan_ID . ',' . $vendor_id . ',' . $driver_id . ')">
                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i> </span>';
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            //Vehicle unassigned itinerary
                                                            // If the range has not been processed, add it to the array
                                                            if (!isset($processed_ranges[$range_key])) {
                                                                $processed_ranges[$range_key] = [
                                                                    'unassigned_count' => 0
                                                                ];
                                                            }

                                                            // Vehicle is not assigned, show itinerary details for all vehicles of the same type
                                                            if ($date == $trip_start_dates[$i]) {

                                                                if ($vendor_id != $prev_vendor_id || $vehicle_type_id != $prev_vehicle_type_id):
                                                                    $processed_ranges[$range_key]['unassigned_count']++;
                                                                endif;

                                                                if ($string_itinerary_plan_IDs != '') {
                                                                    $string_itinerary_plan_IDs .= ',';
                                                                }
                                                                $string_itinerary_plan_IDs .= $itinerary_plan_ID;

                                                                if ($string_itinerary_quote_IDs != '') {
                                                                    $string_itinerary_quote_IDs .= ',';
                                                                }
                                                                $string_itinerary_quote_IDs .= $itinerary_quote_IDs[$i];

                                                                $show_assign_button = true;
                                                                $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                                $itinerary_details_not_assigned .= "<h6 class='text-blue-color mb-1 d-flex gap-2'>
                                                                        <a href='" . $_url . urlencode($itinerary_plan_ID) . "' target='_blank'> " . htmlspecialchars($itinerary_quote_IDs[$i]) . "
                                                                        </a> </h6>";
                                                            }

                                                            //$assigned_itinerary_key = $trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id;

                                                            if ($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id]) {
                                                                $_assigned_itinerary_plan_IDs = $ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id];

                                                                $assigned_trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'trip_end_date_and_time');

                                                                $assigned_trip_end_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'departure_location');

                                                                if (($assigned_trip_end_date_and_time < $trip_start_date_and_time) && ($date == $trip_start_dates[$i])):

                                                                    if ($_string_itinerary_plan_IDs != '') {
                                                                        $_string_itinerary_plan_IDs .= ',';
                                                                    }
                                                                    $_string_itinerary_plan_IDs .= $itinerary_plan_ID;

                                                                    if ($_string_itinerary_quote_IDs != '') {
                                                                        $_string_itinerary_quote_IDs .= ',';
                                                                    }
                                                                    $_string_itinerary_quote_IDs .= $itinerary_quote_IDs[$i];

                                                                    $show_assign_button = true;
                                                                    $_url1 = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                                    $combined_itinerary_details .= "<h6 class='text-blue-color mb-1 d-flex gap-2'> <a href='" . $_url . urlencode($itinerary_plan_ID) . "' target='_blank'> " . htmlspecialchars($itinerary_quote_IDs[$i]) . " </a> </h6>";

                                                                else:
                                                                    $combined_itinerary_details = "";
                                                                endif;
                                                            }
                                                        }
                                                    }

                                                endif;

                                                continue;
                                            } else {

                                                //Show Unassigned for the same itinerary
                                                if (!isset($processed_ranges[$range_key])) {
                                                    $processed_ranges[$range_key] = [
                                                        'unassigned_count' => 0
                                                    ];
                                                }
                                                // Vehicle is not assigned, show itinerary details for all vehicles of the same type
                                                if ($date == $trip_start_dates[$i]) {

                                                    if ($vendor_id != $prev_vendor_id || $vehicle_type_id != $prev_vehicle_type_id):
                                                        $processed_ranges[$range_key]['unassigned_count']++;
                                                    endif;

                                                    if ($string_itinerary_plan_IDs != '') {
                                                        $string_itinerary_plan_IDs .= ',';
                                                    }
                                                    $string_itinerary_plan_IDs .= $itinerary_plan_ID;

                                                    if ($string_itinerary_quote_IDs != '') {
                                                        $string_itinerary_quote_IDs .= ',';
                                                    }
                                                    $string_itinerary_quote_IDs .= $itinerary_quote_IDs[$i];

                                                    $show_assign_button = true;
                                                    $_url = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                    $itinerary_details_not_assigned .= "<h6 class='text-blue-color mb-1 d-flex gap-2'>
                                                                <a href='" . $_url . urlencode($itinerary_plan_ID) . "' target='_blank'>
                                                                    " . htmlspecialchars($itinerary_quote_IDs[$i]) . "
                                                                </a>
                                                            </h6>";

                                                    //echo  $itinerary_quote_IDs[$i] . "--" . $ASSIGNED_ITINERARY[$trip_start_dates[$i]] . "<br>";
                                                    //print_r($trip_start_date_and_time);
                                                    // echo "<br>";
                                                }

                                                //$assigned_itinerary_key = $trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id;

                                                if ($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id]) {


                                                    $_assigned_itinerary_plan_IDs = $ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id];

                                                    $assigned_trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'trip_end_date_and_time');

                                                    $assigned_trip_end_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($ASSIGNED_ITINERARY[$trip_start_dates[$i] .  '-vt_' . $vehicle_type_id . '-' . $vehicle_id], 'departure_location');


                                                    if (($assigned_trip_end_date_and_time < $trip_start_date_and_time) && ($date == $trip_start_dates[$i])):

                                                        if ($_string_itinerary_plan_IDs != '') {
                                                            $_string_itinerary_plan_IDs .= ',';
                                                        }
                                                        $_string_itinerary_plan_IDs .= $itinerary_plan_ID;

                                                        if ($_string_itinerary_quote_IDs != '') {
                                                            $_string_itinerary_quote_IDs .= ',';
                                                        }
                                                        $_string_itinerary_quote_IDs .= $itinerary_quote_IDs[$i];

                                                        $show_assign_button = true;
                                                        $_url1 = "latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=";
                                                        $combined_itinerary_details .= "<h6 class='text-blue-color mb-1 d-flex gap-2'>
                                                                <a href='" . $_url . urlencode($itinerary_plan_ID) . "' target='_blank'>
                                                                    " . htmlspecialchars($itinerary_quote_IDs[$i]) . "
                                                                </a>
                                                                </h6>";

                                                    else:
                                                        $combined_itinerary_details = "";
                                                    endif;
                                                }
                                            }
                                        }
                                    }

                                    if (!empty($itinerary_details) || !empty($itinerary_details_not_assigned)) {

                                        if (!$is_vehicle_assigned && !$is_driver_assigned) {
                                            $vehicle_class =   'class="not-assign-vehicle"';
                                            $background_color =  '';
                                        } else {
                                            $vehicle_class =   'class="not-assign-vehicle"';
                                            $background_color =  '#ffff99';
                                        }
                                        echo "<td>";
                                        // Show Assign Vehicle button if it's the starting day of an itinerary and not yet assigned
                                        if (!empty($itinerary_details_not_assigned) && empty($itinerary_details)) {
                                            $encoded_itinerary_plan_IDs = urlencode(str_replace(',', '|', $string_itinerary_plan_IDs));
                                            $encoded_itinerary_quote_IDs = urlencode(str_replace(',', '|', $string_itinerary_quote_IDs));

                                            echo "<div><br><button type='button' class='btn btn-sm btn-success waves-effect waves-light ps-2 mb-1'  onclick='showassignVehicleModal(\"$encoded_itinerary_plan_IDs\",$vendor_id,$vehicle_type_id,$vehicle_id, \"$encoded_itinerary_quote_IDs\")'><i class='ti ti-plus fw-bold fs-6 me-1'></i>Assign Vehicle</button>";
                                            echo "$itinerary_details_not_assigned";
                                            echo "</div>";
                                        } elseif (!empty($combined_itinerary_details)) {
                                            $_encoded_itinerary_plan_IDs = urlencode(str_replace(',', '|', $_string_itinerary_plan_IDs));
                                            $_encoded_itinerary_quote_IDs = urlencode(str_replace(',', '|', $_string_itinerary_quote_IDs));

                                            echo "<div > <br><button type='button' class='btn btn-sm btn-success waves-effect waves-light ps-2 mb-1'  onclick='showassignVehicleModal(\"$_encoded_itinerary_plan_IDs\",$vendor_id,$vehicle_type_id,$vehicle_id, \"$_encoded_itinerary_quote_IDs\",$_assigned_itinerary_plan_IDs)'><i class='ti ti-plus fw-bold fs-6 me-1'></i>Assign Vehicle</button>";
                                            echo "$combined_itinerary_details";
                                            echo " </div><hr>";
                                        } elseif (!empty($combined_assigned_itinerary_details)) {
                                            echo "<div class='arrival-vehicle' style='padding: 10px; border-radius: 0 0 5px 5px;'> 
                                                        <h5 class='mb-0'><span class='badge badge-primary trip-badge' style='background-color: #89c76f;float: right;'>Trip 1</span></h5> 
                                                        $combined_assigned_itinerary_details";
                                            echo "</div><hr>";
                                        }



                                        if ($show_driver_name) {
                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                            $get_registration_number = $fetch_data['registration_number'];
                                            $customer_whatsapp_no = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                            $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                            $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                            echo "<div  $assigned_class style='padding: 10px; border-radius: 0 0 5px 5px;'> ";
                                            if (!empty($combined_assigned_itinerary_details)) {
                                                echo "<h5 class='mb-0'><span class='badge badge-primary trip-badge' style='background-color: #89c76f;float: right;'>Trip 2</span></h5>";
                                            }
                                            echo  $itinerary_details;
                                            // If a driver is assigned, get the driver's name
                                            echo ' <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - ' . $drivername . '-' . $mobile_no . ' </h6>
                                                                    <div class="d-flex align-items-center">
                                                                        <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>      
                                                                    </div>
                                                                </div>
                                                               ' . $driver_edit . '
                                                            </div>
                                                            </div>';
                                        }

                                        echo "</td>";
                                    } else {
                                        // No itinerary or not assigned, leave the cell empty
                                        echo "<td></td>";
                                    }
                                }
                                echo "</tr>";
                                //print_r($processed_ranges);
                                // echo "<br>";
                                // die;

                                if ($vendor_id != $prev_vendor_id || $vehicle_type_id != $prev_vehicle_type_id):
                                    $additional_vehicle_needed = 0;
                                    foreach ($processed_ranges as $range_key => $counts) {
                                        $unassigned_count = $counts['unassigned_count'];
                                        //  echo "<br>";
                                        if ($unassigned_count > $available_vehicles) {
                                            $additional_vehicle_needed = $additional_vehicle_needed + ($unassigned_count - $available_vehicles);
                                        }
                                    }
                                    // echo $additional_vehicle_needed;
                                    // die;
                                    if ($additional_vehicle_needed > 0) {

                                        for ($k = 0; $k < $additional_vehicle_needed; $k++) {
                                            echo "<tr>";
                                            echo "<td>$vendor</td>";
                                            echo "<td>$vehicle_type </br><a href='javascript:void(0)' class='btn btn-label-primary waves-effect' onclick='showADDVEHICLEMODAL($vendor_id,$vehicle_type_id);' data-bs-dismiss='modal'>+ Add New Vehicle</a></td>";
                                            foreach ($dates as $date) {
                                                echo "<td></td>";
                                            }
                                            echo "</tr>";
                                        }
                                    }

                                endif;

                                $prev_vendor_id = $vendor_id;
                                $prev_vehicle_type_id = $vehicle_type_id;
                            }

                        else:
                            echo "<tr><td colspan='" . (count($dates) + 2) . "'>No Records found</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>

            </div>
        </div>
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">

        <script src="assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.min.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>
        <script>
            $(document).ready(function() {
                $.fn.dataTable.ext.errMode = 'none';

                if ($.fn.DataTable.isDataTable('#vehicle-availability-table')) {
                    $('#vehicle-availability-table').DataTable().destroy();
                }

                if ($('#vehicle-availability-table tbody tr').length > 0 &&
                    $('#vehicle-availability-table thead th').length === $('#vehicle-availability-table tbody tr:first-child td').length) {

                    $('#vehicle-availability-table').DataTable({
                        scrollX: true,
                        fixedColumns: {
                            leftColumns: 2
                        },
                        scrollY: '100vh',
                        fixedHeader: true,
                        paging: false,
                        ordering: false,
                        info: false,
                    });
                } else {
                    console.log('No data available or column count mismatch. DataTable initialization skipped.');
                }
            });
        </script>
    <?php elseif ($_GET['type'] == 'show_assign_vehicle_modal') :

        $vendor_id = $_GET['vendor_id'];
        $vehicle_type_id = $_GET['vehicle_type_id'];
        $vehicle_id = $_GET['vehicle_id'];
        $itinerary_plan_IDs = $_GET['itinerary_plan_IDs'];
        $itinerary_quote_IDs = $_GET['itinerary_quote_IDs'];
        $assigned_itinerary_plan_ID =   $_GET['assigned_itinerary_plan_ID'];
        //decode the URL-encoded string
        $decoded_itinerary_plan_IDs = urldecode($itinerary_plan_IDs);
        $decoded_itinerary_quote_IDs = urldecode($itinerary_quote_IDs);

        $itinerary_quote_IDs_array = explode('|', $decoded_itinerary_quote_IDs);
        $itinerary_plan_IDs_array = explode('|', $decoded_itinerary_plan_IDs);

        $vendor = getVENDOR_DETAILS($vendor_id, 'label');
        $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
        $registration_number = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_registration_number');

    ?>
        <form id="ajax_assign_driver_form" class="row g-3" action="" method="post" data-parsley-validate>

            <input type="hidden" name="hid_date_from" id="hid_date_from" value="" hidden />
            <input type="hidden" name="hid_date_to" id="hid_date_to" value="" hidden />
            <input type="hidden" name="hid_filter_vendor[]" id="hid_filter_vendor" value="" hidden />
            <input type="hidden" name="hid_filter_vendor_vehicle_types[]" id="hid_filter_vendor_vehicle_types" value="" hidden />
            <input type="hidden" name="hid_filter_agent[]" id="hid_filter_agent" value="" hidden />
            <input type="hidden" name="hid_filter_location[]" id="hid_filter_location" value="" hidden />

            <input type="hidden" name="vehicle_type_id" id="vehicle_type_id" value="<?= $vehicle_type_id; ?>" hidden />
            <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_id; ?>" hidden />
            <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?= $vehicle_id; ?>" hidden />

            <div class="text-center">
                <h4 class="mb-2" id="DRIVERFORMLabel"></h4>
            </div>
            <?php
            if (count($itinerary_plan_IDs_array) == 1 && $assigned_itinerary_plan_ID != "") :
                if ($assigned_itinerary_plan_ID != ""):
                    $assigned_trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($assigned_itinerary_plan_ID, 'trip_end_date_and_time');
                    $destination_location_of_assigned_itinerary = get_ITINEARY_CONFIRMED_PLAN_DETAILS($assigned_itinerary_plan_ID, 'departure_location');
                    $destination_location_latitude_of_assigned_itinerary = getSTOREDLOCATIONDETAILS($destination_location_of_assigned_itinerary, 'location_latitude_from_location_name');
                    $destination_location_longtitude_of_assigned_itinerary = getSTOREDLOCATIONDETAILS($destination_location_of_assigned_itinerary, 'location_longtitude_from_location_name');
                endif;
                $start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($$itinerary_plan_IDs_array[0], 'trip_end_date_and_time');
                $arrival_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_IDs_array[0], 'arrival_location');
                $arrival_location_latitude = getSTOREDLOCATIONDETAILS($arrival_location, 'location_latitude_from_location_name');
                $arrival_location_longtitude = getSTOREDLOCATIONDETAILS($arrival_location, 'location_longtitude_from_location_name');

                $required_travel_time_and_distance = calculateDistanceAndDuration($destination_location_latitude_of_assigned_itinerary, $destination_location_longtitude_of_assigned_itinerary, $arrival_location_latitude, $arrival_location_longtitude, 1);
                $distance = $required_travel_time_and_distance['distance'];
                $required_travel_time  = $required_travel_time_and_distance['duration'];

                // Convert the dates to Unix timestamps
                $start_timestamp = strtotime($assigned_trip_end_date_and_time);
                $end_timestamp = strtotime($endstart_date_and_time_date);

                // Calculate the time difference in seconds
                $time_difference_in_seconds = $end_timestamp - $start_timestamp;

                // Convert the time difference to hours
                $hours_difference = $time_difference_in_seconds / 3600;  // 3600 seconds in 1 hour
            ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Warning!</strong> The time difference between the itineraries is not enough to travel to the destination!!<br>
                    <?php if ($distance && $required_travel_time): ?>
                        It will took around <?= $required_travel_time ?> at the speed of <?= getGLOBALSETTING('itinerary_local_speed_limit') ?>KM/Hr to reach the destination. (<?= $distance ?>)
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($assigned_itinerary_plan_ID != ""): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Warning!</strong> The time difference between the some of the itineraries to be assigned is not enough to travel to the destination!!<br>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif;
            ?>


            <div class="col-12">
                <label class="driver-text-label w-100" for="vendor">Vendor </label>
                <div class="form-group">
                    <select id="vendor" name="vendor" class="form-select form-control" disabled>
                        <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="">Vehicle Type</label>
                <div class="form-group">
                    <select id="vendor_vehicle_type" name="vehicle_type" class="form-select form-control" disabled>
                        <?= getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'select') ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mb-2">

                <?php if (count($itinerary_plan_IDs_array) == 1) : ?>
                    <label class="w-100" for="itinerary_quote_ID">Itinerary: <span class="text-primary"><?= $itinerary_quote_IDs_array[0] ?></span></label>
                    <input type="hidden" name="itinerary_quote_ID" id="itinerary_quote_ID" value="<?= $itinerary_quote_IDs_array[0] ?>" hidden />
                    <input type="hidden" name="itineraryPlanId" value="<?= $itinerary_plan_IDs_array[0]; ?>" hidden />
                <?php else: ?>
                    <label class="form-label" for="itineraryPlanId">Itinerary<span class="text-danger"> *</span></label>
                    <select class="form-control form-select" name="itineraryPlanId" id="itineraryPlanId" data-parsley-trigger="keyup">
                        <option value="">Choose the itinerary</option>
                        <?php for ($i = 0; $i < count($itinerary_plan_IDs_array); $i++): ?>
                            <option value="<?= $itinerary_plan_IDs_array[$i] ?>"><?= $itinerary_quote_IDs_array[$i] ?></option>
                        <?php endfor; ?>
                    </select>
                <?php endif; ?>
            </div>
            <?php if (count($itinerary_plan_IDs_array) == 1) : ?>
                <div class="col-12 mb-2">
                    <?php $implode_array_of_itinerary_plan_IDs  = implode(',', $itinerary_plan_IDs_array); ?>
                    <label class="w-100" for="primary_customer_name">Primary Guest Name: <span class="text-primary"><?= get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($implode_array_of_itinerary_plan_IDs, 'primary_customer_name'); ?></span></label>
                </div>
            <?php else: ?>
                <div class="col-12 mb-2 mt-3">
                    <label class="w-100" for="primary_customer_name_select">Primary Guest Name:
                        <span class="text-primary" id="primary_customer_name_select">Choose the Itinerary to View the Guest</span>
                    </label>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <label class="driver-text-label w-100" for="vendor_id">Driver <span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="selected_driver_id" name="driver_id" class="form-select form-control" required>
                        <?php
                        if (count($itinerary_plan_IDs_array) == 1) :
                            echo get_CONFIRMED_ITINERARY_UNASSIGNED_DRIVER_DETAILS($itinerary_plan_IDs_array[0], $vendor_id, 'select', $driver_id);
                        else: ?>
                            <option value="">Choose the Driver</option>
                        <?php endif; ?>
                    </select>

                </div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="staff_form_submit_btn">Assign</button>
            </div>
        </form>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {

                $('#vendor_vehicle_type').selectize();
                $('#vendor_branch').selectize();
                $('#itineraryPlanId').selectize();

                var selected_driver_Select = $('#selected_driver_id').selectize();

                $('#itineraryPlanId').change(function() {
                    var itinerary_plan_ID = $(this).val();
                    var selected_driver_Selectize = selected_driver_Select[0].selectize;

                    // First AJAX request for drivers
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_get_vendor_drivers.php?type=selectize_drivers',
                        data: {
                            vendor_id: '<?= $vendor_id ?>',
                            itinerary_plan_ID: itinerary_plan_ID
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Append the response to the dropdown.
                            selected_driver_Selectize.clear();
                            selected_driver_Selectize.clearOptions();
                            selected_driver_Selectize.addOption(response);
                        }
                    });

                    // Second AJAX request for the customer name
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/ajax_get_customer_name.php', // Replace with the correct path to your PHP script
                        data: {
                            itinerary_plan_ID: itinerary_plan_ID
                        },
                        success: function(response) {
                            // Update the primary_customer_name span with the customer name
                            $('#primary_customer_name_select').text(response);
                        }
                    });
                });


                //AJAX FORM SUBMIT
                $("#ajax_assign_driver_form").submit(function(event) {
                    var form = $('#ajax_assign_driver_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_assignemnt.php?type=assign_vehicle',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to Assign Vehicle', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#assignvehicleFORM').modal('hide');
                            TOAST_NOTIFICATION('success', 'Vehicle Assigned Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        }
                        showUPDATED_AVAILABILITYCHART();
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });

            function showUPDATED_AVAILABILITYCHART() {

                var date_from = $('#hid_date_from').val();
                var date_to = $('#hid_date_to').val();
                var vendor_id = $('#hid_filter_vendor').val();
                var vehicle_type_id = $('#hid_filter_vendor_vehicle_types').val();
                var agent_id = $('#hid_filter_agent').val();
                var location_id = $('#hid_filter_location').val();

                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_form",
                    data: {
                        date_from: date_from,
                        date_to: date_to,
                        vendor_id: vendor_id,
                        vehicle_type_id: vehicle_type_id,
                        agent_id: agent_id,
                        location_id: location_id
                    },
                    success: function(response) {

                        $('#vehicle_availability_list').html(response);
                    }
                });

            }
        </script>
    <?php elseif ($_GET['type'] == 'show_reassign_driver_modal') :

        $vendor_id = $_GET['vendor_id'];
        $driver_id = $_GET['driver_id'];
        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];

        $vendor = getVENDOR_DETAILS($vendor_id, 'label');
        // $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
        //$registration_number = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_registration_number');

    ?>
        <form id="ajax_reassign_driver_form" class="row g-3" action="" method="post" data-parsley-validate>

            <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_id; ?>" hidden />
            <input type="hidden" name="itineraryPlanId" id="itineraryPlanId" value="<?= $itinerary_plan_ID; ?>" hidden />

            <input type="hidden" name="hid_date_from" id="hid_date_from" value="" hidden />
            <input type="hidden" name="hid_date_to" id="hid_date_to" value="" hidden />
            <input type="hidden" name="hid_filter_vendor[]" id="hid_filter_vendor" value="" hidden />
            <input type="hidden" name="hid_filter_vendor_vehicle_types[]" id="hid_filter_vendor_vehicle_types" value="" hidden />
            <input type="hidden" name="hid_filter_agent[]" id="hid_filter_agent" value="" hidden />
            <input type="hidden" name="hid_filter_location[]" id="hid_filter_location" value="" hidden />

            <div class="text-center">
                <h4 class="mb-2" id="DRIVERFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>

            <div class="col-12">
                <label class="driver-text-label w-100" for="vendor_id">Driver<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <select id="driver_id" name="driver_id" class="form-select form-control" required>
                        <?= get_CONFIRMED_ITINERARY_UNASSIGNED_DRIVER_DETAILS($itinerary_plan_ID, $vendor_id, 'select', $driver_id); ?>
                    </select>
                </div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="staff_form_submit_btn">Re-Assign</button>
            </div>
        </form>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#driver_id').selectize();

                var driver_Select = $('#driver_id').selectize();

                //AJAX FORM SUBMIT
                $("#ajax_reassign_driver_form").submit(function(event) {
                    var form = $('#ajax_reassign_driver_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_assignemnt.php?type=reassign_driver',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to Assign Driver', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#editDRIVERDATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Driver Assigned Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        }
                        showUPDATED_AVAILABILITYCHART();
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });

            function showUPDATED_AVAILABILITYCHART() {

                var date_from = $('#hid_date_from').val();
                var date_to = $('#hid_date_to').val();
                var vendor_id = $('#hid_filter_vendor').val();
                var vehicle_type_id = $('#hid_filter_vendor_vehicle_types').val();
                var agent_id = $('#hid_filter_agent').val();
                var location_id = $('#hid_filter_location').val();

                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_form",
                    data: {
                        date_from: date_from,
                        date_to: date_to,
                        vendor_id: vendor_id,
                        vehicle_type_id: vehicle_type_id,
                        agent_id: agent_id,
                        location_id: location_id
                    },
                    success: function(response) {

                        $('#vehicle_availability_list').html(response);
                    }
                });

            }
        </script>

    <?php elseif ($_GET['type'] == 'show_add_driver_modal') :
        if ($logged_user_level == 2):
            $vendor_id = $logged_vendor_id;
        else:
            $vendor_id = '';
        endif;
    ?>
        <h4 class="text-center" id="NEWDRIVERFORMLabel">Add Driver</h4>
        <form id="ajax_add_driver_form" class="row g-3" action="" method="post" data-parsley-validate>

            <input type="hidden" name="hid_date_from" id="hid_date_from" value="" hidden />
            <input type="hidden" name="hid_date_to" id="hid_date_to" value="" hidden />
            <input type="hidden" name="hid_filter_vendor[]" id="hid_filter_vendor" value="" hidden />
            <input type="hidden" name="hid_filter_vendor_vehicle_types[]" id="hid_filter_vendor_vehicle_types" value="" hidden />
            <input type="hidden" name="hid_filter_agent[]" id="hid_filter_agent" value="" hidden />
            <input type="hidden" name="hid_filter_location[]" id="hid_filter_location" value="" hidden />

            <div class="col-md-12 mb-2">
                <label class="form-label" for="driver_vendor_name">Vendor<span class=" text-danger">
                        *</span></label>
                <?php if ($vendor_id == ""): ?>
                    <select id="driver_vendor_name" name="vendor_name" required class="form-control form-select">
                        <?= getVENDOR_DETAILS('', 'select'); ?>
                    </select>
                <?php elseif ($logged_user_level == 2): ?>
                    <select id="driver_vendor_name" name="vendor_name_disabled" class="form-control form-select" disabled>
                        <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                    </select>
                    <input type="hidden" name="vendor_name" value="<?= $vendor_id ?>">
                <?php endif; ?>
            </div>



            <div class="col-md-12 mb-2">
                <label class="form-label" for="driver_vehicle_type">Vehicle Type
                    <span class=" text-danger"> *</span></label>
                <?php if ($vendor_id == ""): ?>
                    <select id="driver_vehicle_type" name="vehicle_type" required class="form-control form-select">
                        <option value="">Choose Vehicle Type</option>
                    </select>
                <?php else: ?>
                    <select id="driver_vehicle_type" name="vehicle_type" required class="form-control form-select">
                        <?= getVENDOR_VEHICLE_TYPES($vendor_id, '', 'select'); ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col-md-12">
                <label class="driver-text-label w-100" for="driver_name">Driver Name<span class=" text-danger">
                        *</span></label>
                <div class="form-group">
                    <input type="text" name="driver_name" id="driver_name" placeholder="Driver Name" value="" required="" autocomplete="off" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <!-- <span class=" text-danger">*</span> -->
                <label class="driver-text-label w-100" for="driver_primary_mobile_number">Primary Mobile
                    Number<span class=" text-danger">
                        *</span></label>
                <div class="form-group">
                    <input type="tel" id="driver_primary_mobile_number" name="driver_primary_mobile_number" class="form-control parsley-success" placeholder="Primary Mobile Number" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_driver_primary_number="" data-parsley-check_driver_primary_number-message="Entered Mobile Number Already Exists" autocomplete="off" required="" maxlength="10" data-parsley-id="17">
                    <input type="hidden" name="old_driver_primary_mobile_number" id="old_driver_primary_mobile_number" data-parsley-type="number">
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" id="btn_driver_submit" class="btn btn-primary">Save</button>
            </div>
        </form>

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#driver_vendor_name').selectize();
                $('#driver_vehicle_type').selectize();

                var driver_vendor_vehicle_types_Select = $('#driver_vehicle_type').selectize();
                $('#driver_vendor_name').change(function() {
                    var vendor_id = $(this).val();
                    var driver_vendor_vehicle_types_Selectize = driver_vendor_vehicle_types_Select[0].selectize;
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_get_vendor_vehicle_types.php?type=selectize_vehicle_types',
                        data: {
                            vendor_id: vendor_id,
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Append the response to the dropdown.
                            driver_vendor_vehicle_types_Selectize.clear();
                            driver_vendor_vehicle_types_Selectize.clearOptions();
                            driver_vendor_vehicle_types_Selectize.addOption(response);
                        }
                    });

                });

                //CHECK DUPLICATE DRIVER MOBILE NUMBER
                // $('#driver_primary_mobile_number').parsley();
                // var old_driver_primary_mobile_numberDETAIL = document.getElementById("old_driver_primary_mobile_number")
                //     .value;
                // var driver_primary_mobile_number = $('#driver_primary_mobile_number').val();
                // window.ParsleyValidator.addValidator('check_driver_primary_number', {
                //     validateString: function(value) {
                //         return $.ajax({
                //             url: 'engine/ajax/__ajax_check_driver_mobilenum.php',
                //             method: "POST",
                //             data: {
                //                 driver_primary_mobile_number: value,
                //                 old_driver_primary_mobile_number: old_driver_primary_mobile_numberDETAIL
                //             },
                //             dataType: "json",
                //             success: function(data) {
                //                 return true;
                //             }
                //         });
                //     }
                // });


                //AJAX FORM SUBMIT
                $("#ajax_add_driver_form").submit(function(event) {
                    var form = $('#ajax_add_driver_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_assignemnt.php?type=add_driver',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE

                            if (response.errors.driver_name_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Name Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.vehicle_type_required) {
                                TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.vendor_name_required) {
                                TOAST_NOTIFICATION('warning', 'Vendor is Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.driver_primary_mobile_number_required) {
                                TOAST_NOTIFICATION('warning', 'Mobile No Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            }
                            $("#btn_driver_submit").prop('disabled', false);
                        } else {
                            //SUCCESS RESPOSNE
                            $('#addNEWDRIVER').modal('hide');
                            if (!response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to Add Driver', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                TOAST_NOTIFICATION('success', 'Driver Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });
        </script>

    <?php elseif ($_GET['type'] == 'show_add_vehicle_modal') :
        $date_from = $_GET['date_from'];
        $date_to = $_GET['date_to'];
        $vehicle_type_id = $_GET['vehicle_type_id'];
        $vendor_id = $_GET['vendor_id'];
        if ($logged_user_level == 2):
            if ($vendor_id == ""):
                $vendor_id = $logged_vendor_id;
            else:
                $vendor_id = $_GET['vendor_id'];
            endif;
        else:
            $vendor_id = $_GET['vendor_id'];
        endif;

    ?>
        <style>
            .pac-container.pac-logo.hdpi,
            .pac-container.pac-logo {
                z-index: 9999999;
            }

            .easy-autocomplete.eac-square {
                width: 100% !important;
                /* Ensure the div has the correct width */
            }

            .easy-autocomplete input[type="text"] {
                width: 100%;
                /* Ensure the input field has the correct width */
            }
        </style>
        <h4 class="text-center" id="NEWVEHICLEFORMLabel">Add Vehicle</h4>
        <form id="ajax_add_vehicle_form" class="row g-3" action="" method="post" data-parsley-validate>

            <input type="hidden" name="hid_date_from" id="hid_date_from" value="" hidden />
            <input type="hidden" name="hid_date_to" id="hid_date_to" value="" hidden />
            <input type="hidden" name="hid_filter_vendor[]" id="hid_filter_vendor" value="" hidden />
            <input type="hidden" name="hid_filter_vendor_vehicle_types[]" id="hid_filter_vendor_vehicle_types" value="" hidden />
            <input type="hidden" name="hid_filter_agent[]" id="hid_filter_agent" value="" hidden />
            <input type="hidden" name="hid_filter_location[]" id="hid_filter_location" value="" hidden />

            <div class="col-md-6 mb-2">
                <label class="form-label" for="vendor_name">Vendor<span class=" text-danger">
                        *</span></label>
                <?php if ($vendor_id == ""): ?>
                    <select id="vendor_name" name="vendor_name" required class="form-control form-select">
                        <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                    </select>
                <?php elseif ($logged_user_level == 2): ?>
                    <select id="vendor_name" name="vendor_name_disabled" class="form-control form-select" disabled>
                        <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                    </select>
                    <input type="hidden" name="vendor_name" value="<?= $vendor_id ?>">

                <?php endif; ?>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label" for="vendor_branch">Vendor Branch<span class=" text-danger">
                        *</span></label>
                <?php if ($vendor_id == ""): ?>
                    <select id="vendor_branch" name="vendor_branch" required class="form-control form-select">
                        <option value="">Choose Branch</option>
                    </select>
                <?php else: ?>
                    <select id="vendor_branch" name="vendor_branch" required class="form-control form-select">
                        <?= getVENDORBRANCHDETAIL('', $vendor_id, 'select') ?>
                    </select>
                <?php endif; ?>
            </div>

            <div class="col-md-6 mb-2">
                <label class="form-label" for="vehicle_type">Vehicle Type<span class=" text-danger">
                        *</span></label>
                <?php if ($vehicle_type_id == "" && $vendor_id == ""): ?>
                    <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                        <option value="">Choose Vehicle Type</option>
                    </select>
                <?php else: ?>
                    <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                        <?= getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'select'); ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label" for="registration_number">Registration Number<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="Registration Number" value="" required="" data-parsley-check_registration_number="" data-parsley-check_registration_number-message="Entered Registration Number Already Exists" data-parsley-pattern="^[A-Z]{2}\s?[0-9]{1,2}\s?[A-Z]{1,2}\s?[0-9]{1,4}\s?[A-Z]{0,1}[0-9]{0,4}$">

                    <input type="hidden" name="old_registration_number" id="old_registration_number" value="">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label" for="chassis_number">Vehicle Origin <span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="vehicle_orign" id="vehicle_orign" class="form-control" placeholder="Choose Vehicle Origin" value="" required="" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label" for="vehicle_fc_expiry_date">Vehicle Expiry Date <span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="vehicle_fc_expiry_date" id="vehicle_fc_expiry_date" class="form-control flatpickr-input" placeholder="Vehicle Expiry Date" value="" required="" readonly="readonly">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label" for="insurance_start_date">Insurance Start Date<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="insurance_start_date" id="insurance_start_date" class="form-control flatpickr-input" placeholder="Insurance Start Date" value="" required="" readonly="readonly">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <label class="form-label" for="insurance_end_date">Insurance End Date<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" name="insurance_end_date" id="insurance_end_date" class="form-control flatpickr-input" placeholder="Insurance End Date" value="" required="" readonly="readonly">
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" id="btn_vehicle_submit" class="btn btn-primary">Save</button>
            </div>
        </form>


        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#vendor_name').selectize();
                $('#vendor_branch').selectize();
                $('#vehicle_type').selectize();

                flatpickr('#insurance_start_date', {
                    dateFormat: 'd-m-Y', // Change this format to your desired date format
                    // Other options go here
                    onChange: function(selectedDates, dateStr) {
                        // Set minimum date for end date based on the selected start date
                        endDatePicker.set('minDate', dateStr);
                    }
                });

                const endDatePicker = flatpickr('#insurance_end_date', {
                    dateFormat: 'd-m-Y', // Change this format to your desired date format
                    // Other options go here
                });

                flatpickr("#vehicle_fc_expiry_date", {
                    dateFormat: "d-m-Y", // Format: day-month-year
                    altInput: true,
                    altFormat: "d-m-Y"
                });

                var vendor_vehicle_types_Select = $('#vehicle_type').selectize();

                $('#vendor_name').change(function() {
                    var vendor_id = $(this).val();

                    var vendor_vehicle_types_Selectize = vendor_vehicle_types_Select[0].selectize;
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_get_vendor_vehicle_types.php?type=selectize_vehicle_types',
                        data: {
                            vendor_id: vendor_id,
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Append the response to the dropdown.
                            vendor_vehicle_types_Selectize.clear();
                            vendor_vehicle_types_Selectize.clearOptions();
                            vendor_vehicle_types_Selectize.addOption(response);
                        }
                    });
                    chooseVENDOR_BRANCH(vendor_id);
                });

                var vehicle_orign = {
                    url: function(phrase) {
                        return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
                                phrase) +
                            "&format=json&type=source";
                    },
                    getValue: "get_source_location",
                    list: {
                        match: {
                            enabled: true
                        },
                        onChooseEvent: function() {
                            getSTATE_CITY_COUNTRY();
                        },
                        hideOnEmptyPhrase: true
                    },
                    theme: "square"
                };
                $("#vehicle_orign").easyAutocomplete(vehicle_orign);

                //CHECK DUPLICATE REGISTRATION NUMBER
                $('#registration_number').parsley();
                var old_registration_number_DETAIL = document.getElementById("old_registration_number").value;
                var registration_number = $('#registration_number').val();
                window.ParsleyValidator.addValidator('check_registration_number', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_vehicle_duplication.php',
                            method: "POST",
                            data: {
                                type: "registration_number",
                                registration_number: value,
                                old_registration_number: old_registration_number_DETAIL,
                                VENDOR_ID: '<?= $VENDOR_ID; ?>'
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_add_vehicle_form").submit(function(event) {
                    var form = $('#ajax_add_vehicle_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/ajax_manage_confirmed_itinerary_vehicle_assignemnt.php?type=add_vehicle',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE

                            if (response.errors.vehicle_type_required) {
                                TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.vendor_name_required) {
                                TOAST_NOTIFICATION('warning', 'Vendor is Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errros.registration_number_required) {
                                TOAST_NOTIFICATION('warning', 'Registration Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.vehicle_fc_expiry_date_required) {
                                TOAST_NOTIFICATION('warning', 'Vehicle FC Expiry Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.insurance_start_date_required) {
                                TOAST_NOTIFICATION('warning', 'Insurance Start Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.insurance_end_date_required) {
                                TOAST_NOTIFICATION('warning', 'Insurance End Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.vehicle_location_required) {
                                TOAST_NOTIFICATION('warning', 'Vehicle Orign Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                            $("#btn_vehicle_submit").prop('disabled', false);
                        } else {
                            //SUCCESS RESPOSNE
                            $('#addNEWVEHICLE').modal('hide');
                            if (!response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to Add Vehicle', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                TOAST_NOTIFICATION('success', 'Vehicle Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                            show_UPDATED_AVAILABILITY_CHART();
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
                });
            });

            function chooseVENDOR_BRANCH(vendor_id) {
                var vendor_branch_Select = $('#vendor_branch').selectize();
                var vendor_branch_Selectize = vendor_branch_Select[0].selectize;
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_get_vendor_branches.php?type=selectize_vendor_branch',
                    data: {
                        vendor_id: vendor_id,
                        date_from: '<?= $date_from ?>',
                        date_to: '<?= $date_to ?>',
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Append the response to the dropdown.
                        vendor_branch_Selectize.clear();
                        vendor_branch_Selectize.clearOptions();
                        vendor_branch_Selectize.addOption(response);
                    }
                });

            }

            function show_UPDATED_AVAILABILITY_CHART() {
                var date_from = $('#hid_date_from').val();
                var date_to = $('#hid_date_to').val();
                var vendor_id = $('#hid_filter_vendor').val();
                var vehicle_type_id = $('#hid_filter_vendor_vehicle_types').val();
                var agent_id = $('#hid_filter_agent').val();
                var location_id = $('#hid_filter_location').val();
                alert(vendor_id);
                alert(vehicle_type_id);
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_form",
                    data: {
                        date_from: date_from,
                        date_to: date_to,
                        vendor_id: vendor_id,
                        vehicle_type_id: vehicle_type_id,
                        agent_id: agent_id,
                        location_id: location_id
                    },
                    success: function(response) {
                        $('#vehicle_availability_list').html(response);
                    }
                });

            }

            function SDcopyAndOpenWhatsApp(phone, message) {
                const decodedMessage = decodeURIComponent(message).replace(/\+/g, ' ');
                navigator.clipboard.writeText(decodedMessage).then(function() {
                    TOAST_NOTIFICATION('success', 'Driver Details copied successfully!', 'Success !!!',
                        '', '', '', '', '', '', '', '', '');

                    const whatsapp_url = `whatsapp://send?phone=${phone}&text=${message}`;
                    window.location.href = whatsapp_url;
                }).catch(function(err) {
                    TOAST_NOTIFICATION('error', 'Error!!!', 'Unable to Copy Text!!!', '', '', '', '', '', '', '', '', '');
                });
            }

            function SLcopyAndOpenWhatsApp(phone, message) {
                const decodedMessage = decodeURIComponent(message).replace(/\+/g, ' ');
                navigator.clipboard.writeText(decodedMessage).then(function() {
                    TOAST_NOTIFICATION('success', 'Share Link copied successfully!', 'Success !!!',
                        '', '', '', '', '', '', '', '', '');

                    const whatsapp_url = `whatsapp://send?phone=${phone}&text=${message}`;
                    window.location.href = whatsapp_url;
                }).catch(function(err) {
                    TOAST_NOTIFICATION('error', 'Error!!!', 'Unable to Copy Text!!!', '', '', '', '', '', '', '', '', '');
                });
            }
        </script>


<?php endif;
else :
    echo "Request Ignored";
endif;
