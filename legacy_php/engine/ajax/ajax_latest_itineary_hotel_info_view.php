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

        $hotel_ID = $_GET['hotel_ID'];
        $selected_hotel_id = $_GET['selected_hotel_id'];
        $all_meal_plan = $_GET['all_meal_plan'];
        $breakfast_meal_plan = $_GET['breakfast_meal_plan'];
        $lunch_meal_plan = $_GET['lunch_meal_plan'];
        $dinner_meal_plan = $_GET['dinner_meal_plan'];
        $choosen_room_type = $_GET['choosen_room_type'];
        $itinerary_plan_id = $_GET['itinerary_plan_id'];
        $itinerary_route_id = $_GET['itinerary_route_id'];
        $group_type = $_GET['group_type'];

        $get_hotel_name = getHOTELDETAILS($hotel_ID, 'HOTEL_NAME');
        $hotel_category = getHOTELDETAILS($hotel_ID, 'hotel_category');
        $hotel_place = getHOTELDETAILS($hotel_ID, 'hotel_place');
        $hotel_category_name = getHOTEL_CATEGORY_DETAILS($hotel_category, 'label');

?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="right: 30px; top: 30px;"></button>
        <div class="row">
            <div class="col-lg-12 d-flex">
                <div class="col-lg-4 position-relative">
                    <div id="carouselExampleDark" class="carousel carousel-light slide carousel-fade h-100" data-bs-ride="carousel">
                        <?php
                        $select_hotel_room_gallery_list_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                        $total_hotel_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_room_gallery_list_query);
                        ?>
                        <div class="carousel-indicators">
                            <?php for ($image_count = 1; $image_count <= $total_hotel_gallery_num_rows_count; $image_count++) : ?>
                                <button type="button" data-target="#carouselExampleDark" data-slide-to="<?= $image_count; ?>" class="active" aria-label="Slide <?= $image_count; ?>" aria-current="true"></button>
                            <?php endfor; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php
                            if ($total_hotel_gallery_num_rows_count > 0) :
                                while ($fetch_hotel_room_gallery_data = sqlFETCHARRAY_LABEL($select_hotel_room_gallery_list_query)) :
                                    $image_counter++;
                                    $hotel_room_gallery_details_id = $fetch_hotel_room_gallery_data['hotel_room_gallery_details_id'];
                                    $room_gallery_name = $fetch_hotel_room_gallery_data['room_gallery_name'];
                                    $hotel_room_photo_url = BASEPATH . 'uploads/room_gallery/' . $room_gallery_name;
                                    $active_class = $image_counter === 1 ? 'active' : '';
                            ?>
                                    <div class="carousel-item <?= $active_class; ?> rounded-start">
                                        <img class="d-block w-100 rounded-start" style="height: 420px;" src="<?= $hotel_room_photo_url; ?>" alt="<?= $room_gallery_name; ?>">
                                    </div>
                            <?php endwhile;
                            endif; ?>
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleDark" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleDark" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                    <div class="overlay overlay_image_wrapper pb-3 rounded-start" style="z-index: 99;">
                        <h6 class="mb-0"><?= $get_hotel_name; ?></h6>
                        <h6 class="mb-0 text-muted" style="font-size: 12px;"><?= $hotel_category_name; ?></h6>
                        <hr class="my-1 w-100 text-muted">
                        <h6 class="mb-0" style="font-size: 12px;"><?= $hotel_place; ?></h6>
                    </div>
                </div>
                <?php if ($hotel_ID == $selected_hotel_id) : ?>
                    <div class="col-lg-8 py-3 px-4">
                        <div class="col-lg-12">
                            <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Room Details</strong></h5>
                        </div>
                        <div class="col-lg-12">
                            <div id="carouselExampleControls" class="carousel slide roomDetailsCarousel">
                                <div class="carousel-inner">
                                    <?php
                                    $selected_itineary_hotel_room_details_query = sqlQUERY_LABEL("SELECT `itinerary_plan_hotel_room_details_ID`, `hotel_id`, `room_type_id`, `room_id`,`room_rate`, `gst_type`, `gst_percentage`, `extra_bed_count`, `extra_bed_rate`, `child_without_bed_count`, `child_without_bed_charges`, `child_with_bed_count`, `child_with_bed_charges`, `breakfast_required`, `lunch_required`, `dinner_required`, `total_breafast_cost`, `total_lunch_cost`, `total_dinner_cost`, `total_room_cost`, `total_room_gst_amount` FROM `dvi_itinerary_plan_hotel_room_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                                    $total_selected_room_count = sqlNUMOFROW_LABEL($selected_itineary_hotel_room_details_query);
                                    if ($total_selected_room_count > 0) :
                                        while ($fetch_hotel_room_details_data = sqlFETCHARRAY_LABEL($selected_itineary_hotel_room_details_query)) :
                                            $room_counter++;
                                            $itinerary_plan_hotel_room_details_ID = $fetch_hotel_room_details_data['itinerary_plan_hotel_room_details_ID'];
                                            $hotel_id = $fetch_hotel_room_details_data['hotel_id'];
                                            $room_type_id = $fetch_hotel_room_details_data['room_type_id'];
                                            $room_id = $fetch_hotel_room_details_data['room_id'];
                                            $room_rate = $fetch_hotel_room_details_data['room_rate'];
                                            $gst_type = $fetch_hotel_room_details_data['gst_type'];
                                            $gst_percentage = $fetch_hotel_room_details_data['gst_percentage'];
                                            $extra_bed_count = $fetch_hotel_room_details_data['extra_bed_count'];
                                            $extra_bed_rate = $fetch_hotel_room_details_data['extra_bed_rate'];
                                            $child_without_bed_count = $fetch_hotel_room_details_data['child_without_bed_count'];
                                            $child_without_bed_charges = $fetch_hotel_room_details_data['child_without_bed_charges'];
                                            $child_with_bed_count = $fetch_hotel_room_details_data['child_with_bed_count'];
                                            $child_with_bed_charges = $fetch_hotel_room_details_data['child_with_bed_charges'];
                                            $breakfast_required = $fetch_hotel_room_details_data['breakfast_required'];
                                            $lunch_required = $fetch_hotel_room_details_data['lunch_required'];
                                            $dinner_required = $fetch_hotel_room_details_data['dinner_required'];
                                            $total_breafast_cost = $fetch_hotel_room_details_data['total_breafast_cost'];
                                            $total_lunch_cost = $fetch_hotel_room_details_data['total_lunch_cost'];
                                            $total_dinner_cost = $fetch_hotel_room_details_data['total_dinner_cost'];
                                            $total_room_cost = $fetch_hotel_room_details_data['total_room_cost'];
                                            $total_room_gst_amount = $fetch_hotel_room_details_data['total_room_gst_amount'];
                                            if ($gst_type == 1) :
                                                $gst_type_label = 'Inclusive';
                                            elseif ($gst_type == 2) :
                                                $gst_type_label = 'Exclusive';
                                            endif;
                                            if ($room_counter == 1) :
                                                $active_class = 'active';
                                            else :
                                                $active_class = '';
                                            endif;
                                            $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_id, $room_id, '', 'get_room_gallery_1st_IMG');
                                            if ($get_room_gallery_1st_IMG) :
                                                $get_room_gallery_1st_IMG = $get_room_gallery_1st_IMG;
                                            else :
                                                $get_room_gallery_1st_IMG = 'no_image_for_room.png';
                                            endif;
                                            $hotel_room_photo_url = BASEPATH . 'uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                    ?>
                                            <div class="carousel-item <?= $active_class; ?>">
                                                <div class="col-lg-12">
                                                    <div class="card mb-3" style="border: 1px solid lightgray;">
                                                        <div class="row g-0">
                                                            <div class="col-md-4 position-relative">
                                                                <img class="card-img card-img-left" src="<?= $hotel_room_photo_url ?>" style="height: 100%;" alt="Card image">
                                                                <div class="creative-pool position-absolute top-0 start-0 w-100">
                                                                    <div class="purple-badge">
                                                                        <span>#<?= $room_counter; ?> | <?= getROOMTYPE($room_type_id, 'label'); ?> - <?= general_currency_symbol . ' ' . number_format(($total_room_cost + $total_room_gst_amount), 2); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card-body">
                                                                    <div class="col-lg-12 d-flex flex-wrap">
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Room Rate</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($room_rate, 2); ?></h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">GST (%)</p>
                                                                            <h6 class="m-0"><?= $gst_percentage . '%  | ' . $gst_type_label; ?></h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Extra Bed Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($extra_bed_rate, 2); ?> (<?= $extra_bed_count; ?> Qty)</h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Child Without Bed Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($child_without_bed_charges, 2); ?> (<?= $child_without_bed_count; ?> Qty)</h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Child With Bed Charges </p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($child_with_bed_charges, 2); ?> (<?= $child_with_bed_count; ?> Qty)</h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Total Food Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($total_breafast_cost + $total_lunch_cost + $total_dinner_cost, 2); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php endwhile;
                                    endif; ?>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-12 d-flex justify-content-center">
                            <div class="divider m-0 col-lg-8">
                                <div class="divider-text">
                                    <i class="ti ti-bed"></i>
                                </div>
                            </div>
                        </div>
                        <?php
                        $selected_itineary_hotel_details_query = sqlQUERY_LABEL("SELECT `hotel_margin_percentage`, `hotel_margin_gst_type`, `hotel_margin_rate`, `hotel_margin_rate_tax_amt`, `hotel_breakfast_cost`, `hotel_breakfast_cost_gst_amount`, `hotel_lunch_cost`, `hotel_lunch_cost_gst_amount`, `hotel_dinner_cost`, `hotel_dinner_cost_gst_amount`, `total_hotel_meal_plan_cost`, `total_hotel_meal_plan_cost_gst_amount`, `total_extra_bed_cost`, `total_extra_bed_cost_gst_amount`, `total_childwith_bed_cost`, `total_childwith_bed_cost_gst_amount`, `total_childwithout_bed_cost`, `total_childwithout_bed_cost_gst_amount`, `total_no_of_rooms`, `total_room_cost`, `total_room_gst_amount`, `total_amenities_cost`, `total_amenities_gst_amount`, `total_hotel_cost`, `total_hotel_tax_amount` FROM `dvi_itinerary_plan_hotel_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_id` = '$itinerary_plan_id' AND `itinerary_route_id` = '$itinerary_route_id' AND `group_type` = '$group_type'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                        while ($fetch_hotel_room_details_data = sqlFETCHARRAY_LABEL($selected_itineary_hotel_details_query)) :
                            $hotel_margin_percentage = $fetch_hotel_room_details_data['hotel_margin_percentage'];
                            $hotel_margin_gst_type = $fetch_hotel_room_details_data['hotel_margin_gst_type'];
                            $hotel_margin_rate = $fetch_hotel_room_details_data['hotel_margin_rate'];
                            $hotel_margin_rate_tax_amt = $fetch_hotel_room_details_data['hotel_margin_rate_tax_amt'];
                            $hotel_breakfast_cost = $fetch_hotel_room_details_data['hotel_breakfast_cost'];
                            $hotel_breakfast_cost_gst_amount = $fetch_hotel_room_details_data['hotel_breakfast_cost_gst_amount'];
                            $hotel_lunch_cost = $fetch_hotel_room_details_data['hotel_lunch_cost'];
                            $hotel_lunch_cost_gst_amount = $fetch_hotel_room_details_data['hotel_lunch_cost_gst_amount'];
                            $hotel_dinner_cost = $fetch_hotel_room_details_data['hotel_dinner_cost'];
                            $hotel_dinner_cost_gst_amount = $fetch_hotel_room_details_data['hotel_dinner_cost_gst_amount'];
                            $total_hotel_meal_plan_cost = $fetch_hotel_room_details_data['total_hotel_meal_plan_cost'];
                            $total_hotel_meal_plan_cost_gst_amount = $fetch_hotel_room_details_data['total_hotel_meal_plan_cost_gst_amount'];
                            $total_extra_bed_cost = $fetch_hotel_room_details_data['total_extra_bed_cost'];
                            $total_extra_bed_cost_gst_amount = $fetch_hotel_room_details_data['total_extra_bed_cost_gst_amount'];
                            $total_childwith_bed_cost = $fetch_hotel_room_details_data['total_childwith_bed_cost'];
                            $total_childwith_bed_cost_gst_amount = $fetch_hotel_room_details_data['total_childwith_bed_cost_gst_amount'];
                            $total_childwithout_bed_cost = $fetch_hotel_room_details_data['total_childwithout_bed_cost'];
                            $total_childwithout_bed_cost_gst_amount = $fetch_hotel_room_details_data['total_childwithout_bed_cost_gst_amount'];
                            $total_no_of_rooms = $fetch_hotel_room_details_data['total_no_of_rooms'];
                            $total_room_cost = $fetch_hotel_room_details_data['total_room_cost'];
                            $total_room_gst_amount = $fetch_hotel_room_details_data['total_room_gst_amount'];
                            $total_amenities_cost = $fetch_hotel_room_details_data['total_amenities_cost'];
                            $total_amenities_gst_amount = $fetch_hotel_room_details_data['total_amenities_gst_amount'];
                            $total_hotel_cost = $fetch_hotel_room_details_data['total_hotel_cost'];
                            $total_hotel_tax_amount = $fetch_hotel_room_details_data['total_hotel_tax_amount'];
                        endwhile;
                        ?>
                        <div class="col-lg-12">
                            <div class="d-flex align-items-center justify-content-between my-1">
                                <p class="mb-0">Total Room Cost</p>
                                <p class="mb-0"><?= general_currency_symbol . ' ' . number_format(($total_room_cost), 2); ?></p>
                            </div>
                            <?php if ($hotel_breakfast_cost > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Breakfast Cost</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($hotel_breakfast_cost, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hotel_lunch_cost > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Lunch Cost</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($hotel_lunch_cost, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hotel_dinner_cost > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Dinner Cost</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($hotel_dinner_cost, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($total_extra_bed_cost > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Extra Bed Cost</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($total_extra_bed_cost, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($total_childwith_bed_cost > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Child with Bed Cost</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($total_childwith_bed_cost, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($total_childwithout_bed_cost > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Child without Cost</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($total_childwithout_bed_cost, 2); ?></p>
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
                                    <div class="d-flex align-items-center justify-content-between my-1">
                                        <p class="mb-0" data-toggle="tooltip" placement="top" title="<?= $amenities_title; ?>"><?= limit_words($amenities_title, 2) . ' <small>(' . $total_qty . ' x ' . general_currency_symbol . ' ' . number_format($amenitie_rate, 2) . ')</small>'; ?></p>
                                        <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($total_amenitie_cost, 2); ?></p>
                                    </div>
                            <?php
                                endwhile;
                            endif;
                            ?>
                            <?php if ($total_room_gst_amount > 0 || $total_amenities_gst_amount > 0 || $hotel_breakfast_cost_gst_amount > 0 || $hotel_lunch_cost_gst_amount > 0 || $hotel_dinner_cost_gst_amount > 0 || $total_extra_bed_cost_gst_amount > 0 || $total_childwith_bed_cost_gst_amount > 0 || $total_childwithout_bed_cost_gst_amount > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Total Tax</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($total_room_gst_amount + $total_amenities_gst_amount + $hotel_breakfast_cost_gst_amount + $hotel_lunch_cost_gst_amount + $hotel_dinner_cost_gst_amount + $total_extra_bed_cost_gst_amount + $total_childwith_bed_cost_gst_amount + $total_childwithout_bed_cost_gst_amount, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hotel_margin_rate > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Hotel Margin (<?= $hotel_margin_percentage; ?>%)</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($hotel_margin_rate, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hotel_margin_rate_tax_amt > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <p class="mb-0">Service Tax for Hotel Margin</p>
                                    <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($hotel_margin_rate_tax_amt, 2); ?></p>
                                </div>
                            <?php endif; ?>
                            <hr class="my-1">
                            <?php if ($total_hotel_cost > 0 || $total_hotel_tax_amount > 0) : ?>
                                <div class="d-flex align-items-center justify-content-between my-1">
                                    <h5 class="mb-0"><b>Grand Total</b></h5>
                                    <h5 class="mb-0 text-primary"><b><?= general_currency_symbol . ' ' . number_format($total_hotel_cost + $total_hotel_tax_amount, 2); ?></b></h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="col-lg-8 py-3 px-4">
                        <div class="col-lg-12">
                            <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Room Details</strong></h5>
                        </div>
                        <div class="col-lg-12">
                            <div id="carouselExampleControls" class="carousel slide roomDetailsCarousel">
                                <div class="carousel-inner">
                                    <?php
                                    $select_hotel_room_details_query = sqlQUERY_LABEL("SELECT `room_ID`, `hotel_id`, `room_type_id`, `gst_type`, `gst_percentage`, `extra_bed_charge`, `child_with_bed_charge`, `child_without_bed_charge` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' AND `status` = '1' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                                    $total_no_of_room_count = sqlNUMOFROW_LABEL($select_hotel_room_details_query);
                                    if ($total_no_of_room_count > 0) :
                                        while ($fetch_hotel_room_details_data = sqlFETCHARRAY_LABEL($select_hotel_room_details_query)) :
                                            $room_counter++;
                                            $room_ID = $fetch_hotel_room_details_data['room_ID'];
                                            $hotel_id = $fetch_hotel_room_details_data['hotel_id'];
                                            $room_type_id = $fetch_hotel_room_details_data['room_type_id'];
                                            $gst_type = $fetch_hotel_room_details_data['gst_type'];
                                            $gst_percentage = $fetch_hotel_room_details_data['gst_percentage'];
                                            $extra_bed_charge = $fetch_hotel_room_details_data['extra_bed_charge'];
                                            $child_with_bed_charge = $fetch_hotel_room_details_data['child_with_bed_charge'];
                                            $child_without_bed_charges = $fetch_hotel_room_details_data['child_without_bed_charges'];

                                            $total_extra_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'total_extra_bed');

                                            $total_child_with_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'total_child_with_bed');
                                            $total_child_without_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'total_child_without_bed');
                                            $itinerary_route_date = getITINEARYROUTE_DETAILS($itinerary_plan_id, $itinerary_route_id, 'itinerary_route_date');

                                            $food_required_count = get_ITINERARY_PLAN_DETAILS($itinerary_plan_id, 'total_adult_n_children_count');

                                            $pricebook_year = date('Y', strtotime($itinerary_route_date));
                                            $pricebook_month = date('F', strtotime($itinerary_route_date));
                                            $formatted_day = 'day_' . date('j', strtotime($itinerary_route_date));

                                            $price_per_night = getROOM_PRICEBOOK_DETAILS($hotel_ID, $room_ID, $pricebook_year, $pricebook_month, $formatted_day, 'room_rate_for_the_day');

                                            $hotel_margin = getHOTELDETAILS($hotel_ID, 'hotel_margin');
                                            $hotel_margin_gst_type = getHOTELDETAILS($hotel_ID, 'hotel_margin_gst_type');
                                            $hotel_margin_gst_percentage = getHOTELDETAILS($hotel_ID, 'hotel_margin_gst_percentage');

                                            if ($all_meal_plan) :
                                                $total_breafast_cost = $food_required_count * $hotel_breafast_cost;
                                                $total_lunch_cost = $food_required_count * $hotel_lunch_cost;
                                                $total_dinner_cost = $food_required_count * $hotel_dinner_cost;
                                            else :
                                                $total_breafast_cost = ($breakfast_meal_plan) ? $food_required_count * $hotel_breafast_cost : 0;
                                                $total_lunch_cost = ($lunch_meal_plan) ? $food_required_count * $hotel_lunch_cost : 0;
                                                $total_dinner_cost = ($dinner_meal_plan) ? $food_required_count * $hotel_dinner_cost : 0;
                                            endif;

                                            $total_hotel_meal_plan_cost = $total_breafast_cost + $total_lunch_cost + $total_dinner_cost;

                                            $total_extra_bed_charges = ($extra_bed_charge * $total_extra_bed);
                                            $total_child_without_bed_charge = ($child_without_bed_charge * $total_child_without_bed);
                                            $total_child_with_bed_charges = ($child_with_bed_charge * $total_child_with_bed);

                                            $total_room_cost = ($price_per_night + $total_extra_bed_charges + $total_child_without_bed_charge + $total_child_with_bed_charges + $total_breafast_cost + $total_lunch_cost + $total_dinner_cost);

                                            if ($gst_type == 1) :
                                                // For Inclusive GST
                                                $new_room_tax_amt = ($total_room_cost * $gst_percentage / 100);
                                                $new_room_amount = ($total_room_cost - $new_room_tax_amt);
                                            elseif ($gst_type == 2) :
                                                // For Exclusive GST
                                                $new_room_tax_amt = ($total_room_cost * $gst_percentage / 100);
                                                $new_room_amount = $total_room_cost;
                                            endif;

                                            if ($hotel_margin > 0) :
                                                // Calculate hotel margin rate
                                                $hotel_margin_rate = ($total_room_cost * $hotel_margin) / 100;
                                            else :
                                                $hotel_margin_rate = 0;
                                            endif;

                                            if ($hotel_margin_rate > 0) :
                                                // Calculate new margin amount and room tax amount based on GST type
                                                if ($hotel_margin_gst_type == 1) :
                                                    // For Inclusive GST
                                                    $new_margin_tax_amt = ($hotel_margin_rate * $hotel_margin_gst_percentage / 100);
                                                    $new_margin_amount = ($hotel_margin_rate - $new_margin_tax_amt);
                                                elseif ($hotel_margin_gst_type == 2) :
                                                    // For Exclusive GST
                                                    $new_margin_tax_amt = ($hotel_margin_rate * $hotel_margin_gst_percentage / 100);
                                                    $new_margin_amount = $hotel_margin_rate;
                                                endif;
                                            else :
                                                $new_margin_amount = $hotel_margin_rate;
                                                $new_margin_tax_amt = 0;
                                            endif;

                                            if ($gst_type == 1) :
                                                $gst_type_label = 'Inclusive';
                                            elseif ($gst_type == 2) :
                                                $gst_type_label = 'Exclusive';
                                            endif;
                                            if ($room_counter == 1) :
                                                $active_class = 'active';
                                            else :
                                                $active_class = '';
                                            endif;

                                            $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_id, $room_ID, '', 'get_room_gallery_1st_IMG');
                                            if ($get_room_gallery_1st_IMG) :
                                                $get_room_gallery_1st_IMG = $get_room_gallery_1st_IMG;
                                            else :
                                                $get_room_gallery_1st_IMG = 'no_image_for_room.png';
                                            endif;
                                            $hotel_room_photo_url = BASEPATH . 'uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                    ?>
                                            <div class="carousel-item <?= $active_class; ?>">
                                                <div class="col-lg-12">
                                                    <div class="card mb-3" style="border: 1px solid lightgray;">
                                                        <div class="row g-0">
                                                            <div class="col-md-4 position-relative">
                                                                <img class="card-img card-img-left" src="<?= $hotel_room_photo_url ?>" style="height: 100%;" alt="Card image">
                                                                <div class="creative-pool position-absolute top-0 start-0 w-100">
                                                                    <div class="purple-badge">
                                                                        <span><?= getROOMTYPE($room_type_id, 'label'); ?> - <?= general_currency_symbol . ' ' . number_format(($new_room_amount + $new_room_tax_amt), 2); ?></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card-body">
                                                                    <div class="col-lg-12 d-flex flex-wrap">
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Room Rate</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($price_per_night, 2); ?></h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">GST (%)</p>
                                                                            <h6 class="m-0"><?= $gst_percentage . '%  | ' . $gst_type_label; ?></h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Extra Bed Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($total_extra_bed_charges, 2); ?> (<?= $total_extra_bed; ?> Qty)</h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Child Without Bed Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($total_child_with_bed_charge, 2); ?> (<?= $total_child_without_bed; ?> Qty)</h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Child with Bed Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($total_child_with_bed_charges, 2); ?> (<?= $total_child_with_bed; ?> Qty)</h6>
                                                                        </div>
                                                                        <div class="col-lg-6 my-2">
                                                                            <p class="m-0 text-muted" style="font-size: 13px;">Total Food Charges</p>
                                                                            <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($total_breafast_cost + $total_lunch_cost + $total_dinner_cost, 2); ?></h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 d-flex justify-content-center">
                                                        <div class="divider m-0 col-lg-8">
                                                            <div class="divider-text">
                                                                <i class="ti ti-bed"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="d-flex align-items-center justify-content-between my-1">
                                                            <p class="mb-0">Total Room Cost</p>
                                                            <p class="mb-0"><?= general_currency_symbol . ' ' . number_format(($new_room_amount), 2); ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between my-1">
                                                            <p class="mb-0">Total Food Cost</p>
                                                            <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($total_hotel_meal_plan_cost, 2); ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between my-1">
                                                            <p class="mb-0">Total Tax</p>
                                                            <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($new_room_tax_amt, 2); ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between my-1">
                                                            <p class="mb-0">Hotel Margin (<?= $hotel_margin_percentage; ?>%)</p>
                                                            <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($new_margin_amount, 2); ?></p>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between my-1">
                                                            <p class="mb-0">Service Tax for Hotel Margin</p>
                                                            <p class="mb-0"><?= general_currency_symbol . ' ' . number_format($new_margin_tax_amt, 2); ?></p>
                                                        </div>
                                                        <hr class="my-1">
                                                        <div class="d-flex align-items-center justify-content-between my-1">
                                                            <h5 class="mb-0 text-dark"><b>Grand Total</b></h5>
                                                            <h5 class="mb-0 text-primary"><b><?= general_currency_symbol . ' ' . number_format($new_room_amount + $total_hotel_meal_plan_cost + $new_room_tax_amt + $new_margin_amount + $new_margin_tax_amt, 2); ?></b></h5>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    <?php endwhile;
                                    endif; ?>
                                </div>
                                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
