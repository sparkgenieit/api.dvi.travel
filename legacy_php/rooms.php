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

  <title>Vehicle</title>

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
  <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
  <link rel='stylesheet' type='text/css' media='screen' href='assets/css/easy-autocomplete.css'>
  <link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css">
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
              <div>
                <h4 class="font-weight-bold">Vehicle</h4>
              </div>
              <div class="my-3">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">
                        <i class="tf-icons ti ti-home mx-2"></i>
                      </a>
                    </li>
                    <li class="breadcrumb-item " aria-current="page">Dashboard</li>
                    <li class="breadcrumb-item active" aria-current="page">Vehicle</li>
                  </ol>
                </nav>
              </div>
            </div>


            <div class="row">
              <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                  <div class="bs-stepper-header border-0 justify-content-start py-2">
                    <div class="step">
                      <button type="button" class="step-trigger">
                        <span class="bs-stepper-circle">1</span>
                        <span class="bs-stepper-label mt-3">
                          <h5 class="bs-stepper-title">Basic Info</h5>

                        </span>
                      </button>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step">
                      <button type="button" class="step-trigger">
                        <span class="bs-stepper-circle  active-stepper">2</span>
                        <span class="bs-stepper-label mt-3">
                          <h5 class="bs-stepper-title">Rooms Details</h5>
                        </span>
                      </button>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step">
                      <button type="button" class="step-trigger">
                        <span class="bs-stepper-circle disble-stepper-num">3</span>
                        <span class="bs-stepper-label mt-3">
                          <h5 class="bs-stepper-title  disble-stepper-title">Amenities</h5>
                          <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                        </span>
                      </button>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step">
                      <button type="button" class="step-trigger">
                        <span class="bs-stepper-circle disble-stepper-num">4</span>
                        <span class="bs-stepper-label mt-3">
                          <h5 class="bs-stepper-title  disble-stepper-title">Price Book</h5>
                        </span>
                      </button>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step">
                      <button type="button" class="step-trigger">
                        <span class="bs-stepper-circle disble-stepper-num">5</span>
                        <span class="bs-stepper-label mt-3">
                          <h5 class="bs-stepper-title  disble-stepper-title">Gallery</h5>
                        </span>
                      </button>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step">
                      <button type="button" class="step-trigger">
                        <span class="bs-stepper-circle disble-stepper-num">6</span>
                        <span class="bs-stepper-label mt-3">
                          <h5 class="bs-stepper-title  disble-stepper-title">Hotel Preview</h5>
                        </span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-12">
                <div class="card p-4">
                  <div class="text-end d-flex justify-content-end">
                    <button onclick="addForm()" data-repeater-create type="button" class="btn btn-primary waves-effect waves-light mb-3 room-add-button">+ Add Rooms</button>
                  </div>
                  <div id="form-container" class="mt-4">
                    <form class="" id="form_hotel_room_hotel" action="#" method="POST">
                      <div class="row g-3">
                        <div class="col-md-4">
                          <label class="hotel-room-label" for="form-repeater-1-4">Room Type</label>
                          <input class="form-control" id="room-type" name="room-type" placeholder="Enter the Room Type" />
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="hotel-room-label" for="form-repeater-1-4">Prefered For</label>
                            <div class="select2-primary">
                              <select id="select2Primary" class="select2 form-select" multiple>
                                <option value="1" selected>Option1</option>
                                <option value="2" selected>Option2</option>
                                <option value="3">Option3</option>
                                <option value="4">Option4</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="hotel-room-label" for="form-repeater-1-4">AC Availability</label>
                          <select id="air_conditioner" name="air_conditioner" class="form-select" required>
                            <?= getACAVAIABILITY($air_conditioner, 'select') ?>
                          </select>
                        </div>
                        <div class="col-md-4"> <label class="hotel-room-label" for="form-repeater-1-4">Room Reference Code</label>
                          <input type="text" id="room_ref_code" name="room_ref_code" required class="form-control" placeholder="Enter the Room Reference Code" value="<?= $room_ref_code; ?>" required autocomplete="off" />
                          <input type="hidden" name="old_room_ref_code" id="old_room_ref_code" value="<?= $room_ref_code; ?>" />
                        </div>
                        <div class="col-md-4">
                          <div class="form-group">
                            <label class="hotel-room-label w-100" for="modalAddCardCvv">Max Adult<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                              <input type="text" id="total_adult" name="total_adult" required class="form-control" placeholder="Enter the total adult " value="<?= $total_adult; ?>" required autocomplete="off" />
                              <input type="hidden" name="old_total_adult" id="old_total_adult" value="<?= $total_adult; ?>" />
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="hotel-room-label w-100" for="modalAddCardCvv">Max Children<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <input type="text" id="total_children" name="total_children" required class="form-control" placeholder="Enter the total children " value="<?= $total_children; ?>" required autocomplete="off" />
                            <input type="hidden" name="old_total_children" id="old_total_children" value="<?= $total_children; ?>" />
                          </div>
                        </div>

                        <div class="col-md-4">
                          <label class="hotel-room-label w-100" for="modalAddCard">Check-IN Time<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <input class="form-control" type="time" placeholder="hh:mm" value="<?= $check_in_time ?>" id="check_in_time" name="check_in_time" required>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <label class="hotel-room-label w-100" for="modalAddCard">Check-OUT Time<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <input class="form-control" type="time" placeholder="hh:mm" value="<?= $check_out_time ?>" id="check_out_time" name="check_out_time" required>
                          </div>
                        </div>
                        <div class="col-lg-10 col-xl-10 col-12 mt-3">
                          <label class="hotel-room-label w-100" for="modalAddCard">Food Included ?</label>
                          <div class="form-group mt-3">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                              <label class="form-check-label" for="inlineCheckbox1">Breakfast</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                              <label class="form-check-label" for="inlineCheckbox2">Lunch</label>
                            </div>
                            <div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3">
                              <label class="form-check-label" for="inlineCheckbox3">Dinner</label>
                            </div>
                          </div>

                        </div>
                        <div class="col-md-12">
                          <label class="hotel-room-label w-100 mb-2" for="modalAddCard">Room Image Upload</label>
                          <div action="https://demos.pixinvent.com/upload" class="dropzone needsclick p-0" id="dropzone-multi">
                            <div class="dz-message needsclick">
                              <p class="fs-4 note needsclick pt-3 mb-1">Drag and drop your image here</p>
                              <p class="text-muted d-block fw-normal mb-2">or</p>
                              <span class="note needsclick btn bg-label-primary d-inline" id="btnBrowse">Browse image</span>
                            </div>
                            <div class="fallback">
                              <input name="file" type="file" />
                            </div>
                          </div>
                        </div>
                        <div class="col-12">
                          <button class="btn btn-label-danger mt-4" onclick="deleteForm(this)">
                            <i class="ti ti-x ti-xs me-1"></i>
                            <span class="align-middle">Delete</span>
                          </button>
                        </div>
                        <div class="border-bottom border-bottom-dashed my-4"></div>
                      </div>
                    </form>
                  </div>
                  <div class="d-flex justify-content-between py-3">
                    <div>
                      <a href="room_details.php" class="btn btn-primary">Back</a>
                    </div>
                    <div>
                      <a href="amenity.php" class="btn btn-primary btn-md">Save &
                        Continue</a>
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
              <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
              <script src="assets/vendor/js/menu.js"></script>
              <script src="assets/vendor/libs/tagify/tagify.js"></script>
              <script src="assets/js/forms-tagify.js"></script>


              <!-- endbuild -->
              <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
              <!-- Form Validation -->
              <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
              <script src="assets/js/parsley.min.js"></script>
              <script src="assets/js/custom-common-script.js"></script>
              <!-- Vendors JS -->
              <script src="assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>
              <script src="assets/js/selectize/selectize.min.js"></script>
              <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
              <script src="assets/vendor/libs/select2/select2.js"></script>
              <script src="assets/js/forms-selects.js"></script>
              <script src="assets/vendor/libs/autosize/autosize.js"></script>
              <!-- <script src="assets/vendor/libs/cleavejs/cleave.js"></script>
              <script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
              <script src="assets/js/jquery.easy-autocomplete.min.js"></script> -->
              <script src="assets/vendor/libs/dropzone/dropzone.js"></script>
              <script src="assets/js/forms-file-upload.js"></script>
              <script>
                function addForm() {
                  // Clone the original form
                  const originalForm = document.querySelector('form');
                  const clonedForm = originalForm.cloneNode(true);

                  // Clear input values in the cloned form (optional)
                  clonedForm.querySelectorAll('input').forEach(input => {
                    input.value = '';
                  });

                  // Append the cloned form to the form container
                  document.getElementById('form-container').appendChild(clonedForm);
                }

                function deleteForm(button) {
                  // Get the form element associated with the clicked "Delete" button
                  const form = button.parentElement;

                  // Remove the form from the container
                  document.getElementById('form-container').removeChild(form);
                }
              </script>


</body>

</html>