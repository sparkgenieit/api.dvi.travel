<?php
/*
* JACKUS - An In-house Framework for TDS Apps
*
* Author: Touchmark Descience Private Limited. 
* https://touchmarkdes.com
* Version 5.0.1
* Copyright (c) 2010-2022 Touchmark De`Science
*
*/
include_once('jackus.php');
admin_reguser_protect();

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
	<title><?php include_once(adminpublicpath('__pagetitle.php')); ?> | <?= $_SITETITLE; ?></title>
	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="assets/img/favicon/site.webmanifest">

	<!-- Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com/">
	<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

	<!-- Icons -->
	<link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
	<link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
	<link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

	<!-- Core CSS -->
	<link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
	<link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
	<link rel="stylesheet" href="assets/css/demo.css" />
	<link rel="stylesheet" href="assets/vendor/js/bootstrap.min.js" />

	<!-- Vendors CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/node-waves/node-waves.css" />
	<link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
	<link rel="stylesheet" href="assets/vendor/libs/typeahead-js/typeahead.css" />
	<link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />
	<link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
	<link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
	<!-- Row Group CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
	<!-- Form Validation -->
	<link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
	<link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
	<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />

	<!-- Bootstrap-timepicker CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.css">
	<link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
	<link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />
	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>
	<script src="assets/js/config.js"></script>
	<style>
		.ck.ck-editor__main {
			max-height: 300px;
			overflow: auto;
		}
	</style>
</head>

