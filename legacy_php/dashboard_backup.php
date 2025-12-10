<?php
include_once("jackus.php");
admin_reguser_protect();
// require_once('check_restriction.php');
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
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
  <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />

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
  <!-- <link rel="stylesheet" href="./assets/vendor/css/pages/ui-carousel.css" /> -->
  <link rel="stylesheet" href="./assets/css/dashboard.css">

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
                <!-- Welcome Banner -->
                <div class="col-md-12 p-4 fade-in-banner welcome-banner-section">
                  <div class="toast-container position-relative w-100">
                    <div class="bs-toast toast fade show w-100" role="alert" aria-live="assertive" aria-atomic="true" style="border-radius: 20px;">
                      <div class="toast-body p-0">
                        <button type="button" data-bs-dismiss="toast" aria-label="Close" style="font-size: 20px;font-weight: 700;position: absolute; top: 5px; right: 8px; color: #c800a5;box-sizing: content-box;padding: 0.25em 0.25em;border: 0;background: none;z-index: 9999">x</button>
                        <div class="row">
                          <div class="col-12 d-flex align-items-center px-0">
                            <img src="assets/img/login-background/bg.jpg" style="border-radius: 20px;" alt="bg" class="img-fluid">
                            <div class="welcome-banner-text-overlay welcome-banner-text-overlay-admin">
                              <h3 style="font-weight: bold; margin-bottom: 5px; color: #fff;" class="text-primary">Welcome, Administrator!</h3>
                              <p style="color: #000; margin-bottom: 5px;">Explore your quick reports below.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Welcome Banner -->

                <!-- Itinerary List -->
                <div class="col-lg-6 mb-4 col-sm-12 px-1">
                  <div class="row">
                    <div class="col-lg-12 d-flex">
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_hotel_count'); ?></h5>
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
                              <h5 class="mb-0 me-2">189</h5>
                              <small>Total Bookings</small>
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
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>183</b></p>
                                      <p class="mb-0">Hotel Count</p>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>72</b></p>
                                      <p class="mb-0">Total Bookings</p>
                                    </li>
                                  </ul>
                                </div>
                                <div class="col-6">
                                  <ul class="list-unstyled mb-0">
                                    <li class="d-flex align-items-center mb-4">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>873</b></p>
                                      <p class="mb-0">Room Count</p>
                                    </li>
                                    <li class="d-flex align-items-center mb-2">
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>150</b></p>
                                      <p class="mb-0">Total Guests</p>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>122</b></p>
                                    <p class="mb-0">Total Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>7</b></p>
                                    <p class="mb-0">Available Vehicles</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>57</b></p>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>7</b></p>
                                    <p class="mb-0">Active Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>6</b></p>
                                    <p class="mb-0">In-active Drivers</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>3</b></p>
                                    <p class="mb-0">On Route Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>20</b></p>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>37</b></p>
                                    <p class="mb-0">Total Vendors</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>4</b></p>
                                    <p class="mb-0">Top Vendors</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b>15</b></p>
                                    <p class="mb-0">Active Vendors</p>
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

                <!-- Most Visited Hotels -->
                <div class="col-lg-7 col-sm-12 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex justify-content-between">
                      <div class="card-title mb-0 col-8">
                        <h5 class="mb-0">Most Visited Hotels</h5>
                        <small class="text-muted">Top 5 Picks by Visitors</small>
                      </div>
                      <div class="col-4 d-flex justify-content-between">
                        <div class="dropdown">
                          <div class="dropdown">
                            <button type="button" class="btn btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">January</button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="javascript:void(0);">January</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">February</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">March</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">April</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">May</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">June</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">July</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">August</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">September</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">October</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">November</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">December</a></li>
                            </ul>
                          </div>
                        </div>
                        <div class="dropdown">
                          <div class="dropdown">
                            <button type="button" class="btn btn-label-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">2024</button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="javascript:void(0);">2024</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">2023</a></li>
                              <li><a class="dropdown-item" href="javascript:void(0);">2022</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="mb-3 pb-1 d-flex">
                          <div class="d-flex w-50 align-items-center me-3">
                            <img src="assets/img/dashboard/hotel-2.png" alt="hotel-logo" class="me-3" width="35" />
                            <div>
                              <h6 class="mb-0">The Park Hotel</h6>
                              <small class="text-muted">Nungambakkam, Chennai</small>
                            </div>
                          </div>
                          <div class="d-flex flex-grow-1 align-items-center">
                            <div class="progress w-100 me-3" style="height:8px;">
                              <div class="progress-bar bg-primary" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                              </div>
                            </div>
                            <span class="text-muted">75%</span>
                          </div>
                        </li>
                        <li class="mb-3 pb-1 d-flex">
                          <div class="d-flex w-50 align-items-center me-3">
                            <img src="assets/img/dashboard/hotel-2.png" alt="hotel-logo" class="me-3" width="35" />
                            <div>
                              <h6 class="mb-0">ITC Grand Chola</h6>
                              <small class="text-muted">Guindy, Chennai</small>
                            </div>
                          </div>
                          <div class="d-flex flex-grow-1 align-items-center">
                            <div class="progress w-100 me-3" style="height:8px;">
                              <div class="progress-bar bg-danger" role="progressbar" style="width: 58%" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
                              </div>
                            </div>
                            <span class="text-muted">58%</span>
                          </div>
                        </li>
                        <li class="mb-3 pb-1 d-flex">
                          <div class="d-flex w-50 align-items-center me-3">
                            <img src="assets/img/dashboard/hotel-2.png" alt="hotel-logo" class="me-3" width="35" />
                            <div>
                              <h6 class="mb-0">Hyatt Regency Chennai</h6>
                              <small class="text-muted">Teynampet, Chennai</small>
                            </div>
                          </div>
                          <div class="d-flex flex-grow-1 align-items-center">
                            <div class="progress w-100 me-3" style="height:8px;">
                              <div class="progress-bar bg-success" role="progressbar" style="width: 46%" aria-valuenow="46" aria-valuemin="0" aria-valuemax="100">
                              </div>
                            </div>
                            <span class="text-muted">46%</span>
                          </div>
                        </li>
                        <li class="mb-3 pb-1 d-flex">
                          <div class="d-flex w-50 align-items-center me-3">
                            <img src="assets/img/dashboard/hotel-2.png" alt="hotel-logo" class="me-3" width="35" />
                            <div>
                              <h6 class="mb-0">Turyaa Chennai</h6>
                              <small class="text-muted">Perungudi, Chennai</small>
                            </div>
                          </div>
                          <div class="d-flex flex-grow-1 align-items-center">
                            <div class="progress w-100 me-3" style="height:8px;">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 23%" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100">
                              </div>
                            </div>
                            <span class="text-muted">23%</span>
                          </div>
                        </li>
                        <li class="mb-3 pb-1 d-flex">
                          <div class="d-flex w-50 align-items-center me-3">
                            <img src="assets/img/dashboard/hotel-2.png" alt="hotel-logo" class="me-3" width="35" />
                            <div>
                              <h6 class="mb-0">Taj Club House</h6>
                              <small class="text-muted"> Royapettah, Chennai</small>
                            </div>
                          </div>
                          <div class="d-flex flex-grow-1 align-items-center">
                            <div class="progress w-100 me-3" style="height:8px;">
                              <div class="progress-bar bg-warning" role="progressbar" style="width: 17%" aria-valuenow="17" aria-valuemin="0" aria-valuemax="100">
                              </div>
                            </div>
                            <span class="text-muted">17%</span>
                          </div>
                        </li>
                      </ul>
                    </div>
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

                <!-- Itinerary List -->
                <div class="col-md-12">
                  <h4 class="mt-3">Itinerary List</h4>
                  <div class="nav-align-top mb-4" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-overall" aria-controls="navs-overall" aria-selected="true">Overall</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-draft" aria-controls="navs-draft" aria-selected="false">Draft</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-upcoming" aria-controls="navs-upcoming" aria-selected="false">Upcoming</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-oncoming" aria-controls="navs-oncoming" aria-selected="false">Ongoing</button>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="navs-overall" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Confirmed</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-warning">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>3.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-secondary">draft</span></td>
                                </tr>
                                <tr>
                                  <td>4.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                                <tr>
                                  <td>5.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-warning">Ongoing</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-draft" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-upcoming" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-oncoming" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
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

                <!-- Agents List -->
                <div class="col-md-12 mb-4">
                  <div class="card p-4">
                    <h4>List of Agents</h4>
                    <div class="dataTable_select text-nowrap">
                      <div class="text-nowrap table-responsive table-bordered">
                        <table class="table table-hover" id="agent_LIST">
                          <thead>
                            <tr>
                              <th scope="col">S.No</th>
                              <th scope="col">Agent Name</th>
                              <th scope="col">Agent Email</th>
                              <th scope="col">Mobile Number</th>
                              <th scope="col">Travel Expert</th>
                              <th scope="col">Subscription Title</th>
                              <th scope="col">Status</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1.</td>
                              <td>Dvi</td>
                              <td>demo@dvi.co.in </td>
                              <td>9632587410</td>
                              <td></td>
                              <td>Premium / 90 Days
                              </td>
                              <td>
                                <div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked="" onchange="togglestatusITEM(1,8);"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>
                              </td>
                            </tr>
                            <tr>
                              <td>2.</td>
                              <td>Sandeep</td>
                              <td>sandeepnaidu2295@gmail.com	</td>
                              <td>9043291900</td>
                              <td></td>
                              <td>Free / 30 Days	

                              </td>
                              <td>
                                <div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked="" onchange="togglestatusITEM(1,8);"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>
                              </td>
                            </tr>
                            <tr>
                              <td>3.</td>
                              <td>Uma</td>
                              <td>uma@touchmarkdes.com	</td>
                              <td>7633456554</td>
                              <td></td>
                              <td>Premium / 90 Days
                              </td>
                              <td>
                                <div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked="" onchange="togglestatusITEM(1,8);"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Agents List -->

                <!-- Hotspot List -->
                <div class="col-12 mb-4" id="hotspot-list">
                  <div class="card-header d-flex align-items-center justify-content-between p-3">
                    <div class="card-title mb-0 d-flex justify-content-between w-100">
                      <div>
                        <h5 class="card-title m-0 me-2">Hotspot List</h5>
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

                <!-- State-wise Itinerary -->
                <!-- <div class="col-5 mb-4">
                  <div class="card h-100">
                    <div class="card-header header-elements">
                      <div class="d-flex flex-column">
                        <h5 class="m-0 me-2">Top Visiting Countries</h5>
                        <small>Visitors from different countries</small>
                      </div>
                      <div class="card-action-element ms-auto py-0">
                        <div class="dropdown">
                          <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-calendar"></i></button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
                            <li>
                              <hr class="dropdown-divider">
                            </li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <canvas id="horizontalBarChart" class="chartjs" data-height="400"></canvas>
                    </div>
                  </div>
                </div> -->
                <!--/ State-wise Itinerary -->

                <!-- Itinerary Detailed Overview -->
                <!-- <div class="col-6 mb-4">
                <div class="card h-100">
                  <div class="card-header header-elements">
                    <div class="d-flex flex-column">
                      <h5 class="m-0 me-2">Top Visiting Countries</h5>
                      <small>Visitors from different countries</small>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="itinerary_detailed_overview">
                      <div class="itinerary_detailed_overview_div_section">
                        <div class="content">
                          <h2>Jane Doe</h2>
                          <span>UI & UX Designer</span>
                        </div>
                      </div>
                      <div class="itinerary_detailed_overview_div_section">
                        <div class="content">
                          <h2>Alex Smith</h2>
                          <span>CEO Expert</span>
                        </div>
                      </div>
                      <div class="itinerary_detailed_overview_div_section">
                        <div class="content">
                          <h2>Emily New</h2>
                          <span>Web Designer</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
                <!--/ Itinerary Detailed Overview -->

                <!-- DVI Hotspot -->
                <!-- <div class="col-6 mb-4">
                <div class="card h-100">
                  <div class="card-header header-elements justify-content-between">
                    <div class="d-flex flex-column">
                      <h5 class="card-title mb-1">DVI Hotspot</h5>
                    </div>
                    <div class="btn-group">
                      <button class="btn btn-primary dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">Chennai</button>
                      <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                        <li><a class="dropdown-item" href="javascript:void(0)">Chennai</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)">Trichy</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)">Dindugul</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)">Madurai</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)">Coimbatore</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)">Karur</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-body">
                    <div id="DVIHotspotSection" class="carousel slide" data-bs-ride="carousel">
                      <ol class="carousel-indicators">
                        <li data-bs-target="#DVIHotspotSection" data-bs-slide-to="0" class="active"></li>
                        <li data-bs-target="#DVIHotspotSection" data-bs-slide-to="1"></li>
                        <li data-bs-target="#DVIHotspotSection" data-bs-slide-to="2"></li>
                      </ol>
                      <div class="carousel-inner">
                        <div class="carousel-item active">
                          <img class="d-block" src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" width="100%" height="350" alt="First slide" />
                          <div class="overlay_text_section_DVIHotspotSection text-center pb-3">
                            <h4 class="mb-1">Marina Beach</h4>
                            <p>Lively urban beach in Chennai, India, stretching along the Bay of Bengal, popular for its golden sands and bustling atmosphere.</p>
                          </div>
                        </div>
                        <div class="carousel-item">
                          <img class="d-block" src="assets/img/itinerary/hotspots/kapaleeshwarar_temple_1.jpeg" width="100%" height="350" alt="Second slide" />
                          <div class="overlay_text_section_DVIHotspotSection text-center pb-3">
                            <h4 class="mb-1">Kapaleeshwarar Temple</h4>
                            <p>Ancient Hindu temple in Chennai, India, dedicated to Lord Shiva, known for its Dravidian architecture and vibrant festivals.</p>
                          </div>
                        </div>
                        <div class="carousel-item">
                          <img class="d-block" src="assets/img/itinerary/hotspots/pondy_bazaar_1.jpeg" width="100%" height="350" alt="Third slide" />
                          <div class="overlay_text_section_DVIHotspotSection text-center pb-3">
                            <h4 class="mb-1">Pondy Bazaar</h4>
                            <p>Vibrant shopping district in Chennai, India, offering a mix of traditional and modern shopping experiences.</p>
                          </div>
                        </div>
                      </div>
                      <a class="carousel-control-prev" href="#DVIHotspotSection" role="button" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                      </a>
                      <a class="carousel-control-next" href="#DVIHotspotSection" role="button" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div> -->
                <!--/ DVI Hotspot -->

                <!-- <div class="col-12 mb-4 d-flex justify-content-center align-items-center">
                  <div class="itinerary-package-section">
                    <div class="card" style="--clr: #009688">
                      <div class="img-box">
                        <img src="assets/img/itinerary/hotspots/marina_beach_1.jpeg" alt="Image">
                      </div>
                      <div class="content">
                        <h2>Chennai to Pondicherry</h2>
                        <p>
                          Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                          Architecto, hic? Magnam eum error saepe doloribus corrupti
                          repellat quisquam alias doloremque!
                        </p>
                        <a href="">Read More</a>
                      </div>
                    </div>
                    <div class="card" style="--clr: #FF3E7F">
                      <div class="img-box">
                        <img src="assets/img/itinerary/hotspots/surf.jpg" alt="Image">
                      </div>
                      <div class="content">
                        <h2>Fruits</h2>
                        <p>
                          Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                          Architecto, hic? Magnam eum error saepe doloribus corrupti
                          repellat quisquam alias doloremque!
                        </p>
                        <a href="">Read More</a>
                      </div>
                    </div>
                    <div class="card" style="--clr: #03A9F4">
                      <div class="img-box">
                        <img src="assets/img/itinerary/hotspots/yoga.jpg" alt="Image">
                      </div>
                      <div class="content">
                        <h2>Flowers</h2>
                        <p>
                          Lorem ipsum, dolor sit amet consectetur adipisicing elit.
                          Architecto, hic? Magnam eum error saepe doloribus corrupti
                          repellat quisquam alias doloremque!
                        </p>
                        <a href="">Read More</a>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>
            <?php elseif ($logged_agent_id != '' &&  $logged_agent_id != '0') : ?>

              <div class="row">
                <div class="col-md-3 mb-3 d-flex">
                  <div class="card w-100 card-border-shadow-warning h-100">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                      <div class="card-title mb-0">
                        <h5 class="mb-0 me-2 fw-bold"><?= getDASHBOARD_COUNT_DETAILS('total_hotel_count'); ?></h5>
                        <small>Total Itineraries</small>
                      </div>
                      <div class="card-icon">
                        <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3 d-flex">
                  <div class="card w-100 card-border-shadow-info h-100">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                      <div class="card-title mb-0">
                        <h5 class="mb-0 me-2 fw-bold">34</h5>
                        <small>Confirmed Itineraries</small>
                      </div>
                      <div class="card-icon">
                        <img src="assets/img/dashboard/journey2.png" alt="visitors" width="80">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3 d-flex">
                  <div class="card w-100 card-border-shadow-primary h-100">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                      <div class="card-title mb-0">
                        <h5 class="mb-0 me-2 fw-bold">10</h5>
                        <small>Draft Itineraries</small>
                      </div>
                      <div class="card-icon">
                        <img src="assets/img/dashboard/draft.png" alt="visitors" width="90">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3 d-flex">
                  <div class="card w-100 card-border-shadow-danger h-100">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                      <div class="card-title mb-0">
                        <h5 class="mb-0 me-2 fw-bold">48</h5>
                        <small>Upcoming Itineraries</small>
                      </div>
                      <div class="card-icon">
                        <img src="assets/img/dashboard/visitors.png" alt="visitors" width="130">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-3 mb-3 d-flex">
                  <div class="card w-100 card-border-shadow-success h-100">
                    <div class="card-body d-flex justify-content-between align-items-center p-3">
                      <div class="card-title mb-0">
                        <h5 class="mb-0 me-2 fw-bold">29</h5>
                        <small>Ongoing Itineraries</small>
                      </div>
                      <div class="card-icon">
                        <img src="assets/img/dashboard/ongoing.jpeg" alt="visitors" width="90">
                      </div>
                    </div>
                  </div>
                </div>



                <div class="col-md-12">
                  <h4 class="mt-3">Itinerary List</h4>
                  <div class="nav-align-top mb-4" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-overall" aria-controls="navs-overall" aria-selected="true">Overall</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-draft" aria-controls="navs-draft" aria-selected="false">Draft</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-upcoming" aria-controls="navs-upcoming" aria-selected="false">Upcoming</button>
                      </li>
                      <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-oncoming" aria-controls="navs-oncoming" aria-selected="false">Ongoing</button>
                      </li>
                    </ul>
                    <div class="tab-content">
                      <div class="tab-pane fade show active" id="navs-overall" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Confirmed</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-warning">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>3.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-secondary">draft</span></td>
                                </tr>
                                <tr>
                                  <td>4.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                                <tr>
                                  <td>5.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-warning">Ongoing</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-draft" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-upcoming" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="navs-oncoming" role="tabpanel">
                        <div class="card-body dataTable_select text-nowrap">
                          <div class="text-nowrap table-responsive table-bordered">
                            <table class="table table-hover" id="agent_LIST">
                              <thead>
                                <tr>
                                  <th scope="col">S.No</th>
                                  <th scope="col">Quote ID</th>
                                  <th scope="col">Source</th>
                                  <th scope="col">Destination</th>
                                  <th scope="col">Start Date</th>
                                  <th scope="col">End Date</th>
                                  <th scope="col">Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>1.</td>
                                  <td>DVI202408-001</td>
                                  <td>Cochin, Kerala, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>07/08/2024 07:00 AM</td>
                                  <td>12/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-success">Ongoing</span></td>
                                </tr>
                                <tr>
                                  <td>2.</td>
                                  <td>DVI202408-002</td>
                                  <td>Chennai, Tamil Nadu, India</td>
                                  <td>Trivandrum, Kerala, India</td>
                                  <td>08/08/2024 07:00 AM</td>
                                  <td>15/08/2024 08:00 PM</td>
                                  <td><span class="badge bg-label-info">Upcoming</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 mb-4">
                  <div class="card p-4">
                    <h4>Daily Moment</h4>
                    <div class="dataTable_select text-nowrap">
                      <div class="text-nowrap table-responsive table-bordered">
                        <table class="table table-hover" id="agent_LIST">
                          <thead>
                            <tr>
                              <th scope="col">S.No</th>
                              <th scope="col">Quote ID</th>
                              <th scope="col">Location</th>
                              <th scope="col">Current Day</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>1.</td>
                              <td>DVI202408-001</td>
                              <td>Cochin, Kerala, India</td>
                              <td>Day3</td>
                            </tr>
                            <tr>
                              <td>2.</td>
                              <td>DVI202408-002</td>
                              <td>Chennai, Tamil Nadu, India</td>
                              <td>Day5</td>
                            </tr>
                            <tr>
                              <td>3.</td>
                              <td>DVI202408-002</td>
                              <td>Chennai, Tamil Nadu, India</td>
                              <td>Day20</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-8 d-flex">
                  <div class="card w-100 p-4">
                    <h4>Invoice Details</h4>
                    <div id="chart"></div>
                  </div>
                </div>
                <div class="col-md-4 d-flex">
                  <div class="card w-100 p-4">
                    <h4>Itinerary Overview</h4>
                    <div id="itineraryoverviewchart"></div>
                  </div>
                </div>


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
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

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
  <script>
    $(document).ready(function() {
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
        series: [35, 20, 55],
        colors: [
          "rgb(215, 0, 179)",
          "rgba(215, 0, 179, 0.6)",
          "rgba(215, 0, 179, 0.3)",
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