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
