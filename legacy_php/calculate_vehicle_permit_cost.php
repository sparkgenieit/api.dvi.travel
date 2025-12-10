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

$itinerary_plan_ID = 8;

if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
    $select_itinerary_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
        $total_child_with_bed = $fetch_list_data["total_child_with_bed"];
        $total_child_without_bed = $fetch_list_data["total_child_without_bed"];
        $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
    endwhile;


    //FETCH ROUTE DETAILS
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

            //SELECT VEHICLE PERMITCOST DETAILS
            echo "<br>" . "ROUTE DATE : " . $itinerary_route_date;
            echo "<br>" . "FROM : " . $location_name;
            echo "<br>" . "TO : " . $next_visiting_location . "<br>";
            echo "-------------------------------------------------- <br><br>";

            $vehicleid = [];
            $vendorid =  [];
            $vendorbranchid = [];
            $permitcost = [];
            $select_source_location_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_name`='$source_location_state' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_source_state = sqlFETCHARRAY_LABEL($select_source_location_state)) :
                $source_state_id = $fetch_source_state['permit_state_id'];
            endwhile;

            $select_destination_location_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_name`='$destination_location_state' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_destination_state = sqlFETCHARRAY_LABEL($select_destination_location_state)) :
                $destination_state_id = $fetch_destination_state['permit_state_id'];
            endwhile;

            $select_itinerary_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_details_ID`, `itinerary_plan_id`, `vehicle_type_id`, `vehicle_count` FROM `dvi_itinerary_plan_vehicle_details`  WHERE `itinerary_plan_id`='$itinerary_plan_ID' AND `deleted`='0' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_vehicle_type_count = sqlNUMOFROW_LABEL($select_itinerary_vehicle_list_query);

            if ($total_vehicle_type_count > 0) :
                while ($fetch_itinerary_vehicle_data = sqlFETCHARRAY_LABEL($select_itinerary_vehicle_list_query)) :

                    $vehicle_type_id = $fetch_itinerary_vehicle_data['vehicle_type_id'];
                    $vehicle_count = $fetch_itinerary_vehicle_data['vehicle_count'];

                    $select_vehicle_list = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_code`, `vehicle_type_id`, `registration_number` FROM `dvi_vehicle`  WHERE `vehicle_type_id`='$vehicle_type_id' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    $total_vehicle_count = sqlNUMOFROW_LABEL($select_vehicle_list);

                    if ($total_vehicle_count > 0) :
                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_list)) :
                            $vehicle_id = $fetch_vehicle_data['vehicle_id'];
                            $vendor_id =  $fetch_vehicle_data['vendor_id'];
                            $vendor_branch_id = $fetch_vehicle_data['vendor_branch_id'];
                            $vehicle_code = $fetch_vehicle_data['vehicle_code'];
                            $registration_number = $fetch_vehicle_data['registration_number'];
                            $state_code = substr($registration_number, 0, 2);

                            $select_vehicle_permit_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_code`='$state_code' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            while ($fetch_vehicle_state = sqlFETCHARRAY_LABEL($select_vehicle_permit_state)) :
                                $vehicle_state_id = $fetch_vehicle_state['permit_state_id'];
                            endwhile;

                            echo "VECHILE: $registration_number <br> ";
                            echo "$vehicle_state_id == $source_state_id && $source_state_id == $destination_state_id <BR>";
                            if ($vehicle_state_id == $destination_state_id && $source_state_id == $destination_state_id) :
                                //echo "same<br>";
                                $vehicleid[] = $fetch_vehicle_data['vehicle_id'];
                                $vendorid[] =  $fetch_vehicle_data['vendor_id'];
                                $vendorbranchid[] = $fetch_vehicle_data['vendor_branch_id'];
                                $permitcost[] = 0;

                                echo "VEHICLE TYPE :" . $fetch_vehicle_data['vehicle_id'] . "<BR>";
                                echo "VENDOR ID :" .  $fetch_vehicle_data['vendor_id'] . "<BR>";
                                echo "BRANCH :" .  $fetch_vehicle_data['vendor_branch_id'] . "<BR>";
                                echo   "PERMIT COST - 0<br><BR>";
                            else :

                                //echo "different<br>";
                                $vehicleid[] = $fetch_vehicle_data['vehicle_id'];
                                $vendorid[] =  $fetch_vehicle_data['vendor_id'];
                                $vendorbranchid[] = $fetch_vehicle_data['vendor_branch_id'];

                                echo "VEHICLE TYPE :" . $fetch_vehicle_data['vehicle_id'] . "<BR>";
                                echo "VENDOR ID :" .  $fetch_vehicle_data['vendor_id'] . "<BR>";
                                echo "BRANCH :" .  $fetch_vehicle_data['vendor_branch_id'] . "<BR>";

                                //CALCULATE PERMIT COST
                                $select_vehicle_permit_cost = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted`='0' AND `status`='1' AND `vendor_id`='$vendor_id' AND `vehicle_type_id` ='$vehicle_type_id' AND `source_state_id`='$vehicle_state_id' AND `destination_state_id`='$destination_state_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_vehicle_permit_cost = sqlFETCHARRAY_LABEL($select_vehicle_permit_cost)) :
                                    $permit_cost = $fetch_vehicle_permit_cost['permit_cost'];
                                    $permitcost[] = $permit_cost;


                                    echo "PERMIT COST " . $permit_cost . "<br><BR>";
                                endwhile;

                            endif;


                        endwhile;
                    endif;


                    $lowest_permit_cost = $permitcost[0]; // Initialize with the first value
                    $lowest_index = 0; // Initialize with the index of the first value

                    for ($i = 1; $i < count($permitcost); $i++) :
                        if ($permitcost[$i] < $lowest_permit_cost) :
                            $lowest_permit_cost = $permitcost[$i];
                            $lowest_index = $i;
                        endif;
                    endfor;
                    echo "SELECTED VEHILCE<BR>";
                    echo "-------------------<BR>";
                    echo "VEHICLE TYPE :" .  $vehicleid[$lowest_index] . "<BR>";
                    echo "VENDOR ID :" .  $vendorid[$lowest_index] . "<BR>";
                    echo "BRANCH :" .  $vendorbranchid[$lowest_index] . "<BR>";
                    echo "PERMIT COST :" .  $lowest_permit_cost . "<BR>";


                endwhile;
            endif;

        endwhile;
    endif;
    $response['result'] = true;
endif;
