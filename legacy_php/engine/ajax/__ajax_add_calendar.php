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

    if ($_GET['type'] == 'show_form') :

        $CALENDAR_ID = $_GET['CALENDAR_ID'];

        if ($CALENDAR_ID != '' && $CALENDAR_ID != 0) :

            $select_subject_details = sqlQUERY_LABEL("SELECT `room_ID`, `hotel_id`, `room_type_id`, `room_ref_code`, `total_max_adults`, `total_max_childrens`, `air_conditioner_availability`, `breakfast_included`, `lunch_included`, `dinner_included`, `check_in_time`, `check_out_time` FROM `dvi_hotel_rooms` WHERE`deleted` = '0' AND `status` = '1' AND `ROOMS_ID` = '$ROOMS_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                $ROOMS_ID = $fetch_data['room_ID'];
                $hotel_id = $fetch_data['hotel_id'];
                $room_type_id = $fetch_data['room_type_id'];
                $room_ref_code = $fetch_data['room_ref_code'];
                $total_max_adults = $fetch_data['total_max_adults'];
                $total_max_childrens = $fetch_data['total_max_childrens'];
                $air_conditioner_availability = $fetch_data['air_conditioner_availability'];
                $breakfast_included = $fetch_data['breakfast_included'];
                $lunch_included = $fetch_data['lunch_included'];
                $dinner_included = $fetch_data['dinner_included'];
                $check_in_time = $fetch_data['check_in_time'];
                $check_out_time = $fetch_data['check_out_time'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;

?>
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>