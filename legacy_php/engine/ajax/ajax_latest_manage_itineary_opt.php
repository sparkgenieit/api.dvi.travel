
    if ($_GET['type'] == 'itineary_basic_info_with_optimized_route') :
        
        $response = [];
        $errors = [];

        // Validate required fields
        if (empty($_POST['arrival_location'])) : $errors['arrival_location_required'] = true;
        endif;
        if (empty($_POST['departure_location'])) : $errors['departure_location_required'] = true;
        endif;
        if (empty($_POST['trip_start_date'])) : $errors['trip_start_date_required'] = true;
        endif;
        if (empty($_POST['trip_start_time'])) : $errors['trip_start_time_required'] = true;
        endif;
        if (empty($_POST['trip_end_date'])) : $errors['trip_end_date_required'] = true;
        endif;
        if (empty($_POST['trip_end_time'])) : $errors['trip_end_time_required'] = true;
        endif;
        if (empty($_POST['expecting_budget'])) : $errors['expecting_budget_required'] = true;
        endif;

        if ($logged_user_level == 1 && $logged_user_level == 3) :
            //ADMIN AND TRAVEL EXPERT
            if (empty($_POST['agent'])) :
                $errors['agent_required'] = true;
            endif;
        endif;

        if (!empty($errors)) : // If there are validation errors
            $response['success'] = false;
            $response['errors'] = $errors;
        else : // If validation is successful
            $response['success'] = true;

            // Process form data
            $site_seeing_restriction_km_limit = getGLOBALSETTING('site_seeing_restriction_km_limit');

            $itinerary_prefrence = trim($_POST['itinerary_prefrence']);
            $agent_id = trim($_POST['agent']);
            $staff_id = trim($_POST['hidden_staff_id']);
            $arrival_location = trim($_POST['arrival_location']);
            $departure_location = trim($_POST['departure_location']);
            $trip_start_date = ($_POST['trip_start_date']);
            $trip_end_date = ($_POST['trip_end_date']);

            $trip_start_time = ($_POST['trip_start_time']);
            $trip_end_time = ($_POST['trip_end_time']);

            $trip_start_date_and_time = $trip_start_date . ' ' . $trip_start_time;
            $trip_end_date_and_time = $trip_end_date . ' ' . $trip_end_time;

            $startdate_timestamp = strtotime(str_replace('/', '-', $trip_start_date_and_time)); // Replace '/' with '-' for compatibility
            $trip_start_date_and_time = date('Y-m-d H:i:s', $startdate_timestamp);

            $trip_start_time = date('H:i:s', $startdate_timestamp);

            $enddate_timestamp = strtotime(str_replace('/', '-', $trip_end_date_and_time)); // Replace '/' with '-' for compatibility
            $trip_end_date_and_time = date('Y-m-d H:i:s', $enddate_timestamp);

            $trip_end_time = date('H:i:s', $enddate_timestamp);

            $arrival_type = $_POST['arrival_type'];
            $departure_type = $_POST['departure_type'];
            $no_of_nights = $_POST['no_of_nights'];
            $no_of_days = $_POST['no_of_days'];
            $expecting_budget = $_POST['expecting_budget'];
            $itinerary_type = $_POST['itinerary_type'];
            $entry_ticket_required = $_POST['entry_ticket_required'];
            $hidden_itinerary_plan_ID = $_POST['hidden_itinerary_plan_ID'];

            $trip_start_date_DBFORMAT = dateformat_database($trip_start_date);
            $trip_end_date_DBFORMAT = dateformat_database($trip_end_date);

            if($hidden_itinerary_plan_ID):
                $check_itinerary_plan_details = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' AND `itinerary_plan_id` = '$hidden_itinerary_plan_ID' AND DATE(`trip_start_date_and_time`) = '$trip_start_date_DBFORMAT'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTEL_DETAILS_LIST:" . sqlERROR_LABEL());
                $total_itineary_num_rows_count = sqlNUMOFROW_LABEL($check_itinerary_plan_details);
                if($total_itineary_num_rows_count == 0):
                    $itineary_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' ";
                    if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_details", '', '', $itineary_sqlWhere)) :
                        if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_amenities", '', '', $itineary_sqlWhere)) :
                            if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $itineary_sqlWhere)) :
                                if (sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_eligible_list", '', '', $itineary_sqlWhere)) :
                                    if (sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_vehicle_details", '', '', $itineary_sqlWhere)) :
                                        $itineary_sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_details", '', '', $itineary_sqlWhere)) :
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_details", '', '', $itineary_sqlWhere)) :
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_guide_details", '', '', $itineary_sqlWhere)) :
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $itineary_sqlWhere)) :
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $itineary_sqlWhere)) :
                                                            if (sqlACTIONS("DELETE", "dvi_itinerary_via_route_details", '', '', $itineary_sqlWhere)) :
                                                            $itineary_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' ";
                                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $itineary_sqlWhere)) :
                                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $itineary_sqlWhere)) :
                                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_guide_slot_cost_details", '', '', $itineary_sqlWhere)) :
                                                                        endif;
                                                                    endif;
                                                                endif;
                                                            endif;
                                                        endif;
                                                    endif;
                                                endif;
                                            endif;
                                        endif;
                                    endif;
                                endif;
                            endif;
                        endif;
                    endif;
                endif;
            endif;

            if ($itinerary_prefrence == 2) :
                $total_adult = $_POST['vehicle_total_adult'];
                $total_children = $_POST['vehicle_total_children'];
                $total_infants = $_POST['vehicle_total_infant'];
                
                $select_itineary_hotel_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$hidden_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTEL_DETAILS_LIST:" . sqlERROR_LABEL());
                $total_itineary_hotel_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_hotel_details);
                if($total_itineary_hotel_num_rows_count > 0):
                    $itineary_hotel_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' ";
                    if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_details", '', '', $itineary_hotel_sqlWhere)) :
                        if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_amenities", '', '', $itineary_hotel_sqlWhere)) :
                            if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $itineary_hotel_sqlWhere)) :
                            endif;
                        endif;
                    endif;
                endif;
            elseif($itinerary_prefrence == 1):
                if (isset($_POST['itinerary_adult']) && isset($_POST['itinerary_children']) && isset($_POST['itinerary_infants'])) :
                    $total_adult = isset($_POST['itinerary_adult']) ? array_sum($_POST['itinerary_adult']) : 0;
                    $total_children = isset($_POST['itinerary_children']) ? array_sum($_POST['itinerary_children']) : 0;
                    $total_infants = isset($_POST['itinerary_infants']) ? array_sum($_POST['itinerary_infants']) : 0;
                else :
                    $total_adult = 0;
                    $total_children = 0;
                    $total_infants = 0;
                endif;
                
                $select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT `vehicle_details_ID` FROM `dvi_itinerary_plan_vehicle_details` WHERE `deleted` = '0' and `itinerary_plan_id` = '$hidden_itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
                $total_itineary_vehicle_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_vehicle_details);
                if($total_itineary_vehicle_num_rows_count > 0):
                    $itineary_vehicle_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' ";
                    if (sqlACTIONS("DELETE", "dvi_itinerary_plan_vehicle_details", '', '', $itineary_vehicle_sqlWhere)) :
                        if (sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_eligible_list", '', '', $itineary_vehicle_sqlWhere)) :
                            if (sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_vehicle_details", '', '', $itineary_vehicle_sqlWhere)) :
                            endif;
                        endif;
                    endif;
                endif;
            else :
                if (isset($_POST['itinerary_adult']) && isset($_POST['itinerary_children']) && isset($_POST['itinerary_infants'])) :
                    $total_adult = isset($_POST['itinerary_adult']) ? array_sum($_POST['itinerary_adult']) : 0;
                    $total_children = isset($_POST['itinerary_children']) ? array_sum($_POST['itinerary_children']) : 0;
                    $total_infants = isset($_POST['itinerary_infants']) ? array_sum($_POST['itinerary_infants']) : 0;
                else :
                    $total_adult = 0;
                    $total_children = 0;
                    $total_infants = 0;
                endif;
            endif;

            $no_of_routes = 1;
            $guide_for_itinerary = $_POST['guide_for_itinerary'];
            $child_bed_type = $_POST['child_bed_type'];

            if($hidden_itinerary_plan_ID):
                if($guide_for_itinerary):
                    $select_itineary_guide_details = sqlQUERY_LABEL("SELECT `route_guide_ID` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' AND `guide_type` = '1'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
                    $total_itineary_guide_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_guide_details);
                    if($total_itineary_guide_num_rows_count == 0):
                        $delete_already_added_route_guide_cost_sqlwhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' ";
                        $delete_already_added_itinerary_route_guide_cost_details = sqlACTIONS("DELETE", "dvi_itinerary_route_guide_slot_cost_details", '', '', $delete_already_added_route_guide_cost_sqlwhere);
                        $delete_already_added_route_guide_sqlwhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
                        $delete_already_added_itinerary_route_guide_details = sqlACTIONS("DELETE", "dvi_itinerary_route_guide_details", '', '', $delete_already_added_route_guide_sqlwhere);
                    endif;
                else:
                    $select_itineary_guide_details = sqlQUERY_LABEL("SELECT `route_guide_ID` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' AND `guide_type` = '2'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_VEHICLE_DETAILS_LIST:" . sqlERROR_LABEL());
                    $total_itineary_guide_num_rows_count = sqlNUMOFROW_LABEL($select_itineary_guide_details);
                    if($total_itineary_guide_num_rows_count == 0):
                        $delete_already_added_route_guide_cost_sqlwhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' ";
                        $delete_already_added_itinerary_route_guide_cost_details = sqlACTIONS("DELETE", "dvi_itinerary_route_guide_slot_cost_details", '', '', $delete_already_added_route_guide_cost_sqlwhere);
                        $delete_already_added_route_guide_sqlwhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";
                        $delete_already_added_itinerary_route_guide_details = sqlACTIONS("DELETE", "dvi_itinerary_route_guide_details", '', '', $delete_already_added_route_guide_sqlwhere);
                    endif;
                endif;
            endif;

            // Initialize variables to count occurrences
            $total_with_bed_count = 0;
            $total_without_bed_count = 0;

            if (isset($child_bed_type)) :
                foreach ($child_bed_type as $subArray) :
                    foreach ($subArray as $value) :
                        if ($value === 'With Bed') : $total_with_bed_count++;
                        elseif ($value === 'Without Bed') : $total_without_bed_count++;
                        endif;
                    endforeach;
                endforeach;
            endif;

            $total_extra_bed = 0;
            $room_count = isset($_POST['total_room_count']) ? count($_POST['total_room_count']) : 0;

            if (isset($_POST['total_room_count'])) :
                $food_type = $_POST['food_type'];

                for ($i = 0; $i < $room_count; $i++) :
                    $result = calculateBedAllocation($_POST['itinerary_adult'][$i]);
                    if ($result['remainingCount'] > 0) : $total_extra_bed++;
                    endif;
                endfor;
            else :
                $room_count = 0;
                $food_type = 0;
            endif;

            $nationality = $_POST['nationality'];
            $meal_plan_breakfast = isset($_POST['meal_plan_breakfast']) && $_POST['meal_plan_breakfast'] == 'on' ? 1 : 0;
            $meal_plan_lunch = isset($_POST['meal_plan_lunch']) && $_POST['meal_plan_lunch'] == 'on' ? 1 : 0;
            $meal_plan_dinner = isset($_POST['meal_plan_dinner']) && $_POST['meal_plan_dinner'] == 'on' ? 1 : 0;
            $special_instructions = $_POST['special_instructions'];
            $special_instructions=nl2br(htmlentities($special_instructions, ENT_QUOTES, 'UTF-8'));
            $pick_up_date_and_time = $_POST['pick_up_date_and_time'] ? date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_POST['pick_up_date_and_time']))) : NULL;
            $vehicle_type = $_POST['vehicle_type'];
            $vehicle_count = $_POST['vehicle_count'];
            $location_id = getSTOREDLOCATION_ID_FROM_SOURCE_AND_DESTINATION($arrival_location, $departure_location);

            $itinerary_quote_ID = getQUOTATIONREFNO();

            if (empty($hidden_itinerary_plan_ID)) :
                $add_quote_arrField = ['`itinerary_quote_ID`'];
                $add_quote_arrValue = ["$itinerary_quote_ID"];
            endif;

            $arrFields = [
                '`itinerary_preference`', '`location_id`', '`arrival_location`', '`departure_location`',
                '`trip_start_date_and_time`', '`trip_end_date_and_time`', '`arrival_type`', '`departure_type`',
                '`no_of_nights`', '`no_of_days`', '`expecting_budget`', '`itinerary_type`', '`entry_ticket_required`',
                '`total_adult`', '`total_children`', '`total_infants`', '`no_of_routes`', '`guide_for_itinerary`',
                '`preferred_room_count`', '`total_extra_bed`', '`total_child_with_bed`', '`total_child_without_bed`',
                '`food_type`', '`nationality`', '`meal_plan_breakfast`', '`meal_plan_lunch`', '`meal_plan_dinner`',
                '`special_instructions`', '`pick_up_date_and_time`', '`createdby`', '`status`'
            ];

            $arrValues = [
                "$itinerary_prefrence", "$location_id", "$arrival_location", "$departure_location",
                "$trip_start_date_and_time", "$trip_end_date_and_time", "$arrival_type", "$departure_type",
                "$no_of_nights", "$no_of_days", "$expecting_budget", "$itinerary_type", "$entry_ticket_required",
                "$total_adult", "$total_children", "$total_infants", "1", "$guide_for_itinerary",
                "$room_count", "$total_extra_bed", "$total_with_bed_count", "$total_without_bed_count",
                "$food_type", "$nationality", "$meal_plan_breakfast", "$meal_plan_lunch", "$meal_plan_dinner",
                "$special_instructions", "$pick_up_date_and_time", "$logged_user_id", "1"
            ];
            
            if($logged_user_level == 4):
                $add_agent_info_arrField = ['`agent_id`','`staff_id`'];
                $add_agent_info_arrValue = ["$logged_agent_id","$logged_staff_id"];
                $arrFields = array_merge($arrFields, $add_agent_info_arrField);
                $arrValues = array_merge($arrValues, $add_agent_info_arrValue);
            else:
                $add_agent_info_arrField = ['`agent_id`','`staff_id`'];
                $add_agent_info_arrValue = ["$agent_id","$staff_id"];
                $arrFields = array_merge($arrFields, $add_agent_info_arrField);
                $arrValues = array_merge($arrValues, $add_agent_info_arrValue);
            endif;

            if (empty($hidden_itinerary_plan_ID)) :
                $arrFields = array_merge($arrFields, $add_quote_arrField);
                $arrValues = array_merge($arrValues, $add_quote_arrValue);
            endif;

            $itinerary_travel_type_buffer_time = "00:00:00";

            if ($hidden_itinerary_plan_ID != '' && $hidden_itinerary_plan_ID != 0) :

                $sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' ";

                //UPDATE ITINEARY PLAN DETAILS
                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_details", $arrFields, $arrValues, $sqlWhere)) :

                    $itinerary_plan_ID = $hidden_itinerary_plan_ID;

                    //IF ITINEARY PREFERENCES [VEHICLE | BOTH HOTEL & VEHICLE]
                    if (isset($vehicle_type) && isset($vehicle_count) && in_array($itinerary_prefrence, array(2, 3))) :

                        foreach ($vehicle_type as $key => $val) :
                            $selected_VEHICLE_TYPE = $_POST['vehicle_type'][$key];
                            $selected_VEHICLE_COUNT = $_POST['vehicle_count'][$key];
                            $selected_vehicle_details_ID = $_POST['hidden_vehicle_details_ID'][$key];

                            $vehicle_arrFields = array('`itinerary_plan_ID`', '`vehicle_type_id`', '`vehicle_count`', '`createdby`', '`status`');
                            $vehicle_arrValues = array("$itinerary_plan_ID", "$selected_VEHICLE_TYPE", "$selected_VEHICLE_COUNT", "$logged_user_id", "1");

                            if ($selected_vehicle_details_ID) :
                                $vehicle_sqlwhere = " `vehicle_details_ID` = '$selected_vehicle_details_ID' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_vehicle_details", $vehicle_arrFields, $vehicle_arrValues, $vehicle_sqlwhere)) :
                                endif;
                            else :
                                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vehicle_details", $vehicle_arrFields, $vehicle_arrValues, '')) :
                                endif;
                            endif;
                        endforeach;
                    endif;

                    $itinerary_adult = $_POST['itinerary_adult'];
                    $itinerary_children = $_POST['itinerary_children'];
                    $itinerary_infants = $_POST['itinerary_infants'];
                    $children_age = $_POST['children_age'];
                    $child_bed_type = $_POST['child_bed_type'];

                    if (isset($_POST['total_room_count'])) :
                        for ($i = 0; $i < count($_POST['total_room_count']); $i++) :
                            $room_id = $i + 1; // Assuming room IDs start from 1

                            //INSERT ADULT TRAVELLERS
                            for ($j = 0; $j < $itinerary_adult[$i]; $j++) :
                                $traveller_type = 1; // Adult

                                $adult_travelers_arrFields = array('`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`createdby`', '`status`');
                                $adult_travelers_arrValues = array("$itinerary_plan_ID", "$traveller_type", "$room_id", "$logged_user_id", "1");

                                $select_itinerary_adult_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                $total_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_adult_traveller_details_query);

                                $diff_adult_count = $itinerary_adult[$i] - $total_num_rows_count;

                                if ($diff_adult_count < 0) :
                                    $absoluteValue = abs($diff_adult_count);
                                    // Query to get total adult count for the room from the database
                                    $select_total_adult_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ORDER BY `traveller_details_ID` ASC LIMIT $absoluteValue") or die(sqlERROR_LABEL());
                                    while ($row = sqlFETCHARRAY_LABEL($select_total_adult_query)) :
                                        $traveller_details_ID = $row['traveller_details_ID'];
                                        $delete_adult_travelers_sqlwhere = " `traveller_details_ID` = '$traveller_details_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $delete_adult_travelers_sqlwhere)) :
                                        endif;
                                    endwhile;
                                endif;

                                //UPDATE ADULT TRAVELLER DETAILS
                                $adult_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_traveller_details", $adult_travelers_arrFields, $adult_travelers_arrValues, $adult_travelers_sqlwhere)) :
                                endif;

                                if ($diff_adult_count > 0) :
                                    //INSERT ADULT TRAVELLER DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $adult_travelers_arrFields, $adult_travelers_arrValues, '')) :
                                    endif;
                                endif;
                            endfor;

                            if($total_children == 0):
                                $children_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '2' ";
                                if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $children_travelers_sqlwhere)) :
                                endif;
                            endif;

                            //INSERT CHILDREN TRAVELLERS
                            for ($j = 0; $j < $itinerary_children[$i]; $j++) :
                                $traveller_type = 2; // Children
                                $traveller_age = $children_age[$room_id][$j];
                                $child_bed_TYPE = $child_bed_type[$room_id][$j];
                                $hidden_traveller_details_ID = $_POST['hidden_traveller_details_ID'][$room_id][$j];

                                if (($child_bed_TYPE) == 'Without Bed') :
                                    $child_bed_type_id = 1;
                                elseif (($child_bed_TYPE) == 'With Bed') :
                                    $child_bed_type_id = 2;
                                endif;

                                $children_travelers_arrFields = array('`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`traveller_age`', '`child_bed_type`', '`createdby`', '`status`');
                                $children_travelers_arrValues = array("$itinerary_plan_ID", "$traveller_type", "$room_id", "$traveller_age", "$child_bed_type_id", "$logged_user_id", "1");

                                $select_itinerary_child_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                $total_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_child_traveller_details_query);

                                $diff_children_count = $itinerary_children[$i] - $total_num_rows_count;

                                if ($diff_children_count < 0) :
                                    $absoluteValue = abs($diff_children_count);
                                    // Query to get total adult count for the room from the database
                                    $select_total_child_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' AND `traveller_details_ID` != '$hidden_traveller_details_ID' ORDER BY `traveller_details_ID` ASC LIMIT $absoluteValue") or die(sqlERROR_LABEL());
                                    while ($row = sqlFETCHARRAY_LABEL($select_total_child_query)) :
                                        $traveller_details_ID = $row['traveller_details_ID'];
                                        $delete_child_travelers_sqlwhere = " `traveller_details_ID` = '$traveller_details_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $delete_child_travelers_sqlwhere)) :
                                        endif;
                                    endwhile;
                                endif;

                                //UPDATE CHILDREN TRAVELLER DETAILS
                                $children_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' and `traveller_details_ID` = '$hidden_traveller_details_ID' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_traveller_details", $children_travelers_arrFields, $children_travelers_arrValues, $children_travelers_sqlwhere)) :
                                endif;

                                if ($diff_children_count > 0) :
                                    //INSERT CHILDREN TRAVELLER DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $children_travelers_arrFields, $children_travelers_arrValues, '')) :
                                    endif;
                                endif;

                            endfor;

                            if($itinerary_infants == 0):
                                $delete_infant_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '3' ";
                                if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $children_travelers_sqlwhere)) :
                                endif;
                            endif;

                            //INSERT INFANT TRAVELLERS
                            for ($j = 0; $j < $itinerary_infants[$i]; $j++) :
                                $traveller_type = 3; // Infant

                                $infant_travelers_arrFields = array('`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`createdby`', '`status`');
                                $infant_travelers_arrValues = array("$itinerary_plan_ID", "$traveller_type", "$room_id", "$logged_user_id", "1");

                                $select_itinerary_infant_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                $total_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_infant_traveller_details_query);

                                $diff_infant_count = $itinerary_infants[$i] - $total_num_rows_count;

                                if ($diff_infant_count < 0) :
                                    $absoluteValue = abs($diff_infant_count);
                                    // Query to get total adult count for the room from the database
                                    $select_total_infant_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ORDER BY `traveller_details_ID` ASC LIMIT $absoluteValue") or die(sqlERROR_LABEL());
                                    while ($row = sqlFETCHARRAY_LABEL($select_total_infant_query)) :
                                        $traveller_details_ID = $row['traveller_details_ID'];
                                        $delete_infant_travelers_sqlwhere = " `traveller_details_ID` = '$traveller_details_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $delete_infant_travelers_sqlwhere)) :
                                        endif;
                                    endwhile;
                                endif;

                                //UPDATE INFANT TRAVELLER DETAILS
                                $infant_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_traveller_details", $infant_travelers_arrFields, $infant_travelers_arrValues, $infant_travelers_sqlwhere)) :
                                endif;

                                if ($diff_infant_count > 0) :
                                    //INSERT INFANT TRAVELLER DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $infant_travelers_arrFields, $infant_travelers_arrValues, '')) :
                                    endif;
                                endif;
                            endfor;

                        endfor;
                    endif;

                    $source_location = $_POST['source_location']; 
                    $next_visiting_location = $_POST['next_visiting_location']; 

                    $total_no_of_routes = count($source_location);

                    // Get the distance limit from the global setting
                    $daily_limit = getGLOBALSETTING('itinerary_distance_limit'); // 200 KM

                    $start_location = $source_location[0]; // Kochi
                    $end_location = end($next_visiting_location); // Trivandrum

                    if($total_no_of_routes <= 10):

                        // Convert arrays to comma-separated strings
                        $source_location_str = implode("','", $source_location);
                        $next_visiting_location_str = implode("','", $next_visiting_location);

                        // Fetch location details
                        $collect_location_details = sqlQUERY_LABEL("SELECT `source_location`, `destination_location`, `distance` FROM `dvi_stored_locations` WHERE `source_location` IN ('$source_location_str') AND `destination_location` IN ('$next_visiting_location_str') AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                        $total_no_of_location = sqlNUMOFROW_LABEL($collect_location_details);

                        $distances = [];

                        // Process results
                        while ($row = sqlFETCHARRAY_LABEL($collect_location_details)) {
                            $source = $row['source_location'];
                            $destination = $row['destination_location'];
                            $distance = $row['distance'];
                            
                            // Populate distances array
                            if (!isset($distances[$source])) {
                                $distances[$source] = [];
                            }
                            $distances[$source][$destination] = $distance;
                        }

                        // Generate all possible routes that include all locations except the last one
                        function generateRoutes($currentRoute, $remainingLocations, $distances, $fixedEndLocation, &$allRoutes, $currentDistance = 0) {
                            if (empty($remainingLocations)) {
                                // Complete the route by adding the fixed end location
                                $lastLocation = end($currentRoute);

                                if (isset($distances[$lastLocation][$fixedEndLocation])) {
                                    $currentDistance += $distances[$lastLocation][$fixedEndLocation];
                                    $currentRoute[] = $fixedEndLocation;
                                }

                                // Store the route and its total distance
                                $allRoutes[] = [
                                    'route' => $currentRoute,
                                    'distance' => $currentDistance
                                ];

                                return;
                            }

                            foreach ($remainingLocations as $key => $location) {
                                $lastLocation = end($currentRoute);
                                $newCurrentDistance = $currentDistance + ($distances[$lastLocation][$location] ?? PHP_INT_MAX);

                                // Recursively generate routes by removing the current location from the remaining set
                                $newRoute = $currentRoute;
                                $newRoute[] = $location;

                                $newRemainingLocations = $remainingLocations;
                                unset($newRemainingLocations[$key]);

                                generateRoutes($newRoute, $newRemainingLocations, $distances, $fixedEndLocation, $allRoutes, $newCurrentDistance);
                            }
                        }

                        // Initialize variables to store all routes
                        $allRoutes = [];

                        // Example usage
                        $start_location = $source_location[0];
                        $end_location = array_pop($next_visiting_location); // Remove and store the last destination
                        $initialRoute = [$start_location];

                        generateRoutes($initialRoute, $next_visiting_location, $distances, $end_location, $allRoutes);

                        // Output all generated routes and their distances
                        /* echo "<pre>";
                        echo "All Generated Routes:\n";
                        foreach ($allRoutes as $routeInfo) {
                            echo "Route: " . implode(' -> ', $routeInfo['route']) . "\n";
                            echo "Total Distance: " . $routeInfo['distance'] . " KM\n";
                            echo "-------------------------\n";
                        }
                        echo "</pre>"; */

                        // Find the best route (shortest distance)
                        $minDistance = PHP_INT_MAX;
                        $bestRoute = [];

                        foreach ($allRoutes as $routeInfo) {
                            if ($routeInfo['distance'] < $minDistance) {
                                $minDistance = $routeInfo['distance'];
                                $bestRoute = $routeInfo['route'];
                            }
                        }

                        /* // Output the best route and the minimum distance
                        echo "<pre>";
                        echo "Best Route: " . implode(' -> ', $bestRoute) . "\n";
                        echo "Minimum Total Distance: " . $minDistance . " KM\n";
                        echo "</pre>"; */

                        if (!empty($bestRoute)) :
                            /* echo "<br>Optimized Route: " . implode(' -> ', $bestRoute) . "\n";
                            echo "Total Distance: " . $minDistance . " KM\n"; */
                            for($r=0;$r<count($bestRoute);$r++):
                                if($r!=(count($bestRoute)-1)):
                                    $new_source_location[] = $bestRoute[$r];
                                endif;
                                if($r!=0):
                                    $new_next_visiting_location[] = $bestRoute[$r];
                                endif;
                            endfor;
                        else :
                            /* echo "No optimized route found.\n"; */
                        endif;
                    else:
                        # MORE THEN 10 DAYS PALN

                        // Function to fetch location details from the database
                        function fetchLocationDetails($source_location, $next_visiting_location)
                        {
                            // Combine source and destination locations and remove duplicates
                            $all_locations = array_merge($source_location, $next_visiting_location);
                            $all_locations_str = implode("','", array_unique($all_locations));

                            $collect_location_details = sqlQUERY_LABEL("SELECT `source_location`, `destination_location`, `distance` FROM `dvi_stored_locations` WHERE `source_location` IN ('$all_locations_str') AND destination_location IN ('$all_locations_str') AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());

                            $distances = [];
                            while ($row = sqlFETCHARRAY_LABEL($collect_location_details)) {
                                $source = $row['source_location'];
                                $destination = $row['destination_location'];
                                $distance = $row['distance'];

                                if (!isset($distances[$source])) {
                                    $distances[$source] = [];
                                }
                                $distances[$source][$destination] = $distance;
                            }

                            return $distances;
                        }

                        // Function to validate if a route meets the daily limit condition
                        function validateRoute($route, $distances, $daily_limit)
                        {
                            $totalDistance = 0;
                            for ($i = 0; $i < count($route) - 1; $i++) {
                                $currentLocation = $route[$i];
                                $nextLocation = $route[$i + 1];
                                if (!isset($distances[$currentLocation][$nextLocation])) {
                                    return false;
                                }
                                $totalDistance += $distances[$currentLocation][$nextLocation];
                                if ($totalDistance > $daily_limit) {
                                    return false;
                                }
                            }
                            return true;
                        }

                        // Nearest Neighbor Heuristic
                        function nearestNeighbor($start, $locations, $distances)
                        {
                            $currentLocation = $start;
                            $route = [$currentLocation];
                            $remainingLocations = array_count_values($locations); // Count occurrences to handle duplicates

                            while (!empty($remainingLocations)) {
                                $nearestDistance = PHP_INT_MAX;
                                $nearestLocation = null;

                                foreach ($remainingLocations as $location => $count) {
                                    if ($count > 0 && isset($distances[$currentLocation][$location]) && $distances[$currentLocation][$location] < $nearestDistance) {
                                        $nearestDistance = $distances[$currentLocation][$location];
                                        $nearestLocation = $location;
                                    }
                                }

                                if ($nearestLocation !== null) {
                                    $route[] = $nearestLocation;
                                    $remainingLocations[$nearestLocation]--;
                                    if ($remainingLocations[$nearestLocation] <= 0) {
                                        unset($remainingLocations[$nearestLocation]);
                                    }
                                    $currentLocation = $nearestLocation;
                                } else {
                                    // Handle the case where no valid next location exists
                                    break;
                                }
                            }

                            return $route;
                        }

                        // Simulated Annealing
                        function simulatedAnnealing($initialRoute, $distances, $daily_limit, $initialTemp = 1000, $coolingRate = 0.003)
                        {
                            $currentRoute = $initialRoute;
                            $currentCost = calculateRouteCost($currentRoute, $distances);

                            $bestRoute = $currentRoute;
                            $minCost = $currentCost;

                            $temperature = $initialTemp;

                            while ($temperature > 1) {
                                // Generate a neighbor by swapping two locations (but do not swap the first and last location)
                                $newRoute = $currentRoute;
                                $i = rand(1, count($newRoute) - 2);
                                $j = rand(1, count($newRoute) - 2);
                                if ($i != 0 && $i != count($newRoute) - 1 && $j != 0 && $j != count($newRoute) - 1) {
                                    list($newRoute[$i], $newRoute[$j]) = array($newRoute[$j], $newRoute[$i]);
                                }

                                $newCost = calculateRouteCost($newRoute, $distances);

                                // Acceptance probability
                                if ($newCost < $currentCost || exp(($currentCost - $newCost) / $temperature) > rand(0, 100) / 100) {
                                    $currentRoute = $newRoute;
                                    $currentCost = $newCost;
                                }

                                if ($newCost < $minCost) {
                                    $minCost = $newCost;
                                    $bestRoute = $newRoute;
                                }

                                // Cool down
                                $temperature *= 1 - $coolingRate;
                            }

                            return [$bestRoute, $minCost];
                        }

                        // Calculate the total cost of a route
                        function calculateRouteCost($route, $distances)
                        {
                            $totalDistance = 0;
                            for ($i = 0; $i < count($route) - 1; $i++) {
                                $currentLocation = $route[$i];
                                $nextLocation = $route[$i + 1];
                                if (!isset($distances[$currentLocation][$nextLocation])) {
                                    return PHP_INT_MAX;
                                }
                                $totalDistance += $distances[$currentLocation][$nextLocation];
                            }
                            return $totalDistance;
                        }
                        // Fetch distances from the database
                        $distances = fetchLocationDetails($source_location, $next_visiting_location);

                        // Get the distance limit from the global setting
                        $daily_limit = getGLOBALSETTING('itinerary_distance_limit');

                        // Prepare the locations for the initial route
                        $remaining_locations = array_slice($next_visiting_location, 0);

                        // Ensure the end location is not included in the remaining locations
                        if (($key = array_search($start_location, $remaining_locations)) !== false) :
                            unset($remaining_locations[$key]);
                        endif;

                        // Ensure the end location is not included in the remaining locations
                        if (($key = array_search($end_location, $remaining_locations)) !== false) :
                            unset($remaining_locations[$key]);
                        endif;

                        // Nearest Neighbor initial solution
                        $initialRoute = nearestNeighbor($start_location, $remaining_locations, $distances);

                        // Ensure the end location is not included in the remaining locations
                        if (($key = array_search($start_location, $initialRoute)) !== false) :
                            unset($initialRoute[$key]);
                        endif;

                        // Ensure the start and end locations are fixed
                        $initialRoute = array_merge([$start_location], $initialRoute, [$end_location]);

                        // Apply Simulated Annealing to improve the solution
                        list($bestRoute, $minCost) = simulatedAnnealing($initialRoute, $distances, $daily_limit);

                        // Output the best route and minimum cost
                        /* echo "<pre>";
                        echo "Best Route: " . implode(' -> ', $bestRoute) . "\n";
                        echo "Minimum Total Distance: " . $minCost . " KM\n";
                        echo "</pre>"; */

                        if (!empty($bestRoute)) :
                            /* echo "<br>Optimized Route: " . implode(' -> ', $bestRoute) . "\n";
                            echo "Total Distance: " . $minDistance . " KM\n"; */
                            for($r=0;$r<count($bestRoute);$r++):
                                    if($r!=(count($bestRoute)-1)):
                                    $new_source_location[] = $bestRoute[$r];
                                endif;
                                if($r!=0):
                                    $new_next_visiting_location[] = $bestRoute[$r];
                                endif;
                            endfor;
                        else :
                            /* echo "No optimized route found.\n"; */
                        endif;
                    endif;

                    $total_source_location_count = count($new_source_location);
                    $total_next_visiting_location_count = count($new_next_visiting_location);

                    $no_of_days = 0;

                    $array_of_modified_route_DATE = implode("','", array_map(function($date) {
                        // Check if the date is already in the Y-m-d format
                        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                            return $date; // Date is already correct
                        }
                        // If date is in d/m/Y format, reformat to Y-m-d
                        $parts = explode('/', $date);
                        return "{$parts[2]}-{$parts[1]}-{$parts[0]}"; // Reformat to Y-m-d
                    }, $_POST['hidden_itinerary_route_date']));

                    $array_of_modified_route_ID = getITINEARYROUTE_DETAILS($hidden_itinerary_plan_ID,$array_of_modified_route_DATE,'get_all_itinerary_route_id_from_route_date','');

                    // Split the string into an array
                    $array_of_modified_route_hotel_ids = explode("','", $array_of_modified_route_ID);
                    // Remove the last element
                    array_pop($array_of_modified_route_hotel_ids);
                    // Convert the array back to a string
                    $array_of_modified_route_hotel_ID = implode("','", $array_of_modified_route_hotel_ids);

                    $itineary_modifed_route_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' AND `itinerary_route_id` NOT IN ('$array_of_modified_route_hotel_ID') ";
                    if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_details", '', '', $itineary_modifed_route_sqlWhere)) :
                        if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_amenities", '', '', $itineary_modifed_route_sqlWhere)) :
                            if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                $itineary_modifed_route_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' AND `itinerary_route_id` NOT IN ('$array_of_modified_route_ID') ";
                                if (sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_vehicle_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                    $itineary_modifed_route_sqlWhere = " `itinerary_plan_ID` = '$hidden_itinerary_plan_ID' AND `itinerary_route_ID` NOT IN ('$array_of_modified_route_ID') ";
                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_guide_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $itineary_modifed_route_sqlWhere)) :
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_via_route_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                                            $itineary_modifed_route_sqlWhere = " `itinerary_plan_id` = '$hidden_itinerary_plan_ID' AND `itinerary_route_id` NOT IN ('$array_of_modified_route_ID') ";
                                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_guide_slot_cost_details", '', '', $itineary_modifed_route_sqlWhere)) :
                                                                    endif;
                                                                endif;
                                                            endif;
                                                        endif;
                                                    endif;
                                                endif;
                                            endif;
                                        endif;
                                    endif;
                                endif;
                            endif;
                        endif;
                    endif;

                    foreach ($new_source_location as $key => $value) :
                        $route_counter++;
                        $no_of_route_count++;
                        $selected_SOURCE_LOCATION = $new_source_location[$key];
                        $selected_NO_OF_DAYS = 1;
                        $selected_NEXT_VISITING_PLACE = $new_next_visiting_location[$key];
                        //$selected_DIRECT_DESTINATION_VISIT = $_POST['direct_destination_visit'][$key + 1][0];
                        $selected_DIRECT_DESTINATION_VISIT_CHECK = 0;
                        /*if ($selected_DIRECT_DESTINATION_VISIT == 'on') :
                            $selected_DIRECT_DESTINATION_VISIT_CHECK = 1;
                        else :
                            $selected_DIRECT_DESTINATION_VISIT_CHECK = 0;
                        endif;*/

                        $itinerary_route_date = date('Y-m-d', strtotime($trip_start_date_and_time . ' + ' . $no_of_days . ' days'));
                        $no_of_days = $no_of_days + $selected_NO_OF_DAYS;

                        $fetch_distance_from_master_table = sqlQUERY_LABEL("SELECT `location_ID`,`distance` FROM  `dvi_stored_locations` WHERE `destination_location` = '$selected_NEXT_VISITING_PLACE' AND `source_location` = '$selected_SOURCE_LOCATION' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($fetch_distance_from_master_table) > 0) :
                            while ($row = sqlFETCHARRAY_LABEL($fetch_distance_from_master_table)) :
                                $location_ID = $row['location_ID'];
                                $distanceKM = $row['distance'];
                            endwhile;
                        else :
                            $distanceKM = 0;
                        endif;

                        if ($total_source_location_count == 1) :
                            $route_start_time = $trip_start_time;
                            $get_travelling_distance = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($selected_SOURCE_LOCATION,$selected_NEXT_VISITING_PLACE,'get_travelling_distance');
                            if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                if ($route_start_time >= '20:00:00') :
                                    $route_end_time = '23:59:00';
                                    $return_to_hotel = '23:59:00';
                                else :
                                    $route_end_time = '20:00:00';
                                    $return_to_hotel = '20:00:00';
                                endif;
                            else :
                                if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                    if ($route_start_time >= '20:00:00') :
                                        $route_end_time = '23:59:00';
                                        $return_to_hotel = '23:59:00';
                                    else :
                                        $route_end_time = '20:00:00';
                                        $return_to_hotel = '20:00:00';
                                    endif;
                                else:
                                    if ($route_start_time >= '20:00:00'):
                                            $route_end_time = '23:59:00';
                                            $return_to_hotel = '23:59:00';
                                    else:
                                        $route_end_time = '20:00:00';
                                        $return_to_hotel = '20:00:00';
                                    endif;
                                endif;
                            endif;
                        else :
                            if (trim($arrival_location) == trim($selected_SOURCE_LOCATION) && $route_counter == 1) :
                                $route_start_time = $trip_start_time;
                            else :
                                $route_start_time = '08:00:00';
                            endif;

                            if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $route_counter == $total_source_location_count) :
                                switch ($departure_type):
                                    case 1: // By Flight
                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                        break;
                                    case 2: // By Train
                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                        break;
                                    case 3: // By Road
                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                        break;
                                    default:
                                        $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                                endswitch;

                                // Convert buffer time to seconds
                                list($hours, $minutes, $seconds) = explode(':', $itinerary_travel_type_buffer_time);
                                $buffer_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                                // Convert trip end time to timestamp
                                $trip_end_timestamp = strtotime($trip_end_time);

                                // Subtract buffer time from trip end time
                                $adjusted_trip_end_timestamp = $trip_end_timestamp - $buffer_seconds;

                                // Convert adjusted time back to the desired format
                                $adjusted_trip_end_time = date('H:i:s', $adjusted_trip_end_timestamp);

                                $route_end_time = $adjusted_trip_end_time;
                                $return_to_hotel = $adjusted_trip_end_time;
                                $trip_last_day = true;
                            else :
                                $get_travelling_distance = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($selected_SOURCE_LOCATION,$selected_NEXT_VISITING_PLACE,'get_travelling_distance');
                                if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                    if ($route_start_time >= '20:00:00') :
                                        $route_end_time = '23:59:00';
                                        $return_to_hotel = '23:59:00';
                                    else :
                                        $route_end_time = '20:00:00';
                                        $return_to_hotel = '20:00:00';
                                    endif;
                                else :
                                    if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                        if ($route_start_time >= '20:00:00') :
                                            $route_end_time = '23:59:00';
                                            $return_to_hotel = '23:59:00';
                                        else :
                                            $route_end_time = '20:00:00';
                                            $return_to_hotel = '20:00:00';
                                        endif;
                                    else:
                                        if ($route_start_time >= '20:00:00'):
                                            $route_end_time = '23:59:00';
                                            $return_to_hotel = '23:59:00';
                                        else:
                                            $route_end_time = '20:00:00';
                                            $return_to_hotel = '20:00:00';
                                        endif;
                                    endif;
                                endif;
                                $trip_last_day = false;
                            endif;
                        endif;

                        $route_arrFields = array('`itinerary_plan_ID`', '`location_id`', '`location_name`', '`itinerary_route_date`', '`no_of_days`', '`no_of_km`', '`direct_to_next_visiting_place`', '`next_visiting_location`', '`route_start_time`', '`route_end_time`', '`createdby`', '`status`');

                        $route_arrValues = array("$itinerary_plan_ID", "$location_ID", "$selected_SOURCE_LOCATION", "$itinerary_route_date", "$selected_NO_OF_DAYS", "$distanceKM", "$selected_DIRECT_DESTINATION_VISIT_CHECK", "$selected_NEXT_VISITING_PLACE", "$route_start_time", "$route_end_time", "$logged_user_id", "1");

                        if ($hidden_itinerary_route_ID && $hidden_itinerary_route_date) :

                            $itinerary_route_ID = $hidden_itinerary_route_ID;

                            $update_via_route_details = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_via_route_details` WHERE `itinerary_route_ID` = '$hidden_itinerary_route_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$hidden_itinerary_route_date' AND `source_location` != '$selected_SOURCE_LOCATION' AND `destination_location` != '$selected_NEXT_VISITING_PLACE' AND `deleted` = '0'") or die("#1_UNABLE_TO_UPDATE_VIA_ROUTE_DATA:" . sqlERROR_LABEL());

                            $route_sqlwhere = " `itinerary_route_ID` = '$hidden_itinerary_route_ID' AND `itinerary_route_date` = '$hidden_itinerary_route_date' ";

                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $route_arrFields, $route_arrValues, $route_sqlwhere)) :

                                $itinerary_common_buffer_time = getGLOBALSETTING('itinerary_common_buffer_time');
                                $hotspot_start_time = $route_start_time;

                                // Convert time strings to seconds
                                $start_seconds = strtotime($hotspot_start_time);
                                $buffer_seconds = strtotime($itinerary_common_buffer_time) - strtotime('00:00:00');

                                // Add the buffer time to the start time
                                $total_seconds = $start_seconds + $buffer_seconds;

                                // Convert the total seconds back to the time format
                                $hotspot_end_time = date('H:i:s', $total_seconds);

                                if($itinerary_prefrence != 1):
                                    if (($hotspot_end_time <= $route_end_time && $trip_last_day == false) || ($trip_last_day == true)) :

                                        $select_itineary_hotspot_refresh_time_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '1'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        $select_itineary_hotspot_refresh_buffer_time_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_refresh_time_data);

                                        //INSERT HOTSPOT REFRESH TIME
                                        $route_hotspot_refresh_time_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                        $route_hotspot_refresh_time_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "1", "1", "$itinerary_common_buffer_time", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

                                        if ($trip_last_day == false) :
                                            //CHECK HOTSPOT REFRESH TIME RECORD AVAILABILITY
                                            if ($select_itineary_hotspot_refresh_buffer_time_count > 0) :
                                                $fetch_itineary_hotspot_refresh_time_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_refresh_time_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_refresh_time_data['route_hotspot_ID'];

                                                $route_hotspot_refresh_time_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '1' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, $route_hotspot_refresh_time_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, '')) :
                                                endif;
                                            endif;
                                        endif;

                                        $start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');

                                        $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                        //VIA ROUTE LOCATION SOURCE NAME
                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                            $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                        else :
                                            $filter_location_name = '';
                                        endif;

                                        //NEXT VISITING PLACE LOCATION NAME
                                        $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                        $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                        if ($get_via_route_IDs): 
                                            if ($get_via_route_IDs): 
                                                $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                            else: 
                                                $get_via_route_location_IDs = NULL;
                                            endif;

                                            // VIA ROUTE LOCATION NAME
                                            $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                            if ($via_route_name): 
                                                // Ensure that $via_route_name is an array
                                                if (is_array($via_route_name)): 
                                                    $via_route_conditions = array_map(function($location) {
                                                        // Use LIKE for pipe-separated values
                                                        return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                    }, $via_route_name);

                                                    // Join conditions with ' OR '
                                                    $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                else: 
                                                    $add_filter_via_route_location = '';
                                                endif;
                                            else: 
                                                $add_filter_via_route_location = '';
                                            endif;
                                        else: 
                                            $via_route_name = '';
                                            $add_filter_via_route_location = '';
                                        endif;

                                        //CHECK DIRECT DESTINATION TRAVEL
                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                            //INSERT HOTSPOT DIRECT DESTINATION TRAVEL

                                            if(empty($via_route_name)):
                                                
                                                $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                                $direct_destination_travel_start_time = $hotspot_end_time;

                                                $travel_distance = calculateTravelDistanceAndTime($start_location_id);

                                                $_distance = $travel_distance['distance'];
                                                $_time = $travel_distance['duration'];

                                                // Extract hours and minutes from the duration string
                                                preg_match('/(\d+) hour/', $_time, $hours_match);
                                                preg_match('/(\d+) min/', $_time, $minutes_match);

                                                $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                                                $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                                                // Format the time as H:i:s
                                                $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                                                // Convert times to seconds
                                                $seconds1 = strtotime("1970-01-01 $direct_destination_travel_start_time UTC");
                                                $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

                                                $direct_destination_travel_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));
                                                $hotspot_siteseeing_travel_start_time = $direct_destination_travel_end_time;

                                                $route_hotspot_direct_destination_visit_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_direct_destination_visit_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "2", "2", "$formatted_time", "$_distance", "$direct_destination_travel_start_time", "$direct_destination_travel_end_time", "$logged_user_id", "1");

                                                if ($select_itineary_hotspot_direct_destination_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, $route_hotspot_direct_destination_visit_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, '')) :
                                                    endif;
                                                endif;

                                                $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                $hotspot_order = 2;
                                            else:
                                                $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);
                                                if($select_itineary_hotspot_direct_destination_count > 0):
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];
                                                    $sqlWhere = " `route_hotspot_ID` = '$route_hotspot_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $sqlWhere)) :
                                                    endif;
                                                endif;
                                                $hotspot_order = 1;
                                                $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                                $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                                $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');
                                            endif;
                                        else :

                                            $hotspot_order = 1;
                                            $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                            $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                            $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');

                                            //DELETE THE DIRECT DESTINATION VISIT RECORD
                                            $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                            if ($select_itineary_hotspot_direct_destination_count > 0) :
                                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $route_hotspot_direct_destination_visit_sqlwhere)) :
                                                endif;
                                            endif;
                                        endif;

                                        if ($trip_last_day == false) :
                                            //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                            // Convert the date string to a Unix timestamp using strtotime
                                            $timestamp = strtotime($hidden_itinerary_route_date);

                                            if ($timestamp !== false) :
                                                // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                            endif;

                                            //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                            #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                            $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                            $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                            // Initialize variables for categorization
                                            $source_location_hotspots = [];
                                            $via_route_hotspots = [];
                                            $destination_hotspots = [];

                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                if(empty($via_route_name)):
                                                    $previous_hotspot_location = $location_name;
                                                else:
                                                    $previous_hotspot_location = $location_name;
                                                endif;
                                            else:
                                            // Initialize variables for the starting location
                                                $previous_hotspot_location = $location_name;
                                            endif;
                                            
                                            if ($select_hotspot_details_num_rows_count > 0): 
                                                while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                    // Proceed with adding the hotspot to the itinerary for the current day
                                                    $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                    $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                    $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                    $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                    $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                    $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                    $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                    $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                    $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                    // Determine the travel location type
                                                    $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                    // Calculate the distance and duration from the starting location
                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                    $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                    // Categorize hotspots based on location type
                                                    $hotspot_details = [
                                                        'hotspot_ID' => $hotspot_ID,
                                                        'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                        'hotspot_name' => $hotspot_name,
                                                        'hotspot_duration' => $hotspot_duration,
                                                        'hotspot_latitude' => $hotspot_latitude,
                                                        'hotspot_longitude' => $hotspot_longitude,
                                                        'hotspot_distance' => $get_hotspot_travelling_distance,
                                                        'hotspot_location' => $hotspot_location,
                                                        'hotspot_priority' => $hotspot_priority,
                                                        'previous_hotspot_location' => $previous_hotspot_location
                                                    ];

                                                    $source_match = containsLocation($hotspot_location, $location_name);
                                                    $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                    if ($source_match) :
                                                        $source_location_hotspots[] = $hotspot_details;
                                                    endif;

                                                    if ($destination_match) :
                                                        $destination_hotspots[] = $hotspot_details;
                                                    endif;

                                                    /* if (!$source_match && !$destination_match) :
                                                        $via_route_hotspots[] = $hotspot_details;
                                                    endif; */

                                                    $via_route_hotspots = []; // initialize before loop
                                                    $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                    if ($matchIndex !== false) {
                                                        // Group hotspots by VIA index
                                                        $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                    }
                                                endwhile;

                                                // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                ksort($via_route_hotspots);

                                                // Flatten grouped hotspots into a single ordered array
                                                $ordered_hotspots = [];
                                                foreach ($via_route_hotspots as $group) {
                                                    foreach ($group as $h) {
                                                        $ordered_hotspots[] = $h;
                                                    }
                                                }

                                                // Now use $ordered_hotspots instead of $via_route_hotspots
                                                $via_route_hotspots = $ordered_hotspots;

                                                sortHotspots($source_location_hotspots);
                                                sortHotspots($via_route_hotspots);
                                                sortHotspots($destination_hotspots);
                                                
                                                // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                endif;

                                                if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                    // Process via route hotspots
                                                    $processed_via_route_hotspots = false;
                                                    if(!empty($via_route_hotspots)):
                                                        foreach ($via_route_hotspots as $hotspot) :
                                                            $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($check_via_route_hotspot_added):
                                                            $processed_via_route_hotspots = true;
                                                            endif;
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                        endforeach;
                                                    endif;

                                                    if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                        // Execute the query to fetch via route IDs
                                                        $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                        // Fetch the results
                                                        if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                            while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                $via_route_traveling_time = $result['duration'];

                                                                // **EXTRACT AND FORMAT TIME DETAILS**
                                                                preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                $extraHours = floor($minutes / 60);
                                                                $hours += $extraHours;
                                                                $minutes %= 60;

                                                                $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                // **CALCULATE THE DURATION IN SECONDS**
                                                                $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                $hotspot_order++;
                                                                $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                
                                                                $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                    $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                    $staring_location_latitude = $via_route_location_lattitude;
                                                                    $staring_location_longtitude = $via_route_location_longitude;
                                                                    $previous_hotspot_location = $via_route_location;
                                                                endif;
                                                            endwhile;
                                                        endif;
                                                    endif;

                                                    // Process destination hotspots
                                                    if (!empty($destination_hotspots)) :
                                                        foreach ($destination_hotspots as $hotspot) :
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                            includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                        endforeach;
                                                    endif;
                                                else:
                                                    // Process source location hotspots
                                                    if (!empty($source_location_hotspots)) :
                                                        foreach ($source_location_hotspots as $hotspot) :
                                                            includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                        endforeach;
                                                    endif;

                                                    // Process via route hotspots
                                                    $processed_via_route_hotspots = false;
                                                    if (!empty($via_route_hotspots)) :
                                                        foreach ($via_route_hotspots as $hotspot) :
                                                            $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($check_via_route_hotspot_added):
                                                            $processed_via_route_hotspots = true;
                                                            endif;
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                        endforeach;
                                                    endif;

                                                    if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                        // Execute the query to fetch via route IDs
                                                        $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                        // Fetch the results
                                                        if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                            while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                $via_route_traveling_time = $result['duration'];

                                                                // **EXTRACT AND FORMAT TIME DETAILS**
                                                                preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                $extraHours = floor($minutes / 60);
                                                                $hours += $extraHours;
                                                                $minutes %= 60;

                                                                $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                // **CALCULATE THE DURATION IN SECONDS**
                                                                $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                $hotspot_order++;
                                                                $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                
                                                                $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                    $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                    $staring_location_latitude = $via_route_location_lattitude;
                                                                    $staring_location_longtitude = $via_route_location_longitude;
                                                                    $previous_hotspot_location = $via_route_location;
                                                                endif;
                                                            endwhile;
                                                        endif;
                                                    endif;

                                                    if (!empty($destination_hotspots)) :
                                                    // Process destination hotspots
                                                        foreach ($destination_hotspots as $hotspot) :
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                            includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                        endforeach;
                                                    endif;

                                                endif;
                                            endif;
                                        endif;

                                        $itinerary_travel_type_buffer_time = "00:00:00";

                                        $get_last_route_id_from_this_itinerary_plan = sqlQUERY_LABEL("SELECT MAX(`itinerary_route_ID`) AS max_route_id FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                        $fetch_last_route_id_from_this_itineary_plan = sqlFETCHARRAY_LABEL($get_last_route_id_from_this_itinerary_plan);
                                        $last_itinerary_route_ID = $fetch_last_route_id_from_this_itineary_plan['max_route_id'];

                                        //INSERT THE END OF THE TRIP DEPARTURE START TIME
                                        if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $last_itinerary_route_ID == $itinerary_route_ID && $trip_last_day == true) :
                                            $hotspot_order = $hotspot_order;

                                            //Determine the buffer time based on the departure_type [1 - By Flight | 2 - By Train | 3 - By Road]
                                            switch ($departure_type):
                                                    case 1: // By Flight
                                                    $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                                    break;
                                                case 2: // By Train
                                                    $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                                    break;
                                                case 3: // By Road
                                                    $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                                    break;
                                                default:
                                                    $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                                            endswitch;

                                            $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                            $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                            $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                            // Determine the travel location type
                                            $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                            // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                            $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                            $destination_traveling_time = $result['duration'];

                                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                            preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                            preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                            $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                            //CALCAULATE THE DURATIONS IN SECONDS
                                            $totalSeconds = ($hours * 3600) + ($minutes * 60);

                                            //ADD THE DURATION TO THE START TIME
                                            $newTimeInSeconds = $totalSeconds + strtotime($itinerary_travel_type_buffer_time);

                                            //CONVERT THE NEW TIME TO {destination_total_duration} H:i:s FORMAT
                                            $destination_total_duration = date('H:i:s', $newTimeInSeconds);

                                            // Convert hotspot_siteseeing_travel_start_time to seconds
                                            $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                            // Convert destination_total_duration to seconds
                                            list($hours, $minutes, $seconds) = sscanf($destination_total_duration, "%d:%d:%d");
                                            $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                            // Add the duration and buffer time to the start time
                                            $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds + $bufferInSeconds;

                                            // Convert the total time back to H:i:s format
                                            $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                            if ($destination_travel_end_time <= $route_end_time) :

                                                // Format total traveling time
                                                $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                                /* // Format buffer time and convert to seconds
                                                $itinerary_travel_type_buffer_time_formatted = date('H:i:s', strtotime($itinerary_travel_type_buffer_time));
                                                list($buffer_hours, $buffer_minutes, $buffer_seconds) = explode(':', $itinerary_travel_type_buffer_time_formatted);
                                                $itinerary_travel_buffer_seconds = ($buffer_hours * 3600) + ($buffer_minutes * 60) + $buffer_seconds; */

                                                // Convert route end time to timestamp
                                                $route_end_timestamp = strtotime($route_end_time);

                                                // Convert total traveling time to seconds
                                                list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                                $travelling_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                                                // Subtract the total traveling time and buffer time from the route end time
                                                $adjusted_route_start_timestamp = $route_end_timestamp - ($travelling_seconds);
                                                /* $itinerary_travel_buffer_seconds */

                                                // Convert the adjusted time back to the desired format
                                                $adjusted_route_hotspot_end_time = date('H:i:s', $adjusted_route_start_timestamp);

                                                if ($timestamp !== false) :
                                                    // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                    $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                                endif;

                                                $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                                //ROUTE LOCATION SOURCE NAME
                                                if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                                    $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                                else :
                                                    $filter_location_name = '';
                                                endif;

                                                //NEXT VISITING PLACE LOCATION NAME
                                                $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                                if ($get_via_route_IDs): 
                                                    
                                                    if ($get_via_route_IDs): 
                                                        $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                                    else: 
                                                        $get_via_route_location_IDs = NULL;
                                                    endif;

                                                    // VIA ROUTE LOCATION NAME
                                                    $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                                    if ($via_route_name): 
                                                        // Ensure that $via_route_name is an array
                                                        if (is_array($via_route_name)): 
                                                            $via_route_conditions = array_map(function($location) {
                                                                // Use LIKE for pipe-separated values
                                                                return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                            }, $via_route_name);

                                                            // Join conditions with ' OR '
                                                            $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                        else: 
                                                            $add_filter_via_route_location = '';
                                                        endif;
                                                    else: 
                                                        $add_filter_via_route_location = '';
                                                    endif;
                                                else: 
                                                    $via_route_name = '';
                                                    $add_filter_via_route_location = '';
                                                endif;

                                                //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                                #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                                $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                                $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                                // Initialize variables for categorization
                                                $source_location_hotspots = [];
                                                $via_route_hotspots = [];
                                                $destination_hotspots = [];

                                                if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                    if(empty($via_route_name)):
                                                        $previous_hotspot_location = $location_name;
                                                    else:
                                                        $previous_hotspot_location = $location_name;
                                                    endif;
                                                else:
                                                // Initialize variables for the starting location
                                                    $previous_hotspot_location = $location_name;
                                                endif;

                                                if ($select_hotspot_details_num_rows_count > 0): 
                                                    while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                        // Proceed with adding the hotspot to the itinerary for the current day
                                                        $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                        $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                        $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                        $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                        $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                        $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                        $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                        $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                        $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                        // Determine the travel location type
                                                        $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                        // Calculate the distance and duration from the starting location
                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                        $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                        // Categorize hotspots based on location type
                                                        $hotspot_details = [
                                                            'hotspot_ID' => $hotspot_ID,
                                                            'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                            'hotspot_name' => $hotspot_name,
                                                            'hotspot_duration' => $hotspot_duration,
                                                            'hotspot_latitude' => $hotspot_latitude,
                                                            'hotspot_longitude' => $hotspot_longitude,
                                                            'hotspot_distance' => $get_hotspot_travelling_distance,
                                                            'hotspot_location' => $hotspot_location,
                                                            'hotspot_priority' => $hotspot_priority,
                                                            'previous_hotspot_location'=>$previous_hotspot_location
                                                        ];

                                                        $source_match = containsLocation($hotspot_location, $location_name);
                                                        $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                        if ($source_match) :
                                                            $source_location_hotspots[] = $hotspot_details;
                                                        endif;

                                                        if ($destination_match) :
                                                            $destination_hotspots[] = $hotspot_details;
                                                        endif;

                                                        /* if (!$source_match && !$destination_match) :
                                                            $via_route_hotspots[] = $hotspot_details;
                                                        endif; */

                                                        $via_route_hotspots = []; // initialize before loop
                                                        $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                        if ($matchIndex !== false) {
                                                            // Group hotspots by VIA index
                                                            $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                        }

                                                    endwhile;

                                                    // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                    ksort($via_route_hotspots);

                                                    // Flatten grouped hotspots into a single ordered array
                                                    $ordered_hotspots = [];
                                                    foreach ($via_route_hotspots as $group) {
                                                        foreach ($group as $h) {
                                                            $ordered_hotspots[] = $h;
                                                        }
                                                    }

                                                    // Now use $ordered_hotspots instead of $via_route_hotspots
                                                    $via_route_hotspots = $ordered_hotspots;

                                                    sortHotspots($source_location_hotspots);
                                                    sortHotspots($via_route_hotspots);
                                                    sortHotspots($destination_hotspots);

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                    $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                    $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                    $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                    $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                    $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                    endif;

                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                        // Process via route hotspots
                                                        $hotspot_processed = false;
                                                        $processed_via_route_hotspots = false;
                                                        if(!empty($via_route_hotspots)):
                                                            foreach ($via_route_hotspots as $hotspot) :
                                                                $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($check_via_route_hotspot_added):
                                                                $processed_via_route_hotspots = true;
                                                                $hotspot_processed = true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                            $hotspot_processed = false;
                                                            // Execute the query to fetch via route IDs
                                                            $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                            // Fetch the results
                                                            if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                    $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                    $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                    $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                    $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                    $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                    $via_route_traveling_time = $result['duration'];

                                                                    // **EXTRACT AND FORMAT TIME DETAILS**
                                                                    preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                    preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                    // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                    $extraHours = floor($minutes / 60);
                                                                    $hours += $extraHours;
                                                                    $minutes %= 60;

                                                                    $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                    // **CALCULATE THE DURATION IN SECONDS**
                                                                    $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                    // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                    $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                    $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                    $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                    $hotspot_order++;
                                                                    $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                    
                                                                    $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                    // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                        $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                        $staring_location_latitude = $via_route_location_lattitude;
                                                                        $staring_location_longtitude = $via_route_location_longitude;
                                                                        $previous_hotspot_location = $via_route_location;
                                                                        $hotspot_processed = true;
                                                                    endif;
                                                                endwhile;
                                                            endif;
                                                        endif;
                                                        
                                                        // Process destination hotspots
                                                        if (!empty($destination_hotspots)) :
                                                            $hotspot_processed = false;
                                                            foreach ($destination_hotspots as $hotspot) :
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                                $destination_hotspots_processed = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($destination_hotspots_processed):
                                                                $hotspot_processed = true;
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                    else:
                                                        // Process source location hotspots
                                                        if (!empty($source_location_hotspots)) :
                                                            $hotspot_processed = false;
                                                            foreach ($source_location_hotspots as $hotspot) :
                                                                $source_hotspots_processed = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($source_hotspots_processed):
                                                                $hotspot_processed = true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        // Process via route hotspots
                                                        $processed_via_route_hotspots = false;
                                                        if (!empty($via_route_hotspots)) :
                                                            $hotspot_processed = false;
                                                            foreach ($via_route_hotspots as $hotspot) :
                                                                $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($check_via_route_hotspot_added):
                                                                $processed_via_route_hotspots = true;
                                                                $hotspot_processed = true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                            $hotspot_processed = false;
                                                            // Execute the query to fetch via route IDs
                                                            $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                            // Fetch the results
                                                            if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                    $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                    $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                    $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                    $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                    $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                    $via_route_traveling_time = $result['duration'];

                                                                    // **EXTRACT AND FORMAT TIME DETAILS**
                                                                    preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                    preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                    // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                    $extraHours = floor($minutes / 60);
                                                                    $hours += $extraHours;
                                                                    $minutes %= 60;

                                                                    $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                    // **CALCULATE THE DURATION IN SECONDS**
                                                                    $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                    // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                    $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                    $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                    $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                    $hotspot_order++;
                                                                    $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                    
                                                                    $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                    // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                        $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                        $staring_location_latitude = $via_route_location_lattitude;
                                                                        $staring_location_longtitude = $via_route_location_longitude;
                                                                        $previous_hotspot_location = $via_route_location;
                                                                        $$hotspot_processed = true;
                                                                    endif;
                                                                endwhile;
                                                            endif;
                                                        endif;
                                                        
                                                        if (!empty($destination_hotspots)) :
                                                            $hotspot_processed = false;
                                                            // Process destination hotspots
                                                            foreach ($destination_hotspots as $hotspot) :
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                                $destination_hotspots_processed = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($destination_hotspots_processed):
                                                                $hotspot_processed = true;
                                                                endif;
                                                            endforeach;
                                                        endif;
                                                    endif;
                                                endif;
                                                
                                                if($hotspot_processed):
                                                    $last_hotspot_location = $last_hotspot_details['last_hotspot_location'];
                                                    $last_hotspot_latitude = $last_hotspot_details['last_hotspot_latitude'];
                                                    $last_hotspot_longitude = $last_hotspot_details['last_hotspot_longitude'];
                                                    $hotspot_siteseeing_travel_start_time = $last_hotspot_details['last_hotspot_end_time'];
                                                else:
                                                    $last_hotspot_location = $previous_hotspot_location;
                                                    $last_hotspot_latitude = $staring_location_latitude;
                                                    $last_hotspot_longitude = $staring_location_longtitude;
                                                    $hotspot_siteseeing_travel_start_time = $hotspot_siteseeing_travel_start_time;
                                                endif;

                                                // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                $travel_location_type = getTravelLocationType($last_hotspot_location, $ending_location_name);
                                                $result = calculateDistanceAndDuration($last_hotspot_latitude, $last_hotspot_longitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                $destination_traveling_time = $result['duration'];

                                                // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                                                // INITIALIZE HOURS AND MINUTES TO ZERO
                                                $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                // CALCULATE TOTAL DURATION IN SECONDS (hours and minutes combined)
                                                $totalDurationInSeconds = ($hours * 3600) + ($minutes * 60);

                                                // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59 (if needed)
                                                $extraHours = floor($minutes / 60);
                                                $hours += $extraHours;
                                                $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                                                // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                                                $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                                                $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                                                // FORMAT THE TOTAL DURATION AS H:i:s (destination_total_duration)
                                                $destination_total_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                // CONVERT hotspot_siteseeing_travel_start_time TO SECONDS
                                                $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                // ADD THE TOTAL DURATION TO THE START TIME (in seconds)
                                                $totalTimeInSeconds = $startTimeInSeconds + $totalDurationInSeconds;

                                                // CONVERT THE TOTAL TIME BACK TO H:i:s FORMAT (destination_travel_end_time)
                                                $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);
                                                
                                                $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                $hotspot_order++;
                                                $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$destination_total_duration", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                                if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                    endif;
                                                endif;
                                            else :

                                                // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                endif;

                                                $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                                // Convert route end time to timestamp
                                                $route_end_timestamp = strtotime($route_end_time);

                                                // Convert total traveling time to seconds
                                                list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                                $travelling_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                // Subtract the total traveling time from the route end time
                                                $adjusted_route_start_timestamp = $route_end_timestamp - $travelling_seconds;

                                                // Convert the adjusted time back to the desired format
                                                $adjusted_route_start_time = date('H:i:s', $adjusted_route_start_timestamp);

                                                $itinerary_route_details_arrFields = array('`route_start_time`', '`route_end_time`');
                                                $itinerary_route_details_arrValues = array("$adjusted_route_start_time", "$route_end_time");
                                                $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";

                                                //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :
                                                endif;

                                                $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                $hotspot_order++;
                                                $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$total_travelling_time", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$adjusted_route_start_time", "$route_end_time", "$logged_user_id", "1");

                                                if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                    endif;
                                                endif;
                                            endif;
                                        else :

                                            $hotspot_order = $hotspot_order;

                                            $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                            $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                            $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                            // Determine the travel location type
                                            $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                            // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                            $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                            $destination_traveling_time = $result['duration'];

                                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                            preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                            preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                            $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                            // Convert hotspot_siteseeing_travel_start_time to seconds
                                            $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                            // Convert destination_total_duration to seconds
                                            list($hours, $minutes, $seconds) = sscanf($duration_formatted, "%d:%d:%d");
                                            $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                            // Add the duration and buffer time to the start time
                                            $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds;

                                            // Convert the total time back to H:i:s format
                                            $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                            $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '5'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                            $hotspot_order++;
                                            $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "5", "$hotspot_order", "$duration_formatted", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '5' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                endif;
                                            endif;
                                            $hotspot_siteseeing_travel_start_time = $destination_travel_end_time;

                                            $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                                            $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_hotel_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "6", "$hotspot_order", "$hotspot_siteseeing_travel_start_time", "$hotspot_siteseeing_travel_start_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                                                $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                                                endif;
                                            endif;
                                        endif;
                                    else :
                                        $delete_route_hotspot_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_route_hotspot_details_sqlwhere)) :
                                        endif;
                                        $response['route_end_time_reached'] = true;
                                    endif;
                                endif;

                                $response['i_result'] = true;
                                $response['redirect_URL'] = 'latestitinerary.php?route=edit&formtype=generate_itinerary&id=' . $itinerary_plan_ID.'&selected_group_type=1';
                                $response['itinerary_plan_ID'] = $itinerary_plan_ID;
                                $response['result_success'] = true;
                            else :
                                $response['i_result'] = false;
                                $response['result_success'] = false;
                            endif;
                        else :
                            //INSERT ROUTE DETAILS
                            $check_itineary_route_details_avilability = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$itinerary_route_date' AND `status` = '1' AND `deleted` = '0'") or die(sqlERROR_LABEL());
                            $get_total_route_details_availabilty_count = sqlNUMOFROW_LABEL($check_itineary_route_details_avilability);

                            if ($get_total_route_details_availabilty_count == 0) :
                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $route_arrFields, $route_arrValues, '')) :
                                    $itinerary_route_ID = sqlINSERTID_LABEL();

                                    $update_via_route_details = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_via_route_details` WHERE `itinerary_route_ID` = '$hidden_itinerary_route_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$itinerary_route_date' AND `source_location` != '$selected_SOURCE_LOCATION' AND `destination_location` != '$selected_NEXT_VISITING_PLACE' AND `deleted` = '0'") or die("#1_UNABLE_TO_UPDATE_VIA_ROUTE_DATA:" . sqlERROR_LABEL());

                                    $itinerary_common_buffer_time = getGLOBALSETTING('itinerary_common_buffer_time');
                                    $hotspot_start_time = $route_start_time;

                                    // Convert time strings to seconds
                                    $start_seconds = strtotime($hotspot_start_time);
                                    $buffer_seconds = strtotime($itinerary_common_buffer_time) - strtotime('00:00:00');

                                    // Add the buffer time to the start time
                                    $total_seconds = $start_seconds + $buffer_seconds;

                                    // Convert the total seconds back to the time format
                                    $hotspot_end_time = date('H:i:s', $total_seconds);

                                    if($itinerary_prefrence != 1):
                                        if (($hotspot_end_time <= $route_end_time && $trip_last_day == false) ||($trip_last_day == true)) :

                                            $select_itineary_hotspot_refresh_time_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '1'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_refresh_buffer_time_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_refresh_time_data);

                                            //INSERT HOTSPOT REFRESH TIME
                                            $route_hotspot_refresh_time_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_refresh_time_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "1", "1", "$itinerary_common_buffer_time", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

                                            if ($trip_last_day == false) :
                                                //CHECK HOTSPOT REFRESH TIME RECORD AVAILABILITY
                                                if ($select_itineary_hotspot_refresh_buffer_time_count > 0) :
                                                    $fetch_itineary_hotspot_refresh_time_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_refresh_time_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_refresh_time_data['route_hotspot_ID'];

                                                    $route_hotspot_refresh_time_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '1' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, $route_hotspot_refresh_time_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, '')) :
                                                    endif;
                                                endif;
                                            endif;

                                            $start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');

                                            $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                            //ROUTE LOCATION SOURCE NAME
                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                                $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                            else :
                                                $filter_location_name = '';
                                            endif;

                                            //NEXT VISITING PLACE LOCATION NAME
                                            $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                            $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                            if ($get_via_route_IDs): 
                                                
                                                if ($get_via_route_IDs): 
                                                    $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                                else: 
                                                    $get_via_route_location_IDs = NULL;
                                                endif;

                                                // VIA ROUTE LOCATION NAME
                                                $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                                if ($via_route_name): 
                                                    // Ensure that $via_route_name is an array
                                                    if (is_array($via_route_name)): 
                                                        $via_route_conditions = array_map(function($location) {
                                                            // Use LIKE for pipe-separated values
                                                            return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                        }, $via_route_name);

                                                        // Join conditions with ' OR '
                                                        $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                    else: 
                                                        $add_filter_via_route_location = '';
                                                    endif;
                                                else: 
                                                    $add_filter_via_route_location = '';
                                                endif;
                                            else: 
                                                $via_route_name = '';
                                                $add_filter_via_route_location = '';
                                            endif;

                                            //CHECK DIRECT DESTINATION TRAVEL
                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                //INSERT HOTSPOT DIRECT DESTINATION TRAVEL

                                                if(empty($via_route_name)):
                                                    $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                                    $direct_destination_travel_start_time = $hotspot_end_time;

                                                    $travel_distance = calculateTravelDistanceAndTime($start_location_id);
                                                    $_distance = $travel_distance['distance'];
                                                    $_time = $travel_distance['duration'];

                                                    // Extract hours and minutes from the duration string
                                                    preg_match('/(\d+) hour/', $_time, $hours_match);
                                                    preg_match('/(\d+) min/', $_time, $minutes_match);

                                                    $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                                                    $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                                                    // Format the time as H:i:s
                                                    $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                                                    // Convert times to seconds
                                                    $seconds1 = strtotime("1970-01-01 $direct_destination_travel_start_time UTC");
                                                    $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

                                                    $direct_destination_travel_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

                                                    $route_hotspot_direct_destination_visit_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                    $route_hotspot_direct_destination_visit_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "2", "2", "$formatted_time", "$_distance", "$direct_destination_travel_start_time", "$direct_destination_travel_end_time", "$logged_user_id", "1");

                                                    if ($select_itineary_hotspot_direct_destination_count > 0) :
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                        $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, $route_hotspot_direct_destination_visit_sqlwhere)) :
                                                        endif;
                                                    else :
                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, '')) :
                                                        endif;
                                                    endif;

                                                    $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                    $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                    $hotspot_order = 2;
                                                else:
                                                    $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);
                                                    if($select_itineary_hotspot_direct_destination_count > 0):
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];
                                                        $sqlWhere = " `route_hotspot_ID` = '$route_hotspot_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $sqlWhere)) :
                                                        endif;
                                                    endif;
                                                    $hotspot_order = 1;
                                                    $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                                    $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                                    $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');
                                                endif;
                                            else :

                                                $hotspot_order = 1;
                                                $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                                $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                                $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');

                                                //DELETE THE DIRECT DESTINATION VISIT RECORD
                                                $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                                if ($select_itineary_hotspot_direct_destination_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $route_hotspot_direct_destination_visit_sqlwhere)) :
                                                    endif;
                                                endif;
                                            endif;

                                            if ($trip_last_day == false) :
                                                //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                                // Convert the date string to a Unix timestamp using strtotime
                                                $timestamp = strtotime($hidden_itinerary_route_date);

                                                if ($timestamp !== false) :
                                                    // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                    $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                                endif;

                                                //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                                #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                                $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                                $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                                // Initialize variables for categorization
                                                $source_location_hotspots = [];
                                                $via_route_hotspots = [];
                                                $destination_hotspots = [];

                                                if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                    if(empty($via_route_name)):
                                                        $previous_hotspot_location = $location_name;
                                                    else:
                                                        $previous_hotspot_location = $location_name;
                                                    endif;
                                                else:
                                                // Initialize variables for the starting location
                                                    $previous_hotspot_location = $location_name;
                                                endif;

                                                if ($select_hotspot_details_num_rows_count > 0): 
                                                    while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                        // Proceed with adding the hotspot to the itinerary for the current day
                                                        $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                        $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                        $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                        $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                        $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                        $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                        $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                        $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                        $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                        // Determine the travel location type
                                                        $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                        // Calculate the distance and duration from the starting location
                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                        $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                        // Categorize hotspots based on location type
                                                        $hotspot_details = [
                                                            'hotspot_ID' => $hotspot_ID,
                                                            'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                            'hotspot_name' => $hotspot_name,
                                                            'hotspot_duration' => $hotspot_duration,
                                                            'hotspot_latitude' => $hotspot_latitude,
                                                            'hotspot_longitude' => $hotspot_longitude,
                                                            'hotspot_distance' => $get_hotspot_travelling_distance,
                                                            'hotspot_location' => $hotspot_location,
                                                            'hotspot_priority' => $hotspot_priority,
                                                            'previous_hotspot_location' => $previous_hotspot_location
                                                        ];

                                                        $source_match = containsLocation($hotspot_location, $location_name);
                                                        $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                        if ($source_match) :
                                                            $source_location_hotspots[] = $hotspot_details;
                                                        endif;

                                                        if ($destination_match) :
                                                            $destination_hotspots[] = $hotspot_details;
                                                        endif;

                                                        /* if (!$source_match && !$destination_match) :
                                                            $via_route_hotspots[] = $hotspot_details;
                                                        endif; */

                                                        $via_route_hotspots = []; // initialize before loop
                                                        $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                        if ($matchIndex !== false) {
                                                            // Group hotspots by VIA index
                                                            $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                        }

                                                    endwhile;

                                                    // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                    ksort($via_route_hotspots);

                                                    // Flatten grouped hotspots into a single ordered array
                                                    $ordered_hotspots = [];
                                                    foreach ($via_route_hotspots as $group) {
                                                        foreach ($group as $h) {
                                                            $ordered_hotspots[] = $h;
                                                        }
                                                    }

                                                    // Now use $ordered_hotspots instead of $via_route_hotspots
                                                    $via_route_hotspots = $ordered_hotspots;

                                                    sortHotspots($source_location_hotspots);
                                                    sortHotspots($via_route_hotspots);
                                                    sortHotspots($destination_hotspots);

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                    $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                    $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                    $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                    $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                    $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                    endif;

                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                        // Process via route hotspots
                                                        $processed_via_route_hotspots = false;
                                                        if(!empty($via_route_hotspots)):
                                                            foreach ($via_route_hotspots as $hotspot) :
                                                                $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($check_via_route_hotspot_added):
                                                                $processed_via_route_hotspots = true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                        // Execute the query to fetch via route IDs
                                                            $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                            // Fetch the results
                                                            if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                    $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                    $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                    $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                    $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                    $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                    $via_route_traveling_time = $result['duration'];

                                                                    // **EXTRACT AND FORMAT TIME DETAILS**
                                                                    preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                    preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                    // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                    $extraHours = floor($minutes / 60);
                                                                    $hours += $extraHours;
                                                                    $minutes %= 60;

                                                                    $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                    // **CALCULATE THE DURATION IN SECONDS**
                                                                    $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                    // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                    $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                    $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                    $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                    $hotspot_order++;
                                                                    $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                    
                                                                    $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                    // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                        $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                        $staring_location_latitude = $via_route_location_lattitude;
                                                                        $staring_location_longtitude = $via_route_location_longitude;
                                                                        $previous_hotspot_location = $via_route_location;
                                                                    endif;
                                                                endwhile;
                                                            endif;
                                                        endif;
                                                        
                                                        // Process destination hotspots
                                                        if (!empty($destination_hotspots)) :
                                                            foreach ($destination_hotspots as $hotspot) :
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                                includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            endforeach;
                                                        endif;
                                                    else:
                                                        // Process source location hotspots
                                                        if (!empty($source_location_hotspots)) :
                                                            foreach ($source_location_hotspots as $hotspot) :
                                                                includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        // Process via route hotspots
                                                        $processed_via_route_hotspots =false;
                                                        if (!empty($via_route_hotspots)) :
                                                            foreach ($via_route_hotspots as $hotspot) :
                                                                $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($check_via_route_hotspot_added):
                                                                $processed_via_route_hotspots =true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                        // Execute the query to fetch via route IDs
                                                            $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                            // Fetch the results
                                                            if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                    $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                    $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                    $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                    $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                    $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                    $via_route_traveling_time = $result['duration'];

                                                                    // **EXTRACT AND FORMAT TIME DETAILS**
                                                                    preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                    preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                    // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                    $extraHours = floor($minutes / 60);
                                                                    $hours += $extraHours;
                                                                    $minutes %= 60;

                                                                    $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                    // **CALCULATE THE DURATION IN SECONDS**
                                                                    $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                    // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                    $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                    $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                    $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                    $hotspot_order++;
                                                                    $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                    
                                                                    $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                    // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                        $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                        $staring_location_latitude = $via_route_location_lattitude;
                                                                        $staring_location_longtitude = $via_route_location_longitude;
                                                                        $previous_hotspot_location = $via_route_location;
                                                                    endif;
                                                                endwhile;
                                                            endif;
                                                        endif;

                                                        if (!empty($destination_hotspots)) :
                                                        // Process destination hotspots
                                                            foreach ($destination_hotspots as $hotspot) :
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                                includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            endforeach;
                                                        endif;
                                                    endif;
                                                endif;
                                            endif;

                                            $get_last_route_id_from_this_itinerary_plan = sqlQUERY_LABEL("SELECT MAX(`itinerary_route_ID`) max_route_id FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                            $fetch_last_route_id_from_this_itineary_plan = sqlFETCHARRAY_LABEL($get_last_route_id_from_this_itinerary_plan);
                                            $last_itinerary_route_ID = $fetch_last_route_id_from_this_itineary_plan['max_route_id'];

                                            //INSERT THE END OF THE TRIP DEPARTURE START TIME
                                            if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $last_itinerary_route_ID == $itinerary_route_ID && $trip_last_day == true) :
                                                $hotspot_order = $hotspot_order;

                                                //Determine the buffer time based on the departure_type [1 - By Flight | 2 - By Train | 3 - By Road]
                                                switch ($departure_type):
                                                        case 1: // By Flight
                                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                                        break;
                                                    case 2: // By Train
                                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                                        break;
                                                    case 3: // By Road
                                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                                        break;
                                                    default:
                                                        $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                                                endswitch;

                                                $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                // Determine the travel location type
                                                $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                                // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                $destination_traveling_time = $result['duration'];

                                                // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                                $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                //CALCAULATE THE DURATIONS IN SECONDS
                                                $totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                //ADD THE DURATION TO THE START TIME
                                                $newTimeInSeconds = $totalSeconds + strtotime($itinerary_travel_type_buffer_time);

                                                //CONVERT THE NEW TIME TO {destination_total_duration} H:i:s FORMAT
                                                $destination_total_duration = date('H:i:s', $newTimeInSeconds);

                                                // Convert hotspot_siteseeing_travel_start_time to seconds
                                                $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                // Convert destination_total_duration to seconds
                                                list($hours, $minutes, $seconds) = sscanf($destination_total_duration, "%d:%d:%d");
                                                $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                // Add the duration and buffer time to the start time
                                                $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds + $bufferInSeconds;

                                                // Convert the total time back to H:i:s format
                                                $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                                if ($destination_travel_end_time <= $route_end_time) :

                                                    // Format total traveling time
                                                    $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                                    // Format buffer time and convert to seconds
                                                    $itinerary_travel_type_buffer_time_formatted = date('H:i:s', strtotime($itinerary_travel_type_buffer_time));
                                                    list($buffer_hours, $buffer_minutes, $buffer_seconds) = explode(':', $itinerary_travel_type_buffer_time_formatted);
                                                    $itinerary_travel_buffer_seconds = ($buffer_hours * 3600) + ($buffer_minutes * 60) + $buffer_seconds;

                                                    // Convert route end time to timestamp
                                                    $route_end_timestamp = strtotime($route_end_time);

                                                    // Convert total traveling time to seconds
                                                    list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                                    $travelling_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                                                    // Subtract the total traveling time and buffer time from the route end time
                                                    $adjusted_route_start_timestamp = $route_end_timestamp - ($travelling_seconds + $itinerary_travel_buffer_seconds);

                                                    // Convert the adjusted time back to the desired format
                                                    $adjusted_route_hotspot_end_time = date('H:i:s', $adjusted_route_start_timestamp);

                                                    //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                                    // Convert the date string to a Unix timestamp using strtotime
                                                    $timestamp = strtotime($hidden_itinerary_route_date);

                                                    if ($timestamp !== false) :
                                                        // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                        $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                                    endif;
                                                    
                                                    $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                                    //ROUTE LOCATION SOURCE NAME
                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                                        $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                                    else :
                                                        $filter_location_name = '';
                                                    endif;

                                                    //NEXT VISITING PLACE LOCATION NAME
                                                    $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                    $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                                    if ($get_via_route_IDs): 
                                                        
                                                        if ($get_via_route_IDs): 
                                                            $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                                        else: 
                                                            $get_via_route_location_IDs = NULL;
                                                        endif;

                                                        // VIA ROUTE LOCATION NAME
                                                        $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                                        if ($via_route_name): 
                                                            // Ensure that $via_route_name is an array
                                                            if (is_array($via_route_name)): 
                                                                $via_route_conditions = array_map(function($location) {
                                                                    // Use LIKE for pipe-separated values
                                                                    return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                                }, $via_route_name);

                                                                // Join conditions with ' OR '
                                                                $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                            else: 
                                                                $add_filter_via_route_location = '';
                                                            endif;
                                                        else: 
                                                            $add_filter_via_route_location = '';
                                                        endif;
                                                    else: 
                                                        $via_route_name = '';
                                                        $add_filter_via_route_location = '';
                                                    endif;

                                                    //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                                    #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                                    $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                                    $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                                    // Initialize variables for categorization
                                                    $source_location_hotspots = [];
                                                    $via_route_hotspots = [];
                                                    $destination_hotspots = [];

                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                        if(empty($via_route_name)):
                                                            $previous_hotspot_location = $location_name;
                                                        else:
                                                            $previous_hotspot_location = $location_name;
                                                        endif;
                                                    else:
                                                    // Initialize variables for the starting location
                                                        $previous_hotspot_location = $location_name;
                                                    endif;

                                                    if ($select_hotspot_details_num_rows_count > 0): 
                                                        while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                            // Proceed with adding the hotspot to the itinerary for the current day
                                                            $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                            $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                            $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                            $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                            $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                            $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                            $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                            $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                            $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                            // Determine the travel location type
                                                            $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                            // Calculate the distance and duration from the starting location
                                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                            $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                            // Categorize hotspots based on location type
                                                            $hotspot_details = [
                                                                'hotspot_ID' => $hotspot_ID,
                                                                'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                                'hotspot_name' => $hotspot_name,
                                                                'hotspot_duration' => $hotspot_duration,
                                                                'hotspot_latitude' => $hotspot_latitude,
                                                                'hotspot_longitude' => $hotspot_longitude,
                                                                'hotspot_distance' => $get_hotspot_travelling_distance,
                                                                'hotspot_location' => $hotspot_location,
                                                                'hotspot_priority' => $hotspot_priority,
                                                                'previous_hotspot_location' => $previous_hotspot_location
                                                            ];

                                                            $source_match = containsLocation($hotspot_location, $location_name);
                                                            $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                            if ($source_match) :
                                                                $source_location_hotspots[] = $hotspot_details;
                                                            endif;

                                                            if ($destination_match) :
                                                                $destination_hotspots[] = $hotspot_details;
                                                            endif;

                                                            /* if (!$source_match && !$destination_match) :
                                                                $via_route_hotspots[] = $hotspot_details;
                                                            endif; */

                                                            $via_route_hotspots = []; // initialize before loop
                                                            $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                            if ($matchIndex !== false) {
                                                                // Group hotspots by VIA index
                                                                $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                            }

                                                        endwhile;

                                                        // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                        ksort($via_route_hotspots);

                                                        // Flatten grouped hotspots into a single ordered array
                                                        $ordered_hotspots = [];
                                                        foreach ($via_route_hotspots as $group) {
                                                            foreach ($group as $h) {
                                                                $ordered_hotspots[] = $h;
                                                            }
                                                        }

                                                        // Now use $ordered_hotspots instead of $via_route_hotspots
                                                        $via_route_hotspots = $ordered_hotspots;

                                                        sortHotspots($source_location_hotspots);
                                                        sortHotspots($via_route_hotspots);
                                                        sortHotspots($destination_hotspots);

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                        $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                        endif;
                                                        
                                                        // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                        $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                        $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                        endif;

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                        $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                        endif;

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                        $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                        endif;

                                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                            // Process via route hotspots
                                                            $processed_via_route_hotspots = false;
                                                            if(!empty($via_route_hotspots)):
                                                                foreach ($via_route_hotspots as $hotspot) :
                                                                    $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                    if($check_via_route_hotspot_added):
                                                                    $processed_via_route_hotspots = true;
                                                                    endif;
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                endforeach;
                                                            endif;

                                                            if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                                // Execute the query to fetch via route IDs
                                                                $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                                // Fetch the results
                                                                if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                    while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                        $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                        $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                        $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                        $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                        $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                        $via_route_traveling_time = $result['duration'];

                                                                        // **EXTRACT AND FORMAT TIME DETAILS**
                                                                        preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                        preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                        $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                        $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                        // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                        $extraHours = floor($minutes / 60);
                                                                        $hours += $extraHours;
                                                                        $minutes %= 60;

                                                                        $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                        // **CALCULATE THE DURATION IN SECONDS**
                                                                        $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                        // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                        $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                        $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                        $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                        $hotspot_order++;
                                                                        $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                        
                                                                        $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                        // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                            $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                            $staring_location_latitude = $via_route_location_lattitude;
                                                                            $staring_location_longtitude = $via_route_location_longitude;
                                                                            $previous_hotspot_location = $via_route_location;
                                                                        endif;
                                                                    endwhile;
                                                                endif;
                                                            endif;

                                                            // Process destination hotspots
                                                            if (!empty($destination_hotspots)) :
                                                                foreach ($destination_hotspots as $hotspot) :
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                    includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                endforeach;
                                                            endif;
                                                        else:
                                                            // Process source location hotspots
                                                            if (!empty($source_location_hotspots)) :
                                                                foreach ($source_location_hotspots as $hotspot) :
                                                                    includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                endforeach;
                                                            endif;

                                                            // Process via route hotspots
                                                            $processed_via_route_hotspots = false;
                                                            if (!empty($via_route_hotspots)) :
                                                                foreach ($via_route_hotspots as $hotspot) :
                                                                    $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                    if($check_via_route_hotspot_added):
                                                                    $processed_via_route_hotspots = true;
                                                                    endif;
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                endforeach;
                                                            endif;

                                                            if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                                // Execute the query to fetch via route IDs
                                                                $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                                // Fetch the results
                                                                if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                    while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                        $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                        $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                        $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                        $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                        $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                        $via_route_traveling_time = $result['duration'];

                                                                        // **EXTRACT AND FORMAT TIME DETAILS**
                                                                        preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                        preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                        $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                        $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                        // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                        $extraHours = floor($minutes / 60);
                                                                        $hours += $extraHours;
                                                                        $minutes %= 60;

                                                                        $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                        // **CALCULATE THE DURATION IN SECONDS**
                                                                        $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                        // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                        $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                        $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                        $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                        $hotspot_order++;
                                                                        $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                        
                                                                        $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                        // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                            $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                            $staring_location_latitude = $via_route_location_lattitude;
                                                                            $staring_location_longtitude = $via_route_location_longitude;
                                                                            $previous_hotspot_location = $via_route_location;
                                                                        endif;
                                                                    endwhile;
                                                                endif;
                                                            endif;

                                                            if (!empty($destination_hotspots)) :
                                                            // Process destination hotspots
                                                                foreach ($destination_hotspots as $hotspot) :
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                    includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                endforeach;
                                                            endif;
                                                        endif;
                                                    endif;
                                                    
                                                    $last_hotspot_location = $last_hotspot_details['last_hotspot_location'];
                                                    $last_hotspot_latitude = $last_hotspot_details['last_hotspot_latitude'];
                                                    $last_hotspot_longitude = $last_hotspot_details['last_hotspot_longitude'];
                                                    $hotspot_siteseeing_travel_start_time = $last_hotspot_details['last_hotspot_end_time'];

                                                    // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                    $travel_location_type = getTravelLocationType($last_hotspot_location, $ending_location_name);
                                                    $result = calculateDistanceAndDuration($last_hotspot_latitude, $last_hotspot_longitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                    $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                    $destination_traveling_time = $result['duration'];

                                                    // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                    preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                    preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                                                    // INITIALIZE HOURS AND MINUTES TO ZERO
                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                    // CALCULATE TOTAL DURATION IN SECONDS (hours and minutes combined)
                                                    $totalDurationInSeconds = ($hours * 3600) + ($minutes * 60);

                                                    // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59 (if needed)
                                                    $extraHours = floor($minutes / 60);
                                                    $hours += $extraHours;
                                                    $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                                                    // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                                                    $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                                                    $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                                                    // FORMAT THE TOTAL DURATION AS H:i:s (destination_total_duration)
                                                    $destination_total_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                    // CONVERT hotspot_siteseeing_travel_start_time TO SECONDS
                                                    $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                    // ADD THE TOTAL DURATION TO THE START TIME (in seconds)
                                                    $totalTimeInSeconds = $startTimeInSeconds + $totalDurationInSeconds;

                                                    // CONVERT THE TOTAL TIME BACK TO H:i:s FORMAT (destination_travel_end_time)
                                                    $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                                    $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                    $hotspot_order++;
                                                    $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                    $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$destination_total_duration", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                                    if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                        $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                        endif;
                                                    else :
                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                        endif;
                                                    endif;
                                                else :

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                    $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                    endif;
                                                    
                                                    // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                    $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                    $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                    $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                    $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                    endif;

                                                    $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                                    // Convert route end time to timestamp
                                                    $route_end_timestamp = strtotime($route_end_time);

                                                    // Convert total traveling time to seconds
                                                    list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                                    $travelling_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                    // Subtract the total traveling time from the route end time
                                                    $adjusted_route_start_timestamp = $route_end_timestamp - $travelling_seconds;

                                                    // Convert the adjusted time back to the desired format
                                                    $adjusted_route_start_time = date('H:i:s', $adjusted_route_start_timestamp);

                                                    $itinerary_route_details_arrFields = array('`route_start_time`', '`route_end_time`');
                                                    $itinerary_route_details_arrValues = array("$adjusted_route_start_time", "$route_end_time");
                                                    $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";

                                                    //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :

                                                    endif;

                                                    $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                    $hotspot_order++;
                                                    $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                    $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$total_travelling_time", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$adjusted_route_start_time", "$route_end_time", "$logged_user_id", "1");

                                                    if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                        $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                        endif;
                                                    else :
                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                        endif;
                                                    endif;
                                                
                                                endif;
                                            else :

                                                $hotspot_order = $hotspot_order;

                                                $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                // Determine the travel location type
                                                $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                                // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                $destination_traveling_time = $result['duration'];

                                                // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                                $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                // Convert hotspot_siteseeing_travel_start_time to seconds
                                                $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                // Convert duration_formatted to seconds
                                                list($hours, $minutes, $seconds) = sscanf($duration_formatted, "%d:%d:%d");
                                                $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                // Add the duration and buffer time to the start time
                                                $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds;

                                                // Convert the total time back to H:i:s format
                                                $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                                $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '5'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                $hotspot_order++;
                                                $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "5", "$hotspot_order", "$duration_formatted", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                                if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '5' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                    endif;
                                                endif;

                                                $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                                                $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_return_to_hotel_location_arrValues = array(
                                                    "$itinerary_plan_ID", "$itinerary_route_ID", "6", "$hotspot_order", "$destination_travel_end_time", "$destination_travel_end_time", "$logged_user_id", "1"
                                                );

                                                if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                                                    $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                                                    $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                                                    endif;
                                                endif;
                                            endif;
                                        else :
                                            $delete_route_hotspot_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_route_hotspot_details_sqlwhere)) :
                                            endif;
                                            $response['route_end_time_reached'] = true;
                                        endif;
                                    endif;

                                    $response['i_result'] = true;
                                    $response['redirect_URL'] = 'latestitinerary.php?route=edit&formtype=generate_itinerary&id=' . $itinerary_plan_ID.'&selected_group_type=1';
                                    $response['itinerary_plan_ID'] = $itinerary_plan_ID;
                                    $response['result_success'] = true;
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            else :
                                while ($fetch_row = sqlFETCHARRAY_LABEL($check_itineary_route_details_avilability)) :
                                    $itinerary_route_ID = $fetch_row['itinerary_route_ID'];
                                endwhile;

                                $route_sqlwhere = " `itinerary_route_ID` = '$itinerary_route_ID' ";

                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $route_arrFields, $route_arrValues, $route_sqlwhere)) :

                                    $update_via_route_details = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_via_route_details` WHERE `itinerary_route_ID` = '$hidden_itinerary_route_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_date` = '$itinerary_route_date' AND `source_location` != '$selected_SOURCE_LOCATION' AND `destination_location` != '$selected_NEXT_VISITING_PLACE' AND `deleted` = '0'") or die("#1_UNABLE_TO_UPDATE_VIA_ROUTE_DATA:" . sqlERROR_LABEL());

                                    $itinerary_common_buffer_time = getGLOBALSETTING('itinerary_common_buffer_time');
                                    $hotspot_start_time = $route_start_time;

                                    // Convert time strings to seconds
                                    $start_seconds = strtotime($hotspot_start_time);
                                    $buffer_seconds = strtotime($itinerary_common_buffer_time) - strtotime('00:00:00');

                                    // Add the buffer time to the start time
                                    $total_seconds = $start_seconds + $buffer_seconds;

                                    // Convert the total seconds back to the time format
                                    $hotspot_end_time = date('H:i:s', $total_seconds);

                                    if($itinerary_prefrence != 1):
                                        if (($hotspot_end_time <= $route_end_time && $trip_last_day == false) ||($trip_last_day == true)) :

                                            $select_itineary_hotspot_refresh_time_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '1'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_refresh_buffer_time_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_refresh_time_data);

                                            //INSERT HOTSPOT REFRESH TIME
                                            $route_hotspot_refresh_time_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_refresh_time_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "1", "1", "$itinerary_common_buffer_time", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

                                            if ($trip_last_day == false) :
                                                //CHECK HOTSPOT REFRESH TIME RECORD AVAILABILITY
                                                if ($select_itineary_hotspot_refresh_buffer_time_count > 0) :
                                                    $fetch_itineary_hotspot_refresh_time_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_refresh_time_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_refresh_time_data['route_hotspot_ID'];

                                                    $route_hotspot_refresh_time_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '1' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, $route_hotspot_refresh_time_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, '')) :
                                                    endif;
                                                endif;
                                            endif;

                                            $start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');

                                            $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                            //ROUTE LOCATION SOURCE NAME
                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                                $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                            else :
                                                $filter_location_name = '';
                                            endif;

                                            //NEXT VISITING PLACE LOCATION NAME
                                            $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                            $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                            if ($get_via_route_IDs): 
                                                if ($get_via_route_IDs): 
                                                    $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                                else: 
                                                    $get_via_route_location_IDs = NULL;
                                                endif;

                                                // VIA ROUTE LOCATION NAME
                                                $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                                if ($via_route_name): 
                                                    // Ensure that $via_route_name is an array
                                                    if (is_array($via_route_name)): 
                                                        $via_route_conditions = array_map(function($location) {
                                                            // Use LIKE for pipe-separated values
                                                            return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                        }, $via_route_name);

                                                        // Join conditions with ' OR '
                                                        $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                    else: 
                                                        $add_filter_via_route_location = '';
                                                    endif;
                                                else: 
                                                    $add_filter_via_route_location = '';
                                                endif;
                                            else: 
                                                $via_route_name = '';
                                                $add_filter_via_route_location = '';
                                            endif;
                                            
                                            //CHECK DIRECT DESTINATION TRAVEL
                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                //INSERT HOTSPOT DIRECT DESTINATION TRAVEL

                                                if(empty($via_route_name)):
                                                    $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                                    $direct_destination_travel_start_time = $hotspot_end_time;

                                                    $travel_distance = calculateTravelDistanceAndTime($start_location_id);
                                                    $_distance = $travel_distance['distance'];
                                                    $_time = $travel_distance['duration'];

                                                    // Extract hours and minutes from the duration string
                                                    preg_match('/(\d+) hour/', $_time, $hours_match);
                                                    preg_match('/(\d+) min/', $_time, $minutes_match);

                                                    $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                                                    $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                                                    // Format the time as H:i:s
                                                    $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                                                    // Convert times to seconds
                                                    $seconds1 = strtotime("1970-01-01 $direct_destination_travel_start_time UTC");
                                                    $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

                                                    $direct_destination_travel_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

                                                    $route_hotspot_direct_destination_visit_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                    $route_hotspot_direct_destination_visit_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "2", "2", "$formatted_time", "$_distance", "$direct_destination_travel_start_time", "$direct_destination_travel_end_time", "$logged_user_id", "1");

                                                    if ($select_itineary_hotspot_direct_destination_count > 0) :
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                        $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, $route_hotspot_direct_destination_visit_sqlwhere)) :
                                                        endif;
                                                    else :
                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, '')) :
                                                        endif;
                                                    endif;

                                                    $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                    $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                    $hotspot_order = 2;
                                                else:
                                                    $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);
                                                    if($select_itineary_hotspot_direct_destination_count > 0):
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];
                                                        $sqlWhere = " `route_hotspot_ID` = '$route_hotspot_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $sqlWhere)) :
                                                        endif;
                                                    endif;
                                                    $hotspot_order = 1;
                                                    $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                                    $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                                    $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');
                                                endif;
                                            else :

                                                $hotspot_order = 1;
                                                $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                                $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                                $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');

                                                //DELETE THE DIRECT DESTINATION VISIT RECORD
                                                $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                                if ($select_itineary_hotspot_direct_destination_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $route_hotspot_direct_destination_visit_sqlwhere)) :
                                                    endif;
                                                endif;
                                            endif;

                                            if ($trip_last_day == false) :
                                                //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                                // Convert the date string to a Unix timestamp using strtotime
                                                $timestamp = strtotime($hidden_itinerary_route_date);

                                                if ($timestamp !== false) :
                                                    // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                    $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                                endif;

                                                //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                                #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                                $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                                $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                                // Initialize variables for categorization
                                                $source_location_hotspots = [];
                                                $via_route_hotspots = [];
                                                $destination_hotspots = [];

                                                if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                    if(empty($via_route_name)):
                                                        $previous_hotspot_location = $location_name;
                                                    else:
                                                        $previous_hotspot_location = $location_name;
                                                    endif;
                                                else:
                                                // Initialize variables for the starting location
                                                    $previous_hotspot_location = $location_name;
                                                endif;

                                                if ($select_hotspot_details_num_rows_count > 0): 
                                                    while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                        // Proceed with adding the hotspot to the itinerary for the current day
                                                        $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                        $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                        $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                        $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                        $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                        $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                        $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                        $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                        $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                        // Determine the travel location type
                                                        $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                        // Calculate the distance and duration from the starting location
                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                        $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                        // Categorize hotspots based on location type
                                                        $hotspot_details = [
                                                            'hotspot_ID' => $hotspot_ID,
                                                            'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                            'hotspot_name' => $hotspot_name,
                                                            'hotspot_duration' => $hotspot_duration,
                                                            'hotspot_latitude' => $hotspot_latitude,
                                                            'hotspot_longitude' => $hotspot_longitude,
                                                            'hotspot_distance' => $get_hotspot_travelling_distance,
                                                            'hotspot_location' => $hotspot_location,
                                                            'hotspot_priority' => $hotspot_priority,
                                                            'previous_hotspot_location' => $previous_hotspot_location
                                                        ];

                                                        $source_match = containsLocation($hotspot_location, $location_name);
                                                        $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                        if ($source_match) :
                                                            $source_location_hotspots[] = $hotspot_details;
                                                        endif;

                                                        if ($destination_match) :
                                                            $destination_hotspots[] = $hotspot_details;
                                                        endif;

                                                        /* if (!$source_match && !$destination_match) :
                                                            $via_route_hotspots[] = $hotspot_details;
                                                        endif; */

                                                        $via_route_hotspots = []; // initialize before loop
                                                        $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                        if ($matchIndex !== false) {
                                                            // Group hotspots by VIA index
                                                            $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                        }

                                                    endwhile;

                                                    // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                    ksort($via_route_hotspots);

                                                    // Flatten grouped hotspots into a single ordered array
                                                    $ordered_hotspots = [];
                                                    foreach ($via_route_hotspots as $group) {
                                                        foreach ($group as $h) {
                                                            $ordered_hotspots[] = $h;
                                                        }
                                                    }

                                                    // Now use $ordered_hotspots instead of $via_route_hotspots
                                                    $via_route_hotspots = $ordered_hotspots;

                                                    sortHotspots($source_location_hotspots);
                                                    sortHotspots($via_route_hotspots);
                                                    sortHotspots($destination_hotspots);

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                    $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                    $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                    $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                    $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                    $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                    endif;

                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                        // Process via route hotspots
                                                        $processed_via_route_hotspots = false;
                                                        if(!empty($via_route_hotspots)):
                                                            foreach ($via_route_hotspots as $hotspot) :
                                                                $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($check_via_route_hotspot_added):
                                                                $processed_via_route_hotspots = true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                            // Execute the query to fetch via route IDs
                                                            $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                            // Fetch the results
                                                            if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                    $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                    $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                    $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                    $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                    $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                    $via_route_traveling_time = $result['duration'];

                                                                    // **EXTRACT AND FORMAT TIME DETAILS**
                                                                    preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                    preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                    // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                    $extraHours = floor($minutes / 60);
                                                                    $hours += $extraHours;
                                                                    $minutes %= 60;

                                                                    $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                    // **CALCULATE THE DURATION IN SECONDS**
                                                                    $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                    // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                    $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                    $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                    $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                    $hotspot_order++;
                                                                    $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                    
                                                                    $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                    // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                        $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                        $staring_location_latitude = $via_route_location_lattitude;
                                                                        $staring_location_longtitude = $via_route_location_longitude;
                                                                        $previous_hotspot_location = $via_route_location;
                                                                    endif;
                                                                endwhile;
                                                            endif;
                                                        endif;
                                                        
                                                        // Process destination hotspots
                                                        if (!empty($destination_hotspots)) :
                                                            foreach ($destination_hotspots as $hotspot) :
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                                includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            endforeach;
                                                        endif;
                                                    else:
                                                        // Process source location hotspots
                                                        if (!empty($source_location_hotspots)) :
                                                            foreach ($source_location_hotspots as $hotspot) :
                                                                includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        // Process via route hotspots
                                                        $processed_via_route_hotspots = false;
                                                        if (!empty($via_route_hotspots)) :
                                                            foreach ($via_route_hotspots as $hotspot) :
                                                                $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                if($check_via_route_hotspot_added):
                                                                $processed_via_route_hotspots = true;
                                                                endif;
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                            endforeach;
                                                        endif;

                                                        if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                            // Execute the query to fetch via route IDs
                                                            $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                            // Fetch the results
                                                            if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                    $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                    $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                    $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                    $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                    $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                    $via_route_traveling_time = $result['duration'];

                                                                    // **EXTRACT AND FORMAT TIME DETAILS**
                                                                    preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                    preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                    // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                    $extraHours = floor($minutes / 60);
                                                                    $hours += $extraHours;
                                                                    $minutes %= 60;

                                                                    $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                    // **CALCULATE THE DURATION IN SECONDS**
                                                                    $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                    // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                    $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                    $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                    $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                    $hotspot_order++;
                                                                    $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                    
                                                                    $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                    // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                        $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                        $staring_location_latitude = $via_route_location_lattitude;
                                                                        $staring_location_longtitude = $via_route_location_longitude;
                                                                        $previous_hotspot_location = $via_route_location;
                                                                    endif;
                                                                endwhile;
                                                            endif;
                                                        endif;
                                                        
                                                        if (!empty($destination_hotspots)) :
                                                        // Process destination hotspots
                                                            foreach ($destination_hotspots as $hotspot) :
                                                                if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                    break; // Stop processing if past the cutoff time
                                                                endif;
                                                                includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            endforeach;
                                                        endif;
                                                    endif;
                                                endif;
                                            endif;

                                            $get_last_route_id_from_this_itinerary_plan = sqlQUERY_LABEL("SELECT MAX(`itinerary_route_ID`) max_route_id FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                            $fetch_last_route_id_from_this_itineary_plan = sqlFETCHARRAY_LABEL($get_last_route_id_from_this_itinerary_plan);
                                            $last_itinerary_route_ID = $fetch_last_route_id_from_this_itineary_plan['max_route_id'];

                                            //INSERT THE END OF THE TRIP DEPARTURE START TIME
                                            if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $last_itinerary_route_ID == $itinerary_route_ID && $trip_last_day == true) :
                                                $hotspot_order = $hotspot_order;

                                                //Determine the buffer time based on the departure_type [1 - By Flight | 2 - By Train | 3 - By Road]
                                                switch ($departure_type):
                                                        case 1: // By Flight
                                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                                        break;
                                                    case 2: // By Train
                                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                                        break;
                                                    case 3: // By Road
                                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                                        break;
                                                    default:
                                                        $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                                                endswitch;

                                                $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                // Determine the travel location type
                                                $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                                // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                $destination_traveling_time = $result['duration'];

                                                // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                                $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                //CALCAULATE THE DURATIONS IN SECONDS
                                                $totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                //ADD THE DURATION TO THE START TIME
                                                $newTimeInSeconds = $totalSeconds + strtotime($itinerary_travel_type_buffer_time);

                                                //CONVERT THE NEW TIME TO {destination_total_duration} H:i:s FORMAT
                                                $destination_total_duration = date('H:i:s', $newTimeInSeconds);

                                                // Convert hotspot_siteseeing_travel_start_time to seconds
                                                $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                // Convert destination_total_duration to seconds
                                                list($hours, $minutes, $seconds) = sscanf($destination_total_duration, "%d:%d:%d");
                                                $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                // Add the duration and buffer time to the start time
                                                $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds + $bufferInSeconds;

                                                // Convert the total time back to H:i:s format
                                                $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                                if ($destination_travel_end_time <= $route_end_time) :

                                                    // Format total traveling time
                                                    $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                                    // Format buffer time and convert to seconds
                                                    $itinerary_travel_type_buffer_time_formatted = date('H:i:s', strtotime($itinerary_travel_type_buffer_time));
                                                    list($buffer_hours, $buffer_minutes, $buffer_seconds) = explode(':', $itinerary_travel_type_buffer_time_formatted);
                                                    $itinerary_travel_buffer_seconds = ($buffer_hours * 3600) + ($buffer_minutes * 60) + $buffer_seconds;

                                                    // Convert route end time to timestamp
                                                    $route_end_timestamp = strtotime($route_end_time);

                                                    // Convert total traveling time to seconds
                                                    list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                                    $travelling_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                                                    // Subtract the total traveling time and buffer time from the route end time
                                                    $adjusted_route_start_timestamp = $route_end_timestamp - ($travelling_seconds + $itinerary_travel_buffer_seconds);

                                                    // Convert the adjusted time back to the desired format
                                                    $adjusted_route_hotspot_end_time = date('H:i:s', $adjusted_route_start_timestamp);

                                                    //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                                    // Convert the date string to a Unix timestamp using strtotime
                                                    $timestamp = strtotime($hidden_itinerary_route_date);

                                                    if ($timestamp !== false) :
                                                        // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                        $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                                    endif;
                                                
                                                    $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                                    //ROUTE LOCATION SOURCE NAME
                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                                        $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                                    else :
                                                        $filter_location_name = '';
                                                    endif;

                                                    //NEXT VISITING PLACE LOCATION NAME
                                                    $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                    $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                                    if ($get_via_route_IDs): 
                                                        
                                                        if ($get_via_route_IDs): 
                                                            $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                                        else: 
                                                            $get_via_route_location_IDs = NULL;
                                                        endif;

                                                        // VIA ROUTE LOCATION NAME
                                                        $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                                        if ($via_route_name): 
                                                            // Ensure that $via_route_name is an array
                                                            if (is_array($via_route_name)): 
                                                                $via_route_conditions = array_map(function($location) {
                                                                    // Use LIKE for pipe-separated values
                                                                    return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                                }, $via_route_name);

                                                                // Join conditions with ' OR '
                                                                $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                            else: 
                                                                $add_filter_via_route_location = '';
                                                            endif;
                                                        else: 
                                                            $add_filter_via_route_location = '';
                                                        endif;
                                                    else: 
                                                        $via_route_name = '';
                                                        $add_filter_via_route_location = '';
                                                    endif;

                                                    //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                                    #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                                    $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                                    $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                                    // Initialize variables for categorization
                                                    $source_location_hotspots = [];
                                                    $via_route_hotspots = [];
                                                    $destination_hotspots = [];

                                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                        if(empty($via_route_name)):
                                                            $previous_hotspot_location = $location_name;
                                                        else:
                                                            $previous_hotspot_location = $location_name;
                                                        endif;
                                                    else:
                                                    // Initialize variables for the starting location
                                                        $previous_hotspot_location = $location_name;
                                                    endif;

                                                    if ($select_hotspot_details_num_rows_count > 0): 
                                                        while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                            // Proceed with adding the hotspot to the itinerary for the current day
                                                            $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                            $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                            $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                            $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                            $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                            $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                            $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                            $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                            $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                            // Determine the travel location type
                                                            $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                            // Calculate the distance and duration from the starting location
                                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                            $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                            // Categorize hotspots based on location type
                                                            $hotspot_details = [
                                                                'hotspot_ID' => $hotspot_ID,
                                                                'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                                'hotspot_name' => $hotspot_name,
                                                                'hotspot_duration' => $hotspot_duration,
                                                                'hotspot_latitude' => $hotspot_latitude,
                                                                'hotspot_longitude' => $hotspot_longitude,
                                                                'hotspot_distance' => $get_hotspot_travelling_distance,
                                                                'hotspot_location' => $hotspot_location,
                                                                'hotspot_priority' => $hotspot_priority,
                                                                'previous_hotspot_location' => $previous_hotspot_location
                                                            ];

                                                            $source_match = containsLocation($hotspot_location, $location_name);
                                                            $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                            if ($source_match) :
                                                                $source_location_hotspots[] = $hotspot_details;
                                                            endif;

                                                            if ($destination_match) :
                                                                $destination_hotspots[] = $hotspot_details;
                                                            endif;

                                                            /* if (!$source_match && !$destination_match) :
                                                                $via_route_hotspots[] = $hotspot_details;
                                                            endif; */
                                                            
                                                            $via_route_hotspots = []; // initialize before loop
                                                            $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                            if ($matchIndex !== false) {
                                                                // Group hotspots by VIA index
                                                                $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                            }

                                                        endwhile;

                                                        // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                        ksort($via_route_hotspots);

                                                        // Flatten grouped hotspots into a single ordered array
                                                        $ordered_hotspots = [];
                                                        foreach ($via_route_hotspots as $group) {
                                                            foreach ($group as $h) {
                                                                $ordered_hotspots[] = $h;
                                                            }
                                                        }

                                                        // Now use $ordered_hotspots instead of $via_route_hotspots
                                                        $via_route_hotspots = $ordered_hotspots;

                                                        sortHotspots($source_location_hotspots);
                                                        sortHotspots($via_route_hotspots);
                                                        sortHotspots($destination_hotspots);

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                        $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                        endif;

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                        $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                        $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                        endif;

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                        $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                        endif;

                                                        // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                        $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                        endif;

                                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                            // Process via route hotspots
                                                            $processed_via_route_hotspots = false;
                                                            if(!empty($via_route_hotspots)):
                                                                foreach ($via_route_hotspots as $hotspot) :
                                                                    $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                    if($check_via_route_hotspot_added):
                                                                    $processed_via_route_hotspots = true;
                                                                    endif;
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                endforeach;
                                                            endif;

                                                            if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                                // Execute the query to fetch via route IDs
                                                                $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                                // Fetch the results
                                                                if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                    while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                        $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                        $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                        $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                        $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                        $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                        $via_route_traveling_time = $result['duration'];

                                                                        // **EXTRACT AND FORMAT TIME DETAILS**
                                                                        preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                        preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                        $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                        $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                        // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                        $extraHours = floor($minutes / 60);
                                                                        $hours += $extraHours;
                                                                        $minutes %= 60;

                                                                        $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                        // **CALCULATE THE DURATION IN SECONDS**
                                                                        $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                        // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                        $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                        $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                        $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                        $hotspot_order++;
                                                                        $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                        
                                                                        $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                        // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                            $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                            $staring_location_latitude = $via_route_location_lattitude;
                                                                            $staring_location_longtitude = $via_route_location_longitude;
                                                                            $previous_hotspot_location = $via_route_location;
                                                                        endif;
                                                                    endwhile;
                                                                endif;
                                                            endif;

                                                            // Process destination hotspots
                                                            if (!empty($destination_hotspots)) :
                                                                foreach ($destination_hotspots as $hotspot) :
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                    includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                endforeach;
                                                            endif;
                                                        else:
                                                            // Process source location hotspots
                                                            if (!empty($source_location_hotspots)) :
                                                                foreach ($source_location_hotspots as $hotspot) :
                                                                    includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                endforeach;
                                                            endif;

                                                            // Process via route hotspots
                                                            $processed_via_route_hotspots = false;
                                                            if (!empty($via_route_hotspots)) :
                                                                foreach ($via_route_hotspots as $hotspot) :
                                                                    $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                    if($check_via_route_hotspot_added):
                                                                    $processed_via_route_hotspots = true;
                                                                    endif;
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                endforeach;
                                                            endif;
                                                            
                                                            if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                                // Execute the query to fetch via route IDs
                                                                $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                                // Fetch the results
                                                                if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                                    while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                        $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                        $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                        $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                        $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                        $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                        $via_route_traveling_time = $result['duration'];

                                                                        // **EXTRACT AND FORMAT TIME DETAILS**
                                                                        preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                        preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                        $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                        $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                        // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                        $extraHours = floor($minutes / 60);
                                                                        $hours += $extraHours;
                                                                        $minutes %= 60;

                                                                        $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                        // **CALCULATE THE DURATION IN SECONDS**
                                                                        $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                        // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                        $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                        $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                        $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                        $hotspot_order++;
                                                                        $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                        
                                                                        $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                        // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                            $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                            $staring_location_latitude = $via_route_location_lattitude;
                                                                            $staring_location_longtitude = $via_route_location_longitude;
                                                                            $previous_hotspot_location = $via_route_location;
                                                                        endif;
                                                                    endwhile;
                                                                endif;
                                                            endif;

                                                            if (!empty($destination_hotspots)) :
                                                            // Process destination hotspots
                                                                foreach ($destination_hotspots as $hotspot) :
                                                                    if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                        break; // Stop processing if past the cutoff time
                                                                    endif;
                                                                    includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                                endforeach;
                                                            endif;
                                                        endif;
                                                    endif;
                                                    
                                                    $last_hotspot_location = $last_hotspot_details['last_hotspot_location'];
                                                    $last_hotspot_latitude = $last_hotspot_details['last_hotspot_latitude'];
                                                    $last_hotspot_longitude = $last_hotspot_details['last_hotspot_longitude'];
                                                    $hotspot_siteseeing_travel_start_time = $last_hotspot_details['last_hotspot_end_time'];

                                                    // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                    $travel_location_type = getTravelLocationType($last_hotspot_location, $ending_location_name);
                                                    $result = calculateDistanceAndDuration($last_hotspot_latitude, $last_hotspot_longitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                    $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                    $destination_traveling_time = $result['duration'];

                                                    // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                    preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                    preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                                                    // INITIALIZE HOURS AND MINUTES TO ZERO
                                                    $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                    $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                    // CALCULATE TOTAL DURATION IN SECONDS (hours and minutes combined)
                                                    $totalDurationInSeconds = ($hours * 3600) + ($minutes * 60);

                                                    // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59 (if needed)
                                                    $extraHours = floor($minutes / 60);
                                                    $hours += $extraHours;
                                                    $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                                                    // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                                                    $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                                                    $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                                                    // FORMAT THE TOTAL DURATION AS H:i:s (destination_total_duration)
                                                    $destination_total_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                    // CONVERT hotspot_siteseeing_travel_start_time TO SECONDS
                                                    $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                    // ADD THE TOTAL DURATION TO THE START TIME (in seconds)
                                                    $totalTimeInSeconds = $startTimeInSeconds + $totalDurationInSeconds;

                                                    // CONVERT THE TOTAL TIME BACK TO H:i:s FORMAT (destination_travel_end_time)
                                                    $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                                    $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                    $hotspot_order++;
                                                    $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                    $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$destination_total_duration", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                                    if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                        $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                        endif;
                                                    else :
                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                        endif;
                                                    endif;
                                                else :

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                    $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                    $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                    $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                    $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                    endif;

                                                    // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                    $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                    endif;

                                                    $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                                    // Convert route end time to timestamp
                                                    $route_end_timestamp = strtotime($route_end_time);

                                                    // Convert total traveling time to seconds
                                                    list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                                    $travelling_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                    // Subtract the total traveling time from the route end time
                                                    $adjusted_route_start_timestamp = $route_end_timestamp - $travelling_seconds;

                                                    // Convert the adjusted time back to the desired format
                                                    $adjusted_route_start_time = date('H:i:s', $adjusted_route_start_timestamp);

                                                    $itinerary_route_details_arrFields = array('`route_start_time`', '`route_end_time`');
                                                    $itinerary_route_details_arrValues = array("$adjusted_route_start_time", "$route_end_time");
                                                    $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";

                                                    //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :

                                                    endif;

                                                    $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                    $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                    $hotspot_order++;
                                                    $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                    $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$total_travelling_time", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$adjusted_route_start_time", "$route_end_time", "$logged_user_id", "1");

                                                    if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                        $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                        $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                        $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                        if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                        endif;
                                                    else :
                                                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                        endif;
                                                    endif;
                                                endif;
                                            else :

                                                $hotspot_order = $hotspot_order;
                                                $itinerary_travel_type_buffer_time = "00:00:00";

                                                $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                                $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                                $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                                // Determine the travel location type
                                                $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                                // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                                $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                $destination_traveling_time = $result['duration'];

                                                // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                                preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                                preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                                $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                                // Convert hotspot_siteseeing_travel_start_time to seconds
                                                $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                                // Convert duration_formatted to seconds
                                                list($hours, $minutes, $seconds) = sscanf($duration_formatted, "%d:%d:%d");
                                                $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                                // Add the duration and buffer time to the start time
                                                $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds;

                                                // Convert the total time back to H:i:s format
                                                $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                                $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '5'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                                $hotspot_order++;
                                                $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "5", "$hotspot_order", "$duration_formatted", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                                if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                    $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                    $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '5' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                    endif;
                                                endif;

                                                $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                                $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                                                $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                                $route_hotspot_return_to_hotel_location_arrValues = array(
                                                    "$itinerary_plan_ID", "$itinerary_route_ID", "6", "$hotspot_order", "$destination_travel_end_time", "$destination_travel_end_time", "$logged_user_id", "1"
                                                );

                                                if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                                                    $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                                                    $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                                                    $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                                                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                                                    endif;
                                                else :
                                                    if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                                                    endif;
                                                endif;
                                            endif;
                                        else :
                                            $delete_route_hotspot_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_route_hotspot_details_sqlwhere)) :
                                            endif;
                                            $response['route_end_time_reached'] = true;
                                        endif;
                                    endif;

                                    $response['i_result'] = true;
                                    $response['redirect_URL'] = 'latestitinerary.php?route=edit&formtype=generate_itinerary&id=' . $itinerary_plan_ID.'&selected_group_type=1';
                                    $response['itinerary_plan_ID'] = $itinerary_plan_ID;
                                    $response['result_success'] = true;
                                else :
                                    $response['i_result'] = false;
                                    $response['result_success'] = false;
                                endif;
                            endif;
                        endif;
                    endforeach;
                endif;

            else :

                // INSERT ITINEARY PLAN DETAILS
                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_details", $arrFields, $arrValues, '')) :
                    $itinerary_plan_ID = sqlINSERTID_LABEL();

                    if (isset($vehicle_type) && isset($vehicle_count) && in_array($itinerary_prefrence, [2, 3])) :
                        foreach ($vehicle_type as $key => $val) :
                            $selected_VEHICLE_TYPE = $_POST['vehicle_type'][$key];
                            $selected_VEHICLE_COUNT = $_POST['vehicle_count'][$key];
                            $selected_vehicle_details_ID = $_POST['hidden_vehicle_details_ID'][$key];

                            $vehicle_arrFields = [
                                '`itinerary_plan_ID`', '`vehicle_type_id`', '`vehicle_count`',
                                '`createdby`', '`status`'
                            ];
                            $vehicle_arrValues = [
                                "$itinerary_plan_ID", "$selected_VEHICLE_TYPE", "$selected_VEHICLE_COUNT",
                                "$logged_user_id", "1"
                            ];

                            if ($selected_vehicle_details_ID) :
                                $vehicle_sqlwhere = " `vehicle_details_ID` = '$selected_vehicle_details_ID' ";
                                sqlACTIONS("UPDATE", "dvi_itinerary_plan_vehicle_details", $vehicle_arrFields, $vehicle_arrValues, $vehicle_sqlwhere);
                            else :
                                sqlACTIONS("INSERT", "dvi_itinerary_plan_vehicle_details", $vehicle_arrFields, $vehicle_arrValues, '');
                            endif;
                        endforeach;
                    endif;

                    // Process traveller details
                    $itinerary_adult = $_POST['itinerary_adult'];
                    $itinerary_children = $_POST['itinerary_children'];
                    $itinerary_infants = $_POST['itinerary_infants'];
                    $children_age = $_POST['children_age'];
                    $child_bed_type = $_POST['child_bed_type'];

                    if (isset($_POST['total_room_count'])) :
                        for ($i = 0; $i < count($_POST['total_room_count']); $i++) :
                            $room_id = $i + 1; // Assuming room IDs start from 1

                            //INSERT ADULT TRAVELLERS
                            for ($j = 0; $j < $itinerary_adult[$i]; $j++) :
                                $traveller_type = 1; // Adult

                                $adult_travelers_arrFields = array('`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`createdby`', '`status`');
                                $adult_travelers_arrValues = array("$itinerary_plan_ID", "$traveller_type", "$room_id", "$logged_user_id", "1");

                                $select_itinerary_adult_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                $total_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_adult_traveller_details_query);

                                $diff_adult_count = $itinerary_adult[$i] - $total_num_rows_count;

                                if ($diff_adult_count < 0) :
                                    $absoluteValue = abs($diff_adult_count);
                                    // Query to get total adult count for the room from the database
                                    $select_total_adult_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ORDER BY `traveller_details_ID` ASC LIMIT $absoluteValue") or die(sqlERROR_LABEL());
                                    while ($row = sqlFETCHARRAY_LABEL($select_total_adult_query)) :
                                        $traveller_details_ID = $row['traveller_details_ID'];
                                        $delete_adult_travelers_sqlwhere = " `traveller_details_ID` = '$traveller_details_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $delete_adult_travelers_sqlwhere)) :
                                        endif;
                                    endwhile;
                                endif;

                                //UPDATE ADULT TRAVELLER DETAILS
                                $adult_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_traveller_details", $adult_travelers_arrFields, $adult_travelers_arrValues, $adult_travelers_sqlwhere)) :
                                endif;

                                if ($diff_adult_count > 0) :
                                    //INSERT ADULT TRAVELLER DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $adult_travelers_arrFields, $adult_travelers_arrValues, '')) :
                                    endif;
                                endif;
                            endfor;

                            if($total_children == 0):
                                $children_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '2' ";
                                if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $children_travelers_sqlwhere)) :
                                endif;
                            endif;

                            //INSERT CHILDREN TRAVELLERS
                            for ($j = 0; $j < $itinerary_children[$i]; $j++) :
                                $traveller_type = 2; // Children
                                $traveller_age = $children_age[$room_id][$j];
                                $child_bed_TYPE = $child_bed_type[$room_id][$j];
                                $hidden_traveller_details_ID = $_POST['hidden_traveller_details_ID'][$room_id][$j];

                                if (($child_bed_TYPE) == 'Without Bed') :
                                    $child_bed_type_id = 1;
                                elseif (($child_bed_TYPE) == 'With Bed') :
                                    $child_bed_type_id = 2;
                                endif;

                                $children_travelers_arrFields = array('`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`traveller_age`', '`child_bed_type`', '`createdby`', '`status`');
                                $children_travelers_arrValues = array("$itinerary_plan_ID", "$traveller_type", "$room_id", "$traveller_age", "$child_bed_type_id", "$logged_user_id", "1");

                                $select_itinerary_child_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                $total_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_child_traveller_details_query);

                                $diff_children_count = $itinerary_children[$i] - $total_num_rows_count;

                                if ($diff_children_count < 0) :
                                    $absoluteValue = abs($diff_children_count);
                                    // Query to get total adult count for the room from the database
                                    $select_total_child_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' AND `traveller_details_ID` != '$hidden_traveller_details_ID' ORDER BY `traveller_details_ID` ASC LIMIT $absoluteValue") or die(sqlERROR_LABEL());
                                    while ($row = sqlFETCHARRAY_LABEL($select_total_child_query)) :
                                        $traveller_details_ID = $row['traveller_details_ID'];
                                        $delete_child_travelers_sqlwhere = " `traveller_details_ID` = '$traveller_details_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $delete_child_travelers_sqlwhere)) :
                                        endif;
                                    endwhile;
                                endif;

                                //UPDATE CHILDREN TRAVELLER DETAILS
                                $children_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' and `traveller_details_ID` = '$hidden_traveller_details_ID' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_traveller_details", $children_travelers_arrFields, $children_travelers_arrValues, $children_travelers_sqlwhere)) :
                                endif;

                                if ($diff_children_count > 0) :
                                    //INSERT CHILDREN TRAVELLER DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $children_travelers_arrFields, $children_travelers_arrValues, '')) :
                                    endif;
                                endif;

                            endfor;

                            if($itinerary_infants == 0):
                                $delete_infant_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '3' ";
                                if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $children_travelers_sqlwhere)) :
                                endif;
                            endif;

                            //INSERT INFANT TRAVELLERS
                            for ($j = 0; $j < $itinerary_infants[$i]; $j++) :
                                $traveller_type = 3; // Infant

                                $infant_travelers_arrFields = array('`itinerary_plan_ID`', '`traveller_type`', '`room_id`', '`createdby`', '`status`');
                                $infant_travelers_arrValues = array("$itinerary_plan_ID", "$traveller_type", "$room_id", "$logged_user_id", "1");

                                $select_itinerary_infant_traveller_details_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                                $total_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_infant_traveller_details_query);

                                $diff_infant_count = $itinerary_infants[$i] - $total_num_rows_count;

                                if ($diff_infant_count < 0) :
                                    $absoluteValue = abs($diff_infant_count);
                                    // Query to get total adult count for the room from the database
                                    $select_total_infant_query = sqlQUERY_LABEL("SELECT `traveller_details_ID` FROM `dvi_itinerary_traveller_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ORDER BY `traveller_details_ID` ASC LIMIT $absoluteValue") or die(sqlERROR_LABEL());
                                    while ($row = sqlFETCHARRAY_LABEL($select_total_infant_query)) :
                                        $traveller_details_ID = $row['traveller_details_ID'];
                                        $delete_infant_travelers_sqlwhere = " `traveller_details_ID` = '$traveller_details_ID' ";
                                        if (sqlACTIONS("DELETE", "dvi_itinerary_traveller_details", '', '', $delete_infant_travelers_sqlwhere)) :
                                        endif;
                                    endwhile;
                                endif;

                                //UPDATE INFANT TRAVELLER DETAILS
                                $infant_travelers_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `room_id` = '$room_id' AND `traveller_type` = '$traveller_type' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_traveller_details", $infant_travelers_arrFields, $infant_travelers_arrValues, $infant_travelers_sqlwhere)) :
                                endif;

                                if ($diff_infant_count > 0) :
                                    //INSERT INFANT TRAVELLER DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_traveller_details", $infant_travelers_arrFields, $infant_travelers_arrValues, '')) :
                                    endif;
                                endif;
                            endfor;

                        endfor;
                    endif;

                    // Main logic
                    /* $source_location = $_POST['source_location'];
                    $next_visiting_location = $_POST['next_visiting_location'];

                    $start_location = $source_location[0];
                    $end_location = end($next_visiting_location);

                    // Collect intermediate locations
                    $intermediate_locations = array_slice($next_visiting_location, 0, -1);
                    $all_locations = array_merge([$start_location], $intermediate_locations, [$end_location]);
                    $unique_locations = array_unique($all_locations);
                    $num_locations = count($unique_locations);

                    // Create a lookup for location indices
                    $location_indices = array_flip($unique_locations);

                    // Construct the SQL query to retrieve all distances
                    $location_placeholders = "'" . implode("','", $unique_locations) . "'";
                    $query = "SELECT `source_location`, `destination_location`, `distance` FROM `dvi_stored_locations`  WHERE `source_location` IN ($location_placeholders)  AND `destination_location` IN ($location_placeholders) AND `deleted` = '0'";

                    $result = sqlQUERY_LABEL($query) or die("#1_UNABLE_TO_FETCH_DATA: " . sqlERROR_LABEL());
                    $distances = [];
                    while ($row = sqlFETCHARRAY_LABEL($result)) {
                        $distances[] = $row;
                    }

                    // Create the distance matrix from the retrieved data
                    $distance_matrix = array_fill(0, $num_locations, array_fill(0, $num_locations, PHP_INT_MAX));
                    foreach ($distances as $row) {
                        $from = $location_indices[$row['source_location']];
                        $to = $location_indices[$row['destination_location']];
                        $distance_matrix[$from][$to] = $row['distance'];
                    }

                    // Get the distance limit from the global setting
                    $daily_limit = getGLOBALSETTING('itinerary_distance_limit');

                    // Optimize only the intermediate locations
                    $intermediate_indices = array_map(function ($loc) use ($location_indices) {
                        return $location_indices[$loc];
                    }, $intermediate_locations);

                    $start_index = $location_indices[$start_location];
                    $end_index = $location_indices[$end_location];

                    // Create the initial route by combining the start, intermediate, and end locations
                    $initial_route_indices = array_merge([$start_index], $intermediate_indices, [$end_index]);

                    // Convert the route indices back to names
                    $route_with_names = convert_indices_to_names($initial_route_indices, $unique_locations);

                    // Enforce daily distance limit
                    $segmented_route = enforce_distance_limit($route_with_names, $distance_matrix, $daily_limit, $unique_locations);

                    // Output the segmented route with source and next visiting place for each day
                    $new_source_location = [];
                    $new_next_visiting_location = [];
                    $day = 1;

                    foreach ($segmented_route as $segment) {
                        for (
                            $i = 0;
                            $i < count($segment) - 1;
                            $i++
                        ) {
                            $source = $segment[$i];
                            $destination = $segment[$i + 1];
                            $distance = $distance_matrix[array_search($source, $unique_locations)][array_search($destination, $unique_locations)];
                            // echo "Day $day: $source -> $destination, Distance: " . number_format($distance, 2) . " km<br>"; 
                            $new_source_location[] = $source;
                            $new_next_visiting_location[] = $destination;
                            $day++;
                        }
                        // If the last segment has only one location, ensure it's treated as a separate day
                        if (count($segment) == 1) {
                            //echo "Day $day: " . $segment[0] . " -> " . $segment[0] . ", Distance: 1.00 km<br>"; 
                            $new_source_location[] = $segment[0];
                            $new_next_visiting_location[] = $segment[0];
                            $day++;
                        }
                    }

                    // Ensure the last location is added to both lists
                    if (end($new_next_visiting_location) !== $end_location) {
                        $new_source_location[] = end($new_next_visiting_location);
                        $new_next_visiting_location[] = $end_location;
                    }*/
                    /* // Print source and next visiting locations
                    echo "<br>Source Locations: <br>";
                    print_r($new_source_location);
                    echo "<br>Next Visiting Locations: <br>";
                    print_r($new_next_visiting_location);
                    exit; */

                    $source_location = $_POST['source_location']; 
                    $next_visiting_location = $_POST['next_visiting_location']; 

                    $total_no_of_routes = count($source_location);

                    // Get the distance limit from the global setting
                    $daily_limit = getGLOBALSETTING('itinerary_distance_limit'); // 200 KM

                    $start_location = $source_location[0]; // Kochi
                    $end_location = end($next_visiting_location); // Trivandrum

                    if($total_no_of_routes <= 10):

                        // Convert arrays to comma-separated strings
                        $source_location_str = implode("','", $source_location);
                        $next_visiting_location_str = implode("','", $next_visiting_location);

                        // Fetch location details
                        $collect_location_details = sqlQUERY_LABEL("SELECT `source_location`, `destination_location`, `distance` FROM `dvi_stored_locations` WHERE `source_location` IN ('$source_location_str') AND `destination_location` IN ('$next_visiting_location_str') AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                        $total_no_of_location = sqlNUMOFROW_LABEL($collect_location_details);

                        $distances = [];

                        // Process results
                        while ($row = sqlFETCHARRAY_LABEL($collect_location_details)) {
                            $source = $row['source_location'];
                            $destination = $row['destination_location'];
                            $distance = $row['distance'];
                            
                            // Populate distances array
                            if (!isset($distances[$source])) {
                                $distances[$source] = [];
                            }
                            $distances[$source][$destination] = $distance;
                        }

                        // Generate all possible routes that include all locations except the last one
                        function generateRoutes($currentRoute, $remainingLocations, $distances, $fixedEndLocation, &$allRoutes, $currentDistance = 0) {
                            if (empty($remainingLocations)) {
                                // Complete the route by adding the fixed end location
                                $lastLocation = end($currentRoute);

                                if (isset($distances[$lastLocation][$fixedEndLocation])) {
                                    $currentDistance += $distances[$lastLocation][$fixedEndLocation];
                                    $currentRoute[] = $fixedEndLocation;
                                }

                                // Store the route and its total distance
                                $allRoutes[] = [
                                    'route' => $currentRoute,
                                    'distance' => $currentDistance
                                ];

                                return;
                            }

                            foreach ($remainingLocations as $key => $location) {
                                $lastLocation = end($currentRoute);
                                $newCurrentDistance = $currentDistance + ($distances[$lastLocation][$location] ?? PHP_INT_MAX);

                                // Recursively generate routes by removing the current location from the remaining set
                                $newRoute = $currentRoute;
                                $newRoute[] = $location;

                                $newRemainingLocations = $remainingLocations;
                                unset($newRemainingLocations[$key]);

                                generateRoutes($newRoute, $newRemainingLocations, $distances, $fixedEndLocation, $allRoutes, $newCurrentDistance);
                            }
                        }

                        // Initialize variables to store all routes
                        $allRoutes = [];

                        // Example usage
                        $start_location = $source_location[0];
                        $end_location = array_pop($next_visiting_location); // Remove and store the last destination
                        $initialRoute = [$start_location];

                        generateRoutes($initialRoute, $next_visiting_location, $distances, $end_location, $allRoutes);

                        // Output all generated routes and their distances
                        /* echo "<pre>";
                        echo "All Generated Routes:\n";
                        foreach ($allRoutes as $routeInfo) {
                            echo "Route: " . implode(' -> ', $routeInfo['route']) . "\n";
                            echo "Total Distance: " . $routeInfo['distance'] . " KM\n";
                            echo "-------------------------\n";
                        }
                        echo "</pre>"; */

                        // Find the best route (shortest distance)
                        $minDistance = PHP_INT_MAX;
                        $bestRoute = [];

                        foreach ($allRoutes as $routeInfo) {
                            if ($routeInfo['distance'] < $minDistance) {
                                $minDistance = $routeInfo['distance'];
                                $bestRoute = $routeInfo['route'];
                            }
                        }

                        /* // Output the best route and the minimum distance
                        echo "<pre>";
                        echo "Best Route: " . implode(' -> ', $bestRoute) . "\n";
                        echo "Minimum Total Distance: " . $minDistance . " KM\n";
                        echo "</pre>"; */

                        if (!empty($bestRoute)) :
                            /* echo "<br>Optimized Route: " . implode(' -> ', $bestRoute) . "\n";
                            echo "Total Distance: " . $minDistance . " KM\n"; */
                            for($r=0;$r<count($bestRoute);$r++):
                                if($r!=(count($bestRoute)-1)):
                                    $new_source_location[] = $bestRoute[$r];
                                endif;
                                if($r!=0):
                                    $new_next_visiting_location[] = $bestRoute[$r];
                                endif;
                            endfor;
                        else :
                            /* echo "No optimized route found.\n"; */
                        endif;
                    else:
                        # MORE THEN 10 DAYS PALN

                        // Function to fetch location details from the database
                        function fetchLocationDetails($source_location, $next_visiting_location)
                        {
                            // Combine source and destination locations and remove duplicates
                            $all_locations = array_merge($source_location, $next_visiting_location);
                            $all_locations_str = implode("','", array_unique($all_locations));

                            $collect_location_details = sqlQUERY_LABEL("SELECT `source_location`, `destination_location`, `distance` FROM `dvi_stored_locations` WHERE `source_location` IN ('$all_locations_str') AND destination_location IN ('$all_locations_str') AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());

                            $distances = [];
                            while ($row = sqlFETCHARRAY_LABEL($collect_location_details)) {
                                $source = $row['source_location'];
                                $destination = $row['destination_location'];
                                $distance = $row['distance'];

                                if (!isset($distances[$source])) {
                                    $distances[$source] = [];
                                }
                                $distances[$source][$destination] = $distance;
                            }

                            return $distances;
                        }

                        // Function to validate if a route meets the daily limit condition
                        function validateRoute($route, $distances, $daily_limit)
                        {
                            $totalDistance = 0;
                            for ($i = 0; $i < count($route) - 1; $i++) {
                                $currentLocation = $route[$i];
                                $nextLocation = $route[$i + 1];
                                if (!isset($distances[$currentLocation][$nextLocation])) {
                                    return false;
                                }
                                $totalDistance += $distances[$currentLocation][$nextLocation];
                                if ($totalDistance > $daily_limit) {
                                    return false;
                                }
                            }
                            return true;
                        }

                        // Nearest Neighbor Heuristic
                        function nearestNeighbor($start, $locations, $distances)
                        {
                            $currentLocation = $start;
                            $route = [$currentLocation];
                            $remainingLocations = array_count_values($locations); // Count occurrences to handle duplicates

                            while (!empty($remainingLocations)) {
                                $nearestDistance = PHP_INT_MAX;
                                $nearestLocation = null;

                                foreach ($remainingLocations as $location => $count) {
                                    if ($count > 0 && isset($distances[$currentLocation][$location]) && $distances[$currentLocation][$location] < $nearestDistance) {
                                        $nearestDistance = $distances[$currentLocation][$location];
                                        $nearestLocation = $location;
                                    }
                                }

                                if ($nearestLocation !== null) {
                                    $route[] = $nearestLocation;
                                    $remainingLocations[$nearestLocation]--;
                                    if ($remainingLocations[$nearestLocation] <= 0) {
                                        unset($remainingLocations[$nearestLocation]);
                                    }
                                    $currentLocation = $nearestLocation;
                                } else {
                                    // Handle the case where no valid next location exists
                                    break;
                                }
                            }

                            return $route;
                        }

                        // Simulated Annealing
                        function simulatedAnnealing($initialRoute, $distances, $daily_limit, $initialTemp = 1000, $coolingRate = 0.003)
                        {
                            $currentRoute = $initialRoute;
                            $currentCost = calculateRouteCost($currentRoute, $distances);

                            $bestRoute = $currentRoute;
                            $minCost = $currentCost;

                            $temperature = $initialTemp;

                            while ($temperature > 1) {
                                // Generate a neighbor by swapping two locations (but do not swap the first and last location)
                                $newRoute = $currentRoute;
                                $i = rand(1, count($newRoute) - 2);
                                $j = rand(1, count($newRoute) - 2);
                                if ($i != 0 && $i != count($newRoute) - 1 && $j != 0 && $j != count($newRoute) - 1) {
                                    list($newRoute[$i], $newRoute[$j]) = array($newRoute[$j], $newRoute[$i]);
                                }

                                $newCost = calculateRouteCost($newRoute, $distances);

                                // Acceptance probability
                                if ($newCost < $currentCost || exp(($currentCost - $newCost) / $temperature) > rand(0, 100) / 100) {
                                    $currentRoute = $newRoute;
                                    $currentCost = $newCost;
                                }

                                if ($newCost < $minCost) {
                                    $minCost = $newCost;
                                    $bestRoute = $newRoute;
                                }

                                // Cool down
                                $temperature *= 1 - $coolingRate;
                            }

                            return [$bestRoute, $minCost];
                        }

                        // Calculate the total cost of a route
                        function calculateRouteCost($route, $distances)
                        {
                            $totalDistance = 0;
                            for ($i = 0; $i < count($route) - 1; $i++) {
                                $currentLocation = $route[$i];
                                $nextLocation = $route[$i + 1];
                                if (!isset($distances[$currentLocation][$nextLocation])) {
                                    return PHP_INT_MAX;
                                }
                                $totalDistance += $distances[$currentLocation][$nextLocation];
                            }
                            return $totalDistance;
                        }
                        // Fetch distances from the database
                        $distances = fetchLocationDetails($source_location, $next_visiting_location);

                        // Get the distance limit from the global setting
                        $daily_limit = getGLOBALSETTING('itinerary_distance_limit');

                        // Prepare the locations for the initial route
                        $remaining_locations = array_slice($next_visiting_location, 0);

                        // Ensure the end location is not included in the remaining locations
                        if (($key = array_search($start_location, $remaining_locations)) !== false) :
                            unset($remaining_locations[$key]);
                        endif;

                        // Ensure the end location is not included in the remaining locations
                        if (($key = array_search($end_location, $remaining_locations)) !== false) :
                            unset($remaining_locations[$key]);
                        endif;

                        // Nearest Neighbor initial solution
                        $initialRoute = nearestNeighbor($start_location, $remaining_locations, $distances);

                        // Ensure the end location is not included in the remaining locations
                        if (($key = array_search($start_location, $initialRoute)) !== false) :
                            unset($initialRoute[$key]);
                        endif;

                        // Ensure the start and end locations are fixed
                        $initialRoute = array_merge([$start_location], $initialRoute, [$end_location]);

                        // Apply Simulated Annealing to improve the solution
                        list($bestRoute, $minCost) = simulatedAnnealing($initialRoute, $distances, $daily_limit);

                        // Output the best route and minimum cost
                        /* echo "<pre>";
                        echo "Best Route: " . implode(' -> ', $bestRoute) . "\n";
                        echo "Minimum Total Distance: " . $minCost . " KM\n";
                        echo "</pre>"; */

                        if (!empty($bestRoute)) :
                            /* echo "<br>Optimized Route: " . implode(' -> ', $bestRoute) . "\n";
                            echo "Total Distance: " . $minDistance . " KM\n"; */
                            for($r=0;$r<count($bestRoute);$r++):
                                    if($r!=(count($bestRoute)-1)):
                                    $new_source_location[] = $bestRoute[$r];
                                endif;
                                if($r!=0):
                                    $new_next_visiting_location[] = $bestRoute[$r];
                                endif;
                            endfor;
                        else :
                            /* echo "No optimized route found.\n"; */
                        endif;
                    endif;

                    $total_source_location_count = count($new_source_location);
                    $total_next_visiting_location_count = count($new_next_visiting_location);

                    $no_of_days = 0;

                    foreach ($new_source_location as $key => $value) :
                        $route_counter++;
                        $no_of_route_count++;
                        $selected_SOURCE_LOCATION = $new_source_location[$key];
                        $selected_NO_OF_DAYS = 1;
                        $selected_NEXT_VISITING_PLACE = $new_next_visiting_location[$key];
                        //$selected_DIRECT_DESTINATION_VISIT = $_POST['direct_destination_visit'][$key + 1][0];
                        $selected_DIRECT_DESTINATION_VISIT_CHECK = 0;
                        /*if ($selected_DIRECT_DESTINATION_VISIT == 'on') :
                            $selected_DIRECT_DESTINATION_VISIT_CHECK = 1;
                        else :
                            $selected_DIRECT_DESTINATION_VISIT_CHECK = 0;
                        endif;*/

                        $itinerary_route_date = date('Y-m-d', strtotime($trip_start_date_and_time . ' + ' . $no_of_days . ' days'));
                        $no_of_days = $no_of_days + $selected_NO_OF_DAYS;

                        $fetch_distance_from_master_table = sqlQUERY_LABEL("SELECT `location_ID`,`distance` FROM  `dvi_stored_locations` WHERE `destination_location` = '$selected_NEXT_VISITING_PLACE' AND `source_location` = '$selected_SOURCE_LOCATION' AND `deleted` = '0'") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($fetch_distance_from_master_table) > 0) :
                            while ($row = sqlFETCHARRAY_LABEL($fetch_distance_from_master_table)) :
                                $location_ID = $row['location_ID'];
                                $distanceKM = $row['distance'];
                            endwhile;
                        else :
                            $distanceKM = 0;
                        endif;

                        if ($total_source_location_count == 1) :
                            $route_start_time = $trip_start_time;
                            $get_travelling_distance = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($selected_SOURCE_LOCATION,$selected_NEXT_VISITING_PLACE,'get_travelling_distance');
                            if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                if ($route_start_time >= '20:00:00') :
                                    $route_end_time = '23:59:00';
                                    $return_to_hotel = '23:59:00';
                                else :
                                    $route_end_time = '20:00:00';
                                    $return_to_hotel = '20:00:00';
                                endif;
                            else :
                                if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                    if ($route_start_time >= '20:00:00') :
                                        $route_end_time = '23:59:00';
                                        $return_to_hotel = '23:59:00';
                                    else :
                                        $route_end_time = '20:00:00';
                                        $return_to_hotel = '20:00:00';
                                    endif;
                                else:
                                    if ($route_start_time >= '20:00:00'):
                                            $route_end_time = '23:59:00';
                                            $return_to_hotel = '23:59:00';
                                    else:
                                        $route_end_time = '20:00:00';
                                        $return_to_hotel = '20:00:00';
                                    endif;
                                endif;
                            endif;
                        else :
                            if (trim($arrival_location) == trim($selected_SOURCE_LOCATION) && $no_of_route_count == 1) :
                                $route_start_time = $trip_start_time;
                            else :
                                $route_start_time = '08:00:00';
                            endif;

                            if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $no_of_route_count == $total_source_location_count) :
                                switch ($departure_type):
                                    case 1: // By Flight
                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                        break;
                                    case 2: // By Train
                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                        break;
                                    case 3: // By Road
                                        $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                        break;
                                    default:
                                        $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                                endswitch;

                                // Convert buffer time to seconds
                                list($hours, $minutes, $seconds) = explode(':', $itinerary_travel_type_buffer_time);
                                $buffer_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                                // Convert trip end time to timestamp
                                $trip_end_timestamp = strtotime($trip_end_time);

                                // Subtract buffer time from trip end time
                                $adjusted_trip_end_timestamp = $trip_end_timestamp - $buffer_seconds;

                                // Convert adjusted time back to the desired format
                                $adjusted_trip_end_time = date('H:i:s', $adjusted_trip_end_timestamp);

                                $route_end_time = $adjusted_trip_end_time;
                                $return_to_hotel = $adjusted_trip_end_time;
                                $trip_last_day = true;
                            else :
                                $get_travelling_distance = getSTOREDLOCATION_SOURCE_AND_DESTINATION_DETAILS($selected_SOURCE_LOCATION,$selected_NEXT_VISITING_PLACE,'get_travelling_distance');
                                if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                    if ($route_start_time >= '20:00:00') :
                                        $route_end_time = '23:59:00';
                                        $return_to_hotel = '23:59:00';
                                    else :
                                        $route_end_time = '20:00:00';
                                        $return_to_hotel = '20:00:00';
                                    endif;
                                else :
                                    if ($get_travelling_distance <= $site_seeing_restriction_km_limit) :
                                        if ($route_start_time >= '20:00:00') :
                                            $route_end_time = '23:59:00';
                                            $return_to_hotel = '23:59:00';
                                        else :
                                            $route_end_time = '20:00:00';
                                            $return_to_hotel = '20:00:00';
                                        endif;
                                    else:
                                        if ($route_start_time >= '20:00:00'):
                                            $route_end_time = '23:59:00';
                                            $return_to_hotel = '23:59:00';
                                        else:
                                            $route_end_time = '20:00:00';
                                            $return_to_hotel = '20:00:00';
                                        endif;
                                    endif;
                                endif;
                                $trip_last_day = false;
                            endif;
                        endif;

                        $route_arrFields = array('`itinerary_plan_ID`', '`location_id`', '`location_name`', '`itinerary_route_date`', '`no_of_days`', '`no_of_km`', '`direct_to_next_visiting_place`', '`next_visiting_location`', '`route_start_time`', '`route_end_time`', '`createdby`', '`status`');

                        $route_arrValues = array("$itinerary_plan_ID", "$location_ID", "$selected_SOURCE_LOCATION", "$itinerary_route_date", "$selected_NO_OF_DAYS", "$distanceKM", "$selected_DIRECT_DESTINATION_VISIT_CHECK", "$selected_NEXT_VISITING_PLACE", "$route_start_time", "$route_end_time", "$logged_user_id", "1");

                        //INSERT ROUTE DETAILS
                        if (sqlACTIONS("INSERT", "dvi_itinerary_route_details", $route_arrFields, $route_arrValues, '')) :
                            $itinerary_route_ID = sqlINSERTID_LABEL();

                            $update_via_route_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_via_route_details` SET `itinerary_route_ID` = '$itinerary_route_ID', `itinerary_plan_ID` = '$itinerary_plan_ID', `itinerary_route_date` = '$itinerary_route_date' WHERE `itinerary_session_id` = '$itinerary_session_id' AND `deleted` = '0'") or die("#1_UNABLE_TO_UPDATE_VIA_ROUTE_DATA:" . sqlERROR_LABEL());

                            $itinerary_common_buffer_time = getGLOBALSETTING('itinerary_common_buffer_time');
                            $hotspot_start_time = $route_start_time;

                            // Convert time strings to seconds
                            $start_seconds = strtotime($hotspot_start_time);
                            $buffer_seconds = strtotime($itinerary_common_buffer_time) - strtotime('00:00:00');

                            // Add the buffer time to the start time
                            $total_seconds = $start_seconds + $buffer_seconds;

                            // Convert the total seconds back to the time format
                            $hotspot_end_time = date('H:i:s', $total_seconds);

                            if($itinerary_prefrence != 1):
                                if (($hotspot_end_time <= $route_end_time && $trip_last_day == false) ||($trip_last_day == true)) :

                                    $select_itineary_hotspot_refresh_time_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '1'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                    $select_itineary_hotspot_refresh_buffer_time_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_refresh_time_data);

                                    //INSERT HOTSPOT REFRESH TIME
                                    $route_hotspot_refresh_time_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                    $route_hotspot_refresh_time_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "1", "1", "$itinerary_common_buffer_time", "$hotspot_start_time", "$hotspot_end_time", "$logged_user_id", "1");

                                    if ($trip_last_day == false) :
                                        //CHECK HOTSPOT REFRESH TIME RECORD AVAILABILITY
                                        if ($select_itineary_hotspot_refresh_buffer_time_count > 0) :
                                            $fetch_itineary_hotspot_refresh_time_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_refresh_time_data);
                                            $route_hotspot_ID = $fetch_itineary_hotspot_refresh_time_data['route_hotspot_ID'];

                                            $route_hotspot_refresh_time_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '1' ";
                                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, $route_hotspot_refresh_time_sqlwhere)) :
                                            endif;
                                        else :
                                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_refresh_time_arrFields, $route_hotspot_refresh_time_arrValues, '')) :
                                            endif;
                                        endif;
                                    endif;

                                    $start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_starting_location_id');

                                    //CHECK DIRECT DESTINATION TRAVEL
                                    if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                        //INSERT HOTSPOT DIRECT DESTINATION TRAVEL

                                        $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                        $direct_destination_travel_start_time = $hotspot_end_time;

                                        $travel_distance = calculateTravelDistanceAndTime($start_location_id);
                                        $_distance = $travel_distance['distance'];
                                        $_time = $travel_distance['duration'];

                                        // Extract hours and minutes from the duration string
                                        preg_match('/(\d+) hour/', $_time, $hours_match);
                                        preg_match('/(\d+) min/', $_time, $minutes_match);

                                        $hours = isset($hours_match[1]) ? $hours_match[1] : 0;
                                        $minutes = isset($minutes_match[1]) ? $minutes_match[1] : 0;

                                        // Format the time as H:i:s
                                        $formatted_time = sprintf('%02d:%02d:00', $hours, $minutes);

                                        // Convert times to seconds
                                        $seconds1 = strtotime("1970-01-01 $direct_destination_travel_start_time UTC");
                                        $seconds2 = strtotime("1970-01-01 $formatted_time UTC");

                                        $direct_destination_travel_end_time = gmdate('H:i:s', ($seconds1 + $seconds2));

                                        $route_hotspot_direct_destination_visit_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                        $route_hotspot_direct_destination_visit_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "2", "2", "$formatted_time", "$_distance", "$direct_destination_travel_start_time", "$direct_destination_travel_end_time", "$logged_user_id", "1");

                                        if ($select_itineary_hotspot_direct_destination_count > 0) :
                                            $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                            $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                            $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, $route_hotspot_direct_destination_visit_sqlwhere)) :
                                            endif;
                                        else :
                                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_direct_destination_visit_arrFields, $route_hotspot_direct_destination_visit_arrValues, '')) :
                                            endif;
                                        endif;

                                        $hotspot_order = 2;
                                        $hotspot_siteseeing_travel_start_time = $direct_destination_travel_end_time;
                                        $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                        $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                    else :

                                        $hotspot_order = 1;
                                        $hotspot_siteseeing_travel_start_time = $hotspot_end_time;
                                        $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                                        $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');

                                        //DELETE THE DIRECT DESTINATION VISIT RECORD
                                        $select_itineary_hotspot_direct_destination_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '2'") or die("#2-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        $select_itineary_hotspot_direct_destination_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_direct_destination_data);

                                        if ($select_itineary_hotspot_direct_destination_count > 0) :
                                            $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_direct_destination_data);
                                            $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                            $route_hotspot_direct_destination_visit_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '2' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $route_hotspot_direct_destination_visit_sqlwhere)) :
                                            endif;
                                        endif;

                                    endif;

                                    if ($trip_last_day == false) :
                                        //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                        // Convert the date string to a Unix timestamp using strtotime
                                        $timestamp = strtotime($itinerary_route_date);

                                        if ($timestamp !== false) :
                                            // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                            $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                        endif;

                                        //ROUTE LOCATION SOURCE NAME
                                        $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                        //NEXT VISITING PLACE LOCATION NAME
                                        $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                            $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                        else:
                                        // Initialize variables for the starting location
                                            $filter_location_name = '';
                                        endif;
                                        
                                        //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                        #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                        $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                        $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                        // Initialize variables for categorization
                                        $source_location_hotspots = [];
                                        $via_route_hotspots = [];
                                        $destination_hotspots = [];
                                        
                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                            if(empty($via_route_name)):
                                                $previous_hotspot_location = $location_name;
                                            else:
                                                $previous_hotspot_location = $location_name;
                                            endif;
                                        else:
                                        // Initialize variables for the starting location
                                            $previous_hotspot_location = $location_name;
                                        endif;

                                        if ($select_hotspot_details_num_rows_count > 0): 
                                            while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                // Proceed with adding the hotspot to the itinerary for the current day
                                                $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                // Determine the travel location type
                                                $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                // Calculate the distance and duration from the starting location
                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                // Categorize hotspots based on location type
                                                $hotspot_details = [
                                                    'hotspot_ID' => $hotspot_ID,
                                                    'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                    'hotspot_name' => $hotspot_name,
                                                    'hotspot_duration' => $hotspot_duration,
                                                    'hotspot_latitude' => $hotspot_latitude,
                                                    'hotspot_longitude' => $hotspot_longitude,
                                                    'hotspot_distance' => $get_hotspot_travelling_distance,
                                                    'hotspot_location' => $hotspot_location,
                                                    'hotspot_priority' => $hotspot_priority,
                                                    'previous_hotspot_location' => $previous_hotspot_location
                                                ];

                                                $source_match = containsLocation($hotspot_location, $location_name);
                                                $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                if ($source_match) :
                                                    $source_location_hotspots[] = $hotspot_details;
                                                endif;

                                                if ($destination_match) :
                                                    $destination_hotspots[] = $hotspot_details;
                                                endif;

                                                /* if (!$source_match && !$destination_match) :
                                                    $via_route_hotspots[] = $hotspot_details;
                                                endif; */

                                                $via_route_hotspots = []; // initialize before loop
                                                $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                if ($matchIndex !== false) {
                                                    // Group hotspots by VIA index
                                                    $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                }

                                            endwhile;

                                            // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                            ksort($via_route_hotspots);

                                            // Flatten grouped hotspots into a single ordered array
                                            $ordered_hotspots = [];
                                            foreach ($via_route_hotspots as $group) {
                                                foreach ($group as $h) {
                                                    $ordered_hotspots[] = $h;
                                                }
                                            }

                                            // Now use $ordered_hotspots instead of $via_route_hotspots
                                            $via_route_hotspots = $ordered_hotspots;                                            
                                            sortHotspots($source_location_hotspots);
                                            sortHotspots($via_route_hotspots);
                                            sortHotspots($destination_hotspots);

                                            // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                            $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                            endif;

                                            // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                            $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                            // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                            $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                            endif;

                                            // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                            $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                            endif;

                                            // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                            $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                            endif;

                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                // Process via route hotspots
                                                $processed_via_route_hotspots = false;
                                                if(!empty($via_route_hotspots)):
                                                    foreach ($via_route_hotspots as $hotspot) :
                                                        $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                        if($check_via_route_hotspot_added):
                                                        $processed_via_route_hotspots = true;
                                                        endif;
                                                        if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                            break; // Stop processing if past the cutoff time
                                                        endif;
                                                    endforeach;
                                                endif;

                                                if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                    // Execute the query to fetch via route IDs
                                                    $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                    // Fetch the results
                                                    if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                        while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                            $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                            $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                            $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                            $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                            $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                            $via_route_traveling_time = $result['duration'];

                                                            // **EXTRACT AND FORMAT TIME DETAILS**
                                                            preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                            preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                            $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                            $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                            // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                            $extraHours = floor($minutes / 60);
                                                            $hours += $extraHours;
                                                            $minutes %= 60;

                                                            $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                            // **CALCULATE THE DURATION IN SECONDS**
                                                            $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                            // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                            $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                            $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                            $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                            $hotspot_order++;
                                                            $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                            
                                                            $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                            // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                $staring_location_latitude = $via_route_location_lattitude;
                                                                $staring_location_longtitude = $via_route_location_longitude;
                                                                $previous_hotspot_location = $via_route_location;
                                                            endif;
                                                        endwhile;
                                                    endif;
                                                endif;

                                                // Process destination hotspots
                                                if (!empty($destination_hotspots)) :
                                                    foreach ($destination_hotspots as $hotspot) :
                                                        if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                            break; // Stop processing if past the cutoff time
                                                        endif;
                                                        includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                    endforeach;
                                                endif;
                                            else:
                                                // Process source location hotspots
                                                if (!empty($source_location_hotspots)) :
                                                    foreach ($source_location_hotspots as $hotspot) :
                                                        includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                        if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                            break; // Stop processing if past the cutoff time
                                                        endif;
                                                    endforeach;
                                                endif;

                                                // Process via route hotspots
                                                $processed_via_route_hotspots = false;
                                                if (!empty($via_route_hotspots)) :
                                                    foreach ($via_route_hotspots as $hotspot) :
                                                        $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                        if($check_via_route_hotspot_added):
                                                        $processed_via_route_hotspots = true;
                                                        endif;
                                                        if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                            break; // Stop processing if past the cutoff time
                                                        endif;
                                                    endforeach;
                                                endif;

                                                if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                    // Execute the query to fetch via route IDs
                                                    $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                    // Fetch the results
                                                    if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                        while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                            $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                            $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                            $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                            $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                            $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                            $via_route_traveling_time = $result['duration'];

                                                            // **EXTRACT AND FORMAT TIME DETAILS**
                                                            preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                            preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                            $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                            $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                            // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                            $extraHours = floor($minutes / 60);
                                                            $hours += $extraHours;
                                                            $minutes %= 60;

                                                            $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                            // **CALCULATE THE DURATION IN SECONDS**
                                                            $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                            // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                            $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                            $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                            $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                            $hotspot_order++;
                                                            $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                            
                                                            $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                            // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                            if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                $staring_location_latitude = $via_route_location_lattitude;
                                                                $staring_location_longtitude = $via_route_location_longitude;
                                                                $previous_hotspot_location = $via_route_location;
                                                            endif;
                                                        endwhile;
                                                    endif;
                                                endif;

                                                if (!empty($destination_hotspots)) :
                                                // Process destination hotspots
                                                    foreach ($destination_hotspots as $hotspot) :
                                                        if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                            break; // Stop processing if past the cutoff time
                                                        endif;
                                                        includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $route_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                    endforeach;
                                                endif;
                                            endif;
                                        endif;
                                    endif;

                                    $get_last_route_id_from_this_itinerary_plan = sqlQUERY_LABEL("SELECT MAX(`itinerary_route_ID`) AS max_route_id FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                    $fetch_last_route_id_from_this_itineary_plan = sqlFETCHARRAY_LABEL($get_last_route_id_from_this_itinerary_plan);
                                    $last_itinerary_route_ID = $fetch_last_route_id_from_this_itineary_plan['max_route_id'];

                                    //INSERT THE END OF THE TRIP DEPARTURE START TIME
                                    if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $last_itinerary_route_ID == $itinerary_route_ID && $trip_last_day == true) :
                                        $hotspot_order = $hotspot_order;

                                        //Determine the buffer time based on the departure_type [1 - By Flight | 2 - By Train | 3 - By Road]
                                        switch ($departure_type):
                                                case 1: // By Flight
                                                $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                                break;
                                            case 2: // By Train
                                                $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                                break;
                                            case 3: // By Road
                                                $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                                break;
                                            default:
                                                $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                                        endswitch;

                                        $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                        $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                        $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                        // Determine the travel location type
                                        $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                        // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                        $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                        $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                        $destination_traveling_time = $result['duration'];

                                        // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                        preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                        preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                        $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                        //CALCAULATE THE DURATIONS IN SECONDS
                                        $totalSeconds = ($hours * 3600) + ($minutes * 60);

                                        //ADD THE DURATION TO THE START TIME
                                        $newTimeInSeconds = $totalSeconds + strtotime($itinerary_travel_type_buffer_time);

                                        //CONVERT THE NEW TIME TO {destination_total_duration} H:i:s FORMAT
                                        $destination_total_duration = date('H:i:s', $newTimeInSeconds);

                                        // Convert hotspot_siteseeing_travel_start_time to seconds
                                        $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                        // Convert destination_total_duration to seconds
                                        list($hours, $minutes, $seconds) = sscanf($destination_total_duration, "%d:%d:%d");
                                        $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                        // Add the duration and buffer time to the start time
                                        $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds + $bufferInSeconds;

                                        // Convert the total time back to H:i:s format
                                        $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                        if ($destination_travel_end_time <= $route_end_time) :

                                            // Format total traveling time
                                            $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                            /* // Format buffer time and convert to seconds
                                            $itinerary_travel_type_buffer_time_formatted = date('H:i:s', strtotime($itinerary_travel_type_buffer_time));
                                            list($buffer_hours, $buffer_minutes, $buffer_seconds) = explode(':', $itinerary_travel_type_buffer_time_formatted);
                                            $itinerary_travel_buffer_seconds = ($buffer_hours * 3600) + ($buffer_minutes * 60) + $buffer_seconds; */

                                            // Convert route end time to timestamp
                                            $route_end_timestamp = strtotime($route_end_time);

                                            // Convert total traveling time to seconds
                                            list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                            $travelling_seconds = ($hours * 3600) + ($minutes * 60) + $seconds;

                                            // Subtract the total traveling time and buffer time from the route end time
                                            $adjusted_route_start_timestamp = $route_end_timestamp - ($travelling_seconds);
                                            /* $itinerary_travel_buffer_seconds; */
                                            
                                            // Convert the adjusted time back to the desired format
                                            $adjusted_route_hotspot_end_time = date('H:i:s', $adjusted_route_start_timestamp);

                                            //CHECK ITINEARY ROUTE & VIA ROUTE DETAILS FOR HOTSPOT
                                            // Convert the date string to a Unix timestamp using strtotime
                                            $timestamp = strtotime($hidden_itinerary_route_date);

                                            if ($timestamp !== false) :
                                                // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                            endif;

                                            $location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                                            //ROUTE LOCATION SOURCE NAME
                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                                $filter_location_name = " HOTSPOT_PLACE.`hotspot_location` LIKE '%$location_name%' OR ";
                                            else :
                                                $filter_location_name = '';
                                            endif;

                                            //NEXT VISITING PLACE LOCATION NAME
                                            $next_visiting_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                            $get_via_route_IDs = get_ITINEARY_VIA_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_via_route_IDs');

                                            if ($get_via_route_IDs): 
                                                
                                                if ($get_via_route_IDs): 
                                                    $get_via_route_location_IDs = implode(',', $get_via_route_IDs);
                                                else: 
                                                    $get_via_route_location_IDs = NULL;
                                                endif;

                                                // VIA ROUTE LOCATION NAME
                                                $via_route_name = getSTOREDLOCATION_VIAROUTE_DETAILS($start_location_id, $get_via_route_location_IDs, 'MULTIPLE_VIAROUTE_LOCATION');

                                                if ($via_route_name): 
                                                    // Ensure that $via_route_name is an array
                                                    if (is_array($via_route_name)): 
                                                        $via_route_conditions = array_map(function($location) {
                                                            // Use LIKE for pipe-separated values
                                                            return "HOTSPOT_PLACE.`hotspot_location` LIKE '%$location%'";
                                                        }, $via_route_name);

                                                        // Join conditions with ' OR '
                                                        $add_filter_via_route_location = ' OR ' . implode(' OR ', $via_route_conditions);
                                                    else: 
                                                        $add_filter_via_route_location = '';
                                                    endif;
                                                else: 
                                                    $add_filter_via_route_location = '';
                                                endif;
                                            else: 
                                                $via_route_name = '';
                                                $add_filter_via_route_location = '';
                                            endif;

                                            //CHECK HOTSPOT AVILABILITY AND ADD INTO THE ITINEARY ROUTE PLAN
                                            #RETRIVE HOTSPOT DATA BASED ON THE LOCATION LOCATION NAME AND VIA ROUTE
                                            $select_hotspot_details_data = sqlQUERY_LABEL("SELECT HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_location`, HOTSPOT_PLACE.`hotspot_latitude`, HOTSPOT_PLACE.`hotspot_longitude`, HOTSPOT_PLACE.`hotspot_duration`,HOTSPOT_PLACE.`hotspot_priority` FROM `dvi_hotspot_place` HOTSPOT_PLACE LEFT JOIN `dvi_hotspot_timing` HOTSPOT_TIMING ON HOTSPOT_TIMING.`hotspot_ID` = HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_PLACE.`deleted` = '0' AND HOTSPOT_PLACE.`status` = '1' AND HOTSPOT_TIMING.`hotspot_timing_day` = '$dayOfWeekNumeric' AND ({$filter_location_name} HOTSPOT_PLACE.`hotspot_location` LIKE '%$next_visiting_name%' {$add_filter_via_route_location}) GROUP BY HOTSPOT_PLACE.`hotspot_ID` ORDER BY CASE WHEN HOTSPOT_PLACE.`hotspot_priority` = 0 THEN 1 ELSE 0 END, HOTSPOT_PLACE.`hotspot_priority` ASC") or die("#1-UNABLE_TO_COLLECT_HOTSPOT_PLACE_LIST:" . sqlERROR_LABEL());
                                            $select_hotspot_details_num_rows_count = sqlNUMOFROW_LABEL($select_hotspot_details_data);

                                            // Initialize variables for categorization
                                            $source_location_hotspots = [];
                                            $via_route_hotspots = [];
                                            $destination_hotspots = [];

                                            if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                if(empty($via_route_name)):
                                                    $previous_hotspot_location = $location_name;
                                                else:
                                                    $previous_hotspot_location = $location_name;
                                                endif;
                                            else:
                                            // Initialize variables for the starting location
                                                $previous_hotspot_location = $location_name;
                                            endif;

                                            if ($select_hotspot_details_num_rows_count > 0): 
                                                while ($fetch_hotspot_data = sqlFETCHARRAY_LABEL($select_hotspot_details_data)): 
                                                    // Proceed with adding the hotspot to the itinerary for the current day
                                                    $hotspot_ID = $fetch_hotspot_data['hotspot_ID'];
                                                    $hotspot_name = $fetch_hotspot_data['hotspot_name'];
                                                    $hotspot_description = $fetch_hotspot_data['hotspot_description'];
                                                    $hotspot_address = $fetch_hotspot_data['hotspot_address'];
                                                    $hotspot_location = $fetch_hotspot_data['hotspot_location'];
                                                    $hotspot_latitude = $fetch_hotspot_data['hotspot_latitude'];
                                                    $hotspot_longitude = $fetch_hotspot_data['hotspot_longitude'];
                                                    $hotspot_duration = $fetch_hotspot_data['hotspot_duration'];
                                                    $hotspot_priority = $fetch_hotspot_data['hotspot_priority'];

                                                    // Determine the travel location type
                                                    $travel_location_type = getTravelLocationType($previous_hotspot_location, $hotspot_location);

                                                    // Calculate the distance and duration from the starting location
                                                    $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $hotspot_latitude, $hotspot_longitude, $travel_location_type);
                                                    $get_hotspot_travelling_distance = number_format($result['distance'], 2, '.', '');

                                                    // Categorize hotspots based on location type
                                                    $hotspot_details = [
                                                        'hotspot_ID' => $hotspot_ID,
                                                        'hotspot_siteseeing_travel_start_time' => $hotspot_siteseeing_travel_start_time,
                                                        'hotspot_name' => $hotspot_name,
                                                        'hotspot_duration' => $hotspot_duration,
                                                        'hotspot_latitude' => $hotspot_latitude,
                                                        'hotspot_longitude' => $hotspot_longitude,
                                                        'hotspot_distance' => $get_hotspot_travelling_distance,
                                                        'hotspot_location' => $hotspot_location,
                                                        'hotspot_priority' => $hotspot_priority,
                                                        'previous_hotspot_location' => $previous_hotspot_location
                                                    ];

                                                    $source_match = containsLocation($hotspot_location, $location_name);
                                                    $destination_match = containsLocation($hotspot_location, $next_visiting_name);

                                                    if ($source_match) :
                                                        $source_location_hotspots[] = $hotspot_details;
                                                    endif;

                                                    if ($destination_match) :
                                                        $destination_hotspots[] = $hotspot_details;
                                                    endif;

                                                    /* if (!$source_match && !$destination_match) :
                                                        $via_route_hotspots[] = $hotspot_details;
                                                    endif; */

                                                    $via_route_hotspots = []; // initialize before loop
                                                    $matchIndex = containsViaRouteLocation($hotspot_location, $via_route_name);
                                                    if ($matchIndex !== false) {
                                                        // Group hotspots by VIA index
                                                        $via_route_hotspots[$matchIndex][] = $hotspot_details;
                                                    }

                                                endwhile;

                                                // Sort by VIA index 0,1,2,... so order matches $via_route_name
                                                ksort($via_route_hotspots);

                                                // Flatten grouped hotspots into a single ordered array
                                                $ordered_hotspots = [];
                                                foreach ($via_route_hotspots as $group) {
                                                    foreach ($group as $h) {
                                                        $ordered_hotspots[] = $h;
                                                    }
                                                }

                                                // Now use $ordered_hotspots instead of $via_route_hotspots
                                                $via_route_hotspots = $ordered_hotspots;

                                                sortHotspots($source_location_hotspots);
                                                sortHotspots($via_route_hotspots);
                                                sortHotspots($destination_hotspots);

                                                // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                                $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                                $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                                // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                                $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                                $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                                endif;

                                                // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                                $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                                if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                                endif;

                                                if ($selected_DIRECT_DESTINATION_VISIT_CHECK == 1) :
                                                    // Process via route hotspots
                                                    $processed_via_route_hotspots = false;
                                                    $hotspot_processed = false;
                                                    if(!empty($via_route_hotspots)):
                                                        foreach ($via_route_hotspots as $hotspot) :
                                                            $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($check_via_route_hotspot_added):
                                                            $processed_via_route_hotspots = true;
                                                            $hotspot_processed = true;
                                                            endif;
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                        endforeach;
                                                    endif;

                                                    if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                        $hotspot_processed = false;
                                                        // Execute the query to fetch via route IDs
                                                        $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                        // Fetch the results
                                                        if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                            while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                $via_route_traveling_time = $result['duration'];

                                                                // **EXTRACT AND FORMAT TIME DETAILS**
                                                                preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                $extraHours = floor($minutes / 60);
                                                                $hours += $extraHours;
                                                                $minutes %= 60;

                                                                $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                // **CALCULATE THE DURATION IN SECONDS**
                                                                $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                $hotspot_order++;
                                                                $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                
                                                                $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                    $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                    $staring_location_latitude = $via_route_location_lattitude;
                                                                    $staring_location_longtitude = $via_route_location_longitude;
                                                                    $previous_hotspot_location = $via_route_location;
                                                                    $hotspot_processed = true;
                                                                endif;
                                                            endwhile;
                                                        endif;
                                                    endif;

                                                    // Process destination hotspots
                                                    if (!empty($destination_hotspots)) :
                                                        $hotspot_processed = false;
                                                        foreach ($destination_hotspots as $hotspot) :
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                            $destination_hotspots_processed = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($destination_hotspots_processed):
                                                            $hotspot_processed = true;
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                else:
                                                    // Process source location hotspots
                                                    if (!empty($source_location_hotspots)) :
                                                        $hotspot_processed = false;
                                                        foreach ($source_location_hotspots as $hotspot) :
                                                            $source_hotspots_processed = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($source_hotspots_processed):
                                                            $hotspot_processed = true;
                                                            endif;
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($source_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                        endforeach;
                                                    endif;

                                                    // Process via route hotspots
                                                    $processed_via_route_hotspots = false;
                                                    if (!empty($via_route_hotspots)) :
                                                        $hotspot_processed = false;
                                                        foreach ($via_route_hotspots as $hotspot) :
                                                            $check_via_route_hotspot_added = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($check_via_route_hotspot_added):
                                                            $processed_via_route_hotspots = true;
                                                            $hotspot_processed = true;
                                                            endif;
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($via_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                        endforeach;
                                                    endif;

                                                    if (getItineraryRouteHotspotsByViaLocation($itinerary_plan_ID, $itinerary_route_ID, $via_route_name) == 0 && !empty($via_route_name)) :
                                                        $hotspot_processed = false;
                                                        // Execute the query to fetch via route IDs
                                                        $select_itineary_via_route_details = sqlQUERY_LABEL("SELECT `via_route_location`, `via_route_location_lattitude`, `via_route_location_longitude` FROM `dvi_stored_location_via_routes` WHERE `deleted` = '0' AND `status` = '1' AND `via_route_location_ID` IN ($get_via_route_location_IDs)") or die("#1-UNABLE_TO_GET_DETAILS:" . sqlERROR_LABEL());
                                                        // Fetch the results
                                                        if (sqlNUMOFROW_LABEL($select_itineary_via_route_details) > 0) :
                                                            while ($fetch_itineary_via_route_data = sqlFETCHARRAY_LABEL($select_itineary_via_route_details)) :
                                                                $via_route_location = $fetch_itineary_via_route_data['via_route_location'];
                                                                $via_route_location_lattitude = $fetch_itineary_via_route_data['via_route_location_lattitude'];
                                                                $via_route_location_longitude = $fetch_itineary_via_route_data['via_route_location_longitude'];

                                                                $get_travel_type = getTravelLocationType($previous_hotspot_location, $via_route_location);

                                                                $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $via_route_location_lattitude, $via_route_location_longitude, $get_travel_type);
                                                                $via_route_travelling_distance = number_format($result['distance'], 2, '.', '');
                                                                $via_route_traveling_time = $result['duration'];

                                                                // **EXTRACT AND FORMAT TIME DETAILS**
                                                                preg_match('/(\d+) hour/', $via_route_traveling_time, $hoursMatch);
                                                                preg_match('/(\d+) mins/', $via_route_traveling_time, $minutesMatch);

                                                                $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                                                $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                                                // **CALCULATE EXTRA HOURS IF MINUTES EXCEED 59**
                                                                $extraHours = floor($minutes / 60);
                                                                $hours += $extraHours;
                                                                $minutes %= 60;

                                                                $via_route_duration_formatted = sprintf('%02d:%02d:00', $hours, $minutes);

                                                                // **CALCULATE THE DURATION IN SECONDS**
                                                                $via_route_totalSeconds = ($hours * 3600) + ($minutes * 60);

                                                                // **CONVERT START TIME TO SECONDS AND CALCULATE END TIME**
                                                                $via_route_startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);
                                                                $newTimeInSeconds = $via_route_startTimeInSeconds + $via_route_totalSeconds;
                                                                $via_route_travel_end_time = date('H:i:s', $newTimeInSeconds);

                                                                $hotspot_order++;
                                                                $via_route_traveling_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`allow_via_route`', '`via_location_name`', '`hotspot_order`','`hotspot_traveling_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');
                                                                
                                                                $via_route_traveling_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "3", "1", "$via_route_location", "$hotspot_order", "$via_route_duration_formatted", "$via_route_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$via_route_travel_end_time", "$logged_user_id", "1");

                                                                // **INSERT THE ITINERARY VIA ROUTE TRAVELING DATA**
                                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $via_route_traveling_arrFields, $via_route_traveling_arrValues, '')) :
                                                                    $hotspot_siteseeing_travel_start_time = $via_route_travel_end_time;
                                                                    $staring_location_latitude = $via_route_location_lattitude;
                                                                    $staring_location_longtitude = $via_route_location_longitude;
                                                                    $previous_hotspot_location = $via_route_location;
                                                                    $hotspot_processed = true;
                                                                endif;
                                                            endwhile;
                                                        endif;
                                                    endif;

                                                    if (!empty($destination_hotspots)) :
                                                        $hotspot_processed = false;
                                                        // Process destination hotspots
                                                        foreach ($destination_hotspots as $hotspot) :
                                                            if (strtotime($hotspot_siteseeing_travel_start_time) >= strtotime($destination_cutoff_time)) :
                                                                break; // Stop processing if past the cutoff time
                                                            endif;
                                                            $destination_hotspots_processed = includeHotspotInItinerary($hotspot, $itinerary_plan_ID, $itinerary_route_ID, $hotspot_order, $logged_user_id, $entry_ticket_required, $total_adult, $total_children, $total_infants, $nationality, $hotspot_siteseeing_travel_start_time, $staring_location_latitude, $staring_location_longtitude, $adjusted_route_hotspot_end_time, $dayOfWeekNumeric,$last_hotspot_details);
                                                            if($destination_hotspots_processed):
                                                            $hotspot_processed = true;
                                                            endif;
                                                        endforeach;
                                                    endif;
                                                endif;
                                            endif;
                                            
                                            if($hotspot_processed):
                                                $last_hotspot_location = $last_hotspot_details['last_hotspot_location'];
                                                $last_hotspot_latitude = $last_hotspot_details['last_hotspot_latitude'];
                                                $last_hotspot_longitude = $last_hotspot_details['last_hotspot_longitude'];
                                                $hotspot_siteseeing_travel_start_time = $last_hotspot_details['last_hotspot_end_time'];
                                            else:
                                                $last_hotspot_location = $previous_hotspot_location;
                                                $last_hotspot_latitude = $staring_location_latitude;
                                                $last_hotspot_longitude = $staring_location_longtitude;
                                                $hotspot_siteseeing_travel_start_time = $hotspot_siteseeing_travel_start_time;
                                            endif;

                                            // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                            $travel_location_type = getTravelLocationType($last_hotspot_location, $ending_location_name);
                                            $result = calculateDistanceAndDuration($last_hotspot_latitude, $last_hotspot_longitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                            $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                            $destination_traveling_time = $result['duration'];

                                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                            preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                            preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                                            // INITIALIZE HOURS AND MINUTES TO ZERO
                                            $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                                            $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                                            // CALCULATE TOTAL DURATION IN SECONDS (hours and minutes combined)
                                            $totalDurationInSeconds = ($hours * 3600) + ($minutes * 60);

                                            // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59 (if needed)
                                            $extraHours = floor($minutes / 60);
                                            $hours += $extraHours;
                                            $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                                            // FORMAT HOURS AND MINUTES WITH LEADING ZEROS
                                            $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                                            $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                                            // FORMAT THE TOTAL DURATION AS H:i:s (destination_total_duration)
                                            $destination_total_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                            // CONVERT hotspot_siteseeing_travel_start_time TO SECONDS
                                            $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                            // ADD THE TOTAL DURATION TO THE START TIME (in seconds)
                                            $totalTimeInSeconds = $startTimeInSeconds + $totalDurationInSeconds;

                                            // CONVERT THE TOTAL TIME BACK TO H:i:s FORMAT (destination_travel_end_time)
                                            $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                            $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                            $hotspot_order++;
                                            $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$destination_total_duration", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                endif;
                                            endif;
                                        else :
                                            
                                            // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS
                                            $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` IN ('3','4') ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                                            endif;

                                            // DELETE THE PREVIOUSLY ADDED ALL THE ACTIVITY
                                            $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                                            // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                                            $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                                            endif;

                                            // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                                            $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                                            endif;

                                            // DELETE THE PREVIOUSLY ADDED ALL THE HOTSPOTS PARKING CHARGES
                                            $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                            if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                                            endif;

                                            $total_travelling_time = date('H:i:s', strtotime($duration_formatted));

                                            // Convert route end time to timestamp
                                            $route_end_timestamp = strtotime($route_end_time);

                                            // Convert total traveling time to seconds
                                            list($hours, $minutes, $seconds) = explode(':', $total_travelling_time);
                                            $travelling_seconds = $hours * 3600 + $minutes * 60 + $seconds;

                                            // Subtract the total traveling time from the route end time
                                            $adjusted_route_start_timestamp = $route_end_timestamp - $travelling_seconds;

                                            // Convert the adjusted time back to the desired format
                                            $adjusted_route_start_time = date('H:i:s', $adjusted_route_start_timestamp);

                                            $itinerary_route_details_arrFields = array('`route_start_time`', '`route_end_time`');
                                            $itinerary_route_details_arrValues = array("$adjusted_route_start_time", "$route_end_time");
                                            $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";

                                            //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                                            if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :

                                            endif;

                                            $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                            $hotspot_order++;
                                            $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "7", "$hotspot_order", "$total_travelling_time", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$adjusted_route_start_time", "$route_end_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                endif;
                                            endif;
                                        endif;
                                    else :

                                        $hotspot_order = $hotspot_order;

                                        if ($selected_DIRECT_DESTINATION_VISIT_CHECK != 1) :
                                            $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                                            $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                                            $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                                            // Determine the travel location type
                                            $travel_location_type = getTravelLocationType($previous_hotspot_location, $ending_location_name);

                                            // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                                            $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                                            $destination_traveling_time = $result['duration'];

                                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                                            preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                                            preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                                            $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                                            // Convert hotspot_siteseeing_travel_start_time to seconds
                                            $startTimeInSeconds = strtotime($hotspot_siteseeing_travel_start_time);

                                            // Convert destination_total_duration to seconds
                                            list($hours, $minutes, $seconds) = sscanf($duration_formatted, "%d:%d:%d");
                                            $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                                            // Add the duration and buffer time to the start time
                                            $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds;

                                            // Convert the total time back to H:i:s format
                                            $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                                            $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '5'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                                            $hotspot_order++;
                                            $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "5", "$hotspot_order", "$duration_formatted", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$hotspot_siteseeing_travel_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '5' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                                endif;
                                            endif;
                                            
                                            $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                                            $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_hotel_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "6", "$hotspot_order", "$destination_travel_end_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                                                $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                                                endif;
                                            endif;
                                        else:
                                            $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                            $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                                            $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                                            $route_hotspot_return_to_hotel_location_arrValues = array("$itinerary_plan_ID", "$itinerary_route_ID", "6", "$hotspot_order", "$destination_travel_end_time", "$destination_travel_end_time", "$logged_user_id", "1");

                                            if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                                                $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                                                $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                                                $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                                                endif;
                                            else :
                                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                                                endif;
                                            endif;
                                        endif;                                
                                    endif;
                                else :
                                    $delete_route_hotspot_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_ID' AND `itinerary_route_ID` = '$itinerary_route_ID' ";
                                    if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_route_hotspot_details_sqlwhere)) :
                                    endif;
                                    $response['route_end_time_reached'] = true;
                                endif;
                            endif;

                            $response['i_result'] = true;
                            $response['redirect_URL'] = 'latestitinerary.php?route=add&formtype=generate_itinerary&id=' . $itinerary_plan_ID.'&selected_group_type=1';
                            $response['itinerary_plan_ID'] = $itinerary_plan_ID;
                            $response['result_success'] = true;
                        else :
                            $response['i_result'] = false;
                            $response['result_success'] = false;
                        endif;
                    endforeach;
                endif;
            endif;

            if (in_array($itinerary_prefrence, array(1, 3))) :

                $delete_room_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID'";
                
                if (sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $delete_room_sqlwhere)) :
                    // Successfully deleted
                endif;

                //PREPARE THE HOTEL ALLOCATION FOR ITINEARY PLAN
                $get_result = get_HOTEL_ROOM_FOR_ITINERARY($itinerary_plan_ID);

                $overall_hotel_total_amount = NULL;
                $overall_hotel_total_tax = NULL;

                // Define arrays to track assigned extra beds and child beds for each route ID
                $assignedExtraBeds = array();
                $assignedChildWithBeds = array();
                $assignedChildWithOutBeds = array();

                $min_date = null;
                $max_date = null;
                $get_route_count=0;

                foreach ($get_result as $group_type => $group) {
                    foreach ($group as $date => $rooms) {
                                   
                        if ($min_date == null || $date < $min_date) {
                            $min_date = $date;
                        }
                        if ($max_date == null || $date > $max_date) {
                            $max_date = $date;
                        }
                        
                        $sequenceNo = 1;   // continuous counter across all rooms
                        foreach ($rooms as $room) {
                            $selected_GROUP_TYPE = str_replace('group', '', $group_type);
                            $selected_ITINEARY_PLAN = $room['itinerary_plan_id'];
                            $selected_ITINEARY_ROUTE_ID = $room['itinerary_route_id'];
                            $selected_ITINEARY_HOTEL_ID = $room['hotel_id'];
                            $selected_ITINEARY_ROOM_TYPE_ID = $room['room_type_id'];
                            $selected_ITINEARY_ROOM_ID = $room['room_id'];
                            $room_quantity = $room['room_quantity'];
                            $selected_ITINEARY_ROOM_RATE = $room['price_per_night'] ?? 0;
                            $selected_ITINEARY_ROOM_GST_TYPE = $room['gst_type'];
                            $selected_ITINEARY_ROOM_GST_PERCENTAGE = $room['gst_percentage'];
                            $selected_ITINEARY_ROOM_EXTRA_BED_COUNT = $room['total_extra_bed'];
                            $selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT = $room['total_child_with_bed'];
                            $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT = $room['total_child_without_bed'];
                            $selected_ITINEARY_ROOM_EXTRA_BED_CHRAGES = $room['extra_bed_charge'];
                            $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_CHARGES = $room['child_without_bed_charge'];
                            $selected_ITINEARY_ROOM_CHILD_WITH_BED_CHARGES = $room['child_with_bed_charge'];
                            $selected_ITINEARY_ROOM_DETAIL_IDS = $room['itinerary_plan_hotel_room_details_ID'];

                            if (isset($selected_ITINEARY_ROOM_DETAIL_IDS)) {
                                foreach ($selected_ITINEARY_ROOM_DETAIL_IDS as $selected_ITINEARY_ROOM_DETAIL_ID) {

                                    for($i = 0; $i<$room_quantity;$i++):
                                        // use $sequenceNo here
                                        $currentSequence = $sequenceNo;

                                        $selected_ITINEARY_ROOM_QTY = 1;
                                        // Check if this route ID has already been encountered
                                        if (!isset($assignedExtraBeds[$selected_ITINEARY_ROUTE_ID])) {
                                            $assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] = 0;
                                        }
                                        if (!isset($assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID])) {
                                            $assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] = 0;
                                        }
                                        if (!isset($assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID])) {
                                            $assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] = 0;
                                        }

                                        // Calculate the remaining extra beds and child beds allowed for this route ID
                                        $remainingExtraBeds = max($room['total_extra_bed'] - $assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE], 0);
                                        $remainingChildWithBeds = max($room['total_child_with_bed'] - $assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE], 0);
                                        $remainingChildWithOutBeds = max($room['total_child_without_bed'] - $assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE], 0);

                                        // Determine the number of extra beds and child beds to allocate
                                        $extraBedsToAllocate = min($remainingExtraBeds, $selected_ITINEARY_ROOM_QTY);
                                        $childWithBedsToAllocate = min($remainingChildWithBeds, $selected_ITINEARY_ROOM_QTY);
                                        $childWithOutBedsToAllocate = min($remainingChildWithOutBeds, $selected_ITINEARY_ROOM_QTY);

                                        // Update the counts of assigned extra beds and child beds for this route ID
                                        $assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] += $extraBedsToAllocate;
                                        $assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] += $childWithBedsToAllocate;
                                        $assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] += $childWithOutBedsToAllocate;

                                        // Assign extra beds and child beds based on availability
                                        /* $selected_ITINEARY_ROOM_EXTRA_BED_COUNT = $extraBedsToAllocate;
                                        $selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT = $childWithBedsToAllocate;
                                        $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT = $childWithOutBedsToAllocate; */

                                        $selected_ITINEARY_ROOM_EXTRA_BED_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'extra_bed_count');
                                        $selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'child_with_bed_count');
                                        $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'child_without_bed_count');

                                        // Calculate charges based on assigned extra beds and child beds
                                        $selected_ITINEARY_ROOM_EXTRA_BED_CHRAGES = $room['extra_bed_charge'] * $selected_ITINEARY_ROOM_EXTRA_BED_COUNT;
                                        $selected_ITINEARY_ROOM_CHILD_WITH_BED_CHARGES = $room['child_with_bed_charge'] * $selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT;
                                        $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_CHARGES = $room['child_without_bed_charge'] * $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT;

                                        $selected_ITINEARY_ROOM_BREAKFAST_COST = $room['hotel_breafast_cost'];
                                        $selected_ITINEARY_ROOM_LUNCH_COST = $room['hotel_lunch_cost'];
                                        $selected_ITINEARY_ROOM_DINNER_COST = $room['hotel_dinner_cost'];
                                        $selected_ITINEARY_ROOM_PERSON_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'total_count');

                                        /* $selected_ITINEARY_ROOM_BREAKFAST_CHARGES =  $selected_ITINEARY_ROOM_BREAKFAST_COST *$selected_ITINEARY_ROOM_PERSON_COUNT;
                                        $selected_ITINEARY_ROOM_LUNCH_CHARGES = $selected_ITINEARY_ROOM_LUNCH_COST *$selected_ITINEARY_ROOM_PERSON_COUNT;
                                        $selected_ITINEARY_ROOM_DINNER_CHARGES = $selected_ITINEARY_ROOM_DINNER_COST *$selected_ITINEARY_ROOM_PERSON_COUNT; */

                                        /*$selected_ITINEARY_ROOM_BREAKFAST_CHARGES = $room['total_hotel_breakfast_cost'];
                                        $selected_ITINEARY_ROOM_LUNCH_CHARGES = $room['total_hotel_lunch_cost'];
                                        $selected_ITINEARY_ROOM_DINNER_CHARGES = $room['total_hotel_dinner_cost'];*/

                                        $selected_ITINEARY_ROOM_ROUTE_DATE = $room['itinerary_route_date'];
                                        $selected_ITINEARY_ROOM_HOTEL_CATEGORY = $room['hotel_category'];
                                        $selected_ITINEARY_ROOM_BREAKFAST_REQUIRED = $room['meal_plan_breakfast'];
                                        $selected_ITINEARY_ROOM_LUNCH_REQUIRED = $room['meal_plan_lunch'];
                                        $selected_ITINEARY_ROOM_DINNER_REQUIRED = $room['meal_plan_dinner'];
                                        /* $selected_ITINEARY_ROOM_QTY = $room['room_quantity']; */

                                        $get_night_details=get_ITINERARY_PLAN_DETAILS($selected_ITINEARY_PLAN,'no_of_nights');  

                                        // Set dinner cost to 0 for the maximum route ID
                                        if (($selected_ITINEARY_ROOM_ROUTE_DATE == $max_date && $get_night_details==0) || ($selected_ITINEARY_ROOM_ROUTE_DATE == $max_date && $get_route_count >1)) {
                                            $selected_ITINEARY_ROOM_DINNER_REQUIRED = 0;
                                            $selected_ITINEARY_ROOM_DINNER_COST = 0; // Assuming 0 means not required
                                        }
                                        
                                        $selected_ITINEARY_ROOM_BREAKFAST_CHARGES = ($selected_ITINEARY_ROOM_BREAKFAST_REQUIRED == 1) ? ($selected_ITINEARY_ROOM_BREAKFAST_COST *$selected_ITINEARY_ROOM_PERSON_COUNT) : 0;
                                        $selected_ITINEARY_ROOM_LUNCH_CHARGES = ($selected_ITINEARY_ROOM_LUNCH_REQUIRED == 1) ? ($selected_ITINEARY_ROOM_LUNCH_COST *$selected_ITINEARY_ROOM_PERSON_COUNT) : 0;
                                        $selected_ITINEARY_ROOM_DINNER_CHARGES = ($selected_ITINEARY_ROOM_DINNER_REQUIRED == 1) ? ($selected_ITINEARY_ROOM_DINNER_COST *$selected_ITINEARY_ROOM_PERSON_COUNT) : 0;

                                        $TOTAL_NEW_ROOM_COST = $selected_ITINEARY_ROOM_RATE * $selected_ITINEARY_ROOM_QTY;

                                        if ($TOTAL_NEW_ROOM_COST > 0) :
                                            if ($selected_ITINEARY_ROOM_GST_TYPE == 1) :
                                                // For Inclusive GST
                                                $new_room_tax_amt = (($TOTAL_NEW_ROOM_COST * $selected_ITINEARY_ROOM_GST_PERCENTAGE) / 100);
                                                $new_room_amount = ($TOTAL_NEW_ROOM_COST - $new_room_tax_amt);
                                            elseif ($selected_ITINEARY_ROOM_GST_TYPE == 2) :
                                                // For Exclusive GST
                                                $new_room_tax_amt = ($TOTAL_NEW_ROOM_COST * $selected_ITINEARY_ROOM_GST_PERCENTAGE / 100);
                                                $new_room_amount = $TOTAL_NEW_ROOM_COST;
                                            endif;
                                        else :
                                            $new_room_tax_amt = 0;
                                            $new_room_amount = 0;
                                        endif;

                                        // SQL operations
                                        $select_itineary_plan_hotel_room_data = sqlQUERY_LABEL("SELECT SUM(`room_qty`) AS TOTAL_ROOM_QTY FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$selected_ITINEARY_PLAN' AND `itinerary_route_id` = '$selected_ITINEARY_ROUTE_ID' AND `itinerary_route_date` = '$selected_ITINEARY_ROOM_ROUTE_DATE' AND `group_type` = '$selected_GROUP_TYPE'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                        while ($fetch_itineary_plan_hotel_room_data = sqlFETCHARRAY_LABEL($select_itineary_plan_hotel_room_data)) :
                                            $get_total_room_qty = $fetch_itineary_plan_hotel_room_data['TOTAL_ROOM_QTY'];
                                        endwhile;

                                        $total_preferred_room_count = get_ITINERARY_PLAN_DETAILS($selected_ITINEARY_PLAN, 'preferred_room_count');

                                        $total_remaining_room_count = $total_preferred_room_count - $get_total_room_qty;

                                        $hotel_room_arrFields = array(
                                            '`itinerary_plan_id`',
                                            '`itinerary_route_id`',
                                            '`hotel_id`',
                                            '`room_type_id`',
                                            '`room_id`',
                                            '`room_qty`',
                                            '`room_rate`',
                                            '`gst_type`',
                                            '`gst_percentage`',
                                            '`extra_bed_count`',
                                            '`extra_bed_rate`',
                                            '`child_with_bed_count`',
                                            '`child_with_bed_charges`',
                                            '`child_without_bed_count`',
                                            '`child_without_bed_charges`',
                                            '`breakfast_required`',
                                            '`lunch_required`',
                                            '`dinner_required`',
                                            '`breakfast_cost_per_person`',
                                            '`lunch_cost_per_person`',
                                            '`dinner_cost_per_person`',
                                            '`total_breafast_cost`',
                                            '`total_lunch_cost`',
                                            '`total_dinner_cost`',
                                            '`total_room_cost`',
                                            '`total_room_gst_amount`',
                                            '`itinerary_route_date`',
                                            '`createdby`',
                                            '`status`',
                                            '`group_type`'
                                        );

                                        $hotel_room_arrValues = array(
                                            "$selected_ITINEARY_PLAN",
                                            "$selected_ITINEARY_ROUTE_ID",
                                            "$selected_ITINEARY_HOTEL_ID",
                                            "$selected_ITINEARY_ROOM_TYPE_ID",
                                            "$selected_ITINEARY_ROOM_ID",
                                            "$selected_ITINEARY_ROOM_QTY",
                                            "$selected_ITINEARY_ROOM_RATE",
                                            "$selected_ITINEARY_ROOM_GST_TYPE",
                                            "$selected_ITINEARY_ROOM_GST_PERCENTAGE",
                                            "$selected_ITINEARY_ROOM_EXTRA_BED_COUNT",
                                            "$selected_ITINEARY_ROOM_EXTRA_BED_CHRAGES",
                                            "$selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT",
                                            "$selected_ITINEARY_ROOM_CHILD_WITH_BED_CHARGES",
                                            "$selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT",
                                            "$selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_CHARGES",
                                            "$selected_ITINEARY_ROOM_BREAKFAST_REQUIRED",
                                            "$selected_ITINEARY_ROOM_LUNCH_REQUIRED",
                                            "$selected_ITINEARY_ROOM_DINNER_REQUIRED",
                                            "$selected_ITINEARY_ROOM_BREAKFAST_COST",
                                            "$selected_ITINEARY_ROOM_LUNCH_COST",
                                            "$selected_ITINEARY_ROOM_DINNER_COST",
                                            "$selected_ITINEARY_ROOM_BREAKFAST_CHARGES",
                                            "$selected_ITINEARY_ROOM_LUNCH_CHARGES",
                                            "$selected_ITINEARY_ROOM_DINNER_CHARGES",
                                            "$new_room_amount",
                                            "$new_room_tax_amt",
                                            "$selected_ITINEARY_ROOM_ROUTE_DATE",
                                            "$logged_user_id",
                                            "1",
                                            "$selected_GROUP_TYPE"
                                        );

                                        if ($total_remaining_room_count > 0) {
                                            if (sqlACTIONS(
                                                "INSERT",
                                                "dvi_itinerary_plan_hotel_room_details",
                                                $hotel_room_arrFields,
                                                $hotel_room_arrValues,
                                                ''
                                            )) {
                                                // Successfully inserted
                                            }
                                        }
                                        // increment global sequence
                                        $sequenceNo++;
                                    endfor;
                                }
                            } else {

                                for($i = 0; $i<$room_quantity;$i++):
                                    // use $sequenceNo here
                                    $currentSequence = $sequenceNo;

                                    $selected_ITINEARY_ROOM_QTY = 1;
                                    // Check if this route ID has already been encountered
                                    if (!isset($assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE])) {
                                        $assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] = 0;
                                    }
                                    if (!isset($assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE])) {
                                        $assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] = 0;
                                    }
                                    if (!isset($assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE])) {
                                        $assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] = 0;
                                    }

                                    // Calculate the remaining extra beds and child beds allowed for this route ID
                                    $remainingExtraBeds = max($room['total_extra_bed'] - $assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE], 0);
                                    $remainingChildWithBeds = max($room['total_child_with_bed'] - $assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE], 0);
                                    $remainingChildWithOutBeds = max($room['total_child_without_bed'] - $assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE], 0);

                                    // Determine the number of extra beds and child beds to allocate
                                    $extraBedsToAllocate = min($remainingExtraBeds, $selected_ITINEARY_ROOM_QTY);
                                    $childWithBedsToAllocate = min($remainingChildWithBeds, $selected_ITINEARY_ROOM_QTY);
                                    $childWithOutBedsToAllocate = min($remainingChildWithOutBeds, $selected_ITINEARY_ROOM_QTY);

                                    // Update the counts of assigned extra beds and child beds for this route ID
                                    $assignedExtraBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] += $extraBedsToAllocate;
                                    $assignedChildWithBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] += $childWithBedsToAllocate;
                                    $assignedChildWithOutBeds[$selected_ITINEARY_ROUTE_ID][$selected_GROUP_TYPE] += $childWithOutBedsToAllocate;

                                    // Assign extra beds and child beds based on availability
                                    //$selected_ITINEARY_ROOM_EXTRA_BED_COUNT = $extraBedsToAllocate;
                                    //$selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT = $childWithBedsToAllocate;
                                    //$selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT = $childWithOutBedsToAllocate;
                                    
                                    $selected_ITINEARY_ROOM_EXTRA_BED_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'extra_bed_count');
                                    $selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'child_with_bed_count');
                                    $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'child_without_bed_count');

                                    // Calculate charges based on assigned extra beds and child beds
                                    $selected_ITINEARY_ROOM_EXTRA_BED_CHRAGES = $room['extra_bed_charge'] * $selected_ITINEARY_ROOM_EXTRA_BED_COUNT;
                                    $selected_ITINEARY_ROOM_CHILD_WITH_BED_CHARGES = $room['child_with_bed_charge'] * $selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT;
                                    $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_CHARGES = $room['child_without_bed_charge'] * $selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT;

                                    $selected_ITINEARY_ROOM_BREAKFAST_COST = $room['hotel_breafast_cost'];
                                    $selected_ITINEARY_ROOM_LUNCH_COST = $room['hotel_lunch_cost'];
                                    $selected_ITINEARY_ROOM_DINNER_COST = $room['hotel_dinner_cost'];

                                    $selected_ITINEARY_ROOM_PERSON_COUNT = getTRAVELLER_COUNT_DETAILS_IN_EACH_ROOM($selected_ITINEARY_PLAN, $currentSequence, 'total_count');
                                    
                                   /* $selected_ITINEARY_ROOM_BREAKFAST_CHARGES = $room['total_hotel_breakfast_cost'];
                                    $selected_ITINEARY_ROOM_LUNCH_CHARGES = $room['total_hotel_lunch_cost'];
                                    $selected_ITINEARY_ROOM_DINNER_CHARGES = $room['total_hotel_dinner_cost'];*/

                                    $selected_ITINEARY_ROOM_ROUTE_DATE = $room['itinerary_route_date'];
                                    $selected_ITINEARY_ROOM_HOTEL_CATEGORY = $room['hotel_category'];
                                    $selected_ITINEARY_ROOM_BREAKFAST_REQUIRED = $room['meal_plan_breakfast'];
                                    $selected_ITINEARY_ROOM_LUNCH_REQUIRED = $room['meal_plan_lunch'];
                                    $selected_ITINEARY_ROOM_DINNER_REQUIRED = $room['meal_plan_dinner'];
                                    //$selected_ITINEARY_ROOM_QTY = $room['room_quantity'];

                                    $get_night_details=get_ITINERARY_PLAN_DETAILS($selected_ITINEARY_PLAN,'no_of_nights');

                                    // Set dinner cost to 0 for the maximum route ID
                                    if (($selected_ITINEARY_ROOM_ROUTE_DATE == $max_date && $get_night_details==0) || ($selected_ITINEARY_ROOM_ROUTE_DATE == $max_date && $get_route_count >1)) {
                                        $selected_ITINEARY_ROOM_DINNER_REQUIRED = 0;
                                        $selected_ITINEARY_ROOM_DINNER_COST = 0; // Assuming 0 means not required
                                    }
                                                                            
                                    $selected_ITINEARY_ROOM_BREAKFAST_CHARGES = ($selected_ITINEARY_ROOM_BREAKFAST_REQUIRED == 1) ? ($selected_ITINEARY_ROOM_BREAKFAST_COST *$selected_ITINEARY_ROOM_PERSON_COUNT) : 0;
                                    $selected_ITINEARY_ROOM_LUNCH_CHARGES = ($selected_ITINEARY_ROOM_LUNCH_REQUIRED == 1) ? ($selected_ITINEARY_ROOM_LUNCH_COST *$selected_ITINEARY_ROOM_PERSON_COUNT) : 0;
                                    $selected_ITINEARY_ROOM_DINNER_CHARGES = ($selected_ITINEARY_ROOM_DINNER_REQUIRED == 1) ? ($selected_ITINEARY_ROOM_DINNER_COST *$selected_ITINEARY_ROOM_PERSON_COUNT) : 0;

                                    $TOTAL_NEW_ROOM_COST = $selected_ITINEARY_ROOM_RATE * $selected_ITINEARY_ROOM_QTY;

                                    if ($TOTAL_NEW_ROOM_COST > 0) :
                                        if ($selected_ITINEARY_ROOM_GST_TYPE == 1) :
                                            // For Inclusive GST
                                            $new_room_tax_amt = (($TOTAL_NEW_ROOM_COST * $selected_ITINEARY_ROOM_GST_PERCENTAGE) / 100);
                                            $new_room_amount = ($TOTAL_NEW_ROOM_COST - $new_room_tax_amt);
                                        elseif ($selected_ITINEARY_ROOM_GST_TYPE == 2) :
                                            // For Exclusive GST
                                            $new_room_tax_amt = ($TOTAL_NEW_ROOM_COST * $selected_ITINEARY_ROOM_GST_PERCENTAGE / 100);
                                            $new_room_amount = $TOTAL_NEW_ROOM_COST;
                                        endif;
                                    else :
                                        $new_room_tax_amt = 0;
                                        $new_room_amount = 0;
                                    endif;

                                    // SQL operations
                                    $select_itineary_plan_hotel_room_data = sqlQUERY_LABEL("SELECT SUM(`room_qty`) AS TOTAL_ROOM_QTY FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$selected_ITINEARY_PLAN' AND `itinerary_route_id` = '$selected_ITINEARY_ROUTE_ID' AND `itinerary_route_date` = '$selected_ITINEARY_ROOM_ROUTE_DATE' AND `group_type` = '$selected_GROUP_TYPE'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                    while ($fetch_itineary_plan_hotel_room_data = sqlFETCHARRAY_LABEL($select_itineary_plan_hotel_room_data)) :
                                        $get_total_room_qty = $fetch_itineary_plan_hotel_room_data['TOTAL_ROOM_QTY'];
                                    endwhile;

                                    $total_preferred_room_count = get_ITINERARY_PLAN_DETAILS($selected_ITINEARY_PLAN, 'preferred_room_count');

                                    $total_remaining_room_count = $total_preferred_room_count - $get_total_room_qty;

                                    $hotel_room_arrFields = array(
                                        '`itinerary_plan_id`',
                                        '`itinerary_route_id`',
                                        '`hotel_id`',
                                        '`room_type_id`',
                                        '`room_id`',
                                        '`room_qty`',
                                        '`room_rate`',
                                        '`gst_type`',
                                        '`gst_percentage`',
                                        '`extra_bed_count`',
                                        '`extra_bed_rate`',
                                        '`child_with_bed_count`',
                                        '`child_with_bed_charges`',
                                        '`child_without_bed_count`',
                                        '`child_without_bed_charges`',
                                        '`breakfast_required`',
                                        '`lunch_required`',
                                        '`dinner_required`',
                                        '`breakfast_cost_per_person`',
                                        '`lunch_cost_per_person`',
                                        '`dinner_cost_per_person`',
                                        '`total_breafast_cost`',
                                        '`total_lunch_cost`',
                                        '`total_dinner_cost`',
                                        '`total_room_cost`',
                                        '`total_room_gst_amount`',
                                        '`itinerary_route_date`',
                                        '`createdby`',
                                        '`status`',
                                        '`group_type`'
                                    );

                                    $hotel_room_arrValues = array(
                                        "$selected_ITINEARY_PLAN",
                                        "$selected_ITINEARY_ROUTE_ID",
                                        "$selected_ITINEARY_HOTEL_ID",
                                        "$selected_ITINEARY_ROOM_TYPE_ID",
                                        "$selected_ITINEARY_ROOM_ID",
                                        "$selected_ITINEARY_ROOM_QTY",
                                        "$selected_ITINEARY_ROOM_RATE",
                                        "$selected_ITINEARY_ROOM_GST_TYPE",
                                        "$selected_ITINEARY_ROOM_GST_PERCENTAGE",
                                        "$selected_ITINEARY_ROOM_EXTRA_BED_COUNT",
                                        "$selected_ITINEARY_ROOM_EXTRA_BED_CHRAGES",
                                        "$selected_ITINEARY_ROOM_CHILD_WITH_BED_COUNT",
                                        "$selected_ITINEARY_ROOM_CHILD_WITH_BED_CHARGES",
                                        "$selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_COUNT",
                                        "$selected_ITINEARY_ROOM_CHILD_WITHOUT_BED_CHARGES",
                                        "$selected_ITINEARY_ROOM_BREAKFAST_REQUIRED",
                                        "$selected_ITINEARY_ROOM_LUNCH_REQUIRED",
                                        "$selected_ITINEARY_ROOM_DINNER_REQUIRED",
                                        "$selected_ITINEARY_ROOM_BREAKFAST_COST",
                                        "$selected_ITINEARY_ROOM_LUNCH_COST",
                                        "$selected_ITINEARY_ROOM_DINNER_COST",
                                        "$selected_ITINEARY_ROOM_BREAKFAST_CHARGES",
                                        "$selected_ITINEARY_ROOM_LUNCH_CHARGES",
                                        "$selected_ITINEARY_ROOM_DINNER_CHARGES",
                                        "$new_room_amount",
                                        "$new_room_tax_amt",
                                        "$selected_ITINEARY_ROOM_ROUTE_DATE",
                                        "$logged_user_id",
                                        "1",
                                        "$selected_GROUP_TYPE"
                                    );

                                    if ($total_remaining_room_count > 0) {
                                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_room_details", $hotel_room_arrFields, $hotel_room_arrValues, '')) {
                                            // Successfully inserted
                                        }
                                    }
                                    // increment global sequence
                                    $sequenceNo++;
                                endfor;
                            }
                        }
                    }
                }

                // Initialize an array to store aggregated values for each date
                $aggregated_hotel_details = array();

                // Fetch hotel room details for the specific itinerary plan
                $selected_hotel_room_price_availability = sqlQUERY_LABEL("SELECT `itinerary_route_id`, `room_rate` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID' AND `group_type` = '$selected_GROUP_TYPE'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
                // Loop through the fetched hotel room details
                while ($fetch_room_data = sqlFETCHARRAY_LABEL($selected_hotel_room_price_availability)) :
                    $itinerary_route_id = $fetch_room_data['itinerary_route_id'];
                    $room_rate[$itinerary_route_id][] = $fetch_room_data['room_rate'];
                endwhile;

                $zero_rate_itinerary_routes = array();

                if (isset($room_rate)) :
                    foreach ($room_rate as $route_id => $rates) {
                        foreach ($rates as $rate) {
                            if ($rate == 0) {
                                $zero_rate_itinerary_routes[] = $route_id;
                                break; // Exit the inner loop once a zero rate is found for this route_id
                            }
                        }
                    }
                endif;

                if (!empty($zero_rate_itinerary_routes)) :
                    $get_itinerary_route_id = implode(", ", $zero_rate_itinerary_routes);
                    $update_itinerary_plan_hotel_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_room_details` SET `hotel_id` = '0', `room_type_id` = '0', `room_id` = '0', `room_rate` = '0', `extra_bed_rate` = '0', `child_without_bed_charges` = '0', `child_with_bed_charges` = '0', `total_breafast_cost` = '0', `total_lunch_cost` = '0', `total_dinner_cost` = '0', `total_room_cost` = '0', `total_room_gst_amount` = '0' WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` IN ($get_itinerary_route_id) AND `group_type` = '$selected_GROUP_TYPE'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
                endif;

                // Fetch hotel room details for the specific itinerary plan
                $selected_hotel_room_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_qty`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `extra_bed_rate`, `child_without_bed_charges`, `child_with_bed_charges`, `total_room_cost`, `total_room_gst_amount`, `gst_type`, `gst_percentage`, `group_type` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());

                // Loop through the fetched hotel room details
                while ($fetch_data = sqlFETCHARRAY_LABEL($selected_hotel_room_query)) :
                    // Extract data from the fetched row
                    $itinerary_plan_hotel_room_details_ID = $fetch_data['itinerary_plan_hotel_room_details_ID'];
                    $itinerary_plan_id = $fetch_data['itinerary_plan_id'];
                    $itinerary_route_id = $fetch_data['itinerary_route_id'];
                    $itinerary_route_date = $fetch_data['itinerary_route_date'];
                    /* $gst_type = $fetch_data['gst_type'];
                    $gst_percentage = $fetch_data['gst_percentage']; */
                    $group_type = $fetch_data['group_type'];

                    // Create a combined key for date and group type
                    $date_group_key = $itinerary_route_date . '_' . $group_type;

                    // Check if aggregated data for this date and group type already exists
                    if (!isset($aggregated_hotel_details[$date_group_key])) :
                        // If not, initialize aggregated values
                        $aggregated_hotel_details[$date_group_key] = array(
                            'total_breafast_cost' => 0,
                            'total_lunch_cost' => 0,
                            'total_dinner_cost' => 0,
                            'extra_bed_rate' => 0,
                            'child_without_bed_charges' => 0,
                            'child_with_bed_charges' => 0,
                            'total_room_cost' => 0,
                            'total_room_gst_amount' => 0,
                            'hotel_ids' => array(),
                            'room_qty' => 0,
                            'gst_type' => $fetch_data['gst_type'],
                            'gst_percentage' => $fetch_data['gst_percentage'],
                            'itinerary_plan_id' => $fetch_data['itinerary_plan_id'],
                            'itinerary_route_id' => $fetch_data['itinerary_route_id'],
                            'itinerary_route_date' => $fetch_data['itinerary_route_date'],
                            'itinerary_plan_hotel_room_details_ID' => $fetch_data['itinerary_plan_hotel_room_details_ID'],
                            'group_type' => $fetch_data['group_type']  // Store group type
                        );
                    endif;

                    // Add room details to the aggregated values
                    $aggregated_hotel_details[$date_group_key]['total_breafast_cost'] += $fetch_data['total_breafast_cost'];
                    $aggregated_hotel_details[$date_group_key]['total_lunch_cost'] += $fetch_data['total_lunch_cost'];
                    $aggregated_hotel_details[$date_group_key]['total_dinner_cost'] += $fetch_data['total_dinner_cost'];
                    $aggregated_hotel_details[$date_group_key]['total_room_cost'] += $fetch_data['total_room_cost'];
                    $aggregated_hotel_details[$date_group_key]['extra_bed_rate'] += $fetch_data['extra_bed_rate'];
                    $aggregated_hotel_details[$date_group_key]['child_without_bed_charges'] += $fetch_data['child_without_bed_charges'];
                    $aggregated_hotel_details[$date_group_key]['child_with_bed_charges'] += $fetch_data['child_with_bed_charges'];
                    $aggregated_hotel_details[$date_group_key]['total_room_gst_amount'] += $fetch_data['total_room_gst_amount'];
                    $aggregated_hotel_details[$date_group_key]['hotel_ids'][] = $fetch_data['hotel_id'];
                    $aggregated_hotel_details[$date_group_key]['room_qty'] += $fetch_data['room_qty'];
                endwhile;

                // Loop through the aggregated hotel details for each date
                foreach ($aggregated_hotel_details as $date_group_key => $aggregated_data) :
                    // Extract aggregated values
                    $total_breafast_cost = $aggregated_data['total_breafast_cost'];
                    $total_lunch_cost = $aggregated_data['total_lunch_cost'];
                    $total_dinner_cost = $aggregated_data['total_dinner_cost'];
                    $total_room_cost = $aggregated_data['total_room_cost'];
                    $extra_bed_rate = $aggregated_data['extra_bed_rate'];
                    $child_without_bed_charges = $aggregated_data['child_without_bed_charges'];
                    $child_with_bed_charges = $aggregated_data['child_with_bed_charges'];
                    $total_room_gst_amount = $aggregated_data['total_room_gst_amount'];
                    $room_qty = $aggregated_data['room_qty'];
                    $gst_type = $aggregated_data['gst_type'];
                    $gst_percentage = $aggregated_data['gst_percentage'];
                    $hotel_ids = $aggregated_data['hotel_ids'];
                    $itinerary_plan_id = $aggregated_data['itinerary_plan_id'];
                    $itinerary_route_date = $aggregated_data['itinerary_route_date'];
                    $itinerary_route_id = $aggregated_data['itinerary_route_id'];
                    $itinerary_plan_hotel_room_details_ID = $aggregated_data['itinerary_plan_hotel_room_details_ID'];
                    $group_types = $aggregated_data['group_type'];
                    $next_visiting_location = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'next_visiting_location');

                    $TOTAL_AMENITIES_COST = get_ITINEARYHOTEL_AMENITIES_DETAILS($group_types, $itinerary_plan_id, $itinerary_route_id, 'TOTAL_AMENITIES_COST');
                    $TOTAL_AMENITIES_GST_COST = get_ITINEARYHOTEL_AMENITIES_DETAILS($group_types, $itinerary_plan_id, $itinerary_route_id, 'TOTAL_AMENITIES_GST_COST');

                    if ($total_room_cost > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $new_room_tax_amt = ((($total_room_cost + $total_room_gst_amount) * $gst_percentage) / 100);
                            $new_room_amount = (($total_room_cost + $total_room_gst_amount) - ($new_room_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $new_room_tax_amt = ($total_room_cost * $gst_percentage / 100);
                            $new_room_amount = $total_room_cost;
                        endif;
                    else :
                        $new_room_tax_amt = 0;
                        $new_room_amount = $total_room_cost;
                    endif;

                    if ($total_breafast_cost > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $total_breafast_cost_tax_amt = ((($total_breafast_cost) * $gst_percentage) / 100);
                            $total_breafast_cost_amount = (($total_breafast_cost) - ($total_breafast_cost_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $total_breafast_cost_tax_amt = ($total_breafast_cost * $gst_percentage / 100);
                            $total_breafast_cost_amount = $total_breafast_cost;
                        endif;
                    else :
                        $total_breafast_cost_amount = $total_breafast_cost;
                        $total_breafast_cost_tax_amt = 0;
                    endif;

                    if ($total_lunch_cost > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $total_lunch_cost_tax_amt = ((($total_lunch_cost) * $gst_percentage) / 100);
                            $total_lunch_cost_amount = (($total_lunch_cost) - ($total_lunch_cost_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $total_lunch_cost_tax_amt = ($total_lunch_cost * $gst_percentage / 100);
                            $total_lunch_cost_amount = $total_lunch_cost;
                        endif;
                    else :
                        $total_lunch_cost_amount = $total_lunch_cost;
                        $total_lunch_cost_tax_amt = 0;
                    endif;

                    if ($total_dinner_cost > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $total_dinner_cost_tax_amt = ((($total_dinner_cost) * $gst_percentage) / 100);
                            $total_dinner_cost_amount = (($total_dinner_cost) - ($total_dinner_cost_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $total_dinner_cost_tax_amt = ($total_dinner_cost * $gst_percentage / 100);
                            $total_dinner_cost_amount = $total_dinner_cost;
                        endif;
                    else :
                        $total_dinner_cost_amount = $total_dinner_cost;
                        $total_dinner_cost_tax_amt = 0;
                    endif;

                    if ($extra_bed_rate > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $total_extra_bed_cost_tax_amt = ((($extra_bed_rate) * $gst_percentage) / 100);
                            $total_extra_bed_cost_amount = (($extra_bed_rate) - ($total_extra_bed_cost_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $total_extra_bed_cost_tax_amt = ($extra_bed_rate * $gst_percentage / 100);
                            $total_extra_bed_cost_amount = $extra_bed_rate;
                        endif;
                    else :
                        $total_extra_bed_cost_amount = $extra_bed_rate;
                        $total_extra_bed_cost_tax_amt = 0;
                    endif;

                    if ($child_with_bed_charges > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $child_with_bed_charges_tax_amt = ((($child_with_bed_charges) * $gst_percentage) / 100);
                            $child_with_bed_charges_amount = (($child_with_bed_charges) - ($child_with_bed_charges_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $child_with_bed_charges_tax_amt = ($child_with_bed_charges * $gst_percentage / 100);
                            $child_with_bed_charges_amount = $child_with_bed_charges;
                        endif;
                    else :
                        $child_with_bed_charges_amount = $child_with_bed_charges;
                        $child_with_bed_charges_tax_amt = 0;
                    endif;

                    if ($child_without_bed_charges > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $child_without_bed_charges_tax_amt = ((($child_without_bed_charges) * $gst_percentage) / 100);
                            $child_without_bed_charges_amount = (($child_without_bed_charges) - ($child_without_bed_charges_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $child_without_bed_charges_tax_amt = ($child_without_bed_charges * $gst_percentage / 100);
                            $child_without_bed_charges_amount = $child_without_bed_charges;
                        endif;
                    else :
                        $child_without_bed_charges_amount = $child_without_bed_charges;
                        $child_without_bed_charges_tax_amt = 0;
                    endif;

                    // Assuming all rooms have the same hotel ID, so we just take the first one
                    $hotel_id = reset($hotel_ids);

                    // Fetch hotel details
                    $selected_hotel_query = sqlQUERY_LABEL("SELECT `hotel_category`, `hotel_margin`,`hotel_margin_gst_type`, `hotel_margin_gst_percentage` FROM `dvi_hotel` WHERE `hotel_id` = '$hotel_id'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
                    $fetch_hotel_data = sqlFETCHARRAY_LABEL($selected_hotel_query);
                    $hotel_category = $fetch_hotel_data['hotel_category'];
                    $hotel_margin = $fetch_hotel_data['hotel_margin'];
                    $hotel_margin_gst_type = $fetch_hotel_data['hotel_margin_gst_type'];
                    $hotel_margin_gst_percentage = $fetch_hotel_data['hotel_margin_gst_percentage'];

                    $total_new_room_cost = $total_breafast_cost_amount +  $total_lunch_cost_amount + $total_dinner_cost_amount +   $new_room_amount +  $total_extra_bed_cost_amount + $child_with_bed_charges_amount +  $child_without_bed_charges_amount + $TOTAL_AMENITIES_COST;

                    if ($hotel_margin > 0 && $total_new_room_cost > 0) :
                        // Calculate hotel margin rate
                        $hotel_margin_rate = ($total_new_room_cost * $hotel_margin) / 100;
                    else :
                        $hotel_margin_rate = 0;
                    endif;

                    if ($hotel_margin_rate > 0 && $hotel_margin_gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($hotel_margin_gst_type == 1) :
                            // For Inclusive GST
                            $new_margin_tax_amt = (($hotel_margin_rate * $hotel_margin_gst_percentage) / 100);
                            $new_margin_amount = ($hotel_margin_rate - $new_margin_tax_amt);
                        elseif ($hotel_margin_gst_type == 2) :
                            // For Exclusive GST
                            $new_margin_tax_amt = ($hotel_margin_rate * $hotel_margin_gst_percentage / 100);
                            $new_margin_amount = $hotel_margin_rate;
                        endif;
                    else :
                        $new_margin_amount = $hotel_margin_rate;
                        $new_margin_tax_amt = 0;
                    endif;

                    // Calculate total number of persons and total hotel meal plan cost
                    $total_no_of_persons = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'total_person_count');
                    $total_hotel_meal_plan_cost = $total_breafast_cost + $total_lunch_cost + $total_dinner_cost;

                    if ($total_hotel_meal_plan_cost > 0 && $gst_percentage > 0) :
                        // Calculate new margin amount and room tax amount based on GST type
                        if ($gst_type == 1) :
                            // For Inclusive GST
                            $total_hotel_meal_plan_cost_tax_amt = ((($total_hotel_meal_plan_cost) * $gst_percentage) / 100);
                            $total_hotel_meal_plan_cost_amount = (($total_hotel_meal_plan_cost) - ($total_hotel_meal_plan_cost_tax_amt));
                        elseif ($gst_type == 2) :
                            // For Exclusive GST
                            $total_hotel_meal_plan_cost_tax_amt = ($total_hotel_meal_plan_cost * $gst_percentage / 100);
                            $total_hotel_meal_plan_cost_amount = $total_hotel_meal_plan_cost;
                        endif;
                    else :
                        $total_hotel_meal_plan_cost_amount = $total_hotel_meal_plan_cost;
                        $total_hotel_meal_plan_cost_tax_amt = 0;
                    endif;

                    $total_hotel_cost = $total_breafast_cost_amount +  $total_lunch_cost_amount + $total_dinner_cost_amount +   $total_room_cost +  $total_extra_bed_cost_amount + $child_with_bed_charges_amount +  $child_without_bed_charges_amount + $TOTAL_AMENITIES_COST + $new_margin_amount;

                    $total_hotel_tax_amount = $total_breafast_cost_tax_amt + $total_lunch_cost_tax_amt + $total_dinner_cost_tax_amt + $new_room_tax_amt + $total_extra_bed_cost_tax_amt + $child_without_bed_charges_tax_amt + $child_with_bed_charges_tax_amt + $TOTAL_AMENITIES_GST_COST + $new_margin_tax_amt;

                    /* // Calculate total hotel cost and total hotel tax amount
                    $total_hotel_cost = $new_room_amount + $new_margin_amount;
                    $total_hotel_tax_amount = $new_room_tax_amt + $new_margin_tax_amt; */

                    if (!in_array($itinerary_route_id, $zero_rate_itinerary_routes)) :
                        $hotel_required = 1;
                    else :
                        $hotel_required = 0;
                        $new_margin_amount = 0;
                        $new_margin_tax_amt = 0;
                        $total_breafast_cost = 0;
                        $total_lunch_cost = 0;
                        $total_dinner_cost = 0;
                        $total_hotel_meal_plan_cost = 0;
                        $total_new_room_cost = 0;
                        $total_hotel_cost = 0;
                        $total_room_gst_amount = 0;
                        $total_hotel_tax_amount = 0;
                        $hotel_category = 0;
                        $hotel_id = 0;
                        $hotel_margin_gst_percentage = 0;
                        $hotel_margin = 0;
                        $hotel_margin_gst_type = 0;
                        $total_breafast_cost_amount = 0;
                        $total_lunch_cost_amount = 0;
                        $total_lunch_cost_tax_amt = 0;
                        $total_dinner_cost_amount = 0;
                        $total_dinner_cost_tax_amt = 0;
                        $total_hotel_meal_plan_cost_amount = 0;
                        $total_hotel_meal_plan_cost_tax_amt = 0;
                        $new_room_tax_amt = 0;
                        $total_extra_bed_cost_amount = 0;
                        $total_extra_bed_cost_tax_amt = 0;
                        $child_with_bed_charges_amount = 0;
                        $child_with_bed_charges_tax_amt = 0;
                        $child_without_bed_charges_amount = 0;
                        $child_without_bed_charges_tax_amt = 0;
                        $TOTAL_AMENITIES_COST = 0;
                        $TOTAL_AMENITIES_GST_COST = 0;
                    endif;

                    // Check if there is existing hotel details record for this date
                    $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_details_ID` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_types'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                    $select_itinerary_plan_hotel_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_data);

                    // Prepare fields and values for insertion or update
                    $hotel_arrFields = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location`', '`hotel_required`', '`hotel_category_id`', '`hotel_id`', '`hotel_margin_percentage`', '`hotel_margin_gst_type`', '`hotel_margin_gst_percentage`', '`hotel_margin_rate`', '`hotel_margin_rate_tax_amt`', '`hotel_breakfast_cost`', '`hotel_breakfast_cost_gst_amount`', '`hotel_lunch_cost`', '`hotel_lunch_cost_gst_amount`', '`hotel_dinner_cost`', '`hotel_dinner_cost_gst_amount`', '`total_no_of_persons`', '`total_hotel_meal_plan_cost`', '`total_hotel_meal_plan_cost_gst_amount`', '`total_no_of_rooms`', '`total_room_cost`', '`total_room_gst_amount`', '`total_extra_bed_cost`', '`total_extra_bed_cost_gst_amount`', '`total_childwith_bed_cost`', '`total_childwith_bed_cost_gst_amount`', '`total_childwithout_bed_cost`', '`total_childwithout_bed_cost_gst_amount`', '`total_hotel_cost`', '`total_hotel_tax_amount`', '`total_amenities_cost`', '`total_amenities_gst_amount`', '`createdby`', '`status`', '`group_type`');

                    $hotel_arrValues = array("$itinerary_plan_id", "$itinerary_route_id", "$itinerary_route_date", "$next_visiting_location", "$hotel_required", "$hotel_category", "$hotel_id", "$hotel_margin", "$hotel_margin_gst_type", "$hotel_margin_gst_percentage", "$new_margin_amount", "$new_margin_tax_amt", "$total_breafast_cost_amount", "$total_breafast_cost_tax_amt", "$total_lunch_cost_amount", "$total_lunch_cost_tax_amt", "$total_dinner_cost_amount", "$total_dinner_cost_tax_amt", "$total_no_of_persons", "$total_hotel_meal_plan_cost_amount", "$total_hotel_meal_plan_cost_tax_amt", "$room_qty", "$total_room_cost", "$total_room_gst_amount", "$total_extra_bed_cost_amount", "$total_extra_bed_cost_tax_amt", "$child_with_bed_charges_amount", "$child_with_bed_charges_tax_amt", "$child_without_bed_charges_amount", "$child_without_bed_charges_tax_amt", "$total_hotel_cost", "$total_hotel_tax_amount", "$TOTAL_AMENITIES_COST", "$TOTAL_AMENITIES_GST_COST", "$logged_user_id", "1", "$group_types");

                    // Insert or update hotel details based on the existence of the record
                    if ($select_itinerary_plan_hotel_count == 0) :
                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_details", $hotel_arrFields, $hotel_arrValues, '')) :
                            $itinerary_plan_hotel_details_ID = sqlINSERTID_LABEL();
                            $update_itinerary_plan_hotel_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_room_details` SET `itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_types'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
                        endif;
                    else :
                        $fetch_itinerary_plan_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_data);
                        $itinerary_plan_hotel_details_ID = $fetch_itinerary_plan_hotel_data['itinerary_plan_hotel_details_ID'];
                        $hotel_sqlwhere = " `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_ID' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_types' ";
                        if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $hotel_arrFields, $hotel_arrValues, $hotel_sqlwhere)) :
                            $update_itinerary_plan_hotel_details = sqlQUERY_LABEL("UPDATE `dvi_itinerary_plan_hotel_room_details` SET `itinerary_plan_hotel_details_id` = '$itinerary_plan_hotel_details_ID' WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_types'") or die("#1get_ITINERARY_PLAN_DETAILS: " . sqlERROR_LABEL());
                        endif;
                    endif;

                    if($hotel_id):
                        $hotel_hotspot_status = getHOTELDETAILS($hotel_id, 'hotel_hotspot_status');
                    else:
                        $hotel_hotspot_status = 1;
                    endif;
                    
                    if($hotel_hotspot_status == 0):
                        #REMOVE HOTSPOT FOR THAT PARTICULAR DAY
                        $delete_hotspots_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `item_type` IN ('3','4') ";
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_details", '', '', $delete_hotspots_sqlwhere)): 
                        endif;

                        // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ENTRY COST
                        $delete_hotspots_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_entry_cost_details", '', '', $delete_hotspots_entry_ticket_sqlwhere)): 
                        endif;

                        $delete_the_selected_hotspots_activity = sqlQUERY_LABEL("DELETE FROM `dvi_itinerary_route_activity_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_id' and `itinerary_route_ID` = '$itinerary_route_id'") or die("#1-UNABLE_TO_DELETE_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());

                        // DELETE THE PREVIOUSLY ADDED ALL THE TRAVELLER HOTSPOTS ACTIVITY ENTRY COST
                        $delete_hotspots_activity_entry_ticket_sqlwhere = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_route_id` = '$itinerary_route_ID' ";
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_activity_entry_cost_details", '', '', $delete_hotspots_activity_entry_ticket_sqlwhere)): 
                        endif;

                        $delete_hotspots_parking_charge_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' ";
                        if (sqlACTIONS("DELETE", "dvi_itinerary_route_hotspot_parking_charge", '', '', $delete_hotspots_parking_charge_sqlwhere)): 
                        endif;

                        $hotspot_order = 2;
                        $route_start_time = get_ITINEARY_ROUTE_HOTSPOT_DETAILS($itinerary_plan_id, $itinerary_route_id, 'get_starting_location_item_type_endtime');
                        
                        if(!$route_start_time):
                            $route_start_time = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'route_start_time');
                        endif;

                        $route_end_time = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'route_end_time');
                        $trip_end_date_and_time = get_ITINEARY_PLAN_DETAILS($itinerary_plan_id, 'trip_end_date_and_time');
                        $get_trip_end_date = date('Y-m-d', strtotime($trip_end_date_and_time));
                        $departure_location = get_ITINEARY_PLAN_DETAILS($itinerary_plan_id, 'departure_location');
                        $selected_NEXT_VISITING_PLACE = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'next_visiting_location');
                        $direct_to_next_visiting_place = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'direct_to_next_visiting_place');
                        $departure_type = get_ITINEARY_PLAN_DETAILS($itinerary_plan_id, 'departure_type');
                        $start_location_id = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id,'get_starting_location_id');

                        $staring_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_lattitude');
                        $staring_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'source_location_longitude');
                        $staring_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'SOURCE_LOCATION');

                        $get_last_route_id_from_this_itinerary_plan = sqlQUERY_LABEL("SELECT MAX(`itinerary_route_ID`) AS max_route_id FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' AND `itinerary_plan_ID` = '$itinerary_plan_id' ORDER BY `itinerary_route_ID` DESC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_ITINEARY_HOTSPOT_LIST:" . sqlERROR_LABEL());
                        $fetch_last_route_id_from_this_itineary_plan = sqlFETCHARRAY_LABEL($get_last_route_id_from_this_itinerary_plan);
                        $last_itinerary_route_ID = $fetch_last_route_id_from_this_itineary_plan['max_route_id'];
                        
                        //INSERT THE END OF THE TRIP DEPARTURE START TIME
                        if (trim($departure_location) == trim($selected_NEXT_VISITING_PLACE) && $last_itinerary_route_ID == $itinerary_route_id) :
                            //Determine the buffer time based on the departure_type [1 - By Flight | 2 - By Train | 3 - By Road]
                            switch ($departure_type):
                                    case 1: // By Flight
                                    $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                                    break;
                                case 2: // By Train
                                    $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                                    break;
                                case 3: // By Road
                                    $itinerary_travel_type_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                                    break;
                                default:
                                    $itinerary_travel_type_buffer_time = "00:00:00"; // Default to 0 if departure type is invalid
                            endswitch;
                            
                            $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                            $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                            $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                            // Determine the travel location type
                            $travel_location_type = getTravelLocationType($staring_location_name, $ending_location_name);

                            // CALCULATE THE DISTANCE AND DURATION TO THE END LOCATION
                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);

                            $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                            $destination_traveling_time = $result['duration'];

                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                            preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                            preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

                            // INITIALIZE HOURS AND MINUTES TO ZERO
                            $hours = isset($hoursMatch[1]) ? intval($hoursMatch[1]) : 0;
                            $minutes = isset($minutesMatch[1]) ? intval($minutesMatch[1]) : 0;

                            // CALCULATE EXTRA HOURS IF MINUTES EXCEED 59
                            $extraHours = floor($minutes / 60);
                            $hours += $extraHours;
                            $minutes %= 60; // REMAINING MINUTES AFTER ADDING TO HOURS

                            // OPTIONAL: FORMAT HOURS AND MINUTES WITH LEADING ZEROS (For Display Purposes)
                            $formattedHours = str_pad($hours, 2, '0', STR_PAD_LEFT);
                            $formattedMinutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);

                            // OPTIONAL: FORMAT THE TIME AS H:i:s (For Display Purposes)
                            $destination_total_duration = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                            // CALCULATE THE DURATION IN SECONDS DIRECTLY (NO NEED FOR FORMATTING AND BACK CONVERSION)
                            $totalSeconds = ($hours * 3600) + ($minutes * 60);

                            // ADD THE DURATION TO THE START TIME (IN SECONDS)
                            $startTimeInSeconds = strtotime($route_start_time);

                            // Calculate the total time in seconds (hotspot start time + travel duration)
                            $totalTimeInSeconds = $startTimeInSeconds + $totalSeconds;

                            // Convert the total time back to H:i:s format
                            $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                            $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `item_type` = '7'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                            $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                            $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                            $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_id", "$itinerary_route_id", "7", "$hotspot_order", "$destination_total_duration", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$route_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                            if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '7' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                endif;
                            else :
                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                endif;
                            endif;

                        else :
                            
                            $itinerary_travel_type_buffer_time = "00:00:00";

                            /* if ($direct_to_next_visiting_place != 1) : */
                            $ending_location_latitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_lattitude');
                            $ending_location_longtitude = getSTOREDLOCATIONDETAILS($start_location_id, 'destination_location_longitude');
                            $ending_location_name = getSTOREDLOCATIONDETAILS($start_location_id, 'DESTINATION_LOCATION');

                            // Determine the travel location type
                            $travel_location_type = getTravelLocationType($staring_location_name, $ending_location_name);

                            // CALULATE THE DISTANCE AND DURATION TO THE END LOCATION
                            $result = calculateDistanceAndDuration($staring_location_latitude, $staring_location_longtitude, $ending_location_latitude, $ending_location_longtitude, $travel_location_type);
                            
                            $destination_travelling_distance = number_format($result['distance'], 2, '.', '');
                            $destination_traveling_time = $result['duration'];

                            // EXTRACT THE HOURS AND MINUTES FROM THE DURATION STRING
                            preg_match('/(\d+) hour/', $destination_traveling_time, $hoursMatch);
                            preg_match('/(\d+) mins/', $destination_traveling_time, $minutesMatch);

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
                            $duration_formatted = sprintf('%02d:%02d:00', $formattedHours, $formattedMinutes);

                            // Convert hotspot_start_time to seconds
                            $startTimeInSeconds = strtotime($route_start_time);

                            // Convert destination_total_duration to seconds
                            list($hours, $minutes, $seconds) = sscanf($duration_formatted, "%d:%d:%d");
                            $durationInSeconds = $hours * 3600 + $minutes * 60 + $seconds;

                            // Add the duration and buffer time to the start time
                            $totalTimeInSeconds = $startTimeInSeconds + $durationInSeconds;

                            // Convert the total time back to H:i:s format
                            $destination_travel_end_time = date('H:i:s', $totalTimeInSeconds);

                            $select_itineary_hotspot_return_departure_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `item_type` = '5'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                            $select_itineary_hotspot_return_departure_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_departure_location_data);

                            $route_hotspot_return_to_departure_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_traveling_time`', '`itinerary_travel_type_buffer_time`', '`hotspot_travelling_distance`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                            $route_hotspot_return_to_departure_location_arrValues = array("$itinerary_plan_id", "$itinerary_route_id", "5", "$hotspot_order", "$duration_formatted", "$itinerary_travel_type_buffer_time", "$destination_travelling_distance", "$route_start_time", "$destination_travel_end_time", "$logged_user_id", "1");

                            if ($select_itineary_hotspot_return_departure_location_count > 0) :
                                $fetch_itineary_hotspot_direct_destination_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_departure_location_data);
                                $route_hotspot_ID = $fetch_itineary_hotspot_direct_destination_data['route_hotspot_ID'];

                                $route_hotspot_return_to_departure_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '5' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, $route_hotspot_return_to_departure_location_sqlwhere)) :
                                endif;
                            else :
                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_departure_location_arrFields, $route_hotspot_return_to_departure_location_arrValues, '')) :
                                endif;
                            endif;
                            $hotspot_start_time = $destination_travel_end_time;
                            /* endif; */

                            $select_itineary_hotspot_return_hotel_location_data = sqlQUERY_LABEL("SELECT `route_hotspot_ID` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `item_type` = '6'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                            $select_itineary_hotspot_return_hotel_location_count = sqlNUMOFROW_LABEL($select_itineary_hotspot_return_hotel_location_data);

                            $route_hotspot_return_to_hotel_location_arrFields = array('`itinerary_plan_ID`', '`itinerary_route_ID`', '`item_type`', '`hotspot_order`', '`hotspot_start_time`', '`hotspot_end_time`', '`createdby`', '`status`');

                            $route_hotspot_return_to_hotel_location_arrValues = array("$itinerary_plan_id", "$itinerary_route_id", "6", "$hotspot_order", "$hotspot_start_time", "$hotspot_start_time", "$logged_user_id", "1");

                            if ($select_itineary_hotspot_return_hotel_location_count > 0) :
                                $fetch_itineary_hotspot_return_hotel_data = sqlFETCHARRAY_LABEL($select_itineary_hotspot_return_hotel_location_data);
                                $route_hotspot_ID = $fetch_itineary_hotspot_return_hotel_data['route_hotspot_ID'];

                                $route_hotspot_return_to_hotel_location_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' AND `route_hotspot_ID` = '$route_hotspot_ID' AND `item_type` = '6' ";
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, $route_hotspot_return_to_hotel_location_sqlwhere)) :
                                endif;
                            else :
                                if (sqlACTIONS("INSERT", "dvi_itinerary_route_hotspot_details", $route_hotspot_return_to_hotel_location_arrFields, $route_hotspot_return_to_hotel_location_arrValues, '')) :
                                endif;
                            endif;

                            if ($hotspot_start_time >= $route_end_time) :
                                $itinerary_route_details_arrFields = array('`route_end_time`');
                                $itinerary_route_details_arrValues = array("$hotspot_start_time");
                                $itinerary_route_details_sqlwhere = " `itinerary_plan_ID` = '$itinerary_plan_id' AND `itinerary_route_ID` = '$itinerary_route_id' ";

                                //UPDATE ITINEARY ROUTE AND PLAN DETAILS
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $itinerary_route_details_arrFields, $itinerary_route_details_arrValues, $itinerary_route_details_sqlwhere)) :
                                endif;

                            endif;
                        endif;
                    endif;
                endforeach;
            endif;
        endif;

        echo json_encode($response);

   