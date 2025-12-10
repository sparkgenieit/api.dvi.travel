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

    $select_gstSETTINGLIST_query = sqlQUERY_LABEL("SELECT `gst_setting_id`, `gst_title`, `gst_value`, `cgst_value`,`sgst_value`,`igst_value`,`status` FROM `dvi_gst_setting` WHERE `deleted` = '0' ORDER BY `gst_setting_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_gstSETTINGLIST_query)) :
        $counter++;
        $gst_setting_id = $fetch_list_data['gst_setting_id'];
        $gst_title = $fetch_list_data['gst_title'];
        $gst_value = $fetch_list_data['gst_value'];
        $cgst_value = $fetch_list_data['cgst_value'];
        $sgst_value = $fetch_list_data['sgst_value'];
        $igst_value = $fetch_list_data['igst_value'];
        $status = $fetch_list_data['status'];
        $status_label = $fetch_list_data["status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"gst_title": "' . $gst_title . '",';
        $datas .= '"gst_value": "' . $gst_title . '",';
        $datas .= '"status": "' . $status . '",';
        // $datas .= '"status_label": "' . $status_label . '",';
        $datas .= '"modify": "' . $gst_setting_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
