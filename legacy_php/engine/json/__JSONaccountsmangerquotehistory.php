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
    $quote_id = $_GET['quote_id'] ?? '';

    // Modify the query to perform a partial match with LIKE
    $select_accountsmanagermain_query = sqlQUERY_LABEL("
        SELECT `accounts_itinerary_details_ID`, `itinerary_quote_ID` 
        FROM `dvi_accounts_itinerary_details` 
        WHERE `deleted` = '0' 
        AND `itinerary_quote_ID` LIKE '%$quote_id%'
    ") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    if (sqlNUMOFROW_LABEL($select_accountsmanagermain_query)):
        $accounts_itinerary_details_IDs = [];
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagermain_query)) {
            $accounts_itinerary_details_IDs[] = $fetch_list_data['accounts_itinerary_details_ID'];
        }

        // If matching records are found, create a filter condition
        if (!empty($accounts_itinerary_details_IDs)) {
            $filter_accounts_itinerary = "AND `accounts_itinerary_details_ID` IN (" . implode(',', $accounts_itinerary_details_IDs) . ")";
        }

        // Perform the main query
        $select_accountsmanagerLIST_query = sqlQUERY_LABEL("
        SELECT 
            `accounts_itinerary_vehicle_transaction_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_vehicle_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Vehicle' AS `transaction_source`
        FROM `dvi_accounts_itinerary_vehicle_transaction_history`
        WHERE `deleted` = '0' {$filter_accounts_itinerary}
        UNION ALL
    
        SELECT 
            `accounts_itinerary_hotel_transaction_history_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_hotel_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Hotel' AS `transaction_source`
        FROM `dvi_accounts_itinerary_hotel_transaction_history`
        WHERE `deleted` = '0' {$filter_accounts_itinerary}
    
        UNION ALL
    
        SELECT 
            `dvi_accounts_itinerary_hotspot_transaction_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_hotspot_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Hotspot' AS `transaction_source`
        FROM `dvi_accounts_itinerary_hotspot_transaction_history`
        WHERE `deleted` = '0' {$filter_accounts_itinerary}
    
        UNION ALL
    
        SELECT 
            `accounts_itinerary_activity_transaction_history_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_activity_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Activity' AS `transaction_source`
        FROM `dvi_accounts_itinerary_activity_transaction_history`
        WHERE `deleted` = '0' {$filter_accounts_itinerary}
    
        UNION ALL
    
        SELECT 
            `accounts_itinerary_guide_transaction_ID`,
            `accounts_itinerary_details_ID`,
            `accounts_itinerary_guide_details_ID` AS transaction_ID,
            `transaction_amount`, 
            `transaction_date`, 
            `transaction_done_by`, 
            `mode_of_pay`, 
            `transaction_utr_no`, 
            `transaction_attachment`,
            'Guide' AS `transaction_source`
        FROM `dvi_accounts_itinerary_guide_transaction_history`
        WHERE `deleted` = '0' {$filter_accounts_itinerary}
    ") or die("#2-UNABLE_TO_COLLECT_TRANSACTION_LIST:" . sqlERROR_LABEL());

        $counter = 0; // Initialize counter
        $datas = ""; // Initialize data container

        // Process the combined results
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
            $counter++;
            $transaction_amount = $fetch_list_data['transaction_amount'];
            $transaction_date = date('d-m-Y h:i A', strtotime($fetch_list_data['transaction_date']));
            $transaction_done_by = $fetch_list_data['transaction_done_by'];
            $mode_of_pay = $fetch_list_data['mode_of_pay'];
            $transaction_utr_no = $fetch_list_data['transaction_utr_no'];
            $transaction_attachment = $fetch_list_data['transaction_attachment'];
            $transaction_source = $fetch_list_data['transaction_source'];
            $accounts_itinerary_details_ID = $fetch_list_data['accounts_itinerary_details_ID'];
            $transaction_ID = $fetch_list_data['transaction_ID'];


            if ($transaction_source == "Vehicle"):
                $vehicle_type_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'vehicle_type_id');
                $get_vehicle_type_title = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                $title = "Vehicle - $get_vehicle_type_title";
            elseif ($transaction_source == "Hotel"):
                $hotel_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'hotel_id');
                $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                $title = "Hotel - $hotel_name";
            elseif ($transaction_source == "Hotspot"):
                $hotspot_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'hotspot_id');
                $hotspot_name = $hotspot_name = getHOTSPOTDETAILS($hotspot_id, 'label');
                $title = "Hotspot - $hotspot_name";
            elseif ($transaction_source == "Activity"):
                $activity_ID = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'activity_id');
                $activity_name = getACTIVITYDETAILS($activity_ID, 'label', '');
                $title = "Activity - $activity_name";
            elseif ($transaction_source == "Guide"):
                $guide_id = getACCOUNTSfilter_MANAGER_DETAILS($accounts_itinerary_details_ID, $transaction_ID, 'guide_id');
                $guide_name = getGUIDEDETAILS($guide_id, 'label');
                $title = "Guide - $guide_name";
            endif;

            // Prepare the data string
            $datas .= "{";
            $datas .= '"count": "' . $counter . '",';
            $datas .= '"transaction_source": "' . $transaction_source . '",';
            $datas .= '"title": "' . $title . '",';
            $datas .= '"transaction_amount": "' . general_currency_symbol . ' ' . number_format(round($transaction_amount), 2) . '",';
            $datas .= '"transaction_date": "' . $transaction_date . '",';
            $datas .= '"transaction_done_by": "' . $transaction_done_by . '",';
            $datas .= '"mode_of_pay": "' . $mode_of_pay . '",';
            $datas .= '"transaction_utr_no": "' . $transaction_utr_no . '",';
            $datas .= '"transaction_attachment": "' . $transaction_attachment . '"';
            $datas .= " },";
        endwhile;

        $data_formatted = substr(trim($datas), 0, -1);
        echo $data_formatted;
        echo "]}";
    else:
        
    endif;
else :
    echo "Request Ignored !!!";
endif;
