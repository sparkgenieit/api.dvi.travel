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

        $itinerary_plan_ID = $_POST['_planID'];
        $itinerary_group_TYPE = $_POST['_groupTYPE'];
        $hotel_rates_visibility = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'hotel_rates_visibility');

        $itinerary_no_of_days = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'no_of_days');
        $itinerary_additional_margin_percentage = getGLOBALSETTING('itinerary_additional_margin_percentage');
        $itinerary_additional_margin_day_limit = getGLOBALSETTING('itinerary_additional_margin_day_limit');
?>
        <?php
        $itinerary_plan_hotel_group_query = sqlQUERY_LABEL("SELECT `group_type` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `itinerary_plan_ID` = '$itinerary_plan_ID' GROUP BY `group_type`") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
        while ($row_hotel_group = sqlFETCHARRAY_LABEL($itinerary_plan_hotel_group_query)) :
            $group_type = $row_hotel_group['group_type'];
            if ($group_type == 1) :
                $add_active_tab_class = 'active';
            else :
                $add_active_tab_class = '';
            endif;
        endwhile;
        ?>
        <style>
            .table-border-active {
                border: 2px solid rgba(114, 49, 207, 0.8);
                border-radius: 5px;
            }
        </style>
        <link rel="stylesheet" href="assets/css/itineary_room_details.css" />
        <?php
        if ($hotel_rates_visibility == 1):
            $add_colspan_hotel_name = "colspan='2'";
            $add_colspan_hotel_total = "colspan='6'";
        else:
            $add_colspan_hotel_name = "";
            $add_colspan_hotel_total = "colspan='5'";
        endif;
        ?>

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
                    $select_itinerary_plan_hotel_data = sqlQUERY_LABEL("SELECT HOTEL_DETAILS.`group_type`, ROOM_DETAILS.`itinerary_plan_hotel_room_details_ID`, ROOM_DETAILS.`room_id`, ROOM_DETAILS.`room_type_id`, ROOM_DETAILS.`gst_type`, ROOM_DETAILS.`gst_percentage`, ROOM_DETAILS.`extra_bed_rate`, ROOM_DETAILS.`child_without_bed_charges`, ROOM_DETAILS.`child_with_bed_charges`, ROOM_DETAILS.`breakfast_required`, ROOM_DETAILS.`lunch_required`, ROOM_DETAILS.`dinner_required`, ROOM_DETAILS.`breakfast_cost_per_person`, ROOM_DETAILS.`lunch_cost_per_person`, ROOM_DETAILS.`dinner_cost_per_person`, HOTEL_DETAILS.`itinerary_plan_hotel_details_ID`, HOTEL_DETAILS.`itinerary_plan_id`, HOTEL_DETAILS.`itinerary_route_id`, HOTEL_DETAILS.`itinerary_route_date`, HOTEL_DETAILS.`itinerary_route_location`, HOTEL_DETAILS.`hotel_required`, HOTEL_DETAILS.`hotel_category_id`, HOTEL_DETAILS.`hotel_id`, HOTEL_DETAILS.`hotel_margin_percentage`, HOTEL_DETAILS.`hotel_margin_gst_type`, HOTEL_DETAILS.`hotel_margin_gst_percentage`, HOTEL_DETAILS.`hotel_margin_rate`, HOTEL_DETAILS.`hotel_margin_rate_tax_amt`, HOTEL_DETAILS.`hotel_breakfast_cost`, HOTEL_DETAILS.`hotel_lunch_cost`, HOTEL_DETAILS.`hotel_dinner_cost`, HOTEL_DETAILS.`total_no_of_persons`, HOTEL_DETAILS.`total_hotel_meal_plan_cost`, HOTEL_DETAILS.`total_no_of_rooms`, HOTEL_DETAILS.`total_room_cost`, HOTEL_DETAILS.`total_extra_bed_cost`, HOTEL_DETAILS.`total_childwith_bed_cost`, HOTEL_DETAILS.`total_childwithout_bed_cost`, HOTEL_DETAILS.`total_room_gst_amount`, HOTEL_DETAILS.`total_hotel_cost`, HOTEL_DETAILS.`total_hotel_tax_amount`, HOTEL_DETAILS.`total_amenities_cost`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_breakfast_cost_gst_amount`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_lunch_cost_gst_amount`, HOTEL_DETAILS.`total_amenities_gst_amount`, HOTEL_DETAILS.`hotel_dinner_cost_gst_amount`, HOTEL_DETAILS.`total_hotel_meal_plan_cost_gst_amount`, HOTEL_DETAILS.`total_extra_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwith_bed_cost_gst_amount`, HOTEL_DETAILS.`total_childwithout_bed_cost_gst_amount` FROM `dvi_itinerary_plan_hotel_details` HOTEL_DETAILS LEFT JOIN `dvi_itinerary_plan_hotel_room_details` ROOM_DETAILS ON ROOM_DETAILS.`itinerary_plan_hotel_details_id` = HOTEL_DETAILS.`itinerary_plan_hotel_details_ID` AND ROOM_DETAILS.`group_type` = '$itinerary_group_TYPE' WHERE HOTEL_DETAILS.`deleted` = '0' AND HOTEL_DETAILS.`status` = '1' AND HOTEL_DETAILS.`itinerary_plan_id` = '$itinerary_plan_ID' AND HOTEL_DETAILS.`group_type` = '$itinerary_group_TYPE' GROUP BY HOTEL_DETAILS.`itinerary_route_date` ORDER BY HOTEL_DETAILS.`itinerary_route_date` ASC") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
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
                            $itinerary_route_location = $fetch_hotel_data['itinerary_route_location'];
                            $hotel_required = $fetch_hotel_data['hotel_required'];
                            $gst_type = $fetch_hotel_data['gst_type'];
                            $gst_percentage = $fetch_hotel_data['gst_percentage'];
                            $hotel_category_id = $fetch_hotel_data['hotel_category_id'];
                            $selected_hotel_id = $fetch_hotel_data['hotel_id'];
                            $hotel_margin_percentage = $fetch_hotel_data['hotel_margin_percentage'];
                            $hotel_margin_gst_type = $fetch_hotel_data['hotel_margin_gst_type'];
                            $hotel_margin_gst_percentage = $fetch_hotel_data['hotel_margin_gst_percentage'];
                            $hotel_margin_rate = round($fetch_hotel_data['hotel_margin_rate']);
                            $hotel_margin_rate_tax_amt = round($fetch_hotel_data['hotel_margin_rate_tax_amt']);
                            $hotel_breakfast_cost = round($fetch_hotel_data['hotel_breakfast_cost']);
                            $hotel_lunch_cost = round($fetch_hotel_data['hotel_lunch_cost']);
                            $hotel_dinner_cost = round($fetch_hotel_data['hotel_dinner_cost']);
                            $total_no_of_persons = $fetch_hotel_data['total_no_of_persons'];
                            $total_hotel_meal_plan_cost = round($fetch_hotel_data['total_hotel_meal_plan_cost']);
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

                            $preferred_room_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'preferred_room_count');

                            $breakfast_required = $fetch_hotel_data['breakfast_required'];
                            $lunch_required = $fetch_hotel_data['lunch_required'];
                            $dinner_required = $fetch_hotel_data['dinner_required'];
                            $breakfast_cost_per_person = $fetch_hotel_data['breakfast_cost_per_person'];
                            $lunch_cost_per_person = $fetch_hotel_data['lunch_cost_per_person'];
                            $dinner_cost_per_person = $fetch_hotel_data['dinner_cost_per_person'];

                            if ($hotel_required != 1) :
                                $hotel_meal_label = 'EP';
                            elseif ($breakfast_required && $breakfast_cost_per_person > 0) :
                                if ($lunch_required && $lunch_cost_per_person > 0 && $dinner_required && $dinner_cost_per_person > 0) :
                                    $hotel_meal_label = 'AP';
                                elseif (($lunch_required && $lunch_cost_per_person > 0) || ($dinner_required && $dinner_cost_per_person > 0)) :
                                    $hotel_meal_label = 'MAP';
                                else :
                                    $hotel_meal_label = 'CP';
                                endif;
                            else :
                                $hotel_meal_label = 'EP';
                            endif;

                            if(empty($itinerary_route_location)):
                                $itinerary_route_location = getITINEARYROUTE_DETAILS($itinerary_plan_ID,$itinerary_route_id,'next_visiting_location','');
                            endif;
                            
                            $payload = [
                                'detailsId'              => (int)$itinerary_plan_hotel_details_ID,
                                'planHotelRoomDetailsId' => (int)$itinerary_plan_hotel_room_details_ID,
                                'planId'                 => (int)$itinerary_plan_ID,
                                'routeDate'              => (string)$itinerary_route_date,
                                'routeId'                => (int)$itinerary_route_id,
                                'groupType'              => (string)$group_type,
                                'selectedHotelId'        => (int)$selected_hotel_id,
                                // totals / counts / flags
                                'totalHotelCost'         => (float)$total_hotel_cost,
                                'totalHotelTaxAmount'    => (float)$total_hotel_tax_amount,
                                'hotelId'                => (int)$hotel_id,
                                'totalNoOfRooms'         => (int)$total_no_of_rooms,
                                'totalExtraBed'          => (int)$total_extra_bed,
                                'totalChildWithBed'      => (int)$total_child_with_bed,
                                'totalChildWithoutBed'   => (int)$total_child_without_bed,
                                'breakfastRequired'      => (int)$breakfast_required,
                                'lunchRequired'          => (int)$lunch_required,
                                'dinnerRequired'         => (int)$dinner_required,
                                'totalNoOfPersons'       => (int)$total_no_of_persons,
                                'gstType'                => (string)$gst_type,
                                'gstPercentage'          => (float)$gst_percentage,
                                'hotelMarginPercentage'  => (float)$hotel_margin_percentage,
                                'hotelMarginGstType'     => (string)$hotel_margin_gst_type,
                                'hotelMarginGstPercentage' => (float)$hotel_margin_gst_percentage,
                                'preferredRoomCount'     => (int)$preferred_room_count,
                            ];
                    ?>
                            <input type="hidden" name="hid_group_type" id="hid_group_type" value="<?= $group_type ?>" />
                            <tr class="cursor-pointer" data-hotel-counter="<?= $itinerary_plan_hotel_details_ID; ?>" onclick='showHOTELROOMDETAILSPANE(<?= htmlspecialchars(json_encode($payload), ENT_QUOTES, "UTF-8"); ?>)'>
                                <td style="max-width: 140px;">Day <?= $hotel_counter; ?> | <?= dateformat_datepicker($itinerary_route_date); ?></td>
                                <td style="max-width: 200px;" class="text-truncate">
                                    <span data-toggle="tooltip" placement="top" title="<?= $itinerary_route_location; ?>">&nbsp;&nbsp;<?= $itinerary_route_location; ?></span>
                                </td>

                                <td <?= $add_colspan_hotel_name; ?> style="max-width: 80px;" class="text-truncate">
                                    <?php if ($hotel_required == 1) : ?>
                                        <span data-toggle="tooltip" placement="top" title="<?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?> - <?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?>"><i class="fa-solid fa-hotel me-1 hotelIcon"></i><?= getHOTELDETAILS($selected_hotel_id, 'HOTEL_NAME'); ?> - <?= getHOTEL_CATEGORY_DETAILS($hotel_category_id, 'label'); ?></span>
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
                                                <p class="mb-0 mr-5" data-toggle="tooltip" placement="top" title="<?= $amenities_title; ?>"><?= limit_words($amenities_title, 2) . ' <small>(' . $total_qty . ' x ' . general_currency_symbol . ' ' . number_format($amenitie_rate, 2) . ')</small>'; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
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
                                            <?php if ($hotel_rates_visibility == 1): ?>
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
                            $grand_total_hotel_charges = (float)getHOTEL_ITINEARY_PLAN_DETAILS($itinerary_plan_ID, $itinerary_group_TYPE, 'GRAND_TOTAL_OF_THE_HOTEL_CHARGES');

                            // Check if additional margin needs to be applied
                            if ($itinerary_no_of_days <= $itinerary_additional_margin_day_limit && $logged_agent_id) {
                                // Calculate additional margin
                                $additional_hotel_margin = ($itinerary_additional_margin_percentage * $grand_total_hotel_charges) / 100;
                            } else {
                                $additional_hotel_margin = 0;
                            }

                            // Format the number
                            $formatted_grand_total = number_format(($grand_total_hotel_charges + $additional_hotel_margin), 2);
                            ?>
                            <h6 class="mb-0"><strong><span><?= general_currency_symbol . ' ' . $formatted_grand_total; ?></span></strong></h6>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <script>
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
                    url: "engine/ajax/ajax_show_recommended_hotel_details_form.php?type=show_form",
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
                var _agent_margin_input_data = $('.agent-margin-input').val();
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_overall_cost_details.php?type=show_grand_itineary_total",
                    data: {
                        _itinerary_plan_ID: itinerary_plan_ID,
                        _groupTYPE: group_type,
                        _agent_margin_input_data: _agent_margin_input_data
                    },
                    success: function(response) {
                        $('#overall_trip_cost').text('');
                        $('#overall_trip_cost').text(response);
                    }
                });
            }

            function showHOTELROOMDETAILSPANE(p) {
                if (!p || !p.detailsId) {
                    console.warn('Missing payload/detailsId');
                    return;
                }

                var $tbody = $('#itineary_hotel_LIST');
                var $host = $tbody.find('tr[data-hotel-counter="' + p.detailsId + '"]');
                if (!$host.length) return;

                // --- settings ---
                var closeOthers = false; // set true to keep only one open at a time
                var reqTimeout = 80000; // ms

                // compute colspan (accounts for existing colspans)
                var colSpan = 0;
                $host.children('td:visible').each(function() {
                    colSpan += this.colSpan || 1;
                });
                if (!colSpan) {
                    $tbody.closest('table').find('> thead > tr:first > th:visible')
                        .each(function() {
                            colSpan += this.colSpan || 1;
                        });
                    if (!colSpan) colSpan = 1;
                }

                var rowId = 'roomsRow_' + p.detailsId;
                var boxId = 'roomsBox_' + p.detailsId;

                if (closeOthers) $tbody.find('tr.rooms-row:visible').not('#' + rowId).hide();

                // create or reuse the details row directly under the host row
                var $row = $('#' + rowId);
                if (!$row.length) {
                    $row = $('<tr id="' + rowId + '" class="rooms-row" style="display:none;">' +
                        '<td colspan="' + colSpan + '"><div id="' + boxId + '"></div></td>' +
                        '</tr>');
                    $host.after($row);
                } else {
                    $row.find('td').attr('colspan', colSpan);
                    if ($row.prev()[0] !== $host[0]) $host.after($row);
                }

                var $box = $('#' + boxId);

                // toggle closed if already open and cached
                if ($row.is(':visible') && $row.data('loaded')) {
                    $row.hide();
                    return;
                }

                // abort any in-flight request for this row
                var prevXhr = $row.data('xhr');
                if (prevXhr && prevXhr.readyState !== 4) {
                    try {
                        prevXhr.abort();
                    } catch (e) {}
                }

                // show row + spinner
                $row.show();
                $box.attr('aria-busy', 'true').html(
                    '<div class="py-3 text-center">' +
                    '<div class="spinner-border" role="status" aria-hidden="true"></div>' +
                    '<div class="small mt-2">Loading hotel details…</div>' +
                    '</div>'
                );

                const keyMap = {
                    detailsId: '_itinerary_plan_hotel_details_ID',
                    planHotelRoomDetailsId: '_itinerary_plan_hotel_room_details_ID',
                    planId: '_itinerary_plan_ID',
                    routeDate: '_itinerary_route_date',
                    routeId: '_itinerary_route_id',
                    groupType: '_groupTYPE',
                    selectedHotelId: '_selected_hotel_id',
                    totalHotelCost: '_total_hotel_cost',
                    totalHotelTaxAmount: '_total_hotel_tax_amount',
                    totalNoOfRooms: '_total_no_of_rooms',
                    totalExtraBed: '_total_extra_bed',
                    totalChildWithBed: '_total_child_with_bed',
                    totalChildWithoutBed: '_total_child_without_bed',
                    breakfastRequired: '_breakfast_required',
                    lunchRequired: '_lunch_required',
                    dinnerRequired: '_dinner_required',
                    totalNoOfPersons: '_total_no_of_persons',
                    gstType: '_gst_type',
                    gstPercentage: '_gst_percentage',
                    hotelMarginPercentage: '_hotel_margin_percentage',
                    hotelMarginGstType: '_hotel_margin_gst_type',
                    hotelMarginGstPercentage: '_hotel_margin_gst_percentage',
                    preferredRoomCount: '_preferred_room_count'
                };

                const data = {};
                for (const [k, v] of Object.entries(keyMap)) {
                    if (p[k] != null && p[k] !== '') data[v] = p[k];
                }

                var xhr = $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_itineary_hotel_roomdetails.php?type=show_form",
                    timeout: reqTimeout,
                    data: data,
                    success: function(html) {
                        // If your PHP sometimes returns a full <tr>…</tr>, you could:
                        // if (/^\s*<tr[\s>]/i.test(html)) { $row.replaceWith(html); return; }

                        $box.html(html);
                        $row.data('loaded', true); // cache flag for instant toggle
                    },
                    error: function(xhr) {
                        var msg = 'Failed to load room details';
                        if (xhr && xhr.status) msg += ' (' + xhr.status + ')';
                        $box.html('<div class="p-3 text-danger text-center">' + msg + '</div>');
                    },
                    complete: function() {
                        $row.removeData('xhr');
                        $box.removeAttr('aria-busy');
                    }
                });

                $row.data('xhr', xhr);
            }

            function showOVERALL_COST_DETAILS(itinerary_plan_ID, group_type) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/ajax_latest_itineary_overall_cost_details.php?type=show_form",
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
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
