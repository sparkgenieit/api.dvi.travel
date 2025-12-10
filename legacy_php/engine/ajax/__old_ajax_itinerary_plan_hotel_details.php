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

    if ($_GET['type'] == 'select_hotels') :

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
            $select_itinerary_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_no_bed`, `guide_for_itinerary` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_query);
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itinerary_query)) :
                $arrival_location = $fetch_list_data['arrival_location'];
                $departure_location = $fetch_list_data['departure_location'];
                $trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
                $trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
                $expecting_budget = $fetch_list_data['expecting_budget'];
                $no_of_routes = $fetch_list_data['no_of_routes'];
                $no_of_days = $fetch_list_data["no_of_days"];
                $no_of_nights = $fetch_list_data['no_of_nights'];
                $total_adult = $fetch_list_data["total_adult"];
                $total_children = $fetch_list_data["total_children"];
                $total_infants = $fetch_list_data["total_infants"];
                $preferred_room_count = $fetch_list_data["preferred_room_count"];
                $total_extra_bed = $fetch_list_data["total_extra_bed"];
                $total_child_no_bed = $fetch_list_data["total_child_no_bed"];
                $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
            endwhile;

            //DELETE EXISTING HOTEL DETAILS
            $sqlWhere_hotel = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
            $delete_previous_plan_hotel_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_details", '', '', $sqlWhere_hotel);
            //DELETE EXISTING HOTEL ROOM DETAILS
            $sqlWhere_rooms = " `itinerary_plan_id` = '$itinerary_plan_ID' ";
            $delete_previous_plan_room_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $sqlWhere_rooms);

            //FETCH ROUTE DETAILS
            $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`,  `no_of_days`, `no_of_km`, `location_via_route`, `next_visiting_location` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);

            if ($total_itinerary_route_count > 0) :
                while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                    $itinerary_route_counter++;
                    $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
                    $location_name = $fetch_itinerary_route_data['location_name'];
                    $location_id = $fetch_itinerary_route_data['location_id'];

                    $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
                    $itinerary_route_year = date('Y', strtotime($itinerary_route_date));
                    $itinerary_route_monthFullName = date('F', strtotime($itinerary_route_date));
                    $itinerary_route_day = date('d', strtotime($itinerary_route_date));
                    $itinerary_route_day = ltrim($itinerary_route_day, '0');

                    $no_of_days = $fetch_itinerary_route_data['no_of_days'];
                    $no_of_km = $fetch_itinerary_route_data['no_of_km'];
                    $location_via_route = $fetch_itinerary_route_data['location_via_route'];


                    $next_visiting_location = $fetch_itinerary_route_data['next_visiting_location'];

                    $get_location_details = sqlQUERY_LABEL("SELECT `destination_location`,`location_ID`,`destination_location_lattitude`,`destination_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                    if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                        while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                            $next_visiting_location_longitude = $fetch_location_data['destination_location_longitude'];
                            $next_visiting_location_latitude = $fetch_location_data['destination_location_lattitude'];
                        endwhile;
                    endif;


                    if ($itinerary_route_counter == $total_itinerary_route_count) :

                        $hotel_required = 0; //NO HOTEL REQUIRED
                        $next_visiting_location = $location_name;

                        $arrFields_hotel = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location`', '`hotel_required`',  '`createdby`', '`status`');

                        $arrValues_hotel = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date", "$next_visiting_location", "$hotel_required",  "$logged_user_id", "1");

                        //INSERT ROUTE DETAILS
                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, '')) :
                        endif;

                    else :

                        $hotel_required = 1; //HOTEL REQUIRED

                        //"SELECT `hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`, `hotel_address`, `hotel_pincode`, `hotel_longitude`, `hotel_latitude`,  ST_Distance(POINT('$next_visiting_location_longitude', '$next_visiting_location_latitude'),POINT(`hotel_longitude`, `hotel_latitude`)) AS distance FROM `dvi_hotel` WHERE `deleted` = '0' and `status` = '1' ORDER BY distance "


                        $select_hotel_details = sqlQUERY_LABEL("SELECT `hotel_id`,`hotel_name`, `hotel_city`, `hotel_state`, `hotel_place`,`hotel_category`, `hotel_address`, `hotel_pincode`, `hotel_longitude`, `hotel_latitude`,  SQRT(POW(69.1 * (`hotel_latitude` - $next_visiting_location_latitude), 2) + POW(69.1 * ($next_visiting_location_longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) AS distance FROM `dvi_hotel` WHERE `deleted` = '0' and `status` = '1'  AND (`hotel_longitude` IS NOT NULL) AND (`hotel_latitude` IS NOT NULL) AND (SQRT(POW(69.1 * (`hotel_latitude` - $next_visiting_location_latitude), 2) + POW(69.1 * ($next_visiting_location_longitude - `hotel_longitude`) * COS(`hotel_latitude` / 57.3), 2)) <= 50) ORDER BY distance ASC LIMIT 1") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                        if (sqlNUMOFROW_LABEL($select_hotel_details) > 0) :

                            while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_hotel_details)) :
                                $hotel_id = $fetch_hotel_data['hotel_id'];
                                $hotel_name = $fetch_hotel_data['hotel_name'];
                                $hotel_place = $fetch_hotel_data['hotel_place'];
                                $hotel_category_id = $fetch_hotel_data['hotel_category'];
                                $hotel_category = getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label');

                                $arrFields_hotel = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location`', '`hotel_required`', '`hotel_category_id`', '`hotel_id`', '`total_no_of_rooms`', '`createdby`', '`status`');

                                $arrValues_hotel = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date", "$next_visiting_location", "$hotel_required", "$hotel_category_id", "$hotel_id", "$preferred_room_count", "$logged_user_id", "1");

                                //INSERT HOTEL DETAILS
                                if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, '')) :
                                    $itinerary_plan_hotel_details_id = sqlINSERTID_LABEL();

                                    //calculate room rate based on budget
                                    $cost_of_room = ($expecting_budget * (ITINERARY_BUDGET_HOTEL_PERCENTAGE / 100)) / $no_of_nights;

                                    $PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET = $cost_of_room / $preferred_room_count;

                                    //FETCH ROOM DETAILS OF THE SELECTED HOTEL BASED ON THE BUDGET 
                                    $gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,R.`extra_bed_charge`, RP.`DAY_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' AND RP.`DAY_$itinerary_route_day`<= '$PERDAY_EXPECTING_ROOM_RATE_BASES_ON_BUDGET' AND R.`hotel_id`='$hotel_id' and R.`deleted` ='0' ORDER BY RP.`DAY_$itinerary_route_day` DESC LIMIT 1") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

                                    $total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);

                                    if ($total_room_count > 0) :
                                        $total_room_rate = 0;
                                        while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
                                            $room_count++;
                                            $room_ID = $fetch_room_data['room_ID'];
                                            $room_title = $fetch_room_data['room_title'];
                                            $room_type_id = $fetch_room_data['room_type_id'];
                                            $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                            $gst_type = $fetch_room_data['gst_type'];
                                            $gst_percentage = $fetch_room_data['gst_percentage'];
                                            $FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
                                            $extra_bed_charge = $fetch_room_data['extra_bed_charge'];
                                            if ($gst_type == 1) :
                                                $roomRate_with_tax = $FIXED_ROOM_RATE;
                                                $extra_bed_charge_with_tax = $extra_bed_charge;
                                                $gst_amt = 0;
                                            elseif ($gst_type == 2) :
                                                $roomRate_with_tax = $FIXED_ROOM_RATE;
                                                $gst_amt = ($gst_percentage / 100) * $roomRate_with_tax;
                                                $roomRate_with_tax = $roomRate_with_tax + $gst_amt;
                                                $extra_bed_charge_with_tax = $extra_bed_charge + $gst_amt;
                                            endif;

                                            $total_room_rate = $total_room_rate + $roomRate_with_tax;

                                            $arrFields_room = array('`itinerary_plan_hotel_details_id`', '`itinerary_plan_id`', '`itinerary_route_id`', '`hotel_id`', '`room_type_id`', '`room_id`', '`room_rate`', '`gst_type`', '`gst_percentage`', '`gst_rate`', '`total_rate_of_room`', '`extra_bed_rate`', '`extra_bed_rate_with_tax`', '`createdby`', '`status`');

                                            $arrValues_room = array("$itinerary_plan_hotel_details_id", "$itinerary_plan_ID", "$itinerary_route_ID", "$hotel_id", "$room_type_id", "$room_ID", "$FIXED_ROOM_RATE", "$gst_type", "$gst_percentage", "$gst_amt", "$roomRate_with_tax", "$extra_bed_charge", "$extra_bed_charge_with_tax",  "$logged_user_id", "1");

                                            if (sqlACTIONS("INSERT", "dvi_itinerary_plan_hotel_room_details", $arrFields_room, $arrValues_room, '')) :
                                            endif;

                                        endwhile;
                                    endif;
                                endif;
                            endwhile;

                            //UPDATE TOTAL ROOM RATE IN HOTEL DETAILS TABLE
                            $arrFields_hotel_details = array('`total_room_rate`');
                            $arrValues_hotel_details = array("$total_room_rate");
                            $sqlWhere_hotel_details = " `itinerary_plan_hotel_details_ID` = '$itinerary_plan_hotel_details_id' ";
                            //UPDATE DETAILS
                            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel_details, $arrValues_hotel_details, $sqlWhere_hotel_details)) :
                            endif;

                        endif;
                    endif;

                endwhile;
            endif;
            $response['result'] = true;
        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'show_itinerary_plan_hotel_details') :

        $itinerary_plan_ID = $_POST['itinerary_plan_ID'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :

            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_no_bed`, `guide_for_itinerary` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $arrival_location = $fetch_list_data['arrival_location'];
                $departure_location = $fetch_list_data['departure_location'];
                $trip_start_date_and_time = $fetch_list_data['trip_start_date_and_time'];
                $trip_end_date_and_time = $fetch_list_data['trip_end_date_and_time'];
                $expecting_budget = $fetch_list_data['expecting_budget'];
                $no_of_routes = $fetch_list_data['no_of_routes'];
                $no_of_days = $fetch_list_data["no_of_days"];
                $no_of_nights = $fetch_list_data['no_of_nights'];
                //$arrival_latitude = $fetch_list_data["arrival_latitude"];
                // $arrival_longitude = $fetch_list_data["arrival_longitude"];
                // $departure_latitude = $fetch_list_data["departure_latitude"];
                // $departure_longitude = $fetch_list_data["departure_longitude"];
                $total_adult = $fetch_list_data["total_adult"];
                $total_children = $fetch_list_data["total_children"];
                $total_infants = $fetch_list_data["total_infants"];
                $preferred_room_count = $fetch_list_data["preferred_room_count"];
                $total_extra_bed = $fetch_list_data["total_extra_bed"];
                $total_child_no_bed = $fetch_list_data["total_child_no_bed"];
                $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
            endwhile;
