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

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary`, `itinerary_preference`, `departure_type` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
                /*$arrival_latitude = $fetch_list_data["arrival_latitude"];
$arrival_longitude = $fetch_list_data["arrival_longitude"];
$departure_latitude = $fetch_list_data["departure_latitude"];
$departure_longitude = $fetch_list_data["departure_longitude"];*/
                $total_adult = $fetch_list_data["total_adult"];
                $total_children = $fetch_list_data["total_children"];
                $total_infants = $fetch_list_data["total_infants"];
                $preferred_room_count = $fetch_list_data["preferred_room_count"];
                $total_extra_bed = $fetch_list_data["total_extra_bed"];
                $check_guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
                $itinerary_preference = $fetch_list_data["itinerary_preference"];
                $departure_type = $fetch_list_data["departure_type"];

                $total_pax_count = $total_adult + $total_children + $total_infants;

                if ($departure_type == '1') :
                    $global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_flight_buffer_time');
                elseif ($departure_type == '2') :
                    $global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_train_buffer_time');
                elseif ($departure_type == '3') :
                    $global_setting_end_buffer_time = getGLOBALSETTING('itinerary_travel_by_road_buffer_time');
                endif;
            endwhile;
        endif;

        $select_itinerary_route_details_update_time = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `route_start_time`, `route_end_time` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `itinerary_route_ID` ASC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
        $total_itinerary_route_count_update_time = sqlNUMOFROW_LABEL($select_itinerary_route_details_update_time);
        if ($total_itinerary_route_count_update_time > 0) :
            while ($fetch_itinerary_route_data_update_time = sqlFETCHARRAY_LABEL($select_itinerary_route_details_update_time)) :
                $itinerary_route_details_counter++;
                $itinerary_route_ID = $fetch_itinerary_route_data_update_time['itinerary_route_ID'];
                $route_start_time = $fetch_itinerary_route_data_update_time['route_start_time'];
                $route_end_time = $fetch_itinerary_route_data_update_time['route_end_time'];

                if ($route_start_time == '' && $route_end_time == '') :
                    $route_start_time = '';
                    $route_end_time = '';

                    if ($itinerary_route_details_counter == 1) :
                        $route_start_time = date('H:i:s', strtotime($trip_start_date_and_time));
                        $route_end_time = date('H:i:s', strtotime($first_day_end_time));

                        if (strtotime($route_start_time) > strtotime($route_end_time)) :
                            $route_end_time = date('H:i:s', strtotime($route_start_time) + 3600);
                        endif;

                        $arrFields = array('`route_start_time`', '`route_end_time`');
                        $arrValues = array("$route_start_time", "$route_end_time");
                    elseif ($itinerary_route_details_counter > 1 && $itinerary_route_details_counter < $total_itinerary_route_count_update_time) :
                        $route_start_time = date('H:i:s', strtotime($start_time_except_first_and_last_day));
                        $route_end_time = date('H:i:s', strtotime($end_time_except_first_and_last_day));

                        $arrFields = array('`route_start_time`', '`route_end_time`');
                        $arrValues = array("$route_start_time", "$route_end_time");
                    elseif ($itinerary_route_details_counter == $total_itinerary_route_count_update_time) :
                        $route_start_time = date('H:i:s', strtotime($last_day_start_time));
                        $route_end_time = date('H:i:s', strtotime($trip_end_date_and_time));

                        $global_setting_route_end_time = date('H:i:s', strtotime($trip_end_date_and_time . ' -' . date('g', strtotime($global_setting_end_buffer_time)) . ' hour' . date('i', strtotime($global_setting_end_buffer_time)) . 'min'));

                        if (strtotime($route_start_time) >= strtotime($global_setting_route_end_time)) :
                            $route_start_time = date('H:i:s', strtotime($global_setting_route_end_time) - 3600);
                        elseif (strtotime($route_start_time) >= strtotime($route_end_time)) :
                            $route_start_time = date('H:i:s', strtotime($route_end_time) - 3600);
                        endif;

                        $arrFields = array('`route_start_time`', '`route_end_time`');
                        $arrValues = array("$route_start_time", "$route_end_time");
                    endif;

                    $sqlWhere = " `itinerary_route_ID` = '$itinerary_route_ID' AND `itinerary_plan_ID` = '$itinerary_plan_ID' ";

                    if (sqlACTIONS("UPDATE", "dvi_itinerary_route_details", $arrFields, $arrValues, $sqlWhere)) :
                    endif;

                endif;

            endwhile;
        endif;

        if ($check_guide_for_itinerary == 1) :
            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `guide_type`, `guide_language` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
            while ($fetch_itinerary_route_data_update_time = sqlFETCHARRAY_LABEL($select_itinerary_route_details_update_time)) :
                $guide_type = $fetch_itinerary_route_data_update_time['guide_type'];
                $guide_language = $fetch_itinerary_route_data_update_time['guide_language'];
            endwhile;
            $total_guide_charges = get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, '', '', $guide_type, $guide_language, '', $total_pax_count, 'check_eligible_guide');
        else :
            $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT ROUTE_DETAILS.`itinerary_route_date`, ROUTE_GUIDE.`itinerary_route_ID`, ROUTE_GUIDE.`guide_type`, ROUTE_GUIDE.`guide_language`, ROUTE_GUIDE.`guide_slot` FROM `dvi_itinerary_route_guide_details` ROUTE_GUIDE LEFT JOIN `dvi_itinerary_route_details` ROUTE_DETAILS ON ROUTE_DETAILS.`itinerary_plan_ID` = ROUTE_GUIDE.`itinerary_plan_ID` WHERE ROUTE_GUIDE.`deleted` = '0' and ROUTE_GUIDE.`status` = '1' and ROUTE_GUIDE.`itinerary_plan_ID` = '$itinerary_plan_ID' AND ROUTE_GUIDE.`guide_type`='2' AND ROUTE_DETAILS.`itinerary_route_ID` = ROUTE_GUIDE.`itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
            while ($fetch_itinerary_route_data_update_time = sqlFETCHARRAY_LABEL($select_itinerary_route_details_update_time)) :
                $itinerary_route_ID = $fetch_itinerary_route_data_update_time['itinerary_route_ID'];
                $guide_type = $fetch_itinerary_route_data_update_time['guide_type'];
                $guide_type = $fetch_itinerary_route_data_update_time['guide_type'];
                $guide_language = $fetch_itinerary_route_data_update_time['guide_language'];
                $guide_slot = $fetch_itinerary_route_data_update_time['guide_slot'];
                $itinerary_route_date = $fetch_itinerary_route_data_update_time['itinerary_route_date'];
            endwhile;
            $total_guide_charges += get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $itinerary_route_date, $guide_type, $guide_language, $guide_slot, $total_pax_count, 'check_eligible_guide');
        endif;
