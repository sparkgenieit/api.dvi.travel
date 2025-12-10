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
$vehicle_id = $_POST['vehicle_id'];
$vendor_id = $_POST['vendor_id'];

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) : //CHECK AJAX REQUEST

    if ($_GET['type'] == 'vendor_vehicle' && $vehicle_id != '') :

        $select_vendor_branch = sqlQUERY_LABEL("SELECT `vehicle_id`, `vendor_id`, `vendor_branch_id`, `vehicle_type_id`, `registration_number`, `registration_date`, `engine_number`, `owner_name`, `owner_contact_no`, `owner_email_id`, `owner_country`, `owner_state`, `owner_city`, `owner_pincode`, `owner_address`, `chassis_number`, `vehicle_fc_expiry_date`, `fuel_type`, `insurance_policy_number`, `insurance_start_date`, `insurance_end_date`, `insurance_contact_no`, `RTO_code`,`vehicle_location_id` FROM `dvi_vehicle` WHERE `vendor_id`= '$vendor_id' AND `vehicle_id`= '$vehicle_id' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
        while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) :
            $vehicle_id = $fetch_data['vehicle_id'];
            $vendor_id = $fetch_data['vendor_id'];
            $vehicle_name = getVEHICLETYPE($fetch_data['vehicle_type_id'], 'get_vehicle_type_title');
            $vendor_branch_id = $fetch_data['vendor_branch_id'];
            $registration_number = $fetch_data['registration_number'];
            $registration_date = date('d/m/Y', strtotime($fetch_data['registration_date']));
            $engine_number = $fetch_data['engine_number'];
            $owner_name = $fetch_data['owner_name'];
            $owner_contact_no = $fetch_data['owner_contact_no'];
            $owner_email_id = $fetch_data['owner_email_id'];
            $owner_country = getCOUNTRYLIST($fetch_data['owner_country'], 'country_label');
            $vehicle_orign = getSTOREDLOCATIONDETAILS($fetch_data['vehicle_location_id'], 'SOURCE_LOCATION');
            // $owner_state = getSTATELIST('', $fetch_data['owner_state'], 'state_label');
            // $owner_city = getCITYLIST('', $fetch_data['owner_city'], 'city_label');
            $owner_state = $fetch_data['owner_state'];
            $owner_city = $fetch_data['owner_city'];

            $owner_pincode = $fetch_data['owner_pincode'];
            $owner_address = $fetch_data['owner_address'];
            $chassis_number = $fetch_data['chassis_number'];
            $vehicle_fc_expiry_date = date('d/m/Y', strtotime($fetch_data['vehicle_fc_expiry_date']));
            $fuel_type = $fetch_data['fuel_type'];
            $insurance_policy_number = $fetch_data['insurance_policy_number'];
            $insurance_start_date = date('d/m/Y', strtotime($fetch_data['insurance_start_date']));
            $insurance_end_date = date('d/m/Y', strtotime($fetch_data['insurance_end_date']));
            $insurance_contact_no = $fetch_data['insurance_contact_no'];
            $RTO_code = $fetch_data['RTO_code'];
        endwhile;