?>


            <div id="hotel_list" class="card border border-primary">
                <div class="d-flex align-items-center justify-content-between  px-3 py-2 pt-3">
                    <h5 class="card-header p-0">Hotel Info List</h5>
                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm d-none" id="customize_back_hotel_btn" onclick="back_itinerary_hotel_customize()"> <i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back To Hotel List
                    </button>
                </div>

                <div class="d-flex justify-content-between">
                    <div class="d-flex p-3">
                        <span class="mb-0 me-4"><strong>Total Rooms</strong><span class="badge badge-center bg-primary bg-glow mx-2"><?= $preferred_room_count; ?></span></span>
                        <span class="mb-0 me-4"><strong>Total Extra Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2"><?= $total_extra_bed; ?></span></span>
                        <span class="mb-0 me-4"><strong>Child No Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2"><?= $total_child_no_bed; ?></span></span>
                    </div>
                    <div class="mb-0 me-3 p-3 pe-0"><strong>Total Amount For Hotel</strong><span class="badge bg-primary bg-glow ms-2">₹ <span id="total_amount_for_hotel">0</span></span></div>
                </div>

                <div id="hotel_preview_table_div">
                    <div class="">
                        <form id="form_hotel_list" action="" method="post">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="30%">Date & Location</th>
                                        <th width="55%">Hotel Details</th>
                                        <th width="15%">Total Rate (<?= $global_currency_format; ?>)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                    $select_itinerary_hotel_details = sqlQUERY_LABEL("SELECT H.`itinerary_plan_hotel_details_ID`,  H.`itinerary_plan_id`,  H.`itinerary_route_id`,  H.`itinerary_route_date`,  H.`itinerary_route_location`,  H.`hotel_required`,  H.`hotel_category_id`,  H.`hotel_id`,  H.`total_no_of_rooms`,  H.`total_room_rate`,R.`itinerary_plan_hotel_room_details_ID`, R.`room_type_id`, R.`room_id`, R.`room_rate`, R.`gst_type`, R.`gst_percentage`, R.`gst_rate`,R.`extra_bed_count`, R.`extra_bed_rate_with_tax`, R.`total_rate_of_room` FROM `dvi_itinerary_plan_hotel_details` H  LEFT JOIN `dvi_itinerary_plan_hotel_room_details` R ON H.`itinerary_plan_hotel_details_ID`= R.`itinerary_plan_hotel_details_id` WHERE  H.`deleted` = '0' and H.`status` = '1' and  H.`itinerary_plan_id` = '$itinerary_plan_ID' GROUP BY H.`itinerary_plan_hotel_details_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_hotel_details);
                                    if ($total_itinerary_route_count > 0) :
                                        while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_hotel_details)) :
                                            $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                                            $itinerary_plan_hotel_room_details_ID = $fetch_hotel_data['itinerary_plan_hotel_room_details_ID'];
                                            $itinerary_route_id = $fetch_hotel_data['itinerary_route_id'];
                                            $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];

                                            $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                                            $hotel_required = $fetch_hotel_data['hotel_required'];
                                            $hotel_category = getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'label');

                                            $hotel_id = $fetch_hotel_data['hotel_id'];
                                            $hotel_name = getHOTEL_DETAIL($hotel_id, '', 'label');
                                            $hotel_place = getHOTEL_PLACE($hotel_id, 'hotel_place');

                                            $total_room_rate = $fetch_hotel_data['total_room_rate'];

                                            $total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];

                                            $room_type_id = $fetch_hotel_data['room_type_id'];
                                            $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                            $room_id = $fetch_hotel_data['room_id'];
                                            $room_title = getROOM_DETAILS($room_id, 'room_title');;
                                            $room_rate = $fetch_hotel_data['room_rate'];
                                            $total_rate_of_room = $fetch_hotel_data['total_rate_of_room'];
                                            $extra_bed_rate_with_tax = $fetch_hotel_data['extra_bed_rate_with_tax'];
                                            $extra_bed_count = $fetch_hotel_data['extra_bed_count'];

                                            //FETCH ROUTE LOCATION LONGITUDE AND LATITUDE
                                            $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `location_id` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_id` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_id'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());

                                            while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                                                $location_id = $fetch_itinerary_route_data['location_id'];

                                                $get_location_details = sqlQUERY_LABEL("SELECT `destination_location`,`location_ID`,`destination_location_lattitude`,`destination_location_longitude` FROM `dvi_stored_locations` WHERE  `location_ID` ='$location_id' ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());

                                                if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                                                    while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                                                        $next_visiting_location_longitude = $fetch_location_data['destination_location_longitude'];
                                                        $next_visiting_location_latitude = $fetch_location_data['destination_location_lattitude'];
                                                    endwhile;
                                                endif;


                                            endwhile;

                                            //HOTEL LIST 


                                            if ($hotel_required == 1) :
                                    ?>
                                                <tr>
                                                    <input type="hidden" name="hidden_itinerary_plan_hotel_details_ID" value="<?= $itinerary_plan_hotel_details_ID ?>" />
                                                    <input type="hidden" name="hidden_route_date" value="<?= $itinerary_route_date ?>" />

                                                    <td>
                                                        <?= date('F d, Y', strtotime($itinerary_route_date)); ?>
                                                        <br>
                                                        <?= $itinerary_route_location; ?>
                                                        <hr />

                                                        <div class="hotel_label_<?= $itinerary_plan_hotel_details_ID ?>">
                                                            <span class="text-primary">Hotel Needed for Stay</span>
                                                            <span class="badge bg-label-primary me-1">
                                                                <?= get_YES_R_NO($hotel_required, 'label') ?>
                                                            </span>
                                                        </div>
                                                        <div class="d-none cls_hotel_required_<?= $itinerary_plan_hotel_details_ID ?> hotel_text_<?= $itinerary_plan_hotel_details_ID ?>">
                                                            <div class="mb-3">
                                                                <label class="text-sm-end" for="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Needed for Stay</label>
                                                                <select name="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-select form-select-sm mt-2" onchange="onchangeHOTELREQUIRED('<?= $itinerary_plan_hotel_details_ID ?>');">
                                                                    <?= get_YES_R_NO($hotel_required, 'select') ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="hotel_label_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_name_label_<?= $itinerary_plan_hotel_details_ID; ?>">
                                                            <h6 class="mt-2 mb-3">
                                                                <?= $hotel_name . ", " . $hotel_place . " (" . $hotel_category . ")"  ?>
                                                            </h6>

                                                            <?php
                                                            $select_itinerary_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `room_type_id`, `room_id`, `room_rate`, `gst_type`, `gst_percentage`, `gst_rate`,`extra_bed_count`, `extra_bed_rate_with_tax`, `total_rate_of_room` FROM  `dvi_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                            $total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
                                                            if ($total_itinerary_room_count > 0) :
                                                                $counter = 0;
                                                                $total_room_rate_daywise = 0;
                                                                while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
                                                                    $counter++;
                                                                    $itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
                                                                    $room_type_id = $fetch_room_data['room_type_id'];
                                                                    $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                                                    $room_id = $fetch_room_data['room_id'];
                                                                    $room_title = getROOM_DETAILS($room_id, 'room_title');;
                                                                    $room_rate = $fetch_room_data['room_rate'];
                                                                    $total_rate_of_room = $fetch_room_data['total_rate_of_room'];
                                                                    $extra_bed_rate_with_tax = $fetch_room_data['extra_bed_rate_with_tax'];
                                                                    $extra_bed_count = $fetch_room_data['extra_bed_count'];

                                                                    $total_room_rate_daywise += $total_rate_of_room;
                                                            ?>

                                                                    <hr class="my-2">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="text-primary mb-0">Room <?= $counter; ?></h6>

                                                                        <div>
                                                                            <span class="p-2"><?= $global_currency_format . ' ' . number_format($total_rate_of_room); ?></span>
                                                                        </div>

                                                                        <span id="" class="" hidden>
                                                                            <?= $total_rate_of_room; ?>
                                                                        </span>

                                                                    </div>
                                                                    <p class="my-2">
                                                                        <i class="ti ti-bed-filled text-primary me-1"></i><?= $room_type_title ?> (Extra Beds - <span class="text-grey fw-bold"><?= $extra_bed_count ?></span>)
                                                                    </p>
                                                            <?php endwhile;
                                                            endif; ?>
                                                        </div>

                                                        <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?> mb-3" id="hotel_category_edit_<?= $itinerary_plan_hotel_details_ID ?>">

                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <label class="text-sm-end" for="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Category</label>
                                                                    <select name="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control form-select  form-select-sm" onchange="onchangeHOTELCATEGORY('<?= $itinerary_plan_hotel_details_ID ?>','<?= $next_visiting_location_latitude ?>','<?= $next_visiting_location_longitude ?>');">
                                                                        <?= getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'select') ?>
                                                                    </select>
                                                                </div>
                                                                <div class="col-8">
                                                                    <label class="text-sm-end" for="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Name</label>
                                                                    <select name="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" style="width: 300px;" autocomplete="off" class="form-select  form-select-sm" onchange="onchangeHOTEL('<?= $itinerary_plan_hotel_details_ID ?>');">
                                                                        <?= getNEARESTHOTELS($next_visiting_location_latitude, $next_visiting_location_longitude, $hotel_id); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="hidden_room_id_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hidden_room_id_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" value="<?= $room_id ?>">

                                                        <?php
                                                        $select_itinerary_room_details = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `room_type_id`, `room_id`, `room_rate`, `gst_type`, `gst_percentage`, `gst_rate`,`extra_bed_count`, `extra_bed_rate_with_tax`, `total_rate_of_room` FROM  `dvi_itinerary_plan_hotel_room_details` WHERE `itinerary_plan_hotel_details_ID`='$itinerary_plan_hotel_details_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                        $total_itinerary_room_count = sqlNUMOFROW_LABEL($select_itinerary_room_details);
                                                        if ($total_itinerary_room_count > 0) :
                                                            $counter = 0;
                                                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_itinerary_room_details)) :
                                                                $counter++;
                                                                $itinerary_plan_hotel_room_details_ID = $fetch_room_data['itinerary_plan_hotel_room_details_ID'];
                                                                $room_type_id = $fetch_room_data['room_type_id'];
                                                                $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                                                $room_id = $fetch_room_data['room_id'];
                                                                $room_title = getROOM_DETAILS($room_id, 'room_title');;
                                                                $room_rate = $fetch_room_data['room_rate'];
                                                                $total_rate_of_room = $fetch_room_data['total_rate_of_room'];
                                                                $extra_bed_rate_with_tax = $fetch_room_data['extra_bed_rate_with_tax'];
                                                                $extra_bed_count = $fetch_room_data['extra_bed_count'];

                                                        ?>

                                                                <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?>">
                                                                    <hr class="my-2">
                                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                                        <h6 class="text-primary mb-0">Room <?= $counter; ?></h6>

                                                                        <div>
                                                                            <span id="room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class=" room_rate_<?= $itinerary_plan_hotel_details_ID ?> p-2"><?= $global_currency_format . ' ' . number_format($total_rate_of_room); ?></span>
                                                                        </div>

                                                                        <span id="" hidden>
                                                                            <?= $total_rate_of_room; ?>
                                                                        </span>

                                                                        <input type="hidden" name="hidden_room_rate_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hidden_room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" value="<?= $total_rate_of_room ?>">

                                                                    </div>
                                                                </div>

                                                                <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?> hotel_roomtype_edit_<?= $itinerary_plan_hotel_details_ID; ?> mb-2">
                                                                    <div class="row justify-content-between">
                                                                        <div class="col">
                                                                            <label class="text-sm-end mb-1" for="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>">Room Type</label>
                                                                            <select name="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" autocomplete="off" class="form-select  form-select-sm hotel_room_<?= $itinerary_plan_hotel_details_ID ?>" onchange="selectROOMDETAILS('<?= $itinerary_plan_hotel_details_ID ?>','<?= $counter ?>','<?= $itinerary_route_date ?>');">
                                                                                <?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, 'select') ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-auto">
                                                                            <label class="mb-1" for="hotel_extra_bed_<?= $itinerary_plan_hotel_details_ID ?>">Extra Beds</label>

                                                                            <div class="input-group input_group_plus_minus">

                                                                                <input type="button" value="-" class="input_minus_button button-minus" data-id="<?= $itinerary_plan_hotel_details_ID ?>" data-rowcount="<?= $counter ?>" data-itineraryhotelroomid="<?= $itinerary_plan_hotel_room_details_ID ?>" data-routedate="<?= $itinerary_route_date ?>">

                                                                                <input type="number" step="1" value="<?= $extra_bed_count ?>" name="extra_bed_count_<?= $itinerary_plan_hotel_details_ID ?>[]" class="input_plus_minus_<?= $itinerary_plan_hotel_details_ID ?> extrabed-field  quantity-field">

                                                                                <input type="button" value="+" class="input_plus_button button-plus" data-id="<?= $itinerary_plan_hotel_details_ID ?>" data-rowcount="<?= $counter ?>" data-itineraryhotelroomid="<?= $itinerary_plan_hotel_room_details_ID ?>" data-routedate="<?= $itinerary_route_date ?>">

                                                                                <input type="hidden" name="extra_bed_rate_with_tax_<?= $itinerary_plan_hotel_details_ID ?>[]" value="<?= $extra_bed_rate_with_tax; ?>" hidden>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <input type="hidden" name="hidden_itinerary_plan_hotel_room_details_ID_<?= $itinerary_plan_hotel_details_ID ?>[]" value="<?= $itinerary_plan_hotel_room_details_ID ?>">


                                                                <div class="d-none hotel_text_<?= $itinerary_plan_hotel_details_ID ?> mb-1 row">
                                                                    <input type="hidden" name="extra_bed_rate_with_tax_<?= $itinerary_plan_hotel_details_ID ?>[]" value="<?= $extra_bed_rate_with_tax; ?>" hidden />
                                                                </div>
                                                        <?php endwhile;
                                                        endif; ?>
                                                    </td>

                                                    <td>
                                                        <div id="total_room_rate_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" class="fw-bolder total_room_rate_<?= $itinerary_plan_hotel_details_ID ?> cls_room_rate">
                                                            <?php echo $global_currency_format . ' ' . number_format($total_room_rate_daywise);
                                                            ?>
                                                        </div>

                                                    </td>

                                                    <td>
                                                        <button type="button" class="btn btn-icon btn-label-primary waves-effect hotel_edit_btn_<?= $itinerary_plan_hotel_details_ID ?>" onclick="editITINERARYHOTELBYROW('<?= $itinerary_plan_hotel_details_ID; ?>')">
                                                            <span class="ti ti-edit"></span>
                                                        </button>

                                                        <button type="submit" class="d-none btn btn-primary waves-effect waves-light hotel_update_btn_<?= $itinerary_plan_hotel_details_ID ?>">
                                                            <span class="ti-xs ti ti-check me-1"></span>Update
                                                        </button>
                                                    </td>
                                                </tr>

                                            <?php else : ?>

                                                <tr class="table-danger">
                                                    <td>
                                                        <?= date('F d, Y', strtotime($itinerary_route_date)); ?><br />
                                                        <?= $itinerary_route_location; ?>
                                                        <hr />
                                                        <div>
                                                            <span class="text-danger">Hotel Needed for
                                                                Stay</span>
                                                            <span class="badge bg-label-danger me-1"><?= $hotel_required == 1 ? "Yes" : "No" ?></span>
                                                        </div>
                                                    </td>

                                                    <td></td>
                                                    <td></td>
                                                    <td></td>

                                                </tr>
                                        <?php endif;
                                            $prev_itinerary_route_date = $itinerary_route_date;
                                        endwhile;
                                    else : ?>
                                        <tr>
                                            <td colspan="7">No records found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>

        <?php
        endif; ?>

        <script>
            $(document).ready(function() {

                $(".form-select").selectize();

                //CALCULATING TOTAL AMOUNT FOR THE HOTEL
                let totalRoomRate = 0;
                $('.cls_room_rate').each(function() {
                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                    const rate = parseFloat(rateText);
                    if (!isNaN(rate)) {

                        totalRoomRate += rate;
                    }
                });
                $("#total_amount_for_hotel").html(totalRoomRate);

                $(document).on('click', '.input_plus_button', function(e) {
                    var total_no_of_extrabeds = 0;
                    var HOTEL_DETAILS_ID = $(this).data('id');
                    var HOTEL_ROOM_DETAILS_ID = $(this).data('itineraryhotelroomid');
                    var ROW_NO = $(this).data('rowcount');
                    var ROUTE_DATE = $(this).data('routedate');
                    var TYPE = "ADD";

                    $('.input_plus_minus_' + HOTEL_DETAILS_ID).each(function() {
                        no_of_extrabeds = parseInt($(this).val());
                        total_no_of_extrabeds += no_of_extrabeds;
                    });

                    var extrabedField = $(this).siblings('.extrabed-field');
                    var currentValue = parseInt(extrabedField.val());

                    if (total_no_of_extrabeds < <?= $total_extra_bed ?>) {
                        extrabedField.val(currentValue + 1);
                        calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE);
                    } else {
                        TOAST_NOTIFICATION('error', 'Total extra bed count exceeded', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }

                });

                $('.input_minus_button').click(function(e) {

                    var HOTEL_DETAILS_ID = $(this).data('id');
                    var HOTEL_ROOM_DETAILS_ID = $(this).data('itineraryhotelroomid');
                    var ROW_NO = $(this).data('rowcount');
                    var ROUTE_DATE = $(this).data('routedate');
                    var TYPE = "SUB";

                    var extrabedField = $(this).siblings('.extrabed-field');
                    var currentValue = parseInt(extrabedField.val());

                    if (currentValue > 0) {
                        extrabedField.val(currentValue - 1);
                        calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE);
                    }
                });

                //AJAX FORM SUBMIT
                $('button[type="submit"]').click(function(event) {
                    event.preventDefault(); // Prevent default form submission

                    // Get the parent <tr> of the clicked button
                    var $row = $(this).closest('tr');

                    // Find and extract the necessary details from the row
                    var itinerary_plan_hotel_details_ID = $row.find('input[name="hidden_itinerary_plan_hotel_details_ID"]').val();
                    var route_date = $row.find('input[name="hidden_route_date"]').val();

                    // Append hotel_required, hotel_category_id, and hotel_id
                    var hotel_required = $('select[name="hotel_required_' + itinerary_plan_hotel_details_ID + '"]').val();
                    var hotel_category_id = $('select[name="hotel_category_' + itinerary_plan_hotel_details_ID + '"]').val();
                    var hotel_id = $('select[name="hotel_name_' + itinerary_plan_hotel_details_ID + '"]').val();

                    // Create FormData object and append the details
                    var formData = new FormData();
                    formData.append('hidden_itinerary_plan_hotel_details_ID', itinerary_plan_hotel_details_ID);
                    formData.append('hidden_route_date', route_date);
                    formData.append('hotel_required', hotel_required);
                    formData.append('hotel_category_id', hotel_category_id);
                    formData.append('hotel_id', hotel_id);

                    // Iterate over the arrays and append each value
                    var hotel_roomtype_ids = $('select[name="hotel_roomtype_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hotel_roomtype_ids.each(function(index, element) {
                        formData.append('hotel_roomtype_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    var hidden_room_ids = $('input[name="hidden_room_id_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hidden_room_ids.each(function(index, element) {
                        formData.append('hidden_room_id_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    var hidden_room_rates = $('input[name="hidden_room_rate_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hidden_room_rates.each(function(index, element) {
                        formData.append('hidden_room_rate_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    var hidden_itinerary_plan_hotel_room_details_IDS = $('input[name="hidden_itinerary_plan_hotel_room_details_ID_' + itinerary_plan_hotel_details_ID + '[]"]');
                    hidden_itinerary_plan_hotel_room_details_IDS.each(function(index, element) {
                        formData.append('hidden_itinerary_plan_hotel_room_details_ID_' + itinerary_plan_hotel_details_ID + '[]', $(element).val());
                    });

                    // Perform AJAX submission
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=update_itinerary_plan_hotel_details',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            // Handle errors if necessary
                        } else {
                            // Handle success response
                            if (response.u_result == true) {
                                TOAST_NOTIFICATION('success', 'Itinerary Hotel Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                showHOTELLIST();
                            } else if (response.u_result == false) {
                                TOAST_NOTIFICATION('error', 'Unable to Update Itinerary Hotel Details', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                });


            });

            function calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE) {

                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;
                var HOTEL_ID = $("#hotel_name_" + HOTEL_DETAILS_ID).val();

                var hotelroom_selectize = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO)[0].selectize;
                var ROOM_TYPE_ID = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val();

                if (ROOM_TYPE_ID) {

                    var DAYS_COUNT = '<?= $no_of_nights ?>';
                    var ITINERARY_BUDGET = '<?= $expecting_budget ?>';
                    var ROOM_COUNT = '<?= $preferred_room_count ?>';

                    $.ajax({
                        url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=extra_bed_cost',
                        type: "POST",
                        data: {
                            HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                            HOTEL_ID: HOTEL_ID,
                            DAYS_COUNT: DAYS_COUNT,
                            ITINERARY_BUDGET: ITINERARY_BUDGET,
                            ROOM_COUNT: ROOM_COUNT,
                            ROUTE_DATE: ROUTE_DATE,
                            ROOM_TYPE_ID: ROOM_TYPE_ID,
                            TYPE: TYPE,
                            HOTEL_ROOM_DETAILS_ID: HOTEL_ROOM_DETAILS_ID
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.result == true) {
                                $("#hidden_room_id_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_ID);
                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + response.room_rate.toLocaleString());
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                let totalRoomRate = 0;

                                $('.room_rate_' + HOTEL_DETAILS_ID).each(function() {
                                    const roomrateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const roomrate = parseFloat(roomrateText);
                                    if (!isNaN(roomrate)) {
                                        totalRoomRate += roomrate;
                                    }
                                });
                                $("#total_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + totalRoomRate.toLocaleString());

                                let total_amount_for_hotel = 0;

                                $('.cls_room_rate').each(function() {
                                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const rate = parseFloat(rateText);
                                    if (!isNaN(rate)) {
                                        total_amount_for_hotel += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(total_amount_for_hotel);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to update Cost', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                }

            }

            function onchangeHOTELREQUIRED(HOTEL_DETAILS_ID) {
                var hotelrequired_selectize = $("#hotel_required_" + HOTEL_DETAILS_ID)[0].selectize;
                var hotel_required = $("#hotel_required_" + HOTEL_DETAILS_ID).val();
                if (hotel_required == 0) {
                    $('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

                    $('.hotel_text_' + HOTEL_DETAILS_ID).addClass('d-none');
                    //$('.hotel_update_btn_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $(".total_room_rate_" + HOTEL_DETAILS_ID).addClass('d-none');
                    $(".cls_hotel_required_" + HOTEL_DETAILS_ID).removeClass('d-none');
                } else if (hotel_required == 1) {
                    $('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

                    $('.hotel_text_' + HOTEL_DETAILS_ID).removeClass('d-none');
                    //$('.hotel_update_btn_' + HOTEL_DETAILS_ID).addClass('d-none');
                    $(".total_room_rate_" + HOTEL_DETAILS_ID).removeClass('d-none');
                    // $(".cls_hotel_required_" + HOTEL_DETAILS_ID).addClass('d-none');
                }
            }

            function onchangeHOTELCATEGORY(HOTEL_DETAILS_ID, LOCATION_LATITUDE, LOCATION_LONGITUDE) {
                var hotel_category_selectize = $("#hotel_category_" + HOTEL_DETAILS_ID)[0].selectize;
                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;

                var hotel_category_id = $("#hotel_category_" + HOTEL_DETAILS_ID).val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_name',
                    type: "POST",
                    data: {
                        HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                        LOCATION_LATITUDE: LOCATION_LATITUDE,
                        LOCATION_LONGITUDE: LOCATION_LONGITUDE,
                        hotel_category_id: hotel_category_id
                    },
                    success: function(response) {
                        // Append the response to the dropdown.
                        hotelname_selectize.clear();
                        hotelname_selectize.clearOptions();
                        hotelname_selectize.addOption(response);

                        $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                        $("#total_amount_for_hotel").html(" 0");

                    }
                });
            }

            function onchangeHOTEL(HOTEL_DETAILS_ID) {

                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;
                var hotel_id = $("#hotel_name_" + HOTEL_DETAILS_ID).val();
                const room_count = <?= $preferred_room_count ?>;

                for (i = 1; i <= room_count; i++) {
                    (function(index) {
                        var hotelroom_selectize = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + index)[0].selectize;

                        $.ajax({
                            url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_room',
                            type: "POST",
                            data: {
                                hotel_id: hotel_id
                            },
                            success: function(response) {
                                // Append the response to the dropdown.
                                hotelroom_selectize.clear();
                                hotelroom_selectize.clearOptions();
                                hotelroom_selectize.addOption(response);
                            }
                        });
                    })(i);
                }
                $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                $("#total_amount_for_hotel").html(" 0");
            }

            function selectROOMDETAILS(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE) {

                var hotelname_selectize = $("#hotel_name_" + HOTEL_DETAILS_ID)[0].selectize;
                var HOTEL_ID = $("#hotel_name_" + HOTEL_DETAILS_ID).val();

                var hotelroom_selectize = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO)[0].selectize;
                var ROOM_TYPE_ID = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val();

                if (ROOM_TYPE_ID) {

                    var DAYS_COUNT = '<?= $no_of_nights ?>';
                    var ITINERARY_BUDGET = '<?= $expecting_budget ?>';
                    var ROOM_COUNT = '<?= $preferred_room_count ?>';
                    //var ROUTE_DATE = '<?= $itinerary_route_date ?>';

                    $.ajax({
                        url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=check_room_availability',
                        type: "POST",
                        data: {
                            HOTEL_DETAILS_ID: HOTEL_DETAILS_ID,
                            HOTEL_ID: HOTEL_ID,
                            DAYS_COUNT: DAYS_COUNT,
                            ITINERARY_BUDGET: ITINERARY_BUDGET,
                            ROOM_COUNT: ROOM_COUNT,
                            ROUTE_DATE: ROUTE_DATE,
                            ROOM_TYPE_ID: ROOM_TYPE_ID
                        },
                        dataType: 'json',
                        success: function(response) {

                            if (response.result == true) {
                                $("#hidden_room_id_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_ID);
                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + response.room_rate.toLocaleString());
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                let totalRoomRate = 0;

                                $('.room_rate_' + HOTEL_DETAILS_ID).each(function() {
                                    const roomrateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const roomrate = parseFloat(roomrateText);
                                    if (!isNaN(roomrate)) {
                                        totalRoomRate += roomrate;
                                    }
                                });
                                $("#total_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html("&#x20b9; " + totalRoomRate.toLocaleString());

                                let total_amount_for_hotel = 0;

                                $('.cls_room_rate').each(function() {
                                    const rateText = $(this).text().replace(/[^\d.]/g, ''); // Remove non-numeric characters
                                    const rate = parseFloat(rateText);
                                    if (!isNaN(rate)) {
                                        total_amount_for_hotel += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(total_amount_for_hotel);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'No Rooms Available', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        }
                    });
                }
            }

            function editITINERARYHOTELBYROW(HOTEL_DETAILS_ID) {
                $('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
                $('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

                $('.hotel_text_' + HOTEL_DETAILS_ID).removeClass('d-none');
                $('.hotel_update_btn_' + HOTEL_DETAILS_ID).removeClass('d-none');
            }
        </script>

<?php
    elseif ($_GET['type'] == 'update_itinerary_plan_hotel_details') :

        $errors = [];
        $response = [];

        $hidden_itinerary_plan_hotel_details_ID = trim($_POST['hidden_itinerary_plan_hotel_details_ID']);
        $hidden_route_date = $_POST['hidden_route_date'];
        $itinerary_route_year = date('Y', strtotime($hidden_route_date));
        $itinerary_route_monthFullName = date('F', strtotime($hidden_route_date));
        $itinerary_route_day = date('d', strtotime($hidden_route_date));

        $hotel_required = trim($_POST["hotel_required"]);
        $hotel_category_id = trim($_POST["hotel_category_id"]);
        $hotel_id  = trim($_POST["hotel_id"]);
        //Array
        $hotel_roomtype_id  = $_POST["hotel_roomtype_" . $hidden_itinerary_plan_hotel_details_ID];
        $hidden_room_id  = $_POST["hidden_room_id_" . $hidden_itinerary_plan_hotel_details_ID];
        $hidden_room_rate  = $_POST["hidden_room_rate_" . $hidden_itinerary_plan_hotel_details_ID];
        $hidden_itinerary_plan_hotel_room_details_IDS  = $_POST["hidden_itinerary_plan_hotel_room_details_ID_" . $hidden_itinerary_plan_hotel_details_ID];

        if ($hotel_required == 0) :

            //$arrFields_hotel = array('`hotel_required`', '`hotel_category_id`', '`hotel_id`', '`total_no_of_rooms`', '`total_room_rate`');
            //$arrValues_hotel = array("$hotel_required", "0", "0", "0", "0");

            $arrFields_hotel = array('`hotel_required`');
            $arrValues_hotel = array("$hotel_required");
            $sqlWhere_hotel = " `itinerary_plan_hotel_details_ID` = '$hidden_itinerary_plan_hotel_details_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, $sqlWhere_hotel)) :
                //DELETE EXISTING ROOM DETAILS
                //$sqlWhere_rooms = " `itinerary_plan_id` = '$hidden_itinerary_plan_hotel_details_ID' ";
                //$delete_previous_plan_room_details = sqlACTIONS("DELETE", "dvi_itinerary_plan_hotel_room_details", '', '', $sqlWhere_rooms);

                $response['success'] = true;
                $response['u_result'] = true;
                $response['result_success'] = true;
            else :
                $response['success'] = false;
                $response['u_result'] = false;
                $response['result_success'] = false;
            endif;

        elseif ($hotel_required == 1) :

            //UPDATE HOTEL DETAILS

            $arrFields_hotel = array('`hotel_required`', '`hotel_category_id`', '`hotel_id`');
            $arrValues_hotel = array("$hotel_required", "$hotel_category_id", "$hotel_id");
            $sqlWhere_hotel = " `itinerary_plan_hotel_details_ID` = '$hidden_itinerary_plan_hotel_details_ID' ";

            if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel, $arrValues_hotel, $sqlWhere_hotel)) :

                if (count($hidden_itinerary_plan_hotel_room_details_IDS) > 0) :
                    $total_room_rate = 0;
                    for ($i = 0; $i < count($hidden_itinerary_plan_hotel_room_details_IDS); $i++) :

                        $gethotel_room_details = sqlQUERY_LABEL("SELECT R.`room_ID`, R.`room_title`, R.`room_type_id`, R.`gst_type`, R.`gst_percentage`,RP.`DAY_$itinerary_route_day` AS ROOM_RATE FROM `dvi_hotel_rooms` R LEFT JOIN `dvi_hotel_room_price_book` RP ON  R.`room_ID` = RP.`room_id`  where RP.`month` ='$itinerary_route_monthFullName' AND RP.`year` = '$itinerary_route_year' and R.`room_ID`='$hidden_room_id[$i]' AND R.`room_type_id`='$hotel_roomtype_id[$i]' AND R.`hotel_id`='$hotel_id' and R.`deleted` ='0'") or die("#getROOMTYPE_DETAILS: UNABLE_TO_GET_ROMM_TYPE_DETAILS: " . sqlERROR_LABEL());

                        $total_room_count = sqlNUMOFROW_LABEL($gethotel_room_details);
                        if ($total_room_count > 0) :
                            while ($fetch_room_data = sqlFETCHARRAY_LABEL($gethotel_room_details)) :
                                $room_count++;
                                $room_ID = $hidden_room_id[$i];
                                $room_title = $fetch_room_data['room_title'];
                                $room_type_id = $hotel_roomtype_id[$i];
                                $room_type_title = getROOM_DETAILS($room_type_id, 'ROOM_TYPE_TITLE');
                                $gst_type = $fetch_room_data['gst_type'];
                                $gst_percentage = $fetch_room_data['gst_percentage'];
                                $FIXED_ROOM_RATE = $fetch_room_data['ROOM_RATE'];
                                if ($gst_type == 1) :
                                    $gst_amt = 0;
                                elseif ($gst_type == 2) :
                                    $gst_amt = ($gst_percentage / 100) * $FIXED_ROOM_RATE;
                                endif;

                                $roomRate_with_tax = $hidden_room_rate[$i];
                                $total_room_rate = $total_room_rate + $roomRate_with_tax;

                                $arrFields_room = array('`room_type_id`', '`room_id`', '`room_rate`', '`gst_type`', '`gst_percentage`', '`gst_rate`', '`total_rate_of_room`');

                                $arrValues_room = array("$room_type_id", "$room_ID", "$FIXED_ROOM_RATE", "$gst_type", "$gst_percentage", "$gst_amt", "$roomRate_with_tax");

                                $sqlWhere_room = " `itinerary_plan_hotel_room_details_ID` = '$hidden_itinerary_plan_hotel_room_details_IDS[$i]' ";

                                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_room_details", $arrFields_room, $arrValues_room, $sqlWhere_room)) :
                                endif;

                            endwhile;
                        endif;
                    endfor;
                endif;

                //UPDATE TOTAL ROOM RATE IN HOTEL DETAILS TABLE
                $arrFields_hotel_details = array('`total_room_rate`');
                $arrValues_hotel_details = array("$total_room_rate");
                $sqlWhere_hotel_details = " `itinerary_plan_hotel_details_ID` = '$hidden_itinerary_plan_hotel_details_ID' ";
                //UPDATE DETAILS
                if (sqlACTIONS("UPDATE", "dvi_itinerary_plan_hotel_details", $arrFields_hotel_details, $arrValues_hotel_details, $sqlWhere_hotel_details)) :
                endif;

                $response['success'] = true;
                $response['u_result'] = true;
                $response['result_success'] = true;
            else :
                $response['success'] = false;
                $response['u_result'] = false;
                $response['result_success'] = false;
            endif;
        //endif;
        endif;
        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
