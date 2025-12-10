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

        $hotel_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotel_ID != '' && $hotel_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `hotel_name`, `hotel_code`, `hotel_place`, `hotel_mobile`, `hotel_email`, `hotel_country`, `hotel_city`, `hotel_state`, `hotel_address`, `hotel_margin`, `hotel_margin_gst_type`,`hotel_margin_gst_percentage`,`hotel_latitude`, `hotel_longitude`,  `hotel_power_backup`, `hotel_pincode`,`hotel_category`, `hotel_hotspot_status`, `status` FROM `dvi_hotel` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $hotel_name = $fetch_list_data['hotel_name'];
                $hotel_code = $fetch_list_data['hotel_code'];
                $hotel_place = $fetch_list_data['hotel_place'];
                $hotel_mobile = $fetch_list_data['hotel_mobile'];
                $hotel_email = $fetch_list_data['hotel_email'];
                $hotel_address = $fetch_list_data['hotel_address'];
                $hotel_category = $fetch_list_data["hotel_category"];
                $hotel_country = $fetch_list_data['hotel_country'];
                $hotel_state = $fetch_list_data["hotel_state"];
                $hotel_city = $fetch_list_data["hotel_city"];
                $hotel_pincode = $fetch_list_data["hotel_pincode"];
                $hotel_margin = $fetch_list_data["hotel_margin"];
                $hotel_latitude = $fetch_list_data["hotel_latitude"];
                $hotel_longitude = $fetch_list_data["hotel_longitude"];
                $hotel_power_backup = $fetch_list_data["hotel_power_backup"];
                $hotel_hotspot_status = $fetch_list_data['hotel_hotspot_status'];
                $status = $fetch_list_data['status'];
                $hotel_margin_gst_type = $fetch_list_data['hotel_margin_gst_type'];
                $hotel_margin_gst_percentage = $fetch_list_data['hotel_margin_gst_percentage'];
            endwhile;
            $btn_label = 'Update & Continue';
        else :
            $hotel_hotspot_status = 0;
            $btn_label = 'Save & Continue';
        endif;

        if ($hotel_ID != '' && $hotel_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=edit&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
        else :
            $basic_info_url = 'javascript:;';
            $room_details_url = 'javascript:;';
            $room_amenities_url = 'javascript:;';
            $hotel_pricebook_url = 'javascript:;';
            $hotel_feedback_url = 'javascript:;';
            $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
        endif;
?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $room_details_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Rooms</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $room_amenities_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Amenities</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $hotel_pricebook_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Price Book</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $hotel_feedback_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Review & Feedback </h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $preview_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">6</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Preview</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card mb-4 p-4">
                    <form id="form_hotel_basic_info" action="" method="POST" data-parsley-validate>
                        <div class="content-header mb-3">
                            <h5 class="text-primary mb-0">Basic Details</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="hotel_name">Hotel Name <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_name" name="hotel_name" class="form-control" placeholder="Enter the Hotel Name" value="<?= $hotel_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotel_place">Place <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_place" name="hotel_place" value="<?= $hotel_place; ?>" class="form-control" placeholder="Enter the hotel place" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotel_status">Status <span class="text-danger">*</span></label>
                                <select id="hotel_status" name="hotel_status" required class="form-select">
                                    <?= getSTATUS($status, 'select') ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_mobile_no">Mobile <span class="text-danger">*</span></label>
                                <textarea id="hotel_mobile_no" name="hotel_mobile_no" class="form-control" placeholder="Enter the Mobile Number" type="text" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off" value="<?= $hotel_mobile ?>" rows="3"><?= $hotel_mobile; ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_email_id">Email <span class="text-danger">*</span></label>
                                <textarea id="hotel_email_id" name="hotel_email_id" class="form-control" placeholder="Enter the Email Id" autocomplete="off" data-parsley-trigger="keyup" data-parsley-whitespace="trim" rows="3" required><?= $hotel_email ?></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_category">Category <span class="text-danger">*</span></label>
                                <select id="hotel_category" name="hotel_category" class="form-control">
                                    <?= getHOTEL_CATEGORY_DETAILS($hotel_category, 'select'); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_powerbackup">Power Backup? <span class="text-danger">*</span></label>
                                <select id="hotel_powerbackup" name="hotel_powerbackup" class="form-select">
                                    <?= get_YES_R_NO($hotel_power_backup, 'select'); ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="hotel_country">Country <span class="text-danger">*</span></label>
                                <select class="form-select" name="hotel_country" id="hotel_country" onchange="CHOOSEN_COUNTRY()" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_country_error_container">
                                    <?= getCOUNTRYLIST($hotel_country, 'select_country'); ?>
                                </select>
                                <div id="hotel_country_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_state">State <span class="text-danger">*</span></label>
                                <select class="form-select" name="hotel_state" id="hotel_state" onchange="CHOOSEN_STATE()" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_state_error_container">
                                    <option value="">Please Choose State</option>
                                </select>
                                <div id="hotel_state_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_city">City <span class="text-danger">*</span></label>
                                <select class="form-select" name="hotel_city" id="hotel_city" onchange="CHOOSEN_CITY()" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_city_error_container">
                                    <option value="">Please Choosen City</option>
                                </select>
                                <div id="hotel_city_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_postal_code">Pincode <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_postal_code" value="<?= $hotel_pincode; ?>" name="hotel_postal_code" class="form-control" maxlength="7" placeholder="Enter the Pincode" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_code">Hotel Code <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_code" name="hotel_code" value="<?= $hotel_code; ?>" class="form-control" readonly placeholder="Enter the hotel code" data-parsley-type="alphanum" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="hotel_margin">Hotel Margin (In Percentage) <span class="text-danger">*</span> </label>
                                <input type="text" id="hotel_margin" value="<?= $hotel_margin; ?>" name="hotel_margin" class="form-control" placeholder="Enter the Margin" required>
                            </div>
                            <div class="col-md-4"><label class="form-label" for="hotel_margin_gst_type">Hotel Margin GST
                                    Type<span class="text-danger">*</span></label>
                                <select id="hotel_margin_gst_type" name="hotel_margin_gst_type" class="form-control form-select" required><?= getGSTTYPE($hotel_margin_gst_type, 'select') ?></select>
                            </div>

                            <div class="col-md-4"><label class="form-label" for="hotel_margin_gst_percentage">Hotel Margin GST
                                    Percentage<span class="text-danger">*</span></label>
                                <div class="form-group">
                                    <select id="hotel_margin_gst_percentage" name="hotel_margin_gst_percentage" class="form-control form-select" required>
                                        <?= getGSTDETAILS($hotel_margin_gst_percentage, 'select'); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-auto">
                                <label class="form-label" for="hotel_latitude">Latitude </label>
                                <input type="text" id="hotel_latitude" value="<?= $hotel_latitude; ?>" name="hotel_latitude" class="form-control" placeholder="Enter the Latitude">
                            </div>
                            <div class="col-md-auto">
                                <label class="form-label" for="hotel_longitude">Longitude</label>
                                <input type="text" id="hotel_longitude" value="<?= $hotel_longitude; ?>" name="hotel_longitude" class="form-control" placeholder="Enter the Longitude">
                                <input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="<?= $hotel_ID; ?>" hidden>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotel_hotspot_status">Hotspot Status <span class="text-danger">*</span></label>
                                <select id="hotel_hotspot_status" name="hotel_hotspot_status" required class="form-select">
                                    <?= getSTATUS($hotel_hotspot_status, 'select') ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="hotel-basic-label" for="hotel_address">Address <span class="text-danger">*</span></label>
                                <textarea id="hotel_address" name="hotel_address" class="form-control" rows="3" placeholder="Enter the  Address" required><?= $hotel_address; ?></textarea>
                            </div>
                        </div>

                        <!--
                        <div class="divider">
                            <div class="divider-text text-primary">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>

                         <div class="content-header mb-3">
                            <h5 class="text-primary mb-0">Food Cost</h5>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="hotel_breafast_cost">Breakfast Cost(₹) (Per Person) <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_breafast_cost" value="<?= $hotel_breafast_cost; ?>" name="hotel_breafast_cost" class="form-control" placeholder="Enter Breakfast Cost" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotel_lunch_cost">Lunch Cost(₹) (Per Person) <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_lunch_cost" value="<?= $hotel_lunch_cost; ?>" name="hotel_lunch_cost" class="form-control" placeholder="Enter Lunch Cost" required />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="hotel_dinner_cost">Dinner Cost(₹) (Per Person) <span class="text-danger">*</span></label>
                                <input type="text" id="hotel_dinner_cost" value="<?= $hotel_dinner_cost; ?>" name="hotel_dinner_cost" class="form-control" placeholder="Enter Dinner Cost" required />
                            </div>
                        </div>  -->
                        <div class=" mt-5">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="hotel.php" class="btn btn-secondary">Back</a>
                                </div>
                                <button type="submit" id="submit_hotel_basic_info_btn" class="btn btn-primary btn-md"><?= $btn_label; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            function CHOOSEN_COUNTRY() {
                var state_selectize = $("#hotel_state")[0].selectize;
                var COUNTRY_ID = $('#hotel_country').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        state_selectize.clear();
                        state_selectize.clearOptions();
                        state_selectize.addOption(response);
                        <?php if ($hotel_state) : ?>
                            state_selectize.setValue('<?= $hotel_state; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function CHOOSEN_STATE() {
                var city_selectize = $("#hotel_city")[0].selectize;
                var STATE_ID = $('#hotel_state').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($hotel_city) : ?>
                            city_selectize.setValue('<?= $hotel_city; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function CHOOSEN_CITY() {
                var hotel_city = $("#hotel_city").val();
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_generate_code.php?type=show_hotel_code',
                    type: "post",
                    data: {
                        hotel_city: hotel_city,
                        hotel_id: '<?= $hotel_ID; ?>'
                    },
                    success: function(response) {
                        $("#hotel_code").val(response);
                    }
                });
            }

            $(document).ready(function() {
                $(".form-select").selectize();

                $('#hotel_category').attr('required', true);
                $('#hotel_country').attr('required', true);
                $('#hotel_state').attr('required', true);
                $('#hotel_city').attr('required', true);
                <?php if ($hotel_ID != '' && $hotel_ID != 0) : ?>
                    CHOOSEN_COUNTRY();
                    CHOOSEN_STATE();
                <?php endif; ?>
                //AJAX FORM SUBMIT
                $("#form_hotel_basic_info").submit(function(event) {
                    var form = $('#form_hotel_basic_info')[0];
                    var data = new FormData(form);
                    $(this).find("button[id='submit_hotel_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_hotel.php?type=hotel_basic_info',
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 80000,
                        dataType: 'json',
                        encode: true,
                    }).done(function(response) {
                        if (!response.success) {
                            $('#submit_hotel_basic_info_btn').prop('disabled', false);
                            //NOT SUCCESS RESPONSE
                            if (response.errors.hotel_name_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Name Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_code_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Code Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_place_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Place Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_mobile_no_required) {
                                TOAST_NOTIFICATION('warning', 'Mobile No Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_email_id_required) {
                                TOAST_NOTIFICATION('warning', 'Email ID Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_address_required) {
                                TOAST_NOTIFICATION('warning', 'Address Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotel_category_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Category Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_status_required) {
                                TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotel_powerbackup_required) {
                                TOAST_NOTIFICATION('warning', 'PowerBackup Applicable? Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_country_required) {
                                TOAST_NOTIFICATION('warning', 'Country Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotel_state_required) {
                                TOAST_NOTIFICATION('warning', 'State Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotel_city_required) {
                                TOAST_NOTIFICATION('warning', 'City Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotel_postal_code_required) {
                                TOAST_NOTIFICATION('warning', 'Postal Code Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_margin_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Margin Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_breafast_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Breakfast Cost Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_lunch_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Lunch Cost Required', 'Warning !!!',
                                    '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_dinner_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Dinner Cost Required', 'Warning !!!',
                                    '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_margin_gst_type_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Margin GST Type Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_margin_gst_percentage_required) {
                                TOAST_NOTIFICATION('warning', 'Hotel Margin GST percentage Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Hotel Basic Details Added', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Hotel Basic Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Hotel Basic Details',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                $(this).find("button[id='submit_hotel_basic_info_btn']").prop('disabled',
                                    false);
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Hotel Basic Details',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                $(this).find("button[id='submit_hotel_basic_info_btn']").prop('disabled', false);
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
    elseif ($_GET['type'] == 'delete_room') :

        $room_ID = $_GET['ID'];
        $HOT_ID = $_GET['HOT_ID'];
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
                    <p class="mb-0 mt-2">Are you sure? want to delete this room
                        <b>"<?= getROOM_DETAILS($room_ID, 'room_title'); ?>"</b><br /> This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-label-github waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="confirmROOMDELETE('<?= $room_ID; ?>','<?= $HOT_ID; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
            </div>
        </div>
        <script>
            function confirmROOMDELETE(ROOM_ID, HOT_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_hotel.php?type=confirm_room_delete",
                    data: {
                        _ID: ROOM_ID,
                        _HOT_ID: HOT_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to delete the room', 'Error !!!', '', '', '', '',
                                    '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#showDELETEMODAL').modal('hide');
                            TOAST_NOTIFICATION('success', 'Room Deleted Successfully', 'Success !!!', '', '', '', '',
                                '', '', '', '', '');
                            $('#room_' + ROOM_ID).remove();
                        }
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'delete_room_gallery') :

        $room_gallery_ID = $_GET['ID'];
        $HOT_ID = $_GET['HOT_ID'];
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
                    <p class="mb-0 mt-2">Are you sure? want to delete this room gallery<br /> This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-label-github waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="confirmROOMGALLERYDELETE('<?= $room_gallery_ID; ?>','<?= $HOT_ID; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
            </div>
        </div>
        <script>
            function confirmROOMGALLERYDELETE(ROOM_GAL_ID, HOT_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_hotel.php?type=confirm_room_gallery_delete",
                    data: {
                        _ID: ROOM_GAL_ID,
                        _HOT_ID: HOT_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to delete the room gallery', 'Error !!!', '', '',
                                    '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#showDELETEMODAL').modal('hide');
                            TOAST_NOTIFICATION('success', 'Room Gallery Deleted Successfully', 'Success !!!', '', '',
                                '', '', '', '', '', '', '');
                            $('#room_gallery_id_' + ROOM_GAL_ID).remove();
                        }
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'room_amenities') :

        $hotel_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotel_ID != '' && $hotel_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=edit&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
        else :
            $basic_info_url = 'javascript:;';
            $room_details_url = 'javascript:;';
            $room_amenities_url = 'javascript:;';
            $hotel_pricebook_url = 'javascript:;';
            $hotel_feedback_url = 'javascript:;';
            $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
        endif;
    ?>
        <!-- Default -->
        <div class="row sticky-element">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle  disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#personal-info-validation">
                            <a href="<?= $room_details_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Rooms</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#social-links-validation">
                            <a href="<?= $room_amenities_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle active-stepper">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title">Amenities</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $hotel_pricebook_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Price Book</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $hotel_feedback_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Review & Feedback</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $preview_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">6</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Preview</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                <button type="button" class="btn btn-primary waves-effect mb-3 add_item_btn">+ Add Amenities</button>
            </div>
        </div>
        <?php
        if ($hotel_ID != '' && $hotel_ID != 0) :
            $select_hotel_amenities_list_query = sqlQUERY_LABEL("SELECT `hotel_amenities_id`, `amenities_title`, `amenities_code`, `quantity`, `availability_type`, `start_time`, `end_time`, `status` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_AMENITIES_LIST:" . sqlERROR_LABEL());
            $total_hotel_amenities_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_amenities_list_query);
            $amenities_count = 0; // Initialize the amenities count
        endif;
        ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card p-3">
                    <form class="" id="form_hotel_room_amenities_details" method="post" data-parsley-validate>
                        <div class="col-md-12">
                            <div id="show_item"></div>
                        </div>
                        <?php if ($total_hotel_amenities_num_rows_count > 0) :
                            while ($fetch_amenities_data = sqlFETCHARRAY_LABEL($select_hotel_amenities_list_query)) :
                                $amenities_count++;
                                $hotel_amenities_id = $fetch_amenities_data['hotel_amenities_id'];
                                $amenities_title = $fetch_amenities_data['amenities_title'];
                                $amenities_title = htmlspecialchars_decode(htmlspecialchars_decode($amenities_title, ENT_QUOTES), ENT_QUOTES);
                                $amenities_code = $fetch_amenities_data['amenities_code'];
                                $quantity = $fetch_amenities_data['quantity'];
                                $availability_type = $fetch_amenities_data['availability_type'];
                                $start_time = $fetch_amenities_data['start_time'];
                                $end_time = $fetch_amenities_data['end_time'];
                                $status = $fetch_amenities_data['status'];
                        ?>
                                <div class="row g-3" id="amenities_<?= $hotel_amenities_id; ?>">
                                    <div class="col-md-12">
                                        <h6 class="m-0">Amenities <?= $amenities_count; ?>/<?= $total_hotel_amenities_num_rows_count; ?>
                                        </h6>
                                    </div>
                                    <div class=" col-md-3">
                                        <label class="form-label" for="amenities_title">Amenities Title <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <input type="text" name="amenities_title[]" id="amenities_title_<?= $amenities_count - 1; ?>" value="<?= $amenities_title; ?>" autocomplete="off" required class="form-control" placeholder="Enter Amenities Title" />
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label" for="amenities_qty">Quantity <span class="text-danger">*</span></label>
                                        <input type="text" name="amenities_qty[]" id="amenities_qty" value="<?= $quantity; ?>" class="form-control" value="1" required readonly placeholder="Enter Quantity" />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="availability_type_<?= $amenities_count - 1; ?>">Availability Type
                                            <span class="text-danger">*</span></label>
                                        <select id="availability_type_<?= $amenities_count - 1; ?>" name="availability_type[]" required class="form-control form-select">
                                            <?= get_AMENITIES_AVILABILITY_TYPE($availability_type, 'select'); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 position-relative" id="start_time_div_<?= $amenities_count - 1; ?>" <?= ($availability_type == 2) ? '' : 'style="display: none;"' ?>>
                                        <label class="form-label" for="available_start_time_<?= $amenities_count - 1; ?>">Available
                                            Start time <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <input class="form-control" type="time" placeholder="hh:mm" value="<?= $start_time; ?>" id="available_start_time_<?= $amenities_count - 1; ?>" name="available_start_time[]">
                                            <span class="time-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 359.286 359.286" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                                    <g>
                                                        <path d="M179.643 359.286c99.043 0 179.643-80.6 179.643-179.643S278.687 0 179.643 0 0 80.6 0 179.643s80.6 179.643 179.643 179.643zm0-335.334c85.869 0 155.691 69.821 155.691 155.691s-69.821 155.691-155.691 155.691S23.952 265.513 23.952 179.643 93.774 23.952 179.643 23.952z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                        <path d="M232.039 236.89c2.216 1.796 4.85 2.635 7.485 2.635a11.91 11.91 0 0 0 9.341-4.491c4.132-5.15 3.293-12.695-1.856-16.827l-55.39-44.312V83.834c0-6.587-5.389-11.976-11.976-11.976s-11.976 5.389-11.976 11.976v95.81c0 3.653 1.677 7.066 4.491 9.341z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 position-relative" id="end_time_div_<?= $amenities_count - 1; ?>" <?= ($availability_type == 2) ? '' : 'style="display: none;"' ?>>
                                        <label class="form-label" for="available_end_time_<?= $amenities_count - 1; ?>">Available End
                                            time <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <input class="form-control" type="time" placeholder="hh:mm" value="<?= $end_time; ?>" id="available_end_time_<?= $amenities_count - 1; ?>" name="available_end_time[]">
                                            <span class="time-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 359.286 359.286" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                                    <g>
                                                        <path d="M179.643 359.286c99.043 0 179.643-80.6 179.643-179.643S278.687 0 179.643 0 0 80.6 0 179.643s80.6 179.643 179.643 179.643zm0-335.334c85.869 0 155.691 69.821 155.691 155.691s-69.821 155.691-155.691 155.691S23.952 265.513 23.952 179.643 93.774 23.952 179.643 23.952z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                        <path d="M232.039 236.89c2.216 1.796 4.85 2.635 7.485 2.635a11.91 11.91 0 0 0 9.341-4.491c4.132-5.15 3.293-12.695-1.856-16.827l-55.39-44.312V83.834c0-6.587-5.389-11.976-11.976-11.976s-11.976 5.389-11.976 11.976v95.81c0 3.653 1.677 7.066 4.491 9.341z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label" for="amenities_status_<?= $amenities_count - 1; ?>">Status <span class="text-danger">*</span></label>
                                        <select id="amenities_status_<?= $amenities_count - 1; ?>" name="amenities_status[]" class="form-control form-select" required><?= getSTATUS($status, 'select') ?></select>
                                    </div>
                                    <?php if ($hotel_amenities_id != "") : ?>
                                        <div class="col-md-2">
                                            <label class="form-label" for="amenities_code_<?= $amenities_count - 1; ?>">Amenities code <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input type="text" name="amenities_code[]" id="amenities_code_<?= $amenities_count - 1; ?>" value="<?= $amenities_code; ?>" required class="form-control" placeholder="Enter Amenities code" readonly />
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <input type="hidden" name="hidden_amenities_ID[]" id="hidden_amenities_ID" value="<?= $hotel_amenities_id; ?>" hidden>
                                    <input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="<?= $hotel_ID; ?>" hidden>
                                    <div class="col-md-2 d-flex align-items-center mb-0">
                                        <button type="button" class="btn btn-label-danger mt-4" onclick="removeAMENITIES('<?= $hotel_amenities_id; ?>','<?= $hotel_ID; ?>')"><i class=" ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                    </div>
                                    <div class="border-bottom border-bottom-dashed my-4"></div>
                                </div>
                            <?php endwhile;
                        else : ?>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <h6 class="m-0">Amenities #1</h6>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label" for="amenities_title">Amenities Title <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="text" name="amenities_title[]" autocomplete="off" id="amenities_title_0" class="form-control" placeholder="Enter Amenities Title" required />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label" for="amenities_qty">Quantity <span class="text-danger">*</span></label>
                                    <input type="text" name="amenities_qty[]" id="amenities_qty" class="form-control" value="1" readonly placeholder="Enter Quantity" required />
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="availability_type_0">Availability Type <span class="text-danger">*</span></label>
                                    <select id="availability_type_0" name="availability_type[]" required class="form-control form-select">
                                        <?= get_AMENITIES_AVILABILITY_TYPE($availability_type, 'select'); ?>
                                    </select>
                                </div>
                                <div class="col-md-2 position-relative" id="start_time_div_0" style="display: none;">
                                    <label class="form-label" for="available_start_time_0">Available Start time <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input class="form-control" type="time" placeholder="hh:mm" id="available_start_time_0" name="available_start_time[]">
                                        <span class="time-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 359.286 359.286" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                                <g>
                                                    <path d="M179.643 359.286c99.043 0 179.643-80.6 179.643-179.643S278.687 0 179.643 0 0 80.6 0 179.643s80.6 179.643 179.643 179.643zm0-335.334c85.869 0 155.691 69.821 155.691 155.691s-69.821 155.691-155.691 155.691S23.952 265.513 23.952 179.643 93.774 23.952 179.643 23.952z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                    <path d="M232.039 236.89c2.216 1.796 4.85 2.635 7.485 2.635a11.91 11.91 0 0 0 9.341-4.491c4.132-5.15 3.293-12.695-1.856-16.827l-55.39-44.312V83.834c0-6.587-5.389-11.976-11.976-11.976s-11.976 5.389-11.976 11.976v95.81c0 3.653 1.677 7.066 4.491 9.341z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2 position-relative" id="end_time_div_0" style="display: none;">
                                    <label class="form-label" for="available_end_time_0">Available End time <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input class="form-control" type="time" placeholder="hh:mm" value="" id="available_end_time_0" name="available_end_time[]">
                                        <span class="time-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 359.286 359.286" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                                <g>
                                                    <path d="M179.643 359.286c99.043 0 179.643-80.6 179.643-179.643S278.687 0 179.643 0 0 80.6 0 179.643s80.6 179.643 179.643 179.643zm0-335.334c85.869 0 155.691 69.821 155.691 155.691s-69.821 155.691-155.691 155.691S23.952 265.513 23.952 179.643 93.774 23.952 179.643 23.952z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                    <path d="M232.039 236.89c2.216 1.796 4.85 2.635 7.485 2.635a11.91 11.91 0 0 0 9.341-4.491c4.132-5.15 3.293-12.695-1.856-16.827l-55.39-44.312V83.834c0-6.587-5.389-11.976-11.976-11.976s-11.976 5.389-11.976 11.976v95.81c0 3.653 1.677 7.066 4.491 9.341z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path>
                                                </g>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" for="amenities_status_0">Status <span class="text-danger">*</span></label>
                                    <select id="amenities_status_0" name="amenities_status[]" class="form-control form-select" required><?= getSTATUS('', 'select') ?></select>
                                </div>
                                <!-- <div class="col-md-2">
                                    <label class="form-label" for="amenities_code_0">Amenities code <span class="text-danger">*</span></label>
                                    <div class="form-group">
                                        <input type="text" name="amenities_code[]" id="amenities_code_0" value="<?= $amenities_code_0; ?>" class="form-control" placeholder="Enter Amenities code" required readonly />
                                    </div>
                                </div>-->
                                <input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="<?= $hotel_ID; ?>" hidden>
                                <div class="col-md-2 mb-0">
                                    <button type="button" class="btn btn-label-danger mt-4"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                </div>
                                <div class="border-bottom border-bottom-dashed my-4"></div>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between py-3">
                            <div>
                                <a href="hotel.php?route=add&formtype=room_details&id=<?= $hotel_ID; ?>" class="btn btn-secondary">Back</a>
                            </div>
                            <button type="submit" id="submit_hotel_room_amenities_details_btn" class="btn btn-primary btn-md">Update & Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>

        <script>
            $(document).ready(function() {

                $(function() {
                    var e, t = $(".sticky-element"),
                        t = (window.Helpers.initCustomOptionCheck(), e = Helpers.isNavbarFixed() ? $(
                            ".layout-navbar").height() + 7 : 0, t.length && t.sticky({
                            topSpacing: e,
                            zIndex: 9
                        }));
                });

                <?php if ($total_hotel_amenities_num_rows_count > 0) :
                    for ($selected_i = 0; $selected_i < $total_hotel_amenities_num_rows_count; $selected_i++) : ?>
                        var row_ID = '<?= $selected_i; ?>';

                        $('#availability_type_' + row_ID).selectize();
                        $('#amenities_status_' + row_ID).selectize();

                        flatpickr('#available_start_time_' + row_ID, {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                            time_24hr: false,
                        });
                        flatpickr('#available_end_time_' + row_ID, {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                            time_24hr: false,
                        });
                <?php endfor;
                endif; ?>

                <?php if ($total_hotel_amenities_num_rows_count > 0) : ?>
                    var amenities_type_counter = '<?= $total_hotel_amenities_num_rows_count - 1; ?>';
                    var amenities_count = '<?= $total_hotel_amenities_num_rows_count + 1; ?>';
                <?php else : ?>
                    var amenities_type_counter = 0;
                    var amenities_count = 1;
                    $(function() {
                        $('#availability_type_0').change(function() {
                            if ($('#availability_type_0').val() == 2) {
                                $('#start_time_div_0').show();
                                $('#end_time_div_0').show();
                            } else {
                                $('#start_time_div_0').hide();
                                $('#end_time_div_0').hide();
                            }
                        });
                    });

                    $(".form-select").selectize();

                    flatpickr('#available_start_time_0', {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                        time_24hr: false,
                    });

                    flatpickr('#available_end_time_0', {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                        time_24hr: false,
                    });
                <?php endif; ?>

                $(".add_item_btn").click(function(e) {
                    amenities_type_counter++;
                    amenities_count++;
                    e.preventDefault();
                    var hotel_ID = '<?= $hotel_ID ?>';
                    $("#show_item").prepend('<div class="row g-3" id="show_' + amenities_type_counter +
                        '"><div class="col-md-12"><h6 class="m-0">Amenities #' + (amenities_count - 1) +
                        '</h6></div><div class="col-md-3"><label class="form-label" for="amenities_title">Amenities Title <span class="text-danger">*</span></label><div class="form-group"><input type="text" name="amenities_title[]" autocomplete="off" id="amenities_title_' +
                        amenities_type_counter +
                        '" class="form-control" required placeholder="Enter Amenities Title" /></div></div><div class="col-md-1"><label class="form-label" for="amenities_qty">Quantity <span class="text-danger">*</span></label><input type="text" name="amenities_qty[]" id="amenities_qty" class="form-control" value="1" readonly required placeholder="Enter Quantity" /></div><div class="col-md-2"><label class="form-label" for="availability_type_' +
                        amenities_type_counter +
                        '">Availability Type <span class="text-danger">*</span></label><select id="availability_type_' +
                        amenities_type_counter +
                        '" name="availability_type[]" required class="form-control form-select availability_type"><?= get_AMENITIES_AVILABILITY_TYPE('', 'select'); ?></select></div><div class="col-md-2 position-relative show_time" id="start_time_div_' +
                        amenities_type_counter +
                        '" style="display:none;"><label class="form-label" for="available_start_time_' +
                        amenities_type_counter +
                        '">Available Start time <span class="text-danger">*</span></label><div class="form-group"><input class="available_start_time form-control" type="time" placeholder="hh:mm" id="available_start_time_' +
                        amenities_type_counter +
                        '" name="available_start_time[]"><span class="time-icon"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 359.286 359.286" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M179.643 359.286c99.043 0 179.643-80.6 179.643-179.643S278.687 0 179.643 0 0 80.6 0 179.643s80.6 179.643 179.643 179.643zm0-335.334c85.869 0 155.691 69.821 155.691 155.691s-69.821 155.691-155.691 155.691S23.952 265.513 23.952 179.643 93.774 23.952 179.643 23.952z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path><path d="M232.039 236.89c2.216 1.796 4.85 2.635 7.485 2.635a11.91 11.91 0 0 0 9.341-4.491c4.132-5.15 3.293-12.695-1.856-16.827l-55.39-44.312V83.834c0-6.587-5.389-11.976-11.976-11.976s-11.976 5.389-11.976 11.976v95.81c0 3.653 1.677 7.066 4.491 9.341z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path></g></svg></span></div></div><div class="col-md-2 position-relative show_time" id="end_time_div_' +
                        amenities_type_counter +
                        '" style="display:none;"><label class="form-label" for="available_end_time_' +
                        amenities_type_counter +
                        '">Available End time <span class="text-danger">*</span></label><div class="form-group"><input class="form-control available_end_time" type="time" placeholder="hh:mm" value="" id="available_end_time_' +
                        amenities_type_counter +
                        '" name="available_end_time[]"><span class="time-icon"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 359.286 359.286" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M179.643 359.286c99.043 0 179.643-80.6 179.643-179.643S278.687 0 179.643 0 0 80.6 0 179.643s80.6 179.643 179.643 179.643zm0-335.334c85.869 0 155.691 69.821 155.691 155.691s-69.821 155.691-155.691 155.691S23.952 265.513 23.952 179.643 93.774 23.952 179.643 23.952z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path><path d="M232.039 236.89c2.216 1.796 4.85 2.635 7.485 2.635a11.91 11.91 0 0 0 9.341-4.491c4.132-5.15 3.293-12.695-1.856-16.827l-55.39-44.312V83.834c0-6.587-5.389-11.976-11.976-11.976s-11.976 5.389-11.976 11.976v95.81c0 3.653 1.677 7.066 4.491 9.341z" fill="#685dd8" opacity="1" data-original="#000000" class=""></path></g></svg></span></div></div><div class="col-md-2"><label class="form-label" for="amenities_status_' +
                        amenities_type_counter +
                        '">Status <span class="text-danger">*</span></label><select id="amenities_status_' +
                        amenities_type_counter +
                        '" name="amenities_status[]" class="form-control form-select" required><?= getSTATUS('1', 'select') ?></select></div><!--<div class="col-md-2"><label class="form-label" for="amenities_code_' +
                        amenities_type_counter +
                        '">Amenities code <span class="text-danger">*</span></label><div class="form-group"><input type="text" name="amenities_code[]" id="amenities_code_' +
                        amenities_type_counter +
                        '" class="form-control" placeholder="Enter Amenities code" required readonly /></div></div>--><div class="col-md-2 d-flex align-items-center mb-0"><button type="button" class="btn btn-label-danger mt-4 remove_item_btn"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div><div class="border-bottom border-bottom-dashed my-4"></div> <input type="hidden" name="hidden_amenities_ID[]" id="hidden_amenities_ID" value="" hidden><input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="' +
                        hotel_ID + '" hidden></div>');

                    /* var targetOffset = $('#show_' + amenities_type_counter).offset().top; */

                    $('#availability_type_' + amenities_type_counter).selectize();
                    $('#amenities_status_' + amenities_type_counter).selectize();

                    /* // Animate the scroll to the target div
                    $("html, body").animate({
                        scrollTop: targetOffset
                    }, 1000); // You can adjust the animation duration (in milliseconds) as needed */

                    flatpickr('.available_start_time', {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                        time_24hr: false,
                    });

                    flatpickr('.available_end_time', {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "h:i K", // Use "h:i K" for AM/PM format
                        time_24hr: false,
                    });
                });

                $(document).on('click', '.remove_item_btn', function(e) {
                    e.preventDefault();
                    let row_item = $(this).parent().parent();
                    $(row_item).remove();
                });

                /*   $('body').on('blur', '[id^="amenities_title_"]', function() {
                       var amenities_title_value = $(this).val();
                       var amenities_code_counter = $(this).attr('id').replace('amenities_title_', '');
                       $.ajax({
                           url: 'engine/ajax/__ajax_fetch_generate_code.php?type=show_amenities_code',
                           type: "post",
                           data: {
                               amenities_title: amenities_title_value,
                               hotel_id: '<?= $hotel_ID; ?>',
                               hotel_amenities_id: '<?= $hotel_amenities_id; ?>'
                           },
                           success: function(response) {
                               $('#amenities_code_' + amenities_code_counter).val(response);
                           }
                       });
                   });*/

                // Add an event listener for changes in the availability type select element
                $('body').on('change', '[id^="availability_type_"]', function() {
                    var availabilityType = $(this).val();
                    var containerId = $(this).attr('id').replace('availability_type_', '');

                    // Check the selected availability type and show/hide the corresponding time fields
                    if (availabilityType === '2') {
                        $('#start_time_div_' + containerId).show();
                        $('#end_time_div_' + containerId).show();
                        $('#available_start_time_' + containerId).attr('required', true);
                        $('#available_end_time_' + containerId).attr('required', true);
                    } else {
                        $('#start_time_div_' + containerId).hide();
                        $('#end_time_div_' + containerId).hide();
                        $('#available_start_time_' + containerId).removeAttr('required');
                        $('#available_end_time_' + containerId).removeAttr('required');
                    }
                });
            });

            $(document).ready(function() {
                //AJAX FORM SUBMIT
                $("#form_hotel_room_amenities_details").submit(function(event) {
                    var form = $('#form_hotel_room_amenities_details')[0];
                    var data = new FormData(form);
                    // $(this).find("button[id='submit_hotel_room_amenities_details_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_hotel.php?type=hotel_amenities_details',
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
                            if (response.errors.amenities_title_required) {
                                TOAST_NOTIFICATION('warning', 'Amenities Title Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.amenities_qty_required) {
                                TOAST_NOTIFICATION('warning', 'Amenities Qty Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.amenities_availability_type_required) {
                                TOAST_NOTIFICATION('warning', 'Availability Type Required', 'Warning !!!',
                                    '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.amenities_start_time_required) {
                                TOAST_NOTIFICATION('warning', 'Start Time Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.amenities_end_time_required) {
                                TOAST_NOTIFICATION('warning', 'End Time Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.amenities_status_required) {
                                TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Amenities Details Added', 'Success !!!', '',
                                    '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Amenities Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                location.assign(response.redirect_URL);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Amenities Details',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Amenities Details',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
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

            function removeAMENITIES(AMENITIES_ID, HOT_ID) {
                $('.receiving-delete-form-data').load('engine/ajax/__ajax_add_hotel_form.php?type=delete_amenities&ID=' +
                    AMENITIES_ID + '&HOT_ID=' + HOT_ID,
                    function() {
                        const container = document.getElementById("showDELETEMODAL");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'delete_amenities') :

        $amenities_ID = $_GET['ID'];
        $HOT_ID = $_GET['HOT_ID'];
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
                    <p class="mb-0 mt-2">Are you sure? want to delete this amenities
                        <b>"<?= getAMENITYDETAILS($amenities_ID, 'amenities_title'); ?>"</b><br /> This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-label-github waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" onclick="confirmAMENITIESDELETE('<?= $amenities_ID; ?>','<?= $HOT_ID; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
            </div>
        </div>
        <script>
            function confirmAMENITIESDELETE(AMENITIES_ID, HOT_ID) {
                $.ajax({
                    type: "POST",
                    url: "engine/ajax/__ajax_manage_hotel.php?type=confirm_amenities_delete",
                    data: {
                        _ID: AMENITIES_ID,
                        _HOT_ID: HOT_ID
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (!response.success) {
                            //NOT SUCCESS RESPONSE
                            if (response.result_success) {
                                TOAST_NOTIFICATION('error', 'Unable to delete the amenities', 'Error !!!', '', '', '',
                                    '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            $('#showDELETEMODAL').modal('hide');
                            TOAST_NOTIFICATION('success', 'Amenities Deleted Successfully', 'Success !!!', '', '', '',
                                '', '', '', '', '', '');
                            $('#amenities_' + AMENITIES_ID).remove();
                        }
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'hotel_review') :

        $hotel_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotel_ID != '' && $hotel_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=edit&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
        else :
            $basic_info_url = 'javascript:;';
            $room_details_url = 'javascript:;';
            $room_amenities_url = 'javascript:;';
            $hotel_pricebook_url = 'javascript:;';
            $hotel_feedback_url = 'javascript:;';
            $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
        endif;

    ?>
        <!-- Default -->
        <div class="row sticky-element">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle  disble-stepper-num">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Basic Info</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#personal-info-validation">
                            <a href="<?= $room_details_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Rooms</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#social-links-validation">
                            <a href="<?= $room_amenities_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Amenities</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $hotel_pricebook_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Price Book</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#price-book">
                            <a href="<?= $hotel_feedback_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle  active-stepper">5</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title stepper-title">Review & Feedback</h4>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $preview_url; ?>" class="step-trigger pe-2 ps-2">
                                <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">6</span>
                                <span class="bs-stepper-label mt-3">
                                    <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Preview</h4>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row mt-3 justify-content-center">
                    <div class="col-4">
                        <div class="card mb-4 p-4">
                            <!-- Plugins css Ends-->
                            <form id="form_hotel_review" class="row g-2" action="" method="post" data-parsley-validate>
                                <div class="col-12 row g-2" id="ajax_form_review">
                                    <div class="col-12">
                                        <label class="form-label text-primary fs-5" for="hotel_rating">Rating</label>
                                        <div class="form-group">

                                            <select class="form-control" name="hotel_rating" id="hotel_rating" required>
                                                <?= getSTARRATINGCOUNT($hotel_rating, 'select'); ?></select>
                                        </div>
                                        <p class="pe-2 my-2">All reviews are from genuine customers</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label w-100" for="review_description">Feedback<span class=" text-danger"> *</span></label>
                                        <div class="form-group">
                                            <textarea class="form-control" id="review_description" name="review_description" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="hiddenHOTEL_ID" id="hiddenHOTEL_ID" value="<?= $hotel_ID; ?>" />
                                    <input type="hidden" name="hidden_hotel_review_id" id="hidden_hotel_review_id">
                                </div>
                                <div class="col-12 d-flex justify-content-between pt-4">
                                    <div>
                                        <button type="button" onclick="show_RATING_FORM('', '')" class="btn btn-label-secondary waves-effect">Cancel</button>
                                    </div>
                                    <div>
                                        <button type="submit" id="submit_hotel_basic_info_btn" class="btn btn-primary btn-md">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-8">
                        <div class="card mb-4 p-4">
                            <div class="dataTable_select text-nowrap">
                                <h4>List of Reviews</h4>
                                <div class="text-nowrap table-responsive table-bordered">
                                    <table id="hotel_review_LIST" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>S.no</th>
                                                <th>Rating</th>
                                                <th>Description</th>
                                                <th>Created On</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-between text-center pt-4">
                        <div>
                            <a href="<?= $hotel_pricebook_url; ?>" class="btn btn-secondary">Back</a>
                        </div>
                        <div>
                            <a href="<?= $preview_url; ?>" class="btn btn-primary btn-md">Update & Continue</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="showDELETEMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content receiving-delete-form-data">

                    </div>
                </div>
            </div>
            <link rel="stylesheet" href="assets/vendor/libs/rateyo/rateyo.css" />
            <script src="assets/js/extended-ui-star-ratings.js"></script>
            <script src="assets/vendor/libs/rateyo/rateyo.js"></script>

            <script>
                $(document).ready(function() {
                    $("#edited_star_ratings").rateYo({
                        rating: 1,
                        fullStar: true,
                        onSet: function(rating, rateYoInstance) {
                            //alert("Rating is set to: " + rating);
                            $("#hotel_rating").val(rating);
                        }
                    });

                    $('#hotel_review_LIST').DataTable({
                        dom: 'Blfrtip',
                        "bFilter": true,
                        buttons: [
                            'copyHtml5',
                            'excelHtml5',
                            'csvHtml5'
                        ],
                        initComplete: function() {
                            $('.buttons-copy').html(
                                '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>'
                            );


                            $('.buttons-excel').html(
                                '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>'
                            );

                            $('.buttons-csv').html(
                                '<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>'
                            );
                        },
                        ajax: {
                            "url": "engine/json/__JSONhotelreviewlist.php?id=<?= $hotel_ID; ?>",
                            "type": "GET"
                        },
                        columns: [{
                                data: "count"
                            }, //0
                            {
                                data: "hotel_rating"
                            }, //1
                            {
                                data: "hotel_description"
                            }, //2
                            {
                                data: "createdon"
                            }, //3
                            {
                                data: "modify"
                            } //4
                        ],
                        columnDefs: [{
                                "targets": 1,
                                "data": "hotel_rating",
                                "render": function(data, type, full) {
                                    if (data == 1) {
                                        return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i></h2>';
                                    } else if (data == 2) {
                                        return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';
                                    } else if (data == 3) {
                                        return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';

                                    } else if (data == 4) {
                                        return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';

                                    } else if (data >= 5) {
                                        return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2"><i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i> <i class="ti ti-star-filled"></i></h2>';

                                    }
                                }
                            },
                            {
                                "targets": 4,
                                "data": "modify",
                                "render": function(data, type, full) {
                                    return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" onclick="show_RATING_FORM(<?= $hotel_ID; ?>, ' +
                                        data +
                                        ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showHOTELREVIEWDELETEMODAL( ' +
                                        data +
                                        ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                                }
                            }

                        ],
                    });

                    //AJAX FORM SUBMIT
                    $("#form_hotel_review").submit(function(event) {
                        var form = $('#form_hotel_review')[0];
                        var data = new FormData(form);
                        //$(this).find("button[id='submit_hotel_basic_info_btn']").prop('disabled', true);
                        $.ajax({
                            type: "post",
                            url: 'engine/ajax/__ajax_manage_hotel.php?type=hotel_review',
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
                                if (response.errors.review_description_required) {
                                    TOAST_NOTIFICATION('warning', 'Review Description Required',
                                        'Warning !!!', '', '', '', '', '', '', '', '', '');
                                }
                            } else {
                                //SUCCESS RESPOSNE
                                if (response.i_result == true) {
                                    //RESULT SUCCESS
                                    TOAST_NOTIFICATION('success', 'Hotel Review Added', 'Success !!!', '',
                                        '', '', '', '', '', '', '', '');
                                    $('#hiddenHOTEL_REVIEW_ID').val('');
                                    show_RATING_FORM(response.guide_id, '');
                                    $('#hotel_review_LIST').DataTable().ajax.reload();
                                } else if (response.u_result == true) {
                                    //RESULT SUCCESS
                                    TOAST_NOTIFICATION('success', 'Hotel Review Updated', 'Success !!!', '',
                                        '', '', '', '', '', '', '', '');
                                    $('#hiddenHOTEL_REVIEW_ID').val('');
                                    show_RATING_FORM(response.guide_id, '');
                                    $('#hotel_review_LIST').DataTable().ajax.reload();
                                } else if (response.i_result == false) {
                                    //RESULT FAILED
                                    TOAST_NOTIFICATION('success', 'Unable to Add Guide Review',
                                        'Success !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.u_result == false) {
                                    //RESULT FAILED
                                    TOAST_NOTIFICATION('success', 'Unable to Update Guide Review',
                                        'Success !!!', '', '', '', '', '', '', '', '', '');
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

                function show_RATING_FORM(id, review_id) {
                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_add_hotel_form.php?type=hotel_rating_form',
                        data: {
                            ID: id,
                            REVIEW_ID: review_id,
                            // Add more data key-value pairs as needed
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#review_description').val(response.hotel_description);
                            $("#hotel_rating").val("");
                            $('#hidden_hotel_review_id').val(response.hotel_review_id);
                            $('#submit_hotel_basic_info_btn').text(response.hotel_review_button);
                            $("#hotel_rating").val(response.hotel_rating);
                            $("#edited_star_ratings").rateYo({
                                rating: response.hotel_rating,
                                fullStar: true,
                                onSet: function(rating, rateYoInstance) {
                                    // alert("Rating is set to: " + rating);
                                    $("#hotel_rating").val(rating);
                                }
                            });
                        }
                    });
                }

                //SHOW DELETE POPUP

                function showHOTELREVIEWDELETEMODAL(ID) {

                    $('.receiving-delete-form-data').load('engine/ajax/__ajax_manage_hotel.php?type=hotel_review_delete&ID=' + ID,
                        function() {
                            const container = document.getElementById("showDELETEMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function confirmHOTELREVIEWDELETE(ID) {

                    $.ajax({
                        type: 'post',
                        url: 'engine/ajax/__ajax_manage_hotel.php?type=deleted_review',
                        data: {
                            ID: ID,

                            // Add more data key-value pairs as needed
                        },
                        dataType: 'json',
                        success: function(response) {
                            location.reload();
                            $('#showDELETEMODAL').modal('hide');
                            $('#hotel_review_LIST').DataTable().ajax.reload();
                        }
                    });
                }
            </script>
        <?php

    elseif ($_GET['type'] == 'hotel_rating_form') :

        $response = [];

        $HOTEL_ID = $_POST['ID'];
        $HOTEL_REVIEW_ID = $_POST['REVIEW_ID'];

        if ($HOTEL_REVIEW_ID != '' && $HOTEL_REVIEW_ID != 0) :
            $btn_label_form = 'Update';
        else :
            $btn_label_form = 'Save';
        endif;

        $select_guideREVIEW_query = sqlQUERY_LABEL("SELECT `hotel_rating`, `hotel_description` FROM `dvi_hotel_review_details` WHERE `hotel_review_id`='$HOTEL_REVIEW_ID' AND `hotel_id`='$HOTEL_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_GUIDE_REVIEW_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_guideREVIEW_query)) :
            $hotel_rating = $fetch_list_data['hotel_rating'];
            $hotel_description = $fetch_list_data['hotel_description'];
        endwhile;

        $response['hotel_rating'] = $hotel_rating;
        $response['hotel_description'] = $hotel_description;
        $response['hotel_review_id'] = $HOTEL_REVIEW_ID;
        $response['hotel_review_button'] = $btn_label_form;

        echo json_encode($response);
    elseif ($_GET['type'] == 'hotel_preview') :

        $hotel_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotel_ID != '' && $hotel_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=edit&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
        else :
            $basic_info_url = 'hotel.php?route=add&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=add&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=add&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=add&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=add&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
        endif;
        ?>
            <!-- STEPPER -->
            <div class="row sticky-element">
                <div class="col-md-12">
                    <div id="wizard-validation" class="bs-stepper box-shadow-none">
                        <div class="bs-stepper-header border-0 justify-content-center py-2">
                            <div class="step">
                                <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">1</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $room_details_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">2</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Rooms</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $room_amenities_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Amenities</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $hotel_pricebook_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Price Book</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $hotel_feedback_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">5</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Review & feedback
                                        </h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $preview_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle active-stepper">6</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title">Preview</h4>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $select_list = sqlQUERY_LABEL("SELECT `hotel_id`, `hotel_name`, `hotel_code`, `hotel_mobile`, `hotel_email`,`hotel_place`, `hotel_country`, `hotel_state`, `hotel_city`,`hotel_address`,`hotel_category`,`hotel_pincode`,`hotel_latitude`,`hotel_longitude`,`status` FROM `dvi_hotel` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                $counter++;
                $hotel_name = $fetch_data['hotel_name'];
                $hotel_code = $fetch_data['hotel_code'];
                $hotel_mobile = $fetch_data['hotel_mobile'];
                $hotel_email = $fetch_data['hotel_email'];
                $hotel_place = $fetch_data['hotel_place'];
                $hotel_country = $fetch_data['hotel_country'];
                $hotel_state = $fetch_data['hotel_state'];
                $hotel_city = $fetch_data['hotel_city'];
                $hotel_address = $fetch_data['hotel_address'];
                $hotel_category = $fetch_data['hotel_category'];
                $hotel_pincode = $fetch_data['hotel_pincode'];
                $hotel_latitude = $fetch_data['hotel_latitude'];
                $hotel_longitude = $fetch_data['hotel_longitude'];
                $status = $fetch_data['status'];
                if ($status == 1) :
                    $status = 'Active';
                    $status_color = 'text-success';
                else :
                    $status = 'In-Active';
                    $status_color = 'text-danger';
                endif;
            endwhile;
            ?>
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card mb-4 p-4">
                        <div class="row">
                            <h5 class="text-primary">Basic Info</h5>
                            <div class="col-md-3">
                                <label>Hotel Name</label>
                                <p class="text-light"><?= $hotel_name; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Hotel Code</label>
                                <p class="text-light"><?= $hotel_code; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Hotel Mobile </label>
                                <p class="text-light"><?= $hotel_mobile; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Hotel Email</label>
                                <p class="text-light"><?= $hotel_email; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Hotel Place</label>
                                <p class="text-light"><?= $hotel_place; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Hotel Category</label>
                                <p class="text-light"><?= getHOTEL_CATEGORY_DETAILS($hotel_category, 'label'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Country</label>
                                <p class="text-light"><?= getCOUNTRYLIST($hotel_country, 'country_label'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>State</label>
                                <p class="text-light"><?= getSTATELIST('', $hotel_state, 'state_label'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>City</label>
                                <p class="text-light"><?= getCITYLIST('', $hotel_city, 'city_label'); ?></p>
                            </div>
                            <div class="col-md-3">
                                <label>Pincode</label>
                                <p class="text-light"><?= $hotel_pincode; ?></p>
                            </div>
                            <?php if ($hotel_latitude != '') : ?>
                                <div class="col-md-3">
                                    <label>Latitude</label>
                                    <p class="text-light"><?= $hotel_latitude; ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if ($hotel_longitude != '') : ?>
                                <div class="col-md-3">
                                    <label>Longitude</label>
                                    <p class="text-light"><?= $hotel_longitude; ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-3">
                                <label>Address</label>
                                <p class="text-light"><?= $hotel_address; ?></p>
                            </div>
                            <div class="col-md-3">
                                <label> Hotel Status</label>
                                <p class="<?= $status_color ?> fw-bold"><?= $status; ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <?php
                            $select_room_details = sqlQUERY_LABEL("SELECT `room_ID`, `room_title`, `room_ref_code`, `total_max_adults`, `total_max_childrens`,`air_conditioner_availability`,`check_in_time`, `check_out_time`, `gst_type`, `gst_percentage`, `breakfast_included`, `lunch_included`,`dinner_included`,`inbuilt_amenities`,`extra_bed_charge` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $total_rooms_num_rows_count = sqlNUMOFROW_LABEL($select_room_details);
                            if ($total_rooms_num_rows_count > 0) :
                                while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_room_details)) :
                                    $room_counter++;
                                    $room_ID = $fetch_room_data['room_ID'];
                                    $room_title = $fetch_room_data['room_title'];
                                    $room_ref_code = $fetch_room_data['room_ref_code'];
                                    $total_max_adults = $fetch_room_data['total_max_adults'];
                                    $total_max_childrens = $fetch_room_data['total_max_childrens'];
                                    $air_conditioner_availability = $fetch_room_data['air_conditioner_availability'];
                                    $check_in_time = date('h:i A', strtotime($fetch_room_data['check_in_time']));
                                    $check_out_time = date('h:i A', strtotime($fetch_room_data['check_out_time']));
                                    $gst_status = $fetch_room_data['gst_type'];
                                    $gst_status_type = getGSTTYPE($gst_status, 'label');
                                    $gst_status_value = $fetch_room_data['gst_percentage'];
                                    $breakfast_included = $fetch_room_data['breakfast_included'];
                                    $lunch_included = $fetch_room_data['lunch_included'];
                                    $dinner_included = $fetch_room_data['dinner_included'];
                                    $extra_bed_charge = $fetch_room_data['extra_bed_charge'];
                                    $inbuilt_amenities = $fetch_room_data['inbuilt_amenities'];

                                    $food_applicable = NULL;
                                    if ($breakfast_included == 0 && $lunch_included == 0 && $dinner_included == 0) :
                                        $food_applicable = 'N/A ';
                                    else :
                                        if ($breakfast_included == 1) :
                                            $food_applicable .= 'Breakfast,';
                                        endif;
                                        if ($lunch_included == 1) :
                                            $food_applicable .= ' Lunch,';
                                        endif;
                                        if ($dinner_included == 1) :
                                            $food_applicable .= ' Dinner,';
                                        endif;
                                    endif;
                            ?>
                                    <h5 class="text-primary">Rooms #<?= $room_counter . '/' . $total_rooms_num_rows_count; ?></h5>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Room Title</label>
                                            <p class="text-light"><?= $room_title; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Room Reference Code</label>
                                            <p class="text-light"><?= $room_ref_code; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Max Adults</label>
                                            <p class="text-light"><?= $total_max_adults; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Total Max Children</label>
                                            <p class="text-light"><?= $total_max_childrens; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Air Conditioner</label>
                                            <p class="text-light"><?= get_YES_R_NO($air_conditioner_availability, 'label'); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Food Applicable</label>
                                            <p class="text-light"><?= substr($food_applicable, 0, -1); ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Check In Time</label>
                                            <p class="text-light"><?= $check_in_time; ?> </p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Check Out Time</label>
                                            <p class="text-light"><?= $check_out_time; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Extra Bed Charge</label>
                                            <p class="text-light">&#x20b9; <?= $extra_bed_charge; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>GST Type</label>
                                            <p class="text-light"><?= $gst_status_type; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>GST Percentage</label>
                                            <p class="text-light"><?= $gst_status_value; ?> %</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Inbuilt Amenities</label>
                                            <p class="text-light">
                                                <?php
                                                if ($inbuilt_amenities != "") :
                                                    echo get_INBUILT_AMENITIES($inbuilt_amenities, 'multilabel');
                                                else :
                                                    echo "N/A";
                                                endif;
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <h5 class="text-primary">Gallery</h5>
                                        <?php
                                        $select_room_gallery_details = sqlQUERY_LABEL("SELECT `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID' and `room_id` = '$room_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                                        $total_room_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_room_gallery_details);
                                        if ($total_room_gallery_num_rows_count > 0) :
                                            while ($fetch_room_gallery_data = sqlFETCHARRAY_LABEL($select_room_gallery_details)) :
                                                $room_gallery_name = $fetch_room_gallery_data['room_gallery_name'];
                                        ?>
                                                <div class="col-md-2 my-2">
                                                    <div class="room-details-image-head">
                                                        <img src="<?= BASEPATH; ?>/uploads/room_gallery/<?= $room_gallery_name; ?>" style="width:100%" onclick="show_HOTEL_ROOM_GALLERY('<?= $room_ID; ?>','<?= $hotel_ID; ?>')" class="room-details-shadow img-fluid cursor rounded">
                                                    </div>
                                                </div>
                                            <?php endwhile;
                                        else :
                                            ?>
                                            <div class="row">
                                                <div class="text-center">
                                                    <img src="../head/assets/img/dummy/no-preview.png" alt="" width="80px" class="img-fluid rounded">
                                                    <p class="ms-2">No Gallery Found</p>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <hr />
                                <?php endwhile;
                            else : ?>
                                <div class="row">
                                    <div class="text-center">
                                        <h5 class="ms-2">No Rooms Found</h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php
                            $select_amenities_details = sqlQUERY_LABEL("SELECT `hotel_amenities_id`,`amenities_title`, `amenities_code`, `quantity`, `availability_type`, `start_time`, `end_time`, `status` FROM `dvi_hotel_amenities` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                            $total_room_num_rows_count = sqlNUMOFROW_LABEL($select_amenities_details);
                            if ($total_room_num_rows_count > 0) :
                                while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_amenities_details)) :
                                    $amenities_counter++;
                                    $hotel_amenities_id = $fetch_room_data['hotel_amenities_id'];
                                    $amenities_title = $fetch_room_data['amenities_title'];
                                    $amenities_title = htmlspecialchars_decode(htmlspecialchars_decode($amenities_title, ENT_QUOTES), ENT_QUOTES);
                                    $amenities_code = $fetch_room_data['amenities_code'];
                                    $quantity = $fetch_room_data['quantity'];
                                    $availability_type = $fetch_room_data['availability_type'];
                                    $end_time = $fetch_room_data['end_time'];
                                    $start_time = $fetch_room_data['start_time'];
                                    $status = $fetch_room_data['status'];
                                    $availability_type_label = get_AMENITIES_AVILABILITY_TYPE($availability_type, 'label');
                                    if ($status == 1) :
                                        $status = 'Active';
                                        $status_color = 'text-success';
                                    else :
                                        $status = 'In-Active';
                                        $status_color = 'text-danger';
                                    endif;

                                    if ($availability_type == 1) :
                                        $start_time = '--';
                                        $end_time = '--';
                                    elseif ($availability_type == 2) :
                                        $formatted_start_time = date('h:i A', strtotime($start_time));
                                        $formatted_end_time = date('h:i A', strtotime($end_time));
                                        $start_time = $formatted_start_time;
                                        $end_time = $formatted_end_time;
                                    else :
                                        $start_time = '--';
                                        $end_time = '--';
                                    endif;
                            ?>
                                    <div class="row">
                                        <h5 class="text-primary">Amenities #<?= $amenities_counter . '/' . $total_room_num_rows_count; ?></h5>
                                        <div class="col-md-3">
                                            <label>Amenities Title</label>
                                            <p class="text-light"><?= $amenities_title ?></p>
                                        </div>
                                        <!--<div class="col-md-2">
                                    <label>Amenities Code</label>
                                    <p class="text-light"><?= $amenities_code ?></p>
                                </div>-->
                                        <div class="col-md-1">
                                            <label>Quantity</label>
                                            <p class="text-light"><?= $quantity; ?></p>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Availability Type</label>
                                            <p class="text-light"><?= $availability_type_label; ?></p>
                                        </div>
                                        <div class="col-md-auto">
                                            <label>Start Time</label>
                                            <p class="text-light"><?= $start_time; ?></p>
                                        </div>
                                        <div class="col-md-auto">
                                            <label>End Time</label>
                                            <p class="text-light"><?= $end_time; ?></p>
                                        </div>
                                        <div class="col-md-auto">
                                            <label> Status</label>
                                            <p class="<?= $status_color ?> fw-bold"><?= $status; ?></p>
                                        </div>
                                    </div>
                                <?php endwhile;
                            else : ?>
                                <div class="row">
                                    <div class="text-center">
                                        <h5 class="ms-2">No Amenities Found</h5>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <hr>
                            <div class="col-12">
                                <div class="card-datatable dataTable_select text-nowrap">
                                    <h5 class="text-primary">List of Reviews</h5>
                                    <div class="table-responsive">
                                        <table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
                                            <thead class="table-head">
                                                <tr>
                                                    <th>S.no</th>
                                                    <th>Rating</th>
                                                    <th>Description</th>
                                                    <th>Created On</th>
                                                    <!-- <th>Actions</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $select_list = sqlQUERY_LABEL("SELECT `hotel_review_id`, `hotel_id`, `hotel_rating`, `hotel_description`, `createdon` FROM `dvi_hotel_review_details` WHERE `deleted` = '0' AND `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                                $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                                if ($select_review_count > 0) :
                                                    while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                        $review_counter++;
                                                        $hotel_review_id = $fetch_data['hotel_review_id'];
                                                        $hotel_id = $fetch_data['hotel_id'];
                                                        $hotel_rating = $fetch_data['hotel_rating'];
                                                        $hotel_description = $fetch_data['hotel_description'];
                                                        $createdon = $fetch_data['createdon'];
                                                ?>
                                                        <tr>
                                                            <td><?= $review_counter; ?></td>
                                                            <td><?= $hotel_rating; ?></td>
                                                            <td><?= $hotel_description; ?></td>
                                                            <td><?= $createdon; ?></td>
                                                            <!-- <td><?= $hotel_review_id; ?></td> -->
                                                        </tr>
                                                    <?php endwhile;
                                                else : ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No Reviews Found !!!</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="<?= $hotel_feedback_url; ?>" class="btn btn-secondary btn-md">Back</a>
                                </div>
                                <div>
                                    <a href="hotel.php" class="btn btn-primary btn-md">Save</a>
                                </div>
                            </div>
                            <script>
                                $(function() {
                                    var e, t = $(".sticky-element"),
                                        t = (window.Helpers.initCustomOptionCheck(), e = Helpers.isNavbarFixed() ? $(
                                            ".layout-navbar").height() + 7 : 0, t.length && t.sticky({
                                            topSpacing: e,
                                            zIndex: 9
                                        }));
                                });

                                function show_HOTEL_ROOM_GALLERY(ROOM_ID, HOT_ID) {
                                    $('.receiving-swiper-room-form-data').load(
                                        'engine/ajax/__ajax_add_hotel_form.php?type=show_hotel_room_gallery&ID=' + ROOM_ID +
                                        '&HOT_ID=' + HOT_ID,
                                        function() {
                                            const container = document.getElementById("showSWIPERGALLERYMODAL");
                                            const modal = new bootstrap.Modal(container);
                                            modal.show();
                                        });
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        <?php

    elseif ($_GET['type'] == 'show_hotel_room_gallery') :

        $room_ID = $_GET['ID'];
        $HOT_ID = $_GET['HOT_ID'];

        $select_room_gallery_details = sqlQUERY_LABEL("SELECT `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' AND `hotel_id` = '$HOT_ID' and `room_id` = '$room_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
        $total_room_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_room_gallery_details);
        ?>
            <div class="modal-header">
                <h5 class="mb-1 fw-bold"><?= getROOM_DETAILS($room_ID, 'room_title'); ?> - Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" class="carousel slide pb-4 mb-2" data-bs-interval="false">
                <ol class="carousel-indicators">
                    <?php for ($i = 0; $i < $total_room_gallery_num_rows_count; $i++) : ?>
                        <li data-bs-target="#room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" data-bs-slide-to="<?= $i; ?>" class="active" aria-current="true"></li>
                    <?php endfor; ?>
                </ol>
                <div class="carousel-inner">
                    <?php if ($total_room_gallery_num_rows_count > 0) :
                        $counter = 0;
                        while ($fetch_room_gallery_data = sqlFETCHARRAY_LABEL($select_room_gallery_details)) :
                            $counter++;
                            $room_gallery_name = $fetch_room_gallery_data['room_gallery_name'];
                            if ($counter == 1) :
                                $active_slider = 'active';
                            else :
                                $active_slider = '';
                            endif;
                    ?>
                            <div class="carousel-item <?= $active_slider; ?>">
                                <div class="onboarding-media">
                                    <div class="d-flex justify-content-center">
                                        <img src="<?= BASEPATH; ?>/uploads/room_gallery/<?= $room_gallery_name; ?>" alt="girl-with-laptop-light" class="img-fluid" data-app-light-img="illustrations/girl-with-laptop-light.png" data-app-dark-img="illustrations/girl-with-laptop-dark.html">
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                    else : ?>
                        <div class="carousel-item active">
                            <div class="onboarding-media">
                                <div class="row">
                                    <div class="text-center">
                                        <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                                        <p class="ms-2">No Gallery Found</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <a class="carousel-control-prev" href="#room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" role="button" data-bs-slide="prev">
                    <i class="ti ti-chevrons-left me-2"></i><span>Previous</span>
                </a>
                <a class="carousel-control-next" href="#room_gallery_<?= $HOT_ID . '_' . $room_ID; ?>" role="button" data-bs-slide="next">
                    <span>Next</span><i class="ti ti-chevrons-right ms-2"></i>
                </a>
            </div>
        <?php
    elseif ($_GET['type'] == 'room_details') :

        $hotel_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($hotel_ID != '' && $hotel_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'hotel.php?route=edit&formtype=basic_info&id=' . $hotel_ID;
            $room_details_url = 'hotel.php?route=edit&formtype=room_details&id=' . $hotel_ID;
            $room_amenities_url = 'hotel.php?route=edit&formtype=room_amenities&id=' . $hotel_ID;
            $hotel_pricebook_url = 'hotel.php?route=edit&formtype=hotel_pricebook&id=' . $hotel_ID;
            $hotel_feedback_url = 'hotel.php?route=edit&formtype=hotel_review&id=' . $hotel_ID;
            $preview_url = 'hotel.php?route=edit&formtype=hotel_preview&id=' . $hotel_ID;
        else :
            $basic_info_url = 'javascript:void();';
            $room_details_url = 'javascript:void();';
            $room_amenities_url = 'javascript:void();';
            $hotel_pricebook_url = 'javascript:void();';
            $hotel_feedback_url = 'javascript:void();';
            $preview_url = 'hotel.php?route=add&formtype=hotel_preview&id=' . $hotel_ID;
        endif;
        ?>
            <div class="row" id="rooms_tab_section">
                <div class="col-12">
                    <div id="wizard-validation" class="bs-stepper mt-2">
                        <div class="bs-stepper-header border-0 justify-content-center py-2">
                            <div class="step">
                                <a href="<?= $basic_info_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-title">1</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $room_details_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle  active-stepper">2</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title">Rooms</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $room_amenities_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">3</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Amenities</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $hotel_pricebook_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">4</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Price Book</h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $hotel_feedback_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">5</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Review & Feedback </h4>
                                    </span>
                                </a>
                            </div>
                            <div class="line">
                                <i class="ti ti-chevron-right"></i>
                            </div>
                            <div class="step">
                                <a href="<?= $preview_url; ?>" class="step-trigger pe-2 ps-2">
                                    <span class="stepper_for_hotel bs-stepper-circle disble-stepper-num">6</span>
                                    <span class="bs-stepper-label mt-3">
                                        <h4 class="stepper_for_hotel bs-stepper-title  disble-stepper-title">Preview</h4>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" class="btn btn-primary waves-effect mb-3 add_item_room_btn">+ Add Rooms</button>
                </div>
            </div>
            <?php
            if ($hotel_ID != '' && $hotel_ID != 0) :
                $select_hotel_room_list_query = sqlQUERY_LABEL("SELECT `room_ID`, `room_type_id`, `preferred_for`, `room_title`, `no_of_rooms_available`, `room_ref_code`, `air_conditioner_availability`,`total_max_adults`, `total_max_childrens`, `check_in_time`, `check_out_time`, `gst_type`, `gst_percentage`, `breakfast_included`, `lunch_included`, `dinner_included`,`inbuilt_amenities`,  `status` FROM `dvi_hotel_rooms` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());
                $total_hotel_rooms_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_room_list_query);
            endif;
            ?>
            <div class="row">
                <div class="col-12">
                    <div class="card p-4">
                        <form id="form_hotel_room_details" action="" method="POST" enctype="multipart/form-data" data-parsley-validate>
                            <div class="col-md-12">
                                <div id="show_room_item"></div>
                            </div>
                            <?php
                            if ($total_hotel_rooms_num_rows_count > 0) :
                                while ($fetch_room_data = sqlFETCHARRAY_LABEL($select_hotel_room_list_query)) :
                                    $room_count++;
                                    $room_ID = $fetch_room_data['room_ID'];
                                    $room_type_id = $fetch_room_data['room_type_id'];
                                    $preferred_for = $fetch_room_data['preferred_for'];
                                    $room_title = $fetch_room_data['room_title'];
                                    $no_of_rooms_available = $fetch_room_data['no_of_rooms_available'];
                                    $room_ref_code = $fetch_room_data['room_ref_code'];
                                    $air_conditioner_availability = $fetch_room_data['air_conditioner_availability'];
                                    $total_max_adults = $fetch_room_data['total_max_adults'];
                                    $total_max_childrens = $fetch_room_data['total_max_childrens'];
                                    $check_in_time = $fetch_room_data['check_in_time'];
                                    $check_out_time = $fetch_room_data['check_out_time'];
                                    $gst_status = $fetch_room_data['gst_type'];
                                    $gst_status_value = $fetch_room_data['gst_percentage'];
                                    $breakfast_included = $fetch_room_data['breakfast_included'];
                                    $lunch_included = $fetch_room_data['lunch_included'];
                                    $dinner_included = $fetch_room_data['dinner_included'];
                                    $inbuilt_amenities = $fetch_room_data['inbuilt_amenities'];
                                    $status = $fetch_room_data['status'];
                                    $room_type_title = getROOMTYPE_DETAILS($room_type_id, 'room_type_title');

                                    $select_hotel_room_gallery_list_query = sqlQUERY_LABEL("SELECT `hotel_room_gallery_details_id`, `room_id`, `room_gallery_name` FROM `dvi_hotel_room_gallery_details` WHERE `deleted` = '0' and `hotel_id` = '$hotel_ID' and `room_id` = '$room_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_GALLERY_LIST:" . sqlERROR_LABEL());
                                    $total_hotel_rooms_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_hotel_room_gallery_list_query);

                                    if ($breakfast_included == 1) :
                                        $breakfast_included_status = 'checked';
                                    else :
                                        $breakfast_included_status = '';
                                    endif;
                                    if ($lunch_included == 1) :
                                        $lunch_included_status = 'checked';
                                    else :
                                        $lunch_included_status = '';
                                    endif;
                                    if ($dinner_included == 1) :
                                        $dinner_included_status = 'checked';
                                    else :
                                        $dinner_included_status = '';
                                    endif;
                            ?>
                                    <div class="row g-3" id="room_<?= $room_ID; ?>">
                                        <div class="col-md-12">
                                            <h6 class="m-0">Room <?= $room_count; ?>/<?= $total_hotel_rooms_num_rows_count; ?></h6>
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="hotel_room_type_title_<?= $room_count - 1; ?>">Room Type <span class="text-danger">*</span></label>
                                            <input type="text" id="hotel_room_type_title_<?= $room_count - 1; ?>" name="hotel_room_type_title[]" required class="form-control" placeholder="Enter the Room type" value="<?= $room_type_title; ?>" required autocomplete="off" />
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="hotel_room_title">Room Title <span class="text-danger">*</span></label>
                                            <input type="text" id="hotel_room_title" name="hotel_room_title[]" required class="form-control" placeholder="Enter the Room Title" value="<?= $room_title; ?>" required autocomplete="off" />
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="no_of_rooms_available">No of Rooms Availability<span class="text-danger">*</span></label>
                                            <input type="text" id="no_of_rooms_available" name="no_of_rooms_available[]" required class="form-control" placeholder="Enter the No. of Rooms Available" value="<?= $no_of_rooms_available; ?>" autocomplete="off" />
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="room_ref_code">Room Code <span class="text-danger">*</span></label>
                                            <input type="text" id="room_ref_code" name="room_ref_code[]" required class="form-control" placeholder="Enter the Ref Code" value="<?= $room_ref_code; ?>" disabled autocomplete="off" />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label class="form-label" for="preferred_for_<?= $room_count - 1; ?>">Prefered For <span class="text-danger">*</span></label>
                                                <div class="select2-primary"><select data-parsley-errors-container="error_preferred_for" id="preferred_for_<?= $room_count - 1; ?>" name="preferred_for[<?= $room_count - 1; ?>][]" class="select2 form-select form-control " multiple><?= get_HOTEL_PREFERRED_FOR($preferred_for, 'select'); ?></select></div>
                                                <div id="error_preferred_for">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="air_conditioner_avilability">AC Availability
                                                <span class="text-danger">*</span></label>
                                            <select id="air_conditioner_avilability" name="air_conditioner_avilability[]" class="form-control form-select" required><?= get_YES_R_NO($air_conditioner_availability, 'select') ?></select>
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="room_status">Status <span class="text-danger">*</span></label>
                                            <select id="room_status" name="room_status[]" class="form-control form-select" required><?= getSTATUS($status, 'select') ?></select>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group"><label class="form-label" for="total_max_adult">Max Adult <span class="text-danger">*</span></label>
                                                <div class="form-group"><input type="text" id="total_max_adult" name="total_max_adult[]" required class="form-control" value="<?= $total_max_adults; ?>" placeholder="Enter the Max Adult" required autocomplete="off" /></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="total_max_children">Max Children <span class="text-danger">*</span></label>
                                            <div class="form-group"><input type="text" id="total_max_children" name="total_max_children[]" required class="form-control" value="<?= $total_max_childrens; ?>" placeholder="Enter the total children" required autocomplete="off" /></div>
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="check_in_time">Check-In Time <span class="text-danger">*</span></label>
                                            <div class="form-group"><input class="form-control" type="time" placeholder="hh:mm" id="check_in_time" value="<?= $check_in_time; ?>" name="check_in_time[]" required></div>
                                        </div>
                                        <div class="col-md-4"><label class="form-label" for="check_out_time">Check-Out Time <span class="text-danger">*</span></label>
                                            <div class="form-group"><input class="form-control" type="time" placeholder="hh:mm" id="check_out_time" value="<?= $check_out_time; ?>" name="check_out_time[]" required>
                                            </div>
                                        </div>

                                        <!--  <div class="col-md-4">
                                            <div class="form-group"><label class="form-label" for="extra_bed_charge">Extra Bed Charge (₹)<span class="text-danger">*</span></label>
                                                <div class="form-group"><input type="text" id="extra_bed_charge" name="extra_bed_charge[]" required class="form-control" value="<?= $extra_bed_charge; ?>" placeholder="Enter the Charge" required autocomplete="off" /></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group"><label class="form-label" for="child_with_bed_charge">Child with Bed Charge (₹)<span class="text-danger">*</span></label>
                                                <div class="form-group"><input type="text" id="child_with_bed_charge" name="child_with_bed_charge[]" required class="form-control" value="<?= $child_with_bed_charge; ?>" placeholder="Enter the Charge" required autocomplete="off" /></div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group"><label class="form-label" for="child_without_bed_charge">Child Without Bed Charge (₹)<span class="text-danger">*</span></label>
                                                <div class="form-group"><input type="text" id="child_without_bed_charge" name="child_without_bed_charge[]" required class="form-control" value="<?= $child_without_bed_charge; ?>" placeholder="Enter the Charge" required autocomplete="off" /></div>
                                            </div>
                                        </div>-->

                                        <div class="col-md-4"><label class="form-label" for="gst_status">GST Type<span class="text-danger">*</span></label>
                                            <select id="gst_status" name="gst_status[]" class="form-control form-select" required><?= getGSTTYPE($gst_status, 'select') ?></select>
                                        </div>

                                        <div class="col-md-4"><label class="form-label" for="gst_status_value">GST Percentage<span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <select id="gst_status_value_<?= $room_count - 1; ?>" name="gst_status_value[]" class="form-control form-select" required>
                                                    <?= getGSTDETAILS($gst_status_value, 'select'); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group"><label class="form-label" for="inbuild_amenities_<?= $room_count - 1; ?>">Inbuilt Amenities <span class="text-danger">*</span></label>
                                                <div class="select2-primary"><select id="inbuild_amenities_<?= $room_count - 1; ?>" name="inbuild_amenities[<?= $room_count - 1; ?>][]" class="select2 form_select" multiple><?= get_INBUILT_AMENITIES($inbuilt_amenities, 'multiselect'); ?></select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="room_gallery_<?= $room_count - 1; ?>" class="form-label">Room Gallery <span class="text-danger">*</span></label>
                                            <div class="form-group">
                                                <input class="form-control" type="file" accept="image/*" id="room_gallery_<?= $room_count - 1; ?>" name="room_gallery[<?= $room_count - 1; ?>][]" multiple>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <?php if ($total_hotel_rooms_gallery_num_rows_count > 0) : ?>
                                                <div id="show_room_gallery_title_<?= $room_count - 1; ?>">
                                                    <h6>Uploaded Room Gallery</h6>
                                                </div>
                                                <div class="row" id="uploaded_room_image_preview_<?= $room_count - 1; ?>">
                                                    <?php
                                                    while ($fetch_room_gallery_data = sqlFETCHARRAY_LABEL($select_hotel_room_gallery_list_query)) :
                                                        $hotel_room_gallery_details_id = $fetch_room_gallery_data['hotel_room_gallery_details_id'];
                                                        $room_gallery_name = $fetch_room_gallery_data['room_gallery_name'];
                                                    ?>
                                                        <div class="col-md-1" id="room_gallery_id_<?= $hotel_room_gallery_details_id; ?>">
                                                            <div style="position: relative;">
                                                                <img class="me-3 rounded img-fluid" src="<?= BASEPATH; ?>/uploads/room_gallery/<?= $room_gallery_name; ?>" alt="Image Preview">
                                                                <span onclick="removeROOMGALLERY('<?= $hotel_room_gallery_details_id; ?>','<?= $hotel_ID; ?>')" class="badge badge-center rounded-pill bg-danger bg-glow" style="position: absolute; top: -10px; right: -6px; cursor:pointer;justify-content: normal;">
                                                                    <i class="fa-regular fa-circle-xmark"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    <?php endwhile; ?>
                                                </div>
                                            <?php else : ?>
                                                <div id="show_room_gallery_title_<?= $room_count - 1; ?>" style="display:none;">
                                                    <h6>Uploaded Room Gallery</h6>
                                                </div>
                                                <div class="row" id="uploaded_room_image_preview_<?= $room_count - 1; ?>"></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-10 col-xl-10 col-12 mt-3"><label class="form-label" for="modalAddCard">Food Included? (Optional)</label>
                                            <div class="form-group mt-3">
                                                <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="breakfast_included_<?= $room_count - 1; ?>" <?= $breakfast_included_status; ?> name="breakfast_included[<?= $room_count - 1; ?>][]"><label class="form-check-label" for="breakfast_included_<?= $room_count - 1; ?>">Breakfast</label></div>
                                                <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="lunch_included_<?= $room_count - 1; ?>" <?= $lunch_included_status; ?> name="lunch_included[<?= $room_count - 1; ?>][]"><label class="form-check-label" for="lunch_included_<?= $room_count - 1; ?>">Lunch</label></div>
                                                <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="dinner_included_<?= $room_count - 1; ?>" <?= $dinner_included_status; ?> name="dinner_included[<?= $room_count - 1; ?>][]"><label class="form-check-label" for="dinner_included_<?= $room_count - 1; ?>">Dinner</label></div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="hidden_room_ID[]" id="hidden_room_ID" value="<?= $room_ID; ?>" hidden>
                                        <input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="<?= $hotel_ID; ?>" hidden>
                                        <div class=" col-lg-2 col-xl-2 col-12 d-flex align-items-center justify-content-end">
                                            <button type="button" class="btn btn-label-danger mt-4" onclick="removeROOM('<?= $room_ID; ?>','<?= $hotel_ID; ?>')"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                        </div>
                                        <div class="border-bottom border-bottom-dashed my-4"></div>
                                    </div>
                                <?php
                                endwhile;
                            else : ?>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <h6 class="m-0">Room #1</h6>
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="hotel_room_type_title_0">Room Type <span class="text-danger">*</span></label>
                                        <input type="text" id="hotel_room_type_title_0" name="hotel_room_type_title[]" required class="form-control" placeholder="Enter the Room type" required autocomplete="off" />
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="hotel_room_title">Room Title <span class="text-danger">*</span></label>
                                        <input type="text" id="hotel_room_title" name="hotel_room_title[]" required class="form-control" placeholder="Enter the Room Title" required autocomplete="off" />
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="form-label" for="preferred_for_0">Prefered For <span class="text-danger">*</span></label>
                                            <div class="select2-primary"><select data-parsley-errors-container="#error_preferred_for" id="preferred_for_0" name="preferred_for[0][]" class="select2 form_select " multiple><?= get_HOTEL_PREFERRED_FOR('', 'select'); ?></select>
                                                <div id="error_preferred_for">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="no_of_rooms_available">No of Rooms Availability<span class="text-danger">*</span></label>
                                        <input type="text" id="no_of_rooms_available" name="no_of_rooms_available[]" required class="form-control" placeholder="Enter the No. of Rooms Available" autocomplete="off" />
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="air_conditioner_avilability">AC Availability
                                            <span class="text-danger">*</span></label>
                                        <select id="air_conditioner_avilability" name="air_conditioner_avilability[]" class="form-control form-select" required><?= get_YES_R_NO($air_conditioner_avilability, 'select') ?></select>
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="room_status">Status <span class="text-danger">*</span></label>
                                        <select id="room_status" name="room_status[]" class="form-control form-select" required><?= getSTATUS($status, 'select') ?></select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="form-label" for="total_max_adult">Max Adult <span class="text-danger">*</span></label>
                                            <div class="form-group"><input type="text" id="total_max_adult" name="total_max_adult[]" required class="form-control" placeholder="Enter the Max Adult" required autocomplete="off" /></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="total_max_children">Max Children <span class="text-danger">*</span></label>
                                        <div class="form-group"><input type="text" id="total_max_children" name="total_max_children[]" required class="form-control" placeholder="Enter the total children" required autocomplete="off" /></div>
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="check_in_time">Check-In Time <span class="text-danger">*</span></label>
                                        <div class="form-group"><input class="form-control" type="time" placeholder="hh:mm" id="check_in_time" name="check_in_time[]" required></div>
                                    </div>
                                    <div class="col-md-4"><label class="form-label" for="check_out_time">Check-Out Time <span class="text-danger">*</span></label>
                                        <div class="form-group"><input class="form-control" type="time" placeholder="hh:mm" id="check_out_time" name="check_out_time[]" required></div>
                                    </div>

                                    <!--  <div class="col-md-4">
                                        <div class="form-group"><label class="form-label" for="extra_bed_charge">Extra Bed Charge <span class="text-danger">*</span></label>
                                            <div class="form-group"><input type="text" id="extra_bed_charge" name="extra_bed_charge[]" required class="form-control" placeholder="Enter the Charge" required autocomplete="off" /></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group"><label class="form-label" for="child_with_bed_charge">Child with Bed Charge <span class="text-danger">*</span></label>
                                            <div class="form-group"><input type="text" id="child_with_bed_charge" name="child_with_bed_charge[]" required class="form-control" placeholder="Enter the Charge" required autocomplete="off" /></div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group"><label class="form-label" for="child_without_bed_charge">Child Without Bed Charge <span class="text-danger">*</span></label>
                                            <div class="form-group"><input type="text" id="child_without_bed_charge" name="child_without_bed_charge[]" required class="form-control" placeholder="Enter the Charge" required autocomplete="off" /></div>
                                        </div>
                                    </div>-->

                                    <div class="col-md-4"><label class="form-label" for="gst_status">GST Type<span class="text-danger">*</span></label>
                                        <select id="gst_status" name="gst_status[]" class="form-control form-select" required><?= getGSTTYPE($gst_status, 'select') ?></select>
                                    </div>

                                    <div class="col-md-4"><label class="form-label" for="gst_status_value">GST Percentage<span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <select id="gst_status_value_0" name="gst_status_value[]" class="form-control form-select" required>
                                                <?= getGSTDETAILS('', 'select') ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group"><label class="form-label" for="inbuild_amenities_0">Inbuilt Amenities
                                                <span class="text-danger">*</span></label>
                                            <div class="select2-primary"><select id="inbuild_amenities_0" name="inbuild_amenities[0][]" class="select2 form_select" multiple><?= get_INBUILT_AMENITIES($inbuilt_amenities, 'multiselect'); ?></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="room_gallery_0" class="form-label">Room Gallery <span class="text-danger">*</span></label>
                                        <div class="form-group">
                                            <input class="form-control" type="file" accept="image/*" id="room_gallery_0" name="room_gallery[0][]" multiple>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div id="show_room_gallery_title_0" style="display:none;">
                                            <h6>Uploaded Room Gallery</h6>
                                        </div>
                                        <div class="row" id="uploaded_room_image_preview_0"></div>
                                    </div>
                                    <div class="col-lg-10 col-xl-10 col-12 mt-3"><label class="form-label" for="modalAddCard">Food Included? (Optional)</label>
                                        <div class="form-group mt-3">
                                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="breakfast_included_0" name="breakfast_included[0][]"><label class="form-check-label" for="breakfast_included_0">Breakfast</label></div>
                                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="lunch_included_0" name="lunch_included[0][]"><label class="form-check-label" for="lunch_included_0">Lunch</label></div>
                                            <div class="form-check form-check-inline"><input class="form-check-input" type="checkbox" id="dinner_included_0" name="dinner_included[0][]"><label class="form-check-label" for="dinner_included_0">Dinner</label></div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="hidden_hotel_ID" id="hidden_hotel_ID" value="<?= $hotel_ID; ?>" hidden>
                                    <div class="col-lg-2 col-xl-2 col-12 d-flex align-items-center justify-content-end"><button type="button" class="btn btn-label-danger mt-4"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
                                    </div>
                                    <div class="border-bottom border-bottom-dashed my-4"></div>
                                </div>
                            <?php endif; ?>
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="hotel.php?route=add&formtype=basic_info&id=<?= $hotel_ID; ?>" class="btn btn-secondary">Back</a>
                                </div>
                                <button type="submit" id="submit_hotel_room_details_btn" class="btn btn-primary btn-md">Update & Continue</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <link rel="stylesheet" href="assets/vendor/libs/select2/select2.css">
            <script src="assets/vendor/libs/select2/select2.js"></script>
            <script src="assets/js/forms-selects.js"></script>
            <script src="assets/js/parsley.min.js"></script>

            <script>
                $(document).ready(function() {
                    $(function() {
                        var e, t = $(".sticky-element"),
                            t = (window.Helpers.initCustomOptionCheck(), e = Helpers.isNavbarFixed() ? $(
                                ".layout-navbar").height() + 7 : 0, t.length && t.sticky({
                                topSpacing: e,
                                zIndex: 9
                            }));
                    });

                    function handleFileInputChange(rowId) {
                        var selectedFilesByRowId = {};

                        $('#room_gallery_' + rowId).change(function() {
                            $('#show_room_gallery_title_' + rowId).show();
                            var previewContainer = $('#uploaded_room_image_preview_' + rowId);
                            previewContainer.empty();

                            var files = this.files;
                            for (var i = 0; i < files.length; i++) {
                                var file = files[i];
                                var reader = new FileReader();

                                reader.onload = (function(file) {
                                    return function(event) {
                                        var imagePreview = $('<div class="col-md-1 me-3"></div>');
                                        var imgElement = $('<img class="me-3" src="' + event.target.result +
                                            '" alt="Image Preview" style="max-width: 100px; max-height: 100px;">'
                                        );

                                        imagePreview.append(imgElement);
                                        previewContainer.append(imagePreview);

                                        if (!selectedFilesByRowId[rowId]) {
                                            selectedFilesByRowId[rowId] = [];
                                        }
                                        selectedFilesByRowId[rowId].push(file);
                                    };
                                })(file);

                                reader.readAsDataURL(file);
                            }
                        });
                    }

                    <?php if ($total_hotel_rooms_num_rows_count > 0) :
                        for ($selected_i = 0; $selected_i < $total_hotel_rooms_num_rows_count; $selected_i++) : ?>
                            var row_ID = '<?= $selected_i; ?>';
                            $('#preferred_for_' + row_ID).select2();

                            var hotel_room_type_title = {
                                url: function(phrase) {
                                    return "engine/json/__JSONsearchroomtype.php?phrase=" + encodeURIComponent(phrase) +
                                        "&format=json";
                                },
                                getValue: "check_room_type",
                                list: {
                                    match: {
                                        enabled: true
                                    },
                                    hideOnEmptyPhrase: true
                                },
                                theme: "square"
                            };
                            $("#hotel_room_type_title_" + row_ID + "").easyAutocomplete(hotel_room_type_title);

                            handleFileInputChange(row_ID);
                        <?php endfor;
                    else :
                        ?>
                        var hotel_room_type_title = {
                            url: function(phrase) {
                                return "engine/json/__JSONsearchroomtype.php?phrase=" + encodeURIComponent(phrase) +
                                    "&format=json";
                            },
                            getValue: "check_room_type",
                            list: {
                                match: {
                                    enabled: true
                                },
                                hideOnEmptyPhrase: true
                            },
                            theme: "square"
                        };
                        $("#hotel_room_type_title_0").easyAutocomplete(hotel_room_type_title);
                        handleFileInputChange('0');
                    <?php
                    endif; ?>

                    <?php if ($total_hotel_rooms_num_rows_count > 0) : ?>
                        var roomtype_counter = <?= $total_hotel_rooms_num_rows_count - 1; ?>;
                        var room_count = <?= $total_hotel_rooms_num_rows_count + 1; ?>;
                    <?php else : ?>
                        var roomtype_counter = 0;
                        var room_count = 1;
                    <?php endif; ?>

                    $(".add_item_room_btn").click(function(e) {
                        roomtype_counter = $('.row.g-3').length;
                        room_count = $('.row.g-3').length;
                        roomtype_counter++;
                        room_count++;
                        e.preventDefault();

                        initializeEasyAutocomplete();

                        $("#show_room_item").prepend(`
    <div class="row g-3" id="show_${parseInt(roomtype_counter - 1)}">
        <div class="col-md-12"><h6 class="m-0">Room #${room_count}</h6></div>
        <div class="col-md-4">
            <label class="form-label" for="hotel_room_type_title_${parseInt(roomtype_counter - 1)}">Room Type <span class="text-danger">*</span></label>
            <input type="text" id="hotel_room_type_title_${parseInt(roomtype_counter - 1)}" name="hotel_room_type_title[]" required class="form-control" placeholder="Enter the Room type" required autocomplete="off" />
        </div>
        <div class="col-md-4">
            <label class="form-label" for="hotel_room_title">Room Title <span class="text-danger">*</span></label>
            <input type="text" id="hotel_room_title" name="hotel_room_title[]" required class="form-control" placeholder="Enter the Room Title" autocomplete="off" />
        </div>
        <div class="col-md-4">
            <label class="form-label" for="no_of_rooms_available">No of Rooms Availability<span class="text-danger">*</span></label>
            <input type="text" id="no_of_rooms_available" name="no_of_rooms_available[]" required class="form-control" placeholder="Enter the No. of Rooms Available" autocomplete="off" />
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="preferred_for">Prefered For <span class="text-danger">*</span></label>
                <div class="select2-primary">
                    <select id="preferred_for_${parseInt(roomtype_counter - 1)}" name="preferred_for[${parseInt(roomtype_counter - 1)}][]" class="select2" multiple>
                        <?= get_HOTEL_PREFERRED_FOR('', 'select'); ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="air_conditioner_avilability_${parseInt(roomtype_counter - 1)}">AC Availability <span class="text-danger">*</span></label>
            <select id="air_conditioner_avilability_${parseInt(roomtype_counter - 1)}" name="air_conditioner_avilability[]" class="form-control" required>
                <?= get_YES_R_NO($air_conditioner_avilability, 'select') ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="room_status_${parseInt(roomtype_counter - 1)}">Status <span class="text-danger">*</span></label>
            <select id="room_status_${parseInt(roomtype_counter - 1)}" name="room_status[]" class="form-control form-select" required>
                <?= getSTATUS($status, 'select'); ?>
            </select>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="total_max_adult">Max Adult <span class="text-danger">*</span></label>
                <div class="form-group">
                    <input type="text" id="total_max_adult" name="total_max_adult[]" required class="form-control" placeholder="Enter the Max Adult" required autocomplete="off" />
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="total_max_children">Max Children<span class="text-danger">*</span></label>
            <div class="form-group">
                <input type="text" id="total_max_children" name="total_max_children[]" required class="form-control" placeholder="Enter the total children" required autocomplete="off" />
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="check_in_time_${parseInt(roomtype_counter - 1)}">Check-In Time<span class="text-danger">*</span></label>
            <div class="form-group">
                <input class="form-control" type="time" placeholder="hh:mm" id="check_in_time_${parseInt(roomtype_counter - 1)}" name="check_in_time[]" required>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="check_out_time_${parseInt(roomtype_counter - 1)}">Check-Out Time<span class="text-danger">*</span></label>
            <div class="form-group">
                <input class="form-control" type="time" placeholder="hh:mm" id="check_out_time_${parseInt(roomtype_counter - 1)}" name="check_out_time[]" required>
            </div>
        </div>
        <!--<div class="col-md-4">
            <label class="form-label" for="extra_bed_charge">Extra Bed Charge<span class="text-danger">*</span></label>
            <div class="form-group">
                <input type="text" id="extra_bed_charge" name="extra_bed_charge[]" required class="form-control" placeholder="Enter the Charge" required autocomplete="off" />
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="child_with_bed_charge">Child with Bed Charge<span class="text-danger">*</span></label>
            <div class="form-group">
                <input type="text" id="child_with_bed_charge" name="child_with_bed_charge[]" required class="form-control" placeholder="Enter the Charge" required autocomplete="off" />
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="child_without_bed_charge">Child without Bed Charge<span class="text-danger">*</span></label>
            <div class="form-group">
                <input type="text" id="child_without_bed_charge" name="child_without_bed_charge[]" required class="form-control" placeholder="Enter the Charge" required autocomplete="off" />
            </div>
        </div>-->
        <div class="col-md-4">
            <label class="form-label" for="gst_status_${parseInt(roomtype_counter - 1)}">GST Type<span class="text-danger">*</span></label>
            <select id="gst_status_${parseInt(roomtype_counter - 1)}" name="gst_status[]" class="form-control form-select" required>
                <?= getGSTTYPE($gst_status, 'select'); ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="gst_status_value_${parseInt(roomtype_counter - 1)}">GST Percentage<span class="text-danger">*</span></label>
            <div class="form-group">
                <select id="gst_status_value_${parseInt(roomtype_counter - 1)}" name="gst_status_value[]" class="form-control form-select" required>
                    <?= getGSTDETAILS($gst_status_value, 'select'); ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="inbuild_amenities_${parseInt(roomtype_counter - 1)}">Inbuilt Amenities <span class="text-danger">*</span></label>
                <div class="select2-primary">
                    <select id="inbuild_amenities_${parseInt(roomtype_counter - 1)}" name="inbuild_amenities[${parseInt(roomtype_counter - 1)}][]" class="select2 form_select" multiple>
                        <?= get_INBUILT_AMENITIES('', 'multiselect'); ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <label for="room_gallery_${parseInt(roomtype_counter - 1)}" class="form-label">Room Gallery <span class="text-danger">*</span></label>
            <div class="form-group">
                <input class="form-control" type="file" accept="image/*" id="room_gallery_${parseInt(roomtype_counter - 1)}" name="room_gallery[${parseInt(roomtype_counter - 1)}][]" multiple>
            </div>
        </div>
        <div class="col-md-12">
            <div id="show_room_gallery_title_${parseInt(roomtype_counter - 1)}" style="display:none;">
                <h6>Uploaded Room Gallery</h6>
            </div>
            <div class="row" id="uploaded_room_image_preview_${parseInt(roomtype_counter - 1)}"></div>
        </div>
        <div class="col-lg-10 col-xl-10 col-12 mt-3">
            <label class="form-label" for="modalAddCard">Food Included? (Optional)</label>
            <div class="form-group mt-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="breakfast_included_${parseInt(roomtype_counter - 1)}" name="breakfast_included[${parseInt(roomtype_counter - 1)}][]">
                    <label class="form-check-label" for="breakfast_included_${parseInt(roomtype_counter - 1)}">Breakfast</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="lunch_included_${parseInt(roomtype_counter - 1)}" name="lunch_included[${parseInt(roomtype_counter - 1)}][]">
                    <label class="form-check-label" for="lunch_included_${parseInt(roomtype_counter - 1)}">Lunch</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="dinner_included_${parseInt(roomtype_counter - 1)}" name="dinner_included[${parseInt(roomtype_counter - 1)}][]">
                    <label class="form-check-label" for="dinner_included_${parseInt(roomtype_counter - 1)}">Dinner</label>
                </div>
                <input type="hidden" name="hidden_room_ID[]" id="hidden_room_ID" value="0" hidden>
            </div>
        </div>
        <div class="col-lg-2 col-xl-2 col-12 d-flex align-items-center justify-content-end">
            <button type="button" class="btn btn-label-danger mt-4 remove_item_btn"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
        </div>
        <div class="border-bottom border-bottom-dashed my-4"></div>
    </div>
`);

                        $('#preferred_for_' + parseInt(roomtype_counter - 1)).select2();
                        $('#air_conditioner_avilability_' + parseInt(roomtype_counter - 1)).selectize();
                        $('#room_status_' + parseInt(roomtype_counter - 1)).selectize();
                        $('#gst_status_value_' + parseInt(roomtype_counter - 1)).selectize();
                        $('#gst_status_' + parseInt(roomtype_counter - 1)).selectize();
                        $('#inbuild_amenities_' + parseInt(roomtype_counter - 1)).select2();

                        $("html, body").animate({
                            scrollTop: 0
                        }, "slow");

                        handleFileInputChange(parseInt(roomtype_counter - 1));

                        flatpickr('[id^="check_in_time_"]', {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: "h:i K",
                            time_24hr: false,
                        });

                        flatpickr('[id^="check_out_time_"]', {
                            enableTime: true,
                            noCalendar: true,
                            dateFormat: "h:i K",
                            time_24hr: false,
                        });

                        var hotel_room_type_title = {
                            url: function(phrase) {
                                return "engine/json/__JSONsearchroomtype.php?phrase=" + encodeURIComponent(
                                    phrase) + "&format=json";
                            },
                            getValue: "check_room_type",
                            list: {
                                match: {
                                    enabled: true
                                },
                                hideOnEmptyPhrase: true
                            },
                            theme: "square"
                        };

                        $('[id^="hotel_room_type_title_"]').easyAutocomplete(hotel_room_type_title);

                        $('[id^="hotel_room_type_title_"]').focus();

                    });

                });

                function initializeEasyAutocomplete() {
                    var hotel_room_type_title = {
                        url: function(phrase) {
                            return "engine/json/__JSONsearchroomtype.php?phrase=" + encodeURIComponent(phrase) + "&format=json";
                        },
                        getValue: "check_room_type",
                        list: {
                            match: {
                                enabled: true
                            },
                            hideOnEmptyPhrase: true
                        },
                        theme: "square"
                    };

                    $('[id^="hotel_room_type_title_"]').each(function() {
                        var inputId = $(this).attr("id");
                        $(this).easyAutocomplete(hotel_room_type_title);
                    });
                }

                $(document).on('click', '.remove_item_btn', function(e) {
                    e.preventDefault();
                    let row_item = $(this).closest('.row.g-3');
                    $(row_item).remove();

                    let roomTypeDivs = $('.row.g-3');
                    for (let i = 0; i < roomTypeDivs.length; i++) {
                        let roomNumber = roomTypeDivs.length - i;
                        $(roomTypeDivs[i]).find('h6').text('Room #' + roomNumber);
                        room_count = roomNumber;
                    }

                    room_count--;
                    roomtype_counter--;
                });

                flatpickr("#check_in_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K",
                    time_24hr: false,
                });

                flatpickr("#check_out_time", {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "h:i K",
                    time_24hr: false,
                });

                $(document).ready(function() {
                    $(".form-select").selectize();
                    $("#form_hotel_room_details").submit(function(event) {
                        var form = $('#form_hotel_room_details')[0];
                        var data = new FormData(form);
                        $(this).find("button[id='submit_hotel_room_details_btn']").prop('disabled', true);
                        $.ajax({
                            type: "post",
                            url: 'engine/ajax/__ajax_manage_hotel.php?type=hotel_room_details',
                            data: data,
                            processData: false,
                            contentType: false,
                            cache: false,
                            timeout: 80000,
                            dataType: 'json',
                            encode: true,
                        }).done(function(response) {
                            if (!response.success) {
                                $('#submit_hotel_room_details_btn').prop('disabled', false);
                                if (response.errors.hotel_room_type_title_required) {
                                    TOAST_NOTIFICATION('warning', 'Room Type Required', 'Warning !!!', '', '',
                                        '', '', '', '', '', '', '');
                                } else if (response.errors.preferred_for_required) {
                                    TOAST_NOTIFICATION('warning', 'Choose Preferred for Required',
                                        'Warning !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.errors.hotel_room_title_required) {
                                    TOAST_NOTIFICATION('warning', 'Room Title Required', 'Warning !!!', '', '',
                                        '', '', '', '', '', '', '');
                                } else if (response.errors.air_conditioner_avilability_required) {
                                    TOAST_NOTIFICATION('warning', 'Air Conditioner Availability Required',
                                        'Warning !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.errors.room_status_required) {
                                    TOAST_NOTIFICATION('warning', 'Status Required', 'Warning !!!', '', '', '',
                                        '', '', '', '', '', '');
                                } else if (response.errors.total_max_adult_required) {
                                    TOAST_NOTIFICATION('warning', 'Max Adults Required', 'Warning !!!', '', '',
                                        '', '', '', '', '', '', '');
                                } else if (response.errors.total_max_children_required) {
                                    TOAST_NOTIFICATION('warning', 'Max Children Required', 'Warning !!!', '',
                                        '', '', '', '', '', '', '', '');
                                } else if (response.errors.check_in_time_required) {
                                    TOAST_NOTIFICATION('warning', 'Check-In Time Required', 'Warning !!!', '',
                                        '', '', '', '', '', '', '', '');
                                } else if (response.errors.check_out_time_required) {
                                    TOAST_NOTIFICATION('warning', 'Check-Out Time Required', 'Warning !!!', '',
                                        '', '', '', '', '', '', '', '');
                                } else if (response.errors.inbuild_amenities_required) {
                                    TOAST_NOTIFICATION('warning', 'Inbuild Amenities are Required',
                                        'Warning !!!', '', '', '', '', '', '', '', '', '');
                                } else if (response.errors.extra_bed_charge_required) {
                                    TOAST_NOTIFICATION('warning', 'Extra bed charge is Required', 'Warning !!!',
                                        '', '', '', '', '', '', '', '', '');
                                }

                            } else {
                                if (response.i_result == true) {
                                    TOAST_NOTIFICATION('success', 'Room Details Added', 'Success !!!', '', '',
                                        '', '', '', '', '', '', '');
                                    location.assign(response.redirect_URL);
                                } else if (response.u_result == true) {
                                    TOAST_NOTIFICATION('success', 'Room Details Updated', 'Success !!!', '', '',
                                        '', '', '', '', '', '', '');
                                    location.assign(response.redirect_URL);
                                } else if (response.i_result == false) {
                                    TOAST_NOTIFICATION('success', 'Unable to Add Room Details', 'Success !!!',
                                        '', '', '', '', '', '', '', '', '');
                                    $(this).find("button[id='submit_hotel_room_details_btn']").prop('disabled',
                                        false);
                                } else if (response.u_result == false) {
                                    TOAST_NOTIFICATION('success', 'Unable to Update Room Details',
                                        'Success !!!', '', '', '', '', '', '', '', '', '');
                                    $(this).find("button[id='submit_hotel_room_details_btn']").prop('disabled', false);
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

                function removeROOM(ROOM_ID, HOT_ID) {
                    $('.receiving-delete-form-data').load('engine/ajax/__ajax_add_hotel_form.php?type=delete_room&ID=' + ROOM_ID +
                        '&HOT_ID=' + HOT_ID,
                        function() {
                            const container = document.getElementById("showDELETEMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }

                function removeROOMGALLERY(ROOM_GAL_ID, HOT_ID) {
                    $('.receiving-delete-form-data').load('engine/ajax/__ajax_add_hotel_form.php?type=delete_room_gallery&ID=' +
                        ROOM_GAL_ID + '&HOT_ID=' + HOT_ID,
                        function() {
                            const container = document.getElementById("showDELETEMODAL");
                            const modal = new bootstrap.Modal(container);
                            modal.show();
                        });
                }
            </script>
    <?php
    endif;
else :
    echo "Request Ignored";
endif;
    ?>