?>
        <div class="row mt-3">
            <div class="col-md-3">
                <label>Vehicle Type</label>
                <p class="text-light"><?= $vehicle_name ?></p>
            </div>
            <div class="col-md-3">
                <label>Branch Name</label>
                <p class="text-light">
                    <?= getBranchLIST($vendor_branch_id, 'branch_label'); ?>
                </p>
            </div>
            <!--<div class="col-md-3">
                <label>Vehicle Code</label>
                <p class="text-light">
                    
                </p>
            </div>-->
            <div class="col-md-3">
                <label>Registration Number</label>
                <p class="text-light"><?= $registration_number ?></p>
            </div>
            <div class="col-md-3">
                <label>Registration Date</label>
                <p class="text-light"><?= $registration_date ?></p>
            </div>
            <div class="col-md-3">
                <label>Engine Number</label>
                <p class="text-light"><?= $engine_number ?></p>
            </div>
            <div class="col-md-3">
                <label>Owner Name</label>
                <p class="text-light"><?= $owner_name ?></p>
            </div>
            <div class="col-md-3">
                <label>Owner Contact Number</label>
                <p class="text-light"><?= $owner_contact_no ?></p>
            </div>
            <?php if ($owner_email_id != '') : ?>
                <div class="col-md-3">
                    <label>Owner Email ID</label>
                    <p class="text-light"><?= $owner_email_id ?></p>
                </div>
            <?php endif; ?>
            <div class="col-md-3">
                <label>Owner Country</label>
                <p class="text-light"><?= $owner_country ?></p>
            </div>

            <div class="col-md-3">
                <label>vehicle Origin</label>
                <p class="text-light"><?= $vehicle_orign ?></p>
            </div>
            <div class="col-md-3">
                <label> State</label>
                <p class="text-light"><?= $owner_state ?></p>
            </div>
            <div class="col-md-3">
                <label> City</label>
                <p class="text-light"><?= $owner_city ?></p>
            </div>
            <div class="col-md-3">
                <label> Pincode</label>
                <p class="text-light"><?= $owner_pincode ?></p>
            </div>
            <div class="col-md-3">
                <label>Owner Address</label>
                <p class="text-light"><?= $owner_address ?></p>
            </div>
            <div class="col-md-3">
                <label>Chassis Number</label>
                <p class="text-light"><?= $chassis_number ?></p>
            </div>
            <div class="col-md-3">
                <label>Vehicle Expiry Date</label>
                <p class="text-light"><?= $vehicle_fc_expiry_date ?></p>
            </div>
            <div class="col-md-3">
                <label>Fuel Type</label>
                <p class="text-light"><?= getfuelType($fuel_type, 'label'); ?></p>
            </div>
            <div class="col-md-3">
                <label>Insurance Policy Number</label>
                <p class="text-light"><?= $insurance_policy_number ?></p>
            </div>
            <div class="col-md-3">
                <label>Insurance Start Date</label>
                <p class="text-light"><?= $insurance_start_date ?></p>
            </div>
            <div class="col-md-3">
                <label>Insurance End Date</label>
                <p class="text-light"><?= $insurance_end_date ?></p>
            </div>
            <div class="col-md-3">
                <label>Insurance Contact Number</label>
                <p class="text-light"><?= $insurance_contact_no; ?></p>
            </div>
            <div class="col-md-3">
                <label>RTO Code</label>
                <p class="text-light"><?= $RTO_code ?></p>
            </div>
        </div>

        <?php
        $select_vehicle_gallery_image_branch = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `vehicle_id`= '$vehicle_id' AND `deleted` = '0' AND (`image_type`='7' OR `image_type`='8' )") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
        $num_of_row_vehicle_gallery_image = sqlNUMOFROW_LABEL($select_vehicle_gallery_image_branch); ?>
        <div class="divider">
            <div class="divider-text text-secondary">
                <i class="ti ti-star"></i>
            </div>
        </div>
        <div class="row mt-2">
            <div>
                <h5 class="text-primary">Images</h5>
            </div>
            <?php
            if ($num_of_row_vehicle_gallery_image > 0) :
                while ($fetch_vehicle_gallery_image_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_image_branch)) :
                    $vehicle_gallery_details_id = $fetch_vehicle_gallery_image_data['vehicle_gallery_details_id'];
                    $vehicle_id = $fetch_vehicle_gallery_image_data['vehicle_id'];
                    $image_type = $fetch_vehicle_gallery_image_data['image_type'];
                    $vehicle_gallery_name = $fetch_vehicle_gallery_image_data['vehicle_gallery_name'];

                    // Extract the file extension from the filename
                    $fileExtension = pathinfo($vehicle_gallery_name, PATHINFO_EXTENSION);

                    // Convert the file extension to lowercase for case-insensitive comparison
                    $fileExtension = strtolower($fileExtension);

                    // Initialize a variable to store the file type, preview HTML, and download link
                    $fileType = '';
                    $previewHtml = '';
                    $downloadLink = '';

                    // Check the file type based on the extension
                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $fileType = 'Image file';
                        $previewHtml = '<img src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
                        $fileType = 'Video file';
                        $previewHtml = '<video width="320" height="240" controls class="d-block w-px-100 h-px-100 rounded">
										  <source src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" type="video/mp4">
										  Your browser does not support the video tag.
									   </video>';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } elseif (in_array($fileExtension, ['doc', 'docx', 'pdf'])) {
                        $fileType = 'Document file';
                        $previewHtml = '<iframe src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" width="600" height="400" frameborder="0" class="d-block w-px-100 h-px-100 rounded"></iframe>';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } else {
                        $fileType = 'Other file type';
                        $previewHtml = '<img src="assets/img/uploaded_file.png" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    }
            ?>
                    <div class="col-md-2">
                        <label><?= getVEHICLEDOCUMENTTYPE($image_type, 'label'); ?></span></label>
                        <div class="vendor-vehicle-image-container">
                            <div>
                                <?= $previewHtml; ?>
                            </div>
                            <div class="vendor-vehicle-download-button" onclick="downloadImage('<?= $downloadLink; ?>')"><i class="ti ti-download ti-sm"></i></div>
                        </div>
                    </div>
                <?php endwhile;
            else : ?>
                <div class="col-md-12 text-center">
                    <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                    <p class="ms-2 fw-bold mt-2 text-secondary">No Image Uploaded...!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php
        $select_vehicle_gallery_video_branch = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `vehicle_id`= '$vehicle_id' AND `deleted` = '0' AND `image_type`='9'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
        $num_of_row_vehicle_gallery_video = sqlNUMOFROW_LABEL($select_vehicle_gallery_video_branch); ?>
        <div class="divider">
            <div class="divider-text text-secondary">
                <i class="ti ti-star"></i>
            </div>
        </div>
        <div class="row mt-2">
            <div>
                <h5 class="text-primary">Video</h5>
            </div>
            <?php
            if ($num_of_row_vehicle_gallery_video > 0) :
                while ($fetch_vehicle_gallery_video_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_video_branch)) :
                    $vehicle_gallery_details_id = $fetch_vehicle_gallery_video_data['vehicle_gallery_details_id'];
                    $vehicle_id = $fetch_vehicle_gallery_video_data['vehicle_id'];
                    $image_type = $fetch_vehicle_gallery_video_data['image_type'];
                    $vehicle_gallery_name = $fetch_vehicle_gallery_video_data['vehicle_gallery_name'];

                    // Extract the file extension from the filename
                    $fileExtension = pathinfo($vehicle_gallery_name, PATHINFO_EXTENSION);

                    // Convert the file extension to lowercase for case-insensitive comparison
                    $fileExtension = strtolower($fileExtension);

                    // Initialize a variable to store the file type, preview HTML, and download link
                    $fileType = '';
                    $previewHtml = '';
                    $downloadLink = '';

                    // Check the file type based on the extension
                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $fileType = 'Image file';
                        $previewHtml = '<img src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
                        $fileType = 'Video file';
                        $previewHtml = '<video width="320" height="240" controls class="d-block w-px-100 h-px-100 rounded">
										  <source src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" type="video/mp4">
										  Your browser does not support the video tag.
									   </video>';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } elseif (in_array($fileExtension, ['doc', 'docx', 'pdf'])) {
                        $fileType = 'Document file';
                        $previewHtml = '<iframe src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" width="600" height="400" frameborder="0" class="d-block w-px-100 h-px-100 rounded"></iframe>';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } else {
                        $fileType = 'Other file type';
                        $previewHtml = '<img src="assets/img/uploaded_file.png" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    }
            ?>
                    <div class="col-md-2">
                        <label><?= getVEHICLEDOCUMENTTYPE($image_type, 'label'); ?></span></label>
                        <div class="vendor-vehicle-image-container">
                            <div>
                                <?= $previewHtml; ?>
                            </div>
                            <div class="vendor-vehicle-download-button" onclick="downloadImage('<?= $downloadLink; ?>')"><i class="ti ti-download ti-sm"></i></div>
                        </div>
                    </div>
                <?php endwhile;
            else : ?>
                <div class="col-md-12 text-center">
                    <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                    <p class="ms-2 fw-bold mt-2 text-secondary">No Video Uploaded...!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php
        $select_vehicle_gallery_document_branch = sqlQUERY_LABEL("SELECT VEHICLE_GALLERY.`vehicle_gallery_details_id`, VEHICLE_GALLERY.`vehicle_id`, VEHICLE_GALLERY.`image_type`, VEHICLE_GALLERY.`vehicle_gallery_name` FROM `dvi_vehicle` AS VEHICLE LEFT JOIN `dvi_vehicle_gallery_details` AS VEHICLE_GALLERY ON VEHICLE.`vehicle_id` = VEHICLE_GALLERY.`vehicle_id` WHERE VEHICLE_GALLERY.`vehicle_id`= '$vehicle_id' AND VEHICLE_GALLERY.`deleted` = '0' AND VEHICLE.`vendor_id`= '$vendor_id' AND VEHICLE.`vendor_branch_id` = '$vendor_branch_id' AND (VEHICLE_GALLERY.`image_type`='1' OR VEHICLE_GALLERY.`image_type`='2' OR VEHICLE_GALLERY.`image_type`='3' OR VEHICLE_GALLERY.`image_type`='4' OR VEHICLE_GALLERY.`image_type`='5' OR VEHICLE_GALLERY.`image_type`='6')") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
        $num_of_row_vehicle_gallery_document = sqlNUMOFROW_LABEL($select_vehicle_gallery_document_branch); ?>
        <div class="divider">
            <div class="divider-text text-secondary">
                <i class="ti ti-star"></i>
            </div>
        </div>
        <div class="row mt-2">
            <div>
                <h5 class="text-primary">Document</h5>
            </div>
            <?php
            if ($num_of_row_vehicle_gallery_document > 0) :
                while ($fetch_vehicle_gallery_document_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_document_branch)) :
                    $vehicle_gallery_details_id = $fetch_vehicle_gallery_document_data['vehicle_gallery_details_id'];
                    $vehicle_id = $fetch_vehicle_gallery_document_data['vehicle_id'];
                    $image_type = $fetch_vehicle_gallery_document_data['image_type'];
                    $vehicle_gallery_name = $fetch_vehicle_gallery_document_data['vehicle_gallery_name'];

                    // Extract the file extension from the filename
                    $fileExtension = pathinfo($vehicle_gallery_name, PATHINFO_EXTENSION);

                    // Convert the file extension to lowercase for case-insensitive comparison
                    $fileExtension = strtolower($fileExtension);

                    // Initialize a variable to store the file type, preview HTML, and download link
                    $fileType = '';
                    $previewHtml = '';
                    $downloadLink = '';

                    // Check the file type based on the extension
                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $fileType = 'Image file';
                        $previewHtml = '<img src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } elseif (in_array($fileExtension, ['mp4', 'avi', 'mov'])) {
                        $fileType = 'Video file';
                        $previewHtml = '<video width="320" height="240" controls class="d-block w-px-100 h-px-100 rounded">
										  <source src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" type="video/mp4">
										  Your browser does not support the video tag.
									   </video>';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } elseif (in_array($fileExtension, ['doc', 'docx', 'pdf'])) {
                        $fileType = 'Document file';
                        $previewHtml = '<iframe src="uploads/vehicle_gallery/' . $vehicle_gallery_name . '" width="600" height="400" frameborder="0" class="d-block w-px-100 h-px-100 rounded"></iframe>';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    } else {
                        $fileType = 'Other file type';
                        $previewHtml = '<img src="assets/img/uploaded_file.png" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
                        $downloadLink = 'uploads/vehicle_gallery/' . $vehicle_gallery_name;
                    }
            ?>
                    <div class="col-md-2">
                        <label><?= getVEHICLEDOCUMENTTYPE($image_type, 'label'); ?></label>
                        <div class="vendor-vehicle-image-container">
                            <div>
                                <?= $previewHtml; ?>
                            </div>
                            <div class="vendor-vehicle-download-button" onclick="downloadImage('<?= $downloadLink; ?>')"><i class="ti ti-download ti-sm"></i></div>
                        </div>
                    </div>
                <?php endwhile;
            else : ?>
                <div class="col-md-12 text-center">
                    <img src="<?= BASEPATH; ?>/assets/img/dummy/no-preview.png" alt="" width="157px" height="112px" class="rounded">
                    <p class="ms-2 fw-bold mt-2 text-secondary">No Document Uploaded...!</p>
                </div>
            <?php endif; ?>
        </div>

        <!--<div class="divider">
            <div class="divider-text text-secondary">
                <i class="ti ti-star"></i>
            </div>
        </div>
        <div class="row mt-2">
            <div>
                <h5 class="text-primary">Document</h5>
            </div>
            <div class="col-md-2">
                <h6>Fc Document</h6>
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
                </svg>
            </div>
            <div class="col-md-2">
                <h6>Insurance</h6>
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
                </svg>
            </div>
        </div>-->
        <script>
            function show_VEHICLE_GALLERY(vehicle_ID, image_type) {
                $('.receiving-swiper-room-form-data').load('engine/ajax/__ajax_vehicle_preview.php?type=show_vehicle_gallery&ID=' + vehicle_ID + '&image_type=' + image_type, function() {
                    const container = document.getElementById("showSWIPERGALLERYMODAL");
                    const modal = new bootstrap.Modal(container);
                    modal.show();
                });
            }

            function downloadImage(imageUrl) {
                // Extract the filename from the imageUrl
                var filename = imageUrl.split('/').pop();

                // Create an anchor element
                var link = document.createElement('a');

                // Set the href attribute to the image URL
                link.href = imageUrl;

                // Set the download attribute to specify the default file name
                link.download = filename;

                // Append the link to the document
                document.body.appendChild(link);

                // Trigger a click on the link to start the download
                link.click();

                // Remove the link from the document
                document.body.removeChild(link);
            }
        </script>
    <?php
    else :
    ?>
        <!-- echo "No Record is Found"; -->
        <p class="text-center">No Record is Found</p>
<?php
    endif;
else :
    echo "Request Ignored";
endif;
