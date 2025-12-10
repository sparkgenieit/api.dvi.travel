<?php
include_once("jackus.php");
admin_reguser_protect();
// require_once('check_restriction.php');
// $current_page = 'dashboard.php'; // Set the current page variable
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


            <div class="row">
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card card-border-shadow-primary h-100">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                      <h5 class="mb-0 me-2"><?=getDASHBOARD_COUNT_DETAILS('total_hotel_count'); ?></h5>
                      <small>Hotel</small>
                    </div>
                    <div class="card-icon">
                      <span class="badge bg-label-primary rounded-pill p-2">
                        <i class='ti ti-building-skyscraper ti-sm'></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card card-border-shadow-info h-100">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                      <h5 class="mb-0 me-2"><?=getDASHBOARD_COUNT_DETAILS('total_vendor_count'); ?></h5>
                      <small>Vendor</small>
                    </div>
                    <div class="card-icon">
                      <span class="badge bg-label-info rounded-pill p-2">
                        <i class='ti ti-building-store ti-sm'></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card card-border-shadow-primary h-100">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                      <h5 class="mb-0 me-2"><?=getDASHBOARD_COUNT_DETAILS('total_vehicle_count'); ?></h5>
                      <small>Vehicle</small>
                    </div>
                    <div class="card-icon">
                      <span class="badge bg-label-primary rounded-pill p-2">
                        <i class='ti ti-car ti-sm'></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card card-border-shadow-info h-100">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                      <h5 class="mb-0 me-2"><?=getDASHBOARD_COUNT_DETAILS('total_driver_count'); ?></h5>
                      <small>Driver</small>
                    </div>
                    <div class="card-icon">
                      <span class="badge bg-label-info rounded-pill p-2">
                        <i class='ti ti-steering-wheel ti-sm'></i>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 mb-4 col-md-12">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title mb-0">Vehicle Overview</h5>
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
                          <h4 class="ms-1 mb-0"><?=getDASHBOARD_COUNT_DETAILS('active_drivers'); ?></h4>
                        </div>
                        <p class="mb-1">Active Drivers</p>
                      </div>
                      <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center mb-2 pb-1">
                          <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-users ti-md"></i></span>
                          </div>
                          <h4 class="ms-1 mb-0"><?=getDASHBOARD_COUNT_DETAILS('inactive_drivers'); ?></h4>
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
              <!-- Map Navigation -->
              <div class="col-8 mb-4">
                <div class="card h-100 mb-4">
                  <h5 class="card-header">Hotspots</h5>
                  <div class="card-body">
                    <div class="leaflet-map" id="userLocation"></div>
                  </div>
                </div>
              </div>
              <!-- /Map Navigation -->


              <!-- Vendor Ranking -->
              <div class="col-md-6 col-xl-4 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex align-items-center">
                    <h5 class="card-title m-0 me-0">List of Vendors</h5>
                  </div>
                  <div class="card-body">
                 
                  <?php $select_vendor_details_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `status` FROM `dvi_vendor_details` WHERE  `status` = '1' AND `deleted` = '0' ORDER BY `vendor_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        $total_vendor = sqlNUMOFROW_LABEL($select_vendor_details_query);
                        if ($total_vendor > 0) :
                          while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_details_query)) :
                            $counter++;
                            $vendor_id = $fetch_data['vendor_id'];                
                            $vendor_name = $fetch_data['vendor_name'];
                            $vendor_branches_count = getDASHBOARD_VENDOR_LIST_DETAILS($vendor_id,'vendor_branch_count');
                            $vehicles_count = getDASHBOARD_VENDOR_LIST_DETAILS($vendor_id,'vendor_vehicles_count');
                            $drivers_count = getDASHBOARD_VENDOR_LIST_DETAILS($vendor_id,'vendor_drivers_count');
                            ?>
                    <ul class="p-0 m-0">
                      <li class="d-flex mb-3 pb-1">
                        <div class="chart-progress me-3" data-color="primary" data-series="72" data-progress_variant="true"></div>
                        <div class="row w-100 align-items-center">
                          <div class="col-12">
                            <div class="me-2">
                              <div class="d-flex">
                                <h6 class="mb-2"><?= $vendor_name; ?> -  </h6>
                                <h6 class="mb-2 ms-1 text-primary"> <?= $vendor_branches_count; ?> Branches</h6>
                              </div>
                              <small><i class="menu-icon tf-icons ti ti-user mb-1"></i>
                                <?= $drivers_count; ?> Drivers</small>
                              <small><i class="menu-icon tf-icons ti ti-steering-wheel mb-1 ms-1"></i>
                                <?= $vehicles_count; ?> Vehicles</small>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                    <?php endwhile;
                    else:
                    echo "No vendors found !!!";
                    endif;?>

                  </div>
                </div>
              </div>
              <!--/ Vendor Ranking -->

              <!-- Vehicles overview -->
              <div class="col-6 mb-4">
                <div class="card h-100">
                  <div class="card-header">
                    <div class="card-title mb-0">
                      <h5 class="m-0">Vehicles overview</h5>
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
              <div class="col-lg-6 col-12 mb-4">
                <div class="card">
                  <div class="card-header header-elements">
                    <h5 class="card-title mb-0">Most Visited Hotels</h5>
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
              </div>
              <!-- /Polar Area Chart -->

              <!-- Renewal Expiry -->
              <div class="col-lg-6 mb-4">
                <div class="card h-100">
                  <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title m-0 me-2">Renewal Expiry</h5>
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
                          <th>EXPIRED ON</th>
                          <th>STATUS</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $select_driver_details_query = sqlQUERY_LABEL("SELECT `driver_id`,`driver_name`,`driver_license_number`,  `driver_license_expiry_date` , `status` FROM `dvi_driver_details` WHERE  `deleted` = '0' ORDER BY `driver_id` DESC") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                        $total_organization_count = sqlNUMOFROW_LABEL($select_driver_details_query);
                        if ($total_organization_count > 0) :
                          while ($fetch_data = sqlFETCHARRAY_LABEL($select_driver_details_query)) :
                            $counter++;
                            $driver_id = $fetch_data['driver_id'];
                            $driver_name = $fetch_data['driver_name'];
                            $driver_license_number = $fetch_data['driver_license_number'];
                            $driver_license_expiry_date = $fetch_data['driver_license_expiry_date'];
                            // Get License Expiry Status
                            $currentDate = date('Y-m-d');

                            if ($driver_license_expiry_date == $currentDate) :

                              $driver_licence_status = "<span class='badge bg-label-danger me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Expires Today</span>";

                            elseif ($driver_license_expiry_date < $currentDate) :

                              $driver_licence_status = "<span class='badge bg-label-dark me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>In-Active</span>";

                            else :

                              $driver_licence_status = "<span class='badge bg-label-success me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Active</span>";

                            endif;


                        ?>

                      <tbody>
                        <tr>
                          <td>
                            <div class="d-flex flex-column">
                              <p class="mb-0 fw-medium"><?= $driver_name; ?></p>
                              <small class="text-muted text-nowrap"><?= $driver_license_number; ?></small>
                            </div>
                          </td>
                          <td>
                            <p class="mb-0 fw-medium"><?= $driver_license_expiry_date; ?></p>
                          </td>
                          <td><span><?= $driver_licence_status; ?></span></td>
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
              <div class="col-5">
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
              </div>
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
</body>
</html>

