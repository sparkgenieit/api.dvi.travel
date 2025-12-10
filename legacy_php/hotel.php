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
                        <div class="d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?>
                                <?php if (isset($_GET['route']) && $_GET['route'] != 'add'): ?>
                                    » <b><?= getHOTEL_DETAIL($_GET['id'], '', 'label'); ?></b>
                                <?php endif; ?>
                            </h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <span id="showHOTELLIST"></span>
                        <span id="showHOTELFORMSTEP1"></span>
                        <span id="showHOTELFORMSTEP2"></span>
                        <span id="showHOTELFORMSTEP3"></span>
                        <span id="showHOTELFORMSTEP4"></span>
                        <span id="showHOTELFORMSTEP5"></span>
                        <span id="showHOTELFORMSTEP6"></span>
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
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <!-- Main JS -->
    <script src="assets/js/footerscript.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            <?php if ($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview') : ?>
                $("#select-rooms").change(function() {
                    // Toggle the visibility of the div containing the select element
                    if ((this.checked) || ($("#selectAll").is(":checked"))) {
                        $("#roomTypeFilterDiv").show();
                    } else if ($("#select-amenities").is(":checked")) {
                        $("#roomTypeFilterDiv").hide();
                    } else {
                        $("#roomTypeFilterDiv").show();
                    }
                });
            <?php endif; ?>

            <?php if ($_GET['route'] == '') : ?>
                // Your code specific to this condition
                show_HOTEL_LIST();
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>
                // Your code specific to this condition
                show_HOTEL_FORM_STEP1('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'room_details') : ?>
                // Your code specific to this condition
                show_HOTEL_FORM_STEP2('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'room_amenities') : ?>
                // Your code specific to this condition
                show_HOTEL_FORM_STEP3('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'hotel_pricebook') : ?>
                // Your code specific to this condition
                show_HOTEL_FORM_STEP4('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'hotel_review') : ?>
                // Your code specific to this condition
                show_HOTEL_FORM_STEP5('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'hotel_preview') : ?>
                // Your code specific to this condition
                show_HOTEL_FORM_STEP6('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif ($_GET['route'] == 'preview' && $_GET['formtype'] == 'preview' && $_GET['request'] == '') : ?>
                // Your code specific to this condition
                show_HOTEL_PREVIEW('basic_info', '<?= $_GET['id']; ?>');
            <?php endif; ?>
        });

        function showPRICEBOOK_MODAL(DATE, ROOM_TYPE_ID) {
            $('.show-pricebook-form-data').load('engine/ajax/__ajax_show_hotel_pricebook_form.php?type=show_form&DT=' +
                DATE + '&ID=<?= $_GET['id']; ?>&ROOM_TYPE_ID=' + ROOM_TYPE_ID,
                function() {
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

        function show_HOTEL_FORM_STEP4(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/ajax_hotel_pricebook_details_form.php?type=show_form',
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
                    $('#showHOTELFORMSTEP4').html(response);
                }
            });
        }

        function show_HOTEL_FORM_STEP5(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_hotel_form.php?type=hotel_review',
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
                    $('#showHOTELFORMSTEP4').html('');
                    $('#showHOTELFORMSTEP5').html(response);
                }
            });
        }

        function show_HOTEL_FORM_STEP6(TYPE, ID) {
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
                    $('#showHOTELFORMSTEP4').html('');
                    $('#showHOTELFORMSTEP5').html('');
                    $('#showHOTELFORMSTEP6').html(response);
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
            $('.receiving-delete-form-data').load('engine/ajax/__ajax_manage_hotel.php?type=hotel_delete&ID=' + ID,
                function() {
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
                        TOAST_NOTIFICATION('success', 'Hotel Delete Successfully', 'Success !!!', '', '', '',
                            '', '', '', '', '', '');
                    } else {
                        TOAST_NOTIFICATION('error', 'Unable to delete the hotel', 'Error !!!', '', '', '', '',
                            '', '', '', '', '');
                    }
                }
            });
        }
    </script>

</body>

</html>