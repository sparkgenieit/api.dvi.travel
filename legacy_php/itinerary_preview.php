<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 5.0.1
* Copyright (c) 2010-2022 Touchmark De`Science
*
*/
include_once('jackus.php');
$itinerary_plan_ID = 10;

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>driver</title>

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/rateyo/rateyo.css">

    <!-- Row Group CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <style>
        .timeline-steps {
            display: flex;
            justify-content: center;
            flex-wrap: wrap
        }

        .timeline-steps .timeline-step {
            align-items: center;
            display: flex;
            flex-direction: column;
            position: relative;
            margin: 1rem
        }

        @media (min-width:768px) {
            .timeline-steps .timeline-step:not(:last-child):after {
                content: "";
                display: block;
                border-top: .25rem dotted #dbdade;
                width: 3.46rem;
                position: absolute;
                left: 7.5rem;
                top: 1.3125rem
            }

            .timeline-steps .timeline-step:not(:first-child):before {
                content: "";
                display: block;
                border-top: .25rem dotted #dbdade;
                width: 3.8125rem;
                position: absolute;
                right: 7.5rem;
                top: 1.3125rem
            }
        }

        .timeline-steps .timeline-content {
            width: 10rem;
            text-align: center
        }

        /* .timeline-steps .timeline-content .inner-circle {
            border-radius: 1.5rem;
            height: 1rem;
            width: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #3b82f6
        }

        .timeline-steps .timeline-content .inner-circle:before {
            content: "";
            background-color: #3b82f6;
            display: inline-block;
            height: 3rem;
            width: 3rem;
            min-width: 3rem;
            border-radius: 6.25rem;
            opacity: .5
        } */
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- Menu -->
            <?php include_once('public/__sidebar.php'); ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include_once('public/__topbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class=" d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="font-weight-bold">Itinerary Preview - <span class="text-primary fs-5">DVIADMIN00A</span></h4>
                            </div>
                        </div>

                        <!-- No Hotspot Found -->
                        <!-- <div class="card p-5">
                            <div class="text-center" role="alert">
                                <img src="assets/img/illustrations/no-results.png" width="90" height="90">
                                <h3>No Hotspots Found</h3>
                                <h6>Unfortunately, there are currently no hotspots available in this area. Please try a different search or check back later.</h6>
                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="nav-align-top mb-4">
                                    <ul class="nav nav-pills mb-3" role="tablist">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-itinerary-1" aria-controls="navs-pills-top-home" aria-selected="true">Itinerary-1</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-itinerary-2" aria-controls="navs-pills-top-profile" aria-selected="false">Itinerary-2</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-itinerary-3" aria-controls="navs-pills-top-messages" aria-selected="false">Itinerary-3</button>
                                        </li>
                                    </ul>
                                    <!-- Itinerary Plan details Start -->
                                    <?php
                                    $select_itinerary_plan_detail = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `location_id`, `arrival_location`, `departure_location`, `generated_quote_code`, `trip_start_date_and_time`, `trip_end_date_and_time`, `arrival_type`, `departure_type`, `expecting_budget`, `itinerary_type`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `nationality`, `itinerary_preference`, `meal_plan_breakfast`, `meal_plan_lunch`, `meal_plan_dinner`, `preferred_room_count`, `total_extra_bed`, `total_child_no_bed`, `vehicle_type`, `guide_for_itinerary`, `food_type`, `special_instructions`, `pick_up_date_and_time`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_PLAN_DETAILS:" . sqlERROR_LABEL());
                                    while ($fetch_itinerary_plan_data = sqlFETCHARRAY_LABEL($select_itinerary_plan_detail)) :
                                        $arrival_location = $fetch_itinerary_plan_data['arrival_location'];
                                        $departure_location = $fetch_itinerary_plan_data['departure_location'];
                                        $no_of_days = $fetch_itinerary_plan_data['no_of_days'];
                                        $no_of_nights = $fetch_itinerary_plan_data['no_of_nights'];
                                        $no_of_routes = $fetch_itinerary_plan_data['no_of_routes'];
                                        $total_adult = $fetch_itinerary_plan_data['total_adult'];
                                        $expecting_budget = $fetch_itinerary_plan_data['expecting_budget'];
                                        $total_children = $fetch_itinerary_plan_data['total_children'];
                                        $total_infants = $fetch_itinerary_plan_data['total_infants'];
                                        $trip_start_date_and_time = $fetch_itinerary_plan_data['trip_start_date_and_time'];
                                        $trip_end_date_and_time = $fetch_itinerary_plan_data['trip_end_date_and_time'];
                                    endwhile;
                                    ?>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-pills-top-itinerary-1" role="tabpanel">
                                            <div class="row">
                                                <h5 class="text-primary">Basic Info</h5>
                                                <div class="col-md-3">
                                                    <label>Arrival</label>
                                                    <p class="text-light"><?= $arrival_location ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Destination</label>
                                                    <p class="text-light"><?= $departure_location ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Days</label>
                                                    <p class="text-light"><?= $no_of_days ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Night</label>
                                                    <p class="text-light"><?= $no_of_nights ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Route</label>
                                                    <p class="text-light"><?= $no_of_routes ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>OverallCost</label>
                                                    <p class="text-light"><?= $expecting_budget ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Start Date</label>
                                                    <p class="text-light"><?= $trip_start_date_and_time ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>End Date</label>
                                                    <p class="text-light"><?= $trip_end_date_and_time ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Adult</label>
                                                    <p class="text-light"><?= $total_adult ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Children</label>
                                                    <p class="text-light"><?= $total_children ?></p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Infants</label>
                                                    <p class="text-light"><?= $total_infants ?></p>
                                                </div>
                                            </div>
                                            <!-- Itinerary Plan details End -->
                                            <hr>
                                            <!-- Itinerary Route details Start -->
                                            <div class="row my-2">
                                                <h5 class="text-primary">Route</h5>
                                                <div class="col">
                                                    <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                                        <?php
                                                        $select_itinerary_route_detail = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `location_via_route`, `route_start_time`, `route_end_time`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_itinerary_route_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID`= $itinerary_plan_ID ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ROUTE_DETAILS:" . sqlERROR_LABEL());
                                                        while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_detail)) :
                                                            $day_counter++;
                                                            $itinerary_plan_ID = $fetch_itinerary_route_data['itinerary_plan_ID'];
                                                            $location_name = $fetch_itinerary_route_data['location_name'];
                                                            $no_of_days = $fetch_itinerary_route_data['no_of_days'];
                                                        ?>
                                                            <div class="timeline-step ms-0">
                                                                <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                                    <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                    <p class="h6 mt-3 mb-1"><?= $location_name ?></p>
                                                                    <p class="h6 mt-3 mb-1">(Day - <?= $day_counter ?>)</p>
                                                                </div>
                                                            </div>
                                                        <?php endwhile; ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Itinerary Route details End -->
                                            <hr>
                                            <!-- Itinerary Hotspot details Start -->

                                            <?php
                                            $select_itinerary_route_detail = sqlQUERY_LABEL("SELECT `itinerary_route_ID`, `itinerary_plan_ID`, `location_id`, `location_name`, `itinerary_route_date`, `no_of_days`, `no_of_km`, `direct_to_next_visiting_place`, `next_visiting_location`, `location_via_route`, `route_start_time`, `route_end_time`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_itinerary_route_details` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID`= $itinerary_plan_ID ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ROUTE_DETAILS:" . sqlERROR_LABEL());
                                            $route_detail_total_num_rows = mysqli_num_rows($select_itinerary_route_detail);
                                            if ($route_detail_total_num_rows > 0) :
                                            ?>
                                                <div class="row">
                                                    <h5 class="text-primary">Hotspot Places</h5>
                                                    <?php
                                                    while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_detail)) :
                                                        $route_counter++;
                                                        $itinerary_route_ID = $fetch_itinerary_route_data['itinerary_route_ID'];
                                                        $location_name = $fetch_itinerary_route_data['location_name'];
                                                        $itinerary_route_date = $fetch_itinerary_route_data['itinerary_route_date'];
                                                    ?>
                                                        <div class="divider">
                                                            <div class="divider-text">
                                                                <i class="ti ti-star"></i>
                                                            </div>
                                                        </div>
                                                        <h6><strong>DAY <?= $route_counter ?> |<span> <?= $location_name ?> |</span><span> <?= $itinerary_route_date ?> </span></strong></h6>

                                                        <?php

                                                        $select_itinerary_hotspot_detail = sqlQUERY_LABEL("SELECT HOTSPOT_DETAILS.`route_hotspot_ID`, HOTSPOT_DETAILS.hotspot_ID,HOTSPOT_DETAILS.`itinerary_plan_ID`,HOTSPOT_DETAILS.`itinerary_route_ID`,HOTSPOT_DETAILS.`hotspot_start_time`,HOTSPOT_DETAILS.`hotspot_amout`, HOTSPOT_PLACE.`hotspot_ID`, HOTSPOT_PLACE.`hotspot_place_id`, HOTSPOT_PLACE.`hotspot_name`, HOTSPOT_PLACE.`hotspot_description`,HOTSPOT_PLACE.`hotspot_location`,HOTSPOT_PLACE.`hotspot_duration` FROM `dvi_itinerary_route_hotspot_details` HOTSPOT_DETAILS LEFT JOIN `dvi_hotspot_place` HOTSPOT_PLACE ON HOTSPOT_DETAILS.`hotspot_ID` =  HOTSPOT_PLACE.`hotspot_ID` WHERE HOTSPOT_DETAILS.`deleted` = '0' AND  HOTSPOT_PLACE.`deleted` = '0' and HOTSPOT_DETAILS.`itinerary_plan_ID`= '$itinerary_plan_ID' AND HOTSPOT_DETAILS.`itinerary_route_ID` = '$itinerary_route_ID' AND HOTSPOT_DETAILS.`item_type` = '3'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_HOTSPOT_DETAILS:" . sqlERROR_LABEL());
                                                        $total_hotspot_available_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_hotspot_detail);
                                                        if ($total_hotspot_available_num_rows_count > 0) :
                                                            while ($fetch_itinerary_hotspot_data = sqlFETCHARRAY_LABEL($select_itinerary_hotspot_detail)) :
                                                                $hotspot_counter++;
                                                                $hotspot_ID = $fetch_itinerary_hotspot_data['hotspot_ID'];
                                                                $hotspot_start_time = $fetch_itinerary_hotspot_data['hotspot_start_time'];
                                                                $hotspot_amout = $fetch_itinerary_hotspot_data['hotspot_amout'];
                                                                $hotspot_name = $fetch_itinerary_hotspot_data['hotspot_name'];
                                                                $hotspot_description = $fetch_itinerary_hotspot_data['hotspot_description'];
                                                                $hotspot_location = $fetch_itinerary_hotspot_data['hotspot_location'];
                                                                $hotspot_duration = $fetch_itinerary_hotspot_data['hotspot_duration'];

                                                        ?>
                                                                <h6 class="text-primary mt-3">Hotspot #<?= $hotspot_counter ?> | <span> <?= $hotspot_name ?> </span></h6>
                                                                <div class="col-md-3">
                                                                    <label>Place</label>
                                                                    <p class="text-light"><?= $hotspot_location ?></p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Fare</label>
                                                                    <p class="text-light"><?= general_currency_symbol . ' ' . number_format($hotspot_amout, 2); ?></p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Visiting Time</label>
                                                                    <p class="text-light"><?= $hotspot_start_time ?></p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label>Duration</label>
                                                                    <p class="text-light"><?= $hotspot_duration ?></p>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <label>Description</label>
                                                                    <p class="text-light"><?= $hotspot_description ?></p>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <h6 class="text-primary mt-3">List of Activity</h6>
                                                                    <div class="table-responsive">
                                                                        <table id="transport" class="table table-flush-spacing border table-bordered">
                                                                            <thead class="table-head">
                                                                                <tr>
                                                                                    <th>S.No</th>
                                                                                    <th>Image</th>
                                                                                    <th>Activity Title</th>
                                                                                    <th>Activity Description</th>
                                                                                    <th>Activity Duration</th>
                                                                                    <th>Visting Time</th>
                                                                                    <th>Amount</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php

                                                                                $select_itinerary_activity_detail = sqlQUERY_LABEL("SELECT dvi_activity.`activity_id`, dvi_activity.`hotspot_id`,dvi_activity.`activity_title`,dvi_activity.`activity_description`,dvi_activity.`activity_duration`,dvi_activity_image_gallery_details.`activity_image_gallery_details_id`,dvi_activity_image_gallery_details.`activity_id`,dvi_activity_image_gallery_details.`activity_image_gallery_name`,dvi_activity_time_slot_details.`activity_id`,dvi_activity_time_slot_details.`start_time`,dvi_activity_time_slot_details.`end_time`,dvi_activity_time_slot_details.`time_slot_type` FROM dvi_activity LEFT JOIN dvi_activity_image_gallery_details ON dvi_activity.`activity_id` =  dvi_activity_image_gallery_details.`activity_id` LEFT JOIN dvi_activity_time_slot_details  ON dvi_activity.`activity_id` =  dvi_activity_time_slot_details.`activity_id` WHERE dvi_activity.`hotspot_id` = '$hotspot_ID' AND dvi_activity.`deleted` = '0' AND  dvi_activity_image_gallery_details.`deleted` = '0' AND dvi_activity_time_slot_details.deleted = '0'") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ACTIVITY_DETAILS:" . sqlERROR_LABEL());
                                                                                $total_activity_available_num_rows_count = sqlNUMOFROW_LABEL($select_itinerary_activity_detail);
                                                                                if ($total_activity_available_num_rows_count > 0) :
                                                                                    while ($fetch_itinerary_activity_data = sqlFETCHARRAY_LABEL($select_itinerary_activity_detail)) :
                                                                                        $activity_counter++;
                                                                                        $activity_title = $fetch_itinerary_activity_data['activity_title'];
                                                                                        $activity_description = $fetch_itinerary_activity_data['activity_description'];
                                                                                        $activity_duration = $fetch_itinerary_activity_data['activity_duration'];
                                                                                        $activity_image_gallery_name = $fetch_itinerary_activity_data['activity_image_gallery_name'];
                                                                                        $start_time = $fetch_itinerary_activity_data['start_time'];
                                                                                        $end_time = $fetch_itinerary_activity_data['end_time'];

                                                                                ?>
                                                                                        <tr>
                                                                                            <td><?= $activity_counter++; ?></td>
                                                                                            <td><img src="<?= BASEPATH; ?>uploads/activity_gallery/<?= $activity_image_gallery_name; ?>" alt="image" style="width:75px; height:75px; border-radius:5px;"></td>
                                                                                            <td><?= $activity_title; ?></td>
                                                                                            <td><?= $activity_description; ?></td>
                                                                                            <td><?= $activity_duration; ?></td>
                                                                                            <td>9AM</td>
                                                                                            <td>200</td>
                                                                                        </tr>
                                                                                <?php endwhile;
                                                                                else:
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td class="text-center" colspan="7">No more Activites Added</td>
                                                                                    </tr>
                                                                                    <?php
                                                                                endif; ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            <?php endwhile;
                                                        else :
                                                            ?>
                                                            <div class="col-md-12 pt-3 pb-3">
                                                                <h6 class="text-center">No more hotspots added !!!</h6>
                                                            </div>

                                                    <?php
                                                        endif;
                                                    endwhile;
                                                    ?>


                                                </div>
                                            <?php endif; ?>
                                            <!-- Itinerary Hotspot details Start -->
                                            <div class="divider">
                                                <div class="divider-text">
                                                    <i class="ti ti-star"></i>
                                                </div>
                                            </div>
                                            <div class="row">
                                            <?php
                                                        $select_itinerary_route_detail = sqlQUERY_LABEL("SELECT dvi_vehicle_type.`vehicle_type_id`,dvi_vehicle_type.`vehicle_type_title`,dvi_vehicle_type.`occupancy`, dvi_itinerary_plan_vendor_vehicle_details.`total_kms_travelled`,  dvi_itinerary_plan_vendor_vehicle_details.`total_time`, dvi_itinerary_plan_vendor_vehicle_details FROM `dvi_vehicle_type` WHERE `status` = '1' and `deleted` = '0' and `itinerary_plan_ID`= $itinerary_plan_ID ") or die("#1-UNABLE_TO_COLLECT_ITINERARY_ROUTE_DETAILS:" . sqlERROR_LABEL());
                                                        while ($fetch_itinerary_route_data = sqlFETCHARRAY_LABEL($select_itinerary_route_detail)) :
                                                            $day_counter++;
                                                            $itinerary_plan_ID = $fetch_itinerary_route_data['itinerary_plan_ID'];
                                                            $location_name = $fetch_itinerary_route_data['location_name'];
                                                            $no_of_days = $fetch_itinerary_route_data['no_of_days'];
                                                        ?>
                                                <h5 class="text-primary">Vehicle Lists</h5>
                                                <div class="d-flex justify-content-between">
                                                    <h6>Innova</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mx-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="0 0 20 32" class="mb-1">
                                                                <g id="location_pin_red" transform="translate(-322 -1036)">
                                                                    <g id="Ellipse_20" data-name="Ellipse 20" transform="translate(338 1056) rotate(90)" fill="#fff" stroke="#0058ff" stroke-width="1">
                                                                        <circle cx="6" cy="6" r="6" stroke="none" />
                                                                        <circle cx="6" cy="6" r="5.5" fill="none" />
                                                                    </g>
                                                                    <g id="location_pin_red-2" data-name="location_pin_red" transform="translate(264.849 1036)">
                                                                        <path id="Path_507" data-name="Path 507" d="M77.151,10.1c0,8.086-9.04,15.574-9.04,15.574a1.569,1.569,0,0,1-1.921,0s-9.04-7.488-9.04-15.574a10,10,0,1,1,20,0Z" fill="#ee3840" />
                                                                        <path id="Path_508" data-name="Path 508" d="M160.582,108.943a5.09,5.09,0,1,1,4.623-5.068A4.866,4.866,0,0,1,160.582,108.943Z" transform="translate(-93.431 -93.781)" fill="#ffe1d6" />
                                                                    </g>
                                                                </g>
                                                            </svg> - 25KM
                                                        </div>
                                                        <div class="mx-2">
                                                            <svg id="Layer_1_copy_2" height="22" viewBox="0 0 48 48" width="22" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1 copy 2" class="mb-1">
                                                                <g fill="#ffb400">
                                                                    <path d="m29.583 17.405h-1.078a7.093 7.093 0 0 0 -.914-1.776h1.992a1.5 1.5 0 0 0 0-3h-7.625c-.055 0-.108-.008-.164-.008h-3.967a1.5 1.5 0 0 0 0 3h3.967a4.1 4.1 0 0 1 3.383 1.784h-7.288a1.5 1.5 0 1 0 0 3h7.945a4.105 4.105 0 0 1 -4.04 3.432h-3.967a1.5 1.5 0 0 0 -1.088 2.532l8.11 8.55a1.5 1.5 0 1 0 2.176-2.064l-5.708-6.018h.477a7.117 7.117 0 0 0 7.075-6.432h.714a1.5 1.5 0 0 0 0-3z" />
                                                                    <path d="m24 48a24 24 0 1 1 14.11-43.416 1.5 1.5 0 0 1 -1.765 2.426 20.972 20.972 0 1 0 6.139 7.014 1.5 1.5 0 0 1 2.639-1.424 24.007 24.007 0 0 1 -21.123 35.4z" />
                                                                    <path d="m41.121 10.9a1.5 1.5 0 0 1 -1.141-.524l-.27-.311a1.5 1.5 0 1 1 2.242-1.993l.308.353a1.5 1.5 0 0 1 -1.139 2.475z" />
                                                                </g>
                                                            </svg> - 2000
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Vehicle Name</label>
                                                    <p class="text-light">Innova</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Seat Capacity</label>
                                                    <p class="text-light">6</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>km Limit</label>
                                                    <p class="text-light">100km</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Time Limit</label>
                                                    <p class="text-light">12hrs</p>
                                                </div>
                                                <?php endwhile; ?>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="transport" class="table table-flush-spacing border table-bordered">
                                                            <thead class="table-head">
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Travel From</th>
                                                                    <th>Travel To</th>
                                                                    <th>Distance(Kms)</th>
                                                                    <th>Travel Date</th>
                                                                    <th>Travel Time</th>
                                                                    <th>Travel Minutes</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Park Hotel</td>
                                                                    <td>Mylapore</td>
                                                                    <td>5KM</td>
                                                                    <td>October 14, 2023</td>
                                                                    <td>9AM</td>
                                                                    <td>25Minutes</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Mylapore</td>
                                                                    <td>Egmore </td>
                                                                    <td>6KM</td>
                                                                    <td>October 14, 2023</td>
                                                                    <td>10.30AM</td>
                                                                    <td>30Minutes</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider">
                                                <div class="divider-text">
                                                    <i class="ti ti-star"></i>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="d-flex justify-content-between">
                                                    <h6>XUV</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="mx-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="0 0 20 32" class="mb-1">
                                                                <g id="location_pin_red" transform="translate(-322 -1036)">
                                                                    <g id="Ellipse_20" data-name="Ellipse 20" transform="translate(338 1056) rotate(90)" fill="#fff" stroke="#0058ff" stroke-width="1">
                                                                        <circle cx="6" cy="6" r="6" stroke="none" />
                                                                        <circle cx="6" cy="6" r="5.5" fill="none" />
                                                                    </g>
                                                                    <g id="location_pin_red-2" data-name="location_pin_red" transform="translate(264.849 1036)">
                                                                        <path id="Path_507" data-name="Path 507" d="M77.151,10.1c0,8.086-9.04,15.574-9.04,15.574a1.569,1.569,0,0,1-1.921,0s-9.04-7.488-9.04-15.574a10,10,0,1,1,20,0Z" fill="#ee3840" />
                                                                        <path id="Path_508" data-name="Path 508" d="M160.582,108.943a5.09,5.09,0,1,1,4.623-5.068A4.866,4.866,0,0,1,160.582,108.943Z" transform="translate(-93.431 -93.781)" fill="#ffe1d6" />
                                                                    </g>
                                                                </g>
                                                            </svg> - 25KM
                                                        </div>
                                                        <div class="mx-2">
                                                            <svg id="Layer_1_copy_2" height="22" viewBox="0 0 48 48" width="22" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1 copy 2" class="mb-1">
                                                                <g fill="#ffb400">
                                                                    <path d="m29.583 17.405h-1.078a7.093 7.093 0 0 0 -.914-1.776h1.992a1.5 1.5 0 0 0 0-3h-7.625c-.055 0-.108-.008-.164-.008h-3.967a1.5 1.5 0 0 0 0 3h3.967a4.1 4.1 0 0 1 3.383 1.784h-7.288a1.5 1.5 0 1 0 0 3h7.945a4.105 4.105 0 0 1 -4.04 3.432h-3.967a1.5 1.5 0 0 0 -1.088 2.532l8.11 8.55a1.5 1.5 0 1 0 2.176-2.064l-5.708-6.018h.477a7.117 7.117 0 0 0 7.075-6.432h.714a1.5 1.5 0 0 0 0-3z" />
                                                                    <path d="m24 48a24 24 0 1 1 14.11-43.416 1.5 1.5 0 0 1 -1.765 2.426 20.972 20.972 0 1 0 6.139 7.014 1.5 1.5 0 0 1 2.639-1.424 24.007 24.007 0 0 1 -21.123 35.4z" />
                                                                    <path d="m41.121 10.9a1.5 1.5 0 0 1 -1.141-.524l-.27-.311a1.5 1.5 0 1 1 2.242-1.993l.308.353a1.5 1.5 0 0 1 -1.139 2.475z" />
                                                                </g>
                                                            </svg> - 2000
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Vehicle Name</label>
                                                    <p class="text-light">XUV</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Seat Capacity</label>
                                                    <p class="text-light">4</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>km Limit</label>
                                                    <p class="text-light">120km</p>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Time Limit</label>
                                                    <p class="text-light">12hrs</p>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table id="transport" class="table table-flush-spacing">
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Travel From</th>
                                                                    <th>Travel To</th>
                                                                    <th>Distance(Kms)</th>
                                                                    <th>Travel Date</th>
                                                                    <th>Travel Time</th>
                                                                    <th>Travel Minutes</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>T.Nagar</td>
                                                                    <td>Marina Beach</td>
                                                                    <td>7KM</td>
                                                                    <td>October 15, 2023</td>
                                                                    <td>10.00AM</td>
                                                                    <td>30Minutes</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Marina Beach</td>
                                                                    <td>Guindy National Park</td>
                                                                    <td>12KM</td>
                                                                    <td>October 15, 2023</td>
                                                                    <td>2.00AM</td>
                                                                    <td>1hr</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mt-4">
                                                <div class="col-md-12">
                                                    <!-- <div class="card p-4"> -->
                                                    <h5 class="text-primary">Hotel Details</h5>
                                                    <div class="table-responsive">
                                                        <table id="transport" class="table table-flush-spacing">
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Hotel Name</th>
                                                                    <th>Hotel Code</th>
                                                                    <th>Staying Date</th>
                                                                    <th>City</th>
                                                                    <th>Address</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1.</td>
                                                                    <td>Park Hotel</td>
                                                                    <td>DVIHTL360492</td>
                                                                    <td>October 14, 2023</td>
                                                                    <td>Chennai</td>
                                                                    <td>601, Anna Salai, Chennai 600006 , India. Reservations. 044 42676000. 044 42144100.</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hotel" href="itinerary_room_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_2" height="26" viewBox="0 0 64 64" width="26" xmlns="http://www.w3.org/2000/svg" data-name="Layer 2" fill="#7367f0">
                                                                                        <path d="m42.254 6.4a20.447 20.447 0 0 0 -10.254-2.4 20.4 20.4 0 0 0 -10.509 2.544 20.415 20.415 0 0 0 -10.064 17.878c.243 8.147 5.435 15.665 10.053 22.207a156.735 156.735 0 0 0 10.52 13.371c6.513-7.378 12.762-15.47 17.26-24.244 1.889-3.686 3.319-7.392 3.319-11.5a20.544 20.544 0 0 0 -10.325-17.856zm-10.254 35.1a16.75 16.75 0 1 1 16.75-16.75 16.75 16.75 0 0 1 -16.75 16.75z" />
                                                                                        <path d="m29 27v-4.9s10.3-1.146 12.961 4.323a.414.414 0 0 1 -.4.577z" />
                                                                                        <circle cx="26" cy="24.025" r="2" />
                                                                                        <path d="m21.5 17a1.5 1.5 0 0 1 1.5 1.5v13.451a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-13.451a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                        <path d="m42.5 23a1.5 1.5 0 0 1 1.5 1.5v7.5a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-7.5a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                        <path d="m30.508 19.492h2.984v18h-2.984z" transform="matrix(0 -1 1 0 3.508 60.492)" />
                                                                                    </svg></span> </a> <a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Amenities" href="itinerary_amenities_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_1" enable-background="new 0 0 512 512" height="22" viewBox="0 0 512 512" width="22" xmlns="http://www.w3.org/2000/svg" fill="#888686">
                                                                                        <g>
                                                                                            <path d="m496 480h-11.507l-12.97-51.88c-1.782-7.123-8.181-12.12-15.523-12.12h-56v-24h48c8.837 0 16-7.164 16-16s-7.163-16-16-16v-8c0-100.481-77.59-183.17-176-191.328v-24.672h24c8.837 0 16-7.164 16-16s-7.163-16-16-16h-80c-8.837 0-16 7.164-16 16s7.163 16 16 16h24v24.672c-98.41 8.158-176 90.847-176 191.328v8c-8.837 0-16 7.164-16 16s7.163 16 16 16h48v24h-56c-7.342 0-13.741 4.997-15.522 12.12l-12.971 51.88h-11.507c-8.837 0-16 7.164-16 16s7.163 16 16 16h480c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-400-128c0-88.224 71.775-160 160-160s160 71.776 160 160v8c-48.629 0-305.697 0-320 0zm48 40h224v24h-224zm-83.508 88 8-32h375.016l8 32z" />
                                                                                            <path d="m16 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                            <path d="m464 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                            <path d="m97.608 120.236c6.248 6.248 16.379 6.249 22.627 0 6.249-6.249 6.249-16.379 0-22.627l-22.628-22.629c-6.248-6.248-16.379-6.249-22.627 0-6.249 6.249-6.249 16.379 0 22.627z" />
                                                                                            <path d="m403.078 124.922c4.095 0 8.189-1.563 11.313-4.686l22.628-22.627c6.249-6.249 6.249-16.379 0-22.627-6.247-6.248-16.378-6.248-22.627 0l-22.628 22.627c-10.108 10.107-2.812 27.313 11.314 27.313z" />
                                                                                            <path d="m256 64c8.837 0 16-7.164 16-16v-32c0-8.836-7.163-16-16-16s-16 7.164-16 16v32c0 8.836 7.163 16 16 16z" />
                                                                                        </g>
                                                                                    </svg> </span> </a></div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2.</td>
                                                                    <td>Hilton Hotel</td>
                                                                    <td>DVIHTL3605262</td>
                                                                    <td>October 15, 2023</td>
                                                                    <td>Chennai</td>
                                                                    <td>601, Anna Salai, Chennai 600006 , India. Reservations. 044 42676000. 044 42144100.</td>
                                                                    <td>
                                                                        <div class="d-flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hotel" href="itinerary_room_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_2" height="26" viewBox="0 0 64 64" width="26" xmlns="http://www.w3.org/2000/svg" data-name="Layer 2" fill="#7367f0">
                                                                                        <path d="m42.254 6.4a20.447 20.447 0 0 0 -10.254-2.4 20.4 20.4 0 0 0 -10.509 2.544 20.415 20.415 0 0 0 -10.064 17.878c.243 8.147 5.435 15.665 10.053 22.207a156.735 156.735 0 0 0 10.52 13.371c6.513-7.378 12.762-15.47 17.26-24.244 1.889-3.686 3.319-7.392 3.319-11.5a20.544 20.544 0 0 0 -10.325-17.856zm-10.254 35.1a16.75 16.75 0 1 1 16.75-16.75 16.75 16.75 0 0 1 -16.75 16.75z" />
                                                                                        <path d="m29 27v-4.9s10.3-1.146 12.961 4.323a.414.414 0 0 1 -.4.577z" />
                                                                                        <circle cx="26" cy="24.025" r="2" />
                                                                                        <path d="m21.5 17a1.5 1.5 0 0 1 1.5 1.5v13.451a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-13.451a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                        <path d="m42.5 23a1.5 1.5 0 0 1 1.5 1.5v7.5a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-7.5a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                        <path d="m30.508 19.492h2.984v18h-2.984z" transform="matrix(0 -1 1 0 3.508 60.492)" />
                                                                                    </svg></span> </a> <a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Amenities" href="itinerary_amenities_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_1" enable-background="new 0 0 512 512" height="22" viewBox="0 0 512 512" width="22" xmlns="http://www.w3.org/2000/svg" fill="#888686">
                                                                                        <g>
                                                                                            <path d="m496 480h-11.507l-12.97-51.88c-1.782-7.123-8.181-12.12-15.523-12.12h-56v-24h48c8.837 0 16-7.164 16-16s-7.163-16-16-16v-8c0-100.481-77.59-183.17-176-191.328v-24.672h24c8.837 0 16-7.164 16-16s-7.163-16-16-16h-80c-8.837 0-16 7.164-16 16s7.163 16 16 16h24v24.672c-98.41 8.158-176 90.847-176 191.328v8c-8.837 0-16 7.164-16 16s7.163 16 16 16h48v24h-56c-7.342 0-13.741 4.997-15.522 12.12l-12.971 51.88h-11.507c-8.837 0-16 7.164-16 16s7.163 16 16 16h480c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-400-128c0-88.224 71.775-160 160-160s160 71.776 160 160v8c-48.629 0-305.697 0-320 0zm48 40h224v24h-224zm-83.508 88 8-32h375.016l8 32z" />
                                                                                            <path d="m16 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                            <path d="m464 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                            <path d="m97.608 120.236c6.248 6.248 16.379 6.249 22.627 0 6.249-6.249 6.249-16.379 0-22.627l-22.628-22.629c-6.248-6.248-16.379-6.249-22.627 0-6.249 6.249-6.249 16.379 0 22.627z" />
                                                                                            <path d="m403.078 124.922c4.095 0 8.189-1.563 11.313-4.686l22.628-22.627c6.249-6.249 6.249-16.379 0-22.627-6.247-6.248-16.378-6.248-22.627 0l-22.628 22.627c-10.108 10.107-2.812 27.313 11.314 27.313z" />
                                                                                            <path d="m256 64c8.837 0 16-7.164 16-16v-32c0-8.836-7.163-16-16-16s-16 7.164-16 16v32c0 8.836 7.163 16 16 16z" />
                                                                                        </g>
                                                                                    </svg> </span> </a></div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- </div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-pills-top-itinerary-2" role="tabpanel">
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header d-flex align-items-center">
                                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-2" aria-expanded="false">
                                                        <i class="me-2 ti ti-map-pin ti-xs"></i>
                                                        Route
                                                    </button>
                                                </h2>
                                                <div id="accordionWithIcon-2" class="accordion-collapse collapse">
                                                    <div class="accordion-body">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-pills-top-itinerary-3" role="tabpanel">
                                            <div class="accordion-item card">
                                                <h2 class="accordion-header d-flex align-items-center">
                                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-3" aria-expanded="false">
                                                        <i class="me-2 ti ti-map-pin ti-xs"></i>
                                                        Route
                                                    </button>
                                                </h2>
                                                <div id="accordionWithIcon-3" class="accordion-collapse collapse">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <div class="col">
                                                                <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                                                    <div class="timeline-step ms-0">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Chennai</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Tirupathi</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Pondicherry</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(2 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Tanjore</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Madurai</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Rameswaram</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Kanyakumari</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Trivandrum</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-step mb-0">
                                                                        <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                            <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                            <p class="h6 mt-3 mb-1">Trivandrum Airport Drop</p>
                                                                            <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Button -->

                        <div class="text-end">
                            <button type="button" class="btn btn-google-plus downloadPdfBtn">
                                <img src="assets/img/icons/pdf-icon.svg" class="me-2"> PDF Download
                            </button>
                        </div>

                        <!-- <div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
                            <ul class="nav p-2 nav-pills card-header-pills " role="tablist">
                                <li class="nav-item ms-0 mx-2">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="true">Basic Info</button>
                                </li>
                                <li class="nav-item mx-2">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile" aria-selected="false">Hotspot</button>
                                </li>
                                <li class="nav-item mx-2">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-messages" aria-controls="navs-pills-top-messages" aria-selected="false">Vehicle</button>
                                </li>
                                <li class="nav-item mx-2">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-hotel" aria-controls="navs-pills-top-hotel" aria-selected="false">Hotel</button>
                                </li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="nav-align-top mb-4">
                                    <div class="tab-content p-0" style="background-color: transparent;">
                                        <div class="tab-pane fade show active" id="navs-pills-top-home" role="tabpanel">
                                            <div class="card p-4">
                                                <div class="row">
                                                    <h5 class="text-primary">Basic Info</h5>
                                                    <div class="col-md-3">
                                                        <label>Arrival</label>
                                                        <p class="text-light">Chennai, Tamil Nadu, India</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Destination</label>
                                                        <p class="text-light">Trivandrum</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Days</label>
                                                        <p class="text-light">7</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Night</label>
                                                        <p class="text-light">6</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Route</label>
                                                        <p class="text-light">3</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>OverallCost</label>
                                                        <p class="text-light">50000</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Start Date</label>
                                                        <p class="text-light">10-11-2023 08:00 AM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>End Date</label>
                                                        <p class="text-light">12-11-2023 12:00 PM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Adult</label>
                                                        <p class="text-light">4</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Children</label>
                                                        <p class="text-light">0</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Infants</label>
                                                        <p class="text-light">4</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row my-2">
                                                <div class="col-12">
                                                    <div class="accordion mt-3" id="accordionWithIcon">
                                                        <div class="card accordion-item">
                                                            <h2 class="accordion-header d-flex align-items-center">
                                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-1" aria-expanded="false">
                                                                    <i class="me-2 ti ti-map-pin ti-xs"></i>
                                                                    Route - 1
                                                                </button>
                                                            </h2>
                                                            <div id="accordionWithIcon-1" class="accordion-collapse collapse">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                                                                <div class="timeline-step ms-0">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Chennai</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(3 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Mahabalipuram</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Pondicherry</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Tanjore</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trichy</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Madurai</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Rameswaram</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Kanyakumari</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trivandrum</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step mb-0">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trivandrum Airport Drop</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header d-flex align-items-center">
                                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-2" aria-expanded="false">
                                                                    <i class="me-2 ti ti-map-pin ti-xs"></i>
                                                                    Route - 2
                                                                </button>
                                                            </h2>
                                                            <div id="accordionWithIcon-2" class="accordion-collapse collapse">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                                                                <div class="timeline-step ms-0">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Chennai</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Pondicherry</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(2 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Tanjore</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trichy</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Madurai</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Rameswaram</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Kanyakumari</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trivandrum</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step mb-0">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trivandrum Airport Drop</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="accordion-item card">
                                                            <h2 class="accordion-header d-flex align-items-center">
                                                                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-3" aria-expanded="false">
                                                                    <i class="me-2 ti ti-map-pin ti-xs"></i>
                                                                    Route - 3
                                                                </button>
                                                            </h2>
                                                            <div id="accordionWithIcon-3" class="accordion-collapse collapse">
                                                                <div class="accordion-body">
                                                                    <div class="row">
                                                                        <div class="col">
                                                                            <div class="timeline-steps aos-init aos-animate" data-aos="fade-up">
                                                                                <div class="timeline-step ms-0">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2003">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Chennai</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2004">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Tirupathi</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2005">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Pondicherry</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(2 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2010">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Tanjore</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Madurai</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Rameswaram</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Kanyakumari</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trivandrum</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="timeline-step mb-0">
                                                                                    <div class="timeline-content" data-toggle="popover" data-trigger="hover" data-placement="top" title="" data-content="And here's some amazing content. It's very engaging. Right?" data-original-title="2020">
                                                                                        <i class="me-2 ti ti-map-pin ti-xs fs-2 text-success"></i>
                                                                                        <p class="h6 mt-3 mb-1">Trivandrum Airport Drop</p>
                                                                                        <p class="h6 text-muted mb-0 mb-lg-0">(1 Night)</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-pills-top-profile" role="tabpanel">
                                            <div class="card p-4">
                                                <div class="row">
                                                    <h5 class="text-primary">Kapaleeshwarar Temple</h5>
                                                    <div class="col-md-3">
                                                        <label>Hotspot</label>
                                                        <p class="text-light">Kapaleeshwarar Temple</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Place</label>
                                                        <p class="text-light">Mylapore, Chennai, Tamil Nadu</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Sightseeing Date</label>
                                                        <p class="text-light">October 14, 2023</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Sightseeing Time</label>
                                                        <p class="text-light">@9.30AM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Visiting Time</label>
                                                        <p class="text-light">9:30 AM to 10:30 AM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Duration</label>
                                                        <p class="text-light">1hr</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Fare</label>
                                                        <p class="text-light">No Fare</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Guide</label>
                                                        <p class="text-success fw-bold"><span class="badge bg-label-danger me-1">No Guide</span></p>
                                                    </div>
                                                </div>
                                                <div class="divider">
                                                    <div class="divider-text">
                                                        <i class="ti ti-star"></i>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h5 class="text-primary">Government Museum</h5>
                                                    <div class="col-md-3">
                                                        <label>Hotspot</label>
                                                        <p class="text-light">Government Museum</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Place</label>
                                                        <p class="text-light">Egmore, Chennai, Tamil Nadu</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Sightseeing Date</label>
                                                        <p class="text-light">October 14, 2023</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Sightseeing Time</label>
                                                        <p class="text-light">@11.00AM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Visiting Time</label>
                                                        <p class="text-light">11:00 AM to 12:00 PM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Duration</label>
                                                        <p class="text-light">1hr</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Fare</label>
                                                        <p class="text-light">₹250</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Guide</label>
                                                        <p class="text-success fw-bold"><span class="badge bg-label-success me-1">With Guide</span></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Person</label>
                                                        <p class="text-light">5</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>TimeLimit</label>
                                                        <p class="text-light">2hr</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Guide Price</label>
                                                        <p class="text-light">₹150</p>
                                                    </div>
                                                </div>
                                                <div class="divider">
                                                    <div class="divider-text">
                                                        <i class="ti ti-star"></i>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h5 class="text-primary">National Art Gallery</h5>
                                                    <div class="col-md-3">
                                                        <label>Hotspot</label>
                                                        <p class="text-light">National Art Gallery</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Place</label>
                                                        <p class="text-light">Egmore, Chennai, Tamil Nadu</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Sightseeing Date</label>
                                                        <p class="text-light">October 14, 2023</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Sightseeing Time</label>
                                                        <p class="text-light">@12.00PM</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Visiting Time</label>
                                                        <p class="text-light">12:00 PM to 1:00 PM </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Duration</label>
                                                        <p class="text-light">1hr</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Fare</label>
                                                        <p class="text-light">₹25</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Guide</label>
                                                        <p class="text-success fw-bold"><span class="badge bg-label-success me-1">With Guide</span></p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Person</label>
                                                        <p class="text-light">4</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>TimeLimit</label>
                                                        <p class="text-light">1hr</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Guide Price</label>
                                                        <p class="text-light">₹100</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-pills-top-messages" role="tabpanel">
                                            <div class="card p-4">
                                                <div class="row">
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="text-primary">Innova</h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="mx-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="0 0 20 32" class="mb-1">
                                                                    <g id="location_pin_red" transform="translate(-322 -1036)">
                                                                        <g id="Ellipse_20" data-name="Ellipse 20" transform="translate(338 1056) rotate(90)" fill="#fff" stroke="#0058ff" stroke-width="1">
                                                                            <circle cx="6" cy="6" r="6" stroke="none" />
                                                                            <circle cx="6" cy="6" r="5.5" fill="none" />
                                                                        </g>
                                                                        <g id="location_pin_red-2" data-name="location_pin_red" transform="translate(264.849 1036)">
                                                                            <path id="Path_507" data-name="Path 507" d="M77.151,10.1c0,8.086-9.04,15.574-9.04,15.574a1.569,1.569,0,0,1-1.921,0s-9.04-7.488-9.04-15.574a10,10,0,1,1,20,0Z" fill="#ee3840" />
                                                                            <path id="Path_508" data-name="Path 508" d="M160.582,108.943a5.09,5.09,0,1,1,4.623-5.068A4.866,4.866,0,0,1,160.582,108.943Z" transform="translate(-93.431 -93.781)" fill="#ffe1d6" />
                                                                        </g>
                                                                    </g>
                                                                </svg> - 25KM
                                                            </div>
                                                            <div class="mx-2">
                                                                <svg id="Layer_1_copy_2" height="22" viewBox="0 0 48 48" width="22" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1 copy 2" class="mb-1">
                                                                    <g fill="#ffb400">
                                                                        <path d="m29.583 17.405h-1.078a7.093 7.093 0 0 0 -.914-1.776h1.992a1.5 1.5 0 0 0 0-3h-7.625c-.055 0-.108-.008-.164-.008h-3.967a1.5 1.5 0 0 0 0 3h3.967a4.1 4.1 0 0 1 3.383 1.784h-7.288a1.5 1.5 0 1 0 0 3h7.945a4.105 4.105 0 0 1 -4.04 3.432h-3.967a1.5 1.5 0 0 0 -1.088 2.532l8.11 8.55a1.5 1.5 0 1 0 2.176-2.064l-5.708-6.018h.477a7.117 7.117 0 0 0 7.075-6.432h.714a1.5 1.5 0 0 0 0-3z" />
                                                                        <path d="m24 48a24 24 0 1 1 14.11-43.416 1.5 1.5 0 0 1 -1.765 2.426 20.972 20.972 0 1 0 6.139 7.014 1.5 1.5 0 0 1 2.639-1.424 24.007 24.007 0 0 1 -21.123 35.4z" />
                                                                        <path d="m41.121 10.9a1.5 1.5 0 0 1 -1.141-.524l-.27-.311a1.5 1.5 0 1 1 2.242-1.993l.308.353a1.5 1.5 0 0 1 -1.139 2.475z" />
                                                                    </g>
                                                                </svg> - 2000
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Vehicle Name</label>
                                                        <p class="text-light">Innova</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Seat Capacity</label>
                                                        <p class="text-light">6</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>km Limit</label>
                                                        <p class="text-light">100km</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Time Limit</label>
                                                        <p class="text-light">12hrs</p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table id="transport" class="table table-flush-spacing border table-bordered">
                                                                <thead class="table-head">
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Travel From</th>
                                                                        <th>Travel To</th>
                                                                        <th>Distance(Kms)</th>
                                                                        <th>Travel Date</th>
                                                                        <th>Travel Time</th>
                                                                        <th>Travel Minutes</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1.</td>
                                                                        <td>Park Hotel</td>
                                                                        <td>Mylapore</td>
                                                                        <td>5KM</td>
                                                                        <td>October 14, 2023</td>
                                                                        <td>9AM</td>
                                                                        <td>25Minutes</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2.</td>
                                                                        <td>Mylapore</td>
                                                                        <td>Egmore </td>
                                                                        <td>6KM</td>
                                                                        <td>October 14, 2023</td>
                                                                        <td>10.30AM</td>
                                                                        <td>30Minutes</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="divider">
                                                    <div class="divider-text">
                                                        <i class="ti ti-star"></i>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="text-primary">XUV</h5>
                                                        <div class="d-flex align-items-center">
                                                            <div class="mx-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="0 0 20 32" class="mb-1">
                                                                    <g id="location_pin_red" transform="translate(-322 -1036)">
                                                                        <g id="Ellipse_20" data-name="Ellipse 20" transform="translate(338 1056) rotate(90)" fill="#fff" stroke="#0058ff" stroke-width="1">
                                                                            <circle cx="6" cy="6" r="6" stroke="none" />
                                                                            <circle cx="6" cy="6" r="5.5" fill="none" />
                                                                        </g>
                                                                        <g id="location_pin_red-2" data-name="location_pin_red" transform="translate(264.849 1036)">
                                                                            <path id="Path_507" data-name="Path 507" d="M77.151,10.1c0,8.086-9.04,15.574-9.04,15.574a1.569,1.569,0,0,1-1.921,0s-9.04-7.488-9.04-15.574a10,10,0,1,1,20,0Z" fill="#ee3840" />
                                                                            <path id="Path_508" data-name="Path 508" d="M160.582,108.943a5.09,5.09,0,1,1,4.623-5.068A4.866,4.866,0,0,1,160.582,108.943Z" transform="translate(-93.431 -93.781)" fill="#ffe1d6" />
                                                                        </g>
                                                                    </g>
                                                                </svg> - 25KM
                                                            </div>
                                                            <div class="mx-2">
                                                                <svg id="Layer_1_copy_2" height="22" viewBox="0 0 48 48" width="22" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1 copy 2" class="mb-1">
                                                                    <g fill="#ffb400">
                                                                        <path d="m29.583 17.405h-1.078a7.093 7.093 0 0 0 -.914-1.776h1.992a1.5 1.5 0 0 0 0-3h-7.625c-.055 0-.108-.008-.164-.008h-3.967a1.5 1.5 0 0 0 0 3h3.967a4.1 4.1 0 0 1 3.383 1.784h-7.288a1.5 1.5 0 1 0 0 3h7.945a4.105 4.105 0 0 1 -4.04 3.432h-3.967a1.5 1.5 0 0 0 -1.088 2.532l8.11 8.55a1.5 1.5 0 1 0 2.176-2.064l-5.708-6.018h.477a7.117 7.117 0 0 0 7.075-6.432h.714a1.5 1.5 0 0 0 0-3z" />
                                                                        <path d="m24 48a24 24 0 1 1 14.11-43.416 1.5 1.5 0 0 1 -1.765 2.426 20.972 20.972 0 1 0 6.139 7.014 1.5 1.5 0 0 1 2.639-1.424 24.007 24.007 0 0 1 -21.123 35.4z" />
                                                                        <path d="m41.121 10.9a1.5 1.5 0 0 1 -1.141-.524l-.27-.311a1.5 1.5 0 1 1 2.242-1.993l.308.353a1.5 1.5 0 0 1 -1.139 2.475z" />
                                                                    </g>
                                                                </svg> - 2000
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Vehicle Name</label>
                                                        <p class="text-light">XUV</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Seat Capacity</label>
                                                        <p class="text-light">4</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>km Limit</label>
                                                        <p class="text-light">120km</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Time Limit</label>
                                                        <p class="text-light">12hrs</p>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table id="transport" class="table table-flush-spacing border table-bordered">
                                                                <thead class="table-head">
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Travel From</th>
                                                                        <th>Travel To</th>
                                                                        <th>Distance(Kms)</th>
                                                                        <th>Travel Date</th>
                                                                        <th>Travel Time</th>
                                                                        <th>Travel Minutes</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1.</td>
                                                                        <td>T.Nagar</td>
                                                                        <td>Marina Beach</td>
                                                                        <td>7KM</td>
                                                                        <td>October 15, 2023</td>
                                                                        <td>10.00AM</td>
                                                                        <td>30Minutes</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2.</td>
                                                                        <td>Marina Beach</td>
                                                                        <td>Guindy National Park</td>
                                                                        <td>12KM</td>
                                                                        <td>October 15, 2023</td>
                                                                        <td>2.00AM</td>
                                                                        <td>1hr</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="navs-pills-top-hotel" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card p-4">
                                                        <h5 class="text-primary">Hotel Details</h5>
                                                        <div class="table-responsive">
                                                            <table id="transport" class="table table-flush-spacing border table-bordered">
                                                                <thead class="table-head">
                                                                    <tr>
                                                                        <th>S.No</th>
                                                                        <th>Hotel Name</th>
                                                                        <th>Hotel Code</th>
                                                                        <th>Staying Date</th>
                                                                        <th>City</th>
                                                                        <th>Address</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>1.</td>
                                                                        <td>Park Hotel</td>
                                                                        <td>DVIHTL360492</td>
                                                                        <td>October 14, 2023</td>
                                                                        <td>Chennai</td>
                                                                        <td>601, Anna Salai, Chennai 600006 , India. Reservations. 044 42676000. 044 42144100.</td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hotel" href="itinerary_room_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_2" height="26" viewBox="0 0 64 64" width="26" xmlns="http://www.w3.org/2000/svg" data-name="Layer 2" fill="#7367f0">
                                                                                            <path d="m42.254 6.4a20.447 20.447 0 0 0 -10.254-2.4 20.4 20.4 0 0 0 -10.509 2.544 20.415 20.415 0 0 0 -10.064 17.878c.243 8.147 5.435 15.665 10.053 22.207a156.735 156.735 0 0 0 10.52 13.371c6.513-7.378 12.762-15.47 17.26-24.244 1.889-3.686 3.319-7.392 3.319-11.5a20.544 20.544 0 0 0 -10.325-17.856zm-10.254 35.1a16.75 16.75 0 1 1 16.75-16.75 16.75 16.75 0 0 1 -16.75 16.75z" />
                                                                                            <path d="m29 27v-4.9s10.3-1.146 12.961 4.323a.414.414 0 0 1 -.4.577z" />
                                                                                            <circle cx="26" cy="24.025" r="2" />
                                                                                            <path d="m21.5 17a1.5 1.5 0 0 1 1.5 1.5v13.451a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-13.451a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                            <path d="m42.5 23a1.5 1.5 0 0 1 1.5 1.5v7.5a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-7.5a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                            <path d="m30.508 19.492h2.984v18h-2.984z" transform="matrix(0 -1 1 0 3.508 60.492)" />
                                                                                        </svg></span> </a> <a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Amenities" href="itinerary_amenities_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_1" enable-background="new 0 0 512 512" height="22" viewBox="0 0 512 512" width="22" xmlns="http://www.w3.org/2000/svg" fill="#888686">
                                                                                            <g>
                                                                                                <path d="m496 480h-11.507l-12.97-51.88c-1.782-7.123-8.181-12.12-15.523-12.12h-56v-24h48c8.837 0 16-7.164 16-16s-7.163-16-16-16v-8c0-100.481-77.59-183.17-176-191.328v-24.672h24c8.837 0 16-7.164 16-16s-7.163-16-16-16h-80c-8.837 0-16 7.164-16 16s7.163 16 16 16h24v24.672c-98.41 8.158-176 90.847-176 191.328v8c-8.837 0-16 7.164-16 16s7.163 16 16 16h48v24h-56c-7.342 0-13.741 4.997-15.522 12.12l-12.971 51.88h-11.507c-8.837 0-16 7.164-16 16s7.163 16 16 16h480c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-400-128c0-88.224 71.775-160 160-160s160 71.776 160 160v8c-48.629 0-305.697 0-320 0zm48 40h224v24h-224zm-83.508 88 8-32h375.016l8 32z" />
                                                                                                <path d="m16 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                                <path d="m464 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                                <path d="m97.608 120.236c6.248 6.248 16.379 6.249 22.627 0 6.249-6.249 6.249-16.379 0-22.627l-22.628-22.629c-6.248-6.248-16.379-6.249-22.627 0-6.249 6.249-6.249 16.379 0 22.627z" />
                                                                                                <path d="m403.078 124.922c4.095 0 8.189-1.563 11.313-4.686l22.628-22.627c6.249-6.249 6.249-16.379 0-22.627-6.247-6.248-16.378-6.248-22.627 0l-22.628 22.627c-10.108 10.107-2.812 27.313 11.314 27.313z" />
                                                                                                <path d="m256 64c8.837 0 16-7.164 16-16v-32c0-8.836-7.163-16-16-16s-16 7.164-16 16v32c0 8.836 7.163 16 16 16z" />
                                                                                            </g>
                                                                                        </svg> </span> </a></div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>2.</td>
                                                                        <td>Hilton Hotel</td>
                                                                        <td>DVIHTL3605262</td>
                                                                        <td>October 15, 2023</td>
                                                                        <td>Chennai</td>
                                                                        <td>601, Anna Salai, Chennai 600006 , India. Reservations. 044 42676000. 044 42144100.</td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Hotel" href="itinerary_room_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_2" height="26" viewBox="0 0 64 64" width="26" xmlns="http://www.w3.org/2000/svg" data-name="Layer 2" fill="#7367f0">
                                                                                            <path d="m42.254 6.4a20.447 20.447 0 0 0 -10.254-2.4 20.4 20.4 0 0 0 -10.509 2.544 20.415 20.415 0 0 0 -10.064 17.878c.243 8.147 5.435 15.665 10.053 22.207a156.735 156.735 0 0 0 10.52 13.371c6.513-7.378 12.762-15.47 17.26-24.244 1.889-3.686 3.319-7.392 3.319-11.5a20.544 20.544 0 0 0 -10.325-17.856zm-10.254 35.1a16.75 16.75 0 1 1 16.75-16.75 16.75 16.75 0 0 1 -16.75 16.75z" />
                                                                                            <path d="m29 27v-4.9s10.3-1.146 12.961 4.323a.414.414 0 0 1 -.4.577z" />
                                                                                            <circle cx="26" cy="24.025" r="2" />
                                                                                            <path d="m21.5 17a1.5 1.5 0 0 1 1.5 1.5v13.451a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-13.451a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                            <path d="m42.5 23a1.5 1.5 0 0 1 1.5 1.5v7.5a0 0 0 0 1 0 0h-3a0 0 0 0 1 0 0v-7.5a1.5 1.5 0 0 1 1.5-1.5z" />
                                                                                            <path d="m30.508 19.492h2.984v18h-2.984z" transform="matrix(0 -1 1 0 3.508 60.492)" />
                                                                                        </svg></span> </a> <a class="btn btn-sm btn-icon text-primary flex-end shadow-none" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Amenities" href="itinerary_amenities_preview.php" style="margin-right: 10px;"><span class="btn-inner"> <svg id="Layer_1" enable-background="new 0 0 512 512" height="22" viewBox="0 0 512 512" width="22" xmlns="http://www.w3.org/2000/svg" fill="#888686">
                                                                                            <g>
                                                                                                <path d="m496 480h-11.507l-12.97-51.88c-1.782-7.123-8.181-12.12-15.523-12.12h-56v-24h48c8.837 0 16-7.164 16-16s-7.163-16-16-16v-8c0-100.481-77.59-183.17-176-191.328v-24.672h24c8.837 0 16-7.164 16-16s-7.163-16-16-16h-80c-8.837 0-16 7.164-16 16s7.163 16 16 16h24v24.672c-98.41 8.158-176 90.847-176 191.328v8c-8.837 0-16 7.164-16 16s7.163 16 16 16h48v24h-56c-7.342 0-13.741 4.997-15.522 12.12l-12.971 51.88h-11.507c-8.837 0-16 7.164-16 16s7.163 16 16 16h480c8.837 0 16-7.164 16-16s-7.163-16-16-16zm-400-128c0-88.224 71.775-160 160-160s160 71.776 160 160v8c-48.629 0-305.697 0-320 0zm48 40h224v24h-224zm-83.508 88 8-32h375.016l8 32z" />
                                                                                                <path d="m16 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                                <path d="m464 272h32c8.837 0 16-7.164 16-16s-7.163-16-16-16h-32c-8.837 0-16 7.164-16 16s7.163 16 16 16z" />
                                                                                                <path d="m97.608 120.236c6.248 6.248 16.379 6.249 22.627 0 6.249-6.249 6.249-16.379 0-22.627l-22.628-22.629c-6.248-6.248-16.379-6.249-22.627 0-6.249 6.249-6.249 16.379 0 22.627z" />
                                                                                                <path d="m403.078 124.922c4.095 0 8.189-1.563 11.313-4.686l22.628-22.627c6.249-6.249 6.249-16.379 0-22.627-6.247-6.248-16.378-6.248-22.627 0l-22.628 22.627c-10.108 10.107-2.812 27.313 11.314 27.313z" />
                                                                                                <path d="m256 64c8.837 0 16-7.164 16-16v-32c0-8.836-7.163-16-16-16s-16 7.164-16 16v32c0 8.836 7.163 16 16 16z" />
                                                                                            </g>
                                                                                        </svg> </span> </a></div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include_once('public/__footer.php'); ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>

    </div>
    <!-- / Layout wrapper -->
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/js/forms-file-upload.js"></script>
    <script src="assets/vendor/libs/rateyo/rateyo.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $(".form-select").selectize();
        });
    </script>

</body>

</html>

<!-- beautify ignore:end -->
<!-- beautify ignore:end -->