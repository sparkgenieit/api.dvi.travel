<?php

include_once("jackus.php");
admin_reguser_protect();
$current_page = 'guide_cost_pricebook.php'; // Set the current page variable
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
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <!-- Bootstrap-timepicker CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>

</head>
<style>
    /* Hide the input fields initially */
    .input-fields {
        display: none;
    }

    .fc .fc-more-popover .fc-popover-body {
        max-height: 314px;
        overflow-y: auto;
    }

    /* WebKit (Chrome, Safari) */
    .fc .fc-more-popover .fc-popover-body::-webkit-scrollbar {
        width: 8px;
    }

    .fc .fc-more-popover .fc-popover-body::-webkit-scrollbar-thumb {
        background-color: #dbdade !important;
        border-radius: 6px;
    }

    .fc .fc-more-popover .fc-popover-body::-webkit-scrollbar-track {
        background-color: #f4f4f5;
    }

    /* Firefox */
    .fc .fc-more-popover .fc-popover-body {
        scrollbar-width: 8px;
        scrollbar-color: #dbdade #f4f4f5;
    }

    /* Microsoft Edge */
    .fc .fc-more-popover .fc-popover-body {
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }
</style>

<body>
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">

            <!-- Menu -->
            <?php include_once('public/__sidebar.php'); ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include_once('public/__topbar.php'); ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-12 col-lg-10 mb-2">
                                <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                                <?php include adminpublicpath('__breadcrumb.php'); ?>
                            </div>
                            <!-- <div class="col-12 col-lg-2 d-flex float-right">
                                <div class="btn-group" id="dropdown-icon-demo">
                                    <button type="button" class="btn btn-primary dropdown-toggle waves-effect waves-light show" data-bs-toggle="dropdown" aria-expanded="true"><i class="ti ti-menu-2 ti-xs me-1"></i> Bulk Import</button>
                                    <ul class="dropdown-menu show" data-popper-placement="top-start" style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(0px, -40px, 0px);">
                                        <li><a href="hotel_room_pricebook.php" class="dropdown-item d-flex align-items-center"><i class="ti ti-chevron-right scaleX-n1-rtl"></i>Rooms Price</a></li>
                                        <li><a href="hotel_amenities_pricebook.php" class="dropdown-item d-flex align-items-center"><i class="ti ti-chevron-right scaleX-n1-rtl"></i>Amenities Price </a></li>
                                    </ul>
                                </div>
                            </div> -->
                        </div>

                        <form class="" id="form_guide_pricebook" method="post" data-parsley-validate>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card app-calendar-wrapper p-4">
                                        <div class="row g-3">
                                            <div class="col-3">
                                                <label class="form-label" for="guide">Guide<span class="text-danger"> *</span></label>
                                                <select id="guide" name="hidden_guide_ID" required class="form-select form-control">
                                                    <?= getGUIDEDETAILS($guide, 'select'); ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label for="selectstartdate" class="form-label">Start Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
                                            </div>
                                            <div class="col-3">
                                                <label for="selectenddate" class="form-label">End Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
                                            </div>

                                        </div>

                                        <div class="col-12" id="guideDetailsWrapper" style="display: none;">
                                            <div class="mt-3" id="cost_type_local">
                                                <h5 class="">Guide Cost Details</h5>
                                                <div class="row">
                                                    <div class="col-8">
                                                        <table class="table table-bordered ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Pax Count</th>
                                                                    <th>Slot Type</th>
                                                                    <th>Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                                <tr>
                                                                    <td>
                                                                        <h6 class="m-0"><span class="text-primary">1-5</span> Pax </h6>
                                                                        <input type="hidden" name="pax_type[]" value="1">
                                                                    </td>
                                                                    <td>
                                                                        <h6 class="mb-3">Slot 1: <span class="text-primary">8 AM to 1 PM</span></h6>
                                                                        <h6 class="mb-3">Slot 2: <span class="text-primary">1 PM to 6 PM</span></h6>
                                                                        <h6 class="mb-2">Slot 3: <span class="text-primary">6 PM to 9 PM</span></h6>
                                                                        <input type="hidden" name="pax_slot_type[]" value="1">
                                                                        <input type="hidden" name="pax_slot_type[]" value="2">
                                                                        <input type="hidden" name="pax_slot_type[]" value="3">
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax1_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax1_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax1_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="m-0"><span class="text-primary">6-14</span> Pax</h6>
                                                                        <input type="hidden" name="pax_type[]" value="2">
                                                                    </td>
                                                                    <td>
                                                                        <h6 class="mb-3">Slot 1: <span class="text-primary">8 AM to 1 PM</span></h6>
                                                                        <h6 class="mb-3">Slot 2: <span class="text-primary">1 PM to 6 PM</span></h6>
                                                                        <h6 class="mb-2">Slot 3: <span class="text-primary">6 PM to 9 PM</span></h6>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax2_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax2_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax2_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="m-0"><span class="text-primary">15-40</span> Pax</h6>
                                                                        <input type="hidden" name="pax_type[]" value="3">
                                                                    </td>
                                                                    <td>
                                                                        <h6 class="mb-3">Slot 1: <span class="text-primary">8 AM to 1 PM</span></h6>
                                                                        <h6 class="mb-3">Slot 2: <span class="text-primary">1 PM to 6 PM</span></h6>
                                                                        <h6 class="mb-2">Slot 3: <span class="text-primary">6 PM to 9 PM</span></h6>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax3_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax3_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="guide_price_pax_1-5" name="pax3_slot_price[]" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex mt-3 justify-content-end">
                                            <div>
                                                <button type="submit" id="btn_guide_submit" class="btn btn-primary waves-effect waves-light pe-3">Submit<?= $btn_label ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="row mt-3">
                                <div class="col-12">
                                    <div class="card app-calendar-wrapper p-4">
                                        <div class="row g-3">
                                            <div class="col-3">
                                                <label class="form-label " for="guide">Guide<span class=" text-danger"> *</span></label>
                                                <select id="guide" name="hidden_guide_ID" required class="form-select form-control">
                                                    <?= getGUIDEDETAILS($guide, 'select'); ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label " for="year">Year<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <input type="text" class="form-control" placeholder="Choose year" name="year" id="year" required autocomplete="off" />
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label " for="month">Month<span class=" text-danger"> *</span></label>
                                                <select id="month" name="month" required class="form-select form-control">
                                                    <?= getMONTHS_LIST($month_id, 'select'); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="pax_count">Pax Count<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select id="pax_count_selected" name="pax_count_selected" class="form-control form-select">
                                                        <?= getPAXCOUNTDETAILS($pax_count, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="slot_selected">Slot Type<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select id="slot_selected" name="slot_selected" class="form-control form-select">
                                                        <?= getSLOTTYPE($slot_id, 'multiselect') ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <label class="form-label" for="price">Price ₹</label>
                                                <input type="text" id="price" name="price" required class="form-control" placeholder="Enter Price">
                                            </div>
                                            <div class="col-3">
                                                <label for="selectstartdate" class="form-label">Start Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
                                            </div>
                                            <div class="col-3">
                                                <label for="selectenddate" class="form-label">End Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
                                            </div>

                                        </div>
                                        <div class="d-flex mt-3 justify-content-end">
                                            <div>
                                                <button type="submit" class="btn btn-primary waves-effect waves-light pe-3">Submit<?= $btn_label ?></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </form>
                        <div class="mt-4">
                            <div class="card app-calendar-wrapper">
                                <div class="row g-0">
                                    <!-- Calendar Sidebar -->
                                    <div class="col app-calendar-sidebar" id="app-calendar-sidebar">
                                        <div class="p-3">
                                            <!-- Filter -->
                                            <div class="mb-3 ms-3">
                                                <small class="text-small text-muted text-uppercase align-middle">Filter</small>
                                            </div>

                                            <div class="form-check mb-2 ms-3">
                                                <input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked>
                                                <label class="form-check-label" for="selectAll">View All</label>
                                            </div>
                                            <div class="app-calendar-events-filter ms-3">
                                                <div class="form-check form-check-success mb-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-slot1" data-value="slot1" checked>
                                                    <label class="form-check-label" for="select-slot1">Slot 1</label>
                                                </div>
                                                <div class="form-check form-check-warning mb-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-slot2" data-value="slot2" checked>
                                                    <label class="form-check-label" for="select-slot2">Slot 2</label>
                                                </div>
                                                <div class="form-check form-check-danger mb-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-slot3" data-value="slot3" checked>
                                                    <label class="form-check-label" for="select-slot3">slot 3</label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <!-- Column for Pax selection -->
                                                <div class="mb-3 me-4">
                                                    <label class="form-label " for="guideFilter">Guide<span class=" text-danger"> *</span></label>
                                                    <select id="guideFilter" name="guideFilter" required class="form-select form-control">
                                                        <option value="null">All</option>
                                                        <?= getGUIDEDETAILS($_GET['id'], 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <!-- Column for Pax selection -->
                                                <div class="mb-3 me-4">
                                                    <label class="form-label" for="paxFilter">Select Pax</label>
                                                    <select id="paxFilter" name="paxFilter" class="form-control form-select">
                                                        <option value="null">All</option>
                                                        <?= getPAXCOUNTDETAILS($_GET['pax_id'], 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Calendar Sidebar -->

                                    <!-- Calendar & Modal -->
                                    <div class="col app-calendar-content">
                                        <div class="card shadow-none border-0">
                                            <div class="card-body pb-0">
                                                <!-- FullCalendar -->
                                                <div id="calendar"></div>
                                            </div>
                                        </div>
                                        <div class="app-overlay"></div>
                                    </div>
                                    <!-- /Calendar & Modal -->
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card-body pb-0">
                                <!-- FullCalendar -->
                                <div id="calendar"></div>
                            </div>
                        </div>
                        <div class="app-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>

    </div>
    <!-- / Layout wrapper -->

    <!-- Enable OTP Modal -->
    <div class="modal fade" id="showPRICEBOOKFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body show-pricebook-form-data">
                </div>
            </div>
        </div>
    </div>

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <!-- <script src="assets/vendor/libs/select2/select2.js"></script> -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/app-guide-calendar.js?id=<?= $_GET['id']; ?>&pax_id=<?= $_GET['pax_id']; ?>"></script>
    <!-- Main JS -->
    <script src=" assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/app-calendar-events.js"></script>
    <script src="assets/js/app-calendar.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
    <!-- Page JS -->
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {

            $("select").selectize();

            var startDatePicker = flatpickr("#selectstartdate", {
                dateFormat: "d-m-Y",
                onChange: function(selectedDates, dateStr, instance) {
                    endDatePicker.set("minDate", dateStr);
                }
            });

            var endDatePicker = flatpickr("#selectenddate", {
                dateFormat: "d-m-Y"
            });

            $('#guideFilter').on('change', function() {
                const selectedValue = $(this).val();
                const currentURL = new URL(window.location.href);
                currentURL.searchParams.set('id', selectedValue);
                history.replaceState(null, null, currentURL);
                location.reload();
            });

            $('#paxFilter').on('change', function() {
                const selectedValue = $(this).val();
                const currentURL = new URL(window.location.href);
                currentURL.searchParams.set('pax_id', selectedValue);
                history.replaceState(null, null, currentURL);
                location.reload();
            });

            //AJAX FORM SUBMIT
            $("#form_guide_pricebook").submit(function(event) {
                var form = $('#form_guide_pricebook')[0];
                var data = new FormData(form);
                $(this).find("button[id='btn_guide_submit']").prop('disabled', true);
                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_guide.php?type=guide_pricebook',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 80000,
                    dataType: 'json',
                    encode: true,
                }).done(function(response) {

                    if (!response.success) {
                        //NOT SUCCESS RESPONSE
                        if (response.errors.guide_required) {
                            TOAST_NOTIFICATION('error', 'Guide is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.selectstartdate_required) {
                            TOAST_NOTIFICATION('error', 'Start date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.selectenddate_required) {
                            TOAST_NOTIFICATION('error', 'End Date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    } else {
                        //SUCCESS RESPOSNE
                        if (response.u_result == true) {
                            //RESULT SUCCESS
                            document.getElementById("form_guide_pricebook").reset();
                            TOAST_NOTIFICATION('success', 'Guide Price Book Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }

                    }
                    if (response == "OK") {
                        return true;
                    } else {
                        return false;
                    }
                });
                event.preventDefault();
            });

            var guideSelect = $('#guide');
            var guideDetailsWrapper = $('#guideDetailsWrapper');

            console.log(guideSelect); // Check if guideSelect is correctly selected
            console.log(guideDetailsWrapper); // Check if guideDetailsWrapper is correctly selected

            guideSelect.on('change', function() {
                console.log("Guide select value changed"); // Log when the value changes
                guideDetailsWrapper.toggle(this.value !== '');
            });

        });
    </script>
</body>

</html>