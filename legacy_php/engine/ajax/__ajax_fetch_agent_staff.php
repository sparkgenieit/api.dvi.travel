<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited.
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2018-2020 Touchmark De`Science
*
*/
include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'agent_staff_selectize') :
        $options = [];
        $agent_ID = $_POST['agent_select'];

        if ($agent_ID != '' && $agent_ID != '0') {
            $filter_by_agent_id = "AND `agent_id`='$agent_ID'";
        } else {
            $filter_by_agent_id = "";
        }

        $selected_query = sqlQUERY_LABEL("SELECT `staff_id`, `staff_name` FROM `dvi_staff_details` WHERE `deleted` = '0' AND `status`='1' AND `roleID`= '4' {$filter_by_agent_id} ORDER BY `staff_id` ASC") or die("#1-getCITY: UNABLE_TO_GET_DATA: " . sqlERROR_LABEL());

        if (sqlNUMOFROW_LABEL($selected_query) > 0) :
            while ($fetch_data = sqlFETCHARRAY_LABEL($selected_query)) :
                $staff_name = $fetch_data["staff_name"];
                $options[] = [
                    "value" => $fetch_data['staff_id'],
                    "text" => $fetch_data['staff_name']
                ];
            endwhile;
        else :
            $options[] = [
                "value" => '',
                "text" => "No records found"
            ];
        endif;

        header('Content-Type: application/json');
        echo json_encode($options);

    endif;

else :
    echo "Request Ignored";
endif;
