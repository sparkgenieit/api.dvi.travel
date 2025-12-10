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

	if ($_GET['type'] == 'add_vehicle') :

		$BRANCH_ID = $_POST['BRANCH_ID'];
		$VENDOR_ID = $_POST['VENDOR_ID'];
		$VEHICLE_ID = $_POST['VEHICLE_ID'];
		$ROUTE = $_POST['ROUTE'];

		if ($VEHICLE_ID != '' && $VEHICLE_ID != 0) :
			$select_vendor_branch = sqlQUERY_LABEL("SELECT  `vehicle_type_id`, `vehicle_location_id`,`registration_number`, `registration_date`, `engine_number`, `owner_name`, `owner_contact_no`, `owner_email_id`, `owner_country`, `owner_state`, `owner_city`, `owner_pincode`, `owner_address`, `chassis_number`, `vehicle_fc_expiry_date`, `fuel_type`,`extra_km_charge`, `insurance_policy_number`, `insurance_start_date`, `insurance_end_date`, `insurance_contact_no`, `RTO_code`, `early_morning_charges`, `evening_charges`,`vehicle_video_url` FROM `dvi_vehicle` WHERE `vendor_id`= '$VENDOR_ID' AND `vendor_branch_id`= '$BRANCH_ID' AND `vehicle_id`= '$VEHICLE_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
			while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) :
				$vehicle_type_id = $fetch_data['vehicle_type_id'];
				$registration_number = $fetch_data['registration_number'];
				$registration_date = date('d/m/Y', strtotime($fetch_data['registration_date']));
				$engine_number = $fetch_data['engine_number'];
				$owner_name = $fetch_data['owner_name'];
				$owner_contact_no = $fetch_data['owner_contact_no'];
				$owner_email_id = $fetch_data['owner_email_id'];
				$owner_country = $fetch_data['owner_country'];
				$owner_state = $fetch_data['owner_state'];
				$owner_city = $fetch_data['owner_city'];
				//$owner_state = getSTATELIST('', $owner_state_id, 'state_label');
				//$owner_city = getCITYLIST('', $fetch_data['owner_city'], 'city_label');
				$owner_pincode = $fetch_data['owner_pincode'];
				$owner_address = $fetch_data['owner_address'];
				$chassis_number = $fetch_data['chassis_number'];
				$vehicle_fc_expiry_date = date('d/m/Y', strtotime($fetch_data['vehicle_fc_expiry_date']));
				$fuel_type = $fetch_data['fuel_type'];
				$vehicle_location_id = $fetch_data['vehicle_location_id'];
				$vehicle_orign = getSTOREDLOCATIONDETAILS($vehicle_location_id, 'SOURCE_LOCATION');
				$extra_km_charge = $fetch_data['extra_km_charge'];
				$insurance_policy_number = $fetch_data['insurance_policy_number'];
				$insurance_start_date = date('d/m/Y', strtotime($fetch_data['insurance_start_date']));
				$insurance_end_date = date('d/m/Y', strtotime($fetch_data['insurance_end_date']));
				$insurance_contact_no = $fetch_data['insurance_contact_no'];
				$RTO_code = $fetch_data['RTO_code'];
				$early_morning_charges = $fetch_data['early_morning_charges'];
				$evening_charges = $fetch_data['evening_charges'];
				$vehicle_video_url = $fetch_data['vehicle_video_url'];
			endwhile;
			$vehicle_form_title = "Update";
		else :
			$vehicle_form_title = "Add";
		endif;
