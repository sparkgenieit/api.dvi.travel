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
	<link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&amp;ampdisplay=swap" rel="stylesheet">
	<link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

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
	<link rel="stylesheet" href="assets/vendor/libs/mapbox-gl/mapbox-gl.css" />

	<!-- Page CSS -->

	<link rel="stylesheet" href="assets/vendor/css/pages/app-logistics-fleet.css" />

	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>
	<script src="assets/js/config.js"></script>
	<link rel="stylesheet" href="assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css">
	<link rel="stylesheet" href="assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/flatpickr/flatpickr.css" />

	<!-- Row Group CSS -->
	<link rel="stylesheet" href="assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css">
	<!-- Form Validation -->
	<link rel="stylesheet" href="assets/vendor/libs/%40form-validation/umd/styles/index.min.css" />
	<link rel="stylesheet" href="assets/css/_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" href="assets/css/_1.13.6_css_jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/parsley_validation.css">
	<link rel="stylesheet" type="text/css" href="assets/js/selectize/selectize.bootstrap5.css">
	<link rel="stylesheet" href="assets/vendor/libs/bs-stepper/bs-stepper.css" />
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="assets/vendor/css/pages/app-chat.css">
	<link rel="stylesheet" href="assets/vendor/libs/select2/select2.css" />
	<link rel="stylesheet" href="assets/vendor/libs/tagify/tagify.css" />
	<link rel="stylesheet" href="assets/vendor/css/pages/ui-carousel.css" />
	<link rel="stylesheet" href="assets/vendor/libs/swiper/swiper.css" />
	<link rel="stylesheet" href="assets/vendor/libs/toastr/toastr.css" />
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="assets/css/easy-autocomplete.css" />
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= $GOOGLEMAP_API_KEY; ?>&libraries=places"></script>

</head>

