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
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .tooltipSection {
            position: relative;
            margin-right: 40px;
            cursor: pointer;
        }

        .tooltipPriceSectionText,
        .tooltipHotelSectionText {
            background-color: #4b4b4b;
            color: #fff;
            position: absolute;
            bottom: 130%;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            opacity: 0;
            transition: all .5s;
        }

        .tooltipAction {
            color: #fff
        }

        .tooltipPriceSectionText::after,
        .tooltipHotelSectionText::after {
            content: '';
            border-width: 5px;
            border-style: solid;
            border-color: #4b4b4b transparent transparent transparent;
            position: absolute;
            left: 50%;
            bottom: -10px;
        }

        .tooltipSection:hover .tooltipPriceSectionText,
        .tooltipSection:hover .tooltipHotelSectionText {
            opacity: 1;
            transform: translateY(30px);
        }

        /* .priceSection:hover i, .hotelSection:hover i {
            background-color: #25d366;
        } */

        .hotelIcon {
            color: #7367f0;
        }

        .hotel-list-nav .nav-link .badge {
            /* background-color: #f2f2f3 !important;
            color: #a8aaae !important; */
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

        #recommendedHotelSelectionPlanBody tr,
        #recommendedHotelSelectionPlanBody td,
        #fourStarHotelSelectionPlanBody tr,
        #fourStarHotelSelectionPlanBody td,
        #threeStarHotelSelectionPlanBody tr,
        #threeStarHotelSelectionPlanBody td {
            cursor: pointer !important;
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

        .sticky-accordion-element {
            position: sticky;
            top: 154px;
            z-index: 999;
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
                        <!-- HOTSPOT ADD INFO START -->
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-header">Tour Itinerary Plan</b></h5>
                                    <a href="?route=add&formtype=itinerary_routes&id=<?= $itinerary_plan_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back
                                        to Route List</a>
                                </div>
                                <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                                    <div>
                                        <h5 class="text-capitalize"> Itinerary for
                                            <b>March 01, 2024</b> to
                                            <b>March 05, 2024</b> (<b>4</b> Nights,
                                            <b>5</b> Days)
                                        </h5>
                                        <h4 class="text-capitalize">Madurai, Tamil Nadu, India <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i>Tirunelveli, Tamil Nadu, India</h4>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="mb-0 me-4 fs-6 text-gray">Adults<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">1</span></span>
                                                <span class="mb-0 me-4 fs-6 text-gray">Children<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">2</span></span>
                                                <span class="mb-0 me-4 fs-6 text-gray">Infants<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">3</span></span>
                                            </div>
                                            <h5 class="mb-0 fs-6 text-gray">Budget</span><span class="badge bg-white text-gray fs-5 fw-semi-bold ms-2">₹ 1000.00</span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="nav-align-top my-2 p-0">
                                    <div class="tab-content p-0 mt-3">
                                        <div class="tab-pane fade active show" id="navs-top-itinerary<?= $tab_content_route_count; ?>" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary">
                                                            <div class=" d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">

                                                                <h3 class="card-title mb-sm-0 me-2 fs-4">Overall Trip Cost : <b class="text-primary"><span id="overall_trip_cost"> ₹ 1,75,000.00</span></b>
                                                                </h3>
                                                                <input type="hidden" id="hotspot_amount" name="hotspot_amount" />
                                                                <div class="action-btns d-flex align-items-center gap-2">
                                                                    <div class="day_wise_guide_avilability_">
                                                                        <a href="javascript:void(0)" class="btn btn-label-github btn-sm" id="add_guide_modal_" onclick="showAddGuideModaloverall();">
                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                                        </a>
                                                                    </div>
                                                                    <div class="action-btns">
                                                                        <a class="btn btn-outline-dribbble btn-sm" id="scrollToTopButton">
                                                                            <span class="align-middle"> <i class="ti ti-arrow-up"></i> Back To Top</span>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="card-body mt-3">

                                                            <div class="col-12" id="itinerary-guidecontainer-overall" style="display: none;">
                                                                <div class="itineray-guide-container d-flex justify-content-between align-items-center py-2 px-4">
                                                                    <div>
                                                                        <div class="my-2">
                                                                            <h6 class="m-0" style="color:#4d287b;">Guide Language - <span class="text-primary">English, Tamil</span></h6>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex">
                                                                        <div>
                                                                            <h5 class="text-primary m-0">₹ 450.00</h5>
                                                                        </div>
                                                                        <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Menu Accordion -->
                                                            <div id="accordionIcon" class="accordion mt-3 accordion-without-arrow">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header text-body d-flex justify-content-between mb-3" id="accordionIconOne">
                                                                        <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0 <?= $collapsed_active_accordion; ?>" data-bs-toggle="collapse" data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                                                            <div class="d-flex justify-content-between align-items-center w-100  itinerary_daywise_list_tab bg-white">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="avatar-wrapper">
                                                                                        <div class="avatar me-2">
                                                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <span class="d-flex align-items-cemter mt-1">
                                                                                        <h6 class="mb-0"> <b>DAY 1</b> - March 01, 2024 (Thursday) |
                                                                                        </h6>

                                                                                        <h6 class="m-0 px-2 text-truncate">Madurai</h6>

                                                                                        <div><i class="ti ti-arrow-big-right-lines-filled"></i></div>

                                                                                        <div class="bg-primary btn-sm text-white px-2 py-1 fs-6 mx-3 rounded-1" data-toggle="tooltip" placement="top" title="Sivakasi, Kovilpati"><i class="ti ti-route ti-tada-hover me-1" style="font-size: 18px;"></i>Via Route</div>

                                                                                        <div><i class="ti ti-arrow-big-right-lines-filled"></i></div>

                                                                                        <h6 class="m-0 px-2 text-truncate">Tirunelveli</h6>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center">
                                                                                    <h6 class="m-0 text-blue-color">07.00 AM</h6>
                                                                                    <i class="ti ti-arrows-diff text-blue-color mx-2"></i>
                                                                                    <h6 class="m-0 text-blue-color">09.00 PM</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </h2>

                                                                    <div id="accordionIcon-1" class="accordion-collapse collapse" data-bs-parent="#accordionIcon">
                                                                        <div class="accordion-body">
                                                                            <div class="row">
                                                                                <div class="col-10">
                                                                                    <div class="d-flex align-items-center ">
                                                                                        <span class="d-flex align-items-center">
                                                                                            <div class="form-group">
                                                                                                <input type="text" readonly="" class="form-control-plaintext text-primary fw-bolder w-px-75 text-center" id="hotspot_start_time_1" name="hotspot_start_time_1" value="07:00 AM">
                                                                                            </div>
                                                                                            <div class="px-2">
                                                                                                <i class="ti ti-arrows-diff"></i>
                                                                                            </div>

                                                                                            <div class="form-group">
                                                                                                <input class="form-control w-px-100 text-center flatpickr-input" type="text" placeholder="hh:mm" id="hotspot_end_time_1" name="hotspot_end_time_1" required="" value="" readonly="readonly">
                                                                                            </div>
                                                                                        </span>
                                                                                        <p class="mb-0 mt-2">
                                                                                            <i class="ti ti-info-circle-filled mb-1 ms-3 me-1"></i><span class="text-warning">Before 6 AM</span> and <span class="text-warning">after 8 PM</span>, extra charges for vehicle and driver are applicable.
                                                                                        </p>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="col-2 d-flex justify-content-end">
                                                                                    <div class="day_wise_guide_avilability_">
                                                                                        <a href="javascript:void(0)" class="btn btn-label-github btn-sm" id="add_guide_modal_" onclick="showAddGuideModal();">
                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 mt-3" id="itinerary-guidecontainer" style="display: none;">
                                                                                    <div class="itineray-guide-container d-flex justify-content-between align-items-center py-2 px-4">
                                                                                        <div>
                                                                                            <div class="my-2">
                                                                                                <h6 class="m-0" style="color:#4d287b;">Guide Language - <span class="text-primary">English, Tamil</span></h6>
                                                                                            </div>
                                                                                            <div class="my-2">
                                                                                                <h6 class="m-0" style="color:#4d287b;">Slot Timing - <span class="text-primary">Slot 1: 8 AM to 1 PM, Slot 3: 6 PM to 9 PM </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex">
                                                                                            <div>
                                                                                                <h5 class="text-primary m-0">₹ 450.00</h5>
                                                                                            </div>
                                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <ul class="timeline pt-3 px-3 mb-0 mt-3">
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-bed text-body ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div>
                                                                                                <h6 class="m-0">Refresh / Relief Period</h6>
                                                                                                <p class="mb-0 mt-1">
                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                    7:00 AM - 8:00 PM
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="p-4 rounded-3" style="background-color: #f0e0f8;">
                                                                                            <div class="row">
                                                                                                <div class="col-md-9">
                                                                                                    <h5 class="mb-0">Thirumalai Nayakkar Palace</h5>
                                                                                                    <p class="mt-2" style="text-align: justify;">
                                                                                                        Built in 1636, as a focal point of his capital at Madurai, Thirumalai Nayak intended the palace to be one of the grandest in South India.The Interior of the palace surpasses many of its Indian contemporaries in scale. The interior is richly decorated whilst the exterior is treated in a more austere style.</p>
                                                                                                </div>
                                                                                                <div class="col-md-3 d-flex justify-content-end position-relative">
                                                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                    </div>
                                                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                    </div>
                                                                                                    <img src="assets/img/thirumalai_nayak.jpg" class="rounded-3" width="200px" height="130px" />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center gap-3">
                                                                                                <p class="mt-2 mb-0">
                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                    8:30 AM - 9:30 AM
                                                                                                </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                    ₹ 15 </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-route me-1 ti-sm mb-1"></i>
                                                                                                    Distance 8 KM (Travelling)</p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                    30 Min (This may vary due to traffic conditions)</p>
                                                                                            </div>
                                                                                            <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-bell text-danger ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div class="px-4 py-2 w-50" style="background-color: #dc3545 !important; border-radius:3px;">
                                                                                                <h6 class="m-0 text-white">You have deviated from our suggestion and implement your approch.</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="p-4 rounded-3" style="background-color: #f0e0f8;">
                                                                                            <div class="row">
                                                                                                <div class="col-md-9">
                                                                                                    <h5 class="mb-2">Meenakshi Amman Temple</h5>
                                                                                                    <p>
                                                                                                        The Meenakshi Temple
                                                                                                        complex is literally a city - one of the largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. </p>
                                                                                                </div>
                                                                                                <div class="col-md-3 d-flex justify-content-end position-relative">
                                                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                    </div>
                                                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                    </div>
                                                                                                    <img src="assets/img/hotspot.jpeg" class="rounded-3" width="200px" height="130px" />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center gap-3">
                                                                                                <p class="mt-2 mb-0">
                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                    10:00 AM - 12:00 PM
                                                                                                </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                    No Fare </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-route me-1 ti-sm mb-1"></i>
                                                                                                    Distance 12 KM (Travelling)</p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                    30 Min (This may vary due to traffic conditions)</p>
                                                                                            </div>
                                                                                            <div class="d-flex justify-content-between mt-2">
                                                                                                <div>
                                                                                                    <button type="button" id="addactivitybtn" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-primary">
                                                                                                        <span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Activity
                                                                                                    </button>
                                                                                                    <button type="button" id="closeactivitybtn" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-danger d-none">
                                                                                                        <span class="tf-icons ti ti-circle-minus ti-xs me-1"></span> close Activity
                                                                                                    </button>
                                                                                                </div>
                                                                                                <i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i>
                                                                                            </div>

                                                                                            <div class="row mt-2" id="addactivitycard" style="display: none; ">
                                                                                                <div class="col-md-12 col-sm-12" style="display: none;">
                                                                                                    <div class="card overflow-hidden" style="height: 450px;">
                                                                                                        <div class="card-body ps ps--active-y" id="vertical-example">
                                                                                                            <div class="row">
                                                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                    <div class="card w-100">
                                                                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                            <div class="p-2 position-relative">
                                                                                                                                <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                                </div>
                                                                                                                                <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                                </div>
                                                                                                                                <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="130" width="100%">
                                                                                                                            </div>
                                                                                                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                                                                                                    <h6 class="custom-option-title mb-0 text-start">Special Dharisanam</h6>
                                                                                                                                    <div data-toggle="tooltip" placement="top" title="The Meenakshi Temple complex is literally a city - one of the
                                                                                                                                     largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each
                                                                                                                                      dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m.">
                                                                                                                                        <i class="ti ti-info-circle"></i>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        11:00 AM - 11:30 AM
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-ticket me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        ₹ 100
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>

                                                                                                                        <button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                            <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                        </button>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                    <div class="card w-100">
                                                                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                            <div class="p-2">
                                                                                                                                <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="130" width="100%">
                                                                                                                            </div>
                                                                                                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                                                                                                    <h6 class="custom-option-title mb-0 text-start">Annathanam</h6>
                                                                                                                                    <div data-toggle="tooltip" placement="top" title="The Meenakshi Temple complex is literally a city - one of the largest 
                                                                                                                                    of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and
                                                                                                                                     victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. "><i class="ti ti-info-circle"></i></div>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        11:30 AM - 12:00 PM
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-ticket me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        ₹ 200
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>

                                                                                                                        <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="add_ITINEARY_ROUTE_HOTSPOT('9.915217404911747', '78.12426820401019',27, 288, 25, 3)">
                                                                                                                            <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                        </button>

                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                    <div class="card w-100">
                                                                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                            <div class="p-2 position-relative">
                                                                                                                                <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                                </div>
                                                                                                                                <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                                </div>
                                                                                                                                <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="130" width="100%">
                                                                                                                            </div>
                                                                                                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                                                                                                    <h6 class="custom-option-title mb-0 text-start">Special Dharisanam</h6>
                                                                                                                                    <div data-toggle="tooltip" placement="top" title="The Meenakshi Temple complex is literally a city - one of the
                                                                                                                                     largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each
                                                                                                                                      dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m.">
                                                                                                                                        <i class="ti ti-info-circle"></i>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        11:00 AM - 11:30 AM
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-ticket me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        ₹ 100
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>

                                                                                                                        <button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                            <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                        </button>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                    <div class="card w-100">
                                                                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                            <div class="p-2">
                                                                                                                                <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="130" width="100%">
                                                                                                                            </div>
                                                                                                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                                                                                                    <h6 class="custom-option-title mb-0 text-start">Annathanam</h6>
                                                                                                                                    <div data-toggle="tooltip" placement="top" title="The Meenakshi Temple complex is literally a city - one of the largest 
                                                                                                                                    of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and
                                                                                                                                     victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. "><i class="ti ti-info-circle"></i></div>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        11:30 AM - 12:00 PM
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-ticket me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        ₹ 200
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>

                                                                                                                        <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="add_ITINEARY_ROUTE_HOTSPOT('9.915217404911747', '78.12426820401019',27, 288, 25, 3)">
                                                                                                                            <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                        </button>

                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                    <div class="card w-100">
                                                                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                            <div class="p-2 position-relative">
                                                                                                                                <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                                </div>
                                                                                                                                <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                                    <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                                </div>
                                                                                                                                <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="130" width="100%">
                                                                                                                            </div>
                                                                                                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                                                                                                    <h6 class="custom-option-title mb-0 text-start">Special Dharisanam</h6>
                                                                                                                                    <div data-toggle="tooltip" placement="top" title="The Meenakshi Temple complex is literally a city - one of the
                                                                                                                                     largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each
                                                                                                                                      dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m.">
                                                                                                                                        <i class="ti ti-info-circle"></i>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        11:00 AM - 11:30 AM
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-ticket me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        ₹ 100
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>

                                                                                                                        <button type="button" class="btn btn-success waves-effect waves-light btn-sm hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                            <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                        </button>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                    <div class="card w-100">
                                                                                                                        <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                            <div class="p-2">
                                                                                                                                <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="130" width="100%">
                                                                                                                            </div>
                                                                                                                            <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                                <div class="my-2 d-flex justify-content-between align-items-center">
                                                                                                                                    <h6 class="custom-option-title mb-0 text-start">Annathanam</h6>
                                                                                                                                    <div data-toggle="tooltip" placement="top" title="The Meenakshi Temple complex is literally a city - one of the largest 
                                                                                                                                    of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and
                                                                                                                                     victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. "><i class="ti ti-info-circle"></i></div>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        11:30 AM - 12:00 PM
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                                <div class="d-flex">
                                                                                                                                    <i class="ti ti-ticket me-1 mb-1"></i>
                                                                                                                                    <p class="mb-0">
                                                                                                                                        ₹ 200
                                                                                                                                    </p>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </label>

                                                                                                                        <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="add_ITINEARY_ROUTE_HOTSPOT('9.915217404911747', '78.12426820401019',27, 288, 25, 3)">
                                                                                                                            <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                        </button>

                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="col-md-12 col-sm-12">
                                                                                                    <div class="card overflow-hidden align-items-center d-flex" style="height: 300px; border:2px solid darkgrey;">
                                                                                                        <img src="assets/img/activity_not_found.jpg" width="200px" height="200px" />
                                                                                                        <h4 class="text-primary">Activity Not found !!!</h4>
                                                                                                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="ps-5 mt-4 border-top">
                                                                                                <h5 class="mt-4">Activity</h5>
                                                                                                <ul class="timeline pt-3">
                                                                                                    <li class="timeline-item pb-4" style="border-left:1px dashed">
                                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                            <i class="ti ti-send rounded-circle text-primary"></i>
                                                                                                        </span>
                                                                                                        <div class="card p-4" style="box-shadow: none !important;">
                                                                                                            <div class="row">
                                                                                                                <div class="col-10">
                                                                                                                    <h5 class="mb-2">Special Dharisanam</h5>
                                                                                                                    <p>
                                                                                                                        The Meenakshi Temple complex is literally a city - one of the largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. </p>
                                                                                                                </div>
                                                                                                                <div class="col-2 position-relative">
                                                                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images">
                                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                    </div>
                                                                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                    </div>
                                                                                                                    <img src="assets/img/hotspot.jpeg" class="rounded-3" width="140px" height="100px" />
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                                <div class="d-flex align-items-center gap-3">
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        11:00 AM - 11:30 AM
                                                                                                                    </p>
                                                                                                                    <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                                        ₹ 100 </p>
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-hourglass-high mb-1"></i>
                                                                                                                        30 Min
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                                <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                    <li class="timeline-item pb-4" style="border-left:1px dashed">
                                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                            <i class="ti ti-send rounded-circle text-primary"></i>
                                                                                                        </span>
                                                                                                        <div class="card p-4" style="box-shadow: none !important;">
                                                                                                            <div class="row">
                                                                                                                <div class="col-10">
                                                                                                                    <h5 class="mb-2">Annathanam</h5>
                                                                                                                    <p>
                                                                                                                        The Meenakshi Temple complex is literally a city - one of the largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. </p>
                                                                                                                </div>
                                                                                                                <div class="col-2 position-relative">
                                                                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images">
                                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                    </div>
                                                                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                    </div>
                                                                                                                    <img src="assets/img/hotspot.jpeg" class="rounded-3" width="140px" height="100px" />
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                                <div class="d-flex align-items-center gap-3">
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        11:30 AM - 12:00 PM
                                                                                                                    </p>
                                                                                                                    <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                                        ₹ 200 </p>
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-hourglass-high mb-1"></i>
                                                                                                                        30 MIN
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                                <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                                            <div class="row">
                                                                                                <div class="col-1 pe-0">
                                                                                                    <div class="avatar me-3">
                                                                                                        <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-car ti-sm text-blue-color"></i></span>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-9 ps-0">
                                                                                                    <div class="mt-1">
                                                                                                        <h6 class="m-0">Travel to tirunelveli.</h6>
                                                                                                        <div class="d-flex gap-3">
                                                                                                            <p class="mt-1 mb-0">
                                                                                                                <i class="ti ti-route me-1 mb-1"></i>
                                                                                                                160 KM
                                                                                                            </p>
                                                                                                            <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                                2 hrs (This may vary due to traffic conditions)</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-2 d-flex align-items-center justify-content-end">
                                                                                                    <i class="ti ti-trash ti-tada-hover text-danger cursor-pointer ms-2"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="px-3 py-2 rounded-3 bg-label-warning" style="border-radius:3px;">
                                                                                            <div class="row">
                                                                                                <div class="col-1 pe-0">
                                                                                                    <div class="avatar me-3">
                                                                                                        <span class="avatar-initial rounded-circle bg-white"><i class="ti ti-building-skyscraper text-warning"></i></span>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-9 ps-0">
                                                                                                    <div class="mt-1">
                                                                                                        <h6 class="m-0">Hotel Madurai Residency</h6>
                                                                                                        <p class="mt-1 mb-0 text-dark">
                                                                                                            <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                            8:00 PM
                                                                                                        </p>
                                                                                                        <p class="mt-1 mb-0 text-dark"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i>
                                                                                                            15, W Marret St, near Periyar, Bus Stand, Madurai Main, Madurai, Tamil Nadu 625001</p>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-2 d-flex align-items-center justify-content-end">
                                                                                                    <div class="position-relative">
                                                                                                        <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images">
                                                                                                            <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                        </div>
                                                                                                        <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                            <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                        </div>
                                                                                                        <img src="assets/img/itinerary/hotels/parkhotel.jpg" class="rounded-3 me-2" width="140px" height="100px" />
                                                                                                    </div>
                                                                                                    <i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center" id="addhotspot">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-primary"><i class="ti ti-plus text-primary ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div class="m-0 text-primary fs-6 cursor-pointer" id="toggleContainer">Click to Add Hotspot </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center d-none" id="closehotspot">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-minus text-danger ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div class="m-0 text-danger fs-6 cursor-pointer" id="toggleContainer">Click to Close Hotspot </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4" id="add_hotspot_itinerary" style="display:none;">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 col-sm-12" style="display:none;">
                                                                                                <div class="card overflow-hidden" style="height: 450px; border:2px solid darkgrey;">
                                                                                                    <!-- <h5 class="card-header">Add Hotspot</h5> -->
                                                                                                    <div class="card-body ps ps--active-y" id="vertical-example">
                                                                                                        <div class="row">
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2 position-relative">
                                                                                                                            <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                                                <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                            </div>
                                                                                                                            <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                                <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                            </div>
                                                                                                                            <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Meenakshi Amman Temple</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                                <!-- <p class="text-start mt-2"></p> -->
                                                                                                                            </div>

                                                                                                                            <div class="d-flex">
                                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                <p class="mb-0">
                                                                                                                                    9:00 AM - 8:00 PM
                                                                                                                                </p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" style="cursor: not-allowed !important;pointer-events: auto;" class="btn btn-warning waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_26" disabled="">
                                                                                                                        <span class=" ti ti-ban ti-xs me-1"></span> Visit Again
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/thirumalai_nayak.jpg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Thirumalai Nayakkar Mahal</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Palace Rd, Mahal Area, Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                            </div>
                                                                                                                            <div class="d-flex">
                                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                <p class="mb-0">
                                                                                                                                    9:00 AM - 1:00 PM, 1:30 PM - 5:00 PM, 6:00 PM - 8:30 PM
                                                                                                                                </p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="add_ITINEARY_ROUTE_HOTSPOT('9.915217404911747', '78.12426820401019',27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                    </button>
                                                                                                                    <button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/thirumalai_nayak.jpg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Thirumalai Nayakkar Mahal</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Palace Rd, Mahal Area, Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                            </div>
                                                                                                                            <div class="d-flex">
                                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                <p class="mb-0">
                                                                                                                                    9:00 AM - 1:00 PM, 1:30 PM - 5:00 PM, 6:00 PM - 8:30 PM
                                                                                                                                </p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="showalertitineraryModal();">
                                                                                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                    </button>
                                                                                                                    <button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2 position-relative">
                                                                                                                            <div class="itinerary-addimage-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Images" onclick="showimageitineraryModal();">
                                                                                                                                <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" />
                                                                                                                            </div>
                                                                                                                            <div class="itinerary-addvideo-icon cursor-pointer" data-toggle="tooltip" placement="top" title="Click to View the Video">
                                                                                                                                <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg" />
                                                                                                                            </div>
                                                                                                                            <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Meenakshi Amman Temple</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                                <!-- <p class="text-start mt-2"></p> -->
                                                                                                                            </div>

                                                                                                                            <div class="d-flex">
                                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                <p class="mb-0">
                                                                                                                                    9:00 AM - 8:00 PM
                                                                                                                                </p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" style="cursor: not-allowed !important;pointer-events: auto;" class="btn btn-warning waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_26" disabled="">
                                                                                                                        <span class=" ti ti-ban ti-xs me-1"></span> Visit Again
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/thirumalai_nayak.jpg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Thirumalai Nayakkar Mahal</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Palace Rd, Mahal Area, Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                            </div>
                                                                                                                            <div class="d-flex">
                                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                <p class="mb-0">
                                                                                                                                    9:00 AM - 1:00 PM, 1:30 PM - 5:00 PM, 6:00 PM - 8:30 PM
                                                                                                                                </p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="add_ITINEARY_ROUTE_HOTSPOT('9.915217404911747', '78.12426820401019',27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                    </button>
                                                                                                                    <button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/thirumalai_nayak.jpg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Thirumalai Nayakkar Mahal</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Palace Rd, Mahal Area, Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                            </div>
                                                                                                                            <div class="d-flex">
                                                                                                                                <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                                <p class="mb-0">
                                                                                                                                    9:00 AM - 1:00 PM, 1:30 PM - 5:00 PM, 6:00 PM - 8:30 PM
                                                                                                                                </p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="showalertitineraryModal();">
                                                                                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                    </button>
                                                                                                                    <button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-12 col-sm-12">
                                                                                                <div class="card overflow-hidden align-items-center d-flex" style="height: 300px; border:2px solid darkgrey;">
                                                                                                    <img src="assets/img/hotspot_not_found.jpg" width="200px" height="200px" />
                                                                                                    <h4 class="text-primary">Hotspot Not Found !!!</h4>
                                                                                                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="px-3 py-2 rounded-3 bg-label-blue" style="border-radius:3px;">
                                                                                            <div class="row">
                                                                                                <div class="col-1 pe-0">
                                                                                                    <div class="avatar me-3">
                                                                                                        <span class="avatar-initial rounded-circle bg-white px-1"><img src="assets/img/svg/travel.svg" /></span>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-9 ps-0">
                                                                                                    <div class="mt-1">
                                                                                                        <h6 class="m-0 text-dark">Travel to Destination (Tirunelveli).</h6>
                                                                                                        <div class="d-flex gap-3">
                                                                                                            <p class="mt-1 mb-0">
                                                                                                                <i class="ti ti-route me-1 mb-1"></i>
                                                                                                                200 KM
                                                                                                            </p>
                                                                                                            <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                                4 hrs (This may vary due to traffic conditions)</p>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-2 d-flex align-items-center justify-content-end">
                                                                                                    <i class="ti ti-trash ti-tada-hover text-danger cursor-pointer ms-2"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div>
                                                                                                <h6 class="m-0">Hotel Madurai Residency</h6>
                                                                                                <div class="d-flex align-items-center gap-3 mt-1">
                                                                                                    <p class="mb-0">
                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                        8:00 PM
                                                                                                    </p>
                                                                                                    <p class="m-0"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i>15, W Marret St, near Periyar, Bus Stand, Madurai Main, Madurai, Tamil Nadu 625001</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header text-body d-flex justify-content-between mb-3" id="accordionIconTwo">
                                                                        <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0 <?= $collapsed_active_accordion; ?>" data-bs-toggle="collapse" data-bs-target="#accordionIcon-2" aria-controls="accordionIcon-2">
                                                                            <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="avatar-wrapper">
                                                                                        <div class="avatar me-2">
                                                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <span class="d-flex align-items-cemter mt-1">
                                                                                        <h6 class="mb-0"> <b>DAY 2</b> - March 02, 2024 (Wednesday) |
                                                                                        </h6>

                                                                                        <h6 class="m-0 px-2 text-truncate">Madurai</h6>

                                                                                        <div><i class="ti ti-arrow-big-right-lines-filled"></i></div>

                                                                                        <div class="bg-primary btn-sm text-white px-2 py-1 fs-6 mx-3 rounded-1" data-toggle="tooltip" placement="top" title="Sivakasi, Kovilpati"><i class="ti ti-route ti-tada-hover me-1" style="font-size: 18px;"></i>Via Route</div>

                                                                                        <div><i class="ti ti-arrow-big-right-lines-filled"></i></div>

                                                                                        <h6 class="m-0 px-2 text-truncate">Tirunelvelli</h6>
                                                                                    </span>
                                                                                </div>
                                                                                <div class="d-flex align-items-center">
                                                                                    <h6 class="m-0 text-blue-color">07.00 AM</h6>
                                                                                    <i class="ti ti-arrows-diff text-blue-color mx-2"></i>
                                                                                    <h6 class="m-0 text-blue-color">09.00 PM</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </h2>

                                                                    <div id="accordionIcon-2" class="accordion-collapse collapse" data-bs-parent="#accordionIcon">
                                                                        <div class="accordion-body">
                                                                            <div class="row">
                                                                                <div class="col-10">
                                                                                    <div class="d-flex align-items-center ">
                                                                                        <span class="d-flex align-items-center">
                                                                                            <div class="form-group">
                                                                                                <input type="text" readonly="" class="form-control-plaintext text-primary fw-bolder w-px-75 text-center" id="hotspot_start_time_1" name="hotspot_start_time_1" value="12:00 PM">
                                                                                            </div>
                                                                                            <div class="px-2">
                                                                                                <i class="ti ti-arrows-diff"></i>
                                                                                            </div>

                                                                                            <div class="form-group">
                                                                                                <input class="form-control w-px-100 text-center flatpickr-input" type="text" placeholder="hh:mm" id="hotspot_end_time_1" name="hotspot_end_time_1" required="" value="" readonly="readonly">
                                                                                            </div>
                                                                                        </span>
                                                                                        <p class="mb-0 mt-2">
                                                                                            <i class="ti ti-info-circle-filled mb-1 ms-3 me-1"></i><span class="text-warning">Before 6 AM</span> and <span class="text-warning">after 8 PM</span>, extra charges for vehicle and driver are applicable.
                                                                                        </p>
                                                                                    </div>

                                                                                </div>
                                                                                <div class="col-2 d-flex justify-content-end">
                                                                                    <div class="day_wise_guide_avilability_">
                                                                                        <a href="javascript:void(0)" class="btn btn-label-github" id="add_guide_modal_" onclick="showAddGuideModal();">
                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span> Add Guide
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 mt-3" id="itinerary-guidecontainer" style="display: none;">
                                                                                    <div class="itineray-guide-container d-flex justify-content-between align-items-center py-2 px-4">
                                                                                        <div>
                                                                                            <div class="my-2">
                                                                                                <h6 class="m-0" style="color:#4d287b;">Guide Language - <span class="text-primary">English, Tamil</span></h6>
                                                                                            </div>
                                                                                            <div class="my-2">
                                                                                                <h6 class="m-0" style="color:#4d287b;">Slot Timing - <span class="text-primary">Slot 1: 8 AM to 1 PM, Slot 3: 6 PM to 9 PM </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex">
                                                                                            <div>
                                                                                                <h5 class="text-primary m-0">₹ 450.00</h5>
                                                                                            </div>
                                                                                            <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <ul class="timeline pt-3 px-3 mb-0 mt-3">
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-bed text-body ti-sm"></i></span>
                                                                                            </div>
                                                                                            <h6 class="m-0">Refresh / Relief Period</h6>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="p-4 rounded-3" style="background-color: #f0e0f8;">
                                                                                            <div class="row">
                                                                                                <div class="col-md-9">
                                                                                                    <h5 class="mb-0">Thirumalai Nayakkar Palace</h5>
                                                                                                    <p class="mt-2" style="text-align: justify;">
                                                                                                        Built in 1636, as a focal point of his capital at Madurai, Thirumalai Nayak intended the palace to be one of the grandest in South India.The Interior of the palace surpasses many of its Indian contemporaries in scale. The interior is richly decorated whilst the exterior is treated in a more austere style.</p>
                                                                                                </div>
                                                                                                <div class="col-md-3 d-flex justify-content-end">
                                                                                                    <img src="assets/img/thirumalai_nayak.jpg" class="rounded-3" width="200px" height="130px" />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center gap-3">
                                                                                                <p class="mt-2 mb-0">
                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                    9:00 AM - 8:00 PM
                                                                                                </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                    No Fare </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-route me-1 ti-sm mb-1"></i>
                                                                                                    Distance 0.9 KM (Travelling)</p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                    1 Min (This may vary due to traffic conditions)</p>
                                                                                            </div>
                                                                                            <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-bell text-danger ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div class="px-4 py-2 w-50" style="background-color: #dc3545 !important; border-radius:3px;">
                                                                                                <h6 class="m-0 text-white">You have deviated from our suggestion and implement your approch.</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="p-4 rounded-3" style="background-color: #f0e0f8;">
                                                                                            <div class="row">
                                                                                                <div class="col-md-9">
                                                                                                    <h5 class="mb-2">Meenakshi Amman Temple</h5>
                                                                                                    <p>
                                                                                                        The Meenakshi Temple complex is literally a city - one of the largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. </p>
                                                                                                </div>
                                                                                                <div class="col-md-3 d-flex justify-content-end">
                                                                                                    <img src="assets/img/hotspot.jpeg" class="rounded-3" width="200px" height="130px" />
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center gap-3">
                                                                                                <p class="mt-2 mb-0">
                                                                                                    <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                    9:00 AM - 8:00 PM
                                                                                                </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                    No Fare </p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-route me-1 ti-sm mb-1"></i>
                                                                                                    Distance 0.9 KM (Travelling)</p>
                                                                                                <p class="mt-2 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>
                                                                                                    1 Min (This may vary due to traffic conditions)</p>
                                                                                            </div>
                                                                                            <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                            <div class="ps-5 mt-4 border-top">
                                                                                                <h5 class="mt-4">Activity</h5>
                                                                                                <ul class="timeline pt-3">
                                                                                                    <li class="timeline-item pb-4" style="border-left:1px dashed">
                                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                            <i class="ti ti-send rounded-circle text-primary"></i>
                                                                                                        </span>
                                                                                                        <div class="card p-4" style="box-shadow: none !important;">
                                                                                                            <h5 class="mb-2">Special Dharisanam</h5>
                                                                                                            <p>
                                                                                                                The Meenakshi Temple complex is literally a city - one of the largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. </p>
                                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                                <div class="d-flex align-items-center gap-3">
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        9:00 AM - 8:00 PM
                                                                                                                    </p>
                                                                                                                    <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                                        ₹ 250 </p>
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-hourglass-high mb-1"></i>
                                                                                                                        1 Hours
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                                <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                    <li class="timeline-item pb-4" style="border-left:1px dashed">
                                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                                            <i class="ti ti-send rounded-circle text-primary"></i>
                                                                                                        </span>
                                                                                                        <div class="card p-4" style="box-shadow: none !important;">
                                                                                                            <h5 class="mb-2">Special Dharisanam</h5>
                                                                                                            <p>
                                                                                                                The Meenakshi Temple complex is literally a city - one of the largest of its kind in India and undoubtedly one of the oldest too. The temple grew with the contribution of each dynasty and victorious monarchs, into an enormous complex extending over an area of 65000 Sq m. </p>
                                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                                <div class="d-flex align-items-center gap-3">
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                                        9:00 AM - 8:00 PM
                                                                                                                    </p>
                                                                                                                    <p class="mt-2 mb-0"><i class="ti ti-ticket me-1 ti-sm mb-1"></i>
                                                                                                                        ₹ 250 </p>
                                                                                                                    <p class="mt-2 mb-0">
                                                                                                                        <i class="ti ti-hourglass-high mb-1"></i>
                                                                                                                        1 Hours
                                                                                                                    </p>
                                                                                                                </div>
                                                                                                                <div class="text-end"><i class="ti ti-trash ti-tada-hover text-danger cursor-pointer"></i></div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-warning"><i class="ti ti-plus text-warning ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div class="m-0 text-warning fs-6 cursor-pointer" id="toggleContainer2">Click to add Hotspot </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4" id="add_hotspot_itinerary2" style="display:none;">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12 col-sm-12">
                                                                                                <div class="card overflow-hidden" style="height: 450px;">
                                                                                                    <!-- <h5 class="card-header">Add Hotspot</h5> -->
                                                                                                    <div class="card-body ps ps--active-y" id="vertical-example">
                                                                                                        <div class="row">
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/hotspot.jpeg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Meenakshi Amman Temple</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                                <!-- <p class="text-start mt-2"></p> -->
                                                                                                                            </div>
                                                                                                                            <p class="my-1 d-flex">
                                                                                                                                <span class="text-start">9:00 AM - 8:00 PM</span>
                                                                                                                            </p>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" style="cursor: not-allowed !important;pointer-events: auto;" class="btn btn-warning waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_26" disabled="">
                                                                                                                        <span class=" ti ti-ban ti-xs me-1"></span> Visit Again
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/thirumalai_nayak.jpg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Thirumalai Nayakkar Mahal</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Palace Rd, Mahal Area, Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                            </div>
                                                                                                                            <p class="my-1 d-flex">
                                                                                                                                <span class="text-start">9:00 AM - 1:00 PM, 1:30 PM - 5:00 PM, 6:00 PM - 8:30 PM</span>
                                                                                                                            </p>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27" onclick="add_ITINEARY_ROUTE_HOTSPOT('9.915217404911747', '78.12426820401019',27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                    </button>
                                                                                                                    <button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3 d-flex">
                                                                                                                <div class="card w-100">
                                                                                                                    <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                                        <div class="p-2">
                                                                                                                            <img src="assets/img/thirumalai_nayak.jpg" class="hotspot_image_container me-3" alt="Hotspot Img" height="180" width="100%">
                                                                                                                        </div>
                                                                                                                        <div class="card-body pt-0 px-3" style="padding-bottom: 60px;">
                                                                                                                            <div class="my-2 d-flex justify-content-between">
                                                                                                                                <h6 class="custom-option-title mb-0 text-start">Thirumalai Nayakkar Mahal</h6>
                                                                                                                                <div data-toggle="tooltip" placement="top" title="Palace Rd, Mahal Area, Madurai Main, Madurai, Tamil Nadu 625001"><i class="ti ti-info-circle"></i></div>
                                                                                                                            </div>
                                                                                                                            <p class="my-1 d-flex">
                                                                                                                                <span class="text-start">9:00 AM - 1:00 PM, 1:30 PM - 5:00 PM, 6:00 PM - 8:30 PM</span>
                                                                                                                            </p>
                                                                                                                        </div>
                                                                                                                    </label>

                                                                                                                    <button type="button" class="btn btn-primary waves-effect waves-light btn-sm hotspot_item_footer" id="add_itinerary_hotspot_27">
                                                                                                                        <span class="ti ti-circle-plus ti-xs me-1"></span> Add
                                                                                                                    </button>
                                                                                                                    <button type="button" class="btn btn-success waves-effect waves-light btn-sm d-none hotspot_item_footer" id="remove_itinerary_hotspot_27" onclick="remove_ITINEARY_ROUTE_HOTSPOT(27, 288, 25, 3)">
                                                                                                                        <span class="ti ti-discount-check-filled ti-xs me-1"></span> Added
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="itineray-daywise-border"></div>
                                                                                    </li>
                                                                                    <li class="mb-4">
                                                                                        <div class="d-flex align-items-center">
                                                                                            <div class="avatar me-3">
                                                                                                <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                                            </div>
                                                                                            <div>
                                                                                                <h6 class="m-0">Hotel Madurai Residency</h6>
                                                                                                <div class="d-flex align-items-center gap-3 mt-1">
                                                                                                    <p class="mb-0">
                                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                                        9:00 AM - 8:00 PM
                                                                                                    </p>
                                                                                                    <p class="m-0"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i>15, W Marret St, near Periyar, Bus Stand, Madurai Main, Madurai, Tamil Nadu 625001</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
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
                        </div>
                        <!-- HOTSPOT ADD INFO START -->


                        <!-- Hotel and Vehicle List START -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-4">
                                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Hotel List</strong></h5>
                                    <div class="card-header pt-2">
                                        <ul class="nav nav-tabs hotel-list-nav card-header-tabs" role="tablist">
                                            <li class="nav-item">
                                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#recommendedHotels" role="tab" aria-selected="true">Recommended Hotel (₹50,000.00 Approx)
                                                    <span class="arrow active"></span>
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link " data-bs-toggle="tab" data-bs-target="#fourStarHotels" role="tab" aria-selected="false">4 Star Hotel (₹45,000.00 Approx)
                                                    <span class="arrow active"></span>
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#threeStarHotels" role="tab" aria-selected="false">3 Star Hotel (₹40,000.00 Approx)
                                                    <span class="arrow active"></span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content px-0">
                                        <div class="tab-pane fade active show" id="recommendedHotels" role="tabpanel">
                                            <div class="text-nowrap mb-3">
                                                <table class="table table-hover border-top-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Day</th>
                                                            <th>Hotel Category</th>
                                                            <th>Hotel Name</th>
                                                            <th>Check In</th>
                                                            <th>Check Out</th>
                                                            <th>Room Type</th>
                                                            <th>Meal</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="border-top-0" id="recommendedHotelSelectionPlanBody">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12">
                                                <div class="">
                                                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Overall Hotel Cost</strong></h5>
                                                    <div class="order-calculations d-flex flex-wrap">
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Room Cost : ₹0.00</b></p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Food Cost : ₹3,800.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Tax : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Hotel Margin (0%) : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Service Tax : ₹0.00</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="col-3 justify-content-end">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading"><strong>Grand Total :</strong></span>
                                                        <h6 class="mb-0"><strong>₹ <span>6,189.00</span></strong></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="fourStarHotels" role="tabpanel">
                                            <div class="table-responsive text-nowrap mb-3">
                                                <table class="table table-hover table-borderless">
                                                    <thead>
                                                        <tr>
                                                            <th>Day</th>
                                                            <th>Hotel Category</th>
                                                            <th>Hotel Name</th>
                                                            <th>Check In</th>
                                                            <th>Check Out</th>
                                                            <th>Room Type</th>
                                                            <th>Meal</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-borderless" id="fourStarHotelSelectionPlanBody">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12">
                                                <div class="">
                                                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Overall Hotel Cost</strong></h5>
                                                    <div class="order-calculations d-flex flex-wrap">
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Room Cost : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Food Cost : ₹3,800.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Tax : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Hotel Margin (0%) : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Service Tax : ₹0.00</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="col-3 justify-content-end">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading"><strong>Grand Total :</strong></span>
                                                        <h6 class="mb-0"><strong>₹ <span>6,189.00</span></strong></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="threeStarHotels" role="tabpanel">
                                            <div class="table-responsive text-nowrap mb-3">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Day</th>
                                                            <th>Hotel Category</th>
                                                            <th>Hotel Name</th>
                                                            <th>Check In</th>
                                                            <th>Check Out</th>
                                                            <th>Room Type</th>
                                                            <th>Meal</th>
                                                            <th>Price</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="" id="threeStarHotelSelectionPlanBody">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-12">
                                                <div class="">
                                                    <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Overall Hotel Cost</strong></h5>
                                                    <div class="order-calculations d-flex flex-wrap">
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Room Cost : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Food Cost : ₹3,800.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Total Tax : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Hotel Margin (0%) : ₹0.00</p>
                                                        </div>
                                                        <div class="col-1">
                                                            <span>+</span>
                                                        </div>
                                                        <div class="col-3">
                                                            <p class="text-heading">Service Tax : ₹0.00</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div class="col-3 justify-content-end">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading"><strong>Grand Total :</strong></span>
                                                        <h6 class="mb-0"><strong>₹ <span>6,189.00</span></strong></h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="card p-4 mt-3">
                                    <h5 class="card-header px-0 mb-2 text-uppercase"><strong>Vehicle List</strong></h5>
                                    <div class="table-responsive text-nowrap mb-3">
                                        <table class="table table-hover">
                                            <thead style="border-style: none !important;">
                                                <tr>
                                                    <th style="width: 100px;">Day's</th>
                                                    <th style="width: 80px;">Vehicle</th>
                                                    <th style="width: 220px;">Travel Place</th>
                                                    <th style="width: 90px;">Traveling KM</th>
                                                    <th style="width: 90px;">Site Seeing KM</th>
                                                    <th style="width: 80px;">Total KM</th>
                                                    <th style="width: 80px;">Total Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-borderless" id="vehiclePlanBody">
                                                <!-- Dynamic rows for vehicle will be added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-12">
                                        <div class="">
                                            <h5 class="card-header p-0 mb-2 text-uppercase"><strong>Overall Vehicle Cost</strong></h5>
                                            <div class="order-calculations d-flex flex-wrap">
                                                <div class="col-3">
                                                    <p class="text-heading">Total Used KM : 1,779 km</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">Total Allowed KM (250 * 6) : 1,500 km</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">Extra KM (20 * 279km) : ₹5,580.00</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">Subtotal Vehicle : ₹50,300.00</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">Total Amount Vehicle : ₹55,880.00</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">GST 9% : ₹5,029.20</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">Total Vendor Margin 5% : ₹3,045.46</p>
                                                </div>
                                                <div class="col-1">
                                                    <span>+</span>
                                                </div>
                                                <div class="col-3">
                                                    <p class="text-heading">Service Tax : ₹00.00</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-12 d-flex justify-content-end">
                                        <div class="col-3 justify-content-end">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-heading"><strong>Grand Total :</strong></span>
                                                <h6 class="mb-0"><strong>₹ <span>63,954.66</span></strong></h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Hotel and Vehicle List END -->

                        <!-- Overall Cost START -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-4">
                                    <!-- <div class="row mt-3 justify-content-center" id="overall_cost_summary"> -->
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="col-md-6">
                                            <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                            <div class="order-calculations">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total for The Hotspot</span>
                                                    <h6 class="mb-0">₹ <span id="gross_total_hotspot_package">0.00</span></h6>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total for The Activity</span>
                                                    <h6 class="mb-0">₹ <span id="gross_total_activity_package">0.00</span></h6>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total for The Hotel</span>
                                                    <h6 class="mb-0">₹ <span id="gross_total_hotel_package">3,800.00</span></h6>
                                                </div>
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Total for The Vehicle</span>
                                                    <h6 class="mb-0">₹ <span id="gross_total_vehicle_package">6,189.00</span></h6>
                                                </div>

                                                <hr>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">Gross Total for The Package</span>
                                                    <h6 class="mb-0">₹ <span id="gross_total_package">2,389.00</span></h6>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading">GST @ 5 % On The total Package
                                                    </span>
                                                    <h6 class="mb-0">₹ <span id="gst_total_package">119.45</span></h6>
                                                </div>

                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-heading fw-bold">Net Payable To Doview
                                                        Holidays India Pvt ltd</span>
                                                    <h6 class="mb-0 fw-bold">₹ <span id="net_total_package">2,508.45</span></h6>
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
                                <!-- </div> -->
                            </div>
                        </div>
                        <!-- Overall Cost END -->
                    </div>
                    <!-- Select guide Modal -->
                    <div class="modal fade" id="additineraryguide" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-simple modal-add-new-address">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="text-center">
                                        <h4 class="mb-2" id="GUIDEFORMLabel">Add Guide</h4>
                                    </div>
                                </div>
                                <form id="additineraryguideForm" class="row g-3">
                                    <div class="col-12 mt-2">
                                        <label class="form-label" for="guide_language">Language<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <select id="guide_language" name="guide_language[]" class="form-control form-select" multiple data-parsley-errors-container="#guide-language-error-container"> <?= getGUIDE_LANGUAGE_DETAILS($guide_language, 'multiselect'); ?></select>
                                        </div>
                                        <div id="guide-language-error-container"></div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <label class="form-label" for="guide_slot">Slot<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <select id="guide_slot" name="guide_slot[]" class="form-control form-select" multiple data-parsley-errors-container="#guide-slot-error-container">
                                                <?= getSLOTTYPE($guide_slot, 'multiselect') ?>
                                            </select>
                                        </div>
                                        <div id="guide-slot-error-container"></div>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--/ Select guide Modal -->

                    <!-- Select hotspot Modal -->
                    <div class="modal fade" id="additineraryhotspot" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-simple modal-add-new-address">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="row">
                                        <div class="text-center">
                                            <svg class="icon-44 text-warning" width="50" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                        <h4 class="text-center mt-3">Hotspot Distance Alert !!!</h4>
                                        <p class="text-start mt-3">Before proceeding, please note that <b>Meenakshi Amman Kovil</b> is nearest to <b>Hotel</b>. <br /><br />Since you have previously added <b>Thirumalai Nayakkar Palace</b> as a hotspot, we recommend visiting <b>Meenakshi Amman Kovil</b> first and then proceeding to <b>Thirumalai Nayakkar Palace</b>.<br /><br /> If you agree with this approach, click 'Proceed'. Otherwise, feel free to decline and choose your own itinerary.</p>
                                        <div class="text-center mt-3 pb-0">
                                            <button type="button" class="btn btn-secondary" onclick="declineHOTSPOTDISTANCEALERT('<?= $PLAN_ID; ?>','<?= $ROUTE_ID; ?>','<?= $PR_HOTSPOT_ID; ?>','<?= $NXT_HOTSPOT_ID; ?>','<?= $dayOfWeekNumeric; ?>')">Decline</button>
                                            <button type="button" class="btn btn-primary" onclick="confirmHOTSPOTDISTANCEALERT('<?= $PLAN_ID; ?>','<?= $ROUTE_ID; ?>','<?= $PR_HOTSPOT_ID; ?>','<?= $NXT_HOTSPOT_ID; ?>','<?= $dayOfWeekNumeric; ?>')">Proceed</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Select hotspot Modal -->
                    </div>
                    <!-- Select hotspot Modal -->

                    <!-- Hotel Details Modal -->
                    <div class="modal fade" id="hotelDetailsModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-refer-and-earn">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <img src="assets/img/itinerary/hotels/parkhotel.jpg" class="img-fluid" alt="Hotel">
                                            <div class="text-center mt-3">
                                                <h3 class="mb-1"><b>The Park Hotel</b></h3>
                                                <p> 4.1 <i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #B2BEB5;font-size: 15px"></i> (1663)</p>
                                            </div>
                                            <div class="mt-1">
                                                <h5 class="text-muted">4 Star Hotel</h5>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <div>
                                                <h5><b>Amenities</b></h5>
                                                <div class="col-12 d-flex flex-wrap">
                                                    <div class="col-6">
                                                        <p><i class="ti ti-swimming me-1"></i>Pool</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p><i class="ti ti-massage me-1"></i>Spa</p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p><i class="ti ti-wifi me-1"></i>Wifi <span class="badge ms-2" style="background-color: #eae8fd !important;color: #7367f0 !important;">Free</span></p>
                                                    </div>
                                                    <div class="col-6">
                                                        <p><i class="ti ti-salad me-1"></i>Breakfast<span class="badge ms-2" style="background-color: #eae8fd !important;color: #7367f0 !important;">Free</span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-3">
                                            <div>
                                                <h5><b>Address & contact information</b></h5>
                                                <div class="col-12 d-flex flex-wrap">
                                                    <p><i class="ti ti-map-pin me-1"></i>601, Anna Salai, near US Embassy, Gangai Karai Puram, Nungambakkam, Chennai, Tamil Nadu 600006</p>
                                                    <p><i class="ti ti-phone me-1"></i>044 4267 6000</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Hotel Details Modal -->

                    <!-- Vehicle Details Modal -->
                    <div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-simple modal-refer-and-earn">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    <div class="row">
                                        <div class="col-12 col-lg-4">
                                            <img src="assets/img/itinerary/vehicle/thar.jpg" class="img-fluid" alt="Hotel">
                                            <div class="text-center mt-3">
                                                <h3 class="mb-1"><b>Thar</b></h3>
                                                <h5 class="mb-1"><b>Chennai Rental Cars</b></h5>
                                                <p> 5 <i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i> (1663)</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-8">
                                            <div>
                                                <h5><b>Amenities</b></h5>
                                                <div class="col-12">
                                                    <p><b>Address:</b> 2nd floor, Cibi Clinic, Leo Muthu Rd, Balaji Nagar, Ekkatuthangal, Chennai, Tamil Nadu 600032</p>
                                                    <p><b>Areas served:</b> Chennai</p>
                                                    <p><b>Hours:</b> Open 24 hours</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ Vehicle Details Modal -->

                    <!-- Image Preview Methods modal -->

                    <div class="modal fade" id="imagePreview" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-simple">
                            <div class="modal-content p-3 p-md-5">
                                <div class="modal-body">
                                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <li data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></li>
                                            <li data-bs-target="#carouselExample" data-bs-slide-to="1"></li>
                                            <li data-bs-target="#carouselExample" data-bs-slide-to="2"></li>
                                        </ol>
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img class="d-block w-100" src="assets/img/itinerary/hotels/hotel-1.jpg" alt="The Hotel" />
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h4>The Park Hotel</h4>
                                                    <p>Hall</p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img class="d-block w-100" src="assets/img/itinerary/hotels/hotel-2.jpg" alt="The Hotel" />
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h4>The Park Hotel</h4>
                                                    <p>Garden</p>
                                                </div>
                                            </div>
                                            <div class="carousel-item">
                                                <img class="d-block w-100" src="assets/img/itinerary/hotels/hotel-3.jpg" alt="The Hotel" />
                                                <div class="carousel-caption d-none d-md-block">
                                                    <h4>The Park Hotel</h4>
                                                    <p>Swimming Pool</p>
                                                </div>
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Image Preview Methods modal -->

                    <!-- / Image Slider modal -->
                    <div class="modal fade" id="additineraryimageslider" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-0">
                                    <div class="text-center mb-2">
                                        <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle">Thirumalai Nayakkar Palace</h5>
                                    </div>
                                    <div id="swiper-gallery">
                                        <div class="swiper gallery-top swiper-initialized swiper-horizontal swiper-backface-hidden">
                                            <div class="swiper-wrapper" id="swiper-wrapper-24a76af782109d3c1" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                                <div class="swiper-slide swiper-slide-active" style="background-image: url(assets/img/auroville_img.jpeg); width: 752px;" role="group" aria-label="1 / 4"></div>
                                                <div class="swiper-slide swiper-slide-next" style="background-image: url(assets/img/hotspot.jpeg); width: 752px;" role="group" aria-label="2 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/thirumalai_nayak.jpg); width: 752px;" role="group" aria-label="3 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/auroville_img_yoga.jpeg); width: 752px;" role="group" aria-label="4 / 4"></div>
                                            </div>
                                            <div class="swiper-button-next swiper-button-white" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-24a76af782109d3c1" aria-disabled="false"></div>
                                            <div class="swiper-button-prev swiper-button-white swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-24a76af782109d3c1" aria-disabled="true"></div>
                                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                        </div>
                                        <div class="swiper gallery-thumbs swiper-initialized swiper-horizontal swiper-free-mode swiper-watch-progress swiper-thumbs swiper-backface-hidden">
                                            <div class="swiper-wrapper" id="swiper-wrapper-3b029644d8466eb5" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                                <div class="swiper-slide swiper-slide-thumb-active swiper-slide-active" style="background-image: url(assets/img/auroville_img.jpeg); width: 180.5px; margin-right: 10px;" role="group" aria-label="1 / 4"></div>
                                                <div class="swiper-slide swiper-slide-next" style="background-image: url(assets/img/hotspot.jpeg); width: 180.5px; margin-right: 10px;" role="group" aria-label="2 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/thirumalai_nayak.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="3 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/auroville_img_yoga.jpeg); width: 180.5px; margin-right: 10px;" role="group" aria-label="4 / 4"></div>
                                            </div>
                                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Image Slider modal -->
                </div>
            </div>
        </div>
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
        <script src="assets/js/selectize/selectize.min.js"></script>
        <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
        <script src="assets/vendor/libs/toastr/toastr.js"></script>
        <script src="assets/vendor/libs/leaflet/leaflet.js"></script>
        <script src="assets/js/maps-leaflet.js"></script>
        <script src="assets/js/footerscript.js"></script>
        <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
        <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
        <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
        <script src="assets/vendor/js/bootstrap.min.js"></script>
        <script src="assets/vendor/js/popper.min.js"></script>
        <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
        <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
        <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
        <script src="assets/js/_js_buttons.html5.min.js"></script>
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
        <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
        <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
        <script src="assets/js/form-layouts.js"></script>
        <!-- Sticky -->
        <script src="assets/js/ui-carousel.js"></script>
        <script src="assets/js/extended-ui-drag-and-drop.js"></script>
        <script src="assets/vendor/libs/select2/select2.js"></script>
        <script src="assets/js/forms-selects.js"></script>
        <script src="assets/js/ui-popover.js"></script>

        <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

        <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <!-- Page JS -->
        <script src="assets/js/extended-ui-perfect-scrollbar.js"></script>

        <script>
            flatpickr("#hotspot_end_time_1", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K", // This format represents hour:minutes AM/PM
            });


            function showAddGuideModal() {
                // Show the modal using Bootstrap's modal method
                $('#additineraryguide').modal('show');

                // When the modal's submit button is clicked
                $('#additineraryguideForm').submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Show the container when the form is submitted
                    $('#itinerary-guidecontainer').show();

                    // Hide the add guide button container
                    $('.day_wise_guide_avilability_').hide();

                    // Close the modal
                    $('#additineraryguide').modal('hide');
                });
            }

            function showAddGuideModaloverall() {
                // Show the modal using Bootstrap's modal method
                $('#additineraryguide').modal('show');

                // When the modal's submit button is clicked
                $('#additineraryguideForm').submit(function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    // Show the container when the form is submitted
                    $('#itinerary-guidecontainer-overall').show();

                    // Hide the add guide button container
                    $('.day_wise_guide_avilability_').hide();

                    // Close the modal
                    $('#additineraryguide').modal('hide');
                });
            }

            function showalertitineraryModal() {
                // Show the modal using Bootstrap's modal method
                $('#additineraryhotspot').modal('show');
            }

            function showimageitineraryModal() {
                // Show the modal using Bootstrap's modal method
                $('#additineraryimageslider').modal('show');
            }

            $(document).ready(function() {

                $(".form-select").selectize();
                $('[data-toggle="tooltip"]').tooltip();
            });

            // START ADD & CLOSE HOTSPOTS
            document.addEventListener("DOMContentLoaded", function() {
                // Add event listener to the "Add Activity" button
                document.getElementById("addactivitybtn").addEventListener("click", function() {
                    // Hide the "Add Activity" button
                    document.getElementById("addactivitybtn").style.display = "none";
                    // Show the "Close Activity" button
                    document.getElementById("closeactivitybtn").classList.remove("d-none");
                    // Show the activity card section
                    document.getElementById("add_hotspot_itinerary").style.display = "block";
                });

                // Add event listener to the "Close Activity" button
                document.getElementById("closeactivitybtn").addEventListener("click", function() {
                    // Hide the "Close Activity" button
                    document.getElementById("closeactivitybtn").classList.add("d-none");
                    // Show the "Add Activity" button
                    document.getElementById("addactivitybtn").style.display = "inline-block";
                    // Hide the activity card section
                    document.getElementById("add_hotspot_itinerary").style.display = "none";
                });

                // Add event listener to the "Add Hotspot" button
                document.getElementById("addhotspot").addEventListener("click", function() {
                    // Show the "Close Hotspot" button
                    document.getElementById("closehotspot").classList.remove("d-none");
                    // Hide the "Add Hotspot" button
                    document.getElementById("addhotspot").classList.add("d-none");
                    // Show the activity card section
                    document.getElementById("add_hotspot_itinerary").style.display = "block";
                });

                // Add event listener to the "Close Hotspot" button
                document.getElementById("closehotspot").addEventListener("click", function() {
                    // Hide the "Close Hotspot" button
                    document.getElementById("closehotspot").classList.add("d-none");
                    // Show the "Add Hotspot" button
                    document.getElementById("addhotspot").classList.remove("d-none");
                    // Hide the activity card section
                    document.getElementById("add_hotspot_itinerary").style.display = "none";
                });
            });
            // END ADD & CLOSE HOTSPOT 

            // START ADD & CLOSE ACTIVITY 
            document.addEventListener("DOMContentLoaded", function() {
                // Add event listener to the "Add Activity" button
                document.getElementById("addactivitybtn").addEventListener("click", function() {
                    // Hide the "Add Activity" button
                    document.getElementById("addactivitybtn").classList.add("d-none");
                    // Show the "Close Activity" button
                    document.getElementById("closeactivitybtn").classList.remove("d-none");
                    // Show the activity card section
                    document.getElementById("addactivitycard").style.display = "block";
                });

                // Add event listener to the "Close Activity" button
                document.getElementById("closeactivitybtn").addEventListener("click", function() {
                    // Hide the "Close Activity" button
                    document.getElementById("closeactivitybtn").classList.add("d-none");
                    // Show the "Add Activity" button
                    document.getElementById("addactivitybtn").classList.remove("d-none");
                    // Hide the activity card section
                    document.getElementById("addactivitycard").style.display = "none";
                });
            });
            // END ADD & CLOSE ACTIVITY 



            // START ITINERARY STEP3

            // RECOMMENDED HOTEL PLAN STARTS

            document.addEventListener("DOMContentLoaded", function() {
                const tbody = document.getElementById('recommendedHotelSelectionPlanBody');
                let openRow = null;

                function createRow(day, hotelCategory, hotelName, checkIn, checkOut, roomType, price) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>Day-${day}</td>
                            <td>
                                <span>${hotelCategory}</span>
                            </td>
                            <td>
                                <span><i class="fa-solid fa-hotel me-1 hotelIcon"></i>${hotelName}</span>
                            </td>                        
                            <td>${checkIn}</td>
                            <td>${checkOut}</td>
                            <td>
                                <span>${roomType}</span>
                            </td>
                            <td>
                                <span>All</span>
                            </td>
                            <td>
                                <span>${price}</span>
                            </td>
                        `;

                    tbody.appendChild(row);

                    const collapseRow = document.createElement('tr');
                    collapseRow.classList.add('collapseRow');
                    collapseRow.innerHTML = `
                                            <td colspan="8" class="p-0">

                                                <div class="collapse">
                                                    <div class="row p-3">
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">
                                                                    <div class="image-overview rounded-top" data-bs-toggle="modal" data-bs-target="#imagePreview">
                                                                        <i class="ti ti-eye"></i>
                                                                        <span>View</span>
                                                                    </div>
                                                                    <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/parkhotel.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />                                                                    
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">The Park Hotel</h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <small style="font-size: 10px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                <small style="font-size: 10px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3 mealSelectionOption">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Choose</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card border-primary">
                                                                <div style="position: relative; display: inline-block;">
                                                                    <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-1.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                    <div class="image-overview">
                                                                        <i class="ti ti-eye"></i>
                                                                        <span>View</span>
                                                                    </div>
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">ITC Grand Chola</h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">5 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                    <small style="font-size: 10px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                <small style="font-size: 10px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3 mealSelectionOption">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-primary w-100">Selected</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">
                                                                    <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-2.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                    <div class="image-overview">
                                                                        <i class="ti ti-eye"></i>
                                                                        <span>View</span>
                                                                    </div>
                                                                </div>                                                             
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">Turyaa Hotel</h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">3 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <small style="font-size: 10px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                <small style="font-size: 10px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3 mealSelectionOption">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Choose</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">
                                                                    <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-3.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                    <div class="image-overview">
                                                                        <i class="ti ti-eye"></i>
                                                                        <span>View</span>
                                                                    </div>
                                                                </div>                                                                 
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">Chennai Le Palace</h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <small style="font-size: 10px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                <small style="font-size: 10px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3 mealSelectionOption">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Choose</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        `;
                    tbody.appendChild(collapseRow);

                    row.addEventListener('click', function() {
                        if (openRow && openRow !== collapseRow) {
                            const openCollapse = openRow.querySelector('.collapse');
                            openCollapse.classList.remove('show');
                        }

                        const collapse = collapseRow.querySelector('.collapse');
                        if (collapse.classList.contains('show')) {
                            collapse.classList.remove('show');
                            openRow = null;
                        } else {
                            collapse.classList.add('show');
                            openRow = collapseRow;

                        }
                    });

                    const cards = collapseRow.querySelectorAll('.col-4');
                    cards.forEach(card => {
                        card.addEventListener('click', function() {
                            row.innerHTML += card.innerHTML;
                        });
                    });
                }
                const numDays = 2;
                const hotelCategories = ['3 Star Hotel', '5 Star Hotel'];
                const hotelNames = ['The Park Hotel', 'ITC Grand Chola'];

                for (let i = 1; i <= numDays; i++) {
                    createRow(
                        i,
                        hotelCategories[i % hotelCategories.length],
                        hotelNames[i % hotelNames.length],
                        '7 Apr 2024<br>Sun, 11:29 am',
                        '7 Apr 2024<br>Sun, 11:29 am',
                        'Classic',
                        '₹31,000'
                    );
                }
            });

            // RECOMMENDED HOTEL PLAN ENDS


            // 4 STAR HOTEL PLAN STARTS

            document.addEventListener("DOMContentLoaded", function() {
                const tbody = document.getElementById('fourStarHotelSelectionPlanBody');

                function createRow(day, hotelCategory, hotelName, checkIn, checkOut, roomType, price) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                                    <td>Day-${day}</td>
                                    <td>
                                        <span>${hotelCategory}</span>
                                    </td>
                                    <td>
                                        <span><i class="fa-solid fa-hotel me-1 hotelIcon"></i>${hotelName}</span>
                                    </td>                        
                                    <td>${checkIn}</td>
                                    <td>${checkOut}</td>
                                    <td>
                                        <span>${roomType}</span>
                                    </td>
                                    <td>
                                        <span>All</span>
                                    </td>
                                    <td>
                                        <span>${price}</span>
                                    </td>
                                `;
                    tbody.appendChild(row);

                    const collapseRow = document.createElement('tr');
                    collapseRow.classList.add('collapseRow');
                    collapseRow.innerHTML = `
                                            <td colspan="8" class="px-3">
                                                <div class="collapse">
                                                    <div class="row my-1">
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card border-primary">
                                                                <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/parkhotel.jpg" style="height: 180px;" alt="Card girl image" />                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">The Park Hotel  <i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: lightgrey; font-size: 12px;"></i></h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <small style="font-size: 9px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                <small style="font-size: 9px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-primary w-100">Selected</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-1.jpg" style="height: 180px;" alt="Card girl image" />                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">ITC Grand Chola  <i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: lightgrey; font-size: 12px;"></i></h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                    <small style="font-size: 9px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                <small style="font-size: 9px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Choose</a>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-2.jpg" style="height: 180px;" alt="Card girl image" />                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">Turyaa Hotel  <i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: lightgrey; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: lightgrey; font-size: 12px;"></i></h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <small style="font-size: 9px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                <small style="font-size: 9px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Choose</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        `;
                    tbody.appendChild(collapseRow);

                    row.addEventListener('click', function() {
                        const collapse = collapseRow.querySelector('.collapse');
                        if (collapse.classList.contains('show')) {
                            collapse.classList.remove('show');
                        } else {
                            collapse.classList.add('show');
                        }
                    });

                    const cards = collapseRow.querySelectorAll('.col-4');
                    cards.forEach(card => {
                        card.addEventListener('click', function() {
                            row.innerHTML += card.innerHTML;
                        });
                    });
                }
                const numDays = 2;
                const hotelCategories = ['4 Star Hotel', '4 Star Hotel'];
                const hotelNames = ['The Park Hotel', 'ITC Grand Chola'];

                for (let i = 1; i <= numDays; i++) {
                    createRow(
                        i,
                        hotelCategories[i % hotelCategories.length],
                        hotelNames[i % hotelNames.length],
                        '7 Apr 2024<br>Sun, 11:29 am',
                        '7 Apr 2024<br>Sun, 11:29 am',
                        'Classic',
                        '₹31,000'
                    );
                }
            });

            // 4 STAR HOTEL PLAN ENDS


            // 3 STAR HOTEL PLAN STARTS

            document.addEventListener("DOMContentLoaded", function() {
                const tbody = document.getElementById('threeStarHotelSelectionPlanBody');

                function createRow(day, hotelCategory, hotelName, checkIn, checkOut, roomType, price) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                                    <td>Day-${day}</td>
                                    <td>
                                        <span>${hotelCategory}</span>
                                    </td>
                                    <td>
                                        <span><i class="fa-solid fa-hotel me-1 hotelIcon"></i>${hotelName}</span>
                                    </td>                        
                                    <td>${checkIn}</td>
                                    <td>${checkOut}</td>
                                    <td>
                                        <span>${roomType}</span>
                                    </td>
                                    <td>
                                        <span>All</span>
                                    </td>
                                    <td>
                                        <span>${price}</span>
                                    </td>
                                `;
                    tbody.appendChild(row);

                    const collapseRow = document.createElement('tr');
                    collapseRow.classList.add('collapseRow');
                    collapseRow.innerHTML = `
                                            <td colspan="8" class="px-3">
                                                <div class="collapse">
                                                    <div class="row my-1">
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card border-primary">
                                                                <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/parkhotel.jpg" style="height: 180px;" alt="Card girl image" />                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">The Park Hotel  <i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: lightgrey; font-size: 12px;"></i></h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">3 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                          

              <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <small style="font-size: 9px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                <small style="font-size: 9px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-primary w-100">Selected</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-1.jpg" style="height: 180px;" alt="Card girl image" />                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h5 class="mb-0">ITC Grand Chola  <i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: #aa008e; font-size: 12px;"></i><i class="ti ti-star-filled" style="color: lightgrey; font-size: 12px;"></i></h5>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">3 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex mb-1 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                    <small style="font-size: 9px;">Check In</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem; height:2rem;">
                                                                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                            </div>
                                                                            <div>
                                                                                <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                <small style="font-size: 9px;">Check Out</small>
                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                        <div class="mb-1">
                                                                            <label for="defaultSelect" class="form-label">Room Type</label>
                                                                            <select id="defaultSelect" class="form-select">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-1 g-3">
                                                                            <small class="text-light fw-medium d-block">Meal</small>
                                                                            <div class="d-flex col-12 flex-wrap">
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                                                    <label class="form-check-label" for="inlineCheckbox1">All</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                                                                    <label class="form-check-label" for="inlineCheckbox2">Breakfast</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox3">Lunch</label>
                                                                                </div>
                                                                                <div class="form-check col-6">
                                                                                    <input class="form-check-input" type="checkbox" id="inlineCheckbox4" value="option3">
                                                                                    <label class="form-check-label" for="inlineCheckbox4">Dinner</label>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100">Choose</a>                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        `;
                    tbody.appendChild(collapseRow);

                    row.addEventListener('click', function() {
                        const collapse = collapseRow.querySelector('.collapse');
                        if (collapse.classList.contains('show')) {
                            collapse.classList.remove('show');
                        } else {
                            collapse.classList.add('show');
                        }
                    });

                    const cards = collapseRow.querySelectorAll('.col-4');
                    cards.forEach(card => {
                        card.addEventListener('click', function() {
                            row.innerHTML += card.innerHTML;
                        });
                    });
                }
                const numDays = 2;
                const hotelCategories = ['3 Star Hotel', '3 Star Hotel'];
                const hotelNames = ['The Park Hotel', 'ITC Grand Chola'];

                for (let i = 1; i <= numDays; i++) {
                    createRow(
                        i,
                        hotelCategories[i % hotelCategories.length],
                        hotelNames[i % hotelNames.length],
                        '7 Apr 2024<br>Sun, 11:29 am',
                        '7 Apr 2024<br>Sun, 11:29 am',
                        'Classic',
                        '₹31,000'
                    );
                }
            });

            // 3 STAR HOTEL PLAN ENDS


            // VEHICLE PLAN STARTS

            document.addEventListener("DOMContentLoaded", function() {
                const vehicleTbody = document.getElementById('vehiclePlanBody');

                function createVehicleRow(day, vehicleName, vendorName, date, travelFromPlace, travelToPlace, travelingKM, siteSeeingKM, totalKM, totalAmount) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                            <td>${date}<br>
                            <span style="font-size: 12px;">Day-${day}</span></td>
                            <td class="tooltipSection vehicleSection">
                                <span class="defaultText" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><i class="fa-solid fa-car me-2" style="color: #7367f0;"></i>${vehicleName}<br>
                                <span style="font-size: 12px;">${vendorName}</span></span>
                            </td>
                            <td class="text-center">${travelFromPlace} <br><i class="ti ti-arrow-big-down-lines m-2" style="color: #aa008e;"></i><br> ${travelToPlace}</td>
                            <td>${travelingKM}</td>
                            <td>${siteSeeingKM}</td>
                            <td>${totalKM}</td>
                            <td>${totalAmount}</td>                            
                            `;
                    vehicleTbody.appendChild(row);
                }

                createVehicleRow('1', 'Thar', 'Owner', '5 Apr 2024', 'Chennai, Tamil Nadu, India', 'Pondicherry, Tamil Nadu, India', '100', '50', '150', '₹10,000');
                createVehicleRow('2', 'Thar', 'Owner', '6 Apr 2024', 'Pondicherry, Tamil Nadu, India', 'Trichy, Tamil Nadu, India', '120', '60', '180', '₹12,000');
                createVehicleRow('3', 'Thar', 'Owner', '7 Apr 2024', 'Trichy, Tamil Nadu, India', 'Dindugul, Tamil Nadu, India', '100', '50', '150', '₹10,000');
                createVehicleRow('4', 'Thar', 'Owner', '8 Apr 2024', 'Dindugul, Tamil Nadu, India', 'Palani, Tamil Nadu, India', '120', '60', '180', '₹12,000');
                createVehicleRow('5', 'Thar', 'Owner', '9 Apr 2024', 'Palani, Tamil Nadu, India', 'Coimbatore, Tamil Nadu, India', '100', '50', '150', '₹10,000');
            });

            // VEHICLE PLAN ENDS

            $(document).ready(function() {
                $('.accordion-collapse').on('show.bs.collapse', function() {
                    $(this).closest('.accordion-item').find('.accordion-header').addClass('sticky-accordion-element');
                });
                $('.accordion-collapse').on('hide.bs.collapse', function() {
                    $(this).closest('.accordion-item').find('.accordion-header').removeClass('sticky-accordion-element');
                });
            });

            // Get the button element
            var scrollToTopButton = document.getElementById("scrollToTopButton");

            // Add click event listener to the button
            scrollToTopButton.addEventListener("click", function() {
                // Scroll the page to the top smoothly
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        </script>
</body>

</html>