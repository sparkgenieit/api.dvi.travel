<?php
include_once('jackus.php');
admin_reguser_protect();
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">

    <!-- Fonts -->
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
    <link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css">
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        .blink {
            animation: blink 2s steps(5, start) infinite;
            -webkit-animation: blink 1s steps(5, start) infinite;
        }

        @keyframes blink {
            to {
                visibility: hidden;
            }
        }

        @-webkit-keyframes blink {
            to {
                visibility: hidden;
            }
        }

        .noblink {
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar ">
        <div class="layout-container">

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Menu -->
                <?php include_once('public/__sidebar.php'); ?>
                <!-- / Menu -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="itinerary-header-sticky-element sticky-element bg-label-primary p-3 mb-3 d-flex align-items-center justify-content-between" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                                    <div>
                                        <div class="d-flex align-items-center gap-4 mb-2">
                                            <h6 class="m-0 text-blue-color">#CQ-DVI202408-110</h6>
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-calendar-event text-body ti-sm me-1"></i>
                                                <h6 class="text-capitalize m-0">
                                                    <b>Aug 22, 2024</b> to
                                                    <b>Aug 28, 2024</b> (<b>6</b> N,
                                                    <b>7</b> D)
                                                </h6>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="m-0">Chennai Central Railway <span><i class="ti ti-arrow-big-right-lines"></i></span> Chennai Central Railway </h6>
                                        </div>
                                    </div>

                                    <div>
                                        <a href="dailymoment.php" type="button" class="btn btn-sm btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back
                                            to List</a>
                                    </div>

                                </div>
                                <div>
                                    <div id="accordionIcon" class="accordion mt-3 accordion-without-arrow">
                                        <div class="accordion-item card  p-3" id="accordionIconTwo">

                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#accordionIcon-2" aria-controls="accordionIcon-2">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="m-0 fs-6">DAY 1 - Mon, Sep 02, 2024</p>
                                                        <h6 class="m-0">Chennai Central Railway <span><i class="ti ti-arrow-big-right-lines"></i></span> Chennai Central Railway </h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="badge badge-center rounded-pill bg-label-secondary p-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#uploadimage"><img src="../head/assets/img/image.png" width="20px" /></span>
                                                    <button type="button" class="btn btn-outline-warning waves-effect ps-3 py-2" data-bs-toggle="modal" data-bs-target="#review"><i class="ti ti-star me-2"></i>Review</button>
                                                    <button type="button" class="btn btn-outline-danger waves-effect ps-3 py-2" data-bs-toggle="modal" data-bs-target="#imageupload">+ Upload Image</button>
                                                    <button type="button" class="btn btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addcharge">+ Add Charge</button>
                                                </div>
                                            </div>

                                            <div id="accordionIcon-2" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                <div class="accordion-body">
                                                    <ul class="timeline pt-3 px-3 mb-0">
                                                        <li class="mb-3">
                                                            <div style="border-radius:3px;" class="px-3 py-2 rounded-3 bg-label-warning">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark p-2"><img src="../head/assets/img/tour-guide.png" width="24px" height="24px"></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0" style="color:#4d287b;">Guide
                                                                                    Name - <span class="text-primary">Saran</span>
                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">1</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Varaha Cave</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- activity -->


                                                                <div class="mb-2">
                                                                    <hr />
                                                                    <h6 class="my-2">Activity</h6>
                                                                    <div class="d-flex align-items-center justify-content-between bg-label-white rounded mt-2 ms-3 px-2 p-1">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-send rounded-circle text-primary"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">#1 Boating at Chennai</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dailymomentactivity-daywise-border"></div>
                                                                    <div class="d-flex align-items-center justify-content-between bg-label-white rounded mt-2 ms-3 px-2 p-1">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-send rounded-circle text-primary"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">#2 Glass Bridge at Coorg</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">2</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Beach Mahabalipuram</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">3</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Descent of the Ganga River</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">4</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Mahabalipuram Shore Temple</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Return Hotel</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i>N/A </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item card  p-3" id="accordionIconday2">

                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#accordionIcon-day2" aria-controls="accordionIcon-day2">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-calendar-event text-body ti-sm"></i></span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="m-0 fs-6">DAY 2 - Tue, Sep 03, 2024</p>
                                                        <h6 class="m-0">Chennai Central Railway <span><i class="ti ti-arrow-big-right-lines"></i></span> Chennai Central Railway </h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-3">
                                                    <button type="button" class="btn btn-outline-warning waves-effect ps-3 py-2" data-bs-toggle="modal" data-bs-target="#review"><i class="ti ti-star me-2"></i>Review</button>
                                                    <button type="button" class="btn btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addcharge">+ Add Charge</button>
                                                </div>
                                            </div>

                                            <div id="accordionIcon-day2" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                <div class="accordion-body">
                                                    <ul class="timeline pt-3 px-3 mb-0">
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">1</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Varaha Cave</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">2</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Beach Mahabalipuram</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">3</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Descent of the Ganga River</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-notvisited"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">4</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Mahabalipuram Shore Temple</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Return Hotel</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i>N/A </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <span class="badge badge-dailymoment-visited"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item card  p-3" id="accordionIconday3" style="border: 2px solid #a2dca2;">

                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center cursor-pointer" data-bs-toggle="collapse" data-bs-target="#accordionIcon-day3" aria-controls="accordionIcon-day3">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            <span class="avatar-initial rounded-circle bg-label-secondary">
                                                                <div class="blink mx-2"><img src="assets/img/current-calender2.png" width="20px" /></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="m-0 fs-6">DAY 3 - Wed, Sep 04, 2024</p>
                                                        <h6 class="m-0">Chennai Central Railway <span><i class="ti ti-arrow-big-right-lines"></i></span> Chennai Central Railway </h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-3">
                                                    <span class="badge badge-center rounded-pill bg-label-secondary p-3 cursor-pointer" data-bs-toggle="modal" data-bs-target="#uploadimage"><img src="../head/assets/img/image.png" width="20px" /></span>
                                                    <button type="button" class="btn btn-outline-warning waves-effect ps-3 py-2" data-bs-toggle="modal" data-bs-target="#review"><i class="ti ti-star me-2"></i>Review</button>
                                                    <button type="button" class="btn btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addcharge">+ Add Charge</button>
                                                </div>
                                            </div>

                                            <div id="accordionIcon-day3" class="accordion-collapse collapse show" data-bs-parent="#accordionIcon">
                                                <div class="accordion-body">
                                                    <ul class="timeline pt-3 px-3 mb-0">
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">1</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Varaha Cave</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <div class="d-flex gap-3" id="button-container">
                                                                                <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2" onclick="showVisited()">
                                                                                    <i class="ti ti-check fs-6 me-1"></i>Visited
                                                                                </button>
                                                                                <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2" onclick="showNotVisited()">
                                                                                    <i class="ti ti-x fs-6 me-1"></i>Not-visited
                                                                                </button>
                                                                            </div>
                                                                            <span class="badge badge-dailymoment-visited" id="visited-label" style="display: none;"><i class="ti ti-check fs-6 me-1"></i>Visited</span>
                                                                            <span class="badge badge-dailymoment-notvisited" id="notvisited-label" style="display: none;"><i class="ti ti-x fs-6 me-1"></i>Not Visited</span>
                                                                            <span class="cursor-pointer" id="edit-icon" data-bs-toggle="modal" data-bs-target="#edit" style="display: none;">
                                                                                <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- activity -->


                                                                <div class="mb-2">
                                                                    <hr />
                                                                    <h6 class="my-2">Activity</h6>
                                                                    <div class="d-flex align-items-center justify-content-between bg-label-white rounded mt-2 ms-3 px-2 p-1">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-send rounded-circle text-primary"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">#1 Boating at Chennai</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dailymomentactivity-daywise-border"></div>
                                                                    <div class="d-flex align-items-center justify-content-between bg-label-white rounded mt-2 ms-3 px-2 p-1">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-send rounded-circle text-primary"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">#2 Glass Bridge at Coorg</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">2</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Beach Mahabalipuram</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">3</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Descent of the Ganga River</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-primary" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark">4</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Mahabalipuram Shore Temple</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        09:06 AM -
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-hourglass-high me-1 ti-sm mb-1"></i>2 Hours </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="dailymoment-daywise-border"></div>
                                                        </li>
                                                        <li class="mb-3">
                                                            <div class="px-3 py-2 rounded-3 bg-label-info" style="border-radius:3px;">
                                                                <div class="row">
                                                                    <div class="col-12 ps-0 d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="avatar me-3 ms-2">
                                                                                <span class="avatar-initial rounded-circle bg-white text-dark"><i class="ti ti-building-skyscraper text-body ti-sm"></i></span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 align-items-center">
                                                                                <h6 class="m-0">Return Hotel</h6>
                                                                                <div class="d-flex align-items-center gap-4 text-dark">
                                                                                    <p class="mt-1 mb-0">
                                                                                        <i class="ti ti-clock me-1 mb-1"></i>
                                                                                        11:06 AM
                                                                                    </p>
                                                                                    <p class="mt-1 mb-0"><i class="ti ti-map-pin rounded-circle mb-1 me-1"></i>N/A </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex gap-3">
                                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2"><i class="ti ti-check fs-6 me-1"></i>Visited</button>
                                                                            <button type="button" class="btn btn-sm btn-secondary waves-effect waves-light ps-2"><i class="ti ti-x fs-6 me-1"></i>Not-visted</button>
                                                                        </div>
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

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="card p-0">
                                            <div class="card-header py-3  d-flex justify-content-between">
                                                <div class="col-md-auto">
                                                    <h5 class="card-title mb-0">List of Charge Details</h5>
                                                </div>
                                            </div>

                                            <div class="card-body dataTable_select text-nowrap">
                                                <div class="text-nowrap table-responsive table-bordered">
                                                    <table class="table table-hover" id="hotspot_LIST">
                                                        <thead>
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Day</th>
                                                                <th>Charge Title</th>
                                                                <th>Charge Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td> DAY 1 - Mon, Sep 02, 2024</td>
                                                                <td>Parking Charge</td>
                                                                <td>₹ 50.00</td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td> DAY 2 - Tue, Sep 03, 2024</td>
                                                                <td>Extra Charge</td>
                                                                <td>₹ 100.00</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->
                    <!-- Footer -->
                    <?php include_once('public/__footer.php'); ?>
                    <!-- / Footer -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Modal -->
    <div class="modal fade" id="addcharge" tabindex="-1" aria-labelledby="addchargeLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form id="ajax_vehicle_type_details_form" class="row g-3" action="" method="post" data-parsley-validate="" novalidate="">
                        <div class="text-center">
                            <h4 class="mb-2" id="VEHICLETYPEFORMLabel">Add Charges</h4>
                        </div>
                        <span id="response_modal"></span>

                        <div class="col-12">
                            <label class="form-label w-100" for="modalAddCardCvv">Charge Type</label>
                            <div class="form-group">
                                <input type="text" id="visited_charge" name="visited_charge" class="form-control" placeholder="Enter the Charge" value="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label w-100" for="modalAddCardCvv">Charge Amount</label>
                            <div class="form-group">
                                <input type="text" id="visited_charge_amount" name="visited_charge_amount" class="form-control" placeholder="Enter the Charge" value="" data-parsley-whitespace="trim" data-parsley-type="number" autocomplete="off">
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="vehicle_type_form_submit_btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageupload" tabindex="-1" aria-labelledby="imageuploadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form id="ajax_vehicle_type_details_form" class="row g-3" action="" method="post" data-parsley-validate="" novalidate="">
                        <div class="text-center">
                            <h4 class="mb-2" id="VEHICLETYPEFORMLabel">Add Image</h4>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="formValidationUsername">Upload Image</label>
                            <div class="form-group">
                                <input type="file" class="input-file" id="fileInput" name="file">
                            </div>
                        </div>

                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="vehicle_type_form_submit_btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="review" tabindex="-1" aria-labelledby="reviewLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form id="ajax_vehicle_type_details_form" class="row g-3" action="" method="post" data-parsley-validate="" novalidate="">
                        <div class="text-center">
                            <h4 class="mb-2" id="VEHICLETYPEFORMLabel">Review Modal</h4>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label w-100" for="modalAddCard">Rating</label>
                            <select class="form-select" name="agent_subscription_plan" id="agent_subscription_plan" data-parsley-trigger="keyup">
                                <option value="">Choose the Rating </option>
                                <option value="1">1 Star</option>
                                <option value="2">2 Star</option>
                                <option value="3">3 Star</option>
                                <option value="4">4 Star</option>
                                <option value="5">5 Star</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label w-100" for="modalAddCardCvv">Notes</label>
                            <div class="form-group">
                                <textarea rows="5" id="visited_notes" name="visited_notes" class="form-control" placeholder="Enter the Notes"></textarea>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="vehicle_type_form_submit_btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <form id="ajax_vehicle_type_details_form" class="row g-3" action="" method="post" data-parsley-validate="" novalidate="">
                        <div class="text-center">
                            <h4 class="mb-2" id="VEHICLETYPEFORMLabel">Edit Status</h4>
                        </div>
                        <div class="col-12 mt-2">
                            <label class="form-label w-100" for="modalAddCard">Status</label>
                            <select class="form-select" name="status" id="status-dropdown" data-parsley-trigger="keyup">
                                <option value="">Choose the Status </option>
                                <option value="1">Visit</option>
                                <option value="2">Not-Visited</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="button" class="btn btn-primary" id="vehicle_type_form_submit_btn">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="uploadimage" tabindex="-1" aria-labelledby="uploadimageLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg receiving-gallery-modal-info-form-data">
            <div class="modal-content">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center mb-2">
                        <h5 class="modal-title mt-2 text-primary" id="modalCenterTitle">DAILY MOMENT IMAGE </h5>
                    </div>
                    <!-- <div id="swiper-gallery">
                        <div class="swiper gallery-top">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" style="background-image:url(../head/assets/img/air-conditioner.png)"></div>
                            </div>
                            <div class="swiper-button-next swiper-button-white"></div>
                            <div class="swiper-button-prev swiper-button-white"></div>
                        </div>
                        <div class="swiper gallery-thumbs">
                          <div class="swiper-wrapper">
                             <div class="swiper-slide" style="background-image:url(../head/assets/img/air-conditioner.png)"></div>
                          </div>
                        </div>

                    </div> -->
                    <img src="../head/assets/img/sedan.jpg" width="750px" height="400px" />
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/vendor/libs/swiper/swiper.js"></script>
    <script src="assets/js/ui-carousel.js"></script>

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
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <!-- Main JS -->
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        function showVisited() {
            document.getElementById('button-container').classList.add('d-none');
            document.getElementById('visited-label').style.display = 'inline';
            document.getElementById('edit-icon').style.display = 'inline';
            document.getElementById('notvisited-label').style.display = 'none';
        }

        function showNotVisited() {
            document.getElementById('button-container').classList.add('d-none');
            document.getElementById('visited-label').style.display = 'none';
            document.getElementById('edit-icon').style.display = 'inline';
            document.getElementById('notvisited-label').style.display = 'inline';
        }

        document.getElementById('status-dropdown').addEventListener('change', function() {
            const visitedLabel = document.getElementById('visited-label');
            const notVisitedLabel = document.getElementById('notvisited-label');
            const value = this.value;

            if (value === '1') {
                visitedLabel.style.display = 'inline';
                notVisitedLabel.style.display = 'none';
            } else if (value === '2') {
                visitedLabel.style.display = 'none';
                notVisitedLabel.style.display = 'inline';
            } else {
                visitedLabel.style.display = 'none';
                notVisitedLabel.style.display = 'none';
            }
        });
    </script>

</body>

</html>