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

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        /* Hide the "All Day" text in Calendar list view */
        .fc-list-event-time {
            display: none;
        }

        .fc-timegrid-axis-cushion,
        .fc-timegrid-slot {
            display: none;
        }

        .light-style .fc .fc-day-today {
            background-color: #fff !important;
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
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h4 class="mb-0">Payout Preview</h4>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-12 d-flex justify-content-between">
                                <h5 class="mb-0 text-primary"><b>DVI202409-017 | Cochin to Trivandrum 6D 5N</b></h5>
                                <h5 class="mb-0 text-primary"><b>03 OCT 2024 to 07 OCT 2024</b></h5>
                            </div>
                            <div class="col-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start justify-content-center">
                                            <div class="content-left">
                                                <span class="text-muted">Total Billed</span>
                                                <div class="d-flex align-items-center mt-2">
                                                    <h3 class="mb-0 me-2">₹50,000</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start justify-content-center">
                                            <div class="content-left">
                                                <span class="text-muted">Total Received</span>
                                                <div class="d-flex align-items-center mt-2">
                                                    <h3 class="mb-0 me-2">₹40,000</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start justify-content-center">
                                            <div class="content-left">
                                                <span class="text-muted">Total Receivable</span>
                                                <div class="d-flex align-items-center mt-2">
                                                    <h3 class="mb-0 me-2">₹10,000</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start justify-content-center">
                                            <div class="content-left">
                                                <span class="text-muted">Total Payout</span>
                                                <div class="d-flex align-items-center mt-2">
                                                    <h3 class="mb-0 me-2">₹10,000</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                            <div class="col-2">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start justify-content-center">
                                            <div class="content-left">
                                                <span class="text-muted">Total Payable</span>
                                                <div class="d-flex align-items-center mt-2">
                                                    <h3 class="mb-0 me-2">₹10,000</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="row">
                            <div class="col-xl-12">
                                <div class="nav-align-top mb-4">
                                    <div class="tab-content">
                                        <div class="col-12 d-flex justify-content-between">
                                            <h5><b>DVI202409-017 | Cochin to Trivandrum 6D 5N</b></h5>
                                            <h5><b>03 OCT 2024 to 07 OCT 2024</b></h5>
                                        </div>
                                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                                            <table class="table table-hover dataTable " id="rolemenu_LIST">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Total Billed</th>
                                                        <th scope="col">Total Received</th>
                                                        <th scope="col">Total Payout</th>
                                                        <th scope="col">Total Receivable</th>
                                                        <th scope="col">Total Payable</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>₹50,000</td>
                                                        <td>₹40,000</td>
                                                        <td>₹10,000</td>
                                                        <td>₹40,000</td>
                                                        <td>₹10,000</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col-12 mb-4">
                                <h4 class="mb-0">Hotel Detail</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="nav-align-top mb-4">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                                            <table class="table table-hover dataTable payout-hotel-details-table-section" id="rolemenu_LIST">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">S.No</th>
                                                        <th scope="col">Day</th>
                                                        <th scope="col">Destination</th>
                                                        <th scope="col">Hotel Name</th>
                                                        <th scope="col">Room Type</th>
                                                        <th scope="col">Price</th>
                                                        <th scope="col">Meal Plan</th>
                                                        <th scope="col">Total Amount</th>
                                                        <th scope="col">Total Payout</th>
                                                        <th scope="col">Total Payable</th>
                                                        <th scope="col">Enter the amount</th>
                                                        <th scope="col" style="width: 150px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>1</td>
                                                        <td>Munnar</td>
                                                        <td>Laspalmas</td>
                                                        <td>Deluxe</td>
                                                        <td>₹10,000.00</td>
                                                        <td>BLD</td>
                                                        <td>₹30,000.00</td>
                                                        <td>₹45,000.00</td>
                                                        <td>₹50,000.00</td>
                                                        <td><input type="text" class="form-control" id="defaultFormControlInput" placeholder="Enter Amount" value="₹3000" aria-describedby="defaultFormControlHelp" /></td>
                                                        <td><button type="submit" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target=".accountmanageraddpaymentmodalsection">Pay Now</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>1</td>
                                                        <td>Kochi</td>
                                                        <td>The Park Hotel</td>
                                                        <td>Deluxe</td>
                                                        <td>₹10,000.00</td>
                                                        <td>BLD</td>
                                                        <td>₹30,000.00</td>
                                                        <td>₹45,000.00</td>
                                                        <td>₹50,000.00</td>
                                                        <td><input type="text" class="form-control" id="defaultFormControlInput" placeholder="Enter Amount" value="₹3000" aria-describedby="defaultFormControlHelp" /></td>
                                                        <td><button type="submit" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target=".accountmanageraddpaymentmodalsection">Pay Now</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>1</td>
                                                        <td>Coorg</td>
                                                        <td>Royal King Hotel</td>
                                                        <td>Deluxe</td>
                                                        <td>₹10,000.00</td>
                                                        <td>BLD</td>
                                                        <td>₹30,000.00</td>
                                                        <td>₹45,000.00</td>
                                                        <td>₹50,000.00</td>
                                                        <td><input type="text" class="form-control" id="defaultFormControlInput" placeholder="Enter Amount" value="₹3000" aria-describedby="defaultFormControlHelp" /></td>
                                                        <td><button type="submit" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target=".accountmanageraddpaymentmodalsection">Pay Now</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <h4 class="mb-0">Vehicle Detail</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="nav-align-top mb-4">
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                                            <table class="table table-hover dataTable payout-vehicle-details-table-section" id="rolemenu_LIST">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">S.No</th>
                                                        <th scope="col">Vehicle Type</th>
                                                        <th scope="col">Vendor Name</th>
                                                        <th scope="col">Vendor Branch</th>
                                                        <th scope="col">Vehicle Origin</th>
                                                        <th scope="col">Qty</th>
                                                        <th scope="col">Total Amount</th>
                                                        <th scope="col">Total Payout</th>
                                                        <th scope="col">Total Payable</th>
                                                        <th scope="col">Enter the amount</th>
                                                        <th scope="col" style="width: 150px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Sedan</td>
                                                        <td>Uma</td>
                                                        <td>VSR</td>
                                                        <td>Chennai</td>
                                                        <td>1</td>
                                                        <td>₹50,000.00</td>
                                                        <td>₹30,000.00</td>
                                                        <td>₹45,000.00</td>
                                                        <td><input type="text" class="form-control" id="defaultFormControlInput" placeholder="Enter Amount" value="₹3000" aria-describedby="defaultFormControlHelp" /></td>
                                                        <td><button type="submit" class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target=".accountmanageraddpaymentmodalsection">Pay Now</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <h4 class="mb-0">Payment Activity</h4>
                            </div>
                        </div>

                        <div class="col-xl-12 paymenthistorydetailssection" id="paymenthistorydetailssection">
                            <div class="nav-align-top mb-4">
                                <!-- <ul class="nav nav-pills mb-3" role="tablist">
                                    <li class="nav-item">
                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-all" aria-controls="navs-pills-top-all" aria-selected="true">All</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-complete" aria-controls="navs-pills-top-complete" aria-selected="false">Complete</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-pending" aria-controls="navs-pills-top-pending" aria-selected="false">Pending</button>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-rejected" aria-controls="navs-pills-top-rejected" aria-selected="false">Rejected</button>
                                    </li>
                                </ul> -->
                                <div class="tab-content p-0">
                                    <div class="tab-pane fade show active" id="navs-pills-top-all" role="tabpanel">
                                        <div class="list-group">
                                            <div href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between col-12">
                                                <div class="li-wrapper d-flex align-items-center col-9">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="fa-solid fa-check"></i></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Paid amount of ₹5,000 to the vendor <b>Uma</b></h6>
                                                        <h6 class="mb-1">UTR : <a href="#">4455321</a></h6>
                                                        <h6 style="font-size: 14px;" class="mb-0"><small class="text-muted">Paid By</small> Saran Kumar</h6>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-0 text-end"><small>06-10-2024 08:30 PM</small></h5>
                                                    <h6 class="mb-0 text-end"><small class="text-muted" style="font-size: 12px;">3 days ago</small></h6>
                                                </div>
                                            </div>
                                            <div href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between col-12">
                                                <div class="li-wrapper d-flex align-items-center col-9">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="fa-solid fa-check"></i></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Paid amount of ₹15,000 to the hotel <b>The Park</b></h6>
                                                        <h6 class="mb-1">UTR : <a href="#">4455376</a></h6>
                                                        <h6 style="font-size: 14px;" class="mb-0"><small class="text-muted">Paid By</small> Kiran</h6>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-0 text-end"><small>05-10-2024 08:30 PM</small></h5>
                                                    <h6 class="mb-0 text-end"><small class="text-muted" style="font-size: 12px;">4 days ago</small></h6>
                                                </div>
                                            </div>
                                            <div href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between col-12">
                                                <div class="li-wrapper d-flex align-items-center col-9">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="fa-solid fa-check"></i></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Paid amount of ₹10,060 to the vendor <b>Uma</b></h6>
                                                        <h6 class="mb-1">UTR : <a href="#">4455321</a></h6>
                                                        <h6 style="font-size: 14px;" class="mb-0"><small class="text-muted">Paid By</small> Saran Kumar</h6>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-0 text-end"><small>06-09-2024 12:30 PM</small></h5>
                                                    <h6 class="mb-0 text-end"><small class="text-muted" style="font-size: 12px;">1 month ago</small></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-pills-top-complete" role="tabpanel">
                                        <div class="list-group">
                                            <div href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between col-12">
                                                <div class="li-wrapper d-flex align-items-center col-9">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-success"><i class="fa-solid fa-check"></i></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Paid amount of ₹5,000 to the vendor <b>Uma</b></h6>
                                                        <h6 class="mb-1">UTR : <a href="#">4455321</a></h6>
                                                        <h6 style="font-size: 14px;" class="mb-0"><small class="text-muted">Paid By</small> Saran Kumar</h6>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-0 text-end"><small>06-10-2024 08:30 PM</small></h5>
                                                    <h6 class="mb-0 text-end"><small class="text-muted" style="font-size: 12px;">3 days ago</small></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-pills-top-pending" role="tabpanel">
                                        <div class="list-group">
                                            <div href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between col-12">
                                                <div class="li-wrapper d-flex align-items-center col-9">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-warning"><i class="fa-solid fa-exclamation"></i></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Pending amount of ₹6,090 to the hotel <b>Taj</b></h6>
                                                        <h6 class="mb-1">UTR : <a href="#">4455382</a></h6>
                                                        <h6 style="font-size: 14px;" class="mb-0"><small class="text-muted">Hold By</small> Saran Kumar</h6>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-0 text-end"><small>26-09-2024 12:30 AM</small></h5>
                                                    <h6 class="mb-0 text-end"><small class="text-muted" style="font-size: 12px;">1 month ago</small></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navs-pills-top-rejected" role="tabpanel">
                                        <div class="list-group">
                                            <div href="javascript:void(0);" class="list-group-item list-group-item-action d-flex justify-content-between col-12">
                                                <div class="li-wrapper d-flex align-items-center col-9">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-danger"><i class="fa-solid fa-xmark"></i></span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1">Rejected amount of ₹1,090 to the hotel <b>Taj</b></h6>
                                                        <h6 class="mb-1">UTR : <a href="#">4455397</a></h6>
                                                        <h6 style="font-size: 14px;" class="mb-0"><small class="text-muted">Rejected By</small> Saran Kumar</h6>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="mb-0 text-end"><small>05-10-2024 11:30 AM</small></h5>
                                                    <h6 class="mb-0 text-end"><small class="text-muted" style="font-size: 12px;">4 days ago</small></h6>
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

    <div class="modal fade" id="showDELETEMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content receiving-delete-form-data">
            </div>
        </div>
    </div>

    <div class="modal-onboarding modal fade animate__animated" id="showSWIPERGALLERYMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="z-index: 999999;">
        <div class="modal-dialog modal-md modal-dialog-center">
            <div class="modal-content receiving-swiper-room-form-data">
            </div>
        </div>
    </div>

    <div class="modal fade" id="showPRICEBOOKFORM" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-top">
            <div class="modal-content">
                <div class="modal-body show-pricebook-form-data">
                </div>
            </div>
        </div>
    </div>

    <!-- Account Manager Payout Pay Now Modal -->
    <div class="modal fade accountmanageraddpaymentmodalsection" id="enableOTP" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2">Add Payment</h3>
                    </div>
                    <form id="enableOTPForm" class="row g-3" onsubmit="return false">
                        <div class="col-12">
                            <div>
                                <label for="defaultFormControlInput" class="form-label">Processed By</label>
                                <input type="text" class="form-control" id="defaultFormControlInput" value="Account Manager - Karan" aria-describedby="defaultFormControlHelp" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <label for="exampleFormControlSelect1" class="form-label">Mode of Payment</label>
                                <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                                    <option selected>Select Payment Method</option>
                                    <option value="1">Card</option>
                                    <option value="2">UPI</option>
                                    <option value="3">Net Banking</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <label for="defaultFormControlInput" class="form-label">UTR Number</label>
                                <input type="text" class="form-control" id="defaultFormControlInput" value="4764921" aria-describedby="defaultFormControlHelp" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div>
                                <label for="exampleFormControlSelect1" class="form-label">Payment Screenshot</label>
                                <input type="file" id="fileInput" style="display: none;" />
                                <button id="formFile" type="button" class="btn clicktoupload-btn-outline waves-effect w-100 d-flex justify-content-between">
                                    <span>Click to Upload</span>
                                    <span class="ti-xs ti ti-upload me-1"></span>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-between">
                            <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Account Manager Payout Pay Now Modal -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

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
        document.getElementById('formFile').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });
    </script>

</body>

</html>