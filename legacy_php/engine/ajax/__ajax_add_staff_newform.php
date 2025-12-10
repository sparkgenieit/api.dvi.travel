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

		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$select_staff_list_query = sqlQUERY_LABEL("SELECT `staff_name`, `staff_mobile`, `staff_email`, `roleID` FROM `dvi_staff_details` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_LIST:" . sqlERROR_LABEL());
			while ($fetch_staff_list_data = sqlFETCHARRAY_LABEL($select_staff_list_query)) :
				$roleID = $fetch_staff_list_data['roleID'];
				$staff_name = $fetch_staff_list_data['staff_name'];
				$staff_mobile = $fetch_staff_list_data['staff_mobile'];
				$staff_email = $fetch_staff_list_data['staff_email'];
			endwhile;

			$select_staff_credientials = sqlQUERY_LABEL("SELECT `userID`, `staff_id`, `user_profile`, `username`, `password` FROM `dvi_users` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_STAFF_CREDIENTIALS_LIST:" . sqlERROR_LABEL());
			while ($fetch_staff_credientials_list_data = sqlFETCHARRAY_LABEL($select_staff_credientials)) :
				$staff_username = $fetch_staff_credientials_list_data['username'];
				$staff_password = $fetch_staff_credientials_list_data['password'];
			endwhile;

			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;
			$button_label = "Update";
		else :
			$basic_info_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save";
		endif;

		if ($staff_ID) :
			$pwd_required = '';
			$email_readonly = 'readonly';
		else :
			$pwd_required = 'required';
			$email_readonly = '';
		endif;
