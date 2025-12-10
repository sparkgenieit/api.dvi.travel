<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*/

include_once('../../jackus.php');

/* ini_set('display_errors', 1);
ini_set('log_errors', 1); */

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_ID = $_POST['_itinerary_plan_ID'];
        $_group_type = $_POST['_group_type'];

        $itinerary_no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
        $itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');

        $hotel_rates_visibility = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'hotel_rates_visibility');
?>
        <style>
            #loader-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .hotelIcon {
                color: #7367f0;
            }

            .hotel-list-nav .nav-link .badge {
                background-color: #e4e4e4 !important;
                color: #4b4b4b !important;
            }

            .hotel-list-nav .nav-link.active .badge,
            .hotel-list-nav .nav-link:hover .badge {
                background-color: #eae8fd !important;
                color: #7367f0 !important;
            }

            .nav-tabs .nav-link.active,
            .nav-tabs .nav-link.active:hover,
            .nav-tabs .nav-link.active:focus,
            .nav-tabs .nav-link.active,
            .nav-tabs .nav-link.active:hover,
            .nav-tabs .nav-link.active:focus {
                box-shadow: none;
                color: #fff;
                background: linear-gradient(to right, rgba(114, 49, 207, 0.8) 0%, rgba(195, 60, 166, 0.8) 68%, rgba(238, 63, 206, 0.8) 100%) !important;
                border: none !important;
            }

            .hotel-list-nav .nav-link:not(.active):hover,
            .hotel-list-nav .nav-link:not(.active):focus,
            .nav-pills .nav-link:not(.active):hover,
            .nav-pills .nav-link:not(.active):focus {
                color: #aa008e !important;
            }

            .nav-item {
                position: relative;
            }

            .nav-item .nav-link.active .arrow::before {
                top: 0;
                border-width: .4rem .4rem 0;
                border-top-color: #bf61c1 !important;
            }

            .nav-item .nav-link.active .arrow::before {
                position: absolute;
                content: "";
                border-color: transparent;
                border-style: solid;
            }

            .nav-item .nav-link.active .arrow.active {
                position: absolute;
                display: block;
                width: .8rem;
                height: .4rem;
                left: 50%;
                bottom: -7px;
            }

            .bs-tooltip-auto[x-placement^=top] .arrow.active,
            .bs-tooltip-top .arrow.active {
                bottom: 0;
            }

            .image-overview {
                position: absolute;
                top: 50%;
                left: 50%;
                width: 100%;
                height: 100%;
                transform: translate(-50%, -50%);
                background: rgba(0, 0, 0, 0.5);
                color: #fff;
                padding: 5px;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }

            .image-overview i {
                color: #fff;
                font-size: 34px;
            }

            .mealSelectionOption label {
                font-size: 0.8125rem;
            }

            .overlay_image_wrapper {
                position: absolute;
                color: #fff;
                background-image: linear-gradient(to bottom, rgba(255, 0, 0, 0), rgba(0, 0, 0, 1));
                display: flex;
                align-items: flex-start;
                justify-content: center;
                flex-direction: column;
                left: 0;
                bottom: 0;
                width: 100%;
                padding: 0.75rem;
                padding-bottom: .3rem;
                padding-top: 2rem;
                text-wrap: wrap;
            }

            .overlay_image_wrapper h6 {
                color: #fff;
            }

            .input-group-room-type {
                border: 1px solid #dbdade;
            }

            .input-group-room-type h6 {
                font-size: 13px;
            }

            .input-group-room-type .form-select:focus,
            .input-group-room-type .form-select:focus-visible,
            .input-group-room-type .selectize-input {
                border: none !important;
            }

            .input-group-room-type,
            .input-group-room-type input,
            .input-group-room-type .form-select .selectize-input {
                border: 1px solid #dbdade;
                font-size: 13px;
            }

            .input-group-room-type:focus-within {
                box-shadow: none;
            }

            .tooltip-inner {
                max-width: auto;
                width: auto;
                background-color: #fff5fd;
                color: #6f6b7d;
                border-radius: 8px;
                padding: 10px;
                border: #6f6b7d 1px solid;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.12), 0 2px 2px rgba(0, 0, 0, 0.12);
            }

            .grand_total_section p {
                background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));
                -webkit-background-clip: text;
                /* Chrome, Safari, and Opera */
                -webkit-text-fill-color: transparent;
                /* Chrome, Safari, and Opera */
                background-clip: text;
                /* Standard property */
                color: transparent;
                /* Fallback color */
            }

            .vehicleSection {
                cursor: pointer;
            }

            .headerDividerSection {
                border-left: 4px solid #fff;
                height: 210px;
                position: absolute;
                right: 390px;
                top: 30px;
            }

            .room-badge-area-show {
                width: 500px;
                height: 500px;
                margin: 0 auto;
                background: #666;
                position: relative;
            }

            .room-bagde-flag-wrap {
                position: absolute;
                top: 10px;
                left: -12px;
            }

            .room-bagde-flag-wrap span {
                font-size: 12px;
                font-weight: 500;
            }

            .room-bagde-flag-wrap::before {
                content: "";
                position: absolute;
                top: 28px;
                right: -12px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0 12px 12px 0;
                /* border-color: transparent #cd48b7 transparent transparent; */
                border-color: transparent #000 transparent transparent;
            }

            .room-bagde-flag {
                text-transform: capitalize;
                color: #fff;
                /* background: linear-gradient(to right, rgba(114, 49, 207, 0.8) 0%, rgba(195, 60, 166, 0.8) 68%, rgba(238, 63, 206, 0.8) 100%) !important; */
                background: #000;
                letter-spacing: 0;
                font-size: 14px;
                line-height: 15px;
                font-weight: 600;
                padding: 5px 9px;
                position: absolute;
                left: 0px;
                display: block;
                text-decoration: none;
            }

            .badge-room-occupancy {
                position: absolute;
                top: 5px;
                left: 0;
                border-radius: 3px;
            }

            .roomTypeSelectionArea h5 {
                font-size: 15px;
            }

            .roomCategoryDropdown {
                font-size: 14px;
                margin-left: 2px;
                margin-top: 1px;
            }

            .defaultEditRoomCategory {
                padding: 0.5rem 1rem 0.5rem 0;
            }

            .carousel .carousel-item.active h6 {
                color: #5d596c;
            }

            .purple-badge {
                height: 35px;
                position: relative;
                background-color: #000;
                border-radius: 0.375rem 0 0 0;
            }

            .purple-badge span {
                display: flex;
                align-items: center;
                height: 100%;
                width: 100%;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 10px;
            }

            .roomDetailsCarousel .carousel-control-prev-icon,
            .roomDetailsCarousel .carousel-control-next-icon {
                background-color: #000;
                border-radius: 50%;
                width: 30px;
                height: 30px;
            }

            .roomDetailsCarousel .carousel-control-prev {
                left: -25px;
            }

            .roomDetailsCarousel .carousel-control-next {
                right: -25px;
            }

            .hotel-supplementry-succesbadge {
                border: 1px solid #28C76F;
                background: #fff;
                color: #28C76F;
                position: absolute;
                right: 0;
                border-radius: 0px;
                bottom: 21px;
                font-size: 16px;
            }

            .hotel-supplementry-dangerbadge {
                border: 1px solid #FF4C51;
                background: #fff;
                color: #FF4C51;
                position: absolute;
                right: 0;
                border-radius: 0px;
                bottom: 21px;
                font-size: 16px;
            }

            .table-border-active {
                border: 2px solid rgba(114, 49, 207, 0.8);
                border-radius: 5px;
            }
        </style>
        <div class="card p-4">
            <div class="d-flex justify-content-between">
                <div class="col-md-6">
                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Hotel List</strong></h5>
                </div>
            </div>
            <div class="card-header pt-2">
                <ul class="nav nav-tabs hotel-list-nav card-header-tabs" role="tablist">
                    <?php
                    $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                    while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
                        $group_type = $row_hotel_group['group_type'];
                        if ($group_type == $_group_type) :
                            $add_active_tab_class = 'active';
                        else :
                            $add_active_tab_class = '';
                        endif;
                    ?>
                        <li class="nav-item">
                            <?php
                            $hotelCharges = getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $group_type, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES');
                            // Check if additional margin needs to be applied
                            if ($itinerary_no_of_days <= $itinerary_additional_margin_day_limit) {
                                // Calculate additional margin
                                $additional_margin = ($itinerary_additional_margin_percentage * $hotelCharges) / 100;
                            } else {
                                $additional_margin = 0;
                            }
                            $formatted_grand_total = ($hotelCharges + $additional_margin);
                            ?>
                            <button class="nav-link <?= $add_active_tab_class; ?>" id="group_tab_<?= $group_type; ?>" onclick="showHOTELTAB('<?= $itinerary_plan_ID; ?>','<?= $group_type; ?>')" role="tab" aria-selected="true">
                                Recommended #<?= $group_type; ?> (<?= general_currency_symbol . ' ' . number_format($formatted_grand_total,2); ?>)
                                <span class="arrow <?= $add_active_tab_class; ?>" id="arrow_group_tab_<?= $group_type; ?>"></span>
                            </button>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>

            <?php
            if ($hotel_rates_visibility == 1):
                $add_colspan_hotel_name = "colspan='2'";
                $add_colspan_hotel_total = "colspan='6'";
            else:
                $add_colspan_hotel_name = "";
                $add_colspan_hotel_total = "colspan='5'";
            endif;
            ?>
            <div class="tab-content px-0">
                <div class="tab-pane fade active show" id="show_recommended_hotel_details" role="tabpanel">
                    <div class="text-nowrap table-responsive p-2 table-border-active">
                        <table class="table table-hover border-top-0">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Destination</th>
                                    <th <?= $add_colspan_hotel_name; ?>>Hotel Name - Category</th>
                                    <th>Hotel Room Type</th>
                                    <?php if ($hotel_rates_visibility == 1): ?>
                                        <th>Price</th>
                                    <?php else: ?>
                                        <th></th>
                                    <?php endif; ?>
                                    <th>Meal Plan</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0" id="itineary_hotel_LIST">
                                <?php
                                $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT HOTEL_DETAILS.`group_type`, ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ROOM_DETAILS.`room_id`, ROOM_DETAILS.`room_type_id`, ROOM_DETAILS.`gst_type`, ROOM_DETAILS.`gst_percentage`, ROOM_DETAILS.`extra_bed_rate`, ROOM_DETAILS.`child_without_bed_charges`, ROOM_DETAILS.`child_with_bed_charges`, ROOM_DETAILS.`breakfast_required`, ROOM_DETAILS.`lunch_required`, ROOM_DETAILS.`dinner_required`, ROOM_DETAILS.`breakfast_cost_per_person`, ROOM_DETAILS.`lunch_cost_per_person`, ROOM_DETAILS.`dinner_cost_per_person`, HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, HOTEL_DETAILS.`itinerary_plan_id`, HOTEL_DETAILS.`itinerary_route_id`, HOTEL_DETAILS.`itinerary_route_date`, HOTEL_DETAILS.`itinerary_route_location`, HOTEL_DETAILS.`hotel_required`, HOTEL_DETAILS.`hotel_category_id`, HOTEL_DETAILS.`hotel_id`, HOTEL_DETAILS.`hotel_margin_percentage`, HOTEL_DETAILS.`hotel_margin_gst_type`, HOTEL_DETAILS.`hotel_margin_gst_percentage`, HOTEL_DETAILS.`hotel_margin_rate`, HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, HOTEL_DETAILS.`hotel_breakfast_cost`, HOTEL_DETAILS.`hotel_lunch_cost`, HOTEL_DETAILS.`hotel_dinner_cost`, HOTEL_DETAILS.`total_no_of_persons`, HOTEL_DETAILS.`total_hotel_meal_plan_cost`, HOTEL_DETAILS.`total_no_of_rooms`, HOTEL_DETAILS.`total_room_cost`, HOTEL_DETAILS.`total_extra_bed_cost`, HOTEL_DETAILS.`total_childwith_bed_cost`, HOTEL_DETAILS.`total_childwithout_bed_cost`, HOTEL_DETAILS.`total_room_gst_amount`, HOTEL_DETAILS.`total_hotel_cost`, HOTEL_DETAILS.`total_hotel_tax_amount`, HOTEL_DETAILS.`total_amenities_cost`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_breakfast_cost_gst_amount`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_lunch_cost_gst_amount`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_dinner_cost_gst_amount`, HOTEL_DETAILS.`total_hotel_meal_plan_cost_gst_amount`, HOTEL_DETAILS.`total_extra_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwith_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwithout_bed_cost_gst_amount` FROM `dvi_itinerary_plan_hotel_details` HOTEL_DETAILS LEFT JOIN `dvi_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` AND ROOM_DETAILS.`group_type` = '$_group_type' WHERE HOTEL_DETAILS.`deleted` = '0' AND HOTEL_DETAILS.`status` = '1' AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND HOTEL_DETAILS.`group_type` = '$_group_type' GROUP BY HOTEL_DETAILS.`itinerary_route_date` ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
                                $select_itinerary_plan_hotel_count = sqlNUMOFROW_LABEL($select_itinerary_plan_hotel_data);
                                if ($select_itinerary_plan_hotel_count > 0) :
                                    while ($fetch_hotel_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_hotel_data)) :
                                        $hotel_counter++;
                                        $itinerary_plan_hotel_details_ID = $fetch_hotel_data['itinerary_plan_hotel_details_ID'];
                                        $group_type = $fetch_hotel_data['group_type'];
                                        $itinerary_plan_hotel_room_details_ID = $fetch_hotel_data['itinerary_plan_hotel_room_details_ID'];
                                        $itinerary_plan_id = $fetch_hotel_data['itinerary_plan_id'];
                                        $itinerary_route_id = $fetch_hotel_data['itinerary_route_id'];
                                        $itinerary_route_date = $fetch_hotel_data['itinerary_route_date'];
                                        $date = new DateTime($itinerary_route_date);
                                        $formatted_date = $date->format('d M Y');
                                        $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                                        $hotel_required = $fetch_hotel_data['hotel_required'];
                                        $gst_type = $fetch_hotel_data['gst_type'];
                                        $gst_percentage = $fetch_hotel_data['gst_percentage'];
                                        $hotel_category_id = $fetch_hotel_data['hotel_category_id'];
                                        $selected_hotel_id = $fetch_hotel_data['hotel_id'];
                                        $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                                        $hotel_margin_gst_type = $fetch_hotel_data['hotel_margin_gst_type'];
                                        $hotel_margin_gst_percentage = $fetch_hotel_data['hotel_margin_gst_percentage'];
                                        $hotel_margin_rate = $fetch_hotel_data['hotel_margin_rate'];
                                        $hotel_margin_rate_tax_amt = $fetch_hotel_data['hotel_margin_rate_tax_amt'];
                                        $hotel_breakfast_cost = $fetch_hotel_data['hotel_breakfast_cost'];
                                        $hotel_lunch_cost = $fetch_hotel_data['hotel_lunch_cost'];
                                        $hotel_dinner_cost = $fetch_hotel_data['hotel_dinner_cost'];
                                        $total_no_of_persons = $fetch_hotel_data['total_no_of_persons'];
                                        $total_hotel_meal_plan_cost = $fetch_hotel_data['total_hotel_meal_plan_cost'];
                                        $total_no_of_rooms = $fetch_hotel_data['total_no_of_rooms'];
                                        $total_room_cost = round($fetch_hotel_data['total_room_cost']);
                                        $total_extra_bed_cost = round($fetch_hotel_data['total_extra_bed_cost']);
                                        $total_childwith_bed_cost = round($fetch_hotel_data['total_childwith_bed_cost']);
                                        $total_childwithout_bed_cost = round($fetch_hotel_data['total_childwithout_bed_cost']);
                                        $extra_bed_rate = round($fetch_hotel_data['extra_bed_rate']);
                                        $child_without_bed_charges = round($fetch_hotel_data['child_without_bed_charges']);
                                        $child_with_bed_charges = round($fetch_hotel_data['child_with_bed_charges']);
                                        $total_room_gst_amount = round($fetch_hotel_data['total_room_gst_amount']);
                                        $total_hotel_cost = round($fetch_hotel_data['total_hotel_cost']);
                                        $total_hotel_tax_amount = round($fetch_hotel_data['total_hotel_tax_amount']);
                                        $total_amenities_cost = round($fetch_hotel_data['total_amenities_cost']);
                                        $total_amenities_gst_amount = round($fetch_hotel_data['total_amenities_gst_amount']);
                                        $hotel_breakfast_cost_gst_amount = round($fetch_hotel_data['hotel_breakfast_cost_gst_amount']);
                                        $hotel_lunch_cost_gst_amount = round($fetch_hotel_data['hotel_lunch_cost_gst_amount']);
                                        $hotel_dinner_cost_gst_amount = round($fetch_hotel_data['hotel_dinner_cost_gst_amount']);
                                        $total_hotel_meal_plan_cost_gst_amount = round($fetch_hotel_data['total_hotel_meal_plan_cost_gst_amount']);
                                        $total_extra_bed_cost_gst_amount = round($fetch_hotel_data['total_extra_bed_cost_gst_amount']);
                                        $total_childwith_bed_cost_gst_amount = round($fetch_hotel_data['total_childwith_bed_cost_gst_amount']);
                                        $total_childwithout_bed_cost_gst_amount = round($fetch_hotel_data['total_childwithout_bed_cost_gst_amount']);
                                        $selected_room_id = $fetch_hotel_data['room_id'];
                                        $selected_room_type_id = $fetch_hotel_data['room_type_id'];
                                        $check_in_time = getROOM_DETAILS($selected_room_id, 'check_in_time');
                                        $check_out_time = getROOM_DETAILS($selected_room_id, 'check_out_time');
                                        $breakfast_required = $fetch_hotel_data['breakfast_required'];
                                        $lunch_required = $fetch_hotel_data['lunch_required'];
                                        $dinner_required = $fetch_hotel_data['dinner_required'];
                                        $breakfast_cost_per_person = $fetch_hotel_data['breakfast_cost_per_person'];
                                        $lunch_cost_per_person = $fetch_hotel_data['lunch_cost_per_person'];
                                        $dinner_cost_per_person = $fetch_hotel_data['dinner_cost_per_person'];
                                        $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');

                                        if ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1 && $lunch_required == 1 && $lunch_cost_per_person > 0 && $dinner_required == 1 && $dinner_cost_per_person > 0):
                                            $hotel_meal_label = 'AP';
                                        elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1 && $dinner_required == 1 && $dinner_cost_per_person > 0) :
                                            $hotel_meal_label = 'MAP';
                                        elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1 && $lunch_required == 1 && $lunch_cost_per_person > 0):
                                            $hotel_meal_label = 'MAP';
                                        elseif ($breakfast_required == 1 && $breakfast_cost_per_person > 0 && $hotel_required == 1):
                                            $hotel_meal_label = 'CP';
                                        else:
                                            $hotel_meal_label = 'EP';
                                        endif;

                                ?>
                                        <input type="hidden" name="hid_group_type" id="hid_group_type" value="<?= $group_type ?>" />
                                        <tr class="cursor-pointer" data-hotel-counter="<?= $itinerary_plan_hotel_details_ID; ?>">
                                            <td style="max-width: 140px;">Day <?= $hotel_counter; ?> | <?= $formatted_date; ?></td>
                                            <td style="max-width: 200px;" class="text-truncate">
                                                <span data-toggle="tooltip" placement="top" title="<?= $itinerary_route_location; ?>"><?= $itinerary_route_location; ?></span>
                                            </td>
                                            <td <?= $add_colspan_hotel_name; ?> style="max-width: 80px;" class="text-truncate">
                                                <?php if ($hotel_required == 1) : ?>
                                                    <span data-toggle="tooltip" placement="top" title="<?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?> - <?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>"><i class="fa-solid fa-hotel me-1 hotelIcon"></i><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?><b> - </b><?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?></span>
                                                <?php else : ?>
                                                    <span>--</span>
                                                <?php endif; ?>
                                            </td>
                                            <td style="max-width: 80px;" class="text-truncate">
                                                <?php if ($hotel_required == 1) : ?>
                                                    <span data-toggle="tooltip" placement="top" title="<?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?>"><?= getROOMTYPE_DETAILS($selected_room_type_id, 'room_type_title'); ?></span>
                                                <?php else : ?>
                                                    <span>--</span>
                                                <?php endif; ?>
                                            </td>
                                            <?php if ($hotel_rates_visibility == 1): ?>
                                                <td class="price-tooltip-data-section">
                                                    <?php if ($logged_user_level != 4): ?>
                                                        <?php if ($total_room_cost > 0) : ?>
                                                            <span class="price-tooltip" data-toggle="tooltip" data-placement="top" data-bs-html="true" title='<div class="">
                                                            <div class="d-flex align-items-center justify-content-between my-3 grand_total_section">
                                                            <p class="mb-0"><b>Total No. of Rooms - <?= $total_no_of_rooms; ?></b></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between my-3 flex-wrap">
                                                            <p class="mb-0 mr-5">Total Room Cost</p>
                                                            <p class="mb-0 mr-5 ml-5 text-center"><?= general_currency_symbol . ' ' . number_format(($total_room_cost), 2); ?></p>
                                                        </div>
                                                        <?php if ($hotel_breakfast_cost > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total Breakfast Cost</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($hotel_breakfast_cost, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($hotel_lunch_cost > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total Lunch Cost</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($hotel_lunch_cost, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($hotel_dinner_cost > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total Dinner Cost</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($hotel_dinner_cost, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($total_extra_bed_cost > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total Extra Bed Cost</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($total_extra_bed_cost, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($total_childwith_bed_cost > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total With Bed Cost</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($total_childwith_bed_cost, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($total_childwithout_bed_cost > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total Without Bed Cost</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($total_childwithout_bed_cost, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php
                                                            $selected_itinerary_plan_hotel_room_amenities = sqlQUERY_LABEL("SELECT AMENITIES.`amenities_title`, ITINEARY_AMENITIES.`total_qty`, ITINEARY_AMENITIES.`amenitie_rate`, ITINEARY_AMENITIES.`total_amenitie_cost` FROM `dvi_itinerary_plan_hotel_room_amenities` ITINEARY_AMENITIES LEFT JOIN `dvi_hotel_amenities` AMENITIES ON AMENITIES.`hotel_amenities_id` = ITINEARY_AMENITIES.`hotel_amenities_id` WHERE ITINEARY_AMENITIES.`deleted` = '0' AND ITINEARY_AMENITIES.`status` = '1' AND ITINEARY_AMENITIES.`itinerary_plan_id` = '$itinerary_plan_id' AND ITINEARY_AMENITIES.`itinerary_route_id` = '$itinerary_route_id' AND ITINEARY_AMENITIES.`hotel_ID` = '$selected_hotel_id' AND `group_type` = '$group_type'") or die("#STATELABEL-LABEL: getHOTEL_DETAIL: " . sqlERROR_LABEL());
                                                            $total_no_of_amenities_count = sqlNUMOFROW_LABEL($selected_itinerary_plan_hotel_room_amenities);
                                                            if ($total_no_of_amenities_count > 0) :
                                                                while ($fetch_hotel_room_amenities_data = sqlFETCHARRAY_LABEL($selected_itinerary_plan_hotel_room_amenities)) :
                                                                    $amenities_title = $fetch_hotel_room_amenities_data['amenities_title'];
                                                                    $total_qty = $fetch_hotel_room_amenities_data['total_qty'];
                                                                    $amenitie_rate = $fetch_hotel_room_amenities_data['amenitie_rate'];
                                                                    $total_amenitie_cost = $fetch_hotel_room_amenities_data['total_amenitie_cost'];
                                                        ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5" data-toggle="tooltip" placement="top" title="<?= $amenities_title; ?>"><?= limit_words($amenities_title, 2) . ' <small>(' . $total_qty . ' x ' . general_currency_symbol . ' ' . number_format($total_amenitie_cost, 2) . ')</small>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($total_amenitie_cost, 2); ?></p>
                                                        </div>
                                                                <?php
                                                                endwhile;
                                                            endif;
                                                                ?>                                                    
                                                        <?php if ($total_room_gst_amount > 0 || $total_amenities_gst_amount > 0 || $hotel_breakfast_cost_gst_amount > 0 || $hotel_lunch_cost_gst_amount > 0 || $hotel_dinner_cost_gst_amount > 0 || $total_extra_bed_cost_gst_amount > 0 || $total_childwith_bed_cost_gst_amount > 0 || $total_childwithout_bed_cost_gst_amount > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Total Tax (<?= $gst_percentage; ?>%)</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($total_room_gst_amount + $total_amenities_gst_amount + $hotel_breakfast_cost_gst_amount + $hotel_lunch_cost_gst_amount + $hotel_dinner_cost_gst_amount + $total_extra_bed_cost_gst_amount + $total_childwith_bed_cost_gst_amount + $total_childwithout_bed_cost_gst_amount, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($hotel_margin_rate > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0">Hotel Margin (<?= $hotel_margin_percentage; ?>%)</p>
                                                            <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($hotel_margin_rate, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($hotel_margin_rate_tax_amt > 0) : ?>
                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                            <p class="mb-0 mr-5">Service Tax (<?= $hotel_margin_gst_percentage; ?>%)</p>
                                                            <p class="mb-0 mr-5 ml-5"><?= general_currency_symbol . ' ' . number_format($hotel_margin_rate_tax_amt, 2); ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if ($total_hotel_cost > 0 || $total_hotel_tax_amount > 0) : ?>
                                                        <hr class="my-2">
                                                        <div class="d-flex align-items-center justify-content-between my-3 grand_total_section">
                                                            <p class="mb-0"><b>Grand Total</b></p>
                                                            <p class="mb-0"><b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost + $total_hotel_tax_amount, 2); ?></b></p>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>'>
                                                                <b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost + $total_hotel_tax_amount, 2); ?></b>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="text-danger fw-bold">Sold Out</span>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <?php if ($total_room_cost > 0) : ?>
                                                            <span>
                                                                <b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost + $total_hotel_tax_amount, 2); ?></b>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="text-danger fw-bold">Sold Out</span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            <?php else: ?>
                                                <td style="max-width: 80px;"></td>
                                            <?php endif; ?>
                                            <td style="max-width: 80px;" class="text-truncate">
                                                <span><?= $hotel_meal_label; ?></span>
                                            </td>
                                        </tr>
                                <?php endwhile;
                                endif; ?>
                                <tr>
                                    <td <?= $add_colspan_hotel_total; ?> class="text-end"><strong>Hotel Total :</strong></td>
                                    <td>
                                        <?php
                                        // Ensure getHOTEL_ITINEARY_PLAN_DETAILS returns a numeric value or 0
                                        $grand_total_hotel_charges = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '1', 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES');

                                        // Format the number
                                        $formatted_grand_total = number_format(round($grand_total_hotel_charges), 2);
                                        ?>
                                        <h6 class="mb-0"><strong><span><?= general_currency_symbol . ' ' . $formatted_grand_total; ?></span></strong></h6>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 d-none">
                        <div class="">
                            <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Overall Hotel Cost</strong></h5>
                            <div class="order-calculations d-flex flex-wrap">
                                <div class="col-3">
                                    <?php
                                    $total_room_cost = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_ROOM_COST');
                                    $total_food_cost = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_FOOD_COST');
                                    // Calculate the total hotel cost
                                    $total_hotel_cost = $total_room_cost - $total_food_cost;
                                    $total_food_cost = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_FOOD_COST');
                                    $total_amenities_cost = (float)get_ITINEARYHOTEL_AMENITIES_DETAILS($group_type, $itinerary_plan_ID, '', 'TOTAL_AMENITIES_COST');
                                    $total_room_tax_amount = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_ROOM_TAX_AMOUNT');
                                    $total_amenities_tax_amount = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_AMENITIES_TAX_AMOUNT');
                                    $hotel_margin_rate = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_MARGIN_RATE');
                                    $service_tax_amount = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, '', 'TOTAL_HOTEL_MARGIN_RATE_TAX_AMOUNT');
                                    ?>
                                    <p class="text-heading">Total Hotel Cost : <b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost, 2); ?></b></p>
                                </div>
                                <div class="col-1">
                                    <span>+</span>
                                </div>
                                <div class="col-3">
                                    <p class="text-heading">Total Food Cost : <b><?= general_currency_symbol . ' ' . number_format($total_food_cost, 2); ?></b></p>
                                </div>
                                <div class="col-1">
                                    <span>+</span>
                                </div>
                                <div class="col-3">
                                    <p class="text-heading">Total Amenities Cost : <b><?= general_currency_symbol . ' ' . number_format($total_amenities_cost, 2); ?></b></p>
                                </div>
                                <div class="col-1">
                                    <span>+</span>
                                </div>
                                <div class="col-3">
                                    <p class="text-heading">Total Tax : <b><?= general_currency_symbol . ' ' . number_format($total_room_tax_amount + $total_amenities_tax_amount, 2); ?></b></p>
                                </div>
                                <div class="col-1">
                                    <span>+</span>
                                </div>
                                <div class="col-3">
                                    <p class="text-heading">Hotel Margin : <b><?= general_currency_symbol . ' ' . number_format($hotel_margin_rate, 2); ?></b></p>
                                </div>
                                <div class="col-1">
                                    <span>+</span>
                                </div>
                                <div class="col-3">
                                    <p class="text-heading">Service Tax : <b><?= general_currency_symbol . ' ' . number_format($service_tax_amount, 2); ?></b></p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {

                // $("[id^='choosen_room_type_']").selectize();

                // Initialize all tooltips generally
                $('[data-toggle="tooltip"]').tooltip({
                    html: true,
                    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                });

                // Specifically initialize tooltips with the price-tooltip class
                $('.price-tooltip').tooltip({
                    html: true,
                    template: '<div class="tooltip price-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>'
                });

                // Add click event handler to each tr element
                $("tbody#itineary_hotel_LIST tr").on("click", function() {
                    // Get the value of hotel_counter from the data attribute
                    var hotelCounter = $(this).data("hotel-counter");

                    // Construct the ID of the corresponding tr element to toggle its visibility
                    var trId = "#hotel_details_" + hotelCounter;

                    // Toggle the visibility of the corresponding tr element
                    $(trId).toggleClass("d-none");

                    // Check if the row is now visible and focus the input field
                    if (!$(trId).hasClass("d-none")) {
                        $(trId).find(".hotelSearch").focus();
                    }
                });

                // When any 'All' checkbox is clicked
                $('[id^="all_meal_plan_"]').change(function() {
                    var hotel_id = $(this).data('hotel-id'); // Get the respective hotel ID
                    var route_id = $(this).data('route-id'); // Get the respective route ID
                    var grouptype_id = $(this).data('grouptype-id'); // Get the respective grouptype
                    var hotel_room_detail_id = $(this).data('hotel_room_detail-id'); // Get the respective hotel_room_detail_id
                    var isChecked = $(this).is(':checked'); // Check if 'All' is checked or not

                    // Find checkboxes for breakfast, lunch, and dinner using the hotel ID
                    $('#breakfast_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id + ', #lunch_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id + ', #dinner_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id).prop('checked', isChecked); // Set them to the same state as 'All'
                });

                // When any individual checkbox is clicked
                $('[id^="breakfast_meal_plan_"], [id^="lunch_meal_plan_"], [id^="dinner_meal_plan_"]').change(function() {
                    var hotel_id = $(this).data('hotel-id'); // Get the respective hotel ID
                    var route_id = $(this).data('route-id'); // Get the respective route ID
                    var grouptype_id = $(this).data('grouptype-id'); // Get the respective grouptype
                    var hotel_room_detail_id = $(this).data('hotel_room_detail-id'); // Get the respective hotel_room_detail_id

                    // If any checkbox is unchecked, uncheck the 'All' checkbox
                    if (!$(this).is(':checked')) {
                        $('#all_meal_plan_' + hotel_id + '_' + route_id).prop('checked', false);
                    } else {
                        var isChecked = $(this).is(':checked');
                        var checked_all_meal_plan = $('#breakfast_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id + ', #lunch_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id + ', #dinner_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id).prop('checked', isChecked);
                        if (checked_all_meal_plan) {
                            $('#all_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id).prop('checked', true);
                        }
                    }
                });
            });

            $('.search-container').each(function() {
                var $searchContainer = $(this); // The specific search container
                var $searchInput = $searchContainer.find('.hotelSearch'); // Search input within the section

                $searchInput.on('input', function() {
                    var searchValue = $(this).val().toLowerCase().trim(); // Get and normalize the search input

                    // Find hotel cards within the same search container section
                    $searchContainer.find('.col-md-3, .col-lg-4, .col-xxl-3').each(function() {
                        var hotelName = $(this).find('.overlay h6.mb-0.text-wrap').text().toLowerCase().trim(); // Get the hotel name text
                        console.log(hotelName); // Debug: Check what names are being found
                        if (hotelName.includes(searchValue)) {
                            $(this).show(); // Show matching cards
                        } else {
                            $(this).hide(); // Hide non-matching cards
                        }
                    });
                });
            });

            function getchangeHOTELROOMTYPE(dropdown, group_type, itinerary_plan_hotel_details_ID, itinerary_plan_hotel_room_details_ID, itinerary_plan_id, itinerary_route_id, hotel_id) {
                var choosen_room_type = $(dropdown).val(); // Use the ID of the dropdown to select it
                $('#choosen_room_type_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID + ' option:selected').val(choosen_room_type);
            }

            function changeHOTELROOMTYPE(dropdown, group_type, itinerary_plan_hotel_details_ID, itinerary_plan_hotel_room_details_ID, itinerary_plan_id, itinerary_route_id, hotel_id) {
                // Get the selected value of the current dropdown within the loop
                var choosen_room_type = $(dropdown).val(); // Use the ID of the dropdown to select it
                var choosen_room_type = $('#choosen_room_type_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID + ' option:selected').val();
                var hidden_hotel_required = $('#hidden_hotel_required').val();
                var all_meal_plan = $('#all_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var breakfast_meal_plan = $('#breakfast_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var lunch_meal_plan = $('#lunch_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var dinner_meal_plan = $('#dinner_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;

                if (!choosen_room_type) {
                    choosen_room_type = ''; // Ensure it's an empty string if no option is selected
                }

                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_manage_itineary.php?type=show_modify_hotel_room_type_form&itinerary_plan_hotel_details_ID=' + itinerary_plan_hotel_details_ID + '&itinerary_plan_hotel_room_details_ID=' + itinerary_plan_hotel_room_details_ID + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&all_meal_plan=' + all_meal_plan + '&breakfast_meal_plan=' + breakfast_meal_plan + '&lunch_meal_plan=' + lunch_meal_plan + '&dinner_meal_plan=' + dinner_meal_plan + '&choosen_room_type=' + choosen_room_type + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showHOTELROOMGALLERY(hotel_ID) {
                $('.receiving-gallery-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_show_hotel_gallery.php?type=show_form&hotel_ID=' + hotel_ID, function() {
                    const container = document.getElementById("GALLERYMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showHOTELADDAMENITIES(group_type, hotel_ID, itinerary_route_date, itinerary_plan_id, itinerary_route_id) {
                $('.receiving-hotel-amenities-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_show_hotel_amenities.php?type=show_form&hotel_ID=' + hotel_ID + '&itinerary_route_date=' + itinerary_route_date + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&group_type=' + group_type, function() {
                    const container = document.getElementById("hotelADDAMENITIESMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function modifyHOTELROOM(group_type, itinerary_plan_hotel_details_ID, itinerary_plan_hotel_room_details_ID, itinerary_plan_id, itinerary_route_id, hotel_id, hotel_required) {
                var all_meal_plan = $('#all_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var breakfast_meal_plan = $('#breakfast_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var lunch_meal_plan = $('#lunch_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var dinner_meal_plan = $('#dinner_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var choosen_room_type = $('#choosen_room_type_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).val();

                if (choosen_room_type) {
                    choosen_room_type = choosen_room_type;
                } else {
                    choosen_room_type = '';
                }

                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_manage_itineary.php?type=show_modify_hotel_form&itinerary_plan_hotel_details_ID=' + itinerary_plan_hotel_details_ID + '&itinerary_plan_hotel_room_details_ID=' + itinerary_plan_hotel_room_details_ID + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&hotel_required=' + hotel_required + '&all_meal_plan=' + all_meal_plan + '&breakfast_meal_plan=' + breakfast_meal_plan + '&lunch_meal_plan=' + lunch_meal_plan + '&dinner_meal_plan=' + dinner_meal_plan + '&choosen_room_type=' + choosen_room_type + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function modifyHOTELROOMTYPE(group_type, itinerary_plan_hotel_details_ID, itinerary_plan_hotel_room_details_ID, itinerary_plan_id, itinerary_route_id, hotel_id, hotel_required) {
                var all_meal_plan = $('#all_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var breakfast_meal_plan = $('#breakfast_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var lunch_meal_plan = $('#lunch_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var dinner_meal_plan = $('#dinner_meal_plan_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;

                $('.receiving-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotel_multiple_rooms.php?type=show_form&itinerary_plan_hotel_details_ID=' + itinerary_plan_hotel_details_ID + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&hotel_id=' + hotel_id + '&hotel_required=' + hotel_required + '&all_meal_plan=' + all_meal_plan + '&breakfast_meal_plan=' + breakfast_meal_plan + '&lunch_meal_plan=' + lunch_meal_plan + '&dinner_meal_plan=' + dinner_meal_plan + '&group_type=' + group_type, function() {
                    const container = document.getElementById("MODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showHOTELDETAILS(group_type, hotel_ID, selected_hotel_id, itinerary_plan_id, itinerary_route_id, itinerary_plan_hotel_room_details_ID) {
                var all_meal_plan = $('#all_meal_plan_' + hotel_ID + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var breakfast_meal_plan = $('#breakfast_meal_plan_' + hotel_ID + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var lunch_meal_plan = $('#lunch_meal_plan_' + hotel_ID + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var dinner_meal_plan = $('#dinner_meal_plan_' + hotel_ID + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).prop('checked') ? 1 : 0;
                var choosen_room_type = $('#choosen_room_type_' + hotel_id + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).val();

                if (choosen_room_type) {
                    choosen_room_type = choosen_room_type;
                } else {
                    choosen_room_type = '';
                }

                $('.receiving-view-modal-info-form-data').load('engine/ajax/ajax_latest_itineary_hotel_info_view.php?type=show_form&hotel_ID=' + hotel_ID + '&selected_hotel_id=' + selected_hotel_id + '&all_meal_plan=' + all_meal_plan + '&breakfast_meal_plan=' + breakfast_meal_plan + '&lunch_meal_plan=' + lunch_meal_plan + '&dinner_meal_plan=' + dinner_meal_plan + '&choosen_room_type=' + choosen_room_type + '&itinerary_plan_id=' + itinerary_plan_id + '&itinerary_route_id=' + itinerary_route_id + '&group_type=' + group_type, function() {
                    const container = document.getElementById("VIEWMODALINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function showHOTELTAB(itinerary_plan_id, group_type) {
                var spinner = $('#spinner');
                // Remove active class from all nav-link buttons
                let tabs = document.querySelectorAll('.nav-link');
                tabs.forEach(function(tab) {
                    tab.classList.remove('active'); // Assuming 'active' is the class you want to remove
                });

                // Add active class to the clicked button
                let currentTab = document.getElementById('group_tab_' + group_type);
                let arrowgroupcurrentTab = document.getElementById('arrow_group_tab_' + group_type);
                currentTab.classList.add('active');
                arrowgroupcurrentTab.classList.add('active');
                spinner.show();
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_view_itinerary_recommended_hotel_details_form.php?type=show_form",
                    data: {
                        _planID: itinerary_plan_id,
                        _groupTYPE: group_type,
                    },
                    success: function(response) {
                        $('#show_recommended_hotel_details').html('')
                        $('#show_recommended_hotel_details').html(response);
                        showOVERALL_COST_DETAILS(itinerary_plan_id, group_type);
                        showOVERALLCOSTAMOUNT(itinerary_plan_id, group_type);
                        spinner.hide();
                    }
                });
            }

            function showOVERALLCOSTAMOUNT(itinerary_plan_ID, group_type) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_view_itineary_overall_cost_details.php?type=show_grand_itineary_total",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                        _groupTYPE: group_type,
                    },
                    success: function(response) {
                        $('#overall_trip_cost').text('');
                        $('#overall_trip_cost').text(response);
                    }
                });
            }

            function showOVERALL_COST_DETAILS(itinerary_plan_ID, group_type) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_view_itineary_overall_cost_details.php?type=show_form",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                        _groupTYPE: group_type,
                    },
                    success: function(response) {
                        $('#showOVERALLCOSTINFO').html('');
                        $('#showOVERALLCOSTINFO').html(response);
                    }
                });
            }

            function get_ACTIVE_GROUP_TYPE() {
                // Find the active tab
                var activeTab = document.querySelector('.nav-link.active');

                // If an active tab is found
                if (activeTab) {
                    // Extract the ID of the active tab
                    var tabId = activeTab.id;

                    // Extract the number from the tab ID
                    var tabNumber = tabId.match(/group_tab_(\d+)/)[1];

                    // Return the extracted tab number
                    return tabNumber;
                }

                // Return null if no active tab is found
                return null;
            }

            function showHOTELRATES(itinerary_plan_ID) {
                var spinner = $('#spinner');
                var isChecked = $('#check_show_hotel_rates').is(':checked') ? 1 : 0; // Get the checkbox state (1 if checked, 0 if unchecked)
                spinner.show();
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_manage_itineary.php?type=show_hotel_rates",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                        _hotel_rates_visibility: isChecked
                    },
                    dataType: "json", // Expect JSON response
                    success: function(response) {
                        if (response.success) {
                            TOAST_NOTIFICATION('success', 'Hotel Rates Enabled', response.message || 'Success !!!');
                            showHOTELTAB(itinerary_plan_ID, get_ACTIVE_GROUP_TYPE());
                            spinner.hide();
                        } else {
                            spinner.hide();
                            TOAST_NOTIFICATION('error', 'Unable to complete your request. Please try again later.', response.message || 'Error !!!');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        spinner.hide();
                        TOAST_NOTIFICATION('error', 'Unable to complete your request. Please try again later.', 'AJAX Error');
                    }
                });
            }
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
