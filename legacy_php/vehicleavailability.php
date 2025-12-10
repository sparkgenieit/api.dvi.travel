<?php
include_once("jackus.php");
$current_page = 'hotel_category.php'; // Set the current page variable
admin_reguser_protect();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Canonical SEO -->
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
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/flatpickr/flatpickr.css" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="./assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/parsley_validation.css">
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/js/config.js"></script>
    <style>
        .table-responsive {
            overflow-x: auto;
            /* Enables horizontal scroll when content overflows */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on touch devices */
        }

        table {
            width: 100%;
            /* Ensure table takes full width inside the responsive div */
            border-collapse: collapse;
            margin-top: 1rem;
            border: 1px solid #ddd;
        }

        thead th {
            background-color: #f4f4f4;
        }

        thead th,
        tbody td {
            padding: 0.75rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        .vehicle-avail-dropdown {
            background: #fff;
            border: 1px solid #b3b2b2;
            padding: 5px 5px;
            border-radius: 5px;
        }

        .arrival-deparure-vehicle {
            background-color: #e0d7fa96;
        }

        .inbetween-vehicle {
            background-color: #faebd794;
        }

        .completed-vehicle {
            background-color: #dffbdf;
        }

        .not-assign-vehicle {
            background-color: #fff;
        }

        /* Sticky Columns */
        .sticky-col {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            background-color: #fff;
            border-right: 1px solid #ddd;
            z-index: 10;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sticky-col+.sticky-col {
            left: 119px;
            z-index: 9;
            background-color: #fff;
            /* Ensure background color for consistency */
            border-left: 1px solid #ddd;
            /* Border between sticky columns */
            border-right: 1px solid #ddd;
            /* Ensure both sides have a border */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }


        thead th.sticky-col+.sticky-col {
            position: sticky;
            top: 0;
            background-color: #e8e8e8;
            /* Background color for sticky header */
            z-index: 11;
            /* Ensure sticky header is above the sticky columns */
            border-right: 1px solid #ddd;
            /* Optional: border for header separation */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        thead th.sticky-col {
            position: sticky;
            top: 0;
            background-color: #e8e8e8;
            /* Background color for sticky header */
            z-index: 11;
            /* Ensure sticky header is above the sticky columns */
            border-right: 1px solid #ddd;
            /* Optional: border for header separation */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }



        /* Set borders for each cell inside the sticky column */
        .sticky-col td {
            border: 1px solid #ddd;
            /* Ensure cells in sticky column have borders */
        }

        @media (max-width: 768px) {
            .table-responsive {
                /* Ensure responsiveness on smaller screens */
                width: 100%;
                overflow-x: auto;
            }
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
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <div class="d-flex justify-content-end p-1">
                            <span id="response_alert"></span>
                        </div>
                        <!-- Users List Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card p-0">
                                    <div class="card-header pb-3 d-flex justify-content-between">
                                        <h5 class="card-title mb-3">List of Vehicle Availability</h5>
                                        <div>
                                            <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#adddriver">+ Add Driver</a>
                                            <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" data-bs-toggle="modal" data-bs-target="#addvehicle">+ Add Vehicle</a>
                                        </div>
                                    </div>
                                    <div class="card shadow-none bg-transparent border border-primary mb-3 mx-4">
                                        <div class="card-body p-3">
                                            <h5 class="card-title text-uppercase">Filter</h5>
                                            <div class="row align-items-end">
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label" for="filter_from">Date from</label>
                                                    <input type="text" name="filter_from" id="filter_from"
                                                        class="form-control" placeholder="DD/MM/YYYY" />
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label" for="filter_to">Date to</label>
                                                    <input type="text" name="filter_to" id="filter_to"
                                                        class="form-control" placeholder="DD/MM/YYYY" />
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label" for="vendor_name">Vendor</label>
                                                    <select id="vendor_name" name="vendor_name" required class="form-control form-select">
                                                        <?= getVENDOR_DETAILS('', 'select'); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <label class="form-label" for="vehicle_type">Vehicle Type</label>
                                                    <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                                                        <?= getVEHICLETYPE('', 'select'); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="agent_type">Agent</label>
                                                    <select id="agent_type" name="agent_type" required class="form-control form-select">
                                                        <?= getAGENT_details('', '', 'select'); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="location_type">Location</label>
                                                    <select id="location_type" name="location_type" required class="form-control form-select">
                                                        <?= getGOOGLE_LOCATION_DETAILS('', 'select'); ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <a href="javascript:void(0)" id="filter_clear" class="btn btn-secondary">Clear </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-nowrap table-responsive table-bordered">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="sticky-col" scope="col">Vendor Name</th>
                                                        <th class="sticky-col" scope="col">Vehicle Type</th>
                                                        <th scope="col">Sep 01,2024</th>
                                                        <th scope="col">Sep 02,2024</th>
                                                        <th scope="col">Sep 03,2024</th>
                                                        <th scope="col">Sep 04,2024</th>
                                                        <th scope="col">Sep 05,2024</th>
                                                        <th scope="col">Sep 06,2024</th>
                                                        <th scope="col">Sep 07,2024</th>
                                                        <th scope="col">Sep 08,2024</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="sticky-col">Saran - 97858558588 Travels</td>
                                                        <td class="sticky-col">
                                                            Sedan</br><span class="text-blue-color">TN 22 SD 0202</span>
                                                        </td>
                                                        <td></td>
                                                        <td class="not-assign-vehicle">
                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2 mb-1" data-bs-toggle="modal" data-bs-target="#assignvehicle"><i class="ti ti-plus fw-bold fs-6 me-1"></i>Assign Vehicle</button>
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-002 </h6>
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 </h6>
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-011 </h6>
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-023 </h6>
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-007 </h6>
                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td>

                                                        </td>
                                                        <td class="not-assign-vehicle">
                                                            <button type="button" class="btn btn-sm btn-success waves-effect waves-light ps-2" data-bs-toggle="modal" data-bs-target="#assignvehicleNOTMULTIPLE"><i class="ti ti-plus fw-bold fs-6 me-1"></i>Assign Vehicle</button>
                                                            <h6 class="text-blue-color mb-1">CQ-DVI202409-004</h6>
                                                        </td>
                                                        <td></td>
                                                        <td></td>

                                                    </tr>
                                                    <tr>
                                                        <td class="sticky-col">Saran - 97858558588 Travels</td>
                                                        <td class="sticky-col">
                                                            Sedan</br><span class="text-blue-color">TN 52 ET 1562</span>
                                                        </td>
                                                        <td class="arrival-deparure-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 06.00 AM</span></h6>

                                                            <h6 class="text-dark mb-2">Chennai => Mahabalipuram</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Mahabalipuram => Pondicherry</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>

                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Pondicherry => Thiruvarur</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Thiruvarur => Kumbakonam</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Kumbakonam => Kumbakonam</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Kumbakonam => tanjore</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">tanjore => trichy</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="arrival-deparure-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 09.00 PM</span></h6>

                                                            <h6 class="text-dark mb-2">trichy => trichy</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="sticky-col">Uma Travels</td>
                                                        <td class="sticky-col">
                                                            Innova</br><span class="text-blue-color">TN 52 ET 1562</span>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="arrival-deparure-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 06.00 AM</span></h6>

                                                            <h6 class="text-dark mb-2">Chennai => Mahabalipuram</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Mahabalipuram => Pondicherry</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>

                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Pondicherry => Thiruvarur</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Kumbakonam => tanjore</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="inbetween-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">tanjore => trichy</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="arrival-deparure-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 09.00 PM</span></h6>

                                                            <h6 class="text-dark mb-2">trichy => trichy</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="sticky-col">Saran - 97858558588 Travels</td>
                                                        <td class="sticky-col">
                                                            Sedan</br><span class="text-blue-color">TN 52 ET 1562</span>
                                                        </td>
                                                        <td class="arrival-deparure-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 06.00 AM</span></h6>

                                                            <h6 class="text-dark mb-2">Chennai => Mahabalipuram</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="completed-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Mahabalipuram => Pondicherry</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>

                                                        </td>
                                                        <td class="completed-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Pondicherry => Thiruvarur</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="completed-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Thiruvarur => Kumbakonam</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="completed-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Kumbakonam => Kumbakonam</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="completed-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">Kumbakonam => tanjore</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="completed-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003</h6>

                                                            <h6 class="text-dark mb-2">tanjore => trichy</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td class="arrival-deparure-vehicle">
                                                            <h6 class="text-blue-color mb-1 d-flex gap-2">CQ-DVI202409-003 <span class="d-flex align-items-center gap-1 mb-1" style="color: #6f6b7d;"><i class="ti ti-clock text-body fs-5 fw-semibold ti-sm"></i> 09.00 PM</span></h6>

                                                            <h6 class="text-dark mb-2">trichy => trichy</h6>
                                                            <div class="d-flex">
                                                                <div>
                                                                    <h6 class="mb-2"><img src="assets/img/svg/profile.svg" width="26px" height="26px" /> - Saran - 97858558588 </h6>
                                                                    <span class="badge badge-dailymoment-visited mb-2"><i class="ti ti-check fs-6 me-1"></i>Assigned</span>
                                                                </div>
                                                                <span class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#editassign">
                                                                    <i class="ti-sm ti ti-edit mb-1 ms-2"></i>
                                                                </span>
                                                            </div>
                                                        </td>
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
    <div class="modal fade" id="adddriver" tabindex="-1" aria-labelledby="adddriverLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Add Driver</h4>
                    <form id="adddriver_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vendor_name">Vendor<span class=" text-danger">
                                    *</span></label>
                            <select id="vendor_name" name="vendor_name" required class="form-control form-select">
                                <?= getVENDOR_DETAILS('', 'select'); ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Vehicle Type<span class=" text-danger">
                                    *</span></label>
                            <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                                <?= getVEHICLETYPE('', 'select'); ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="driver-text-label w-100" for="driver_name">Driver Name<span class=" text-danger">
                                    *</span></label>
                            <div class="form-group">
                                <input type="text" name="driver_name" id="driver_name" placeholder="Driver Name" value="Kumar" required="" autocomplete="off" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="driver-text-label w-100" for="driver_primary_mobile_number">Primary Mobile
                                Number<span class=" text-danger">*</span></label>
                            <div class="form-group">
                                <input type="tel" id="driver_primary_mobile_number" name="driver_primary_mobile_number" class="form-control parsley-success" placeholder="Primary Mobile Number" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_driver_primary_number="" data-parsley-check_driver_primary_number-message="Entered Mobile Number Already Exists" autocomplete="off" required="" maxlength="10" data-parsley-id="17">
                                <input type="hidden" name="old_driver_primary_mobile_number" id="old_driver_primary_mobile_number" data-parsley-type="number">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addvehicle" tabindex="-1" aria-labelledby="addvehicleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Add Vehicle</h4>
                    <form id="addvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="vehicle_type">Vehicle Type<span class=" text-danger">
                                    *</span></label>
                            <select id="vehicle_type" name="vehicle_type" required class="form-control form-select">
                                <?= getVEHICLETYPE('', 'select'); ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="registration_number">Registration Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="Registration Number" value="" required="" data-parsley-check_registration_number="" data-parsley-check_registration_number-message="Entered Registration Number Already Exists" data-parsley-pattern="^[A-Z]{2}\s?[0-9]{1,2}\s?[A-Z]{1,2}\s?[0-9]{1,4}\s?[A-Z]{0,1}[0-9]{0,4}$">

                                <input type="hidden" name="old_registration_number" id="old_registration_number" value="">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="chassis_number">Vehicle Origin <span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="vehicle_orign" id="vehicle_orign" class="form-control" placeholder="Choose Vehicle Origin" value="" required="" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="vehicle_fc_expiry_date">Vehicle Expiry Date <span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="vehicle_fc_expiry_date" id="vehicle_fc_expiry_date" class="form-control flatpickr-input" placeholder="Vehicle Expiry Date" value="" required="" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="insurance_start_date">Insurance Start Date<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_start_date" id="insurance_start_date" class="form-control flatpickr-input" placeholder="Insurance Start Date" value="" required="" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label" for="insurance_end_date">Insurance End Date<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_end_date" id="insurance_end_date" class="form-control flatpickr-input" placeholder="Insurance End Date" value="" required="" readonly="readonly">
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignvehicle" tabindex="-1" aria-labelledby="assignvehicleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Assign Vehicle</h4>
                    <form id="assignvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Code ID<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Code ID</option>
                                <option value="1">CQ-DVI202409-002</option>
                                <option value="2">CQ-DVI202409-003</option>
                                <option value="3">CQ-DVI202409-011</option>
                                <option value="4">CQ-DVI202409-023</option>
                                <option value="5">CQ-DVI202409-007</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Driver<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Driver</option>
                                <option value="1">P. Bharathiraja thangapalam</option>
                                <option value="2">G. Pavithren</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignvehicleNOTMULTIPLE" tabindex="-1" aria-labelledby="assignvehicleNOTMULTIPLELabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Assign Vehicle</h4>
                    <form id="assignvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Driver<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Driver</option>
                                <option value="1">P. Bharathiraja thangapalam</option>
                                <option value="2">G. Pavithren</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-success">Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editassign" tabindex="-1" aria-labelledby="editassignLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-5">
                    <h4 class="text-center" id="exampleModalLabel">Assign Vehicle</h4>
                    <form id="assignvehicle_details_form" class="row g-3" action="" method="post" data-parsley-validate>
                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="vehicle_type">Driver<span class=" text-danger">
                                    *</span></label>
                            <select class="form-control form-select" name="status" id="status" data-parsley-trigger="keyup">
                                <option value="">Choose the Driver</option>
                                <option value="1">P. Bharathiraja thangapalam - 9656565666</option>
                                <option value="2">G. Pavithren - 98989565665</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-between text-center pt-4">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>

                            <button type="submit" class="btn btn-success">Re-Assign</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
    <script src="./assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="./assets/js/_jquery.dataTables.min.js"></script>
    <script src="./assets/js/_dataTables.buttons.min.js"></script>
    <script src="./assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="./assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="./assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="./assets/js/_js_buttons.html5.min.js"></script>
    <script src="./assets/js/parsley.min.js"></script>
    <!-- <script src="./assets/js/custom-common-script.js"></script> -->
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#filter_from", {
                dateFormat: "d-m-Y", // Format: day-month-year
                altInput: true,
                altFormat: "d-m-Y"
            });
            flatpickr("#filter_to", {
                dateFormat: "d-m-Y", // Format: day-month-year
                altInput: true,
                altFormat: "d-m-Y"
            });
            flatpickr("#insurance_start_date", {
                dateFormat: "d-m-Y", // Format: day-month-year
                altInput: true,
                altFormat: "d-m-Y"
            });
            flatpickr("#insurance_end_date", {
                dateFormat: "d-m-Y", // Format: day-month-year
                altInput: true,
                altFormat: "d-m-Y"
            });
            flatpickr("#vehicle_fc_expiry_date", {
                dateFormat: "d-m-Y", // Format: day-month-year
                altInput: true,
                altFormat: "d-m-Y"
            });
        });
    </script>

</body>

</html>