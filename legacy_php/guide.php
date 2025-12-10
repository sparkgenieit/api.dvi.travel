<?php
include_once('jackus.php');
admin_reguser_protect();

if (isset($logged_guide_id) && $logged_guide_id != 0 && isset($_GET['route']) && $_GET['id'] != $logged_guide_id):
    header("Location:guide.php?route=edit&formtype=basic_info&id=$logged_guide_id");
    die;
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

    <link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        /* Add this to your CSS */
        .input-error {
            border-color: red;
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <?php include_once('public/__sidebar.php'); ?>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4>
                                <?php include adminpublicpath('__pagetitle.php'); ?>
                                <?php if (isset($_GET['route']) && $_GET['route'] != 'add'): ?>
                                    » <b><?= getGUIDEDETAILS($_GET['id'], 'label'); ?></b>
                                <?php endif; ?>
                            </h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <span id="showGUIDELIST"></span>
                        <span id="showGUIDEFORMSTEP1"></span>
                        <span id="showGUIDEPRICEBOOK"></span>
                        <span id="showGUIDEFEEDBACK"></span>
                        <span id="showGUIDEPREVIEW"></span>
                        <span id="showGUIDEOVERALLPREVIEW"></span>

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
    <!-- Add Hotel Category Modal -->
    <div class="modal fade" id="addVEHICLETYPEFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-vehicle-type-form-data">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <!--Delte Hotel Category Modal -->
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

    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>
    <script src="assets/js/forms-tagify.js"></script>
    <script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
    <?php if (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'guide_pricebook') : ?>
        <script src="assets/js/app-guide-calendar.js?id=<?= $_GET['id']; ?>"></script>
    <?php endif; ?>

    <script src=" assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <!-- Main JS -->

    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            <?php if ($_GET['route'] == '') : ?>
                show_GUIDE_LIST();
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>
                show_GUIDE_FORM_STEP1('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'guide_pricebook') : ?>
                show_GUIDE_FORM_STEP2('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'guide_feedback') : ?>
                show_GUIDE_FORM_STEP3('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'guide_preview') : ?>
                show_GUIDE_FORM_STEP4('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
            <?php elseif (($_GET['route'] == 'preview') && $_GET['formtype'] == 'overallpreview') : ?>
                show_GUIDE_FORM_STEP5('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>')
            <?php endif; ?>

            function show_GUIDE_LIST() {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_guide_list.php?type=show_form',
                    success: function(response) {
                        $('#showGUIDELIST').html(response);
                    }
                });
            }

            function show_GUIDE_FORM_STEP1(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_add_guide_form.php?type=basic_info',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showGUIDELIST').html('');
                        $('#showGUIDEPRICEBOOK').html('');
                        $('#showGUIDEFORMSTEP1').html(response);
                    }
                });
            }

            function show_GUIDE_FORM_STEP2(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_guide_price_book.php?type=guide_pricebook',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showGUIDELIST').html('');
                        $('#showGUIDEFORMSTEP1').html('');
                        $('#showGUIDEPRICEBOOK').html(response);
                    }
                });
            }


            function show_GUIDE_FORM_STEP3(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_guide_feedbackandreview.php?type=guide_feedback',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showGUIDELIST').html('');
                        $('#showGUIDEFORMSTEP1').html('');
                        $('#showGUIDEPRICEBOOK').html('');
                        $('#showGUIDEFEEDBACK').html(response);
                    }
                });
            }

            function show_GUIDE_FORM_STEP4(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_guide_preview.php?type=guide_preview',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {

                        $('#showGUIDELIST').html('');
                        $('#showGUIDEFORMSTEP1').html('');
                        $('#showGUIDEPRICEBOOK').html('');
                        $('#showGUIDEFEEDBACK').html('');
                        $('#showGUIDEPREVIEW').html(response);
                    }
                });
            }

            function show_GUIDE_FORM_STEP5(TYPE, ID) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_guide_overallpreview.php?type=overallpreview',
                    data: {
                        ID: ID,
                        TYPE: TYPE
                    },
                    success: function(response) {
                        $('#showGUIDELIST').html('');
                        $('#showGUIDEFORMSTEP1').html('');
                        $('#showGUIDEPRICEBOOK').html('');
                        $('#showGUIDEFEEDBACK').html('');
                        $('#showGUIDEPREVIEW').html('');
                        $('#showGUIDEOVERALLPREVIEW').html(response);
                    }
                });
            }

        });
    </script>
</body>

</html>