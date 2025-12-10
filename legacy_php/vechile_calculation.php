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

include_once('jackus.php');

$itinerary_plan_id = $_GET['plan_id'];

$select_itineary_vehicle_cost_calculation = sqlQUERY_LABEL("SELECT USERS.`userID`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`,VEHICLE.`vehicle_location_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`registration_number`, VEHICLE.`vehicle_fc_expiry_date`, VEHICLE.`insurance_end_date`, VEHICLE.`owner_city`,VEHICLE.`extra_km_charge`, ITINEARY_PLAN_VEHICLE.`itinerary_plan_id`, ITINEARY_PLAN_VEHICLE.`vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details` ITINEARY_PLAN_VEHICLE LEFT JOIN `dvi_vehicle` VECHILE ON ITINEARY_PLAN_VEHICLE.`vehicle_type_id` = VECHILE.`vehicle_type_id` LEFT JOIN `dvi_vehicle` VEHICLE ON VEHICLE.`vehicle_type_id` = ITINEARY_PLAN_VEHICLE.`vehicle_type_id` LEFT JOIN `dvi_users` USERS ON USERS.`vendor_id` = VEHICLE.`vendor_id` WHERE ITINEARY_PLAN_VEHICLE.`itinerary_plan_id` = '$itinerary_plan_id' AND VEHICLE.`vehicle_fc_expiry_date` >= CURRENT_DATE() AND VEHICLE.`insurance_end_date` >= CURRENT_DATE() AND VEHICLE.`status` = '1' and VEHICLE.`deleted` = '0' GROUP BY VEHICLE.`vendor_branch_id`;") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
$total_no_of_select_vehicle_details = sqlNUMOFROW_LABEL($select_itineary_vehicle_cost_calculation);
if ($total_no_of_select_vehicle_details > 0) :
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <div class="container">
        <div class="row mt-5">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vendor Name</th>
                        <th scope="col">Branch Name</th>
                        <th scope="col">Vehicle Reg. No.</th>
                        <th scope="col">Vehicle FC Expiry Date</th>
                        <th scope="col">Vehicle Insurance Expiry Date</th>
                        <th scope="col">Vehicle Type</th>
                        <th scope="col">Vehicle State</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_cost_calculation)) :
                        $vendor_vehicle_count++;
                        $userID = $fetch_list_data['userID'];
                        $vehicle_id = $fetch_list_data['vehicle_id'];
                        $vendor_id = $fetch_list_data['vendor_id'];
                        $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
                        $vendor_branch_gst_type =  getBranchLIST($vendor_branch_id, 'branch_gst_type');
                        $branch_gst_percentage =  getBranchLIST($vendor_branch_id, 'branch_gst_percentage');
                        $registration_number = $fetch_list_data['registration_number'];
                        $state_code = substr($registration_number, 0, 2);
                        $owner_city = $fetch_list_data['owner_city'];
                        $vehicle_city_name = getCITYLIST('', $owner_city, 'city_label');
                        $vehicle_fc_expiry_date = $fetch_list_data['vehicle_fc_expiry_date'];
                        $insurance_end_date = $fetch_list_data['insurance_end_date'];
                        $itinerary_plan_id = $fetch_list_data['itinerary_plan_id'];
                        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                        $vehicle_state = substr($registration_number, 0, 2);
                        $vehicle_location_id = $fetch_list_data['vehicle_location_id'];
                        $extra_km_charge = $fetch_list_data['extra_km_charge'];

                        $vehicle_orign = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
                        $vehicle_orign_location_latitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_latitude');
                        $vehicle_orign_location_longtitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_longtitude');

                        $select_vehicle_permit_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_code`='$state_code' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        while ($fetch_vehicle_state = sqlFETCHARRAY_LABEL($select_vehicle_permit_state)) :
                            $vehicle_state_id = $fetch_vehicle_state['permit_state_id'];
                        endwhile;

                    ?>
                        <tr>
                            <th scope="row"><?= $vendor_vehicle_count; ?></th>
                            <td><?= getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid'); ?></td>
                            <td><?= getVENDORANDVEHICLEDETAILS($vendor_branch_id, 'get_vendorbranchname_from_vendorbranchid'); ?></td>
                            <td><?= $registration_number; ?></td>
                            <td><?= dateformat_datepicker($vehicle_fc_expiry_date); ?></td>
                            <td><?= dateformat_datepicker($insurance_end_date); ?></td>
                            <td><?= getVEHICLELIST($vehicle_type_id, 'vehicle_label'); ?></td>
                            <td><?= $vehicle_state; ?></td>
                        </tr>
                        <!-- Add a new table row for additional details -->
                        <tr>
                            <td colspan="8">
                                <!-- Add your new table here -->
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Additional Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $select_itineary_route_plan_info = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_km`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_id' and `status` = '1' and `deleted` = '0' ORDER BY `itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());


                                        $total_no_of_itineary_plan_details = sqlNUMOFROW_LABEL($select_itineary_route_plan_info);
                                        if ($total_no_of_itineary_plan_details > 0) :
                                        ?>
                                            <tr>
                                                <th scope="col">#Day</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Source Location</th>
                                                <th scope="col">Destination Location</th>
                                                <th scope="col">Running KMS</th>
                                                <th>Sight seeing KMS</th>
                                                <th>Traveling time</th>
                                                <th>Sight seeing time</th>
                                                <th>Total time</th>
                                                <th>Total KM</th>
                                                <th scope="col">Cost Type</th>
                                                <th scope="col">Total Cost</th>
                                                <th scope="col">Permit Cost</th>
                                            </tr>
                                            <?php
                                            $overall_total_trip_cost = 0;
                                            $overall_total_permit_cost = 0;

                                            while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_info)) :
                                                $route_count++;
                                                $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                                                $location_id = $fetch_itineary_route_data['location_id'];
                                                $location_name = $fetch_itineary_route_data['location_name'];
                                                $itinerary_route_date = dateformat_datepicker($fetch_itineary_route_data['itinerary_route_date']);
                                                $no_of_km = $fetch_itineary_route_data['no_of_km'];
                                                $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                                                $day = date('j', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                                                $year = date('Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                                                $month = date('F', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                                                $location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'location_latitude', $location_id);
                                                $location_longtitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'location_longtitude', $location_id);

                                                $next_visiting_location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'next_visiting_location_latitude', $location_id);
                                                $next_visiting_location_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_ID, 'next_visiting_location_longitude', $location_id);

                                                $source_location_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');

                                                $RUNNINGTIME = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_TRAVEL_TIME');
                                                $RUNNING_TIME = sprintf('%02d:%02d:00', ...explode(':', $RUNNINGTIME));

                                                $RUNNING_DISTANCE =
                                                    getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE');

                                                $SIGHT_SEEING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_id, $itinerary_route_ID, 'SIGHT_SEEING_TIME');

                                                $SIGHT_SEEING_DISTANCE =
                                                    getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_id, $itinerary_route_ID, 'SIGHT_SEEING_DISTANCE');

                                                //IF DAY 1 ADD PICKUP DIS AND TIME
                                                if ($route_count == 1) :
                                                    if ($vehicle_orign != $location_name) :

                                                        $distance_from_vehicle_orign_to_pickup_point =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $location_latitude, $location_longtitude);

                                                        $pickup_distance = $distance_from_vehicle_orign_to_pickup_point['distance'];
                                                        $pickup_duration = $distance_from_vehicle_orign_to_pickup_point['duration'];

                                                        //FORMAT DURATION
                                                        $parts = explode(' ', $pickup_duration);
                                                        $hours = 0;
                                                        $minutes = 0;

                                                        if (count($parts) >= 2) {
                                                            if (
                                                                $parts[1] == 'hour' || $parts[1] == 'hours'
                                                            ) {
                                                                $hours = (int)$parts[0];
                                                            }
                                                            if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
                                                                $minutes = (int)$parts[2];
                                                            }
                                                        }

                                                        // Format the time as HH:MM:SS
                                                        $formated_pickup_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
                                                    else :
                                                        $pickup_distance = 0;
                                                        $formated_pickup_duration = "00:00:00";
                                                    endif;

                                                    $TOTAL_RUNNING_KM
                                                        = $RUNNING_DISTANCE + $pickup_distance;

                                                    //TOTAL RUNNING TIME
                                                    // Convert time strings to seconds
                                                    $RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME);
                                                    $PICKUP_TIME_INSECONDS = strtotime($formated_pickup_duration);

                                                    // Add the seconds
                                                    $totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_INSECONDS;

                                                    // Convert total seconds back to time format
                                                    $TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

                                                else :
                                                    $TOTAL_RUNNING_TIME = $RUNNING_TIME;

                                                    $TOTAL_RUNNING_KM
                                                        = $RUNNING_DISTANCE;
                                                endif;

                                                //if LAST DAY ADD DROP DIS AND TIME
                                                if ($total_no_of_itineary_plan_details == $route_count) :

                                                    if ($vehicle_orign != $next_visiting_location) :

                                                        $distance_from_drop_point_to_vehicle_orign =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $next_visiting_location_latitude, $next_visiting_location_longitude);

                                                        $drop_distance = $distance_from_drop_point_to_vehicle_orign['distance'];
                                                        $drop_duration = $distance_from_drop_point_to_vehicle_orign['duration'];

                                                        //FORMAT DURATION
                                                        $parts = explode(' ', $drop_duration);
                                                        $hours = 0;
                                                        $minutes = 0;

                                                        if (count($parts) >= 2) {
                                                            if (
                                                                $parts[1] == 'hour' || $parts[1] == 'hours'
                                                            ) {
                                                                $hours = (int)$parts[0];
                                                            }
                                                            if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
                                                                $minutes = (int)$parts[2];
                                                            }
                                                        }

                                                        // Format the time as HH:MM:SS
                                                        $formated_drop_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
                                                    else :
                                                        $drop_distance = 0;
                                                        $formated_drop_duration = "00:00:00";
                                                    endif;

                                                    $TOTAL_RUNNING_KM
                                                        = $RUNNING_DISTANCE + $drop_distance;

                                                    //TOTAL SIGHT SEEING TIME
                                                    // Convert time strings to seconds
                                                    $RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME) - strtotime('00:00:00');
                                                    $PICKUP_TIME_IN_SECONDS = strtotime($formated_drop_duration) - strtotime('00:00:00');

                                                    // Add the seconds
                                                    $totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_IN_SECONDS;

                                                    // Convert total seconds back to time format
                                                    $TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

                                                else :
                                                    $TOTAL_RUNNING_TIME = $RUNNING_TIME;

                                                    $TOTAL_RUNNING_KM
                                                        = $RUNNING_DISTANCE;
                                                endif;

                                                $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_DISTANCE;

                                                //TOTAL TIME
                                                // Convert time durations to seconds
                                                $TOTAL_RUNNING_TIME_IN_SECONDS = strtotime($TOTAL_RUNNING_TIME) - strtotime('00:00:00');
                                                $SIGHT_SEEING_TIME_IN_SECONDS = strtotime($SIGHT_SEEING_TIME) - strtotime('00:00:00');

                                                $totalSeconds1 = $TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_INSECONDS;

                                                $TOTAL_TIME = gmdate('H:i:s', $totalSeconds1);

                                                //COST CALCULATION
                                                if ($vehicle_city_name == $source_location_city) :
                                                    $trip_cost_type = 'Local Cost';
                                                    //LOCAL TRIP
                                                    //echo  $TOTAL_TIME . "<br>";
                                                    $time_parts = explode(':', $TOTAL_TIME);
                                                    $TOTAL_TIME_hours = intval($time_parts[0]);
                                                    $TOTAL_TIME_minutes = intval($time_parts[1]);

                                                    // Round the total time based on minutes
                                                    if ($TOTAL_TIME_minutes < 15) :
                                                        $TOTAL_HOURS =  $TOTAL_TIME_hours;
                                                    else :
                                                        $TOTAL_HOURS = $TOTAL_TIME_hours + 1;
                                                    endif;
                                                    // echo $TOTAL_HOURS . "<br>";
                                                    $hours_limit_id = getHOUR($TOTAL_HOURS, 'get_hour_limit_id');

                                                    $total_trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $hours_limit_id);
                                                //echo $total_trip_cost . "<br>";

                                                else :
                                                    $trip_cost_type = 'Outstation Cost'; //OUTSTATION TRIP
                                                    $kms_limit = getKMLIMIT($vendor_id, 'get_kms_limit');

                                                    if ($TOTAL_KM > $kms_limit) :
                                                        $extra_km = $TOTAL_KM - $kms_limit;
                                                    else :
                                                        $extra_km = 0;
                                                    endif;

                                                    $kms_limit_id = getKMLIMIT($vendor_id, 'get_kms_limit_id');

                                                    /* if ($userID == 12) :
                                                        $kms_limit_id = 1;
                                                    elseif ($userID == 16) :
                                                        $kms_limit_id = 3;
                                                    endif;*/
                                                    $trip_cost = getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $kms_limit_id, '', $userID);

                                                    if ($extra_km > 0) :
                                                        $total_extra_km_charge = $extra_km * $extra_km_charge;
                                                        $total_trip_cost = $trip_cost + $total_extra_km_charge;
                                                    else :
                                                        $total_trip_cost = $trip_cost;
                                                    endif;

                                                endif;
                                                $overall_total_trip_cost += $total_trip_cost;



                                                //GET STATE DETAILS OF SOURCE AND DESTINATION
                                                if ($location_name == $next_visiting_location) :
                                                    $filter_by = "  `source_location`='$location_name' ";
                                                else :
                                                    $filter_by = "  `destination_location` ='$next_visiting_location' AND `source_location`='$location_name' ";
                                                endif;

                                                $get_location_details = sqlQUERY_LABEL("SELECT `source_location_state`,`destination_location_state` FROM `dvi_stored_locations` WHERE  {$filter_by} ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
                                                if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                                                    while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                                                        if ($location_name == $next_visiting_location) :
                                                            $destination_location_state =
                                                                $source_location_state = $fetch_location_data['source_location_state'];
                                                        else :
                                                            $destination_location_state = $fetch_location_data['destination_location_state'];
                                                            $source_location_state = $fetch_location_data['source_location_state'];
                                                        endif;
                                                    endwhile;
                                                endif;

                                                $source_state_id = getVEHICLE_PERMIT_DETAILS($source_location_state, 'GET_PERMIT_STATE_ID');

                                                $destination_state_id = getVEHICLE_PERMIT_DETAILS($destination_location_state, 'GET_PERMIT_STATE_ID');

                                                $permit_cost = 0;

                                                $permit_cost_collected_variable = "permit_cost_collected_" . $destination_state_id . "_" . $vehicle_id;
                                                $permit_cost_day_count_variable = $permit_cost_collected_variable . "_day_count";

                                                if (${$permit_cost_collected_variable} == 1) :
                                                    ${$permit_cost_day_count_variable}++;
                                                endif;

                                                if ($vehicle_state_id == $destination_state_id && $source_state_id == $destination_state_id) :
                                                    //SAME STATE 
                                                    $permit_cost = 0;
                                                else :
                                                    //DIFFERENT STATE
                                                    if ((${$permit_cost_collected_variable} != 1) || ((${$permit_cost_collected_variable} == 1) && ${$permit_cost_day_count_variable} == 8)
                                                    ) :
                                                        $select_vehicle_permit_cost = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted`='0' AND `status`='1' AND `vendor_id`='$vendor_id' AND `vehicle_type_id`='$vehicle_type_id' AND `source_state_id`='$vehicle_state_id' AND `destination_state_id`='$destination_state_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                        while ($fetch_vehicle_permit_cost = sqlFETCHARRAY_LABEL($select_vehicle_permit_cost)) :
                                                            $permit_cost = $fetch_vehicle_permit_cost['permit_cost'];
                                                            ${$permit_cost_collected_variable} = 1;
                                                            ${$permit_cost_day_count_variable} = 1;
                                                        endwhile;
                                                    endif;
                                                endif;
                                                $overall_total_permit_cost += $permit_cost;
                                            ?>
                                                <tr>
                                                    <td><?= 'Day ' . $route_count; ?></td>
                                                    <td><?= $itinerary_route_date; ?></td>
                                                    <td><?= $location_name; ?></td>
                                                    <td><?= $next_visiting_location; ?></td>
                                                    <td><?= number_format($TOTAL_RUNNING_KM, 2); ?></td>
                                                    <td><?= $SIGHT_SEEING_DISTANCE; ?></td>
                                                    <td><?= $TOTAL_RUNNING_TIME; ?></td>
                                                    <td><?= $SIGHT_SEEING_TIME; ?></td>
                                                    <td><?= $TOTAL_TIME; ?></td>
                                                    <td><?= number_format($TOTAL_KM, 2); ?></td>
                                                    <td><?= $trip_cost_type; ?></td>
                                                    <td><?= $global_currency_format . ' ' . number_format($total_trip_cost, 2); ?></td>
                                                    <td><?= $global_currency_format . ' ' . number_format($permit_cost, 2); ?></td>
                                                </tr>
                                            <?php
                                            endwhile;

                                            if ($vendor_branch_gst_type == 1) :
                                                // For Inclusive GST
                                                $new_overall_total_trip_cost = $overall_total_trip_cost / (1 + ($branch_gst_percentage / 100));

                                                $gst_tax_amt = ($overall_total_trip_cost - $new_overall_total_trip_cost);

                                            elseif ($vendor_branch_gst_type == 2) :
                                                // For Exclusive GST
                                                $new_overall_total_trip_cost = $overall_total_trip_cost;
                                                $gst_tax_amt = ($overall_total_trip_cost * $branch_gst_percentage / 100);
                                            endif;

                                            ?>
                                            <tr>
                                                <td colspan="9"></td>
                                                <td colspan="2">Total Permit Cost</td>
                                                <td colspan="2"><b><?= $global_currency_format . ' ' . number_format($overall_total_permit_cost, 2); ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"></td>
                                                <td colspan="2">Vechile Cost</td>
                                                <td colspan="2"><b><?= $global_currency_format . ' ' . number_format($overall_total_trip_cost, 2); ?></b></td>
                                            </tr>

                                            <tr>
                                                <td colspan="9"></td>
                                                <td colspan="2">Total Vechile GST <?= $branch_gst_percentage ?>%</td>
                                                <td colspan="2"><b>
                                                        <?php //$global_currency_format . ' ' . number_format((($overall_total_trip_cost * 18) / 100), 2); 
                                                        ?>
                                                        <?= $global_currency_format . ' ' . number_format($gst_tax_amt, 2) ?>
                                                    </b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="9"></td>
                                                <td colspan="2">Total Vechile Cost</td>
                                                <td colspan="2"><b>
                                                        <?php // $global_currency_format . ' ' . number_format($overall_total_trip_cost + (($overall_total_trip_cost * 18) / 100), 2); 
                                                        ?>
                                                        <?= $global_currency_format . ' ' . number_format($new_overall_total_trip_cost); ?>
                                                    </b></td>
                                            </tr>
                                        <?php
                                        else :
                                        ?>
                                        <?php
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                    <?php

                    endwhile;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php
else :
    echo "No more Vehicles found with valid insurance and FC.";
endif;
?>