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

        $inbuilt_amenity_type_id  = $_GET['AMENITY_TYPE_ID'];

        if ($inbuilt_amenity_type_id != '' && $inbuilt_amenity_type_id != 0) :

            $select_amenity_list = sqlQUERY_LABEL("SELECT `inbuilt_amenity_type_id`, `inbuilt_amenity_title` FROM `dvi_inbuilt_amenities` WHERE `deleted` = '0' AND `inbuilt_amenity_type_id` = '$inbuilt_amenity_type_id'") or die("#1-UNABLE_TO_COLLECT_InbuildAmenity_type_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_amenity_list)) :
                $inbuilt_amenity_type_id = $fetch_data['inbuilt_amenity_type_id'];
                $inbuilt_amenity_title = $fetch_data['inbuilt_amenity_title'];

            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <!-- Plugins css Ends-->
        <form id="ajax_inbuilt_amenity_type_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="AMENITYTYPEFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Inbuild Amenity Title<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="inbuilt_amenity_title" name="inbuilt_amenity_title" class="form-control form_required" placeholder="Enter the Inbuilt Amenity Title" value="<?= $inbuilt_amenity_title; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_inbuilt_amenity_title data-parsley-check_inbuilt_amenity_title-message="Amenity Type Title Already Exists" autocomplete="off" />
                    <input type="hidden" name="old_inbuilt_amenity_title" id="old_inbuilt_amenity_title" value="<?= $inbuilt_amenity_title; ?>" />
                    <input type="hidden" name="hiddeninbuilt_amenity_type_id" id="hiddeninbuilt_amenity_type_id" value="<?= $inbuilt_amenity_type_id; ?>" />
                </div>

            </div>
            <!-- <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">Availability<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="availability" name="availability" required class="form-control form_required" placeholder="Enter the availability" value="<?= $inbuilt_amenity_availability; ?>" data-parsley-whitespace="trim" data-parsley-type="number" autocomplete="off" required />
                </div>
            </div> -->
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="inbuild_amenity_type_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>
            $('#inbuilt_amenity_title').bind('keyup', function() {
                if (allFilled()) $('#inbuild_amenity_type_form_submit_btn').removeAttr('disabled');
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
                $('#inbuilt_amenity_title').parsley();
                var old_inbuilt_amenity_titleDETAIL = document.getElementById("old_inbuilt_amenity_title").value;
                var inbuilt_amenity_title = $('#inbuilt_amenity_title').val();
                window.ParsleyValidator.addValidator('check_inbuilt_amenity_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_inbuilt_amenity_title.php',
                            method: "POST",
                            data: {
                                inbuilt_amenity_title: value,
                                old_inbuilt_amenity_title: old_inbuilt_amenity_titleDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#ajax_inbuilt_amenity_type_details_form").submit(function(event) {
                    var form = $('#ajax_inbuilt_amenity_type_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_inbuild_amenity_type.php?type=add',
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
                            if (response.errors.inbuilt_amenity_title_required) {
                                MODAL_ALERT(response.errors.inbuilt_amenity_title_required);
                                $('#inbuilt_amenity_title').focus();
                            }
                            // else if (response.errors.amenity_availability_required) {
                            //     MODAL_ALERT(response.errors.amenity_availability_required);
                            //     $('#availability').focus();
                            // }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                $('#ajax_inbuilt_amenity_type_details_form')[0].reset();
                                $('#addAMENITYTYPEFORM').modal('hide');
                                $('#inbuild_amenity_type_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == true) {
                                //RESULT FAILED
                                $('#ajax_inbuilt_amenity_type_details_form')[0].reset();
                                $('#addAMENITYTYPEFORM').modal('hide');
                                $('#inbuild_amenity_type_LIST').DataTable().ajax.reload();
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