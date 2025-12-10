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
// admin_reguser_protect();
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
</head>

<body>
    <div class="layout-wrapper layout-content-navbar ">
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
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <form class="" id="form_activity_pricebook" method="post" data-parsley-validate>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card app-calendar-wrapper p-4">
                                        <div class="row g-3">
                                            <div class="col-3">
                                                <label class="form-label " for="hotspot">Hotspot<span class=" text-danger"> *</span></label>
                                                <select id="hotspot" name="hotspot" required class="form-select form-control" onchange="getACTIVITY_DETAILS();">
                                                    <?= getHOTSPOTDETAILS($hotspot_id, 'select'); ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label " for="activity">Activity<span class=" text-danger"> *</span></label>
                                                <select id="activity" name="hidden_activity_ID" required class="form-select form-control">
                                                    <?= getACTIVITYDETAILS($activity, 'select'); ?>
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label" for="nationality">Nationality<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select id="price_type" name="nationality" class="form-control form-select" required onchange="toggleActivityPrice()">
                                                        <option value="0">Choose Nationality</option>
                                                        <option value="1">Indian</option>
                                                        <option value="2">Non-Indian</option>
                                                        <option value="3">Both</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <label for="selectstartdate" class="form-label">Start Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
                                            </div>
                                            <div class="col-3">
                                                <label for="selectenddate" class="form-label">End Date</label>
                                                <input type="text" class="form-control show_datepicker" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
                                            </div>
                                            <div class="col-12" id="activity_price" style="display: none;">
                                                <h5 class="">Activity Cost Details</h5>
                                                <div class="row">
                                                    <div class="col-4" id="india_person" style="display: none;">
                                                        <table class="table table-bordered ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Person</th>
                                                                    <th>Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="mb-3 text-primary">Adult</h6>
                                                                        <h6 class="mb-3 text-primary">Children</h6>
                                                                        <h6 class="mb-2 text-primary">Infant</span></h6>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="adult_cost" name="adult_cost" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="child_cost" name="child_cost" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="infant_cost" name="infant_cost" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="col-4" id="foreign_person" style="display: none;">
                                                        <table class="table table-bordered ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Person</th>
                                                                    <th>Price</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <h6 class="mb-3 text-primary">Foreign Adult</h6>
                                                                        <h6 class="mb-3 text-primary">Foreign Children</h6>
                                                                        <h6 class="mb-2 text-primary">Foreign Infant</span></h6>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" id="foreign_adult_cost" name="foreign_adult_cost" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="foreign_child_cost" name="foreign_child_cost" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                        <input type="text" id="foreign_infant_cost" name="foreign_infant_cost" required="" class="form-control w-px-150 py-1 mb-1" placeholder="Enter Price" value="0">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex justify-content-end">
                                                <div>
                                                    <button type="submit" id="btn_frm_submit" class="btn btn-primary waves-effect waves-light pe-3 mt-4">Submit<?= $btn_label ?></button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

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
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-adult" data-value="adult" checked>
                                                    <label class="form-check-label" for="select-adult">Adult</label>
                                                </div>
                                                <div class="form-check form-check-warning mb-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-children" data-value="children" checked>
                                                    <label class="form-check-label" for="select-children">Children</label>
                                                </div>
                                                <div class="form-check form-check-danger mb-2">
                                                    <input class="form-check-input input-filter" type="checkbox" id="select-infant" data-value="infant" checked>
                                                    <label class="form-check-label" for="select-infant">Infant</label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <!-- Column for Pax selection -->
                                                <div class="mb-3 me-4">
                                                    <label class="form-label " for="hotspotFilter">Hotspot<span class=" text-danger"> *</span></label>
                                                    <select id="hotspotFilter" name="hotspotFilter" required class="form-select form-control">
                                                        <option value="null">All</option>
                                                        <?= getHOTSPOTDETAILS($_GET['hotspot_id'], 'select'); ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3 me-4">
                                                    <label class="form-label " for="activityFilter">Activity<span class=" text-danger"> *</span></label>
                                                    <select id="activityFilter" name="activityFilter" required class="form-select form-control">
                                                        <option value="null">All</option>
                                                        <?php
                                                        if ($_GET['id'] != "null") :
                                                            echo  getACTIVITYDETAILS($_GET['id'], 'select', $_GET['hotspot_id']);
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="mb-3 me-4">
                                                    <label class="form-label" for="nationality">Nationality<span class=" text-danger"> *</span></label>

                                                    <select id="nationalityFilter" name="nationalityFilter" class="form-control form-select">
                                                        <option value="null">All</option>
                                                        <option value="1" <?= ($_GET['nationality_id'] == 1) ?  "selected" : "" ?>>Indian</option>
                                                        <option value="2" <?= ($_GET['nationality_id'] == 2) ?  "selected" : "" ?>>Non-Indian</option>
                                                    </select>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col app-calendar-content">
                                        <div class="card shadow-none border-0">
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

    <div class="modal fade" id="showPRICEBOOKFORM" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-top">
            <div class="modal-content">
                <div class="modal-body show-pricebook-form-data">
                </div>
            </div>
        </div>
    </div>

    <!--  DELETE COURSE MODAL -->
    <div class="modal fade" id="confirmDELETEINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-0">
                <div class="modal-body receiving-confirm-delete-form-data">
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

    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>

    <!-- Vehicle Cost Calendar -->
    <script src="assets/js/app-activity-calendar.js?id=<?= $_GET['id']; ?>"></script>

    <script src=" assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <!-- Page JS -->
    <script src="assets/js/app-calendar-events.js"></script>
    <script src="assets/js/app-calendar.js"></script>
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />

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

            $('#activityFilter').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue != "") {

                    const hotspot_id = $('#hotspotFilter').val();
                    const nationality_id = $('#nationalityFilter').val();
                    const currentURL = new URL(window.location.href);
                    currentURL.searchParams.set('id', selectedValue);
                    currentURL.searchParams.set('hotspot_id', hotspot_id);
                    currentURL.searchParams.set('nationality_id', nationality_id);
                    history.replaceState(null, null, currentURL);
                    location.reload();
                }
            });

            $('#hotspotFilter').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue != "") {
                    filterACTIVITY_DETAILS();
                    const activity_id = $('#activityFilter').val();
                    const nationality_id = $('#nationalityFilter').val();
                    const currentURL = new URL(window.location.href);
                    currentURL.searchParams.set('hotspot_id', selectedValue);
                    currentURL.searchParams.set('nationality_id', nationality_id);
                    currentURL.searchParams.set('id', activity_id);
                    history.replaceState(null, null, currentURL);
                    location.reload();
                }
            });

            $('#nationalityFilter').on('change', function() {
                const selectedValue = $(this).val();
                if (selectedValue != "") {
                    const activity_id = $('#activityFilter').val();
                    const hotspot_id = $('#hotspotFilter').val();
                    const currentURL = new URL(window.location.href);
                    currentURL.searchParams.set('nationality_id', selectedValue);
                    currentURL.searchParams.set('hotspot_id', hotspot_id);
                    currentURL.searchParams.set('id', activity_id);
                    history.replaceState(null, null, currentURL);
                    location.reload();
                }
            });

            //AJAX FORM SUBMIT
            $("#form_activity_pricebook").submit(function(event) {
                var form = $('#form_activity_pricebook')[0];
                var data = new FormData(form);
                $(this).find("button[id='btn_frm_submit']").prop('disabled', true);
                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_activity.php?type=activity_pricebook',
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
                        if (response.errors.hotspot_required) {
                            TOAST_NOTIFICATION('error', 'Hotspot is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.activity_required) {
                            TOAST_NOTIFICATION('error', 'Activity is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.nationality_required) {
                            TOAST_NOTIFICATION('error', 'Nationality is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.selectstartdate_required) {
                            TOAST_NOTIFICATION('error', 'Start date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.selectenddate_required) {
                            TOAST_NOTIFICATION('error', 'End Date is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.adult_cost_required) {
                            TOAST_NOTIFICATION('error', 'Adult Cost is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.child_cost_required) {
                            TOAST_NOTIFICATION('error', 'Child Cost is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.infant_cost_required) {
                            TOAST_NOTIFICATION('error', 'Infant Cost is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.foreign_adult_cost_required) {
                            TOAST_NOTIFICATION('error', 'Adult Cost is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.foreign_child_cost_required) {
                            TOAST_NOTIFICATION('error', 'Child Cost is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errors.foreign_infant_cost_required) {
                            TOAST_NOTIFICATION('error', 'Infant Cost is Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    } else {
                        //SUCCESS RESPOSNE
                        if (response.u_result == true) {
                            //RESULT SUCCESS
                            document.getElementById("form_activity_pricebook").reset();
                            TOAST_NOTIFICATION('success', 'Activity Price Book Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

        });

        function filterACTIVITY_DETAILS() {

            var activity_selectize = $("#activityFilter")[0].selectize;
            var hotspot_id = $("#hotspotFilter").val();
            $.ajax({
                url: 'engine/ajax/__ajax_get_hotspot_activities.php?type=activity_selectize',
                type: "POST",
                data: {
                    hotspot_id: hotspot_id
                },
                success: function(response) {
                    // Append the response to the dropdown.

                    activity_selectize.clear();
                    activity_selectize.clearOptions();
                    activity_selectize.addOption(response);
                    <?php if ($_GET['id'] != "null") : ?>
                        activity_selectize.setValue('<?= $_GET['id'] ?>');
                    <?php else : ?>
                        activity_selectize.setValue(response[0].value);
                    <?php endif; ?>
                }
            });
        }

        function getACTIVITY_DETAILS() {

            var activity_selectize = $("#activity")[0].selectize;
            var hotspot_id = $("#hotspot").val();
            $.ajax({
                url: 'engine/ajax/__ajax_get_hotspot_activities.php?type=activity_selectize',
                type: "POST",
                data: {
                    hotspot_id: hotspot_id
                },
                success: function(response) {
                    // Append the response to the dropdown.

                    activity_selectize.clear();
                    activity_selectize.clearOptions();
                    activity_selectize.addOption(response);
                    activity_selectize.setValue(response[0].value);
                }
            });
        }

        function toggleActivityPrice() {
            var priceType = document.getElementById('price_type').value;
            var activityPrice = document.getElementById('activity_price');
            var indiaPerson = document.getElementById('india_person');
            var foreignPerson = document.getElementById('foreign_person');

            // Hide all sections
            activityPrice.style.display = 'none';
            indiaPerson.style.display = 'none';
            foreignPerson.style.display = 'none';

            // Show sections based on selected option
            if (priceType == '1') { // Indian
                indiaPerson.style.display = 'block';
            } else if (priceType == '2') { // Non-Indian
                foreignPerson.style.display = 'block';
            } else if (priceType == '3') { // Both
                indiaPerson.style.display = 'block';
                foreignPerson.style.display = 'block';
            }

            // Show activity price section if any option other than default is selected
            if (priceType != '') {
                activityPrice.style.display = 'block';
            }
        }
    </script>
</body>

</html>