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
$hotel_id = $_GET['hotel_id'];

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Hotel Preview</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="./assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="./assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/animate-css/animate.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/sweetalert2/sweetalert2.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="./assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <!-- Page CSS -->

    <link rel="stylesheet" href="./assets/vendor/css/pages/page-user-view.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="./assets/vendor/css/pages/ui-carousel.css" />
    <link rel="stylesheet" href="./assets/vendor/css/pages/swiper.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/swiper/swiper.css" />



    <!-- Page CSS -->

    <link rel="stylesheet" href="./assets/vendor/css/pages/app-calendar.css" />
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="./assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="./assets/js/config.js"></script>

</head>

<body>
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
                                <h4 class="font-weight-bold">Hotel Over All Preview</h4>
                            </div>
                            <div class="my-3">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">
                                                <i class="tf-icons ti ti-home mx-2"></i>
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item " aria-current="page">Hotels</li>
                                        <li class="breadcrumb-item active" aria-current="page">Hotel Over All Preview</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                        <!-- User Content -->
                        <div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
                            <ul class="nav p-2 nav-pills card-header-pills " role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button type="button" class="nav-link active shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#basic_info" aria-controls="basic_info" aria-selected="true" fdprocessedid="pg55hh">Basic Info</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#rooms_details" aria-controls="rooms_details" aria-selected="false" fdprocessedid="rkjecy" tabindex="-1">Rooms Details</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#amenities" aria-controls="amenities" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Amenities</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#price_book" aria-controls="price_book" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Price Book</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#booking_history" aria-controls="booking_history" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Booking History</button>
                                </li>
                            </ul>
                        </div>
                        <div class="">
                            <div class="tab-content p-0" id="pills-tabContent">
                                <div class="tab-pane card p-4 mb-3 fade show active" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div class="row">
                                        <h4 class="text-primary">Basic Info</h4>
                                        <div class="col-md-3">
                                            <label>Hotel Name</label>
                                            <p class="text-light">Taj Hotel</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Code</label>
                                            <p class="text-light">Taj2792</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Mobile </label>
                                            <p class="text-light">892792092</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Email</label>
                                            <p class="text-light">taj@gmail.com</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Place</label>
                                            <p class="text-light">Anna Nagar</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Hotel Category</label>
                                            <p class="text-light">shfasfch</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Country</label>
                                            <p class="text-light">India</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>State</label>
                                            <p class="text-light">Tmail Nadu</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>City</label>
                                            <p class="text-light">Rajapalayam</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Pincode</label>
                                            <p class="text-light">666666</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Latitude</label>
                                            <p class="text-light">29027</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Longitude</label>
                                            <p class="text-light">927822</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Address</label>
                                            <p class="text-light">342 anna nagar street chennai</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label> Hotel Status</label>
                                            <p class="text-success fw-bold">Active</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="rooms_details" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <div class="card p-4 mb-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <h5 class="text-primary">Rooms Details</h5>
                                                    <div class="col-md-3">
                                                        <label>Room Title</label>
                                                        <p class="text-light">Deluxe</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Room Reference Code</label>
                                                        <p class="text-light">46727</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Adult (Above 12 Years)</label>
                                                        <p class="text-light">2</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Children (0 t0 3 Years)</label>
                                                        <p class="text-light">1</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Children (3 to 12 Years)</label>
                                                        <p class="text-light">1</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Air Conditioner</label>
                                                        <p class="text-light">Yes</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Food Applicable</label>
                                                        <p class="text-light">BreakFast</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Check In Time</label>
                                                        <p class="text-light">2023-05-03 </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Check Out Time</label>
                                                        <p class="text-light">2023-05-05 </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h5 class="text-primary">Gallery</h5>
                                                    <div class="col-md-1  my-2">
                                                        <div class="room-details-image-head">
                                                            <img src="../head/assets/img/dummy/hotel1.jpg" style="width:100%" onclick="openModal();currentSlide(1)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel2.jpeg" style="width:100%" onclick="openModal();currentSlide(2)" class="room-details-shadow cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel3.jpeg" onclick="openModal();currentSlide(3)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel4.jpeg" onclick="openModal();currentSlide(4)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel1.jpg" onclick="openModal();currentSlide(5)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel2.jpeg" onclick="openModal();currentSlide(6)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel3.jpeg" onclick="openModal();currentSlide(7)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel4.jpeg" onclick="openModal();currentSlide(8)" class="room-details-shadow img-fluid cursor rounded">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <h5 class="text-primary">Rooms Details</h5>
                                                    <div class="col-md-3">
                                                        <label>Room Title</label>
                                                        <p class="text-light">Super Deluxe</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Room Reference Code</label>
                                                        <p class="text-light">46727</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Adult (Above 12 Years)</label>
                                                        <p class="text-light">2</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Children (0 t0 3 Years)</label>
                                                        <p class="text-light">1</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Total Children (3 to 12 Years)</label>
                                                        <p class="text-light">1</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Air Conditioner</label>
                                                        <p class="text-light">Yes</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Food Applicable</label>
                                                        <p class="text-light">BreakFast</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Check In Time</label>
                                                        <p class="text-light">2023-05-03 </p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Check Out Time</label>
                                                        <p class="text-light">2023-05-05 </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h5 class="text-primary">Gallery</h5>
                                                    <div class="col-md-1 my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel1.jpg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel2.jpeg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel3.jpeg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel4.jpeg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel3.jpeg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel4.jpeg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1 my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel1.jpg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1  my-2 ">
                                                        <div>
                                                            <img src="../head/assets/img/dummy/hotel2.jpeg" alt="" class="img-fluid rounded">
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-md-1  my-2 mx-auto ">
                                                <div>
                                                    <img src="../head/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                                    <h5 class="ms-2 hotel_preview_no_image">No Gallery Found</h5>
                                                </div>
                                            </div> -->
                                                </div>
                                                <hr />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade card p-4 mb-3" id="amenities" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <div class="row justify-content-between">
                                        <h4 class="text-primary">Amenities</h4>
                                        <div class="col-md-2">
                                            <label>Amenities Title</label>
                                            <p class="text-light">Indoor</p>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Amenities Cost</label>
                                            <p class="text-light">50</p>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Per Hour Cost </label>
                                            <p class="text-light">10</p>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Per Day Cost </label>
                                            <p class="text-light">100</p>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Quantity</label>
                                            <p class="text-light">1</p>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                                <div class="tab-pane fade card p-4 mb-3" id="price_book" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <div class=" app-calendar-wrapper">
                                        <div class="row g-0">

                                            <!-- Calendar & Modal -->
                                            <div class="col app-calendar-content">
                                                <div class="card shadow-none border-0">
                                                    <div class="card-body pb-0">
                                                        <!-- FullCalendar -->
                                                        <div id="calendar"></div>
                                                    </div>
                                                </div>
                                                <div class="app-overlay"></div>
                                                <!-- FullCalendar Offcanvas -->
                                                <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar" aria-labelledby="addEventSidebarLabel">
                                                </div>
                                            </div>
                                            <!-- /Calendar & Modal -->
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade card p-4 mb-3" id="booking_history" role="tabpanel" aria-labelledby="pills-contact-tab">
                                </div>
                            </div>


                            <!-- Room Details Modal -->
                            <div id="myModal" class="modal room-details-modal">
                                <span class="close room-details-close cursor" onclick="closeModal()">&times;</span>
                                <a class="prev room-details-prev mx-3" onclick="plusSlides(-1)">&#10094;</a>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel1.jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel2.jpeg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel3.jpeg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel4.jpeg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel1.jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel2.jpeg" class="rounded" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel3.jpeg" class="rounded" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src="../head/assets/img/dummy/hotel4.jpeg" class="rounded" height="700px">
                                    </div>
                                </div>

                                <a class="next room-details-next mx-3" onclick="plusSlides(1)">&#10095;</a>
                            </div>
                        </div>
                        <!-- Room Details Modal -->
                    </div>
                    <!-- Room Details Modal -->

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
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets/vendor/libs/hammer/hammer.js"></script>
    <script src="./assets/vendor/libs/i18n/i18n.js"></script>
    <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="./assets/vendor/libs/moment/moment.js"></script>
    <script src="./assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="./assets/vendor/libs/sweetalert2/sweetalert2.js"></script>
    <script src="./assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="./assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="./assets/vendor/libs/select2/select2.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="./assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="./assets/js/_jquery.dataTables.min.js"></script>
    <script src="./assets/js/_dataTables.buttons.min.js"></script>
    <script src="./assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="./assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="./assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="./assets/js/_js_buttons.html5.min.js"></script>
    <script src="./assets/vendor/libs/fullcalendar/fullcalendar.js"></script>

    <!-- CALENDAR JS -->
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/moment/moment.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="./assets/js/modal-edit-user.js"></script>
    <script src="./assets/js/app-user-view.js"></script>
    <script src="./assets/js/app-user-view-account.js"></script>
    <!-- Vendors JS -->

    <script src="./assets/vendor/libs/moment/moment.js"></script>
    <script src="./assets/vendor/libs/swiper/swiper.js"></script>



    <!-- Page JS -->
    <script src="./assets/js/app-calendar-events.js"></script>
    <script src="./assets/js/app-hotel-calendar.js"></script>
    <script src="./assets/js/ui-carousel.js"></script>
    <script>
        $(document).ready(function() {
            $('#rooms_LIST').DataTable({
                dom: 'Blfrtip',
                "bFilter": false,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                   
                ],
                initComplete: function() {
                    $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

                    $('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

                    $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');

                
                },
                ajax: {
                    "url": 'engine/json/JSONhotelpreview_roomLIST.php?hotel_id=<?= $hotel_id; ?>',
                    "type": "GET"
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "room_title"
                    }, //1
                    {
                        data: "room_ref_code"
                    }, //2
                    {
                        data: "total_persons"
                    }, //3
                    {
                        data: "food_applicable"
                    }, //4
                    {
                        data: "status"
                    }, //5
                ],
                columnDefs: [{
                    "targets": 5,
                    "data": "status",
                    "render": function(data, type, row, full) {
                        switch (data) {
                            case '1':
                                return '<span class="badge bg-label-success p-2 rounded">Active</span>';
                                break;
                            case '0':
                                return '<span class="badge bg-label-danger p-2 rounded">In_Active</span>';
                                break;
                        }
                    }
                }],
            });
            $('#amenities_LIST').DataTable({
                dom: 'Blfrtip',
                "bFilter": false,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                   
                ],
                initComplete: function() {
                    $('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

                    $('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

                    $('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');

                   
                },
                ajax: {
                    "url": 'engine/json/JSONhotelpreview_amenityLIST.php?hotel_id=<?= $hotel_id; ?>',
                    "type": "GET"
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "amenities_title"
                    }, //1
                    {
                        data: "amenities_code"
                    }, //2
                    {
                        data: "per_hour_cost"
                    }, //3
                    {
                        data: "per_day_cost"
                    }, //4
                    {
                        data: "quantity"
                    }, //5
                    {
                        data: "status"
                    }, //6
                ],
                columnDefs: [{
                    "targets": 6,
                    "data": "status",
                    "render": function(data, type, row, full) {
                        switch (data) {
                            case '1':
                                return '<span class="badge bg-label-success p-2 rounded">Active</span>';
                                break;
                            case '0':
                                return '<span class="badge bg-label-danger p-2 rounded">In_Active</span>';
                                break;
                        }
                    }
                }],
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.form-check-input');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('click', function() {
                    checkboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                });
            });
        });

        "use strict";
        flatpickr(".inline-calendar", {
            dateFormat: "Y-m-d", // Format: Year-Month-Day
            defaultDate: "today", // Initial date
            enableTime: false, // Disable time selection
            inline: true
        });

        let date = new Date,
            nextDay = new Date((new Date).getTime() + 864e5),
            nextMonth = 11 === date.getMonth() ? new Date(date.getFullYear() + 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() + 1, 1),
            prevMonth = 11 === date.getMonth() ? new Date(date.getFullYear() - 1, 0, 1) : new Date(date.getFullYear(), date.getMonth() - 1, 1);

        let events = []; // Initialize an empty array for events.

        document.addEventListener("DOMContentLoaded", function() {
            // Use Fetch API to load the JSON file.
            fetch("hotel_filter_calendar_event_room_data.php")
                .then((response) => response.json())
                .then((data) => {
                    // Now you can work with the events data as before.
                    events = data;
                    const v = document.getElementById("calendar");

                    const r = new Calendar(v, {
                        initialView: "dayGridMonth",
                        events: events,
                        plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
                        editable: true,
                        dateClick: function(e) {
                            const clickedDate = e.date;
                            var date = new Date(clickedDate),
                                yr = date.getFullYear(),
                                month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
                                day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate(),
                                newDate = yr + '-' + month + '-' + day;
                            console.log(newDate);
                        },
                        headerToolbar: {
                            left: "prev,next today",
                            center: "title",
                            right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek", // Include the views you want
                        },
                        buttonText: {
                            today: "Today",
                            month: "Month",
                            week: "Week",
                            day: "Day",
                            list: "List",
                        },
                        eventClassNames: function({
                            event: e
                        }) {
                            const classNames = [];

                            // Check if 'calendar' property exists in the extendedProps
                            if (e.extendedProps && e.extendedProps.calendar) {
                                const calendarColorMap = {
                                    'rooms': 'fc-event-success',
                                    'Business': 'fc-event-primary',
                                    'amenities': 'fc-event-warning',
                                    // Add more calendar-color mappings as needed
                                };

                                const calendarClassName = calendarColorMap[e.extendedProps.calendar];

                                if (calendarClassName) {
                                    classNames.push(calendarClassName);
                                }
                            }

                            return classNames;
                        }

                    });

                    // Assuming you have checkboxes with class "input-filter" for filtering
                    const checkboxes = document.querySelectorAll('.input-filter');
                    const viewAllCheckbox = document.getElementById('selectAll'); // Assuming you have an "View All" checkbox

                    // Function to handle the "View All" checkbox
                    function handleViewAllCheckbox() {
                        const isChecked = viewAllCheckbox.checked;

                        if (isChecked) {
                            filterEvents();
                        } else {
                            r.removeAllEvents();
                        }
                    }

                    // Attach a click event listener to the "View All" checkbox
                    viewAllCheckbox.addEventListener('click', handleViewAllCheckbox);

                    // Function to handle the filtering based on checkboxes
                    function filterEvents() {
                        const selectedFilters = Array.from(checkboxes)
                            .filter(checkbox => checkbox.checked && checkbox.id !== 'selectAll') // Exclude the "View All" checkbox
                            .map(checkbox => checkbox.getAttribute('data-value'));

                        const filteredEvents = events.filter(event => {
                            if (selectedFilters.length === 0 || selectedFilters.includes('all')) {
                                // If "View All" is checked or no specific filters are selected, show all events
                                return true;
                            } else {
                                // Adjust this condition based on your data structure and how you want to filter
                                return selectedFilters.includes(event.extendedProps.calendar.toLowerCase());
                            }
                        });

                        r.removeAllEvents(); // Remove existing events from the calendar
                        r.addEventSource(filteredEvents); // Add filtered events to the calendar
                    }

                    // Attach a click event listener to each checkbox to trigger the filtering
                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('click', filterEvents);
                    });

                    // Initial filtering on page load (if needed)
                    filterEvents();

                    r.render();
                }).catch((error) => {
                    console.error("Error loading events:", error);
                });

            function showPRICEBOOK_MODAL(DATE) {
                $('.show-pricebook-form-data').load('engine/ajax/ajax_show_hote_pricebook_form.php?type=show_form&DT=' + DATE + '', function() {
                    const container = document.getElementById("showPRICEBOOKFORM");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                })
            }


        });
    </script>

    <script>
        // Room Details Image model popup
        function openModal() {
            document.getElementById("myModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        var slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("room-details-slides");
            var dots = document.getElementsByClassName("demo");
            var captionText = document.getElementById("caption");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
            captionText.innerHTML = dots[slideIndex - 1].alt;
        }
    </script>
</body>


</html>