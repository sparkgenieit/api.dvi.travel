<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2022 Touchmark De`Science
*
*/

include_once('../../jackus.php');
$phrase = $_GET['phrase'];
$return_arr = array();

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')): //CHECK AJAX REQUEST

    if (!empty($logged_vendor_id) && $logged_vendor_id != '0') {
        $query = "SELECT dip.itinerary_quote_ID
              FROM dvi_confirmed_itinerary_plan_details dip
              LEFT JOIN dvi_itinerary_plan_vendor_eligible_list vel
              ON vel.itinerary_plan_id = dip.itinerary_plan_ID
              AND vel.vendor_id = $logged_vendor_id
              AND vel.itineary_plan_assigned_status = 1
              WHERE dip.`itinerary_quote_ID` LIKE '$phrase%'
              AND dip.deleted = '0'
              AND vel.itinerary_plan_id IS NOT NULL";
    } else {
        $query = "SELECT `itinerary_quote_ID` 
              FROM `dvi_confirmed_itinerary_plan_details` 
              WHERE `deleted`='0' AND `status`='1' AND `itinerary_quote_ID` LIKE '$phrase%'";
    }

    $fetch = sqlQUERY_LABEL("$query") or die("#1_UNABLE_TO_FETCH_DATA:" . sqlERROR_LABEL());

    if (sqlNUMOFROW_LABEL($fetch) > 0) {
        while ($row = sqlFETCHARRAY_LABEL($fetch)) {
            $row_array['get_quote_ID'] = $row['itinerary_quote_ID'];
            array_push($return_arr, $row_array);
        }
    } else {
        $row_array['get_quote_ID'] = "$phrase";
        array_push($return_arr, $row_array);
    }
    echo json_encode($return_arr);

else :
    echo "Request Ignored !!!";
endif;
