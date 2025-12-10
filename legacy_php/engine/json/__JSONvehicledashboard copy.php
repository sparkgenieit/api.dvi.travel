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


    echo "{";
    echo '"data":[';

    if ($_GET['show'] == 'show_idle') :
        $select_hotel_list_query = sqlQUERY_LABEL("SELECT v.`vehicle_id`, v.`vendor_id`, v.`vehicle_type_id` FROM `dvi_vehicle` v LEFT JOIN `dvi_confirmed_itinerary_vendor_vehicle_assigned` va ON v.`vehicle_id` = va.`vehicle_id` AND va.`status` = '1' AND va.`deleted` = '0' AND NOW() BETWEEN va.`trip_start_date_and_time`  AND va.`trip_end_date_and_time` AND NOW() < va.`trip_start_date_and_time` WHERE v.`status` = '1' AND v.`deleted` = '0' AND va.`vehicle_id` IS NULL ORDER BY v.`vehicle_id` DESC LIMIT 5") or die("#2-getTOTALCOUNT_LIST: " . sqlERROR_LABEL());
	elseif ($_GET['show'] == 'show_upcoming') :
        $select_hotel_list_query = sqlQUERY_LABEL("SELECT `driver_assigned_ID`, `itinerary_plan_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `driver_id` FROM `dvi_confirmed_itinerary_vendor_driver_assigned` WHERE `deleted` = '0' AND NOW() < `trip_start_date_and_time` ORDER BY `driver_assigned_ID` DESC LIMIT 5 ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
	elseif ($_GET['show'] == 'show_oncoming') :
        $select_hotel_list_query = sqlQUERY_LABEL("SELECT vd.`vehicle_id`, vd.`vendor_id`, vd.`vehicle_type_id`, vd.`vendor_vehicle_type_id` FROM `dvi_confirmed_itinerary_plan_vendor_eligible_list` ep JOIN `dvi_confirmed_itinerary_plan_vendor_vehicle_details` vd ON ep.`itinerary_plan_id` = vd.`itinerary_plan_id` AND ep.`itinerary_plan_vendor_eligible_ID` = vd.`itinerary_plan_vendor_eligible_ID` WHERE  ep.`deleted` = '0' AND ep.`itineary_plan_assigned_status` = '1'  AND vd.`deleted` = '0' AND  vd.`itinerary_route_date` = CURDATE();") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

	elseif ($_GET['show'] == 'show_service_vehicle') :
        $select_hotel_list_query = sqlQUERY_LABEL("SELECT `vehicle_id`, `vehicle_type_id`, `owner_name` FROM `dvi_vehicle` WHERE `deleted` = '0' AND (`vehicle_fc_expiry_date` < CURDATE() OR `insurance_end_date` < CURDATE()) ORDER BY `vehicle_id` DESC LIMIT 5 ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
	endif;


    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_id'];
        $vehicle_id = $fetch_list_data['vehicle_id'];
        $vendor_id = $fetch_list_data['vendor_id'];
        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
        $owner_name = $fetch_list_data['owner_name'];
        $vehicle_type =  getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
        $driver_id = $fetch_list_data['driver_id'];
        $get_vendor_name =  getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
        $get_driver_name =  getDRIVER_DETAILS('', $driver_id, 'driver_name');
        $get_arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $get_departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $get_vehicle_number =  getVENDORANDVEHICLEDETAILS($vehicle_id,'get_registration_number','');
        $get_vehicle_type_id =  getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'get_vehicle_type_id');
        $get_vehicle_type =  getVEHICLETYPE($get_vehicle_type_id, 'get_vehicle_type_title');

      
        
        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"get_vehicle_number": "' . $get_vehicle_number . '",';
        $datas .= '"get_vehicle_type": "' . $get_vehicle_type . '",';
        $datas .= '"owner_name": "' . $owner_name . '",';
        $datas .= '"get_vendor_name": "' . $get_vendor_name . '",';
        $datas .= '"vehicle_type": "' . $vehicle_type . '",';
        $datas .= '"get_driver_name": "' . $get_driver_name . '",';
        $datas .= '"get_arrival_location": "' . $get_arrival_location . '",';
        $datas .= '"get_departure_location": "' . $get_departure_location . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
