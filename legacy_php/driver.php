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

if (isset($logged_vendor_id) && $logged_vendor_id != 0 && isset($_GET['route']) && isset($_GET['id'])):
    $check_vendor_driver = getDRIVER_DETAILS($logged_vendor_id, $_GET['id'], 'check_vendor_driver');
    if ($check_vendor_driver == 0):
        header("Location:driver.php");
    endif;
endif;

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr"
    data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap"
        rel="stylesheet">

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
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        .driver-image-close {
            position: absolute;
            top: 28px;
            right: 61px;
            background-color: #ffecfc;
            border: 1px solid #cdcaf0;
            padding: 2px 6px;
            cursor: pointer;
            border-radius: 18px;
            font-size: 12px;
            color: #d700b3;
            font-weight: 600;
        }

        .logo-img-container {
            width: 110px;
            /* Set the desired width */
            height: 83px;
            /* Set the desired height */
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid #ccc;
            /* Optional: add a border for better visibility */
            overflow: hidden;
            /* Ensure image stays within the container */
            border-radius: 8px;
        }

        .logo-img-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
            /* Scale the image to fill the container while preserving its aspect ratio */
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
                        <div class="d-flex justify-content-between">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?>
                                <?php if (isset($_GET['route']) && $_GET['route'] != 'add'): ?>
                                    » <b><?= getDRIVER_DETAILS('', $_GET['id'], 'driver_name'); ?> - [<?= getVENDORANDVEHICLEDETAILS(getDRIVER_DETAILS('', $_GET['id'], 'vendor_id'), 'get_vendorname_from_vendorid'); ?>]</b>
                                <?php endif; ?>
                            </h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>

                        <span id="showDRIVERLIST"></span>
                        <span id="showDRIVERFORMSTEP1"></span>
                        <span id="showDRIVERFORMSTEP2"></span>
                        <span id="showDRIVERFORMSTEP3"></span>
                        <span id="showDRIVERFORMSTEP4"></span>
                        <span id="showDRIVERCREATEPREVIEW"></span>
                        <span id="showDRIVERPREVIEW"></span>

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
    <div class="modal fade" id="showSWIPERGALLERYMODAL" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered1 modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-swiper-room-form-data">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>


    <!--Delte Hotel Category Modal -->
    <!-- <div class="modal fade" id="confirmDELETEINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-0">
                <div class="modal-body receiving-confirm-delete-form-data">
                </div>
            </div>
        </div>
    </div> -->

    <!-- Delete Driver Modal -->
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
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets/vendor/libs/hammer/hammer.js"></script>
    <script src="./assets/vendor/libs/i18n/i18n.js"></script>
    <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="./assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <!-- Flat Picker -->
    <script src="./assets/vendor/libs/moment/moment.js"></script>
    <script src="./assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="./assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="./assets/js/modal-add-new-cc.js"></script>
    <script src="./assets/js/modal-add-new-address.js"></script>
    <script src="./assets/js/modal-edit-user.js"></script>
    <script src="./assets/js/modal-enable-otp.js"></script>
    <script src="./assets/js/modal-share-project.js"></script>
    <script src="./assets/js/modal-create-app.js"></script>
    <script src="./assets/js/modal-two-factor-auth.js"></script>
    <script src="./assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="./assets/js/_jquery.dataTables.min.js"></script>
    <script src="./assets/js/_dataTables.buttons.min.js"></script>
    <script src="./assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="./assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="./assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="./assets/js/_js_buttons.html5.min.js"></script>
    <script src="./assets/js/parsley.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <script src="./assets/js/custom-common-script.js"></script>
    <!-- Selectize JS and Autocomplete JS -->
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!---------->

    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            <?php if ($_GET['route'] == '') : ?>
                show_DRIVER_LIST();
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'driver_basic_info') : ?>
                show_DRIVER_FORM_STEP1('<?= $_GET['id']; ?>', '<?= $_GET['route']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'driver_cost') : ?>
                show_DRIVER_FORM_STEP2('<?= $_GET['id']; ?>', '<?= $_GET['route']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'driver_upload_documents') : ?>
                show_DRIVER_FORM_STEP3('<?= $_GET['id']; ?>', '<?= $_GET['route']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'driver_feedback') : ?>
                show_DRIVER_FORM_STEP4('<?= $_GET['id']; ?>', '<?= $_GET['route']; ?>');
            <?php elseif ($_GET['route'] == 'add' || $_GET['route'] == 'edit' && $_GET['formtype'] == 'driver_create_preview') : ?>
                show_DRIVER_CREATE_PREVIEW('<?= $_GET['id']; ?>', '<?= $_GET['route']; ?>');
            <?php endif; ?>
        });

        function show_DRIVER_LIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_driver_list.php?type=show_form',
                success: function(response) {
                    $('#showDRIVERLIST').html(response);
                }
            });
        }

        function show_DRIVER_FORM_STEP1(ID, ROUTE) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_driver_form.php?type=driver_basic_info&ID=' + ID + '&ROUTE=' + ROUTE,
                success: function(response) {
                    $('#showDRIVERLIST').html('');
                    $('#add_driver').hide();
                    $('#showDRIVERFORMSTEP1').html(response);
                }
            });
        }

        function show_DRIVER_FORM_STEP2(ID, ROUTE) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_driver_form.php?type=driver_cost&ID=' + ID + '&ROUTE=' + ROUTE,
                success: function(response) {
                    $('#showDRIVERLIST').html('');
                    $('#add_driver').hide();
                    $('#showDRIVERFORMSTEP1').html('');
                    $('#showDRIVERFORMSTEP2').html(response);
                }
            });
        }

        function show_DRIVER_FORM_STEP3(ID, ROUTE) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_driver_form.php?type=driver_upload_documents&ID=' + ID + '&ROUTE=' +
                    ROUTE,
                success: function(response) {
                    $('#showDRIVERLIST').html('');
                    $('#add_driver').hide();
                    $('#showDRIVERFORMSTEP1').html('');
                    $('#showDRIVERFORMSTEP2').html('');
                    $('#showDRIVERFORMSTEP3').html(response);
                }
            });
        }

        function show_DRIVER_FORM_STEP4(ID, ROUTE) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_driver_form.php?type=driver_feedback&ID=' + ID + '&ROUTE=' + ROUTE,
                success: function(response) {
                    $('#showDRIVERLIST').html('');
                    $('#add_driver').hide();
                    $('#showDRIVERFORMSTEP1').html('');
                    $('#showDRIVERFORMSTEP2').html('');
                    $('#showDRIVERFORMSTEP3').html('');
                    $('#showDRIVERFORMSTEP4').html(response);
                }
            });
        }

        function show_DRIVER_CREATE_PREVIEW(ID, ROUTE) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_driver_form.php?type=driver_create_preview&ID=' + ID + '&ROUTE=' +
                    ROUTE,
                success: function(response) {
                    $('#showDRIVERCREATEPREVIEW').html(response);
                }
            });
        }

        //SHOW DELETE POPUP
        // function showDELETEDRIVERMODAL(ID) {
        //     $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_driver.php?type=delete&ID=' + ID,
        //         function() {
        //             const container = document.getElementById("confirmDELETEINFODATA");
        //             const modal = new bootstrap.Modal(container);
        //             modal.show();
        //         });
        // }

        //SHOW DELETE POPUP
        function showDELETEDRIVERMODAL(ID) {
            $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_driver.php?type=delete&ID=' + ID,
                function() {
                    const container = document.getElementById("confirmDELETEINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
        }

        function togglestatusITEM(STATUS_ID, DRIVER_ID) {
            if (DRIVER_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_driver.php?type=updatestatus",
                    data: {
                        DRIVER_ID: DRIVER_ID,
                        STATUS_ID: STATUS_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#driver_LIST').DataTable().ajax.reload();

                            if (response.result_driver_license_expiry_date == true) {
                                TOAST_NOTIFICATION('warning',
                                    'Update driver license expired date to change status.', 'Warning !!!',
                                    '', '', '', '', '', '', '', '', '');
                                $("switch_status_" + DRIVER_ID).attr("checked", false);
                            } else {
                                TOAST_NOTIFICATION('success', 'Driver Status Updated Successfully.',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            TOAST_NOTIFICATION('success', 'Unable to update driver status.', 'Success !!!', '',
                                '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        }

        $(document).ready(function() {

            <?php if ($_GET['route'] == 'preview' && $_GET['formtype'] == 'driver_preview') : ?>
                show_DRIVER_PREVIEW('<?= $_GET['id']; ?>');
            <?php endif; ?>
        });

        function show_DRIVER_PREVIEW(ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_driver_preview.php?type=driver_preview&ID=' + ID,
                success: function(response) {
                    $('#showDRIVERPREVIEW').html(response);
                }
            });
        }

        // function confirmDRIVERDELETE(ID) {
        //     $.ajax({
        //         type: "POST",
        //         url: "engine/ajax/ajax_manage_driver.php?type=confirm_delete",
        //         data: {
        //             _ID: ID
        //         },
        //         dataType: 'json',
        //         success: function(response) {
        //             if (!response.success) {
        //                 //NOT SUCCESS RESPONSE
        //                 if (response.result_error) {
        //                     ERROR_ALERT(response.result_error);
        //                 }
        //             } else {
        //                 //SUCCESS RESPOSNE
        //                 if (response.response_result) {
        //                     SUCCESS_ALERT(response.response_result);
        //                 }
        //                 $('#confirmDELETEINFODATA').modal('hide');
        //                 $('#driver_LIST').DataTable().ajax.reload();
        //             }
        //         }
        //     });
        // }

        function confirmDRIVERDELETE(ID) {
            $.ajax({
                type: "POST",
                url: "engine/ajax/__ajax_manage_driver.php?type=confirm_delete",
                data: {
                    _ID: ID
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        // NOT SUCCESS RESPONSE
                        if (response.result_error) {
                            ERROR_ALERT(response.result_error);
                        }
                    } else {
                        // SUCCESS RESPONSE
                        if (response.response_result) {
                            SUCCESS_ALERT(response.response_result);
                        }
                        $('#confirmDELETEINFODATA').modal('hide');
                        $('#driver_LIST').DataTable().ajax.reload();
                        window.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                }
            });
        }
    </script>

</body>

</html>