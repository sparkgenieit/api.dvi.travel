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
    $filter_by_vendor = '';

    if ($logged_vendor_id != '' &&  $logged_vendor_id != '0'):
        $filter_by_vendor = "`vendor_id` = '$logged_vendor_id' AND";
    endif;

    $current_DATETIME = date('Y-m-d H:i:s');
    $current_DATE = date('Y-m-d');



    if ($_GET['show'] == 'show_idle') :

        $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id` FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE {$filter_by_vendor} '$current_DATETIME' BETWEEN `trip_start_date_and_time` AND `trip_end_date_and_time`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        if (sqlNUMOFROW_LABEL($select_vehicle_list_query) > 0) :
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                $vendor_id = $fetch_list_data['vendor_id'];
                $vehicle_id = $fetch_list_data['vehicle_id'];
                $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];

                $select_vehicle_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_branch_id`, `registration_number` FROM `dvi_vehicle` WHERE `vendor_id` != $vendor_id  AND `vehicle_type_id` != $vendor_vehicle_type_id AND `vehicle_id` != $vehicle_id AND `status` = '1' AND `deleted` = '0' ORDER BY `vehicle_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicle_query)) :
                    $counter++;
                    $vendor_id = $fetch_data['vendor_id'];
                    $vendor_branch_id = $fetch_data['vendor_branch_id'];
                    $registration_number = $fetch_data['registration_number'];
                    $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                    $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');

                    $datas .= "{";
                    $datas .= '"count": "' . $counter . '",';
                    $datas .= '"vendor_name": "' . $vendor_name . '",';
                    $datas .= '"branch_name": "' . $branch_name . '",';
                    $datas .= '"registration_number": "' . $registration_number . '"';
                    $datas .= " },";
                endwhile;
            endwhile; //end of while loop
        else:
            $select_vehicle_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_branch_id`, `registration_number` FROM `dvi_vehicle` WHERE `status` = '1' AND `deleted` = '0' ORDER BY `vehicle_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicle_query)) :
                $counter++;
                $vendor_id = $fetch_data['vendor_id'];
                $vendor_branch_id = $fetch_data['vendor_branch_id'];
                $registration_number = $fetch_data['registration_number'];
                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');

                $datas .= "{";
                $datas .= '"count": "' . $counter . '",';
                $datas .= '"vendor_name": "' . $vendor_name . '",';
                $datas .= '"branch_name": "' . $branch_name . '",';
                $datas .= '"registration_number": "' . $registration_number . '"';
                $datas .= " },";
            endwhile;
        endif;

    elseif ($_GET['show'] == 'show_upcoming') :
        if ($logged_agent_id != '' &&  $logged_agent_id != '0'):
            $select_itinerary_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `agent_id` = $logged_agent_id") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_list_query)) :
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE `itinerary_plan_id` = $itinerary_plan_ID AND `trip_start_date_and_time` > '$current_DATETIME'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                    $counter++;
                    $itinerary_plan_ID = $fetch_list_data['itinerary_plan_id'];
                    $vendor_id = $fetch_list_data['vendor_id'];
                    $vehicle_id = $fetch_list_data['vehicle_id'];
                    $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
                    $driver_id =  getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                    $vendor_branch_id = get_vehicle_DETAILS($vehicle_id, 'vendor_branch_id');
                    $vehicle_type_id = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_ID($itinerary_plan_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'get_vehicle_type_id');
                    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                    $get_arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                    $get_departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                    $start_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                    $end_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                    $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                    $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                    $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                    $vehicle_type =  getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                    $driver_name =  getDRIVER_DETAILS('', $driver_id, 'driver_name');
                    $driver_mobile_no =  getDRIVER_DETAILS('', $driver_id, 'mobile_no');

                    $datas .= "{";
                    $datas .= '"count": "' . $counter . '",';
                    $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
                    $datas .= '"get_arrival_location": "' . $get_arrival_location . '",';
                    $datas .= '"get_departure_location": "' . $get_departure_location . '",';
                    $datas .= '"start_date_and_time": "' .  $start_date_and_time . '",';
                    $datas .= '"end_date_and_time": "' .  $end_date_and_time . '",';
                    $datas .= '"customer_name": "' .  $customer_name . '",';
                    $datas .= '"vendor_name": "' . $vendor_name . '",';
                    $datas .= '"branch_name": "' . $branch_name . '",';
                    $datas .= '"vehicle_type": "' . $vehicle_type . '",';
                    $datas .= '"driver_name": "' . $driver_name . '",';
                    $datas .= '"driver_mobile_no": "' . $driver_mobile_no . '",';
                    $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '"';
                    $datas .= " },";

                endwhile; //end of while loop
            endwhile;
        else:
            $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE {$filter_by_vendor} `trip_start_date_and_time` > '$current_DATETIME'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                $counter++;
                $itinerary_plan_ID = $fetch_list_data['itinerary_plan_id'];
                $vendor_id = $fetch_list_data['vendor_id'];
                $vehicle_id = $fetch_list_data['vehicle_id'];
                $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
                $vendor_branch_id = get_vehicle_DETAILS($vehicle_id, 'vendor_branch_id');
                $driver_id =  getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                $vehicle_type_id = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_ID($itinerary_plan_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'get_vehicle_type_id');
                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                $get_arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                $get_departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                $start_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                $end_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                $vehicle_type =  getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                $driver_name =  getDRIVER_DETAILS('', $driver_id, 'driver_name');
                $driver_mobile_no =  getDRIVER_DETAILS('', $driver_id, 'mobile_no');

                $datas .= "{";
                $datas .= '"count": "' . $counter . '",';
                $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
                $datas .= '"get_arrival_location": "' . $get_arrival_location . '",';
                $datas .= '"get_departure_location": "' . $get_departure_location . '",';
                $datas .= '"start_date_and_time": "' .  $start_date_and_time . '",';
                $datas .= '"end_date_and_time": "' .  $end_date_and_time . '",';
                $datas .= '"customer_name": "' .  $customer_name . '",';
                $datas .= '"vendor_name": "' . $vendor_name . '",';
                $datas .= '"branch_name": "' . $branch_name . '",';
                $datas .= '"vehicle_type": "' . $vehicle_type . '",';
                $datas .= '"driver_name": "' . $driver_name . '",';
                $datas .= '"driver_mobile_no": "' . $driver_mobile_no . '",';
                $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '"';
                $datas .= " },";

            endwhile; //end of while loop
        endif;
    elseif ($_GET['show'] == 'show_oncoming') :
        if ($logged_agent_id != '' &&  $logged_agent_id != '0'):
            $select_itinerary_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_confirmed_itinerary_plan_details` WHERE `deleted` = '0' AND `agent_id` = $logged_agent_id") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_list_query)) :
                $itinerary_plan_ID = $fetch_data['itinerary_plan_ID'];
                $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE {$filter_by_vendor} `itinerary_plan_id` = $itinerary_plan_ID AND '$current_DATETIME' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                    $counter++;
                    $itinerary_plan_ID = $fetch_list_data['itinerary_plan_id'];
                    $vendor_id = $fetch_list_data['vendor_id'];
                    $vehicle_id = $fetch_list_data['vehicle_id'];
                    $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
                    $driver_id =  getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                    $vendor_branch_id = get_vehicle_DETAILS($vehicle_id, 'vendor_branch_id');
                    $vehicle_type_id = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_ID($itinerary_plan_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'get_vehicle_type_id');
                    $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                    $get_arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                    $get_departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                    $start_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                    $end_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                    $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                    $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                    $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                    $vehicle_type =  getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                    $driver_name =  getDRIVER_DETAILS('', $driver_id, 'driver_name');
                    $driver_mobile_no =  getDRIVER_DETAILS('', $driver_id, 'mobile_no');

                    $datas .= "{";
                    $datas .= '"count": "' . $counter . '",';
                    $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
                    $datas .= '"get_arrival_location": "' . $get_arrival_location . '",';
                    $datas .= '"get_departure_location": "' . $get_departure_location . '",';
                    $datas .= '"start_date_and_time": "' .  $start_date_and_time . '",';
                    $datas .= '"end_date_and_time": "' .  $end_date_and_time . '",';
                    $datas .= '"customer_name": "' .  $customer_name . '",';
                    $datas .= '"vendor_name": "' . $vendor_name . '",';
                    $datas .= '"branch_name": "' . $branch_name . '",';
                    $datas .= '"vehicle_type": "' . $vehicle_type . '",';
                    $datas .= '"driver_name": "' . $driver_name . '",';
                    $datas .= '"driver_mobile_no": "' . $driver_mobile_no . '",';
                    $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '"';
                    $datas .= " },";

                endwhile;
            endwhile;
        else:
            $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `trip_start_date_and_time`, `trip_end_date_and_time` FROM `dvi_confirmed_itinerary_vendor_vehicle_assigned` WHERE {$filter_by_vendor} '$current_DATETIME' BETWEEN `trip_start_date_and_time` AND `trip_end_date_and_time`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                $counter++;
                $itinerary_plan_ID = $fetch_list_data['itinerary_plan_id'];
                $vendor_id = $fetch_list_data['vendor_id'];
                $vehicle_id = $fetch_list_data['vehicle_id'];
                $driver_id =  getASSIGNED_DRIVER($itinerary_plan_ID, 'driver_id');
                $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
                $vendor_branch_id = get_vehicle_DETAILS($vehicle_id, 'vendor_branch_id');
                $vehicle_type_id = get_ITINEARY_CONFIRMED_PLAN_VEHICLE_TYPE_ID($itinerary_plan_ID, $vendor_id, $vendor_vehicle_type_id, $vehicle_id, 'get_vehicle_type_id');
                $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                $get_arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
                $get_departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
                $start_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time')));
                $end_date_and_time =   date('d-m-Y h:i A', strtotime(get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time')));
                $itinerary_quote_ID = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
                $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
                $vehicle_type =  getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                $driver_name =  getDRIVER_DETAILS('', $driver_id, 'driver_name');
                $driver_mobile_no =  getDRIVER_DETAILS('', $driver_id, 'mobile_no');


                $datas .= "{";
                $datas .= '"count": "' . $counter . '",';
                $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
                $datas .= '"get_arrival_location": "' . $get_arrival_location . '",';
                $datas .= '"get_departure_location": "' . $get_departure_location . '",';
                $datas .= '"start_date_and_time": "' .  $start_date_and_time . '",';
                $datas .= '"end_date_and_time": "' .  $end_date_and_time . '",';
                $datas .= '"customer_name": "' .  $customer_name . '",';
                $datas .= '"vendor_name": "' . $vendor_name . '",';
                $datas .= '"branch_name": "' . $branch_name . '",';
                $datas .= '"vehicle_type": "' . $vehicle_type . '",';
                $datas .= '"driver_name": "' . $driver_name . '",';
                $datas .= '"driver_mobile_no": "' . $driver_mobile_no . '",';
                $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '"';
                $datas .= " },";

            endwhile;
        endif;

    elseif ($_GET['show'] == 'show_service_vehicle') :

        $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_branch_id`, `registration_number` FROM `dvi_vehicle` WHERE {$filter_by_vendor} `deleted` = '0' AND  (`vehicle_fc_expiry_date` < '$current_DATE' OR `insurance_end_date` < '$current_DATE')") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
            $counter++;
            $vendor_id = $fetch_list_data['vendor_id'];
            $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
            $registration_number = $fetch_list_data['registration_number'];
            $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
            $branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');

            $datas .= "{";
            $datas .= '"count": "' . $counter . '",';
            $datas .= '"vendor_name": "' . $vendor_name . '",';
            $datas .= '"branch_name": "' . $branch_name . '",';
            $datas .= '"registration_number": "' . $registration_number . '"';
            $datas .= " },";

        endwhile; //end of while loop
    endif;


    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
