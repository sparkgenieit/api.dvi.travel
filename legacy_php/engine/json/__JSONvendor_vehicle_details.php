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


    $vendor_branch_id = getVENDORBRANCHDETAIL('', $logged_vendor_id, 'get_vendor_branch_id');

    echo "{";
    echo '"data":[';

    $select_VEHICLELIST_query = sqlQUERY_LABEL("SELECT `vendor_id`,`vehicle_id`, `vehicle_type_id`, `registration_number`, `vehicle_fc_expiry_date`, `insurance_end_date`,`status` FROM `dvi_vehicle` WHERE `deleted` = '0' and  `status` = '1' and `vendor_branch_id`='$vendor_branch_id' ORDER BY `vehicle_id`  DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_VEHICLELIST_query)) :
        $counter++;
        $vendor_id = $fetch_list_data['vendor_id'];
        $vehicle_id = $fetch_list_data['vehicle_id'];
        $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
        $vehicle_type_title = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
        $registration_number = $fetch_list_data['registration_number'];
        $vehicle_fc_expiry_date = date('d/m/Y', strtotime($fetch_list_data['vehicle_fc_expiry_date']));
        $insurance_end_date = date('d/m/Y', strtotime($fetch_list_data['insurance_end_date']));
        $status = $fetch_list_data['status'];

        if ($vehicle_fc_expiry_date < date('d/m/Y')):
            $vehicle_fc_expiry_date_status = '0';
            $fc_status = 'FC Expired';
        else:
            $vehicle_fc_expiry_date_status = '1';
            $fc_status = 'FC Active';
        endif;

        if ($insurance_end_date < date('d/m/Y')):
            $insurance_end_date_status = '0';
            $insurance_status = 'Insurance Expired';
        else:
            $insurance_end_date_status = '1';
            $insurance_status = 'Insurance Active';
        endif;

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vehicle_number": "' . $registration_number . '",';
        $datas .= '"vehicle_type": "' . $vehicle_type_title . '",';
        $datas .= '"fc_date": "' . $vehicle_fc_expiry_date . '",';
        $datas .= '"vehicle_fc_expiry_date_status": "' . $vehicle_fc_expiry_date_status . '",';
        $datas .= '"fc_status": "' . $fc_status . '",';
        $datas .= '"insurance_end_date_status": "' . $insurance_end_date_status . '",';
        $datas .= '"insurance_end_date": "' . $insurance_end_date . '",';
        $datas .= '"insurance_status": "' . $insurance_status . '",';
        $datas .= '"modify": "' . $vehicle_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
