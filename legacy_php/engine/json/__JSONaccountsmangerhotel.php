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

use function PHPSTORM_META\type;

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    echo "{";
    echo '"data":[';

    $itinerary_ID = $_GET['ID'];

    $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_hotel_details_ID`, `itinerary_plan_ID`, `hotel_id`, `total_hotel_cost`, `total_hotel_tax_amount`, `total_no_of_days`, `total_payable`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_ID' ORDER BY `accounts_itinerary_details_ID` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
        $counter++;
        $accounts_itinerary_hotel_details_ID = $fetch_list_data['accounts_itinerary_hotel_details_ID'];
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $hotel_id = $fetch_list_data['hotel_id'];
        $total_hotel_cost = $fetch_list_data['total_hotel_cost'];
        $total_hotel_tax_amount = $fetch_list_data['total_hotel_tax_amount'];
        $total_no_of_days = $fetch_list_data['total_no_of_days'];
        $total_payable = $fetch_list_data['total_payable'];
        $total_paid = $fetch_list_data['total_paid'];
        $total_balance = $fetch_list_data['total_balance'];

        $itinerary_route_date = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $hotel_id, 'itinerary_route_date');
        $itinerary_route_location = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $hotel_id, 'itinerary_route_location');
        $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
        $room_type_id = get_CONFIRMED_ITINEARY_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_date, 'get_room_type_id');
        $room_type_name  = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');

        $select_HOTELROOMLIST_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_room_details_ID`, `itinerary_plan_hotel_details_id`, `group_type`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `hotel_id`, `room_type_id`, `room_id`, `room_qty`, `room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `breakfast_cost_per_person`, `lunch_cost_per_person`, `dinner_cost_per_person`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_confirmed_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_ID' AND `hotel_id` = '$hotel_id' AND `room_type_id` = '$room_type_id'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_HOTELROOMLIST_query)) :
            $breakfast_required = $fetch_list_data['breakfast_required'];
            $lunch_required = $fetch_list_data['lunch_required'];
            $dinner_required = $fetch_list_data['dinner_required'];
            $breakfast_cost_per_person = $fetch_list_data['breakfast_cost_per_person'];
            $lunch_cost_per_person = $fetch_list_data['lunch_cost_per_person'];
            $dinner_cost_per_person = $fetch_list_data['dinner_cost_per_person'];

            if ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $lunch_required == 1 && $lunch_cost_per_person > 0 && $dinner_required == 1 && $dinner_cost_per_person > 0):
                $hotel_meal_label = 'AP';
            elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $dinner_required == 1 && $dinner_cost_per_person > 0) :
                $hotel_meal_label = 'MAP';
            elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $lunch_required == 1 && $lunch_cost_per_person > 0):
                $hotel_meal_label = 'MAP';
            elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0):
                $hotel_meal_label = 'CP';
            else:
                $hotel_meal_label = 'No Meal Plan';
            endif;
        endwhile;

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
        $datas .= '"itinerary_route_date": "' . date('d-m-Y', strtotime($itinerary_route_date)) . '",';
        $datas .= '"total_no_of_days": "' . $total_no_of_days . '",';
        $datas .= '"itinerary_route_location": "' . $itinerary_route_location . '",';
        $datas .= '"hotel_id": "' . $hotel_id . '",';
        $datas .= '"hotel_name": "' . $hotel_name . '",';
        $datas .= '"room_type_name": "' . $room_type_name . '",';
        $datas .= '"hotel_meal_label": "' . $hotel_meal_label . '",';
        $datas .= '"numeric_total_balance": "' . round($total_balance) . '",';
        $datas .= '"total_payable": " ' . general_currency_symbol . ' ' . number_format(round($total_payable), 2) . '",';
        $datas .= '"total_paid": "' . general_currency_symbol . ' ' . number_format(round($total_paid), 2) . '",';
        $datas .= '"total_balance": "' . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . '",';
        $datas .= '"modify": "' . $accounts_itinerary_hotel_details_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
