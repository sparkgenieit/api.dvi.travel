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
include_once 'jackus.php';
admin_reguser_protect();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

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
    <link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

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
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLEMAP_API_KEY; ?>&libraries=places"></script>

    <style>
        #loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            /* Semi-transparent white background */
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .itinerary_quantity {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .itinerary_quantity__minus,
        .itinerary_quantity__plus {
            display: block;
            width: 31px;
            height: 26px;
            margin: 0;
            background: #dee0ee;
            text-decoration: none;
            text-align: center;
            line-height: 23px;
            font-size: 23px;
            cursor: pointer;
        }

        .itinerary_quantity__minus {
            border-radius: 3px 0 0 3px;
        }

        .itinerary_quantity__plus {
            border-radius: 0 3px 3px 0;
        }

        .itinerary_quantity__input {
            width: 48px;
            height: 26px;
            margin: 0;
            padding: 0;
            text-align: center;
            border-top: 2px solid #dee0ee;
            border-bottom: 2px solid #dee0ee;
            border-left: 1px solid #dee0ee;
            border-right: 2px solid #dee0ee;
            background: #fff;
            color: #8184a1;
        }

        .itinerary_quantity__minus:link,
        .itinerary_quantity__plus:link {
            color: #8184a1;
        }

        .room-itinerary-btn {
            border: 1px solid #dee0ee;
            border-radius: 5px;
            padding: 4px 23px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            <?php include_once 'public/__sidebar.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Navbar -->
                <?php include_once 'public/__topbar.php'; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-4">
                                    <form id="form_itinerary_basicinfo" action="" method="post" data-parsley-validate>

                                        <div class="d-flex justify-content-between mb-3">
                                            <h4 class="font-weight-bold">Itinerary Plan</h4>
                                            <a class="btn btn-label-github waves-effect waves-light pe-3" href="newitinerary.php"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i>Back To Itinerary List</a>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="itinerary_prefrence">Itinerary Prefrence<span class=" text-danger"> *</span></label>
                                            <div class="form-group">
                                                <div class="form-check form-check-inline mt-2">
                                                    <input class="form-check-input" type="radio" id="itinerary_prefrence_1" name="itinerary_prefrence" value="1" required data-parsley-errors-container="#itinerary_prefrence_error" checked />
                                                    <label class="form-check-label" for="itinerary_prefrence_1">Hotel</label>
                                                </div>
                                                <div class="form-check form-check-inline mt-2">
                                                    <input class="form-check-input" type="radio" id="itinerary_prefrence_2" name="itinerary_prefrence" value="2" required data-parsley-errors-container="#itinerary_prefrence_error">
                                                    <label class="form-check-label" for="itinerary_prefrence_2">Vehicle</label>
                                                </div>
                                                <div class="form-check form-check-inline mt-2">
                                                    <input class="form-check-input" type="radio" id="itinerary_prefrence_3" name="itinerary_prefrence" value="3" required data-parsley-errors-container="#itinerary_prefrence_error">
                                                    <label class="form-check-label" for="itinerary_prefrence_3">Both Hotel and Vehicle</label>
                                                </div>
                                            </div>
                                            <div id="itinerary_prefrence_error"></div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="arrival_location">Arrival<span class="text-danger"> *</span></label>
                                                    <input id="arrival_location" name="arrival_location" class="form-control" type="text" placeholder="Select Arrival" required value="<?= $arrival_location; ?>">
                                                    <input type="hidden" class="form-control" name="location_id" id="location_id" hidden value="<?= $location_id ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="departure_location">Departure<span class="text-danger"> *</span></label>
                                                    <input id="departure_location" name="departure_location" class="form-control" type="text" placeholder="Select Departure" required value="<?= $departure_location; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="trip_start_date_and_time">Trip Start Date & Time<span class=" text-danger"> *</span></label>
                                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_start_date_and_time" name="trip_start_date_and_time" value="<?= $trip_start_date_and_time ?>" required />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="trip_end_date_and_time">Trip End Date & Time<span class=" text-danger"> *</span></label>
                                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="trip_end_date_and_time" name="trip_end_date_and_time" required value="<?= $trip_end_date_and_time ?>" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="time">Arrival Type<span class=" text-danger">*</span></label>
                                                <div class="form-group">
                                                    <select name="arrival_type" id="arrival_type" autocomplete="off" class="form-control" required>
                                                        <?= getTRAVELTYPE($arrival_type, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="time">Departure Type<span class="text-danger">
                                                        *</span></label>
                                                <div class="form-group">
                                                    <select name="departure_type" id="departure_type" autocomplete="off" class="form-control" required>
                                                        <?= getTRAVELTYPE($departure_type, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label" for="no_of_nights">Number of Nights</label>
                                                <input type="text" class="form-control bg-body" id="no_of_nights" name="no_of_nights" value="<?= (!empty($no_of_nights)) ? $no_of_nights : "0" ?>" readonly>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label" for="no_of_days">Number of Days</label>
                                                <input type="text" class="form-control bg-body" id="no_of_days" name="no_of_days" value="<?= (!empty($no_of_days)) ? $no_of_days : "0" ?>" readonly>
                                            </div>

                                            <div class=" col-md-3">
                                                <label class="form-label" for="expecting_budget">Budget<span class=" text-danger">
                                                        *</span></label>
                                                <div class="form-group">
                                                    <input type="text" name="expecting_budget" id="expecting_budget" placeholder="Enter Budget" value="<?= $expecting_budget ?>" required autocomplete="off" class="form-control" />
                                                </div>
                                            </div>
                                            <div class="col-md-3" id="times">
                                                <label class="form-label" for="time">Itinerary Type<span class=" text-danger">
                                                        *</span></label>
                                                <div class="form-group">
                                                    <select name="itinerary_type" id="itinerary_type" autocomplete="off" class="form-control" required>
                                                        <?= get_ITINERARY_TYPE($itinerary_type, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" id="addRoomBtn" class="btn btn-primary">+ Add Rooms</button>
                                            </div>
                                            <div class="col-md-12">
                                                <!-- Existing room columns will be appended here -->
                                                <div class="card shadow-none bg-transparent border border-primary border-dashed p-4 pt-1 ps-3">
                                                    <div class="row" id="room_container">
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label class="form-label" for="guide_for_itinerary">Guide Required for Whole Itineary<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select name="guide_for_itinerary" id="guide_for_itinerary" class="form-control" required>
                                                        <?= get_YES_R_NO($guide_for_itinerary, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="pick_up_date_and_time">Food Preferences<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select name="food_type" id="food_type" autocomplete="off" class="form-control" required>
                                                        <?= getFOODTYPE($food_type, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="nationality"> Nationality <span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select name="nationality" id="nationality" autocomplete="off" class="form-control form-select" required>
                                                        <?= getCOUNTRY_LIST($nationality, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-4 col-xl-4 col-12" id="meal_plan_checkbox">
                                                <label class="form-label" for="meal_plan">Meal Plan<span class=" text-danger"> *</span></label>
                                                <div class="form-group mt-2">
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_breakfast" <?php if ($meal_plan_breakfast == 1) : echo 'checked'; endif; ?> name="meal_plan_breakfast"><label class="form-check-label" for="meal_plan_breakfast">Breakfast</label></div>
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_lunch" <?php if ($meal_plan_lunch == 1) : echo 'checked';endif; ?> name="meal_plan_lunch"><label class="form-check-label" for="meal_plan_lunch">Lunch</label></div>
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_dinner" <?php if ($meal_plan_dinner == 1) : echo 'checked'; endif; ?> name="meal_plan_dinner"><label class="form-check-label" for="meal_plan_dinner">Dinner</label></div>
                                                </div>
                                            </div>

                                            <div id="vehicle_pick_up_date_and_time" class="col-md-3">
                                                <label class="form-label" for="pick_up_date_and_time">Pick Up Date & Time<span class=" text-danger"> *</span></label>
                                                <input type="text" class="form-control" placeholder="DD/MM/YYYY HH:MM" id="pick_up_date_and_time" name="pick_up_date_and_time" value="" />
                                            </div>

                                            <div id="special_instructions" class="col-md-5">
                                                <label class="form-label" for="special_instructions">Special Instructions<span class=" text-danger"> </span></label>
                                                <textarea id="special_instructions" name="special_instructions" class="form-control" placeholder="Enter the Special Instruction" rows="3"><?= $special_instructions; ?></textarea>
                                            </div>

                                            <div class="col-md-12" id="vehicle_type_select">
                                                <div class=" d-flex justify-content-between align-items-center mt-3">
                                                    <h5 class="text-uppercase m-0 fw-bold">Vehicle</h5>
                                                </div>
                                            </div>

                                            <div class="col-md-12" id="vehicle_type_select_multiple">
                                                <div class="row g-3" id="show_item">
                                                    <div class="col-6 pb-2 vehicle_col pe-3 ps-3" id="vehicle_1">
                                                        <h6 class="heading_count_vehicle_type m-0">
                                                            Vehicle #1 </h6>
                                                        <div class="row align-items-end mt-2">
                                                            <div class="col">
                                                                <label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                                                <select id="vehicle_type" name="vehicle_type[]" class="form-control form-select">
                                                                    <?= getVEHICLETYPE_DETAILS($vehicle_type_id, 'select'); ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="form-label" for="vehicle_count">Vehicle Count<span class=" text-danger">
                                                                        *</span></label>
                                                                <div class="form-group">
                                                                    <input type="text" name="vehicle_count[]" id="vehicle_count" placeholder="Enter Vehicle Count" data-parsley-type="digits" min="1" value="1" required="" autocomplete="off" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-auto d-flex align-items-center mb-0">
                                                                <button type="button" class="btn btn-icon btn-danger waves-effect waves-light remove_btn" onclick="removeVehicle(this)">
                                                                    <i class=" ti ti-trash ti-xs"></i>
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mt-3">
                                                    <button type="button" class="btn btn-link rounded-pill waves-effect add_item_btn p-0 text-primary" onclick="addVehicle()">
                                                        <span class="tf-icons ti ti-circle-plus ti-xs me-1"></span> Add Vehicle
                                                    </button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

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
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/vendor/js/bootstrap.min.js"></script>
    <script src="assets/vendor/js/popper.min.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/app-logistics-fleet.js"></script>
    <script src="assets/js/app-chat.js"></script>
    <script src="assets/js/forms-tagify.js"></script>

    <!-- Phone Swiper -->
    <script src="assets/vendor/libs/sortablejs/sortable.js"></script>
    <script src="assets/vendor/libs/swiper/swiper.js"></script>
    <!-- Phone Swiper -->

    <!-- Sticky -->
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="assets/js/form-layouts.js"></script>

    <script src="assets/vendor/js/poppers.min.js"></script>
    <script src="assets/vendor/js/bootstrap.min.js"></script>
    <!-- Sticky -->

    <script src="assets/js/ui-carousel.js"></script>
    <script src="assets/js/extended-ui-drag-and-drop.js"></script>

    <script>
        $(document).ready(function() {

            // Function to add a room
            function addRoom() {
                var roomCount = $('.room_count').length + 1;
                var newRoom = $('<div class="col-md-4 room_count mt-4 px-3" style="border-right: 1px dashed rgb(168, 170, 174);">' +
        '<div class="d-flex justify-content-between">' +
        '<h5 class="text-primary mb-2">#Room ' + roomCount + '</h5>' +
        '<div>' +
        '<i class="ti ti-trash text-danger cursor-pointer pe-2 deleteRoom-itinerary"></i>' +
        '</div>' +
        '</div>' +
        '<div class="row d-flex align-items-center py-2">' +
        '<div class="col-6">' +
        '<div for="traveller_age"> Adult -</div>' +
        '<small><i class="ti ti-info-circle"></i> Age 11 or above</small>' +
        '</div>' +
        '<div class="col-6">' +
        '<button type="button" class="room-itinerary-btn btn-label-primary ms-4 addAdultBtn">+ Add Adult</button>' +
        '<div class="itinerary_quantity itinerary_quantityAdult" style="display: none;">' +
        '<a class="itinerary_quantity__minus"><span>-</span></a>' +
        '<input name="itinerary_quantity" readonly type="text" class="itinerary_quantity__input itinerary_quantityadult" value="1">' +
        '<a class="itinerary_quantity__plus"><span>+</span></a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row d-flex align-items-center py-2">' +
        '<div class="col-6">' +
        '<div for="traveller_age"> Child-</div>' +
        '<small><i class="ti ti-info-circle"></i>Above 5 below 10</small>' +
        '</div>' +
        '<div class="col-6">' +
        '<button type="button" class="room-itinerary-btn btn-label-primary ms-4 addchildrenBtn">+ Add Child</button>' +
        '<div class="itinerary_quantity itinerary_quantityChildren" style="display: none;">' +
        '<a class="itinerary_quantity__minus"><span>-</span></a>' +
        '<input name="itinerary_quantity" readonly type="text" class="itinerary_quantity__input itinerary_quantitychildren" value="1">' +
        '<a class="itinerary_quantity__plus"><span>+</span></a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row d-flex align-items-center py-2">' +
        '<div class="col-6">' +
        '<div for="traveller_age"> Infant -</div>' +
        '<small><i class="ti ti-info-circle"></i>Age 0 - 5</small>' +
        '</div>' +
        '<div class="col-6">' +
        '<button type="button" class="room-itinerary-btn btn-label-primary ms-4 addinfantBtn">+ Add Infant</button>' +
        '<div class="itinerary_quantity itinerary_quantityInfant" style="display: none;">' +
        '<a class="itinerary_quantity__minus"><span>-</span></a>' +
        '<input name="itinerary_quantity" type="text" readonly class="itinerary_quantity__input itinerary_quantityinfant" value="0">' +
        '<a class="itinerary_quantity__plus"><span>+</span></a>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="row mt-2 children_increament_count">' +
        '</div>' +
        '</div>');

    $('#room_container').append(newRoom);

    // Check if the room count is a multiple of 3 and remove the border style if true
    if (roomCount % 3 === 0) {
        newRoom.css('border-right', 'none');
    }
}

            // Event listener for "Add Room" button
            $('#addRoomBtn').click(function() {
                addRoom();
            });

            // Initially add a room
            addRoom();

            // Event listener for deleting a room
            $(document).on('click', '.deleteRoom-itinerary', function() {
                $(this).closest('.room_count').remove(); // Remove the closest room element
            });

            // Event listeners for showing adult, children, and infant counters
            $(document).on('click', '.addAdultBtn', function() {
                $(this).hide(); // Hide the add adult button
                $(this).siblings('.itinerary_quantityAdult').show(); // Show the adult counter
            });

            $(document).on('click', '.addchildrenBtn', function() {
                var parentRoom = $(this).closest('.room_count'); // Find the parent room
                var childCount = parseInt(parentRoom.find('.itinerary_quantityChildren .itinerary_quantity__input').val());
                if (childCount === 1) {
                    $(this).hide(); // Hide the add children button
                    var parentRoom = $(this).closest('.room_count'); // Find the parent room
                    parentRoom.find('.itinerary_quantityChildren').show(); // Show the children counter
                    updateChildrenFields(parentRoom);
                } else {
                    $(this).hide(); // Hide the add children button
                    var parentRoom = $(this).closest('.room_count'); // Find the parent room
                    parentRoom.find('.itinerary_quantityChildren').show(); // Show the children counter
                }
            });

            $(document).on('click', '.addinfantBtn', function() {
                $(this).hide(); // Hide the add infant button
                $(this).siblings('.itinerary_quantityInfant').show(); // Show the infant counter
            });

            // itinerary_quantity increment and decrement functionality for adults
            $(document).on('click', '.itinerary_quantityAdult .itinerary_quantity__plus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var adultValue = parseInt(inputField.val());
                var childInputField = $(this).closest('.room_count').find('.itinerary_quantityChildren .itinerary_quantity__input');
                var childValue = parseInt(childInputField.val());

                if (adultValue < 3) { // Maximum limit for adults
                    adultValue++;
                    inputField.val(adultValue);

                    // Adjust maximum limit for children based on the adult count
                    if (adultValue === 1) {
                        childInputField.attr('max', 3);
                        if (childValue > 3) {
                            childInputField.val(3);
                        }
                    } else if (adultValue === 2) {
                        childInputField.attr('max', 2);
                        if (childValue > 2) {
                            childInputField.val(2);
                        }
                    } else if (adultValue === 3) {
                        childInputField.attr('max', 1);
                        if (childValue > 1) {
                            childInputField.val(1);
                        }
                    }
                }

                // Change cursor to 'not-allowed' if maximum count reached
                if (adultValue >= 3) {
                    $(this).css('cursor', 'not-allowed');
                }
            });

            $(document).on('click', '.itinerary_quantityAdult .itinerary_quantity__minus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var adultValue = parseInt(inputField.val());
                var childInputField = $(this).closest('.room_count').find('.itinerary_quantityChildren .itinerary_quantity__input');
                var childValue = parseInt(childInputField.val());

                if (adultValue > 1) { // Minimum limit for adults
                    adultValue--;
                    inputField.val(adultValue);

                    // Adjust maximum limit for children based on the adult count
                    if (adultValue === 1) {
                        childInputField.attr('max', 3);
                        if (childValue > 3) {
                            childInputField.val(3);
                        }
                    } else if (adultValue === 2) {
                        childInputField.attr('max', 2);
                        if (childValue > 2) {
                            childInputField.val(2);
                        }
                    } else if (adultValue === 3) {
                        childInputField.attr('max', 1);
                        if (childValue > 1) {
                            childInputField.val(1);
                        }
                    }
                }

                // Change cursor back to 'pointer' when not at maximum count
                if (adultValue < 3) {
                    $(this).siblings('.itinerary_quantity__plus').css('cursor', 'pointer');
                }
            });

            // itinerary_quantity increment and decrement functionality for children
            $(document).on('click', '.itinerary_quantityChildren .itinerary_quantity__plus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var value = parseInt(inputField.val());
                if (value < parseInt(inputField.attr('max'))) { // Maximum limit for children
                    value++;
                    inputField.val(value);
                }
                updateChildrenFields($(this).closest('.room_count'));

                // Change cursor to 'not-allowed' if maximum count reached
                if (value >= parseInt(inputField.attr('max'))) {
                    $(this).css('cursor', 'not-allowed');
                }
            });

            $(document).on('click', '.itinerary_quantityChildren .itinerary_quantity__minus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var value = parseInt(inputField.val());
                if (value > 0) { // Minimum limit for children
                    value--;
                    inputField.val(value);
                    updateChildrenFields($(this).closest('.room_count'));
                }

                // Change cursor back to 'pointer' when not at maximum count
                if (value < parseInt(inputField.attr('max'))) {
                    $(this).siblings('.itinerary_quantity__plus').css('cursor', 'pointer');
                }
            });


            // Function to update children fields within the specific room
            function updateChildrenFields(parentRoom) {
                var count = parseInt(parentRoom.find('.itinerary_quantityChildren .itinerary_quantity__input').val());
                var childrenFieldContainer = parentRoom.find('.children_increament_count');
                childrenFieldContainer.empty();

                // Add new children_field divs based on the count
                for (var i = 1; i <= count; i++) {
                    var childrenField = $('<div class="col-md-6 children_field"><label class="form-label" for="children_count">Children #' + i + '</label><div class="form-group"><input type="text" name="children_count" id="children_count_' + i + '" placeholder="Children Count" value="" required="" autocomplete="off" class="form-control"></div></div>');
                    childrenFieldContainer.append(childrenField);
                }
            }

            // itinerary_quantity increment and decrement functionality for infants
            $(document).on('click', '.itinerary_quantityInfant .itinerary_quantity__plus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var value = parseInt(inputField.val());
                value++;
                inputField.val(value);
            });

            $(document).on('click', '.itinerary_quantityInfant .itinerary_quantity__minus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var value = parseInt(inputField.val());
                if (value > 0) {
                    value--;
                    inputField.val(value);
                }
            });

        });

        function addVehicle() {
            // Clone the first vehicle column
            var vehicleClone = document.querySelector('#vehicle_1').cloneNode(true);

            // Find the count of existing vehicle columns
            var vehicleCount = document.querySelectorAll('.vehicle_col').length;

            // Update IDs and other attributes as needed
            vehicleClone.id = 'vehicle_' + (vehicleCount + 1);
            vehicleClone.querySelector('#vehicle_type').id = 'vehicle_type_' + (vehicleCount + 1);
            vehicleClone.querySelector('#vehicle_count').id = 'vehicle_count_' + (vehicleCount + 1);
            // Update other IDs or attributes here

            // Update the vehicle label based on the count
            vehicleClone.querySelector('.heading_count_vehicle_type').textContent = 'Vehicle #' + (vehicleCount + 1);

            // Attach the removeVehicle function to the remove button
            vehicleClone.querySelector('.remove_btn').onclick = function() {
                removeVehicle(this);
            };

            // Add style directly to the cloned column
            if ((vehicleCount + 1) % 2 === 0) {
                vehicleClone.style.borderLeft = "1px dashed #a8aaae";
            }

            // Append the cloned vehicle column to the parent container
            document.querySelector('#show_item').appendChild(vehicleClone);
        }

        function removeVehicle(buttonElement) {
            var vehicleToRemove = buttonElement.closest('.vehicle_col');
            vehicleToRemove.parentNode.removeChild(vehicleToRemove);
        }


        function toggleVehicleSection() {
            var vehicleSection = document.getElementById("vehicle_type_select");
            var vehicleTypeSelect = document.getElementById("vehicle_type_select_multiple");
            var vehiclepickupdate = document.getElementById("vehicle_pick_up_date_and_time");
            var hotelRadio = document.getElementById("itinerary_prefrence_1");
            var vehicleRadio = document.getElementById("itinerary_prefrence_2");
            var bothRadio = document.getElementById("itinerary_prefrence_3");

            if (vehicleRadio.checked || bothRadio.checked) {
                vehicleSection.style.display = "block";
                vehicleTypeSelect.style.display = "block";
                vehiclepickupdate.style.display = "block";
            } else {
                vehicleSection.style.display = "none";
                vehicleTypeSelect.style.display = "none";
                vehiclepickupdate.style.display = "none";
            }
        }

        // Call the function initially to set the visibility correctly based on the initially checked radio button
        toggleVehicleSection();

        // Add event listeners to each radio button to trigger the function when the selection changes
        var radioButtons = document.getElementsByName("itinerary_prefrence");
        radioButtons.forEach(function(radio) {
            radio.addEventListener("change", toggleVehicleSection);
        });
    </script>

</body>

</html>