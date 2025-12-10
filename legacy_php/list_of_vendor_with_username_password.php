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

$branch_id = $_GET['id'];


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

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
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
  <link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
  <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
  <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
  <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
  <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
  <!-- <link rel='stylesheet' type='text/css' media='screen' href='assets/css/easy-autocomplete.css'> -->

  <!-- Helpers -->
  <script src="assets/vendor/js/helpers.js"></script>
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
              <h4>Role Permission - Vendor List</h4>
              <?php include adminpublicpath('__breadcrumb.php'); ?>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card p-0">
                  <div class="card-header pb-3 d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                      <h5 class="m-0">List of vendor with username and password</h5>
                    </div>
                  </div>
                  <div class="card-body dataTable_select text-nowrap">
                    <table id="vehicle_LIST" class="table table-flush-spacing border table-responsive">
                      <thead class="table-head">
                        <tr>
                          <th>S.No</th>
                          <th>Vendor Name</th>
                          <th>Username</th>
                          <th>Password</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>1</td>
                          <td>Vendor 1</td>
                          <td>vendor1</td>
                          <td>test</td>
                        </tr>
                        <tr>
                          <td>2</td>
                          <td>Vendor 2</td>
                          <td>vendor2</td>
                          <td>test</td>
                        </tr>
                        <tr>
                          <td>3</td>
                          <td>Vendor 3</td>
                          <td>vendor3</td>
                          <td>test</td>
                        </tr>
                        <tr>
                          <td>4</td>
                          <td>Vendor 4</td>
                          <td>vendor4</td>
                          <td>test</td>
                        </tr>
                        <tr>
                          <td>5</td>
                          <td>Vendor 5</td>
                          <td>vendor5</td>
                          <td>test</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
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
  <script></script>
  <!-- <script src="assets/js/app-hotel-calendar.js"></script> -->

</body>

</html>