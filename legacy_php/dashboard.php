<?php
include_once("jackus.php");
// ini_set('display_errors', 1);
// ini_set('log_errors', 1);
admin_reguser_protect();
require_once('check_restriction.php');

// Last and current month dates
$current_month_start = date('Y-m-01');
$current_month_end = date('Y-m-t');
$last_month_start = date('Y-m-01', strtotime('first day of last month'));
$last_month_end = date('Y-m-t', strtotime('last day of last month'));

// Profit calculation functions (re-use your originals)
function calculate_total_profit($from_date, $to_date)
{
  $coupon_discount = round(getACCOUNTSMANAGER_COUPENDISCOUNT_AMOUNT($from_date, $to_date, 'itinerary_total_coupon_discount_amount'));
  $profit_components = ['PROFIT_GUIDE', 'PROFIT_HOTSPOT', 'PROFIT_ACTIVITY', 'PROFIT_HOTEL', 'PROFIT_VEHICLE'];
  $total_profit = 0;
  foreach ($profit_components as $component) {
    $total_profit += round(getACCOUNTSfilter_MANAGER_PROFITAMOUNT('', '', $from_date, $to_date, '', '', $component));
  }
  return $total_profit - $coupon_discount;
}

$current_month_profit = calculate_total_profit($current_month_start, $current_month_end);
$last_month_profit = calculate_total_profit($last_month_start, $last_month_end);

$growth_percentage = ($last_month_profit != 0)
  ? round((($current_month_profit - $last_month_profit) / $last_month_profit) * 100, 2)
  : 0;

// For color indication (green for up, red for down)
$badge_class = ($growth_percentage >= 0) ? 'bg-label-success' : 'bg-label-danger';

if ($growth_percentage >= 0) {
  $arrow = '▲'; // or '<i class="bi bi-arrow-up-short"></i>'
} elseif ($growth_percentage < 0) {
  $arrow = '▼'; // or '<i class="bi bi-arrow-down-short"></i>'
} else {
  $arrow = '';
}

if ($logged_agent_id):
  $agent_profit_amount = get_AGENTMARGIN_AMOUNT($current_month_start, $current_month_end, $logged_agent_id, 'itinerary_agent_margin_amount');

  $agent_profit_amount_previous = get_AGENTMARGIN_AMOUNT($last_month_start, $last_month_end, $logged_agent_id, 'itinerary_agent_margin_amount');

  // Growth/decline percentage (dashboard style)
  $agent_growth_percentage = ($agent_profit_amount_previous != 0)
    ? round((($agent_profit_amount - $agent_profit_amount_previous) / $agent_profit_amount_previous) * 100, 2)
    : 0;

  $agent_badge_class = ($agent_growth_percentage >= 0) ? 'bg-label-success' : 'bg-label-danger';

  // Arrow logic
  if ($agent_growth_percentage >= 0) {
    $agent_arrow = '▲';
  } elseif ($agent_growth_percentage < 0) {
    $agent_arrow = '▼';
  } else {
    $agent_arrow = '';
  }
endif;

