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

/*ini_set('display_errors', 1);
ini_set('log_errors', 1);*/

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'select_hotels') :

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];
        $itinerary_preference = $_POST['itinerary_preference'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :

            //HOTEL
            if ($itinerary_preference == '1' || $itinerary_preference == '3') :

                $select_itinerary_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`,`meal_plan_breakfast`,`meal_plan_lunch`,`meal_plan_dinner` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_query);
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itinerary_query)) :
                    $arrival_location = $fetch_list_data['arrival_location'];
                    $departure_location = $fetch_list_data['departure_location'];
                    $trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
                    $trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
                    $expecting_budget = $fetch_list_data['expecting_budget'];
                    $no_of_routes = $fetch_list_data['no_of_routes'];
                    $no_of_days = $fetch_list_data["no_of_days"];
                    $no_of_nights = $fetch_list_data['no_of_nights'];
                    $total_adult = $fetch_list_data["total_adult"];
                    $total_children = $fetch_list_data["total_children"];
                    $total_infants = $fetch_list_data["total_infants"];
                    $preferred_room_count = $fetch_list_data["preferred_room_count"];
                    $total_extra_bed = $fetch_list_data["total_extra_bed"];
                    $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
                    $meal_plan_breakfast = $fetch_list_data["meal_plan_breakfast"];
                    $meal_plan_lunch = $fetch_list_data["meal_plan_lunch"];
                    $meal_plan_dinner = $fetch_list_data["meal_plan_dinner"];
                endwhile;

                //DELETE EXISTING HOTEL DETAILS
                $sqlWhere_hotel = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
                $delete_previous_plan_hotel_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_details", '', '', $sqlWhere_hotel);
                //DELETE EXISTING HOTEL ROOM DETAILS
                $sqlWhere_rooms = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
                $delete_previous_plan_room_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $sqlWhere_rooms);

                //FETCH ROUTE DETAILS FOR HOTEL SELECTION 
                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`,  `no_of_days`, `no_of_km`, `location_via_route`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);

                if ($total_itinerary_route_count > 0) :
                    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                        $itinerary_route_counter++;
                        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
                        $location_name = $fetch_itinerary_route_data['location_name'];
                        $location_id = $fetch_itinerary_route_data['location_id'];

                        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
                        $itinerary_route_year = date('Y', strtotime($itinerary_route_date));
                        $itinerary_route_monthFullName = date('F', strtotime($itinerary_route_date));
                        $itinerary_route_day = date('d', strtotime($itinerary_route_date));
                        $itinerary_route_day = ltrim($itinerary_route_day, '0');
                        $no_of_days = $fetch_itinerary_route_data['no_of_days'];
                        $no_of_km = $fetch_itinerary_route_data['no_of_km'];
                        $location_via_route = $fetch_itinerary_route_data['location_via_route'];
                        $next_visiting_location = $fetch_itinerary_route_data['next_visiting_location'];

                        $get_location_details = sqlQUERY_LABEL("SELECT `destination_location`,`location_ID`,`destination_location_lattitude`,`destination_location_longitude`,`source_location_state`,`destination_location_state` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                            while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                                $next_visiting_location_longitude = $fetch_location_data['destination_location_longitude'];
                                $next_visiting_location_latitude = $fetch_location_data['destination_location_lattitude'];
                                $destination_location_state = $fetch_location_data['destination_location_state'];
                                $source_location_state = $fetch_location_data['source_location_state'];
                            endwhile;
                        endif;

                        // SELECT HOTEL DETAILS
                        if ($itinerary_route_counter == $total_itinerary_route_count) :

                            $hotel_required = 0; //NO HOTEL REQUIRED
                            $next_visiting_location = $location_name;

                            $arrFields_hotel = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location`', '`hotel_required`',  '`createdby`', '`status`');

                            $arrValues_hotel = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date", "$next_visiting_location", "$hotel_required",  "$logged_user_id", "1");

                            //INSERT ROUTE DETAILS
                            if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, '')) :
                            endif;

                        else :

                            $hotel_required = 1; //HOTEL REQUIRED

                            //"SELECT `hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`, `hotel_address`, `hotel_pincode`, `hotel_longitude`, `hotel_latitude`,  ST_Distance(POINT('$next_visiting_location_longitude', '$next_visiting_location_latitude'),POINT(`hotel_longitude`, `hotel_latitude`)) AS distance FROM `dvi_hotel` WHERE `deleted` = '0' and `status` = '1' ORDER BY distance "


                            $select_hotel_details = sqlQUERY_LABEL("SELECT `hotel_id`,`hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`,`hotel_category`, `hotel_address`, `hotel_pincode`, `hotel_margin`,`hotel_breafast_cost`,`hotel_lunch_cost`,`hotel_dinner_cost`,`hotel_longitude`, `hotel_latitude`,  SQRT(POW(69.1 * (`hotel_latitude` - $next_visiting_location_latitude), 2) + POW(69.1 * ($next_visiting_location_longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) AS distance FROM `dvi_hotel` WHERE `deleted` = '0' and `status` = '1'  AND (`hotel_longitude` IS NOT NULL) AND (`hotel_latitude` IS NOT NULL) AND (SQRT(POW(69.1 * (`hotel_latitude` - $next_visiting_location_latitude), 2) + POW(69.1 * ($next_visiting_location_longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) <= 50) ORDER BY distance ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                            if (sqlNUMOFROW_LABEL($select_hotel_details) > 0) :

                                while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
                                    $hotel_id = $fetch_hotel_data['hotel_id'];
                                    $hotel_name = $fetch_hotel_data['hotel_name'];
                                    $hotel_place = $fetch_hotel_data['hotel_place'];
                                    $hotel_category_id = $fetch_hotel_data['hotel_category'];
                                    $hotel_category = getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label');
                                    $hotel_margin_percentage = $fetch_hotel_data['hotel_margin'];
                                    $hotel_breafast_cost = $fetch_hotel_data['hotel_breafast_cost'];
                                    $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                                    $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];
                                    $total_no_of_persons = $total_adult  + $total_children;

                                    $total_hotel_meal_plan_cost = 0;
                                    if ($meal_plan_breakfast == 1) :
                                        $total_hotel_meal_plan_cost += $hotel_breafast_cost;
                                    endif;

                                    if ($meal_plan_lunch == 1) :
                                        $total_hotel_meal_plan_cost += $hotel_lunch_cost;
                                    endif;
                                    if ($meal_plan_dinner == 1) :
                                        $total_hotel_meal_plan_cost += $hotel_dinner_cost;
                                    endif;

                                    $total_hotel_meal_plan_cost = ($total_hotel_meal_plan_cost) * $total_no_of_persons;

                                    $arrFields_hotel = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location`', '`hotel_required`', '`hotel_category_id`', '`hotel_id`', '`hotel_margin_percentage`', '`hotel_breakfast_cost`', '`hotel_lunch_cost`', '`hotel_dinner_cost`', '`total_no_of_persons`', '`total_hotel_meal_plan_cost`', '`total_no_of_rooms`', '`createdby`', '`status`');

                                    $arrValues_hotel = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date", "$next_visiting_location", "$hotel_required", "$hotel_category_id", "$hotel_id", "$hotel_margin_percentage",  "$hotel_breafast_cost", "$hotel_lunch_cost", "$hotel_dinner_cost", "$total_no_of_persons", "$total_hotel_meal_plan_cost", "$preferred_room_count", "$logged_user_id", "1");

                                    //INSERT HOTEL DETAILS
                                    if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, '')) :
                                        $itinerary_plan_hotel_details_id = sqlINSERTID_LABEL();

                                        //calculate room rate based on budget
                                        $cost_of_room = ($expecting_budget * (ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100)) / $no_of_nights;

                                        $PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET = $cost_of_room / $preferred_room_count;

                                        //FETCH ROOM DETAILS OF THE SELECTED HOTEL BASED ON THE BUDGET 
                                        $gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,R.`extra_bed_charge`,R.`child_with_bed_charge`,R.`child_without_bed_charge`, RP.`DAY_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' AND RP.`DAY_$itinerary_route_day`<= '$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET' AND R.`hotel_id`='$hotel_id' and R.`deleted` ='0' ORDER BY RP.`DAY_$itinerary_route_day` DESC LIMIT $preferred_room_count") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

                                        $total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);

                                        if ($total_room_count > 0) :
                                            $total_room_rate = 0;
                                            $room_count = 0;
                                            $total_room_rate_without_tax = 0;
                                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
                                                $room_count++;
                                                $room_ID = $fetch_room_data['room_ID'];
                                                $room_title = $fetch_room_data['room_title'];
                                                $room_type_id = $fetch_room_data['room_type_id'];
                                                $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                                $gst_type = $fetch_room_data['gst_type'];
                                                $gst_percentage = $fetch_room_data['gst_percentage'];
                                                $FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
                                                $child_with_bed_charge = $fetch_room_data['child_with_bed_charge'];

                                                if ($room_count == 1) :
                                                    $extra_bed_count =  $total_extra_bed;
                                                    // $child_bed_count = $total_child_bed;
                                                    $child_without_bed_charge = $fetch_room_data['child_without_bed_charge'];
                                                    $extra_bed_charge = $fetch_room_data['extra_bed_charge'] + $child_without_bed_charge;

                                                else :
                                                    $extra_bed_count =  0;
                                                    $extra_bed_charge = 0;
                                                endif;

                                                if ($gst_type == 1) :
                                                    // For Inclusive GST
                                                    //ROOM RATE
                                                    $roomRate_without_tax = $FIXED_ROOM_RATE / (1 + ($gst_percentage / 100));
                                                    $gst_amt = ($FIXED_ROOM_RATE - $roomRate_without_tax);
                                                    $roomRate_with_tax = $FIXED_ROOM_RATE;

                                                    //EXTRA BED RATE
                                                    if ($extra_bed_count > 0) :
                                                        $extrabedcharge = $extra_bed_charge * $extra_bed_count;
                                                        $extra_bed_charge_without_tax = $extrabedcharge / (1 + ($gst_percentage / 100));
                                                        $extrabed_gst_amt = ($extrabedcharge - $extra_bed_charge_without_tax);
                                                        $extra_bed_charge_with_tax = $extrabedcharge;
                                                    else :
                                                        $extra_bed_charge_with_tax = 0;
                                                        $extrabed_gst_amt = 0;
                                                        $extra_bed_charge_without_tax = 0;
                                                    endif;

                                                elseif ($gst_type == 2) :
                                                    // For Exclusive GST
                                                    //ROOM RATE
                                                    $roomRate_without_tax = $FIXED_ROOM_RATE;
                                                    $gst_amt = ($FIXED_ROOM_RATE * $gst_percentage / 100);
                                                    $roomRate_with_tax = $roomRate_without_tax + $gst_amt;

                                                    //EXTRA BED RATE
                                                    if ($extra_bed_count > 0) :

                                                        $extrabedcharge = $extra_bed_charge * $extra_bed_count;
                                                        $extra_bed_charge_without_tax = $extrabedcharge;
                                                        $extrabed_gst_amt = ($extrabedcharge * $gst_percentage / 100);
                                                        $extra_bed_charge_with_tax = $extra_bed_charge_without_tax + $extrabed_gst_amt;
                                                    else :
                                                        $extra_bed_charge_with_tax = 0;
                                                        $extrabed_gst_amt = 0;
                                                        $extra_bed_charge_without_tax = 0;
                                                    endif;

                                                endif;
                                                //RATE WITHOUT TAX
                                                $total_room_and_extrabed_rate_without_tax = $roomRate_without_tax + $extra_bed_charge_without_tax;

                                                $total_room_rate_without_tax = $total_room_rate_without_tax + $total_room_and_extrabed_rate_without_tax;
                                                //RATE WITH TAX
                                                $total_room_and_extrabed_rate_with_tax = $roomRate_with_tax + $extra_bed_charge_with_tax;

                                                $total_room_rate = $total_room_rate + $total_room_and_extrabed_rate_with_tax;

                                                $arrFields_room = array('`itinerary_plan_hotel_details_id`', '`itinerary_plan_id`', '`itinerary_route_id`', '`hotel_id`', '`room_type_id`', '`room_id`', '`room_rate`', '`gst_type`', '`gst_percentage`', '`gst_rate`', '`total_rate_of_room`', '`extra_bed_count`', '`extra_bed_rate`', '`total_extra_bed_rate`', '`extra_bed_gst_rate`', '`total_extra_bed_charge_with_tax`', '`createdby`', '`status`');

                                                $arrValues_room = array("$itinerary_plan_hotel_details_id", "$itinerary_plan_ID", "$itinerary_route_ID", "$hotel_id", "$room_type_id", "$room_ID", "$roomRate_without_tax", "$gst_type", "$gst_percentage", "$gst_amt", "$roomRate_with_tax", "$extra_bed_count", "$extra_bed_charge", "$extra_bed_charge_without_tax", "$extrabed_gst_amt", "$extra_bed_charge_with_tax", "$logged_user_id", "1");

                                                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_room_details", $arrFields_room, $arrValues_room, '')) :
                                                endif;

                                            endwhile;
                                        endif;
                                    endif;

                                endwhile;

                                //UPDATE TOTAL ROOM RATE IN HOTEL DETAILS TABLE

                                $hotel_margin_rate = $total_room_rate_without_tax * ($hotel_margin_percentage / 100);

                                $arrFields_hotel_details = array('`total_room_rate`', '`hotel_margin_rate`');
                                $arrValues_hotel_details = array("$total_room_rate", "$hotel_margin_rate");
                                $sqlWhere_hotel_details = " `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_id' ";
                                //UPDATE DETAILS
                                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel_details, $arrValues_hotel_details, $sqlWhere_hotel_details)) :
                                endif;

                            endif;
                        endif;

                    endwhile;
                endif;

                $response['result'] = true;
            endif;

            //VEHICLE
            if ($itinerary_preference == '2' || $itinerary_preference == '3') :

                //DELETE EXISTING VEHICLE DETAILS
                $sqlWhere_vehicle = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
                $delete_previous_plan_vehicle_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_vehicle_details", '', '', $sqlWhere_vehicle);

                // $sqlWhere_vehicle = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
                $delete_previous_plan_vehicle_summary = sqlACTIONS("DELETE", "dvi_itinerary_plan_vendor_summary", '', '', $sqlWhere_vehicle);


                //COLLECT VENDOR DETAILS IN SORTED ORDER (ASCENDING)
                $VENDER_DETAILS = getITINERARYVEHICLELIST($itinerary_plan_ID);

                if (!in_array(false, $VENDER_DETAILS['vehicle_available'])) :
                    //INSERT VENDOR VEHICLE WITH LOWEST PRICE DATA
                    $select_itineary_route_plan_info = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_km`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' and `status` = '1' and `deleted` = '0' ORDER BY `itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                    $total_no_of_itineary_plan_details = sqlNUMOFROW_LABEL($select_itineary_route_plan_info);
                    if ($total_no_of_itineary_plan_details > 0) :

                        $overall_total_trip_cost = 0;
                        $overall_total_vehicle_gst_tax_amt = 0;
                        $overall_total_driver_charge = 0;
                        $overall_total_driver_gst_tax_amt = 0;
                        $overall_total_permit_cost = 0;
                        $overall_total_vehicle_parking_charge = 0;
                        $overall_total_vehicle_toll_charge = 0;
                        $route_count = 0;
                        $overall_total_extra_km_charge = 0;

                        $TOTAL_DISTANCE = 0;
                        $TOTAL_TIME_TAKEN = "00:00:00";
                        $route_perday_km = getROUTECONFIGURATION('route_perday_km');
                        $TOTAL_ALLOWED_KM = $route_perday_km * $total_no_of_itineary_plan_details;

                        while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_info)) :
                            $route_count++;
                            $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                            $location_id = $fetch_itineary_route_data['location_id'];
                            $location_name = $fetch_itineary_route_data['location_name'];
                            $itinerary_route_date_DB_format = $fetch_itineary_route_data['itinerary_route_date'];
                            $itinerary_route_date = dateformat_datepicker($fetch_itineary_route_data['itinerary_route_date']);
                            $no_of_km = $fetch_itineary_route_data['no_of_km'];
                            $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                            $day = date('j', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                            $year = date('Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                            $month = date('F', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                            $location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_latitude', $location_id);
                            $location_longtitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_longtitude', $location_id);

                            $next_visiting_location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_latitude', $location_id);
                            $next_visiting_location_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_longitude', $location_id);

                            $source_location_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');


                            $select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT  `vehicle_count`, `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details`  WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND  `status` = '1' and `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                            $total_no_of_vehicle_selected = sqlNUMOFROW_LABEL($select_itineary_vehicle_details);
                            if ($total_no_of_vehicle_selected > 0) :
                                while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_details)) :

                                    $vehicletypeid = $fetch_vehicle_data['vehicle_type_id'];
                                    $vehicletype_title = getVEHICLETYPE($vehicletypeid, 'get_vehicle_type_title');
                                    $vehicle_count = $fetch_vehicle_data['vehicle_count'];
                                    $vehicle_occupancy = getOCCUPANCY($vehicletypeid, 'get_occupancy');

                                    //SELECT VENDOR AND VEHICLE WITH LOWEST PRICE
                                    //AND VEHICLE.`vehicle_location_id` IN ($location_ids_string)

                                    $selected_vendor_id = $VENDER_DETAILS['vendor_id'][0];
                                    $selected_vendor_branch_id = $VENDER_DETAILS['vendor_branch_id'][0];
                                    $selected_vehicle_type_id = $VENDER_DETAILS['vehicle_type_id'][0];
                                    $selected_vehicle_id = $VENDER_DETAILS['vehicle_id'][0];

                                    $select_itineary_vehicle_cost_calculation = sqlQUERY_LABEL(
                                        "SELECT VEHICLE_TYPES.`vehicle_type_id`, VEHICLE_TYPES.`driver_batta`, VEHICLE_TYPES.`food_cost`, VEHICLE_TYPES.`accomodation_cost`, VEHICLE_TYPES.`extra_cost`, VEHICLE_TYPES.`driver_early_morning_charges`, VEHICLE_TYPES.`driver_evening_charges`,VEHICLE.`vehicle_type_id`,VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`,VEHICLE.`vehicle_location_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`registration_number`, VEHICLE.`vehicle_fc_expiry_date`, VEHICLE.`insurance_end_date`, VEHICLE.`owner_city`,VEHICLE.`extra_km_charge` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VEHICLE_TYPES ON (VEHICLE.`vehicle_type_id` = VEHICLE_TYPES.`vendor_vehicle_type_ID` AND VEHICLE.`vendor_id`=VEHICLE_TYPES.`vendor_id`) WHERE VEHICLE.`vehicle_fc_expiry_date` >= CURRENT_DATE() AND VEHICLE.`insurance_end_date` >= CURRENT_DATE() AND VEHICLE.`status` = '1' and VEHICLE.`deleted` = '0' AND VEHICLE_TYPES.`vehicle_type_id`='$vehicletypeid' AND VEHICLE.`vehicle_type_id` = '$selected_vehicle_type_id' AND VEHICLE.`vehicle_id`='$selected_vehicle_id' AND VEHICLE.`vendor_id`='$selected_vendor_id' AND VEHICLE.`vendor_branch_id`='$selected_vendor_branch_id' "
                                    ) or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                                    $total_no_of_select_vehicle_details = sqlNUMOFROW_LABEL($select_itineary_vehicle_cost_calculation);

                                    if ($total_no_of_select_vehicle_details > 0) :

                                        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_cost_calculation)) :
                                            $vendor_vehicle_count++;

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

                                            $vehicle_type_id = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicletypeid, 'get_vendor_vehicle_type_ID');
                                            $vehicle_state = substr($registration_number, 0, 2);
                                            $vehicle_location_id = $fetch_list_data['vehicle_location_id'];
                                            $extra_km_charge = $fetch_list_data['extra_km_charge'];
                                            //DRIVER COST
                                            $driver_batta = $fetch_list_data['driver_batta'];
                                            $driver_accomodation_cost = $fetch_list_data['accomodation_cost'];
                                            $driver_extra_cost = $fetch_list_data['extra_cost'];
                                            $driver_food_cost = $fetch_list_data['food_cost'];
                                            $driver_early_morning_charges = $fetch_list_data['driver_early_morning_charges'];
                                            $driver_evening_charges = $fetch_list_data['driver_evening_charges'];

                                            $vehicle_orign = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
                                            $vehicle_orign_location_latitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_latitude');
                                            $vehicle_orign_location_longtitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_longtitude');

                                            $select_vehicle_permit_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_code`='$state_code' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            while ($fetch_vehicle_state = sqlFETCHARRAY_LABEL($select_vehicle_permit_state)) :
                                                $vehicle_state_id = $fetch_vehicle_state['permit_state_id'];
                                            endwhile;


                                            //VEHICLE CHARGE CALCULATION
                                            $RUNNINGTIME = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_TRAVEL_TIME');
                                            $RUNNING_TIME = sprintf('%02d:%02d:00', ...explode(':', $RUNNINGTIME));

                                            $RUNNING_DISTANCE =
                                                getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE');

                                            $SIGHT_SEEING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TIME');

                                            $SIGHT_SEEING_DISTANCE =
                                                getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_DISTANCE');

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
                                                $TOTAL_RUNNING_KM = $RUNNING_DISTANCE;
                                            endif;

                                            $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_DISTANCE;
                                            $TOTAL_KM = ceil($TOTAL_KM);

                                            //TOTAL TIME
                                            // Convert time durations to seconds
                                            $TOTAL_RUNNING_TIME_IN_SECONDS = strtotime($TOTAL_RUNNING_TIME) - strtotime('00:00:00');
                                            $SIGHT_SEEING_TIME_IN_SECONDS = strtotime($SIGHT_SEEING_TIME) - strtotime('00:00:00');

                                            $totalSeconds1 = $TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS;

                                            $TOTAL_TIME = gmdate('H:i:s', $totalSeconds1);
                                            // echo $TOTAL_TIME . "---" . $TOTAL_KM . "<br>";
                                            // echo $vehicle_city_name . "---" . $source_location_city . "<br>";

                                            //COST CALCULATION

                                            if ($vehicle_city_name == $source_location_city) :
                                                $trip_cost_type = '1';
                                                //LOCAL TRIP
                                                //echo  $TOTAL_TIME . "<br>";
                                                $time_parts = explode(':', $TOTAL_TIME);
                                                $TOTAL_TIME_hours = intval($time_parts[0]);
                                                $TOTAL_TIME_minutes = intval($time_parts[1]);

                                                // Round the total time based on minutes
                                                if ($TOTAL_TIME_minutes < 30) :
                                                    $TOTAL_HOURS =  $TOTAL_TIME_hours;
                                                else :
                                                    $TOTAL_HOURS = $TOTAL_TIME_hours + 1;
                                                endif;

                                                $hours_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_hour_limit', $vendor_id, $TOTAL_HOURS);

                                                $km_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_km_limit', $vendor_id, $TOTAL_HOURS, $TOTAL_KM);
                                                $kms_limit = getTIMELIMIT($km_time_limit_id, 'km_limit', $vendor_id);

                                                if ($km_time_limit_id == $hours_time_limit_id) :
                                                    $time_limit_id = $km_time_limit_id;

                                                    $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
                                                    $total_trip_cost = $trip_cost * $vehicle_count;
                                                elseif ($km_time_limit_id > $hours_time_limit_id) :
                                                    //IF KM IS GREATER
                                                    $time_limit_id = $km_time_limit_id;

                                                    $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);

                                                    $total_trip_cost = $trip_cost * $vehicle_count;
                                                elseif ($km_time_limit_id < $hours_time_limit_id) :
                                                    //IF TIME IS GREATER
                                                    $time_limit_id = $hours_time_limit_id;

                                                    $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
                                                    $total_trip_cost = $trip_cost * $vehicle_count;
                                                endif;

                                            //echo $total_trip_cost . "<br>";
                                            else :
                                                $trip_cost_type = '2'; //OUTSTATION TRIP
                                                $kms_limit_id = getKMLIMIT($vehicle_type_id, 'get_kms_limit_id', $vendor_id);
                                                $kms_limit = getKMLIMIT($vehicle_type_id, 'get_kms_limit', $vendor_id);

                                                $trip_cost = getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $kms_limit_id, $userID);

                                                $total_trip_cost = $trip_cost * $vehicle_count;
                                            endif;

                                            //CALCULATE GST FOR VEHICLE CHARGES
                                            if ($vendor_branch_gst_type == 1) :
                                                // For Inclusive GST
                                                $new_total_trip_cost = $total_trip_cost / (1 + ($branch_gst_percentage / 100));

                                                $vehicle_gst_tax_amt = ($total_trip_cost - $new_total_trip_cost);

                                            elseif ($vendor_branch_gst_type == 2) :
                                                // For Exclusive GST
                                                $new_total_trip_cost = $total_trip_cost;
                                                $vehicle_gst_tax_amt = ($total_trip_cost * $branch_gst_percentage / 100);
                                            endif;

                                            $overall_total_trip_cost += $new_total_trip_cost;
                                            $overall_total_vehicle_gst_tax_amt += $vehicle_gst_tax_amt;
                                            // $overall_total_extra_km_charge += $total_extra_km_charge;

                                            //DRIVER COST CALCULATION
                                            $driver_charges = ($driver_batta +  $driver_accomodation_cost + $driver_extra_cost + $driver_food_cost) * $vehicle_count;
                                            //CALCULATE GST FOR DRIVER CHARGES
                                            if ($vendor_branch_gst_type == 1) :
                                                // For Inclusive GST
                                                $new_driver_charges = $driver_charges / (1 + ($branch_gst_percentage / 100));

                                                $driver_gst_tax_amt = ($driver_charges - $new_driver_charges);

                                            elseif ($vendor_branch_gst_type == 2) :
                                                // For Exclusive GST
                                                $new_driver_charges = $driver_charges;
                                                $driver_gst_tax_amt = ($driver_charges * $branch_gst_percentage / 100);
                                            endif;

                                            $overall_total_driver_charge += $new_driver_charges;
                                            $overall_total_driver_gst_tax_amt += $driver_gst_tax_amt;

                                            // PERMIT COST CALCULATION
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
                                            $permit_cost =  $permit_cost * $vehicle_count;
                                            $overall_total_permit_cost += $permit_cost;

                                            //TOLL CHARGE CALCULATION
                                            $VEHICLE_TOLL_CHARGE = getVEHICLE_TOLL_CHARGES($vehicletypeid, $location_id) * $vehicle_count;
                                            $overall_total_vehicle_toll_charge += $VEHICLE_TOLL_CHARGE;

                                            //PARKING CHARGE CALCULATION
                                            $VEHICLE_PARKING_CHARGE = getHOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'total_hotspot_parking_charges') * $vehicle_count;
                                            $overall_total_vehicle_parking_charge += $VEHICLE_PARKING_CHARGE;

                                            $total_vendor_cost_per_day = $new_total_trip_cost  + $new_driver_charges +  $permit_cost + $VEHICLE_PARKING_CHARGE + $VEHICLE_TOLL_CHARGE;
                                            $total_tax_per_day = $vehicle_gst_tax_amt + $driver_gst_tax_amt;

                                            $total_vendor_cost_per_day_with_tax = $total_vendor_cost_per_day + $total_tax_per_day;

                                            $TOTAL_DISTANCE = $TOTAL_DISTANCE + $TOTAL_KM;

                                            //TOTAL TIME TAKEN
                                            // Convert time durations to seconds
                                            $TOTAL_TIME_TAKEN_IN_SECONDS = strtotime($TOTAL_TIME_TAKEN) - strtotime('00:00:00');

                                            $totalSeconds3 = $TOTAL_TIME_TAKEN_IN_SECONDS + ($TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS);

                                            $TOTAL_TIME_TAKEN = gmdate('H:i:s', $totalSeconds3);

                                            $arrFields_vehicle = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location_from`', '`itinerary_route_location_to`', '`vendor_id`', '`vendor_branch_id`', '`vehile_type_id`', '`vehicle_id`', '`vehicle_count`', '`running_kms`', '`sight_seeing_kms`', '`total_kms_travelled`', '`traveling_time`', '`sight_seeing_time`', '`total_time`', '`cost_type`',  '`local_time_limit_id`', '`outstation_km_limit_id`', '`extra_km_charge`',  '`driver_bhatta`', '`driver_food_cost`', '`driver_accomodation_cost`', '`extra_cost`', '`total_driver_cost`', '`total_driver_gst_amt`', '`toll_charge`', '`vehicle_parking_charge`',  '`vehicle_permit_cost`', '`vehicle_gst_type`', '`vehicle_gst_percentage`', 'vehicle_per_day_cost', '`vehicle_gst_amount`', '`total_vehicle_cost`', '`total_vehicle_cost_with_gst`',  '`createdby`', '`status`');


                                            $arrValues_vehicle = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date_DB_format", "$location_name", "$next_visiting_location", "$vendor_id", "$vendor_branch_id", "$vehicle_type_id", "$vehicle_id", "$vehicle_count", "$TOTAL_RUNNING_KM", "$SIGHT_SEEING_DISTANCE", "$TOTAL_KM", "$TOTAL_RUNNING_TIME", "$SIGHT_SEEING_TIME", "$TOTAL_TIME", "$trip_cost_type", "$time_limit_id", "$kms_limit_id", "$extra_km_charge", "$driver_batta", "$driver_food_cost", "$driver_accomodation_cost", "$driver_extra_cost", "$new_driver_charges", "$driver_gst_tax_amt", "$VEHICLE_TOLL_CHARGE", "$VEHICLE_PARKING_CHARGE", "$permit_cost", "$vendor_branch_gst_type", "$branch_gst_percentage", "$new_total_trip_cost", "$vehicle_gst_tax_amt", "$total_vendor_cost_per_day", "$total_vendor_cost_per_day_with_tax", "$logged_user_id", "1");

                                            //INSERT ROUTE VENDOR VEHICLE DETAILS
                                            if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_vehicle_details", $arrFields_vehicle, $arrValues_vehicle, '')) :

                                            endif;

                                        endwhile;
                                    endif;

                                endwhile;

                            endif;

                        endwhile;

                        //CALCULATE VEHICLE SUMMARY
                        $select_vehicle_summary = sqlQUERY_LABEL("SELECT  `itinerary_plan_id`,   `vendor_branch_id`,`vehicle_id`,`vendor_id`,`vehile_type_id`,`vehicle_count`,SUM(`total_kms_travelled`) AS total_kms_travelled , SEC_TO_TIME(SUM(TIME_TO_SEC(`total_time`))) AS total_time, SUM(`total_driver_cost`) AS total_driver_cost,  SUM(`total_driver_gst_amt`) AS total_driver_gst_amt,   SUM(`toll_charge`) AS toll_charge,   SUM(`vehicle_parking_charge`) AS vehicle_parking_charge,  SUM(`vehicle_permit_cost`) AS vehicle_permit_cost,  SUM(`vehicle_gst_amount`) AS vehicle_gst_amount, SUM(`vehicle_per_day_cost`) AS vehicle_per_day_cost,  SUM(`total_vehicle_cost`) AS total_vehicle_cost, SUM(`total_vehicle_cost_with_gst`) AS total_vehicle_cost_with_gst  FROM `dvi_itinerary_plan_vendor_vehicle_details`  WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `status` = '1'  AND `deleted` = '0' GROUP BY `vehile_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        $vehicle_summary_cout = sqlNUMOFROW_LABEL($select_vehicle_summary);

                        while ($fetch_summary_data = sqlFETCHARRAY_LABEL($select_vehicle_summary)) :

                            //$itinerary_plan_id = $fetch_summary_data['itinerary_plan_id'];
                            $itinerary_route_id = $fetch_summary_data['itinerary_route_id'];
                            $vendor_branch_id = $fetch_summary_data['vendor_branch_id'];
                            $vendor_id = $fetch_summary_data['vendor_id'];
                            $vehicle_id = $fetch_summary_data['vehicle_id'];
                            $vehicle_type_id = $fetch_summary_data['vehile_type_id'];
                            $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
                            $vehicle_count = $fetch_summary_data['vehicle_count'];
                            $total_kms_travelled = $fetch_summary_data['total_kms_travelled'];
                            $total_time = $fetch_summary_data['total_time'];

                            $vehicle_permit_cost = $fetch_summary_data['vehicle_permit_cost'];
                            $toll_charge = $fetch_summary_data['toll_charge'];
                            $vehicle_parking_charge = $fetch_summary_data['vehicle_parking_charge'];
                            $total_driver_cost = $fetch_summary_data['total_driver_cost'];
                            $total_driver_gst_amt =  $fetch_summary_data['total_driver_gst_amt'];
                            $vehicle_per_day_cost = $fetch_summary_data['vehicle_per_day_cost'];

                            $total_vehicle_cost = $fetch_summary_data['total_vehicle_cost'];
                            $vehicle_gst_amount = $fetch_summary_data['vehicle_gst_amount'];

                            //EXTRA KM CHARGE
                            $extra_km_charges = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_extra_km_charge');
                            if ($TOTAL_ALLOWED_KM < $total_kms_travelled) :
                                $extra_km = $TOTAL_ALLOWED_KM - $total_kms_travelled;
                                $total_extra_km_charge =  ($extra_km * $extra_km_charges) * $vehicle_count;
                            else :
                                $extra_km = 0;
                                $total_extra_km_charge = 0;
                            endif;

                            $total_vehicle_cost_with_gst = $fetch_summary_data['total_vehicle_cost_with_gst'] + $total_extra_km_charge;

                            $margin_percentage = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_margin_percentage');
                            $VENDOR_MARGIN = $total_vehicle_cost_with_gst * ($margin_percentage / 100);

                            $grand_total_vehicle_cost = $total_vehicle_cost_with_gst + $VENDOR_MARGIN;

                            $arrFields_vehicle_summary = array('`itinerary_plan_id`', '`vendor_id`', '`vehicle_type_id`', '`vehicle_id`', '`vendor_branch_id`', '`vehicle_count`', '`total_kms`', '`total_time`', '`total_vehicle_permit_cost`', '`total_toll_charge`', '`total_vehicle_parking_charge`', '`total_driver_cost`', '`total_driver_gst_amt`', '`total_vehicle_per_day_cost`', '`total_vehicle_cost`', '`extra_km`', '`extra_km_charge`', '`total_extra_km_charge`', '`total_vehicle_gst_amount`',  '`total_vehicle_cost_with_gst`', '`vendor_margin_percentage`', '`vendor_margin`', '`grand_total`',  '`createdby`', '`status`');


                            $arrValues_vehicle_summary = array("$itinerary_plan_ID",  "$vendor_id", "$vehicle_type_id", "$vehicle_id", "$vendor_branch_id",   "$vehicle_count", "$total_kms_travelled", "$total_time", "$vehicle_permit_cost", "$toll_charge", "$vehicle_parking_charge", "$total_driver_cost", "$total_driver_gst_amt", "$vehicle_per_day_cost", "$total_vehicle_cost", "$extra_km", "$extra_km_charges", "$total_extra_km_charge", "$vehicle_gst_amount", "$total_vehicle_cost_with_gst", "$margin_percentage", "$vendor_margin", "$grand_total_vehicle_cost", "$logged_user_id", "1");

                            //INSERT VENDOR SUMMARY DETAILS
                            if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_summary", $arrFields_vehicle_summary, $arrValues_vehicle_summary, '')) :
                                $itinerary_plan_vendor_summary_id = sqlINSERTID_LABEL();

                                $arrFields_vendor_details = array('`itinerary_plan_vendor_summary_id`');
                                $arrValues_vendor_details = array("$itinerary_plan_vendor_summary_id");
                                $sqlWhere_vendor_details = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehile_type_id` = '$vehicle_type_id' ";
                                //UPDATE DETAILS
                                if (sqlACTIONS(
                                    "UPDATE",
                                    "dvi_itinerary_plan_vendor_vehicle_details",
                                    $arrFields_vendor_details,
                                    $arrValues_vendor_details,
                                    $sqlWhere_vendor_details
                                )) :
                                endif;

                            endif;


                        endwhile;

                        $response['result'] = true;
                    endif;
                else :
                    $response['result'] = false;
                    $vehicle_type_name = implode(' ', $VENDER_DETAILS['vehicle_type_not_available']);
                    $response['vehicle_type'] = $vehicle_type_name;
                endif;

            endif;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'show_itinerary_plan_hotel_details') :

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :

            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`,`meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $arrival_location = $fetch_list_data['arrival_location'];
                $departure_location = $fetch_list_data['departure_location'];
                $trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
                $trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
                $expecting_budget = $fetch_list_data['expecting_budget'];
                $no_of_routes = $fetch_list_data['no_of_routes'];
                $no_of_days = $fetch_list_data["no_of_days"];
                $no_of_nights = $fetch_list_data['no_of_nights'];
                $total_adult = $fetch_list_data["total_adult"];
                $total_children = $fetch_list_data["total_children"];
                $total_infants = $fetch_list_data["total_infants"];
                $preferred_room_count = $fetch_list_data["preferred_room_count"];
                $total_extra_bed = $fetch_list_data["total_extra_bed"];
                $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
                $meal_plan_breakfast = $fetch_list_data["meal_plan_breakfast"];
                $meal_plan_lunch = $fetch_list_data["meal_plan_lunch"];
                $meal_plan_dinner = $fetch_list_data["meal_plan_dinner"];
            endwhile;
?>

            <div class="hotel_list">

                <div class="row align-items-center justify-content-between mb-4">
                    <div class="col">
                        <h5 class="card-header p-0 text-primary mb-0">Hotel List</h5>
                    </div>
                    <div class="col text-end">
                        <div class="mb-0"><strong>Total Amount For Hotel</strong><span class="badge bg-primary bg-glow ms-2">₹ <span id="total_amount_for_hotel">0</span></span></div>
                    </div>
                </div>

                <?php
                $select_itinerary_hotel_details = sqlQUERY_LABEL("SELECT H.`itinerary_plan_hotel_details_ID`,  H.`itinerary_plan_id`,  H.`itinerary_route_id`,  H.`itinerary_route_date`,  H.`itinerary_route_location`,  H.`hotel_required`,  H.`hotel_category_id`,  H.`hotel_id`,  H.`total_no_of_rooms`,  H.`total_room_rate`,H.`hotel_margin_percentage`, H.`hotel_margin_rate`, H.`hotel_breakfast_cost`, H.`hotel_lunch_cost`, H.`hotel_dinner_cost`, H.`total_no_of_persons`, H.`total_hotel_meal_plan_cost`, R.`itinerary_plan_hotel_room_details_ID`, R.`room_type_id`, R.`room_id`, R.`room_rate`, R.`gst_type`, R.`gst_percentage`, R.`gst_rate`,R.`extra_bed_count`, R.`total_extra_bed_rate`, R.`total_rate_of_room` FROM `dvi_itinerary_plan_hotel_details` H  LEFT JOIN `dvi_itinerary_plan_hotel_room_details` R ON H.`itinerary_plan_hotel_details_ID`= R.`itinerary_plan_hotel_details_id` WHERE  H.`deleted` = '0' and H.`status` = '1' and  H.`itinerary_plan_id` = '$itinerary_plan_ID' GROUP BY H.`itinerary_plan_hotel_details_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_hotel_details);
                if ($total_itinerary_route_count > 0) :
                    while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_details)) :
                        $count++;
                        $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                        $itinerary_plan_hotel_room_details_ID = $fetch_hotel_data['itinerary_plan_hotel_room_details_ID'];
                        $itinerary_route_id = $fetch_hotel_data['itinerary_route_id'];
                        $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];

                        $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                        $hotel_required = $fetch_hotel_data['hotel_required'];
                        $hotel_category = getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'label');

                        $hotel_id = $fetch_hotel_data['hotel_id'];
                        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                        $hotel_place = getHOTEL_PLACE($hotel_id, 'hotel_place');

                        $total_room_rate = $fetch_hotel_data['total_room_rate'];

                        $total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];

                        $room_type_id = $fetch_hotel_data['room_type_id'];
                        $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                        $room_id = $fetch_hotel_data['room_id'];
                        $room_title = getROOM_DETAILS($room_id, 'room_title');;
                        $room_rate = $fetch_hotel_data['room_rate'];
                        $total_rate_of_room = $fetch_hotel_data['total_rate_of_room'];
                        $total_extra_bed_rate = $fetch_hotel_data['total_extra_bed_rate'];
                        $total_no_of_persons = $fetch_hotel_data['total_no_of_persons'];

                        $extra_bed_count = $fetch_hotel_data['extra_bed_count'];
                        //HOTEL MARGIN
                        $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                        $hotel_margin_rate = $fetch_hotel_data['hotel_margin_rate'];
                        //HOTEL MEAL COST
                        $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                        $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                        $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];

                        $total_hotel_meal_plan_cost = $fetch_hotel_data['total_hotel_meal_plan_cost'];

                        //FETCH ROUTE LOCATION LONGITUDE AND LATITUDE
                        $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `location_id` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_id'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());

                        while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                            $location_id = $fetch_itinerary_route_data['location_id'];

                            $get_location_details = sqlQUERY_LABEL("SELECT `destination_location`,`location_ID`,`destination_location_lattitude`,`destination_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                            if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                                while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                                    $next_visiting_location_longitude = $fetch_location_data['destination_location_longitude'];
                                    $next_visiting_location_latitude = $fetch_location_data['destination_location_lattitude'];
                                endwhile;
                            endif;


                        endwhile;

                        //HOTEL LIST 


                        if ($hotel_required == 1) :
                ?>
                            <!-- Day <?= $count; ?> -->
                            <div class="card border border-secondary p-3 mt-2 day_wise_card">
                                <input type="hidden" name="hidden_itinerary_plan_hotel_details_ID" value="<?= $itinerary_plan_hotel_details_ID ?>" />
                                <input type="hidden" name="hidden_route_date" value="<?= $itinerary_route_date ?>" />
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0">
                                        <span>DAY <?= $count; ?> - <?= date('F d, Y', strtotime($itinerary_route_date)); ?></span>
                                        <span class="mx-1">|</span>
                                        <span class="text-primary me-1">
                                            <i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i><?= $itinerary_route_location; ?></span>
                                    </h6>
                                    <div class="hotel_label_<?= $itinerary_plan_hotel_details_ID ?>">
                                        <span class="text-primary">Hotel Needed for Stay - </span>
                                        <span class="text-primary me-1 fw-bolder"><?= get_YES_R_NO($hotel_required, 'label') ?></span>
                                    </div>
                                    <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?>">
                                        <div class="mb-3 row align-items-center">
                                            <label for="html5-text-input" class="col-md-auto col-form-label text-primary py-0 pe-0">Hotel Needed for Stay - </label>
                                            <div class="col-md-auto">
                                                <select name="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control  form-select-sm" onchange="onchangeHOTELREQUIRED('<?= $itinerary_plan_hotel_details_ID ?>');">
                                                    <?= get_YES_R_NO('1', 'select') ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="hotel_label_<?= $itinerary_plan_hotel_details_ID ?>">
                                    <div class="d-flex align-items-center justify-content-between mt-2 mb-3">
                                        <div class="">
                                            <h5 class="mb-0"><b><?= $hotel_name . ", " . $hotel_place . " (" . $hotel_category . ")"  ?></b></h5>

                                            <?php
                                            $hotel_review_details = sqlQUERY_LABEL("SELECT `hotel_rating` FROM `dvi_hotel_review_details` WHERE  `hotel_id` ='$hotel_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
                                            $count_reviews = 0;

                                            if (sqlNUMOFROW_LABEL($hotel_review_details) > 0) :
                                                while ($fetch_hotel_review_data = sqlFETCHARRAY_LABEL($hotel_review_details)) :
                                                    $count_reviews++;
                                                    $hotel_rating += $fetch_hotel_review_data['hotel_rating'];
                                                endwhile;
                                            else :
                                                $hotel_rating = 0;
                                            endif;

                                            if ($hotel_rating > 0) :
                                                $hotel_rating = round($hotel_rating / $count_reviews, 1);
                                            endif;

                                            if ($hotel_rating == 0) :
                                                $hotel_rating_label = '';
                                            elseif ($hotel_rating >= 0.5 && $hotel_rating < 1) :
                                                $hotel_rating_label = 'Poor - ';
                                            elseif ($hotel_rating >= 1 && $hotel_rating < 2) :
                                                $hotel_rating_label = 'Fair - ';
                                            elseif ($hotel_rating >= 2 && $hotel_rating < 3) :
                                                $hotel_rating_label = 'Good - ';
                                            elseif ($hotel_rating >= 3 && $hotel_rating < 4) :
                                                $hotel_rating_label = 'Very Good - ';
                                            elseif ($hotel_rating >= 4 && $hotel_rating <= 5) :
                                                $hotel_rating_label = 'Excellent - ';
                                            endif;
                                            ?>
                                            <small class="mb-0 d-flex align-items-center">
                                                <span class="badge me-1" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
                                                    <small><?= $hotel_rating; ?> <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
                                                </span>
                                                <?= $hotel_rating_label; ?><a href="javascript:;" class="text-dark text-decoration-underline ms-1"><?= $count_reviews; ?> reviews</a>
                                            </small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary waves-effect hotel_edit_btn_<?= $itinerary_plan_hotel_details_ID ?>" onclick="editITINERARYHOTELBYROW('<?= $itinerary_plan_hotel_details_ID; ?>')">
                                            <span class="ti-xs ti ti-edit me-1"></span>Edit
                                        </button>
                                    </div>
                                </div>


                                <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?> mb-3">
                                    <div class="row justify-content-between align-items-end">
                                        <div class="col-md-auto row">
                                            <div class="col-md-auto">
                                                <label class="text-sm-end" for="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Category</label>
                                                <select name="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control" onchange="onchangeHOTELCATEGORY('<?= $itinerary_plan_ID ?>','<?= $itinerary_plan_hotel_details_ID ?>','<?= $next_visiting_location_latitude ?>','<?= $next_visiting_location_longitude ?>','<?= $itinerary_route_date ?>');">
                                                    <?= getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'select') ?>
                                                </select>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="text-sm-end" for="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Name</label>
                                                <select name="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" style="width: 300px;" autocomplete="off" class="form-control" onchange="onchangeHOTEL('<?= $itinerary_plan_hotel_details_ID ?>');">
                                                    <?= getNEARESTHOTELS($next_visiting_location_latitude, $next_visiting_location_longitude, $hotel_id, $itinerary_plan_ID, $itinerary_route_date); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="text-sm-end" for="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Meal Plan</label>
                                                <div class="form-group mt-2">
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_breakfast" <?php if ($meal_plan_breakfast == 1) : echo 'checked';
                                                                                                                                                                        endif; ?> name="meal_plan_breakfast"><label class="form-check-label" for="meal_plan_breakfast">Breakfast</label></div>
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_lunch" <?php if ($meal_plan_lunch == 1) : echo 'checked';
                                                                                                                                                                    endif; ?> name="meal_plan_lunch"><label class="form-check-label" for="meal_plan_lunch">Lunch</label></div>
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_dinner" <?php if ($meal_plan_dinner == 1) : echo 'checked';
                                                                                                                                                                    endif; ?> name="meal_plan_dinner"><label class="form-check-label" for="meal_plan_dinner">Dinner</label></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <button type="submit" class="d-none btn btn-primary waves-effect waves-light hotel_update_btn_<?= $itinerary_plan_hotel_details_ID ?>">
                                                <span class="ti-xs ti ti-check me-1"></span>Update
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="dvi_hotel_room_details" class="row align-items-center">
                                    <input type="hidden" name="hidden_room_id_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hidden_room_id_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" value="<?= $room_id ?>" />
                                    <div class="col-9 border-end">
                                        <div class="row">
                                            <?php
                                            $select_itinerary_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `room_type_id`, `room_id`, `room_rate`, `gst_type`, `gst_percentage`, `gst_rate`, `extra_bed_count`, `extra_bed_rate`, `total_extra_bed_rate`, `extra_bed_gst_rate`, `total_extra_bed_charge_with_tax`, `total_rate_of_room` FROM  `dvi_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
                                            if ($total_itinerary_room_count > 0) :
                                                $counter = 0;
                                                $total_room_rate_daywise = 0;
                                                $grand_total_room_rate_daywise = 0;
                                                $total_room_gst_rate = 0;
                                                while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
                                                    $counter++;
                                                    $itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
                                                    $room_type_id = $fetch_room_data['room_type_id'];
                                                    $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                                    $room_id = $fetch_room_data['room_id'];
                                                    $room_title = getROOM_DETAILS($room_id, 'room_title');;
                                                    $room_rate = $fetch_room_data['room_rate'];
                                                    $gst_rate = $fetch_room_data['gst_rate'];
                                                    $total_rate_of_room = $fetch_room_data['total_rate_of_room'];


                                                    $extra_bed_count = $fetch_room_data['extra_bed_count'];
                                                    $extra_per_bed_rate = $fetch_room_data['extra_bed_rate'];
                                                    $total_extra_bed_rate = $fetch_room_data['total_extra_bed_rate'];

                                                    $extra_bed_gst_rate  = $fetch_room_data['extra_bed_gst_rate'];
                                                    $total_extra_bed_charge_with_tax  = $fetch_room_data['total_extra_bed_charge_with_tax'];


                                                    $total_room_rate_daywise += ($room_rate + $total_extra_bed_rate);
                                                    $total_room_gst_rate += ($gst_rate + $extra_bed_gst_rate);
                                                    $grand_total_room_rate_daywise += ($total_rate_of_room + $total_extra_bed_rate + $hotel_margin_rate + $total_hotel_meal_plan_cost);

                                                    $selected_hotel_room_details_query = sqlQUERY_LABEL("SELECT `air_conditioner_availability`, `total_max_adults`, `total_max_childrens`, `check_in_time`, `check_out_time`, `gst_type`, `gst_percentage`, `breakfast_included`, `lunch_included`, `dinner_included`, `inbuilt_amenities`, `extra_bed_charge` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$room_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
                                                    if (sqlNUMOFROW_LABEL($selected_hotel_room_details_query) > 0) :
                                                        while ($fetch_hotel_room_details_data = sqlFETCHARRAY_LABEL($selected_hotel_room_details_query)) :
                                                            $air_conditioner_availability = $fetch_hotel_room_details_data['air_conditioner_availability'];
                                                            if ($air_conditioner_availability == '0') :
                                                                $air_conditioner_availability_label = 'AC Unavailable';
                                                            elseif ($air_conditioner_availability == '1') :
                                                                $air_conditioner_availability_label = 'AC Available';
                                                            endif;

                                                            $total_max_adults = $fetch_hotel_room_details_data['total_max_adults'];
                                                            $total_max_childrens = $fetch_hotel_room_details_data['total_max_childrens'];
                                                            $check_in_time = date('g:i A', strtotime($fetch_hotel_room_details_data['check_in_time']));
                                                            $check_out_time = date('g:i A', strtotime($fetch_hotel_room_details_data['check_out_time']));

                                                            $gst_type = $fetch_hotel_room_details_data['gst_type'];
                                                            $gst_percentage = $fetch_hotel_room_details_data['gst_percentage'];

                                                            $breakfast_included = $fetch_hotel_room_details_data['breakfast_included'];

                                                            $food_label = '';

                                                            if ($breakfast_included == '1') :
                                                                $food_label .= 'Breakfast';
                                                            else :
                                                                $food_label .= '';
                                                            endif;

                                                            $lunch_included = $fetch_hotel_room_details_data['lunch_included'];
                                                            if ($lunch_included == '1') :
                                                                if ($food_label != '') :
                                                                    $food_label .= ', ';
                                                                endif;

                                                                $food_label .= 'Lunch';
                                                            else :
                                                                $food_label .= '';
                                                            endif;

                                                            $dinner_included = $fetch_hotel_room_details_data['dinner_included'];
                                                            if ($dinner_included == '1') :
                                                                if ($food_label != '') :
                                                                    $food_label .= ', ';
                                                                endif;

                                                                $food_label .= 'Dinner';
                                                            else :
                                                                $food_label .= '';
                                                            endif;

                                                            if ($food_label == '') :
                                                                $food_label = 'N/A';
                                                            endif;

                                                            $inbuilt_amenities = $fetch_hotel_room_details_data['inbuilt_amenities'];

                                                            if ($inbuilt_amenities != '') :
                                                                $inbuilt_amenities_label = get_INBUILT_AMENITIES($inbuilt_amenities, 'multilabel');
                                                            else :
                                                                $inbuilt_amenities_label = 'N/A';
                                                            endif;


                                                            $extra_bed_charge = $fetch_hotel_room_details_data['extra_bed_charge'];
                                                        endwhile;
                                                    endif;

                                                    $hotel_room_gallery_COUNT = 0;
                                                    $room_gallery_name_first = '';
                                                    $selected_hotel_room_gallery_details_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` where `deleted` = '0' and `room_id` = '$room_id' LIMIT 1") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
                                                    $hotel_room_gallery_COUNT = sqlNUMOFROW_LABEL($selected_hotel_room_gallery_details_query);
                                                    if ($hotel_room_gallery_COUNT > 0) :
                                                        while ($fetch_hotel_room_gallery_details_data = sqlFETCHARRAY_LABEL($selected_hotel_room_gallery_details_query)) :
                                                            $hotel_room_gallery_details_id = $fetch_hotel_room_gallery_details_data['hotel_room_gallery_details_id'];
                                                            $room_gallery_name_first = BASEPATH . '/uploads/room_gallery/' . $fetch_hotel_room_gallery_details_data['room_gallery_name'];
                                                        endwhile;
                                                    else :
                                                        $room_gallery_name_first = BASEPATH . 'uploads/no-photo.png';
                                                    endif;
                                            ?>
                                                    <div class="col-12">
                                                        <div class="hotel_label_<?= $itinerary_plan_hotel_details_ID ?>">

                                                            <div class="row justify-content-between mb-2">
                                                                <div class="col-9">
                                                                    <h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room <?= $counter; ?> - <?= $room_type_title . ' - ' . $room_title; ?></span> <small class="mb-0 air_conditioner_availability_label_<?= $itinerary_plan_hotel_details_ID ?>">(<?= $air_conditioner_availability_label; ?>)</small></h6>
                                                                    <small><i class="ti ti-users ti-xs me-1"></i><span class="total_max_adults_<?= $itinerary_plan_hotel_details_ID ?>"><?= $total_max_adults; ?></span> Adults, <span class="total_max_childrens_<?= $itinerary_plan_hotel_details_ID ?>"><?= $total_max_childrens; ?></span> Children</small>
                                                                </div>
                                                                <div class="col-3 text-primary mb-0 text-end">
                                                                    <h5 class="mb-0 lh-1">
                                                                        <span class="room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?> room_rate_<?= $itinerary_plan_hotel_details_ID ?> p-2"><?= $global_currency_format . ' ' . number_format($room_rate + $total_extra_bed_rate, 2); ?></span>
                                                                    </h5>
                                                                    <small>+ <?= $global_currency_format; ?> <span class="gst_rate_<?= $itinerary_plan_hotel_details_ID ?>"><?= number_format($gst_rate + $extra_bed_gst_rate, 2); ?></span> Tax & Charges</small>

                                                                    <input type="hidden" name="hidden_room_rate_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hidden_room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" value="<?= $total_rate_of_room + $total_extra_bed_charge_with_tax ?>" />
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-3">
                                                                    <img class="d-flex mx-auto rounded cursor-pointer" src="<?= $room_gallery_name_first; ?>" alt="<?= $room_type_title . '_' . $room_title; ?>" <?php if ($hotel_room_gallery_COUNT > 0) : ?> data-bs-toggle="modal" data-bs-target="#modalCenter_<?= $itinerary_plan_hotel_room_details_ID; ?>_<?= $room_id; ?>" <?php endif; ?> width="150" height="125" style="border: 1px solid #c33ca6;" />
                                                                </div>
                                                                <div class="col-9 row">
                                                                    <div class="col-4">
                                                                        <small class="mb-0">Check-In Time</small>
                                                                        <p class="mb-0 fw-bolder check_in_time_<?= $itinerary_plan_hotel_details_ID ?>"><?= $check_in_time; ?></p>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <small class="mb-0">Check-Out Time</small>
                                                                        <p class="mb-0 fw-bolder check_out_time_<?= $itinerary_plan_hotel_details_ID ?>"><?= $check_out_time; ?></p>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <small class="mb-0">Extra Bed(<?= $global_currency_format; ?> <span class="extra_bed_charge_<?= $itinerary_plan_hotel_details_ID ?>"><?= $extra_per_bed_rate; ?></span> Per)</small>
                                                                        <p class="mb-0 fw-bolder"><?= $extra_bed_count ?></p>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <small class="mb-0">Food</small>
                                                                        <p class="mb-0 fw-bolder food_label_<?= $itinerary_plan_hotel_details_ID ?>"><?= $food_label; ?></p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="mb-0">Inbuilt Amenities</small>
                                                                        <p class="mb-0 fw-bolder inbuilt_amenities_label_<?= $itinerary_plan_hotel_details_ID ?>"><?= $inbuilt_amenities_label; ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php if ($hotel_room_gallery_COUNT > 0) : ?>

                                                            <div class="modal fade" id="modalCenter_<?= $itinerary_plan_hotel_room_details_ID; ?>_<?= $room_id; ?>" tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body pt-0">

                                                                            <div class="text-center mb-2">
                                                                                <h5 class="modal-title" id="modalCenterTitle"><?= $hotel_name . ", " . $hotel_place . " (" . $hotel_category . ")"  ?> </h5>
                                                                                <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"><?= $room_type_title . ' - ' . $room_title; ?></h5>
                                                                            </div>
                                                                            <div id="swiper-gallery">
                                                                                <div class="swiper gallery-top">
                                                                                    <div class="swiper-wrapper">
                                                                                        <?php
                                                                                        $room_gallery_name = '';
                                                                                        $selected_hotel_room_gallery_details_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` where `deleted` = '0' and `room_id` = '$room_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
                                                                                        if (sqlNUMOFROW_LABEL($selected_hotel_room_gallery_details_query) > 0) :
                                                                                            while ($fetch_hotel_room_gallery_details_data = sqlFETCHARRAY_LABEL($selected_hotel_room_gallery_details_query)) :
                                                                                                $hotel_room_gallery_details_id = $fetch_hotel_room_gallery_details_data['hotel_room_gallery_details_id'];
                                                                                                $room_gallery_name = BASEPATH . '/uploads/room_gallery/' . $fetch_hotel_room_gallery_details_data['room_gallery_name'];
                                                                                        ?>
                                                                                                <div class="swiper-slide" style="background-image:url(<?= $room_gallery_name; ?>)"></div>
                                                                                        <?php
                                                                                            endwhile;
                                                                                        else :
                                                                                            $room_gallery_name = '';
                                                                                        endif;
                                                                                        ?>
                                                                                    </div>
                                                                                    <!-- Add Arrows -->
                                                                                    <div class="swiper-button-next swiper-button-white"></div>
                                                                                    <div class="swiper-button-prev swiper-button-white"></div>
                                                                                </div>
                                                                                <div class="swiper gallery-thumbs">
                                                                                    <div class="swiper-wrapper">
                                                                                        <?php
                                                                                        $room_gallery_name = '';
                                                                                        $selected_hotel_room_gallery_details_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` where `deleted` = '0' and `room_id` = '$room_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
                                                                                        if (sqlNUMOFROW_LABEL($selected_hotel_room_gallery_details_query) > 0) :
                                                                                            while ($fetch_hotel_room_gallery_details_data = sqlFETCHARRAY_LABEL($selected_hotel_room_gallery_details_query)) :
                                                                                                $hotel_room_gallery_details_id = $fetch_hotel_room_gallery_details_data['hotel_room_gallery_details_id'];
                                                                                                $room_gallery_name = BASEPATH . '/uploads/room_gallery/' . $fetch_hotel_room_gallery_details_data['room_gallery_name'];
                                                                                        ?>
                                                                                                <div class="swiper-slide" style="background-image:url(<?= $room_gallery_name; ?>)"></div>
                                                                                        <?php
                                                                                            endwhile;
                                                                                        else :
                                                                                            $room_gallery_name = '';
                                                                                        endif;
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?>">

                                                            <div class="row justify-content-between mb-2">
                                                                <div class="col-9">
                                                                    <div class="row align-items-center">
                                                                        <label for="html5-text-input" class="col-md-auto col-form-label py-0 pe-0 text-primary fw-bolder"><i class="ti ti-bed-filled text-primary me-1"></i>Room <?= $counter; ?> - Type</label>
                                                                        <div class="col-md-auto">
                                                                            <select name="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" autocomplete="off" class="form-control  form-select-sm hotel_room_<?= $itinerary_plan_hotel_details_ID ?>" onchange="selectROOMDETAILS('<?= $itinerary_plan_hotel_details_ID ?>','<?= $counter ?>','<?= $itinerary_route_date ?>','<?= $extra_bed_count ?>','<?= $total_no_of_persons ?>');">
                                                                                <?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, 'select') ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-auto px-0"> <small id="air_conditioner_availability_label_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="mb-0 air_conditioner_availability_label_<?= $itinerary_plan_hotel_details_ID ?>">(<?= $air_conditioner_availability_label; ?>)</small></div>
                                                                    </div>

                                                                    <small>
                                                                        <i class="ti ti-users ti-xs me-1"></i>
                                                                        <span id="total_max_adults_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="total_max_adults_<?= $itinerary_plan_hotel_details_ID ?>"><?= $total_max_adults; ?></span> Adults, <span id="total_max_childrens_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="total_max_childrens_<?= $itinerary_plan_hotel_details_ID ?>"><?= $total_max_childrens; ?></span> Children
                                                                    </small>
                                                                </div>
                                                                <div class="col-3 text-primary mb-0 text-end">
                                                                    <h5 class="mb-0 lh-1">
                                                                        <span id="room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="room_rate_<?= $itinerary_plan_hotel_details_ID ?> p-2"><?= $global_currency_format . ' ' . number_format($room_rate + $total_extra_bed_rate, 2); ?></span>
                                                                    </h5>

                                                                    <small>+ <?= $global_currency_format; ?> <span id="gst_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="gst_rate_<?= $itinerary_plan_hotel_details_ID ?>"><?= number_format($gst_rate + $extra_bed_gst_rate, 2); ?></span> Tax & Charges</small>
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="hidden_room_rate_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hidden_room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" value="<?= $total_rate_of_room + $total_extra_bed_charge_with_tax ?>" />


                                                            <div class="row mb-2">
                                                                <div class="col-3">
                                                                    <img class="d-flex mx-auto rounded cursor-pointer" src="<?= $room_gallery_name_first; ?>" alt="<?= $room_type_title . '_' . $room_title; ?>" <?php if ($hotel_room_gallery_COUNT > 0) : ?> data-bs-toggle="modal" data-bs-target="#modalCenter_<?= $itinerary_plan_hotel_room_details_ID; ?>_<?= $room_id; ?>" <?php endif; ?> width="150" height="125" style="border: 1px solid #c33ca6;" />
                                                                </div>
                                                                <div class="col-9 row">
                                                                    <div class="col-4">
                                                                        <small class="mb-0">Check-In Time</small>
                                                                        <p id="check_in_time_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="mb-0 fw-bolder check_in_time_<?= $itinerary_plan_hotel_details_ID ?>"><?= $check_in_time; ?></p>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <small class="mb-0">Check-Out Time</small>
                                                                        <p id="check_out_time_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="mb-0 fw-bolder check_out_time_<?= $itinerary_plan_hotel_details_ID ?>"><?= $check_out_time; ?></p>
                                                                    </div>

                                                                    <div class="col-4">
                                                                        <small class="mb-0">Extra Bed(<?= $global_currency_format; ?> <span id="extra_bed_charge_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="extra_bed_charge_<?= $itinerary_plan_hotel_details_ID ?>"><?= $extra_per_bed_rate; ?></span> Per)</small>
                                                                        <p class="mb-0 fw-bolder"><?= $extra_bed_count ?></p>
                                                                        <input type="hidden" name="extra_bed_rate_with_tax_<?= $itinerary_plan_hotel_details_ID ?>[]" value="<?= $total_extra_bed_rate; ?>" hidden>
                                                                    </div>

                                                                    <div class="col-4">
                                                                        <small class="mb-0">Food</small>
                                                                        <p id="food_label_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="mb-0 fw-bolder food_label_<?= $itinerary_plan_hotel_details_ID ?>"><?= $food_label; ?></p>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <small class="mb-0">Inbuilt Amenities</small>
                                                                        <p id="inbuilt_amenities_label_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="mb-0 fw-bolder inbuilt_amenities_label_<?= $itinerary_plan_hotel_details_ID ?>"><?= $inbuilt_amenities_label; ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="hidden_itinerary_plan_hotel_room_details_ID_<?= $itinerary_plan_hotel_details_ID ?>[]" value="<?= $itinerary_plan_hotel_room_details_ID ?>" />
                                                        <input type="hidden" name="extra_bed_rate_with_tax_<?= $itinerary_plan_hotel_details_ID ?>[]" value="<?= $total_extra_bed_rate; ?>" hidden />
                                                    </div>

                                                    <?php if ($total_itinerary_room_count > $counter) : ?>
                                                        <div class="col-12">
                                                            <hr class="my-3">
                                                        </div>
                                                    <?php endif; ?>
                                            <?php
                                                endwhile;
                                            endif; ?>

                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class=" my-auto">
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Total Room Cost</p>
                                                <p class="mb-0 total_room_rate_<?= $itinerary_plan_hotel_details_ID ?> cls_room_rate" id="total_room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>"><?php echo $global_currency_format . ' ' . number_format($total_room_rate_daywise, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Total Tax</p>
                                                <p class="mb-0 total_room_tax_<?= $itinerary_plan_hotel_details_ID ?>" id="total_room_tax_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>">
                                                    <?php echo $global_currency_format . ' ' . number_format($total_room_gst_rate, 2); ?>
                                                </p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0" id="hotel_margin_percentage_label_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>">Hotel Margin (<?= $hotel_margin_percentage ?>%)</p>
                                                <p class="mb-0" id="total_hotel_margin_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>"><?php echo $global_currency_format . ' ' . number_format($hotel_margin_rate, 2); ?></p>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <p class="mb-0">Total Food Cost</p>
                                                <p class="mb-0" id="total_food_cost_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>"><?php echo $global_currency_format . ' ' . number_format($total_hotel_meal_plan_cost, 2); ?></p>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                <h5 class="mb-0">Grand Total</h5>
                                                <h5 class="mb-0 text-primary fw-bolder grand_total_room_rate_<?= $itinerary_plan_hotel_details_ID ?> cls_grand_total_room_rate" id="grand_total_room_rate_<?= $itinerary_plan_hotel_details_ID ?>">
                                                    <?php echo $global_currency_format . ' ' . number_format($grand_total_room_rate_daywise, 2); ?>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Day <?= $count; ?> -->
                        <?php else : ?>
                            <div class="card border border-secondary p-3 mt-2 day_wise_card" style="background-color: #fdf1f1;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="mb-0">
                                        <span>DAY <?= $count; ?> - <?= date('F d, Y', strtotime($itinerary_route_date)); ?></span>
                                        <span class="mx-1">|</span>
                                        <span class="text-primary me-1">
                                            <i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i><?= $itinerary_route_location; ?></span>
                                    </h6>
                                    <div class="hotel_label_<?= $itinerary_plan_hotel_details_ID ?>">
                                        <span class="text-primary">Hotel Needed for Stay - </span>
                                        <span class="text-primary me-1 fw-bolder"><?= get_YES_R_NO($hotel_required, 'label') ?></span>
                                    </div>
                                </div>
                            </div>

                    <?php endif;
                        $prev_itinerary_route_date = $itinerary_route_date;
                    endwhile;
                else : ?>
                    <div class="card border border-secondary p-3">
                        <p class="mb-0 text-center">No records found</p>
                    </div>
                <?php endif; ?>

                <div class="card border border-primary p-0 mt-3">
                    <div class="p-3 pb-0">
                        <h5 class="card-header p-0 mb-3">Hotel Summary</h5>
                        <div class="order-calculations">
                            <table class="table mb-2">
                                <tbody class="table-border-bottom-0" style="border: 1px solid #fff;">
                                    <?php
                                    $select_itinerary_hotel_details = sqlQUERY_LABEL("SELECT H.`itinerary_plan_hotel_details_ID`,  H.`itinerary_plan_id`,  H.`itinerary_route_id`,  H.`itinerary_route_date`,  H.`itinerary_route_location`,  H.`hotel_required`,  H.`hotel_category_id`,  H.`hotel_id`,  H.`total_no_of_rooms`,  H.`total_room_rate`,H.`hotel_margin_percentage`, H.`hotel_margin_rate`, H.`hotel_breakfast_cost`, H.`hotel_lunch_cost`, H.`hotel_dinner_cost`, H.`total_no_of_persons`, H.`total_hotel_meal_plan_cost`, R.`itinerary_plan_hotel_room_details_ID`, R.`room_type_id`, R.`room_id`, R.`room_rate`, R.`gst_type`, R.`gst_percentage`, R.`gst_rate`,R.`extra_bed_count`, R.`total_extra_bed_rate`, R.`total_rate_of_room` FROM `dvi_itinerary_plan_hotel_details` H  LEFT JOIN `dvi_itinerary_plan_hotel_room_details` R ON H.`itinerary_plan_hotel_details_ID`= R.`itinerary_plan_hotel_details_id` WHERE  H.`deleted` = '0' and H.`status` = '1' and  H.`itinerary_plan_id` = '$itinerary_plan_ID' and H.`hotel_required`='1' GROUP BY H.`itinerary_plan_hotel_details_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_hotel_details);
                                    if ($total_itinerary_route_count > 0) :
                                        $count = 0;
                                        while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_details)) :
                                            $count++;
                                            $hotel_category = getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'label');
                                            $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                                            $hotel_required = $fetch_hotel_data['hotel_required'];
                                            $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
                                            $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                                            $hotel_id = $fetch_hotel_data['hotel_id'];
                                            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                            $hotel_place = getHOTEL_PLACE($hotel_id, 'hotel_place');
                                            $total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];
                                            $total_room_rate = $fetch_hotel_data['total_room_rate'];
                                            $room_rate = $fetch_hotel_data['room_rate'];

                                            $total_extra_bed_rate = $fetch_hotel_data['total_extra_bed_rate'];
                                            $total_no_of_persons = $fetch_hotel_data['total_no_of_persons'];

                                            $extra_bed_count = $fetch_hotel_data['extra_bed_count'];
                                            //HOTEL MARGIN
                                            $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                                            $hotel_margin_rate = $fetch_hotel_data['hotel_margin_rate'];
                                            //HOTEL MEAL COST
                                            $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                                            $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                                            $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];

                                            $total_hotel_meal_plan_cost = $fetch_hotel_data['total_hotel_meal_plan_cost'];

                                            if ($hotel_required == 1) : ?>
                                                <tr>
                                                    <td class="px-0 align-top" style="    border-color: #ce3db0;">
                                                        <h6 class="my-2">
                                                            <span>DAY <?= $count; ?> - <?= date('F d, Y', strtotime($itinerary_route_date)); ?></span>
                                                        </h6>

                                                        <div class="text-heading text-primary fw-bold my-2"><?= $hotel_name; ?> (<?= $total_no_of_rooms; ?> Rooms)
                                                        </div>

                                                        <div class="me-1 my-2">
                                                            <i class="ti ti-location-filled ti-xs me-1 mb-1 text-primary"></i><?= $itinerary_route_location; ?>
                                                        </div>

                                                        <!-- <div class="me-1 my-2">
                                                            <i class="ti ti-star-filled ti-xs me-1 mb-1 text-primary"></i><?= $hotel_category; ?>
                                                        </div>-->
                                                    </td>
                                                    <td class="align-top" style="border-left: 1px solid #ce3db0; border-right: 1px solid #ce3db0; border-color: #ce3db0;">
                                                        <?php
                                                        $select_itinerary_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `room_type_id`, `room_id`, `room_rate`, `gst_type`, `gst_percentage`, `gst_rate`, `extra_bed_count`, `extra_bed_rate`, `total_extra_bed_rate`, `extra_bed_gst_rate`, `total_extra_bed_charge_with_tax`, `total_rate_of_room` FROM  `dvi_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                        $total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
                                                        if ($total_itinerary_room_count > 0) :
                                                            $counter = 0;

                                                            $total_room_rate_daywise = 0;
                                                            $grand_total_room_rate_daywise = 0;
                                                            $total_room_gst_rate = 0;
                                                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
                                                                $counter++;
                                                                $itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
                                                                $room_type_id = $fetch_room_data['room_type_id'];
                                                                $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                                                $room_id = $fetch_room_data['room_id'];
                                                                $room_title = getROOM_DETAILS($room_id, 'room_title');;
                                                                $room_rate = $fetch_room_data['room_rate'];
                                                                $gst_rate = $fetch_room_data['gst_rate'];
                                                                $total_rate_of_room = $fetch_room_data['total_rate_of_room'];

                                                                $extra_bed_count = $fetch_room_data['extra_bed_count'];
                                                                $extra_per_bed_rate = $fetch_room_data['extra_bed_rate'];
                                                                $total_extra_bed_rate = $fetch_room_data['total_extra_bed_rate'];

                                                                $extra_bed_gst_rate  = $fetch_room_data['extra_bed_gst_rate'];
                                                                $total_extra_bed_charge_with_tax  = $fetch_room_data['total_extra_bed_charge_with_tax'];


                                                                $total_room_rate_daywise += ($room_rate + $total_extra_bed_rate);
                                                                $total_room_gst_rate += ($gst_rate + $extra_bed_gst_rate);
                                                                $grand_total_room_rate_daywise += ($total_rate_of_room + $total_extra_bed_rate + $hotel_margin_rate + $total_hotel_meal_plan_cost);

                                                                $selected_hotel_room_details_query = sqlQUERY_LABEL("SELECT `air_conditioner_availability`, `total_max_adults`, `total_max_childrens`, `check_in_time`, `check_out_time`, `gst_type`, `gst_percentage`, `breakfast_included`, `lunch_included`, `dinner_included`, `inbuilt_amenities`, `extra_bed_charge` FROM `dvi_hotel_rooms` where `deleted` = '0' and `room_ID` = '$room_id'") or die("#3-getCOLLEGE:UNABLE_TO_GET_COLLEGE_DETAILS: " . sqlERROR_LABEL());
                                                                if (sqlNUMOFROW_LABEL($selected_hotel_room_details_query) > 0) :
                                                                    while ($fetch_hotel_room_details_data = sqlFETCHARRAY_LABEL($selected_hotel_room_details_query)) :
                                                                        $air_conditioner_availability = $fetch_hotel_room_details_data['air_conditioner_availability'];
                                                                        if ($air_conditioner_availability == '0') :
                                                                            $air_conditioner_availability_label = 'AC Unavailable';
                                                                        elseif ($air_conditioner_availability == '1') :
                                                                            $air_conditioner_availability_label = 'AC Available';
                                                                        endif;

                                                                        $check_in_time = date('g:i A', strtotime($fetch_hotel_room_details_data['check_in_time']));
                                                                        $check_out_time = date('g:i A', strtotime($fetch_hotel_room_details_data['check_out_time']));

                                                                        $gst_type = $fetch_hotel_room_details_data['gst_type'];
                                                                        $gst_percentage = $fetch_hotel_room_details_data['gst_percentage'];

                                                                        $breakfast_included = $fetch_hotel_room_details_data['breakfast_included'];

                                                                        $food_label = '';

                                                                        if ($breakfast_included == '1') :
                                                                            $food_label .= 'Breakfast';
                                                                        else :
                                                                            $food_label .= '';
                                                                        endif;

                                                                        $lunch_included = $fetch_hotel_room_details_data['lunch_included'];
                                                                        if ($lunch_included == '1') :
                                                                            if ($food_label != '') :
                                                                                $food_label .= ', ';
                                                                            endif;

                                                                            $food_label .= 'Lunch';
                                                                        else :
                                                                            $food_label .= '';
                                                                        endif;

                                                                        $dinner_included = $fetch_hotel_room_details_data['dinner_included'];
                                                                        if ($dinner_included == '1') :
                                                                            if ($food_label != '') :
                                                                                $food_label .= ', ';
                                                                            endif;

                                                                            $food_label .= 'Dinner';
                                                                        else :
                                                                            $food_label .= '';
                                                                        endif;

                                                                        if ($food_label == '') :
                                                                            $food_label = 'N/A';
                                                                        endif;
                                                                    endwhile;
                                                                endif;

                                                        ?>
                                                                <div class="mx-3">
                                                                    <div class="row justify-content-between  mb-2">
                                                                        <div class="col-9">
                                                                            <h6 class="mb-0"><span class="text-primary"><i class="ti ti-bed me-1"></i> Room <?= $counter; ?> - <?= $room_type_title; ?></span> <!--<small class="mb-0">(<?= $air_conditioner_availability_label; ?>)</small>--></h6>
                                                                            <h6 class="mb-0"><i class="text-primary ti ti-clock ti-xs me-1 mb-1"></i>
                                                                                <span>Check In - <?= $check_in_time; ?></span>,
                                                                                <span>Check Out - <?= $check_out_time; ?></span>
                                                                            </h6>
                                                                            <!-- <h6 class="mb-0"><i class="text-primary ti ti-tools-kitchen-2 ti-xs me-1 mb-1"></i>
                                                                                <span><?= $food_label; ?></span>
                                                                            </h6>-->

                                                                        </div>
                                                                        <div class="col-3 mb-0 text-end">
                                                                            <h6 class="mb-0 lh-1">
                                                                                <?= $global_currency_format . ' ' . number_format($room_rate + $total_extra_bed_rate, 2); ?>
                                                                                <br />
                                                                                <small>(+
                                                                                    <?= $global_currency_format . ' ' . number_format($gst_rate + $extra_bed_gst_rate, 2); ?> Tax)</small>
                                                                            </h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php if ($total_itinerary_room_count > $counter) : ?>
                                                                    <hr class="my-2" />
                                                        <?php
                                                                endif;
                                                            endwhile;
                                                        endif;

                                                        $overall_total_room_rate += $total_room_rate_daywise;
                                                        $overall_total_room_gst_rate += $total_room_gst_rate;
                                                        $overall_total_margin_cost += $hotel_margin_rate;
                                                        $overall_total_food_cost += $total_hotel_meal_plan_cost;
                                                        ?>
                                                    </td>
                                                    <td class="px-0 text-end align-top" style="    border-color: #ce3db0;">
                                                        <h6 class="mb-0"><?= $global_currency_format . ' ' . number_format($grand_total_room_rate_daywise, 2); ?></h6>
                                                        <!--<small>(+ <?= $global_currency_format . ' ' . number_format($total_room_gst_rate, 2); ?> Tax)</small>-->
                                                    </td>
                                                </tr>
                                    <?php
                                            endif;
                                        endwhile;
                                    endif; ?>
                                </tbody>
                            </table>

                            <hr style="color: #e865cf; border-top: 2px dashed;" class="text-primary" />

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-heading">Total Hotel Cost</span>
                                <h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_cost"><?= number_format($overall_total_room_rate, 2); ?>
                                    </span></h6>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-heading">Total Hotel Tax</span>
                                <h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_taxes"><?= number_format($overall_total_room_gst_rate, 2); ?>
                                    </span></h6>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-heading">Total Hotel Margin</span>
                                <h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_taxes"><?= number_format($overall_total_margin_cost, 2); ?>
                                    </span></h6>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-heading">Total Food Cost</span>
                                <h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_taxes"><?= number_format($overall_total_food_cost, 2); ?>
                                    </span></h6>
                            </div>

                        </div>
                    </div>

                    <div class="d-flex justify-content-between  px-3 py-3" style="background-color: #f2f2f2;">
                        <h5 class="text-heading fw-bold mb-0">Grand Hotel Total </h5>
                        <h5 class="mb-0 fw-bold"><?= $global_currency_format; ?> <span id="overall_hotel_cost"><?= number_format($overall_total_room_rate + $overall_total_room_gst_rate, 2); ?>
                            </span></h5>
                    </div>
                </div>

            </div>

        <?php
        endif; ?>

        <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
        <script src="assets/js/ui-carousel.js"></script>
        <script>
            $(document).ready(function() {

                $(".form-select").selectize();


                //CALCULATING TOTAL AMOUNT FOR THE HOTEL
                let totalRoomRate = 0;
                $('.cls_room_rate').each(function() {
                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                    const rate = parseFloat(rateText);
                    if (!isNaN(rate)) {

                        totalRoomRate += rate;
                    }
                });

                $("#total_amount_for_hotel").html(totalRoomRate.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                console.log('asd');
                $(document).on('click', '.input_plus_button', function(e) {
                    total_no_of_extrabeds = 0;
                    var HOTEL_DETAILS_ID = $(this).data('id');
                    var HOTEL_ROOM_DETAILS_ID = $(this).data('itineraryhotelroomid');
                    var ROW_NO = $(this).data('rowcount');
                    var ROUTE_DATE = $(this).data('routedate');
                    var TYPE = "ADD";

                    $('.input_plus_minus_' + HOTEL_DETAILS_ID).each(function() {
                        no_of_extrabeds = parseInt($(this).val());
                        //alert(no_of_extrabeds);
                        total_no_of_extrabeds += no_of_extrabeds;
                    });

                    var extrabedField = $(this).siblings('.extrabed-field');
                    var currentValue = parseInt(extrabedField.val());
                    var defined_extra_bed_count = '<?= $total_extra_bed ?>';
                    extra_bed_count = parseInt(defined_extra_bed_count);
                    //alert(total_no_of_extrabeds);
                    //alert(extra_bed_count);
                    if (total_no_of_extrabeds < extra_bed_count) {
                        extrabedField.val(currentValue + 1);
                        calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE);
                    } else if (total_no_of_extrabeds == extra_bed_count) {
                        TOAST_NOTIFICATION('error', 'Total extra bed count exceeded', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }

                });

                $('.input_minus_button').click(function(e) {

                    var HOTEL_DETAILS_ID = $(this).data('id');
                    var HOTEL_ROOM_DETAILS_ID = $(this).data('itineraryhotelroomid');
                    var ROW_NO = $(this).data('rowcount');
                    var ROUTE_DATE = $(this).data('routedate');
                    var TYPE = "SUB";

                    var extrabedField = $(this).siblings('.extrabed-field');
                    var currentValue = parseInt(extrabedField.val());

                    if (currentValue > 0) {
                        extrabedField.val(currentValue - 1);
                        calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE);
                    }
                });

                //AJAX FORM SUBMIT
                $('button[type="submit"]').click(function(event) {
                    event.preventDefault(); // Prevent default form submission

                    // Get the parent <tr> of the clicked button
                    var $row = $(this).closest('.day_wise_card');

                    // Find and extract the necessary details from the row
                    var itinerary_plan_hotel_details_ID = $row.find('input[name="hidden_itinerary_plan_hotel_details_ID"]').val();
                    var route_date = $row.find('input[name="hidden_route_date"]').val();

                    // Append hotel_required, hotel_category_id, and hotel_id
                    var hotel_required = $('select[name="hotel_required_' + itinerary_plan_hotel_details_ID + '"]').val();
                    var hotel_category_id = $('select[name="hotel_category_' + itinerary_plan_hotel_details_ID + '"]').val();
                    var hotel_id = $('select[name="hotel_name_' + itinerary_plan_hotel_details_ID + '"]').val();

                    // Create FormData object and append the details
                    var formData = new FormData();
                    formData.append('hidden_itinerary_plan_hotel_details_ID', itinerary_plan_hotel_details_ID);
                    formData.append('hidden_route_date', route_date);
                    formData.append('hotel_required', hotel_required);
                    formData.append('hotel_category_id', hotel_category_id);
                    formData.append('hotel_id', hotel_id);

                    // Iterate over the arrays and append each value
                    var hotel_roomtype_ids = $('select[name="hotel_roomtype_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hotel_roomtype_ids.each(function(index, element) {
                        formData.append('hotel_roomtype_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    var hidden_room_ids = $('input[name="hidden_room_id_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hidden_room_ids.each(function(index, element) {
                        formData.append('hidden_room_id_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    var hidden_room_rates = $('input[name="hidden_room_rate_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hidden_room_rates.each(function(index, element) {
                        formData.append('hidden_room_rate_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    var hidden_itinerary_plan_hotel_room_details_IDS = $('input[name="hidden_itinerary_plan_hotel_room_details_ID_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hidden_itinerary_plan_hotel_room_details_IDS.each(function(index, element) {
                        formData.append('hidden_itinerary_plan_hotel_room_details_ID_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    // Perform AJAX submission
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=update_itinerary_plan_hotel_details',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            // Handle errors if necessary
                        } else {
                            // Handle success response
                            if (response.u_result == true) {
                                TOAST_NOTIFICATION('success', 'Itinerary Hotel Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                showHOTELLIST();
                            } else if (response.u_result == false) {
                                TOAST_NOTIFICATION('error', 'Unable to Update Itinerary Hotel Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                });


            });

            function calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE) {

                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;
                var HOTEL_ID = $("#hotel_name_" + HOTEL_DETAILS_ID).val();

                var hotelroom_selectize = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO)[0].selectize;
                var ROOM_TYPE_ID = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val();

                if (ROOM_TYPE_ID) {

                    var DAYS_COUNT = '<?= $no_of_nights ?>';
                    var ITINERARY_BUDGET = '<?= $expecting_budget ?>';
                    var ROOM_COUNT = '<?= $preferred_room_count ?>';

                    $.ajax({
                        url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=extra_bed_cost',
                        type: "POST",
                        data: {
                            HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                            HOTEL_ID: HOTEL_ID,
                            DAYS_COUNT: DAYS_COUNT,
                            ITINERARY_BUDGET: ITINERARY_BUDGET,
                            ROOM_COUNT: ROOM_COUNT,
                            ROUTE_DATE: ROUTE_DATE,
                            ROOM_TYPE_ID: ROOM_TYPE_ID,
                            TYPE: TYPE,
                            HOTEL_ROOM_DETAILS_ID: HOTEL_ROOM_DETAILS_ID
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.result == true) {
                                $("#hidden_room_id_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_ID);
                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + response.room_rate.toLocaleString());
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                let totalRoomRate = 0;

                                $('.room_rate_' + HOTEL_DETAILS_ID).each(function() {
                                    const roomrateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const roomrate = parseFloat(roomrateText);
                                    if (!isNaN(roomrate)) {
                                        totalRoomRate += roomrate;
                                    }
                                });
                                $("#total_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + totalRoomRate.toLocaleString());

                                let total_amount_for_hotel = 0;

                                $('.cls_room_rate').each(function() {
                                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const rate = parseFloat(rateText);
                                    if (!isNaN(rate)) {
                                        total_amount_for_hotel += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(total_amount_for_hotel);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to update Cost', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                }

            }

            function onchangeHOTELREQUIRED(HOTEL_DETAILS_ID) {
                var hotelrequired_selectize = $("#hotel_required_" + HOTEL_DETAILS_ID)[0].selectize;
                var hotel_required = $("#hotel_required_" + HOTEL_DETAILS_ID).val();
                if (hotel_required == 0) {
                    $('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

                    $('.hotel_text_' + HOTEL_DETAILS_ID).addClass('d-none');
                    //$('.hotel_update_btn_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $(".total_room_rate_" + HOTEL_DETAILS_ID).addClass('d-none');
                    $(".cls_hotel_required_" + HOTEL_DETAILS_ID).removeClass('d-none');
                } else if (hotel_required == 1) {
                    $('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

                    $('.hotel_text_' + HOTEL_DETAILS_ID).removeClass('d-none');
                    //$('.hotel_update_btn_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $(".total_room_rate_" + HOTEL_DETAILS_ID).removeClass('d-none');
                    // $(".cls_hotel_required_" + HOTEL_DETAILS_ID).addClass('d-none');
                }
            }

            /*function onchangeHOTELCATEGORY(HOTEL_DETAILS_ID, LOCATION_LATITUDE, LOCATION_LONGITUDE) {
                var hotel_category_selectize = $("#hotel_category_" + HOTEL_DETAILS_ID)[0].selectize;
                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;

                var hotel_category_id = $("#hotel_category_" + HOTEL_DETAILS_ID).val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_name',
                    type: "POST",
                    data: {
                        HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                        LOCATION_LATITUDE: LOCATION_LATITUDE,
                        LOCATION_LONGITUDE: LOCATION_LONGITUDE,
                        hotel_category_id: hotel_category_id
                    },
                    success: function(response) {
                        // Append the response to the dropdown.
                        hotelname_selectize.clear();
                        hotelname_selectize.clearOptions();
                        hotelname_selectize.addOption(response);

                        $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                        $("#total_amount_for_hotel").html(" 0");

                    }
                });
            }
            
            function onchangeHOTEL(HOTEL_DETAILS_ID) {

                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;
                var hotel_id = $("#hotel_name_" + HOTEL_DETAILS_ID).val();
                const room_count = <?= $preferred_room_count ?>;

                for (i = 1; i <= room_count; i++) {
                    (function(index) {
                        var hotelroom_selectize = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + index)[0].selectize;

                        $.ajax({
                            url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_room',
                            type: "POST",
                            data: {
                                hotel_id: hotel_id
                            },
                            success: function(response) {
                                // Append the response to the dropdown.
                                hotelroom_selectize.clear();
                                hotelroom_selectize.clearOptions();
                                hotelroom_selectize.addOption(response);
                            }
                        });
                    })(i);
                }
                $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                $("#total_amount_for_hotel").html(" 0");
            }

              function selectROOMDETAILS(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE) {

                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;
                var HOTEL_ID = $("#hotel_name_" + HOTEL_DETAILS_ID).val();

                var hotelroom_selectize = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO)[0].selectize;
                var ROOM_TYPE_ID = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val();

                if (ROOM_TYPE_ID) {

                    var DAYS_COUNT = '<?= $no_of_nights ?>';
                    var ITINERARY_BUDGET = '<?= $expecting_budget ?>';
                    var ROOM_COUNT = '<?= $preferred_room_count ?>';
                    //var ROUTE_DATE = '<?= $itinerary_route_date ?>';

                    $.ajax({
                        url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=check_room_availability',
                        type: "POST",
                        data: {
                            HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                            HOTEL_ID: HOTEL_ID,
                            DAYS_COUNT: DAYS_COUNT,
                            ITINERARY_BUDGET: ITINERARY_BUDGET,
                            ROOM_COUNT: ROOM_COUNT,
                            ROUTE_DATE: ROUTE_DATE,
                            ROOM_TYPE_ID: ROOM_TYPE_ID
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.result == true) {
                                $("#hidden_room_id_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_ID);
                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + response.room_rate.toLocaleString());
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                let totalRoomRate = 0;

                                $('.room_rate_' + HOTEL_DETAILS_ID).each(function() {
                                    const roomrateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const roomrate = parseFloat(roomrateText);
                                    if (!isNaN(roomrate)) {
                                        totalRoomRate += roomrate;
                                    }
                                });
                                $("#total_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + totalRoomRate.toLocaleString());

                                let total_amount_for_hotel = 0;

                                $('.cls_room_rate').each(function() {
                                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const rate = parseFloat(rateText);
                                    if (!isNaN(rate)) {
                                        total_amount_for_hotel += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(total_amount_for_hotel);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'No Rooms Available', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                }
            }

            */

            function onchangeHOTELCATEGORY(ITINERARY_ID, HOTEL_DETAILS_ID, LOCATION_LATITUDE, LOCATION_LONGITUDE, ROUTE_DATE) {
                var hotel_category_select = $("#hotel_category_" + HOTEL_DETAILS_ID);
                var hotel_name_select = $("#hotel_name_" + HOTEL_DETAILS_ID);

                var hotel_category_id = hotel_category_select.val();
                alert(ITINERARY_ID);
                alert(HOTEL_DETAILS_ID);

                alert(LOCATION_LATITUDE);
                alert(LOCATION_LONGITUDE);

                alert(ROUTE_DATE); // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_name',
                    type: "POST",
                    data: {
                        ITINERARY_ID: ITINERARY_ID,
                        HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                        LOCATION_LATITUDE: LOCATION_LATITUDE,
                        LOCATION_LONGITUDE: LOCATION_LONGITUDE,
                        hotel_category_id: hotel_category_id,
                        ROUTE_DATE: ROUTE_DATE
                    },
                    success: function(response) {
                        // Clear existing options
                        hotel_name_select.empty();
                        hotel_name_select.append($('<option>', {
                            value: '',
                            text: 'Please Select Hotel'
                        }));

                        // Append new options
                        response.forEach(function(option) {
                            hotel_name_select.append($('<option>', {
                                value: option.value,
                                text: option.text
                            }));
                        });

                        // Reset room rate and total amount
                        $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");

                        $(".air_conditioner_availability_label_" + HOTEL_DETAILS_ID).html("");
                        $(".total_max_adults_" + HOTEL_DETAILS_ID).html("0");
                        $(".total_max_childrens_" + HOTEL_DETAILS_ID).html("0");
                        $(".gst_rate_" + HOTEL_DETAILS_ID).html("0");
                        $(".check_in_time_" + HOTEL_DETAILS_ID).html("--");
                        $(".check_out_time_" + HOTEL_DETAILS_ID).html("--");
                        $(".food_label_" + HOTEL_DETAILS_ID).html("--");
                        $(".inbuilt_amenities_label_" + HOTEL_DETAILS_ID).html("--");
                        $(".extra_bed_charge_" + HOTEL_DETAILS_ID).html("0");

                        $("#total_amount_for_hotel").html(" 0");
                    }
                });
            }


            function onchangeHOTEL(HOTEL_DETAILS_ID) {
                var hotel_id = $("#hotel_name_" + HOTEL_DETAILS_ID).val();
                const room_count = <?= $preferred_room_count ?>;

                for (var i = 1; i <= room_count; i++) {
                    (function(index) {
                        var hotelroom_select = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + index);

                        $.ajax({
                            url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_room',
                            type: "POST",
                            data: {
                                hotel_id: hotel_id
                            },
                            success: function(response) {
                                // Clear existing options
                                hotelroom_select.empty();
                                hotelroom_select.append($('<option>', {
                                    value: '',
                                    text: 'Please select a Room Type'
                                }));

                                // Append new options
                                response.forEach(function(option) {
                                    hotelroom_select.append($('<option>', {
                                        value: option.value,
                                        text: option.text
                                    }));
                                });
                            }
                        });
                    })(i);
                }

                $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                $("#total_amount_for_hotel").html(" 0");
            }

            function selectROOMDETAILS(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, extra_bed_count, total_no_of_persons) {
                //alert(HOTEL_DETAILS_ID);
                //alert(ROW_NO);
                var HOTEL_ID = $("#hotel_name_" + HOTEL_DETAILS_ID).val();
                var ROOM_TYPE_ID = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val();

                if (ROOM_TYPE_ID) {
                    var DAYS_COUNT = '<?= $no_of_nights ?>';
                    var ITINERARY_BUDGET = '<?= $expecting_budget ?>';
                    var ROOM_COUNT = '<?= $preferred_room_count ?>';

                    $.ajax({
                        url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=check_room_availability',
                        type: "POST",
                        data: {
                            HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                            HOTEL_ID: HOTEL_ID,
                            DAYS_COUNT: DAYS_COUNT,
                            ITINERARY_BUDGET: ITINERARY_BUDGET,
                            ROOM_COUNT: ROOM_COUNT,
                            ROUTE_DATE: ROUTE_DATE,
                            ROOM_TYPE_ID: ROOM_TYPE_ID,
                            extra_bed_count: extra_bed_count,
                            total_no_of_persons: total_no_of_persons
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.result == true) {
                                $("#hidden_room_id_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_ID);
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.room_rate);
                                $("#gst_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.gst_rate);

                                $("#air_conditioner_availability_label_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.air_conditioner_availability_label);
                                $("#total_max_adults_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.total_max_adults);
                                $("#total_max_childrens_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.total_max_childrens);
                                $("#check_in_time_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.check_in_time);
                                $("#check_out_time_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.check_out_time);
                                $("#food_label_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.food_label);
                                $("#inbuilt_amenities_label_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.inbuilt_amenities_label);
                                $("#extra_bed_charge_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.extra_bed_charge);

                                // ROOM GRAND TOTAL 

                                let totalRoomRate = 0;
                                $('.room_rate_' + HOTEL_DETAILS_ID).each(function() {
                                    const roomrateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const roomrate = parseFloat(roomrateText);
                                    // console.log(roomrate);
                                    if (!isNaN(roomrate)) {
                                        totalRoomRate += roomrate;
                                    }
                                });
                                $("#total_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + totalRoomRate);

                                let totalRoomtax = 0;
                                $('.gst_rate_' + HOTEL_DETAILS_ID).each(function() {
                                    const roomtaxText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const roomtax = parseFloat(roomtaxText);
                                    if (!isNaN(roomtax)) {
                                        totalRoomtax = totalRoomtax + roomtax;
                                    }
                                });
                                $("#total_room_tax_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + totalRoomtax);

                                $("#total_food_cost_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + response.total_hotel_meal_plan_cost);
                                $("#total_hotel_margin_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + response.hotel_margin_rate);
                                $("#hotel_margin_percentage_label_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("Hotel Margin (" + response.hotel_margin_percentage + " %)");


                                let total_amount_for_hotel = 0;
                                $('.cls_grand_total_room_rate').each(function() {
                                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const rate = parseFloat(rateText);
                                    if (!isNaN(rate)) {
                                        total_amount_for_hotel += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(total_amount_for_hotel);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'No Rooms Available', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                }
            }

            // Initialize main gallery Swiper instance
            var galleryTop = new Swiper('.gallery-top', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });

            // Initialize thumbnail gallery Swiper instance
            var galleryThumbs = new Swiper('.gallery-thumbs', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesVisibility: true,
                watchSlidesProgress: true,
            });

            // Connect main gallery with thumbnail gallery
            galleryTop.controller.control = galleryThumbs;
            galleryThumbs.controller.control = galleryTop;

            function editITINERARYHOTELBYROW(HOTEL_DETAILS_ID) {
                $('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
                $('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

                $('.hotel_text_' + HOTEL_DETAILS_ID).removeClass('d-none');
                $('.hotel_update_btn_' + HOTEL_DETAILS_ID).removeClass('d-none');
            }
        </script>

<?php
    elseif ($_GET['type'] == 'update_itinerary_plan_hotel_details') :

        $errors = [];
        $response = [];

        $hidden_itinerary_plan_hotel_details_ID = trim($_POST['hidden_itinerary_plan_hotel_details_ID']);
        $hidden_route_date = $_POST['hidden_route_date'];
        $itinerary_route_year = date('Y', strtotime($hidden_route_date));
        $itinerary_route_monthFullName = date('F', strtotime($hidden_route_date));
        $itinerary_route_day = ltrim(date("d", strtotime($hidden_route_date)), '0');

        $hotel_required = trim($_POST["hotel_required"]);
        $hotel_category_id = trim($_POST["hotel_category_id"]);
        $hotel_id  = trim($_POST["hotel_id"]);
        //Array
        $hotel_roomtype_id  = $_POST["hotel_roomtype_" . $hidden_itinerary_plan_hotel_details_ID];
        $hidden_room_id  = $_POST["hidden_room_id_" . $hidden_itinerary_plan_hotel_details_ID];
        $hidden_room_rate  = $_POST["hidden_room_rate_" . $hidden_itinerary_plan_hotel_details_ID];
        $hidden_itinerary_plan_hotel_room_details_IDS  = $_POST["hidden_itinerary_plan_hotel_room_details_ID_" . $hidden_itinerary_plan_hotel_details_ID];

        if ($hotel_required == 0) :

            //$arrFields_hotel = array('`hotel_required`', '`hotel_category_id`', '`hotel_id`', '`total_no_of_rooms`', '`total_room_rate`');
            //$arrValues_hotel = array("$hotel_required", "0", "0", "0", "0");

            $arrFields_hotel = array('`hotel_required`');
            $arrValues_hotel = array("$hotel_required");
            $sqlWhere_hotel = " `itinerary_plan_hotel_details_ID` = '$hidden_itinerary_plan_hotel_details_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, $sqlWhere_hotel)) :
                //DELETE EXISTING ROOM DETAILS
                //$sqlWhere_rooms = " `itinerary_plan_id` = '$hidden_itinerary_plan_hotel_details_ID' ";
                //$delete_previous_plan_room_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $sqlWhere_rooms);

                $response['success'] = true;
                $response['u_result'] = true;
                $response['result_success'] = true;
            else :
                $response['success'] = false;
                $response['u_result'] = false;
                $response['result_success'] = false;
            endif;

        elseif ($hotel_required == 1) :

            //UPDATE HOTEL DETAILS

            $arrFields_hotel = array('`hotel_required`', '`hotel_category_id`', '`hotel_id`');
            $arrValues_hotel = array("$hotel_required", "$hotel_category_id", "$hotel_id");
            $sqlWhere_hotel = " `itinerary_plan_hotel_details_ID` = '$hidden_itinerary_plan_hotel_details_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, $sqlWhere_hotel)) :

                if (count($hidden_itinerary_plan_hotel_room_details_IDS) > 0) :
                    $total_room_rate = 0;
                    for ($i = 0; $i < count($hidden_itinerary_plan_hotel_room_details_IDS); $i++) :

                        $gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,R.`extra_bed_charge`,R.`child_with_bed_charge`,R.`child_without_bed_charge`, RP.`DAY_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' and R.`room_ID`='$hidden_room_id[$i]' AND R.`room_type_id`='$hotel_roomtype_id[$i]' AND RP.`hotel_id`='$hotel_id' and R.`deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());


                        $total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);

                        if ($total_room_count > 0) :
                            $total_room_rate = 0;
                            $room_count = 0;
                            $total_room_rate_without_tax = 0;
                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
                                $room_count++;
                                $room_ID = $fetch_room_data['room_ID'];
                                $room_title = $fetch_room_data['room_title'];
                                $room_type_id = $fetch_room_data['room_type_id'];
                                $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                $gst_type = $fetch_room_data['gst_type'];
                                $gst_percentage = $fetch_room_data['gst_percentage'];
                                $FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
                                $child_with_bed_charge = $fetch_room_data['child_with_bed_charge'];

                                if ($room_count == 1) :
                                    $extra_bed_count =  $total_extra_bed;
                                    // $child_bed_count = $total_child_bed;
                                    $child_without_bed_charge = $fetch_room_data['child_without_bed_charge'];
                                    $extra_bed_charge = $fetch_room_data['extra_bed_charge'] + $child_without_bed_charge;

                                else :
                                    $extra_bed_count =  0;
                                    $extra_bed_charge = 0;
                                endif;

                                if ($gst_type == 1) :
                                    // For Inclusive GST
                                    //ROOM RATE
                                    $roomRate_without_tax = $FIXED_ROOM_RATE / (1 + ($gst_percentage / 100));
                                    $gst_amt = ($FIXED_ROOM_RATE - $roomRate_without_tax);
                                    $roomRate_with_tax = $FIXED_ROOM_RATE;

                                    //EXTRA BED RATE
                                    if ($extra_bed_count > 0) :
                                        $extrabedcharge = $extra_bed_charge * $extra_bed_count;
                                        $extra_bed_charge_without_tax = $extrabedcharge / (1 + ($gst_percentage / 100));
                                        $extrabed_gst_amt = ($extrabedcharge - $extra_bed_charge_without_tax);
                                        $extra_bed_charge_with_tax = $extrabedcharge;
                                    else :
                                        $extra_bed_charge_with_tax = 0;
                                        $extrabed_gst_amt = 0;
                                        $extra_bed_charge_without_tax = 0;
                                    endif;

                                elseif ($gst_type == 2) :
                                    // For Exclusive GST
                                    //ROOM RATE
                                    $roomRate_without_tax = $FIXED_ROOM_RATE;
                                    $gst_amt = ($FIXED_ROOM_RATE * $gst_percentage / 100);
                                    $roomRate_with_tax = $roomRate_without_tax + $gst_amt;

                                    //EXTRA BED RATE
                                    if ($extra_bed_count > 0) :

                                        $extrabedcharge = $extra_bed_charge * $extra_bed_count;
                                        $extra_bed_charge_without_tax = $extrabedcharge;
                                        $extrabed_gst_amt = ($extrabedcharge * $gst_percentage / 100);
                                        $extra_bed_charge_with_tax = $extra_bed_charge_without_tax + $extrabed_gst_amt;
                                    else :
                                        $extra_bed_charge_with_tax = 0;
                                        $extrabed_gst_amt = 0;
                                        $extra_bed_charge_without_tax = 0;
                                    endif;

                                endif;
                                //RATE WITHOUT TAX
                                $total_room_and_extrabed_rate_without_tax = $roomRate_without_tax + $extra_bed_charge_without_tax;

                                $total_room_rate_without_tax = $total_room_rate_without_tax + $total_room_and_extrabed_rate_without_tax;
                                //RATE WITH TAX
                                $total_room_and_extrabed_rate_with_tax = $roomRate_with_tax + $extra_bed_charge_with_tax;

                                $total_room_rate = $total_room_rate + $total_room_and_extrabed_rate_with_tax;

                                $sqlWhere_room = " `itinerary_plan_hotel_room_details_ID` = '$hidden_itinerary_plan_hotel_room_details_IDS[$i]' ";

                                $arrFields_room = array('`itinerary_plan_hotel_details_id`', '`itinerary_plan_id`', '`itinerary_route_id`', '`hotel_id`', '`room_type_id`', '`room_id`', '`room_rate`', '`gst_type`', '`gst_percentage`', '`gst_rate`', '`total_rate_of_room`', '`extra_bed_count`', '`extra_bed_rate`', '`total_extra_bed_rate`', '`extra_bed_gst_rate`', '`total_extra_bed_charge_with_tax`');

                                $arrValues_room = array("$itinerary_plan_hotel_details_id", "$itinerary_plan_ID", "$itinerary_route_ID", "$hotel_id", "$room_type_id", "$room_ID", "$roomRate_without_tax", "$gst_type", "$gst_percentage", "$gst_amt", "$roomRate_with_tax", "$extra_bed_count", "$extra_bed_charge", "$extra_bed_charge_without_tax", "$extrabed_gst_amt", "$extra_bed_charge_with_tax");


                                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_room_details", $arrFields_room, $arrValues_room, $sqlWhere_room)) :
                                endif;

                            endwhile;
                        endif;
                    endfor;
                endif;

                //UPDATE TOTAL ROOM RATE IN HOTEL DETAILS TABLE
                $arrFields_hotel_details = array('`total_room_rate`');
                $arrValues_hotel_details = array("$total_room_rate");
                $sqlWhere_hotel_details = " `itinerary_plan_hotel_details_ID` = '$hidden_itinerary_plan_hotel_details_ID' ";
                //UPDATE DETAILS
                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel_details, $arrValues_hotel_details, $sqlWhere_hotel_details)) :
                endif;

                $response['success'] = true;
                $response['u_result'] = true;
                $response['result_success'] = true;
            else :
                $response['success'] = false;
                $response['u_result'] = false;
                $response['result_success'] = false;
            endif;
        //endif;
        endif;
        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