<body>
	<!-- Layout wrapper -->
	<div class="layout-wrapper layout-content-navbar  ">
		<div class="layout-container">
			<!-- Menu -->
			<?php include_once 'public/__sidebar.php'; ?>
			<!-- / Menu -->

			<!-- Layout container -->
			<div class="layout-page">

				<!-- Navbar -->
				<?php include_once 'public/__topbar.php'; ?>
				<!-- / Navbar -->

				<!-- Content wrapper -->
				<div class="content-wrapper">
					<!-- Content -->
					<div class="container-xxl flex-grow-1 container-p-y">
						<div class="d-flex justify-content-between align-items-center">
							<h4><?php include adminpublicpath('__pagetitle.php'); ?></h4>
							<?php include adminpublicpath('__breadcrumb.php'); ?>
						</div>
						<span id="showITINERARYLIST"></span>
						<span id="showITINERARYFORM1"></span>
						<span id="showITINERARYFORM2"></span>
						<span id="showITINERARYFORM3"></span>
					</div>
					<!-- / Content -->
				</div>
				<!-- Content wrapper -->
			</div>
			<!-- / Layout page -->
		</div>

		<!-- Overlay -->
		<div class=" layout-overlay layout-menu-toggle">
		</div>
		<!-- Drag Target Area To SlideIn Menu On Small Screens -->
		<div class="drag-target"></div>

		<div id="spinner"></div>
	</div>
	<!-- / Layout wrapper -->

	<!-- Add New Activities Modal -->
	<div class="modal fade" id="addACTIVITIES" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
		<div class="modal-dialog modal-lg modal-simple modal-add-new-address modal-dialog-centered">
			<div class="modal-content p-3 p-md-5">
				<div class="receiving-activity-form-data">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
		</div>
	</div>

	<!--/ Add New Activities Modal -->

	<!-- Add New Guide Modal -->
	<div class="modal fade" id="addGUIDEADDFORM" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
			<div class="modal-content">
				<div class="receiving-guide-add-form-data">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
		</div>
	</div>
	<!-- Add New Guide Modal -->

	<div class="modal fade" id="confirmALTERDAYINFODATA" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
			<div class="modal-content p-3">
				<div class="modal-body receiving-confirm-alter-day-form-data">
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="itineraryALERT" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
			<div class="modal-content">
				<div class="receiving-itinerary-alert-data">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modalHotelCheckIn" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content receiving-itinerary-hotel-data">
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
	<script src="assets/js/_jquery.dataTables.min.js"></script>
	<script src="assets/js/_dataTables.buttons.min.js"></script>
	<script src="assets/js/selectize/selectize.min.js"></script>
	<script src="assets/js/jquery.easy-autocomplete.min.js"></script>
	<script src="assets/vendor/libs/toastr/toastr.js"></script>
	<script src="assets/vendor/libs/leaflet/leaflet.js"></script>
	<script src="assets/js/maps-leaflet.js"></script>
	<script src="assets/js/footerscript.js"></script>
	<script src="assets/vendor/libs/dropzone/dropzone.js"></script>
	<script src="assets/vendor/libs/fullcalendar/fullcalendar.js"></script>
	<script src="assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
	<script src="assets/vendor/js/bootstrap.min.js"></script>
	<script src="assets/vendor/js/popper.min.js"></script>
	<script src="assets/js/_jszip_3.10.1_jszip.min.js"></script>
	<script src="assets/js/_pdfmake_0.1.53_pdfmake.min.js"></script>
	<script src="assets/js/_libs_pdfmake_0.1.53_vfs_fonts.js"></script>
	<script src="assets/js/_js_buttons.html5.min.js"></script>
	<!-- Main JS -->
	<script src="assets/js/main.js"></script>
	<!-- Page JS -->
	<script src="assets/js/app-logistics-fleet.js"></script>
	<script src="assets/js/app-chat.js"></script>
	<script src="assets/js/forms-tagify.js"></script>
	<!-- Phone Swiper -->
	<script src="assets/vendor/libs/sortablejs/sortable.js"></script>
	<script src="assets/vendor/libs/swiper/swiper.js"></script>
	<!-- Phone Swiper -->
	<!-- Sticky -->
	<script src="assets/vendor/libs/cleavejs/cleave.js"></script>
	<script src="assets/vendor/libs/cleavejs/cleave-phone.js"></script>
	<script src="assets/js/form-layouts.js"></script>
	<!-- Sticky -->
	<script src="assets/js/ui-carousel.js"></script>
	<script src="assets/js/extended-ui-drag-and-drop.js"></script>
	<script src="assets/vendor/libs/select2/select2.js"></script>
	<script src="assets/js/forms-selects.js"></script>


	<script>
		$(document).ready(function() {
			// Open the modal
			$("#openModalBtn").click(function() {
				$("#modalHotelCheckIn").modal("show");
			});

			// Close the modal
			$("#closeModalBtn").click(function() {
				$("#modalHotelCheckIn").modal("hide");
			});
		});
		$(document).ready(function() {
			$('#addACTIVITIES').modal({
				backdrop: 'static',
				keyboard: false
			});
		});

		function showDayFormHideDiv(itinerary_route_counter) {
			var checkbox = document.getElementById("day_guide_applicable_" + itinerary_route_counter);

			if (checkbox.checked) {
				$(".day_guide_applicable_form_" + itinerary_route_counter).removeClass('d-none');
			} else {
				$(".day_guide_applicable_form_" + itinerary_route_counter).addClass('d-none');
			}
		}

		<?php if (($_GET['route'] == '') && $_GET['formtype'] == '') : ?>
			show_ITINERARY_LIST();
		<?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'basic_info') : ?>
			show_ITINERARY_FORM_STEP1('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
		<?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'itinerary_routes') : ?>
			show_ITINERARY_FORM_STEP2('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
		<?php elseif (($_GET['route'] == 'add' || $_GET['route'] == 'edit') && $_GET['formtype'] == 'generate_itinerary') : ?>
			show_ITINERARY_FORM_STEP3('<?= $_GET['route']; ?>', '<?= $_GET['id']; ?>');
		<?php endif; ?>

		function show_ITINERARY_LIST() {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_itinerary_list.php?type=show_form',
				success: function(response) {
					$('#showITINERARYLIST').html(response);
				}
			});
		}

		function show_ITINERARY_FORM_STEP1(TYPE, ID) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_itinerary_basic_info_form.php?type=show_form',
				data: {
					ID: ID,
					TYPE: TYPE
				},
				success: function(response) {
					$('#showITINERARYLIST').html('');
					$('#showITINERARYFORM1').html(response);
				}
			});
		}

		function show_ITINERARY_FORM_STEP2(TYPE, ID) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_itinerary_routes_form.php?type=show_form',
				data: {
					ID: ID,
					TYPE: TYPE
				},
				success: function(response) {
					$('#showITINERARYLIST').html('');
					$('#showITINERARYFORM1').html('');
					$('#showITINERARYFORM2').html(response);
				}
			});
		}

		function show_ITINERARY_FORM_STEP3(TYPE, ID) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_generate_itinerary_plan.php?type=show_form',
				data: {
					ID: ID,
					TYPE: TYPE
				},
				success: function(response) {
					$('#showITINERARYLIST').html('');
					$('#showITINERARYFORM1').html('');
					$('#showITINERARYFORM2').html('');
					$('#showITINERARYFORM3').html(response);
				}
			});
		}

		//ADD MODAL
		function showACTIVITYMODAL(itinerary_count, itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_id, dayOfWeekNumeric, hotspot_start_time, hotspot_end_time) {
			$('.receiving-activity-form-data').load('engine/ajax/__ajax_add_activity_modal.php?type=show_hotspot_activity_form&itinerary_count=' + itinerary_count + '&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&route_hotspot_ID=' + route_hotspot_ID + '&hotspot_id=' + hotspot_id + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&hotspot_start_time=' + hotspot_start_time + '&hotspot_end_time=' + hotspot_end_time, function() {
				const container = document.getElementById("addACTIVITIES");
				const modal = new bootstrap.Modal(container);
				modal.show();
			});
		}

		function showACTIVITYFORHOTSPOTMODAL(itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_id, dayOfWeekNumeric, hotspot_start_time, hotspot_end_time) {
			$('.receiving-activity-form-data').load('engine/ajax/__ajax_add_activity_modal.php?type=show_hotspot_activity_form&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&route_hotspot_ID=' + route_hotspot_ID + '&hotspot_id=' + hotspot_id + '&dayOfWeekNumeric=' + dayOfWeekNumeric + '&hotspot_start_time=' + hotspot_start_time + '&hotspot_end_time=' + hotspot_end_time, function() {
				const container = document.getElementById("addACTIVITIES");
				const modal = new bootstrap.Modal(container);
				modal.show();
			});
		}

		function show_added_ITINERARY_DETAILS(itinerary_count, itinerary_plan_ID, itinerary_route_ID, hotspot_route_date) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_show_added_hotspot_in_itinerary_plan.php?type=show_form',
				data: {
					itinerary_count: itinerary_count,
					itinerary_route_ID: itinerary_route_ID,
					itinerary_plan_ID: itinerary_plan_ID,
					hotspot_route_date: hotspot_route_date
				},
				success: function(response) {
					$('#default_itineray_header.default_itineray_header_' + itinerary_count).removeClass('d-none');
					$('#show_add_hotsopt_form.show_add_hotsopt_form_' + itinerary_count).html('');
					$('#show_added_hotspot_response.show_added_hotspot_response_' + itinerary_count).html(response);
					$('#show_available_hotspot_list.show_available_hotspot_list_' + itinerary_count).html('');
				}
			});
		}

		//Add Itinerary Activity 
		function add_ITINEARY_ROUTE_ACTIVITY(route_hotspot_ID, activity_id, dayOfWeekNumeric, hotspot_entry_time_label, itinerary_count, hotspot_id) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_manage_newitinerary.php?type=itinerary_route_activity_details',
				data: {
					hotspot_id: hotspot_id,
					route_hotspot_ID: route_hotspot_ID,
					activity_id: activity_id,
					dayOfWeekNumeric: dayOfWeekNumeric
				},
				dataType: 'json',
				success: function(response) {
					if (!response.success) {
						// NOT SUCCESS RESPONSE
						if (response.errors.route_hotspot_ID_required) {
							TOAST_NOTIFICATION('warning', 'Route Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.activity_id_required) {
							TOAST_NOTIFICATION('warning', 'Activity ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.dayOfWeekNumeric_required) {
							TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						// SUCCESS RESPOSNE
						if (response.i_result == true) {
							if (itinerary_count != '') {
								show_added_ITINERARY_DETAILS(itinerary_count, response.itinerary_plan_ID, response.itinerary_route_ID, hotspot_entry_time_label);
							}
							$('#overall_trip_cost').html(response.overall_trip_cost);
							// alert();
							$('#add_itinerary_activity_' + route_hotspot_ID + '_' + activity_id).addClass('d-none');
							$('#remove_itinerary_activity_' + route_hotspot_ID + '_' + activity_id).removeClass('d-none');
							TOAST_NOTIFICATION('success', 'Activity Added Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.i_result == false) {
							// RESULT FAILED
							TOAST_NOTIFICATION('warning', 'Unable to Add Activity', 'Success !!!', '', '', '', '', '', '', '', '', '');
						}
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				}
			});
		}

		//Remove Itinerary Activity
		function remove_ITINEARY_ROUTE_ACTIVITY(route_hotspot_ID, activity_id, dayOfWeekNumeric, itinerary_count, hotspot_entry_time_label, hotspot_id) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_manage_newitinerary.php?type=remove_itineary_route_activity_details',
				data: {
					hotspot_id: hotspot_id,
					route_hotspot_ID: route_hotspot_ID,
					activity_id: activity_id,
					dayOfWeekNumeric: dayOfWeekNumeric,
					itinerary_count: itinerary_count,
					hotspot_entry_time_label: hotspot_entry_time_label
				},
				dataType: 'json',
				success: function(response) {
					if (!response.success) {
						// NOT SUCCESS RESPONSE
						if (response.errors.route_hotspot_ID_required) {
							TOAST_NOTIFICATION('warning', 'Route Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.activity_id_required) {
							TOAST_NOTIFICATION('warning', 'Activity ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.dayOfWeekNumeric_required) {
							TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						// SUCCESS RESPOSNE
						if (response.i_result == true) {
							if (itinerary_count != '') {
								show_added_ITINERARY_DETAILS(itinerary_count, response.itinerary_plan_ID, response.itinerary_route_ID, hotspot_entry_time_label);
							}
							$('#overall_trip_cost').html(response.overall_trip_cost);

							// alert();
							$('#remove_itinerary_activity_' + route_hotspot_ID + '_' + activity_id).addClass('d-none');
							$('#add_itinerary_activity_' + route_hotspot_ID + '_' + activity_id).removeClass('d-none');
							TOAST_NOTIFICATION('success', 'Activity Removed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.i_result == false) {
							// RESULT FAILED
							TOAST_NOTIFICATION('warning', 'Unable to Remove Activity', 'Success !!!', '', '', '', '', '', '', '', '', '');
						}
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				}
			});
		}

		function show_list_ITINERARY_DETAILS(hotspot_ID, date, time, route_hotspot_ID, dayOfWeekNumeric, itinerary_count) {
			var activity_name = $('#activity_name').val();
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_add_activity_list_modal.php?type=show_form',
				data: {
					activity_name: activity_name,
					hotspot_ID: hotspot_ID,
					date: date,
					time: time,
					route_hotspot_ID: route_hotspot_ID,
					dayOfWeekNumeric: dayOfWeekNumeric,
					itinerary_count: itinerary_count
				},
				success: function(response) {
					$('#itinerary_hotspot_activity').html(response);
				}
			});
		}

		function remove_itinerary_route_hotspot_in_list(hotspot_id, itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric, type) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_manage_newitinerary.php?type=remove_itinerary_route_hotspot_details',
				data: {
					hotspot_id: hotspot_id,
					itinerary_route_ID: itinerary_route_ID,
					itinerary_plan_ID: itinerary_plan_ID,
					dayOfWeekNumeric: dayOfWeekNumeric
				},
				dataType: 'json',
				success: function(response) {
					if (!response.success) {
						// NOT SUCCESS RESPONSE
						if (response.errors.hotspot_id_required) {
							TOAST_NOTIFICATION('warning', 'Hotspot ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.itinerary_route_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.itinerary_plan_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.dayOfWeekNumeric_required) {
							TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						// SUCCESS RESPOSNE
						if (response.i_result == true) {
							// alert();
							$('#remove_itinerary_hotspot_' + hotspot_id).addClass('d-none');
							$('#add_itinerary_hotspot_' + hotspot_id).removeClass('d-none');
							show_ITINERARY_FORM_STEP3(type, itinerary_plan_ID);
							TOAST_NOTIFICATION('success', 'Hotspot Removed Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');
							$('#overall_trip_cost').html(response.overall_trip_cost);
						} else if (response.i_result == false) {
							// RESULT FAILED
							if (response.hotspot_not_available_status == true) {
								TOAST_NOTIFICATION('warning', response.hotspot_not_available, 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else if (response.hotspot_day_time_over_status == true) {
								TOAST_NOTIFICATION('warning', response.hotspot_day_time_over, 'Warning !!!', '', '', '', '', '', '', '', '', '');
							} else {
								TOAST_NOTIFICATION('warning', 'Unable to Add Hotspot', 'Success !!!', '', '', '', '', '', '', '', '', '');
							}
							show_ITINERARY_FORM_STEP3(type, itinerary_plan_ID);
						}
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				}
			});
		}

		function skipActivity(itinerary_plan_ID, itinerary_route_ID, route_hotspot_ID, hotspot_id) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_manage_newitinerary.php?type=skip_activty',
				data: {
					itinerary_plan_ID: itinerary_plan_ID,
					itinerary_route_ID: itinerary_route_ID,
					route_hotspot_ID: route_hotspot_ID,
					hotspot_id: hotspot_id
				},
				dataType: 'json',
				success: function(response) {
					if (!response.success) {
						// NOT SUCCESS RESPONSE
						if (response.errors.itinerary_plan_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.itinerary_route_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Route ID is required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.route_hotspot_ID_required) {
							TOAST_NOTIFICATION('warning', 'Route Hotspot ID is required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.hotspot_id_required) {
							TOAST_NOTIFICATION('warning', 'Hotspot ID is required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						// SUCCESS RESPOSNE
						if (response.u_result == true) {
							$('#addACTIVITIES').modal('hide');
							TOAST_NOTIFICATION('success', response.success_message, 'Success !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.u_result == false) {
							// RESULT FAILED
							TOAST_NOTIFICATION('warning', 'Unable to Update', 'Success !!!', '', '', '', '', '', '', '', '', '');
						}
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				}
			});

		}

		function showHOTELCHECKINMODAL(itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric) {
			$('.receiving-itinerary-hotel-data').load('engine/ajax/__ajax_add_hotel_checkin_modal.php?type=show_modal&itinerary_plan_ID=' + itinerary_plan_ID + '&itinerary_route_ID=' + itinerary_route_ID + '&dayOfWeekNumeric=' + dayOfWeekNumeric, function() {
				const container = document.getElementById("modalHotelCheckIn");
				const modal = new bootstrap.Modal(container);
				modal.show();

				// Add event listener to button inside modal
				$('.close-modal-button').click(function() {
					modal.hide(); // Close the modal
					$('#check_in_hotel_status_' + itinerary_route_ID).val('1');
				});
			});
		}

		function add_ITINEARY_ROUTE_HOTEL(latitude, longitude, itinerary_plan_hotel_details_ID, itinerary_route_ID, itinerary_plan_ID, dayOfWeekNumeric) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_manage_newitinerary.php?type=add_hotel_day_wise',
				data: {
					latitude: latitude,
					longitude: longitude,
					itinerary_plan_hotel_details_ID: itinerary_plan_hotel_details_ID,
					itinerary_route_ID: itinerary_route_ID,
					itinerary_plan_ID: itinerary_plan_ID,
					dayOfWeekNumeric: dayOfWeekNumeric
				},
				dataType: 'json',
				success: function(response) {
					if (!response.success) {
						// NOT SUCCESS RESPONSE
						if (response.errors.itinerary_plan_hotel_details_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Hotel ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.itinerary_route_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Route ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.itinerary_plan_ID_required) {
							TOAST_NOTIFICATION('warning', 'Itinerary Plan ID is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						} else if (response.errors.dayOfWeekNumeric_required) {
							TOAST_NOTIFICATION('warning', 'Day Of Week Numeric is Required', 'Warning !!!', '', '', '', '', '', '', '', '', '');
						}
					} else {
						// SUCCESS RESPOSNE
						if (response.i_result == true) {

							TOAST_NOTIFICATION('success', 'Hotel Add Successfully', 'Success !!!', '', '', '', '', '', '', '', '', '');

							$('.close-modal-button').click();

							$('.view_itinerary_' + itinerary_plan_ID + '_' + itinerary_route_ID).click();
							$('.customize_' + itinerary_plan_ID + '_' + itinerary_route_ID).click();
						}
					}
					if (response == "OK") {
						return true;
					} else {
						return false;
					}
				}
			});
		}
	</script>
</body>

</html>