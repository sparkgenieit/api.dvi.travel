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

	<title><?php include adminpublicpath('__pagetitle.php'); ?> - <?= $_SITETITLE; ?></title>

	<!-- Favicon -->
	<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicon/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon/favicon-16x16.png">
	<link rel="manifest" href="assets/img/favicon/site.webmanifest">

	<!-- Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">

	<!-- Icons -->
	<link rel="stylesheet" href="assets/vendor/fonts/fontawesome.css" />
	<link rel="stylesheet" href="assets/vendor/fonts/tabler-icons.css" />
	<link rel="stylesheet" href="assets/vendor/fonts/flag-icons.css" />

	<!-- Core CSS -->
	<link rel="stylesheet" href="assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
	<link rel="stylesheet" href="assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
	<link rel="stylesheet" href="assets/css/demo.css" />

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
	<!-- Row Group CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
	<!-- Form Validation -->
	<link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
	<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
	<link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
	<link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
	<link rel="stylesheet" href="assets/vendor/libs/dropzone/dropzone.css">
	<link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />
	<link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />
	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>
	<script src="assets/js/config.js"></script>
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
	<style>
		/* Hide the "All Day" text in Calendar list view */
		.fc-list-event-time {
			display: none;
		}

		.fc-timegrid-axis-cushion,
		.fc-timegrid-slot {
			display: none;
		}

		.light-style .fc .fc-day-today {
			background-color: #fff !important;
		}
	</style>
</head>

