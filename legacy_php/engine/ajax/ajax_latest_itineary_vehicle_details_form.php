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

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $itinerary_plan_vendor_vehicle_details_ID = $_GET['itinerary_plan_vendor_vehicle_details_ID'];
        $itinerary_plan_vendor_eligible_ID = $_GET['itinerary_plan_vendor_eligible_ID'];
        $itinerary_plan_ID = $_GET['itinerary_plan_ID'];

        $select_itinerary_plan_vendor_vehicle_summary_data = sqlQUERY_LABEL("SELECT `itinerary_route_id`, `itinerary_route_date`, `vehicle_type_id`, `vendor_id`, `vendor_vehicle_type_id`, `vehicle_id`, `vendor_branch_id`, `travel_type`, `itinerary_route_location_from`, `itinerary_route_location_to`, `total_running_km`, `total_running_time`, `total_siteseeing_km`, `total_siteseeing_time`, `total_travelled_km`, `total_travelled_time`, `vehicle_rental_charges`, `vehicle_toll_charges`, `vehicle_parking_charges`, `vehicle_driver_charges`, `vehicle_permit_charges`, `before_6_am_extra_time`, `after_8_pm_extra_time`, `before_6_am_charges_for_driver`, `before_6_am_charges_for_vehicle`, `after_8_pm_charges_for_driver`, `after_8_pm_charges_for_vehicle`, `total_vehicle_amount` FROM `dvi_itinerary_plan_vendor_vehicle_details` WHERE `deleted` = '0' AND `status` = '1' AND `itinerary_plan_vendor_vehicle_details_ID` = '$itinerary_plan_vendor_vehicle_details_ID' AND `itinerary_plan_vendor_eligible_ID` = '$itinerary_plan_vendor_eligible_ID' AND `itinerary_plan_id` = '$itinerary_plan_ID'") or die("#3-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
        $select_itinerary_plan_vendor_vehicle_summary_count = sqlNUMOFROW_LABEL($select_itinerary_plan_vendor_vehicle_summary_data);
        while ($fetch_eligible_vendor_vehicle_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_vendor_vehicle_summary_data)) :
            $itinerary_route_id = $fetch_eligible_vendor_vehicle_data['itinerary_route_id'];
            $itinerary_route_date = $fetch_eligible_vendor_vehicle_data['itinerary_route_date'];
            $vehicle_type_id = $fetch_eligible_vendor_vehicle_data['vehicle_type_id'];
            $vendor_id = $fetch_eligible_vendor_vehicle_data['vendor_id'];
            $vendor_vehicle_type_id = $fetch_eligible_vendor_vehicle_data['vendor_vehicle_type_id'];
            $vehicle_id = $fetch_eligible_vendor_vehicle_data['vehicle_id'];
            $vendor_branch_id = $fetch_eligible_vendor_vehicle_data['vendor_branch_id'];
            $travel_type = $fetch_eligible_vendor_vehicle_data['travel_type'];
            $itinerary_route_location_from = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_from'];
            $itinerary_route_location_to = $fetch_eligible_vendor_vehicle_data['itinerary_route_location_to'];
            $total_running_km = $fetch_eligible_vendor_vehicle_data['total_running_km'];
            $total_running_time = $fetch_eligible_vendor_vehicle_data['total_running_time'];
            $total_siteseeing_km = $fetch_eligible_vendor_vehicle_data['total_siteseeing_km'];
            $total_siteseeing_time = $fetch_eligible_vendor_vehicle_data['total_siteseeing_time'];
            $total_travelled_km = $fetch_eligible_vendor_vehicle_data['total_travelled_km'];
            $total_travelled_time = $fetch_eligible_vendor_vehicle_data['total_travelled_time'];
            $vehicle_rental_charges = $fetch_eligible_vendor_vehicle_data['vehicle_rental_charges'];
            $vehicle_toll_charges = $fetch_eligible_vendor_vehicle_data['vehicle_toll_charges'];
            $vehicle_parking_charges = $fetch_eligible_vendor_vehicle_data['vehicle_parking_charges'];
            $vehicle_driver_charges = $fetch_eligible_vendor_vehicle_data['vehicle_driver_charges'];
            $vehicle_permit_charges = $fetch_eligible_vendor_vehicle_data['vehicle_permit_charges'];
            $before_6_am_extra_time = $fetch_eligible_vendor_vehicle_data['before_6_am_extra_time'];
            $after_8_pm_extra_time = $fetch_eligible_vendor_vehicle_data['after_8_pm_extra_time'];
            $before_6_am_charges_for_driver = $fetch_eligible_vendor_vehicle_data['before_6_am_charges_for_driver'];
            $before_6_am_charges_for_vehicle = $fetch_eligible_vendor_vehicle_data['before_6_am_charges_for_vehicle'];
            $after_8_pm_charges_for_driver = $fetch_eligible_vendor_vehicle_data['after_8_pm_charges_for_driver'];
            $after_8_pm_charges_for_vehicle = $fetch_eligible_vendor_vehicle_data['after_8_pm_charges_for_vehicle'];
            $total_vehicle_amount = $fetch_eligible_vendor_vehicle_data['total_vehicle_amount'];
            if ($travel_type == 1) :
                $travel_type_label = 'Local';
            elseif ($travel_type == 2) :
                $travel_type_label = 'Outstation';
            else :
                $travel_type_label = '--';
            endif;
        endwhile;
        $get_vehicle_video_url = getVENDORANDVEHICLEDETAILS($vehicle_id, 'get_vehicle_video_url');
