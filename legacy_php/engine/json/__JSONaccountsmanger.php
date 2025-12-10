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

    if ($_GET['type'] == 'all_accountsmanager') :
        $filterbyaccountsmanager = " ";
    elseif($_GET['type'] == 'paid_accountsmanager'):
        $filterbyaccountsmanager = " and `total_receivable_amount` = '0'";
    elseif($_GET['type'] == 'due_accountsmanager'):
        $filterbyaccountsmanager = " and `total_receivable_amount` != '0'";
    endif;

    $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `agent_id`, `staff_id`, `confirmed_itinerary_plan_ID`, `itinerary_quote_ID`, `total_billed_amount`, `total_received_amount`, `total_receivable_amount` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' {$filterbyaccountsmanager} ORDER BY `accounts_itinerary_details_ID` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
        $counter++;
        $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
        $agent_id = $fetch_list_data['agent_id'];
        $itinerary_quote_ID = $fetch_list_data['itinerary_quote_ID'];
        $total_billed_amount = $fetch_list_data['total_billed_amount'];
        $total_received_amount = $fetch_list_data['total_received_amount'];
        $total_receivable_amount = $fetch_list_data['total_receivable_amount'];
        $trip_start_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_start_date_and_time');
        $trip_end_date_and_time = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'trip_end_date_and_time');
        $arrival_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
        $departure_location = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
        $staff_id = get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'staff_id');
        $agent_name = getAGENT_details($agent_id, '', 'label');
        $travel_expert_id = getAGENT_details($agent_id, '', 'travel_expert_id');
        $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
        $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
     

        if ($roleID == 1): //ADMIN
            $username = $fetch_list_data["username"];
        elseif ($roleID == 3 && $staff_id != 0 && $agent_id == 0): //TE
            $username = "Travel Expert - <br>" . $fetch_list_data["staff_name"];
        elseif ($roleID == 4 && $staff_id == 0 && $agent_id != 0): //AGENT
            $username = "Agent - <br>" . $fetch_list_data["agent_name"];
        elseif ($roleID == 4 && $staff_id != 0 && $agent_id != 0): //AGENT STAFF
            $username = "Staff - <br>" . $fetch_list_data["staff_name"];
        endif;
      
        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '",';
        $datas .= '"itinerary_date": "' .date('d/m/Y h:i A', strtotime( $trip_start_date_and_time)) . ' to ' . date('d/m/Y h:i A', strtotime($trip_end_date_and_time)) . '",';
        $datas .= '"itinerary_location": "' . $arrival_location . ' to ' . $departure_location . '",';
        $datas .= '"agent_name": "' . $agent_name . '",';
        $datas .= '"customer_name": "' . $customer_name . '",';
        $datas .= '"travel_expert_name": "' . $travel_expert_name . '",';
        $datas .= '"total_billed_amount": " '. general_currency_symbol . ' ' . number_format($total_billed_amount,2). '",';
        $datas .= '"total_received_amount": "'. general_currency_symbol . ' ' . number_format($total_received_amount,2) . '",';
        $datas .= '"total_receivable_amount": "'. general_currency_symbol . ' ' . number_format($total_receivable_amount,2) . '",';
        $datas .= '"username": "' . $username . '",';
        $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
        $datas .= '"modify": "' . $itinerary_plan_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
