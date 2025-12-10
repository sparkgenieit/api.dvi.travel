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

  <title>Hotel Category </title>

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
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
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
                        <span class="bs-stepper-circle disble-stepper-title">1</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title  disble-stepper-title">Driver Basic Info</h5>
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
                        <span class="bs-stepper-circle active-stepper">3</span>
                        <span class="bs-stepper-label mt-3 ">
                          <h5 class="bs-stepper-title">Upload Document</h5>

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
              <div class="col-12">
                <div class="card p-4">
                  <div class="card-body bulk-upload-body">
                    <div class="d-flex justify-content-between">
                      <h4>Upload Documents</h4>
                      <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fileUploadModal">
                          + Add Uploads
                        </button>
                      </div>
                    </div>
                    <div class="text-center mt-2">
                      <svg xmlns="http://www.w3.org/2000/svg" height="150" version="1.1" viewBox="-23 0 512 512" width="150">
                        <g id="surface1">
                          <path d="M 337.953125 230.601562 C 404.113281 239.886719 455.015625 296.65625 455.015625 365.378906 C 455.015625 440.503906 394.082031 501.4375 318.957031 501.4375 C 267.3125 501.4375 222.277344 472.625 199.335938 430.152344 C 188.878906 410.839844 182.902344 388.75 182.902344 365.273438 C 182.902344 290.148438 243.835938 229.214844 318.957031 229.214844 C 325.363281 229.320312 331.660156 229.75 337.953125 230.601562 Z M 337.953125 230.601562 " style="stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                          <path d="M 337.953125 230.601562 C 331.765625 229.75 325.363281 229.320312 318.957031 229.320312 C 243.835938 229.320312 182.902344 290.253906 182.902344 365.378906 C 182.902344 388.855469 188.878906 410.945312 199.335938 430.257812 L 199.121094 430.367188 L 57.199219 430.367188 C 31.265625 430.367188 10.242188 409.34375 10.242188 383.414062 L 10.242188 57.730469 C 10.242188 31.800781 31.265625 10.777344 57.199219 10.777344 L 229.429688 10.777344 L 229.429688 88.464844 C 229.429688 108.523438 245.648438 124.746094 265.710938 124.746094 L 337.953125 124.746094 Z M 337.953125 230.601562 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                          <path d="M 229.429688 10.777344 L 337.953125 124.746094 L 265.710938 124.746094 C 245.648438 124.746094 229.429688 108.523438 229.429688 88.464844 Z M 229.429688 10.777344 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                          <path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.96875 347.664062 119.410156 345.851562 117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.199219 0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.199219 440.71875 L 193.148438 440.71875 C 219.609375 485.535156 267.203125 512 318.960938 512 C 399.847656 512 465.6875 446.265625 465.6875 365.273438 C 465.6875 329.632812 452.988281 295.375 429.511719 268.59375 C 408.277344 244.476562 379.890625 228.042969 348.945312 221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 240.097656 102.550781 240.097656 88.464844 L 240.097656 37.457031 Z M 21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.199219 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 L 327.605469 135.523438 L 327.605469 218.863281 C 324.402344 218.757812 321.839844 218.332031 319.066406 218.332031 C 281.824219 218.332031 247.570312 232.628906 221.746094 255.039062 L 86.222656 255.039062 C 80.355469 255.039062 75.550781 259.839844 75.550781 265.710938 C 75.550781 271.582031 80.351562 276.382812 86.222656 276.382812 L 201.898438 276.382812 C 194.320312 287.054688 188.023438 297.726562 183.117188 309.464844 L 86.222656 309.464844 C 80.355469 309.464844 75.550781 314.265625 75.550781 320.132812 C 75.550781 326.003906 80.351562 330.804688 86.222656 330.804688 L 176.179688 330.804688 C 173.511719 341.476562 172.125 353.320312 172.125 365.167969 C 172.125 383.839844 175.644531 402.300781 182.476562 419.375 L 57.199219 419.375 C 37.242188 419.375 21.34375 403.367188 21.34375 383.414062 Z M 318.960938 490.765625 C 272.96875 490.765625 230.601562 465.582031 208.621094 425.136719 C 198.695312 406.890625 193.46875 386.292969 193.46875 365.378906 C 193.46875 296.230469 249.703125 239.992188 318.851562 239.992188 C 324.722656 239.992188 330.589844 240.421875 336.351562 241.167969 C 366.019531 245.328125 393.335938 260.054688 413.183594 282.679688 C 433.246094 305.515625 444.238281 334.859375 444.238281 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625" style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000" />
                          <path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.355469 201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.355469 223.027344 86.222656 223.027344 Z M 86.222656 223.027344 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000" />
                          <path d="M 326.535156 286.625 C 324.507812 284.492188 321.734375 283.210938 318.746094 283.210938 C 315.757812 283.210938 312.984375 284.492188 310.957031 286.625 L 248.425781 353.746094 C 244.367188 358.015625 244.6875 364.84375 248.957031 368.792969 C 250.984375 370.714844 253.652344 371.675781 256.214844 371.675781 C 259.09375 371.675781 262.082031 370.5 264.21875 368.257812 L 308.394531 320.984375 L 308.394531 437.515625 C 308.394531 443.382812 313.199219 448.1875 319.066406 448.1875 C 324.9375 448.1875 329.738281 443.382812 329.738281 437.515625 L 329.738281 320.988281 L 373.597656 368.261719 C 377.652344 372.527344 384.269531 372.847656 388.644531 368.792969 C 392.910156 364.738281 393.125 358.015625 389.175781 353.746094 Z M 326.535156 286.625 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000" />
                        </g>
                      </svg>
                    </div>
                    <!-- Display uploaded files outside the modal -->
                    <div id="uploadedFilesArea" class="mt-3">
                      <div class="row d-flex flex-wrap" id="uploadedFileList"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="fileUploadModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <!-- <div class="modal-content">
                  <div class="modal-body">
                    <h4 class="modal-title text-center mb-2" id="exampleModalLabel">Upload Files</h4>
                    <form id="fileUploadForm" enctype="multipart/form-data">
                      <div class="row">
                        <div class="col-12 mb-3">
                          <label class="form-label" for="formValidationUsername">Document Type<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <select id="driver_upload_document" class="form-control">
                              <option value="">Choose the Document Type</option>
                              <option value="1">Aadhar Card</option>
                              <option value="2">Pan card</option>
                              <option value="3">Profile Image</option>
                              <option value="4">Voter Id</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-12">
                          <label class="form-label" for="formValidationUsername">Upload Profile<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <input type="file" class="input-file" id="fileInput" name="file[]">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="uploadButton">Upload</button>
                  </div>
                </div> -->
                <div class="modal-content p-4">
                  <div class="modal-body receiving-subject-form-data"> <!-- Plugins css Ends-->
                    <form id="fileUploadForm" enctype="multipart/form-data">
                      <div class="modal-header pt-0 border-0">
                        <h4 class="modal-title mx-auto" style="color:black">Add Document</h4>
                      </div>
                      <div class="row mt-2">
                        <div class="col-12 mb-3">
                          <label class="form-label" for="formValidationUsername">Document Type<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <select id="driver_upload_document" class="form-control">
                              <option value="">Choose the Document Type</option>
                              <option value="1">Aadhar Card</option>
                              <option value="2">Pan card</option>
                              <option value="3">Profile Image</option>
                              <option value="4">Voter Id</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-12">
                          <label class="form-label" for="formValidationUsername">Upload Profile<span class=" text-danger"> *</span></label>
                          <div class="form-group">
                            <input type="file" class="input-file" id="fileInput" name="file[]">
                          </div>
                        </div>
                      </div>
                      <div class="d-flex justify-content-center pt-4">
                        <button type="button" class="btn btn-label-github waves-effect mx-1" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="button" class="btn btn-primary mx-1">Save</button>
                      </div>
                    </form>
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
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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


    // $(document).ready(function() {
    //   const uploadedFileList = $('#uploadedFileList');
    //   let selectedFiles = [];

    //   // Function to display uploaded files with download icons
    //   function displayUploadedFiles() {
    //     // Clear the existing file list
    //     uploadedFileList.empty();

    //     // Display all selected files with download icons in rows with three columns
    //     for (let i = 0; i < selectedFiles.length; i++) {
    //       const file = selectedFiles[i];
    //       const fileName = file.name;

    //       const fileItem = $('<div class="col-md-4 mb-3"></div>');
    //       const fileCard = $('<div class="card"></div>');
    //       const fileCardBody = $('<div class="card-body d-flex justify-content-between p-3 border-dashed rounded"></div>');

    //       // Close button (X) to delete the file
    //       const closeButton = $('<button type="button" class="close" aria-label="Close" style="position:absolute; right:-6px; top:-7px;border:none;background-color:#7367f0;border-radius:50%;color:white">X</button>');
    //       closeButton.html('<span aria-hidden="true">&times;</span>');
    //       closeButton.click(function() {
    //         // Remove the file from the selectedFiles array
    //         selectedFiles = selectedFiles.filter(selectedFile => selectedFile !== file);
    //         displayUploadedFiles();
    //       });

    //       const fileNameElement = $('<p class="card-text m-0"></p>');
    //       fileNameElement.text(fileName);

    //       const downloadLink = $('<a class="download-link ms-3" href="#"><i class="fas fa-download ml-2"></i></a>');
    //       downloadLink.click(function(e) {
    //         e.preventDefault(); // Prevent the link from navigating to a new page
    //         // Create a temporary URL for the file and trigger the download
    //         const fileURL = URL.createObjectURL(file);
    //         const link = document.createElement('a');
    //         link.href = fileURL;
    //         link.download = fileName;
    //         link.click();
    //         URL.revokeObjectURL(fileURL); // Clean up the temporary URL
    //       });

    //       fileCardBody.append(closeButton, fileNameElement, downloadLink);
    //       fileCard.append(fileCardBody);
    //       fileItem.append(fileCard);
    //       uploadedFileList.append(fileItem);
    //     }
    //   }

    //   // Initially, hide the download links
    //   $('.download-link').hide();

    //   $('#uploadButton').click(function() {
    //     const newlySelectedFiles = $('#fileInput').prop('files');

    //     if (newlySelectedFiles.length > 0) {
    //       // Get the newly selected files and add them to the list
    //       selectedFiles = selectedFiles.concat(Array.from(newlySelectedFiles));
    //       displayUploadedFiles();
    //     } else {
    //       alert('Please select one or more files to upload.');
    //     }
    //   });
    // });
  </script>

</body>

</html>