?>
        <div id="se-pre-con"></div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Tour Itinerary Plan</b></h5>
                    <a href="?route=add&formtype=itinerary_routes&id=<?= $itinerary_plan_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back
                        to Route List</a>
                </div>
            </div>
            <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                <div>
                    <h5 class="text-capitalize"> Itinerary for
                        <b><?= date('F d, Y', strtotime($trip_start_date_and_time)); ?></b> to
                        <b><?= date('F d, Y', strtotime($trip_end_date_and_time)); ?></b> (<b><?= $no_of_nights; ?></b> Nights,
                        <b><?= $no_of_days; ?></b> Days)
                    </h5>
                    <h3 class="text-capitalize"><?= $arrival_location; ?> <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i> <?= $departure_location; ?></h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="mb-0 me-4"><strong>Adults</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_adult; ?></span></span>
                            <span class="mb-0 me-4"><strong>Children</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_children; ?></span></span>
                            <span class="mb-0 me-4"><strong>Infants</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_infants; ?></span></span>
                        </div>
                        <h5 class="mb-0"><strong>Budget</strong></span><span class="badge bg-primary bg-glow ms-2"><?= $global_currency_format . ' ' . number_format($expecting_budget, 0); ?></span>
                        </h5>
                    </div>
                </div>
            </div>

            <div class="nav-align-top my-2 p-0">
                <ul class="nav nav-pills" role="tablist">
                    <?php for ($nav_route_count = 1; $nav_route_count <= $no_of_routes; $nav_route_count++) : ?>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary<?= $nav_route_count; ?>" aria-controls="navs-top-itinerary<?= $nav_route_count; ?>" aria-selected="true">Route Itinerary
                                <?= $nav_route_count; ?></button>
                        </li>
                    <?php endfor; ?>
                </ul>
                <div class="tab-content p-0 mt-3">
                    <?php for ($tab_content_route_count = 1; $tab_content_route_count <= $no_of_routes; $tab_content_route_count++) :
                        $calculating_budget = '0';
                    ?>
                        <div class="tab-pane fade active show" id="navs-top-itinerary<?= $tab_content_route_count; ?>" role="tabpanel">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary">
                                            <div class=" d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                                <div>
                                                    <h5 class="card-title mb-sm-0 me-2 text-primary">Route Itinerary
                                                        <?= $tab_content_route_count; ?></h5>
                                                    <?php
                                                    $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                    $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                    if ($check_guide_for_itinerary == 1 && $total_itinerary_guide_route_count == 0) : ?>
                                                        <div>
                                                            <a href="javascript:void(0)" class="text-decoration-underline" onclick="showaddGUIDEADDFORMMODAL('<?= $nav_route_count; ?>','','1','<?= $itinerary_plan_ID; ?>','','');">
                                                                <span class="text-primary"> + Add Guide</span>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php
                                                    if ($check_guide_for_itinerary == 1 && $total_itinerary_guide_route_count > 0) :
                                                        while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                            $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                            $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                                                            $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                                                            $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                            $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                            $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                        endwhile;
                                                    ?>
                                                        <div>
                                                            <span id="edit_guide_modal" class="" style="color: #4d287b;">
                                                                Itinerary Guide Language - <span class="text-primary" id="language_choosen_itinerary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?></span>
                                                                <a href="javascript:void(0)" onclick="showaddGUIDEADDFORMMODAL('<?= $nav_route_count; ?>','<?= $route_guide_ID; ?>','1','<?= $itinerary_plan_ID; ?>','','');" class="edit_guide_modal_link" style="color: #4d287b;">
                                                                    <span class="ti-sm ti ti-edit mb-1"></span>
                                                                </a>
                                                            </span>
                                                            <p>
                                                                <b><?= general_currency_symbol . ' ' . number_format(get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, '', '', $guide_type, $guide_language, '', $total_pax_count, 'check_eligible_guide'), 2); ?></b>
                                                            </p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <h4 class="card-title mb-sm-0 me-2 text-primary">Overall Trip Cost <b class="text-primary"><?= $global_currency_format . ' '; ?><span id="overall_trip_cost"><?= getOVERLALLTRIPCOST($itinerary_plan_ID); ?></span></b>
                                                </h4>
                                                <input type="hidden" id="hotspot_amount" name="hotspot_amount" />
                                                <div class="action-btns">
                                                    <button class="btn btn-label-github me-3" id="scrollToTopButton">
                                                        <span class="align-middle"> Back To Top</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-body mt-3">
                                            <!-- Menu Accordion -->
                                            <div class="accordion" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar" style="--bs-accordion-bg: #f8f7fa;">
                                                <?php
                                                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `location_via_route`, `route_start_time`, `route_end_time`,`next_visiting_location`  FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                                                if ($total_itinerary_route_count > 0) :
                                                    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                                                        $itinerary_route_counter++;
                                                        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
                                                        $itinerary_plan_ID = $fetch_itinerary_route_data['itinerary_plan_ID'];
                                                        $location_name = $fetch_itinerary_route_data['location_name'];
                                                        $next_visiting_location = $fetch_itinerary_route_data['next_visiting_location'];
                                                        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
                                                        $location_latitude = $fetch_itinerary_route_data['location_latitude'];
                                                        $location_longtitude = $fetch_itinerary_route_data['location_longtitude'];
                                                        $no_of_days = $fetch_itinerary_route_data['no_of_days'];
                                                        $no_of_km = $fetch_itinerary_route_data['no_of_km'];
                                                        $location_via_route = $fetch_itinerary_route_data['location_via_route'];
                                                        $via_route_latitude = $fetch_itinerary_route_data['via_route_latitude'];
                                                        $via_route_longtitude = $fetch_itinerary_route_data['via_route_longtitude'];
                                                        $route_start_time = $fetch_itinerary_route_data['route_start_time'];
                                                        $route_end_time = $fetch_itinerary_route_data['route_end_time'];

                                                        if ($itinerary_route_counter == 1) :
                                                            $show_first_accordion = 'show';
                                                            $show_active_accordion = 'active';
                                                            $collapsed_active_accordion = '';
                                                            $collapsed_accordion = 'false';
                                                        else :
                                                            $show_first_accordion = '';
                                                            $show_active_accordion = '';
                                                            $collapsed_active_accordion = 'collapsed';
                                                            $collapsed_accordion = 'true';
                                                        endif;

                                                        $timestamp = strtotime($itinerary_route_date);

                                                        if ($timestamp !== false) {
                                                            // Get the numeric representation of the day of the week (0 for Sunday, 1 for Monday, etc.)
                                                            $dayOfWeekNumeric = (int)date('N', $timestamp) - 1;
                                                        } else {
                                                            //echo "Invalid date format.";
                                                        }
                                                ?>
                                                        <!-- Day <?= $itinerary_route_counter; ?> -->
                                                        <div class="accordion-item border-0 <?= $show_active_accordion; ?> bg-white rounded-3 mb-3">
                                                            <div class="accordion-header itinerary-sticky-title p-0 mb-3">
                                                                <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0 <?= $collapsed_active_accordion; ?>" data-bs-toggle="collapse" data-bs-target="#day_<?= $itinerary_route_counter; ?>" aria-expanded="<?= $collapsed_accordion; ?>">
                                                                    <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar-wrapper">
                                                                                <div class="avatar me-2">
                                                                                    <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                </div>
                                                                            </div>
                                                                            <span class="d-flex">
                                                                                <h6 class="mb-0"> <b>DAY <?= $itinerary_route_counter; ?></b> -
                                                                                    <?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>
                                                                                    | <?= $location_name; ?></h6>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div id="day_<?= $itinerary_route_counter; ?>" class="accordion-collapse collapse <?= $show_first_accordion; ?>">
                                                                <div class="accordion-body pt-1 pb-0">
                                                                    <div>
                                                                        <div class="d-flex justify-content-between align-items-center default_itineray_header_<?= $itinerary_route_counter; ?>" id="default_itineray_header">

                                                                            <h5 class="text-uppercase mb-0">Itinerary</h5>

                                                                            <div>
                                                                                <div class="d-flex align-items-center  justify-content-center">
                                                                                    <span class="d-flex">
                                                                                        <div class="form-group">
                                                                                            <?php if ($itinerary_route_counter == 1) : ?>
                                                                                                <input type="text" readonly="" class="form-control-plaintext text-primary fw-bolder w-px-75 text-center" id="hotspot_start_time_<?= $itinerary_route_counter; ?>" name="hotspot_start_time_<?= $itinerary_route_counter; ?>" value="<?= date('h:i A', strtotime($trip_start_date_and_time)); ?>" />
                                                                                            <?php else : ?>
                                                                                                <input class="form-control w-px-100 text-center" type="time" placeholder="hh:mm" id="hotspot_start_time_<?= $itinerary_route_counter; ?>" name="hotspot_start_time_<?= $itinerary_route_counter; ?>" required value="<?= $route_start_time; ?>">
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                        <div class="form-group">
                                                                                            <input type="text" readonly="" class="form-control-plaintext w-px-40 text-center" value="TO" />
                                                                                        </div>

                                                                                        <div class="form-group">
                                                                                            <?php if ($itinerary_route_counter == $total_itinerary_route_count) : ?>
                                                                                                <input type="text" readonly="" class="form-control-plaintext text-primary fw-bolder w-px-75 text-center" id="hotspot_end_time_<?= $itinerary_route_counter; ?>" name="hotspot_end_time_<?= $itinerary_route_counter; ?>" value="<?= date('h:i A', strtotime($trip_end_date_and_time)); ?>" />
                                                                                            <?php else : ?>
                                                                                                <input class="form-control w-px-100 text-center" type="time" placeholder="hh:mm" id="hotspot_end_time_<?= $itinerary_route_counter; ?>" name="hotspot_end_time_<?= $itinerary_route_counter; ?>" required value="<?= $route_end_time; ?>">
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </span>
                                                                                    <button type="button" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="addITINEARYROUTETIME(<?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_counter; ?>, <?= $total_itinerary_route_count; ?>,'<?= $itinerary_route_date; ?>')">
                                                                                        <span class="ti ti-calendar-time me-2"></span> Update
                                                                                    </button>
                                                                                </div>
                                                                                <p class="mb-0 mt-2">
                                                                                    <i class="ti ti-info-circle-filled mb-1 me-1"></i><span class="text-warning">Before 6 AM</span> and <span class="text-warning">after 8 PM</span>, extra charges for vehicle and driver are applicable.
                                                                                </p>
                                                                            </div>

                                                                            <input type="hidden" class="form-control" name="check_in_hotel_status_<?= $itinerary_route_ID; ?>" id="check_in_hotel_status_<?= $itinerary_route_ID; ?>" hidden value="0" />

                                                                            <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm customize_<?= $itinerary_plan_ID; ?>_<?= $itinerary_route_ID; ?>" onclick="show_add_HOTSPOTS(<?= $itinerary_route_counter; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, '<?= $location_name; ?>', '<?= $itinerary_route_date; ?>','<?= $next_visiting_location ?>','<?= $location_via_route ?>')">
                                                                                <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize
                                                                            </button>
                                                                        </div>
                                                                        <input type="hidden" name="hidden_location_name" id="hidden_location_name" value="<?= $location_name; ?>" hidden>
                                                                        <input type="hidden" name="hidden_itinerary_route_date" id="hidden_itinerary_route_date" value="<?= $itinerary_route_date; ?>" hidden>
                                                                        <span class="show_add_hotsopt_form_<?= $itinerary_route_counter; ?>" id="show_add_hotsopt_form"></span>
                                                                        <p class="mb-4 show_available_hotspot_list_<?= $itinerary_route_counter; ?>" id="show_available_hotspot_list"></p>

                                                                        <?php
                                                                        if ($before_location_name == '') :
                                                                            $before_location_name = $location_name;
                                                                        else :
                                                                            if ($before_location_name == $location_name) : ?>
                                                                                <div class="bs-toast toast fade show w-50 my-3 text-white border-0 mx-auto" role="alert" aria-live="assertive" aria-atomic="true" style="box-shadow: none !important; background-color: #c489e9 !important">
                                                                                    <div class="toast-body d-flex align-items-center text-white border-0" style="box-shadow: none !important; background-color: #c489e9 !important">
                                                                                        <i class="ti ti-bell ti-xs me-2 text-white"></i>
                                                                                        <div class="me-auto fw-medium">Day Trip is available
                                                                                        </div>
                                                                                        <button type="button" class="btn-close btn-close-white text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                                                                    </div>
                                                                                </div>
                                                                        <?php
                                                                            else :
                                                                                $before_location_name = '';
                                                                            endif;
                                                                        endif;
                                                                        ?>
                                                                        <span class="show_added_hotspot_response_<?= $itinerary_route_counter; ?>" id="show_added_hotspot_response">
                                                                            <?php

                                                                            if ($check_guide_for_itinerary == 0) :
                                                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' and `itinerary_route_ID` = '$itinerary_route_ID' AND `guide_type`='2'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                                                                                $route_guide_ID = '';
                                                                                $guide_type = '';
                                                                                $guide_language = '';
                                                                                $guide_slot = '';
                                                                                $name_guide_language = '';
                                                                                $name_guide_slot = '';

                                                                                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                                                if ($total_itinerary_guide_route_count > 0) :
                                                                                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                                                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                                                    endwhile;
                                                                            ?>
                                                                                    <span id="edit_guide_modal" class="" style="color: #4d287b;">
                                                                                        Guide Language - <span class="text-primary" id="language_choosen_itinerary"><?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'label'); ?><br><?= 'Slot Timing - ' . getSLOTTYPE($guide_slot, 'label'); ?></span>
                                                                                        <a href="javascript:void(0)" id="add_guide_modal_<?= $itinerary_route_counter; ?>" onclick="showaddGUIDEADDFORMMODAL('<?= $nav_route_count; ?>',<?= $route_guide_ID; ?>, '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>');" class="edit_guide_modal_link" style="color: #4d287b;">
                                                                                            <span class="ti-sm ti ti-edit mb-1"></span>
                                                                                        </a>
                                                                                    </span>
                                                                                    <p style="color: #4d287b;">
                                                                                        <b><?= general_currency_symbol . ' ' . number_format(get_ITINEARY_GUIDE_COST_DETAILS($itinerary_plan_ID, $itinerary_route_ID, $itinerary_route_date, $guide_type, $guide_language, $guide_slot, $total_pax_count, 'check_eligible_guide'), 2); ?></b>
                                                                                    </p>
                                                                                <?php else : ?>
                                                                                    <div class="mt-3 day_wise_guide_avilability_<?= $itinerary_route_counter; ?>">
                                                                                        <a href="javascript:void(0)" class="btn btn-label-github btn-sm mt-1" id="add_guide_modal_<?= $itinerary_route_counter; ?>" onclick="showaddGUIDEADDFORMMODAL('<?= $nav_route_count; ?>',0, '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>');">
                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add
                                                                                            Guide
                                                                                        </a>
                                                                                    </div>

                                                                            <?php endif;
                                                                            endif;
                                                                            $select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT  ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`itinerary_plan_ID`, ROUTE_HOTSPOT.`hotspot_plan_own_way`, ROUTE_HOTSPOT.`itinerary_route_ID`, ROUTE_HOTSPOT.`hotspot_ID`, ROUTE_HOTSPOT.`hotspot_entry_time_label`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_activity_skipping`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_operating_hours`, HOTSPOT_PLACE.`hotspot_photo_url`, HOTSPOT_PLACE.`hotspot_rating`,HOTSPOT_PLACE.`hotspot_duration` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT_PLACE ON ROUTE_HOTSPOT.`hotspot_ID`=HOTSPOT_PLACE.`hotspot_ID` WHERE ROUTE_HOTSPOT.`deleted` = '0' and ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' ORDER BY ROUTE_HOTSPOT.`hotspot_order` ASC") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                                                                            $total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);
                                                                            ?>
                                                                            <ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
                                                                                <?php
                                                                                if ($total_route_hotspot_list_num_rows_count > 0) :
                                                                                    while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
                                                                                        $counter++;
                                                                                        $route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
                                                                                        //$item_type = $fetch_route_hotspot_list_data['item_type'];
                                                                                        //$custom_description = $fetch_route_hotspot_list_data['custom_description'];
                                                                                        $itinerary_plan_ID = $fetch_route_hotspot_list_data['itinerary_plan_ID'];
                                                                                        $itinerary_route_ID = $fetch_route_hotspot_list_data['itinerary_route_ID'];
                                                                                        $hotspot_ID = $fetch_route_hotspot_list_data['hotspot_ID'];
                                                                                        $hotspot_entry_time_label = $fetch_route_hotspot_list_data['hotspot_entry_time_label'];
                                                                                        $hotspot_amout = $fetch_route_hotspot_list_data['hotspot_amout'];
                                                                                        $hotspot_traveling_time = $fetch_route_hotspot_list_data['hotspot_traveling_time'];
                                                                                        $hotspot_travelling_distance = $fetch_route_hotspot_list_data['hotspot_travelling_distance'];
                                                                                        $hotspot_start_time = $fetch_route_hotspot_list_data['hotspot_start_time'];
                                                                                        $hotspot_end_time = $fetch_route_hotspot_list_data['hotspot_end_time'];
                                                                                        $hotspot_activity_skipping = $fetch_route_hotspot_list_data['hotspot_activity_skipping'];
                                                                                        $hotspot_name = $fetch_route_hotspot_list_data['hotspot_name'];
                                                                                        $hotspot_description = $fetch_route_hotspot_list_data['hotspot_description'];
                                                                                        $hotspot_address = $fetch_route_hotspot_list_data['hotspot_address'];
                                                                                        $hotspot_operating_hours = $fetch_route_hotspot_list_data['hotspot_operating_hours'];
                                                                                        $hotspot_operating_hours = explode('|', $hotspot_operating_hours);
                                                                                        $hotspot_photo_url = $fetch_route_hotspot_list_data['hotspot_photo_url'];
                                                                                        $hotspot_rating = $fetch_route_hotspot_list_data['hotspot_rating'];
                                                                                        $hotspot_duration = $fetch_route_hotspot_list_data['hotspot_duration'];
                                                                                        $hotspot_plan_own_way = $fetch_route_hotspot_list_data['hotspot_plan_own_way'];
                                                                                ?>
                                                                                        <li class="timeline-item timeline-item-transparent">

                                                                                            <?php if ($item_type == 1) : ?>
                                                                                                <span class="timeline-point timeline-point-success"></span>
                                                                                                <div class="timeline-event">
                                                                                                    <div class="timeline-header mb-sm-0 mb-3">
                                                                                                        <h6 class="mb-0">
                                                                                                            <?= getGLOBALSETTING('itinerary_break_time'); ?>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                    <div class="timeline-event-time timeline-event-time-itinerary">
                                                                                                        <?= date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php elseif ($item_type == 2) : ?>
                                                                                                <span class="timeline-indicator-advanced timeline-indicator-warning">
                                                                                                    <i class="ti ti-road rounded-circle"></i>
                                                                                                </span>
                                                                                                <div class="timeline-event">
                                                                                                    <div class="timeline-header mb-sm-0 mb-3">
                                                                                                        <h6 class="mb-0 text-warning">Travelling to
                                                                                                            <span class="text-dark"><?= $custom_description; ?></span>
                                                                                                            <span class="text-primary">distance
                                                                                                                <?= $hotspot_travelling_distance; ?></span>,
                                                                                                            <span class="text-primary">estimated time
                                                                                                                <?= formatDuration($hotspot_traveling_time); ?></span>
                                                                                                            and this may vary due to traffic conditions.
                                                                                                            <?php if ($hotspot_plan_own_way) : ?>
                                                                                                                <div class="bs-toast toast fade show w-50 my-3 text-white border-0 mx-auto" role="alert" aria-live="assertive" aria-atomic="true" style="box-shadow: none !important; background-color: #c489e9 !important">
                                                                                                                    <div class="toast-body d-flex align-items-center text-white border-0" style="box-shadow: none !important; background-color: #c489e9 !important">
                                                                                                                        <i class="ti ti-bell ti-xs me-2 text-white"></i>
                                                                                                                        <div class="me-auto fw-medium">You have deviated from our suggestion and implemented your approach.
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            <?php endif; ?>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                    <div class="timeline-event-time timeline-event-time-itinerary">
                                                                                                        <?= date('h:i A', strtotime($hotspot_start_time)) . ' - ' . date('h:i A', strtotime($hotspot_end_time)); ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php elseif ($item_type == 3) : ?>
                                                                                                <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                    <i class="ti ti-map-pin rounded-circle text-primary"></i>
                                                                                                </span>
                                                                                                <div class="timeline-event pb-3">
                                                                                                    <div class="d-flex flex-sm-row flex-column align-items-center">
                                                                                                        <div>
                                                                                                            <img src="../assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                        </div>
                                                                                                        <div class="w-100">
                                                                                                            <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                <h6 class="mb-0 text-capitalize">Marina
                                                                                                                    Beach</h6>
                                                                                                                <button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_itinerary_route_hotspot_in_list(<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>, '<?= $TYPE; ?>')"><span class="ti ti-trash"></span></button>
                                                                                                            </div>
                                                                                                            <p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i>Chennai,
                                                                                                                Tamil Nadu, India</p>
                                                                                                            <p class="my-1">
                                                                                                                <i class="ti ti-clock-filled me-1 mb-1"></i>8
                                                                                                                AM
                                                                                                            </p>

                                                                                                            <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                <p class="mt-1 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>No
                                                                                                                    Fare</p>
                                                                                                                <h6 class="text-primary mb-0">
                                                                                                                    <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i>
                                                                                                                    <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i>
                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                </h6>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <p class="mt-2" style="text-align: justify;">Marina
                                                                                                        Beach, the pride of Chennai is the second
                                                                                                        longest beach in the world and has a wide sandy
                                                                                                        shore. Situated on the beach, are the Samadhis
                                                                                                        or memorials dedicated to C.N.Annadurai and
                                                                                                        M.G.Ramachandran, both former Chief Ministers of
                                                                                                        the state.
                                                                                                    </p>

                                                                                                    <div class="col-12 text-center">
                                                                                                        <button type="button" class="btn btn-primary" onclick="showACTIVITYMODAL('<?= $itinerary_route_counter; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $route_hotspot_ID; ?>', '<?= $hotspot_ID; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $hotspot_start_time; ?>', '<?= $hotspot_end_time; ?>')">Add
                                                                                                            Activities </button>
                                                                                                    </div>
                                                                                                    <div class="timeline-event-time timeline-event-time-itinerary">
                                                                                                        8:30 AM To 8:50 AM</div>
                                                                                                </div>

                                                                                                <!-- Activities -->
                                                                                                <div class="row">
                                                                                                    <div class="col-12">
                                                                                                        <ul class="timeline timeline-center mt-1">
                                                                                                            <li class="timeline-item timeline-item-activities">
                                                                                                                <span class="timeline-indicator timeline-indicator-primary">
                                                                                                                    <i class="ti ti-trekking ti-sm"></i>
                                                                                                                </span>
                                                                                                                <div class="timeline-event timeline-event-activities pb-3 py-2 px-3">
                                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                        <h6 class="mb-0 text-capitalize">
                                                                                                                            <b>Swimming</b>
                                                                                                                        </h6>
                                                                                                                        <button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_route_counter; ?>', '<?= $itinerary_route_date; ?>', '<?= $hotspot_ID; ?>')"><span class="ti ti-trash"></span></button>
                                                                                                                    </div>
                                                                                                                    <div class="d-flex flex-sm-row flex-column align-items-center">
                                                                                                                        <img src="../assets/img/itinerary/hotspots/yoga.jpg" class="rounded me-3" alt="Show img" height="80" width="80" />
                                                                                                                        <div class="w-100">
                                                                                                                            <p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">10 AM
                                                                                                                                </span></p>
                                                                                                                            <p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum
                                                                                                                                    10 Persons
                                                                                                                                    Allowed</span></p>
                                                                                                                            <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                                <p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>
                                                                                                                                    Adult: 10, Child:
                                                                                                                                    10, Infant: 10
                                                                                                                                </p>
                                                                                                                                <h6 class="text-primary mb-0">
                                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                                </h6>
                                                                                                                                </h6>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                    <p class="mt-2 mb-0" style="text-align: justify;">
                                                                                                                        Marina Beach, the pride of
                                                                                                                        Chennai is the second longest
                                                                                                                        beach in the world and has a
                                                                                                                        wide sandy shore. Situated on
                                                                                                                        the beach, are the Samadhis or
                                                                                                                        memorials dedicated to
                                                                                                                        C.N.Annadurai and
                                                                                                                        M.G.Ramachandran, both former
                                                                                                                        Chief Ministers of the state.
                                                                                                                    </p>
                                                                                                                    <div class="timeline-event-time timeline-event-time-activities">
                                                                                                                        8:50 AM To 10:50 AM</div>
                                                                                                                </div>
                                                                                                            </li>
                                                                                                        </ul>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- Activities -->
                                                                                            <?php elseif ($item_type == 4) : ?>
                                                                                                <span class="timeline-point timeline-point-success"></span>
                                                                                                <div class="timeline-event">
                                                                                                    <div class="timeline-header mb-sm-0 mb-3">
                                                                                                        <h6 class="mb-0">
                                                                                                            <?= getGLOBALSETTING('accommodation_return'); ?>
                                                                                                        </h6>
                                                                                                    </div>
                                                                                                    <div class="timeline-event-time timeline-event-time-itinerary">
                                                                                                        10:50 AM to 11:50 AM
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php endif; ?>
                                                                                        </li>
                                                                                    <?php endwhile; ?>
                                                                                <?php endif; ?>
                                                                            </ul>
                                                                        </span>
                                                                        <span class="show_loader_response_<?= $itinerary_route_counter; ?>" id="show_loader_response"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                    <?php endwhile;
                                                endif; ?>
                                                        </div>

                                                        <?php if ($itinerary_preference == '1' || $itinerary_preference == '3') : ?>
                                                            <div class="divider my-4">
                                                                <div class="divider-text">
                                                                    <i class="ti ti-map-2 ti-sm text-primary"></i>
                                                                </div>
                                                            </div>

                                                            <div id="hotel_list"></div>
                                                        <?php endif;
                                                        if ($itinerary_preference == '2' || $itinerary_preference == '3') : ?>

                                                            <div class="divider my-4">
                                                                <div class="divider-text">
                                                                    <i class="ti ti-map-2 ti-sm text-primary"></i>
                                                                </div>
                                                            </div>

                                                            <div id="vehicle_list"></div>
                                                        <?php endif; ?>

                                                        <div class="divider my-4">
                                                            <div class="divider-text">
                                                                <i class="ti ti-map-2 ti-sm text-primary"></i>
                                                            </div>
                                                        </div>

                                                        <div class="row mt-3 justify-content-center" id="overall_cost_summary">
                                                            <div class="col-md-12">
                                                                <div class="">
                                                                    <h5 class="card-header p-0 mb-2 text-uppercase">Overall Cost</h5>
                                                                    <div class="order-calculations">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Hotspot</span>
                                                                            <h6 class="mb-0"><?= $global_currency_format . ' '; ?><span id="gross_total_hotspot_package"><?= number_format(getOVERALLCOSTFORTYPE($itinerary_plan_ID, 'hotspot'), 2); ?></span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Activity</span>
                                                                            <h6 class="mb-0"><?= $global_currency_format . ' '; ?><span id="gross_total_activity_package"><?= number_format(getOVERALLCOSTFORTYPE($itinerary_plan_ID, 'activity'), 2); ?></span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Hotel</span>
                                                                            <h6 class="mb-0"><?= $global_currency_format . ' '; ?><span id="gross_total_hotel_package"><?= number_format(getOVERALLCOSTFORTYPE($itinerary_plan_ID, 'hotel'), 2); ?></span></h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Total for The Vehicle</span>
                                                                            <h6 class="mb-0"><?= $global_currency_format . ' '; ?><span id="gross_total_vehicle_package"><?= number_format(getOVERALLCOSTFORTYPE($itinerary_plan_ID, 'vehicle'), 2); ?></span></h6>
                                                                        </div>

                                                                        <hr />
                                                                        <?php
                                                                        $select_vehicle_summary = sqlQUERY_LABEL("SELECT `vendor_id`,`vehile_type_id`,`vehicle_count`,SUM(`total_kms_travelled`) AS total_kms_travelled , SEC_TO_TIME(SUM(TIME_TO_SEC(`total_time`))) AS total_time, SUM(`total_driver_cost`) AS total_driver_cost, 
                                                SUM(`total_driver_gst_amt`) AS total_driver_gst_amt, 
                                                SUM(`toll_charge`) AS toll_charge, 
                                                SUM(`vehicle_parking_charge`) AS vehicle_parking_charge, 
                                                SUM(`vehicle_permit_cost`) AS vehicle_permit_cost, 
                                                SUM(`vehicle_gst_amount`) AS vehicle_gst_amount, 
                                                SUM(`vehicle_per_day_cost`) AS vehicle_per_day_cost, 
                                                SUM(`total_vehicle_cost`) AS total_vehicle_cost, 
                                                SUM(`total_vehicle_cost_with_gst`) AS total_vehicle_cost_with_gst 
                                            FROM `dvi_itinerary_plan_vendor_vehicle_details` 
                                            WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' 
                                            AND `status` = '1' 
                                            AND `deleted` = '0' 
                                            GROUP BY `vehile_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                                        $vehicle_summary_cout = sqlNUMOFROW_LABEL($select_vehicle_summary);
                                                                        $grand_total_vehicle_summary = 0;
                                                                        while ($fetch_summary_data = sqlFETCHARRAY_LABEL($select_vehicle_summary)) :
                                                                            $grand_total_vehicle_summary = $grand_total_vehicle_summary + ($fetch_summary_data['total_vehicle_cost_with_gst']);
                                                                            $vendor_id = $fetch_summary_data['vendor_id'];
                                                                        endwhile; ?>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Gross Total for The Package</span>
                                                                            <h6 class="mb-0"><?= $global_currency_format . ' '; ?> <span id="gross_total_package"><?= getOVERLALLTRIPCOST($itinerary_plan_ID); ?></span></h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">GST @ 5 % On The total Package
                                                                            </span>
                                                                            <h6 class="mb-0"><?= $global_currency_format . ' '; ?> <span id="gst_total_package"><?= getOVERLALLTRIPCOST($itinerary_plan_ID); ?></span></h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading fw-bold">Net Payable To Doview
                                                                                Holidays India Pvt ltd</span>
                                                                            <h6 class="mb-0 fw-bold"><?= $global_currency_format . ' '; ?> <span id="net_total_package"><?= getOVERLALLTRIPCOST($itinerary_plan_ID); ?></span></h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex justify-content-between">
                                                            <div class="demo-inline-spacing">
                                                                <button type="button" class="btn rounded-pill btn-google-plus waves-effect waves-light">
                                                                    <i class="tf-icons ti ti-mail ti-xs me-1"></i> Share Via Email
                                                                </button>
                                                                <button type="button" class="btn rounded-pill btn-success d-none waves-effect waves-light">
                                                                    <i class="tf-icons ti ti-brand-whatsapp ti-xs me-1"></i> Share Via
                                                                    Whatsapp
                                                                </button>
                                                            </div>
                                                            <div class="demo-inline-spacing">
                                                                <button type="button" class="btn btn-primary waves-effect waves-light">
                                                                    <span class="ti-xs ti ti-check me-1"></span>Confirm
                                                                </button>
                                                            </div>
                                                        </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    endfor; ?>

                </div>
            </div>
        </div>
        <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

        <!-- Vendors JS -->
        <script src="assets/vendor/libs/animate-on-scroll/animate-on-scroll.js"></script>

        <!-- Main JS -->
        <script src="assets/js/main.js"></script>

        <script src="assets/js/extended-ui-timeline.js"></script>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            // CALCULATING TOTAL AMOUNT FOR THE HOTEL
            let overall_trip_cost = 0;
            const overall_cost_hotspot_activity_hotel = '<?= getOVERLALLTRIPCOST($itinerary_plan_ID); ?>';
            const total_vendor_margin = '<?= calculateVENDORMARGIN($grand_total_vehicle_summary, $vendor_id); ?>';
            const total_guide_charge = '<?= $total_guide_charges; ?>';

            //const overall_vehicle_cost = $('#overall_vehicle_cost').text();
            const overall_vehicle_cost = '0';
            // Remove commas and convert to numbers
            const overall_cost_hotspot_activity_hotel_number = parseFloat(overall_cost_hotspot_activity_hotel.replace(/,/g, ""));
            const overall_vehicle_cost_number = parseFloat(overall_vehicle_cost.replace(/,/g, ""));
            const total_vendor_margin_cost_number = parseFloat(total_vendor_margin.replace(/,/g, ""));
            const total_guide_charge_cost_number = parseFloat(total_guide_charge.replace(/,/g, ""));
            // Add the numbers
            overall_trip_cost = overall_cost_hotspot_activity_hotel_number + overall_vehicle_cost_number + total_vendor_margin_cost_number + total_guide_charge_cost_number;
            // Display the result
            $("#gross_total_package").html(overall_trip_cost.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            let gst_amt = (5 / 100) * overall_trip_cost;
            let net_total_package = overall_trip_cost + gst_amt;
            $("#gst_total_package").html(gst_amt.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            $("#overall_trip_cost").html(net_total_package.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
            $("#net_total_package").html(net_total_package.toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));

            $(document).ready(function() {

                showHOTELLIST();
                showVEHICLELIST();

                $(".form-select").selectize();
                $("#guide_language").attr('required', true);
                $("#guide_slot").attr('required', true);

                "use strict";
                !(function() {
                    var i1 = document.getElementById("response_for_the_added_hotspots");
                    i1 && Sortable.create(i1, {
                        animation: 150,
                        group: "taskList"
                    });
                })();

                <?php
                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_name`, `itinerary_route_date`,  `no_of_days`, `no_of_km`, `location_via_route` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                if ($total_itinerary_route_count > 0) :
                    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                        $itinerary_route_counter_flatpickr++;
                        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
                        $itinerary_plan_ID = $fetch_itinerary_route_data['itinerary_plan_ID'];
                        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];

                        if ($itinerary_route_counter_flatpickr > 1) : ?>
                            flatpickr('#hotspot_start_time_<?= $itinerary_route_counter_flatpickr; ?>', {
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                                time_24hr: false,
                            });
                        <?php endif; ?>

                        show_added_ITINERARY_DETAILS('<?= $itinerary_route_counter_flatpickr; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $itinerary_route_date; ?>');

                        <?php if ($itinerary_route_counter_flatpickr == $total_itinerary_route_count) : ?>
                        <?php else : ?>
                            flatpickr('#hotspot_end_time_<?= $itinerary_route_counter_flatpickr; ?>', {
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                                time_24hr: false,
                            });
                        <?php endif; ?>
                <?php
                    endwhile;
                endif; ?>
            });

            function addITINEARYROUTETIME(itinerary_route_ID, itinerary_plan_ID, itinerary_route_counter,
                total_itinerary_route_count, itinerary_route_date) {
                var hotspot_start_time = $('#hotspot_start_time_' + itinerary_route_counter).val();
                var hotspot_end_time = $('#hotspot_end_time_' + itinerary_route_counter).val();

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_manage_newitinerary.php?type=update_itinerary_route_timing',
                    data: {
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_plan_ID: itinerary_plan_ID,
                        itinerary_route_counter: itinerary_route_counter,
                        hotspot_start_time: hotspot_start_time,
                        hotspot_end_time: hotspot_end_time,
                        total_itinerary_route_count: total_itinerary_route_count
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            // NOT SUCCESS RESPONSE
                            if (response.errors.itinerary_route_ID_required) {
                                TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_plan_ID_required) {
                                TOAST_NOTIFICATION('warning', 'Itinerary Plan is Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.itinerary_route_counter_required) {
                                TOAST_NOTIFICATION('warning', 'Itinerary Route Counter is Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_start_time_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Start Time is Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_end_time_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot End Time is Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotspot_start_time_exceed) {
                                TOAST_NOTIFICATION('warning', 'Start time should not greater than end time',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_end_time_exceed) {
                                TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_start_and_end_time_exceed) {
                                TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.minimum_trip_end_time_should_be_required) {
                                TOAST_NOTIFICATION('warning', response.errors.minimum_trip_end_time_should_be_required,
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_buffer_exceed) {
                                TOAST_NOTIFICATION('warning', response.errors.hotspot_buffer_exceed,
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_minimum_hotspot_ending_hour_required) {
                                TOAST_NOTIFICATION('warning', 'End time should be +1 Hour from the Start Time',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPOSNE
                            $('#overall_cost_summary').load(' #overall_cost_summary');
                            if (response.u_result == true) {
                                TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '',
                                    '', '', '', '');
                                //show_ITINERARY_FORM_STEP3('<?= $TYPE; ?>', <?= $itinerary_plan_ID; ?>);
                                show_added_ITINERARY_DETAILS(itinerary_route_counter, itinerary_plan_ID, itinerary_route_ID, itinerary_route_date);

                                //show_add_HOTSPOTS(<?= $itinerary_route_counter; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, '<?= $location_name; ?>', '<?= $itinerary_route_date; ?>','<?= $next_visiting_location ?>','<?= $location_via_route ?>')
                            } else if (response.u_result == false) {
                                // RESULT FAILED
                                TOAST_NOTIFICATION('warning', 'Unable to Update', 'Success !!!', '', '', '', '', '', '',
                                    '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    }
                });

            }

            function show_added_ITINERARY_DETAILS(itinerary_count, itinerary_plan_ID, itinerary_route_ID, hotspot_route_date) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_show_added_hotspot_in_itinerary_plan.php?type=show_form',
                    data: {
                        itinerary_count: itinerary_count,
                        itinerary_route_ID: itinerary_route_ID,
                        itinerary_plan_ID: itinerary_plan_ID,
                        hotspot_route_date: hotspot_route_date
                    },
                    success: function(response) {
                        $('#default_itineray_header.default_itineray_header_' + itinerary_count).removeClass('d-none');
                        $('#show_add_hotsopt_form.show_add_hotsopt_form_' + itinerary_count).html('');
                        $('#show_added_hotspot_response.show_added_hotspot_response_' + itinerary_count).html(response);
                        $('#show_available_hotspot_list.show_available_hotspot_list_' + itinerary_count).html('');
                    }
                });
            }

            function showHOTELLIST() {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=show_itinerary_plan_hotel_details",
                    data: {
                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>',
                    },
                    success: function(response) {
                        $('#hotel_list').html(response);
                    }
                });
            }

            function showVEHICLELIST() {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_itinerary_plan_vehicle_details.php?type=show_itinerary_plan_vehicle_details",
                    data: {
                        itinerary_plan_ID: '<?= $itinerary_plan_ID; ?>',
                    },
                    success: function(response) {
                        $('#vehicle_list').html(response);
                    }
                });
            }

            function show_TOAST_MESSAGE_FOR_TIME() {
                TOAST_NOTIFICATION('warning', 'Kindly update start and end time', 'Warning !!!', '', '', '', '', '', '', '', '', '');
            }

            function show_add_HOTSPOTS(itinerary_count, itinerary_route_ID, itinerary_plan_ID, hidden_location_name,
                hidden_itinerary_route_date, next_visiting_location_name, via_route_location) {

                var route_start_time = $('#hotspot_start_time_' + itinerary_count).val();
                var route_end_time = $('#hotspot_end_time_' + itinerary_count).val();

                if (route_start_time == '' || route_end_time == '') {
                    show_TOAST_MESSAGE_FOR_TIME();
                } else {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_add_new_hotspot_for_itinerary_plan.php?type=show_form',
                        data: {
                            hidden_location_name: hidden_location_name,
                            hidden_itinerary_route_date: hidden_itinerary_route_date,
                            hidden_itinerary_count: itinerary_count,
                            itinerary_route_ID: itinerary_route_ID,
                            itinerary_plan_ID: itinerary_plan_ID,
                            next_visiting_location_name: next_visiting_location_name,
                            via_route_location: via_route_location
                        },
                        success: function(response) {
                            $('#default_itineray_header.default_itineray_header_' + itinerary_count).addClass('d-none');
                            $('#show_added_hotspot_response.show_added_hotspot_response_' + itinerary_count).html('');
                            $('#show_add_hotsopt_form.show_add_hotsopt_form_' + itinerary_count).html(response);
                        }
                    });
                }
            }

            function showaddGUIDEADDFORMMODAL(ROUTE_COUNTER, ROUTE_GUIDE_ID, GUIDE_TYPE, itinerary_plan_ID, itinerary_route_ID, DATE) {
                $('#addGUIDEADDFORM').modal('hide');
                $('.receiving-guide-add-form-data').load(
                    'engine/ajax/__ajax_modal_guide_form.php?type=guide_form_for_itinerary&ROUTE_GUIDE_ID=' + ROUTE_GUIDE_ID +
                    '&GUIDE_TYPE=' + GUIDE_TYPE + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' +
                    itinerary_route_ID + '&ROUTE_COUNTER=' + ROUTE_COUNTER,
                    function() {
                        const container = document.getElementById("addGUIDEADDFORM");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                        if (ROUTE_GUIDE_ID) {
                            if (GUIDE_TYPE == '1') {
                                $('#GUIDEFORMLabel').html('Update Guide For Itinerary');
                            } else {
                                $('#GUIDEFORMLabel').html('Update Guide ' + DATE);
                            }
                        } else {
                            if (GUIDE_TYPE == '1') {
                                $('#GUIDEFORMLabel').html('Add Guide For Itinerary');
                            } else {
                                $('#GUIDEFORMLabel').html('Add Guide ' + DATE);
                            }
                        }
                    });
            }

            function numberFormat(number, thousandSeparator) {
                number = Number(number);
                thousandSeparator = thousandSeparator || ',';

                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
            }

            $(document).ready(function() {

                //CALCULATING TOTAL AMOUNT FOR THE HOTEL
                let totalRoomRate = 0;
                $('.cls_room_rate').each(function() {
                    const rate = parseFloat($(this).text());
                    if (!isNaN(rate)) {
                        totalRoomRate += rate;
                    }
                });

                totalRoomRate = numberFormat(totalRoomRate, ',');
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
                        TOAST_NOTIFICATION('error', 'Total extra bed count exceeded', 'Error !!!', '', '', '', '',
                            '', '', '', '', '');
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
                $("#form_hotel_list").submit(function(event) {
                    alert("submit");
                    var form = $('#form_hotel_list')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_itinerary_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_itinerary_plan_hotel_details.php?type=update_itinerary_plan_hotel_details',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            //if (response.errors.arrival_location_required) {
                            //    TOAST_NOTIFICATION('error', 'Arrival Place is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            //}
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Itinerary Hotel Details Updated',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                showHOTELLIST();
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error',
                                    'Unable to Update Itinerary Hotel Details Details', 'Error !!!', '',
                                    '', '', '', '', '', '', '', '');
                            }
                        }
                        if (response == "OK") {
                            return true;
                        } else {
                            return false;
                        }
                    });
                    event.preventDefault();
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
                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.room_rate);
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                let totalRoomRate = 0;

                                $('.cls_room_rate').each(function() {
                                    const rate = parseFloat($(this).text());
                                    if (!isNaN(rate)) {
                                        totalRoomRate += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(totalRoomRate);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to update Cost', 'Error !!!', '', '', '', '', '',
                                    '', '', '', '');
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
                    $('.hotel_update_btn_' + HOTEL_DETAILS_ID).addClass('d-none');
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
                                $("#room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).html(response.room_rate);
                                $("#hidden_room_rate_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val(response.room_rate);

                                let totalRoomRate = 0;

                                $('.cls_room_rate').each(function() {
                                    const rate = parseFloat($(this).text());
                                    if (!isNaN(rate)) {
                                        totalRoomRate += rate;
                                    }
                                });
                                $("#total_amount_for_hotel").html(totalRoomRate);

                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'No Rooms Available', 'Error !!!', '', '', '', '', '', '',
                                    '', '', '');
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

            function incrementValue(e, id) {
                e.preventDefault();
                var fieldName = $(e.target).data('field');
                var parent = $(e.target).closest('div');
                var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

                if (!isNaN(currentVal)) {
                    parent.find('input[name=' + fieldName + ']').val(currentVal + 1);
                } else {
                    parent.find('input[name=' + fieldName + ']').val(0);
                }
            }

            function decrementValue(e, id) {
                e.preventDefault();
                var fieldName = $(e.target).data('field');
                var parent = $(e.target).closest('div');
                var currentVal = parseInt(parent.find('input[name=' + fieldName + ']').val(), 10);

                if (!isNaN(currentVal) && currentVal > 0) {
                    parent.find('input[name=' + fieldName + ']').val(currentVal - 1);
                } else {
                    parent.find('input[name=' + fieldName + ']').val(0);
                }

            }
            $('.input-group.input_group_plus_minus').on('click', '.button-plus#input_plus_button', function(e) {
                var dataID = $(this).attr('data-id');

                incrementValue(e, dataID);
            });

            $('.input-group.input_group_plus_minus').on('click', '.button-minus#input_minus_button', function(e) {
                var dataID = $(this).attr('data-id');
                decrementValue(e, dataID);
            });
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
