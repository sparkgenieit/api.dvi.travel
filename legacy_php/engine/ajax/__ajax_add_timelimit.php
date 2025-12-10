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

        $TIME_LIMIT_ID = $_GET['TIME_LIMIT_ID'];
        $VENDOR_ID = $_GET['VENDOR_ID'];
        $UPDATE_FROM = $_GET['UPDATE_FROM'];


        if ($TIME_LIMIT_ID != '' && $TIME_LIMIT_ID != 0) :
            $select_timelimit_details = sqlQUERY_LABEL("SELECT `time_limit_id`, `vendor_id`, `vendor_vehicle_type_id`, `time_limit_title`, `hours_limit`, `km_limit` FROM `dvi_time_limit` WHERE `deleted` = '0' AND `status` = '1' AND `time_limit_id` = '$TIME_LIMIT_ID' ") or die("#1-UNABLE_TO_COLLECT_TIME_LIMIT_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_timelimit_details)) :
                $TIME_LIMIT_ID = $fetch_data['time_limit_id'];
                $time_limit_title = $fetch_data['time_limit_title'];
                $hours_limit = $fetch_data['hours_limit'];
                $km_limit = $fetch_data['km_limit'];
                $vendor_id = $fetch_data['vendor_id'];
                $vendor_vehicle_type_id = $fetch_data['vendor_vehicle_type_id'];
                $vehicle_type = getVENDOR_VEHICLE_TYPES($logged_vendor_id, $vendor_vehicle_type_id, 'label');
            endwhile;
            $btn_label = 'Update';
        else :
            $vendor_id = $VENDOR_ID;
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_time_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="TIMELIMITFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>

            <input type="hidden" name="hiddenTIME_LIMIT_ID" id="hiddenTIME_LIMIT_ID" value="<?= $TIME_LIMIT_ID; ?>" hidden />
            <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_id; ?>" hidden />

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
                    <select id="vendor_vehicle_type" name="vendor_vehicle_type" required class="form-control form-select" data-parsley-required="true">
                        <?php if ($logged_vendor_id != "" || $logged_vendor_id != 0) : ?>
                            <?= getVENDOR_VEHICLE_TYPES($vendor_id, $vendor_vehicle_type_id, 'select') ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="kms_limit_title">Title<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="time_limit_title" name="time_limit_title" required class="form-control" placeholder="Enter Title" value="<?= $time_limit_title; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_time_limit_title data-parsley-check_time_limit_title-message="Entered  title Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_time_limit_title" id="old_time_limit_title" value="<?= $time_limit_title; ?>" />

                </div>
            </div>
            <div class="col-6">
                <label class="form-label w-100" for="hours_limit">Hours<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="hours_limit" name="hours_limit" required class="form-control" placeholder="Enter Hours" value="<?= $hours_limit; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_hours_limit data-parsley-check_hours_limit-message="Entered Hours and KM already exists" autocomplete="off" data-parsley-errors-container="#error_container1" />
                    <input type="hidden" name="old_hours_limit" id="old_hours_limit" value="<?= $hours_limit; ?>" />
                </div>
                <div id="error_container1"></div>
            </div>

            <div class="col-6">
                <label class="form-label w-100" for="km_limit">Kilometer(KM)<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="km_limit" name="km_limit" class="form-control" placeholder="KM Lmit" value="<?= $km_limit; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_km_limit data-parsley-check_km_limit-message="Entered Hours and KM already exists" autocomplete="off" data-parsley-errors-container="#error_container" />
                    <input type="hidden" name="old_km_limit" id="old_km_limit" value="<?= $km_limit; ?>" />
                </div>
                <div id="error_container"></div>
            </div>

            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class=" btn btn-label-github waves-effect" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="time_limit_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <!-- <div id="spinner"></div> -->
        <script src="assets/js/parsley.min.js"></script>
        <link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
        <script src="assets/js/selectize/selectize.min.js"></script>

        <script>
            $('#hours_limit, #km_limit').bind('keyup', function() {
                if (allFilled()) $('#time_limit_form_submit_btn').removeAttr('disabled');
            });

            $("select").selectize();

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


            $(document).ready(function() {


                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                //CHECK DUPLICATE TITLE
                $('#time_limit_title').parsley();
                window.ParsleyValidator.addValidator('check_time_limit_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_time_limit_title.php',
                            method: "POST",
                            data: {
                                time_limit_title: value,
                                old_time_limit_title: document.getElementById("old_time_limit_title").value
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //CHECK DUPLICATE KMS-HOURS
                $('#hours_limit').parsley();
                window.ParsleyValidator.addValidator('check_hours_limit', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_time_limit.php',
                            method: "POST",
                            data: {
                                hours_limit: value,
                                old_hours_limit: document.getElementById("old_hours_limit").value,
                                km_limit: document.getElementById("km_limit").value,
                                old_km_limit: document.getElementById("old_km_limit").value,
                                VENDOR_VEHICLE_TYPE_ID: document.getElementById("vendor_vehicle_type").value
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //CHECK DUPLICATE KMS-HOURS
                $('#km_limit').parsley();
                window.ParsleyValidator.addValidator('check_km_limit', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_time_limit.php',
                            method: "POST",
                            data: {
                                hours_limit: document.getElementById("hours_limit").value,
                                old_hours_limit: document.getElementById("old_hours_limit").value,
                                km_limit: value,
                                old_km_limit: document.getElementById("old_km_limit").value,
                                VENDOR_VEHICLE_TYPE_ID: document.getElementById("vendor_vehicle_type").value
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }

                });


                //AJAX FORM SUBMIT
                $("#ajax_time_details_form").submit(function(event) {
                    var form = $('#ajax_time_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_timelimit.php?type=add',
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
                            } else if (response.errors.time_limit_title_required) {
                                TOAST_NOTIFICATION('warning', 'Title Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hours_limit_required) {
                                TOAST_NOTIFICATION('warning', 'Hours Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.km_limit_required) {
                                TOAST_NOTIFICATION('warning', 'KM Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.time_limit_duplicated) {
                                TOAST_NOTIFICATION('warning', 'Already exist for this vehicle type', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }

                        } else {
                            //SUCCESS RESPOSNE
                            if (!response.result) {
                                TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else {
                                $('#time_limit_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Submited Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            }
                            $('#addTIMELIMITFORM').modal('hide');

                            <?php if ($UPDATE_FROM == 'PRICEBOOK') : ?>
                                reloadVehiclesContainer();
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

            function reloadVehiclesContainer() {
                $.ajax({
                    url: 'engine/ajax/__ajax_get_local_vehicle_price_details.php?type=show_form&ID=' + '<?= $VENDOR_ID ?>',
                    type: 'get',
                    success: function(data) {
                        $('#vehiclesContainer').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        </script>


<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>