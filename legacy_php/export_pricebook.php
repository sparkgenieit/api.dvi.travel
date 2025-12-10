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

if (isset($_POST['confirm_download']) && $_POST['confirm_download'] == "download") :


	$pricebook_type = $_POST['pricebook_type'];
	//	HOTEL PRICE DETAILS
	if ($pricebook_type == 1) :

		$hotel_id = trim($validation_globalclass->sanitize($_POST['hotel_name']));
		$room_type = trim($validation_globalclass->sanitize($_POST['room_type']));
		$month = trim($validation_globalclass->sanitize($_POST['month']));
		$month_name = getMONTHS_LIST($month, 'label');
		$year = trim($validation_globalclass->sanitize($_POST['year']));

		$url = "export_hotel_room_pricebook.php?hotel=" . urlencode($hotel_id) .
			"&room_type=" . urlencode($room_type) .
			"&month=" . urlencode($month) .
			"&year=" . urlencode($year);

		// Redirect to the constructed URL
		header("Location: $url");
		exit();


	endif;

endif;

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
	<link rel="stylesheet" href="assets/css/bootstrap-datepicker3.css" />

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
	<link rel="stylesheet" href="assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css">
	<link rel="stylesheet" href="assets/vendor/libs/fullcalendar/fullcalendar.css" />
	<link rel="stylesheet" href="assets/vendor/css/pages/app-calendar.css" />

	<!-- Helpers -->
	<script src="assets/vendor/js/helpers.js"></script>
	<script src="assets/js/config.js"></script>
</head>

