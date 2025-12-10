<?php
include_once("jackus.php");
admin_reguser_protect();
require_once('check_restriction.php');
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

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


  <!-- Favicon -->
  <!-- <link rel="icon" type="image/x-icon" href="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/favicon/favicon.ico" /> -->

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
  <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
  <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
  <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
  <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
  <link rel="stylesheet" href="./assets/css/_css_jquery.dataTables.min.css" />
  <link rel="stylesheet" href="./assets/css/_1.13.6_css_jquery.dataTables.min.css" />

  <!-- Page CSS -->
  <link rel="stylesheet" href="./assets/vendor/libs/select2/select2.css" />

  <!-- Map -->
  <link rel="stylesheet" href="assets/vendor/libs/leaflet/leaflet.css" />
  <!-- Map -->

  <!-- Swiper -->
  <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />

  <!-- Helpers -->
  <script src="./assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
  <script src="./assets/vendor/js/template-customizer.js"></script>
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="./assets/js/config.js"></script>

  <!-- Page CSS -->

  <link rel="stylesheet" href="./assets/vendor/css/pages/app-logistics-dashboard.css" />
  <link rel="stylesheet" href="./assets/css/style.css">
  <link rel="stylesheet" href="./assets/vendor/css/pages/cards-advance.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />
  <link rel="stylesheet" href="./assets/css/dashboard.css">
  <link rel="stylesheet" href="../../assets/vendor/css/pages/app-logistics-dashboard.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css" />

</head>

