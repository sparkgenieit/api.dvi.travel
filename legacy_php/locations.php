<?php
include_once("jackus.php");
$current_page = 'locations.php';
admin_reguser_protect(); // Set the current page variable
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
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
    <link rel="stylesheet" type="text/css" href="./assets/css/parsley_validation.css">
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/vendor/js/template-customizer.js"></script>
    <script src="./assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <?php /* <script src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLEMAP_API_KEY; ?>&libraries=places"></script> */ ?>
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
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
                <?php //include_once('public/__topbar.php'); ?>
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

                        <span id="showLOCATIONLIST"></span>
                        <span id="showLOCATIONPREVIEW"></span>
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

    <!-- Add Location Modal -->
    <div class="modal fade" id="addLOCATIONFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp  modal-xl">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-location-form-data">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Location Modal -->
    <div class="modal fade" id="deleteLOCATIONFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-md">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-delete-location-form-data">
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

    <!--Delte location Modal -->
    <div class="modal fade" id="confirmDELETELOCATIONINFODATA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-0">
                <div class="modal-body receiving-confirm-delete-location-form-data">
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
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>

    <script>
        <?php if ($_GET['route'] == '') : ?>
            // Your code specific to this condition
            show_LOCATION_LIST();
        <?php elseif ($_GET['route'] == 'preview'  && $_GET['formtype'] == 'preview') : ?>
            show_LOCATION_PREVIEW('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php endif; ?>

        function show_LOCATION_LIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_location_list.php',
                success: function(response) {
                    $('#showLOCATIONLIST').html(response);
                    $('#showLOCATIONPREVIEW').html('');
                }
            });
        }

        function show_LOCATION_PREVIEW(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_location.php?type=preview',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showLOCATIONLIST').html('');
                    $('#showLOCATIONPREVIEW').html(response);
                }
            });
        }
    </script>
</body>

</html>