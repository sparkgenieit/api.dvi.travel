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
        $filterbyaccountsmanager = " and `total_balance` = '0'";
    elseif ($_GET['id'] == 3):
        $filterbyaccountsmanager = " and `total_balance` != '0'";
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

    // Combine all filters
    $combined_filters = "{$filterbyaccountsquoteid} {$filterbyaccountsagent}";

    $getstatus_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID` FROM `dvi_accounts_itinerary_details` WHERE `deleted` = '0' and `status` = '1' {$combined_filters} ") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
    while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatus_query)) :
        $itinerary_plan_ID = $getstatus_fetch['itinerary_plan_ID'];
        if ($itinerary_plan_ID):
            $filterbyaccountsagent_plan = "and `itinerary_plan_ID` = '$itinerary_plan_ID'";
        else:
            $filterbyaccountsagent_plan = "";
        endif;

        $getstatusDATE_query = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_eligible_ID` FROM `dvi_confirmed_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = $itinerary_plan_ID {$filterbyaccounts_date} GROUP BY `itinerary_plan_vendor_eligible_ID`") or die("#getROOMTYPE_DETAILS: get_ASSIGNED_HOTEL_FOR_ITINEARY_PLAN_DETAILS: " . sqlERROR_LABEL());
        while ($getstatus_fetch = sqlFETCHARRAY_LABEL($getstatusDATE_query)) :
            $itinerary_plan_vendor_eligible_ID = $getstatus_fetch['itinerary_plan_vendor_eligible_ID'];
            if ($itinerary_plan_vendor_eligible_ID):
                $filterbyaccounts_date_format = "and `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID'";
            else:
                $filterbyaccounts_date_format = "";
            endif;

            $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_details_ID`, `itinerary_plan_ID`, `vehicle_id`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vendor_branch_id`, `total_vehicle_qty`, `total_payable`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' {$filterbyaccountsagent_plan} {$filterbyaccounts_date_format} {$filterbyaccountsmanager}") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                $counter++;
                $accounts_itinerary_vehicle_details_ID = $fetch_list_data['accounts_itinerary_vehicle_details_ID'];
                $vehicle_id = $fetch_list_data['vehicle_id'];
                $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                $itinerary_plan_ID = $fetch_list_data['itinerary_plan_ID'];
                $vendor_id = $fetch_list_data['vendor_id'];
                $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
                $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
                $agent_id = getACCOUNTSfilter_MANAGER_DETAILS('', $itinerary_plan_ID, 'agent_ID');
            $agent_name_format = getAGENT_details($agent_id, '', 'agent_name');
            $customer_name = get_CONFIRMED_ITINEARY_CUSTOMER_DETAILS($itinerary_plan_ID, 'primary_customer_name');
                $total_vehicle_qty = $fetch_list_data['total_vehicle_qty'];
                $total_payable = $fetch_list_data['total_payable'];
                $total_paid = $fetch_list_data['total_paid'];
                $total_balance = $fetch_list_data['total_balance'];
                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
                $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
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
                $datas .= '"get_vendorname": "' . $get_vendorname . '",';
                $datas .= '"get_vehicle_type_title": "' . $get_vehicle_type_title . '",';
                $datas .= '"vendor_branch_name": "' . $vendor_branch_name . '",';
                $datas .= '"total_vehicle_qty": "' . $total_vehicle_qty . '",';
                $datas .= '"numeric_total_balance": "' . round($total_balance) . '",';
                $datas .= '"numeric_inhand_amount": "' . round($inhand_amount) . '",';
                $datas .= '"customer_name": "' . $customer_name . '",';
                $datas .= '"total_payable": " ' . general_currency_symbol . ' ' . number_format(round($total_payable), 2) . '",';
                $datas .= '"total_paid": "' . general_currency_symbol . ' ' . number_format(round($total_paid), 2) . '",';
                $datas .= '"total_balance": "' . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . '",';
                $datas .= '"total_received_amount": "' . general_currency_symbol . ' ' . number_format(round($total_received_amount), 2) . '</br> '. $agent_name_format .'",';
                $datas .= '"inhand_amount": "' . general_currency_symbol . ' ' . number_format(round($inhand_amount), 2) . '",';
                $datas .= '"modify": "' . $vehicle_id . '"';
                $datas .= " },";

            endwhile; //end of while loop
        endwhile;
    endwhile;

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
