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

  <title>Driver - <?= $_SITETITLE; ?></title>
  <!-- <title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title> -->

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
            <span id="showDRIVERLIST"></span>
            <span id="showDRIVERFORMSTEP1"></span>
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
  <!-- / Layout wrapper -->
  <!-- Add Amenities List Modal -->
  <div class="modal fade" id="addDRIVERFORM" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="receiving-driver-form-data">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
    </div>
  </div>
  <!--Delte Amenities List Modal -->
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
  <!-- Main JS -->
  <script src="assets/js/main.js"></script>

  <script>
    $(document).ready(function() {
      <?php if ($_GET['route'] == '') : ?>
        show_DRIVER_LIST();
      <?php elseif ($_GET['route'] == 'add' && $_GET['formtype'] == 'basic_info') : ?>
        show_DRIVER_FORM_STEP1('<?= $_GET['id']; ?>');
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

    function show_DRIVER_FORM_STEP1(ID) {
      $.ajax({
        type: 'post',
        url: 'engine/ajax/__ajax_add_driver_form.php?type=basic_info&ID=' + ID,
        success: function(response) {
          $('#showDRIVERLIST').html('');
          $('#add_hotel').hide();
          $('#showDRIVERFORMSTEP1').html(response);
        }
      });
    }

    function showDRIVERMODAL(DRIVER_ID) {
      $('.receiving-driver-form-data').load('engine/ajax/ajax_add_driver_form.php?type=show_form&DRIVER_ID=' +
        DRIVER_ID + '',
        function() {
          const container = document.getElementById("addDRIVERFORM");
          const modal = new bootstrap.Modal(container);
          modal.show();
          if (DRIVER_ID) {
            $('#addDRIVERFORMLabel').html('Edit Driver Details');
          } else {
            $('#addDRIVERFORMLabel').html('Add Driver Details');
          }
        });
    }

    function togglestatusITEM(STATUS_ID, DRIVER_ID) {
      if (DRIVER_ID) {
        $.ajax({
          type: "POST",
          url: "engine/ajax/ajax_manage_driver.php?type=updatestatus",
          data: {
            DRIVER_ID: DRIVER_ID,
            STATUS_ID: STATUS_ID
          },
          dataType: 'json',
          success: function(response) {
            if (response.result == true) {
              $('#driver_LIST').DataTable().ajax.reload();
              SUCCESS_ALERT(response.result_success);
            } else {
              ERROR_ALERT(response.result_error);
            }
          }
        });
      }
    }

    function showDELETEDRIVERMODAL(ID) {
      $('.receiving-confirm-delete-form-data').load('engine/ajax/ajax_manage_driver.php?type=delete&ID=' + ID,
        function() {
          const container = document.getElementById("confirmDELETEINFODATA");
          const modal = new bootstrap.Modal(container);
          modal.show();
        });
    }

    function confirmDRIVERDELETE(ID) {
      $.ajax({
        type: "POST",
        url: "engine/ajax/ajax_manage_driver.php?type=confirmdelete",
        data: {
          _ID: ID
        },
        dataType: 'json',
        success: function(response) {
          if (!response.success) {
            //NOT SUCCESS RESPONSE
            if (response.result_error) {
              ERROR_ALERT(response.result_error);
            }
          } else {
            //SUCCESS RESPOSNE
            if (response.response_result) {
              SUCCESS_ALERT(response.response_result);
            }
            $('#confirmDELETEINFODATA').modal('hide');
            $('#driver_LIST').DataTable().ajax.reload();
          }
        }
      });
    }
  </script>

</body>

</html>