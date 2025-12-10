
                                                    // FORMAT THE TIME AS H:i:s
                                                    $formated_pickup_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                    $TOTAL_PICKUP_KM = $pickup_distance;
                                                    $TOTAL_PICKUP_DURATION = $formated_pickup_duration;
                                                else :
                                                    $TOTAL_PICKUP_KM = 0;
                                                    $TOTAL_PICKUP_DURATION = "00:00:00";
                                                endif;

                                                $TOTAL_RUNNING_TRAVEL_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'TOTAL_RUNNING_TRAVEL_TIME');

                                                $SIGHT_SEEING_TRAVELLING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TRAVELLING_TIME');

                                                $SIGHT_SEEING_TRAVELLING_KM = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TRAVELLING_DISTANCE');

                                                $GET_RUNNING_KM = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'TOTAL_RUNNING_KM');

                                                $TOTAL_RUNNING_KM = $TOTAL_PICKUP_KM + $GET_RUNNING_KM;

                                                $TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS = strtotime("1970-01-01 $TOTAL_RUNNING_TRAVEL_TIME UTC");
                                                $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS = strtotime("1970-01-01 $SIGHT_SEEING_TRAVELLING_TIME UTC");
                                                $PICKUP_TIME_IN_SECONDS = strtotime("1970-01-01 $formated_pickup_duration UTC");

                                                // Add the seconds
                                                $total_times_in_Seconds = $TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS + $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS + $PICKUP_TIME_IN_SECONDS;

                                                // Convert total seconds back to time format
                                                $TOTAL_TIME = gmdate('H:i:s', $total_times_in_Seconds);

                                                $total_travelling_times_in_Seconds = $TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS + $PICKUP_TIME_IN_SECONDS;

                                                $TOTAL_TRAVELLING_TIME = gmdate('H:i:s', $total_travelling_times_in_Seconds);

                                                $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_TRAVELLING_KM;

                                                $time_parts = explode(':', $TOTAL_TIME);
                                                $TOTAL_TIME_hours = intval($time_parts[0]);
                                                $TOTAL_TIME_minutes = intval($time_parts[1]);

                                                /* echo "TOTAL_RUNNING_TRAVEL_TIME => $TOTAL_RUNNING_TRAVEL_TIME";
                                            echo "<br>";
                                            echo "SIGHT_SEEING_TRAVELLING_TIME => $SIGHT_SEEING_TRAVELLING_TIME";
                                            echo "<br>";
                                            echo "SIGHT_SEEING_TRAVELLING_KM => $SIGHT_SEEING_TRAVELLING_KM";
                                            echo "<br>";
                                            echo "GET_RUNNING_KM => $GET_RUNNING_KM";
                                            echo "<br>";
                                            echo "TOTAL_RUNNING_KM => $TOTAL_RUNNING_KM";
                                            echo "<br>";
                                            echo "TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS => $TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS";
                                            echo "<br>";
                                            echo "SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS => $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS";
                                            echo "<br>";
                                            echo "PICKUP_TIME_IN_SECONDS => $PICKUP_TIME_IN_SECONDS";
                                            echo "<br>";
                                            echo "total_times_in_Seconds => $total_times_in_Seconds";
                                            echo "<br>";
                                            echo "TOTAL_TIME => $TOTAL_TIME";
                                            echo "<br>";
                                            echo "total_travelling_times_in_Seconds => $total_travelling_times_in_Seconds";
                                            echo "<br>";
                                            echo "TOTAL_TRAVELLING_TIME => $TOTAL_TRAVELLING_TIME";
                                            echo "<br>";
                                            echo "TOTAL_KM => $TOTAL_KM";
                                            echo "<br>";
                                            echo "TOTAL_TIME_hours => $TOTAL_TIME_hours";
                                            echo "<br>";
                                            echo "TOTAL_TIME_minutes => $TOTAL_TIME_minutes";
                                            echo "<br>"; */

                                                // Round the total time based on minutes
                                                if ($TOTAL_TIME_minutes < 30) :
                                                    $TOTAL_HOURS =  $TOTAL_TIME_hours;
                                                else :
                                                    $TOTAL_HOURS = $TOTAL_TIME_hours + 1;
                                                endif;

                                                /* $hours_time_limit_id = getTIMELIMIT($vendor_vehicle_type_ID, 'get_time_limit_id_from_hour_limit', $vendor_id, $TOTAL_HOURS);

                                                $km_time_limit_id = getTIMELIMIT($vendor_vehicle_type_ID, 'get_time_limit_id_from_km_limit', $vendor_id, $TOTAL_HOURS, $TOTAL_KM); */

                                                $time_limit_id = getTIMELIMIT($vendor_vehicle_type_ID, 'get_time_limit_id_for_hours_and_km', $vendor_id, $TOTAL_HOURS, $TOTAL_KM);

                                                /* echo "TOTAL_KM => $TOTAL_KM | km_time_limit_id => $km_time_limit_id";
                                                echo "<br>";
                                                echo "TOTAL_HOURS => $TOTAL_HOURS | hours_time_limit_id => $hours_time_limit_id";
                                                echo "<br>"; */

                                                /* // Determine which time limit ID to use
                                                if ($km_time_limit_id == $hours_time_limit_id) :
                                                    $time_limit_id = $km_time_limit_id;
                                                    $getTIMELIMITID = $km_time_limit_id;
                                                elseif ($km_time_limit_id > $hours_time_limit_id) :
                                                    // If KM limit is greater
                                                    $time_limit_id = $km_time_limit_id;
                                                    $getTIMELIMITID = $km_time_limit_id;
                                                elseif ($km_time_limit_id < $hours_time_limit_id) :
                                                    // If hour limit is greater
                                                    $time_limit_id = $hours_time_limit_id;
                                                    $getTIMELIMITID = $hours_time_limit_id;
                                                endif; */

                                                /* echo "DAY 1 => final_time_limit_id => $time_limit_id";
                                                echo "<br>"; */

                                                $getTIMELIMITID = $time_limit_id;
                                                $TOTAL_ALLOWED_LOCAL_KM = getTIMELIMIT($time_limit_id, 'km_limit', '', '', '');

                                                if ($TOTAL_KM > $TOTAL_ALLOWED_LOCAL_KM):
                                                    $TOTAL_LOCAL_EXTRA_KM = ($TOTAL_KM - $TOTAL_ALLOWED_LOCAL_KM);
                                                else:
                                                    $TOTAL_LOCAL_EXTRA_KM = 0;
                                                endif;

                                                /* echo "TOTAL_KM => $TOTAL_KM | TOTAL_ALLOWED_LOCAL_KM => $TOTAL_ALLOWED_LOCAL_KM | TOTAL_LOCAL_EXTRA_KM => $TOTAL_LOCAL_EXTRA_KM <br>";
                                                echo "<br>";
                                                echo "<br>"; */

                                                $vehicle_cost_for_the_day = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vendor_branch_id, $vendor_vehicle_type_ID, $time_limit_id);

                                                # LOCAL TRIP TOLL CHARGES FOR DAY 1
                                                // TOLL CHARGE CALCULATION WITH VEHICLE ORIGIN & SOURCE & VIA & DESTINATION
                                                if ($get_via_route_IDs):

                                                    $toll_source_location = $location_name;
                                                    $get_via_route_location_IDs = implode(',', $get_via_route_IDs);

                                                    // VIA ROUTE LOCATION NAME
                                                    $via_route_names = getSTOREDLOCATION_VIAROUTE_DETAILS($location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');
                                                    $toll_destination_location = $next_visiting_location;

                                                    // Check if $via_route_names is valid and is an array
                                                    if (!is_array($via_route_names)) :
                                                        $via_route_names = []; // Ensure it is an array even if empty
                                                    endif;

                                                    // Initialize an array to store locations
                                                    $toll_charge_locations = [];

                                                    // Step 1: Vehicle Origin to Source
                                                    $toll_charge_locations[] = [$vehicle_origin, $toll_source_location];

                                                    // Step 2: Source to the first VIA route (if available)
                                                    if (!empty($via_route_names)) :
                                                        $toll_charge_locations[] = [$toll_source_location, $via_route_names[0]];
                                                    endif;

                                                    // Step 3: Via to Via for multiple VIA routes
                                                    for ($i = 0; $i < count($via_route_names) - 1; $i++) :
                                                        $toll_charge_locations[] = [$via_route_names[$i], $via_route_names[$i + 1]];
                                                    endfor;

                                                    // Step 4: Last VIA route to Destination (or Source to Destination if no VIA routes)
                                                    if (!empty($via_route_names)) :
                                                        $toll_charge_locations[] = [$via_route_names[count($via_route_names) - 1], $toll_destination_location];
                                                    else :
                                                        $toll_charge_locations[] = [$toll_source_location, $toll_destination_location]; // Direct route if no via routes
                                                    endif;

                                                    // Dynamically assign variables for each location pair and calculate toll charges
                                                    foreach ($toll_charge_locations as $index => $location_pair) :
                                                        $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                        // LOCAL TRIP TOLL CHARGE CALCULATION FOR VEHICLE ORIGIN & SOURCE & VIA & DESTINATION
                                                        if ($get_location_id):
                                                            $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                        else:
                                                            $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                        endif;
                                                    endforeach;

                                                else:
                                                    $toll_source_location = $location_name;
                                                    $toll_destination_location = $next_visiting_location;

                                                    // Initialize an array to store locations
                                                    $toll_charge_locations = [];

                                                    // Step 1: Vehicle Origin to Source
                                                    $toll_charge_locations[] = [$vehicle_origin, $toll_source_location];

                                                    // Step 2: Source to Destination
                                                    $toll_charge_locations[] = [$toll_source_location, $toll_destination_location];

                                                    // Dynamically assign variables for each location pair and calculate toll charges
                                                    foreach ($toll_charge_locations as $index => $location_pair) :
                                                        $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                        // LOCAL TRIP TOLL CHARGE CALCULATION FOR VEHICLE ORIGIN & SOURCE & DESTINATION
                                                        if ($get_location_id):
                                                            $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                        else:
                                                            $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                        endif;

                                                    endforeach;
                                                endif;

                                                // TOLL CHARGE CALCULATION
                                                $VEHICLE_TOLL_CHARGE = $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE;

                                                //PARKING CHARGE CALCULATION
                                                $VEHICLE_PARKING_CHARGE = getITINERARY_HOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($vehicle_type_id, $itinerary_plan_ID, $itinerary_route_ID, 'total_hotspot_parking_charges');

                                            /* echo "<tr>";
                                            echo "<td>$itinerary_route_date <br> Day $route_count (LOC) <br> $route_start_time - $route_end_time</td>";
                                            echo "<td>From : $location_name <br> To: $next_visiting_location</td>";
                                            echo "<td>$TOTAL_RUNNING_KM / $TOTAL_TRAVELLING_TIME</td>";
                                            echo "<td>$SIGHT_SEEING_TRAVELLING_KM / $SIGHT_SEEING_TRAVELLING_TIME </td>";
                                            echo "<td>$TOTAL_KM / $TOTAL_TIME</td>";
                                            echo "<td>RENTAL : $vehicle_cost_for_the_day <br> TOLL_CHARGE : $VEHICLE_TOLL_CHARGE <br> PARKING_CHARGE : $VEHICLE_PARKING_CHARGE <br> DRIVER_CHARGES : $TOTAL_DRIVER_CHARGES <br> PERMIT_CHARGES : $permit_charges <br> BEFORE_6AM_AFTER_8PM_CHARGES : $morning_extra_time [$VENDOR_VEHICLE_MORNING_CHARGES + $DRIVER_MORINING_CHARGES] | $evening_extra_time [$VENDOR_VEHICLE_EVENING_CHARGES + $DRIVER_EVEINING_CHARGES] </td>";
                                            echo "</tr>"; */
                                            else :
                                                $TOTAL_PICKUP_KM = 0;
                                                $TOTAL_PICKUP_DURATION = "00:00:00";

                                                /* $RUNNINGTIME = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_TRAVEL_TIME');

                                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                            preg_match('/(\d+) hour/', $RUNNINGTIME, $hoursMatch);
                                            preg_match('/(\d+) mins/', $RUNNINGTIME, $minutesMatch);

                                            // INITIALIZE HOURS AND MINUTES TO ZERO
                                            $runningtime_hours = 0;
                                            $runningtime_minutes = 0;

                                            $runningtime_hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                            $runningtime_minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                            // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59
                                            $extraHours = floor($runningtime_minutes / 60);
                                            $runningtime_hours += $extraHours;
                                            $runningtime_minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                                            // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                                            $runningtime_formattedHours = str_pad($runningtime_hours, 2, '0', STR_PAD_LEFT);
                                            $runningtime_formattedMinutes = str_pad($runningtime_minutes, 2, '0', STR_PAD_LEFT);

                                            // FORMAT THE TIME AS H:i:s
                                            $formated_runningtime_duration = sprintf('%02d:%02d:00', $runningtime_formattedHours, $runningtime_formattedMinutes);

                                            $RUNNING_DISTANCE = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE'); */

                                                $TOTAL_RUNNING_TRAVEL_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'TOTAL_RUNNING_TRAVEL_TIME');

                                                $SIGHT_SEEING_TRAVELLING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TRAVELLING_TIME');

                                                $SIGHT_SEEING_TRAVELLING_KM = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TRAVELLING_DISTANCE');

                                                $RUNNING_KM = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'TOTAL_RUNNING_KM');

                                                $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME = '00:00:00';

                                                if ($total_no_of_itineary_plan_route_details == $route_count) :
                                                    // Determine the travel location type
                                                    $travel_location_type = getTravelLocationType($destination_location_city, $vehicle_origin_city);
                                                    $distance_from_droping_point_to_vehicle_orign =  calculateDistanceAndDuration($next_visiting_location_latitude, $next_visiting_location_longitude, $vehicle_origin_location_latitude, $vehicle_origin_location_longtitude, $travel_location_type);

                                                    $return_pickup_distance = $distance_from_droping_point_to_vehicle_orign['distance'];
                                                    $return_pickup_duration_time = $distance_from_droping_point_to_vehicle_orign['duration'];

                                                    // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                    preg_match('/(\d+) hour/', $return_pickup_duration_time, $hoursMatch);
                                                    preg_match('/(\d+) mins/', $return_pickup_duration_time, $minutesMatch);

                                                    // INITIALIZE HOURS AND MINUTES TO ZERO
                                                    $hours = 0;
                                                    $minutes = 0;

                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                    // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59
                                                    $extraHours = floor($minutes / 60);
                                                    $hours += $extraHours;
                                                    $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                                                    // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                                                    $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                                                    $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                                                    // FORMAT THE TIME AS H:i:s
                                                    $formated_return_pickup_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                    $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME');

                                                    $TOTAL_DROP_KM = $return_pickup_distance;
                                                    $TOTAL_DROP_DURATION = $formated_return_pickup_duration;
                                                else :
                                                    $TOTAL_DROP_KM = 0;
                                                    $TOTAL_DROP_DURATION = "00:00:00";
                                                endif;

                                                $TOTAL_RUNNING_KM = $RUNNING_KM + $TOTAL_DROP_KM;

                                                $TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS = strtotime("1970-01-01 $TOTAL_RUNNING_TRAVEL_TIME UTC");
                                                $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS = strtotime("1970-01-01 $SIGHT_SEEING_TRAVELLING_TIME UTC");
                                                $RUNNING_TIME_IN_SECONDS = strtotime("1970-01-01 $formated_runningtime_duration UTC");
                                                $formated_return_pickup_duration = strtotime("1970-01-01 $formated_return_pickup_duration UTC");
                                                $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME = strtotime("1970-01-01 $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME UTC");

                                                $total_travelling_times_in_Seconds = (($TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS + $RUNNING_TIME_IN_SECONDS + $formated_return_pickup_duration) - ($TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME));

                                                $TOTAL_TRAVELLING_TIME = gmdate('H:i:s', $total_travelling_times_in_Seconds);

                                                // Add the seconds
                                                $total_times_in_Seconds = (($TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS + $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS + $RUNNING_TIME_IN_SECONDS + $formated_return_pickup_duration) - ($TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME));

                                                // Convert total seconds back to time format
                                                $TOTAL_TIME = gmdate('H:i:s', $total_times_in_Seconds);

                                                $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_TRAVELLING_KM;

                                                $time_parts = explode(':', $TOTAL_TIME);
                                                $TOTAL_TIME_hours = intval($time_parts[0]);
                                                $TOTAL_TIME_minutes = intval($time_parts[1]);

                                                // Round the total time based on minutes
                                                if ($TOTAL_TIME_minutes < 30) :
                                                    $TOTAL_HOURS =  $TOTAL_TIME_hours;
                                                else :
                                                    $TOTAL_HOURS = $TOTAL_TIME_hours + 1;
                                                endif;

                                                /* $hours_time_limit_id = getTIMELIMIT($vendor_vehicle_type_ID, 'get_time_limit_id_from_hour_limit', $vendor_id, $TOTAL_HOURS);

                                                $km_time_limit_id = getTIMELIMIT($vendor_vehicle_type_ID, 'get_time_limit_id_from_km_limit', $vendor_id, $TOTAL_HOURS, $TOTAL_KM); */

                                                $time_limit_id = getTIMELIMIT($vendor_vehicle_type_ID, 'get_time_limit_id_for_hours_and_km', $vendor_id, $TOTAL_HOURS, $TOTAL_KM);

                                                /* echo "TOTAL_KM => $TOTAL_KM | km_time_limit_id => $km_time_limit_id";
                                            echo "<br>";
                                            echo "TOTAL_HOURS => $TOTAL_HOURS | hours_time_limit_id => $hours_time_limit_id";
                                            echo "<br>"; */

                                                /* // Determine which time limit ID to use
                                                if ($km_time_limit_id == $hours_time_limit_id) :
                                                    $time_limit_id = $km_time_limit_id;
                                                    $getTIMELIMITID = $km_time_limit_id;
                                                elseif ($km_time_limit_id > $hours_time_limit_id) :
                                                    // If KM limit is greater
                                                    $time_limit_id = $km_time_limit_id;
                                                    $getTIMELIMITID = $km_time_limit_id;
                                                elseif ($km_time_limit_id < $hours_time_limit_id) :
                                                    // If hour limit is greater
                                                    $time_limit_id = $hours_time_limit_id;
                                                    $getTIMELIMITID = $hours_time_limit_id;
                                                endif; */

                                                /* echo "final_time_limit_id => $time_limit_id";
                                            echo "<br>"; */

                                                $getTIMELIMITID = $time_limit_id;
                                                $TOTAL_ALLOWED_LOCAL_KM = getTIMELIMIT($time_limit_id, 'km_limit', '', '', '');

                                                if ($TOTAL_KM > $TOTAL_ALLOWED_LOCAL_KM):
                                                    $TOTAL_LOCAL_EXTRA_KM = ($TOTAL_KM - $TOTAL_ALLOWED_LOCAL_KM);
                                                else:
                                                    $TOTAL_LOCAL_EXTRA_KM = 0;
                                                endif;

                                                /* echo "TOTAL_KM => $TOTAL_KM | TOTAL_ALLOWED_LOCAL_KM => $TOTAL_ALLOWED_LOCAL_KM | TOTAL_LOCAL_EXTRA_KM => $TOTAL_LOCAL_EXTRA_KM <br>";
                                            echo "<br>";
                                            echo "<br>"; */

                                                $vehicle_cost_for_the_day = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vendor_branch_id, $vendor_vehicle_type_ID, $time_limit_id);

                                                if ($total_no_of_itineary_plan_route_details == $route_count) :
                                                    # LOCAL TRIP LAST DAY TOLL CHAREGS
                                                    if ($get_via_route_IDs):
                                                        $toll_source_location = $location_name;
                                                        $get_via_route_location_IDs = implode(',', $get_via_route_IDs);

                                                        // VIA ROUTE LOCATION NAME
                                                        $via_route_names = getSTOREDLOCATION_VIAROUTE_DETAILS($location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');
                                                        $toll_destination_location = $next_visiting_location;

                                                        // Check if $via_route_names is valid and is an array
                                                        if (!is_array($via_route_names)) :
                                                            $via_route_names = []; // Ensure it is an array even if empty
                                                        endif;

                                                        // Initialize an array to store locations
                                                        $toll_charge_locations = [];

                                                        // Step 1: Source to the first VIA route (if available)
                                                        if (!empty($via_route_names)) :
                                                            $toll_charge_locations[] = [$toll_source_location, $via_route_names[0]];
                                                        endif;

                                                        // Step 2: Via to Via (for multiple via routes)
                                                        for ($i = 0; $i < count($via_route_names) - 1; $i++) :
                                                            $toll_charge_locations[] = [$via_route_names[$i], $via_route_names[$i + 1]];
                                                        endfor;

                                                        // Step 3: Last VIA route to Destination
                                                        if (!empty($via_route_names)) :
                                                            $toll_charge_locations[] = [$via_route_names[count($via_route_names) - 1], $toll_destination_location];
                                                        else :
                                                            $toll_charge_locations[] = [$toll_source_location, $toll_destination_location]; // Direct route if no via routes
                                                        endif;

                                                        // Step 4: Destination to Vehicle Origin
                                                        $toll_charge_locations[] = [$toll_destination_location, $vehicle_origin];

                                                        // Dynamically assign variables for each location pair and calculate toll charges
                                                        foreach ($toll_charge_locations as $index => $location_pair) :
                                                            $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                            // LOCAL TRIP TOLL CHARGE CALCULATION FOR SOURCE & VIA & DESTINATION & BACK TO ORIGIN
                                                            if ($get_location_id):
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                            else:
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                            endif;
                                                        endforeach;

                                                    else:
                                                        $toll_source_location = $location_name;
                                                        $toll_destination_location = $next_visiting_location;

                                                        // Initialize an array to store locations
                                                        $toll_charge_locations = [];

                                                        // Step 1: Source to Destination
                                                        $toll_charge_locations[] = [$toll_source_location, $toll_destination_location];

                                                        // Step 2: Destination to Vehicle Origin
                                                        $toll_charge_locations[] = [$toll_destination_location, $vehicle_origin];

                                                        // Dynamically assign variables for each location pair and calculate toll charges
                                                        foreach ($toll_charge_locations as $index => $location_pair) :
                                                            $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                            // LOCAL TRIP TOLL CHARGE CALCULATION FOR SOURCE & VIA & DESTINATION & BACK TO ORIGI
                                                            if ($get_location_id):
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                            else:
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                else:
                                                    # LOCAL TRIP IN BETWEEN DAYS TOLL CHARGES
                                                    if ($get_via_route_IDs):

                                                        $toll_source_location = $location_name;
                                                        $get_via_route_location_IDs = implode(',', $get_via_route_IDs);

                                                        // VIA ROUTE LOCATION NAME
                                                        $via_route_names = getSTOREDLOCATION_VIAROUTE_DETAILS($location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');
                                                        $toll_destination_location = $next_visiting_location;

                                                        // Check if $via_route_names is valid and is an array
                                                        if (!is_array($via_route_names)) :
                                                            $via_route_names = []; // Ensure it is an array even if empty
                                                        endif;

                                                        // Initialize an array to store locations
                                                        $toll_charge_locations = [];

                                                        // Set the first location pair (source and first VIA route location)
                                                        if (!empty($via_route_names)) :
                                                            $toll_charge_locations[] = [$toll_source_location, $via_route_names[0]];
                                                        endif;

                                                        // Loop through the VIA route names to create subsequent location pairs
                                                        for ($i = 0; $i < count($via_route_names) - 1; $i++) :
                                                            $toll_charge_locations[] = [$via_route_names[$i], $via_route_names[$i + 1]];
                                                        endfor;

                                                        // Set the last location pair (last VIA route location and destination)
                                                        if (!empty($via_route_names)) :
                                                            $toll_charge_locations[] = [$via_route_names[count($via_route_names) - 1], $toll_destination_location];
                                                        else :
                                                            $toll_charge_locations[] = [$toll_source_location, $toll_destination_location]; // Direct route if no via routes
                                                        endif;

                                                        // Dynamically assign variables for each location pair and calculate toll charges
                                                        foreach ($toll_charge_locations as $index => $location_pair) :
                                                            $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                            // LOCAL TRIP TOLL CHARGE CALCULATION FOR SOURCE & VIA & DESTINATION
                                                            if ($get_location_id):
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                            else:
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                            endif;
                                                        endforeach;

                                                    else:
                                                        $toll_source_location = $location_name;
                                                        $toll_destination_location = $next_visiting_location;

                                                        // Initialize an array to store locations
                                                        $toll_charge_locations = [];
