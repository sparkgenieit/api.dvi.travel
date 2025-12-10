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
ini_set('log_errors', 1);
 */
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_itinerary_plan_vehicle_details') :

        $itinerary_plan_id = $_POST['itinerary_plan_ID'];

        if ($itinerary_plan_id != '' && $itinerary_plan_id != 0) :

            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `preferred_room_count`, `total_extra_bed`, `total_child_with_bed`, `total_child_without_bed`, `guide_for_itinerary` FROM `dvi_itinerary_plan_details` WHERE `deleted` = '0' and `itinerary_plan_ID` = '$itinerary_plan_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
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
                $total_adult = $fetch_list_data["total_adult"];
                $total_children = $fetch_list_data["total_children"];
                $total_infants = $fetch_list_data["total_infants"];
                $preferred_room_count = $fetch_list_data["preferred_room_count"];
                $total_extra_bed = $fetch_list_data["total_extra_bed"];
                $guide_for_itinerary = $fetch_list_data["guide_for_itinerary"];
            endwhile;

            $VENDER_DETAILS = getITINERARYVEHICLELIST($itinerary_plan_id);

?>

            <div class="vehicle_list">

                <div class="row align-items-center justify-content-between mb-2">
                    <div class="col-md-auto">
                        <h5 class="card-header p-0 text-primary mb-2">Vehicle List</h5>
                    </div>

                    <div class="card-body dataTable_select text-nowrap px-2">
                        <div class="text-nowrap table-bordered">
                            <table id="hotel_LIST" class="table table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Vendor Name</th>
                                        <th>Branches</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = count($VENDER_DETAILS['vendor_name']);
                                    for ($i = 0; $i < max(3, $count); $i++) :
                                        // Check if there is a vendor available
                                        if ($i < $count) : ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox" name="selected_vendors[]" value="<?= $VENDER_DETAILS['vendor_id'][$i] ?>" <?= $i == 0 ? "checked" : "" ?> onclick="recalculateVENDOR_DETAILS(this,'<?= $itinerary_plan_id ?>','<?= $VENDER_DETAILS['vendor_id'][$i] ?>','<?= $VENDER_DETAILS['vendor_branch_id'][$i] ?>')">
                                                </td>
                                                <td> <?= $VENDER_DETAILS['vendor_name'][$i] ?> </td>
                                                <td>
                                                    <select class="form-control " name="branch_details" id="branch_details">
                                                        <?= getVENDORBRANCHDETAIL($VENDER_DETAILS['vendor_branch_id'][$i], '', 'select', $VENDER_DETAILS['vendor_id'][$i]); ?>
                                                    </select>
                                                </td>
                                                <td> <?= $global_currency_format . ' ' . number_format($VENDER_DETAILS['total_vendor_cost'][$i], 2); ?></td>
                                            </tr>
                                    <?php else :
                                            break;
                                        endif;
                                    endfor; ?>

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php
                    $select_itineary_plan_vehicle_info = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `itinerary_route_location_from`, `itinerary_route_location_to`, `vendor_id`, `vendor_branch_id`, `vehile_type_id`, `vehicle_id`, `vehicle_count`, `running_kms`, `sight_seeing_kms`, `total_kms_travelled`, `traveling_time`, `sight_seeing_time`, `total_time`, `cost_type`, `local_time_limit_id`, `outstation_km_limit_id`, `extra_km_charge`, `driver_bhatta`, `driver_food_cost`, `driver_accomodation_cost`, `extra_cost`, `total_driver_cost`, `total_driver_gst_amt`, `toll_charge`, `vehicle_parking_charge`, `vehicle_permit_state_id`, `vehicle_permit_cost_id`, `vehicle_permit_cost`, `vehicle_gst_type`, `vehicle_gst_percentage`, `vehicle_gst_amount`,`vehicle_per_day_cost`, `total_vehicle_cost`, `total_vehicle_cost_with_gst` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_id' and `status` = '1' and `deleted` = '0' ORDER BY `itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                    $total_no_of_vehicle_info = sqlNUMOFROW_LABEL($select_itineary_plan_vehicle_info);
                    if ($total_no_of_vehicle_info > 0) :

                        $overall_total_trip_cost = 0;
                        $overall_total_vehicle_gst_tax_amt = 0;
                        $overall_total_driver_charge = 0;
                        $overall_total_driver_gst_tax_amt = 0;
                        $overall_total_permit_cost = 0;
                        $overall_total_vehicle_parking_charge = 0;
                        $overall_total_vehicle_toll_charge = 0;
                        $route_count = 0;
                        $grand_total = 0;

                        $TOTAL_DISTANCE = 0;
                        $TOTAL_TIME_TAKEN = "00:00:00";

                        while ($fetch_itineary_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_plan_vehicle_info)) :

                            $itinerary_route_date = dateformat_datepicker($fetch_itineary_vehicle_data['itinerary_route_date']);

                            if ($prev_itinerary_route_date != $itinerary_route_date) :
                                $route_count++;
                            endif;

                            $itinerary_plan_vendor_vehicle_details_ID =  $fetch_itineary_vehicle_data['itinerary_plan_vendor_vehicle_details_ID'];
                            $itinerary_route_id =  $fetch_itineary_vehicle_data['itinerary_route_id'];

                            $day = date('j', strtotime($fetch_itineary_vehicle_data['itinerary_route_date']));
                            $year = date('Y', strtotime($fetch_itineary_vehicle_data['itinerary_route_date']));
                            $month = date('F', strtotime($fetch_itineary_vehicle_data['itinerary_route_date']));

                            $itinerary_route_location_from =  $fetch_itineary_vehicle_data['itinerary_route_location_from'];
                            $itinerary_route_location_to =  $fetch_itineary_vehicle_data['itinerary_route_location_to'];
                            $vendor_id =  $fetch_itineary_vehicle_data['vendor_id'];
                            $vendor_branch_id =  $fetch_itineary_vehicle_data['vendor_branch_id'];
                            $vehile_type_id =  $fetch_itineary_vehicle_data['vehile_type_id'];
                            $vehicletype_title = getVENDOR_VEHICLE_TYPES($vendor_id, $vehile_type_id, 'label');
                            $vehicle_id =  $fetch_itineary_vehicle_data['vehicle_id'];
                            $vehicle_video_url = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_vehicle_video_url');
                            $vehicle_count =  $fetch_itineary_vehicle_data['vehicle_count'];
                            $vehicletypeid = getVENDOR_VEHICLE_TYPES($vendor_id, $vehile_type_id, 'get_vehicle_type_id');
                            $vehicle_occupancy = getOCCUPANCY($vehicletypeid, 'get_occupancy');

                            $running_kms =  $fetch_itineary_vehicle_data['running_kms'];
                            $sight_seeing_kms =  $fetch_itineary_vehicle_data['sight_seeing_kms'];
                            $total_kms_travelled =  $fetch_itineary_vehicle_data['total_kms_travelled'];
                            $traveling_time =  $fetch_itineary_vehicle_data['traveling_time'];
                            $sight_seeing_time =  $fetch_itineary_vehicle_data['sight_seeing_time'];
                            $total_time =  $fetch_itineary_vehicle_data['total_time'];
                            $cost_type =  $fetch_itineary_vehicle_data['cost_type'];
                            $local_time_limit_id =  $fetch_itineary_vehicle_data['local_time_limit_id'];
                            $extra_km_charge =  $fetch_itineary_vehicle_data['extra_km_charge'];

                            $route_perday_km = getROUTECONFIGURATION('route_perday_km');
                            if ($route_perday_km >= $total_kms_travelled) :
                                $extra_km = 0;
                            else :
                                $extra_km = $total_kms_travelled - $route_perday_km;
                            endif;

                            $driver_bhatta =  $fetch_itineary_vehicle_data['driver_bhatta'];
                            $driver_food_cost =  $fetch_itineary_vehicle_data['driver_food_cost'];
                            $driver_accomodation_cost =  $fetch_itineary_vehicle_data['driver_accomodation_cost'];
                            $extra_cost =  $fetch_itineary_vehicle_data['extra_cost'];
                            $total_driver_cost =  $fetch_itineary_vehicle_data['total_driver_cost'];
                            $total_driver_gst_amt =  $fetch_itineary_vehicle_data['total_driver_gst_amt'];
                            $toll_charge =  $fetch_itineary_vehicle_data['toll_charge'];
                            $vehicle_parking_charge =  $fetch_itineary_vehicle_data['vehicle_parking_charge'];
                            $vehicle_permit_cost =  $fetch_itineary_vehicle_data['vehicle_permit_cost'];
                            $vehicle_gst_type =  $fetch_itineary_vehicle_data['vehicle_gst_type'];
                            $vehicle_gst_percentage =  $fetch_itineary_vehicle_data['vehicle_gst_percentage'];
                            $vehicle_gst_amount =  $fetch_itineary_vehicle_data['vehicle_gst_amount'];
                            $vehicle_per_day_cost = $fetch_itineary_vehicle_data['vehicle_per_day_cost'];
                            $total_vehicle_cost =  $fetch_itineary_vehicle_data['total_vehicle_cost'];
                            $total_vehicle_cost_with_gst =  $fetch_itineary_vehicle_data['total_vehicle_cost_with_gst'];
                            $total_gst_amt = $total_driver_gst_amt + $vehicle_gst_amount;

                            ${"total_trip_cost_per_day_" . $itinerary_route_date} = $total_vehicle_cost;
                            ${"total_trip_tax_per_day_" . $itinerary_route_date} = $total_gst_amt;
                            ${"total_trip_cost_with_tax_per_day_" . $itinerary_route_date} = $total_vehicle_cost_with_gst;

                            if ($prev_itinerary_route_date == $itinerary_route_date) :
                                ${"total_trip_cost_per_day_" . $itinerary_route_date} = ${"total_trip_cost_per_day_" . $itinerary_route_date} + $total_vehicle_cost;
                                ${"total_trip_tax_per_day_" . $itinerary_route_date} = ${"total_trip_tax_per_day_" . $itinerary_route_date} + $total_gst_amt;
                                ${"total_trip_cost_with_tax_per_day_" . $itinerary_route_date} = ${"total_trip_cost_with_tax_per_day_" . $itinerary_route_date} + $total_vehicle_cost_with_gst;
                            endif;

                            $overall_total_gst_tax_amt = $overall_total_gst_tax_amt + ${"total_trip_tax_per_day_" . $itinerary_route_date};

                            $overall_total_trip_cost = $overall_total_trip_cost + ${"total_trip_cost_per_day_" . $itinerary_route_date};

                            $overall_total_driver_charge = $overall_total_driver_charge + $total_driver_cost;

                            $overall_total_permit_cost = $overall_total_permit_cost + $vehicle_permit_cost;
                            $overall_total_vehicle_parking_charge = $overall_total_vehicle_parking_charge + $vehicle_parking_charge;
                            $overall_total_vehicle_toll_charge = $overall_total_vehicle_toll_charge + $toll_charge;

                            $grand_total = $grand_total + ($overall_total_gst_tax_amt + $overall_total_trip_cost);

                            $TOTAL_DISTANCE = $TOTAL_DISTANCE + $total_kms_travelled;

                            //TOTAL TIME TAKEN
                            // Convert time durations to seconds
                            $TOTAL_TIME_TAKEN_IN_SECONDS = strtotime($TOTAL_TIME_TAKEN) - strtotime('00:00:00');
                            $_TIME_IN_SECONDS = strtotime($total_time) - strtotime('00:00:00');

                            $totalSeconds3 = $TOTAL_TIME_TAKEN_IN_SECONDS + $_TIME_IN_SECONDS;

                            $TOTAL_TIME_TAKEN = gmdate('H:i:s', $totalSeconds3);


                            $vehicle_parking_charge =
                                getHOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($itinerary_plan_id, $itinerary_route_id, 'total_hotspot_parking_charges');
                            $total_vehicle_cost = $total_vehicle_cost + $vehicle_parking_charge;

                    ?>

                            <div class="card border border-secondary p-3 mt-2">
                                <?php if ($prev_itinerary_route_date != $itinerary_route_date) : ?>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0">
                                            <span>DAY <?= $route_count ?> - <?= $month . " " . $day . ", " . $year ?> </span>
                                        </h6>
                                        <h6 class="mb-0">
                                            <span class="text-primary me-1">
                                                <i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>
                                                <?php
                                                if ($itinerary_route_location_from == $itinerary_route_location_to) :
                                                    echo $itinerary_route_location_from;
                                                else :
                                                    echo $itinerary_route_location_from . " To " . $itinerary_route_location_to;
                                                endif;
                                                ?>
                                            </span>
                                        </h6>
                                        <h6 class="mb-0">
                                            <span class="text-primary me-1">
                                                <i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
                                                <?php
                                                $time_parts2 = explode(':', $total_time);
                                                $TOTAL_TIME_hours2 = intval($time_parts2[0]);
                                                $TOTAL_TIME_minutes2 = intval($time_parts2[1]);
                                                $TOTAL_TIME_seconds = intval($time_parts2[2]);

                                                echo $TOTAL_TIME_hours2 . " Hours " . $TOTAL_TIME_minutes2 . " Minutes ";
                                                ?></span>
                                        </h6>
                                    </div>

                                    <hr />

                                    <div class="row justify-content-center">
                                        <div class="col-4">
                                            <small class="mb-0">Travel Distance & Time</small>
                                            <p class="mb-0 fw-bolder">
                                                <i class="ti ti-road ti-xs text-primary me-1 mb-1"></i><?= $running_kms ?> KM
                                            </p>
                                            <p class="mb-0 fw-bolder">
                                                <i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
                                                <?php
                                                $time_parts = explode(':', $traveling_time);
                                                $TOTAL_TIME_hours = intval($time_parts[0]);
                                                $TOTAL_TIME_minutes = intval($time_parts[1]);
                                                $TOTAL_TIME_seconds = intval($time_parts[2]);

                                                echo $TOTAL_TIME_hours . " Hours " . $TOTAL_TIME_minutes . " Minutes "; ?>
                                            </p>
                                        </div>
                                        <div class="col-4 text-center">
                                            <small class="mb-0">Sight-seeing Distance & Time</small>
                                            <p class="mb-0 fw-bolder">
                                                <i class="ti ti-road ti-xs text-primary me-1 mb-1"></i> <?= $sight_seeing_kms ?>KM
                                            </p>
                                            <p class="mb-0 fw-bolder">
                                                <i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
                                                <?php
                                                $time_parts1 = explode(':', $sight_seeing_time);
                                                $TOTAL_TIME_hours1 = intval($time_parts1[0]);
                                                $TOTAL_TIME_minutes1 = intval($time_parts1[1]);
                                                $TOTAL_TIME_seconds = intval($time_parts1[2]);

                                                echo $TOTAL_TIME_hours1 . " Hours " . $TOTAL_TIME_minutes1 . " Minutes "; ?>
                                            </p>
                                        </div>
                                        <div class="col-4 text-end">
                                            <small class="mb-0 text-primary">Total Distance & Time</small>
                                            <p class="mb-0 text-primary fw-bolder">
                                                <i class="ti ti-road ti-xs text-primary me-1 mb-1"></i><?= $total_kms_travelled ?> KM
                                            </p>
                                            <p class="mb-0 text-primary fw-bolder">
                                                <i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
                                                <?php
                                                $time_parts2 = explode(':', $total_time);
                                                $TOTAL_TIME_hours2 = intval($time_parts2[0]);
                                                $TOTAL_TIME_minutes2 = intval($time_parts2[1]);
                                                $TOTAL_TIME_seconds = intval($time_parts2[2]);

                                                echo $TOTAL_TIME_hours2 . " Hours " . $TOTAL_TIME_minutes2 . " Minutes "; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <hr />

                                <div class="row align-items-center">
                                    <div class="col-9 border-end">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row justify-content-between mb-2">
                                                    <div class="col-md-auto">
                                                        <h5 class="mb-0 fw-bolder d-flex align-items-center">
                                                            #1 - <b class="text-primary"><?= $vehicletype_title ?></b>
                                                            <span class="badge rounded-pill bg-label-primary mx-2" style="font-size: 11px;">COUNT <?= $vehicle_count ?></span>
                                                        </h5>
                                                        <h6 class="mb-0">
                                                            <i class="text-primary ti ti-users-group me-1"></i>
                                                            Max Occupancy: <?= ($vehicle_occupancy == "" ? "--" : $vehicle_occupancy) ?>
                                                        </h6>
                                                    </div>
                                                    <div class="col-md-auto text-primary mb-0 text-end">
                                                        <h5 class="mb-0 lh-1">
                                                            <?= $global_currency_format . ' ' . number_format($total_vehicle_cost, 2); ?>
                                                        </h5>

                                                        <small>+ <?= $global_currency_format . ' ' . number_format($vehicle_gst_amount, 2); ?> Vehicle Taxes &amp; Charges</small><br>
                                                        <small>+ <?= $global_currency_format . ' ' . number_format($total_driver_gst_amt, 2); ?> Driver Taxes &amp; Charges</small>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-3">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <?php
                                                            $select_gallery_info = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted`='0' and `status`='1' AND `vehicle_id`='$vehicle_id' AND (`image_type`='7' OR `image_type`='8') LIMIT 1") or die("#1-UNABLE_TO_COLLECT_IMAGES:" . sqlERROR_LABEL());

                                                            $gallery_count = sqlNUMOFROW_LABEL($select_gallery_info);
                                                            if ($gallery_count > 0) :
                                                                while ($fetch_vehicle_gallery = sqlFETCHARRAY_LABEL($select_gallery_info)) :

                                                                    $vehicle_gallery_details_id = $fetch_vehicle_gallery['vehicle_gallery_details_id'];
                                                                    $vehicle_gallery_image = BASEPATH . '/uploads/vehicle_gallery/' . $fetch_vehicle_gallery['vehicle_gallery_name'];
                                                            ?>
                                                                    <img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="<?= $vehicle_gallery_image; ?>" alt="<?= $vehicletype_title; ?>" <?php if ($gallery_count > 0) : ?> data-bs-toggle="modal" data-bs-target="#modalCenter1_<?= $itinerary_plan_vendor_vehicle_details_ID; ?>_<?= $vehicle_id; ?>" <?php endif; ?> width="150" height="110" style="border: 1px solid #c33ca6;" />
                                                                    <?php if ($vehicle_video_url != "") : ?>
                                                                        <a href="<?= $vehicle_video_url ?>" target="_blank" class="button">Play Video</a>
                                                                    <?php endif; ?>

                                                                <?php endwhile;
                                                            else : ?>
                                                                <img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="<?= BASEPATH . 'uploads/no-photo.png' ?>" alt="" width="150" height="110" style="border: 1px solid #c33ca6;" />

                                                            <?php endif;
                                                            ?>
                                                        </div>
                                                        <div class="modal fade" id="modalCenter1_<?= $itinerary_plan_vendor_vehicle_details_ID; ?>_<?= $vehicle_id; ?>" tabindex="-1" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body pt-0">

                                                                        <div class="text-center mb-2">
                                                                            <h5 class="modal-title" id="modalCenterTitle"><?= $vehicletype_title  ?> </h5>
                                                                            <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle"></h5>
                                                                        </div>
                                                                        <div id="swiper-gallery">
                                                                            <div class="swiper gallery-top">
                                                                                <div class="swiper-wrapper">
                                                                                    <?php
                                                                                    $select_gallery_info = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted`='0' and `status`='1' AND `vehicle_id`='$vehicle_id' AND (`image_type`='7' OR `image_type`='8' )") or die("#1-UNABLE_TO_COLLECT_IMAGES:" . sqlERROR_LABEL());

                                                                                    $gallery_count = sqlNUMOFROW_LABEL($select_gallery_info);
                                                                                    if ($gallery_count > 0) :
                                                                                        while ($fetch_vehicle_gallery = sqlFETCHARRAY_LABEL($select_gallery_info)) :

                                                                                            $vehicle_gallery_details_id = $fetch_vehicle_gallery['vehicle_gallery_details_id'];

                                                                                            $vehicle_gallery_image = BASEPATH . '/uploads/vehicle_gallery/' . $fetch_vehicle_gallery['vehicle_gallery_name'];
                                                                                    ?>
                                                                                            <div class="swiper-slide" style="background-image:url(<?= $vehicle_gallery_image; ?>)"></div>
                                                                                    <?php
                                                                                        endwhile;
                                                                                    else :
                                                                                        $vehicle_gallery_image = '';
                                                                                    endif;
                                                                                    ?>
                                                                                </div>
                                                                                <!-- Add Arrows -->
                                                                                <div class="swiper-button-next swiper-button-white"></div>
                                                                                <div class="swiper-button-prev swiper-button-white"></div>
                                                                            </div>
                                                                            <div class="swiper gallery-thumbs">
                                                                                <div class="swiper-wrapper">
                                                                                    <?php
                                                                                    $vehicle_gallery_image = '';
                                                                                    $select_gallery_info = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted`='0' and `status`='1' AND `vehicle_id`='$vehicle_id' AND (`image_type`='7' OR `image_type`='8' )") or die("#1-UNABLE_TO_COLLECT_IMAGES:" . sqlERROR_LABEL());

                                                                                    $gallery_count = sqlNUMOFROW_LABEL($select_gallery_info);
                                                                                    if ($gallery_count > 0) :
                                                                                        while ($fetch_vehicle_gallery = sqlFETCHARRAY_LABEL($select_gallery_info)) :

                                                                                            $vehicle_gallery_details_id = $fetch_vehicle_gallery['vehicle_gallery_details_id'];

                                                                                            $vehicle_gallery_image = BASEPATH . '/uploads/vehicle_gallery/' . $fetch_vehicle_gallery['vehicle_gallery_name'];
                                                                                    ?>
                                                                                            <div class="swiper-slide" style="background-image:url(<?= $vehicle_gallery_image; ?>)"></div>
                                                                                    <?php
                                                                                        endwhile;
                                                                                    else :
                                                                                        $vehicle_gallery_image = '';
                                                                                    endif;
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="col-9">
                                                        <div class="row g-3">
                                                            <div class="col-4">
                                                                <small class="mb-0">Allowed kms</small>
                                                                <p class="mb-0 fw-bolder text-success"><?= getROUTECONFIGURATION('route_perday_km'); ?></p>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="mb-0">Extra kms</small>
                                                                <p class="mb-0 fw-bolder"><?= $extra_km ?></p>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="mb-0">Per day rental (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($vehicle_per_day_cost, 2); ?></p>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="mb-0">Charge for extra kms (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder"><span class="text-primary"><?= number_format($extra_km_charge, 2); ?></span></p>
                                                            </div>
                                                            <div class="col-4">
                                                                <small class="mb-0">Permit charge (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($vehicle_permit_cost, 2); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-3">
                                                        <div class="row g-3">
                                                            <div class="col-3">
                                                                <small class="mb-0">Driver Bhatta (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($driver_bhatta, 2); ?></p>
                                                            </div>
                                                            <div class="col-3">
                                                                <small class="mb-0">Driver Food Cost (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder"><span class="text-primary"><?= number_format($driver_food_cost, 2); ?></span>
                                                                <p>
                                                            </div>
                                                            <div class="col-3">
                                                                <small class="mb-0">Accomdation Cost (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($driver_accomodation_cost, 2); ?></p>
                                                            </div>
                                                            <div class="col-3">
                                                                <small class="mb-0">Extra Cost (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($extra_cost, 2); ?></p>
                                                            </div>
                                                            <div class="col-3">
                                                                <small class="mb-0">Toll Charge (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($toll_charge, 2); ?></p>
                                                            </div>
                                                            <div class="col-3">
                                                                <small class="mb-0">Parking Charge (<?= $global_currency_format; ?>)</small>
                                                                <p class="mb-0 fw-bolder text-primary"><?= number_format($vehicle_parking_charge, 2); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php if ($prev_itinerary_route_date != $itinerary_route_date) : ?>
                                        <div class="col-3">
                                            <div class=" my-auto">
                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                    <p class="mb-0">Total Cost</p>
                                                    <p class="mb-0"><?= $global_currency_format; ?> <?= number_format(${"total_trip_cost_per_day_" . $itinerary_route_date}, 2); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between  my-3">
                                                    <p class="mb-0">Total Taxes</p>
                                                    <p class="mb-0"><?= $global_currency_format; ?> <?= number_format(${"total_trip_tax_per_day_" . $itinerary_route_date}, 2); ?></p>
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between my-3">
                                                    <h5 class="mb-0">Grand Total</h5>
                                                    <h5 class="mb-0 text-primary fw-bolder"><?= $global_currency_format; ?> <?= number_format(${"total_trip_cost_with_tax_per_day_" . $itinerary_route_date}, 2); ?></h5>

                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                    <?php
                            $prev_itinerary_route_date = $itinerary_route_date;
                        endwhile;
                    endif;

                    //VEHICLE SUMMARY
                    $select_vehicle_time_summary = sqlQUERY_LABEL("SELECT SUM(`total_kms_travelled`) AS total_kms_travelled , SEC_TO_TIME(SUM(TIME_TO_SEC(`total_time`))) AS total_time  FROM `dvi_itinerary_plan_vendor_vehicle_details`  WHERE `itinerary_plan_ID` = '$itinerary_plan_id'  AND `status` = '1'  AND `deleted` = '0' GROUP BY `vehile_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                    while ($fetch_summary_time_data = sqlFETCHARRAY_LABEL($select_vehicle_time_summary)) :
                        $summary_total_kms_travelled =  $fetch_summary_time_data['total_kms_travelled'];
                        $summary_total_time =  $fetch_summary_time_data['total_time'];
                    endwhile;   ?>

                    <div class="card border border-primary p-0 mt-3">
                        <div class="p-3 pb-0">
                            <div class="row justify-content-between align-items-center mb-3">
                                <div class="col-md-auto">
                                    <h5 class="card-header p-0 mb-0">Vehicle Summary</h5>
                                </div>
                                <div class="col-md-auto text-end">
                                    <h6 class="mb-0">
                                        <span class="text-primary me-1">
                                            <i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>Total Distance - <?= $summary_total_kms_travelled ?> KM</span>
                                    </h6>
                                    <h6 class="mb-0">
                                        <span class="text-primary me-1">
                                            <i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>
                                            <?php
                                            $time_parts3 = explode(':', $summary_total_time);
                                            $TOTAL_TIME_hours3 = intval($time_parts3[0]);
                                            $TOTAL_TIME_minutes3 = intval($time_parts3[1]);

                                            echo $TOTAL_TIME_hours3 . " Hours " . $TOTAL_TIME_minutes3 . " Minutes ";
                                            ?></span>
                                    </h6>
                                </div>
                            </div>
                            <div id="vehicle_preview_table_div">
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 15%;">Travel Date</th>
                                                <th style="width: 15%;">Vehicle Type</th>
                                                <th class="small-column" style="width: 20%;">Travel Places</th>
                                                <th style="width: 15%;">Distance (Kms)</th>
                                                <th style="width: 15%;">Sight-seeing distance (Kms)</th>
                                                <th style="width: 15%;">Total Distance (Kms)</th>
                                                <th style="width: 10%;">Time</th>
                                                <th style="width: 10%;">Total Cost</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            <?php
                                            $select_itineary_plan_vehicle = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_vehicle_details_ID`, `vendor_id`,`vehile_type_id`,`itinerary_plan_id`, `itinerary_route_id`, `itinerary_route_date`, `itinerary_route_location_from`, `itinerary_route_location_to`,  SUM(`running_kms`) AS TRAVELLED_KM, SUM(`sight_seeing_kms`) AS SIGHT_SEEING_KMS, SUM(`total_kms_travelled`) AS TOTAL_KM, SEC_TO_TIME(SUM(TIME_TO_SEC(`traveling_time`))) AS TOTAL_TRAVELLING_TIME, SEC_TO_TIME(SUM(TIME_TO_SEC(`sight_seeing_time`))) AS TOTAL_SIGHT_SEEING_TIME, SEC_TO_TIME(SUM(TIME_TO_SEC(`total_time`))) AS TOTAL_TIME, SUM(`total_vehicle_cost_with_gst`) AS TOTAL_COST FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `itinerary_plan_ID` = '$itinerary_plan_id' and `status` = '1' and `deleted` = '0' GROUP BY `itinerary_plan_vendor_vehicle_details_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $vehicle_count = sqlNUMOFROW_LABEL($select_itineary_plan_vehicle);
                                            if ($vehicle_count > 0) :

                                                while ($fetch_itineary_vehicle_info = sqlFETCHARRAY_LABEL($select_itineary_plan_vehicle)) :

                                                    $itinerary_route_date = dateformat_datepicker($fetch_itineary_vehicle_info['itinerary_route_date']);

                                                    $itinerary_plan_vendor_vehicle_details_ID =  $fetch_itineary_vehicle_info['itinerary_plan_vendor_vehicle_details_ID'];
                                                    $itinerary_route_id =  $fetch_itineary_vehicle_info['itinerary_route_id'];

                                                    $vendor_id = $fetch_itineary_vehicle_info['vendor_id'];
                                                    $vehicle_type_id = $fetch_itineary_vehicle_info['vehile_type_id'];
                                                    $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');

                                                    $day = date('j', strtotime($fetch_itineary_vehicle_info['itinerary_route_date']));
                                                    $year = date('Y', strtotime($fetch_itineary_vehicle_info['itinerary_route_date']));
                                                    $month = date('F', strtotime($fetch_itineary_vehicle_info['itinerary_route_date']));

                                                    $itinerary_route_location_from =  $fetch_itineary_vehicle_info['itinerary_route_location_from'];
                                                    $itinerary_route_location_to =  $fetch_itineary_vehicle_info['itinerary_route_location_to'];
                                                    $TRAVELLED_KM =  number_format($fetch_itineary_vehicle_info['TRAVELLED_KM'], 2);
                                                    $SIGHT_SEEING_KMS =  number_format($fetch_itineary_vehicle_info['SIGHT_SEEING_KMS'], 2);
                                                    $TOTAL_KM = number_format($fetch_itineary_vehicle_info['TOTAL_KM'], 2);
                                                    $TOTAL_TRAVELLING_TIME =  $fetch_itineary_vehicle_info['TOTAL_TRAVELLING_TIME'];
                                                    $TOTAL_SIGHT_SEEING_TIME =  $fetch_itineary_vehicle_info['TOTAL_SIGHT_SEEING_TIME'];
                                                    $TOTAL_TRAVELLED_TIME =  $fetch_itineary_vehicle_info['TOTAL_TIME'];

                                                    $time_parts_4 = explode(':', $TOTAL_TRAVELLED_TIME);
                                                    $TOTAL_TRAVELLED_TIME_H = intval($time_parts_4[0]);
                                                    $TOTAL_TRAVELLED_TIME_M = intval($time_parts_4[1]);

                                                    $TOTAL_COST =  $fetch_itineary_vehicle_info['TOTAL_COST'];
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <?php
                                                            if ($itinerary_route_date != $itinerary_route_date_prev) :
                                                                echo $month . " " . $day . " " . $year;
                                                            else :
                                                                echo "";
                                                            endif;
                                                            ?>
                                                        </td>
                                                        <td><?= $vehicle_type  ?></td>
                                                        <td>
                                                            <?php
                                                            if ($itinerary_route_location_from == $itinerary_route_location_to) :
                                                                echo $itinerary_route_location_from;
                                                            else :
                                                                echo $itinerary_route_location_from . " To <br>" . $itinerary_route_location_to;
                                                            endif;
                                                            ?>
                                                        </td>
                                                        <td><?= $TRAVELLED_KM ?></td>
                                                        <td><?= $SIGHT_SEEING_KMS ?></td>
                                                        <td><?= $TOTAL_KM ?></td>
                                                        <td><?= $TOTAL_TRAVELLED_TIME_H . " Hours " . $TOTAL_TRAVELLED_TIME_M . " Minutes "; ?></td>
                                                        <td><?= $global_currency_format; ?> <?= number_format($TOTAL_COST, 2); ?></td>
                                                    </tr>
                                            <?php
                                                    $itinerary_route_date_prev = $itinerary_route_date;
                                                endwhile;
                                            endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <br />

                            <div class="order-calculations">
                                <div>
                                    <table class="table table-borderless">
                                        <thead>
                                            <tr>
                                                <th>Vehicle</th>
                                                <th>Extra KMS </th>
                                                <th>Total Extra KM Charges</th>
                                                <th>Total Vehicle Charges</th>
                                                <th>Total Permit Charges</th>
                                                <th>Total Parking Charges</th>
                                                <th>Total Toll Charges</th>
                                                <th>Total Driver Charges</th>
                                                <th>Cost with tax</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $select_vehicle_summary = sqlQUERY_LABEL("SELECT `itinerary_plan_vendor_summary_ID`, `itinerary_plan_id`, `vendor_id`, `vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `vehicle_count`, `total_kms`, `total_time`, `total_vehicle_permit_cost`, `total_toll_charge`, `total_vehicle_parking_charge`, `total_driver_cost`, `total_driver_gst_amt`, `total_vehicle_per_day_cost`, `total_vehicle_cost`, `total_vehicle_gst_amount`, `total_vehicle_cost_with_gst`, `vendor_margin_percentage`, `vendor_margin`, `extra_km`, `extra_km_charge`, `total_extra_km_charge`, `grand_total` FROM `dvi_itinerary_plan_vendor_summary` WHERE `itinerary_plan_id`='$itinerary_plan_id' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            $vehicle_summary_cout = sqlNUMOFROW_LABEL($select_vehicle_summary);
                                            $total_vehiclecost_summary = 0;
                                            $total_vehicletax_summary = 0;
                                            $total_margin_summary = 0;
                                            $grand_total_vehicle_summary = 0;
                                            while ($fetch_summary_data = sqlFETCHARRAY_LABEL($select_vehicle_summary)) :
                                                $total_vehiclecost_summary = $total_vehiclecost_summary + $fetch_summary_data['total_vehicle_cost'];

                                                $total_vehicletax_summary = $total_vehicletax_summary + ($fetch_summary_data['total_vehicle_gst_amount'] + $fetch_summary_data['total_driver_gst_amt']);

                                                $total_margin_summary = $total_margin_summary + ($fetch_summary_data['vendor_margin']);

                                                $grand_total_vehicle_summary = $grand_total_vehicle_summary + ($fetch_summary_data['grand_total']);

                                                $vendor_id = $fetch_summary_data['vendor_id'];
                                                $vehicle_type_id = $fetch_summary_data['vehicle_type_id'];
                                                $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');

                                                $vendor_margin_percentage = $fetch_summary_data['vendor_margin_percentage'];
                                                $vendor_margin = $fetch_summary_data['vendor_margin'];
                                                $total_extra_km_charge = $fetch_summary_data['total_extra_km_charge'];
                                                $extra_km = $fetch_summary_data['extra_km'];

                                            ?>
                                                <tr>
                                                    <td>
                                                        <span class="text-primary"><?= $vehicle_type ?></span>
                                                        <span class="badge rounded-pill bg-label-primary">COUNT <?= $fetch_summary_data['vehicle_count'] ?></span>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $extra_km; ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $global_currency_format; ?> <?= number_format($total_extra_km_charge, 2); ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $global_currency_format; ?> <?= number_format($fetch_summary_data['total_vehicle_per_day_cost'], 2); ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $global_currency_format; ?> <?= number_format($fetch_summary_data['total_vehicle_permit_cost'], 2); ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $global_currency_format; ?> <?= number_format($fetch_summary_data['total_vehicle_parking_charge'], 2); ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $global_currency_format; ?> <?= number_format($fetch_summary_data['total_toll_charge'], 2); ?></h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0"><?= $global_currency_format; ?> <?= number_format($fetch_summary_data['total_driver_cost'], 2) ?> </h6>
                                                    </td>
                                                    <td>
                                                        <h6 class="mb-0">
                                                            <?= $global_currency_format; ?> <?= number_format(($fetch_summary_data['total_vehicle_cost'] + $total_extra_km_charge), 2); ?> <br />
                                                            <small>
                                                                (+ <?= $global_currency_format; ?> <?= number_format(($fetch_summary_data['total_driver_gst_amt'] + $fetch_summary_data['total_vehicle_gst_amount']), 2); ?> Taxes)
                                                            </small>
                                                        </h6>
                                                    </td>
                                                </tr>

                                            <?php endwhile;
                                            ?>

                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-heading">Total Vehicle Cost</span>
                                    <h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_cost"><?= number_format($total_vehiclecost_summary, 2); ?>
                                        </span></h6>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-heading">Total Vehicle Taxes</span>
                                    <h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_taxes"><?= number_format($total_vehicletax_summary, 2); ?>
                                        </span></h6>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-heading">Vendor Margin (<?= $vendor_margin_percentage ?> %)</span>
                                    <h6 class="mb-0"><?= $global_currency_format; ?> <span id="vendor_margin"><?= number_format($total_margin_summary, 2); ?>
                                        </span></h6>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between  px-3 py-3" style="background-color: #f2f2f2;">
                            <h5 class="text-heading fw-bold mb-0">Grand Vehicle Total </h5>
                            <h5 class="mb-0 fw-bold"><?= $global_currency_format; ?> <span id="overall_vehicle_cost">
                                    <?= number_format($grand_total_vehicle_summary, 2);
                                    ?>
                                </span></h5>
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

                    $("#total_amount_for_hotel").html(totalRoomRate.toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }));

                    $(document).on('click', '.input_plus_button', function(e) {
                        total_no_of_extrabeds = 0;
                        var HOTEL_DETAILS_ID = $(this).data('id');
                        var HOTEL_ROOM_DETAILS_ID = $(this).data('itineraryhotelroomid');
                        var ROW_NO = $(this).data('rowcount');
                        var ROUTE_DATE = $(this).data('routedate');
                        var TYPE = "ADD";

                        $('.input_plus_minus_' + HOTEL_DETAILS_ID).each(function() {
                            no_of_extrabeds = parseInt($(this).val());
                            //alert(no_of_extrabeds);
                            total_no_of_extrabeds += no_of_extrabeds;
                        });

                        var extrabedField = $(this).siblings('.extrabed-field');
                        var currentValue = parseInt(extrabedField.val());
                        var defined_extra_bed_count = '<?= $total_extra_bed ?>';
                        extra_bed_count = parseInt(defined_extra_bed_count);
                        //alert(total_no_of_extrabeds);
                        //alert(extra_bed_count);
                        if (total_no_of_extrabeds < extra_bed_count) {
                            extrabedField.val(currentValue + 1);
                            calculateEXTRABEDCOST(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE, HOTEL_ROOM_DETAILS_ID, TYPE);
                        } else if (total_no_of_extrabeds == extra_bed_count) {
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

                function recalculateVENDOR_DETAILS(Event, PLAN_ID, VENDOR_ID, VENDOR_BRANCH_ID) {

                    if (Event.checked) {
                        $.ajax({
                            type: 'post',
                            url: 'engine/ajax/__ajax_itinerary_plan_vehicle_details.php?type=update_itinerary_plan_vehicle_details_on_changing_vendor',
                            data: {
                                PLAN_ID: PLAN_ID,
                                VENDOR_ID: VENDOR_ID,
                                VENDOR_BRANCH_ID: VENDOR_BRANCH_ID,
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (!response.success) {
                                    // NOT SUCCESS RESPONSE

                                } else {
                                    // SUCCESS RESPOSNE
                                    if (response.i_result == true) {
                                        //$('#vehicle_list').load(' #vehicle_list');
                                        showUPDATED_VEHICLE_LIST(PLAN_ID);
                                    }
                                }
                            }
                        });
                    }
                }

                function showUPDATED_VEHICLE_LIST(PLAN_ID) {
                    $.ajax({
                        type: "POST",
                        url: "engine/ajax/__ajax_itinerary_plan_vehicle_details.php?type=show_itinerary_plan_vehicle_details",
                        data: {
                            itinerary_plan_ID: PLAN_ID,
                        },
                        success: function(response) {
                            $('#vehicle_list').html(response);
                        }
                    });
                }

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

                /*function onchangeHOTELCATEGORY(HOTEL_DETAILS_ID, LOCATION_LATITUDE, LOCATION_LONGITUDE) {
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

                */

                function onchangeHOTELCATEGORY(HOTEL_DETAILS_ID, LOCATION_LATITUDE, LOCATION_LONGITUDE) {
                    var hotel_category_select = $("#hotel_category_" + HOTEL_DETAILS_ID);
                    var hotel_name_select = $("#hotel_name_" + HOTEL_DETAILS_ID);

                    var hotel_category_id = hotel_category_select.val();

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
                            // Clear existing options
                            hotel_name_select.empty();
                            hotel_name_select.append($('<option>', {
                                value: '',
                                text: 'Please select Hotel'
                            }));

                            // Append new options
                            response.forEach(function(option) {
                                hotel_name_select.append($('<option>', {
                                    value: option.value,
                                    text: option.text
                                }));
                            });

                            // Reset room rate and total amount
                            $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                            $("#total_amount_for_hotel").html(" 0");
                        }
                    });
                }


                function onchangeHOTEL(HOTEL_DETAILS_ID) {
                    var hotel_id = $("#hotel_name_" + HOTEL_DETAILS_ID).val();
                    const room_count = <?= $preferred_room_count ?>;

                    for (var i = 1; i <= room_count; i++) {
                        (function(index) {
                            var hotelroom_select = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + index);

                            $.ajax({
                                url: 'engine/ajax/__ajax_get_hotel_dropdown.php?type=selectize_hotel_room',
                                type: "POST",
                                data: {
                                    hotel_id: hotel_id
                                },
                                success: function(response) {
                                    // Clear existing options
                                    hotelroom_select.empty();
                                    hotelroom_select.append($('<option>', {
                                        value: '',
                                        text: 'Please select a Room Type'
                                    }));

                                    // Append new options
                                    response.forEach(function(option) {
                                        hotelroom_select.append($('<option>', {
                                            value: option.value,
                                            text: option.text
                                        }));
                                    });
                                }
                            });
                        })(i);
                    }

                    $(".room_rate_" + HOTEL_DETAILS_ID).html(" 0");
                    $("#total_amount_for_hotel").html(" 0");
                }


                function selectROOMDETAILS(HOTEL_DETAILS_ID, ROW_NO, ROUTE_DATE) {
                    var HOTEL_ID = $("#hotel_name_" + HOTEL_DETAILS_ID).val();
                    var ROOM_TYPE_ID = $("#hotel_roomtype_" + HOTEL_DETAILS_ID + "_" + ROW_NO).val();

                    if (ROOM_TYPE_ID) {
                        var DAYS_COUNT = '<?= $no_of_nights ?>';
                        var ITINERARY_BUDGET = '<?= $expecting_budget ?>';
                        var ROOM_COUNT = '<?= $preferred_room_count ?>';

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
    elseif ($_GET['type'] == 'update_itinerary_plan_vehicle_details_on_adding_hotspot') :

        $errors = [];
        $response = [];

        $itinerary_plan_ID = trim($_POST['PLAN_ID']);
        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call
            $response['success'] = true;

            //RE-CALCULATE VENDOR DETAILS
            $select_itineary_route_plan_info = sqlQUERY_LABEL("SELECT V.`itinerary_plan_vendor_vehicle_details_ID`,V.`vendor_id`, V.`vendor_branch_id`, V.`vehile_type_id`, V.`vehicle_id`,R.`itinerary_route_ID`, R.`location_id`, R.`location_name`, R.`itinerary_route_date`, R.`no_of_km`, R.`next_visiting_location` FROM `dvi_itinerary_route_details` R LEFT JOIN `dvi_itinerary_plan_vendor_vehicle_details` V ON V.`itinerary_route_id` = R.`itinerary_route_ID` WHERE R.`itinerary_plan_ID` = '$itinerary_plan_ID' and R.`status` = '1' and R.`deleted` = '0' ORDER BY R.`itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

            $total_no_of_itineary_plan_details = sqlNUMOFROW_LABEL($select_itineary_route_plan_info);

            if ($total_no_of_itineary_plan_details > 0) :

                $overall_total_trip_cost = 0;
                $overall_total_vehicle_gst_tax_amt = 0;
                $overall_total_driver_charge = 0;
                $overall_total_driver_gst_tax_amt = 0;
                $overall_total_permit_cost = 0;
                $overall_total_vehicle_parking_charge = 0;
                $overall_total_vehicle_toll_charge = 0;
                $route_count = 0;
                $overall_total_extra_km_charge = 0;

                $TOTAL_DISTANCE = 0;
                $TOTAL_TIME_TAKEN = "00:00:00";
                $route_perday_km = getROUTECONFIGURATION('route_perday_km');
                $TOTAL_ALLOWED_KM = $route_perday_km * $total_no_of_itineary_plan_details;

                while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_info)) :
                    $route_count++;
                    $itinerary_plan_vendor_vehicle_details_ID = $fetch_itineary_route_data['itinerary_plan_vendor_vehicle_details_ID'];
                    $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                    $location_id = $fetch_itineary_route_data['location_id'];
                    $location_name = $fetch_itineary_route_data['location_name'];
                    $itinerary_route_date_DB_format = $fetch_itineary_route_data['itinerary_route_date'];
                    $itinerary_route_date = dateformat_datepicker($fetch_itineary_route_data['itinerary_route_date']);
                    $no_of_km = $fetch_itineary_route_data['no_of_km'];
                    $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                    $day = date('j', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                    $year = date('Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                    $month = date('F', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                    $location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_latitude', $location_id);
                    $location_longtitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_longtitude', $location_id);

                    $next_visiting_location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_latitude', $location_id);
                    $next_visiting_location_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_longitude', $location_id);

                    $source_location_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');


                    $select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT  `vehicle_count`, `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details`  WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND  `status` = '1' and `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                    $total_no_of_vehicle_selected = sqlNUMOFROW_LABEL($select_itineary_vehicle_details);

                    if ($total_no_of_vehicle_selected > 0) :
                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_details)) :

                            $vehicletypeid = $fetch_vehicle_data['vehicle_type_id'];
                            $vehicletype_title = getVEHICLETYPE($vehicletypeid, 'get_vehicle_type_title');
                            $vehicle_count = $fetch_vehicle_data['vehicle_count'];
                            $vehicle_occupancy = getOCCUPANCY($vehicletypeid, 'get_occupancy');

                            //SELECT VENDOR AND VEHICLE WITH LOWEST PRICE
                            //AND VEHICLE.`vehicle_location_id` IN ($location_ids_string)

                            $selected_vendor_id =  $fetch_itineary_route_data['vendor_id'];
                            $selected_vendor_branch_id = $fetch_itineary_route_data['vendor_branch_id'];
                            $selected_vehicle_type_id = $fetch_itineary_route_data['vehile_type_id'];
                            $selected_vehicle_id = $fetch_itineary_route_data['vehicle_id'];

                            $select_itineary_vehicle_cost_calculation = sqlQUERY_LABEL(
                                "SELECT VEHICLE_TYPES.`vehicle_type_id`, VEHICLE_TYPES.`driver_batta`, VEHICLE_TYPES.`food_cost`, VEHICLE_TYPES.`accomodation_cost`, VEHICLE_TYPES.`extra_cost`, VEHICLE_TYPES.`driver_early_morning_charges`, VEHICLE_TYPES.`driver_evening_charges`,VEHICLE.`vehicle_type_id`,VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`,VEHICLE.`vehicle_location_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`registration_number`, VEHICLE.`vehicle_fc_expiry_date`, VEHICLE.`insurance_end_date`, VEHICLE.`owner_city`,VEHICLE.`extra_km_charge` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VEHICLE_TYPES ON (VEHICLE.`vehicle_type_id` = VEHICLE_TYPES.`vendor_vehicle_type_ID` AND VEHICLE.`vendor_id`=VEHICLE_TYPES.`vendor_id`) WHERE VEHICLE.`vehicle_fc_expiry_date` >= CURRENT_DATE() AND VEHICLE.`insurance_end_date` >= CURRENT_DATE() AND VEHICLE.`status` = '1' and VEHICLE.`deleted` = '0' AND VEHICLE_TYPES.`vehicle_type_id`='$vehicletypeid' AND VEHICLE.`vehicle_type_id` = '$selected_vehicle_type_id' AND VEHICLE.`vehicle_id`='$selected_vehicle_id' AND VEHICLE.`vendor_id`='$selected_vendor_id' AND VEHICLE.`vendor_branch_id`='$selected_vendor_branch_id' "
                            ) or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                            $total_no_of_select_vehicle_details = sqlNUMOFROW_LABEL($select_itineary_vehicle_cost_calculation);

                            if ($total_no_of_select_vehicle_details > 0) :

                                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_cost_calculation)) :
                                    $vendor_vehicle_count++;

                                    $vehicle_id = $fetch_list_data['vehicle_id'];
                                    $vendor_id = $fetch_list_data['vendor_id'];
                                    $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
                                    $vendor_branch_gst_type =  getBranchLIST($vendor_branch_id, 'branch_gst_type');
                                    $branch_gst_percentage =  getBranchLIST($vendor_branch_id, 'branch_gst_percentage');
                                    $registration_number = $fetch_list_data['registration_number'];
                                    $state_code = substr($registration_number, 0, 2);
                                    $owner_city = $fetch_list_data['owner_city'];
                                    $vehicle_city_name = getCITYLIST('', $owner_city, 'city_label');
                                    $vehicle_fc_expiry_date = $fetch_list_data['vehicle_fc_expiry_date'];
                                    $insurance_end_date = $fetch_list_data['insurance_end_date'];

                                    $vehicle_type_id = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicletypeid, 'get_vendor_vehicle_type_ID');
                                    $vehicle_state = substr($registration_number, 0, 2);
                                    $vehicle_location_id = $fetch_list_data['vehicle_location_id'];
                                    $extra_km_charge = $fetch_list_data['extra_km_charge'];
                                    //DRIVER COST
                                    $driver_batta = $fetch_list_data['driver_batta'];
                                    $driver_accomodation_cost = $fetch_list_data['accomodation_cost'];
                                    $driver_extra_cost = $fetch_list_data['extra_cost'];
                                    $driver_food_cost = $fetch_list_data['food_cost'];
                                    $driver_early_morning_charges = $fetch_list_data['driver_early_morning_charges'];
                                    $driver_evening_charges = $fetch_list_data['driver_evening_charges'];

                                    $vehicle_orign = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
                                    $vehicle_orign_location_latitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_latitude');
                                    $vehicle_orign_location_longtitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_longtitude');

                                    $select_vehicle_permit_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_code`='$state_code' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    while ($fetch_vehicle_state = sqlFETCHARRAY_LABEL($select_vehicle_permit_state)) :
                                        $vehicle_state_id = $fetch_vehicle_state['permit_state_id'];
                                    endwhile;


                                    //VEHICLE CHARGE CALCULATION
                                    $RUNNINGTIME = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_TRAVEL_TIME');
                                    $RUNNING_TIME = sprintf('%02d:%02d:00', ...explode(':', $RUNNINGTIME));

                                    $RUNNING_DISTANCE =
                                        getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE');

                                    $SIGHT_SEEING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TIME');

                                    $SIGHT_SEEING_DISTANCE =
                                        getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_DISTANCE');

                                    //IF DAY 1 ADD PICKUP DIS AND TIME
                                    if ($route_count == 1) :
                                        if ($vehicle_orign != $location_name) :

                                            $distance_from_vehicle_orign_to_pickup_point =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $location_latitude, $location_longtitude);

                                            $pickup_distance = $distance_from_vehicle_orign_to_pickup_point['distance'];
                                            $pickup_duration = $distance_from_vehicle_orign_to_pickup_point['duration'];

                                            //FORMAT DURATION
                                            $parts = explode(' ', $pickup_duration);
                                            $hours = 0;
                                            $minutes = 0;

                                            if (count($parts) >= 2) {
                                                if (
                                                    $parts[1] == 'hour' || $parts[1] == 'hours'
                                                ) {
                                                    $hours = (int)$parts[0];
                                                }
                                                if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
                                                    $minutes = (int)$parts[2];
                                                }
                                            }

                                            // Format the time as HH:MM:SS
                                            $formated_pickup_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
                                        else :
                                            $pickup_distance = 0;
                                            $formated_pickup_duration = "00:00:00";
                                        endif;

                                        $TOTAL_RUNNING_KM
                                            = $RUNNING_DISTANCE + $pickup_distance;

                                        //TOTAL RUNNING TIME
                                        // Convert time strings to seconds
                                        $RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME);
                                        $PICKUP_TIME_INSECONDS = strtotime($formated_pickup_duration);

                                        // Add the seconds
                                        $totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_INSECONDS;

                                        // Convert total seconds back to time format
                                        $TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

                                    else :
                                        $TOTAL_RUNNING_TIME = $RUNNING_TIME;

                                        $TOTAL_RUNNING_KM
                                            = $RUNNING_DISTANCE;
                                    endif;

                                    //if LAST DAY ADD DROP DIS AND TIME
                                    if ($total_no_of_itineary_plan_details == $route_count) :

                                        if ($vehicle_orign != $next_visiting_location) :

                                            $distance_from_drop_point_to_vehicle_orign =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $next_visiting_location_latitude, $next_visiting_location_longitude);

                                            $drop_distance = $distance_from_drop_point_to_vehicle_orign['distance'];
                                            $drop_duration = $distance_from_drop_point_to_vehicle_orign['duration'];

                                            //FORMAT DURATION
                                            $parts = explode(' ', $drop_duration);
                                            $hours = 0;
                                            $minutes = 0;

                                            if (count($parts) >= 2) {
                                                if (
                                                    $parts[1] == 'hour' || $parts[1] == 'hours'
                                                ) {
                                                    $hours = (int)$parts[0];
                                                }
                                                if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
                                                    $minutes = (int)$parts[2];
                                                }
                                            }

                                            // Format the time as HH:MM:SS
                                            $formated_drop_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
                                        else :
                                            $drop_distance = 0;
                                            $formated_drop_duration = "00:00:00";
                                        endif;

                                        $TOTAL_RUNNING_KM
                                            = $RUNNING_DISTANCE + $drop_distance;

                                        //TOTAL SIGHT SEEING TIME
                                        // Convert time strings to seconds
                                        $RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME) - strtotime('00:00:00');
                                        $PICKUP_TIME_IN_SECONDS = strtotime($formated_drop_duration) - strtotime('00:00:00');

                                        // Add the seconds
                                        $totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_IN_SECONDS;

                                        // Convert total seconds back to time format
                                        $TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

                                    else :
                                        $TOTAL_RUNNING_TIME = $RUNNING_TIME;
                                        $TOTAL_RUNNING_KM = $RUNNING_DISTANCE;
                                    endif;

                                    $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_DISTANCE;
                                    $TOTAL_KM = ceil($TOTAL_KM);

                                    //TOTAL TIME
                                    // Convert time durations to seconds
                                    $TOTAL_RUNNING_TIME_IN_SECONDS = strtotime($TOTAL_RUNNING_TIME) - strtotime('00:00:00');
                                    $SIGHT_SEEING_TIME_IN_SECONDS = strtotime($SIGHT_SEEING_TIME) - strtotime('00:00:00');

                                    $totalSeconds1 = $TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS;

                                    $TOTAL_TIME = gmdate('H:i:s', $totalSeconds1);
                                    // echo $TOTAL_TIME . "---" . $TOTAL_KM . "<br>";
                                    // echo $vehicle_city_name . "---" . $source_location_city . "<br>";

                                    //COST CALCULATION

                                    if ($vehicle_city_name == $source_location_city) :
                                        $trip_cost_type = '1';
                                        //LOCAL TRIP
                                        //echo  $TOTAL_TIME . "<br>";
                                        $time_parts = explode(':', $TOTAL_TIME);
                                        $TOTAL_TIME_hours = intval($time_parts[0]);
                                        $TOTAL_TIME_minutes = intval($time_parts[1]);

                                        // Round the total time based on minutes
                                        if ($TOTAL_TIME_minutes < 30) :
                                            $TOTAL_HOURS =  $TOTAL_TIME_hours;
                                        else :
                                            $TOTAL_HOURS = $TOTAL_TIME_hours + 1;
                                        endif;

                                        $hours_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_hour_limit', $vendor_id, $TOTAL_HOURS);

                                        $km_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_km_limit', $vendor_id, $TOTAL_HOURS, $TOTAL_KM);
                                        $kms_limit = getTIMELIMIT($km_time_limit_id, 'km_limit', $vendor_id);

                                        if ($km_time_limit_id == $hours_time_limit_id) :
                                            $time_limit_id = $km_time_limit_id;

                                            $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
                                            $total_trip_cost = $trip_cost * $vehicle_count;
                                        elseif ($km_time_limit_id > $hours_time_limit_id) :
                                            //IF KM IS GREATER
                                            $time_limit_id = $km_time_limit_id;

                                            $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);

                                            $total_trip_cost = $trip_cost * $vehicle_count;
                                        elseif ($km_time_limit_id < $hours_time_limit_id) :
                                            //IF TIME IS GREATER
                                            $time_limit_id = $hours_time_limit_id;

                                            $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
                                            $total_trip_cost = $trip_cost * $vehicle_count;
                                        endif;

                                    //echo $total_trip_cost . "<br>";
                                    else :
                                        $trip_cost_type = '2'; //OUTSTATION TRIP
                                        $kms_limit_id = getKMLIMIT($vehicle_type_id, 'get_kms_limit_id', $vendor_id);
                                        $kms_limit = getKMLIMIT($vehicle_type_id, 'get_kms_limit', $vendor_id);

                                        $trip_cost = getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $kms_limit_id, $userID);

                                        $total_trip_cost = $trip_cost * $vehicle_count;
                                    endif;

                                    //CALCULATE GST FOR VEHICLE CHARGES
                                    if ($vendor_branch_gst_type == 1) :
                                        // For Inclusive GST
                                        $new_total_trip_cost = $total_trip_cost / (1 + ($branch_gst_percentage / 100));

                                        $vehicle_gst_tax_amt = ($total_trip_cost - $new_total_trip_cost);

                                    elseif ($vendor_branch_gst_type == 2) :
                                        // For Exclusive GST
                                        $new_total_trip_cost = $total_trip_cost;
                                        $vehicle_gst_tax_amt = ($total_trip_cost * $branch_gst_percentage / 100);
                                    endif;

                                    $overall_total_trip_cost += $new_total_trip_cost;
                                    $overall_total_vehicle_gst_tax_amt += $vehicle_gst_tax_amt;
                                    // $overall_total_extra_km_charge += $total_extra_km_charge;

                                    //DRIVER COST CALCULATION
                                    $driver_charges = ($driver_batta +  $driver_accomodation_cost + $driver_extra_cost + $driver_food_cost) * $vehicle_count;
                                    //CALCULATE GST FOR DRIVER CHARGES
                                    if ($vendor_branch_gst_type == 1) :
                                        // For Inclusive GST
                                        $new_driver_charges = $driver_charges / (1 + ($branch_gst_percentage / 100));

                                        $driver_gst_tax_amt = ($driver_charges - $new_driver_charges);

                                    elseif ($vendor_branch_gst_type == 2) :
                                        // For Exclusive GST
                                        $new_driver_charges = $driver_charges;
                                        $driver_gst_tax_amt = ($driver_charges * $branch_gst_percentage / 100);
                                    endif;

                                    $overall_total_driver_charge += $new_driver_charges;
                                    $overall_total_driver_gst_tax_amt += $driver_gst_tax_amt;

                                    // PERMIT COST CALCULATION
                                    //GET STATE DETAILS OF SOURCE AND DESTINATION
                                    if ($location_name == $next_visiting_location) :
                                        $filter_by = "  `source_location`='$location_name' ";
                                    else :
                                        $filter_by = "  `destination_location` ='$next_visiting_location' AND `source_location`='$location_name' ";
                                    endif;

                                    $get_location_details = sqlQUERY_LABEL("SELECT `source_location_state`,`destination_location_state` FROM `dvi_stored_locations` WHERE  {$filter_by} ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
                                    if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                                        while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                                            if ($location_name == $next_visiting_location) :
                                                $destination_location_state =
                                                    $source_location_state = $fetch_location_data['source_location_state'];
                                            else :
                                                $destination_location_state = $fetch_location_data['destination_location_state'];
                                                $source_location_state = $fetch_location_data['source_location_state'];
                                            endif;
                                        endwhile;
                                    endif;

                                    $source_state_id = getVEHICLE_PERMIT_DETAILS($source_location_state, 'GET_PERMIT_STATE_ID');

                                    $destination_state_id = getVEHICLE_PERMIT_DETAILS($destination_location_state, 'GET_PERMIT_STATE_ID');

                                    $permit_cost = 0;

                                    $permit_cost_collected_variable = "permit_cost_collected_" . $destination_state_id . "_" . $vehicle_id;
                                    $permit_cost_day_count_variable = $permit_cost_collected_variable . "_day_count";

                                    if (${$permit_cost_collected_variable} == 1) :
                                        ${$permit_cost_day_count_variable}++;
                                    endif;

                                    if ($vehicle_state_id == $destination_state_id && $source_state_id == $destination_state_id) :
                                        //SAME STATE 
                                        $permit_cost = 0;
                                    else :
                                        //DIFFERENT STATE
                                        if ((${$permit_cost_collected_variable} != 1) || ((${$permit_cost_collected_variable} == 1) && ${$permit_cost_day_count_variable} == 8)
                                        ) :
                                            $select_vehicle_permit_cost = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted`='0' AND `status`='1' AND `vendor_id`='$vendor_id' AND `vehicle_type_id`='$vehicle_type_id' AND `source_state_id`='$vehicle_state_id' AND `destination_state_id`='$destination_state_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            while ($fetch_vehicle_permit_cost = sqlFETCHARRAY_LABEL($select_vehicle_permit_cost)) :
                                                $permit_cost = $fetch_vehicle_permit_cost['permit_cost'];
                                                ${$permit_cost_collected_variable} = 1;
                                                ${$permit_cost_day_count_variable} = 1;
                                            endwhile;
                                        endif;
                                    endif;
                                    $permit_cost =  $permit_cost * $vehicle_count;
                                    $overall_total_permit_cost += $permit_cost;

                                    //TOLL CHARGE CALCULATION
                                    $VEHICLE_TOLL_CHARGE = getVEHICLE_TOLL_CHARGES($vehicletypeid, $location_id) * $vehicle_count;
                                    $overall_total_vehicle_toll_charge += $VEHICLE_TOLL_CHARGE;

                                    //PARKING CHARGE CALCULATION
                                    $VEHICLE_PARKING_CHARGE =
                                        getHOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'total_hotspot_parking_charges') * $vehicle_count;
                                    $overall_total_vehicle_parking_charge += $VEHICLE_PARKING_CHARGE;

                                    $total_vendor_cost_per_day = $new_total_trip_cost  + $new_driver_charges +  $permit_cost + $VEHICLE_PARKING_CHARGE + $VEHICLE_TOLL_CHARGE;
                                    $total_tax_per_day = $vehicle_gst_tax_amt + $driver_gst_tax_amt;

                                    $total_vendor_cost_per_day_with_tax = $total_vendor_cost_per_day + $total_tax_per_day;

                                    $TOTAL_DISTANCE = $TOTAL_DISTANCE + $TOTAL_KM;

                                    //TOTAL TIME TAKEN
                                    // Convert time durations to seconds
                                    $TOTAL_TIME_TAKEN_IN_SECONDS = strtotime($TOTAL_TIME_TAKEN) - strtotime('00:00:00');

                                    $totalSeconds3 = $TOTAL_TIME_TAKEN_IN_SECONDS + ($TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS);

                                    $TOTAL_TIME_TAKEN = gmdate('H:i:s', $totalSeconds3);

                                    $arrFields_vehicle = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location_from`', '`itinerary_route_location_to`', '`vendor_id`', '`vendor_branch_id`', '`vehile_type_id`', '`vehicle_id`', '`vehicle_count`', '`running_kms`', '`sight_seeing_kms`', '`total_kms_travelled`', '`traveling_time`', '`sight_seeing_time`', '`total_time`', '`cost_type`',  '`local_time_limit_id`', '`outstation_km_limit_id`', '`extra_km_charge`',  '`driver_bhatta`', '`driver_food_cost`', '`driver_accomodation_cost`', '`extra_cost`', '`total_driver_cost`', '`total_driver_gst_amt`', '`toll_charge`', '`vehicle_parking_charge`',  '`vehicle_permit_cost`', '`vehicle_gst_type`', '`vehicle_gst_percentage`', 'vehicle_per_day_cost', '`vehicle_gst_amount`', '`total_vehicle_cost`', '`total_vehicle_cost_with_gst`',  '`createdby`', '`status`');

                                    $arrValues_vehicle = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date_DB_format", "$location_name", "$next_visiting_location", "$vendor_id", "$vendor_branch_id", "$vehicle_type_id", "$vehicle_id", "$vehicle_count", "$TOTAL_RUNNING_KM", "$SIGHT_SEEING_DISTANCE", "$TOTAL_KM", "$TOTAL_RUNNING_TIME", "$SIGHT_SEEING_TIME", "$TOTAL_TIME", "$trip_cost_type", "$time_limit_id", "$kms_limit_id", "$extra_km_charge", "$driver_batta", "$driver_food_cost", "$driver_accomodation_cost", "$driver_extra_cost", "$new_driver_charges", "$driver_gst_tax_amt", "$VEHICLE_TOLL_CHARGE", "$VEHICLE_PARKING_CHARGE", "$permit_cost", "$vendor_branch_gst_type", "$branch_gst_percentage", "$new_total_trip_cost", "$vehicle_gst_tax_amt", "$total_vendor_cost_per_day", "$total_vendor_cost_per_day_with_tax", "$logged_user_id", "1");


                                    if ($itinerary_plan_vendor_vehicle_details_ID != "" && $itinerary_plan_vendor_vehicle_details_ID != 0) :

                                        $sqlWhere_vehicle = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_plan_vendor_vehicle_details_ID` = '$itinerary_plan_vendor_vehicle_details_ID' ";

                                        //UPDATE DETAILS
                                        if (sqlACTIONS(
                                            "UPDATE",
                                            "dvi_itinerary_plan_vendor_vehicle_details",
                                            $arrFields_vehicle,
                                            $arrValues_vehicle,
                                            $sqlWhere_vehicle
                                        )) :
                                        endif;

                                    else :

                                        //INSERT ROUTE VENDOR VEHICLE DETAILS
                                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_vehicle_details", $arrFields_vehicle, $arrValues_vehicle, '')) :

                                        endif;
                                    endif;
                                endwhile;
                            endif;

                        endwhile;

                    endif;

                endwhile;

                //CALCULATE VEHICLE SUMMARY
                $select_vehicle_summary = sqlQUERY_LABEL("SELECT  `itinerary_plan_id`,  `itinerary_plan_vendor_summary_id`, `vendor_branch_id`,`vehicle_id`,`vendor_id`,`vehile_type_id`,`vehicle_count`,SUM(`total_kms_travelled`) AS total_kms_travelled , SEC_TO_TIME(SUM(TIME_TO_SEC(`total_time`))) AS total_time, SUM(`total_driver_cost`) AS total_driver_cost,  SUM(`total_driver_gst_amt`) AS total_driver_gst_amt,   SUM(`toll_charge`) AS toll_charge,   SUM(`vehicle_parking_charge`) AS vehicle_parking_charge,  SUM(`vehicle_permit_cost`) AS vehicle_permit_cost,  SUM(`vehicle_gst_amount`) AS vehicle_gst_amount, SUM(`vehicle_per_day_cost`) AS vehicle_per_day_cost,  SUM(`total_vehicle_cost`) AS total_vehicle_cost, SUM(`total_vehicle_cost_with_gst`) AS total_vehicle_cost_with_gst  FROM `dvi_itinerary_plan_vendor_vehicle_details`  WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `status` = '1'  AND `deleted` = '0' GROUP BY `vehile_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $vehicle_summary_cout = sqlNUMOFROW_LABEL($select_vehicle_summary);

                while ($fetch_summary_data = sqlFETCHARRAY_LABEL($select_vehicle_summary)) :

                    $itinerary_plan_vendor_summary_id = $fetch_summary_data['itinerary_plan_vendor_summary_id'];
                    $itinerary_route_id = $fetch_summary_data['itinerary_route_id'];
                    $vendor_branch_id = $fetch_summary_data['vendor_branch_id'];
                    $vendor_id = $fetch_summary_data['vendor_id'];
                    $vehicle_id = $fetch_summary_data['vehicle_id'];
                    $vehicle_type_id = $fetch_summary_data['vehile_type_id'];
                    $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
                    $vehicle_count = $fetch_summary_data['vehicle_count'];
                    $total_kms_travelled = $fetch_summary_data['total_kms_travelled'];
                    $total_time = $fetch_summary_data['total_time'];

                    $vehicle_permit_cost = $fetch_summary_data['vehicle_permit_cost'];
                    $toll_charge = $fetch_summary_data['toll_charge'];
                    $vehicle_parking_charge = $fetch_summary_data['vehicle_parking_charge'];
                    $total_driver_cost = $fetch_summary_data['total_driver_cost'];
                    $total_driver_gst_amt =  $fetch_summary_data['total_driver_gst_amt'];
                    $vehicle_per_day_cost = $fetch_summary_data['vehicle_per_day_cost'];

                    $total_vehicle_cost = $fetch_summary_data['total_vehicle_cost'];
                    $vehicle_gst_amount = $fetch_summary_data['vehicle_gst_amount'];

                    //EXTRA KM CHARGE
                    $extra_km_charges = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_extra_km_charge');
                    if ($TOTAL_ALLOWED_KM < $total_kms_travelled) :
                        $extra_km = $TOTAL_ALLOWED_KM - $total_kms_travelled;
                        $total_extra_km_charge =  ($extra_km * $extra_km_charges) * $vehicle_count;
                    else :
                        $extra_km = 0;
                        $total_extra_km_charge = 0;
                    endif;

                    $total_vehicle_cost_with_gst = $fetch_summary_data['total_vehicle_cost_with_gst'] + $total_extra_km_charge;

                    $margin_percentage = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_margin_percentage');
                    $VENDOR_MARGIN = $total_vehicle_cost_with_gst * ($margin_percentage / 100);

                    $grand_total_vehicle_cost = $total_vehicle_cost_with_gst + $VENDOR_MARGIN;

                    $arrFields_vehicle_summary = array('`itinerary_plan_id`', '`vendor_id`', '`vehicle_type_id`', '`vehicle_id`', '`vendor_branch_id`', '`vehicle_count`', '`total_kms`', '`total_time`', '`total_vehicle_permit_cost`', '`total_toll_charge`', '`total_vehicle_parking_charge`', '`total_driver_cost`', '`total_driver_gst_amt`', '`total_vehicle_per_day_cost`', '`total_vehicle_cost`', '`extra_km`', '`extra_km_charge`', '`total_extra_km_charge`', '`total_vehicle_gst_amount`',  '`total_vehicle_cost_with_gst`', '`vendor_margin_percentage`', '`vendor_margin`', '`grand_total`',  '`createdby`', '`status`');


                    $arrValues_vehicle_summary = array("$itinerary_plan_ID",  "$vendor_id", "$vehicle_type_id", "$vehicle_id", "$vendor_branch_id",   "$vehicle_count", "$total_kms_travelled", "$total_time", "$vehicle_permit_cost", "$toll_charge", "$vehicle_parking_charge", "$total_driver_cost", "$total_driver_gst_amt", "$vehicle_per_day_cost", "$total_vehicle_cost", "$extra_km", "$extra_km_charges", "$total_extra_km_charge", "$vehicle_gst_amount", "$total_vehicle_cost_with_gst", "$margin_percentage", "$vendor_margin", "$grand_total_vehicle_cost", "$logged_user_id", "1");

                    if ($itinerary_plan_vendor_summary_id == 0) :

                        //INSERT VENDOR SUMMARY DETAILS
                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_summary", $arrFields_vehicle_summary, $arrValues_vehicle_summary, '')) :
                            $itinerary_plan_vendor_summary_id = sqlINSERTID_LABEL();

                            $arrFields_vendor_details = array('`itinerary_plan_vendor_summary_id`');
                            $arrValues_vendor_details = array("$itinerary_plan_vendor_summary_id");
                            $sqlWhere_vendor_details = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehile_type_id` = '$vehicle_type_id' ";
                            //UPDATE SUMMARY ID IN VEHICLE DETAILS
                            if (sqlACTIONS(
                                "UPDATE",
                                "dvi_itinerary_plan_vendor_vehicle_details",
                                $arrFields_vendor_details,
                                $arrValues_vendor_details,
                                $sqlWhere_vendor_details
                            )) :
                            endif;

                        endif;

                    else :
                        $sqlWhere_vehicle_summary = " `itinerary_plan_id` = '$itinerary_plan_ID' AND  `itinerary_plan_vendor_summary_id` = ' $itinerary_plan_vendor_summary_id' ";
                        if (sqlACTIONS(
                            "UPDATE",
                            "dvi_itinerary_plan_vendor_summary",
                            $arrFields_vehicle_summary,
                            $arrValues_vehicle_summary,
                            $sqlWhere_vehicle_summary
                        )) :
                        endif;
                    endif;

                endwhile;

                $response['i_result'] = true;
                $response['result_success'] = true;
            endif;

        endif;
        echo json_encode($response);

    elseif ($_GET['type'] == 'update_itinerary_plan_vehicle_details_on_changing_vendor') :

        $errors = [];
        $response = [];

        $itinerary_plan_ID = trim($_POST['PLAN_ID']);
        $itinerary_vendor_id = trim($_POST['VENDOR_ID']);
        $itinerary_vendor_branch_id = trim($_POST['VENDOR_BRANCH_ID']);


        if (!empty($errors)) :
            //error call
            $response['success'] = false;
            $response['errors'] = $errors;
        else :
            //success call
            $response['success'] = true;

            //RE-CALCULATE VENDOR DETAILS
            $select_itineary_route_plan_info = sqlQUERY_LABEL("SELECT V.`itinerary_plan_vendor_vehicle_details_ID`,V.`vendor_id`, V.`vendor_branch_id`, V.`vehile_type_id`, V.`vehicle_id`,R.`itinerary_route_ID`, R.`location_id`, R.`location_name`, R.`itinerary_route_date`, R.`no_of_km`, R.`next_visiting_location` FROM `dvi_itinerary_route_details` R LEFT JOIN `dvi_itinerary_plan_vendor_vehicle_details` V ON V.`itinerary_route_id` = R.`itinerary_route_ID` WHERE R.`itinerary_plan_ID` = '$itinerary_plan_ID' and R.`status` = '1' and R.`deleted` = '0' ORDER BY R.`itinerary_route_ID`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

            $total_no_of_itineary_plan_details = sqlNUMOFROW_LABEL($select_itineary_route_plan_info);

            if ($total_no_of_itineary_plan_details > 0) :

                $overall_total_trip_cost = 0;
                $overall_total_vehicle_gst_tax_amt = 0;
                $overall_total_driver_charge = 0;
                $overall_total_driver_gst_tax_amt = 0;
                $overall_total_permit_cost = 0;
                $overall_total_vehicle_parking_charge = 0;
                $overall_total_vehicle_toll_charge = 0;
                $route_count = 0;
                $overall_total_extra_km_charge = 0;

                $TOTAL_DISTANCE = 0;
                $TOTAL_TIME_TAKEN = "00:00:00";
                $route_perday_km = getROUTECONFIGURATION('route_perday_km');
                $TOTAL_ALLOWED_KM = $route_perday_km * $total_no_of_itineary_plan_details;

                while ($fetch_itineary_route_data = sqlFETCHARRAY_LABEL($select_itineary_route_plan_info)) :
                    $route_count++;
                    $itinerary_plan_vendor_vehicle_details_ID = $fetch_itineary_route_data['itinerary_plan_vendor_vehicle_details_ID'];
                    $itinerary_route_ID = $fetch_itineary_route_data['itinerary_route_ID'];
                    $location_id = $fetch_itineary_route_data['location_id'];
                    $location_name = $fetch_itineary_route_data['location_name'];
                    $itinerary_route_date_DB_format = $fetch_itineary_route_data['itinerary_route_date'];
                    $itinerary_route_date = dateformat_datepicker($fetch_itineary_route_data['itinerary_route_date']);
                    $no_of_km = $fetch_itineary_route_data['no_of_km'];
                    $next_visiting_location = $fetch_itineary_route_data['next_visiting_location'];
                    $day = date('j', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                    $year = date('Y', strtotime($fetch_itineary_route_data['itinerary_route_date']));
                    $month = date('F', strtotime($fetch_itineary_route_data['itinerary_route_date']));

                    $location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_latitude', $location_id);
                    $location_longtitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'location_longtitude', $location_id);

                    $next_visiting_location_latitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_latitude', $location_id);
                    $next_visiting_location_longitude = getITINEARYROUTE_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'next_visiting_location_longitude', $location_id);

                    $source_location_city = getSTOREDLOCATIONDETAILS($location_id, 'SOURCE_CITY');


                    $select_itineary_vehicle_details = sqlQUERY_LABEL("SELECT  `vehicle_count`, `vehicle_type_id` FROM `dvi_itinerary_plan_vehicle_details`  WHERE `itinerary_plan_id` = '$itinerary_plan_ID' AND  `status` = '1' and `deleted` = '0' ") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());

                    $total_no_of_vehicle_selected = sqlNUMOFROW_LABEL($select_itineary_vehicle_details);

                    if ($total_no_of_vehicle_selected > 0) :
                        while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_details)) :

                            $vehicletypeid = $fetch_vehicle_data['vehicle_type_id'];
                            $vehicletype_title = getVEHICLETYPE($vehicletypeid, 'get_vehicle_type_title');
                            $vehicle_count = $fetch_vehicle_data['vehicle_count'];
                            $vehicle_occupancy = getOCCUPANCY($vehicletypeid, 'get_occupancy');

                            //SELECT VENDOR AND VEHICLE WITH LOWEST PRICE
                            //AND VEHICLE.`vehicle_location_id` IN ($location_ids_string)

                            $selected_vendor_id =  $itinerary_vendor_id;
                            $selected_vendor_branch_id = $itinerary_vendor_branch_id;
                            // $selected_vehicle_type_id = $fetch_itineary_route_data['vehile_type_id'];
                            // $selected_vehicle_id = $fetch_itineary_route_data['vehicle_id'];

                            $select_itineary_vehicle_cost_calculation = sqlQUERY_LABEL(
                                "SELECT VEHICLE_TYPES.`vehicle_type_id`, VEHICLE_TYPES.`driver_batta`, VEHICLE_TYPES.`food_cost`, VEHICLE_TYPES.`accomodation_cost`, VEHICLE_TYPES.`extra_cost`, VEHICLE_TYPES.`driver_early_morning_charges`, VEHICLE_TYPES.`driver_evening_charges`,VEHICLE.`vehicle_type_id`,VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`,VEHICLE.`vehicle_location_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`registration_number`, VEHICLE.`vehicle_fc_expiry_date`, VEHICLE.`insurance_end_date`, VEHICLE.`owner_city`,VEHICLE.`extra_km_charge` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VEHICLE_TYPES ON (VEHICLE.`vehicle_type_id` = VEHICLE_TYPES.`vendor_vehicle_type_ID` AND VEHICLE.`vendor_id`=VEHICLE_TYPES.`vendor_id`) WHERE VEHICLE.`vehicle_fc_expiry_date` >= CURRENT_DATE() AND VEHICLE.`insurance_end_date` >= CURRENT_DATE() AND VEHICLE.`status` = '1' and VEHICLE.`deleted` = '0' AND VEHICLE_TYPES.`vehicle_type_id`='$vehicletypeid'  AND VEHICLE.`vendor_id`='$selected_vendor_id' AND VEHICLE.`vendor_branch_id`='$selected_vendor_branch_id' "
                            ) or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());

                            $total_no_of_select_vehicle_details = sqlNUMOFROW_LABEL($select_itineary_vehicle_cost_calculation);

                            if ($total_no_of_select_vehicle_details > 0) :

                                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_itineary_vehicle_cost_calculation)) :
                                    $vendor_vehicle_count++;

                                    $vehicle_id = $fetch_list_data['vehicle_id'];
                                    $vendor_id = $fetch_list_data['vendor_id'];
                                    $vendor_branch_id = $fetch_list_data['vendor_branch_id'];
                                    $vendor_branch_gst_type =  getBranchLIST($vendor_branch_id, 'branch_gst_type');
                                    $branch_gst_percentage =  getBranchLIST($vendor_branch_id, 'branch_gst_percentage');
                                    $registration_number = $fetch_list_data['registration_number'];
                                    $state_code = substr($registration_number, 0, 2);
                                    $owner_city = $fetch_list_data['owner_city'];
                                    $vehicle_city_name = getCITYLIST('', $owner_city, 'city_label');
                                    $vehicle_fc_expiry_date = $fetch_list_data['vehicle_fc_expiry_date'];
                                    $insurance_end_date = $fetch_list_data['insurance_end_date'];

                                    $vehicle_type_id = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicletypeid, 'get_vendor_vehicle_type_ID');
                                    $vehicle_state = substr($registration_number, 0, 2);
                                    $vehicle_location_id = $fetch_list_data['vehicle_location_id'];
                                    $extra_km_charge = $fetch_list_data['extra_km_charge'];
                                    //DRIVER COST
                                    $driver_batta = $fetch_list_data['driver_batta'];
                                    $driver_accomodation_cost = $fetch_list_data['accomodation_cost'];
                                    $driver_extra_cost = $fetch_list_data['extra_cost'];
                                    $driver_food_cost = $fetch_list_data['food_cost'];
                                    $driver_early_morning_charges = $fetch_list_data['driver_early_morning_charges'];
                                    $driver_evening_charges = $fetch_list_data['driver_evening_charges'];

                                    $vehicle_orign = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
                                    $vehicle_orign_location_latitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_latitude');
                                    $vehicle_orign_location_longtitude = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'location_longtitude');

                                    $select_vehicle_permit_state = sqlQUERY_LABEL("SELECT `permit_state_id`, `state_name`  FROM `dvi_permit_state` WHERE `state_code`='$state_code' AND `deleted`='0' AND `status`='1'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                    while ($fetch_vehicle_state = sqlFETCHARRAY_LABEL($select_vehicle_permit_state)) :
                                        $vehicle_state_id = $fetch_vehicle_state['permit_state_id'];
                                    endwhile;


                                    //VEHICLE CHARGE CALCULATION
                                    $RUNNINGTIME = getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_TRAVEL_TIME');
                                    $RUNNING_TIME = sprintf('%02d:%02d:00', ...explode(':', $RUNNINGTIME));

                                    $RUNNING_DISTANCE =
                                        getSTOREDLOCATIONDETAILS($location_id, 'TOTAL_DISTANCE');

                                    $SIGHT_SEEING_TIME = getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_TIME');

                                    $SIGHT_SEEING_DISTANCE =
                                        getITINEARY_ROUTE_HOTSPOT_DETAILS('', $itinerary_plan_ID, $itinerary_route_ID, 'SIGHT_SEEING_DISTANCE');
                                    //  echo  $itinerary_route_ID . "----";
                                    // echo  $SIGHT_SEEING_DISTANCE . "----";

                                    //IF DAY 1 ADD PICKUP DIS AND TIME
                                    if ($route_count == 1) :
                                        if ($vehicle_orign != $location_name) :

                                            $distance_from_vehicle_orign_to_pickup_point =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $location_latitude, $location_longtitude);

                                            $pickup_distance = $distance_from_vehicle_orign_to_pickup_point['distance'];
                                            $pickup_duration = $distance_from_vehicle_orign_to_pickup_point['duration'];

                                            //FORMAT DURATION
                                            $parts = explode(' ', $pickup_duration);
                                            $hours = 0;
                                            $minutes = 0;

                                            if (count($parts) >= 2) {
                                                if (
                                                    $parts[1] == 'hour' || $parts[1] == 'hours'
                                                ) {
                                                    $hours = (int)$parts[0];
                                                }
                                                if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
                                                    $minutes = (int)$parts[2];
                                                }
                                            }

                                            // Format the time as HH:MM:SS
                                            $formated_pickup_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
                                        else :
                                            $pickup_distance = 0;
                                            $formated_pickup_duration = "00:00:00";
                                        endif;

                                        $TOTAL_RUNNING_KM
                                            = $RUNNING_DISTANCE + $pickup_distance;

                                        //TOTAL RUNNING TIME
                                        // Convert time strings to seconds
                                        $RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME);
                                        $PICKUP_TIME_INSECONDS = strtotime($formated_pickup_duration);

                                        // Add the seconds
                                        $totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_INSECONDS;

                                        // Convert total seconds back to time format
                                        $TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

                                    else :
                                        $TOTAL_RUNNING_TIME = $RUNNING_TIME;

                                        $TOTAL_RUNNING_KM
                                            = $RUNNING_DISTANCE;
                                    endif;

                                    //if LAST DAY ADD DROP DIS AND TIME
                                    if ($total_no_of_itineary_plan_details == $route_count) :

                                        if ($vehicle_orign != $next_visiting_location) :

                                            $distance_from_drop_point_to_vehicle_orign =  calculateDistanceAndDuration($vehicle_orign_location_latitude, $vehicle_orign_location_longtitude, $next_visiting_location_latitude, $next_visiting_location_longitude);

                                            $drop_distance = $distance_from_drop_point_to_vehicle_orign['distance'];
                                            $drop_duration = $distance_from_drop_point_to_vehicle_orign['duration'];

                                            //FORMAT DURATION
                                            $parts = explode(' ', $drop_duration);
                                            $hours = 0;
                                            $minutes = 0;

                                            if (count($parts) >= 2) {
                                                if (
                                                    $parts[1] == 'hour' || $parts[1] == 'hours'
                                                ) {
                                                    $hours = (int)$parts[0];
                                                }
                                                if (count($parts) >= 4 && ($parts[3] == 'min' || $parts[3] == 'mins')) {
                                                    $minutes = (int)$parts[2];
                                                }
                                            }

                                            // Format the time as HH:MM:SS
                                            $formated_drop_duration =  sprintf('%02d:%02d:00', $hours, $minutes);
                                        else :
                                            $drop_distance = 0;
                                            $formated_drop_duration = "00:00:00";
                                        endif;

                                        $TOTAL_RUNNING_KM
                                            = $RUNNING_DISTANCE + $drop_distance;

                                        //TOTAL SIGHT SEEING TIME
                                        // Convert time strings to seconds
                                        $RUNNING_TIME_IN_SECONDS = strtotime($RUNNING_TIME) - strtotime('00:00:00');
                                        $PICKUP_TIME_IN_SECONDS = strtotime($formated_drop_duration) - strtotime('00:00:00');

                                        // Add the seconds
                                        $totalSeconds = $RUNNING_TIME_IN_SECONDS + $PICKUP_TIME_IN_SECONDS;

                                        // Convert total seconds back to time format
                                        $TOTAL_RUNNING_TIME = gmdate('H:i:s', $totalSeconds);

                                    else :
                                        $TOTAL_RUNNING_TIME = $RUNNING_TIME;
                                        $TOTAL_RUNNING_KM = $RUNNING_DISTANCE;
                                    endif;

                                    $TOTAL_KM = $TOTAL_RUNNING_KM + $SIGHT_SEEING_DISTANCE;
                                    $TOTAL_KM = ceil($TOTAL_KM);

                                    //TOTAL TIME
                                    // Convert time durations to seconds
                                    $TOTAL_RUNNING_TIME_IN_SECONDS = strtotime($TOTAL_RUNNING_TIME) - strtotime('00:00:00');
                                    $SIGHT_SEEING_TIME_IN_SECONDS = strtotime($SIGHT_SEEING_TIME) - strtotime('00:00:00');

                                    $totalSeconds1 = $TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS;

                                    $TOTAL_TIME = gmdate('H:i:s', $totalSeconds1);
                                    // echo $TOTAL_TIME . "---" . $TOTAL_KM . "<br>";
                                    // echo $vehicle_city_name . "---" . $source_location_city . "<br>";

                                    //COST CALCULATION

                                    if ($vehicle_city_name == $source_location_city) :
                                        $trip_cost_type = '1';
                                        //LOCAL TRIP
                                        //echo  $TOTAL_TIME . "<br>";
                                        $time_parts = explode(':', $TOTAL_TIME);
                                        $TOTAL_TIME_hours = intval($time_parts[0]);
                                        $TOTAL_TIME_minutes = intval($time_parts[1]);

                                        // Round the total time based on minutes
                                        if ($TOTAL_TIME_minutes < 30) :
                                            $TOTAL_HOURS =  $TOTAL_TIME_hours;
                                        else :
                                            $TOTAL_HOURS = $TOTAL_TIME_hours + 1;
                                        endif;

                                        $hours_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_hour_limit', $vendor_id, $TOTAL_HOURS);

                                        $km_time_limit_id = getTIMELIMIT($vehicle_type_id, 'get_km_limit', $vendor_id, $TOTAL_HOURS, $TOTAL_KM);
                                        $kms_limit = getTIMELIMIT($km_time_limit_id, 'km_limit', $vendor_id);

                                        if ($km_time_limit_id == $hours_time_limit_id) :
                                            $time_limit_id = $km_time_limit_id;

                                            $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
                                            $total_trip_cost = $trip_cost * $vehicle_count;
                                        elseif ($km_time_limit_id > $hours_time_limit_id) :
                                            //IF KM IS GREATER
                                            $time_limit_id = $km_time_limit_id;

                                            $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);

                                            $total_trip_cost = $trip_cost * $vehicle_count;
                                        elseif ($km_time_limit_id < $hours_time_limit_id) :
                                            //IF TIME IS GREATER
                                            $time_limit_id = $hours_time_limit_id;

                                            $trip_cost = getVEHICLE_LOCAL_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $userID, $time_limit_id);
                                            $total_trip_cost = $trip_cost * $vehicle_count;
                                        endif;

                                    //echo $total_trip_cost . "<br>";
                                    else :
                                        $trip_cost_type = '2'; //OUTSTATION TRIP
                                        $kms_limit_id = getKMLIMIT($vehicle_type_id, 'get_kms_limit_id', $vendor_id);
                                        $kms_limit = getKMLIMIT($vehicle_type_id, 'get_kms_limit', $vendor_id);

                                        $trip_cost = getVEHICLE_OUTSTATION_PRICEBOOK_COST($day, $year, $month, $vendor_id, $vehicle_type_id, $kms_limit_id, $userID);

                                        $total_trip_cost = $trip_cost * $vehicle_count;
                                    endif;

                                    //CALCULATE GST FOR VEHICLE CHARGES
                                    if ($vendor_branch_gst_type == 1) :
                                        // For Inclusive GST
                                        $new_total_trip_cost = $total_trip_cost / (1 + ($branch_gst_percentage / 100));

                                        $vehicle_gst_tax_amt = ($total_trip_cost - $new_total_trip_cost);

                                    elseif ($vendor_branch_gst_type == 2) :
                                        // For Exclusive GST
                                        $new_total_trip_cost = $total_trip_cost;
                                        $vehicle_gst_tax_amt = ($total_trip_cost * $branch_gst_percentage / 100);
                                    endif;

                                    $overall_total_trip_cost += $new_total_trip_cost;
                                    $overall_total_vehicle_gst_tax_amt += $vehicle_gst_tax_amt;
                                    // $overall_total_extra_km_charge += $total_extra_km_charge;

                                    //DRIVER COST CALCULATION
                                    $driver_charges = ($driver_batta +  $driver_accomodation_cost + $driver_extra_cost + $driver_food_cost) * $vehicle_count;
                                    //CALCULATE GST FOR DRIVER CHARGES
                                    if ($vendor_branch_gst_type == 1) :
                                        // For Inclusive GST
                                        $new_driver_charges = $driver_charges / (1 + ($branch_gst_percentage / 100));

                                        $driver_gst_tax_amt = ($driver_charges - $new_driver_charges);

                                    elseif ($vendor_branch_gst_type == 2) :
                                        // For Exclusive GST
                                        $new_driver_charges = $driver_charges;
                                        $driver_gst_tax_amt = ($driver_charges * $branch_gst_percentage / 100);
                                    endif;

                                    $overall_total_driver_charge += $new_driver_charges;
                                    $overall_total_driver_gst_tax_amt += $driver_gst_tax_amt;

                                    // PERMIT COST CALCULATION
                                    //GET STATE DETAILS OF SOURCE AND DESTINATION
                                    if ($location_name == $next_visiting_location) :
                                        $filter_by = "  `source_location`='$location_name' ";
                                    else :
                                        $filter_by = "  `destination_location` ='$next_visiting_location' AND `source_location`='$location_name' ";
                                    endif;

                                    $get_location_details = sqlQUERY_LABEL("SELECT `source_location_state`,`destination_location_state` FROM `dvi_stored_locations` WHERE  {$filter_by} ") or die("#1-UNABLE_TO_COLLECT_DATA:" . sqlERROR_LABEL());
                                    if (sqlNUMOFROW_LABEL($get_location_details) > 0) :
                                        while ($fetch_location_data = sqlFETCHARRAY_LABEL($get_location_details)) :

                                            if ($location_name == $next_visiting_location) :
                                                $destination_location_state =
                                                    $source_location_state = $fetch_location_data['source_location_state'];
                                            else :
                                                $destination_location_state = $fetch_location_data['destination_location_state'];
                                                $source_location_state = $fetch_location_data['source_location_state'];
                                            endif;
                                        endwhile;
                                    endif;

                                    $source_state_id = getVEHICLE_PERMIT_DETAILS($source_location_state, 'GET_PERMIT_STATE_ID');

                                    $destination_state_id = getVEHICLE_PERMIT_DETAILS($destination_location_state, 'GET_PERMIT_STATE_ID');

                                    $permit_cost = 0;

                                    $permit_cost_collected_variable = "permit_cost_collected_" . $destination_state_id . "_" . $vehicle_id;
                                    $permit_cost_day_count_variable = $permit_cost_collected_variable . "_day_count";

                                    if (${$permit_cost_collected_variable} == 1) :
                                        ${$permit_cost_day_count_variable}++;
                                    endif;

                                    if ($vehicle_state_id == $destination_state_id && $source_state_id == $destination_state_id) :
                                        //SAME STATE 
                                        $permit_cost = 0;
                                    else :
                                        //DIFFERENT STATE
                                        if ((${$permit_cost_collected_variable} != 1) || ((${$permit_cost_collected_variable} == 1) && ${$permit_cost_day_count_variable} == 8)
                                        ) :
                                            $select_vehicle_permit_cost = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted`='0' AND `status`='1' AND `vendor_id`='$vendor_id' AND `vehicle_type_id`='$vehicle_type_id' AND `source_state_id`='$vehicle_state_id' AND `destination_state_id`='$destination_state_id' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                            while ($fetch_vehicle_permit_cost = sqlFETCHARRAY_LABEL($select_vehicle_permit_cost)) :
                                                $permit_cost = $fetch_vehicle_permit_cost['permit_cost'];
                                                ${$permit_cost_collected_variable} = 1;
                                                ${$permit_cost_day_count_variable} = 1;
                                            endwhile;
                                        endif;
                                    endif;
                                    $permit_cost =  $permit_cost * $vehicle_count;
                                    $overall_total_permit_cost += $permit_cost;

                                    //TOLL CHARGE CALCULATION
                                    $VEHICLE_TOLL_CHARGE = getVEHICLE_TOLL_CHARGES($vehicletypeid, $location_id) * $vehicle_count;
                                    $overall_total_vehicle_toll_charge += $VEHICLE_TOLL_CHARGE;

                                    //PARKING CHARGE CALCULATION
                                    $VEHICLE_PARKING_CHARGE =
                                        getHOTSPOT_VEHICLE_PARKING_CHARGES_DETAILS($itinerary_plan_ID, $itinerary_route_ID, 'total_hotspot_parking_charges') * $vehicle_count;
                                    $overall_total_vehicle_parking_charge += $VEHICLE_PARKING_CHARGE;

                                    $total_vendor_cost_per_day = $new_total_trip_cost  + $new_driver_charges +  $permit_cost + $VEHICLE_PARKING_CHARGE + $VEHICLE_TOLL_CHARGE;
                                    $total_tax_per_day = $vehicle_gst_tax_amt + $driver_gst_tax_amt;

                                    $total_vendor_cost_per_day_with_tax = $total_vendor_cost_per_day + $total_tax_per_day;

                                    $TOTAL_DISTANCE = $TOTAL_DISTANCE + $TOTAL_KM;

                                    //TOTAL TIME TAKEN
                                    // Convert time durations to seconds
                                    $TOTAL_TIME_TAKEN_IN_SECONDS = strtotime($TOTAL_TIME_TAKEN) - strtotime('00:00:00');

                                    $totalSeconds3 = $TOTAL_TIME_TAKEN_IN_SECONDS + ($TOTAL_RUNNING_TIME_IN_SECONDS + $SIGHT_SEEING_TIME_IN_SECONDS);

                                    $TOTAL_TIME_TAKEN = gmdate('H:i:s', $totalSeconds3);

                                    $arrFields_vehicle = array('`itinerary_plan_id`', '`itinerary_route_id`', '`itinerary_route_date`', '`itinerary_route_location_from`', '`itinerary_route_location_to`', '`vendor_id`', '`vendor_branch_id`', '`vehile_type_id`', '`vehicle_id`', '`vehicle_count`', '`running_kms`', '`sight_seeing_kms`', '`total_kms_travelled`', '`traveling_time`', '`sight_seeing_time`', '`total_time`', '`cost_type`',  '`local_time_limit_id`', '`outstation_km_limit_id`', '`extra_km_charge`',  '`driver_bhatta`', '`driver_food_cost`', '`driver_accomodation_cost`', '`extra_cost`', '`total_driver_cost`', '`total_driver_gst_amt`', '`toll_charge`', '`vehicle_parking_charge`',  '`vehicle_permit_cost`', '`vehicle_gst_type`', '`vehicle_gst_percentage`', 'vehicle_per_day_cost', '`vehicle_gst_amount`', '`total_vehicle_cost`', '`total_vehicle_cost_with_gst`',  '`createdby`', '`status`');

                                    $arrValues_vehicle = array("$itinerary_plan_ID", "$itinerary_route_ID", "$itinerary_route_date_DB_format", "$location_name", "$next_visiting_location", "$vendor_id", "$vendor_branch_id", "$vehicle_type_id", "$vehicle_id", "$vehicle_count", "$TOTAL_RUNNING_KM", "$SIGHT_SEEING_DISTANCE", "$TOTAL_KM", "$TOTAL_RUNNING_TIME", "$SIGHT_SEEING_TIME", "$TOTAL_TIME", "$trip_cost_type", "$time_limit_id", "$kms_limit_id", "$extra_km_charge", "$driver_batta", "$driver_food_cost", "$driver_accomodation_cost", "$driver_extra_cost", "$new_driver_charges", "$driver_gst_tax_amt", "$VEHICLE_TOLL_CHARGE", "$VEHICLE_PARKING_CHARGE", "$permit_cost", "$vendor_branch_gst_type", "$branch_gst_percentage", "$new_total_trip_cost", "$vehicle_gst_tax_amt", "$total_vendor_cost_per_day", "$total_vendor_cost_per_day_with_tax", "$logged_user_id", "1");


                                    if ($itinerary_plan_vendor_vehicle_details_ID != "" && $itinerary_plan_vendor_vehicle_details_ID != 0) :

                                        $sqlWhere_vehicle = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `itinerary_plan_vendor_vehicle_details_ID` = '$itinerary_plan_vendor_vehicle_details_ID' ";

                                        //UPDATE DETAILS
                                        if (sqlACTIONS(
                                            "UPDATE",
                                            "dvi_itinerary_plan_vendor_vehicle_details",
                                            $arrFields_vehicle,
                                            $arrValues_vehicle,
                                            $sqlWhere_vehicle
                                        )) :
                                        endif;

                                    else :

                                        //INSERT ROUTE VENDOR VEHICLE DETAILS
                                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_vehicle_details", $arrFields_vehicle, $arrValues_vehicle, '')) :

                                        endif;
                                    endif;
                                endwhile;
                            endif;

                        endwhile;

                    endif;

                endwhile;

                //CALCULATE VEHICLE SUMMARY
                $select_vehicle_summary = sqlQUERY_LABEL("SELECT  `itinerary_plan_id`,  `itinerary_plan_vendor_summary_id`, `vendor_branch_id`,`vehicle_id`,`vendor_id`,`vehile_type_id`,`vehicle_count`,SUM(`total_kms_travelled`) AS total_kms_travelled , SEC_TO_TIME(SUM(TIME_TO_SEC(`total_time`))) AS total_time, SUM(`total_driver_cost`) AS total_driver_cost,  SUM(`total_driver_gst_amt`) AS total_driver_gst_amt,   SUM(`toll_charge`) AS toll_charge,   SUM(`vehicle_parking_charge`) AS vehicle_parking_charge,  SUM(`vehicle_permit_cost`) AS vehicle_permit_cost,  SUM(`vehicle_gst_amount`) AS vehicle_gst_amount, SUM(`vehicle_per_day_cost`) AS vehicle_per_day_cost,  SUM(`total_vehicle_cost`) AS total_vehicle_cost, SUM(`total_vehicle_cost_with_gst`) AS total_vehicle_cost_with_gst  FROM `dvi_itinerary_plan_vendor_vehicle_details`  WHERE `itinerary_plan_ID` = '$itinerary_plan_ID' AND `status` = '1'  AND `deleted` = '0' GROUP BY `vehile_type_id`") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                $vehicle_summary_cout = sqlNUMOFROW_LABEL($select_vehicle_summary);

                while ($fetch_summary_data = sqlFETCHARRAY_LABEL($select_vehicle_summary)) :

                    $itinerary_plan_vendor_summary_id = $fetch_summary_data['itinerary_plan_vendor_summary_id'];
                    $itinerary_route_id = $fetch_summary_data['itinerary_route_id'];
                    $vendor_branch_id = $fetch_summary_data['vendor_branch_id'];
                    $vendor_id = $fetch_summary_data['vendor_id'];
                    $vehicle_id = $fetch_summary_data['vehicle_id'];
                    $vehicle_type_id = $fetch_summary_data['vehile_type_id'];
                    $vehicle_type = getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'label');
                    $vehicle_count = $fetch_summary_data['vehicle_count'];
                    $total_kms_travelled = $fetch_summary_data['total_kms_travelled'];
                    $total_time = $fetch_summary_data['total_time'];

                    $vehicle_permit_cost = $fetch_summary_data['vehicle_permit_cost'];
                    $toll_charge = $fetch_summary_data['toll_charge'];
                    $vehicle_parking_charge = $fetch_summary_data['vehicle_parking_charge'];
                    $total_driver_cost = $fetch_summary_data['total_driver_cost'];
                    $total_driver_gst_amt =  $fetch_summary_data['total_driver_gst_amt'];
                    $vehicle_per_day_cost = $fetch_summary_data['vehicle_per_day_cost'];

                    $total_vehicle_cost = $fetch_summary_data['total_vehicle_cost'];
                    $vehicle_gst_amount = $fetch_summary_data['vehicle_gst_amount'];

                    //EXTRA KM CHARGE
                    $extra_km_charges = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_extra_km_charge');
                    if ($TOTAL_ALLOWED_KM < $total_kms_travelled) :
                        $extra_km = $TOTAL_ALLOWED_KM - $total_kms_travelled;
                        $total_extra_km_charge =  ($extra_km * $extra_km_charges) * $vehicle_count;
                    else :
                        $extra_km = 0;
                        $total_extra_km_charge = 0;
                    endif;

                    $total_vehicle_cost_with_gst = $fetch_summary_data['total_vehicle_cost_with_gst'] + $total_extra_km_charge;

                    $margin_percentage = getVENDORNAMEDETAIL($vendor_id, 'get_vendor_margin_percentage');
                    $VENDOR_MARGIN = $total_vehicle_cost_with_gst * ($margin_percentage / 100);

                    $grand_total_vehicle_cost = $total_vehicle_cost_with_gst + $VENDOR_MARGIN;

                    $arrFields_vehicle_summary = array('`itinerary_plan_id`', '`vendor_id`', '`vehicle_type_id`', '`vehicle_id`', '`vendor_branch_id`', '`vehicle_count`', '`total_kms`', '`total_time`', '`total_vehicle_permit_cost`', '`total_toll_charge`', '`total_vehicle_parking_charge`', '`total_driver_cost`', '`total_driver_gst_amt`', '`total_vehicle_per_day_cost`', '`total_vehicle_cost`', '`extra_km`', '`extra_km_charge`', '`total_extra_km_charge`', '`total_vehicle_gst_amount`',  '`total_vehicle_cost_with_gst`', '`vendor_margin_percentage`', '`vendor_margin`', '`grand_total`',  '`createdby`', '`status`');


                    $arrValues_vehicle_summary = array("$itinerary_plan_ID",  "$vendor_id", "$vehicle_type_id", "$vehicle_id", "$vendor_branch_id",   "$vehicle_count", "$total_kms_travelled", "$total_time", "$vehicle_permit_cost", "$toll_charge", "$vehicle_parking_charge", "$total_driver_cost", "$total_driver_gst_amt", "$vehicle_per_day_cost", "$total_vehicle_cost", "$extra_km", "$extra_km_charges", "$total_extra_km_charge", "$vehicle_gst_amount", "$total_vehicle_cost_with_gst", "$margin_percentage", "$vendor_margin", "$grand_total_vehicle_cost", "$logged_user_id", "1");

                    if ($itinerary_plan_vendor_summary_id == 0) :

                        //INSERT VENDOR SUMMARY DETAILS
                        if (sqlACTIONS("INSERT", "dvi_itinerary_plan_vendor_summary", $arrFields_vehicle_summary, $arrValues_vehicle_summary, '')) :
                            $itinerary_plan_vendor_summary_id = sqlINSERTID_LABEL();

                            $arrFields_vendor_details = array('`itinerary_plan_vendor_summary_id`');
                            $arrValues_vendor_details = array("$itinerary_plan_vendor_summary_id");
                            $sqlWhere_vendor_details = " `itinerary_plan_id` = '$itinerary_plan_ID' AND `vehile_type_id` = '$vehicle_type_id' ";
                            //UPDATE SUMMARY ID IN VEHICLE DETAILS
                            if (sqlACTIONS(
                                "UPDATE",
                                "dvi_itinerary_plan_vendor_vehicle_details",
                                $arrFields_vendor_details,
                                $arrValues_vendor_details,
                                $sqlWhere_vendor_details
                            )) :
                            endif;

                        endif;

                    else :
                        $sqlWhere_vehicle_summary = " `itinerary_plan_id` = '$itinerary_plan_ID' AND  `itinerary_plan_vendor_summary_id` = ' $itinerary_plan_vendor_summary_id' ";
                        if (sqlACTIONS(
                            "UPDATE",
                            "dvi_itinerary_plan_vendor_summary",
                            $arrFields_vehicle_summary,
                            $arrValues_vehicle_summary,
                            $sqlWhere_vehicle_summary
                        )) :
                        endif;
                    endif;

                endwhile;
                $response['i_result'] = true;
                $response['result_success'] = true;
            endif;

        endif;
        echo json_encode($response);

    endif;
else :
    echo "Request Ignored";
endif;
