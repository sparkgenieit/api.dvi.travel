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

    $staff_id = $_POST['staff_id'];
    $agent_ID = trim($_GET['agent_ID']);

    if ($agent_ID != '' && $agent_ID != 0) :
        $filter_by_agent_id = " AND `agent_id` = '$agent_ID' ";
    else :
        $filter_by_agent_id = '';
    endif;

    echo "{";
    echo '"data":[';

    $select_staff_list_query = sqlQUERY_LABEL("SELECT `staff_id`, `agent_id`, `staff_name`, `staff_mobile`, `staff_email`, `roleID`, `status` FROM `dvi_staff_details` WHERE `deleted` = '0' {$filter_by_agent_id} ORDER BY `staff_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_staff_list_query)) :
        $counter++;
        $staff_id = $fetch_list_data['staff_id'];
        $staff_name = $fetch_list_data['staff_name'];
        $staff_mobile = $fetch_list_data['staff_mobile'];
        $staff_email = $fetch_list_data['staff_email'];
        $status = $fetch_list_data['status'];
        $roleID = $fetch_list_data['roleID'];
        $get_role_name = getRole($roleID, 'label');
        if ($get_role_name) :
            $get_role_name = $get_role_name;
        else :
            $get_role_name = '--';
        endif;
        $agent_id = $fetch_list_data['agent_id'];
        $agent_name = getAGENT_details($agent_id, '', 'label');

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"staff_name": "' . $staff_name . '",';
        $datas .= '"staff_mobile": "' . $staff_mobile . '",';
        $datas .= '"staff_email": "' . $staff_email . '",';
        $datas .= '"get_role_name": "' . $get_role_name . '",';
        $datas .= '"agent_name": "' . $agent_name . '",';
        $datas .= '"status": "' . $status . '",';

        // $datas .= '"hotel_category": "' . $hotel_category . '",';
        $datas .= '"modify": "' . $staff_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
