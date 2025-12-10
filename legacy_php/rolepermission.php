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
$current_page = 'rolepermission.php';
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
    <!-- Fonts -->
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
    <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" href="./assets/css/_1.13.6_css_jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/parsley_validation.css">
    <link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar  ">
        <div class="layout-container">
            <!-- Layout container -->
            <div class="layout-page">
                <!-- Menu -->
                <?php include_once('public/__sidebar.php'); ?>
                <!-- / Menu -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class=" d-flex justify-content-between align-items-center">
                            <h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
                            <?php include adminpublicpath('__breadcrumb.php'); ?>
                        </div>
                        <?php if (empty($_GET['route']) && $_GET['route'] == '') : ?>
                            <div class="d-flex justify-content-end p-1">
                                <span id="response_alert"></span>
                            </div>
                            <!-- Users List Table -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card p-0">
                                        <div class="card-header pb-3 d-flex justify-content-between">
                                            <h5 class="card-title mb-3">List of Role Permission</h5>
                                            <a href="rolepermission.php?route=add" class="btn btn-label-primary waves-effect">+ Add Role Permission</a>
                                        </div>
                                        <div class="card-body dataTable_select text-nowrap">
                                            <div class="text-nowrap table-responsive table-bordered">
                                                <table class="table table-hover " id="rolemenu_LIST">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">S.No</th>
                                                            <th scope="col">Action</th>
                                                            <th scope="col">Role Name</th>
                                                            <th scope="col">Status</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif (isset($_GET['route']) && ($_GET['route'] == 'add' || $_GET['route'] == 'edit')) :
                            $role_id = $_GET['role_id'];
                            if ($_GET['route'] == 'edit' && $role_id) :
                                $button_label = 'Update';
                                $title_label = 'Edit Role Permission';
                                $get_role_name = getRole($role_id, 'label');
                            else :
                                $button_label = 'Save';
                                $title_label = 'Add Role Permission';
                            endif;
                            if ($role_id <= 6) :
                                $get_role_disabled = 'readonly disabled';
                            else :
                                $get_role_disabled = '';
                            endif;
                        ?>
                            <div class="p-2">
                                <form id="ajax_rolemenu_add_form" action="" method="post" data-parsley-validate>
                                    <input type="hidden" name="hidden_ROLE_ID" id="hidden_ROLE_ID" value="<?= $role_id; ?>" />
                                    <div class="card p-5 ">

                                        <div class="modal-body">
                                            <!-- <div class="mb-4">
                                                    <h4 class="role-title mb-2">Add Role Permissions</h4>
                                                    <p class="text-muted">Set role permissions</p>
                                                </div> -->
                                            <!-- Add role form -->
                                            <div class="card-header pt-0 ps-0 pb-2 text-center border-0">
                                                <h4 class="card-title mx-auto"><?= $title_label; ?></h4>
                                            </div>
                                            <div class="col-5 mb-4">
                                                <label class="form-label text-dark" for="role_name">Role Name<span class="text-danger"> *</span></label>
                                                <input type="text" class="form-control" autofocus id="role_name" name="role_name" autocomplete="off" value="<?= $get_role_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-checkrolename data-parsley-checkrolename-message="Role Name Already Exists" <?= $get_role_disabled ?> />
                                                <input type="hidden" name="old_role_name" id="old_role_name" value="<?= $get_role_name; ?>" />
                                            </div>
                                            <div class="col-12">
                                                <h5>Role Permissions</h5>
                                                <!-- Permission table -->
                                                <div class="table-responsive">
                                                    <table class="table table-flush-spacing border" id="rolepermission">
                                                        <thead class="table-head">
                                                            <tr>
                                                                <th scope="col">S.No</th>
                                                                <th scope="col">Page Name</th>
                                                                <th scope="col">Read Access</th>
                                                                <th scope="col">Write Access</th>
                                                                <th scope="col">Modify Access</th>
                                                                <th scope="col">Full Access</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $pageIds = array();
                                                            $select_rolepermission_list_query = sqlQUERY_LABEL("SELECT `page_menu_id`,`page_title`,`page_name` FROM `dvi_pagemenu` WHERE `status` = '1' and `deleted` = '0'");
                                                            $num_row = sqlNUMOFROW_LABEL($select_rolepermission_list_query);
                                                            if ($num_row > 0) :
                                                                while ($fetch_records = sqlFETCHARRAY_LABEL($select_rolepermission_list_query)) :
                                                                    $pageId = $fetch_records['page_menu_id'];
                                                                    $pageIds[] = $pageId;
                                                                    $page_title = $fetch_records['page_title'];
                                                                    $page_name = $fetch_records['page_name'];
                                                                    $read_access = getROLEACCESS_DETAILS($role_id, $pageId, 'read_access');
                                                                    $write_access = getROLEACCESS_DETAILS($role_id, $pageId, 'write_access');
                                                                    $modify_access = getROLEACCESS_DETAILS($role_id, $pageId, 'modify_access');
                                                                    $full_access = getROLEACCESS_DETAILS($role_id, $pageId, 'full_access');

                                                                    if ($read_access == 1) :
                                                                        $read_access_status = 'checked';
                                                                    else :
                                                                        $read_access_status = '';
                                                                    endif;
                                                                    if ($write_access == 1) :
                                                                        $write_access_status = 'checked';
                                                                    else :
                                                                        $write_access_status = '';
                                                                    endif;
                                                                    if ($modify_access == 1) :
                                                                        $modify_access_status = 'checked';
                                                                    else :
                                                                        $modify_access_status = '';
                                                                    endif;
                                                                    if ($full_access == 1) :
                                                                        $full_access_status = 'checked';
                                                                    else :
                                                                        $full_access_status = '';
                                                                    endif;
                                                            ?>
                                                                    <tr>
                                                                        <td class="text-nowrap fw-medium ps-4"><?= $pageId; ?></td>
                                                                        <td class="text-nowrap fw-medium" name="page_name"><?= $page_title; ?></td>
                                                                        <td>
                                                                            <div class="form-check me-3 me-lg-5">
                                                                                <input class="form-check-input" type="checkbox" id="read_access_<?= $pageId; ?>" <?= $read_access_status; ?> name="role_read_access[<?= $pageId; ?>][]" />
                                                                                <input type="hidden" name="page_menu_id[]" value="<?= $pageId; ?>">
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check me-3 me-lg-5">
                                                                                <input class="form-check-input" type="checkbox" id="edit_access_<?= $pageId; ?>" <?= $write_access_status; ?> name="role_write_access[<?= $pageId; ?>][]" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check me-3 me-lg-5">
                                                                                <input class="form-check-input" type="checkbox" id="create_access_<?= $pageId; ?>" <?= $modify_access_status; ?> name="role_modify_access[<?= $pageId; ?>][]" />
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="form-check me-3 me-lg-5">
                                                                                <input class="form-check-input" type="checkbox" id="full_access_<?= $pageId; ?>" <?= $full_access_status; ?> name="role_full_access[<?= $pageId; ?>][]" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endwhile;
                                                            else : ?>
                                                                <tr>
                                                                    <td class="text-center" colspan='6'>No data Available</td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        </tbody>

                                                    </table>
                                                </div>
                                                <!-- Permission table -->
                                            </div>
                                            <div class=" text-center mt-5">
                                                <button type="submit" class="btn btn-primary float-end ms-2" id="rolepermission_form_submit"><?= $button_label; ?></button>
                                                <a class="btn btn-light float-start" href="rolepermission.php" data-bs-dismiss=" modal">Cancel</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--/ Add role form -->
                            </div>
                        <?php endif; ?>
                    </div>
                    <!-- Footer -->
                    <?php include_once('public/__footer.php'); ?>
                    <!-- / Footer -->
                </div>
            </div>


        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->

    </div>
    <!-- Content wrapper -->

    </div>
    <!-- / Layout page -->

    </div>
    <!-- Overlay -->

    </div>
    <!-- / Layout wrapper -->

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

    <script src="./assets/vendor/libs/%40form-validation/umd/bundle/popular.min.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/plugin-bootstrap5/index.min.js"></script>
    <script src="./assets/vendor/libs/%40form-validation/umd/plugin-auto-focus/index.min.js"></script>
    <script src="./assets/js/code.jquery.com_jquery-3.7.0.js"></script>
    <script src="./assets/js/_jquery.dataTables.min.js"></script>
    <script src="./assets/js/_dataTables.buttons.min.js"></script>
    <script src="./assets/js/_jszip_3.10.1_jszip.min.js"></script>
    <script src="./assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
    <script src="./assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
    <script src="./assets/js/_js_buttons.html5.min.js"></script>
    <script src="./assets/js/parsley.min.js"></script>
    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script src="assets/vendor/libs/toastr/toastr.js"></script>
    <script src="assets/js/footerscript.js"></script>
    <!-- Page JS -->
    <script src="./assets/js/app-access-roles.js"></script>
    <script src="./assets/js/modal-add-role.js"></script>
    <script>
        <?php foreach ($pageIds as $pageId) : ?>
            var fullAccessCheckbox<?= $pageId; ?> = document.getElementById("full_access_<?= $pageId; ?>");
            var readAccessCheckbox<?= $pageId; ?> = document.getElementById("read_access_<?= $pageId; ?>");
            var editAccessCheckbox<?= $pageId; ?> = document.getElementById("edit_access_<?= $pageId; ?>");
            var createAccessCheckbox<?= $pageId; ?> = document.getElementById("create_access_<?= $pageId; ?>");

            fullAccessCheckbox<?= $pageId; ?>.addEventListener("change", function() {
                updateAccessCheckboxes(<?= $pageId; ?>);
            });

            readAccessCheckbox<?= $pageId; ?>.addEventListener("change", function() {
                updateFullAccessCheckbox(<?= $pageId; ?>);
            });

            editAccessCheckbox<?= $pageId; ?>.addEventListener("change", function() {
                updateFullAccessCheckbox(<?= $pageId; ?>);
            });

            createAccessCheckbox<?= $pageId; ?>.addEventListener("change", function() {
                updateFullAccessCheckbox(<?= $pageId; ?>);
            });

            function updateAccessCheckboxes(pageId) {
                var fullAccess = document.getElementById("full_access_" + pageId);
                var readAccess = document.getElementById("read_access_" + pageId);
                var editAccess = document.getElementById("edit_access_" + pageId);
                var createAccess = document.getElementById("create_access_" + pageId);

                if (fullAccess.checked) {
                    // If full access is checked, check all three checkboxes
                    readAccess.checked = true;
                    editAccess.checked = true;
                    createAccess.checked = true;
                } else {
                    // If full access is unchecked, uncheck all three checkboxes
                    readAccess.checked = false;
                    editAccess.checked = false;
                    createAccess.checked = false;
                }
            }

            function updateFullAccessCheckbox(pageId) {
                var fullAccess = document.getElementById("full_access_" + pageId);
                var readAccess = document.getElementById("read_access_" + pageId);
                var editAccess = document.getElementById("edit_access_" + pageId);
                var createAccess = document.getElementById("create_access_" + pageId);

                if (readAccess.checked && editAccess.checked && createAccess.checked) {
                    // If all three checkboxes (read, edit, create) are checked, check full access
                    fullAccess.checked = true;
                } else {
                    // If any of the checkboxes is unchecked, uncheck full access
                    fullAccess.checked = false;
                }
            }
        <?php endforeach; ?>
    </script>
    <script>
        // $(document).ready(function() {
        //     $('#rolepermission').DataTable({
        //         dom: 't',
        //     });
        // });
        $(document).ready(function() {
            $('#rolemenu_LIST').DataTable({
                // dom: 'Blfrtip',
                "bFilter": true,
                ajax: {
                    "url": "engine/json/__JSONrolemenu.php",
                    "type": "GET"
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "modify"
                    }, //1
                    {
                        data: "role_name"
                    }, //2
                    {
                        data: "status"
                    }, //3

                ],
                columnDefs: [{
                        "targets": 3,
                        "data": "status",
                        "render": function(data, type, row, full) {
                            if (row.modify <= 6) {
                                switch (data) {
                                    case '1':
                                        return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked disabled onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                    case '0':
                                        return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" disabled onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                }
                            } else {
                                switch (data) {
                                    case '1':
                                        return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                    case '0':
                                        return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
                                }
                            }
                        }
                    },
                    {
                        "targets": 1,
                        "data": "modify",
                        "render": function(data, type, row, full) {
                            if (row.modify <= 6) {
                                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="rolepermission.php?route=edit&role_id=' + row.modify + '" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-danger flex-end disabled" href="javascript:void(0);" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a></div>';
                            } else {
                                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" href="rolepermission.php?route=edit&role_id=' + row.modify + '" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEROLEMENUMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a></div>';
                            }
                        }
                    }
                ]
            });
        });

        function togglestatusITEM(STATUS_ID, ROLE_ID) {
            if (ROLE_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_rolemenu.php?type=updatestatus",
                    data: {
                        ROLE_ID: ROLE_ID,
                        STATUS_ID: STATUS_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        // if (response.result == true) {
                        //     $('#rolemenu_LIST').DataTable().ajax.reload();
                        //     SUCCESS_ALERT(response.result_success);
                        // } else {
                        //     ERROR_ALERT(response.result_error);
                        // }
                        if (response.result == true) {
                            $('#rolemenu_LIST').DataTable().ajax.reload();
                            TOAST_NOTIFICATION('success', 'Status Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        } else {
                            TOAST_NOTIFICATION('error', 'Unable to Update the Status', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                });
            }
        }

        function showDELETEROLEMENUMODAL(ID) {
            $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_rolemenu.php?type=delete&ID=' + ID, function() {
                const container = document.getElementById("confirmDELETEINFODATA");
                const modal = new bootstrap.Modal(container);
                modal.show();
            });
        }

        function confirmROLEMENUDELETE(ID) {
            $.ajax({
                type: "POST",
                url: "engine/ajax/__ajax_manage_rolemenu.php?type=confirmdelete",
                data: {
                    _ID: ID
                },
                dataType: 'json',
                success: function(response) {
                    if (response.result == true) {
                        $('#rolemenu_LIST').DataTable().ajax.reload();
                        $('#confirmDELETEINFODATA').modal('hide');
                        TOAST_NOTIFICATION('success', 'Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                    } else {
                        TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
                    }
                    // if (!response.success) {
                    //     //NOT SUCCESS RESPONSE
                    //     if (response.result_error) {
                    //         // ERROR_ALERT(response.result_error);
                    //     }
                    // } else {
                    //     //SUCCESS RESPOSNE
                    //     if (response.response_result) {
                    //         // SUCCESS_ALERT(response.response_result);
                    //     }
                    //     $('#confirmDELETEINFODATA').modal('hide');
                    //     $('#rolemenu_LIST').DataTable().ajax.reload();
                    // }
                }
            });
        }

        $('#role_name').on('blur', function() {
            if (allFilled()) $('#rolepermission_form_submit').removeAttr('disabled');
        });
        // $('.page_allowed').on('click', function() {
        //     if (allFilled()) $('#rolepermission_form_submit').removeAttr('disabled');
        // });

        function allFilled() {
            var filled = true;
            $('body .form_required').each(function() {
                if ($(this).val() == '') filled = false;
            });
            return filled;
        }

        $(document).ready(function() {
            $('.modal').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
            });
            // //CHECK DUPLICATE ROLE NAME
            $('#role_name').parsley();
            var old_role_name = document.getElementById("old_role_name").value;
            window.ParsleyValidator.addValidator('checkrolename', {
                validateString: function(value) {
                    return $.ajax({
                        url: 'engine/ajax/__ajax_check_rolename.php',
                        method: "POST",
                        data: {
                            role_name: value,
                            old_role_name: old_role_name
                        },
                        dataType: "json",
                        success: function(data) {
                            return true;
                        }
                    });
                }
            });

            //AJAX FORM SUBMIT
            $("#ajax_rolemenu_add_form").submit(function(event) {
                var form = $('#ajax_rolemenu_add_form')[0];
                var data = new FormData(form);
                // $(this).find("button[type='submit']").prop('disabled', true);
                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_rolemenu.php?type=add',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 80000,
                    dataType: 'json',
                    encode: true,
                }).done(function(response) {
                    console.log(data);
                    if (!response.success) {
                        //NOT SUCCESS RESPONSE
                        if (response.errors.role_name_required) {
                            MODAL_ALERT(response.errors.role_name_required);
                            $('#role_name').focus();
                        } else if (response.errors.allow_access_page_required) {
                            MODAL_ALERT(response.errors.allow_access_page_required);
                        }
                    } else {
                        //SUCCESS RESPOSNE
                        if (response.i_result == true) {
                            $('#ajax_rolemenu_add_form')[0].reset();
                            TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            setTimeout(function() {
                                location.assign(response.redirect_URL);
                            }, 1000);
                        } else if (response.u_result == true) {
                            $('#ajax_rolemenu_add_form')[0].reset();
                            TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            setTimeout(function() {
                                location.assign(response.redirect_URL);
                            }, 1000);
                        } else {
                            //NOT SUCCESS RESPONSE
                            TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                        }
                    }
                    if (response == "OK") {
                        return true;
                    } else {
                        return false;
                    }
                });
                event.preventDefault();
            });
        });
    </script>

</body>

</html>