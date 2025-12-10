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

?><div class="d-flex justify-content-end"> <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showFilter();" data-bs-dismiss="modal"><i class="tf-icons ti ti-filter ti-xs me-1"></i> Filter</a></div>
    <div class="row mt-3 d-none" id="filterSection">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-3">
                    <h5 class="card-title">Filter</h5>
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label" for="date_from">Date from<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="date_from" name="date_from" value="" required />
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="date_to">Date To<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="date_to" name="date_to" value="" required />
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="filter_vendor">Vendor <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <?php if ($logged_user_level == 1): ?>
                                    <select name="filter_vendor" id="filter_vendor" class="form-select form-control" required multiple>
                                        <?= getVENDOR_DETAILS($logged_vendor_id, 'select'); ?>
                                    </select>
                                <?php else: ?>
                                    <select name="filter_vendor[]" id="filter_vendor" class="form-select form-control" multiple disabled>
                                        <option value="<?= $logged_vendor_id ?>" selected><?= getVENDOR_DETAILS($logged_vendor_id, 'label'); ?></option>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label" for="filter_vendor_vehicle_types">Vehicle Type <span class="text-danger">*</span></label>
                            <div class="form-group">
                                <select name="filter_vendor_vehicle_types" id="filter_vendor_vehicle_types" class="form-select form-control" required multiple>
                                    <?php if ($logged_user_level == 1): ?>
                                        <option value=""> Choose Vehicle Types</option>
                                    <?php else: ?>
                                        <?= getVENDOR_VEHICLE_TYPES($logged_vendor_id, '', 'select') ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php if ($logged_user_level == 1): ?>
                            <div class="col-md-3">
                                <label class="form-label" for="filter_agent">Agent <span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select name="filter_agent" id="filter_agent" class="form-select form-control" required multiple>
                                        <?= getAGENT_details('', '', 'select') ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-3">
                            <label class="form-label" for="filter_location"> Location <span class="text-danger">*</span></label>
                            <!--<input type="text" name="source_location" id="source_location" class="form-control">-->
                            <div class="form-group">
                                <select name="filter_location" id="filter_location" class="form-select form-control" required multiple>
                                    <?= getSOURCE_LOCATION_DETAILS($selected_value, 'select_source'); ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <a href="vehicle_availability_chart.php" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card p-0">
                <div class="card-header pb-3 d-flex justify-content-between">

                    <div class="col-md-5">
                        <h5 class="card-title mb-3 mt-2">Vehicle Availability Chart</h5>
                    </div>
                    <div class="col-md-auto text-end">
                        <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showADDVEHICLEMODAL();" data-bs-dismiss="modal">+ Add New Vehicle</a>
                        <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showADDDRIVERMODAL();" data-bs-dismiss="modal">+ Add New Driver</a>
                    </div>
                </div>

                <?php
                // Get the first and last day of the current month using strtotime
                $date_from = date('Y-m-01');
                $date_to = date('Y-m-t');
                if ($logged_user_level == 2):
                    $filter_by_vendor_id = " AND (CNF_ITINEARY_VEHICLE_VOUCER.`vendor_id`= '$logged_vendor_id') ";
                else:
                    $filter_by_vendor_id = "";
                endif;
                $startOfMonth = strtotime(date('Y-m-01'));  // First day of the current month
                $endOfMonth = strtotime(date('Y-m-t'));     // Last day of the current month

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


                <div class="card-body" id="vehicle_availability_list">
                    <div class="text-nowrap table-responsive table-bordered">
                        <table id="vehicle-availability-table">
                            <thead>
                                <tr>
                                    <th scope="col">Vendor</th>
                                    <th scope="col">Vehicle Type</th>
                                    <!-- Loop through PHP array and display date headers -->
                                    <?php foreach ($dates as $date): ?>
                                        <th><?php echo date('d-M Y', strtotime($date)); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                $selected_query = sqlQUERY_LABEL(" SELECT
                                VEHICLE.`vendor_id`,
                                VEHICLE.`vehicle_type_id`,
                                VEHICLE.`vehicle_id`,
                                VEHICLE.`registration_number`,
                                GROUP_CONCAT(CNF_ITINEARY.`itinerary_plan_ID` ORDER BY CNF_ITINEARY.`trip_start_date_and_time` ASC) AS itinerary_plan_IDs,
                                GROUP_CONCAT(DATE_FORMAT(CNF_ITINEARY.`trip_start_date_and_time`, '%Y-%m-%d') ORDER BY CNF_ITINEARY.`trip_start_date_and_time` ASC) AS trip_start_dates,
                                GROUP_CONCAT(DATE_FORMAT(CNF_ITINEARY.`trip_end_date_and_time`, '%Y-%m-%d') ORDER BY CNF_ITINEARY.`trip_start_date_and_time` ASC) AS trip_end_dates,
                                GROUP_CONCAT(CNF_ITINEARY.`arrival_location` ORDER BY CNF_ITINEARY.`trip_start_date_and_time` ASC) AS arrival_locations,
                                GROUP_CONCAT(CNF_ITINEARY.`departure_location` ORDER BY CNF_ITINEARY.`trip_start_date_and_time` ASC) AS departure_locations,
                                GROUP_CONCAT(CNF_ITINEARY.`itinerary_quote_ID` ORDER BY CNF_ITINEARY.`trip_start_date_and_time` ASC) AS itinerary_quote_IDs,
                                route_data.route_arrival_locations,
                                route_data.route_departure_locations,
                                COUNT(DISTINCT CNF_ITINEARY.`itinerary_plan_ID`) AS confirmed_itinerary_count
                            FROM
                                `dvi_vehicle` VEHICLE
                        
                            LEFT JOIN `dvi_confirmed_itinerary_plan_vehicle_voucher_details` CNF_ITINEARY_VEHICLE_VOUCER
                                ON CNF_ITINEARY_VEHICLE_VOUCER.`vendor_id` = VEHICLE.`vendor_id`
                                AND CNF_ITINEARY_VEHICLE_VOUCER.`vehicle_type_id` = VEHICLE.`vehicle_type_id`
                                AND CNF_ITINEARY_VEHICLE_VOUCER.`vehicle_booking_status` = '4'
                                AND CNF_ITINEARY_VEHICLE_VOUCER.`status` = '1'
                                AND CNF_ITINEARY_VEHICLE_VOUCER.`deleted` = '0'
                        
                            LEFT JOIN `dvi_confirmed_itinerary_plan_details` CNF_ITINEARY
                                ON CNF_ITINEARY.`itinerary_plan_ID` = CNF_ITINEARY_VEHICLE_VOUCER.`itinerary_plan_id`
                                AND CNF_ITINEARY.`status` = '1'
                                AND CNF_ITINEARY.`deleted` = '0'
                                AND (CNF_ITINEARY.`itinerary_preference` = '2' OR CNF_ITINEARY.`itinerary_preference` = '3')
                                AND ((DATE(CNF_ITINEARY.`trip_start_date_and_time`) BETWEEN '$date_from' AND '$date_to') OR (DATE(CNF_ITINEARY.`trip_end_date_and_time`) BETWEEN '$date_from' AND '$date_to')) 
                        
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
                                VEHICLE.`status` = '1'
                                AND VEHICLE.`deleted` = '0'
                                {$filter_by_vendor_id}
                        
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
                                        $confirmed_itineraries = $fetch_data['confirmed_itinerary_count'];
                                        //echo $available_vehicles . "--" . $confirmed_itineraries;
                                        $processed_ranges = [];

                                        echo "<tr>";
                                        echo "<td>$vendor</td>";
                                        echo "<td>$vehicle_type </br><span class='text-blue-color'> $registration_number</span></td>";
                                        /* echo $vendor . "-" . $vehicle_type . "-" . $registration_number . "<br>";

                                        print_r($itinerary_plan_IDs);
                                        echo "<br>";
                                        print_r($trip_start_dates);
                                        echo "<br>";
                                        print_r($trip_end_dates);
                                        echo "<br>";*/
                                        // Loop through each date in the month
                                        foreach ($dates as $date) {
                                            $date_timestamp = strtotime($date);

                                            $itinerary_details = '';
                                            $share_whatsapp = '';
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
                                                    AND `vendor_vehicle_type_id` = '{$fetch_data['vehicle_type_id']}' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `assigned_vehicle_status` != '0' 
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
                                                                                $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                                $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                                $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                                $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                                $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

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
                                                                            $assigned_class = 'class="arrival-vehicle"';
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
                                                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                            $get_registration_number = $fetch_data['registration_number'];
                                                                            $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
                                                                            $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                            $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                                                            $share_whatsapp = '
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                            </div>';
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
                                                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                            $get_registration_number = $fetch_data['registration_number'];
                                                                            $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, requesttype: 'driver_name');
                                                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
                                                                            $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                            $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                                                            $share_whatsapp = '
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>

                                                                            </div>';
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
                                                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                            $get_registration_number = $fetch_data['registration_number'];
                                                                            $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
                                                                            $share_whatsapp = '
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                            </div>';
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
                                                                                $mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                                $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');

                                                                                $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                                $get_registration_number = $fetch_data['registration_number'];
                                                                                $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                                $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                                $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                                $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                                $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                                                                $combined_assigned_itinerary_details .= ' <div class="d-flex">
                                                                                    <div>
                                                                                        <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - ' . getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name') . '-' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . ' </h6>
                                                                                        <div class="d-flex align-items-center">
                                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                                            <a class="btn btn-sm btn-primary ms-1 ps-2" 
                                                                                            href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a>
                                                                                            <a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>

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
                                                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                            $get_registration_number = $fetch_data['registration_number'];
                                                                            $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                            $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                            $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                                                            $share_whatsapp = '
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                            </div>';
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
                                                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                            $get_registration_number = $fetch_data['registration_number'];
                                                                            $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                            $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                            $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                                                            $share_whatsapp = '
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                            </div>';
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
                                                                            $driver_id = getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                                                                            $drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_vehicle_type = getVENDOR_VEHICLE_TYPES($fetch_data['vendor_id'], $fetch_data['vehicle_type_id'], 'label');
                                                                            $get_registration_number = $fetch_data['registration_number'];
                                                                            $customer_whatsapp_no = '91' . get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_contact_no');
                                                                            $get_drivername = getDRIVER_DETAILS($vendor_id, $driver_id, 'driver_name');
                                                                            $get_mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');

                                                                            $share_link_message = urlencode('Check out this link: ' . PUBLICPATH . 'dailymoment.php?formtype=driver&id=' . $itinerary_plan_ID);

                                                                            $message = urlencode("Check Your Assigned Driver Details:\nDriver Name: " . $get_drivername . "\nMobile No: " . $get_mobile_no . "\nVehicle Name: " . $get_vehicle_type . "\nVehicle Number: " . $get_registration_number);

                                                                            $share_whatsapp = '
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Assigned</span><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SLcopyAndOpenWhatsApp(\'' . getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no') . '\', \'' . addslashes($share_link_message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Share Link</a><a class="btn btn-sm btn-primary ms-1 ps-2" href="javascript:void(0);" onclick="SDcopyAndOpenWhatsApp(\'' . addslashes($customer_whatsapp_no) . '\', \'' . addslashes($message) . '\')"><i class="ti ti-share-3 fs-6 pe-1"></i> Driver Details</a>
                                                                            </div>';
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
                                                                            $mobile_no = getDRIVER_DETAILS($vendor_id, $driver_id, 'mobile_no');
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
                                                if ($show_driver_name) {

                                                    echo "<div  $assigned_class style='padding: 10px; border-radius: 0 0 5px 5px;'> ";
                                                    if (!empty($combined_assigned_itinerary_details)) {
                                                        echo "<h5 class='mb-0'><span class='badge badge-primary trip-badge' style='background-color: #89c76f;float: right;'>Trip 1</span></h5>";
                                                    }
                                                    echo  $itinerary_details;
                                                    // If a driver is assigned, get the driver's name
                                                    echo '<div class="d-flex">
                                                    <div>
                                                        <h6 class="mb-2">
                                                            <img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - ' . htmlspecialchars($drivername) . ' - ' . htmlspecialchars($mobile_no) . '
                                                        </h6>' .
                                                        $share_whatsapp . '
                                                    </div>
                                                    ' . $driver_edit . '
                                                </div></div><hr>';
                                                }
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
                                                    echo " </div>";
                                                } elseif (!empty($combined_assigned_itinerary_details)) {

                                                    echo "<div class='arrival-vehicle' style='padding: 10px; border-radius: 0 0 5px 5px;'> 
                                                        <h5 class='mb-0'><span class='badge badge-primary trip-badge' style='background-color: #89c76f;float: right;'>Trip 2</span></h5> 
                                                        $combined_assigned_itinerary_details";
                                                    echo "</div>";
                                                }



                                                echo "</td>";
                                            } else {
                                                // No itinerary or not assigned, leave the cell empty
                                                echo "<td></td>";
                                            }
                                        }
                                        echo "</tr>";
                                        // print_r($processed_ranges);
                                        //echo "<br>";
                                        // die;

                                        if ($vendor_id != $prev_vendor_id || $vehicle_type_id != $prev_vehicle_type_id):
                                            $additional_vehicle_needed = 0;
                                            foreach ($processed_ranges as $range_key => $counts) {
                                                $unassigned_count = $counts['unassigned_count'];
                                                //echo "<br>";
                                                if ($unassigned_count > $available_vehicles) {
                                                    $additional_vehicle_needed = $additional_vehicle_needed + ($unassigned_count - $available_vehicles);
                                                }
                                            }
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
                <link rel="stylesheet" href="assets/vendor/libs/datatables-fixedheader-bs5/fixedheader.bootstrap5.css">
                <script src="assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.min.js"></script>

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
                                scrollY: '42vh',
                                fixedHeader: true,
                                paging: false,
                                ordering: false,
                                info: false,
                            });
                        } else {
                            console.log('No data available or column count mismatch. DataTable initialization skipped.');
                        }


                        // Get current date
                        const today = new Date();

                        // Get the first day of the current month
                        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);

                        // Get the last day of the current month
                        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

                        // Initialize the Flatpickr with default dates
                        var dateFromPicker = flatpickr("#date_from", {
                            dateFormat: "d-m-Y",
                            defaultDate: firstDayOfMonth,
                            onChange: function(selectedDates, dateStr, instance) {
                                // When #date_from changes, update the #date_to minimum date
                                dateToPicker.set('minDate', selectedDates[0]); // Set the minDate to the selected date in #date_from
                                showUPDATED_AVAILABILITY_CHART();
                            }
                        });

                        // Initialize the flatpickr for #date_to
                        var dateToPicker = flatpickr("#date_to", {
                            dateFormat: "d-m-Y",
                            defaultDate: lastDayOfMonth,
                            minDate: firstDayOfMonth,
                            onChange: function(selectedDates, dateStr, instance) {
                                // Trigger update after date selection
                                showUPDATED_AVAILABILITY_CHART();
                            }
                        });


                        $('#filter_location').selectize();
                        $('#filter_agent').selectize();
                        $('#filter_vendor_vehicle_types').selectize();
                        $('#filter_vendor').selectize();

                        $('#filter_vendor_vehicle_types, #filter_agent, #filter_location').change(function() {
                            showUPDATED_AVAILABILITY_CHART();
                        });

                        var filter_vendor_vehicle_types_Select = $('#filter_vendor_vehicle_types').selectize();

                        $('#filter_vendor').change(function() {
                            var vendor_id = $(this).val();

                            var filter_vendor_vehicle_types_Selectize = filter_vendor_vehicle_types_Select[0].selectize;
                            $.ajax({
                                type: 'post',
                                url: 'engine/ajax/__ajax_get_vendor_vehicle_types.php?type=multiple_selectize_vehicle_type',
                                data: {
                                    vendor_id: vendor_id,
                                },
                                dataType: 'json',
                                success: function(response) {
                                    // Append the response to the dropdown.
                                    filter_vendor_vehicle_types_Selectize.clear();
                                    filter_vendor_vehicle_types_Selectize.clearOptions();
                                    filter_vendor_vehicle_types_Selectize.addOption(response);
                                }
                            });
                            showUPDATED_AVAILABILITY_CHART();
                        });
                    });

                    // document.getElementById('shareLink').onclick = function() {
                    //     const phoneNumber = '9344352766';
                    //     const message = encodeURIComponent(''. PUBLICPATH.'dailymoment.php?formtype=driver&id=740');
                    //     const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;
                    //     window.open(whatsappUrl, '_blank');
                    // };

                    function showFilter() {
                        const filter = document.getElementById('filterSection');
                        filter.classList.toggle('d-none');
                    }

                    function showUPDATED_AVAILABILITY_CHART() {

                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();
                        var vendor_id = $('#filter_vendor').val();
                        var vehicle_type_id = $('#filter_vendor_vehicle_types').val();
                        var agent_id = $('#filter_agent').val();
                        var location_id = $('#filter_location').val();

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

                    //ASSIGN VEHICLE MODAL
                    function showassignVehicleModal(itinerary_plan_IDs, vendor_id, vehicle_type_id, vehicle_id, itinerary_quote_IDs, assigned_itinerary_plan_ID = '') {

                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();
                        var filter_vendor = $('#filter_vendor').val();
                        var filter_vendor_vehicle_types = $('#filter_vendor_vehicle_types').val();
                        var filter_agent = $('#filter_agent').val();
                        var filter_location = $('#filter_location').val();

                        $('.receiving-vehicle-form-data').load('engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_assign_vehicle_modal&itinerary_plan_IDs=' + itinerary_plan_IDs + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id + '&vehicle_id=' + vehicle_id + '&itinerary_quote_IDs=' + itinerary_quote_IDs + '&assigned_itinerary_plan_ID=' + assigned_itinerary_plan_ID + '',
                            function() {
                                const container = document.getElementById("assignvehicleFORM");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                                $('#DRIVERFORMLabel').html('Assign Vehicle');
                                $('#hid_date_from').val(date_from);
                                $('#hid_date_to').val(date_to);
                                $('#hid_filter_vendor').val(filter_vendor);
                                $('#hid_filter_vendor_vehicle_types').val(filter_vendor_vehicle_types);
                                $('#hid_filter_agent').val(filter_agent);
                                $('#hid_filter_location').val(filter_agent);
                            });
                    }
                    //EDIT ASSIGNED DRIVER MODAL
                    function editDRIVERMODAL(itinerary_plan_ID, vendor_id, driver_id) {

                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();
                        var filter_vendor = $('#filter_vendor').val();
                        var filter_vendor_vehicle_types = $('#filter_vendor_vehicle_types').val();
                        var filter_agent = $('#filter_agent').val();
                        var filter_location = $('#filter_location').val();

                        $('.receiving-driver-form-data').load('engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_reassign_driver_modal&itinerary_plan_ID=' + itinerary_plan_ID + '&vendor_id=' + vendor_id + '&driver_id=' + driver_id + '',
                            function() {
                                const container = document.getElementById("editDRIVERDATA");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                                $('#DRIVERFORMLabel').html('Re-Assign Driver');
                                $('#hid_date_from').val(date_from);
                                $('#hid_date_to').val(date_to);
                                $('#hid_filter_vendor').val(filter_vendor);
                                $('#hid_filter_vendor_vehicle_types').val(filter_vendor_vehicle_types);
                                $('#hid_filter_agent').val(filter_agent);
                                $('#hid_filter_location').val(filter_agent);
                            });
                    }

                    //ADD NEW DRIVER MODAL
                    function showADDDRIVERMODAL() {

                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();
                        var filter_vendor = $('#filter_vendor').val();
                        var filter_vendor_vehicle_types = $('#filter_vendor_vehicle_types').val();
                        var filter_agent = $('#filter_agent').val();
                        var filter_location = $('#filter_location').val();

                        $('.receiving-add-driver-form-data').load('engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_add_driver_modal' + '',
                            function() {
                                const container = document.getElementById("addNEWDRIVER");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                                $('#NEWDRIVERFORMLabel').html('Add New Driver');
                                $('#hid_date_from').val(date_from);
                                $('#hid_date_to').val(date_to);
                                $('#hid_filter_vendor').val(filter_vendor);
                                $('#hid_filter_vendor_vehicle_types').val(filter_vendor_vehicle_types);
                                $('#hid_filter_agent').val(filter_agent);
                                $('#hid_filter_location').val(filter_agent);
                            });
                    }

                    //ADD NEW VEHICLE MODAL
                    function showADDVEHICLEMODAL(vendor_id = "", vehicle_type_id = "") {
                        var date_from = $('#date_from').val();
                        var date_to = $('#date_to').val();
                        var filter_vendor = $('#filter_vendor').val();
                        var filter_vendor_vehicle_types = $('#filter_vendor_vehicle_types').val();
                        var filter_agent = $('#filter_agent').val();
                        var filter_location = $('#filter_location').val();

                        $('.receiving-add-vehicle-form-data').load('engine/ajax/ajax_confirmed_itinerary_vehicle_assignemnt_list.php?type=show_add_vehicle_modal&date_from=' + date_from + '&date_to=' + date_to + '&vendor_id=' + vendor_id + '&vehicle_type_id=' + vehicle_type_id + '',
                            function() {
                                const container = document.getElementById("addNEWVEHICLE");
                                const modal = new bootstrap.Modal(container);
                                modal.show();
                                $('#NEWVEHICLEFORMLabel').html('Add New Vehicle');
                                $('#hid_date_from').val(date_from);
                                $('#hid_date_to').val(date_to);
                                $('#hid_filter_vendor').val(filter_vendor);
                                $('#hid_filter_vendor_vehicle_types').val(filter_vendor_vehicle_types);
                                $('#hid_filter_agent').val(filter_agent);
                                $('#hid_filter_location').val(filter_agent);
                            });
                    }

                    function SDcopyAndOpenWhatsApp(phone, message) {
                        const decodedMessage = decodeURIComponent(message).replace(/\+/g, ' ');
                        const encodedMessage = encodeURIComponent(decodedMessage);

                        navigator.clipboard.writeText(decodedMessage).then(function() {
                            TOAST_NOTIFICATION('success', 'Driver Details copied successfully!', 'Success !!!');

                            let whatsapp_url = '';
                            if (/Android|iPhone|iPad/i.test(navigator.userAgent)) {
                                // Mobile devices
                                whatsapp_url = `whatsapp://send?phone=${phone}&text=${encodedMessage}`;
                                window.location.href = whatsapp_url;
                            } else {
                                // Desktop (Web link, reuse named window)
                                whatsapp_url = `https://web.whatsapp.com/send?phone=${phone}&text=${encodedMessage}`;
                                let whatsappWindow = window.open('', 'whatsapp_tab');
                                if (whatsappWindow) {
                                    whatsappWindow.location.href = whatsapp_url;
                                    whatsappWindow.focus();
                                } else {
                                    TOAST_NOTIFICATION('info', 'Please allow popups for this site to open WhatsApp Web.', 'Popup Blocked');
                                }
                            }
                        }).catch(function(err) {
                            TOAST_NOTIFICATION('error', 'Error!!!', 'Unable to Copy Text!!!');
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
            <?php
        endif;

            ?>