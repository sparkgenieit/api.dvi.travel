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

    $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT `accounts_itinerary_vehicle_details_ID`, `vehicle_id`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vendor_branch_id`, `total_vehicle_qty`, `total_payable`, `total_paid`, `total_balance` FROM `dvi_accounts_itinerary_vehicle_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
        $counter++;
        $accounts_itinerary_vehicle_details_ID = $fetch_list_data['accounts_itinerary_vehicle_details_ID'];
        $vehicle_id = $fetch_list_data['vehicle_id'];
        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
        $vendor_id = $fetch_list_data['vendor_id'];
        $vendor_vehicle_type_id = $fetch_list_data['vendor_vehicle_type_id'];
        $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
        $total_vehicle_qty = $fetch_list_data['total_vehicle_qty'];
        $total_payable = $fetch_list_data['total_payable'];
        $total_paid = $fetch_list_data['total_paid'];
        $total_balance = $fetch_list_data['total_balance'];

        $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
        $get_vendorname = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid', '');
        $vendor_branch_name = getVENDORBRANCHDETAIL($vendor_branch_id, '', 'get_vendor_branch_name');
        
        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"itinerary_plan_ID": "' . $itinerary_ID . '",';
        $datas .= '"vehicle_id": "' . $vehicle_id . '",';
        $datas .= '"get_vehicle_type_title": "' . $get_vehicle_type_title . ' - ' . $get_vendorname . '",';
        $datas .= '"vendor_branch_name": "' . $vendor_branch_name . '",';
        $datas .= '"total_vehicle_qty": "' . $total_vehicle_qty . '",';
        $datas .= '"numeric_total_balance": "' . round($total_balance) . '",';
        $datas .= '"total_payable": " ' . general_currency_symbol . ' ' . number_format(round($total_payable), 2) . '",';
        $datas .= '"total_paid": "' . general_currency_symbol . ' ' . number_format(round($total_paid), 2) . '",';
        $datas .= '"total_balance": "' . general_currency_symbol . ' ' . number_format(round($total_balance), 2) . '",';
        $datas .= '"modify": "' . $accounts_itinerary_vehicle_details_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
