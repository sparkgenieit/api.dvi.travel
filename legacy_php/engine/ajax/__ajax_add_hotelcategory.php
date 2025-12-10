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

        $HOTEL_CATEGORY_ID = $_GET['HOTEL_CATEGORY_ID'];

        if ($HOTEL_CATEGORY_ID != '' && $HOTEL_CATEGORY_ID != 0) :
            $select_subject_details = sqlQUERY_LABEL("SELECT `hotel_category_id`, `hotel_category_title` , `hotel_category_code` FROM `dvi_hotel_category` WHERE `deleted` = '0'  AND `hotel_category_id` = '$HOTEL_CATEGORY_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_subject_details)) :
                $HOTEL_CATEGORY_ID = $fetch_data['hotel_category_id'];
                $hotel_category_title = $fetch_data['hotel_category_title'];
                $hotel_category_code = $fetch_data['hotel_category_code'];
            endwhile;
            $btn_label = 'Update';
        else :
            $btn_label = 'Save';
        endif;
?>
        <form id="ajax_hotel_category_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2" id="HOTELCATEGORYFORMLabel"></h4>
            </div>
            <span id="response_modal"></span>
            <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Hotel Category Title<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="hotel_category_title" name="hotel_category_title" class="form-control" placeholder="Enter the Hotel Category Title" value="<?= $hotel_category_title; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_hotel_category_title data-parsley-check_hotel_category_title-message="Hotel Category Title Already Exists" autocomplete="off" onchange="GENERATE_HOTEL_CATEGORY_CODE()" data-parsley-trigger="keyup" />
                    <input type="hidden" name="old_hotel_category_title" id="old_hotel_category_title" value="<?= $hotel_category_title; ?>" />
                    <input type="hidden" name="hiddenHOTEL_CATEGORY_ID" id="hiddenHOTEL_CATEGORY_ID" value="<?= $HOTEL_CATEGORY_ID; ?>" hidden />
                </div>
            </div>
            <div class="col-12">
                <label class="form-label w-100" for="modalAddCardCvv">Hotel Category Code<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="hotel_category_code" name="hotel_category_code" required class="form-control" placeholder="Enter the Hotel Category Code" value="<?= $hotel_category_code; ?>" readonly required />
                    <input type="hidden" name="old_hotel_category_code" id="old_hotel_category_code" value="<?= $hotel_category_code; ?>" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn hotel_category_add_form" id="hotel_category_form_submit_btn"><?= $btn_label; ?></button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            $('#hotel_category_title, #hotel_category_title').bind('keyup', function() {
                if (allFilled()) $('#hotel_category_form_submit_btn').removeAttr('disabled');
            });


            function allFilled() {
                var filled = true;
                $('body .form_required').each(function() {
                    if ($(this).val() == '') filled = false;
                });
                return filled;
            }

            function GENERATE_HOTEL_CATEGORY_CODE() {
                var hotel_category_title = $("#hotel_category_title").val();
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_generate_code.php?type=show_hotel_category_code',
                    type: "post",
                    data: {
                        hotel_category_id: '<?= $HOTEL_CATEGORY_ID; ?>',
                        hotel_category_title: hotel_category_title
                    },
                    success: function(response) {
                        $("#hotel_category_code").val(response);
                    }
                });
            }


            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });



                //CHECK DUPLICATE hotel category TITLE
                $('#hotel_category_title').parsley();
                var old_hotel_category_titleDETAIL = document.getElementById("old_hotel_category_title").value;
                var hotel_category_title = $('#hotel_category_title').val();
                window.ParsleyValidator.addValidator('check_hotel_category_title', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_hotelcategory_title.php',
                            method: "POST",
                            data: {
                                hotel_category_title: value,
                                old_hotel_category_title: old_hotel_category_titleDETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });
                //AJAX FORM SUBMIT
                $("#ajax_hotel_category_details_form").submit(function(event) {
                    var form = $('#ajax_hotel_category_details_form')[0];
                    var data = new FormData(form);
                    var spinner = $("#spinner");
                    console.log(data);
                    $(this).find("button[type='submit']").prop('disabled', true);
                    // spinner.show();
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_hotelcategory.php?type=add',
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
                            if (response.errors.hotel_category_code) {
                                MODAL_ALERT(response.errors.hotel_category_code_required);
                                $('#hotel_category_code').focus();
                            } else if (response.errors.hotel_category_code_already_exist) {
                                MODAL_ALERT(response.errors.hotel_category_code_already_exist);
                                $('#hotel_category_code').focus();
                            } else if (response.errors.hotel_category_title) {
                                MODAL_ALERT(response.errors.hotel_category_title_required);
                                $('#hotel_category_title').focus();
                            } else if (response.errors.hotel_category_title_already_exist) {
                                MODAL_ALERT(response.errors.hotel_category_title_already_exist);
                                $('#hotel_category_title').focus();
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            spinner.hide();
                            // if (response.result == true) {
                            //     //RESULT SUCCESS
                            //     $('#ajax_hotel_category_details_form')[0].reset();
                            //     $('#addHOTELCATEGORYFORM').modal('hide');
                            //     $('#hotel_category_LIST').DataTable().ajax.reload();
                            //     SUCCESS_ALERT(response.result_success);
                            // } else if (response.result == false) {
                            //     //RESULT FAILED
                            //     ERROR_ALERT(response.result_error);
                            // }
                            if (!response.success) {
                                //NOT SUCCESS RESPONSE
                                if (response.result_success) {
                                    TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                }
                            } else {
                                //SUCCESS RESPOSNE
                                $('#ajax_hotel_category_details_form')[0].reset();
                                $('#addHOTELCATEGORYFORM').modal('hide');
                                $('#hotel_category_LIST').DataTable().ajax.reload();
                                TOAST_NOTIFICATION('success', 'submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                // $('#room_' + ROOM_ID).remove();
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