?>
		<!-- Default Wizard -->

		<div class="row mt-3">
			<div class="col-md-12">
				<div class="card p-4">
					<div>
						<form id="form_basic_info" action="" method="POST" autocomplete="off" data-parsley-validate>
							<!-- Basic Info -->
							<div id="basic_info" class="content active dstepper-block">
								<div class="content-header mb-3">
									<h5 class="text-primary mb-0">Staff Details</h5>
								</div>
								<div class="row g-3">
									<div class="col-sm-3">
										<label class="form-label" for="staff_name">Staff Name<span class="text-danger"> *</span></label>
										<input type="text" name="staff_name" id="staff_name" class="form-control" placeholder="Staff Name" required value="<?= $staff_name ?>" />
									</div>
									<div class="col-sm-3">
										<label class="form-label" for="staff_email">Email ID<span class=" text-danger"> *</span></label>
										<input type="email" name="staff_email" id="staff_email" class="form-control" placeholder="Email ID" aria-label="Email ID" data-parsley-type="email" required <?= $email_readonly; ?> data-parsley-check_staff_email data-parsley-check_staff_email-message="Entered staff Email Already Exists" value="<?= $staff_email ?>" data-parsley-trigger="keyup" />
										<input type="hidden" name="old_staff_email" id="old_staff_email" value="<?= $staff_email; ?>" />
									</div>
									<div class="col-sm-3">
										<label class="form-label" for="staff_mobile">Mobile Number<span class=" text-danger"> *</span></label>
										<input type="tel" name="staff_mobile" id="staff_mobile" class="form-control" placeholder="Mobile" aria-label="Email ID" data-parsley-type="number" data-parsley-trigger="keyup" required data-parsley-check_staff_mobile data-parsley-check_staff_mobile-message="Entered staff Mobile Number Already Exists" data-parsley-pattern="^\+?[1-9]\d{1,14}$" minlength="10" maxlength="10" value="<?= $staff_mobile ?>" />
										<input type="hidden" name="old_staff_mobile" id="old_staff_mobile" value="<?= $staff_mobile; ?>" />
									</div>

									<!--<div class="col-md-3">
										<label class="form-label" for="staff_password">Password <span class=" text-danger"> <?= (($staff_ID == '' || $staff_ID == 0) && $ROUTE == 'add') ? "*" : "" ?></span></label>
										<div class="form-group">
											<input type="password" name="staff_password" id="staff_password" class="form-control" placeholder="Password" <?= $pwd_required; ?> />
										</div>
									</div>-->

									<div class="col-md-3">
										<label class="form-label" for="staff_password">
											Password <span class="text-danger"><?= (($staff_ID == '' || $staff_ID == 0) && $ROUTE == 'add') ? "*" : "" ?></span>
										</label>
										<div class="form-group position-relative">
											<input type="password" name="staff_password" id="staff_password" class="form-control" placeholder="Password" <?= $pwd_required; ?> />
											<span id="togglePassword" class="position-absolute" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer;">
												<i class="fa fa-eye"></i>
											</span>
										</div>
									</div>

									<?php if ($logged_user_level != '4' && $roleID  != '0') : ?>
										<div class="col-sm-3">
											<label class="form-label" for="staff_select_role">Role<span class=" text-danger"> *</span></label>
											<select class="form-select" name="staff_select_role" id="staff_select_role" data-parsley-errors-container="#staff_role_error_container" required>
												<?= getRole($roleID, 'select'); ?>
											</select>
											<div id="staff_role_error_container"></div>
										</div>
									<?php endif; ?>

									<input type="hidden" name="hidden_staff_ID" id="hidden_staff_ID" value="<?= $staff_ID; ?>" hidden>

									<div class="row g-3 mt-2">
										<div class="col-12 d-flex justify-content-between">
											<div>
												<a href="newstaff.php" class="btn btn-secondary">Back
												</a>
											</div>
											<button type="submit" class="btn btn-primary float-end ms-2" id="permit_cost_form_submit">Save</button>
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

		<script src="assets/js/parsley.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
		<script src="assets/js/selectize/selectize.min.js"></script>

		<script>
			const togglePassword = document.querySelector('#togglePassword');
			const passwordField = document.querySelector('#staff_password');

			togglePassword.addEventListener('click', function(e) {
				// Toggle the type attribute
				const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
				passwordField.setAttribute('type', type);

				// Toggle the eye icon
				this.querySelector('i').classList.toggle('fa-eye');
				this.querySelector('i').classList.toggle('fa-eye-slash');
			});

			document.getElementById('staff_email').addEventListener('input', function() {
				var staffEmail = this.value; // Get the email from the input field

				// Extract the username and password based on the email
				var staffUsername = staffEmail.substring(0, staffEmail.indexOf('@'));
				// var staffPassword = staffEmail.substring(0, staffEmail.indexOf('@'));

				// Set the values of the username and password input fields
				document.getElementById('staff_username').value = staffUsername;
				// document.getElementById('staff_password').value = staffPassword;
			});



			$(document).ready(function() {
				$("select").selectize();

				//CHECK DUPLICATE staff EMAIL ID
				$('#staff_email').parsley();
				var old_staff_emailDETAIL = document.getElementById("old_staff_email").value;
				var staff_email = $('#staff_email').val();
				window.ParsleyValidator.addValidator('check_staff_email', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_staff_email.php',
							method: "POST",
							data: {
								staff_email: value,
								old_staff_email: old_staff_emailDETAIL
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});


				//CHECK DUPLICATE staff MOBILE NO
				$('#staff_mobile').parsley();
				var old_staff_mobileDETAIL = document.getElementById("old_staff_mobile").value;
				var staff_mobile = $('#staff_mobile').val();
				window.ParsleyValidator.addValidator('check_staff_mobile', {
					validateString: function(value) {
						return $.ajax({
							url: 'engine/ajax/__ajax_check_staff_mobile_number.php',
							method: "POST",
							data: {
								staff_mobile_number: value,
								old_staff_mobile_number: old_staff_mobileDETAIL
							},
							dataType: "json",
							success: function(data) {
								return true;
							}
						});
					}
				});

				//AJAX FORM SUBMIT
				$("#form_basic_info").submit(function(event) {
					var form = $('#form_basic_info')[0];
					var data = new FormData(form);
					// $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
					$.ajax({
						type: "post",
						url: 'engine/ajax/__ajax_manage_staff.php?type=staff_basic_info',
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
							if (response.errors.staff_name_required) {
								TOAST_NOTIFICATION('warning', 'staff Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_email_required) {
								TOAST_NOTIFICATION('warning', 'staff Email Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_mobile_required) {
								TOAST_NOTIFICATION('warning', 'staff Primary Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_mobile_no_already_exist) {
								TOAST_NOTIFICATION('warning', 'Staff Mobile Number already exists', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_email_address_already_exist) {
								TOAST_NOTIFICATION('warning', 'Staff Email ID already exists', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'staff basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.i_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to create staff basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'staff basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to update staff basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_email_address_already_exist) {
								TOAST_NOTIFICATION('warning', 'staff email id already exists', 'Warning !!!', '', '', '', '', '', '', '', '', '');
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

		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;

			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update";
		else :
			$basic_info_url = 'newstaff.php?route=add&formtype=basic_info&id=' . $staff_ID;

			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save";
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
								<span class="stepper_for_staff bs-stepper-circle  disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title  disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Default Wizard -->

		<script src="assets/js/parsley.min.js"></script>
		<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
		<script src="assets/js/selectize/selectize.min.js"></script>

		<script>
			function staffBranchLocation_location() {
				var staffBranchLocationInput = document.getElementById('staff_branch_location');
				// var staffBranchLocationAutocomplete = new google.maps.places.Autocomplete(staffBranchLocationInput);
			}

			$(document).ready(function() {
				$(".form-select").selectize();
				<?php
				if ($staff_ID != '' && $staff_ID != 0) :
					$select_staff_branch_add_btn_list_query = sqlQUERY_LABEL("SELECT `staff_id` FROM `dvi_staff_branches` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_staff_LIST:" . sqlERROR_LABEL());
					$total_staff_branch_add_btn_list_num_rows_count = sqlNUMOFROW_LABEL($select_staff_branch_add_btn_list_query);
					if ($total_staff_branch_add_btn_list_num_rows_count > 0) : ?>
						staff_branch_counter = <?= $total_staff_branch_add_btn_list_num_rows_count; ?>;
						<?php
						while ($fetch_staff_branch_list_data = sqlFETCHARRAY_LABEL($select_staff_branch_add_btn_list_query)) :
							$staff_branch_list_count++;
						?>
							$("#staff_branch_country_<?= $staff_branch_list_count; ?>").attr('required', true);
							$("#staff_branch_state_<?= $staff_branch_list_count; ?>").attr('required', true);
							$("#staff_branch_city_<?= $staff_branch_list_count; ?>").attr('required', true);
							$("#staff_branch_gst_<?= $staff_branch_list_count; ?>").attr('required', true);
						<?php endwhile;
					else : ?>
						staff_branch_counter = 1;

						$("#staff_branch_country_1").attr('required', true);
						$("#staff_branch_state_1").attr('required', true);
						$("#staff_branch_city_1").attr('required', true);
						$("#staff_branch_gst_1").attr('required', true);
				<?php endif;
				endif;
				?>

				//CHECK DUPLICATE staff EMAIL ID
				<?php
				if ($staff_ID != '' && $staff_ID != 0) :
					$total_staff_branch_list_num_rows_count = 0;
					$staff_branch_count = 0;
					$select_staff_branch_list_query = sqlQUERY_LABEL("SELECT `staff_branch_id`, `staff_id`, `staff_branch_name`,  `staff_branch_emailid`, `staff_branch_primary_mobile_number`, `staff_branch_alternative_mobile_number`, `staff_branch_country`, `staff_branch_state`, `staff_branch_city`, `staff_branch_pincode`, `staff_branch_location`, `staff_branch_gst_type`, `staff_branch_gst`, `staff_branch_address`, `status` FROM `dvi_staff_branches` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_staff_LIST:" . sqlERROR_LABEL());
					$total_staff_branch_list_num_rows_count = sqlNUMOFROW_LABEL($select_staff_branch_list_query);

					while ($fetch_staff_branch_data = sqlFETCHARRAY_LABEL($select_staff_branch_list_query)) :
						$staff_branch_count++;
				?>
						$('#staff_branch_emailid_<?= $staff_branch_count ?>').parsley();
						var old_staff_branch_emailidDETAIL_<?= $staff_branch_count ?> = document.getElementById("old_staff_branch_emailid_<?= $staff_branch_count ?>").value;
						var staff_branch_emailid_<?= $staff_branch_count ?> = $('#staff_branch_emailid_<?= $staff_branch_count ?>').val();
						window.ParsleyValidator.addValidator('check_staff_email_<?= $staff_branch_count ?>', {
							validateString: function(value) {
								return $.ajax({
									url: 'engine/ajax/__ajax_check_staff_branch_emailid.php',
									method: "POST",
									data: {
										staff_branch_emailid: value,
										old_staff_branch_emailid: old_staff_branch_emailidDETAIL_<?= $staff_branch_count ?>
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
					$('#staff_branch_emailid_1').parsley();
					var old_staff_branch_emailidDETAIL_1 = document.getElementById("old_staff_branch_emailid_1").value;
					var staff_branch_emailid_1 = $('#staff_branch_emailid_1').val();
					window.ParsleyValidator.addValidator('check_staff_email_1', {
						validateString: function(value) {
							return $.ajax({
								url: 'engine/ajax/__ajax_check_staff_branch_emailid.php',
								method: "POST",
								data: {
									staff_branch_emailid: value,
									old_staff_branch_emailid: old_staff_branch_emailidDETAIL_1
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
					staff_branch_counter++;

					e.preventDefault();

					$("#show_item").prepend(`<div class="row g-3" id="show_` + staff_branch_counter + `"><div class="col-md-12 mt-4"><h6 class="m-0 branch_heading">Branch #` + staff_branch_counter + `</h6></div><div class="col-md-4"><label class="form-label" for="staff_branch_name_` + staff_branch_counter + `">Branch Name <span class="text-danger">*</span></label> <input type="text" name="staff_branch_name[]" id="staff_branch_name_` + staff_branch_counter + `" class="form-control" placeholder="Branch Name" required /></div><div class="col-md-4"><label class="form-label" for="staff_branch_location_` + staff_branch_counter + `">Branch Location<span class="text-danger"> *</span></label><div class="form-group"><input type="text" name="staff_branch_location[]" onkeypress="staffBranchLocation_location()"  id="staff_branch_location_` + staff_branch_counter + `" class="form-control" placeholder="Branch Location" required /></div></div><div class="col-md-4"><label class="form-label" for="staff_branch_emailid_` + staff_branch_counter + `">Email ID <span class="text-danger">*</span></label><input type="text" name="staff_branch_emailid[]" id="staff_branch_emailid_` + staff_branch_counter + `" class="form-control" placeholder="Email ID" data-parsley-type="email" required data-parsley-check_staff_email_` + staff_branch_counter + ` data-parsley-check_staff_email_` + staff_branch_counter + `-message="Entered staff Email Already Exists" /><input type="hidden" name="old_staff_branch_emailid[]" id="old_staff_branch_emailid_` + staff_branch_counter + `" value="<?= $staff_branch_emailid; ?>" /></div><div class="col-md-4"><div class="form-group"><label class="form-label" for="staff_branch_primary_mobile_number_` + staff_branch_counter + `">Primary Mobile Number <span class="text-danger">*</span></label><div class="select2-primary"> <input type="tel" name="staff_branch_primary_mobile_number[]" id="staff_branch_primary_mobile_number_` + staff_branch_counter + `" class="form-control" placeholder="Primary Mobile Number" required /></div></div></div><div class="col-md-4"><label class="form-label" for="staff_branch_alternative_mobile_number_` + staff_branch_counter + `">Alternative Mobile Number <span class="text-danger">*</span></label><input type="tel" name="staff_branch_alternative_mobile_number[]" id="staff_branch_alternative_mobile_number_` + staff_branch_counter + `" class="form-control" placeholder="Alternative Mobile Number" required /></div><div class="col-md-4"><label class="form-label" for="staff_branch_country_` + staff_branch_counter + `">Country <span class="text-danger">*</span></label><div class="form-group"><select class="form-select form-add-select_` + staff_branch_counter + `" name="staff_branch_country[]" id="staff_branch_country_` + staff_branch_counter + `" onchange="CHOOSEN_COUNTRY_ADD(` + staff_branch_counter + `)" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_country_error_container_` + staff_branch_counter + `"><?= getCOUNTRYLIST($staff_branch_country, 'select_country'); ?></select></div><div id="branch_country_error_container_` + staff_branch_counter + `"></div></div><div class="col-md-4"><label class="form-label" for="staff_branch_state_` + staff_branch_counter + `">State <span class="text-danger">*</span></label><div class="form-group"><select class="form-select form-add-select_` + staff_branch_counter + `" name="staff_branch_state[]" id="staff_branch_state_` + staff_branch_counter + `" value="<?= $staff_branch_state; ?>" onchange="CHOOSEN_STATE_ADD(` + staff_branch_counter + `)" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_state_error_container_` + staff_branch_counter + `"><option value="">Choose State</option></select></div><div id="branch_state_error_container_` + staff_branch_counter + `"></div></div><div class="col-md-4"><div class="form-group"><label class="form-label" for="staff_branch_city_` + staff_branch_counter + `">City <span class="text-danger">*</span></label><div class="form-group"><select class="form-select form-add-select_` + staff_branch_counter + `" name="staff_branch_city[]" id="staff_branch_city_` + staff_branch_counter + `" value="<?= $staff_branch_city; ?>" data-parsley-trigger="keyup" data-parsley-errors-container="#branch_city_error_container_` + staff_branch_counter + `"><option value="">Choose City</option></select></div><div id="branch_city_error_container_` + staff_branch_counter + `"></div></div></div><div class="col-md-4"><label class="form-label" for="staff_branch_pincode_` + staff_branch_counter + `">Pincode<span class="text-danger"> *</span></label><div class="form-group"><input type="text" name="staff_branch_pincode[]" id="staff_branch_pincode_` + staff_branch_counter + `" class="form-control" placeholder="Pincode" required /></div></div><div class="col-md-4"><label class="form-label" for="staff_branch_gst_type_` + staff_branch_counter + `">GST Type<span class="text-danger">*</span></label><select id="staff_branch_gst_type_` + staff_branch_counter + `" name="staff_branch_gst_type[]" class="form-control form-select form-add-select_` + staff_branch_counter + `"><?= getGSTTYPE($staff_branch_gst_type, 'select') ?></select></div><div class="col-md-4"><label class="form-label" for="staff_branch_gst_` + staff_branch_counter + `">GST %<span class="text-danger">*</span></label><div class="form-group"><select id ="staff_branch_gst_` + staff_branch_counter + `" name="staff_branch_gst[]" class ="form-control form-select form-add-select_` + staff_branch_counter + `" data-parsley-errors-container="#branch_gst_error_container"><?= getGSTDETAILS('', 'select'); ?></select></div><div id="branch_gst_error_container"></div></div><div class="col-md-4"><label class="form-label" for="staff_branch_address_` + staff_branch_counter + `">Address<span class="text-danger"> *</span></label><div class="form-group"><textarea id="staff_branch_address_` + staff_branch_counter + `" name="staff_branch_address[]" class="form-control" rows="1" placeholder="Address" required=""></textarea></div><input type="hidden" name="hidden_staff_branch_id[]" id="hidden_staff_branch_id_` + staff_branch_counter + `" value="" hidden><input type="hidden" name="hidden_staff_ID[]" id="hidden_staff_ID_` + staff_branch_counter + `" value="<?= $staff_ID; ?>" hidden></div><div class = "col d-flex align-items-center justify-content-end"><button type = "button" class = "btn btn-label-danger mt-4 remove_item_btn"><i class = "ti ti-x ti-xs me-1"></i><span class="align-middle">Delete</span></button></div><div class = "border-bottom border-bottom-dashed my-4"></div></div>`);

					$(".form-add-select_" + staff_branch_counter).selectize();
					var staffBranchLocationInput = document.getElementById('staff_branch_location_' + staff_branch_counter);

					$("#staff_branch_gst_type_" + staff_branch_counter).attr('required', true);
					$("#staff_branch_country_" + staff_branch_counter).attr('required', true);
					$("#staff_branch_state_" + staff_branch_counter).attr('required', true);
					$("#staff_branch_city_" + staff_branch_counter).attr('required', true);
					$("#staff_branch_gst_" + staff_branch_counter).attr('required', true);

					//CHECK DUPLICATE staff EMAIL ID
					$('#staff_branch_emailid_' + staff_branch_counter).parsley();
					var old_staff_branch_emailidDETAIL = document.getElementById("old_staff_branch_emailid_" + staff_branch_counter).value;
					var staff_branch_emailid = $('#staff_branch_emailid_' + staff_branch_counter).val();
					window.ParsleyValidator.addValidator('check_staff_email_' + staff_branch_counter, {
						validateString: function(value) {
							return $.ajax({
								url: 'engine/ajax/__ajax_check_staff_branch_emailid.php',
								method: "POST",
								data: {
									staff_branch_emailid: value,
									old_staff_branch_emailid: old_staff_branch_emailidDETAIL
								},
								dataType: "json",
								success: function(data) {
									return true;
								}
							});
						}
					});


					<?php if ($total_staff_branch_add_btn_list_num_rows_count > 0) : ?>
						for (i = <?= $total_staff_branch_add_btn_list_num_rows_count + 1; ?>; i <= staff_branch_counter; i++) {
							CHOOSEN_COUNTRY_ADD(i);
							CHOOSEN_STATE_ADD(i);
						}
					<?php else : ?>
						CHOOSEN_COUNTRY_ADD(staff_branch_counter);
						CHOOSEN_STATE_ADD(staff_branch_counter);
					<?php endif; ?>
				});

				//AJAX FORM SUBMIT
				$("#form_branch_info").submit(function(event) {
					var form = $('#form_branch_info')[0];
					var data = new FormData(form);
					// $(this).find("button[id='submit_staff_info_btn']").prop('disabled', true);
					$.ajax({
						type: "post",
						url: 'engine/ajax/__ajax_manage_staff.php?type=staff_branch',
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
							if (response.errors.staff_name_required) {
								TOAST_NOTIFICATION('warning', 'staff Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_email_required) {
								TOAST_NOTIFICATION('warning', 'staff Email Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_mobile_required) {
								TOAST_NOTIFICATION('warning', 'staff Primary Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_alternative_mobile_number_required) {
								TOAST_NOTIFICATION('warning', 'staff Alternative Mobile Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_country_required) {
								TOAST_NOTIFICATION('warning', 'staff Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_state_required) {
								TOAST_NOTIFICATION('warning', 'staff State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_city_required) {
								TOAST_NOTIFICATION('warning', 'staff City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_pincode_required) {
								TOAST_NOTIFICATION('warning', 'staff Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_othernumber_required) {
								TOAST_NOTIFICATION('warning', 'staff Other Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_address_required) {
								TOAST_NOTIFICATION('warning', 'staff Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_gstin_number_required) {
								TOAST_NOTIFICATION('warning', 'staff GSTIN Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_pan_number_required) {
								TOAST_NOTIFICATION('warning', 'staff PAN Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_gst_percentage_required) {
								TOAST_NOTIFICATION('warning', 'staff GST Percentage Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_country_required) {
								TOAST_NOTIFICATION('warning', 'staff GST Country Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_state_required) {
								TOAST_NOTIFICATION('warning', 'staff GST State Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_city_required) {
								TOAST_NOTIFICATION('warning', 'staff GST City Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_pincode_required) {
								TOAST_NOTIFICATION('warning', 'staff GST Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.gst_address_required) {
								TOAST_NOTIFICATION('warning', 'staff GST Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.errors.staff_select_role_required) {
								TOAST_NOTIFICATION('warning', 'staff Role Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'staff basic info created successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.i_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to create staff basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'staff basic info Updated successfully.', 'Success !!!', '', '', '', '', '', '', '', '', '');
								setTimeout(function() {
									location.assign(response.redirect_URL);
								}, 1000);
							} else if (response.u_result == false) {
								//RESULT FAILED
								TOAST_NOTIFICATION('success', 'Unable to update staff basic info.', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
						staff_id: '<?= $staff_id; ?>',
						staff_branch_id: '<?= $staff_branch_id; ?>'
					},
					success: function(response) {
						$('#staff_branch_code').val(response);
					}
				});
			}*/

			/*function branch_code_generate_add(staff_branch_counter) {
				alert(staff_branch_counter);
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_generate_code.php?type=show_branch_code',
					type: "post",
					data: {
						staff_id: '<?= $staff_id; ?>',
						staff_branch_id: '',
						staff_branch_counter: staff_branch_counter
					},
					success: function(response) {
						$('#staff_branch_code_' + staff_branch_counter).val(response);
					}
				});
			}*/

			/*function CHOOSEN_COUNTRY() {
				var state_selectize = $("#staff_branch_state")[0].selectize;
				var COUNTRY_ID = $('#staff_branch_country').val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.

						state_selectize.clear();
						state_selectize.clearOptions();
						state_selectize.addOption(response);
						<?php if ($staff_branch_state) : ?>
							state_selectize.setValue('<?= $staff_branch_state; ?>');
						<?php endif; ?>
					}
				});
			}

			function CHOOSEN_STATE() {
				var city_selectize = $("#staff_branch_city")[0].selectize;
				var STATE_ID = $('#staff_branch_state').val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.
						city_selectize.clear();
						city_selectize.clearOptions();
						city_selectize.addOption(response);
						<?php if ($staff_branch_city) : ?>
							city_selectize.setValue('<?= $staff_branch_city; ?>');
						<?php endif; ?>
					}
				});
			}*/

			function CHOOSEN_COUNTRY_ADD(staff_branch_counter) {
				var state_selectize = $("#staff_branch_state_" + staff_branch_counter)[0].selectize;
				var COUNTRY_ID = $('#staff_branch_country_' + staff_branch_counter).val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_country&COUNTRY_ID=' + COUNTRY_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.

						state_selectize.clear();
						state_selectize.clearOptions();
						state_selectize.addOption(response);
						<?php if ($staff_branch_state) : ?>
							state_selectize.setValue('<?= $staff_branch_state; ?>');
						<?php endif; ?>
					}
				});
			}

			function CHOOSEN_STATE_ADD(staff_branch_counter) {
				var city_selectize = $("#staff_branch_city_" + staff_branch_counter)[0].selectize;
				var STATE_ID = $('#staff_branch_state_' + staff_branch_counter).val();
				// Get the response from the server.
				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
					type: "GET",
					success: function(response) {
						// Append the response to the dropdown.
						city_selectize.clear();
						city_selectize.clearOptions();
						city_selectize.addOption(response);
						<?php if ($staff_branch_city) : ?>
							city_selectize.setValue('<?= $staff_branch_city; ?>');
						<?php endif; ?>
					}
				});
			}

			function deletebranch(staff_branch_ID, staff_ID, ROUTE) {
				$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_add_staff_newform.php?type=delete_branch&staff_branch_ID=' + staff_branch_ID + '&staff_ID=' + staff_ID + '&ROUTE=' + ROUTE, function() {
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
		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Continue";
		else :
			$basic_info_url = 'newstaff.php?route=add&formtype=basic_info&id=' . $staff_ID;
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save";
		endif;
	?>
		<!-- Default Wizard -->
		<div class="row">
			<div class="col-12">
				<div id="wizard-validation" class="bs-stepper mt-2">
					<div class="bs-stepper-header border-0 justify-content-center py-2">
						<div class="step" data-target="#basic_info">
							<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle active-stepper">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Permit Cost
									</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#preview">
							<a href="<?= $preview_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Preview
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
							<div id="">
								<div class="content-header pb-3 d-flex justify-content-between">
									<div class="col-md-auto">
										<h5 class="text-primary card-title mb-3 mt-2">List of Vehicle Type - Driver Cost</h5>
									</div>
									<div class="col-md-auto text-end">
										<a href="javascript:void(0)" class="btn btn-label-primary waves-effect" onclick="showVEHICLETYPEDRIVERCOSTMODAL(0);" data-bs-dismiss="modal">+ Add Vehicle Type - Driver Cost</a>
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
														<th scope="col">Driver</br> Bhatta</th>
														<th scope="col">Food</br> Cost</th>
														<th scope="col">Accomdation</br> Cost</th>
														<th scope="col">Extra</br> Cost</th>
														<th scope="col">Morning</br> Charges </th>
														<th scope="col">Evening </br>Charges</th>
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
						"url": "engine/json/__JSONvehicle_type_driver_cost.php?staff_id=" + '<?= $staff_ID ?>',
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
						"render": function(data, type, full) {
							return '<div class="flex align-items-center list-user-action"><a class="btn btn-sm btn-icon text-primary flex-end"  data-bs-toggle="tooltip" data-bs-placement="center" title="Edit" href="javascript:void(0);" onclick="showVEHICLETYPEDRIVERCOSTMODAL(' + data + ');" style="margin-right: 10px;"><span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" > <path d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path fill-rule="evenodd" clip-rule="evenodd" d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a>  <a class="btn btn-sm btn-icon text-danger flex-end" href="javascript:void(0);" onclick="showDELETEVEHICLETYPEMODAL(' + data + ');" aria-label="Delete" data-bs-original-title="Delete"> <span class="btn-inner"> <svg style="width: 22px; height: 22px;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path> </svg> </span> </a> </div>';
						}
					}],
				});
			});

			function checkVehicleType() {
				var vehicleTypeAdded = table.rows().count();
				if (vehicleTypeAdded > 0) {
					window.location.href = "newstaff.php?route=<?= $ROUTE; ?>&formtype=vehicle_info&id=<?= $staff_ID; ?>";
				} else {
					TOAST_NOTIFICATION('warning', 'Please add the vehicle type first', 'Warning !!!', '', '', '', '', '', '', '', '', '');
					return false;
				}
			}

			//ADD & EDIT MODAL
			function showVEHICLETYPEDRIVERCOSTMODAL(staff_VEHICLE_TYPE_ID) {
				var staff_ID = '<?= $staff_ID ?>';
				$('.receiving-vehicle-type-form-data').load('engine/ajax/__ajax_add_staff_vehicle_type.php?type=show_form&staff_VEHICLE_TYPE_ID=' + staff_VEHICLE_TYPE_ID + '&staff_ID=' + staff_ID + '', function() {
					const container = document.getElementById("addVEHICLEDRIVERCOSTFORM");
					const modal = new bootstrap.Modal(container);
					modal.show();
					$('#VEHICLEDRIVERCOSTFORMLabel').html('Vehicle Type - Driver Cost');
				});
			}

			//DELETE MODAL
			function showDELETEVEHICLETYPEMODAL(ID) {
				$('.receiving-confirm-delete-vehicle-type-form-data').load('engine/ajax/__ajax_add_staff_vehicle_type.php?type=delete_vehicle_type&ID=' + ID, function() {
					const container = document.getElementById("confirmDELETEVEHICLETYPEINFODATA");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}
			//CONFIRM DELETE
			function confirmVEHICLETYPEDELETE(ID) {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_add_staff_vehicle_type.php?type=confirmdelete",
					data: {
						_ID: ID
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
	elseif ($_GET['type'] == 'vehicle_info') :
		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;
			$branch_info_url = 'newstaff.php?route=edit&formtype=branch_info&id=' . $staff_ID;
			$driver_cost_url = 'newstaff.php?route=edit&formtype=driver_cost&id=' . $staff_ID;
			$vehicle_info_url = 'newstaff.php?route=edit&formtype=vehicle_info&id=' . $staff_ID;
			$permit_cost_info_url = 'newstaff.php?route=edit&formtype=permit_cost_info&id=' . $staff_ID;
			$preview_url = 'newstaff.php?route=edit&formtype=preview&id=' . $staff_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newstaff.php?route=add&formtype=basic_info&id=' . $staff_ID;
			$branch_info_url = 'newstaff.php?route=add&formtype=branch_info&id=' . $staff_ID;
			$driver_cost_url = 'newstaff.php?route=add&formtype=driver_cost&id=' . $staff_ID;
			$vehicle_info_url = 'newstaff.php?route=add&formtype=vehicle_info&id=' . $staff_ID;
			$permit_cost_info_url = 'javascript:;';
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
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle active-stepper">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Permit Cost
									</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#preview">
							<a href="<?= $preview_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Preview
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
											$select_branches = sqlQUERY_LABEL("SELECT `staff_branch_id`,  `staff_id`, `staff_branch_name` FROM `dvi_staff_branches` WHERE `deleted` = '0' and `staff_id` = '$staff_ID'") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
											while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_branches)) :
												$staff_id = $fetch_list_data['staff_id'];
												$staff_branch_id = $fetch_list_data['staff_branch_id'];
												$staff_branch_name = $fetch_list_data['staff_branch_name'];
												$firstletters = substr($staff_branch_name, 0, 1);
											?>
												<div class="col-12 col-lg-3 position-relative" onclick="choosen_vehicle_list('<?= $staff_id; ?>', '<?= $staff_branch_id; ?>', '<?= $ROUTE; ?>')">
													<span class="badge bg-label-primary position-absolute staff-vehicle-count py-0">
														<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class="me-2">
															<g>
																<g data-name="13-car">
																	<path d="M120 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24ZM408 236a52 52 0 1 0 52 52 52.059 52.059 0 0 0-52-52Zm0 76a24 24 0 1 1 24-24 24 24 0 0 1-24 24Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
																	<path d="M477.4 193.04 384 176l-79.515-65.975A44.109 44.109 0 0 0 276.526 100H159.38a43.785 43.785 0 0 0-34.359 16.514L74.232 176H40a36.04 36.04 0 0 0-36 36v44a44.049 44.049 0 0 0 44 44h9.145a64 64 0 1 1 125.71 0h162.29a64 64 0 1 1 125.71 0H472a36.04 36.04 0 0 0 36-36v-35.368a35.791 35.791 0 0 0-30.6-35.592ZM180 164a12 12 0 0 1-12 12h-52.755a6 6 0 0 1-4.563-9.9l34.916-40.9a12 12 0 0 1 9.126-4.2H168a12 12 0 0 1 12 12Zm60 56h-16a12 12 0 0 1 0-24h16a12 12 0 0 1 0 24Zm94.479-43.706-114.507-.266a12 12 0 0 1-11.972-12V133a12 12 0 0 1 12-12h57.548a12 12 0 0 1 7.433 2.58l53.228 42a6 6 0 0 1-3.73 10.714Z" fill="#7367f0" opacity="1" data-original="#000000" class=""></path>
																</g>
															</g>
														</svg>
														<?= getVECHILECOUNT($staff_id, $staff_branch_id, 'vehicle_count'); ?>
													</span>
													<a href="javascript:void(0);" class="d-flex justify-content-between vehicle-branches-card vehicle_branchid_<?= $staff_branch_id; ?> p-3">
														<div class="d-flex">

															<div class="avatar me-3">
																<span class="avatar-initial rounded bg-label-primary fs-4"><?= $firstletters; ?></span>
															</div>
															<div>
																<h5 class="mb-1 fs-5"><?= $staff_branch_name; ?></h5>
																<p class="m-0 fs-6"></p>
															</div>
														</div>
														<div class="d-flex align-items-center text-primary vehicle_list_icon" id="vehicle_list_icon_<?= $staff_branch_id; ?>">
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
											<a href="newstaff.php?route=<?= $ROUTE; ?>&formtype=permit_cost_info&id=<?= $staff_ID; ?>" class="btn btn-primary btn-next text-white"> Skip & Continue </a>
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
				var staff_branch_counter = 0;
			});

			// Choosen Vehicle List
			function choosen_vehicle_list(staff_id, branch_id, route) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_create_newvehicle_list.php?type=show_vehicle_list',
					data: {
						staff_id: staff_id,
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
	<?php

	elseif ($_GET['type'] == 'permit_cost_info') :
		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;
			$branch_info_url = 'newstaff.php?route=edit&formtype=branch_info&id=' . $staff_ID;
			$driver_cost_url = 'newstaff.php?route=edit&formtype=driver_cost&id=' . $staff_ID;
			$vehicle_info_url = 'newstaff.php?route=edit&formtype=vehicle_info&id=' . $staff_ID;
			$permit_cost_info_url = 'newstaff.php?route=edit&formtype=permit_cost_info&id=' . $staff_ID;
			$preview_url = 'newstaff.php?route=edit&formtype=preview&id=' . $staff_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update & Continue";
		else :
			$basic_info_url = 'newstaff.php?route=add&formtype=basic_info&id=' . $staff_ID;
			$branch_info_url = 'newstaff.php?route=add&formtype=branch_info&id=' . $staff_ID;
			$driver_cost_url = 'newstaff.php?route=add&formtype=driver_cost&id=' . $staff_ID;
			$vehicle_info_url = 'newstaff.php?route=add&formtype=vehicle_info&id=' . $staff_ID;
			$permit_cost_info_url = 'newstaff.php?route=add&formtype=permit_cost_info&id=' . $staff_ID;
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
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#branch_info">
							<a href="<?= $branch_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="true" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">2</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Branch</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $driver_cost_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">3</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Vehicle Type<br />(Driver Cost)</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#vehicle_info">
							<a href="<?= $vehicle_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">4</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Vehicle</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#permit_cost">
							<a href="<?= $permit_cost_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle active-stepper">5</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title">Permit Cost
									</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
						</div>
						<div class="step" data-target="#preview">
							<a href="<?= $preview_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false" <?= $disabled_navigate; ?>>
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">6</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Preview
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
													<button class="btn btn-label-primary waves-effect" onclick="add_permit_cost('<?= $staff_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost')">+ Add Permit Cost</button>
												</div>
										</div>
										<div class="card-body dataTable_select text-nowrap px-0">
											<div class="table-responsive">
												<table class="table table-flush-spacing border table-bordered" id="permit_cost_LIST">
													<thead class="table-head">
														<tr>
															<th scope="col">S.No</th><!-- 1 -->
															<th scope="col">View&Edit Permitcost</th><!-- 2 -->
															<th scope="col">Vehicle Type</th><!-- 3 -->
															<th scope="col">Source State</th><!-- 4 -->
															<!-- 5 -->
														</tr>
													</thead>
													<tbody>
														<?php
														$select_VEHICLE_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT  PC.vehicle_type_id,PC.`source_state_id` FROM  dvi_permit_state PS LEFT JOIN  dvi_permit_cost PC ON PS.permit_state_id = PC.destination_state_id  AND PC.deleted = '0'  AND PC.staff_id = '$staff_ID'  GROUP BY  PC.`source_state_id`,PC.vehicle_type_id") or die("#1-UNABLE_TO_COLLECT_VEHICLE_PERMIT_COST_LIST:" . sqlERROR_LABEL());
														$num_of_row_vehicle = sqlNUMOFROW_LABEL($select_VEHICLE_PERMITCOSTLIST_query);
														if ($num_of_row_vehicle > 0) :
															$counter = 0;
															while ($fetch_vehicle_permitcost_list_data = sqlFETCHARRAY_LABEL($select_VEHICLE_PERMITCOSTLIST_query)) :
																$group_by_vehicle_type_id = $fetch_vehicle_permitcost_list_data['vehicle_type_id'];
																$group_by_source_state_id =
																	$fetch_vehicle_permitcost_list_data['source_state_id'];
																$counter++;
																$select_PERMITCOSTLIST_query = sqlQUERY_LABEL("SELECT `staff_id`,`permit_cost_id`,`vehicle_type_id`, `source_state_id`, `destination_state_id`, `permit_cost` FROM `dvi_permit_cost` WHERE `deleted` = '0' AND `staff_id` = '$staff_ID' AND `source_state_id`='$group_by_source_state_id' AND `vehicle_type_id`='$group_by_vehicle_type_id' ORDER BY `permit_cost_id` ASC") or die("#1-UNABLE_TO_COLLECT_COURSE_LIST:" . sqlERROR_LABEL());
																$num_of_row = sqlNUMOFROW_LABEL($select_PERMITCOSTLIST_query);
																if ($num_of_row > 0) :
																	$counter_state_list = 0;
																	$currentSourceState = '';
																	while ($fetch_list_data = sqlFETCHARRAY_LABEL($select_PERMITCOSTLIST_query)) {
																		$staff_id = $fetch_list_data['staff_id'];
																		$permit_cost_id = $fetch_list_data['permit_cost_id'];
																		$vehicle_type_id = $fetch_list_data['vehicle_type_id'];
																		$source_state_id = $fetch_list_data['source_state_id'];
																		$source_state_name = getSTATE_DETAILS($fetch_list_data['source_state_id'], 'label');
																		$destination_state_name = getSTATE_DETAILS($fetch_list_data['destination_state_id'], 'label');
																		$permit_cost = $fetch_list_data['permit_cost'];

																		if ($currentSourceState != $source_state_name) {

																			if ($currentSourceState != '') {
																				echo '</div></td></tr>';
																			}
																			echo "<tr>";
																			echo "<td>{$counter}</td>";
																			echo "<td><a class='cursor-pointer' data-bs-toggle='modal' data-bs-target='#exampleModal' onclick='fetchPermitCost(\"{$staff_id}\",\"{$group_by_source_state_id}\", \"{$group_by_vehicle_type_id}\");'><img src='assets/img/svg/eye.svg' class='me-1'/></a><a class='cursor-pointer'  onclick='show_PERMIT_EDIT_MODAL(\"{$staff_id}\",\"{$group_by_source_state_id}\", \"{$group_by_vehicle_type_id}\");'><img src='assets/img/svg/edit.svg' class='me-1'/></a></td>";
																			echo "<td>{$vehicle_type_name}</td>";
																			echo "<td>{$source_state_name}</td>";

																			$currentSourceState = $source_state_name;
																		}
																	}

																	if ($currentSourceState != '') {
																		echo '</div></td>';
																	}

																	echo '</tr>';
																endif;
																$prev_vehicle_type_id = $group_by_vehicle_type_id;
															endwhile;
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
												<a href="<?= $vehicle_info_url; ?>" class="btn btn-secondary btn-prev">Back
												</a>
												<a href="newstaff.php?route=<?= $ROUTE; ?>&formtype=preview&id=<?= $staff_ID; ?>" class="btn btn-primary btn-next"> <span class="align-middle d-sm-inline-block d-none me-sm-1">Skip & Continue</span></a>
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
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-xl modal-simple modal-enable-otp modal-dialog-centered">
				<div class="modal-content p-3 p-md-5">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
		</div>


		<!-- /Default Wizard -->
		<script>
			$(document).ready(function() {

				var dataTable = $('#permit_cost_LIST').DataTable({
					dom: 'lfrtip',
					"bFilter": true,
				});

			});


			function add_permit_cost(ID, ROUTE, TYPE) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_add_staff_newform.php?type=' + TYPE + '&ROUTE=' + ROUTE + '&ID=' + ID,
					success: function(response) {
						$('#showstaffLIST').html('');
						$('#showstaffFORMSTEP1').html('');
						$('#showstaffFORMSTEP2').html('');
						$('#showstaffFORMSTEP3').html('');
						$('#showstaffFORMSTEP4').html(response);
						$('#showstaffPREVIEW').html('');
					}
				});
			}

			function fetchPermitCost(staff_id, sourceStateId, vehicleTypeId) {
				// AJAX request
				$.ajax({
					url: 'engine/ajax/__ajax_staff_permitcost.php',
					type: 'POST',
					data: {
						staff_id: staff_id,
						source_state_id: sourceStateId,
						vehicle_type_id: vehicleTypeId
					},
					success: function(response) {
						$('#exampleModal .modal-content').html(response); // Populate modal with response data
					}
				});
			}

			//EDIT PERMIT COST MODAL
			function show_PERMIT_EDIT_MODAL(staff_ID, SOURCE_STATE_ID, VEHICLE_TYPE_ID) {
				$('.receiving-permit-form-data').load('engine/ajax/__ajax_update_vehicle_permit_details.php?type=show_form&VEHICLE_TYPE_ID=' + VEHICLE_TYPE_ID + '&staff_ID=' + staff_ID + '&SOURCE_STATE_ID=' + SOURCE_STATE_ID + '', function() {
					const container = document.getElementById("editPERMITFORM");
					const modal = new bootstrap.Modal(container);
					modal.show();
					if (PERMIT_ID) {
						$('#PERMITLabel').html('Update Permit Details');
					}
				});
			}
		</script>
	<?php

	elseif ($_GET['type'] == 'preview') :
		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;
			$branch_info_url = 'newstaff.php?route=edit&formtype=branch_info&id=' . $staff_ID;
			$driver_cost_url = 'newstaff.php?route=edit&formtype=driver_cost&id=' . $staff_ID;
			$vehicle_info_url = 'newstaff.php?route=edit&formtype=vehicle_info&id=' . $staff_ID;
			$permit_cost_info_url = 'newstaff.php?route=edit&formtype=permit_cost_info&id=' . $staff_ID;
			$preview_url = 'newstaff.php?route=edit&formtype=preview&id=' . $staff_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';

			$button_label = "Update";
		else :
			$basic_info_url = 'newstaff.php?route=add&formtype=basic_info&id=' . $staff_ID;
			$branch_info_url = 'newstaff.php?route=add&formtype=branch_info&id=' . $staff_ID;
			$driver_cost_url = 'newstaff.php?route=add&formtype=driver_cost&id=' . $staff_ID;
			$vehicle_info_url = 'newstaff.php?route=add&formtype=vehicle_info&id=' . $staff_ID;
			$permit_cost_info_url = 'newstaff.php?route=add&formtype=permit_cost_info&id=' . $staff_ID;
			$preview_url = 'javascript:;';
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';

			$button_label = "Save";
		endif;

	?>
		<!-- Default Wizard -->
		<div class="row">
			<div class="col-12">
				<div id="wizard-validation" class="bs-stepper mt-2">
					<div class="bs-stepper-header border-0 justify-content-center py-2">
						<div class="step" data-target="#basic_info">
							<a href="<?= $basic_info_url; ?>" type="button" class="btn step-trigger pe-2 ps-2" aria-selected="false">
								<span class="stepper_for_staff bs-stepper-circle disble-stepper-title">1</span>
								<span class="bs-stepper-label mt-3">
									<h5 class="stepper_for_staff bs-stepper-title disble-stepper-title">Basic Info</h5>
								</span>
							</a>
						</div>
						<div class="line">
							<i class="ti ti-chevron-right"></i>
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
												$select_staff = sqlQUERY_LABEL("SELECT `staff_id`,`staff_name`, `staff_mobile`, `staff_email`, `status` FROM `dvi_staff_details` WHERE `staff_id`= '$staff_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
												while ($fetch_data = sqlFETCHARRAY_LABEL($select_staff)) :
													$staff_id = $fetch_data['staff_id'];
													$staff_name = $fetch_data['staff_name'];
													$staff_mobile = $fetch_data['staff_mobile'];
													$staff_email = $fetch_data['staff_email'];
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
														<label>staff Name</label>
														<p class="disble-stepper-title"><?= $staff_name; ?></p>
													</div>
													<div class="col-md-3">
														<label>Email ID</label>
														<p class="disble-stepper-title"><?= $staff_email; ?></p>
													</div>
													<div class="col-md-3">
														<label>Mobile Number</label>
														<p class="disble-stepper-title"><?= $staff_mobile; ?></p>
													</div>

													<div class="col-md-3">
														<label>Status</label>
														<p class="<?= $status_color; ?> fw-bold"><?= $status ?></p>
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
												$select_staff_branch = sqlQUERY_LABEL("SELECT `staff_branch_id`, `staff_branch_name` FROM `dvi_staff_branches` WHERE `staff_id`= '$staff_ID' AND `deleted` = '0'") or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
												?>
												<div class="d-flex justify-content-between align-items-center">
													<h5 class="text-primary mb-0">Branch Details</h5>

													<div class="d-flex align-items-center">
														<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
														<select class="form-select form-select-sm" name="choose_branch" id="choose_branch" onchange="change_choose_branch();" data-parsley-trigger="keyup">
															<?php

															while ($fetch_data = sqlFETCHARRAY_LABEL($select_staff_branch)) {
																$staff_branch_id = $fetch_data['staff_branch_id'];

																$staff_branch_name = $fetch_data['staff_branch_name'];
																echo "<option value='$staff_branch_id'>$staff_branch_name</option>";
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
												$select_staff_branch = sqlQUERY_LABEL(
													"SELECT `vehicle_id`, `vehicle_type_id`, `staff_branch_id` FROM `dvi_vehicle` WHERE `staff_id`= '$staff_ID' AND  `deleted` = '0'"
												) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
												?>
												<div class="d-flex justify-content-between align-items-center">
													<h5 class="text-primary my-1">Vehicle Details</h5>
													<div class="d-flex align-items-center">
														<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
														<select class="form-select form-select-sm" name="choose_vehicle" id="choose_vehicle" onchange="change_choose_vehicle()" data-parsley-trigger="keyup">
															<?php
															while ($fetch_data = sqlFETCHARRAY_LABEL($select_staff_branch)) {
																$vehicle_id = $fetch_data['vehicle_id'];
																$vehicle_type_id = $fetch_data['vehicle_type_id'];
																$staff_branch_name = getBranchLIST($fetch_data['staff_branch_id'], 'branch_label');
																$vehicle_name = getVEHICLELIST($vehicle_type_id, 'vehicle_label');
																echo "<option value='$vehicle_id'>$vehicle_name - $staff_branch_name</option>";
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
												$select_staff_branch = sqlQUERY_LABEL(
													"SELECT `vehicle_type_id` FROM `dvi_permit_cost` WHERE `staff_id`= '$staff_ID' AND  `deleted` = '0' GROUP BY `vehicle_type_id` "
												) or die("#1-UNABLE_TO_COLLECT_QUESTION_LIST:" . sqlERROR_LABEL());
												$permitcost_num = sqlNUMOFROW_LABEL($select_staff_branch);
												?>
												<div class="d-flex justify-content-between align-items-center">
													<h5 class="text-primary my-1">Permit Cost Details</h5>
													<?php if ($permitcost_num > 0) : ?>
														<div class="d-flex align-items-center">
															<p class="mb-0 me-3 text-primary"><b>Filter</b> </p>
															<select class="form-select form-select-sm" name="choose_permitcost" id="choose_permitcost" onchange="change_choose_permitcost()">
																<?php
																while ($fetch_data = sqlFETCHARRAY_LABEL($select_staff_branch)) {
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
												<a href="newstaff.php" class="btn btn-primary float-end ms-2">Submit</a>
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
				var staff_id = '<?= $staff_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_staff_permitcostpreview.php?type=staff_vehicle',
					data: {
						vehicle_type_id: choose_permitcost,
						staff_id: staff_id
					},
					success: function(response) {
						// $('#add_staff').hide();
						$('#permitcost_perview').html(response);
					}
				});
			}

			// branch script start
			function change_choose_branch() {
				var choose_branch = $('#choose_branch').val();
				var staff_id = '<?= $staff_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_staff_branchpreview.php?type=staff_vehicle',
					data: {
						branch_id: choose_branch,
						staff_id: staff_id
					},
					success: function(response) {
						// $('#add_staff').hide();
						$('#branch_perview').html(response);
					}
				});
			}
			// branch script end

			// vehicle script start
			function change_choose_vehicle() {
				var choose_vehicle = $('#choose_vehicle').val();
				var vehicle_type_id = '<?= $vehicle_type_id; ?>';
				var staff_id = '<?= $staff_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_staff_vehiclepreview.php?type=staff_vehicle',
					data: {
						vehicle_id: choose_vehicle,
						vehicle_type_id: vehicle_type_id,
						staff_id: staff_id
					},
					success: function(response) {
						// $('#add_staff').hide();
						$('#vehicle_perview').html(response);
					}
				});
			}
			// vehicle script end



			// vehicle script start
			function choose_permitcost() {
				var choose_permitcost = $('#choose_permitcost').val();
				var staff_id = '<?= $staff_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_staff_permitcostpreview.php?type=staff_vehicle',
					data: {
						vehicle_type_id: choose_permitcost,
						staff_id: staff_id
					},
					success: function(response) {
						// $('#add_staff').hide();
						$('#permitcost_perview').html(response);
					}
				});
			}
			// vehicle script end

			// branch script start
			function choose_branch() {
				var choose_branch = $('#choose_branch').val();
				var staff_id = '<?= $staff_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_staff_branchpreview.php?type=staff_vehicle',
					data: {
						branch_id: choose_branch,
						staff_id: staff_id
					},
					success: function(response) {
						// $('#add_staff').hide();
						$('#branch_perview').html(response);
					}
				});
			}
			// branch script end

			// vehicle script start
			function choose_vehicle() {
				var choose_vehicle = $('#choose_vehicle').val();
				var vehicle_type_id = '<?= $vehicle_type_id; ?>';
				var staff_id = '<?= $staff_ID; ?>';

				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_staff_vehiclepreview.php?type=staff_vehicle',
					data: {
						vehicle_id: choose_vehicle,
						vehicle_type_id: vehicle_type_id,
						staff_id: staff_id
					},
					success: function(response) {
						// $('#add_staff').hide();
						$('#vehicle_perview').html(response);
					}
				});
			}
			// vehicle script end
		</script>
	<?php

	elseif ($_GET['type'] == 'permit_cost') :

		$staff_ID = $_GET['ID'];
		$ROUTE = $_GET['ROUTE'];

		if ($staff_ID != '' && $staff_ID != 0 && $ROUTE == 'edit') :
			$basic_info_url = 'newstaff.php?route=edit&formtype=basic_info&id=' . $staff_ID;
			$preview_url = 'newstaff.php?route=edit&formtype=preview&id=' . $staff_ID;
			$disabled_navigate = '';
			$button_text_disabled = '';
		else :
			$basic_info_url = 'newstaff.php?route=add&formtype=basic_info&id=' . $staff_ID;
			$disabled_navigate = 'disabled';
			$button_text_disabled = ' text-light';
		endif;
	?>


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
						url: 'engine/ajax/__ajax_manage_staff.php?type=permit_cost',
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
							} else if (response.errros.hidden_permit_staff_ID_required) {
								TOAST_NOTIFICATION('warning', 'staff ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
							}
						} else {
							//SUCCESS RESPOSNE
							if (response.i_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Permit Cost Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								showstaffFORMSTEP4('<?= $staff_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost_info');
							} else if (response.i_result == false) {
								TOAST_NOTIFICATION('success', 'Unable to Add Permit Cost Details', 'Success !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.u_result == true) {
								//RESULT SUCCESS
								TOAST_NOTIFICATION('success', 'Permit Cost Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
								showstaffFORMSTEP4('<?= $staff_ID; ?>', '<?= $ROUTE; ?>', 'permit_cost_info');
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
						url: 'engine/ajax/__ajax_manage_staff.php?type=update_permit_cost',
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
							if (response.result_success) {
								TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
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
				var staff_ID = '<?= $staff_ID ?>';

				if (SOURCE_STATE_ID != "" && VEHICLE_TYPE_ID != "" && staff_ID != "") {
					$.ajax({
						type: 'post',
						url: 'engine/ajax/__ajax_update_vehicle_permit_details.php?type=update_permit_cost&VEHICLE_TYPE_ID=' + VEHICLE_TYPE_ID + '&staff_ID=' + staff_ID + '&SOURCE_STATE_ID=' + SOURCE_STATE_ID,
						success: function(response) {
							$('#stateInputContainer').html(response);
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
					url: 'engine/ajax/__ajax_add_staff_newform.php?type=' + TYPE + '&ROUTE=' + ROUTE + '&ID=' + ID,
					success: function(response) {
						$('#showstaffLIST').html('');
						$('#showstaffFORMSTEP1').html('');
						$('#showstaffFORMSTEP2').html('');
						$('#showstaffFORMSTEP3').html('');
						$('#showstaffFORMSTEP4').html(response);
						$('#showstaffPREVIEW').html('');

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

		$staff_branch_ID = $_GET['staff_branch_ID'];
		$staff_ID = $_GET['staff_ID'];
		$ROUTE = $_GET['ROUTE'];

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
					<p class="mb-0 mt-2">Are you sure? Want to delete this Branch <b></b><br /> This action cannot be undone.</p>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-center">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" onclick="confirmBRANCHDELETE('<?= $staff_branch_ID; ?>','<?= $staff_ID; ?>', '<?= $ROUTE; ?>')" class="btn btn-danger waves-effect waves-light">Delete</button>
			</div>
		</div>
		<script>
			function confirmBRANCHDELETE(staff_branch_ID, staff_ID, ROUTE) {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_manage_staff.php?type=confirm_branch_delete",
					data: {
						staff_branch_ID: staff_branch_ID,
						staff_ID: staff_ID,
						ROUTE: ROUTE
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
	endif;
else :
	echo "Request Ignored";
endif;
