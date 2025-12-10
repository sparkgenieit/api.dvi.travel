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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'add_vehicle_plan') :

        $response = [];
        $errors = [];

        $itinerary_plan_ID = $_POST['_ID'];

        $current_DATE = date('Y-m-d');

        # GET REQUIRED VEHICLE TYPE WITH COUNT
        $select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT `vehicle_type_id`, SUM(`vehicle_count`) AS vehicle_count FROM `dvi_itinerary_plan_vehicle_details`  WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND  `status` = '1' and `deleted` = '0' GROUP BY `vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        $total_no_of_vehicle_count = sqlNUMOFROW_LABEL($select_itineary_vehicle_details);
        if ($total_no_of_vehicle_count > 0) :
            while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_details)) :
                $vehicle_type_id[] = $fetch_vehicle_data['vehicle_type_id'];
                $vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                $vehicle_count[] = $fetch_vehicle_data['vehicle_count'];
            endwhile;

            $array_of_location_name = getITINEARYROUTE_DETAILS($itinerary_plan_ID, '', 'array_of_location_name', '');
            $array_of_next_visiting_location = getITINEARYROUTE_DETAILS($itinerary_plan_ID, '', 'array_of_next_visiting_location', '');

            $eligible_vehicle_location = array_merge($array_of_location_name, $array_of_next_visiting_location);

            $get_eligible_location_citys = getSTOREDLOCATIONDETAILS($eligible_vehicle_location, 'get_location_city_from_location_name');

            $get_implode_eligible_location_CITYS = implode("','", $get_eligible_location_citys);

            // INITIALIZE AN ARRAY TO STORE AVAILABLE VEHICLE COUNT FOR EACH VEHICLE TYPE
            $available_vehicle_counts = [];

            for ($i = 0; $i < count($vehicle_type_id); $i++) :
                $current_vehicle_type_id = $vehicle_type_id[$i];
                //QUERY TO GET AVAILABLE VEHICLE COUNT DATA

                $check_vehicle_type_wise_vehicle_count_availability = sqlQUERY_LABEL("SELECT COUNT(VEHICLE.`vehicle_type_id`) AS available_vehicle_count FROM `dvi_vehicle` AS VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPES ON VEHICLE.`vehicle_type_id` = VENDOR_VEHICLE_TYPES.`vendor_vehicle_type_ID` AND VEHICLE.`vendor_id` = VENDOR_VEHICLE_TYPES.`vendor_id` LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = VEHICLE.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = VEHICLE.`vendor_branch_id` WHERE VEHICLE.`status` = '1' AND VEHICLE.`deleted` = '0' AND VENDOR_DETAILS.`status` = '1' AND VENDOR_DETAILS.`deleted` = '0' AND VENDOR_BRANCH_DETAILS.`status` = '1' AND VENDOR_BRANCH_DETAILS.`deleted` = '0' AND VENDOR_VEHICLE_TYPES.`vehicle_type_id` = '$current_vehicle_type_id' AND VEHICLE.`owner_city` IN ('$get_implode_eligible_location_CITYS') GROUP BY VEHICLE.`vehicle_type_id`") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                //FETCH AVAILABLE VEHICLE COUNT DATA
                $fetch_available_vehicle_count_data = sqlNUMOFROW_LABEL($check_vehicle_type_wise_vehicle_count_availability);

                //CHECK IF ANY AVILABLE VEHICLE COUNT DATA FOR THE CURRENT VEHICLE TYPE
                if ($fetch_available_vehicle_count_data) :
                    //STORE THE AVAILABLE VEHICLE COUNT FOR THE CURRENT VEHICLE TYPE
                    $available_vehicle_counts[$current_vehicle_type_id] = $fetch_available_vehicle_count_data;
                else :
                    //IF NO DATA AVAILABLE SET TO 0 FOR THIS VEHICLE TYPE
                    $available_vehicle_counts[$current_vehicle_type_id] = 0;
                endif;
            endfor;

            # CHECKING THE SHORTAGES OF VEHICLE TYPE
            for ($i = 0; $i < count($vehicle_type_id); $i++) :
                // Get the vehicle type ID
                $current_vehicle_type_id = $vehicle_type_id[$i];

                if ($fetch_available_vehicle_count_data == 0) :
                    //CHECK IF THERE IS A SHORTAGE OF VEHICLE FOR THE CURRENT VEHICLE TYPE
                    $selected_vehicle_type_ID = $current_vehicle_type_id;
                    $vehicle_type_title = getVEHICLETYPE($selected_vehicle_type_ID, 'get_vehicle_type_title');

                    //CALCULTE THE DIFFERENCE BETWEEN AVAILABLE AND REQUIRED VEHICLES
                    $shortage = $vehicle_count[$i] - $available_vehicle_counts[$current_vehicle_type_id];

                    //CONSTRUCT THE ERROR MESSAGE WITH AVAILABLE AND REQUIRED COUNTS
                    $errors['vehicle_type_not_found'] = "Vehicle type $vehicle_type_title not found.";
                endif;

            endfor;

            if (!empty($errors)) :
                //error call
                $response['success'] = false;
                $response['errors'] = $errors;
            else :
                # CHECK VECHILE ELIGIBILITY BETWEEN THE ITINEARY ROUTE
                $select_itineary_route_plan_locations_wise_vehicle_details = sqlQUERY_LABEL("SELECT VENDOR_DETAILS.`vendor_margin`, VENDOR_DETAILS.`vendor_margin_gst_type`, VENDOR_DETAILS.`vendor_margin_gst_percentage`, VENDOR_BRANCH_DETAILS.`vendor_branch_name`, VENDOR_BRANCH_DETAILS.`vendor_branch_gst_type`, VENDOR_BRANCH_DETAILS.`vendor_branch_gst`, VENDOR_DETAILS.`vendor_name`, VENDOR_VEHICLE_TYPES.`vendor_vehicle_type_ID`, VENDOR_VEHICLE_TYPES.`vehicle_type_id`, VENDOR_VEHICLE_TYPES.`driver_batta`, VENDOR_VEHICLE_TYPES.`food_cost`, VENDOR_VEHICLE_TYPES.`accomodation_cost`, VENDOR_VEHICLE_TYPES.`extra_cost`, VENDOR_VEHICLE_TYPES.`driver_early_morning_charges`, VENDOR_VEHICLE_TYPES.`driver_evening_charges`, VEHICLE.`vehicle_location_id`, VEHICLE.`registration_number`, VEHICLE.`vendor_id`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`vehicle_fc_expiry_date`, VEHICLE.`insurance_end_date`, VEHICLE.`owner_city`,VEHICLE.`extra_km_charge`, STORED_LOCATION.`source_location_lattitude`, STORED_LOCATION.`source_location_longitude`, STORED_LOCATION.`destination_location_lattitude`, STORED_LOCATION.`destination_location_longitude`, VEHICLE.`early_morning_charges`, VEHICLE.`evening_charges` FROM `dvi_stored_locations` AS STORED_LOCATION LEFT JOIN `dvi_vehicle` AS VEHICLE ON VEHICLE.`vehicle_location_id` = STORED_LOCATION.`location_ID` LEFT JOIN `dvi_stored_locations` AS VEHICLE_LOCATION ON VEHICLE_LOCATION.`location_ID` = VEHICLE.`vehicle_location_id` LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPES ON VEHICLE.`vehicle_type_id` = VENDOR_VEHICLE_TYPES.`vendor_vehicle_type_ID` AND VEHICLE.`vendor_id`= VENDOR_VEHICLE_TYPES.`vendor_id` LEFT JOIN `dvi_vendor_details` VENDOR_DETAILS ON VENDOR_DETAILS.`vendor_id` = VEHICLE.`vendor_id` LEFT JOIN `dvi_vendor_branches` VENDOR_BRANCH_DETAILS ON VENDOR_BRANCH_DETAILS.`vendor_branch_id` = VEHICLE.`vendor_branch_id` WHERE VEHICLE.`owner_city` IN ('$get_implode_eligible_location_CITYS') AND VENDOR_DETAILS.`status` = '1' AND VENDOR_DETAILS.`deleted` = '0' AND VENDOR_BRANCH_DETAILS.`status` = '1' AND VENDOR_BRANCH_DETAILS.`deleted` = '0' AND VEHICLE.`status` = '1' and VEHICLE.`deleted` = '0' AND VENDOR_VEHICLE_TYPES.`vehicle_type_id` IN (" . implode(",", $vehicle_type_id) . ") GROUP BY VEHICLE.`vehicle_type_id` ORDER BY VENDOR_DETAILS.`vendor_id`") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL()); //VENDOR_BRANCH_DETAILS.`vendor_branch_id`,
                $total_no_of_available_vehicle_count_for_itineary_route_plan = sqlNUMOFROW_LABEL($select_itineary_route_plan_locations_wise_vehicle_details);

                if ($total_no_of_available_vehicle_count_for_itineary_route_plan > 0) :

                    $permit_cost_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' ";

                    if (sqlACTIONS("DELETE", "dvi_itinerary_plan_route_permit_charge", '', '', $permit_cost_sqlwhere)) :
                    endif;

                    while ($fetch_available_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_locations_wise_vehicle_details)) :
                        $vendor_name = $fetch_available_vehicle_data['vendor_name'];
                        $vendor_margin = $fetch_available_vehicle_data['vendor_margin'];
                        $vendor_margin_gst_type = $fetch_available_vehicle_data['vendor_margin_gst_type'];
                        $vendor_margin_gst_percentage = $fetch_available_vehicle_data['vendor_margin_gst_percentage'];
                        $vendor_branch_gst_type = $fetch_available_vehicle_data['vendor_branch_gst_type'];
                        $vendor_branch_gst = $fetch_available_vehicle_data['vendor_branch_gst'];
                        $vendor_branch_name = $fetch_available_vehicle_data['vendor_branch_name'];
                        $vendor_vehicle_type_ID = $fetch_available_vehicle_data['vendor_vehicle_type_ID'];
                        $vehicle_type_id = $fetch_available_vehicle_data['vehicle_type_id'];
                        $driver_batta = $fetch_available_vehicle_data['driver_batta'];
                        $food_cost = $fetch_available_vehicle_data['food_cost'];
                        $accomodation_cost = $fetch_available_vehicle_data['accomodation_cost'];
                        $extra_cost = $fetch_available_vehicle_data['extra_cost'];
                        $driver_early_morning_charges = $fetch_available_vehicle_data['driver_early_morning_charges'];
                        $driver_evening_charges = $fetch_available_vehicle_data['driver_evening_charges'];
                        $early_morning_charges = $fetch_available_vehicle_data['early_morning_charges'];
                        $evening_charges = $fetch_available_vehicle_data['evening_charges'];
                        $vehicle_location_id = $fetch_available_vehicle_data['vehicle_location_id'];
                        $registration_number = $fetch_available_vehicle_data['registration_number'];
                        $state_code = substr($registration_number, 0, 2);
                        $vendor_id = $fetch_available_vehicle_data['vendor_id'];
                        $vehicle_id = $fetch_available_vehicle_data['vehicle_id'];
                        $vendor_branch_id = $fetch_available_vehicle_data['vendor_branch_id'];
                        $vehicle_fc_expiry_date = $fetch_available_vehicle_data['vehicle_fc_expiry_date'];
                        $insurance_end_date = $fetch_available_vehicle_data['insurance_end_date'];
                        $owner_city = $fetch_available_vehicle_data['owner_city'];
                        $extra_km_charge = $fetch_available_vehicle_data['extra_km_charge'];
                        $location_id = $fetch_available_vehicle_data['location_id'];
                        $source_location_lattitude = $fetch_available_vehicle_data['source_location_lattitude'];
                        $source_location_longitude = $fetch_available_vehicle_data['source_location_longitude'];
                        $destination_location_lattitude = $fetch_available_vehicle_data['destination_location_lattitude'];
                        $destination_location_longitude = $fetch_available_vehicle_data['destination_location_longitude'];
                        $via_route_location_lattitude = $fetch_available_vehicle_data['via_route_location_lattitude'];
                        $via_route_location_longitude = $fetch_available_vehicle_data['via_route_location_longitude'];
                        $distance_from_source = $fetch_available_vehicle_data['distance_from_source'];
                        $distance_from_destination = $fetch_available_vehicle_data['distance_from_destination'];
                        $distance_from_via_route = $fetch_available_vehicle_data['distance_from_via_route'];
                        $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                        $get_vehicle_type_count = getITINEARY_PLAN_VEHICLE_DETAILS($itinerary_plan_ID, $vehicle_type_id, 'get_vehicle_type_count');

                        for ($vh_count = 1; $vh_count <= $get_vehicle_type_count; $vh_count++):

                            $TOTAL_DRIVER_CHARGES = ($driver_batta +  $food_cost + $accomodation_cost + $extra_cost);

                            $get_title = getKMLIMIT($vendor_vehicle_type_ID, 'get_title_from_vehicle_type', $vendor_id);
                            $get_kms_limit = getKMLIMIT($vendor_vehicle_type_ID, 'get_kms_limit', $vendor_id);
                            $kms_limit_id = getKMLIMIT($vendor_vehicle_type_ID, 'get_kms_limit_id', $vendor_id);
                            $processed_vehicle_records = 0; // Add at top

                            if ($current_DATE > $vehicle_fc_expiry_date) :
                                /* $errors['vehicle_fc_expiry_date_reached'] = "Sorry, Don't have a Valid FC for [$vendor_name => $vendor_branch_name - $registration_number] !!!"; */
                                continue;
                            endif;

                            if ($current_DATE > $insurance_end_date) :
                                /* $errors['insurance_end_date_reached'] = "Sorry, Don't have a Valid Insurance for [$vendor_name => $vendor_branch_name - $registration_number] !!!"; */
                                continue;
                            endif;

                            if (empty($get_kms_limit)) :
                                /* $errors['vendor_vehicle_outstaion_kms_limit_not_found'] = "Sorry, Don't have a Outstaion KM Limit Details for [$vendor_name] !!!"; */
                                continue;
                            endif;

                            if (!empty($errors)) :
                                //error call
                                $response['success'] = false;
                                $response['errors'] = $errors;
                            else :
                                $vehicle_origin = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
                                $vehicle_origin_city = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_CITY');
                                $vehicle_origin_location_latitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_latitude');
                                $vehicle_origin_location_longtitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_longtitude');
                                $driver_charges = $driver_batta +  $food_cost + $accomodation_cost + $extra_cost;
                                $vehicle_permit_state_id = getSTATE_DETAILS($state_code, 'vehicle_permit_state_id');

                                $select_itineary_route_plan_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_km`, `next_visiting_location`, `direct_to_next_visiting_place`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' and `status` = '1' and `deleted` = '0' ORDER BY `itinerary_route_ID` ASC") or die("#1-UNABLE_TO_COLLECT_ROUTE_LIST:" . sqlERROR_LABEL());
                                $total_no_of_itineary_plan_route_details = sqlNUMOFROW_LABEL($select_itineary_route_plan_details);
                                if ($total_no_of_itineary_plan_route_details > 0) :
                                    $route_count = 0;

                                    /* echo "<table border='1'>";
                                echo "<tr style='background-color:yellow'>";
                                echo "<th>Day's</th>";
                                echo "<th>Location Name</th>";
                                echo "<th>Total Running KM / Time</th>";
                                echo "<th>Total Siteseeing KM / Time</th>";
                                echo "<th>Total KM/Time</th>";
                                echo "<th>Amount</th>";
                                echo "</tr>";

                                echo "<tr>";
                                echo "<td colspan='6'>VENDOR : <b>$vendor_name</b> | BRANCH : <b>$vendor_branch_name</b> | VEHICLE TYPE : <b>$get_vehicle_type_title</b> | Vehicle Origin : <b>$vehicle_origin <br> VEHICLE NO : $registration_number</b></td>";
                                echo "</tr>"; */

                                    $OVERALL_TOTAL_TIME = NULL;
                                    $OVERALL_TOTAL_KM = NULL;
                                    $OVERALL_RENDAL_CHARGES = NULL;
                                    $OVERALL_VEHICLE_TOLL_CHARGE = NULL;
                                    $OVERALL_VEHICLE_PARKING_CHARGE = NULL;
                                    $OVERALL_TOTAL_DRIVER_CHARGES = NULL;
                                    $OVERALL_PERMIT_CHARGES = NULL;
                                    $OVERALL_BEFORE_6AM_EXTRA_TIME = NULL;
                                    $OVERALL_AFTER_8PM_EXTRA_TIME = NULL;
                                    $OVERALL_DRIVER_MORINING_CHARGES = NULL;
                                    $OVERALL_VENDOR_VEHICLE_MORNING_CHARGES = NULL;
                                    $OVERALL_DRIVER_EVEINING_CHARGES = NULL;
                                    $OVERALL_VENDOR_VEHICLE_EVENING_CHARGES = NULL;
                                    $OVERALL_TOTAL_VEHICLE_AMOUNT = NULL;
                                    $OVERALL_TOTAL_HOURS_n_MINS = NULL;
                                    $OVERALL_OUTSTATION_KM = NULL;
                                    $OVERALL_LOCAL_KM = NULL;
                                    $OVERALL_LOCAL_EXTRA_KM = NULL;
                                    $OVERALL_LOCAL_EXTRA_KM_CHARGES = NULL;
                                    $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE = NULL;
                                    $TOTAL_ITINEARY_ALLOWED_KM = NULL;
                                    $get_extra_kms = NULL;
                                    $TOTAL_EXTRA_KM = NULL;
                                    $TOTAL_EXTRA_KM_CHARGE = NULL;
                                    $new_total_vehicle_cost = NULL;
                                    $new_total_vehicle_tax_amt = NULL;
                                    $new_total_margin_amount = NULL;
                                    $new_total_margin_service_tax_amt = NULL;
                                    $VEHICLE_GRAND_TOTAL_AMOUNT = NULL;

                                    while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_details)) :
                                        $route_count++;
                                        $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                                        $location_id = $fetch_itineary_route_data['location_id'];
                                        $location_name = $fetch_itineary_route_data['location_name'];
                                        $itinerary_route_date = $fetch_itineary_route_data['itinerary_route_date'];
                                        $no_of_km = $fetch_itineary_route_data['no_of_km'];
                                        $route_start_time = $fetch_itineary_route_data['route_start_time'];
                                        $route_end_time = $fetch_itineary_route_data['route_end_time'];
                                        $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                                        $direct_to_next_visiting_place = $fetch_itineary_route_data['direct_to_next_visiting_place'];
                                        $day = date('j', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                                        $year = date('Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                                        $month = date('F', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                                        $location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_latitude', $location_id);
                                        $location_longtitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_longtitude', $location_id);
                                        $next_visiting_location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_latitude', $location_id);
                                        $next_visiting_location_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_longitude', $location_id);

                                        $vehicle_cost_for_the_day = getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vendor_branch_id, $vendor_vehicle_type_ID, $kms_limit_id);

                                        /* $source_location_state = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_LOCATION_STATE');
                                    $destination_location_state = getSTOREDLOCATIONDETAILS($location_id, 'DESTINATION_LOCATION_STATE');
                                    $source_state_id = getVEHICLE_PERMIT_DETAILS($source_location_state, 'GET_PERMIT_STATE_ID');
                                    $destination_state_id = getVEHICLE_PERMIT_DETAILS($destination_location_state, 'GET_PERMIT_STATE_ID'); */

                                        $source_location_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');
                                        $destination_location_city = getSTOREDLOCATIONDETAILS($location_id, 'DESTINATION_CITY');

                                        $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                        #CALCULATE PERMIT CHARGES
                                        // Step 1: Vehicle Origin and Source
                                        $permit_source_location = $location_name;  // The first location (source)

                                        if ($get_via_route_IDs):
                                            // Step 2: VIA Route Location IDs (if any)
                                            $get_via_route_location_IDs = implode(',', $get_via_route_IDs); // VIA route IDs as a string

                                            // Step 3: Fetch VIA Route Location Names (if applicable)
                                            $permit_via_locations = getSTOREDLOCATION_VIAROUTE_DETAILS($location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');
                                            $via_locations_city = getSTOREDLOCATION_VIAROUTE_DETAILS($location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_CITY');
                                        else:
                                            $get_via_route_location_IDs = NULL;
                                            $via_locations_city = NULL;
                                            $permit_via_locations = NULL;
                                        endif;

                                        // Step 4: Destination Location
                                        $permit_destination_location = $next_visiting_location;  // Final destination

                                        if ($route_count == 1):
                                            // Step 5: Prepare the array - Merge Origin, Source, VIA, and Destination locations
                                            $locations = array_merge(
                                                [$vehicle_origin],
                                                [$permit_source_location],   // Vehicle Origin to Source
                                                !empty($permit_via_locations) ? $permit_via_locations : [], // VIA Locations (if any)
                                                [$permit_destination_location]  // Destination
                                            );
                                        else:
                                            // Prepare the array without the Vehicle Origin (for intermediate routes)
                                            $locations = array_merge(
                                                [$permit_source_location],   // Source (no Vehicle Origin)
                                                !empty($permit_via_locations) ? $permit_via_locations : [], // VIA Locations (if any)
                                                [$permit_destination_location]  // Destination
                                            );

                                            // Check if it's the last route
                                            if ($total_no_of_itineary_plan_route_details == $route_count):
                                                // Add Vehicle Origin at the end
                                                $locations = array_merge(
                                                    [$permit_source_location],   // Source
                                                    !empty($permit_via_locations) ? $permit_via_locations : [], // VIA Locations
                                                    [$permit_destination_location],  // Destination
                                                    [$vehicle_origin]  // Vehicle Origin at the end
                                                );
                                            endif;
                                        endif;

                                        /* echo "itinerary_route_date => $itinerary_route_date";
                                        print_r($locations);
                                        echo "<br>";
                                        echo "<br>"; */

                                        $location_states = [];
                                        foreach ($locations as $location) :
                                            $get_location_state_name_query = sqlQUERY_LABEL("SELECT `source_location_state` FROM `dvi_stored_locations` WHERE `source_location` = '$location' AND `status` = '1' AND `deleted` = '0' ORDER BY `location_ID` DESC LIMIT 1") or die("#STATELABEL-LABEL: getPERMIT_COST_DETAILS: " . sqlERROR_LABEL());
                                            $source_location_row = sqlFETCHARRAY_LABEL($get_location_state_name_query);
                                            if ($source_location_row) :
                                                $location_states[] = $source_location_row['source_location_state'];
                                            endif;
                                        endforeach;

                                        /* echo "<br>";
                                        echo "<br>";
                                        print_r($location_states);
                                        echo "<br>";
                                        echo "<br>"; */

                                        // Step 2: Retrieve the state IDs for each state name
                                        $state_ids = [];
                                        foreach ($location_states as $state_name) :
                                            $get_location_permit_state_data_query = sqlQUERY_LABEL("SELECT `permit_state_id` FROM `dvi_permit_state` WHERE `state_name` = '$state_name' AND `status` = '1' AND `deleted` = '0' ORDER BY `permit_state_id` ASC LIMIT 1") or die("#STATELABEL-LABEL: getPERMIT_COST_DETAILS: " . sqlERROR_LABEL());
                                            $state_row = sqlFETCHARRAY_LABEL($get_location_permit_state_data_query);
                                            if ($state_row) :
                                                $state_ids[] = $state_row['permit_state_id'];
                                            endif;
                                        endforeach;

                                        // Step 3: Loop through each state and calculate permit charges
                                        $seven_days_ago = date('Y-m-d', strtotime($itinerary_route_date . ' - 6 days'));
                                        $permit_charges = 0;

                                        foreach ($state_ids as $state_id) :
                                            /* echo "SELECT `route_permit_charge_ID` FROM `dvi_itinerary_plan_route_permit_charge` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_branch_id` = '$vendor_branch_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `source_state_id` = '$vehicle_permit_state_id' AND `destination_state_id` = '$state_id' AND `itinerary_route_date` >= '$seven_days_ago' AND `status` = '1' AND `deleted` = '0' ORDER BY `route_permit_charge_ID` ASC LIMIT 1";
                                            echo "<br>";
                                            echo "<br>"; */
                                            // Check if permit cost was calculated within the last 7 days for this state
                                            $check_permit_charge_already_calcualted_within_7_days = sqlQUERY_LABEL("SELECT `route_permit_charge_ID` FROM `dvi_itinerary_plan_route_permit_charge` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `vendor_id` = '$vendor_id' AND `vendor_branch_id` = '$vendor_branch_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `source_state_id` = '$vehicle_permit_state_id' AND `destination_state_id` = '$state_id' AND `itinerary_route_date` >= '$seven_days_ago' AND `status` = '1' AND `deleted` = '0' ORDER BY `route_permit_charge_ID` ASC LIMIT 1") or die("#STATELABEL-LABEL: getPERMIT_COST_DETAILS: " . sqlERROR_LABEL());
                                            $check_permit_charge_already_calcualted_within_7_days_count = sqlNUMOFROW_LABEL($check_permit_charge_already_calcualted_within_7_days);

                                            // If not calculated, calculate permit charge
                                            if ($check_permit_charge_already_calcualted_within_7_days_count == 0) :
                                                // Get permit cost between source state and destination state

                                                /* echo "SELECT `permit_cost` FROM `dvi_permit_cost` WHERE `source_state_id` = '$vehicle_permit_state_id' AND `destination_state_id` = '$state_id' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vendor_vehicle_type_ID' AND `status` = '1' AND `deleted` = '0' ORDER BY `permit_cost_id` ASC LIMIT 1";
                                                echo "<br>";
                                                echo "<br>"; */

                                                $get_permit_cost_for_the_destination_state = sqlQUERY_LABEL("SELECT `permit_cost` FROM `dvi_permit_cost` WHERE `source_state_id` = '$vehicle_permit_state_id' AND `destination_state_id` = '$state_id' AND `vendor_id` = '$vendor_id' AND `vehicle_type_id` = '$vendor_vehicle_type_ID' AND `status` = '1' AND `deleted` = '0' ORDER BY `permit_cost_id` ASC LIMIT 1") or die("#STATELABEL-LABEL: getPERMIT_COST_DETAILS: " . sqlERROR_LABEL());
                                                $row_permit_cost = sqlFETCHARRAY_LABEL($get_permit_cost_for_the_destination_state);

                                                if ($row_permit_cost) :
                                                    // Add the permit cost to the total
                                                    $permit_cost = $row_permit_cost['permit_cost'];

                                                    $permit_charge_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`itinerary_route_date`', '`vendor_id`', '`vendor_branch_id`', '`vendor_vehicle_type_id`', '`source_state_id`', '`destination_state_id`', '`permit_cost`', '`createdby`', '`status`');

                                                    $permit_charges_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date", "$vendor_id", "$vendor_branch_id", "$vendor_vehicle_type_ID", "$vehicle_permit_state_id", "$state_id", "$permit_cost", "$logged_user_id", "1");

                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_plan_route_permit_charge", $permit_charge_arrFields, $permit_charges_arrValues, '')) :
                                                    /* echo "itinerary_plan_ID => $itinerary_plan_ID | itinerary_route_ID => $itinerary_route_ID | VENDOR_ID => $vendor_id | BRANCH_ID => $vendor_branch_id | VEHICLE_TYPE => $vendor_vehicle_type_ID |SOURCE => $vehicle_permit_state_id | DESTINATION => $state_id | Permit_Cost => $permit_cost Inserted <br><br>"; */
                                                    else:
                                                    /* echo "itinerary_plan_ID => $itinerary_plan_ID | itinerary_route_ID => $itinerary_route_ID | VENDOR_ID => $vendor_id | BRANCH_ID => $vendor_branch_id | VEHICLE_TYPE => $vendor_vehicle_type_ID |SOURCE => $vehicle_permit_state_id | DESTINATION => $state_id ==> Permit Cose Not Inserted <br><br>"; */
                                                    endif;
                                                endif;
                                            endif;
                                        endforeach;

                                        /* echo "<br>";
                                        echo "<br>"; */

                                        $get_location_permit_state_data_query = sqlQUERY_LABEL("SELECT SUM(`permit_cost`) AS TOTAL_PERMIT_CHARGES FROM `dvi_itinerary_plan_route_permit_charge` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `vendor_id` = '$vendor_id' AND `vendor_branch_id` = '$vendor_branch_id' AND `vendor_vehicle_type_id` = '$vendor_vehicle_type_ID' AND `status` = '1' AND `deleted` = '0'") or die("#STATELABEL-LABEL: getPERMIT_COST_DETAILS: " . sqlERROR_LABEL());
                                        $state_row = sqlFETCHARRAY_LABEL($get_location_permit_state_data_query);
                                        if ($state_row) :
                                            $permit_charges = $state_row['TOTAL_PERMIT_CHARGES'];
                                        endif;

                                        // Initialize variables for morning and evening charges
                                        $morning_extra_time = 0;
                                        $evening_extra_time = 0;
                                        $DRIVER_MORINING_CHARGES = 0;
                                        $DRIVER_EVEINING_CHARGES = 0;
                                        $VENDOR_VEHICLE_MORNING_CHARGES = 0;
                                        $VENDOR_VEHICLE_EVENING_CHARGES = 0;
                                        $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE = 0;

                                        $TOTAL_PICKUP_KM = 0;
                                        $TOTAL_PICKUP_DURATION = "00:00:00";
                                        $TOTAL_DROP_KM = 0;
                                        $TOTAL_DROP_DURATION = "00:00:00";

                                        // Convert route start and end times to Unix timestamps
                                        $route_start_timestamp = strtotime($route_start_time);
                                        $route_end_timestamp = strtotime($route_end_time);

                                        // Calculate Unix timestamps for 6 AM and 8 PM
                                        $six_am_timestamp = strtotime('06:00:00');
                                        $eight_pm_timestamp = strtotime('20:00:00');

                                        // Check if route start time is before 6 AM and route end time is after 8 PM
                                        if ($route_start_timestamp < $six_am_timestamp && $route_end_timestamp > $eight_pm_timestamp) {
                                            // Calculate morning charges for the time before 6 AM
                                            $morning_difference_seconds = $six_am_timestamp - $route_start_timestamp;
                                            $morning_hours = floor($morning_difference_seconds / 3600); // 3600 seconds in an hour
                                            $morning_minutes = floor(($morning_difference_seconds % 3600) / 60); // remaining minutes
                                            $morning_extra_time = $morning_hours + ($morning_minutes / 60);

                                            // Calculate extra time charges for the time after 8 PM
                                            $extra_time_difference_seconds = $route_end_timestamp - $eight_pm_timestamp;
                                            $extra_time_hours = floor($extra_time_difference_seconds / 3600); // 3600 seconds in an hour
                                            $extra_time_minutes = floor(($extra_time_difference_seconds % 3600) / 60); // remaining minutes
                                            $evening_extra_time = $extra_time_hours + ($extra_time_minutes / 60);
                                        } elseif ($route_start_timestamp < $six_am_timestamp) {
                                            // Calculate morning charges if the route starts before 6 AM but ends before 8 PM
                                            $morning_difference_seconds = $six_am_timestamp - $route_start_timestamp;
                                            $morning_hours = floor($morning_difference_seconds / 3600); // 3600 seconds in an hour
                                            $morning_minutes = floor(($morning_difference_seconds % 3600) / 60); // remaining minutes
                                            $morning_extra_time = $morning_hours + ($morning_minutes / 60);
                                        } elseif ($route_end_timestamp > $eight_pm_timestamp) {
                                            // Calculate extra time charges if the route ends after 8 PM but starts after 6 AM
                                            $extra_time_difference_seconds = $route_end_timestamp - $eight_pm_timestamp;
                                            $extra_time_hours = floor($extra_time_difference_seconds / 3600); // 3600 seconds in an hour
                                            $extra_time_minutes = floor(($extra_time_difference_seconds % 3600) / 60); // remaining minutes
                                            $evening_extra_time = $extra_time_hours + ($extra_time_minutes / 60);
                                        }

                                        $DRIVER_MORINING_CHARGES = ($driver_early_morning_charges * $morning_extra_time);
                                        $DRIVER_EVEINING_CHARGES = ($driver_evening_charges * $evening_extra_time);
                                        $VENDOR_VEHICLE_MORNING_CHARGES = ($early_morning_charges * $morning_extra_time);
                                        $VENDOR_VEHICLE_EVENING_CHARGES = ($evening_charges * $evening_extra_time);

                                        $check_local_via_route_city = false;

                                        if ($via_locations_city):
                                            /* echo "#1 check_local_via_route_city => $check_local_via_route_city <br>"; */
                                            // Check if all elements in $via_locations_city match $vehicle_origin_city
                                            $all_via_match = !empty($via_locations_city) && array_reduce($via_locations_city, function ($carry, $city) use ($vehicle_origin_city) {
                                                /* echo "$city == $vehicle_origin_city<br>"; */
                                                return $carry && ($city == $vehicle_origin_city);
                                            }, true);

                                            if ($all_via_match):
                                                /* echo "#2 check_local_via_route_city => $check_local_via_route_city <br>"; */
                                                $check_local_via_route_city = true;
                                            endif;
                                        else:
                                            /* echo "#3 check_local_via_route_city => $check_local_via_route_city <br>"; */
                                            $check_local_via_route_city = true;
                                        endif;

                                        /* echo "$source_location_city == $destination_location_city && $source_location_city == $vehicle_origin_city && ($route_count == 1 || $route_count == $total_no_of_itineary_plan_route_details || ($previous_location_city == $source_location_city && $previous_destination_location_city == $destination_location_city)) && $check_local_via_route_city == true";
                                    echo "<br>"; */

                                        /* echo "$source_location_city == $destination_location_city && $source_location_city == $vehicle_origin_city && ($route_count == 1 || $route_count == $total_no_of_itineary_plan_route_details || ($previous_destination_location_city == $source_location_city)) && $check_local_via_route_city == true";
                                    echo "<br>"; */

                                        /* $previous_location_city == $source_location_city &&  */
                                        if ($source_location_city == $destination_location_city && $source_location_city == $vehicle_origin_city && ($route_count == 1 || $route_count == $total_no_of_itineary_plan_route_details || ($previous_destination_location_city == $source_location_city)) && $check_local_via_route_city == true) :
                                            //LOCAL TRIP
                                            $travel_type = 1;
                                            if ($route_count == 1) : //DAY 1 TRIP PLAN FOR LOCAL
                                                // if ($vehicle_origin_city != $source_location_city) :
                                                if ($vehicle_origin != $location_name) :

                                                    // Determine the travel location type
                                                    $travel_location_type = getTravelLocationType($vehicle_origin_city, $source_location_city);
                                                    $distance_from_vehicle_orign_to_pickup_point = calculateDistanceAndDuration($vehicle_origin_location_latitude, $vehicle_origin_location_longtitude, $location_latitude, $location_longtitude, $travel_location_type);

                                                    $pickup_distance = $distance_from_vehicle_orign_to_pickup_point['distance'];
                                                    $pickup_duration_time = $distance_from_vehicle_orign_to_pickup_point['duration'];

                                                    // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                    preg_match('/(\d+) hour/', $pickup_duration_time, $hoursMatch);
                                                    preg_match('/(\d+) mins/', $pickup_duration_time, $minutesMatch);

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

                                                        // Step 1: Source to Destination
                                                        $toll_charge_locations[] = [$toll_source_location, $toll_destination_location];

                                                        // Dynamically assign variables for each location pair and calculate toll charges
                                                        foreach ($toll_charge_locations as $index => $location_pair) :
                                                            $get_location_id = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($location_pair[0], $location_pair[1], 'get_location_id');

                                                            // LOCAL TRIP TOLL CHARGE CALCULATION FOR SOURCE & DESTINATION
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
                                            endif;

                                        /* echo "<tr>";
                                        echo "<td>$itinerary_route_date <br> Day $route_count (LOC) <br> $route_start_time - $route_end_time</td>";
                                        echo "<td>From : $location_name <br> To: $next_visiting_location</td>";
                                        echo "<td>$TOTAL_RUNNING_KM / $TOTAL_TRAVELLING_TIME</td>";
                                        echo "<td>$SIGHT_SEEING_TRAVELLING_KM / $SIGHT_SEEING_TRAVELLING_TIME </td>";
                                        echo "<td>$TOTAL_KM / $TOTAL_TIME</td>";
                                        echo "<td>RENTAL : $vehicle_cost_for_the_day <br> TOLL_CHARGE : $VEHICLE_TOLL_CHARGE <br> PARKING_CHARGE : $VEHICLE_PARKING_CHARGE <br> DRIVER_CHARGES : $TOTAL_DRIVER_CHARGES <br> PERMIT_CHARGES : $permit_charges <br> BEFORE_6AM_AFTER_8PM_CHARGES : $morning_extra_time [$VENDOR_VEHICLE_MORNING_CHARGES + $DRIVER_MORINING_CHARGES] | $evening_extra_time [$VENDOR_VEHICLE_EVENING_CHARGES + $DRIVER_EVEINING_CHARGES] </td>";
                                        echo "</tr>"; */

                                        else :
                                            //OUTSTATION TRIP
                                            $travel_type = 2;
                                            if ($route_count == 1) : //DAY 1 TRIP PLAN FOR OUTSTATION
                                                // if ($vehicle_origin_city != $source_location_city) :
                                                if ($vehicle_origin != $location_name) :

                                                    // Determine the travel location type
                                                    $travel_location_type = getTravelLocationType($vehicle_origin_city, $source_location_city);
                                                    $distance_from_vehicle_orign_to_pickup_point = calculateDistanceAndDuration($vehicle_origin_location_latitude, $vehicle_origin_location_longtitude, $location_latitude, $location_longtitude, $travel_location_type);

                                                    $pickup_distance = $distance_from_vehicle_orign_to_pickup_point['distance'];
                                                    $pickup_duration_time = $distance_from_vehicle_orign_to_pickup_point['duration'];

                                                    // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                    preg_match('/(\d+) hour/', $pickup_duration_time, $hoursMatch);
                                                    preg_match('/(\d+) mins/', $pickup_duration_time, $minutesMatch);

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

                                                if ($total_no_of_itineary_plan_route_details == $route_count) :

                                                    $travel_location_type = getTravelLocationType($destination_location_city, $vehicle_origin_city);

                                                    $distance_from_droping_point_to_vehicle_orign =  calculateDistanceAndDuration($next_visiting_location_latitude, $next_visiting_location_longitude, $vehicle_origin_location_latitude, $vehicle_origin_location_longtitude, $travel_location_type);

                                                    /* print_r($distance_from_droping_point_to_vehicle_orign); */

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
                                                    $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME = "00:00:00";
                                                endif;

                                                $TOTAL_KM = $TOTAL_KM + $TOTAL_DROP_KM;

                                                # OUTSTATION TRIP TOLL CHARGES FOR DAY 1
                                                // TOLL CHARGE CALCULATION WITH VEHICLE ORIGIN & SOURCE & VIA & DESTINATION
                                                if ($route_count == 1):
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

                                                            // OUTSTATION TRIP TOLL CHARGE CALCULATION FOR VEHICLE ORIGIN & SOURCE & VIA & DESTINATION
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

                                                            // OUTSTATION TRIP TOLL CHARGE CALCULATION FOR VEHICLE ORIGIN & SOURCE & DESTINATION
                                                            if ($get_location_id):
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += getVEHICLE_TOLL_CHARGES($vehicle_type_id, $get_location_id);
                                                            else:
                                                                $COLLECT_VEHICLE_TOLL_CHARGE_WITH_VIA_ROUTE += 0;
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                elseif ($total_no_of_itineary_plan_route_details == $route_count) :
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

                                                    $travel_location_type = getTravelLocationType($destination_location_city, $vehicle_origin_city);

                                                    $distance_from_droping_point_to_vehicle_orign =  calculateDistanceAndDuration($next_visiting_location_latitude, $next_visiting_location_longitude, $vehicle_origin_location_latitude, $vehicle_origin_location_longtitude, $travel_location_type);

                                                    /* print_r($distance_from_droping_point_to_vehicle_orign); */

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
                                                    $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME = "00:00:00";
                                                endif;

                                                $TOTAL_RUNNING_KM = $RUNNING_KM + $TOTAL_DROP_KM;

                                                $TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS = strtotime("1970-01-01 $TOTAL_RUNNING_TRAVEL_TIME UTC");
                                                $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS = strtotime("1970-01-01 $SIGHT_SEEING_TRAVELLING_TIME UTC");
                                                $RUNNING_TIME_IN_SECONDS = strtotime("1970-01-01 $formated_runningtime_duration UTC");
                                                $formated_return_pickup_duration_in_seconds = strtotime("1970-01-01 $formated_return_pickup_duration UTC");
                                                $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME = strtotime("1970-01-01 $TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME UTC");

                                                $total_travelling_times_in_Seconds = (($TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS + $RUNNING_TIME_IN_SECONDS + $formated_return_pickup_duration_in_seconds) - ($TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME));

                                                $TOTAL_TRAVELLING_TIME = gmdate('H:i:s', $total_travelling_times_in_Seconds);

                                                // Add the seconds
                                                $total_times_in_Seconds = (($TOTAL_RUNNING_TRAVEL_TIME_IN_SECONDS + $SIGHT_SEEING_TRAVELLING_TIME_IN_SECONDS + $RUNNING_TIME_IN_SECONDS + $formated_return_pickup_duration_in_seconds) - ($TOTAL_RUNNING_TRAVEL_LAST_DAY_BUFFER_TIME));

                                                // Convert total seconds back to time format
                                                $TOTAL_TIME = gmdate('H:i:s', $total_times_in_Seconds);

                                                $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_TRAVELLING_KM;

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
