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

    $itinerary_plan_ID = trim($_GET['itinerary_plan_ID']);

    echo "{";
    echo '"data":[';

    $select_list_query = sqlQUERY_LABEL("SELECT `confirmed_itinerary_incidental_expenses_history_ID`, `itinerary_plan_id`, `itinerary_route_id`, `component_type`, `component_id`, `incidental_amount`, `reason`, `createdon` FROM `dvi_confirmed_itinerary_incidental_expenses_history` WHERE `deleted` = '0' AND `itinerary_plan_id` = $itinerary_plan_ID") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_list_query)) :
        $counter++;
        $confirmed_itinerary_incidental_expenses_history_ID = $fetch_list_data['confirmed_itinerary_incidental_expenses_history_ID'];
        $itinerary_plan_id = $fetch_list_data['itinerary_plan_id'];
        $itinerary_route_id = $fetch_list_data['itinerary_route_id'];
        $components_type = $fetch_list_data['component_type'];
        $component_id = $fetch_list_data['component_id'];
        $incidental_amount = $fetch_list_data['incidental_amount'];
        $reason = $fetch_list_data['reason'];
        $createdon = date('d-m-Y h:i A', strtotime($fetch_list_data['createdon']));
        $route_date = date('d-m-Y', strtotime(getITINEARY_CONFIRMED_ROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'get_route_date', '')));

        if($components_type == 1):
            $component_type = 'Guide';
            $component_name = getGUIDEDETAILS($component_id, 'label');
        elseif($components_type == 2):
            $component_type = 'Hotspot';
            $component_name = getHOTSPOTDETAILS($component_id, 'label');
        elseif($components_type == 3):
            $component_type = 'Activity';
            $component_name = getACTIVITYDETAILS($component_id, 'label', '');
        elseif($components_type == 4):
            $component_type = 'Hotel';
            $component_name =getHOTEL_DETAIL($component_id, '', 'label');
        elseif($components_type == 5):
            $component_type = 'Vendor';
            $component_name =getVENDOR_DETAILS($component_id,'label');
            $route_date =  '--';
        endif;

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"route_date": "' . $route_date . '",';
        $datas .= '"component_type": "' . $component_type . '",';
        $datas .= '"component_name": "' . $component_name . '",';
        $datas .= '"incidental_amount": "' . general_currency_symbol . ' ' . number_format(round($incidental_amount),2) . '",';
        $datas .= '"date": "' . $createdon . '",';
        $datas .= '"reason": "' . $reason . '",';
        $datas .= '"modify": "' . $confirmed_itinerary_incidental_expenses_history_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
