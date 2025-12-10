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

  <title>Driver Form</title>

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
              <div>
                <h4 class="font-weight-bold">Add Drivers</h4>
              </div>
              <div class="my-3">
                <!-- <nav aria-label="breadcrumb">
                  <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">
                        <i class="tf-icons ti ti-home mx-2"></i>
                      </a>
                    </li>
                    <li class="breadcrumb-item " aria-current="page">Driver</li>
                    <li class="breadcrumb-item active" aria-current="page">Add Drivers</li>
                  </ol>
                </nav> -->
              </div>
            </div>

            <div class="row">
              <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                  <div class="bs-stepper-header border-0 justify-content-start py-2">
                    <div class="step" data-target="#account-details-validation">
                      <a type="button" href="" class="step-trigger">
                        <span class="bs-stepper-circle  active-stepper">1</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title">Driver Basic Info</h5>
                          <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                        </span>
                      </a>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step" data-target="#account-details-validation">
                      <a type="button" href="" class="step-trigger">
                        <span class="bs-stepper-circle  disble-stepper-title">2</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title disble-stepper-title">Cost Details</h5>

                          <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                        </span>
                      </a>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step" data-target="#account-details-validation">
                      <a type="button" href="" class="step-trigger">
                        <span class="bs-stepper-circle  disble-stepper-title">3</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title disble-stepper-title">Upload Document</h5>

                          <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                        </span>
                      </a>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step" data-target="#account-details-validation">
                      <a type="button" href="" class="step-trigger">
                        <span class="bs-stepper-circle  disble-stepper-title">4</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title disble-stepper-title">Renewal History</h5>

                          <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                        </span>
                      </a>
                    </div>
                    <div class="line">
                      <i class="ti ti-chevron-right"></i>
                    </div>
                    <div class="step" data-target="#account-details-validation">
                      <a type="button" href="" class="step-trigger">
                        <span class="bs-stepper-circle  disble-stepper-title">5</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>

                          <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                        </span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mt-3">
              <div class="col-md-12">
                <div class="card p-4">
                  <form class="" id="form_add_driver" method="post">
                    <div class="row g-3">
                      <div class="col-md-4">
                        <label class="driver-text-label w-100" for="vendor_id">Vendor ID<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <select id="vendor_id" class="form-control">
                            <option value="">Choose the Vendor Id</option>
                            <option value="1">Ven01</option>
                            <option value="2">Ven02</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label w-100" for="driver_name">Driver Name<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="driver_name" id="driver_name" class="form-control" placeholder="Enter Driver Name" />
                        </div>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="driver-text-label" for="dateofbirth">Date of Birth<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="dateofbirth" id="dateofbirth" class="form-control" placeholder="Enter License Number" />
                          <span class="calender-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                              <g>
                                <defs>
                                  <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                    <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000" opacity="1"></path>
                                  </clipPath>
                                </defs>
                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                  <path d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374" style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform="translate(236.333 118)" fill="none" stroke="#7367f0" stroke-width="40" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity="" data-original="#000000" opacity="1" class=""></path>
                                </g>
                              </g>
                            </svg>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label w-100" for="driver_blood_group">Blood Group<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="driver_blood_group" id="driver_blood_group" class="form-control" placeholder="Enter Blood Group" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="formValidationUsername">Gender<span class=" text-danger"> *</span></label>
                        <select id="availability-type" class="form-control">
                          <option value="">Male</option>
                          <option value="1">Female</option>
                          <option value="2">Others</option>
                        </select>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label w-100" for="driver_primary_mobileno">Primary Mobile<span class=" text-danger">*</span></label>
                        <div class="form-group">
                          <input type="text" id="driver_primary_mobileno" name="driver_primary_mobileno" class="form-control" placeholder="Enter Primary Mobile No" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label w-100" for="driver_alternative_mobileno">Alternative Mobile No</label>
                        <div class="form-group">
                          <input type="text" id="driver_alternative_mobileno" name="driver_alternative_mobileno" class="form-control" placeholder="Enter Alternative Mobile No" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="driver_email_id">Email Id</label>
                        <div class="form-group">
                          <input type="text" id="driver_email_id" name="driver_email_id" class="form-control" placeholder="Enter the Email ID" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="aadhar_card_num">Aadhar Card Number<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="aadhar_card_num" id="aadhar_card_num" class="form-control" placeholder="Enter the Aadhar Card Number" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="pan_card_num">PAN Card Number<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="pan_card_num" id="pan_card_num" class="form-control" placeholder="Enter the PAN Card  Number" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="voter_id_num">Voter Id Number<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="voter_id_num" id="voter_id_num" class="form-control" placeholder="Enter the Voter Id Number" />
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="license_number">License Number<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="license_number" id="license_number" class="form-control" placeholder="Enter License Number" />
                        </div>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="driver-text-label" for="formValidationUsername">License Issue Date<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="" id="licensedate" class="form-control" placeholder="Enter License Issue Date" />
                          <span class="calender-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                              <g>
                                <defs>
                                  <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                    <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000" opacity="1"></path>
                                  </clipPath>
                                </defs>
                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                  <path d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374" style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform="translate(236.333 118)" fill="none" stroke="#7367f0" stroke-width="40" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity="" data-original="#000000" opacity="1" class=""></path>
                                </g>
                              </g>
                            </svg>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="driver-text-label" for="license_expire_date">License Expire Date<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <input type="text" name="license_expire_date" id="license_expire_date" class="form-control" placeholder="Enter License Expire Date" />
                          <span class="calender-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                              <g>
                                <defs>
                                  <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                    <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000" opacity="1"></path>
                                  </clipPath>
                                </defs>
                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                  <path d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374" style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform="translate(236.333 118)" fill="none" stroke="#7367f0" stroke-width="40" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity="" data-original="#000000" opacity="1" class=""></path>
                                </g>
                              </g>
                            </svg>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label w-100" for="driver_document_type">Document Type<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <select id="driver_document_type" class="form-control">
                            <option value="">Choose the Document Type</option>
                            <option value="1">Aadhar Card</option>
                            <option value="2">Pan card</option>
                            <option value="3">Profile Image</option>
                            <option value="4">Voter Id</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="driver_address">Address<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                          <textarea id="driver_address" name="driver_address" class="form-control" placeholder="Enter the Address" type="text" data-parsley-type="address" autocomplete="off" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-checkemail data-parsley-checkemail-message="Address already Exists" autocomplete="off" value="" rows="3" maxlength="255"></textarea>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <label class="driver-text-label" for="formValidationUsername">Upload Profile</label>
                        <div class="form-group d-flex">
                          <input class="input-file" type="file" id="file-input" multiple>
                          <!-- <div id="file-content"></div> -->
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="d-flex justify-content-between mt-4">
                    <a href="" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l14 0"></path>
                        <path d="M5 12l4 4"></path>
                        <path d="M5 12l4 -4"></path>
                      </svg>Back</a>
                    <a href="" type="button" class="btn btn-primary waves-effect waves-light pe-3">Save &
                      Continue<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M5 12l14 0"></path>
                        <path d="M15 16l4 -4"></path>
                        <path d="M15 8l4 4"></path>
                      </svg></a>
                  </div>
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
  <!-- Add Amenities List Modal -->
  <div class="modal fade" id="addAMENITIESLISTFORM" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple">
      <div class="modal-content p-3 p-md-5">
        <div class="receiving-amenities-category-form-data">
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
  <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="assets/vendor/js/menu.js"></script>
  <script src="assets/vendor/libs/tagify/tagify.js"></script>
  <script src="assets/js/forms-tagify.js"></script>
  <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>


  <!-- Form Validation -->
  <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
  <script src="assets/js/parsley.min.js"></script>
  <script src="assets/js/custom-common-script.js"></script>
  <!-- Vendors JS -->
  <script src="assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>
  <script src="assets/js/selectize/selectize.min.js"></script>
  <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
  <script>
    // Initialize Flatpickr
    flatpickr('#dateofbirth', {
      dateFormat: 'd-m-Y', // Change this format to your desired date format
      // Other options go here
    });

    flatpickr('#licensedate', {
      dateFormat: 'd-m-Y', // Change this format to your desired date format
      // Other options go here
    });
    flatpickr('#license_expire_date', {
      dateFormat: 'd-m-Y', // Change this format to your desired date format
      // Other options go here
    });
  </script>

</body>

</html>