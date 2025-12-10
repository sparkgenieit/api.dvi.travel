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


    $hotel_state = $_GET['hotel_state'];
    $hotel_city = $_GET['hotel_city'];

    if ($hotel_state != '' && $hotel_state != '0') :
        $filterbyhotelstate = " and `hotel_state` = '$hotel_state' ";
    else :
        $filterbyhotelstate = "";
    endif;

    if ($hotel_city != '' && $hotel_city != '0') :
        $filterbyhotelcity = " and `hotel_city` = '$hotel_city' ";
    else :
        $filterbyhotelcity = "";
    endif;

    echo "{";
    echo '"data":[';

    $select_hotel_list_query = sqlQUERY_LABEL("SELECT `hotel_id`, `hotel_name`, `hotel_code`, `hotel_mobile`, `hotel_email`, `hotel_margin`, `hotel_margin_gst_type`, `hotel_margin_gst_percentage`, `hotel_hotspot_status`, `hotel_country`, `hotel_city`, `hotel_state`, `hotel_address`, `hotel_pincode`,`hotel_category`, `status` FROM `dvi_hotel` WHERE `deleted` = '0' {$filterbyhotelstate} {$filterbyhotelcity} ORDER BY `hotel_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
    while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
        $counter++;
        $hotel_id = $fetch_list_data['hotel_id'];
        $hotel_name = $fetch_list_data['hotel_name'];
        $hotel_code = $fetch_list_data['hotel_code'];
        $hotel_mobile = $fetch_list_data['hotel_mobile'];
        $hotel_email = $fetch_list_data['hotel_email'];
        $hotel_country = $fetch_list_data['hotel_country'];
        $status = $fetch_list_data['status'];
        $hotel_mobile = str_replace(',', "<br>", $fetch_list_data['hotel_mobile']);
        $hotel_mobile_formatted = str_replace(',', "/", $fetch_list_data['hotel_mobile']);

        $hotel_state = $fetch_list_data["hotel_state"];
        $hotel_state = getSTATELIST('', $hotel_state, 'state_label');

        $hotel_city = $fetch_list_data["hotel_city"];
        $hotel_city = getCITYLIST($hotel_state, $hotel_city, 'city_label');

        $hotel_category = $fetch_list_data["hotel_category"];
        $hotel_category = getHOTEL_CATEGORY_DETAILS($hotel_category, 'label');
        $hotel_margin = $fetch_list_data["hotel_margin"];
        $hotel_margin_gst_type = $fetch_list_data["hotel_margin_gst_type"];
        $hotel_margin_gst_percentage = $fetch_list_data["hotel_margin_gst_percentage"];
        $hotel_hotspot_status = $fetch_list_data["hotel_hotspot_status"];

        $datas .= "{";
        $datas .= '"count": "' . $counter . '",';
        $datas .= '"hotel_name": "' . $hotel_name . '",';
        $datas .= '"hotel_code": "' . $hotel_code . '",';
        $datas .= '"hotel_state": "' . $hotel_state . '",';
        $datas .= '"hotel_city": "' . $hotel_city . '",';
        $datas .= '"hotel_mobile": "' . $hotel_mobile . '",';
        $datas .= '"hotel_mobile_formatted": "' . $hotel_mobile_formatted . '",';
        $datas .= '"hotel_status": "' . $status . '",';
        $datas .= '"hotel_margin": "' . $hotel_margin . '",';
        $datas .= '"hotel_margin_gst_type": "' . $hotel_margin_gst_type . '",';
        $datas .= '"hotel_margin_gst_percentage": "' . $hotel_margin_gst_percentage . '",';
        $datas .= '"hotel_hotspot_status": "' . $hotel_hotspot_status . '",';
        $datas .= '"hotel_category": "' . $hotel_category . '",';
        $datas .= '"modify": "' . $hotel_id . '"';
        $datas .= " },";

    endwhile; //end of while loop

    $data_formatted = substr(trim($datas), 0, -1);
    echo $data_formatted;
    echo "]}";
else :
    echo "Request Ignored !!!";
endif;
