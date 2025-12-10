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

    if ($_GET['type'] == 'show_form') : ?>

        <link rel="stylesheet" href="../../assets/css/style.css" />

        <?php
        $itinerary_plan_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `arrival_latitude`, `arrival_longitude`, `departure_latitude`, `departure_longitude`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
                $arrival_latitude = $fetch_list_data["arrival_latitude"];
                $arrival_longitude = $fetch_list_data["arrival_longitude"];
                $departure_latitude = $fetch_list_data["departure_latitude"];
                $departure_longitude = $fetch_list_data["departure_longitude"];
                $total_adult = $fetch_list_data["total_adult"];
                $total_children = $fetch_list_data["total_children"];
                $total_infants = $fetch_list_data["total_infants"];
                $preferred_room_count = $fetch_list_data["preferred_room_count"];
                $total_extra_bed = $fetch_list_data["total_extra_bed"];
                $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
            endwhile;
        endif;

        ?>
        <div id="se-pre-con"></div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-header">Tour Itinerary Plan</b></h5>
                    <a href="?route=add&formtype=itinerary_routes&id=<?= $itinerary_plan_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Route List</a>
                </div>
            </div>
            <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                <div>
                    <h5 class="text-capitalize"> Itinerary for <b><?= date('F d, Y', strtotime($trip_start_date_and_time)); ?></b> to <b><?= date('F d, Y', strtotime($trip_end_date_and_time)); ?></b> (<b><?= $no_of_nights; ?></b> Nights, <b><?= $no_of_days; ?></b> Days)</h5>
                    <h3 class="text-capitalize"><?= $arrival_location; ?> <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i> <?= $departure_location; ?></h3>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="mb-0 me-4"><strong>Adults</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_adult; ?></span></span>
                            <span class="mb-0 me-4"><strong>Children</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_children; ?></span></span>
                            <span class="mb-0 me-4"><strong>Infants</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_infants; ?></span></span>
                        </div>
                        <h5 class="mb-0"><strong>Budget</strong></span><span class="badge bg-primary bg-glow ms-2"><?= $global_currency_format . ' ' . number_format($expecting_budget, 0); ?></span></h5>
                    </div>
                </div>
                <div>
                </div>
            </div>

            <div class="nav-align-top my-2 p-0">
                <ul class="nav nav-pills" role="tablist">
                    <?php for ($nav_route_count = 1; $nav_route_count <= $no_of_routes; $nav_route_count++) : ?>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary<?= $nav_route_count; ?>" aria-controls="navs-top-itinerary<?= $nav_route_count; ?>" aria-selected="true">Route Itinerary <?= $nav_route_count; ?></button>
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
                                                    <h5 class="card-title mb-sm-0 me-2 text-primary">Route Itinerary <?= $tab_content_route_count; ?></h5>
                                                    <div>
                                                        <a href="javascript:void(0)" class="text-decoration-underline" onclick="showaddGUIDEADDFORMMODAL(0, '1', '<?= $itinerary_plan_ID; ?>', '', '');">
                                                            <span class="text-primary"> + Add Guide</span>
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="showaddGUIDEADDFORMMODAL(0, '1', '<?= $itinerary_plan_ID; ?>', '', '');">
                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                        </a>
                                                    </div>

                                                    <div>
                                                        <span style="color: #4d287b;">
                                                            Itinerary Guide Language - <span class="text-primary">Tamil, English</span>
                                                            <a href="javascript:void(0)" class="" onclick="showaddGUIDEADDFORMMODAL(0, '1', '<?= $itinerary_plan_ID; ?>', '', '');" style="color: #4d287b;" onclick="showaddGUIDEADDFORMMODAL(0, '1', '<?= $itinerary_plan_ID; ?>', '', '');">
                                                                <span class="ti-sm ti ti-edit mb-1"></span>
                                                            </a>
                                                        </span>
                                                    </div>
                                                </div>
                                                <h4 class="card-title mb-sm-0 me-2 text-primary">Overall Trip Cost <b class="text-primary"><?= $global_currency_format . ' '; ?><span id="overall_trip_cost"><?= number_format(getOVERLALLTRIPCOST($itinerary_plan_ID), 2); ?></span></b></h4>
                                                <input type="hidden" id="hotspot_amount" name="hotspot_amount" />
                                                <div class="action-btns">
                                                    <button class="btn btn-label-github me-3" id="scrollToTopButton">
                                                        <span class="align-middle"> Back To Top</span>
                                                    </button>
                                                </div>
                                            </div>

                                            <?php if ($guide_for_itinerary == '1') :

                                                $select_itinerary_guide_route_details = sqlQUERY_LABEL("SELECT `route_guide_ID`, `itinerary_plan_ID`, `itinerary_route_ID`, `guide_type`, `guide_language`, `guide_slot` FROM `dvi_itinerary_route_guide_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' AND `guide_type`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                $total_itinerary_guide_route_count = sqlNUMOFROW_LABEL($select_itinerary_guide_route_details);
                                                if ($total_itinerary_guide_route_count > 0) :
                                                    while ($fetch_itinerary_guide_route_data = sqlFETCHARRAY_LABEL($select_itinerary_guide_route_details)) :
                                                        $route_guide_ID = $fetch_itinerary_guide_route_data['route_guide_ID'];
                                                        $itinerary_plan_ID = $fetch_itinerary_guide_route_data['itinerary_plan_ID'];
                                                        $itinerary_route_ID = $fetch_itinerary_guide_route_data['itinerary_route_ID'];
                                                        $guide_type = $fetch_itinerary_guide_route_data['guide_type'];
                                                        $guide_language = $fetch_itinerary_guide_route_data['guide_language'];
                                                        $guide_slot = $fetch_itinerary_guide_route_data['guide_slot'];
                                                    endwhile;
                                                endif;
                                            ?>
                                                <div>
                                                    <?php if ($total_itinerary_guide_route_count > 0) : ?>
                                                        <a href="javascript:void(0)" class="btn btn-label-github btn-sm mt-1 d-none" id="add_guide_modal" onclick="showaddGUIDEADDFORMMODAL(0, '1', '<?= $itinerary_plan_ID; ?>', '', '', '');">
                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                        </a>
                                                        <span id="edit_guide_modal" class="" style="color: #4d287b;">
                                                            Itinerary Guide Language - <span class="text-primary" id="language_choosen_itinerary">Tamil, English</span>
                                                            <a href="javascript:void(0)" class="edit_guide_modal_link" style="color: #4d287b;">
                                                                <span class="ti-sm ti ti-edit mb-1"></span>
                                                            </a>
                                                        </span>
                                                    <?php else : ?>
                                                        <a href="javascript:void(0)" class="btn btn-label-github btn-sm mt-1" id="add_guide_modal" onclick="showaddGUIDEADDFORMMODAL(0, '1', '<?= $itinerary_plan_ID; ?>', '', '', '');">
                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                        </a>
                                                        <span id="edit_guide_modal" class="d-none" style="color: #4d287b;">
                                                            Itinerary Guide Language - <span class="text-primary" id="language_choosen_itinerary"></span>
                                                            <a href="javascript:void(0)" class="edit_guide_modal_link" style="color: #4d287b;">
                                                                <span class="ti-sm ti ti-edit mb-1"></span>
                                                            </a>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                        </div>

                                        <div class="card-body mt-3">
                                            <!-- Menu Accordion -->
                                            <div class="accordion" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar" style="--bs-accordion-bg: #f8f7fa;">
                                                <?php
                                                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_name`, `itinerary_route_date`, `location_latitude`, `location_longtitude`, `no_of_days`, `no_of_km`, `location_via_route`, `via_route_latitude`, `route_start_time`, `route_end_time`, `via_route_longtitude`,`next_visiting_location`,`next_visiting_location_latitude`,`next_visiting_location_longitude`  FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
                                                        <!-- Day 1 -->
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
                                                                                <h6 class="mb-0"><?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?> | <?= $location_name; ?></h6>
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
                                                                            <div class="d-flex align-items-center">
                                                                                <span class="d-flex">
                                                                                    <div class="form-group">
                                                                                        <?php if ($itinerary_route_counter == '1') : ?>
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
                                                                                <button type="button" class="btn btn-sm btn-primary waves-effect waves-light ms-2" onclick="add_itinerary_route_time(<?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $itinerary_route_counter; ?>, <?= $total_itinerary_route_count; ?>)">
                                                                                    <span class="ti ti-calendar-time me-2"></span> Update
                                                                                </button>
                                                                            </div>
                                                                            <?php if ($route_start_time != '' && $route_end_time != '') : ?>
                                                                                <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="show_add_HOTSPOTS(<?= $itinerary_route_counter; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, '<?= $location_name; ?>', '<?= $itinerary_route_date; ?>','<?= $next_visiting_location ?>','<?= $location_via_route ?>')"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize </button>
                                                                            <?php else : ?>
                                                                                <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="show_toast_message_for_time()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize </button>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <input type="hidden" name="hidden_location_name" id="hidden_location_name" value="<?= $location_name; ?>" hidden>
                                                                        <input type="hidden" name="hidden_itinerary_route_date" id="hidden_itinerary_route_date" value="<?= $itinerary_route_date; ?>" hidden>
                                                                        <span class="show_add_hotsopt_form_<?= $itinerary_route_counter; ?>" id="show_add_hotsopt_form"></span>
                                                                        <span class="show_available_hotspot_list_<?= $itinerary_route_counter; ?>" id="show_available_hotspot_list"></span>
                                                                        <span class="show_added_hotspot_response_<?= $itinerary_route_counter; ?>" id="show_added_hotspot_response">
                                                                            <?php
                                                                            if ($before_location_name == '') :
                                                                                $before_location_name = $location_name;
                                                                            else :
                                                                                if ($before_location_name == $location_name) : ?>
                                                                                    <div class="bs-toast toast fade show w-50 my-3 text-white border-0 mx-auto" role="alert" aria-live="assertive" aria-atomic="true" style="box-shadow: none !important; background-color: #c489e9 !important">
                                                                                        <div class="toast-body d-flex align-items-center text-white border-0" style="box-shadow: none !important; background-color: #c489e9 !important">
                                                                                            <i class="ti ti-bell ti-xs me-2 text-white"></i>
                                                                                            <div class="me-auto fw-medium">Day Trip is available</div>
                                                                                            <button type="button" class="btn-close btn-close-white text-white" data-bs-dismiss="toast" aria-label="Close"></button>
                                                                                        </div>
                                                                                    </div>
                                                                            <?php
                                                                                else :
                                                                                    $before_location_name = '';
                                                                                endif;
                                                                            endif;
                                                                            ?>

                                                                            <?php if ($guide_for_itinerary == '0') :
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

                                                                                        $guide_language = explode(',', $guide_language);
                                                                                        for ($i = 0; $i < count($guide_language); $i++) {
                                                                                            $name_guide_language .= ucfirst(getGUIDE_LANGUAGE_DETAILS($guide_language[$i], 'label'));
                                                                                            if ($i < (count($guide_language) - 1)) :
                                                                                                $name_guide_language .= ', ';
                                                                                            endif;
                                                                                        }

                                                                                        $guide_slot = explode(',', $guide_slot);
                                                                                        for ($j = 0; $j < count($guide_slot); $j++) {
                                                                                            $name_guide_slot .= ucfirst(getSLOTTYPE($guide_slot[$j], 'label'));
                                                                                            if ($j < (count($guide_slot) - 1)) :
                                                                                                $name_guide_slot .= ', ';
                                                                                            endif;
                                                                                        }
                                                                                    endwhile;
                                                                                endif;
                                                                            ?>
                                                                                <div class="mt-3">
                                                                                    <?php if ($total_itinerary_guide_route_count > 0) : ?>
                                                                                        <span id="edit_guide_modal_<?= $itinerary_route_counter; ?>" class="" style="color: #4d287b;">
                                                                                            Guide Language for <?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?> - <span class="text-primary" id="language_choosen_itinerary_<?= $itinerary_route_counter; ?>"><?= $name_guide_language; ?></span> and <span class="text-primary" id="slot_choosen_itinerary_<?= $itinerary_route_counter; ?>"><?= $name_guide_slot; ?></span>

                                                                                            <a href="javascript:void(0)" class="edit_guide_modal_link_<?= $itinerary_route_counter; ?>" onclick="showaddGUIDEADDFORMMODAL('<?= $route_guide_ID; ?>', '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>', '<?= $itinerary_route_counter; ?>')" style="color: #4d287b;">
                                                                                                <span class="ti-sm ti ti-edit mb-1"></span>
                                                                                            </a>
                                                                                        </span>
                                                                                    <?php else : ?>
                                                                                        <a href="javascript:void(0)" class="btn btn-label-github btn-sm mt-1" id="add_guide_modal_<?= $itinerary_route_counter; ?>" onclick="showaddGUIDEADDFORMMODAL(0, '2', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= date('F d, Y (l)', strtotime($itinerary_route_date)); ?>', '<?= $itinerary_route_counter; ?>');">
                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                                                        </a>
                                                                                        <span id="edit_guide_modal_<?= $itinerary_route_counter; ?>" class="d-none" style="color: #4d287b;">
                                                                                            Itinerary Guide Language - <span class="text-primary" id="language_choosen_itinerary_<?= $itinerary_route_counter; ?>">Tamil, English</span>
                                                                                            <a href="javascript:void(0)" class="edit_guide_modal_link_<?= $itinerary_route_counter; ?>" style="color: #4d287b;">
                                                                                                <span class="ti-sm ti ti-edit mb-1"></span>
                                                                                            </a>
                                                                                        </span>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                            <?php
                                                                            $select_route_hotspot_list_query = sqlQUERY_LABEL("SELECT ROUTE_HOTSPOT.`route_hotspot_ID`, ROUTE_HOTSPOT.`itinerary_plan_ID`, ROUTE_HOTSPOT.`itinerary_route_ID`, ROUTE_HOTSPOT.`hotspot_ID`,   ROUTE_HOTSPOT.`hotspot_entry_time_label`, ROUTE_HOTSPOT.`hotspot_amout`, ROUTE_HOTSPOT.`hotspot_traveling_time`, ROUTE_HOTSPOT.`hotspot_travelling_distance`, ROUTE_HOTSPOT.`hotspot_start_time`, ROUTE_HOTSPOT.`hotspot_end_time`, ROUTE_HOTSPOT.`hotspot_activity_skipping`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`, HOTSPOT_PLACE.`hotspot_address`, HOTSPOT_PLACE.`hotspot_operating_hours`, HOTSPOT_PLACE.`hotspot_photo_url`, HOTSPOT_PLACE.`hotspot_rating`,HOTSPOT_PLACE.`hotspot_duration` FROM `dvi_itinerary_route_hotspot_details` ROUTE_HOTSPOT LEFT JOIN `dvi_hotspot_place` HOTSPOT_PLACE ON ROUTE_HOTSPOT.`hotspot_ID`=HOTSPOT_PLACE.`hotspot_ID` WHERE ROUTE_HOTSPOT.`deleted` = '0' and ROUTE_HOTSPOT.`itinerary_plan_ID` = '$itinerary_plan_ID' and ROUTE_HOTSPOT.`itinerary_route_ID` = '$itinerary_route_ID' and ROUTE_HOTSPOT.`hotspot_entry_time_label` = '$dayOfWeekNumeric'") or die("#1-UNABLE_TO_COLLECT_LIST:" . sqlERROR_LABEL());
                                                                            $total_route_hotspot_list_num_rows_count = sqlNUMOFROW_LABEL($select_route_hotspot_list_query);
                                                                            ?>

                                                                            <ul class="timeline timeline_itinerary pt-3 px-3 mb-0 mt-3" id="response_for_the_added_hotspots">
                                                                                <li class="timeline-item timeline-item-transparent">
                                                                                    <span class="timeline-point timeline-point-success"></span>
                                                                                    <div class="timeline-event">
                                                                                        <div class="timeline-header mb-sm-0 mb-3">
                                                                                            <h6 class="mb-0"><?= getGLOBALSETTING('itinerary_break_time'); ?></h6>
                                                                                        </div>
                                                                                        <div class="timeline-event-time timeline-event-time-itinerary">
                                                                                            <?php
                                                                                            if ($itinerary_route_counter == '1') :
                                                                                                echo date('g:i A', strtotime($trip_start_date_and_time)) . ' To ' . date('g:i A', strtotime(date('h:i:s', strtotime($trip_start_date_and_time)) . '+' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                                                                                            else :
                                                                                                if ($route_start_time != '') :
                                                                                                    echo date('g:i A', strtotime($route_start_time)) . ' To ' . date('g:i A', strtotime($route_start_time  . '+' .
                                                                                                        date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min'));
                                                                                                else :
                                                                                                    echo '<b class="text-muted">Need to update time !</b>';
                                                                                                endif;
                                                                                            endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>

                                                                                <?php
                                                                                if ($total_route_hotspot_list_num_rows_count > 0) :
                                                                                    $counter = 0;
                                                                                    while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($select_route_hotspot_list_query)) :
                                                                                        $counter++;
                                                                                        $route_hotspot_ID = $fetch_route_hotspot_list_data['route_hotspot_ID'];
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
                                                                                        $hotspot_duration =
                                                                                            $fetch_route_hotspot_list_data['hotspot_duration'];
                                                                                ?>
                                                                                        <li class="timeline-item timeline-item-transparent">
                                                                                            <span class="timeline-indicator-advanced timeline-indicator-warning">
                                                                                                <i class="ti ti-road rounded-circle"></i>
                                                                                            </span>
                                                                                            <div class="timeline-event">
                                                                                                <div class="timeline-header mb-sm-0 mb-3">
                                                                                                    <?php
                                                                                                    $hours = date('H', strtotime($hotspot_traveling_time));
                                                                                                    $minutes = date('i', strtotime($hotspot_traveling_time));
                                                                                                    $seconds = date('s', strtotime($hotspot_traveling_time));

                                                                                                    $formattedDuration = '';

                                                                                                    if ($hours > 0) {
                                                                                                        $formattedDuration .= ltrim($hours, '0') . ' hour' . ($hours > 1 ? 's' : '');
                                                                                                    }

                                                                                                    if ($minutes > 0) {
                                                                                                        $formattedDuration .= ($formattedDuration ? ' ' : '') . ltrim($minutes, '0') . ' min' . ($minutes > 1 ? 's' : '');
                                                                                                    }

                                                                                                    if ($seconds > 0) {
                                                                                                        $formattedDuration .= ($formattedDuration ? ' ' : '') . ltrim($seconds, '0') . ' sec' . ($seconds > 1 ? 's' : '');
                                                                                                    }

                                                                                                    if (!$formattedDuration) {
                                                                                                        $formattedDuration = '0 mins';
                                                                                                    }
                                                                                                    ?>
                                                                                                    <h6 class="mb-0 text-warning">Travelling <span class="text-primary">distance <?= strtoupper($hotspot_travelling_distance); ?></span>, <span class="text-primary">estimated time <?= ucwords($formattedDuration); ?></span> and this may vary due to traffic conditions.</h6>
                                                                                                </div>
                                                                                                <div class="timeline-event-time timeline-event-time-itinerary"><?= date('g:i A', strtotime($hotspot_start_time . ' -' . $formattedDuration)); ?> To <?= date('g:i A', strtotime($hotspot_start_time)); ?></div>
                                                                                            </div>
                                                                                        </li>

                                                                                        <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                            <?php //endif; 
                                                                                            ?>
                                                                                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                <i class="ti ti-map-pin rounded-circle text-primary"></i>
                                                                                            </span>
                                                                                            <div class="timeline-event pb-3">
                                                                                                <div class="d-flex flex-sm-row flex-column align-items-center">
                                                                                                    <div>
                                                                                                        <img src="uploads/hotspot_gallery/<?= $hotspot_photo_url; ?>" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                    </div>
                                                                                                    <div class="w-100">
                                                                                                        <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                            <h6 class="mb-0 text-capitalize"><?= $hotspot_name; ?></h6>
                                                                                                            <button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_itinerary_route_hotspot_in_list(<?= $hotspot_ID; ?>, <?= $itinerary_route_ID; ?>, <?= $itinerary_plan_ID; ?>, <?= $dayOfWeekNumeric; ?>, '<?= $TYPE; ?>')"><span class="ti ti-trash"></span></button>
                                                                                                        </div>
                                                                                                        <p class="my-1"><i class="ti ti-map-pin me-1 mb-1"></i><?= $hotspot_address; ?></p>
                                                                                                        <p class="my-1">
                                                                                                            <i class="ti ti-clock-filled me-1 mb-1"></i>
                                                                                                            <?php
                                                                                                            if ($hotspot_operating_hours[$dayOfWeekNumeric] != '') :
                                                                                                                $pattern = '/:\s*(.+)/';
                                                                                                                if (preg_match($pattern, $hotspot_operating_hours[$dayOfWeekNumeric], $matches)) {
                                                                                                                    // Get the matched data
                                                                                                                    $dataAfterColon = trim($matches[1]);
                                                                                                                    echo $dataAfterColon;
                                                                                                                } else {
                                                                                                                    echo "Time slots not available";
                                                                                                                }
                                                                                                            else :
                                                                                                                echo 'Time slots not available';
                                                                                                            endif;
                                                                                                            ?>
                                                                                                        </p>

                                                                                                        <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                            <p class="mt-1 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                                <?php if ($hotspot_amout == '' || $hotspot_amout == '0') :
                                                                                                                    echo 'No Fare';
                                                                                                                else :
                                                                                                                    echo $hotspot_amout;
                                                                                                                endif; ?>
                                                                                                            </p>
                                                                                                            <h6 class="text-primary mb-0">
                                                                                                                <?php for ($rate_count = 1; $rate_count <= round($hotspot_rating); $rate_count++) : ?>
                                                                                                                    <i class="ti ti-star-filled"></i>
                                                                                                                <?php endfor; ?>
                                                                                                            </h6>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <?php
                                                                                                if ($hotspot_description != '') : ?>
                                                                                                    <p class="mt-2" style="text-align: justify;">
                                                                                                        <?= $hotspot_description; ?>
                                                                                                    </p>
                                                                                                <?php endif; ?>

                                                                                                <div class="col-12 text-center">
                                                                                                    <?php
                                                                                                    $select_activity_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_ID`='$hotspot_ID' and ACTIVITY_TIME_SLOT.`status` and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date`='$itinerary_route_date'") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                                                                                                    $activity_count = sqlNUMOFROW_LABEL($select_activity_query);

                                                                                                    if ($activity_count == 0) :
                                                                                                        $select_activity_query = sqlQUERY_LABEL("SELECT ACTIVITY.`activity_id`, ACTIVITY.`activity_title`, ACTIVITY.`hotspot_id`, ACTIVITY_TIME_SLOT.`time_slot_type`, ACTIVITY_TIME_SLOT.`special_date`,  ACTIVITY_TIME_SLOT.`start_time`, ACTIVITY_TIME_SLOT.`end_time` FROM `dvi_activity` AS ACTIVITY LEFT JOIN `dvi_activity_time_slot_details` AS ACTIVITY_TIME_SLOT ON ACTIVITY.`activity_id`=ACTIVITY_TIME_SLOT.`activity_id` WHERE ACTIVITY.`status` and ACTIVITY.`deleted` = '0' and ACTIVITY.`hotspot_ID`='$hotspot_ID' and ACTIVITY_TIME_SLOT.`status` and ACTIVITY_TIME_SLOT.`deleted` = '0' and ACTIVITY_TIME_SLOT.`start_time` <= '$hotspot_start_time' AND ACTIVITY_TIME_SLOT.`special_date` IS NULL") or die("#1-UNABLE_TO_ITINERARY_PLAN:" . sqlERROR_LABEL());
                                                                                                        $activity_count = sqlNUMOFROW_LABEL($select_activity_query);
                                                                                                    endif;

                                                                                                    if ($activity_count > 0) :
                                                                                                    ?>
                                                                                                        <button type="button" class="btn btn-primary" onclick="showACTIVITYMODAL('<?= $itinerary_route_counter; ?>', '<?= $itinerary_plan_ID; ?>', '<?= $itinerary_route_ID; ?>', '<?= $route_hotspot_ID; ?>', '<?= $hotspot_ID; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $hotspot_start_time; ?>', '<?= $hotspot_end_time; ?>')">Add Activities </button>
                                                                                                    <?php endif; ?>
                                                                                                </div>
                                                                                                <div class="timeline-event-time timeline-event-time-itinerary"><?= date('g:i A', strtotime($hotspot_start_time)); ?> To <?= date('g:i A', strtotime($hotspot_end_time)); ?></div>
                                                                                            </div>

                                                                                            <?php
                                                                                            $selected_query = sqlQUERY_LABEL("SELECT ROUTE_ACTIVITY.`route_activity_ID`, ROUTE_ACTIVITY.`itinerary_plan_ID`, ROUTE_ACTIVITY.`itinerary_route_ID`, ROUTE_ACTIVITY.`hotspot_ID`, ROUTE_ACTIVITY.`route_hotspot_ID`, ROUTE_ACTIVITY.`activity_ID`, ROUTE_ACTIVITY.`activity_entry_time_label`, ROUTE_ACTIVITY.`activity_amout`, ROUTE_ACTIVITY.`activity_start_time`, ROUTE_ACTIVITY.`activity_end_time`, ACTIVITY.activity_id, ACTIVITY.activity_title, ACTIVITY.activity_description, ACTIVITY.hotspot_id, ACTIVITY.max_allowed_person_count FROM `dvi_itinerary_route_activity_details` ROUTE_ACTIVITY LEFT JOIN `dvi_activity` ACTIVITY ON ACTIVITY.activity_ID=ROUTE_ACTIVITY.activity_ID WHERE ROUTE_ACTIVITY.`itinerary_plan_ID`='$itinerary_plan_ID' AND ROUTE_ACTIVITY.`itinerary_route_ID`='$itinerary_route_ID' AND ROUTE_ACTIVITY.`hotspot_ID`='$hotspot_ID' AND ROUTE_ACTIVITY.`status`='1' AND ROUTE_ACTIVITY.`deleted`='0'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                                                                            $activity_route_count = sqlNUMOFROW_LABEL($selected_query);

                                                                                            if ($activity_route_count > 0) :
                                                                                            ?>
                                                                                                <!-- Activities -->
                                                                                                <div class="row">
                                                                                                    <div class="col-12">
                                                                                                        <ul class="timeline timeline-center mt-1">
                                                                                                            <?php
                                                                                                            while ($fetch_route_hotspot_list_data = sqlFETCHARRAY_LABEL($selected_query)) :

                                                                                                                $activity_id = $fetch_route_hotspot_list_data['activity_id'];
                                                                                                                $activity_title = $fetch_route_hotspot_list_data['activity_title'];
                                                                                                                $activity_description = $fetch_route_hotspot_list_data['activity_description'];
                                                                                                                $max_allowed_person_count = $fetch_route_hotspot_list_data['max_allowed_person_count'];
                                                                                                                $route_activity_start_time = date('g:i A', strtotime($fetch_route_hotspot_list_data['activity_start_time']));
                                                                                                                $route_activity_end_time = date('g:i A', strtotime($fetch_route_hotspot_list_data['activity_end_time']));
                                                                                                                $activity_hotspot_location = getHOTSPOTDETAILS($fetch_route_hotspot_list_data['hotspot_ID'], 'label');

                                                                                                                $selected_activity_gallery_query = sqlQUERY_LABEL("SELECT `activity_image_gallery_details_id`,`activity_image_gallery_name` FROM `dvi_activity_image_gallery_details` WHERE `activity_id`='$activity_id' AND `status`='1' AND `deleted`='0' ORDER BY `activity_id` ASC") or die("#ACTIVITY_GALLERY-LABEL: SELECT_ACTIVITY_GALLERY_LABEL: " . sqlERROR_LABEL());
                                                                                                                while ($fetch_activity_gallery_list_data = sqlFETCHARRAY_LABEL($selected_activity_gallery_query)) :
                                                                                                                    $activity_image_gallery_details_id = $fetch_activity_gallery_list_data['activity_image_gallery_details_id'];
                                                                                                                    $activity_image_gallery_name = $fetch_activity_gallery_list_data['activity_image_gallery_name'];
                                                                                                                endwhile;
                                                                                                            ?>
                                                                                                                <li class="timeline-item timeline-item-activities">
                                                                                                                    <span class="timeline-indicator timeline-indicator-primary">
                                                                                                                        <i class="ti ti-trekking ti-sm"></i>
                                                                                                                    </span>
                                                                                                                    <div class="timeline-event timeline-event-activities pb-3 py-2 px-3">
                                                                                                                        <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                            <h6 class="mb-0 text-capitalize"><b><?= $activity_title; ?></b></h6>
                                                                                                                            <button type="button" class="btn btn-icon btn-sm btn-outline-danger waves-effect border-0" href="javascript:void(0);" onclick="remove_ITINEARY_ROUTE_ACTIVITY('<?= $route_hotspot_ID; ?>', '<?= $activity_id; ?>', '<?= $dayOfWeekNumeric; ?>', '<?= $itinerary_route_counter; ?>', '<?= $itinerary_route_date; ?>', '<?= $hotspot_ID; ?>')"><span class="ti ti-trash"></span></button>
                                                                                                                        </div>
                                                                                                                        <div class="d-flex flex-sm-row flex-column align-items-center">
                                                                                                                            <img src="uploads/activity_gallery/<?= $activity_image_gallery_name; ?>" class="rounded me-3" alt="Show img" height="80" width="80" />
                                                                                                                            <div class="w-100">
                                                                                                                                <p class="my-1"><i class="ti ti-clock-filled me-1 mb-1"></i><span class="mt-2">
                                                                                                                                        <?php
                                                                                                                                        $selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$hotspot_start_time' and `special_date`='$itinerary_route_date'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                                                                                                                        $activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);

                                                                                                                                        if ($activity_time_slot_query == '0') :
                                                                                                                                            $selected_time_slot_query = sqlQUERY_LABEL("SELECT `special_date`, `start_time`, `end_time` FROM `dvi_activity_time_slot_details` WHERE `activity_id`='$activity_id' AND `status` and `deleted` = '0' and `start_time` <= '$hotspot_start_time' and `special_date` IS NULL") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                                                                                                                            $activity_time_slot_query = sqlNUMOFROW_LABEL($selected_time_slot_query);
                                                                                                                                        endif;
                                                                                                                                        if ($activity_time_slot_query > 0) :
                                                                                                                                            while ($fetch_time_slot_list_data = sqlFETCHARRAY_LABEL($selected_time_slot_query)) :
                                                                                                                                                $counter_time_slot++;
                                                                                                                                                $special_date = $fetch_time_slot_list_data['special_date'];

                                                                                                                                                $activity_start_time = date('H:i A', strtotime($fetch_time_slot_list_data['start_time']));
                                                                                                                                                $activity_end_time = date('H:i A', strtotime($fetch_time_slot_list_data['end_time']));
                                                                                                                                                if ($counter_time_slot == $activity_time_slot_query) :
                                                                                                                                                    echo $activity_start_time . ' to ' . $activity_end_time;
                                                                                                                                                else :
                                                                                                                                                    echo $activity_start_time . ' to ' . $activity_end_time . ', ';
                                                                                                                                                endif;
                                                                                                                                            endwhile;
                                                                                                                                        endif; ?>
                                                                                                                                    </span></p>
                                                                                                                                <p class="my-1"><i class="ti ti-users-group me-1 mb-1"></i><span class="mt-2">Maximum <?= $max_allowed_person_count; ?> Persons Allowed</span></p>
                                                                                                                                <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                                    <p class="mb-0"><i class="ti ti-ticket me-1 ti-sm"></i>
                                                                                                                                        <?php
                                                                                                                                        $month = date('F', strtotime($itinerary_route_date));
                                                                                                                                        $year = date('Y', strtotime($itinerary_route_date));
                                                                                                                                        $date = 'day_' . date('d', strtotime($itinerary_route_date));

                                                                                                                                        $selected_pricebook_query = sqlQUERY_LABEL("SELECT `year`, `month`, `price_type`, `$date` FROM `dvi_activity_pricebook` WHERE `activity_id`='$activity_id' AND `status`='1' and `deleted` = '0' AND `year`='$year' AND `month`='$month'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                                                                                                                        $activity_pricebook_query = sqlNUMOFROW_LABEL($selected_pricebook_query);

                                                                                                                                        if ($activity_pricebook_query > 0) :
                                                                                                                                            while ($fetch_pricebook_list_data = sqlFETCHARRAY_LABEL($selected_pricebook_query)) :
                                                                                                                                                $price_type = $fetch_pricebook_list_data['price_type'];
                                                                                                                                                $price = $fetch_pricebook_list_data[$date];
                                                                                                                                                if ($price_type == '1') :
                                                                                                                                                    echo 'Adult: ' . $price;
                                                                                                                                                    $adult_present = true;
                                                                                                                                                elseif ($price_type == '2') :
                                                                                                                                                    if ($adult_present == true) :
                                                                                                                                                        echo ', Child: ' . $price;
                                                                                                                                                    else :
                                                                                                                                                        echo 'Child: ' . $price;
                                                                                                                                                    endif;
                                                                                                                                                    $child_present = true;
                                                                                                                                                elseif ($price_type == '3') :
                                                                                                                                                    if ($child_present == true) :
                                                                                                                                                        echo ', Infant: ' . $price;
                                                                                                                                                    else :
                                                                                                                                                        echo 'Infant: ' . $price;
                                                                                                                                                    endif;
                                                                                                                                                endif;
                                                                                                                                            endwhile;
                                                                                                                                        else :
                                                                                                                                            echo 'No Fare';
                                                                                                                                        endif; ?>
                                                                                                                                    </p>
                                                                                                                                    <h6 class="text-primary mb-0">
                                                                                                                                        <?php
                                                                                                                                        $selected_review_query = sqlQUERY_LABEL("SELECT `activity_rating` FROM `dvi_activity_review_details` WHERE `activity_id`='$activity_id' AND `status`='1' AND `deleted`='0'") or die("#BRANCHLABEL-LABEL: SELECT_BRANCH_LABEL: " . sqlERROR_LABEL());
                                                                                                                                        $activity_review_query = sqlNUMOFROW_LABEL($selected_review_query);

                                                                                                                                        if ($activity_review_query > 0) :
                                                                                                                                            while ($fetch_review_list_data = sqlFETCHARRAY_LABEL($selected_review_query)) :
                                                                                                                                                $totalRating += $fetch_review_list_data['activity_rating'];
                                                                                                                                            endwhile;
                                                                                                                                            $_rating = ($activity_review_query > 0) ? $totalRating / $activity_review_query : 0;
                                                                                                                                        endif;
                                                                                                                                        for ($rate_count = 1; $rate_count <= round($_rating); $rate_count++) : ?>
                                                                                                                                            <i class="ti ti-star-filled"></i>
                                                                                                                                        <?php endfor; ?>
                                                                                                                                    </h6>
                                                                                                                                    </h6>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        <?php if ($activity_description != '') : ?>
                                                                                                                            <p class="mt-2 mb-0" style="text-align: justify;">
                                                                                                                                <?= $activity_description; ?>
                                                                                                                            </p>
                                                                                                                        <?php endif; ?>
                                                                                                                        <div class="timeline-event-time timeline-event-time-activities"><?= $route_activity_start_time; ?> To <?= $route_activity_end_time; ?></div>
                                                                                                                    </div>
                                                                                                                </li>
                                                                                                            <?php endwhile; ?>
                                                                                                        </ul>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- Activities -->
                                                                                            <?php endif; ?>
                                                                                        </li>
                                                                                    <?php endwhile; ?>
                                                                                <?php else : ?>
                                                                                    <li class="timeline-item timeline-item-transparent">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-warning">
                                                                                            <i class="ti ti-bell rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event">
                                                                                            <div class="timeline-header mb-sm-0 mb-3">
                                                                                                <div class="d-flex align-items-center">
                                                                                                    <h6 class="mb-0 text-warning mt-1"><?= getGLOBALSETTING('custom_hotspot_or_activity'); ?>
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="timeline-event-time timeline-event-time-itinerary"></div>
                                                                                        </div>
                                                                                    </li>
                                                                                <?php endif; ?>

                                                                                <li class="timeline-item timeline-item-transparent border-transparent">
                                                                                    <span class="timeline-point timeline-point-success"></span>
                                                                                    <div class="timeline-event">
                                                                                        <div class="timeline-header mb-sm-0 mb-3">
                                                                                            <h6 class="mb-0"><?= getGLOBALSETTING('accommodation_return'); ?></h6>
                                                                                        </div>
                                                                                        <div class="timeline-event-time timeline-event-time-itinerary">
                                                                                            <?php
                                                                                            if ($route_end_time != '') :
                                                                                                $select_itinerary_route_hotspot_details = sqlQUERY_LABEL("SELECT `route_hotspot_ID`, `hotspot_end_time` FROM `dvi_itinerary_route_hotspot_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID' ORDER BY `route_hotspot_ID` ASC") or die("#1-UNABLE_TO_COLLECT_ROUTE_HOTSPOT_LIST:" . sqlERROR_LABEL());
                                                                                                $total_itinerary_route_hotspot_count = sqlNUMOFROW_LABEL($select_itinerary_route_hotspot_details);
                                                                                                while ($fetch_itinerary_route_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_route_hotspot_details)) :
                                                                                                    $hotspot_end_time = $fetch_itinerary_route_data['hotspot_end_time'];
                                                                                                endwhile;
                                                                                                if ($total_itinerary_route_hotspot_count == 0) :
                                                                                                    $route_end_time_str = strtotime($route_end_time);

                                                                                                    // Set the end time to 11:59 PM
                                                                                                    $route_max_end_time_str = strtotime("11:59 PM");

                                                                                                    // Calculate the difference in seconds
                                                                                                    $time_difference = $route_max_end_time_str - $route_end_time_str;

                                                                                                    // If more than 1 hour remaining until 11:59 PM, adjust the end time
                                                                                                    if ($time_difference > 3600) : // 3600 seconds = 1 hour
                                                                                                        $end_time = $route_end_time_str + 3600; // Add 1 hour
                                                                                                    else :
                                                                                                        $end_time = $route_max_end_time_str; // No adjustment needed
                                                                                                    endif;

                                                                                                    // Format the times for display
                                                                                                    $start_time_formatted = date("g:i A", $route_end_time_str);
                                                                                                    $end_time_formatted = date("g:i A", $end_time);

                                                                                                    echo $start_time_formatted . ' To ' . $end_time_formatted;

                                                                                                else :
                                                                                                    echo date('g:i A', strtotime($route_end_time . ' -' . date('g', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . ' hour' . date('i', strtotime(getGLOBALSETTING('itinerary_common_buffer_time'))) . 'min')) . ' To ' . date('g:i A', strtotime($route_end_time));
                                                                                                endif;
                                                                                            else :
                                                                                                echo '<b class="text-muted">Need to update time !</b>';
                                                                                            endif;
                                                                                            ?>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </ul>
                                                                        </span>
                                                                        <span class="show_loader_response_<?= $itinerary_route_counter; ?>" id="show_loader_response"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php endwhile;
                                                endif; ?>

                                                <div id="hotel_list">
                                                </div>

                                                <div class="card border border-primary mt-4">
                                                    <div class="d-flex align-items-center justify-content-between  px-3 py-2 pt-3">
                                                        <h5 class="card-header p-0">Vehicle List</h5>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex p-3">
                                                            <span class="mb-0 me-4"><strong>Total Passengers</strong><span class="badge badge-center bg-primary bg-glow mx-2">6</span></span>
                                                            <span class="mb-0 me-4"><strong>Total vehicle</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                        </div>
                                                        <div class="mb-0 me-3 p-3 pe-0"><strong>Total Amount For vehicle</strong><span class="badge bg-primary bg-glow ms-2">₹10,000</span></div>
                                                    </div>
                                                    <div id="vehicle_preview_table_div">
                                                        <div class="table-responsive text-nowrap">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Travel Date</th>
                                                                        <th>Travel Places</th>
                                                                        <th>Distance (Kms)</th>
                                                                        <th>Sight-seeing distance (Kms)</th>
                                                                        <th>Total Distance (Kms)</th>
                                                                        <th>Time</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="table-border-bottom-0">
                                                                    <tr>
                                                                        <td>
                                                                            October 14, 2023
                                                                        </td>
                                                                        <td>Chennai Local</td>
                                                                        <td>0</td>
                                                                        <td>30</td>
                                                                        <td>30</td>
                                                                        <td>2 hours 30 minutes</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>October 15, 2023
                                                                        </td>
                                                                        <td>Chennai to
                                                                            <br />
                                                                            Tanjore
                                                                        </td>
                                                                        <td>345</td>
                                                                        <td>25</td>
                                                                        <td>370</td>
                                                                        <td>8 hours 30 minutes</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>October 17, 2023
                                                                        </td>
                                                                        <td>Tanjore to
                                                                            <br />
                                                                            Trichy
                                                                        </td>
                                                                        <td>60</td>
                                                                        <td>10</td>
                                                                        <td>70</td>
                                                                        <td>5 hours 00 minutes</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>October 18, 2023
                                                                        </td>
                                                                        <td>Trichy to
                                                                            <br />
                                                                            Madurai
                                                                        </td>
                                                                        <td>132</td>
                                                                        <td>28</td>
                                                                        <td>160</td>
                                                                        <td>N/A</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>October 19, 2023
                                                                        </td>
                                                                        <td>Madurai to
                                                                            <br />
                                                                            Kanyakumari
                                                                        </td>
                                                                        <td>244</td>
                                                                        <td>46</td>
                                                                        <td>290</td>
                                                                        <td>N/A</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>October 20, 2023
                                                                        </td>
                                                                        <td>Kanyakumari to
                                                                            <br />
                                                                            Trivandrum
                                                                        </td>
                                                                        <td>95</td>
                                                                        <td>120</td>
                                                                        <td>215</td>
                                                                        <td>N/A</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <div class="card p-3">
                                                            <h5 class="card-header p-0 mb-2">Vehicle Details</h5>
                                                            <div class="order-calculations">
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span class="text-heading">Indigo * 2</span>
                                                                    <h6 class="mb-0">₹2,760</h6>
                                                                </div>
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span class="text-heading fw-bold">Total Vehicle Cost</span>
                                                                    <h6 class="mb-0 fw-bold">₹2,760</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="card p-3">
                                                            <h5 class="card-header p-0 mb-2">Overall Cost</h5>
                                                            <div class="order-calculations">
                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span class="text-heading">Gross Total for The Package</span>
                                                                    <h6 class="mb-0">₹1,37,304</h6>
                                                                </div>

                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span class="text-heading">GST @ 5 % On The total Package </span>
                                                                    <h6 class="mb-0">₹6,865</h6>
                                                                </div>

                                                                <div class="d-flex justify-content-between mb-2">
                                                                    <span class="text-heading fw-bold">Nett Payable To Doview Holidays India Pvt ltd</span>
                                                                    <h6 class="mb-0 fw-bold">₹1,44,169</h6>
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
                                                        <button type="button" class="btn rounded-pill btn-success waves-effect waves-light">
                                                            <i class="tf-icons ti ti-brand-whatsapp ti-xs me-1"></i> Share Via Whatsapp
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
            $(document).ready(function() {

                showHOTELLIST();

                $(".form-select").selectize();
                $("#guide_language").attr('required', true);
                $("#guide_slot").attr('required', true);

                <?php
                $select_itinerary_route_details_form = sqlQUERY_LABEL("SELECT `itinerary_route_ID` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_itinerary_route_count_form = sqlNUMOFROW_LABEL($select_itinerary_route_details_form);
                if ($total_itinerary_route_count_form > 0) :
                    while ($fetch_itinerary_route_data_form = sqlFETCHARRAY_LABEL($select_itinerary_route_details_form)) :
                        $itinerary_route_counter_form++;
                ?>
                        $("#form_itinerary_guide_<?= $itinerary_route_counter_form; ?>").submit(function(event) {
                            var form = $('#form_itinerary_guide_<?= $itinerary_route_counter_form; ?>')[0];
                            var data = new FormData(form);
                            // $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
                            $.ajax({
                                type: "post",
                                url: 'engine/ajax/__ajax_manage_newitinerary.php?type=guide_for_itinerary',
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
                                    if (response.errros.guide_language_required) {
                                        TOAST_NOTIFICATION('warning', 'Guide Language Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                    } else if (response.errros.guide_slot_required) {
                                        TOAST_NOTIFICATION('warning', 'Guide Slot Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                    } else if (response.errros.itinerary_plan_ID_required) {
                                        TOAST_NOTIFICATION('warning', 'Itinerary Plan ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                    } else if (response.errros.itinerary_route_ID_required) {
                                        TOAST_NOTIFICATION('warning', 'Itinerary Route ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                    } else if (response.errros.guide_type_required) {
                                        TOAST_NOTIFICATION('warning', 'Guide Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                    }
                                } else {
                                    //SUCCESS RESPOSNE
                                    if (response.i_result == true) {
                                        //RESULT SUCCESS
                                        TOAST_NOTIFICATION('success', 'Itinerary Guide Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                        if (response.itinerary_route_ID != '') {
                                            $(".hidden_route_guide_ID_" + response.itinerary_route_ID).val(response.itinerary_route_guide_id);
                                        }
                                        //location.assign(response.redirect_URL);
                                    } else if (response.u_result == false) {
                                        //RESULT FAILED
                                        TOAST_NOTIFICATION('success', 'Unable to Create Itinerary Guide', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                    } else if (response.u_result == true) {
                                        //RESULT SUCCESS
                                        TOAST_NOTIFICATION('success', 'Itinerary Guide Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                        //location.assign(response.redirect_URL);
                                    } else if (response.u_result == false) {
                                        //RESULT FAILED
                                        TOAST_NOTIFICATION('success', 'Unable to Update Itinerary Guide', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
                <?php endwhile;
                endif; ?>

                    "use strict";
                !(function() {
                    var i1 = document.getElementById("response_for_the_added_hotspots");
                    i1 && Sortable.create(i1, {
                        animation: 150,
                        group: "taskList"
                    });
                })();
                <?php
                $select_itinerary_route_details = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_name`, `itinerary_route_date`, `location_latitude`, `location_longtitude`, `no_of_days`, `no_of_km`, `location_via_route`, `via_route_latitude`, `via_route_longtitude` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `status` = '1' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_itinerary_route_count = sqlNUMOFROW_LABEL($select_itinerary_route_details);
                if ($total_itinerary_route_count > 0) :
                    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_details)) :
                        $itinerary_route_counter_flatpickr++;

                        if ($itinerary_route_counter_flatpickr > '1') : ?>
                            flatpickr('#hotspot_start_time_<?= $itinerary_route_counter_flatpickr; ?>', {
                                enableTime: true,
                                noCalendar: true,
                                dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                                time_24hr: false,
                            });
                        <?php endif; ?>

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

            <?php
            if ($itinerary_plan_ID != '' && $itinerary_plan_ID != 0) :
                $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `arrival_latitude`, `arrival_longitude`, `departure_latitude`, `departure_longitude`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $total_hotel_list_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_list_query);
                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                    $expecting_budget = $fetch_list_data['expecting_budget'];
                    $no_of_days = $fetch_list_data["no_of_days"];
                    $no_of_nights = $fetch_list_data['no_of_nights'];
                endwhile;

                $location_dataArray = array();
                $itinerary_route_date_dataArray = array();
                $no_of_days_dataArray = array();

                $select_itinerary_route_list_query = sqlQUERY_LABEL("SELECT `location_name`, `itinerary_route_date`, `no_of_days` FROM `dvi_itinerary_route_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                while ($fetch_itinerary_route_list_data = sqlFETCHARRAY_LABEL($select_itinerary_route_list_query)) :
                    $location_name = $fetch_itinerary_route_list_data['location_name'];
                    array_push($location_dataArray, $location_name);

                    $itinerary_route_date = $fetch_itinerary_route_list_data['itinerary_route_date'];
                    array_push($itinerary_route_date_dataArray, $itinerary_route_date);
                endwhile;

                //$check_availablity = calculateAndCheckAvailability($expecting_budget, $no_of_days, $no_of_nights, $location_dataArray, $itinerary_route_date_dataArray);

                if ($check_availablity['result'] == 'true') :
                    if ($check_availablity['hotelAvailable'] == 'false' && $check_availablity['vehicleAvailable'] == 'false') : ?>
                        alertBudgetItinerary("<?= $check_availablity['hotelAvailable']; ?>", "<?= $check_availablity['vehicleAvailable'] ?>");
                    <?php elseif ($check_availablity['hotelAvailable'] == 'false') : ?>
                        alertBudgetItinerary("<?= $check_availablity['hotelAvailable']; ?>", '');
                    <?php elseif ($check_availablity['vehicleAvailable'] == 'false') : ?>
                        alertBudgetItinerary('', "<?= $check_availablity['vehicleAvailable']; ?>");
                    <?php
                    endif;
                    $hotspot_amount = $check_availablity['hotspotAmount']; ?>
                    document.getElementById('hotspot_amount').value = <?= $hotspot_amount; ?>
            <?php
                endif;
            endif;
            ?>

            function add_itinerary_route_time(itinerary_route_ID, itinerary_plan_ID, itinerary_route_counter, total_itinerary_route_count) {
                var hotspot_start_time = $('#hotspot_start_time_' + itinerary_route_counter).val();
                var hotspot_end_time = $('#hotspot_end_time_' + itinerary_route_counter).val();

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_manage_newitinerary.php?type=hotspot_route_time',
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
                                TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_plan_ID_required) {
                                TOAST_NOTIFICATION('warning', 'Itinerary Plan is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.itinerary_route_counter_required) {
                                TOAST_NOTIFICATION('warning', 'Itinerary Route Counter is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_start_time_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot Start Time is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_end_time_required) {
                                TOAST_NOTIFICATION('warning', 'Hotspot End Time is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_start_time_exceed) {
                                TOAST_NOTIFICATION('warning', 'Start time should not greater than end time', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_end_time_exceed) {
                                TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_start_and_end_time_exceed) {
                                TOAST_NOTIFICATION('warning', 'End time should not below start time', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotspot_minimum_hotspot_ending_hour_required) {
                                TOAST_NOTIFICATION('warning', 'End time should be +1 Hour from the Start Time', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            // SUCCESS RESPOSNE
                            if (response.u_result == true) {
                                TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

                                show_ITINERARY_FORM_STEP3('<?= $TYPE; ?>', <?= $itinerary_plan_ID; ?>);
                            } else if (response.u_result == false) {
                                // RESULT FAILED
                                TOAST_NOTIFICATION('warning', 'Unable to Update', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

            function show_toast_message_for_time() {
                TOAST_NOTIFICATION('warning', 'Kindly update start and end time', 'Warning !!!', '', '', '', '', '', '', '', '', '');
            }

            function checkHOTELAVILABILITY(itinerary_route_ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_nearset_available_hotel_details.php?type=show_form',
                    data: {
                        itinerary_route_ID: itinerary_route_ID,
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#hotel_name_edit_' + itinerary_route_ID).html();
                        $('#hotel_roomtype_edit_' + itinerary_route_ID).html();
                    }
                });
            }

            function show_add_HOTSPOTS(itinerary_count, itinerary_route_ID, itinerary_plan_ID, hidden_location_name, hidden_itinerary_route_date, next_visiting_location_name, via_route_location) {
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

            function alertBudgetItinerary(hotelAvailable, vehicleAvailable) {
                $('.receiving-itinerary-alert-data').load('engine/ajax/__ajax_generate_itinerary_plan.php?type=show_alert&HOTEL_AVAILABLE=' + hotelAvailable + '&VEHICLE_AVAILABLE=' + vehicleAvailable + '', function() {
                    const container = document.getElementById("itineraryALERT");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showaddGUIDEADDFORMMODAL(GUIDE_ID, GUIDE_TYPE, itinerary_plan_ID, itinerary_route_ID, date) {
                $('#addGUIDEADDFORM').modal('hide');
                $('.receiving-guide-add-form-data').load('engine/ajax/__ajax_modal_guide_form.php?type=guide_form_for_itinerary&GUIDE_ID=' + GUIDE_ID + '&GUIDE_TYPE=' + GUIDE_TYPE + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '', function() {
                    const container = document.getElementById("addGUIDEADDFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                    if (GUIDE_ID) {
                        if (GUIDE_TYPE == '1') {
                            $('#GUIDEFORMLabel').html('Update Guide For Itinerary');
                        } else {
                            $('#GUIDEFORMLabel').html('Update Guide ' + date);
                        }
                    } else {
                        if (GUIDE_TYPE == '1') {
                            $('#GUIDEFORMLabel').html('Add Guide For Itinerary');
                        } else {
                            $('#GUIDEFORMLabel').html('Add Guide ' + date);
                        }
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'show_alert') :

        $HOTEL_AVAILABLE = $_GET['HOTEL_AVAILABLE'];
        $VEHICLE_AVAILABLE = $_GET['VEHICLE_AVAILABLE'];
    ?>
        <div class="row g-3">
            <div class="col-12">
                <div class="text-center">
                    <?php if ($HOTEL_AVAILABLE == 'false' && $VEHICLE_AVAILABLE == 'false') :
                        echo 'Hotel and Vehicle is not available';
                    elseif ($HOTEL_AVAILABLE == 'false') :
                        echo 'Hotel is not available';
                    elseif ($VEHICLE_AVAILABLE == 'false') :
                        echo 'Vehicle is not available';
                    endif; ?>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-label-github waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="add_hotspot_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </div>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
