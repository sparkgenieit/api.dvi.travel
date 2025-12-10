<?php
include_once("jackus.php");
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?>
                                <?php if (isset($_GET['route']) && $_GET['route'] != 'add'): ?>
                                    » <b><?= getAGENT_details($_GET['id'], '', 'label'); ?></b>
                                <?php endif; ?>
                            </h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <span id="showAGENTLIST"></span>
                        <span id="showAGENTFORM"></span>
                        <span id="showAGENTSTAFFFORM"></span>
                        <span id="showADDAGENTSTAFFFORM"></span>
                        <span id="showAGENTWALLETFORM"></span>
                        <span id="showAGENTINVOICEFORM"></span>
                        <span id="showAGENTCONFIGFORM"></span>
                        <span id="showAGENTPREVIEW"></span>
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

    <div class="modal fade" id="addSTAFFFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-staff-form-data">
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
    <!-- Selectize JS and Autocomplete JS -->
    <script src="assets/js/selectize/selectize.min.js"></script>
    <!-- Flat Picker -->
    <script src="assets/vendor/libs/moment/moment.js"></script>
    <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <!-- Form Validation -->
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
    <script src="assets/js/jquery.easy-autocomplete.min.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>
    <script>
        <?php if ($_GET['route'] == '') : ?>
            // Your code specific to this condition
            show_AGENT_LIST();
        <?php elseif (($_GET['route'] == 'edit') && $_GET['formtype'] == 'agent_info') : ?>
            show_AGENT_FORM('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php elseif (($_GET['route'] == 'edit') && $_GET['formtype'] == 'agent_staff') : ?>
            show_AGENTSTAFF_FORM('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'agent_add_staff') : ?>
            show_ADDAGENTSTAFF_FORM('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>', '<?= $_GET['staffid']; ?>');
        <?php elseif (($_GET['route'] == 'edit') && $_GET['formtype'] == 'agent_wallet') : ?>
            show_AGENTWALLET_FORM('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php elseif (($_GET['route'] == 'edit') && $_GET['formtype'] == 'agent_invoice') : ?>
            show_AGENTINVOICE_FORM('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php elseif (($_GET['route'] == 'edit') && $_GET['formtype'] == 'agent_config') : ?>
            show_AGENTCONFIG_FORM('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php elseif ($_GET['route'] == 'preview'  && $_GET['formtype'] == 'preview') : ?>
            show_AGENT_PREVIEW('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
        <?php endif; ?>

        function show_AGENT_LIST() {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_agent_list.php',
                success: function(response) {
                    $('#showAGENTLIST').html(response);
                    $('#showAGENTFORM').html('');
                    $('#showAGENTPREVIEW').html('');
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html('');
                }
            });
        }

        function show_AGENT_FORM(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=agent_info',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html(response);
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html('');
                    $('#showAGENTPREVIEW').html('');
                }
            });
        }

        function show_AGENTSTAFF_FORM(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=agent_staff',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html('');
                    $('#showAGENTSTAFFFORM').html(response);
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html('');
                    $('#showAGENTPREVIEW').html('');
                }
            });
        }

        function show_ADDAGENTSTAFF_FORM(TYPE, ID, STAFFID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=agent_add_staff',
                data: {
                    ID: ID,
                    STAFFID: STAFFID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html('');
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html(response);
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html('');
                    $('#showAGENTPREVIEW').html('');
                }
            });
        }

        function show_AGENTWALLET_FORM(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=agent_wallet',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html('');
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html(response);
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html('');
                    $('#showAGENTPREVIEW').html('');
                }
            });
        }

        function show_AGENTINVOICE_FORM(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=agent_invoice',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html('');
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html(response);
                    $('#showAGENTCONFIGFORM').html('');
                    $('#showAGENTPREVIEW').html('');
                }
            });
        }

        function show_AGENTCONFIG_FORM(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=agent_config',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html('');
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html(response);
                    $('#showAGENTPREVIEW').html('');
                }
            });
        }

        function show_AGENT_PREVIEW(TYPE, ID) {
            $.ajax({
                type: 'post',
                url: 'engine/ajax/__ajax_add_agent_form.php?type=preview',
                data: {
                    ID: ID,
                    TYPE: TYPE
                },
                success: function(response) {
                    $('#showAGENTLIST').html('');
                    $('#showAGENTFORM').html('');
                    $('#showAGENTSTAFFFORM').html('');
                    $('#showADDAGENTSTAFFFORM').html('');
                    $('#showAGENTWALLETFORM').html('');
                    $('#showAGENTINVOICEFORM').html('');
                    $('#showAGENTCONFIGFORM').html('');
                    $('#showAGENTPREVIEW').html(response);
                }
            });
        }
        //STATUS UPDATE
        function togglestatusITEM(STATUS_ID, AGENT_ID) {
            if (AGENT_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_agent.php?type=updatestatus",
                    data: {
                        AGENT_ID: AGENT_ID,
                        STATUS_ID: STATUS_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.result == true) {
                            $('#agent_LIST').DataTable().ajax.reload();
                            TOAST_NOTIFICATION('success', 'Status Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Update the Sttaus', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        }
    </script>
</body>

</html>