<?php
include_once("jackus.php");
admin_reguser_protect(); // Set the current page variable
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
    <link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
    <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css">
    <link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>

    <style>
        .gst-attachement-download {
            border: 1px solid #e9e7fd;
            padding: 10px;
            border-radius: 5px;
            background-color: #ffecfc6e !important;
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card p-4">
                                    <div class="row">
                                        <h5 class="text-primary">Voucher Details</h5>
                                        <div class="col-md-3">
                                            <label class="text-light">Quote ID</label>
                                            <p>#DVI202407-192</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Start Date</label>
                                            <p>16/07/2024</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">End Date</label>
                                            <p>27/07/2024</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Days/Night</label>
                                            <p>9D/8N</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Person Count</label>
                                            <p>Adult-2, Child-1, Infant-0</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Room Count</label>
                                            <p>1</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Extra Bed</label>
                                            <p>1</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Child With bed</label>
                                            <p>0</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Child Without bed</label>
                                            <p>0</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="text-light">Overall Trip Cost</label>
                                            <p>₹ 50,000.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-0">
                                    <div class="card-header mb-2 d-flex align-items-center justify-content-between">
                                        <h5 class="card-title text-primary m-0">Hotel Details</h5>
                                        <button id="createVoucherButton" class="btn btn-label-primary d-none" data-bs-toggle="modal" data-bs-target="#imageModal">Create Voucher</button>
                                    </div>
                                    <div class="card-body dataTable_select text-nowrap">
                                        <div class="text-nowrap table-responsive table-bordered">
                                            <table id="staff_CASHHISTORY" class="table table-hover">
                                                <thead class="table-head">
                                                    <tr>
                                                        <th><input class="form-check-input" type="checkbox" value="" id="allcustomCheck"></th>
                                                        <th>DAYS</th>
                                                        <th>DATE</th>
                                                        <th>Hotel Name & </br>Category</th>
                                                        <th>Location</th>
                                                        <th>Room Count</th>
                                                        <th>Check IN</th>
                                                        <th>Check Out</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th><input class="form-check-input" type="checkbox" value="" id="customCheck1"></th>
                                                        <td>Day1</td>
                                                        <td>17/07/2024</td>
                                                        <td>Hilton&3*</td>
                                                        <td>Chennai</td>
                                                        <td>2</td>
                                                        <td>17/07/2024,4.00PM</td>
                                                        <td>18/07/2024,6.00AM</td>
                                                        <td><span class="badge bg-label-secondary">Block</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th><input class="form-check-input" type="checkbox" value="" id="customCheck2"></th>
                                                        <td>Day2</td>
                                                        <td>18/07/2024</td>
                                                        <td>Taj&5*</td>
                                                        <td>Pondi</td>
                                                        <td>2</td>
                                                        <td>18/07/2024,6.00PM</td>
                                                        <td>19/07/2024,7.00AM</td>
                                                        <td><span class="badge bg-label-warning">Awaiting</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-0">
                                    <div class="card-header mb-1 d-flex align-items-center justify-content-between">
                                        <h5 class="card-title text-primary m-0">Vehicle Details</h5>
                                        <button id="createVoucherButton" class="btn btn-label-primary d-none" data-bs-toggle="modal" data-bs-target="#imageModal">Create Voucher</button>
                                    </div>
                                    <div class="card-body dataTable_select text-nowrap">
                                        <div class="text-nowrap table-responsive table-bordered">
                                            <table id="staff_CASHHISTORY" class="table table-hover">
                                                <thead class="table-head">
                                                    <tr>
                                                        <th>VEHICLE TYPE</th>
                                                        <th>VENDOR NAME</th>
                                                        <th>BRANCH NAME</th>
                                                        <th>VEHICLE ORIGIN</th>
                                                        <th>STATUS</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>sedan</td>
                                                        <td>VSR</td>
                                                        <td>VSR-Chennai</td>
                                                        <td>Chennai</td>
                                                        <td>
                                                            <select class="form-select" name="vehicle_month" id="vehicle_month">
                                                                <option value="">Choosen Status</option>
                                                                <option value="1"><span class="badge bg-label-secondary">Block</span></option>
                                                                <option value="2"><span class="badge bg-label-warning">Awaiting</span></option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
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

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content ">
                <div class="modal-header p-0 text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <h5 class="modal-title text-center mb-3" id="imageModalLabel">Create Hotel Voucher</h5>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <h6 class="text-primary">Day-1 [Hilton - Chennai]</h6>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="confirmed_by">Confirmed By<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="confirmed_by" id="confirmed_by" class="form-control required-field" placeholder="Confirmed By" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="email_id">Email Id<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="email_id" id="email_id" class="form-control required-field" placeholder="Email Id" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="mobile_number">Mobile Number<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="mobile_number" id="mobile_number" class="form-control required-field" placeholder="Mobile Number" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="confirmed_by">Status<span class=" text-danger"> *</span></label>
                                    <select class="form-select" name="vehicle_month" id="vehicle_month">
                                        <option value="">Choosen Status</option>
                                        <option value="1">Awaiting</option>
                                        <option value="2">Block</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="confirmed_by">Waiting List<span class=" text-danger"> *</span></label>
                                    <select class="form-select" name="vehicle_month" id="vehicle_month">
                                        <option value="">Choosen Waiting List</option>
                                        <option value="1">Sold Out</option>
                                        <option value="2">Rate Issue</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom border-bottom-dashed my-4"></div>
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <h6 class="text-primary">Day-2 [Taj - Pondi]</h6>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="confirmed_by">Confirmed By<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="confirmed_by" id="confirmed_by" class="form-control required-field" placeholder="Confirmed By" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="email_id">Email Id<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="email_id" id="email_id" class="form-control required-field" placeholder="Email Id" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="mobile_number">Mobile Number<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="mobile_number" id="mobile_number" class="form-control required-field" placeholder="Mobile Number" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="confirmed_by">Status<span class=" text-danger"> *</span></label>
                                    <select class="form-select" name="vehicle_month" id="vehicle_month">
                                        <option value="">Choosen Status</option>
                                        <option value="1">Awaiting</option>
                                        <option value="2">Block</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label" for="confirmed_by">Waiting List<span class=" text-danger"> *</span></label>
                                    <select class="form-select" name="vehicle_month" id="vehicle_month">
                                        <option value="">Choosen Waiting List</option>
                                        <option value="1">Sold Out</option>
                                        <option value="2">Rate Issue</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-bottom border-bottom-dashed my-4"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-primary m-0">Cancellation Policy</h5>
                        <button class="btn btn-label-primary" data-bs-toggle="modal" data-bs-target="#cancellation_policy">+ Add Cancellation</button>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 mt-3">
                            <div class="text-nowrap table-responsive table-bordered">
                                <table id="staff_CASHHISTORY" class="table table-hover">
                                    <thead class="table-head">
                                        <tr>
                                            <th>S.No</th>
                                            <th>Description</th>
                                            <th>Cancellation Date</th>
                                            <th>Cancellation percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>17 June before cancel date</td>
                                            <td>17/07/2024</td>
                                            <td>50%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Sumbit</button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="cancellation_policy" tabindex="-1" aria-labelledby="cancellation_policyLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-0 text-center">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-5">
                    <h5 class="modal-title text-center mb-3" id="cancellation_policyLabel">Add Coupon</h5>
                    <div class="col-md-12 mb-2">
                        <label class="form-label" for="cancellation_date">Cancellation Date<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="cancellation_date" id="cancellation_date" class="form-control required-field" placeholder="dd/mm/yy" autocomplete="off" required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="cancellation_percentage">Percentage<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="cancellation_percentage" id="cancellation_percentage" class="form-control required-field" placeholder="Cancellation Percentage" autocomplete="off" value="<?= $quotation_no_format; ?>" required />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="cancellation_description">Description<span class=" text-danger"> *</span></label>
                        <div class="form-group">
                            <textarea rows="3" id="cancellation_description" name="cancellation_description" placeholder="Enter the Title" class="form-control required-field" required><?= $invoice_address; ?></textarea>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Sumbit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Core JS -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <!-- Vendors JS -->
    <script src="assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js"></script>
    <!-- Flat Picker -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
    <script src="assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="assets/js/modal-add-new-cc.js"></script>
    <script src="assets/js/modal-add-new-address.js"></script>
    <script src="assets/js/modal-edit-user.js"></script>
    <script src="assets/js/modal-enable-otp.js"></script>
    <script src="assets/js/modal-share-project.js"></script>
    <script src="assets/js/modal-create-app.js"></script>
    <script src="assets/js/modal-two-factor-auth.js"></script>
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr("#cancellation_date", {
                enableTime: false, // Set to true to enable time selection
                dateFormat: "d-m-Y", // Customize date format
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const allCheck = document.getElementById('allcustomCheck');
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            const button = document.getElementById('createVoucherButton');

            function toggleButtonVisibility() {
                const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                if (anyChecked) {
                    button.classList.remove('d-none');
                } else {
                    button.classList.add('d-none');
                }
            }

            allCheck.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = allCheck.checked;
                });
                toggleButtonVisibility();
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', toggleButtonVisibility);
            });

            toggleButtonVisibility(); // Initial check on page load
        });
    </script>

</body>

</html>