?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="row">
            <div class="col-lg-3 position-relative">
                <div id="carouselExampleDarkTwo" class="carousel carousel-light slide carousel-fade h-100" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php
                        $select_vehicle_gallery_list_query = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' and `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                        $total_vehicle_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_vehicle_gallery_list_query);
                        for ($image_count = 1; $image_count <= $total_hotel_gallery_num_rows_count; $image_count++) : ?>
                            <button type="button" data-bs-target="#carouselExampleDarkTwo" data-slide-to="<?= $image_count; ?>" class="active" aria-label="Slide <?= $image_count; ?>" aria-current="true"></button>
                        <?php endfor; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php
                        if ($total_vehicle_gallery_num_rows_count > 0) :
                            while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_list_query)) :
                                $vehicle_gallery_details_id = $fetch_vehicle_gallery_data['vehicle_gallery_details_id'];
                                $vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];
                                $image_already_exist = $DIRECTORY_DOCUMENT_ROOT . 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                                $image_path = BASEPATH . '/uploads/vehicle_gallery/' . $vehicle_gallery_name;
                                $default_image = BASEPATH . 'uploads/no-photo.png';

                                // Check if the image file exists
                                $image_src = file_exists($image_already_exist) ? $image_path : $default_image;
                        ?>
                                <div class="carousel-item <?= $active_class; ?> rounded">
                                    <img class="d-block w-100 rounded" style="width: 100%; height: 180px;" src="<?= $image_src; ?>" alt="<?= $vehicle_gallery_name; ?>">
                                </div>
                            <?php endwhile;
                        else :
                            $vehicle_photo_url = BASEPATH . 'uploads/no-photo.png';
                            ?>
                            <div class="carousel-item active rounded">
                                <img class="d-block w-100 rounded" style="width: 100%; height: 180px;" src="<?= $vehicle_photo_url; ?>" alt="<?= $vehicle_gallery_name; ?>">
                            </div>
                        <?php
                        endif; ?>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleDarkTwo" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleDarkTwo" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="">
                    <div class="row">
                        <div class="d-flex align-items-center justify-content-between my-2">
                            <?php if ($get_vehicle_video_url) : ?>
                                <h3 class="my-0"><b><a target="blank" href="<?= $get_vehicle_video_url; ?>" style="color: #5d596c;"><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); ?> <i class="ti ti-brand-youtube ti-burst " style="color: white;background: #ff0000;border: none;padding: 3px;border-radius: 50%;font-size: 13px;"></i></a></b></h3>
                            <?php else : ?>
                                <h3 class="my-0"><b><?= getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title'); ?></b></h3>
                            <?php endif; ?>
                            <div>
                                <h5 class="mb-0"><span class="mb-0 text-primary fw-bolder"><?= general_currency_symbol . '' . number_format($total_vehicle_amount, 2); ?></span></h5>
                            </div>
                        </div>
                    </div>
                    <h5 class="my-2">Occupancy : <?= getVEHICLETYPE($vehicle_type_id, 'get_occupancy'); ?></h5>
                    <h5 class="my-2"><?= $itinerary_route_location_from; ?><span><i class="ti ti-arrow-big-right-lines-filled mx-2"></i></span><?= $itinerary_route_location_to; ?></h5>
                    <div class="col-12 d-flex">
                        <div class="col-4">
                            <p class="m-0 text-muted" style="font-size: 13px;">Travel Distance & Time</p>
                            <h6 class="m-0"><i class="ti ti-road text-primary me-1"></i><?= number_format($total_running_km, 2); ?> KM <i class="ti ti-clock text-primary me-1"></i><?= formatTimeDuration($total_running_time); ?></h6>
                        </div>
                        <div class="col-4">
                            <p class="m-0 text-muted" style="font-size: 13px;">Sight-seeing Distance & Time</p>
                            <h6 class="m-0"><i class="ti ti-road text-primary me-1"></i><?= number_format($total_siteseeing_km, 2); ?> KM <i class="ti ti-clock text-primary me-1"></i><?= formatTimeDuration($total_siteseeing_time); ?></h5>
                        </div>
                        <div class="col-4">
                            <p class="m-0 text-muted" style="font-size: 13px;">Total Distance & Time</p>
                            <h6 class="m-0"><i class="ti ti-road text-primary me-1"></i><?= number_format($total_travelled_km, 2); ?> KM <i class="ti ti-clock text-primary me-1"></i><?= formatTimeDuration($total_travelled_time); ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row rounded-2 mt-3" style="background-color: #f7f7f7;">
            <div class="col-lg-12 d-flex position-relative p-4">
                <div class="col-lg-12 d-flex flex-wrap">
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Rental Charges (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($vehicle_rental_charges, 2); ?></h6>
                    </div>
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Toll Charges (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($vehicle_toll_charges, 2); ?></h6>
                    </div>
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Parking Charges (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($vehicle_parking_charges, 2); ?></h6>
                    </div>
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Driver Charges (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($vehicle_driver_charges, 2); ?></h6>
                    </div>
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Permit Charges (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($vehicle_permit_charges, 2); ?></h6>
                    </div>
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Before 6AM After 8PM Charges for Driver (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($before_6_am_charges_for_driver + $after_8_pm_charges_for_driver, 2); ?></h6>
                    </div>
                    <div class="col-lg-4 my-2">
                        <p class="m-0 text-muted" style="font-size: 13px;">Before 6AM After 8PM Charges for Vendor (<?= general_currency_symbol; ?>)</p>
                        <h6 class="m-0"><?= general_currency_symbol . ' ' . number_format($before_6_am_charges_for_vehicle + $after_8_pm_charges_for_vehicle, 2); ?></h6>
                    </div>
                </div>
            </div>
        </div>
<?php endif;
else :
    echo "Request Ignored";
endif;
