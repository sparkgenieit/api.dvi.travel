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

// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
include_once('../../jackus.php');


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';
        $quote_id = $_POST['quote_id'] ?? '';
        
        $formatted_from_date = !empty($from_date) ? dateformat_database($from_date) : '';
        $formatted_to_date = !empty($to_date) ? dateformat_database($to_date) : '';
        
        $filterbyaccounts_date_main = (!empty($formatted_from_date) && !empty($formatted_to_date)) ?
        "AND (
            (DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
            (DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
            ('$formatted_from_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
            ('$formatted_to_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
        )" : '';
        
        $accounts_itinerary_details_ID = !empty($quote_id) ? getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts') : '';
        $filterbyaccountsquoteid = (!empty($quote_id) && $quote_id != 'null') ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';
        
        $get_accounts_data_query = sqlQUERY_LABEL("
            SELECT itinerary_plan_ID, agent_id
            FROM dvi_accounts_itinerary_details
            WHERE deleted = '0' 
                AND `total_payable_amount` = `total_payout_amount`
                AND status = '1' 
                {$filterbyaccounts_date_main} 
                {$filterbyaccountsquoteid}
        ") or die(json_encode(["error" => "#get_accounts_data_query: " . sqlERROR_LABEL()]));
        
        $response = ["show" => sqlNUMOFROW_LABEL($get_accounts_data_query) > 0];
        echo json_encode($response);

    elseif ($_GET['type'] == 'show_form_purchase') :

        $from_date = $_POST['from_date'] ?? '';
        $to_date = $_POST['to_date'] ?? '';
        $quote_id = $_POST['quote_id'] ?? '';
        
        $formatted_from_date = !empty($from_date) ? dateformat_database($from_date) : '';
        $formatted_to_date = !empty($to_date) ? dateformat_database($to_date) : '';
        
        $filterbyaccounts_date_main = (!empty($formatted_from_date) && !empty($formatted_to_date)) ?
        "AND (
            (DATE(`trip_start_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
            (DATE(`trip_end_date_and_time`) BETWEEN '$formatted_from_date' AND '$formatted_to_date') OR
            ('$formatted_from_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`)) OR
            ('$formatted_to_date' BETWEEN DATE(`trip_start_date_and_time`) AND DATE(`trip_end_date_and_time`))
        )" : '';
        
        $accounts_itinerary_details_ID = !empty($quote_id) ? getACCOUNTSfilter_MANAGER_DETAILS('', $quote_id, 'itinerary_quote_ID_accounts') : '';
        $filterbyaccountsquoteid = (!empty($quote_id) && $quote_id != 'null') ? "AND `accounts_itinerary_details_ID` = '$accounts_itinerary_details_ID'" : '';

        
        $get_accountsdata_query = sqlQUERY_LABEL("
          SELECT 
      itinerary_plan_ID,
      agent_id
  FROM 
      dvi_accounts_itinerary_details
  WHERE 
     deleted = '0' 
    AND status = '1' {$filterbyaccounts_date_main} {$filterbyaccountsquoteid}
        ") or die(json_encode(["error" => "#get_accountsdata_query: " . sqlERROR_LABEL()]));
        
        $response = ["show" => sqlNUMOFROW_LABEL($get_accountsdata_query) > 0];
        echo json_encode($response);

?>
<?php
    endif;
endif;
?>