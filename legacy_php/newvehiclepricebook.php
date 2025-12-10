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
// admin_reguser_protect();
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
											<div class="row g-3">
												<div class="col-3">
													<label class="form-label" for="vehicle_type_id">Vehicle Type <span class=" text-danger"> *</span></label>
													<select id="vehicle_type_id" name="vehicle_type_id" class="form-select form-control form-selectize" data-parsley-trigger="keyup" data-parsley-errors-container="#vehicle_type_id_error_container">
														<option value="">Select Any One </option>
														<?= getVEHICLETYPE('', 'select'); ?>
													</select>
													<div id="vehicle_type_id_error_container"></div>
												</div>
												<div class="col-3">
													<label class="form-label" for="cost_type">Cost Type<span class="text-danger">*</span></label>
													<select id="cost_type" name="cost_type" class="form-select form-control form-selectize" data-parsley-trigger="keyup" data-parsley-errors-container="#cost_type_error_container"  onchange="changeCosttype()">
														<option value="">Select Any One</option>
														<option value="1">Local</option>
														<option value="2">Outstation</option>
													</select>
													<div id="cost_type_error_container"></div>
												</div>
												<div class="col-3">
													<label class="form-label " for="year">Year<span class=" text-danger"> *</span></label>
													<input type="text" class="form-control" placeholder="Choose year" name="year" id="year" required autocomplete="off" onchange="updateDateRange()" />
												</div>

												<div class="col-3">
													<label class="form-label " for="month">Month<span class=" text-danger"> *</span></label>
													<select id="month" name="month" class="form-select form-control form-selectize" data-parsley-trigger="keyup" data-parsley-errors-container="#month_error_container" onchange="updateDateRange()">
														<?= getMONTHS_LIST($month_id, 'select'); ?>
													</select>
													<div id="month_error_container"></div>
												</div>
												<div class="col-3" id="repeatFor1">
													<label for="selectstartdate" class="form-label">Start Date</label>
													<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectstartdate" name="selectstartdate" required />
												</div>
												<div class="col-3" id="repeatFor2">
													<label for="selectenddate" class="form-label">End Date</label>
													<input type="text" class="form-control" placeholder="DD/MM/YYYY" id="selectenddate" name="selectenddate" required />
												</div>
												<div class="col-3 d-none" id="outstaion_km_dropdown">
													<label class="form-label" for="kms_limit">Select KM Limit</label>
													<select id="kms_limit" name="kms_limit" class="form-select form-control form-selectize">
														<?= getKMLIMIT('', 'select', $logged_user_id); ?>
													</select>
												</div>
												<div class="col-3 d-none" id="outstaion_price">
													<label class="form-label" for="price">Price ₹</label>
													<input type="text" id="price" name="price" required class="form-control" placeholder="Enter Price">
												</div>
												
												<div class="col-12 d-none" id="cost_type_local">
													<h5 class="">Cost type - <span class="text-primary">Local</span></h5>
													
													<div class="row">
														<div class="col-5">
															<table class="table table-bordered text-center">
																<thead>
																  <tr>
																	<th>Time & KM</th>
																	<th>Price</th>
																  </tr>
																</thead>
																<tbody>
																  <tr>
																	<td>4 Hrs 40 KM</td>
																	<td>
																		<input type="text" id="price" name="price" required class="form-control w-px-150 mx-auto" placeholder="Enter Price">
																	</td>
																  </tr>
																  <tr>
																	<td>5 Hrs 50 KM</td>
																	<td>
																		<input type="text" id="price" name="price" required class="form-control w-px-150 mx-auto" placeholder="Enter Price">
																	</td>
																  </tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
												
												<div class="col-12 d-flex justify-content-between">
													<button type="reset" class="btn btn-label-secondary waves-effect my-2">
														Cancel
													</button>
													<button type="submit" id="form_submit_vehicle_cost_pricebook" class="btn btn-primary my-2">
														Submit
													</button>
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
		// Get references to input elements
		const yearInput = document.getElementById('year');
		const monthSelect = document.getElementById('month');
		const fromDateInput = document.getElementById('selectstartdate');
		const toDateInput = document.getElementById('selectenddate');
			
		$(document).ready(function() {
			$(".form-selectize").selectize();
			
			$("#vehicle_type_id").attr('required', true);
			$("#cost_type").attr('required', true);
			$("#month").attr('required', true);
			
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

						if (response.errros.vehicle_type_required) {
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
						} else if (response.errros.selectperdate_required) {
							TOAST_NOTIFICATION('warning', 'Date Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}

						if (response.result_success) {
							TOAST_NOTIFICATION('error', 'Unable to submit', 'Error !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						document.getElementById("vehicle_cost_pricebook_form").reset();
						filter_calendar();
						TOAST_NOTIFICATION('success', 'Submit Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				});
				event.preventDefault();
			});

			selectstartdate = flatpickr("#selectstartdate", {
				enableTime: false,
				dateFormat: "d-m-Y"
			});

			selectenddate = flatpickr("#selectenddate", {
				enableTime: false,
				dateFormat: "d-m-Y"
			});
			
			// Initialize datepicker for year input
			$('#year').datepicker({
				autoclose: true,
				minViewMode: 2,
				format: 'yyyy'
			});
		});
		
		// Function to update the fromDate and toDate inputs based on selection
		function updateDateRange() {
			const selectedYear = parseInt(yearInput.value);
			const selectedMonth = monthSelect.value;

			// Calculate the last day of the selected month
			const lastDay = new Date(selectedYear, selectedMonth, 0).getDate();

			// Set fromDate to the 1st of the selected month and year
			selectstartdate.setDate(`01-${selectedMonth}-${selectedYear}`, true);

			// Set toDate to the last day of the selected month and year
			selectenddate.setDate(`${lastDay}-${selectedMonth}-${selectedYear}`, true);
		}

		function changeCosttype() {
			var selectedCostType = document.getElementById('cost_type').value;
		
			if (selectedCostType === '1') {
				$('#cost_type_local').removeClass("d-none");
				$('#outstaion_km_dropdown').addClass("d-none");
				$('#outstaion_price').addClass("d-none");
			} else if (selectedCostType === '2') {
				$('#cost_type_local').addClass("d-none");
				$('#outstaion_km_dropdown').removeClass("d-none");
				$('#outstaion_price').removeClass("d-none");
			}
		}
		
		function updateDatepicker() {
			const year = parseInt(document.getElementById("year").value);
			const month = parseInt(document.getElementById("month").value);
			//const lastDay = new Date(year, month, 0).getDate();
			const minDate = new Date(year, month - 1, 1);
			const maxDate = new Date(year, month, 0);

			selectperdate.set("minDate", minDate);
			selectperdate.set("maxDate", maxDate);

			selectstartdate.set("minDate", minDate);
			selectstartdate.set("maxDate", maxDate);

			selectenddate.set("minDate", minDate);
			selectenddate.set("maxDate", maxDate);
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

			// Function to fetch JSON data based on vehicleTypeFilter
			function fetchEventsData() {
				return fetch(
						"engine/json/__JSON_vehicle_cost_calendar_pricebook_details.php"
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
					showPRICEBOOK_MODAL(formattedDate);
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
	</script>

</body>

</html>