?>

		<div class="row" id="basic_card">
			<div class="col-md-12">
				<form id="form_add_vehicle" method="POST" data-parsley-validate>

					<div class="d-flex justify-content-between align-items-center mb-3">
						<h5 class="card-title"><?= $vehicle_form_title; ?> Vehicle for <b class="text-primary"><?= getVENDORANDVEHICLEDETAILS($BRANCH_ID, 'get_vendorbranchname_from_vendorbranchid'); ?></b></h5>
						<button type="button" class="btn btn-secondary" onclick="showVEHICLELIST();">Back to list
						</button>
					</div>

					<div class="row g-3">
						<h5 class="text-primary mt-3 mb-0">Vehicle Basic Info</h5>
						<div class="col-md-4">
							<label class="form-label" for="vehicle_type_id">Vehicle Type<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<select class="form-select" name="vehicle_type_id" id="vehicle_type_id">
									<?= //getVEHICLETYPE($vehicle_type_id, 'select');
									getVENDOR_VEHICLE_TYPES($VENDOR_ID, $vehicle_type_id, 'select'); ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="registration_number">Registration Number<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="registration_number" id="registration_number" class="form-control" placeholder="Registration Number" value="<?= $registration_number; ?>" required data-parsley-check_registration_number data-parsley-check_registration_number-message="Entered Registration Number Already Exists" required data-parsley-pattern="^[A-Z]{2}\s?[0-9]{1,2}\s?[A-Z]{1,2}\s?[0-9]{1,4}\s?[A-Z]{0,1}[0-9]{0,4}$" />

								<input type="hidden" name="old_registration_number" id="old_registration_number" value="<?= $registration_number; ?>" />
							</div>
						</div>
						<div class="col-md-4 position-relative">
							<label class="form-label" for="registration_date">Registration Date<span class="text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="registration_date" id="registration_date" class="form-control" placeholder="Registration Date" value="<?= $registration_date; ?>" required />
								<!-- <span class="calender-icon">
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
								</span> -->
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="engine_number">Engine Number<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="engine_number" id="engine_number" class="form-control" placeholder="Engine Number" value="<?= $engine_number; ?>" required data-parsley-check_engine_number data-parsley-check_engine_number-message="Entered Engine Number Already Exists" />
								<input type="hidden" name="old_engine_number" id="old_engine_number" value="<?= $engine_number; ?>" />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="owner_name">Owner Name<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Owner Name" value="<?= $owner_name; ?>" required />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="owner_contact_no">Owner Contact Number<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="owner_contact_no" id="owner_contact_no" class="form-control" placeholder="Owner Contact Number" value="<?= $owner_contact_no; ?>" required data-parsley-pattern="^[0-9]{10}$" />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="owner_email_id">Owner Email ID</label>
							<div class="form-group">
								<input type="email" data-parsley-type="email" name="owner_email_id" id="owner_email_id" class="form-control" placeholder="Owner Email ID" value="<?= $owner_email_id; ?>" />
							</div>
						</div>
						<!--
						<div class="col-md-4">
							<label class="form-label" for="owner_country">Owner Country<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<select class="form-select" name="owner_country" id="owner_country" onchange="CHOOSEN_COUNTRY_OWNER();" data-parsley-trigger="keyup" data-parsley-errors-container="#vehicle_country_error_container">
									<?= getCOUNTRYLIST($owner_country, 'select_country'); ?>
								</select>
							</div>
							<div id="vehicle_country_error_container"></div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="owner_state">Owner State<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<select class="form-select" name="owner_state" id="owner_state" value="<?= $owner_state; ?>" onchange="CHOOSEN_STATE_OWNER();" data-parsley-trigger="keyup" data-parsley-errors-container="#owner_state_error_container">
									<?php if ($VEHICLE_ID != '' && $VEHICLE_ID != 0) :
										getSTATELIST($owner_country, $owner_state, 'select_state');
									else : ?>
										<option value="">Choose State</option>
									<?php endif; ?>
								</select>
							</div>
							<div id="owner_state_error_container"></div>
						</div>
						<div class="col-md-4">

							<label class="form-label" for="owner_city">Owner City<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<select class="form-select" name="owner_city" id="owner_city" value="<?= $owner_city; ?>" data-parsley-trigger="keyup" data-parsley-errors-container="#owner_city_error_container">
									<?php if ($VEHICLE_ID != '' && $VEHICLE_ID != 0) :
										getCITYLIST($owner_state_id, $owner_city, 'select_city');
									else : ?>
										<option value="">Choose City</option>
									<?php endif; ?>
								</select>
							</div>
							<div id="owner_city_error_container"></div>
						</div>-->

						<div class="col-md-4">
							<label class="form-label" for="owner_address">Owner Address<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<textarea id="owner_address" name="owner_address" class="form-control" rows="1" placeholder="Owner Address" required=""><?= $owner_address; ?></textarea>
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label" for="owner_pincode">Owner Pincode<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="owner_pincode" id="owner_pincode" value="<?= $owner_pincode; ?>" class="form-control" placeholder="Owner Pincode" required />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="owner_country">Country<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<select class="form-select" name="owner_country" id="owner_country" onchange="CHOOSEN_COUNTRY_OWNER();" data-parsley-trigger="keyup" data-parsley-errors-container="#vehicle_country_error_container">
									<?= getCOUNTRYLIST($owner_country, 'select_country'); ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="chassis_number">Vehicle Origin <span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="vehicle_orign" id="vehicle_orign" class="form-control" placeholder="Choose Vehicle Origin" value="<?= $vehicle_orign ?>" required />
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label" for="owner_state">State<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="owner_state" id="owner_state" class="form-control" placeholder="State" value="<?= $owner_state ?>" required readonly />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="owner_city">City<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="owner_city" id="owner_city" class="form-control" placeholder="City" value="<?= $owner_city ?>" required readonly />
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label" for="chassis_number">Chassis Number<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="chassis_number" id="chassis_number" class="form-control" placeholder="Chassis Number" value="<?= $chassis_number; ?>" required data-parsley-check_chassis_number data-parsley-check_chassis_number-message="Entered Chassis Number Already Exists" />
								<input type="hidden" name="old_chassis_number" id="old_chassis_number" value="<?= $chassis_number; ?>" />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="vehicle_fc_expiry_date">Vehicle Expiry Date <span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="vehicle_fc_expiry_date" id="vehicle_fc_expiry_date" class="form-control" placeholder="Vehicle Expiry Date" value="<?= $vehicle_fc_expiry_date; ?>" required />
								<!-- <span class="calender-icon">
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
								</span> -->
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="fuel_type">Fuel Type<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<select class="form-control" name="fuel_type" id="fuel_type">
									<?= getfuelType($fuel_type, 'select');   ?>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="chassis_number">Extra KM Charge (₹)<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="extra_km_charge" id="extra_km_charge" class="form-control" placeholder="Extra KM Charge" value="<?= $extra_km_charge; ?>" required data-parsley-type="number" data-parsley-pattern="^\d+(\.\d{1,2})?$" />
							</div>
						</div>

						<div class="col-md-4">
							<label class="form-label" for="early_morning_charges">Early Morning Charges (₹)(Before 6 AM)<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="early_morning_charges" id="early_morning_charges" class="form-control" placeholder="Early Morning Charges" value="<?= $early_morning_charges; ?>" required data-parsley-type="number" data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" autocomplete="off" />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="evening_charges">Evening Charges (₹)(After 8 PM)<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="evening_charges" id="evening_charges" class="form-control" placeholder="Evening Charges" value="<?= $evening_charges; ?>" required data-parsley-type="number" data-parsley-error-message="Please enter valid price" data-parsley-trigger="keyup" autocomplete="off" />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="vehicle_video_url">Vehicle Video URL<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="vehicle_video_url" id="vehicle_video_url" class="form-control" placeholder="Enter Video url" value="<?= $vehicle_video_url; ?>" required autocomplete="off" />
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
								<input type="text" name="insurance_policy_number" id="insurance_policy_number" class="form-control" placeholder="Insurance Policy Number" value="<?= $insurance_policy_number; ?>" required data-parsley-check_insurance_policy_number data-parsley-check_insurance_policy_number-message="Entered Insurance Policy Number Already Exists" />
								<input type="hidden" name="old_insurance_policy_number" id="old_insurance_policy_number" value="<?= $insurance_policy_number; ?>" />
							</div>
						</div>
						<div class="col-md-4 position-relative">
							<label class="form-label" for="insurance_start_date">Insurance Start Date<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="insurance_start_date" id="insurance_start_date" class="form-control" placeholder="Insurance Start Date" value="<?= $insurance_start_date; ?>" required />
								<!-- <span class="calender-icon">
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
								</span> -->
							</div>
						</div>
						<div class="col-md-4 position-relative">
							<label class="form-label" for="insurance_end_date">Insurance End Date<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="insurance_end_date" id="insurance_end_date" class="form-control" placeholder="Insurance End Date" value="<?= $insurance_end_date; ?>" required />
								<!-- <span class="calender-icon">
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
								</span> -->
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="insurance_contact_no">Insurance Contact Number<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="insurance_contact_no" id="insurance_contact_no" class="form-control" placeholder="Insurance Contact Number" value="<?= $insurance_contact_no; ?>" required />
							</div>
						</div>
						<div class="col-md-4">
							<label class="form-label" for="rto_code">RTO Code<span class=" text-danger"> *</span></label>
							<div class="form-group">
								<input type="text" name="rto_code" id="rto_code" class="form-control" placeholder="RTO Code" value="<?= $RTO_code; ?>" required />
							</div>
						</div>

						<div class="divider">
							<div class="divider-text text-primary">
								<i class="ti ti-star"></i>
							</div>
						</div>

						<div class="col-md-12">
							<h5 class="text-primary m-0 mb-3">Upload</h5>
							<div class="row">
								<div class="col-12">
									<?php
									$select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `vehicle_id`= '$VEHICLE_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
									$num_of_row_vehicle_gallery = sqlNUMOFROW_LABEL($select_vehicle_gallery_branch);
									if ($num_of_row_vehicle_gallery > 0) : ?>
										<div class="justify-content-center bulk-upload-body" id="file_upload" style="display: none;">
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
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal" onclick="showUPLOADFILEMODAL();">+ Upload File</button>
												</div>
											</div>
										</div>
										<div class="card-body p-0 pt-2" id="file-upload2">
											<div class="d-flex justify-content-between">
												<p>Uploaded Files</p>
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal" onclick="showUPLOADFILEMODAL();">
													+ Upload Again
												</button>
											</div>
											<div id="uploadedFilesArea" class="mt-3">
												<div class="row" id="uploadedFileList">
													<?php
													while ($fetch_vehicle_gallery_data = sqlFETCHARRAY_LABEL($select_vehicle_gallery_branch)) :
														$vehicle_counter++;
														$vehicle_gallery_details_id = $fetch_vehicle_gallery_data['vehicle_gallery_details_id'];
														$vehicle_id = $fetch_vehicle_gallery_data['vehicle_id'];
														$image_type = $fetch_vehicle_gallery_data['image_type'];
														$vehicle_gallery_name = $fetch_vehicle_gallery_data['vehicle_gallery_name'];

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

														<div class="col-md-3 mb-3" id="vehicle_col_gallery_<?= $vehicle_counter; ?>">
															<div class="p-2 rounded position-relative alert alert-primary">
																<button type="button" class="btn btn-sm btn-icon waves-effect waves-light  position-absolute rounded-circle" style="top: -10px;right: -10px;" onclick="removeVEHICLEGALLERY(<?= $vehicle_gallery_details_id; ?>, <?= $vehicle_id; ?>, 'vehicle_col_gallery_<?= $vehicle_counter; ?>')">
																	<span class="ti ti-square-rounded-x-filled ti-lg"></span>
																</button>
																<h6><?= getVEHICLEDOCUMENTTYPE($image_type, 'label'); ?></h6>
																<div class="text-center">
																	<div class="vendor-vehicle-image-container">
																		<div>
																			<?= $previewHtml; ?>
																		</div>
																		<div class="vendor-vehicle-download-button" onclick="downloadImage('<?= $downloadLink; ?>')"><i class="ti ti-download ti-sm"></i></div>
																	</div>
																</div>
																<div class="text-center mt-2">
																	<p class="card-text mb-0 text-center"><?= $vehicle_gallery_name; ?></p>
																</div>
															</div>
														</div>
													<?php endwhile; ?>
												</div>
											</div>
										</div>
									<?php else : ?>
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
													<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal" onclick="showUPLOADFILEMODAL();">+ Upload File</button>
												</div>
											</div>
										</div>
										<div class="card-body p-0 pt-2" id="file-upload2" style="display: none;">
											<div class="d-flex justify-content-between">
												<p>Uploaded Files</p>
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload_document_modal" onclick="showUPLOADFILEMODAL();">
													+ Upload Again
												</button>
											</div>
											<div id="uploadedFilesArea" class="mt-3">
												<div class="row" id="uploadedFileList"></div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>

						<input type="hidden" name="vehicle_id" id="vehicle_id" value="<?= $VEHICLE_ID; ?>" />
						<input type="hidden" name="vendor_id" id="vendor_id" value="<?= $VENDOR_ID; ?>" />
						<input type="hidden" name="branch_id" id="branch_id" value="<?= $BRANCH_ID; ?>" />
					</div>
					<div class="d-flex justify-content-between mt-4">
						<button type="button" class="btn btn-secondary" onclick="showVEHICLELIST();">Back</button>
						<?php
						if ($VEHICLE_ID != '') :
							$value = 'Update';
						else :
							$value = 'Save';
						endif;
						?>
						<button type="submit" id="submit_vendor_vehicle" class="btn btn-primary waves-effect waves-light pe-3"><?= $value; ?></button>

					</div>
				</form>
			</div>
		</div>

		<div class="modal fade" id="upload_document_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content p-4">
					<div class="modal-body receiving-upload-file-data"> <!-- Plugins css Ends-->
						<form id="ajax_upload_document_form" enctype="multipart/form-data" method="POST" data-parsley-validate>
							<div class="modal-header pt-0 border-0">
								<h4 class="modal-title mx-auto" style="color:black">Document Upload</h4>
							</div>
							<div class="row mt-2">
								<div class="col-12 mb-3">
									<label class="form-label" for="document_type">Document Type<span class=" text-danger"> *</span></label>
									<div class="form-group">
										<select id="document_type" name="document_type" class="form-select" data-parsley-trigger="keyup" data-parsley-errors-container="#document_type_error_container"><?= getVEHICLEDOCUMENTTYPE('', 'select'); ?></select>
									</div>
									<div id="document_type_error_container"></div>
								</div>
								<div class="col-12">
									<label class="form-label" for="upload_document">Upload Document<span class=" text-danger"> *</span></label>
									<div class="form-group">
										<input type="file" class="input-file" id="fileInput" name="upload_document">
									</div>
								</div>
								<input type="hidden" name="hidden_vehicle_gallery_details_id" id="hidden_vehicle_gallery_details_id" class="form-control" value="" />
								<input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" class="form-control" value="<?= $VENDOR_ID; ?>" />
								<input type="hidden" name="hidden_branch_ID" id="hidden_branch_ID" class="form-control" value="<?= $BRANCH_ID; ?>" />
								<input type="hidden" name="hidden_vehicle_ID" id="hidden_vehicle_ID" class="form-control" value="<?= $VEHICLE_ID; ?>" />
							</div>
							<div class="d-flex justify-content-center pt-4">
								<button type="button" class="btn btn-label-github waves-effect mx-1" data-bs-dismiss="modal" aria-label="Close">Close</button>
								<button type="button" id="submit_upload_document_vehicle_btn" class="btn btn-primary btn-md">
									<!-- <button type="button" class="btn btn-primary mx-1"> -->
									Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
		<script src="assets/js/parsley.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
		<script src="assets/js/selectize/selectize.min.js"></script>

		<script>
			function getSTATE_CITY_COUNTRY() {
				var vehicle_orign = $('#vehicle_orign').val();
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_get_stored_location_details.php?type=GET_LOCATION_DETAILS',
					data: {
						vehicle_orign: vehicle_orign,
					},
					dataType: 'json',
					success: function(response) {
						$('#owner_state').val(response.source_location_state);
						$('#owner_city').val(response.source_location_city);
					}
				});
			}

			$(document).ready(function() {
				$(".form-select").selectize();

				$("#owner_country").attr("required", true);
				$("#owner_state").attr("required", true);
				$("#owner_city").attr("required", true);
				$("#vehicle_type_id").attr("required", true);
				$("#fuel_type").attr("required", true);


				var vehicle_orign = {
					url: function(phrase) {
						return "engine/json/__JSONsearchsourcelocation.php?phrase=" + encodeURIComponent(
								phrase) +
							"&format=json&type=source";
					},
					getValue: "get_source_location",
					list: {
						match: {
							enabled: true
						},
						onChooseEvent: function() {
							getSTATE_CITY_COUNTRY();
						},
						hideOnEmptyPhrase: true
					},
					theme: "square"
				};
				$("#vehicle_orign").easyAutocomplete(vehicle_orign);


				// Initialize Flatpickr
				flatpickr('#registration_date', {
					dateFormat: 'd-m-Y', // Change this format to your desired date format
					// Other options go here
				});

				flatpickr('#insurance_start_date', {
					dateFormat: 'd-m-Y', // Change this format to your desired date format
					// Other options go here
					onChange: function(selectedDates, dateStr) {
						// Set minimum date for end date based on the selected start date
						endDatePicker.set('minDate', dateStr);
					}
				});

				const endDatePicker = flatpickr('#insurance_end_date', {
					dateFormat: 'd-m-Y', // Change this format to your desired date format
					// Other options go here
				});

				flatpickr('#vehicle_fc_expiry_date', {
					dateFormat: 'd-m-Y', // Change this format to your desired date format
					// Other options go here
				});

				//CHECK DUPLICATE REGISTRATION NUMBER
				$('#registration_number').parsley();
				var old_registration_number_DETAIL = document.getElementById("old_registration_number").value;
				var registration_number = $('#registration_number').val();
				window.ParsleyValidator.addValidator('check_registration_number', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_vehicle_duplication.php',
							method: "POST",
							data: {
								type: "registration_number",
								registration_number: value,
								old_registration_number: old_registration_number_DETAIL,
								VENDOR_ID: '<?= $VENDOR_ID; ?>'
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				//CHECK DUPLICATE ENGINE NUMBER
				$('#engine_number').parsley();
				var old_engine_number_DETAIL = document.getElementById("old_engine_number").value;
				var engine_number = $('#engine_number').val();
				window.ParsleyValidator.addValidator('check_engine_number', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_vehicle_duplication.php',
							method: "POST",
							data: {
								type: "engine_number",
								engine_number: value,
								old_engine_number: old_engine_number_DETAIL,
								VENDOR_ID: '<?= $VENDOR_ID; ?>'
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				//CHECK DUPLICATE CHASSIS NUMBER
				$('#chassis_number').parsley();
				var old_chassis_number_DETAIL = document.getElementById("old_chassis_number").value;
				var chassis_number = $('#chassis_number').val();
				window.ParsleyValidator.addValidator('check_chassis_number', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_vehicle_duplication.php',
							method: "POST",
							data: {
								type: "chassis_number",
								chassis_number: value,
								old_chassis_number: old_chassis_number_DETAIL,
								VENDOR_ID: '<?= $VENDOR_ID; ?>'
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				//CHECK DUPLICATE INSURANCE POLICY NUMBER
				$('#insurance_policy_number').parsley();
				var old_insurance_policy_number_DETAIL = document.getElementById("old_insurance_policy_number").value;
				var insurance_policy_number = $('#insurance_policy_number').val();
				window.ParsleyValidator.addValidator('check_insurance_policy_number', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_vehicle_duplication.php',
							method: "POST",
							data: {
								type: "insurance_policy_number",
								insurance_policy_number: value,
								old_insurance_policy_number: old_insurance_policy_number_DETAIL,
								VENDOR_ID: '<?= $VENDOR_ID; ?>'
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				const uploadedFileList = $('#uploadedFileList');
				let selectedFiles = [];

				// Initially, hide the download links
				$('.download-link').hide();
				$('#submit_upload_document_vehicle_btn').click(function() {
					const newlySelectedDocumentType = $('#document_type').val();
					const newlySelectedFiles = $('#fileInput').prop('files');

					if (newlySelectedDocumentType == 0) {
						TOAST_NOTIFICATION('warning', 'Document Type is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
					}

					if (newlySelectedFiles.length > 0) {
						// Get the newly selected files and add them to the list
						//selectedFiles = selectedFiles.concat(Array.from(newlySelectedFiles));
						displayUploadedFiles(newlySelectedDocumentType, newlySelectedFiles);

						// Hide the file_upload card and show the file_upload2 card
						$('#file_upload').hide();
						$('#file-upload2').show();

						// Close the modal
						$('#fileUploadModal').modal('hide');
					} else {
						TOAST_NOTIFICATION('warning', 'Upload Document is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
					}
				});
				//AJAX FORM SUBMIT
				$("#form_add_vehicle").submit(function(event) {
					var form = $('#form_add_vehicle')[0];
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
							if (response.errros.vehicle_type_id_required) {
								TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.registration_number_required) {
								TOAST_NOTIFICATION('warning', 'Registration Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.registration_date_required) {
								TOAST_NOTIFICATION('warning', 'Registration Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.engine_number_required) {
								TOAST_NOTIFICATION('warning', 'Engine Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_name_required) {
								TOAST_NOTIFICATION('warning', 'Owner Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_contact_no_required) {
								TOAST_NOTIFICATION('warning', 'Owner Contact Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_email_id_required) {
								TOAST_NOTIFICATION('warning', 'Owner Email ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_country_required) {
								TOAST_NOTIFICATION('warning', 'Owner Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_state_required) {
								TOAST_NOTIFICATION('warning', 'Owner State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_city_required) {
								TOAST_NOTIFICATION('warning', 'Owner City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_pincode_required) {
								TOAST_NOTIFICATION('warning', 'Owner Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.owner_address_required) {
								TOAST_NOTIFICATION('warning', 'Owner Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.chassis_number_required) {
								TOAST_NOTIFICATION('warning', 'Chassis Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.vehicle_fc_expiry_date_required) {
								TOAST_NOTIFICATION('warning', 'Vehicle FC Expiry Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.fuel_type_required) {
								TOAST_NOTIFICATION('warning', 'Fuel Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.insurance_policy_number_required) {
								TOAST_NOTIFICATION('warning', 'Insurance Policy Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.insurance_start_date_required) {
								TOAST_NOTIFICATION('warning', 'Insurance Start Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.insurance_end_date_required) {
								TOAST_NOTIFICATION('warning', 'Insurance End Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.insurance_contact_no_required) {
								TOAST_NOTIFICATION('warning', 'Insurance Contact Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.rto_code_required) {
								TOAST_NOTIFICATION('warning', 'RTO Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.vendor_id_required) {
								TOAST_NOTIFICATION('warning', 'Vendor ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errros.branch_id_required) {
								TOAST_NOTIFICATION('warning', 'Branch ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							if (response.i_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Vehicle Details Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								showvendorFORMSTEP3('<?= $VENDOR_ID; ?>', '<?= $ROUTE; ?>', 'vehicle_info');

								/*$('#show_add_vehicle').hide();
								$('#vehicle_list_without_add_form').show();
								$('#list_vehicle_details').show();
                                $('#vehicle_LIST').ajax.reload();*/
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Vehicle Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

								showvendorFORMSTEP3('<?= $VENDOR_ID; ?>', '<?= $ROUTE; ?>', 'vehicle_info');

							} else if (response.i_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to Add Vehicle Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to Update Vehicle Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

				/*$("#ajax_upload_document_form").submit(function(event) {
					var form = $('#ajax_upload_document_form')[0];
					var data = new FormData(form);

					$(this).find("button[id='submit_upload_document_vehicle_btn']").prop('disabled', true);
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
							} else if (response.errros.upload_document_required) {
								TOAST_NOTIFICATION('warning', 'Upload Document Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								
								TOAST_NOTIFICATION('success', 'Document Created Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							}
						}
						if (response == "OK") {
							return true;
						} else {
							return false;
						}
					});
					event.preventDefault();
				});*/

			});

			var counter_image_for_id;
			<?php
			$select_vehicle_gallery_branch = sqlQUERY_LABEL("SELECT `vehicle_gallery_details_id`, `vehicle_id`, `image_type`, `vehicle_gallery_name` FROM `dvi_vehicle_gallery_details` WHERE `vehicle_id`= '$VEHICLE_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_VEHICLE_GALLERY_LIST:" . sqlERROR_LABEL());
			$counter_of_row_vehicle_gallery = sqlNUMOFROW_LABEL($select_vehicle_gallery_branch);
			if ($counter_of_row_vehicle_gallery > 0) :
			?>
				counter_image_for_id = <?= $counter_of_row_vehicle_gallery + 1; ?>;
			<?php else : ?>
				counter_image_for_id = '0';
			<?php endif; ?>
			var counter_image = '0';

			function getFileType(filename) {
				var fileExtension = filename.split('.').pop().toLowerCase();

				if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
					return 'image';
				} else if (['mp4', 'avi', 'mov'].includes(fileExtension)) {
					return 'video';
				} else if (['doc', 'docx', 'pdf'].includes(fileExtension)) {
					return 'document';
				} else {
					return 'other';
				}
			}

			function displayUploadedFiles(selectedDocumentType, selectedFiles) {
				const uploadedFileList = $('#uploadedFileList');

				// Display all selected files with download icons in rows with three columns
				for (let i = 0; i < selectedFiles.length; i++) {
					const file = selectedFiles[i];
					const fileName = file.name;
					const modifiedFileName = fileName;
					var fileType = getFileType(fileName);
					const selectedDocumentTypeText = $('#document_type').text();

					// Create a new FileReader for each file
					var reader = new FileReader();

					// Use a closure to capture the file in the loop
					reader.onload = (function(file) {
						return function(e) {
							let image_src = e.target.result;
							let vehicle_col_gallery_counter_image = 'vehicle_col_gallery_' + counter_image_for_id;

							var previewHtml = '';
							var downloadLink = image_src; // Replace with your actual download link

							if (fileType === 'image') {
								previewHtml = '<img src="' + image_src + '" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
							} else if (fileType === 'video') {
								previewHtml = '<video width="320" height="240" controls class="d-block w-px-100 h-px-100 rounded">' +
									'<source src="' + image_src + '" type="video/mp4">' +
									'Your browser does not support the video tag.' +
									'</video>';
							} else if (fileType === 'document') {
								previewHtml = '<iframe src="' + image_src + '" width="600" height="400" frameborder="0" class="d-block w-px-100 h-px-100 rounded"></iframe>';
							} else {
								previewHtml = '<img src="assets/img/uploaded_file.png" alt="Image Preview" class="d-block w-px-100 h-px-100 rounded">';
							}

							const fileItemFinal = $(`
							<div class="col-md-3 mb-3" id=${vehicle_col_gallery_counter_image}>
								<div class="p-2 rounded position-relative alert alert-primary">
									<button type="button" class="btn btn-sm btn-icon waves-effect waves-light  position-absolute rounded-circle" style="top: -10px;right: -10px;" onclick="removeInsertedVEHICLEGALLERY('${vehicle_col_gallery_counter_image}')">
										<span class="ti ti-square-rounded-x-filled ti-lg"></span>
									</button>
									<h6>${selectedDocumentTypeText}</h6>
									<div class="text-center">
										<div class="vendor-vehicle-image-container">
											${previewHtml}
											<div class="vendor-vehicle-download-button" onclick="downloadImage('${image_src}')"><i class="ti ti-download ti-sm"></i></div>
											<input type="text" name="document_type_id[${counter_image}]" id="document_type_id" value="${selectedDocumentType}" hidden="" />
											<input type="file" name="vehicle_gallery[${counter_image}]" id="uploadDocument_${counter_image}" hidden="" />
										</div>
									</div>
									<div class="text-center mt-2">
										<p class="card-text mb-0 text-center"> ${modifiedFileName} </p>
									</div>
								</div>
							</div>`);

							uploadedFileList.append(fileItemFinal);

							// Access the input element dynamically
							var input2 = document.getElementById('uploadDocument_' + counter_image);

							// Create a new File object from the selected file
							var newFile = new File([file], file.name, {
								type: file.type
							});

							// Create a new DataTransfer object
							var dataTransfer = new DataTransfer();

							// Add the new file to the DataTransfer object
							dataTransfer.items.add(newFile);

							// Set the DataTransfer object to the second input
							input2.files = dataTransfer.files;
						};
					})(file);

					// Read the selected image file as a data URL
					reader.readAsDataURL(file);
					($('#document_type')[0].selectize).clear();
					document.getElementById('fileInput').value = '';
					counter_image++;
				}
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


			function showUPLOADFILEMODAL() {
				//$('.receiving-upload-file-data').load('engine/ajax/__ajax_manage_activity.php?type=activity_delete', function() {

				// });
				const container = document.getElementById("upload_document_modal");
				const modal = new bootstrap.Modal(container);
				modal.show();
				$("#document_type").attr("required", true);
			}

			function CHOOSEN_COUNTRY_OWNER() {
				var state_selectize = $("#owner_state")[0].selectize;
				var COUNTRY_ID = $('#owner_country').val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.

						state_selectize.clear();
						state_selectize.clearOptions();
						state_selectize.addOption(response);
						<?php if ($owner_state) : ?>
							state_selectize.setValue('<?= $owner_state; ?>');
						<?php endif; ?>
					}
				});
			}

			function CHOOSEN_STATE_OWNER() {
				var city_selectize = $("#owner_city")[0].selectize;
				var STATE_ID = $('#owner_state').val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.
						city_selectize.clear();
						city_selectize.clearOptions();
						city_selectize.addOption(response);
						<?php if ($owner_city) : ?>
							city_selectize.setValue('<?= $owner_city; ?>');
						<?php endif; ?>
					}
				});
			}

			function showVEHICLELIST() {

				showvendorFORMSTEP3('<?= $VENDOR_ID; ?>', '<?= $ROUTE; ?>', 'vehicle_info');
				// Scroll to the top of the page
				window.scrollTo({
					top: 0,
					behavior: 'smooth' // Use smooth scrolling if supported
				});
			}

			function addVEHICLEDETAILS(BRANCH_ID, VENDOR_ID, VEHICLE_ID, ROUTE) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_add_newvehicle_info.php?type=add_vehicle',
					data: {
						BRANCH_ID: BRANCH_ID,
						VENDOR_ID: VENDOR_ID,
						VEHICLE_ID: VEHICLE_ID,
						ROUTE: ROUTE
					},
					success: function(response) {
						$('#list_vehicle_details').hide();
						$('#vehicle_list_without_add_form').hide();
						$('#show_add_vehicle').show();
						$('#show_add_vehicle').html(response);

						// Scroll to the top of the page
						window.scrollTo({
							top: 0,
							behavior: 'smooth' // Use smooth scrolling if supported
						});
					}
				});

			}

			function removeVEHICLEGALLERY(VEHICLE_GAL_ID, VEHICLE_ID, vehicle_col_gallery_counter) {
				$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_add_newvehicle_info.php?type=delete_vehicle_gallery&ID=' + VEHICLE_GAL_ID + '&VEHICLE_ID=' + VEHICLE_ID + '&vehicle_col_gallery_counter=' + vehicle_col_gallery_counter, function() {
					const container = document.getElementById("confirmDELETEINFODATA");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}

			function removeInsertedVEHICLEGALLERY(vehicle_col_gallery_counter) {
				$('#' + vehicle_col_gallery_counter).remove();
			}
		</script>
	<?php
	elseif ($_GET['type'] == 'delete_vehicle_gallery') :

		$vehicle_gallery_ID = $_GET['ID'];
		$VEHICLE_ID = $_GET['VEHICLE_ID'];
		$vehicle_col_gallery_counter = $_GET['vehicle_col_gallery_counter'];

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
					<p class="mb-0 mt-2">Are you sure? want to delete this vehicle gallery<br /> This action cannot be undone.</p>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-center">
				<button type="button" class="btn btn-label-github waves-effect" data-bs-dismiss="modal">Close</button>
				<button type="button" onclick="confirmVEHICLEGALLERYDELETE('<?= $vehicle_gallery_ID; ?>','<?= $VEHICLE_ID; ?>', '<?= $vehicle_col_gallery_counter; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
			</div>
		</div>
		<script>
			function confirmVEHICLEGALLERYDELETE(VEHICLE_GAL_ID, VEHICLE_ID, vehicle_col_gallery_counter) {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_manage_vendor.php?type=confirm_vehicle_gallery_delete",
					data: {
						_ID: VEHICLE_GAL_ID,
						_VEHICLE_ID: VEHICLE_ID
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							//NOT SUCCESS RESPONSE
							if (response.result_success) {
								TOAST_NOTIFICATION('error', 'Unable to delete the vehicle gallery', 'Error !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							$('#confirmDELETEINFODATA').modal('hide');
							TOAST_NOTIFICATION('success', 'Vehicle Gallery Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							$('#' + vehicle_col_gallery_counter).remove();
						}
					}
				});
			}
		</script>
<?php

	endif;
endif;
?>