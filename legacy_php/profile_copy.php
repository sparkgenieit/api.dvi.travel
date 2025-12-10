<?php

include_once("jackus.php");
$current_page = 'profile.php';
admin_reguser_protect(); // Set the current page variable

$select_agent_details_query = sqlQUERY_LABEL("SELECT  `agent_ID`, `travel_expert_id`, `subscription_plan_id`, `agent_name`, `agent_lastname`, `agent_primary_mobile_number`, `agent_alternative_mobile_number`, `agent_email_id`, `agent_gst_number`, `agent_gst_attachment`,`agent_ref_no` FROM `dvi_agent` WHERE `deleted` = '0' AND `agent_ID` = '$logged_agent_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
while ($fetch_data = sqlFETCHARRAY_LABEL($select_agent_details_query)) :
    $agent_name = !empty($fetch_data['agent_name']) ? $fetch_data['agent_name'] : '--';
    $agent_lastname = !empty($fetch_data['agent_lastname']) ? $fetch_data['agent_lastname'] : '--';
    $agent_email_id = !empty($fetch_data['agent_email_id']) ? $fetch_data['agent_email_id'] : '--';
    $agent_primary_mobile_number = !empty($fetch_data['agent_primary_mobile_number']) ? $fetch_data['agent_primary_mobile_number'] : '--';
    $agent_alternative_mobile_number = !empty($fetch_data['agent_alternative_mobile_number']) ? $fetch_data['agent_alternative_mobile_number'] : '--';
    $agent_gst_number = !empty($fetch_data['agent_gst_number']) ? $fetch_data['agent_gst_number'] : '--';
    $agent_gst_attachment = $fetch_data['agent_gst_attachment'];
    $agent_ref_no = !empty($fetch_data['agent_ref_no']) ? $fetch_data['agent_ref_no'] : '--';
    $travel_expert_id = $fetch_data['travel_expert_id'];
    $travel_expert_name = getTRAVEL_EXPERT($travel_expert_id, 'label');
endwhile;

$select_agentconfig_query = sqlQUERY_LABEL("SELECT `agent_config_id`, `agent_id`, `site_logo`, `company_name`, `site_address`, `terms_condition`, `invoice_logo`, `invoice_gstin_no`,`invoice_pan_no`, `invoice_address` FROM `dvi_agent_configuration` WHERE `deleted` = '0' AND `agent_id` = '$logged_agent_id'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
while ($fetch_config_data = sqlFETCHARRAY_LABEL($select_agentconfig_query)) :
    $hidden_agent_ID = $fetch_config_data['agent_id'];
    $site_logo = $fetch_config_data['site_logo'];
    $site_address = $fetch_config_data['site_address'];
    $terms_condition = $fetch_config_data['terms_condition'];
    $invoice_logo = $fetch_config_data['invoice_logo'];
    $company_name = $fetch_config_data['company_name'];
    $invoice_gstin_no = $fetch_config_data['invoice_gstin_no'];
    $invoice_pan_no = $fetch_config_data['invoice_pan_no'];
    $invoice_address = $fetch_config_data['invoice_address'];
endwhile;
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php include agentpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">

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

    <!-- Bootstrap-timepicker CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.css">
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
                            <?php $list_user_datas = sqlQUERY_LABEL("SELECT `username`, `useremail`, `user_profile`, `roleID` FROM `dvi_users` where `userID` ='$logged_user_id'") or die("#1_UNABLE_TO_FETCH_USER_DATA:" . sqlERROR_LABEL());
                            while ($row = sqlFETCHARRAY_LABEL($list_user_datas)) :
                                $username = $row["username"];
                                $useremail = $row["useremail"];
                                $user_profile = $row["user_profile"];
                                $roleID = $row["roleID"];
                                $roleName = getRole($roleID, 'label');
                            endwhile; ?>
                            <div class="col-md-4 d-flex">
                                <div class="card p-4 text-center w-100">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <a class="text-decoration-none" href="dashboard.php"><i class="ti ti-arrow-left"></i></a>
                                        <h6 class="m-0 me-3">PROFILE</h6>
                                        <div></div>
                                    </div>
                                    <hr class="mb-4 mt-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="avatar-lg bg-primary rounded-circle">
                                            <h4 class="text-center text-white d-flex align-items-center justify-content-center" style="height: 100%;"><?= ucwords($useremail[0]); ?></h4>
                                        </div>
                                    </div>
                                    <h4 class="mt-2 mb-0"><?= $username; ?></h4>
                                    <p class="badge bg-label-primary mt-1 mx-auto"><?= ucwords($roleName); ?></p>
                                    <!-- Referral Code Section -->
                                    <?php $get_reg_no = getAGENT_details($logged_agent_id, '', 'get_referral_number_from_agent_id');

                                    if ($get_reg_no) :
                                        $encoded_referral_code = Encryption::Encode($get_reg_no, SECRET_KEY);
                                        $hidden_ref_link = PUBLICPATH . "register.php?ref_code=" . $encoded_referral_code; ?>
                                        <div class="mt-1">
                                            <label class="fs-14 mb-2">Share Your Referral Code</label>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="text" id="referralCode" value="<?= $get_reg_no; ?>" readonly class="form-control form-control-sm me-2" style="width: 120px;">
                                                <input type="text" name="hidden_ref_link" id="hidden_ref_link" value="<?= $hidden_ref_link; ?>" class="d-none" />
                                                <button id="copyBtn" class="btn btn-sm btn-primary" onclick="copyReferralCode()" data-toggle="tooltip" data-placement="top" title="Copied!">Copy</button>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-8 d-flex">
                                <div class="card p-4 w-100">
                                    <div class="row">
                                        <h5 class="text-primary">Basic Info</h5>
                                        <div></div>
                                        <div class="col-md-4">
                                            <label>First Name</label>
                                            <p class="text-light">
                                                <?= $agent_name; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Last Name</label>
                                            <p class="text-light">
                                                <?= $agent_lastname; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email Address</label>
                                            <p class="text-light">
                                                <?= $agent_email_id; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Mobile No </label>
                                            <p class="text-light">
                                                <?= $agent_primary_mobile_number; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Alternative Mobile No</label>
                                            <p class="text-light">
                                                <?= $agent_alternative_mobile_number; ?>
                                            </p>

                                        </div>
                                        <div class="col-md-4">
                                            <label>GSTIN Number</label>
                                            <p class="text-light">
                                                <?= $agent_gst_number; ?>
                                            </p>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Travel Expert</label>
                                            <p class="text-light">
                                                <?= $travel_expert_name; ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label>GST Attachement</label>
                                            <div class="gst-attachement-download d-flex align-items-center justify-content-between">
                                                <?php if (!empty($agent_gst_attachment)) : ?>
                                                    <h6 class="m-0"><?= $agent_gst_attachment; ?></h6>
                                                    <a href="uploads/agent_doc/<?= $agent_gst_attachment; ?>" download>
                                                        <img src="assets/img/svg/downloads.svg" alt="Download" />
                                                    </a>
                                                <?php else : ?>
                                                    <h6 class="m-0">No file uploaded</h6>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card p-4">
                                    <form id="agent_configuration_setting" action="" method="POST" data-parsley-validate>
                                        <div class="row g-3 mt-2">
                                            <h5 class="text-primary m-0">General Configuration</h5>
                                            <input type="hidden" id="hidden_agent_ID" name="hidden_agent_ID" value="<?= $hidden_agent_ID; ?>" />
                                            <div class="col-md-6">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label" for="">Logo Upload</label>
                                                    <a href="#" class="fw-bold" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#sitelogo">View</a>
                                                </div>
                                                <div class="form-group">
                                                    <input type="file"
                                                        name="site_logo_upload"
                                                        id="site_logo_upload"
                                                        autocomplete="off"
                                                        class="form-control"
                                                        accept=".jpg,.jpeg,.png" />
                                                    <input type="hidden" id="site_logo" name="site_logo" value="<?= $site_logo; ?>" />
                                                </div>

                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label" for="agent_company_name">Company Name</label>
                                                <div class="form-group">
                                                    <input type="text" name="agent_company_name" id="agent_company_name" class="form-control" autocomplete="off" placeholder="Company Name" value="<?= $company_name; ?>" />
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="site_address">Address</label>
                                                <div class="form-group">
                                                    <textarea rows="1" id="site_address" name="site_address" placeholder="Enter the Address" class="form-control"><?= $site_address; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="terms_condition">Terms and Condition</label>
                                                <div class="form-group">
                                                    <textarea rows="1" id="terms_condition" name="terms_condition" placeholder="Enter the Terms and condition" class="form-control"><?= $terms_condition; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="divider">
                                            <div class="divider-text">
                                                <i class="ti ti-star ti-sm text-primary"></i>
                                            </div>
                                        </div>

                                        <div class="row g-3 mt-2 mb-2">
                                            <h5 class="text-primary m-0">Invoice Setting</h5>

                                            <div class="col-md-3">
                                                <div class="d-flex justify-content-between">
                                                    <label class="form-label" for="">Invoice Logo Upload</label>
                                                    <a href="#" class="fw-bold" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#invoicelogo">View</a>
                                                </div>
                                                <div class="form-group">
                                                    <input type="file" name="invoice_logo_upload" id="invoice_logo_upload" autocomplete="off" class="form-control" accept=".jpg,.jpeg,.png" />
                                                    <input type="hidden" id="invoice_logo" name="invoice_logo" value="<?= $invoice_logo; ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="gst_in_number">GSTIN Number</label>
                                                <div class="form-group">
                                                    <input type="text" name="gst_in_number" id="gst_in_number" class="form-control" data-parsley-whitespace="trim" data-parsley-trigger="keyup" autocomplete="off" placeholder="GSTIN Number" data-parsley-pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[A-Za-z0-9]{1}" value="<?= $invoice_gstin_no; ?>" maxlength="15" />
                                                    <small class="text-dark"><b>GSTIN Format: 10AABCU9603R1Z5 </b></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label" for="invoice_pan_no">Pan No</label>
                                                <div class="form-group">
                                                    <input type="text" name="invoice_pan_no" id="invoice_pan_no" class="form-control" data-parsley-whitespace="trim" data-parsley-trigger="keyup" autocomplete="off" placeholder="PAN Number" data-parsley-pattern="^[A-Z]{5}[0-9]{4}[A-Z]{1}$" value="<?= $invoice_pan_no; ?>" maxlength="10" />
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="invoice_address">Invoice Address</label>
                                                <div class="form-group">
                                                    <textarea rows="1" id="invoice_address" name="invoice_address" placeholder="Enter the Address" class="form-control"><?= $invoice_address; ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-center mt-4">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light pe-3">
                                                <span class="ti-xs ti ti-device-floppy me-1"></span>Update
                                            </button>
                                        </div>

                                    </form>
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
    <!-- Add Agent Modal -->
    <div class="modal fade" id="addAGENTFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-agent-form-data">
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

    <!-- Site logo Modal -->
    <div class="modal fade" id="sitelogo" tabindex="-1" aria-labelledby="sitelogoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sitelogoLabel">Site Logo Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="site_logo_modal" src="uploads/agent_gallery/<?= $site_logo; ?>" alt="No-Image-Found" class="rounded-3" width="150px" height="150px" />
                </div>
            </div>
        </div>
    </div>

    <!--Invoice logo Modal -->
    <div class="modal fade" id="invoicelogo" tabindex="-1" aria-labelledby="invoicelogoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoicelogoLabel">Invoice Logo Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="invoice_logo_modal" src="uploads/agent_gallery/<?= $invoice_logo; ?>" alt="No-Image-Found" class="rounded-3" width="150px" height="150px" />
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery first -->
    <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>

    <!-- Include other scripts that depend on jQuery -->
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/vendor/js/menu.js"></script>
    <script src="assets/vendor/libs/tagify/tagify.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="assets/js/parsley.min.js"></script>
    <script src="assets/js/easy-autocomplete.min.js"></script>
    <script src="assets/js/selectize/selectize.min.js"></script>
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>
    <script src="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/_jquery.dataTables.min.js"></script>
    <script src="assets/js/_dataTables.buttons.min.js"></script>
    <script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="assets/js/_js_buttons.html5.min.js"></script>
    <script src="assets/js/ckeditor5.js"></script>
    <script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/ckeditor5.js"></script>

    <script>
        CKEDITOR.ClassicEditor.create(document.getElementById("terms_condition"), {
            updateSourceElementOnDestroy: true,
            toolbar: {
                items: [
                    'exportPDF', 'exportWord', '|',
                    'findAndReplace', 'selectAll', '|',
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'undo', 'redo',
                    '-',
                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                    'alignment', '|',
                    'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                    'textPartLanguage', '|',
                    'sourceEditing', 'lineHeight'
                ],
                shouldNotGroupWhenFull: true
            },
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            heading: {
                options: [{
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            placeholder: '',
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            lineHeight: {
                options: [1, 1.2, 1.5, 2, 2.5, 3],
                supportAllValues: true
            },
            htmlSupport: {
                allow: [{
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: true
                }]
            },
            htmlEmbed: {
                showPreviews: true
            },
            mention: {
                feeds: [{
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }]
            },
            removePlugins: [
                'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
                'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
                'FormatPainter', 'TableOfContents'
            ]
        }).then(editor => {
            $('#update_submit_global_setting_btn').on('click', function() {
                editor.updateSourceElement();
                $('#terms_condition').parsley().validate();

                if ($('#terms_condition').parsley().isValid()) {
                    // Form submission logic
                } else {
                    // Handle validation errors
                }
            });
        }).catch(err => {
            console.error(err.stack);
        });

        $(document).ready(function() {
            // Initialize tooltip
            $('#copyBtn').tooltip({
                trigger: 'manual' // Manual trigger
            });

            // AJAX FORM SUBMIT
            $("#agent_configuration_setting").submit(function(event) {
                console.log("Form submitted"); // Check if the submit function is triggered
                var form = $('#agent_configuration_setting')[0];
                var data = new FormData(form);
                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_agent_configuration.php?type=agent_config',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 80000,
                    dataType: 'json',
                    encode: true,
                }).done(function(response) {
                    if (!response.success) {
                        // NOT SUCCESS RESPONSE
                        showToaster('Unable to Update Agent Configuration', 'error');
                    } else {
                        // SUCCESS RESPONSE
                        if (response.result == true) {
                            // RESULT SUCCESS
                            showToaster('Agent Configuration Updated', 'success');
                            setTimeout(function() {
                                window.location.href = response.redirect_URL;
                            }, 1000);
                        } else if (response.result == false) {
                            // RESULT FAILED
                            showToaster('Unable to Update Agent Configuration', 'error');
                        }
                    }
                    if (response == "OK") {
                        return true;
                    } else {
                        return false;
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX request failed", textStatus, errorThrown); // Log AJAX errors
                });
                event.preventDefault();
            });
        });

        // Function to copy referral code
        function copyReferralCode() {
            var referralCodeInput = document.getElementById("hidden_ref_link");
            referralCodeInput.select();
            referralCodeInput.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(referralCodeInput.value).then(function() {
                // Show tooltip
                var copyBtn = $('#copyBtn');
                copyBtn.tooltip('show');

                // Hide tooltip after 2 seconds
                setTimeout(function() {
                    copyBtn.tooltip('hide');
                }, 2000); // Hide tooltip after 2 seconds
            }, function(err) {
                console.error("Failed to copy text: ", err);
            });
        }

        function showToaster(message, type = 'warning', timeout = 0) {
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": timeout,
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr[type](message);
        }

        function validateAndPreviewImage(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const validFileTypes = ['image/jpeg', 'image/png'];
                if (!validFileTypes.includes(file.type)) {
                    showToaster('Invalid file type. Please select a JPG or PNG image.', 'warning');
                    input.value = ''; // Clear the input
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (input.id === 'site_logo_upload') {
                        $('#site_logo_modal').attr('src', e.target.result);
                    } else if (input.id === 'invoice_logo_upload') {
                        $('#invoice_logo_modal').attr('src', e.target.result);
                    }
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>