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

        /* .tooltipSection {
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
            box-shadow: 0 -2px 0 #7367f0 inset;
            color: #fff;
            background: #7367f0;
        }

        .hotel-list-nav .nav-link:not(.active):hover,
        .hotel-list-nav .nav-link:not(.active):focus,
        .nav-pills .nav-link:not(.active):hover,
        .nav-pills .nav-link:not(.active):focus {
            color: #7367f0 !important;
        }

        #recommendedHotelSelectionPlanBody tr,
        #recommendedHotelSelectionPlanBody td,
        #fourStarHotelSelectionPlanBody tr,
        #fourStarHotelSelectionPlanBody td,
        #threeStarHotelSelectionPlanBody tr,
        #threeStarHotelSelectionPlanBody td {
            cursor: pointer !important;
        } */

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
            padding: 1rem;
            padding-bottom: .3rem;
            padding-top: 2rem;
        }

        .overlay_image_wrapper h6 {
            color: #fff;
        }

        .input-group-room-type input:focus,
        .input-group:focus,
        .input-group-room-type .form-select:focus,
        .input-group-room-type .form-select:focus-visible {
            border: none;
        }

        .input-group-room-type,
        .input-group-room-type input,
        .input-group-room-type .form-select .selectize-input {
            border: none;
            font-size: 13px;
        }

        .input-group-room-type:focus-within {
            box-shadow: none;
        }

        .tooltip-inner {
            max-width: 500px;
            width: 300px;
            background-color: #fff5fd;
            color: #6f6b7d;
            border-radius: 8px;
            padding: 10px;
            border: #6f6b7d 1px solid;
            box-shadow: 0 1px 1px rgba(0,0,0,0.12), 0 2px 2px rgba(0,0,0,0.12);
        }

        .grand_total_section p{
            background-image: linear-gradient(to bottom, rgba(114, 49, 207, 1), rgba(195, 60, 166, 1), rgba(238, 63, 206, 1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
                                <div class="d-flex justify-content-center">
                                    <div class="demo-inline-spacing">
                                        <button type="button" class="btn btn-primary waves-effect waves-light">
                                            <span class="ti-xs ti ti-check me-1"></span>Confirm
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Overall Cost END -->
                    </div>


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
                                                <h6 class="text-muted">4 Star Hotel</h6>
                                                <p> 4.1 <i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #fbbc04;font-size: 15px"></i><i class="ti ti-star-filled me-1" style="color: #B2BEB5;font-size: 15px"></i> (1663)</p>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="card-header">
                                                <ul class="nav nav-tabs nav-pills card-header-pills" role="tablist">
                                                    <li class="nav-item nav-item-modal" role="presentation">
                                                        <button type="button" class="nav-link nav-link-modal active" role="tab" data-bs-toggle="tab" data-bs-target="#amenitiesNavSection" aria-controls="amenitiesNavSection" aria-selected="true">Amenities</button>
                                                        <span class="arrow active"></span>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button type="button" class="nav-link nav-link-modal" role="tab" data-bs-toggle="tab" data-bs-target="#priceNavSection" aria-controls="priceNavSection" aria-selected="false" tabindex="-1">Price</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button type="button" class="nav-link nav-link-modal" role="tab" data-bs-toggle="tab" data-bs-target="#aboutNavSection" aria-controls="aboutNavSection" aria-selected="false" tabindex="-1">About</button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button type="button" class="nav-link nav-link-modal" role="tab" data-bs-toggle="tab" data-bs-target="#addressNavSection" aria-controls="addressNavSection" aria-selected="false" tabindex="-1">Address</button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-body mt-3">
                                                <div class="tab-content p-0">
                                                    <div class="tab-pane fade active show" id="amenitiesNavSection" role="tabpanel">
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
                                                            <div class="col-6">
                                                                <p><i class="ti ti-barbell me-1"></i>Wellness</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p><i class="ti ti-parking me-1"></i>Parking & transport</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p><i class="ti ti-baby-carriage me-1"></i>Children</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p><i class="ti ti-wheelchair me-1"></i>Accessibility</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p><i class="ti ti-paw me-1"></i>Pets Allowed</p>
                                                            </div>
                                                            <div class="col-6">
                                                                <p><i class="ti ti-beer me-1"></i>Bar</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="priceNavSection" role="tabpanel">
                                                        <div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">King</p>
                                                                <p class="mb-0">₹250 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Classic</p>
                                                                <p class="mb-0">₹200 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Suite</p>
                                                                <p class="mb-0">₹300 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Premium King Rooms</p>
                                                                <p class="mb-0">₹250 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Premium Classic</p>
                                                                <p class="mb-0">₹200 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Double Bed</p>
                                                                <p class="mb-0">₹300 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Single Bed</p>
                                                                <p class="mb-0">₹300 per night</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Double Delux</p>
                                                                <p class="mb-0">₹300 per night</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="aboutNavSection" role="tabpanel">
                                                        <div>
                                                            <h5><b>About this hotel</b></h5>
                                                            <p>Set in a striking contemporary building, this high-end hotel is 4 km from Marina Beach, 5 km from the Government Museum and 15 km from Chennai International Airport.</p>
                                                            <p>Polished rooms have Wi-Fi, flat-screen TVs and minibars, as well as tea and coffeemaking facilities. Suites add sitting areas; upgraded suites provide separate living rooms. Room service is available 24/7.</p>
                                                            <p>Amenities include 3 restaurants (1 offering Thai cuisine), a cocktail bar and a nightclub, plus a rooftop outdoor pool with a poolside bar. There's also a spa with a gym, in addition to breakfast.</p>
                                                            <ul>
                                                                <li>Check-in time: 14:00</li>
                                                                <li>Check-out time: 12:00</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="tab-pane fade" id="addressNavSection" role="tabpanel">
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
                            </div>
                        </div>
                    </div>
                    <!--/ Hotel Details Modal -->

                    <!-- Vehicle Details Modal -->
                    <div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-simple modal-refer-and-earn">
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
                                                <h5><b>Details :</b></h5>
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
                    <div class="modal fade" id="imagePreviewOne" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-0">
                                    <div class="text-center mb-2">
                                        <h5 class="modal-title" id="modalCenterTitle">THE PARK HOTEL (4 STAR HOTELS) </h5>
                                        <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle">PREMIUM SUITE - City View</h5>
                                    </div>
                                    <div id="swiper-gallery">
                                        <div class="swiper gallery-top swiper-initialized swiper-horizontal swiper-backface-hidden">
                                            <div class="swiper-wrapper" id="swiper-wrapper-24a76af782109d3c1" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                                <div class="swiper-slide swiper-slide-active" style="background-image: url(assets/img/itinerary/hotels/room-1.jpg); width: 752px;" role="group" aria-label="1 / 4"></div>
                                                <div class="swiper-slide swiper-slide-next" style="background-image: url(assets/img/itinerary/hotels/room-2.jpg); width: 752px;" role="group" aria-label="2 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-3.jpg); width: 752px;" role="group" aria-label="3 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-4.jpg); width: 752px;" role="group" aria-label="4 / 4"></div>
                                            </div>
                                            <div class="swiper-button-next swiper-button-white" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-24a76af782109d3c1" aria-disabled="false"></div>
                                            <div class="swiper-button-prev swiper-button-white swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-24a76af782109d3c1" aria-disabled="true"></div>
                                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                        </div>
                                        <div class="swiper gallery-thumbs swiper-initialized swiper-horizontal swiper-free-mode swiper-watch-progress swiper-thumbs swiper-backface-hidden">
                                            <div class="swiper-wrapper" id="swiper-wrapper-3b029644d8466eb5" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                                <div class="swiper-slide swiper-slide-thumb-active swiper-slide-active" style="background-image: url(assets/img/itinerary/hotels/room-1.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="1 / 4"></div>
                                                <div class="swiper-slide swiper-slide-next" style="background-image: url(assets/img/itinerary/hotels/room-2.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="2 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-3.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="3 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-4.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="4 / 4"></div>
                                            </div>
                                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="imagePreviewTwo" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body pt-0">
                                    <div class="text-center mb-2">
                                        <h5 class="modal-title" id="modalCenterTitle">ITC GRAND CHOLA (5 STAR HOTELS) </h5>
                                        <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle">PREMIUM SUITE - City View</h5>
                                    </div>
                                    <div id="swiper-gallery">
                                        <div class="swiper gallery-top swiper-initialized swiper-horizontal swiper-backface-hidden">
                                            <div class="swiper-wrapper" id="swiper-wrapper-24a76af782109d3c1" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                                <div class="swiper-slide swiper-slide-active" style="background-image: url(assets/img/itinerary/hotels/room-5.jpg); width: 752px;" role="group" aria-label="1 / 4"></div>
                                                <div class="swiper-slide swiper-slide-next" style="background-image: url(assets/img/itinerary/hotels/room-6.jpg); width: 752px;" role="group" aria-label="2 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-7.jpg); width: 752px;" role="group" aria-label="3 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-8.jpg); width: 752px;" role="group" aria-label="4 / 4"></div>
                                            </div>
                                            <div class="swiper-button-next swiper-button-white" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-24a76af782109d3c1" aria-disabled="false"></div>
                                            <div class="swiper-button-prev swiper-button-white swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-24a76af782109d3c1" aria-disabled="true"></div>
                                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                        </div>
                                        <div class="swiper gallery-thumbs swiper-initialized swiper-horizontal swiper-free-mode swiper-watch-progress swiper-thumbs swiper-backface-hidden">
                                            <div class="swiper-wrapper" id="swiper-wrapper-3b029644d8466eb5" aria-live="polite" style="transition-duration: 0ms; transform: translate3d(0px, 0px, 0px);">
                                                <div class="swiper-slide swiper-slide-thumb-active swiper-slide-active" style="background-image: url(assets/img/itinerary/hotels/room-5.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="1 / 4"></div>
                                                <div class="swiper-slide swiper-slide-next" style="background-image: url(assets/img/itinerary/hotels/room-6.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="2 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-7.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="3 / 4"></div>
                                                <div class="swiper-slide" style="background-image: url(assets/img/itinerary/hotels/room-8.jpg); width: 180.5px; margin-right: 10px;" role="group" aria-label="4 / 4"></div>
                                            </div>
                                            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span><span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Image Preview Methods modal -->

                </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {

            $(".form-select").selectize();
            $('[data-toggle="tooltip"]').tooltip();
        });

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
                                <span class="price-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title='<div class="">
                                                            <div class="d-flex align-items-center justify-content-between my-3 flex-wrap">
                                                                <p class="mb-0">Total Room Cost</p>
                                                                <p class="mb-0">₹4,158.42</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Total Food Cost</p>
                                                                <p class="mb-0">₹0.00</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Total Tax</p>
                                                                <p class="mb-0">₹41.58</p>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Hotel Margin (8%)</p>
                                                                <p class="mb-0">₹332.67</p>
                                                            </div> 
                                                            <div class="d-flex align-items-center justify-content-between my-3">
                                                                <p class="mb-0">Service Tax</p>
                                                                <p class="mb-0">₹00.00</p>
                                                            </div> 
                                                            <hr class="my-2">   
                                                            <div class="d-flex align-items-center justify-content-between my-3 grand_total_section">
                                                                <p class="mb-0"><b>Grand Total</b></p>
                                                                <p class="mb-0"><b>₹4,532.67</b></p>
                                                            </div> 
                                                        </div>'>${price}</span>
                            </td>
                        `;

                tbody.appendChild(row);

                const tooltips = row.querySelectorAll('.price-tooltip');
                tooltips.forEach((tooltip) => {
                    new bootstrap.Tooltip(tooltip, {
                        html: true
                    });
                });

                const collapseRow = document.createElement('tr');
                collapseRow.classList.add('collapseRow');
                collapseRow.innerHTML = `
                                            <td colspan="8" class="p-0">
                                                <div class="collapse">
                                                    <div class="row p-3">
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">                                                                      
                                                                    <div class="image_wrapper">
                                                                        <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/parkhotel.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                        <div class="overlay overlay_image_wrapper">
                                                                            <h6 class="mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                            <h6 class="mb-0">The Park Hotel -  ₹26,000</h6>   
                                                                            <h6 class="mb-0" style="font-size: 12px;">17 Nov 23</h6>                                                                           
                                                                        </div>
                                                                    </div> 
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreviewOne">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>    
                                                                    <div class="itinerary-details-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Details" data-bs-original-title="Click to View the Details">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/details.svg" data-bs-toggle="modal" data-bs-target="#hotelDetailsModal">
                                                                    </div>                                                               
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2">                                                                    
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">09:00</h6>
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
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">22:30</h6>
                                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check Out</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3">
                                                                    <div class="input-group input-group-room-type border-0">
                                                                            <input type="text" class="form-control border-0 ps-0" aria-label="Text input with dropdown button" Value="Room Type" readonly>
                                                                            <select id="defaultSelect" class="form-select border-0 pe-0">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3 mealSelectionOption mealSelectionOption1">
                                                                            <small class="fw-medium d-block">Meal</small>
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
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100 mb-2">Choose</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card border-primary">
                                                                <div style="position: relative; display: inline-block;">                                                                      
                                                                    <div class="image_wrapper">
                                                                        <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-1.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                        <div class="overlay overlay_image_wrapper">
                                                                            <h6 class="mb-0" style="font-size: 12px;">5 Star Hotel</h6>
                                                                            <h6 class="mb-0">ITC Grand Chola -  ₹29,000</h6>   
                                                                            <h6 class="mb-0" style="font-size: 12px;">17 Nov 23</h6>                                                                           
                                                                        </div>
                                                                    </div> 
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreviewOne">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>    
                                                                    <div class="itinerary-details-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Details" data-bs-original-title="Click to View the Details">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/details.svg" data-bs-toggle="modal" data-bs-target="#hotelDetailsModal">
                                                                    </div>                                                               
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2">                                                                    
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">09:00</h6>
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
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">22:30</h6>
                                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check Out</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3">
                                                                    <div class="input-group input-group-room-type border-0">
                                                                            <input type="text" class="form-control border-0 ps-0" aria-label="Text input with dropdown button" Value="Room Type" readonly>
                                                                            <select id="defaultSelect" class="form-select border-0 pe-0">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3 mealSelectionOption mealSelectionOption1">
                                                                            <small class="fw-medium d-block">Meal</small>
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
                                                                    <a href="javascript:void(0);" class="btn btn-primary w-100 mb-2">Selected</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">                                                                      
                                                                    <div class="image_wrapper">
                                                                        <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-2.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                        <div class="overlay overlay_image_wrapper">
                                                                            <h6 class="mb-0" style="font-size: 12px;">3 Star Hotel</h6>
                                                                            <h6 class="mb-0">Turyaa Hotel -  ₹24,000</h6>   
                                                                            <h6 class="mb-0" style="font-size: 12px;">17 Nov 23</h6>                                                                           
                                                                        </div>
                                                                    </div> 
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreviewOne">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>    
                                                                    <div class="itinerary-details-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Details" data-bs-original-title="Click to View the Details">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/details.svg" data-bs-toggle="modal" data-bs-target="#hotelDetailsModal">
                                                                    </div>                                                               
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2">                                                                    
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">09:00</h6>
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
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">22:30</h6>
                                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check Out</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3">
                                                                    <div class="input-group input-group-room-type border-0">
                                                                            <input type="text" class="form-control border-0 ps-0" aria-label="Text input with dropdown button" Value="Room Type" readonly>
                                                                            <select id="defaultSelect" class="form-select border-0 pe-0">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3 mealSelectionOption mealSelectionOption1">
                                                                            <small class="fw-medium d-block">Meal</small>
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
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100 mb-2">Choose</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">                                                                      
                                                                    <div class="image_wrapper">
                                                                        <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/hotel-3.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />
                                                                        <div class="overlay overlay_image_wrapper">
                                                                            <h6 class="mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                            <h6 class="mb-0">Chennai Le Palace -  ₹28,500</h6>   
                                                                            <h6 class="mb-0" style="font-size: 12px;">17 Nov 23</h6>                                                                           
                                                                        </div>
                                                                    </div> 
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreviewOne">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>    
                                                                    <div class="itinerary-details-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Details" data-bs-original-title="Click to View the Details">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/details.svg" data-bs-toggle="modal" data-bs-target="#hotelDetailsModal">
                                                                    </div>                                                               
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2">                                                                    
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-clock ti-sm" style="font-size: 1.5rem !important;"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">09:00</h6>
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
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 13px;">22:30</h6>
                                                                                    <h6 class="text-muted mb-0" style="font-size: 10px;">Check Out</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3">
                                                                        <div class="input-group input-group-room-type border-0">
                                                                            <input type="text" class="form-control border-0 ps-0" aria-label="Text input with dropdown button" Value="Room Type" readonly>
                                                                            <select id="defaultSelect" class="form-select border-0 pe-0">
                                                                                <option>Suite</option>
                                                                                <option value="1">King</option>
                                                                                <option value="2">Classic</option>
                                                                                <option value="3">Suite</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 mb-2 g-3 mealSelectionOption mealSelectionOption1">
                                                                            <small class="fw-medium d-block">Meal</small>
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
                                                                    <a href="javascript:void(0);" class="btn btn-outline-primary w-100 mb-2">Choose</a>
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
            const hotelCategories = ['4 Star Hotel', '5 Star Hotel'];
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

            // Checkbox functionality
            document.querySelectorAll('.mealSelectionOption input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.mealSelectionOption');
                    const allCheckbox = parent.querySelector('input[type="checkbox"][id^="inlineCheckbox1"]');

                    if (this.id.startsWith('inlineCheckbox1')) {
                        parent.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                            cb.checked = this.checked;
                        });
                    } else {
                        const allChecked = [...parent.querySelectorAll('input[type="checkbox"]:not(#inlineCheckbox1)')].every(cb => cb.checked);
                        allCheckbox.checked = allChecked;
                    }
                });
            });
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
                    <td colspan="8" class="p-0">
                                                <div class="collapse">
                                                    <div class="row p-3">
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">
                                                                    <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/parkhotel.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />   
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreview">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>                                                                   
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h6 class="mb-0">The Park Hotel - <span style="color: #d700b3;"> ₹26,000</span></h6>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">4 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <h6 style="font-size: 10px;">Check In</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
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
                                                                    <div class="col-12 mb-1 g-3 mealSelectionOption mealSelectionOption1">
                                                                            <small class="fw-medium d-block">Meal</small>
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
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreview">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>                                                                    
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h6 class="mb-0">ITC Grand Chola - <span style="color: #d700b3;"> ₹26,000</span></h6>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">5 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                    <h6 style="font-size: 10px;">Check In</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
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
                                                                    <div class="col-12 mb-1 g-3 mealSelectionOption mealSelectionOption2">
                                                                            <small class="fw-medium d-block">Meal</small>
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
            // Checkbox functionality
            document.querySelectorAll('.mealSelectionOption input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.mealSelectionOption');
                    const allCheckbox = parent.querySelector('input[type="checkbox"][id^="inlineCheckbox1"]');

                    if (this.id.startsWith('inlineCheckbox1')) {
                        parent.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                            cb.checked = this.checked;
                        });
                    } else {
                        const allChecked = [...parent.querySelectorAll('input[type="checkbox"]:not(#inlineCheckbox1)')].every(cb => cb.checked);
                        allCheckbox.checked = allChecked;
                    }
                });
            });
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
                                            <td colspan="8" class="p-0">
                                                <div class="collapse">
                                                    <div class="row p-3">
                                                        <div class="col-3 mb-3 px-2">
                                                            <div class="card">
                                                                <div style="position: relative; display: inline-block;">
                                                                    <img class="img-fluid rounded-top" src="assets/img/itinerary/hotels/parkhotel.jpg" style="height: 180px; width: 100%;" alt="Hotel Image" />   
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreview">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>                                                                   
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h6 class="mb-0">The Park Hotel - <span style="color: #d700b3;">₹23,000</span></h6>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">3 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">17 Nov 23</h6>
                                                                                    <h6 style="font-size: 10px;">Check In</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
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
                                                                    <div class="itinerary-image-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Images" data-bs-original-title="Click to View the Images">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/image.svg" data-bs-toggle="modal" data-bs-target="#imagePreview">
                                                                    </div>
                                                                    <div class="itinerary-video-icon cursor-pointer" data-toggle="tooltip" placement="top" aria-label="Click to View the Video" data-bs-original-title="Click to View the Video">
                                                                        <img class="ms-1 ti-tada-hover" src="assets/img/svg/video-player.svg">
                                                                    </div>                                                                    
                                                                </div>                                                                
                                                                <div class="card-body pt-0 pb-2 mt-1">
                                                                    <h6 class="mb-0">ITC Grand Chola - <span style="color: #d700b3;">₹26,000</span></h6>
                                                                    <div class="col-12 mb-1">
                                                                        <h6 class="text-muted mb-0" style="font-size: 12px;">3 Star Hotel</h6>
                                                                    </div>
                                                                    <div class="col-12 d-flex my-2 g-3">
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                                <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
                                                                                    <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-calendar-event ti-sm"></i></span>
                                                                                </div>
                                                                                <div>
                                                                                    <h6 class="mb-0 text-nowrap" style="font-size: 12px;">7 Apr 2024</h6>
                                                                                    <h6 style="font-size: 10px;">Check In</h6>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="d-flex">
                                                                            <div class="avatar flex-shrink-0 me-2" style="width: 2rem;height: 2rem;">
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

            // Checkbox functionality
            document.querySelectorAll('.mealSelectionOption input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const parent = this.closest('.mealSelectionOption');
                    const allCheckbox = parent.querySelector('input[type="checkbox"][id^="inlineCheckbox1"]');

                    if (this.id.startsWith('inlineCheckbox1')) {
                        parent.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                            cb.checked = this.checked;
                        });
                    } else {
                        const allChecked = [...parent.querySelectorAll('input[type="checkbox"]:not(#inlineCheckbox1)')].every(cb => cb.checked);
                        allCheckbox.checked = allChecked;
                    }
                });
            });
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
                                <span class="defaultText" data-bs-toggle="modal" data-bs-target="#vehicleDetailsModal"><i class="fa-solid fa-car me-2" style="color: #7367f0;"></i>${vehicleName}<br>
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
    </script>

</body>

</html>