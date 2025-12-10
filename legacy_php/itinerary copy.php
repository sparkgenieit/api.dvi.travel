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


                        <span id="showITINERARYLIST"></span>
                        <span id="showITINERARYFORMSTEP1"></span>
                        <?php if (

                            $_GET['route'] == 'add' &&
                            $_GET['formtype'] == 'itinerary_list'
                        ) : ?>

                            <div id="se-pre-con"></div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-header text-capitalize">Trip <b>Chennai</b> to <b>Trivandrum airport Drop</b></h5>
                                        <a href="itinerary.php?route=add" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Edit Plan</a>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-4 col-12 mb-md-0 mb-4">
                                            <div class="form-check custom-option custom-option-icon mt-3">
                                                <label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p>Route 1</p>

                                                                <input class="form-check-input d-none" name="itinerary_list_card" type="radio" value="" id="customRadioIcon1" />
                                                            </div>
                                                            <ul class="list-group list-group-flush timeline ps-3 mt-1 mb-0" id="itinerary_1">
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Chennai</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Mahabalipuram</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Pondicherry</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Tanjore</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trichy</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Madurai</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Rameswaram</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Kanyakumari</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trivandrum</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 border-transparent p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin mt-1"></i>
                                                                    </span>
                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trivandrum airport Drop</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-outline-dribbble waves-effect">
                                                                <span class="ti-xs ti ti-circle-plus me-1"></span>Create Itinerary
                                                            </a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-md-0 mb-4">
                                            <div class="form-check custom-option custom-option-icon mt-3">
                                                <label class="form-check-label custom-option-content p-0" for="customRadioIcon2">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p>Route 2</p>

                                                                <input class="form-check-input d-none" name="itinerary_list_card" type="radio" value="" id="customRadioIcon2" />
                                                            </div>
                                                            <ul class="list-group list-group-flush timeline ps-3 mt-1 mb-0" id="itinerary_2">
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Chennai</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Pondicherry</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="2" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Tanjore</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trichy</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Madurai</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Rameswaram</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Kanyakumari</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trivandrum</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 border-transparent p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin mt-1"></i>
                                                                    </span>
                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trivandrum airport Drop</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-outline-dribbble waves-effect">
                                                                <span class="ti-xs ti ti-circle-plus me-1"></span>Create Itinerary
                                                            </a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-md-0 mb-4">
                                            <div class="form-check custom-option custom-option-icon mt-3">
                                                <label class="form-check-label custom-option-content p-0" for="customRadioIcon3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="d-flex justify-content-between">
                                                                <p>Route 3</p>

                                                                <input class="form-check-input d-none" name="itinerary_list_card" type="radio" value="" id="customRadioIcon3" />
                                                            </div>
                                                            <ul class="list-group list-group-flush timeline ps-3 mt-1 mb-0" id="itinerary_3">
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Chennai</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Tirupathi</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Pondicherry</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="2" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Tanjore</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Madurai</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Rameswaram</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Kanyakumari</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin"></i>
                                                                    </span>

                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trivandrum</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4 border-transparent p-0">
                                                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                                                        <i class="ti ti-map-pin mt-1"></i>
                                                                    </span>
                                                                    <div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0">
                                                                        <div class="d-flex justify-content-between">
                                                                            <div class="text-start">
                                                                                <h6 class="mb-1 text-capitalize">Trivandrum airport Drop</h6>
                                                                                <div class="mb-1 row">
                                                                                    <small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small>
                                                                                    <div class="col-auto px-0">
                                                                                        <div class="input-group input_group_plus_minus input_itinerary_list">
                                                                                            <input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity">
                                                                                            <input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field">
                                                                                            <input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect"><i class="tf-icons ti ti-trash-filled text-danger"></i></button>
                                                                                <button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                            <a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-outline-dribbble waves-effect">
                                                                <span class="ti-xs ti ti-circle-plus me-1"></span>Create Itinerary
                                                            </a>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center my-4">
                                        <a href="itinerary.php?route=add&formtype=itinerary_daywise" class="btn btn-primary waves-effect">
                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Create All Itinerary
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_daywise') : ?>
                            <div id="se-pre-con"></div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-header">Tour Itinerary Plan</b></h5>
                                        <a href="itinerary.php?route=add&formtype=itinerary_list" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Route List</a>
                                    </div>
                                </div>
                                <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                                    <div>
                                        <h5 class="text-capitalize"> Itinerary for <b>October 14, 2023</b> to <b>October 24, 2023</b> (<b>10</b> Day, <b>9</b> Night)</h5>
                                        <h3 class="text-capitalize">Chennai <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i> Trivandrum airport Drop</h3>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="mb-0 me-4"><strong>Adults</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">2</span></span>
                                                <span class="mb-0 me-4"><strong>Children</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">1</span></span>
                                                <span class="mb-0 me-4"><strong>Infants</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">1</span></span>
                                            </div>
                                            <h5 class="mb-0"><strong>Budget</strong></span><span class="badge bg-primary bg-glow ms-2">₹ 55,000</span></h5>
                                        </div>
                                    </div>
                                    <div>
                                    </div>
                                </div>

                                <div class="nav-align-top my-2 p-0">
                                    <ul class="nav nav-pills" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary1" aria-controls="navs-top-itinerary1" aria-selected="true">Route Itinerary 1</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary2" aria-controls="navs-top-itinerary2" aria-selected="false" tabindex="-1">Route Itinerary 2</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary3" aria-controls="navs-top-itinerary3" aria-selected="false" tabindex="-1">Route Itinerary 3</button>
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
                                                                <h4 class="card-title mb-sm-0 me-2">Overall Trip Cost <b class="text-primary">₹ 1,07,957</b></h4>
                                                                <div class="action-btns">
                                                                    <button class="btn btn-label-github me-3" id="scrollToTopButton">
                                                                        <span class="align-middle"> Back To Top</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">

                                                            <div class="text-end">
                                                                <label class="switch switch-square mb-3">
                                                                    <input type="checkbox" class="switch-input" id="switch_map">
                                                                    <span class="switch-toggle-slider">
                                                                        <span class="switch-on"></span>
                                                                        <span class="switch-off"></span>
                                                                    </span>
                                                                    <span class="switch-label">Map</span>
                                                                </label>
                                                            </div>

                                                            <div class="row app-logistics-fleet-wrapper mb-3" id="itinerary_map_div">
                                                                <!-- Map Menu Button when screen is < md -->
                                                                <div class="flex-shrink-0 position-fixed m-4 d-md-none w-auto zindex-1">
                                                                    <button class="btn btn-label-white border border-2 zindex-2 p-2" data-bs-toggle="sidebar" data-overlay="" data-target="#app-logistics-fleet-sidebar"><i class="ti ti-menu-2"></i></button>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <!-- Mapbox Map container -->
                                                                    <div class="col h-100 map-container">
                                                                        <!-- Map -->
                                                                        <div id="map" class="h-100 w-100"></div>
                                                                    </div>
                                                                </div>
                                                            </div>

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
                                                                                        <h6 class="mb-0">October 14, 2023 (Saturday)</h6>
                                                                                    </span>
                                                                                </div>

                                                                                <div class="d-none" id="itinerary_customized_cost">
                                                                                    <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                                    <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i> ₹250</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="day1" class="accordion-collapse collapse show">
                                                                        <div class="accordion-body pt-1 pb-0">
                                                                            <div id="itinerary_hotspot_list_day1">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="text-uppercase mb-0">Itinerary</h5>
                                                                                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_itinerary_daywise_click()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize </button>
                                                                                </div>
                                                                                <ul class="timeline pt-3 px-3 mb-0">
                                                                                    <li class="timeline-item timeline-item-transparent">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-success">
                                                                                            <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event">
                                                                                            <div class="timeline-header">
                                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                                            </div>
                                                                                            <p class="mb-0">Depart from stay</p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Marina Beach</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore. Situated on the beach, are the Samadhis or memorials dedicated to C.N.Annadurai and M.G.Ramachandran, both former Chief Ministers of the state.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Kapaleeshwarar Temple</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>234, Ramakrishna Mutt Rd, Mylapore, Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>10 AM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Government Museum Chennai</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Pantheon Rd, Egmore, Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>12 AM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹250</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">National Art Gallery Chennai</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>3794+RF9, PANTHIAN ROAD, எழும்பூர், Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>4 PM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹25</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Pondy Bazaar</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>T-nagar, Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>6 PM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                Pondy Bazaar, officially called Soundarapandianar Angadi, is a market and neighborhood located in T. Nagar, Chennai, India. It is one of the principal shopping districts of Chennai.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                                            <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event">
                                                                                            <div class="timeline-header">
                                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                                            </div>
                                                                                            <p class="mb-0">Relax at stay</p>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="d-none" id="edit_itinerary_daywise_div">
                                                                                <!-- Itinerary Customization -->
                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <div>
                                                                                        <h5 class="text-capitalize mb-0">Itinerary Customization</h5>
                                                                                        <p class="text-secondary mb-0">Select the hotspots you would like to include for visit.</p>
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button>
                                                                                </div>
                                                                                <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                                                <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                                    <option value="">Search Hotspot</option>
                                                                                    <option value="1">B.M. Birla Planetarium</option>
                                                                                    <option value="2">Chennai Snake Park</option>
                                                                                </select>

                                                                                <div class="row mb-2">
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox1" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox2">
                                                                                                <div class="itinerary_card_image">
                                                                                                    <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                    <div class="itinerary_card_activity_label">Activity Available</div>
                                                                                                </div>
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Kapaleeshwarar Temple </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox2" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">10 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox3">
                                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Government Museum Chennai </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox3" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">12 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">4 Hours</p>
                                                                                                        <p class="mb-0">₹ 250</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox4">
                                                                                                <img src="assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox4" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">₹ 25</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox5">
                                                                                                <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Pondy Bazaar </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox5" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">6 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">3 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                                            <div>
                                                                                                <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                                            </div>
                                                                                            <h5 class="text-primary">Add Hotspot To Visit</h5>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                                        <div class="custom-option custom-option-icon h-100">
                                                                                            <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                                            <div class="row">
                                                                                                <!-- With arrows -->
                                                                                                <div class="col-md-12 mb-1">
                                                                                                    <div class="swiper" id="swiper-with-arrows-itinerary">
                                                                                                        <div class="swiper-wrapper">
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                                        </div>
                                                                                                        <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                        <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <span class="custom-option-body px-4">
                                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                    <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>

                                                                                                    <div class="d-flex">
                                                                                                        <button type="button" class="btn rounded-pill btn-outline-vimeo waves-effect me-3">
                                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Add Guide
                                                                                                        </button>
                                                                                                        <button type="button" class="btn rounded-pill btn-outline-dribbble waves-effect me-3">
                                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Add Activity
                                                                                                        </button>
                                                                                                        <h6 class="text-success d-flex mb-0 align-items-center"><i class="ti ti-checks ti-sm mb-0 me-1"></i>Selected</h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                </h6>
                                                                                                <p class="mt-2" style="text-align: justify;">
                                                                                                    The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                                                </p>
                                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <p class="mb-0 d-flex">Average Visit Duration
                                                                                                        <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                                        <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                                        <table class="table table-borderless text-start table-sm mb-0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Adults
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Children
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Infants
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>

                                                                                                        <p class="mb-1 d-flex px-4 pt-2">
                                                                                                            <b> Total Visit Cost</b>
                                                                                                            <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <div class="d-flex justify-content-between mt-4">
                                                                                                    <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                                    <button type="button" class="btn btn-primary waves-effect">
                                                                                                        <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                                    </button>
                                                                                                </div>
                                                                                            </span>
                                                                                            <!-- </label> -->
                                                                                        </div>
                                                                                    </div>


                                                                                </div>
                                                                                <!-- Itinerary Customization -->


                                                                                <!-- Activity Customization -->
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="text-capitalize mb-0">Activity Customization</h5>
                                                                                    <!-- <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button> -->
                                                                                </div>

                                                                                <p class="text-secondary">Select the activities you would like to include for visit.</p>
                                                                                <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                                                <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                                    <option value="">Search Activity</option>
                                                                                    <option value="1">B.M. Birla Planetarium</option>
                                                                                    <option value="2">Chennai Snake Park</option>
                                                                                </select>

                                                                                <div class="row mb-2">
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1">
                                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="customCheckboxIcon1" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                                            <div>
                                                                                                <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                                            </div>
                                                                                            <h5 class="text-primary">Add Activity</h5>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                                        <div class="custom-option custom-option-icon h-100">
                                                                                            <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                                            <div class="row">
                                                                                                <!-- With arrows -->
                                                                                                <div class="col-md-12 mb-1">
                                                                                                    <div class="swiper" id="swiper-with-arrows-activity">
                                                                                                        <div class="swiper-wrapper">
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                                        </div>
                                                                                                        <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                        <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <span class="custom-option-body px-2">
                                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                    <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                                                    <div class="d-flex">
                                                                                                        <button type="button" class="btn rounded-pill btn-outline-vimeo waves-effect me-3">
                                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Add Guide
                                                                                                        </button>
                                                                                                        <h6 class="text-success d-flex mb-0 align-items-center"><i class="ti ti-checks ti-sm mb-0 me-1"></i>Selected</h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                </h6>
                                                                                                <p class="mt-2" style="text-align: justify;">
                                                                                                    The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                                                </p>
                                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <p class="mb-0 d-flex">Average Visit Duration
                                                                                                        <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                                        <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                </div>

                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                                        <table class="table table-borderless text-start table-sm mb-0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Adults
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Children
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Infants
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>

                                                                                                        <p class="mb-1 d-flex px-4 pt-2">
                                                                                                            <b> Total Visit Cost</b>
                                                                                                            <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                                        </p>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="d-flex justify-content-between mt-4">
                                                                                                    <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                                    <button type="button" class="btn btn-primary waves-effect">
                                                                                                        <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                                    </button>
                                                                                                </div>
                                                                                            </span>
                                                                                            <!-- </label> -->
                                                                                        </div>
                                                                                    </div>


                                                                                </div>
                                                                                <!-- Activity Customization -->


                                                                                <div class="text-center my-4">
                                                                                    <button type="button" class="btn btn-label-linkedin waves-effect" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Build a day trip </button>
                                                                                </div>

                                                                                <!-- <div
                                                                                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row"
                                                                                        >
                                                                                        <h5 class="card-title mb-sm-0 me-2">Sticky Action Bar</h5>
                                                                                        <div class="action-btns">
                                                                                            <button class="btn btn-label-primary me-3">
                                                                                            <span class="align-middle"> Back</span>
                                                                                            </button>
                                                                                            <button class="btn btn-primary">Place Order</button>
                                                                                        </div>
                                                                                        </div> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Day 2 -->
                                                                <div class="accordion-item border-0 active bg-white rounded-3" id="fl-2">
                                                                    <div class="accordion-header itinerary-sticky-title p-0 mb-3" id="dayTwo">
                                                                        <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#day2" aria-expanded="true">
                                                                            <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                                                <div class="d-flex align-items-center">
                                                                                    <div class="avatar-wrapper">
                                                                                        <div class="avatar me-2">
                                                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <span class="d-flex">
                                                                                        <h6 class="mb-0">October 15, 2023 (Sunday)</h6>
                                                                                    </span>
                                                                                </div>

                                                                                <div class="d-none" id="itinerary_customized_cost">
                                                                                    <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                                    <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i> ₹250</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="day2" class="accordion-collapse collapse show">
                                                                        <div class="accordion-body pt-1 pb-0">
                                                                            <div id="itinerary_hotspot_list_day1">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="text-uppercase mb-0">Itinerary</h5>
                                                                                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_itinerary_daywise_click()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize </button>
                                                                                </div>
                                                                                <ul class="timeline pt-3 px-3 mb-0">
                                                                                    <li class="timeline-item timeline-item-transparent">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-success">
                                                                                            <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event">
                                                                                            <div class="timeline-header">
                                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                                            </div>
                                                                                            <p class="mb-0">Depart from stay</p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Marina Beach</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore. Situated on the beach, are the Samadhis or memorials dedicated to C.N.Annadurai and M.G.Ramachandran, both former Chief Ministers of the state.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Kapaleeshwarar Temple</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>234, Ramakrishna Mutt Rd, Mylapore, Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>10 AM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Government Museum Chennai</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Pantheon Rd, Egmore, Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>12 AM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹250</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">National Art Gallery Chennai</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>3794+RF9, PANTHIAN ROAD, எழும்பூர், Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>4 PM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹25</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event pb-3">
                                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                                <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                                <div class="w-100">
                                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                                        <h6 class="mb-0 text-capitalize">Pondy Bazaar</h6>
                                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                                    </div>
                                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>T-nagar, Chennai</p>
                                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>6 PM</p>
                                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                                Pondy Bazaar, officially called Soundarapandianar Angadi, is a market and neighborhood located in T. Nagar, Chennai, India. It is one of the principal shopping districts of Chennai.
                                                                                            </p>
                                                                                        </div>
                                                                                    </li>
                                                                                    <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                                        <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                                            <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                                        </span>
                                                                                        <div class="timeline-event">
                                                                                            <div class="timeline-header">
                                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                                            </div>
                                                                                            <p class="mb-0">Relax at stay</p>
                                                                                        </div>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="d-none" id="edit_itinerary_daywise_div">
                                                                                <!-- Itinerary Customization -->
                                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                    <div>
                                                                                        <h5 class="text-capitalize mb-0">Itinerary Customization</h5>
                                                                                        <p class="text-secondary mb-0">Select the hotspots you would like to include for visit.</p>
                                                                                    </div>
                                                                                    <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button>
                                                                                </div>
                                                                                <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                                                <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                                    <option value="">Search Hotspot</option>
                                                                                    <option value="1">B.M. Birla Planetarium</option>
                                                                                    <option value="2">Chennai Snake Park</option>
                                                                                </select>

                                                                                <div class="row mb-2">
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox1">
                                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox1" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox2">
                                                                                                <div class="itinerary_card_image">
                                                                                                    <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                    <div class="itinerary_card_activity_label">Activity Available</div>
                                                                                                </div>
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Kapaleeshwarar Temple </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox2" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">10 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox3">
                                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Government Museum Chennai </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox3" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">12 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">4 Hours</p>
                                                                                                        <p class="mb-0">₹ 250</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox4">
                                                                                                <img src="assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox4" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">₹ 25</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="hotspotCheckbox5">
                                                                                                <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Pondy Bazaar </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="hotspotCheckbox5" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">6 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">3 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                                            <div>
                                                                                                <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                                            </div>
                                                                                            <h5 class="text-primary">Add Hotspot To Visit</h5>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                                        <div class="custom-option custom-option-icon h-100">
                                                                                            <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                                            <div class="row">
                                                                                                <!-- With arrows -->
                                                                                                <div class="col-md-12 mb-1">
                                                                                                    <div class="swiper" id="swiper-with-arrows-itinerary">
                                                                                                        <div class="swiper-wrapper">
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                                        </div>
                                                                                                        <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                        <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <span class="custom-option-body px-4">
                                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                    <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>

                                                                                                    <div class="d-flex">
                                                                                                        <button type="button" class="btn rounded-pill btn-outline-vimeo waves-effect me-3">
                                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Add Guide
                                                                                                        </button>
                                                                                                        <button type="button" class="btn rounded-pill btn-outline-dribbble waves-effect me-3">
                                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Add Activity
                                                                                                        </button>
                                                                                                        <h6 class="text-success d-flex mb-0 align-items-center"><i class="ti ti-checks ti-sm mb-0 me-1"></i>Selected</h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                </h6>
                                                                                                <p class="mt-2" style="text-align: justify;">
                                                                                                    The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                                                </p>
                                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <p class="mb-0 d-flex">Average Visit Duration
                                                                                                        <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                                        <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                                        <table class="table table-borderless text-start table-sm mb-0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Adults
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Children
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Infants
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>

                                                                                                        <p class="mb-1 d-flex px-4 pt-2">
                                                                                                            <b> Total Visit Cost</b>
                                                                                                            <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                                        </p>
                                                                                                    </div>

                                                                                                </div>

                                                                                                <div class="d-flex justify-content-between mt-4">
                                                                                                    <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                                    <button type="button" class="btn btn-primary waves-effect">
                                                                                                        <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                                    </button>
                                                                                                </div>
                                                                                            </span>
                                                                                            <!-- </label> -->
                                                                                        </div>
                                                                                    </div>


                                                                                </div>
                                                                                <!-- Itinerary Customization -->


                                                                                <!-- Activity Customization -->
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <h5 class="text-capitalize mb-0">Activity Customization</h5>
                                                                                    <!-- <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Back </button> -->
                                                                                </div>

                                                                                <p class="text-secondary">Select the activities you would like to include for visit.</p>
                                                                                <!-- <p class="mb-0"><strong>Free Time</strong></span><span class="badge bg-primary bg-glow ms-2">2 Hours 30 Mins</span></p> -->

                                                                                <select id="itinerary_source" name="itinerary_source" required class="form-select mb-3">
                                                                                    <option value="">Search Activity</option>
                                                                                    <option value="1">B.M. Birla Planetarium</option>
                                                                                    <option value="2">Chennai Snake Park</option>
                                                                                </select>

                                                                                <div class="row mb-2">
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <div class="form-check custom-option custom-option-icon h-100">
                                                                                            <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1">
                                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="me-3" alt="Show img" height="180" width="100%" />
                                                                                                <span class="custom-option-body px-2">
                                                                                                    <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                        <h6 class="custom-option-title mb-0 text-start"> Marina Beach </h6>
                                                                                                        <input class="form-check-input" type="checkbox" value="" id="customCheckboxIcon1" checked />
                                                                                                    </div>
                                                                                                    <h6 class="text-primary mb-0 d-flex">
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                        <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    </h6>
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">8 AM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                                                        <p class="mb-0">2 Hours</p>
                                                                                                        <p class="mb-0">No Fare</p>
                                                                                                    </div>
                                                                                                </span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 mb-md-0 mb-2 pb-3">
                                                                                        <button type="button" class="btn btn-label-primary waves-effect h-100 w-100 d-block">
                                                                                            <div>
                                                                                                <i class="ti ti-circle-plus ti-xl mb-2"></i>
                                                                                            </div>
                                                                                            <h5 class="text-primary">Add Activity</h5>
                                                                                        </button>
                                                                                    </div>

                                                                                    <div class="col-md-12 mb-md-0 mb-2 pb-3">
                                                                                        <div class="custom-option custom-option-icon h-100">
                                                                                            <!-- <label class="form-check-label custom-option-content p-0" for="customCheckboxIcon1"> -->
                                                                                            <div class="row">
                                                                                                <!-- With arrows -->
                                                                                                <div class="col-md-12 mb-1">
                                                                                                    <div class="swiper" id="swiper-with-arrows-activity">
                                                                                                        <div class="swiper-wrapper">
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_1.jpeg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_2.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_3.jpg)"></div>
                                                                                                            <div class="swiper-slide" style="background-image:url(assets/img/itinerary/hotspots/national_art_gallery_4.jpg)"></div>
                                                                                                        </div>
                                                                                                        <div class="swiper-button-next swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                        <div class="swiper-button-prev swiper-button-white custom-icon">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <span class="custom-option-body px-2">
                                                                                                <div class="d-flex justify-content-between align-items-center my-2">
                                                                                                    <h6 class="custom-option-title mb-0 text-start"> National Art Gallery Chennai </h6>
                                                                                                    <div class="d-flex">
                                                                                                        <button type="button" class="btn rounded-pill btn-outline-vimeo waves-effect me-3">
                                                                                                            <span class="ti-xs ti ti-circle-plus me-1"></span>Add Guide
                                                                                                        </button>
                                                                                                        <h6 class="text-success d-flex mb-0 align-items-center"><i class="ti ti-checks ti-sm mb-0 me-1"></i>Selected</h6>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <h6 class="text-primary mb-0 d-flex">
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                    <i class="ti ti-star-filled ti-xs"></i>
                                                                                                </h6>
                                                                                                <p class="mt-2" style="text-align: justify;">
                                                                                                    The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                                                </p>
                                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                                    <p class="my-1 d-flex">
                                                                                                        Trip Time
                                                                                                        <span class="text-decoration-underline ms-1">4 PM</span> <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                    <p class="mb-0 d-flex">Average Visit Duration
                                                                                                        <span class="text-decoration-underline ms-1">3 Hours</span>
                                                                                                        <a href="javascript:void(0);" class="text-muted" data-bs-toggle="modal" data-bs-target="#modalCenter"><i class="ti ti-pencil me-3 mt-0 mb-2 ti-sm"></i></a>
                                                                                                    </p>
                                                                                                </div>

                                                                                                <div class="d-flex justify-content-between">
                                                                                                    <div class="p-1 rounded text-center" style="background-color: rgba(75,75,75,.04);">
                                                                                                        <table class="table table-borderless text-start table-sm mb-0">
                                                                                                            <tbody>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Adults
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Children
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="form-check mt-1 me-1 ps-0">
                                                                                                                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" checked>
                                                                                                                            <label class="form-check-label me-2" for="defaultCheck1">
                                                                                                                                Infants
                                                                                                                            </label>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <input id="sallInput" class="form-control form-control-sm w-px-50" type="text" value="1" placeholder="">
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            </tbody>
                                                                                                        </table>

                                                                                                        <p class="mb-1 d-flex px-4 pt-2">
                                                                                                            <b> Total Visit Cost</b>
                                                                                                            <span class="text-decoration-underline ms-4">₹ 25</span>
                                                                                                        </p>
                                                                                                    </div>

                                                                                                </div>
                                                                                                <div class="d-flex justify-content-between mt-4">
                                                                                                    <a href="" type="button" class="btn btn-label-github waves-effect ps-3">Cancel</a>
                                                                                                    <button type="button" class="btn btn-primary waves-effect">
                                                                                                        <span class="ti-xs ti ti-world me-1"></span>Add To Trip
                                                                                                    </button>
                                                                                                </div>
                                                                                            </span>
                                                                                            <!-- </label> -->
                                                                                        </div>
                                                                                    </div>


                                                                                </div>
                                                                                <!-- Activity Customization -->


                                                                                <div class="text-center my-4">
                                                                                    <button type="button" class="btn btn-label-linkedin waves-effect" onclick="edit_back_itinerary_daywise_click()"> <i class="tf-icons ti ti-arrow-big-left-filled ti-xs me-1"></i> Build a day trip </button>
                                                                                </div>

                                                                                <!-- <div
                                                                                        class="card-header sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row"
                                                                                        >
                                                                                        <h5 class="card-title mb-sm-0 me-2">Sticky Action Bar</h5>
                                                                                        <div class="action-btns">
                                                                                            <button class="btn btn-label-primary me-3">
                                                                                            <span class="align-middle"> Back</span>
                                                                                            </button>
                                                                                            <button class="btn btn-primary">Place Order</button>
                                                                                        </div>
                                                                                        </div> -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="card border border-primary hotel_content">
                                                                <div class="hotel_header itinerary-sticky-title">
                                                                    <div class="d-flex align-items-center justify-content-between  px-3 py-2 pt-3">
                                                                        <h5 class="card-header p-0">Hotel List</h5>
                                                                        <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm" id="customize_hotel_btn" onclick="edit_itinerary_hotel_customize()"> <i class="tf-icons ti ti-edit ti-xs me-1"></i> Customize Hotel </button>
                                                                        <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm d-none" id="customize_back_hotel_btn" onclick="back_itinerary_hotel_customize()"> <i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back To Hotel List </button>
                                                                    </div>

                                                                    <div class="d-flex justify-content-between">
                                                                        <div class="d-flex p-3">
                                                                            <span class="mb-0 me-4"><strong>Total Rooms</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                                            <span class="mb-0 me-4"><strong>Total Extra Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                                            <span class="mb-0 me-4"><strong>Child No Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                                        </div>

                                                                        <div class="mb-0 me-3 p-3 pe-0"><strong>Total Amount For Hotel</strong><span class="badge bg-primary bg-glow ms-2">₹28,900</span></div>
                                                                    </div>
                                                                </div>

                                                                <div id="hotel_preview_table_div" class="p-3">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-between">
                                                                                <h5 class="card-title">5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel Category</h5>
                                                                                <div class="me-2">
                                                                                    <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive text-nowrap">
                                                                                <table class="table table-striped">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Dates</th>
                                                                                            <th>Location</th>
                                                                                            <th>Hotel Name</th>
                                                                                            <th>Room</th>
                                                                                            <th>Meal</th>
                                                                                            <th>Cost</th>
                                                                                            <th></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="table-border-bottom-0">
                                                                                        <tr>
                                                                                            <td>
                                                                                                October 14, 2023
                                                                                                <br />
                                                                                                October 15, 2023
                                                                                                <br />
                                                                                                (2N)
                                                                                            </td>
                                                                                            <td>Chennai</td>
                                                                                            <td id="hotel_name_edit"><span class="fw-medium">Zion by the Park</span></td>
                                                                                            <td id="hotel_room_edit">Standard</td>
                                                                                            <td id="hotel_meal_edit">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 10,282</td>
                                                                                            <td id="hotel_rowwise_submit">
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 16, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Pondicherry</td>
                                                                                            <td><span class="fw-medium">Misty Ocean</span></td>
                                                                                            <td>Premium</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 17, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Tanjore</td>
                                                                                            <td><span class="fw-medium">Grand Ashoka</span></td>
                                                                                            <td>Premium</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 18, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Trichy</td>
                                                                                            <td><span class="fw-medium">Hotel Rockfort View</span></td>
                                                                                            <td>Standard</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 19, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Madurai</td>
                                                                                            <td><span class="fw-medium">Mmr Garden</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 20, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Rameswaram</td>
                                                                                            <td><span class="fw-medium">Star Palace</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 21, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Kanyakumari</td>
                                                                                            <td><span class="fw-medium">Gopi Niva Grand</span></td>
                                                                                            <td>Superior</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 22, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Trivandrum</td>
                                                                                            <td><span class="fw-medium">Biverah</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card mt-3">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-between">
                                                                                <h5 class="card-title">4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel Category</h5>
                                                                                <div class="me-2">
                                                                                    <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive text-nowrap">
                                                                                <table class="table table-striped">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Dates</th>
                                                                                            <th>Location</th>
                                                                                            <th>Hotel Name</th>
                                                                                            <th>Room</th>
                                                                                            <th>Meal</th>
                                                                                            <th>Cost</th>
                                                                                            <th></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="table-border-bottom-0">
                                                                                        <tr>
                                                                                            <td>
                                                                                                October 14, 2023
                                                                                                <br />
                                                                                                October 15, 2023
                                                                                                <br />
                                                                                                (2N)
                                                                                            </td>
                                                                                            <td>Chennai</td>
                                                                                            <td id="hotel_name_edit"><span class="fw-medium">Zion by the Park</span></td>
                                                                                            <td id="hotel_room_edit">Standard</td>
                                                                                            <td id="hotel_meal_edit">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 10,282</td>
                                                                                            <td id="hotel_rowwise_submit">
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 16, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Pondicherry</td>
                                                                                            <td><span class="fw-medium">Misty Ocean</span></td>
                                                                                            <td>Premium</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 17, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Tanjore</td>
                                                                                            <td><span class="fw-medium">Grand Ashoka</span></td>
                                                                                            <td>Premium</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 18, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Trichy</td>
                                                                                            <td><span class="fw-medium">Hotel Rockfort View</span></td>
                                                                                            <td>Standard</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 19, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Madurai</td>
                                                                                            <td><span class="fw-medium">Mmr Garden</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 20, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Rameswaram</td>
                                                                                            <td><span class="fw-medium">Star Palace</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 21, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Kanyakumari</td>
                                                                                            <td><span class="fw-medium">Gopi Niva Grand</span></td>
                                                                                            <td>Superior</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 22, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Trivandrum</td>
                                                                                            <td><span class="fw-medium">Biverah</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card mt-3">
                                                                        <div class="card-body">
                                                                            <div class="d-flex justify-content-between">
                                                                                <h5 class="card-title">3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel Category</h5>
                                                                                <div class="me-2">
                                                                                    <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                </div>
                                                                            </div>
                                                                            <div class="table-responsive text-nowrap">
                                                                                <table class="table table-striped">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Dates</th>
                                                                                            <th>Location</th>
                                                                                            <th>Hotel Name</th>
                                                                                            <th>Room</th>
                                                                                            <th>Meal</th>
                                                                                            <th>Cost</th>
                                                                                            <th></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="table-border-bottom-0">
                                                                                        <tr>
                                                                                            <td>
                                                                                                October 14, 2023
                                                                                                <br />
                                                                                                October 15, 2023
                                                                                                <br />
                                                                                                (2N)
                                                                                            </td>
                                                                                            <td>Chennai</td>
                                                                                            <td id="hotel_name_edit"><span class="fw-medium">Zion by the Park</span></td>
                                                                                            <td id="hotel_room_edit">Standard</td>
                                                                                            <td id="hotel_meal_edit">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 10,282</td>
                                                                                            <td id="hotel_rowwise_submit">
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect" onclick="itinerary_hotel_edit_rowwise()">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 16, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Pondicherry</td>
                                                                                            <td><span class="fw-medium">Misty Ocean</span></td>
                                                                                            <td>Premium</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 17, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Tanjore</td>
                                                                                            <td><span class="fw-medium">Grand Ashoka</span></td>
                                                                                            <td>Premium</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 18, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Trichy</td>
                                                                                            <td><span class="fw-medium">Hotel Rockfort View</span></td>
                                                                                            <td>Standard</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 19, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Madurai</td>
                                                                                            <td><span class="fw-medium">Mmr Garden</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 20, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Rameswaram</td>
                                                                                            <td><span class="fw-medium">Star Palace</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 21, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Kanyakumari</td>
                                                                                            <td><span class="fw-medium">Gopi Niva Grand</span></td>
                                                                                            <td>Superior</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>October 22, 2023
                                                                                                <br />
                                                                                                (1N)
                                                                                            </td>
                                                                                            <td>Trivandrum</td>
                                                                                            <td><span class="fw-medium">Biverah</span></td>
                                                                                            <td>Executive</td>
                                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                            <td>₹ 5,140</td>
                                                                                            <td>
                                                                                                <button type="button" class="btn btn-icon btn-label-primary waves-effect">
                                                                                                    <span class="ti ti-edit"></span>
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-none" id="hotel_customization_div">
                                                                    <div class="mx-2 demo-inline-spacing">
                                                                        <button type="button" class="btn rounded-pill btn-label-linkedin waves-effect"><i class="tf-icons ti ti-check ti-xs me-1"></i> 5 Star Hotel</button>
                                                                        <button type="button" class="btn rounded-pill btn-label-pinterest waves-effect"> 4 Star Hotel </button>
                                                                        <button type="button" class="btn rounded-pill btn-label-info waves-effect"> 3 Star Hotel </button>
                                                                        <button type="button" class="btn rounded-pill btn-label-slack waves-effect"> 2 Star Hotel </button>
                                                                        <button type="button" class="btn rounded-pill btn-label-github waves-effect"> 1 Star Hotel</button>
                                                                    </div>

                                                                    <div class="table-responsive text-nowrap mt-3">
                                                                        <table class="table table-striped  table-sm">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th></th>
                                                                                    <th>Dates</th>
                                                                                    <th>Location</th>
                                                                                    <th>Hotel Name</th>
                                                                                    <th>Room</th>
                                                                                    <th>Meal</th>
                                                                                    <th>Cost</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody class="table-border-bottom-0">
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked id="checkbox_5star1">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_4star1">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_3star1">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_2star1">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_1star1">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        Oct 14, 2023
                                                                                        <br />
                                                                                        Oct 15, 2023
                                                                                        <br />
                                                                                        (2N)
                                                                                    </td>
                                                                                    <td>Chennai</td>
                                                                                    <td id="hotel_name_edit_customize">
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('5star1')">Zion by the Park <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('4star1')">Lemon Tree <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('3star1')">The Residency Towers <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('2star1')">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3 cursor-pointer" onclick="checkCheckboxHotelTableRow('1star1')">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td id="hotel_room_edit_customize">
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('5star1')">Standard</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('4star1')">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('3star1')">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('2star1')">Standard</div>
                                                                                        <div class="fw-medium py-3 cursor-pointer" onclick="checkCheckboxHotelTableRow('1star1')">Standard</div>
                                                                                    </td>
                                                                                    <td id="hotel_meal_edit_customize">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('5star1')">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('4star1')">₹ 11,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('3star1')">₹ 15,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('2star1')">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 cursor-pointer" onclick="checkCheckboxHotelTableRow('1star1')">₹ 10,282</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Oct 16, 2023
                                                                                        <br />
                                                                                        (1N)
                                                                                    </td>
                                                                                    <td>Pondicherry</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Misty Ocean <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Shenbaga <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Le Pondy <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                                        <div class="fw-medium py-3">Standard</div>
                                                                                    </td>
                                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Oct 17, 2023
                                                                                        <br />
                                                                                        (1N)
                                                                                    </td>
                                                                                    <td>Tanjore</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Grand Ashoka <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Sangam <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                                        <div class="fw-medium py-3">Standard</div>
                                                                                    </td>
                                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Oct 18, 2023
                                                                                        <br />
                                                                                        (1N)
                                                                                    </td>
                                                                                    <td>Trichy</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                                        <div class="fw-medium py-3">Standard</div>
                                                                                    </td>
                                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Oct 19, 2023
                                                                                        <br />
                                                                                        (1N)
                                                                                    </td>
                                                                                    <td>Madurai</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                                        <div class="fw-medium py-3">Standard</div>
                                                                                    </td>
                                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Oct 20, 2023
                                                                                        <br />
                                                                                        (1N)
                                                                                    </td>
                                                                                    <td>Rameswaram</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                                        <div class="fw-medium py-3">Standard</div>
                                                                                    </td>
                                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                        <div class="fw-medium py-3">
                                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>Oct 21, 2023
                                                                                        <br />
                                                                                        (1N)
                                                                                    </td>
                                                                                    <td>Kanyakumari</td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                                        <div class="fw-medium py-3">Standard</div>
                                                                                    </td>
                                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                                    <td <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282
                                                                    </div>
                                                                    <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                    <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                    <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                    <div class="fw-medium py-3">₹ 5,282</div>
                                                                    </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>
                                                                            <div class="fw-medium py-3">
                                                                                <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                            </div>
                                                                            <div class="fw-medium py-3">
                                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                            </div>
                                                                            <div class="fw-medium py-3">
                                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                            </div>
                                                                            <div class="fw-medium py-3">
                                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                            </div>
                                                                            <div class="fw-medium py-3">
                                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                            </div>
                                                                        </td>
                                                                        <td>Oct 22, 2023
                                                                            <br />
                                                                            (1N)
                                                                        </td>
                                                                        <td>Trivandrum</td>
                                                                        <td>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">Biverah <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                            <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                            <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                            <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                            <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                            <div class="fw-medium py-3">Standard</div>
                                                                        </td>
                                                                        <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                        <td>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                            <div class="fw-medium py-3">₹ 5,282</div>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>

                                                        </div>

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

                                                                        <div class="row align-items-center justify-content-between mb-2">
                                                                            <div class="col-auto">
                                                                                <span class="text-heading">Add Agent Commission Cost </span>
                                                                            </div>
                                                                            <div class="col-md-3">
                                                                                <input type="text" class="form-control form-control-sm" id="basic-default-name" placeholder="In Rupee(₹)">
                                                                            </div>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading">GST @ 5 % On The total Package </span>
                                                                            <h6 class="mb-0">₹6,865</h6>
                                                                        </div>

                                                                        <div class="d-flex justify-content-between mb-2">
                                                                            <span class="text-heading fw-bold">Net Payable To Doview Holidays India Pvt ltd</span>
                                                                            <h6 class="mb-0 fw-bold">₹1,44,169</h6>
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
                                                                <button type="button" class="btn rounded-pill btn-success waves-effect waves-light">
                                                                    <i class="tf-icons ti ti-brand-whatsapp ti-xs me-1"></i> Share Via Whatsapp
                                                                </button>
                                                                <button type="button" class="btn rounded-pill btn-label-linkedin waves-effect">
                                                                    <i class="tf-icons ti ti-share ti-xs me-1"></i> Share
                                                                </button>
                                                            </div>
                                                            <div class="demo-inline-spacing">
                                                                <button type="button" class="btn btn-primary waves-effect waves-light">
                                                                    <span class="ti-xs ti ti-check me-1"></span>Confirm
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-top-itinerary2" role="tabpanel">
                                        <p>
                                            Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice cream. Gummies
                                            halvah
                                            tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream cheesecake fruitcake.
                                        </p>
                                        <p class="mb-0">
                                            Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah cotton candy
                                            liquorice caramels.
                                        </p>
                                    </div>
                                    <div class="tab-pane fade" id="navs-top-itinerary3" role="tabpanel">
                                        <p>
                                            Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies cupcake gummi
                                            bears
                                            cake chocolate.
                                        </p>
                                        <p class="mb-0">
                                            Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake. Sweet roll icing
                                            sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding jelly jelly-o tart brownie
                                            jelly.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">B.M. Birla Planetarium</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row g-2">
                                                <div class="col-12 mb-0">
                                                    <label for="emailWithTitle" class="form-label text-black">Time Range</label>
                                                </div>
                                                <div class="col mb-0">
                                                    <label for="emailWithTitle" class="form-label text-black">From</label>
                                                    <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr_time_from" />
                                                </div>
                                                <div class="col mb-0">
                                                    <label for="emailWithTitle" class="form-label text-black">To</label>
                                                    <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr_time_to" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        <?php elseif ($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview') : ?>
            <div id="se-pre-con"></div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-header">Itinerary Plan</b></h5>
                        <a href="itinerary.php" type="button" class="btn btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to List</a>
                    </div>
                </div>
                <div class="itinerary_plan_header d-flex flex-column justify-content-between rounded my-2 p-4">
                    <div>
                        <h5 class="text-capitalize"> Itinerary for <b>October 14, 2023</b> to <b>October 24, 2023</b> (<b>10</b> Day, <b>9</b> Night)</h5>
                        <h3 class="text-capitalize">Chennai <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-xl mx-1"></i> Trivandrum airport Drop</h3>
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="mb-0 me-4"><strong>Adults</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">2</span></span>
                                <span class="mb-0 me-4"><strong>Children</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">1</span></span>
                                <span class="mb-0 me-4"><strong>Infants</strong><span class="badge badge-center bg-primary bg-glow rounded-pill mx-2">1</span></span>
                            </div>
                            <h5 class="mb-0"><strong>Budget</strong></span><span class="badge bg-primary bg-glow ms-2">₹ 55,000</span></h5>
                        </div>
                    </div>
                    <div>
                    </div>
                </div>

                <div class="nav-align-top my-2 p-0">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary1" aria-controls="navs-top-itinerary1" aria-selected="true">Route Itinerary 1</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary2" aria-controls="navs-top-itinerary2" aria-selected="false" tabindex="-1">Route Itinerary 2</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary3" aria-controls="navs-top-itinerary3" aria-selected="false" tabindex="-1">Route Itinerary 3</button>
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
                                        <div class="card-body">

                                            <div class="text-end">
                                                <label class="switch switch-square mb-3">
                                                    <input type="checkbox" class="switch-input" id="switch_map">
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on"></span>
                                                        <span class="switch-off"></span>
                                                    </span>
                                                    <span class="switch-label">Map</span>
                                                </label>
                                            </div>

                                            <div class="row app-logistics-fleet-wrapper mb-3" id="itinerary_map_div">
                                                <!-- Map Menu Button when screen is < md -->
                                                <div class="flex-shrink-0 position-fixed m-4 d-md-none w-auto zindex-1">
                                                    <button class="btn btn-label-white border border-2 zindex-2 p-2" data-bs-toggle="sidebar" data-overlay="" data-target="#app-logistics-fleet-sidebar"><i class="ti ti-menu-2"></i></button>
                                                </div>
                                                <div class="col-md-12">
                                                    <!-- Mapbox Map container -->
                                                    <div class="col h-100 map-container">
                                                        <!-- Map -->
                                                        <div id="map" class="h-100 w-100"></div>
                                                    </div>
                                                </div>
                                            </div>

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
                                                                        <h6 class="mb-0">October 14, 2023 (Saturday)</h6>
                                                                    </span>
                                                                </div>

                                                                <div class="" id="itinerary_customized_cost">
                                                                    <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                    <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i> ₹250</span>
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
                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                            </div>
                                                                            <p class="mb-0">Depart from stay</p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Marina Beach</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore. Situated on the beach, are the Samadhis or memorials dedicated to C.N.Annadurai and M.G.Ramachandran, both former Chief Ministers of the state.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Kapaleeshwarar Temple</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>234, Ramakrishna Mutt Rd, Mylapore, Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>10 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Government Museum Chennai</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Pantheon Rd, Egmore, Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>12 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹250</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">National Art Gallery Chennai</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>3794+RF9, PANTHIAN ROAD, எழும்பூர், Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>4 PM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹25</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Pondy Bazaar</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>T-nagar, Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>6 PM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                Pondy Bazaar, officially called Soundarapandianar Angadi, is a market and neighborhood located in T. Nagar, Chennai, India. It is one of the principal shopping districts of Chennai.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                            <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event">
                                                                            <div class="timeline-header">
                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                            </div>
                                                                            <p class="mb-0">Relax at stay</p>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Day 2 -->
                                                <div class="accordion-item border-0 active bg-white rounded-3" id="fl-2">
                                                    <div class="accordion-header itinerary-sticky-title p-0 mb-3" id="dayTwo">
                                                        <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#day2" aria-expanded="true">
                                                            <div class="d-flex justify-content-between align-items-center w-100 itinerary_daywise_list_tab bg-white">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="avatar-wrapper">
                                                                        <div class="avatar me-2">
                                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                                        </div>
                                                                    </div>
                                                                    <span class="d-flex">
                                                                        <h6 class="mb-0">October 15, 2023 (Sunday)</h6>
                                                                    </span>
                                                                </div>

                                                                <div class="" id="itinerary_customized_cost">
                                                                    <!-- <span class="text-muted fw-bold me-3"><i class="ti ti-clock mb-1"></i> 3 Hours 15 Mins</span> -->
                                                                    <span class="text-muted fw-bold me-3"><i class="ti ti-ticket mb-1"></i> ₹250</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="day2" class="accordion-collapse collapse show">
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
                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                            </div>
                                                                            <p class="mb-0">Depart from stay</p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Marina Beach</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Chennai, Tamil Nadu, India</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>8 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                Marina Beach, the pride of Chennai is the second longest beach in the world and has a wide sandy shore. Situated on the beach, are the Samadhis or memorials dedicated to C.N.Annadurai and M.G.Ramachandran, both former Chief Ministers of the state.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Kapaleeshwarar Temple</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>234, Ramakrishna Mutt Rd, Mylapore, Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>10 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Government Museum Chennai</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>Pantheon Rd, Egmore, Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>12 AM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹250</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The Kapaleeshwarar Temple is a Hindu temple dedicated to Shiva located in Mylapore, Chennai in the Indian state of Tamil Nadu. The form of Shiva's consort Parvati worshipped at this temple is called Karpagambal (goddess of the wish-yielding tree). The temple was built around the 7th century CE and is an example of Dravidian architecture.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/national_art_gallery_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">National Art Gallery Chennai</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>3794+RF9, PANTHIAN ROAD, எழும்பூர், Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>4 PM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>₹25</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                The National Art Gallery situated in Egmore, Chennai, is one of the oldest art galleries in India. It is located in the Government Museum Complex on Pantheon Road, Egmore, which also houses the Government Museum and the Connemara Public Library.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item pb-4 timeline-item-success border-left-dashed">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-primary">
                                                                            <i class="ti ti-map-pin rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event pb-3">
                                                                            <div class="d-flex flex-sm-row flex-column">
                                                                                <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" class="rounded me-3" alt="Show img" height="100" width="100" />
                                                                                <div class="w-100">
                                                                                    <div class="timeline-header flex-wrap mb-1 mt-3 mt-sm-0 align-items-center">
                                                                                        <h6 class="mb-0 text-capitalize">Pondy Bazaar</h6>
                                                                                        <h6 class="text-primary mb-0"><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i><i class="ti ti-star-filled"></i></h6>
                                                                                    </div>
                                                                                    <p class="my-1"><i class="ti ti-map-pin me-1"></i>T-nagar, Chennai</p>
                                                                                    <p class="my-1"><i class="ti ti-clock-filled me-1"></i>6 PM</p>
                                                                                    <p class="my-1"><i class="ti ti-ticket me-1 ti-sm"></i>No Fare</p>
                                                                                </div>
                                                                            </div>
                                                                            <p class="mt-2" style="text-align: justify;">
                                                                                Pondy Bazaar, officially called Soundarapandianar Angadi, is a market and neighborhood located in T. Nagar, Chennai, India. It is one of the principal shopping districts of Chennai.
                                                                            </p>
                                                                        </div>
                                                                    </li>
                                                                    <li class="timeline-item timeline-item-transparent border-transparent pb-0">
                                                                        <span class="timeline-indicator-advanced timeline-indicator-danger">
                                                                            <i class="ti ti-building-skyscraper rounded-circle"></i>
                                                                        </span>
                                                                        <div class="timeline-event">
                                                                            <div class="timeline-header">
                                                                                <h6 class="mb-0">Zone by The Park Hotel</h6>
                                                                            </div>
                                                                            <p class="mb-0">Relax at stay</p>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="card border border-primary hotel_content">
                                                <div class="hotel_header itinerary-sticky-title">
                                                    <div class="d-flex align-items-center justify-content-between  px-3 py-2 pt-3">
                                                        <h5 class="card-header p-0">Hotel List</h5>
                                                        <button type="button" class="btn btn-outline-dribbble waves-effect btn-sm d-none" id="customize_back_hotel_btn" onclick="back_itinerary_hotel_customize()"> <i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back To Hotel List </button>
                                                    </div>

                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex p-3">
                                                            <span class="mb-0 me-4"><strong>Total Rooms</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                            <span class="mb-0 me-4"><strong>Total Extra Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                            <span class="mb-0 me-4"><strong>Child No Bed</strong><span class="badge badge-center bg-primary bg-glow mx-2">1</span></span>
                                                        </div>

                                                        <div class="mb-0 me-3 p-3 pe-0"><strong>Total Amount For Hotel</strong><span class="badge bg-primary bg-glow ms-2">₹28,900</span></div>
                                                    </div>
                                                </div>

                                                <div id="hotel_preview_table_div" class="p-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <div class="table-responsive text-nowrap">
                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Dates</th>
                                                                            <th>Location</th>
                                                                            <th>Hotel Name</th>
                                                                            <th>Room</th>
                                                                            <th>Meal</th>
                                                                            <th>Cost</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="table-border-bottom-0">
                                                                        <tr>
                                                                            <td>
                                                                                October 14, 2023
                                                                                <br />
                                                                                October 15, 2023
                                                                                <br />
                                                                                (2N)
                                                                            </td>
                                                                            <td>Chennai</td>
                                                                            <td id="hotel_name_edit"><span class="fw-medium">Zion by the Park</span></td>
                                                                            <td id="hotel_room_edit">Standard</td>
                                                                            <td id="hotel_meal_edit">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 10,282</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 16, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Pondicherry</td>
                                                                            <td><span class="fw-medium">Misty Ocean</span></td>
                                                                            <td>Premium</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 17, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Tanjore</td>
                                                                            <td><span class="fw-medium">Grand Ashoka</span></td>
                                                                            <td>Premium</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 18, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Trichy</td>
                                                                            <td><span class="fw-medium">Hotel Rockfort View</span></td>
                                                                            <td>Standard</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 19, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Madurai</td>
                                                                            <td><span class="fw-medium">Mmr Garden</span></td>
                                                                            <td>Executive</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 20, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Rameswaram</td>
                                                                            <td><span class="fw-medium">Star Palace</span></td>
                                                                            <td>Executive</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 21, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Kanyakumari</td>
                                                                            <td><span class="fw-medium">Gopi Niva Grand</span></td>
                                                                            <td>Superior</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>October 22, 2023
                                                                                <br />
                                                                                (1N)
                                                                            </td>
                                                                            <td>Trivandrum</td>
                                                                            <td><span class="fw-medium">Biverah</span></td>
                                                                            <td>Executive</td>
                                                                            <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                            <td>₹ 5,140</td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-none" id="hotel_customization_div">
                                                    <div class="mx-2 demo-inline-spacing">
                                                        <button type="button" class="btn rounded-pill btn-label-linkedin waves-effect"><i class="tf-icons ti ti-check ti-xs me-1"></i> 5 Star Hotel</button>
                                                        <button type="button" class="btn rounded-pill btn-label-pinterest waves-effect"> 4 Star Hotel </button>
                                                        <button type="button" class="btn rounded-pill btn-label-info waves-effect"> 3 Star Hotel </button>
                                                        <button type="button" class="btn rounded-pill btn-label-slack waves-effect"> 2 Star Hotel </button>
                                                        <button type="button" class="btn rounded-pill btn-label-github waves-effect"> 1 Star Hotel</button>
                                                    </div>

                                                    <div class="table-responsive text-nowrap mt-3">
                                                        <table class="table table-striped  table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th></th>
                                                                    <th>Dates</th>
                                                                    <th>Location</th>
                                                                    <th>Hotel Name</th>
                                                                    <th>Room</th>
                                                                    <th>Meal</th>
                                                                    <th>Cost</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked id="checkbox_5star1">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_4star1">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_3star1">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_2star1">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" id="checkbox_1star1">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        Oct 14, 2023
                                                                        <br />
                                                                        Oct 15, 2023
                                                                        <br />
                                                                        (2N)
                                                                    </td>
                                                                    <td>Chennai</td>
                                                                    <td id="hotel_name_edit_customize">
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('5star1')">Zion by the Park <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('4star1')">Lemon Tree <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('3star1')">The Residency Towers <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('2star1')">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3 cursor-pointer" onclick="checkCheckboxHotelTableRow('1star1')">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td id="hotel_room_edit_customize">
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('5star1')">Standard</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('4star1')">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('3star1')">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('2star1')">Standard</div>
                                                                        <div class="fw-medium py-3 cursor-pointer" onclick="checkCheckboxHotelTableRow('1star1')">Standard</div>
                                                                    </td>
                                                                    <td id="hotel_meal_edit_customize">Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('5star1')">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('4star1')">₹ 11,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('3star1')">₹ 15,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary cursor-pointer" onclick="checkCheckboxHotelTableRow('2star1')">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 cursor-pointer" onclick="checkCheckboxHotelTableRow('1star1')">₹ 10,282</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 16, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Pondicherry</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Misty Ocean <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Shenbaga <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Le Pondy <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 17, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Tanjore</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Grand Ashoka <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Sangam <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 18, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Trichy</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Standard</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 19, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Madurai</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 20, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Rameswaram</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                                        <div class="fw-medium py-3">₹ 5,282</div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                        <div class="fw-medium py-3">
                                                                            <input type="checkbox" class="dt-checkboxes form-check-input">
                                                                        </div>
                                                                    </td>
                                                                    <td>Oct 21, 2023
                                                                        <br />
                                                                        (1N)
                                                                    </td>
                                                                    <td>Kanyakumari</td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Hotel Rockfort View <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                                        <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                                        <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                                        <div class="fw-medium py-3">Standard</div>
                                                                    </td>
                                                                    <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                                    <td <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282
                                                    </div>
                                                    <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                    <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                    <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                    <div class="fw-medium py-3">₹ 5,282</div>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="fw-medium py-3">
                                                                <input type="checkbox" class="dt-checkboxes form-check-input" checked>
                                                            </div>
                                                            <div class="fw-medium py-3">
                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                            </div>
                                                            <div class="fw-medium py-3">
                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                            </div>
                                                            <div class="fw-medium py-3">
                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                            </div>
                                                            <div class="fw-medium py-3">
                                                                <input type="checkbox" class="dt-checkboxes form-check-input">
                                                            </div>
                                                        </td>
                                                        <td>Oct 22, 2023
                                                            <br />
                                                            (1N)
                                                        </td>
                                                        <td>Trivandrum</td>
                                                        <td>
                                                            <div class="fw-medium py-3 border-bottom border-primary">Biverah <span>(5 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                            <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(4 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                            <div class="fw-medium py-3 border-bottom border-primary">Courtyard By Marriott <span>(3 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                            <div class="fw-medium py-3 border-bottom border-primary">Tower Park <span>(2 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>

                                                            <div class="fw-medium my-3">Zion Park <span>(1 <i class="ti ti-star-filled ti-xs mb-1"></i> Hotel)</span></div>
                                                        </td>
                                                        <td>
                                                            <div class="fw-medium py-3 border-bottom border-primary">Executive</div>
                                                            <div class="fw-medium py-3 border-bottom border-primary">Superior</div>
                                                            <div class="fw-medium py-3 border-bottom border-primary">King Room</div>
                                                            <div class="fw-medium py-3 border-bottom border-primary">Premium</div>
                                                            <div class="fw-medium py-3">Standard</div>
                                                        </td>
                                                        <td>Breakfast<br /> Lunch<br /> Dinner</td>
                                                        <td>
                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 6,282</div>
                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 10,282</div>
                                                            <div class="fw-medium py-3 border-bottom border-primary">₹ 5,282</div>
                                                            <div class="fw-medium py-3">₹ 5,282</div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                        </div>

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
                    <div class="tab-pane fade" id="navs-top-itinerary2" role="tabpanel">
                        <p>
                            Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice cream. Gummies
                            halvah
                            tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream cheesecake fruitcake.
                        </p>
                        <p class="mb-0">
                            Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah cotton candy
                            liquorice caramels.
                        </p>
                    </div>
                    <div class="tab-pane fade" id="navs-top-itinerary3" role="tabpanel">
                        <p>
                            Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies cupcake gummi
                            bears
                            cake chocolate.
                        </p>
                        <p class="mb-0">
                            Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake. Sweet roll icing
                            sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding jelly jelly-o tart brownie
                            jelly.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        <div class="demo-inline-spacing">
                            <button type="button" class="btn rounded-pill btn-google-plus waves-effect waves-light">
                                <i class="tf-icons ti ti-mail ti-xs me-1"></i> Share All Itinerary Via Email
                            </button>
                            <button type="button" class="btn rounded-pill btn-success waves-effect waves-light">
                                <i class="tf-icons ti ti-brand-whatsapp ti-xs me-1"></i> Share All Itinerary Via Whatsapp
                            </button>
                            <button type="button" class="btn rounded-pill btn-label-linkedin waves-effect">
                                <i class="tf-icons ti ti-share ti-xs me-1"></i> Share All Itinerary
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">B.M. Birla Planetarium</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-12 mb-0">
                                    <label for="emailWithTitle" class="form-label text-black">Time Range</label>
                                </div>
                                <div class="col mb-0">
                                    <label for="emailWithTitle" class="form-label text-black">From</label>
                                    <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr_time_from" />
                                </div>
                                <div class="col mb-0">
                                    <label for="emailWithTitle" class="form-label text-black">To</label>
                                    <input type="text" class="form-control" placeholder="HH:MM" id="flatpickr_time_to" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php endif; ?>
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
<script src="assets/vendor/libs/mapbox-gl/mapbox-gl.js"></script>
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
<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="assets/vendor/libs/toastr/toastr.js"></script>

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
<!-- Sticky -->

<!-- <script src="assets/js/ui-carousel.js"></script> -->
<!-- <script src="assets/js/extended-ui-drag-and-drop.js"></script> -->


<script>
    $(document).ready(function() {
        <?php if (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>

            // Your code specific to this condition
            show_ADD_ITENARY_STEP1('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');

        <?php endif; ?>
        <?php if (($_GET['route'] == '')) : ?>
            // Your code specific to this condition
            showITINERARY_LIST();

        <?php endif; ?>

        <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_list') : ?>
            $(".form-select").selectize();

            $('#itinerary_1').on('click', '#btn_add_more', function() {
                $(this).closest('li').after('<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0" id="add_more_itinerary"><span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span><div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0"><div class="d-flex justify-content-between"><select id="itinerary_arrival" name="itinerary_arrival"  required class="form-select form-select-sm text-start"><option value="">Search</option><option value="1">Chennai</option><option value="2">Kanyakumari</option><option value="3">Puducherry</option></select><button type="button" class="btn btn-sm btn-label-primary waves-effect mx-1 my-1" onclick="addItinerarySubmit()">Submit</button></div><div class="mt-1 mb-1 row"><small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small><div class="col-auto px-0"><div class="input-group input_group_plus_minus input_itinerary_list"><input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity"><input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field"><input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity"></div></div></div></div></li>');
            });

            $('#itinerary_2').on('click', '#btn_add_more', function() {
                $(this).closest('li').after('<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0"><span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span><div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0"><div class="d-flex justify-content-between"><select id="itinerary_arrival" name="itinerary_arrival"  required class="form-select form-select-sm text-start"><option value="">Search</option><option value="1">Chennai</option><option value="2">Kanyakumari</option><option value="3">Puducherry</option></select><button type="button" class="btn btn-sm btn-label-primary waves-effect mx-1 my-1" onclick="addItinerarySubmit()">Submit</button></div><div class="mt-1 mb-1 row"><small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small><div class="col-auto px-0"><div class="input-group input_group_plus_minus input_itinerary_list"><input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity"><input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field"><input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity"></div></div></div></div></li>');
            });

            $('#itinerary_3').on('click', '#btn_add_more', function() {
                $(this).closest('li').after('<li class="list-group-item drag-item cursor-move d-flex justify-content-between align-items-center timeline-item ms-1 ps-4  border-left-dashed border-0 border-start p-0"><span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span><div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0"><div class="d-flex justify-content-between"><select id="itinerary_arrival" name="itinerary_arrival"  required class="form-select form-select-sm text-start"><option value="">Search</option><option value="1">Chennai</option><option value="2">Kanyakumari</option><option value="3">Puducherry</option></select><button type="button" class="btn btn-sm btn-label-primary waves-effect mx-1 my-1" onclick="addItinerarySubmit()">Submit</button></div><div class="mt-1 mb-1 row"><small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small><div class="col-auto px-0"><div class="input-group input_group_plus_minus input_itinerary_list"><input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity"><input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field"><input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity"></div></div></div></div></li>');
            });
        <?php endif; ?>

        <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_daywise') : ?>
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

        function addItinerarySubmit() {
            $("#add_more_itinerary").html('<span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none"><i class="ti ti-map-pin"></i></span><div class="itinerary_timeline_event timeline-event ps-0 pb-0 px-0"><div class="d-flex justify-content-between"><div class="text-start"><h6 class="mb-1 text-capitalize">Chennai (City)</h6><div class="mb-1 row"><small class="col-auto col-form-labeltext-light fw-medium mt-1 text-bold text-light">Nights</small><div class="col-auto px-0"><div class="input-group input_group_plus_minus input_itinerary_list"><input id="input_minus_button" type="button" value="-" class="button-minus" data-field="quantity"><input id="input_plus_minus" type="number" step="1" max="" value="1" name="quantity" class="quantity-field"><input id="input_plus_button" type="button" value="+" class="button-plus" data-field="quantity"></div></div></div></div><div><button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect mx-1"><i class="tf-icons ti ti-edit text-primary"></i></button><button type="button" class="btn btn-sm btn-icon btn-label-danger waves-effect mx-1"><i class="tf-icons ti ti-trash-filled text-danger"></i></button><button type="button" class="btn btn-sm btn-icon btn-label-primary waves-effect" id="btn_add_more"><i class="tf-icons ti ti-circle-plus"></i></button></div></div></div>');
        }
    <?php endif; ?>

    <?php if ($_GET['route'] == 'add' && $_GET['formtype'] == 'itinerary_daywise') : ?>

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


    // To hide loaded Map 
    function showITINERARY_LIST() {
        $.ajax({
            type: 'post',
            url: 'engine/ajax/__ajax_itinerary_list.php?type=show_form',
            success: function(response) {
                $('#showITINERARYLIST').html(response);
            }
        });
    }

    const targetElement_map = document.getElementById('itinerary_map_div');
    targetElement_map.classList.add('d-none');

    document.getElementById('scrollToTopButton').addEventListener('click', function() {
        // Scroll to the top of the page
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        }); // Use smooth scrolling for a smooth transition
    });
</script>
</body>

</html>

<!-- beautify ignore:end -->