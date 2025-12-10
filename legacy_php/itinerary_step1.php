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
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
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

                        <!-- ITINERARY BASIC INFO START -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-4">
                                    <form id="form_itinerary_basicinfo" action="" method="post" data-parsley-validate>
                                        <div class="row g-3">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h4 class="font-weight-bold">Itinerary Plan</h4>
                                                <a class="btn btn-label-github btn-sm waves-effect waves-light pe-3" href="newitinerary.php"><i class="tf-icons ti ti-arrow-left ti-xs me-1"></i>Back To Itinerary List</a>
                                            </div>
                                            <div class="col-md-12 mb-3">
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
                                                    <select name="arrival_type" id="arrival_type" autocomplete="off" class="form-select form-control" required>
                                                        <?= getTRAVELTYPE($arrival_type, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="time">Departure Type<span class="text-danger">
                                                        *</span></label>
                                                <div class="form-group">
                                                    <select name="departure_type" id="departure_type" autocomplete="off" class="form-select form-control" required>
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
                                                    <select name="itinerary_type" id="itinerary_type" autocomplete="off" class="form-select form-control" required>
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
                                                    <select name="guide_for_itinerary" id="guide_for_itinerary" class="form-select form-control" required>
                                                        <?= get_YES_R_NO($guide_for_itinerary, 'select'); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="pick_up_date_and_time">Food Preferences<span class=" text-danger"> *</span></label>
                                                <div class="form-group">
                                                    <select name="food_type" id="food_type" autocomplete="off" class="form-select form-control" required>
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
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_breakfast" <?php if ($meal_plan_breakfast == 1) : echo 'checked';
                                                                                                                                                                        endif; ?> name="meal_plan_breakfast"><label class="form-check-label" for="meal_plan_breakfast">Breakfast</label></div>
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_lunch" <?php if ($meal_plan_lunch == 1) : echo 'checked';
                                                                                                                                                                    endif; ?> name="meal_plan_lunch"><label class="form-check-label" for="meal_plan_lunch">Lunch</label></div>
                                                    <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="meal_plan_dinner" <?php if ($meal_plan_dinner == 1) : echo 'checked';
                                                                                                                                                                    endif; ?> name="meal_plan_dinner"><label class="form-check-label" for="meal_plan_dinner">Dinner</label></div>
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
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ITINERARY BASIC INFO END -->

                        <!-- ITINERARY ROUTE START -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-check custom-option custom-option-icon border-0">
                                    <label class="form-check-label custom-option-content p-0" for="customRadioIcon1">
                                        <div class="nav-align-top nav-tabs-shadow mb-4">
                                            <ul class="nav nav-tabs" role="presentation">
                                                <li class="nav-item" role="presentation">
                                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#route-one" aria-controls="route-one" aria-selected="true">Route - 1</button>
                                                </li>
                                                <!-- <li class="nav-item" role="presentation">
                                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#route-two" aria-controls="route-two" aria-selected="false">Route - 2</button>
                                                </li> -->
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="route-one" role="tabpanel">
                                                    <div class="table-responsive">
                                                        <table id="example" class="table table-borderless" style="width:100%">
                                                            <thead class="table-header-color">
                                                                <tr>
                                                                    <th>DAY</th>
                                                                    <th>DATE</th>
                                                                    <th>SOURCE DESTINATION</th>
                                                                    <th>NEXT DESTINATION</th>
                                                                    <th>VIA ROUTE</th>
                                                                    <th style="width: 0;padding: 0px"></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="text-start">
                                                        <button type="button" class="btn btn-outline-dribbble btn-sm addNextDayPlan"><i class="ti ti-plus ti-tada-hover"></i>Add Day</button>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="route-two" role="tabpanel">

                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="button" class="btn btn-primary">Continue</button>
                        </div>
                        <!-- ITINERARY ROUTE END -->
                    </div>
                </div>
                <!-- Select Route Modal -->
                <div class="modal fade" id="addNewRoute" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-simple modal-add-new-address">
                        <div class="modal-content p-3 p-md-5">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mb-4">
                                    <h3 class="address-title mb-2"><i class="ti ti-map-2 ti-md rounded-circle scaleX-n1-rtl" style="color: #aa008e !important"></i> Via Route</h3>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                                <div>
                                    <h5 class="m-0 d-none d-sm-block"><i class="ti ti-current-location rounded-circle scaleX-n1-rtl px-2" style="color: #aa008e !important"></i>Chennai</h5>
                                    <p class="m-0 text-muted text-center mt-1" style="font-size: 12px;">Source Location</p>
                                </div>
                                <div>
                                    <h5 class="m-0 d-none d-sm-block"><i class="ti ti-map-pin rounded-circle scaleX-n1-rtl px-2" style="color: #aa008e !important"></i>Pondicherry</h5>
                                    <p class="m-0 text-muted text-center mt-1" style="font-size: 12px;">Next Destination</p>
                                </div>
                            </div>
                            <form id="addNewRouteForm" class="row g-3" onsubmit="return false">
                                <ul class="timeline mb-0 add-route-timeline" id="add-route-timeline">
                                    <li class="timeline-item timeline-item-transparent pb-3" style="margin-left: 3rem; padding-left: 1rem;">
                                        <div class="col-10 d-flex justify-content-center route align-items-center">
                                            <!-- <span class="timeline-point timeline-point-secondary-color remove-timeline" style="background-color: #28c76f !important"><i class="ti ti-location ti-tada-hover"></i></span> -->
                                            <span class="timeline-indicator-advanced timeline-indicator-primary"></span>
                                            <span class="timeline-indicator-advanced timeline-indicator-primary" style="top: 0.6rem;">
                                                <i class="ti ti-send rounded-circle scaleX-n1-rtl" style="color: #28c76f !important; background: #fff;"></i>
                                            </span>
                                            <div class="col-8">
                                                <select name="modalAddressCountry" class="select2 form-select" data-allow-clear="true">
                                                    <option value="">Select Next Location</option>
                                                    <option value="Australia">Australia</option>
                                                    <option value="Bangladesh">Bangladesh</option>
                                                    <option value="Belarus">Belarus</option>
                                                </select>
                                            </div>
                                            <div class="col-2 d-flex justify-content-evenly">
                                                <div class="col-2 text-center">
                                                    <button type="button" class="btn btn-primary btn-sm addRoute"><i class="ti ti-plus ti-tada-hover"></i></button>
                                                </div>
                                                <!-- <div class="col-2"></div> -->
                                            </div>
                                        </div>
                                    </li>
                                    <div class="routesContainer mt-0"></div>
                                </ul>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--/ Select Route Modal -->
            </div>
        </div>
    </div>
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
        flatpickr("#trip_start_date_and_time", {
            enableTime: true, // Enable time selection
            dateFormat: "d/m/Y H:i K", // Date and time format
            time_24hr: true // Use 24-hour time format
        });

        flatpickr("#trip_end_date_and_time", {
            enableTime: true, // Enable time selection
            dateFormat: "d/m/Y H:i K", // Date and time format
            time_24hr: true // Use 24-hour time format
        });

        flatpickr("#pick_up_date_and_time", {
            enableTime: true, // Enable time selection
            dateFormat: "d/m/Y H:i K", // Date and time format
            time_24hr: true // Use 24-hour time format
        });

        $(document).ready(function() {

            // ADD ROOM START

            function addRoom(adultCount, childCount, infantCount) {
                var roomCount = $('.room_count').length + 1;
                var newRoom = $('<div class="col-md-12 room_count mt-2 px-3" style="border-bottom: 1px dashed rgb(168, 170, 174);">' +
                    '<div class="d-flex justify-content-between">' +
                    '<h5 class="text-primary mb-0">#Room ' + roomCount + '</h5>' +
                    '<div><i class="ti ti-trash text-danger cursor-pointer pe-2 deleteRoom-itinerary"></i></div>' +
                    '</div>' +
                    '<div class="d-flex col-12 align-items-start">' +
                    '<div class="d-flex align-items-center py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addAdultBtn">+ Add</button>' +
                    '<div class="itinerary_quantity itinerary_quantityAdult" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_quantity" readonly type="text" class="itinerary_quantity__input itinerary_quantityadult" value="' + adultCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '<div class="ms-2 mt-2">' +
                    '<div for="traveller_age"> Adult <i class="ti ti-info-circle me-1"></i></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="d-flex align-items-center py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addchildrenBtn">+ Add</button>' +
                    '<div class="itinerary_quantity itinerary_quantityChildren" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_quantity" readonly type="text" class="itinerary_quantity__input itinerary_quantitychildren" value="' + childCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '<div class="ms-2 mt-2">' +
                    '<div for="traveller_age"> Child <i class="ti ti-info-circle me-1"></i></div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="d-none align-items-center py-2 children_increament_count_section me-4">' +
                    '<div class="children_increament_count g-3 d-flex align-items-center flex-column">' +
                    '</div>' +
                    '</div>' +
                    '<div class="d-flex align-items-center py-2 me-4 flex-column">' +
                    '<div>' +
                    '<button type="button" class="room-itinerary-btn btn-label-primary addinfantBtn">+ Add</button>' +
                    '<div class="itinerary_quantity itinerary_quantityInfant" style="display: none;">' +
                    '<a class="itinerary_quantity__minus"><span>-</span></a>' +
                    '<input name="itinerary_quantity" type="text" readonly class="itinerary_quantity__input itinerary_quantityinfant" value="' + infantCount + '">' +
                    '<a class="itinerary_quantity__plus"><span>+</span></a>' +
                    '</div>' +
                    '</div>' +
                    '<div class="ms-2 mt-2">' +
                    '<div for="traveller_age">Infant <i class="ti ti-info-circle me-1"></i></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>');

                $('#room_container').append(newRoom);
            }

            $('#addRoomBtn').click(function() {
                addRoom(1, 1, 1); // Pass initial values for adult, child, and infant counts
            });

            addRoom(1, 1, 1);

            // ADD ROOM END


            // REMOVE ROOM START

            $(document).on('click', '.deleteRoom-itinerary', function() {
                if ($(this).closest('.room_count').index() === 0) {
                    TOAST_NOTIFICATION('warning', 'Cannot delete the first room!', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                    return;
                }

                $(this).closest('.room_count').remove(); // Remove the closest room element

                // Adjust room titles
                $('.room_count').each(function(index) {
                    $(this).find('h5').text('#Room ' + (index + 1));
                });
            });

            // REMOVE ROOM END


            // ADULT SCRIPT START

            // Event listeners for showing adult, children, and infant counters
            $(document).on('click', '.addAdultBtn', function() {
                $(this).hide(); // Hide the add adult button
                var adultCounter = $(this).siblings('.itinerary_quantityAdult');
                adultCounter.show(); // Show the adult counter
                adultCounter.find('.itinerary_quantity__input').val(1); // Reset adult count to 1
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
                            updateChildrenFields($(this).closest('.room_count'), 3); // Update child fields with new count
                        }
                    } else if (adultValue === 2) {
                        childInputField.attr('max', 2);
                        if (childValue > 2) {
                            childInputField.val(2);
                            updateChildrenFields($(this).closest('.room_count'), 2); // Update child fields with new count
                        }
                    } else if (adultValue === 3) {
                        childInputField.attr('max', 1);
                        if (childValue > 1) {
                            childInputField.val(1);
                            updateChildrenFields($(this).closest('.room_count'), 1); // Update child fields with new count
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

                if (adultValue > 0) { // Minimum limit for adults
                    adultValue--;
                    inputField.val(adultValue);

                    if (adultValue === 0) {
                        $(this).closest('.room_count').find('.addAdultBtn').show(); // Show the "Add Adult" button
                        $(this).closest('.itinerary_quantityAdult').hide(); // Hide the adult counter
                    }

                    // Adjust maximum limit for children based on the adult count
                    if (adultValue === 1) {
                        childInputField.attr('max', 3);
                        if (childValue > 3) {
                            childInputField.val(3);
                            updateChildrenFields($(this).closest('.room_count'), 3); // Update child fields with new count
                        }
                    } else if (adultValue === 2) {
                        childInputField.attr('max', 2);
                        if (childValue > 2) {
                            childInputField.val(2);
                            updateChildrenFields($(this).closest('.room_count'), 2); // Update child fields with new count
                        }
                    } else if (adultValue === 3) {
                        childInputField.attr('max', 1);
                        if (childValue > 1) {
                            childInputField.val(1);
                            updateChildrenFields($(this).closest('.room_count'), 1); // Update child fields with new count
                        }
                    }
                }

                // Change cursor back to 'pointer' when not at maximum count
                if (adultValue < 3) {
                    $(this).siblings('.itinerary_quantity__plus').css('cursor', 'pointer');
                }
            });


            // ADULT SCRIPT END

            // CHILD SCRIPT END

            $(document).on('click', '.addchildrenBtn', function() {
                var parentRoom = $(this).closest('.room_count'); // Find the parent room
                var childCount = parseInt(parentRoom.find('.itinerary_quantityChildren .itinerary_quantity__input').val());
                var childSection = parentRoom.find('.children_increament_count_section');
                childSection.addClass('d-flex');
                childSection.removeClass('d-none');

                if (childCount === 1) {
                    $(this).hide(); // Hide the add children button
                    childSection.addClass('d-flex');
                    childSection.removeClass('d-none'); // Show the children increment count section
                    parentRoom.find('.itinerary_quantityChildren').show(); // Show the children counter
                    updateChildrenFields(parentRoom);
                } else if (childCount === 0) {
                    $(this).hide(); // Hide the add children button
                    var childCounter = $(this).siblings('.itinerary_quantityChildren');
                    childCounter.show(); // Show the children counter
                    childCounter.find('.itinerary_quantity__input').val(1); // Reset child count to 1
                } else {
                    $(this).hide(); // Hide the add children button
                    childSection.addClass('d-flex').removeClass('d-none'); // Show the children increment count section
                    parentRoom.find('.itinerary_quantityChildren').show(); // Show the children counter
                }

                if (childCount === 0) {
                    childSection.removeClass('d-flex').addClass('d-none'); // Hide the children increment count section
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

                if (value >= parseInt(inputField.attr('max'))) {
                    $(this).css('cursor', 'not-allowed');
                }

                // Insert new child section if the maximum count is reached
                if (value === parseInt(inputField.attr('max'))) {
                    var parentRoom = $(this).closest('.room_count');
                    var childrenSection = parentRoom.find('.children_increament_count_section').first(); // Find the first children increment section
                    var newSection = childrenSection.clone(); // Clone the children increment section
                    newSection.find('.itinerary_quantity__input').val(''); // Clear the input value
                    childrenSection.after(newSection); // Insert the cloned section after the current children increment section
                }

                updateChildrenFields($(this).closest('.room_count'));
            });

            $(document).on('click', '.itinerary_quantityChildren .itinerary_quantity__minus', function(e) {
                e.preventDefault();
                var inputField = $(this).siblings('.itinerary_quantity__input');
                var value = parseInt(inputField.val());

                if (value > 0) { // Minimum limit for children
                    value--;
                    inputField.val(value);
                    updateChildrenFields($(this).closest('.room_count'));
                    $(".children_increament_count_section").removeClass("d-none");
                    $(".children_increament_count_section").addClass("d-flex");
                }

                if (value === 0) {
                    $(this).closest('.room_count').find('.addchildrenBtn').show(); // Show the "Add Children" button
                    $(this).closest('.itinerary_quantityChildren').hide(); // Hide the children counter
                    $(".children_increament_count_section").addClass("d-none");
                    $(".children_increament_count_section").removeClass("d-flex");
                }

                // Change cursor back to 'pointer' when not at maximum count
                if (value < parseInt(inputField.attr('max'))) {
                    $(this).siblings('.itinerary_quantity__plus').css('cursor', 'pointer');
                    $(".children_increament_count_section").removeClass("d-none");
                    $(".children_increament_count_section").addClass("d-flex");
                }
            });

            function updateChildrenFields(parentRoom) {
                var count = parseInt(parentRoom.find('.itinerary_quantityChildren .itinerary_quantity__input').val());
                var childrenFieldContainer = parentRoom.find('.children_increament_count');
                var existingChildFields = childrenFieldContainer.find('.children_field').length;
                if (count > existingChildFields) {
                    // Add new children_field divs based on the difference between the count and existing fields
                    for (var i = existingChildFields + 1; i <= count; i++) {
                        // var childrenField = $('<div class="col-md-6 children_field"><label class="form-label" for="children_count">Children #' + i + '</label><div class="form-group"><input type="text" name="children_count" id="children_count_' + i + '" placeholder="Children Count" value="" required="" autocomplete="off" class="form-control"></div></div>');
                        // var childrenField = $('<div class="col-md-6 children_field"><label class="form-label" for="children_count">Children #' + i + '</label><div class="form-group"><input type="text" name="children_count" id="children_count_' + i + '" placeholder="Children Count" value="" required="" autocomplete="off" class="form-control"></div></div>');
                        var childrenField = $('<div class="children_field justify-content-center d-flex"><div class="input-group"><input type="text" style="width:80px;" name="children_count" id="children_count_' + i + '" value="" required="" autocomplete="off" class="form-control p-1"><button class="btn dropdown-toggle px-1 py-0" style="border: 1px solid #dee0ee;font-size: 12px;" type="button" data-bs-toggle="dropdown" aria-expanded="false">With Bed</button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0);">With Bed</a></li><li><a class="dropdown-item" href="javascript:void(0);">Without Bed</a></li></ul></div></div><div class="children_field text-center ms-2 mt-2 flex-column"><div for="children_count">Children #' + i + '</div></div>');
                        childrenFieldContainer.append(childrenField);
                    }
                } else if (count < existingChildFields) {
                    // Remove extra children_field divs based on the difference between the existing fields and the count
                    childrenFieldContainer.find('.children_field:gt(' + (count - 1) + ')').remove();
                }
            }

            // CHILD SCRIPT END

            // INFANT SCRIPT END

            $(document).on('click', '.addinfantBtn', function() {
                $(this).hide(); // Hide the add infant button
                var infantCounter = $(this).siblings('.itinerary_quantityInfant'); // Show the infant counter
                infantCounter.show(); // Show the adult counter
                infantCounter.find('.itinerary_quantity__input').val(1);
                $(".itinerary_quantityInfant").removeClass("d-none");
                $(".itinerary_quantityInfant").addClass("d-flex");
            });

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
                if (value === 0) {
                    $(this).closest('.room_count').find('.addinfantBtn').show(); // Show the "Add Adult" button
                    $(this).closest('.itinerary_quantityInfant').hide(); // Hide the adult counter
                    $(".itinerary_quantityInfant").addClass("d-none");
                    $(".itinerary_quantityInfant").removeClass("d-flex");
                }

            });

            // INFANT SCRIPT END

        });


        // VEHICLE ADD START

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

        // VEHICLE ADD END


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


        // STEP-2 SCRIPT START

        function addNewRow(button) {
            var table = document.getElementById("example");
            var lastRow = table.rows[table.rows.length - 1];
            var newRow = lastRow.cloneNode(true);
            var dayNumber = parseInt(newRow.cells[0].innerText.split(" ")[1]) + 1;
            newRow.cells[0].innerText = "DAY " + dayNumber;
            newRow.cells[1].innerText = "";
            newRow.cells[2].querySelector("input").value = "";
            newRow.cells[3].querySelector("input").value = "";
            table.querySelector("tbody").appendChild(newRow);
        }

        $(document).ready(function() {
            // Handle click event for all buttons with data-bs-toggle="modal"
            $('button[data-bs-toggle="modal"]').on('click', function() {
                // Get the target modal ID from data-bs-target attribute
                var modalId = $(this).attr('data-bs-target');
                // Open the modal with the specified ID
                $(modalId).modal('show');
            });
        });


        $(document).ready(function() {
            let sourceDestination = "Chennai";
            let nextDestination = "Pondicherry";
            let addedDays = {};

            $("tbody").on("click", ".addNextDayPlan", function() {
                let $row = $(this).closest("tr");
                let $nextDestinationInput = $row.find(".nextDestinationInput");
                let nextDestinationValue = $nextDestinationInput.val().trim();

                if (nextDestinationValue === "") {
                    $nextDestinationInput.addClass("is-invalid");
                    return;
                }

                $nextDestinationInput.removeClass("is-invalid");

                let day = parseInt($row.find(".day").text().split(" ")[1]);
                if (addedDays[day]) {
                    return; // Row already added for this day
                }

                addedDays[day] = true;

                let dateParts = $row.find(".date").text().split("/");
                let currentDate = new Date(parseInt(dateParts[2]), parseInt(dateParts[1]) - 1, parseInt(dateParts[0]));
                currentDate.setDate(currentDate.getDate() + 1);

                let newRow = `<tr>
                                <td class="day">DAY ${day + 1}</td>
                                <td class="date">${currentDate.toLocaleDateString('en-GB')}</td>
                                <td>
                                    <input type="text" class="form-control" value="${nextDestinationValue}" aria-describedby="defaultFormControlHelp">
                                </td>
                                <td>
                                    <input type="text" class="form-control nextDestinationInput" value="" aria-describedby="defaultFormControlHelp">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNewRoute"><i class="ti ti-route ti-tada-hover"></i></button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-outline-dribbble btn-sm addNextDayPlan"><i class="ti ti-plus ti-tada-hover"></i>Add</button>
                                </td>
                                <td>
                                    <i class="ti ti-x ti-md"></i>
                                </td>
                            </tr>`;

                $row.after(newRow);
            });

            $("tbody").on("input", ".nextDestinationInput", function() {
                $(this).removeClass("is-invalid");
            });
        });

        $(document).ready(function() {
            $(".addRoute").click(function() {
                $(".routesContainer").append(`
                    <li class="timeline-item timeline-item-transparent pb-3 route-list" style="margin-left: 3rem; padding-left: 1rem;">
                        <div class="col-10 d-flex justify-content-center route align-items-center">
                            <span class="timeline-indicator-advanced timeline-indicator-primary" style="top: 0.6rem;">
                                <i class="ti ti-send rounded-circle scaleX-n1-rtl" style="color: #28c76f !important; background: #fff;"></i>
                            </span>
                            <div class="col-8">
                                <select name="modalAddressCountry" class="select2 form-select" data-allow-clear="true">
                                    <option value="">Select Next Location</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Belarus">Belarus</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            <div class="col-2 d-flex justify-content-evenly">
                                <div class="col-2 text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm remove-route"><i class="ti ti-trash ti-tada-hover"></i></button>
                                </div>
                            </div>
                        </div>
                    </li>
                `);
            });

            $(".routesContainer").on("click", ".remove-route", function() {
                $(this).closest(".route-list").remove();
            });
        });
    </script>

    <script>
        var tripDay = 5;
        document.addEventListener('DOMContentLoaded', function() {
            for (let i = 1; i <= tripDay; i++) {
                addDayRow(i);
            }
        });

        document.querySelector('.addNextDayPlan').addEventListener('click', function() {
            let lastRow = document.querySelector('#example tbody tr:last-child');
            let lastDayNum = parseInt(lastRow.querySelector('.day').innerText.replace('DAY ', ''));
            let nextDayNum = lastDayNum + 1;

            addDayRow(nextDayNum);
        });

        function addDayRow(dayNum) {
            let today = new Date();
            today.setDate(today.getDate() + (dayNum - 1)); // Set date for each day

            let formattedDate = `${(today.getDate() < 10 ? '0' : '')}${today.getDate()}/${(today.getMonth() + 1 < 10 ? '0' : '')}${today.getMonth() + 1}/${today.getFullYear()}`;

            let newRow = `
                    <tr>
                        <td class="day">DAY ${dayNum}</td>
                        <td class="date">${formattedDate}</td>
                        <td>
                            <input type="text" class="form-control" value="" aria-describedby="defaultFormControlHelp">
                        </td>
                        <td>
                            <input type="text" class="form-control nextDestinationInput" value="" aria-describedby="defaultFormControlHelp">
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addNewRoute"><i class="ti ti-route ti-tada-hover"></i></button>
                        </td>
                        <td>${dayNum > tripDay ? '<i class="ti ti-x ti-danger ti-tada-hover ti-md deleteRow" style="color: #F32013; cursor: pointer;"></i>' : ''}</td>
                    </tr>
                `;

            document.querySelector('#example tbody').insertAdjacentHTML('beforeend', newRow);

            // Update day numbers for subsequent rows
            let rows = document.querySelectorAll('#example tbody tr');
            for (let i = dayNum; i < rows.length; i++) {
                rows[i].querySelector('.day').innerText = `DAY ${i + 1}`;
            }

            // Update dates for subsequent rows
            let currentDate = new Date(today.getTime());
            for (let i = dayNum; i < rows.length; i++) {
                currentDate.setDate(currentDate.getDate() + 1);
                let formattedDate = `${(currentDate.getDate() < 10 ? '0' : '')}${currentDate.getDate()}/${(currentDate.getMonth() + 1 < 10 ? '0' : '')}${currentDate.getMonth() + 1}/${currentDate.getFullYear()}`;
                rows[i].querySelector('.date').innerText = formattedDate;
            }

            // Add event listener to the delete icon of the new row
            if (dayNum > tripDay) {
                let deleteIcons = document.querySelectorAll('.deleteRow');
                deleteIcons[deleteIcons.length - 1].addEventListener('click', function() {
                    this.closest('tr').remove();
                    // Re-calculate day numbers and dates for remaining rows
                    rows = document.querySelectorAll('#example tbody tr');
                    for (let i = 0; i < rows.length; i++) {
                        rows[i].querySelector('.day').innerText = `DAY ${i + 1}`;
                        let newDate = new Date(today.getTime());
                        newDate.setDate(newDate.getDate() + i);
                        let formattedDate = `${(newDate.getDate() < 10 ? '0' : '')}${newDate.getDate()}/${(newDate.getMonth() + 1 < 10 ? '0' : '')}${newDate.getMonth() + 1}/${newDate.getFullYear()}`;
                        rows[i].querySelector('.date').innerText = formattedDate;
                    }
                });
            }
        }
    </script>


</body>

</html>