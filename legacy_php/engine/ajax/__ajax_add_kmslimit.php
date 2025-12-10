<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 4.0.1
* Copyright (c) 2010-2023 Touchmark Descience Pvt Ltd
*
*/

include_once('../../jackus.php');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'show_form') :

        $KMS_LIMIT_ID = $_GET['KMS_LIMIT_ID'];
        $VENDOR_ID = $_GET['VENDOR_ID'];
        $UPDATE_FROM = $_GET['UPDATE_FROM'];

        if ($KMS_LIMIT_ID != '' && $KMS_LIMIT_ID != 0) :
            $select_subject_details = sqlQUERY_LABEL("SELECT `kms_limit_id`,`vendor_vehicle_type_id`,`kms_limit_title`,`kms_limit`, `vendor_id` FROM `dvi_kms_limit` WHERE `deleted` = '0' AND `status` = '1' AND `kms_limit_id` = '$KMS_LIMIT_ID'") or die("#1-UNABLE_TO_COLLECT_KMS_LIMIT_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                $KMS_LIMIT_ID = $fetch_data['kms_limit_id'];
                $kms_limit_title = $fetch_data['kms_limit_title'];
                $kms_limit = $fetch_data['kms_limit'];
                $vendor_id = $fetch_data['vendor_id'];
                $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
                $vehicle_type = getVENDOR_VEHICLE_TYPES($logged_vendor_id, $vendor_vehicle_type_id, 'label');
            endwhile;
            $btn_label = 'Update';
            $vehicle_type_edit = "disabled";
        else :
            $vendor_id = $VENDOR_ID;
            $btn_label = 'Save';
            $vehicle_type_edit = "";
        endif;
?>
        <form id="ajax_kms_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="KMSLIMITFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>

            <?php /* if ($logged_vendor_id == "" || $logged_vendor_id == 0) : ?>
                <div class="col-12 mt-2">
                    <label class="form-label w-100" for="vendor_id">Vendor Name<span class=" text-danger"> *</span></label>
                    <select id="vendor_id" name="vendor_id" class="form-select form-control" data-parsley-trigger="keyup" onchange="showVEHICLE_TYPES();" required>
                        <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                    </select>
                </div>
            <?php endif; */ ?>

            <div class="col-12 mt-2">
                <label class="form-label w-100" for="vehicle_type">Vehicle type<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <?php if ($KMS_LIMIT_ID != '' && $KMS_LIMIT_ID != 0) : ?>
                        <input type="hidden" name="vendor_vehicle_type" id="vendor_vehicle_type" value="<?= $vendor_vehicle_type_id; ?>" hidden />
                    <?php endif; ?>
                    <select id="vendor_vehicle_type" name="vendor_vehicle_type" required class="form-control form-select" data-parsley-required="true" <?= $vehicle_type_edit ?>>
                        <?php if ($logged_vendor_id != "" || $logged_vendor_id != 0) : ?>
                            <?= getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'select') ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="kms_limit_title">Outstation KM Limit Title<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="kms_limit_title" name="kms_limit_title" required class="form-control" placeholder="Outstation KM Limit Title" value="<?= $kms_limit_title; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_kms_limit_title data-parsley-check_kms_limit_title-message="Entered kms limit title Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_kms_limit_title" id="old_kms_limit_title" value="<?= $kms_limit_title; ?>" />
                    <input type="hidden" name="hiddenKMS_LIMIT_ID" id="hiddenKMS_LIMIT_ID" value="<?= $KMS_LIMIT_ID; ?>" hidden />
                    <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_id; ?>" hidden />
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="kms_limit">Outstation KM Limit<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="kms_limit" name="kms_limit" required class="form-control" placeholder="Outstation KM Limit" value="<?= $kms_limit; ?>" required data-parsley-trigger="keyup" data-parsley-type-message="Only numbers are allowed" data-parsley-type="number" data-parsley-whitespace="trim" data-parsley-check_kms_limit data-parsley-check_kms_limit-message="Entered kms limit Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_kms_limit" id="old_kms_limit" value="<?= $kms_limit; ?>" />
                </div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-label-github waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="kms_limit_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <script src="assets/js/selectize/selectize.min.js"></script>

        <script>
            $('#vendor_vehicle_type ,#kms_limit_title, #kms_limit_title').bind('keyup', function() {
                if (allFilled()) $('#kms_limit_form_submit_btn').removeAttr('disabled');
            });

            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }

            function showVEHICLE_TYPES() {
                //var vendor_vehicle_selectize = $("#vendor_vehicle_type")[0].selectize;
                var vendor_id = $("#vendor_id").val();
                $.ajax({
                    url: 'engine/ajax/__ajax_get_vendor_vehicle_types.php?type=selectize_vehicle_types',
                    type: "POST",
                    data: {
                        vendor_id: vendor_id
                    },
                    success: function(response) {
                        // Append the response to the dropdown.
                        console.log("AJAX Response:", response); // Debug: Check the response
                        // Initialize Selectize if not already initialized
                        if (!$("#vendor_vehicle_type")[0].selectize) {
                            $("#vendor_vehicle_type").selectize();
                        }
                        // Update options and set value
                        $("#vendor_vehicle_type")[0].selectize.clear();
                        $("#vendor_vehicle_type")[0].selectize.clearOptions();
                        $("#vendor_vehicle_type")[0].selectize.addOption(response);
                        $("#vendor_vehicle_type")[0].selectize.setValue(response[0].value);
                    }
                });
            }

            function reloadoutsideVehiclesContainer() {
                $.ajax({
                    url: 'engine/ajax/__ajax_get_outside_vehicle_price_details.php?type=show_form&ID=' + '<?= $VENDOR_ID ?>',
                    type: 'get',
                    success: function(data) {
                        $('#outstationVehiclesContainer').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            $(document).ready(function() {

                $("select").selectize();

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //CHECK DUPLICATE KMS LIMIT TITLE
                $('#kms_limit_title').parsley();
                var old_kms_limit_titleDETAIL = document.getElementById("old_kms_limit_title").value;
                var kms_limit_title = $('#kms_limit_title').val();
                window.ParsleyValidator.addValidator('check_kms_limit_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_kms_limit_title.php',
                            method: "POST",
                            data: {
                                kms_limit_title: value,
                                old_kms_limit_title: old_kms_limit_titleDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_kms_details_form").submit(function(event) {
                    var form = $('#ajax_kms_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_kmslimit.php?type=add',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        //console.log(data);
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errors.vendor_vehicle_type_required) {
                                TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.kms_limit_title_required) {
                                TOAST_NOTIFICATION('warning', 'KM Limit Title Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.kms_limit_required) {
                                TOAST_NOTIFICATION('warning', 'KM Limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.kms_limit_duplicated) {
                                TOAST_NOTIFICATION('warning', 'KM Limit already exist for this vehicle type', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (!response.result) {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                $('#kms_limit_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'KM Limit Submitted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                            $('#addKMSLIMITFORM').modal('hide');
                            <?php if ($UPDATE_FROM == 'PRICEBOOK') : ?>
                                reloadoutsideVehiclesContainer();
                            <?php endif; ?>
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
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>