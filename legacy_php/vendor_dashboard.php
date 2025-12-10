<?php
include_once("jackus.php");
admin_reguser_protect();
$vendor_id = $logged_user_id;
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

                <!-- Navbar -->

                <?php include_once('public/__topbar.php'); ?>


                <!-- / Navbar -->
                <div class="content-wrapper">

                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">


                        <div class=" d-flex justify-content-between align-items-center">
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
                        <div class="d-flex text-primary justify-content-between">
                            <div>
                                <h4>Welcome, <?= ucwords(getUSERDETAIL($logged_user_id, 'user_name')); ?></h4>
                            </div>
                            <div>
                                <!--<p class="">06-10-2023, 2:00 PM</p>-->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 mb-4 col-md-12">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title mb-0">Vechile Overview</h5>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="row gy-3">
                                            <div class="col-md-6 col-6">
                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded bg-label-info"><i class="ti ti-car ti-md"></i></span>
                                                    </div>
                                                    <h4 class="ms-1 mb-0">42</h4>
                                                </div>
                                                <p class="mb-1">On Route Vehicles</p>
                                            </div>
                                            <div class="col-md-6 col-6">
                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-car ti-md"></i></span>
                                                    </div>
                                                    <h4 class="ms-1 mb-0">42</h4>
                                                </div>
                                                <p class="mb-1">Available Vehicles</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 mb-4 col-md-12">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title mb-0">Drivers Overview</h5>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="row gy-3">
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded bg-label-success"><i class="ti ti-users ti-md"></i></span>
                                                    </div>
                                                    <h4 class="ms-1 mb-0"><?= getVENDOR_DASHBOARD_DETAILS_COUNT($vendor_id, 'vendor_driver_active_count'); ?><h4>
                                                </div>
                                                <p class="mb-1">Active Drivers</p>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-users ti-md"></i></span>
                                                    </div>
                                                    <h4 class="ms-1 mb-0"><?= getVENDOR_DASHBOARD_DETAILS_COUNT($vendor_id, 'vendor_driver_inactive_count'); ?></h4>
                                                </div>
                                                <p class="mb-1">In-active Drivers</p>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded bg-label-info"><i class="ti ti-steering-wheel ti-md"></i></span>
                                                    </div>
                                                    <h4 class="ms-1 mb-0">42</h4>
                                                </div>
                                                <p class="mb-1">On Route Drivers</p>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="d-flex align-items-center mb-2 pb-1">
                                                    <div class="avatar me-2">
                                                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-steering-wheel ti-md"></i></span>
                                                    </div>
                                                    <h4 class="ms-1 mb-0">42</h4>
                                                </div>
                                                <p class="mb-1">Available Drivers</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Bar Charts -->
                            <!-- <div class="col-12 mb-4">
                <div class="card">
                  <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Latest Statistics</h5>
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
                    <canvas id="barChart1" class="chartjs" data-height="400"></canvas>
                  </div>
                </div>
              </div> -->
                            <!-- /Bar Charts -->

                            <!-- Vehicles overview -->
                            <div class="col-md-6 col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0">Todays Trip Summary</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-none d-lg-flex vehicles-progress-labels mb-4">
                                            <div class="vehicles-progress-label on-the-way-text" style="width: 39.7%;">On the way</div>
                                            <div class="vehicles-progress-label upcoming-text" style="width: 28.3%;">Upcoming Trips</div>
                                            <div class="vehicles-progress-label loading-text" style="width: 17.4%;">Available Vehicles</div>
                                            <div class="vehicles-progress-label waiting-text text-nowrap" style="width: 14.6%;">Waiting</div>
                                        </div>
                                        <div class="vehicles-overview-progress progress rounded-2 my-4" style="height: 46px;">
                                            <div class="progress-bar fw-medium text-start bg-body text-dark px-3 rounded-0" role="progressbar" style="width: 39.7%" aria-valuenow="39.7" aria-valuemin="0" aria-valuemax="100">39.7%</div>
                                            <div class="progress-bar fw-medium text-start bg-primary px-3" role="progressbar" style="width: 28.3%" aria-valuenow="28.3" aria-valuemin="0" aria-valuemax="100">28.3%</div>
                                            <div class="progress-bar fw-medium text-start text-bg-info px-3" role="progressbar" style="width: 17.4%" aria-valuenow="17.4" aria-valuemin="0" aria-valuemax="100">17.4%</div>
                                            <div class="progress-bar fw-medium text-start bg-gray-900 px-2 rounded-0 px-lg-2 px-xxl-3" role="progressbar" style="width: 14.6%" aria-valuenow="14.6" aria-valuemin="0" aria-valuemax="100">14.6%</div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table card-table">
                                                <tbody class="table-border-bottom-0">
                                                    <tr>
                                                        <td class="w-50 ps-0">
                                                            <div class="d-flex justify-content-start align-items-center">
                                                                <div class="me-2">
                                                                    <i class="ti ti-truck mt-n1"></i>
                                                                </div>
                                                                <h6 class="mb-0 fw-normal">On the way</h6>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-0 text-nowrap">
                                                            <h6 class="mb-0">2hr 10min</h6>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="fw-medium">39.7%</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 ps-0">
                                                            <div class="d-flex justify-content-start align-items-center">
                                                                <div class="me-2">
                                                                    <i class='ti ti-circle-arrow-down mt-n1'></i>
                                                                </div>
                                                                <h6 class="mb-0 fw-normal">Upcoming Trips</h6>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-0 text-nowrap">
                                                            <h6 class="mb-0">3hr 15min</h6>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="fw-medium">28.3%</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 ps-0">
                                                            <div class="d-flex justify-content-start align-items-center">
                                                                <div class="me-2">
                                                                    <i class='ti ti-circle-arrow-up mt-n1'></i>
                                                                </div>
                                                                <h6 class="mb-0 fw-normal">Available Vehicles</h6>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-0 text-nowrap">
                                                            <h6 class="mb-0">1hr 24min</h6>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="fw-medium">17.4%</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-50 ps-0">
                                                            <div class="d-flex justify-content-start align-items-center">
                                                                <div class="me-2">
                                                                    <i class="ti ti-clock mt-n1"></i>
                                                                </div>
                                                                <h6 class="mb-0 fw-normal">Waiting</h6>
                                                            </div>
                                                        </td>
                                                        <td class="text-end pe-0 text-nowrap">
                                                            <h6 class="mb-0">5hr 19min</h6>
                                                        </td>
                                                        <td class="text-end pe-0">
                                                            <span class="fw-medium">14.6%</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/ Vehicles overview -->

                            <!-- Polar Area Chart -->
                            <!-- <div class="col-lg-6 col-12 mb-4">
                                <div class="card">
                                    <div class="card-header header-elements">
                                        <h5 class="card-title mb-0">Collections - Branches</h5>
                                        <div class="card-header-elements ms-auto py-0 dropdown">
                                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" id="heat-chart-dd" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-dots-vertical"></i></button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="heat-chart-dd">
                                                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="polarChart" class="chartjs" data-height="337"></canvas>
                                    </div>
                                </div>
                            </div> -->
                            <!-- /Polar Area Chart -->
                            <div class="col-12 col-xl-6 mb-4 col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between pb-1">
                                        <h5 class="mb-0 card-title">Total Earning</h5>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="totalEarning" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarning">
                                                <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" style="position: relative;">
                                        <div class="d-flex align-items-center">
                                            <h1 class="mb-0 me-2">87%</h1>
                                            <i class="ti ti-chevron-up text-success me-1"></i>
                                            <p class="text-success mb-0">25.8%</p>
                                        </div>
                                        <div id="totalEarningChart" style="min-height: 230px;">
                                            <div id="apexchartsra5wdsk9" class="apexcharts-canvas apexchartsra5wdsk9 apexcharts-theme-light" style="width: 400px; height: 230px;"><svg id="SvgjsSvg1588" width="400" height="230" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;">
                                                    <g id="SvgjsG1590" class="apexcharts-inner apexcharts-graphical" transform="translate(15.89142857142857, -10)">
                                                        <defs id="SvgjsDefs1589">
                                                            <linearGradient id="SvgjsLinearGradient1593" x1="0" y1="0" x2="0" y2="1">
                                                                <stop id="SvgjsStop1594" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop>
                                                                <stop id="SvgjsStop1595" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                                                <stop id="SvgjsStop1596" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop>
                                                            </linearGradient>
                                                            <clipPath id="gridRectMaskra5wdsk9">
                                                                <rect id="SvgjsRect1598" width="405.99999999999994" height="245" x="-13.89142857142857" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                            </clipPath>
                                                            <clipPath id="forecastMaskra5wdsk9"></clipPath>
                                                            <clipPath id="nonForecastMaskra5wdsk9"></clipPath>
                                                            <clipPath id="gridRectMarkerMaskra5wdsk9">
                                                                <rect id="SvgjsRect1599" width="382.21714285714285" height="249" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect>
                                                            </clipPath>
                                                        </defs>
                                                        <rect id="SvgjsRect1597" width="0" height="245" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1593)" class="apexcharts-xcrosshairs" y2="245" filter="none" fill-opacity="0.9"></rect>
                                                        <g id="SvgjsG1621" class="apexcharts-xaxis" transform="translate(0, 0)">
                                                            <g id="SvgjsG1622" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g>
                                                        </g>
                                                        <g id="SvgjsG1631" class="apexcharts-grid">
                                                            <g id="SvgjsG1632" class="apexcharts-gridlines-horizontal" style="display: none;">
                                                                <line id="SvgjsLine1634" x1="-11.89142857142857" y1="0" x2="390.1085714285714" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                <line id="SvgjsLine1635" x1="-11.89142857142857" y1="49" x2="390.1085714285714" y2="49" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                <line id="SvgjsLine1636" x1="-11.89142857142857" y1="98" x2="390.1085714285714" y2="98" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                <line id="SvgjsLine1637" x1="-11.89142857142857" y1="147" x2="390.1085714285714" y2="147" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                <line id="SvgjsLine1638" x1="-11.89142857142857" y1="196" x2="390.1085714285714" y2="196" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                                <line id="SvgjsLine1639" x1="-11.89142857142857" y1="245" x2="390.1085714285714" y2="245" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line>
                                                            </g>
                                                            <g id="SvgjsG1633" class="apexcharts-gridlines-vertical" style="display: none;"></g>
                                                            <line id="SvgjsLine1641" x1="0" y1="245" x2="378.21714285714285" y2="245" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                            <line id="SvgjsLine1640" x1="0" y1="1" x2="0" y2="245" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line>
                                                        </g>
                                                        <g id="SvgjsG1600" class="apexcharts-bar-series apexcharts-plot-series">
                                                            <g id="SvgjsG1601" class="apexcharts-series" seriesName="Earning" rel="1" data:realIndex="0">
                                                                <path id="SvgjsPath1603" d="M -4.862791836734694 142L -4.862791836734694 60.124999999999986Q -4.862791836734694 55.124999999999986 0.13720816326530638 55.124999999999986L -0.13720816326530638 55.124999999999986Q 4.862791836734694 55.124999999999986 4.862791836734694 60.124999999999986L 4.862791836734694 60.124999999999986L 4.862791836734694 142Q 4.862791836734694 147 -0.13720816326530638 147L 0.13720816326530638 147Q -4.862791836734694 147 -4.862791836734694 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M -4.862791836734694 142L -4.862791836734694 60.124999999999986Q -4.862791836734694 55.124999999999986 0.13720816326530638 55.124999999999986L -0.13720816326530638 55.124999999999986Q 4.862791836734694 55.124999999999986 4.862791836734694 60.124999999999986L 4.862791836734694 60.124999999999986L 4.862791836734694 142Q 4.862791836734694 147 -0.13720816326530638 147L 0.13720816326530638 147Q -4.862791836734694 147 -4.862791836734694 142z" pathFrom="M -4.862791836734694 142L -4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L -4.862791836734694 142" cy="55.124999999999986" cx="4.862791836734694" j="0" val="15" barHeight="91.87500000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1604" d="M 49.168228571428564 142L 49.168228571428564 90.75Q 49.168228571428564 85.75 54.168228571428564 85.75L 53.89381224489795 85.75Q 58.89381224489795 85.75 58.89381224489795 90.75L 58.89381224489795 90.75L 58.89381224489795 142Q 58.89381224489795 147 53.89381224489795 147L 54.168228571428564 147Q 49.168228571428564 147 49.168228571428564 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 49.168228571428564 142L 49.168228571428564 90.75Q 49.168228571428564 85.75 54.168228571428564 85.75L 53.89381224489795 85.75Q 58.89381224489795 85.75 58.89381224489795 90.75L 58.89381224489795 90.75L 58.89381224489795 142Q 58.89381224489795 147 53.89381224489795 147L 54.168228571428564 147Q 49.168228571428564 147 49.168228571428564 142z" pathFrom="M 49.168228571428564 142L 49.168228571428564 142L 58.89381224489795 142L 58.89381224489795 142L 58.89381224489795 142L 58.89381224489795 142L 58.89381224489795 142L 49.168228571428564 142" cy="85.75" cx="58.89381224489796" j="1" val="10" barHeight="61.25000000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1605" d="M 103.19924897959183 142L 103.19924897959183 29.499999999999986Q 103.19924897959183 24.499999999999986 108.19924897959183 24.499999999999986L 107.92483265306122 24.499999999999986Q 112.92483265306122 24.499999999999986 112.92483265306122 29.499999999999986L 112.92483265306122 29.499999999999986L 112.92483265306122 142Q 112.92483265306122 147 107.92483265306122 147L 108.19924897959183 147Q 103.19924897959183 147 103.19924897959183 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 103.19924897959183 142L 103.19924897959183 29.499999999999986Q 103.19924897959183 24.499999999999986 108.19924897959183 24.499999999999986L 107.92483265306122 24.499999999999986Q 112.92483265306122 24.499999999999986 112.92483265306122 29.499999999999986L 112.92483265306122 29.499999999999986L 112.92483265306122 142Q 112.92483265306122 147 107.92483265306122 147L 108.19924897959183 147Q 103.19924897959183 147 103.19924897959183 142z" pathFrom="M 103.19924897959183 142L 103.19924897959183 142L 112.92483265306122 142L 112.92483265306122 142L 112.92483265306122 142L 112.92483265306122 142L 112.92483265306122 142L 103.19924897959183 142" cy="24.499999999999986" cx="112.9248326530612" j="2" val="20" barHeight="122.50000000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1606" d="M 157.2302693877551 142L 157.2302693877551 103Q 157.2302693877551 98 162.2302693877551 98L 161.9558530612245 98Q 166.9558530612245 98 166.9558530612245 103L 166.9558530612245 103L 166.9558530612245 142Q 166.9558530612245 147 161.9558530612245 147L 162.2302693877551 147Q 157.2302693877551 147 157.2302693877551 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 157.2302693877551 142L 157.2302693877551 103Q 157.2302693877551 98 162.2302693877551 98L 161.9558530612245 98Q 166.9558530612245 98 166.9558530612245 103L 166.9558530612245 103L 166.9558530612245 142Q 166.9558530612245 147 161.9558530612245 147L 162.2302693877551 147Q 157.2302693877551 147 157.2302693877551 142z" pathFrom="M 157.2302693877551 142L 157.2302693877551 142L 166.9558530612245 142L 166.9558530612245 142L 166.9558530612245 142L 166.9558530612245 142L 166.9558530612245 142L 157.2302693877551 142" cy="98" cx="166.95585306122445" j="3" val="8" barHeight="49.00000000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1607" d="M 211.26128979591834 142L 211.26128979591834 78.5Q 211.26128979591834 73.5 216.26128979591834 73.5L 215.98687346938772 73.5Q 220.98687346938772 73.5 220.98687346938772 78.5L 220.98687346938772 78.5L 220.98687346938772 142Q 220.98687346938772 147 215.98687346938772 147L 216.26128979591834 147Q 211.26128979591834 147 211.26128979591834 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 211.26128979591834 142L 211.26128979591834 78.5Q 211.26128979591834 73.5 216.26128979591834 73.5L 215.98687346938772 73.5Q 220.98687346938772 73.5 220.98687346938772 78.5L 220.98687346938772 78.5L 220.98687346938772 142Q 220.98687346938772 147 215.98687346938772 147L 216.26128979591834 147Q 211.26128979591834 147 211.26128979591834 142z" pathFrom="M 211.26128979591834 142L 211.26128979591834 142L 220.98687346938772 142L 220.98687346938772 142L 220.98687346938772 142L 220.98687346938772 142L 220.98687346938772 142L 211.26128979591834 142" cy="73.5" cx="220.98687346938772" j="4" val="12" barHeight="73.5" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1608" d="M 265.2923102040816 142L 265.2923102040816 41.749999999999986Q 265.2923102040816 36.749999999999986 270.2923102040816 36.749999999999986L 270.017893877551 36.749999999999986Q 275.017893877551 36.749999999999986 275.017893877551 41.749999999999986L 275.017893877551 41.749999999999986L 275.017893877551 142Q 275.017893877551 147 270.017893877551 147L 270.2923102040816 147Q 265.2923102040816 147 265.2923102040816 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 265.2923102040816 142L 265.2923102040816 41.749999999999986Q 265.2923102040816 36.749999999999986 270.2923102040816 36.749999999999986L 270.017893877551 36.749999999999986Q 275.017893877551 36.749999999999986 275.017893877551 41.749999999999986L 275.017893877551 41.749999999999986L 275.017893877551 142Q 275.017893877551 147 270.017893877551 147L 270.2923102040816 147Q 265.2923102040816 147 265.2923102040816 142z" pathFrom="M 265.2923102040816 142L 265.2923102040816 142L 275.017893877551 142L 275.017893877551 142L 275.017893877551 142L 275.017893877551 142L 275.017893877551 142L 265.2923102040816 142" cy="36.749999999999986" cx="275.017893877551" j="5" val="18" barHeight="110.25000000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1609" d="M 319.3233306122449 142L 319.3233306122449 78.5Q 319.3233306122449 73.5 324.3233306122449 73.5L 324.04891428571426 73.5Q 329.04891428571426 73.5 329.04891428571426 78.5L 329.04891428571426 78.5L 329.04891428571426 142Q 329.04891428571426 147 324.04891428571426 147L 324.3233306122449 147Q 319.3233306122449 147 319.3233306122449 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 319.3233306122449 142L 319.3233306122449 78.5Q 319.3233306122449 73.5 324.3233306122449 73.5L 324.04891428571426 73.5Q 329.04891428571426 73.5 329.04891428571426 78.5L 329.04891428571426 78.5L 329.04891428571426 142Q 329.04891428571426 147 324.04891428571426 147L 324.3233306122449 147Q 319.3233306122449 147 319.3233306122449 142z" pathFrom="M 319.3233306122449 142L 319.3233306122449 142L 329.04891428571426 142L 329.04891428571426 142L 329.04891428571426 142L 329.04891428571426 142L 329.04891428571426 142L 319.3233306122449 142" cy="73.5" cx="329.04891428571426" j="6" val="12" barHeight="73.5" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1610" d="M 373.3543510204081 142L 373.3543510204081 121.375Q 373.3543510204081 116.375 378.3543510204081 116.375L 378.0799346938775 116.375Q 383.0799346938775 116.375 383.0799346938775 121.375L 383.0799346938775 121.375L 383.0799346938775 142Q 383.0799346938775 147 378.0799346938775 147L 378.3543510204081 147Q 373.3543510204081 147 373.3543510204081 142z" fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 373.3543510204081 142L 373.3543510204081 121.375Q 373.3543510204081 116.375 378.3543510204081 116.375L 378.0799346938775 116.375Q 383.0799346938775 116.375 383.0799346938775 121.375L 383.0799346938775 121.375L 383.0799346938775 142Q 383.0799346938775 147 378.0799346938775 147L 378.3543510204081 147Q 373.3543510204081 147 373.3543510204081 142z" pathFrom="M 373.3543510204081 142L 373.3543510204081 142L 383.0799346938775 142L 383.0799346938775 142L 383.0799346938775 142L 383.0799346938775 142L 383.0799346938775 142L 373.3543510204081 142" cy="116.375" cx="383.0799346938775" j="7" val="5" barHeight="30.625000000000004" barWidth="9.725583673469387"></path>
                                                            </g>
                                                            <g id="SvgjsG1611" class="apexcharts-series" seriesName="Expense" rel="2" data:realIndex="1">
                                                                <path id="SvgjsPath1613" d="M -4.862791836734694 157L -4.862791836734694 189.875Q -4.862791836734694 194.875 0.13720816326530638 194.875L -0.13720816326530638 194.875Q 4.862791836734694 194.875 4.862791836734694 189.875L 4.862791836734694 189.875L 4.862791836734694 157Q 4.862791836734694 152 -0.13720816326530638 152L 0.13720816326530638 152Q -4.862791836734694 152 -4.862791836734694 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M -4.862791836734694 157L -4.862791836734694 189.875Q -4.862791836734694 194.875 0.13720816326530638 194.875L -0.13720816326530638 194.875Q 4.862791836734694 194.875 4.862791836734694 189.875L 4.862791836734694 189.875L 4.862791836734694 157Q 4.862791836734694 152 -0.13720816326530638 152L 0.13720816326530638 152Q -4.862791836734694 152 -4.862791836734694 157z" pathFrom="M -4.862791836734694 157L -4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L -4.862791836734694 157" cy="184.875" cx="4.862791836734694" j="0" val="-7" barHeight="-42.875" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1614" d="M 49.168228571428564 157L 49.168228571428564 208.25Q 49.168228571428564 213.25 54.168228571428564 213.25L 53.89381224489795 213.25Q 58.89381224489795 213.25 58.89381224489795 208.25L 58.89381224489795 208.25L 58.89381224489795 157Q 58.89381224489795 152 53.89381224489795 152L 54.168228571428564 152Q 49.168228571428564 152 49.168228571428564 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 49.168228571428564 157L 49.168228571428564 208.25Q 49.168228571428564 213.25 54.168228571428564 213.25L 53.89381224489795 213.25Q 58.89381224489795 213.25 58.89381224489795 208.25L 58.89381224489795 208.25L 58.89381224489795 157Q 58.89381224489795 152 53.89381224489795 152L 54.168228571428564 152Q 49.168228571428564 152 49.168228571428564 157z" pathFrom="M 49.168228571428564 157L 49.168228571428564 157L 58.89381224489795 157L 58.89381224489795 157L 58.89381224489795 157L 58.89381224489795 157L 58.89381224489795 157L 49.168228571428564 157" cy="203.25" cx="58.89381224489796" j="1" val="-10" barHeight="-61.25000000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1615" d="M 103.19924897959183 157L 103.19924897959183 189.875Q 103.19924897959183 194.875 108.19924897959183 194.875L 107.92483265306122 194.875Q 112.92483265306122 194.875 112.92483265306122 189.875L 112.92483265306122 189.875L 112.92483265306122 157Q 112.92483265306122 152 107.92483265306122 152L 108.19924897959183 152Q 103.19924897959183 152 103.19924897959183 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 103.19924897959183 157L 103.19924897959183 189.875Q 103.19924897959183 194.875 108.19924897959183 194.875L 107.92483265306122 194.875Q 112.92483265306122 194.875 112.92483265306122 189.875L 112.92483265306122 189.875L 112.92483265306122 157Q 112.92483265306122 152 107.92483265306122 152L 108.19924897959183 152Q 103.19924897959183 152 103.19924897959183 157z" pathFrom="M 103.19924897959183 157L 103.19924897959183 157L 112.92483265306122 157L 112.92483265306122 157L 112.92483265306122 157L 112.92483265306122 157L 112.92483265306122 157L 103.19924897959183 157" cy="184.875" cx="112.9248326530612" j="2" val="-7" barHeight="-42.875" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1616" d="M 157.2302693877551 157L 157.2302693877551 220.5Q 157.2302693877551 225.5 162.2302693877551 225.5L 161.9558530612245 225.5Q 166.9558530612245 225.5 166.9558530612245 220.5L 166.9558530612245 220.5L 166.9558530612245 157Q 166.9558530612245 152 161.9558530612245 152L 162.2302693877551 152Q 157.2302693877551 152 157.2302693877551 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 157.2302693877551 157L 157.2302693877551 220.5Q 157.2302693877551 225.5 162.2302693877551 225.5L 161.9558530612245 225.5Q 166.9558530612245 225.5 166.9558530612245 220.5L 166.9558530612245 220.5L 166.9558530612245 157Q 166.9558530612245 152 161.9558530612245 152L 162.2302693877551 152Q 157.2302693877551 152 157.2302693877551 157z" pathFrom="M 157.2302693877551 157L 157.2302693877551 157L 166.9558530612245 157L 166.9558530612245 157L 166.9558530612245 157L 166.9558530612245 157L 166.9558530612245 157L 157.2302693877551 157" cy="215.5" cx="166.95585306122445" j="3" val="-12" barHeight="-73.5" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1617" d="M 211.26128979591834 157L 211.26128979591834 183.75Q 211.26128979591834 188.75 216.26128979591834 188.75L 215.98687346938772 188.75Q 220.98687346938772 188.75 220.98687346938772 183.75L 220.98687346938772 183.75L 220.98687346938772 157Q 220.98687346938772 152 215.98687346938772 152L 216.26128979591834 152Q 211.26128979591834 152 211.26128979591834 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 211.26128979591834 157L 211.26128979591834 183.75Q 211.26128979591834 188.75 216.26128979591834 188.75L 215.98687346938772 188.75Q 220.98687346938772 188.75 220.98687346938772 183.75L 220.98687346938772 183.75L 220.98687346938772 157Q 220.98687346938772 152 215.98687346938772 152L 216.26128979591834 152Q 211.26128979591834 152 211.26128979591834 157z" pathFrom="M 211.26128979591834 157L 211.26128979591834 157L 220.98687346938772 157L 220.98687346938772 157L 220.98687346938772 157L 220.98687346938772 157L 220.98687346938772 157L 211.26128979591834 157" cy="178.75" cx="220.98687346938772" j="4" val="-6" barHeight="-36.75" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1618" d="M 265.2923102040816 157L 265.2923102040816 202.125Q 265.2923102040816 207.125 270.2923102040816 207.125L 270.017893877551 207.125Q 275.017893877551 207.125 275.017893877551 202.125L 275.017893877551 202.125L 275.017893877551 157Q 275.017893877551 152 270.017893877551 152L 270.2923102040816 152Q 265.2923102040816 152 265.2923102040816 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 265.2923102040816 157L 265.2923102040816 202.125Q 265.2923102040816 207.125 270.2923102040816 207.125L 270.017893877551 207.125Q 275.017893877551 207.125 275.017893877551 202.125L 275.017893877551 202.125L 275.017893877551 157Q 275.017893877551 152 270.017893877551 152L 270.2923102040816 152Q 265.2923102040816 152 265.2923102040816 157z" pathFrom="M 265.2923102040816 157L 265.2923102040816 157L 275.017893877551 157L 275.017893877551 157L 275.017893877551 157L 275.017893877551 157L 275.017893877551 157L 265.2923102040816 157" cy="197.125" cx="275.017893877551" j="5" val="-9" barHeight="-55.12500000000001" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1619" d="M 319.3233306122449 157L 319.3233306122449 177.625Q 319.3233306122449 182.625 324.3233306122449 182.625L 324.04891428571426 182.625Q 329.04891428571426 182.625 329.04891428571426 177.625L 329.04891428571426 177.625L 329.04891428571426 157Q 329.04891428571426 152 324.04891428571426 152L 324.3233306122449 152Q 319.3233306122449 152 319.3233306122449 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 319.3233306122449 157L 319.3233306122449 177.625Q 319.3233306122449 182.625 324.3233306122449 182.625L 324.04891428571426 182.625Q 329.04891428571426 182.625 329.04891428571426 177.625L 329.04891428571426 177.625L 329.04891428571426 157Q 329.04891428571426 152 324.04891428571426 152L 324.3233306122449 152Q 319.3233306122449 152 319.3233306122449 157z" pathFrom="M 319.3233306122449 157L 319.3233306122449 157L 329.04891428571426 157L 329.04891428571426 157L 329.04891428571426 157L 329.04891428571426 157L 329.04891428571426 157L 319.3233306122449 157" cy="172.625" cx="329.04891428571426" j="6" val="-5" barHeight="-30.625000000000004" barWidth="9.725583673469387"></path>
                                                                <path id="SvgjsPath1620" d="M 373.3543510204081 157L 373.3543510204081 196Q 373.3543510204081 201 378.3543510204081 201L 378.0799346938775 201Q 383.0799346938775 201 383.0799346938775 196L 383.0799346938775 196L 383.0799346938775 157Q 383.0799346938775 152 378.0799346938775 152L 378.3543510204081 152Q 373.3543510204081 152 373.3543510204081 157z" fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskra5wdsk9)" pathTo="M 373.3543510204081 157L 373.3543510204081 196Q 373.3543510204081 201 378.3543510204081 201L 378.0799346938775 201Q 383.0799346938775 201 383.0799346938775 196L 383.0799346938775 196L 383.0799346938775 157Q 383.0799346938775 152 378.0799346938775 152L 378.3543510204081 152Q 373.3543510204081 152 373.3543510204081 157z" pathFrom="M 373.3543510204081 157L 373.3543510204081 157L 383.0799346938775 157L 383.0799346938775 157L 383.0799346938775 157L 383.0799346938775 157L 383.0799346938775 157L 373.3543510204081 157" cy="191" cx="383.0799346938775" j="7" val="-8" barHeight="-49.00000000000001" barWidth="9.725583673469387"></path>
                                                            </g>
                                                            <g id="SvgjsG1602" class="apexcharts-datalabels" data:realIndex="0"></g>
                                                            <g id="SvgjsG1612" class="apexcharts-datalabels" data:realIndex="1"></g>
                                                        </g>
                                                        <line id="SvgjsLine1642" x1="-11.89142857142857" y1="0" x2="390.1085714285714" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line>
                                                        <line id="SvgjsLine1643" x1="-11.89142857142857" y1="0" x2="390.1085714285714" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line>
                                                        <g id="SvgjsG1644" class="apexcharts-yaxis-annotations"></g>
                                                        <g id="SvgjsG1645" class="apexcharts-xaxis-annotations"></g>
                                                        <g id="SvgjsG1646" class="apexcharts-point-annotations"></g>
                                                        <rect id="SvgjsRect1647" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect>
                                                        <rect id="SvgjsRect1648" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect>
                                                    </g>
                                                    <g id="SvgjsG1629" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)">
                                                        <g id="SvgjsG1630" class="apexcharts-yaxis-texts-g"></g>
                                                    </g>
                                                    <g id="SvgjsG1591" class="apexcharts-annotations"></g>
                                                </svg>
                                                <div class="apexcharts-legend" style="max-height: 115px;"></div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start my-4">
                                            <div class="badge rounded bg-label-primary p-2 me-3 rounded"><i class="ti ti-currency-dollar ti-sm"></i></div>
                                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                                <div class="me-2">
                                                    <h6 class="mb-0">Total Sales</h6>
                                                    <small class="text-muted">Refund</small>
                                                </div>
                                                <p class="mb-0 text-success">+$98</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start">
                                            <div class="badge rounded bg-label-secondary p-2 me-3 rounded"><i class="ti ti-brand-paypal ti-sm"></i></div>
                                            <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                                                <div class="me-2">
                                                    <h6 class="mb-0">Total Revenue</h6>
                                                    <small class="text-muted">Client Payment</small>
                                                </div>
                                                <p class="mb-0 text-success">+$126</p>
                                            </div>
                                        </div>
                                        <div class="resize-triggers">
                                            <div class="expand-trigger">
                                                <div style="width: 449px; height: 440px;"></div>
                                            </div>
                                            <div class="contract-trigger"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Renewal Expiry -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title m-0 me-2">Renewal History</h5>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="teamMemberList" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical ti-sm ventext-muted"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="teamMemberList">
                                                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-borderless border-top">
                                            <thead class="border-bottom">
                                                <tr>
                                                    <th>DRIVER</th>
                                                    <th>EXPIRED ON</th>
                                                    <th>RENEWED ON</th>
                                                    <th>STATUS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $select_driver_details_query = sqlQUERY_LABEL("SELECT  dvi_driver_details.driver_name,dvi_driver_details.driver_license_number,  dvi_driver_license_renewal_log_details.end_date AS expiration_date, dvi_driver_license_renewal_log_details.start_date FROM   dvi_driver_details JOIN dvi_driver_license_renewal_log_details ON dvi_driver_details.driver_id = dvi_driver_license_renewal_log_details.driver_id WHERE MONTH(dvi_driver_license_renewal_log_details.start_date) = MONTH(CURRENT_DATE()) AND YEAR(dvi_driver_license_renewal_log_details.start_date) = YEAR(CURRENT_DATE());") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                $total_organization_count = sqlNUMOFROW_LABEL($select_driver_details_query);
                                                if ($total_organization_count > 0) :
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_driver_details_query)) :
                                                        $counter++;
                                                        $driver_id = $fetch_data['driver_id'];
                                                        $driver_name = $fetch_data['driver_name'];
                                                        $driver_license_number = $fetch_data['driver_license_number'];
                                                        $expiration_date = $fetch_data['expiration_date'];
                                                        $date = new DateTime($expiration_date);
                                                        $formattedLINCENSEEXPIREDDATE = $date->format('d-m-Y');
                                                        $start_date = $fetch_data['start_date'];
                                                        $date = new DateTime($start_date);
                                                        $formattedLINCENSERENEWALDDATE = $date->format('d-m-Y');

                                                        // Get License Expiry Status
                                                        $currentDate = date('Y-m-d');
                                                        $date = new DateTime($currentDate);
                                                        $formattedLCURRENTDATE = $date->format('d-m-Y');

                                                        if ($start_date == $currentDate) :

                                                            $driver_licence_status = "<span class='badge bg-label-danger me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Renewal date: $formattedLINCENSERENEWALDDATE'>Renewal Today</span>";

                                                        elseif ($start_date < $currentDate) :

                                                            $driver_licence_status = "<span class='badge bg-label-dark me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Renewal date: $formattedLINCENSERENEWALDDATE'>In-Active</span>";

                                                        else :

                                                            $driver_licence_status = "<span class='badge bg-label-success me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Renewal date: $formattedLINCENSERENEWALDDATE'>Active</span>";

                                                        endif;
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex flex-column">
                                                                    <p class="mb-0 fw-medium"><?= $driver_name; ?></p>
                                                                    <small class="text-muted text-nowrap"><?= $driver_license_number; ?></small>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 fw-medium"><?= $formattedLINCENSEEXPIREDDATE; ?></p>
                                                            </td>
                                                            <td>
                                                                <p class="mb-0 fw-medium"><?= $formattedLINCENSERENEWALDDATE; ?></p>
                                                            </td>
                                                            <td><span class=""><?= $driver_licence_status; ?></span></td>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No records found !!!</td>
                                                    </tr>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Renewal Expiry -->

                            <!-- Upcoming Trip -->
                            <div class="col-lg-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between">
                                        <h5 class="card-title m-0 me-2">Upcoming Trip</h5>
                                        <div class="dropdown">
                                            <button class="btn p-0" type="button" id="teamMemberList" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="teamMemberList">
                                                <a class="dropdown-item" href="javascript:void(0);">Download</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-borderless border-top">
                                            <thead class="border-bottom">
                                                <tr>
                                                    <th>DRIVER</th>
                                                    <th>Source</th>
                                                    <th>Destination</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <p class="mb-0 fw-medium">Driver</p>
                                                            <small class="text-muted text-nowrap">DFAF2342342324</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 fw-medium">Pondicherry</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 fw-medium">Chennai</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <p class="mb-0 fw-medium">Driver</p>
                                                            <small class="text-muted text-nowrap">DFAF2342342324</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 fw-medium">Pondicherry</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 fw-medium">Chennai</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <p class="mb-0 fw-medium">Driver</p>
                                                            <small class="text-muted text-nowrap">DFAF2342342324</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 fw-medium">Pondicherry</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 fw-medium">Chennai</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--/ Upcoming Trip -->

                            <div class="col-md-7">
                                <div class="card h-100">
                                    <div class="card-body row widget-separator g-0">
                                        <div class="col-sm-5 border-shift border-end">
                                            <h2 class="text-primary d-flex align-items-center gap-1 mb-2">4.89<i class="ti ti-star-filled"></i></h2>
                                            <p class="h6 mb-1">Total 187 reviews</p>
                                            <p class="pe-2 mb-2">All reviews are from genuine customers</p>
                                            <span class="badge bg-label-primary p-2 mb-sm-0">+5 This week</span>
                                            <hr class="d-sm-none">
                                        </div>

                                        <div class="col-sm-7 gap-2 text-nowrap d-flex flex-column justify-content-between ps-sm-4 pt-2 py-sm-2">
                                            <div class="d-flex align-items-center gap-3">
                                                <small>5 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 61.50%" aria-valuenow="61.50" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">124</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>4 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 24%" aria-valuenow="24" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">40</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>3 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">12</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>2 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 7%" aria-valuenow="7" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">7</small>
                                            </div>
                                            <div class="d-flex align-items-center gap-3">
                                                <small>1 Star</small>
                                                <div class="progress w-100" style="height:10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 2%" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="w-px-20 text-end">2</small>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- State-wise Itinerary -->
                            <!-- <div class="col-5">
                <div class="card h-100">
                  <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">State-wise Itinerary</h5>
                  </div>
                  <div class="card-body">
                    <ul class="p-0 m-0">
                      <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                          <h6 class="mb-0">Chennai</h6>
                          <div class="d-flex">
                            <div class="badge bg-label-primary">100 Hotspots</div>
                          </div>
                        </div>
                      </li>
                      <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                          <h6 class="mb-0">Bangalore</h6>
                          <div class="d-flex">
                            <div class="badge bg-label-primary">100 Hotspots</div>
                          </div>
                        </div>
                      </li>
                      <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                          <h6 class="mb-0">Kanyakumari</h6>
                          <div class="d-flex">
                            <div class="badge bg-label-primary">100 Hotspots</div>
                          </div>
                        </div>
                      </li>
                      <li class="pb-1 d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                          <h6 class="mb-0">Puducherry</h6>
                          <div class="d-flex">
                            <div class="badge bg-label-primary">100 Hotspots</div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div> -->
                            <!--/ State-wise Itinerary -->

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

        // // Select all menu links
        // var menuLinks = document.querySelectorAll('.menu-link');

        // // Loop through the menu links and check if their href matches the current URL
        // menuLinks.forEach(function(link) {
        //     var href = link.getAttribute('href');
        //     if (currentUrl.indexOf(href) !== -1) {
        //         // Add the "active" class to the matching menu item
        //         link.classList.add('active');
        //     }
        // });

        // JavaScript code to toggle the sub-menu visibility
        const hotelsMenuItem = document.getElementById('hotels-menu-item');
        const subMenu = hotelsMenuItem.querySelector('.menu-sub');

        hotelsMenuItem.addEventListener('click', () => {
            // Toggle the visibility of the sub-menu
            subMenu.classList.toggle('show');
        });
    </script>
</body>


<!-- Mirrored from demos.pixinvent.com/vuexy-html-admin-template/html/vertical-menu-template/app-logistics-dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 01 Sep 2023 07:44:44 GMT -->

</html>

<!-- beautify ignore:end -->