
                                                if ($total_no_of_itineary_plan_route_details == $route_count) :
                                                    # OUTSTATION TRIP LAST DAY TOLL CHARGES
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

                                                            // OUTSTATION TRIP TOLL CHARGE CALCULATION FOR SOURCE & VIA & DESTINATION & BACK TO ORIGI
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

                                                            // OUTSTATION TRIP TOLL CHARGE CALCULATION FOR SOURCE & VIA & DESTINATION & BACK TO ORIGI
                                                            if ($get_location_id):
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                            else:
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                else:
                                                    # OUTSTATION TRIP IN BETWEEN DAYS TOLL CHARGES
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

                                                            // OUTSTATION TRIP TOLL CHARGE CALCULATION FOR SOURCE & VIA & DESTINATION
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

                                                        // Dynamically assign variables for each location pair and calculate toll charges
                                                        foreach ($toll_charge_locations as $index => $location_pair) :
                                                            $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                            // OUTSTATION TRIP TOLL CHARGE CALCULATION FOR SOURCE & DESTINATION
                                                            if ($get_location_id):
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                            else:
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                endif;

                                                // TOLL CHARGE CALCULATION
                                                $VEHICLE_TOLL_CHARGE = $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE;

                                                //PARKING CHARGE CALCULATION
                                                $VEHICLE_PARKING_CHARGE = getITINERARY_HOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($vehicle_type_id, $itinerary_plan_ID, $itinerary_route_ID, 'total_hotspot_parking_charges');

                                            /* echo "<tr>";
                                                echo "<td>$itinerary_route_date <br> Day $route_count (OUT) <br> $route_start_time - $route_end_time</td>";
                                                echo "<td>From : $location_name <br> To: $next_visiting_location</td>";
                                                echo "<td>$TOTAL_RUNNING_KM / $TOTAL_TRAVELLING_TIME</td>";
                                                echo "<td>$SIGHT_SEEING_TRAVELLING_KM / $SIGHT_SEEING_TRAVELLING_TIME </td>";
                                                echo "<td>$TOTAL_KM / $TOTAL_TIME</td>";
                                                echo "<td>RENTAL : $vehicle_cost_for_the_day <br> TOLL_CHARGE : $VEHICLE_TOLL_CHARGE <br> PARKING_CHARGE : $VEHICLE_PARKING_CHARGE <br> DRIVER_CHARGES : $TOTAL_DRIVER_CHARGES <br> PERMIT_CHARGES : $permit_charges <br> BEFORE_6AM_AFTER_8PM_CHARGES : $morning_extra_time [$VENDOR_VEHICLE_MORNING_CHARGES + $DRIVER_MORINING_CHARGES] | $evening_extra_time [$VENDOR_VEHICLE_EVENING_CHARGES + $DRIVER_EVEINING_CHARGES] </td>";
                                                echo "</tr>"; */

                                            endif;
                                        endif;

                                        $TOTAL_VEHICLE_AMOUNT = ($vehicle_cost_for_the_day + $VEHICLE_TOLL_CHARGE + $VEHICLE_PARKING_CHARGE + $TOTAL_DRIVER_CHARGES + $permit_charges + $DRIVER_MORINING_CHARGES + $VENDOR_VEHICLE_MORNING_CHARGES + $DRIVER_EVEINING_CHARGES + $VENDOR_VEHICLE_EVENING_CHARGES);

                                        $OVERALL_TOTAL_KM += $TOTAL_KM;
                                        $OVERALL_TOTAL_TIME[] = $TOTAL_TIME;
                                        $OVERALL_RENDAL_CHARGES += $vehicle_cost_for_the_day;
                                        $array_of_vehicle_cost_for_the_day[] = $vehicle_cost_for_the_day;
                                        $OVERALL_VEHICLE_TOLL_CHARGE += $VEHICLE_TOLL_CHARGE;
                                        $OVERALL_VEHICLE_PARKING_CHARGE += $VEHICLE_PARKING_CHARGE;
                                        $OVERALL_TOTAL_DRIVER_CHARGES += $TOTAL_DRIVER_CHARGES;
                                        $OVERALL_PERMIT_CHARGES += $permit_charges;
                                        $OVERALL_BEFORE_6AM_EXTRA_TIME += $morning_extra_time;
                                        $OVERALL_AFTER_8PM_EXTRA_TIME += $evening_extra_time;
                                        $OVERALL_DRIVER_MORINING_CHARGES += $DRIVER_MORINING_CHARGES;
                                        $OVERALL_VENDOR_VEHICLE_MORNING_CHARGES += $VENDOR_VEHICLE_MORNING_CHARGES;
                                        $OVERALL_DRIVER_EVEINING_CHARGES += $DRIVER_EVEINING_CHARGES;
                                        $OVERALL_VENDOR_VEHICLE_EVENING_CHARGES += $VENDOR_VEHICLE_EVENING_CHARGES;
                                        $OVERALL_TOTAL_VEHICLE_AMOUNT += $TOTAL_VEHICLE_AMOUNT;

                                        if ($travel_type == 2) :
                                            $getTIMELIMITID = 0;
                                            $TOTAL_LOCAL_EXTRA_KM = 0;
                                            $TOTAL_LOCAL_EXTRA_KM_CHARGES = 0;
                                            $OVERALL_OUTSTATION_KM += $TOTAL_KM;
                                            $TOTAL_ALLOWED_LOCAL_KM = 0;
                                        else :
                                            $getTIMELIMITID = $getTIMELIMITID;
                                            $TOTAL_LOCAL_EXTRA_KM = $TOTAL_LOCAL_EXTRA_KM;
                                            if ($TOTAL_LOCAL_EXTRA_KM > 0):
                                                $TOTAL_LOCAL_EXTRA_KM_CHARGES = ($TOTAL_LOCAL_EXTRA_KM * $extra_km_charge);
                                            else:
                                                $TOTAL_LOCAL_EXTRA_KM_CHARGES = 0;
                                            endif;
                                            $OVERALL_OUTSTATION_KM += 0;
                                            $TOTAL_ALLOWED_LOCAL_KM = $TOTAL_ALLOWED_LOCAL_KM;
                                        endif;

                                        /* echo "itinerary_plan_ID => $itinerary_plan_ID | itinerary_route_ID => $itinerary_route_ID | vehicle_type_id => $vehicle_type_id | vendor_id => $vendor_id | vehicle_id => $vehicle_id <br>";
                                    echo "TOTAL_LOCAL_EXTRA_KM => $TOTAL_LOCAL_EXTRA_KM <br>";
                                    echo "TOTAL_LOCAL_EXTRA_KM_CHARGES => $TOTAL_LOCAL_EXTRA_KM_CHARGES <br>";
                                    echo "OVERALL_OUTSTATION_KM => $OVERALL_OUTSTATION_KM <br>";
                                    echo "TOTAL_ALLOWED_LOCAL_KM => $TOTAL_ALLOWED_LOCAL_KM <br>";
                                    echo "getTIMELIMITID => $getTIMELIMITID <br><br>"; */

                                        if (in_array($array_of_vehicle_cost_for_the_day, array(0))) :
                                            $errors['all_vehicle_cost_zero'] = "All vehicle cost entries are 0. No eligible vehicle records were processed.";
                                        endif;

                                        $OVERALL_LOCAL_KM += $TOTAL_ALLOWED_LOCAL_KM;
                                        $OVERALL_LOCAL_EXTRA_KM += $TOTAL_LOCAL_EXTRA_KM;
                                        $OVERALL_LOCAL_EXTRA_KM_CHARGES += $TOTAL_LOCAL_EXTRA_KM_CHARGES;

                                        $TOTAL_RUNNING_KM = $TOTAL_RUNNING_KM ?? 0;
                                        $TOTAL_TRAVELLING_TIME = $TOTAL_TRAVELLING_TIME ?? "00:00:00";
                                        $SIGHT_SEEING_TRAVELLING_KM = $SIGHT_SEEING_TRAVELLING_KM ?? 0;
                                        $SIGHT_SEEING_TRAVELLING_TIME = $SIGHT_SEEING_TRAVELLING_TIME ?? "00:00:00";
                                        $TOTAL_KM = $TOTAL_KM ?? 0;
                                        $TOTAL_TIME = $TOTAL_TIME ?? "00:00:00";
                                        $vehicle_cost_for_the_day = $vehicle_cost_for_the_day ?? 0;
                                        $VEHICLE_TOLL_CHARGE = $VEHICLE_TOLL_CHARGE ?? 0;
                                        $VEHICLE_PARKING_CHARGE = $VEHICLE_PARKING_CHARGE ?? 0;
                                        $TOTAL_DRIVER_CHARGES = $TOTAL_DRIVER_CHARGES ?? 0;
                                        $permit_charges = $permit_charges ?? 0;
                                        $morning_extra_time = $morning_extra_time ?? "00:00:00";
                                        $evening_extra_time = $evening_extra_time ?? "00:00:00";
                                        $DRIVER_MORINING_CHARGES = $DRIVER_MORINING_CHARGES ?? 0;
                                        $VENDOR_VEHICLE_MORNING_CHARGES = $VENDOR_VEHICLE_MORNING_CHARGES ?? 0;
                                        $DRIVER_EVEINING_CHARGES = $DRIVER_EVEINING_CHARGES ?? 0;
                                        $VENDOR_VEHICLE_EVENING_CHARGES = $VENDOR_VEHICLE_EVENING_CHARGES ?? 0;
                                        $TOTAL_VEHICLE_AMOUNT = $TOTAL_VEHICLE_AMOUNT ?? 0;

                                        //INSERT ALL THE ELIGIBLE VENDOR WISE VEHICLE LIST 
                                        $vendor_vehicle_details_arrFields = array('`itinerary_plan_id`', '`itinerary_route_id`', '`vehicle_type_id`', '`vendor_id`', '`vendor_vehicle_type_id`', '`vehicle_qty`', '`vehicle_id`', '`vendor_branch_id`', '`time_limit_id`', '`travel_type`', '`itinerary_route_date`', '`itinerary_route_location_from`', '`itinerary_route_location_to`', '`total_running_km`', '`total_running_time`', '`total_siteseeing_km`', '`total_siteseeing_time`', '`total_pickup_km`', '`total_pickup_duration`', '`total_drop_km`', '`total_drop_duration`', '`total_extra_km`', '`extra_km_rate`', '`total_extra_km_charges`', '`total_travelled_km`', '`total_travelled_time`', '`vehicle_rental_charges`', '`vehicle_toll_charges`', '`vehicle_parking_charges`', '`vehicle_driver_charges`', '`vehicle_permit_charges`', '`before_6_am_extra_time`', '`after_8_pm_extra_time`', '`before_6_am_charges_for_driver`', '`before_6_am_charges_for_vehicle`', '`after_8_pm_charges_for_driver`', '`after_8_pm_charges_for_vehicle`', '`total_vehicle_amount`', '`createdby`', '`status`');

                                        if ($getTIMELIMITID):
                                            $getTIMELIMITID = $getTIMELIMITID;
                                        else:
                                            $getTIMELIMITID = "0";
                                        endif;

                                        $vendor_vehicle_details_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "$vehicle_type_id", "$vendor_id", "$vendor_vehicle_type_ID", "1", "$vehicle_id", "$vendor_branch_id", "$getTIMELIMITID", "$travel_type", "$itinerary_route_date", "$location_name", "$next_visiting_location", "$TOTAL_RUNNING_KM", "$TOTAL_TRAVELLING_TIME", "$SIGHT_SEEING_TRAVELLING_KM", "$SIGHT_SEEING_TRAVELLING_TIME", "$TOTAL_PICKUP_KM", "$TOTAL_PICKUP_DURATION", "$TOTAL_DROP_KM", "$TOTAL_DROP_DURATION", "$TOTAL_LOCAL_EXTRA_KM", "$extra_km_charge", "$TOTAL_LOCAL_EXTRA_KM_CHARGES", "$TOTAL_KM", "$TOTAL_TIME", "$vehicle_cost_for_the_day", "$VEHICLE_TOLL_CHARGE", "$VEHICLE_PARKING_CHARGE", "$TOTAL_DRIVER_CHARGES", "$permit_charges", "$morning_extra_time", "$evening_extra_time", "$DRIVER_MORINING_CHARGES", "$VENDOR_VEHICLE_MORNING_CHARGES", "$DRIVER_EVEINING_CHARGES", "$VENDOR_VEHICLE_EVENING_CHARGES", "$TOTAL_VEHICLE_AMOUNT", "$logged_user_id", "1");

                                        $check_vehicle_details_already_available = sqlQUERY_LABEL("SELECT COALESCE(SUM(CASE WHEN vehicle_qty = 0 THEN 1 ELSE vehicle_qty END), 0) AS vehicle_qty FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        while ($fetch_vehicle_vendor_data = sqlFETCHARRAY_LABEL($check_vehicle_details_already_available)) :
                                            $vehicle_qty = $fetch_vehicle_vendor_data['vehicle_qty'];
                                        endwhile;

                                        if ($vehicle_cost_for_the_day == 0) {
                                            continue;
                                        }

                                        if ($vehicle_qty < $get_vehicle_type_count) :
                                            if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_vehicle_details", $vendor_vehicle_details_arrFields, $vendor_vehicle_details_arrValues, '')) :
                                            endif;
                                        else :
                                            $vendor_vehicle_details_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id' ";

                                            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_vendor_vehicle_details", $vendor_vehicle_details_arrFields, $vendor_vehicle_details_arrValues, $vendor_vehicle_details_sqlwhere)) :
                                            endif;
                                        endif;
                                        $previous_location_city = $source_location_city;
                                        $previous_destination_location_city = $destination_location_city;
                                    endwhile;
                                /* echo "</table>";
                                echo "<br>"; */
                                else :
                                    $errors['no_more_route_details_found'] = "No more route details found !!!";
                                endif;
                            endif;

                            $overall_time_total_hours = NULL;
                            $overall_time_total_minutes = NULL;

                            // Loop through each time and calculate total hours and minutes
                            /* foreach ($OVERALL_TOTAL_TIME as $time) :
                            list($hours, $minutes, $seconds) = explode(":", $time);

                            // Convert time to total minutes
                            $overall_time_total_minutes += $hours * 60 + $minutes;
                            endforeach; */

                            if (isset($OVERALL_TOTAL_TIME) && is_iterable($OVERALL_TOTAL_TIME)) :
                                foreach ($OVERALL_TOTAL_TIME as $time) :
                                    list($hours, $minutes, $seconds) = explode(":", $time);

                                    // Convert time to total minutes
                                    $overall_time_total_minutes += $hours * 60 + $minutes;
                                endforeach;
                            endif;

                            // Calculate total hours from total minutes
                            $overall_time_total_hours += floor($overall_time_total_minutes / 60);
                            $overall_time_total_minutes %= 60; // Remaining minutes after converting to hours

                            $OVERALL_TOTAL_HOURS_n_MINS = $overall_time_total_hours . '.' . $overall_time_total_minutes;

                            $PER_DAY_KM_LIMIT = $get_kms_limit;

                            $select_total_outstaion_day_data = sqlQUERY_LABEL("SELECT COUNT(*) AS count FROM ( SELECT `itinerary_plan_vendor_vehicle_details_ID`, `travel_type` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id' ORDER BY `itinerary_plan_vendor_vehicle_details_ID` DESC LIMIT $total_no_of_itineary_plan_route_details) AS limited_rows WHERE `travel_type` = '2'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                            /* $total_outstation_day_available_count = sqlNUMOFROW_LABEL($select_total_outstaion_day_data); */
                            while ($fetch_total_outstaion_day_data = sqlFETCHARRAY_LABEL($select_total_outstaion_day_data)) :
                                $total_outstation_day_available_count = $fetch_total_outstaion_day_data['count'];
                            endwhile;

                            $TOTAL_ITINEARY_ALLOWED_KM = ($PER_DAY_KM_LIMIT * $total_outstation_day_available_count);
                            $PER_EXTRA_KM_CHARGE = $extra_km_charge;

                            /* echo "OVERALL_OUTSTATION_KM => $OVERALL_OUTSTATION_KM | TOTAL_ITINEARY_ALLOWED_KM => $TOTAL_ITINEARY_ALLOWED_KM | PER_EXTRA_KM_CHARGE => $PER_EXTRA_KM_CHARGE | TOTAL_EXTRA_KM => $TOTAL_EXTRA_KM <br>"; */

                            $get_extra_kms = ($OVERALL_OUTSTATION_KM - $TOTAL_ITINEARY_ALLOWED_KM);

                            if ($get_extra_kms > 0) :
                                $TOTAL_EXTRA_KM = $get_extra_kms;
                                $TOTAL_EXTRA_KM_CHARGE = ($TOTAL_EXTRA_KM * $PER_EXTRA_KM_CHARGE);
                            else :
                                $TOTAL_EXTRA_KM = 0;
                                $TOTAL_EXTRA_KM_CHARGE = 0;
                            endif;

                            $OVERALL_TOTAL_VEHICLE_AMOUNT = $OVERALL_TOTAL_VEHICLE_AMOUNT + $TOTAL_EXTRA_KM_CHARGE + $OVERALL_LOCAL_EXTRA_KM_CHARGES;

                            if ($vendor_branch_gst > 0) :
                                if ($vendor_branch_gst_type == 1) :
                                    // For Inclusive GST
                                    $new_total_vehicle_cost = $OVERALL_TOTAL_VEHICLE_AMOUNT / (1 + ($vendor_branch_gst / 100));
                                    $new_total_vehicle_tax_amt = ($OVERALL_TOTAL_VEHICLE_AMOUNT - $new_total_vehicle_cost);
                                elseif ($vendor_branch_gst_type == 2) :
                                    // For Exclusive GST
                                    $new_total_vehicle_cost = $OVERALL_TOTAL_VEHICLE_AMOUNT;
                                    $new_total_vehicle_tax_amt = ($OVERALL_TOTAL_VEHICLE_AMOUNT * $vendor_branch_gst / 100);
                                endif;
                            else :
                                $new_total_vehicle_cost = $OVERALL_TOTAL_VEHICLE_AMOUNT;
                                $new_total_vehicle_tax_amt = 0;
                            endif;

                            $TOTAL_VENDOR_MARGIN_AMOUNT = (($OVERALL_TOTAL_VEHICLE_AMOUNT * $vendor_margin) / 100);

                            if ($vendor_margin_gst_percentage > 0) :
                                if ($vendor_margin_gst_type == 1) :
                                    // For Inclusive GST
                                    $new_total_margin_amount = $TOTAL_VENDOR_MARGIN_AMOUNT / (1 + ($vendor_margin_gst_percentage / 100));
                                    $new_total_margin_service_tax_amt = ($TOTAL_VENDOR_MARGIN_AMOUNT - $new_total_margin_amount);
                                elseif ($vendor_margin_gst_type == 2) :
                                    // For Exclusive GST
                                    $new_total_margin_amount = $TOTAL_VENDOR_MARGIN_AMOUNT;
                                    $new_total_margin_service_tax_amt = ($TOTAL_VENDOR_MARGIN_AMOUNT * $vendor_margin_gst_percentage / 100);
                                endif;
                            else :
                                $new_total_margin_amount = $TOTAL_VENDOR_MARGIN_AMOUNT;
                                $new_total_margin_service_tax_amt = 0;
                            endif;

                            $VEHICLE_GRAND_TOTAL_AMOUNT = ($new_total_vehicle_cost + $new_total_vehicle_tax_amt + $new_total_margin_amount + $new_total_margin_service_tax_amt);

                            $array_of_vehicle_cost_for_the_day = $array_of_vehicle_cost_for_the_day ?? [];

                            $OVERALL_TOTAL_KM = $OVERALL_TOTAL_KM ?? 0;
                            $OVERALL_OUTSTATION_KM = $OVERALL_OUTSTATION_KM ?? 0;
                            $OVERALL_TOTAL_HOURS_n_MINS = $OVERALL_TOTAL_HOURS_n_MINS ?? "00:00:00";
                            $OVERALL_RENDAL_CHARGES = $OVERALL_RENDAL_CHARGES ?? 0;
                            $OVERALL_VEHICLE_TOLL_CHARGE = $OVERALL_VEHICLE_TOLL_CHARGE ?? 0;
                            $OVERALL_VEHICLE_PARKING_CHARGE = $OVERALL_VEHICLE_PARKING_CHARGE ?? 0;
                            $OVERALL_TOTAL_DRIVER_CHARGES = $OVERALL_TOTAL_DRIVER_CHARGES ?? 0;
                            $OVERALL_PERMIT_CHARGES = $OVERALL_PERMIT_CHARGES ?? 0;
                            $OVERALL_BEFORE_6AM_EXTRA_TIME = $OVERALL_BEFORE_6AM_EXTRA_TIME ?? "00:00:00";
                            $OVERALL_AFTER_8PM_EXTRA_TIME = $OVERALL_AFTER_8PM_EXTRA_TIME ?? "00:00:00";
                            $OVERALL_DRIVER_MORINING_CHARGES = $OVERALL_DRIVER_MORINING_CHARGES ?? 0;
                            $OVERALL_VENDOR_VEHICLE_MORNING_CHARGES = $OVERALL_VENDOR_VEHICLE_MORNING_CHARGES ?? 0;
                            $OVERALL_DRIVER_EVEINING_CHARGES = $OVERALL_DRIVER_EVEINING_CHARGES ?? 0;
                            $OVERALL_VENDOR_VEHICLE_EVENING_CHARGES = $OVERALL_VENDOR_VEHICLE_EVENING_CHARGES ?? 0;
                            $PER_EXTRA_KM_CHARGE = $PER_EXTRA_KM_CHARGE ?? 0;
                            $TOTAL_ITINEARY_ALLOWED_KM = $TOTAL_ITINEARY_ALLOWED_KM ?? 0;
                            $TOTAL_EXTRA_KM = $TOTAL_EXTRA_KM ?? 0;
                            $TOTAL_EXTRA_KM_CHARGE = $TOTAL_EXTRA_KM_CHARGE ?? 0;
                            $vendor_branch_gst = $vendor_branch_gst ?? 0;
                            $new_total_vehicle_tax_amt = $new_total_vehicle_tax_amt ?? 0;
                            $new_total_vehicle_cost = $new_total_vehicle_cost ?? 0;
                            $vendor_margin = $vendor_margin ?? 0;
                            $vendor_margin_gst_percentage = $vendor_margin_gst_percentage ?? 0;
                            $new_total_margin_amount = $new_total_margin_amount ?? 0;
                            $new_total_margin_service_tax_amt = $new_total_margin_service_tax_amt ?? 0;
                            $VEHICLE_GRAND_TOTAL_AMOUNT = $VEHICLE_GRAND_TOTAL_AMOUNT ?? 0;

                            //INSERT ALL THE ELIGIBLE VENDOR WISE VEHICLE LIST 
                            $vendor_eligible_list_arrFields = array('`itinerary_plan_id`', '`vehicle_type_id`', '`vendor_id`', '`outstation_allowed_km_per_day`', '`vendor_vehicle_type_id`',  '`total_vehicle_qty`', '`vehicle_id`', '`vendor_branch_id`', '`vehicle_orign`', '`vehicle_count`', '`total_kms`', '`total_outstation_km`', '`total_time`', '`total_rental_charges`', '`total_toll_charges`', '`total_parking_charges`', '`total_driver_charges`', '`total_permit_charges`', '`total_before_6_am_extra_time`', '`total_after_8_pm_extra_time`', '`total_before_6_am_charges_for_driver`', '`total_before_6_am_charges_for_vehicle`', '`total_after_8_pm_charges_for_driver`', '`total_after_8_pm_charges_for_vehicle`', '`extra_km_rate`', '`total_allowed_kms`', '`total_extra_kms`', '`total_extra_kms_charge`',  '`total_allowed_local_kms`', '`total_extra_local_kms`', '`total_extra_local_kms_charge`', '`vehicle_gst_type`', '`vehicle_gst_percentage`', '`vehicle_gst_amount`', '`vehicle_total_amount`', '`vendor_margin_percentage`', '`vendor_margin_gst_type`', '`vendor_margin_gst_percentage`', '`vendor_margin_amount`', '`vendor_margin_gst_amount`', '`vehicle_grand_total`', '`createdby`', '`status`');

                            $vendor_eligible_list_arrValues = array("$itinerary_plan_ID", "$vehicle_type_id", "$vendor_id", "$get_kms_limit", "$vendor_vehicle_type_ID", "1", "$vehicle_id", "$vendor_branch_id", "$vehicle_origin", "1", "$OVERALL_TOTAL_KM", "$OVERALL_OUTSTATION_KM", "$OVERALL_TOTAL_HOURS_n_MINS", "$OVERALL_RENDAL_CHARGES", "$OVERALL_VEHICLE_TOLL_CHARGE", "$OVERALL_VEHICLE_PARKING_CHARGE", "$OVERALL_TOTAL_DRIVER_CHARGES", "$OVERALL_PERMIT_CHARGES", "$OVERALL_BEFORE_6AM_EXTRA_TIME", "$OVERALL_AFTER_8PM_EXTRA_TIME",  "$OVERALL_DRIVER_MORINING_CHARGES", "$OVERALL_VENDOR_VEHICLE_MORNING_CHARGES", "$OVERALL_DRIVER_EVEINING_CHARGES", "$OVERALL_VENDOR_VEHICLE_EVENING_CHARGES", "$PER_EXTRA_KM_CHARGE", "$TOTAL_ITINEARY_ALLOWED_KM", "$TOTAL_EXTRA_KM", "$TOTAL_EXTRA_KM_CHARGE", "$OVERALL_LOCAL_KM", "$OVERALL_LOCAL_EXTRA_KM", "$OVERALL_LOCAL_EXTRA_KM_CHARGES", "$vendor_branch_gst_type", "$vendor_branch_gst", "$new_total_vehicle_tax_amt", "$new_total_vehicle_cost", "$vendor_margin", "$vendor_margin_gst_type", "$vendor_margin_gst_percentage", "$new_total_margin_amount", "$new_total_margin_service_tax_amt", "$VEHICLE_GRAND_TOTAL_AMOUNT", "$logged_user_id", "1");

                            /* $delete_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` != '$vendor_id' AND `vehicle_type_id` != '$vehicle_type_id' AND `vendor_branch_id` != '$vendor_branch_id'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                            $delete_itinerary_plan_vendor_eligible_list = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` != '$vendor_id' AND `vehicle_type_id` != '$vehicle_type_id' AND `vendor_branch_id` != '$vendor_branch_id'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL()); */

                            /* $check_vehicle_eligible_list_already_available = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                            $total_vehicle_eligible_list_available_count = sqlNUMOFROW_LABEL($check_vehicle_eligible_list_already_available); */

                            $check_vehicle_eligible_list_already_available = sqlQUERY_LABEL("SELECT COALESCE(SUM(CASE WHEN total_vehicle_qty = 0 THEN 1 ELSE total_vehicle_qty END), 0) AS total_vehicle_qty FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                            while ($fetch_vehicle_vendor_eligible_data = sqlFETCHARRAY_LABEL($check_vehicle_eligible_list_already_available)) :
                                $total_vehicle_qty = $fetch_vehicle_vendor_eligible_data['total_vehicle_qty'];
                            endwhile;

                            if ($vehicle_cost_for_the_day == 0) {
                                continue;
                            }

                            $processed_vehicle_records++; // Count valid entries

                            if ($total_vehicle_qty < $get_vehicle_type_count) :
                                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_eligible_list", $vendor_eligible_list_arrFields, $vendor_eligible_list_arrValues, '')) :
                                    $itinerary_plan_vendor_eligible_ID = sqlINSERTID_LABEL();
                                    $update_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_vendor_vehicle_details` SET `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id' ORDER BY `itinerary_plan_vendor_vehicle_details_ID` DESC LIMIT $total_no_of_itineary_plan_route_details") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                endif;
                            else :
                                $vendor_eligible_list_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vehicle_id` = '$vehicle_id' AND `vendor_branch_id` = '$vendor_branch_id' ";
                                $check_vehicle_eligible_list_already_available_data = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID` FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id' ORDER BY `itinerary_plan_vendor_eligible_ID` ASC LIMIT $vh_count") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                while ($fetch_vehicle_eligible_data = sqlFETCHARRAY_LABEL($check_vehicle_eligible_list_already_available_data)) :
                                    $itinerary_plan_vendor_eligible_ID = $fetch_vehicle_eligible_data['itinerary_plan_vendor_eligible_ID'];
                                endwhile;
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_vendor_eligible_list", $vendor_eligible_list_arrFields, $vendor_eligible_list_arrValues, $vendor_eligible_list_sqlwhere)) :
                                    $update_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_vendor_vehicle_details` SET `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `vendor_branch_id` = '$vendor_branch_id' ORDER BY `itinerary_plan_vendor_vehicle_details_ID` DESC LIMIT $total_no_of_itineary_plan_route_details") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                endif;
                            endif;


                        endfor;
                    /*   if ($processed_vehicle_records == 0) {
                            $errors['all_vehicle_cost_zero'] = "All vehicle cost entries are 0. No eligible vehicle records were processed.";
                        } */
                    endwhile;

                    $select_itineary_required_vehicle_details = sqlQUERY_LABEL("SELECT `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details` WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND  `status` = '1' and `deleted` = '0' GROUP BY `vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                    $total_no_of_vehicle_required_count = sqlNUMOFROW_LABEL($select_itineary_required_vehicle_details);
                    if ($total_no_of_vehicle_required_count > 0) :
                        $update_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_vendor_eligible_list` SET `itineary_plan_assigned_status` = '0' WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' ORDER BY `itinerary_plan_vendor_eligible_ID` ASC") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                        while ($fetch_vehicle_eligible_data = sqlFETCHARRAY_LABEL($select_itineary_required_vehicle_details)) :
                            $vehicle_type_id = $fetch_vehicle_eligible_data['vehicle_type_id'];
                            $total_required_count = getITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_ID, $vehicle_type_id, 'get_vehicle_type_count');
                            $array_of_vehicle_type_id[] = $fetch_vehicle_eligible_data['vehicle_type_id'];
                            $itinerary_plan_vendor_eligible_ID = $fetch_vehicle_eligible_data['itinerary_plan_vendor_eligible_ID'];

                            $update_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_vendor_eligible_list` SET `itineary_plan_assigned_status` = '1' WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehicle_type_id` = '$vehicle_type_id' AND `vehicle_grand_total` > '0' ORDER BY `vehicle_grand_total` ASC LIMIT $total_required_count") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                        endwhile;

                        $required_vehicle_types = implode(',', $array_of_vehicle_type_id);

                        $delete_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_plan_vendor_eligible_list` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehicle_type_id` NOT IN ($required_vehicle_types)") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                        $delete_itinerary_plan_vendor_vehicle_details = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehicle_type_id` NOT IN ($required_vehicle_types) AND `itinerary_plan_vendor_eligible_ID` != '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                    endif;
                else :
                    $errors['no_more_vehicle_found_this_route'] = "No more vehicle found between these routes !!!";
                endif;
            endif;
        else :
            $errors['no_more_vehicle_records'] = "No more vehicle records found !!!";
        endif;

        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            $response['success'] = true;
        endif;

        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
