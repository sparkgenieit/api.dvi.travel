<?php
include_once("jackus.php");
$current_page = 'vehicle_availability_chart.php'; // Set the current page variable
admin_reguser_protect();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <!-- Canonical SEO -->
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
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/js/config.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <style>
        .table-responsive {
            overflow-x: auto;
            /* Enables horizontal scroll when content overflows */
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on touch devices */
        }

        table {
            width: 100%;
            /* Ensure table takes full width inside the responsive div */
            border-collapse: collapse;
            margin-top: 1rem;
            border: 1px solid #ddd;
        }

        thead th {
            background-color: #f4f4f4;
        }

        thead th,
        tbody td {
            padding: 0.75rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        .vehicle-avail-dropdown {
            background: #fff;
            border: 1px solid #b3b2b2;
            padding: 5px 5px;
            border-radius: 5px;
        }

        .arrival-deparure-vehicle {
            background-color: #e0d7fa96;
        }

        .inbetween-vehicle {
            background-color: #faebd794;
        }

        .completed-vehicle {
            background-color: #dffbdf;
        }

        .not-assign-vehicle {
            background-color: #fff;
        }

        /* Sticky Columns */
        .sticky-col {
            position: -webkit-sticky;
            position: sticky;
            left: 0;
            background-color: #fff;
            border-right: 1px solid #ddd;
            z-index: 10;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sticky-col+.sticky-col {
            left: 119px;
            z-index: 9;
            background-color: #fff;
            /* Ensure background color for consistency */
            border-left: 1px solid #ddd;
            /* Border between sticky columns */
            border-right: 1px solid #ddd;
            /* Ensure both sides have a border */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }


        thead th.sticky-col+.sticky-col {
            position: sticky;
            top: 0;
            background-color: #e8e8e8;
            /* Background color for sticky header */
            z-index: 11;
            /* Ensure sticky header is above the sticky columns */
            border-right: 1px solid #ddd;
            /* Optional: border for header separation */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        thead th.sticky-col {
            position: sticky;
            top: 0;
            background-color: #e8e8e8;
            /* Background color for sticky header */
            z-index: 11;
            /* Ensure sticky header is above the sticky columns */
            border-right: 1px solid #ddd;
            /* Optional: border for header separation */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }



        /* Set borders for each cell inside the sticky column */
        .sticky-col td {
            border: 1px solid #ddd;
            /* Ensure cells in sticky column have borders */
        }

        @media (max-width: 768px) {
            .table-responsive {
                /* Ensure responsiveness on smaller screens */
                width: 100%;
                overflow-x: auto;
            }
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
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <div class="d-flex justify-content-end p-1">
                            <span id="response_alert"></span>
                        </div>

                        <span id="showGUIDELIST"></span>
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
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>


    <script>
        <?php if ($_GET['route'] == '') : ?>
            show_VEHICLE_LIST();
        <?php endif; ?>


        function show_VEHICLE_LIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_guide_availability_list.php',
                success: function(response) {
                    $('#showGUIDELIST').html(response);
                }
            });
        }
    </script>
</body>

</html>