<body>
	<div class="layout-wrapper layout-content-navbar ">
		<div class="layout-container">

			<!-- Menu -->
			<?php include_once('public/__sidebar.php'); ?>
			<!-- / Menu -->

			<!-- Layout container -->
			<div class="layout-page">

				<!-- Content wrapper -->
				<div class="content-wrapper">

					<!-- Content -->
					<div class="container-xxl flex-grow-1 container-p-y">
						<div class=" d-flex justify-content-between align-items-center">
							<div>
								<h4>Export Price Details</h4>
							</div>
						</div>

						<div class="row">
							<div class="col-xl-12">
								<div class="nav-align-top  mb-4">
									<ul class="nav nav-tabs mb-3 p-2 border-0" role="tablist" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
										<li class="nav-item">
											<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#home-pricebook" aria-controls="home-pricebook" aria-selected="true">Hotel
												Pricebook</button>
										</li>
										<li class="nav-item">
											<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#amenities-pricebook" aria-controls="amenities-pricebook" aria-selected="false">Amenities
												Pricebook</button>
										</li>
										<li class="nav-item">
											<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#vehicle-pricebook" aria-controls="vehicle-pricebook" aria-selected="false">Vehicle Pricebook</button>
										</li>
										<li class="nav-item">
											<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#guide-pricebook" aria-controls="guide-pricebook" aria-selected="false">Guide Pricebook</button>
										</li>
										<li class="nav-item">
											<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#activity-pricebook" aria-controls="activity-pricebook" aria-selected="false">Activity Pricebook</button>
										</li>
										<li class="nav-item">
											<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#toll-pricebook" aria-controls="toll-pricebook" aria-selected="false">Toll</button>
										</li>
										<li class="nav-item">
											<button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#parking-pricebook" aria-controls="parking-pricebook" aria-selected="false">Parking</button>
										</li>
									</ul>
									<div class="tab-content" style="box-shadow: 0px 2px 6px 0px rgba(135, 70, 180, 0.2) !important;">
										<div class="tab-pane fade show active" id="home-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-md-3">
													<label class="form-label" for="hotel_city">City <span class="text-danger">*</span></label>
													<select class="form-select" name="hotel_city" id="hotel_city" onchange="CHOOSEN_CITY()" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_city_error_container">
														<option value="">Please Choosen City</option>
													</select>
													<div id="hotel_city_error_container"></div>
												</div>
												<div class="col-md-3">
													<label class="form-label" for="hotel_month">Month <span class="text-danger">*</span> </label>
													<select class="form-select" name="hotel_month" id="hotel_month">
														<option value="">Please Choosen City</option>
														<option value="1">Januvary</option>
														<option value="2">Febrary</option>
														<option value="3">March</option>
														<option value="4">April</option>
													</select>
												</div>
												<div class="col-md-3">
													<label class="form-label" for="hotel_year">Year <span class="text-danger">*</span></label>
													<div class="input-group">
														<input name="hotel_year" id="hotel_year" autocomplete="off" required class="form-control" placeholder="Month" />
													</div>
												</div>
												<div class="col-md-3 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Hotel Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Hotel Name</th>
																		<th scope="col">Room Type</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Hilton</td>
																		<td>DELUXE</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="amenities-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-md-3">
													<label class="form-label" for="hotel_city">City <span class="text-danger">*</span></label>
													<select class="form-select" name="hotel_city" id="hotel_city" onchange="CHOOSEN_CITY()" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_city_error_container">
														<option value="">Please Choosen City</option>
													</select>
													<div id="hotel_city_error_container"></div>
												</div>
												<div class="col-md-3">
													<label class="form-label" for="hotel_city">Hotel <span class="text-danger">*</span></label>
													<select class="form-select" name="hotel_city" id="hotel_city" onchange="CHOOSEN_CITY()" data-parsley-trigger="keyup" data-parsley-errors-container="#hotel_city_error_container">
														<option value="">Please Choosen Hotel</option>
													</select>
													<div id="hotel_city_error_container"></div>
												</div>
												<div class="col-md-2">
													<label class="form-label" for="amenities_month">Month <span class="text-danger">*</span> </label>
													<select class="form-select" name="amenities_month" id="amenities_month">
														<option value="">Choosen Month</option>
														<option value="1">Januvary</option>
														<option value="2">Febrary</option>
														<option value="3">March</option>
														<option value="4">April</option>
													</select>
												</div>
												<div class="col-md-2">
													<label class="form-label" for="amenities_year">Year <span class="text-danger">*</span></label>
													<div class="input-group">
														<input name="amenities_year" id="amenities_year" autocomplete="off" required class="form-control" placeholder="Month" />
													</div>
												</div>
												<div class="col-md-2 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Amenities Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Hotel Name</th>
																		<th scope="col">Amentities</th>
																		<th scope="col">Amentities Type</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Hilton</td>
																		<td>WIFI NETWORK</td>
																		<td>Hour</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="vehicle-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-3">
													<label class="form-label" for="vendor_vehicle">Vendor<span class=" text-danger"> *</span></label>
													<select id="vendor_vehicle" name="vendor_vehicle" class="form-select form-control" data-parsley-trigger="keyup" required>
														<option value="">Choosen Vendor</option>
													</select>
												</div>
												<div class="col-md-3">
													<label class="form-label" for="vendor_branch">Vendor Branch <span class=" text-danger"> *</span></label>
													<select id="vendor_branch" name="vendor_branch" class="form-select form-control" data-parsley-trigger="keyup" onchange="changeCosttype()" required>
														<?= getVENDORBRANCHDETAIL($vendor_branch, $logged_vendor_id, 'select'); ?>
													</select>
												</div>
												<div class="col-md-2">
													<label class="form-label" for="vehicle_month">Month <span class="text-danger">*</span> </label>
													<select class="form-select" name="vehicle_month" id="vehicle_month">
														<option value="">Choosen Month</option>
														<option value="1">Januvary</option>
														<option value="2">Febrary</option>
														<option value="3">March</option>
														<option value="4">April</option>
													</select>
												</div>
												<div class="col-md-2">
													<label class="form-label" for="vehicle_year">Year <span class="text-danger">*</span></label>
													<div class="input-group">
														<input name="vehicle_year" id="vehicle_year" autocomplete="off" required class="form-control" placeholder="Month" />
													</div>
												</div>
												<div class="col-md-2 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Vehicle Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Vehicle Name</th>
																		<th scope="col">Vendor</th>
																		<th scope="col">Branch</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Sedan</td>
																		<td>Uber</td>
																		<td>Uber-Chennai</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="guide-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-md-3">
													<label class="form-label" for="guide_month">Month <span class="text-danger">*</span> </label>
													<select class="form-select" name="guide_month" id="guide_month">
														<option value="">Choosen Month</option>
														<option value="1">Januvary</option>
														<option value="2">Febrary</option>
														<option value="3">March</option>
														<option value="4">April</option>
													</select>
												</div>
												<div class="col-md-3">
													<label class="form-label" for="guide_year">Year <span class="text-danger">*</span></label>
													<div class="input-group">
														<input name="guide_year" id="guide_year" autocomplete="off" required class="form-control" placeholder="Month" />
													</div>
												</div>
												<div class="col-md-6 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Guide Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Guide Name</th>
																		<th scope="col">Slot</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Ariya</td>
																		<td>Slot 1, slot 2</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="activity-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-md-3">
													<label class="form-label" for="activity_month">Month <span class="text-danger">*</span> </label>
													<select class="form-select" name="activity_month" id="activity_month">
														<option value="">Choosen Month</option>
														<option value="1">Januvary</option>
														<option value="2">Febrary</option>
														<option value="3">March</option>
														<option value="4">April</option>
													</select>
												</div>
												<div class="col-md-3">
													<label class="form-label" for="activity_year">Year <span class="text-danger">*</span></label>
													<div class="input-group">
														<input name="activity_year" id="activity_year" autocomplete="off" required class="form-control" placeholder="Month" />
													</div>
												</div>
												<div class="col-md-6 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Activity Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Activity Name</th>
																		<th scope="col">Hotspot</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Water Boat</td>
																		<td>Lake</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="toll-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-md-3" id="vehicletypeDiv">
													<label class="form-label" for="vehicle_type">Vehicle Type <span class=" text-danger"> *</span></label>
													<select id="vehicle_type" name="vehicle_type" class="form-select form-control" onchange="changeCosttype()" required>
														<option value=""> Choose Vehicle Type</option>
													</select>
												</div>
												<div class="col-md-9 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Toll Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Location Name</th>
																		<th scope="col">Vehicle Type</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Chennai</td>
																		<td>Innova</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="parking-pricebook" role="tabpanel">
											<div class="row">
												<div class="col-md-3" id="vehicletypeDiv">
													<label class="form-label" for="vehicle_type">Vehicle Type <span class=" text-danger"> *</span></label>
													<select id="vehicle_type" name="vehicle_type" class="form-select form-control" onchange="changeCosttype()" required>
														<option value=""> Choose Vehicle Type</option>
													</select>
												</div>
												<div class="col-md-9 d-flex align-items-end justify-content-end">
													<button class="btn btn-sm btn-label-success"><i class="ti ti-download me-2"></i>Export</button>
												</div>
											</div>
											<div class="row mt-4">
												<div class="col-md-12">
													<h5>Parking Price List</h5>
													<div class="card-body dataTable_select text-nowrap">
														<div class="text-nowrap table-responsive table-bordered">
															<table class="table table-hover" id="language_LIST">
																<thead>
																	<tr>
																		<th scope="col">S.No</th>
																		<th scope="col">Hotspot Name</th>
																		<th scope="col">Vehicle Type</th>
																		<th scope="col">Month</th>
																		<th scope="col">Year</th>
																		<th scope="col">Day 1</th>
																		<th scope="col">Day 2</th>
																		<th scope="col">Day 3</th>
																		<th scope="col">Day 4</th>
																		<th scope="col">Day 5</th>
																		<th scope="col">Day 6</th>
																		<th scope="col">Day 7</th>
																		<th scope="col">Day 8</th>
																		<th scope="col">Day 9</th>
																		<th scope="col">Day 10</th>
																		<th scope="col">Day 11</th>
																		<th scope="col">Day 12</th>
																		<th scope="col">Day 13</th>
																		<th scope="col">Day 14</th>
																		<th scope="col">Day 15</th>
																		<th scope="col">Day 16</th>
																		<th scope="col">Day 17</th>
																		<th scope="col">Day 18</th>
																		<th scope="col">Day 19</th>
																		<th scope="col">Day 20</th>
																		<th scope="col">Day 21</th>
																		<th scope="col">Day 22</th>
																		<th scope="col">Day 23</th>
																		<th scope="col">Day 24</th>
																		<th scope="col">Day 25</th>
																		<th scope="col">Day 26</th>
																		<th scope="col">Day 27</th>
																		<th scope="col">Day 28</th>
																		<th scope="col">Day 29</th>
																		<th scope="col">Day 30</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td>1.</td>
																		<td>Hogenakkal Waterfalls</td>
																		<td>Innova</td>
																		<td>November</td>
																		<td>2024</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																		<td>₹ 500</td>
																	</tr>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
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

	<!-- Core JS -->
	<!-- build:js assets/vendor/js/core.js -->

	<script src="assets/vendor/libs/jquery/jquery.js"></script>
	<script src="assets/vendor/libs/popper/popper.js"></script>
	<script src="assets/vendor/js/bootstrap.js"></script>
	<script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
	<script src="assets/vendor/libs/i18n/i18n.js"></script>
	<script src="assets/vendor/libs/typeahead-js/typeahead.js"></script>
	<script src="assets/vendor/js/menu.js"></script>
	<script src="assets/vendor/libs/tagify/tagify.js"></script>
	<script src="assets/js/forms-tagify.js"></script>
	<script src="assets/vendor/libs/toastr/toastr.js"></script>

	<!-- endbuild -->
	<script src="assets/vendor/libs/flatpickr/flatpickr.js"></script>
	<!-- Form Validation -->
	<script src="assets/js/code.jquery.com_jquery-3.7.0.js"></script>
	<script src="assets/js/parsley.min.js"></script>
	<script src="assets/js/custom-common-script.js"></script>
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
	<script src=" assets/vendor/libs/jquery-sticky/jquery-sticky.js"></script>
	<script src="assets/js/bootstrap-datepicker.js"></script>
	<script src="assets/js/bootstrap-datepicker.min.js"></script>
	<!-- Main JS -->
	<script src="assets/js/main.js"></script>


	<script>
		$(document).ready(function() {
			$('#hotel_year').datepicker({
				format: "yyyy",
				viewMode: "years",
				minViewMode: "years"
			});
			$('#amenities_year').datepicker({
				format: "yyyy",
				viewMode: "years",
				minViewMode: "years"
			});
			$('#vehicle_year').datepicker({
				format: "yyyy",
				viewMode: "years",
				minViewMode: "years"
			});
			$('#guide_year').datepicker({
				format: "yyyy",
				viewMode: "years",
				minViewMode: "years"
			});
			$('#activity_year').datepicker({
				format: "yyyy",
				viewMode: "years",
				minViewMode: "years"
			});
		});

		$(document).ready(function() {

			$("select").selectize();

			$('#year').datepicker({
				format: "yyyy",
				startView: "years",
				minViewMode: "years",
				autoclose: true
			}).on('changeDate', function(e) {
				// Use Moment.js to format the selected date
				const formattedDate = moment(e.date).format('YYYY');
				$(this).val(formattedDate);
			});
		});



		function CHOOSEN_STATE_ADD() {
			var city_selectize = $("#hotel_city")[0].selectize;
			var STATE_ID = $('#hotel_state').val();
			// Get the response from the server.
			$.ajax({
				url: 'engine/ajax/__ajax_fetch_state_n_city.php?type=selectize_state&STATE_ID=' + STATE_ID,
				type: "GET",
				success: function(response) {
					// Append the response to the dropdown.
					city_selectize.clear();
					city_selectize.clearOptions();
					city_selectize.addOption(response);
					<?php /*if ($vendor_branch_city) : ?>
                city_selectize.setValue('<?= $vendor_branch_city; ?>');
                <?php endif; */ ?>
				}
			});
		}

		function show_room_for_hotel(TYPE, ID) {
			$.ajax({
				type: 'post',
				url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
				data: {
					ID: ID,
					TYPE: TYPE
				},
				success: function(response) {
					$('#roomTypeFilterDiv').html(response);
				}
			});
		}



		//trigger hotel name through hotel category
		var hotelCategorySelectize = $('#hotel_category').selectize()[0].selectize;
		var hotelNameSelectize = $('#hotel_name').selectize()[0].selectize;
		// Listen for the change event on Selectize
		hotelCategorySelectize.on('change', function() {
			var hotelCategoryValue = hotelCategorySelectize.getValue();
			var hotelNameValue = hotelNameSelectize.getValue();
			var hotel_city = $('#hotel_city').val();
			console.log("Selected hotelCategory value: " + hotelCategoryValue);
			if (hotelCategoryValue !== '' && hotelCategoryValue !== '0') {
				show_category_for_the_hotel('select_hotel', hotelCategoryValue, hotel_city);
			}
		});

		function show_category_for_the_hotel(TYPE, HOTEL_CAT_ID, CITY_ID) {
			if (HOTEL_CAT_ID == '') {
				var HOTEL_CAT_ID = $('#hotel_category').val();
			}
			if (CITY_ID == '') {
				var CITY_ID = $('#hotel_city').val();
			}
			if (HOTEL_CAT_ID != "" && CITY_ID != "") {
				$.ajax({
					type: 'post',
					url: 'engine/ajax/__ajax_hotel_overall_pricebook.php',
					data: {
						ID: HOTEL_CAT_ID,
						TYPE: TYPE,
						CITY_ID: CITY_ID
					},
					success: function(response) {
						$('#hotelDiv').html(response);
					}
				});
			}
		}
	</script>

</body>

</html>