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


						<div class="nav-align-top my-2 p-0">
							<ul class="nav nav-pills" role="tablist">
								<li class="nav-item" role="presentation">
									<button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-itinerary1" aria-controls="navs-top-itinerary1" aria-selected="true">Route Itinerary
										1</button>
								</li>
							</ul>
							<div class="tab-pane fade active show" id="navs-top-itinerary1" role="tabpanel">
								<div class="row">
									<div class="col-12">
										<div class="card">
											<div class="card-body mt-3">

												<div class="divider">
													<div class="divider-text">
														<i class="ti ti-map-2 ti-sm text-primary"></i>
													</div>
												</div>

												<div class="hotel_list">

													<div class="row align-items-center justify-content-between mb-2">
														<div class="col-9">
															<h5 class="card-header p-0 text-primary mb-2">Hotel List</h5>
														</div>
													</div>

													<!-- Day 1 -->
													<div class="card border border-secondary p-3">
														<div class="d-flex align-items-center justify-content-between">
															<h6 class="mb-0">
																<span>DAY 1 - February 07, 2024</span>
																<span class="mx-1">|</span>
																<span class="text-primary me-1">
																	<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>Cochin, Kerala, India</span>
															</h6>
															<div class="hotel_label_1">
																<span class="text-primary">Hotel Needed for Stay - </span>
																<span class="text-primary me-1 fw-bolder">Yes </span>
															</div>
															<div class="d-none hotel_text_1">
																<div class="mb-3 row align-items-center">
																	<label for="html5-text-input" class="col-md-auto col-form-label text-primary py-0 pe-0">Hotel Needed for Stay - </label>
																	<div class="col-md-auto">
																		<select name="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control  form-select-sm" onchange="onchangeHOTELREQUIRED('<?= $itinerary_plan_hotel_details_ID ?>');">
																			<?= get_YES_R_NO('1', 'select') ?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="hotel_label_1">
															<div class="d-flex align-items-center justify-content-between mt-2 mb-3">
																<?php
																$hotel_rating = '4';
																?>
																<div class="">
																	<h5 class="mb-0"><b>TRAVANCORE COURT, Cochin - Lotus Club (3 STAR HOTELS)</b></h5>
																	<small class="mb-0 d-flex align-items-center">
																		<span class="badge me-1" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
																			<small>4 <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
																		</span>
																		Good - <a href="#" class="text-dark text-decoration-underline">10 reviews</a>
																	</small>
																</div>
																<button type="button" class="btn btn-sm btn-primary waves-effect" onclick="editITINERARYHOTELBYROW('1')">
																	<span class="ti-xs ti ti-edit me-1"></span>Edit
																</button>
															</div>
														</div>
														<div class="d-none hotel_text_1 mb-3">
															<div class="row justify-content-between align-items-end">
																<div class="col-md-auto row">
																	<div class="col-md-auto">
																		<label class="text-sm-end" for="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Category</label>
																		<select name="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control" onchange="onchangeHOTELCATEGORY('<?= $itinerary_plan_hotel_details_ID ?>','<?= $next_visiting_location_latitude ?>','<?= $next_visiting_location_longitude ?>');">
																			<?= getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'select') ?>
																		</select>
																	</div>
																	<div class="col-md-auto">
																		<label class="text-sm-end" for="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Name</label>
																		<select name="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" style="width: 300px;" autocomplete="off" class="form-control" onchange="onchangeHOTEL('<?= $itinerary_plan_hotel_details_ID ?>');">
																			<?= getNEARESTHOTELS('11.9416', '79.8083', $hotel_id); ?>
																		</select>
																	</div>

																</div>
																<div class="col-md-auto">
																	<button type="button" class="btn btn-primary waves-effect waves-light hotel_update_btn_1" onclick="updateITINERARYHOTELBYROW('1')">
																		<span class="ti-xs ti ti-check me-1"></span>Update
																	</button>
																</div>
															</div>
														</div>
														<div class="row align-items-center">
															<div class="col-9 border-end">

																<div class="row">
																	<div class="col-12">
																		<div class="hotel_label_1">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - Deluxe Room</span> <small class="mb-0">(AC Available)</small></h6>
																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults, 1 Children, 1 Infant</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 3,000</h5>
																					<small>+ <?= $global_currency_format; ?> 125 Taxes & Charges</small>
																				</div>
																			</div>

																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="d-flex mx-auto rounded cursor-pointer" src="uploads/room_gallery/DVIR3WR00002_1.jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter1" width="150" height="125" />
																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<p class="mb-0 fw-bolder">1</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch, Dinner</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="modal fade" id="modalCenter1" tabindex="-1" aria-hidden="true">
																			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="modalCenterTitle">TRAVANCORE COURT, Cochin - Lotus Club (3 STAR HOTELS) - Deluxe Room</h5>
																						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																					</div>
																					<div class="modal-body">

																						<div id="swiper-gallery">
																							<div class="swiper gallery-top">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIR3WR00002_1.jpg)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																								<!-- Add Arrows -->
																								<div class="swiper-button-next swiper-button-white"></div>
																								<div class="swiper-button-prev swiper-button-white"></div>
																							</div>
																							<div class="swiper gallery-thumbs">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIR3WR00002_1.jpg)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="d-none hotel_text_1">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<div class="row align-items-center">
																						<label for="html5-text-input" class="col-md-auto col-form-label py-0 pe-0 text-primary fw-bolder"><i class="ti ti-bed-filled text-primary me-1"></i>Room 1 - Type</label>
																						<div class="col-md-auto">
																							<select name="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" autocomplete="off" class="form-control  form-select-sm hotel_room_<?= $itinerary_plan_hotel_details_ID ?>" onchange="selectROOMDETAILS('<?= $itinerary_plan_hotel_details_ID ?>','<?= $counter ?>','<?= $itinerary_route_date ?>');">
																								<?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, 'select') ?>
																							</select>
																						</div>
																						<div class="col-md-auto px-0"> <small class="mb-0">(AC Available)</small></div>
																					</div>

																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults, 2 Children, 1 Infant</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 3,000</h5>
																					<small>+ <?= $global_currency_format; ?> 125 Taxes & Charges</small>
																				</div>
																			</div>


																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="w-px-150 d-flex mx-auto rounded" src="uploads/room_gallery/DVIR3WR00002_1.jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter1" width="150" height="125">
																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<div class="input-group input_group_plus_minus">
																							<input type="button" value="-" id="input_minus_button" class="button-minus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																							<input type="number" step="1" min="0" value="1" required="" data-parsley-errors-container="#number_of_routes_error" name="number_of_routes" class="input_plus_minus quantity-field  h-px-30">
																							<input type="button" value="+" id="input_plus_button" class="button-plus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																						</div>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch, Dinner</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-12">
																		<hr class="my-3">
																	</div>

																	<div class="col-12">
																		<div class="hotel_label_1">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - SECRET GARDEN</span> <small class="mb-0">(AC Available)</small></h6>
																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 1,500</h5>
																					<small>+ <?= $global_currency_format; ?> 75 Taxes & Charges</small>
																				</div>
																			</div>

																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="uploads/room_gallery/DVIRLUX00001_2.png" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter2" width="150" height="125" />

																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<p class="mb-0 fw-bolder">0</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="modal fade" id="modalCenter2" tabindex="-1" aria-hidden="true">
																			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="modalCenterTitle">TRAVANCORE COURT, Cochin - Lotus Club (3 STAR HOTELS) - SECRET GARDEN</h5>
																						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																					</div>
																					<div class="modal-body">

																						<div id="swiper-gallery">
																							<div class="swiper gallery-top">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.png)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																								<!-- Add Arrows -->
																								<div class="swiper-button-next swiper-button-white"></div>
																								<div class="swiper-button-prev swiper-button-white"></div>
																							</div>
																							<div class="swiper gallery-thumbs">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.png)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="d-none hotel_text_1">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<div class="row align-items-center">
																						<label for="html5-text-input" class="col-md-auto col-form-label py-0 pe-0 text-primary fw-bolder"><i class="ti ti-bed-filled text-primary me-1"></i>Room 1 - Type</label>
																						<div class="col-md-auto">
																							<select name="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" autocomplete="off" class="form-control  form-select-sm hotel_room_<?= $itinerary_plan_hotel_details_ID ?>" onchange="selectROOMDETAILS('<?= $itinerary_plan_hotel_details_ID ?>','<?= $counter ?>','<?= $itinerary_route_date ?>');">
																								<?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, 'select') ?>
																							</select>
																						</div>
																						<div class="col-md-auto px-0"> <small class="mb-0">(AC Available)</small></div>
																					</div>

																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 1,500</h5>
																					<small>+ <?= $global_currency_format; ?> 75 Taxes & Charges</small>
																				</div>
																			</div>


																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="w-px-150 d-flex mx-auto rounded" src="uploads/room_gallery/DVIRLUX00001_2.png" alt="" data-bs-toggle="modal" data-bs-target="#modalCenter2" width="150" height="125">
																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<div class="input-group input_group_plus_minus">
																							<input type="button" value="-" id="input_minus_button" class="button-minus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																							<input type="number" step="1" min="0" value="0" required="" data-parsley-errors-container="#number_of_routes_error" name="number_of_routes" class="input_plus_minus quantity-field  h-px-30">
																							<input type="button" value="+" id="input_plus_button" class="button-plus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																						</div>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-3">
																<div class=" my-auto">
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Cost</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 4,500</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Taxes</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 200</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<h5 class="mb-0">Grand Total</h5>
																		<h5 class="mb-0 text-primary fw-bolder"><?= $global_currency_format; ?> 4,700</h5>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- Day 1 -->

													<!-- Day 2 -->
													<div class="card border border-secondary p-3 mt-2">
														<div class="d-flex align-items-center justify-content-between">
															<h6 class="mb-0">
																<span>DAY 2 - February 08, 2024</span>
																<span class="mx-1">|</span>
																<span class="text-primary me-1">
																	<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>Alleppey, Kerala, India</span>
															</h6>
															<div class="hotel_label_2">
																<span class="text-primary">Hotel Needed for Stay - </span>
																<span class="text-primary me-1 fw-bolder">Yes </span>
															</div>
															<div class="d-none hotel_text_2">
																<div class="mb-3 row align-items-center">
																	<label for="html5-text-input" class="col-md-auto col-form-label text-primary py-0 pe-0">Hotel Needed for Stay - </label>
																	<div class="col-md-auto">
																		<select name="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_required_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control  form-select-sm" onchange="onchangeHOTELREQUIRED('<?= $itinerary_plan_hotel_details_ID ?>');">
																			<?= get_YES_R_NO('1', 'select') ?>
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="hotel_label_2">
															<div class="d-flex align-items-center justify-content-between mt-2 mb-3">
																<div class="">
																	<h5 class="mb-0"><b>PAGODA, Alleppey - Mullakkal (3 STAR HOTELS)</b></h5>
																	<small class="mb-0 d-flex align-items-center">
																		<span class="badge me-1" style="color: #fff; background-color: #c33ca6; -webkit-text-fill-color: white;">
																			<small>4 <i class="ti ti-star-filled ti-xs" style="font-size: 0.8rem !important;margin-top: -3px;"></i></small>
																		</span>
																		Good - <a href="#" class="text-dark text-decoration-underline">11 reviews</a>
																	</small>
																</div>

																<button type="button" class="btn btn-sm btn-primary waves-effect" onclick="editITINERARYHOTELBYROW('2')">
																	<span class="ti-xs ti ti-edit me-1"></span>Edit
																</button>
															</div>
														</div>
														<div class="d-none hotel_text_2 mb-3">
															<div class="row justify-content-between align-items-end">
																<div class="col-md-auto row">
																	<div class="col-md-auto">
																		<label class="text-sm-end" for="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Category</label>
																		<select name="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_category_<?= $itinerary_plan_hotel_details_ID ?>" autocomplete="off" class="form-control" onchange="onchangeHOTELCATEGORY('<?= $itinerary_plan_hotel_details_ID ?>','<?= $next_visiting_location_latitude ?>','<?= $next_visiting_location_longitude ?>');">
																			<?= getHOTEL_CATEGORY_DETAILS($fetch_hotel_data['hotel_category_id'], 'select') ?>
																		</select>
																	</div>
																	<div class="col-md-auto">
																		<label class="text-sm-end" for="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>">Hotel Name</label>
																		<select name="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" id="hotel_name_<?= $itinerary_plan_hotel_details_ID ?>" style="width: 300px;" autocomplete="off" class="form-control" onchange="onchangeHOTEL('<?= $itinerary_plan_hotel_details_ID ?>');">
																			<?= getNEARESTHOTELS('11.9416', '79.8083', $hotel_id); ?>
																		</select>
																	</div>

																</div>
																<div class="col-md-auto">
																	<button type="button" class="btn btn-primary waves-effect waves-light hotel_update_btn_1" onclick="updateITINERARYHOTELBYROW('2')">
																		<span class="ti-xs ti ti-check me-1"></span>Update
																	</button>
																</div>
															</div>
														</div>
														<div class="row align-items-center">
															<div class="col-9 border-end">

																<div class="row">
																	<div class="col-12">
																		<div class="hotel_label_2">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 1 - Cottage</span> <small class="mb-0">(AC Available)</small></h6>
																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults, 1 Children, 1 Infant</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 3,000</h5>
																					<small>+ <?= $global_currency_format; ?> 125 Taxes & Charges</small>
																				</div>
																			</div>

																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="d-flex mx-auto rounded cursor-pointer" src="uploads/room_gallery/DVIRLUX00001_3.jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter3" width="150" height="125" />
																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<p class="mb-0 fw-bolder">1</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch, Dinner</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="modal fade" id="modalCenter3" tabindex="-1" aria-hidden="true">
																			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="modalCenterTitle">PAGODA, Alleppey - Mullakkal (3 STAR HOTELS) - Cottage</h5>
																						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																					</div>
																					<div class="modal-body">

																						<div id="swiper-gallery">
																							<div class="swiper gallery-top">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_3.jpg)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																								<!-- Add Arrows -->
																								<div class="swiper-button-next swiper-button-white"></div>
																								<div class="swiper-button-prev swiper-button-white"></div>
																							</div>
																							<div class="swiper gallery-thumbs">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_3.jpg)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="d-none hotel_text_2">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<div class="row align-items-center">
																						<label for="html5-text-input" class="col-md-auto col-form-label py-0 pe-0 text-primary fw-bolder"><i class="ti ti-bed-filled text-primary me-1"></i>Room 1 - Type</label>
																						<div class="col-md-auto">
																							<select name="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" autocomplete="off" class="form-control  form-select-sm hotel_room_<?= $itinerary_plan_hotel_details_ID ?>" onchange="selectROOMDETAILS('<?= $itinerary_plan_hotel_details_ID ?>','<?= $counter ?>','<?= $itinerary_route_date ?>');">
																								<?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, 'select') ?>
																							</select>
																						</div>
																						<div class="col-md-auto px-0"> <small class="mb-0">(AC Available)</small></div>
																					</div>

																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults, 2 Children, 1 Infant</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 3,000</h5>
																					<small>+ <?= $global_currency_format; ?> 125 Taxes & Charges</small>
																				</div>
																			</div>


																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="w-px-150 d-flex mx-auto rounded" src="uploads/room_gallery/DVIRLUX00001_3.jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter3" width="150" height="125">
																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<div class="input-group input_group_plus_minus">
																							<input type="button" value="-" id="input_minus_button" class="button-minus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																							<input type="number" step="1" min="0" value="1" required="" data-parsley-errors-container="#number_of_routes_error" name="number_of_routes" class="input_plus_minus quantity-field  h-px-30">
																							<input type="button" value="+" id="input_plus_button" class="button-plus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																						</div>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch, Dinner</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																	<div class="col-12">
																		<hr class="my-3">
																	</div>

																	<div class="col-12">
																		<div class="hotel_label_2">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<h6 class="mb-0 fw-bolder"><span class="text-primary"><i class="ti ti-bed-filled me-1"></i> Room 2 - Super Deluxe</span> <small class="mb-0">(AC Available)</small></h6>
																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 1,500</h5>
																					<small>+ <?= $global_currency_format; ?> 75 Taxes & Charges</small>
																				</div>
																			</div>

																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="uploads/room_gallery/DVIRLUX00001_3.jpeg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter4" width="150" height="125" />

																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<p class="mb-0 fw-bolder">0</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="modal fade" id="modalCenter4" tabindex="-1" aria-hidden="true">
																			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
																				<div class="modal-content">
																					<div class="modal-header">
																						<h5 class="modal-title" id="modalCenterTitle">PAGODA, Alleppey - Mullakkal (3 STAR HOTELS) - Super Deluxe</h5>
																						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
																					</div>
																					<div class="modal-body">

																						<div id="swiper-gallery">
																							<div class="swiper gallery-top">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_3.jpeg)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																								<!-- Add Arrows -->
																								<div class="swiper-button-next swiper-button-white"></div>
																								<div class="swiper-button-prev swiper-button-white"></div>
																							</div>
																							<div class="swiper gallery-thumbs">
																								<div class="swiper-wrapper">
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_3.jpeg)">Slide 1</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRCOT124380_1.png)">Slide 2</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRHER541797_1.jpeg)">Slide 3</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_1.jpg)">Slide 4</div>
																									<div class="swiper-slide" style="background-image:url(uploads/room_gallery/DVIRLUX00001_2.jpeg)">Slide 5</div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

																		<div class="d-none hotel_text_2">

																			<div class="row justify-content-between mb-2">
																				<div class="col-9">
																					<div class="row align-items-center">
																						<label for="html5-text-input" class="col-md-auto col-form-label py-0 pe-0 text-primary fw-bolder"><i class="ti ti-bed-filled text-primary me-1"></i>Room 1 - Type</label>
																						<div class="col-md-auto">
																							<select name="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>[]" id="hotel_roomtype_<?= $itinerary_plan_hotel_details_ID ?>_<?= $counter ?>" autocomplete="off" class="form-control  form-select-sm hotel_room_<?= $itinerary_plan_hotel_details_ID ?>" onchange="selectROOMDETAILS('<?= $itinerary_plan_hotel_details_ID ?>','<?= $counter ?>','<?= $itinerary_route_date ?>');">
																								<?= getHOTEL_ROOM_TYPE_DETAIL($hotel_id, $room_type_id, 'select') ?>
																							</select>
																						</div>
																						<div class="col-md-auto px-0"> <small class="mb-0">(AC Available)</small></div>
																					</div>

																					<small><i class="ti ti-users ti-xs me-1"></i>2 Adults</small>
																				</div>
																				<div class="col-3 text-primary mb-0 text-end">
																					<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 1,500</h5>
																					<small>+ <?= $global_currency_format; ?> 75 Taxes & Charges</small>
																				</div>
																			</div>


																			<div class="row mb-2">
																				<div class="col-3">
																					<img class="w-px-150 d-flex mx-auto rounded" src="uploads/room_gallery/DVIRLUX00001_3.jpeg" alt="" data-bs-toggle="modal" data-bs-target="#modalCenter4" width="150" height="125">
																				</div>
																				<div class="col-9 row">
																					<div class="col-4">
																						<small class="mb-0">Check-In Time</small>
																						<p class="mb-0 fw-bolder">9:00 AM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Check-Out Time</small>
																						<p class="mb-0 fw-bolder">9:00 PM</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra Bed(<?= $global_currency_format; ?> 500 Per)</small>
																						<div class="input-group input_group_plus_minus">
																							<input type="button" value="-" id="input_minus_button" class="button-minus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																							<input type="number" step="1" min="0" value="0" required="" data-parsley-errors-container="#number_of_routes_error" name="number_of_routes" class="input_plus_minus quantity-field  h-px-30">
																							<input type="button" value="+" id="input_plus_button" class="button-plus  h-px-30" data-field="number_of_routes" data-id="no_of_routes">
																						</div>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Food</small>
																						<p class="mb-0 fw-bolder">Breakfast, Lunch</p>
																					</div>
																					<div class="col-6">
																						<small class="mb-0">Inbuilt Amenities</small>
																						<p class="mb-0 fw-bolder">Free Internet, Room Services</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-3">
																<div class=" my-auto">
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Cost</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 4,500</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Taxes</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 200</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<h5 class="mb-0">Grand Total</h5>
																		<h5 class="mb-0 text-primary fw-bolder"><?= $global_currency_format; ?> 4,700</h5>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- Day 2 -->


													<div class="card border border-primary p-0 mt-3">
														<div class="p-3 pb-0">
															<h5 class="card-header p-0 mb-3">Hotel Summary</h5>
															<div class="order-calculations">
																<table class="table mb-2">
																	<tbody class="table-border-bottom-0" style="border: 1px solid #fff;">
																		<tr>
																			<td class="px-0 align-top" style="    border-color: #ce3db0;">
																				<h6 class="my-2">
																					<span>DAY 1 - February 07, 2024</span>
																				</h6>

																				<div class="text-heading text-primary fw-bold my-2">TRAVANCORE COURT (2 Rooms)
																				</div>

																				<div class="me-1 my-2">
																					<i class="ti ti-location-filled ti-xs me-1 mb-1 text-primary"></i>Cochin, Kerala, India
																				</div>

																				<div class="me-1 my-2">
																					<i class="ti ti-star-filled ti-xs me-1 mb-1 text-primary"></i>3 STAR HOTELS
																				</div>
																			</td>
																			<td class="align-top" style="border-left: 1px solid #ce3db0; border-right: 1px solid #ce3db0; border-color: #ce3db0;">
																				<div class="mx-3">
																					<div class="row justify-content-between  mb-2">
																						<div class="col-9">
																							<h6 class="mb-0"><span class="text-primary"><i class="ti ti-bed me-1"></i> Room 1 - Deluxe</span> <small class="mb-0">(AC Available)</small></h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-clock ti-xs me-1 mb-1"></i>
																								<span>Check In - 9:00 AM</span>,
																								<span>Check Out - 9:00 PM</span>
																							</h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-tools-kitchen-2 ti-xs me-1 mb-1"></i>
																								<span>Breakfast, Lunch, Dinner</span>
																							</h6>

																						</div>
																						<div class="col-3 mb-0 text-end">
																							<h6 class="mb-0 lh-1">
																								₹ 3,000
																								<br />
																								<small>(+ ₹ 125 Taxes)</small>
																							</h6>
																						</div>
																					</div>
																				</div>
																				<hr class="my-2" />
																				<div class="mx-3">
																					<div class="row justify-content-between  mb-2">
																						<div class="col-9">
																							<h6 class="mb-0"><span class="text-primary"><i class="ti ti-bed me-1"></i> Room 2 - SECRET GARDEN</span> <small class="mb-0">(AC Available)</small></h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-clock ti-xs me-1 mb-1"></i>
																								<span>Check In - 9:00 AM</span>,
																								<span>Check Out - 9:00 PM</span>
																							</h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-tools-kitchen-2 ti-xs me-1 mb-1"></i>
																								<span>Breakfast, Lunch</span>
																							</h6>

																						</div>
																						<div class="col-3 mb-0 text-end">
																							<h6 class="mb-0 lh-1">
																								₹ 1,500
																								<br />
																								<small>(+ ₹ 75 Taxes)</small>
																							</h6>
																						</div>
																					</div>
																				</div>
																			</td>
																			<td class="px-0 text-end align-top" style="    border-color: #ce3db0;">
																				<h6 class="mb-0"><?= $global_currency_format; ?> 4,500</h6>
																				<small>(+ <?= $global_currency_format; ?> 200 Taxes)</small>
																			</td>
																		</tr>
																		<tr>
																			<td class="px-0 align-top" style="    border-color: #ce3db0;">
																				<h6 class="my-2">
																					<span>DAY 2 - February 08, 2024</span>
																				</h6>

																				<div class="text-heading text-primary fw-bold my-2">PAGODA (2 Rooms)
																				</div>

																				<div class="me-1 my-2">
																					<i class="ti ti-location-filled ti-xs me-1 mb-1 text-primary"></i>Alleppey, Kerala, India
																				</div>

																				<div class="me-1 my-2">
																					<i class="ti ti-star-filled ti-xs me-1 mb-1 text-primary"></i>3 STAR HOTELS
																				</div>
																			</td>
																			<td class="align-top" style="border-left: 1px solid #ce3db0; border-right: 1px solid #ce3db0; border-color: #ce3db0;">
																				<div class="mx-3">
																					<div class="row justify-content-between  mb-2">
																						<div class="col-9">
																							<h6 class="mb-0"><span class="text-primary"><i class="ti ti-bed me-1"></i> Room 1 - Deluxe</span> <small class="mb-0">(AC Available)</small></h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-clock ti-xs me-1 mb-1"></i>
																								<span>Check In - 9:00 AM</span>,
																								<span>Check Out - 9:00 PM</span>
																							</h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-tools-kitchen-2 ti-xs me-1 mb-1"></i>
																								<span>Breakfast, Lunch, Dinner</span>
																							</h6>

																						</div>
																						<div class="col-3 mb-0 text-end">
																							<h6 class="mb-0 lh-1">
																								₹ 3,000
																								<br />
																								<small>(+ ₹ 125 Taxes)</small>
																							</h6>
																						</div>
																					</div>
																				</div>
																				<hr class="my-2" />
																				<div class="mx-3">
																					<div class="row justify-content-between  mb-2">
																						<div class="col-9">
																							<h6 class="mb-0"><span class="text-primary"><i class="ti ti-bed me-1"></i> Room 2 - SECRET GARDEN</span> <small class="mb-0">(AC Available)</small></h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-clock ti-xs me-1 mb-1"></i>
																								<span>Check In - 9:00 AM</span>,
																								<span>Check Out - 9:00 PM</span>
																							</h6>
																							<h6 class="mb-0"><i class="text-primary ti ti-tools-kitchen-2 ti-xs me-1 mb-1"></i>
																								<span>Breakfast, Lunch</span>
																							</h6>

																						</div>
																						<div class="col-3 mb-0 text-end">
																							<h6 class="mb-0 lh-1">
																								₹ 1,500
																								<br />
																								<small>(+ ₹ 75 Taxes)</small>
																							</h6>
																						</div>
																					</div>
																				</div>
																			</td>
																			<td class="px-0 text-end align-top" style="    border-color: #ce3db0;">
																				<h6 class="mb-0"><?= $global_currency_format; ?> 4,500</h6>
																				<small>(+ <?= $global_currency_format; ?> 200 Taxes)</small>
																			</td>
																		</tr>
																	</tbody>
																</table>

																<hr style="color: #e865cf; border-top: 2px dashed;" class="text-primary" />

																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total Hotel Cost</span>
																	<h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_cost">9,000
																		</span></h6>
																</div>
																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total Hotel Taxes</span>
																	<h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_taxes">400
																		</span></h6>
																</div>
															</div>
														</div>
														<div class="d-flex justify-content-between  px-3 py-3" style="background-color: #f2f2f2;">
															<h5 class="text-heading fw-bold mb-0">Grand Hotel Total </h5>
															<h5 class="mb-0 fw-bold  text-primary"><?= $global_currency_format; ?> <span id="overall_hotel_cost">9,400
																</span></h5>
														</div>
													</div>
												</div>

												<div class="divider mt-5 mb-4">
													<div class="divider-text">
														<i class="ti ti-map-2 ti-sm text-primary"></i>
													</div>
												</div>

												<div class="vehicle_list">

													<div class="row align-items-center justify-content-between mb-2">
														<div class="col-md-auto">
															<h5 class="card-header p-0 text-primary mb-2">Vehicle List</h5>
														</div>
														<div class="col-md-auto">
															<div class="mb-3 row">
																<label for="html5-text-input" class="col-md-auto col-form-label pe-0">Filter</label>
																<div class="col-md-auto">
																	<select name="vendor_name" id="vendor_name" autocomplete="off" class="form-control form-select w-px-200">
																		<?= getVENDOR_DETAILS($fetch_hotel_data['hotel_category_id'], 'select') ?>
																	</select>
																</div>
																<div class="col-md-auto">
																	<select name="vendor_branch" id="vendor_branch" autocomplete="off" class="form-control form-select w-px-200">
																		<option value="">Choose Branch</option>
																	</select>
																</div>
															</div>
														</div>
													</div>

													<!-- Day 1 -->
													<div class="card border border-secondary p-3">
														<div class="d-flex align-items-center justify-content-between">
															<h6 class="mb-0">
																<span>DAY 1 - February 07, 2024</span>
															</h6>
															<h6 class="mb-0">
																<span class="text-primary me-1">
																	<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>Cochin, Kerala, India</span>
															</h6>
															<h6 class="mb-0">
																<span class="text-primary me-1">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>8 Hours 28 Minutes</span>
															</h6>
														</div>

														<hr />

														<div class="row justify-content-center">
															<div class="col-4">
																<small class="mb-0">Travel Distance & Time</small>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>1.0 KM
																</p>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>10 Minutes
																</p>
															</div>
															<div class="col-4 text-center">
																<small class="mb-0">Sight-seeing Distance & Time</small>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>23.8 KM
																</p>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>8 Hours 18 Minutes
																</p>
															</div>
															<div class="col-4 text-end">
																<small class="mb-0 text-primary">Total Distance & Time</small>
																<p class="mb-0 text-primary fw-bolder">
																	<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>24.8 KM
																</p>
																<p class="mb-0 text-primary fw-bolder">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>8 Hours 28 Minutes
																</p>
															</div>
														</div>

														<hr />

														<div class="row align-items-center">
															<div class="col-9 border-end">
																<div class="row">
																	<div class="col-12">
																		<div class="row justify-content-between mb-2">
																			<div class="col-md-auto">
																				<h5 class="mb-0 fw-bolder d-flex align-items-center">
																					#1 - <b class="text-primary">Sedan</b>
																					<span class="badge rounded-pill bg-label-primary mx-2" style="font-size: 11px;">COUNT 1</span>
																				</h5>
																				<h6 class="mb-0">
																					<i class="text-primary ti ti-users-group me-1"></i>
																					Max Occupancy: 5
																				</h6>
																			</div>
																			<div class="col-md-auto text-primary mb-0 text-end">
																				<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 8,150</h5>
																				<small>+ <?= $global_currency_format; ?> 125 Taxes &amp; Charges</small>
																			</div>
																		</div>
																		<div class="row mb-2">
																			<div class="col-3">
																				<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="uploads/vehicle_gallery/DVIV-959105-136191704778785-exterior (1).jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter4" width="150" height="110">

																			</div>
																			<div class="col-9">
																				<div class="row g-3">
																					<div class="col-4">
																						<small class="mb-0">Per km rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder">19</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Max allowed kms</small>
																						<p class="mb-0 fw-bolder text-success">200</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra kms</small>
																						<p class="mb-0 fw-bolder">0</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Per day rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">6,000</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Charge for extra kms (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">0</span></p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Permit charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">0</p>
																					</div>
																				</div>
																			</div>
																			<div class="col-12 mt-3">
																				<div class="row g-3">
																					<div class="col-3">
																						<small class="mb-0">Driver Bhatta (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">1,500</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Driver Food Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">100</span>
																						<p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Accomdation Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">350</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Extra Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">100</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Toll Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Parking Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-12">
																		<hr class="mt-0" />
																	</div>

																	<div class="col-12">
																		<div class="row justify-content-between mb-2">
																			<div class="col-md-auto">
																				<h5 class="mb-0 fw-bolder d-flex align-items-center">
																					#2 - <b class="text-primary">Innova A/C </b>
																					<span class="badge rounded-pill bg-label-primary mx-2" style="font-size: 11px;">COUNT 1</span>
																				</h5>
																				<h6 class="mb-0">
																					<i class="text-primary ti ti-users-group me-1"></i>
																					Max Occupancy: 7
																				</h6>
																			</div>
																			<div class="col-md-auto text-primary mb-0 text-end">
																				<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 10,650</h5>
																				<small>+ <?= $global_currency_format; ?> 100 Taxes &amp; Charges</small>
																			</div>
																		</div>
																		<div class="row mb-2">
																			<div class="col-3">
																				<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="uploads/vehicle_gallery/interior (1).jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter4" width="150" height="110">

																			</div>
																			<div class="col-9">
																				<div class="row g-3">
																					<div class="col-4">
																						<small class="mb-0">Per km rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder">30</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Max allowed kms</small>
																						<p class="mb-0 fw-bolder text-success">250</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra kms</small>
																						<p class="mb-0 fw-bolder">0</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Per day rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">8,000</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Charge for extra kms (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">0</span>
																						<p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Permit charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">0</p>
																					</div>
																				</div>
																			</div>
																			<div class="col-12 mt-3">
																				<div class="row g-3">
																					<div class="col-3">
																						<small class="mb-0">Driver Bhatta (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">2,000</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Driver Food Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">100</span>
																						<p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Accomdation Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">350</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Extra Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">100</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Toll Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Parking Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-3">
																<div class=" my-auto">
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Cost</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 18,800</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Taxes</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 225</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<h5 class="mb-0">Grand Total</h5>
																		<h5 class="mb-0 text-primary fw-bolder"><?= $global_currency_format; ?> 19,025</h5>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- Day 1 -->



													<!-- Day 2 -->
													<div class="card border border-secondary p-3 mt-2">
														<div class="d-flex align-items-center justify-content-between">
															<h6 class="mb-0">
																<span>DAY 2 - February 08, 2024</span>
															</h6>
															<h6 class="mb-0">
																<span class="text-primary me-1">
																	<i class="ti ti-location-filled ti-xs text-primary me-1 mb-1"></i>Cochin, Kerala, India To Bangalore, Karnataka, India</span>
															</h6>
															<h6 class="mb-0">
																<span class="text-primary me-1">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>11 Hours 25 Minutes</span>
															</h6>
														</div>

														<hr />

														<div class="row justify-content-center">
															<div class="col-4">
																<small class="mb-0">Travel Distance & Time</small>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>548 KM
																</p>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>10 Hours 14 Minutes
																</p>
															</div>
															<div class="col-4 text-center">
																<small class="mb-0">Sight-seeing Distance & Time</small>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>30 KM
																</p>
																<p class="mb-0 fw-bolder">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>1 Hours 11 Minutes
																</p>
															</div>
															<div class="col-4 text-end">
																<small class="mb-0 text-primary">Total Distance & Time</small>
																<p class="mb-0 text-primary fw-bolder">
																	<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>578 KM
																</p>
																<p class="mb-0 text-primary fw-bolder">
																	<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>11 Hours 25 Minutes
																</p>
															</div>
														</div>

														<hr />

														<div class="row align-items-center">
															<div class="col-9 border-end">
																<div class="row">
																	<div class="col-12 text-center text-warning">
																		<span class="mb-0">Permit States - </span>
																		<span class="mb-0 fw-bolder">Karnataka</span>
																	</div>

																	<div class="col-12">
																		<hr />
																	</div>

																	<div class="col-12">
																		<div class="row justify-content-between mb-2">
																			<div class="col-md-auto">
																				<h5 class="mb-0 fw-bolder d-flex align-items-center">
																					#1 - <b class="text-primary">Sedan</b>
																					<span class="badge rounded-pill bg-label-primary mx-2" style="font-size: 11px;">COUNT 1</span>
																				</h5>
																				<h6 class="mb-0">
																					<i class="text-primary ti ti-users-group me-1"></i>
																					Max Occupancy: 5
																				</h6>
																			</div>
																			<div class="col-md-auto text-primary mb-0 text-end">
																				<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 15,832</h5>
																				<small>+ <?= $global_currency_format; ?> 350 Taxes &amp; Charges</small>
																			</div>
																		</div>
																		<div class="row mb-2">
																			<div class="col-3">
																				<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="uploads/vehicle_gallery/DVIV-959105-136191704778785-exterior (1).jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter4" width="150" height="110">

																			</div>
																			<div class="col-9">
																				<div class="row g-3">
																					<div class="col-4">
																						<small class="mb-0">Per km rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder">19</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Max allowed kms</small>
																						<p class="mb-0 fw-bolder text-danger">200</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra kms</small>
																						<p class="mb-0 fw-bolder">378</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Per day rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">6,000</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Charge for extra kms (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">7,182</span> (19 * 378)</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Permit charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">500</p>
																					</div>
																				</div>
																			</div>
																			<div class="col-12 mt-3">
																				<div class="row">
																					<div class="col-3">
																						<small class="mb-0">Driver Bhatta (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">1,500</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Driver Food Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">100</span>
																						<p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Accomdation Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">350</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Extra Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">100</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Toll Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Parking Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>

																	<div class="col-12">
																		<hr class="" />
																	</div>

																	<div class="col-12">
																		<div class="row justify-content-between mb-2">
																			<div class="col-md-auto">
																				<h5 class="mb-0 fw-bolder d-flex align-items-center">
																					#2 - <b class="text-primary">Innova A/C </b>
																					<span class="badge rounded-pill bg-label-primary mx-2" style="font-size: 11px;">COUNT 1</span>
																				</h5>
																				<h6 class="mb-0">
																					<i class="text-primary ti ti-users-group me-1"></i>
																					Max Occupancy: 7
																				</h6>
																			</div>
																			<div class="col-md-auto text-primary mb-0 text-end">
																				<h5 class="mb-0 lh-1"><?= $global_currency_format; ?> 20,990</h5>
																				<small>+ <?= $global_currency_format; ?> 240 Taxes &amp; Charges</small>
																			</div>
																		</div>
																		<div class="row mb-2">
																			<div class="col-3">
																				<img class="w-px-150 d-flex mx-auto rounded cursor-pointer" src="uploads/vehicle_gallery/interior (1).jpg" alt="Card image cap" data-bs-toggle="modal" data-bs-target="#modalCenter4" width="150" height="110">

																			</div>
																			<div class="col-9">
																				<div class="row g-3">
																					<div class="col-4">
																						<small class="mb-0">Per km rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder">30</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Max allowed kms</small>
																						<p class="mb-0 fw-bolder text-danger">250</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Extra kms</small>
																						<p class="mb-0 fw-bolder">328</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Per day rental (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">8,000</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Charge for extra kms (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">9,840</span> (30 * 328)</p>
																					</div>
																					<div class="col-4">
																						<small class="mb-0">Permit charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">500</p>
																					</div>
																				</div>
																			</div>
																			<div class="col-12 mt-3">
																				<div class="row">
																					<div class="col-3">
																						<small class="mb-0">Driver Bhatta (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">2,000</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Driver Food Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder"><span class="text-primary">100</span>
																						<p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Accomdation Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">350</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Extra Cost (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">100</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Toll Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																					<div class="col-3">
																						<small class="mb-0">Parking Charge (<?= $global_currency_format; ?>)</small>
																						<p class="mb-0 fw-bolder text-primary">50</p>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="col-3">
																<div class=" my-auto">
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Cost</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 36,822</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<p class="mb-0">Total Taxes</p>
																		<p class="mb-0"><?= $global_currency_format; ?> 590</p>
																	</div>
																	<div class="d-flex align-items-center justify-content-between my-3">
																		<h5 class="mb-0">Grand Total</h5>
																		<h5 class="mb-0 text-primary fw-bolder"><?= $global_currency_format; ?> 37,412</h5>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- Day 2 -->


													<div class="card border border-primary p-0 mt-3">
														<div class="p-3 pb-0">
															<div class="row justify-content-between align-items-center mb-3">
																<div class="col-md-auto">
																	<h5 class="card-header p-0 mb-0">Vehicle Summary</h5>
																</div>
																<div class="col-md-auto text-end">
																	<h6 class="mb-0">
																		<span class="text-primary me-1">
																			<i class="ti ti-road ti-xs text-primary me-1 mb-1"></i>Total Distance - 602.8 KM</span>
																	</h6>
																	<h6 class="mb-0">
																		<span class="text-primary me-1">
																			<i class="ti ti-clock ti-xs text-primary me-1 mb-1"></i>19 Hours 53 Minutes</span>
																	</h6>
																</div>
															</div>
															<div class="order-calculations">
																<div>
																	<table class="table table-borderless">
																		<thead>
																			<tr>
																				<th>Vehicle</th>
																				<th>Total Rental</th>
																				<th>Total KM Charges</th>
																				<th>Total Permit Charges</th>
																				<th>Other Charges</th>
																				<th>Cost with tax</th>
																			</tr>
																		</thead>
																		<tbody>
																			<tr>
																				<td>
																					<span class="text-primary">Seden</span>
																					<span class="badge rounded-pill bg-label-primary">COUNT 1</span>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 12,000</h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 7,182</h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 500</h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 4,300<br /><small>(<?= $global_currency_format; ?> 4,100 Driver Cost + <?= $global_currency_format; ?> 100 Toll Charge + <?= $global_currency_format; ?> 100 Parking Charge)</small></h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 23,982 <br /><small>(+ <?= $global_currency_format; ?> 475 Taxes)</small></h6>
																				</td>
																			</tr>
																			<tr>
																				<td>
																					<span class="text-primary">Innova A/C</span>
																					<span class="badge rounded-pill bg-label-primary">COUNT 1</span>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 16,000</h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 9,840</h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 500</h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 2,750<br /><small>(<?= $global_currency_format; ?> 2,550 Driver Cost + <?= $global_currency_format; ?> 100 Toll Charge + <?= $global_currency_format; ?> 100 Parking Charge)</small></h6>
																				</td>
																				<td>
																					<h6 class="mb-0"><?= $global_currency_format; ?> 31,640 <br /><small>(+ <?= $global_currency_format; ?> 340 Taxes)</small></h6>
																				</td>
																			</tr>
																		</tbody>
																	</table>
																</div>

																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total Vehicle Cost</span>
																	<h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_cost">55,622
																		</span></h6>
																</div>
																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total Vehicle Taxes</span>
																	<h6 class="mb-0"><?= $global_currency_format; ?> <span id="overall_taxes">815
																		</span></h6>
																</div>
															</div>
														</div>
														<div class="d-flex justify-content-between  px-3 py-3" style="background-color: #f2f2f2;">
															<h5 class="text-heading fw-bold mb-0">Grand Vehicle Total </h5>
															<h5 class="mb-0 fw-bold"><?= $global_currency_format; ?> <span id="overall_hotel_cost">56,437
																</span></h5>
														</div>
													</div>

												</div>

												<div class="divider mt-5 mb-4">
													<div class="divider-text">
														<i class="ti ti-map-2 ti-sm text-primary"></i>
													</div>
												</div>

												<div class="row mt-3 justify-content-center">
													<div class="col-md-12">
														<div class="">
															<h5 class="card-header p-0 mb-2 text-uppercase">Overall Cost</h5>
															<div class="order-calculations">

																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total for The Hotspot</span>
																	<h6 class="mb-0">₹ <span id="gross_total_package">3,000</span></h6>
																</div>
																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total for The Hotel</span>
																	<h6 class="mb-0">₹ <span id="gross_total_package">9,400</span></h6>
																</div>
																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Total for The Vehicle</span>
																	<h6 class="mb-0">₹ <span id="gross_total_package">56,437</span></h6>
																</div>

																<hr />

																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">Gross Total for The Package</span>
																	<h6 class="mb-0">₹ <span id="gross_total_package">68,837</span></h6>
																</div>

																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading">GST @ 5 % On The total Package
																	</span>
																	<h6 class="mb-0">₹ <span id="gst_total_package">500</span></h6>
																</div>

																<div class="d-flex justify-content-between mb-2">
																	<span class="text-heading fw-bold">Nett Payable To Doview
																		Holidays India Pvt ltd</span>
																	<h6 class="mb-0 fw-bold">₹ <span id="net_total_package">69,337</span></h6>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div class="d-flex justify-content-between">
													<div class="demo-inline-spacing">
														<button type="button" class="btn rounded-pill btn-google-plus waves-effect waves-light">
															<i class="tf-icons ti ti-mail ti-xs me-1"></i> Share Via Email
														</button>
													</div>
													<div class="demo-inline-spacing">
														<button type="button" class="btn btn-primary waves-effect waves-light me-0">
															<span class="ti-xs ti ti-check me-1"></span>Confirm
														</button>
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
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
	</div>
	<!-- / Layout wrapper -->

	<div class="modal fade" id="confirmALTERDAYINFODATA" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-simple modal-enable-otp modal-dialog-centered">
			<div class="modal-content">
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

	<!-- Core JS -->
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
			$(".form-select").selectize();
		});

		function editITINERARYHOTELBYROW(HOTEL_DETAILS_ID) {
			$('.hotel_label_' + HOTEL_DETAILS_ID).addClass('d-none');
			$('.hotel_edit_btn_' + HOTEL_DETAILS_ID).addClass('d-none');

			$('.hotel_text_' + HOTEL_DETAILS_ID).removeClass('d-none');
			$('.hotel_update_btn_' + HOTEL_DETAILS_ID).removeClass('d-none');
		}

		function updateITINERARYHOTELBYROW(HOTEL_DETAILS_ID) {
			$('.hotel_label_' + HOTEL_DETAILS_ID).removeClass('d-none');
			$('.hotel_edit_btn_' + HOTEL_DETAILS_ID).removeClass('d-none');

			$('.hotel_text_' + HOTEL_DETAILS_ID).addClass('d-none');
			$('.hotel_update_btn_' + HOTEL_DETAILS_ID).addClass('d-none');
		}
	</script>
</body>

</html>