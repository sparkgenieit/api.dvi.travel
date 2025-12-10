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

        $itinerary_plan_hotel_details_ID = (int)$_POST['_itinerary_plan_hotel_details_ID'];
        $itinerary_plan_hotel_room_details_ID = (int)$_POST['_itinerary_plan_hotel_room_details_ID'];
        $itinerary_plan_ID = (int)$_POST['_itinerary_plan_ID'];
        $itinerary_route_date = $_POST['_itinerary_route_date'];
        $itinerary_route_id = (int)$_POST['_itinerary_route_id'];
        $group_type = (int)$_POST['_groupTYPE'];
        $selected_hotel_id = (int)$_POST['_selected_hotel_id'];
        $total_hotel_cost = (float)$_POST['_total_hotel_cost'];
        $total_hotel_tax_amount = (float)$_POST['_total_hotel_tax_amount'];
        $total_no_of_rooms = (int)$_POST['_total_no_of_rooms'];
        $total_extra_bed = (int)$_POST['_total_extra_bed'];
        $total_child_with_bed = (int)$_POST['_total_child_with_bed'];
        $total_child_without_bed = (int)$_POST['_total_child_without_bed'];
        $breakfast_required = (int)$_POST['_breakfast_required'];
        $lunch_required = (int)$_POST['_lunch_required'];
        $dinner_required = (int)$_POST['_dinner_required'];
        $total_no_of_persons = (int)$_POST['_total_no_of_persons'];
        $gst_type = (float)$_POST['_gst_type'];
        $gst_percentage = (float)$_POST['_gst_percentage'];
        $hotel_margin_percentage = (float)$_POST['_hotel_margin_percentage'];
        $hotel_margin_gst_type = (int)$_POST['_hotel_margin_gst_type'];
        $hotel_margin_gst_percentage = (float)$_POST['_hotel_margin_gst_percentage'];
        $preferred_room_count = (int)$_POST['_preferred_room_count'];
