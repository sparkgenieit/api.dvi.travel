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
                        <span id="showHOTELLIST"></span>
                        <span id="showHOTELFORMSTEP1"></span>
                        <span id="showHOTELFORMSTEP2"></span>
                        <span id="showHOTELFORMSTEP3"></span>
                        <span id="showHOTELFORMSTEP5"></span>
                        <?php if (($_GET['route'] == 'add' || $_GET['route'] == 'edit' || $_GET['route'] == 'preview') && ($_GET['formtype'] == 'hotel_pricebook' || $_GET['formtype'] == 'preview')) :
                            $hotel_ID = $_GET['id'];

                            if ($hotel_ID != '' && $hotel_ID != 0 && $_GET['route'] == 'edit') :
                                $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
                                $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
                                $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
                                $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
                                $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
                            else :
                                $basic_info_url = 'hotel.php?route=add&formtype=basic_info&id=' . $hotel_ID;
                                $room_details_url = 'hotel.php?route=add&formtype=room_details&id=' . $hotel_ID;
                                $room_amenities_url = 'hotel.php?route=add&formtype=room_amenities&id=' . $hotel_ID;
                                $hotel_pricebook_url = 'hotel.php?route=add&formtype=hotel_pricebook&id=' . $hotel_ID;
                                $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
                            endif;
                            if ($_GET['request'] == '' && $_GET['route'] != 'preview' && $_GET['formtype'] != 'preview') :
                        ?>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div id="wizard-validation" class="bs-stepper mt-2">
                                            <div class="bs-stepper-header border-0 justify-content-start py-2">
                                                <div class="step" data-target="#account-details-validation">
                                                    <a href="<?= $basic_info_url ?>" class="step-trigger">
                                                        <span class="bs-stepper-circle disble-stepper-num">1</span>
                                                        <span class="bs-stepper-label mt-3 ">
                                                            <h5 class="bs-stepper-title disble-stepper-title">Hotel Basic Info</h5>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="line">
                                                    <i class="ti ti-chevron-right"></i>
                                                </div>
                                                <div class="step" data-target="#personal-info-validation">
                                                    <a href="<?= $room_details_url ?>" class="step-trigger">
                                                        <span class="bs-stepper-circle">2</span>
                                                        <span class="bs-stepper-label mt-3">
                                                            <h5 class="bs-stepper-title disble-stepper-title">Rooms</h5>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="line">
                                                    <i class="ti ti-chevron-right"></i>
                                                </div>
                                                <div class="step" data-target="#social-links-validation">
                                                    <a href="<?= $room_amenities_url ?>" class="step-trigger">
                                                        <span class="bs-stepper-circle">3</span>
                                                        <span class="bs-stepper-label mt-3">
                                                            <h5 class="bs-stepper-title disble-stepper-title">Amenities</h5>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="line">
                                                    <i class="ti ti-chevron-right"></i>
                                                </div>
                                                <div class="step" data-target="#price-book">
                                                    <a href="<?= $hotel_pricebook_url ?>" class="step-trigger">
                                                        <span class="bs-stepper-circle active-stepper">4</span>
                                                        <span class="bs-stepper-label mt-3">
                                                            <h5 class="bs-stepper-title">Price Book</h5>
                                                        </span>
                                                    </a>
                                                </div>
                                                <div class="line">
                                                    <i class="ti ti-chevron-right"></i>
                                                </div>
                                                <div class="step">
                                                    <a href="<?= $preview_url ?>" class="step-trigger">
                                                        <span class="bs-stepper-circle disble-stepper-num">5</span>
                                                        <span class="bs-stepper-label mt-3">
                                                            <h5 class="bs-stepper-title disble-stepper-title">Hotel Preview</h5>
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else :
                                $hotel_pricebook_url = 'hotel.php?route=preview&formtype=preview&request=pricebook&id=' . $hotel_ID;
                            ?>
                                <div class="card mb-3 col-xl-12 col-lg-12 col-md-12 order-0 order-md-1 px-1" id="show_preview_pricebook_tab">
                                    <ul class="nav p-2 nav-pills card-header-pills " role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="javascript:;" onclick="show_HOTEL_PREVIEW('basic_info','<?= $hotel_ID; ?>')" class=" nav-link <?= $active_basic_info_tab; ?> shadow-none hotel_overall_preview_tap">Basic Info</a>
                                        </li>
                                        <li class="nav-item mx-2" role="presentation">
                                            <a href="javascript:;" onclick="show_HOTEL_PREVIEW('room_details','<?= $hotel_ID; ?>')" class="nav-link <?= $active_room_details_tab; ?> shadow-none hotel_overall_preview_tap">Rooms Details</a>
                                        </li>
                                        <li class="nav-item mx-2" role="presentation">
                                            <a href="javascript:;" onclick="show_HOTEL_PREVIEW('room_amenities','<?= $hotel_ID; ?>')" class="nav-link <?= $active_room_amenities_tab; ?> shadow-none hotel_overall_preview_tap">Amenities</a>
                                        </li>
                                        <li class="nav-item mx-2" role="presentation">
                                            <a href="<?= $hotel_pricebook_url; ?>" class="nav-link active shadow-none hotel_overall_preview_tap">Price Book</a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <?php if (($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview') || ($_GET['formtype'] == 'hotel_pricebook')) : ?>
                                <div class="row" id="show_calendar_div">
                                    <div class="col-12">
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
                                                                <input class="form-check-input input-filter" type="checkbox" id="select-rooms" data-value="rooms" checked>
                                                                <label class="form-check-label" for="select-rooms">Room Price</label>
                                                            </div>
                                                            <div class="form-check form-check-warning mb-2">
                                                                <input class="form-check-input input-filter" type="checkbox" id="select-amenities" data-value="amenities" checked>
                                                                <label class="form-check-label" for="select-amenities">Amentites Price</label>
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
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <span id="showHOTELPREVIEW"></span>
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
    <script src="assets/js/app-hotel-calendar.js?id=<?= $_GET['id']; ?>"></script>
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            <?php if ($_GET['route'] == '') : ?>
                show_HOTEL_LIST();
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>
                show_HOTEL_FORM_STEP1('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'room_details') : ?>
                show_HOTEL_FORM_STEP2('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'room_amenities') : ?>
                show_HOTEL_FORM_STEP3('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'hotel_preview') : ?>
                show_HOTEL_FORM_STEP5('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif ($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview' && $_GET['request'] == '') : ?>
                show_HOTEL_PREVIEW('', '<?= $_GET['id']; ?>');
            <?php endif; ?>
        });

        function showPRICEBOOK_MODAL(DATE) {
            $('.show-pricebook-form-data').load('engine/ajax/__ajax_show_hotel_pricebook_form.php?type=show_form&DT=' + DATE + '&ID=<?= $_GET['id']; ?>', function() {
                const container = document.getElementById("showPRICEBOOKFORM");
                const modal = new bootstrap.Modal(container);
                modal.show();
            });
        }

        function show_HOTEL_LIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_hotel_list.php?type=show_form',
                success: function(response) {
                    $('#showHOTELLIST').html(response);
                }
            });
        }

        function show_HOTEL_FORM_STEP1(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_hotel_form.php?type=basic_info',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showHOTELLIST').html('');
                    $('#add_hotel').hide();
                    $('#showHOTELFORMSTEP1').html(response);
                }
            });
        }

        function show_HOTEL_FORM_STEP2(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_hotel_form.php?type=room_details',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showHOTELLIST').html('');
                    $('#add_hotel').hide();
                    $('#showHOTELFORMSTEP1').html('');
                    $('#showHOTELFORMSTEP2').html(response);
                }
            });
        }

        function show_HOTEL_FORM_STEP3(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_hotel_form.php?type=room_amenities',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showHOTELLIST').html('');
                    $('#add_hotel').hide();
                    $('#showHOTELFORMSTEP1').html('');
                    $('#showHOTELFORMSTEP2').html('');
                    $('#showHOTELFORMSTEP3').html(response);
                }
            });
        }

        function show_HOTEL_FORM_STEP5(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_hotel_form.php?type=hotel_preview',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showHOTELLIST').html('');
                    $('#add_hotel').hide();
                    $('#showHOTELFORMSTEP1').html('');
                    $('#showHOTELFORMSTEP2').html('');
                    $('#showHOTELFORMSTEP3').html('');
                    $('#showHOTELFORMSTEP5').html(response);
                }
            });
        }

        function show_HOTEL_PREVIEW(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_hotel_overall_preview.php?type=hotel_preview',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showHOTELLIST').html('');
                    $('#add_hotel').hide();
                    $('#showHOTELFORMSTEP1').html('');
                    $('#showHOTELFORMSTEP2').html('');
                    $('#showHOTELFORMSTEP3').html('');
                    if (TYPE) {
                        $('#show_calendar_div').hide();
                        $('#show_preview_pricebook_tab').hide();
                    } else {
                        $('#show_calendar_div').show();
                        $('#show_preview_pricebook_tab').show();
                    }
                    $('#showHOTELPREVIEW').html(response);
                }
            });
        }

        //SHOW DELETE POPUP
        function showHOTELDELETEMODAL(ID) {
            $('.receiving-delete-form-data').load('engine/ajax/__ajax_manage_hotel.php?type=hotel_delete&ID=' + ID, function() {
                const container = document.getElementById("showDELETEMODAL");
                const modal = new bootstrap.Modal(container);
                modal.show();
            });
        }

        //CONFIRM DELETE POPUP
        function confirmHOTELDELETE(ID) {
            $.ajax({
                type: "POST",
                url: "engine/ajax/__ajax_manage_hotel.php?type=confirm_hotel_delete",
                data: {
                    _ID: ID
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result == true) {
                        $('#hotel_LIST').DataTable().ajax.reload();
                        $('#showDELETEMODAL').modal('hide');
                        TOAST_NOTIFICATION('success', 'Hotel Delete Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                    } else {
                        TOAST_NOTIFICATION('error', 'Unable to delete the hotel', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }
                }
            });
        }
    </script>

</body>

</html>