<body>
  <!-- Layout wrapper -->
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar  ">
    <div class="layout-container">
      <!-- Menu -->
      <!-- Layout container -->
      <div class="layout-page">

        <?php include_once('public/__sidebar.php'); ?>

        <div class="content-wrapper">

          <!-- Content -->
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h4 class="font-weight-bold">Dashboard</h4>
              </div>
              <div class="my-3">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">
                        <i class="tf-icons ti ti-home mx-2"></i>
                      </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                  </ol>
                </nav>
              </div>
            </div>

            <?php
            if ($logged_user_level == 1) : ?>
              <div class="row">

                <!-- Hour chart  -->
                <div class="col-lg-12 mb-4">
                  <div class="card shadow-none my-4 border-0">
                    <div class="card-body row p-4">
                      <div class="col-12 col-md-8 card-separator">
                        <h3>Welcome back, Admin 👋🏻 </h3>
                        <div class="col-12 col-lg-7">
                          <p>Your progress this week is Awesome. let's keep it up and get a lot of points reward !</p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap gap-3 me-5">
                          <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="bg-label-primary p-2 rounded">
                              <i class='ti ti-user ti-xl'></i>
                            </span>
                            <div class="content-right">
                              <p class="mb-0">Total Agents</p>
                              <h4 class="text-primary mb-0"><?= getAGENT_details('', '', 'get_total_agent_count'); ?></h4>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-info p-2 rounded">
                              <i class='ti ti-road ti-xl'></i>
                            </span>
                            <div class="content-right">
                              <p class="mb-0">Total Driver</p>
                              <h4 class="text-info mb-0"><?= getDASHBOARD_COUNT_DETAILS('total_driver_count'); ?></h4>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                              <i class='ti ti-car ti-xl'></i>
                            </span>
                            <div class="content-right">
                              <p class="mb-0">Total Guide</p>
                              <h4 class="text-warning mb-0"><?= getGUIDEDETAILS('', 'total_guide_count'); ?></h4>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md-4 ps-md-3 ps-lg-4 pt-3 pt-md-0">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <div>
                              <h5 class="mb-2">Active Bookings</h5>
                              <p class="mb-5">Weekly report</p>
                            </div>
                            <div class="time-spending-chart">
                              <h3 class="mb-2">34<span class="text-muted ms-1">Total</span> </h3>
                              <span class="badge bg-label-success">+18.4%</span>
                            </div>
                          </div>
                          <div id="leadsReportChart"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Hour chart End  -->

                <!-- Itinerary List -->
                <div class="col-lg-6 mb-4 col-sm-12 px-1">
                  <div class="row">
                    <div class="col-lg-12 d-flex">
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_itinerary_count'); ?></h5>
                              <small>Total Itineraries</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-info h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">₹3,90,000.00</h5>
                              <small>Total Revenue</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/profit.png" alt="Profit" width="100">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex mt-2">
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-primary h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_confirm_itinerary_count'); ?></h5>
                              <small>Total Confirm Bookings</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/booking.png" alt="booking" width="75">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-danger h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">4,523</h5>
                              <small>Completed Journeys</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/journey2.png" alt="visitors" width="90">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Itinerary List -->

                <!-- Overview Section -->
                <div class="col-lg-6 mb-4 col-sm-12 overview-section">
                  <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg bg-primary" id="swiper-with-pagination-cards">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Hotel Overview</h5>
                            <small>Insights into Hotel Performance</small>
                          </div>
                          <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-10 col-12 order-2 order-md-1">
                              <!-- <h6 class="text-white mt-0 mt-md-3 mb-3">Hotel Performance Overview</h6> -->
                              <div class="row mt-5">
                                <div class="col-6">
                                  <ul class="list-unstyled mb-0">
                                    <li class="d-flex mb-4 align-items-center">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_hotel_count'); ?></b></p>
                                      <p class="mb-0">Hotel Count</p>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_amenities_count'); ?></b></p>
                                      <p class="mb-0">Amentities Count</p>
                                    </li>
                                  </ul>
                                </div>
                                <div class="col-6">
                                  <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center mb-4">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_room_count'); ?></b></p>
                                      <p class="mb-0">Room Count</p>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_hotel_booking'); ?></b></p>
                                      <p class="mb-0">Total Bookings</p>
                                    </li>
                                  </ul>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-4 col-md-2 col-12 order-1 order-md-2 my-4 my-md-0 text-center d-flex justify-content-center align-items-center">
                              <img src="assets/img/dashboard/hotel-1.png" alt="Hotel Overview" width="150">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Vehicle Overview</h5>
                            <small>Insights into Fleet Performance</small>
                          </div>
                          <div class="col-lg-8 col-md-10 col-12 order-2 order-md-1">
                            <!-- <h6 class="text-white mt-0 mt-md-3 mb-3">Vehicle Performance Overview</h6> -->
                            <div class="row mt-5">
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vehicle_count'); ?></b></p>
                                    <p class="mb-0">Total Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vehicle_available'); ?></b></p>
                                    <p class="mb-0">Available Vehicles</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vehicle_ongoing'); ?></b></p>
                                    <p class="mb-0">On Route Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>17</b></p>
                                    <p class="mb-0">Reserved Vehicles</p>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 col-md-2 col-12 order-1 order-md-2 my-4 my-md-0 text-center d-flex justify-content-center align-items-center">
                            <img src="assets/img/dashboard/car.png" alt="Website Analytics" width="150">
                          </div>
                        </div>
                      </div>
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Driver Overview</h5>
                            <small>Driver Performance Overview</small>
                          </div>
                          <div class="col-lg-8 col-md-10 col-12 order-2 order-md-1">
                            <!-- <h6 class="text-white mt-0 mt-md-3 mb-3">Driver Performance Overview</h6> -->
                            <div class="row mt-5">
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('active_drivers'); ?></b></p>
                                    <p class="mb-0">Active Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('inactive_drivers'); ?></b></p>
                                    <p class="mb-0">In-active Drivers</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_driver_ongoing'); ?></b></p>
                                    <p class="mb-0">On Route Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_driver_available'); ?></b></p>
                                    <p class="mb-0">Available Drivers</p>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 col-md-2 col-12 order-1 order-md-2 my-4 my-md-0 text-center d-flex justify-content-center align-items-center">
                            <img src="assets/img/dashboard/driver.png" alt="Website Analytics" width="130">
                          </div>
                        </div>
                      </div>
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Vendor Overview</h5>
                            <small>Vendor into Hotel Performance</small>
                          </div>
                          <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                            <!-- <h6 class="text-white mt-0 mt-md-3 mb-3">Vendor Performance Overview</h6> -->
                            <div class="row mt-5">
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vendor_count'); ?></b></p>
                                    <p class="mb-0">Total Vendors</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>4</b></p>
                                    <p class="mb-0">Active Vendors</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_branch_count'); ?></b></p>
                                    <p class="mb-0">Total Branches</p>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center d-flex justify-content-center align-items-center">
                            <img src="assets/img/dashboard/vendor-2.png" alt="Website Analytics" width="150">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-pagination"></div>
                  </div>
                </div>
                <!--/ Overview Section -->

                <!-- Daily Moment Status -->
                <div class="col-md-7 mb-4 vehicle-live-data-section">
                  <div class="card overflow-hidden">
                    <div class="app-logistics-fleet-sidebar col h-100" id="app-logistics-fleet-sidebar">
                      <div class="card-header border-0 pt-4 pb-2 d-flex justify-content-between">
                        <h5 class="mb-0 card-title">Daily Moment</h5>
                        <div>
                          <input type="text" id="dailymoment-picker" placeholder="MM/DD/YYYY" class="form-control" />
                        </div>
                        <!-- Sidebar close button -->
                        <i class="ti ti-x ti-xs cursor-pointer close-sidebar d-md-none btn btn-label-secondary p-0" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar"></i>
                      </div>
                      <span id="dailymoment-container"></span>

                    </div>
                  </div>
                </div>
                <!-- Daily Moment Status -->

                <!-- Top-Rated Guides, Vendors, and Drivers Approval -->
                <div class="col-md-5 col-xl-5 mb-4 top-rated-performance-section">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between pb-2 mb-1">
                      <div class="card-title mb-1">
                        <h5 class="m-0 me-2">Star Performers</h5>
                        <small class="text-muted">Top-Rated Guides, Vendors, and Drivers</small>
                      </div>

                      <div class="dropdown">
                        <button class="btn p-0" type="button" id="topPerformersDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topPerformersDropdown">
                          <a class="dropdown-item" href="javascript:void(0);">Download Report</a>
                          <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        </div>
                      </div>
                    </div>

                    <div class="card-body pb-0">
                      <div class="nav-align-top">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                          <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-guides" aria-controls="top-rated-guides" aria-selected="true">Guides</button>
                          </li>
                          <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-vendors" aria-controls="top-rated-vendors" aria-selected="false">Vendors</button>
                          </li>
                          <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-drivers" aria-controls="top-rated-drivers" aria-selected="false">Drivers</button>
                          </li>
                        </ul>

                        <div class="tab-content p-0">
                          <!-- Guides Section -->
                          <div class="tab-pane fade show active" id="top-rated-guides" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/avatars/2.png" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <div>
                                    <h6 class="mb-0">Annie Mary</h6>
                                    <div class="user-progress">
                                      <p class="text-danger fw-medium mb-0 d-flex align-items-center gap-1">
                                        <i class="ti ti-chevron-down"></i>
                                        1.2%
                                      </p>
                                    </div>
                                  </div>
                                </div>
                                <div class="user-progress">
                                  <h6 class="text-body mb-0">10k</h6>
                                </div>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/avatars/3.png" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">John Mathew</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-up"></i>
                                      5.8%
                                    </p>
                                  </div>
                                </div>
                              </li>
                            </ul>
                          </div>

                          <!-- Vendors Section -->
                          <div class="tab-pane fade" id="top-rated-vendors" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/user.svg" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">Vendor A</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-up"></i>
                                      16.2%
                                    </p>
                                  </div>
                                </div>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/user.svg" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">Vendor B</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-danger fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-down"></i>
                                      6.2%
                                    </p>
                                  </div>
                                </div>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/user.svg" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">Vendor C</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-danger fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-down"></i>
                                      0.2%
                                    </p>
                                  </div>
                                </div>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/user.svg" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">Vendor D</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-danger fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-down"></i>
                                      1.2%
                                    </p>
                                  </div>
                                </div>
                              </li>
                            </ul>
                          </div>

                          <!-- Drivers Section -->
                          <div class="tab-pane fade" id="top-rated-drivers" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/profile.svg" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">Driver X</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-danger fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-down"></i>
                                      6.2%
                                    </p>
                                  </div>
                                </div>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-4">
                                <div class="d-flex align-items-center">
                                  <div class="avatar avatar-md me-3 ms-1 profile-image-container">
                                    <img src="assets/img/profile.svg" alt="Avatar" class="rounded-circle">
                                  </div>
                                  <h6 class="mb-0">Driver Y</h6>
                                </div>
                                <div>
                                  <div class="user-progress">
                                    <p class="text-success fw-medium mb-0 d-flex align-items-center gap-1">
                                      <i class="ti ti-chevron-up"></i>
                                      25.8%
                                    </p>
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
                <!--/ Top-Rated Guides, Vendors, and Drivers Approval -->



                <!-- Itinerary List -->
                <div class="col-md-12">
                  <h4 class="mt-3">Itinerary List</h4>
                  <span id="itinerary_details"></span>
                </div>
                <!-- Itinerary List -->

                <!-- Agents List -->
                <div class="col-md-12 mb-4">
                  <h4>List of Agents</h4>
                  <span id="agent_details"></span>
                </div>
                <!-- Agents List -->

                <!-- Itinerary List -->
                <div class="col-md-12">
                  <h4 class="mt-3">Live Vehicle Status</h4>
                  <span id="vehicle_details"></span>
                </div>
                <!-- Itinerary List -->

                <!-- Most Visited Hotels -->
                <div class="col-lg-7 col-sm-12 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0 col-8">
                        <h5 class="mb-0">Most Visited Hotels</h5>
                        <small class="text-muted">Top 5 Picks by Visitors</small>
                      </div>
                      <div class="col-4 d-flex justify-content-end">
                        <input type="text" id="yearPicker" class="form-control" placeholder="Year">
                      </div>
                    </div>
                    <span id="tophotel_list"></span>
                  </div>
                </div>
                <!--/ Most Visited Hotels -->

                <!-- Itinerary Details -->
                <div class="col-lg-5 mb-4 col-sm-12">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Itinerary Overview</h5>
                        <small>Travel Itinerary Preference</small>
                      </div>
                    </div>
                    <div class="card-body">
                      <div id="deliveryExceptionsChart"></div>
                    </div>
                  </div>
                </div>
                <!--/ Itinerary Details -->

                <!-- Hotspot List -->
                <div class="col-12 mb-4" id="hotspot-list">
                  <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div class="card-title mb-0 d-flex justify-content-between align-items-center w-100">
                      <div>
                        <h4 class="mt-3">Hotspot List</h4>
                      </div>
                      <div class="col-4">
                        <select id="select2Basic" class="select2 form-select form-select-lg py-1" data-allow-clear="true">
                          <option value="BLR">Bangalore</option>
                          <option value="HYD">Hyderabad</option>
                          <option value="CHN">Chennai</option>
                          <option value="KCH">Kochi</option>
                          <option value="VZG">Visakhapatnam</option>
                          <option value="MYS">Mysore</option>
                          <option value="PNE">Pune</option>
                          <option value="TVM">Thiruvananthapuram</option>
                          <option value="CLT">Calicut</option>
                          <option value="MDR">Madurai</option>
                          <option value="COI">Coimbatore</option>
                          <option value="TJR">Tiruchirappalli</option>
                          <option value="KKN">Kakinada</option>
                          <option value="KLM">Kollam</option>
                          <option value="TPT">Tirupati</option>
                          <option value="MLR">Mangalore</option>
                          <option value="BEL">Belgaum</option>
                          <option value="HPT">Hosapete</option>
                          <option value="NND">Nellore</option>
                          <option value="MMB">Mumbai</option>
                          <option value="GRJ">Gurajat</option>
                          <option value="ATP">Anantapur</option>
                          <option value="GDV">Gadag</option>
                          <option value="SHM">Shimoga</option>
                          <option value="SLM">Salem</option>
                          <option value="VLL">Vellore</option>
                          <option value="TCR">Thrissur</option>
                          <option value="PRY">Puducherry</option>
                          <option value="TRP">Tirupur</option>
                          <option value="APR">Amaravati</option>
                          <option value="TPT">Tirupati</option>
                          <option value="TDD">Tadepalligudem</option>
                          <option value="RJY">Rajahmundry</option>
                          <option value="BGK">Bhongir</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="swiper" id="swiper-multiple-slides">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/yoga.jpg" alt="Yoga" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Yoga</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" alt="Government Museum<" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Government Museum</b>
                            </h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/marina_beach_2.jpeg" alt="Marina Beach" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Marina Beach</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/surf.jpg" alt="Surf" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Surf</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" alt="Pondy Bazaar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Pondy Bazaar</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" alt="Kapaleeshwarar Temple" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Kapaleeshwarar Temple</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="hotspot-list-swiper-button-prev swiper-button-prev"></div>
                    <div class="hotspot-list-swiper-button-next swiper-button-next"></div>
                  </div>
                </div>
                <!--/ Hotspot List -->
              </div>
            <?php elseif ($logged_agent_id != '' &&  $logged_agent_id != '0') : ?>

              <div class="row">
                <!-- Hour chart  -->
                <div class="col-lg-12 mb-4">
                  <div class="card shadow-none my-4 border-0">
                    <div class="card-body row p-4">
                      <div class="col-12 col-md-8 card-separator">
                        <h3>Welcome back, Agent 👋🏻 </h3>
                        <div class="col-12 col-lg-7">
                          <p>Your progress this week is Awesome. let's keep it up and get a lot of points reward !</p>
                        </div>
                        <div class="d-flex justify-content-between flex-wrap gap-3 me-5">
                          <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                              <i class='ti ti-user ti-xl'></i>
                            </span>
                            <div class="content-right">
                              <p class="mb-0">Total Customers</p>
                              <h4 class="text-primary mb-0">18</h4>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="bg-label-info p-2 rounded">
                              <i class='ti ti-calendar ti-xl'></i>
                            </span>
                            <div class="content-right">
                              <p class="mb-0">Validity Ends</p>
                              <h4 class="text-info mb-0"><?= date('d M Y', strtotime(getDASHBOARD_COUNT_DETAILS('validity_end_date', $logged_agent_id))); ?></h4>
                            </div>
                          </div>
                          <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                              <i class='ti ti-cash ti-xl'></i>
                            </span>
                            <div class="content-right">
                              <p class="mb-0">Paid Invoice</p>
                              <h4 class="text-warning mb-0">5</h4>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12 col-md-4 ps-md-3 ps-lg-4 pt-3 pt-md-0">
                        <div class="d-flex justify-content-between align-items-center">
                          <div>
                            <div>
                              <h5 class="mb-2">Last Month Profit</h5>
                              <p class="mb-5">September</p>
                            </div>
                            <div class="time-spending-chart">
                              <h3 class="mb-2">₹ 3,40,000.00<span class="text-muted ms-1">Total</span> </h3>
                              <span class="badge bg-label-success">+35.2%</span>
                            </div>
                          </div>
                          <div id="leadsReportChart"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Hour chart End  -->

                <!-- Itinerary List -->
                <div class="col-lg-12 mb-4 col-sm-12 px-1">
                  <div class="row">
                    <div class="col-md-12 d-flex">
                      <div class="col-lg-3 px-2">
                        <div class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_itinerary_count', $logged_agent_id); ?></h5>
                              <small>Total Itineraries</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 px-2">
                        <div class="card card-border-shadow-info h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">₹3,90,000.00</h5>
                              <small>Total Revenue</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/profit.png" alt="Profit" width="100">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 px-2">
                        <div class="card card-border-shadow-primary h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_confirm_itinerary_count', $logged_agent_id); ?></h5>
                              <small>Total Bookings</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/booking.png" alt="booking" width="75">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-3 px-2">
                        <div class="card card-border-shadow-danger h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">4,523</h5>
                              <small>Completed Journeys</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/journey2.png" alt="visitors" width="90">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Itinerary List -->

                <!-- Itinerary List -->
                <div class="col-md-12">
                  <h4 class="mt-3">Itinerary List</h4>
                  <span id="itinerary_details"></span>
                </div>
                <!-- Itinerary List -->

                <!-- Itinerary List -->
                <div class="col-md-12">
                  <h4 class="mt-3">Live Vehicle Status</h4>
                  <div class="nav-align-top mb-4" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-on-route-ehicles" aria-controls="navs-on-route-ehicles" aria-selected="true">On route vehicles</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-upcoming-trips" aria-controls="navs-upcoming-trips" aria-selected="false">Upcoming Trips</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-idle-vehicles" aria-controls="navs-idle-vehicles" aria-selected="false">Idle Vehicles</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-completed-trips" aria-controls="navs-completed-trips" aria-selected="false">Completed Trips</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-in-service-maintenance" aria-controls="navs-in-service-maintenance" aria-selected="false">In-Service Vehicles</button>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="navs-on-route-ehicles" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Vehicle Number</th>
                                  <th scope="col">Vehicle Type</th>
                                  <th scope="col">Driver Name</th>
                                  <th scope="col">Starting Route</th>
                                  <th scope="col">Ending Route</th>
                                  <th scope="col">Status</th>
                                  <th scope="col"></th>
                                </tr>
                                <!-- <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Vehicle Number</th>
                                  <th scope="col">Vehicle Type</th>
                                  <th scope="col">Driver Name</th>
                                  <th scope="col">Scheduled Date</th>
                                  <th scope="col">Starting Route</th>
                                  <th scope="col">Ending Route</th>
                                  <th scope="col">Estimated Start Time</th>
                                  <th scope="col">Estimated Arrival Time</th>
                                  <th scope="col">Status</th>
                                  <th scope="col"></th>
                                </tr> -->
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>Raja</td>
                                  <td>XUV</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>
                                    <h6 class="mb-0 text-success">Available</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>AP-29-BH-8545</td>
                                  <td>Durai</td>
                                  <td>Crossover</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>
                                    <h6 class="mb-0 text-primary">Completed</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                  <!-- <td><div class="d-flex align-items-center"><div div="" class="progress w-100" style="height: 8px;"><div class="progress-bar" role="progressbar" style="width:56%;" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"></div></div><div class="text-body ms-3">56%</div></div></td> -->
                                </tr>
                                <tr>
                                  <td>3.</td>
                                  <td>TN-29-JD-8S45</td>
                                  <td>Viraaj</td>
                                  <td>Coupe</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Ooty, Tamil Nadu, India</td>
                                  <td>
                                    <h6 class="mb-0 text-primary">Completed</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>4.</td>
                                  <td>TN-94-MK-5000</td>
                                  <td>Vinoth Kumar</td>
                                  <td>Sedan</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Coorg, Bengaluru, India</td>
                                  <td>
                                    <h6 class="mb-0" style="color: #7367f0;">On Route</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>5.</td>
                                  <td>KL-01-SM-9055</td>
                                  <td>Ravi Sharma</td>
                                  <td>XUV</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Kochi, Kerala, India</td>
                                  <td>
                                    <h6 class="mb-0 text-danger">Cancelled</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-upcoming-trips" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Vehicle Number</th>
                                  <th scope="col">Vehicle Type</th>
                                  <th scope="col">Driver Name</th>
                                  <th scope="col">Scheduled Date</th>
                                  <th scope="col">Starting Route</th>
                                  <th scope="col">Ending Route</th>
                                  <th scope="col">Estimated Start Time</th>
                                  <th scope="col">Estimated Arrival Time</th>
                                  <th scope="col">Status</th>
                                  <th scope="col"></th>
                                </tr>

                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>XUV</td>
                                  <td>Raja</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>
                                    <h6 class="mb-0 text-warning">Upcoming</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>MP-03-RA-1405</td>
                                  <td>XUV</td>
                                  <td>Kaja</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>
                                    <h6 class="mb-0 text-warning">Upcoming</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-idle-vehicles" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Vehicle Number</th>
                                  <th scope="col">Vehicle Type</th>
                                  <th scope="col">Driver Name</th>
                                  <th scope="col">Last Completed Trip</th>
                                  <th scope="col">Location</th>
                                  <th scope="col">Availability Status</th>
                                  <th scope="col"></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>XUV</td>
                                  <td>Raja</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai</td>
                                  <td>
                                    <h6 class="mb-0 text-success">Idle</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-completed-trips" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Vehicle Number</th>
                                  <th scope="col">Vehicle Type</th>
                                  <th scope="col">Driver Name</th>
                                  <th scope="col">Trip Date</th>
                                  <th scope="col">Starting Route</th>
                                  <th scope="col">Ending Route</th>
                                  <th scope="col">Completion Time</th>
                                  <th scope="col">Status</th>
                                  <th scope="col"></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>Sedan</td>
                                  <td>Raja</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>18/08/2024 06:30 PM</td>
                                  <td>
                                    <h6 class="mb-0 text-warning">Delay</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>Sedan</td>
                                  <td>Raja</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>18/08/2024 06:30 PM</td>
                                  <td>
                                    <h6 class="mb-0 text-warning">Delay</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>3.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>Sedan</td>
                                  <td>Raja</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>Chennai, Tamilnadu, India</td>
                                  <td>18/08/2024 06:30 PM</td>
                                  <td>
                                    <h6 class="mb-0 text-warning">Delay</h6>
                                  </td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-in-service-maintenance" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Vehicle Number</th>
                                  <th scope="col">Vehicle Type</th>
                                  <th scope="col">Owner</th>
                                  <th scope="col"></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>KA-03-HA-1985</td>
                                  <td>Sedan</td>
                                  <td>Vijay</td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>AP-13-HA-3955</td>
                                  <td>Sedan</td>
                                  <td>Vijay</td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>3.</td>
                                  <td>UP-28-HA-8005</td>
                                  <td>Sedan</td>
                                  <td>Vijay</td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                                <tr>
                                  <td>4.</td>
                                  <td>UP-28-HA-8005</td>
                                  <td>Sedan</td>
                                  <td>Vijay</td>
                                  <td><button type="button" class="btn btn-primary waves-effect waves-light">Details</button></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Itinerary List -->

                <!-- Most Visited Hotels -->
                <div class="col-lg-7 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0 col-8">
                        <h5 class="mb-0">Most Visited Hotels</h5>
                        <small class="text-muted">Top 5 Picks by Visitors</small>
                      </div>
                      <div class="col-4 d-flex justify-content-end">
                        <input type="text" id="yearPicker" class="form-control" placeholder="Year">
                      </div>
                    </div>
                    <span id="tophotel_list"></span>
                  </div>
                </div>
                <!--/ Most Visited Hotels -->

                <!-- Itinerary Details -->
                <div class="col-lg-5 mb-4 col-sm-12">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <div class="card-title mb-0">
                        <h5 class="m-0 me-2">Itinerary Overview</h5>
                        <small>Agent Itinerary Preference</small>
                      </div>
                    </div>
                    <div class="card-body">
                      <div id="agentdeliveryExceptionsChart"></div>
                    </div>
                  </div>
                </div>
                <!--/ Itinerary Details -->

                <!-- Daily Moment Status -->
                <div class="col-md-5 mb-4 vehicle-live-data-section">
                  <div class="card overflow-hidden">
                    <div class="app-logistics-fleet-sidebar col h-100" id="app-logistics-fleet-sidebar">
                      <div class="card-header border-0 pt-4 pb-2 d-flex justify-content-between">
                        <h5 class="mb-0 card-title">Daily Moment</h5>
                        <div>
                          <input type="text" id="bs-datepicker-basic" placeholder="MM/DD/YYYY" value="03/09/2024" class="form-control" />
                        </div>
                        <!-- Sidebar close button -->
                        <i class="ti ti-x ti-xs cursor-pointer close-sidebar d-md-none btn btn-label-secondary p-0" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar"></i>
                      </div>
                      <!-- Sidebar when screen < md -->
                      <div class="card-body p-2 pt-0 logistics-fleet-sidebar-body pb-4">
                        <!-- Menu Accordion -->
                        <div class="accordion" id="fleet" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar">
                          <!-- Fleet 1 -->
                          <div class="accordion-item border-0 mb-0" id="fl-1">
                            <div class="accordion-header" id="fleetOne">
                              <div role="button" class="accordion-button collapsed shadow-none align-items-center" data-bs-toggle="collapse" data-bs-target="#fleet1" aria-expanded="true" aria-controls="fleet1">

                                <div class="d-flex align-items-center">
                                  <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                      <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car text-body ti-sm"></i></span>
                                    </div>
                                  </div>
                                  <span class="d-flex flex-column">
                                    <span class="h6 mb-0"><a class="text-primary" href="#" target="_blank">CQ-DVI202409-017</a></span>
                                    <span class="text-muted">Ongoing</span>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div id="fleet1" class="accordion-collapse collapse" data-bs-parent="#fleet">
                              <div class="accordion-body pt-3 pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                  <h6 class="mb-1">Trip Process</h6>
                                  <p class="text-body mb-1">65%</p>
                                </div>
                                <div class="progress" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <ul class="timeline ps-3 mt-4 mb-0">
                                  <li class="timeline-item ms-1 ps-4 border-left-dashed">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-circle-check'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Chennai, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 01, 7:53 AM</p>
                                    </div>
                                  </li>
                                  <li class="timeline-item ms-1 ps-4 border-transparent">
                                    <span class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                      <i class='ti ti-map-pin mt-1'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-uppercase fw-medium">Mahabalipuram, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 04, 8:18 AM</p>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <!-- Fleet 2 -->
                          <div class="accordion-item border-0 mb-0" id="fl-2">
                            <div class="accordion-header" id="fleetTwo">
                              <div role="button" class="accordion-button collapsed shadow-none align-items-center" data-bs-toggle="collapse" data-bs-target="#fleet2" aria-expanded="true" aria-controls="fleet2">

                                <div class="d-flex align-items-center">
                                  <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                      <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car text-body ti-sm"></i></span>
                                    </div>
                                  </div>
                                  <span class="d-flex flex-column">
                                    <span class="h6 mb-0"><a class="text-primary" href="#" target="_blank">CQ-DVI202409-017</a></span>
                                    <span class="text-muted">Ongoing</span>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div id="fleet2" class="accordion-collapse collapse" data-bs-parent="#fleet">
                              <div class="accordion-body pt-3 pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                  <h6 class="mb-1">Trip Process</h6>
                                  <p class="text-body mb-1">65%</p>
                                </div>
                                <div class="progress" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <ul class="timeline ps-3 mt-4 mb-0">
                                  <li class="timeline-item ms-1 ps-4 border-left-dashed">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-circle-check'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Chennai, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 01, 7:53 AM</p>
                                    </div>
                                  </li>
                                  <li class="timeline-item ms-1 ps-4 border-transparent">
                                    <span class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                      <i class='ti ti-map-pin mt-1'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-uppercase fw-medium">Mahabalipuram, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 04, 8:18 AM</p>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <!-- Fleet 3 -->
                          <div class="accordion-item border-0 mb-0" id="fl-3">
                            <div class="accordion-header" id="fleetTwo">
                              <div role="button" class="accordion-button collapsed shadow-none align-items-center" data-bs-toggle="collapse" data-bs-target="#fleet3" aria-expanded="true" aria-controls="fleet3">

                                <div class="d-flex align-items-center">
                                  <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                      <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car text-body ti-sm"></i></span>
                                    </div>
                                  </div>
                                  <span class="d-flex flex-column">
                                    <span class="h6 mb-0"><a class="text-primary" href="#" target="_blank">CQ-DVI202409-024</a></span>
                                    <span class="text-success">Completed</span>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div id="fleet3" class="accordion-collapse collapse" data-bs-parent="#fleet">
                              <div class="accordion-body pt-3 pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                  <h6 class="mb-1">Trip Process</h6>
                                  <p class="text-body mb-1">65%</p>
                                </div>
                                <div class="progress" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <ul class="timeline ps-3 mt-4 mb-0">
                                  <li class="timeline-item ms-1 ps-4 border-left-dashed">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-circle-check'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Chennai, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 01, 7:53 AM</p>
                                    </div>
                                  </li>
                                  <li class="timeline-item ms-1 ps-4 border-transparent">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-map-pin mt-1'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Mahabalipuram, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 04, 8:18 AM</p>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <!-- Fleet 4 -->
                          <div class="accordion-item border-0 mb-0" id="fl-4">
                            <div class="accordion-header" id="fleetFour">
                              <div role="button" class="accordion-button collapsed shadow-none align-items-center" data-bs-toggle="collapse" data-bs-target="#fleet4" aria-expanded="true" aria-controls="fleet4">

                                <div class="d-flex align-items-center">
                                  <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                      <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car text-body ti-sm"></i></span>
                                    </div>
                                  </div>
                                  <span class="d-flex flex-column">
                                    <span class="h6 mb-0"><a class="text-primary" href="#" target="_blank">CQ-DVI202409-017</a></span>
                                    <span class="text-muted">Ongoing</span>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div id="fleet4" class="accordion-collapse collapse" data-bs-parent="#fleet">
                              <div class="accordion-body pt-3 pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                  <h6 class="mb-1">Trip Process</h6>
                                  <p class="text-body mb-1">65%</p>
                                </div>
                                <div class="progress" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <ul class="timeline ps-3 mt-4 mb-0">
                                  <li class="timeline-item ms-1 ps-4 border-left-dashed">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-circle-check'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Chennai, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 01, 7:53 AM</p>
                                    </div>
                                  </li>
                                  <li class="timeline-item ms-1 ps-4 border-transparent">
                                    <span class="timeline-indicator-advanced timeline-indicator-primary border-0 shadow-none">
                                      <i class='ti ti-map-pin mt-1'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-uppercase fw-medium">Mahabalipuram, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 04, 8:18 AM</p>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <!-- Fleet 5 -->
                          <div class="accordion-item border-0 mb-0" id="fl-5">
                            <div class="accordion-header" id="fleetFive">
                              <div role="button" class="accordion-button collapsed shadow-none align-items-center" data-bs-toggle="collapse" data-bs-target="#fleet5" aria-expanded="true" aria-controls="fleet5">

                                <div class="d-flex align-items-center">
                                  <div class="avatar-wrapper">
                                    <div class="avatar me-2">
                                      <span class="avatar-initial rounded-circle bg-label-secondary"><i class="ti ti-car text-body ti-sm"></i></span>
                                    </div>
                                  </div>
                                  <span class="d-flex flex-column">
                                    <span class="h6 mb-0"><a class="text-primary" href="#" target="_blank">CQ-DVI202409-024</a></span>
                                    <span class="text-success">Completed</span>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div id="fleet5" class="accordion-collapse collapse" data-bs-parent="#fleet">
                              <div class="accordion-body pt-3 pb-0">
                                <div class="d-flex align-items-center justify-content-between">
                                  <h6 class="mb-1">Trip Process</h6>
                                  <p class="text-body mb-1">65%</p>
                                </div>
                                <div class="progress" style="height: 5px;">
                                  <div class="progress-bar" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <ul class="timeline ps-3 mt-4 mb-0">
                                  <li class="timeline-item ms-1 ps-4 border-left-dashed">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-circle-check'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Chennai, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 01, 7:53 AM</p>
                                    </div>
                                  </li>
                                  <li class="timeline-item ms-1 ps-4 border-transparent">
                                    <span class="timeline-indicator-advanced timeline-indicator-success border-0 shadow-none">
                                      <i class='ti ti-map-pin mt-1'></i>
                                    </span>
                                    <div class="timeline-event ps-0 pb-0">
                                      <div class="timeline-header">
                                        <small class="text-success text-uppercase fw-medium">Mahabalipuram, Tamil Nadu, India</small>
                                      </div>
                                      <p class="text-muted mb-0">Sep 04, 8:18 AM</p>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="text-center">
                          <a href="#" class="">View All<span class="ms-2"><i class="ti ti-chevron-right"></i></span></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Daily Moment Status -->

                <!-- Invoice Details -->
                <div class="col-md-7 d-flex mb-4">
                  <div class="card w-100 p-4">
                    <h4>Invoice Details</h4>
                    <div id="chart"></div>
                  </div>
                </div>
                <!--/ Invoice Details -->

                <!-- Hotspot List -->
                <div class="col-12 mb-4" id="hotspot-list">
                  <div class="card-header d-flex align-items-center justify-content-between py-3">
                    <div class="card-title mb-0 d-flex justify-content-between align-items-center w-100">
                      <div>
                        <h4 class="mt-3">Hotspot List</h4>
                      </div>
                      <div class="col-4">
                        <select id="select2Basic" class="select2 form-select form-select-lg py-1" data-allow-clear="true">
                          <option value="BLR">Bangalore</option>
                          <option value="HYD">Hyderabad</option>
                          <option value="CHN">Chennai</option>
                          <option value="KCH">Kochi</option>
                          <option value="VZG">Visakhapatnam</option>
                          <option value="MYS">Mysore</option>
                          <option value="PNE">Pune</option>
                          <option value="TVM">Thiruvananthapuram</option>
                          <option value="CLT">Calicut</option>
                          <option value="MDR">Madurai</option>
                          <option value="COI">Coimbatore</option>
                          <option value="TJR">Tiruchirappalli</option>
                          <option value="KKN">Kakinada</option>
                          <option value="KLM">Kollam</option>
                          <option value="TPT">Tirupati</option>
                          <option value="MLR">Mangalore</option>
                          <option value="BEL">Belgaum</option>
                          <option value="HPT">Hosapete</option>
                          <option value="NND">Nellore</option>
                          <option value="MMB">Mumbai</option>
                          <option value="GRJ">Gurajat</option>
                          <option value="ATP">Anantapur</option>
                          <option value="GDV">Gadag</option>
                          <option value="SHM">Shimoga</option>
                          <option value="SLM">Salem</option>
                          <option value="VLL">Vellore</option>
                          <option value="TCR">Thrissur</option>
                          <option value="PRY">Puducherry</option>
                          <option value="TRP">Tirupur</option>
                          <option value="APR">Amaravati</option>
                          <option value="TPT">Tirupati</option>
                          <option value="TDD">Tadepalligudem</option>
                          <option value="RJY">Rajahmundry</option>
                          <option value="BGK">Bhongir</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="swiper" id="swiper-multiple-slides">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/yoga.jpg" alt="Yoga" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Yoga</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" alt="Government Museum<" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Government Museum</b>
                            </h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/marina_beach_2.jpeg" alt="Marina Beach" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Marina Beach</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/surf.jpg" alt="Surf" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Surf</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" alt="Pondy Bazaar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Pondy Bazaar</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                      <div class="swiper-slide hover-hotspot-list" style="width: 383.667px; margin-right: 30px; height: 300px; border-radius: 15px; overflow: hidden;">
                        <figure>
                          <img src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" alt="Kapaleeshwarar Temple" style="width: 100%; height: 100%; object-fit: cover; border-radius: 15px;">
                          <div class="overlay_text_section"></div>
                          <div class="overlay_image_wrapper">
                            <h6 class="mb-0 text-wrap text-white"><b>Kapaleeshwarar Temple</b></h6>
                            <h6 class="mb-0 text-white" style="font-size: 10px;">Chennai, Tamilnadu</h6>
                          </div>
                          <button type="button" class="btn overlay_button" data-bs-toggle="modal" data-bs-target="#hotspotModalSection">Know More</button>
                        </figure>
                      </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="hotspot-list-swiper-button-prev swiper-button-prev"></div>
                    <div class="hotspot-list-swiper-button-next swiper-button-next"></div>
                  </div>
                </div>
                <!--/ Hotspot List -->


              </div>

            <?php elseif ($logged_vendor_id != '' &&  $logged_vendor_id != '0') : ?>
              <div class="row">
                  <!-- Hour chart  -->
                  <div class="col-lg-12 mb-4">
                    <div class="card shadow-none my-4 border-0">
                      <div class="card-body row p-4">
                        <div class="col-12 col-md-8 card-separator">
                          <h3>Welcome back, Vendor 👋🏻 </h3>
                          <div class="col-12 col-lg-7">
                            <p>Your progress this week is Awesome. let's keep it up and get a lot of points reward !</p>
                          </div>
                          <div class="d-flex justify-content-between flex-wrap gap-3 me-5">
                            <div class="d-flex align-items-center gap-3">
                              <span class="bg-label-primary p-2 rounded">
                                <i class='ti ti-user ti-xl'></i>
                              </span>
                              <div class="content-right">
                                <p class="mb-0">Total Vehicle</p>
                                <h4 class="text-primary mb-0"><?= getDASHBOARD_VENDOR_LIST_DETAILS($logged_vendor_id, 'vendor_vehicles_count'); ?></h4>
                              </div>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                              <span class="bg-label-warning p-2 rounded">
                                <i class='ti ti-cash ti-xl'></i>
                              </span>
                              <div class="content-right">
                                <p class="mb-0">Total Branch</p>
                                <h4 class="text-warning mb-0"><?= getDASHBOARD_VENDOR_LIST_DETAILS($logged_vendor_id, 'vendor_branch_count'); ?></h4>
                              </div>
                            </div>
                            <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                              <span class="bg-label-info p-2 rounded">
                                <i class='ti ti-user ti-xl'></i>
                              </span>
                              <div class="content-right">
                                <p class="mb-0">Total Driver</p>
                                <h4 class="text-info mb-0"><?= getDASHBOARD_VENDOR_LIST_DETAILS($logged_vendor_id, 'vendor_drivers_count'); ?></h4>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-4 ps-md-3 ps-lg-4 pt-3 pt-md-0">
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                              <div>
                                <h5 class="mb-2">Last Month Profit</h5>
                                <p class="mb-5">September</p>
                              </div>
                              <div class="time-spending-chart">
                                <h3 class="mb-2">₹ 3,40,000.00<span class="text-muted ms-1">Total</span> </h3>
                                <span class="badge bg-label-success">+35.2%</span>
                              </div>
                            </div>
                            <div id="leadsReportChart"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <!-- Hour chart End  -->

                 <!-- Itinerary List -->
                 <div class="col-lg-6 mb-4 col-sm-12 px-1">
                  <div class="row">
                    <div class="col-lg-12 d-flex">
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_VENDOR_LIST_DETAILS($logged_vendor_id, 'vendor_itinerary_count'); ?></h5>
                              <small>Total Trips</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-info h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">₹3,90,000.00</h5>
                              <small>Total Revenue</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/profit.png" alt="Profit" width="100">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex mt-2">
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-primary h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">8</h5>
                              <small>Scheduled Trips</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/booking.png" alt="booking" width="75">
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-danger h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2">4,523</h5>
                              <small>Completed Trips</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/journey2.png" alt="visitors" width="90">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Itinerary List -->

                  <!-- Overview Section -->
                  <div class="col-lg-6 mb-4 col-sm-12 overview-section">
                  <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg bg-primary" id="swiper-with-pagination-cards">
                    <div class="swiper-wrapper">
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Vehicle Overview</h5>
                            <small>Insights into Fleet Performance</small>
                          </div>
                          <div class="col-lg-8 col-md-10 col-12 order-2 order-md-1">
                            <!-- <h6 class="text-white mt-0 mt-md-3 mb-3">Vehicle Performance Overview</h6> -->
                            <div class="row mt-5">
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_VENDOR_LIST_DETAILS($logged_vendor_id, 'vendor_itinerary_count'); ?></b></p>
                                    <p class="mb-0">Total Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_VENDOR_LIST_DETAILS($logged_vendor_id, 'vendor_itinerary_count'); ?></b></p>
                                    <p class="mb-0">Available Vehicles</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vehicle_ongoing'); ?></b></p>
                                    <p class="mb-0">On Route Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>17</b></p>
                                    <p class="mb-0">Reserved Vehicles</p>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 col-md-2 col-12 order-1 order-md-2 my-4 my-md-0 text-center d-flex justify-content-center align-items-center">
                            <img src="assets/img/dashboard/car.png" alt="Website Analytics" width="150">
                          </div>
                        </div>
                      </div>
                      <div class="swiper-slide">
                        <div class="row">
                          <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Driver Overview</h5>
                            <small>Driver Performance Overview</small>
                          </div>
                          <div class="col-lg-8 col-md-10 col-12 order-2 order-md-1">
                            <!-- <h6 class="text-white mt-0 mt-md-3 mb-3">Driver Performance Overview</h6> -->
                            <div class="row mt-5">
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('active_drivers'); ?></b></p>
                                    <p class="mb-0">Active Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('inactive_drivers'); ?></b></p>
                                    <p class="mb-0">In-active Drivers</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_driver_ongoing'); ?></b></p>
                                    <p class="mb-0">On Route Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_driver_available'); ?></b></p>
                                    <p class="mb-0">Available Drivers</p>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <div class="col-lg-4 col-md-2 col-12 order-1 order-md-2 my-4 my-md-0 text-center d-flex justify-content-center align-items-center">
                            <img src="assets/img/dashboard/driver.png" alt="Website Analytics" width="130">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-pagination"></div>
                  </div>
                </div>
                <!--/ Overview Section -->

                   <!-- Itinerary List -->
                   <div class="col-md-12">
                  <h4 class="mt-3">Live Vehicle Status</h4>
                  <span id="vehicle_details"></span>
                </div>
                <!-- Itinerary List -->
              </div>
            <?php else : ?>

              <div class="row">
                <div class="col-md-12">
                  <h3 style="font-weight: bold; margin-bottom: 5px; color: #fff;" class="text-primary">Welcome, DVI Holidays!</h3>
                </div>

              </div>

            <?php endif; ?>
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

  <!-- Hotspot Modal -->
  <div class="modal fade" id="hotspotModalSection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body px-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="row">
            <div class="col-md-5">
              <div id="carouselExample-cf" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-bs-target="#carouselExample-cf" data-bs-slide-to="0" class="active"></li>
                  <li data-bs-target="#carouselExample-cf" data-bs-slide-to="1"></li>
                  <li data-bs-target="#carouselExample-cf" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="assets/img/itinerary/hotspots/yoga.jpg" alt="Yoga" style="width: 100%;height: 250px;object-fit: cover; border-radius: 15px;" alt="First slide" />
                  </div>
                  <div class="carousel-item">
                    <img src="assets/img/itinerary/hotspots/surf.jpg" alt="Yoga" style="width: 100%;height: 250px;object-fit: cover; border-radius: 15px;" alt="Second slide" />
                  </div>
                  <div class="carousel-item">
                    <img src="assets/img/itinerary/hotspots/government_museum_1.jpeg" alt="Yoga" style="width: 100%;height: 250px; object-fit: cover; border-radius: 15px;" alt="Third slide" />
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExample-cf" role="button" data-bs-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExample-cf" role="button" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Next</span>
                </a>
              </div>
              <h5 class="card-title text-center mt-2"><b>Yoga</b></h5>
              <h6 class="text-center"><small>Chennai, Tamilnadu</small></h6>
            </div>
            <div class="col-md-7">
              <div class="card-body">
                <p class="card-text">
                  Yoga is a practice that connects the body, breath, and mind. It uses physical postures, breathing exercises, and meditation to improve overall health. Yoga was developed as a spiritual practice thousands of years ago. Today, most Westerners who do yoga do it for exercise or to reduce stress.
                </p>
                <p class="card-text">
                  Yoga is a profound practice that harmonizes the body, breath, and mind. Through a blend of physical postures, controlled breathing, and meditation, yoga fosters holistic well-being. Originating as a spiritual discipline millennia ago, yoga has evolved into a popular form of exercise and stress management in the Western world. Its timeless teachings offer a pathway to inner peace and physical vitality.
                </p>
                <p class="card-text"><b>Timing :</b> Working hours (06.00 AM - 12.00 PM & 05.00 PM - 10.00 PM).</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Hotspot Modal -->

  <!-- Drag Target Area To SlideIn Menu On Small Screens -->
  <div class="drag-target"></div>

  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->

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
  <script src="./assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
  <!-- Flat Picker -->
  <script src="./assets/vendor/libs/moment/moment.js"></script>
  <script src="./assets/vendor/libs/flatpickr/flatpickr.js"></script>
  <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
  <!-- Form Validation -->
  <script src="./assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
  <script src="./assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
  <script src="./assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
  <script src="./assets/js/modal-add-new-cc.js"></script>
  <script src="./assets/js/modal-add-new-address.js"></script>
  <script src="./assets/js/modal-edit-user.js"></script>
  <script src="./assets/js/modal-enable-otp.js"></script>
  <script src="./assets/js/modal-share-project.js"></script>
  <script src="./assets/js/modal-create-app.js"></script>
  <script src="./assets/js/modal-two-factor-auth.js"></script>
  <script src="./assets/js/_jquery.dataTables.min.js"></script>
  <script src="./assets/js/_dataTables.buttons.min.js"></script>

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>
  <script src="assets/vendor/libs/toastr/toastr.js"></script>
  <script src="assets/js/footerscript.js"></script>

  <!-- Map -->
  <script src="assets/vendor/libs/leaflet/leaflet.js"></script>
  <script src="assets/js/maps-leaflet.js"></script>
  <!-- Map -->

  <!-- Chart JS -->
  <script src="assets/vendor/libs/chartjs/chartjs.js"></script>
  <script src="assets/js/charts-chartjs.js"></script>

  <!-- Swiper -->
  <script src="assets/vendor/libs/swiper/swiper.js"></script>

  <!-- Page JS -->
  <script src="./assets/js/dashboards-analytics.js"></script>
  <script src="./assets/js/app-academy-dashboard.js"></script>
  <script src="./assets/vendor/libs/apex-charts/apexcharts.js"></script>
  <script src="./assets/js/cards-statistics.js"></script>
  <script src="./assets/js/ui-toasts.js"></script>
  <script src="./assets/js/ui-carousel.js"></script>
  <script src="./assets/js/charts-chartjs.js"></script>
  <script src="./assets/vendor/libs/select2/select2.js"></script>
  <script src="./assets/js/bootstrap-datepicker.min.js"></script>

  <!-- <script src="./assets/js/app-logistics-dashboard.js"></script> -->

  <script>
    $(document).ready(function() {

      flatpickr("#dailymoment-picker", {
        defaultDate: new Date(),
        dateFormat: "d-m-Y",
        onChange: function(selectedDates, dateStr, instance) {
          // Call the function to load daily moments when the date changes
          showDAILYMOMENT(dateStr);
        }
      });

      var currentYear = new Date().getFullYear();


      $('#yearPicker').datepicker({
        format: "yyyy", 
        viewMode: "years",
        minViewMode: "years", 
        autoclose: true 
      }).datepicker('setDate', new Date(currentYear, 0, 1)); 

     
      $('#yearPicker').on('changeDate', function(e) {
        var selectedYear = e.format(0, "yyyy"); 
        showTOPHOTEL(selectedYear); 
      });

      $(document).ready(function() {
        showTOPHOTEL(currentYear); 
      });


      <?php if ($_GET['route'] == '') : ?>
        showITINEARYLIST();
        showVEHICLELIST();
        showTOPHOTEL();
        showDAILYMOMENT();
        showAGENTLIST();
      <?php endif; ?>


      function showITINEARYLIST() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboarditinerary_list.php?type=show_form',
          success: function(response) {
            $('#itinerary_details').html(response);
          }
        });
      }

      function showAGENTLIST() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboardagent_list.php?type=show_form',
          success: function(response) {
            $('#agent_details').html(response);
          }
        });
      }

      function showVEHICLELIST() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboardvehicle_list.php?type=show_form',
          success: function(response) {
            $('#vehicle_details').html(response);
          }
        });
      }

      function showTOPHOTEL(year) {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_tophotel.php?type=show_form',
          data: {
            year: year // Pass the selected date as a parameter
          },
          success: function(response) {
            $('#tophotel_list').html(response);
          }
        });
      }

      function showDAILYMOMENT(selectedDate) {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_dailymoment.php?type=show_form',
          data: {
            date: selectedDate // Pass the selected date as a parameter
          },
          success: function(response) {
            $('#dailymoment-container').html(response);
          }
        });
      }

      "use strict";
      !(function() {
        var o = "#836AF9",
          r = "#ffe800",
          t = "#28dac6",
          e = "#EDF1F4",
          a = "#2B9AFF",
          l = "#84D0FF";
        let i, n, d, s, c;
        s = (
          isDarkStyle ?
          ((i = config.colors_dark.cardColor),
            (n = config.colors_dark.headingColor),
            (d = config.colors_dark.textMuted),
            (c = config.colors_dark.bodyColor),
            config.colors_dark) :
          ((i = config.colors.cardColor),
            (n = config.colors.headingColor),
            (d = config.colors.textMuted),
            (c = config.colors.bodyColor),
            config.colors)
        ).borderColor;
        document.querySelectorAll(".chartjs").forEach(function(o) {
          o.height = o.dataset.height;
        });
        var p = document.getElementById("barChart1"),
          p =
          (p &&
            new Chart(p, {
              type: "bar",
              data: {
                labels: [
                  "Jan",
                  "Feb",
                  "Mar",
                  "Apr",
                  "May",
                  "June",
                  "July",
                  "Aug",
                  "Sep",
                  "Oct",
                  "Nov",
                  "Dec"
                ],
                datasets: [{
                  data: [
                    275, 90, 190, 205, 125, 85, 55, 87, 127, 150, 230, 280,
                  ],
                  backgroundColor: t,
                  borderColor: "transparent",
                  maxBarThickness: 15,
                  borderRadius: {
                    topRight: 15,
                    topLeft: 15
                  },
                }, ],
              },
              options: {
                responsive: !0,
                maintainAspectRatio: !1,
                animation: {
                  duration: 500
                },
                plugins: {
                  tooltip: {
                    rtl: isRtl,
                    backgroundColor: i,
                    titleColor: n,
                    bodyColor: c,
                    borderWidth: 1,
                    borderColor: s,
                  },
                  legend: {
                    display: !1
                  },
                },
                scales: {
                  x: {
                    grid: {
                      color: s,
                      drawBorder: !1,
                      borderColor: s
                    },
                    ticks: {
                      color: d
                    },
                  },
                  y: {
                    min: 0,
                    max: 400,
                    grid: {
                      color: s,
                      drawBorder: !1,
                      borderColor: s
                    },
                    ticks: {
                      stepSize: 100,
                      color: d
                    },
                  },
                },
              },
            }),
            document.getElementById("horizontalBarChart"))
      })();
    });
    // Get the current page's URL
    var currentUrl = window.location.href;

    // Select all menu links
    var menuLinks = document.querySelectorAll('.menu-link');

    // Loop through the menu links and check if their href matches the current URL
    menuLinks.forEach(function(link) {
      var href = link.getAttribute('href');
      if (currentUrl.indexOf(href) !== -1) {
        // Add the "active" class to the matching menu item
        link.classList.add('active');
      }
    });

    // JavaScript code to toggle the sub-menu visibility
    const hotelsMenuItem = document.getElementById('hotels-menu-item');
    const subMenu = hotelsMenuItem.querySelector('.menu-sub');

    hotelsMenuItem.addEventListener('click', () => {
      // Toggle the visibility of the sub-menu
      subMenu.classList.toggle('show');
    });
  </script>

  <script>
    var swiper = new Swiper('#hotspot-list .swiper-initialized', {
      slidesPerView: 3,
      spaceBetween: 10,
      navigation: {
        nextEl: '.hotspot-list-swiper-button-next',
        prevEl: '.hotspot-list-swiper-button-prev',
      },
      autoplay: {
        delay: 5000, // 5 seconds delay between slides
        disableOnInteraction: false, // Do not stop autoplay on interaction
      },
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var a = document.querySelector("#deliveryExceptionsChart");
      var o = {
        chart: {
          height: 320,
          parentHeightOffset: 0,
          type: "donut"
        },
        labels: [
          "Hotel",
          "Vehicle",
          "Both Hotel & Vehicle",
        ],
        series: [<?= getDASHBOARD_COUNT_DETAILS('hotel_preference_count'); ?>, <?= getDASHBOARD_COUNT_DETAILS('vehicle_preference_count'); ?>, <?= getDASHBOARD_COUNT_DETAILS('both_preference_count'); ?>],
        colors: [
          "#f1ca56",
          "#6779d6",
          "#d73c23a1",
        ],
        stroke: {
          width: 0
        },
        dataLabels: {
          enabled: false,
          formatter: function(e, t) {
            return parseInt(e);
          },
        },
        legend: {
          show: true,
          position: "bottom",
          offsetY: 10,
          markers: {
            width: 8,
            height: 8,
            offsetX: -3
          },
          itemMargin: {
            horizontal: 15,
            vertical: 5
          },
          fontSize: "13px",
          fontFamily: "Public Sans",
          fontWeight: 400,
          labels: {
            colors: "black",
            useSeriesColors: false
          },
        },
        tooltip: {
          theme: false
        },
        grid: {
          padding: {
            top: 15
          }
        },
        states: {
          hover: {
            filter: {
              type: "none"
            }
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: "77%",
              labels: {
                show: true,
                value: {
                  fontSize: "26px",
                  fontFamily: "Public Sans",
                  color: "black",
                  fontWeight: 500,
                  offsetY: -30,
                  formatter: function(e) {
                    return parseInt(e);
                  },
                },
                name: {
                  offsetY: 20,
                  fontFamily: "Public Sans"
                },
                total: {
                  show: true,
                  fontSize: ".65rem",
                  label: "TOTAL PREFERENCE",
                  color: "black",
                  formatter: function(e) {
                    return "<?= getDASHBOARD_COUNT_DETAILS('total_preference_count'); ?>";
                  },
                },
              },
            },
          },
        },
        responsive: [{
          breakpoint: 420,
          options: {
            chart: {
              height: 360
            }
          }
        }],
      };
      new ApexCharts(a, o).render();
    });

    document.addEventListener("DOMContentLoaded", function() {
      var a = document.querySelector("#agentdeliveryExceptionsChart");
      var o = {
        chart: {
          height: 320,
          parentHeightOffset: 0,
          type: "donut"
        },
        labels: [
          "Hotel",
          "Vehicle",
          "Both Hotel & Vehicle",
        ],
        series: [<?= getDASHBOARD_COUNT_DETAILS('hotel_preference_count', $logged_agent_id); ?>, <?= getDASHBOARD_COUNT_DETAILS('vehicle_preference_count', $logged_agent_id); ?>, <?= getDASHBOARD_COUNT_DETAILS('both_preference_count', $logged_agent_id); ?>],
        colors: [
          "#f1ca56",
          "#6779d6",
          "#d73c23a1",
        ],
        stroke: {
          width: 0
        },
        dataLabels: {
          enabled: false,
          formatter: function(e, t) {
            return parseInt(e);
          },
        },
        legend: {
          show: true,
          position: "bottom",
          offsetY: 10,
          markers: {
            width: 8,
            height: 8,
            offsetX: -3
          },
          itemMargin: {
            horizontal: 15,
            vertical: 5
          },
          fontSize: "13px",
          fontFamily: "Public Sans",
          fontWeight: 400,
          labels: {
            colors: "black",
            useSeriesColors: false
          },
        },
        tooltip: {
          theme: false
        },
        grid: {
          padding: {
            top: 15
          }
        },
        states: {
          hover: {
            filter: {
              type: "none"
            }
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: "77%",
              labels: {
                show: true,
                value: {
                  fontSize: "26px",
                  fontFamily: "Public Sans",
                  color: "black",
                  fontWeight: 500,
                  offsetY: -30,
                  formatter: function(e) {
                    return parseInt(e);
                  },
                },
                name: {
                  offsetY: 20,
                  fontFamily: "Public Sans"
                },
                total: {
                  show: true,
                  fontSize: ".65rem",
                  label: "TOTAL PREFERENCE",
                  color: "black",
                  formatter: function(e) {
                    return "<?= getDASHBOARD_COUNT_DETAILS('total_preference_count',$logged_agent_id); ?>";
                  },
                },
              },
            },
          },
        },
        responsive: [{
          breakpoint: 420,
          options: {
            chart: {
              height: 360
            }
          }
        }],
      };
      new ApexCharts(a, o).render();
    });
  </script>


  <script>
    var options = {
      series: [{
        name: 'Profit',
        color: '#0012559e',
        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
      }, {
        name: 'Due',
        color: '#d7232391',
        data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
      }],
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '30%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
      },
      yaxis: {
        title: {
          show: false,
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return "$ " + val + " thousands"
          }
        }
      }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();



    document.addEventListener("DOMContentLoaded", function() {
      var a = document.querySelector("#itineraryoverviewchart");
      var o = {
        chart: {
          height: 320,
          parentHeightOffset: 0,
          type: "donut"
        },
        labels: [
          "Hotel",
          "Vehicle",
          "Both Hotel & Vehicle",
        ],
        series: [35, 20, 55],
        colors: [
          "#f1ca56",
          "#6779d6",
          "#d73c23a1",
        ],
        stroke: {
          width: 0
        },
        dataLabels: {
          enabled: false,
          formatter: function(e, t) {
            return parseInt(e) + "%";
          },
        },
        legend: {
          show: true,
          position: "bottom",
          offsetY: 10,
          markers: {
            width: 8,
            height: 8,
            offsetX: -3
          },
          itemMargin: {
            horizontal: 15,
            vertical: 5
          },
          fontSize: "13px",
          fontFamily: "Public Sans",
          fontWeight: 400,
          labels: {
            colors: "black",
            useSeriesColors: false
          },
        },
        tooltip: {
          theme: false
        },
        grid: {
          padding: {
            top: 15
          }
        },
        states: {
          hover: {
            filter: {
              type: "none"
            }
          }
        },
        plotOptions: {
          pie: {
            donut: {
              size: "80%",
              labels: {
                show: true,
                value: {
                  fontSize: "26px",
                  fontFamily: "Public Sans",
                  color: "black",
                  fontWeight: 500,
                  offsetY: -30,
                  formatter: function(e) {
                    return parseInt(e) + "%";
                  },
                },
                name: {
                  offsetY: 20,
                  fontFamily: "Public Sans"
                },
                total: {
                  show: true,
                  fontSize: ".65rem",
                  label: "AVG. ITINERARY PREFERENCE",
                  color: "black",
                  formatter: function(e) {
                    return "45%";
                  },
                },
              },
            },
          },
        },
        responsive: [{
          breakpoint: 420,
          options: {
            chart: {
              height: 360
            }
          }
        }],
      };
      new ApexCharts(a, o).render();
    });
  </script>

</body>

</html>