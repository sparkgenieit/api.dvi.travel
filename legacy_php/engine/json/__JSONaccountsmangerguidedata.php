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

    if ($_GET['id'] == 1) :
        $filterbyaccountsmanager = " ";
    elseif ($_GET['id'] == 2):
        $filterbyaccountsmanager = " AND `total_balance` = '0'";
    elseif ($_GET['id'] == 3):
        $filterbyaccountsmanager = " AND `total_balance` != '0'";
    endif;

    // Get and format dates
    $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
    $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
    $agent_name = isset($_GET['agent_name']) ? $_GET['agent_name'] : '';
    $quote_id = isset($_GET['quote_id']) ? trim($_GET['quote_id']) : '';

    $formatted_from_date = dateformat_database($from_date);
    $formatted_to_date = dateformat_database($to_date);

    // Prepare filters
    $filterbyaccounts_date = !empty($from_date) && !empty($to_date) ?
        "AND DATE(`itinerary_route_date`) BETWEEN '$formatted_from_date' AND '$formatted_to_date'" : '';

    $filterbyaccountsagent = !empty($agent_name) ? "AND `agent_id` = '$agent_name'" : '';
    $filterbyaccountsquoteid = !empty($quote_id) ? "AND `itinerary_quote_ID` = '$quote_id'" : '';

    $getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$filterbyaccountsagent}{$filterbyaccountsquoteid}") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
    while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
        $itinerary_plan_ID = $getstatus_fetch['itinerary_plan_ID'];
        if ($itinerary_plan_ID):
            $filterbyaccountsagent_plan = "AND `itinerary_plan_ID` = '$itinerary_plan_ID'";
        else:
            $filterbyaccountsagent_plan = "";
        endif;

        $combined_filters = "{$filterbyaccountsmanager} {$filterbyaccounts_date} {$filterbyaccountsagent_plan}";

        $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_guide_details_ID`, `accounts_itinerary_details_ID`, `cnf_itinerary_guide_slot_cost_details_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_slot_cost_details_ID`, `route_guide_ID`, `guide_id`, `itinerary_route_date`, `guide_type`, `guide_slot`, `guide_slot_cost`, `total_payable`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_guide_details` WHERE `deleted` = '0' {$combined_filters}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
            $counter++;
            $accounts_itinerary_guide_details_ID = $fetch_list_data['accounts_itinerary_guide_details_ID'];
            $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
            $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
            $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
            $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
            $itinerary_route_ID = $fetch_list_data['itinerary_route_ID'];
            $guide_id = $fetch_list_data['guide_id'];
            $itinerary_route_date = $fetch_list_data['itinerary_route_date'];
            $guide_slot = $fetch_list_data['guide_slot'];
            $guide_slot_cost = $fetch_list_data['guide_slot_cost'];
            $total_payable = $fetch_list_data['total_payable'];
            $total_paid = $fetch_list_data['total_paid'];
            $total_balance = $fetch_list_data['total_balance'];
            $guide_name = getGUIDEDETAILS($guide_id, 'label');
            $guide_language = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'guide_language');
            $get_guide_language = getGUIDE_LANGUAGE_DETAILS($guide_language, 'label');
            $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
            $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
            $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
            $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
            $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
            $inhand_amount = round($total_received_amount - $total_payout_amount);

            if ($guide_slot == 0):
                $guide_slot_label = 'Slot 1: 8 AM to 1 PM, </br>Slot 2: 1 PM to 6 PM, </br>Slot 3: 6 PM to 9 PM';
            elseif ($guide_slot == 1):
                $guide_slot_label = 'Slot 1: 8 AM to 1 PM';
            elseif ($guide_slot == 2):
                $guide_slot_label = 'Slot 2: 1 PM to 6 PM';
            elseif ($guide_slot == 3):
                $guide_slot_label = 'Slot 3: 6 PM to 9 PM';
            endif;

            $datas .= "{";
            $datas .= '"count": "' . $counter . '",';
            $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
            $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '",';
            $datas .= '"itinerary_route_date": "' . date('d-m-Y', strtotime($itinerary_route_date)) . '",';
            $datas .= '"arrival_location": "' . $arrival_location . '",';
            $datas .= '"departure_location": "' . $departure_location . '",';
            $datas .= '"customer_name": "' . $customer_name . '",';
            $datas .= '"guide_name": "' . $guide_name . '",';
            $datas .= '"guide_language": "' . $get_guide_language . '",';
            $datas .= '"guide_slot_label": "' . $guide_slot_label . '",';
            $datas .= '"numeric_total_balance": "' . round($total_balance) . '",';
            $datas .= '"numeric_inhand_amount": "' . round($inhand_amount) . '",';
            $datas .= '"acc_guide_detail_id": "' . $accounts_itinerary_guide_details_ID . '",';
            $datas .= '"total_payable": " ' . general_currency_symbol . ' ' . number_format(round($total_payable), 2) . '",';
            $datas .= '"total_paid": "' . general_currency_symbol . ' ' . number_format(round($total_paid), 2) . '",';
            $datas .= '"total_balance": "' . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . '",';
            $datas .= '"total_received_amount": "' . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . '</br> '. $agent_name_format .'",';
            $datas .= '"inhand_amount": "' . general_currency_symbol . ' ' . number_format(round($inhand_amount), 2) . '",';
            $datas .= '"modify": "' . $guide_id . '"';
            $datas .= " },";

        endwhile; //end of while loop
    endwhile;
    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";

else :
    echo "Request Ignored !!!";
endif;
