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


    $agent_id = trim($_GET['aid']);
    if ($agent_id) :
        $filter_by_agent_id = "AND `agent_ID` = '$agent_id'";
    else :
        $filter_by_agent_id = "";
    endif;

    echo "{";
    echo '"data":[';

    $select_subscription_list_query = sqlQUERY_LABEL("SELECT `agent_subscribed_plan_additional_info_ID`, `agent_ID`, `agent_subscribed_plan_ID`, `no_of_additional_staff`, `total_additional_staff_charges` FROM `dvi_agent_subscribed_plans_additional_info` WHERE `deleted` = '0' AND `status` = '0' {$filter_by_agent_id} ORDER BY `agent_subscribed_plan_additional_info_ID` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_subscription_list_query)) :
        $counter++;
        $agent_subscribed_plan_additional_info_ID = $fetch_list_data['agent_subscribed_plan_additional_info_ID'];
        $agent_id = $fetch_list_data['agent_ID'];
        $agent_name = getAGENT_details($agent_id, '', 'label');
        $agent_subscribed_plan_ID = $fetch_list_data['agent_subscribed_plan_ID'];
        $subscription_plan_title =  get_AGENT_SUBSCRIBED_PLAN_DETAILS($agent_subscribed_plan_ID, '', 'subscription_plan_title');
        $no_of_additional_staff = $fetch_list_data['no_of_additional_staff'];
        $total_additional_staff_charges = $fetch_list_data['total_additional_staff_charges'];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"agent_name": "' . $agent_name . '",';
        $datas .= '"subscription_plan_title": "' . $subscription_plan_title . '",';
        $datas .= '"no_of_additional_staff": "' . $no_of_additional_staff . '",';
        $datas .= '"total_additional_staff_charges": "' . $total_additional_staff_charges . '",';
        $datas .= '"modify": "' . $agent_subscribed_plan_additional_info_ID . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
