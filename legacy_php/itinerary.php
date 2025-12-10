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
include_once 'jackus.php';
admin_reguser_protect();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
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
    <link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

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
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLEMAP_API_KEY; ?>&libraries=places"></script>

    <style>
        #loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            <?php include_once 'public/__sidebar.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include_once 'public/__topbar.php'; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <?php if ($_GET['route'] == '') : ?>
                            <span id="showITINERARYLIST"></span>
                        <?php elseif (
                            $_GET['route'] == 'add' &&
                            $_GET['formtype'] == 'basic_info'
                        ) : ?>
                            <span id="showITINERARYFORMSTEP1"></span>
                        <?php elseif (
                            $_GET['route'] == 'add' &&
                            $_GET['formtype'] == 'itinerary_list'
                        ) : ?>
                            <span id="showITINERARYFORMSTEP2"></span>
                        <?php elseif ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_daywise') : ?>
                            <div id="se-pre-con"></div>
                            <span id="showITINERARYDAYWISEPLAN"></span>

                        <?php elseif ($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview') : ?>
                            <div id="se-pre-con"></div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-header">Itinerary Plan</b></h5>
                                        <a href="itinerary.php" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to List</a>
                                    </div>
                                </div>
                                <?php
                                $id = $_GET['id'];
                                $select_itinerary_list_query = sqlQUERY_LABEL("SELECT `itinerary_plan_ID`, `arrival_location`, `departure_location`, `trip_start_date_and_time`, `trip_end_date_and_time`, `expecting_budget`, `no_of_routes`, `no_of_days`, `no_of_nights`, `total_adult`, `total_children`, `total_infants`, `itinerary_preference`, `preferred_room_type_id`, `preferred_room_count`, `total_extra_bed`, `preferred_vehicle_type_id`, `preferred_vehicle_count` FROM `dvi_itinerary_plan_details` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_plan_ID` = '$id'") or die("#1-SELECT-ORGANIZATION-DETAILS: UNABLE_TO_COLLECT " . sqlERROR_LABEL());
                                $total_count = sqlNUMOFROW_LABEL($select_itinerary_list_query);

                                if ($total_count > 0) :

                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_itinerary_list_query)) :
                                        $arrival_location = $fetch_data['arrival_location'];
                                        $departure_location = $fetch_data['departure_location'];
                                        //START DATE FORMAT CHANGE
                                        $trip_start_date_and_time = $fetch_data['trip_start_date_and_time'];
                                        $datestamp = strtotime($trip_start_date_and_time);
                                        $trip_start_date = date("Y-m-d", $datestamp);
                                        $start_date = strtotime($trip_start_date_and_time);
                                        $formatted_startDATE = date("F j, Y", $start_date);
                                        //END DATE FORMAT CHANGE
                                        $trip_end_date_and_time = $fetch_data['trip_end_date_and_time'];
                                        $end_date = strtotime($trip_end_date_and_time);
                                        $formatted_endDATE = date("F j, Y", $end_date);
                                        $expecting_budget = $fetch_data['expecting_budget'];
                                        $expecting_budget_format = number_format($expecting_budget, 2);
                                        $no_of_days = $fetch_data['no_of_days'];
                                        $no_of_nights = $fetch_data['no_of_nights'];
                                        $total_adult = $fetch_data['total_adult'];
                                        $total_children = $fetch_data['total_children'];
                                        $total_infants = $fetch_data['total_infants'];
                                        $no_of_nights = $fetch_data['no_of_nights'];
                                ?>
                                        <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                                            <div>
                                                <h5 class="text-capitalize text-primary"> Itinerary for <b><?= $formatted_startDATE; ?></b> to <b><?= $formatted_endDATE; ?></b> (<b><?= $no_of_days; ?></b> Day, <b><?= $no_of_nights; ?></b> Night)</h5>
                                                <h6 class="text-capitalize"><?= $arrival_location; ?> <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i><?= $departure_location; ?></h6>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <span class="mb-0 me-4"><strong>Adults</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_adult; ?></span></span>
                                                        <span class="mb-0 me-4"><strong>Children</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_children; ?></span></span>
                                                        <span class="mb-0 me-4"><strong>Infants</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2"><?= $total_infants; ?></span></span>
                                                    </div>
                                                    <h5 class="mb-0"><strong>Budget</strong></span><span class="badge bg-primary bg-glow ms-2">₹ <?= $expecting_budget_format; ?></h5>
                                                </div>
                                            </div>
                                            <div>
                                            </div>
                                        </div>
                                <?php
                                    endwhile;
                                endif;
                                ?>
                                <div class="nav-align-top my-2 p-0">
                                    <ul class="nav nav-pills" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary1" aria-controls="navs-top-itinerary1" aria-selected="true">Route Itinerary 1</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content p-0 mt-3">
                                        <div class="tab-pane fade active show" id="navs-top-itinerary1" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary">
                                                            <div class=" d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
                                                                <h5 class="card-title mb-sm-0 me-2">Route Itinerary 1</h5>
                                                                <h4 class="card-title mb-sm-0 me-2">Overall Trip Cost <b class="text-primary">₹ 1,44,834</b></h4>
                                                                <div class="action-btns">
                                                                    <button class="btn btn-label-github me-3" id="scrollToTopButton">
                                                                        <span class="align-middle"> Back To Top</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $select_previewALL_list = sqlQUERY_LABEL("SELECT `itinerary_all_details_preview_id`, `itinerary_routes_source`, `itinerary_routes_destination`, `overall_trip_cost`, `hotel_id`, `hotspot_id`, `trip_date` FROM `dvi_itinerary_all_details_preview` WHERE `status` = '1' AND `deleted` = '0' AND `itinerary_routes_source` = '$arrival_location' and `trip_date` = '$trip_start_date'") or die("#1-SELECT-ORGANIZATION-DETAILS: UNABLE_TO_COLLECT " . sqlERROR_LABEL());

                                                        $total_previewcount = sqlNUMOFROW_LABEL($select_previewALL_list);

                                                        if ($total_previewcount > 0) :
                                                            while ($get_data = sqlFETCHARRAY_LABEL($select_previewALL_list)) :
                                                                $itinerary_routes_source = $get_data['itinerary_routes_source'];
                                                                $itinerary_routes_destination = $get_data['itinerary_routes_destination'];
                                                                $overall_trip_cost = $get_data['overall_trip_cost'];
                                                                $hotel_id = $get_data['hotel_id'];
                                                                $hotspot_id = $get_data['hotspot_id'];
                                                                $trip_date = $get_data['trip_date'];
                                                                $datestamp = strtotime($trip_date);
                                                                $formattedTRIPDate = date("F j, Y (l)", $datestamp);
                                                        ?>

                                                                <div class="card-body">
                                                                    <!-- Menu Accordion -->
                                                                    <div class="accordion" id="day" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar" style="--bs-accordion-bg: #f8f7fa;">
                                                                        <!-- Day 1 -->
                                                                        <div class="accordion-item border-0 active bg-white rounded-3" id="fl-1">
                                                                            <div class="accordion-header itinerary-sticky-title p-0 mb-3" id="dayOne">
                                                                                <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#day1" aria-expanded="true">
                                                                                    <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar-wrapper">
                                                                                                <div class="avatar me-2">
                                                                                                    <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                                </div>
                                                                                            </div>
                                                                                            <span class="d-flex">
                                                                                                <h6 class="mb-0"><?= $formattedTRIPDate; ?></h6>
                                                                                            </span>
                                                                                        </div>

                                                                                        <div class="" id="itinerary_customized_cost">
                                                                                            <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                                            <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i>₹250</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>



                                                                            <div id="day1" class="accordion-collapse collapse show">
                                                                                <div class="accordion-body pt-1 pb-0">
                                                                                    <div id="itinerary_hotspot_list_day1">
                                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                                            <h5 class="text-uppercase mb-0">Itinerary</h5>
                                                                                        </div>
                                                                                        <ul class="timeline pt-3 px-3 mb-0">
                                                                                            <li class="timeline-item timeline-item-transparent">
                                                                                                <span class="timeline-indicator-advanced timeline-indicator-success">
                                                                                                    <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                                                </span>
                                                                                                <div class="timeline-event">
                                                                                                    <div class="timeline-header">
                                                                                                        <h6 class="mb-0"><?= getHOTEL_DETAIL($hotel_id, '', 'label') ?></h6>
                                                                                                    </div>
                                                                                                    <p class="mb-0">Depart from stay</p>
                                                                                                </div>
                                                                                            </li>

                                                                                            <?php
                                                                                            $select_previewALL_list = sqlQUERY_LABEL("SELECT `hotspot_place_id`, `hotspot_place_title`, `hotspot_place_description`, `hotspot_place_location`, `hotspot_place_start_time`, `hotspot_place_end_time`, `hotspot_cost`, `hotspot_rating`, `hotspot_gallery_name` FROM `dvi_hotspot_place` WHERE `status` = '1' AND `deleted` = '0'") or die("#1-SELECT-ORGANIZATION-DETAILS: UNABLE_TO_COLLECT " . sqlERROR_LABEL());

                                                                                            $total_previewcount = sqlNUMOFROW_LABEL($select_previewALL_list);

                                                                                            if ($total_previewcount > 0) :
                                                                                                while ($get_data = sqlFETCHARRAY_LABEL($select_previewALL_list)) :
                                                                                                    $hotspot_place_id = $get_data['hotspot_place_id'];
                                                                                                    $hotspot_place_title = $get_data['hotspot_place_title'];
                                                                                                    $hotspot_place_description = $get_data['hotspot_place_description'];
                                                                                                    $hotspot_place_location = $get_data['hotspot_place_location'];
                                                                                                    $hotspot_place_start_time = $get_data['hotspot_place_start_time'];
                                                                                                    $hotspot_place_end_time = $get_data['hotspot_place_end_time'];
                                                                                                    $hotspot_cost = $get_data['hotspot_cost'];
                                                                                                    $hotspot_gallery_name = $get_data['hotspot_gallery_name'];
                                                                                                    $hotspot_rating = $get_data['hotspot_rating'];
                                                                                            ?>

                                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                                        </span>
                                                                                                        <div class="timeline-event pb-3">
                                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                                <!-- <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" /> -->
                                                                                                                <div class="w-100">
                                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                                        <h6 class="mb-0 text-capitalize text-primary"><?= $hotspot_place_title; ?></h6>
                                                                                                                        <h6 class="text-primary mb-0"><?php $rating = $hotspot_rating; // You should fetch this value from the database

                                                                                                                                                        // Loop to display filled stars based on the rating
                                                                                                                                                        for ($i = 1; $i <= $rating; $i++) {
                                                                                                                                                            if ($i <= $rating) {
                                                                                                                                                                echo '<i class="ti ti-star-filled"></i>';
                                                                                                                                                            } else {
                                                                                                                                                                echo '<i class="ti ti-star"></i>';
                                                                                                                                                            }
                                                                                                                                                        } ?></h6>
                                                                                                                    </div>
                                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i><?= $hotspot_place_location; ?></p>
                                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i><?= $hotspot_place_start_time; ?> - <?= $hotspot_place_end_time; ?></p>
                                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i><?= $hotspot_cost; ?></p>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                                <?= $hotspot_place_description; ?>
                                                                                                            </p>
                                                                                                        </div>
                                                                                                    </li>

                                                                                                <?php
                                                                                                endwhile;
                                                                                                ?>
                                                                                                <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                                                    <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                                                        <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                                                    </span>
                                                                                                    <div class="timeline-event">
                                                                                                        <div class="timeline-header">
                                                                                                            <h6 class="mb-0"><?= getHOTEL_DETAIL($hotel_id, '', 'label'); ?></h6>
                                                                                                        </div>
                                                                                                        <p class="mb-0">Relax at stay</p>
                                                                                                    </div>
                                                                                                </li><?php
                                                                                                    endif; ?>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            endwhile;
                                                        endif; ?>

                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <div class="card p-3">
                                                                    <h5 class="card-header p-0 mb-2">Vehicle Details</h5>
                                                                    <div class="order-calculations">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Indigo</span>
                                                                            <h6 class="mb-0">₹1,730</h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Indigo</span>
                                                                            <h6 class="mb-0">₹1,030</h6>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading fw-bold">Total Cost</span>
                                                                            <h6 class="mb-0 fw-bold">₹2,760</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card p-3">
                                                                    <h5 class="card-header p-0 mb-2">Overall Cost</h5>
                                                                    <div class="order-calculations">
                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Gross Total for The Package</span>
                                                                            <h6 class="mb-0">₹1,37,304</h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">Agent Commission Cost</span>
                                                                            <h6 class="mb-0">₹665</h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">GST @ 5 % On The total Package </span>
                                                                            <h6 class="mb-0">₹6,865</h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading fw-bold">Net Payable To Doview Holidays India Pvt ltd</span>
                                                                            <h6 class="mb-0 fw-bold">₹1,44,834</h6>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex justify-content-center">
                                                            <div class="demo-inline-spacing">
                                                                <button type="button" class="btn rounded-pill btn-google-plus waves-effect waves-light">
                                                                    <i class="tf-icons ti ti-mail ti-xs me-1"></i> Share Via Email
                                                                </button>
                                                                <button type="button" class="btn rounded-pill btn-success waves-effect waves-light">
                                                                    <i class="tf-icons ti ti-brand-whatsapp ti-xs me-1"></i> Share Via Whatsapp
                                                                </button>
                                                                <button type="button" class="btn rounded-pill btn-label-linkedin waves-effect">
                                                                    <i class="tf-icons ti ti-share ti-xs me-1"></i> Share
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

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
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>

    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <!-- Vendors JS -->
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/vendor/js/bootstrap.min.js"></script>
    <script src="assets/vendor/js/popper.min.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/app-logistics-fleet.js"></script>
    <script src="assets/js/app-chat.js"></script>
    <script src="assets/js/forms-tagify.js"></script>

    <!-- Phone Swiper -->
    <script src="assets/vendor/libs/sortablejs/sortable.js"></script>
    <script src="assets/vendor/libs/swiper/swiper.js"></script>
    <!-- Phone Swiper -->

    <!-- Sticky -->
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="assets/js/form-layouts.js"></script>

    <script src="assets/vendor/js/poppers.min.js"></script>
    <script src="assets/vendor/js/bootstrap.min.js"></script>
    <!-- Sticky -->

    <script src="assets/js/ui-carousel.js"></script>
    <script src="assets/js/extended-ui-drag-and-drop.js"></script>

    <script>
        $(document).ready(function() {
            <?php if (($_GET['route'] == '')) : ?>
                // Your code specific to this condition
                showITINERARY_LIST();

            <?php endif; ?>
            <?php if (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>

                // Your code specific to this condition
                show_ADD_ITENARY_STEP1('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');

            <?php endif; ?>

            <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_list') : ?>
                // Your code specific to this condition
                show_ADD_ITENARY_STEP2('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
                $(".form-select").selectize();
            <?php endif; ?>

            <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_daywise') : ?>
                showITINERARYDAYWISEPLAN('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
                $(".form-select").selectize();

                window.addEventListener('scroll', function() {
                    const sections = document.querySelectorAll('.accordion-item');

                    sections.forEach(section => {
                        const title = section.querySelector('.accordion-header');
                        const rect = section.getBoundingClientRect();

                        if (rect.top <= 69) {
                            title.classList.add('sticky');
                        } else {
                            title.classList.remove('sticky');
                        }
                    });

                    const sections_hotel = document.querySelectorAll('.hotel_content');

                    sections_hotel.forEach(section_hotel => {
                        const title_hotel = section_hotel.querySelector('.hotel_header');
                        const rect_hotel = section_hotel.getBoundingClientRect();

                        if (rect_hotel.top <= 69) {
                            title_hotel.style.backgroundColor = '#ffffff';
                            title_hotel.style.border = '2px solid #1ab7ea';
                            title_hotel.style.borderRadius = '10px';
                            title_hotel.classList.add('sticky');
                        } else {
                            title_hotel.style.backgroundColor = 'transparent';
                            title_hotel.style.border = 'none';
                            title_hotel.style.borderRadius = '0px';
                            title_hotel.classList.remove('sticky');
                        }
                    });
                });

                // To hide loaded Map 
                const targetElement_map = document.getElementById('itinerary_map_div');
                targetElement_map.classList.add('d-none');

                document.getElementById('scrollToTopButton').addEventListener('click', function() {
                    // Scroll to the top of the page
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    }); // Use smooth scrolling for a smooth transition
                });


                "use strict";
                !(function() {
                    var e = document.querySelector("#swiper-default"),
                        i = document.querySelector("#swiper-with-arrows-itinerary"),
                        j = document.querySelector("#swiper-with-arrows-activity"),
                        h = document.querySelector("#swiper-with-arrows-hotel"),
                        k1 = document.querySelector("#swiper-with-arrows-vehicle1"),
                        k2 = document.querySelector("#swiper-with-arrows-vehicle2");
                    let u;
                    e && new Swiper(e, {
                            slidesPerView: "auto"
                        }),
                        i &&
                        new Swiper(i, {
                            slidesPerView: 3,
                            navigation: {
                                prevEl: ".swiper-button-prev",
                                nextEl: ".swiper-button-next",
                            },
                        }),
                        j &&
                        new Swiper(j, {
                            slidesPerView: 3,
                            navigation: {
                                prevEl: ".swiper-button-prev",
                                nextEl: ".swiper-button-next",
                            },
                        }),
                        h &&
                        new Swiper(h, {
                            slidesPerView: 3,
                            navigation: {
                                prevEl: ".swiper-button-prev",
                                nextEl: ".swiper-button-next",
                            },
                        }),
                        k1 &&
                        new Swiper(k1, {
                            slidesPerView: "auto",
                            navigation: {
                                prevEl: ".swiper-button-prev",
                                nextEl: ".swiper-button-next",
                            },
                        }),
                        k2 &&
                        new Swiper(k2, {
                            slidesPerView: "auto",
                            navigation: {
                                prevEl: ".swiper-button-prev",
                                nextEl: ".swiper-button-next",
                            },
                        });
                })();

                // Get references to the checkbox and div element
                const checkbox_itinerary_addguide = document.getElementById("itinerary_addguide");
                const div_itinerary_guide_form = document.getElementById("itinerary_guide_form");

                // Add an event listener to the checkbox
                checkbox_itinerary_addguide.addEventListener("click", function() {
                    // If the checkbox is checked, display the div; otherwise, hide it
                    if (checkbox_itinerary_addguide.checked) {
                        div_itinerary_guide_form.classList.remove('d-none');
                    } else {
                        div_itinerary_guide_form.classList.add('d-none');
                    }
                });

                //Toggle switch
                const switchElement = document.getElementById('switch_map');
                const targetElement = document.getElementById('itinerary_map_div');

                switchElement.addEventListener('change', function() {
                    if (this.checked) {
                        // If the switch is turned on, add the "d-block" class
                        targetElement.classList.remove('d-none');
                    } else {
                        // If the switch is turned off, remove the "d-block" class
                        targetElement.classList.add('d-none');
                    }
                });

            <?php endif; ?>
            // loader
            // var myDiv = document.getElementById("se-pre-con");
            // var myDiv1 = document.getElementById("container_div");

            // show = function(){
            //     myDiv1.style.display = "none";
            //     myDiv.style.display = "block";
            //     setTimeout(hide, 5000); // 5 seconds
            // },

            // hide = function(){
            //     myDiv.style.display = "none";
            //     myDiv1.style.display = "block";
            // };

            // show();

        });

        <?php if ($_GET['route'] == '') : ?>

            function showITINERARY_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_itinerary_list.php?type=show_form',
                    success: function(response) {
                        $('#showITINERARYLIST').html(response);
                    }
                });
            }
        <?php endif; ?>

        <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'basic_info') : ?>

            function show_ADD_ITENARY_STEP1(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_add_itinerary_form.php?type=basic_info',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        // $('#showHOTELLIST').html('');
                        // $('#add_hotel').hide();
                        $('#showITINERARYFORMSTEP1').html(response);
                    }
                });
            }


        <?php endif; ?>

        <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_list') : ?>

            function show_ADD_ITENARY_STEP2(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_add_itinerary_form.php?type=itinerary_list',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        // $('#showHOTELLIST').html('');
                        // $('#add_hotel').hide();
                        $('#showITINERARYFORMSTEP1').html('');
                        $('#showITINERARYFORMSTEP2').html(response);
                    }
                });
            }

            function addItinerarySubmit() {
                $("#add_more_itinerary").html('<span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span><div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0"><div class="d-flex justify-content-between"><div class="text-start"><h6 class="mb-1 text-capitalize">Chennai (City)</h6><div class="mb-1 row"><small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small><div class="col-auto px-0"><div class="input-group input_group_plus_minus input_itinerary_list"><input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity"><input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field"><input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity"></div></div></div></div><div><button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect mx-1"><i class="tf-icons ti ti-edit text-primary"></i></button><button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect mx-1"><i class="tf-icons ti ti-trash-filled text-danger"></i></button><button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button></div></div></div>');
            }

            function show_ADD_ITENARY_STEP2(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_add_itinerary_form.php?type=itinerary_list',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        // $('#showHOTELLIST').html('');
                        // $('#add_hotel').hide();
                        $('#showITINERARYFORMSTEP1').html('');
                        $('#showITINERARYFORMSTEP2').html(response);
                    }
                });
            }
        <?php endif; ?>

        <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_daywise') : ?>

            function showITINERARYDAYWISEPLAN(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_show_itinerary_daywise_plan.php?type=show_daywise_plan',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        // $('#showHOTELLIST').html('');
                        // $('#add_hotel').hide();
                        $('#showITINERARYDAYWISEPLAN').html(response);
                    }
                });
            }


            function itinerary_hotel_edit_rowwise() {
                $("#hotel_name_edit").html('<select id="itinerary_hotel_name" name="itinerary_hotel_name" required class="form-select mb-0 px-2"><option value="">Search Hotel</option><option value="1">	Zion by the Park</option><option value="2">	Lemon Tree</option></select>');
                $("#hotel_room_edit").html('<select id="itinerary_hotel_room" name="itinerary_hotel_room" required class="form-select px-2"><option value="">Select Room</option><option value="1">Premium</option><option value="2">Standard</option><option value="3">Executive</option><option value="4">Superior</option><option value="5">Mejestic</option><option value="6">Deluxe</option></select>');
                $("#hotel_meal_edit").html('<div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1"><label class="form-check-label" for="inlineCheckbox1">Breakfast</label></div><div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2"><label class="form-check-label" for="inlineCheckbox2">Lunch</label></div><div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3"><label class="form-check-label" for="inlineCheckbox3">Dinner</label></div>');
                $("#hotel_rowwise_submit").html('<button type="button" class="btn  btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise_submit()"><i class="tf-icons ti ti-check"></i></button>');
            }


            function itinerary_hotel_edit_rowwise_submit() {
                $("#hotel_name_edit").html('<span class="fw-medium">Zion by the Park</span>');
                $("#hotel_room_edit").html('Standard');
                $("#hotel_meal_edit").html('Breakfast<br/> Lunch<br/> Dinner');
                $("#hotel_rowwise_submit").html('<button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()">Submit</button>');
                $("#hotel_rowwise_submit").html('<button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()"><span class="ti ti-edit"></span></button>');
            }


            function itinerary_hotel_edit_rowwise_customize() {
                $("#hotel_name_edit_customize").html('<select id="itinerary_hotel_name" name="itinerary_hotel_name" required class="form-select mb-0 px-2"><option value="">Search Hotel</option><option value="1">	Zion by the Park</option><option value="2">	Lemon Tree</option></select>');
                $("#hotel_room_edit_customize").html('<select id="itinerary_hotel_room" name="itinerary_hotel_room" required class="form-select px-2"><option value="">Select Room</option><option value="1">Premium</option><option value="2">Standard</option><option value="3">Executive</option><option value="4">Superior</option><option value="5">Mejestic</option><option value="6">Deluxe</option></select>');
                $("#hotel_meal_edit_customize").html('<div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1"><label class="form-check-label" for="inlineCheckbox1">Breakfast</label></div><div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2"><label class="form-check-label" for="inlineCheckbox2">Lunch</label></div><div class="form-check mt-2"><input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3"><label class="form-check-label" for="inlineCheckbox3">Dinner</label></div>');
                $("#hotel_rowwise_submit_customize").html('<button type="button" class="btn  btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise_submit_customize()"><i class="tf-icons ti ti-check"></i></button>');
            }


            function itinerary_hotel_edit_rowwise_submit_customize() {
                $("#hotel_name_edit_customize").html('<span class="fw-medium">Zion by the Park</span>');
                $("#hotel_room_edit_customize").html('Standard');
                $("#hotel_meal_edit_customize").html('Breakfast<br/> Lunch<br/> Dinner');
                $("#hotel_rowwise_submit_customize").html('<button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()">Submit</button>');
                $("#hotel_rowwise_submit").html('<button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise_customize()"><span class="ti ti-edit"></span></button>');
            }

            function edit_itinerary_daywise_click() {
                const myDiv1 = document.getElementById('itinerary_hotspot_list_day1');
                const myDiv2 = document.getElementById('edit_itinerary_daywise_div');
                const myDiv3 = document.getElementById('itinerary_customized_cost');

                myDiv1.classList.add('d-none');
                myDiv2.classList.remove('d-none');
                myDiv3.classList.remove('d-none');
            }

            function edit_back_itinerary_daywise_click() {
                const myDiv1 = document.getElementById('itinerary_hotspot_list_day1');
                const myDiv2 = document.getElementById('edit_itinerary_daywise_div');
                const myDiv3 = document.getElementById('itinerary_customized_cost');

                myDiv1.classList.remove('d-none');
                myDiv2.classList.add('d-none');
                myDiv3.classList.add('d-none');
            }

            function submit_itinerary_btn_click() {
                const myDiv1 = document.getElementById('edit_itinerary_hotspot');
                const myDiv2 = document.getElementById('itinerary_hotspot_list');
                const myDiv3 = document.getElementById('edit_itinerary_btn_click');

                myDiv3.classList.add('d-block');
                myDiv1.classList.add('d-none');
                myDiv2.classList.add('d-block');
            }

            function edit_itinerary_hotel_customize() {
                const myDiv1 = document.getElementById('customize_hotel_btn');
                const myDiv2 = document.getElementById('customize_back_hotel_btn');
                const myDiv3 = document.getElementById('hotel_preview_table_div');
                const myDiv4 = document.getElementById('hotel_customization_div');

                myDiv1.classList.add('d-none');
                myDiv2.classList.remove('d-none');
                myDiv3.classList.add('d-none');
                myDiv4.classList.remove('d-none');
            }

            function back_itinerary_hotel_customize() {
                const myDiv1 = document.getElementById('customize_hotel_btn');
                const myDiv2 = document.getElementById('customize_back_hotel_btn');
                const myDiv3 = document.getElementById('hotel_preview_table_div');
                const myDiv4 = document.getElementById('hotel_customization_div');

                myDiv1.classList.remove('d-none');
                myDiv2.classList.add('d-none');
                myDiv3.classList.remove('d-none');
                myDiv4.classList.add('d-none');
            }

            // Function to check the checkbox when the div is clicked
            function checkCheckboxHotelTableRow(rowNumber) {
                const checkbox = document.getElementById(`checkbox_${rowNumber}`);
                checkbox.checked = !checkbox.checked;
            }
        <?php endif; ?>

        <?php if ($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview') : ?>
            // To hide loaded Map 
            const targetElement_map = document.getElementById('itinerary_map_div');
            targetElement_map.classList.add('d-none');

            document.getElementById('scrollToTopButton').addEventListener('click', function() {
                // Scroll to the top of the page
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                }); // Use smooth scrolling for a smooth transition
            });
        <?php endif; ?>
    </script>
</body>

</html>

<!-- beautify ignore:end -->