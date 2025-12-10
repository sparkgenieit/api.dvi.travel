<?php
include_once("jackus.php");
$current_page = 'hotel_category.php';
admin_reguser_protect(); // Set the current page variable
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="./assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="./assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="./assets/vendor/libs/flatpickr/flatpickr.css" />
    <!-- Row Group CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
    <!-- Form Validation -->
    <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="./assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/parsley_validation.css">
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/vendor/js/template-customizer.js"></script>
    <script src="./assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Menu -->
            <!-- Layout container -->
            <div class="layout-page">
                <?php include_once('public/__sidebar.php'); ?>
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
                        <div class="d-flex justify-content-end p-1">
                            <span id="response_alert"></span>
                        </div>
                        <!-- Users List Table -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card p-0">
                                    <div class="card-header pb-3 d-flex justify-content-between">
                                        <h5 class="card-title mb-3">List of Hotels Category</h5>
                                        <a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showHOTELCATEGORYMODAL(0);" data-bs-dismiss="modal">+ Add Hotel Category</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->
                </div>
                <!-- Footer -->
                <?php include_once('public/__footer.php'); ?>
                <!-- / Footer -->
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

    </div>
    <!-- / Layout wrapper -->
    <!-- Add Hotel Category Modal -->
    <div class="modal fade" id="addHOTELCATEGORYFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-hotel-category-form-data">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <!--Delte Hotel Category Modal -->
    <div class="modal fade" id="confirmDELETEINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-0">
                <div class="modal-body receiving-confirm-delete-form-data">
                </div>
            </div>
        </div>
    </div>
    <!-- Core JS -->
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets/vendor/libs/hammer/hammer.js"></script>
    <script src="./assets/vendor/libs/i18n/i18n.js"></script>
    <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>
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
    <!-- <script src="./assets/js/custom-common-script.js"></script> -->
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            TOAST_NOTIFICATION('warning', 'Hotel Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
        });
    </script>
</body>

</html>