<body>
	<div class="layout-wrapper layout-content-navbar ">
		<div class="layout-container">

			<!-- Menu -->
			<?php include_once('public/__sidebar.php'); ?>
			<!-- / Menu -->

			<!-- Layout container -->
			<div class="layout-page">

				<!-- Navbar -->
				<?php include_once('public/__topbar.php'); ?>
				<!-- / Navbar -->

				<!-- Content wrapper -->
				<div class="content-wrapper">
					<!-- Content -->
					<div class="container-xxl flex-grow-1 container-p-y">
						<div class=" d-flex justify-content-between align-items-center">
							<h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
							<?php include adminpublicpath('__breadcrumb.php'); ?>
						</div>

						<div class="row mb-3">
							<div class="col-md-12">
								<div class="card ">
									<div class="card-body">
										<form id="vehicle_cost_pricebook_form" action="" method="POST" data-parsley-validate>
											<div class="row g-3" id="vendor_outstation_details">
												<?php if ($logged_vendor_id != "" && $logged_vendor_id != 0) : ?>
													<div class="col-3">
														<label class="form-label" for="vendor_branch">Vendor Branch <span class=" text-danger"> *</span></label>
														<select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
															<?= getVENDORBRANCHDETAIL($vendor_branch, $logged_vendor_id, 'select'); ?>
														</select>
													</div>
													<div class="col-3" id="vehicletypeDiv">
														<label class="form-label" for="vehicle_type">Vehicle Type <span class=" text-danger"> *</span></label>
														<select id="vehicle_type" name="vehicle_type" class="form-select form-control" onchange="changeCosttype()" required>
															<option value=""> Choose Vehicle Type</option>
														</select>
													</div>
													<div class="col-3">
														<label class="form-label" for="cost_type">Cost Type<span class="text-danger">*</span></label>
														<select id="cost_type" name="cost_type" class="form-control" onchange="changeCosttype()" required>
															<option value="">Select Any One</option>
															<option value="1">Local</option>
															<option value="2">Outstation</option>
														</select>
													</div>
													<!--<div class="col-3">
														<label class="form-label" for="year">Year<span class="text-danger"> *</span></label>
														<input type="text" class="form-control" placeholder="Choose year" name="year" id="year" required autocomplete="off" />
													</div>

													<div class="col-3">
														<label class="form-label" for="month">Month<span class=" text-danger"> *</span></label>
														<select id="month" name="month" class="form-control" data-parsley-trigger="keyup" onchange="updateDateRange()">
															<?= getMONTHS_LIST($month_id, 'select'); ?>
														</select>
													</div>-->
													<div class="col-3" id="repeatFor1">
														<label for="selectstartdate" class="form-label">Start Date</label>
														<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
													</div>
													<div class="col-3" id="repeatFor2">
														<label for="selectenddate" class="form-label">End Date</label>
														<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
													</div>
													<input type="hidden" name="vendor_name" id="vendor_name" value="<?= $logged_vendor_id; ?>">
												<?php else : ?>
													<div class=" col-3">
														<label class="form-label" for="vendor_name">Vendor Name<span class=" text-danger"> *</span></label>
														<select id="vendor_name" name="vendor_name" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
															<?= getVENDOR_DETAILS($vendor_name, 'select'); ?>
														</select>
													</div>
													<div class="col-3" id="vendorbranchDiv">
														<label class="form-label" for="vendor_branch">Vendor Branch <span class=" text-danger"> *</span></label>
														<select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
															<option value=""> Choose Vendor Branch</option>
														</select>
													</div>
													<div class="col-3" id="vehicletypeDiv">
														<label class="form-label" for="vehicle_type">Vehicle Type <span class=" text-danger"> *</span></label>
														<select id="vehicle_type" name="vehicle_type" class="form-select form-control" onchange="changeCosttype()" required>
															<option value=""> Choose Vehicle Type</option>
														</select>
													</div>
													<div class="col-3">
														<label class="form-label" for="cost_type">Cost Type<span class="text-danger">*</span></label>
														<select id="cost_type" name="cost_type" class="form-control" onchange="changeCosttype()" required>
															<option value="">Select Any One</option>
															<option value="1">Local</option>
															<option value="2">Outstation</option>
														</select>
													</div>
													<!--<div class="col-3">
														<label class="form-label" for="year">Year<span class="text-danger"> *</span></label>
														<input type="text" class="form-control" placeholder="Choose year" name="year" id="year" required autocomplete="off" />
													</div>

													<div class="col-3">
														<label class="form-label" for="month">Month<span class=" text-danger"> *</span></label>
														<select id="month" name="month" class="form-control" data-parsley-trigger="keyup" onchange="updateDateRange()">
															<?= getMONTHS_LIST($month_id, 'select'); ?>
														</select>
													</div>-->
													<div class="col-3" id="repeatFor1">
														<label for="selectstartdate" class="form-label">Start Date</label>
														<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
													</div>
													<div class="col-3" id="repeatFor2">
														<label for="selectenddate" class="form-label">End Date</label>
														<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
													</div>
												<?php endif; ?>
											</div>

											<div class="row g-3">
												<!-- LOCAL FIELDS -->
												<div class="col-12" id="vendor_local_details"></div>

												<div id="div_submit_btn" class="col-12">
													<div class="d-flex justify-content-between">
														<button type="button" class="btn btn-label-secondary waves-effect my-2" onclick="resetForm()">
															Cancel
														</button>
														<button type="submit" id="form_submit_vehicle_cost_pricebook" class="btn btn-primary my-2">
															Submit
														</button>
													</div>
												</div>
											</div>

										</form>
									</div>
								</div>
							</div>
						</div>

						<div class="row" id="show_calendar_div">
							<div class="col-12">
								<div class="card app-calendar-wrapper">
									<div class="row g-0">
										<!-- Calendar Sidebar -->
										<div class="col app-calendar-sidebar" id="app-calendar-sidebar">
											<div class="p-3">
												<!-- Filter -->
												<div class="mb-3 ms-3">
													<small class="text-small text-muted text-uppercase align-middle">Filter</small>
												</div>

												<div class="form-check mb-2 ms-3">
													<input class="form-check-input select-all" type="checkbox" id="selectAll" data-value="all" checked>
													<label class="form-check-label" for="selectAll">View All</label>
												</div>

												<div class="app-calendar-events-filter ms-3">
													<div class="form-check form-check-success mb-2">
														<input class="form-check-input input-filter" type="checkbox" id="select-rooms" data-value="local" checked>
														<label class="form-check-label" for="select-rooms">Local</label>
													</div>
													<div class="form-check form-check-warning mb-2">
														<input class="form-check-input input-filter" type="checkbox" id="select-amenities" data-value="outstation" checked>
														<label class="form-check-label" for="select-amenities">Outstation</label>
													</div>
												</div>
												<br>
												<?php if ($logged_vendor_id != "" && $logged_vendor_id != 0) : ?>
													<div class="mb-3 ms-3">
														<label class="form-label" for="vehicle_type_id">Vehicle Type <span class=" text-danger"> *</span></label>
														<select id="filter_vehicle_type_id" name="filter_vehicle_type_id" class="form-select form-control form-selectize" data-parsley-trigger="keyup" onchange="filter_calendar()">
															<?=
															getVENDOR_VEHICLE_TYPES($logged_vendor_id, '', 'select'); ?>
														</select>
													</div>

													<div class="mb-3 ms-3" id="vendorbranchfilterdiv">
														<label class="driver-text-label w-100" for="filter_vendor_branch_id">Choose Vendor Branch <span class=" text-danger"> *</span></label>
														<select id="filter_vendor_branch_id" name="filter_vendor_branch_id" class="form-select form-control form-selectize" data-parsley-trigger="keyup" onchange="filter_calendar()">
															<?=
															getVENDORBRANCHDETAIL('', $logged_vendor_id, 'select') ?>
														</select>
													</div>

												<?php else : ?>

													<div class="mb-3 ms-3">
														<label class="driver-text-label w-100" for="vendor_id">Choose Vendor<span class=" text-danger"> *</span></label>
														<select id="filter_vendor_id" name="filter_vendor_id" class="form-select form-control" data-parsley-trigger="keyup" onchange="showVEHICLE_TYPES();">
															<?= getVENDOR_DETAILS($vendor_id, 'select'); ?>
														</select>

													</div>

													<div class="mb-3 ms-3">
														<label class="driver-text-label w-100" for="filter_vehicle_type_id">Choose Vehicle Type<span class=" text-danger"> *</span></label>
														<select id="filter_vehicle_type_id" name="filter_vehicle_type_id" class="form-select form-control form-selectize" data-parsley-trigger="keyup" onchange="filter_calendar()">
															<option value="">Choose Vehicle Type</option>
														</select>
													</div>

													<div class="mb-3 ms-3" id="vendorbranchfilterdiv">
														<label class="driver-text-label w-100" for="filter_vendor_branch_id">Choose Vendor Branch <span class=" text-danger"> *</span></label>
														<select id="filter_vendor_branch_id" name="filter_vendor_branch_id" class="form-select form-control form-selectize" data-parsley-trigger="keyup" onchange="filter_calendar()">
															<option value="">Choose Vendor Branch</option>
														</select>
													</div>

												<?php endif; ?>
											</div>
										</div>
										<!-- /Calendar Sidebar -->

										<!-- Calendar & Modal -->
										<div class="col app-calendar-content">
											<div class="card shadow-none border-0">
												<div class="card-body pb-0">
													<!-- FullCalendar -->
													<div id="calendar"></div>
												</div>
											</div>
											<div class="app-overlay"></div>
										</div>
										<!-- /Calendar & Modal -->
									</div>
								</div>
							</div>
						</div>

					</div>
					<!-- / Content -->
					<!-- Footer -->
					<?php include_once('public/__footer.php'); ?>
					<!-- / Footer -->
				</div>
				<!-- Content wrapper -->
			</div>
			<!-- / Layout page -->
		</div>
	</div>
	<!-- / Layout wrapper -->

	<div class="modal fade" id="showPRICEBOOKFORM" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog modal-xl modal-dialog-top">
			<div class="modal-content">
				<div class="modal-body show-pricebook-form-data">
				</div>
			</div>
		</div>
	</div>

	<!--  DELETE COURSE MODAL -->
	<div class="modal fade" id="confirmDELETEINFODATA" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
			<div class="modal-content p-0">
				<div class="modal-body receiving-confirm-delete-form-data">
				</div>
			</div>
		</div>
	</div>

	<!-- Core JS -->
	<!-- build:js assets/vendor/js/core.js -->

	<script src="assets/vendor/libs/jquery/jquery.js"></script>
	<script src="assets/vendor/libs/popper/popper.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
	<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
	<script src="assets/vendor/libs/i18n/i18n.js"></script>
	<script src="assets/vendor/js/menu.js"></script>

	<!-- endbuild -->
	<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
	<script src="assets/vendor/libs/tagify/tagify.js"></script>

	<!-- Form Validation -->
	<script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
	<!-- Vendors JS -->

	<script src="assets/js/selectize/selectize.min.js"></script>
	<script src="assets/js/jquery.easy-autocomplete.min.js"></script>
	<script src="assets/vendor/libs/toastr/toastr.js"></script>
	<script src="assets/js/footerscript.js"></script>
	<script src="assets/vendor/libs/dropzone/dropzone.js"></script>
	<script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>

	<!-- Vehicle Cost Calendar -->
	<script src="assets/js/app-vehicle-cost-calendar.js?roomTypeFilterValue=" + roomTypeFilterValue></script>

	<script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
	<script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
	<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/bootstrap-datepicker.min.js"></script>
	<!-- Main JS -->
	<script src="assets/js/main.js"></script>
	<script src="assets/js/parsley.min.js"></script>
	<script>
		$(document).ready(function() {

			$("select").selectize();

			//AJAX FORM SUBMIT
			$("#vehicle_cost_pricebook_form").submit(function(event) {
				var form = $('#vehicle_cost_pricebook_form')[0];
				var data = new FormData(form);
				console.log(data);
				//$(this).find("button[type='submit']").prop('disabled', true);
				// spinner.show();
				$.ajax({
					type: "post",
					url: 'engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=vehicle_cost_pricebook',
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
						if (response.errros.vendor_branch_required) {
							TOAST_NOTIFICATION('warning', 'Vendor Branch Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.vehicle_type_required) {
							TOAST_NOTIFICATION('warning', 'Vehicle Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.year_required) {
							TOAST_NOTIFICATION('warning', 'Year Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.month_required) {
							TOAST_NOTIFICATION('warning', 'Month Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.price_required) {
							TOAST_NOTIFICATION('warning', 'Price Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.cost_type_required) {
							TOAST_NOTIFICATION('warning', 'Cost Type Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.hours_limit_required) {
							TOAST_NOTIFICATION('warning', 'Hours Limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.kms_limit_required) {
							TOAST_NOTIFICATION('warning', 'KM Limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.time_limit_required) {
							TOAST_NOTIFICATION('warning', 'Time Limit Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.selectstartdate_required) {
							TOAST_NOTIFICATION('warning', 'Start Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errros.selectenddate_required) {
							TOAST_NOTIFICATION('warning', 'End Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {

						/*document.getElementById('hour-dropdown').style.display = 'none';
						document.getElementById('km-dropdown').style.display = 'none';
						document.getElementById('hour-limit-dropdown').style.display = 'none';

						document.getElementById("repeatFor1").style.display = "none";
						document.getElementById("repeatFor2").style.display = "none";
						document.getElementById("selectperdate_div").style.display = "block";*/
						if (response.result_success == false) {
							TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
						} else {
							document.getElementById("vehicle_cost_pricebook_form").reset();
							filter_calendar();
							TOAST_NOTIFICATION('success', 'Submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							//SUCCESS RESPOSNE
							setTimeout(function() {
								location.reload();
							}, 1000);
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

			var startDatePicker = flatpickr("#selectstartdate", {
				dateFormat: "d-m-Y",
				onChange: function(selectedDates, dateStr, instance) {
					endDatePicker.set("minDate", dateStr);
				}
			});

			var endDatePicker = flatpickr("#selectenddate", {
				dateFormat: "d-m-Y"
			});

		});

		/*	
		function showPRICEBOOK_MODAL(DATE) {
				$('.show-pricebook-form-data').load('engine/ajax/__ajax_show_vehicle_pricebook_form.php?type=show_form&DT=' + DATE + '&ID=<?= $_GET['vehicle_type']; ?>', function() {
					const container = document.getElementById("showPRICEBOOKFORM");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}

			//SHOW DELETE POPUP
			function showDELETEVEHICLECOSTPRICEBOOKMODAL(ID, COST_TYPE, DATE) {
				$('.receiving-confirm-delete-form-data').load('engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=delete_vehicle_pricebook&ID=' + ID + '&COST_TYPE=' + COST_TYPE + '&DATE=' + DATE, function() {
					const container = document.getElementById("confirmDELETEINFODATA");
					const modal = new bootstrap.Modal(container);
					modal.show();
				});
			}
			//CONFIRM DELETE POPUP
			function confirmVEHICLECOSTPRICEBOOKDELETE(ID, COST_TYPE, DATE) {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=confirmdelete_vehicle_pricebook",
					data: {
						_ID: ID,
						_COST_TYPE: COST_TYPE,
						_DATE: DATE,
					},
					dataType: 'json',
					success: function(response) {
						if (response.result == true) {
							show_UPDATE_VEHICLETYPE(response.selectedValue, response.date);
							show_UPDATE_OUTSTATION_VEHICLETYPE(response.selectedValue, response.date);

							$('#confirmDELETEINFODATA').modal('hide');
							TOAST_NOTIFICATION('success', 'Delete Updated Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
						} else {
							TOAST_NOTIFICATION('error', 'Unable to Delete', 'Error !!!', '', '', '', '', '', '', '', '', '');
						}
					}
				});
			}

			function show_UPDATE_VEHICLETYPE(selectedValue, date) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=show_local_vehicle',
					data: {
						filter_pricebook_id: selectedValue,
						filter_date: date,
						// Add more data key-value pairs as needed
					},
					success: function(response) {
						$('#ajax_vehicle_local_form').html(response);
					}
				});
			}

			function show_UPDATE_OUTSTATION_VEHICLETYPE(selectedValue, date) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=show_outstation_vehicle',
					data: {
						filter_pricebook_id: selectedValue,
						filter_date: date,
						// Add more data key-value pairs as needed
					},
					success: function(response) {
						$('#ajax_vehicle_outstation_form').html(response);
					}
				});
			}*/
		<?php if ($logged_vendor_id == "" || $logged_vendor_id == 0) : ?>

			function showVEHICLE_TYPES() {

				var vendor_vehicle_selectize = $("#filter_vehicle_type_id")[0].selectize;
				var vendor_id = $("#filter_vendor_id").val();
				$.ajax({
					url: 'engine/ajax/__ajax_get_vendor_vehicle_types.php?type=selectize_vehicle_types',
					type: "POST",
					data: {
						vendor_id: vendor_id
					},
					success: function(response) {
						// Append the response to the dropdown.
						var allOption = {
							value: '',
							text: 'All'
						};
						response.unshift(allOption);
						vendor_vehicle_selectize.clear();
						vendor_vehicle_selectize.clearOptions();
						vendor_vehicle_selectize.addOption(response);
						vendor_vehicle_selectize.setValue(response[0].value);

					}
				});
				showFILTER_VENDOR_BRANCHES(vendor_id);
				filter_calendar();
			}
		<?php endif; ?>

		function showFILTER_VENDOR_BRANCHES(ID) {

			var vendor_branch_selectize = $("#filter_vendor_branch_id")[0].selectize;
			var vendor_id = $("#filter_vendor_id").val();
			$.ajax({
				url: 'engine/ajax/__ajax_vehicle_overall_pricebook.php',
				type: "POST",
				data: {
					ID: vendor_id,
					TYPE: 'selectize_vendor_branch'
				},
				success: function(response) {
					// Append the response to the dropdown.

					vendor_branch_selectize.clear();
					vendor_branch_selectize.clearOptions();
					vendor_branch_selectize.addOption(response);
					vendor_branch_selectize.setValue(response[0].value);
				}
			});
		}

		function filter_calendar() {
			let date = new Date(),
				nextDay = new Date(new Date().getTime() + 864e5),
				nextMonth =
				11 === date.getMonth() ?
				new Date(date.getFullYear() + 1, 0, 1) :
				new Date(date.getFullYear(), date.getMonth() + 1, 1),
				prevMonth =
				11 === date.getMonth() ?
				new Date(date.getFullYear() - 1, 0, 1) :
				new Date(date.getFullYear(), date.getMonth() - 1, 1);

			let events = []; // Initialize an empty array for events.


			<?php if ($logged_vendor_id == "" || $logged_vendor_id == 0) : ?>
				var filter_vendor_id = document.getElementById("filter_vendor_id").value;
			<?php else : ?>
				var filter_vendor_id = "";
			<?php endif; ?>
			var filter_vehicle_type_id = document.getElementById("filter_vehicle_type_id").value;
			var filter_vendor_branch_id = document.getElementById("filter_vendor_branch_id").value;
			// Function to fetch JSON data based on vehicleTypeFilter
			function fetchEventsData() {
				return fetch(
						"engine/json/__JSON_vehicle_cost_calendar_pricebook_details.php?filter_vehicle_type_id=" + filter_vehicle_type_id + "&filter_vendor_id=" + filter_vendor_id + "&filter_vendor_branch_id=" + filter_vendor_branch_id
					)
					.then((response) => response.json())
					.catch((error) => {
						console.error("Error loading events:", error);
					});
			}

			// document.addEventListener("DOMContentLoaded", function() {
			const v = document.getElementById("calendar");

			const r = new Calendar(v, {
				initialView: "dayGridMonth",
				plugins: [dayGridPlugin, interactionPlugin, listPlugin, timegridPlugin],
				editable: true,
				dateClick: function(e) {
					const clickedDate = e.date;
					const year = clickedDate.getFullYear();
					const month = String(clickedDate.getMonth() + 1).padStart(2, "0"); // Adding 1 because months are zero-based
					const day = String(clickedDate.getDate()).padStart(2, "0");

					const formattedDate = `${year}-${month}-${day}`;
					// Show your modal popup here
					//showPRICEBOOK_MODAL(formattedDate);
				},
				headerToolbar: {
					left: "prev,next",
					center: "title",
					right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek", // Include the views you want
				},
				buttonText: {
					month: "Month",
					week: "Week",
					day: "Day",
					list: "List",
				},
				eventClassNames: function({
					event: e
				}) {
					const classNames = [];

					// Check if 'calendar' property exists in the extendedProps
					if (e.extendedProps && e.extendedProps.calendar) {
						const calendarColorMap = {
							'local': 'fc-event-success',
							'Business': 'fc-event-primary',
							'outstation': 'fc-event-warning',
							// Add more calendar-color mappings as needed
						};

						const calendarClassName = calendarColorMap[e.extendedProps.calendar];

						if (calendarClassName) {
							classNames.push(calendarClassName);
						}
					}

					return classNames;
				},
			});

			const checkboxes = document.querySelectorAll('.input-filter');
			const viewAllCheckbox = document.getElementById('selectAll'); // Assuming you have an "View All" checkbox

			// Function to handle the "View All" checkbox
			function handleViewAllCheckbox() {
				const isChecked = viewAllCheckbox.checked;

				if (isChecked) {
					r.addEventSource(events);
					// Iterate through the selected checkboxes and uncheck them
					checkboxes.forEach(function(checkbox) {
						checkbox.checked = true;
					});
					viewAllCheckbox.checked = true;
				} else {
					r.removeAllEvents();
					// Iterate through the selected checkboxes and uncheck them
					checkboxes.forEach(function(checkbox) {
						checkbox.checked = false;
					});
				}
			}

			// Attach a click event listener to the "View All" checkbox
			viewAllCheckbox.addEventListener('click', handleViewAllCheckbox);

			// Function to handle the filtering based on checkboxes
			function filterEvents() {

				const selectedFilters = Array.from(checkboxes)
					.filter(checkbox => checkbox.checked && checkbox.id !== 'selectAll') // Exclude the "View All" checkbox
					.map(checkbox => checkbox.getAttribute('data-value'));

				if (selectedFilters != 'local,outstation') {
					viewAllCheckbox.checked = false;
				} else {
					viewAllCheckbox.checked = true;
				}

				const filteredEvents = events.filter(event => {
					if (selectedFilters.length === 0 || selectedFilters.includes('all')) {
						// If "View All" is checked or no specific filters are selected, show all events
						if (selectedFilters.length === 0) {
							return false;
						} else {
							return true;
						}
					} else {
						// Adjust this condition based on your data structure and how you want to filter
						return selectedFilters.includes(event.extendedProps.calendar.toLowerCase());
					}
				});

				r.removeAllEvents(); // Remove existing events from the calendar
				r.addEventSource(filteredEvents); // Add filtered events to the calendar
			}

			// Attach a click event listener to each checkbox to trigger the filtering
			checkboxes.forEach(checkbox => {
				checkbox.addEventListener('click', filterEvents);
			});
			// Initial filtering on page load (if needed)
			filterEvents();
			console.log($('#vehicleTypeFilter').val());
			// Fetch initial data and update events
			fetchEventsData($('#vehicleTypeFilter').val())
				.then((data) => {
					events = data;
					r.addEventSource(events);
					r.render();
				});
			// );
		}

		function resetForm() {
			var form = document.getElementById("vehicle_cost_pricebook_form");
			form.reset();

			// Assuming you have a Selectize instance named mySelectize
			var mySelectize_vehicle_type_id = $('#vehicle_type').selectize()[0].selectize;
			// Resetting the Selectize instance
			mySelectize_vehicle_type_id.clear();

			// Assuming you have a Selectize instance named mySelectize
			var mySelectize_cost_type = $('#cost_type').selectize()[0].selectize;
			// Resetting the Selectize instance
			mySelectize_cost_type.clear();

			// Assuming you have a Selectize instance named mySelectize
			//var mySelectize_month = $('#month').selectize()[0].selectize;
			// Resetting the Selectize instance
			//mySelectize_month.clear();

			$('#outstaion_km_dropdown').remove();
			$('#outstaion_price').remove();
			$('#vendor_local_details').html('');

			// Scroll to the top of the page
			window.scrollTo({
				top: 0,
				behavior: 'smooth' // This makes the scrolling smooth, but it's optional
			});
		}

		function changeCosttype() {
			var selectedCostType = document.getElementById('cost_type').value;
			var vehicle_type_id = document.getElementById('vehicle_type').value;
			var vehicle_branch_id = document.getElementById('vendor_branch').value;
			var vendor_id = document.getElementById('vendor_name').value;

			if (vehicle_type_id != "" && selectedCostType != "" && vehicle_branch_id != "" && vendor_id != "") {
				$.ajax({
					type: "POST",
					url: "engine/ajax/__ajax_manage_vehicle_cost_pricebook_form.php?type=show_vendor_pricebook_form",
					data: {
						selectedCostType: selectedCostType,
						vehicle_type_id: vehicle_type_id,
						vehicle_branch_id: vehicle_branch_id,
						vendor_id: vendor_id
					},
					success: function(response) {

						if (selectedCostType === '1') {
							$('#vendor_local_details').html(response);
							//$('#vendor_outstation_details').html('');
							$('#outstaion_km_dropdown').remove();
							$('#outstaion_price').remove();
						} else {
							//$('#vendor_outstation_details').html(response);
							$('#outstaion_km_dropdown').remove();
							$('#outstaion_price').remove();
							$('#vendor_outstation_details').append(response);
							$('#vendor_local_details').html('');
						}
					}
				});
			}
		}

		<?php if ($logged_vendor_id == "" || $logged_vendor_id == 0) : ?>
			//trigger hotel name through hotel category
			var vendorNameSelectize = $('#vendor_name').selectize()[0].selectize;
			var vendorBranchSelectize = $('#vendor_branch').selectize()[0].selectize;
			// Listen for the change event on Selectize
			vendorNameSelectize.on('change', function() {
				var vendorNameValue = vendorNameSelectize.getValue();
				var vendorBranchValue = vendorBranchSelectize.getValue();
				console.log("Selected VendorName value: " + vendorNameValue);

				if (vendorNameValue !== '' && vendorNameValue !== '0') {
					show_branch_of_the_vendor('select_branch', vendorNameValue);
				}
			});

			function show_branch_of_the_vendor(TYPE, ID) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vehicle_overall_pricebook.php',
					data: {
						ID: ID,
						TYPE: TYPE
					},
					success: function(response) {
						$('#vendorbranchDiv').html(response);
					}
				});
			}
		<?php else : ?>
			//trigger hotel name through hotel category
			var vendorBranchSelectize = $('#vendor_branch').selectize()[0].selectize;
			var vehicleTypeSelectize = $('#vehicle_type').selectize()[0].selectize;
			// Listen for the change event on Selectize
			vendorBranchSelectize.on('change', function() {
				var vendorBranchValue = vendorBranchSelectize.getValue();
				var vehicletypeValue = vehicleTypeSelectize.getValue();
				console.log("Selected vendorName value: " + vendorBranchValue);

				if (vendorBranchValue !== '' && vendorBranchValue !== '0') {
					show_vehicle_type_for_vendorbranch('select_vehicle_type', '<?= $logged_vendor_id; ?>');
				}
			});

			function show_vehicle_type_for_vendorbranch(TYPE, ID) {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_vehicle_overall_pricebook.php',
					data: {
						ID: ID,
						TYPE: TYPE
					},
					success: function(response) {
						$('#vehicletypeDiv').html(response);
					}
				});
			}
		<?php endif; ?>
	</script>

</body>

</html>