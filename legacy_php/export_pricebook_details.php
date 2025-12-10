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
    <title><?php include_once(adminpublicpath('__pagetitle.php')); ?> | <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />

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
    <link rel="stylesheet" href="assets/vendor/js/bootstrap.min.js" />

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
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />

    <!-- Bootstrap-timepicker CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />

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
                            <div>
                                <h4>Export Price Details</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="nav-align-top  mb-4">
                                    <ul class="nav nav-tabs mb-3 p-2 border-0" role="tablist" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                                        <li class="nav-item">
                                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#home-pricebook" aria-controls="home-pricebook" aria-selected="true">Hotel
                                                Pricebook</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#amenities-pricebook" aria-controls="amenities-pricebook" aria-selected="false">Amenities
                                                Pricebook</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#vehicle-pricebook" aria-controls="vehicle-pricebook" aria-selected="false">Vehicle Pricebook</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#guide-pricebook" aria-controls="guide-pricebook" aria-selected="false">Guide Pricebook</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#hotspot-pricebook" aria-controls="hotspot-pricebook" aria-selected="false">Hotspot Pricebook</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#activity-pricebook" aria-controls="activity-pricebook" aria-selected="false">Activity Pricebook</button>
                                        </li>
                                        <li class="nav-item">
                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#toll-pricebook" aria-controls="toll-pricebook" aria-selected="false">Toll</button>
                                        </li>
                                        <li class="nav-item">

                                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#parking-pricebook" aria-controls="parking-pricebook" aria-selected="false">Parking</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
                                        <div class="tab-pane fade show active" id="home-pricebook" role="tabpanel">
                                            <span id="show_hotel_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="amenities-pricebook" role="tabpanel">
                                            <span id="show_hotel_amenities_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="vehicle-pricebook" role="tabpanel">
                                            <span id="show_vehicle_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="guide-pricebook" role="tabpanel">
                                            <span id="show_guide_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="hotspot-pricebook" role="tabpanel">
                                            <span id="show_hotspot_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="activity-pricebook" role="tabpanel">
                                            <span id="show_activity_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="toll-pricebook" role="tabpanel">
                                            <span id="show_toll_pricebook_export"></span>
                                        </div>
                                        <div class="tab-pane fade" id="parking-pricebook" role="tabpanel">
                                            <span id="show_parking_pricebook_export"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- Footer -->
                    <?php include_once('public/__footer.php'); ?>
                    <!-- / Footer -->
                </div>
            </div>

        </div>
        <!-- / Layout page -->
    </div>
    <!-- / Layout wrapper -->

    <div id="spinner"></div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>
    <script src="assets/js/forms-tagify.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>

    <!-- endbuild -->
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/js/custom-common-script.js"></script>
    <script src="assets/js/easy-autocomplete.min.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <!-- Vendors JS -->
    <script src="assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>

    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src=" assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/js/bootstrap-datepicker.js"></script>
    <script src="assets/js/bootstrap-datepicker.min.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <script>
        $(document).ready(function() {

            loadTabContent('#home-pricebook');
            // Add click event listeners to the tab buttons
            $('button[data-bs-toggle="tab"]').on('click', function() {
                var tabId = $(this).data("bs-target"); // Get the data-bs-target attribute
                loadTabContent(tabId);
            });

            // Function to load content based on the selected tab
            function loadTabContent(tabId) {
                console.log('Loading content for tab:', tabId); // Debug statement
                switch (tabId) {
                    case '#home-pricebook':
                        show_EXPORT_LIST();
                        break;
                    case '#amenities-pricebook':
                        show_EXPORT_AMENITY_LIST();
                        break;
                    case '#vehicle-pricebook':
                        show_VEHICLE_PRICEBOOK_LIST();
                        break;
                    case '#guide-pricebook':
                        show_GUIDE_PRICEBOOK_LIST();
                        break;
                    case '#hotspot-pricebook':
                        show_HOTSPOT_PRICEBOOK_LIST();
                        break;
                    case '#activity-pricebook':
                        show_ACTIVITY_PRICEBOOK_LIST();
                        break;
                    case '#parking-pricebook':
                        show_PARKING_PRICEBOOK_LIST();
                        break;
                    case '#toll-pricebook':
                        show_TOLL_PRICEBOOK_LIST();
                        break;
                }
            }

            // AJAX functions
            function show_EXPORT_LIST() {

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_latest_hotelroom_list.php?type=show_form',
                    success: function(response) {
                        $('#show_hotel_pricebook_export').html(response);

                    }
                });
            }

            function show_EXPORT_AMENITY_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_hotelamenities_pricebook_latest.php?type=show_form',
                    success: function(response) {
                        $('#show_hotel_amenities_pricebook_export').html(response);
                    }
                });
            }

            function show_VEHICLE_PRICEBOOK_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_vehicle_price_export.php?type=show_form',
                    success: function(response) {
                        $('#show_vehicle_pricebook_export').html(response);
                    }
                });
            }

            function show_HOTSPOT_PRICEBOOK_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_hotspot_pricebook_latest.php?type=show_form',
                    success: function(response) {
                        $('#show_hotspot_pricebook_export').html(response);
                    }
                });
            }

            function show_ACTIVITY_PRICEBOOK_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_get_activity_pricebook_details.php?type=show_form',
                    success: function(response) {
                        $('#show_activity_pricebook_export').html(response);
                    }
                });
            }

            function show_PARKING_PRICEBOOK_LIST() {

                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_get_parking_pricebook_details.php?type=show_form',
                    success: function(response) {
                        $('#show_parking_pricebook_export').html(response);
                        spi
                    }
                });
            }

            function show_GUIDE_PRICEBOOK_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_guidepricebook_latest.php?type=show_form',
                    success: function(response) {
                        $('#show_guide_pricebook_export').html(response);
                    }
                });
            }

            function show_TOLL_PRICEBOOK_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_toll_pricebook_latest.php?type=show_form',
                    success: function(response) {
                        $('#show_toll_pricebook_export').html(response);
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', (event) => {
                var vehicleTab = document.querySelector('[data-bs-target="#vehicle-pricebook"]');
                vehicleTab.addEventListener('shown.bs.tab', function(e) {
                    show_VEHICLE_PRICEBOOK_LIST();
                });
            });
        });

        $(document).ready(function() {
            $('#hotel_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#amenities_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#vehicle_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#guide_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#activity_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
        });

        $(document).ready(function() {
            $('#hotel_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#amenities_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#vehicle_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#guide_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
            $('#activity_year').datepicker({
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });
        });

        $(document).ready(function() {
            $("select").selectize();
            $('#year').datepicker({
                format: "yyyy",
                startView: "years",
                minViewMode: "years",
                autoclose: true
            }).on('changeDate', function(e) {
                // Use Moment.js to format the selected date
                const formattedDate = moment(e.date).format('YYYY');
                $(this).val(formattedDate);
            });
        });

        function CHOOSEN_STATE_ADD() {
            var city_selectize = $("#hotel_city")[0].selectize;
            var STATE_ID = $('#hotel_state').val();
            // Get the response from the server.
            $.ajax({
                url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                type: "GET",
                success: function(response) {
                    // Append the response to the dropdown.
                    city_selectize.clear();
                    city_selectize.clearOptions();
                    city_selectize.addOption(response);
                }
            });
        }

        function show_room_for_hotel(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#roomTypeFilterDiv').html(response);
                }
            });
        }

        function show_category_for_the_hotel(TYPE, HOTEL_CAT_ID, CITY_ID) {
            if (HOTEL_CAT_ID == '') {
                var HOTEL_CAT_ID = $('#hotel_category').val();
            }
            if (CITY_ID == '') {
                var CITY_ID = $('#hotel_city').val();
            }
            if (HOTEL_CAT_ID != "" && CITY_ID != "") {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
                    data: {
                        ID: HOTEL_CAT_ID,
                        TYPE: TYPE,
                        CITY_ID: CITY_ID
                    },
                    success: function(response) {
                        $('#hotelDiv').html(response);
                    }
                });
            }
        }
    </script>

</body>

</html>