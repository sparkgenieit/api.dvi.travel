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
admin_reguser_protect();
$current_page = 'hotel_gallery.php'; // Set the current page variable
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Hotel</title>

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
                                <h4 class="font-weight-bold">Preview </h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="wizard-validation" class="bs-stepper mt-2">
                                    <div class="bs-stepper-header border-0 justify-content-start py-2">
                                        <div class="step" data-target="#account-details-validation">
                                            <a href="#" type="button" class="step-trigger">
                                                <span class="bs-stepper-circle disble-stepper-title">1</span>
                                                <span class="bs-stepper-label mt-3 ">
                                                    <h5 class="bs-stepper-title disble-stepper-title">Basic Info</h5>
                                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                                </span>
                                            </a>
                                        </div>
                                        <div class="line">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                        <div class="step" data-target="#personal-info-validation">
                                            <a href="" type="button" class="step-trigger">
                                                <span class="bs-stepper-circle disble-stepper-num">2</span>
                                                <span class="bs-stepper-label mt-3">
                                                    <h5 class="bs-stepper-title disble-stepper-num">Rooms Details</h5>
                                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                                </span>
                                            </a>
                                        </div>
                                        <div class="line">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                        <div class="step" data-target="#social-links-validation">
                                            <a href="" type="button" class="step-trigger">
                                                <span class="bs-stepper-circle disble-stepper-num">3</span>
                                                <span class="bs-stepper-label mt-3">
                                                    <h5 class="bs-stepper-title disble-stepper-num">Amenities</h5>
                                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                                </span>
                                            </a>
                                        </div>
                                        <div class="line">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                        <div class="step" data-target="#price-book">
                                            <a href="" type="button" class="step-trigger">
                                                <span class="bs-stepper-circle disble-stepper-title">4</span>
                                                <span class="bs-stepper-label mt-3">
                                                    <h5 class="bs-stepper-title disble-stepper-title">Price Book</h5>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="step" data-target="#price-book">
                                            <a href="" type="button" class="step-trigger">
                                                <span class="bs-stepper-circle disble-stepper-title">5</span>
                                                <span class="bs-stepper-label mt-3">
                                                    <h5 class="bs-stepper-title disble-stepper-title">Gallery</h5>
                                                </span>
                                            </a>
                                        </div>
                                        <div class="line">
                                            <i class="ti ti-chevron-right"></i>
                                        </div>
                                        <div class="step" data-target="#price-book">
                                            <a href="" type="button" class="step-trigger">
                                                <span class="bs-stepper-circle active-stepper">6</span>
                                                <span class="bs-stepper-label mt-3">
                                                    <h5 class="bs-stepper-title">Preview</h5>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card mb-4 p-4">
                                    <div class="row">
                                        <h4 class="text-primary">Basic Info</h4>
                                        <div class="col-md-3">
                                            <label>Hotel Name</label>
                                            <p class="text-light"><?= $hotel_name; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Code</label>
                                            <p class="text-light"><?= $hotel_code; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Mobile </label>
                                            <p class="text-light"><?= $hotel_mobile; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Email</label>
                                            <p class="text-light"><?= $hotel_email; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Place</label>
                                            <p class="text-light"><?= $hotel_place; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Category</label>
                                            <p class="text-light"><?= $hotel_category; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Country</label>
                                            <p class="text-light"><?= $hotel_country; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>State</label>
                                            <p class="text-light"><?= $hotel_state; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>City</label>
                                            <p class="text-light"><?= $hotel_city; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Pincode</label>
                                            <p class="text-light"><?= $hotel_pincode; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Latitude</label>
                                            <p class="text-light"><?= $hotel_latitude; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Longitude</label>
                                            <p class="text-light"><?= $hotel_longitude; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Address</label>
                                            <p class="text-light"><?= $hotel_address; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label> Hotel Status</label>
                                            <p class="text-success fw-bold"><?= $status; ?></p>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <h4 class="text-primary">Rooms Details</h4>
                                        <div class="col-md-3">
                                            <label>Room Title</label>
                                            <p class="text-light"><?= $room_title; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Room Reference Code</label>
                                            <p class="text-light"><?= $room_ref_code; ?></p>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <label>Total Adult (Above 12 Years)</label>
                                            <p class="text-light"><?= $room_ref_code ?></p>
                                        </div> -->
                                        <div class="col-md-3">
                                            <label>Total Max Adults</label>
                                            <p class="text-light"><?= $total_max_adults; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Max Children</label>
                                            <p class="text-light"><?= $total_max_childrens; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Air Conditioner</label>
                                            <p class="text-light"><?= $air_conditioner_availability; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Food Applicable</label>
                                            <p class="text-light"><?= substr($food_applicable, 0, -1); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Check In Time</label>
                                            <p class="text-light"><?= $check_in_time; ?> </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Check Out Time</label>
                                            <p class="text-light"><?= $check_out_time; ?></p>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <h4 class="text-primary">Amenities</h4>
                                        <div class="col-md-3">
                                            <label>Amenities Title</label>
                                            <p class="text-light">Indoor</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Amenities Cost</label>
                                            <p class="text-light">50</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Per Hour Cost </label>
                                            <p class="text-light">10</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Per Day Cost </label>
                                            <p class="text-light">100</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Quantity</label>
                                            <p class="text-light">1</p>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row">
                                        <h4 class="text-primary">Gallery</h4>
                                        <!-- <div class="col-md-2 my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel1.jpg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2  my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel2.jpeg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2  my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel3.jpeg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2  my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel4.jpeg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2  my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel3.jpeg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2  my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel4.jpeg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2 my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel1.jpg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div>
                                        <div class="col-md-2  my-2 ">
                                            <div>
                                                <img src="../head/assets/img/dummy/hotel2.jpeg" alt="" class="img-fluid rounded">
                                            </div>
                                        </div> -->
                                        <!-- <div class="col-md-2  my-2 mx-auto ">
                                    <div>
                                        <img src="../head/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                        <h5 class="ms-2 hotel_preview_no_image">No Gallery Found</h5>
                                    </div>
                                </div> -->
                                    </div>
                                    <div class="d-flex justify-content-between mt-5">
                                        <button type="button" class="btn btn-light waves-effect waves-light">Edit</button>
                                        <button type="button" class="btn btn-primary waves-effect waves-light">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>

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