<body>
	<div class="layout-wrapper layout-content-navbar ">
		<div class="layout-container">

			<!-- Layout container -->
			<div class="layout-page">
				<!-- Menu -->
				<?php include_once('public/__sidebar.php'); ?>
				<!-- / Menu -->

				<!-- Content wrapper -->
				<div class="content-wrapper">

					<!-- Content -->
					<div class="container-xxl flex-grow-1 container-p-y">
						<div class=" d-flex justify-content-between align-items-center">
							<div>
								<h4>Global Settings</h4>
							</div>
						</div>
						<?php

						$select_globalsetting_query = sqlQUERY_LABEL("SELECT `extrabed_rate_percentage`, `childwithbed_rate_percentage`, `childnobed_rate_percentage`, `hotel_margin`, `hotel_margin_gst_type`, `hotel_margin_gst_percentage`, `eligibile_country_code`, `itinerary_distance_limit`, `itinerary_common_buffer_time`, `allowed_km_limit_per_day`, `site_seeing_restriction_km_limit`, `itinerary_travel_by_flight_buffer_time`, `itinerary_travel_by_train_buffer_time`, `itinerary_travel_by_road_buffer_time`, `itinerary_break_time`, `itinerary_hotel_return`, `itinerary_hotel_start`, `custom_hotspot_or_activity`, `accommodation_return`, `hotel_terms_condition`, `hotel_voucher_terms_condition`, `vehicle_terms_condition`, `itinerary_local_speed_limit`,`agent_referral_bonus_credit`, `itinerary_outstation_speed_limit`, `site_title`, `company_name`, `company_address`, `company_pincode`, `company_gstin_no`, `company_contact_no`, `company_email_id`,`cc_email_id`,`default_hotel_voucher_email_id`,`default_vehicle_voucher_email_id`, `company_logo`, `company_cin`, `bank_acc_holder_name`, `bank_acc_no`, `bank_ifsc_code`, `bank_name`, `branch_name`,`company_pan_no`, `hotel_hsn`, `vehicle_hsn`, `service_component_hsn`, `youtube_link`, `facebook_link`, `instagram_link`, `linkedin_link`,`vehicle_voucher_terms_condition`,`default_accounts_email_id`,`itinerary_additional_margin_percentage`,`itinerary_additional_margin_day_limit` FROM `dvi_global_settings` WHERE `deleted` = '0' and `global_settings_ID`= '1' ") or die("#1-UNABLE_TO_COLLECT_HOTEL_LIST:" . sqlERROR_LABEL());
						while ($fetch_globalsetting_data = sqlFETCHARRAY_LABEL($select_globalsetting_query)) :
							$itinerary_additional_margin_percentage = $fetch_globalsetting_data['itinerary_additional_margin_percentage'];
							$itinerary_additional_margin_day_limit = $fetch_globalsetting_data['itinerary_additional_margin_day_limit'];
							$extrabed_rate_percentage = $fetch_globalsetting_data['extrabed_rate_percentage'];
							$childwithbed_rate_percentage = $fetch_globalsetting_data['childwithbed_rate_percentage'];
							$childnobed_rate_percentage = $fetch_globalsetting_data['childnobed_rate_percentage'];
							$hotel_margin = $fetch_globalsetting_data['hotel_margin'];
							$hotel_margin_gst_type = $fetch_globalsetting_data['hotel_margin_gst_type'];
							$hotel_margin_gst_percentage = $fetch_globalsetting_data['hotel_margin_gst_percentage'];
							$eligibile_country_code = $fetch_globalsetting_data['eligibile_country_code'];
							$itinerary_common_buffer_time = $fetch_globalsetting_data['itinerary_common_buffer_time'];
							$itinerary_distance_limit = $fetch_globalsetting_data['itinerary_distance_limit'];
							$allowed_km_limit_per_day = $fetch_globalsetting_data['allowed_km_limit_per_day'];
							$site_seeing_restriction_km_limit = $fetch_globalsetting_data['site_seeing_restriction_km_limit'];
							$hotel_terms_condition = $fetch_globalsetting_data['hotel_terms_condition'];
							$hotel_voucher_terms_condition = $fetch_globalsetting_data['hotel_voucher_terms_condition'];
							$vehicle_terms_condition = $fetch_globalsetting_data['vehicle_terms_condition'];
							$vehicle_voucher_terms_condition = $fetch_globalsetting_data['vehicle_voucher_terms_condition'];
							$itinerary_travel_by_flight_buffer_time = $fetch_globalsetting_data['itinerary_travel_by_flight_buffer_time'];
							$itinerary_travel_by_train_buffer_time = $fetch_globalsetting_data['itinerary_travel_by_train_buffer_time'];
							$itinerary_travel_by_road_buffer_time = $fetch_globalsetting_data['itinerary_travel_by_road_buffer_time'];

							$site_title = $fetch_globalsetting_data['site_title'];
							$company_name = $fetch_globalsetting_data['company_name'];
							$company_address = $fetch_globalsetting_data['company_address'];
							$company_pincode = $fetch_globalsetting_data['company_pincode'];
							$company_gstin_no = $fetch_globalsetting_data['company_gstin_no'];
							$company_contact_no = $fetch_globalsetting_data['company_contact_no'];
							$company_email_id = $fetch_globalsetting_data['company_email_id'];
							$cc_email_id = $fetch_globalsetting_data['cc_email_id'];
							$default_hotel_voucher_email_id = $fetch_globalsetting_data['default_hotel_voucher_email_id'];
							$default_vehicle_voucher_email_id = $fetch_globalsetting_data['default_vehicle_voucher_email_id'];
							$company_logo = $fetch_globalsetting_data['company_logo'];
							$company_cin = $fetch_globalsetting_data['company_cin'];
							$bank_acc_holder_name = $fetch_globalsetting_data['bank_acc_holder_name'];
							$bank_acc_no = $fetch_globalsetting_data['bank_acc_no'];
							$bank_ifsc_code = $fetch_globalsetting_data['bank_ifsc_code'];
							$bank_name = $fetch_globalsetting_data['bank_name'];
							$branch_name = $fetch_globalsetting_data['branch_name'];
							$company_pan_no = $fetch_globalsetting_data['company_pan_no'];
							$hotel_hsn = $fetch_globalsetting_data['hotel_hsn'];
							$vehicle_hsn = $fetch_globalsetting_data['vehicle_hsn'];
							$service_component_hsn = $fetch_globalsetting_data['service_component_hsn'];
							$youtube_link = $fetch_globalsetting_data['youtube_link'];
							$facebook_link = $fetch_globalsetting_data['facebook_link'];
							$instagram_link = $fetch_globalsetting_data['instagram_link'];
							$linkedin_link = $fetch_globalsetting_data['linkedin_link'];

							if ($itinerary_common_buffer_time != '') :
								$itinerary_common_buffer_time = date("H:i", strtotime($itinerary_common_buffer_time));
							endif;
							if ($itinerary_travel_by_flight_buffer_time != '') :
								$itinerary_travel_by_flight_buffer_time = date("H:i", strtotime($itinerary_travel_by_flight_buffer_time));
							endif;
							if ($itinerary_travel_by_train_buffer_time != '') :
								$itinerary_travel_by_train_buffer_time = date("H:i", strtotime($itinerary_travel_by_train_buffer_time));
							endif;
							if ($itinerary_travel_by_road_buffer_time != '') :
								$itinerary_travel_by_road_buffer_time = date("H:i", strtotime($itinerary_travel_by_road_buffer_time));
							endif;

							$itinerary_break_time = $fetch_globalsetting_data['itinerary_break_time'];
							$itinerary_hotel_return = $fetch_globalsetting_data['itinerary_hotel_return'];
							$itinerary_hotel_start = $fetch_globalsetting_data['itinerary_hotel_start'];
							$custom_hotspot_or_activity = $fetch_globalsetting_data['custom_hotspot_or_activity'];
							$itinerary_local_speed_limit = $fetch_globalsetting_data['itinerary_local_speed_limit'];
							$itinerary_outstation_speed_limit = $fetch_globalsetting_data['itinerary_outstation_speed_limit'];
							$accommodation_return = $fetch_globalsetting_data['accommodation_return'];
							$agent_referral_bonus_credit = $fetch_globalsetting_data['agent_referral_bonus_credit'];
							$default_accounts_email_id = $fetch_globalsetting_data['default_accounts_email_id'];
						endwhile;
						?>
						<div class="row mt-3">
							<div class="col-md-12">
								<div class="card p-4">
									<form id="form_state_config" action="" method="POST" data-parsley-validate>
										<div class="row g-3 mt-2 mb-2">

											<h5 class="text-primary m-0">State Configuration</h5>

											<div class="col-md-4">
												<label class="form-label w-100" for="state_name">State Name<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<select id="state_name" name="state_name" class="form-select" required>
														<?= getSTATELIST(101, 35, 'select_state'); ?>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vehicle_onground_support_number">On Ground Support Number<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vehicle_onground_support_number" id="vehicle_onground_support_number" class="form-control" placeholder="On Ground Support Number" value="<?= $vehicle_onground_support_number; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vehicle_escalation_call_number">Escalation Call Number
													<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vehicle_escalation_call_number" id="vehicle_escalation_call_number" class="form-control" placeholder="Escalation Call Number" value="<?= $vehicle_escalation_call_number; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="d-flex justify-content-center mt-4">
												<button type="submit" class="btn btn-primary waves-effect waves-light pe-3">
													<span class="ti-xs ti ti-device-floppy me-1"></span>Update
												</button>
											</div>
										</div>
									</form>
									<div class="divider">
										<div class="divider-text text-primary">
											<h5 class="text-primary m-0">Hotel API Configurations</h5>
										</div>
									</div>
									<form id="form_global_setting" action="" method="POST" data-parsley-validate>
										<div class="row g-3 mt-2 mb-2">

											<h5 class="text-primary m-0">TBO Hotel Eligible Countries</h5>
											<div class="col-md-12 mb-4">
												<label class="form-label w-100" for="state_name">Choosen Country<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<select id="country_id" multiple name="country_id[]" class="form-select" required>
														<?= getCOUNTRYLIST($eligibile_country_code, 'select_country_code'); ?>
													</select>
												</div>
											</div>

											<h5 class="text-primary m-0">Extra Occupancy <small>(rate calculated as a percentage of the room tariff – applicable for Extra Bed, Child with Bed, or Child without Bed).</small></h5>
											<div class="col-md-4">
												<label class="form-label" for="extrabed_rate_percentage">Extrabed Rate Percentage<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="extrabed_rate_percentage" id="extrabed_rate_percentage" min="0" max="100" class="form-control" placeholder="Extra Bed Rate Percentage" value="<?= $extrabed_rate_percentage; ?>" required />
												</div>
											</div>

											<div class="col-md-4">
												<label class="form-label" for="childwithbed_rate_percentage">Child With Bed Rate Percentage<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="childwithbed_rate_percentage" id="childwithbed_rate_percentage" min="0" max="100" class="form-control" placeholder="Extra Bed Rate Percentage" value="<?= $childwithbed_rate_percentage; ?>" required />
												</div>
											</div>

											<div class="col-md-4 mb-4">
												<label class="form-label" for="childnobed_rate_percentage">Child No Bed Rate Percentage<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="childnobed_rate_percentage" id="childnobed_rate_percentage" min="0" max="100" class="form-control" placeholder="Extra Bed Rate Percentage" value="<?= $childnobed_rate_percentage; ?>" required />
												</div>
											</div>

											<h5 class="text-primary m-0">Hotel Default Margin <small>(If no pricebook data is available for the selected date (within 365 days), this default configuration will be applied).</small></h5>
											<div class="col-md-4">
												<label class="form-label" for="hotel_margin">Hotel Margin (In Percentage) <span class="text-danger">*</span> </label>
												<input type="text" id="hotel_margin" value="<?= $hotel_margin; ?>" min="0" max="100" name="hotel_margin" class="form-control" placeholder="Enter the Margin" required>
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

											<div class="divider">
												<div class="divider-text">
													<i class="ti ti-settings-filled ti-sm text-primary"></i>
												</div>
											</div>

											<h5 class="text-primary m-0">Itinerary Distance</h5>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_distance_limit">Distance Limit (Between Locations)<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_distance_limit" id="itinerary_distance_limit" class="form-control" placeholder="Distance Limit" value="<?= $itinerary_distance_limit; ?>" required />
												</div>
											</div>

											<div class="col-md-4">
												<label class="form-label" for="allowed_km_limit_per_day">Allowed KM (Per Day)<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="allowed_km_limit_per_day" id="allowed_km_limit_per_day" class="form-control" placeholder="Allowed KM" value="<?= $allowed_km_limit_per_day; ?>" required />
												</div>
											</div>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_common_buffer_time">Common Buffer Time<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_common_buffer_time" id="itinerary_common_buffer_time" class="form-control" placeholder="Common Buffer Time" value="<?= $itinerary_common_buffer_time; ?>" required />
												</div>
											</div>

											<div class="col-md-4">
												<label class="form-label" for="site_seeing_restriction_km_limit">Site Seeing KM Limit Restriction<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="site_seeing_restriction_km_limit" id="site_seeing_restriction_km_limit" class="form-control" placeholder="Site Seeing KM Restriction" value="<?= $site_seeing_restriction_km_limit; ?>" required />
												</div>
											</div>
										</div>

										<div class="row g-3 mt-2">
											<h5 class="text-primary m-0 mt-4">Itinerary Travel Buffer Time</h5>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_travel_by_flight_buffer_time">Flight Buffer Time<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_travel_by_flight_buffer_time" id="itinerary_travel_by_flight_buffer_time" class="form-control" placeholder="Flight Buffer Time" value="<?= $itinerary_travel_by_flight_buffer_time; ?>" required />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="itinerary_travel_by_train_buffer_time">Train Buffer Time<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_travel_by_train_buffer_time" id="itinerary_travel_by_train_buffer_time" class="form-control" placeholder="Train Buffer Time" value="<?= $itinerary_travel_by_train_buffer_time; ?>" required />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="itinerary_travel_by_road_buffer_time">Road Buffer Time<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_travel_by_road_buffer_time" id="itinerary_travel_by_road_buffer_time" class="form-control" placeholder="Road Buffer Time" value="<?= $itinerary_travel_by_road_buffer_time; ?>" required />
												</div>
											</div>

										</div>

										<div class="row g-3 mt-2 mb-2">
											<h5 class="text-primary m-0 mt-4">Itinerary Customize Text</h5>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_break_time">Journey Start<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_break_time" id="itinerary_break_time" class="form-control" placeholder="Day First Start" value="<?= $itinerary_break_time; ?>" required />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="itinerary_hotel_start">In-Between Day Start (Including Last Day)<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_hotel_start" id="itinerary_hotel_start" class="form-control" placeholder="In-Between Day Start" value="<?= $itinerary_hotel_start; ?>" required />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="itinerary_hotel_return">In-Between Day End (Including Last Day)<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_hotel_return" id="itinerary_hotel_return" class="form-control" placeholder="In-Between Day End" value="<?= $itinerary_hotel_return; ?>" required />
												</div>
											</div>
											<!-- <div class="col-md-4">
												<label class="form-label" for="custom_hotspot_or_activity">Custom Hotspot/Activity<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="custom_hotspot_or_activity" id="custom_hotspot_or_activity" class="form-control" placeholder="Custom Hotspot/Activity" value="<?= $custom_hotspot_or_activity; ?>" required />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="accommodation_return">Accommodation Return<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="accommodation_return" id="accommodation_return" class="form-control" placeholder="Accommodation Return" value="<?= $accommodation_return; ?>" required />
												</div>
											</div> -->
											<input type="hidden" id="hidden_global_settings_ID" name="hidden_global_settings_ID" value="1" />
										</div>

										<div class="row">
											<div class="col-md-6 mt-3">
												<label class="form-label" for="hotel_terms_condition">Hotel Terms and Condition<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<textarea rows="10" id="hotel_terms_condition" name="hotel_terms_condition" class="form-control" required><?= $hotel_terms_condition; ?> </textarea>
												</div>
											</div>

											<div class="col-md-6 mt-3">
												<label class="form-label" for="vehicle_terms_condition">Vehicle Terms and Condition<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<textarea rows="10" id="vehicle_terms_condition" name="vehicle_terms_condition" class="form-control" required><?= $vehicle_terms_condition; ?> </textarea>
												</div>
											</div>
											<div class="col-md-6 mt-3">
												<label class="form-label" for="hotel_voucher_terms_condition">Hotel Voucher Terms and Condition<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<textarea rows="10" id="hotel_voucher_terms_condition" name="hotel_voucher_terms_condition" class="form-control" required><?= $hotel_voucher_terms_condition; ?> </textarea>
												</div>
											</div>
											<div class="col-md-6 mt-3">
												<label class="form-label" for="vehicle_voucher_terms_condition">Vehicle Voucher Terms and Condition<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<textarea rows="10" id="vehicle_voucher_terms_condition" name="vehicle_voucher_terms_condition" class="form-control" required><?= $vehicle_voucher_terms_condition; ?> </textarea>
												</div>
											</div>
										</div>

										<div class="row g-3 mt-2 mb-2">
											<h5 class="text-primary m-0 mt-4">Itinerary Travel Speed</h5>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_local_speed_limit">Local travel speed limit (KM/Hr)<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_local_speed_limit" id="itinerary_local_speed_limit" class="form-control" placeholder="Speed Limit" value="<?= $itinerary_local_speed_limit; ?>" required />
												</div>
											</div>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_outstation_speed_limit">Outstation travel speed limit (KM/Hr)<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_outstation_speed_limit" id="itinerary_outstation_speed_limit" class="form-control" placeholder="Speed Limit" value="<?= $itinerary_outstation_speed_limit; ?>" required />
												</div>
											</div>
										</div>

										<div class="row g-3 mt-2 mb-2">
											<h5 class="text-primary m-0 mt-4">
												Itinerary Additional Margin Settings
												<small>
													(If the itinerary is <?= $itinerary_additional_margin_day_limit; ?> days or fewer,
													a margin of <?= $itinerary_additional_margin_percentage; ?> percentage will be applied to the overall itinerary cost).
												</small>
											</h5>
											<div class="col-md-4">
												<label class="form-label" for="itinerary_local_margin">Additional margin Percentage <span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_additional_margin_percentage" id="itinerary_additional_margin_percentage" class="form-control" placeholder="Margin Percentage" value="<?= $itinerary_additional_margin_percentage; ?>" required />
												</div>
											</div>

											<div class="col-md-4">
												<label class="form-label" for="itinerary_additional_margin_day_limit">Additional Margin Applicable day Limit (Days) <span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="itinerary_additional_margin_day_limit" id="itinerary_additional_margin_day_limit" class="form-control" placeholder="Enter No of Days" value="<?= $itinerary_additional_margin_day_limit; ?>" required />
												</div>
											</div>
										</div>

										<div class="row g-3 mt-2 mb-2">
											<h5 class="text-primary m-0 mt-4">Agent Settings</h5>
											<div class="col-md-4">
												<label class="form-label" for="agent_referral_bonus_credit">Referral Bonus Credit<span class=" text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="agent_referral_bonus_credit" id="agent_referral_bonus_credit" class="form-control" placeholder="Referral Bonus Credit" value="<?= $agent_referral_bonus_credit; ?>" required autocomplete="off" />
												</div>
											</div>
										</div>
										<div class="row g-3 mt-2 mb-2">
											<h5 class="text-primary m-0 mt-4">Site Settings</h5>
											<div class="col-md-4">
												<label class="form-label" for="site_title">Site Title<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="site_title" id="site_title" class="form-control" placeholder="Site Title" value="<?= $site_title; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_name">Company Name<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_name" id="company_name" class="form-control" placeholder="Company Name" value="<?= $company_name; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_address">Address <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_address" id="company_address" class="form-control" placeholder="Address" value="<?= $company_address; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_pincode">Pincode <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_pincode" id="company_pincode" class="form-control" placeholder="Pincode" value="<?= $company_pincode; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_gstin_no">GSTIN No. <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_gstin_no" id="company_gstin_no" class="form-control" placeholder="GSTIN No." data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-pattern="\d{2}[A-Za-z]{5}\d{4}[A-Za-z]{1}\d{1}[A-Za-z]{1}[A-Za-z0-9]{1}" autocomplete="off" value="<?= $company_gstin_no; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_pan_no">PAN No. <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_pan_no" id="company_pan_no" class="form-control" placeholder="PAN No." data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-pattern="[A-Za-z]{5}\d{4}[A-Za-z]{1}" autocomplete="off" value="<?= $company_pan_no; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_contact_no">Contact No. <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_contact_no" id="company_contact_no" class="form-control" placeholder="Contact No." value="<?= $company_contact_no; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_email_id">Email ID <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_email_id" id="company_email_id" class="form-control" placeholder="Company Email ID" value="<?= $company_email_id; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="email" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="cc_email_id">CC Email ID <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="cc_email_id" id="cc_email_id" class="form-control" placeholder="CC Email ID" value="<?= $cc_email_id; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="email" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="default_hotel_voucher_email_id">Hotel Voucher Default Email ID <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="default_hotel_voucher_email_id" id="default_hotel_voucher_email_id" class="form-control" placeholder="Hotel Voucher Default Email ID" value="<?= $default_hotel_voucher_email_id; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="default_vehicle_voucher_email_id">Vehicle Voucher Default Email ID <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="default_vehicle_voucher_email_id" id="default_vehicle_voucher_email_id" class="form-control" placeholder="Vehicle Voucher Default Email ID" value="<?= $default_vehicle_voucher_email_id; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="default_accounts_email_id">Accounts Default Email ID <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="default_accounts_email_id" id="default_accounts_email_id" class="form-control" placeholder="Hotel Voucher Default Email ID" value="<?= $default_accounts_email_id; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="hotel_hsn">Hotel HSN <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="hotel_hsn" id="hotel_hsn" class="form-control" placeholder="Hotel HSN" value="<?= $hotel_hsn; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="vehicle_hsn">Vehicle HSN <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="vehicle_hsn" id="vehicle_hsn" class="form-control" placeholder="Vehicle HSN" value="<?= $vehicle_hsn; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="service_component_hsn">Guide + Hotspot + Activity HSN <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="service_component_hsn" id="service_component_hsn" class="form-control" placeholder="Guide + Hotspot + Activity HSN " value="<?= $service_component_hsn; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" data-parsley-type="number" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_logo">Logo</label>
												<?php if ($company_logo) : ?>
													<a href="#" class="fw-bold float-end" style="font-size: 12px;" data-bs-toggle="modal" data-bs-target="#company_logo_modal">View</a>
												<?php endif; ?>
												<div class="form-group">
													<input type="file" name="company_logo" id="company_logo" class="form-control" placeholder="Company Logo" autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="company_cin">CIN Number<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="company_cin" id="company_cin" class="form-control" placeholder="CIN Number" value="<?= $company_cin; ?>" data-parsley-trigger="keyup" data-parsley-whitespace="trim" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="youtube_link">YouTube Link<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="youtube_link" id="youtube_link" class="form-control" placeholder="YouTube Link" value="<?= $youtube_link; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="facebook_link">Facebook Link<span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="facebook_link" id="facebook_link" class="form-control" placeholder="Facebook Link" value="<?= $facebook_link; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="instagram_link">Instagram Link <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="instagram_link" id="instagram_link" class="form-control" placeholder="Instagram Link" value="<?= $instagram_link; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="linkedin_link">LinkedIn Link <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="linkedin_link" id="linkedin_link" class="form-control" placeholder="LinkedIn Link" value="<?= $linkedin_link; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="account_holder_name">Account Holder Name <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="account_holder_name" id="account_holder_name" class="form-control" placeholder="Account Holder Name" value="<?= $bank_acc_holder_name; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="account_number">Account Number <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="account_number" id="account_number" class="form-control" placeholder="Account Number" value="<?= $bank_acc_no; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="bank_ifsc_no">IFSC Code <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="bank_ifsc_no" id="bank_ifsc_no" class="form-control" placeholder="Account Number" value="<?= $bank_ifsc_code; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="bank_name">Bank Name <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="Bank Name" value="<?= $bank_name; ?>" required autocomplete="off" />
												</div>
											</div>
											<div class="col-md-4">
												<label class="form-label" for="branch_name">Branch Name <span class="text-danger"> *</span></label>
												<div class="form-group">
													<input type="text" name="branch_name" id="branch_name" class="form-control" placeholder="Branch Name" value="<?= $branch_name; ?>" required autocomplete="off" />
												</div>
											</div>
										</div>
										<div class="d-flex justify-content-center mt-4">
											<button type="submit" class="btn btn-primary waves-effect waves-light pe-3">
												<span class="ti-xs ti ti-device-floppy me-1"></span>Update
											</button>
										</div>
									</form>
								</div>
							</div>
						</div>

					</div>
					<!-- Footer -->
					<?php include_once('public/__footer.php'); ?>
					<!-- / Footer -->
				</div>
			</div>

		</div>
		<!-- / Layout page -->
	</div>
	<!-- / Layout wrapper -->

	<!-- Company Logo Modal -->
	<div class="modal fade" id="company_logo_modal" tabindex="-1" aria-labelledby="company_logoLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="company_logoLabel">Company Logo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-center">
					<img src="uploads/logo/<?= $company_logo; ?>" alt="No-Image-Found" class="rounded-3" width="150" height="150" />
				</div>
			</div>
		</div>
	</div>

	<!-- <div class="modal fade" id="showDELETEMODAL" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content receiving-delete-form-data">
				<div class="modal-body">
					<div class="row">
						<div class="text-center">
							<svg class="icon-44" width="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">
								<path d="M19.3248 9.46826C19.3248 9.46826 18.7818 16.2033 18.4668 19.0403C18.3168 20.3953 17.4798 21.1893 16.1088 21.2143C13.4998 21.2613 10.8878 21.2643 8.27979 21.2093C6.96079 21.1823 6.13779 20.3783 5.99079 19.0473C5.67379 16.1853 5.13379 9.46826 5.13379 9.46826" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M20.708 6.23975H3.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M17.4406 6.23973C16.6556 6.23973 15.9796 5.68473 15.8256 4.91573L15.5826 3.69973C15.4326 3.13873 14.9246 2.75073 14.3456 2.75073H10.1126C9.53358 2.75073 9.02558 3.13873 8.87558 3.69973L8.63258 4.91573C8.47858 5.68473 7.80258 6.23973 7.01758 6.23973" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
							</svg>
						</div>
						<h6 class="mt-4 mb-2 text-center">Are you sure?</h6>
						<p class="text-center">Do you really want to delete these record? <br />This process cannot be undone.<br>This
							process includes deletion of rooms,amenities and price against the hotel.</p>
						<div class="text-center pb-0">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button type="submit" onclick="confirmHOTELDELETE('<?= $ID; ?>');" class="btn btn-danger">Delete</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->

	<!-- Core JS -->
	<!-- build:js assets/vendor/js/core.js -->

	<script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
	<script src="assets/vendor/libs/popper/popper.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
	<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
	<script src="assets/vendor/libs/i18n/i18n.js"></script>
	<script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
	<script src="assets/vendor/js/menu.js"></script>
	<script src="assets/vendor/libs/tagify/tagify.js"></script>
	<script src="assets/js/forms-tagify.js"></script>
	<script src="assets/vendor/libs/toastr/toastr.js"></script>
	<script src="assets/js/footerscript.js"></script>

	<!-- endbuild -->
	<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
	<!-- Form Validation -->
	<script src="assets/js/parsley.min.js"></script>
	<script src="assets/js/easy-autocomplete.min.js"></script>
	<script src="assets/js/selectize/selectize.min.js"></script>
	<!-- Vendors JS -->
	<script src="assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>

	<script src="assets/js/jquery.easy-autocomplete.min.js"></script>
	<script src="assets/vendor/libs/bootstarp-timepicker/bootstrap-timepicker.min.js"></script>
	<script src="assets/js/_jquery.dataTables.min.js"></script>
	<script src="assets/js/_dataTables.buttons.min.js"></script>
	<script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
	<script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
	<script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
	<script src="assets/js/_js_buttons.html5.min.js"></script>
	<script src="assets/js/ckeditor5.js"></script>

	<script src=" assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
	<!-- Main JS -->
	<script src="assets/js/main.js"></script>


	<script>
		$(document).ready(function() {
			$('#state_name').selectize();
			$('#country_id').selectize();

			function fetchStateConfig() {
				var stateId = $('#state_name').val();
				var countryId = 101;

				$.ajax({
					url: 'engine/ajax/__ajax_fetch_state_config.php?type=fetch_numbers',
					type: 'POST',
					data: {
						state_id: stateId,
						country_id: countryId
					},
					success: function(response) {
						$('#vehicle_onground_support_number').val(response.vehicle_onground_support_number);
						$('#vehicle_escalation_call_number').val(response.vehicle_escalation_call_number);
					},
					error: function(xhr, status, error) {
						console.error(error);
					}
				});
			}

			// Trigger the fetchStateConfig function on page load
			fetchStateConfig();

			// Also trigger the fetchStateConfig function when the state selection changes
			$('#state_name').change(function() {
				fetchStateConfig();
			});

			//AJAX FORM SUBMIT
			$("#form_state_config").submit(function(event) {
				var form = $('#form_state_config')[0];
				var data = new FormData(form);
				// $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
				$.ajax({
					type: "post",
					url: 'engine/ajax/__ajax_manage_global_setting.php?type=state_config_update',
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
						if (response.errros.state_required) {
							TOAST_NOTIFICATION('warning', 'State Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						//SUCCESS RESPOSNE
						if (response.u_result == true) {
							//RESULT SUCCESS
							TOAST_NOTIFICATION('success', 'State Config Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							/* location.assign(response.redirect_URL); */
						} else if (response.u_result == false) {
							//RESULT FAILED
							TOAST_NOTIFICATION('success', 'Unable to Update State Config', 'Success !!!', '', '', '', '', '', '', '', '', '');
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
		if (typeof CKEDITOR === 'undefined') {
			console.error('CKEditor not found. Please check the script source.');
		} else {
			CKEDITOR.ClassicEditor.create(document.getElementById("vehicle_terms_condition"), {
				updateSourceElementOnDestroy: true,
				toolbar: {
					items: [
						'exportPDF', 'exportWord', '|',
						'findAndReplace', 'selectAll', '|',
						'heading', '|',
						'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
						'bulletedList', 'numberedList', 'todoList', '|',
						'outdent', 'indent', '|',
						'undo', 'redo',
						'-',
						'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
						'alignment', '|',
						'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
						'specialCharacters', 'horizontalLine', 'pageBreak', '|',
						'textPartLanguage', '|',
						'sourceEditing', 'lineHeight'
					],
					shouldNotGroupWhenFull: true
				},
				list: {
					properties: {
						styles: true,
						startIndex: true,
						reversed: true
					}
				},
				heading: {
					options: [{
							model: 'paragraph',
							title: 'Paragraph',
							class: 'ck-heading_paragraph'
						},
						{
							model: 'heading1',
							view: 'h1',
							title: 'Heading 1',
							class: 'ck-heading_heading1'
						},
						{
							model: 'heading2',
							view: 'h2',
							title: 'Heading 2',
							class: 'ck-heading_heading2'
						},
						{
							model: 'heading3',
							view: 'h3',
							title: 'Heading 3',
							class: 'ck-heading_heading3'
						},
						{
							model: 'heading4',
							view: 'h4',
							title: 'Heading 4',
							class: 'ck-heading_heading4'
						},
						{
							model: 'heading5',
							view: 'h5',
							title: 'Heading 5',
							class: 'ck-heading_heading5'
						},
						{
							model: 'heading6',
							view: 'h6',
							title: 'Heading 6',
							class: 'ck-heading_heading6'
						}
					]
				},
				placeholder: '',
				fontFamily: {
					options: [
						'default',
						'Arial, Helvetica, sans-serif',
						'Courier New, Courier, monospace',
						'Georgia, serif',
						'Lucida Sans Unicode, Lucida Grande, sans-serif',
						'Tahoma, Geneva, sans-serif',
						'Times New Roman, Times, serif',
						'Trebuchet MS, Helvetica, sans-serif',
						'Verdana, Geneva, sans-serif'
					],
					supportAllValues: true
				},
				fontSize: {
					options: [10, 12, 14, 'default', 18, 20, 22],
					supportAllValues: true
				},
				lineHeight: {
					options: [1, 1.2, 1.5, 2, 2.5, 3],
					supportAllValues: true
				},
				htmlSupport: {
					allow: [{
						name: /.*/,
						attributes: true,
						classes: true,
						styles: true
					}]
				},
				htmlEmbed: {
					showPreviews: true
				},
				mention: {
					feeds: [{
						marker: '@',
						feed: [
							'@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
							'@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
							'@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
							'@sugar', '@sweet', '@topping', '@wafer'
						],
						minimumCharacters: 1
					}]
				},
				removePlugins: [
					'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
					'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
					'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
					'FormatPainter', 'TableOfContents'
				]
			}).then(editor => {
				$('#update_submit_global_setting_btn').on('click', function() {
					editor.updateSourceElement();
					$('#vehicle_terms_condition').parsley().validate();

					if ($('#vehicle_terms_condition').parsley().isValid()) {
						// Form submission logic
					} else {
						// Handle validation errors
					}
				});
			}).catch(err => {
				console.error(err.stack);
			});

			CKEDITOR.ClassicEditor.create(document.getElementById("hotel_terms_condition"), {
				updateSourceElementOnDestroy: true,
				toolbar: {
					items: [
						'exportPDF', 'exportWord', '|',
						'findAndReplace', 'selectAll', '|',
						'heading', '|',
						'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
						'bulletedList', 'numberedList', 'todoList', '|',
						'outdent', 'indent', '|',
						'undo', 'redo',
						'-',
						'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
						'alignment', '|',
						'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
						'specialCharacters', 'horizontalLine', 'pageBreak', '|',
						'textPartLanguage', '|',
						'sourceEditing', 'lineHeight'
					],
					shouldNotGroupWhenFull: true
				},
				list: {
					properties: {
						styles: true,
						startIndex: true,
						reversed: true
					}
				},
				heading: {
					options: [{
							model: 'paragraph',
							title: 'Paragraph',
							class: 'ck-heading_paragraph'
						},
						{
							model: 'heading1',
							view: 'h1',
							title: 'Heading 1',
							class: 'ck-heading_heading1'
						},
						{
							model: 'heading2',
							view: 'h2',
							title: 'Heading 2',
							class: 'ck-heading_heading2'
						},
						{
							model: 'heading3',
							view: 'h3',
							title: 'Heading 3',
							class: 'ck-heading_heading3'
						},
						{
							model: 'heading4',
							view: 'h4',
							title: 'Heading 4',
							class: 'ck-heading_heading4'
						},
						{
							model: 'heading5',
							view: 'h5',
							title: 'Heading 5',
							class: 'ck-heading_heading5'
						},
						{
							model: 'heading6',
							view: 'h6',
							title: 'Heading 6',
							class: 'ck-heading_heading6'
						}
					]
				},
				placeholder: '',
				fontFamily: {
					options: [
						'default',
						'Arial, Helvetica, sans-serif',
						'Courier New, Courier, monospace',
						'Georgia, serif',
						'Lucida Sans Unicode, Lucida Grande, sans-serif',
						'Tahoma, Geneva, sans-serif',
						'Times New Roman, Times, serif',
						'Trebuchet MS, Helvetica, sans-serif',
						'Verdana, Geneva, sans-serif'
					],
					supportAllValues: true
				},
				fontSize: {
					options: [10, 12, 14, 'default', 18, 20, 22],
					supportAllValues: true
				},
				lineHeight: {
					options: [1, 1.2, 1.5, 2, 2.5, 3],
					supportAllValues: true
				},
				htmlSupport: {
					allow: [{
						name: /.*/,
						attributes: true,
						classes: true,
						styles: true
					}]
				},
				htmlEmbed: {
					showPreviews: true
				},
				mention: {
					feeds: [{
						marker: '@',
						feed: [
							'@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
							'@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
							'@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
							'@sugar', '@sweet', '@topping', '@wafer'
						],
						minimumCharacters: 1
					}]
				},
				removePlugins: [
					'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
					'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
					'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
					'FormatPainter', 'TableOfContents'
				]
			}).then(editor => {
				$('#update_submit_global_setting_btn').on('click', function() {
					editor.updateSourceElement();
					$('#hotel_terms_condition').parsley().validate();

					if ($('#hotel_terms_condition').parsley().isValid()) {
						// Form submission logic
					} else {
						// Handle validation errors
					}
				});
			}).catch(err => {
				console.error(err.stack);
			});

			CKEDITOR.ClassicEditor.create(document.getElementById("hotel_voucher_terms_condition"), {
				updateSourceElementOnDestroy: true,
				toolbar: {
					items: [
						'exportPDF', 'exportWord', '|',
						'findAndReplace', 'selectAll', '|',
						'heading', '|',
						'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
						'bulletedList', 'numberedList', 'todoList', '|',
						'outdent', 'indent', '|',
						'undo', 'redo',
						'-',
						'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
						'alignment', '|',
						'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
						'specialCharacters', 'horizontalLine', 'pageBreak', '|',
						'textPartLanguage', '|',
						'sourceEditing', 'lineHeight'
					],
					shouldNotGroupWhenFull: true
				},
				list: {
					properties: {
						styles: true,
						startIndex: true,
						reversed: true
					}
				},
				heading: {
					options: [{
							model: 'paragraph',
							title: 'Paragraph',
							class: 'ck-heading_paragraph'
						},
						{
							model: 'heading1',
							view: 'h1',
							title: 'Heading 1',
							class: 'ck-heading_heading1'
						},
						{
							model: 'heading2',
							view: 'h2',
							title: 'Heading 2',
							class: 'ck-heading_heading2'
						},
						{
							model: 'heading3',
							view: 'h3',
							title: 'Heading 3',
							class: 'ck-heading_heading3'
						},
						{
							model: 'heading4',
							view: 'h4',
							title: 'Heading 4',
							class: 'ck-heading_heading4'
						},
						{
							model: 'heading5',
							view: 'h5',
							title: 'Heading 5',
							class: 'ck-heading_heading5'
						},
						{
							model: 'heading6',
							view: 'h6',
							title: 'Heading 6',
							class: 'ck-heading_heading6'
						}
					]
				},
				placeholder: '',
				fontFamily: {
					options: [
						'default',
						'Arial, Helvetica, sans-serif',
						'Courier New, Courier, monospace',
						'Georgia, serif',
						'Lucida Sans Unicode, Lucida Grande, sans-serif',
						'Tahoma, Geneva, sans-serif',
						'Times New Roman, Times, serif',
						'Trebuchet MS, Helvetica, sans-serif',
						'Verdana, Geneva, sans-serif'
					],
					supportAllValues: true
				},
				fontSize: {
					options: [10, 12, 14, 'default', 18, 20, 22],
					supportAllValues: true
				},
				lineHeight: {
					options: [1, 1.2, 1.5, 2, 2.5, 3],
					supportAllValues: true
				},
				htmlSupport: {
					allow: [{
						name: /.*/,
						attributes: true,
						classes: true,
						styles: true
					}]
				},
				htmlEmbed: {
					showPreviews: true
				},
				mention: {
					feeds: [{
						marker: '@',
						feed: [
							'@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
							'@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
							'@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
							'@sugar', '@sweet', '@topping', '@wafer'
						],
						minimumCharacters: 1
					}]
				},
				removePlugins: [
					'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
					'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
					'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
					'FormatPainter', 'TableOfContents'
				]
			}).then(editor => {
				$('#update_submit_global_setting_btn').on('click', function() {
					editor.updateSourceElement();
					$('#hotel_voucher_terms_condition').parsley().validate();

					if ($('#hotel_voucher_terms_condition').parsley().isValid()) {
						// Form submission logic
					} else {
						// Handle validation errors
					}
				});
			}).catch(err => {
				console.error(err.stack);
			});

			CKEDITOR.ClassicEditor.create(document.getElementById("vehicle_voucher_terms_condition"), {
				updateSourceElementOnDestroy: true,
				toolbar: {
					items: [
						'exportPDF', 'exportWord', '|',
						'findAndReplace', 'selectAll', '|',
						'heading', '|',
						'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
						'bulletedList', 'numberedList', 'todoList', '|',
						'outdent', 'indent', '|',
						'undo', 'redo',
						'-',
						'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
						'alignment', '|',
						'link', 'blockQuote', 'insertTable', 'codeBlock', 'htmlEmbed', '|',
						'specialCharacters', 'horizontalLine', 'pageBreak', '|',
						'textPartLanguage', '|',
						'sourceEditing', 'lineHeight'
					],
					shouldNotGroupWhenFull: true
				},
				list: {
					properties: {
						styles: true,
						startIndex: true,
						reversed: true
					}
				},
				heading: {
					options: [{
							model: 'paragraph',
							title: 'Paragraph',
							class: 'ck-heading_paragraph'
						},
						{
							model: 'heading1',
							view: 'h1',
							title: 'Heading 1',
							class: 'ck-heading_heading1'
						},
						{
							model: 'heading2',
							view: 'h2',
							title: 'Heading 2',
							class: 'ck-heading_heading2'
						},
						{
							model: 'heading3',
							view: 'h3',
							title: 'Heading 3',
							class: 'ck-heading_heading3'
						},
						{
							model: 'heading4',
							view: 'h4',
							title: 'Heading 4',
							class: 'ck-heading_heading4'
						},
						{
							model: 'heading5',
							view: 'h5',
							title: 'Heading 5',
							class: 'ck-heading_heading5'
						},
						{
							model: 'heading6',
							view: 'h6',
							title: 'Heading 6',
							class: 'ck-heading_heading6'
						}
					]
				},
				placeholder: '',
				fontFamily: {
					options: [
						'default',
						'Arial, Helvetica, sans-serif',
						'Courier New, Courier, monospace',
						'Georgia, serif',
						'Lucida Sans Unicode, Lucida Grande, sans-serif',
						'Tahoma, Geneva, sans-serif',
						'Times New Roman, Times, serif',
						'Trebuchet MS, Helvetica, sans-serif',
						'Verdana, Geneva, sans-serif'
					],
					supportAllValues: true
				},
				fontSize: {
					options: [10, 12, 14, 'default', 18, 20, 22],
					supportAllValues: true
				},
				lineHeight: {
					options: [1, 1.2, 1.5, 2, 2.5, 3],
					supportAllValues: true
				},
				htmlSupport: {
					allow: [{
						name: /.*/,
						attributes: true,
						classes: true,
						styles: true
					}]
				},
				htmlEmbed: {
					showPreviews: true
				},
				mention: {
					feeds: [{
						marker: '@',
						feed: [
							'@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
							'@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
							'@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
							'@sugar', '@sweet', '@topping', '@wafer'
						],
						minimumCharacters: 1
					}]
				},
				removePlugins: [
					'CKBox', 'CKFinder', 'EasyImage', 'RealTimeCollaborativeComments', 'RealTimeCollaborativeTrackChanges',
					'RealTimeCollaborativeRevisionHistory', 'PresenceList', 'Comments', 'TrackChanges', 'TrackChangesData',
					'RevisionHistory', 'Pagination', 'WProofreader', 'MathType', 'SlashCommand', 'Template', 'DocumentOutline',
					'FormatPainter', 'TableOfContents'
				]
			}).then(editor => {
				$('#update_submit_global_setting_btn').on('click', function() {
					editor.updateSourceElement();
					$('#vehicle_voucher_terms_condition').parsley().validate();

					if ($('#vehicle_voucher_terms_condition').parsley().isValid()) {
						// Form submission logic
					} else {
						// Handle validation errors
					}
				});
			}).catch(err => {
				console.error(err.stack);
			});
		}

		$(document).ready(function() {

			flatpickr('#itinerary_common_buffer_time', {
				enableTime: true,
				enableSeconds: false,
				noCalendar: true,
				dateFormat: "H:i",
				time_24hr: true,
				minDate: new Date(0, 0, 0, 1, 0) // Set minimum time to 01:00 (1 hour)
			});

			flatpickr('#itinerary_travel_by_flight_buffer_time', {
				enableTime: true,
				enableSeconds: false,
				noCalendar: true,
				dateFormat: "H:i",
				time_24hr: true,
				minDate: new Date(0, 0, 0, 1, 0) // Set minimum time to 01:00 (1 hour)
			});

			flatpickr('#itinerary_travel_by_train_buffer_time', {
				enableTime: true,
				enableSeconds: false,
				noCalendar: true,
				dateFormat: "H:i",
				time_24hr: true,
				minDate: new Date(0, 0, 0, 1, 0) // Set minimum time to 01:00 (1 hour)
			});

			flatpickr('#itinerary_travel_by_road_buffer_time', {
				enableTime: true,
				enableSeconds: false,
				noCalendar: true,
				dateFormat: "H:i",
				time_24hr: true,
				minDate: new Date(0, 0, 0, 1, 0) // Set minimum time to 01:00 (1 hour)
			});

			//AJAX FORM SUBMIT
			$("#form_global_setting").submit(function(event) {
				var form = $('#form_global_setting')[0];
				var data = new FormData(form);
				// $(this).find("button[id='submit_hotspot_info_btn']").prop('disabled', true);
				$.ajax({
					type: "post",
					url: 'engine/ajax/__ajax_manage_global_setting.php?type=global_setting',
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
						if (response.errros.itinerary_distance_limit_required) {
							TOAST_NOTIFICATION('warning', 'Distance Limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.allowed_km_required) {
							TOAST_NOTIFICATION('warning', 'Allowed KM Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_common_buffer_time_required) {
							TOAST_NOTIFICATION('warning', 'Common Buffer Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_travel_by_flight_buffer_time_required) {
							TOAST_NOTIFICATION('warning', 'Flight Buffer Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_travel_by_train_buffer_time_required) {
							TOAST_NOTIFICATION('warning', 'Train Buffer Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_travel_by_road_buffer_time_required) {
							TOAST_NOTIFICATION('warning', 'Road Buffer Time Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_break_time_required) {
							TOAST_NOTIFICATION('warning', 'Day First Start Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_hotel_return_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Hotel Return Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_hotel_start_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Hotel Start Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.custom_hotspot_or_activity_required) {
							TOAST_NOTIFICATION('warning', 'Custom Hotspot or Activity Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.accommodation_return_required) {
							TOAST_NOTIFICATION('warning', 'Accommodation Return Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.terms_condition_required) {
							TOAST_NOTIFICATION('warning', 'Vehicle & Hotel Terms and Condition Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_local_speed_limit_required) {
							TOAST_NOTIFICATION('warning', 'itinerary local speed limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.itinerary_outstation_speed_limit_required) {
							TOAST_NOTIFICATION('warning', 'itinerary outstation speed limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_name_return_required) {
							TOAST_NOTIFICATION('warning', 'Company Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_address_return_required) {
							TOAST_NOTIFICATION('warning', 'Company Address Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_pincode_return_required) {
							TOAST_NOTIFICATION('warning', 'Company Pincode Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_gstin_no_return_required) {
							TOAST_NOTIFICATION('warning', 'Company GSTIN No. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_contact_no_return_required) {
							TOAST_NOTIFICATION('warning', 'Company Contact No. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_email_id_return_required) {
							TOAST_NOTIFICATION('warning', 'Company Email ID. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.cc_email_id_return_required) {
							TOAST_NOTIFICATION('warning', 'CC Email ID. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.hotel_hsn_return_required) {
							TOAST_NOTIFICATION('warning', 'Hotel HSN. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.vehicle_hsn_return_required) {
							TOAST_NOTIFICATION('warning', 'Vehicle HSN. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.service_component_hsn_return_required) {
							TOAST_NOTIFICATION('warning', 'Guide + Hotspot + Activity HSN. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_pan_no_return_required) {
							TOAST_NOTIFICATION('warning', 'Company PAN No. Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.site_title_return_required) {
							TOAST_NOTIFICATION('warning', 'Site Title is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.youtube_link_required) {
							TOAST_NOTIFICATION('warning', 'YouTube Link Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.linkedin_link_required) {
							TOAST_NOTIFICATION('warning', 'LinkedIn Link Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.instagram_link_required) {
							TOAST_NOTIFICATION('warning', 'Instagram Link Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.facebook_link_required) {
							TOAST_NOTIFICATION('warning', 'Facebook Link Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.company_cin_required) {
							TOAST_NOTIFICATION('warning', 'Company CIN Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.account_holder_name_required) {
							TOAST_NOTIFICATION('warning', 'Account Holder Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.account_number_required) {
							TOAST_NOTIFICATION('warning', 'Account Number Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.bank_name_required) {
							TOAST_NOTIFICATION('warning', 'Bank Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.branch_name_required) {
							TOAST_NOTIFICATION('warning', 'Branch Name Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.bank_ifsc_code_required) {
							TOAST_NOTIFICATION('warning', 'Bank IFSC Code Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.default_hotel_voucher_email_id_required) {
							TOAST_NOTIFICATION('warning', 'Hotel Voucher Default Email ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.default_vehicle_voucher_email_id_required) {
							TOAST_NOTIFICATION('warning', 'Vehicle Voucher Default Email ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.default_accounts_email_id_required) {
							TOAST_NOTIFICATION('warning', 'Default Accounts Email ID Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}else if (response.errros.itinerary_additional_margin_day_limit_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Additional Margin Day Limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}else if (response.errros.itinerary_additional_margin_percentage_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Additional Margin Percentage Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						//SUCCESS RESPOSNE
						if (response.u_result == true) {
							//RESULT SUCCESS
							TOAST_NOTIFICATION('success', 'Global Settings Updated', 'Success !!!', '', '', '', '', '', '', '', '', '');
							/* location.assign(response.redirect_URL); */
						} else if (response.u_result == false) {
							//RESULT FAILED
							TOAST_NOTIFICATION('success', 'Unable to Update Global Settings', 'Success !!!', '', '', '', '', '', '', '', '', '');
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

</body>

</html>