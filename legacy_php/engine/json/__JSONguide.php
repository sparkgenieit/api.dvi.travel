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

    echo "{";
    echo '"data":[';

    if ($logged_guide_id):
        $filter_by_guide = " AND `guide_id` = '$logged_guide_id'";
    else:
        $filter_by_guide = "";
    endif;

    $select_guide_list_query = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name`, `guide_dob`, `guide_bloodgroup`, `guide_gender`, `guide_primary_mobile_number`, `guide_alternative_mobile_number`, `guide_email`, `guide_emergency_mobile_number`, `guide_language_proficiency`, `guide_aadhar_number`, `guide_experience`, `guide_country`, `guide_state`, `guide_city`,`gst_type`, `guide_gst`, `guide_available_slot`, `guide_bank_name`, `guide_bank_branch_name`, `guide_ifsc_code`, `guide_account_number`, `guide_preffered_for`, `applicable_hotspot_places`, `applicable_activity_places`,`status` FROM `dvi_guide_details` where  `deleted`='0' {$filter_by_guide} ORDER BY `guide_id` DESC") or die("#1-UNABLE_TO_COLLECT_GUIDE_LIST:" . sqlERROR_LABEL());

    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_guide_list_query)) :
        $counter++;
        $guide_id = $fetch_list_data['guide_id'];
        $guide_name = $fetch_list_data['guide_name'];
        $guide_primary_mobile_number = $fetch_list_data['guide_primary_mobile_number'];
        $guide_email = $fetch_list_data['guide_email'];
        $guide_bloodgroup = $fetch_list_data['guide_bloodgroup'];
        $guide_gender = $fetch_list_data['guide_gender'];
        $status = $fetch_list_data['status'];
        $guide_city = $fetch_list_data["guide_city"];
        $guide_aadhar_number = $fetch_list_data["guide_aadhar_number"];


        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"guide_name": "' . $guide_name . '",';
        $datas .= '"guide_primary_mobile_number": "' . $guide_primary_mobile_number . '",';
        $datas .= '"guide_email": "' . $guide_email . '",';
        $datas .= '"status": "' . $status . '",';
        $datas .= '"modify": "' . $guide_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
