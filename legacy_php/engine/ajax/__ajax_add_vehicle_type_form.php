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

        $vehicle_type_ID = $_GET['VEHICLE_TYPE_ID'];


        if ($vehicle_type_ID != '' && $vehicle_type_ID != 0) :

            $select_vehicle_list = sqlQUERY_LABEL("SELECT `vehicle_type_id`, `vehicle_type_title` , `occupancy` FROM `dvi_vehicle_type` WHERE `deleted` = '0' AND `vehicle_type_id` = '$vehicle_type_ID'") or die("#1-UNABLE_TO_COLLECT_vehicle_type_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicle_list)) :
                $vehicle_type_Id = $fetch_data['vehicle_type_id'];
                $vehicle_type_title = $fetch_data['vehicle_type_title'];
                $occupancy = $fetch_data['occupancy'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <!-- Plugins css Ends-->
        <form id="ajax_vehicle_type_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="VEHICLETYPEFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Vehicle Type Title<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="vehicle_type_title" name="vehicle_type_title" class="form-control" placeholder="Enter the Vehicle Type Title" value="<?= $vehicle_type_title; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_vehicle_type_title data-parsley-check_vehicle_type_title-message="Vehicle Type Title Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_vehicle_type_title" id="old_vehicle_type_title" value="<?= $vehicle_type_title; ?>" />
                </div>

            </div>
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">Occupancy<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="occupancy" min="1" name="occupancy" required class="form-control" placeholder="Enter the occupancy" value="<?= $occupancy; ?>" data-parsley-whitespace="trim" data-parsley-type="number" autocomplete="off" required />
                    <input type="hidden" name="hiddenVEHICLE_TYPE_ID" id="hiddenVEHICLE_TYPE_ID" value="<?= $vehicle_type_Id; ?>" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="vehicle_type_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $('#vehicle_type_title, #vehicle_type_title').bind('keyup', function() {
                if (allFilled()) $('#vehicle_type_form_submit_btn').removeAttr('disabled');
            });


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

                //CHECK DUPLICATE hotel category TITLE
                $('#vehicle_type_title').parsley();
                var old_vehicle_type_titleDETAIL = document.getElementById("old_vehicle_type_title").value;
                var vehicle_type_title = $('#vehicle_type_title').val();
                window.ParsleyValidator.addValidator('check_vehicle_type_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_vehicle_type_title.php',
                            method: "POST",
                            data: {
                                vehicle_type_title: value,
                                old_vehicle_type_title: old_vehicle_type_titleDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_vehicle_type_details_form").submit(function(event) {
                    var form = $('#ajax_vehicle_type_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_vehicle_type.php?type=add',
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
                            spinner.hide();
                            //NOT SUCCESS RESPONSE
                            if (response.errors.vehicle_type_code) {
                                MODAL_ALERT(response.errors.vehicle_type_code_required);
                                $('#vehicle_type_code').focus();
                            } else if (response.errors.vehicle_type_code_already_exist) {
                                MODAL_ALERT(response.errors.vehicle_type_code_already_exist);
                                $('#vehicle_type_code').focus();
                            } else if (response.errors.vehicle_type_title) {
                                MODAL_ALERT(response.errors.vehicle_type_title_required);
                                $('#vehicle_type_title').focus();
                            } else if (response.errors.vehicle_type_title_already_exist) {
                                MODAL_ALERT(response.errors.vehicle_type_title_already_exist);
                                $('#vehicle_type_title').focus();
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#ajax_vehicle_type_details_form')[0].reset();
                                $('#addVEHICLETYPEFORM').modal('hide');
                                $('#vehicle_type_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == true) {
                                //RESULT FAILED
                                $('#ajax_vehicle_type_details_form')[0].reset();
                                $('#addVEHICLETYPEFORM').modal('hide');
                                $('#vehicle_type_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.result == false) {
                                //RESULT FAILED
                                ERROR_ALERT(response.result_error);
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
<?php
    endif;
else :
    echo "Request Ignored !!!";
endif;
?>