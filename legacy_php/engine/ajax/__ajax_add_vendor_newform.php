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
		$ROUTE = $_GET['ROUTE'];
		//
		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$select_vendor_list_query = sqlQUERY_LABEL("SELECT `vendor_name`, `vendor_code`, `vendor_email`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_country`, `vendor_state`, `vendor_city`, `vendor_pincode`, `vendor_othernumber`, `vendor_address`,`vendor_company_name`, `invoice_gstin_number`, `invoice_pan_number`, `invoice_pincode`, `invoice_mobile_number`,`invoice_email`,`invoice_address`,`invoice_logo`,`status`, `vendor_margin`,`vendor_margin_gst_type`,`vendor_margin_gst_percentage` FROM `dvi_vendor_details` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_LIST:" . sqlERROR_LABEL());
			while ($fetch_vendor_list_data = sqlFETCHARRAY_LABEL($select_vendor_list_query)) :
				$vendor_name = $fetch_vendor_list_data['vendor_name'];
				$vendor_code = $fetch_vendor_list_data['vendor_code'];
				$vendor_email = $fetch_vendor_list_data['vendor_email'];

				$vendor_primary_mobile_number = $fetch_vendor_list_data['vendor_primary_mobile_number'];
				$vendor_alternative_mobile_number = $fetch_vendor_list_data['vendor_alternative_mobile_number'];
				$vendor_country = $fetch_vendor_list_data["vendor_country"];
				$vendor_state = $fetch_vendor_list_data["vendor_state"];
				$vendor_city = $fetch_vendor_list_data["vendor_city"];
				$vendor_pincode = $fetch_vendor_list_data["vendor_pincode"];
				$vendor_othernumber = $fetch_vendor_list_data["vendor_othernumber"];
				$vendor_address = $fetch_vendor_list_data["vendor_address"];
				$vendor_company_name = $fetch_vendor_list_data['vendor_company_name'];
				$invoice_gstin_number = $fetch_vendor_list_data['invoice_gstin_number'];
				$invoice_pan_number = $fetch_vendor_list_data["invoice_pan_number"];
				$invoice_pincode = $fetch_vendor_list_data["invoice_pincode"];
				$invoice_mobile_number = $fetch_vendor_list_data["invoice_mobile_number"];
				$invoice_email = $fetch_vendor_list_data["invoice_email"];
				$invoice_address = $fetch_vendor_list_data['invoice_address'];
				$invoice_logo = $fetch_vendor_list_data["invoice_logo"];
				$status = $fetch_vendor_list_data['status'];
				$vendor_margin = $fetch_vendor_list_data['vendor_margin'];
				$vendor_margin_gst_type = $fetch_vendor_list_data['vendor_margin_gst_type'];
				$vendor_margin_gst_percentage = $fetch_vendor_list_data['vendor_margin_gst_percentage'];
			endwhile;

			$select_vendor_credientials = sqlQUERY_LABEL("SELECT `userID`, `vendor_id`, `user_profile`, `username`, `password`, `roleID` FROM `dvi_users` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
			while ($fetch_vendor_credientials_list_data = sqlFETCHARRAY_LABEL($select_vendor_credientials)) :
				$vendor_select_role = $fetch_vendor_credientials_list_data['roleID'];
				$vendor_username = $fetch_vendor_credientials_list_data['username'];
				$vendor_password = $fetch_vendor_credientials_list_data['password'];
			endwhile;

			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			// $disabled_navigate = '';
			// $button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'javascript:;';
			$branch_info_url = 'javascript:;';
			$driver_cost_url = 'javascript:;';
			$vehicle_pricebook_url = 'javascript:;';
			$vehicle_info_url = 'javascript:;';
			$permit_cost_info_url = 'javascript:;';
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
?>
		<!-- Default Wizard -->
		<div class="row">
			<div class="col-12">
				<div id="wizard-validation" class="bs-stepper mt-2">
					<div class="bs-stepper-header border-0 justify-content-center py-2">
						<div class="step" data-target="#basic_info">
							<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle active-stepper">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_Pricebook">
							<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Pricebook</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Permit Cost
									</h5>
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
					<div>
						<form id="form_basic_info" action="" method="POST" data-parsley-validate>
							<!-- Basic Info -->
							<div id="basic_info" class="content active dstepper-block">
								<div class="content-header mb-3">
									<h5 class="text-primary mb-0">Basic Details</h5>
								</div>
								<div class="row g-3">
									<div class="col-sm-3">
										<label class="form-label" for="vendor_name">Vendor Name<span class="text-danger"> *</span></label>
										<input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Vendor Name" required value="<?= $vendor_name ?>" autocomplete="off" />
									</div>
									<div class="col-sm-3">
										<label class="form-label" for="vendor_email">Email ID<span class=" text-danger"> *</span></label>
										<input type="email" name="vendor_email" id="vendor_email" class="form-control" placeholder="Email ID" aria-label="Email ID" data-parsley-type="email" required data-parsley-check_vendor_email data-parsley-check_vendor_email-message="Entered Vendor Email Already Exists" value="<?= $vendor_email ?>" data-parsley-trigger="keyup" autocomplete="off" />
										<input type="hidden" name="old_vendor_email" id="old_vendor_email" value="<?= $vendor_email; ?>" />
									</div>
									<div class="col-sm-3">
										<label class="form-label" for="vendor_primary_mobile_number">Primary Mobile Number<span class=" text-danger"> *</span></label>
										<input type="tel" name="vendor_primary_mobile_number" id="vendor_primary_mobile_number" class="form-control" placeholder="Primary Mobile Number" aria-label="Primary Mobile Number" required data-parsley-type="number" value="<?= $vendor_primary_mobile_number ?>" data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" maxlength="10" autocomplete="off" />
									</div>
									<div class="col-sm-3">
										<label class="form-label" for="vendor_alternative_mobile_number">Alternative Mobile Number<span class=" text-danger"> *</span></label>
										<input type="tel" id="vendor_alternative_mobile_number" name="vendor_alternative_mobile_number" class="form-control" placeholder="Alternative Mobile Number" required data-parsley-type="number" value="<?= $vendor_alternative_mobile_number ?>" data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" maxlength="10" autocomplete="off" />
									</div>
									<div class="col-sm-3">
										<label class="form-label" for="vendor_country">Country<span class=" text-danger"> *</span></label>
										<select class="form-select" name="vendor_country" id="vendor_country" onchange="CHOOSEN_COUNTRY()" data-parsley-trigger="keyup" data-parsley-errors-container="#vendor_country_error_container">
											<?= getCOUNTRYLIST($vendor_country, 'select_country'); ?>
										</select>
										<div id="vendor_country_error_container"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label" for="vendor_state">State<span class=" text-danger"> *</span></label>
										<div class="form-group">
											<select class="form-select" name="vendor_state" id="vendor_state" onchange="CHOOSEN_STATE()" data-parsley-trigger="keyup" data-parsley-errors-container="#vendor_state_error_container">
												<option value="">Choose State</option>
											</select>
										</div>
										<div id="vendor_state_error_container"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label" for="vendor_city">City<span class=" text-danger"> *</span></label>
										<div class="form-group">
											<select class="form-select" name="vendor_city" id="vendor_city" value="<?= $vendor_city; ?>" data-parsley-trigger="keyup" data-parsley-errors-container="#vendor_city_error_container">
												<option value="">Choose City</option>
											</select>
										</div>
										<div id="vendor_city_error_container"></div>
									</div>
									<div class="col-md-3">
										<label class="form-label" for="vendor_pincode">Pincode<span class=" text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="vendor_pincode" id="vendor_pincode" class="form-control" placeholder="Pincode" value="<?= $vendor_pincode; ?>" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-3">
										<label class="form-label" for="vendor_othernumber">Other Number</label>
										<div class="form-group">
											<input type="text" name="vendor_othernumber" id="vendor_othernumber" class="form-control" placeholder="Other Number" value="<?= $vendor_othernumber; ?>" autocomplete="off" />
										</div>
									</div>
									<?php if (($vendor_ID == '' || $vendor_ID == 0) && $ROUTE != 'edit') : ?>
										<div class="col-md-3">
											<label class="form-label" for="vendor_username">Username<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="text" name="vendor_username" id="vendor_username" class="form-control" placeholder="Username" autocomplete="off" />
											</div>
										</div>
									<?php endif; ?>
									<div class="col-md-3">
										<label class="form-label" for="vendor_password">Password <span class=" text-danger"> <?= (($vendor_ID == '' || $vendor_ID == 0) && $ROUTE == 'add') ? "*" : "" ?></span></label>
										<div class="form-group">
											<input type="password" name="vendor_password" id="vendor_password" class="form-control" placeholder="Password" value="" autocomplete="off" />
										</div>
									</div>
									<?php if ($logged_user_level == 1): ?>
										<div class="col-sm-3">
											<label class="form-label" for="vendor_select_role">Role<span class=" text-danger"> *</span></label>
											<select class="form-select" name="vendor_select_role" id="vendor_select_role" value="<?= $vendor_select_role; ?>" data-parsley-errors-container="#vendor_role_error_container">
												<?= getRole($vendor_select_role, 'select'); ?>
											</select>
											<div id="vendor_role_error_container"></div>
										</div>
										<div class="col-md-3">
											<label class="form-label" for="vendor_margin">Vendor Margin % <span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="text" name="vendor_margin" id="vendor_margin" class="form-control" placeholder="Vendor Margin" required value="<?= $vendor_margin; ?>" autocomplete="off" />
											</div>
										</div>
										<div class="col-md-4"><label class="form-label" for="vendor_margin_gst_type">Vendor Margin GST Type<span class="text-danger">*</span></label>
											<select id="vendor_margin_gst_type" name="vendor_margin_gst_type" class="form-control form-select" required><?= getGSTTYPE($vendor_margin_gst_type, 'select') ?></select>
										</div>
										<div class="col-md-4"><label class="form-label" for="vendor_margin_gst_percentage">Vendor Margin GST Percentage<span class="text-danger">*</span></label>
											<div class="form-group">
												<select id="vendor_margin_gst_percentage" name="vendor_margin_gst_percentage" class="form-control form-select" required>
													<?= getGSTDETAILS($vendor_margin_gst_percentage, 'select'); ?>
												</select>
											</div>
										</div>
									<?php else: ?>
										<input type="hidden" name="vendor_select_role" id="vendor_select_role" value="<?= $vendor_select_role; ?>" hidden>
										<input type="hidden" name="vendor_margin" id="vendor_margin" value="<?= $vendor_margin; ?>" hidden>
										<input type="hidden" name="vendor_margin_gst_type" id="vendor_margin_gst_type" value="<?= $vendor_margin_gst_type; ?>" hidden>
										<input type="hidden" name="vendor_margin_gst_percentage" id="vendor_margin_gst_percentage" value="<?= $vendor_margin_gst_percentage; ?>" hidden>
									<?php endif; ?>

									<div class="col-md-4">
										<label class="form-label" for="vendor_address">Address<span class=" text-danger"> *</span></label>
										<div class="form-group">
											<textarea id="vendor_address" rows="2" name="vendor_address" class="form-control" placeholder="Address" required=""> <?= $vendor_address; ?> </textarea>
										</div>
									</div>
								</div>

								<input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
								<div class="divider">
									<div class="divider-text text-primary">
										<i class="ti ti-star"></i>
									</div>
								</div>
								<div class="row g-3 mt-2 mb-2">
									<h5 class="text-primary m-0">Invoice Details</h5>
									<div class="col-md-4">
										<label class="form-label" for="vendor_company_name">Company Name<span class="text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="vendor_company_name" id="vendor_company_name" class="form-control" placeholder="Company Name" value="<?= $vendor_company_name; ?>" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_address">Address <span class="text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="invoice_address" id="invoice_address" class="form-control" placeholder="Address" value="<?= $invoice_address; ?>" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_pincode">Pincode <span class="text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="invoice_pincode" id="invoice_pincode" class="form-control" placeholder="Pincode" value="<?= $invoice_pincode; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_gstin_number">GSTIN Number<span class=" text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="invoice_gstin_number" id="invoice_gstin_number" class="form-control" placeholder="GSTIN FORMAT: 10AABCU9603R1Z5 " data-parsley-checkgst data-parsley-checkgst-message="GST Number already Exists" data-parsley-type="alphanum" data-parsley-pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}\d{1}" data-parsley-whitespace="trim" data-parsley-trigger="keyup" value="<?= $invoice_gstin_number; ?>" data-parsley-errors-container="#invoice_gstin_number_error_container" data-parsley-check_invoice_gstin_number data-parsley-check_invoice_gstin_number-message="Entered Vendor GSTIN Number Already Exists" autocomplete="off" required />
											<input type="hidden" name="old_invoice_gstin_number" id="old_invoice_gstin_number" value="<?= $invoice_gstin_number; ?>" />
											<small class="text-dark"><b>GSTIN Format: 10AABCU9603R1Z5 </b></small>
										</div>
										<div id="invoice_gstin_number_error_container"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_pan_number">PAN Number<span class=" text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="invoice_pan_number" id="invoice_pan_number" class="form-control" placeholder="Pan Format: CNFPC5441D" data-parsley-checkpan data-parsley-checkpan-message="PAN Number already Exists" data-parsley-type="alphanum" data-parsley-pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" data-parsley-whitespace="trim" data-parsley-trigger="keyup" value="<?= $invoice_pan_number; ?>" data-parsley-errors-container="#invoice_pan_number_error_container" data-parsley-check_invoice_pan_number data-parsley-check_invoice_pan_number-message="Entered Vendor PAN Number Already Exists" autocomplete="off" required />
											<input type="hidden" name="old_invoice_pan_number" id="old_invoice_pan_number" value="<?= $invoice_pan_number; ?>" />
											<small class="text-dark"><b>Pan Format: CNFPC5441D</b> </small>
										</div>
										<div id="invoice_pan_number_error_container"></div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_mobile_number">Contact No. <span class="text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="invoice_mobile_number" id="invoice_mobile_number" class="form-control" placeholder="Contact No." value="<?= $invoice_mobile_number; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_email">Email ID <span class="text-danger"> *</span></label>
										<div class="form-group">
											<input type="text" name="invoice_email" id="invoice_email" class="form-control" placeholder="Company Email ID" value="<?= $invoice_email; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="email" required autocomplete="off" />
										</div>
									</div>
									<div class="col-md-4">
										<label class="form-label" for="invoice_logo">Logo</label>
										<?php if ($invoice_logo) : ?>
											<a href="#" class="fw-bold float-end" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#invoice_logo_modal">View</a>
										<?php endif; ?>
										<div class="form-group">
											<input type="file" name="invoice_logo" id="invoice_logo" class="form-control" placeholder="Company Logo" autocomplete="off" />
										</div>
									</div>

								</div>

								<div class="row g-3 mt-2">
									<div class="col-12 d-flex justify-content-between">
										<div>
											<a href="newvendor.php" class="btn btn-secondary">Back
											</a>
										</div>
										<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1"><?= $button_label; ?></span></button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Company Logo Modal -->
		<div class="modal fade" id="invoice_logo_modal" tabindex="-1" aria-labelledby="invoice_logoLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="invoice_logoLabel">Company Logo</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body text-center">
						<img src="uploads/logo/<?= $invoice_logo; ?>" alt="No-Image-Found" class="rounded-3" width="150" height="150" />
					</div>
				</div>
			</div>
		</div>

		<!-- /Default Wizard -->

		<script src="assets/js/parsley.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
		<script src="assets/js/selectize/selectize.min.js"></script>

		<script>
			document.getElementById('vendor_email').addEventListener('input', function() {
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

			$(document).ready(function() {
				$(".form-select").selectize();

				//CHECK DUPLICATE VENDOR EMAIL ID
				$('#vendor_email').parsley();
				var old_vendor_emailDETAIL = document.getElementById("old_vendor_email").value;
				var vendor_email = $('#vendor_email').val();
				window.ParsleyValidator.addValidator('check_vendor_email', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_vendor_email.php',
							method: "POST",
							data: {
								vendor_email: value,
								old_vendor_email: old_vendor_emailDETAIL
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				<?php if ($logged_user_level == 1): ?>
					//CHECK DUPLICATE VENDOR GSTIN NUMBER
					$('#invoice_gstin_number').parsley();
					var old_invoice_gstin_numberDETAIL = document.getElementById("old_invoice_gstin_number").value;
					var invoice_gstin_number = $('#invoice_gstin_number').val();
					window.ParsleyValidator.addValidator('check_invoice_gstin_number', {
						validateString: function(value) {
							return $.ajax({
								url: 'engine/ajax/__ajax_check_invoice_gstin_number.php',
								method: "POST",
								data: {
									invoice_gstin_number: value,
									old_invoice_gstin_number: old_invoice_gstin_numberDETAIL
								},
								dataType: "json",
								success: function(data) {
									return true;
								}
							});
						}
					});

					//CHECK DUPLICATE VENDOR PAN NUMBER
					$('#invoice_pan_number').parsley();
					var old_invoice_pan_numberDETAIL = document.getElementById("old_invoice_pan_number").value;
					var invoice_pan_number = $('#invoice_pan_number').val();
					window.ParsleyValidator.addValidator('check_invoice_pan_number', {
						validateString: function(value) {
							return $.ajax({
								url: 'engine/ajax/__ajax_check_invoice_pan_number.php',
								method: "POST",
								data: {
									invoice_pan_number: value,
									old_invoice_pan_number: old_invoice_pan_numberDETAIL
								},
								dataType: "json",
								success: function(data) {
									return true;
								}
							});
						}
					});
				<?php endif; ?>

				<?php if ($vendor_ID != '' && $vendor_ID != 0) : ?>
					CHOOSEN_COUNTRY();
					CHOOSEN_STATE();
				<?php endif; ?>

				$("#vendor_country").attr('required', true);
				$("#vendor_state").attr('required', true);
				$("#vendor_city").attr('required', true);
				$("#vendor_select_role").attr('required', true);

				//AJAX FORM SUBMIT
				$("#form_basic_info").submit(function(event) {
					var form = $('#form_basic_info')[0];
					var data = new FormData(form);
					// $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
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
							if (response.errors.vendor_name_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_email_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Email Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_email_already_exist) {
								TOAST_NOTIFICATION('warning', 'Vendor Email Already Exist', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_primary_mobile_number_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Primary Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_alternative_mobile_number_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Alternative Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_country_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_state_required) {
								TOAST_NOTIFICATION('warning', 'Vendor State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_city_required) {
								TOAST_NOTIFICATION('warning', 'Vendor City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_pincode_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_othernumber_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Other Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_address_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_gstin_number_required) {
								TOAST_NOTIFICATION('warning', 'Invoice GSTIN Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_company_name_required) {
								TOAST_NOTIFICATION('warning', 'Company Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_pan_number_required) {
								TOAST_NOTIFICATION('warning', 'Invoice PAN Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_pincode_required) {
								TOAST_NOTIFICATION('warning', 'Invoice Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_mobile_number_required) {
								TOAST_NOTIFICATION('warning', 'Invoice Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_address_required) {
								TOAST_NOTIFICATION('warning', 'Invoice Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_email_required) {
								TOAST_NOTIFICATION('warning', 'Invoice Email Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.invoice_logo_required) {
								TOAST_NOTIFICATION('warning', 'Invoice Logo Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_select_role_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Role Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_margin_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Margin Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_margin_gst_type_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Margin GST Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_margin_gst_percentage_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Margin GST percentage Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Vendor basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.i_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to create vendor basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Vendor basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to update vendor basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

	elseif ($_GET['type'] == 'branch_info') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'javascript:;';
			$vehicle_pricebook_url = 'javascript:;';
			$vehicle_info_url = 'javascript:;';
			$permit_cost_info_url = 'javascript:;';
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
	?>
		<!-- Default Wizard -->


		<!-- Default Wizard -->
		<div class="row">
			<div class="col-12">
				<div id="wizard-validation" class="bs-stepper mt-2">
					<div class="bs-stepper-header border-0 justify-content-center py-2">
						<div class="step" data-target="#basic_info">
							<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle  disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title  disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle active-stepper ">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_Pricebook">
							<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Pricebook</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Permit Cost
									</h5>
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
					<div>
						<form id="form_branch_info" action="" method="POST" data-parsley-validate>
							<!-- Branch Info -->
							<div id="branch_info" class="content active dstepper-block">
								<div class="content-header mb-3">
									<div class="d-flex justify-content-between">
										<h5 class="text-primary mb-0">Branch Details</h5>
										<button type="button" class="btn btn-label-primary waves-effect add_item_btn">+ Add Branch</button>
									</div>
								</div>
								<?php
								if ($vendor_ID != '' && $vendor_ID != 0) :
									$select_vendor_branch_list_query = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_id`, `vendor_branch_name`, `vendor_branch_emailid`, `vendor_branch_primary_mobile_number`, `vendor_branch_alternative_mobile_number`, `vendor_branch_country`, `vendor_branch_state`, `vendor_branch_city`, `vendor_branch_pincode`, `vendor_branch_location`, `vendor_branch_gst_type`, `vendor_branch_gst`, `vendor_branch_address`, `status` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_LIST:" . sqlERROR_LABEL());
									$total_vendor_branch_list_num_rows_count = sqlNUMOFROW_LABEL($select_vendor_branch_list_query);
								endif;
								?>
								<div class="row g-3">
									<div class="col-md-12">
										<div id="show_item"></div>
									</div>
								</div>
								<?php
								if ($total_vendor_branch_list_num_rows_count > 0) :
									$branch_no =  $total_vendor_branch_list_num_rows_count;
									while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
										$vendor_branch_count++;
										$vendor_branch_id = $fetch_vendor_branch_data['vendor_branch_id'];
										$vendor_id = $fetch_vendor_branch_data['vendor_id'];
										$vendor_branch_name = $fetch_vendor_branch_data['vendor_branch_name'];
										//$vendor_branch_code = $fetch_vendor_branch_data['vendor_branch_code'];
										$vendor_branch_emailid = $fetch_vendor_branch_data['vendor_branch_emailid'];
										$vendor_branch_primary_mobile_number = $fetch_vendor_branch_data['vendor_branch_primary_mobile_number'];
										$vendor_branch_alternative_mobile_number = $fetch_vendor_branch_data['vendor_branch_alternative_mobile_number'];
										$vendor_branch_country = $fetch_vendor_branch_data['vendor_branch_country'];
										$vendor_branch_state_id = $fetch_vendor_branch_data['vendor_branch_state'];
										$vendor_branch_state = getSTATELIST('', $vendor_branch_state_id, 'state_label');
										$vendor_branch_city_id = $fetch_vendor_branch_data['vendor_branch_city'];
										$vendor_branch_city = getCITYLIST('', $fetch_vendor_branch_data['vendor_branch_city'], 'city_label');
										$vendor_branch_pincode = $fetch_vendor_branch_data['vendor_branch_pincode'];
										$vendor_branch_location = $fetch_vendor_branch_data['vendor_branch_location'];
										$vendor_branch_gst = $fetch_vendor_branch_data['vendor_branch_gst'];
										$vendor_branch_gst_type = $fetch_vendor_branch_data['vendor_branch_gst_type'];
										$vendor_branch_address = $fetch_vendor_branch_data['vendor_branch_address'];
								?>
										<div class="row g-3">
											<div class="col-md-12">
												<h6 class="m-0 branch_heading">Branch #<?= $branch_no; ?></h6>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_name">Branch Name<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vendor_branch_name[]" id="vendor_branch_name" class="form-control" placeholder="Branch Name" value="<?php echo $vendor_branch_name; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_location">Branch Location<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vendor_branch_location[]" id="vendor_branch_location" class="form-control" placeholder="Branch Location" value="<?= $vendor_branch_location; ?>" onkeypress="vendorBranchLocation_location()" required autocomplete="off" />
												</div>
											</div>
											<?php /* if ($vendor_branch_id != "") : ?>
												<div class="col-md-4">
													<label class="form-label" for="vendor_branch_code">Branch Code<span class=" text-danger"> *</span></label>
													<div class="form-group">
														<input type="text" name="vendor_branch_code[]" id="vendor_branch_code" class="form-control" placeholder="Branch Code" value="<?= $vendor_branch_code; ?>" readonly required />
													</div>
												</div>
											<?php endif; */ ?>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_emailid">Email ID<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vendor_branch_emailid[]" id="vendor_branch_emailid_<?= $vendor_branch_count ?>" class="form-control" placeholder="Email ID" value="<?= $vendor_branch_emailid; ?>" data-parsley-type="email" required data-parsley-check_vendor_email_<?= $vendor_branch_count ?> data-parsley-check_vendor_email_<?= $vendor_branch_count ?>-message="Entered Vendor Email Already Exists" autocomplete="off" />
													<input type="hidden" name="old_vendor_branch_emailid[]" id="old_vendor_branch_emailid_<?= $vendor_branch_count ?>" value="<?= $vendor_branch_emailid; ?>" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_primary_mobile_number">Primary Mobile Number<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="tel" name="vendor_branch_primary_mobile_number[]" id="vendor_branch_primary_mobile_number" class="form-control" placeholder="Primary Mobile Number" value="<?= $vendor_branch_primary_mobile_number; ?>" required data-parsley-type="number" data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" maxlength="10" autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_alternative_mobile_number">Alternative Mobile Number<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="tel" name="vendor_branch_alternative_mobile_number[]" id="vendor_branch_alternative_mobile_number" class="form-control" placeholder="Alternative Mobile Number" value="<?= $vendor_branch_alternative_mobile_number; ?>" required data-parsley-pattern="^\+?[1-9]\d{1,14}$" data-parsley-type="number" data-parsley-trigger="change" maxlength="10" autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_country">Country<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<select class="form-select vendor_branch_country" name="vendor_branch_country[]" id="vendor_branch_country_<?= $vendor_branch_count ?>" onchange="CHOOSEN_COUNTRY_ADD('<?= $vendor_branch_count; ?>')" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_country_error_container">
														<?= getCOUNTRYLIST($vendor_branch_country, 'select_country'); ?>
													</select>
												</div>
												<div id="branch_country_error_container"></div>
											</div>
											<div class="col-md-4">

												<label class="form-label" for="vendor_branch_state">State<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<select class="form-select vendor_branch_state" name="vendor_branch_state[]" id="vendor_branch_state_<?= $vendor_branch_count ?>" onchange="CHOOSEN_STATE_ADD(<?= $vendor_branch_count; ?>)" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_state_error_container">
														<?php if ($vendor_ID != '' && $vendor_ID != 0) :
															getSTATELIST($vendor_branch_country, $vendor_branch_state_id, 'select_state');
														else : ?>
															<option value="">Choose State</option>
														<?php endif; ?>
													</select>
												</div>
												<div id="branch_state_error_container"></div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_city">City<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<select class="form-select vendor_branch_city" name="vendor_branch_city[]" id="vendor_branch_city_<?= $vendor_branch_count ?>" value="<?= $vendor_branch_city; ?>" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_city_error_container">
														<?php if ($vendor_ID != '' && $vendor_ID != 0) :
															getCITYLIST($vendor_branch_state_id, $vendor_branch_city, 'select_city');
														else : ?>
															<option value="">Choose City</option>
														<?php endif; ?>
													</select>
												</div>
												<div id="branch_city_error_container"></div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_pincode">Pincode<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vendor_branch_pincode[]" id="vendor_branch_pincode" value="<?= $vendor_branch_pincode; ?>" class="form-control" placeholder="Pincode" required autocomplete="off" />
												</div>
											</div>
											<?php if ($logged_user_level == 1): ?>
												<div class="col-md-4"><label class="form-label" for="gst_status">GST Type<span class="text-danger">*</span></label>
													<select id="vendor_branch_gst_type" name="vendor_branch_gst_type[]" class="form-control form-select" required><?= getGSTTYPE($vendor_branch_gst_type, 'select') ?></select>
												</div>
												<div class="col-md-4">
													<label class="form-label" for="vendor_branch_gst">GST%<span class=" text-danger"> *</span></label>
													<div class="form-group">
														<select id="vendor_branch_gst" name="vendor_branch_gst[]" class="form-control form-select vendor_branch_gst" data-parsley-errors-container="#branch_gst_error_container">
															<?= getGSTDETAILS($vendor_branch_gst, 'select') ?>
														</select>
													</div>
													<div id="branch_gst_error_container"></div>
												</div>
											<?php else: ?>
												<input type="hidden" name="vendor_branch_gst_type" id="vendor_branch_gst_type" value="<?= $vendor_branch_gst_type; ?>" hidden>
												<input type="hidden" name="vendor_branch_gst" id="vendor_branch_gst" value="<?= $vendor_branch_gst; ?>" hidden>
											<?php endif; ?>
											<div class=" col-md-4">
												<label class="form-label" for="vendor_branch_address">Address<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<textarea id="vendor_branch_address" name="vendor_branch_address[]" class="form-control" rows="1" placeholder="Address" required=""><?= $vendor_branch_address; ?></textarea>
												</div>
											</div>
											<input type="hidden" name="hidden_vendor_branch_id[]" id="hidden_vendor_branch_id" value="<?= $vendor_branch_id; ?>" hidden>
											<input type="hidden" name="hidden_vendor_id[]" id="hidden_vendor_id" value="<?= $vendor_ID; ?>" hidden>
											<div class="col d-flex align-items-center justify-content-end">
												<button type="button" onclick="deletebranch('<?= $vendor_branch_id; ?>','<?= $vendor_ID; ?>', '<?= $ROUTE; ?>');" class="btn btn-label-danger mt-4"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
											</div>

											<div class="border-bottom border-bottom-dashed my-4"></div>
											<?php if ($vendor_branch_count == $total_vendor_branch_list_num_rows_count) : ?>
												<div class="col-12 d-flex justify-content-between">
													<a href="<?= $basic_info_url; ?>" class="btn btn-secondary">
														<span class="align-middle d-sm-inline-block d-none">Back</span>
													</a>
													<button type="submit" class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1"><?= $button_label; ?></span></button>
												</div>
											<?php endif; ?>
										</div>
									<?php
										$branch_no--;
									endwhile; ?>
								<?php else : ?>
									<div class="row g-3">
										<div class="col-md-12">
											<h6 class="m-0 branch_heading">Branch #1</h6>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_name">Branch Name<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="text" name="vendor_branch_name[]" id="vendor_branch_name" class="form-control" placeholder="Branch Name" value="<?php echo $vendor_branch_name; ?>" required autocomplete="off" />
											</div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_location">Branch Location<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="text" name="vendor_branch_location[]" id="vendor_branch_location" class="form-control" placeholder="Branch Location" value="<?= $vendor_branch_location; ?>" onkeypress="vendorBranchLocation_location()" required autocomplete="off" />
											</div>
										</div>

										<?php /* if ($vendor_branch_id != "") : ?>
											<div class="col-md-4">
												<label class="form-label" for="vendor_branch_code">Branch Code<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vendor_branch_code[]" id="vendor_branch_code" class="form-control" placeholder="Branch Code" value="<?= $vendor_branch_code; ?>" readonly required />
												</div>
											</div>
										<?php endif; */ ?>

										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_emailid">Email ID<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="text" name="vendor_branch_emailid[]" id="vendor_branch_emailid_1" class="form-control" placeholder="Email ID" value="<?= $vendor_branch_emailid; ?>" data-parsley-type="email" required data-parsley-check_vendor_email_1 data-parsley-check_vendor_email_1-message="Entered Branch Email Already Exists" autocomplete="off" />
												<input type="hidden" name="old_vendor_branch_emailid[]" id="old_vendor_branch_emailid_1" value="<?= $vendor_branch_emailid; ?>" />
											</div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_primary_mobile_number">Primary Mobile Number<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="tel" name="vendor_branch_primary_mobile_number[]" id="vendor_branch_primary_mobile_number" class="form-control" placeholder="Primary Mobile Number" value="<?= $vendor_branch_primary_mobile_number; ?>" required data-parsley-trigger="change" data-parsley-pattern="^\+?[1-9]\d{1,14}$" autocomplete="off" />
											</div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_alternative_mobile_number">Alternative Mobile Number<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="tel" name="vendor_branch_alternative_mobile_number[]" id="vendor_branch_alternative_mobile_number" class="form-control" placeholder="Alternative Mobile Number" data-parsley-pattern="^\+?[1-9]\d{1,14}$" data-parsley-type="number" value="<?= $vendor_branch_alternative_mobile_number; ?>" required data-parsley-trigger="change" autocomplete="off" />
											</div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_country">Country<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<select class="form-select vendor_branch_country" name="vendor_branch_country[]" id="vendor_branch_country_1" onchange="CHOOSEN_COUNTRY_ADD('1')" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_country_error_container">
													<?= getCOUNTRYLIST($vendor_branch_country, 'select_country'); ?>
												</select>
											</div>
											<div id="branch_country_error_container"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_state">State<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<select class="form-select vendor_branch_state" name="vendor_branch_state[]" id="vendor_branch_state_1" value="<?= $vendor_branch_state; ?>" onchange="CHOOSEN_STATE_ADD('1')" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_state_error_container">
													<option value="">Choose State</option>
												</select>
											</div>
											<div id="branch_state_error_container"></div>
										</div>
										<div class="col-md-4">

											<label class="form-label" for="vendor_branch_city">City<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<select class="form-select vendor_branch_city" name="vendor_branch_city[]" id="vendor_branch_city_1" value="<?= $vendor_branch_city; ?>" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_city_error_container">
													<option value="">Choose City</option>
												</select>
											</div>
											<div id="branch_city_error_container"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_pincode">Pincode<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<input type="text" name="vendor_branch_pincode[]" id="vendor_branch_pincode" value="<?= $vendor_branch_pincode; ?>" class="form-control" placeholder="Pincode" required autocomplete="off" />
											</div>
										</div>
										<div class="col-md-4"><label class="form-label" for="gst_status">GST Type<span class="text-danger">*</span></label>
											<select id="vendor_branch_gst_type" name="vendor_branch_gst_type[]" class="form-control form-select" required><?= getGSTTYPE($vendor_branch_gst_type, 'select') ?></select>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_gst">GST%<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<select id="vendor_branch_gst" name="vendor_branch_gst[]" class="form-control form-select vendor_branch_gst" data-parsley-errors-container="#branch_gst_error_container">
													<?= getGSTDETAILS($vendor_branch_gst, 'select') ?>
												</select>
											</div>
											<div id="branch_gst_error_container"></div>
										</div>
										<div class="col-md-4">
											<label class="form-label" for="vendor_branch_address">Address<span class=" text-danger"> *</span></label>
											<div class="form-group">
												<textarea id="vendor_branch_address" name="vendor_branch_address[]" class="form-control" rows="1" placeholder="Address" required=""><?= $vendor_branch_address; ?></textarea>
											</div>
										</div>
										<input type="hidden" name="hidden_vendor_branch_id[]" id="hidden_vendor_branch_id" value="<?= $vendor_branch_id; ?>" hidden>
										<input type="hidden" name="hidden_vendor_id[]" id="hidden_vendor_id" value="<?= $vendor_ID; ?>" hidden>
										<div class="col d-flex align-items-center justify-content-end">
											<button type="button" onclick="deletebranch('<?= $vendor_branch_id; ?>','<?= $vendor_ID; ?>', '<?= $ROUTE; ?>');" class="btn btn-label-danger mt-4"><i class="ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button>
										</div>
										<div class="border-bottom border-bottom-dashed my-4"></div>

										<div class="col-12 d-flex justify-content-between">
											<a href="<?= $basic_info_url; ?>" class="btn btn-secondary">Back
											</a>
											<button class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1"><?= $button_label; ?></span></button>
										</div>
									</div>
								<?php endif; ?>

							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
		<!-- /Default Wizard -->

		<script src="assets/js/parsley.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
		<script src="assets/js/selectize/selectize.min.js"></script>

		<script>
			function vendorBranchLocation_location() {
				var vendorBranchLocationInput = document.getElementById('vendor_branch_location');
				// var vendorBranchLocationAutocomplete = new google.maps.places.Autocomplete(vendorBranchLocationInput);
			}

			$(document).ready(function() {
				$(".form-select").selectize();
				<?php
				if ($vendor_ID != '' && $vendor_ID != 0) :
					$select_vendor_branch_add_btn_list_query = sqlQUERY_LABEL("SELECT `vendor_id` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_LIST:" . sqlERROR_LABEL());
					$total_vendor_branch_add_btn_list_num_rows_count = sqlNUMOFROW_LABEL($select_vendor_branch_add_btn_list_query);
					if ($total_vendor_branch_add_btn_list_num_rows_count > 0) : ?>
						vendor_branch_counter = <?= $total_vendor_branch_add_btn_list_num_rows_count; ?>;
						<?php
						while ($fetch_vendor_branch_list_data = sqlFETCHARRAY_LABEL($select_vendor_branch_add_btn_list_query)) :
							$vendor_branch_list_count++;
						?>
							$("#vendor_branch_country_<?= $vendor_branch_list_count; ?>").attr('required', true);
							$("#vendor_branch_state_<?= $vendor_branch_list_count; ?>").attr('required', true);
							$("#vendor_branch_city_<?= $vendor_branch_list_count; ?>").attr('required', true);
							$("#vendor_branch_gst_<?= $vendor_branch_list_count; ?>").attr('required', true);
						<?php endwhile;
					else : ?>
						vendor_branch_counter = 1;

						$("#vendor_branch_country_1").attr('required', true);
						$("#vendor_branch_state_1").attr('required', true);
						$("#vendor_branch_city_1").attr('required', true);
						$("#vendor_branch_gst_1").attr('required', true);
				<?php endif;
				endif;
				?>

				//CHECK DUPLICATE VENDOR EMAIL ID
				<?php
				if ($vendor_ID != '' && $vendor_ID != 0) :
					$total_vendor_branch_list_num_rows_count = 0;
					$vendor_branch_count = 0;
					$select_vendor_branch_list_query = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_id`, `vendor_branch_name`,  `vendor_branch_emailid`, `vendor_branch_primary_mobile_number`, `vendor_branch_alternative_mobile_number`, `vendor_branch_country`, `vendor_branch_state`, `vendor_branch_city`, `vendor_branch_pincode`, `vendor_branch_location`, `vendor_branch_gst_type`, `vendor_branch_gst`, `vendor_branch_address`, `status` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_LIST:" . sqlERROR_LABEL());
					$total_vendor_branch_list_num_rows_count = sqlNUMOFROW_LABEL($select_vendor_branch_list_query);

					while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
						$vendor_branch_count++;
				?>
						$('#vendor_branch_emailid_<?= $vendor_branch_count ?>').parsley();
						var old_vendor_branch_emailidDETAIL_<?= $vendor_branch_count ?> = document.getElementById("old_vendor_branch_emailid_<?= $vendor_branch_count ?>").value;
						var vendor_branch_emailid_<?= $vendor_branch_count ?> = $('#vendor_branch_emailid_<?= $vendor_branch_count ?>').val();
						window.ParsleyValidator.addValidator('check_vendor_email_<?= $vendor_branch_count ?>', {
							validateString: function(value) {
								return $.ajax({
									url: 'engine/ajax/__ajax_check_vendor_branch_emailid.php',
									method: "POST",
									data: {
										vendor_branch_emailid: value,
										old_vendor_branch_emailid: old_vendor_branch_emailidDETAIL_<?= $vendor_branch_count ?>
									},
									dataType: "json",
									success: function(data) {
										return true;
									}
								});
							}
						});
					<?php endwhile;
				else : ?>
					$('#vendor_branch_emailid_1').parsley();
					var old_vendor_branch_emailidDETAIL_1 = document.getElementById("old_vendor_branch_emailid_1").value;
					var vendor_branch_emailid_1 = $('#vendor_branch_emailid_1').val();
					window.ParsleyValidator.addValidator('check_vendor_email_1', {
						validateString: function(value) {
							return $.ajax({
								url: 'engine/ajax/__ajax_check_vendor_branch_emailid.php',
								method: "POST",
								data: {
									vendor_branch_emailid: value,
									old_vendor_branch_emailid: old_vendor_branch_emailidDETAIL_1
								},
								dataType: "json",
								success: function(data) {
									return true;
								}
							});
						}
					});
				<?php endif; ?>

				$(".add_item_btn").click(function(e) {
					vendor_branch_counter++;

					e.preventDefault();

					$("#show_item").prepend(`<div class="row g-3" id="show_` + vendor_branch_counter + `"><div class="col-md-12 mt-4"><h6 class="m-0 branch_heading">Branch #` + vendor_branch_counter + `</h6></div><div class="col-md-4"><label class="form-label" for="vendor_branch_name_` + vendor_branch_counter + `">Branch Name <span class="text-danger">*</span></label> <input type="text" name="vendor_branch_name[]" id="vendor_branch_name_` + vendor_branch_counter + `" class="form-control" placeholder="Branch Name" required autocomplete="off" /></div><div class="col-md-4"><label class="form-label" for="vendor_branch_location_` + vendor_branch_counter + `">Branch Location<span class="text-danger"> *</span></label><div class="form-group"><input type="text" name="vendor_branch_location[]" onkeypress="vendorBranchLocation_location()"  id="vendor_branch_location_` + vendor_branch_counter + `" class="form-control" placeholder="Branch Location" required autocomplete="off"/></div></div><div class="col-md-4"><label class="form-label" for="vendor_branch_emailid_` + vendor_branch_counter + `">Email ID <span class="text-danger">*</span></label><input type="text" name="vendor_branch_emailid[]" id="vendor_branch_emailid_` + vendor_branch_counter + `" class="form-control" placeholder="Email ID" data-parsley-type="email" required data-parsley-check_vendor_email_` + vendor_branch_counter + ` data-parsley-check_vendor_email_` + vendor_branch_counter + `-message="Entered Vendor Email Already Exists" /><input type="hidden" name="old_vendor_branch_emailid[]" id="old_vendor_branch_emailid_` + vendor_branch_counter + `" value="<?= $vendor_branch_emailid; ?>" autocomplete="off"/></div><div class="col-md-4"><div class="form-group"><label class="form-label" for="vendor_branch_primary_mobile_number_` + vendor_branch_counter + `">Primary Mobile Number <span class="text-danger">*</span></label><div class="select2-primary"> <input type="tel" name="vendor_branch_primary_mobile_number[]" id="vendor_branch_primary_mobile_number_` + vendor_branch_counter + `" class="form-control" placeholder="Primary Mobile Number" required autocomplete="off"/></div></div></div><div class="col-md-4"><label class="form-label" for="vendor_branch_alternative_mobile_number_` + vendor_branch_counter + `">Alternative Mobile Number <span class="text-danger">*</span></label><input type="tel" name="vendor_branch_alternative_mobile_number[]" id="vendor_branch_alternative_mobile_number_` + vendor_branch_counter + `" class="form-control" placeholder="Alternative Mobile Number" required autocomplete="off"/></div><div class="col-md-4"><label class="form-label" for="vendor_branch_country_` + vendor_branch_counter + `">Country <span class="text-danger">*</span></label><div class="form-group"><select class="form-select form-add-select_` + vendor_branch_counter + `" name="vendor_branch_country[]" id="vendor_branch_country_` + vendor_branch_counter + `" onchange="CHOOSEN_COUNTRY_ADD(` + vendor_branch_counter + `)" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_country_error_container_` + vendor_branch_counter + `"><?= getCOUNTRYLIST($vendor_branch_country, 'select_country'); ?></select></div><div id="branch_country_error_container_` + vendor_branch_counter + `"></div></div><div class="col-md-4"><label class="form-label" for="vendor_branch_state_` + vendor_branch_counter + `">State <span class="text-danger">*</span></label><div class="form-group"><select class="form-select form-add-select_` + vendor_branch_counter + `" name="vendor_branch_state[]" id="vendor_branch_state_` + vendor_branch_counter + `" value="<?= $vendor_branch_state; ?>" onchange="CHOOSEN_STATE_ADD(` + vendor_branch_counter + `)" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_state_error_container_` + vendor_branch_counter + `"><option value="">Choose State</option></select></div><div id="branch_state_error_container_` + vendor_branch_counter + `"></div></div><div class="col-md-4"><div class="form-group"><label class="form-label" for="vendor_branch_city_` + vendor_branch_counter + `">City <span class="text-danger">*</span></label><div class="form-group"><select class="form-select form-add-select_` + vendor_branch_counter + `" name="vendor_branch_city[]" id="vendor_branch_city_` + vendor_branch_counter + `" value="<?= $vendor_branch_city; ?>" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_city_error_container_` + vendor_branch_counter + `"><option value="">Choose City</option></select></div><div id="branch_city_error_container_` + vendor_branch_counter + `"></div></div></div><div class="col-md-4"><label class="form-label" for="vendor_branch_pincode_` + vendor_branch_counter + `">Pincode<span class="text-danger"> *</span></label><div class="form-group"><input type="text" name="vendor_branch_pincode[]" id="vendor_branch_pincode_` + vendor_branch_counter + `" class="form-control" placeholder="Pincode" required autocomplete="off"/></div></div><div class="col-md-4"><label class="form-label" for="vendor_branch_gst_type_` + vendor_branch_counter + `">GST Type<span class="text-danger">*</span></label><select id="vendor_branch_gst_type_` + vendor_branch_counter + `" name="vendor_branch_gst_type[]" class="form-control form-select form-add-select_` + vendor_branch_counter + `"><?= getGSTTYPE($vendor_branch_gst_type, 'select') ?></select></div><div class="col-md-4"><label class="form-label" for="vendor_branch_gst_` + vendor_branch_counter + `">GST %<span class="text-danger">*</span></label><div class="form-group"><select id ="vendor_branch_gst_` + vendor_branch_counter + `" name="vendor_branch_gst[]" class ="form-control form-select form-add-select_` + vendor_branch_counter + `" data-parsley-errors-container="#branch_gst_error_container"><?= getGSTDETAILS('', 'select'); ?></select></div><div id="branch_gst_error_container"></div></div><div class="col-md-4"><label class="form-label" for="vendor_branch_address_` + vendor_branch_counter + `">Address<span class="text-danger"> *</span></label><div class="form-group"><textarea id="vendor_branch_address_` + vendor_branch_counter + `" name="vendor_branch_address[]" class="form-control" rows="1" placeholder="Address" required=""></textarea></div><input type="hidden" name="hidden_vendor_branch_id[]" id="hidden_vendor_branch_id_` + vendor_branch_counter + `" value="" hidden><input type="hidden" name="hidden_vendor_ID[]" id="hidden_vendor_ID_` + vendor_branch_counter + `" value="<?= $vendor_ID; ?>" hidden></div><div class = "col d-flex align-items-center justify-content-end"><button type = "button" class = "btn btn-label-danger mt-4 remove_item_btn"><i class = "ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div><div class = "border-bottom border-bottom-dashed my-4"></div></div>`);

					$(".form-add-select_" + vendor_branch_counter).selectize();
					var vendorBranchLocationInput = document.getElementById('vendor_branch_location_' + vendor_branch_counter);

					$("#vendor_branch_gst_type_" + vendor_branch_counter).attr('required', true);
					$("#vendor_branch_country_" + vendor_branch_counter).attr('required', true);
					$("#vendor_branch_state_" + vendor_branch_counter).attr('required', true);
					$("#vendor_branch_city_" + vendor_branch_counter).attr('required', true);
					$("#vendor_branch_gst_" + vendor_branch_counter).attr('required', true);

					//CHECK DUPLICATE VENDOR EMAIL ID
					$('#vendor_branch_emailid_' + vendor_branch_counter).parsley();
					var old_vendor_branch_emailidDETAIL = document.getElementById("old_vendor_branch_emailid_" + vendor_branch_counter).value;
					var vendor_branch_emailid = $('#vendor_branch_emailid_' + vendor_branch_counter).val();
					window.ParsleyValidator.addValidator('check_vendor_email_' + vendor_branch_counter, {
						validateString: function(value) {
							return $.ajax({
								url: 'engine/ajax/__ajax_check_vendor_branch_emailid.php',
								method: "POST",
								data: {
									vendor_branch_emailid: value,
									old_vendor_branch_emailid: old_vendor_branch_emailidDETAIL
								},
								dataType: "json",
								success: function(data) {
									return true;
								}
							});
						}
					});


					<?php if ($total_vendor_branch_add_btn_list_num_rows_count > 0) : ?>
						for (i = <?= $total_vendor_branch_add_btn_list_num_rows_count + 1; ?>; i <= vendor_branch_counter; i++) {
							CHOOSEN_COUNTRY_ADD(i);
							CHOOSEN_STATE_ADD(i);
						}
					<?php else : ?>
						CHOOSEN_COUNTRY_ADD(vendor_branch_counter);
						CHOOSEN_STATE_ADD(vendor_branch_counter);
					<?php endif; ?>
				});

				//AJAX FORM SUBMIT
				$("#form_branch_info").submit(function(event) {
					var form = $('#form_branch_info')[0];
					var data = new FormData(form);
					// $(this).find("button[id='submit_vendor_info_btn']").prop('disabled', true);
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
							if (response.errors.vendor_name_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_email_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Email Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_primary_mobile_number_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Primary Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_alternative_mobile_number_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Alternative Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_country_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_state_required) {
								TOAST_NOTIFICATION('warning', 'Vendor State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_city_required) {
								TOAST_NOTIFICATION('warning', 'Vendor City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_pincode_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_othernumber_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Other Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_address_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_gstin_number_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GSTIN Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_pan_number_required) {
								TOAST_NOTIFICATION('warning', 'Vendor PAN Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_gst_percentage_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GST Percentage Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_country_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GST Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_state_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GST State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_city_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GST City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_pincode_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GST Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_address_required) {
								TOAST_NOTIFICATION('warning', 'Vendor GST Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.vendor_select_role_required) {
								TOAST_NOTIFICATION('warning', 'Vendor Role Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Vendor basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.i_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to create vendor basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Vendor basic info Updated successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to update vendor basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

			/*function branch_code_generate() {
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_generate_code.php?type=show_branch_code',
					type: "post",
					data: {
						vendor_id: '<?= $vendor_id; ?>',
						vendor_branch_id: '<?= $vendor_branch_id; ?>'
					},
					success: function(response) {
						$('#vendor_branch_code').val(response);
					}
				});
			}*/

			/*function branch_code_generate_add(vendor_branch_counter) {
				alert(vendor_branch_counter);
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_generate_code.php?type=show_branch_code',
					type: "post",
					data: {
						vendor_id: '<?= $vendor_id; ?>',
						vendor_branch_id: '',
						vendor_branch_counter: vendor_branch_counter
					},
					success: function(response) {
						$('#vendor_branch_code_' + vendor_branch_counter).val(response);
					}
				});
			}*/

			/*function CHOOSEN_COUNTRY() {
				var state_selectize = $("#vendor_branch_state")[0].selectize;
				var COUNTRY_ID = $('#vendor_branch_country').val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.

						state_selectize.clear();
						state_selectize.clearOptions();
						state_selectize.addOption(response);
						<?php if ($vendor_branch_state) : ?>
							state_selectize.setValue('<?= $vendor_branch_state; ?>');
						<?php endif; ?>
					}
				});
			}

			function CHOOSEN_STATE() {
				var city_selectize = $("#vendor_branch_city")[0].selectize;
				var STATE_ID = $('#vendor_branch_state').val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.
						city_selectize.clear();
						city_selectize.clearOptions();
						city_selectize.addOption(response);
						<?php if ($vendor_branch_city) : ?>
							city_selectize.setValue('<?= $vendor_branch_city; ?>');
						<?php endif; ?>
					}
				});
			}*/

			function CHOOSEN_COUNTRY_ADD(vendor_branch_counter) {
				var state_selectize = $("#vendor_branch_state_" + vendor_branch_counter)[0].selectize;
				var COUNTRY_ID = $('#vendor_branch_country_' + vendor_branch_counter).val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.

						state_selectize.clear();
						state_selectize.clearOptions();
						state_selectize.addOption(response);
						<?php if ($vendor_branch_state) : ?>
							state_selectize.setValue('<?= $vendor_branch_state; ?>');
						<?php endif; ?>
					}
				});
			}

			function CHOOSEN_STATE_ADD(vendor_branch_counter) {
				var city_selectize = $("#vendor_branch_city_" + vendor_branch_counter)[0].selectize;
				var STATE_ID = $('#vendor_branch_state_' + vendor_branch_counter).val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.
						city_selectize.clear();
						city_selectize.clearOptions();
						city_selectize.addOption(response);
						<?php if ($vendor_branch_city) : ?>
							city_selectize.setValue('<?= $vendor_branch_city; ?>');
						<?php endif; ?>
					}
				});
			}

			function deletebranch(vendor_branch_ID, vendor_ID, ROUTE) {
				$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_add_vendor_newform.php?type=delete_branch&vendor_branch_ID=' + vendor_branch_ID + '&vendor_ID=' + vendor_ID + '&ROUTE=' + ROUTE, function() {
					const container = document.getElementById("confirmDELETEINFODATA");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}

			$(document).on('click', '.remove_item_btn', function(e) {
				e.preventDefault();
				let row_item = $(this).parent().parent();
				$(row_item).remove();
				branch_count = $('.branch_heading').length;
				$('.branch_heading').each(function(index, element) {
					$(this).html('Branch #' + branch_count);
					branch_count--;
				});
			});
		</script>

	<?php
	elseif ($_GET['type'] == 'driver_cost') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=add&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'javascript:;';
			$vehicle_info_url = 'javascript:;';
			$permit_cost_info_url = 'javascript:;';
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
	?>
		<!-- Default Wizard -->
		<div class="row">
			<div class="col-12">
				<div id="wizard-validation" class="bs-stepper mt-2">
					<div class="bs-stepper-header border-0 justify-content-center py-2">
						<div class="step" data-target="#basic_info">
							<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle active-stepper">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_Pricebook">
							<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Pricebook</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Permit Cost
									</h5>
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
					<div class="nav-align-top mb-4">
						<ul class="nav nav-tabs" role="tablist">
							<li class="nav-item">
								<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="true">Driver Cost</button>
							</li>
							<li class="nav-item">
								<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="false">Outstation KM Limit</button>
							</li>
							<li class="nav-item">
								<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-messages" aria-controls="navs-top-messages" aria-selected="false">Local KM Limit</button>
							</li>
						</ul>
						<div class="tab-content px-0 pb-0">
							<div class="tab-pane fade show active" id="navs-top-home" role="tabpanel">
								<form onSubmit="return false">
									<div id="vehicle_info" class="content active dstepper-block">
										<div id="">
											<div class="content-header pb-3 d-flex justify-content-between">
												<div class="col-md-auto">
													<h5 class="card-title mb-3 mt-2">List of Vehicle Type - Driver Cost</h5>
												</div>
												<div class="col-md-auto text-end">
													<a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showVEHICLETYPEDRIVERCOSTMODAL('0','<?= $vendor_ID; ?>');" data-bs-dismiss="modal">+ Add Vehicle Type - Driver Cost</a>
												</div>
											</div>

											<div class="row g-3">
												<div class="col-12 mb-3">
													<div class="text-nowrap table-responsive table-bordered">
														<table class="table table-hover " id="vehicle_type_driver_cost_LIST">
															<thead>
																<tr>
																	<th scope="col">S.No</th>
																	<th scope="col">Action</th>
																	<th scope="col">Vehicle</br> Type</th>
																	<th scope="col">Driver</br> Bhatta(₹)</th>
																	<th scope="col">Food</br> Cost(₹)</th>
																	<th scope="col">Accomdation</br> Cost(₹)</th>
																	<th scope="col">Extra</br> Cost(₹)</th>
																	<th scope="col">Morning</br> Charges(₹)</th>
																	<th scope="col">Evening </br>Charges(₹)</th>
																</tr>
															</thead>
														</table>
													</div>
												</div>

												<div class="col-12 d-flex justify-content-between">
													<a href="<?= $branch_info_url; ?>" class="btn btn-secondary btn-prev">
														Back
													</a>
													<a onclick="checkVehicleType()" href="javascript:void(0);" class="btn btn-primary btn-next text-white"> <?= $button_label ?> </a>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
								<div class="row">
									<div class="col-md-12">
										<div class=" p-0">
											<div class="pb-3 d-flex justify-content-between">
												<h5 class="card-title mb-3">List of Outstation KM Limit</h5>
												<?php //if ($kmlimit_exist == 0) : 
												?>
												<a id="btn_add_kmlimit" href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showKMSLIMITMODAL('0','<?= $vendor_ID; ?>');" data-bs-dismiss="modal">+ Add Outstation KM Limit</a>
												<?php //endif; 
												?>
											</div>
											<div class="dataTable_select text-nowrap">
												<div class="text-nowrap overflow-hidden table-bordered">
													<table class="table table-hover border" id="kms_limit_LIST">
														<thead class="border">
															<tr>
																<th scope="col">S.No</th>
																<th scope="col">Action</th>
																<th scope="col">Vendor</th>
																<th scope="col">Vehicle Type</th>
																<th scope="col">Outstation KM Limit Title</th>
																<th scope="col">Outstation KM Limit</th>
																<th scope="col">Status</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
									</div>

									<script>
										$(document).ready(function() {
											$('#kms_limit_LIST').DataTable({
												dom: 'Blfrtip',
												"bFilter": true,
												buttons: [{
														extend: 'copy',
														text: window.copyButtonTrans,
														exportOptions: {
															columns: [0, 1, 2, 5], // Only name, email and role
														}
													},
													{
														extend: 'excel',
														text: window.excelButtonTrans,
														exportOptions: {
															columns: [0, 1, 2, 5], // Only name, email and role
														}
													},
													{
														extend: 'csv',
														text: window.csvButtonTrans,
														exportOptions: {
															columns: [0, 1, 2, 5], // Only name, email and role
														}
													}
												],
												initComplete: function() {
													$('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

													$('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

													$('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');


												},
												ajax: {
													"url": "engine/json/__JSONkmslimit.php?ID=<?= $vendor_ID; ?>",
													"type": "GET"
												},
												columns: [{
														data: "count"
													}, //0
													{
														data: "modify"
													}, //1
													{
														data: "vendor_name"
													}, //2
													{
														data: "vehicle_type"
													}, //3
													{
														data: "kms_limit_title"
													}, //4
													{
														data: "kms_limit"
													}, //5
													{
														data: "status"
													} //6
												],
												columnDefs: [{
													"targets": 6,
													"data": "status",
													"render": function(data, type, row, full) {
														switch (data) {
															case '1':
																return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
																break;
															case '0':
																return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input"  onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
																break;
														}
													}
												}, {
													"targets": 1,
													"data": "modify",
													"render": function(data, type, row, full) {
														return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" href="javascript:void(0);" onclick="showKMSLIMITMODAL(' + data + ',' + row.vendor_id + ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEKMSLIMITMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
													}
												}],
											});
										});

										//ADD & DELETE MODAL
										function showKMSLIMITMODAL(KMS_LIMIT_ID, VENDOR_ID) {
											$('.receiving-kms-limit-form-data').load('engine/ajax/__ajax_add_kmslimit.php?type=show_form&KMS_LIMIT_ID=' + KMS_LIMIT_ID + '&VENDOR_ID=' + VENDOR_ID, function() {
												const container = document.getElementById("addKMSLIMITFORM");
												const modal = new bootstrap.Modal(container);
												modal.show();
												if (KMS_LIMIT_ID) {
													$('#KMSLIMITFORMLabel').html('Update Outstation KM Limit');
												} else {
													$('#KMSLIMITFORMLabel').html('Add Outstation KM Limit');
												}
											});
										}

										//SHOW DELETE POPUP
										function showDELETEKMSLIMITMODAL(ID, VENDOR_ID) {
											$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_kmslimit.php?type=delete&ID=' + ID + '&VENDOR_ID=' + VENDOR_ID, function() {
												const container = document.getElementById("confirmDELETEINFODATA");
												const modal = new bootstrap.Modal(container);
												modal.show();
											});
										}
										//CONFIRM DELETE POPUP
										function confirmKMSLIMITDELETE(ID, VENDOR_ID) {
											$.ajax({
												type: "POST",
												url: "engine/ajax/__ajax_manage_kmslimit.php?type=confirmdelete",
												data: {
													_ID: ID,
													VENDOR_ID: VENDOR_ID
												},
												dataType: 'json',
												success: function(response) {
													if (response.result == true) {
														$('#kms_limit_LIST').DataTable().ajax.reload();
														$('#confirmDELETEINFODATA').modal('hide');
														TOAST_NOTIFICATION('success', 'Delete Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
													} else {
														TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
													}
												}
											});
										}
										//STATUS UPDATE
										function togglestatusITEM(STATUS_ID, KMS_LIMIT_ID) {
											if (KMS_LIMIT_ID) {
												$.ajax({
													type: "POST",
													url: "engine/ajax/__ajax_manage_kmslimit.php?type=updatestatus",
													data: {
														KMS_LIMIT_ID: KMS_LIMIT_ID,
														STATUS_ID: STATUS_ID
													},
													dataType: 'json',
													success: function(response) {
														if (response.result == true) {
															$('#kms_limit_LIST').DataTable().ajax.reload();
															TOAST_NOTIFICATION('success', 'Status Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
														} else {
															TOAST_NOTIFICATION('error', 'Unable to Update the Sttaus', 'Error !!!', '', '', '', '', '', '', '', '', '');
														}
													}
												});
											}
										}
									</script>
								</div>
							</div>
							<div class="tab-pane fade" id="navs-top-messages" role="tabpanel">
								<div class="row">
									<div class="col-md-12">
										<div class="p-0">
											<div class="pb-3 d-flex justify-content-between">
												<h5 class="card-title mb-3">List of Local KM Limit</h5>

												<a id="btn_limelimit" href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showTIMELIMITMODAL('0','<?= $vendor_ID; ?>');" data-bs-dismiss="modal">+ Add Local KM Limit</a>

											</div>
											<div class="dataTable_select text-nowrap">
												<div class="text-nowrap overflow-hidden table-bordered">
													<table class="table table-hover border" id="time_limit_LIST">
														<thead class="border">
															<tr>
																<th scope="col">S.No</th>
																<th scope="col">Action</th>
																<th scope="col">Vendor </th>
																<th scope="col">Vehicle Type</th>
																<th scope="col">Title</th>
																<th scope="col">Hours</th>
																<th scope="col">KM</th>
																<th scope="col">Status</th>
															</tr>
														</thead>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<script>
									$(document).ready(function() {
										$('#time_limit_LIST').DataTable({
											dom: 'Blfrtip',
											"bFilter": true,
											buttons: [{
													extend: 'copy',
													text: window.copyButtonTrans,
													exportOptions: {
														columns: [0, 1, 2, 5], // Only name, email and role
													}
												},
												{
													extend: 'excel',
													text: window.excelButtonTrans,
													exportOptions: {
														columns: [0, 1, 2, 5], // Only name, email and role
													}
												},
												{
													extend: 'csv',
													text: window.csvButtonTrans,
													exportOptions: {
														columns: [0, 1, 2, 5], // Only name, email and role
													}
												}
											],
											initComplete: function() {
												$('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

												$('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

												$('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');
											},
											ajax: {
												"url": "engine/json/__JSONtimelimit.php?ID=<?= $vendor_ID; ?>",
												"type": "GET"
											},
											columns: [{
													data: "count"
												}, //0
												{
													data: "modify"
												}, //1
												{
													data: "vendor_name"
												}, //2
												{
													data: "vehicle_type"
												}, //3
												{
													data: "time_limit_title"
												}, //4
												{
													data: "hours_limit"
												}, //5
												{
													data: "km_limit"
												}, //6
												{
													data: "status"
												} //7
											],
											columnDefs: [{
												"targets": 7,
												"data": "status",
												"render": function(data, type, row, full) {
													switch (data) {
														case '1':
															return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input" checked onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
															break;
														case '0':
															return '<div class="media-body text-start switch-sm"><label class="switch mt-0"><input type="checkbox" class="switch-input"  onChange="togglestatusITEM(' + data + ',' + row.modify + ');"><span class="switch-toggle-slider"><span class="switch-on"></span></span></label></div>';
															break;
													}
												}
											}, {
												"targets": 1,
												"data": "modify",
												"render": function(data, type, row, full) {
													return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" href="javascript:void(0);" onclick="showTIMELIMITMODAL(' + data + ',' + row.vendor_id + ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETETIMELIMITMODAL(' + data + ',' + row.vendor_id + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a></div>';
												}
											}],
										});
									});

									//ADD & DELETE MODAL
									function showTIMELIMITMODAL(TIME_LIMIT_ID, VENDOR_ID) {
										$('.receiving-time-limit-form-data').load('engine/ajax/__ajax_add_timelimit.php?type=show_form&TIME_LIMIT_ID=' + TIME_LIMIT_ID + '&VENDOR_ID=' + VENDOR_ID, function() {
											const container = document.getElementById("addTIMELIMITFORM");
											const modal = new bootstrap.Modal(container);
											modal.show();
											if (TIME_LIMIT_ID) {
												$('#TIMELIMITFORMLabel').html('Update Local KM Limit');
											} else {
												$('#TIMELIMITFORMLabel').html('Add Local KM Limit');
											}
										});
									}

									//SHOW DELETE POPUP
									function showDELETETIMELIMITMODAL(ID, VENDOR_ID) {
										$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_timelimit.php?type=delete&ID=' + ID + '&VENDOR_ID=' + VENDOR_ID, function() {
											const container = document.getElementById("confirmDELETEINFODATA");
											const modal = new bootstrap.Modal(container);
											modal.show();
										});
									}
									//CONFIRM DELETE POPUP
									function confirmTIMELIMITDELETE(ID, VENDOR_ID) {
										$.ajax({
											type: "POST",
											url: "engine/ajax/__ajax_manage_timelimit.php?type=confirmdelete",
											data: {
												_ID: ID,
												VENDOR_ID: VENDOR_ID
											},
											dataType: 'json',
											success: function(response) {
												if (response.result == true) {
													$('#time_limit_LIST').DataTable().ajax.reload();
													$('#confirmDELETEINFODATA').modal('hide');
													TOAST_NOTIFICATION('success', 'Delete Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
												} else {
													TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
												}
											}
										});
									}
								</script>
							</div>
						</div>
					</div>
					<div id="show_add_vehicle" class="col-md-12"></div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="addVEHICLEDRIVERCOSTFORM" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
				<div class="modal-content p-3 p-md-5">
					<div class="receiving-vehicle-type-form-data">
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				</div>
			</div>
		</div>

		<!--  DELETE VEHICLE TYPE MODAL -->
		<div class="modal fade" id="confirmDELETEVEHICLETYPEINFODATA" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
				<div class="modal-content p-0">
					<div class="modal-body receiving-confirm-delete-vehicle-type-form-data">
					</div>
				</div>
			</div>
		</div>

		<script>
			var table;
			$(document).ready(function() {
				table = $('#vehicle_type_driver_cost_LIST').DataTable({
					dom: 'Blfrtip',
					"bFilter": true,
					buttons: [{
							extend: 'copy',
							text: window.copyButtonTrans,
							exportOptions: {
								columns: [0, 1, 2, 3, 4, 5], // Only name, email and role
							}
						},
						{
							extend: 'excel',
							text: window.excelButtonTrans,
							exportOptions: {
								columns: [0, 1, 2, 3, 4, 5], // Only name, email and role
							}
						},
						{
							extend: 'csv',
							text: window.csvButtonTrans,
							exportOptions: {
								columns: [0, 1, 2, 3, 4, 5], // Only name, email and role
							}
						}
					],
					initComplete: function() {
						$('.buttons-copy').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-primary"><svg class="me-2" id="copy2" xmlns="http://www.w3.org/2000/svg" width="13.917" height="16" viewBox="0 0 13.917 16"><path id="Path_4697" data-name="Path 4697" d="M138.078,247.423q0-2.022,0-4.044a2.151,2.151,0,0,1,.656-1.655,2.033,2.033,0,0,1,1.381-.562c.422-.011.845-.006,1.267,0,.126,0,.171-.039.169-.168-.006-.39,0-.78,0-1.169a2.063,2.063,0,0,1,2.1-2.133q3.118-.016,6.237,0a2.055,2.055,0,0,1,2.1,2.093q.017,4.166,0,8.332a2.056,2.056,0,0,1-2.129,2.09c-.39,0-.78,0-1.169,0-.126,0-.172.039-.17.167.006.39,0,.78,0,1.169a2.063,2.063,0,0,1-2.1,2.133q-3.118.017-6.237,0a2.066,2.066,0,0,1-2.1-2.126C138.073,250.173,138.078,248.8,138.078,247.423Zm1.436-.009q0,2.062,0,4.124a.617.617,0,0,0,.7.7q3.093,0,6.186,0a.615.615,0,0,0,.657-.421,1.122,1.122,0,0,0,.048-.336q0-4.075,0-8.151a.671.671,0,0,0-.749-.757q-3.052,0-6.1,0a1.163,1.163,0,0,0-.273.035.612.612,0,0,0-.458.661Q139.512,245.344,139.514,247.414Zm11.039-3.453q0-2.054,0-4.109c0-.5-.222-.727-.721-.728q-3.061,0-6.122,0a.656.656,0,0,0-.743.751c0,.357,0,.715,0,1.072,0,.211,0,.212.217.212q1.624,0,3.248,0a2.042,2.042,0,0,1,1.1.3,2,2,0,0,1,.987,1.777c.011,1.786.005,3.573,0,5.359,0,.146.038.2.191.2.362-.01.725,0,1.088,0a1.113,1.113,0,0,0,.336-.048.615.615,0,0,0,.421-.657Q150.554,246.023,150.553,243.961Z" transform="translate(-138.076 -237.684)" fill="currentColor"/></svg>Copy</a>');

						$('.buttons-csv').html('<a href="javascript:;" class="d-flex align-items-center  btn btn-sm btn-outline-secondary"><svg class="me-2" id="CSV" xmlns="http://www.w3.org/2000/svg" width="16" height="16.003" viewBox="0 0 16 16.003"><path id="Path_4683" data-name="Path 4683" d="M121.613,246.015H107.873a1.5,1.5,0,0,1-1.124-1.124v-6.183a1.554,1.554,0,0,1,.557-.861,1.621,1.621,0,0,1,1.095-.277c.24.01.24,0,.24-.24q0-2.911,0-5.822a1.758,1.758,0,0,1,.018-.326,1.405,1.405,0,0,1,1.416-1.165c2.138,0,4.277,0,6.415,0a.739.739,0,0,1,.567.235q1.766,1.777,3.543,3.543a.766.766,0,0,1,.246.594c-.01.994,0,1.988,0,2.981,0,.2,0,.207.212.208h.312a1.4,1.4,0,0,1,1.378,1.374c0,1.9,0,3.8,0,5.7a1.325,1.325,0,0,1-.14.586A1.476,1.476,0,0,1,121.613,246.015Zm-6.886-.949h6.461c.428,0,.6-.169.6-.593q0-2.669,0-5.338c0-.436-.167-.6-.607-.6H108.305c-.439,0-.607.166-.607.6q0,2.661,0,5.322c0,.446.165.61.614.61Zm.017-7.494h4.9c.238,0,.238,0,.238-.244q0-1.2,0-2.4c0-.2,0-.2-.2-.2-.7,0-1.4,0-2.107,0a1.4,1.4,0,0,1-1.436-1.443c0-.692,0-1.384,0-2.076,0-.227,0-.228-.223-.228H110.2c-.427,0-.6.169-.6.6q0,2.887,0,5.774c0,.225,0,.226.225.226Zm2.353-5.863c0,.508,0,1.007,0,1.506a.488.488,0,0,0,.552.547q.687,0,1.374,0c.042,0,.093.022.116-.011Z" transform="translate(-106.749 -230.012)" fill="currentColor"/><path id="Path_4684" data-name="Path 4684" d="M175.471,458.453c0,.293,0,.586,0,.879a.45.45,0,0,0,.252.419.4.4,0,0,0,.43-.031.518.518,0,0,0,.206-.418.467.467,0,0,1,.923-.018,1.079,1.079,0,0,1-.022.376,1.378,1.378,0,0,1-2.725-.292c0-.627,0-1.253,0-1.88a1.377,1.377,0,0,1,2.752.012.468.468,0,1,1-.934.055.456.456,0,0,0-.355-.437.428.428,0,0,0-.447.184.546.546,0,0,0-.084.317c0,.278,0,.556,0,.834Z" transform="translate(-171.69 -446.545)" fill="currentColor"/><path id="Path_4685" data-name="Path 4685" d="M265.629,456.143a1.319,1.319,0,0,1,.924.358.483.483,0,0,1,.071.679.46.46,0,0,1-.677.042.441.441,0,1,0-.277.742,1.336,1.336,0,0,1,1.025.511,1.38,1.38,0,0,1-1.977,1.911.492.492,0,0,1-.1-.7.476.476,0,0,1,.7-.036.437.437,0,0,0,.737-.246c.052-.263-.169-.491-.487-.508a1.321,1.321,0,0,1-1.169-.745A1.373,1.373,0,0,1,265.629,456.143Z" transform="translate(-257.627 -446.524)" fill="currentColor"/><path id="Path_4686" data-name="Path 4686" d="M355.585,458.164l.365-1.453c.021-.083.04-.167.063-.25a.478.478,0,0,1,.573-.368.473.473,0,0,1,.343.588c-.061.271-.133.54-.2.809q-.346,1.382-.693,2.764a.474.474,0,0,1-.935.014c-.214-.842-.424-1.685-.635-2.528-.088-.353-.18-.705-.263-1.059a.471.471,0,0,1,.745-.5.515.515,0,0,1,.176.293q.192.772.388,1.544c.012.048.027.1.04.144Z" transform="translate(-343.803 -446.463)" fill="currentColor"/></svg>CSV</a>');

						$('.buttons-excel').html('<a href="javascript:;" class="d-flex align-items-center btn btn-sm btn-outline-success"><svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><g id="Group_4245" data-name="Group 4245" transform="translate(0 0.001)"><path id="Path_4699" data-name="Path 4699" d="M93.8,243.992V231.943a.678.678,0,0,1,.562-.412q3.142-.621,6.283-1.253.743-.148,1.487-.3c.405-.08.671.155.673.594,0,.355,0,.71,0,1.065,0,.456,0,.456.43.456q2.989,0,5.978,0a.558.558,0,0,1,.443.163.648.648,0,0,1,.145.475q0,5.232,0,10.464c0,.044,0,.089,0,.133a.528.528,0,0,1-.279.449.606.606,0,0,1-.319.059h-6.149c-.246,0-.246,0-.246.269,0,.421,0,.843,0,1.264a.53.53,0,0,1-.656.583c-.113-.021-.225-.044-.337-.066q-3.731-.746-7.463-1.489A.67.67,0,0,1,93.8,243.992Zm7.981-6.023q0-3.286,0-6.573c0-.208-.007-.216-.191-.179q-3.3.656-6.591,1.31c-.162.032-.19.117-.19.272q.005,5.167,0,10.333c0,.236,0,.236.215.278l1.625.322,4.9.979c.224.045.225.04.225-.2Q101.779,241.239,101.779,237.969Zm6.994.007q0-2.271,0-4.543c0-.245,0-.246-.237-.246h-4.012c-.51,0-1.02.005-1.53,0-.153,0-.2.054-.195.213.01.21.01.422,0,.632-.007.155.049.2.191.2.416-.008.833,0,1.249,0a.691.691,0,0,1,.2.023.54.54,0,0,1,.357.606.512.512,0,0,1-.483.457c-.437.007-.874,0-1.311,0-.194,0-.2.006-.2.219s.006.422,0,.632c-.006.148.05.192.184.19.421-.006.843,0,1.264,0a.545.545,0,1,1-.006,1.09c-.421,0-.843,0-1.264,0-.125,0-.181.039-.177.18.007.227.007.455,0,.682,0,.141.051.182.176.181.421-.005.843,0,1.264,0a.686.686,0,0,1,.2.024.54.54,0,0,1,.355.607.512.512,0,0,1-.485.456q-.663.01-1.327,0c-.133,0-.19.041-.184.19.008.216.011.433,0,.649-.009.167.056.208.2.206.411-.008.822,0,1.233,0a.718.718,0,0,1,.2.021.54.54,0,0,1,.362.6.514.514,0,0,1-.494.463q-.663.009-1.327,0c-.127,0-.18.043-.175.182.007.2,0,.41,0,.616,0,.243,0,.243.223.243h5.526c.221,0,.221,0,.221-.245Q108.774,240.239,108.773,237.976Z" transform="translate(-93.798 -229.969)" fill="currentColor"/><path id="Path_4700" data-name="Path 4700" d="M157.743,350.819a.547.547,0,0,1-.416-.868c.2-.278.418-.547.629-.819.242-.312.478-.627.729-.932a.208.208,0,0,0-.007-.325c-.427-.475-.843-.96-1.266-1.438a.6.6,0,0,1-.168-.58.512.512,0,0,1,.4-.385.544.544,0,0,1,.556.184q.457.519.912,1.04l.252.289c.138.159.139.16.265,0q.691-.887,1.381-1.776a.617.617,0,0,1,.418-.277.547.547,0,0,1,.524.861c-.175.243-.364.477-.548.714-.347.448-.691.9-1.046,1.34a.191.191,0,0,0,.014.3c.5.56.99,1.126,1.485,1.69a.676.676,0,0,1,.193.361.548.548,0,0,1-.947.45c-.238-.256-.465-.523-.7-.786-.249-.284-.5-.565-.744-.855-.087-.1-.134-.093-.212.009-.395.516-.8,1.027-1.194,1.541A.6.6,0,0,1,157.743,350.819Z" transform="translate(-154.805 -340.139)" fill="currentColor"/></g></svg>Excel</a>');
					},
					ajax: {
						"url": "engine/json/__JSONvehicle_type_driver_cost.php?vendor_id=<?= $vendor_ID; ?>",
						"type": "GET"
					},
					columns: [{
							data: "count"
						}, //0
						{
							data: "modify"
						}, //1
						{
							data: "vehicle_type"
						}, //2
						{
							data: "driver_bhatta"
						}, //3
						{
							data: "food_cost"
						}, //4
						{
							data: "accomdation_cost"
						}, //5
						{
							data: "extra_cost"
						}, //6
						{
							data: "driver_early_morning_charges"
						}, //7
						{
							data: "driver_evening_charges"
						} //8	 
					],
					columnDefs: [{
						"targets": 1,
						"data": "modify",
						"render": function(data, type, row, full) {
							return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" href="javascript:void(0);" onclick="showVEHICLETYPEDRIVERCOSTMODAL(' + data + ',' + row.vendor_id + ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEVEHICLETYPEMODAL(' + data + ',' + row.vendor_id + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
						}
					}],
				});
			});

			function checkVehicleType() {
				var vehicleTypeAdded = table.rows().count();
				if (vehicleTypeAdded > 0) {
					window.location.href = "newvendor.php?route=<?= $ROUTE; ?>&formtype=vehicle_info&id=<?= $vendor_ID; ?>";
				} else {
					TOAST_NOTIFICATION('warning', 'Please add the vehicle type first', 'Warning !!!', '', '', '', '', '', '', '', '', '');
					return false;
				}
			}

			//ADD & EDIT MODAL
			function showVEHICLETYPEDRIVERCOSTMODAL(VENDOR_VEHICLE_TYPE_ID, VENDOR_ID) {
				$('.receiving-vehicle-type-form-data').load('engine/ajax/__ajax_add_vendor_vehicle_type.php?type=show_form&VENDOR_VEHICLE_TYPE_ID=' + VENDOR_VEHICLE_TYPE_ID + '&VENDOR_ID=' + VENDOR_ID + '', function() {
					const container = document.getElementById("addVEHICLEDRIVERCOSTFORM");
					const modal = new bootstrap.Modal(container);
					modal.show();
					$('#VEHICLEDRIVERCOSTFORMLabel').html('Vehicle Type - Driver Cost');
				});
			}

			//DELETE MODAL
			function showDELETEVEHICLETYPEMODAL(ID, VENDOR_ID) {
				$('.receiving-confirm-delete-vehicle-type-form-data').load('engine/ajax/__ajax_add_vendor_vehicle_type.php?type=delete_vehicle_type&ID=' + ID + '&VENDOR_ID=' + VENDOR_ID, function() {
					const container = document.getElementById("confirmDELETEVEHICLETYPEINFODATA");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}
			//CONFIRM DELETE
			function confirmVEHICLETYPEDELETE(ID, VENDOR_ID) {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_add_vendor_vehicle_type.php?type=confirmdelete",
					data: {
						_ID: ID,
						VENDOR_ID: VENDOR_ID
					},
					dataType: 'json',
					success: function(response) {
						if (!response.success) {
							//NOT SUCCESS RESPONSE
							if (response.result_error) {
								TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.success == true) {
								$('#confirmDELETEVEHICLETYPEINFODATA').modal('hide');
								TOAST_NOTIFICATION('success', 'Deleted Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							}
							$('#vehicle_type_driver_cost_LIST').DataTable().ajax.reload();
						}
					}
				});
			}
		</script>
	<?php
	elseif ($_GET['type'] == 'vehicle_pricebook') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=add&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'javascript:;';
			$permit_cost_info_url = 'javascript:;';
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
	?>
		<!-- Default Wizard -->
		<div class="row">
			<div class="col-12">
				<div id="wizard-validation" class="bs-stepper mt-2">
					<div class="bs-stepper-header border-0 justify-content-center py-2">
						<div class="step" data-target="#basic_info">
							<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_Pricebook">
							<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_vendor bs-stepper-circle active-stepper">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title">Vehicle Pricebook</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Permit Cost
									</h5>
								</span>
							</a>
						</div>

					</div>
				</div>
			</div>
		</div>

		<?php
		$select_vendor_list_query = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_margin`, `vendor_margin_gst_type`, `vendor_margin_gst_percentage` FROM `dvi_vendor_details` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_LIST:" . sqlERROR_LABEL());
		while ($fetch_vendor_list_data = sqlFETCHARRAY_LABEL($select_vendor_list_query)) :
			$vendor_id = $fetch_vendor_list_data["vendor_id"];
			$vendor_margin = $fetch_vendor_list_data["vendor_margin"];
			$vendor_margin_gst_type = $fetch_vendor_list_data["vendor_margin_gst_type"];
			$vendor_margin_gst_percentage = $fetch_vendor_list_data['vendor_margin_gst_percentage'];
		endwhile;
		?>

		<div>
			<!-- VENDOR MARGIN DETAILS -->
			<?php if ($logged_user_level == 1): ?>
				<div class="row mt-3">
					<div class="col-md-12">
						<div class="card p-4">
							<div class="row">
								<div class="d-flex align-items-center justify-content-between mb-2">
									<h5 class="m-0">Vendor Margin Details</h5>
									<input type="hidden" id="vendor_id" value="<?= $vendor_ID; ?>" />
									<button type="button" class="btn btn-primary btn-md" onclick="updateVENDORMARGINDETAILS();">Update</button>
								</div>
								<div class="col-md-3">
									<label class="form-label" for="vendor_margin">Vendor Margin %</label>
									<div class="form-group">
										<input type="text" name="vendor_margin" id="vendor_margin" class="form-control" placeholder="Vendor Margin" required value="<?= $vendor_margin; ?>" />
									</div>
								</div>
								<div class="col-md-3">
									<label class="form-label" for="vendor_margin_gst_type">Vendor Margin GST Type</label>
									<select id="vendor_margin_gst_type" name="vendor_margin_gst_type" class="form-control form-select" required>
										<?= getGSTTYPE($vendor_margin_gst_type, 'select') ?>
									</select>
								</div>
								<div class="col-md-3">
									<label class="form-label" for="vendor_margin_gst_percentage">Vendor Margin GST Percentage</label>
									<div class="form-group">
										<select id="vendor_margin_gst_percentage" name="vendor_margin_gst_percentage" class="form-control form-select" required>
											<?= getGSTDETAILS($vendor_margin_gst_percentage, 'select'); ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php else: ?>
				<input type="hidden" name="vendor_margin" id="vendor_margin" value="<?= $vendor_margin; ?>" hidden>
				<input type="hidden" name="vendor_margin_gst_type" id="vendor_margin_gst_type" value="<?= $vendor_margin_gst_type; ?>" hidden>
				<input type="hidden" name="vendor_margin_gst_percentage" id="vendor_margin_gst_percentage" value="<?= $vendor_margin_gst_percentage; ?>" hidden>
			<?php endif; ?>

			<!-- DRIVER COST DETAILS -->
			<div class="row mt-3">
				<div class="col-md-12">
					<div class="card p-4">
						<div class="d-flex align-items-center justify-content-between mb-2">
							<h5 class="m-0">Driver Cost Details</h5>
							<button type="submit" form="updateDriverCostForm" class="btn btn-primary btn-md">Update</button>
						</div>
						<form id="updateDriverCostForm">
							<div class="dataTable_select text-nowrap">
								<div class="text-nowrap table-responsive table-bordered">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">Vehicle Type</th>
												<th scope="col">Driver Cost(₹)</th>
												<th scope="col">Food Cost(₹)</th>
												<th scope="col">Accommodation Cost(₹)</th>
												<th scope="col">Extra Cost(₹)</th>
												<th scope="col">Morning Charge(₹)</th>
												<th scope="col">Evening Charge(₹)</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$select_vendor_vehicle_type_details = sqlQUERY_LABEL("SELECT VEHICLE_TYPE.`vehicle_type_title`, VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID`, VENDOR_VEHICLE_TYPE.`vehicle_type_id`, VENDOR_VEHICLE_TYPE.`driver_batta`, VENDOR_VEHICLE_TYPE.`food_cost`, VENDOR_VEHICLE_TYPE.`accomodation_cost`, VENDOR_VEHICLE_TYPE.`extra_cost`, VENDOR_VEHICLE_TYPE.`driver_early_morning_charges`, VENDOR_VEHICLE_TYPE.`driver_evening_charges` FROM `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` WHERE VENDOR_VEHICLE_TYPE.`deleted` = '0' AND VENDOR_VEHICLE_TYPE.`status` = '1' AND VENDOR_VEHICLE_TYPE.`vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_VEHICLE_TYPE_DETAILS:" . sqlERROR_LABEL());
											if (sqlNUMOFROW_LABEL($select_vendor_vehicle_type_details) > 0) :
												while ($fetch_vendor_vehicle_type_data = sqlFETCHARRAY_LABEL($select_vendor_vehicle_type_details)) :
													$vehicle_type_title = $fetch_vendor_vehicle_type_data["vehicle_type_title"];
													$vendor_vehicle_type_ID = $fetch_vendor_vehicle_type_data["vendor_vehicle_type_ID"];
													$driver_batta = $fetch_vendor_vehicle_type_data["driver_batta"];
													$food_cost = $fetch_vendor_vehicle_type_data["food_cost"];
													$accomodation_cost = $fetch_vendor_vehicle_type_data["accomodation_cost"];
													$extra_cost = $fetch_vendor_vehicle_type_data["extra_cost"];
													$driver_early_morning_charges = $fetch_vendor_vehicle_type_data["driver_early_morning_charges"];
													$driver_evening_charges = $fetch_vendor_vehicle_type_data["driver_evening_charges"];
											?>
													<tr>
														<td><?= $vehicle_type_title; ?></td>
														<td><input type="hidden" name="vehicle_type_title[]" value="<?= $vehicle_type_title; ?>"><input type="hidden" name="vendor_vehicle_type_ID[]" value="<?= $vendor_vehicle_type_ID; ?>"><input type="text" name="driver_batta[]" class="form-control" placeholder="Driver Cost" value="<?= $driver_batta; ?>"></td>
														<td><input type="text" name="food_cost[]" class="form-control" placeholder="Food Cost" value="<?= $food_cost; ?>"></td>
														<td><input type="text" name="accomodation_cost[]" class="form-control" placeholder="Accommodation Cost" value="<?= $accomodation_cost; ?>"></td>
														<td><input type="text" name="extra_cost[]" class="form-control" placeholder="Extra Cost" value="<?= $extra_cost; ?>"></td>
														<td><input type="text" name="driver_early_morning_charges[]" class="form-control" placeholder="Morning Charges" value="<?= $driver_early_morning_charges; ?>"></td>
														<td><input type="text" name="driver_evening_charges[]" class="form-control" placeholder="Evening Charges" value="<?= $driver_evening_charges; ?>"></td>
													</tr>
												<?php
												endwhile;
											else : ?>
												<tr>
													<td colspan="7" class="text-center">No more records found.</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<?php
			// Fetch vendor branches
			$select_vendor_branch_list_query = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `deleted` = '0' AND `status` = '1' AND `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_VENDOR_BRANCH_LIST:" . sqlERROR_LABEL());

			$total_vendor_branch_count = sqlNUMOFROW_LABEL($select_vendor_branch_list_query);
			?>

			<!-- EXTRA COST DETAILS -->
			<div class="row">
				<div class="col-12 mt-2">
					<div class="card p-3">
						<div class="row py-2">
							<div class="d-flex align-items-center justify-content-between mb-2">
								<h5 class="m-0">Vehicle Extra Cost Details</h5>
								<button type="button" class="btn btn-primary btn-md" id="update_vehicle_extra_cost_button">Update</button>
							</div>
						</div>
						<div class="row">
							<?php
							if ($total_vendor_branch_count > 0) :
								$branch_counter = 0;
								while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
									$branch_counter++;
									$vendor_branch_id = $fetch_vendor_branch_data["vendor_branch_id"];
									$vendor_branch_name = $fetch_vendor_branch_data["vendor_branch_name"];

									// Fetch vehicle details for each branch
									$select_vehicle_details_query = sqlQUERY_LABEL("SELECT VEHICLE_TYPE.`vehicle_type_title`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`, VEHICLE.`vehicle_type_id`, VEHICLE.`vendor_branch_id`, VEHICLE.`extra_km_charge`, VEHICLE.`extra_hour_charge`, VEHICLE.`early_morning_charges`, VEHICLE.`evening_charges` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE ON VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` = VEHICLE.`vehicle_type_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` WHERE VEHICLE.`vendor_id` = '$vendor_ID' AND VEHICLE.`vendor_branch_id` = '$vendor_branch_id' AND VEHICLE.`deleted` = '0' AND VEHICLE.`status` = '1' GROUP BY VEHICLE.`vehicle_type_id`") or die("#2-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());
									$total_vehicle_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
							?>
									<div class="row">
										<div class="d-flex align-items-center mb-3">
											<h5 class="m-0 text-primary">Branch #<?= $branch_counter; ?> - <?= ucfirst($vendor_branch_name); ?></h5>
										</div>
										<?php
										if ($total_vehicle_count > 0) :
											while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
												$vehicle_id = $fetch_vehicle_data["vehicle_id"];
												$vehicle_type_id = $fetch_vehicle_data["vehicle_type_id"];
												$vehicle_type_title = $fetch_vehicle_data["vehicle_type_title"];
												$extra_km_charge = $fetch_vehicle_data["extra_km_charge"];
												$extra_hour_charge = $fetch_vehicle_data["extra_hour_charge"];
												$early_morning_charges = $fetch_vehicle_data["early_morning_charges"];
												$evening_charges = $fetch_vehicle_data["evening_charges"];
										?>
												<div class="row vehicle-container">
													<input type="hidden" name="vehicle_type_id[]" value="<?= $vehicle_type_id; ?>">
													<input type="hidden" name="vehicle_type_title[]" value="<?= $vehicle_type_title; ?>">
													<input type="hidden" name="vendor_id" value="<?= $vendor_ID; ?>">
													<input type="hidden" name="vendor_branch_id" value="<?= $vendor_branch_id; ?>">
													<div class="col-md-2 mb-2">
														<div class="form-group"><label class="form-label">Vehicle Type</label>
															<div class="form-group">
																<p class="text-primary p-0 my-2"><?= $vehicle_type_title; ?></p>
															</div>
														</div>
													</div>
													<div class="col-md-2 mb-2">
														<div class="form-group"><label class="form-label" for="extra_km_charge_<?= $vehicle_type_id; ?>">Extra KM Charge(₹)</label>
															<div class="form-group">
																<input type="text" id="extra_km_charge_<?= $vehicle_type_id; ?>" name="extra_km_charge[]" class="form-control" value="<?= $extra_km_charge; ?>" placeholder="Enter the Extra KM Charge" autocomplete="off" />
															</div>
														</div>
													</div>

													<div class="col-md-2 mb-2">
														<div class="form-group"><label class="form-label" for="extra_hour_charge_<?= $vehicle_type_id; ?>">Extra Hour Charge(₹)</label>
															<div class="form-group">
																<input type="text" id="extra_hour_charge_<?= $vehicle_type_id; ?>" name="extra_hour_charge[]" class="form-control" value="<?= $extra_hour_charge; ?>" placeholder="Enter the Extra hour Charge" autocomplete="off" />
															</div>
														</div>
													</div>

													<div class="col-md-3 mb-2">
														<div class="form-group"><label class="form-label" for="early_morning_charges_<?= $vehicle_type_id; ?>">Early Morning Charges (₹)(Before 6 AM)</label>
															<div class="form-group">
																<input type="text" id="early_morning_charges_<?= $vehicle_type_id; ?>" name="early_morning_charges[]" class="form-control" value="<?= $early_morning_charges; ?>" placeholder="Enter the Early Morning Charges" autocomplete="off" />
															</div>
														</div>
													</div>
													<div class="col-md-3 mb-2">
														<div class="form-group">
															<label class="form-label" for="evening_charges_<?= $vehicle_type_id; ?>">Evening Charges (₹)(After 8 PM)</label>
															<div class="form-group">
																<input type="text" id="evening_charges_<?= $vehicle_type_id; ?>" name="evening_charges[]" class="form-control" value="<?= $evening_charges; ?>" placeholder="Enter the Evening Charges" autocomplete="off" />
															</div>
														</div>
													</div>
												</div>
											<?php endwhile;
										else : ?>
											<div class="col-12 text-center">
												<h6 class="text-muted">No vehicles found for this branch.</h6>
											</div>
										<?php endif; ?>
									</div>
									<?php if ($branch_counter != $total_vendor_branch_count) : ?>
										<div class="border-bottom border-bottom-dashed my-4"></div>
									<?php endif; ?>
								<?php endwhile;
							else : ?>
								<div class="col-12 text-center">
									<h5 class="text-muted">No branches found!</h5>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<?php
			sqlDATASEEK_LABEL($select_vendor_branch_list_query, 0);
			?>

			<!-- VEHICLE RENTAL COST DETAILS FOR LOCAL -->
			<div class="row">
				<div class="col-12 mt-2">
					<div class="card p-3">
						<div class="row py-2">
							<div class="col-md-6 my-auto">
								<div class="pb-3 d-flex justify-content-between">
									<h5 class="mb-0">Vehicle Rental Cost Details | Local Pricebook</h5>
								</div>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-4">
										<a id="btn_limelimit" href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showTIMELIMITMODAL('0','<?= $vendor_ID; ?>');" data-bs-dismiss="modal">+ Add KM Limit</a>
									</div>
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-8">
												<div class="input-group">
													<input type="text" name="local_pricebook_start_date" id="local_pricebook_start_date" autocomplete="off" required="" class="form-control" placeholder="Start Date">
													<input type="text" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" name="local_pricebook_end_date" id="local_pricebook_end_date" autocomplete="off" required="" class="form-control" placeholder="End Date">
													<span class="calender-icon d-none"><img class="" src="../head/assets/img/svg/calendar.svg"></span>
												</div>
												<div id="local_pricebook_vehicle_date_error" class="invalid-feedback">This field is required</div>
											</div>
											<div class="col-md-4 text-end">
												<button type="button" id="update_local_pricebook" class="btn btn-primary btn-md">Update</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" id="vehiclesContainer">
							<?php
							if ($total_vendor_branch_count > 0) :
								$branch_counter = 0;
								while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
									$branch_counter++;
									$vendor_branch_id = $fetch_vendor_branch_data["vendor_branch_id"];
									$vendor_branch_name = $fetch_vendor_branch_data["vendor_branch_name"];

									// Fetch vehicle details for each branch

									$select_vehicle_details_query = sqlQUERY_LABEL("SELECT 
									TIME_LIMIT.time_limit_id,
									TIME_LIMIT.time_limit_title,
									VEHICLE_TYPE.vehicle_type_title,
									VEHICLE.vehicle_id,
									VEHICLE.vendor_id,
									VEHICLE.vehicle_type_id,
									VEHICLE.vendor_branch_id,
									VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID
								FROM 
									dvi_vendor_vehicle_types VENDOR_VEHICLE_TYPE
								LEFT JOIN 
									(SELECT MIN(vehicle_id) AS vehicle_id, vendor_id, vehicle_type_id, vendor_branch_id
									FROM dvi_vehicle
									WHERE vendor_id = '$vendor_ID' 
									AND vendor_branch_id = '$vendor_branch_id' 
									AND deleted = '0' 
									AND status = '1'
									GROUP BY vehicle_type_id, vendor_id, vendor_branch_id) VEHICLE 
									ON VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID = VEHICLE.vehicle_type_id
								LEFT JOIN 
									dvi_vehicle_type VEHICLE_TYPE 
									ON VEHICLE_TYPE.vehicle_type_id = VENDOR_VEHICLE_TYPE.vehicle_type_id
								LEFT JOIN 
									dvi_time_limit TIME_LIMIT 
									ON TIME_LIMIT.vendor_id = VENDOR_VEHICLE_TYPE.vendor_id 
									AND TIME_LIMIT.vendor_vehicle_type_id = VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID 
								WHERE 
									VENDOR_VEHICLE_TYPE.vendor_id = '$vendor_ID' 
									AND VENDOR_VEHICLE_TYPE.deleted = '0' 
									AND VENDOR_VEHICLE_TYPE.status = '1' 
									AND TIME_LIMIT.time_limit_id IS NOT NULL ORDER BY VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID ASC") or die("#2-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());

									$total_vehicle_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
							?>
									<div class="row">
										<div class="d-flex align-items-center mb-3">
											<h5 class="m-0 text-primary">Branch #<?= $branch_counter; ?> - <?= ucfirst($vendor_branch_name); ?></h5>
										</div>
										<?php
										if ($total_vehicle_count > 0) :
											while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
												$vehicle_counter++;
												$vehicle_id = $fetch_vehicle_data["vehicle_id"];
												$time_limit_id = $fetch_vehicle_data["time_limit_id"];
												$time_limit_title = $fetch_vehicle_data["time_limit_title"];
												$vehicle_type_title = $fetch_vehicle_data["vehicle_type_title"];
												$vendor_vehicle_type_ID = $fetch_vehicle_data["vendor_vehicle_type_ID"];
												$add_border_class = ($vehicle_counter % 2) ? 'border-end' : '';
												$add_margin_class = ($vehicle_counter % 2) ? '' : 'ms-3';
										?>
												<div class="row col-md-6 <?= $add_border_class; ?> vehicle-row">
													<input type="hidden" name="vendor_id[]" value="<?= $vendor_ID; ?>">
													<input type="hidden" name="vendor_branch_id[]" value="<?= $vendor_branch_id; ?>">
													<input type="hidden" name="vehicle_id[]" value="<?= $vehicle_id; ?>">
													<input type="hidden" name="time_limit_id[]" value="<?= $time_limit_id; ?>">
													<input type="hidden" name="vehicle_type_id[]" value="<?= $vendor_vehicle_type_ID; ?>">
													<input type="hidden" name="vehicle_type_title[]" value="<?= $vehicle_type_title; ?>">
													<div class="col-md-3 mb-2 <?= $add_margin_class; ?>">
														<div class="form-group"><label class="form-label">Vehicle Type</label>
															<div class="form-group">
																<p class="text-primary p-0 my-2"><?= $vehicle_type_title; ?></p>
															</div>
														</div>
													</div>
													<div class="col-md-4 mb-2">
														<div class="form-group"><label class="form-label">Local KM Limit</label>
															<div class="form-group">
																<p class="text-primary p-0 my-2"><?= $time_limit_title; ?></p>
															</div>
														</div>
													</div>
													<div class="col-md-4 mb-2">
														<div class="form-group"><label class="form-label" for="vehicle_rental_charge_<?= $vehicle_id; ?>">Rental Charge(₹)</label>
															<div class="form-group">
																<input type="text" id="vehicle_rental_charge_<?= $vehicle_id; ?>" name="vehicle_rental_charge[]" class="form-control" placeholder="Enter the Rental Charge" autocomplete="off" />
															</div>
														</div>
													</div>
												</div>
											<?php endwhile; ?>
										<?php else : ?>
											<div class="col-12 text-center">
												<h6 class="text-muted">No vehicles found for this branch.</h6>
											</div>
										<?php endif; ?>
									</div>
									<?php if ($branch_counter != $total_vendor_branch_count) : ?>
										<div class="border-bottom border-bottom-dashed my-4"></div>
									<?php endif; ?>
								<?php endwhile; ?>
							<?php else : ?>
								<div class="col-12 text-center">
									<h5 class="text-muted">No branches found!</h5>
								</div>
							<?php endif; ?>
						</div>
						<div class="row g-3" id="show_local_pricebook_container">
						</div>
					</div>
				</div>
			</div>

			<?php
			sqlDATASEEK_LABEL($select_vendor_branch_list_query, 0);
			?>

			<!-- VEHICLE RENTAL COST DETAILS FOR OUTSTATION -->
			<div class="row">
				<div class="col-12 mt-2">
					<div class="card p-3">
						<div class="row py-2">
							<div class="col-md-6 my-auto">
								<h5 class="mb-0">Vehicle Rental Cost Details | Outstation Pricebook</h5>
							</div>
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-4">
										<a id="btn_limelimit" href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showKMSLIMITMODAL('0','<?= $vendor_ID; ?>');" data-bs-dismiss="modal">+ Add KM Limit</a>
									</div>
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-8">
												<div class="input-group">
													<input type="text" name="outstation_pricebook_start_date" id="outstation_pricebook_start_date" autocomplete="off" required="" class="form-control" placeholder="Start Date">
													<input type="text" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;" name="outstation_pricebook_end_date" id="outstation_pricebook_end_date" autocomplete="off" required="" class="form-control" placeholder="End Date">
													<span class="calender-icon d-none"><img class="" src="../head/assets/img/svg/calendar.svg"></span>
												</div>
												<div id="outstation_pricebook_vehicle_date_error" class="invalid-feedback">This field is required</div>
											</div>
											<div class="col-md-4 text-end">
												<button type="button" id="update_outstation_pricebook" class="btn btn-primary btn-md">Update</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row" id="outstationVehiclesContainer">
							<?php
							if ($total_vendor_branch_count > 0) :
								$branch_counter = 0;
								while ($fetch_vendor_branch_data = sqlFETCHARRAY_LABEL($select_vendor_branch_list_query)) :
									$branch_counter++;
									$vendor_branch_id = $fetch_vendor_branch_data["vendor_branch_id"];
									$vendor_branch_name = $fetch_vendor_branch_data["vendor_branch_name"];

									// Fetch vehicle details for each branch
									$select_vehicle_details_query = sqlQUERY_LABEL("SELECT 
										KMS_LIMIT.`kms_limit_id`, KMS_LIMIT.`kms_limit_title`, KMS_LIMIT.`kms_limit`, VEHICLE_TYPE.`vehicle_type_title`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`, VEHICLE.`vehicle_type_id`, VEHICLE.`vendor_branch_id`, VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID`
									FROM 
										dvi_vendor_vehicle_types VENDOR_VEHICLE_TYPE
									LEFT JOIN 
										(SELECT MIN(vehicle_id) AS vehicle_id, vendor_id, vehicle_type_id, vendor_branch_id
									FROM dvi_vehicle
									WHERE vendor_id = '$vendor_ID' 
									AND vendor_branch_id = '$vendor_branch_id' 
									AND deleted = '0' 
									AND status = '1'
									GROUP BY vehicle_type_id, vendor_id, vendor_branch_id) VEHICLE 
									ON VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID = VEHICLE.vehicle_type_id
									LEFT JOIN 
										dvi_vehicle_type VEHICLE_TYPE 
										ON VEHICLE_TYPE.vehicle_type_id = VENDOR_VEHICLE_TYPE.vehicle_type_id
									LEFT JOIN 
										`dvi_kms_limit` KMS_LIMIT
										ON KMS_LIMIT.vendor_id = VENDOR_VEHICLE_TYPE.vendor_id 
										AND KMS_LIMIT.vendor_vehicle_type_id = VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID WHERE VENDOR_VEHICLE_TYPE.vendor_id = '$vendor_ID' AND VENDOR_VEHICLE_TYPE.`deleted` = '0' AND VENDOR_VEHICLE_TYPE.`status` = '1' AND KMS_LIMIT.`kms_limit_id` IS NOT NULL ORDER BY VENDOR_VEHICLE_TYPE.vendor_vehicle_type_ID ASC") or die("#2-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());

									//$select_vehicle_details_query = sqlQUERY_LABEL("SELECT KMS_LIMIT.`kms_limit_id`, KMS_LIMIT.`kms_limit_title`, KMS_LIMIT.`kms_limit`, VEHICLE_TYPE.`vehicle_type_title`, VEHICLE.`vehicle_id`, VEHICLE.`vendor_id`, VEHICLE.`vehicle_type_id`, VEHICLE.`vendor_branch_id`, VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` FROM `dvi_vehicle` VEHICLE LEFT JOIN `dvi_vendor_vehicle_types` VENDOR_VEHICLE_TYPE ON VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` = VEHICLE.`vehicle_type_id` LEFT JOIN `dvi_vehicle_type` VEHICLE_TYPE ON VEHICLE_TYPE.`vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vehicle_type_id` LEFT JOIN `dvi_kms_limit` KMS_LIMIT ON KMS_LIMIT.`vendor_id` = VEHICLE.`vendor_id` AND KMS_LIMIT.`vendor_vehicle_type_id` = VENDOR_VEHICLE_TYPE.`vendor_vehicle_type_ID` WHERE VEHICLE.`vendor_id` = '$vendor_ID' AND VEHICLE.`vendor_branch_id` = '$vendor_branch_id' AND VEHICLE.`deleted` = '0' AND VEHICLE.`status` = '1' AND KMS_LIMIT.`kms_limit_id` IS NOT NULL") or die("#2-UNABLE_TO_COLLECT_VEHICLE_DETAILS:" . sqlERROR_LABEL());
									$total_vehicle_count = sqlNUMOFROW_LABEL($select_vehicle_details_query);
							?>
									<div class="row">
										<div class="d-flex align-items-center mb-3">
											<h5 class="m-0 text-primary">Branch #<?= $branch_counter; ?> - <?= ucfirst($vendor_branch_name); ?></h5>
										</div>
										<?php
										if ($total_vehicle_count > 0) :
											while ($fetch_vehicle_data = sqlFETCHARRAY_LABEL($select_vehicle_details_query)) :
												$vehicle_counter++;
												$vehicle_id = $fetch_vehicle_data["vehicle_id"];
												$kms_limit = $fetch_vehicle_data["kms_limit"];
												$kms_limit_id = $fetch_vehicle_data["kms_limit_id"];
												$kms_limit_title = $fetch_vehicle_data["kms_limit_title"];
												$vendor_vehicle_type_ID = $fetch_vehicle_data["vendor_vehicle_type_ID"];
												$vehicle_type_title = $fetch_vehicle_data["vehicle_type_title"];
												$add_border_class = ($vehicle_counter % 2) ? 'border-end' : '';
												$add_margin_class = ($vehicle_counter % 2) ? '' : 'ms-3';
										?>
												<div class="row col-md-6 <?= $add_border_class; ?> vehicle-row">
													<input type="hidden" name="vendor_id[]" value="<?= $vendor_ID; ?>">
													<input type="hidden" name="vendor_branch_id[]" value="<?= $vendor_branch_id; ?>">
													<input type="hidden" name="vehicle_id[]" value="<?= $vehicle_id; ?>">
													<input type="hidden" name="kms_limit_id[]" value="<?= $kms_limit_id; ?>">
													<input type="hidden" name="vehicle_type_id[]" value="<?= $vendor_vehicle_type_ID; ?>">
													<input type="hidden" name="vehicle_type_title[]" value="<?= $vehicle_type_title; ?>">
													<div class="col-md-3 mb-2 <?= $add_margin_class; ?>">
														<div class="form-group"><label class="form-label">Vehicle Type</label>
															<div class="form-group">
																<p class="text-primary p-0 my-2"><?= $vehicle_type_title; ?></p>
															</div>
														</div>
													</div>
													<div class="col-md-4 mb-2">
														<div class="form-group"><label class="form-label">Outstaion KM Limit</label>
															<div class="form-group">
																<p class="text-primary p-0 my-2"><?= $kms_limit . ' KM'; ?></p>
															</div>
														</div>
													</div>
													<div class="col-md-4 mb-2">
														<div class="form-group"><label class="form-label" for="outstation_vehicle_rental_charge_<?= $vehicle_id; ?>">Rental Charge(₹)</label>
															<div class="form-group">
																<input type="text" id="outstation_vehicle_rental_charge_<?= $vehicle_id; ?>" name="outstation_vehicle_rental_charge[]" class="form-control" placeholder="Enter the Rental Charge" autocomplete="off" />
															</div>
														</div>
													</div>
												</div>
											<?php endwhile; ?>
										<?php else : ?>
											<div class="col-12 text-center">
												<h6 class="text-muted">No vehicles found for this branch.</h6>
											</div>
										<?php endif; ?>
									</div>
									<?php if ($branch_counter != $total_vendor_branch_count) : ?>
										<div class="border-bottom border-bottom-dashed my-4"></div>
									<?php endif; ?>
								<?php endwhile; ?>
							<?php else : ?>
								<div class="col-12 text-center">
									<h5 class="text-muted">No branches found!</h5>
								</div>
							<?php endif; ?>
						</div>

						<div class="row g-3" id="show_outstation_pricebook_container">
						</div>

						<div class="d-flex justify-content-between py-3">
							<div>
								<a href="<?= $driver_cost_url; ?>" class="btn btn-secondary">Back</a>
							</div>
							<a href="<?= $permit_cost_info_url; ?>" class="btn btn-primary btn-md ">Skip & Continue</a>
						</div>
					</div>
				</div>
			</div>

			<script>
				$(document).ready(function() {
					flatpickr("#local_pricebook_start_date", {
						dateFormat: "d-m-Y",
						onChange: function(selectedDates, dateStr, instance) {
							// Get the selected local pricebook start date
							const startDate = selectedDates[0];

							// Clear the value of the end date input field
							document.getElementById("local_pricebook_end_date").value = "";

							// Re-initialize the Flatpickr for the local pricebook end date with the new minDate
							flatpickr("#local_pricebook_end_date", {
								dateFormat: "d-m-Y",
								minDate: startDate, // Set the minimum date for the local pricebook end date picker
								onChange: function(selectedDates, dateStr, instance) {
									// Get the selected amenities end date
									endDate = selectedDates[0];

									// Trigger AJAX call if both start and end dates are selected
									if (startDate && endDate) {
										getVEHICLE_LOCAL_PRICEBOOK_DETAILS(startDate, endDate);
									}
								}
							});
						}
					});

					function getVEHICLE_LOCAL_PRICEBOOK_DETAILS(startDate, endDate) {
						const formattedStartDate = flatpickr.formatDate(startDate, "d-m-Y");
						const formattedEndDate = flatpickr.formatDate(endDate, "d-m-Y");
						const vendor_ID = '<?= $vendor_ID; ?>';
						$.ajax({
							url: 'engine/ajax/ajax_vehicle_local_pricebook_details.php?type=show_form',
							type: 'POST',
							data: {
								vendor_ID: vendor_ID,
								start_date: formattedStartDate,
								end_date: formattedEndDate
							},
							success: function(response) {
								// Handle the response from the server
								console.log('Response:', response);
								$('#show_local_pricebook_container').html(response);
							},
							error: function(error) {
								console.log('Error:', error);
							}
						});
					}

					// Initialize Flatpickr for the local pricebook end date without a minDate initially
					flatpickr("#local_pricebook_end_date", {
						dateFormat: "d-m-Y",
					});

					// Initialize Flatpickr for the outstation pricebook start date
					flatpickr("#outstation_pricebook_start_date", {
						dateFormat: "d-m-Y",
						onChange: function(selectedDates, dateStr, instance) {
							// Get the selected outstation pricebook start date
							const startDate = selectedDates[0];

							// Clear the value of the end date input field
							document.getElementById("outstation_pricebook_end_date").value = "";

							// Re-initialize the Flatpickr for the outstation pricebook end date with the new minDate
							flatpickr("#outstation_pricebook_end_date", {
								dateFormat: "d-m-Y",
								minDate: startDate, // Set the minimum date for the outstation pricebook end date picker
								onChange: function(selectedDates, dateStr, instance) {
									// Get the selected amenities end date
									endDate = selectedDates[0];

									// Trigger AJAX call if both start and end dates are selected
									if (startDate && endDate) {
										getVEHICLE_OUTSTATION_PRICEBOOK_DETAILS(startDate, endDate);
									}
								}
							});
						}
					});

					function getVEHICLE_OUTSTATION_PRICEBOOK_DETAILS(startDate, endDate) {
						const formattedStartDate = flatpickr.formatDate(startDate, "d-m-Y");
						const formattedEndDate = flatpickr.formatDate(endDate, "d-m-Y");
						const vendor_ID = '<?= $vendor_ID; ?>';
						$.ajax({
							url: 'engine/ajax/ajax_vehicle_outstation_pricebook_details.php?type=show_form',
							type: 'POST',
							data: {
								vendor_ID: vendor_ID,
								start_date: formattedStartDate,
								end_date: formattedEndDate
							},
							success: function(response) {
								// Handle the response from the server
								console.log('Response:', response);
								$('#show_outstation_pricebook_container').html(response);
							},
							error: function(error) {
								console.log('Error:', error);
							}
						});
					}

					// Initialize Flatpickr for the outstation pricebook end date without a minDate initially
					flatpickr("#outstation_pricebook_end_date", {
						dateFormat: "d-m-Y",
					});

					$('.form-select').selectize();

					$('#updateDriverCostForm').on('submit', function(e) {
						e.preventDefault();
						$.ajax({
							url: 'engine/ajax/ajax_manage_vendor_pricebook_details.php?type=vendor_driver_cost',
							type: 'POST',
							data: $(this).serialize(),
							dataType: 'json',
							success: function(response) {
								if (!response.success) {
									TOAST_NOTIFICATION('error', 'Something went wrong... Unable to Update now', 'Error !!!');
								} else {
									if (response.result == true) {
										TOAST_NOTIFICATION('success', 'Driver Cost Details Updated Successfully', 'Success !!!');
									} else {
										TOAST_NOTIFICATION('error', 'Sorry, Unable to Update the Driver Cost Details.', 'Error !!!');
									}
								}
							},
							error: function() {
								TOAST_NOTIFICATION('error', 'Something went wrong... Unable to Update now', 'Error !!!');
							}
						});
					});
				});

				document.getElementById('update_vehicle_extra_cost_button').addEventListener('click', function() {
					let formData = new FormData();
					let errors = [];

					// Collect all vehicle containers
					let vehicleContainers = document.querySelectorAll('.vehicle-container');

					// Get vendor_id and vendor_branch_id from the first container (since they are the same for all)
					if (vehicleContainers.length > 0) {
						let vendorId = vehicleContainers[0].querySelector('input[name="vendor_id"]').value;
						let vendorBranchId = vehicleContainers[0].querySelector('input[name="vendor_branch_id"]').value;
						formData.append('vendor_id', vendorId);
						formData.append('vendor_branch_id', vendorBranchId);
					}

					vehicleContainers.forEach((container, index) => {
						let vehicleTypeId = container.querySelector('input[name="vehicle_type_id[]"]').value;
						let vehicleTypeTitle = container.querySelector('input[name="vehicle_type_title[]"]').value;
						let extraKmCharge = container.querySelector('input[name="extra_km_charge[]"]').value;
						let extrahrCharge = container.querySelector('input[name="extra_hour_charge[]"]').value;
						let earlyMorningCharges = container.querySelector('input[name="early_morning_charges[]"]').value;
						let eveningCharges = container.querySelector('input[name="evening_charges[]"]').value;

						console.log(`Vehicle ${index + 1}:`);
						console.log(`vehicleTypeId: ${vehicleTypeId}`);
						console.log(`vehicleTypeTitle: ${vehicleTypeTitle}`);
						console.log(`extraKmCharge: ${extraKmCharge}`);
						console.log(`earlyMorningCharges: ${earlyMorningCharges}`);
						console.log(`eveningCharges: ${eveningCharges}`);

						formData.append('vehicle_type_id[]', vehicleTypeId);
						formData.append('vehicle_type_title[]', vehicleTypeTitle);
						formData.append('extra_km_charge[]', extraKmCharge);
						formData.append('extra_hour_charge[]', extrahrCharge);
						formData.append('early_morning_charges[]', earlyMorningCharges);
						formData.append('evening_charges[]', eveningCharges);
					});

					// Logging formData to verify its content
					for (let pair of formData.entries()) {
						console.log(pair[0] + ': ' + pair[1]);
					}

					fetch('engine/ajax/ajax_manage_vendor_pricebook_details.php?type=vehicle_extra_cost', {
							method: 'POST',
							body: formData
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								if (data.result) {
									TOAST_NOTIFICATION('success', 'Vehicle Cost Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								} else {
									TOAST_NOTIFICATION('error', 'Sorry, Unable to Update the Vehicle Details.', 'Error !!!', '', '', '', '', '', '', '', '', '', '');
								}
							} else {
								let errorMessages = data.errors.join('<br>');
								TOAST_NOTIFICATION('error', errorMessages, 'Error !!!', '', '', '', '', '', '', '', '', '', '');
							}
						})
						.catch(error => {
							console.error('Error:', error);
							TOAST_NOTIFICATION('error', 'An unexpected error occurred.', 'Error !!!', '', '', '', '', '', '', '', '', '', '');
						});
				});

				//LOCAL PRICEBOOK DETAILS
				document.getElementById('update_local_pricebook').addEventListener('click', function() {
					const vehiclesContainer = document.getElementById('vehiclesContainer');
					const vehicleRows = vehiclesContainer.querySelectorAll('.vehicle-row');
					const inputs = vehiclesContainer.querySelectorAll('input');
					const formData = new FormData();

					// Get the start date and end date elements
					const startDate = document.getElementById('local_pricebook_start_date');
					const endDate = document.getElementById('local_pricebook_end_date');
					const dateError = document.getElementById('local_pricebook_vehicle_date_error');

					let valid = true;
					let vehiclesValid = false; // To check if at least one vehicle has a valid charge
					let checkDateValidation = false;

					// Validate rental charges and gather form data
					vehicleRows.forEach(row => {
						const vehicleId = row.querySelector('input[name="vehicle_id[]"]').value;
						const vehicleTypeTitle = row.querySelector('input[name="vehicle_type_title[]"]').value;
						const vehicleRentalCharge = row.querySelector('input[name="vehicle_rental_charge[]"]').value;
						const vendorId = row.querySelector('input[name="vendor_id[]"]').value;
						const vendorBranchId = row.querySelector('input[name="vendor_branch_id[]"]').value;
						const vehicleTypeId = row.querySelector('input[name="vehicle_type_id[]"]').value;
						const timeLimitId = row.querySelector('input[name="time_limit_id[]"]').value;

						formData.append('vehicle_id[]', vehicleId);
						formData.append('vehicle_type_title[]', vehicleTypeTitle);
						formData.append('vendor_id[]', vendorId);
						formData.append('vendor_branch_id[]', vendorBranchId);
						formData.append('vehicle_type_id[]', vehicleTypeId);
						formData.append('time_limit_id[]', timeLimitId);

						if (vehicleRentalCharge) {
							formData.append('vehicle_rental_charge[]', vehicleRentalCharge);
							checkDateValidation = true;
							vehiclesValid = true;
						} else {
							formData.append('vehicle_rental_charge[]', '');
						}

						// Add or remove invalid class based on input value
						if (!vehicleRentalCharge && vehicleRentalCharge.required) {
							valid = false;
							row.querySelector('input[name="vehicle_rental_charge[]"]').classList.add('is-invalid');
						} else {
							row.querySelector('input[name="vehicle_rental_charge[]"]').classList.remove('is-invalid');
						}
					});

					// Validate start date and end date only if rental charge is entered
					if (checkDateValidation) {
						if (!startDate.value && !endDate.value) {
							valid = false;
							startDate.classList.add('is-invalid');
							endDate.classList.add('is-invalid');
							dateError.textContent = "Start date and End date are required.";
							dateError.style.display = 'block';
						} else if (!startDate.value) {
							valid = false;
							startDate.classList.add('is-invalid');
							endDate.classList.remove('is-invalid');
							dateError.textContent = "Start date is required.";
							dateError.style.display = 'block';
						} else if (!endDate.value) {
							valid = false;
							endDate.classList.add('is-invalid');
							startDate.classList.remove('is-invalid');
							dateError.textContent = "End date is required.";
							dateError.style.display = 'block';
						} else {
							startDate.classList.remove('is-invalid');
							endDate.classList.remove('is-invalid');
							dateError.style.display = 'none';
							formData.append('local_pricebook_start_date', startDate.value);
							formData.append('local_pricebook_end_date', endDate.value);
						}
					}

					if (!valid || !vehiclesValid) {
						const toastMessage = !vehiclesValid ? 'Please enter at least one rental charge for the vehicles.' : 'Please fill in all required fields.';
						TOAST_NOTIFICATION('error', toastMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
						return;
					}

					fetch('engine/ajax/ajax_manage_vendor_pricebook_details.php?type=vehicle_local_pricebook_cost', {
							method: 'POST',
							body: formData
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								inputs.forEach(input => {
									if (input.type === 'text') {
										input.value = '';
									}
								});
								TOAST_NOTIFICATION('success', 'Successfully Updated the vehicle rental local cost details', 'Success !!!', '', '', '', '', '', '', '', '', '');
								// Add any additional success handling here
								const vendor_ID = '<?= $vendor_ID; ?>';
								$.ajax({
									url: 'engine/ajax/ajax_vehicle_local_pricebook_details.php?type=show_form',
									type: 'POST',
									data: {
										vendor_ID: vendor_ID,
										start_date: startDate.value,
										end_date: endDate.value
									},
									success: function(response) {
										// Handle the response from the server
										console.log('Response:', response);
										$('#show_local_pricebook_container').html(response);
									},
									error: function(error) {
										console.log('Error:', error);
									}
								});
							} else {
								TOAST_NOTIFICATION('error', 'Unable to update the vehicle rental local cost details.', 'Error !!!', '', '', '', '', '', '', '', '', '');
							}
						})
						.catch(error => {
							console.error('Error:', error);
							TOAST_NOTIFICATION('error', 'An unexpected error occurred.', 'Error !!!', '', '', '', '', '', '', '', '', '');
						});
				});

				// Remove error messages when user starts typing
				document.getElementById('local_pricebook_start_date').addEventListener('input', function() {
					const dateError = document.getElementById('local_pricebook_vehicle_date_error');
					this.classList.remove('is-invalid');
					if (dateError.textContent.includes("Start date is required.") || dateError.textContent.includes("Start date and End date are required.")) {
						dateError.style.display = 'none';
					}
				});

				document.getElementById('local_pricebook_end_date').addEventListener('input', function() {
					const dateError = document.getElementById('local_pricebook_vehicle_date_error');
					this.classList.remove('is-invalid');
					if (dateError.textContent.includes("End date is required.") || dateError.textContent.includes("Start date and End date are required.")) {
						dateError.style.display = 'none';
					}
				});

				// OUTSTATION PRICEBOOK DETAILS
				document.getElementById('update_outstation_pricebook').addEventListener('click', function() {
					const vehiclesContainer = document.getElementById('outstationVehiclesContainer');
					const inputs = vehiclesContainer.querySelectorAll('input');
					const formData = new FormData();

					// Get the start date and end date elements
					const startDate = document.getElementById('outstation_pricebook_start_date');
					const endDate = document.getElementById('outstation_pricebook_end_date');
					const dateError = document.getElementById('outstation_pricebook_vehicle_date_error');

					let valid = true;
					let vehiclesValid = false; // To check if at least one vehicle has a valid rental charge

					// Validate start date and end date
					if (!startDate.value && !endDate.value) {
						valid = false;
						startDate.classList.add('is-invalid');
						endDate.classList.add('is-invalid');
						dateError.textContent = "Start date and End date should be required.";
						dateError.style.display = 'block';
					} else if (!startDate.value) {
						valid = false;
						startDate.classList.add('is-invalid');
						endDate.classList.remove('is-invalid');
						dateError.textContent = "Start date should be required.";
						dateError.style.display = 'block';
					} else if (!endDate.value) {
						valid = false;
						endDate.classList.add('is-invalid');
						startDate.classList.remove('is-invalid');
						dateError.textContent = "End date should be required.";
						dateError.style.display = 'block';
					} else {
						startDate.classList.remove('is-invalid');
						endDate.classList.remove('is-invalid');
						dateError.style.display = 'none';
						formData.append(startDate.name, startDate.value);
						formData.append(endDate.name, endDate.value);
					}

					// Validate other input fields
					inputs.forEach(input => {
						const errorElement = document.getElementById(`${input.id}_error`);
						if (input.name.startsWith('outstation_vehicle_rental_charge')) {
							if (input.value) {
								vehiclesValid = true;
							}
							if (!input.value && input.required) {
								valid = false;
								input.classList.add('is-invalid');
								if (errorElement) errorElement.style.display = 'block';
							} else {
								input.classList.remove('is-invalid');
								if (errorElement) errorElement.style.display = 'none';
							}
						}
					});

					if (!valid || !vehiclesValid) {
						const toastMessage = !vehiclesValid ? 'Please enter at least one rental charge for the vehicles.' : 'Please fill in all required fields.';
						TOAST_NOTIFICATION('error', toastMessage, 'Error !!!', '', '', '', '', '', '', '', '', '');
						return;
					}

					// Prepare data for insertion/updating
					const vendorIDs = [];
					const vehicleIDs = [];
					const kmsLimitIDs = [];
					const rentalCharges = [];
					const vendorBranchIDs = [];
					const vehicleTypeIDs = [];

					inputs.forEach(input => {
						if (input.name.startsWith('vendor_id')) {
							vendorIDs.push(input.value);
						} else if (input.name.startsWith('vehicle_id')) {
							vehicleIDs.push(input.value);
						} else if (input.name.startsWith('kms_limit_id')) {
							kmsLimitIDs.push(input.value);
						} else if (input.name.startsWith('outstation_vehicle_rental_charge')) {
							rentalCharges.push(input.value);
						} else if (input.name.startsWith('vendor_branch_id')) {
							vendorBranchIDs.push(input.value);
						} else if (input.name.startsWith('vehicle_type_id')) {
							vehicleTypeIDs.push(input.value);
						}
					});

					// Add additional data for each vehicle
					vehicleIDs.forEach((vehicleID, index) => {
						formData.append(`vendor_id[${index}]`, vendorIDs[index]);
						formData.append(`vehicle_id[${index}]`, vehicleID);
						formData.append(`kms_limit_id[${index}]`, kmsLimitIDs[index]);
						formData.append(`vendor_branch_id[${index}]`, vendorBranchIDs[index]);
						formData.append(`vehicle_type_id[${index}]`, vehicleTypeIDs[index]);
						if (rentalCharges[index]) {
							formData.append(`outstation_vehicle_rental_charge[${index}]`, rentalCharges[index]);
						}
						formData.append(`outstation_pricebook_start_date`, startDate.value);
						formData.append(`outstation_pricebook_end_date`, endDate.value);
					});

					fetch('engine/ajax/ajax_manage_vendor_pricebook_details.php?type=vehicle_outstation_pricebook_cost', {
							method: 'POST',
							body: formData
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								TOAST_NOTIFICATION('success', 'Successfully Updated the outstation pricebook details', 'Success !!!', '', '', '', '', '', '', '', '', '');
								inputs.forEach(input => {
									if (input.type === 'text') {
										input.value = '';
									}
								});
								const vendor_ID = '<?= $vendor_ID; ?>';
								$.ajax({
									url: 'engine/ajax/ajax_vehicle_outstation_pricebook_details.php?type=show_form',
									type: 'POST',
									data: {
										vendor_ID: vendor_ID,
										start_date: startDate.value,
										end_date: endDate.value
									},
									success: function(response) {
										// Handle the response from the server
										console.log('Response:', response);
										$('#show_outstation_pricebook_container').html(response);
									},
									error: function(error) {
										console.log('Error:', error);
									}
								});
							} else {
								TOAST_NOTIFICATION('error', 'Unable to update the outstation pricebook details.', 'Error !!!', '', '', '', '', '', '', '', '', '');
							}
						})
						.catch(error => {
							console.error('Error:', error);
							TOAST_NOTIFICATION('error', 'An unexpected error occurred.', 'Error !!!', '', '', '', '', '', '', '', '', '');
						});
				});

				// Remove error messages when user starts typing
				document.getElementById('outstation_pricebook_start_date').addEventListener('input', function() {
					const dateError = document.getElementById('outstation_pricebook_vehicle_date_error');
					this.classList.remove('is-invalid');
					if (dateError.textContent.includes("Start date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
						dateError.style.display = 'none';
					}
				});

				document.getElementById('outstation_pricebook_end_date').addEventListener('input', function() {
					const dateError = document.getElementById('outstation_pricebook_vehicle_date_error');
					this.classList.remove('is-invalid');
					if (dateError.textContent.includes("End date should be required.") || dateError.textContent.includes("Start date and End date should be required.")) {
						dateError.style.display = 'none';
					}
				});

				function updateVENDORMARGINDETAILS() {
					var vendor_id = $('#vendor_id').val();
					var vendor_margin = $('#vendor_margin').val();
					var vendor_margin_gst_type = $('#vendor_margin_gst_type').val();
					var vendor_margin_gst_percentage = $('#vendor_margin_gst_percentage').val();

					$.ajax({
						url: 'engine/ajax/ajax_manage_vendor_pricebook_details.php?type=vendor_margin_details',
						type: "POST",
						data: {
							vendor_id: vendor_id,
							vendor_margin: vendor_margin,
							vendor_margin_gst_type: vendor_margin_gst_type,
							vendor_margin_gst_percentage: vendor_margin_gst_percentage
						},
						dataType: 'json',
						success: function(response) {
							// Handle success response
							if (!response.success) {
								TOAST_NOTIFICATION('error', 'Something went wrong... Unable to Update now', 'Error !!!', '', '', '', '', '', '', '', '', '');
							} else {
								if (response.result == true) {
									TOAST_NOTIFICATION('success', 'Vendor Margin Details Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
									// You can call a function here to refresh the form or redirect to another page
								} else {
									TOAST_NOTIFICATION('error', 'Sorry, Unable to Update the Vendor Details.', 'Error !!!', '', '', '', '', '', '', '', '', '');
								}
							}
						}
					});
				}

				//ADD & DELETE MODAL
				function showTIMELIMITMODAL(TIME_LIMIT_ID, VENDOR_ID) {
					$('.receiving-time-limit-form-data').load('engine/ajax/__ajax_add_timelimit.php?type=show_form&TIME_LIMIT_ID=' + TIME_LIMIT_ID + '&VENDOR_ID=' + VENDOR_ID + '&UPDATE_FROM=PRICEBOOK', function() {
						const container = document.getElementById("addTIMELIMITFORM");
						const modal = new bootstrap.Modal(container);
						modal.show();
						if (TIME_LIMIT_ID != 0) {
							$('#TIMELIMITFORMLabel').html('Update Local KM Limit');
						} else {
							$('#TIMELIMITFORMLabel').html('Add Local KM Limit');
						}
					});
				}

				//ADD & DELETE MODAL
				function showKMSLIMITMODAL(KMS_LIMIT_ID, VENDOR_ID) {
					$('.receiving-kms-limit-form-data').load('engine/ajax/__ajax_add_kmslimit.php?type=show_form&KMS_LIMIT_ID=' + KMS_LIMIT_ID + '&VENDOR_ID=' + VENDOR_ID + '&UPDATE_FROM=PRICEBOOK', function() {
						const container = document.getElementById("addKMSLIMITFORM");
						const modal = new bootstrap.Modal(container);
						modal.show();
						if (KMS_LIMIT_ID) {
							$('#KMSLIMITFORMLabel').html('Update Outstation KM Limit');
						} else {
							$('#KMSLIMITFORMLabel').html('Add Outstation KM Limit');
						}
					});
				}
			</script>

		<?php
	elseif ($_GET['type'] == 'vehicle_info') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];
		$v_id = $_GET['v_id'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=add&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=add&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'javascript:;';
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
		?>
			<!-- Default Wizard -->
			<div class="row">
				<div class="col-12">
					<div id="wizard-validation" class="bs-stepper mt-2">
						<div class="bs-stepper-header border-0 justify-content-center py-2">
							<div class="step" data-target="#basic_info">
								<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">1</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Basic Info</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#branch_info">
								<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle active-stepper">4</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title">Vehicle</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_Pricebook">
								<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">5</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Pricebook</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#permit_cost">
								<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">6</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Permit Cost
										</h5>
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
						<form onSubmit="return false">
							<div id="vehicle_info" class="content active dstepper-block">
								<div id="vehicle_list_without_add_form">
									<div class="content-header mb-3">
										<h5 class="text-primary mb-0">List of Branch</h5>
									</div>
									<div class="row g-3">
										<div class="col-sm-12">
											<div class="row g-3">
												<?php
												$select_branches = sqlQUERY_LABEL("SELECT `vendor_branch_id`,  `vendor_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `deleted` = '0' and `vendor_id` = '$vendor_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
												while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_branches)) :
													$vendor_id = $fetch_list_data['vendor_id'];
													$vendor_branch_id = $fetch_list_data['vendor_branch_id'];
													$vendor_branch_name = $fetch_list_data['vendor_branch_name'];
													$firstletters = substr($vendor_branch_name, 0, 1);
												?>
													<div class="col-12 col-lg-3 position-relative" onclick="choosen_vehicle_list('<?= $vendor_id; ?>', '<?= $vendor_branch_id; ?>', '<?= $ROUTE; ?>')">
														<span class="badge bg-label-primary position-absolute vendor-vehicle-count py-0">
															<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="me-2">
																<g>
																	<g data-name="13-car">
																		<path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
																		<path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
																	</g>
																</g>
															</svg>
															<?= getVECHILECOUNT($vendor_id, $vendor_branch_id, 'vehicle_count'); ?>
														</span>
														<a href="javascript:void(0);" class="d-flex justify-content-between vehicle-branches-card vehicle_branchid_<?= $vendor_branch_id; ?> p-3">
															<div class="d-flex">

																<div class="avatar me-3">
																	<span class="avatar-initial rounded bg-label-primary fs-4"><?= $firstletters; ?></span>
																</div>
																<div>
																	<h5 class="mb-1 fs-5"><?= $vendor_branch_name; ?></h5>
																	<p class="m-0 fs-6"></p>
																</div>
															</div>
															<div class="d-flex align-items-center text-primary vehicle_list_icon" id="vehicle_list_icon_<?= $vendor_branch_id; ?>">
																<i class="ti ti-chevron-right me-1"></i>
															</div>
														</a>
													</div>
												<?php endwhile; ?>
												<div class="col-12 mb-3" id="vehicle_list">

												</div>
											</div>
											<div class="col-12 d-flex justify-content-between">
												<a href="<?= $branch_info_url; ?>" class="btn btn-secondary btn-prev">
													Back
												</a>

												<a href="newvendor.php?route=<?= $ROUTE; ?>&formtype=vehicle_pricebook&id=<?= $vendor_ID; ?>" class="btn btn-primary btn-next text-white"> Skip & Continue </a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>

						<div id="show_add_vehicle" class="col-md-12"></div>
					</div>
				</div>
			</div>
			<!-- /Default Wizard -->


			<script>
				$(document).ready(function() {
					choose_branch();
					choose_vehicle();
					var vendor_branch_counter = 0;
				});

				// Choosen Vehicle List
				function choosen_vehicle_list(vendor_id, branch_id, route) {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_create_newvehicle_list.php?type=show_vehicle_list',
						data: {
							vendor_id: vendor_id,
							branch_id: branch_id,
							route: route
						},
						success: function(response) {
							$('.vehicle-branches-card').removeClass('vehicle_list_selected');
							$('.vehicle_list_icon').html('<i class="ti ti-chevron-right me-1"></i>');
							$('#vehicle_list').html('');

							$('.vehicle-branches-card.vehicle_branchid_' + branch_id).addClass('vehicle_list_selected');
							$('.vehicle_list_icon#vehicle_list_icon_' + branch_id).html('<i class="ti ti-chevron-down me-1"></i>');
							$('#vehicle_list').html(response);
						}
					});
				}

				function choosen_vehicle_list_details(vendor_id, branch_id, vehicle_id, route) {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_create_newvehicle_list.php?type=show_vehicle_list',
						data: {
							vendor_id: vendor_id,
							branch_id: branch_id,
							vehicle_id: vehicle_id,
							route: route
						},
						success: function(response) {
							$('.vehicle-branches-card').removeClass('vehicle_list_selected');
							$('.vehicle_list_icon').html('<i class="ti ti-chevron-right me-1"></i>');
							$('#vehicle_list').html('');

							$('.vehicle-branches-card.vehicle_branchid_' + branch_id).addClass('vehicle_list_selected');
							$('.vehicle_list_icon#vehicle_list_icon_' + branch_id).html('<i class="ti ti-chevron-down me-1"></i>');
							$('#vehicle_list').html(response);
						}
					});
				}

				// Remove Choosen Vehicle List
				function remove_choosen_vehicle_list(ID) {
					$('.vehicle-branches-card').removeClass('vehicle_list_selected');
					$('.vehicle_list_icon').html('<i class="ti ti-chevron-right me-1"></i>');

					$('#vehicle_list').html('');
					// Scroll to the top of the page
					window.scrollTo({
						top: 0,
						behavior: 'smooth' // Use smooth scrolling if supported
					});
				}
			</script>

			<?php if (!empty($_GET['v_id'])): ?>
				<script>
					const ROUTE = "<?php echo $_GET['route']; ?>";
					const VENDOR_ID = "<?php echo $_GET['id']; ?>";
					const BRANCH_ID = "<?php echo $_GET['vbranch']; ?>";
					const VEHICLE_ID = "<?php echo $_GET['v_id']; ?>";

					choosen_vehicle_list_details(VENDOR_ID, BRANCH_ID, VEHICLE_ID, ROUTE);
				</script>
			<?php endif; ?>
		<?php
	elseif ($_GET['type'] == 'permit_cost_info') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=add&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=add&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=add&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
		?>
			<!-- Default Wizard -->
			<div class="row">
				<div class="col-12">
					<div id="wizard-validation" class="bs-stepper mt-2">
						<div class="bs-stepper-header border-0 justify-content-center py-2">
							<div class="step" data-target="#basic_info">
								<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">1</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Basic Info</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#branch_info">
								<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_Pricebook">
								<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">5</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Pricebook</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#permit_cost">
								<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle active-stepper">6</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title">Permit Cost
										</h5>
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
						<div>
							<form onSubmit="return false">
								<!-- Permit Cost -->
								<div id="permit_cost" class="content active dstepper-block">
									<div class="row">
										<div class="col-md-12">
											<div class="d-flex justify-content-between">
												<h5 class="card-title text-primary">Permit Details</h4>
													<div>
														<button class="btn btn-label-primary waves-effect" onclick="add_permit_cost('<?= $vendor_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost')">+ Add Permit Cost</button>
													</div>
											</div>
											<div class="card-body dataTable_select text-nowrap px-0">
												<div class="table-responsive">
													<table class="table table-flush-spacing border table-bordered" id="permit_cost_LIST">
														<thead class="table-head">
															<tr>
																<th scope="col">S.No</th>
																<th scope="col">View&Edit Permitcost</th>
																<th scope="col">Vehicle Type</th>
																<th scope="col">Source State</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$select_VEHICLE_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT  PC.vehicle_type_id,PC.`source_state_id` FROM dvi_permit_state PS LEFT JOIN  dvi_permit_cost PC ON PS.permit_state_id = PC.destination_state_id  AND PC.deleted = '0'  AND PC.vendor_id = '$vendor_ID'  GROUP BY  PC.`source_state_id`,PC.vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_VEHICLE_PERMIT_COST_LIST:" . sqlERROR_LABEL());
															$num_of_row_vehicle = sqlNUMOFROW_LABEL($select_VEHICLE_PERMITCOSTLIST_query);
															if ($num_of_row_vehicle > 0) :
																$permit_counter = 1;
																while ($fetch_vehicle_permitcost_list_data = sqlFETCHARRAY_LABEL($select_VEHICLE_PERMITCOSTLIST_query)) :
																	$group_by_vehicle_type_id = $fetch_vehicle_permitcost_list_data['vehicle_type_id'];
																	$group_by_source_state_id = $fetch_vehicle_permitcost_list_data['source_state_id'];
																	$select_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `vendor_id`,`permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `vendor_id` = '$vendor_ID' AND `source_state_id`='$group_by_source_state_id' AND `vehicle_type_id`='$group_by_vehicle_type_id' ORDER BY `permit_cost_id` ASC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
																	$num_of_row = sqlNUMOFROW_LABEL($select_PERMITCOSTLIST_query);
																	if ($num_of_row > 0) :
																		$counter_state_list = 0;
																		$currentSourceState = '';
																		while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_PERMITCOSTLIST_query)) :
																			$vendor_id = $fetch_list_data['vendor_id'];
																			$permit_cost_id = $fetch_list_data['permit_cost_id'];
																			$vehicle_type_id = $fetch_list_data['vehicle_type_id'];
																			$source_state_id = $fetch_list_data['source_state_id'];
																			$vehicle_type_name = getVENDOR_VEHICLE_TYPES($vendor_ID, $vehicle_type_id, 'label');
																			$source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
																			$source_state_code = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'state_code');
																			$destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
																			$permit_cost = $fetch_list_data['permit_cost'];

																			if ($currentSourceState != $source_state_name) :

																				if ($currentSourceState != '') :
																					echo '</div></td></tr>';
																				endif;
																				echo "<tr id='$permit_cost_id'>";
																				echo "<td>{$permit_counter}</td>";
																				echo "<td>
																				<a class='cursor-pointer' data-bs-toggle='modal' data-bs-target='#permit_modal_view' onclick='fetchPermitCost(\"{$vendor_id}\",\"{$group_by_source_state_id}\", \"{$group_by_vehicle_type_id}\", \"{$vehicle_type_name}\");'><img src='assets/img/svg/eye.svg' class='me-1'/></a>
																				<a class='cursor-pointer'  onclick='show_PERMIT_EDIT_MODAL(\"{$vendor_id}\",\"{$group_by_source_state_id}\", \"{$group_by_vehicle_type_id}\");'><img src='assets/img/svg/edit.svg' class='me-1'/></a> 
																				<a class='btn btn-sm btn-icon text-danger flex-end cursor-pointer'  onclick='delete_PERMIT_MODAL(\"{$permit_cost_id}\",\"{$vendor_id}\",\"{$group_by_source_state_id}\", \"{$group_by_vehicle_type_id}\");'><img src='assets/img/svg/delete.svg' class='me-1'/></a>
																				</td>";
																				echo "<td>{$vehicle_type_name}</td>";
																				echo "<td>{$source_state_code} - {$source_state_name}</td>";

																				$currentSourceState = $source_state_name;
																				$permit_counter++;
																			endif;
																		endwhile;

																		if ($currentSourceState != '') :
																			echo '</div></td>';
																		endif;

																		echo '</tr>';
																	endif;
																	$prev_vehicle_type_id = $group_by_vehicle_type_id;
																endwhile;
															else :
															?>
																<tr>
																	<td class="text-center" colspan='4'>No data Available</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
												<div class="d-flex justify-content-between mt-4">
													<a href="<?= $vehicle_info_url; ?>" class="btn btn-secondary btn-prev">Back
													</a>
													<a href="newvendor.php" class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Submit</span></a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Edit Permit cost Modal -->
			<div class="modal fade" id="editPERMITFORM" tabindex="-1" aria-hidden="true">
				<div class="modal-dialog modal-xl modal-simple modal-enable-otp modal-dialog-centered">
					<div class="modal-content p-3 p-md-5">
						<div class="receiving-permit-form-data">
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
					</div>
				</div>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="permit_modal_view" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-xl modal-simple modal-enable-otp modal-dialog-centered">
					<div class="modal-content p-3 p-md-5">
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				</div>
			</div>

			<!-- /Default Wizard -->
			<script>
				var dataTable = $('#permit_cost_LIST').DataTable({
					dom: 'lfrtip',
					"bFilter": true,
				});

				function add_permit_cost(ID, ROUTE, TYPE) {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_add_vendor_newform.php?type=' + TYPE + '&ROUTE=' + ROUTE + '&ID=' + ID,
						success: function(response) {
							$('#showvendorLIST').html('');
							$('#showvendorFORMSTEP1').html('');
							$('#showvendorFORMSTEP2').html('');
							$('#showvendorFORMSTEP3').html('');
							$('#showvendorFORMSTEP4').html(response);
							$('#showvendorPREVIEW').html('');
						}
					});
				}

				function fetchPermitCost(vendor_id, sourceStateId, vehicleTypeId, vehicle_type_name) {
					// AJAX request
					$.ajax({
						url: 'engine/ajax/__ajax_vendor_permitcost.php',
						type: 'POST',
						data: {
							vendor_id: vendor_id,
							source_state_id: sourceStateId,
							vehicle_type_id: vehicleTypeId,
							vehicle_type_name: vehicle_type_name
						},
						success: function(response) {
							$('#permit_modal_view .modal-content').html(response); // Populate modal with response data
						}
					});
				}

				//EDIT PERMIT COST MODAL
				function show_PERMIT_EDIT_MODAL(VENDOR_ID, SOURCE_STATE_ID, VEHICLE_TYPE_ID) {
					$('.receiving-permit-form-data').load('engine/ajax/__ajax_update_vehicle_permit_details.php?type=show_form&VEHICLE_TYPE_ID=' + VEHICLE_TYPE_ID + '&VENDOR_ID=' + VENDOR_ID + '&SOURCE_STATE_ID=' + SOURCE_STATE_ID + '', function() {
						const container = document.getElementById("editPERMITFORM");
						const modal = new bootstrap.Modal(container);
						modal.show();
						if (PERMIT_ID) {
							$('#PERMITLabel').html('Update Permit Details');
						}
					});
				}

				function delete_PERMIT_MODAL(PERMIT_COST_ID, VENDOR_ID, SOURCE_STATE_ID, VEHICLE_TYPE_ID) {
					$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_update_vehicle_permit_details.php?type=delete_permit_cost&PERMIT_COST_ID=' + PERMIT_COST_ID + '&VEHICLE_TYPE_ID=' + VEHICLE_TYPE_ID + '&VENDOR_ID=' + VENDOR_ID + '&SOURCE_STATE_ID=' + SOURCE_STATE_ID + '', function() {
						const container = document.getElementById("confirmDELETEINFODATA");
						const modal = new bootstrap.Modal(container);
						modal.show();
					});
				}

				function confirmPERMITCOSTDELETE(PERMIT_COST_ID, VEHICLE_TYPE_ID, VENDOR_ID, SOURCE_STATE_ID) {
					$.ajax({
						url: 'engine/ajax/__ajax_update_vehicle_permit_details.php?type=confirm_delete_permit_cost',
						type: 'POST',
						data: {
							VEHICLE_TYPE_ID: VEHICLE_TYPE_ID,
							VENDOR_ID: VENDOR_ID,
							SOURCE_STATE_ID: SOURCE_STATE_ID
						},
						dataType: 'json',
						success: function(response) {
							if (response.result === true) {
								$('#confirmDELETEINFODATA').modal('hide'); // Hide modal after success
								TOAST_NOTIFICATION('success', 'Successfully Deleted the Permit Cost', 'Success !!!');
								location.reload();
							} else {
								TOAST_NOTIFICATION('error', 'Unable to Delete the Permit Cost', 'Error !!!');
							}
						},
						error: function(xhr, status, error) {
							TOAST_NOTIFICATION('error', 'An error occurred: ' + error, 'Error !!!');
						}
					});
				}
			</script>
		<?php

	elseif ($_GET['type'] == 'preview') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			$preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=add&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=add&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=add&formtype=permit_cost_info&id=' . $vendor_ID;
			$preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;

		?>
			<!-- Default Wizard -->
			<div class="row">
				<div class="col-12">
					<div id="wizard-validation" class="bs-stepper mt-2">
						<div class="bs-stepper-header border-0 justify-content-center py-2">
							<div class="step" data-target="#basic_info">
								<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">1</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Basic Info</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#branch_info">
								<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#permit_cost">
								<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle  disble-stepper-title">5</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title  disble-stepper-title">Permit Cost
										</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#preview">
								<a href="<?= $preview_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle active-stepper">6</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title">Preview
										</h5>
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
						<div>
							<form onSubmit="return false">
								<!-- Preview -->
								<div id="preview" class="content active dstepper-block">
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12">
													<?php
													$select_vendor = sqlQUERY_LABEL("SELECT `vendor_id`, `vendor_name`, `vendor_code`, `vendor_email`, `vendor_primary_mobile_number`, `vendor_alternative_mobile_number`, `vendor_country`, `vendor_state`, `vendor_city`, `vendor_pincode`, `vendor_othernumber`, `vendor_address`, `invoice_gstin_number`, `vendor_pan_number`, `vendor_gst_percentage`, `gst_country`, `gst_state`, `gst_city`, `gst_pincode`, `gst_address`,`status` FROM `dvi_vendor_details` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
													while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor)) :
														$vendor_id = $fetch_data['vendor_id'];
														$vendor_name = $fetch_data['vendor_name'];
														$vendor_code = $fetch_data['vendor_code'];
														$vendor_email = $fetch_data['vendor_email'];
														$vendor_primary_mobile_number = $fetch_data['vendor_primary_mobile_number'];
														$vendor_alternative_mobile_number = $fetch_data['vendor_alternative_mobile_number'];
														$vendor_country = $fetch_data['vendor_country'];
														$vendor_state = $fetch_data['vendor_state'];
														$vendor_city = $fetch_data['vendor_city'];
														$vendor_pincode = $fetch_data['vendor_pincode'];
														$vendor_othernumber = $fetch_data['vendor_othernumber'];
														$vendor_address = $fetch_data['vendor_address'];
														$invoice_gstin_number = $fetch_data['invoice_gstin_number'];
														$vendor_pan_number = $fetch_data['vendor_pan_number'];
														$vendor_gst_percentage = $fetch_data['vendor_gst_percentage'];
														$gst_country = $fetch_data['gst_country'];
														$gst_state = $fetch_data['gst_state'];
														$gst_city = $fetch_data['gst_city'];
														$gst_pincode = $fetch_data['gst_pincode'];
														$gst_address = $fetch_data['gst_address'];
														$status = $fetch_data['status'];
													endwhile;

													if ($status == 1) :
														$status = 'Active';
														$status_color = 'text-success';
													else :
														$status = 'In Active';
														$status_color = 'text-danger';
													endif;
													?>
													<div>
														<h5 class="text-primary my-1">Basic Details</h5>
													</div>
													<div class="row mt-3">
														<div class="col-md-3">
															<label>Vendor Name</label>
															<p class="disble-stepper-title"><?= $vendor_name; ?></p>
														</div>
														<div class="col-md-3">
															<label>Email ID</label>
															<p class="disble-stepper-title"><?= $vendor_email; ?></p>
														</div>
														<div class="col-md-3">
															<label>Primary Mobile Number</label>
															<p class="disble-stepper-title"><?= $vendor_primary_mobile_number; ?></p>
														</div>
														<div class="col-md-3">
															<label>Alternative Mobile Number</label>
															<p class="disble-stepper-title"><?= $vendor_alternative_mobile_number; ?></p>
														</div>
														<div class="col-md-3">
															<label>Country</label>
															<p class="disble-stepper-title"><?= getCOUNTRYLIST($vendor_country, 'country_label'); ?></p>
														</div>

														<div class="col-md-3">
															<label>State</label>
															<p class="disble-stepper-title"><?= getSTATELIST('', $vendor_state, 'state_label'); ?></p>
														</div>
														<div class="col-md-3">
															<label>City</label>
															<p class="disble-stepper-title"><?= getCITYLIST('', $vendor_city, 'city_label'); ?></p>
														</div>
														<div class="col-md-3">
															<label>Pincode</label>
															<p class="disble-stepper-title"><?= $vendor_pincode; ?></p>
														</div>

														<div class="col-md-3">
															<label>Other Number</label>
															<p class="disble-stepper-title"><?= $vendor_othernumber; ?></p>
														</div>
														<div class="col-md-3">
															<label>Status</label>
															<p class="<?= $status_color; ?> fw-bold"><?= $status ?></p>
														</div>
														<div class="col-md-6">
															<label>Address</label>
															<p class="disble-stepper-title"><?= $vendor_address; ?></p>
														</div>
													</div>
													<?php if ($invoice_gstin_number != '') : ?>
														<div class="divider">
															<div class="divider-text text-secondary">
																<i class="ti ti-star"></i>
															</div>
														</div>
														<div class="row">
															<div>
																<h5 class="text-primary mt-1 mb-3">GST Details</h5>
															</div>
															<div class="col-md-3">
																<label>GSTIN Number</label>
																<p class="disble-stepper-title"><?= $invoice_gstin_number; ?></p>
															</div>
															<div class="col-md-3">
																<label>PAN Number</label>
																<p class="disble-stepper-title"><?= $vendor_pan_number; ?></p>
															</div>
															<div class="col-md-3">
																<label>GST Percentage</label>
																<p class="disble-stepper-title"><?= getGSTDETAILS($vendor_gst_percentage, 'label'); ?></p>
															</div>
															<div class="col-md-3">
																<label>Country</label>
																<p class="disble-stepper-title"><?= getCOUNTRYLIST($gst_country, 'country_label'); ?></p>
															</div>
															<div class="col-md-3">
																<label>State</label>
																<p class="disble-stepper-title"><?= getSTATELIST('', $gst_state, 'state_label'); ?></p>
															</div>
															<div class="col-md-3">
																<label>City</label>
																<p class="disble-stepper-title"><?= getCITYLIST('', $gst_city, 'city_label'); ?></p>
															</div>
															<div class="col-md-3">
																<label>Pincode</label>
																<p class="disble-stepper-title"><?= $gst_pincode; ?></p>
															</div>

															<div class="col-md-3">
																<label>Address</label>
																<p class="disble-stepper-title"><?= $gst_address; ?></p>
															</div>
														</div>
													<?php endif; ?>
												</div>

												<div class="divider">
													<div class="divider-text text-secondary">
														<i class="ti ti-star"></i>
													</div>
												</div>

												<div class="col-md-12">
													<?php
													$select_vendor_branch = sqlQUERY_LABEL("SELECT `vendor_branch_id`, `vendor_branch_name` FROM `dvi_vendor_branches` WHERE `vendor_id`= '$vendor_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
													?>
													<div class="d-flex justify-content-between align-items-center">
														<h5 class="text-primary mb-0">Branch Details</h5>

														<div class="d-flex align-items-center">
															<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
															<select class="form-select form-select-sm" name="choose_branch" id="choose_branch" onchange="change_choose_branch();" data-parsley-trigger="keyup">
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
														"SELECT `vehicle_id`, `vehicle_type_id`, `vendor_branch_id` FROM `dvi_vehicle` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0'"
													) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
													?>
													<div class="d-flex justify-content-between align-items-center">
														<h5 class="text-primary my-1">Vehicle Details</h5>
														<div class="d-flex align-items-center">
															<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
															<select class="form-select form-select-sm" name="choose_vehicle" id="choose_vehicle" onchange="change_choose_vehicle()" data-parsley-trigger="keyup">
																<?php
																while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
																	$vehicle_id = $fetch_data['vehicle_id'];
																	$vehicle_type_id = $fetch_data['vehicle_type_id'];
																	$vendor_branch_name = getBranchLIST($fetch_data['vendor_branch_id'], 'branch_label');
																	$vehicle_name = getVEHICLELIST($vehicle_type_id, 'vehicle_label');
																	echo "<option value='$vehicle_id'>$vehicle_name - $vendor_branch_name</option>";
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
														"SELECT `vehicle_type_id` FROM `dvi_permit_cost` WHERE `vendor_id`= '$vendor_ID' AND  `deleted` = '0' GROUP BY `vehicle_type_id` "
													) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
													$permitcost_num = sqlNUMOFROW_LABEL($select_vendor_branch);
													?>
													<div class="d-flex justify-content-between align-items-center">
														<h5 class="text-primary my-1">Permit Cost Details</h5>
														<?php if ($permitcost_num > 0) : ?>
															<div class="d-flex align-items-center">
																<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
																<select class="form-select form-select-sm" name="choose_permitcost" id="choose_permitcost" onchange="change_choose_permitcost()">
																	<?php
																	while ($fetch_data = sqlFETCHARRAY_LABEL($select_vendor_branch)) {
																		$vehicle_type_id = $fetch_data['vehicle_type_id'];
																		$vehicle_type_name =  getVEHICLELIST($vehicle_type_id, 'vehicle_label');
																		echo "<option value='$vehicle_type_id'>$vehicle_type_name</option>";
																	}
																	?>
																</select>
															</div>
														<?php endif; ?>
													</div>

													<span id="permitcost_perview"></span>

												</div>
												<div class="d-flex justify-content-between mt-4">
													<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn btn-secondary">Back</a>
													<a href="newvendor.php" class="btn btn-primary float-end ms-2">Submit</a>
												</div>
											</div>

										</div>
									</div>

								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- /Default Wizard -->
			<script>
				$(document).ready(function() {
					choose_branch();
					choose_vehicle();
					choose_permitcost();
				});

				function change_choose_permitcost() {
					var choose_permitcost = $('#choose_permitcost').val();
					var vendor_id = '<?= $vendor_ID; ?>';

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

				// branch script start
				function change_choose_branch() {
					var choose_branch = $('#choose_branch').val();
					var vendor_id = '<?= $vendor_ID; ?>';

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
				// branch script end

				// vehicle script start
				function change_choose_vehicle() {
					var choose_vehicle = $('#choose_vehicle').val();
					var vehicle_type_id = '<?= $vehicle_type_id; ?>';
					var vendor_id = '<?= $vendor_ID; ?>';

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
				// vehicle script end



				// vehicle script start
				function choose_permitcost() {
					var choose_permitcost = $('#choose_permitcost').val();
					var vendor_id = '<?= $vendor_ID; ?>';

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
				// vehicle script end

				// branch script start
				function choose_branch() {
					var choose_branch = $('#choose_branch').val();
					var vendor_id = '<?= $vendor_ID; ?>';

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
				// branch script end

				// vehicle script start
				function choose_vehicle() {
					var choose_vehicle = $('#choose_vehicle').val();
					var vehicle_type_id = '<?= $vehicle_type_id; ?>';
					var vendor_id = '<?= $vendor_ID; ?>';

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
				// vehicle script end
			</script>
		<?php

	elseif ($_GET['type'] == 'permit_cost') :

		$vendor_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($vendor_ID != '' && $vendor_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newvendor.php?route=edit&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=edit&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=edit&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=edit&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=edit&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'newvendor.php?route=edit&formtype=preview&id=' . $vendor_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newvendor.php?route=add&formtype=basic_info&id=' . $vendor_ID;
			$branch_info_url = 'newvendor.php?route=add&formtype=branch_info&id=' . $vendor_ID;
			$driver_cost_url = 'newvendor.php?route=add&formtype=driver_cost&id=' . $vendor_ID;
			$vehicle_pricebook_url = 'newvendor.php?route=edit&formtype=vehicle_pricebook&id=' . $vendor_ID;
			$vehicle_info_url = 'newvendor.php?route=add&formtype=vehicle_info&id=' . $vendor_ID;
			$permit_cost_info_url = 'newvendor.php?route=add&formtype=permit_cost_info&id=' . $vendor_ID;
			// $preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save & Continue";
		endif;
		?>
			<!-- Default Wizard -->


			<div class="row">
				<div class="col-12">
					<div id="wizard-validation" class="bs-stepper mt-2">
						<div class="bs-stepper-header border-0 justify-content-center py-2">
							<div class="step" data-target="#basic_info">
								<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">1</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Basic Info</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#branch_info">
								<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">2</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Branch</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">3</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_info">
								<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">4</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#vehicle_Pricebook">
								<a href="<?= $vehicle_pricebook_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
									<span class="stepper_for_vendor bs-stepper-circle disble-stepper-title">5</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title disble-stepper-title">Vehicle Pricebook</h5>
									</span>
								</a>
							</div>
							<div class="line">
								<i class="ti ti-chevron-right"></i>
							</div>
							<div class="step" data-target="#permit_cost">
								<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
									<span class="stepper_for_vendor bs-stepper-circle active-stepper">6</span>
									<span class="bs-stepper-label mt-3">
										<h5 class="stepper_for_vendor bs-stepper-title">Permit Cost
										</h5>
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
						<div>
							<div class="row mt-3">
								<div class="col-md-12">
									<div class="d-flex justify-content-between align-items-center">
										<h5 class="card-title text-primary">Add Permit Cost</h5>
										<button type="button" class="btn btn-secondary" onclick="showPERMITCOSTLIST('<?= $vendor_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost_info');">Back To List
										</button>
									</div>

									<form id="permit_cost_form" method="POST" data-parsley-validate>
										<div class="row">
											<div class="col-md-6 mb-3">
												<label class="form-label" for="vehicle_type">Vehicle Type <span class="text-danger">*</span></label>
												<select class="form-control form-select" name="vehicle_type" id="vehicle_type" data-parsley-required="true" required onchange="toggleStateInputs();">
													<?= //getVEHICLETYPE('', 'select');  
													getVENDOR_VEHICLE_TYPES($vendor_ID, '', 'select'); ?>
												</select>
											</div>
											<div class="col-md-6 mb-3">
												<label class="form-label" for="selected_state">State <span class="text-danger">*</span></label>
												<select class="form-control form-select" name="selected_state" id="permit_state" onchange="toggleStateInputs();" data-parsley-required="true" required>
													<option value="">Select Any One</option>
												</select>
											</div>

											<input type="hidden" name="hidden_vendor_ID" id="hidden_vendor_ID" value="<?= $vendor_ID; ?>" hidden>
										</div>
										<div class="row align-items-end" id="stateInputContainer">
										</div>
										<div class="d-flex justify-content-between mt-4">
											<button type="button" class="btn btn-secondary" onclick="showPERMITCOSTLIST('<?= $vendor_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost_info');">Back To List</button>
											<button type="submit" class="btn btn-primary float-end ms-2" id="permit_cost_form_submit">Save</button>
										</div>
									</form>
								</div>
							</div>
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
							//console.log(stateInputMap);

							// Populate the dropdown and initialize Selectize here
							populateDropdown();
							initializeSelectize();
						},
						error: function(xhr, status, error) {
							//console.log("Request failed with status: " + status);
						}
					});

					/*$("#permit_cost_form").submit(function(event) {
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
								if (response.errros.vehicle_type_id_required) {
									TOAST_NOTIFICATION('warning', 'Vehicle ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.errros.selectedState_required) {
									TOAST_NOTIFICATION('warning', 'Selected State is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.errros.hidden_permit_vendor_ID_required) {
									TOAST_NOTIFICATION('warning', 'Vendor ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
								}
							} else {
								//SUCCESS RESPOSNE
								if (response.i_result == true) {
									//RESULT SUCCESS
									TOAST_NOTIFICATION('success', 'Permit Cost Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
									showvendorFORMSTEP4('<?= $vendor_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost_info');
								} else if (response.i_result == false) {
									TOAST_NOTIFICATION('success', 'Unable to Add Permit Cost Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.u_result == true) {
									//RESULT SUCCESS
									TOAST_NOTIFICATION('success', 'Permit Cost Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
									showvendorFORMSTEP4('<?= $vendor_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost_info');
								} else if (response.u_result == false) {
									TOAST_NOTIFICATION('success', 'Unable to Update Permit Cost Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

					//AJAX FORM SUBMIT
					$("#permit_cost_form").submit(function(event) {
						var form = $('#permit_cost_form')[0];
						var data = new FormData(form);
						//var spinner = $("#spinner");
						//console.log(data);
						//$(this).find("button[type='submit']").prop('disabled', true);
						// spinner.show();
						$.ajax({
							type: "post",
							url: 'engine/ajax/__ajax_manage_vendor.php?type=update_permit_cost',
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
								//NOT SUCCESS RESPONSE
								if (response.errors.vehicle_type_id_required) {
									TOAST_NOTIFICATION('error', 'Vehicle Type Required', 'Error !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.errors.selectedState_required) {
									TOAST_NOTIFICATION('error', 'State should be required', 'Error !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.errors.hidden_permit_vendor_ID_required) {
									TOAST_NOTIFICATION('error', 'Please Check Choosen Vendor', 'Error !!!', '', '', '', '', '', '', '', '', '');
								} else if (response.errors.vehicle_type_permit_charges_already_exist) {
									TOAST_NOTIFICATION('warning', 'Choosen Vehicle Type Permit Charges Already Added.', 'Warning !!!', '', '', '', '', '', '', '', '', '');
								}
							} else {
								//SUCCESS RESPOSNE
								//$('#editPERMITFORM').modal('hide');
								TOAST_NOTIFICATION('success', 'Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									// Reload the window
									window.location.reload();
								}, 3000); // 3000 milliseconds = 3 seconds
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

				/*function toggleStateInputs() {
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
								columnDiv.className = "col-md-2 mb-3";

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
				}*/

				function toggleStateInputs() {
					var SOURCE_STATE_ID = $("#permit_state").val();
					var VEHICLE_TYPE_ID = $("#vehicle_type").val();
					var VENDOR_ID = '<?= $vendor_ID ?>';

					if (SOURCE_STATE_ID != "" && VEHICLE_TYPE_ID != "" && VENDOR_ID != "") {
						$.ajax({
							type: 'post',
							url: 'engine/ajax/__ajax_update_vehicle_permit_details.php?type=update_permit_cost&VEHICLE_TYPE_ID=' + VEHICLE_TYPE_ID + '&VENDOR_ID=' + VENDOR_ID + '&SOURCE_STATE_ID=' + SOURCE_STATE_ID,
							dataType: 'json',
							success: function(response) {
								if (!response.success) {
									// Handle error
									if (response.errors.vehicle_type_permit_charges_already_exist) {
										TOAST_NOTIFICATION('warning', 'Chosen Vehicle Type Permit Charges Already Added.', 'Warning !!!');
									}
								} else {
									// Inject HTML into the container
									$('#stateInputContainer').html(response.html);
								}
							},
							error: function(xhr, status, error) {
								// Handle AJAX errors
								TOAST_NOTIFICATION('error', 'An error occurred: ' + error, 'Error !!!');
							}
						});
					}
				}

				// Initially populate the dropdown and hide all input fields
				populateDropdown();
				//toggleStateInputs();

				function showPERMITCOSTLIST(ID, ROUTE, TYPE) {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_add_vendor_newform.php?type=' + TYPE + '&ROUTE=' + ROUTE + '&ID=' + ID,
						success: function(response) {
							$('#showvendorLIST').html('');
							$('#showvendorFORMSTEP1').html('');
							$('#showvendorFORMSTEP2').html('');
							$('#showvendorFORMSTEP3').html('');
							$('#showvendorFORMSTEP4').html(response);
							$('#showvendorPREVIEW').html('');

							// Scroll to the top of the page
							window.scrollTo({
								top: 0,
								behavior: 'smooth' // Use smooth scrolling if supported
							});
						}
					});
				}
			</script>
		<?php
	elseif ($_GET['type'] == 'delete_branch') :

		$vendor_branch_ID = $_GET['vendor_branch_ID'];
		$vendor_ID = $_GET['vendor_ID'];
		$ROUTE = $_GET['ROUTE'];

		$select_vendor_id_already_used_vehicle = sqlQUERY_LABEL("SELECT COUNT(`vehicle_id`) AS TOTAL_USED_COUNT FROM `dvi_vehicle` WHERE `status` = '1' and `vendor_id` = '$vendor_ID' and `vendor_branch_id`='$vendor_branch_ID' and `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_DETAILS:" . sqlERROR_LABEL());
		while ($fetch_vendor_vehicle_data = sqlFETCHARRAY_LABEL($select_vendor_id_already_used_vehicle)) :
			$TOTAL_USED_COUNT_VEHICLE = $fetch_vendor_vehicle_data['TOTAL_USED_COUNT'];
		endwhile;

		?>
			<div class="row p-2">
				<div class="modal-body">
					<?php if ($TOTAL_USED_COUNT_VEHICLE == 0) : ?>
						<div class="text-center">
							<h3 class="mb-2">Confirmation Alert?</h3>
							<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="60" height="60" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 60 60" xml:space="preserve" class="">
								<g>
									<path d="M15.84 22.25H8.16a3.05 3.05 0 0 1-3-2.86L4.25 5.55a.76.76 0 0 1 .2-.55.77.77 0 0 1 .55-.25h14a.75.75 0 0 1 .75.8l-.87 13.84a3.05 3.05 0 0 1-3.04 2.86zm-10-16 .77 13.05a1.55 1.55 0 0 0 1.55 1.45h7.68a1.56 1.56 0 0 0 1.55-1.45l.81-13z" fill="#7D7D7D" opacity="1" data-original="#000000" class=""></path>
									<path d="M21 6.25H3a.75.75 0 0 1 0-1.5h18a.75.75 0 0 1 0 1.5z" fill="#7D7D7D" opacity="1" data-original="#000000" class=""></path>
									<path d="M15 6.25H9a.76.76 0 0 1-.75-.75V3.7a2 2 0 0 1 1.95-1.95h3.6a2 2 0 0 1 1.95 2V5.5a.76.76 0 0 1-.75.75zm-5.25-1.5h4.5v-1a.45.45 0 0 0-.45-.45h-3.6a.45.45 0 0 0-.45.45zM15 18.25a.76.76 0 0 1-.75-.75v-8a.75.75 0 0 1 1.5 0v8a.76.76 0 0 1-.75.75zM9 18.25a.76.76 0 0 1-.75-.75v-8a.75.75 0 0 1 1.5 0v8a.76.76 0 0 1-.75.75zM12 18.25a.76.76 0 0 1-.75-.75v-8a.75.75 0 0 1 1.5 0v8a.76.76 0 0 1-.75.75z" fill="#7D7D7D" opacity="1" data-original="#000000" class=""></path>
								</g>
							</svg>
							<p class="mb-0 mt-2">Are you sure? Want to delete this Branch <b></b><br /> This action cannot be undo.</p>
						</div>
				</div>
				<div class="modal-footer d-flex justify-content-center">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" onclick="confirmBRANCHDELETE('<?= $vendor_branch_ID; ?>','<?= $vendor_ID; ?>', '<?= $ROUTE; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
				</div>
			<?php else :
			?>
				<div class="text-center">
					<svg class="icon-44 text-warning" width="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M4.81409 20.4368H19.1971C20.7791 20.4368 21.7721 18.7267 20.9861 17.3527L13.8001 4.78775C13.0091 3.40475 11.0151 3.40375 10.2231 4.78675L3.02509 17.3518C2.23909 18.7258 3.23109 20.4368 4.81409 20.4368Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M12.0024 13.4147V10.3147" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
						<path d="M11.995 16.5H12.005" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
					</svg>
				</div>
				<h6 class="mt-4 mb-2 text-center">Sorry !!! You cannot delete this Branch.</h6>
				<p class="text-center"> Since this Branch is assigned with Vehicles.</p>
				<div class="text-center pb-0">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				</div>
			<?php endif;
			?>
			</div>
			<script>
				function confirmBRANCHDELETE(vendor_branch_ID, vendor_ID, ROUTE) {
					$.ajax({
						type: "POST",
						url: "engine/ajax/__ajax_manage_vendor.php?type=confirm_branch_delete",
						data: {
							vendor_branch_ID: vendor_branch_ID,
							vendor_ID: vendor_ID,
							ROUTE: ROUTE
						},
						dataType: 'json',
						success: function(response) {
							if (!response.success) {
								//NOT SUCCESS RESPONSE
								if (response.result_success) {
									TOAST_NOTIFICATION('error', 'Unable to delete the Branch', 'Error !!!', '', '', '', '', '', '', '', '', '');
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
	endif;
else :
	echo "Request Ignored";
endif;
