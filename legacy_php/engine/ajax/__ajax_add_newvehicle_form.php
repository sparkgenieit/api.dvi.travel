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

        $branch_ID = $_GET['ID'];
        $vendor_ID = $_GET['vendor_id'];
        $vehicle_ID = $_GET['vehicle_id'];



        if ($vehicle_ID != '' && $vehicle_ID != 0) :

            $select_vehicle_list_query = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_type_id`, `registration_number`, `registration_date`, `engine_number`, `owner_name`, `vehicle_name`, `fuel_type`, `model_name`, `chassis_number`, `insurance_policy_number`, `insurance_start_date`, `insurance_expiry_date`, `insurance_company_name`, `vehicle_fc_expiry_date`, `RTO_code`, `vehicle_RTO` FROM `dvi_vehicle` WHERE `deleted` = '0' and `vehicle_id` = '$vehicle_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_ROOM_LIST:" . sqlERROR_LABEL());

            $total_vehicle_list_num_rows_count = sqlNUMOFROW_LABEL($select_vehicle_list_query);
        endif;
        if ($total_vehicle_list_num_rows_count > 0) :
            while ($fetch_data = sqlFETCHARRAY_LABEL($select_vehicle_list_query)) :
                $vehicle_id =  $fetch_data['vehicle_id'];
                $vendor_id =  $fetch_data['vendor_id'];
                $vendor_branch_id =  $fetch_data['vendor_branch_id'];
                $vehicle_type_id =  $fetch_data['vehicle_type_id'];
                $registration_number =  $fetch_data['registration_number'];
                $registration_date =  $fetch_data['registration_date'];
                $engine_number =  $fetch_data['engine_number'];
                $owner_name =  $fetch_data['owner_name'];
                $vehicle_name =  $fetch_data['vehicle_name'];
                $fuel_type =  $fetch_data['fuel_type'];
                $model_name =  $fetch_data['model_name'];
                $chassis_number =  $fetch_data['chassis_number'];
                $insurance_policy_number =  $fetch_data['insurance_policy_number'];
                $insurance_start_date =  $fetch_data['insurance_start_date'];
                $insurance_expiry_date =  $fetch_data['insurance_expiry_date'];
                $insurance_company_name =  $fetch_data['insurance_company_name'];
                $vehicle_fc_expiry_date =  $fetch_data['vehicle_fc_expiry_date'];
                $RTO_code =  $fetch_data['RTO_code'];
                $vehicle_RTO =  $fetch_data['vehicle_RTO'];
            endwhile;
        endif;

?>

        <div class="row" id="basic_card">
            <div class="col-md-12">
                <div class="card-header pb-3 mt-2 d-flex justify-content-between align-items-center">
                    <div>
                        <!--<h5 class="card-title mb-3 mt-2">List of Vehicle</h5>
						<h5 class="card-title mb-3 mt-2 text-primary">Branch Name - <b><?= getVENDORANDVEHICLEDETAILS($branch_ID, 'get_vendorbranchname_from_vendorbranchid'); ?></b></h5>-->
                        <h5 class="card-title">Create Vehicle in Branch <b class="text-primary"><?= getVENDORANDVEHICLEDETAILS($branch_ID, 'get_vendorbranchname_from_vendorbranchid'); ?></b></h5>
                    </div>
                    <div>
                        <a href="javascript:void(0)" onclick="remove_choosen_vehicle_list(<?= $vendor_ID; ?>)" type="button" class="btn btn-secondary me-1">Back to Vehicle List</a>
                        <!--<a href="javascript:;" type="button" class="btn btn-label-primary waves-effect" onclick="showvehicleFORMSTEP1(<?= $branch_ID; ?>,<?= $vendor_id ?>,0)"><i class="ti ti-plus ti-xs me-1"></i>Add More </a>-->
                    </div>
                </div>
                <form id="form_vendor_branch_form" method="POST">
                    <div class="row g-3">
                        <h5 class="text-primary mt-3 mb-0">Basic Info</h5>
                        <div class="col-md-3">
                            <label class="form-label" for="modalAddCard">Vehicle Type<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <select class="form-control" name="vehicle_name" id="vehicle_name">
                                    <?= getVEHICLETYPE($vehicle_type_id, 'select');   ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="registration_number">Registration Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="Registration Number" value="<?= $registration_number; ?>" />
                            </div>
                        </div>
                        <div class="col-md-3 position-relative">
                            <label class="form-label" for="registration-date">Registration Date<span class="text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="registration_date" id="registration_date" class="form-control" placeholder="Registration Date" value="<?= $registration_date; ?>" />
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
                        <div class="col-md-3">
                            <label class="form-label" for="engine_number">Engine Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Engine Number" value="<?= $engine_number; ?>" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="owner_Name">Owner Name<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="owner_Name" id="owner_Name" class="form-control" placeholder="Owner Name" value="<?= $owner_name; ?>" />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="owner_contact_number">Owner Contact Number<span class=" text-danger"> *</span></label>
                            <input id="owner_contact_number" class="form-control" placeholder="Owner Contact Number" aria-label="Owner Contact Number" required />
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="email">Owner Email ID</label>
                            <input type="email" id="email" class="form-control" placeholder="Owner Email ID" aria-label="Owner Email ID" />
                        </div>
                        <div class="col-sm-3">
                            <label class="form-label" for="country">Owner Country<span class=" text-danger"> *</span></label>
                            <select class="form-select" name="country" id="country">
                                <?= getCOUNTRYLIST($vendor_country_id, 'select_country'); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="state">Owner State<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <select class="form-select" name="state" id="state" value="">
                                    <option value="">Choose State</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="city">Owner City<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <select class="form-select" name="city" id="city" value="">
                                    <option value="">Choose City</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="pincode">Owner Pincode<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="pincode" id="pincode" class="form-control" placeholder="Owner Pincode" value="" required />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="address">Owner Address<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <textarea id="vendor_address" rows="1" name="address" class="form-control" placeholder="Owner Address" required="">  </textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="chassis_number">Chassis Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="chassis_number" id="chassis_number" class="form-control" placeholder="Chassis Number" value="<?= $chassis_number; ?>" />
                            </div>
                        </div>
                        <div class="col-md-3 position-relative">
                            <label class="form-label" for="chassis_number">Vehicle Expiry Date (FC) <span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="fc_expiry_date" id="fc_expiry_date" class="form-control" placeholder="Vehicle Expiry Date (FC)" value="<?= $vehicle_fc_expiry_date; ?>" />
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
                        <div class="col-md-3">
                            <label class="form-label" for="fuel_type">Fuel Type<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <select class="form-control" name="fuel_type" id="fuel_type">
                                    <?= getfuelType($fuel_type, 'select');   ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="vendor_branch_gst">GST%<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <select id="vendor_branch_gst" class="form-control form-select">
                                    <option value="">Choose the GST%</option>
                                    <option value="1">20%</option>
                                    <option value="2">15%</option>
                                </select>
                            </div>
                        </div>

                        <div class="divider">
                            <div class="divider-text text-primary">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <h5 class="text-primary m-0">Insurance & FC Details</h5>
                        <div class="col-md-4">
                            <label class="form-label" for="insurance_policy_number">Insurance Policy Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_policy_number" id="insurance_policy_number" class="form-control" placeholder="Insurance Policy Number" value="<?= $insurance_policy_number; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4 position-relative">
                            <label class="form-label" for="insurance_start_date">Insurance Start Date<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_start_date" id="insurance_start_date" class="form-control" placeholder="Insurance Start Date" value="<?= $insurance_start_date; ?>" />
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
                        <div class="col-md-4 position-relative">
                            <label class="form-label" for="insurance_expiry_date">Insurance End Date<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_expiry_date" id="insurance_expiry_date" class="form-control" placeholder="Insurance End Date" value="<?= $insurance_expiry_date; ?>" />
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
                            <label class="form-label" for="insurance_company_number">Insurance Contact Number<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="insurance_company_number" id="insurance_company_number" class="form-control" placeholder="Insurance Contact Number" value="<?= $insurance_company_name; ?>" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="rto_code">RTO Code<span class=" text-danger"> *</span></label>
                            <div class="form-group">
                                <input type="text" name="rto_code" id="rto_code" class="form-control" placeholder="RTO Code" value="<?= $RTO_code; ?>" />
                            </div>
                        </div>
                        <!-- <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_id; ?>" />
                                <input type="hidden" name="branch_id" id="branch_id" value="<?= $vendor_branch_id; ?>" /> -->
                        <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?= $vehicle_ID; ?>" />


                        <input type="hidden" name="vendor_id" id="vendor_id" value="<?= $vendor_ID; ?>" />
                        <input type="hidden" name="branch_id" id="branch_id" value="<?= $branch_ID; ?>" />

                    </div>

                    <div class="row" id="upload_div">
                        <div class="divider">
                            <div class="divider-text text-primary">
                                <i class="ti ti-star"></i>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?php
                            $select_vehicle_gallery  = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`,`vehicle_id`,`image_type`,`vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' and `vehicle_id` = '$vehicle_id'  ORDER BY `vehicle_id` DESC LIMIT 0,1") or die("#1-collect_hotel_code_count: " . sqlERROR_LABEL());

                            $num_vehicle_document = sqlNUMOFROW_LABEL($select_vehicle_gallery);
                            ?>

                            <?php if ($num_vehicle_document == 0) : ?>
                                <div class="d-flex justify-content-between">
                                    <h5 class="text-primary m-0 mb-3">Image,Video & Document Uploads</h5>
                                    <button type="button" class="btn btn-label-primary" data-toggle="modal" data-target="#upload_document_modal">+ Upload File</button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="justify-content-center" id="file_upload">
                                            <div class="card-body bulk-import-body text-center p-5" id="uploadButtonContainer">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="100" version="1.1" viewBox="-23 0 512 512" width="100">
                                                    <g id="surface1">
                                                        <path d="M 337.953125 230.601562 C 404.113281 239.886719 455.015625 296.65625 455.015625 365.378906 C 455.015625 440.503906 394.082031 501.4375 318.957031 501.4375 C 267.3125 501.4375 222.277344 472.625 199.335938 430.152344 C 188.878906 410.839844 182.902344 388.75 182.902344 365.273438 C 182.902344 290.148438 243.835938 229.214844 318.957031 229.214844 C 325.363281 229.320312 331.660156 229.75 337.953125 230.601562 Z M 337.953125 230.601562 " style="stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;"></path>
                                                        <path d="M 337.953125 230.601562 C 331.765625 229.75 325.363281 229.320312 318.957031 229.320312 C 243.835938 229.320312 182.902344 290.253906 182.902344 365.378906 C 182.902344 388.855469 188.878906 410.945312 199.335938 430.257812 L 199.121094 430.367188 L 57.199219 430.367188 C 31.265625 430.367188 10.242188 409.34375 10.242188 383.414062 L 10.242188 57.730469 C 10.242188 31.800781 31.265625 10.777344 57.199219 10.777344 L 229.429688 10.777344 L 229.429688 88.464844 C 229.429688 108.523438 245.648438 124.746094 265.710938 124.746094 L 337.953125 124.746094 Z M 337.953125 230.601562 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;"></path>
                                                        <path d="M 229.429688 10.777344 L 337.953125 124.746094 L 265.710938 124.746094 C 245.648438 124.746094 229.429688 108.523438 229.429688 88.464844 Z M 229.429688 10.777344 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;"></path>
                                                        <path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.96875 347.664062 119.410156 345.851562 117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.199219 0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.199219 440.71875 L 193.148438 440.71875 C 219.609375 485.535156 267.203125 512 318.960938 512 C 399.847656 512 465.6875 446.265625 465.6875 365.273438 C 465.6875 329.632812 452.988281 295.375 429.511719 268.59375 C 408.277344 244.476562 379.890625 228.042969 348.945312 221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 240.097656 102.550781 240.097656 88.464844 L 240.097656 37.457031 Z M 21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.199219 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 L 327.605469 135.523438 L 327.605469 218.863281 C 324.402344 218.757812 321.839844 218.332031 319.066406 218.332031 C 281.824219 218.332031 247.570312 232.628906 221.746094 255.039062 L 86.222656 255.039062 C 80.355469 255.039062 75.550781 259.839844 75.550781 265.710938 C 75.550781 271.582031 80.351562 276.382812 86.222656 276.382812 L 201.898438 276.382812 C 194.320312 287.054688 188.023438 297.726562 183.117188 309.464844 L 86.222656 309.464844 C 80.355469 309.464844 75.550781 314.265625 75.550781 320.132812 C 75.550781 326.003906 80.351562 330.804688 86.222656 330.804688 L 176.179688 330.804688 C 173.511719 341.476562 172.125 353.320312 172.125 365.167969 C 172.125 383.839844 175.644531 402.300781 182.476562 419.375 L 57.199219 419.375 C 37.242188 419.375 21.34375 403.367188 21.34375 383.414062 Z M 318.960938 490.765625 C 272.96875 490.765625 230.601562 465.582031 208.621094 425.136719 C 198.695312 406.890625 193.46875 386.292969 193.46875 365.378906 C 193.46875 296.230469 249.703125 239.992188 318.851562 239.992188 C 324.722656 239.992188 330.589844 240.421875 336.351562 241.167969 C 366.019531 245.328125 393.335938 260.054688 413.183594 282.679688 C 433.246094 305.515625 444.238281 334.859375 444.238281 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625" style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000"></path>
                                                        <path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.355469 201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.355469 223.027344 86.222656 223.027344 Z M 86.222656 223.027344 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000"></path>
                                                        <path d="M 326.535156 286.625 C 324.507812 284.492188 321.734375 283.210938 318.746094 283.210938 C 315.757812 283.210938 312.984375 284.492188 310.957031 286.625 L 248.425781 353.746094 C 244.367188 358.015625 244.6875 364.84375 248.957031 368.792969 C 250.984375 370.714844 253.652344 371.675781 256.214844 371.675781 C 259.09375 371.675781 262.082031 370.5 264.21875 368.257812 L 308.394531 320.984375 L 308.394531 437.515625 C 308.394531 443.382812 313.199219 448.1875 319.066406 448.1875 C 324.9375 448.1875 329.738281 443.382812 329.738281 437.515625 L 329.738281 320.988281 L 373.597656 368.261719 C 377.652344 372.527344 384.269531 372.847656 388.644531 368.792969 C 392.910156 364.738281 393.125 358.015625 389.175781 353.746094 Z M 326.535156 286.625 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000"></path>
                                                    </g>
                                                </svg>

                                                <div class="mt-2">
                                                    <h5>No Documents Found</h5>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else :
                                $btn_next_step = 'skip&continue'; ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-primary mb-0">Image,Video & Document Upload</h5>
                                    <div><button type="button" class="btn btn-label-primary" data-toggle="modal" data-target="#upload_document_modal">+ Upload Again</button></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="justify-content-center" id="file_upload">
                                            <div class="card-body bulk-import-body text-center p-3" id="uploaddocumentContainer">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="text-primary">Images</h6>
                                                    </div>
                                                    <?php $select_vehicle_list = sqlQUERY_LABEL("SELECT  `owner_name` FROM `dvi_vehicle` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_DRIVER_DETAILS:" . sqlERROR_LABEL());

                                                    while ($fetch_driver_data = sqlFETCHARRAY_LABEL($select_vehicle_list)) :
                                                        $counter++;
                                                        $owner_name = $fetch_driver_data['owner_name'];
                                                    endwhile;

                                                    $select_list = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name`, `status` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `vehicle_id` = '1' ORDER BY 'image_type' ASC") or die("#1-UNABLE_TO_COLLECT_VEHICAL_COST_LIST:" . sqlERROR_LABEL());

                                                    $num_row_document = sqlNUMOFROW_LABEL($select_list);
                                                    if ($num_row_document > 0) :
                                                        $btn_next_step = "Save & Continue";
                                                        while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                            $counter++;
                                                            $vehicle_gallery_details_id = $fetch_data['vehicle_gallery_details_id'];
                                                            $vehicle_id = $fetch_data['vehicle_id'];
                                                            $image_type = $fetch_data['image_type'];
                                                            $vehicle_gallery_name = $fetch_data['vehicle_gallery_name'];
                                                    ?>

                                                            <div class="col-md-3  my-2" id="imageContainer">
                                                                <div class="my-2">
                                                                    <label>Interior</label>
                                                                </div>

                                                                <div style="position: relative;">
                                                                    <a href="uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" class="fs-6" download>
                                                                        <img id="galleryImage" src="uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" class="room-details-shadow cursor rounded" width="200px" height="120px">
                                                                    </a>
                                                                    <button class="vehicle-image-close" onclick="closeImage('imageContainer')">X</button>
                                                                </div>

                                                            </div>
                                                        <?php
                                                        endwhile;
                                                        ?>
                                                </div>
                                                <hr>

                                                <!-- video -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="text-primary mb-0">Video</h6>
                                                    </div>
                                                    <div class="col-md-3  my-2" id="videoContainer">
                                                        <div class="my-2">
                                                            <label>Video</label>
                                                        </div>

                                                        <div style="position: relative;">
                                                            <a href="javascript:;" class="fs-6" download>
                                                                <img src="assets/img/sample_video.mp4" class="room-details-shadow  cursor rounded" width="200px" height="120px">
                                                            </a>
                                                            <button class="vehicle-image-close" onclick="closeImage('videoContainer')">X</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h6 class="text-primary mb-0">Document</h6>
                                                    </div>
                                                    <div class="col-md-3  my-2" id="fileContainer">
                                                        <div class="my-2">
                                                            <label>Fc Document</label>
                                                        </div>
                                                        <div class="mt-4" style="position: relative;">
                                                            <a href="javascript:;" target="_blank" style="color: blue; text-decoration: none;">
                                                                <svg id="Capa_1" enable-background="new 0 0 510 510" height="42px" viewBox="0 0 510 510" width="42px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                    <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="157.153" x2="399.748" y1="198.847" y2="441.441">
                                                                        <stop offset="0" stop-color="#7faef4"></stop>
                                                                        <stop offset="1" stop-color="#4c8df1"></stop>
                                                                    </linearGradient>
                                                                    <linearGradient id="lg1">
                                                                        <stop offset="0" stop-color="#4c8df1" stop-opacity="0"></stop>
                                                                        <stop offset="1" stop-color="#4256ac"></stop>
                                                                    </linearGradient>
                                                                    <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="410.106" x2="371.606" xlink:href="#lg1" y1="173.728" y2="61.228"></linearGradient>
                                                                    <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="343.272" x2="387.993" y1="58.728" y2="103.45">
                                                                        <stop offset="0" stop-color="#a7c5fd"></stop>
                                                                        <stop offset="1" stop-color="#7faef4"></stop>
                                                                    </linearGradient>
                                                                    <linearGradient id="SVGID_4_" gradientTransform="matrix(-1 0 0 1 1574 0)" gradientUnits="userSpaceOnUse" x1="1319" x2="1319" xlink:href="#lg1" y1="463.7" y2="513.288"></linearGradient>
                                                                    <g>
                                                                        <path d="m68.17 31.88v446.25c0 17.529 14.341 31.87 31.87 31.87h309.91c17.534 0 31.88-14.346 31.88-31.88v-360.725c0-9.787-3.888-19.173-10.808-26.094l-80.493-80.493c-6.921-6.92-16.307-10.808-26.094-10.808h-224.385c-17.534 0-31.88 14.346-31.88 31.88z" fill="url(#SVGID_1_)"></path>
                                                                        <g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <path d="m153.111 246.041h203.778c5.079 0 9.196-4.117 9.196-9.196v-22.425c0-5.079-4.117-9.196-9.196-9.196h-203.778c-5.079 0-9.196 4.117-9.196 9.196v22.425c0 5.079 4.117 9.196 9.196 9.196z" fill="#ebeff0"></path>
                                                                                    </g>
                                                                                </g>
                                                                            </g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <path d="m153.111 309.756h203.778c5.079 0 9.196-4.117 9.196-9.196v-22.425c0-5.079-4.117-9.196-9.196-9.196h-203.778c-5.079 0-9.196 4.117-9.196 9.196v22.425c0 5.079 4.117 9.196 9.196 9.196z" fill="#ebeff0"></path>
                                                                                    </g>
                                                                                </g>
                                                                            </g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <path d="m153.111 373.47h203.778c5.079 0 9.196-4.117 9.196-9.196v-22.424c0-5.079-4.117-9.196-9.196-9.196h-203.778c-5.079 0-9.196 4.117-9.196 9.196v22.425c0 5.078 4.117 9.195 9.196 9.195z" fill="#ebeff0"></path>
                                                                                    </g>
                                                                                </g>
                                                                            </g>
                                                                            <g>
                                                                                <g>
                                                                                    <g>
                                                                                        <path d="m150.573 437.185h103.155c3.677 0 6.658-2.981 6.658-6.658v-27.5c0-3.677-2.981-6.658-6.658-6.658h-103.155c-3.677 0-6.658 2.981-6.658 6.658v27.5c0 3.677 2.981 6.658 6.658 6.658z" fill="#ebeff0"></path>
                                                                                    </g>
                                                                                </g>
                                                                            </g>
                                                                        </g>
                                                                        <path d="m350.528 10.808c-4.74-4.74-10.638-8.056-17.028-9.676v103.922l108.33 108.33v-95.99c0-9.787-3.888-19.173-10.808-26.094z" fill="url(#SVGID_2_)"></path>
                                                                        <path d="m440.737 108.443c.118.512.227 1.011.326 1.492h-97.648c-6.914 0-12.52-5.605-12.52-12.52v-96.834c.763.136 1.565.295 2.392.478 7.279 1.61 13.916 5.353 19.188 10.624l77.655 77.655c5.251 5.251 8.938 11.87 10.607 19.105z" fill="url(#SVGID_3_)"></path>
                                                                        <path d="m441.83 447.201v30.919c0 17.534-14.346 31.88-31.88 31.88h-309.91c-17.529 0-31.87-14.342-31.87-31.87v-30.929z" fill="url(#SVGID_4_)"></path>
                                                                    </g>
                                                                </svg></a>
                                                            <button class="vehicle-file-close" onclick="closeImage('fileContainer')">X</button>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endif;
                                                endif; ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-label-github waves-effect ps-3" onclick="remove_choosen_vehicle_list(<?= $vendor_ID; ?>)"><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-left me-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l14 0"></path>
                                <path d="M5 12l4 4"></path>
                                <path d="M5 12l4 -4"></path>
                            </svg>Back</button>
                        <button type="submit" id="submit_vendor_vehicle" class="btn btn-primary waves-effect waves-light pe-3">Save & Close<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l14 0"></path>
                                <path d="M15 16l4 -4"></path>
                                <path d="M15 8l4 4"></path>
                            </svg></button>

                    </div>
                </form>
                <div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content p-4">
                            <div class="modal-body receiving-subject-form-data"> <!-- Plugins css Ends-->
                                <form id="ajax_upload_document_form" enctype="multipart/form-data">
                                    <div class="modal-header pt-0 border-0">
                                        <h4 class="modal-title mx-auto" style="color:black">Document Upload</h4>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12 mb-3">
                                            <label class="form-label" for="formValidationUsername">Document Type<span class=" text-danger"> *</span></label>
                                            <div class="form-group">
                                                <select id="document_type" name="document_type" class="form-control" required>
                                                    <option value="">Choose the File Type</option>
                                                    <option value="1">Exterior</option>
                                                    <option value="2">Interior</option>
                                                    <option value="3">Video</option>
                                                    <option value="4">RC Document</option>
                                                    <option value="5">FC Document</option>
                                                    <option value="6">Vehicle Permit</option>
                                                    <option value="7">Insurance</option>
                                                    <option value="8">Others</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="formValidationUsername">Upload Document<span class=" text-danger"> *</span></label>
                                            <div class="form-group">
                                                <input type="file" class="input-file" id="fileInput" name="file">
                                            </div>
                                        </div>
                                        <input type="hidden" name="hidden_vehicle_gallery_details_id" id="hidden_vehicle_gallery_details_id" value="<?= $vehicle_gallery_details_id; ?>" />
                                        <input type="hidden" name="hidden_vehicle_ID" id="hidden_vehicle_ID" class="form-control" value="<?= $vehicle_id; ?>" />
                                        <input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" class="form-control" value="<?= $vendor_id; ?>" />
                                        <input type="hidden" name="hidden_branch_ID" id="hidden_branch_ID" class="form-control" value="<?= $branch_id; ?>" />
                                    </div>
                                    <div class="d-flex justify-content-between pt-4">
                                        <button type="button" class="btn btn-label-github waves-effect mx-1" data-dismiss="modal" aria-label="Close">Close</button>
                                        <button type="submit" id="submit_driver_upload_document_btn" class="btn btn-primary btn-md">
                                            <!-- <button type="button" class="btn btn-primary mx-1"> -->
                                            Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            </div>
        </div>
        <script>
            function closeImage(containerId) {
                var containerElement = document.getElementById(containerId);
                if (containerElement) {
                    // Remove the container element (which includes both the image and the close button)
                    containerElement.parentNode.removeChild(containerElement);
                }
            }

            flatpickr('#registration_date', {
                dateFormat: 'd-m-Y', // Change this format to your desired date format
                // Other options go here
            });

            flatpickr('#insurance_start_date', {
                dateFormat: 'd-m-Y', // Change this format to your desired date format
                // Other options go here
            });
            flatpickr('#insurance_expiry_date', {
                dateFormat: 'd-m-Y', // Change this format to your desired date format
                // Other options go here
            });
            flatpickr('#fc_expiry_date', {
                dateFormat: 'd-m-Y', // Change this format to your desired date format
                // Other options go here
            });
            $("#form_vendor_branch_form").submit(function(event) {

                var form = $('#form_vendor_branch_form')[0];

                var data = new FormData(form);

                $(this).find("button[id='submit_vendor_vehicle']").prop('disabled', true);

                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_vendor.php?type=vendor_vehicle',
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
                        //     //SUCCESS RESPOSNE
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
            $("#ajax_upload_document_form").submit(function(event) {
                var form = $('#ajax_upload_document_form')[0];
                var data = new FormData(form);


                $(this).find("button[id='submit_driver_upload_document_btn']").prop('disabled', true);
                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_vendor.php?type=vehicle_upload_document',
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
                        if (response.errros.document_type_required) {
                            TOAST_NOTIFICATION('warning', 'Document Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errros.driver_document_required) {
                            TOAST_NOTIFICATION('warning', 'Driver Document Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                        }
                    } else {
                        //SUCCESS RESPOSNE
                        if (response.i_result == true) {

                            location.assign(response.redirect_URL);

                            //SUCCESS RESPOSNE
                            // $("button[id='submit_driver_upload_document_btn']").prop('disabled', false);
                            // // $('#ajax_upload_document_form')[0].reset();
                            // // window.location.reload()
                            //   $('#ajax_upload_document_form').modal('hide');
                            // // upload_document_div(<?= $_GET['ID'] ?>);

                            // //   $.ajax({
                            // //     type: 'post',
                            // //     url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID=<?= $_GET['ID']; ?>',
                            // //     success: function(response) {
                            // //       $('#upload_document_image').html('');
                            // //       $('#upload_document_image').html(response);
                            // //     }
                            // //   });
                            // TOAST_NOTIFICATION('success', 'Upload Document Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

                        }
                        //else if (response.u_result == true) {
                        //     //RESULT SUCCESS
                        //     $("button[id='submit_driver_upload_document_btn']").prop('disabled', false);
                        //     $('#ajax_upload_document_form')[0].reset();
                        //     $('#upload_document_modal').modal('hide');
                        //     // upload_document_div(<?= $_GET['ID'] ?>);
                        //     $.ajax({
                        //         type: 'post',
                        //         url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID=<?= $_GET['ID']; ?>',
                        //         success: function(response) {
                        //             $('#upload_document_image').html('');
                        //             $('#upload_document_image').html(response);
                        //         }
                        //     });
                        //     TOAST_NOTIFICATION('success', 'Upload Document Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        // } else if (response.i_result == false) {
                        //     //RESULT FAILED
                        //     TOAST_NOTIFICATION('warning', 'Unable to Add Driver Upload Document Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        // } else if (response.u_result == false) {
                        //     //RESULT FAILED
                        //     TOAST_NOTIFICATION('warning', 'Unable to Add Driver Upload Document Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        // }
                    }
                    if (response == "OK") {
                        return true;
                    } else {
                        return false;
                    }
                });
                event.preventDefault();
            });
        </script>

    <?php
    elseif ($_GET['type'] == 'image_upload') :
        $vehicle_id = $_GET['ID'];
        $branch = $_GET['branch_id'];
        $vendor_id  = $_GET['vendor_id'];
        $branch_id = $_GET['branch_id'];
        $vehicle_gallery_details_id = $_GET['vehicle_gallerybranch_id_details_id'];



    ?>
        <div class="row" id="upload_div">
            <div class="col-md-12">
                <?php
                $select_vehicle_gallery  = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`,`vehicle_id`,`image_type`,`vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' and `vehicle_id` = '$vehicle_id'  ORDER BY `vehicle_id` DESC LIMIT 0,1") or die("#1-collect_hotel_code_count: " . sqlERROR_LABEL());

                $num_vehicle_document = sqlNUMOFROW_LABEL($select_vehicle_gallery);

                if ($num_vehicle_document == 0) :
                ?>
                    <div class="card p-4">
                        <h5 class="text-primary m-0 mb-3">Image & Video Upload</h5>
                        <div class="row">
                            <div class="col-12">

                                <div class="justify-content-center bulk-upload-body" id="file_upload">
                                    <div class="card-body bulk-import-body text-center p-5" id="uploadButtonContainer">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="150" version="1.1" viewBox="-23 0 512 512" width="150">
                                            <g id="surface1">
                                                <path d="M 337.953125 230.601562 C 404.113281 239.886719 455.015625 296.65625 455.015625 365.378906 C 455.015625 440.503906 394.082031 501.4375 318.957031 501.4375 C 267.3125 501.4375 222.277344 472.625 199.335938 430.152344 C 188.878906 410.839844 182.902344 388.75 182.902344 365.273438 C 182.902344 290.148438 243.835938 229.214844 318.957031 229.214844 C 325.363281 229.320312 331.660156 229.75 337.953125 230.601562 Z M 337.953125 230.601562 " style="stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;"></path>
                                                <path d="M 337.953125 230.601562 C 331.765625 229.75 325.363281 229.320312 318.957031 229.320312 C 243.835938 229.320312 182.902344 290.253906 182.902344 365.378906 C 182.902344 388.855469 188.878906 410.945312 199.335938 430.257812 L 199.121094 430.367188 L 57.199219 430.367188 C 31.265625 430.367188 10.242188 409.34375 10.242188 383.414062 L 10.242188 57.730469 C 10.242188 31.800781 31.265625 10.777344 57.199219 10.777344 L 229.429688 10.777344 L 229.429688 88.464844 C 229.429688 108.523438 245.648438 124.746094 265.710938 124.746094 L 337.953125 124.746094 Z M 337.953125 230.601562 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;"></path>
                                                <path d="M 229.429688 10.777344 L 337.953125 124.746094 L 265.710938 124.746094 C 245.648438 124.746094 229.429688 108.523438 229.429688 88.464844 Z M 229.429688 10.777344 " style=" stroke:none;fill-rule:nonzero;fill:#fff;fill-opacity:1;"></path>
                                                <path d="M 348.945312 221.640625 L 348.945312 124.746094 C 348.945312 121.96875 347.664062 119.410156 345.851562 117.382812 L 237.21875 3.308594 C 235.191406 1.175781 232.308594 0 229.429688 0 L 57.199219 0 C 25.398438 0 0 25.929688 0 57.730469 L 0 383.414062 C 0 415.214844 25.398438 440.71875 57.199219 440.71875 L 193.148438 440.71875 C 219.609375 485.535156 267.203125 512 318.960938 512 C 399.847656 512 465.6875 446.265625 465.6875 365.273438 C 465.6875 329.632812 452.988281 295.375 429.511719 268.59375 C 408.277344 244.476562 379.890625 228.042969 348.945312 221.640625 Z M 240.101562 37.457031 L 312.984375 114.179688 L 265.710938 114.179688 C 251.625 114.179688 240.097656 102.550781 240.097656 88.464844 L 240.097656 37.457031 Z M 21.34375 383.414062 L 21.34375 57.730469 C 21.34375 37.667969 37.242188 21.34375 57.199219 21.34375 L 218.757812 21.34375 L 218.757812 88.464844 C 218.757812 114.394531 239.78125 135.523438 265.710938 135.523438 L 327.605469 135.523438 L 327.605469 218.863281 C 324.402344 218.757812 321.839844 218.332031 319.066406 218.332031 C 281.824219 218.332031 247.570312 232.628906 221.746094 255.039062 L 86.222656 255.039062 C 80.355469 255.039062 75.550781 259.839844 75.550781 265.710938 C 75.550781 271.582031 80.351562 276.382812 86.222656 276.382812 L 201.898438 276.382812 C 194.320312 287.054688 188.023438 297.726562 183.117188 309.464844 L 86.222656 309.464844 C 80.355469 309.464844 75.550781 314.265625 75.550781 320.132812 C 75.550781 326.003906 80.351562 330.804688 86.222656 330.804688 L 176.179688 330.804688 C 173.511719 341.476562 172.125 353.320312 172.125 365.167969 C 172.125 383.839844 175.644531 402.300781 182.476562 419.375 L 57.199219 419.375 C 37.242188 419.375 21.34375 403.367188 21.34375 383.414062 Z M 318.960938 490.765625 C 272.96875 490.765625 230.601562 465.582031 208.621094 425.136719 C 198.695312 406.890625 193.46875 386.292969 193.46875 365.378906 C 193.46875 296.230469 249.703125 239.992188 318.851562 239.992188 C 324.722656 239.992188 330.589844 240.421875 336.351562 241.167969 C 366.019531 245.328125 393.335938 260.054688 413.183594 282.679688 C 433.246094 305.515625 444.238281 334.859375 444.238281 365.378906 C 444.34375 434.527344 388.109375 490.765625 318.960938 490.765625 Z M 318.960938 490.765625" style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000"></path>
                                                <path d="M 86.222656 223.027344 L 194.320312 223.027344 C 200.191406 223.027344 204.992188 218.222656 204.992188 212.355469 C 204.992188 206.484375 200.191406 201.683594 194.320312 201.683594 L 86.222656 201.683594 C 80.355469 201.683594 75.550781 206.484375 75.550781 212.355469 C 75.550781 218.222656 80.355469 223.027344 86.222656 223.027344 Z M 86.222656 223.027344 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000"></path>
                                                <path d="M 326.535156 286.625 C 324.507812 284.492188 321.734375 283.210938 318.746094 283.210938 C 315.757812 283.210938 312.984375 284.492188 310.957031 286.625 L 248.425781 353.746094 C 244.367188 358.015625 244.6875 364.84375 248.957031 368.792969 C 250.984375 370.714844 253.652344 371.675781 256.214844 371.675781 C 259.09375 371.675781 262.082031 370.5 264.21875 368.257812 L 308.394531 320.984375 L 308.394531 437.515625 C 308.394531 443.382812 313.199219 448.1875 319.066406 448.1875 C 324.9375 448.1875 329.738281 443.382812 329.738281 437.515625 L 329.738281 320.988281 L 373.597656 368.261719 C 377.652344 372.527344 384.269531 372.847656 388.644531 368.792969 C 392.910156 364.738281 393.125 358.015625 389.175781 353.746094 Z M 326.535156 286.625 " style="stroke:none;fill-rule:nonzero;fill-opacity:1;" fill="#f4f4f7" data-original="#000000"></path>
                                            </g>
                                        </svg>

                                        <div class="mt-2">
                                            <h5>No Documents Found</h5>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal">+ Upload File</button>
                                        </div>
                                    </div>
                                </div>
                            <?php else :
                            $btn_next_step = 'skip&continue'; ?>
                                <div class="justify-content-center bulk-upload-body" id="file_upload">

                                    <div class="card-body bulk-import-body text-center p-5" id="uploaddocumentContainer">
                                        <div class="row">
                                            <div class="d-flex justify-content-between">
                                                <h4>Uploaded Documents</h4>
                                                <div><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal">+ Upload Again</button></div>
                                            </div>
                                            <?php $select_vehicle_list = sqlQUERY_LABEL("SELECT  `owner_name` FROM `dvi_vehicle` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id'") or die("#1-UNABLE_TO_COLLECT_DRIVER_DETAILS:" . sqlERROR_LABEL());

                                            while ($fetch_driver_data = sqlFETCHARRAY_LABEL($select_vehicle_list)) :
                                                $counter++;
                                                $owner_name = $fetch_driver_data['owner_name'];
                                            endwhile;

                                            $select_list = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name`, `status` FROM `dvi_vehicle_gallery_details` WHERE `deleted` = '0' AND `vehicle_id` = '$vehicle_id' ORDER BY 'image_type' ASC") or die("#1-UNABLE_TO_COLLECT_VEHICAL_COST_LIST:" . sqlERROR_LABEL());

                                            $num_row_document = sqlNUMOFROW_LABEL($select_list);
                                            if ($num_row_document > 0) :
                                                $btn_next_step = "Save & Continue";
                                                while ($fetch_data = sqlFETCHARRAY_LABEL($select_list)) :
                                                    $counter++;
                                                    $vehicle_gallery_details_id = $fetch_data['vehicle_gallery_details_id'];
                                                    $vehicle_id = $fetch_data['vehicle_id'];
                                                    $image_type = $fetch_data['image_type'];
                                                    $vehicle_gallery_name = $fetch_data['vehicle_gallery_name'];
                                            ?>

                                                    <div class="col-md-3  my-2">
                                                        <div class="my-2">
                                                            <label><?= getDOCUMENTTYPE($document_type, 'label'); ?></label>
                                                        </div>

                                                        <a href="uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" class="fs-6" download>
                                                            <img src="uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" class="room-details-shadow  cursor rounded" width="200px" height="120px">
                                                        </a>
                                                    </div>



                                                    <!-- <div class="col-md-3  my-2">
                                                <div class="my-2">
                                                    <label><?= getDOCUMENTTYPE($document_type, 'label'); ?></label>
                                                </div>
                                               
                                                <a href="uploads/vehicle_gallery/<?= $vehicle_gallery_name; ?>" class="fs-6" download>
                                                    <img src="../head/uploads/vehicle_gallery/<?= $driver_document_name; ?>" class="room-details-shadow  cursor rounded" width="200px" height="120px">
                                                </a>
                                            </div> -->
                                                <?php
                                                endwhile;

                                                ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0 pt-2" id="file-upload2" style="display: none;">
                                    <div class="d-flex justify-content-between">
                                        <p>Uploaded Files</p>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal">
                                            + Upload Again
                                        </button>
                                    </div>
                                    <div id="uploadedFilesArea" class="mt-3">
                                        <div class="row" id="uploadedFileList"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 text-end">
                                <a href="vendor.php?route=add&formtype=branch_list&id=<?= $vendor_id; ?>" id="submit_vendor_vehicle" class="btn btn-primary waves-effect waves-light pe-3">Save & Close</a>
                                <a href="list_of_vehicle.php?id=<?= $branch_id ?>&vendor_id=<?= $vendor_id ?>" id="submit_vendor_vehicle" class="btn btn-primary waves-effect waves-light pe-3"><?= $btn_next_step ?><svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-narrow-right ms-1" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 12l14 0"></path>
                                        <path d="M15 16l4 -4"></path>
                                        <path d="M15 8l4 4"></path>
                                    </svg></a>

                            </div>
                    <?php endif;
                                        endif;
                    ?>
                        </div>
                    </div>
            </div>
        </div>
        <div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content p-4">
                    <div class="modal-body receiving-subject-form-data"> <!-- Plugins css Ends-->
                        <form id="ajax_upload_document_form" enctype="multipart/form-data">
                            <div class="modal-header pt-0 border-0">
                                <h4 class="modal-title mx-auto" style="color:black">Document Upload</h4>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 mb-3">
                                    <label class="form-label" for="formValidationUsername">Document Type<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <select id="document_type" name="document_type" class="form-control" required>
                                            <option value="">Choose the File Type</option>
                                            <option value="1">Exterior</option>
                                            <option value="2">Interior</option>
                                            <option value="3">Video</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="formValidationUsername">Upload Document<span class=" text-danger"> *</span></label>
                                    <div class="form-group">
                                        <input type="file" class="input-file" id="fileInput" name="file">
                                    </div>
                                </div>
                                <input type="hidden" name="hidden_vehicle_gallery_details_id" id="hidden_vehicle_gallery_details_id" value="<?= $vehicle_gallery_details_id; ?>" />
                                <input type="hidden" name="hidden_vehicle_ID" id="hidden_vehicle_ID" class="form-control" value="<?= $vehicle_id; ?>" />
                                <input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" class="form-control" value="<?= $vendor_id; ?>" />
                                <input type="hidden" name="hidden_branch_ID" id="hidden_branch_ID" class="form-control" value="<?= $branch_id; ?>" />
                            </div>
                            <div class="d-flex justify-content-between pt-4">
                                <button type="button" class="btn btn-label-github waves-effect mx-1" data-dismiss="modal" aria-label="Close">Close</button>
                                <button type="submit" id="submit_driver_upload_document_btn" class="btn btn-primary btn-md">
                                    <!-- <button type="button" class="btn btn-primary mx-1"> -->
                                    Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script>
            $("#ajax_upload_document_form").submit(function(event) {
                var form = $('#ajax_upload_document_form')[0];
                var data = new FormData(form);


                $(this).find("button[id='submit_driver_upload_document_btn']").prop('disabled', true);
                $.ajax({
                    type: "post",
                    url: 'engine/ajax/__ajax_manage_vendor.php?type=vehicle_upload_document',
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
                        if (response.errros.document_type_required) {
                            TOAST_NOTIFICATION('warning', 'Document Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                        } else if (response.errros.driver_document_required) {
                            TOAST_NOTIFICATION('warning', 'Driver Document Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
                        }
                    } else {
                        //SUCCESS RESPOSNE
                        if (response.i_result == true) {

                            location.assign(response.redirect_URL);

                            //SUCCESS RESPOSNE
                            // $("button[id='submit_driver_upload_document_btn']").prop('disabled', false);
                            // // $('#ajax_upload_document_form')[0].reset();
                            // // window.location.reload()
                            //   $('#ajax_upload_document_form').modal('hide');
                            // // upload_document_div(<?= $_GET['ID'] ?>);

                            // //   $.ajax({
                            // //     type: 'post',
                            // //     url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID=<?= $_GET['ID']; ?>',
                            // //     success: function(response) {
                            // //       $('#upload_document_image').html('');
                            // //       $('#upload_document_image').html(response);
                            // //     }
                            // //   });
                            // TOAST_NOTIFICATION('success', 'Upload Document Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

                        }
                        //else if (response.u_result == true) {
                        //     //RESULT SUCCESS
                        //     $("button[id='submit_driver_upload_document_btn']").prop('disabled', false);
                        //     $('#ajax_upload_document_form')[0].reset();
                        //     $('#upload_document_modal').modal('hide');
                        //     // upload_document_div(<?= $_GET['ID'] ?>);
                        //     $.ajax({
                        //         type: 'post',
                        //         url: 'engine/ajax/__ajax_driver_upload_document.php?type=show_image&driver_ID=<?= $_GET['ID']; ?>',
                        //         success: function(response) {
                        //             $('#upload_document_image').html('');
                        //             $('#upload_document_image').html(response);
                        //         }
                        //     });
                        //     TOAST_NOTIFICATION('success', 'Upload Document Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        // } else if (response.i_result == false) {
                        //     //RESULT FAILED
                        //     TOAST_NOTIFICATION('warning', 'Unable to Add Driver Upload Document Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        // } else if (response.u_result == false) {
                        //     //RESULT FAILED
                        //     TOAST_NOTIFICATION('warning', 'Unable to Add Driver Upload Document Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
                        // }
                    }
                    if (response == "OK") {
                        return true;
                    } else {
                        return false;
                    }
                });
                event.preventDefault();
            });
        </script>
<?php
    endif;
endif;

?>