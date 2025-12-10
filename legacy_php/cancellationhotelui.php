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
                                            <!-- Hotel Details Section -->
                                            <h5 class="card-header px-0 py-2 mb-3 text-uppercase border-bottom text-blue-color fw-bold">
                                                Hotel Details
                                            </h5>

                                            <!-- Accordion for Multiple Hotels -->
                                            <div class="accordion" id="hotelAccordion">
                                                <!-- Hotel 1 -->
                                                <div class="accordion-item mb-3" style="border: 1px solid #dee2e6; border-radius: 5px; background-color: #f8f9fa;">
                                                    <h2 class="accordion-header" id="hotelHeading1">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#hotelDetails1" aria-expanded="false" aria-controls="hotelDetails1" style="background-color: #ffffff;">
                                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                                <span><strong>Wed, Nov 27, 2024</strong> | Fragrant Nature Kollam | Cochin</span>
                                                                <div class="text-end">
                                                                    <strong class="text-primary">₹ 8,500</strong>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="hotelDetails1" class="accordion-collapse collapse" aria-labelledby="hotelHeading1" data-bs-parent="#hotelAccordion">
                                                        <div class="accordion-body" style="background-color: #ffffff; border-top: 1px solid #dee2e6;">
                                                            <!-- Cancel Entire Day Button -->
                                                            <div class="text-end mt-3">
                                                                <button class="btn btn-danger btn-sm">Cancel Entire Day</button>
                                                            </div>

                                                            <!-- Rooms and Items Section -->
                                                            <div class="mt-3">
                                                                <!-- Room 1 -->
                                                                <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="fw-bold text-primary mb-0">Standard Room * 1</h6>
                                                                        <div>
                                                                            <span class="text-primary fw-bold">₹ 4,500</span>
                                                                            <button class="btn btn-outline-danger btn-sm ms-3">Cancel Room</button>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <!-- Items under Room -->
                                                                    <div class="row g-3">
                                                                        <!-- Extra Bed -->
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                <!-- Left Section: Ticket Details -->
                                                                                <div style="flex: 1;">
                                                                                    <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;">Extra Bed</p>
                                                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Price: ₹ 500</small>
                                                                                </div>

                                                                                <!-- Middle Section: Cancellation Percentage -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                    <input id="cancellation-percentage" type="number" value="10" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;">
                                                                                </div>

                                                                                <!-- Middle Section: Defect Type -->
                                                                                <div style="width: 140px;">
                                                                                    <label for="defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                    <select id="defect-type" class="form-select form-select-sm" style="width: 100%;">
                                                                                        <option value="">Reason</option>
                                                                                        <option value="dvi">From DVI</option>
                                                                                        <option value="guest">From Guest</option>
                                                                                    </select>
                                                                                </div>

                                                                                <!-- Right Section: Cancel Button -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                    <button id="cancel-button" class="btn btn-outline-danger btn-sm w-100 waves-effect">Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Child with Bed -->
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                <!-- Left Section: Ticket Details -->
                                                                                <div style="flex: 1;">
                                                                                    <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;">Child with bed</p>
                                                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Price: ₹ 500</small>
                                                                                </div>

                                                                                <!-- Middle Section: Cancellation Percentage -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                    <input id="cancellation-percentage" type="number" value="10" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;">
                                                                                </div>

                                                                                <!-- Middle Section: Defect Type -->
                                                                                <div style="width: 140px;">
                                                                                    <label for="defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                    <select id="defect-type" class="form-select form-select-sm" style="width: 100%;">
                                                                                        <option value="">Reason</option>
                                                                                        <option value="dvi">From DVI</option>
                                                                                        <option value="guest">From Guest</option>
                                                                                    </select>
                                                                                </div>

                                                                                <!-- Right Section: Cancel Button -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                    <button id="cancel-button" class="btn btn-outline-danger btn-sm w-100 waves-effect">Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Add more rooms -->
                                                            <div class="mt-3">
                                                                <!-- Room 1 -->
                                                                <div class="border p-3 mb-3" style="border-radius: 5px; background-color: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="fw-bold text-primary mb-0">Delux Room * 1</h6>
                                                                        <div>
                                                                            <span class="text-primary fw-bold">₹ 4,500</span>
                                                                            <button class="btn btn-outline-danger btn-sm ms-3">Cancel Room</button>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    <!-- Items under Room -->
                                                                    <div class="row g-3">
                                                                        <!-- Extra Bed -->
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                <!-- Left Section: Ticket Details -->
                                                                                <div style="flex: 1;">
                                                                                    <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;">Extra Bed</p>
                                                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Price: ₹ 500</small>
                                                                                </div>

                                                                                <!-- Middle Section: Cancellation Percentage -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                    <input id="cancellation-percentage" type="number" value="10" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;">
                                                                                </div>

                                                                                <!-- Middle Section: Defect Type -->
                                                                                <div style="width: 140px;">
                                                                                    <label for="defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                    <select id="defect-type" class="form-select form-select-sm" style="width: 100%;">
                                                                                        <option value="">Reason</option>
                                                                                        <option value="dvi">From DVI</option>
                                                                                        <option value="guest">From Guest</option>
                                                                                    </select>
                                                                                </div>

                                                                                <!-- Right Section: Cancel Button -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                    <button id="cancel-button" class="btn btn-outline-danger btn-sm w-100 waves-effect">Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <!-- Child with Bed -->
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex align-items-center p-3 border rounded" style="gap: 20px;">
                                                                                <!-- Left Section: Ticket Details -->
                                                                                <div style="flex: 1;">
                                                                                    <p id="ticket-details" class="m-0 fw-bold" style="font-size: 0.9rem;">Child with bed</p>
                                                                                    <small class="text-muted d-block" style="font-size: 0.75rem;">Price: ₹ 500</small>
                                                                                </div>

                                                                                <!-- Middle Section: Cancellation Percentage -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancellation-percentage" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Cancellation %</label>
                                                                                    <input id="cancellation-percentage" type="number" value="10" min="0" max="100" class="form-control form-control-sm text-center" style="width: 100%;">
                                                                                </div>

                                                                                <!-- Middle Section: Defect Type -->
                                                                                <div style="width: 140px;">
                                                                                    <label for="defect-type" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">Defect Type</label>
                                                                                    <select id="defect-type" class="form-select form-select-sm" style="width: 100%;">
                                                                                        <option value="">Reason</option>
                                                                                        <option value="dvi">From DVI</option>
                                                                                        <option value="guest">From Guest</option>
                                                                                    </select>
                                                                                </div>

                                                                                <!-- Right Section: Cancel Button -->
                                                                                <div style="width: 100px;">
                                                                                    <label for="cancel-button" class="d-block mb-1" style="font-size: 0.75rem; color: #495057;">&nbsp;</label>
                                                                                    <button id="cancel-button" class="btn btn-outline-danger btn-sm w-100 waves-effect">Cancel</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Cancellation Policy and Terms -->
                                                            <div class="row g-3 mt-3">
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-uppercase">Cancellation Policy</h6>
                                                                    <ul>
                                                                        <li>Oct 31, 2024: 50% Cancellation Fee</li>
                                                                        <li>Nov 15, 2024: 20% Cancellation Fee</li>
                                                                    </ul>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6 class="fw-bold text-uppercase">Terms & Conditions</h6>
                                                                    <ul>
                                                                        <li>Camera fee at monuments.</li>
                                                                        <li>Monument or Temple Entrance Fees.</li>
                                                                        <li>Boat ride.</li>
                                                                        <li>Insurance.</li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Repeat for other hotels -->
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