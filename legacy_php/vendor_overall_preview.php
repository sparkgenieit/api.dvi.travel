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

$vendor_ID = $_GET['id'];


if ($vendor_ID != "" && $_GET['route'] == "preview") :
    $select_vendor = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `vendor_code`, `vendor_email`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_country`, `vendor_state`, `vendor_city`, `vendor_pincode`, `vendor_othernumber`, `vendor_address`, `vendor_gstin_number`, `vendor_pan_number`, `vendor_gst_percentage`, `gst_country`, `gst_state`, `gst_city`, `gst_pincode`, `gst_address`,`status` FROM `dvi_vendor_details` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
    while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor)) :
        $vendor_id = $fetch_data['vendor_id'];
        $vendor_name = $fetch_data['vendor_name'];
        $vendor_code = $fetch_data['vendor_code'];
        $vendor_email = $fetch_data['vendor_email'];
        $vendor_primary_mobile_number = $fetch_data['vendor_primary_mobile_number'];
        $vendor_alternative_mobile_number = $fetch_data['vendor_alternative_mobile_number'];
        $vendor_country = $fetch_data['vendor_country'];
        $vendor_state = $fetch_data['vendor_state'];
        $vendor_city = $fetch_data['vendor_city'];
        $vendor_pincode = $fetch_data['vendor_pincode'];
        $vendor_othernumber = $fetch_data['vendor_othernumber'];
        $vendor_address = $fetch_data['vendor_address'];
        $vendor_gstin_number = $fetch_data['vendor_gstin_number'];
        $vendor_pan_number = $fetch_data['vendor_pan_number'];
        $vendor_gst_percentage = $fetch_data['vendor_gst_percentage'];
        $gst_country = $fetch_data['gst_country'];
        $gst_state = $fetch_data['gst_state'];
        $gst_city = $fetch_data['gst_city'];
        $gst_pincode = $fetch_data['gst_pincode'];
        $gst_address = $fetch_data['gst_address'];
        $status = $fetch_data['status'];
    endwhile;

    if ($status == 1) :
        $status = 'Active';
        $status_color = 'text-success';
    else :
        $status = 'In Active';
        $status_color = 'text-danger';
    endif;
endif;
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
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <!-- Page CSS -->

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="assets/vendor/libs/plyr/plyr.css" />

    <!-- Page CSS -->

    <link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>

</head>

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
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>

                        <!-- User Content -->
                        <div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1">
                            <ul class="nav p-2 nav-pills card-header-pills " role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button type="button" class="nav-link active shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#basic_info" aria-controls="basic_info" aria-selected="true" fdprocessedid="pg55hh">Basic Info</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#branch_details" aria-controls="branch_details" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Branch Details</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#vehicle" aria-controls="vehicle" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Vehicle</button>
                                </li>
                                <li class="nav-item mx-2" role="presentation">
                                    <button type="button" class="nav-link shadow-none hotel_overall_preview_tap" role="tab" data-bs-toggle="tab" data-bs-target="#permitcost" aria-controls="permitcost" aria-selected="false" fdprocessedid="dxymu" tabindex="-1">Permit Cost</button>
                                </li>
                            </ul>

                        </div>
                        <div class="">
                            <div class="tab-content p-0" id="pills-tabContent">
                                <div class="tab-pane card p-4 mb-3 fade show active" id="basic_info" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <div>
                                        <h5 class="text-primary my-1">Basic Details</h5>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label>Vendor Name</label>
                                            <p class="disble-stepper-title"><?= $vendor_name; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Email ID</label>
                                            <p class="disble-stepper-title"><?= $vendor_email; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Primary Mobile Number</label>
                                            <p class="disble-stepper-title"><?= $vendor_primary_mobile_number; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Alternative Mobile Number</label>
                                            <p class="disble-stepper-title"><?= $vendor_alternative_mobile_number; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Country</label>
                                            <p class="disble-stepper-title"><?= getCOUNTRYLIST($vendor_country, 'country_label'); ?></p>
                                        </div>

                                        <div class="col-md-3">
                                            <label>State</label>
                                            <p class="disble-stepper-title"><?= getSTATELIST('', $vendor_state, 'state_label'); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>City</label>
                                            <p class="disble-stepper-title"><?= getCITYLIST('', $vendor_city, 'city_label'); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Pincode</label>
                                            <p class="disble-stepper-title"><?= $vendor_pincode; ?></p>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Other Number</label>
                                            <p class="disble-stepper-title"><?= $vendor_othernumber; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Status</label>
                                            <p class="<?= $status_color; ?> fw-bold"><?= $status ?></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Address</label>
                                            <p class="disble-stepper-title"><?= $vendor_address; ?></p>
                                        </div>
                                    </div>
                                    <?php if ($vendor_gstin_number != '') : ?>
                                        <div class="divider">
                                            <div class="divider-text text-secondary">
                                                <i class="ti ti-star"></i>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div>
                                                <h5 class="text-primary mt-1 mb-3">GST Details</h5>
                                            </div>
                                            <div class="col-md-3">
                                                <label>GSTIN Number</label>
                                                <p class="disble-stepper-title"><?= $vendor_gstin_number; ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>PAN Number</label>
                                                <p class="disble-stepper-title"><?= $vendor_pan_number; ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>GST Percentage</label>
                                                <p class="disble-stepper-title"><?= getGSTDETAILS($vendor_gst_percentage, 'label'); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Country</label>
                                                <p class="disble-stepper-title"><?= getCOUNTRYLIST($gst_country, 'country_label'); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>State</label>
                                                <p class="disble-stepper-title"><?= getSTATELIST('', $gst_state, 'state_label'); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>City</label>
                                                <p class="disble-stepper-title"><?= getCITYLIST('', $gst_city, 'city_label'); ?></p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Pincode</label>
                                                <p class="disble-stepper-title"><?= $gst_pincode; ?></p>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Address</label>
                                                <p class="disble-stepper-title"><?= $gst_address; ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="tab-pane card p-4 mb-3 fade " id="branch_details" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <?php
                                    $select_vendor_branch = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="text-primary mb-0">Branch Details</h5>

                                        <div class="d-flex align-items-center">
                                            <p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
                                            <select class="form-select form-select-sm" name="choose_branch" id="choose_branch" onchange="change_choose_branch()" data-parsley-trigger="keyup">
                                                <?php
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
                                                    $vendor_branch_id = $fetch_data['vendor_branch_id'];
                                                     $vendor_branch_name = $fetch_data['vendor_branch_name'];
                                                    echo "<option value='$vendor_branch_id'>$vendor_branch_name</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <span id="branch_perview"></span>

                                </div>

                                <div class="tab-pane card p-4 mb-3 fade" id="vehicle" role="tabpanel" aria-labelledby="pills-contact-tab">

                                    <?php
                                    $select_vendor_branch = sqlQUERY_LABEL(
                                        "SELECT `vehicle_id`, `vehicle_type_id`, `vendor_branch_id` FROM `dvi_vehicle` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0'"
                                    ) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="text-primary my-1">Vehicle Details</h5>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
                                            <select class="form-select form-select-sm" name="choose_vehicle" id="choose_vehicle" onchange="change_choose_vehicle()" data-parsley-trigger="keyup">
                                                <?php
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
                                                    $vehicle_id = $fetch_data['vehicle_id'];
                                                    $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                                    $vendor_branch_name = getBranchLIST($fetch_data['vendor_branch_id'], 'branch_label');
                                                    $vehicle_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                    echo "<option value='$vehicle_id'>$vehicle_name - $vendor_branch_name </option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <span id="vehicle_perview"></span>

                                </div>

                                <div class="tab-pane fade card p-4 mb-3" id="permitcost" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <?php
                                    $select_vendor_branch = sqlQUERY_LABEL(
                                        "SELECT `vehicle_type_id` FROM `dvi_permit_cost` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0' GROUP BY `vehicle_type_id` "
                                    ) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                    $permitcost_num = sqlNUMOFROW_LABEL($select_vendor_branch);
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="text-primary my-1">Permit Cost Details</h5>
                                        <?php if ($permitcost_num > 0) : ?>
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
                                                <select class="form-select form-select-sm" name="choose_permitcost" id="choose_permitcost" onchange="change_choose_permitcost()">
                                                    <?php
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
                                                        $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                                        $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                        echo "<option value='$vehicle_type_id'>$vehicle_type_name</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <span id="permitcost_perview"></span>

                                </div>

                            </div>


                            <!-- Galley Modal -->
                            <div id="myModal" class="modal room-details-modal">
                                <span class="close room-details-close cursor" onclick="closeModal()">&times;</span>
                                <a class="prev room-details-prev mx-3" onclick="plusSlides(-1)">&#10094;</a>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/interior (1).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/interior (2).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/interior (3).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/interior (4).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/interior (5).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/exterior (1).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/exterior (2).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>
                                <div class="room-details-slides">
                                    <div class="d-flex justify-content-center mt-5">
                                        <img src=".head/assets/img/exterior (3).jpg" class="rounded" width="" height="700px">
                                    </div>
                                </div>

                                <a class="next room-details-next mx-3" onclick="plusSlides(1)">&#10095;</a>
                            </div>
                        </div>
                        <!-- Galley Modal -->
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
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
    <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>

    <!-- CALENDAR JS -->
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/vendor/libs/select2/select2.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/modal-edit-user.js"></script>
    <script src="assets/js/app-user-view.js"></script>
    <script src="assets/js/app-user-view-account.js"></script>
    <!-- Vendors JS -->

    <script src="assets/vendor/libs/swiper/swiper.js"></script>
    <script src="assets/vendor/libs/plyr/plyr.js"></script>
    <script src="assets/js/extended-ui-media-player.js"></script>



    <!-- Page JS -->
    <script src="assets/js/app-calendar-events.js"></script>
    <script src="assets/js/app-calendar.js"></script>
    <script src="assets/js/ui-carousel.js"></script>
    <script>
        // branch script start
        function choose_branch() {
            var choose_branch = $('#choose_branch').val();
            var vendor_id = <?= $vendor_id; ?>;


            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_vendor_branchpreview.php?type=vendor_vehicle',
                data: {
                    branch_id: choose_branch,
                    vendor_id: vendor_id
                },
                success: function(response) {
                    // $('#add_vendor').hide();
                    $('#branch_perview').html(response);
                }
            });
        }
        $(document).ready(function() {
            choose_branch();
        });
        // branch script end

        // vehicle script start
        function choose_vehicle() {
            var choose_vehicle = $('#choose_vehicle').val();
            var vendor_id = <?= $vendor_id; ?>;


            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_vendor_vehiclepreview.php?type=vendor_vehicle',
                data: {
                    vehicle_id: choose_vehicle,
                    vendor_id: vendor_id
                },
                success: function(response) {
                    // $('#add_vendor').hide();
                    $('#vehicle_perview').html(response);
                }
            });
        }
        $(document).ready(function() {
            choose_vehicle();
        });
        // vehicle script end

        // vehicle script start
        function choose_permitcost() {
            var choose_permitcost = $('#choose_permitcost').val();
            var vendor_id = <?= $vendor_id; ?>;

            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_vendor_permitcostpreview.php?type=vendor_vehicle',
                data: {
                    vehicle_type_id: choose_permitcost,
                    vendor_id: vendor_id
                },
                success: function(response) {
                    // $('#add_vendor').hide();
                    $('#permitcost_perview').html(response);
                }
            });
        }
        $(document).ready(function() {
            choose_permitcost();
        });
        // vehicle script end
    </script>
</body>


</html>