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

$current_month_first_date = date('d-m-Y', strtotime('first day of this month'));
$current_month_last_date = date('d-m-Y', strtotime('last day of this month'));

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
    <link rel="stylesheet" href="assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.bootstrap5.css">
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
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <div class="card p-3">
                                    <div class="row">
                                        <h5>FILTER</h5>
                                        <div class="col-3">
                                            <div>
                                                <label class="form-label" for="from_date">From Date:</label>
                                                <input type="text" class="form-control" id="from_date" placeholder="Select Date">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div>
                                                <label class="form-label" for="to_date">To Date:</label>
                                                <input type="text" class="form-control" id="to_date" placeholder="Select Date">
                                            </div>
                                        </div>
                                        <!-- <div class="col-6 text-end">
                                            <a type="button" href="dailymoment_tracker.php" class="btn btn-primary mt-4">Clear</a>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card p-0">
                                    <div class="card-header pb-3">
                                        <div class=" d-flex justify-content-between align-items-center">
                                            <h5 class="card-title mb-0">List of Daily Moment</h5>
                                            <div>
                                                <button id="export-accounts-btn" class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body dataTable_select text-nowrap">
                                        <div class="text-nowrap table-responsive table-bordered">
                                            <table class="table table-hover" id="daily_moment_LIST">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Guest Name</th>
                                                        <th>Quote ID</th>
                                                        <th>Route Date</th>
                                                        <th>Type(A/D/O)</th>
                                                        <th>From Location</th>
                                                        <th>To Location</th>
                                                        <th>Arrival Flight/Train Details</th>
                                                        <th>Departure Flight/Train Details</th>
                                                        <th>Hotel</th>
                                                        <th>Meal Plan</th>
                                                        <th>Vendor</th>
                                                        <th>Vehicle</th>
                                                        <th>Vehicle No</th>
                                                        <th>Driver Name</th>
                                                        <th>Driver Mobile</th>
                                                        <th>Special Remark</th>
                                                        <th>Travel Expert</th>
                                                        <th>Agent</th>
                                                    </tr>
                                                </thead>
                                            </table>
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
    <script src="assets/vendor/libs/datatables-fixedcolumns-bs5/fixedcolumns.min.js"></script>
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
        $(document).ready(function() {


            let fromDate = "<?= $current_month_first_date ?>";
            let toDate = "<?= $current_month_last_date ?>";

            // Initialize Flatpickr for From Date
            const fromDatePicker = flatpickr("#from_date", {
                enableTime: false,
                dateFormat: "d-m-Y",
                defaultDate: fromDate, // set default
                onChange: function(selectedDates, dateStr, instance) {
                    fromDate = dateStr;
                    toDatePicker.set('minDate', selectedDates[0]);
                    updateDataTable();
                },
            });

            // Initialize Flatpickr for To Date
            const toDatePicker = flatpickr("#to_date", {
                enableTime: false,
                dateFormat: "d-m-Y",
                defaultDate: toDate, // set default
                onChange: function(selectedDates, dateStr, instance) {
                    toDate = dateStr;
                    updateDataTable();
                },
            });

            // Function to Update DataTable URL with Dates
            function updateDataTable() {
                // Ensure both dates are passed correctly in the URL
                const url = `engine/json/__JSONdailymoment.php?from_date=${encodeURIComponent(fromDate)}&to_date=${encodeURIComponent(toDate)}`;
                $('#daily_moment_LIST').DataTable().ajax.url(url).load();
            }

            // Initialize DataTable
            $('#daily_moment_LIST').DataTable({
                dom: 'lfrtip',
                bFilter: true,
                scrollX: true, // Enable horizontal scrolling
                fixedColumns: {
                    leftColumns: 3, // Make the first three columns sticky
                },

                ajax: {
                    url: `engine/json/__JSONdailymoment.php?from_date=${encodeURIComponent(fromDate)}&to_date=${encodeURIComponent(toDate)}`,
                    type: "GET",
                },
                columns: [{
                        data: "count"
                    }, // 0
                    {
                        data: "guest_name"
                    }, // 1
                    {
                        data: "itinerary_plan_ID"
                    }, // 2
                    {
                        data: "route_date"
                    }, // 2
                    {
                        data: "trip_type"
                    }, // 3
                    {
                        data: "location_name"
                    }, // 4
                    {
                        data: "next_visiting_location"
                    }, // 5
                    {
                        data: "arrival_flight_details"
                    }, // 6
                    {
                        data: "departure_flight_details"
                    }, // 7
                    {
                        data: "hotel_name"
                    }, // 8
                    {
                        data: "meal_plan"
                    }, // 9
                    {
                        data: "vendor_name"
                    }, // 10
                    {
                        data: "vehicle_type_title"
                    }, // 11
                    {
                        data: "vehicle_no"
                    }, // 12
                    {
                        data: "driver_name"
                    }, // 13
                    {
                        data: "driver_mobile"
                    }, // 14
                    {
                        data: "special_remarks"
                    }, // 15
                    {
                        data: "travel_expert_name"
                    }, // 16
                    {
                        data: "agent_name"
                    }, // 17
                ],
                columnDefs: [{
                    targets: 0,
                    data: "count",
                    render: function(data, type, row, full) {
                        return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Driver Feedback" href="dailymoment.php?formtype=day_list&id=' + row.itinerary_plan_ID + '" target="_blank" style="margin-right: 3px;"><span class="btn-inner"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g data-name="13-car"><path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path><path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#8b8b8b" opacity="1" data-original="#000000"></path></g></g></svg></span> </a></div>';
                    },
                    //<a class="btn btn-sm btn-icon text-danger flex-end" href="dailymoment.php?formtype=day_list_guide&id=' + row.itinerary_plan_ID + '" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Guide Feedback"> <span class="btn-inner"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="22px" height="22px" x="0" y="0" viewBox="0 0 510 510" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="m201.419 302.352-.951 3.955L255 343.627l54.516-37.315-.935-3.961c-33.671 16.875-73.493 16.873-107.162.001zM255 0c-52.806 0-87.355 39.182-94.654 90h189.309C342.355 39.182 307.806 0 255 0zM360 120H150l-30 45h270zM225 450h60v60h-60zM240 368.012l-79.234-53.047C104.751 317.19 60 363.311 60 419.88v90h60V450h30v59.88h45V420h45zM349.237 314.965 270 368.01V420h45v89.88h45V450h30v59.88h60v-90c0-56.45-44.626-102.679-100.763-104.915zM345 195H165c0 49.706 40.294 90 90 90s90-40.294 90-90z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path></g></svg></span></a>
                }, {
                    targets: 2,
                    data: "quote_id",
                    render: function(data, type, row, full) {
                        return `<a class="text-primary" href="latestconfirmeditinerary.php?route=add&formtype=generate_itinerary&id=${data}" target="_blank" style="margin-right: 10px;">${row.quote_id}</a>`;
                    },
                }, ],
            });

            $('#export-accounts-btn').click(function() {
                if (!fromDate || !toDate) {
                    $('#export-accounts-btn').prop('disabled', true);
                    alert("Please select both From Date and To Date before exporting.");
                    return;
                }

                // Construct the URL with JavaScript variables for fromDate and toDate
                const exportUrl = `excel_export_dailymoment_tracker.php?from_date=${encodeURIComponent(fromDate)}&to_date=${encodeURIComponent(toDate)}`;
                window.location.href = exportUrl;
            });

        });
    </script>

</body>

</html>