?>
        <link rel="stylesheet" href="assets/css/itineary_room_details.css" />
        <tr class="" id="hotel_details_<?= $itinerary_plan_hotel_details_ID; ?>">
            <td colspan="8" class="p-0">
                <div class="collapse show">
                    <div class="search-container">
                        <div class="row m-2">
                            <div class="col-9"></div>
                            <div class="col-3">
                                <input type="text" class="hotelSearch form-control" placeholder="Search Hotel..." onkeyup="filterHotel()">
                            </div>
                        </div>
                        <div class="row p-3 pt-2">
                            <?php
                            $select_itineary_route_details = sqlQUERY_LABEL("SELECT ITINEARY_ROUTE_DETAILS.`location_id`, ITINEARY_ROUTE_DETAILS.`itinerary_route_date`, ITINEARY_ROUTE_DETAILS.`next_visiting_location`, STORED_LOCATION.`destination_location_lattitude`, STORED_LOCATION.`destination_location_longitude`,HOTEL.`hotel_id`, HOTEL.`hotel_name`, HOTEL.`hotel_category`, HOTEL.`hotel_latitude`, HOTEL.`hotel_longitude`,ROOMS.`room_id`,ROOMS.`room_type_id`,ROOMS.`room_title`,MONTHNAME(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as month,YEAR(ITINEARY_ROUTE_DETAILS.itinerary_route_date) as year, CASE WHEN DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) < 10 THEN CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR)) ELSE CONCAT('day_', CAST(DAY(ITINEARY_ROUTE_DETAILS.itinerary_route_date) AS CHAR)) END as formatted_day, (6371 * acos(cos(radians(STORED_LOCATION.`destination_location_lattitude`)) * cos(radians(HOTEL.`hotel_latitude`)) * cos(radians(HOTEL.`hotel_longitude`) - radians(STORED_LOCATION.`destination_location_longitude`)) + sin(radians(STORED_LOCATION.`destination_location_lattitude`)) * sin(radians(HOTEL.`hotel_latitude`)))) AS distance_in_km FROM `dvi_itinerary_route_details` ITINEARY_ROUTE_DETAILS LEFT JOIN `dvi_stored_locations` STORED_LOCATION ON STORED_LOCATION.`location_ID` = ITINEARY_ROUTE_DETAILS.`location_id` LEFT JOIN `dvi_hotel` HOTEL ON 1=1 LEFT JOIN `dvi_hotel_rooms` ROOMS ON ROOMS.`hotel_id` = HOTEL.`hotel_id` WHERE ITINEARY_ROUTE_DETAILS.`deleted` = '0' AND ITINEARY_ROUTE_DETAILS.`status` = '1' AND ITINEARY_ROUTE_DETAILS.`itinerary_plan_ID` = '$itinerary_plan_ID' AND `ITINEARY_ROUTE_DETAILS`.`itinerary_route_date` = '$itinerary_route_date' AND HOTEL.`hotel_latitude` IS NOT NULL AND ROOMS.`room_id` IS NOT NULL AND HOTEL.`status` = '1' AND ROOMS.`status` = '1' AND HOTEL.`deleted` = '0' AND ROOMS.`deleted` = '0' AND ROOMS.`room_type_id` IS NOT NULL AND HOTEL.`hotel_longitude` IS NOT NULL GROUP BY ITINEARY_ROUTE_DETAILS.`itinerary_route_date`, HOTEL.`hotel_id` HAVING distance_in_km <= 20 ORDER BY ITINEARY_ROUTE_DETAILS.`itinerary_route_date`,distance_in_km ASC") or die("#1-UNABLE_TO_COLLECT_ITINEARY_TRAVELLER_DETAILS_LIST:" . sqlERROR_LABEL());
                            while ($row = sqlFETCHARRAY_LABEL($select_itineary_route_details)) :
                                $hotel_id = $row['hotel_id'];
                                $total_avaialble_room_count = getAVILABLEROOMCOUNT($hotel_id);

                                // Reset room-related variables for each hotel
                                $room_type_id = [];
                                $room_rate_for_the_day = [];

                                $select_room_details = sqlQUERY_LABEL("SELECT ROOMS.`room_id`, ROOMS.`room_type_id`, ROOMS.`room_title` FROM `dvi_hotel_rooms` ROOMS WHERE ROOMS.`hotel_id` = '$hotel_id'") or die("#2-UNABLE_TO_COLLECT_ROOM_DETAILS:" . sqlERROR_LABEL());

                                // Display room details for the current hotel
                                while ($room_row = sqlFETCHARRAY_LABEL($select_room_details)) :
                                    $room_id = $room_row['room_id'];
                                    $room_type_id[] = $room_row['room_type_id'];
                                    $room_title = $room_row['room_title'];
                                    $extra_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '1');
                                    $child_with_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '2');
                                    $child_without_bed_charge = getROOMBED_PRICEBOOK_DETAILS($hotel_id, $room_row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_bed_rate_for_the_day', '3');
                                    $room_rate_for_the_day[] = getROOM_PRICEBOOK_DETAILS($hotel_id, $room_row['room_id'], $row['year'], $row['month'], $row['formatted_day'], 'room_rate_for_the_day');
                                endwhile;

                                // Make room_type_id array contain only unique values
                                $room_type_id = array_unique($room_type_id);

                                // Sort the room rates in ascending order
                                asort($room_rate_for_the_day);

                                // Get the lowest non-zero room rate
                                $non_zero_rates = array_filter(
                                    $room_rate_for_the_day,
                                    function ($rate) {
                                        return $rate > 0;
                                    }
                                );

                                $lowest_room_rate = !empty($non_zero_rates) ? min($non_zero_rates) : 0;

                                $get_room_gallery_1st_IMG = getROOM_GALLERY_DETAILS($hotel_id, $room_id, '', 'get_room_gallery_1st_IMG');

                                $room_check_in_time = getROOM_DETAILS($room_id, 'check_in_time');
                                $room_check_out_time = getROOM_DETAILS($room_id, 'check_out_time');

                                if ($selected_hotel_id == $hotel_id) :

                                    $check_breakfast_required = $breakfast_required;
                                    $check_lunch_required = $lunch_required;
                                    $check_dinner_required = $dinner_required;

                                    /*$check_breakfast_required = get_ITINEARY_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, $room_id, 'breakfast_required');
                                    $check_lunch_required = get_ITINEARY_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, $room_id, 'lunch_required');
                                    $check_dinner_required = get_ITINEARY_PLAN_HOTEL_ROOM_DETAILS($itinerary_plan_ID, $itinerary_route_id, $hotel_id, $room_id, 'dinner_required');*/

                                    if ($check_breakfast_required == 1 && $check_lunch_required == 1 && $check_dinner_required == 1) :
                                        $all_meal_plan = 1;
                                    else :
                                        $all_meal_plan = 0;
                                    endif;

                                    $hotel_required = 0;
                                    $add_selected_hotel_class = 'bg-success';
                                    $choose_hotel_btn_class = 'btn btn-outline-secondary w-25 mb-2 p-2';
                                    $choose_hotel_btn_label = '<i class="ti ti-trash ti-tada-hover text-secondary cursor-pointer"></i>';
                                    $onlick_hotel_btn_attribute = 'onclick="modifyHOTELROOM(' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ',' . $hotel_required . ')"';

                                    $update_selected_hotel_class = '';
                                    $update_hotel_btn_class = 'btn btn-primary w-75 mb-2';
                                    $update_hotel_btn_label = 'Update';
                                    $update_onlick_hotel_btn_attribute = 'onclick="modifyHOTELROOM(' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ', 1 )"';

                                    $onlick_hotel_roomtype_attribute = 'onclick="modifyHOTELROOMTYPE(' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ',' . $hotel_required . ')"';
                                else :
                                    $hotel_required = 1;
                                    $add_selected_hotel_class = '';
                                    $choose_hotel_btn_class = 'btn btn-outline-primary w-100 mb-2';
                                    $choose_hotel_btn_label = 'Choose';
                                    $onlick_hotel_btn_attribute = 'onclick="modifyHOTELROOM(' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ',' . $hotel_required . ')"';
                                    $onlick_hotel_roomtype_attribute = 'onclick="modifyHOTELROOMTYPE(' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ',' . $hotel_required . ')"';

                                    $check_breakfast_required = $breakfast_required;
                                    $check_lunch_required = $lunch_required;
                                    $check_dinner_required = $dinner_required;

                                    if ($check_breakfast_required == 1 && $check_lunch_required == 1 && $check_dinner_required == 1) :
                                        $all_meal_plan = 1;
                                    else :
                                        $all_meal_plan = 0;
                                    endif;
                                endif;

                                $total_extra_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_extra_bed');
                                $total_child_with_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_with_bed');
                                $total_child_without_bed = get_ITINERARY_PLAN_DETAILS($itinerary_plan_ID, 'total_child_without_bed');

                                $get_selected_hotel_total_cost = ($total_hotel_cost + $total_hotel_tax_amount);
                                $get_total_hotel_cost = getHOTEL_PRICEDIFFERENCE_DETAILS($hotel_id, $itinerary_route_date, $total_no_of_rooms, $total_extra_bed, $total_child_with_bed, $total_child_without_bed, $breakfast_required, $lunch_required, $dinner_required, $total_no_of_persons, $gst_type, $gst_percentage, $hotel_margin_percentage, $hotel_margin_gst_type, $hotel_margin_gst_percentage);
                                $carrot_cost_details = ($get_total_hotel_cost-$get_selected_hotel_total_cost);
                            ?>
                                <?php if ($preferred_room_count > 1) : ?>
                                    <?php if ($total_avaialble_room_count >= $preferred_room_count && $lowest_room_rate > 0) : ?>
                                        <div class="col-md-3 col-lg-4 col-xxl-3 mb-3 px-2" id="vertical-example">
                                            <div class="card">
                                                <div style="position: relative; display: inline-block;">
                                                    <div class="image_wrapper">
                                                        <?php
                                                        $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                                        $image_path = BASEPATH . '/uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                                        $default_image = BASEPATH . 'uploads/no-photo.png';

                                                        if ($get_room_gallery_1st_IMG):
                                                            // Check if the image file exists
                                                            $image_src = file_exists($image_already_exist) ? $image_path : $default_image;
                                                        else:
                                                            $image_src = $default_image;
                                                        endif;
                                                        ?>
                                                        <img class="img-fluid rounded-top" src="<?= $image_src; ?>" style="height: 180px; width: 100%;" alt="Hotel Image" />

                                                        <div class="overlay overlay_image_wrapper">
                                                            <h6 class="mb-0" style="font-size: 11px;"><?= getHOTEL_CATEGORY_DETAILS($row['hotel_category'], 'label'); ?></h6>
                                                            <h6 class="mb-0 text-wrap"><?= $row['hotel_name']; ?></h6>
                                                            <div class="d-flex align-items-center justify-content-between w-75">
                                                                <h6 class="mb-0" style="font-size: 11px;"><span class="text-muted">starting from</span> <?= general_currency_symbol . ' ' . number_format($lowest_room_rate, 2); ?>/d - <?= date('d M Y', strtotime($row['itinerary_route_date'])); ?></h6>
                                                                <?php if ($selected_hotel_id != $hotel_id) :
                                                                    if ($carrot_cost_details > 0) : ?>
                                                                        <span class="badge hotel-supplementry-succesbadge px-2 pb-1"><img class="pb-1 pe-1" src="assets/img/svg/drop-up.svg" /><?= general_currency_symbol . ' ' . number_format($carrot_cost_details, 2); ?></span>
                                                                    <?php else : ?>
                                                                        <span class="badge hotel-supplementry-dangerbadge px-2 pb-1"><img class="pb-1 pe-1" src="assets/img/svg/down.svg" /><?= general_currency_symbol . ' ' . number_format($carrot_cost_details, 2) ?></span>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" onclick="showHOTELROOMGALLERY('<?= $hotel_id; ?>')">
                                                    </div>
                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Details" data-bs-original-title="Click to View the Details">
                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/details.svg" onclick="showHOTELDETAILS('<?= $group_type; ?>','<?= $hotel_id; ?>','<?= $selected_hotel_id; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_plan_hotel_room_details_ID; ?>')">
                                                    </div>
                                                    <div class="room-bagde-flag-wrap">
                                                        <div class="room-bagde-flag shadow-lg <?= $add_selected_hotel_class; ?>"><img src="assets/img/svg/bed_1.svg"><span> - <?= $total_no_of_rooms; ?></span></div>
                                                    </div>
                                                    <?php if ($selected_hotel_id == $hotel_id) : ?>
                                                        <div class="itinerary-details-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Amenities" data-bs-original-title="Click to View the Details">
                                                            <img class="ms-1 ti-tada-hover" src="assets/img/svg/amenities.svg" onclick="showHOTELADDAMENITIES('<?= $group_type; ?>','<?= $hotel_id; ?>','<?= $itinerary_route_date; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_id; ?>')">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-body pt-0 pb-2 mt-3">
                                                    <div class="col-12 d-flex mb-3 g-3">
                                                        <div class="col-6">
                                                            <div class="d-flex">
                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;"><?= date('h:i A', strtotime($room_check_in_time)); ?></h6>
                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check In</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="d-flex">
                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;"><?= date('h:i A', strtotime($room_check_out_time)); ?></h6>
                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check Out</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if ($selected_hotel_id == $hotel_id) : ?>
                                                        <div class="col-12 mb-3 g-3">
                                                            <div class="d-xxl-flex align-items-center defaultEditRoomCategory">
                                                                <h6 class="m-0">Room Type -</h6>
                                                                <span class="roomCategoryDropdown text-muted"></span>
                                                                <span class="roomCategoryDropdown text-muted"><?= $total_no_of_rooms; ?> Rooms Selected</span>
                                                                <i class="ti ti-edit ti-sm" <?= $onlick_hotel_roomtype_attribute; ?>></i>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>

                                                    <div class="col-12 mb-3 g-3 mealSelectionOption mealSelectionOption2">
                                                        <small class="fw-medium d-block">Meal</small>
                                                        <div class="d-flex col-12 flex-wrap">
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="all_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="all_meal_plan" <?= ($all_meal_plan == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="all_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">All </label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="breakfast_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="breakfast_meal_plan" <?= ($check_breakfast_required == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="breakfast_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">Breakfast</label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="lunch_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="lunch_meal_plan" <?= ($check_lunch_required == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="lunch_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">Lunch</label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="dinner_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="dinner_meal_plan" <?= ($check_dinner_required == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="dinner_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">Dinner</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <?php if ($selected_hotel_id == $hotel_id) : ?>
                                                            <button type="button" <?= $update_onlick_hotel_btn_attribute; ?> class="<?= $update_hotel_btn_class; ?>"><?= $update_hotel_btn_label; ?></button>
                                                        <?php endif; ?>
                                                        <button type="button" <?= $onlick_hotel_btn_attribute; ?> class="<?= $choose_hotel_btn_class; ?>"><?= $choose_hotel_btn_label; ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?php if ($total_avaialble_room_count >= $preferred_room_count && $lowest_room_rate > 0) : ?>
                                        <div class="col-md-3 col-lg-4 col-xxl-3 mb-3 px-2" id="vertical-example">
                                            <div class="card border-primary">
                                                <div style="position: relative; display: inline-block;">
                                                    <div class="image_wrapper">
                                                        <?php
                                                        $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                                        $image_path = BASEPATH . '/uploads/room_gallery/' . $get_room_gallery_1st_IMG;
                                                        $default_image = BASEPATH . 'uploads/no-photo.png';

                                                        if ($get_room_gallery_1st_IMG):
                                                            // Check if the image file exists
                                                            $image_src = file_exists($image_already_exist) ? $image_path : $default_image;
                                                        else:
                                                            $image_src = $default_image;
                                                        endif;
                                                        ?>
                                                        <img class="img-fluid rounded-top" src="<?= $image_src; ?>" style="height: 180px; width: 100%;" alt="Hotel Image" />

                                                        <div class="overlay overlay_image_wrapper">
                                                            <h6 class="mb-0" style="font-size: 11px;"><?= getHOTEL_CATEGORY_DETAILS($row['hotel_category'], 'label'); ?></h6>
                                                            <h6 class="mb-0 text-wrap"><?= $row['hotel_name']; ?></h6>
                                                            <h6 class="mb-0" style="font-size: 11px;"><span class="text-muted">starting from</span> <?= general_currency_symbol . ' ' . number_format($lowest_room_rate, 2); ?>/d - <?= date('d M Y', strtotime($row['itinerary_route_date'])); ?></h6>

                                                            <?php if ($selected_hotel_id != $hotel_id) :
                                                                if ($carrot_cost_details > 0) : ?>
                                                                    <span class="badge hotel-supplementry-succesbadge px-2 pb-1"><img class="pb-1 pe-1" src="assets/img/svg/drop-up.svg" /><?= general_currency_symbol . ' ' . number_format($carrot_cost_details, 2); ?></span>
                                                                <?php else : ?>
                                                                    <span class="badge hotel-supplementry-dangerbadge px-2 pb-1"><img class="pb-1 pe-1" src="assets/img/svg/down.svg" /><?= general_currency_symbol . ' ' . number_format($carrot_cost_details, 2); ?></span>
                                                            <?php endif;
                                                            endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" onclick="showHOTELROOMGALLERY('<?= $hotel_id; ?>')">
                                                    </div>
                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Details" data-bs-original-title="Click to View the Details">
                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/details.svg" onclick="showHOTELDETAILS('<?= $group_type; ?>','<?= $hotel_id; ?>','<?= $selected_hotel_id; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_id; ?>','<?= $itinerary_plan_hotel_room_details_ID; ?>')">
                                                    </div>
                                                    <div class=" room-bagde-flag-wrap">
                                                        <div class="room-bagde-flag shadow-lg <?= $add_selected_hotel_class; ?>"><img src="assets/img/svg/bed_1.svg"><span> - <?= $total_no_of_rooms; ?></span></div>
                                                    </div>
                                                    <?php if ($selected_hotel_id == $hotel_id) : ?>
                                                        <div class="itinerary-details-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Amenities" data-bs-original-title="Click to View the Details">
                                                            <img class="ms-1 ti-tada-hover" src="assets/img/svg/amenities.svg" onclick="showHOTELADDAMENITIES('<?= $group_type; ?>','<?= $hotel_id; ?>','<?= $itinerary_route_date; ?>','<?= $itinerary_plan_ID; ?>','<?= $itinerary_route_id; ?>')">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-body pt-0 pb-2 mt-3">
                                                    <div class="col-12 d-flex mb-3 g-3">
                                                        <div class="col-6">
                                                            <div class="d-flex">
                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;"><?= date('h:i A', strtotime($room_check_in_time)); ?></h6>
                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check In</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="d-flex">
                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;"><?= date('h:i A', strtotime($room_check_out_time)); ?></h6>
                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check Out</h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3 g-3">
                                                        <div>
                                                            <label class="mb-1" style="font-size: 13px;">Room Type</label>
                                                            <?php if ($selected_hotel_id == $hotel_id) :
                                                                $onchange_roomtype_attribute = 'onchange="changeHOTELROOMTYPE(this, ' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ')"';
                                                            else:
                                                                $onchange_roomtype_attribute = 'onchange="getchangeHOTELROOMTYPE(this,' . $group_type . ',' . $itinerary_plan_hotel_details_ID . ',' . $itinerary_plan_hotel_room_details_ID . ',' . $itinerary_plan_ID . ',' . $itinerary_route_id . ',' . $hotel_id . ')"';
                                                            endif; ?>
                                                            <select id="choosen_room_type_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="choosen_room_type" class="form-control form-select choosen_room_type" <?= $onchange_roomtype_attribute; ?>>
                                                                <?php if ($selected_hotel_id == $hotel_id) : ?>
                                                                    <?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $selected_room_type_id, $row['itinerary_route_date'], 'select_itineary_hotel'); ?>
                                                                <?php else : ?>
                                                                    <?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, '', $row['itinerary_route_date'], 'select_itineary_hotel'); ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mb-3 g-3 mealSelectionOption mealSelectionOption2">
                                                        <small class="fw-medium d-block">Meal</small>
                                                        <div class="d-flex col-12 flex-wrap">
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="all_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="all_meal_plan" <?= ($all_meal_plan == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="all_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">All</label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="breakfast_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="breakfast_meal_plan" <?= ($check_breakfast_required == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="breakfast_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">Breakfast</label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="lunch_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="lunch_meal_plan" <?= ($check_lunch_required == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="lunch_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">Lunch</label>
                                                            </div>
                                                            <div class="form-check col-6">
                                                                <input class="form-check-input" type="checkbox" id="dinner_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>" name="dinner_meal_plan" <?= ($check_dinner_required == 1) ? 'checked' : ''; ?> data-hotel-id="<?= $hotel_id; ?>" data-route-id="<?= $itinerary_route_id; ?>" data-grouptype-id="<?= $group_type; ?>" data-hotel_room_detail-id="<?= $itinerary_plan_hotel_room_details_ID; ?>">
                                                                <label class="form-check-label" for="dinner_meal_plan_<?= $hotel_id; ?>_<?= $itinerary_route_id; ?>_<?= $group_type; ?>_<?= $itinerary_plan_hotel_room_details_ID; ?>">Dinner</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php if ($selected_hotel_id == $hotel_id) : ?>
                                                        <button type="button" <?= $update_onlick_hotel_btn_attribute; ?> class="<?= $update_hotel_btn_class; ?>"><?= $update_hotel_btn_label; ?></button>
                                                    <?php endif; ?>

                                                    <button type="button" <?= $onlick_hotel_btn_attribute; ?> class="<?= $choose_hotel_btn_class; ?>"><?= $choose_hotel_btn_label; ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </td>
        </tr>

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
                    var hotel_id = $(this).data('hotel-id');
                    var route_id = $(this).data('route-id');
                    var grouptype_id = $(this).data('grouptype-id');
                    var hotel_room_detail_id = $(this).data('hotel_room_detail-id');
                    var isChecked = $(this).is(':checked');

                    $('#breakfast_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id).prop('checked', isChecked);
                    $('#lunch_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id).prop('checked', isChecked);
                    $('#dinner_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id).prop('checked', isChecked);
                });

                // When any individual checkbox is clicked
                $('[id^="breakfast_meal_plan_"], [id^="lunch_meal_plan_"], [id^="dinner_meal_plan_"]').change(function() {
                    var hotel_id = $(this).data('hotel-id');
                    var route_id = $(this).data('route-id');
                    var grouptype_id = $(this).data('grouptype-id');
                    var hotel_room_detail_id = $(this).data('hotel_room_detail-id');

                    // Build the checkbox IDs
                    var breakfast_checkbox = $('#breakfast_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id);
                    var lunch_checkbox = $('#lunch_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id);
                    var dinner_checkbox = $('#dinner_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id);
                    var all_checkbox = $('#all_meal_plan_' + hotel_id + '_' + route_id + '_' + grouptype_id + '_' + hotel_room_detail_id);

                    // If all 3 are checked, check the "All" checkbox
                    if (breakfast_checkbox.is(':checked') && lunch_checkbox.is(':checked') && dinner_checkbox.is(':checked')) {
                        all_checkbox.prop('checked', true);
                    } else {
                        all_checkbox.prop('checked', false);
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
                var choosen_room_type = $('#choosen_room_type_' + hotel_ID + '_' + itinerary_route_id + '_' + group_type + '_' + itinerary_plan_hotel_room_details_ID).val();

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
        </script>
<?php
    endif;
endif;
?>