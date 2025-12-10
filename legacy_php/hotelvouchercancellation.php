<?php
include_once("jackus.php");
admin_reguser_protect();

$itinerary_plan_ID = $_GET['cip_id'];

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">
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
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>

    <style>
        .itinerary-header-sticky-element {
            position: sticky;
            top: 0px;
            background-color: #ffffff;
            z-index: 1000;
            box-shadow: 0px 0px 4px 0px rgba(135, 70, 180, 0.2) !important;
        }

        .strikethrough {
            text-decoration: line-through;
            color: gray;
        }

        .sticky-accordion-element {
            position: sticky;
            top: 122px;
            z-index: 1000;
        }
    </style>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <?php include_once('public/__sidebar.php'); ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">

                        <div class="row">
                            <div class="col-12">
                                <div class="itinerary-header-sticky-element card-header sticky-element bg-label-primary p-3 mt-3">
                                    <div class=" d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-4">
                                            <h6 class="m-0 text-blue-color">#DVI2024111174</h6>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-calendar-event text-body ti-sm me-1"></i>
                                                <h6 class="text-capitalize m-0">
                                                    <b>Nov 29, 2024</b> to
                                                    <b>Nov 30, 2024 </b> (<b>1</b> N,
                                                    <b>2 D)
                                                </h6>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Adults<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">2</span></span>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Child<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">0</span></span>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Infants<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2">0</span></span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between my-2">
                                        <h5 class="text-capitalize mb-0">Chennai <i class="tf-icons ti ti-arrow-big-right-lines-filled ti-sm mx-1"></i> Trichy</h5>
                                        <h6 class="card-title mb-sm-0">Guide for Whole Day : <b class="text-danger"><span>No</span></b> / Entry Ticket : <b class="text-danger"><span>No</span></b> / <b class="text-success"><span>Yes</span></b>
                                        </h6>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-0">
                                        <div>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Room Count<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">1</span></span>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Extra Bed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill mx-2">0</span></span>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Child withbed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2">0</span></span>
                                            <span class="mb-0 fs-6 text-gray fw-medium">Child withoutbed<span class="badge badge-center bg-white fw-semi-bold text-gray rounded-pill ms-2">0</span></span>
                                        </div>
                                        <h5 class="card-title mb-sm-0">Guest : <b class="text-primary fs-5"><span>Ariyappan</span></b>
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body rounded-0">
                                                <div id="guide1">
                                                    <div class="d-flex justify-content-between align-items-center py-2">
                                                        <h6 class="m-0" style="color:#4d287b;"><span><input class="form-check-input me-2 guide-rate-checkbox" type="checkbox"></span> Guide
                                                            - <span class="text-primary">English , Slot 1: 8 AM to 1 PM, Slot 2: 1 PM to 6 PM, Slot 3: 6 PM to 9 PM</span>
                                                        </h6>
                                                        <div>
                                                            <h6 class="mb-0 guide-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                        <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                            <span class="text-heading fw-bold">Cancellation % : </span>
                                                            <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                        </div>
                                                        <div class="text-end">
                                                            <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Menu Accordion -->
                                                <div id="accordionIcon" class="accordion accordion-without-arrow">
                                                    <!-- DAY WISE ACCORDION -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header text-body d-flex justify-content-between sticky-accordion-element" id="accordionIconOne">
                                                            <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                                                <div class="w-100 itinerary_daywise_list_tab bg-white py-3">
                                                                    <div class="row">
                                                                        <div class="col-sm-3 col-md-3 col-xxl-3 d-flex align-items-center">
                                                                            <h6 class="mb-0"><span><input class="form-check-input mx-2 hotspot_checkbox" type="checkbox"></span> <b>DAY 1</b> -
                                                                                Wed, Nov 27, 2024
                                                                            </h6>
                                                                        </div>
                                                                        <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                        <div class="col-sm-5 col-md-5 col-xxl-5 text-start d-flex align-items-center">
                                                                            <h6 class="mb-0 d-inline-block text-truncate d-flex align-items-center" data-toggle="tooltip" placement="top" title="Chennai Central">Chennai Central
                                                                            </h6>
                                                                            <span>&nbsp;<i class="ti ti-arrow-big-right-lines"></i>&nbsp;</span>
                                                                            <h6 class="m-0 d-inline-block text-truncate" data-toggle="tooltip" placement="top" title="Chennai Central">Chennai Central
                                                                            </h6>
                                                                        </div>
                                                                        <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                        <div class="d-flex align-items-center col-md-4 justify-content-end">

                                                                            <h5 class="card-title mb-0 fs-6"> Cancellation Charges : <span class="text-blue-color fw-bold fs-5">₹ 5000.00</span></h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </h2>

                                                        <div class="load_ajax_response" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-12 mt-2 mb-3">
                                                                        <div class="tab-pane fade show active">
                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-2 pe-0">
                                                                                <span class="text-heading fw-bold">Defect Type : </span>
                                                                                <select id="hotel_category" name="hotel_category" class="form-control form-select w-px-200">
                                                                                    <option value="">Choose Defect</option>
                                                                                    <option value="1">Defect By Customer</option>
                                                                                    <option value="2">Defect By DVI</option>
                                                                                </select>
                                                                            </div>
                                                                            <div id="guide2">
                                                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                                                    <h6 class="m-0" style="color:#4d287b;"><span><input class="form-check-input me-2 guide-rate-checkbox" type="checkbox"></span> Guide
                                                                                        - <span class="text-primary">English</span>
                                                                                    </h6>
                                                                                    <div>
                                                                                        <h6 class="mb-0 guide-price">₹ 4,500</h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="ms-4 mt-2">
                                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                        <h6 class="m-0">
                                                                                            <span><input class="form-check-input me-2 guide_slot_checkbox" type="checkbox"></span>
                                                                                            Slot 1: 8 AM to 1 PM
                                                                                        </h6>
                                                                                        <h6 class="mb-0 guide_slot_price">₹ 500</h6>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                        <h6 class="m-0">
                                                                                            <span><input class="form-check-input me-2 guide_slot_checkbox" type="checkbox"></span>
                                                                                            Slot 2: 1 PM to 6 PM
                                                                                        </h6>
                                                                                        <h6 class="mb-0 guide_slot_price">₹ 400</h6>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                        <h6 class="m-0">
                                                                                            <span><input class="form-check-input me-2 guide_slot_checkbox" type="checkbox"></span>
                                                                                            Slot 3: 6 PM to 9 PM
                                                                                        </h6>
                                                                                        <h6 class="mb-0 guide_slot_price">₹ 700</h6>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                                                    <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                        <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                        <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                    </div>
                                                                                    <div class="text-end">
                                                                                        <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text">
                                                                                    <i class="ti ti-user"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-12 col-xl-6" style="border-right: 1px solid #c5c5c5;">
                                                                                    <div id="hotspot1">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox" type="checkbox"></span>
                                                                                                    #1 Kapleswara Temple
                                                                                                </h6>

                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div id="hotspot2">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox" type="checkbox"></span>
                                                                                                    #2 Marina Beach
                                                                                                </h6>
                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div id="activity1">
                                                                                        <h6 class="text-primary mb-2">Activity</h6>
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 activity-rate-checkbox" type="checkbox"></span>
                                                                                                    #1 Special Dharsanam
                                                                                                </h6>
                                                                                                <h6 class="mb-0 activity-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 activity_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 activity-price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 activity_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 activity-price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 activity_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 activity-price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 activity_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 activity-price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class=" d-flex align-items-center justify-content-end gap-2 mb-2 pe-0">
                                                                                            <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                            <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text">
                                                                                    <i class="ti ti-map-pin"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-12 col-xl-6" style="border-right: 1px solid #c5c5c5;">
                                                                                    <div id="hotspot1">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox" type="checkbox"></span>
                                                                                                    #3 Kapleswara Temple
                                                                                                </h6>

                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div id="hotspot2">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox" type="checkbox"></span>
                                                                                                    #4 Marina Beach
                                                                                                </h6>
                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text">
                                                                                    <i class="ti ti-map-pin"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-12 col-xl-6" style="border-right: 1px solid #c5c5c5;">
                                                                                    <div id="hotspot1">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox" type="checkbox"></span>
                                                                                                    #5 Kapleswara Temple
                                                                                                </h6>

                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-5 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div id="hotspot2">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 hotspot_title_checkbox" type="checkbox"></span>
                                                                                                    #6 Marina Beach
                                                                                                </h6>
                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Adult 2
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Child 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotspot_rate_checkbox" type="checkbox"></span>
                                                                                                    Infant 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 hotspot_rate_price">₹ 450</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center justify-content-between my-3">
                                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                                <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                                <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                            </div>
                                                                                            <div class="text-end">
                                                                                                <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text">
                                                                                    <i class="ti ti-map-pin"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="text-end">
                                                                                <h6 class="my-3 fw-bold">Total Cancellation Charge for DAY#1: <span class="text-blue-color fw-bold fs-5">₹ 5000.00</span></h6>
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
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body rounded-0">
                                                <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Hotel Details</strong></h5>
                                                <!-- Menu Accordion -->
                                                <div id="accordionIcon" class="accordion accordion-without-arrow">
                                                    <!-- DAY WISE ACCORDION -->
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header text-body d-flex justify-content-between sticky-accordion-element" id="accordionIconOne">
                                                            <div role="button" class="accordion-button shadow-none align-items-center bg-transparent itinerary_daywise_accordion_button_tab p-0" data-bs-toggle="collapse" data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                                                <div class="w-100 itinerary_daywise_list_tab bg-white py-3">
                                                                    <div class="row">
                                                                        <div class="col-sm-8 col-md-8 col-xxl-8 d-flex align-items-center">
                                                                            <h6 class="mb-0"><span><input class="form-check-input mx-2 hotel-checkbox" type="checkbox"></span>
                                                                                Wed, Nov 27, 2024 | <span class="fs-5 text-primary">Fragrant Nature Kollam</span> | <span class="fs-5">Cochin</span>
                                                                            </h6>
                                                                        </div>

                                                                        <?php /* <div class="col-auto d-flex align-items-center"> <span> | </span> </div> */ ?>
                                                                        <div class="d-flex align-items-center col-md-4 justify-content-end">

                                                                            <h5 class="card-title mb-0 fs-6"> Cancellation Charges : <span class="text-blue-color fw-bold fs-5">₹ 5000.00</span></h5>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </h2>

                                                        <div class="load_ajax_response" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-12 mt-2 mb-3">
                                                                        <div class="tab-pane fade show active">
                                                                            <div class=" d-flex align-items-center justify-content-end gap-2 mb-4 pe-0">
                                                                                <span class="text-heading fw-bold">Defect Type : </span>
                                                                                <select id="hotel_category" name="hotel_category" class="form-control form-select w-px-200">
                                                                                    <option value="">Choose Defect</option>
                                                                                    <option value="1">Defect By Customer</option>
                                                                                    <option value="2">Defect By DVI</option>
                                                                                </select>
                                                                            </div>


                                                                            <div class="row">
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div class="overflow-hidden mb-3 border " style="height: 200px;">
                                                                                        <div class="px-3 py-2" style="border-bottom: 1px solid #dddbdb">
                                                                                            <h6 class="text-primary m-0">Hotel Voucher Terms & Condition</h6>
                                                                                        </div>
                                                                                        <div class="text-blue-color p-3" id="vertical-example" style="max-height: 200px; overflow-y: auto;">
                                                                                            <p class="m-0" style="line-height: 27px;">
                                                                                                <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-nowrap overflow-hidden table-bordered">
                                                                                        <table class="table table-hover table-responsive">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th>S.No</th>
                                                                                                    <th>Cancellation Date</th>
                                                                                                    <th>Percentage</th>
                                                                                                    <th>Description</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td>1</td>
                                                                                                    <td>Oct 31, 2024</td>
                                                                                                    <td>50%</td>
                                                                                                    <td>
                                                                                                        <div data-bs-html="true" data-toggle="tooltip" placement="top" title="Cancellation Charges from Total Value" class="cursor-pointer"><img src="assets/img/svg/eye.svg" width="26px" /></div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>2</td>
                                                                                                    <td>Nov 05, 2024</td>
                                                                                                    <td>90%</td>
                                                                                                    <td>
                                                                                                        <div data-bs-html="true" data-toggle="tooltip" placement="top" title="Cancellation Charges from Total Value" class="cursor-pointer"><img src="assets/img/svg/eye.svg" width="26px" /></div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div id="room1">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                                                    Standard Room * 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Child with Bed
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Child without Bed
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Extra Bed
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Breakfast
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 450</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Lunch
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 250</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Dinner
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 100</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                                                        <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                            <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                            <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                        </div>
                                                                                        <div class="text-end">
                                                                                            <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="divider">
                                                                                <div class="divider-text">
                                                                                    <i class="ti ti-building-skyscraper"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div class="overflow-hidden mb-3 border " style="height: 200px;">
                                                                                        <div class="px-3 py-2" style="border-bottom: 1px solid #dddbdb">
                                                                                            <h6 class="text-primary m-0">Hotel Voucher Terms & Condition</h6>
                                                                                        </div>
                                                                                        <div class="text-blue-color p-3" id="vertical-example" style="max-height: 200px; overflow-y: auto;">
                                                                                            <p class="m-0" style="line-height: 27px;">
                                                                                                <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                                                                            </p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-nowrap overflow-hidden table-bordered">
                                                                                        <table class="table table-hover table-responsive">
                                                                                            <thead>
                                                                                                <tr>
                                                                                                    <th>S.No</th>
                                                                                                    <th>Cancellation Date</th>
                                                                                                    <th>Percentage</th>
                                                                                                    <th>Description</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <tr>
                                                                                                    <td>1</td>
                                                                                                    <td>Oct 31, 2024</td>
                                                                                                    <td>50%</td>
                                                                                                    <td>
                                                                                                        <div data-bs-html="true" data-toggle="tooltip" placement="top" title="Cancellation Charges from Total Value" class="cursor-pointer"><img src="assets/img/svg/eye.svg" width="26px" /></div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td>2</td>
                                                                                                    <td>Nov 05, 2024</td>
                                                                                                    <td>90%</td>
                                                                                                    <td>
                                                                                                        <div data-bs-html="true" data-toggle="tooltip" placement="top" title="Cancellation Charges from Total Value" class="cursor-pointer"><img src="assets/img/svg/eye.svg" width="26px" /></div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-xl-6">
                                                                                    <div id="room2">
                                                                                        <div class="mt-2">
                                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                                <h6 class="m-0 text-blue-color">
                                                                                                    <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                                                    Delux Room * 1
                                                                                                </h6>
                                                                                                <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="ms-4 mt-2">
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Child with Bed
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 500</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Child without Bed
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 400</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Extra Bed
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 700</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Breakfast
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 450</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Lunch
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 250</h6>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center mb-1 justify-content-between">
                                                                                                <h6 class="m-0">
                                                                                                    <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                                                    Dinner
                                                                                                </h6>
                                                                                                <h6 class="mb-0 price">₹ 100</h6>
                                                                                            </div>
                                                                                            <!-- Add other options similarly -->
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="ms-3 mt-2">
                                                                                        <h6 class="text-primary mb-2">Amenities</h6>
                                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                                            <h6 class="m-0 text-blue-color">
                                                                                                <span><input class="form-check-input me-2 amentities-rate-checkbox" type="checkbox"></span>
                                                                                                Room Service
                                                                                            </h6>
                                                                                            <h6 class="mb-0 amentities-price">₹ 400</h6>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center justify-content-between mb-3 mt-2">
                                                                                        <div class=" d-flex align-items-center justify-content-end gap-2 mb-0 pe-0">
                                                                                            <span class="text-heading fw-bold">Cancellation % : </span>
                                                                                            <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10">
                                                                                        </div>
                                                                                        <div class="text-end">
                                                                                            <h6 class="mb-0">Cancellation Charge: <span class="fw-bold text-blue-color">₹ 500.00 </span></h6>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text">
                                                                                    <i class="ti ti-building-skyscraper"></i>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex align-items-center justify-content-between">
                                                                                <label class="cursor-pointer text-blue-color" for="cancellation_fee_show"><span><input class="form-check-input me-2" id="cancellation_fee_show" name="cancellation_fee_show" type="checkbox"></span>Cancellation charge display to hotel ? </label>
                                                                                <div class="text-end">
                                                                                    <h6 class="my-3 fw-bold">Total Cancellation Charge for DAY#1: <span class="text-blue-color fw-bold fs-5">₹ 5000.00</span></h6>
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

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body rounded-0">
                                                <h5 class="card-header px-0 py-0 mb-2 text-uppercase"><strong>Vehicle Details</strong></h5>

                                                <table class="table table-hover table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th> <input class="form-check-input me-2 roomtype-rate-checkbox fs-6 select_all_vehicles" type="checkbox"></th>
                                                            <th>VENDOR NAME</th>
                                                            <th>BRANCH NAME</th>
                                                            <th>VEHICLE</th>
                                                            <th>TOTAL QTY</th>
                                                            <th>DEFECT BY</th>
                                                            <th>CANCELLATION %</th>
                                                            <th>TOTAL AMOUNT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="max-width: 60px;">

                                                                <input class="form-check-input me-2 vehicle_rate_checkbox" type="checkbox">

                                                            </td>
                                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="DVI-CHENNAI">DVI-CHENNAI</span></td>
                                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="DVI-CHENNAI">DVI-CHENNAI</span></td>
                                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="SEDAN">SEDAN</span></td>
                                                            <td colspan="1">1 x ₹ 4,759.00</td>
                                                            <td colspan="1"><select id="hotel_category" name="hotel_category" class="form-control form-select w-px-200">
                                                                    <option value="">Choose Defect</option>
                                                                    <option value="1">Defect By Customer</option>
                                                                    <option value="2">Defect By DVI</option>
                                                                </select></td>
                                                            <td colspan="1"><input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10"></td>
                                                            <td colspan="1">
                                                                <span class="vehicle_rate_price" data-toggle="tooltip" data-bs-html="true" data-placement="top" data-bs-original-title="₹ 4,759.00"><b>₹ 4,759.00</b></span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="max-width: 60px;">

                                                                <input class="form-check-input me-2 vehicle_rate_checkbox" type="checkbox">

                                                            </td>
                                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="DVI-MADURAI">DVI-MADURAI</span></td>
                                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="DVI-MADURAI">DVI-MADURAI</span></td>
                                                            <td style="max-width: 50px;" class="text-truncate"><span data-toggle="tooltip" placement="top" data-bs-original-title="SEDAN">SEDAN</span></td>
                                                            <td colspan="1">1 x ₹ 4,759.00</td>
                                                            <td colspan="1"><select id="hotel_category" name="hotel_category" class="form-control form-select w-px-200">
                                                                    <option value="">Choose Defect</option>
                                                                    <option value="1">Defect By Customer</option>
                                                                    <option value="2">Defect By DVI</option>
                                                                </select></td>
                                                            <td colspan="1"><input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field w-px-100 py-1" style="width: 33%;" placeholder="cancel %" value="10"></td>
                                                            <td colspan="1" class="room-price">
                                                                <span class="vehicle_rate_price" data-toggle="tooltip" data-bs-html="true" data-placement="top" data-bs-original-title="₹ 4,759.00"><b>₹ 4,759.00</b></span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="card p-4 px-3">
                                            <div class="row ">
                                                <div class="col-md-6"></div>
                                                <div class="col-12 col-md-6">
                                                    <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                                    <div class="order-calculations">
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-heading">Total Cancellation Service</span>
                                                            <h6 class="mb-0">₹ 2000.00 </h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-heading">Total Cancellation Percentage(%)</span>
                                                            <h6 class="mb-0">5%</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-heading">Total Cancellation Charge</span>
                                                            <h6 class="mb-0">₹ 1900.00</h6>
                                                        </div>
                                                        <hr>
                                                        <div class="d-flex justify-content-between mb-2">
                                                            <span class="text-heading fw-bold">Cancellation Charge</span>
                                                            <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field" style="width: 33%;" placeholder="Enter the Charge" value="₹ 1900">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div class="col-12 col-md-12 text-end">
                                                    <button type="button" class="btn btn-secondary">Cancel</button>
                                                    <button type="button" class="btn btn-primary">Confirm</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->
                </div>
                <!-- Footer -->
                <?php include_once('public/__footer.php'); ?>
                <!-- / Footer -->
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- Vendors JS -->
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <!-- Flat Picker -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Functionality for hotspot_rate_checkbox
            $('.hotspot_rate_checkbox').change(function() {
                // Add or remove strikethrough for the associated price within the same tab
                $(this).closest('.d-flex').find('.hotspot_rate_price').toggleClass('strikethrough', this.checked);
            });

            // Functionality for hotspot_checkbox
            $('.hotspot_checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck all related checkboxes within the same active tab
                let $currentTab = $('.tab-pane.active'); // Get the currently active tab
                $currentTab.find('.hotspot_title_checkbox, .hotspot_rate_checkbox, .activity-rate-checkbox, .guide-rate-checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all prices based on hotspot_checkbox
                $currentTab.find('.hotspot_rate_checkbox').each(function() {
                    $(this).closest('.d-flex').find('.hotspot_rate_price').toggleClass('strikethrough', isChecked);
                });
                $currentTab.find('.activity-rate-checkbox').each(function() {
                    $(this).closest('.d-flex').find('.activity-price').toggleClass('strikethrough', isChecked);
                });
                $currentTab.find('.guide-rate-checkbox').each(function() {
                    $(this).closest('.d-flex').find('.guide-price').toggleClass('strikethrough', isChecked);
                });

                // Toggle strikethrough for room prices
                $currentTab.find('.room-price').toggleClass('strikethrough', isChecked);

                // Toggle strikethrough for amenities prices
                $currentTab.find('.activity-price').toggleClass('strikethrough', isChecked);
                $currentTab.find('.guide-price').toggleClass('strikethrough', isChecked);
            });

            // Functionality for hotspot_title_checkbox
            $('.hotspot_title_checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck the associated hotspot_rate_checkbox elements within the same room
                $(this).closest('#hotspot1, #hotspot2').find('.hotspot_rate_checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all hotspot_rate_checkbox prices within the same room
                $(this).closest('#hotspot1, #hotspot2').find('.hotspot_rate_checkbox').each(function() {
                    $(this).closest('.d-flex').find('.hotspot_rate_price').toggleClass('strikethrough', isChecked);
                });

                // Toggle strikethrough for the associated room price
                $(this).closest('.d-flex').find('.room-price').toggleClass('strikethrough', isChecked);
            });

            // Functionality for activity-rate-checkbox
            $('.activity-rate-checkbox').change(function() {
                let isChecked = this.checked;

                // Toggle strikethrough for the associated amenities price within the same tab
                $(this).closest('.d-flex').find('.activity-price').toggleClass('strikethrough', isChecked);
            });
            // When the main guide-rate-checkbox is toggled
            $('.guide-rate-checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck all associated guide_slot_checkbox elements
                $(this).closest('#guide2').find('.guide_slot_checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all associated guide_slot_price elements
                $(this).closest('#guide2').find('.guide_slot_price').toggleClass('strikethrough', isChecked);

                // Toggle strikethrough for the main guide price
                $(this).closest('#guide2').find('.guide-price').toggleClass('strikethrough', isChecked);
            });

            // When an individual guide_slot_checkbox is toggled
            $('.guide_slot_checkbox').change(function() {
                let isChecked = this.checked;

                // Toggle strikethrough for the associated guide_slot_price
                $(this).closest('.d-flex').find('.guide_slot_price').toggleClass('strikethrough', isChecked);

                // Update the main guide-rate-checkbox state
                let allChecked = $(this).closest('#guide2').find('.guide_slot_checkbox').length ===
                    $(this).closest('#guide2').find('.guide_slot_checkbox:checked').length;

                $(this).closest('#guide2').find('.guide-rate-checkbox').prop('checked', allChecked);

                // Update the strikethrough for the main guide price
                let anyChecked = $(this).closest('#guide2').find('.guide_slot_checkbox:checked').length > 0;
             
            });

            // Functionality for hotel-rate-checkbox
            $('.hotel-rate-checkbox').change(function() {
                // Add or remove strikethrough for the associated price within the same tab
                $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', this.checked);
            });

            // Functionality for hotel-checkbox
            $('.hotel-checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck all related checkboxes within the same active tab
                let $currentTab = $('.tab-pane.active'); // Get the currently active tab
                $currentTab.find('.roomtype-rate-checkbox, .hotel-rate-checkbox, .amentities-rate-checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all prices based on hotel-checkbox
                $currentTab.find('.hotel-rate-checkbox').each(function() {
                    $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                });

                // Toggle strikethrough for room prices
                $currentTab.find('.room-price').toggleClass('strikethrough', isChecked);

                // Toggle strikethrough for amenities prices
                $currentTab.find('.amentities-price').toggleClass('strikethrough', isChecked);
            });

            // Functionality for roomtype-rate-checkbox
            $('.roomtype-rate-checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck the associated hotel-rate-checkbox elements within the same room
                $(this).closest('#room1, #room2').find('.hotel-rate-checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all hotel-rate-checkbox prices within the same room
                $(this).closest('#room1, #room2').find('.hotel-rate-checkbox').each(function() {
                    $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                });

                // Toggle strikethrough for the associated room price
                $(this).closest('.d-flex').find('.room-price').toggleClass('strikethrough', isChecked);
            });

            // Functionality for amentities-rate-checkbox
            $('.amentities-rate-checkbox').change(function() {
                let isChecked = this.checked;

                // Toggle strikethrough for the associated amenities price within the same tab
                $(this).closest('.d-flex').find('.amentities-price').toggleClass('strikethrough', isChecked);
            });

            // Ensure the correct ID is used for the "Select All" checkbox
            $('.select_all_vehicles').change(function() {
                let isChecked = this.checked;

                // Check/uncheck all vehicle_rate_checkbox elements
                $('.vehicle_rate_checkbox').prop('checked', isChecked);

                // Add/remove the strikethrough class for all vehicle_rate_price elements
                $('.vehicle_rate_price').each(function() {
                    $(this).toggleClass('strikethrough', isChecked);
                });
            });

            // Individual checkbox functionality
            $('.vehicle_rate_checkbox').change(function() {
                // Check if all individual checkboxes are checked
                let allChecked = $('.vehicle_rate_checkbox').length === $('.vehicle_rate_checkbox:checked').length;
                $('.select_all_vehicles').prop('checked', allChecked);

                // Toggle the strikethrough class for the corresponding price
                $(this).closest('tr').find('.vehicle_rate_price').toggleClass('strikethrough', this.checked);
            });

            $('body').tooltip({
                selector: '[data-toggle="tooltip"]'
            });
            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            })

        });
    </script>
</body>

</html>