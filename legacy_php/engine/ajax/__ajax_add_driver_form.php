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

    if ($_GET['type'] == 'driver_basic_info') :

        $driver_ID = $_GET['ID'];
        if ($driver_ID != '' && $driver_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `driver_id`, `driver_code`, `vendor_id`, `vehicle_type_id`,`driver_name`, `driver_primary_mobile_number`, `driver_alternate_mobile_number`, `driver_whatsapp_mobile_number`, `driver_email`, `driver_aadharcard_num`, `driver_voter_id_num`, `driver_pan_card`, `driver_license_issue_date`, `driver_license_expiry_date`, `driver_license_number`, `driver_blood_group`, `driver_gender`, `driver_date_of_birth`, `driver_profile_image`, `driver_address`, `status` FROM `dvi_driver_details` WHERE `deleted` = '0' and `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_DRIVER_LIST:" . sqlERROR_LABEL());
            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $driver_code = $fetch_list_data['driver_code'];
                $vendor_id = $fetch_list_data['vendor_id'];
                $driver_name = $fetch_list_data['driver_name'];
                $driver_primary_mobile_number = $fetch_list_data['driver_primary_mobile_number'];
                $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
                $driver_alternate_mobile_number = $fetch_list_data['driver_alternate_mobile_number'];
                $driver_whatsapp_mobile_number = $fetch_list_data['driver_whatsapp_mobile_number'];
                $driver_email = $fetch_list_data['driver_email'];
                $driver_aadharcard_num = $fetch_list_data["driver_aadharcard_num"];
                $driver_voter_id_num = $fetch_list_data['driver_voter_id_num'];
                $driver_pan_card = $fetch_list_data["driver_pan_card"];
                $driver_license_issue_date = date('d-m-Y', strtotime($fetch_list_data["driver_license_issue_date"]));
                $driver_license_expiry_date = date('d-m-Y', strtotime($fetch_list_data["driver_license_expiry_date"]));
                $driver_license_number = $fetch_list_data["driver_license_number"];
                $driver_blood_group = $fetch_list_data["driver_blood_group"];
                $driver_gender = $fetch_list_data["driver_gender"];
                $driver_date_of_birth = date('d-m-Y', strtotime($fetch_list_data['driver_date_of_birth']));
                $driver_profile_image = $fetch_list_data['driver_profile_image'];
                $driver_address = $fetch_list_data['driver_address'];
                $status = $fetch_list_data['status'];
            endwhile;


            $basic_info_url = 'driver.php?route=edit&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=edit&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=edit&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=edit&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=edit&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Update & Continue";
        else :
            $basic_info_url = 'javascript:;';
            $driver_cost_url = 'javascript:;';
            $driver_upload_documents_url = 'javascript:;';
            $driver_feedback_url = 'javascript:;';
            $driver_create_preview_url = 'javascript:;';

            $btn_label = "Save & Continue";
        endif;
?>
        <!-- STEPPER -->
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  active-stepper">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title">Basic Info</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_cost_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Cost Details</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_upload_documents_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Upload Document</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_feedback_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Feedback & Review</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_create_preview_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Preview</h5>
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
                    <form id="form_add_driver_basic" method="POST" data-parsley-validate>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="vendor_id">Choose Vendor<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">

                                    <select id="vendor_id" name="vendor_id" class="form-select form-control"
                                        data-parsley-trigger="keyup" data-parsley-errors-container="#vendor_error_container"
                                        onchange="showVEHICLE_TYPES();">
                                        <?php if ($logged_vendor_id != 0 && $logged_user_level != 1): ?>
                                            <?= getVENDOR_DETAILS($logged_vendor_id, 'logged_vendor_select'); ?>
                                        <?php else: ?>
                                            <?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
                                        <?php endif; ?>
                                    </select>
                                    <div id="vendor_error_container"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="vendor_vehicle_id">Choose Vehicle Type<span
                                        class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <select id="vendor_vehicle_id" name="vendor_vehicle_id" class="form-select form-control"
                                        data-parsley-trigger="keyup"
                                        data-parsley-errors-container="#vendor_vehicle_error_container">
                                        <option value="">Choose Vehicle Type</option>
                                        <?php // getVENDOR_VEHICLE_TYPES($vendor_id, $vehicle_type_id, 'select'); 
                                        ?>
                                    </select>
                                    <div id="vendor_vehicle_error_container"></div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="driver_name">Driver Name<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_name" id="driver_name" placeholder="Driver Name"
                                        value="<?= $driver_name; ?>" required autocomplete="off" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="driver_primary_mobile_number">Primary Mobile
                                    Number<span class=" text-danger">*</span></label>
                                <div class="form-group">
                                    <input type="tel" id="driver_primary_mobile_number" name="driver_primary_mobile_number"
                                        class="form-control" placeholder="Primary Mobile Number"
                                        value="<?= $driver_primary_mobile_number ?>" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-check_driver_primary_number
                                        data-parsley-check_driver_primary_number-message="Entered Mobile Number Already Exists" autocomplete="off" required maxlength="10" />
                                    <input type="hidden" name="old_driver_primary_mobile_number"
                                        id="old_driver_primary_mobile_number" value="<?= $driver_primary_mobile_number; ?>"
                                        data-parsley-type="number" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="driver_alternate_mobile_number">Alternative Mobile
                                    Number</label>
                                <div class="form-group">
                                    <input type="text" id="driver_alternate_mobile_number" name="driver_alternate_mobile_number"
                                        class="form-control" placeholder="Alternative Mobile Number"
                                        value="<?= $driver_alternate_mobile_number; ?>" maxlength="10"
                                        data-parsley-type="number" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="driver_whatsapp_mobile_number">Whatsapp Mobile
                                    Number</label>
                                <div class="form-group">
                                    <input type="tel" id="driver_whatsapp_mobile_number" name="driver_whatsapp_mobile_number"
                                        class="form-control" placeholder="Whatsapp Mobile Number"
                                        value="<?= $driver_whatsapp_mobile_number ?>" data-parsley-type="number" data-parsley-trigger="keyup" data-parsley-whitespace="trim" autocomplete="off" maxlength="10" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="driver_email">Email ID</label>
                                <div class="form-group">
                                    <input type="text" id="driver_email" name="driver_email" class="form-control"
                                        placeholder="Email ID" data-parsley-type="email" data-parsley-check_driver_email
                                        data-parsley-check_driver_email-message="Entered Vendor Email Already Exists"
                                        value="<?= $driver_email; ?>" data-parsley-trigger="keyup" />
                                    <input type="hidden" name="old_driver_email" id="old_driver_email"
                                        value="<?= $driver_email; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- <span
                                    class=" text-danger"> *</span> -->
                                <label class="driver-text-label" for="driver_license_number">License Number</label>
                                <div class="form-group">
                                    <input type="text" name="driver_license_number" id="driver_license_number"
                                        class="form-control" placeholder="License Number Format: CH03 78678555785"
                                        value="<?= $driver_license_number; ?>"
                                        data-parsley-errors-container="#driver_license_number_error_container" />
                                    <small class="text-dark"><b>License Number Format: CH03 78678555785</b> </small>
                                </div>
                                <div id="driver_license_number_error_container"></div>
                            </div>
                            <div class="col-md-4 position-relative">
                                <!-- <span
                                    class=" text-danger"> *</span> -->
                                <label class="driver-text-label" for="formValidationUsername">License Issue Date</label>
                                <div class="form-group">
                                    <input type="text" name="driver_license_issue_date" id="driver_license_issue_date"
                                        class="form-control" placeholder="License Issue Date"
                                        value="<?= $driver_license_issue_date; ?>" />
                                    <span class="calender-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
                                            width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                            <g>
                                                <defs>
                                                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                        <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000"
                                                            opacity="1"></path>
                                                    </clipPath>
                                                </defs>
                                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                    <path
                                                        d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374"
                                                        style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(236.333 118)" fill="none" stroke="#7367f0"
                                                        stroke-width="40" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000" opacity="1" class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 position-relative">
                                <!-- <span
                                    class=" text-danger"> *</span> -->
                                <label class="driver-text-label" for="driver_license_expiry_date">License Expire Date</label>
                                <div class="form-group">
                                    <input type="text" name="driver_license_expiry_date" id="driver_license_expiry_date"
                                        class="form-control" placeholder="License Expire Date"
                                        value="<?= $driver_license_expiry_date; ?>" />
                                    <span class="calender-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
                                            width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                            <g>
                                                <defs>
                                                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                        <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000"
                                                            opacity="1"></path>
                                                    </clipPath>
                                                </defs>
                                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                    <path
                                                        d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374"
                                                        style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(236.333 118)" fill="none" stroke="#7367f0"
                                                        stroke-width="40" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000" opacity="1" class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <!-- <div class="col-md-4 position-relative">
                        <label class="driver-text-label" for="driver_date_of_birth">Date of Birth<span
                                class=" text-danger"> *</span></label>
                        <div class="form-group">
                            <input type="text" name="driver_date_of_birth" id="driver_date_of_birth"
                                class="form-control" placeholder="Date of Birth" value="<?= $driver_date_of_birth; ?>"
                                required />
                            <span class="calender-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
                                    width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667"
                                    style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                    <g>
                                        <defs>
                                            <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000"
                                                    opacity="1"></path>
                                            </clipPath>
                                        </defs>
                                        <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                            <path
                                                d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374"
                                                style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                transform="translate(236.333 118)" fill="none" stroke="#7367f0"
                                                stroke-width="40" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                data-original="#000000" opacity="1" class=""></path>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                        </div>
                    </div> -->
                            <div class="col-md-4 position-relative">
                                <label class="driver-text-label" for="driver_date_of_birth">Date of Birth</label>
                                <div class="form-group">
                                    <input type="text" name="driver_date_of_birth" id="driver_date_of_birth"
                                        class="form-control" placeholder="Date of Birth" value="<?= $driver_date_of_birth; ?>" />
                                    <span class="calender-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs"
                                            width="20px" height="20px" x="0" y="0" viewBox="0 0 682.667 682.667"
                                            style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                                            <g>
                                                <defs>
                                                    <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                                        <path d="M0 512h512V0H0Z" fill="#7367f0" data-original="#000000"
                                                            opacity="1"></path>
                                                    </clipPath>
                                                </defs>
                                                <g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)">
                                                    <path
                                                        d="M0 0h39.333m78.895 0h39.333M-118 0h39.333M0 118h39.333m78.895 0h39.333M-118 118h39.333m-137.666 98.667h472.227M-137.439-98H177c43.572 0 78.894 35.322 78.894 78.895v274.877c0 43.572-35.322 78.895-78.894 78.895h-314.439c-43.572 0-78.894-35.323-78.894-78.895V-19.105c0-43.573 35.322-78.895 78.894-78.895zm275.333 373.667V374m-236.227-98.333V374"
                                                        style="stroke-width:40;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1"
                                                        transform="translate(236.333 118)" fill="none" stroke="#7367f0"
                                                        stroke-width="40" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity=""
                                                        data-original="#000000" opacity="1" class=""></path>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label w-100" for="driver_blood_group">Blood Group</label>
                                <div class="form-group">
                                    <select id="driver_blood_group" name="driver_blood_group" class="form-select">
                                        <option value="">Choose Blood Group</option>
                                        <?= getBLOOD_GROUP($driver_blood_group, 'select') ?>
                                    </select>
                                    <!-- <div id="driver_blood_group_error_container"></div> -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="driver_gender">Gender</label>
                                <select id="driver_gender" name="driver_gender" class="form-select">
                                    <option value="">Choose Gender</option>
                                    <?= getGENDERLIST($driver_gender, 'select') ?>
                                </select>
                                <!-- <div id="driver_gender_error_container"></div> -->
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="aadhar_card_num">Aadhar Card Number</label>
                                <div class="form-group">
                                    <input type="text" name="driver_aadharcard_num" id="driver_aadharcard_num"
                                        class="form-control" placeholder="Aadhar Number Format: 246884637988"
                                        data-parsley-type="alphanum" data-parsley-pattern="^[0-9]{12}"
                                        data-parsley-whitespace="trim" data-parsley-trigger="keyup"
                                        value="<?= $driver_aadharcard_num; ?>" data-parsley-check_driver_aadharcard_num
                                        data-parsley-check_driver_aadharcard_num-message="Entered Aadhar Number Already Exists"
                                        data-parsley-errors-container="#driver_aadharcard_num_error_container" />
                                    <input type="hidden" name="old_driver_aadharcard_num" id="old_driver_aadharcard_num"
                                        value="<?= $driver_aadharcard_num; ?>" />
                                    <small class="text-dark"><b>Aadhar Number Format: 246884637988</b> </small>
                                </div>
                                <div id="driver_aadharcard_num_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="driver_pan_card">PAN Card Number</label>
                                <div class="form-group">
                                    <input type="text" name="driver_pan_card" id="driver_pan_card" class="form-control"
                                        placeholder="Pan Format: CNFPC5441D" data-parsley-type="alphanum"
                                        data-parsley-pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" data-parsley-whitespace="trim"
                                        data-parsley-trigger="keyup" value="<?= $driver_pan_card; ?>"
                                        data-parsley-errors-container="#driver_pan_card_error_container"
                                        data-parsley-check_driver_pan_card
                                        data-parsley-check_driver_pan_card-message="Entered PAN Number Already Exists" />
                                    <input type="hidden" name="old_driver_pan_card" id="old_driver_pan_card"
                                        value="<?= $driver_pan_card; ?>" />
                                    <small class="text-dark"><b>Pan Format: CNFPC5441D</b> </small>
                                </div>
                                <div id="driver_pan_card_error_container"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="driver_voter_id_num">Voter ID Number</label>
                                <div class="form-group">
                                    <input type="text" name="driver_voter_id_num" id="driver_voter_id_num" class="form-control"
                                        placeholder="Voter ID Number" value="<?= $driver_voter_id_num; ?>" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="formValidationUsername">Upload Profile</label>
                                <div class="form-group d-flex">
                                    <input class="input-file" name="file" type="file" id="file-input" accept="image/*">
                                    <input type="hidden" name="old_file_name" value="<?= $driver_profile_image ?>">
                                </div>
                            </div>

                            <div class="col-md-auto" id="file-container">
                                <label class="driver-text-label" for="formValidationUsername">Uploaded Profile</label>
                                <div class="form-group d-flex logo-img-container">
                                    <a id="file-link" href="<?= BASEPATH; ?>uploads/driver_gallery/<?= $driver_profile_image; ?>" download="<?= $driver_profile_image; ?>">
                                        <img id="file-content"
                                            src="<?= BASEPATH; ?>uploads/driver_gallery/<?= $driver_profile_image; ?>"
                                            alt="Uploaded Image"
                                            width="100px"
                                            height="83px" />
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="driver-text-label" for="driver_address">Address</label>
                                <div class="form-group">
                                    <textarea id="driver_address" name="driver_address" class="form-control"
                                        placeholder="Address" type="text" autocomplete="off" value="" rows="3" maxlength="255"><?= $driver_address; ?></textarea>
                                </div>
                                <input type="hidden" name="hidden_driver_ID" id="hidden_driver_ID" class="form-control"
                                    value="<?= $driver_ID; ?>" />
                            </div>
                        </div>
                        <div class=" mt-3">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="driver.php" class="btn btn-secondary">Back
                                    </a>
                                </div>

                                <button type="submit" id="submit_driver_basic_info_btn"
                                    class="btn btn-primary btn-md"><?= $btn_label; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>
        <script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
        <script>
            document.getElementById('file-input').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageURL = e.target.result;

                        // Update the image source
                        document.getElementById('file-content').src = imageURL;

                        // Update the download link
                        const fileLink = document.getElementById('file-link');
                        fileLink.href = imageURL;
                        fileLink.download = file.name; // Set the filename for download

                        // Show the image container
                        document.getElementById('file-container').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
            <?php if (empty($driver_profile_image) || empty($driver_ID)) : ?>
                document.getElementById('file-container').style.display = 'none';
            <?php endif; ?>
            document.getElementById('file-input').addEventListener('change', function() {
                document.getElementById('file-container').style.display = 'block';
                var file = this.files[0];
                var reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('file-content').src = e.target.result;
                }

                reader.readAsDataURL(file);
            });
            $(document).ready(function() {

                $(".form-select").selectize();
                <?php if (isset($driver_ID)) : ?>
                    showVEHICLE_TYPES();
                <?php endif; ?>

                flatpickr('#driver_date_of_birth', {
                    dateFormat: 'd-m-Y',
                    defaultDate: $('#driver_date_of_birth').val() // Ensure the default date is set correctly
                });


                flatpickr('#driver_license_issue_date', {
                    dateFormat: 'd-m-Y',
                });
                flatpickr('#driver_license_expiry_date', {
                    dateFormat: 'd-m-Y',
                });

                $("#vendor_id").attr("required", true);
                $("#vendor_vehicle_id").attr("required", true);
                // $("#driver_blood_group").attr("required", true);
                // $("#driver_gender").attr("required", true);

                //CHECK DUPLICATE DRIVER MOBILE NUMBER
                // $('#driver_primary_mobile_number').parsley();
                // var old_driver_primary_mobile_numberDETAIL = document.getElementById("old_driver_primary_mobile_number")
                //     .value;
                // var driver_primary_mobile_number = $('#driver_primary_mobile_number').val();
                // window.ParsleyValidator.addValidator('check_driver_primary_number', {
                //     validateString: function(value) {
                //         return $.ajax({
                //             url: 'engine/ajax/__ajax_check_driver_mobilenum.php',
                //             method: "POST",
                //             data: {
                //                 driver_primary_mobile_number: value,
                //                 old_driver_primary_mobile_number: old_driver_primary_mobile_numberDETAIL
                //             },
                //             dataType: "json",
                //             success: function(data) {
                //                 return true;
                //             }
                //         });
                //     }
                // });

                //CHECK DUPLICATE DRIVER EMAIL ID
                $('#driver_email').parsley();
                var old_driver_email_DETAIL = document.getElementById("old_driver_email").value;
                var driver_email = $('#driver_email').val();
                window.ParsleyValidator.addValidator('check_driver_email', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_driver_email.php',
                            method: "POST",
                            data: {
                                driver_email: value,
                                old_driver_email: old_driver_email_DETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //CHECK DUPLICATE DRIVER PAN NUMBER
                $('#driver_pan_card').parsley();
                var old_driver_pan_card_DETAIL = document.getElementById("old_driver_pan_card").value;
                var driver_pan_card = $('#driver_pan_card').val();
                window.ParsleyValidator.addValidator('check_driver_pan_card', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_driver_pan_card.php',
                            method: "POST",
                            data: {
                                driver_pan_card: value,
                                old_driver_pan_card: old_driver_pan_card_DETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //CHECK DUPLICATE DRIVER AADHAR NUMBER
                $('#driver_aadharcard_num').parsley();
                var old_driver_aadharcard_num_DETAIL = document.getElementById("old_driver_aadharcard_num").value;
                var driver_aadharcard_num = $('#driver_aadharcard_num').val();
                window.ParsleyValidator.addValidator('check_driver_aadharcard_num', {
                    validateString: function(value) {
                        return $.ajax({
                            url: 'engine/ajax/__ajax_check_driver_aadharcard_num.php',
                            method: "POST",
                            data: {
                                driver_aadharcard_num: value,
                                old_driver_aadharcard_num: old_driver_aadharcard_num_DETAIL
                            },
                            dataType: "json",
                            success: function(data) {
                                return true;
                            }
                        });
                    }
                });

                //AJAX FORM SUBMIT
                $("#form_add_driver_basic").submit(function(event) {
                    var form = $('#form_add_driver_basic')[0];
                    var data = new FormData(form);
                    $(this).find("button[id='submit_driver_basic_info_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_driver.php?type=driver_basic_info',
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
                            if (response.errors.driver_name_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Name Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.driver_code_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Code Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.vendor_id_required) {
                                TOAST_NOTIFICATION('warning', 'Vendor is Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.driver_primary_mobile_number_required) {
                                TOAST_NOTIFICATION('warning', 'Mobile No Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.driver_alternate_mobile_number_required) {
                                TOAST_NOTIFICATION('warning', 'Email ID Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
                            } else if (response.errors.hotel_address_required) {
                                TOAST_NOTIFICATION('warning', 'Address Required', 'Warning !!!', '', '', '',
                                    '', '', '', '', '', '');
                            } else if (response.errors.hotel_category_required) {
                                TOAST_NOTIFICATION('warning', 'Category Required', 'Warning !!!', '', '',
                                    '', '', '', '', '', '', '');
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
                                // } else if (response.errors.hotel_postal_code_required) {
                                //     TOAST_NOTIFICATION('warning', 'Postal Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                                // }
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                // alert();
                                TOAST_NOTIFICATION('success', 'Driver Basic Info Created Successfully',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');

                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Driver Basic Details Updated', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('warning', 'Unable to Add Driver Basic Details',
                                    'Error !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('warning', 'Unable to Add Driver Basic Details',
                                    'Error !!!', '', '', '', '', '', '', '', '', '');
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

            function showVEHICLE_TYPES() {

                var vendor_vehicle_selectize = $("#vendor_vehicle_id")[0].selectize;
                var vendor_id = $("#vendor_id").val();
                $.ajax({
                    url: 'engine/ajax/__ajax_get_vendor_vehicle_types.php?type=selectize_vehicle_types',
                    type: "POST",
                    data: {
                        vendor_id: vendor_id
                    },
                    success: function(response) {
                        // Append the response to the dropdown.
                        vendor_vehicle_selectize.clear();
                        vendor_vehicle_selectize.clearOptions();
                        vendor_vehicle_selectize.addOption(response);
                        <?php if (isset($driver_ID)) : ?>
                            vendor_vehicle_selectize.setValue(<?= $vehicle_type_id ?>);
                        <?php endif; ?>
                    }
                });
            }
        </script>
    <?php
    elseif ($_GET['type'] == 'driver_cost') :

        $driver_ID = $_GET['ID'];

        if ($driver_ID != '' && $driver_ID != 0) :
            $select_hotel_list_query = sqlQUERY_LABEL("SELECT `driver_salary`, `driver_food_cost`, `driver_accomdation_cost`, `driver_bhatta_cost`, `driver_gst_type`, `driver_early_morning_charges`, `driver_evening_charges` FROM `dvi_driver_costdetails` WHERE `deleted` = '0' and `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_DRIVER_LIST:" . sqlERROR_LABEL());

            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_hotel_list_query)) :
                $driver_salary = $fetch_list_data['driver_salary'];
                $driver_food_cost = $fetch_list_data['driver_food_cost'];
                $driver_accomdation_cost = $fetch_list_data['driver_accomdation_cost'];
                $driver_bhatta_cost = $fetch_list_data['driver_bhatta_cost'];
                $driver_gst_type = $fetch_list_data['driver_gst_type'];
                $driver_early_morning_charges = $fetch_list_data['driver_early_morning_charges'];
                $driver_evening_charges = $fetch_list_data['driver_evening_charges'];
            endwhile;

        endif;

        if ($driver_ID != '' && $driver_ID != 0 && $_GET['ROUTE'] == 'edit') :
            $basic_info_url = 'driver.php?route=edit&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=edit&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=edit&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=edit&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=edit&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Update & Continue";
        else :
            $basic_info_url = 'driver.php?route=add&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'javascript:;';
            $driver_upload_documents_url = 'javascript:;';
            $driver_feedback_url = 'javascript:;';
            $driver_create_preview_url = 'javascript:;';

            $btn_label = "Save & Continue";
        endif;
    ?>
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_cost_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle active-stepper">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title">Cost Details</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_upload_documents_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Upload Document</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_feedback_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Feedback & Review</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_create_preview_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Preview</h5>
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
                    <form class="" id="form_add_driver_cost" method="post" data-parsley-validate>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" for="driver_salary">Driver Salary ₹<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_salary" id="driver_salary" class="form-control"
                                        placeholder="Driver Salary" required value="<?= $driver_salary; ?>"
                                        data-parsley-type="number" data-parsley-error-message="Please enter valid price"
                                        data-parsley-trigger="keyup" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="driver_food_cost">Food Cost ₹<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_food_cost" id="driver_food_cost" class="form-control"
                                        placeholder="Food Cost" value="<?= $driver_food_cost; ?>" required
                                        data-parsley-type="number" data-parsley-error-message="Please enter valid price"
                                        data-parsley-trigger="keyup" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="driver_accomdation_cost">Accomdation Cost ₹<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_accomdation_cost" id="driver_accomdation_cost"
                                        class="form-control" placeholder="Accomdation Cost"
                                        value="<?= $driver_accomdation_cost; ?>" required data-parsley-type="number"
                                        data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="driver_bhatta_cost">Bhatta Cost ₹<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_bhatta_cost" id="driver_bhatta_cost" class="form-control"
                                        placeholder="Bhatta Cost" value="<?= $driver_bhatta_cost; ?>" required
                                        data-parsley-type="number" data-parsley-error-message="Please enter valid price"
                                        data-parsley-trigger="keyup" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" for="driver_early_morning_charges">Early Morning Charges(₹)(Before 6
                                    AM)<span class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_early_morning_charges" id="driver_early_morning_charges"
                                        class="form-control" placeholder="Early Morning Charges"
                                        value="<?= $driver_early_morning_charges; ?>" required data-parsley-type="number"
                                        data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="driver_evening_charges">Evening Charges (₹)(After 6 PM)<span
                                        class=" text-danger"> *</span></label>
                                <div class="form-group">
                                    <input type="text" name="driver_evening_charges" id="driver_evening_charges"
                                        class="form-control" placeholder="Evening Charges"
                                        value="<?= $driver_evening_charges; ?>" required data-parsley-type="number"
                                        data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" />
                                </div>
                            </div>
                            <?php
                            /* <div class="col-md-3">
                                <label class="form-label" for="driver_gst_type">GST Type<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <select id="driver_gst_type" name="driver_gst_type" required
                                        class="form-select form-control" required>
                                        <?= getGSTTYPE($driver_gst_type, 'select'); ?>
                                    </select>
                                </div>
                            </div> */
                            ?>
                            <input type="hidden" name="driver_gst_type" id="driver_gst_type" value="<?= $driver_gst_type; ?>" hidden>
                        </div>

                        <input type="hidden" name="hidden_driver_ID" id="hidden_driver_ID" class="form-control"
                            value="<?= $driver_ID; ?>" />
                        <div class=" mt-5">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="<?= $basic_info_url; ?>" class="btn btn-secondary">Back
                                    </a>
                                </div>
                                <button type="submit" id="submit_driver_cost_btn"
                                    class="btn btn-primary btn-md"><?= $btn_label; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="assets/js/parsley.min.js"></script>
        <script>
            $(document).ready(function() {
                $(".form-select").selectize();
                //AJAX FORM SUBMIT
                $("#form_add_driver_cost").submit(function(event) {
                    var form = $('#form_add_driver_cost')[0];
                    var data = new FormData(form);
                    $(this).find("button[id='submit_driver_cost_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_driver.php?type=driver_cost',
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
                            if (response.errors.driver_salary_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Salary Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_early_morning_charges_required) {
                                TOAST_NOTIFICATION('warning',
                                    'Driver Early Morning  Percentage is Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_evening_charges_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Evening Charges is Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_food_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Food Cost Required', 'Warning !!!',
                                    '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_accomdation_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Accomdation Cost is Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_bhatta_cost_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Bhatta Cost is Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_gst_percentage_required) {
                                TOAST_NOTIFICATION('warning', 'Driver GST Percentage is Required',
                                    'Warning !!!', '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                // alert();
                                TOAST_NOTIFICATION('success', 'Driver Cost Created Successfully',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');

                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                TOAST_NOTIFICATION('success', 'Driver Cost Updated', 'Success !!!', '', '',
                                    '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('warning', 'Unable to Add Driver Cost Details',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('warning', 'Unable to Add Driver Cost Details',
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
    elseif ($_GET['type'] == 'driver_upload_documents') :

        $driver_ID = $_GET['ID'];

        if ($driver_ID != '' && $driver_ID != 0 && $_GET['ROUTE'] == 'edit') :
            $basic_info_url = 'driver.php?route=edit&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=edit&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=edit&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=edit&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=edit&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Update & Continue";
        else :
            $basic_info_url = 'driver.php?route=add&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=add&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=add&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=add&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=add&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Save & Continue";
        endif;

    ?>
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_cost_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Cost Details</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_upload_documents_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle active-stepper">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title">Upload Document</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_feedback_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Feedback & Review</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_create_preview_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Preview</h5>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card p-4">
                    <div class="upload_document_image">
                        <div class="card-body bulk-upload-body" id="bulk-upload-body">
                            <div class="d-flex justify-content-between">
                                <h4>Upload Documents</h4>
                                <div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#upload_document_modal">
                                        + Upload
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <?php if ($driver_ID != '') :
                                    $select_driver_list = sqlQUERY_LABEL("SELECT `driver_code`, `driver_name` FROM `dvi_driver_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_DRIVER_DETAILS:" . sqlERROR_LABEL());

                                    while ($fetch_driver_data = sqlFETCHARRAY_LABEL($select_driver_list)) :
                                        $counter++;
                                        $driver_code = $fetch_driver_data['driver_code'];
                                        $driver_name = $fetch_driver_data['driver_name'];
                                    endwhile;

                                    $select_list = sqlQUERY_LABEL("SELECT `driver_document_details_id`, `driver_id`, `document_type`, `driver_document_name`, `status` FROM `dvi_driver_document_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID' ORDER BY 'document_type' ASC") or die("#1-UNABLE_TO_COLLECT_VEHICAL_COST_LIST:" . sqlERROR_LABEL());

                                    $num_row_document = sqlNUMOFROW_LABEL($select_list);
                                    if ($num_row_document > 0) :
                                        $btn_next_step = "Save & Continue";
                                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                            $counter++;
                                            $driver_document_details_id = $fetch_data['driver_document_details_id'];
                                            $driver_id = $fetch_data['driver_id'];
                                            $document_type = $fetch_data['document_type'];
                                            $driver_document_name = $fetch_data['driver_document_name'];
                                ?>
                                            <div class="col-md-3  my-2 position-relative" id="<?= $driver_document_name ?>">
                                                <div class="my-2">
                                                    <label><?= getDOCUMENTTYPE($document_type, 'label'); ?></label>
                                                </div>

                                                <a href="uploads/driver_gallery/<?= $driver_document_name; ?>" class="fs-6" download>
                                                    <img src="assets/img/uploaded_file.png"
                                                        class="room-details-shadow  cursor rounded" width="200px" height="120px">
                                                </a>
                                                <button class="driver-image-close"
                                                    onclick="closeImage('<?= $driver_document_name ?>')">X</button>
                                            </div>
                                        <?php
                                        endwhile;

                                    else :
                                        $btn_next_step = "Skip & Continue";
                                        ?>
                                        <div class="text-center mt-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="150" version="1.1" viewBox="-23 0 512 512"
                                                width="150">
                                                <g id="surface1">
                                                    <path
                                                        d="M 337.953125 230.601562 C 404.113281 239.886719 455.015625 296.65625 455.015625 365.378906 C 455.015625 440.503906 394.082031 501.4375 318.957031 501.4375 C 267.3125 501.4375 222.277344 472.625 199.335938 430.152344 C 188.878906 410.839844 182.902344 388.75 182.902344 365.273438 C 182.902344 290.148438 243.835938 229.214844 318.957031 229.214844 C 325.363281 229.320312 331.660156 229.75 337.953125 230.601562 Z M 337.953125 230.601562 "
                                                        style="stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                                                    <path
                                                        d="M 337.953125 230.601562 C 331.765625 229.75 325.363281 229.320312 318.957031 229.320312 C 243.835938 229.320312 182.902344 290.253906 182.902344 365.378906 C 182.902344 388.855469 188.878906 410.945312 199.335938 430.257812 L 199.121094 430.367188 L 57.199219 430.367188 C 31.265625 430.367188 10.242188 409.34375 10.242188 383.414062 L 10.242188 57.730469 C 10.242188 31.800781 31.265625 10.777344 57.199219 10.777344 L 229.429688 10.777344 L 229.429688 88.464844 C 229.429688 108.523438 245.648438 124.746094 265.710938 124.746094 L 337.953125 124.746094 Z M 337.953125 230.601562 "
                                                        style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                                                    <path
                                                        d="M 229.429688 10.777344 L 337.953125 124.746094 L 265.710938 124.746094 C 245.648438 124.746094 229.429688 108.523438 229.429688 88.464844 Z M 229.429688 10.777344 "
                                                        style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;" />
                                                    <path
                                                        d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.96875 347.664062 119.410156 345.851562 117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.199219 0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.199219 440.71875 L 193.148438 440.71875 C 219.609375 485.535156 267.203125 512 318.960938 512 C 399.847656 512 465.6875 446.265625 465.6875 365.273438 C 465.6875 329.632812 452.988281 295.375 429.511719 268.59375 C 408.277344 244.476562 379.890625 228.042969 348.945312 221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 240.097656 102.550781 240.097656 88.464844 L 240.097656 37.457031 Z M 21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.199219 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 L 327.605469 135.523438 L 327.605469 218.863281 C 324.402344 218.757812 321.839844 218.332031 319.066406 218.332031 C 281.824219 218.332031 247.570312 232.628906 221.746094 255.039062 L 86.222656 255.039062 C 80.355469 255.039062 75.550781 259.839844 75.550781 265.710938 C 75.550781 271.582031 80.351562 276.382812 86.222656 276.382812 L 201.898438 276.382812 C 194.320312 287.054688 188.023438 297.726562 183.117188 309.464844 L 86.222656 309.464844 C 80.355469 309.464844 75.550781 314.265625 75.550781 320.132812 C 75.550781 326.003906 80.351562 330.804688 86.222656 330.804688 L 176.179688 330.804688 C 173.511719 341.476562 172.125 353.320312 172.125 365.167969 C 172.125 383.839844 175.644531 402.300781 182.476562 419.375 L 57.199219 419.375 C 37.242188 419.375 21.34375 403.367188 21.34375 383.414062 Z M 318.960938 490.765625 C 272.96875 490.765625 230.601562 465.582031 208.621094 425.136719 C 198.695312 406.890625 193.46875 386.292969 193.46875 365.378906 C 193.46875 296.230469 249.703125 239.992188 318.851562 239.992188 C 324.722656 239.992188 330.589844 240.421875 336.351562 241.167969 C 366.019531 245.328125 393.335938 260.054688 413.183594 282.679688 C 433.246094 305.515625 444.238281 334.859375 444.238281 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625"
                                                        style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7"
                                                        data-original="#000000" />
                                                    <path
                                                        d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.355469 201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.355469 223.027344 86.222656 223.027344 Z M 86.222656 223.027344 "
                                                        style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7"
                                                        data-original="#000000" />
                                                    <path
                                                        d="M 326.535156 286.625 C 324.507812 284.492188 321.734375 283.210938 318.746094 283.210938 C 315.757812 283.210938 312.984375 284.492188 310.957031 286.625 L 248.425781 353.746094 C 244.367188 358.015625 244.6875 364.84375 248.957031 368.792969 C 250.984375 370.714844 253.652344 371.675781 256.214844 371.675781 C 259.09375 371.675781 262.082031 370.5 264.21875 368.257812 L 308.394531 320.984375 L 308.394531 437.515625 C 308.394531 443.382812 313.199219 448.1875 319.066406 448.1875 C 324.9375 448.1875 329.738281 443.382812 329.738281 437.515625 L 329.738281 320.988281 L 373.597656 368.261719 C 377.652344 372.527344 384.269531 372.847656 388.644531 368.792969 C 392.910156 364.738281 393.125 358.015625 389.175781 353.746094 Z M 326.535156 286.625 "
                                                        style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7"
                                                        data-original="#000000" />
                                                </g>
                                            </svg>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                <?php endif; ?>
                            </div>

                        </div>
                        <input type="hidden" name="hidden_driver_ID" id="hidden_driver_ID" class="form-control"
                            value="<?= $driver_ID; ?>" />
                        <div class=" mt-5">
                            <div class="d-flex justify-content-between py-3">
                                <div>
                                    <a href="<?= $driver_cost_url; ?>" class="btn btn-secondary">Back
                                    </a>
                                </div>
                                <div>
                                    <a href="<?= $driver_feedback_url; ?>" type="button"
                                        class="btn btn-primary waves-effect waves-light"><?= $btn_next_step; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content p-4">
                    <div class="modal-body receiving-subject-form-data">
                        <!-- Plugins css Ends-->
                        <form id="ajax_upload_document_form" enctype="multipart/form-data">
                            <div class="modal-header pt-0 border-0">
                                <h4 class="modal-title mx-auto" style="color:black">Document Upload</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="formValidationUsername">Document Type<span
                                            class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <select id="document_type" name="document_type" class="form-select form-control"
                                            required>
                                            <?= getDOCUMENTTYPE('', 'select'); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="formValidationUsername">Upload Document<span
                                            class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="file" class="input-file" id="fileInput" name="file">
                                    </div>
                                </div>

                                <input type="hidden" name="hidden_driver_ID" id="hidden_driver_ID" class="form-control"
                                    value="<?= $driver_ID; ?>" />
                            </div>
                            <div class="d-flex justify-content-center pt-4">
                                <button type="button" class="btn btn-label-github waves-effect mx-1" data-dismiss="modal"
                                    aria-label="Close">Close</button>
                                <button type="submit" id="submit_driver_upload_document_btn" class="btn btn-primary btn-md">
                                    <!-- <button type="button" class="btn btn-primary mx-1"> -->
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="assets/js/selectize/selectize.min.js"></script>
        <script src="assets/js/parsley.min.js"></script>
        <script>
            function closeImage(containerId) {
                var containerElement = document.getElementById(containerId);
                if (containerElement) {
                    // Remove the container element (which includes both the image and the close button)
                    containerElement.parentNode.removeChild(containerElement);
                }
            }
            $(document).ready(function() {
                $(".form-select").selectize();
                // funtion upload_document_div(driver_ID){
                //     $.ajax({
                //         type: 'post',
                //         url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID='.$driver_ID,
                //         success: function(response) {
                //             $('#upload_document_image').html('');
                //             $('#upload_document_image').html(response);
                //         }
                //     });
                // }

                //AJAX FORM SUBMIT
                $("#ajax_upload_document_form").submit(function(event) {
                    var form = $('#ajax_upload_document_form')[0];
                    var data = new FormData(form);
                    $(this).find("button[id='submit_driver_upload_document_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_driver.php?type=driver_upload_document',
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
                            if (response.errors.document_type_required) {
                                TOAST_NOTIFICATION('warning', 'Document Type Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.errors.driver_document_required) {
                                TOAST_NOTIFICATION('warning', 'Driver Document Required', 'Warning !!!', '',
                                    '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {

                                //SUCCESS RESPOSNE
                                $("button[id='submit_driver_upload_document_btn']").prop('disabled', false);
                                $('#ajax_upload_document_form')[0].reset();
                                $('#upload_document_modal').modal('hide');
                                // upload_document_div(<?= $_GET['ID'] ?>);

                                $.ajax({
                                    type: 'post',
                                    url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID=<?= $_GET['ID']; ?>',
                                    success: function(response) {
                                        $('#upload_document_image').html('');
                                        $('#upload_document_image').html(response);
                                    }
                                });
                                TOAST_NOTIFICATION('success', 'Upload Document Created Successfully',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS
                                $("button[id='submit_driver_upload_document_btn']").prop('disabled', false);
                                $('#ajax_upload_document_form')[0].reset();
                                $('#upload_document_modal').modal('hide');
                                // upload_document_div(<?= $_GET['ID'] ?>);
                                $.ajax({
                                    type: 'post',
                                    url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID=<?= $_GET['ID']; ?>',
                                    success: function(response) {
                                        $('#upload_document_image').html('');
                                        $('#upload_document_image').html(response);
                                    }
                                });
                                TOAST_NOTIFICATION('success', 'Upload Document Updated Successfully',
                                    'Success !!!', '', '', '', '', '', '', '', '', '');
                                setTimeout(function() {
                                    location.assign(response.redirect_URL);
                                }, 1000);
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('warning',
                                    'Unable to Add Driver Upload Document Details', 'Success !!!', '',
                                    '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('warning',
                                    'Unable to Add Driver Upload Document Details', 'Success !!!', '',
                                    '', '', '', '', '', '', '', '');
                            }
                            location.reload();
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
    elseif ($_GET['type'] == 'driver_feedback') :

        $driver_ID = $_GET['ID'];

        if ($driver_ID != '' && $driver_ID != 0 && $_GET['ROUTE'] == 'edit') :
            $basic_info_url = 'driver.php?route=edit&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=edit&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=edit&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=edit&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=edit&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Update & Continue";
        else :
            $basic_info_url = 'driver.php?route=add&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=add&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=add&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=add&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=add&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Save & Continue";
        endif;

    ?>
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_cost_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Cost Details</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_upload_documents_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Upload Document</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_feedback_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  active-stepper">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title">Feedback & Review</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_create_preview_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">5</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Preview</h5>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3 justify-content-center">
            <div class="col-4">
                <div class="card mb-4 p-4">
                    <!-- Plugins css Ends-->
                    <form id="form_guide_review" class="row g-2" action="" method="post" data-parsley-validate>
                        <div class="col-12 row g-2" id="ajax_form_review">
                            <div class="col-12">
                                <label class="form-label text-primary fs-5" for="driver_rating">Rating</label>
                                <div class="form-group">
                                    <div id="edited_star_ratings" data-rateyo-full-star="true"></div>
                                    <input type="hidden" name="driver_rating" id="driver_rating" value="1" />
                                </div>
                                <p class="pe-2 my-2">All reviews are from genuine customers</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label w-100" for="review_description">Feedback<span class=" text-danger">
                                        *</span></label>
                                <div class="form-group">
                                    <textarea class="form-control" id="review_description" name="review_description" rows="3"
                                        required></textarea>
                                </div>
                            </div>
                            <input type="hidden" name="hiddenDRIVER_ID" id="hiddenDRIVER_ID" value="<?= $driver_ID; ?>" />
                            <input type="hidden" name="hiddenDRIVER_REVIEW_ID" id="hiddenDRIVER_REVIEW_ID" value="" />
                        </div>
                        <div class="col-12 text-end pt-4">
                            <div>
                                <button type="submit" id="submit_driver_rating_btn" class="btn btn-primary btn-md">Save</button>
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
                            <table id="driver_review_LIST" class="table table-hover">
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
                    <a href="<?= $driver_upload_documents_url; ?>" class="btn btn-secondary">Back
                    </a>
                </div>
                <div>
                    <a href="<?= $driver_create_preview_url; ?>" class="btn btn-primary btn-md"><?= $btn_label; ?></a>
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
                        $("#driver_rating").val(rating);
                    }
                });

                $('#driver_review_LIST').DataTable({
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
                        "url": "engine/json/__JSONdriverreviewlist.php?id=<?= $driver_ID; ?>",
                        "type": "GET"
                    },
                    columns: [{
                            data: "count"
                        }, //0
                        {
                            data: "driver_rating"
                        }, //1
                        {
                            data: "driver_description"
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
                            "data": "driver_rating",
                            "render": function(data, type, full) {
                                return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2">' +
                                    data + '<i class="ti ti-star-filled"></i></h2>';
                            }
                        },
                        {
                            "targets": 4,
                            "data": "modify",
                            "render": function(data, type, full) {
                                return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" onclick="show_RATING_FORM(<?= $driver_ID; ?>, ' +
                                    data +
                                    ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDRIVERREVIEWDELETEMODAL(<?= $driver_ID; ?>, ' +
                                    data +
                                    ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                            }
                        }
                    ],
                });

                //AJAX FORM SUBMIT
                $("#form_guide_review").submit(function(event) {
                    var form = $('#form_guide_review')[0];
                    var data = new FormData(form);
                    //$(this).find("button[id='submit_driver_rating_btn']").prop('disabled', true);
                    $.ajax({
                        type: "post",
                        url: 'engine/ajax/__ajax_manage_driver.php?type=driver_review',
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
                                TOAST_NOTIFICATION('warning', 'Review Description Required', 'Warning !!!',
                                    '', '', '', '', '', '', '', '', '');
                            }
                        } else {
                            //SUCCESS RESPOSNE
                            if (response.i_result == true) {
                                //RESULT SUCCESS

                                TOAST_NOTIFICATION('success', 'Driver Review Added', 'Success !!!', '', '',
                                    '', '', '', '', '', '', '');
                                $('#hiddenDRIVER_REVIEW_ID').val('');
                                show_RATING_FORM(response.guide_id, '');
                                $('#driver_review_LIST').DataTable().ajax.reload();
                                location.reload();
                            } else if (response.u_result == true) {
                                //RESULT SUCCESS

                                TOAST_NOTIFICATION('success', 'Driver Review Updated', 'Success !!!', '',
                                    '', '', '', '', '', '', '', '');
                                $('#hiddenDRIVER_REVIEW_ID').val('');
                                show_RATING_FORM(response.guide_id, '');
                                $('#driver_review_LIST').DataTable().ajax.reload();
                                location.reload();
                            } else if (response.i_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Add Driver Review', 'Success !!!',
                                    '', '', '', '', '', '', '', '', '');
                            } else if (response.u_result == false) {
                                //RESULT FAILED
                                TOAST_NOTIFICATION('success', 'Unable to Update Driver Review',
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
                    url: 'engine/ajax/__ajax_add_driver_form.php?type=driver_rating_form',
                    data: {
                        ID: id,
                        REVIEW_ID: review_id,
                        // Add more data key-value pairs as needed
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#ajax_form_review').html(response.form);
                        $('#submit_driver_rating_btn').html(response.btn_label);
                        $("html, body").animate({
                            scrollTop: 0
                        }, "slow");

                        if (response.driver_rating) {
                            rating = response.driver_rating;
                        } else {
                            rating = 1;
                        }
                        $("#edited_star_ratings").rateYo({
                            rating: rating,
                            fullStar: true,
                            onSet: function(rating, rateYoInstance) {
                                //alert("Rating is set to: " + rating);
                                $("#driver_rating").val(rating);
                            }
                        });
                    }
                });
            }

            //SHOW DELETE POPUP
            function showDRIVERREVIEWDELETEMODAL(ID, REVIEW) {
                $('.receiving-delete-form-data').load('engine/ajax/__ajax_manage_driver.php?type=driver_review_delete&REVIEW=' +
                    REVIEW + '&ID=' + ID,
                    function() {
                        const container = document.getElementById("showDELETEMODAL");
                        const modal = new bootstrap.Modal(container);
                        modal.show();
                    });
            }

            function confirmDRIVERREVIEWDELETE(id, review_id) {
                $.ajax({
                    type: 'post',
                    url: 'engine/ajax/__ajax_manage_driver.php?type=deleted_review',
                    data: {
                        ID: id,
                        REVIEW_ID: review_id,
                        // Add more data key-value pairs as needed
                    },
                    dataType: 'json',
                    success: function(response) {
                        $('#showDELETEMODAL').modal('hide');
                        $('#driver_review_LIST').DataTable().ajax.reload();
                    }
                });
            }
        </script>
        <script>
            $('#guide_review_LIST').DataTable({
                dom: 'Blfrtip',
                "bFilter": true,
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'pdfHtml5'
                ],
                initComplete: function() {
                    $('.buttons-copy').html(
                        '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>'
                    );


                    $('.buttons-excel').html(
                        '<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>'
                    );

                    $('.buttons-pdf').html(
                        '<a href="javascript:;" class="d-flex align-items-center btn btn-sm  btn-outline-danger"><svg version="1.1" fill="currentColor" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" class="me-2" width="16" height="16" xml:space="preserve"><g><g><path d="M494.479,138.557L364.04,3.018C362.183,1.09,359.621,0,356.945,0h-194.41c-21.757,0-39.458,17.694-39.458,39.442v137.789H44.29c-16.278,0-29.521,13.239-29.521,29.513v147.744C14.769,370.761,28.012,384,44.29,384h78.787v88.627c0,21.71,17.701,39.373,39.458,39.373h295.238c21.757,0,39.458-17.653,39.458-39.351V145.385 C497.231,142.839,496.244,140.392,494.479,138.557zM359.385,26.581l107.079,111.265H359.385V26.581z M44.29,364.308c-5.42,0-9.828-4.405-9.828-9.82V206.744c0-5.415,4.409-9.821,9.828-9.821h265.882c5.42,0,9.828,4.406,9.828,9.821v147.744c0,5.415-4.409,9.82-9.828,9.82H44.29zM477.538,472.649c0,10.84-8.867,19.659-19.766,19.659H162.535c-10.899,0-19.766-8.828-19.766-19.68V384h167.403c16.278,0,29.521-13.239,29.521-29.512V206.744c0-16.274-13.243-29.513-29.521-29.513H142.769V39.442c0-10.891,8.867-19.75,19.766-19.75h177.157v128c0,5.438,4.409,9.846,9.846,9.846h128V472.649z"/></g></g><g><g><path d="M132.481,249.894c-3.269-4.25-7.327-7.01-12.173-8.279c-3.154-0.846-9.923-1.269-20.308-1.269H72.596v84.577h17.077v-31.904h11.135c7.731,0,13.635-0.404,17.712-1.212c3-0.654,5.952-1.99,8.856-4.01c2.904-2.019,5.298-4.798,7.183-8.336c1.885-3.538,2.827-7.904,2.827-13.096C137.385,259.634,135.75,254.144,132.481,249.894z M117.856,273.173c-1.288,1.885-3.067,3.269-5.337,4.154s-6.769,1.327-13.5,1.327h-9.346v-24h8.25c6.154,0,10.25,0.192,12.288,0.577c2.769,0.5,5.058,1.75,6.865,3.75c1.808,2,2.712,4.539,2.712,7.615C119.789,269.096,119.144,271.288,117.856,273.173z"/></g></g><g><g><path d="M219.481,263.452c-1.846-5.404-4.539-9.971-8.077-13.702s-7.789-6.327-12.75-7.789c-3.692-1.077-9.058-1.615-16.096-1.61h-31.212v84.577h32.135c6.308,0,11.346-0.596,15.115-1.789c5.039-1.615,9.039-3.865,12-6.75c3.923-3.808,6.942-8.788,9.058-14.942c1.731-5.039,2.596-11.039,2.596-18C222.25,275.519,221.327,268.856,219.481,263.452z M202.865,298.183c-1.154,3.789-2.644,6.51-4.471,8.163c-1.827,1.654-4.125,2.827-6.894,3.519c-2.115,0.539-5.558,0.808-10.327,0.808h-12.75v0v-56.019h7.673c6.961,0,11.635,0.269,14.019,0.808c3.192,0.692,5.827,2.019,7.904,3.981c2.077,1.962,3.692,4.692,4.846,8.192c1.154,3.5,1.731,8.519,1.731,15.058C204.596,289.231,204.019,294.394,202.865,298.183z"/></g></g><g><g><polygon points="294.827,254.654 294.827,240.346 236.846,240.346 236.846,324.923 253.923,324.923 253.923,288.981 289.231,288.981 289.231,274.673 253.923,274.673 253.923,254.654"/></g></g></svg>PDF</a>'
                    );
                },
                ajax: {
                    "url": "engine/json/__JSONdriverreviewlist.php?id=<?= $driver_ID; ?>",
                    "type": "GET"
                },
                columns: [{
                        data: "count"
                    }, //0
                    {
                        data: "driver_rating"
                    }, //1
                    {
                        data: "driver_description"
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
                        "data": "guide_rating",
                        "render": function(data, type, full) {
                            return '<h2 class="text-primary d-flex align-items-center gap-1 mb-2">' + data +
                                '<i class="ti ti-star-filled"></i></h2>';
                        }
                    },
                    {
                        "targets": 4,
                        "data": "modify",
                        "render": function(data, type, full) {
                            return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" onclick="show_RATING_FORM(<?= $driver_ID; ?>, ' +
                                data +
                                ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDRIVERREVIEWDELETEMODAL(<?= $driver_ID; ?>, ' +
                                data +
                                ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
                        }
                    }
                ],
            });
        </script>
    <?php
    elseif ($_GET['type'] == 'driver_rating_form') :
        $DRIVER_ID = $_POST['ID'];
        $DRIVER_REVIEW_ID = $_POST['REVIEW_ID'];

        if ($DRIVER_REVIEW_ID != '' && $DRIVER_REVIEW_ID != 0) :
            $btn_label_form = 'Update';
        else :
            $btn_label_form = 'Save';
        endif;

        $select_guideREVIEW_query = sqlQUERY_LABEL("SELECT `driver_rating`, `driver_description` FROM `dvi_driver_review_details` WHERE `driver_review_id`='$DRIVER_REVIEW_ID' AND `driver_id`='$DRIVER_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_GUIDE_REVIEW_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_guideREVIEW_query)) :
            $counter++;
            $driver_rating = $fetch_list_data['driver_rating'];
            $driver_description = $fetch_list_data['driver_description'];
        endwhile;

        $response['driver_rating'] = $driver_rating;
        $response['btn_label'] = $btn_label_form;

        $response['form'] = '<div class="col-12">
			<label class="form-label w-100" for="driver_rating">Rating<span class=" text-danger"> *</span></label>
			<div class="form-group">
				<div id="edited_star_ratings" data-rateyo-full-star="true"></div>
				<input type="hidden" name="driver_rating" id="driver_rating" value="' . $driver_rating . '" />
			</div>
		</div>
		<div class="col-12">
			<label class="form-label w-100" for="review_description">Description<span class=" text-danger"> *</span></label>
			<div class="form-group">
				<textarea class="form-control" id="review_description" name="review_description" rows="3" required>' . $driver_description . '</textarea>
			</div>
		</div>
		<input type="hidden" name="hiddenDRIVER_ID" id="hiddenDRIVER_ID" value="' . $DRIVER_ID . '" />
		<input type="hidden" name="hiddenDRIVER_REVIEW_ID" id="hiddenDRIVER_REVIEW_ID" value="' . $DRIVER_REVIEW_ID . '" />';

        echo json_encode($response); ?>

    <?php
    elseif ($_GET['type'] == 'driver_create_preview') :

        $driver_ID = $_GET['ID'];

        $select_driverCREATEDPREVIEW_query = sqlQUERY_LABEL("SELECT `driver_id`, `driver_code`, `vendor_id`, `vehicle_type_id`,`driver_name`, `driver_primary_mobile_number`, `driver_alternate_mobile_number`, `driver_email`, `driver_aadharcard_num`, `driver_voter_id_num`, `driver_pan_card`, `driver_license_issue_date`, `driver_license_expiry_date`, `driver_license_number`, `driver_blood_group`, `driver_gender`, `driver_date_of_birth`, `driver_profile_image`, `driver_address` FROM `dvi_driver_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());

        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_driverCREATEDPREVIEW_query)) :
            $driver_id = $fetch_list_data['driver_id'];
            $driver_code = $fetch_list_data['driver_code'];
            $vendor_id = $fetch_list_data['vendor_id'];
            $vendor_name = getVENDORANDVEHICLEDETAILS($vendor_id, 'get_vendorname_from_vendorid');
            $vehicle_type_id = $fetch_list_data['vehicle_type_id'];
            $vehicle_type = getVEHICLETYPE_DETAILS($vehicle_type_id, 'label');
            $driver_name = $fetch_list_data['driver_name'];
            $driver_primary_mobile_number = $fetch_list_data['driver_primary_mobile_number'];
            $driver_alternate_mobile_number = $fetch_list_data['driver_alternate_mobile_number'];
            $driver_email = $fetch_list_data['driver_email'];
            $driver_aadharcard_num = $fetch_list_data['driver_aadharcard_num'];
            $driver_voter_id_num = $fetch_list_data['driver_voter_id_num'];
            $driver_pan_card = $fetch_list_data['driver_pan_card'];
            $driver_license_issue_date =
                dateformat_datepicker($fetch_list_data['driver_license_issue_date']);
            $driver_license_expiry_date =
                dateformat_datepicker($fetch_list_data['driver_license_expiry_date']);
            $driver_license_number = $fetch_list_data['driver_license_number'];
            $driver_blood_group = getBLOOD_GROUP($fetch_list_data['driver_blood_group'], 'label');
            $driver_gender = getGENDER($fetch_list_data['driver_gender'], 'label');
            $driver_date_of_birth = dateformat_datepicker($fetch_list_data['driver_date_of_birth']);
            $driver_profile_image = $fetch_list_data['driver_profile_image'];
            $driver_address = $fetch_list_data['driver_address'];
        endwhile;


        $select_driverCREATEDPREVIEW_cost_query = sqlQUERY_LABEL("SELECT `driver_costdetails_id`, `driver_id`, `driver_salary`, `driver_food_cost`, `driver_accomdation_cost`, `driver_bhatta_cost`, `driver_gst_type`, `driver_early_morning_charges`, `driver_evening_charges` FROM `dvi_driver_costdetails` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
        while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_driverCREATEDPREVIEW_cost_query)) :
            $driver_salary = $fetch_list_data['driver_salary'];
            $driver_food_cost = $fetch_list_data['driver_food_cost'];
            $driver_accomdation_cost = $fetch_list_data['driver_accomdation_cost'];
            $driver_bhatta_cost = $fetch_list_data['driver_bhatta_cost'];
            $driver_gst_type = getGSTTYPE($fetch_list_data['driver_gst_type'], 'label');
            $driver_early_morning_charges = $fetch_list_data['driver_early_morning_charges'];
            $driver_evening_charges = $fetch_list_data['driver_evening_charges'];
        endwhile;


        if ($driver_ID != '' && $driver_ID != 0 && $_GET['ROUTE'] == 'edit') :
            $basic_info_url = 'driver.php?route=edit&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=edit&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=edit&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=edit&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=edit&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Update & Continue";
        else :
            $basic_info_url = 'driver.php?route=add&formtype=driver_basic_info&id=' . $driver_ID;
            $driver_cost_url = 'driver.php?route=add&formtype=driver_cost&id=' . $driver_ID;
            $driver_upload_documents_url = 'driver.php?route=add&formtype=driver_upload_documents&id=' . $driver_ID;
            $driver_feedback_url = 'driver.php?route=add&formtype=driver_feedback&id=' . $driver_ID;
            $driver_create_preview_url = 'driver.php?route=add&formtype=driver_create_preview&id=' . $driver_ID;

            $btn_label = "Save & Continue";
        endif;
    ?>
        <div class="row">
            <div class="col-12">
                <div id="wizard-validation" class="bs-stepper mt-2">
                    <div class="bs-stepper-header border-0 justify-content-center py-2">
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $basic_info_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">1</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Basic Info</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_cost_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle disble-stepper-title">2</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Cost Details</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_upload_documents_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  disble-stepper-title">3</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Upload Document</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_feedback_url; ?>" class="step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle disble-stepper-title ">4</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title disble-stepper-title">Feedback & Review</h5>
                                </span>
                            </a>
                        </div>
                        <div class="line">
                            <i class="ti ti-chevron-right"></i>
                        </div>
                        <div class="step" data-target="#account-details-validation">
                            <a href="<?= $driver_create_preview_url; ?>" class=" step-trigger">
                                <span class="stepper_for_hotel  bs-stepper-circle  active-stepper">5</span>
                                <span class="bs-stepper-label mt-3 ">
                                    <h5 class="stepper_for_hotel bs-stepper-title">Preview</h5>
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
                    <div class="row">
                        <h4 class="text-primary">Basic Info</h4>
                        <?php if ($driver_profile_image != '') : ?>
                            <div class="col-md-3 rounded-circle">
                                <div class="wrapper mb-3">
                                    <img src="<?= BASEPATH; ?>uploads/driver_gallery/<?= $driver_profile_image; ?>"
                                        class="driver-profile">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="col-md-3">
                            <label>Vendor Name</label>
                            <?php if (!empty($vendor_name)) : ?>
                                <p class="text-light"><?= $vendor_name; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-3">
                            <label>Vehicle Type</label>
                            <?php if (!empty($vehicle_type)) : ?>
                                <p class="text-light"><?= $vehicle_type; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-3">
                            <label>Driver Name</label>
                            <?php if (!empty($driver_name)) : ?>
                                <p class="text-light"><?= $driver_name; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Date Of Birth</label>
                            <?php if (!empty($driver_date_of_birth)) : ?>
                                <p class="text-light"><?= $driver_date_of_birth; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Blood Group</label>
                            <?php if (!empty($driver_blood_group)) : ?>
                                <p class="text-light"><?= $driver_blood_group; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Gender</label>
                            <?php if (!empty($driver_gender)) : ?>
                                <p class="text-light"><?= $driver_gender; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Primary Mobile Number</label>
                            <?php if (!empty($driver_primary_mobile_number)) : ?>
                                <p class="text-light"><?= $driver_primary_mobile_number; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($driver_alternate_mobile_number)) : ?>
                            <div class="col-md-3">
                                <label>Alternative Mobile Number</label>
                                <?php if (!empty($driver_alternate_mobile_number)) : ?>
                                    <p class="text-light"><?= $driver_alternate_mobile_number; ?></p>
                                <?php else : ?>
                                    <p class="text-light">--</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($driver_email)) : ?>
                            <div class="col-md-3">
                                <label>Email ID</label>
                                <?php if (!empty($driver_email)) : ?>
                                    <p class="text-light"><?= $driver_email; ?></p>
                                <?php else : ?>
                                    <p class="text-light">--</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <label>Aadhar Card Number</label>
                            <?php if (!empty($driver_aadharcard_num)) : ?>
                                <p class="text-light"><?= $driver_aadharcard_num; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($driver_pan_card)) : ?>
                            <div class="col-md-3">
                                <label>Pan Card Number</label>
                                <?php if (!empty($driver_pan_card)) : ?>
                                    <p class="text-light"><?= $driver_pan_card; ?></p>
                                <?php else : ?>
                                    <p class="text-light">--</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <label>Voder ID Number</label>
                            <?php if (!empty($driver_voter_id_num)) : ?>
                                <p class="text-light"><?= $driver_voter_id_num; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>License Number</label>
                            <?php if (!empty($driver_license_number)) : ?>
                                <p class="text-light"><?= $driver_license_number; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>License Issue Date</label>
                            <?php if (!empty($driver_license_issue_date)) : ?>
                                <p class="text-light"><?= $driver_license_issue_date; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>License Expire Date</label>
                            <?php if (!empty($driver_license_expiry_date)) : ?>
                                <p class="text-light"><?= $driver_license_expiry_date; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Address</label>
                            <?php if (!empty($driver_address)) : ?>
                                <p class="text-light"><?= $driver_address; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="divider">
                        <div class="divider-text text-muted">
                            <i class="ti ti-star"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="text-primary">Cost Details</h4>

                        <div class="col-md-4">
                            <label>Driver Salary (₹)</label>
                            <?php if (!empty($driver_salary)) : ?>
                                <p class="text-light"><?= $driver_salary; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <!--   <div class="col-md-3">
                            <label>Food Cost ₹</label>
                            <?php if (!empty($driver_food_cost)) : ?>
                                <p class="text-light"><?= $driver_food_cost; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Accomdation ₹</label>
                            <?php if (!empty($driver_accomdation_cost)) : ?>
                                <p class="text-light"><?= $driver_accomdation_cost; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>Bhatta Cost ₹</label>
                            <?php if (!empty($driver_bhatta_cost)) : ?>
                                <p class="text-light"><?= $driver_bhatta_cost; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <label>GST %</label>
                            <?php if (!empty($driver_gst_type)) : ?>
                                <p class="text-light"><?= $driver_gst_type; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>-->
                        <div class="col-md-4">
                            <label>Early Morning Charges(₹) (Before 6 AM)</label>
                            <?php if (!empty($driver_early_morning_charges)) : ?>
                                <p class="text-light"><?= $driver_early_morning_charges; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <label>Evening Charges(₹)(After 6 PM)</label>
                            <?php if (!empty($driver_evening_charges)) : ?>
                                <p class="text-light"><?= $driver_evening_charges; ?></p>
                            <?php else : ?>
                                <p class="text-light">--</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="divider">
                        <div class="divider-text text-muted">
                            <i class="ti ti-star"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="text-primary">Document Upload</h4>
                        <?php $select_driverCREATEDPREVIEW_document_query = sqlQUERY_LABEL("SELECT `driver_document_details_id`, `driver_id`, `document_type`, `driver_document_name` FROM `dvi_driver_document_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
                        $total_driver_gallery_num_rows_count = sqlNUMOFROW_LABEL($select_driverCREATEDPREVIEW_document_query);
                        if ($total_driver_gallery_num_rows_count > 0) :
                            while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_driverCREATEDPREVIEW_document_query)) :
                                $driver_document_details_id = $fetch_list_data['driver_document_details_id'];
                                $document_type = getDOCUMENTTYPE($fetch_list_data['document_type'], 'label');
                                $driver_document_name = $fetch_list_data['driver_document_name'];
                        ?>
                                <div class="col-md-3">
                                    <div class="my-2">
                                        <label><?= $document_type; ?></label>
                                    </div>
                                    <img src="<?= BASEPATH; ?>uploads/driver_gallery/<?= $driver_document_name; ?>"
                                        onclick="openModal();currentSlide(<?= $driver_document_details_id; ?>)"
                                        class="room-details-shadow  cursor rounded" width="200px" height="120px">
                                </div>
                            <?php endwhile;
                        else :
                            ?>
                            <div class="onboarding-media">
                                <div class="row">
                                    <div class="text-center">
                                        <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px"
                                            height="112px" class="rounded">
                                        <p class="ms-2">No Gallery Found</p>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endif; ?>
                    </div>
                    <div class="divider">
                        <div class="divider-text text-muted">
                            <i class="ti ti-star"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="text-primary">Renewal History</h4>

                        <div class="card-datatable dataTable_select text-nowrap">
                            <div class="table-responsive">
                                <table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
                                    <thead class="table-head">
                                        <tr>
                                            <th>S.no</th>
                                            <th>License Number</th>
                                            <th>Validity Start Date</th>
                                            <th>Validity End Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_list = sqlQUERY_LABEL("SELECT `driver_license_renewal_log_ID`, `license_number`, `start_date`, `end_date` FROM `dvi_driver_license_renewal_log_details` WHERE `status` = '1' AND `deleted` = '0' AND `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                        $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                        if ($select_review_count > 0) :
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                $license_renewal_counter++;
                                                $driver_license_renewal_log_ID = $fetch_data['driver_license_renewal_log_ID'];
                                                $license_number = $fetch_data['license_number'];
                                                $start_date_formated = dateformat_datepicker($fetch_data['start_date']);
                                                $end_date_formated = dateformat_datepicker($fetch_data['end_date']);
                                                $end_date = $fetch_data['end_date'];
                                                $current_date = date('Y-m-d');
                                                if ($end_date == $currentDate) :

                                                    $driver_licence_status = "<span class='badge bg-label-danger me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Expires Today</span>";

                                                elseif ($end_date < $currentDate) :

                                                    $driver_licence_status = "<span class='badge bg-label-dark me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>In-Active</span>";

                                                else :

                                                    $driver_licence_status = "<span class='badge bg-label-success me-1 cursor-pointer' data-bs-toggle='tooltip' data-bs-placement='top' title='Expire date: $driver_license_expiry_date'>Active</span>";

                                                endif;
                                        ?>
                                                <tr>
                                                    <td><?= $license_renewal_counter; ?></td>
                                                    <td><?= $license_number; ?></td>
                                                    <td><?= $start_date_formated; ?></td>
                                                    <td><?= $end_date_formated; ?></td>
                                                    <td><?= $driver_licence_status; ?></td>
                                                </tr>
                                            <?php
                                            endwhile;
                                        else :
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No License History Found !!!</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="divider">
                        <div class="divider-text text-muted">
                            <i class="ti ti-star"></i>
                        </div>
                    </div>
                    <div class="row">
                        <h4 class="text-primary">Feedback & Review</h4>
                        <div class="card-datatable dataTable_select text-nowrap">
                            <div class="table-responsive">
                                <table id="hotel_review_LIST" class="table table-flush-spacing border table-bordered">
                                    <thead class="table-head">
                                        <tr>
                                            <th>S.no</th>
                                            <th>Rating</th>
                                            <th>Description</th>
                                            <th>Created On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $select_list = sqlQUERY_LABEL("SELECT `driver_review_id`, `driver_id`, `driver_rating`, `driver_description`, `createdon` FROM `dvi_driver_review_details` WHERE `deleted` = '0' AND `driver_id` = '$driver_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
                                        $select_review_count = sqlNUMOFROW_LABEL($select_list);
                                        if ($select_review_count > 0) :
                                            while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                $review_counter++;
                                                $driver_review_id = $fetch_data['driver_review_id'];
                                                $driver_id = $fetch_data['driver_id'];
                                                $driver_rating = $fetch_data['driver_rating'];
                                                $driver_description = $fetch_data['driver_description'];
                                                $createdon = $fetch_data['createdon'];
                                                $formatted_createdon_date = date('d/m/Y H:i:s', strtotime($createdon));
                                        ?>
                                                <tr>
                                                    <td><?= $review_counter; ?> </td>
                                                    <td><?= $driver_rating; ?> STAR</td>
                                                    <td><?= $driver_description; ?></td>
                                                    <td><?= $formatted_createdon_date; ?></td>
                                                </tr>
                                            <?php
                                            endwhile;
                                        else :
                                            ?>
                                            <tr>
                                                <td colspan="5" class="text-center">No Reviews Found !!!</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class=" text-center mt-5">
                            <a class="btn btn-primary float-end ms-2" href="driver.php" data-bs-dismiss=" modal">Confirm</a>
                            <a class="btn btn-light float-start" href="<?= $driver_feedback_url; ?>"
                                data-bs-dismiss=" modal">Back</a>
                        </div>
                    </div>
                </div>
            </div>
    <?php
    endif;
else :
    echo "Request Ignored";
endif;
