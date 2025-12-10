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

        $guide_ID = $_POST['ID'];
        $TYPE = $_POST['TYPE'];

        if ($guide_ID != '' && $guide_ID != 0) :
            $select_guide_list_query = sqlQUERY_LABEL("SELECT `guide_id`, `guide_name`, `guide_dob`, `guide_bloodgroup`, `guide_gender`, `guide_primary_mobile_number`, `guide_alternative_mobile_number`, `guide_email`, `guide_emergency_mobile_number`, `guide_language_proficiency`, `guide_aadhar_number`, `guide_experience`, `guide_country`, `guide_state`, `guide_city`, `gst_type`,`guide_gst`, `guide_available_slot`, `guide_bank_name`, `guide_bank_branch_name`, `guide_ifsc_code`, `guide_account_number`, `guide_preffered_for`, `applicable_hotspot_places`, `applicable_activity_places` FROM `dvi_guide_details` WHERE `deleted` = '0' and `guide_id` = '$guide_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_guide_list_query)) :
                $guide_name = $fetch_list_data['guide_name'];
                $guide_dob =  date('d-m-Y', strtotime($fetch_list_data['guide_dob']));
                $guide_bloodgroup = $fetch_list_data['guide_bloodgroup'];
                $guide_gender = $fetch_list_data['guide_gender'];
                $guide_primary_mobile_number = $fetch_list_data['guide_primary_mobile_number'];
                $guide_alternative_mobile_number = $fetch_list_data['guide_alternative_mobile_number'];
                $guide_email = $fetch_list_data["guide_email"];
                $guide_emergency_mobile_number = $fetch_list_data['guide_emergency_mobile_number'];
                $guide_language_proficiency = $fetch_list_data["guide_language_proficiency"];
                $guide_aadhar_number = $fetch_list_data["guide_aadhar_number"];
                $guide_experience = $fetch_list_data["guide_experience"];
                $guide_country = $fetch_list_data["guide_country"];
                $guide_state = $fetch_list_data["guide_state"];
                $guide_city = $fetch_list_data["guide_city"];
                $gst_type = $fetch_list_data["gst_type"];
                $guide_gst = $fetch_list_data["guide_gst"];
                $guide_available_slot = $fetch_list_data["guide_available_slot"];
                $guide_bank_name = $fetch_list_data["guide_bank_name"];
                $guide_bank_branch_name = $fetch_list_data["guide_bank_branch_name"];
                $guide_ifsc_code = $fetch_list_data["guide_ifsc_code"];
                $guide_account_number = $fetch_list_data["guide_account_number"];
                $guide_preffered_for = $fetch_list_data["guide_preffered_for"];
                $applicable_hotspot_places = $fetch_list_data["applicable_hotspot_places"];
                $applicable_activity_places = $fetch_list_data["applicable_activity_places"];
                $status = $fetch_list_data['status'];
            endwhile;

            $select_guide_credientials = sqlQUERY_LABEL("SELECT `userID`, `guide_id`, `user_profile`, `username`, `password`, `roleID` FROM `dvi_users` WHERE `deleted` = '0' and `guide_id` = '$guide_ID'") or die("#1-UNABLE_TO_COLLECT_GUIDE_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
            while ($fetch_vendor_credientials_list_data = sqlFETCHARRAY_LABEL($select_guide_credientials)) :
                $guide_select_role = $fetch_vendor_credientials_list_data['roleID'];
                $guide_username = $fetch_vendor_credientials_list_data['username'];
                $guide_password = $fetch_vendor_credientials_list_data['password'];
            endwhile;

            $btn_label = 'Update & Continue';
        else :
            $btn_label = 'Save & Continue';
            $guide_select_role = getRole('Guide', 'Role_id');
        endif;

        if ($guide_ID != '' && $guide_ID != 0 && $TYPE == 'edit') :
            $basic_info_url = 'guide.php?route=edit&formtype=basic_info&id=' . $guide_ID;
            $guide_pricebook_url = 'guide.php?route=edit&formtype=guide_pricebook&id=' . $guide_ID;
            $guide_feedback_url = 'guide.php?route=edit&formtype=guide_feedback&id=' . $guide_ID;
            $preview_url = 'guide.php?route=edit&formtype=guide_preview&id=' . $guide_ID;
        else :
            $basic_info_url = 'javascript:;';
            $guide_pricebook_url = 'javascript:;';
            $guide_feedback_url = 'javascript:;';
            $preview_url = 'guide.php?route=add&formtype=guide_preview&id=' . $guide_ID;
        endif;

        if ($guide_ID) :
            $email_readonly = 'readonly';
        else :
            $email_readonly = '';
        endif;
?>
        <!-- STEPPER -->

        <div class="row">
            <div class="col-md-12">
                <div id="wizard-validation" class="bs-stepper box-shadow-none">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle active-stepper">1</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title">Guide Basic Info</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $guide_pricebook_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle disble-stepper-num">2</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title disble-stepper-title">Pricebook</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $guide_feedback_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle disble-stepper-num">3</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title  disble-stepper-title">FeedBack & Review</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step">
                            <a href="<?= $preview_url; ?>" class="step-trigger">
                                <span class="bs-stepper-circle disble-stepper-num">4</span>
                                <span class="bs-stepper-label mt-3">
                                    <h5 class="bs-stepper-title  disble-stepper-title">Guide Preview</h5>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form id="form_guide_basic_info" action="" method="POST" data-parsley-validate>
            <input type="hidden" name="hidden_guide_ID" value="<?= $guide_ID ?>" />
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label" for="guide_name">Guide Name<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="guide_name" id="guide_name" class="form-control" placeholder="Enter the guide name" value="<?= $guide_name; ?>" required data-parsley-trigger="keyup" data-parsley-whitespace="trim" required />
                                </div>
                            </div>
                            <div class="col-md-4 position-relative">
                                <label class="form-label" for="guide_dob">Date of Birth</label>
                                <div class="form-group">
                                    <input type="text" name="guide_dob" id="guide_dob" class="form-control" placeholder="DD/MM/YYY" value="<?= $guide_dob; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" />
                                    <span class="calender-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                            <g>
                                                <defs>
                                                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                        <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000" opacity="1"></path>
                                                    </clipPath>
                                                </defs>
                                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                    <path d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374" style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform="translate(236.333 118)" fill="none" stroke="#7367f0" stroke-width="40" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity="" data-original="#000000" opacity="1" class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="blood_group">Blood Group<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select id="blood_group" name="blood_group" class="form-select" required>
                                        <?= getBLOOD_GROUP($guide_id, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_gender">Gender<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select id="guide_gender" name="guide_gender" class="form-select" required>
                                        <?= getGENDER($guide_gender, 'select'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_primary_mobile_no">Primary Mobile Number<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="guide_primary_mobile_number" id="guide_primary_mobile_number" class="form-control" placeholder="Enter the mobile number" value="<?= $guide_primary_mobile_number; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" required maxlength="10" data-parsley-pattern="^\d{10}$" data-parsley-pattern-message="Please enter a 10-digit number." />
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="guide_alternativr_mobile_no">Alternative Mobile Number</label>
                                <div class="form-group">
                                    <input type="text" name="guide_alternative_mobile_no" id="guide_alternativr_mobile_no" class="form-control" placeholder="Enter the mobile number" value="<?= $guide_alternative_mobile_number; ?>" data-parsley-type="number" data-parsley-trigger="keyup" autocomplete="off" maxlength="10" data-parsley-pattern="^\d{10}$" data-parsley-pattern-message="Please enter a 10-digit number." />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_email_id">Email ID<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="guide_email_id" id="guide_email_id" class="form-control" required value="<?= $guide_email; ?>" data-parsley-type="email" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-checkemail data-parsley-checkemail-message="Email Address already Exists" <?= $email_readonly; ?> placeholder="Enter the Email Address" autocomplete="off" />
                                    <input type="hidden" name="old_guide_email_id" id="old_guide_email_id" value="<?= $guide_email; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="infant_far">Emergency Mobile Number</label>
                                <div class="form-group">
                                    <input type="text" name="guide_emergency_mobile_number" id="guide_emergency_mobile_number" class="form-control" placeholder="Enter the Emg.Mobile Num" value="<?= $guide_emergency_mobile_number; ?>" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-check_emergency_mobile_number autocomplete="off" maxlength="10" onblur="check_emergency_mobile_number(this.value)" data-parsley-pattern="^\d{10}$" data-parsley-pattern-message="Please enter a 10-digit number." />
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <label class="form-label" for="guide_username">Username<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="guide_username" id="guide_username" class="form-control" placeholder="Username" <?= ($guide_ID == '' || $guide_ID == 0) ? "required" : "" ?> readonly />
                                </div>
                            </div> -->
                            <div class="col-md-4">
                                <label class="form-label" for="guide_password">
                                    Password <span class="text-danger">*</span>
                                </label>
                                <div class="form-group position-relative">
                                    <input type="password" name="guide_password" id="guide_password" class="form-control" placeholder="Password" <?= ($guide_ID == '' && $guide_ID == 0) ? "required" : "" ?> autocomplete="off" />
                                    <span id="toggleGuidePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="guide_select_role">Role<span class=" text-danger">
                                        *</span></label>
                                <select class="form-select" name="guide_select_role" id="guide_select_role" value="<?= $guide_select_role; ?>" data-parsley-errors-container="#vendor_role_error_container">
                                    <?= getRole($guide_select_role, 'select'); ?>
                                </select>
                                <div id="vendor_role_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_aadhar_no">Aadhar Card No</label>
                                <div class="form-group">
                                    <input type="text" name="guide_aadhar_no" id="guide_aadhar_no" class="form-control" placeholder="852652589666" value="<?= $guide_aadhar_number; ?>" autocomplete="off" maxlength="12" data-parsley-trigger="keyup" data-parsley-pattern="^\d{12}$" data-parsley-pattern-message="Please enter a Valid Aadhar Number." />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="language_proficiency">Language Proficiency<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select id="language_proficiency" name="language_proficiency" class="form-control form-select" required>
                                        <?= getGUIDE_LANGUAGE_DETAILS($guide_language_proficiency, 'select'); ?></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_experience">Experience</label>
                                <div class="form-group">
                                    <input type="text" name="guide_experience" id="guide_experience" class="form-control" placeholder="Enter the Experience" value="<?= $guide_experience; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_country">Country</label>
                                <div class="form-group">
                                    <select class="form-select" name="guide_country" id="guide_country" onchange="CHOOSEN_COUNTRY()" data-parsley-trigger="keyup">
                                        <?= getCOUNTRYLIST($guide_country, 'select_country'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_state">State</label>
                                <div class="form-group">
                                    <select class="form-select" name="guide_state" id="guide_state" onchange="CHOOSEN_STATE()" data-parsley-trigger="keyup">
                                        <option value="">Please Choose State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_city">City</label>
                                <div class="form-group">
                                    <select class="form-select" name="guide_city" id="guide_city" data-parsley-trigger="keyup">
                                        <option value="">Please Choosen City</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4"><label class="form-label" for="gst_status">GST Type<span class="text-danger">*</span></label>
                                <select id="gst_status" name="gst_status" class="form-control form-select" required><?= getGSTTYPE($gst_status, 'select') ?></select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_gst_percentage">GST%<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select id="guide_gst_percentage" name="guide_gst_percentage" class="form-control form-select">
                                        <?= getGSTDETAILS($guide_gst, 'select') ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_slot">Guide Available Slots<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select id="guide_slot" name="guide_slot[]" class="form-control form-select" multiple required>
                                        <?= getSLOTTYPE($guide_available_slot, 'multiselect') ?>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="hidden_guide_ID" id="hidden_guide_ID" value="<?= $guide_ID; ?>" hidden>

                            <div class="divider">
                                <div class="divider-text">
                                    <div class="badge rounded bg-label-primary p-1"><i class="ti ti-star ti-sm"></i></div>
                                </div>
                            </div>

                            <!-- bank details  -->

                            <h5 class="text-primary m-0">Bank Details</h5>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_bank_name">Bank Name</label>
                                <div class="form-group">
                                    <input type="text" name="guide_bank_name" id="guide_bank_name" class="form-control timepicker" placeholder="Enter the Bank Name" value="<?= $guide_bank_name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_branch_name">Branch Name</label>
                                <div class="form-group">
                                    <input type="text" name="guide_branch_name" id="guide_branch_name" class="form-control timepicker" placeholder="Enter the Branch Name" value="<?= $guide_bank_branch_name; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_IFSC_code">IFSC Code</label>
                                <div class="form-group">
                                    <input type="text" name="guide_IFSC_code" id="guide_IFSC_code" class="form-control timepicker" placeholder="CUB00202" value="<?= $guide_ifsc_code; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_account_no">Account Number</label>
                                <div class="form-group">
                                    <input type="text" name="guide_account_no" id="guide_account_no" class="form-control" placeholder="Eg:51000200230002000" value="<?= $guide_account_number; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="guide_confirm_account_no">Confirm Account Number</label>
                                <div class="form-group">
                                    <input type="text" name="guide_confirm_account_no" id="guide_confirm_account_no" class="form-control" placeholder="Eg:51000200230002000" value="<?= $guide_account_number; ?>" onblur="check_confirm_acc_no(this.value)" data-parsley-equalto="#guide_account_no" data-parsley-trigger="keyup" data-parsley-equalto-message="Account Number Does Not Match" />
                                </div>
                            </div>
                            <div class="divider">
                                <div class="divider-text">
                                    <div class="badge rounded bg-label-primary p-1"><i class="ti ti-star ti-sm"></i></div>
                                </div>
                            </div>
                            <div>
                                <h5 class="text-primary m-0">Guide Prefered For</h5>
                            </div>
                            <div class="col-md-4 d-flex gap-3">
                                <div class="form-group">
                                    <input class="form-check-input me-1" type="checkbox" value="1" id="hotspotCheckbox" name="hotspotCheckbox" <?= ($guide_preffered_for == 1) ? "checked" : "" ?> onclick="showGUIDEPREFERENCEDETAILS(this,'hotspot_check')">
                                    Hotspot
                                </div>
                                <div class="form-group">
                                    <input class="form-check-input me-1" type="checkbox" value="1" id="activityCheckbox" name="activityCheckbox" <?= ($guide_preffered_for == 2) ? "checked" : "" ?> onclick="showGUIDEPREFERENCEDETAILS(this,'activity_check')">
                                    Activity
                                </div>
                                <div class="form-group">
                                    <input class="form-check-input me-1" type="checkbox" value="1" id="itineraryCheckbox" onclick="showGUIDEPREFERENCEDETAILS(this,'itinerary_check')" name="itineraryCheckbox" <?= ($guide_preffered_for == 3) ? "checked" : "" ?>>
                                    Itinerary
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-4 <?= ($guide_preffered_for == 1) ? "" : "d-none" ?>" id="hotspot_check">
                                    <label class="form-label" for="hotspotSelect">Hotspot Place<span class=" text-danger">
                                            *</span></label>
                                    <div class="form-group">
                                        <select id="hotspotSelect" name="hotspotSelect[]" class="form-control form-select" multiple>
                                            <?= getHOTSPOTDETAILS($applicable_hotspot_places, 'multiselect'); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 <?= ($guide_preffered_for == 2) ? "" : "d-none" ?>" id="activity_check">
                                    <label class="form-label" for="activitySelect">Activity<span class=" text-danger">
                                            *</span></label>
                                    <div class="form-group">
                                        <select id="activitySelect" name="activitySelect[]" class="form-control form-select" multiple>
                                            <?= getACTIVITYDETAILS($applicable_activity_places, 'multiselect'); ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8 mt-2 <?= ($guide_preffered_for == 3) ? "" : "d-none" ?>" id="itinerary_check">
                                    <h6 class="alert alert-primary">From the beginning to the end of each day, the itinerary and
                                        all the hotspots serve as a guide for the entire journey.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" mt-5">
                <div class="d-flex justify-content-between py-3">
                    <div>
                        <a href="guide.php" class="btn btn-secondary">Back</a>
                    </div>
                    <button type="submit" id="submit_hotel_basic_info_btn" class="btn btn-primary btn-md"><?= $btn_label; ?></button>
                </div>
            </div>
        </form>


        <script src="assets/js/parsley.min.js"></script>

        <script>
            flatpickr('#guide_dob', {
                dateFormat: 'd/m/Y',
            });

            const toggleGuidePassword = document.querySelector('#toggleGuidePassword');
            const guidePasswordField = document.querySelector('#guide_password');

            toggleGuidePassword.addEventListener('click', function(e) {
                // Toggle the type attribute
                const type = guidePasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
                guidePasswordField.setAttribute('type', type);

                // Toggle the eye icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            document.getElementById('guide_email_id').addEventListener('input', function() {
                var guideEmail = this.value; // Get the email from the input field

                var guidePassword = guideEmail.substring(0, guideEmail.indexOf('@'));

                document.getElementById('guide_password').value = guidePassword;
            });

            function showGUIDEPREFERENCEDETAILS(event, TYPE) {

                if (TYPE == 'hotspot_check') {
                    if (event.checked) {
                        $('#hotspot_check').removeClass('d-none');
                        $('#activity_check').addClass('d-none');
                        $('#itinerary_check').addClass('d-none');
                        // Make Hotspot select field required
                        $('#hotspotSelect').prop('required', true);
                    } else {
                        $('#hotspot_check').addClass('d-none');
                        // Remove required attribute from Hotspot select field
                        $('#hotspotSelect').prop('required', false);
                    }
                    document.getElementById('itineraryCheckbox').checked = false;
                    document.getElementById('activityCheckbox').checked = false;
                    // Remove required attribute from Activity select field
                    $('#activitySelect').prop('required', false);

                } else if (TYPE == 'activity_check') {
                    if (event.checked) {
                        $('#activity_check').removeClass('d-none');
                        $('#hotspot_check').addClass('d-none');
                        $('#itinerary_check').addClass('d-none');
                        // Make Activity select field required
                        $('#activitySelect').prop('required', true);
                    } else {
                        $('#activity_check').addClass('d-none');
                        // Remove required attribute from Activity select field
                        $('#activitySelect').prop('required', false);
                    }
                    document.getElementById('itineraryCheckbox').checked = false;
                    document.getElementById('hotspotCheckbox').checked = false;
                    // Remove required attribute from Hotspot select field
                    $('#hotspotSelect').prop('required', false);

                } else if (TYPE == 'itinerary_check') {
                    if (event.checked) {
                        $('#itinerary_check').removeClass('d-none');
                        $('#hotspot_check').addClass('d-none');
                        $('#activity_check').addClass('d-none');
                    } else {
                        $('#itinerary_check').addClass('d-none');
                    }
                    document.getElementById('activityCheckbox').checked = false;
                    document.getElementById('hotspotCheckbox').checked = false;
                    // Remove required attribute from both select fields
                    $('#hotspotSelect').prop('required', false);
                    $('#activitySelect').prop('required', false);
                }
            }

            function CHOOSEN_COUNTRY() {
                var state_selectize = $("#guide_state")[0].selectize;
                var COUNTRY_ID = $('#guide_country').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        state_selectize.clear();
                        state_selectize.clearOptions();
                        state_selectize.addOption(response);
                        <?php if ($guide_state) : ?>
                            state_selectize.setValue('<?= $guide_state; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function CHOOSEN_STATE() {
                var city_selectize = $("#guide_city")[0].selectize;
                var STATE_ID = $('#guide_state').val();
                // Get the response from the server.
                $.ajax({
                    url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
                    type: "GET",
                    success: function(response) {
                        // Append the response to the dropdown.
                        city_selectize.clear();
                        city_selectize.clearOptions();
                        city_selectize.addOption(response);
                        <?php if ($guide_city) : ?>
                            city_selectize.setValue('<?= $guide_city; ?>');
                        <?php endif; ?>
                    }
                });
            }

            function check_emergency_mobile_number(mobnumber) {
                if (mobnumber == document.getElementById('guide_primary_mobile_number').value) {
                    TOAST_NOTIFICATION('warning', 'Primary Mobile Number and Emergency Mobile Number should not be same',
                        'Warning !!!', '', '', '', '', '', '', '', '', '');
                }
            }

            // Add a custom notEqualTo validator
            window.Parsley.addValidator('check_emergency_mobile_number', {
                validateString: function(value, requirement) {
                    // Get the value of the Primary Mobile Number field
                    var primaryMobileNumber = $('#guide_primary_mobile_number').val();

                    // Compare the value with the Primary Mobile Number
                    var isValid = value != primaryMobileNumber;

                    // Manipulate the input style based on the validation result
                    if (isValid) {
                        $(requirement).removeClass('input-error').css('border-color',
                            ''); // Remove the input-error class and reset border color
                    } else {
                        $(requirement).addClass('input-error').css('border-color',
                            'red'); // Add the input-error class and set border color to red
                    }

                    return isValid;
                },
                messages: {
                    en: 'Emergency Mobile Number should be different from Primary Mobile Number.',
                },
            });

            function check_confirm_acc_no(accnumber) {
                if (accnumber != document.getElementById('guide_account_no').value) {
                    TOAST_NOTIFICATION('warning', 'Account Number Does Not Match', 'Warning !!!', '', '', '', '', '', '', '', '',
                        '');
                }
            }

            $(document).ready(function() {
                $(".form-select").selectize();

                <?php if ($guide_ID != '' && $guide_ID != 0) : ?>
                    CHOOSEN_COUNTRY();
                    CHOOSEN_STATE();
                <?php endif; ?>

                //CHECK DUPLICATE staff EMAIL ID
                $('#guide_email_id').parsley();
                var old_guide_email_id = document.getElementById("old_guide_email_id").value;
                var guide_email_id = $('#guide_email_id').val();
                window.ParsleyValidator.addValidator('checkemail', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_guide_email.php',
                            method: "POST",
                            data: {
                                guide_email_id: value,
                                old_guide_email_id: old_guide_email_id
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#form_guide_basic_info").submit(function(event) {

                    var form = $('#form_guide_basic_info')[0];
                    var data = new FormData(form);
                    //  $(this).find("button[id='submit_hotel_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_guide.php?type=guide_basic_info',
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
                            if (response.errors.hotel_name_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Name Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.guide_gender_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Gender Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.guide_primary_mobile_no_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Primart Mobile no Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.guide_email_id_required) {
                                TOAST_NOTIFICATION('warning', 'Email ID Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.guide_select_role_required) {
                                TOAST_NOTIFICATION('warning', 'Role Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.guide_language_proficiency_required) {
                                TOAST_NOTIFICATION('warning', 'Language Proficiency Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.guide_slot_required) {
                                TOAST_NOTIFICATION('warning', 'Guide Slot Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.guide_emergency_mobile_number_same) {
                                TOAST_NOTIFICATION('warning', response.errors
                                    .guide_emergency_mobile_number_same, 'Warning !!!', '', '', '', '',
                                    '', '', '', '', '');
                            } else if (response.errors.guide_account_no_not_same) {
                                TOAST_NOTIFICATION('warning', response.errors.guide_account_no_not_same,
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Guide Basic Details Added', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Guide Basic Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Guide Basic Details',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Guide Basic Details',
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
        </script>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
?>