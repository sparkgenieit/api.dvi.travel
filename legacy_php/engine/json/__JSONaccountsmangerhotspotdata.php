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

    $getstatus_query = sqlQUERY_LABEL("SELECT i.`itinerary_plan_ID`, r.`itinerary_route_ID`
     FROM `dvi_accounts_itinerary_details` i
     LEFT JOIN `dvi_confirmed_itinerary_route_details` r ON i.`itinerary_plan_ID` = r.`itinerary_plan_ID`
     WHERE i.`deleted` = '0' 
     AND i.`status` = '1' 
     {$filterbyaccountsagent} 
     {$filterbyaccountsquoteid} 
     {$filterbyaccounts_date}")
        or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());

    while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
        $itinerary_plan_ID = $getstatus_fetch['itinerary_plan_ID'];
        $itinerary_route_ID = $getstatus_fetch['itinerary_route_ID'];

        if ($itinerary_plan_ID):
            $filterbyaccountsagent_plan = "AND `itinerary_plan_ID` = '$itinerary_plan_ID'";
        else:
            $filterbyaccountsagent_plan = "";
        endif;

        if ($itinerary_route_ID):
            $filterbyaccounts_date_format = "AND `itinerary_route_ID` = '$itinerary_route_ID'";
        else:
            $filterbyaccounts_date_format = "";
        endif;

        $combined_filters = "{$filterbyaccountsmanager} {$filterbyaccounts_date_format} {$filterbyaccountsagent_plan}";


        $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_hotspot_details_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `route_hotspot_ID`, `hotspot_ID`, `hotspot_amount`, `total_payable`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_hotspot_details` WHERE `deleted` = '0' AND `hotspot_amount` > 0 {$combined_filters}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
            $counter++;
            $accounts_itinerary_hotspot_details_ID = $fetch_list_data['accounts_itinerary_hotspot_details_ID'];
            $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
            $itinerary_route_ID = $fetch_list_data['itinerary_route_ID'];
            $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
            $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
            $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
            $hotspot_ID = $fetch_list_data['hotspot_ID'];
            $itinerary_route_date = getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'get_route_date', '');
            $hotspot_amount = $fetch_list_data['hotspot_amount'];
            $total_payable = $fetch_list_data['total_payable'];
            $total_paid = $fetch_list_data['total_paid'];
            $total_balance = $fetch_list_data['total_balance'];
            $hotspot_name = getHOTSPOTDETAILS($hotspot_ID, 'label');
            $itinerary_quote_ID =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'itinerary_quote_ID');
            $arrival_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'arrival_location');
            $departure_location =  get_ITINEARY_CONFIRMED_PLAN_DETAILS($itinerary_plan_ID, 'departure_location');
            $total_payout_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_payout_amount');
            $total_received_amount = getACCOUNTS_MANAGER_DETAILS($itinerary_plan_ID, '', 'total_received_amount');
            $inhand_amount = round($total_received_amount - $total_payout_amount);


            $datas .= "{";
            $datas .= '"count": "' . $counter . '",';
            $datas .= '"itinerary_plan_ID": "' . $itinerary_plan_ID . '",';
            $datas .= '"itinerary_quote_ID": "' . $itinerary_quote_ID . '",';
            $datas .= '"arrival_location": "' . $arrival_location . '",';
            $datas .= '"departure_location": "' . $departure_location . '",';
            $datas .= '"itinerary_route_date": "' . date('d-m-Y', strtotime($itinerary_route_date)) . '",';
            $datas .= '"hotspot_name": "' . $hotspot_name . '",';
            $datas .= '"numeric_total_balance": "' . round($total_balance) . '",';
            $datas .= '"numeric_total_payable": "' . round($total_payable) . '",';
            $datas .= '"numeric_inhand_amount": "' . round($inhand_amount) . '",';
            $datas .= '"acc_hotspot_detail_id": "' . $accounts_itinerary_hotspot_details_ID . '",';
            $datas .= '"customer_name": "' . $customer_name . '",';
            $datas .= '"total_payable": " ' . general_currency_symbol . ' ' . number_format(round($total_payable), 2) . '",';
            $datas .= '"total_paid": "' . general_currency_symbol . ' ' . number_format(round($total_paid), 2) . '",';
            $datas .= '"total_balance": "' . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . '",';
            $datas .= '"total_received_amount": "' . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . '</br> '. $agent_name_format .'",';
            $datas .= '"inhand_amount": "' . general_currency_symbol . ' ' . number_format(round($inhand_amount), 2) . '",';
            $datas .= '"modify": "' . $hotspot_ID . '"';
            $datas .= " },";

        endwhile; //end of while loop
    endwhile;

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
