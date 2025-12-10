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
$current_page = 'pagemenu.php'; // Set the current page variable
admin_reguser_protect();
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title> Pagemenu </title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

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
    <link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" />

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
    <link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
    <link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

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
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/itineary_custom_style.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
    <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
    <link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
    <link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

</head>

<body>
    <div class="layout-wrapper layout-content-navbar  ">
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
                                <h4 class="font-weight-bold">Page Menu</h4>
                            </div>
                            <div class="my-3">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#">
                                                <i class="tf-icons ti ti-home mx-2"></i>
                                            </a>
                                        </li>
                                        <!-- <li class="breadcrumb-item" aria-current="page">Hotels</li>
                                        <li class="breadcrumb-item" aria-current="page">Configuration</li> -->

                                        <li class="breadcrumb-item active" aria-current="page">Page Menu </li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end p-1">
                            <span id="response_alert"></span>
                        </div>


                        <!-- Users List Table -->
                        <div class="card p-3">
                            <div class="card-header border-bottom d-flex justify-content-between">
                                <h5 class="card-title mb-3">List of Page Menu</h5>
                                <a href="javascript:void(0)" class="btn rounded-pill btn-label-primary waves-effect" onclick="showPAGEMODAL(0);" data-bs-dismiss="modal">+ Add Page Menu</a>
                            </div>
                            <div>
                                <div class="m-2 p-3 table-responsive">
                                    <table class="table table-hover" id="pagemenu_LIST">
                                        <thead class="table-head">
                                            <tr>
                                                <th scope="col">S.No</th>
                                                <th scope="col">Page Title</th>
                                                <th scope="col">Page Name</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
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
    <!-- Add Amenities Category Modal -->
    <div class="modal fade" id="addPAGEMENUFORM" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
            <div class="modal-content p-3 p-md-5">
                <div class="receiving-pagemenu-form-data">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>
    <!--Delte Amenities Category Modal -->
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

    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets/vendor/libs/hammer/hammer.js"></script>
    <script src="./assets/vendor/libs/i18n/i18n.js"></script>
    <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

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
    <script src="./assets/vendor/libs/toastr/toastr.js"></script>
    <script src="./assets/js/footerscript.js"></script>
    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            $('#pagemenu_LIST').DataTable({
                dom: '<"row align-items-center"<"col-md-6" l><"col-md-6" f>><"table-responsive my-3" rt><"row align-items-center" <"col-md-6" i><"col-md-6" p>><"clear">',
                ajax: {
                    "url": "engine/json/__JSONpagemenu.php",
                    "type": "GET"
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "page_title"
                    }, //1
                    {
                        data: "page_name"
                    }, //2
                    {
                        data: "status"
                    }, //3
                    {
                        data: "modify"
                    } //4
                ],
                columnDefs: [{
                    "targets": 3,
                    "data": "status",
                    "render": function(data, type, row, full) {
                        switch (data) {
                            case '1':
                                return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                break;
                            case '0':
                                return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input"  onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                break;
                        }
                    }
                }, {
                    "targets": 4,
                    "data": "modify",
                    "render": function(data, type, full) {
                        return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="javascript:void(0);" onclick="showPAGEMODAL(' + data + ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEPAGEMENUMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                    }
                }],
            });
        });

        function showPAGEMODAL(PAGEMENU_ID) {
            $('.receiving-pagemenu-form-data').load('engine/ajax/__ajax_add_pagemenu_form.php?type=show_form&PAGEMENU_ID=' + PAGEMENU_ID + '', function() {
                const container = document.getElementById("addPAGEMENUFORM");
                const modal = new bootstrap.Modal(container);
                modal.show();
                if (PAGEMENU_ID) {
                    $('#addPAGEMENUFORMLabel').html('Edit Page Menu');
                } else {
                    $('#addPAGEMENUFORMLabel').html('Add Page Menu');
                }
            });
        }

        function togglestatusITEM(STATUS_ID, PAGEMENU_ID) {
            if (PAGEMENU_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_pagemenu.php?type=updatestatus",
                    data: {
                        PAGEMENU_ID: PAGEMENU_ID,
                        STATUS_ID: STATUS_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status_result_success == true) {
                            $('#pagemenu_LIST').DataTable().ajax.reload();
                            // Show the toast notification
                            TOAST_NOTIFICATION('success', 'Status Updated Successfully!!!', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);
                        } else if (response.status_result_success == false) {
                            // Show the toast notification
                            TOAST_NOTIFICATION('error', 'Unable to Update Status', 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
                        }
                    }
                });
            }
        }

        function showDELETEPAGEMENUMODAL(ID) {
            $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_pagemenu.php?type=delete&ID=' + ID, function() {
                const container = document.getElementById("confirmDELETEINFODATA");
                const modal = new bootstrap.Modal(container);
                modal.show();
            });
        }

        function confirmPAGEMENUDELETE(ID) {
            $.ajax({
                type: "POST",
                url: "engine/ajax/__ajax_manage_pagemenu.php?type=confirmdelete",
                data: {
                    _ID: ID
                },
                dataType: 'json',
                success: function(response) {
                    if (!response.success) {
                        //NOT SUCCESS RESPONSE
                        if (response.delete_result_success == false) {
                            // Show the toast notification
                            TOAST_NOTIFICATION('error', 'Unable to Delete Success', 'Error !!!', '', '', '', '', '', '', '', '', '', 5000);
                        }
                    } else {
                        //SUCCESS RESPOSNE
                        if (response.delete_result_success == true) {
                            $('#pagemenu_LIST').DataTable().ajax.reload();
                            // Show the toast notification
                            TOAST_NOTIFICATION('success', 'Record Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '', 5000);
                        }
                        $('#confirmDELETEINFODATA').modal('hide');
                        $('#pagemenu_LIST').DataTable().ajax.reload();
                    }
                }
            });
        }
    </script>
</body>

</html>