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

        $agent_ID = $_GET['AGENT_ID'];

?>
        <!-- Plugins css Ends-->
        <form id="subscription_details_form" class="row g-3" action="" method="post" data-parsley-validate>
            <div class="text-center">
                <h4 class="mb-2">Add Subscription</h4>
            </div>
            <span id="response_modal"></span>
             <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Description<span class=" text-danger"> *</span></label>
                <div class="form-group">
                   <textarea rows="2" id="description_subscripe" name="description_subscripe" class="form-control" placeholder="Enter the Total Days" required> </textarea>
                </div>
            </div>
             <div class="col-12 mt-2">
                <label class="form-label w-100" for="modalAddCard">Total Days<span class=" text-danger"> *</span></label>
                <div class="form-group">
                    <input type="text" id="total_days" name="total_days" data-parsley-type="number" class="form-control" placeholder="Enter the Total Days" required autocomplete="off" />
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between text-center pt-4">
                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                <button type="submit" class="btn btn-primary" id="vehicle_type_form_submit_btn">Save</button>
            </div>
        </form>
        <div id="spinner"></div>
        <script src="assets/js/parsley.min.js"></script>

        <script>

            $(document).ready(function() {

                $('.modal').on('shown.bs.modal', function() {
                    $(this).find('[autofocus]').focus();
                });

                $("#subscription_details_form").submit(function(event) {
                    var form = $('#subscription_details_form')[0];
                    var data = new FormData(form);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_subscription_plan.php?type=edit&id='+ <?= $agent_ID;?>,
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

                            if (response.errors.description_subscripe_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Your First Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#description_subscripe').focus();
                            } else if (response.errors.total_days_required) {
                                TOAST_NOTIFICATION('error', 'Please Enter Your Last Name !!!', 'Error !!!', '', '', '', '', '', '', '', '', '');
                                $('#total_days').focus();
                            } 

                        } else {
                            //SUCCESS RESPOSNE
                            if (response.result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Subscription Plan Successfully Updated !', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('error', 'Unable to Update Subscription Plan !', 'Error !!!', '', '', '', '', '', '', '', '', '');

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