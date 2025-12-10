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
                                            <!-- Guide Details Section -->
                                            <h5 class="card-header px-0 py-2 mb-2 text-uppercase border-bottom text-blue-color fw-bold">
                                                Guide Details
                                            </h5>

                                            <!-- Day 1 -->
                                            <h6 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                Entire Itineary | 30 Dec, 2024 Monday to 02 Jan 2025 Thursday
                                            </h6>

                                            <div class="border rounded p-2" style="background-color: #ffeaea !important; border-left: 5px solid #dc3545 !important;">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <!-- Cancellation Details (Left) -->
                                                    <div class="ms-2">
                                                        <h6 class="m-0" style="color: #dc3545; font-size: 1rem; font-weight: 600;">
                                                            Slot 1: 8 AM to 1 PM (Cancelled)
                                                        </h6>
                                                        <p class="mb-1 mt-1" style="color: #495057; font-size: 0.9rem;">
                                                            <strong>Name:</strong> <span style="font-weight: 500;">Saran Venkatesh</span> | <strong>Language:</strong> <span style="font-weight: 500;">Saran Venkatesh</span> | <strong>Defact Type:</strong> <span style="font-weight: 500;">From DVI</span>
                                                        </p>
                                                        <p class="mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                                            <strong>Cancelled On:</strong> 30 Dec, 2024 at 10:00 AM
                                                        </p>
                                                        <p class="mb-1" style="color: #6c757d; font-size: 0.85rem;">
                                                            <strong>Original Amount:</strong> ₹ 500
                                                        </p>
                                                        <p class="mb-0" style="color: #212529; font-size: 0.9rem; font-weight: bold;">
                                                            Refund Amount: ₹ 450 (10% Deduction)
                                                        </p>
                                                    </div>

                                                    <!-- Refund Section (Right) -->
                                                    <div class="text-end me-2" style="align-self: center;">
                                                        <p class="mb-0" style="color: #dc3545; font-size: 0.85rem; font-weight: 500;">
                                                            Refund
                                                        </p>
                                                        <h6 class="m-0" style="color: #dc3545; font-size: 1rem; font-weight: 600;">
                                                            ₹ 450
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="border rounded p-2" style="background-color: #f9f9f9;" id="slot-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0" style="color: #333; font-size: 1rem; font-weight: bold;">
                                                        <strong>Slot 1:</strong> 8 AM to 1 PM | <strong>Slot 2:</strong> 1 AM to 6 PM | <strong>Slot 3:</strong> 6 AM to 9 PM
                                                    </h6>
                                                    <h6 class="mb-0 text-primary">₹ 4500</h6>
                                                </div>
                                                <div class="mt-1">
                                                    <h6 style="color: #555; font-size: 0.9rem;">
                                                        <strong>Name:</strong> Saran Venkatesh | <strong>Language:</strong> <span class="text-primary">English</span>
                                                    </h6>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center mt-1">
                                                    <label for="cancellation_slot3" class="fw-bold me-1" style="color: #555; font-size: 0.9rem;">Cancellation %:</label>
                                                    <input type="number" id="cancellation_slot3" min="0" max="100" class="form-control form-control-sm d-inline-block" style="width: 60px;" placeholder="%" value="10">
                                                    <label for="defect_type_slot3" class="fw-bold ms-3 me-1" style="color: #555; font-size: 0.9rem;">Defect Type:</label>
                                                    <select id="defect_type_slot3" class="form-select form-select-sm d-inline-block" style="width: 150px;">
                                                        <option value="not-available">Not Available</option>
                                                        <option value="time-issue">Time Conflict</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <button class="btn btn-outline-danger btn-sm ms-3">Cancel</button>
                                                </div>
                                            </div>

                                            <!-- Day 1 -->
                                            <h6 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                Date: 30 Dec, 2024 Monday
                                            </h6>

                                            <!-- Slot 1 -->
                                            <div class="border rounded p-2 mb-2" style="background-color: #f9f9f9;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0" style="color: #333; font-size: 1rem; font-weight: bold;">
                                                        <strong>Slot 1:</strong> 8 AM to 1 PM
                                                    </h6>
                                                    <h6 class="mb-0 text-primary">₹ 500</h6>
                                                </div>
                                                <div class="mt-1">
                                                    <h6 style="color: #555; font-size: 0.9rem;">
                                                        <strong>Name:</strong> Saran Venkatesh | <strong>Language:</strong> <span class="text-primary">English</span>
                                                    </h6>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center mt-1">
                                                    <label for="cancellation_slot1" class="fw-bold me-1" style="color: #555; font-size: 0.9rem;">Cancellation %:</label>
                                                    <input type="number" id="cancellation_slot1" min="0" max="100" class="form-control form-control-sm d-inline-block" style="width: 60px;" placeholder="%" value="10">
                                                    <label for="defect_type_slot1" class="fw-bold ms-3 me-1" style="color: #555; font-size: 0.9rem;">Defect Type:</label>
                                                    <select id="defect_type_slot1" class="form-select form-select-sm d-inline-block" style="width: 150px;">
                                                        <option value="not-available">Not Available</option>
                                                        <option value="time-issue">Time Conflict</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <button class="btn btn-outline-danger btn-sm ms-3">Cancel</button>
                                                </div>
                                            </div>

                                            <!-- Slot 2 -->
                                            <div class="border rounded p-2 mb-2" style="background-color: #f9f9f9;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0" style="color: #333; font-size: 1rem; font-weight: bold;">
                                                        <strong>Slot 2:</strong> 1 PM to 6 PM
                                                    </h6>
                                                    <h6 class="mb-0 text-primary">₹ 400</h6>
                                                </div>
                                                <div class="mt-1">
                                                    <h6 style="color: #555; font-size: 0.9rem;">
                                                        <strong>Name:</strong> Saran Venkatesh | <strong>Language:</strong> <span class="text-primary">English</span>
                                                    </h6>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center mt-1">
                                                    <label for="cancellation_slot2" class="fw-bold me-1" style="color: #555; font-size: 0.9rem;">Cancellation %:</label>
                                                    <input type="number" id="cancellation_slot2" min="0" max="100" class="form-control form-control-sm d-inline-block" style="width: 60px;" placeholder="%" value="10">
                                                    <label for="defect_type_slot2" class="fw-bold ms-3 me-1" style="color: #555; font-size: 0.9rem;">Defect Type:</label>
                                                    <select id="defect_type_slot2" class="form-select form-select-sm d-inline-block" style="width: 150px;">
                                                        <option value="not-available">Not Available</option>
                                                        <option value="time-issue">Time Conflict</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <button class="btn btn-outline-danger btn-sm ms-3">Cancel</button>
                                                </div>
                                            </div>

                                            <!-- Day 2 -->
                                            <h6 class="text-uppercase mt-3 mb-2 text-muted fw-bold" style="font-size: 1.1rem;">
                                                Date: 31 Dec, 2024 Tuesday
                                            </h6>

                                            <!-- Slot 3 -->
                                            <div class="border rounded p-2" style="background-color: #f9f9f9;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="m-0" style="color: #333; font-size: 1rem; font-weight: bold;">
                                                        <strong>Slot 1:</strong> 8 AM to 1 PM
                                                    </h6>
                                                    <h6 class="mb-0 text-primary">₹ 600</h6>
                                                </div>
                                                <div class="mt-1">
                                                    <h6 style="color: #555; font-size: 0.9rem;">
                                                        <strong>Name:</strong> Saran Venkatesh | <strong>Language:</strong> <span class="text-primary">English</span>
                                                    </h6>
                                                </div>
                                                <div class="d-flex justify-content-end align-items-center mt-1">
                                                    <label for="cancellation_slot3" class="fw-bold me-1" style="color: #555; font-size: 0.9rem;">Cancellation %:</label>
                                                    <input type="number" id="cancellation_slot3" min="0" max="100" class="form-control form-control-sm d-inline-block" style="width: 60px;" placeholder="%" value="10">
                                                    <label for="defect_type_slot3" class="fw-bold ms-3 me-1" style="color: #555; font-size: 0.9rem;">Defect Type:</label>
                                                    <select id="defect_type_slot3" class="form-select form-select-sm d-inline-block" style="width: 150px;">
                                                        <option value="not-available">Not Available</option>
                                                        <option value="time-issue">Time Conflict</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                    <button class="btn btn-outline-danger btn-sm ms-3">Cancel</button>
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

</body>

</html>