// Stat counts (replace with your existing PHP functions)
$total_agents = getAGENT_details('', '', 'get_total_agent_count');
$total_drivers = getDASHBOARD_COUNT_DETAILS('total_driver_count');
$total_guides = getGUIDEDETAILS('', 'total_guide_count');

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
  <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
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
                <div class="justify-content-center">
                  <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-4 my-4">
                      <div class="card-body p-4">
                        <div class="row align-items-center">
                          <!-- LEFT: Welcome and Stats -->
                          <div class="col-12 col-lg-8">
                            <h3 class="fw-bold mb-2">Welcome back, Admin <span style="font-size:1.8rem;">👋🏻</span></h3>
                            <p class="text-secondary mb-4" style="max-width:420px">Your progress this week is Awesome. Let's keep it up and get a lot of points reward!</p>
                            <div class="d-flex flex-wrap gap-3 mb-2">
                              <!-- Agents Stat -->
                              <a href="agent.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#f7e8fd; text-decoration:none; min-width:170px;">
                                <span class="d-flex align-items-center justify-content-center" style="background:#fff; padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-user ti-xl' style="color:#c241e4; font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Agents</div>
                                  <div class="fw-bold mb-0" style="color:#c241e4; font-size:1.3rem;"><?= $total_agents ?></div>
                                </div>
                              </a>
                              <!-- Drivers Stat -->
                              <a href="driver.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#e7f7fd; text-decoration:none; min-width:170px;">
                                <span class="d-flex align-items-center justify-content-center" style="background:#fff; padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-car ti-xl' style="color:#30aadd; font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Driver</div>
                                  <div class="fw-bold mb-0" style="color:#30aadd; font-size:1.3rem;"><?= $total_drivers ?></div>
                                </div>
                              </a>
                              <!-- Guides Stat -->
                              <a href="guide.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#fff6ec; text-decoration:none; min-width:170px;">
                                <span class="d-flex align-items-center justify-content-center" style="background:#fff; padding:10px 12px; border-radius:8px;">
                                  <img src="assets/img/svg/tour-guide.svg" width="35px" />
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Guide</div>
                                  <div class="fw-bold mb-0" style="color:#fbb15c; font-size:1.3rem;"><?= $total_guides ?></div>
                                </div>
                              </a>
                            </div>
                          </div>
                          <!-- RIGHT: Profits -->
                          <div class="col-12 col-lg-4">
                            <div class="d-flex flex-row flex-lg-column h-100 align-items-stretch justify-content-between gap-0 gap-lg-3">
                              <!-- Last Month Profit -->
                              <div class="flex-fill px-3 py-2">
                                <div class="pb-0">
                                  <div class="text-secondary mb-1" style="font-size:1rem; font-weight:600;">Last Month Profit</div>
                                  <div class="text-secondary mb-1 small"><?= date('F Y', strtotime('first day of -1 month')); ?></div>
                                  <a href="accountsmanager.php?dashboard_from=<?= $last_month_start ?>&dashboard_to=<?= $last_month_end ?>" class="fs-4 text-dark fw-bold" style="text-decoration:none;">
                                    <?= general_currency_symbol ?> <?= number_format($last_month_profit, 2); ?>
                                  </a>
                                </div>
                              </div>
                              <!-- Current Month Profit -->
                              <div class="flex-fill px-3 py-2">
                                <div class="pb-0">
                                  <div class="text-secondary mb-1" style="font-size:1rem; font-weight:600;">Current Month Profit</div>
                                  <div class="text-secondary mb-1 small"><?= date('F Y'); ?></div>
                                  <div class="d-flex align-items-center gap-2">
                                    <a href="accountsmanager.php?dashboard_from=<?= $current_month_start ?>&dashboard_to=<?= $current_month_end ?>" class="fs-4 text-dark fw-bold" style="text-decoration:none;">
                                      <?= general_currency_symbol ?> <?= number_format($current_month_profit, 2); ?>
                                    </a>
                                    <div class="badge <?= $badge_class; ?> p-1 px-2" style="font-size:0.80rem; background:#fbeaec; color:#e24d4c; font-weight:500;">
                                      <?= $arrow ?> <?= abs($growth_percentage) ?>%
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> <!-- row -->
                      </div> <!-- card-body -->
                    </div> <!-- card -->
                  </div>
                </div>

                <!-- Itinerary List -->
                <div class="col-lg-6 mb-4 col-sm-12 px-1">
                  <div class="row">
                    <div class="col-lg-12 d-flex">
                      <div class="col-lg-6 px-2">
                        <a href="latestitinerary.php" class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_itinerary_count'); ?></h5>
                              <small class="text-dark">Total Itineraries</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-lg-6 px-2">
                        <a target="_blank" href="latestconfirmeditinerary.php" class="card card-border-shadow-info h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(getDASHBOARD_COUNT_DETAILS('total_revenue'), 2); ?></h5>
                              <small class="text-dark">Total Revenue</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/profit.png" alt="Profit" width="100">
                            </div>
                          </div>
                        </a>
                      </div>
                    </div>
                    <div class="col-lg-12 d-flex mt-2">
                      <div class="col-lg-6 px-2">
                        <a target="_blank" href="latestconfirmeditinerary.php" class="card card-border-shadow-primary h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_confirm_itinerary_count'); ?></h5>
                              <small class="text-dark">Total Confirm Bookings</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/booking.png" alt="booking" width="75">
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-lg-6 px-2">
                        <a target="_blank" href="latestconfirmeditinerary.php?type=cancelled_bookings" class="card card-border-shadow-danger h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_cancelled_itinerary_count'); ?></h5>
                              <small class="text-dark">Cancelled Booking</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/Calendar.png" alt="visitors" width="75">
                            </div>
                          </div>
                        </a>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vehicle_upcoming'); ?></b></p>
                                    <p class="mb-0">Upcoming Vehicles</p>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_inactive_vendor_count'); ?></b></p>
                                    <p class="mb-0">In Active Vendors</p>
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
                <div class="col-md-7 mb-4 vehicle-live-data-section d-flex ">
                  <div class="card w-100 overflow-hidden">
                    <div class="app-logistics-fleet-sidebar col h-100" id="app-logistics-fleet-sidebar">
                      <div class="card-header border-0 pt-4 pb-2 d-flex justify-content-between">
                        <h5 class="mb-0 card-title">Daily Moment</h5>
                        <div>
                          <input type="text" id="dailymoment-picker" placeholder="MM/DD/YYYY" class="form-control" />
                        </div>
                        <!-- Sidebar close button -->
                        <i class="ti ti-x ti-xs cursor-pointer close-sidebar d-md-none btn btn-label-secondary p-0" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar"></i>
                      </div>
                      <span id="dailymoment_container"></span>

                    </div>
                  </div>
                </div>
                <!-- Daily Moment Status -->

                <!-- Top-Rated Guides, Vendors, and Drivers Approval -->
                <div class="col-md-5 col-xl-5 mb-4 top-rated-performance-section d-flex">
                  <div class="card w-100">
                    <div class="card-header d-flex justify-content-between pb-2 mb-1">
                      <div class="card-title mb-1">
                        <h5 class="m-0 me-2">Star Performers</h5>
                        <small class="text-muted">Top-Rated Agents, Travel Expert, Guides and Vendors</small>
                      </div>
                    </div>

                    <div class="card-body pb-0">
                      <div class="nav-align-top">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                          <li class="nav-item">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-agent" aria-controls="top-rated-agent" aria-selected="false">Agents</button>
                          </li>
                          <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-travelexpert" aria-controls="top-rated-travelexpert" aria-selected="true">Travel Expert</button>
                          </li>
                          <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-guides" aria-controls="top-rated-guides" aria-selected="true">Guides</button>
                          </li>
                          <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-vendors" aria-controls="top-rated-vendors" aria-selected="false">Vendors</button>
                          </li>

                        </ul>

                        <div class="tab-content p-0">
                          <!-- Agent Section -->
                          <div class="tab-pane fade show active" id="top-rated-agent" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <span id="star-agent-details"></span>
                            </ul>
                          </div>

                          <!-- Travel Expert Section -->
                          <div class="tab-pane fade" id="top-rated-travelexpert" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <span id="star-travelexpert-details"></span>
                            </ul>
                          </div>


                          <!-- Guides Section -->
                          <div class="tab-pane fade" id="top-rated-guides" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <span id="star-guide-details"></span>
                            </ul>
                          </div>

                          <!-- Vendors Section -->
                          <div class="tab-pane fade" id="top-rated-vendors" role="tabpanel">
                            <ul class="list-group list-group-flush">
                              <span id="star-vehicle-details"></span>
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
                  <h4 class="mt-3">Confirmed Itinerary List</h4>
                  <span id="itinerary_details"></span>
                </div>
                <!-- Itinerary List -->

                <!-- Agents List -->
                <div class="col-md-12 mb-4">
                  <h4>Agents wise Confirmed Itinerary</h4>
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
              </div>
            <?php elseif ($logged_agent_id != '' &&  $logged_agent_id != '0') : ?>

              <div class="row">
                <div class="justify-content-center">
                  <div class="col-12">
                    <div class="card shadow-none border-0 rounded-4 my-4">
                      <div class="card-body p-4">
                        <div class="row align-items-center">
                          <!-- LEFT: Welcome and Stats -->
                          <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                            <h3 class="fw-bold mb-2">Welcome back, Agent <span style="font-size:1.8rem;">👋🏻</span></h3>
                            <p class="text-secondary mb-4" style="max-width:420px">Your progress this week is Awesome. Let's keep it up and get a lot of points reward!</p>
                            <div class="d-flex flex-wrap gap-3">
                              <!-- Validity Ends Stat -->
                              <a href="agent_subscription_history.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#e7f7fd; text-decoration:none; min-width:170px;">
                                <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-calendar ti-xl text-info' style="font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Validity Ends</div>
                                  <div class="fw-bold mb-0 text-blue-color" style="font-size:1.15rem;">
                                    <?= date('d M Y', strtotime(getDASHBOARD_COUNT_DETAILS('validity_end_date', $logged_agent_id))); ?>
                                  </div>
                                </div>
                              </a>
                              <!-- Total Customers Stat -->
                              <div class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#f7e8fd; min-width:170px;">
                                <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-user ti-xl text-primary' style="font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Customers</div>
                                  <div class="fw-bold mb-0 text-primary" style="font-size:1.15rem;">
                                    <?= getAGENTDASHBOARD_COUNT_DETAILS($logged_agent_id, 'total_CUSTOMER_count'); ?>
                                  </div>
                                </div>
                              </div>
                              <!-- Total Staff Stat -->
                              <a href="newstaff.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#fff6ec; text-decoration:none; min-width:170px;">
                                <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-users ti-xl text-warning' style="font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Staff</div>
                                  <div class="fw-bold mb-0 text-warning" style="font-size:1.15rem;">
                                    <?= getAGENTDASHBOARD_COUNT_DETAILS($logged_agent_id, 'total_staff_count'); ?>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                          <!-- RIGHT: Profit card -->
                          <div class="col-12 col-lg-4">
                            <div class="d-flex flex-column gap-2">
                              <!-- Last Month Profit -->
                              <div class="px-3 py-2">
                                <div class="text-secondary mb-1" style="font-size:1rem; font-weight:600;">Last Month Profit</div>
                                <div class="text-secondary mb-1 small"><?= date('F Y', strtotime('first day of last month')); ?></div>
                                <div class="fw-bold text-dark mb-1" style="font-size:1.5rem;">
                                  <?= general_currency_symbol ?> <?= number_format($agent_profit_amount_previous, 2); ?>
                                </div>
                              </div>
                              <!-- Current Month Profit -->
                              <div class="px-3 py-2">
                                <div class="text-secondary mb-1" style="font-size:1rem; font-weight:600;">Current Month Profit</div>
                                <div class="text-secondary mb-1 small"><?= date('F Y'); ?></div>
                                <div class="d-flex align-items-center gap-2">
                                  <a href="#" class="fs-4 text-dark fw-bold" style="text-decoration:none;">
                                    <?= general_currency_symbol ?> <?= number_format($agent_profit_amount, 2); ?>
                                  </a>
                                  <div class="badge <?= $agent_badge_class; ?> p-1 px-2" style="font-size:0.80rem; background:#fbeaec; color:#e24d4c; font-weight:500;">
                                    <?= $agent_arrow ?> <?= abs($agent_growth_percentage) ?>%
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div> <!-- row -->
                      </div> <!-- card-body -->
                    </div> <!-- card -->
                  </div>
                </div>

                <!-- Itinerary List -->
                <div class="col-lg-12 mb-4 col-sm-12 px-1">
                  <div class="row">
                    <div class="col-md-12 d-flex">
                      <div class="col-lg-3 px-2">
                        <a href="latestitinerary.php" class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_itinerary_count', $logged_agent_id); ?></h5>
                              <small class="text-dark">Total Itineraries</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-lg-3 px-2">
                        <a href="latestconfirmeditinerary.php" class="card card-border-shadow-info h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(getDASHBOARD_COUNT_DETAILS('total_revenue', $logged_agent_id), 2); ?></h5>
                              <small class="text-dark">Total Revenue</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/profit.png" alt="Profit" width="100">
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-lg-3 px-2">
                        <a href="latestconfirmeditinerary.php" class="card card-border-shadow-primary h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_confirm_itinerary_count', $logged_agent_id); ?></h5>
                              <small class="text-dark">Total Bookings</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/booking.png" alt="booking" width="75">
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-lg-3 px-2">
                        <div class="card card-border-shadow-danger h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_agent_cancelled_itinerary_count', $logged_agent_id); ?></h5>
                              <small class="text-dark">Total Cancelled Booking</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/Calendar.png" alt="visitors" width="75">
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
                  <h4 class="mt-3">Confirmed Itinerary List</h4>
                  <span id="itinerary_details"></span>
                </div>
                <!-- Itinerary List -->

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
                <div class="col-md-5 mb-4 vehicle-live-data-section d-flex ">
                  <div class="card w-100 overflow-hidden">
                    <div class="app-logistics-fleet-sidebar col h-100" id="app-logistics-fleet-sidebar">
                      <div class="card-header border-0 pt-4 pb-2 d-flex justify-content-between">
                        <h5 class="mb-0 card-title">Top 5 Current Itinerary</h5>
                        <div>
                          <input type="text" id="dailymoment-picker" placeholder="MM/DD/YYYY" class="form-control" />
                        </div>
                        <!-- Sidebar close button -->
                        <i class="ti ti-x ti-xs cursor-pointer close-sidebar d-md-none btn btn-label-secondary p-0" data-bs-toggle="sidebar" data-overlay data-target="#app-logistics-fleet-sidebar"></i>
                      </div>
                      <span id="dailymoment_container"></span>

                    </div>
                  </div>
                </div>
                <!-- Daily Moment Status -->

                <!-- Invoice Details -->
                <div class="col-md-7 d-flex mb-4">
                  <div class="card w-100 p-4">
                    <h4>Booking Details</h4>
                    <div id="chart"></div>
                  </div>
                </div>
                <!--/ Invoice Details -->

              </div>

            <?php elseif ($logged_vendor_id != '' &&  $logged_vendor_id != '0') : ?>
              <div class="row">

                <!-- Hour chart  -->
                <div class="col-12 mb-4">
                  <div class="card shadow-none my-4 border-0 rounded-4">
                    <div class="card-body p-4">
                      <h3 class="fw-bold mb-2">Welcome back, Vendor <span style="font-size:1.8rem;">👋🏻</span></h3>
                      <p class="text-secondary mb-4" style="max-width:420px">
                        Your progress this week is Awesome. Let's keep it up and get a lot of points reward!
                      </p>
                      <div class="d-flex flex-wrap gap-3">
                        <!-- Total Itinerary -->
                        <a href="latestconfirmeditinerary.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#fff6ec; min-width:170px; text-decoration:none;">
                          <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                            <i class='ti ti-cash ti-xl text-warning' style="font-size:2rem;"></i>
                          </span>
                          <div>
                            <div class="mb-0 text-dark" style="font-size:1rem;">Total Itinerary</div>
                            <div class="fw-bold mb-0 text-warning" style="font-size:1.2rem;">
                              <?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_itinerary_count'); ?>
                            </div>
                          </div>
                        </a>
                        <!-- Total Branch -->
                        <a href="newvendor.php?route=edit&formtype=vehicle_info&id=<?= $logged_vendor_id ?>" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#e7f7fd; min-width:170px; text-decoration:none;">
                          <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                            <i class='ti ti-car ti-xl text-info' style="font-size:2rem;"></i>
                          </span>
                          <div>
                            <div class="mb-0 text-dark" style="font-size:1rem;">Total Branch</div>
                            <div class="fw-bold mb-0 text-info" style="font-size:1.2rem;">
                              <?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_branch_count'); ?>
                            </div>
                          </div>
                        </a>
                        <!-- Total Driver -->
                        <a href="driver.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#e9fbe8; min-width:170px; text-decoration:none;">
                          <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                            <img src="assets/img/driver.svg" width="35px" />
                          </span>
                          <div>
                            <div class="mb-0 text-dark" style="font-size:1rem;">Total Driver</div>
                            <div class="fw-bold mb-0 text-success" style="font-size:1.2rem;">
                              <?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_drivers_count'); ?>
                            </div>
                          </div>
                        </a>
                        <!-- Total Vehicle -->
                        <a href="newvendor.php?route=edit&formtype=vehicle_info&id=<?= $logged_vendor_id ?>" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#f7e8fd; min-width:170px; text-decoration:none;">
                          <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                            <i class='ti ti-user ti-xl text-primary' style="font-size:2rem;"></i>
                          </span>
                          <div>
                            <div class="mb-0 text-dark" style="font-size:1rem;">Total Vehicle</div>
                            <div class="fw-bold mb-0 text-primary" style="font-size:1.2rem;">
                              <?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_vehicle_count'); ?>
                            </div>
                          </div>
                        </a>
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
                        <a href="latestconfirmeditinerary.php" class="card card-border-shadow-warning h-100">
                          <div class="card-body d-flex justify-content-between align-items-center p-3">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_trip_count'); ?></h5>
                              <small class="text-dark">Total Trips</small>
                            </div>
                            <div class="card-icon">
                              <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                            </div>
                          </div>
                        </a>
                      </div>
                      <div class="col-lg-6 px-2">
                        <div class="card card-border-shadow-info h-100">
                          <div class="card-body d-flex justify-content-between align-items-center">
                            <div class="card-title mb-0">
                              <h5 class="mb-0 me-2"><?= general_currency_symbol . ' ' . number_format((getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'current_year_profit')), 2); ?></h5>
                              <small class="text-dark">Total Revenue</small>
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
                              <h5 class="mb-0 me-2"><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_vehicle_upcoming'); ?></h5>
                              <small class="text-dark">Scheduled Trips</small>
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
                              <h5 class="mb-0 me-2"><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_trip_complete'); ?></h5>
                              <small class="text-dark">Completed Trips</small>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_vehicle_count'); ?></b></p>
                                    <p class="mb-0">Total Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_vehicle_available'); ?></b></p>
                                    <p class="mb-0">Available Vehicles</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_vehicle_ongoing'); ?></b></p>
                                    <p class="mb-0">On Route Vehicles</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_vehicle_upcoming'); ?></b></p>
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
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'active_drivers'); ?></b></p>
                                    <p class="mb-0">Active Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'inactive_drivers'); ?></b></p>
                                    <p class="mb-0">In-active Drivers</p>
                                  </li>
                                </ul>
                              </div>
                              <div class="col-6">
                                <ul class="list-unstyled mb-0">
                                  <li class="d-flex mb-4 align-items-center">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_driver_ongoing'); ?></b></p>
                                    <p class="mb-0">On Route Drivers</p>
                                  </li>
                                  <li class="d-flex align-items-center mb-2">
                                    <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getVENDOR_DASHBOARD_DETAILS($logged_vendor_id, '', '', 'total_driver_available'); ?></b></p>
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

                <!-- Daily Moment Status -->
                <div class="col-md-12 mb-4 vehicle-live-data-section">
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
                      <!-- Sidebar when screen < md -->
                      <span id="dailymoment_container"></span>
                    </div>
                  </div>
                </div>
                <!-- Daily Moment Status -->

                <!-- Invoice Details -->
                <!-- <div class="col-md-7 d-flex mb-4">
                  <div class="card w-100 p-4">
                    <h4>Invoice Details</h4>
                    <div id="chart"></div>
                  </div>
                </div> -->
                <!--/ Invoice Details -->

                <!-- Branch List -->
                <div class="col-md-12 d-flex mb-4 vehicle-branch-section">
                  <div class="card card-action mb-4 w-100">
                    <div class="card-header align-items-center py-4">
                      <h5 class="card-action-title mb-0">Branch Details</h5>
                      <div class="card-action-element">
                        <a href="newvendor.php?route=edit&formtype=vehicle_info&id=<?= $logged_vendor_id ?>" class="btn btn-label-primary" target="_blank">View All</a>
                      </div>
                    </div>
                    <span id="vendorbranch_container"></span>
                  </div>
                </div>
                <!--/ Branch List -->

                <!-- Bar Charts -->
                <!-- <div class="col-xl-6 col-12 mb-4">
                  <div class="card">
                    <div class="card-header header-elements">
                      <div class="card-title mb-0">
                        <h5 class="mb-0">Earning Reports</h5>
                        <small class="card-subtitle">Monthly Earnings Overview</small>
                      </div>
                      <div class="card-action-element ms-auto py-0">
                        <div class="dropdown">
                          <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false"><i class="ti ti-calendar"></i><span class="ms-1 mt-1">2024</span></button>
                          <ul class="dropdown-menu dropdown-menu-end">
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center active">2024</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">2023</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">2022</a></li>
                            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">2021</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <canvas id="barChart" class="chartjs" data-height="400"></canvas>
                    </div>
                  </div>
                </div> -->
                <!-- /Bar Charts -->

                <!-- FC Overview Section -->
                <div class="col-xl-12 mb-4 fc-overview-section">


                  <h5 class="m-0 me-2">FC Overview</h5>
                  <small class="text-muted">Fitness Certificate Overview</small>



                  <!--/ FC Overview Section -->
                  <span id="vendor_fcoverview_details"></span>
                </div>
              <?php elseif ($logged_user_level == 6) : ?>
                <div class="row">

                  <!-- Hour chart  -->
                  <div class="col-12 mb-4">
                    <div class="card shadow-none border-0 my-4 rounded-4">
                      <div class="card-body p-4">
                        <div class="row align-items-center">
                          <!-- LEFT: Welcome and Stats -->
                          <div class="col-12 col-lg-8 mb-4 mb-lg-0">
                            <?= $logged_staff_ids ?>
                            <h3 class="fw-bold mb-2">
                              Welcome back, <?= ucwords(getAGENT_STAFF_DETAILS($logged_accounts_id, '', 'label')); ?> <span style="font-size:1.8rem;">👋🏻</span>
                            </h3>
                            <p class="text-secondary mb-4" style="max-width:420px">
                              Your progress this week is Awesome. Let's keep it up and get a lot of points reward!
                            </p>
                            <div class="d-flex flex-wrap gap-3">
                              <!-- Total Agents -->
                              <a href="agent.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#f7e8fd; min-width:170px; text-decoration:none;">
                                <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-road ti-xl text-primary' style="font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Agents</div>
                                  <div class="fw-bold mb-0 text-primary" style="font-size:1.15rem;">
                                    <?= getAGENT_details('', '', 'get_total_agent_count'); ?>
                                  </div>
                                </div>
                              </a>
                              <!-- Total Driver -->
                              <a href="driver.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#e7f7fd; min-width:170px; text-decoration:none;">
                                <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-user ti-xl text-info' style="font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Driver</div>
                                  <div class="fw-bold mb-0 text-info" style="font-size:1.15rem;">
                                    <?= getDASHBOARD_COUNT_DETAILS('total_driver_count'); ?>
                                  </div>
                                </div>
                              </a>
                              <!-- Total Guide -->
                              <a href="guide.php" class="d-flex align-items-center gap-3 px-3 py-2 rounded-3" style="background:#fff6ec; min-width:170px; text-decoration:none;">
                                <span class="d-flex align-items-center justify-content-center bg-white" style="padding:10px 12px; border-radius:8px;">
                                  <i class='ti ti-car ti-xl text-warning' style="font-size:2rem;"></i>
                                </span>
                                <div>
                                  <div class="mb-0 text-dark" style="font-size:1rem;">Total Guide</div>
                                  <div class="fw-bold mb-0 text-warning" style="font-size:1.15rem;">
                                    <?= getGUIDEDETAILS('', 'total_guide_count'); ?>
                                  </div>
                                </div>
                              </a>
                            </div>
                          </div>
                          <!-- RIGHT: Active Bookings (Weekly report) -->
                          <div class="col-12 col-lg-4">
                            <div class="px-3 py-4" style="background:#f4f8ff; border-radius:14px; min-height:170px;">
                              <div class="text-secondary mb-1" style="font-size:1rem; font-weight:600;">Active Bookings</div>
                              <div class="text-secondary mb-1 small">Weekly report</div>
                              <div class="fw-bold mb-2" style="font-size:2rem;">
                                <?= getDASHBOARD_COUNT_DETAILS('weekly_itinerary_count'); ?>
                                <span class="text-muted ms-1" style="font-size:1rem;">Total</span>
                              </div>
                              <div>
                                <?= getAC_MNRG_DASHBOARD_DETAILS('', '', 'weekly_diff_percentage') ?>
                              </div>
                            </div>
                          </div>
                        </div> <!-- row -->
                      </div> <!-- card-body -->
                    </div> <!-- card -->
                  </div>

                  <!-- Hour chart End  -->

                  <!-- Itinerary List -->
                  <div class="col-lg-6 mb-4 col-sm-12 px-1">
                    <div class="row">
                      <div class="col-lg-12 d-flex">
                        <div class="col-lg-6 px-2">
                          <a href="latestitinerary.php" class="card card-border-shadow-warning h-100">
                            <div class="card-body d-flex justify-content-between align-items-center p-3">
                              <div class="card-title mb-0">
                                <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_itinerary_count'); ?></h5>
                                <small class="text-dark">Total Itineraries</small>
                              </div>
                              <div class="card-icon">
                                <img src="assets/img/dashboard/visitor.png" alt="visitors" width="130">
                              </div>
                            </div>
                          </a>
                        </div>
                        <div class="col-lg-6 px-2">
                          <a target="_blank" href="latestconfirmeditinerary.php" class="card card-border-shadow-info h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                              <div class="card-title mb-0">
                                <h5 class="mb-0 me-2"><?= general_currency_symbol ?> <?= number_format(getDASHBOARD_COUNT_DETAILS('total_revenue'), 2); ?></h5>
                                <small class="text-dark">Total Revenue</small>
                              </div>
                              <div class="card-icon">
                                <img src="assets/img/dashboard/profit.png" alt="Profit" width="100">
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                      <div class="col-lg-12 d-flex mt-2">
                        <div class="col-lg-6 px-2">
                          <a target="_blank" href="latestconfirmeditinerary.php" class="card card-border-shadow-primary h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                              <div class="card-title mb-0">
                                <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_confirm_itinerary_count'); ?></h5>
                                <small class="text-dark">Total Confirm Bookings</small>
                              </div>
                              <div class="card-icon">
                                <img src="assets/img/dashboard/booking.png" alt="booking" width="75">
                              </div>
                            </div>
                          </a>
                        </div>
                        <div class="col-lg-6 px-2">
                          <a target="_blank" href="latestconfirmeditinerary.php?type=cancelled_bookings" class="card card-border-shadow-danger h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                              <div class="card-title mb-0">
                                <h5 class="mb-0 me-2"><?= getDASHBOARD_COUNT_DETAILS('total_cancelled_itinerary_count'); ?></h5>
                                <small class="text-dark">Cancelled Booking</small>
                              </div>
                              <div class="card-icon">
                                <img src="assets/img/dashboard/Calendar.png" alt="visitors" width="75">
                              </div>
                            </div>
                          </a>
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
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_vehicle_upcoming'); ?></b></p>
                                      <p class="mb-0">Upcoming Vehicles</p>
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
                                      <p class="mb-0 fw-medium me-2 website-analytics-text-bg overview-section-status-report-section"><b><?= getDASHBOARD_COUNT_DETAILS('total_inactive_vendor_count'); ?></b></p>
                                      <p class="mb-0">In Active Vendors</p>
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

                  <!-- Earning Reports -->
                  <!-- <div class="col-lg-6 mb-4">
                  <div class="card h-100">
                    <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                      <div class="card-title mb-0">
                        <h5 class="mb-0">Earning Reports</h5>
                        <small class="text-muted">Weekly Earnings Overview</small>
                      </div>
                      <div class="dropdown">
                        <button class="btn p-0" type="button" id="earningReportsId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId">
                          <a class="dropdown-item" href="javascript:void(0);">View More</a>
                          <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-12 col-md-4 d-flex flex-column align-self-end">
                          <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
                            <h1 class="mb-0">$468</h1>
                            <div class="badge rounded bg-label-success">+4.2%</div>
                          </div>
                          <small>You informed of this week compared to last week</small>
                        </div>
                        <div class="col-12 col-md-8">
                          <div id="weeklyEarningReports"></div>
                        </div>
                      </div>
                      <div class="border rounded p-3 mt-4">
                        <div class="row gap-4 gap-sm-0">
                          <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                              <div class="badge rounded bg-label-primary p-1"><i class="ti ti-currency-dollar ti-sm"></i></div>
                              <h6 class="mb-0">Earnings</h6>
                            </div>
                            <h4 class="my-2 pt-1">$545.69</h4>
                            <div class="progress w-75" style="height:4px">
                              <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                              <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                              <h6 class="mb-0">Profit</h6>
                            </div>
                            <h4 class="my-2 pt-1">$256.34</h4>

                            <div class="progress w-75" style="height:4px">
                              <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </div>
                          <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                              <div class="badge rounded bg-label-danger p-1"><i class="ti ti-brand-paypal ti-sm"></i></div>
                              <h6 class="mb-0">Expense</h6>
                            </div>
                            <h4 class="my-2 pt-1">$74.19</h4>
                            <div class="progress w-75" style="height:4px">
                              <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> -->
                  <!--/ Earning Reports -->

                  <!-- Support Tracker -->
                  <!-- <div class="col-md-6 mb-4">
                    <div class="card h-100">
                      <div class="card-header d-flex justify-content-between pb-0">
                        <div class="card-title mb-0">
                          <h5 class="mb-0">Payout List</h5>
                          <small class="text-muted">Payment Overview </small>
                        </div>
                        <div>
                          <a href="accountsmanager.php" class="btn btn-label-primary" target="_blank">View All</a>
                        </div>
                      </div>

                      <div class="card-body">
                        <div class="row">
                          <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                            <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                              <h3 class="mb-0"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS('', '', 'amt_payout_of_agent')), 2); ?></h3>
                              <p class="mb-0">Total Payout</p>
                            </div>
                            <ul class="p-0 m-0">
                              <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-receipt  ti-sm"></i></div>
                                <div>
                                  <h6 class="mb-0 text-nowrap">Total Billed</h6>
                                  <small class="text-muted"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS('', '', 'amt_billed_of_agent')), 2); ?></small>
                                </div>
                              </li>
                              <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-circle-check ti-sm"></i></div>
                                <div>
                                  <h6 class="mb-0 text-nowrap">Total Received</h6>
                                  <small class="text-muted"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS('', '', 'amt_received_from_agent')), 2); ?></small>
                                </div>
                              </li>
                             <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                <div class="badge rounded bg-label-success p-1"><i class="ti ti-clock ti-sm"></i></div>
                                <div>
                                  <h6 class="mb-0 text-nowrap">Total Receivable</h6>
                                  <small class="text-muted"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS('', '', 'amt_receivable_from_agent')), 2); ?></small>
                                </div>
                              </li>
                               <li class="d-flex gap-3 align-items-center pb-1">
                                <div class="badge rounded bg-label-warning p-1"><i class="ti ti-help-square-rounded ti-sm"></i></div>
                                <div>
                                  <h6 class="mb-0 text-nowrap">Total Payable</h6>
                                  <small class="text-muted"><?= general_currency_symbol ?> <?= number_format(round(getAC_MNRG_DASHBOARD_DETAILS('', '', 'amt_payable_of_agent')), 2); ?></small>
                                </div>
                              </li>
                            </ul>
                          </div>
                          <div class="col-12 col-sm-8 col-md-12 col-lg-8 d-flex align-items-center">
                            <div id="payoutTracker"></div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div> -->

                  <div class="col-xl-6 col-md-6 mb-4 accountmanagerdashboard-guide-section">
                    <div class="card h-100 position-relative second-card">
                      <div class="guide-icon">
                        <!-- <i class="ti ti-compass"></i> -->
                      </div>
                      <div class="card-header d-flex justify-content-between pb-0">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 d-flex align-items-center"><i class="ti ti-coin-rupee me-2"></i>Payout List</h5>
                          <small class="text-muted">Payment Overview</small>
                        </div>

                        <div class="col-6 d-flex justify-content-end align-items-center">
                          <select id="agent_id" class="form-select">
                            <?= getAGENT_details($agent_id, '', 'select') ?>

                            <!-- Add more agents here -->
                          </select>

                        </div>
                        <!-- <a href="accountsmanager.php" class="btn btn-label-primary" target="_blank">View All</a> -->
                      </div>
                      <span id="agent_payment_details"></span>
                    </div>
                  </div>
                  <!--/ Agent to DVI Reports -->
                  <!--/ Support Tracker -->

                  <!-- DVI to Guide -->
                  <div class="col-xl-6 col-md-6 mb-4 accountmanagerdashboard-guide-section">
                    <div class="card h-100 position-relative second-card">
                      <div class="guide-icon">
                        <!-- <i class="ti ti-compass"></i> -->
                      </div>
                      <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 d-flex align-items-center"><i class="ti ti-compass me-2"></i>DVI to Guide</h5>
                          <small class="text-muted">Managing Guides & Tours</small>
                        </div>

                        <div class="col-6 d-flex justify-content-end align-items-center">
                          <select id="guide_id" class="form-select">
                            <?= getGUIDE_DASHBOARD_DETAILS($guide_id, '', '', '', 'guide_select') ?>
                            <!-- Add more agents here -->
                          </select>
                          <!-- <a href="accountsmanager.php" class="btn btn-label-primary" target="_blank">View All</a> -->
                        </div>
                      </div>

                      <span id="guide_container"></span>
                    </div>
                  </div>
                  <!--/ DVI to Guide -->

                  <!-- DVI to Hotel -->
                  <div class="col-xl-6 col-md-6 mb-4 accountmanagerdashboard-guide-section">
                    <div class="card h-100 position-relative second-card">
                      <div class="guide-icon">
                        <!-- <i class="ti ti-compass"></i> -->
                      </div>
                      <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 d-flex align-items-center"><i class="ti ti-building  me-2"></i>DVI to Hotel</h5>
                          <small class="text-muted">Managing Hotels & Bookings</small>
                        </div>
                        <div class="col-6 d-flex justify-content-end align-items-center">
                          <select id="hotel_id" class="form-select">
                            <?= getHOTEL_DETAIL($hotel_id, '', 'select') ?>
                            <!-- Add more agents here -->
                          </select>
                          <!-- <a href="accountsmanager.php" class="btn btn-label-primary" target="_blank">View All</a> -->
                        </div>
                      </div>
                      <span id="hotel_container"></span>
                    </div>
                  </div>
                  <!--/ DVI to Hotel -->

                  <!-- DVI to Transport -->

                  <div class="col-xl-6 col-md-6 mb-4 accountmanagerdashboard-guide-section">
                    <div class="card h-100 position-relative second-card">
                      <div class="guide-icon">
                        <!-- <i class="ti ti-compass"></i> -->
                      </div>
                      <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 d-flex align-items-center"><i class="ti ti-car me-2"></i>DVI to Vendor</h5>
                          <small class="text-muted">Managing Vehicle Bookings</small>
                        </div>
                        <div class="col-6 d-flex justify-content-end align-items-center">
                          <select id="vendor_id" class="form-select">
                            <?= getVEHICLE_DASHBOARD_DETAILS($vendor_id, '', '', '', 'vendor_select') ?>
                            <!-- Add more agents here -->
                          </select>
                        </div>
                      </div>
                      <span id="vendor_container"></span>
                    </div>
                  </div>
                  <!--/ DVI to Transport -->

                  <!-- DVI to Hotspot -->
                  <div class="col-xl-6 col-md-6 mb-4 accountmanagerdashboard-guide-section">
                    <div class="card h-100 position-relative second-card">
                      <div class="guide-icon">
                        <!-- <i class="ti ti-compass"></i> -->
                      </div>
                      <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 d-flex align-items-center"><i class="ti ti-tilt-shift me-2"></i>DVI to Hotspot</h5>
                          <small class="text-muted">Managing Hotspot Bookings</small>
                        </div>
                        <div class="col-6 d-flex justify-content-end align-items-center">
                          <select id="hotspot_id" class="form-select">
                            <?= getHOTSPOT_DASHBOARD_DETAILS($hotspot_id, '', '', '', 'hotspot_select'); ?>
                            <!-- Add more agents here -->
                          </select>
                        </div>
                      </div>
                      <span id="hotspot_container"></span>
                    </div>
                  </div>
                  <!--/ DVI to Hotspot -->

                  <!-- DVI to Activity -->
                  <div class="col-xl-6 col-md-6 mb-4 accountmanagerdashboard-guide-section">
                    <div class="card h-100 position-relative second-card">
                      <div class="guide-icon">
                        <!-- <i class="ti ti-compass"></i> -->
                      </div>
                      <div class="card-header d-flex justify-content-between">
                        <div class="card-title mb-0">
                          <h5 class="m-0 me-2 d-flex align-items-center"><i class="ti ti-photo me-2"></i>DVI to Activity</h5>
                          <small class="text-muted">Managing Activity Bookings</small>
                        </div>
                        <div class="col-6 d-flex justify-content-end align-items-center">
                          <select id="activity_id" class="form-select">
                            <?= getACTIVITY_DASHBOARD_DETAILS($activity_id, '', '', '', 'activity_select') ?>
                            <!-- Add more agents here -->
                          </select>
                        </div>
                      </div>
                      <span id="activity_container"></span>
                    </div>
                  </div>
                  <!--/ DVI to Activity -->

                  <!-- Itinerary Details -->
                  <div class="col-lg-6 mb-4 col-sm-12">
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

                  <!-- Top-Rated Guides, Vendors, and Drivers Approval -->
                  <div class="col-md-6 col-xl-6 mb-4 top-rated-performance-section d-flex">
                    <div class="card w-100">
                      <div class="card-header d-flex justify-content-between pb-2 mb-1">
                        <div class="card-title mb-1">
                          <h5 class="m-0 me-2">Star Performers</h5>
                          <small class="text-muted">Top-Rated Agents, Travel Expert, Guides and Vendors</small>
                        </div>
                      </div>

                      <div class="card-body pb-0">
                        <div class="nav-align-top">
                          <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item">
                              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-agent" aria-controls="top-rated-agent" aria-selected="false">Agents</button>
                            </li>
                            <li class="nav-item">
                              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-travelexpert" aria-controls="top-rated-travelexpert" aria-selected="true">Travel Expert</button>
                            </li>
                            <li class="nav-item">
                              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-guides" aria-controls="top-rated-guides" aria-selected="true">Guides</button>
                            </li>
                            <li class="nav-item">
                              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#top-rated-vendors" aria-controls="top-rated-vendors" aria-selected="false">Vendors</button>
                            </li>
                          </ul>

                          <div class="tab-content p-0">
                            <!-- Agent Section -->
                            <div class="tab-pane fade show active" id="top-rated-agent" role="tabpanel">
                              <ul class="list-group list-group-flush">
                                <span id="star-agent-details"></span>
                              </ul>
                            </div>

                            <!-- Travel Expert Section -->
                            <div class="tab-pane fade" id="top-rated-travelexpert" role="tabpanel">
                              <ul class="list-group list-group-flush">
                                <span id="star-travelexpert-details"></span>
                              </ul>
                            </div>


                            <!-- Guides Section -->
                            <div class="tab-pane fade" id="top-rated-guides" role="tabpanel">
                              <ul class="list-group list-group-flush">
                                <span id="star-guide-details"></span>
                              </ul>
                            </div>

                            <!-- Vendors Section -->
                            <div class="tab-pane fade" id="top-rated-vendors" role="tabpanel">
                              <ul class="list-group list-group-flush">
                                <span id="star-vehicle-details"></span>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--/ Top-Rated Guides, Vendors, and Drivers Approval -->

                  <!-- Daily Moment Status -->
                  <div class="col-md-12 mb-4 vehicle-live-data-section">
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
                        <!-- Sidebar when screen < md -->
                        <span id="dailymoment_container"></span>
                      </div>
                    </div>
                  </div>
                  <!-- Daily Moment Status -->

                  <!-- Itinerary List -->
                  <div class="col-md-12">
                    <h4 class="mt-3">Confirmed Itinerary List</h4>
                    <span id="itinerary_details"></span>
                  </div>
                  <!-- Itinerary List -->

                  <!-- Top-Performing Agents List -->
                  <div class="col-md-12">
                    <!-- Heading -->
                    <h4 class="mt-3 mb-1">Top-Performing Agents with Subscription Details</h4>
                    <p class="text-muted mb-4">Below is a list of agents who have generated significant profit, along with their subscription end dates.</p>

                    <!-- Agents Table -->
                    <div class="nav-align-top mb-4" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                      <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-under-maintenance" role="tabpanel">
                          <div class="card-body dataTable_select text-nowrap">
                            <div class="text-nowrap table-responsive table-bordered">
                              <table class="table table-hover" id="agent_LIST">
                                <thead>
                                  <tr>
                                    <th scope="col">S.No</th>
                                    <th scope="col">Agent Name</th>
                                    <th scope="col">Agent Email</th>
                                    <th scope="col">Mobile Number</th>
                                    <th scope="col">Subscription Title</th>
                                    <th scope="col">Subscription End Date</th>
                                    <th scope="col">Profit Generated</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                  $select_accountsmanagerLIST_query = sqlQUERY_LABEL("SELECT da.agent_ID, da.agent_name, da.agent_primary_mobile_number, da.agent_email_id, da.agent_lastname, SUM(dipd.agent_margin) AS total_margin FROM dvi_agent da JOIN dvi_itinerary_plan_details dipd ON da.agent_ID = dipd.agent_id WHERE da.deleted = '0' AND da.status = '1' AND dipd.deleted = '0' GROUP BY da.agent_ID ORDER BY total_margin DESC LIMIT 3") or die("#1-UNABLE_TO_COLLECT_AGENT_LIST:" . sqlERROR_LABEL());

                                  $count = 0;
                                  while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_accountsmanagerLIST_query)) :
                                    $count++;
                                    $agent_ID = $fetch_list_data['agent_ID'];
                                    $agent_name = $fetch_list_data['agent_name'];
                                    $agent_lastname = $fetch_list_data['agent_lastname'];
                                    $agent_full_name = $agent_name . ' ' . $agent_lastname;
                                    $agent_primary_mobile_number = $fetch_list_data['agent_primary_mobile_number'];
                                    $agent_email_id = $fetch_list_data['agent_email_id'];
                                    $total_margin = $fetch_list_data['total_margin'];
                                    $agent_subscription_id = get_AGENT_SUBSCRIBED_PLAN_DETAILS('', $agent_ID, 'subscription_plan_ID');
                                    $agent_subscription_validity = getSUBSCRIPTION_REGISTRATION($agent_subscription_id, 'validity_days');
                                    $agent_subscription_title = get_AGENT_SUBSCRIBED_PLAN_DETAILS($agent_subscription_id, $agent_ID, 'subscription_plan_title');
                                    $agent_subscription_validity_end = dateformat_datepicker(get_AGENT_SUBSCRIBED_PLAN_DETAILS($agent_subscription_id, $agent_ID, 'validity_end'));
                                  ?>
                                    <tr>
                                      <td><?= $count ?></td>
                                      <td>
                                        <?= $agent_full_name; ?>
                                        <?php if ($count == 1) : ?>
                                          <span class="badge bg-success ms-2">Top Performer</span>
                                        <?php endif; ?>
                                      </td>
                                      <td><?= $agent_email_id ?></td>
                                      <td><?= $agent_primary_mobile_number ?></td>
                                      <td><?= $agent_subscription_title ?> / <?= $agent_subscription_validity ?> Days</td>
                                      <td><?= $agent_subscription_validity_end ?></td>
                                      <td> <?= general_currency_symbol ?> <?= number_format(round($total_margin), 2); ?></td>
                                    </tr>
                                  <?php endwhile; ?>
                                </tbody>

                              </table>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- End of Agents List -->

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
  <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
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
  <script src="assets/js/selectize/selectize.min.js"></script>
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

  <script>
    $(document).ready(function() {
      $('#guide_id').selectize();
      $('#hotel_id').selectize();
      $('#vendor_id').selectize();
      $('#agent_id').selectize();
      $('#hotspot_id').selectize();
      $('#activity_id').selectize();

      $('#guide_id').on('change', function() {

        var selectedGuide = $(this).val();
        showGUIDE_DETAILS(selectedGuide);
      });

      $('#hotel_id').on('change', function() {

        var selectedHotel = $(this).val();
        showHOTEL_DETAILS(selectedHotel);
      });

      $('#vendor_id').on('change', function() {

        var selectedVendor = $(this).val();
        showVENDOR_DETAILS(selectedVendor);
      });

      $('#hotspot_id').on('change', function() {

        var selectedHotspot = $(this).val();
        showHOTSPOT_DETAILS(selectedHotspot);
      });

      $('#agent_id').on('change', function() {

        var selectedAgent = $(this).val();
        showAGENT_PAYMENT_DETAILS(selectedAgent);
      });

      $('#activity_id').on('change', function() {

        var selectedActivity = $(this).val();
        showACTIVITY_DETAILS(selectedActivity);
      });

    });

    function showGUIDE_DETAILS(selectedGuide) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/ajax_latest_dashboard_guide_details.php?type=show_form',
        data: {
          id: selectedGuide
        },
        success: function(response) {
          $('#guide_container').html(response);
        }
      });
    }

    function showHOTEL_DETAILS(selectedHotel) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/ajax_latest_dashboard_hotel_details.php?type=show_form',
        data: {
          id: selectedHotel
        },
        success: function(response) {
          $('#hotel_container').html(response);
        }
      });
    }

    function showVENDOR_DETAILS(selectedVendor) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/ajax_latest_dashboard_vehicle_details.php?type=show_form',
        data: {
          id: selectedVendor
        },
        success: function(response) {
          $('#vendor_container').html(response);
        }
      });
    }

    function showHOTSPOT_DETAILS(selectedHotspot) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/ajax_latest_dashboard_hotspot_details.php?type=show_form',
        data: {
          id: selectedHotspot
        },
        success: function(response) {
          $('#hotspot_container').html(response);
        }
      });

    }

    function showACTIVITY_DETAILS(selectedActivity) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/ajax_latest_dashboard_activity_details.php?type=show_form',
        data: {
          id: selectedActivity
        },
        success: function(response) {
          $('#activity_container').html(response);
        }
      });

    }


    function showAGENT_PAYMENT_DETAILS(selectedAgent) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/ajax_latest_dashboard_agent_payment_details.php?type=show_form',
        data: {
          id: selectedAgent
        },
        success: function(response) {
          $('#agent_payment_details').html(response);
        }
      });


    }
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

        var selectedGuide = $('#guide_id').val();
        showGUIDE_DETAILS(selectedGuide);

        var selectedHotel = $('#hotel_id').val();
        showHOTEL_DETAILS(selectedHotel);

        var selectedVendor = $('#vendor_id').val();
        showVENDOR_DETAILS(selectedVendor);

        var selectedHotspot = $('#hotspot_id').val();
        showHOTSPOT_DETAILS(selectedHotspot);

        var selectedActivity = $('#activity_id').val();
        showACTIVITY_DETAILS(selectedActivity);

      });

      <?php if ($_GET['route'] == '') : ?>
        showITINEARYLIST();
        showVEHICLELIST();
        showTOPHOTEL();
        showTOGUIDE();
        showTOVEHICLE();
        showTOTRAVELEXPERT();
        showTOAGENT();
        showDAILYMOMENT();
        showVENDORBRANCH();
        showAGENTLIST();
        // showGUIDE_DETAILS();
        // showHOTEL_DETAILS();
        // showVENDOR_DETAILS();
        // showHOTSPOT_DETAILS();
        showVENDOR_VEHICLE_FCOVERVIEW();
        showAGENT_PAYMENT_DETAILS();
        // showACTIVITY_DETAILS();
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

      function showVENDOR_VEHICLE_FCOVERVIEW() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboardvendor_fcoverview_list.php?type=show_form',
          success: function(response) {
            $('#vendor_fcoverview_details').html(response);
          }
        });
      }

      function showTOGUIDE() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_topguide.php?type=show_form',
          success: function(response) {
            $('#star-guide-details').html(response);
          }
        });
      }

      function showTOVEHICLE() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_topvendor.php?type=show_form',
          success: function(response) {
            $('#star-vehicle-details').html(response);
          }
        });
      }

      function showTOAGENT() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_topagent.php?type=show_form',
          success: function(response) {
            $('#star-agent-details').html(response);
          }
        });
      }

      function showTOTRAVELEXPERT() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_toptravelexpert.php?type=show_form',
          success: function(response) {
            $('#star-travelexpert-details').html(response);
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
            $('#dailymoment_container').html(response);
          }
        });
      }

      function showVENDORBRANCH() {
        $.ajax({
          type: 'post',
          url: 'engine/ajax/ajax_latest_dashboard_vendor_branch.php?type=show_form',
          success: function(response) {
            $('#vendorbranch_container').html(response);
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
                    return "<?= getDASHBOARD_COUNT_DETAILS('total_preference_count', $logged_agent_id); ?>";
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
    "use strict";

    (function() {
      // Theme-based configurations
      let e, t, a, r, o;
      if (isDarkStyle) {
        e = config.colors_dark.cardColor;
        a = config.colors_dark.textMuted;
        t = config.colors_dark.headingColor;
        r = "dark";
        o = "#5E6692";
      } else {
        e = config.colors.cardColor;
        a = config.colors.textMuted;
        t = config.colors.headingColor;
        r = "";
        o = "#817D8D";
      }

      // Support Tracker Chart
      var payoutTrackerElement = document.querySelector("#payoutTracker");
      if (payoutTrackerElement) {
        var payoutTrackerOptions = {

          series: [<?= getAC_MNRG_DASHBOARD_DETAILS('', '', 'get_completed_task_percentage') ?>],
          labels: ["Completed Payouts"],
          chart: {
            height: 360,
            type: "radialBar"
          },
          plotOptions: {
            radialBar: {
              offsetY: 10,
              startAngle: -140,
              endAngle: 130,
              hollow: {
                size: "65%"
              },
              track: {
                background: e,
                strokeWidth: "100%"
              },
              dataLabels: {
                name: {
                  offsetY: -20,
                  color: a,
                  fontSize: "13px",
                  fontWeight: "400"
                },
                value: {
                  offsetY: 10,
                  color: t,
                  fontSize: "38px",
                  fontWeight: "500"
                }
              }
            }
          },
          colors: [config.colors.primary],
          fill: {
            type: "gradient",
            gradient: {
              shade: "dark",
              shadeIntensity: 0.5,
              gradientToColors: [config.colors.primary],
              opacityFrom: 1,
              opacityTo: 0.6
            }
          },
          stroke: {
            dashArray: 10
          },
          grid: {
            padding: {
              top: -20,
              bottom: 5
            }
          },
          states: {
            hover: {
              filter: {
                type: "none"
              }
            },
            active: {
              filter: {
                type: "none"
              }
            }
          },
          responsive: [{
              breakpoint: 1025,
              options: {
                chart: {
                  height: 330
                }
              }
            },
            {
              breakpoint: 769,
              options: {
                chart: {
                  height: 280
                }
              }
            }
          ]
        };
        new ApexCharts(payoutTrackerElement, payoutTrackerOptions).render();
      }

    })();
  </script>


  <script>
    var options = {
      series: [{
        name: 'Agent Profit',
        color: '#6779d6',
        data: [<?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_jan_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_feb_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_mar_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_apr_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_may_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_jun_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_july_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_aug_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_sep_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_oct_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_nov_count'); ?>, <?= getAGENTAMOUNTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_dec_count'); ?>]
      }, {
        name: 'Booking Count',
        color: '#d7232391',
        data: [<?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_jan_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_feb_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_mar_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_apr_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_may_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_jun_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_july_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_aug_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_sep_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_oct_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_nov_count'); ?>, <?= getAGENTDASHBOARD_MONTH_DETAILS($logged_agent_id, 'total_dec_count'); ?>]
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
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
            return val
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