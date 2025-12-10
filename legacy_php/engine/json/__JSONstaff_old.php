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
    // echo "SELECT `staff_id`, `vendor_id`, `staff_name`, `staff_email`, `staff_mobile_number`,`status` FROM `dvi_staff` WHERE `deleted` = '0' ORDER BY `staff_id` DESC";
    // exit;
    $select_gstSETTINGLIST_query = sqlQUERY_LABEL("SELECT `staff_id`, `vendor_id`, `staff_name`, `staff_email`, `staff_mobile_number`,`status` FROM `dvi_staff` WHERE `deleted` = '0' ORDER BY `staff_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_gstSETTINGLIST_query)) :
        $counter++;
        $staff_id = $fetch_list_data['staff_id'];
        $vendor_id = $fetch_list_data['vendor_id'];
        $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid');
        $staff_name = $fetch_list_data['staff_name'];
        $staff_email = $fetch_list_data['staff_email'];
        $staff_mobile_number = $fetch_list_data['staff_mobile_number'];
        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"vendor_name": "' . $vendor_name . '",';
        $datas .= '"staff_name": "' . $staff_name . '",';
        $datas .= '"staff_email": "' . $staff_email . '",';
        $datas .= '"staff_mobile_number": "' . $staff_mobile_number . '",';
        $datas .= '"status": "' . $status . '",';
        // $datas .= '"status_label": "' . $status_label . '",';
        $datas .= '"modify": "' . $staff_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
