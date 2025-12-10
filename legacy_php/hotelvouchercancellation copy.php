<?php
include_once("jackus.php");
admin_reguser_protect();

$itinerary_plan_ID = $_GET['cip_id'];

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
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

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>

    <style>
        .itinerary-header-title-sticky {
            position: sticky;
            top: 2px;
            background-color: #ffffff;
            z-index: 9;
        }
        .strikethrough {
            text-decoration: line-through;
            /* Apply line-through */
            color: gray;
            /* Optional: Change color to indicate it's struck out */
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
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Hotel Voucher Cancellation <span><a href="latestitinerary.php">[CQ-DVI202410-041] </a></span></h5>
                                    <a href="latestconfirmeditinerary_voucherdetails.php" class="btn btn-sm btn-label-github waves-effect ps-3"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i> Back to Voucher</a>
                                </div>
                                <div>
                                    <div class="itinerary-header-title-sticky card p-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <h5 class="text-primary">PAGODA RESORTS & STD </h5>
                                                <h5 class="text-dark ms-2"> | Allepay, kerala, India</h5>
                                            </div>
                                            <div>
                                                <p class="text-success fs-5 fw-bold"><i class="ti ti-point-filled"></i> Confirmed</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="text-light">Confirmed By</label>
                                                <p>Anjaly</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="text-light">Email ID</label>
                                                <p>Anjaly@gmail.com</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="text-light">Mobile No</label>
                                                <p>98966696996</p>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="text-light">Invoice To</label>
                                                <p>GST Bill against DVI</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion my-3" id="accordionExample">
                                        <div class="card accordion-item active">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                                    Cancellation Policy
                                                </button>
                                            </h2>

                                            <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="overflow-hidden mb-3 border " style="height: 200px;">
                                                        <div class="px-3 py-2" style="border-bottom: 1px solid #dddbdb">
                                                            <h6 class="text-primary m-0">Hotel Voucher Terms & Condition</h6>
                                                        </div>
                                                        <div class="text-blue-color p-3" id="vertical-example" style="max-height: 200px; overflow-y: auto;">
                                                            <p class="m-0" style="line-height: 27px;">
                                                                <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="text-nowrap overflow-hidden table-bordered">
                                                        <table class="table table-hover table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Cancellation Date</th>
                                                                    <th>Percentage</th>
                                                                    <th>Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>Oct 31, 2024</td>
                                                                    <td>50%</td>
                                                                    <td>Cancellation Charges from Total Value</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>Nov 05, 2024</td>
                                                                    <td>90%</td>
                                                                    <td>Cancellation Charges from Total Value</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nav-align-left nav-tabs-shadow mb-4">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#hotel-day-1" aria-controls="hotel-day-1" aria-selected="true">
                                                    <span><input class="form-check-input me-2 hotel-checkbox" type="checkbox"></span>
                                                    Day-1 | 07 Nov 2024
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#hotel-day-2" aria-controls="hotel-day-2" aria-selected="false"> <span><input class="form-check-input me-2 hotel-checkbox" type="checkbox"></span> Day-2 | 08 Nov 2024</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content py-2">
                                            <div class="tab-pane fade show active" id="hotel-day-1">
                                                <div id="room1">
                                                    <div class="ms-3 mt-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                Standard Room * 1
                                                            </h6>
                                                            <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-5 mt-2">
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child with Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 500</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child without Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 400</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Extra Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 700</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Breakfast
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 450</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Lunch
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 250</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Dinner
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 100</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div id="room2">
                                                    <div class="ms-3 mt-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                Delux Room * 1
                                                            </h6>
                                                            <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-5 mt-2">
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child with Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 500</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child without Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 400</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Extra Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 700</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Breakfast
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 450</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Lunch
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 250</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Dinner
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 100</h6>
                                                        </div>
                                                        <!-- Add other options similarly -->
                                                    </div>
                                                </div>
                                                <div class="ms-3 mt-2">
                                                    <h6 class="text-primary mb-2">Amenities</h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="m-0 text-blue-color">
                                                            <span><input class="form-check-input me-2 amentities-rate-checkbox" type="checkbox"></span>
                                                            Flower Bed Decoration
                                                        </h6>
                                                        <h6 class="mb-0 amentities-price">₹ 400</h6>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="text-end">
                                                    <h6 class="my-3 fw-bold">Total Cancellation Charge (5%): ₹ 500.00</h6>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="hotel-day-2">
                                                <div id="room1">
                                                    <div class="ms-3 mt-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                Standard Room * 1
                                                            </h6>
                                                            <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-5 mt-2">
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child with Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 500</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child without Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 400</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Extra Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 700</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Breakfast
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 450</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Lunch
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 250</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Dinner
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 100</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ms-3 mt-2">
                                                    <h6 class="text-primary mb-2">Amenities</h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="m-0 text-blue-color">
                                                            <span><input class="form-check-input me-2 amentities-rate-checkbox" type="checkbox"></span>
                                                            Flower Bed Decoration
                                                        </h6>
                                                        <h6 class="mb-0 amentities-price">₹ 400</h6>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="text-end">
                                                    <h6 class="my-3 fw-bold">Total Cancellation Charge (5%): ₹ 500.00</h6>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card p-4 px-3">
                                        <div class="row ">
                                            <div class="col-md-6"></div>
                                            <div class="col-12 col-md-6">
                                                <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                                <div class="order-calculations">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Cancellation Service</span>
                                                        <h6 class="mb-0">₹ 2000.00 </h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Cancellation Percentage(%)</span>
                                                        <h6 class="mb-0">5%</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Cancellation Charge</span>
                                                        <h6 class="mb-0">₹ 1900.00</h6>
                                                    </div>
                                                    <hr>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading fw-bold">Cancellation Charge</span>
                                                        <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field" style="width: 33%;" placeholder="Enter the Charge" value="₹ 1900">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex align-items-center mt-4">
                                            <div class="col-12 col-md-6">
                                                <h6 class="m-0 text-blue-color"><span><input class="form-check-input me-2" type="checkbox" id="hotel-rate-checkbox"></span>Cancellation charge display to hotel ?</h6></span>
                                            </div>
                                            <div class="col-12 col-md-6 text-end">
                                                <button type="button" class="btn btn-secondary">Cancel</button>
                                                <button type="button" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="divider">
                                    <div class="divider-text">
                                        <i class="ti ti-building-skyscraper text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="itinerary-header-title-sticky card p-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <h5 class="text-primary">QUALITY INN SABARI </h5>
                                                <h5 class="text-dark ms-2"> | Chennai, Tamilnadu, India</h5>
                                            </div>
                                            <div>
                                                <p class="text-danger fs-5 fw-bold"><i class="ti ti-point-filled"></i> Waitinglist</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="text-light">Confirmed By</label>
                                                <p>Anjaly</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="text-light">Email ID</label>
                                                <p>Anjaly@gmail.com</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="text-light">Mobile No</label>
                                                <p>98966696996</p>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="text-light">Invoice To</label>
                                                <p>GST Bill against DVI</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion my-3" id="accordionExample">
                                        <div class="card accordion-item active">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                                                    Cancellation Policy
                                                </button>
                                            </h2>

                                            <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="overflow-hidden mb-3 border " style="height: 200px;">
                                                        <div class="px-3 py-2" style="border-bottom: 1px solid #dddbdb">
                                                            <h6 class="text-primary m-0">Hotel Voucher Terms & Condition</h6>
                                                        </div>
                                                        <div class="text-blue-color p-3" id="vertical-example" style="max-height: 200px; overflow-y: auto;">
                                                            <p class="m-0" style="line-height: 27px;">
                                                                <?= geTERMSANDCONDITION('get_hotel_terms_n_condtions'); ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="text-nowrap overflow-hidden table-bordered">
                                                        <table class="table table-hover table-responsive">
                                                            <thead>
                                                                <tr>
                                                                    <th>S.No</th>
                                                                    <th>Cancellation Date</th>
                                                                    <th>Percentage</th>
                                                                    <th>Description</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1</td>
                                                                    <td>Oct 31, 2024</td>
                                                                    <td>50%</td>
                                                                    <td>Cancellation Charges from Total Value</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>2</td>
                                                                    <td>Nov 05, 2024</td>
                                                                    <td>90%</td>
                                                                    <td>Cancellation Charges from Total Value</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nav-align-left nav-tabs-shadow mb-4">
                                        <ul class="nav nav-tabs" role="tablist">
                                            <li class="nav-item">
                                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#hotel-day-1" aria-controls="hotel-day-1" aria-selected="true">
                                                    <span><input class="form-check-input me-2 hotel-checkbox" type="checkbox"></span>
                                                    Day-1 | 07 Nov 2024
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#hotel-day-2" aria-controls="hotel-day-2" aria-selected="false"> <span><input class="form-check-input me-2 hotel-checkbox" type="checkbox"></span> Day-2 | 08 Nov 2024</button>
                                            </li>
                                        </ul>
                                        <div class="tab-content py-2">
                                            <div class="tab-pane fade show active" id="hotel-day-1">
                                                <div id="room1">
                                                    <div class="ms-3 mt-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                Standard Room * 1
                                                            </h6>
                                                            <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-5 mt-2">
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child with Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 500</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child without Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 400</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Extra Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 700</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Breakfast
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 450</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Lunch
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 250</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Dinner
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 100</h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div id="room2">
                                                    <div class="ms-3 mt-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                Delux Room * 1
                                                            </h6>
                                                            <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-5 mt-2">
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child with Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 500</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child without Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 400</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Extra Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 700</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Breakfast
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 450</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Lunch
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 250</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Dinner
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 100</h6>
                                                        </div>
                                                        <!-- Add other options similarly -->
                                                    </div>
                                                </div>
                                                <div class="ms-3 mt-2">
                                                    <h6 class="text-primary mb-2">Amenities</h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <h6 class="m-0 text-blue-color">
                                                            <span><input class="form-check-input me-2 amentities-rate-checkbox" type="checkbox"></span>
                                                            Flower Bed Decoration
                                                        </h6>
                                                        <h6 class="mb-0 amentities-price">₹ 400</h6>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="text-end">
                                                    <h6 class="my-3 fw-bold">Total Cancellation Charge (5%): ₹ 500.00</h6>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="hotel-day-2">
                                                <div id="room1">
                                                    <div class="ms-3 mt-2">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 roomtype-rate-checkbox" type="checkbox"></span>
                                                                Standard Room * 1
                                                            </h6>
                                                            <h6 class="mb-0 room-price">₹ 4,500</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-5 mt-2">
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child with Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 500</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Child without Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 400</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Extra Bed
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 700</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Breakfast
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 450</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Lunch
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 250</h6>
                                                        </div>
                                                        <div class="d-flex align-items-center mb-1 justify-content-between">
                                                            <h6 class="m-0">
                                                                <span><input class="form-check-input me-2 hotel-rate-checkbox" type="checkbox"></span>
                                                                Dinner
                                                            </h6>
                                                            <h6 class="mb-0 price">₹ 100</h6>
                                                        </div>
                                                    </div>
                                                    <div class="ms-3 mt-2">
                                                        <h6 class="text-primary mb-2">Amenities</h6>
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <h6 class="m-0 text-blue-color">
                                                                <span><input class="form-check-input me-2 amentities-rate-checkbox" type="checkbox"></span>
                                                                Flower Bed Decoration
                                                            </h6>
                                                            <h6 class="mb-0 amentities-price">₹ 400</h6>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="text-end">
                                                        <h6 class="my-3 fw-bold">Total Cancellation Charge (5%): ₹ 500.00</h6>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="card p-4 px-3">
                                        <div class="row ">
                                            <div class="col-md-6"></div>
                                            <div class="col-12 col-md-6">
                                                <h5 class="card-header p-0 mb-2 text-uppercase"><b>Overall Cost</b></h5>
                                                <div class="order-calculations">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Cancellation Service</span>
                                                        <h6 class="mb-0">₹ 2000.00 </h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Cancellation Percentage(%)</span>
                                                        <h6 class="mb-0">5%</h6>
                                                    </div>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading">Total Cancellation Charge</span>
                                                        <h6 class="mb-0">₹ 1900.00</h6>
                                                    </div>
                                                    <hr>
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="text-heading fw-bold">Cancellation Charge</span>
                                                        <input type="text" name="cancellation_charge" id="cancellation_charge" class="form-control required-field" style="width: 33%;" placeholder="Enter the Charge" value="₹ 1900">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex align-items-center mt-4">
                                            <div class="col-12 col-md-6">
                                                <h6 class="m-0 text-blue-color"><span><input class="form-check-input me-2" type="checkbox" id="hotel-rate-checkbox"></span>Cancellation charge display to hotel ?</h6></span>
                                            </div>
                                            <div class="col-12 col-md-6 text-end">
                                                <button type="button" class="btn btn-secondary">Cancel</button>
                                                <button type="button" class="btn btn-primary">Confirm</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- / Content -->
                </div>
                <!-- Footer -->
                <?php include_once('public/__footer.php'); ?>
                <!-- / Footer -->
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- Vendors JS -->
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <!-- Flat Picker -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Functionality for hotel-rate-checkbox
            $('.hotel-rate-checkbox').change(function() {
                // Add or remove strikethrough for the associated price within the same tab
                $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', this.checked);
            });

            // Functionality for hotel-checkbox
            $('.hotel-checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck all related checkboxes within the same active tab
                let $currentTab = $('.tab-pane.active'); // Get the currently active tab
                $currentTab.find('.roomtype-rate-checkbox, .hotel-rate-checkbox, .amentities-rate-checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all prices based on hotel-checkbox
                $currentTab.find('.hotel-rate-checkbox').each(function() {
                    $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                });

                // Toggle strikethrough for room prices
                $currentTab.find('.room-price').toggleClass('strikethrough', isChecked);

                // Toggle strikethrough for amenities prices
                $currentTab.find('.amentities-price').toggleClass('strikethrough', isChecked);
            });

            // Functionality for roomtype-rate-checkbox
            $('.roomtype-rate-checkbox').change(function() {
                let isChecked = this.checked;

                // Check/uncheck the associated hotel-rate-checkbox elements within the same room
                $(this).closest('#room1, #room2').find('.hotel-rate-checkbox').prop('checked', isChecked);

                // Toggle strikethrough for all hotel-rate-checkbox prices within the same room
                $(this).closest('#room1, #room2').find('.hotel-rate-checkbox').each(function() {
                    $(this).closest('.d-flex').find('.price').toggleClass('strikethrough', isChecked);
                });

                // Toggle strikethrough for the associated room price
                $(this).closest('.d-flex').find('.room-price').toggleClass('strikethrough', isChecked);
            });

            // Functionality for amentities-rate-checkbox
            $('.amentities-rate-checkbox').change(function() {
                let isChecked = this.checked;

                // Toggle strikethrough for the associated amenities price within the same tab
                $(this).closest('.d-flex').find('.amentities-price').toggleClass('strikethrough', isChecked);
            });
        });
    </script>
</body>

</html>