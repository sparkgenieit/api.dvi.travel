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

    if ($_GET['type'] == 'basic_info') :

        $vendor_ID = $_GET['ID'];

        if ($vendor_ID != '' && $vendor_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `vendor_code`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_email`, `vendor_gstin`, `vendor_gstin_address`, `vendor_pan_card`, `vendor_faxnumber`,`gst_pincode`, `vendor_country_id`, `vendor_state_id`, `vendor_city_id`, `vendor_address`, `vendor_pincode`, `gst_country`, `gst_state`, `gst_city`,`status` FROM `dvi_vendor_details` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $vendor_name = $fetch_list_data['vendor_name'];
                $vendor_code = $fetch_list_data['vendor_code'];
                $vendor_primary_mobile_number = $fetch_list_data['vendor_primary_mobile_number'];
                $vendor_alternative_mobile_number = $fetch_list_data['vendor_alternative_mobile_number'];
                $vendor_email = $fetch_list_data['vendor_email'];
                $vendor_gstin = $fetch_list_data['vendor_gstin'];
                $vendor_gstin_address = $fetch_list_data["vendor_gstin_address"];
                $vendor_pan_card = $fetch_list_data['vendor_pan_card'];
                $vendor_faxnumber = $fetch_list_data["vendor_faxnumber"];
                $vendor_country_id = $fetch_list_data["vendor_country_id"];
                $vendor_state = $fetch_list_data["vendor_state_id"];
                $vendor_city = $fetch_list_data["vendor_city_id"];
                $vendor_address = $fetch_list_data["vendor_address"];
                $vendor_pincode = $fetch_list_data["vendor_pincode"];
                $gst_country = $fetch_list_data["gst_country"];
                $gst_state = $fetch_list_data["gst_state"];
                $gst_city = $fetch_list_data["gst_city"];
                $status = $fetch_list_data['status'];
                $gst_pincode = $fetch_list_data['gst_pincode'];

            endwhile;
            $select_vendor_credientials = sqlQUERY_LABEL("SELECT `userID`, `vendor_id`, `user_profile`, `username`, `password`, `roleID` FROM `dvi_users` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_vendor_credientials)) :
                $vendor_select_role = $fetch_list_data['roleID'];
                $vendor_username = $fetch_list_data['username'];
                $vendor_password = $fetch_list_data['password'];
            endwhile;
            $btn_label = 'Update & Continue';
        else :
            $btn_label = 'Save & Continue';
        endif;
        if ($vendor_ID != '' && $vendor_ID != 0) :
            $basic_info_url = 'vendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
            $vendor_branch_url = 'vendor.php?route=add&formtype=vendor_branch&id=' . $vendor_ID;
            $vendor_branch_list_url = 'vendor.php?route=add&formtype=branch_list&id=' . $vendor_ID;
            $vendor_permit_cost_url = 'vendor.php?route=add&formtype=permit_cost_list&ID=' . $vendor_ID;
            $preview_url = 'vendor.php?route=add&formtype=vendor_add_preview&ID=' . $vendor_ID;
        else :
            $basic_info_url = 'javascript:;';
            $vendor_branch_url = 'javascript:;';
            $vendor_branch_list_url = 'javascript:;';
            $vendor_permit_cost_url = 'javascript:;';
            $preview_url = 'javascript:;';
        endif;

?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-start py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  active-stepper">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title">Vendor Basic Info</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_branch_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Branch Details</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_branch_list_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Vehicle</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_permit_cost_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Permit Cost</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $preview_url; ?>" type="button" class="step-trigger">
                                <span class="bs-stepper-circle disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card p-4">
                    <form class="" id="form_vendor_basic" method="post" enctype="multipart/form-data" data-parsley-validate>
                        <div class="row g-3">
                            <h5 class="text-primary mt-3 mb-0">Basic Details</h5>
                            <div class="col-md-4">
                                <label class="form-label" for="modalAddCard">Vendor Name<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Enter Vendor Name" value="<?= $vendor_name; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="Vendor_email">Email ID<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="Vendor_email" id="Vendor_email" class="form-control" placeholder="Enter the Email" value="<?= $vendor_email; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_select_role">Role Permission<span class="text-danger">*</span></label>
                                <select class="form-select" name="vendor_select_role" id="vendor_select_role" value="<?= $vendor_select_role; ?>">
                                    <?= getRole($vendor_select_role, 'select'); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_primary_mobile">Primary Mobile<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_primary_mobile" id="vendor_primary_mobile" class="form-control" placeholder="Enter the Mobile Number" maxlength="10" value="<?= $vendor_primary_mobile_number; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_alternative_mobile">Alternative Mobile<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_alternative_mobile" id="vendor_alternative_mobile" class="form-control" placeholder="Enter the Mobile Number" maxlength="10" value="<?= $vendor_alternative_mobile_number; ?>" required />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="vendor_country">Country<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select class="form-select" name="vendor_country" id="vendor_country" onchange="CHOOSEN_COUNTRY()" data-parsley-trigger="keyup">
                                        <?= getCOUNTRYLIST($vendor_country_id, 'select_country'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_state">State<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select class="form-select" name="vendor_state" id="vendor_state" value="<?= $vendor_state_id; ?>" onchange="CHOOSEN_STATE()" data-parsley-trigger="keyup">
                                        <option value="">Choose state</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_city">City<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select class="form-select" name="vendor_city" id="vendor_city" value="<?= $vendor_city_id; ?>" data-parsley-trigger="keyup">
                                        <option value="">Please Choosen City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_pincode">Pincode<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_pincode" id="vendor_pincode" class="form-control" placeholder="Enter the Pincode" value="<?= $vendor_pincode; ?>" required />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_fax_num">Fax Number</label>
                                <div class="form-group">
                                    <input type="text" name="vendor_fax_num" id="vendor_fax_num" class="form-control" placeholder="Enter the Fax Number" value="<?= $vendor_faxnumber; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_username">Username<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_username" id="vendor_username" class="form-control" placeholder="Enter the Username" value="<?= $vendor_username; ?>" readonly />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_password">Password<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_password" id="vendor_password" class="form-control" placeholder="Enter the Password" value="<?= $vendor_password; ?>" readonly />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_address">Address<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <textarea id="vendor_address" rows="1" name="vendor_address" class="form-control" placeholder="Enter the Address" required=""> <?= $vendor_address; ?> </textarea>
                                </div>
                            </div>
                            <div class="divider">
                                <div class="divider-text text-primary">
                                    <i class="ti ti-star"></i>
                                </div>
                            </div>
                            <h5 class="text-primary mt-1 mb-1">GSTIN Details</h5>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_gstin">GSTIN Number<span class=" text-danger"> </span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_gstin" id="vendor_gstin" class="form-control" placeholder="GSTIN FORMAT: 10AABCU9603R1Z5 " data-parsley-checkgst data-parsley-checkgst-message="GST Number already Exists" data-parsley-type="alphanum" data-parsley-pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}\d{1}" data-parsley-whitespace="trim" data-parsley-trigger="keyup" value="<?= $vendor_gstin; ?>" required />
                                    <!-- <small class="text-danger">GSTIN Format: 10AABCU9603R1Z5 </small> -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_pan_num">PAN Number<span class=" text-danger"> </span></label>
                                <div class="form-group">
                                    <input type="text" name="vendor_pan_num" id="vendor_pan_num" class="form-control" placeholder="Pan Card Format: CNFPC5441D" data-parsley-checkpan data-parsley-checkpan-message="PAN Number already Exists" data-parsley-type="alphanum" data-parsley-pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" data-parsley-whitespace="trim" data-parsley-trigger="keyup" value="<?= $vendor_pan_card; ?>" />
                                    <!-- <small class="text-danger">Pan Card Format: CNFPC5441D </small> -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="gst_country">Country<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select class="form-select" name="gst_country" id="gst_country" onchange="GSTCHOOSEN_COUNTRY()" data-parsley-trigger="keyup">
                                        <?= getCOUNTRYLIST($gst_country, 'select_country'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="gst_state">State<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select class="form-select" name="gst_state" id="gst_state" value="<?= $gst_state; ?>" onchange="GSTCHOOSEN_STATE()" data-parsley-trigger="keyup">
                                        <option value="">Choose state</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_city">City<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select class="form-select" name="gst_city" id="gst_city" value="<?= $gst_city; ?>" data-parsley-trigger="keyup">
                                        <option value="">Please Choosen City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_gstin_pincode">Pincode</label>
                                <div class="form-group">
                                    <input type="text" name="vendor_gstin_pincode" id="vendor_gstin_pincode" class="form-control" placeholder="Enter the Pincode" value="<?= $gst_pincode; ?>" required />
                                    <input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="vendor_gstin_address">Address</label>
                                <div class="form-group">
                                    <textarea id="vendor_gstin_address" name="vendor_gstin_address" class="form-control" rows="1" placeholder="Enter the Address" required=""><?= $vendor_gstin_address; ?> </textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="vendor.php" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l14 0"></path>
                                    <path d="M5 12l4 4"></path>
                                    <path d="M5 12l4 -4"></path>
                                </svg>Back</a>
                            <button type="submit" name="submit_vendor_basic_info_btn" id="submit_vendor_basic_info_btn" class="btn btn-primary waves-effect waves-light pe-3">Save &
                                Continue<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l14 0"></path>
                                    <path d="M15 16l4 -4"></path>
                                    <path d="M15 8l4 4"></path>
                                </svg></button>
                    </form>
                </div>
            </div>
        </div>
        </div>

        <!-- /Validation Wizard -->
        <script src="assets/js/parsley.min.js"></script>

        <script>
            document.getElementById('Vendor_email').addEventListener('input', function() {
                var vendorEmail = this.value; // Get the email from the input field

                // Extract the username and password based on the email
                var vendorUsername = vendorEmail.substring(0, vendorEmail.indexOf('@'));
                var vendorPassword = vendorEmail.substring(0, vendorEmail.indexOf('@'));

                // Set the values of the username and password input fields
                document.getElementById('vendor_username').value = vendorUsername;
                document.getElementById('vendor_password').value = vendorPassword;
            });

            function CHOOSEN_COUNTRY() {
                var state_selectize = $("#vendor_state")[0].selectize;
                var COUNTRY_ID = $('#vendor_country').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        state_selectize.clear();
                        state_selectize.clearOptions();
                        state_selectize.addOption(response);
                        <?php if ($vendor_state) : ?>
                            state_selectize.setValue('<?= $vendor_state; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function CHOOSEN_STATE() {
                var city_selectize = $("#vendor_city")[0].selectize;
                var STATE_ID = $('#vendor_state').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($vendor_city) : ?>
                            city_selectize.setValue('<?= $vendor_city; ?>');
                        <?php endif; ?>
                    }
                });
            }


            function GSTCHOOSEN_COUNTRY() {

                var state_selectize = $("#gst_state")[0].selectize;
                var COUNTRY_ID = $('#gst_country').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        state_selectize.clear();
                        state_selectize.clearOptions();
                        state_selectize.addOption(response);
                        <?php if ($gst_state) : ?>
                            state_selectize.setValue('<?= $gst_state; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function GSTCHOOSEN_STATE() {
                var city_selectize = $("#gst_city")[0].selectize;
                var STATE_ID = $('#gst_state').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($gst_city) : ?>
                            city_selectize.setValue('<?= $gst_city; ?>');
                        <?php endif; ?>
                    }
                });
            }

            $(document).ready(function() {
                $(".form-select").selectize();

                <?php if ($vendor_ID != '' && $vendor_ID != 0) : ?>

                    CHOOSEN_COUNTRY();
                    CHOOSEN_STATE();
                    GSTCHOOSEN_COUNTRY();
                    GSTCHOOSEN_STATE();
                <?php endif; ?>

                //AJAX FORM SUBMIT
                $("#form_vendor_basic").submit(function(event) {
                    var form = $('#form_vendor_basic')[0];
                    var data = new FormData(form);
                    $(this).find("button[id='submit_vendor_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_vendor.php?type=vendor_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errros.hotel_name_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_code_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_place_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Place Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_mobile_no_required) {
                                TOAST_NOTIFICATION('warning', 'Mobile No Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_email_id_required) {
                                TOAST_NOTIFICATION('warning', 'Email ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_address_required) {
                                TOAST_NOTIFICATION('warning', 'Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_category_required) {
                                TOAST_NOTIFICATION('warning', 'Category Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_status_required) {
                                TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_powerbackup_required) {
                                TOAST_NOTIFICATION('warning', 'PowerBackup Applicable? Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_country_required) {
                                TOAST_NOTIFICATION('warning', 'Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_state_required) {
                                TOAST_NOTIFICATION('warning', 'State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_city_required) {
                                TOAST_NOTIFICATION('warning', 'City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_postal_code_required) {
                                TOAST_NOTIFICATION('warning', 'Postal Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {

                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Hotel Basic Details Added', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Hotel Basic Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Hotel Basic Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Hotel Basic Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

            "use strict";
            (function() {
                var tagify_hotel_mobile_no = document.querySelector("#hotel_mobile_no");
                var tagify_hotel_email_id = document.querySelector("#hotel_email_id");
                var tagifyMobile = new Tagify(tagify_hotel_mobile_no);
                var tagifyEmail = new Tagify(tagify_hotel_email_id);
            })();
        </script>
    <?php
    elseif ($_GET['type'] == 'vendor_branch') :

        $vendor_ID = $_GET['ID'];
        if ($vendor_ID != '' && $vendor_ID != 0) :
            $basic_info_url = 'vendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
            $vendor_branch_url = 'vendor.php?route=add&formtype=vendor_branch&id=' . $vendor_ID;
            $vendor_branch_list_url = 'vendor.php?route=add&formtype=branch_list&id=' . $vendor_ID;
            $vendor_permit_cost_url = 'vendor.php?route=add&formtype=permit_cost_list&ID=' . $vendor_ID;
            $preview_url = 'vendor.php?route=add&formtype=vendor_add_preview&ID=' . $vendor_ID;
        else :
            $basic_info_url = 'javascript:;';
            $vendor_branch_url = 'javascript:;';
            $vendor_branch_list_url = 'javascript:;';
            $vendor_permit_cost_url = 'javascript:;';
            $preview_url = 'javascript:;';

        endif;


    ?>
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-start py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Vendor Basic Info</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_branch_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  active-stepper">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title ">Branch Details</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_branch_list_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Vehicle</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_permit_cost_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Permit Cost</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $preview_url; ?>" type="button" class="step-trigger">
                                <span class="bs-stepper-circle disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <?php
        if ($vendor_ID != '' && $vendor_ID != 0) :
            $select_vendor_branch_list_query = sqlQUERY_LABEL("SELECT `vendor_branch_id`,  `vendor_id`, `vendor_branch_name`, `branch_primary_mobile_number`, `branch_alternative_mobile_number`, `branch_emailid`, `branch_country_id`, `branch_state_id`, `branch_city_id`, `branch_place`, `branch_primary_address`, `branch_pincode`, `status` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
            $total_vendor_branch_list_num_rows_count = sqlNUMOFROW_LABEL($select_vendor_branch_list_query);
        endif;
        ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card p-4">
                    <form id="form_vendor_branch_form" action="" method="POST">
                        <div class="d-flex justify-content-between mb-3 mt-3">
                            <div>
                                <h4 class="text-primary">Branch Details</h4>
                            </div>
                            <div> <button type="button" class="btn btn-label-primary waves-effect add_item_btn">+ Add Branch</button></div>
                        </div>
                        <div class="col-md-12">
                            <div id="show_item"></div>
                        </div>
                        <?php
                        if ($total_vendor_branch_list_num_rows_count > 0) :
                            while ($fetch_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
                                $room_count++;
                                $vendor_branch_id = $fetch_branch_data['vendor_branch_id'];
                                $vendor_id = $fetch_branch_data['vendor_id'];
                                $vendor_branch_name = $fetch_branch_data['vendor_branch_name'];
                                $branch_primary_mobile_number = $fetch_branch_data['branch_primary_mobile_number'];
                                $branch_alternative_mobile_number = $fetch_branch_data['branch_alternative_mobile_number'];
                                $branch_emailid = $fetch_branch_data['branch_emailid'];
                                $branch_country_id = $fetch_branch_data['branch_country_id'];
                                $branch_state_id = $fetch_branch_data['branch_state_id'];
                                $branch_city_id = $fetch_branch_data['branch_city_id'];
                                $branch_place = $fetch_branch_data['branch_place'];
                                $branch_pincode = $fetch_branch_data['branch_pincode'];
                                $branch_primary_address = $fetch_branch_data['branch_primary_address'];
                        ?>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="vendor_branch_name">Branch Name<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_branch_name[]" id="vendor_branch_name" class="form-control" placeholder="Enter Branch Name" value="<?php echo $vendor_branch_name; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="vendor_branch_email">Email ID<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_branch_email[]" id="vendor_branch_email" class="form-control" placeholder="Enter Branch Email ID" value="<?= $branch_emailid; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="branch_primary_mobile">Primary Mobile<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="tel" name="branch_primary_mobile[]" id="branch_primary_mobile" class="form-control" placeholder="Enter the Primary Mobile" value="<?= $branch_primary_mobile_number; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="alternative_primary_mobile">Alternative Mobile<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="tel" name="alternative_primary_mobile[]" id="alternative_primary_mobile" class="form-control" placeholder="Enter the Alternative Mobile" value="<?= $branch_alternative_mobile_number; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="vendor_country">Country<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_country[]" id="vendor_country" class="form-control" placeholder="Enter Country Name" value="<?php echo $branch_country_id; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <label class="form-label" for="vendor_state">State<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_state[]" id="vendor_state" class="form-control" placeholder="Enter State Name" value="<?php echo $branch_state_id; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <label class="form-label" for="vendor_city">City<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_city[]" id="vendor_city" class="form-control" placeholder="Enter City Name" value="<?php echo $branch_city_id; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="vendor_branch_place">Place<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_branch_place[]" id="vendor_branch_place" class="form-control" placeholder="Enter Place" value="<?= $branch_place; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="vendor_pincode">Pincode<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <input type="text" name="vendor_pincode[]" id="vendor_pincode" value="<?= $branch_pincode; ?>" class="form-control" placeholder="Enter the Pincode" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="vendor_address">Address<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <textarea id="vendor_address" name="vendor_address[]" class="form-control" rows="1" placeholder="Enter the  Address" required=""><?= $branch_primary_address; ?></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="hidden_branch_ID[]" id="hidden_branch_ID" value="<?= $vendor_branch_id; ?>" hidden>
                                    <input type="hidden" name="hidden_vendor_ID[]" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
                                    <div class="col-lg-2 col-xl-2 col-12 d-flex align-items-center justify-content-end"><button type="button" onclick="deletebranch('<?= $vendor_branch_id; ?>','<?= $vendor_ID; ?>');" class="btn btn-label-danger mt-4"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                    </div>
                                    <div class="border-bottom border-bottom-dashed my-4"></div>
                                </div>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="vendor_branch_name">Branch Name<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_branch_name[]" id="vendor_branch_name" class="form-control" placeholder="Enter Branch Name" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vendor_branch_email">Emai ID<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_branch_email[]" id="vendor_branch_email" class="form-control" placeholder="Enter Branch Email ID" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="branch_primary_mobile">Primary Mobile<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="tel" name="branch_primary_mobile[]" id="branch_primary_mobile" class="form-control" placeholder="Enter the Primary Mobile" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="alternative_primary_mobile">Alternative Mobile<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="tel" name="alternative_primary_mobile[]" id="alternative_primary_mobile" class="form-control" placeholder="Enter the Alternative Mobile" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vendor_country">Country<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_country[]" id="vendor_country" class="form-control" value="" placeholder="Enter Country Name" />
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <label class="form-label" for="vendor_state">State<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_state[]" id="vendor_state" class="form-control" placeholder="Enter State Name" value="" />
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <label class="form-label" for="vendor_city">City<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_city[]" id="vendor_city" class="form-control" placeholder="Enter City Name" value="" />
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label" for="vendor_branch_place">Place<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_branch_place[]" id="vendor_branch_place" class="form-control" placeholder="Enter Place" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vendor_pincode">Pincode<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="text" name="vendor_pincode[]" id="vendor_pincode" class="form-control" placeholder="Enter the Pincode" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="vendor_address">Address<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <textarea id="vendor_address" name="vendor_address[]" class="form-control" rows="3" placeholder="Enter the  Address" required=""></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="hidden_vendor_ID[]" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
                                <div class="col-lg-2 col-xl-2 col-12 d-flex align-items-center justify-content-end"><button type="button" class="btn btn-label-danger mt-4 remove_item_btn"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                </div>
                                <div class="border-bottom border-bottom-dashed my-4"></div>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between py-3">
                            <div>
                                <a href="vendor.php?route=add&formtype=basic_info&id=<?= $vendor_ID; ?>" class="btn btn-secondary"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M5 12l4 4"></path>
                                        <path d="M5 12l4 -4"></path>
                                    </svg>Back</a>
                            </div>
                            <button type="submit" id="submit_hotel_room_details_btn" class="btn btn-primary btn-md">Update & Continue<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M5 12l14 0"></path>
                                    <path d="M15 16l4 -4"></path>
                                    <path d="M15 8l4 4"></path>
                                </svg></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css">
        <script src="assets/vendor/libs/select2/select2.js"></script>
        <script src="assets/js/forms-selects.js"></script>
        <script>
            $(document).ready(function() {

                var vendor_branch_counter = 0;

                $(".add_item_btn").click(function(e) {
                    vendor_branch_counter++;
                    e.preventDefault();

                    $("#show_item").prepend('<div class="row g-3" id="show_' + vendor_branch_counter + '"><div class="col-md-4"><label class="form-label" for="branch_name_' + vendor_branch_counter + '">Branch Name <span class="text-danger">*</span></label> <input type="text" name="vendor_branch_name[]" id="vendor_branch_name' + vendor_branch_counter + '" class="form-control" placeholder="Enter Branch Name" /></div><div class="col-md-4"><label class="form-label" for="hotel_room_title">Email ID <span class="text-danger">*</span></label><input type="text" name="vendor_branch_email[]" id="vendor_branch_email" class="form-control" placeholder="Enter Branch Email ID" /></div><div class="col-md-4"><div class="form-group"><label class="form-label" for="preferred_for">Primary Mobile <span class="text-danger">*</span></label><div class="select2-primary"> <input type="tel" name="branch_primary_mobile[]" id="branch_primary_mobile" class="form-control" placeholder="Enter the Primary Mobile" /></div></div></div><div class="col-md-4"><label class="form-label" for="alternative_mobile_' + vendor_branch_counter + '">Alternative Mobile<span class="text-danger">*</span></label><input type="tel" name="alternative_primary_mobile[]" id="alternative_primary_mobile" class="form-control" placeholder="Enter the Alternative Mobile" /></div><div class="col-md-4"><label class="form-label" for="vendor_country' + vendor_branch_counter + '">Vendor country <span class="text-danger">*</span></label> <input type="text" name="vendor_country[]" id="vendor_country' + vendor_branch_counter + '" class="form-control" placeholder="Enter Country Name" value=" " /></div><div class="col-md-4"><label class="form-label" for="room_ref_code' + vendor_branch_counter + '">Vendor state <span class="text-danger">*</span></label><input type="text" name="vendor_state[]" id="vendor_state' + vendor_branch_counter + '" class="form-control" placeholder="Enter State Name" value="" /></div><div class="col-md-4"><div class="form-group"><label class="form-label" for="vendor_city' + vendor_branch_counter + '">Vendor City <span class="text-danger">*</span></label><div class="form-group"> <input type="text" name="vendor_city[]" id="vendor_city' + vendor_branch_counter + '" class="form-control" placeholder="Enter City Name" value="" /></div></div></div><div class="col-md-4"><label class="form-label" for="vendor_place">Vendor Place<span class="text-danger">*</span></label><div class="form-group"><input type="text" name="vendor_branch_place[]"   id="vendor_branch_place" class="form-control" placeholder="Enter Place" /></div></div><div class="col-md-4"><label class="form-label" for="pincode_' + vendor_branch_counter + '">Pincode<span class="text-danger">*</span></label><div class="form-group"><input type="text" name="vendor_pincode[]" id="vendor_pincode" class="form-control" placeholder="Enter the Pincode" /></div></div><div class="col-md-4"><label class="form-label" for="Address_' + vendor_branch_counter + '">Address<span class="text-danger">*</span></label><div class="form-group"> <textarea id="vendor_address" name="vendor_address[]" class="form-control" rows="1" placeholder="Enter the  Address" required=""></textarea></div><input type="hidden" name="hidden_vendor_ID[]" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden></div><div class="col-lg-2 col-xl-2 col-12 d-flex align-items-center justify-content-end"><button type="button" class="btn btn-label-danger mt-4 remove_item_btn"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div><div class="border-bottom border-bottom-dashed my-4"></div></div>');
                    // $(".form-select").selectize();
                });

            });


            function CHOOSEN_COUNTRY() {

                var state_selectize = $("#vendor_state")[0].selectize;
                var COUNTRY_ID = $('#vendor_country').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.

                        state_selectize.clear();
                        state_selectize.clearOptions();
                        state_selectize.addOption(response);
                        <?php if ($vendor_state) : ?>
                            state_selectize.setValue('<?= $vendor_state; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function CHOOSEN_COUNTRYS() {

                var state_selectize = $("#vendor_state")[0].selectize;
                var COUNTRY_ID = $('#vendor_country').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.

                        state_selectize.clear();
                        state_selectize.clearOptions();
                        state_selectize.addOption(response);
                        <?php if ($vendor_state) : ?>
                            state_selectize.setValue('<?= $vendor_state; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function CHOOSEN_STATES() {
                var city_selectize = $("#vendor_city")[0].selectize;
                var STATE_ID = $('#vendor_state').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($vendor_city) : ?>
                            city_selectize.setValue('<?= $vendor_city; ?>');
                        <?php endif; ?>
                    }
                });
            }



            function CHOOSEN_STATE() {
                var city_selectize = $("#vendor_city")[0].selectize;
                var STATE_ID = $('#vendor_state').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($vendor_city) : ?>
                            city_selectize.setValue('<?= $vendor_city; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function deletebranch(vendor_branch_ID, vendor_ID) {

                $('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_add_vendor_form.php?type=delete_branch&vendor_branch_ID=' + vendor_branch_ID + '&vendor_ID=' + vendor_ID, function() {
                    const container = document.getElementById("confirmDELETEINFODATA");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            $(document).on('click', '.remove_item_btn', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent();
                $(row_item).remove();
            });

            flatpickr("#check_in_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                time_24hr: false,
            });
            flatpickr("#check_out_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                time_24hr: false,
            });

            $(document).ready(function() {
                $(".form-select").selectize();
                //AJAX FORM SUBMIT
                $("#form_vendor_branch_form").submit(function(event) {
                    var form = $('#form_vendor_branch_form')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_hotel_room_details_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_vendor.php?type=vendor_branch',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.errros.hotel_room_type_title_required) {
                                TOAST_NOTIFICATION('warning', 'Room Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.preferred_for_required) {
                                TOAST_NOTIFICATION('warning', 'Choose Preferred for Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.hotel_room_title_required) {
                                TOAST_NOTIFICATION('warning', 'Room Title Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.air_conditioner_avilability_required) {
                                TOAST_NOTIFICATION('warning', 'Air Conditioner Availability Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.room_status_required) {
                                TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.room_ref_code_required) {
                                TOAST_NOTIFICATION('warning', 'Room Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.total_max_adult_required) {
                                TOAST_NOTIFICATION('warning', 'Max Adults Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.total_max_children_required) {
                                TOAST_NOTIFICATION('warning', 'Max Children Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.check_in_time_required) {
                                TOAST_NOTIFICATION('warning', 'Check-In Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errros.check_out_time_required) {
                                TOAST_NOTIFICATION('warning', 'Check-Out Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Room Details Added', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Room Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Room Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Room Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
    elseif ($_GET['type'] == 'delete_branch') :

        $vendor_branch_ID = $_GET['vendor_branch_ID'];
        $vendor_ID = $_GET['vendor_ID'];

    ?>
        <div class="row p-2">
            <div class="modal-body">
                <div class="text-center">
                    <h3 class="mb-2">Confirmation Alert?</h3>
                    <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="60" height="60" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 60 60" xml:space="preserve" class="">
                        <g>
                            <path d="M15.84 22.25H8.16a3.05 3.05 0 0 1-3-2.86L4.25 5.55a.76.76 0 0 1 .2-.55.77.77 0 0 1 .55-.25h14a.75.75 0 0 1 .75.8l-.87 13.84a3.05 3.05 0 0 1-3.04 2.86zm-10-16 .77 13.05a1.55 1.55 0 0 0 1.55 1.45h7.68a1.56 1.56 0 0 0 1.55-1.45l.81-13z" fill="#7D7D7D" opacity="1" data-original="#000000" class=""></path>
                            <path d="M21 6.25H3a.75.75 0 0 1 0-1.5h18a.75.75 0 0 1 0 1.5z" fill="#7D7D7D" opacity="1" data-original="#000000" class=""></path>
                            <path d="M15 6.25H9a.76.76 0 0 1-.75-.75V3.7a2 2 0 0 1 1.95-1.95h3.6a2 2 0 0 1 1.95 2V5.5a.76.76 0 0 1-.75.75zm-5.25-1.5h4.5v-1a.45.45 0 0 0-.45-.45h-3.6a.45.45 0 0 0-.45.45zM15 18.25a.76.76 0 0 1-.75-.75v-8a.75.75 0 0 1 1.5 0v8a.76.76 0 0 1-.75.75zM9 18.25a.76.76 0 0 1-.75-.75v-8a.75.75 0 0 1 1.5 0v8a.76.76 0 0 1-.75.75zM12 18.25a.76.76 0 0 1-.75-.75v-8a.75.75 0 0 1 1.5 0v8a.76.76 0 0 1-.75.75z" fill="#7D7D7D" opacity="1" data-original="#000000" class=""></path>
                        </g>
                    </svg>
                    <p class="mb-0 mt-2">Are you sure? want to delete this Branch <b></b><br /> This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-label-github waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="confirmBRANCHDELETE('<?= $vendor_branch_ID; ?>','<?= $vendor_ID; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
            </div>
        </div>
        <script>
            function confirmBRANCHDELETE(vendor_branch_ID, vendor_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_vendor.php?type=confirm_branch_delete",
                    data: {
                        vendor_branch_ID: vendor_branch_ID,
                        vendor_ID: vendor_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to delete the room', 'Error !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#confirmDELETEINFODATA').modal('hide');
                            TOAST_NOTIFICATION('success', 'Branch Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                            location.assign(response.redirect_URL);
                            // $('#room_' + ROOM_ID).remove();
                        }
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'branch_list') :

        $vendor_ID = $_GET['ID'];

        if ($vendor_ID != '' && $vendor_ID != 0) :
            $basic_info_url = 'vendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
            $vendor_branch_url = 'vendor.php?route=add&formtype=vendor_branch&id=' . $vendor_ID;
            $vendor_branch_list_url = 'vendor.php?route=add&formtype=branch_list&id=' . $vendor_ID;
            $vendor_permit_cost_url = 'vendor.php?route=add&formtype=permit_cost_list&ID=' . $vendor_ID;
            $preview_url = 'vendor.php?route=add&formtype=vendor_add_preview&ID=' . $vendor_ID;
        else :
            $basic_info_url = 'javascript:;';
            $vendor_branch_url = 'javascript:;';
            $vendor_branch_list_url = 'javascript:;';
            $vendor_permit_cost_url = 'javascript:;';
            $preview_url = 'javascript:;';
        endif;

    ?>
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-start py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Vendor Basic Info</h5>
                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_branch_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Branch Details</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_branch_list_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle   active-stepper">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title">Vehicle</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a type="button" href="<?= $vendor_permit_cost_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="bs-stepper-title disble-stepper-title">Permit Cost</h5>

                                    <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $preview_url; ?>" type="button" class="step-trigger">
                                <span class="bs-stepper-circle disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card p-4">
                            <div class="row g-3">
                                <h4 class="mb-1">List of Branches</h4>
                                <?php
                                $select_branches = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_branches)) :
                                    $vendor_id = $fetch_list_data['vendor_id'];
                                    $vendor_branch_id = $fetch_list_data['vendor_branch_id'];

                                    $vendor_branch_name = $fetch_list_data['vendor_branch_name'];
                                    $firstletters = substr($vendor_branch_name, 0, 1);
                                ?>
                                    <div class="col-12 col-lg-3 position-relative">
                                        <span class="badge bg-label-primary position-absolute vendor-vehicle-count py-0"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="me-2">
                                                <g>
                                                    <g data-name="13-car">
                                                        <path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
                                                        <path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
                                                    </g>
                                                </g>
                                            </svg> <?= getVECHILECOUNT($vendor_id, $vendor_branch_id, 'vehicle_count'); ?></span>
                                        <a href="list_of_vehicle.php?id=<?= $vendor_branch_id; ?>&vendor_id=<?= $vendor_ID; ?>" class="d-flex justify-content-between vehicle-branches-card p-3">
                                            <div class="d-flex">
                                                <div class="avatar me-3">
                                                    <span class="avatar-initial rounded bg-label-primary fs-4"><?= $firstletters; ?></span>
                                                </div>
                                                <div>
                                                    <h5 class="mb-1 fs-5"><?= $vendor_branch_name; ?></h5>

                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center text-primary">
                                                <i class="ti ti-chevron-right me-1"></i>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile; ?>


                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="vendor.php?route=add&formtype=vendor_branch&id=<?= $vendor_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M5 12l4 4"></path>
                                        <path d="M5 12l4 -4"></path>
                                    </svg>Back</a>

                                <a href="vendor.php?route=add&formtype=permit_cost_list&ID=<?= $vendor_ID; ?>" class="btn btn-primary waves-effect waves-light pe-3">Save &

                                    Continue<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M15 16l4 -4"></path>
                                        <path d="M15 8l4 4"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php
    elseif ($_GET['type'] == 'permit_cost') :

        $vendor_ID = $_GET['ID'];
        if ($vendor_ID != '' && $vendor_ID != 0) :
            $basic_info_url = 'vendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
            $vendor_branch_url = 'vendor.php?route=add&formtype=vendor_branch&id=' . $vendor_ID;
            $vendor_branch_list_url = 'vendor.php?route=add&formtype=branch_list&id=' . $vendor_ID;
            $vendor_permit_cost_url = 'vendor.php?route=add&formtype=permit_cost_list&ID=' . $vendor_ID;
            $preview_url = 'vendor.php?route=add&formtype=vendor_add_preview&ID=' . $vendor_ID;
        else :
            $basic_info_url = 'javascript:;';
            $vendor_branch_url = 'javascript:;';
            $vendor_branch_list_url = 'javascript:;';
            $vendor_permit_cost_url = 'javascript:;';
            $preview_url = 'javascript:;';

        endif;
        ?>
            <div class="row">
                <div class="col-12">
                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header border-0 justify-content-start py-2">
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">1</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vendor Basic Info</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_branch_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">2</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Branch Details</h5>

                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_branch_list_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">3</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vehicle</h5>

                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_permit_cost_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle   active-stepper">4</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title">Permit Cost</h5>

                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#price-book">
                                <a href="<?= $preview_url; ?>" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-title">5</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-0">
                        <div class="card-body dataTable_select text-nowrap">
                            <form id="permit_cost_form" action="" method="POST">
                                <div class="row">
                                    <?php
                                    // $select_permit_costs = sqlQUERY_LABEL("SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `status` = '1' AND `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_CATEGORY_DETAILS:" . sqlERROR_LABEL());
                                    // $vendor_count = sqlNUMOFROW_LABEL($select_permit_costs);

                                    // if ($vendor_count > 0) :
                                    ?>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="vehicle_type" id="vehicle_type" required>
                                            <?= getVEHICLETYPE('', 'select');   ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label" for="selected_state">State <span class="text-danger">*</span></label>
                                        <select class="form-select" name="selected_state[]" id="permit_state" onchange="toggleStateInputs();" required>
                                            <option value="">Select Any One</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="permit_cost_ID[]" id="hidden_permit_cost_ID" value="<?= $permit_cost; ?>" hidden>
                                    <input type="hidden" name="hidden_vendor_ID[]" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
                                </div>
                                <div class="row" id="stateInputContainer">


                                    <div class="col-md-3 mb-3">
                                        <label class="form-label" for="selected_state"><?= $destination_state_id; ?><span class="text-danger">*</span></label>
                                        <input class="form-control" type="text" name="state_cost[]" value="<?= $permit_cost; ?>">
                                        <input class="form-control" type="hidden" name="state_cost[]" id="permit_cost_id">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <a href="vendor.php?route=add&formtype=permit_cost_list&ID=<?= $vendor_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l14 0"></path>
                                            <path d="M5 12l4 4"></path>
                                            <path d="M5 12l4 -4"></path>
                                        </svg>Back</a>
                                    <button type="submit" class="btn btn-primary float-end ms-2" id="permit_cost_form_submit">Save<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l14 0"></path>
                                            <path d="M15 16l4 -4"></path>
                                            <path d="M15 8l4 4"></path>
                                        </svg></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    // Make an AJAX request to fetch the state data from your PHP script using local jQuery
                    $.ajax({
                        url: "engine/json/__JSONpermitstate.php",
                        method: "GET",
                        dataType: "json",
                        success: function(data) {
                            // The 'data' variable contains the parsed JSON response
                            stateInputMap = data;
                            console.log(stateInputMap);

                            // Populate the dropdown and initialize Selectize here
                            populateDropdown();
                            initializeSelectize();
                        },
                        error: function(xhr, status, error) {
                            console.log("Request failed with status: " + status);
                        }
                    });
                    $("#permit_cost_form").submit(function(event) {
                        var form = $('#permit_cost_form')[0];
                        var data = new FormData(form);
                        // $(this).find("button[id='submit_hotel_room_details_btn']").prop('disabled', true);
                        $.ajax({
                            type: "post",
                            url: 'engine/ajax/__ajax_manage_vendor.php?type=permit_cost',
                            data: data,
                            processData: false,
                            contentType: false,
                            cache: false,
                            timeout: 80000,
                            dataType: 'json',
                            encode: true,
                        }).done(function(response) {
                            if (!response.success) {
                                //NOT SUCCESS RESPONSE
                                // if (response.errros.hotel_room_type_title_required) {
                                //     TOAST_NOTIFICATION('warning', 'Room Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.preferred_for_required) {
                                //     TOAST_NOTIFICATION('warning', 'Choose Preferred for Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.hotel_room_title_required) {
                                //     TOAST_NOTIFICATION('warning', 'Room Title Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.air_conditioner_avilability_required) {
                                //     TOAST_NOTIFICATION('warning', 'Air Conditioner Availability Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.room_status_required) {
                                //     TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.room_ref_code_required) {
                                //     TOAST_NOTIFICATION('warning', 'Room Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.total_max_adult_required) {
                                //     TOAST_NOTIFICATION('warning', 'Max Adults Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.total_max_children_required) {
                                //     TOAST_NOTIFICATION('warning', 'Max Children Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.check_in_time_required) {
                                //     TOAST_NOTIFICATION('warning', 'Check-In Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // } else if (response.errros.check_out_time_required) {
                                //     TOAST_NOTIFICATION('warning', 'Check-Out Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // }
                            } else {
                                //SUCCESS RESPOSNE
                                if (response.i_result == true) {
                                    //RESULT SUCCESS
                                    TOAST_NOTIFICATION('success', 'Room Details Added', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                    location.assign(response.redirect_URL);
                                } else if (response.u_result == true) {
                                    //RESULT SUCCESS
                                    TOAST_NOTIFICATION('success', 'Room Details Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                    location.assign(response.redirect_URL);
                                } else if (response.i_result == false) {
                                    //RESULT FAILED
                                    TOAST_NOTIFICATION('success', 'Unable to Add Room Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.u_result == false) {
                                    //RESULT FAILED
                                    TOAST_NOTIFICATION('success', 'Unable to Update Room Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

                var stateInputMap = {}; // Initialize an empty object

                function populateDropdown() {
                    // Get the select element
                    var select = document.getElementById("permit_state");

                    // Clear existing options
                    select.innerHTML = '<option value="">Select Any One</option>';

                    // Populate the dropdown with valid options from stateInputMap
                    for (var stateValue in stateInputMap) {
                        var option = document.createElement("option");
                        option.value = stateValue;
                        option.text = stateInputMap[stateValue];
                        select.appendChild(option);
                    }
                }

                function initializeSelectize() {
                    // Initialize Selectize for the select input
                    $("select").selectize();
                }

                function toggleStateInputs() {
                    // Get the selected state
                    var selectedState = document.getElementById("permit_state").value;

                    // Get the container for state-specific input fields
                    var stateInputContainer = document.getElementById("stateInputContainer");

                    // Clear any existing input fields
                    stateInputContainer.innerHTML = "";

                    // Check if a state is selected
                    if (selectedState !== "") {
                        // Generate and append input fields for the states not selected
                        for (var stateValue in stateInputMap) {
                            if (stateValue !== selectedState) {
                                var stateName = stateInputMap[stateValue];

                                // Create a div with col-md-6 class
                                var columnDiv = document.createElement("div");
                                columnDiv.className = "col-md-3 mb-3";

                                var stateInputLabel = document.createElement("label");
                                stateInputLabel.className = "form-label";
                                stateInputLabel.textContent = stateName;
                                stateInputLabel.htmlFor = "state_cost_" + stateValue;

                                var stateInput = document.createElement("input");
                                stateInput.type = "text";
                                stateInput.id = "state_cost_" + stateValue;
                                stateInput.name = "state_cost[]";
                                stateInput.className = "form-control";
                                stateInput.placeholder = "₹";
                                // stateInput.required = true;
                                stateInput.setAttribute("data-parsley-trigger", "keyup");
                                stateInput.setAttribute("data-parsley-type", "number");
                                stateInput.setAttribute("data-parsley-whitespace", "trim");
                                stateInput.setAttribute("autocomplete", "off");

                                var stateInputhidden = document.createElement("input");
                                stateInputhidden.type = "hidden";
                                stateInputhidden.id = "state_cost_" + stateValue;
                                stateInputhidden.name = "statehidden_cost[]";
                                stateInputhidden.value = stateValue;
                                // Append the label and input field to the column div
                                columnDiv.appendChild(stateInputLabel);
                                columnDiv.appendChild(stateInput);
                                columnDiv.appendChild(stateInputhidden);
                                columnDiv.appendChild(stateInputhidden);

                                // Append the column div to the container
                                stateInputContainer.appendChild(columnDiv);
                            }
                        }
                    }
                }

                // Initially populate the dropdown and hide all input fields
                populateDropdown();
                toggleStateInputs();
            </script>

        <?php elseif ($_GET['type'] == 'permit_cost_list') :

        $vendor_ID = $_GET['ID'];
        if ($vendor_ID != '' && $vendor_ID != 0) :
            $basic_info_url = 'vendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
            $vendor_branch_url = 'vendor.php?route=add&formtype=vendor_branch&id=' . $vendor_ID;
            $vendor_branch_list_url = 'vendor.php?route=add&formtype=branch_list&id=' . $vendor_ID;
            $vendor_permit_cost_url = 'vendor.php?route=add&formtype=permit_cost_list&ID=' . $vendor_ID;
            $preview_url = 'vendor.php?route=add&formtype=vendor_add_preview&ID=' . $vendor_ID;
        else :
            $basic_info_url = 'javascript:;';
            $vendor_branch_url = 'javascript:;';
            $vendor_branch_list_url = 'javascript:;';
            $vendor_permit_cost_url = 'javascript:;';
            $preview_url = 'javascript:;';
        endif;
        ?>
            <div class="row">
                <div class="col-12">
                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header border-0 justify-content-start py-2">
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">1</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vendor Basic Info</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_branch_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">2</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Branch Details</h5>


                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_branch_list_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">3</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vehicle</h5>


                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">

                                <a type="button" href="<?= $vendor_permit_cost_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle   active-stepper">4</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title ">Permit Cost</h5>


                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>

                            <div class="step" data-target="#price-book">
                                <a href="<?= $preview_url; ?>" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-title">5</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title disble-stepper-title">Preview</h5>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-3 text-primary">Permit Details</h4>
                            <div> <a href="vendor.php?route=add&formtype=permit_cost&ID=<?= $vendor_ID; ?>" id="add_vendor" class="btn btn-label-primary waves-effect">+ Add Permit Cost</a></div>
                        </div>
                        <div class="card-body dataTable_select text-nowrap px-0">
                            <div class="table-responsive">
                                <table class="table table-flush-spacing border table-bordered" id="permit_cost_LIST">
                                    <thead class="table-head">
                                        <tr>
                                            <th scope="col">S.No</th><!-- 1 -->
                                            <th scope="col">Vehicle Type</th><!-- 2 -->
                                            <th scope="col">Source State</th><!-- 3 -->
                                            <th scope="col">Destination States and Permit Cost</th><!-- 4 -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$vendor_ID' ORDER BY `permit_cost_id` DESC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                                        $num_of_row = sqlNUMOFROW_LABEL($select_PERMITCOSTLIST_query);
                                        if ($num_of_row > 0) :
                                            $counter = 0;
                                            $currentSourceState = '';
                                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_PERMITCOSTLIST_query)) {
                                                $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                                                $vehicle_type_name = getVEHICLETYPE($vehicle_type_id, 'get_vehicle_type_title');
                                                $source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
                                                $destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
                                                $permit_cost = $fetch_list_data['permit_cost'];
                                                if ($currentSourceState != $source_state_name) {
                                                    if ($currentSourceState != '') {
                                                        echo '</div></td></tr>';
                                                    }
                                                    $counter++;
                                                    echo "<tr>";
                                                    echo "<td>{$counter}</td>";
                                                    echo "<td>{$vehicle_type_name}</td>";
                                                    echo "<td>{$source_state_name}</td>";
                                                    echo '<td>';
                                                    echo '<div class="card-body w-75 h-75 bg-label-dark rounded p-3">';
                                                    $currentSourceState = $source_state_name;
                                                }

                                                if (!empty($permit_cost)) {
                                                    $permit_cost_display = "₹ {$permit_cost}";
                                                    if ($currentSourceState == $source_state_name) {
                                                        echo '<div class="row">';
                                                        echo '<div class="col-md-6 fw-bold">';
                                                        echo "{$destination_state_name}";
                                                        echo '</div>';

                                                        echo '<div class="col-md-6">';
                                                        echo "{$permit_cost_display}";
                                                        echo '</div>';
                                                        echo '<hr class="mt-2 me-0 ms-0 mb-2 text-light">';
                                                        echo '</div>';
                                                    }
                                                }
                                            }
                                            if ($currentSourceState != '');
                                            echo '</div></td></tr>';

                                        else :
                                        ?>
                                            <tr>
                                                <td class="text-center" colspan='37'>No data Available</td>
                                            </tr>
                                        <?php endif; ?>

                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="vendor.php?route=add&formtype=branch_list&id=<?= $vendor_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M5 12l4 4"></path>
                                        <path d="M5 12l4 -4"></path>
                                    </svg>Back</a>
                                <a href="vendor.php?route=add&formtype=vendor_add_preview&ID=<?= $vendor_ID; ?>" class="btn btn-primary float-end ms-2">Save & Continue<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M15 16l4 -4"></path>
                                        <path d="M15 8l4 4"></path>
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php
    elseif ($_GET['type'] == 'vendor_add_preview') :


        $vendor_ID = $_GET['ID'];


        if ($vendor_ID != '' && $vendor_ID != 0) :
            $basic_info_url = 'vendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
            $vendor_branch_url = 'vendor.php?route=add&formtype=vendor_branch&id=' . $vendor_ID;
            $vendor_branch_list_url = 'vendor.php?route=add&formtype=branch_list&id=' . $vendor_ID;
            $vendor_permit_cost_url = 'vendor.php?route=add&formtype=permit_cost_list&ID=' . $vendor_ID;
            $preview_url = 'vendor.php?route=add&formtype=vendor_add_preview&ID=' . $vendor_ID;
        else :
            $basic_info_url = 'javascript:;';
            $vendor_branch_url = 'javascript:;';
            $vendor_branch_list_url = 'javascript:;';
            $vendor_permit_cost_url = 'javascript:;';
            $preview_url = 'javascript:;';
        endif;

        ?>
            <div class="row">
                <div class="col-12">
                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header border-0 justify-content-start py-2">
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $basic_info_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">1</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vendor Basic Info</h5>
                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_branch_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">2</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Branch Details</h5>

                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_branch_list_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle disble-stepper-title">3</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Vehicle</h5>

                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#account-details-validation">
                                <a type="button" href="<?= $vendor_permit_cost_url; ?>" class="step-trigger">
                                    <span class="bs-stepper-circle  disble-stepper-title">4</span>
                                    <span class="bs-stepper-label mt-3 ">
                                        <h5 class="bs-stepper-title disble-stepper-title">Permit Cost</h5>

                                        <!-- <span class="bs-stepper-subtitle">Setup Account Details</span> -->
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step" data-target="#price-book">
                                <a href="<?= $preview_url; ?>" type="button" class="step-trigger">
                                    <span class="bs-stepper-circle active-stepper">5</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h5 class="bs-stepper-title">Preview</h5>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                $select_vendor = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `vendor_code`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_email`, `vendor_gstin`, `vendor_gstin_address`, `vendor_pan_card`, `vendor_faxnumber`, `vendor_country_id`, `vendor_state_id`, `vendor_city_id`, `vendor_address`, `vendor_pincode`, `gst_country`, `gst_state`, `gst_city`,`status` FROM `dvi_vendor_details` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor)) :
                                    $vendor_id = $fetch_data['vendor_id'];
                                    $vendor_name = $fetch_data['vendor_name'];
                                    $vendor_code = $fetch_data['vendor_code'];
                                    $vendor_primary_mobile_number = $fetch_data['vendor_primary_mobile_number'];
                                    $vendor_alternative_mobile_number = $fetch_data['vendor_alternative_mobile_number'];
                                    $vendor_email = $fetch_data['vendor_email'];
                                    $vendor_gstin = $fetch_data['vendor_gstin'];
                                    $vendor_gstin_address = $fetch_data['vendor_gstin_address'];
                                    $vendor_pan_card = $fetch_data['vendor_pan_card'];
                                    $vendor_faxnumber = $fetch_data['vendor_faxnumber'];
                                    $vendor_country_id = $fetch_data['vendor_country_id'];
                                    $vendor_state_id = $fetch_data['vendor_state_id'];
                                    $vendor_city_id = $fetch_data['vendor_city_id'];
                                    $vendor_address = $fetch_data['vendor_address'];
                                    $vendor_pincode = $fetch_data['vendor_pincode'];
                                    $gst_country = $fetch_data['gst_country'];
                                    $gst_state = $fetch_data['gst_state'];
                                    $gst_city = $fetch_data['gst_city'];
                                    $status = $fetch_data['status'];
                                endwhile;

                                if ($status == 1) :
                                    $status = 'Active';
                                else :
                                    $status = 'In Active';
                                endif;
                                ?>
                                <div>
                                    <h5 class="text-primary my-1">Basic Details</h5>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label>Vendor Name</label>
                                        <p class="text-light"><?= $vendor_name ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Email Id</label>
                                        <p class="text-light"><?= $vendor_email ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Primary Mobile</label>
                                        <p class="text-light"><?= $vendor_primary_mobile_number ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Alternative Mobile</label>
                                        <p class="text-light"><?= $vendor_alternative_mobile_number ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Country</label>
                                        <p class="text-light"><?= $vendor_country_id ?></p>
                                    </div>

                                    <div class="col-md-3">
                                        <label>State</label>
                                        <p class="text-light"><?= $vendor_state_id ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>City</label>
                                        <p class="text-light"><?= $vendor_city_id ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pincode</label>
                                        <p class="text-light"><?= $vendor_pincode ?></p>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Fax Number</label>
                                        <p class="text-light"><?= $vendor_faxnumber ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Status</label>
                                        <p class="text-light fw-bold"><?= $status ?></p>
                                    </div>
                                </div>
                                <div class="divider">
                                    <div class="divider-text text-secondary">
                                        <i class="ti ti-star"></i>
                                    </div>
                                </div>
                                <div class="row">
                                    <div>
                                        <h5 class="text-primary my-1">GST Details</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <label>GSTIN</label>
                                        <p class="text-light"><?= $vendor_gstin ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pan Number</label>
                                        <p class="text-light"><?= $vendor_pan_card ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Country</label>
                                        <p class="text-light"><?= $gst_country ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>State</label>
                                        <p class="text-light"><?= $gst_state ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>City</label>
                                        <p class="text-light"><?= $gst_city ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pincode</label>
                                        <p class="text-light"><?= $vendor_pincode ?></p>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Address</label>
                                        <p class="text-light"><?= $vendor_gstin_address ?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="divider">
                                <div class="divider-text text-secondary">
                                    <i class="ti ti-star"></i>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <?php
                                $select_vendor_branch = sqlQUERY_LABEL("SELECT `vendor_branch_id`,  `vendor_id`, `vendor_branch_name`, `branch_primary_mobile_number`, `branch_alternative_mobile_number`, `branch_emailid`, `branch_country_id`, `branch_state_id`, `branch_city_id`, `branch_place`, `branch_primary_address`, `branch_pincode` FROM `dvi_vendor_branches` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="text-primary my-1">Branch Details</h5>

                                    <div>
                                        <select class="form-select" name="choose_branch" id="choose_branch" onchange="choose_branch()" data-parsley-trigger="keyup">
                                            <?php
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
                                                $vendor_branch_id = $fetch_data['vendor_branch_id'];

                                                $vendor_branch_name = $fetch_data['vendor_branch_name'];
                                                echo "<option value='$vendor_branch_id'>$vendor_branch_name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>

                                <span id="branch_perview"></span>

                            </div>

                            <div class="divider">
                                <div class="divider-text text-secondary">
                                    <i class="ti ti-star"></i>
                                </div>
                            </div>
                            <div class="col-md-12">

                                <?php
                                $select_vendor_branch = sqlQUERY_LABEL(
                                    "SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_type_id`, `registration_number`, `registration_date`, `engine_number`, `owner_name`, `vehicle_name`, `fuel_type`, `model_name`, `chassis_number`, `insurance_policy_number`, `insurance_start_date`, `insurance_expiry_date`, `insurance_company_name`, `vehicle_fc_expiry_date`, `RTO_code`, `vehicle_RTO`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_vehicle` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0'"
                                ) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="text-primary my-1">Vehicle Details</h5>
                                    <div>
                                        <select class="form-select" name="choose_vehicle" id="choose_vehicle" onchange="choose_vehicle()" data-parsley-trigger="keyup">
                                            <?php
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
                                                $vehicle_id = $fetch_data['vehicle_id'];
                                                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                                $vehicle_name = getVECHILELIST($vehicle_type_id, 'vehicle_label');
                                                echo "<option value='$vehicle_id'>$vehicle_name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>

                                <span id="vehicle_perview"></span>

                            </div>
                            <div class="divider">
                                <div class="divider-text text-secondary">
                                    <i class="ti ti-star"></i>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php
                                $select_vendor_branch = sqlQUERY_LABEL(
                                    "SELECT `permit_cost_id`, `vendor_id`, `vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost`, `createdby`, `createdon`, `updatedon`, `status`, `deleted` FROM `dvi_permit_cost` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0' GROUP BY `vehicle_type_id` "
                                ) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
                                ?>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="text-primary my-1">Permitcost Details</h5>
                                    <div>
                                        <select class="form-select" name="choose_permitcost" id="choose_permitcost" onchange="choose_permitcost()" data-parsley-trigger="keyup">
                                            <?php
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
                                                $vehicle_id = $fetch_data['vehicle_id'];
                                                $vehicle_type_id = $fetch_data['vehicle_type_id'];
                                                $vehicle_type_name =  getVECHILELIST($vehicle_type_id, 'vehicle_label');
                                                echo "<option value='$vehicle_id'>$vehicle_type_name</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                </div>

                                <span id="permitcost_perview"></span>

                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="vendor.php?route=add&formtype=permit_cost_list&ID=<?= $vendor_ID; ?>" type="button" class="btn btn-label-github waves-effect ps-3"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M5 12l4 4"></path>
                                        <path d="M5 12l4 -4"></path>
                                    </svg>Back</a>
                                <a href="vendor.php" class="btn btn-primary float-end ms-2">Save<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M15 16l4 -4"></path>
                                        <path d="M15 8l4 4"></path>
                                    </svg></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // branch script start
                function choose_branch() {
                    var choose_branch = $('#choose_branch').val();
                    var vendor_id = <?= $vendor_id; ?>;


                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_vendor_branchpreview.php?type=vendor_vehicle',
                        data: {
                            branch_id: choose_branch,
                            vendor_id: vendor_id
                        },
                        success: function(response) {
                            // $('#add_vendor').hide();
                            $('#branch_perview').html(response);
                        }
                    });
                }
                $(document).ready(function() {
                    choose_branch();
                });
                // branch script end

                // vehicle script start
                function choose_vehicle() {
                    var choose_vehicle = $('#choose_vehicle').val();
                    var vehicle_type_id = <?= $vehicle_type_id; ?>;
                    var vendor_id = <?= $vendor_id; ?>;


                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_vendor_vehiclepreview.php?type=vendor_vehicle',
                        data: {
                            vehicle_id: choose_vehicle,
                            vehicle_type_id: vehicle_type_id,
                            vendor_id: vendor_id
                        },
                        success: function(response) {
                            // $('#add_vendor').hide();
                            $('#vehicle_perview').html(response);
                        }
                    });
                }
                $(document).ready(function() {
                    choose_vehicle();
                });
                // vehicle script end

                // vehicle script start
                function choose_permitcost() {
                    var choose_permitcost = $('#choose_permitcost').val();
                    var vendor_id = <?= $vendor_id; ?>;

                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_vendor_permitcostpreview.php?type=vendor_vehicle',
                        data: {
                            vehicle_type_id: choose_permitcost,
                            vendor_id: vendor_id
                        },
                        success: function(response) {
                            // $('#add_vendor').hide();
                            $('#permitcost_perview').html(response);
                        }
                    });
                }
                $(document).ready(function() {
                    choose_permitcost();
                });
                // vehicle script end
            </script>
        </div>

        </div>
        <?php

        $firstTabActive = false; // Set the flag to false after the first tab content

        ?>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
