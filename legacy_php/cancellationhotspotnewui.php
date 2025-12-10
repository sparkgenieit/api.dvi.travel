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
                                <div class="col-12">

                                    <div class="card">
                                        <div class="card-body rounded-0">
                                            <!-- Hotspot Entry Tickets Section -->
                                            <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                                Hotspot Entry Tickets
                                            </h5>

                                            <!-- Multiple Hotspots -->
                                            <div class="mb-5">
                                                <!-- Example: Hotspot 1 -->
                                                <div class="mb-3" style="border: 1px solid #ccc; border-radius: 5px; padding: 15px; background-color: #f9f9f9;">
                                                    <!-- Hotspot Name with Total Cost -->
                                                    <div class="d-flex justify-content-between align-items-center mb-0">
                                                        <h6 class="text-uppercase text-muted fw-bold" style="font-size: 1.1rem;">
                                                            #1 Kapleswara Temple
                                                        </h6>
                                                        <span class="text-primary" style="font-size: 1.2rem; font-weight: bold;">₹ 7,200</span>
                                                    </div>

                                                    <!-- Accordion for Ticket Sections -->
                                                    <div class="accordion" id="accordionExample1">
                                                        <div class="row g-3">

                                                            <div class="col-6">
                                                                <div class="accordion" id="accordionExample">
                                                                    <div class="accordion-item shadow-sm">
                                                                        <!-- Accordion Header -->
                                                                        <h2 class="accordion-header" id="activityHeading1">
                                                                            <button
                                                                                class="accordion-button collapsed"
                                                                                type="button"
                                                                                data-bs-toggle="collapse"
                                                                                data-bs-target="#collapseAdults"
                                                                                aria-expanded="false"
                                                                                aria-controls="collapseAdults">
                                                                                <div class="d-flex justify-content-between align-items-center w-100">
                                                                                    <!-- Left: Activity Title and Indicators -->
                                                                                    <div>
                                                                                        <b style="font-size: 0.95rem;">Adults (9)</b>
                                                                                        <div class="d-flex align-items-center gap-3 mt-1 d-none">
                                                                                            <div class="d-flex align-items-center text-success gap-1">
                                                                                                <i class="ti ti-user-check" style="font-size: 1rem;"></i>
                                                                                                <span style="font-size: 0.85rem;">Booked: 9</span>
                                                                                            </div>
                                                                                            <div class="d-flex align-items-center text-danger gap-1">
                                                                                                <i class="ti ti-user-x" style="font-size: 1rem;"></i>
                                                                                                <span style="font-size: 0.85rem;">Cancelled: 4</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <!-- Right: Total Cost with Cancellation Status -->
                                                                                    <div class="text-end">
                                                                                        <div class="d-flex align-items-center justify-content-end">
                                                                                            <b class="text-muted text-decoration-line-through me-2" style="font-size: 1rem;">₹ 6,500</b>
                                                                                            <b class="text-primary" style="font-size: 1rem;">₹ 5,800</b>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </button>
                                                                        </h2>

                                                                        <!-- Accordion Content -->
                                                                        <div id="collapseAdults" class="accordion-collapse collapse border-3 border-bottom border-danger rounded-bottom" aria-labelledby="headingAdults" data-bs-parent="#accordionExample">
                                                                            <div class="accordion-body">
                                                                                <!-- Summary Section -->
                                                                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-check text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span class="text-success" style="font-size: 0.9rem;">Booked: <strong>9</strong></span>
                                                                                    </div>
                                                                                    <div class="d-flex align-items-center">
                                                                                        <i class="ti ti-user-x text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                        <span class="text-danger" style="font-size: 0.9rem;">Cancelled: <strong>4</strong></span>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Section: Active Tickets -->
                                                                                <h6 class="text-success mb-2 d-flex align-items-center">
                                                                                    <i class="ti ti-ticket text-success me-2" style="font-size: 1.2rem;"></i>
                                                                                    Active Tickets
                                                                                </h6>
                                                                                <div class="row mt-2 g-3">
                                                                                    <div class="col-12 mt-0 mb-2">
                                                                                        <div class="border rounded" style="gap: 20px;">
                                                                                            <div class="d-flex justify-content-between align-items-center p-2 border-secondary border-bottom" style="gap: 10px;">
                                                                                                <!-- Left: Ticket Details -->
                                                                                                <div style="flex: 1; text-align: left;">
                                                                                                    <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;">Adult 1</p>
                                                                                                    <small class="text-muted" style="font-size: 0.8rem;">Price: ₹ 600</small>
                                                                                                </div>

                                                                                                <!-- Middle: Cancellation and Defect -->
                                                                                                <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                    <!-- Cancellation Percentage -->
                                                                                                    <div style="flex: 1;">
                                                                                                        <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                        <input type="number" value="10" min="0" max="100" class="form-control form-control-sm">
                                                                                                    </div>

                                                                                                    <!-- Defect Type -->
                                                                                                    <div style="flex: 1;">
                                                                                                        <label for="defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                        <select class="form-select form-select-sm">
                                                                                                            <option value="not-available">Not Available</option>
                                                                                                            <option value="time-issue">Time Conflict</option>
                                                                                                            <option value="other">Other</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <!-- Right: Cancel Button -->
                                                                                                <div style="flex: 0.5; text-align: end;">
                                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                    <button class="btn btn-outline-danger btn-sm waves-effect">Cancel</button>
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="d-flex justify-content-between align-items-center p-2 border-secondary" style="gap: 10px;">
                                                                                                <!-- Left: Ticket Details -->
                                                                                                <div style="flex: 1; text-align: left;">
                                                                                                    <p class="mb-0" style="color: #495057; font-size: 0.9rem; font-weight: 500;">Adult 2</p>
                                                                                                    <small class="text-muted" style="font-size: 0.8rem;">Price: ₹ 600</small>
                                                                                                </div>

                                                                                                <!-- Middle: Cancellation and Defect -->
                                                                                                <div class="d-flex align-items-center justify-content-between" style="flex: 2; gap: 10px;">
                                                                                                    <!-- Cancellation Percentage -->
                                                                                                    <div style="flex: 1;">
                                                                                                        <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Cancellation %</label>
                                                                                                        <input type="number" value="10" min="0" max="100" class="form-control form-control-sm">
                                                                                                    </div>

                                                                                                    <!-- Defect Type -->
                                                                                                    <div style="flex: 1;">
                                                                                                        <label for="defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057; text-align: left;">Defect Type</label>
                                                                                                        <select class="form-select form-select-sm">
                                                                                                            <option value="not-available">Not Available</option>
                                                                                                            <option value="time-issue">Time Conflict</option>
                                                                                                            <option value="other">Other</option>
                                                                                                        </select>
                                                                                                    </div>
                                                                                                </div>

                                                                                                <!-- Right: Cancel Button -->
                                                                                                <div style="flex: 0.5; text-align: end;">
                                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                                    <button class="btn btn-outline-danger btn-sm waves-effect">Cancel</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Divider -->
                                                                                <hr class="my-4 mt-2 mb-3 border-bottom">

                                                                                <!-- Section: Cancelled Tickets -->
                                                                                <h6 class="text-danger mb-3 d-flex align-items-center">
                                                                                    <i class="ti ti-ticket-off text-danger me-2" style="font-size: 1.2rem;"></i>
                                                                                    <span>Cancelled Tickets</span>
                                                                                </h6>

                                                                                <div class="row g-3">
                                                                                    <!-- Cancelled Ticket Example -->
                                                                                    <div class="col-12">
                                                                                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                            <!-- Left Side: Ticket Details -->
                                                                                            <div>
                                                                                                <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;">Adult 3 (Cancelled)</p>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: 30 Dec, 2024 at 10:00 AM</small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: From DVI</small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> ₹ 500</small>
                                                                                                <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: ₹ 450 (10% Deduction)</p>
                                                                                            </div>
                                                                                            <!-- Right Side: Refunded Amount -->
                                                                                            <div class="text-center">
                                                                                                <span class="text-danger" style="font-size: 0.85rem; font-weight: 500;">Refund</span>
                                                                                                <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;">₹ 450</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                            <!-- Left Side: Ticket Details -->
                                                                                            <div>
                                                                                                <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;">Adult 4 (Cancelled)</p>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Cancelled on: 30 Dec, 2024 at 10:00 AM</small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;">Defect Type: From DVI</small>
                                                                                                <small class="d-block" style="font-size: 0.75rem;color:#6c757d;"><strong>Original Amount:</strong> ₹ 500</small>
                                                                                                <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: ₹ 450 (10% Deduction)</p>
                                                                                            </div>
                                                                                            <!-- Right Side: Refunded Amount -->
                                                                                            <div class="text-center">
                                                                                                <span class="badge bg-danger" style="font-size: 0.75rem;">Refunded</span>
                                                                                                <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;">₹ 450</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-12">
                                                                                        <div class="d-flex justify-content-between align-items-center p-3" style="background-color: #ffeaea; border-left: 5px solid #dc3545; border-radius: 5px;">
                                                                                            <!-- Left Side: Ticket Details -->
                                                                                            <div>
                                                                                                <p class="m-0 fw-bold text-danger" style="font-size: 0.9rem; color: #495057;">Adult 5 (Cancelled)</p>
                                                                                                <small class="d-block" style="font-size: 0.75rem; color:#6c757d;">Cancelled on: 30 Dec, 2024 at 10:00 AM</small>
                                                                                                <small class="d-block" style="font-size: 0.75rem; color:#6c757d;">Defect Type: From DVI</small>
                                                                                                <small class="d-block" style="font-size: 0.75rem; color:#6c757d;"><strong>Original Amount:</strong> ₹ 500</small>
                                                                                                <p class="m-0 fw-bold" style="font-size: 0.85rem; color: #212529;">Refund Amount: ₹ 450 (10% Deduction)</p>
                                                                                            </div>
                                                                                            <!-- Right Side: Refunded Amount -->
                                                                                            <div class="text-center">
                                                                                                <span class="badge bg-danger" style="font-size: 0.75rem;">Refunded</span>
                                                                                                <p class="fw-bold text-danger m-0" style="font-size: 0.85rem;">₹ 450</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Refund Summary -->
                                                                                <div class="text-end mt-4">
                                                                                    <p class="m-0 fw-bold"><strong>Total Refund Processed:</strong> ₹ 1,350</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Children Section -->
                                                            <div class="col-6">
                                                                <div class="card accordion-item">
                                                                    <h2 class="accordion-header" id="activityHeading1">
                                                                        <button
                                                                            class="accordion-button collapsed w-100 d-flex align-items-center"
                                                                            type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#childrenAccordion1" aria-expanded="false" aria-controls="childrenAccordion1">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Children (3)</b>
                                                                                    <div class="d-flex align-items-center gap-3 mt-1 d-none">
                                                                                        <div class="d-flex align-items-center text-success gap-1">
                                                                                            <i class="ti ti-user-check" style="font-size: 1rem;"></i>
                                                                                            <span style="font-size: 0.85rem;">Booked: 9</span>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center text-danger gap-1">
                                                                                            <i class="ti ti-user-x" style="font-size: 1rem;"></i>
                                                                                            <span style="font-size: 0.85rem;">Cancelled: 4</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Right: Total Cost with Cancellation Status -->
                                                                                <div class="text-end">
                                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                                        <b class="text-primary" style="font-size: 1rem;">₹ 2,100</b>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="childrenAccordion1" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <!-- Add content for Children section here -->
                                                                            Children content goes here.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Infants Section -->
                                                            <div class="col-6">
                                                                <div class="card accordion-item">
                                                                    <h2 class="accordion-header" id="headingInfants1">
                                                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#infantsAccordion1" aria-expanded="false" aria-controls="infantsAccordion1">
                                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                                <!-- Left: Activity Title and Indicators -->
                                                                                <div>
                                                                                    <b style="font-size: 0.95rem;">Infants (4)</b>
                                                                                    <div class="d-flex align-items-center gap-3 mt-1 d-none">
                                                                                        <div class="d-flex align-items-center text-success gap-1">
                                                                                            <i class="ti ti-user-check" style="font-size: 1rem;"></i>
                                                                                            <span style="font-size: 0.85rem;">Booked: 9</span>
                                                                                        </div>
                                                                                        <div class="d-flex align-items-center text-danger gap-1">
                                                                                            <i class="ti ti-user-x" style="font-size: 1rem;"></i>
                                                                                            <span style="font-size: 0.85rem;">Cancelled: 4</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <!-- Right: Total Cost with Cancellation Status -->
                                                                                <div class="text-end">
                                                                                    <div class="d-flex align-items-center justify-content-end">
                                                                                        <b class="text-primary" style="font-size: 1rem;">₹ 1,600</b>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </button>
                                                                    </h2>
                                                                    <div id="infantsAccordion1" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                                                        <div class="accordion-body">
                                                                            <!-- Add content for Infants section here -->
                                                                            Infants content goes here.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Example: Add more hotspots in the same structure -->
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

</body>

</html>